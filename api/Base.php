<?

date_default_timezone_set("Asia/Kolkata");
if(function_exists('xdebug_disable')) { xdebug_disable(); }
error_reporting(E_ALL ^ E_DEPRECATED);
	// #################################################
	// USER FUNCTIONS
	// #################################################

function encrypt($pure_string ) {

	$iv = "1234567812345678";
	$iv = "";
	$password = "NightCrawler";
	$method = "aes-128-ecb";
	$encrypted = openssl_encrypt($pure_string, $method, $password,false, $iv);
	$encrypted=ascii2hex($encrypted);
	return $encrypted;
}

function decrypt($encrypted_string) {
	$iv = "1234567812345678";
	$iv = "";
	$password = "NightCrawler";
	$method = "aes-128-ecb";
	$encrypted_string=hex2ascii($encrypted_string);
	$decrypted = openssl_decrypt($encrypted_string, $method, $password,false, $iv);
	return $decrypted;
}

function ascii2hex($ascii) {
	$hex = '';
	for ($i = 0; $i < strlen($ascii); $i++) {
		$byte = strtoupper(dechex(ord($ascii{$i})));
		$byte = str_repeat('0', 2 - strlen($byte)).$byte;
		$hex.=$byte."";
	}
	return $hex;
}


function hex2ascii($hex){
	$ascii='';
	$hex=str_replace(" ", "", $hex);
	for($i=0; $i<strlen($hex); $i=$i+2) {
		$ascii.=chr(hexdec(substr($hex, $i, 2)));
	}
	return($ascii);
}


function get_client_ip() {
	$ipaddress = '';
	if (isset($_SERVER['HTTP_CLIENT_IP']))
	$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else if(isset($_SERVER['HTTP_X_FORWARDED']))
	$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
	$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	else if(isset($_SERVER['HTTP_FORWARDED']))
	$ipaddress = $_SERVER['HTTP_FORWARDED'];
	else if(isset($_SERVER['REMOTE_ADDR']))
	$ipaddress = $_SERVER['REMOTE_ADDR'];
	else
	$ipaddress = 'UNKNOWN';
	return $ipaddress;
}


	function getRoute($imei, $dtf, $dtt, $min_stop_duration, $filter)
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
		
		$route = getRouteRaw($imei, $accuracy, $dtf, $dtt);
		
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
			$result['drives'] = getRouteDrives($route, $accuracy, $result['stops'], $fcr, $fuel_sensors, $fuelcons_sensors, $acc);
			
			// load events
			$result['events'] = getRouteEvents($imei, $dtf, $dtt);
			
			// count route_length
			$result['route_length'] = 0;
			for ($i=0; $i<count($result['drives']); ++$i)
			{
				$result['route_length'] += $result['drives'][$i][7];
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
			$result['stops_duration'] = getTimeDetails($result['stops_duration_time']);
			
			// count drives duration and engine work
			$result['drives_duration_time'] = 0;
			for ($i=0; $i<count($result['drives']); ++$i)
			{
				$diff = strtotime($result['drives'][$i][5])-strtotime($result['drives'][$i][4]);
				$result['drives_duration_time'] += $diff;
			}
			$result['drives_duration'] = getTimeDetails($result['drives_duration_time']);
			
			// prepare full engine work and idle info
			$result['engine_work_time'] = 0;
			$result['engine_idle_time'] = 0;
			
			for ($i=0; $i<count($result['drives']); ++$i)
			{
				$result['engine_work_time'] += $result['drives'][$i][12];
				$result['drives'][$i][12] = getTimeDetails($result['drives'][$i][12]);
			}
			
			for ($i=0; $i<count($result['stops']); ++$i)
			{
				$result['engine_idle_time'] += $result['stops'][$i][9];
				$result['stops'][$i][9] = getTimeDetails($result['stops'][$i][9]);	
			}
			
			// set total engine work and idle
			$result['engine_work_time'] += $result['engine_idle_time'];
			$result['engine_work'] = getTimeDetails($result['engine_work_time']);
			$result['engine_idle'] = getTimeDetails($result['engine_idle_time']);
		}
		
		return $result;
	}
	
	

	function getUserIdFromSessionHash()
	{
		global $gsValues;
		
		$result = false;
		
		if (isset($_COOKIE['gs_sess_hash']))
		{
			$sess_hash = $_COOKIE['gs_sess_hash'];
			
			$q = "SELECT * FROM `gs_users` WHERE `sess_hash`='".$sess_hash."'";
			$r = mysql_query($q);
			
			if ($row = mysql_fetch_array($r))
			{
				$sess_hash_check = md5($gsValues['PATH_ROOT'].$row['id'].$row['username'].$row['password'].$_SERVER['REMOTE_ADDR']);
				
				if ($sess_hash_check == $sess_hash)
				{
					$result = $row['id'];
				}
			}
		}
		
		return $result;
	}
	
	function setUserSessionHash($id)
	{
		global $gsValues;
		
		$q = "SELECT * FROM `gs_users` WHERE `id`='".$id."'";
		$r = mysql_query($q);
		
		$row = mysql_fetch_array($r);
		
		$sess_hash = md5($gsValues['PATH_ROOT'].$row['id'].$row['username'].$row['password'].$_SERVER['REMOTE_ADDR']);
		
		$q = "UPDATE gs_users SET `sess_hash`='".$sess_hash."' WHERE `id`='".$id."'";
		$r = mysql_query($q);
		
		$expire = time() + 2592000;
		setcookie("gs_sess_hash", $sess_hash, $expire, '/');
	}
	
	function deleteUserSessionHash($id)
	{
		$q = "UPDATE gs_users SET `sess_hash`='' WHERE `id`='".$id."'";
		$r = mysql_query($q);
		
		$expire = time() + 2592000;
		setcookie("gs_sess_hash","",time()-$expire, '/');
	}
	
	function setUserSession($id)
	{
		global $gsValues;
        	
		$q2 = "UPDATE gs_users SET `ip`='".$_SERVER['REMOTE_ADDR']."', `dt_login`='".gmdate("Y-m-d H:i:s")."' WHERE `id`='".$id."'";
		$r2 = mysql_query($q2);
	}
	
	function setUserSessionSettings($id)
	{
		global $gsValues;
		
		// set user settings
		$_SESSION = array_merge($_SESSION, getUserData($id));
		
		// set language from cookies
		$_SESSION["language"] = $gsValues['LANGUAGE'];
	}
	
	function setUserSessionUnits()
	{
		global $la;
		
		if ($_SESSION["unit_distance"] == 'km')
		{
			$_SESSION["unit_speed_string"]  = $la['U_KPH'];
			$_SESSION["unit_distance_string"]  = $la['U_KM'];
			$_SESSION["unit_height_string"]  = $la['U_M'];
		}
		else if ($_SESSION["unit_distance"] == 'mi')
		{
			$_SESSION["unit_speed_string"]  = $la['U_MPH'];
			$_SESSION["unit_distance_string"]  = $la['U_MI'];
			$_SESSION["unit_height_string"]  = $la['U_FT'];
		}
		else if ($_SESSION["unit_distance"] == 'nm')
		{
			$_SESSION["unit_speed_string"]  = $la['U_KN'];
			$_SESSION["unit_distance_string"]  = $la['U_NM'];
			$_SESSION["unit_height_string"]  = $la['U_FT'];
		}
		
		if ($_SESSION["unit_capacity"] == 'l')
		{
			$_SESSION["unit_capacity_string"]  = $la['U_LITERS'];
		}
		else
		{
			$_SESSION["unit_capacity_string"]  = $la['U_GALLONS'];
		}
		
		if ($_SESSION["unit_temperature"] == 'c')
		{
			$_SESSION["unit_temperature_string"]  = 'C';
		}
		else
		{
			$_SESSION["unit_temperature_string"]  = 'F';
		}
		
		$_SESSION["unit_day_string"] = $la['U_D'];
		$_SESSION["unit_hour_string"] = $la['U_H'];
		$_SESSION["unit_minute_string"] = $la['U_MIN'];
		$_SESSION["unit_second_string"] = $la['U_S'];
	}
	
	function setUserSessionCPanel($id)
	{
		global $gsValues;
		
		if (!isset($_SESSION["cpanel_privileges"]))
		{
			if (($_SESSION["username"] == $gsValues['ADMIN_USERNAME']) || ($_SESSION['privileges'] == 'admin'))
			{
				$_SESSION["cpanel_user_id"] = $id;
				$_SESSION["cpanel_privileges"] = 'admin';
				$_SESSION["cpanel_manager_id"] = 0;
			}
			else if ($_SESSION['privileges'] == 'manager')
			{
				$_SESSION["cpanel_user_id"] = $id;
				$_SESSION["cpanel_privileges"] = 'manager';
				$_SESSION["cpanel_manager_id"] = $id;
			}
			else
			{
				$_SESSION["cpanel_privileges"] = false;
			}
		}
	}
	
	function checkUserSession()
	{
		$file = basename($_SERVER['SCRIPT_NAME']);
		
		if ($file == 'index.php')
		{
			$user_id = getUserIdFromSessionHash();
			
			if($user_id != false)
			{
				setUserSession($user_id);
				setUserSessionSettings($user_id);
				setUserSessionCPanel($user_id);
				Header("Location: tracking.php");
			}
			else
			{
				session_unset();
				session_destroy();
				session_start();
			}
		}
		else
		{
			if (checkUserSession2() == false)
			{
				if (($file == 'tracking.php') || ($file == 'cpanel.php'))
				{
					session_unset();
					session_destroy();
					Header("Location: index.php");
				}
				
				die;
			}
		}
	}
	
	function checkUserSession2()
	{
		global $gsValues;
		
		$result = false;
		
		if (isset($_SESSION["user_id"]) && isset($_SESSION["session"])&& isset($_SESSION["remote_addr"]))
		{
			if (($_SESSION["session"] == md5($gsValues['PATH_ROOT'])) && ($_SESSION["remote_addr"] == md5($_SERVER['REMOTE_ADDR'])))
			{
				$result = true;
			}
		}
		
		return $result;
	}
	
	function checkUserCPanelPrivileges()
	{
		global $gsValues;
		
		if (!isset($_SESSION["cpanel_privileges"]))
		{
			die;
		}
		
		if ($_SESSION["cpanel_privileges"] == false)
		{
			die;
		}
		
		if (($_SESSION["cpanel_privileges"] == 'admin') && ($gsValues['ADMIN_IP'] != ''))
		{
			$admin_ips = explode(",", $gsValues['ADMIN_IP']);	
			if (!in_array($_SERVER['REMOTE_ADDR'], $admin_ips))
			{
				echo 'Your IP is not in Admin list.';
				die;
			}
		}
		
		if ($_SESSION["user_id"] != $_SESSION['cpanel_user_id'])
		{
			setUserSession($_SESSION['cpanel_user_id']);
		}
	}
	
	function getUserData($id)
	{
		$result = array();
		
		$q = "SELECT * FROM `gs_users` WHERE `id`='".$id."'";
		$r = mysql_query($q);
		$row = mysql_fetch_array($r);
		
		$result["user_id"] = $id;
		$result["active"] = $row['active'];
		$result["manager_id"] = $row['manager_id'];
		
		$privileges = explode("|", $row['privileges']);
		$result["privileges"] = $privileges[0];
			
		if ($result["privileges"] == 'subuser')
		{				
			$privileges[1] = explode(",", $privileges[1]);
			$result["privileges_imei"] = '"'.implode('","', $privileges[1]).'"';
			
			$privileges[2] = explode(",", $privileges[2]);
			$result["privileges_zone"] = '"'.implode('","', $privileges[2]).'"';
			
			$privileges[3] = explode(",", $privileges[3]);
			$result["privileges_marker"] = '"'.implode('","', $privileges[3]).'"';
			
			if (!isset($privileges[4])) {$privileges[4] = '';}
			$result["privileges_history"] = $privileges[4];
			
			if (!isset($privileges[5])) {$privileges[5] = '';}
			$result["privileges_reports"] = $privileges[5];
			
			if (!isset($privileges[6])) {$privileges[6] = '';}
			$result["privileges_object_control"] = $privileges[6];
			
			if (!isset($privileges[7])) {$privileges[7] = '';}
			$result["privileges_image_gallery"] = $privileges[7];
			
			if (!isset($privileges[8])) {$privileges[8] = '';}
			$result["privileges_chat"] = $privileges[8];
			
			if (!isset($privileges[9])) {$privileges[9] = '';}
			$privileges[9] = explode(",", $privileges[9]);
			$result["privileges_route"] = '"'.implode('","', $privileges[9]).'"';
		}
		else
		{
			$result["privileges_imei"] = '';
			$result["privileges_marker"] = '';
			$result["privileges_route"] = '';
			$result["privileges_zone"] = '';
			$result["privileges_history"] = 'true';
			$result["privileges_reports"] = 'true';
			$result["privileges_object_control"] = 'true';
			$result["privileges_image_gallery"] = 'true';
			$result["privileges_chat"] = 'true';
		}
		
		$result["username"] = $row['username'];
		$result["email"] = $row['email'];
		$result["info"] = $row['info'];
		$result["obj_add"] = $row['obj_add'];
		$result["obj_num"] = $row['obj_num'];
		$result["obj_dt"] = $row['obj_dt'];
		
		$result["timezone"] = $row['timezone'];
		$result["language"] = $row['language'];
		
		$result["map_rmp"] = $row['map_rmp'];
		$result["map_ts"] = $row['map_ts'];
		
		if($row['map_tc'] == '')
		{
			$result["map_tc"] = '#00FF44';
		}
		else
		{
			$result["map_tc"] = $row['map_tc'];
		}
		
		if($row['map_rc'] == '')
		{
			$result["map_rc"] = '#FF0000';
		}
		else
		{
			$result["map_rc"] = $row['map_rc'];
		}
		
		if($row['map_rhc'] == '')
		{
			$result["map_rhc"] = '#0800FF';
		}
		else
		{
			$result["map_rhc"] = $row['map_rhc'];
		}
		
		if ($row['map_icon'] == '')
		{
			$result["map_icon"] = 'arrow';
		}
		else
		{
			$result["map_icon"] = $row['map_icon'];
		}
		
		$result["sms_gateway_server"] = $row['sms_gateway_server'];
		$result["sms_gateway"] = $row['sms_gateway'];
		$result["sms_gateway_url"] = $row['sms_gateway_url'];
		
		$result["places_markers"] = $row['places_markers'];
		$result["places_routes"] = $row['places_routes'];
		$result["places_zones"] = $row['places_zones'];
		
		$units = explode(",", $row['units']);
		
		$result["unit_distance"] = @$units[0];
		if ($result["unit_distance"] == '')
		{
			$result["unit_distance"] = 'km';
		}
		
		$result["unit_capacity"] = @$units[1];
		if ($result["unit_capacity"] == '')
		{
			$result["unit_capacity"] = 'l';
		}
		
		$result["unit_temperature"] = @$units[2];
		if ($result["unit_temperature"] == '')
		{
			$result["unit_temperature"] = 'c';
		}
		
		return $result;
	}

	function getUserIdFromAPIKey($key)
	{
		$q = "SELECT * FROM `gs_users` WHERE `api_key`='".$key."'";
		$r = mysql_query($q);
		$row = mysql_fetch_array($r);
		
		if($row["api"] == "true")
		{
			return $row["id"];
		}
		else
		{
			return false;	
		}
	}
	
	function convUserTimezone($dt)
	{
		if ($dt != "0000-00-00 00:00:00")
		{
			$dt = date("Y-m-d H:i:s", strtotime($dt.$_SESSION["timezone"]));
		}
		
		return $dt;
	}
	
	function convUserUTCTimezone($dt)
	{
		if (substr($_SESSION["timezone"],0,1) == "+")
		{
			$timezone_diff = str_replace("+", "-", $_SESSION["timezone"]);
		}
		else
		{
			$timezone_diff = str_replace("-", "+", $_SESSION["timezone"]);
		}
		
		return date("Y-m-d H:i:s", strtotime($dt.$timezone_diff));
	}
	
	function checkUserToObjectPrivileges($imei)
	{
		// check privileges
		if ($_SESSION["privileges"] == 'subuser')
		{
			$user_id = $_SESSION["manager_id"];
		}
		else
		{
			$user_id = $_SESSION["user_id"];
		}
		
		$q = "SELECT * FROM `gs_user_trackers` WHERE `user_id`='".$user_id."' AND `imei`='".$imei."'";
		$r = mysql_query($q);
		$row = mysql_fetch_array($r,MYSQL_ASSOC);
		
		if ($row)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function addUser($active, $privileges, $manager_id, $email, $password, $obj_add, $obj_num, $obj_dt)
	{
		global $gsValues, $la;
		
		$result = '';
		
		$email = strtolower($email);
		
		$q = "SELECT * FROM `gs_users` WHERE `email`='".$email."' LIMIT 1";
		$r = mysql_query($q);
		$num = mysql_numrows($r);
		
		if ($num == 0)
		{
			if ($password == '')
			{
				$password = substr(hash('sha1',gmdate('d F Y G i s u')),0,6);
			}
			
			$subject = $la['REGISTRATION_AT'];
			
			$message = $la['HELLO'].",\r\n\r\n";
			$message = $message.$la['THANK_YOU_FOR_REGISTERING_AT']."\r\n\r\n";
			$message = $message.$la['ACCESS_TO_GPS_SERVER_ACCOUNT'].': '.$gsValues['URL_LOGIN']."\r\n\r\n";
			$message = $message.$la['USERNAME'].': '.$email."\r\n";
			$message = $message.$la['PASSWORD'].': '.$password."\r\n";
			
			if (sendEmail($email, $subject, $message))
			{					
				$obj_dt = date("Y-m-d", strtotime(gmdate("Y-m-d").' + '.$obj_dt.' days'));
				
				$q = "INSERT INTO gs_users (	`active`,
								`privileges`,
								`manager_id`,
								`username`, 
								`password`, 
								`email`, 
								`dt_reg`, 
								`obj_add`, 
								`obj_num`, 
								`obj_dt`, 
								`timezone`, 
								`language`,
								`map_rmp`)
								VALUES
								('".$active."',
								'".$privileges."',
								'".$manager_id."',
								'".$email."',
								'".md5($password)."',
								'".$email."',
								'".gmdate("Y-m-d H:i:s")."',
								'".$obj_add."',
								'".$obj_num."',
								'".$obj_dt."',
								'".$gsValues['TIMEZONE']."',
								'".$gsValues['LANGUAGE']."',
								'true'
								)";
								
				$r = mysql_query($q);
				$result = 'OK';
				
				//write log
				writeLog('user_access', 'User registration: successful. E-mail: '.$email);
			}
			else
			{
				$result = $la['CANT_SEND_EMAIL'].' '.$la['CONTACT_ADMINISTRATOR'];	
			}
		}
		else
		{
			$result = $la['THIS_EMAIL_ALREADY_EXISTS'];
		}
		
		return $result;
	}
	
	function delUser($id)
	{
		$q = "DELETE FROM `gs_users` WHERE `id`='".$id."'";
		$r = mysql_query($q);
		
		// delete user sub users
		$q = "DELETE FROM `gs_users` WHERE `privileges` LIKE '%subuser%' AND `manager_id`='".$id."'";
		$r = mysql_query($q);
		
		$q = "DELETE FROM `gs_user_events` WHERE `user_id`='".$id."'";
		$r = mysql_query($q);
		
		$q = "DELETE FROM `gs_user_events_data` WHERE `user_id`='".$id."'";
		$r = mysql_query($q);
		
		$q = "DELETE FROM `gs_user_events_status` WHERE `user_id`='".$id."'";
		$r = mysql_query($q);
		
		$q = "DELETE FROM `gs_user_zones` WHERE `user_id`='".$id."'";
		$r = mysql_query($q);
		
		$q = "DELETE FROM `gs_user_markers` WHERE `user_id`='".$id."'";
		$r = mysql_query($q);
		
		$q = "DELETE FROM `gs_user_trackers` WHERE `user_id`='".$id."'";
		$r = mysql_query($q);
		
		$q = "DELETE FROM `gs_user_tracker_groups` WHERE `user_id`='".$id."'";
		$r = mysql_query($q);
		
		$q = "DELETE FROM `gs_user_tracker_drivers` WHERE `user_id`='".$id."'";
		$r = mysql_query($q);
		
		$q = "DELETE FROM `gs_tracker_cmd_exec` WHERE `user_id`='".$id."'";
		$r = mysql_query($q);
		
		$q = "DELETE FROM `gs_user_cmd` WHERE `user_id`='".$id."'";
		$r = mysql_query($q);
		
		$q = "DELETE FROM `gs_user_reports` WHERE `user_id`='".$id."'";
		$r = mysql_query($q);
	}
	
	function getUserObjectIMEIs($id)
	{
		$result = false;
		
		$q = "SELECT * FROM `gs_user_trackers` WHERE `user_id`='".$id."'";
		$r = mysql_query($q);
		
		while($row = mysql_fetch_array($r,MYSQL_ASSOC))
		{
			$result .= '"'.$row['imei'].'",';
		}
		$result = rtrim($result, ',');
		
		return $result;
	}
	
	function getUserNumberOfMarkers($id)
	{
		$q = "SELECT * FROM `gs_user_markers` WHERE `user_id`='".$id."'";
		$r = mysql_query($q);
		$count = mysql_numrows($r);
		
		return $count;
	}
	
	function getUserNumberOfZones($id)
	{
		$q = "SELECT * FROM `gs_user_zones` WHERE `user_id`='".$id."'";
		$r = mysql_query($q);
		$count = mysql_numrows($r);
		
		return $count;
	}
	
	function getUserNumberOfRoutes($id)
	{
		$q = "SELECT * FROM `gs_user_routes` WHERE `user_id`='".$id."'";
		$r = mysql_query($q);
		$count = mysql_numrows($r);
		
		return $count;
	}
	
	// #################################################
	//  END USER FUNCTIONS
	// #################################################
	
	// #################################################
	// OBJECT FUNCTIONS
	// #################################################
	
	function checkObjectLimitSystem()
	{
		global $gsValues;
		
		if ($gsValues['OBJECT_LIMIT'] == 0)
		{
			return false;
		}
		
		$q = "SELECT * FROM `gs_trackers`";
		$r = mysql_query($q);
		$num = mysql_numrows($r);
		
		if ($num >= $gsValues['OBJECT_LIMIT'])
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function checkObjectLimitUser()
	{
		// check privileges
		if ($_SESSION["privileges"] == 'subuser')
		{
			$user_id = $_SESSION["manager_id"];
		}
		else
		{
			$user_id = $_SESSION["user_id"];
		}
		
		$q = "SELECT * FROM `gs_user_trackers` WHERE `user_id`='".$user_id."'";
		$r = mysql_query($q);
		$num = mysql_numrows($r);
		
		if($num >= $_SESSION["obj_num"])
		{
			return true;
		}
		return false;
	}
	
	function checkObjectExistsSystem($imei)
	{
		$q = "SELECT * FROM `gs_trackers` WHERE `imei`='".$imei."'";
		$r = mysql_query($q);
		$num = mysql_numrows($r);
		if ($num >= 1)
		{
			return true;
		}
		return false;	
	}
	
	function checkObjectExistsUser($imei)
	{
		$q = "SELECT * FROM `gs_user_trackers` WHERE `imei`='".$imei."'";
		$r = mysql_query($q);
		$num = mysql_numrows($r);
		if ($num >= 1)
		{
			return true;
		}
		return false;
	}
	
	function delObjectUser($user_id, $imei)
	{
		$q = "DELETE FROM `gs_user_trackers` WHERE `user_id`='".$user_id."' AND `imei`='".$imei."'";
		$r = mysql_query($q);
		
		$q = "DELETE FROM `gs_user_events_data` WHERE `user_id`='".$user_id."' AND `imei`='".$imei."'";
		$r = mysql_query($q);
		
		$q = "DELETE FROM `gs_user_events_status` WHERE `user_id`='".$user_id."' AND `imei`='".$imei."'";
		$r = mysql_query($q);
	}

	function delObjectSystem($imei)
	{
		global $gsValues;
		
		$q = "DELETE FROM `gs_trackers` WHERE `imei`='".$imei."'";
		$r = mysql_query($q);
		
		$q = "DELETE FROM `gs_rfid_swipe_data` WHERE `imei`='".$imei."'";
		$r = mysql_query($q);
		
		$q = "DELETE FROM `gs_tracker_sensors` WHERE `imei`='".$imei."'";
		$r = mysql_query($q);
		
		$q = "DELETE FROM `gs_tracker_service` WHERE `imei`='".$imei."'";
		$r = mysql_query($q);
		
		$q = "DELETE FROM `gs_user_trackers` WHERE `imei`='".$imei."'";
		$r = mysql_query($q);
		
		$q = "DELETE FROM `gs_user_events_data` WHERE `imei`='".$imei."'";
		$r = mysql_query($q);
		
		$q = "DELETE FROM `gs_user_events_status` WHERE `imei`='".$imei."'";
		$r = mysql_query($q);
		
		$q = "SELECT * FROM `gs_tracker_img` WHERE `imei`='".$imei."'";
  		$r = mysql_query($q);
		
		while($row = mysql_fetch_array($r,MYSQL_ASSOC))
		{
			$q2 = "DELETE FROM `gs_tracker_img` WHERE `img_id`='".$row['img_id']."'";
			$r2 = mysql_query($q2);
			
			$img_file = $gsValues['PATH_ROOT'].'data/img/'.$row['img_file'];
			if(is_file($img_file))
			{
				@unlink($img_file);
			}
		}
		
		$q = "DELETE FROM `gs_tracker_chat` WHERE `imei`='".$imei."'";
		$r = mysql_query($q);
		
		$q = "DROP TABLE gs_tracker_data_".$imei;
		$r = mysql_query($q);
		
		//write log
		writeLog('object_op', 'Delete object: successful. IMEI: '.$imei);
	}
	
	function clearObjectHistory($imei)
	{
		$q = "DELETE FROM `gs_rfid_swipe_data` WHERE `imei`='".$imei."'";
		$r = mysql_query($q);
		
		$q = "DELETE FROM gs_tracker_data_".$imei;
		$r = mysql_query($q);
		
		$q = "DELETE FROM `gs_user_events_data` WHERE `imei`='".$imei."'";
		$r = mysql_query($q);
		
		$q = "DELETE FROM `gs_user_events_status` WHERE `imei`='".$imei."'";
		$r = mysql_query($q);
		
		$q = "UPDATE `gs_trackers` SET  `dt_server`='0000-00-00 00:00:00',
						`dt_tracker`='0000-00-00 00:00:00',
						`lat`='0',
						`lng`='0',
						`altitude`='0',
						`angle`='0',
						`speed`='0',
						`loc_valid`='0',
						`params`=''
						WHERE `imei`='".$imei."'";
		$r = mysql_query($q);
		
		//write log
		writeLog('object_op', 'Clear object history: successful. IMEI: '.$imei);
	}
	
	//update by vetrivel.N
	function createObjectDataTable($imei)
	{
		$q = "CREATE TABLE gs_tracker_data_".$imei."(	dt_server datetime NOT NULL,
								dt_tracker datetime NOT NULL,
								lat double,
								lng double,
								altitude double,
								angle double,
								speed double,
								params varchar(1000) COLLATE utf8_bin NOT NULL,
								 `mileage` double DEFAULT NULL,
								  KEY `idx_date` (`dt_tracker`)
								) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		$r = mysql_query($q);
		
		//write log
		writeLog('object_op', 'Add object: successful. IMEI: '.$imei);
	}
	
	function checkObjectActive($imei)
	{
	global $ms;
		
		$q = "SELECT * FROM `gs_objects` WHERE `imei`='".$imei."'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r,MYSQL_ASSOC);
		
		if ($row['active'] == 'true')
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function getObjectName($user_id, $imei)
	{
		$q = "SELECT * FROM `gs_user_trackers` WHERE `user_id`='".$user_id."' AND `imei`='".$imei."'";
		$r = mysql_query($q);
		$row = mysql_fetch_array($r,MYSQL_ASSOC);
		
		return $row['name'];
	}
	
	function getObjectDriverIbutRFID($params)
	{
		$driver = false;
		
		$ibutrfid = '';
		
		$ibut = paramsParamValue($params, 'ibut');
		$rfid = paramsParamValue($params, 'rfid');
		
		if (($ibut != '') && ($ibut != '0'))
		{
			$ibutrfid = $ibut;
		}
		
		if (($rfid != '') && ($rfid != '0'))
		{
			$ibutrfid = $rfid;
		}
		
		if ($ibutrfid != '')
		{
			$q = "SELECT * FROM `gs_user_tracker_drivers` WHERE LOWER(`driver_ibutrfid`)='".strtolower($ibutrfid)."'";
			$r = mysql_query($q);
			$driver = mysql_fetch_array($r,MYSQL_ASSOC);	
		}
		
		return $driver;
	}
	
	function getObjectDriver($user_id, $imei, $params)
	{
		$driver = false;
		
		$q = "SELECT * FROM `gs_user_trackers` WHERE `user_id`='".$user_id ."' AND `imei`='".$imei."'";
		$r = mysql_query($q);
		$row = mysql_fetch_array($r,MYSQL_ASSOC);
		
		$driver_id = $row['driver_id'];
		
		if ($driver_id == '-1')
		{
			return $driver;
		}
		
		if ($driver_id == '0')
		{
			return getObjectDriverIbutRFID($params);
		}
	       
		$q = "SELECT * FROM `gs_user_tracker_drivers` WHERE `user_id`='".$user_id ."' AND `driver_id`='".$driver_id."'";
		$r = mysql_query($q);
		$driver = mysql_fetch_array($r,MYSQL_ASSOC);
		
		return $driver;
	}
	
	function getObjectOdometer($imei)
	{		
		$q = "SELECT * FROM `gs_trackers` WHERE `imei`='".$imei."'";
		$r = mysql_query($q);
		$row = mysql_fetch_array($r,MYSQL_ASSOC);
		
		return floor($row['odometer']);
	}
	
	function getObjectEngineHours($imei)
	{		
		$q = "SELECT * FROM `gs_trackers` WHERE `imei`='".$imei."'";
		$r = mysql_query($q);
		$row = mysql_fetch_array($r,MYSQL_ASSOC);
		
		return floor($row['engine_hours'] / 60 / 60);
	}
	
	function getObjectFCR($imei)
	{
		global $gsValues;
		
		$q = "SELECT * FROM `gs_trackers` WHERE `imei`='".$imei."'";
		$r = mysql_query($q);
		$row = mysql_fetch_array($r,MYSQL_ASSOC);
		
		// set default fcr if not set in DB
		if ($row['fcr'] == '')
		{
			$row['fcr'] = '0,0,'.$gsValues['FCR_WINTER_PERIOD'];
		}
		
		return $row['fcr'];
	}
	
	function getObjectAccuracy($imei)
	{
		global $gsValues;
		
		$q = "SELECT * FROM `gs_trackers` WHERE `imei`='".$imei."'";
		$r = mysql_query($q);
		$row = mysql_fetch_array($r,MYSQL_ASSOC);
		
		// set default accuracy if not set in DB
		if ($row['accuracy'] == '')
		{
			$row['accuracy'] = $gsValues['ACCURACY'];
		}
		
		$temp = explode(",", $row['accuracy']);
		
		$accuracy = array();
		$accuracy['stops'] = $temp[0];
		$accuracy['min_moving_speed'] = $temp[1];
		$accuracy['min_diff_points'] = $temp[2];
		$accuracy['use_gpslev'] = $temp[3];
		$accuracy['min_gpslev'] = $temp[4];
		$accuracy['use_hdop'] = $temp[5];
		$accuracy['max_hdop'] = $temp[6];
		$accuracy['min_ff'] = $temp[7];
		$accuracy['min_ft'] = $temp[8];
		
		return $accuracy;
	}

	
	function getObjectSensors($imei)
	{
		// get object sensor list
		$q = "SELECT * FROM `gs_tracker_sensors` WHERE `imei`='".$imei."' ORDER BY `name` ASC";
		$r = mysql_query($q);
		
		$sensors = array();
		
		while($row=mysql_fetch_array($r))
		{
			$sensor_id = $row['sensor_id'];
			
			$calibration = json_decode($row['calibration'], true);
			if ($calibration == null)
			{
				$calibration = array();	
			}
			
			$sensors[$sensor_id] = array(	'name' => $row['name'],
							'type' => $row['type'],
							'param' => $row['param'],
							'result_type' => $row['result_type'],
							'text_1' => $row['text_1'],
							'text_0' => $row['text_0'],
							'units' => $row['units'],
							'lv' => $row['lv'],
							'hv' => $row['hv'],
							'formula' => $row['formula'],
							'calibration' => $calibration
							);
		}
		
		return $sensors;
	}
	
	function getObjectService($imei)
	{
		// get object service list
		$q = "SELECT * FROM `gs_tracker_service` WHERE `imei`='".$imei."' ORDER BY `name` ASC";
		$r = mysql_query($q);
		
		$service = array();
		
		while($row=mysql_fetch_array($r))
		{
			$row['odo_interval'] = convDistanceUnits($row['odo_interval'], 'km', $_SESSION["unit_distance"]);
			$row['odo_last'] = convDistanceUnits($row['odo_last'], 'km', $_SESSION["unit_distance"]);
			$row['odo_left_num'] = convDistanceUnits($row['odo_left_num'], 'km', $_SESSION["unit_distance"]);
			
			$row['odo_interval'] = round($row['odo_interval']);
			$row['odo_last'] = round($row['odo_last']);
			$row['odo_left_num'] = round($row['odo_left_num']);
			
			$service_id = $row['service_id'];
			$service[$service_id] = array(	'name' => $row['name'],
							'odo' => $row['odo'],
							'odo_interval' => $row['odo_interval'],
							'odo_last' => $row['odo_last'],
							'engh' => $row['engh'],
							'engh_interval' => $row['engh_interval'],
							'engh_last' => $row['engh_last'],
							'days' => $row['days'],
							'days_interval' => $row['days_interval'],
							'days_last' => $row['days_last'],
							'odo_left' => $row['odo_left'],
							'odo_left_num' => $row['odo_left_num'],
							'engh_left' => $row['engh_left'],
							'engh_left_num' => $row['engh_left_num'],
							'days_left' => $row['days_left'],
							'days_left_num' => $row['days_left_num'],
							'update_last' => $row['update_last']
							);
		}
		
		return $service;
	}
	
	function getObjectSensorValue($params, $sensor)
	{
		$result = array();
		$result['value'] = 0;
		$result['value_full'] = '';
		
		$param_value = paramsParamValue($params, $sensor['param']);
		
		if ($sensor['result_type'] == 'logic')
		{
			if($param_value == 1)
			{
				$result['value'] = $param_value;
				$result['value_full'] = $sensor['text_1'];
			}
			else
			{
				$result['value'] = $param_value;
				$result['value_full'] = $sensor['text_0'];
			}
		}
		else if ($sensor['result_type'] == 'value')
		{		
			if ($sensor['formula'] != '')
			{
				$formula = explode("|", $sensor['formula']);
				
				if ($formula[0] == 'add')
				{
					$param_value = $param_value + $formula[1];
				}
				
				if ($formula[0] == 'sub')
				{
					$param_value = $param_value - $formula[1];
				}
				
				if ($formula[0] == 'mul')
				{
					$param_value = $param_value * $formula[1];
				}
				
				if ($formula[0] == 'div')
				{
					$param_value = $param_value / $formula[1];
				}
			}
			
			// calibration
			$out_of_cal = true;
			
			$calibration = json_decode($sensor['calibration'], true);
			if ($calibration == null)
			{
				$calibration = array();	
			}
			
			if (count($calibration) >= 2)
			{
				// put all X values to separate array
				$x_arr = array();
				
				for ($i=0; $i<count($calibration); $i++)
				{
					$x_arr[] = $calibration[$i]['x'];
				}
			    
				sort($x_arr);
				
				for ($i=0; $i<count($calibration)-1; $i++)
				{
					$x_low = $x_arr[$i];
					$x_high = $x_arr[$i+1];
					
					if (($param_value >= $x_low) && ($param_value <= $x_high))
					{
						// get Y low and high
						$y_low = 0;
						$y_high = 0;
						
						for($j=0; $j<count($calibration); $j++)
						{
							if ($calibration[$j]['x'] == $x_low)
							{
								$y_low = $calibration[$j]['y'];
							}
							
							if ($calibration[$j]['x'] == $x_high)
							{
								$y_high = $calibration[$j]['y'];
							}
						}
						
						// get coeficient
						$a = $param_value - $x_low;
						$b = $x_high - $x_low;
						
						$coef = ($a/$b);
						
						$c = $y_high - $y_low;
						$coef = $c * $coef;
						
						$param_value = $y_low + $coef;
						
						$out_of_cal = false;
						
						break;
					}
				}
			    
				if ($out_of_cal)
				{
					// check if lower than cal
					$x_low = $x_arr[0];
					
					if ($param_value < $x_low)
					{
						for($j=0; $j<count($calibration); $j++)
						{		    
							if ($calibration[$j]['x'] == $x_low)
							{
							    $param_value = $calibration[$j]['y'];
							}
						}
					}
					
					// check if higher than cal
					$x_high = end($x_arr);
					
					if ($param_value > $x_high)
					{		    
						for($j=0; $j<count($calibration); $j++)
						{		    
							if ($calibration[$j]['x'] == $x_high)
							{
							    $param_value = $calibration[$j]['y'];
							}
						}
					}
				}
			}
			
			$param_value = sprintf("%01.2f", $param_value);
			
			$result['value'] = $param_value;
			$result['value_full'] = $param_value.' '.$sensor['units'];
			
		}
		else if ($sensor['result_type'] == 'string')
		{
			$result['value'] = $param_value;
			$result['value_full'] = $param_value;
		}
		else if ($sensor['result_type'] == 'percentage')
		{
			if (($param_value > $sensor['lv']) && ($param_value < $sensor['hv']))
			{
				$a = $param_value - $sensor['lv'];
				$b = $sensor['hv'] - $sensor['lv'];
				
				$result['value'] = floor(($a/$b) * 100);
			}
			else if ($param_value <= $sensor['lv'])
			{
				$result['value'] = 0;
			}
			else if ($param_value >= $sensor['hv'])
			{
				$result['value'] = 100;
			}
			
			$result['value_full'] = $result['value'].' %';
		}
		
		return $result;
	}
	
	function getObjectSensorFromType($imei, $type)
	{
		$result = array();
		
		$q = "SELECT * FROM `gs_tracker_sensors` WHERE `imei`='".$imei."' AND `type`='".$type."'";
		$r = mysql_query($q);
		
		while($sensor=mysql_fetch_array($r,MYSQL_ASSOC))
		{
			$result[] = $sensor;
		}
		
		if (count($result) > 0)
		{
			return $result;
		}
		else
		{
			return false;
		}
	}
	
	function getObjectActivePeriodAvgDate($id)
        {
                $total_days = 0;
                $count = 0;
                
                $q = "SELECT * FROM `gs_trackers` WHERE `imei` IN (".getUserObjectIMEIs($id).")";
		$r = mysql_query($q);
                
                while($row=mysql_fetch_array($r))
		{
                        //if($row['active'] == 'true')
                        //{
                                $today = strtotime(gmdate('Y-m-d'));
                                $active_dt = strtotime($row['active_dt']);
                                
                                $diff_days = round(($active_dt - $today) / 86400);
                                
                                // check if not less than 0 days
                                //if ($diff_days > 0)
                                //{
                                        $total_days += $diff_days;
                                //}
                                
                                $count++;
                        //}     
		}
                
                $total_days = round($total_days/$count);
		
		$date_from_today = date('Y-m-d', strtotime(gmdate('Y-m-d'). ' + '.$total_days.' days'));
		
		return $date_from_today;
        }
	
	// #################################################
	// END OBJECT FUNCTIONS
	// #################################################
	
	// #################################################
	// MATH FUNCTIONS
	// #################################################
	
	function convSpeedUnits($val, $from, $to)
	{
		return floor(convDistanceUnits($val, $from, $to));
	}
	
	function convDistanceUnits($val, $from, $to)
	{
		if ($from == 'km')
		{
			if ($to == 'mi')
			{
				$val = $val * 0.621371;
			}
			else if ($to == 'nm')
			{
				$val = $val * 0.539957;
			}
		}
		else if ($from == 'mi')
		{
			if ($to == 'km')
			{
				$val = $val * 1.60934;
			}
			else if ($to == 'nm')
			{
				$val = $val * 0.868976;
			}
		}
		else if ($from == 'nm')
		{
			if ($to == 'km')
			{
				$val = $val * 1.852;
			}
			else if ($to == 'nm')
			{
				$val = $val * 1.15078;
			}
		}
		
		return $val;	
	}
	
	function convAltitudeUnits($val, $from, $to)
	{
		if ($from == 'km')
		{
			if (($to == 'mi') || ($to == 'nm')) // to feet
			{
				$val = floor($val * 3.28084);
			}
		}
		
		return $val;
	}
	
	//function convTempUnits($val, $from, $to)
	//{
	//	
	//}
	
	function getTimeDetails($sec)
	{
		$seconds = 0;
 		$hours   = 0;
 		$minutes = 0;
		
		if($sec % 86400 <= 0){$days = $sec / 86400;}
		if($sec % 86400 > 0)
		{
			$rest = ($sec % 86400);
			$days = ($sec - $rest) / 86400;
     		if($rest % 3600 > 0)
			{
				$rest1 = ($rest % 3600);
				$hours = ($rest - $rest1) / 3600;
        		if($rest1 % 60 > 0)
				{
					$rest2 = ($rest1 % 60);
           		$minutes = ($rest1 - $rest2) / 60;
           		$seconds = $rest2;
        		}
        		else{$minutes = $rest1 / 60;}
     		}
     		else{$hours = $rest / 3600;}
		}
		
		if($days > 0){$days = $days.' d ';}
		else{$days = false;}
		if($hours > 0){$hours = $hours.' h ';}
		else{$hours = false;}
		if($minutes > 0){$minutes = $minutes.' m ';}
		else{$minutes = false;}
		$seconds = $seconds.' s';
		
		return $days.''.$hours.''.$minutes.''.$seconds;
	}
	
	function getTimeDetails_dailyboearding($sec)
	{
		$seconds = 0;
 		$hours   = 0;
 		$minutes = 0;
		
		if($sec % 86400 <= 0){$days = $sec / 86400;}
		if($sec % 86400 > 0)
		{
			$rest = ($sec % 86400);
			$days = ($sec - $rest) / 86400;
     		if($rest % 3600 > 0)
			{
				$rest1 = ($rest % 3600);
				$hours = ($rest - $rest1) / 3600;
        		if($rest1 % 60 > 0)
				{
					$rest2 = ($rest1 % 60);
           		$minutes = ($rest1 - $rest2) / 60;
           		$seconds = $rest2;
        		}
        		else{$minutes = $rest1 / 60;}
     		}
     		else{$hours = $rest / 3600;}
		}
		
		if($days > 0){$days = $days;}
		else{$days = false;}
		if($hours > 0){$hours = $hours;}
		else{$hours = false;}
		if($minutes > 0){$minutes = $minutes;}
		else{$minutes = false;}
		$seconds = $seconds;
		
		return (($days*24)+$hours).'.'.$minutes;
	}
	
	
	function getTimeDifferenceDetails($start_date, $end_date)
	{
		$diff = strtotime($end_date)-strtotime($start_date);
		return getTimeDetails($diff);
	}
	
	function getTimeDifferenceDetails_dailyboearding($start_date, $end_date)
	{
		$diff = strtotime($end_date)-strtotime($start_date);
		return getTimeDetails_dailyboearding($diff);
	}

	function getLengthBetweenCoordinates($lat1, $lon1, $lat2, $lon2)
	{
		$theta = $lon1 - $lon2; 
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)); 
		$dist = acos($dist); 
		$dist = rad2deg($dist); 
		$km = $dist * 60 * 1.1515 * 1.609344;
		
		return sprintf("%01.6f", $km);
	}
	
	function getAngle($lat1, $lng1, $lat2, $lng2)
	{
		$angle = (rad2deg(atan2(sin(deg2rad($lng2) - deg2rad($lng1)) * cos(deg2rad($lat2)), cos(deg2rad($lat1)) * sin(deg2rad($lat2)) - sin(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($lng2) - deg2rad($lng1)))) + 360) % 360;
		
		return floor($angle);
	}
	
	function isPointInPolygon($vertices, $lat, $lng)
	{
		$polyX = array();
		$polyY = array();
		
		$ver_arr = explode(',', $vertices);
		
		$polySides = 0;
		
		$i = 0;
		
		while ($i < sizeof($ver_arr))
		{
			$polyX[] = $ver_arr[$i+1];
			$polyY[] = $ver_arr[$i];
			
			$i+=2;
			$polySides++;
		}
		
		$j = $polySides-1 ;
		$oddNodes = 0;
		
		for ($i=0; $i<$polySides; $i++)
		{
			if ($polyY[$i]<$lat && $polyY[$j]>=$lat || $polyY[$j]<$lat && $polyY[$i]>=$lat)
			{
				if ($polyX[$i]+($lat-$polyY[$i])/($polyY[$j]-$polyY[$i])*($polyX[$j]-$polyX[$i])<$lng)
				{
					$oddNodes=!$oddNodes;
				}
			}
			$j=$i;
		}
		
		return $oddNodes;
	}
	
	function isPointOnLine($points, $lat, $lng)
        {
                $lineX = array();
		$lineY = array();
		
                $points_num = 0;
		
		$points_arr = explode(',', $points);
		
		$i = 0;
		while ($i < sizeof($points_arr))
		{
			$lineX[] = $points_arr[$i];
			$lineY[] = $points_arr[$i+1];
			
			$i+=2;
			$points_num++;
		}
                
                for ($i=0; $i<$points_num-1; $i++)
		{
			// line segment
			$a['lat'] = $lineX[$i];
			$a['lng'] = $lineY[$i];
			$b['lat'] = $lineX[$i+1];
			$b['lng'] = $lineY[$i+1];
			
			// point
			$c['lat'] = $lat;
			$c['lng'] = $lng;
			
			$dist = getGeoDistancePointToSegment($a, $b, $c);
			$dist = sprintf('%0.6f', $dist);
                        
			if (!isset($distance))
			{
				$distance = $dist;
			}
			else
			{
				if ($distance > $dist)
				{
					$distance = $dist;
				}	
			}
                }
                
                return $distance;    
        }
	
	function getHeightFromBaseTriangle($ab, $ac, $bc)
	{
		// find $s (semiperimeter) for Heron's formula
		$s = ($ab + $ac + $bc) / 2;
		
		// Heron's formula - area of a triangle
		$area = sqrt($s * ($s - $ab) * ($s - $ac) * ($s - $bc));
		
		// find the height of a triangle - ie - distance from point to line segment
		$height = $area / (.5 * $ab);
		
		return $area;
	}
	
	function getAnglesFromSides($ab, $bc, $ac)
	{
		$a = $bc;
		$b = $ac;
		$c = $ab;
		
		$a_div = 2 * $b * $c;
		if ($a_div == 0)
		{
			$a_div = 1;
		}
		
		$b_div = 2 * $c * $a;
		if ($b_div == 0)
		{
			$b_div = 1;
		}
		
		$c_div = 2 * $a * $b;
		if ($c_div == 0)
		{
			$c_div = 1;
		}
		
		$angle['a'] = rad2deg(acos((pow($b,2) + pow($c,2) - pow($a,2)) / $a_div));
		$angle['b'] = rad2deg(acos((pow($c,2) + pow($a,2) - pow($b,2)) / $b_div));
		$angle['c'] = rad2deg(acos((pow($a,2) + pow($b,2) - pow($c,2)) / $c_div));
		
		return $angle;		
	}
	
	function getGeoDistancePointToSegment($a, $b, $c)
	{
		$ab = getLengthBetweenCoordinates($a['lat'], $a['lng'], $b['lat'], $b['lng']); // base or line segment
		$ac = getLengthBetweenCoordinates($a['lat'], $a['lng'], $c['lat'], $c['lng']);
		$bc = getLengthBetweenCoordinates($b['lat'], $b['lng'], $c['lat'], $c['lng']);
		
		$angle = getAnglesFromSides($ab, $bc, $ac);
		
		if($ab + $ac == $bc) // then points are collinear - point is on the line segment
		{
			return 0;
		}
		elseif($angle['a'] <= 90 && $angle['b'] <= 90) // A or B are not obtuse - return height as distance
		{
			return getHeightFromBaseTriangle($ab, $ac, $bc);
		}
		else // A or B are obtuse - return smallest side as distance
		{
			return ($ac > $bc) ? $bc : $ac;
		}
	}
	
	// #################################################
	// END MATH FUNCTIONS
	// #################################################
	
	// #################################################
	// STRING/ARRAY FUNCTIONS
	// #################################################
	
	function paramsMerge($old, $new)
	{
		$old_params = paramsToArray($old);
		$new_params = paramsToArray($new);
		
		$arr_params = array_merge($old_params, $new_params);
		$params = '';
		
		foreach ($arr_params as $key => $value)
		{
			$params .= $key.'='.$value.'|';
		}
		
		return $params;
	}
	
	function paramsToList($str)
	{
		$params = explode("|", $str);
		$arr_params = array();
		
		for ($i = 0; $i < count($params)-1; ++$i)
		{
			$param = explode("=", $params[$i]);
			array_push($arr_params, $param[0]);
		}
		
		return implode(',', $arr_params);
	}
	
	function paramsToArray($params)
	{
		$params = explode("|", $params);
		$arr_params = array();
		
		for ($i = 0; $i < count($params)-1; ++$i)
		{
			$param = explode("=", $params[$i]);
			$arr_params[$param[0]] = $param[1];
		}
		
		return $arr_params;
	}
	
	function paramsParamValue($params, $param)
	{
		$result;
		
		$params = paramsToArray($params);
		
		if (array_key_exists($param, $params))
		{
			if ($params[$param] != '')
			{
				$result = $params[$param];
			}
			else
			{
				$result = 0;
			}
		}
		else
		{
			$result = 0;
		}
		
		return $result;
	}

	function searchString($str, $findme)
	{
		return preg_match('/'.$findme.'/',$str);
	}
	
	function truncateString($text, $chars)
	{
		if (strlen($text) > $chars)
		{
			$text = substr($text,0,$chars).'...';
		}
		return $text;
	}
	
	function generatorTag()
	{
		echo '<meta name="generator" content="GPS-server.net Server software" />';
	}
	
	// #################################################
	// END STRING/ARRAY FUNCTIONS
	// #################################################
	
	// #################################################
	// GEOCODER FUNCTIONS
	// #################################################

	function getGeocoderCache($lat, $lng)
	{
		global $ms;
		$result = '';
		
		// set lat and lng search ranges
		$lat_a = $lat - 0.000050;
		$lat_b = $lat + 0.000050;
		
		$lng_a = $lng - 0.000050;
		$lng_b = $lng + 0.000050;
		
		$q = "SELECT * FROM gs_geocoder_cache WHERE (lat BETWEEN ".$lat_a." AND ".$lat_b.") AND (lng BETWEEN ".$lng_a." AND ".$lng_b.")";
		$r = mysqli_query($ms,$q);
		$row = mysqli_fetch_array($r);
		
		if ($row)
		{
			$id = $row['id'];
			$result = $row['address'];			
			//$count = $row['count'] + 1;
			
			//$q = 'UPDATE gs_geocoder_cache SET `count`="'.$count.'" WHERE id="'.$id.'"';
			//$r = mysqli_query($ms,$q);
		}
		
		return $result;
	}
	
	function insertGeocoderCache($lat, $lng, $address)
	{
		global $ms;
		if (($lat == '') || ($lng == '') || ($address == ''))
		{
			return;
		}
		
		$q = "INSERT INTO `gs_geocoder_cache`(	`lat`,
							`lng`,
							`address`)
							VALUES
							('".$lat."',
							'".$lng."',
							'".$address."')";
		$r = mysqli_query($ms,$q);
	}
	
	// #################################################
	// END GEOCODER FUNCTIONS
	// #################################################
	
	// #################################################
	// LANGUAGE FUNCTIONS
	// #################################################
	
	function loadLanguage($lng)
	{
		global $la, $gsValues;
		
		// always load main english language to prevet error if something is not translated in another language
		include ($gsValues['PATH_ROOT'].'lng/english/lng_main.php');
		
		// load another language
		if ($lng != 'english')
		{
			$lng = $gsValues['PATH_ROOT'].'lng/'.$lng.'/lng_main.php';
			
			if (file_exists($lng))
			{
				include($lng);
			}
		}
	}
	
	function getLanguageList()
	{
		global $gsValues;
		
		$result = '';
		
		foreach ($gsValues['LANGUAGES'] as $value)
		{        
		    // language list in settings dialog
		    $result .= '<option value="'.$value.'">'.ucfirst($value).'</option>';
		}
		
		return $result;
	}
	
	// #################################################
	// END LANGUAGE FUNCTIONS
	// #################################################
	
	// #################################################
	// END LOG FUNCTIONS
	// #################################################
	
	function writeLog($log, $log_data)
	{
		global $gsValues;
		
		$file = gmdate("Y_m").'_'.$log.'.php';
		$path = $gsValues['PATH_ROOT'].'logs/'.$file;
		
		if (!file_exists($path))
		{
			$str = "<?\r\n";
			$str .= "session_start();\r\n";
			$str .= "include ('../init.php');\r\n";
			$str .= "include ('../func/fn_common.php');\r\n";
			$str .= "checkUserSession();\r\n";
			$str .= "checkUserCPanelPrivileges();\r\n";
			$str .= "header('Content-Type:text/plain');\r\n";
			$str .= "?>\r\n";
			
			file_put_contents($path, $str, FILE_APPEND);
		}
		
		$str = '['.gmdate("Y-m-d H:i:s").'] '.$_SERVER['REMOTE_ADDR'].' ';
		
		if (isset($_SESSION["user_id"]) && isset($_SESSION["username"]))
		{
			$str .= '['.$_SESSION["user_id"].']'.$_SESSION["username"].' ';	
		}
		
		$str .= '- '.$log_data."\r\n";
		
		file_put_contents($path, $str, FILE_APPEND);
	}
	
	// #################################################
	// END LOG FUNCTIONS
	// #################################################
	
	  //Newly added by SelvaG
	function getIFSData($imei, $accuracy, $fuel_sensors, $dtf, $dtt)
	{
		//if (!checkUserToObjectPrivileges($imei))
		//{
		//	return;
		//}
		
		$ifs_data = array();
		
		$q = "SELECT DISTINCT dt_tracker, speed, params,mileage FROM `gs_tracker_data_".$imei."` 
                    WHERE dt_tracker BETWEEN '".$dtf."' AND '".$dtt."' ORDER BY dt_tracker ASC";
					
		$r = mysql_query($q);
        
        $sensor = $fuel_sensors[0];
		
		while($route_data=mysql_fetch_array($r,MYSQL_ASSOC))
		{
		  
            $data = getObjectSensorValue($route_data['params'], $sensor);
          
			$dt_tracker = convUserTimezone($route_data['dt_tracker']);
			$speed = $route_data['speed'];
			//update by vetrivel.N
			$mileage = $route_data['mileage'];
            if($speed == null){
                $speed = 0;
            }             
			$acc = paramsParamValue($route_data['params'], 'acc');
            if($acc == null){
                $acc = 0;
            }    
            //$fuel = paramsParamValue($route_data['params'], 'fuel1');
            $fuel = $data['value'];
            if($fuel == null){
                $fuel = 0;
            }            
            $fuel_ltrs = paramsParamValue($route_data['params'], 'fuel1');
            if($fuel_ltrs == null){
                $fuel_ltrs = 0;
            }
            $temp1 = paramsParamValue($route_data['params'], 'temp1');
            
            if ($temp1 == null){
                $temp1 = 0;
            }
            
			
			if($_SESSION["unit_distance"] == "mi")
			{
				$speed = floor($speed / 1.609344);
			}
			
			$ifs_data[] = array( "date" => $dt_tracker,
						"speed" => $speed,
						"acc" => $acc,
                        "fuel" => $fuel,
                        "fuelltrs" => $fuel_ltrs,
                        "temp1" => $temp1,
						"mileage" => $mileage);
		}
		
		return $ifs_data;
	}
    
    //Newly added by SelvaG
	function getBoxVolume($sensor)
	{
		$result="0";
		
		if ($sensor['result_type'] == 'logic')
		{
            
		}
		else if ($sensor['result_type'] == 'value')
		{		
			if ($sensor['formula'] != '')
			{
				$formula = explode("|", $sensor['formula']);
				
				if ($formula[0] == 'add')
				{
                    
				}
				
				if ($formula[0] == 'sub')
				{
					
				}
				
				if ($formula[0] == 'mul')
				{
                    $result = 100 * $formula[1];
				}
				
				if ($formula[0] == 'div')
				{
					
				}
			}
		}
		return $result;
	}    

//Code Modified Begin By Vetrivel.NR
	
	function getObjectFromName($imei)
	{
		global $ms;
		$q = "SELECT * FROM `gs_objects` WHERE replace(name,' ','')='".$imei."'";
		$r = mysqli_query($ms, $q);
		if($row = mysqli_fetch_array($r))
		{
			return $row;
		}
		else
		{
			return false;
		}
	}
	
	//Code Modified End By Vetrivel.NR

?>
