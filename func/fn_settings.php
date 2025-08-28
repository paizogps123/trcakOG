<? 
	session_start();
	include ('../init.php');
	include ('fn_common.php');
	include ('../tools/sms.php');
	checkUserSession();
	
	loadLanguage($_SESSION["language"], $_SESSION["units"]);
	
	if(@$_POST['cmd'] == 'clear_sms_queue')
	{
		clearSMSAPPQueue($_SESSION['sms_gateway_identifier']);
		echo 'OK';
		
		die;
	}
	
	if(@$_POST['cmd'] == 'load_server_data')
	{	
		$custom_maps = array();
		
		$q = "SELECT * FROM `gs_maps` ORDER BY `name` ASC";
		$r = mysqli_query($ms, $q);
		
		while($row=mysqli_fetch_array($r))
		{
			$map_id = $row['map_id'];
			$name = $row['name'];
			$active = $row['active'];
			$type = $row['type'];
			$url = $row['url'];
			$layers = $row['layers'];
			
			$layer_id = 'map_'.strtolower($name).'_'.$map_id;
			
			if ($active == 'true')
			{
				$custom_maps[] = array('layer_id' => $layer_id,'name' => $name, 'active' => $active, 'type' => $type, 'url' => $url, 'layers' => $layers);	
			}
			
		}
		
		$result = array('url_root' => $gsValues['URL_ROOT'],
				'map_custom' => $custom_maps,
				'map_osm' => $gsValues['MAP_OSM'],
				'map_bing' => $gsValues['MAP_BING'],
				'map_google' => $gsValues['MAP_GOOGLE'],
				'map_google_traffic' => $gsValues['MAP_GOOGLE_TRAFFIC'],
				'map_mapbox' => $gsValues['MAP_MAPBOX'],
				'map_yandex' => $gsValues['MAP_YANDEX'],
				'map_bing_key' => $gsValues['MAP_BING_KEY'],
				'map_mapbox_key' => $gsValues['MAP_MAPBOX_KEY'],
				'map_layer' => $gsValues['MAP_LAYER'],
				'map_zoom' => $gsValues['MAP_ZOOM'],
				'map_lat' => $gsValues['MAP_LAT'],
				'map_lng' => $gsValues['MAP_LNG'],
				'notify_obj_expire' => $gsValues['NOTIFY_OBJ_EXPIRE'],
				'notify_obj_expire_period' => $gsValues['NOTIFY_OBJ_EXPIRE_PERIOD'],
				'notify_account_expire' => $gsValues['NOTIFY_ACCOUNT_EXPIRE'],
				'notify_account_expire_period' => $gsValues['NOTIFY_ACCOUNT_EXPIRE_PERIOD']
				);
		
		echo json_encode($result);
		die;
	}
	
	if(@$_POST['cmd'] == 'load_user_data')
	{
		
		// groups_collapsed
		$default = array(	'objects' => false,
					'markers' => false,
					'routes' => false,
					'zones' => false
					);
		
		if (($_SESSION['groups_collapsed'] == '') || (json_decode($_SESSION['groups_collapsed'],true) == null))
		{
			$groups_collapsed = $default;
		}
		else
		{
			$groups_collapsed = json_decode($_SESSION['groups_collapsed'],true);
			
			if (!isset($groups_collapsed["objects"])) { $groups_collapsed["objects"] = $default["objects"]; }
			if (!isset($groups_collapsed["markers"])) { $groups_collapsed["markers"] = $default["markers"]; }
			if (!isset($groups_collapsed["routes"])) { $groups_collapsed["routes"] = $default["routes"]; }
			if (!isset($groups_collapsed["zones"])) { $groups_collapsed["objects"] = $default["zones"]; }
		}
		
		// ohc
		$default = array(	'no_connection' => false,
					'no_connection_color' => '#FFAEAE',
					'stopped' => false,
					'stopped_color' => '#FFAEAE',
					'moving' => false,
					'moving_color' => '#B0E57C',
					'engine_idle' => false,
					'engine_idle_color' => '#FFF0AA',
					'event_sos' => false,
					'event_sos_color' => '#B4D8E7'
					);
		
		if (($_SESSION['ohc'] == '') || (json_decode($_SESSION['ohc'],true) == null))
		{
			$ohc = $default;
		}
		else
		{
			$ohc = json_decode($_SESSION['ohc'],true);
			
			if (!isset($ohc["no_connection"])) { $ohc["no_connection"] = $default["no_connection"]; }
			if (!isset($ohc["no_connection_color"])) { $ohc["no_connection_color"] = $default["no_connection_color"]; }
			if (!isset($ohc["stopped"])) { $ohc["stopped"] = $default["stopped"]; }
			if (!isset($ohc["stopped_color"])) { $ohc["stopped_color"] = $default["stopped_color"]; }
			if (!isset($ohc["moving"])) { $ohc["moving"] = $default["moving"]; }
			if (!isset($ohc["moving_color"])) { $ohc["moving_color"] = $default["moving_color"]; }
			if (!isset($ohc["engine_idle"])) { $ohc["engine_idle"] = $default["engine_idle"]; }
			if (!isset($ohc["engine_idle_color"])) { $ohc["engine_idle_color"] = $default["engine_idle_color"]; }
			if (!isset($ohc["event_sos"])) { $ohc["event_sos"] = $default["event_sos"]; }
			if (!isset($ohc["event_sos_color"])) { $ohc["event_sos_color"] = $default["event_sos_color"]; }
		}
		
		if (($_SESSION['info'] == '') || (json_decode($_SESSION['info'],true) == null))
		{
			$info = array('name' => '',
				      'company' => '',
				      'address' => '',
				      'post_code' => '',
				      'city' => '',
				      'country' => '',
				      'phone1' => '',
				      'phone2' => '',
				      'email' => ''
				      );
		}
		else
		{
			$info = json_decode($_SESSION['info'], true);
		}
		
		if ($_SESSION['sms_gateway_identifier'] == '')
		{
			$_SESSION['sms_gateway_identifier'] = genSMSGatewayIdn($_SESSION["email"]);
		}
		
		$result = array('username' => $_SESSION["username"],
				'email' => $_SESSION["email"],
				'manager_id' => $_SESSION["manager_id"],
				'cpanel_privileges' => $_SESSION["cpanel_privileges"],
				'privileges' => $_SESSION["privileges"],
				'privileges_imei' => $_SESSION["privileges_imei"],
				'privileges_marker' => $_SESSION["privileges_marker"],
				'privileges_route' => $_SESSION["privileges_route"],
				'privileges_zone' => $_SESSION["privileges_zone"],
				'privileges_history' => $_SESSION["privileges_history"],
				'privileges_reports' => $_SESSION["privileges_reports"],
				'emergency_alert' => $_SESSION["emergency_alert"],
				'boarding_point_alert_system' => $_SESSION["boarding_point_alert_system"],
				'send_command' => $_SESSION["send_command"],
				'privileges_rilogbook' => $_SESSION["privileges_rilogbook"],
				'privileges_dtc' => $_SESSION["privileges_dtc"],
				'privileges_object_control' => $_SESSION["privileges_object_control"],
				'privileges_image_gallery' => $_SESSION["privileges_image_gallery"],
				'privileges_live_tripreport' => $_SESSION["privileges_live_tripreport"],
				'emergency_alert' => $_SESSION["emergency_alert"],
				'privileges_chat' => $_SESSION["privileges_chat"],
				'privileges_subaccounts' => $_SESSION["privileges_subaccounts"],
				'billing' => $_SESSION["billing"],
				'obj_add' => $_SESSION["obj_add"],
				'obj_limit' => $_SESSION["obj_limit"],
				'obj_limit_num' => $_SESSION["obj_limit_num"],
				'obj_days' => $_SESSION["obj_days"],
				'obj_days_dt' => $_SESSION["obj_days_dt"],
				'obj_edit' => $_SESSION["obj_edit"],
				'obj_history_clear' => $_SESSION["obj_history_clear"],
				'chat_notify' => $_SESSION['chat_notify'],
				'map_sp' => $_SESSION['map_sp'],
				'map_is' => $_SESSION['map_is'],
				'map_rc' => $_SESSION['map_rc'],
				'map_rhc' => $_SESSION['map_rhc'],
				'groups_collapsed' => $groups_collapsed,
				'od' => $_SESSION['od'],
				'ohc' => $ohc,
				'sms_gateway' => $_SESSION['sms_gateway'],
				'sms_gateway_type' => $_SESSION['sms_gateway_type'],
				'sms_gateway_url' => $_SESSION['sms_gateway_url'],
				'sms_gateway_identifier' => $_SESSION['sms_gateway_identifier'],
				'sms_gateway_total_in_queue' => getSMSAPPTotalInQueue($_SESSION['sms_gateway_identifier']),
				'language' => $_SESSION["language"],
				'unit_distance' => $_SESSION["unit_distance"],
				'unit_capacity' => $_SESSION["unit_capacity"],
				'unit_temperature' => $_SESSION["unit_temperature"],
				'currency' => $_SESSION["currency"],
				'timezone' => $_SESSION["timezone"],
				'dst' => $_SESSION["dst"],
				'dst_start' => $_SESSION["dst_start"],
				'dst_end' => $_SESSION["dst_end"],
				'info' => $info,
				'ambulance'=>$_SESSION["ambulance"],
				'user_dashboard'=>$_SESSION['user_dashboard']
				);
		if(isset($_SESSION['cpanel_user_id'])){
			$user_settings=checkUserSettings($_SESSION["cpanel_user_id"]);
		}else{
			$user_settings=checkUserSettings($_SESSION["user_id"]);
		}		
	
		$result['access_settings_user'] = json_decode($user_settings['cpanel_user'],true);
		$result['access_settings_object'] = json_decode($user_settings['object'],true);
		$result['access_settings_subuser'] = json_decode($user_settings['subaccount'],true);
		$result['access_settings_group'] = json_decode($user_settings['group'],true);
		$result['access_settings_markers'] = json_decode($user_settings['markers'],true);
		$result['access_settings_route'] = json_decode($user_settings['route'],true);
		$result['access_settings_events'] = json_decode($user_settings['events'],true);
		$result['access_settings_zones'] = json_decode($user_settings['zones'],true);
		$result['access_settings_duplicate'] = json_decode($user_settings['duplicate'],true);
		$result['access_settings_clr_history'] = json_decode($user_settings['clr_history'],true);
		
		echo json_encode($result);
		die;
	}
	
	if(@$_POST['cmd'] == 'save_user_settings')
	{
		$sms_gateway = $_POST["sms_gateway"];
		$sms_gateway_type = $_POST["sms_gateway_type"];
		$sms_gateway_url = $_POST["sms_gateway_url"];
		$sms_gateway_identifier = $_POST["sms_gateway_identifier"];
		
		$chat_notify = $_POST["chat_notify"];
		$map_sp = $_POST["map_sp"];
		$map_is = $_POST["map_is"];
		$map_rc = $_POST["map_rc"];
		$map_rhc = $_POST["map_rhc"];
		$groups_collapsed = $_POST["groups_collapsed"];
		$od = $_POST["od"];
		$ohc = $_POST["ohc"];
		$language = $_POST["language"];
		$units = $_POST["units"];
		$currency = $_POST["currency"];
		$timezone = $_POST["timezone"];
		$dst = $_POST["dst"];
		$dst_start = $_POST["dst_start"];
		$dst_end = $_POST["dst_end"];
		$info = $_POST["info"];
		$old_password = $_POST["old_password"];
		$new_password = $_POST["new_password"];
		
		$q = "UPDATE `gs_users` SET ";
		
		if ($sms_gateway != 'na')
		{
			$q .= "`sms_gateway`='".$sms_gateway."',";
		}
		
		if ($sms_gateway_type != 'na')
		{
			$q .= "`sms_gateway_type`='".$sms_gateway_type."',";
		}
		
		if ($sms_gateway_url != 'na')
		{
			$q .= "`sms_gateway_url`='".$sms_gateway_url."',";
		}
		
		if ($sms_gateway_identifier != 'na')
		{
			$q .= "`sms_gateway_identifier`='".$sms_gateway_identifier."',";
		}
		
		if ($chat_notify != 'na')
		{
			$q .= "`chat_notify`='".$chat_notify."',";
		}
		
		$q .= "`map_sp`='".$map_sp."',";
		
		$q .= "`map_is`='".$map_is."',";
		
		if ($map_rc != 'na')
		{
			$q .= "`map_rc`='".$map_rc."',";
		}
		
		if ($map_rhc != 'na')
		{
			$q .= "`map_rhc`='".$map_rhc."',";
		}
		
		if ($groups_collapsed != 'na')
		{
			$q .= "`groups_collapsed`='".$groups_collapsed."',";
		}
		
		if ($od != 'na')
		{
			$q .= "`od`='".$od."',";
		}
		
		if ($ohc != 'na')
		{
			$q .= "`ohc`='".$ohc."',";
		}
		
		if ($info != 'na')
		{
			$q .= "`info`='".$info."',";
		}
		
		if ($currency != 'na')
		{
			$q .= "`currency`='".$currency."',";
		}
		
		$q .=  "`language`='".$language."',
			`units`='".$units."',
			`timezone`='".$timezone."'";
			
		$q .= "WHERE `id`='".$_SESSION["user_id"]."'";
		$r = mysqli_query($ms, $q);
		
		// dst
		if ($dst != 'na')
		{
			$q = "UPDATE `gs_users` SET dst='".$dst."', dst_start='".$dst_start."', dst_end='".$dst_end."' WHERE `id`='".$_SESSION["user_id"]."'";
			$r = mysqli_query($ms, $q);
		}
		
		// password
		if ($new_password != '')
		{
			$q = "SELECT * FROM `gs_users` WHERE `id`='".$_SESSION["user_id"]."' AND `password`='".md5($old_password)."' LIMIT 1";
			$r = mysqli_query($ms, $q);
			$row = mysqli_fetch_array($r);
			
			if ($row)
			{
				$q = "UPDATE `gs_users` SET password='".md5($new_password)."' WHERE `id`='".$_SESSION["user_id"]."'";
				$r = mysqli_query($ms, $q);
			}
			else
			{
				echo 'ERROR_INCORRECT_PASSWORD';
				die;
			}
		}
		
		echo 'OK';
		die;
	}
	
	if(@$_POST['cmd'] == 'save_user_language')
	{
		$language = $_POST["language"];
		
		$q = "UPDATE `gs_users` SET `language`='".$language."' WHERE `id`='".$_SESSION["user_id"]."'";
		$r = mysqli_query($ms, $q);
		
		echo 'OK';
		die;
	}
?>