<?
	set_time_limit(0);
	
	session_start();
	include ('../init.php');
	include ('fn_common.php');
	include ('fn_cleanup.php');
	include ('../tools/email.php');
	include ('../tools/sms.php');
	checkUserSession();
	checkUserCPanelPrivileges();
	
	loadLanguage($_SESSION["language"], $_SESSION["units"]);
        
        if(@$_POST['cmd'] == 'load_server_data')
	{
		// get not modified server settings
		include ('../config.custom.php');
		
		if ($gsValues['SERVER_API_KEY'] == '')
		{
			$gsValues['SERVER_API_KEY'] = genServerAPIKey();
		}
		
		if ($gsValues['SMS_GATEWAY_IDENTIFIER'] == '')
		{
			$gsValues['SMS_GATEWAY_IDENTIFIER'] = genSMSGatewayIdn($_SESSION["email"]);
		}
		
		$result = array('name' => stripcslashes($gsValues['NAME']),
				'generator' => stripcslashes($gsValues['GENERATOR']),
				'server_api_key' => $gsValues['SERVER_API_KEY'],
				'show_about' => $gsValues['SHOW_ABOUT'],
				'languages' => $gsValues['LANGUAGES'],
				'logo' => $gsValues['LOGO'],
				'url_login' => $gsValues['URL_LOGIN'],
				'url_help' => $gsValues['URL_HELP'],
				'url_contact' => $gsValues['URL_CONTACT'],
				'url_shop' => $gsValues['URL_SHOP'],
				'url_sms_gateway_app' => $gsValues['URL_SMS_GATEWAY_APP'],
				'geocoder_cache' => $gsValues['GEOCODER_CACHE'],
				'connection_timeout' => $gsValues['CONNECTION_TIMEOUT'],
				'history_period' => $gsValues['HISTORY_PERIOD'],
				'db_backup_time' => $gsValues['DB_BACKUP_TIME'],
				'db_backup_email' => $gsValues['DB_BACKUP_EMAIL'],
				'map_osm' => $gsValues['MAP_OSM'],
				'map_bing' => $gsValues['MAP_BING'],
				'map_google' => $gsValues['MAP_GOOGLE'],
				'map_google_traffic' => $gsValues['MAP_GOOGLE_TRAFFIC'],
				'map_mapbox' => $gsValues['MAP_MAPBOX'],
				'map_yandex' => $gsValues['MAP_YANDEX'],
				'map_bing_key' => $gsValues['MAP_BING_KEY'],
				'map_google_key' => $gsValues['MAP_GOOGLE_KEY'],
				'map_mapbox_key' => $gsValues['MAP_MAPBOX_KEY'],
				'map_layer' => $gsValues['MAP_LAYER'],
				'map_zoom' => $gsValues['MAP_ZOOM'],
				'map_lat' => $gsValues['MAP_LAT'],
				'map_lng' => $gsValues['MAP_LNG'],
				'page_after_login' => $gsValues['PAGE_AFTER_LOGIN'],
				'allow_registration' => $gsValues['ALLOW_REGISTRATION'],
				'account_expire' => $gsValues['ACCOUNT_EXPIRE'],
				'account_expire_period' => $gsValues['ACCOUNT_EXPIRE_PERIOD'],
				'language' => $gsValues['LANGUAGE'],				
				'unit_of_distance' => $gsValues['UNIT_OF_DISTANCE'],
				'unit_of_capacity' => $gsValues['UNIT_OF_CAPACITY'],
				'unit_of_temperature' => $gsValues['UNIT_OF_TEMPERATURE'],
				'currency' => $gsValues['CURRENCY'],
				'timezone' => $gsValues['TIMEZONE'],
				'dst' => $gsValues['DST'],
				'dst_start' => $gsValues['DST_START'],
				'dst_end' => $gsValues['DST_END'],
				'obj_add' => $gsValues['OBJ_ADD'],
				'obj_limit' => $gsValues['OBJ_LIMIT'],
				'obj_limit_num' => $gsValues['OBJ_LIMIT_NUM'],
				'obj_days' => $gsValues['OBJ_DAYS'],
				'obj_days_num' => $gsValues['OBJ_DAYS_NUM'],
				'obj_days_trial' => $gsValues['OBJ_DAYS_TRIAL'],
				'obj_edit' => $gsValues['OBJ_EDIT'],
				'obj_history_clear' => $gsValues['OBJ_HISTORY_CLEAR'],
				'history' => $gsValues['HISTORY'],
				'reports' => $gsValues['REPORTS'],
				'rilogbook' => $gsValues['RILOGBOOK'],
				'dtc' => $gsValues['DTC'],
				'object_control' => $gsValues['OBJECT_CONTROL'],
				'image_gallery' => $gsValues['IMAGE_GALLERY'],
				'chat' => $gsValues['CHAT'],
				'subaccounts' => $gsValues['SUBACCOUNTS'],
				'sms_gateway_server' => $gsValues['SMS_GATEWAY_SERVER'],
				'api' => $gsValues['API'],
				'notify_obj_expire' => $gsValues['NOTIFY_OBJ_EXPIRE'],
				'notify_obj_expire_period' => $gsValues['NOTIFY_OBJ_EXPIRE_PERIOD'],
				'notify_account_expire' => $gsValues['NOTIFY_ACCOUNT_EXPIRE'],
				'notify_account_expire_period' => $gsValues['NOTIFY_ACCOUNT_EXPIRE_PERIOD'],
				'reports_schedule' => $gsValues['REPORTS_SCHEDULE'],
				'places_markers' => $gsValues['PLACES_MARKERS'],
				'places_routes' => $gsValues['PLACES_ROUTES'],
				'places_zones' => $gsValues['PLACES_ZONES'],
				'usage_email_daily' => $gsValues['USAGE_EMAIL_DAILY'],
				'usage_sms_daily' => $gsValues['USAGE_SMS_DAILY'],
				'usage_api_daily' => $gsValues['USAGE_API_DAILY'],
				'billing' => $gsValues['BILLING'],
				'billing_gateway' => $gsValues['BILLING_GATEWAY'],
				'billing_currency' => $gsValues['BILLING_CURRENCY'],
				'billing_paypal_account' => $gsValues['BILLING_PAYPAL_ACCOUNT'],
				'billing_paypal_custom' => $gsValues['BILLING_PAYPAL_CUSTOM'],
				'billing_paypal_ipn_url' => $gsValues['URL_ROOT'].'/api/billing/paypal.php',
				'billing_custom_url' => $gsValues['BILLING_CUSTOM_URL'],
				'email' => $gsValues['EMAIL'],
				'email_no_reply' => $gsValues['EMAIL_NO_REPLY'],
				'email_signature' => $gsValues['EMAIL_SIGNATURE'],
				'email_smtp' => $gsValues['EMAIL_SMTP'],
				'email_smtp_host' => $gsValues['EMAIL_SMTP_HOST'],
				'email_smtp_port' => $gsValues['EMAIL_SMTP_PORT'],
				'email_smtp_auth' => $gsValues['EMAIL_SMTP_AUTH'],
				'email_smtp_secure' => $gsValues['EMAIL_SMTP_SECURE'],
				'email_smtp_username' => $gsValues['EMAIL_SMTP_USERNAME'],
				'email_smtp_password' => $gsValues['EMAIL_SMTP_PASSWORD'],
				'sms_gateway' => $gsValues['SMS_GATEWAY'],
				'sms_gateway_type' => $gsValues['SMS_GATEWAY_TYPE'],
				'sms_gateway_number_filter' => $gsValues['SMS_GATEWAY_NUMBER_FILTER'],
				'sms_gateway_url' => $gsValues['SMS_GATEWAY_URL'],
				'sms_gateway_identifier' => $gsValues['SMS_GATEWAY_IDENTIFIER'],				
				'server_cleanup_users_ae' => $gsValues['SERVER_CLEANUP_USERS_AE'],
				'server_cleanup_objects_not_activated_ae' => $gsValues['SERVER_CLEANUP_OBJECTS_NOT_ACTIVATED_AE'],
				'server_cleanup_objects_not_used_ae' => $gsValues['SERVER_CLEANUP_OBJECTS_NOT_USED_AE'],
				'server_cleanup_db_junk_ae' => $gsValues['SERVER_CLEANUP_DB_JUNK_AE'],
				'server_cleanup_users_days' => $gsValues['SERVER_CLEANUP_USERS_DAYS'],
				'server_cleanup_objects_not_activated_days' => $gsValues['SERVER_CLEANUP_OBJECTS_NOT_ACTIVATED_DAYS']
				);
		echo json_encode($result);
		die;
	}
	
	if(@$_POST['cmd'] == 'save_server_data')
	{
		if ($_SESSION["cpanel_privileges"] != 'super_admin')
		{
			die;
		}
		
		$config = '';
		
		foreach ($_POST as $key => $value)
		{
			if ($key <> 'cmd')
			{
				$config .= '$gsValues[\''.strtoupper($key).'\'] = "'.$value.'";'."\r\n";
			}
		}
		
		$config = "<?\r\n".$config. "?>";
		
		$handle = fopen('../config.custom.php', 'w');
		fwrite($handle, $config);
		fclose($handle);
		
		echo 'OK';
		die;		
	}
        
        	
	if(@$_POST['cmd'] == 'clear_geocoder_cache')
	{
		if ($_SESSION["cpanel_privileges"] != 'super_admin')
		{
			die;
		}
		
		$q = "DELETE FROM gs_geocoder_cache";
		$r = mysqli_query($ms, $q);
		
		$q = "ALTER TABLE gs_geocoder_cache AUTO_INCREMENT = 1";
		$r = mysqli_query($ms, $q);
		
		echo 'OK';
		die;
	}
        
        if(@$_POST['cmd'] == 'load_custom_map_list')
	{
		$result = array();
		
		$q = "SELECT * FROM `gs_maps` ORDER BY `name` ASC";
		$r = mysqli_query($ms, $q);
		
		while($row=mysqli_fetch_array($r))
		{
			$map_id = $row['map_id'];
			$name = $row['name'];
			$active = $row['active'];
			$type = strtoupper($row['type']);
			$url = $row['url'];
			$layers = $row['layers'];
			
			$result[] = array('map_id' => $map_id, 'name' => $name, 'active' => $active, 'type' => $type, 'url' => $url, 'layers' => $layers);
		}
		
		echo json_encode($result);
		die;
	}
        
        if(@$_POST['cmd'] == 'load_custom_map')
	{
		$result = array();
		
		$map_id = $_POST['map_id'];
		
		$q = "SELECT * FROM `gs_maps` WHERE `map_id`='".$map_id."'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);
		
		$result = array('name' => $row['name'], 'active' => $row['active'], 'type' => $row['type'], 'url' => $row['url'], 'layers' => $row['layers']);
		
		echo json_encode($result);
		die;
	}
	
	if(@$_POST['cmd'] == 'save_custom_map')
	{
		$map_id = $_POST["map_id"];
		$name = $_POST["name"];
		$active = $_POST["active"];
		$type = $_POST["type"];
		$url = $_POST["url"];
		$layers = $_POST["layers"];
		
		if ($map_id == 'false')
		{
			$q = "INSERT INTO `gs_maps` 	(`name`,
							`active`,
							`type`,
							`url`,
							`layers`
							) VALUES (
							'".$name."',
							'".$active."',
							'".$type."',
							'".$url."',
							'".$layers."')";
		}
		else
		{
			$q = "UPDATE `gs_maps` SET 	`name`='".$name."', 
							`active`='".$active."',
							`type`='".$type."',
							`url`='".$url."',
							`layers`='".$layers."'
							WHERE `map_id`='".$map_id."'";
		}
		
		$r = mysqli_query($ms, $q);
		
		echo 'OK';
	}
	
	if(@$_POST['cmd'] == 'delete_all_custom_maps')
	{		
		$q = "DELETE FROM `gs_maps`";
		$r = mysqli_query($ms, $q);
		
		echo 'OK';
		die;
	}
        
        if(@$_POST['cmd'] == 'delete_custom_map')
	{
		$map_id = $_POST["map_id"];
		
		$q = "DELETE FROM `gs_maps` WHERE `map_id`='".$map_id."'";
		$r = mysqli_query($ms, $q);
		
		echo 'OK';
		die;
	}
        
        if(@$_POST['cmd'] == 'load_billing_plan_list')
	{
		$result = array();
		
		$q = "SELECT * FROM `gs_billing_plans` ORDER BY `name` ASC";
		$r = mysqli_query($ms, $q);
		
		while($row=mysqli_fetch_array($r))
		{
			$plan_id = $row['plan_id'];
			$name = $row['name'];
			$active = $row['active'];
			$objects = $row['objects'];
			$period = $row['period'];
			$period_type = $row['period_type'];
			$price = $row['price'];
			
			$price .= ' '.$gsValues['BILLING_CURRENCY'];
			
			$result[] = array('plan_id' => $plan_id, 'name' => $name, 'active' => $active, 'objects' => $objects, 'period' => $period, 'period_type' => $period_type, 'price' => $price);
		}
		
		echo json_encode($result);
		die;
	}
        
        if(@$_POST['cmd'] == 'load_billing_plan')
	{
		$result = array();
		
		$plan_id = $_POST['plan_id'];
		
		$q = "SELECT * FROM `gs_billing_plans` WHERE `plan_id`='".$plan_id."'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);
		
		$result = array('plan_id' => $row['plan_id'], 'name' => $row['name'], 'active' => $row['active'], 'objects' => $row['objects'], 'period' => $row['period'], 'period_type' => $row['period_type'], 'price' => $row['price']);
		
		echo json_encode($result);
		die;
	}
	
	if(@$_POST['cmd'] == 'save_billing_plan')
	{
		$plan_id = $_POST["plan_id"];
		$name = $_POST["name"];
		$active = $_POST["active"];
		$objects = $_POST["objects"];
		$period = $_POST["period"];
		$period_type = $_POST["period_type"];
		$price = $_POST["price"];
		
		if ($plan_id == 'false')
		{
			$q = "INSERT INTO `gs_billing_plans` 	(`name`,
							`active`,
							`objects`,
							`period`,
							`period_type`,
							`price`
							) VALUES (
							'".$name."',
							'".$active."',
							'".$objects."',
							'".$period."',
							'".$period_type."',
							'".$price."')";
		}
		else
		{
			$q = "UPDATE `gs_billing_plans` SET 	`name`='".$name."', 
							`active`='".$active."',
							`objects`='".$objects."',
							`period`='".$period."',
							`period_type`='".$period_type."',
							`price`='".$price."'
							WHERE `plan_id`='".$plan_id."'";
		}
		
		$r = mysqli_query($ms, $q);
		
		echo 'OK';
		die;
	}
	
	if(@$_POST['cmd'] == 'delete_all_billing_plans')
	{		
		$q = "DELETE FROM `gs_billing_plans`";
		$r = mysqli_query($ms, $q);
		
		echo 'OK';
		die;
	}
        
        if(@$_POST['cmd'] == 'delete_billing_plan')
	{
		$plan_id = $_POST["plan_id"];
		
		$q = "DELETE FROM `gs_billing_plans` WHERE `plan_id`='".$plan_id."'";
		$r = mysqli_query($ms, $q);
		
		echo 'OK';
		die;
	}
        
        if(@$_POST['cmd'] == 'load_template_list')
	{
		$result = array();
		
		$q = "SELECT * FROM `gs_templates` WHERE `language`='english' ORDER BY `name` ASC";
		$r = mysqli_query($ms, $q);
		
		while($row=mysqli_fetch_array($r))
		{
			$name = $row['name'];
			
			$result[] = array('name' => $name);
		}
		
		echo json_encode($result);
		die;
	}
        
        if(@$_POST['cmd'] == 'load_template')
	{
		$result = array();
		
		$name = $_POST['name'];
		$language = $_POST['language'];
		
		$q = "SELECT * FROM `gs_templates` WHERE `name`='".$name."' AND `language`='".$language."'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);
		
		if (!$row)
		{
			$q = "SELECT * FROM `gs_templates` WHERE `name`='".$name."' AND `language`='english'";
			$r = mysqli_query($ms, $q);
			$row = mysqli_fetch_array($r);
		}
		
		$result = array('name' => $row['name'], 'subject' => $row['subject'], 'message' => $row['message']);
		
		echo json_encode($result);
		die;
	}
	
	if(@$_POST['cmd'] == 'save_template')
	{
		$name = $_POST['name'];
		$language = $_POST['language'];
		$subject = $_POST['subject'];
		$message = $_POST['message'];
		
		$q = "SELECT * FROM `gs_templates` WHERE `name`='".$name."' AND `language`='".$language."'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);
		
		if ($row)
		{
			$q = "UPDATE gs_templates SET `subject`='".$subject."', `message`='".$message."' WHERE `name`='".$name."' AND `language`='".$language."'";
			$r = mysqli_query($ms, $q);
		}
		else
		{
			$q = "INSERT INTO `gs_templates` 	(`name`,
								`language`,
								`subject`,
								`message`)
								VALUES (
								'".$name."',
								'".$language."',
								'".$subject."',
								'".$message."')";		
			$r = mysqli_query($ms, $q);
		}
		
		echo 'OK';
		die;
	}
        
        if(@$_POST['cmd'] == 'clear_sms_queue')
	{
		clearSMSAPPQueue($gsValues['SMS_GATEWAY_IDENTIFIER']);
		echo 'OK';
		
		die;
	}
        
        if(@$_POST['cmd'] == 'server_cleanup_users')
	{
		$days = $_POST['days'];
		$result = serverCleanupUsers($days);
		
		echo $result;
		
		die;
	}
	
	if(@$_POST['cmd'] == 'server_cleanup_objects_not_activated')
	{
		$days = $_POST['days'];
		$result = serverCleanupObjectsNotActivated($days);
		
		echo $result;
		
		die;
	}
	
	if(@$_POST['cmd'] == 'server_cleanup_objects_not_used')
	{
		$result = serverCleanupObjectsNotUsed();
		
		echo $result;
		
		die;
	}
	
	if(@$_POST['cmd'] == 'server_cleanup_db_junk')
	{
		$result = serverCleanupDbJunk();
		
		echo $result;
		
		die;
	}
        
        if(@$_POST['cmd'] == 'load_log_list')
	{
		$result = array();
		
		$dir = $gsValues['PATH_ROOT'].'/logs';
		$dh = opendir($dir);
		
		while (($file = readdir($dh)) !== false)
		{
			if ($file != '.' && $file != '..' && $file != 'Thumbs.db')
			{
				$modified = convUserTimezone(gmdate("Y-m-d H:i:s", filemtime($dir.'/'.$file)));
				$size = filesize($dir.'/'.$file);
				$size = number_format($size / 1048576, 3);
				$result[] = array('name' => $file, 'modified' => $modified, 'size' => $size);
			}
		}
		
		closedir($dh);
		
		echo json_encode($result);
		die;
	}
        
        if(@$_POST['cmd'] == 'delete_all_logs')
	{
		$path = $gsValues['PATH_ROOT'].'logs/';
		
		$files = glob($path."*.php");
		
		if (is_array($files))
		{
			foreach($files as $file)
			{
				if(is_file($file))
				{
					@unlink($file);
				}
			}
		}
		
		echo 'OK';
		die;
	}
        
        if(@$_POST['cmd'] == 'delete_log')
	{
		$file = $_POST['file'];
		
		$file = $gsValues['PATH_ROOT'].'logs/'.$file;
		if(is_file($file))
		{
			@unlink($file);
		}
		
		echo 'OK';
		die;
	}
?>