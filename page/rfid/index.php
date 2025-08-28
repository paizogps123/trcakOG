<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <title>RFID Report Filter</title>

  <!-- ✅ jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- ✅ Select2 CSS + JS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <!-- ✅ SheetJS for Excel export -->
  <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }

    .form-inline {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      align-items: center;
      margin-bottom: 20px;
    }

    .form-inline label {
      font-weight: bold;
    }

    .form-inline input,
    .form-inline select {
      padding: 6px;
      font-size: 14px;
    }

    select,
    input[type="datetime-local"],
    input[type="text"] {
      width: 175px;
    }

    #imeiSelect {
      min-width: 275px;
    }

    .btn {
      padding: 8px 16px;
      background-color: #3498db;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .btn:hover {
      background-color: #2980b9;
    }

    h3 {
      margin-top: 30px;
    }

    table.report {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 30px;
    }

    table.report th,
    table.report td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: center;
    }

    table.report th {
      background: #f2f2f2;
    }
  </style>
</head>

<body>

  <h2>RFID Report Filter</h2>

  <div class="form-inline">
    <label for="imeiSelect">Vehicles:</label>
    <select id="imeiSelect" multiple></select>

    <label for="from">From:</label>
    <input type="datetime-local" id="from">

    <label for="to">To:</label>
    <input type="datetime-local" id="to">

    <label for="rfid">RFID:</label>
    <input type="text" id="rfid" placeholder="Search RFID..." oninput="filterByRfid()">

    <button class="btn" id="generateBtn">Generate</button>
       <button class="btn" id="downloadBtn" disabled>Download</button>
  </div>

  <div id="report"></div>

  <script>
    // helpers to format dates
    function toInputDateTime(dt) {
      const pad = n => String(n).padStart(2, '0');
      return `${dt.getFullYear()}-${pad(dt.getMonth()+1)}-${pad(dt.getDate())}T${pad(dt.getHours())}:${pad(dt.getMinutes())}`;
    }
    function toServerDateTime(s) {
      // convert "YYYY-MM-DDTHH:MM" -> "YYYY-MM-DD HH:MM:00"
      return s.replace('T', ' ') + ':00';
    }

    let lastReportData = null;

    $(function () {
      // initialize date inputs to today
      const now = new Date();
      const start = new Date(now); start.setHours(0,0,0,0);
      const end   = new Date(now); end.setHours(23,59,0,0);
      $('#from').val(toInputDateTime(start));
      $('#to').val(toInputDateTime(end));

      // init Select2
      $('#imeiSelect').select2({ placeholder: "Select vehicles", allowClear: true });
      fetchVehicleList();

      // generate button
      $('#generateBtn').on('click', loadReport);
      $('#downloadBtn').click(downloadExcel);
    });

    function fetchVehicleList() {
            fetch('https://paizogps.in/func/fn_settings.objects.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({ cmd: 'load_object_data' })
            })
                .then(res => res.json())
                .then(data => {
                    if (typeof data === 'object' && data !== null) {
                        Object.entries(data).forEach(([imei, value]) => {
                            const label = `${value.name} (${imei})`;
                            const option = new Option(label, imei, false, false);
                            $('#imeiSelect').append(option);
                        });
                        $('#imeiSelect').trigger('change');
                    } else {
                        console.error("Unexpected response format:", data);
                    }
                })
                .catch(err => {
                    console.error("Error loading vehicles:", err);
                });
        }

    function loadReport() {
      const imeis = $('#imeiSelect').val() || [];
      const from  = $('#from').val();
      const to    = $('#to').val();
      const rfid  = $('#rfid').val().trim();

       $('#downloadBtn').prop('disabled', true);
      
      if ( !from || !to) {
        alert("Please select date range to search.");
        return;
      }

      if (!imeis.length && !rfid) {
        alert("Please select at least one vehicle or rfid card no to search.");
        return;
      }

      $('#report').html('<p><em>Loading report…</em></p>');

      const params = $.param({
        imeis: imeis.join(','),
        from:  toServerDateTime(from),
        to:    toServerDateTime(to),
        rfid:  rfid
      });

      fetch('../rfid.php?' + params)
        .then(res => {
          if (!res.ok) throw new Error(res.statusText);
          return res.json();
        })
        .then(data => {lastReportData = data,renderReport(data), $('#downloadBtn').prop('disabled', false)})
        .catch(err => {
          $('#report').html(`<p style="color:red;">Error: ${err.message}</p>`);
        });
    }

    function renderReport(data) {
      const $out = $('#report').empty();
      const dates = Object.keys(data).sort();
      if (!dates.length) {
        return $out.html('<p>No records found.</p>');
      }

      dates.forEach(date => {
        const entries = Object.values(data[date]);
        $out.append(`<h3>Date: ${date}</h3>`);

        let html = '<table class="report"><thead><tr>' +
          '<th>SN</th><th>Vehicle No</th><th>RFID Tag</th><th>Date</th>' +
          '<th>Swipe 1</th><th>Loc 1</th>' +
          '<th>Swipe 2</th><th>Loc 2</th>' +
          '<th>Swipe 3</th><th>Loc 3</th>' +
          '<th>Swipe 4</th><th>Loc 4</th>' +
          '</tr></thead><tbody>';

        entries.forEach((e, i) => {
          html += `<tr>
            <td>${i+1}</td>
            <td>${e.vehicle_no}</td>
            <td>${e.rfid}</td>
            <td>${e.date}</td>`;

          // up to 4 swipes
          for (let j = 0; j < 4; j++) {
            if (e.swipes[j]) {
              html += `<td>${e.swipes[j].time}</td><td>${e.swipes[j].loc}</td>`;
            } else {
              html += '<td></td><td></td>';
            }
          }
          html += '</tr>';
        });

        html += '</tbody></table>';
        $out.append(html);
      });
    }


     function downloadExcel(){
      if (!lastReportData) return;
      const wb = XLSX.utils.book_new();

      Object.keys(lastReportData).sort().forEach(date=>{
        const entries = Object.values(lastReportData[date]);
        // Build an array-of-arrays for this sheet
        const aoa = [
          ['SN','Vehicle No','RFID Tag','Date','Swipe 1','Loc 1','Swipe 2','Loc 2','Swipe 3','Loc 3','Swipe 4','Loc 4']
        ];
        entries.forEach((e,i)=>{
          const row = [i+1, e.vehicle_no, e.rfid, date];
          for(let j=0;j<4;j++){
            if(e.swipes[j]){
              row.push(e.swipes[j].time, e.swipes[j].loc);
            } else {
              row.push('', '');
            }
          }
          aoa.push(row);
        });

        const ws = XLSX.utils.aoa_to_sheet(aoa);
        XLSX.utils.book_append_sheet(wb, ws, date);
      });

      XLSX.writeFile(wb, 'rfid_report.xlsx');
    }

     function filterByRfid() {
            const filterText = $('#rfid').val().trim().toLowerCase();

            $('#report table').each(function () {
                $(this).find('tr').each(function (index) {
                    if (index === 0) return; // skip header row
                    const rfidText = $(this).find('td').eq(2).text().trim().toLowerCase();
                    $(this).toggle(rfidText.includes(filterText));
                });
            });
        }
        
  </script>

</body>

</html>
