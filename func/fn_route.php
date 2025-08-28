<?
	// describe route array data
	// $route[0] - dt_tracker
	// $route[1] - lat
	// $route[2] - lng
	// $route[3] - altitude
	// $route[4] - angle
	// $route[5] - speed
	// $route[6] - params

	function getRouteRaw($imei, $accuracy, $dtf, $dtt,$timezone=true,$altitude_filter=false)
	{
		global $ms;
		
		$route = array();
		$extraqry="";
		if($accuracy['fueltype']=="FMS")
		{
			$extraqry=",fuelused";	
		}
		
		$q = "SELECT DISTINCT	dt_tracker,
					lat,
					lng,
					altitude,
					angle,
					speed,
					params,mileage".$extraqry."
					FROM `gs_object_data_".$imei."` WHERE dt_tracker BETWEEN '".$dtf."' AND '".$dtt."' ORDER BY dt_tracker ASC";
		$r = mysqli_query($ms, $q);
		if (!$r)return  $route;
		
		while($route_data=mysqli_fetch_array($r))
		{
			if($timezone==true){
				$dt_tracker = convUserTimezone($route_data['dt_tracker']);
			}else{
				$dt_tracker = $route_data['dt_tracker'];
			}
			$lat = $route_data['lat'];
			$lng = $route_data['lng'];
			$altitude = $route_data['altitude'];
			$angle = $route_data['angle'];
			$speed = $route_data['speed'];
			
			$fuelused =0;
			if($accuracy['fueltype']=="FMS")
			{
				$fuelused = $route_data['fuelused'];
			}
			//$params = json_decode($route_data['params'],true);
			$params = paramsToArray($route_data['params']); // CODE UPDATED BY VETRIVEL.N
			
			$speed = convSpeedUnits($speed, 'km', $_SESSION["unit_distance"]);
			$altitude = convAltitudeUnits($altitude, 'km', $_SESSION["unit_distance"]);
			
			if (isset($params['gpslev']) && ($accuracy['use_gpslev'] == true))
			{
				$gpslev = $params['gpslev'];
			}
			else
			{
				$gpslev = 0;
				$accuracy['min_gpslev'] = 0;
			}
			
			if (isset($params['hdop']) && ($accuracy['use_hdop'] == true))
			{
				$hdop = $params['hdop'];
			}
			else
			{
				$hdop = 0;
				$accuracy['max_hdop'] = 0;
			}
			
			if (($gpslev >= $accuracy['min_gpslev']) && ($hdop <= $accuracy['max_hdop']))
			{
				
				if (($lat != 0) && ($lng != 0))
				{
					// if ($altitude_filter !== false && $altitude > $altitude_filter) {
					// 	continue;
					// }
			
					$route[] = array(	$dt_tracker,
								$lat,
								$lng,
								$altitude,
								$angle,
								$speed,
								$params,
								round($route_data['mileage'],2),
								$fuelused);
				}
			}
		}
		
		return $route;
	}
	
	function getRouteEvents($imei, $dtf, $dtt)
	{
		global $ms;
		
		// check privileges
		if ($_SESSION["privileges"] == 'subuser')
		{
			$user_id = $_SESSION["manager_id"];
		}
		else
		{
			$user_id = $_SESSION["user_id"];
		}
		
		$events = array();
			
		$q = "SELECT * FROM `gs_user_events_data` WHERE `user_id`='".$user_id."' AND `imei`='".$imei."' AND dt_tracker BETWEEN '".$dtf."' AND '".$dtt."' ORDER BY dt_tracker ASC";
		
		$r = mysqli_query($ms, $q);
		
		while($event_data=mysqli_fetch_array($r))
		{
			$event_data['speed'] = convSpeedUnits($event_data['speed'], 'km', $_SESSION["unit_distance"]);
			$event_data['altitude'] = convAltitudeUnits($event_data['altitude'], 'km', $_SESSION["unit_distance"]);
			
			$event_data['params'] = json_decode($event_data['params'],true);
			
			$events[] = array(	$event_data['event_desc'],
						convUserTimezone($event_data['dt_tracker']),
						$event_data['lat'],
						$event_data['lng'],
						$event_data['altitude'],
						$event_data['angle'],
						$event_data['speed'],
						$event_data['params'],
						$event_data['type'],
						$event_data['startend'],
						$event_data['zoneid'],
						$event_data['ctype']
						);
		}
		
		return $events;
	}
	
	function getRoute($imei, $dtf, $dtt, $min_stop_duration, $filter,$DailyKM=false,$timezone=true,$altitude_filter=false) 
	{

		$accuracy = getObjectAccuracy($imei);

		$result = array();
		$result['route'] = array();
		$result['stops'] = array();
		$result['drives'] = array();
		$result['events'] = array();
		
		if (checkObjectActive($imei) != true)
		{
			return $result;
		}
		
		$route = getRouteRaw($imei, $accuracy, $dtf, $dtt,$timezone,$altitude_filter);
		
		if (count($route) > 0)
		{
			// get object fuel rates
			$fcr = getObjectFCR($imei);
			
			// get ACC sensor
			$sensor = getSensorFromType($imei, 'acc');
			$acc = $sensor[0]['param'];
			
			// filter jumping cordinates
			if ($filter == true)
			{
				$route = removeRouteJunkPoints($route, $accuracy, array());
			}
			$result['route'] = $route;
			
			// create stops
			if ($accuracy['stops'] == 'gpsacc')
			{
				$result['stops'] = getRouteStopsGPSACC($route, $accuracy, $min_stop_duration, $acc);	
			}
			else if ($accuracy['stops'] == 'acc')
			{
				$result['stops'] = getRouteStopsACC($route, $accuracy, $min_stop_duration, $acc);
			}
			else
			{
				$result['stops'] = getRouteStopsGPS($route, $accuracy, $min_stop_duration, $acc);
			}
			
			// create drives
			$fuel_sensors = getSensorFromType($imei, 'fuel');
			$fuelcons_sensors = getSensorFromType($imei, 'fuelcons');
			$result['drives'] = getRouteDrives($route, $accuracy, $result['stops'], $fcr, $fuel_sensors, $fuelcons_sensors, $acc,$DailyKM);
			
			// load events
			$result['events'] = getRouteEvents($imei, $dtf, $dtt);
			
			// count route_length
			$result['route_length'] = 0;
			$result['daily_kmdata'] = array();
			for ($i=0; $i<count($result['drives']); ++$i)
			{
				$result['route_length'] += $result['drives'][$i][7];

				for ($idkm=0; $idkm<count($result['drives'][$i][13]); $idkm++)
				{
					$last_dkm="";
					if(count($result['daily_kmdata'])>0)
					$last_dkm=$result['daily_kmdata'][count($result['daily_kmdata'])-1]["date"];
					
					if($result['drives'][$i][13][$idkm]["dailykm"] > 0)
					{
						if($last_dkm=="" || $result['drives'][$i][13][$idkm]["date"]!=$last_dkm)
						{
							$result['daily_kmdata'][]=$result['drives'][$i][13][$idkm];
						}
						else 
						{
							$result['daily_kmdata'][count($result['daily_kmdata'])-1]["dailykm"]=$result['drives'][$i][13][$idkm]["dailykm"]+$result['daily_kmdata'][count($result['daily_kmdata'])-1]["dailykm"];
						}
					}
				}
			}

			// count top speed				
			$result['top_speed'] = 0;
			for ($i=0; $i<count($result['drives']); ++$i)
			{
				if ($result['top_speed'] < $result['drives'][$i][8])
				{
					$result['top_speed'] = $result['drives'][$i][8];
				}
			}
			
			// count avg speed
			$result['avg_speed'] = 0;
			for ($i=0; $i<count($result['drives']); ++$i)
			{
				$result['avg_speed'] += $result['drives'][$i][9];
			}
			
			if (count($result['drives']) > 0)
			{
				$result['avg_speed'] = floor($result['avg_speed'] / count($result['drives']));
			}
				
			// count fuel consumption
			$result['fuel_consumption'] = 0;
			for ($i=0; $i<count($result['drives']); ++$i)
			{
				$result['fuel_consumption'] += $result['drives'][$i][10];
			}
			
			// count fuel cost
			$result['fuel_cost'] = 0;
			for ($i=0; $i<count($result['drives']); ++$i)
			{
				$result['fuel_cost'] += $result['drives'][$i][11];
			}
			
			// count stops duration
			$result['stops_duration_time'] = 0;
			for ($i=0; $i<count($result['stops']); ++$i)
			{
				$diff = strtotime($result['stops'][$i][7])-strtotime($result['stops'][$i][6]);
				$result['stops_duration_time'] += $diff;
			}
			$result['stops_duration'] = getTimeDetails($result['stops_duration_time'], true);
			
			// count drives duration and engine work
			$result['drives_duration_time'] = 0;
			for ($i=0; $i<count($result['drives']); ++$i)
			{
				$diff = strtotime($result['drives'][$i][5])-strtotime($result['drives'][$i][4]);
				$result['drives_duration_time'] += $diff;
			}
			$result['drives_duration'] = getTimeDetails($result['drives_duration_time'], true);
			
			// prepare full engine work and idle info
			$result['engine_work_time'] = 0;
			$result['engine_idle_time'] = 0;
			
			for ($i=0; $i<count($result['drives']); ++$i)
			{
				$result['engine_work_time'] += $result['drives'][$i][12];
				$result['drives'][$i][12] = getTimeDetails($result['drives'][$i][12], true);
			}
			
			for ($i=0; $i<count($result['stops']); ++$i)
			{
				$result['engine_idle_time'] += $result['stops'][$i][9];
				$result['stops'][$i][9] = getTimeDetails($result['stops'][$i][9], true);	
			}
			
			// set total engine work and idle
			$result['engine_work_time'] += $result['engine_idle_time'];
			$result['engine_work'] = getTimeDetails($result['engine_work_time'], true);
			$result['engine_idle'] = getTimeDetails($result['engine_idle_time'], true);
		}
		
		return $result;
	}
	
	function getRouteOverspeeds($route, $speed_limit,$max_speed_limit=0)
	{
		$overspeeds = array();
		$overspeed = 0;
		$top_speed = 0;
		$avg_speed = 0;
		$avg_speed_c = 0;
		
		for ($i=0; $i<count($route); ++$i)
		{
			$speed = $route[$i][5];
			
			if ($speed > $speed_limit && ($max_speed_limit==0 || $speed < $max_speed_limit))
			{	
				if($overspeed == 0)
				{
					$overspeed_start = $route[$i][0];
					$overspeed = 1;
				}
				
				if ($speed >= $top_speed)
				{
					$top_speed = $speed;
					$overspeed_lat = $route[$i][1];
					$overspeed_lng = $route[$i][2];
				}
				
				$avg_speed += $speed;
				$avg_speed_c++;
			}
			else
			{
				if ($overspeed == 1 && ($max_speed_limit==0 || $speed < $max_speed_limit))
				{
					$overspeed_end = $route[$i][0];
					$overspeed_duration = getTimeDifferenceDetails($overspeed_start, $overspeed_end);
					
					$overspeeds[] = array(	$overspeed_start,
								$overspeed_end,
								$overspeed_duration,
								$top_speed,
								floor($avg_speed / $avg_speed_c),
								$overspeed_lat,
								$overspeed_lng
								);
					
					$top_speed = 0;
					$avg_speed = 0;
					$avg_speed_c = 0;
					$overspeed = 0;
				}
			}
		}
		
		return $overspeeds;
	}
	
	function getRouteUnderspeeds($route, $speed_limit)
	{
		$underpeeds = array();
		$underpeed = 0;
		$top_speed = 0;
		$avg_speed = 0;
		$avg_speed_c = 0;
		
		for ($i=0; $i<count($route); ++$i)
		{
			$speed = $route[$i][5];
			
			if ($speed < $speed_limit)
			{	
				if($underpeed == 0)
				{
					$underpeed_start = $route[$i][0];
					$underpeed = 1;
				}
				
				if ($speed >= $top_speed)
				{
					$top_speed = $speed;
					$underpeed_lat = $route[$i][1];
					$underpeed_lng = $route[$i][2];
				}
				
				$avg_speed += $speed;
				$avg_speed_c++;
			}
			else
			{
				if ($underpeed == 1)
				{
					$underpeed_end = $route[$i][0];
					$underpeed_duration = getTimeDifferenceDetails($underpeed_start, $underpeed_end);
					
					$underpeeds[] = array(	$underpeed_start,
								$underpeed_end,
								$underpeed_duration,
								$top_speed,
								floor($avg_speed / $avg_speed_c),
								$underpeed_lat,
								$underpeed_lng
								);
									
					$top_speed = 0;
					$avg_speed = 0;
					$avg_speed_c = 0;
					$underpeed = 0;
				}
			}
		}
		
		return $underpeeds;
	}
	
	function getRouteStopsGPSACC($route, $accuracy, $min_stop_duration, $acc)
	{
		$stops = array();
		$stoped = 0;
		
		$min_moving_speed = $accuracy['min_moving_speed'];
		
		for ($i=0; $i<count($route); ++$i)
		{
			$params = $route[$i][6];
			
			if (!isset($params[$acc]))
			{
				$params[$acc] = '';
			}
			
			$stop_speed = $route[$i][5];
			
			if ((($stop_speed <= $min_moving_speed) && ($i < count($route)-1)) || (($params[$acc] == '0') && ($i < count($route)-1)))
			{	
				if($stoped == 0)
				{
					$start_id = $i;
					
					$stop_start = $route[$i][0];
					$stop_lat = $route[$i][1];
					$stop_lng = $route[$i][2];
					$stop_altitude = $route[$i][3];
					$stop_angle = $route[$i][4];
					$stop_params = $route[$i][6];
					
					$stoped = 1;
				}
			}
			else
			{
				if ($stoped == 1)
				{
					$end_id = $i;
					
					$stop_end = $route[$i][0];
					$stop_duration = getTimeDifferenceDetails($stop_start, $stop_end);
					$stop_engine_hours = getRouteEngineHours($route, $start_id, $end_id, $acc);
					
					$time_diff = strtotime($stop_end)-strtotime($stop_start);
					
					if ($time_diff > ($min_stop_duration * 60))
					{
						$stops[] = array(	$start_id,
									$end_id,
									$stop_lat,
									$stop_lng,
									$stop_altitude,
									$stop_angle,
									$stop_start,
									$stop_end,
									$stop_duration,
									$stop_engine_hours,
									$stop_params,
									);
					}
					$stoped = 0;
				}
			}
		}
		return $stops;
	}
	
	function getRouteStopsACC($route, $accuracy, $min_stop_duration, $acc)
	{
		$stops = array();
		$stoped = 0;
		
		for ($i=0; $i<count($route); ++$i)
		{
			$params = $route[$i][6];
			
			if (!isset($params[$acc]))
			{
				$params[$acc] = '';
			}
			
			if (($params[$acc] == '0') && ($i < count($route)-1))
			{
				
				
				if($stoped == 0)
				{
					$start_id = $i;
					
					$stop_start = $route[$i][0];
					$stop_lat = $route[$i][1];
					$stop_lng = $route[$i][2];
					$stop_altitude = $route[$i][3];
					$stop_angle = $route[$i][4];
					$stop_params = $route[$i][6];
					
					$stoped = 1;
				}
			}
			else
			{
				if ($stoped == 1)
				{
					$end_id = $i;
					
					$stop_end = $route[$i][0];
					$stop_duration = getTimeDifferenceDetails($stop_start, $stop_end);
					//$stop_engine_hours = getRouteEngineHours($route, $start_id, $end_id, $acc);
					$stop_engine_hours = '0'; // because Stop is detected by ACC
					
					$time_diff = strtotime($stop_end)-strtotime($stop_start);
					
					if ($time_diff > ($min_stop_duration * 60))
					{
						$stops[] = array(	$start_id,
									$end_id,
									$stop_lat,
									$stop_lng,
									$stop_altitude,
									$stop_angle,
									$stop_start,
									$stop_end,
									$stop_duration,
									$stop_engine_hours,
									$stop_params
									);
					}
					$stoped = 0;
				}
			}
		}
		return $stops;
	}

	function getRouteStopsGPS($route, $accuracy, $min_stop_duration, $acc)
	{
		$stops = array();
		$stoped = 0;
		
		$min_moving_speed = $accuracy['min_moving_speed'];
		
		for ($i=0; $i<count($route); ++$i)
		{
			$stop_speed = $route[$i][5];
			
			if (($stop_speed <= $min_moving_speed) && ($i < count($route)-1))
			{	
				if($stoped == 0)
				{
					$start_id = $i;
					
					$stop_start = $route[$i][0];
					$stop_lat = $route[$i][1];
					$stop_lng = $route[$i][2];
					$stop_altitude = $route[$i][3];
					$stop_angle = $route[$i][4];
					$params = $route[$i][6];
					
					$stoped = 1;
				}
			}
			else
			{
				if ($stoped == 1)
				{
					$end_id = $i;
					
					$stop_end = $route[$i][0];
					$stop_duration = getTimeDifferenceDetails($stop_start, $stop_end);
					$stop_engine_hours = getRouteEngineHours($route, $start_id, $end_id, $acc);
					
					$time_diff = strtotime($stop_end)-strtotime($stop_start);
					
					if ($time_diff > ($min_stop_duration * 60))
					{
						$stops[] = array(	$start_id,
									$end_id,
									$stop_lat,
									$stop_lng,
									$stop_altitude,
									$stop_angle,
									$stop_start,
									$stop_end,
									$stop_duration,
									$stop_engine_hours,
									$params
									);
					}
					$stoped = 0;
				}
			}
		}
		return $stops;
	}
	
	function getRouteDrives($route, $accuracy, $stops, $fcr, $fuel_sensors, $fuelcons_sensors, $acc,$DailyKM=false)
	{
		$drives = array();
		
		if (count($stops) == 0)
		{
			// moving between start and end marker if no stops
			$id_start_s = 0;
			$id_start = 0;
			$id_end = count($route)-1;
			
			$dt_start_s = $route[$id_start_s][0];
			$dt_start = $route[$id_start][0];
			$dt_end = $route[$id_end][0];
			
			if ($dt_start != $dt_end)
			{
				if($DailyKM)
				{
					$route_length_ary = getRouteLength($route, $id_start_s, $id_end,$DailyKM);
					$route_length=$route_length_ary["length"];
					$daily_kmdata=$route_length_ary["dailykm"];
				}
				else 
				{
					$route_length = getRouteLength($route, $id_start_s, $id_end);
					$daily_kmdata=array();
				}
				$moving_duration = getTimeDifferenceDetails($dt_start, $dt_end);
				//$route_length = getRouteLength($route, $id_start_s, $id_end);
				$top_speed = getRouteTopSpeed($route, $id_start_s, $id_end);
				$avg_speed = getRouteAvgSpeed($route, $id_start_s, $id_end);
				$fuel_consumption = getRouteFuelConsumption($route, $id_start, $id_end, $accuracy, $fcr, $fuel_sensors, $fuelcons_sensors);
				$fuel_cost = getRouteFuelCost($fuel_consumption, $fcr);
				$engine_work = getRouteEngineHours($route, $id_start, $id_end, $acc);
				
				$drives_start_end = array(	$id_start_s,
								$id_start,
								$id_end,
								$dt_start_s,
								$dt_start,
								$dt_end,
								$moving_duration,
								$route_length,
								$top_speed,
								$avg_speed,
								$fuel_consumption,
								$fuel_cost,
								$engine_work,
								$daily_kmdata);
			}
		}
		else
		{
			// moving between start and first stop
			$id_start_s = 0;
			$id_start = 0;
			$id_end = $stops[0][0];
			
			if ($id_end != 0)
			{
				$dt_start_s = $route[$id_start_s][0];
				$dt_start = $route[$id_start][0];
				$dt_end = $route[$id_end][0];
				
				if ($dt_start != $dt_end)
				{
					if($DailyKM)
					{
						$route_length_ary = getRouteLength($route, $id_start_s, $id_end,$DailyKM);
						$route_length=$route_length_ary["length"];
						$daily_kmdata=$route_length_ary["dailykm"];
					}
					else 
					{
						$route_length = getRouteLength($route, $id_start_s, $id_end);
						$daily_kmdata=array();
					}
					$moving_duration = getTimeDifferenceDetails($dt_start, $dt_end);
					//$route_length = getRouteLength($route, $id_start_s, $id_end);
					$top_speed = getRouteTopSpeed($route, $id_start_s, $id_end);
					$avg_speed = getRouteAvgSpeed($route, $id_start_s, $id_end);
					$fuel_consumption = getRouteFuelConsumption($route, $id_start, $id_end, $accuracy, $fcr, $fuel_sensors, $fuelcons_sensors);
					$fuel_cost = getRouteFuelCost($fuel_consumption, $fcr);
					$engine_work = getRouteEngineHours($route, $id_start, $id_end, $acc);
					
					$drives_start = array(	$id_start_s,
								$id_start,
								$id_end,
								$dt_start_s,
								$dt_start,
								$dt_end,
								$moving_duration,
								$route_length,
								$top_speed,
								$avg_speed,
								$fuel_consumption,
								$fuel_cost,
								$engine_work,
								$daily_kmdata);
				}
			}
			
			// moving between end and last stop								
			$id_start_s = $stops[count($stops)-1][0];
			$id_start = $stops[count($stops)-1][1];
			$id_end = count($route)-1;
			
			if ($id_start != $id_end)
			{
				$dt_start_s = $route[$id_start_s][0];
				$dt_start = $route[$id_start][0];
				$dt_end = $route[$id_end][0];
				
				if ($dt_start != $dt_end)
				{
					if($DailyKM)
					{
						$route_length_ary = getRouteLength($route,$id_start_s, $id_end,$DailyKM);
						$route_length=$route_length_ary["length"];
						$daily_kmdata=$route_length_ary["dailykm"];
					}
					else 
					{
						$route_length = getRouteLength($route, $id_start_s, $id_end);
						$daily_kmdata=array();
					}
					$moving_duration = getTimeDifferenceDetails($dt_start, $dt_end);
					//$route_length = getRouteLength($route, $id_start_s, $id_end);
					$top_speed = getRouteTopSpeed($route, $id_start_s, $id_end);
					$avg_speed = getRouteAvgSpeed($route, $id_start_s, $id_end);
					$fuel_consumption = getRouteFuelConsumption($route, $id_start, $id_end, $accuracy, $fcr, $fuel_sensors, $fuelcons_sensors);
					$fuel_cost = getRouteFuelCost($fuel_consumption, $fcr);
					$engine_work = getRouteEngineHours($route, $id_start, $id_end, $acc);
					
					$drives_end = array(	$id_start_s,
								$id_start,
								$id_end,
								$dt_start_s,
								$dt_start,
								$dt_end,
								$moving_duration,
								$route_length,
								$top_speed,
								$avg_speed,
								$fuel_consumption,
								$fuel_cost,
								$engine_work,
								$daily_kmdata);
				}
			}	
		}
		
		// moving between stops
		for ($i=0; $i<count($stops)-1; ++$i)
		{
			$id_start_s = $stops[$i][0];
			$id_start = $stops[$i][1];
			$id_end = $stops[$i+1][0];
			
			$dt_start_s = $route[$id_start_s][0];
			$dt_start = $route[$id_start][0];
			$dt_end = $route[$id_end][0];
			
			if ($dt_start != $dt_end)
			{
				if($DailyKM)
				{
					$route_length_ary = getRouteLength($route, $id_start_s, $id_end,$DailyKM);
					$route_length=$route_length_ary["length"];
					$daily_kmdata=$route_length_ary["dailykm"];
				}
				else 
				{
					$route_length = getRouteLength($route, $id_start_s, $id_end);
					$daily_kmdata=array();
				}
				$moving_duration = getTimeDifferenceDetails($dt_start, $dt_end);
				//$route_length = getRouteLength($route, $id_start_s, $id_end);
				$top_speed = getRouteTopSpeed($route, $id_start_s, $id_end);
				$avg_speed = getRouteAvgSpeed($route, $id_start_s, $id_end);
				$fuel_consumption = getRouteFuelConsumption($route, $id_start, $id_end, $accuracy, $fcr, $fuel_sensors, $fuelcons_sensors);
				$fuel_cost = getRouteFuelCost($fuel_consumption, $fcr);
				$engine_work = getRouteEngineHours($route, $id_start, $id_end, $acc);
				
				$drives_stops[] = array(	$id_start_s,
								$id_start,
								$id_end,
								$dt_start_s,
								$dt_start,
								$dt_end,
								$moving_duration,
								$route_length,
								$top_speed,
								$avg_speed,
								$fuel_consumption,
								$fuel_cost,
								$engine_work,
								$daily_kmdata);
			}
		}
		
		if(isset($drives_start_end))
		{
			$drives[] = $drives_start_end;
		}
		else
		{
			if(isset($drives_start))
			{
				$drives[] = $drives_start;
			}
			
			if(isset($drives_stops))
			{
				$drives = array_merge($drives, $drives_stops);
			}
			
			if(isset($drives_end))
			{
				$drives[] = $drives_end;
			}
		}
		
		return $drives;
	}
	
	function getRouteFuelCost($fuel_consumption, $fcr)
	{
		$fuel_cost = 0;
		
		if ($fcr == '')
		{
			return $fuel_cost;
		}
		
		$fuel_cost = $fuel_consumption * $fcr['cost'];
		
		return sprintf("%01.2f", $fuel_cost);
	}
	
	function getRouteFuelConsumption($route, $start_id, $end_id, $accuracy, $fcr, $fuel_sensors, $fuelcons_sensors)
	{
		$fuel_consumtion = 0;
		
		if ($fcr == '')
		{
			return $fuel_consumtion;
		}
		
		$source = $fcr['source'];
		$measurement = $fcr['measurement'];
		$cost = $fcr['cost'];
		$summer = $fcr['summer'];
		$winter = $fcr['winter'];
		$winter_start = $fcr['winter_start'];
		$winter_end= $fcr['winter_end'];
		
		$diff_ff = $accuracy['min_ff'];
		
		if ($source == 'rates') 
		{
			if (($summer > 0) && ($winter > 0))
			{
				for ($i=$start_id; $i<$end_id-1; ++$i)
				{
					$lat1 = $route[$i][1];
					$lng1 = $route[$i][2];
					$lat2 = $route[$i+1][1];
					$lng2 = $route[$i+1][2];
					$length = getLengthBetweenCoordinates($lat1, $lng1, $lat2, $lng2);
					
					if ($measurement == 'mpg')
					{
						$length = convDistanceUnits($length, 'km', 'mi');
					}
					
					$f_date = strtotime($route[$i][0]);
					$f_date1 = strtotime(gmdate("Y").'-'.$winter_start);
					$f_date2 = strtotime(gmdate("Y").'-'.$winter_end);
					
					if ($f_date1 >= $f_date2)
					{
						$f_date2 = strtotime((gmdate("Y") + 1).'-'.$winter_end);
					}
					
					if (($f_date >= $f_date1) && ($f_date <= $f_date2 ))
					{
						$fuel_consumtion += $length / $winter;
					}
					else
					{
						$fuel_consumtion += $length / $summer;
					}	
				}			
			}	
		}
		else if (($source == 'fuel') && ($fuel_sensors != false))
		{
			$params1 = $route[$start_id][6];
			$params2 = $route[$end_id][6];
			
			// loop per fuel sensors
			for ($j=0; $j<count($fuel_sensors); ++$j)
			{
				$before = getSensorValue($params1, $fuel_sensors[$j]);
				$after = getSensorValue($params2, $fuel_sensors[$j]);
				
				$diff = $after['value'] - $before['value'];
				
				if ($diff < 0)
				{
					$fuel_consumtion += $diff;	
				}
			}
				
			$fuel_consumtion = abs($fuel_consumtion);
		}
		else if (($source == 'fuelcons') && ($fuelcons_sensors != false))
		{
			for ($i=$start_id; $i<$end_id; ++$i)
			{
				$params = $route[$i][6];
				
				$cons = getSensorValue($params, $fuelcons_sensors[0]);
				
				$fuel_consumtion += abs($cons['value']);
			}
		}
		
		return sprintf("%01.2f", $fuel_consumtion);
	}
	
	function getRouteFuelFillings($route, $accuracy, $fuel_sensors)
	{
		$result = array();
		$result['fillings'] = array();
		
		if ($fuel_sensors == false)
		{
			return $result;
		}
		
		$diff_ff = $accuracy['min_ff'];
		
		$total_filled = 0;
		
		for ($i=0; $i<count($route)-1; ++$i)
		{
			$params1 = $route[$i][6];
			$params2 = $route[$i+1][6];
			
			$speed1 = $route[$i][5];
			$speed2 = $route[$i+1][5];
			
			// loop per fuel sensors
			for ($j=0; $j<count($fuel_sensors); ++$j)
			{				
				$before = getSensorValue($params1, $fuel_sensors[$j]); // fuel level
				$after = getSensorValue($params2, $fuel_sensors[$j]); // fuel level in next point
				
				$diff = $after['value'] - $before['value']; // fuel filling
				
				if (($diff >= $diff_ff) && (($speed1 < 10) || ($speed2 < 10)))
				{
					//echo $before['value'].' '.$after['value'].' '.$diff.'</br>';
					
					$dt_tracker = $route[$i+1][0];
					
					$lat = $route[$i+1][1];
					$lng = $route[$i+1][2];
					
					$filled = $after['value'] - $before['value'];
					
					$total_filled += $filled;
					
					$sensor = $fuel_sensors[$j]['name'];
					
					$params = $route[$i+1][6];
					
					$result['fillings'][] = array(	$dt_tracker,
									$lat,
									$lng,
									$before['value_full'],
									$after['value_full'],
									$filled.' '.$fuel_sensors[$j]['units'],
									$sensor,
									$params);
				}
			}
		}
		
		$result['total_filled'] = $total_filled.' '.$fuel_sensors[0]['units'];
		
		return $result;
	}
	
	function getRouteFuelThefts($route, $accuracy, $fuel_sensors)
	{
		$result = array();
		$result['thefts'] = array();
		
		if ($fuel_sensors == false)
		{
			return $result;
		}
		
		$diff_ft = $accuracy['min_ft'];
		
		$total_stolen = 0;
		
		for ($i=0; $i<count($route)-1; ++$i)
		{
			$params1 = $route[$i][6];
			$params2 = $route[$i+1][6];
			
			$speed1 = $route[$i][5];
			$speed2 = $route[$i+1][5];
			
			// loop per fuel sensors
			for ($j=0; $j<count($fuel_sensors); ++$j)
			{
				$before = getSensorValue($params1, $fuel_sensors[$j]); // fuel level
				$after = getSensorValue($params2, $fuel_sensors[$j]); // fuel level in next point
				
				$diff = $before['value'] - $after['value']; // fuel filling
				
				if (($diff >= $diff_ft) && (($speed1 < 10) || ($speed2 < 10)))
				{
					$dt_tracker = $route[$i+1][0];
					
					$lat = $route[$i+1][1];
					$lng = $route[$i+1][2];
					
					$stolen = $before['value'] - $after['value'];
					
					$total_stolen += $stolen;
					
					$sensor = $fuel_sensors[$j]['name'];
					
					$params = $route[$i+1][6];
					
					$result['thefts'][] = array(	$dt_tracker,
									$lat,
									$lng,
									$before['value_full'],
									$after['value_full'],
									$stolen.' '.$fuel_sensors[$j]['units'],
									$sensor,
									$params);
				}
			}
		}
		
		$result['total_stolen'] = $total_stolen.' '.$fuel_sensors[0]['units'];
		
		return $result;
	}
	
	function getRouteLogicSensorInfo($route, $accuracy, $sensors)
	{
		$result = array();
		
		if ($sensors == false)
		{
			return $result;
		}
		
		for ($i=0; $i<count($sensors); ++$i)
		{
			$status = false;
			$activation_time = '';
			$deactivation_time = '';
			$activation_lat = '';
			$activation_lng = '';
			$deactivation_lat = '';
			$deactivation_lng = '';
			
			$sensor = $sensors[$i];
			$sensor_name = $sensor['name'];
			$sensor_param = $sensor['param'];			
			
			for ($j=0; $j<count($route); ++$j)
			{				
				$dt_tracker = $route[$j][0];
				$lat = $route[$j][1];
				$lng = $route[$j][2];
				$params = $route[$j][6];
				
				$param_value = getParamValue($params, $sensor_param);
				
				if ($status == false)
				{
					if ($param_value == 1)
					{
						$activation_time = $dt_tracker;
						$activation_lat = $lat;
						$activation_lng = $lng;
						$status = true;
					}
				}
				else
				{
					if ($param_value == 0)
					{
						$deactivation_time = $dt_tracker;
						$deactivation_lat = $lat;
						$deactivation_lng = $lng;
						
						$duration = getTimeDifferenceDetails($activation_time, $deactivation_time);
						
						$result[] = array($sensor_name,
							       $activation_time,
							       $deactivation_time,
							       $duration,
							       $activation_lat,
							       $activation_lng,
							       $deactivation_lat,
							       $deactivation_lng);
						
						$status = false;
						$activation_time = '';
						$deactivation_time = '';
						$activation_lat = '';
						$activation_lng = '';
						$deactivation_lat = '';
						$deactivation_lng = '';
					}
				}
			}
		}
		
		return $result;
	}
	
	function getRouteLength($route, $start_id, $end_id,$DailyKM=false)
	{
		// check if not last point
		if (count($route) == $end_id)
		{
			$end_id -= 1;
		}
		
		$length = 0;
		$daily_kmData=array();
		$current_dailykm="";$previ_dailykm="";$dail_km=0;
		for ($i=$start_id; $i<$end_id; ++$i)
		{
			$lat1 = $route[$i][1];
			$lng1 = $route[$i][2];
			$lat2 = $route[$i+1][1];
			$lng2 = $route[$i+1][2];
			$dail_km = getLengthBetweenCoordinates($lat1, $lng1, $lat2, $lng2);
			$length +=  (float) $dail_km;
			if($DailyKM)
			{
				$current_dailykm=date("Y-m-d",strtotime($route[$i][0]));
				// if($previ_dailykm="" || $previ_dailykm!=$current_dailykm)
				// {
					$daily_kmData[]=array("date"=>$current_dailykm,"dailykm"=>$dail_km);	
					$previ_dailykm=$current_dailykm;
					$dail_km=0;
				// }
				
			}

		}
		$length = convDistanceUnits($length, 'km', $_SESSION["unit_distance"]);
		if($current_dailykm!=""){
			$daily_kmData[]=array("date"=>$current_dailykm,"dailykm"=>$dail_km);
			$dail_km=0;
		}
		if($DailyKM)
		return array("length"=>sprintf("%01.2f", $length),"dailykm"=>$daily_kmData);
		else
		return sprintf("%01.2f", $length);
	}
	
	function getRouteTopSpeed($route, $start_id, $end_id)
	{
		$top_speed = 0;
		for ($i=$start_id; $i<$end_id; ++$i)
		{
			if ($top_speed < $route[$i][5])
			{
				$top_speed = $route[$i][5];
			}
		}
		
		return $top_speed;
	}
	
	function getRouteAvgSpeed($route, $start_id, $end_id)
	{
		$avg_speed = 0;
		for ($i=$start_id; $i<$end_id; ++$i)
		{
			$avg_speed += $route[$i][5];
		}
		$num = $end_id - $start_id;
		
		return floor($avg_speed/$num);
	}
	
	function getRouteEngineHours($route, $start_id, $end_id, $acc)
	{		
		// check if not last point
		if (count($route) == $end_id)
		{
			$end_id -= 1;
		}
		
		$engine_hours = 0;
		
		for ($i=$start_id; $i<$end_id; ++$i)
		{
			$dt_tracker1 = $route[$i][0];
			$params1 = $route[$i][6];
			$dt_tracker2 = $route[$i+1][0];
			$params2 = $route[$i+1][6];

			if (isset($params1[$acc]) && isset($params2[$acc]))
			{
				if (($params1[$acc] == '1') && ($params2[$acc] == '1'))
				{
					$engine_hours += strtotime($dt_tracker2)-strtotime($dt_tracker1);
				}
			}
		}
		
		return $engine_hours;
	}
	
	function removeRouteJunkPoints($route, $accuracy)
	{
		$temp = array();
		
		$min_moving_speed = $accuracy['min_moving_speed'];
		$min_diff_points = $accuracy['min_diff_points'];		
		
		// filter drifting
		for ($i=0; $i<count($route)-1; ++$i)
		{
			$dt_tracker = $route[$i][0];
			
			$lat1 = $route[$i][1];
			$lng1 = $route[$i][2];
			$lat2 = $route[$i+1][1];
			$lng2 = $route[$i+1][2];
			
			$speed = $route[$i][5];
			
			$lat_diff = abs($lat1 - $lat2);
			$lng_diff = abs($lng1 - $lng2);
			
			if (($i == 0) || ($speed > $min_moving_speed) || ($lat_diff > $min_diff_points) && ($lng_diff > $min_diff_points))
			{
				$lat_temp = $lat2;
				$lng_temp = $lng2;
				
				$temp[] = $route[$i];
			}
			else
			{
				if (isset($lat_temp))
				{
					$route[$i][1] = $lat_temp;
					$route[$i][2] = $lng_temp;
				}
				$temp[] = $route[$i];
			}
			
		}
		$temp[] = $route[count($route)-1]; // add last point
		
		return $temp;
	}
	
	
	
	// code update by vetrivel.N
		
	function getTRIPWISE($imeis, $dtff, $dttt, $min_stop_duration, $filter,$speed_limit)
	{		
		global $ms;
 	      $dtff=date('Y-m-d H:i:s',strtotime('-30 minutes',strtotime($dtff)));
		// check privileges
		if ($_SESSION["privileges"] == 'subuser')
		{
			$user_id = $_SESSION["manager_id"];
		}
		else
		{
			$user_id = $_SESSION["user_id"];
		}
		$trip=array();
		$trip['trip'] = array();
		$trip['stops'] = array();
		$trip['drives'] = array();
		$trip['events'] = array();
		
				$q = "select dre.tripname,gu.name vehicleno,drt.routename,t.tid,t.imei,t.date,t.route_id,t.startzone,t.endzone,CONCAT(dre.tfh,'.',dre.tfm)starttime,
				CONCAT(dre.tth,'.',dre.ttm)endtime,t.midzonecount,t.midzone,Date_Format(convert_tz(t.astarttime,'+00:00','+05:30'),'%H.%i')astarttime,astarttime oastarttime,
				Date_Format(convert_tz(t.aendtime,'+00:00','+05:30'),'%H.%i')aendtime,aendtime oaendtime,
				(select zone_name from gs_user_zones where gs_user_zones.zone_id=t.startzone) as start_zone_name,
				(select zone_name from gs_user_zones where gs_user_zones.zone_id=t.endzone) as end_zone_name
				from tripdata t join gs_objects gu on gu.imei=t.imei
				join droute drt on drt.route_id=t.route_id and drt.user_id=t.user_id
				join droute_events dre on dre.event_id=t.eventid and drt.user_id=t.user_id
				and t.user_id='".$user_id."' and t.imei in (".$imeis.")  and (date between '".$dtff."' and '".$dttt."') order by t.date,t.starttime,vehicleno
			
				";
			
				 
				/*
				 	union				
				select dre.tripname,gu.name vehicleno,drt.routename,t.tid,t.imei,t.date,t.route_id,t.startzone,t.endzone,dre.datefrom starttime,
				dre.dateto endtime,t.midzonecount,t.midzone,Date_Format(convert_tz(t.astarttime,'+00:00','+05:30'),'%H.%i')astarttime,astarttime oastarttime,
				Date_Format(convert_tz(t.aendtime,'+00:00','+05:30'),'%H.%i')aendtime,aendtime oaendtime,
				(select zone_name from gs_user_zones where gs_user_zones.zone_id=t.startzone) as start_zone_name,
				(select zone_name from gs_user_zones where gs_user_zones.zone_id=t.endzone) as end_zone_name
				from tripdata_daily t join gs_user_trackers gu on gu.user_id=t.user_id and gu.imei=t.imei
				join droute drt on drt.route_id=t.route_id and drt.user_id=t.user_id
				join droute_events_daily dre on dre.event_id=t.eventid and drt.user_id=t.user_id
				and t.user_id='".$user_id."' and t.imei in (".$imeis.")  and (date between '".$dtff."' and '".$dttt."') order by t.date,t.starttime,vehicleno 
				 */
					
		$r = mysqli_query($ms,$q);
		

		if(!$r)
        {
        	return $trip;
        }
		$itd=0;
		while($trip_data=mysqli_fetch_array($r,MYSQLI_ASSOC))
		{
			$imei=$trip_data['imei'];
			$dtf=$trip_data['oastarttime'];
			$dtt=$trip_data['oaendtime'];
			$aendtime=$trip_data['aendtime'];
			$astarttime=$trip_data['astarttime'];
			
			$itd++;
			$dt_tracker = date('d/m/Y',(strtotime(convUserTimezone($trip_data['date']))));
			$delay=(floatval($trip_data['aendtime'])-floatval($trip_data['endtime']));
			$takentiming=getTimeDifferenceDetails($dtf,$dtt);
			$top=0;$avg=0;$takenkm=0;$stopduration="";
			$pieces=0;
			if($delay<0)
			{
				$pieces = explode(".", abs($delay));
				if(count($pieces)>1)
				$delay= "- ".$pieces[0].' H '.$pieces[1].' m ';		
				else 
			 	$delay= "- ".$pieces[0].' m ';
			}
			else 
			{
				try{
				$pieces = explode(".", abs($delay));
				if(count($pieces)>1)
				$delay= "+ ". $pieces[0].' H '.$pieces[1].' m ';
				else 
				$delay= "- ".$pieces[0].' m ';
				}
				catch (Exception $e) 
				{	
					echo 'Caught exception: ',  $e->getMessage(), "\n";
				}
			}
		
		$result = array();
		$result['route'] = array();
		$result['stops'] = array();
		$result['drives'] = array();
		$result['events'] = array();
		$result['stops_duration'] = 0;
		$result['route_length'] = 0;
		$result['top_speed'] = 0;
		$result['avg_speed'] = 0;
		$result['engine_work'] = 0;
		$result['engine_idle'] = 0;
		
			$accuracy = getObjectAccuracy($imei);
			$route = getRouteRaw($imei, $accuracy, ($trip_data['oastarttime']), ($trip_data['oaendtime']));
		
		
		if ( count($route) > 0&& isset($dtf) && isset($dtt) && ($dtf!="" && $dtf!=null) && ($dtt!="" && $dtt!=null) )
		{
			// get object fuel rates
			$fcr = getObjectFCR($imei);
			
			// get ACC sensor
			$sensor = getSensorValue($imei, 'acc');
			$acc = $sensor[0]['param'];
			
			// filter jumping cordinates
			if ($filter == true)
			{
				$route = removeRouteJunkPoints($route, $accuracy, array());
			}
			$result['route'] = $route;
			
			// merge params
			//$result['route'] = mergeRouteParams($route);
			
			// create stops
			if ($accuracy['stops'] == 'gpsacc')
			{
				$result['stops'] = getRouteStopsGPSACC($route, $accuracy, $min_stop_duration, $acc);	
			}
			else if ($accuracy['stops'] == 'acc')
			{
				$result['stops'] = getRouteStopsACC($route, $accuracy, $min_stop_duration, $acc);
			}
			else
			{
				$result['stops'] = getRouteStopsGPS($route, $accuracy, $min_stop_duration, $acc);
			}
			
			// create drives
			$result['drives'] = getRouteDrives($route, $accuracy, $result['stops'], $fcr, $acc);
			
			// load events
			$result['events'] = getRouteEvents($imei, $dtf, $dtt);
			
			// count route_length
			
			for ($i=0; $i<count($result['drives']); ++$i)
			{
				$result['route_length'] += $result['drives'][$i][4];
			}
			
			//$result['route_length']= floatval($route[count($route)-1][7]) - floatval($route[0][7]);
			
			// count top speed				
			
			for ($i=0; $i<count($result['drives']); ++$i)
			{
				if ($result['top_speed'] < $result['drives'][$i][5])
				{
					$result['top_speed'] = $result['drives'][$i][5];
				}
			}
			
			// count avg speed
			
			for ($i=0; $i<count($result['drives']); ++$i)
			{
				$result['avg_speed'] += $result['drives'][$i][6];
			}
			
			if (count($result['drives']) > 0)
			{
				$result['avg_speed'] = floor($result['avg_speed'] / count($result['drives']));
			}
				
			// count fc
			$result['fuel_consumption'] = 0;
			for ($i=0; $i<count($result['drives']); ++$i)
			{
				$result['fuel_consumption'] += $result['drives'][$i][7];
			}
			
			// count stops duration
			
			for ($i=0; $i<count($result['stops']); ++$i)
			{
				$diff = strtotime($result['stops'][$i][7])-strtotime($result['stops'][$i][6]);
				$result['stops_duration'] += $diff;
			}
			$result['stops_duration'] = getTimeDetails($result['stops_duration']);
			
			// count drives duration and engine work
			$result['drives_duration'] = 0;
			
			for ($i=0; $i<count($result['drives']); ++$i)
			{
				$diff = strtotime($result['drives'][$i][2])-strtotime($result['drives'][$i][1]);
				$result['drives_duration'] += $diff;
			}
			$result['drives_duration'] = getTimeDetails($result['drives_duration']);
			
			// prepare full engine work and idle info
		
			
			if ($acc != false)
			{
				for ($i=0; $i<count($result['drives']); ++$i)
				{
					$result['engine_work'] += $result['drives'][$i][8];
					$result['drives'][$i][8] = getTimeDetails($result['drives'][$i][8]);
				}
				
				for ($i=0; $i<count($result['stops']); ++$i)
				{
					$result['engine_idle'] += $result['stops'][$i][9];
					$result['stops'][$i][9] = getTimeDetails($result['stops'][$i][9]);	
				}
			}
			
			// set total engine work and idle
			$result['engine_work'] += $result['engine_idle'];
			$result['engine_work'] = getTimeDetails($result['engine_work']);
			$result['engine_idle'] = getTimeDetails($result['engine_idle']);
		}
	
				if ($speed_limit > 0)
				{
					$overspeeds = getRouteOverspeeds($result['route'], $speed_limit);
					$overspeeds_count = count($overspeeds);
				}
				else
				{
					$overspeeds_count = 0;
				}
	
				if (($dtf=="" || $dtf==null) || ($dtt=="" || $dtt==null))
				{
					$delay="";
					$takentiming="";
				}
				
			$trip['trip'][] = array($itd,
						$trip_data['vehicleno'],
						$trip_data['imei'],
						$trip_data['tripname'],
						$trip_data['routename'],
						$dt_tracker,
						$trip_data['start_zone_name'],
						$trip_data['end_zone_name'],
						$trip_data['starttime'],
						$trip_data['endtime'],
						$trip_data['astarttime'],
						$trip_data['aendtime'],
						$result['avg_speed'],
						$delay,
						$result['route_length'],
						($takentiming),
						$overspeeds_count,
						$result['stops_duration'],
						$trip_data['midzonecount'],
						$trip_data['midzone']
						
						);
						
		}
				
		return $trip;
	}
	
	function getoffline()
	{
		global $ms;
		$route = array();
		
		$yesterday = gmdate("Y-m-d H:m:s", time()-86400);
		
		$user_id="";
		if ($_SESSION["privileges"] == 'subuser')
		{
			$user_id = $_SESSION["manager_id"];
		}
		else
		{
			$user_id = $_SESSION["user_id"];
		}
		
		//$q = "SELECT gt.*,gut.name FROM gs_trackers gt join gs_user_trackers gut where gt.dt_tracker<'".$yesterday."' and gt.imei=gut.imei and gut.user_id='".$user_id."' order by gt.dt_tracker asc ";
		//$q = "SELECT go.*,guo.user_id FROM gs_objects go join gs_user_objects guo on guo.imei=go.imei where go.dt_tracker<'".$yesterday."' and go.imei=guo.imei and guo.user_id='".$user_id."' order by go.dt_tracker asc ";
		$q="SELECT go.*,guo.user_id,guog.group_name FROM gs_objects go join gs_user_objects guo on 
			guo.imei=go.imei left join gs_user_object_groups guog on guo.group_id=guog.group_id
			  where go.dt_tracker<'".$yesterday."' and go.imei=guo.imei and guo.user_id='".$user_id."' order by go.dt_tracker asc ";
				
		$r = mysqli_query($ms,$q);
		
		while($route_data=mysqli_fetch_array($r,MYSQLI_ASSOC))
		{
			$imei = $route_data['imei'];
			$dt_tracker = convUserTimezone($route_data['dt_tracker']);
			$lat = $route_data['lat'];
			$lng = $route_data['lng'];
			$altitude = $route_data['altitude'];
			$angle = $route_data['angle'];
			$speed = $route_data['speed'];
			$params = $route_data['params'];
			$name = $route_data['name'];
			$speed = convSpeedUnits($speed, 'km', $_SESSION["unit_distance"]);
			$altitude = convAltitudeUnits($altitude, 'km', $_SESSION["unit_distance"]);
			$duration=getTimeDifferenceDetails($route_data['dt_tracker'], gmdate("Y-m-d H:m:s"));
			$arr_params = paramsToArray($params);
			
			if (!isset($arr_params['gpslev']) || ($accuracy['use_gpslev'] == 'false'))
			{
				$arr_params['gpslev'] = 0;
				$accuracy['min_gpslev'] = 0;
			}
			
			if (!isset($arr_params['hdop']) || ($accuracy['use_hdop'] == 'false'))
			{
				$arr_params['hdop'] = 0;
				$accuracy['max_hdop'] = 0;
			}
			
			if (($arr_params['gpslev'] >= $accuracy['min_gpslev']) && ($arr_params['hdop'] <= $accuracy['max_hdop']))
			{
				
				if (($lat != 0) && ($lng != 0))
				{
					$route[] = array(	
								$imei,
								$dt_tracker,
								$lat,
								$lng,
								$altitude,
								$angle,
								$speed,
								$params,
								$name,
								$duration,
								$route_data['group_name'],
								$route_data['fueltype'],
								$route_data['fuel1'],
								$route_data['fuel2'],
								$route_data['temp1'],
								$route_data['temp2'],
								$route_data['sim_number'],
								$route_data['device']
								);
				}
			}
		}
		
		return $route;
	}

		
	function getofflinesensor()
	{
		global $ms;
		$route = array();
		
		$yesterday = gmdate("Y-m-d H:m:s", time()-86400);
		
		$user_id="";
		if ($_SESSION["privileges"] == 'subuser')
		{
			$user_id = $_SESSION["manager_id"];
		}
		else
		{
			$user_id = $_SESSION["user_id"];
		}
		
		$q = "SELECT go.*,guo.user_id FROM gs_objects go join gs_user_objects guo where go.fueltype='FUEL Sensor' and go.imei=guo.imei and guo.user_id='".$user_id."' order by go.dt_tracker asc ";		
		$r = mysqli_query($ms,$q);
		
		while($route_data=mysqli_fetch_array($r,MYSQLI_ASSOC))
		{
			/*
			$fuel1=0;$fuel1=0;$fuel1=0;$fuel1=0;
			
			for ($j=0; $j<count($fuel_sensors); ++$j)
			{				
				$before = getObjectSensorValue($params, $fuel_sensors[$j]); 
			
			}
			$fuel_sensors = getObjectSensorFromType($imei, 'fuel');
			*/
			$imei = $route_data['imei'];
			$params = $route_data['params'];
			$arr_params = paramsToArray($params);
			
			
			if(isset($arr_params['fuel1'])){
			if( floatval($arr_params['fuel1'])>100  ||$arr_params['fuel1']==".00" ||  $arr_params['fuel1']=="00.0" || $arr_params['fuel1']=="0.0" || $arr_params['fuel1']=="00.00"  || $arr_params['fuel1']=="0.00" || $arr_params['fuel1']=="")
			{
			
			$dt_tracker = convUserTimezone($route_data['dt_tracker']);
			$lat = $route_data['lat'];
			$lng = $route_data['lng'];
			$altitude = $route_data['altitude'];
			$angle = $route_data['angle'];
			$speed = $route_data['speed'];
		
			$name = $route_data['name'];
			$speed = convSpeedUnits($speed, 'km', $_SESSION["unit_distance"]);
			$altitude = convAltitudeUnits($altitude, 'km', $_SESSION["unit_distance"]);
			$duration=getTimeDifferenceDetails($route_data['dt_tracker'], gmdate("Y-m-d H:m:s"));
			
			
			if (!isset($arr_params['gpslev']) || ($accuracy['use_gpslev'] == 'false'))
			{
				$arr_params['gpslev'] = 0;
				$accuracy['min_gpslev'] = 0;
			}
			
			if (!isset($arr_params['hdop']) || ($accuracy['use_hdop'] == 'false'))
			{
				$arr_params['hdop'] = 0;
				$accuracy['max_hdop'] = 0;
			}
			
			if (($arr_params['gpslev'] >= $accuracy['min_gpslev']) && ($arr_params['hdop'] <= $accuracy['max_hdop']))
			{
				
				if (($lat != 0) && ($lng != 0))
				{
					$route[] = array(	
								$imei,
								$dt_tracker,
								$lat,
								$lng,
								$altitude,
								$angle,
								$speed,
								$params,
								$name,
								$duration,
								$arr_params['fuel1']
								);
				}
			}
			
			}}
			
		}
		
		return $route;
	}
	
	
	
	
	// code done by vetrivel.N
	
	
	//code done by vetrivel
	function getRouteRawnew($imei, $accuracy, $dtf, $dtt)
	{
	
		global $ms;
		$route = array();
		
		$extraqry="";
		if($accuracy['fueltype']=="FMS")
		{
			$extraqry=",fuelused";	
		}
		
		$q = "SELECT DISTINCT	dt_tracker,
					lat,
					lng,
					altitude,
					angle,
					speed,
					params,mileage".$extraqry."
					FROM `gs_object_data_".$imei."` WHERE dt_tracker BETWEEN '".$dtf."' AND '".$dtt."' and params!='' and params!='1' ORDER BY dt_tracker ASC";
					
		$r = mysqli_query($ms,$q);
		
		if(!$r)
        {
        	return $route;
        }
		
		while($route_data=mysqli_fetch_array($r,MYSQLI_ASSOC))
		{
			$dt_tracker = convUserTimezone($route_data['dt_tracker']);
			$lat = $route_data['lat'];
			$lng = $route_data['lng'];
			$altitude = $route_data['altitude'];
			$angle = $route_data['angle'];
			$speed = $route_data['speed'];
			$params =paramsToArray($route_data['params']);
			$mileage = $route_data['mileage'];
			$fuelused =0;
			if($accuracy['fueltype']=="FMS")
			{
				$fuelused = $route_data['fuelused'];
			}
			
			if($_SESSION["unit_distance"] == "mi")
			{
				$speed = floor($speed / 1.609344);
				$altitude = floor($altitude * 3.28084);
			}
			
			
			if (!isset($arr_params['gpslev']) || ($accuracy['use_gpslev'] == 'false'))
			{
				$arr_params['gpslev'] = 0;
				$accuracy['min_gpslev'] = 0;
			}
			
			if (!isset($arr_params['hdop']) || ($accuracy['use_hdop'] == 'false'))
			{
				$arr_params['hdop'] = 0;
				$accuracy['max_hdop'] = 0;
			}
			
			if ($arr_params['gpslev'] >= $accuracy['min_gpslev'] && $arr_params['hdop'] <= $accuracy['max_hdop'])
			{
				if (($lat != 0) && ($lng != 0))
				{
					$route[] = array(	$dt_tracker,
								$lat,
								$lng,
								$altitude,
								$angle,
								$speed,
								$params,
								$mileage,
								$fuelused
								);
				}
			}
		}

		return $route;
	}
	
	
	//code update by vetrivel.N
	
	function caculateoverspeedcountdb($dataevents)
	{
		$Countnow=0;
		$counttemp=0;
		for ($i=0; $i<count($dataevents); ++$i)
		{
			if($dataevents[$i][8]=="overspeed")
			{
				//$Countnow++;
				$Countnow=$Countnow+1;
			}
			elseif ($dataevents[$i][8]=="temp_abn")
			{
				$counttemp++;
			}
		}
		
		$retarr=array();
		$retarr[0]=$Countnow;
		$retarr[1]=$counttemp;
		
		return $retarr;
	}
	
	function getNearestZonev($imei,$lat,$lng)
	{
		global $ms;
		$user_id="";
		if ($_SESSION["privileges"] == 'subuser')
		{
			$user_id = $_SESSION["manager_id"];
		}
		else
		{
			$user_id = $_SESSION["user_id"];
		}

		$in_zone_vertices = array();
		$name = '';
		$zone_id = "";
		$distance = 0;
		
		$q = "SELECT * FROM `gs_user_zones` WHERE `user_id`='".$user_id."' ";
		$r = mysqli_query($ms,$q);
		
		while($zone = mysqli_fetch_array($r,MYSQLI_ASSOC))
		{	
			$zone_vertices = explode(",",$zone['zone_vertices']);
			for ($j = 0;$j < count($zone_vertices);$j += 2) 
			{
				$zone_lat = $zone_vertices[$j];
				$zone_lng = $zone_vertices[$j + 1];
				$temp = getLengthBetweenCoordinates($lat, $lng, $zone_lat, $zone_lng);
				if ($distance > $temp || $distance == 0) 
				{
					$distance = $temp;
					$name = $zone['zone_name'];
					$in_zone_vertices = $zone_vertices;
				}
			}
		}

	$allpolystring="";
	for ($j = 0;$j < count($in_zone_vertices);$j += 2) 
	{
  		$zone_lat = floatval($in_zone_vertices[$j]);
		$zone_lng = floatval($in_zone_vertices[$j + 1]);
		if($allpolystring=="")
		$allpolystring.=$zone_lat.",".$zone_lng;
		else 
		$allpolystring.=",".$zone_lat.",".$zone_lng;

	}

	if($allpolystring!="")
	{
	if (isPointInPolygon($allpolystring, $lat, $lng)) 
	{
		$distance = 0;
	}
	
	$distance =$distance; //convDistanceUnits($distance, 'km', $_SESSION['unit_distance']);
	$distance = round($distance,2);
	$distance = "(" .$distance ." km)";
		
	$result = array();
	$result['name'] = $name;
	$result['distance'] = $distance;

	return ($name." ".$distance);

	}
	else
	{
		return "";
	}


	}


	//code daone by vetrivel.N


	//code done by vetrivel.N

	function getRouteFuelFillingsnew($route, $accuracy, $fuel_sensors)
	{
		$result = array();
		$result['fillings'] = array();

		if ($fuel_sensors == false)
		{
			return $result;
		}

		$diff_ff = $accuracy['min_ff'];
		$total_filled = 0;

		for ($i=0; $i<count($route)-1; ++$i)
		{
			$params1 = $route[$i][6];
			$params2 = $route[$i+1][6];

			$speed1 = $route[$i][5];
			$speed2 = $route[$i+1][5];

		
			// loop per fuel sensors
			for ($j=0; $j<count($fuel_sensors); ++$j)
			{
				$before=array();
				$after=array();
				
				if($accuracy['fueltype']=='FMS')
				{
					$before['value'] = $route[$i][8]; // fuel level
					$after['value'] = $route[$i+1][8]; // fuel level in next point
					$before['value_full'] = $route[$i][8]; // fuel level
					$after['value_full'] = $route[$i+1][8]; // fuel level in next point
				}
				else
				{
					$before = getSensorValue($params1, $fuel_sensors[$j]); // fuel level
					$after = getSensorValue($params2, $fuel_sensors[$j]); // fuel level in next point
				}
				
				//$before = getSensorValue($params1, $fuel_sensors[$j]); // fuel level
				//$after = getSensorValue($params2, $fuel_sensors[$j]); // fuel level in next point
				
				$diff = $after['value'] - $before['value']; // fuel filling
				$sensor = $fuel_sensors[$j]['name'];
				//if (($diff >$diff_ff) && (($speed1 < 5) || ($speed2 < 5)))
				if((($speed1 < 5) && ($speed2 < 5)))
				{
					if (($diff >1))
					{
						//echo $before['value'].' '.$after['value'].' '.$diff.'</br>';
	
						$dt_tracker_start = $route[$i][0];
						
						$dt_tracker = $route[$i+1][0];
						
						$lat = $route[$i+1][1];
						$lng = $route[$i+1][2];
						
						$filled = $after['value'] - $before['value'];

						$params = $route[$i+1][6];
						
						if(!isset($result['fillings'][$sensor]))
						{
							$result['fillings'][$sensor][] = array("end"=>$dt_tracker,
										"lat"=>$lat,
										"lng"=>$lng,
										"before"=>$before['value_full'],
										"after"=>$after['value_full'],
										"filled"=>$filled,
										"sensor"=>$sensor,
										"params"=>$params,
										"start"=>$dt_tracker_start,
										"sensor_unit"=>$fuel_sensors[$j]['units'],
										"row"=>$i+1,
										"mileage_start"=> $route[$i][7],
										"mileage_end"=> $route[$i+1][7],
										"closed"=>"N");
						}
						else if(isset($result['fillings'][$sensor]))
						{
							 $datetime1 = strtotime($result['fillings'][$sensor][count($result['fillings'][$sensor])-1]["start"]);
							 
							 $datetime2 = strtotime($dt_tracker);
							 $interval  = abs($datetime2 - $datetime1);
							 $minutes   = round($interval / 60);
							 
							 if($minutes<=15)
						 	 {
						 	 	$beforev=$result['fillings'][$sensor][count($result['fillings'][$sensor])-1]["before"];
						 	 	$filledv=$after['value_full']-$beforev;
						 	 	$closed=$result['fillings'][$sensor][count($result['fillings'][$sensor])-1]["closed"];

						 	 	if($filledv>0 && $closed=="N")
						 	 	{
						 	 		$result['fillings'][$sensor][count($result['fillings'][$sensor])-1]["end"]=$dt_tracker;
						 	 		$result['fillings'][$sensor][count($result['fillings'][$sensor])-1]["after"]=$after['value_full'];
						 	 		$result['fillings'][$sensor][count($result['fillings'][$sensor])-1]["filled"]=$filledv;
						 	 		$result['fillings'][$sensor][count($result['fillings'][$sensor])-1]["row"]=$i+1;
						 	 		$result['fillings'][$sensor][count($result['fillings'][$sensor])-1]["mileage_end"]=$route[$i+1][7];
						 	 		$result['fillings'][$sensor][count($result['fillings'][$sensor])-1]["closed"]="N";
						 	 	}
						 	 	else
						 	 	{
						 	 		$mileabs  = abs($route[$i][7] - $route[$i+1][7]);
						 	 		if($mileabs<2)
						 	 		{
							 	 		$result['fillings'][$sensor][] = array("end"=>$dt_tracker,
											"lat"=>$lat,
											"lng"=>$lng,
											"before"=>$before['value_full'],
											"after"=>$after['value_full'],
											"filled"=>$filled,
											"sensor"=>$sensor,
											"params"=>$params,
											"start"=>$dt_tracker_start,
							 	 			"sensor_unit"=>$fuel_sensors[$j]['units'],
							 	 			"row"=>$i+1,
											"mileage_start"=> $route[$i][7],
											"mileage_end"=> $route[$i+1][7],
							 	 			"closed"=>"N");
						 	 		}
						 	 	}
						 	 }
						 	 else 
						 	 {
						 	 						 	 		
						 	 	$result['fillings'][$sensor][] = array("end"=>$dt_tracker,
										"lat"=>$lat,
										"lng"=>$lng,
										"before"=>$before['value_full'],
										"after"=>$after['value_full'],
										"filled"=>$filled,
										"sensor"=>$sensor,
										"params"=>$params,
										"start"=>$dt_tracker_start,
						 	 			"sensor_unit"=>$fuel_sensors[$j]['units'],
						 	 			"row"=>$i+1,
										"mileage_start"=> $route[$i][7],
										"mileage_end"=> $route[$i+1][7],
						 	 			"closed"=>"N");
						 	 }
						}	
					}
				}
				else 
				{
					if(isset($result['fillings'][$sensor]) && count($result['fillings'][$sensor])>0)
					$result['fillings'][$sensor][count($result['fillings'][$sensor])-1]["closed"]="Y";
				}
			}
		}


		$rtnv['fillings'] = array();
		//secondary optimization going to run 
		for ($j=0; $j<count($fuel_sensors); ++$j)
		{
			$sensor = $fuel_sensors[$j]['name'];
			if(isset($result['fillings'][$sensor]))
			{
			for ($i=0; $i<count($result['fillings'][$sensor]); $i++)
			{
				if($result['fillings'][$sensor][$i]["filled"]>5)
				{
					$datetime1 = strtotime($result['fillings'][$sensor][$i]["start"]);
					$datetime2 = strtotime($result['fillings'][$sensor][$i]["end"]);
					$interval  = abs($datetime2 - $datetime1);
					$minutes   = round($interval / 60);
					if($minutes<=20)
					{
						$mvs = floatval($result['fillings'][$sensor][$i]["mileage_start"]);
						$mve = floatval($result['fillings'][$sensor][$i]["mileage_end"]);
						if(abs($mve-$mvs)<1)
						{
							$total_filled+=$result['fillings'][$sensor][$i]["filled"];
							$rtnv['fillings'][$sensor][]=$result['fillings'][$sensor][$i];
						}
					}	
				}
			}
			}
		}		
		
		$rtnv['total_filled'] = $total_filled.' '.$fuel_sensors[0]['units'];
		
		return $rtnv;
	}
	
	
	function getRouteFuelTheftsnew($route, $accuracy, $fuel_sensors)
	{
		$result = array();
		$result['thefts'] = array();
		
		if ($fuel_sensors == false)
		{
			return $result;
		}
		
		$diff_ft = $accuracy['min_ft'];
		
		
		for ($i=0; $i<count($route)-1; ++$i)
		{
			$params1 = $route[$i][6];
			$params2 = $route[$i+1][6];
			
			$speed1 = $route[$i][5];
			$speed2 = $route[$i+1][5];
			
			// loop per fuel sensors
			for ($j=0; $j<count($fuel_sensors); ++$j)
			{
				$before=array();
				$after=array();

				if($accuracy['fueltype']=='FMS')
				{
					$before['value'] = $route[$i][8]; // fuel level
					$after['value'] = $route[$i+1][8]; // fuel level in next point
					$before['value_full'] = $route[$i][8]; // fuel level
					$after['value_full'] = $route[$i+1][8]; // fuel level in next point
				}
				else
				{
					$before = getSensorValue($params1, $fuel_sensors[$j]); // fuel level
					$after = getSensorValue($params2, $fuel_sensors[$j]); // fuel level in next point
				}	
				//$before = getSensorValue($params1, $fuel_sensors[$j]); // fuel level
				//$after = getSensorValue($params2, $fuel_sensors[$j]); // fuel level in next point

				$diff = $before['value'] - $after['value']; // fuel filling
				
				//if (($diff >= $diff_ft) && (($speed1 < 5) || ($speed2 < 5)))
				
				if (($diff >= 1) && (($speed1 < 5) && ($speed2 < 5)))
				{
					$dt_tracker_start = $route[$i][0];
					
					$dt_tracker = $route[$i+1][0];
					
					$lat = $route[$i+1][1];
					$lng = $route[$i+1][2];
					
					$stolen = $before['value'] - $after['value'];
					
					$sensor = $fuel_sensors[$j]['name'];
					
					$params = $route[$i+1][6];
					
					if(!isset($result['thefts'][$sensor]))
					{
						$result['thefts'][$sensor][] = array("end"=>$dt_tracker,
									"lat"=>$lat,
									"lng"=>$lng,
									"before"=>$before['value_full'],
									"after"=>$after['value_full'],
									"siphon"=>$stolen,
									"sensor"=>$sensor,
									"params"=>$params,
									"start"=>$dt_tracker_start,
					 	 			"sensor_unit"=>$fuel_sensors[$j]['units'],
					 	 			"row"=>$i+1,
									"mileage_start"=> $route[$i][7],
									"mileage_end"=> $route[$i+1][7]);
					}
					else if(isset($result['thefts'][$sensor]))
					{
						 $datetime1 = strtotime($result['thefts'][$sensor][count($result['thefts'][$sensor])-1]["start"]);
						 
						 $datetime2 = strtotime($dt_tracker);
						 $interval  = abs($datetime2 - $datetime1);
						 $minutes   = round($interval / 60);
						 
						 if($minutes<=10)
					 	 {
					 	 	$beforev=$result['thefts'][$sensor][count($result['thefts'][$sensor])-1]["before"];
					 	 	$filledv=$beforev-$after['value_full'];
					 	 	
					 	 	if($filledv>0)
					 	 	{
					 	 		$result['thefts'][$sensor][count($result['thefts'][$sensor])-1]["end"]=$dt_tracker;
					 	 		$result['thefts'][$sensor][count($result['thefts'][$sensor])-1]["after"]=$after['value_full'];
					 	 		$result['thefts'][$sensor][count($result['thefts'][$sensor])-1]["siphon"]=$filledv;
					 	 		$result['thefts'][$sensor][count($result['thefts'][$sensor])-1]["row"]=$i+1;
					 	 		$result['thefts'][$sensor][count($result['thefts'][$sensor])-1]["mileage_end"]=$route[$i+1][7];
					 	 	}
					 	 }
					 	 else 
					 	 {
					 	 						 	 		
					 	 	$result['thefts'][$sensor][] = array("end"=>$dt_tracker,
									"lat"=>$lat,
									"lng"=>$lng,
									"before"=>$before['value_full'],
									"after"=>$after['value_full'],
									"siphon"=>$stolen,
									"sensor"=>$sensor,
									"params"=>$params,
									"start"=>$dt_tracker_start,
					 	 			"sensor_unit"=>$fuel_sensors[$j]['units'],
					 	 			"row"=>$i+1,
									"mileage_start"=> $route[$i][7],
									"mileage_end"=> $route[$i+1][7]);
					 	 }
					}
				}
			}
		}
		$total_stolen=0;
		$rtnv['thefts'] = array();
		//secondary optimization going to run
		for ($j=0; $j<count($fuel_sensors); ++$j)
		{
			$sensor = $fuel_sensors[$j]['name'];
			if(isset($result['thefts'][$sensor]))
			{
			for ($i=0; $i<count($result['thefts'][$sensor]); $i++)
			{
				if($result['thefts'][$sensor][$i]["siphon"]>5)
				{
					$datetime1 = strtotime($result['thefts'][$sensor][$i]["start"]);
					$datetime2 = strtotime($result['thefts'][$sensor][$i]["end"]);
					$interval  = abs($datetime2 - $datetime1);
					$minutes   = round($interval / 60);
					if($minutes<=20)
					{
						$mvs = floatval($result['thefts'][$sensor][$i]["mileage_start"]);
						$mve = floatval($result['thefts'][$sensor][$i]["mileage_end"]);
						if(abs($mve-$mvs)<1)
						{
							$total_stolen+=$result['thefts'][$sensor][$i]["siphon"];
							$rtnv['thefts'][$sensor][]=$result['thefts'][$sensor][$i];
						}
					}	
				}
			}
			}
		}		
		
		$rtnv['total_stolen'] = $total_stolen.' '.$fuel_sensors[0]['units'];
		
		return $rtnv;
	}

	
	
	function getRouteFuelFillingsnewold($route, $accuracy, $fuel_sensors)
	{
		$result = array();
		
		$prevrslt = array();
		
		if ($fuel_sensors == false)
		{
			return $result;
		}
		
		$prev_fuel=0;
		$diff_ff =floatval($accuracy['min_ff']);
	
		for ($i=0; $i<count($route)-1; ++$i)
		{
			$odo=$route[$i][7];
			$params1 = $route[$i][6];
			$params2 = $route[$i+1][6];
			
			$speed1 = $route[$i][5];
			$speed2 = $route[$i+1][5];

			// loop per fuel sensors
			for ($j=0; $j<count($fuel_sensors); ++$j)
			{				
				$before = getSensorValue($params1, $fuel_sensors[$j]); // fuel level
				$after = getSensorValue($params2, $fuel_sensors[$j]); // fuel level in next point
				
				$before['value']=floatval ($before['value'] );
				$after['value']=floatval ($after['value'] );
				
			//if first value greater then next value
			  if($before['value'] < $after['value'])
		      {
				if(empty( $prevrslt ))
				{
					$prev_fuel=floor($before['value']);
					$prevrslt[] = array($route[$i][0],$route[$i][1],$route[$i][2],$before['value'],$speed1,$params1);
				}
		      }
		      else
		      {

		      	if(!empty( $prevrslt ))
		      	{

		      		$filled = $after['value'] - $prevrslt[0][3]; // fuel filling
		      		//if (($diff > $prev_fuel) && (($prevrslt[0][4] <= 10) || ($speed2 < 10)))
				if (($filled>0   && $after['value']> $prev_fuel ) && ($prevrslt[0][4] <= 10) )
				{
					$dt_tracker = $route[$i+1][0];					
					$lat = $route[$i+1][1];
					$lng = $route[$i+1][2];
										
					$sensor = $fuel_sensors[$j]['name'];
					
					$ibut = getParamValue($params2, 'ibut');
					$rfid = getParamValue($params2, 'rfid');
					$driver = getObjectDriverFromIbutRFIDnew($ibut, $rfid);
					
					if(count($result)==0)
					{
						$filled =$after['value'] - $prevrslt[0][3];
						//$prefil=floatval($after['value']) - floatval($prevrslt[0][3]);
						//if == 0 store new one 
						   if($filled > $diff_ff && $after['value']> $prev_fuel )
						 	{
								$result[] = array(	$prevrslt[0][0],
								$prevrslt[0][1],
								$prevrslt[0][2],
								$prevrslt[0][3],
								$after['value'],
								$filled,
								$sensor,
								$driver['driver_name']
								,$route[$i][0]
								,getTimeDifferenceDetails($prevrslt[0][0],$route[$i][0]),
								$odo,
								$speed1,
								$prevrslt[0][4],
								);
						 	}
						 	$prev_fuel=floor($after['value']);
					}
					else
					 {
					 	//else chck with old if time diff between 10 mins update with old time
					 	 $date1 = new DateTime($result[count( $result)-1][0]);
						 $date2 = new DateTime($prevrslt[0][0]);
						 $interval = $date1->diff($date2);
						 
						 $datetime1 = strtotime($result[count( $result)-1][0]);
						 $datetime2 = strtotime($prevrslt[0][0]);
						 $interval  = abs($datetime2 - $datetime1);
						 $minutes   = round($interval / 60);
						 
						 
					 	 //if(($interval ->s <=60 || $interval ->i <= 10 )&& $interval ->h==0 && $interval ->y==0 && $interval ->m==0 && $interval ->d==0 )
					 	 if($minutes<=15 && $speed1<5)
					 	 {
					 	 	$filled =$after['value'] - floatval($result[count( $result)-1][3]);
							
					 	 		$result[count( $result)-1] = array($result[count( $result)-1][0],
								$prevrslt[0][1],
								$prevrslt[0][2],
								$result[count( $result)-1][3],
								$after['value'],
								$filled,
								$sensor,
								$driver['driver_name']
								,$route[$i][0]
								,getTimeDifferenceDetails($result[count( $result)-1][0],$route[$i][0]),
								$odo,
								$speed1,
								$result[count( $result)-1][12]
								);
							$prev_fuel=floor($after['value']);
						 }
						 else
						 {
						 	$filled=$after['value'] - floatval($prevrslt[0][3]);
						 	if($filled > $diff_ff && $after['value']> $prev_fuel  && $speed1<5)
						 	{
							 	$result[] = array(	$prevrslt[0][0],
							 	$prevrslt[0][1],
							 	$prevrslt[0][2],
							 	$prevrslt[0][3],
							 	$after['value'],
							 	$filled,
							 	$sensor,
							 	$driver['driver_name']
							 	,$route[$i][0]
							 	,getTimeDifferenceDetails($prevrslt[0][0],$route[$i][0]),
							 	$odo,
							 	$speed1,
							 	$prevrslt[0][4]
							 	);
							 	$prev_fuel=floor($after['value']);
						 	}
						 	
						 	
						 }
					}

					
				}
			
				//clear array
				// unset($prevrslt);
				 $prevrslt = array();
				 
			   }
			   
			  }
				// here may be i should write code
			}
		}
		

		$total_filled=0;
		$resultfinal=array();
		for ($i=0; $i<count($result); $i++)
		{
			$datetime1 = strtotime($result[$i][0]);
			$datetime2 = strtotime($result[$i][8]);
			$interval  = abs($datetime2 - $datetime1);
			$minutes   = round($interval / 60);				 
			//if($minutes > 1 && $diff_ff<=floatval($result[$i][5]) && 10<=floatval($result[$i][5])  && floatval($result[$i][3])!=0 &&  intval($result[$i][11])<10 &&  intval($result[$i][12])<10 )
			if($minutes > 1 && $diff_ff<=floatval($result[$i][5]) && 10<=floatval($result[$i][5])  && floatval($result[$i][3])!=0 &&  intval($result[$i][11])<10  )
			{
				$total_filled+=$result[$i][5];
				$resultfinal["fillings"][]=$result[$i];	
			}
		}
		
		
		$resultfinal['total_filled'] = $total_filled.' '.$fuel_sensors[0]['units'];
		return $resultfinal;
	}
	
	function getRouteFuelTheftsnewold($route, $accuracy, $fuel_sensors)
	{
		$result = array();
		$prevrslt = array();

		if ($fuel_sensors == false)
		{
			return $result;
		}

		$diff_ff = $accuracy['min_ft'];

		for ($i=0; $i<count($route)-1; ++$i)
		{
			$odo=$route[$i][7];
			$params1 = $route[$i][6];
			$params2 = $route[$i+1][6];

			$speed1 = $route[$i][5];
			$speed2 = $route[$i+1][5];

			if($speed1<5 && $speed2<5)
			{

				// loop per fuel sensors
				for ($j=0; $j<count($fuel_sensors); ++$j)
				{

					$sensor = $fuel_sensors[$j]['name'];
					$before=array();
					$after=array();

					if($accuracy['fueltype']=='FMS')
					{
						$before['value'] = $route[$i][8]; // fuel level
						$after['value'] = $route[$i+1][8]; // fuel level in next point
					}
					else
					{
						$before = getSensorValue($params1, $fuel_sensors[$j]); // fuel level
						$after = getSensorValue($params2, $fuel_sensors[$j]); // fuel level in next point
					}

					//if first value greater then next value
			  if(floatval ($before['value'] )>floatval($after['value'] ) )
			  {
			  	if(empty( $prevrslt ))
			  	{
			  		$prevrslt[] = array("bfr_dt"=>$route[$i][0],"lat"=>$route[$i][1],"lng"=>$route[$i][2],"bfr"=>$before['value'],"bfr_speed"=>$speed1,"bfr_paramm"=>$params1);
			  	}
			  }
			  else
			  {
			  	if(!empty( $prevrslt ))
			   {

			   	$diff =floatval($prevrslt[0]["bfr"]) - floatval($after['value']); // fuel filling
			   	if (($diff >= 4))
			   	{
			   		$dt_tracker = $route[$i][0];
			   		$filled =$diff;
			   		$ibut = getParamValue($params2, 'ibut');
			   		$rfid = getParamValue($params2, 'rfid');
			   		$driver = getObjectDriverFromIbutRFIDnew($ibut, $rfid);
			   		 
			   		if(count($result)==0)
			   		{
			   			//if == 0 store new one
			   			$result[] = array("bfr_dt"=>$prevrslt[0]["bfr_dt"],
								"lat"=>$prevrslt[0]["lat"],
								"lng"=>$prevrslt[0]["lng"],
								"bfr"=>$prevrslt[0]["bfr"],
								"aftr"=>$after['value'],
								"theft"=>$filled,
								"sensor"=>$sensor,
								"driver"=>$driver['driver_name']
			   			,"aftr_dt"=>$dt_tracker
			   			,"diff"=>getTimeDifferenceDetails($prevrslt[0]["bfr_dt"],$route[$i+1][0]),
								"bfr_speed"=>$speed1,
								"aftr_speed"=>$speed2,
								"mileage"=>$route[$i+1][7],
								"finished"=>"no"
								);
			   		}
			   		else
			   		{
			   			//else chck with old if time diff between 10 mins update with old time
			   			$date1 = strtotime($result[count($result)-1]["bfr_dt"]);
			   			$date2 = strtotime($dt_tracker);
			   			$tmp=round(abs($date1 - $date2) / 60,2);
			   			if ($tmp<= 10 && $result[count($result)-1]["finished"]=="no" )
			   			{

			   				$filled =floatval($result[count( $result)-1]["bfr"]) - floatval($after['value']);

			   				$result[count( $result)-1]["aftr"]=$after['value'];
			   				$result[count( $result)-1]["theft"]=$filled;
			   				$result[count( $result)-1]["aftr_dt"]=$dt_tracker;
			   				$result[count( $result)-1]["diff"]=getTimeDifferenceDetails($result[count($result)-1]["bfr_dt"],$dt_tracker);
			   				$result[count( $result)-1]["aftr_speed"]=$speed2;
			   				$result[count( $result)-1]["mileage"]=$route[$i+1][7];

			   			}
			   			else
			   			{
			   				$result[] = array("bfr_dt"=>$prevrslt[0]["bfr_dt"],
								"lat"=>$prevrslt[0]["lat"],
								"lng"=>$prevrslt[0]["lng"],
								"bfr"=>$prevrslt[0]["bfr"],
								"aftr"=>$after['value'],
								"theft"=>$filled,
								"sensor"=>$sensor,
								"driver"=>$driver['driver_name']
			   					,"aftr_dt"=>$dt_tracker
			   					,"diff"=>getTimeDifferenceDetails($prevrslt[0]["bfr_dt"],$dt_tracker),
								"bfr_speed"=>$speed1,
								"aftr_speed"=>$speed2,
								"mileage"=>$route[$i+1][7],
								"finished"=>"no"
								);
			   			}
			   		}

			   	}
			   
			  	 if(count($result)>0 )
				 {
					$result[count($result)-1]["finished"]="yes";
				 }
			   	
			   	$prevrslt = array();
			   }

			    
			  }

				}
			}
			else
			{
				 if(count($result)>0 )
				 {
					$result[count($result)-1]["finished"]="yes";
				 }
				 $prevrslt = array();
			}
		}

		//echo json_encode($result);
		$resultfinal=array();
		
		 for ($i=0; $i<count($result); $i++)
		 {
			if(floatval($result[$i]["theft"])>0 )
			{
				$resultfinal[]=$result[$i];
			}
		 }

			//echo json_encode($resultfinal);
			
		return $result;
	}

	function fngettripconsumption($route, $accuracy, $fuel_sensors,$ff, $dtf, $dtt,$imei)
	{
		
		
		$retarr = array();
		$get1=null;$get2=null;
		
		for ($j=0; $j<count($fuel_sensors); ++$j)
		{
			$sensor = $fuel_sensors[$j];
			if(isset($ff['fillings'][$sensor["name"]]))
			{
				for($i=0;$i<count($ff['fillings'][$sensor["name"]]);$i++)
				{
					$get1=date("Y-m-d H:i:s" ,strtotime($ff['fillings'][$sensor["name"]][$i]["start"]));
					$get2=date("Y-m-d H:i:s" ,strtotime($ff['fillings'][$sensor["name"]][$i]["end"]));
					if($i==0)
					{		
				    	$retarr[] = fnrouteparams($route,$dtf,convUserUTCTimezone($get1),$sensor,$imei);
					}
					else
					{
						$get3=date("Y-m-d H:i:s" ,strtotime($ff['fillings'][$sensor["name"]][$i-1]["end"]));

			    		$retarr[] = fnrouteparams($route,convUserUTCTimezone($get3),convUserUTCTimezone($get1),$sensor,$imei);
					}
				}
				$retarr[] = fnrouteparams($route,convUserUTCTimezone($get2),($dtt),$sensor,$imei);
			}
			else 
			{
				$retarr[] = fnrouteparams($route,$dtf,($dtt),$sensor,$imei);
			}
		}		
		if(count($retarr)>0)
		{
			return $retarr;
		}	
		else
		{
			return false;
		}	
	}
	
	function  fnrouteparamstest($route, $timefrom, $timeto, $fuel_sensors, $imei)
	{
			$accuracy = getObjectAccuracy($imei);
			$sensor = getSensorFromType($imei, 'acc');
			$acc = $sensor[0]['param'];
			$fcr = getObjectFCR($imei);
			$result['route'] = $route;
			$min_stop_duration=1;
			// create stops
			if ($accuracy['stops'] == 'gpsacc')
			{
				$result['stops'] = getRouteStopsGPSACC($route, $accuracy, $min_stop_duration, $acc);	
			}
			else if ($accuracy['stops'] == 'acc')
			{
				$result['stops'] = getRouteStopsACC($route, $accuracy, $min_stop_duration, $acc);
			}
			else
			{
				$result['stops'] = getRouteStopsGPS($route, $accuracy, $min_stop_duration, $acc);
			}
			
			// create drives
			$fuel_sensors = getSensorFromType($imei, 'fuel');
			$fuelcons_sensors = getSensorFromType($imei, 'fuelcons');
			$result['drives'] = getRouteDrives($route, $accuracy, $result['stops'], $fcr, $fuel_sensors, $fuelcons_sensors, $acc,false);
			

			// count route_length
			$result['route_length'] = 0;
			$result['daily_kmdata'] = array();
			for ($i=0; $i<count($result['drives']); ++$i)
			{
				$result['route_length'] += $result['drives'][$i][7];
			}

		$final3=array();
		if(count($route)>0)
		{
			$p1v = getSensorValue($route[0][6], $fuel_sensors[0]); // fuel level
			$p2v = getSensorValue($route[count($route)-1][6], $fuel_sensors[0]); // fuel level in next point
			$difduration=getTimeDifferenceDetails($route[0][0],$route[count($route)-1][0]);
			$mil=($route[count($route)-1][7]-$route[0][7] );							
			$fc=$p1v["value"]-$p2v["value"];
			$final3[]=array( "dt_trackerfrom" => $route[0][0],"dt_trackerto"=> $route[count($route)-1][0],"duration"=>$difduration,
			"mi1"=>$result['route_length'],"mi2"=>$result['route_length'],"mid"=>$mil,"fuel"=>$fc);	
	
		}	
		

		
		return  $final3;
		
	}

	/* Old version method replaced by new to makes fast by NR.vetrivel*/
	function fnrouteparams($route,$timefrom,$timeto,$fuel_sensors,$imei)
	{
		if($fuel_sensors==FALSE)
		{
			return;	
		}
		global $ms;
		$begin=$timefrom;
		$end=$timeto;
		$final1=array();
		$final3=array();
		
			$q="select * from gs_object_data_".$imei." where 
			dt_tracker=(select max(dt_tracker) from gs_object_data_".$imei." where
 			dt_tracker between '".$begin."' AND '".$end."')
 			or dt_tracker=(select min(dt_tracker) from gs_object_data_".$imei." where
 			dt_tracker between '".$begin."' AND '".$end."')";
			
			$r = mysqli_query($ms,$q);

			if($r!=false)
			{
				while ($relt = mysqli_fetch_array($r)) 
				{
			  	 	$final1[]=$relt;
				}
			}
		if(count($final1)>0)
		{
			$p1v = getSensorValue(paramsToArray($final1[0]["params"]), $fuel_sensors); // fuel level
			$p2v = getSensorValue(paramsToArray($final1[count($final1)-1]["params"]), $fuel_sensors); // fuel level in next point
			$difduration=getTimeDifferenceDetails($final1[0]["dt_tracker"],$final1[count($final1)-1]["dt_tracker"]);
			$mil=round(abs($final1[count($final1)-1]["mileage"]-$final1[0]["mileage"] ),2);					
			$fc=($p1v["value"]-$p2v["value"]);
			$final3[]=array( "dt_trackerfrom" => $final1[0]["dt_tracker"],"dt_trackerto"=> $final1[count($final1)-1]["dt_tracker"],"duration"=>$difduration,
			"mi1"=>$final1[0]["mileage"],"mi2"=>$final1[count($final1)-1]["mileage"],"mid"=>$mil,"fuel"=>$fc);	
		}	

		return  $final3;
		
	}	
	
	
	function fnrouteparamsDELETEABLE($route,$timefrom,$timeto,$fuel_sensors,$imei)
	{
		if($fuel_sensors==FALSE)
		{
		return;	
		}
		global $ms;
		$begin=$timefrom;
		$end=$timeto;
	
		
		
		$final1=array();
		$final3=array();
		
			$q = "SELECT dt_tracker,
					lat,
					lng,
					altitude,
					angle,
					speed,
					params,mileage FROM `gs_object_data_".$imei."` WHERE dt_tracker BETWEEN '".$begin."' AND '".$end."'  order by dt_tracker asc ";
			$r = mysqli_query($ms,$q);

			if($r!=false)
			{
				while ($relt = mysqli_fetch_array($r)) 
				{
				   $final1[]=$relt;
				}
			}
			
			$rlength= getRouteLength($route, 0, count($route),false);
			
			if(count($final1)>0)
			{
				$p1v = getSensorValue($final1[0]["params"], $fuel_sensors[0]); // fuel level
				$p2v = getSensorValue($final1[count($final1)-1]["params"], $fuel_sensors[0]); // fuel level in next point
	
				$difduration=getTimeDifferenceDetails($final1[0]["dt_tracker"],$final1[count($final1)-1]["dt_tracker"]);
		
				$mil=($final1[count($final1)-1]["mileage"]-$final1[0]["mileage"] );					
		
				$fc=$p1v["value"]-$p2v["value"];
				$final3[]=array( "dt_trackerfrom" => $final1[0]["dt_tracker"],"dt_trackerto"=> $final1[count($final1)-1]["dt_tracker"],"duration"=>$difduration,
				"mi1"=>$rlength,"mi2"=>$rlength,"mid"=>$mil,"fuel"=>$fc);	
	
			}		
	
		return  $final3;
		
	}	

	function fnrouteparams1($route,$timefrom,$timeto,$dtf, $dtt,$fuel_sensors)
	{
		if($fuel_sensors==FALSE)
		{
		return;	
		}
		
		$begin=null;
		$end=null;
		
		if($dtf<$timefrom )
		{
		   $begin=$dtf;
		   $end =$timefrom;
		}
		else
	    {
		   $begin=$timefrom;
		   $end =$dtt;
		}
		
		
		$param1=array();
		$param2=array();
		
		$final3=array();
		
		for($j=0;$j<count($route);$j++)
		{
		  $curdate=date("Y-m-d H:i:s" ,strtotime($route[$j][0]));
		 
		 	if($curdate==$begin)
			{
				
				$param1[]=$route[$j];
			}
		 
			if($curdate<=$end)
			{
				$param2[]=$route[$j];
			}
				
		}
	
		$p1v = getSensorValue($param1[0][6], $fuel_sensors[0]); // fuel level
		$p2v = getSensorValue($param2[0][6], $fuel_sensors[0]); // fuel level in next point
				
		$fc=$p1v["value"]-$p2v["value"];
		$final3[]=array($param1[0][0],$param2[0][0],getTimeDifferenceDetails($param1[0][0], $param2[0][0]),$param1[0][7],$param2[0][7],($param2[0][7]-$param1[0][7]),$fc);
		
					
		return  $final3;
		
	}	

	function getObjectDriverFromIbutRFIDnew($ibut, $rfid)
	{		
		global $ms;
		$driver = false;
		
		if (($ibut != '') && ($ibut != 0))
		{
			$id = $ibut;
		}
		
		if (($rfid != '') && ($rfid != 0))
		{
			$id = $rfid;
		}
		
		if (isset($id))
		{
			$q = "SELECT * FROM `gs_user_object_drivers` WHERE `driver_ibutrfid`='".$id."'";
			$r = mysqli_query($ms,$q);
			$driver = mysqli_fetch_array($r,MYSQLI_ASSOC);
		}
		
		return $driver;
	}
	
    function rfidtripreport($imei, $dtf, $dtt)
    {
       
        global $ms;
        $user_id=0;
        if ($_SESSION["privileges"] == 'subuser')
        {
            $user_id = $_SESSION["manager_id"];
        }
        else
        {
            $user_id = $_SESSION["user_id"];
        }
        
        $route = array();
        
        $q = "select r.*,g.name from rfidgps r join gs_objects g on r.imei=g.imei and r.imei='".$imei."'
        and r.user_id='".$user_id."' and ( r.starttime BETWEEN '".$dtf."' AND '".$dtt."'  )  ORDER BY r.uid ASC";
        //or r.endtime BETWEEN '".$dtf."' AND '".$dtt."'
   
 
        $r = mysqli_query($ms,$q);
        
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC))
        {
            $mileage=0;
            
            $q1 = "SELECT DISTINCT   dt_tracker,
                    lat,
                    lng,
                    altitude,
                    angle,
                    speed,
                    params,mileage
                    FROM `gs_object_data_".$imei."` WHERE dt_tracker BETWEEN '".$dtf."' AND '".$dtt."' and params!='' and params!='1' ORDER BY dt_tracker ASC";
                
            $r1 = mysqli_query($ms,$q1);
            
            $final1=array();
            while($route_data=mysqli_fetch_array($r1,MYSQLI_ASSOC))
            {
               $final1[]=$route_data;
            }
            if(count($final1)>0)
            $mileage=($final1[count($final1)-1]["mileage"]-$final1[0]["mileage"] );
            $stsv="";
            $dtev="";
            if(isset($row['starttime']))
            $dtsv=convUserTimezone( $row['starttime']);
           
            if(($row['endtime'])!='')
            {
            $dtev=convUserTimezone( $row['endtime']);
            }
            else
            {
              $dtev='Open';
 
            }
            
            
            
            $route[] = array($row['name'],$row['vehicleno'],$row['vendorname'],$row['drivername'],$row['driverphone'],$row['driverrfid'],$dtsv,$dtev,$mileage);
               
        }
        
        return $route;
    }
    
    
    function detailrfidtripreport($imei, $dtf, $dtt,$driverrfid)
    {
    	global $ms;
        $dtf=convUserUTCTimezone($dtf);

          if($dtt=='Open' || $dtt==null)
            {
             $dtt=convUserTimezone(gmdate("Y-m-d H:i:s"));
            }
            else
            {
               $dtf=convUserUTCTimezone($dtt);  
            }

            $rfidtrip = array();
            $finalqr=array();
            
            $qr="select * from gs_rfid_swipe_data where imei='".$imei."' and rfid='".$driverrfid."' and dt_swipe between '".$dtf."' and '".$dtt."' order by dt_swipe";
            $rq1 = mysqli_query($ms,$qr); 
                     
            while($row_qr=mysqli_fetch_array($rq1,MYSQLI_ASSOC))
            {
               $finalqr[]=$row_qr;

            } 
           

            $cnt1=count($finalqr);
                     
            for ($j=0; $j < $cnt1; $j+=2) 
            {
                  $f1= $finalqr[$j];
                  $f2=null;  
                $f2dt=null;
                if(count($finalqr)>($j+1))
                {   
                     $f2= $finalqr[$j+1];
                     $f2dt=$f2["dt_swipe"];
                     
                }
                else
                {
                    $f2=null;
                    $f2dt=convUserTimezone(gmdate("Y-m-d H:i:s"));
                }
                    
                      $q1 = "SELECT DISTINCT   dt_tracker,lat,lng,altitude,angle,speed,params,mileage
                             FROM `gs_object_data_".$imei."` WHERE dt_tracker BETWEEN '".$f1["dt_swipe"]."' AND '".$f2dt."' and params!='' and params!='1' ORDER BY dt_tracker ASC";
               
                       $r1 = mysqli_query($ms,$q1);
            
                       $final12=array();
                       while($route_data=mysqli_fetch_array($r1,MYSQLI_ASSOC))
                       {
                            $final12[]=$route_data;
                       }
                       $mileage=null;
                        if(count($final12)>0)
                        $mileage=($final12[count($final12)-1]["mileage"]-$final12[0]["mileage"] );
            
                   $rfidtrip[]=array("starttime"=>$f1["dt_swipe"],"lat1"=>$f1["lat"],"lng1"=>$f1["lng"],"endtime"=>$f2["dt_swipe"],"lat2"=>$f2["lat"],"lng2"=>$f2["lng"],"mileage"=>$mileage,"driverrfid"=>$driverrfid);

            }
     

        return  $rfidtrip;
    }
    
    function detailrfidtripemployee($imei, $dtf, $dtt,$driverrfid)
    {
    	global $ms;
               $dte=null;
               if($dtt==null)
               {
                 $dte=convUserTimezone(gmdate("Y-m-d H:i:s"));
               }
               else
               {
                    $dte=$dtt;
               }
       
            $rfidtrip = array();
 
            $finalqr0=array();
            
            $qr0="select distinct gw.rfid,re.name from gs_rfid_swipe_data gw,rfidemployee re where gw.imei='".$imei."'  and rfid!='".$driverrfid."' and gw.dt_swipe between '".$dtf."' and '".$dte."' and re.rfidid=gw.rfid order by re.name,gw.dt_swipe";          

            $rq0 = mysqli_query($ms,$qr0); 
                                 
            while($row_qr0=mysqli_fetch_array($rq0,MYSQLI_ASSOC))
            {
               $finalqr0[]=$row_qr0;

            } 

            $cnt0=count($finalqr0);
                     
            for ($j0=0; $j0 < $cnt0; $j0++) 
            {
                $finalqr=array();
                       
                $rfidnew=  $finalqr0[$j0]['rfid'];     
                                 
                $qr="select distinct gw.*,re.name from gs_rfid_swipe_data gw,rfidemployee re where gw.imei='".$imei."'  and rfid='".$rfidnew."' and gw.dt_swipe between '".$dtf."' and '".$dte."' and re.rfidid=gw.rfid order by re.name,gw.dt_swipe";          

            $rq1 = mysqli_query($ms,$qr); 
                                 
            while($row_qr=mysqli_fetch_array($rq1,MYSQLI_ASSOC))
            {
               $finalqr[]=$row_qr;

            } 

            $cnt1=count($finalqr);
                     
            for ($j=0; $j < $cnt1; $j+=2) 
            {
                $f2= null;
                
                if(count($finalqr)>($j+1))
                {
                    $f2= $finalqr[$j+1];
                    $f2["dt_swipe"]= convUserTimezone($f2["dt_swipe"]);
                } 
                   
                     $f1= $finalqr[$j];
                  
                     $rfidtrip[]=array("name"=>$f1["name"],"starttime"=> convUserTimezone($f1["dt_swipe"]),"lat1"=>$f1["lat"],"lng1"=>$f1["lng"],"endtime"=>$f2["dt_swipe"],"lat2"=>$f2["lat"],"lng2"=>$f2["lng"]);
    
            }  
                
            }

        return  $rfidtrip;
    }
    
    
    function detailrfidtripemployeeold($imei, $dtf, $dtt,$driverrfid)
    {
         global $ms;   
        $rfidtrip = array();
              
            $finalqr=array();
            
            $qr="select gw.*,re.name from gs_rfid_swipe_data gw,rfidemployee re where gw.imei='".$imei."'  and rfid!='".$driverrfid."' and gw.dt_swipe between '".$dtf."' and '".$dte."' and re.rfidid=gw.rfid order by re.name,gw.dt_swipe";          

            $rq1 = mysqli_query($ms,$qr); 
                                 
            while($row_qr=mysqli_fetch_array($rq1,MYSQLI_ASSOC))
            {
               $finalqr[]=$row_qr;

            } 

            $cnt1=count($finalqr);
                     
            for ($j=0; $j < $cnt1; $j+=2) 
            {
                if(count($finalqr)>($j+1))
                {
                    
                     $f1= $finalqr[$j];
                     $f2= $finalqr[$j+1];
                     $rfidtrip[]=array("name"=>$f1["name"],"starttime"=>$f1["dt_swipe"],"lat1"=>$f1["lat"],"lng1"=>$f1["lng"],"endtime"=>$f2["dt_swipe"],"lat2"=>$f2["lat"],"lng2"=>$f2["lng"]);
                   
                }
            }
            
       
          
        return  $rfidtrip;
    }
    
	
	//code update end here vetrivel.N
	
	/*Code DOne By VETRIVEL.N*/
	function getUserObjectIMEInew($id,$groupid=false)
	{
		global $ms;
		$result = array();

		if ($_SESSION["privileges"] == 'subuser')
		{
			$q = "SELECT * FROM `gs_user_objects`
			WHERE `user_id`='".$id ."' AND `imei` IN (".$_SESSION["privileges_imei"].") ORDER BY `imei` ASC";
		}
		else
		{
			$q = "SELECT * FROM `gs_user_objects` WHERE `user_id`='".$id ."'";
			if(($groupid!=false && $groupid!='') || $groupid=='0'){
				$q.=" and `group_id`='".$groupid."'";
			}
			$q.=" ORDER BY `imei` ASC";
		}	

		$r = mysqli_query($ms,$q);
		$iiii=0;
		while($row = mysqli_fetch_array($r,MYSQLI_ASSOC))
		{
			//$result .= '"'.$row['imei'].'",';
			//if($iiii<10)
			$result[] = $row['imei'];$iiii=$iiii+1;
		}
		//$result = rtrim($result, ',');
		
		return $result;
	}
	
	 function getLtrforFMS($imei)
	{
		global  $ms;
			$fuel1l=0;
			$qf="select param,units,SPLIT_STR(formula, '|',2) mul from gs_tracker_sensors where imei='".$imei."' and param='fuel1' ";
			$resultf = mysqli_query($ms,$qf);
			if($resultf!=false)
			{
			while($rowf = mysql_fetch_array($resultf,MYSQLI_ASSOC))
			{
				//echo json_encode($rowf);
				$fuel1l=$rowf['mul'];						
			}	
			}
			
			return  $fuel1l;
	}
	
	
	function getRouteTripConsumption($imei, $dtf, $dtt, $min_stop_duration, $filter,$accuracy)
	{		
	
		
		$result = array();
		$result['route'] = array();
		$result['stops'] = array();
		$result['drives'] = array();
		$result['events'] = array();
		
		if (checkObjectActive($imei) != true)
		{
			return $result;
		}

		$route = getRouteRawnew($imei, $accuracy, $dtf, $dtt);
		
		
		if (count($route) > 0)
		{
			// get object fuel rates
			$fcr = getObjectFCR($imei);
			
			// get ACC sensor
			$sensor = getSensorFromType($imei, 'acc');

			$acc = $sensor[0]['param'];
			
			// filter jumping cordinates
			if ($filter == true)
			{
				$route = removeRouteJunkPoints($route, $accuracy, array());
			}
			$result['route'] = $route;
			
			// merge params
			//$result['route'] = mergeRouteParams($route);
			
			if($accuracy['fueltype']!='FMS')
			{
			
				// create stops
				if ($accuracy['stops'] == 'gpsacc')
				{
					$result['stops'] = getRouteStopsGPSACC($route, $accuracy, $min_stop_duration, $acc);	
				}
				else if ($accuracy['stops'] == 'acc')
				{
						
					$result['stops'] = getRouteStopsACC($route, $accuracy, $min_stop_duration, $acc);
				}
				else
				{
					$result['stops'] = getRouteStopsGPS($route, $accuracy, $min_stop_duration, $acc);
				}
			
			}
			else 
			{
				$result['stops'] = getRouteStopsACC($route, $accuracy, $min_stop_duration, $acc);
			}
			
			
			
			// create drives
			$result['drives'] = getRouteDrivesTripConsumtion($route, $accuracy, $result['stops'], $fcr, $acc);
			
			if(isset($result['drives']))
			{
				$ard=array();
				for($ird=0;$ird<count($result['drives']);$ird++)
				{
					if(intval($result['drives'][$ird][4]>10))
					$ard[]=$result['drives'][$ird];
				}
				$result['drives']=$ard;
			}

			// count route_length
			$result['route_length'] = 0;
			for ($i=0; $i<count($result['drives']); ++$i)
			{
				$result['route_length'] += $result['drives'][$i][4];
			}
			
			// count top speed				
			$result['top_speed'] = 0;
			for ($i=0; $i<count($result['drives']); ++$i)
			{
				if ($result['top_speed'] < $result['drives'][$i][5])
				{
					$result['top_speed'] = $result['drives'][$i][5];
				}
			}
			
			// count avg speed
			$result['avg_speed'] = 0;
			for ($i=0; $i<count($result['drives']); ++$i)
			{
				$result['avg_speed'] += $result['drives'][$i][6];
			}
			
			if (count($result['drives']) > 0)
			{
				$result['avg_speed'] = floor($result['avg_speed'] / count($result['drives']));
			}
				
			// count fc
			$result['fuel_consumption'] = 0;
			for ($i=0; $i<count($result['drives']); ++$i)
			{
				$result['fuel_consumption'] += $result['drives'][$i][7];
			}
			
			// count stops duration
			$result['stops_duration'] = 0;
			for ($i=0; $i<count($result['stops']); ++$i)
			{
				$diff = strtotime($result['stops'][$i][7])-strtotime($result['stops'][$i][6]);
				$result['stops_duration'] += $diff;
			}
			$result['stops_duration'] = getTimeDetails($result['stops_duration'], true);
			
			// count drives duration and engine work
			$result['drives_duration'] = 0;
			
			for ($i=0; $i<count($result['drives']); ++$i)
			{
				$diff = strtotime($result['drives'][$i][2])-strtotime($result['drives'][$i][1]);
				$result['drives_duration'] += $diff;
			}
			$result['drives_duration'] = getTimeDetails($result['drives_duration'], true);
			
			// prepare full engine work and idle info
			$result['engine_work'] = 0;
			$result['engine_idle'] = 0;
			
			if ($acc != false)
			{
				for ($i=0; $i<count($result['drives']); ++$i)
				{
					$result['engine_work'] += $result['drives'][$i][8];
					$result['drives'][$i][8] = getTimeDetails($result['drives'][$i][8], true);
				}
				
				for ($i=0; $i<count($result['stops']); ++$i)
				{
					$result['engine_idle'] += $result['stops'][$i][9];
					$result['stops'][$i][9] = getTimeDetails($result['stops'][$i][9], true);	
				}
			}
			
			// set total engine work and idle
			$result['engine_work'] += $result['engine_idle'];
			$result['engine_work'] = getTimeDetails($result['engine_work'], true);
			$result['engine_idle'] = getTimeDetails($result['engine_idle'], true);
		}
		
		return $result;
	}
	
	
	function getRouteDrivesTripConsumtion($route, $accuracy, $stops, $fcr, $acc)
	{
		
		
		$drives = array();
		
		if (count($stops) == 0)
		{
			// moving between start and end marker if no stops
			$start_s_id = 0;
			$start_id = 0;
			$end_id = count($route);
			
			$dt_start_s = $route[$start_s_id][0];
			$dt_start = $route[$start_id][0];
			$dt_end = $route[$end_id-1][0];
			
			$sta_mileage = $route[$start_id][7];
			$end_mileage = $route[$end_id-1][7];
			
			if ($dt_start != $dt_end)
			{
				$moving_duration = getTimeDifferenceDetails($dt_start, $dt_end);
				$route_length = getRouteLength($route, $start_id, $end_id);
				$top_speed = getRouteTopSpeed($route, $start_id, $end_id);
				$avg_speed = getRouteAvgSpeed($route, $start_id, $end_id);
				$fuel_consumption = getRouteFuelTRIPConsumption($route, $start_id, $end_id, $accuracy);
				$engine_work = getRouteEngineHours($route, $start_id, $end_id, $acc);
				
				$drives[] = array(	$dt_start_s,
							$dt_start,
							$dt_end,
							$moving_duration,
							$route_length,
							$top_speed,
							$avg_speed,
							$fuel_consumption,
							$engine_work,
							$sta_mileage,
							round( $end_mileage,2)
							);
			}
		}
		else
		{
			// moving between start and first stop
			$start_s_id = 0;
			$start_id = 0;
			$end_id = $stops[0][0];
			
			if ($end_id != 0)
			{
				$dt_start_s = $route[$start_s_id][0];
				$dt_start = $route[$start_id][0];
				$dt_end = $route[$end_id][0];
				
					$sta_mileage = $route[$start_id][7];
					$end_mileage = $route[$end_id][7];
			
				if ($dt_start != $dt_end)
				{
					$moving_duration = getTimeDifferenceDetails($dt_start, $dt_end);
					$route_length = getRouteLength($route, $start_s_id, $end_id);
					$top_speed = getRouteTopSpeed($route, $start_s_id, $end_id);
					$avg_speed = getRouteAvgSpeed($route, $start_s_id, $end_id);
					$fuel_consumption = getRouteFuelTRIPConsumption($route, $start_s_id, $end_id, $accuracy);
					$engine_work = getRouteEngineHours($route, $start_id, $end_id, $acc);
					
					$drives[] = array(	$dt_start_s,
								$dt_start,
								$dt_end,
								$moving_duration,
								$route_length,
								$top_speed,
								$avg_speed,
								$fuel_consumption,
								$engine_work,
								$sta_mileage,
								round( $end_mileage,2)
							);
				}
			}
			
			// moving between end and last stop								
			$start_s_id = $stops[count($stops)-1][0];
			$start_id = $stops[count($stops)-1][1];
			$end_id = count($route);
			
			if ($start_id != $end_id-1)
			{
				$dt_start_s = $route[$start_s_id][0];
				$dt_start = $route[$start_id][0];
				$dt_end = $route[$end_id-1][0];
				
				$sta_mileage = $route[$start_id][7];
				$end_mileage = $route[$end_id-1][7];
				
				if ($dt_start != $dt_end)
				{
					$moving_duration = getTimeDifferenceDetails($dt_start, $dt_end);
					$route_length = getRouteLength($route, $start_s_id, $end_id);
					$top_speed = getRouteTopSpeed($route, $start_s_id, $end_id);
					$avg_speed = getRouteAvgSpeed($route, $start_s_id, $end_id);
					$fuel_consumption = getRouteFuelTRIPConsumption($route, $start_s_id, $end_id, $accuracy);
					$engine_work = getRouteEngineHours($route, $start_id, $end_id, $acc);
					
					$drives[] = array(	$dt_start_s,
								$dt_start,
								$dt_end,
								$moving_duration,
								$route_length,
								$top_speed,
								$avg_speed,
								$fuel_consumption,
								$engine_work,
								$sta_mileage,
								round( $end_mileage,2)
								);
				}
			}	
		}
		
		// moving between stops
		for ($i=0; $i<count($stops)-1; ++$i)
		{
			$start_s_id = $stops[$i][0];
			$start_id = $stops[$i][1];
			$end_id = $stops[$i+1][0];
			
			$dt_start_s = $route[$start_s_id][0];
			$dt_start = $route[$start_id][0];
			$dt_end = $route[$end_id][0];
			
			$sta_mileage = $route[$start_id][7];
			$end_mileage = $route[$end_id][7];
				
			if ($dt_start != $dt_end)
			{
				$moving_duration = getTimeDifferenceDetails($dt_start, $dt_end);
				$route_length = getRouteLength($route, $start_s_id, $end_id);
				$top_speed = getRouteTopSpeed($route, $start_s_id, $end_id);
				$avg_speed = getRouteAvgSpeed($route, $start_s_id, $end_id);
				$fuel_consumption = getRouteFuelTRIPConsumption($route, $start_s_id, $end_id, $accuracy);
				$engine_work = getRouteEngineHours($route, $start_id, $end_id, $acc);
				
				$drives[] = array(	$dt_start_s,
							$dt_start,
							$dt_end,
							$moving_duration,
							$route_length,
							$top_speed,
							$avg_speed,
							$fuel_consumption,
							$engine_work,
							$sta_mileage,
							round( $end_mileage,2)
							);
			}
		}
		
		return $drives;
	}
		
	function getRouteFuelTRIPConsumption($route, $start_id, $end_id, $accuracy)
	{
		$fuel_consumtion = 0;
		
		if($accuracy['fueltype']=="FMS")
		{
			$first=$route[$start_id][8];
			$second=$route[$end_id-1][8];
			$fuel_consumtion = $second-$first;
		}
		else 
		{
			$paramF=paramsToArray($route[$start_id][6]);
			$paramE=paramsToArray($route[$end_id-1][6]);
			$first= $paramF['fuel1'];
			$second= $paramE['fuel1'];
			$fuel_consumtion = $second-$first;
		}
		
		
		return sprintf("%01.2f", $fuel_consumtion);
	}
	
	
	
	function getmaintenance($imeis,$dtf,$dtt)
	{
		global $ms;		
		$route = array();	
      
		$from = $_POST['dtf'];
		$to = $_POST['dtt'];
		
		$user_id="";
		if ($_SESSION["privileges"] == 'subuser')
		{
			$user_id = $_SESSION["manager_id"];
		}
		else
		{
			$user_id = $_SESSION["user_id"];
			
		}
		
		if($imeis!='')
			$q="SELECT ss.*,gu.username,sd.staff_name,GROUP_CONCAT(sw.`work` SEPARATOR ', ')as work_type,gso.name FROM  staff_service ss join gs_users gu  on  ss.client_id=gu.id join staff_data sd on ss.staff_id=sd.staff_id join staff_service_sub sss on ss.service_id=sss.service_id join staff_work sw on sss.work_id=sw.work_id join gs_objects gso on ss.imei=gso.imei where ss.client_id='".$user_id."' and ss.imei in (".$imeis.") and (ss.schedule_date between date('".$from."') and date('".$to."')) GROUP BY sss.service_id  order by ss.schedule_date asc";
		else  
			$q="SELECT ss.*,gu.username,sd.staff_name,GROUP_CONCAT(sw.`work` SEPARATOR ', ')as work_type,gso.name FROM  staff_service ss join gs_users gu  on  ss.client_id=gu.id join staff_data sd on ss.staff_id=sd.staff_id join staff_service_sub sss on ss.service_id=sss.service_id join staff_work sw on sss.work_id=sw.work_id join gs_objects gso on ss.imei=gso.imei where ss.client_id='".$user_id."'  and (ss.schedule_date between date('".$from."') and date('".$to."'))  GROUP BY sss.service_id  order by ss.schedule_date asc";
		$r = mysqli_query($ms,$q);
	   
		
		while($maint=mysqli_fetch_assoc($r))
		{
		
			$client_id = $maint['client_id'];
			$staff_id = $maint['staff_id'];
			$site_location = $maint['site_location'];
			$schedule_date = $maint['schedule_date'];
			$imei = $maint['imei'];
			$object = $maint['name'];
			$works = $maint['works'];			
			$intime = $maint['intime'];	
			$outtime = $maint['outtime'];	
			// $warrenty = $maint['warrenty'];	
			if($maint['warrenty']=='true'){
			 $warrenty = 'YES';
			}else{
				$warrenty = 'NO';
			}	
			$office_note = $maint['office_note'];
			$username = $maint['username'];
			$staff_name = $maint['staff_name'];			
			$work = $maint['work_type'];
			$company=$maint['company'];
		       
			
			if (($client_id != 0) && ($staff_id != 0))
			{
				$route[] = array($username,$company,$site_location,$schedule_date,$imei,$object,$maint['vehicle_type'],$maint['fuel1'],$works,$work,getObjectAccessories($imei),$maint['status'],$staff_name,$warrenty,$maint['service_close'],$office_note);
			}
			
		}
		
		return $route;
	}

	function get_fueldata_offline($imei,$dtf)
	{
		global $ms;		
		$route = array();	
      
		$from = $_POST['dtf'];
		
		
		$qf="SELECT SUM(filled) filled FROM fuel_fill_offline
 		WHERE date(end_time)=date('".$from."') AND imei='".$imei."' group BY date(end_time);";
		
		$qt="SELECT SUM(theft) theft FROM fuel_theft_offline
 		WHERE date(end_time)=date('".$from."') AND imei='".$imei."' group BY date(end_time);";
		
		$ro1 = mysqli_query($ms,$qf);
		$ro2 = mysqli_query($ms,$qt);
	   	$rf="0";
	   	$rt="0";
	   		
		if($rof=mysqli_fetch_assoc($ro1))
		{
			$rf=$rof["filled"];
		}
		if($rot=mysqli_fetch_assoc($ro2))
		{
			$rt=$rot["theft"];
		}
		
		$rtn["fill"]=$rf;
		$rtn["theft"]=$rt;
		
		return $rtn;
	}
	
	function getRouteLengthByDT($route, $start_datetime, $end_datetime, $DailyKM = false)
{
    // Convert the datetime strings to timestamps for comparison
    $start_timestamp = strtotime($start_datetime);
    $end_timestamp = strtotime($end_datetime);
    
    // Initialize length and daily km tracking variables
    $length = 0;
    $daily_kmData = array();
    $current_dailykm = "";
    $previ_dailykm = "";
    $dail_km = 0;

    // Loop through the route data
    for ($i = 0; $i < count($route) - 1; ++$i) {
        // Get the timestamp for the current and next point (route[0] is the datetime)
        $route_timestamp = strtotime($route[$i][0]);

        // If the current point is within the date range, calculate the distance
        if ($route_timestamp >= $start_timestamp && $route_timestamp <= $end_timestamp) {
            $lat1 = $route[$i][1];
            $lng1 = $route[$i][2];
            $lat2 = $route[$i + 1][1];
            $lng2 = $route[$i + 1][2];
            
            // Calculate the distance between the points
            $dail_km = getLengthBetweenCoordinates($lat1, $lng1, $lat2, $lng2);
            $length += (float)$dail_km;

            if ($DailyKM) {
                // Get the date part of the current timestamp for daily km tracking
                $current_dailykm = date("Y-m-d", $route_timestamp);

                // Track daily kilometers, resetting on a new date
                if ($previ_dailykm != $current_dailykm) {
                    $daily_kmData[] = array("date" => $current_dailykm, "dailykm" => $dail_km);
                    $previ_dailykm = $current_dailykm;
                    $dail_km = 0;
                }
                // Accumulate daily distance for the same day
                $dail_km += $dail_km;
            }
        }
    }

    // Convert the total length to the preferred unit
    $length = convDistanceUnits($length, 'km', $_SESSION["unit_distance"]);

    // If daily kilometers were requested, add the last day’s data
    if ($current_dailykm != "") {
        $daily_kmData[] = array("date" => $current_dailykm, "dailykm" => $dail_km);
    }

    // Return the result with daily km data or just the total length
    if ($DailyKM) {
        return array("length" => sprintf("%01.2f", $length), "dailykm" => $daily_kmData);
    } else {
        return sprintf("%01.2f", $length);
    }
}


	
?>
