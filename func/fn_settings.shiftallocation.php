<? 
	session_start();
	include ('../init.php');
	include ('fn_common.php');
	include ('../tools/gc_func.php');
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

	if(@$_POST['cmd'] == 'delete_shift_allocation')
	{
		$dat = $_POST["field_id"];
		
		$q = "DELETE FROM `schedule_timev` WHERE `user_id`='".$user_id."' AND `dat`='".$dat."'";
		$r = mysqli_query($ms, $q);
		//echo $q;
		echo 'OK';
		die;
	}
	if(@$_POST['cmd'] == 'delete_selected_shift_allocation')
	{
		$items = $_POST["items"];
		for ($i = 0; $i < count($items); ++$i)
		{
			$item = $items[$i];
			
			$q = "DELETE FROM `schedule_timev` WHERE `user_id`='".$user_id."' AND `dat`='".$item."'";
			$r = mysqli_query($ms, $q);
		}
		
		echo 'OK';
		die;
	}
	
	if(@$_POST['cmd'] == 'save_shift_allocation')
	{
		$curdateindian = $_POST["date"];
		$crewtype = $_POST["crew"];

		$q = "SELECT distinct crew,dat FROM `schedule_timev` WHERE user_id='".$user_id."' and dat='".$curdateindian."' order by dat desc";
		$r = mysqli_query($ms, $q);
		if(mysqli_num_rows($r)<=0)
		{
			$crewtype_tme=$gsValues['POST_RFID'][$user_id]["crew_time"];
			$crewarray=str_split($crewtype);
			for($icar=0;$icar<count($crewarray);$icar++)
			{
				if($icar==2)
				{
					$curdateindian_next=date('Y-m-d', strtotime('+1 day', strtotime($curdateindian)));
					$qassin="insert into schedule_timev(user_id,UNIT_NAME,START_TIME,END_TIME,DAT,crew) values
 									('".$user_id."','".$crewarray[$icar]."','".$curdateindian." ".$crewtype_tme[$icar]["start"]."'
 									,'".$curdateindian_next." ".$crewtype_tme[$icar]["end"]."','".$curdateindian."','".$crewtype."')";
					$rassin = mysqli_query($ms, $qassin);
				}
				else
				{
					$qassin="insert into schedule_timev(user_id,UNIT_NAME,START_TIME,END_TIME,DAT,crew) values
 									('".$user_id."','".$crewarray[$icar]."','".$curdateindian." ".$crewtype_tme[$icar]["start"]."'
 									,'".$curdateindian." ".$crewtype_tme[$icar]["end"]."','".$curdateindian."','".$crewtype."')";
					$rassin = mysqli_query($ms, $qassin);
				}
			}
			echo 'OK';
		}
		else
		echo 'Please delete existing records for selected date.';
	}
	
	if(@$_GET['cmd'] == 'load_shift_allocation')
	{ 
		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
	
		if(!$sidx) $sidx =1;

		$q = "SELECT distinct  crew,dat FROM `schedule_timev` WHERE user_id='".$user_id."' order by dat desc";
		$r = mysqli_query($ms, $q);
		 
		$count = mysqli_num_rows($r);
		
		$response = new stdClass();
		$response->page = 1;
		//$response->total = $count;
		$response->records = $count;
		if ($r)
		{
			$i=0;
			while($row = mysqli_fetch_array($r)) {
				
				$date = $row["dat"];
				$crew = $row["crew"];
				//$crew = (str_replace(',',"",$row["crew"]));
							
				// set modify buttons
				//$modify = '<a href="#" onclick="settingsShiftAllocation(\''.$date.'\');" title="'.$la['EDIT'].'"><img src="theme/images/edit.svg" />';
				$modify = '</a><a href="#" onclick="settingsShiftAllocationDelete(\''.$date.'\');" title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>';
				// set row
				$response->rows[$i]['id']=$date;
				$response->rows[$i]['cell']=array($date,$crew,$modify);
				$i++;
			}
		}
		
		header('Content-type: application/json');
		echo json_encode($response);
		die;
	}
	
	
if(@$_GET['cmd'] == 'load_sos_alert')
	{ 		
		$sno=0;
		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
		
		 $start = 0; //temp solution correct this @nandha
		
		if(!$sidx) $sidx =1;
	            // get records number			
		// if ($_SESSION["privileges"] == 'subuser')
		// {
		// 	$qT= "select gued.event_id,gued.user_id,gued.event_desc,gued.dt_tracker,gued.lat,gued.lng,go.name,ud.driver_name,ud.driver_phone,gued.imei,gup.group_name,go.model from gs_user_events_data gued join gs_objects go on go.imei=gued.imei join gs_user_objects uo on uo.imei=go.imei and uo.imei in (".$_SESSION["privileges_imei"].") left join gs_user_object_drivers ud on ud.driver_id=uo.driver_id left join gs_user_object_groups gup on gup.group_id=uo.group_id and gup.user_id=uo.user_id  where type='".$_GET['event']."'and gued.user_id=uo.user_id and gued.user_id='".$user_id."' GROUP BY dt_tracker order by gued.dt_tracker desc ";
		// }
		// else
		// {			
		// 	$qT= "select gued.event_id,gued.user_id,gued.event_desc,gued.dt_tracker,gued.lat,gued.lng,go.name,ud.driver_name,ud.driver_phone,gued.imei,gup.group_name,go.model from gs_user_events_data gued join gs_objects go on go.imei=gued.imei join gs_user_objects uo on uo.imei=go.imei left join gs_user_object_drivers ud on ud.driver_id=uo.driver_id left join gs_user_object_groups gup on gup.group_id=uo.group_id and gup.user_id=uo.user_id  where type='".$_GET['event']."' and gued.user_id=uo.user_id and gued.user_id='".$user_id."' GROUP BY dt_tracker order by gued.dt_tracker desc ";
		// }
		if($_SESSION["privileges"] == 'subuser'){
			$qT= "select gued.event_id,gued.user_id,gued.event_desc,gued.dt_tracker,gued.lat,gued.lng,go.name,ud.driver_name,ud.driver_phone,gued.imei,gup.group_name,go.model from gs_user_events_data gued join gs_objects go on go.imei=gued.imei join gs_user_objects uo on uo.imei=go.imei and uo.imei in (".$_SESSION["privileges_imei"].") left join gs_user_object_drivers ud on ud.driver_id=uo.driver_id left join gs_user_object_groups gup on gup.group_id=uo.group_id and gup.user_id=uo.user_id  where type='".$_GET['event']."'and gued.user_id=uo.user_id and gued.user_id='".$user_id."' GROUP BY dt_tracker order by gued.dt_tracker desc ";
		}else{
			$qT="SELECT a.event_id,a.user_id,a.event_desc,a.dt_tracker,a.lat,a.lng,c.name,d.driver_name,d.driver_phone,a.imei,f.group_name,c.model FROM gs_user_events_data a LEFT JOIN gs_user_objects b ON a.imei=b.imei LEFT JOIN gs_objects c ON a.imei=c.imei LEFT JOIN gs_user_object_drivers d ON b.driver_id=d.driver_id LEFT JOIN gs_user_object_groups f ON b.group_id=f.group_id WHERE a.type='".$_GET['event']."' AND a.user_id=b.user_id and b.user_id='".$user_id."' AND a.event_id NOT IN (SELECT event_id FROM event_action e) GROUP BY a.dt_tracker order by a.dt_tracker ";
		}
		//$r1 = mysqli_query($ms, $q1);
		$r1 = mysqli_query($ms, $qT);

		$count1 = mysqli_num_rows($r1);
		
		if ($count1 > 0)
		{
			$total_pages = ceil($count1/$limit);
		}
		else
		{
			$total_pages = 1;
		}
		
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit; // do not put $limit*($page - 1)
		
		// if ($_SESSION["privileges"] == 'subuser')
		// {
		// 	$q= "select gued.event_id,gued.user_id,gued.event_desc,gued.dt_tracker,gued.lat,gued.lng,go.name,ud.driver_name,ud.driver_phone,gued.imei,gup.group_name,go.model from gs_user_events_data gued join gs_objects go on go.imei=gued.imei join gs_user_objects uo on uo.imei=go.imei and uo.imei in (".$_SESSION["privileges_imei"].") left join gs_user_object_drivers ud on ud.driver_id=uo.driver_id left join gs_user_object_groups gup on gup.group_id=uo.group_id and gup.user_id=uo.user_id  where type='".$_GET['event']."'and gued.user_id=uo.user_id and gued.user_id='".$user_id."' GROUP BY dt_tracker order by gued.dt_tracker desc LIMIT $start, $limit";
		// }
		// else
		// {			
		// 	$q= "select gued.event_id,gued.user_id,gued.event_desc,gued.dt_tracker,gued.lat,gued.lng,go.name,ud.driver_name,ud.driver_phone,gued.imei,gup.group_name,go.model from gs_user_events_data gued join gs_objects go on go.imei=gued.imei join gs_user_objects uo on uo.imei=go.imei left join gs_user_object_drivers ud on ud.driver_id=uo.driver_id left join gs_user_object_groups gup on gup.group_id=uo.group_id and gup.user_id=uo.user_id  where type='".$_GET['event']."' and gued.user_id=uo.user_id and gued.user_id='".$user_id."' GROUP BY dt_tracker order by gued.dt_tracker desc LIMIT $start, $limit";
		// }

		if($_SESSION["privileges"] == 'subuser'){
			$q= "select gued.event_id,gued.user_id,gued.event_desc,gued.dt_tracker,gued.lat,gued.lng,go.name,ud.driver_name,ud.driver_phone,gued.imei,gup.group_name,go.model from gs_user_events_data gued join gs_objects go on go.imei=gued.imei join gs_user_objects uo on uo.imei=go.imei and uo.imei in (".$_SESSION["privileges_imei"].") left join gs_user_object_drivers ud on ud.driver_id=uo.driver_id left join gs_user_object_groups gup on gup.group_id=uo.group_id and gup.user_id=uo.user_id  where type='".$_GET['event']."'and gued.user_id=uo.user_id and gued.user_id='".$user_id."' GROUP BY dt_tracker order by gued.dt_tracker desc LIMIT $start, $limit";
		}else{
			$q="SELECT a.event_id,a.user_id,a.event_desc,a.dt_tracker,a.lat,a.lng,c.name,d.driver_name,d.driver_phone,a.imei,f.group_name,c.model FROM gs_user_events_data a LEFT JOIN gs_user_objects b ON a.imei=b.imei LEFT JOIN gs_objects c ON a.imei=c.imei LEFT JOIN gs_user_object_drivers d ON b.driver_id=d.driver_id LEFT JOIN gs_user_object_groups f ON b.group_id=f.group_id WHERE a.type='".$_GET['event']."' AND a.user_id=b.user_id and b.user_id='".$user_id."' AND a.event_id NOT IN (SELECT event_id FROM event_action e) GROUP BY a.dt_tracker order by a.dt_tracker ";
		}
		$r = mysqli_query($ms, $q);
		 
		$count = mysqli_num_rows($r);

		$response = new stdClass();
		$response->page = $page;
		$response->total = $total_pages;
		$response->records = $count1;			
		
			$i=0;
			while($row = mysqli_fetch_array($r)) {
					$sno=$i;
					$imei = $row["imei"];
					$eventid = $row["event_id"];
					$userid = $row["user_id"];
					$name = $row["name"];
					$eventdesc = $row["event_desc"];				
					$drivername = $row["driver_name"];
					$drivernumber = $row["driver_phone"];
					$date = convUserTimezone($row["dt_tracker"]);
					$groupname = $row["group_name"];
					$transportmodel = $row["model"];
					$lat=$row["lat"];
					$lng=$row["lng"];
					$show_coordinates="true";
					$show_addresses="false";
					$zones_addresses="false";
					$modify = $i;
					$modify1 = $i;
					$modify2 = $i;
					// $address=reportsGetPossition($lat, $lng, $show_coordinates, $show_addresses, $zones_addresses);
					$address='<a href="http://maps.google.com/maps?q='.$lat.','.$lng.'&t=m" target="_blank">'.$lat.' &deg;, '.$lng.' &deg;</a>';

					$response->rows[$i]['id']=$i;
					$response->rows[$i]['cell']=array($sno,$name,$groupname,$transportmodel,$eventdesc,$date,$drivername,$drivernumber,$address,$eventid,$modify,$modify1,$modify2,$imei,$userid);
					$i++;
				
			}

		header('Content-type: application/json');
		echo json_encode($response);
		die;

	}


function reportsGetPossition($lat, $lng, $show_coordinates, $show_addresses, $zones_addresses)
	{
		global $ms, $user_id, $zones_addr, $zones_addr_loaded;
		
		$lat = sprintf('%0.6f', $lat);
		$lng = sprintf('%0.6f', $lng);
		
		if ($show_coordinates == 'true')
		{
			$position = '<a href="http://maps.google.com/maps?q='.$lat.','.$lng.'&t=m" target="_blank">'.$lat.' &deg;, '.$lng.' &deg;</a>';	
		}
		else
		{
			$position = '';
		}
		
		if ($zones_addresses == 'true')
		{
			if ($zones_addr_loaded == false)
			{
				$q = "SELECT * FROM `gs_user_zones` WHERE `user_id`='".$user_id."'";
				$r = mysqli_query($ms, $q);
				
				while($row=mysqli_fetch_array($r))
				{
					$zones_addr[] = array($row['zone_id'],$row['zone_name'], $row['zone_vertices']);	
				}
				
				$zones_addr_loaded = true;
			}
			
			for ($j=0; $j<count($zones_addr); ++$j)
			{
				$zone_name = $zones_addr[$j][1];
				$zone_vertices = $zones_addr[$j][2];
				
				$isPointInPolygon = isPointInPolygon($zone_vertices, $lat, $lng);
				
				if ($isPointInPolygon)
				{
					if ($position == '')
					{
						$position = $zone_name;	
					}
					else
					{
						$position .= ' - '.$zone_name;	
					}
					
					return $position;
				}
			}
		}
		
		if ($show_addresses == 'true')
		{			
			$address = geocoderGetAddress($lat, $lng);
			
			if ($address != '')
			{
				if ($position == '')
				{
					$position = $address;	
				}
				else
				{
					$position .= ' - '.$address;	
				}	
			}
		}
		
		return $position;
	}
	
?>