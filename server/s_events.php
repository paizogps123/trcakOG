<?
// $loc - location data array
// $ed - event data array
// $ud - user data array
// $od - object data array

function check_events($loc, $loc_events, $params_events, $service_events, $rfidevent = '')
{

	if(@$loc["alarm"] == "rfid" && @$loc["type"] == "param"){
		return;
	}
	global $ms;
	$q = "SELECT gs_objects.*, gs_user_objects.*
				FROM gs_objects
				INNER JOIN gs_user_objects ON gs_objects.imei = gs_user_objects.imei
				WHERE gs_user_objects.imei='" . $loc['imei'] . "'";

	$r = mysqli_query($ms, $q);
	while ($od = mysqli_fetch_array($r)) {
		// get user data
		$q2 = "SELECT * FROM `gs_users` WHERE `id`='" . $od['user_id'] . "'";
		$r2 = mysqli_query($ms, $q2);
		$ud = mysqli_fetch_array($r2);

		// events loop
		$q2 = "SELECT * FROM `gs_user_events` WHERE `user_id`='" . $od['user_id'] . "' AND UPPER(`imei`) LIKE '%" . $loc['imei'] . "%' order by notify_sms desc ";
		$r2 = mysqli_query($ms, $q2);

		while ($ed = mysqli_fetch_array($r2)) {

			if ($ed['active'] == 'true') {
				$loc['type'] = $ed['type'];

				if ($ed['type'] == 'overspeed') {
					event_overspeed($ed, $ud, $od, $loc);
				}
				if ($ed['type'] == 'underspeed') {
					event_underspeed($ed, $ud, $od, $loc);
				}

				// if($rfidevent==true){
				if ($ed['type'] == 'unauthpeople') {
					if (@$loc['rfid'] != '') {
						event_unauthorizedpeople($ed, $ud, $od, $loc);
					}
				}
				// }




				// check for loc events
				if ($loc_events == true) {
					/*
									   $loc['type']=$ed['type'];
									   if ($ed['type'] == 'overspeed')
									   {
									   event_overspeed($ed,$ud,$od,$loc);
									   }
									   if ($ed['type'] == 'underspeed')
									   {
									   event_underspeed($ed,$ud,$od,$loc);
									   }
									   */
					if ($ed['type'] == 'route_in') {
						event_route_in($ed, $ud, $od, $loc);
					}
					if ($ed['type'] == 'route_out') {
						event_route_out($ed, $ud, $od, $loc);
					}
					if ($ed['type'] == 'zone_in') {
						event_zone_in($ed, $ud, $od, $loc);
					}
					if ($ed['type'] == 'zone_out') {
						event_zone_out($ed, $ud, $od, $loc);
					}

					if ($ed['type'] == 'toll_zone_in') {
						event_toll_zone_in($ed, $ud, $od, $loc);
					}
					if ($ed['type'] == 'toll_zone_out') {
						event_toll_zone_out($ed, $ud, $od, $loc);
					}
				}

				// check for params events
				if ($params_events == true) {
					if ($ed['type'] == 'param') {
						event_param($ed, $ud, $od, $loc);
					}

					if ($ed['type'] == 'sensor') {
						event_sensor($ed, $ud, $od, $loc);
					}
				}

				// check for service events
				if ($service_events == true) {
					if (($ed['type'] == 'connyes') || ($ed['type'] == 'connno')) {
						event_connection($ed, $ud, $od, $loc);
					}

					if (($ed['type'] == 'gpsyes') || ($ed['type'] == 'gpsno')) {
						event_gps($ed, $ud, $od, $loc);
					}

					if (($ed['type'] == 'stopped') || ($ed['type'] == 'moving') || ($ed['type'] == 'engidle') || ($ed['type'] == 'aconidle')) {
						event_stopped_moving_engidle($ed, $ud, $od, $loc);
					}
				}

				//code updated by  Vetrivel.NR 6-4-18
				if ($ed['type'] == 'haccelcus') {
					event_onserver_haccel($ed, $ud, $od, $loc);
				}

				if ($ed['type'] == 'hbrakecus') {
					event_onserver_hbrake($ed, $ud, $od, $loc);
				}

				//code updated by  Nandha  18-03-22
				if ($ed['type'] == 'haccel_server') {
					event_server_event_haccel($ed, $ud, $od, $loc);
				}

				if ($ed['type'] == 'hbrake_server') {
					event_server_event_hbrake($ed, $ud, $od, $loc);
				}

				/*
							if (
							($ed['type'] == 'haccel') || ($ed['type'] == 'hbrake')
							//|| ($ed['type'] == 'haccelm') || ($ed['type'] == 'haccell') 
							//|| ($ed['type'] == 'hbrakem')|| ($ed['type'] == 'hbrakel')
							)
							{
								event_tracker_onserver($ed,$ud,$od,$loc);
							}
							*/

				// check for GPS tracker events
				if (!isset($loc['event'])) {
					continue;
				}

				if ($loc['event'] == 'lowpwr') {
					$loc['event'] = 'lowbat';
				}

				//update ud1 event into sos for PlayG102 /102G
				if ($od['device'] == "Play 102G" && $loc['event'] == "ud1") {
					$loc['event'] = 'sos';
				}

				if (($ed['type'] == 'sos') && ($loc['event'] == 'sos')) {
					event_tracker($ed, $ud, $od, $loc);
				}
				if ($ed['type'] == 'triproutedivert') {
					event_routedeviation_GPS($ed, $ud, $od, $loc);
				}

				if (($ed['type'] == 'bracon') && ($loc['event'] == 'bracon')) {
					event_tracker($ed, $ud, $od, $loc);
				}

				if (($ed['type'] == 'bracoff') && ($loc['event'] == 'bracoff')) {
					event_tracker($ed, $ud, $od, $loc);
				}

				if (($ed['type'] == 'mandown') && ($loc['event'] == 'mandown')) {
					event_tracker($ed, $ud, $od, $loc);
				}

				if (($ed['type'] == 'shock') && ($loc['event'] == 'shock')) {
					event_tracker($ed, $ud, $od, $loc);
				}

				if (($ed['type'] == 'tow') && ($loc['event'] == 'tow')) {
					event_tracker($ed, $ud, $od, $loc);
				}

				if (
					(($ed['type'] == 'haccel') && ($loc['event'] == 'haccel'))
					||
					(($ed['type'] == 'haccel') && ($loc['event'] == 'saccel'))
				) {
					event_tracker($ed, $ud, $od, $loc);
				}

				if (
					(($ed['type'] == 'hbrake') && ($loc['event'] == 'hbrake')) ||
					(($ed['type'] == 'hbrake') && ($loc['event'] == 'sbrake'))
				) {
					event_tracker($ed, $ud, $od, $loc);
				}

				if (($ed['type'] == 'hcorn') && ($loc['event'] == 'hcorn')) {
					event_tracker($ed, $ud, $od, $loc);
				}
				if ($ed['user_id'] != 1386) {	// update by nandha (as per conversation with shafi sir sed to disable the WTI powec cut event)

					if (($ed['type'] == 'pwrcut') && ($loc['event'] == 'pwrcut')) {
						event_tracker($ed, $ud, $od, $loc);
					}
				}

				if (
					(($ed['type'] == 'gpsantcut') && ($loc['event'] == 'gpscut'))
					||
					(($ed['type'] == 'gpsantcut') && ($loc['event'] == 'gpsantcut'))
				) {
					event_tracker($ed, $ud, $od, $loc);
				}

				if (($ed['type'] == 'lowdc') && ($loc['event'] == 'lowdc')) {
					event_tracker($ed, $ud, $od, $loc);
				}

				if (($ed['type'] == 'lowbat') && ($loc['event'] == 'lowbat')) {
					event_tracker($ed, $ud, $od, $loc);
				}

				if (($ed['type'] == 'jamming') && ($loc['event'] == 'jamming')) {
					event_tracker($ed, $ud, $od, $loc);
				}

				if (($ed['type'] == 'dtc') && (substr($loc['event'], 0, 3) == 'dtc')) {
					event_tracker($ed, $ud, $od, $loc);
				}

				if (($ed['type'] == 'last_delivery') && ($loc['event'] == 'last_delivery')) {
					event_tracker($ed, $ud, $od, $loc);
				}


				//update by VETRIVEL.NR
				if (
					(($ed['type'] == 'temp_abn') && ($loc['event'] == 'temp_abn'))
					|| (($ed['type'] == 'gpssta') && ($loc['event'] == 'gpssta'))
					|| (($ed['type'] == 'mainpwrsta') && ($loc['event'] == 'mainpwrsta'))
					|| (($ed['type'] == 'lowpwr') && ($loc['event'] == 'lowpwr'))
					|| (($ed['type'] == 'ud1') && ($loc['event'] == 'ud1'))
					|| (($ed['type'] == 'dooropen') && ($loc['event'] == 'dooropen'))
					|| (($ed['type'] == 'ud4') && ($loc['event'] == 'ud4'))
					|| (($ed['type'] == 'ud5') && ($loc['event'] == 'ud5'))
					|| (($ed['type'] == 'crash') && ($loc['event'] == 'crash'))
					|| (($ed['type'] == 'Shake') && ($loc['event'] == 'Shake'))
					|| (($ed['type'] == 'fuelstolen') && ($loc['event'] == 'fuelstolen'))
					|| (($ed['type'] == 'lowfuel') && ($loc['event'] == 'lowfuel'))
					|| (($ed['type'] == 'fueldiscnt') && ($loc['event'] == 'fueldiscnt'))
					|| (($ed['type'] == 'stopovrtime') && ($loc['event'] == 'stopovrtime'))
					|| (($ed['type'] == 'ud2') && ($loc['event'] == 'ud2'))
					|| (($ed['type'] == 'gpsantcut') && ($loc['event'] == 'gps_jammer'))
					|| (($ed['type'] == 'gsm_jammer') && ($loc['event'] == 'gsm_jammer'))
					|| (($ed['type'] == 'removal_alarm') && ($loc['event'] == 'removal_alarm'))
				) {
					//Insert_Issue($loc['imei'],'Event',json_encode($loc),1);
					event_tracker($ed, $ud, $od, $loc);
				}
			}
		}

		//code updated by VETRIVEL.NR

		if ($service_events == false) {
			if ($loc_events == true) {
				check_eventsnew($loc, $od, $ud);
			} else
				fnlog_daily($loc, $od, $ud, 'in event', json_encode($loc_events));
		}

	}
}

//code updated by  Nandha 18-3-22
function event_server_event_haccel($ed, $ud, $od, $loc)
{
	global $ms;
	$event_status = get_event_status($ed['event_id'], $loc['imei']);

	$ed['event_desc'] = $ed['name'];
	$pre_data_sec = 45;
	$st_date = date('Y-m-d H:i:s', strtotime('-' . $pre_data_sec . ' seconds', strtotime($loc['dt_tracker'])));
	$q = "select * from gs_object_data_" . $loc['imei'] . " where dt_tracker between '" . $st_date . "' and '" . $loc['dt_tracker'] . "' order by dt_tracker desc LIMIT 2,1";
	$r = mysqli_query($ms, $q);

	if ($r && $row = mysqli_fetch_assoc($r)) {
		if ($ed['checked_value'] == '' || $ed['checked_value'] == 0) {
			$ed['checked_value'] = '14';
		}
		// if($row["speed"]<$loc['speed'])
		// {
		// $avgspeed=abs($row['speed']-$loc['speed'])/2;
		$subspeed = $loc['speed'] - $row['speed'];
		// $travel_length=getLengthBetweenCoordinates($row['lat'], $row['lng'], $loc['lat'], $loc['lng'])*1000;
		$revsec = strtotime($row['dt_tracker']);
		$csec = strtotime($loc['dt_tracker']);
		$avgsec = abs($revsec - $csec);
		$value = ($subspeed / $avgsec) * 4;
		// 		// checked_value
		// $value=$travel_length/$avgsec;
		// if($value>$ed['checked_value'] and $avgspeed>10){
		// 	$ed['event_desc'] = $ed['name'];
		// 	event_notify($ed,$ud,$od,$loc);
		// }
		// $value=$travel_length/$avgsec;
		if ($value >= $ed['checked_value']) {
			$loc['event_value'] = $value;
			$ed['event_desc'] = $ed['name'];
			event_notify($ed, $ud, $od, $loc);
		}

		// $myfile = fopen("vvv_routevalue.txt", "a");
		// fwrite($myfile,$row['lat'].'-'.$row['lng'].'-'.$loc['lat'].'-'.$loc['lng']);
		// fwrite($myfile, "\n");
		// fwrite($myfile,$travel_length);
		// fwrite($myfile, "\n");
		// fwrite($myfile,$revsec);
		// fwrite($myfile, "\n");
		// fwrite($myfile,$csec);
		// fwrite($myfile, "\n");
		// fwrite($myfile,$avgsec);
		// fwrite($myfile, "\n");
		// fwrite($myfile,$value);
		// fwrite($myfile, "\n");
		// fwrite($myfile,'---------------------');
		// fwrite($myfile, "\n");
		// fclose($myfile);
	}
}

//code updated by  Nandha 18-3-22
function event_server_event_hbrake($ed, $ud, $od, $loc)
{
	global $ms;
	$event_status = get_event_status($ed['event_id'], $loc['imei']);

	$ed['event_desc'] = $ed['name'];
	$pre_data_sec = 45;
	$st_date = date('Y-m-d H:i:s', strtotime('-' . $pre_data_sec . ' seconds', strtotime($loc['dt_tracker'])));
	$q = "select * from gs_object_data_" . $loc['imei'] . " where dt_tracker between '" . $st_date . "' and '" . $loc['dt_tracker'] . "' order by dt_tracker desc LIMIT 2,1";
	$r = mysqli_query($ms, $q);

	if ($r && $row = mysqli_fetch_assoc($r)) {
		if ($ed['checked_value'] == '' || $ed['checked_value'] == 0) {
			$ed['checked_value'] = '20';
		}
		// if($row["speed"]<$loc['speed'])
		// {
		// $avgspeed=abs($row['speed']+$loc['speed'])/2;
		$subspeed = $loc['speed'] - $row['speed'];
		// $travel_length=getLengthBetweenCoordinates($row['lat'], $row['lng'], $loc['lat'], $loc['lng'])*1000;
		$revsec = strtotime($row['dt_tracker']);
		$csec = strtotime($loc['dt_tracker']);
		$avgsec = abs($revsec - $csec);
		$value = ($subspeed / $avgsec) * 4;
		// 		// checked_value
		// $value=$travel_length/$avgsec;
		// if($value>$ed['checked_value'] and $avgspeed<10){
		// 	$ed['event_desc'] = $ed['name'];
		// 	event_notify($ed,$ud,$od,$loc);
		// }
		// $value=$avgspeed/$avgsec;
		// if($value>$ed['checked_value'] and $avgspeed<10){
		// 	$ed['event_desc'] = $ed['name'];
		// 	event_notify($ed,$ud,$od,$loc);
		// }
		// $value=$avgspeed/$avgsec;

		// $myfile = fopen("vvv_neg.txt", "a");
		// fwrite($myfile,-abs($ed['checked_value']));
		// fwrite($myfile, "\n");
		// fwrite($myfile,$value);
		// fwrite($myfile, "\n");
		// fwrite($myfile,'-------------');
		// fwrite($myfile, "\n");
		// fclose($myfile);

		if ($value <= -abs($ed['checked_value'])) {
			$loc['event_value'] = $value;
			$ed['event_desc'] = $ed['name'];
			event_notify($ed, $ud, $od, $loc);
		}
	}
}

//code updated by  Vetrivel.NR 6-4-18
function event_onserver_haccel($ed, $ud, $od, $loc)
{
	global $ms;
	$event_status = get_event_status($ed['event_id'], $loc['imei']);

	$ed['event_desc'] = $ed['name'];

	if ($ed['type'] == 'dtc') {
		$codes = str_replace("dtc:", "", $loc['event']);
		$codes = str_replace(",", ", ", $codes);
		$ed['event_desc'] .= ' (' . $codes . ')';
	}

	if ($ed['type'] == "haccelcus" && $loc['speed'] > 20) {

		$accl = array();
		$accl["haccel"]["t"] = 10;
		$accl["haccel"]["s"] = 30;

		$accl["haccelm"]["t"] = 20;
		$accl["haccelm"]["s"] = 30;

		$accl["haccell"]["t"] = 30;
		$accl["haccell"]["s"] = 30;

		$trigr = false;
		for ($itr = 0; $itr < count($accl); $itr++) {
			if ($itr == 0 && $trigr == false) {

				$st_date = date('Y-m-d H:i:s', strtotime('-' . ($accl['haccel']["t"] + 1) . ' seconds', strtotime($loc['dt_tracker'])));
				$q = "select * from gs_object_data_" . $loc['imei'] . " where dt_tracker between '" . $st_date . "' and '" . $loc['dt_tracker'] . "' order by dt_tracker asc limit 1";
				$r = mysqli_query($ms, $q);

				if ($r && $row = mysqli_fetch_assoc($r)) {
					if ($row["speed"] < $loc['speed']) {

						$speed_diff = $loc['speed'] - $row["speed"];
						if ($speed_diff > $accl['haccel']["s"]) {
							$ed['event_desc'] = $ed['name'] . " High";
							event_notify($ed, $ud, $od, $loc);
							$trigr = true;
						}
					}
				}
			} else if ($itr == 1 && $trigr == false) {
				$st_date = date('Y-m-d H:i:s', strtotime('-' . ($accl['haccelm']["t"] + 1) . ' seconds', strtotime($loc['dt_tracker'])));
				$q = "select * from gs_object_data_" . $loc['imei'] . " where dt_tracker between '" . $st_date . "' and '" . $loc['dt_tracker'] . "' order by dt_tracker asc limit 1";
				$r = mysqli_query($ms, $q);
				if ($r && $row = mysqli_fetch_assoc($r)) {
					if ($row["speed"] < $loc['speed']) {
						$speed_diff = $loc['speed'] - $row["speed"];
						if ($speed_diff > $accl['haccelm']["s"]) {
							$ed['event_desc'] = $ed['name'] . " Medium";
							event_notify($ed, $ud, $od, $loc);
							$trigr = true;
						}
					}
				}
			} else if ($itr == 2 && $trigr == false) {
				$st_date = date('Y-m-d H:i:s', strtotime('-' . ($accl['haccell']["t"] + 1) . ' seconds', strtotime($loc['dt_tracker'])));
				$q = "select * from gs_object_data_" . $loc['imei'] . " where dt_tracker between '" . $st_date . "' and '" . $loc['dt_tracker'] . "' order by dt_tracker asc limit 1";
				$r = mysqli_query($ms, $q);
				if ($r && $row = mysqli_fetch_assoc($r)) {
					if ($row["speed"] < $loc['speed']) {
						$speed_diff = $loc['speed'] - $row["speed"];
						if ($speed_diff > $accl['haccell']["s"]) {
							$ed['event_desc'] = $ed['name'] . " Low";
							event_notify($ed, $ud, $od, $loc);
							$trigr = true;
						}
					}
				}
			}

		}
	}
}


function event_onserver_hbrake($ed, $ud, $od, $loc)
{
	global $ms;
	$event_status = get_event_status($ed['event_id'], $loc['imei']);

	$ed['event_desc'] = $ed['name'];

	if ($ed['type'] == 'dtc') {
		$codes = str_replace("dtc:", "", $loc['event']);
		$codes = str_replace(",", ", ", $codes);
		$ed['event_desc'] .= ' (' . $codes . ')';
	}

	if ($ed['type'] == "hbrakecus") {
		$break = array();
		$break["hbrake"]["t"] = 10;
		$break["hbrake"]["s"] = 15;

		$break["hbrakem"]["t"] = 20;
		$break["hbrakem"]["s"] = 10;

		$break["hbrakel"]["t"] = 10;
		$break["hbrakel"]["s"] = 5;

		$trigr = false;
		for ($itr = 0; $itr < count($break); $itr++) {
			if ($itr == 0 && $trigr == false) {
				$st_date = date('Y-m-d H:i:s', strtotime('-' . ($break["hbrake"]["t"] + 1) . ' seconds', strtotime($loc['dt_tracker'])));
				$q = "select * from gs_object_data_" . $loc['imei'] . " where dt_tracker between '" . $st_date . "' and '" . $loc['dt_tracker'] . "' order by dt_tracker asc limit 1";
				$r = mysqli_query($ms, $q);
				if ($r && $row = mysqli_fetch_assoc($r)) {
					if ($row["speed"] > $loc['speed']) {
						$speed_diff = $row["speed"] - $loc['speed'];
						if ($speed_diff > $break["hbrake"]["s"]) {
							$ed['event_desc'] = $ed['name'] . " High";
							event_notify($ed, $ud, $od, $loc);
							$trigr = true;
						}

					}
				}
			} else if ($itr == 1 && $trigr == false) {
				$st_date = date('Y-m-d H:i:s', strtotime('-' . ($break["hbrakem"]["t"] + 1) . ' seconds', strtotime($loc['dt_tracker'])));
				$q = "select * from gs_object_data_" . $loc['imei'] . " where dt_tracker between '" . $st_date . "' and '" . $loc['dt_tracker'] . "' order by dt_tracker asc limit 1";
				$r = mysqli_query($ms, $q);
				if ($r && $row = mysqli_fetch_assoc($r)) {
					if ($row["speed"] > $loc['speed']) {
						$speed_diff = $row["speed"] - $loc['speed'];
						if ($speed_diff > $break["hbrakem"]["s"]) {
							$ed['event_desc'] = $ed['name'] . " Medium";
							event_notify($ed, $ud, $od, $loc);
							$trigr = true;
						}
					}
				}
			} else if ($itr == 2 && $trigr == false) {
				$st_date = date('Y-m-d H:i:s', strtotime('-' . ($break["hbrakel"]["t"] + 1) . ' seconds', strtotime($loc['dt_tracker'])));
				$q = "select * from gs_object_data_" . $loc['imei'] . " where dt_tracker between '" . $st_date . "' and '" . $loc['dt_tracker'] . "' order by dt_tracker asc limit 1";
				$r = mysqli_query($ms, $q);
				if ($r && $row = mysqli_fetch_assoc($r)) {
					if ($row["speed"] > $loc['speed']) {
						$speed_diff = $row["speed"] - $loc['speed'];
						if ($speed_diff > $break["hbrakel"]["s"]) {
							$ed['event_desc'] = $ed['name'] . " Low";
							event_notify($ed, $ud, $od, $loc);
							$trigr = true;
						}
					}
				}
			}
		}

	}

}



function event_tracker($ed, $ud, $od, $loc)
{
	$event_status = get_event_status($ed['event_id'], $loc['imei']);

	$ed['event_desc'] = $ed['name'];

	if ($ed['type'] == 'dtc') {
		$codes = str_replace("dtc:", "", $loc['event']);
		$codes = str_replace(",", ", ", $codes);
		$ed['event_desc'] .= ' (' . $codes . ')';
	}

	event_notify($ed, $ud, $od, $loc);
}


function event_connection($ed, $ud, $od, $loc)
{
	global $gsValues;

	if ($ed['type'] == 'connyes') {
		if (strtotime($loc['dt_server']) >= strtotime(gmdate("Y-m-d H:i:s") . " - " . $gsValues['CONNECTION_TIMEOUT'] . " minutes")) {

			if (get_event_status($ed['event_id'], $loc['imei']) == -1) {
				set_event_status($ed['event_id'], $loc['imei'], '1');
				// set dt_tracker to dt_server to show exact time
				$loc['dt_tracker'] = $loc['dt_server'];
				// add event desc to event data array
				$ed['event_desc'] = $ed['name'];
				event_notify($ed, $ud, $od, $loc);
			}
		} else {
			if (get_event_status($ed['event_id'], $loc['imei']) != -1) {
				set_event_status($ed['event_id'], $loc['imei'], '-1');
			}
		}
	}

	if ($ed['type'] == 'connno') {
		if (strtotime($loc['dt_server']) < strtotime(gmdate("Y-m-d H:i:s") . " - " . $ed['checked_value'] . " minutes")) {
			if (get_event_status($ed['event_id'], $loc['imei']) == -1) {
				set_event_status($ed['event_id'], $loc['imei'], '1');
				// set dt_tracker to dt_server to show exact time
				$loc['dt_tracker'] = $loc['dt_server'];
				// add event desc to event data array
				$ed['event_desc'] = $ed['name'];
				event_notify($ed, $ud, $od, $loc);
			}
		} else {
			if (get_event_status($ed['event_id'], $loc['imei']) != -1) {
				set_event_status($ed['event_id'], $loc['imei'], '-1');
			}
		}
	}
}

function event_gps($ed, $ud, $od, $loc)
{
	if ($ed['type'] == 'gpsyes') {
		if ($loc['loc_valid'] == '1') {
			if (get_event_status($ed['event_id'], $loc['imei']) == -1) {
				set_event_status($ed['event_id'], $loc['imei'], '1');
				// set dt_tracker to dt_server to show exact time
				$loc['dt_tracker'] = $loc['dt_server'];
				// add event desc to event data array
				$ed['event_desc'] = $ed['name'];
				event_notify($ed, $ud, $od, $loc);
			}
		} else {
			if (get_event_status($ed['event_id'], $loc['imei']) != -1) {
				set_event_status($ed['event_id'], $loc['imei'], '-1');
			}
		}
	}

	if ($ed['type'] == 'gpsno') {
		if (($loc['loc_valid'] == '0') && (strtotime($loc['dt_tracker']) < strtotime(gmdate("Y-m-d H:i:s") . " - " . $ed['checked_value'] . " minutes"))) {
			if (get_event_status($ed['event_id'], $loc['imei']) == -1) {
				set_event_status($ed['event_id'], $loc['imei'], '1');
				// set dt_tracker to dt_server to show exact time
				$loc['dt_tracker'] = $loc['dt_server'];
				// add event desc to event data array
				$ed['event_desc'] = $ed['name'];
				event_notify($ed, $ud, $od, $loc);
			}
		} else {
			if (get_event_status($ed['event_id'], $loc['imei']) != -1) {
				set_event_status($ed['event_id'], $loc['imei'], '-1');
			}
		}
	}
}

function event_stopped_moving_engidle($ed, $ud, $od, $loc)
{
	if (isset($loc['dt_last_stop'])) {
		$dt_last_stop = strtotime($loc['dt_last_stop']);
	} else {
		$dt_last_stop = 0;
	}

	if (isset($loc['dt_last_idle'])) {
		$dt_last_idle = strtotime($loc['dt_last_idle']);
	} else {
		$dt_last_idle = 0;
	}

	if (isset($loc['dt_last_move'])) {
		$dt_last_move = strtotime($loc['dt_last_move']);
	} else {
		$dt_last_move = 0;
	}

	if (isset($loc['dt_last_aconidle'])) {
		$dt_last_aconidle = strtotime($loc['dt_last_aconidle']);
	} else {
		$dt_last_aconidle = 0;
	}

	if (($dt_last_stop > 0) || ($dt_last_move > 0)) {
		if ($ed['type'] == 'stopped') {
			if (($dt_last_stop >= $dt_last_move) && (strtotime($loc['dt_last_stop']) < strtotime(gmdate("Y-m-d H:i:s") . " - " . $ed['checked_value'] . " minutes"))) {
				if (get_event_status($ed['event_id'], $loc['imei']) == -1) {
					set_event_status($ed['event_id'], $loc['imei'], '1');
					// set dt_tracker to dt_server to show exact time
					$loc['dt_tracker'] = $loc['dt_server'];
					// add event desc to event data array
					$ed['event_desc'] = $ed['name'];
					event_notify($ed, $ud, $od, $loc);
				}
			} else {
				if (get_event_status($ed['event_id'], $loc['imei']) != -1) {
					set_event_status($ed['event_id'], $loc['imei'], '-1');
				}
			}
		}

		if ($ed['type'] == 'moving') {
			if (($dt_last_stop < $dt_last_move) && (strtotime($loc['dt_last_move']) < strtotime(gmdate("Y-m-d H:i:s") . " - " . $ed['checked_value'] . " minutes"))) {
				if (get_event_status($ed['event_id'], $loc['imei']) == -1) {
					set_event_status($ed['event_id'], $loc['imei'], '1');
					// set dt_tracker to dt_server to show exact time
					$loc['dt_tracker'] = $loc['dt_server'];
					// add event desc to event data array
					$ed['event_desc'] = $ed['name'];
					event_notify($ed, $ud, $od, $loc);
				}
			} else {
				if (get_event_status($ed['event_id'], $loc['imei']) != -1) {
					set_event_status($ed['event_id'], $loc['imei'], '-1');
				}
			}
		}

		if ($ed['type'] == 'engidle') {
			if (($dt_last_stop <= $dt_last_idle) && ($dt_last_move <= $dt_last_idle) && (strtotime($loc['dt_last_idle']) < strtotime(gmdate("Y-m-d H:i:s") . " - " . $ed['checked_value'] . " minutes"))) {
				if (get_event_status($ed['event_id'], $loc['imei']) == -1) {
					set_event_status($ed['event_id'], $loc['imei'], '1');
					// set dt_tracker to dt_server to show exact time
					$loc['dt_tracker'] = $loc['dt_server'];
					// add event desc to event data array
					$ed['event_desc'] = $ed['name'];
					event_notify($ed, $ud, $od, $loc);
				}
			} else {
				if (get_event_status($ed['event_id'], $loc['imei']) != -1) {
					set_event_status($ed['event_id'], $loc['imei'], '-1');
				}
			}
		}


		//$dt_last_aconidle
		if ($ed['type'] == 'aconidle') {
			if (
				($dt_last_stop <= $dt_last_idle) && ($dt_last_move <= $dt_last_idle) &&
				($dt_last_aconidle <
					strtotime(gmdate("Y-m-d H:i:s") . " - " . $ed['checked_value'] . " minutes"))
				&& ($dt_last_idle <= $dt_last_aconidle)
			) {

				if ($loc["imei"] == "22732012") {
					echo "vetrielse" . json_encode($loc);
				}

				if (get_event_status($ed['event_id'], $loc['imei']) == -1) {
					set_event_status($ed['event_id'], $loc['imei'], '1');
					// set dt_tracker to dt_server to show exact time
					$loc['dt_tracker'] = $loc['dt_server'];
					// add event desc to event data array
					$ed['event_desc'] = $ed['name'];
					event_notify($ed, $ud, $od, $loc);
				}
			} else {
				if ($loc["imei"] == "22732012") {
					echo "we54" . json_encode($loc);
				}
				if (get_event_status($ed['event_id'], $loc['imei']) != -1) {
					set_event_status($ed['event_id'], $loc['imei'], '-1');
				}
			}
		}

	}
}

function event_route_in($ed, $ud, $od, $loc)
{
	global $ms;

	$event_status = get_event_status($ed['event_id'], $loc['imei']);

	// check if route still exists, to fix bug if user deletes zone
	$q = "SELECT * FROM `gs_user_routes` WHERE `route_id`='" . $event_status . "'";
	$r = mysqli_query($ms, $q);

	if (mysqli_num_rows($r) == 0) {
		set_event_status($ed['event_id'], $loc['imei'], '-1');

		$event_status = '-1';
	}

	// check event
	$q = "SELECT * FROM `gs_user_routes` WHERE `user_id`='" . $ed['user_id'] . "' AND `route_id` IN (" . $ed['routes'] . ")";
	$r = mysqli_query($ms, $q);

	while ($route = mysqli_fetch_array($r)) {
		$dist = isPointOnLine($route['route_points'], $loc['lat'], $loc['lng']);

		// get user units and convert if needed
		$units = explode(",", $ud['units']);
		$dist = convDistanceUnits($dist, 'km', $units[0]);

		if ($dist <= $route['route_deviation']) {
			if ($event_status == -1) {
				set_event_status($ed['event_id'], $loc['imei'], $route['route_id']);
				// add event desc to event data array
				$ed['event_desc'] = $ed['name'] . ' (' . $route['route_name'] . ')';
				event_notify($ed, $ud, $od, $loc);
			}
		} else {
			if ($event_status == $route['route_id']) {
				set_event_status($ed['event_id'], $loc['imei'], '-1');
			}
		}
	}
}

function event_route_out($ed, $ud, $od, $loc)
{
	global $ms;

	$event_status = get_event_status($ed['event_id'], $loc['imei']);

	// check if route still exists, to fix bug if user deletes zone
	$q = "SELECT * FROM `gs_user_routes` WHERE `route_id`='" . $event_status . "'";
	$r = mysqli_query($ms, $q);

	if (mysqli_num_rows($r) == 0) {
		set_event_status($ed['event_id'], $loc['imei'], '-1');

		$event_status = '-1';
	}

	// check event
	$q = "SELECT * FROM `gs_user_routes` WHERE `user_id`='" . $ed['user_id'] . "' AND `route_id` IN (" . $ed['routes'] . ")";
	$r = mysqli_query($ms, $q);

	while ($route = mysqli_fetch_array($r)) {
		$dist = isPointOnLine($route['route_points'], $loc['lat'], $loc['lng']);

		// get user units and convert if needed
		$units = explode(",", $ud['units']);
		$dist = convDistanceUnits($dist, 'km', $units[0]);

		if ($dist < $route['route_deviation']) {
			if ($event_status == -1) {
				set_event_status($ed['event_id'], $loc['imei'], $route['route_id']);
			}
		} else {
			if ($event_status == $route['route_id']) {
				set_event_status($ed['event_id'], $loc['imei'], '-1');
				// add event desc to event data array
				$ed['event_desc'] = $ed['name'] . ' (' . $route['route_name'] . ')';
				event_notify($ed, $ud, $od, $loc);
			}
		}
	}
}

function event_zone_in($ed, $ud, $od, $loc)
{
	global $ms;

	$event_status = get_event_status($ed['event_id'], $loc['imei']);

	// check if zone still exists, to fix bug if user deletes zone
	$q = "SELECT * FROM `gs_user_zones` WHERE `zone_id`='" . $event_status . "'";
	$r = mysqli_query($ms, $q);

	if (mysqli_num_rows($r) == 0) {
		set_event_status($ed['event_id'], $loc['imei'], '-1');

		$event_status = '-1';
	}

	// check event
	$q = "SELECT * FROM `gs_user_zones` WHERE `user_id`='" . $ed['user_id'] . "' AND `zone_id` IN (" . $ed['zones'] . ")";
	$r = mysqli_query($ms, $q);

	if (!$r) {
		return;
	}

	while ($zone = mysqli_fetch_array($r)) {
		$in_zone = isPointInPolygon($zone['zone_vertices'], $loc['lat'], $loc['lng']);

		if ($in_zone) {
			if ($event_status == -1) {
				set_event_status($ed['event_id'], $loc['imei'], $zone['zone_id']);
				// add event desc to event data array
				$ed['event_desc'] = $ed['name'] . ' (' . $zone['zone_name'] . ')';
				$loc["type"] = "Zone_In";
				$loc["zone_id"] = $zone['zone_id'];
				event_notify($ed, $ud, $od, $loc);
			}
		} else {
			if ($event_status == $zone['zone_id']) {
				set_event_status($ed['event_id'], $loc['imei'], '-1');
			}
		}
	}
}

function event_zone_out($ed, $ud, $od, $loc)
{
	global $ms;

	$event_status = get_event_status($ed['event_id'], $loc['imei']);

	// check if zone still exists, to fix bug if user deletes zone
	$q = "SELECT * FROM `gs_user_zones` WHERE `zone_id`='" . $event_status . "'";
	$r = mysqli_query($ms, $q);

	if (mysqli_num_rows($r) == 0) {
		set_event_status($ed['event_id'], $loc['imei'], '-1');

		$event_status = '-1';
	}

	// check event
	$q = "SELECT * FROM `gs_user_zones` WHERE `user_id`='" . $ed['user_id'] . "' AND `zone_id` IN (" . $ed['zones'] . ")";
	$r = mysqli_query($ms, $q);

	if (!$r) {
		return;
	}

	while ($zone = mysqli_fetch_array($r)) {
		$in_zone = isPointInPolygon($zone['zone_vertices'], $loc['lat'], $loc['lng']);

		if ($in_zone) {
			if ($event_status == -1) {
				set_event_status($ed['event_id'], $loc['imei'], $zone['zone_id']);
			}
		} else {
			if ($event_status == $zone['zone_id']) {
				set_event_status($ed['event_id'], $loc['imei'], '-1');
				// add event desc to event data array
				$ed['event_desc'] = $ed['name'] . ' (' . $zone['zone_name'] . ')';
				$loc["type"] = "Zone_Out";
				$loc["zone_id"] = $zone['zone_id'];
				event_notify($ed, $ud, $od, $loc);
			}
		}
	}
}

function event_toll_zone_out($ed, $ud, $od, $loc)
{
	global $ms;

	$event_status = get_event_status($ed['event_id'], $loc['imei']);

	// check if zone still exists, to fix bug if user deletes zone
	$q = "SELECT * FROM `gs_user_zones` WHERE `zone_id`='" . $event_status . "'";
	$r = mysqli_query($ms, $q);

	if (mysqli_num_rows($r) == 0) {
		set_event_status($ed['event_id'], $loc['imei'], '-1');

		$event_status = '-1';
	}

	// check event
	$q = "SELECT * FROM `gs_user_zones` WHERE `group_id`='23'";
	$r = mysqli_query($ms, $q);

	if (!$r) {
		return;
	}

	while ($zone = mysqli_fetch_array($r)) {
		$in_zone = isPointInPolygon($zone['zone_vertices'], $loc['lat'], $loc['lng']);

		if ($in_zone) {
			if ($event_status == -1) {
				set_event_status($ed['event_id'], $loc['imei'], $zone['zone_id']);
			}
		} else {
			if ($event_status == $zone['zone_id']) {
				set_event_status($ed['event_id'], $loc['imei'], '-1');
				// add event desc to event data array
				$ed['event_desc'] = $ed['name'] . ' (' . $zone['zone_name'] . ')';
				$loc["type"] = "Zone_Out";
				$loc["zone_id"] = $zone['zone_id'];
				event_notify($ed, $ud, $od, $loc);
			}
		}
	}
}

function event_toll_zone_in($ed, $ud, $od, $loc)
{
	global $ms;

	$event_status = get_event_status($ed['event_id'], $loc['imei']);

	// check if zone still exists, to fix bug if user deletes zone
	$q = "SELECT * FROM `gs_user_zones` WHERE `zone_id`='" . $event_status . "'";
	$r = mysqli_query($ms, $q);

	if (mysqli_num_rows($r) == 0) {
		set_event_status($ed['event_id'], $loc['imei'], '-1');

		$event_status = '-1';
	}

	// check event
	$q = "SELECT * FROM `gs_user_zones` WHERE `group_id`='23'";
	$r = mysqli_query($ms, $q);

	if (!$r) {
		return;
	}

	while ($zone = mysqli_fetch_array($r)) {
		$in_zone = isPointInPolygon($zone['zone_vertices'], $loc['lat'], $loc['lng']);

		if ($in_zone) {
			if ($event_status == -1) {
				set_event_status($ed['event_id'], $loc['imei'], $zone['zone_id']);
				// add event desc to event data array
				$ed['event_desc'] = $ed['name'] . ' (' . $zone['zone_name'] . ')';
				$loc["type"] = "Zone_In";
				$loc["zone_id"] = $zone['zone_id'];
				event_notify($ed, $ud, $od, $loc);
			}
		} else {
			if ($event_status == $zone['zone_id']) {
				set_event_status($ed['event_id'], $loc['imei'], '-1');
			}
		}
	}
}

function event_param($ed, $ud, $od, $loc)
{
	$condition = false;
	$params = @$loc['params'];
	$units = explode(",", $ud['units']);

	$pc = json_decode($ed['checked_value'], true);
	if ($pc == null) {
		return;
	}

	// check conditions
	for ($i = 0; $i < count($pc); $i++) {
		$cn = false;

		if ($pc[$i]['src'] == 'speed') {
			$value = convSpeedUnits($loc['speed'], 'km', $units[0]);
		} else {
			// check if param exits
			if (!isset($params[$pc[$i]['src']])) {
				$condition = false;
				break;
			}

			$value = $params[$pc[$i]['src']];
		}

		if ($pc[$i]['cn'] == 'eq') {
			if ($value == $pc[$i]['val'])
				$cn = true;
		}

		if ($pc[$i]['cn'] == 'gr') {
			if ($value > $pc[$i]['val'])
				$cn = true;
		}

		if ($pc[$i]['cn'] == 'lw') {
			if ($value < $pc[$i]['val'])
				$cn = true;
		}

		if ($cn == true) {
			$condition = true;
		} else {
			$condition = false;
			break;
		}
	}

	// if ($loc["imei"] == "353201357357992") {
	// 	$myfile = fopen("353201357357992.txt", "a");
	// 	fwrite($myfile, json_encode($loc));
	// 	fwrite($myfile, "\n");
	// 	fclose($myfile);
	// }
	if ($condition) {
		if (get_event_status($ed['event_id'], $loc['imei']) == -1) {
			set_event_status($ed['event_id'], $loc['imei'], 1);
			// add event desc to event data array
			$ed['event_desc'] = $ed['name'];
			event_notify($ed, $ud, $od, $loc);
			
		}
	} else {
		$event_status = get_event_status($ed['event_id'], $loc['imei']);
		if ( $event_status != -1) {
			if($loc["params"]){
				set_event_status($ed['event_id'], $loc['imei'], -1);
			}
		}
	}
}

function event_sensor($ed, $ud, $od, $loc)
{
	$condition = false;
	$params = $loc['params'];
	$units = explode(",", $ud['units']);

	$sc = json_decode($ed['checked_value'], true);
	if ($sc == null) {
		return;
	}

	$sensors = getSensors($loc['imei']);

	// check conditions
	for ($i = 0; $i < count($sc); $i++) {
		$cn = false;

		if ($sc[$i]['src'] == 'speed') {
			$value = convSpeedUnits($loc['speed'], 'km', $units[0]);
		} else {
			$sensor = false;

			for ($j = 0; $j < count($sensors); ++$j) {
				if ($sc[$i]['src'] == $sensors[$j]['name']) {
					$sensor = $sensors[$j];
				}
			}

			// check if sensor exits
			if (!$sensor) {
				$condition = false;
				break;
			}

			// check if param exits
			if (!isset($params[$sensor['param']])) {
				$condition = false;
				break;
			}

			$sensor_value = getSensorValue($params, $sensor);

			$value = $sensor_value['value'];
		}

		if ($sc[$i]['cn'] == 'eq') {
			if ($value == $sc[$i]['val'])
				$cn = true;
		}

		if ($sc[$i]['cn'] == 'gr') {
			if ($value > $sc[$i]['val'])
				$cn = true;
		}

		if ($sc[$i]['cn'] == 'lw') {
			if ($value < $sc[$i]['val'])
				$cn = true;
		}

		if ($cn == true) {
			$condition = true;
		} else {
			$condition = false;
			break;
		}
	}

	if ($condition) {
		if (get_event_status($ed['event_id'], $loc['imei']) == -1) {
			set_event_status($ed['event_id'], $loc['imei'], '1');
			// add event desc to event data array
			$ed['event_desc'] = $ed['name'];
			event_notify($ed, $ud, $od, $loc);
		}
	} else {
		if (get_event_status($ed['event_id'], $loc['imei']) != -1) {
			if($loc["params"]){
				set_event_status($ed['event_id'], $loc['imei'], '-1');
			}
		}
	}
}

function event_overspeed($ed, $ud, $od, $loc)
{
	$speed = @$loc['speed'];

	// get user speed unit and convert if needed
	$units = explode(",", $ud['units']);
	$speed = convSpeedUnits($speed, 'km', $units[0]);

	if ($speed > $ed['checked_value']) {
		if (get_event_status($ed['event_id'], $loc['imei']) == -1) {
			set_event_status($ed['event_id'], $loc['imei'], '1');
			// add event desc to event data array
			$ed['event_desc'] = $ed['name'];
			;
			event_notify($ed, $ud, $od, $loc);
		}
	} else {
		if (get_event_status($ed['event_id'], $loc['imei']) != -1) {
			set_event_status($ed['event_id'], $loc['imei'], '-1');
		}
	}
}

function event_underspeed($ed, $ud, $od, $loc)
{
	$speed = $loc['speed'];

	// get user speed unit and convert if needed
	$units = explode(",", $ud['units']);
	$speed = convSpeedUnits($speed, 'km', $units[0]);

	if ($speed < $ed['checked_value']) {
		if (get_event_status($ed['event_id'], $loc['imei']) == -1) {
			set_event_status($ed['event_id'], $loc['imei'], '1');
			// add event desc to event data array
			$ed['event_desc'] = $ed['name'];
			event_notify($ed, $ud, $od, $loc);
		}
	} else {
		if (get_event_status($ed['event_id'], $loc['imei']) != -1) {
			set_event_status($ed['event_id'], $loc['imei'], '-1');
		}
	}
}

function event_unauthorizedpeople($ed, $ud, $od, $loc)
{
	global $ms;


	// if (get_event_status($ed['event_id'], $loc['imei']) == -1)
	// {
	// 	set_event_status($ed['event_id'], $loc['imei'], '1');
	// add event desc to event data array
	$rfidrev = $loc['rfid'];
	$hex = str_split($loc['rfid'], 2);
	if (gettype($hex) == 'array') {
		$hex = array_reverse($hex);
		$rfidrev = strtoupper(join("", $hex));
	}
	$q = "SELECT * FROM dstudent where `rfid`='" . $loc['rfid'] . "' or `rfid`='" . $rfidrev . "'";
	$r = mysqli_query($ms, $q);
	if (mysqli_num_rows($r) == 0) {
		$ed['event_desc'] = $ed['name'] . '- ' . $rfidrev;
		event_notify($ed, $ud, $od, $loc);
	}

	// }
}

function event_routedeviation_GPS($ed, $ud, $od, $loc)
{
	$event_status = get_event_status($ed['event_id'], $loc['imei']);

	$ed['event_desc'] = $ed['name'];

	// $myfile = fopen("vvv_routevalue.txt", "a");
	// fwrite($myfile,checkRoute_inOut_Status($loc));
	// fwrite($myfile, "\n");
	// fwrite($myfile,$event_status);
	// fwrite($myfile, "\n");
	// fwrite($myfile,$loc['imei']);
	// fwrite($myfile, "\n");
	// fwrite($myfile,$loc['dt_tracker']);
	// fwrite($myfile, "\n");
	// fclose($myfile);

	if (checkRoute_inOut_Status($loc) == 1 && $event_status == '-1') {
		if ($ed['type'] == 'dtc') {
			$codes = str_replace("dtc:", "", $loc['event']);
			$codes = str_replace(",", ", ", $codes);
			$ed['event_desc'] .= ' (' . $codes . ')';
		}

		event_notify($ed, $ud, $od, $loc);
		set_event_status($ed['event_id'], $loc['imei'], '1');

	} else if (checkRoute_inOut_Status($loc) != 1) {
		set_event_status($ed['event_id'], $loc['imei'], '-1');
	}
}

function event_notify($ed, $ud, $od, $loc)
{
	global $ms, $gsValues;



	$imei = $loc['imei'];


	if (!checkObjectActive($imei)) {
		return;
	}

	// get current date and time for week days and day time check
	$dt_check = convUserIDTimezone($ud['id'], date("Y-m-d H:i:s", strtotime($loc['dt_server'])));

	if (!check_event_week_days($dt_check, $ed['week_days'])) {
		return;
	}

	if (!check_event_day_time($dt_check, $ed['day_time'])) {
		return;
	}

	if (!check_event_working_hour($dt_check, $ed['day_time'], $ud, $ed, $loc)) {
		return;
	}

	if (!check_event_route_trigger($ed, $ud, $loc)) {
		return;
	}
	if ($loc['imei'] == '91000718') {
		$myfile = fopen("vvv.txt", "a");
		fwrite($myfile, 'IN');
		fwrite($myfile, "\n");
		fclose($myfile);
	}

	if (!check_event_zone_trigger($ed, $ud, $loc)) {
		return;
	}

	// duration from last event
	if (!check_event_duration_from_last($ed, $imei)) {
		return;
	} else {
		$q = "UPDATE `gs_user_events_status` SET `dt_server`='" . gmdate("Y-m-d H:i:s") . "' WHERE `event_id`='" . $ed['event_id'] . "' AND `imei`='" . $imei . "'";
		$r = mysqli_query($ms, $q);
	}

	if (!isset($loc["type"])) {
		$loc["type"] = $ed['type'];
	}

	$aryzo = "";
	$aryzoval = "";
	if (isset($loc["zone_id"])) {
		$aryzo = ",zoneid";
		$aryzoval = ",'" . $loc["zone_id"] . "'";
	}
	if ($ed['heighalert'] == 'true') {
		$heighalert_ack = '0';
	} else {
		$heighalert_ack = '1';
	}

	// insert event into list	type,	'".$ed['type']."',
	$q = "INSERT INTO `gs_user_events_data` (	user_id,
								user_event_id,
								event_desc,
								notify_system,
								notify_arrow,
								notify_arrow_color,
								notify_ohc,
								notify_ohc_color,
								heighalert,
								heighalert_ack,
								imei,
								dt_server,
								dt_tracker,
								lat,
								lng,
								altitude,
								angle,
								speed,
								params,								
								type" . $aryzo . "
								) VALUES (
								'" . $ed['user_id'] . "',
								'" . $ed['event_id'] . "',
								'" . $ed['event_desc'] . "',
								'" . $ed['notify_system'] . "',
								'" . $ed['notify_arrow'] . "',
								'" . $ed['notify_arrow_color'] . "',
								'" . $ed['notify_ohc'] . "',
								'" . $ed['notify_ohc_color'] . "',
								'" . $ed['heighalert'] . "',
								'" . $heighalert_ack . "',
								'" . $od['imei'] . "',
								'" . $loc['dt_server'] . "',
								'" . $loc['dt_tracker'] . "',
								'" . $loc['lat'] . "',
								'" . $loc['lng'] . "',
								'" . $loc['altitude'] . "',
								'" . $loc['angle'] . "',
								'" . $loc['speed'] . "',
								'" . json_encode($loc['params']) . "',
								'" . $ed["type"] . "'
								" . $aryzoval . ")";
	$r = mysqli_query($ms, $q);

	if (isset($loc['event_value'])) {
		$last_id = $ms->insert_id;
		$qu = "UPDATE `gs_user_events_data` SET `custom_value`='" . $loc['event_value'] . "' WHERE `event_id`='" . $last_id . "'";
		mysqli_query($ms, $qu);

	}

	// send cmd
	if ($ed['cmd_send'] == 'true') {
		if ($ed['cmd_gateway'] == 'gprs') {
			sendObjectGPRSCommand($ed['user_id'], $imei, $ed['event_desc'], $ed['cmd_type'], $ed['cmd_string']);
		} else if ($ed['cmd_gateway'] == 'sms') {
			sendObjectSMSCommand($ed['user_id'], $imei, $ed['event_desc'], $ed['cmd_string']);
		}
	}

	// send email notification
	if (checkUserUsage($ed['user_id'], 'email')) {
		if (($ed['notify_email'] == 'true') && ($ed['notify_email_address'] != '')) {
			$email = $ed['notify_email_address'];

			$template = event_notify_template('email', $ed, $ud, $od, $loc);

			$result = sendEmail($email, $template['subject'], $template['message'], true);

			if ($result) {
				//update user usage
				updateUserUsage($ed['user_id'], false, $result, false, false);
			}
			fndblogemail("Event", $ud, $loc['type'], $template['message']);
		}
	}

	// send SMS notification
	if (checkUserUsage($ed['user_id'], 'sms')) {

		if (($ed['notify_sms'] == 'true') && ($ed['notify_sms_number'] != '')) {

			$result = false;

			$number = $ed['notify_sms_number'];

			$template = event_notify_template('sms', $ed, $ud, $od, $loc);

			if ($ud['sms_gateway'] == 'true') {
				if ($ud['sms_gateway_type'] == 'http') {

					fndblogemail("SMS", $ud, $loc['type'], $number . '-' . $template['message']);
					$result = sendSMSHTTP($ud['sms_gateway_url'], '', $number, $template['message']);
				} else if ($ud['sms_gateway_type'] == 'app') {
					$result = sendSMSAPP($ud['sms_gateway_identifier'], '', $number, $template['message']);
				}
			} else {
				if (($ud['sms_gateway_server'] == 'true') && ($gsValues['SMS_GATEWAY'] == 'true')) {
					if ($gsValues['SMS_GATEWAY_TYPE'] == 'http') {
						fndblogemail("SMS", $ud, $loc['type'], $number . '-' . $template['message']);
						$result = sendSMSHTTP($gsValues['SMS_GATEWAY_URL'], $gsValues['SMS_GATEWAY_NUMBER_FILTER'], $number, $template['message']);
					} else if ($gsValues['SMS_GATEWAY_TYPE'] == 'app') {
						$result = sendSMSAPP($gsValues['SMS_GATEWAY_IDENTIFIER'], $gsValues['SMS_GATEWAY_NUMBER_FILTER'], $number, $template['message']);
					}
				}
			}

			if ($result) {
				//update user usage
				updateUserUsage($ed['user_id'], false, false, $result, false);
			}
		}

	}
}

function event_notify_template($type, $ed, $ud, $od, $loc)
{
	global $ms, $la;

	// load language
	loadLanguage($ud["language"], $ud["units"]);

	// get template
	$template = array();
	$template['subject'] = '';
	$template['message'] = '';

	if ($type == 'email') {
		$template = getDefaultTemplate('event_email', $ud["language"]);
	} else if ($type == 'sms') {
		$template = getDefaultTemplate('event_sms', $ud["language"]);
	}

	if ($ed[$type . '_template_id'] != 0) {
		$q = "SELECT * FROM `gs_user_templates` WHERE `template_id`='" . $ed[$type . '_template_id'] . "'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);

		if ($row) {
			if ($row['subject'] != '') {
				$template['subject'] = $row['subject'];
			}

			if ($row['message'] != '') {
				$template['message'] = $row['message'];
			}
		}
	}

	// modify template variables
	$g_map = 'http://maps.google.com/maps?q=' . $loc['lat'] . ',' . $loc['lng'] . '&t=m';

	// add timezone to dt_tracker and dt_server
	$dt_server = convUserIDTimezone($ud['id'], $loc['dt_server']);
	$dt_tracker = convUserIDTimezone($ud['id'], $loc['dt_tracker']);

	$speed = $loc['speed'];
	$units = explode(",", $ud['units']);
	$speed = convSpeedUnits($speed, 'km', $units[0]);
	$speed = $speed . ' ' . $la["UNIT_SPEED"];

	$driver = getObjectDriver($ud['id'], $od['imei'], $loc['params']);

	$trailer = getObjectTrailer($ud['id'], $od['imei'], $loc['params']);

	// check if there is address variable
	if ((strpos($template['subject'], "%ADDRESS%") !== "") || (strpos($template['message'], "%ADDRESS%") !== "")) {
		$address = geocoderGetAddress($loc["lat"], $loc["lng"]);
	}

	foreach ($template as $key => $value) {
		$value = str_replace("%NAME%", $od["name"], $value);
		$value = str_replace("%IMEI%", $od["imei"], $value);
		$value = str_replace("%EVENT%", $ed['event_desc'], $value);
		$value = str_replace("%LAT%", $loc["lat"], $value);
		$value = str_replace("%LNG%", $loc["lng"], $value);
		$value = str_replace("%SPEED%", $speed, $value);
		$value = str_replace("%ALT%", $loc["altitude"], $value);
		$value = str_replace("%ANGLE%", $loc["angle"], $value);
		$value = str_replace("%DT_POS%", $dt_tracker, $value);
		$value = str_replace("%DT_SER%", $dt_server, $value);
		$value = str_replace("%G_MAP%", $g_map, $value);
		$value = str_replace("%TR_MODEL%", $od["model"], $value);
		$value = str_replace("%PL_NUM%", $od["plate_number"], $value);
		$value = str_replace("%DRIVER%", $driver['driver_name'], $value);
		$value = str_replace("%TRAILER%", $trailer['trailer_name'], $value);
		$value = str_replace("%ADDRESS%", $address, $value);

		$template[$key] = $value;
	}

	return $template;
}

function get_event_status($event_id, $imei)
{
	global $ms;

	$result = -1;

	$q = "SELECT * FROM `gs_user_events_status` WHERE `event_id`='" . $event_id . "' AND `imei`='" . $imei . "'";
	$r = mysqli_query($ms, $q);
	$row = mysqli_fetch_array($r);
	if ($row) {
		$result = $row['event_status'];
	} else {
		$q = "INSERT INTO `gs_user_events_status` (`event_id`,`imei`,`event_status`) VALUES ('" . $event_id . "','" . $imei . "','-1')";
		$r = mysqli_query($ms, $q);
	}

	return $result;
}

function set_event_status($event_id, $imei, $value)
{
	global $ms;

	$q = "UPDATE `gs_user_events_status` SET `event_status`='" . $value . "' WHERE `event_id`='" . $event_id . "' AND `imei`='" . $imei . "'";
	$r = mysqli_query($ms, $q);

}

function check_event_duration_from_last($ed, $imei)
{
	global $ms;

	$q = "SELECT * FROM `gs_user_events_status` WHERE `event_id`='" . $ed['event_id'] . "' AND `imei`='" . $imei . "'";
	$r = mysqli_query($ms, $q);
	$row = mysqli_fetch_array($r);

	if ($row) {
		if ($ed['duration_from_last_event'] == 'true') {
			if (strtotime($row['dt_server']) >= strtotime(gmdate("Y-m-d H:i:s") . " - " . $ed['duration_from_last_event_minutes'] . " minutes")) {
				return false;
			}
		}
	}

	return true;
}

function check_event_week_days($dt_check, $week_days)
{
	$day_of_week = gmdate('w', strtotime($dt_check));
	$week_days = explode(',', $week_days);

	if ($week_days[$day_of_week] == 'true') {
		return true;
	} else {
		return false;
	}
}

function check_event_day_time($dt_check, $day_time)
{
	$day_of_week = gmdate('w', strtotime($dt_check));
	$day_time = json_decode($day_time, true);

	if ($day_time != null) {
		if ($day_time['dt'] == true) {
			if (($day_time['sun'] == true) && ($day_of_week == 0)) {
				$from = $day_time['sun_from'];
				$to = $day_time['sun_to'];
			} else if (($day_time['mon'] == true) && ($day_of_week == 1)) {
				$from = $day_time['mon_from'];
				$to = $day_time['mon_to'];
			} else if (($day_time['tue'] == true) && ($day_of_week == 2)) {
				$from = $day_time['tue_from'];
				$to = $day_time['tue_to'];
			} else if (($day_time['wed'] == true) && ($day_of_week == 3)) {
				$from = $day_time['wed_from'];
				$to = $day_time['wed_to'];
			} else if (($day_time['thu'] == true) && ($day_of_week == 4)) {
				$from = $day_time['thu_from'];
				$to = $day_time['thu_to'];
			} else if (($day_time['fri'] == true) && ($day_of_week == 5)) {
				$from = $day_time['fri_from'];
				$to = $day_time['fri_to'];
			} else if (($day_time['sat'] == true) && ($day_of_week == 6)) {
				$from = $day_time['sat_from'];
				$to = $day_time['sat_to'];
			} else {
				return false;
			}

			if (isset($from) && isset($to)) {
				$dt_check = strtotime(date("H:i", strtotime($dt_check)));
				$from = strtotime($from);
				$to = strtotime($to);

				if (($from < $dt_check) && ($to > $dt_check)) {
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		} else {
			return true;
		}
	} else {
		return true;
	}
}

function check_event_working_hour($dt_check, $day_time, $ud, $ed, $loc)
{
	global $ms;
	$user_id = $ed['user_id'];
	$timing_trigger = $ed['timing'];
	$imei = $loc['imei'];

	if (($timing_trigger == '') || ($timing_trigger == 'off')) {
		return true;
	}

	$day_of_week = gmdate('w', strtotime($dt_check));
	$day_time = json_decode($day_time, true);

	if ($timing_trigger == 'in') {
		$q = "select from_time,to_time,today,day from ex_user_work_hour where status='true'
				  and imei='" . $imei . "' and `user_id`='" . $user_id . "' order by from_time";
		$r = mysqli_query($ms, $q);

		if (!$r) {
			return false;
		}

		while ($timing = mysqli_fetch_array($r)) {
			$from = $timing["from_time"];//str_replace(":",".",$timing["from_time"]);
			$to = $timing["to_time"];//str_replace(":",".",$timing["to_time"]);
			$today = $timing["today"];
			$day = $timing["day"];

			if (
				(($day == "Sun") && ($day_of_week == 0)) ||
				(($day == "Mon") && ($day_of_week == 1)) ||
				(($day == "Tue") && ($day_of_week == 2)) ||
				(($day == "Wed") && ($day_of_week == 3)) ||
				(($day == "Thr") && ($day_of_week == 4)) ||
				(($day == "Fri") && ($day_of_week == 5)) ||
				(($day == "Sat") && ($day_of_week == 6))
			) {
				if (isset($from) && isset($to)) {
					$dt_check = strtotime(date("H:i", strtotime($dt_check)));
					$from = strtotime($from);

					if ($today == "Same Day")
						$to = strtotime($to);
					else
						$to = strtotime(date("H:i", strtotime("+1 day", strtotime($dt_check))));

					if (($from < $dt_check) && ($to > $dt_check)) {
						return true;
					}
				}
			}
		}
	}

	if ($timing_trigger == 'out') {
		$q = "select from_time,to_time,today,day from ex_user_work_hour where status='true'
				  and imei='" . $imei . "' and `user_id`='" . $user_id . "' order by from_time";
		$r = mysqli_query($ms, $q);

		if (!$r) {
			return false;
		}

		$in_time = false;

		while ($timing = mysqli_fetch_array($r)) {
			$from = $timing["from_time"];//str_replace(":",".",$timing["from_time"]);
			$to = $timing["to_time"];//str_replace(":",".",$timing["to_time"]);
			$today = $timing["today"];
			$day = $timing["day"];
			if (
				(($day == "Sun") && ($day_of_week == 0)) ||
				(($day == "Mon") && ($day_of_week == 1)) ||
				(($day == "Tue") && ($day_of_week == 2)) ||
				(($day == "Wed") && ($day_of_week == 3)) ||
				(($day == "Thr") && ($day_of_week == 4)) ||
				(($day == "Fri") && ($day_of_week == 5)) ||
				(($day == "Sat") && ($day_of_week == 6))
			) {
				if (isset($from) && isset($to)) {
					$dt_check = strtotime(date("H:i", strtotime($dt_check)));
					$from = strtotime($from);
					if ($today == "Same Day")
						$to = strtotime($to);
					else
						$to = strtotime(date("H:i", strtotime("+1 day", strtotime($dt_check))));

					if (($from > $dt_check) && ($to < $dt_check)) {
						$in_time = true;
						break;
					}
				}
			}
		}

		if ($in_time == false) {
			return true;
		}
	}

	return false;

}


function check_event_route_trigger($ed, $ud, $loc)
{
	global $ms;

	$user_id = $ed['user_id'];
	$route_trigger = $ed['route_trigger'];
	$routes = $ed['routes'];
	$lat = $loc['lat'];
	$lng = $loc['lng'];

	if (($route_trigger == '') || ($route_trigger == 'off')) {
		return true;
	}

	if ($route_trigger == 'in') {
		$q = "SELECT * FROM `gs_user_routes` WHERE `user_id`='" . $user_id . "' AND `route_id` IN (" . $routes . ")";
		$r = mysqli_query($ms, $q);

		if (!$r) {
			return false;
		}

		while ($route = mysqli_fetch_array($r)) {
			$dist = isPointOnLine($route['route_points'], $loc['lat'], $loc['lng']);

			// get user units and convert if needed
			$units = explode(",", $ud['units']);
			$dist = convDistanceUnits($dist, 'km', $units[0]);

			if ($dist <= $route['route_deviation']) {
				return true;
			}
		}
	}

	if ($route_trigger == 'out') {
		$q = "SELECT * FROM `gs_user_routes` WHERE `user_id`='" . $user_id . "' AND `route_id` IN (" . $routes . ")";
		$r = mysqli_query($ms, $q);

		if (!$r) {
			return false;
		}

		$in_routes = false;

		while ($route = mysqli_fetch_array($r)) {
			$dist = isPointOnLine($route['route_points'], $loc['lat'], $loc['lng']);

			// get user units and convert if needed
			$units = explode(",", $ud['units']);
			$dist = convDistanceUnits($dist, 'km', $units[0]);

			if ($dist <= $route['route_deviation']) {
				$in_routes = true;
				break;
			}
		}

		if ($in_routes == false) {
			return true;
		}
	}

	return false;
}

function check_event_zone_trigger($ed, $ud, $loc)
{
	global $ms;

	$user_id = $ed['user_id'];
	$zone_trigger = $ed['zone_trigger'];
	$zones = $ed['zones'];
	$lat = $loc['lat'];
	$lng = $loc['lng'];

	if (($zone_trigger == '') || ($zone_trigger == 'off')) {
		return true;
	}

	if ($zone_trigger == 'in') {
		$q = "SELECT * FROM `gs_user_zones` WHERE `user_id`='" . $user_id . "' AND `zone_id` IN (" . $zones . ")";
		$r = mysqli_query($ms, $q);

		if (!$r) {
			return false;
		}

		while ($zone = mysqli_fetch_array($r)) {
			$in_zone = isPointInPolygon($zone['zone_vertices'], $lat, $lng);

			if ($in_zone) {
				return true;
			}
		}
	}

	if ($zone_trigger == 'out') {
		$q = "SELECT * FROM `gs_user_zones` WHERE `user_id`='" . $user_id . "' AND `zone_id` IN (" . $zones . ")";
		$r = mysqli_query($ms, $q);

		if (!$r) {
			return false;
		}

		$in_zones = false;

		while ($zone = mysqli_fetch_array($r)) {
			$in_zone = isPointInPolygon($zone['zone_vertices'], $lat, $lng);

			if ($in_zone) {
				$in_zones = true;
				break;
			}
		}

		if ($in_zones == false) {
			return true;
		}
	}

	return false;
}

//Code Update by VETRIVEL.NR

function check_eventsnew($loc, $od, $ud)
{
	try {

		if ($od["triptype"] == "Daily") {
			$ud["boardtype"] = "Daily";
			$ud["tbl"] = "tripdata_daily";
			$ud["tbl_sub"] = "";
			$ud["tblbstatus"] = "gs_user_events_status_boarding_daily";
			check_eventsdaily($loc, $od, $ud);
		} else if ($od["triptype"] == "Scheduled") {
			$ud["boardtype"] = "Scheduled";
			$ud["tbl"] = "tripdata";
			$ud["tbl_sub"] = "tripdata_zonedetail";
			$ud["tblbstatus"] = "gs_user_events_status_boarding";
			check_eventsonce($loc, $od, $ud);
		}
	} catch (Exception $e) {
		echo "DATAOK";
		die;
	}
}

$zone_data = array();
$zone_data["in"] = array();
$zone_data["out"] = array();

function check_eventsonce($loc, $od, $ud)
{
	global $ms, $zone_data;
	// events loop
	$q2 = "select dr.event_id,dr.user_id,dr.imei,dr.route_id,dr. active,dr.dontholiday,dr.week_days,
            dr.notify_system,dr.notify_email,dr.notify_email_address,dr. notify_sms,dr.notify_sms_number,dr.today,
            dr.tfh,dr.tfm,dr.tth,dr.ttm from droute_events dr 
                   where dr.user_id='" . $od['user_id'] . "' and dr.imei='" . $loc['imei'] . "' order by imei,event_id";
	$r2 = mysqli_query($ms, $q2);
	if ($r2 == TRUE) {

		while ($ed = mysqli_fetch_array($r2, MYSQLI_ASSOC)) {
			if ($ed['active'] == 'true') {

				$ed["tblbstatus"] = $ud["tblbstatus"];

				$dt_check = date("Y-m-d H:i:s", strtotime($loc['dt_tracker'] . $ud["timezone"]));
				$day_of_week = date('w', strtotime($dt_check . $ud["timezone"]));

				$week_days = explode(';', $ed['week_days']);

				if ($week_days[$day_of_week] == 'true') {

					$dtftot = ($ed['tfh'] . '.' . $ed['tfm']);
					$dtttot = ($ed['tth'] . '.' . $ed['ttm']);
					$diFend = 23.59;
					$diTfrom = 0;

					$dttchk = (date("H.i", strtotime($loc['dt_tracker'] . $ud["timezone"])));


					$curdateindian = date('Y-m-d', strtotime(gmdate("Y-m-d") . $ud["timezone"]));
					$aftrdate = date('Y-m-d H:i', strtotime('-30 minutes', strtotime($curdateindian . " " . $ed['tfh'] . ":" . $ed['tfm'] . ":00")));
					$dttoday = $ed['today'];
					$aftrdate2 = date('Y-m-d', strtotime($aftrdate));
					if ($curdateindian != $aftrdate2) {
						$dttoday = "Different";
					}

					$dtftot = date('H.i', strtotime('-30 minutes', strtotime($curdateindian . " " . $ed['tfh'] . ":" . $ed['tfm'] . ":00")));
					$dtttot = date('H.i', strtotime('+59 minutes', strtotime($curdateindian . " " . $ed['tth'] . ":" . $ed['ttm'] . ":00")));

					if (
						($dttoday == "Same" && (($dttchk) >= ($dtftot) && ($dttchk) <= ($dtttot)))
						|| ($dttoday == "Different" && (($dttchk >= $dtftot and $dttchk <= $diFend)
							|| ($dttchk >= $diTfrom and $dttchk <= $dtttot)))
					) {

						$qed = "select d.zonecode checked_value,d.zoneinout,d.message,d.point  from   droute_sub d  where d.route_id='" . $ed["route_id"] . "'  order by FIELD(point,'Start','','End') asc";
						$red = mysqli_query($ms, $qed);
						if ($red == TRUE) {
							while ($rowed = mysqli_fetch_array($red, MYSQLI_ASSOC)) {

								$ed["tblbstatus"] = $ud["tblbstatus"];
								$ed['zoneinout'] = $rowed['zoneinout'];
								if ($rowed['zoneinout'] == 'Zone In') {
									$ed['type'] = "zone_in";
								} else if ($rowed['zoneinout'] == 'Zone Out') {
									$ed['type'] = "zone_out";
								}

								$ed['message'] = $rowed['message'];
								$ed['checked_value'] = $rowed['checked_value'];
								$ed['point'] = $rowed['point'];

								//check_event_status_boarding($ed, $loc['imei']);

								$loc["startend"] = $ed['point'];
								$loc["route_id"] = $ed['route_id'];
								if ($ed['zoneinout'] == 'Zone In') {
									event_zone_inboarding($ed, $ud, $od, $loc);
								} else if ($ed['zoneinout'] == 'Zone Out') {
									event_zone_outboarding($ed, $ud, $od, $loc);
								}

							}
						}
					}


				}
			}
		}

	}


}


function check_eventsdaily($loc, $od, $ud)
{
	global $ms;
	// events loop
	$q2 = "select dr.event_id,dr.user_id,dr.imei,dr.route_id,dr. active,dr.dontholiday,
            dr.notify_system,dr.notify_email,dr.notify_email_address,dr. notify_sms,dr.notify_sms_number,
            dr.datefrom,dr.dateto  from droute_events_daily dr  
                   where dr.user_id='" . $od['user_id'] . "' and dr.active='true' AND dr.imei='" . $loc['imei'] . "'  ";

	$r2 = mysqli_query($ms, $q2);
	if ($r2 == TRUE) {
		while ($ed = mysqli_fetch_array($r2, MYSQLI_ASSOC)) {
			if ($ed['active'] == 'true') {
				$ed["tblbstatus"] = $ud["tblbstatus"];
				$tf = $ed['datefrom'];
				$tt = $ed['dateto'];
				$dttchk = (date("Y-m-d H:i:s", strtotime($loc['dt_tracker'] . $ud["timezone"])));
				$dtftot = date('Y-m-d H:i:s', strtotime('-30 minutes', strtotime($tf)));
				$dtttot = date('Y-m-d H:i:s', strtotime('+59 minutes', strtotime($tt)));


				if (($dttchk >= $dtftot) && ($dttchk <= ($dtttot))) {
					$qed = "select d.zonecode checked_value,d.zoneinout,d.message,d.point  from   droute_sub d  where d.route_id='" . $ed["route_id"] . "'  order by FIELD(point,'Start','','End') asc";
					$red = mysqli_query($ms, $qed);
					if ($red == TRUE) {
						while ($rowed = mysqli_fetch_array($red, MYSQLI_ASSOC)) {
							$ed["tblbstatus"] = $ud["tblbstatus"];
							$ed['zoneinout'] = $rowed['zoneinout'];
							if ($rowed['zoneinout'] == 'Zone In') {
								$ed['type'] = "zone_in";
							} else if ($rowed['zoneinout'] == 'Zone Out') {
								$ed['type'] = "zone_out";
							}

							$ed['message'] = $rowed['message'];
							$ed['checked_value'] = $rowed['checked_value'];
							$ed['point'] = $rowed['point'];

							//check_event_status_boarding($ed, $loc['imei']);

							$loc["startend"] = $ed['point'];
							$loc["route_id"] = $ed['route_id'];
							if ($ed['zoneinout'] == 'Zone In') {
								event_zone_inboarding($ed, $ud, $od, $loc);

							} else if ($ed['zoneinout'] == 'Zone Out') {
								event_zone_outboarding($ed, $ud, $od, $loc);
							}
						}
					}
				}
			}
		}

	}
}


function event_zone_inboarding($ed, $ud, $od, $loc)
{
	global $ms;
	$event_status = get_event_status_boarding($ed["event_id"], $loc['imei'], $ed["tblbstatus"]);

	// check if zone still exists, to fix bug if user deletes zone
	$q = "SELECT * FROM `gs_user_zones` WHERE `zone_id`='" . $event_status . "'";
	$r = mysqli_query($ms, $q);

	if (mysqli_num_rows($r) == 0) {
		set_event_status_boarding('-1', $ed['event_id'], $loc['imei'], $ed["tblbstatus"], $event_status);

		$event_status = '-1';
	}


	$q = "SELECT * FROM `gs_user_zones` WHERE `user_id`='" . $ed['user_id'] . "' AND `zone_id` IN (" . $ed['checked_value'] . ")";
	$r = mysqli_query($ms, $q);

	while ($zone = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		$in_zone = isPointInPolygon($zone['zone_vertices'], $loc['lat'], $loc['lng']);

		if ($in_zone) {
			if ($event_status == -1 || $zone['zone_id'] != $event_status) {
				set_event_status_boarding($zone['zone_id'], $ed["event_id"], $loc['imei'], $ed["tblbstatus"]);
				$loc["type"] = "Zone_In";
				$ed['event_desc'] = $ed['zoneinout'] . ' (' . $zone['zone_name'] . ')';
				$loc["zoneid"] = $zone['zone_id'];
				$loc["zonename"] = $zone['zone_name'];
				event_notifyboarding($ed, $ud, $od, $loc);
				//$zone_data["in"][]=$zone['zone_id'];
			}
		} else {
			if ($event_status == $zone['zone_id']) {
				set_event_status_boarding('-1', $ed["event_id"], $loc['imei'], $ed["tblbstatus"], $zone['zone_id']);
			} else
				set_event_status_boarding(-1, $ed["event_id"], $loc['imei'], $ed["tblbstatus"], $zone['zone_id']);
		}
	}
}

function event_zone_outboarding($ed, $ud, $od, $loc)
{
	global $ms;
	$event_status = get_event_status_boarding($ed["event_id"], $loc['imei'], $ed["tblbstatus"]);
	// check if zone still exists, to fix bug if user deletes zone
	$q = "SELECT * FROM `gs_user_zones` WHERE `zone_id`='" . $event_status . "'";
	$r = mysqli_query($ms, $q);

	if (mysqli_num_rows($r) == 0) {
		set_event_status_boarding('-1', $ed['event_id'], $loc['imei'], $ed["tblbstatus"], $event_status);

		$event_status = '-1';
	}

	$q = "SELECT * FROM `gs_user_zones` WHERE `user_id`='" . $ed['user_id'] . "' AND `zone_id` IN (" . $ed['checked_value'] . ")";
	$r = mysqli_query($ms, $q);
	while ($zone = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		$in_zone = isPointInPolygon($zone['zone_vertices'], $loc['lat'], $loc['lng']);
		if ($in_zone) {
			if ($event_status == -1) {
				set_event_status_boarding($zone['zone_id'], $ed["event_id"], $loc['imei'], $ed["tblbstatus"]);
			} else
				set_event_status_boarding($zone['zone_id'], $ed["event_id"], $loc['imei'], $ed["tblbstatus"]);
		} else {

			if ($event_status == $zone['zone_id']) {
				set_event_status_boarding('-1', $ed["event_id"], $loc['imei'], $ed["tblbstatus"], $zone['zone_id']);
				$ed['event_desc'] = $ed['zoneinout'] . ' (' . $zone['zone_name'] . ')';
				$loc["type"] = "Zone_Out";
				$loc["zoneid"] = $zone['zone_id'];
				$loc["zonename"] = $zone['zone_name'];
				event_notifyboarding($ed, $ud, $od, $loc);
				//$zone_data["out"][]=$zone['zone_id'];
			}
		}
	}
}

function event_notifyboarding($ed, $ud, $od, $loc)
{
	global $ms, $gsValues;

	$imei = $loc['imei'];

	if (!checkObjectActive($imei)) {
		return;
	}

	// insert event into list//'".$ed['type']."', update type themself
	$q = "INSERT INTO `gs_user_events_data` (	user_id,
								user_event_id,
								event_desc,
								notify_system,
								notify_arrow,
								notify_arrow_color,
								notify_ohc,
								notify_ohc_color,
								imei,
								dt_server,
								dt_tracker,
								lat,
								lng,
								altitude,
								angle,
								speed,
								params,
								type,
                                zoneid,
                                startend,
                                route_id,ctype,trip_id
								) VALUES (
								'" . $ed['user_id'] . "',
								'" . $ed['event_id'] . "',
								'" . $ed['event_desc'] . "',
								'" . $ed['notify_system'] . "',
								false,
								'arrow_yellow',
								false,
								'#FFFF00',
								'" . $od['imei'] . "',
								'" . $loc['dt_server'] . "',
								'" . $loc['dt_tracker'] . "',
								'" . $loc['lat'] . "',
								'" . $loc['lng'] . "',
								'" . $loc['altitude'] . "',
								'" . $loc['angle'] . "',
								'" . $loc['speed'] . "',
								'" . json_encode($loc['params']) . "',
                                '" . $ed['type'] . "',
                                '" . $loc['zoneid'] . "',
                                '" . $loc['startend'] . "',
                                '" . $loc['route_id'] . "',
                                '" . $ud['boardtype'] . "',
                                '" . $ed['event_id'] . "')";
	$r = mysqli_query($ms, $q);

	if ($loc['startend'] != '') {
		$q = "UPDATE `gs_objects` SET `trip_status`='" . $loc['startend'] . "',`current_trip_id`='" . $ed['event_id'] . "',`trip_start_end_time`='" . $loc['dt_tracker'] . "' where imei='" . $od['imei'] . "'";
		mysqli_query($ms, $q);
	}

	$ed['cmd_send'] = false;
	// send cmd
	if ($ed['cmd_send'] == 'true') {
		if ($ed['cmd_gateway'] == 'gprs') {
			sendObjectGPRSCommand($ed['user_id'], $imei, $ed['event_desc'], $ed['cmd_type'], $ed['cmd_string']);
		} else if ($ed['cmd_gateway'] == 'sms') {
			sendObjectSMSCommand($ed['user_id'], $imei, $ed['event_desc'], $ed['cmd_string']);
		}
	}

	// send email notification
	if (checkUserUsage($ed['user_id'], 'email')) {
		if (($ed['notify_email'] == 'true') && ($ed['notify_email_address'] != '')) {
			$email = $ed['notify_email_address'];
			$ed['email_template_id'] = 0;
			$ed['sms_template_id'] = 0;

			$template = event_notify_template('email', $ed, $ud, $od, $loc);

			$result = sendEmail($email, $template['subject'], $template['message'], true);

			if ($result) {
				//update user usage
				updateUserUsage($ed['user_id'], false, $result, false, false);
			}

			$q2 = "select * from dstudent where user_id`='" . $od['user_id'] . "'  and status='Active'  AND route_id='" . $ed['route_id'] . "'";
			$r2 = mysqli_query($ms, $q2);
			if ($r2 == TRUE) {
				while ($ed2 = mysqli_fetch_array($r2, MYSQLI_ASSOC)) {
					$resultv = sendEmail($ed2["emailid"], $template['subject'], $template['message'], true);

					if ($result) {
						//	update user usage
						updateUserUsage($ed['user_id'], false, $resultv, false, false);
					}
					fndblogemail("EventBoarding", $ud, $loc['type'], $template['message']);
				}
			}
		}
	}

	// send SMS notification
	if (checkUserUsage($ed['user_id'], 'sms')) {
		if (($ed['notify_sms'] == 'true') && ($ed['notify_sms_number'] != '')) {
			$result = false;

			$number = $ed['notify_sms_number'];

			$template = event_notify_template('sms', $ed, $ud, $od, $loc);

			if ($ud['sms_gateway'] == 'true') {
				if ($ud['sms_gateway_type'] == 'http') {
					$result = sendSMSHTTP($ud['sms_gateway_url'], '', $number, $template['message']);

					$q2 = "select * from dstudent where user_id='" . $od['user_id'] . "'  and status='Active'  AND route_id='" . $ed['route_id'] . "'";
					$r2 = mysqli_query($ms, $q2);
					if ($r2 == TRUE) {
						while ($ed2 = mysqli_fetch_array($r2, MYSQLI_ASSOC)) {
							$resultv = sendSMSHTTP($ud['sms_gateway_url'], '', $ed2["phno"], $template['message']);
							if ($resultv) {
								//update user usage
								updateUserUsage($ed['user_id'], false, false, $resultv, false);
							}
						}
					}
				} else if ($ud['sms_gateway_type'] == 'app') {
					$result = sendSMSAPP($ud['sms_gateway_identifier'], '', $number, $template['message']);
				}
			} else {
				if (($ud['sms_gateway_server'] == 'true') && ($gsValues['SMS_GATEWAY'] == 'true')) {
					if ($gsValues['SMS_GATEWAY_TYPE'] == 'http') {
						$result = sendSMSHTTP($gsValues['SMS_GATEWAY_URL'], $gsValues['SMS_GATEWAY_NUMBER_FILTER'], $number, $template['message']);

						$q2 = "select * from dstudent where user_id='" . $od['user_id'] . "'  and status='Active'  AND route_id='" . $ed['route_id'] . "'";
						$r2 = mysqli_query($ms, $q2);
						if ($r2 == TRUE) {
							while ($ed2 = mysqli_fetch_array($r2, MYSQLI_ASSOC)) {
								$resultv = sendSMSHTTP($gsValues['SMS_GATEWAY_URL'], $gsValues['SMS_GATEWAY_NUMBER_FILTER'], $ed2["phno"], $template['message']);
								if ($resultv) {
									//update user usage
									updateUserUsage($ed['user_id'], false, false, $resultv, false);
								}
							}
						}

					} else if ($gsValues['SMS_GATEWAY_TYPE'] == 'app') {
						$result = sendSMSAPP($gsValues['SMS_GATEWAY_IDENTIFIER'], $gsValues['SMS_GATEWAY_NUMBER_FILTER'], $number, $template['message']);
					}
				}
			}

			if ($result) {
				//update user usage
				updateUserUsage($ed['user_id'], false, false, $result, false);
			}
		}
	}
}


function event_notifyboardingold($ed, $ud, $od, $loc)
{
	global $ms;
	global $la;
	global $gsValues;

	// insert event into list
	$q = 'INSERT INTO `gs_user_events_data` (   user_id,
                                event_desc,
                                notify_system,
                                imei,
                                obj_name,
                                dt_server,
                                dt_tracker,
                                lat,
                                lng,
                                altitude,
                                angle,
                                speed,
                                params,
                                type,
                                zoneid,
                                startend,
                                route_id,ctype,trip_id
                                ) VALUES (
                                "' . $ed['user_id'] . '",
                                "' . $ed['event_desc'] . '",
                                "' . $ed['notify_system'] . '",
                                "' . $loc['imei'] . '",
                                "' . $od['name'] . '",
                                "' . $loc['dt_server'] . '",
                                "' . $loc['dt_tracker'] . '",
                                "' . $loc['lat'] . '",
                                "' . $loc['lng'] . '",
                                "' . $loc['altitude'] . '",
                                "' . $loc['angle'] . '",
                                "' . $loc['speed'] . '",
                                "' . $loc['params'] . '",
                                "' . $loc['type'] . '",
                                "' . $loc['zoneid'] . '",
                                "' . $loc['startend'] . '",
                                "' . $loc['route_id'] . '",
                                "' . $ud['boardtype'] . '",
                                "' . $ed['event_id'] . '"
                            )';
	$r = mysqli_query($ms, $q);

	//storetrip($ed,$ud,$od,$loc);

	// send email notification
	if (($ed['notify_email'] == 'true') && ($ed['notify_email_address'] != '')) {
		// load language
		loadLanguage($ud["language"]);

		// add timezone to dt_tracker and dt_server
		$dt_server = date("Y-m-d H:i:s", strtotime($loc['dt_server'] . $ud["timezone"]));
		$dt_tracker = date("Y-m-d H:i:s", strtotime($loc['dt_tracker'] . $ud["timezone"]));

		$email = $ed['notify_email_address'];

		$subject = $od['name'] . ' - ' . $ed['event_desc'] . ' - ' . $gsValues['NAME'];

		$message = $la['HELLO'] . ",\r\n\r\n";
		$message .= $la['THIS_IS_EVENT_MESSAGE'] . "\r\n\r\n";

		$message .= $la['EVENT'] . ": " . $ed['event_desc'] . "\r\n\r\n";

		$message .= $la['OBJECT'] . ": " . $od['name'] . "\r\n";

		if ($od['model'] != '') {
			$message .= $la['TRANSPORT_MODEL'] . ": " . $od['model'] . "\r\n";
		}

		if ($od['plate_number'] != '') {
			$message .= $la['PLATE_NUMBER'] . ": " . $od['plate_number'] . "\r\n";
		}

		$driver = getObjectDriver($ud['id'], $od['imei'], $loc['params']);
		if ($driver['driver_name'] != '') {
			$message .= $la['DRIVER'] . ": " . $driver['driver_name'] . "\r\n";
		}

		$position = 'http://maps.google.com/maps?q=' . $loc['lat'] . ',' . $loc['lng'] . '&t=m';
		$message .= $la['POSITION'] . ": " . $position . "\r\n";

		$speed = $loc['speed'];
		$units = explode(",", $ud['units']);
		$speed = convSpeedUnits($speed, 'km', $units[0]);

		$speed_unit = $la['U_KPH'];
		if (@$units[0] == 'mi') {
			$speed_unit = $la['U_MPH'];
		} else if (@$units[0] == 'nm') {
			$speed_unit = $la['U_KN'];
		}

		$message .= $la['SPEED'] . ": " . $speed . " " . $speed_unit . "\r\n";

		$message .= $la['TIME_POSITION'] . ": " . $dt_tracker . "\r\n";
		$message .= $la['TIME_SERVER'] . ": " . $dt_server;



		sendEmail($email, $subject, $message . "\r\n\r\n" . $ed["message"], true);

		$q2 = "select * from dstudent where user_id`='" . $od['user_id'] . "'  and status='Active'  AND route_id='" . $ed['route_id'] . "'";
		$r2 = mysqli_query($ms, $q2);

		if ($r2 == TRUE) {
			while ($ed2 = mysqli_fetch_array($r2, MYSQLI_ASSOC)) {
				sendEmail($ed2["emailid"], $subject, $message . "\r\n\r\n" . $ed["message"], true);
				fndblogemail("Event", $ud, $loc['type'], $message . "\r\n\r\n" . $ed["message"]);
			}
		}
	}

	// send SMS notification
	if (($ed['notify_sms'] == 'true') && ($ed['notify_sms_number'] != '')) {

		if ($loc['imei'] == "19000984") {
			tempsmsfn($ed['event_desc']);
		}

		// load language
		loadLanguage($ud["language"]);

		// add timezone to dt_tracker and dt_server
		$dt_server = date("Y-m-d H:i:s", strtotime($loc['dt_server'] . $ud["timezone"]));
		$dt_tracker = date("Y-m-d H:i:s", strtotime($loc['dt_tracker'] . $ud["timezone"]));

		$number = $ed['notify_sms_number'];

		$position = 'http://maps.google.com/maps?q=' . $loc['lat'] . ',' . $loc['lng'] . '&t=m';

		$message = "";
		$message .= $la['OBJECT'] . ": " . $od['name'] . "\r\n";
		$message .= $la['EVENT'] . ": " . $ed['event_desc'] . "\r\n";
		$message .= "," . $ed["message"];


		if ($ud['sms_gateway'] == 'true') {
			sendSMS($ud['sms_gateway_url'], $number, $message);
		} else {
			if (($ud['sms_gateway_server'] == 'true') && ($gsValues['SMS_GATEWAY'] == 'true')) {
				sendSMS($gsValues['SMS_GATEWAY_URL'], $number, $message);
			}
		}

		$q2 = "select * from dstudent where user_id='" . $od['user_id'] . "'  and status='Active'  AND route_id='" . $ed['route_id'] . "'";
		$r2 = mysqli_query($ms, $q2);


		if ($r2 == TRUE) {
			while ($ed2 = mysqli_fetch_array($r2, MYSQLI_ASSOC)) {

				if ($ud['sms_gateway'] == 'true') {
					sendSMS($ud['sms_gateway_url'], $ed2["phno"], $ed["message"]);
				} else {
					if (($ud['sms_gateway_server'] == 'true') && ($gsValues['SMS_GATEWAY'] == 'true')) {
						sendSMS($gsValues['SMS_GATEWAY_URL'], $ed2["phno"], $ed["message"]);
					}
				}

			}
		}

	}
}

function get_event_status_boarding($event_id, $imei, $tblstatus)
{
	global $ms;
	$result = '-1';

	$q = "SELECT event_status FROM " . $tblstatus . " WHERE `event_id`='" . $event_id . "' AND `imei`='" . $imei . "'";
	$r = mysqli_query($ms, $q);
	$row = mysqli_fetch_array($r);

	if ($row) {
		$result = $row['event_status'];
	} else {
		$q = "INSERT INTO " . $tblstatus . " (`event_id`,`imei`,`event_status`) VALUES ('" . $event_id . "','" . $imei . "','-1')";
		$r = mysqli_query($ms, $q);
	}

	return $result;

}

function set_event_status_boarding($value, $event_id, $imei, $tblstatus, $cur_Zone_id = -1)
{
	global $ms;
	//$q = "UPDATE ".$tblstatus." SET `event_status`='".$value."' WHERE `event_id`='".$event_id."' AND `imei`='".$imei."' and event_status!='-1'";

	if ($value == $cur_Zone_id && $cur_Zone_id == -1)
		return;

	if ($value == -1) {
		$q = "UPDATE " . $tblstatus . " SET `event_status`='" . $value . "' WHERE `event_id`='" . $event_id . "' AND `imei`='" . $imei . "' and event_status=" . $cur_Zone_id;
		$r = mysqli_query($ms, $q);

	} else {
		$q = "UPDATE " . $tblstatus . " SET `event_status`='" . $value . "' WHERE `event_id`='" . $event_id . "' AND `imei`='" . $imei . "' and event_status!=" . $value;
		$r = mysqli_query($ms, $q);

	}
}

function change_event_status_boarding($value, $event_id, $imei, $tblstatus)
{
	global $ms;
	//$q = "UPDATE ".$tblstatus." SET `event_status`='".$value."' WHERE `event_id`='".$event_id."' AND `imei`='".$imei."' and event_status!='-1'";
	$q = "UPDATE " . $tblstatus . " SET `event_status`='" . $value . "' WHERE `event_id`='" . $event_id . "' AND `imei`='" . $imei . "' ";
	$r = mysqli_query($ms, $q);

}

function create_event_status_boarding($value, $event_id, $imei, $tblstatus)
{
	global $ms;
	$q = "INSERT  INTO " . $tblstatus . " (`event_id`,`imei`,`event_status`) SELECT * FROM
			(select " . $event_id . " event_id, " . $imei . " imei, " . $value . " event_status) AS tmp WHERE NOT EXISTS ( SELECT * FROM  " . $tblstatus . "  WHERE imei ='" . $imei . "' and `event_id`='" . $event_id . "' and `event_status`='" . $value . "') LIMIT 1;";
	$r = mysqli_query($ms, $q);
}


function check_event_status_boarding($ed, $imei)
{
	global $ms;
	$q = "SELECT event_status FROM " . $ed["tblbstatus"] . " WHERE `event_id`='" . $ed['event_id'] . "' AND  `imei`='" . $imei . "'";

	$r = mysqli_query($ms, $q);

	if ($r == TRUE) {
		$row = mysqli_fetch_array($r, MYSQLI_ASSOC);

		// check if event status record exists, if not - create it
		if ($row) {
			// check if zone still exists, to fix bug if user deletes zone
			if (($ed['zoneinout'] == 'Zone Out') || ($ed['zoneinout'] == 'Zone In')) {
				$q = "SELECT zone_id FROM `gs_user_zones` WHERE `zone_id`='" . $row['event_status'] . "'";
				$r = mysqli_query($ms, $q);
				$is_data = mysqli_num_rows($r);
				if ($is_data == 0) {
					$q = "UPDATE " . $ed["tblbstatus"] . " SET `event_status`='-1' WHERE `event_id`='" . $ed['event_id'] . "'
													
													AND `imei`='" . $imei . "'";
					$r = mysqli_query($ms, $q);
				}
			}
		} else {
			$q = "INSERT INTO " . $ed["tblbstatus"] . " (`event_id`,`imei`,`event_status`) VALUES (" . $ed['event_id'] . ",'" . $imei . "','-1')";
			$r = mysqli_query($ms, $q);
		}

	} else {
		$q = "INSERT INTO " . $ed["tblbstatus"] . " (`event_id`,`imei`,`event_status`) VALUES (" . $ed['event_id'] . ",'" . $imei . "','-1')";
		$r = mysqli_query($ms, $q);
	}
}


function fndblogemail($type, $ud, $eventtpe, $message)
{
	global $ms;
	try {
		$q = "INSERT INTO emaillog ( datetime, user_id, count,type,event_type,mailcontent) VALUES ('" . date("Y-m-d H:i:s") . "'," . $ud["id"] . ",1,'" . $type . "','" . $eventtpe . "','" . $message . "')";
		$result = mysqli_query($ms, $q);

	} catch (Exception $e) {
	}
}

function fnlog_daily($loc, $od, $ud, $id_where, $loc_events = 'empty')
{
	global $ms;
	try {
		//if($ud["id"]==279 &&$loc["imei"]=="6120317536")
		if ($loc["imei"] == "6120317536") {
			$q = "INSERT INTO delete_dailyrpt (user_id,imei,dt_server,dt_tracker,lat,lng,loc,object,user)
		 	VALUES ('279','" . $loc["imei"] . "','" . $loc["dt_server"] . "','" . $loc["dt_tracker"] . "','" . $loc["lat"] . "','" . $loc["lng"] . "','" . json_encode($loc) . "','" . $od["dt_tracker"] . "','" . $id_where . "-" . $loc_events . "')";
			$result = mysqli_query($ms, $q);

		}
	} catch (Exception $e) {
	}
}

function checkRoute_inOut_Status($loc)
{
	global $ms;
	$cloc = $loc;
	$return = 2;
	$q = "SELECT trip_start_end_time,current_trip_id FROM `gs_objects` where `active`='true' and `trip_status`='Start' and `imei`='" . $loc['imei'] . "' and `trip_start_end_time`<'" . $cloc['dt_tracker'] . "' LIMIT 1";

	$r = mysqli_query($ms, $q);
	if (mysqli_num_rows($r) > 0) {
		$row = mysqli_fetch_assoc($r);
		if ($row['current_trip_id'] != '' and $row['current_trip_id'] > 0) {
			// $qt="SELECT route_id,tfh,tfm,tth,ttm,fromday,today,daysinterval FROM `droute_events` where `event_id`='".$row['current_trip_id']."' LIMIT 1";
			$qt = "SELECT a.tfh,a.tfm,a.tth,a.ttm,a.fromday,a.today,a.daysinterval,b.route_id,b.trip_route_id,c.route_points FROM droute_events a LEFT JOIN droute b ON a.route_id=b.route_id LEFT JOIN gs_user_routes c ON b.trip_route_id=c.route_id where a.event_id='" . $row['current_trip_id'] . "' LIMIT 1";

			$rt = mysqli_query($ms, $qt);
			if (mysqli_num_rows($rt) > 0) {
				$rowT = mysqli_fetch_assoc($rt);
				if ($rowT['trip_route_id'] != '') {

					$cloc['route_zone'] = $rowT['route_points'];
					$tripstarttime = date('Y-m-d', strtotime(convUserTimezone($row["trip_start_end_time"])));
					// approximate start and end time 
					$apr_start = date('Y-m-d H:i:s', strtotime('-30 minutes', strtotime($tripstarttime . " " . $rowT['tfh'] . ":" . $rowT['tfm'] . ":00")));

					if ($rowT['today'] == 'Different') {
						$spr_end = date('Y-m-d H:i:s', strtotime('+59 minutes', strtotime($tripstarttime . " " . $rowT['tth'] . ":" . $rowT['ttm'] . ":00")));
					} else {
						$spr_end = date('Y-m-d H:i:s', strtotime('+59 minutes', strtotime($tripstarttime . " " . $rowT['tth'] . ":" . $rowT['ttm'] . ":00")));
					}
					if (strtotime($spr_end) > strtotime($cloc['dt_tracker'])) {
						$url = 'http://165.232.188.166:8080/GetRouteEvent/';
						$postdata = json_encode($cloc);

						$ch = curl_init($url);
						// curl_setopt_array($ch,[
						// 	CURLOPT_POST=>1,
						// 	CURLOPT_URL => $url,
						// 	CURLOPT_USERAGENT => 'Event Engin',
						// 	CURLOPT_POSTFIELDS => $postdata,
						// 	CURLOPT_HTTPHEADER=>array('Content-Type: application/json'),
						// 	CURLOPT_RETURNTRANSFER=>1

						// ]);
						curl_setopt($ch, CURLOPT_POST, 1);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
						$result = curl_exec($ch);
						curl_close($ch);
						// print_r ($result);
						$result = json_decode($result, true);

						if ($result['data'] == '1') {
							$loc['event'] = 'triproutedivert';
							// check_events($loc, true, true, false);
							$return = 1;
						} else {
							$return = 0;
						}

						$myfile = fopen("vvv_route.txt", "a");
						fwrite($myfile, json_encode($loc));
						fwrite($myfile, "\n");
						fwrite($myfile, json_encode($result));
						fwrite($myfile, "\n");
						fwrite($myfile, $result['data']);
						fwrite($myfile, "\n");
						fwrite($myfile, '****************************');
						fwrite($myfile, "\n");
						fclose($myfile);

					} else {
						$qU = "UPDATE `gs_objects` SET `current_trip_id`='',`trip_status`='' where `imei`='" . $cloc['imei'] . "'";
						mysqli_query($ms, $qU);
					}
				}
			}
		}
	}
	return $return;
}

?>