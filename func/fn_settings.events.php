<? 
	session_start();
	include ('../init.php');
	include ('fn_common.php');
	checkUserSession();
	
	loadLanguage($_SESSION["language"], $_SESSION["units"]);
	
	if(@$_POST['cmd'] == 'load_event_data')
	{
		$user_id = $_SESSION["user_id"];
		
		$q = "SELECT * FROM `gs_user_events` WHERE `user_id`='".$user_id."' ORDER BY `name` ASC";
		$r = mysqli_query($ms, $q);
		
		$result = array();
		
		while($row = mysqli_fetch_array($r))
		{
			$event_id = $row['event_id'];
			
			$day_time = json_decode($row['day_time'], true);
			
			if (($row['type'] == 'param') || ($row['type'] == 'sensor'))
			{
				$row['checked_value'] = json_decode($row['checked_value'], true);
				
				if ($row['checked_value'] == null)
				{
					$row['checked_value'] = array();	
				}
			}
			
			$result[$event_id] = array(	'type' => $row['type'],
							'name' => $row['name'],
							'active' => $row['active'],
							'duration_from_last_event' => $row['duration_from_last_event'],
							'duration_from_last_event_minutes' => $row['duration_from_last_event_minutes'],
							'week_days' => $row['week_days'],
							'day_time' => $day_time,
							'imei' => $row['imei'],
							'checked_value' => $row['checked_value'],
							'route_trigger' => $row['route_trigger'],
							'zone_trigger' => $row['zone_trigger'],
							'routes' => $row['routes'],
							'zones' => $row['zones'],
							'notify_system' => $row['notify_system'],
							'notify_email' => $row['notify_email'],
							'notify_email_address' => $row['notify_email_address'],
							'notify_sms' => $row['notify_sms'],
							'notify_sms_number' => $row['notify_sms_number'],							
							'email_template_id' => $row['email_template_id'],
							'sms_template_id' => $row['sms_template_id'],
							'notify_arrow' => $row['notify_arrow'],
							'notify_arrow_color' => $row['notify_arrow_color'],
							'notify_ohc' => $row['notify_ohc'],
							'notify_ohc_color' => $row['notify_ohc_color'],
							'cmd_send' => $row['cmd_send'],
							'cmd_gateway' => $row['cmd_gateway'],
							'cmd_type' => $row['cmd_type'],
							'cmd_string' => $row['cmd_string'],
							'workhour' => $row['timing']
							);
		}
		echo json_encode($result);
		die;
	}
	
	if(@$_GET['cmd'] == 'load_event_list')
	{ 
		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
		
		$user_id = $_SESSION["user_id"];
		
		if(!$sidx) $sidx =1;
		
		// get records number
		$q = "SELECT * FROM `gs_user_events` WHERE `user_id`='".$user_id."'";
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
		
		$q = "SELECT * FROM `gs_user_events` WHERE `user_id`='".$user_id."' ORDER BY $sidx $sord LIMIT $start, $limit";
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
				$event_id = $row['event_id'];
				$name = $row['name'];
				$object=count(explode(',', $row['imei']));
				
				if ($row['active'] == 'true')
				{
					$active = '<img src="theme/images/tick-green.svg" />';
				}
				else
				{
					$active = '<img src="theme/images/remove-red.svg" style="width:12px;" />';
				}
				
				$notify_system = explode(",", $row['notify_system']);
				
				if (@$notify_system[0] == 'true')
				{
					$notify_system = '<img src="theme/images/tick-green.svg" />';
				}
				else
				{
					$notify_system = '<img src="theme/images/remove-red.svg" style="width:12px;" />';
				}
				
				if ($row['notify_email'] == 'true')
				{
					$notify_email = '<img src="theme/images/tick-green.svg" />';
				}
				else
				{
					$notify_email = '<img src="theme/images/remove-red.svg" style="width:12px;" />';
				}
				
				if ($row['notify_sms'] == 'true')
				{
					$notify_sms = '<img src="theme/images/tick-green.svg" />';
				}
				else
				{
					$notify_sms = '<img src="theme/images/remove-red.svg" style="width:12px;" />';
				}
				
				// set modify buttons
				$modify='';
				if(checkSettingsPrivileges('events','edit')==true){
					$modify .= '<a href="#" onclick="settingsEventProperties(\''.$event_id.'\');" title="'.$la['EDIT'].'"><img src="theme/images/edit.svg" />';
				}
				if(checkSettingsPrivileges('events','delete')==true){
					$modify .= '</a><a href="#" onclick="settingsEventDelete(\''.$event_id.'\');"  title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>';
				}
				// set row
				$response->rows[$i]['id']=$event_id;
				$response->rows[$i]['cell']=array($name,$active,$notify_system,$object,$notify_email,$notify_sms,$modify);
				$i++;
			}
		}
		
		header('Content-type: application/json');
		echo json_encode($response);
		die;
	}
	
	if(@$_POST['cmd'] == 'delete_event')
	{
		if(checkSettingsPrivileges('events','delete')!=true){
			echo 'NO_PERMISSION';
			die;
		}

		$event_id = $_POST["event_id"];
		$user_id = $_SESSION["user_id"];
		
		$q = "DELETE FROM `gs_user_events` WHERE `event_id`='".$event_id."' AND `user_id`='".$user_id."'";
		$r = mysqli_query($ms, $q);
		
		$q = "DELETE FROM `gs_user_events_status` WHERE `event_id`='".$event_id."'";
		$r = mysqli_query($ms, $q);
		
		echo 'OK';
		die;
	}
	
	if(@$_POST['cmd'] == 'delete_selected_events')
	{
		if(checkSettingsPrivileges('events','delete')!=true){
			echo 'NO_PERMISSION';
			die;
		}
		$items = $_POST["items"];
		$user_id = $_SESSION["user_id"];
				
		for ($i = 0; $i < count($items); ++$i)
		{
			$item = $items[$i];
			
			
			$q = "DELETE FROM `gs_user_events` WHERE `event_id`='".$item."' AND `user_id`='".$user_id."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_user_events_status` WHERE `event_id`='".$item."'";
			$r = mysqli_query($ms, $q);
		}
		
		echo 'OK';
		die;
	}
	
	if(@$_POST['cmd'] == 'save_event')
	{
		$event_id = $_POST["event_id"];
		$user_id = $_SESSION["user_id"];
		$type = $_POST["type"];
		$name = $_POST["name"];
		$active = $_POST["active"];
		$duration_from_last_event = $_POST["duration_from_last_event"];
		$duration_from_last_event_minutes = $_POST["duration_from_last_event_minutes"];
		$week_days = $_POST["week_days"];
		$day_time = $_POST["day_time"];
		$imei = $_POST["imei"];
		$checked_value = $_POST["checked_value"];
		$route_trigger = $_POST["route_trigger"];
		$zone_trigger = $_POST["zone_trigger"];
		$routes = $_POST["routes"];
		$zones = $_POST["zones"];
		$notify_system = $_POST["notify_system"];
		$notify_email = $_POST["notify_email"];
		$notify_email_address = $_POST["notify_email_address"];
		$notify_sms = $_POST["notify_sms"];
		$notify_sms_number = $_POST["notify_sms_number"];
		$email_template_id = $_POST["email_template_id"];
		$sms_template_id = $_POST["sms_template_id"];
		$notify_arrow = $_POST["notify_arrow"];
		$notify_arrow_color = $_POST["notify_arrow_color"];
		$notify_ohc = $_POST["notify_ohc"];
		$notify_ohc_color = $_POST["notify_ohc_color"];
		$cmd_send = $_POST["cmd_send"];
		$cmd_gateway = $_POST["cmd_gateway"];
		$cmd_type = $_POST["cmd_type"];
		$cmd_string = $_POST["cmd_string"];
		$timing = $_POST["timing"];
		
		if ($event_id == 'false' && checkSettingsPrivileges('events','add')==true)
		{
			$q = "INSERT INTO `gs_user_events` (`user_id`,
								`type`,
								`name`,
								`active`,
								`duration_from_last_event`,
								`duration_from_last_event_minutes`,
								`week_days`,
								`day_time`,
								`imei`,
								`checked_value`,
								`route_trigger`,
								`zone_trigger`,
								`routes`,
								`zones`,
								`notify_system`,
								`notify_email`,
								`notify_email_address`,
								`notify_sms`,
								`notify_sms_number`,
								`email_template_id`,
								`sms_template_id`,
								`notify_arrow`,
								`notify_arrow_color`,
								`notify_ohc`,
								`notify_ohc_color`,
								`cmd_send`,
								`cmd_gateway`,
								`cmd_type`,
								`cmd_string`,
								timing
								) VALUES (
								'".$user_id."',
								'".$type."',
								'".$name."',
								'".$active."',
								'".$duration_from_last_event."',
								'".$duration_from_last_event_minutes."',
								'".$week_days."',
								'".$day_time."',
								'".$imei."',
								'".$checked_value."',
								'".$route_trigger."',
								'".$zone_trigger."',
								'".$routes."',
								'".$zones."',
								'".$notify_system."',
								'".$notify_email."',
								'".$notify_email_address."',
								'".$notify_sms."',												
								'".$notify_sms_number."',
								'".$email_template_id."',
								'".$sms_template_id."',
								'".$notify_arrow."',
								'".$notify_arrow_color."',
								'".$notify_ohc."',
								'".$notify_ohc_color."',
								'".$cmd_send."',
								'".$cmd_gateway."',
								'".$cmd_type."',
								'".$cmd_string."',
								'".$timing."')";			
		}
		else if($event_id != 'false' && checkSettingsPrivileges('events','edit')==true)
		{
			$q = "UPDATE `gs_user_events` SET 	`type`='".$type."', 
								`name`='".$name."',
								`active`='".$active."',
								`duration_from_last_event`='".$duration_from_last_event."',
								`duration_from_last_event_minutes`='".$duration_from_last_event_minutes."',
								`week_days`='".$week_days."',
								`day_time`='".$day_time."',
								`imei`='".$imei."',
								`checked_value`='".$checked_value."',
								`route_trigger`='".$route_trigger."',
								`zone_trigger`='".$zone_trigger."',
								`routes`='".$routes."',
								`zones`='".$zones."',
								`notify_system`='".$notify_system."',
								`notify_email`='".$notify_email."',
								`notify_email_address`='".$notify_email_address."',
								`notify_sms`='".$notify_sms."',
								`notify_sms_number`='".$notify_sms_number."',
								`email_template_id`='".$email_template_id."',
								`sms_template_id`='".$sms_template_id."',
								`notify_arrow`='".$notify_arrow."',
								`notify_arrow_color`='".$notify_arrow_color."',
								`notify_ohc`='".$notify_ohc."',
								`notify_ohc_color`='".$notify_ohc_color."',
								`cmd_send`='".$cmd_send."',
								`cmd_gateway`='".$cmd_gateway."',
								`cmd_type`='".$cmd_type."',
								`cmd_string`='".$cmd_string."',
								 timing='".$timing."'
								WHERE `event_id`='".$event_id."'";
		}else{
			echo 'NO_PERMISSION';
			die;
		}
		
		$r = mysqli_query($ms, $q);

		if($type=='tanker_lowfuel'){
			$imeis_ex=explode(',',$imei);
			for ($i=0; $i <count($imeis_ex) ; $i++) { 
				$q="UPDATE gs_objects SET `tanker_low_fuel`='".$checked_value."' where imei='".$imeis_ex[$i]."'";
				mysqli_query($ms,$q);
			}
			
		}else if($type=='vehicle_lowfuel')
		{
			$imeis_ex=explode(',',$imei);
			for ($i=0; $i <count($imeis_ex) ; $i++) { 
				$q="UPDATE gs_objects SET `vehicle_low_fuel`='".$checked_value."' where imei='".$imeis_ex[$i]."'";
				mysqli_query($ms,$q);
			}
		}
		
		echo 'OK';
	}
?>