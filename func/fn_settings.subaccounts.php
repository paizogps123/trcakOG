<?
	session_start();
	include ('../init.php');
	include ('fn_common.php');
	checkUserSession();
	
	include ('../tools/email.php');
	
	loadLanguage($_SESSION["language"], $_SESSION["units"]);
	
	if(@$_POST['cmd'] == 'load_subaccount_data')
	{
		$manager_id = $_SESSION["user_id"];
		
		$q = "SELECT * FROM `gs_users` WHERE `privileges` LIKE '%subuser%' AND `manager_id`='".$manager_id."' ORDER BY `email` ASC";
		$r = mysqli_query($ms, $q);
		
		$result = array();
		
		while($row=mysqli_fetch_array($r))
		{
			$privileges = json_decode($row['privileges'],true);
			
			$privileges = checkUserPrivilegesArray($privileges);
			
			$imei = $privileges['imei'];
			$marker = $privileges['marker'];
			$route = $privileges['route'];
			$zone = $privileges['zone'];
			$history = $privileges['history'];
			$reports = $privileges['reports'];
			$sos = $privileges['sos'];
			$live_tripreport = $privileges['live_tripreport'];
			$sendcommand = $privileges['sendcommand'];
			$boarding = $privileges['boarding'];
			$rilogbook = $privileges['rilogbook'];
			$dtc = $privileges['dtc'];
			$object_control = $privileges['object_control'];
			$image_gallery = $privileges['image_gallery'];
			$chat = $privileges['chat'];
			$select_report=reportlist_selecteduser($row['id']);
			if (!isset($privileges['au_active'])) { $privileges['au_active'] = false; }
			$au_active = $privileges['au_active'];
			
			if (!isset($privileges['au'])) { $privileges['au'] = ''; }
			$au = $privileges['au'];
			
			$subaccount_id = $row['id'];
			$user_settings=checkUserSettings($row['id']);
			$result[$subaccount_id] = array('email' => $row['email'],
							'active' => $row['active'],
							'account_expire' => $row['account_expire'],
							'account_expire_dt' => $row['account_expire_dt'],
							'imei' => $imei,
							'marker' => $marker,
							'route' => $route,
							'zone' => $zone,
							'history' => $history,
							'reports' => $reports,
							'sos' => $sos,
							'live_tripreport' => $live_tripreport,
							'sendcommand' => $sendcommand,
							'boarding' => $boarding,
							'rilogbook' => $rilogbook,
							'dtc' => $dtc,
							'object_control' => $object_control,
							'image_gallery' => $image_gallery,
							'chat' => $chat,
							'au_active' => $au_active,
							'au' => $au,
							'mobile_num' => $row['subuser_phone'],
							'subaccnt_type'=>$row['driver'],
							'ambulance'=>$row['ambulance'],
							'select_report'=>$select_report,
							'access_settings_user'=>json_decode($user_settings['cpanel_user'],true),
							'access_settings_object'=>json_decode($user_settings['object'],true),
							'access_settings_subuser'=>json_decode($user_settings['subaccount'],true),
							'access_settings_group'=>json_decode($user_settings['group'],true),
							'access_settings_markers'=>json_decode($user_settings['markers'],true),
							'access_settings_route'=>json_decode($user_settings['route'],true),
							'access_settings_events'=>json_decode($user_settings['events'],true),
							'access_settings_zones'=>json_decode($user_settings['zones'],true),
							'access_settings_duplicate'=>json_decode($user_settings['duplicate'],true),
							'access_settings_clr_history'=>json_decode($user_settings['clr_history'],true)
							);
			}
		echo json_encode($result);
		die;
	}
	
	if(@$_POST['cmd'] == 'delete_subaccount')
	{
		if(checkSettingsPrivileges('subaccount','delete')!=true){
			echo 'NO_PERMISSION';
			die;
		}
		$subaccount_id= $_POST["subaccount_id"];
		$manager_id = $_SESSION["user_id"];
		
		$q = "DELETE FROM `gs_users` WHERE `id`='".$subaccount_id."' AND `manager_id`='".$manager_id."'";
		$r = mysqli_query($ms, $q);
		
		echo 'OK';
		die;
	}
	
	if(@$_POST['cmd'] == 'delete_selected_subaccounts')
	{
		if(checkSettingsPrivileges('subaccount','delete')!=true){
			echo 'NO_PERMISSION';
			die;
		}
		$items = $_POST["items"];
		$manager_id = $_SESSION["user_id"];
				
		for ($i = 0; $i < count($items); ++$i)
		{
			$item = $items[$i];
			
			$q = "DELETE FROM `gs_users` WHERE `id`='".$item."' AND `manager_id`='".$manager_id."'";
			$r = mysqli_query($ms, $q);
		}
		
		echo 'OK';
		die;
	}
	
	if(@$_POST['cmd'] == 'save_subaccount')
	{
		$result = '';
		
		$subaccount_id = $_POST["subaccount_id"];
		$email = strtolower($_POST["email"]);
		$password = $_POST["password"];
		$active = $_POST["active"];
		$account_expire = $_POST["account_expire"];
		$account_expire_dt = $_POST["account_expire_dt"];
		$privileges = $_POST["privileges"];
		$manager_id = $_SESSION["user_id"];
		$mobile_num = $_POST["mobile_num"];
		$subaccnt_type=$_POST["subaccnt_type"];
		$ambulance=$_POST["ambulance"];
		$report_list=$_POST['report_list'];
		
		if($subaccnt_type=="Driver account")
		$subaccnt_type="true";
		else
		$subaccnt_type="false";

		$user_settings=array();
		$user_settings['u_user_settings']=$_POST['u_user_settings'];
		$user_settings['u_subuser_settings']=$_POST['u_subuser_settings'];
		$user_settings['u_object_settings']=$_POST['u_object_settings'];
		$user_settings['u_group_settings']=$_POST['u_group_settings'];
		$user_settings['u_event_settings']=$_POST['u_event_settings'];
		$user_settings['u_zone_settings']=$_POST['u_zone_settings'];
		$user_settings['u_route_settings']=$_POST['u_route_settings'];
		$user_settings['u_marker_settings']=$_POST['u_marker_settings'];
		$user_settings['u_duplicate_settings']=$_POST['u_duplicate_settings'];
		$user_settings['u_clr_history_settings']=$_POST['u_clr_history_settings'];
		$user_settings= json_encode($user_settings);	
		
		if ($subaccount_id == 'false')
		{
			if(checkSettingsPrivileges('subaccount','add')!=true){
				echo 'NO_PERMISSION';
				die;
			}
			$manager_id = $_SESSION["user_id"];
						
			$result = addUser('true', $active, $account_expire, $account_expire_dt, $privileges, $manager_id, $email, $password, 'false', 'false', '', 'false', '', 'false', 'false',$mobile_num,$subaccnt_type,$report_list,'','',$user_settings);
		}
		else
		{	
			if(checkSettingsPrivileges('subaccount','edit')!=true){
				echo 'NO_PERMISSION';
				die;
			}
			if($user_settings!=''){
				$user_settings = json_decode(stripslashes($user_settings),true);
				$u_user_settings=$user_settings['u_user_settings'];
				$u_subuser_settings=$user_settings['u_subuser_settings'];
				$u_object_settings=$user_settings['u_object_settings'];
				$u_group_settings=$user_settings['u_group_settings'];
				$u_event_settings=$user_settings['u_event_settings'];
				$u_zone_settings=$user_settings['u_zone_settings'];
				$u_route_settings=$user_settings['u_route_settings'];
				$u_marker_settings=$user_settings['u_marker_settings'];
				$u_duplicate_settings=$user_settings['u_duplicate_settings'];
				$u_clr_history_settings=$user_settings['u_clr_history_settings'];
			}else{
				$u_user_settings='{"add":false,"edit":false,"delete":false}';
				$u_subuser_settings='{"add":false,"edit":false,"delete":false}';
				$u_object_settings='{"add":false,"edit":false,"delete":false}';
				$u_group_settings='{"add":false,"edit":false,"delete":false}';
				$u_event_settings='{"add":false,"edit":false,"delete":false}';
				$u_zone_settings='{"add":false,"edit":false,"delete":false}';
				$u_route_settings='{"add":false,"edit":false,"delete":false}';
				$u_marker_settings='{"add":false,"edit":false,"delete":false}';
				$u_duplicate_settings='{"add":false,"edit":false,"delete":false}';
				$u_clr_history_settings='{"add":false,"edit":false,"delete":false}';
			}

			update_report_list($subaccount_id,$report_list);
			if($account_expire_dt!="")
			{
				$q = "UPDATE `gs_users` SET 	`active`='".$active."',
							`account_expire`='".$account_expire."',
							`account_expire_dt`='".$account_expire_dt."',
							`username`='".$email."',
							`email`='".$email."',
							 subuser_phone='".$mobile_num."',
        					 driver='".$subaccnt_type."',
							`privileges`='".$privileges."'
							WHERE `id`='".$subaccount_id."' AND `manager_id`='".$manager_id."'";
				$r = mysqli_query($ms, $q);
			}
			else
			{
				$q = "UPDATE `gs_users` SET 	`active`='".$active."',
							`account_expire`='".$account_expire."',
							`username`='".$email."',
							`email`='".$email."',
							 subuser_phone='".$mobile_num."',
        					 driver='".$subaccnt_type."',
							`privileges`='".$privileges."'
							WHERE `id`='".$subaccount_id."' AND `manager_id`='".$manager_id."'";
				$r = mysqli_query($ms, $q);
			}
			updateUserSettings($subaccount_id,$u_user_settings,$u_subuser_settings,$u_object_settings,$u_group_settings,$u_event_settings,$u_zone_settings,$u_route_settings,$u_marker_settings,$u_duplicate_settings,$u_clr_history_settings);
			
			//update manger's ambulance data if its manager rights is true means can cg=hange otherwise cant,Manager rights and sub accnt rght will update by cpanel rights so it ill work fine dont rise bugs
			$q = "UPDATE gs_users as t1 INNER JOIN gs_users as t2 ON t1.manager_id = t2.id AND t1.id='".$subaccount_id."' and t2.ambulance='true' SET t1.ambulance ='".$ambulance."' ";
			$r = mysqli_query($ms, $q);
		
			if ($password != '')
			{
				$q = "UPDATE `gs_users` SET `password`='".md5($password)."' WHERE `id`='".$subaccount_id."' AND `manager_id`='".$manager_id."'";
				$r = mysqli_query($ms, $q);
			}
			
			$result = 'OK';
		}
		
		echo $result;
		die;
	}
	
	if(@$_GET['cmd'] == 'load_subaccount_list')
	{
		$manager_id = $_SESSION["user_id"];
		
		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
		
		if(!$sidx) $sidx = 1;
		
		// get records number
		$q = "SELECT * FROM `gs_users` WHERE `privileges` LIKE '%subuser%' AND `manager_id`='".$manager_id."'";
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
		
		$q = "SELECT * FROM `gs_users` WHERE `privileges` LIKE '%subuser%' AND `manager_id`='".$manager_id."' ORDER BY $sidx $sord LIMIT $start, $limit";
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
				$subaccount_id = $row["id"];
				$email = $row['email'];
				
				if ($row['active'] == 'true')
				{
					$active = '<img src="theme/images/tick-green.svg" />';
				}
				else
				{
					$active = '<img src="theme/images/remove-red.svg" style="width:12px;" />';
				}
				
				$privileges = json_decode($row['privileges'],true);
				
				$imeis = count(explode(",", $privileges['imei']));
				
				$markers = explode(",", $privileges['marker']);
				if ($markers[0] != '')
				{
					$markers = count($markers);
				}
				else
				{
					$markers = 0;
				}
				
				$routes = explode(",", $privileges['route']);
				if ($routes[0] != '')
				{
					$routes = count($routes);
				}
				else
				{
					$routes = 0;
				}
				
				$zones = explode(",", $privileges['zone']);
				if ($zones[0] != '')
				{
					$zones = count($zones);
				}
				else
				{
					$zones = 0;
				}
				
				$places = $markers.'/'.$routes.'/'.$zones;
				
				// set modify buttons
				$modify ='';
				if(checkSettingsPrivileges('subaccount','edit')==true){
					$modify .= '<a href="#" onclick="settingsSubaccountProperties(\''.$subaccount_id.'\');" title="'.$la['EDIT'].'"><img src="theme/images/edit.svg"/></a>';
				}
				if(checkSettingsPrivileges('subaccount','delete')==true){
					$modify .= '<a href="#" onclick="settingsSubaccountDelete(\''.$subaccount_id.'\');" title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg"/></a>';
				}
				// set row
				$response->rows[$i]['id']=$subaccount_id;
				$response->rows[$i]['cell']=array($email,$active,$imeis,$places,$modify);
				$i++;
			}
		}

		header('Content-type: application/json');
		echo json_encode($response);
		die;
	}
?>