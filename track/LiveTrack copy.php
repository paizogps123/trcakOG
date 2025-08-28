<?

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

<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Powered By Paizo Gps</title>
	<link type="text/css" href="../theme/jquery-ui.css?v=<? echo $gsValues['VERSION_ID']; ?>" rel="Stylesheet" />
	<link type="text/css" href="../theme/ui.jqgrid.css?v=<? echo $gsValues['VERSION_ID']; ?>" rel="Stylesheet" />
	<link type="text/css" href="../theme/style.css?v=<? echo $gsValues['VERSION_ID']; ?>" rel="Stylesheet" />

	<link type="text/css" href="../theme/leaflet/leaflet.css?v=<? echo $gsValues['VERSION_ID']; ?>" rel="Stylesheet" />
	<?
	if ($gsValues['MAP_GOOGLE'] == 'true') {
		if ($gsValues['MAP_GOOGLE_KEY'] == '') {
			echo '<script src="' . $gsValues['HTTP_MODE'] . '://maps.google.com/maps/api/js"></script>';
		} else {
			echo '<script src="' . $gsValues['HTTP_MODE'] . '://maps.google.com/maps/api/js?key=' . $gsValues['MAP_GOOGLE_KEY'] . '"></script>';
		}
	}
	?>

	<?
	if ($gsValues['MAP_YANDEX'] == 'true') {
		echo '<script src="' . $gsValues['HTTP_MODE'] . '://api-maps.yandex.ru/2.0/?load=package.map&lang=ru-RU"></script>';
	}
	?>

	<script type="text/javascript" src="../js/leaflet/leaflet.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>

	<?
	if ($gsValues['MAP_MAPBOX'] == 'true') {
		echo '<script src="' . $gsValues['HTTP_MODE'] . '://api.mapbox.com/mapbox.js/v3.0.1/mapbox.js"></script>';
	}
	?>

	<script type="text/javascript" src="../js/es6-promise.min.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script>ES6Promise.polyfill();</script>

	<script type="text/javascript" src="../js/leaflet/tile/google.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script type="text/javascript" src="../js/leaflet/tile/bing.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script type="text/javascript" src="../js/leaflet/tile/yandex.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script type="text/javascript" src="../js/leaflet/marker.rotate.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>

	<script type="text/javascript" src="../js/jquery-2.1.4.min.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script type="text/javascript"
		src="../js/jquery-migrate-1.2.1.min.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script type="text/javascript" src="../js/jquery.jqGrid.locale.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script type="text/javascript" src="../js/jquery.jqGrid.min.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>

	<script type="text/javascript" src="../js/moment.min.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>

	<script type="text/javascript" src="../js/gs.config.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>
	<script type="text/javascript" src="../js/gs.common.js?v=<? echo $gsValues['VERSION_ID']; ?>"></script>


	<script src="https://unpkg.com/leaflet-rotate@0.2.8/dist/leaflet-rotate.js"></script>
	<script src="https://unpkg.com/leaflet-rotatedmarker@0.2.0/leaflet.rotatedMarker.js"></script>
	<script>
		var key = "<? echo $key; ?>";
		var imei = "<? echo $imei; ?>";
		// vars
		var la = [];
		var map;
		var mapLayers = new Array();
		var mapMarkerIcons = new Array();
		var mapPopup;
		var timer_objectFollow;
		var objectsData = new Array();
		var settingsValuesUser = new Array();


		function load() {
			loadLanguageAPI();
			settingsLoad('server');
			initMap();
			initGui();
			objectFollow('<? echo $imei; ?>');
			var load2 = setTimeout("load2()", 2000);
		}

		function load2() {
			document.getElementById("loading_panel").style.display = "none";
		}

		function unload() {

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
					// shedule next object reload
					timer_objectFollow = setTimeout("objectFollow('" + imei + "');", gsValues['map_refresh'] * 1000);
				},
				success: function (result) {
					// Code Done By Vetrivel.N :P 
					// Get Out Of My Code :) 
					if (result["type"] == "Success") {
						objectRemoveFromMap();
						objectAddToMap(result);
					}
					else {
						window.location.href = "https://paizogps.in/track/error.php";
					}
					// shedule next object reload
					timer_objectFollow = setTimeout("objectFollow('" + imei + "');", gsValues['map_refresh'] * 1000);
				}
			});
		}

		function objectAddToMap(result) {
			// get data


			if (result['type'] == 'Success') {
				//var name = result['name'];
				var name = imei;
				var lat = result['lat'];
				var lng = result['lng'];
				var altitude = result['altitude'];
				var angle = result['angle'];
				var speed = result['speed'];
				var dt_tracker = result['dt_tracker'];
				var params = false;
			}
			else {
				window.location.href = "https://paizogps.in/track/error.php";
			}

			// get icon zoom level
			var zoom = 1;

			// rotate marker only if icon is arrow
			var iconAngle = angle;


			//marker
			var icon = mapMarkerIcons['arrow_green'];

			if (result['acc'] == "0")
				icon = mapMarkerIcons['arrow_red'];
			else
				icon = mapMarkerIcons['arrow_green'];

			var marker = L.marker([lat, lng], { icon: icon, iconAngle: iconAngle, size: new google.maps.Size(16, 16) });
			// marker.setRotationAngle(angle);
			var label = name + " (" + speed + " " + la["UNIT_SPEED"] + ")";
			marker.bindTooltip(label, { noHide: true, offset: [20 * zoom, -12], direction: 'right' });
			// map.setView([lat, lng], 19);
			// map.setView([lat, lng]);
			map.panTo([lat, lng]);
			// map.setBearing(angle);

			// set click event
			marker.on('click', function (e) {

				{
					geocoderGetAddress(lat, lng, function (responce) {
						var address = responce;
						var position = urlPosition(lat, lng);

						var text = '<table>\
							<tr><td><strong>' + la['OBJECT'] + ':</strong></td><td>' + name + '</td></tr>\
							<tr><td><strong>' + la['ADDRESS'] + ':</strong></td><td>' + address + '</td></tr>\
							<tr><td><strong>' + la['POSITION'] + ':</strong></td><td>' + position + '</td></tr>\
							<tr><td><strong>' + la['ALTITUDE'] + ':</strong></td>\
							<td>' + altitude + '</td></tr>\
							<tr><td><strong>' + la['ANGLE'] + ':</strong></td><td>' + angle + ' &deg;</td></tr>\
							<tr><td><strong>' + la['SPEED'] + ':</strong></td>\
							<td>' + speed + '</td></tr>\
							<tr><td><strong>' + la['TIME'] + ':</strong></td><td>' + dt_tracker + '</td></tr>';



						text += '</table>';

						addPopupToMap(lat, lng, [0, 0], text);
					});
				}
			});


			mapLayers['realtime'].addLayer(marker);
			marker.setRotationAngle(iconAngle);

		}

		function objectRemoveFromMap() {
			mapLayers['realtime'].clearLayers();
		}



		function initMap() {
			map = L.map('map_follow', { minZoom: gsValues['map_min_zoom'], maxZoom: gsValues['map_max_zoom'], editable: true, zoomControl: false, rotate: true, rotateControl: { closeOnZeroBearing: false }, bearing: 0, touchRotate: true });

			// add map layers
			initSelectList('map_layer_list');

			// define map layers
			defineMapLayers();

			// define layers	
			mapLayers['realtime'] = L.layerGroup();
			mapLayers['realtime'].addTo(map);

			// add map controls
			map.addControl(L.control.zoom({ zoomInText: '', zoomOutText: '', zoomInTitle: la['ZOOM_IN'], zoomOutTitle: la['ZOOM_OUT'] }));

			// set map type
			var map_layer = '<? echo $map_layer; ?>';
			switchMapLayer(map_layer);

			map.setView([0, 0], 15);
			// console.log(mapLayers['realtime'])
		}

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



		function initSelectList(list) {
			switch (list) {
				case "map_layer_list":
					var select = document.getElementById('map_layer');
					select.options.length = 0; // clear out existing items

					if (gsValues['map_osm']) {
						select.options.add(new Option('OSM Map', 'osm'));
					}

					if (gsValues['map_bing']) {
						select.options.add(new Option('Bing Road', 'broad'));
						select.options.add(new Option('Bing Aerial', 'baer'));
						select.options.add(new Option('Bing Hybrid', 'bhyb'));
					}

					if (gsValues['map_google']) {
						select.options.add(new Option('Google Streets', 'gmap'));
						select.options.add(new Option('Google Satellite', 'gsat'));
						select.options.add(new Option('Google Hybrid', 'ghyb'));
						select.options.add(new Option('Google Terrain', 'gter'));
					}

					if (gsValues['map_mapbox']) {
						select.options.add(new Option('Mapbox Streets', 'mbmap'));
						select.options.add(new Option('Mapbox Satellite', 'mbsat'));
					}

					if (gsValues['map_yandex']) {
						select.options.add(new Option('Yandex', 'yandex'));
					}

					for (var i = 0; i < gsValues['map_custom'].length; i++) {
						var layer_id = gsValues['map_custom'][i].layer_id;
						var name = gsValues['map_custom'][i].name;

						select.options.add(new Option(name, layer_id));
					}
					break;
			}
		}

		function loadObjectMapMarkerIcons() {

		}


		function addPopupToMap(lat, lng, offset, text) {
			mapPopup = L.popup({ offset: offset }).setLatLng([lat, lng]).setContent(text).openOn(map);
		}

		function settingsLoad(type) {
			switch (type) {
				case "server":
					var data = {
						cmd: 'load_server_values'
					};
					$.ajax({
						type: "POST",
						url: "fn_settings.php",
						data: data,
						dataType: 'json',
						cache: false,
						async: false,
						success: function (result) {
							gsValues['map_custom'] = result['map_custom'];
							gsValues['map_osm'] = strToBoolean(result['map_osm']);
							gsValues['map_bing'] = strToBoolean(result['map_bing']);
							gsValues['map_google'] = strToBoolean(result['map_google']);
							gsValues['map_google_traffic'] = strToBoolean(result['map_google_traffic']);
							gsValues['map_mapbox'] = strToBoolean(result['map_mapbox']);
							gsValues['map_yandex'] = strToBoolean(result['map_yandex']);
							gsValues['map_bing_key'] = result['map_bing_key'];
							gsValues['map_mapbox_key'] = result['map_mapbox_key'];
							gsValues['map_lat'] = result['map_lat'];
							gsValues['map_lng'] = result['map_lng'];
							gsValues['map_zoom'] = result['map_zoom'];
							gsValues['map_layer'] = result['map_layer'];
						}
					});
					break;
			}
		}

		function openFullscreen() {
			const elem = document.documentElement; // Or any specific element you want fullscreen

			if (elem.requestFullscreen) {
				elem.requestFullscreen();
			} else if (elem.webkitRequestFullscreen) { // Safari
				elem.webkitRequestFullscreen();
			} else if (elem.msRequestFullscreen) { // IE11
				elem.msRequestFullscreen();
			}
		}

		function closeFullscreen() {
			if (document.exitFullscreen) {
				document.exitFullscreen();
			} else if (document.webkitExitFullscreen) { // Safari
				document.webkitExitFullscreen();
			} else if (document.msExitFullscreen) { // IE11
				document.msExitFullscreen();
			}
		}

	</script>
</head>

<body onload="load()" onUnload="unload()">
	<div id="loading_panel">
		<div class="table">
			<div class="table-cell center-middle">
				<img style="border:0px" src="https://paizogps.in/theme/images/logo.png" />
				<!-- 	<div class="loader">
					<? echo $la['LOADING_PLEASE_WAIT']; ?>
					<span></span>
				</div>
				 -->
			</div>
		</div>
	</div>

	<div id="map_follow"></div>
	<div class="object-follow-control">
		<div class="row4">

			<div class="margin-right-3"><button onclick="openFullscreen()">Go Fullscreen</button></div>

			<div class="margin-right-3"><input id="follow" type="checkbox" class="checkbox" checked /></div>
			<div class="margin-right-3">
				<? echo $la['FOLLOW']; ?>
			</div>
			<div class="margin-left-3"><select id="map_layer" onChange="switchMapLayer($(this).val());"></select></div>
		</div>
	</div>
	<div id="side_panel_follow">
		<div id="side_panel_follow_data_list">
			<table id="side_panel_follow_data_list_grid"></table>
		</div>
	</div>
</body>

</html>