
<?php


function reportsGenerateDailyKM($imeis, $dtf, $dtt, $speed_limit, $stop_duration, $data_items,$format) //GenerateDailyKM
	{
		global $_SESSION, $la, $user_id;
		
		$result = '<table class="report" width="100%"><tr align="center">';
		
		$result .= '<th>'.$la['OBJECT'].'</th>';
		
		if (in_array("route_start", $data_items))
		{
			$result .= '<th>'.$la['ROUTE_START'].'</th>';
		}
		
		if (in_array("route_end", $data_items))
		{
			$result .= '<th>'.$la['ROUTE_END'].'</th>';
		}
		
		if (in_array("route_length", $data_items))
		{
			$result .= '<th>'.$la['ROUTE_LENGTH'].'</th>';
		}
		
		if (in_array("move_duration", $data_items))
		{
			$result .= '<th>'.$la['MOVE_DURATION'].'</th>';
		}
		
		if (in_array("stop_duration", $data_items))
		{
			$result .= '<th>'.$la['STOP_DURATION'].'</th>';
		}
		
		if (in_array("stop_count", $data_items))
		{
			$result .= '<th>'.$la['STOP_COUNT'].'</th>';
		}
		
		if (in_array("top_speed", $data_items))
		{
			$result .= '<th>'.$la['TOP_SPEED'].'</th>';
		}
		
		if (in_array("avg_speed", $data_items))
		{
			$result .= '<th>'.$la['AVG_SPEED'].'</th>';
		}
		
		if (in_array("overspeed_count", $data_items))
		{
			$result .= '<th>'.$la['OVERSPEED_COUNT'].'</th>';
		}
		
		if (in_array("fuel_consumption", $data_items))
		{
			$result .= '<th>'.$la['FUEL_CONSUMPTION'].'</th>';
		}
		
		if (in_array("fuel_cost", $data_items))
		{
			$result .= '<th>'.$la['FUEL_COST'].'</th>';
		}
		
		if (in_array("engine_work", $data_items))
		{
			$result .= '<th>'.$la['ENGINE_WORK'].'</th>';
		}
		
		if (in_array("engine_idle", $data_items))
		{
			$result .= '<th>'.$la['ENGINE_IDLE'].'</th>';
		}
		
		if (in_array("odometer", $data_items))
		{
			$result .= '<th>'.$la['ODOMETER'].'</th>';
		}
		
		if (in_array("engine_hours", $data_items))
		{
			$result .= '<th>'.$la['ENGINE_HOURS'].'</th>';
		}
		
		if (in_array("driver", $data_items))
		{
			$result .= '<th>'.$la['DRIVER'].'</th>';
		}
		
		if (in_array("trailer", $data_items))
		{
			$result .= '<th>'.$la['TRAILER'].'</th>';
		}
		
		$result .='<th>'.$la['VIEW_DETAIL'].'</th>';
		
		$result .= '</tr>';
		
		$total_route_length = 0;
		$total_drives_duration = 0;
		$total_stops_duration = 0;
		$total_stop_count = 0;
		$total_top_speed = 0;
		$total_avg_speed = 0;
		$total_overspeed_count = 0;
		$total_fuel_consumption = 0;
		$total_fuel_cost = 0;
		$total_engine_work = 0;
		$total_engine_idle = 0;
		$total_odometer = 0;
		$total_engine_hours = 0;
		
		$is_data = false;
		
		for ($i=0; $i<count($imeis); ++$i)
		{
			$imei = $imeis[$i];
			
			$data = getRoute($imei, $dtf, $dtt, $stop_duration, true,true);
					
			if (count($data['route']) == 0)
			{
				$result .= '<tr align="center">';
				$result .= '<td>'.getObjectName($imei).'</td>';
				$result .= '<td colspan="17">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td>';
				$result .= '</tr>';
			}
			else
			{
				$is_data = true;
				
				if ($speed_limit > 0)
				{
					$overspeeds = getRouteOverspeeds($data['route'], $speed_limit);
					$overspeed_count = count($overspeeds);
				}
				else
				{
					$overspeed_count = 0;
				}
				
				$odometer = getObjectOdometer($imei);
				$odometer = floor(convDistanceUnits($odometer, 'km', $_SESSION["unit_distance"]));
				
				$result .= '<tr align="center">';
				
				$result .= '<td>'.getObjectName($imei).'</td>';
				
				if (in_array("route_start", $data_items))
				{
					$result .= '<td>'.$data['route'][0][0].'</td>';
				}
				
				if (in_array("route_end", $data_items))
				{
					$result .= '<td>'.$data['route'][count($data['route'])-1][0].'</td>';
				}
				
				if (in_array("route_length", $data_items))
				{
					$result .= '<td>'.$data['route_length'].' '.$la["UNIT_DISTANCE"].'</td>';
					
					$total_route_length += $data['route_length'];
				}
				
				if (in_array("move_duration", $data_items))
				{
					$result .= '<td>'.$data['drives_duration'].'</td>';
					
					$total_drives_duration += $data['drives_duration_time'];
				}
				
				if (in_array("stop_duration", $data_items))
				{
					$result .= '<td>'.$data['stops_duration'].'</td>';
					
					$total_stops_duration += $data['stops_duration_time'];
				}
				
				if (in_array("stop_count", $data_items))
				{
					$result .= '<td>'.count($data['stops']).'</td>';
					
					$total_stop_count += count($data['stops']);
				}
				
				if (in_array("top_speed", $data_items))
				{
					$result .= '<td>'.$data['top_speed'].' '.$la["UNIT_SPEED"].'</td>';
				}
				
				if (in_array("avg_speed", $data_items))
				{
					$result .= '<td>'.$data['avg_speed'].' '.$la["UNIT_SPEED"].'</td>';
				}
				
				if (in_array("overspeed_count", $data_items))
				{
					$result .= '<td>'.$overspeed_count.'</td>';
					
					$total_overspeed_count += $overspeed_count;
				}
				
				if (in_array("fuel_consumption", $data_items))
				{
					$result .= '<td>'.$data['fuel_consumption'].' '.$la["UNIT_CAPACITY"].'</td>';
					
					$total_fuel_consumption += $data['fuel_consumption'];
				}
				
				if (in_array("fuel_cost", $data_items))
				{
					$result .= '<td>'.$data['fuel_cost'].' '.$_SESSION["currency"].'</td>';
					
					$total_fuel_cost += $data['fuel_cost'];
				}
				
				if (in_array("engine_work", $data_items))
				{
					$result .= '<td>'.$data['engine_work'].'</td>';
					
					$total_engine_work += $data['engine_work_time'];
				}
				
				if (in_array("engine_idle", $data_items))
				{
					$result .= '<td>'.$data['engine_idle'].'</td>';
					
					$total_engine_idle += $data['engine_idle_time'];
				}
				
				if (in_array("odometer", $data_items))
				{
					$result .= '<td>'.$odometer.' '.$la["UNIT_DISTANCE"].'</td>';
					
					$total_odometer += $odometer;
				}
				
				if (in_array("engine_hours", $data_items))
				{
					$engine_hours = getObjectEngineHours($imei, true);
					
					$result .= '<td>'.$engine_hours.'</td>';
					
					$total_engine_hours += $engine_hours;
				}
				
				if (in_array("driver", $data_items))
				{
					$params = $data['route'][count($data['route'])-1][6];
					$driver = getObjectDriver($user_id, $imei, $params);
					if ($driver['driver_name'] == ''){$driver['driver_name'] = $la['NA'];}
						
					$result .= '<td>'.$driver['driver_name'].'</td>';
				}
				
				if (in_array("trailer", $data_items))
				{
					$params = $data['route'][count($data['route'])-1][6];
					$trailer = getObjectTrailer($user_id, $imei, $params);
					if ($trailer['trailer_name'] == ''){$trailer['trailer_name'] = $la['NA'];}
						
					$result .= '<td>'.$trailer['trailer_name'].'</td>';
				}
				
				if(count($data['daily_kmdata'])>0)
				{
					$result .= '<td align="center"><a href="#" class="pitcher" data-prod-cat="1"> + </a></td></tr>';
					if($format=="html")
					$result .= '<tr class="cat1" style="display:none" >';
					else
					$result .= '<tr class="cat1" >';

					$result .= '<td width="100%"  align="center" colspan="16">
						<table border="1" width="100%" ><tr  style="    font-weight: bold;padding: 2px;border: 1px solid #000000;color: white;background-color: #5C5D5C;"><td>'.$la['SINO'].'</td><td>'.$la['DATE'].'</td><td>'.$la['TAKENKM'].'</td></tr>';
					for ($idkm=0; $idkm<count($data['daily_kmdata']); $idkm++)
					{
						$result .= '<tr><td>'.($idkm+1).'</td><td>'.$data['daily_kmdata'][$idkm]["date"].'</td><td>'.sprintf("%01.2f", $data['daily_kmdata'][$idkm]["dailykm"]).'</td></tr>';
					}
					$result .= '</td></tr></table>';
				}
				else 
					$result .= '<td align="center">NA</td></tr>';
				
			}
			
			unset($data);
		}
		
		if (in_array("total", $data_items) && ($is_data == true))
		{
			$result .= '<tr align="center">';
			
			$result .= '<td></td>';
			
			if (in_array("route_start", $data_items))
			{
				$result .= '<td></td>';
			}
			
			if (in_array("route_end", $data_items))
			{
				$result .= '<td></td>';
			}
			
			if (in_array("route_length", $data_items))
			{
				$result .= '<td>'.$total_route_length.' '.$la["UNIT_DISTANCE"].'</td>';
			}
			
			if (in_array("move_duration", $data_items))
			{
				$result .= '<td>'.getTimeDetails($total_drives_duration, true).'</td>';
			}
			
			if (in_array("stop_duration", $data_items))
			{
				$result .= '<td>'.getTimeDetails($total_stops_duration, true).'</td>';
			}
			
			if (in_array("stop_count", $data_items))
			{
				$result .= '<td>'.$total_stop_count.'</td>';
			}
			
			if (in_array("top_speed", $data_items))
			{
				$result .= '<td></td>';
			}
			
			if (in_array("avg_speed", $data_items))
			{
				$result .= '<td></td>';
			}
			
			if (in_array("overspeed_count", $data_items))
			{
				$result .= '<td>'.$total_overspeed_count.'</td>';
			}
			
			if (in_array("fuel_consumption", $data_items))
			{
				$result .= '<td>'.$total_fuel_consumption.' '.$la["UNIT_CAPACITY"].'</td>';
			}
			
			if (in_array("fuel_cost", $data_items))
			{
				$result .= '<td>'.$total_fuel_cost.' '.$_SESSION["currency"].'</td>';
			}
			
			if (in_array("engine_work", $data_items))
			{
				$result .= '<td>'.getTimeDetails($total_engine_work, true).'</td>';
			}
			
			if (in_array("engine_idle", $data_items))
			{
				$result .= '<td>'.getTimeDetails($total_engine_idle, true).'</td>';
			}
			
			if (in_array("odometer", $data_items))
			{
				$result .= '<td>'.$total_odometer.' '.$la["UNIT_DISTANCE"].'</td>';
			}
			
			if (in_array("engine_hours", $data_items))
			{
				$result .= '<td>'.$total_engine_hours.' '.$la["UNIT_H"].'</td>';
			}
			
			if (in_array("driver", $data_items))
			{
				$result .= '<td></td>';
			}
			
			if (in_array("trailer", $data_items))
			{
				$result .= '<td></td>';
			}
			
			$result .= '</tr>';
		}

		$result .= '</table>';
		
		return $result;
		
	}
?>