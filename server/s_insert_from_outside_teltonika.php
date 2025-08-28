<?
if(isset($_POST['rfid'])){
	$_POST['rfid']=ltrim($_POST['rfid'], "0");
}
	include ('s_init.php');
	include ('../func/fn_common.php');
	include ('../tools/gc_func.php');
	include ('s_events.php');



	 

	if(@$_POST['method'] == 'teltoInsertNew_rfid')
	{
		$loc=[];
		$loc_prev = get_gs_objects_data(@$_POST['imei']);
		$_POST['lat'] = @$loc_prev['lat'];
		$_POST['lng'] = @$loc_prev['lng'];
		$loc['protocol'] = @$_POST['protocol'];
		$loc['net_protocol'] = "";
		$loc['ip'] = @$_SERVER['REMOTE_ADDR'];
		$loc['port'] = @$_POST['port'];

		/*if(isset($_POST["imei"]))
		{
		*/
		$loc['imei'] = $_POST['imei'];
		$loc['lat'] = $_POST['lat'];
		$loc['lng'] = $_POST['lng'];
		$loc['dt_tracker'] = $_POST['dt_tracker'];
		$loc['dt_server'] = gmdate("Y-m-d H:i:s");
		$loc['alarm'] = 'rfid';
		$loc['rfid'] = @$_POST['rfid'];

		check_events($loc, true, true, false);
	 	echo 'RFIDOK';
	 }

	include ('NewModule.php');
	

	//  if(@$_POST['imei']=='353691847291167')
	// {
	//  	$myfile = fopen("1167_vvv_teltonika.txt", "a");
	//  	fwrite($myfile,json_encode($_POST));
	//  	fwrite($myfile, "\n");
	//  	fclose($myfile);
	// }



	/*
	 if(isset($_POST['imei']))
	{
		// $myfile = fopen("0ilocktracker_rfid.txt", "a");		
		// fwrite($myfile,json_encode($_POST));
		// fwrite($myfile, "\n");
		// fclose($myfile);

		$ch = curl_init('http://dispenser.pistos-iot.com/beta/server/s_insert_from_outside_teltonika.php');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);

		// execute!
		$response = curl_exec($ch);

		// close the connection, release resources used
		curl_close($ch);

		// do anything you want with your response
		var_dump($response);
	}
	*/

	if(@$_POST['method'] == 'teltoInsertNew')
	{
		$loc['protocol'] = @$_POST['protocol'];
		$loc['net_protocol'] = "";
		$loc['ip'] = @$_SERVER['REMOTE_ADDR'];
		$loc['port'] = @$_POST['port'];

		/*if(isset($_POST["imei"]))
		{
		*/
		$loc['imei'] = $_POST['imei'];
		$loc['lat'] = $_POST['lat'];
		$loc['lng'] = $_POST['lng'];
		$loc['dt_tracker'] = $_POST['datetime'];
		$loc['dt_server'] = gmdate("Y-m-d H:i:s");
		$loc['altitude'] = @$_POST['altitude'];
		$loc['angle'] = $_POST['angle'];
		$loc['speed'] = $_POST['speed'];
		$loc['type'] = @$_POST['type'];
		$loc['alarm'] = @$_POST['alarm'];
		if(isset($_POST['total_odometer'])){
			$_POST['total_odometer']=$_POST['total_odometer']/1000;
		}
		if(@$_POST['fuel1']==0 && @$_POST['lls_fuel1']>5500){
			$_POST['lls_fuel1']=0;
		}
		if(@$_POST['analog_input1']>'1100'){
			$_POST['di1']='1';
		}else{
			$_POST['di1']='0';
		}
		//$loc['params'] = $_POST['params'];
		//$loc['params'] ="acc=".@$_POST['ignition']."|"."signal_gps=".@$_POST['gsm_signal']."|"."data_mode=".@$_POST['data_mode']."|"."fuel1=".@$_POST['fuel1']."|"."lls_fuel1=".@$_POST['lls_fuel1']."|";
		$loc['params'] ="acc=".@$_POST['ignition']."|"."signal_gps=".@$_POST['gsm_signal']."|"."data_mode=".@$_POST['data_mode']."|"."fuel1=".@$_POST['fuel1']."|"."lls_fuel1=".@$_POST['lls_fuel1']."|"."fuel2=".@$_POST['fuel2']."|"."lls_fuel2=".@$_POST['lls_fuel2']."|"."fuel3=".@$_POST['fuel3']."|"."lls_fuel3=".@$_POST['lls_fuel3']."|"."ble_temp1=".@$_POST['ble_temp1']."|"."ble_temp2=".@$_POST['ble_temp2']."|"."ble_temp3=".@$_POST['ble_temp3']."|"."Bfreq1=".@$_POST['ble_fuel_freq1']."|"."Bfreq2=".@$_POST['ble_fuel_freq2']."|"."Bfreq3=".@$_POST['ble_fuel_freq3']."|"."Bfreq4=".@$_POST['ble_fuel_freq4']."|"."fuelhz1=".checkhz(@$_POST['ble_fuel_freq1'])."|"."fuelhz2=".checkhz(@$_POST['ble_fuel_freq2'])."|"."fuelhz3=".checkhz(@$_POST['ble_fuel_freq3'])."|"."fuelhz4=".checkhz(@$_POST['ble_fuel_freq4'])."|"."temp1=".@$_POST['lls_temp1']."|"."odo=".@$_POST['total_odometer']."|"."analog_input1=".@$_POST['analog_input1']."|"."analog_input2=".@$_POST['analog_input2']."|"."di1=".@$_POST['di1']."|"."external_voltage=".@$_POST['external_voltage']."|";

		$loc['params'] = paramsToArray($loc['params']);
		$loc['params']['fuelhz5'] = 0;

		
		// if($loc['params']['fuelhz1']>0){
		// 	$fuel1=check_sen_calibration($loc['params']['fuelhz1'],$loc['imei'],'fuelhz1');
		// 	$loc['params']['fuel1']=$fuel1;
		// }
		// if($loc['params']['fuelhz2']>0){
		// 	$fuel2=check_sen_calibration($loc['params']['fuelhz2'],$loc['imei'],'fuelhz2');
		// 	$loc['params']['fuel2']=$fuel2;
		// }
		// if($loc['params']['fuelhz3']>0){
		// 	$fuel3=check_sen_calibration($loc['params']['fuelhz3'],$loc['imei'],'fuelhz3');
		// 	$loc['params']['fuel3']=$fuel3;
		// }
		// if($loc['params']['fuelhz4']>0){
		// 	$fuel4=check_sen_calibration($loc['params']['fuelhz4'],$loc['imei'],'fuelhz4');
		// 	$loc['params']['fuel4']=$fuel4;
		// }
		// if($loc['params']['fuelhz5']>0){
		// 	$fuel5=check_sen_calibration($loc['params']['fuelhz5'],$loc['imei'],'fuelhz5');
		// 	$loc['params']['fuel5']=$fuel5;
		// }


		if(@$_POST["pwrcut"] == "1" || @$_POST["pwrcut"] == 1 ) {
			$_POST["event"] = "pwrcut";
			$_POST["alarm"] = "pwrcut";
		}

		if($_POST['gsm_signal']!=null && $_POST['gsm_signal']>0)
		{
			$loc['loc_valid'] = '1'; //$_POST['signal_gps'];
		}
		else
		{
			$loc['loc_valid'] = '0'; //$_POST['signal_gps'];
		}
		$loc['event'] = @$_POST['event'];
		insert_db_loc($loc);
	

		echo "DATAOK";

	}

	
	function insert_db_loc($loc)
	{
		global $ms;
		
		// format data
		$loc['imei'] = strtoupper(trim($loc['imei']));
		$loc['lat'] = (double)sprintf('%0.6f', $loc['lat']);
		$loc['lng'] = (double)sprintf('%0.6f', $loc['lng']);
		$loc['altitude'] = floor($loc['altitude']);
		$loc['angle'] = floor($loc['angle']);
		$loc['speed'] = floor($loc['speed']);
		//$loc['protocol'] = strtolower($loc['protocol']);
		$loc['protocol'] = ($loc['protocol']);
		$loc['net_protocol'] = strtolower($loc['net_protocol']);
		
		// check for wrong IMEI
		if (!ctype_alnum($loc['imei']))
		{
			return false;
		}
		
		// check for wrong speed
		if ($loc['speed'] > 200)
		{
			return false;
		}
		
		// check if object exists in system
		if (!checkObjectExistsSystem($loc['imei']))
		{
			insert_db_unused($loc);
			return false;
		}
		
		// adjust GPS time
		$loc['dt_tracker'] = adjustObjectTime($loc['imei'], $loc['dt_tracker']);
		
		// check if dt_tracker is one day too far - skip coordinate		      
		if (strtotime($loc['dt_tracker']) >= strtotime(gmdate("Y-m-d H:i:s").' +1 days'))
		{
			return false;
		}
		
		// check if dt_tracker is at least one hour too far - set 0 UTC time
		if (strtotime($loc['dt_tracker']) >= strtotime(gmdate("Y-m-d H:i:s").' +1 hours'))
		{
			Insert_Issue($loc['imei'],'TrackerTime',json_encode($loc),1);
			$loc['dt_tracker'] = gmdate("Y-m-d H:i:s");
		}
		
		// get previous known location
		$loc_prev = get_gs_objects_data($loc['imei']);
		
		if($loc_prev["device"]=="I-Tracker")
		{
			$sensor_all = get_allsensor_calibration($loc['imei']);
			if(isset($sensor_all))
			{
				$sensor1 = @$sensor_all["fuelhz1"];
				$sensor2 = @$sensor_all["fuelhz2"];
				$sensor3 = @$sensor_all["fuelhz3"];
				$sensor4 = @$sensor_all["fuelhz4"];
				$sensor5 = @$sensor_all["fuelhz5"];
				
				if(isset($sensor1) && $loc['params']['fuelhz1']>0)
				{
					$rawhz = $loc["params"]["fuelhz1"];		
					$raw1 = get_final_ltrs(-1,$rawhz,$sensor1);
	            	$rawliter=$raw1[0];
	            	$rawlevel=$raw1[1];					
					$loc["params"]["fuel1"] = $rawliter;
				}
				
				if(isset($sensor2) && $loc['params']['fuelhz2']>0)
				{
					$rawhz = $loc["params"]["fuelhz2"];				 
					$raw1 = get_final_ltrs(-1,$rawhz,$sensor2);
	            	$rawliter=$raw1[0];
	            	$rawlevel=$raw1[1];			
	            	
					$loc["params"]["fuel2"] = $rawliter;
				}
				
				if(isset($sensor3) && $loc['params']['fuelhz3']>0)
				{
					$rawhz = $loc["params"]["fuelhz3"];		
					$raw1 = get_final_ltrs(-1,$rawhz,$sensor3);
	            	$rawliter=$raw1[0];
	            	$rawlevel=$raw1[1];		
					$loc["params"]["fuel3"] = $rawliter;
				}
				
				if(isset($sensor4) && $loc['params']['fuelhz4']>0)
				{
					$rawhz = $loc["params"]["fuelhz4"];				 
					$raw1 = get_final_ltrs(-1,$rawhz,$sensor4);
	            	$rawliter=$raw1[0];
	            	$rawlevel=$raw1[1];		
					$loc["params"]["fuel4"] = $rawliter;
				}
				
				if(isset($sensor5) && $loc['params']['fuelhz5']>0)
				{
					$rawhz = $loc["params"]["fuelhz5"];	
					$raw1 = get_final_ltrs(-1,$rawhz,$sensor5);
	            	$rawliter=$raw1[0];
	            	$rawlevel=$raw1[1];		
					$loc["params"]["fuel5"] = $rawliter;
				}
				
			}
		}

		// merge params only if dt_tracker is newer
		if (strtotime($loc['dt_tracker']) >= strtotime($loc_prev['dt_tracker']))
		{
			$loc['params'] = mergeParams($loc_prev['params'], $loc['params']);
		}
		
		if (($loc['speed']-$loc_prev['speed'])>60 && $loc['speed']>100 && $loc_prev['speed']<100 )
		{
			return false;
		}
		
		if ($loc_prev['speed'] > 100 && $loc_prev['speed']<$loc['speed'] && ($loc['speed']-$loc_prev['speed'])>25 )
		{
			return false;
		}

		
		$arr2odo=insert_db_odo_engh($loc, $loc_prev);
		
		//$loc=Replacelossdata($loc,$loc_prev,$arr2odo);
				
		insert_db_objects($loc, $loc_prev);
		
		insert_db_status($loc, $loc_prev);
		
		insert_db_ri($loc, $loc_prev);
		
		insert_db_dtc($loc);
		
		

		// check for duplicate locations
		if (loc_filter($loc, $loc_prev) == false)
		{
			//if(@$_POST['alarm']!="true")
			//{
				insert_db_object_data($loc,$loc_prev,$arr2odo);
			//}
			/*
			if ($loc['loc_valid'] == 0)
			{
				if (($loc['lat'] == 0) || ($loc['lng'] == 0))
				{
					$loc['dt_tracker'] = $loc_prev['dt_tracker'];
					$loc['lat'] = $loc_prev['lat'];
					$loc['lng'] = $loc_prev['lng'];
					$loc['altitude'] = $loc_prev['altitude'];
					$loc['angle'] = $loc_prev['angle'];
					$loc['speed'] = $loc_prev['speed'];
				}
			}
			
			// check for local events if dt_tracker is newer, in other case only tracker events will be checked
			if (($loc['lat'] != 0) && ($loc['lng'] != 0))
			{
				// check for local events if dt_tracker is newer, in other case only tracker events will be checked
				if (strtotime($loc['dt_tracker']) >= strtotime($loc_prev['dt_tracker']))
				{
					//check_events($loc, true, true, false);
					check_events($loc, true, true, false);
				}
				else
				{
					//check_events($loc, false, false, false);
					check_events($loc, true, true, false);
				}
			}
			*/
			// PostRedBus($loc,$loc_prev);
		}
		
		PostRedBus($loc,$loc_prev);

		if ($loc['loc_valid'] == 0)
		{
			if (($loc['lat'] == 0) || ($loc['lng'] == 0))
			{
				$loc['dt_tracker'] = $loc_prev['dt_tracker'];
				$loc['lat'] = $loc_prev['lat'];
				$loc['lng'] = $loc_prev['lng'];
				$loc['altitude'] = $loc_prev['altitude'];
				$loc['angle'] = $loc_prev['angle'];
				$loc['speed'] = $loc_prev['speed'];
			}
		}
			
		// check for local events if dt_tracker is newer, in other case only tracker events will be checked
		if (($loc['lat'] != 0) && ($loc['lng'] != 0))
		{
			// check for local events if dt_tracker is newer, in other case only tracker events will be checked
			if (strtotime($loc['dt_tracker']) >= strtotime($loc_prev['dt_tracker']))
			{
				//check_events($loc, true, true, false);
				check_events($loc, true, true, false);
			}
			else
			{
				check_events($loc, false, false, false);
				//check_events($loc, true, true, false);
			}
		}
	}
	
	function insert_db_noloc($loc)
	{
		global $ms;
		
		// format data
		$loc['imei'] = strtoupper(trim($loc['imei']));
		$loc['protocol'] = strtolower($loc['protocol']);
		$loc['net_protocol'] = strtolower($loc['net_protocol']);
		
		// check for wrong IMEI
		if (!ctype_alnum($loc['imei']))
		{
			return false;
		}
		
		// get previous known location
		$loc_prev = get_gs_objects_data($loc['imei']);
		
		if ($loc_prev != false)
		{
			// add previous known location
			$loc['dt_tracker'] = $loc_prev['dt_tracker'];
			$loc['lat'] = $loc_prev['lat'];
			$loc['lng'] = $loc_prev['lng'];
			$loc['altitude'] = $loc_prev['altitude'];
			$loc['angle'] = $loc_prev['angle'];
			$loc['speed'] = $loc_prev['speed'];
			$loc['loc_valid'] = $loc_prev['loc_valid'];
			$loc['params'] = mergeParams($loc_prev['params'], $loc['params']);
			
			$q = "UPDATE gs_objects SET 	`protocol`='".$loc['protocol']."',
							`net_protocol`='".$loc['net_protocol']."',
							`ip`='".$loc['ip']."',
							`port`='".$loc['port']."',
							`dt_server`='".$loc['dt_server']."',
							`params`='".json_encode($loc['params'])."'
							WHERE imei='".$loc['imei']."'";
							
			$r = mysqli_query($ms, $q) or die(mysqli_error($ms));
			
			// check if location exists
			if (($loc['lat'] != 0) && ($loc['lng'] != 0))
			{
				insert_db_status($loc, $loc_prev);
				
				insert_db_dtc($loc);
				
				check_events($loc, false, true, false);
			}
		}
	}
	
	function insert_db_imgloc($loc)
	{
		global $ms, $gsValues;
		
		// format data
		$loc['imei'] = strtoupper(trim($loc['imei']));
		$loc['lat'] = (double)sprintf('%0.6f', $loc['lat']);
		$loc['lng'] = (double)sprintf('%0.6f', $loc['lng']);
		$loc['altitude'] = floor($loc['altitude']);
		$loc['angle'] = floor($loc['angle']);
		$loc['speed'] = floor($loc['speed']);
		$loc['protocol'] = strtolower($loc['protocol']);
		$loc['net_protocol'] = strtolower($loc['net_protocol']);
		
		// check for wrong IMEI
		if (!ctype_alnum($loc['imei']))
		{
			return false;
		}
		
		// check if object exists in system
		if (!checkObjectExistsSystem($loc['imei']))
		{
			return false;
		}
		
		$img_file = $loc['imei'].'_'.$loc['dt_tracker'].'.jpg';
		$img_file = str_replace('-', '', $img_file);
		$img_file = str_replace(':', '', $img_file);
		$img_file = str_replace(' ', '_', $img_file);
		
		// save to database
		$q = "INSERT INTO gs_object_img (img_file,
						imei,
						dt_server,
						dt_tracker,
						lat,
						lng,
						altitude,
						angle,
						speed,
						params
						) VALUES (
						'".$img_file."',
						'".$loc['imei']."',
						'".$loc['dt_server']."',
						'".$loc['dt_tracker']."',
						'".$loc['lat']."',
						'".$loc['lng']."',
						'".$loc['altitude']."',
						'".$loc['angle']."',
						'".$loc['speed']."',
						'".json_encode($loc['params'])."')";
				    
		$r = mysqli_query($ms, $q);
		
		 // save file
		$img_path = $gsValues['PATH_ROOT'].'/data/img/';
		$img_path = $img_path.basename($img_file);
		
		$postdata = hex2bin($loc["img"]);
				
		if(substr($postdata,0,3) == "\xFF\xD8\xFF")
		{
			$fp = fopen($img_path,"w");
			fwrite($fp,$postdata);
			fclose($fp);
		}
	}
	
	function insert_db_unused($loc)
	{
		global $ms;
		
		$q = "INSERT INTO `gs_objects_unused` (imei, protocol, net_protocol, ip, port, dt_server, count)
						VALUES ('".$loc['imei']."', '".$loc['protocol']."', '".$loc['net_protocol']."', '".$loc['ip']."', '".$loc['port']."', '".$loc['dt_server']."', '1')
						ON DUPLICATE KEY UPDATE protocol = '".$loc['protocol']."', net_protocol = '".$loc['net_protocol']."', ip = '".$loc['ip']."', port = '".$loc['port']."', dt_server = '".$loc['dt_server']."', count = count + 1";
		$r = mysqli_query($ms, $q) or die(mysqli_error($ms));
	}
	
	function insert_db_objects($loc, $loc_prev)
	{
		global $ms;
		
		if (strtotime($loc['dt_tracker']) >= strtotime($loc_prev['dt_tracker']))
		{
			if ($loc['loc_valid'] == 1)
			{
				// calculate angle
				$loc['angle'] = getAngle($loc_prev['lat'], $loc_prev['lng'], $loc['lat'], $loc['lng']);
				
				$q = "UPDATE gs_objects SET	`protocol`='".$loc['protocol']."',
								`net_protocol`='".$loc['net_protocol']."',
								`ip`='".$loc['ip']."',
								`port`='".$loc['port']."',
								`dt_server`='".$loc['dt_server']."',
								`dt_tracker`='".$loc['dt_tracker']."',
								`lat`='".$loc['lat']."',
								`lng`='".$loc['lng']."',
								`altitude`='".$loc['altitude']."',
								`angle`='".$loc['angle']."',
								`speed`='".$loc['speed']."',
								`loc_valid`='1',
								`params`='".json_encode($loc['params'])."'
								WHERE imei='".$loc['imei']."'";
								
				$r = mysqli_query($ms, $q) or die(mysqli_error($ms));

				
				//update_dispenser_imei($loc['imei'],$loc['params']);


			}
			else
			{
				$loc['speed'] = 0;
				
				$q = "UPDATE gs_objects SET 	`protocol`='".$loc['protocol']."',
								`net_protocol`='".$loc['net_protocol']."',
								`ip`='".$loc['ip']."',
								`port`='".$loc['port']."',
								`dt_server`='".$loc['dt_server']."',
								`speed`='".$loc['speed']."',
								`loc_valid`='0',
								`params`='".json_encode($loc['params'])."'
								WHERE imei='".$loc['imei']."'";
								
				$r = mysqli_query($ms, $q) or die(mysqli_error($ms));
			}
		}
		else
		{
			$q = "UPDATE gs_objects SET 	`protocol`='".$loc['protocol']."',
							`net_protocol`='".$loc['net_protocol']."',
							`ip`='".$loc['ip']."',
							`port`='".$loc['port']."',
							`dt_server`='".$loc['dt_server']."'
							WHERE imei='".$loc['imei']."'";
							
			$r = mysqli_query($ms, $q) or die(mysqli_error($ms));

		}
	}
	
	function insert_db_object_data($loc,$loc_prev,$arr2odo)
	{
		global $ms;
		if (($loc['lat'] != 0) && ($loc['lat'] != 0))
		{
			$mileage=round($arr2odo["odovalue"],4);

			//if(isset($loc["params"]["odo"]) && $loc_prev["odometer_type"]!="gps")
			//$mileage=round($loc["params"]["odo"],4);
			
			if($mileage==0)
			{
				$mileage=$loc_prev["odometer"];
				//$myfile = fopen("vvv_odo_0.txt", "a");
				//fwrite($myfile,json_encode($arr2odo).'---'.json_encode($loc).'---'.json_encode($loc_prev) );
				//fwrite($myfile, "\n");
				//fclose($myfile);
			}
			$loc['params']["odo"]=$mileage;
			$q = "INSERT INTO gs_object_data_".$loc['imei']."(	dt_server,
										dt_tracker,
										lat,
										lng,
										altitude,
										angle,
										speed,
										params,mileage
										) VALUES (
										'".$loc['dt_server']."',
										'".$loc['dt_tracker']."',
										'".$loc['lat']."',
										'".$loc['lng']."',
										'".$loc['altitude']."',
										'".$loc['angle']."',
										'".$loc['speed']."',
										'".json_encode($loc['params'])."',
										'".$mileage."')";

			$r = mysqli_query($ms, $q) or die(mysqli_error($ms));
			Insert_Issue($loc['imei'],'Mileage0',$q,1);
		}
	}
	
	function insert_db_status($loc, $loc_prev)
	{
		global $ms;
		
		if (strtotime($loc['dt_tracker']) >= strtotime($loc_prev['dt_tracker']))
		{
			$imei = $loc['imei'];
			$params = $loc['params'];
			
			$dt_last_stop = strtotime($loc_prev['dt_last_stop']);
			$dt_last_idle = strtotime($loc_prev['dt_last_idle']);
			$dt_last_move = strtotime($loc_prev['dt_last_move']);
			$dt_last_aconidle= strtotime($loc_prev['dt_last_aconidle']);
			if ($loc['loc_valid'] == 1)
			{
				// status stop
				if ((($dt_last_stop <= 0) || ($dt_last_stop < $dt_last_move)) && ($loc['speed'] == 0))
				{
					$q = "UPDATE gs_objects SET `dt_last_stop`='".$loc['dt_server']."' WHERE imei='".$imei."'";			
					$r = mysqli_query($ms, $q) or die(mysqli_error($ms));
					
					$dt_last_stop = strtotime($loc['dt_server']);
				}
				
				// status moving
				if (($dt_last_stop >= $dt_last_move) && ($loc['speed'] > 0))
				{
					$q = "UPDATE gs_objects SET `dt_last_move`='".$loc['dt_server']."' WHERE imei='".$imei."'";			
					$r = mysqli_query($ms, $q) or die(mysqli_error($ms));
					
					$dt_last_move = strtotime($loc['dt_server']);
				}
			}
			else
			{
				// status stop
				if ((($dt_last_stop <= 0) || ($dt_last_stop < $dt_last_move)) && ($loc['speed'] == 0))
				{
					$q = "UPDATE gs_objects SET `dt_last_stop`='".$loc['dt_server']."' WHERE imei='".$imei."'";			
					$r = mysqli_query($ms, $q) or die(mysqli_error($ms));
					
					$dt_last_stop = strtotime($loc['dt_server']);
				}
			}
			
			// status idle
			if ($dt_last_stop >= $dt_last_move)
			{
				$sensor = getSensorFromType($imei, 'acc');
				$acc = $sensor[0]['param'];
				
				if (isset($params[$acc]))
				{
					if (($params[$acc] == 1) && ($dt_last_idle <= 0))
					{
						$q = "UPDATE gs_objects SET `dt_last_idle`='".$loc['dt_server']."' WHERE imei='".$imei."'";
						$r = mysqli_query($ms, $q) or die(mysqli_error($ms));
						$dt_last_idle = strtotime($loc_prev['dt_server']);	
					}
					else if (($params[$acc] == 0) && ($dt_last_idle > 0))
					{
						$q = "UPDATE gs_objects SET `dt_last_idle`='0000-00-00 00:00:00' WHERE imei='".$imei."'";
						$r = mysqli_query($ms, $q) or die(mysqli_error($ms));
					}
				}
			}
			else
			{
				if ($dt_last_idle > 0)
				{
					$q = "UPDATE gs_objects SET `dt_last_idle`='0000-00-00 00:00:00' WHERE imei='".$imei."'";
					$r = mysqli_query($ms, $q) or die(mysqli_error($ms));
				}
			}
			
			// engine idle when ac on
			if ($dt_last_stop >= $dt_last_move )
			{
				$sensor = getSensorFromType($imei, 'acc');
				$acc = $sensor[0]['param'];
				$ac="di1";
				if($loc_prev["device"]=="Play FM100")
				$ac="di3";

				if (isset($params[$acc]))
				{
					if ((($params[$acc] == 1) && ($dt_last_idle > 0)) && ((getParamValue($params, $ac) == 1) && ($dt_last_aconidle <= 0)))
					{
						$q = "UPDATE gs_objects SET `dt_last_aconidle`='".$loc['dt_server']."' WHERE imei='".$imei."'";
						$r = mysqli_query($ms, $q) or die(mysqli_error($ms));	
					}
					else if ((($params[$acc] == 0) && ($dt_last_idle > 0)) || ((getParamValue($params, $ac) == 0) && ($dt_last_aconidle > 0)))
					{
						$q = "UPDATE gs_objects SET `dt_last_aconidle`='0000-00-00 00:00:00' WHERE imei='".$imei."'";
						$r = mysqli_query($ms, $q) or die(mysqli_error($ms));
					}
				}
			}
			else
			{
				if ($dt_last_aconidle > 0)
				{
					$q = "UPDATE gs_objects SET `dt_last_aconidle`='0000-00-00 00:00:00' WHERE imei='".$imei."'";
					$r = mysqli_query($ms, $q) or die(mysqli_error($ms));
				}
			}
		}
	}
	
	function insert_db_odo_engh($loc, $loc_prev)
	{
		global $ms;
		
		$imei = $loc['imei'];
		$params = $loc['params'];
		$params_prev = $loc_prev['params'];
		$arr2odo['type']=$loc_prev['odometer_type'];
		$arr2odo['odovalue']=$loc_prev["odometer"];
		// odo gps
		if ($loc_prev['odometer_type'] == 'gps')
		{
			if (strtotime($loc['dt_tracker']) >= strtotime($loc_prev['dt_tracker']))
			{
				if (($loc_prev['lat'] != 0) && ($loc_prev['lng'] != 0) && ($loc['speed'] > 3))
				{
					$odometer = getLengthBetweenCoordinates($loc_prev['lat'], $loc_prev['lng'], $loc['lat'], $loc['lng']);
					
					$q = 'UPDATE gs_objects SET `odometer` = odometer + '.$odometer.' WHERE imei="'.$imei.'"';
					$r = mysqli_query($ms, $q);
					$arr2odo['odovalue']=$loc_prev["odometer"]+$odometer;
				}	
			}
		}
		
		// odo sen
		if ($loc_prev['odometer_type'] == 'sen')
		{
			$sensor = getSensorFromType($imei, 'odo');
			
			if ($sensor != false)
			{
				$sensor_ = $sensor[0];
				
				$odo = getSensorValue($params, $sensor_);
				
				$result_type = $sensor_['result_type'];
				
				if ($result_type == 'abs')
				{
					$odo['value'] = $odo['value'] - $loc_prev["mileage"] ;
					if (strtotime($loc['dt_tracker']) >= strtotime($loc_prev['dt_tracker']))
					{	
						$q = 'UPDATE gs_objects SET `odometer` = '.$odo['value'].' WHERE imei="'.$imei.'"';
						$r = mysqli_query($ms, $q);
						$arr2odo['odovalue']=$odo['value'];
					}
					else 
					$arr2odo['odovalue']=$odo['value'];
				}
				
				if ($result_type == 'rel')
				{
					$odometer=round(($odo['value']/1000),4);
					$q = 'UPDATE gs_objects SET `odometer` = odometer + '.$odometer.' WHERE imei="'.$imei.'"';
					$r = mysqli_query($ms, $q);
					$arr2odo['odovalue']=$loc_prev["odometer"]+$odometer;
				}
			}
		}
		
		// engh acc
		if ($loc_prev['engine_hours_type'] == 'acc')
		{
			if (strtotime($loc['dt_tracker']) >= strtotime($loc_prev['dt_tracker']))
			{
				if ((strtotime($loc['dt_tracker']) > 0) && (strtotime($loc_prev['dt_tracker']) > 0))
				{
					$engine_hours = 0;
					
					// get ACC sensor
					$sensor = getSensorFromType($imei, 'acc');
					$acc = $sensor[0]['param'];
					
					// calculate engine hours from ACC
					$dt_tracker = $loc['dt_tracker'];
					$dt_tracker_prev = $loc_prev['dt_tracker'];
					
					if (isset($params_prev[$acc]) && isset($params[$acc]))
					{
						if (($params_prev[$acc] == '1') && ($params[$acc] == '1'))
						{
							$engine_hours = strtotime($dt_tracker)-strtotime($dt_tracker_prev);
							
							$q = 'UPDATE gs_objects SET `engine_hours` = engine_hours + '.$engine_hours.' WHERE imei="'.$imei.'"';
							$r = mysqli_query($ms, $q);
						}
					}	
				}
			}
		}
		
		// eng sen
		if ($loc_prev['engine_hours_type'] == 'sen')
		{
			$sensor = getSensorFromType($imei, 'engh');
			
			if ($sensor != false)
			{
				$sensor_ = $sensor[0];
				
				$engh = getSensorValue($params, $sensor_);
								
				$result_type = $sensor_['result_type'];
				
				if ($result_type == 'abs')
				{
					if (strtotime($loc['dt_tracker']) >= strtotime($loc_prev['dt_tracker']))
					{	
						$q = 'UPDATE gs_objects SET `engine_hours` = '.$engh['value'].' WHERE imei="'.$imei.'"';
						$r = mysqli_query($ms, $q);
					}
				}
				
				if ($result_type == 'rel')
				{
					$q = 'UPDATE gs_objects SET `engine_hours` = engine_hours + '.$engh['value'].' WHERE imei="'.$imei.'"';
					$r = mysqli_query($ms, $q);
				}
			}
		}
		
		return $arr2odo;
	}
	
	function insert_db_ri($loc, $loc_prev)
	{
		global $ms;
		
		// logbook
		if (strtotime($loc['dt_tracker']) >= strtotime($loc_prev['dt_tracker']))
		{
			$imei = $loc['imei'];
			$params = $loc['params'];
			$params_prev = $loc_prev['params'];
			
			$group_array = array('da', 'pa', 'ta');
			
			for ($i=0; $i<count($group_array); ++$i)
			{
				$group = $group_array[$i];
				
				$sensor = getSensorFromType($imei, $group);
				
				if ($sensor != false)
				{
					$sensor_ = $sensor[0];
					
					$sensor_data = getSensorValue($params, $sensor_);
					$assign_id = $sensor_data['value'];
					
					$sensor_data_prev = getSensorValue($params_prev, $sensor_);
					$assign_id_prev = $sensor_data_prev['value'];
					
					if ((string)$assign_id != (string)$assign_id_prev)
					{
						insert_db_ri_data($loc['dt_server'], $loc['dt_tracker'], $imei, $group, $assign_id, $loc['lat'], $loc['lng']);
					}
				}
				
			}
		}
	}
	
	function insert_db_ri_data($dt_server, $dt_tracker, $imei, $group, $assign_id, $lat, $lng)
	{
		global $ms;
		
		$address = geocoderGetAddress($lat, $lng);
		
		$q = 'INSERT INTO gs_rilogbook_data  (	`dt_server`,
							`dt_tracker`,
							`imei`,
							`group`,
							`assign_id`,
							`lat`,
							`lng`,
							`address`
							) VALUES (
							"'.$dt_server.'",
							"'.$dt_tracker.'",
							"'.$imei.'",
							"'.$group.'",
							"'.$assign_id.'",
							"'.$lat.'",
							"'.$lng.'",
							"'.$address.'")';
							
		$r = mysqli_query($ms, $q) or die(mysqli_error($ms));
	}
	
	function insert_db_dtc($loc)
	{
		global $ms;
		
		if (isset($loc['event']))
		{	
			if (substr($loc['event'], 0, 3) == 'dtc')
			{
				$dtcs = str_replace("dtc:", "", $loc['event']);
				
				$dtcs = explode(',', $dtcs);
				
				for ($i = 0; $i < count($dtcs); ++$i)
				{
					if ($dtcs[$i] != '')
					{
						insert_db_dtc_data($loc['dt_server'], $loc['dt_tracker'], $loc['imei'], strtoupper($dtcs[$i]), $loc['lat'], $loc['lng']);	
					}
				}
			}
		}
	}
	
	function insert_db_dtc_data($dt_server, $dt_tracker, $imei, $code, $lat, $lng)
	{
		global $ms;
		
		$address = geocoderGetAddress($lat, $lng);
		
		$q = 'INSERT INTO gs_dtc_data  (`dt_server`,
						`dt_tracker`,
						`imei`,
						`code`,
						`lat`,
						`lng`,
						`address`
						) VALUES (
						"'.$dt_server.'",
						"'.$dt_tracker.'",
						"'.$imei.'",
						"'.$code.'",
						"'.$lat.'",
						"'.$lng.'",
						"'.$address.'")';
							
		$r = mysqli_query($ms, $q) or die(mysqli_error($ms));
	}
	
	function get_gs_objects_data($imei)
	{
		global $ms;
		
		$q = "SELECT * FROM gs_objects WHERE `imei`='".$imei."'";
		$r = mysqli_query($ms, $q) or die(mysqli_error($ms));
		$row = mysqli_fetch_array($r);
		
		if ($row)
		{
			$row['params'] = json_decode($row['params'],true);
			
			return $row;
		}
		else
		{
			return false;
		}
	}
	
	function loc_filter($loc, $loc_prev)
	{
		global $ms, $gsValues;
		
		if ($gsValues['LOCATION_FILTER'] == false)
		{
			return false;
		}
		
		if (isset($loc['lat']) && isset($loc['lng']) && isset($loc['params']))
		{
			if (($loc['event'] == '') && ($loc_prev['params'] == $loc['params']))
			{
				$dt_difference = abs(strtotime($loc['dt_server']) - strtotime($loc_prev['dt_server']));
				
				//if($dt_difference < 120)
				if($dt_difference < 10)
				{
					// skip same location
					if (($loc_prev['lat'] == $loc['lat']) && ($loc_prev['lng'] == $loc['lng']) && ($loc_prev['speed'] == $loc['speed']))
					{
						return true;
					}
					
					// skip drift
					$distance = getLengthBetweenCoordinates($loc_prev['lat'], $loc_prev['lng'], $loc['lat'], $loc['lng']);
					if (($dt_difference < 30) && ($distance < 0.01) && ($loc['speed'] < 3) && ($loc_prev['speed'] == 0))
					{
						return true;
					}
				}
			}
		}
		
		return false;
	}

	function Replacelossdata($loc,$loc_prev,$arr2odo)
	{
	
			//$mileage=round($arr2odo["odovalue"],4);
			//if(floatval($mileage)==0)$mileage=$loc_prev["odometer"];	
			if($loc_prev["fueltype"]=="FUEL Sensor")
			{
				//Neglate high/low peak value :)
				$f1 = getParamValue($loc['params'],"fuel1");
				if(($f1==".00" ||  $f1=="00.0" || $f1=="0.0" || $f1=="00.00"  || $f1=="0.00" || $f1==""  || $f1>100 ))
				{
					$fuel1prev=(getParamValue($loc_prev['params'],"fuel1"));
					$loc['params']["fuel1"]=$fuel1prev;
				}
			}
				
			if($loc_prev["temp1"]=="YES")
			{
				$t1 =floatval(getParamValue($loc['params'],"temp1"));
				if($t1==85 || $t1==-55.5 || $t1==-55)
				{
					$temp1prev=(getParamValue($loc_prev['params'],"temp1"));
					$loc['params']["temp1"]=$temp1prev;
				}
			}
			if($loc_prev["temp2"]=="YES")
			{
				$t1 =floatval(getParamValue($loc['params'],"temp2"));
				if($t1==85 || $t1==-55.5 || $t1==-55)
				{
					$temp1prev=(getParamValue($loc_prev['params'],"temp2"));
					$loc['params']["temp2"]=$temp1prev;
				}
			}
			
			return $loc;
	}

	
function checkhz($value=''){
	if($value!='' || $value>0)
	{
		$sd=$value/1000;
		return $sd;
	}else{
		return '0';
	}
}

function checktemp($tmp){
	if($tmp>='20' ){
		$temp=$tmp-50;
		return $temp;
	}
	else{
		return '0';
	}
}

// function check_sen_calibration($hz,$imei,$paramid){
// 	global $ms;
// 	$value=0;
// 	$q="SELECT * FROM gs_object_cal_sensor where imei='".$imei."' and param_id='".$paramid."'";
// 	$r=mysqli_query($ms,$q);
// 	if(mysqli_num_rows($r)>0){
// 		while ($row=mysqli_fetch_assoc($r)) {			
// 			$row['calibration_data']=json_decode($row['calibration_data'],true);
// 			$value=fuel_sen_calibration($row['calibration_data'],$hz);
// 			return $value;
// 		}
// 	}
// 	return $value;
// }

// function fuel_sen_calibration($cl,$currentHZ){

// 	if($currentHZ>0){		

// 		$emptyval=array();
// 		$fullvalue=array();
// 		$return=0;
// 		$fuel=0;
// 		$equalvolum='';
// 		if(count($cl)>0){
// 			for ($i=0; $i <count($cl); $i++) {
// 				if($cl[$i]['option']=='empty'){
// 					$emptyval=$cl[$i];
// 					if($emptyval['hz']<$currentHZ){
// 						return 0;
// 					}
// 				}else if ($cl[$i]['option']=='full'){
// 					$fullvalue=$cl[$i];
// 					// if($fullvalue['hz']<$currentHZ){
// 					// 	return $fullvalue['volum'];
// 					// }
// 				}

// 			}
// 			if(count($emptyval)>0 && count($fullvalue)>0){
// 				$glevel=getcurrentlevel($emptyval,$fullvalue,$currentHZ);
// 				$lmm=$emptyval;
// 				$hmm=$fullvalue;
// 				for ($j=0; $j <count($cl); $j++) { 				
// 					if($cl[$j]['option']!='empty' AND $cl[$j]['option']!='full' ){

// 						if($glevel<=$cl[$j]['level'] AND ($hmm['level']>$cl[$j]['level'])){
// 							$hmm=$cl[$j];
// 						}
						
// 						if($glevel>=$cl[$j]['level'] and ($lmm['level']<$cl[$j]['level'])){
// 							$lmm=$cl[$j];
// 						}
// 					}
// 					if($cl[$j]['level']==round($glevel,2)){
// 						$equalvolum=$cl[$j]['volum'];
// 					}
// 					if($cl[$j]['hz']==round($currentHZ,2)){
// 						$equalvolum=$cl[$j]['volum'];
// 					}
// 				}
// 				$lper=getLevelPercentage($lmm['level'],$hmm['level'],$glevel);
// 				$hzlper=getLevelPercentage_hz($lmm['hz'],$hmm['hz'],$currentHZ);			
// 				if($equalvolum!=""){
// 					$fuel=$equalvolum;
// 					$lper=0;
// 				}
// 				else if($lper>0){
// 					$fuel=getFuel($lmm['volum'],$hmm['volum'],$lper);
// 				}
// 				$fuel=round($fuel,2);
// 				$return=$fuel;
// 			}

// 		}
// 		if(isset($check)){
// 			$myfile = fopen("vvv_hz.txt", "a");
// 			fwrite($myfile,$return);
// 			fwrite($myfile, "\n");
// 			fclose($myfile);
// 		}
// 		return $return;
// 	}else{
// 		return 0;
// 	}
// }

// function getcurrentlevel($emptyval,$fullvalue,$currentHZ)
// {
// 	$ls=$fullvalue['level'];
// 	$f0=$emptyval['hz'];
// 	$f1=$fullvalue['hz'];
// 	$fa=$currentHZ;
// 	$mm=$ls*$f1*($f0/$fa-1)/($f0-$f1);

// 	return $mm;
// }

// function getLevelPercentage($a,$b,$c){
// 	$formula=0;
// 	if($a<$b and $b>$c and $a<$c){
// 		$s1=$c-$a;
// 		$s2=$s1*100;
// 		$s3=$b-$a;
// 		$s4=$s2/$s3;
// 		$formula=$s4;
// 	}
		
// 	return $formula;
// }

// function getLevelPercentage_hz($b,$a,$c){
// 	$formula=0;
// 	if($a<$b and $b>$c and $a<$c){
// 		$s1=$c-$a;
// 		$s2=$s1*100;
// 		$s3=$b-$a;
// 		$s4=$s2/$s3;
// 		$formula=$s4;
// 	}
// 	return $formula;
// }

// function getFuel($b,$a,$c){
// 	$c=100-$c;
// 	$formula=0;
// 	if($a>$b){
// 		$formula=$c*($b-$a)/100+$a;
// 	}
// 	return $formula;
// }

// function update_dispenser_imei($imei,$params){


// 	global $ms;
// 	$q="SELECT * FROM gs_objects where dis_tracker_imei='".$imei."'";
// 	$r=mysqli_query($ms,$q);
// 	if(mysqli_num_rows($r)>0)
// 	{
// 		$row=mysqli_fetch_assoc($r);
// 		$par=json_decode($row['params'],true);
		
// 		$par['T_fuel1']=@$params['fuel1'];
// 		$par['T_fuel2']=@$params['fuel2'];
// 		$par['T_fuel3']=@$params['fuel3'];
// 		$par['T_fuel4']=@$params['fuel4'];
// 		$par['T_fuel5']=@$params['fuel5'];


// 		$par['fuelhz1']=@$params['fuelhz1'];
// 		$par['fuelhz2']=@$params['fuelhz2'];
// 		$par['fuelhz3']=@$params['fuelhz3'];
// 		$par['fuelhz4']=@$params['fuelhz4'];
// 		$par['fuelhz5']=@$params['fuelhz5'];
		
// 		$par['fuel1']=@$params['fuel1'];
// 		$par['fuel2']=@$params['fuel2'];
// 		$par['fuel3']=@$params['fuel3'];
// 		$par['fuel4']=@$params['fuel4'];
// 		$par['fuel5']=@$params['fuel5'];
		
// 		$par['ble_temp1']=@$params['ble_temp1'];
// 		$par['ble_temp2']=@$params['ble_temp2'];
// 		$par['ble_temp3']=@$params['ble_temp3'];
		
// 		$par['tmp1']=@$params['tmp1'];
// 		$par['tmp2']=@$params['tmp2'];
// 		$par['tmp3']=@$params['tmp3'];
// 		$par['tmp4']=@$params['tmp4'];
// 		$par['tmp5']=@$params['tmp5'];
// 		//$par['T_acc']=@$params['acc'];
// 		$qu="UPDATE gs_objects SET params='".json_encode($par)."' where imei='".$row['imei']."'";
// 		mysqli_query($ms,$qu);

/*
if($imei=="354018115588406")
{
	$myfile = fopen("vvv_teltonika_4006.txt", "a");
		fwrite($myfile,json_encode($params));
	 	fwrite($myfile, "\n");

	 	fwrite($myfile,$qu);
	 	fwrite($myfile, "\n");
	 	fclose($myfile);

}
*/


		
// 	}
// }


	
?>