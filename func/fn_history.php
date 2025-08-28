<?
	set_time_limit(300);
	
	session_start();	
	include ('../init.php');
	include ('fn_common.php');
	include ('fn_route.php');
	checkUserSession();
	
	loadLanguage($_SESSION["language"], $_SESSION["units"]);
	
	// check privileges
	if ($_SESSION["privileges"] == 'subuser')
	{
		$user_id = $_SESSION["manager_id"];
	}
	else
	{
		$user_id = $_SESSION["user_id"];
	}
	
	if(@$_POST['cmd'] == 'load_route_data')
	{		
		$imei = $_POST['imei'];
		$dtf = $_POST['dtf'];
		$dtt = $_POST['dtt'];
		$min_stop_duration = $_POST['min_stop_duration'];
		
		if (!checkUserToObjectPrivileges($user_id, $imei))
		{
			die;
		}
		
		$result = getRoute($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $min_stop_duration, true,false,true,50000);
		
		mysqli_close($ms);
		
		ob_start();
		header('Content-type: application/json');
		echo json_encode($result);
		header("Connection: close");
		header("Content-length: " . (string)ob_get_length());
		ob_end_flush();
		die;
	}
	
	if(@$_POST['cmd'] == 'delete_selected_msgs')
	{
		if($_SESSION["obj_history_clear"] == 'true')
		{
			$imei = $_POST["imei"];
			$items = $_POST["items"];
					
			for ($i = 0; $i < count($items); ++$i)
			{
				$item = $items[$i];
				
				$q = "DELETE FROM `gs_object_data_".$imei."` WHERE `dt_tracker`='".$item."'";
				$r = mysqli_query($ms, $q);
			}
			
			echo 'OK';
		}
		
		die;
	}
	
	if(@$_GET['cmd'] == 'load_msg_list_empty')
	{
		$response = new stdClass();
		$response->page = 1;
		$response->total = 1;
		$response->records = 0;
		
		header('Content-type: application/json');
		echo json_encode($response);
		die;
	}
	
	if(@$_GET['cmd'] == 'load_msg_list')
	{
		$imei = $_GET['imei'];
		$dtf = convUserUTCTimezone($_GET['dtf']);
		$dtt = convUserUTCTimezone($_GET['dtt']);
		
		if (!checkUserToObjectPrivileges($user_id, $imei))
		{
			die;
		}
		
		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
		
		if(!$sidx) $sidx =1;
		
		// get records number
		$q = "SELECT DISTINCT	dt_server,
					dt_tracker,
					lat,
					lng,
					altitude,
					angle,
					speed,
					params
					FROM `gs_object_data_".$imei."` WHERE dt_tracker BETWEEN '".$dtf."' AND '".$dtt."'";
					
		$r = mysqli_query($ms, $q);
		$count = mysqli_num_rows($r);
		
		if ($count > 0)
		{
			$total_pages = ceil($count/$limit);
		}
		else
		{
			$total_pages = 1;
		}
		
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit; // do not put $limit*($page - 1)
		
		$q .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		$r = mysqli_query($ms, $q);
		
		$response = new stdClass();
		$response->page = $page;
		$response->total = $total_pages;
		$response->records = $count;
		
		if ($r)
		{
			$i=0;
			while($row = mysqli_fetch_array($r))
			{
				$dt_server = convUserTimezone($row['dt_server']);
				$dt_tracker = convUserTimezone($row['dt_tracker']);
				
				$row['lat'] = sprintf('%0.6f', $row['lat']);
				$row['lng'] = sprintf('%0.6f', $row['lng']);
				
				$row['altitude'] = convAltitudeUnits($row['altitude'], 'km', $_SESSION["unit_distance"]).' '.$la["UNIT_HEIGHT"];
				$row['speed'] = convSpeedUnits($row['speed'], 'km', $_SESSION["unit_distance"]).' '.$la["UNIT_SPEED"];
				
				if ($row['params'] == '')
				{
					$row['params'] = '';
				}
				else
				{
					//$row['params'] = json_decode($row['params'],true);
					$row['params'] = paramsToArray($row['params']);
					
					$arr_params = array();
					
					foreach ($row['params'] as $key => $value)
					{
						array_push($arr_params, $key.'='.$value);
					}
					
					$row['params'] = implode(', ', $arr_params);
				}
				
				//$response->rows[$i]['id'] = $i;
				if($row['altitude']<10000){
				$response->rows[$i]['id']=$row['dt_tracker'];
				$response->rows[$i]['cell']=array($dt_tracker, $dt_server, $row['lat'], $row['lng'], $row['altitude'], $row['angle'], $row['speed'], $row['params']);
				$i++;
				}
			}
		}
		
		header('Content-type: application/json');
		echo json_encode($response);
		die;
	}



	//code done by vetrivelht



	if(@$_GET['cmd'] == 'load_historytrack')
	{
		try
		{
			$imei = $_GET['imei'];
			$dtf = convUserUTCTimezone($_GET['dtf']);
			$dtt = convUserUTCTimezone($_GET['dtt']);

			if (!checkUserToObjectPrivileges($user_id, $imei))
			{
				die;
			}

			$page = $_GET['page']; // get the requested page
			$limit = $_GET['rows']; // get how many rows we want to have into the grid
			$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
			$sord = $_GET['sord']; // get the direction

			if(!$sidx) $sidx =1;

			$resultodotype = mysqli_query($ms,"SELECT odometer_type FROM gs_objects where imei='".$imei."' ");
			$rowodotype = mysqli_fetch_array($resultodotype);

			$q = "";

			//if($rowodotype['odometer_type'] =="odo")
			{
				$q ="SELECT DISTINCT gut.name,gtd.dt_tracker,gtd.dt_server,gtd.lat,gtd.lng,gtd.altitude,gtd.angle,
			gtd.speed,gtd.params,gtd.mileage  FROM gs_object_data_".$imei."  gtd
			inner join gs_objects  gut on gut.imei='".$imei."' and gtd.dt_tracker BETWEEN '".$dtf."' AND '".$dtt."' ";
			}

			$r = mysqli_query($ms,$q);

			$count = mysqli_num_rows($r);



			if( $count >0 )
			{
				$total_pages = ceil($count/$limit);
			}
			else
			{
				$total_pages = 1;
			}

			if ($page > $total_pages) $page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)

			//if($rowodotype['odometer_type'] =="odo")
			{
				$q = "SELECT DISTINCT gut.name,gtd.dt_tracker,gtd.dt_server,gtd.lat,gtd.lng,gtd.altitude,gtd.angle,
			gtd.speed,gtd.params,gtd.mileage  FROM gs_object_data_".$imei."  gtd
			inner join gs_objects gut on gut.imei='".$imei."'  and gtd.dt_tracker BETWEEN '".$dtf."' AND '".$dtt."'  ORDER BY $sidx $sord LIMIT $start, $limit";
			}

			$result = mysqli_query($ms,$q);

			$responce = new stdClass();
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;

			if ($count > 0)
			{
				$qd="select param,units,SPLIT_STR(formula, '|',2) mul from gs_object_sensors where imei='".$imei."'";
				$qd="select param,units,formula mul from gs_object_sensors where imei='".$imei."'";
				$resultd = mysqli_query($ms,$qd);
				$fuel1l="";$fuel2l="";$fuel3l="";$fuel4l="";
				if($resultd){
					while($rowd = mysqli_fetch_array($resultd,MYSQLI_ASSOC))
					{
						$paramd=$rowd["param"];

						if($paramd=="fuel1")
						{
							if($rowd['mul']!="" )
							{
								$fuel1l=$rowd['mul'];
							}
						}

						if($paramd=="fuel2")
						{
							if($rowd['mul']!="" )
							{
								$fuel2l=$rowd['mul'];
							}
						}

						if($paramd=="fuel3")
						{
							if($rowd['mul']!="" )
							{
								$fuel3l=$rowd['mul'];
							}
						}

						if($paramd=="fuel4")
						{
							if($rowd['mul']!="" )
							{
								$fuel4l=$rowd['mul'];
							}
						}

					}
				}
				
				$ac_param;
				$sensor_list=getSensors($imei);
				for($is=0;$is<count($sensor_list);$is++)
				{
					if($sensor_list[$is]["name"]=="A/C")
					{
						$ac_param=$sensor_list[$is];
						break;
					}
				}
				
					
				$i=0;
				while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
				{


					$dt_tracker="";
					$fuel1p="";$gps="";$accig="";$airc="";$tmp1="";$fuel2p="";$fuel3p="";$fuel4p="";
					$mul=0;$mul2=0;$mul3=0;$mul4=0;
					$temp2="";$temp3="";
					$dt_tracker = convUserTimezone($row['dt_tracker']);


					$dt_server =convUserTimezone($row['dt_server']);

					$params=$row['params'];
					$modify = '<a href="#" onclick="historyRouteMsgDelete(\''.$row['dt_tracker'].'\');" title="'.$la['DELETE'].'"><img src="img/ico/trash.png" /></a>';

					if ($row['params'] == '')
					{
						$row['params'] = '';
					}
					else
					{


							
						$paramlist3 ="";

						if( ($params!=null && $params!="" && $params !="1" && !empty($params) && $params!='') )
						{
								
							//$paramlist3 = explode("|",$params);
							$paramlist3 = paramsToArray($params);

						}
						else
						{
								
							//get previouse param data
							$fl="1";
							$paramlist3 ="";
							$paramlist33=get_lastparamdata($imei,$row['dt_tracker']);

							$params=$paramlist33['params'];
								
							//$paramlist3 = explode("|",$paramlist33['params']);
							$paramlist3 = paramsToArray($paramlist33['params']);
								
							$mileage=$paramlist33['mileage'];
						}

						if(isset($paramlist3["odo"]))
						{
							$mileage= $paramlist3["odo"] ;
						}
						if(isset($paramlist3["fuel1"]))
						{
							$fuel1p= $paramlist3["fuel1"] ;
						}
						if(isset($paramlist3["fuel2"]))
						{
							$fuel2p= $paramlist3["fuel2"] ;
						}
						if(isset($paramlist3["fuel3"]))
						{
							$fuel3p= $paramlist3["fuel3"] ;
						}
						if(isset($paramlist3["fuel4"]))
						{
							$fuel4p= $paramlist3["fuel4"] ;
						}
						
						//$tmp1 = getSensorValue($paramlist3, "temp1");

						if(isset($paramlist3["temp1"]))
						{
							$tmp1= $paramlist3["temp1"] ;
						}

						if(isset($paramlist3["temp2"]))
						{
							$temp2= $paramlist3["temp2"] ;
						}

						if(isset($paramlist3["temp3"]))
						{
							$temp3= $paramlist3["temp3"] ;
						}

						if(isset($paramlist3["gpsL"]))
						{
							if($paramlist3["gpsL"]==0)
							{$gps="OFF";}else{$gps="ON";}
						}
						else
						{
							$gps="NA";
						}
						
						if(isset($paramlist3["di1"]))
						{
							if($paramlist3["di1"]==0)
							{$airc="OFF";}else{$airc="ON";}
						}

						if(isset($ac_param))
						{
							$airc = getSensorValue($paramlist3, $ac_param);
							$airc=$airc["value_full"];
						}
						else 
						{
							$airc='-';
						}
						
						if(isset($paramlist3["acc"]))
						{
							if($paramlist3["acc"]==0)
							{$accig="OFF";}else{$accig="ON";}
						}
							
						if($fuel1l!=""  && $fuel1p !="0")
						{
							$mul = fngetFuelvalueHT($fuel1p,$fuel1l);
						}

						if($fuel2l!=""  && $fuel2p !="0")
						{
							$mul2 = fngetFuelvalueHT($fuel2p,$fuel2l);
						}

						if($fuel3l!=""  && $fuel3p !="0")
						{
							$mul3 = fngetFuelvalueHT($fuel3p,$fuel3l);
						}

						if($fuel4l!=""  && $fuel4p !="0")
						{
							$mul4 = fngetFuelvalueHT($fuel4p,$fuel4l);
						}

					}
					
					if($row['altitude']<10000){
					$lat = '<a href="http://maps.google.com/maps?q='.$row['lat'].','.$row['lng'].'&t=m" target="_blank">'.$row['lat'].' &deg;</a>';
					$lng = '<a href="http://maps.google.com/maps?q='.$row['lat'].','.$row['lng'].'&t=m" target="_blank">'.$row['lng'].' &deg;</a>';
					$mileage=$row['mileage'];
					$responce->rows[$i]['id'] = $i;
					$responce->rows[$i]['cell']=array($row['name'],$dt_tracker, $dt_server, $row['lat'], $row['lng'], $row['altitude'], $row['angle'], $row['speed'], $mileage,$gps, $accig, $airc,$fuel1p, $mul,  $tmp1);
					$i++;
					}
				}
			}

			header('Content-type: application/json');
			echo json_encode($responce);
			die;

		}
		catch (Exception $e) {

			echo 'Caught exception: ',  $e->getMessage(), "\n";

		}
	}


	function get_lastparamdata($imei,$dt_tracker)
	{
			
		$q = "SELECT mileage,params FROM gs_tracker_data_".$imei."  WHERE  ( params!='' and params!='1' and params<>'' ) and mileage is not null  and params is not null   and dt_tracker > '". convUserUTCTimezone($dt_tracker)."'  limit 1  ";
		$r = mysqli_query($ms,$q) or die(mysql_error());
		$row = mysqli_fetch_array($r);

		return $row;
	}
	// code end vetrivel

	
?>
