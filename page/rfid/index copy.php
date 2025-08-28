<!DOCTYPE html>
<html>

<head>
    <title>RFID Report Filter</title>

    <!-- ✅ jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- ✅ Select2 CSS + JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
            width: 200px;
        }

        #imeiSelect {
            min-width: 300px;
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

        #report {
            margin-top: 30px;
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

        <button class="btn" onclick="loadReport()">Generate</button>
    </div>

    <div id="report"></div>

    <script>
            const today = new Date();

    // Format function: returns YYYY-MM-DDTHH:MM
    function formatDateTime(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }

    // Create 12:00 AM today
    const fromDate = new Date(today);
    fromDate.setHours(0, 0, 0, 0); // 12:00 AM

    // Create 11:59 PM today
    const toDate = new Date(today);
    toDate.setHours(23, 59, 0, 0); // 11:59 PM

    // Set input values
    document.getElementById('from').value = formatDateTime(fromDate);
    document.getElementById('to').value = formatDateTime(toDate);

        $(document).ready(function () {
            $('#imeiSelect').select2({
                placeholder: "Select vehicles",
                allowClear: true
            });

            fetchVehicleList();
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

        function formatDateTime(input) {
            const dt = new Date(input);
            return dt.getFullYear() + '-' +
                String(dt.getMonth() + 1).padStart(2, '0') + '-' +
                String(dt.getDate()).padStart(2, '0') + ' ' +
                String(dt.getHours()).padStart(2, '0') + ':' +
                String(dt.getMinutes()).padStart(2, '0') + ':00';
        }

        function loadReport() {
            const imeis = $('#imeiSelect').val();
            const from = $('#from').val();
            const to = $('#to').val();
            const rfid = $('#rfid').val();

            if ( !from || !to) {
                alert("Please select vehicles, from date, and to date.");
                return;
            }

            const url = `../rfid.php?imeis=${encodeURIComponent(imeis.join(','))}&from=${encodeURIComponent(formatDateTime(from))}&to=${encodeURIComponent(formatDateTime(to))}`;

            $('#report').html('<p><em>Loading report...</em></p>');

            fetch(url)
                .then(res => res.text())
                .then(html => {
                    $('#report').html(html);
                    filterByRfid(); // Filter after content loads
                })
                .catch(err => {
                    $('#report').html(`<p style="color:red;">Error loading report: ${err}</p>`);
                });
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