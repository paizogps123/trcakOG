<?php
include('../init.php');
include('../func/fn_common.php');
include('fn_settings.php');

global $ms, $gsValues;

$imei = @$_GET['VehicleNo'];
$key = @$_GET['tripkey'];
$map = 2;
$map_layer = "gmap";

loadLanguage("english", "km,l,c");
?>
<!DOCTYPE html>
<html>

<head>
  <title>Leaflet-Rotate — Thanjavur Demo</title>
  <meta charset="utf-8">
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
  <title>Powered By Paizo Gps</title>
  <link type="text/css" href="../theme/jquery-ui.css?v=<?php echo $gsValues['VERSION_ID']; ?>" rel="stylesheet" />

  <!-- Leaflet core -->
  <script src="https://unpkg.com/leaflet@1.9/dist/leaflet-src.js"></script>
  <link href="https://unpkg.com/leaflet@1.9/dist/leaflet.css" rel="stylesheet">

  <!-- Leaflet-Rotate plugin -->
  <script src="https://unpkg.com/leaflet-rotate@0.2.8/dist/leaflet-rotate-src.js"></script>

  <?php
  if ($gsValues['MAP_GOOGLE'] == 'true') {
    $api = $gsValues['MAP_GOOGLE_KEY'] === ''
      ? 'maps.google.com/maps/api/js'
      : 'maps.google.com/maps/api/js?key=' . $gsValues['MAP_GOOGLE_KEY'];
    echo '<script src="' . $gsValues['HTTP_MODE'] . '://' . $api . '"></script>';
  }
  ?>

  <script src="../js/es6-promise.min.js?v=<?php echo $gsValues['VERSION_ID']; ?>"></script>
  <script>ES6Promise.polyfill();</script>
  <script src="../js/leaflet/tile/google.js?v=<?php echo $gsValues['VERSION_ID']; ?>"></script>
  <script src="../js/jquery-2.1.4.min.js?v=<?php echo $gsValues['VERSION_ID']; ?>"></script>

  <script src="../js/gs.config.js?v=<?php echo $gsValues['VERSION_ID']; ?>"></script>
  <script src="../js/gs.common.js?v=<?php echo $gsValues['VERSION_ID']; ?>"></script>

  <style>
    /* make the whole document stretch to full viewport height */
    html,
    body {
      height: 100%;
      margin: 0;
      /* remove default body margin so the map touches the edges */
    }

    /* the map now fills the page */
    #map {
      width: 100%;
      height: 100%;
      /* key change — full height */
      border: 1px solid #ccc;
    }
  </style>
</head>

<body onload="load()">
  <div id="map"></div>

  <script>
    var key = "<? echo $key; ?>";
    var imei = "<? echo $imei; ?>";
    var timer_objectFollow;
    let vehicleMarker = null;
    var la = [];
    var mapMarkerIcons = new Array();
    function load() {
      loadLanguageAPI();
      initGui();
      objectFollow('<? echo $imei; ?>');
      // var load2 = setTimeout("load2()", 2000);
    }
    /* ------------------------------------------------------------------ */
    /*  Base layers                                                       */
    /* ------------------------------------------------------------------ */
    const esri = L.tileLayer(
      'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
      maxZoom: 24,
      maxNativeZoom: 18,
      attribution: 'Tiles © Esri'
    });

    const osm = L.tileLayer(
      'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 24,
      maxNativeZoom: 19,
      attribution: '&copy; OpenStreetMap contributors'
    });
    const Google_Url = "http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}";
    const gmap = L.tileLayer(Google_Url, { maxZoom: 21, subdomains: ["mt0", "mt1", "mt2", "mt3"] });

    /* ------------------------------------------------------------------ */
    /*  Map initialisation                                                */
    /* ------------------------------------------------------------------ */
    const THANJAVUR = [10.7870, 79.1391];

    const map = L.map('map', {
      // center: THANJAVUR,
      zoom: 14,
      layers: [gmap],
      rotate: true,
      rotateControl: { closeOnZeroBearing: false },
      bearing: 0,
      touchRotate: true
    });

    L.control.layers(
      { 'Google': gmap,'Osm': osm, 'Osm Satellite': esri },
      null, { collapsed: false }
    ).addTo(map);

    // L.marker(THANJAVUR, { title: 'Thanjavur' })
    //   .addTo(map)
    //   .bindPopup('<strong>Thanjavur</strong><br>Tamil Nadu, India')
    //   .openPopup();

    // const path = [
    //   [10.7870, 79.1391],   // start
    //   [10.7895, 79.1400],
    //   [10.7920, 79.1412],
    //   [10.7938, 79.1430],
    //   [10.7910, 79.1455]
    // ];

    // let step = 0;

    // /* 2.  Create marker at the first point */
    // const marker = L.marker(path[0], { title: 'Vehicle' }).addTo(map);

    // /* 3.  Function to move the marker */
    // function moveMarker() {
    //   step = (step + 1) % path.length;      // loop through the array
    //   const nextPos = path[step];

    //   marker.setLatLng(nextPos);            // move the marker
    //   map.panTo(nextPos, { animate: true }); // keep it centred (optional)
    // }

    // /* 4.  Start the 10-second interval */
    // setInterval(moveMarker, 10_000);        // 10 000 ms = 10 s

    function initGui() {


      // map marker icons
      var zoom = 1;

      var icon_size_x = 90 * zoom;
      var icon_size_y = 90 * zoom;
      var icon_anc_x = 14 * zoom;
      var icon_anc_y = 14 * zoom;
      var icon_anc_x = 45 * zoom;
      var icon_anc_y = 45 * zoom;

      mapMarkerIcons['arrow_black'] = L.icon({
        iconUrl: '../img/markers/arrow-black.svg',
        iconSize: [icon_size_x, icon_size_y], // size of the icon
        iconAnchor: [icon_anc_x, icon_anc_y], // point of the icon which will correspond to marker's location
        popupAnchor: [0, 0] // point from which the popup should open relative to the iconAnchor
      });

      mapMarkerIcons['arrow_blue'] = L.icon({
        iconUrl: '../img/markers/arrow-blue.svg',
        iconSize: [icon_size_x, icon_size_y], // size of the icon
        iconAnchor: [icon_anc_x, icon_anc_y], // point of the icon which will correspond to marker's location
        popupAnchor: [0, 0] // point from which the popup should open relative to the iconAnchor
      });

      mapMarkerIcons['arrow_green'] = L.icon({
        iconUrl: '../img/markers/arrow-green.svg',
        iconSize: [icon_size_x, icon_size_y], // size of the icon
        iconAnchor: [icon_anc_x, icon_anc_y], // point of the icon which will correspond to marker's location
        popupAnchor: [0, 0] // point from which the popup should open relative to the iconAnchor
      });

      mapMarkerIcons['arrow_grey'] = L.icon({
        iconUrl: '../img/markers/arrow-grey.svg',
        iconSize: [icon_size_x, icon_size_y], // size of the icon
        iconAnchor: [icon_anc_x, icon_anc_y], // point of the icon which will correspond to marker's location
        popupAnchor: [0, 0] // point from which the popup should open relative to the iconAnchor
      });

      mapMarkerIcons['arrow_orange'] = L.icon({
        iconUrl: '../img/markers/arrow-orange.svg',
        iconSize: [icon_size_x, icon_size_y], // size of the icon
        iconAnchor: [icon_anc_x, icon_anc_y], // point of the icon which will correspond to marker's location
        popupAnchor: [0, 0] // point from which the popup should open relative to the iconAnchor
      });

      mapMarkerIcons['arrow_purple'] = L.icon({
        iconUrl: '../img/markers/arrow-purple.svg',
        iconSize: [icon_size_x, icon_size_y], // size of the icon
        iconAnchor: [icon_anc_x, icon_anc_y], // point of the icon which will correspond to marker's location
        popupAnchor: [0, 0] // point from which the popup should open relative to the iconAnchor
      });

      mapMarkerIcons['arrow_red'] = L.icon({
        iconUrl: '../img/markers/arrow-red.svg',
        iconSize: [56, 28], // size of the icon
        iconAnchor: [28, 14], // point of the icon which will correspond to marker's location
        popupAnchor: [0, 0] // point from which the popup should open relative to the iconAnchor
      });


      mapMarkerIcons['arrow_yellow'] = L.icon({
        iconUrl: '../img/markers/arrow-yellow.svg',
        iconSize: [icon_size_x, icon_size_y], // size of the icon
        iconAnchor: [icon_anc_x, icon_anc_y], // point of the icon which will correspond to marker's location
        popupAnchor: [0, 0] // point from which the popup should open relative to the iconAnchor
      });
    }


    function objectFollow(imei) {
      clearTimeout(timer_objectFollow);

      var data = {
        cmd: 'DeviceTrack',
        key: key,
        deviceid: imei
      };

      $.ajax({
        type: "POST",
        url: "https://paizogps.in/api/LiveTrack.php",
        data: data,
        dataType: 'json',
        cache: false,
        error: function (statusCode, errorThrown) {
          timer_objectFollow = setTimeout("objectFollow('" + imei + "');", gsValues['map_refresh'] * 1000);
        },
        success: function (result) {
          // Code Done By Vetrivel.N :P 
          if (result["type"] == "Success") {
            updateVehicle(result);
          }
          else {
            window.location.href = "https://paizogps.in/track/error.php";
          }
          timer_objectFollow = setTimeout("objectFollow('" + imei + "');", gsValues['map_refresh'] * 1000);
        }
      });
    }

    function updateVehicle(result) {
      const lat = +result.lat;
      const lng = +result.lng;
      const angle = +result.angle;          // heading
      const speed = +result.speed;
      const altitude = +result.altitude;
      const dtTracker = result.dt_tracker;
      const acc = result.acc;            // ignition on/off (0/1)

      /* choose icon colour by ACC status */
      const iconKey = acc === '0' ? 'arrow_red' : 'arrow_green';
      const icon = mapMarkerIcons[iconKey];

      if (!vehicleMarker) {
        /* ----- first update → create marker ------------------- */
        vehicleMarker = L.marker([lat, lng], {
          icon: icon,
          iconAngle: angle      // works with leaflet-rotated-marker
        }).addTo(map);

        /* click handler → show rich popup */
        vehicleMarker.on('click', () => showPopup({
          lat, lng, angle, speed, altitude, dtTracker
        }));
      } else {
        /* ----- subsequent updates → just move / rotate -------- */
        vehicleMarker.setLatLng([lat, lng]);
        // vehicleMarker.setRotationAngle(angle);     // requires rotated-marker plugin
        vehicleMarker.setIcon(icon);               // green ↔ red when ACC flips
      }

      /* keep the tooltip fresh */
      vehicleMarker.bindTooltip(
        `${imei} (${speed} ${la.UNIT_SPEED})`,
        { noHide: true, offset: [20, -12], direction: 'right' }
      );

      /* keep the map centred on the vehicle (optional) */
      map.panTo([lat, lng]);
    }

    /* ----------------------------------------------------------------------------
       Build and open the popup on demand (runs inside the click-handler).
       --------------------------------------------------------------------------*/
    function showPopup({ lat, lng, angle, speed, altitude, dtTracker }) {
      geocoderGetAddress(lat, lng, address => {
        const html = `
        <table>
          <tr><td><strong>${la.OBJECT}:</strong></td><td>${imei}</td></tr>
          <tr><td><strong>${la.ADDRESS}:</strong></td><td>${address}</td></tr>
          <tr><td><strong>${la.POSITION}:</strong></td><td>${urlPosition(lat, lng)}</td></tr>
          <tr><td><strong>${la.ALTITUDE}:</strong></td><td>${altitude}</td></tr>
          <tr><td><strong>${la.ANGLE}:</strong></td><td>${angle}&deg;</td></tr>
          <tr><td><strong>${la.SPEED}:</strong></td><td>${speed}</td></tr>
          <tr><td><strong>${la.TIME}:</strong></td><td>${dtTracker}</td></tr>
        </table>`;
        addPopupToMap(lat, lng, [0, 0], html);
      });
    }

  </script>
</body>

</html>