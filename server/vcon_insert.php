<?
	//$_POST['net_protocol'] - tcp or udp
	//$_POST['protocol'] - device protocol, like coban, teltonika, xexun
	//$_POST['ip'] - IP address of GPS device
	//$_POST['port'] - PORT of GPS device
	//$_POST['imei'] - device 15 char ID
	//$_POST['dt_server'] - 0 UTC date and time in "YYYY-MM-DD HH-MM-SS" format
	//$_POST['dt_tracker'] - 0 UTC date and time in "YYYY-MM-DD HH-MM-SS" format
	//$_POST['lat'] - latitude with +/-
	//$_POST['lng'] - longitude with +/-
	//$_POST['altitude'] - in meters
	//$_POST['angle'] - in degree
	//$_POST['speed'] - in km/h
	//$_POST['loc_valid'] - 1 means valid location, 0 means not valid location
	//$_POST['params'] - stores array of params like acc, di, do, ai...
	//$_POST['event'] - possible events: sos, bracon, bracoff, mandown, shock, tow, haccel, hbrake, hcorn, pwrcut, gpscut, lowdc, lowbat, jamming
	

$myfile = fopen("0vvv_concox.txt", "a");
fwrite($myfile,gmdate("Y-m-d H:i:s").' - '.json_encode($_POST));
fwrite($myfile, "\n");
fclose($myfile);


	
	include ('s_init.php');
	include ('../func/fn_common.php');
	include ('../tools/gc_func.php');
	include ('NewModule.php');
	include ('s_events.php');
	
	/*if(!isset($_POST['method']))
	{
		$_POST['method'] = "amwellInsertNew";
	}
	*/
	
	/*
		$_POST['method'] = 'amwellInsertNew';
		$_POST['imei'] = '21596731';
		$_POST['lat'] = '13.045150';
		$_POST['lng'] = '80.180192';
		$_POST['dt_tracker'] = gmdate("Y-m-d H:i:s");
		$_POST['dt_server'] = gmdate("Y-m-d H:i:s");
		$_POST['altitude'] = 10;
		$_POST['angle'] = 30;
		$_POST['speed'] = 30;
		$_POST['type'] = 0;
		$_POST['alarm'] = false;
		$_POST['signal_gps'] = 5;
		$_POST['status'] = '';
	*/
	
	
	if(@$_POST['method'] == 'Alarm')
	{
		$loc['protocol'] = @$_POST['protocol'];
		$loc['net_protocol'] = "";
		$loc['ip'] = @$_SERVER['REMOTE_ADDR'];
		$loc['port'] = @$_POST['port'];
		
		$loc['imei'] = $_POST['imei'];
		$loc['lat'] = $_POST['lat'];
		$loc['lng'] = $_POST['lng'];
		$loc['dt_tracker'] = $_POST['dt'];
		$loc['dt_server'] = gmdate("Y-m-d H:i:s");
		$loc['altitude'] = @$_POST['altitude'];
		$loc['angle'] = @$_POST['angle'];
		$loc['speed'] = @$_POST['speed'];
		$loc['type'] = 0;
		$loc['alarm'] = 'true';
		
		$params=array();
		$params["di3"]=@$_POST['di3'];
		$params["di4"]=@$_POST['di4'];
		$params["gpsL"]=@$_POST['gps'];
		$loc['params'] = $params;
		
		if($_POST['gsm']!=null && $_POST['gsm']>0)
		{
			$loc['loc_valid'] = '1'; 
		}
		else
		{
			$loc['loc_valid'] = '0'; 
		}
		$loc['event'] = @$_POST['event'];
		
		$loc['status']="";
		
		if(isset($loc['event']) && $loc['event']!="")
		{
			$events=explode("-",$loc['event']);
			if(count($events)>0)
			{
				for($iv=0;$iv<count($events);$iv++)
				{
					$sngevent=$events[$iv];
					if($sngevent!="")
					{
						$loc['event']=$sngevent;
						insert_db_loc($loc);
					}
				}
			}
			else
			{
				insert_db_loc($loc);
			}
		}
		else
		{
			insert_db_loc($loc);
		}
	

		echo "DATAOK";

	}
	else if(@$_POST['method'] == 'Loc' || @$_POST['method'] == 'Locp')
	{
		$loc['protocol'] = @$_POST['protocol'];
		$loc['net_protocol'] = "";
		$loc['ip'] = @$_SERVER['REMOTE_ADDR'];
		$loc['port'] = @$_POST['port'];
		
		$loc['imei'] = $_POST['imei'];
		$loc['lat'] = $_POST['lat'];
		$loc['lng'] = $_POST['lng'];
		$loc['dt_tracker'] = $_POST['dt'];
		$loc['dt_server'] = gmdate("Y-m-d H:i:s");
		$loc['altitude'] = @$_POST['altitude'];
		$loc['angle'] = @$_POST['angle'];
		$loc['speed'] = @$_POST['speed'];
		$loc['type'] = 0;
		$loc['alarm'] = 'false';
		
		if($_POST['gsm']!=null && $_POST['gsm']>0)
		{
			$loc['loc_valid'] = '1'; 
		}
		else
		{
			$loc['loc_valid'] = '0'; 
		}
		$loc['event'] = @$_POST['event'];
		$loc['status']="";
		
		if(@$_POST['rfid']!="")
		{
			$_POST['dt_tracker'] = $_POST['dt'];
			$rfid=@$_POST['rfid'];
			$_POST['rfid']=hex2ascii($rfid);
			echo "DATAOK";
			new_rfidswipe();
			die;			
		}

		if(@$_POST['acc']=='On'){
			$_POST['acc']='1';
		}else if(@$_POST['acc']=='Off'){
			$_POST['acc']='0';
		}

		$params=array();
		// $params["odo"]=@$_POST['odo'];
		$params["fuel1"]=@$_POST['fuel1'];
		$params["acc"]=@$_POST['acc'];
		$params["di1"]=@$_POST['di1'];
		$params["di2"]=@$_POST['di2'];
		$params["di3"]=@$_POST['di3'];
		$params["di4"]=@$_POST['di4'];
		$params["di5"]=@$_POST['di5'];
		$params["di6"]=@$_POST['di6'];
		$params["gpsL"]=@$_POST['gps'];
		$params["pump"]=@$_POST['pump'];
		$params["temp1"]=@$_POST['temp1'];
		
		$loc['params'] = $params;
		
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
			if(@$loc['alarm']=="false")
			{
				insert_db_object_data($loc,$loc_prev,$arr2odo);
			}
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
			PostRedBus($loc,$loc_prev);
		}
		
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
			
			if($mileage==0)
			{
				$mileage=$loc_prev["odometer"];
			}
			
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
	
?>
