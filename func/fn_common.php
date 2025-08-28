<?


	//code done by vetrivel.N
	
	/**
	 * Returns an encrypted & utf8-encoded
	 */
	
function get_allsensor_calibration($imei)
	{
		global $ms;
		$sensor_all=array();
		
		$q="SELECT * FROM gs_object_cal_sensor where imei='".$imei."' order by param_id";
		$r=mysqli_query($ms,$q);
		$col=0;
		if(mysqli_num_rows($r)>0)
		{
			while ($row=mysqli_fetch_assoc($r)) 
			{	
				$sensor_data=array();
				$row['calibration_data'] = json_decode($row['calibration_data'],true);
				$row['calibration_data'] = array_orderby($row['calibration_data'], 'level', SORT_ASC);

				$sensor_data[] = $row['calibration_data'];
				$col = $col + 1 ;
				for($i=0;$i<count($row['calibration_data']);$i++)
				{
					if($row['calibration_data'][$i]['option']=='empty')
					{
						$sensor_all[$row["param_id"]]["empty"] = $row['calibration_data'][$i];
					}
					else if($row['calibration_data'][$i]['option']=='full') 
					{
						$sensor_all[$row["param_id"]]["full"] = $row['calibration_data'][$i];
					}
				}
				$sensor_all[$row["param_id"]]["data"] = $row['calibration_data'];
			}
		}
		
		
		return $sensor_all;
	}
	
function get_sensor_calibration($imei,$paramid)
{
	global $ms;
	$sensor_all=array();
	$sensor_data=array();
	$q="SELECT * FROM gs_object_cal_sensor where imei='".$imei."' and param_id='".$paramid."'";
	$r=mysqli_query($ms,$q);
	$col=0;
	if(mysqli_num_rows($r)>0)
	{
		while ($row=mysqli_fetch_assoc($r)) 
		{		
			$row['calibration_data'] = json_decode($row['calibration_data'],true);
			$sensor_data[$col] =$row['calibration_data'];
			$col = $col + 1 ;
			for($i=0;$i<count($row['calibration_data']);$i++)
			{
				if($row['calibration_data'][$i]['option']=='empty')
				{
					$sensor_all["empty"] = $row['calibration_data'][$i];
				}
				else if($row['calibration_data'][$i]['option']=='full') 
				{
					$sensor_all["full"] = $row['calibration_data'][$i];
				}
			}
		}
	}
	
	$sensor_all["data"] = $sensor_data[0];
	return $sensor_all;
}
	

function get_liter($rawhz,$sensor_all)
{
	$dempty = $sensor_all["empty"]["hz"];
	$dfull = $sensor_all["full"]["hz"];
	$ilength = $sensor_all["full"]["level"];
	//$mm=$ilength*$dfull*($dempty/$rawhz-1)/($dempty-$dfull);
        if($rawhz>=$dempty && $rawhz<=$dfull)
        {
		$d4 = ($dempty == $dfull || $rawhz == 0.0) ? 0.0 : ((($ilength) * $dfull) * (($dempty / $rawhz) - 1.0)) / ($dempty - $dfull);
        return round($d4,2);
        }
        else
        {
        $d4 = ($dempty == $dfull || $rawhz == 0.0) ? 0.0 : ((($ilength) * $dfull) * (($dempty / $rawhz) - 1.0)) / ($dempty - $dfull);
        return round($d4,2);
        //return (-1);
        }
	
    }
	
	function get_final_ltr($rawlevel,$rawhz,$sensor_all_info)
	{
		$empty = $sensor_all_info["empty"];
		$full = $sensor_all_info["full"];
		$ilength = $sensor_all_info["full"]["level"];
		$sensor_all = $sensor_all_info["data"];
		$nl = 0;
		if(count($sensor_all)>=2)
		{
			$b = count($sensor_all);
			$b2 = count($sensor_all);
			
			$i2 = 0;
			if ($rawlevel >= ($empty["level"] * 0.1)) 
			{
				$i3 = $b2 - 1;
				if ($rawlevel <=  $sensor_all[$i3]["level"] ) 
				{
					$i = 0;
                    while ($i2 < $b2) 
					{
                        $i = $i2 + 1;
                        if ( ($rawlevel >= $sensor_all[$i2]["level"]) && ($rawlevel <= $sensor_all[$i]["level"])) 
						{
                            break;
                        }
                        $i2 = $i;
                    }
				}
				else
				{
					$i2 = $b2 - 2;
                    $i = $i3;
				}
			}
			else
			{
				 $i = 1;
			}
		
			$d3 = $sensor_all[$i2]["volum"] * 0.1;
            $d4 = $sensor_all[$i2]["level"] * 0.1;
            $nl = $d3 + (( ($sensor_all[$i]["volum"] - $d3) * ($rawlevel - $d4)) / ($sensor_all[$i]["level"] - $d4));
            $d2 = 0.0;
		}
				
		return round($nl,2);
	}
	
	function get_liters($rawhz,$dempty,$dfull,$ilength)
	{
		//echo "raw". $rawhz." ehz ". $dempty." fhz ". $dfull." fln ". $ilength;
		//$dempty = $sensor_all["empty"]["hz"];
		//$dfull = $sensor_all["full"]["hz"];
		//$ilength = $sensor_all["full"]["level"];
		        
		$d4 = ($dempty == $dfull || $rawhz == 0.0) ? 0.0 : ((($ilength) * $dfull) * (($dempty / $rawhz) - 1.0)) / ($dempty - $dfull);
        return round($d4,2);
		
    }
	
	function get_final_ltrs($rawlevel,$rawhz,$sensor_all_info)
	{
		
		$empty = $sensor_all_info["empty"];
		$full = $sensor_all_info["full"];
		$ilength = $sensor_all_info["full"]["level"];
		$sensor_all = $sensor_all_info["data"];
		$nl = -1;
		if ($rawhz >= ($empty["hz"]) || $rawhz<=($full["hz"])) 
		{
			return -1;
		}
		if(count($sensor_all)>=2)
		{
			$b = count($sensor_all);
			$b2 = count($sensor_all);
			
			$i2 = 0;
			if ($rawhz <= ($empty["hz"])) 
			{

					
				$i3 = $b2 - 1;
				if ($rawhz >=  $sensor_all[$i3]["hz"] ) 
				{

					$i = 0;
                    while ($i2 < $b2) 
					{
                        $i = $i2 + 1;
                        if ( ($rawhz <= $sensor_all[$i2]["hz"]) && ($rawhz >= $sensor_all[$i]["hz"])) 
						{
                            break;
                        }
                        $i2 = $i;
                    }
				}
				else
				{
					$i2 = $b2 - 2;
                    $i = $i3;
				}
			}
			else
			{
				 $i = 1;
			}
			
			
		
			$rawlevel = get_liters($rawhz,$sensor_all[0]["hz"],$sensor_all[$i]["hz"],$sensor_all[$i]["level"]);
			//Fuel Ltrs ={[(Max Ltrs- Minimum Ltrs)/(Max mm- Minimum mm)]* (Current mm- Minimum mm) }+ Minimum Ltrs
			
			//$d3 = $sensor_all[$i2]["volum"] * 0.1;
            //$d4 = $sensor_all[$i2]["level"] * 0.1;
            //$nl = $d3 + (( ($sensor_all[$i]["volum"] - $d3) * ($rawlevel - $d4)) / ($sensor_all[$i]["level"] - $d4));
            
			$minltr = $sensor_all[$i2]["volum"];
            $minmm = $sensor_all[$i2]["level"] ;
            
            $maxltr = $sensor_all[$i]["volum"];
            $maxmm = $sensor_all[$i]["level"];
            //var nl = minltr + ( ((maxltr-minltr)/ (maxmm-minmm)) * (rawlevel-minmm));
            
            $nl = $minltr + ( (($maxltr-$minltr)/ ($maxmm-$minmm)) * ($rawlevel-$minmm));
            $d2 = 0.0;
		}
				
		return array(round($nl,2),$rawlevel);
	}
	
	function array_orderby()
	{
		$args = func_get_args();
		$data = array_shift($args);
		foreach ($args as $n => $field) {
			if (is_string($field)) {
				$tmp = array();
				foreach ($data as $key => $row)
					$tmp[$key] = $row[$field];
				$args[$n] = $tmp;
				}
		}
		$args[] = &$data;
		call_user_func_array('array_multisort', $args);
		return array_pop($args);
	}
	
	function encrypt($pure_string ) {
	
		$iv = "1234567812345678";
		$password = "NightCrawler";
		$method = "aes-128-cbc";
		$encrypted = openssl_encrypt($pure_string, $method, $password,false, $iv);
		$encrypted=ascii2hex($encrypted);
		return $encrypted;
	}
	
	
	/**
	 * Returns decrypted original string
	 */
	function decrypt($encrypted_string) {
		$iv = "1234567812345678";
		$password = "NightCrawler";
		$method = "aes-128-cbc";
		$encrypted_string=hex2ascii($encrypted_string);
		$decrypted = openssl_decrypt($encrypted_string, $method, $password,false, $iv);
		return $decrypted;
	}
	
	function ascii2hex($ascii) {
		$hex = '';
		for ($i = 0; $i < strlen($ascii); $i++) {
			$byte = strtoupper(dechex(ord($ascii[$i])));
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
	
	//code done by vetrivel.N

	// #################################################
	//  CPANEL FUNCTIONS
	// #################################################
	
	function checkCPanelToUserPrivileges($id)
	{
		global $ms;
		
		if ($_SESSION["cpanel_privileges"] == 'manager')
		{
			$q = "SELECT * FROM `gs_users` WHERE `id`='".$id."'";
			$r = mysqli_query($ms, $q);
			$row = mysqli_fetch_array($r);
			
			if ($row["manager_id"] != $_SESSION["cpanel_manager_id"])
			{
				die;
			}
		}
	}
	
	function checkCPanelToObjectPrivileges($imei)
	{
		global $ms, $la;
		
		if ($_SESSION["cpanel_privileges"] == 'manager')
		{
			$q = "SELECT * FROM `gs_objects` WHERE `imei`='".$imei."'";
			$r = mysqli_query($ms, $q);
			$row = mysqli_fetch_array($r);
			
			if ($row["manager_id"] != $_SESSION["cpanel_manager_id"])
			{
				die;
			}
		}
	}
	
	// #################################################
	//  END CPANEL FUNCTIONS
	// #################################################
	
	// #################################################
	//  PASSWORD, API, IDENTIFIER FUNCTIONS
	// #################################################
	
	function genAccountPassword()
	{
		return substr(hash('sha1',gmdate('d F Y G i s u')),0,6);
	}
	
	function genAccountRecoverToken($email)
	{
		global $ms;
		
		while(true)
		{
			$token = strtoupper(md5(rand().$email.gmdate("Y-m-d H:i:s").rand()));
			
			$q = "SELECT * FROM `gs_user_account_recover` WHERE `token`='".$token."'";
			$r = mysqli_query($ms, $q);
			$num = mysqli_num_rows($r);
			
			if ($num == 0)
			{
				return $token;
			}	
		}
	}
	
	function genServerAPIKey()
	{
		global $ms, $gsValues;
		
		$api_key = '';
		
		if ($gsValues['HW_KEY'] != '')
		{
			$api_key = strtoupper(md5(rand().$gsValues['HW_KEY'].gmdate("Y-m-d H:i:s").rand()));	
		}
		
		return $api_key;
	}
	
	function genUserAPIKey($email)
	{
		global $ms;
		
		while(true)
		{
			$api_key = strtoupper(md5(rand().$email.gmdate("Y-m-d H:i:s").rand()));
			
			$q = "SELECT * FROM `gs_users` WHERE `api_key`='".$api_key."'";
			$r = mysqli_query($ms, $q);
			$num = mysqli_num_rows($r);
			
			if ($num == 0)
			{
				return $api_key;
			}	
		}
	}
	
	function genSMSGatewayIdn($email)
	{
		global $ms, $gsValues;
		
		while(true)
		{
			$sms_idn = strtoupper(md5(rand().$email.gmdate("Y-m-d H:i:s").rand()));
			
			$sms_idn = preg_replace("/[^0-9]/", "", $sms_idn);
			
			$sms_idn = substr($sms_idn.$sms_idn, 0, 20);
			
			$q = "SELECT * FROM `gs_users` WHERE `sms_gateway_identifier`='".$sms_idn."'";
			$r = mysqli_query($ms, $q);
			$num = mysqli_num_rows($r);
			
			if (($num == 0) && ($sms_idn != $gsValues['SMS_GATEWAY_IDENTIFIER']))
			{
				return $sms_idn;
			}	
		}
	}
	
	// #################################################
	//  END PASSWORD, API, IDENTIFIER FUNCTIONS
	// #################################################
	
	// #################################################
	// USER FUNCTIONS
	// #################################################
	
	function getUserIdFromAU($au)
	{
		global $ms, $gsValues;
		
		$result = false;
		
		$q = "SELECT * FROM `gs_users` WHERE `privileges` LIKE '%subuser%' and `privileges` LIKE '%".$au."%'";
		$r = mysqli_query($ms, $q);
		
		if ($row = mysqli_fetch_array($r))
		{
			$privileges = json_decode($row['privileges'],true);
			
			if ($privileges['type'] == 'subuser')
			{
				if ($privileges['au_active'] == true)
				{
					if ($privileges['au'] == $au)
					{
						if ($row['active'] == "true")
						{
							$result = $row['id'];	
						}
					}
				}
			}
		}
		
		return $result;
	}
	
	function getUserIdFromSessionHash()
	{
		global $ms, $gsValues;
		
		$result = false;
		
		if (isset($_COOKIE['gs_sess_hash']))
		{
			$sess_hash = $_COOKIE['gs_sess_hash'];
			
			$q = "SELECT * FROM `gs_users` WHERE `sess_hash`='".$sess_hash."'";
			$r = mysqli_query($ms, $q);
			
			if ($row = mysqli_fetch_array($r))
			{
				$sess_hash_check = md5($gsValues['PATH_ROOT'].$row['id'].$row['username'].$row['password']);
				
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
		global $ms, $gsValues;
		
		$q = "SELECT * FROM `gs_users` WHERE `id`='".$id."'";
		$r = mysqli_query($ms, $q);
		
		$row = mysqli_fetch_array($r);
		
		$sess_hash = md5($gsValues['PATH_ROOT'].$row['id'].$row['username'].$row['password']);
		
		$q = "UPDATE gs_users SET `sess_hash`='".$sess_hash."' WHERE `id`='".$id."'";
		$r = mysqli_query($ms, $q);
		
		$expire = time() + 2592000;
		setcookie("gs_sess_hash", $sess_hash, $expire, '/', null, null, true);
	}
	
	function deleteUserSessionHash($id)
	{
		global $ms;
		
		$q = "UPDATE gs_users SET `sess_hash`='' WHERE `id`='".$id."'";
		$r = mysqli_query($ms, $q);
		
		$expire = time() + 2592000;
		setcookie("gs_sess_hash","",time()-$expire, '/');
	}
	
	function setUserSession($id)
	{
		global $ms, $gsValues;
		
		$_SESSION["user_id"] = $id;
		$_SESSION["session"] = md5($gsValues['PATH_ROOT']);
		$_SESSION["remote_addr"] = md5($_SERVER['REMOTE_ADDR']);
		loginuserreportlist($id);
		$q2 = "UPDATE gs_users SET `ip`='".$_SERVER['REMOTE_ADDR']."', `dt_login`='".gmdate("Y-m-d H:i:s")."' WHERE `id`='".$id."'";
		$r2 = mysqli_query($ms, $q2);
	}
	
	function setUserSessionSettings($id)
	{
		global $ms, $gsValues;
		
		// set user settings
		$_SESSION = array_merge($_SESSION, getUserData($id));
	}
	
	function setUserSessionCPanel($id)
	{
		global $ms, $gsValues;
		
		if (!isset($_SESSION["cpanel_privileges"]))
		{
			if ($_SESSION['privileges'] == 'super_admin')
			{
				$_SESSION["cpanel_user_id"] = $id;
				$_SESSION["cpanel_privileges"] = 'super_admin';
				$_SESSION["cpanel_manager_id"] = 0;
			}
			else if ($_SESSION['privileges'] == 'admin')
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
	
	function setUserSessionObjOverwrt($id)
	{
		global $ms, $gsValues;
		
		if (isset($_SESSION["cpanel_privileges"]))
		{
			
			$q2 = "SELECT * FROM `gs_users` WHERE `id`='".$id."'";
			$r2 = mysqli_query($ms, $q2);
			$row2 = mysqli_fetch_array($r2);
				
			$_SESSION["obj_add"] = $row2["obj_add"];
			$_SESSION["obj_limit"] = $row2["obj_limit"];
			$_SESSION["obj_limit_num"] = $row2["obj_limit_num"];
			$_SESSION["obj_days"] = $row2["obj_days"];
			$_SESSION["obj_days_dt"] = $row2["obj_days_dt"];
			$_SESSION["obj_edit"] = $row2["obj_edit"];
			$_SESSION["obj_history_clear"] = $row2["obj_history_clear"];
			
		}
	}
	
	function checkUserSession()
	{
		global $gsValues;
		
		$file = basename($_SERVER['SCRIPT_NAME']);
		
		if (($file == 'index.php') || (checkUserSession2() == false))
		{
			session_unset();
			session_destroy();
			session_start();
				
			$user_id = getUserIdFromSessionHash();
			
			if($user_id != false)
			{
				setUserSession($user_id);
				setUserSessionSettings($user_id);
				setUserSessionCPanel($user_id);
			}
		}
		
		if (checkUserSession2() == false)
		{
			if (($file == 'Dashboard.php') || ($file == 'cpanel.php'))
			{
				Header("Location: index.php");
			}
			
			if (($file != 'index.php') && ($file != 'fn_connect.php'))
			{
				die;
			}
		}
		else
		{
			if ($file == 'index.php')
			{
				if (($gsValues['PAGE_AFTER_LOGIN'] == 'cpanel') && ($_SESSION["cpanel_privileges"] != false))
				{
					if (file_exists('cpanel.php'))
					{
						Header("Location: cpanel.php");
					}
					else
					{
						Header("Location: Dashboard.php");
					}
				}
				else
				{
					Header("Location: Dashboard.php");
				}
			}
		}
	}
	
	function checkUserSession2()
	{
		global $ms, $gsValues;
		
		$result = false;
		
		if (isset($_SESSION["user_id"]) && isset($_SESSION["session"]) && isset($_SESSION["remote_addr"]) && isset($_SESSION["cpanel_privileges"]))
		{
			if (checkUserActive($_SESSION["user_id"]) == true)
			{
				if (($_SESSION["cpanel_privileges"] == false) || ($gsValues['ADMIN_IP_SESSION_CHECK'] == false))
				{
					if ($_SESSION["session"] == md5($gsValues['PATH_ROOT']))
					{
						$result = true;
					}	
				}
				else
				{
					if (($_SESSION["session"] == md5($gsValues['PATH_ROOT'])) && ($_SESSION["remote_addr"] == md5($_SERVER['REMOTE_ADDR'])))
					{
						$result = true;
					}	
				}
			}	
		}
		
		return $result;
	}
	
	function checkUserActive($id)
	{
		global $ms;
		
		$q = "SELECT * FROM `gs_users` WHERE `id`='".$id."'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);
		
		if ($row['active'] == 'true')
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function checkUserCPanelPrivileges()
	{
		global $ms, $gsValues;
		
		if (!isset($_SESSION["cpanel_privileges"]))
		{
			die;
		}
		
		if ($_SESSION["cpanel_privileges"] == false)
		{
			die;
		}
		
		if (($_SESSION["cpanel_privileges"] == 'super_admin') || ($_SESSION["cpanel_privileges"] == 'admin'))
		{
			if ($gsValues['ADMIN_IP'] != '')
			{
				$admin_ips = explode(",", $gsValues['ADMIN_IP']);	
				if (!in_array($_SERVER['REMOTE_ADDR'], $admin_ips))
				{
					die;
				}	
			}
		}
		
		if ($_SESSION["user_id"] != $_SESSION['cpanel_user_id'])
		{
			setUserSession($_SESSION['cpanel_user_id']);
		}
	}
	
	function getUserData($id)
	{
		global $gsValues, $ms, $la;
		
		$result = array();
		
		$q = "SELECT * FROM `gs_users` WHERE `id`='".$id."'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);
		
		$result["user_id"] = $id;
		$result["active"] = $row['active'];
		$result["ambulance"] = $row['ambulance'];
		$result["staff_manag"] = $row['staff_manag'];
		$result["inventory_manag"] = $row['inventory_manag'];
		$result["manager_id"] = $row['manager_id'];
		$result["manager_billing"] = $row["manager_billing"];
		$result["user_dashboard"] = @$row['user_dashboard'];
		
		$privileges = json_decode($row['privileges'],true);
		$privileges = checkUserPrivilegesArray($privileges);
		if(!isset($privileges['dashboard'])){
			$result['user_dashboard']='';
		}else{
			$result['user_dashboard']=$privileges["dashboard"];
		}
		if ($privileges["type"] == 'subuser')
		{
			$result["privileges"] = $privileges["type"];
			
			$privileges["imei"] = explode(",", $privileges["imei"]);
			$result["privileges_imei"] = '"'.implode('","', $privileges["imei"]).'"';
			
			$privileges["marker"] = explode(",", $privileges["marker"]);
			$result["privileges_marker"] = '"'.implode('","', $privileges["marker"]).'"';
			
			$privileges["route"] = explode(",", $privileges["route"]);
			$result["privileges_route"] = '"'.implode('","', $privileges["route"]).'"';
			
			$privileges["zone"] = explode(",", $privileges["zone"]);
			$result["privileges_zone"] = '"'.implode('","', $privileges["zone"]).'"';
			
			// check manager user privileges, in case some of them are not available, reset subuser privileges
			$q2 = "SELECT * FROM `gs_users` WHERE `id`='".$row['manager_id']."'";
			$r2 = mysqli_query($ms, $q2);
			$row2 = mysqli_fetch_array($r2);
			$manager_privileges = json_decode($row2['privileges'],true);
			$manager_privileges = checkUserPrivilegesArray($manager_privileges);
			
			if ($manager_privileges["history"] == false) { $privileges["history"] = false; }
			if ($manager_privileges["reports"] == false) { $privileges["reports"] = false; }
			if ($manager_privileges["sos"] == false) { $privileges["sos"] = false; }
			if ($manager_privileges["sendcommand"] == false) { $privileges["sendcommand"] = false; }
			if ($manager_privileges["boarding"] == false) { $privileges["boarding"] = false; }
			if ($manager_privileges["rilogbook"] == false) { $privileges["rilogbook"] = false; }
			if ($manager_privileges["dtc"] == false) { $privileges["dtc"] = false; }
			if ($manager_privileges["object_control"] == false) { $privileges["object_control"] = false; }
			if ($manager_privileges["image_gallery"] == false) { $privileges["image_gallery"] = false; }
			if ($manager_privileges["live_tripreport"] == false) { $privileges["live_tripreport"] = false; }
			if ($manager_privileges["chat"] == false) { $privileges["chat"] = false; }
			
			$result["privileges_history"] = $privileges["history"];
			$result["privileges_reports"] = $privileges["reports"];
			$result["emergency_alert"] = $privileges["sos"];
			$result["send_command"] = $privileges["sendcommand"];
			$result["boarding_point_alert_system"] = $privileges["boarding"];
			$result["privileges_rilogbook"] = $privileges["rilogbook"];
			$result["privileges_dtc"] = $privileges["dtc"];
			$result["privileges_object_control"] = $privileges["object_control"];
			$result["privileges_image_gallery"] = $privileges["image_gallery"];
			$result["privileges_live_tripreport"] = $privileges["live_tripreport"];
			$result["privileges_chat"] = $privileges["chat"];
			$result["privileges_subaccounts"] = $privileges["subaccounts"];
		}
		else
		{
			$result["privileges"] = $privileges["type"];
			$result["privileges_imei"] = '';
			$result["privileges_marker"] = '';
			$result["privileges_route"] = '';
			$result["privileges_zone"] = '';
			
			$result["privileges_history"] = $privileges["history"];
			$result["privileges_reports"] = $privileges["reports"];
			$result["emergency_alert"] = $privileges["sos"];
			$result["boarding_point_alert_system"] = $privileges["boarding"];
			$result["send_command"] = $privileges["sendcommand"];
			$result["privileges_rilogbook"] = $privileges["rilogbook"];
			$result["privileges_dtc"] = $privileges["dtc"];
			$result["privileges_object_control"] = $privileges["object_control"];
			$result["privileges_image_gallery"] = $privileges["image_gallery"];
			$result["privileges_live_tripreport"] = $privileges["live_tripreport"];
			$result["privileges_chat"] = $privileges["chat"];
			$result["privileges_subaccounts"] = $privileges["subaccounts"];
		}
		
		// billing
		if (($gsValues['BILLING'] == 'true') && ($privileges["type"] != 'subuser'))
		{
			$result["billing"] = true;
			
			if ($row["manager_id"] != 0)
			{
				$q2 = "SELECT * FROM `gs_users` WHERE `id`='".$row['manager_id']."'";
				$r2 = mysqli_query($ms, $q2);
				$row2 = mysqli_fetch_array($r2);
						
				if($row2['manager_billing'] == 'true')
				{					
					$result["billing"] = true;
				}
				else
				{
					$result["billing"] = false;
				}
			}
		}
		else
		{
			$result["billing"] = false;
		}
		
		$result["username"] = $row['username'];
		$result["email"] = $row['email'];
		$result["info"] = $row['info'];
		
		$result["obj_add"] = $row['obj_add'];
		$result["obj_limit"] = $row['obj_limit'];
		$result["obj_limit_num"] = $row['obj_limit_num'];
		$result["obj_days"] = $row['obj_days'];
		$result["obj_days_dt"] = $row['obj_days_dt'];
		$result["obj_edit"] = $row['obj_edit'];
		$result["obj_history_clear"] = $row['obj_history_clear'];
		
		$result["currency"] = $row['currency'];
		$result["timezone"] = $row['timezone'];
		
		$result["dst"] = $row['dst'];
		$result["dst_start"] = $row['dst_start'];
		$result["dst_end"] = $row['dst_end'];
		
		$result["language"] = $row['language'];
		
		$result["chat_notify"] = $row['chat_notify'];
		
		$result["map_sp"] = $row['map_sp'];
		$result["map_is"] = $row['map_is'];
		
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
		
		$result["groups_collapsed"] = $row['groups_collapsed'];
		$result["od"] = $row['od'];
		$result["ohc"] = $row['ohc'];
		
		$result["sms_gateway_server"] = $row['sms_gateway_server'];
		$result["sms_gateway"] = $row['sms_gateway'];
		$result["sms_gateway_type"] = $row['sms_gateway_type'];
		$result["sms_gateway_url"] = $row['sms_gateway_url'];
		$result["sms_gateway_identifier"] = $row['sms_gateway_identifier'];
		
		$result["places_markers"] = $row['places_markers'];
		$result["places_routes"] = $row['places_routes'];
		$result["places_zones"] = $row['places_zones'];
		
		$result["usage_email_daily"] = $row['usage_email_daily'];
		$result["usage_sms_daily"] = $row['usage_sms_daily'];
		$result["usage_api_daily"] = $row['usage_api_daily'];		
		
		// units
		$result["units"] = $row['units'];
		$result = array_merge($result, getUnits($row['units']));

		$user_settings=checkUserSettings($id);

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

		return $result; 
	}
	
	function convUserTimezone($dt)
	{
		if (strtotime($dt) > 0)
		{
			$dt = gmdate("Y-m-d H:i:s", strtotime($dt.$_SESSION["timezone"]));
			
			// DST
			if ($_SESSION["dst"] == 'true')
			{
				$dt_ = gmdate('m-d H:i:s', strtotime($dt));
				$dst_start = $_SESSION["dst_start"].':00';
				$dst_end =  $_SESSION["dst_end"].':00';
				
				if (isDateInRange(convDateToNum($dt_), convDateToNum($dst_start), convDateToNum($dst_end)))
				{
					$dt = gmdate("Y-m-d H:i:s", strtotime($dt.'+ 1 hour'));
				}
			}
		}
		
		return $dt;
	}
	
	function convUserUTCTimezone($dt)
	{
		if (strtotime($dt) > 0)
		{
			if (substr($_SESSION["timezone"],0,1) == "+")
			{
				$timezone_diff = str_replace("+", "-", $_SESSION["timezone"]);				
			}
			else
			{
				$timezone_diff = str_replace("-", "+", $_SESSION["timezone"]);
			}
			
			$dt = gmdate("Y-m-d H:i:s", strtotime($dt.$timezone_diff));
		
			// DST
			if ($_SESSION["dst"] == 'true')
			{
				$dt_ = gmdate('m-d H:i:s', strtotime($dt));
				$dst_start = $_SESSION["dst_start"].':00';
				$dst_end =  $_SESSION["dst_end"].':00';
				
				if (isDateInRange(convDateToNum($dt_), convDateToNum($dst_start), convDateToNum($dst_end)))
				{
					$dt = gmdate("Y-m-d H:i:s", strtotime($dt.'- 1 hour'));
				}
			}
		}
		
		return $dt;
	}
	
	function convUserIDTimezone($user_id, $dt)
	{
		global $ms;
		
		if (strtotime($dt) > 0)
		{
			$q = "SELECT * FROM `gs_users` WHERE `id`='".$user_id."'";
			$r = mysqli_query($ms, $q);
			
			if (!$r)
			{
				return false;
			}
			
			$row = mysqli_fetch_array($r);
			
			if ($row)
			{	
				$dt = gmdate("Y-m-d H:i:s", strtotime($dt.$row["timezone"]));
				
				// DST
				if ($row["dst"] == 'true')
				{
					$dt_ = gmdate('m-d H:i:s', strtotime($dt));
					$dst_start = $row["dst_start"].':00';
					$dst_end =  $row["dst_end"].':00';
					
					if (isDateInRange(convDateToNum($dt_), convDateToNum($dst_start), convDateToNum($dst_end)))
					{
						$dt = gmdate("Y-m-d H:i:s", strtotime($dt.'+ 1 hour'));
					}	
				}
			}
		}
		
		return $dt;
	}
	
	function convUserIDUTCTimezone($user_id, $dt)
	{
		global $ms;
		
		if (strtotime($dt) > 0)
		{
			$q = "SELECT * FROM `gs_users` WHERE `id`='".$user_id."'";
			$r = mysqli_query($ms, $q);
			
			if (!$r)
			{
				return false;
			}
			
			$row = mysqli_fetch_array($r);
			
			if ($row)
			{
				if (substr($row["timezone"],0,1) == "+")
				{
					$timezone_diff = str_replace("+", "-", $row["timezone"]);
				}
				else
				{
					$timezone_diff = str_replace("-", "+", $row["timezone"]);
				}
				
				$dt = gmdate("Y-m-d H:i:s", strtotime($dt.$timezone_diff));
				
				// DST
				if ($row["dst"] == 'true')
				{
					$dt_ = gmdate('m-d H:i:s', strtotime($dt));
					$dst_start = $row["dst_start"].':00';
					$dst_end =  $row["dst_end"].':00';
					
					if (isDateInRange(convDateToNum($dt_), convDateToNum($dst_start), convDateToNum($dst_end)))
					{
						$dt = gmdate("Y-m-d H:i:s", strtotime($dt.'- 1 hour'));
					}	
				}	
			}
		}
		
		return $dt;
	}
	
	function checkUserToObjectPrivileges($id, $imei)
	{
		global $ms;
		
		$q = "SELECT * FROM `gs_user_objects` WHERE `user_id`='".$id."' AND `imei`='".$imei."'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);
		
		if ($row)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function checkUserExists($email)
	{
		global $ms;
		
		$email = strtolower($email);
		
		$q = "SELECT * FROM `gs_users` WHERE `email`='".$email."' LIMIT 1";
		$r = mysqli_query($ms, $q);
		$num = mysqli_num_rows($r);
		
		if ($num == 0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
    function addUser($send, $active, $account_expire, $account_expire_dt, $privileges, $manager_id, $email, $password, $obj_add, $obj_limit, $obj_limit_num, $obj_days, $obj_days_num, $obj_edit, $obj_history_clear,$mobile_num,$subaccnt_type,$report_list,$info,$otherapi,$user_settings)
	{
		global $ms, $gsValues, $la;
		
		$status = false;
		
		$result = '';
		
		$email = strtolower($email);
		$character=json_decode($otherapi);

		if (!checkUserExists($email))
		{
			if ($password == '')
			{
				$password = genAccountPassword();
			}
			
			$privileges_ = json_decode(stripslashes($privileges),true);
			
			if (isset($_SESSION['LANGUAGE']))
			{
				$language = $_SESSION['LANGUAGE'];
			}
			else
			{
				$language = $gsValues['LANGUAGE'];
			}
			
			if (($privileges_['type'] == 'subuser') && (@$privileges_['au_active'] == true))
			{
				$url_au = $gsValues['URL_ROOT']."/index.php?au=".$privileges_['au'];
				$url_au_mobile = $gsValues['URL_ROOT']."/index.php?au=".$privileges_['au'].'&m=true';
				
				$template = getDefaultTemplate('account_registration_au', $language);
				
				$subject = $template['subject'];
				$message = $template['message'];
				
				$subject = str_replace("%SERVER_NAME%", $gsValues['NAME'], $subject);
				$subject = str_replace("%URL_AU%", $url_au, $subject);
				$subject = str_replace("%URL_AU_MOBILE%", $url_au_mobile, $subject);
				
				$message = str_replace("%SERVER_NAME%", $gsValues['NAME'], $message);
				$message = str_replace("%URL_AU%", $url_au, $message);
				$message = str_replace("%URL_AU_MOBILE%", $url_au_mobile, $message);
			}
			else
			{
				$template = getDefaultTemplate('account_registration', $language);
				
				$subject = $template['subject'];
				$message = $template['message'];
				
				$subject = str_replace("%SERVER_NAME%", $gsValues['NAME'], $subject);
				$subject = str_replace("%URL_LOGIN%", $gsValues['URL_LOGIN'], $subject);
				$subject = str_replace("%EMAIL%", $email, $subject);
				$subject = str_replace("%USERNAME%", $email, $subject);
				$subject = str_replace("%PASSWORD%", $password, $subject);
				
				$message = str_replace("%SERVER_NAME%", $gsValues['NAME'], $message);
				$message = str_replace("%URL_LOGIN%", $gsValues['URL_LOGIN'], $message);
				$message = str_replace("%EMAIL%", $email, $message);
				$message = str_replace("%USERNAME%", $email, $message);
				$message = str_replace("%PASSWORD%", $password, $message);
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
			
			if ($send == 'true')
			{
				if (sendEmail($email, $subject, $message))
				{
					$status = true;
				}
			}
			else
			{
				$status = true;
			}
			$status = true;
			
			if ($status == true)
			{
				if ($privileges_['type'] == 'subuser')
				{
					$api = '';
					$api_key = '';	
				}
				else
				{
					$api = $gsValues['API'];
					$api_key = genUserAPIKey($email);
				}
				
				if ($privileges_['type'] == 'subuser')
				{
					$api = '';
					$api_key = '';	
				}
				else if($character->addusertype=='cpaneladduser'){
					$api = $character->u_api_active;
					$api_key =$character->u_api_key;	
				}
				else
				{
					$api = $gsValues['API'];
					$api_key = genUserAPIKey($email);
				}
				if ($obj_limit == 'false')
				{
					$obj_limit_num = 0;
				}
				
				if ($obj_days == 'true')
				{
					$obj_days_dt = gmdate("Y-m-d", strtotime(gmdate("Y-m-d").' + '.$obj_days_num.' days'));
				}
				else
				{
					$obj_days_dt = '';
				}
								
				$dst = $gsValues['DST'];
				
				if ($dst == 'true')
				{
					$dst_start = $gsValues['DST_START'];
					$dst_end = $gsValues['DST_END'];	
				}
				else
				{
					$dst_start = '';
					$dst_end = '';	
				}
				
			if(isset($character->u_smsgw)){
				$smsgtwy=$character->u_smsgw;
			}else{
				$smsgtwy=`false`;
			}
			if(isset($info['info'])){
				$infoval=$info['info'];
			}else{
				$infoval='';
			}
				
				$units = $gsValues['UNIT_OF_DISTANCE'].','.$gsValues['UNIT_OF_CAPACITY'].','.$gsValues['UNIT_OF_TEMPERATURE'];
				
				$q = "INSERT INTO gs_users (	`active`,
				`account_expire`,
				`account_expire_dt`,
				`privileges`,
				`manager_id`,
				`username`, 
				`password`, 
				`email`,
				`api`,
				`api_key`,
				`dt_reg`,
				`obj_add`, 
				`obj_limit`,
				`obj_limit_num`,
				`obj_days`,
				`obj_days_dt`,
				`obj_edit`,
				`obj_history_clear`,
				`currency`,
				`timezone`,
				`dst`,
				`dst_start`,
				`dst_end`,
				`language`,
				`units`,
				`map_sp`,
				`map_is`,
				`sms_gateway_server`,subuser_phone,driver,subuser_status,info)
				VALUES
				('".$active."',
				'".$account_expire."',
				'".$account_expire_dt."',
				'".$privileges."',
				'".$manager_id."',
				'".$email."',
				'".md5($password)."',
				'".$email."',
				'".$api."',
				'".$api_key."',
				'".gmdate("Y-m-d H:i:s")."',
				'".$obj_add."',
				'".$obj_limit."',
				'".$obj_limit_num."',
				'".$obj_days."',
				'".$obj_days_dt."',
				'".$obj_edit."',
				'".$obj_history_clear."',
				'".$gsValues['CURRENCY']."',
				'".$gsValues['TIMEZONE']."',
				'".$dst."',
				'".$dst_start."',
				'".$dst_end."',
				'".$gsValues['LANGUAGE']."',
				'".$units."',
				'last',
				'1',
				'".$smsgtwy."',
				'".$mobile_num."',
				'".$subaccnt_type."',
				'Deactive',
				'".$infoval."'
				)";
								//'false',=>'".$gsValues['SMS_GATEWAY_SERVER']."',
				$r = mysqli_query($ms, $q);
				
				//Update By Vetrivel.N
				$last_id = mysqli_insert_id($ms);

				addUserSettings($last_id,$u_user_settings,$u_subuser_settings,$u_object_settings,$u_group_settings,$u_event_settings,$u_zone_settings,$u_route_settings,$u_marker_settings,$u_duplicate_settings,$u_clr_history_settings);

			if(isset($character->addusertype)){
			if($character->addusertype=='cpaneladduser'){
				$qU="UPDATE gs_users SET places_markers='".$character->u_place_marker."',places_routes='".$character->u_place_routes."',places_zones='".$character->u_places_zone."',usage_email_daily='".$character->u_email_daily."',usage_sms_daily='".$character->u_sms_daily."',usage_api_daily='".$character->u_api_daily."',userapikey='".$character->u_user_key."',userapiip='".$character->u_user_ip."',username='".$character->u_username."' WHERE id='".$last_id."'";
				$r = mysqli_query($ms, $qU);
			}		}
				
				$arrayexpload=explode(',',$report_list);
				$rq="SELECT * FROM c_report_list where active='A'";		
				$r1=mysqli_query($ms,$rq);
				while($rowq=mysqli_fetch_array($r1)){
					if(in_array($rowq['rid'],$arrayexpload)){
						$active='A';
					}else{
						$active='D';
					}
					$q=mysqli_query($ms,"INSERT INTO c_report_privilege (rid,user_id,active)VALUES (".$rowq['rid'].",".$last_id.",'".$active."')");
				}
				CreateDefaultEvents($email);
				
				//write log
				writeLog('user_access', 'User registration: successful. E-mail: '.$email);
				
				$result = 'OK';
			}
			else
			{
				$result = 'ERROR_NOT_SENT';	
			}
		}
		else
		{
			$result = 'ERROR_EMAIL_EXISTS';
		}
		
		return $result;
	}
	
	//Updated By Vetrivel.N
	function CreateDefaultEvents($email)
	{
		global $ms, $gsValues, $la;
		
		$qu="select id from gs_users where email='".$email."'";
		$ru = mysqli_query($ms, $qu);
		$rowu=mysqli_fetch_assoc($ru);
		
		$week_days='true,true,true,true,true,true,true,';
		$daytime='{"dt":false,"sun":false,"sun_from":"00:00","sun_to":"24:00","mon":false,"mon_from":"00:00","mon_to":"24:00","tue":false,"tue_from":"00:00","tue_to":"24:00","wed":false,"wed_from":"00:00","wed_to":"24:00","thu":false,"thu_from":"00:00","thu_to":"24:00","fri":false,"fri_from":"00:00","fri_to":"24:00","sat":false,"sat_from":"00:00","sat_to":"24:00"}';
		$notify_system='true,true,true,alarm1.mp3';
		
		$q1 = "INSERT INTO `gs_user_events` (`user_id`,`type`,`name`,`active`,`duration_from_last_event`,`duration_from_last_event_minutes`,`week_days`,`day_time`,`imei`,`checked_value`,`route_trigger`,`zone_trigger`,`routes`,`zones`,`notify_system`,`notify_email`,`notify_email_address`,`notify_sms`,`notify_sms_number`,`email_template_id`,`sms_template_id`,`notify_arrow`,`notify_arrow_color`,`notify_ohc`,`notify_ohc_color`,`cmd_send`,`cmd_gateway`,`cmd_type`,`cmd_string`) VALUES 
		('".$rowu["id"]."','fuelstolen','Fuel Siphon','true','','0','".$week_days."','".$daytime."','','','','','','','".$notify_system."','false','','false','','0','0','false','arrow_yellow','false','#FFFF00','false','gprs','ascii','')";
		$r1 = mysqli_query($ms, $q1);

		$q1 = "INSERT INTO `gs_user_events` (`user_id`,`type`,`name`,`active`,`duration_from_last_event`,`duration_from_last_event_minutes`,`week_days`,`day_time`,`imei`,`checked_value`,`route_trigger`,`zone_trigger`,`routes`,`zones`,`notify_system`,`notify_email`,`notify_email_address`,`notify_sms`,`notify_sms_number`,`email_template_id`,`sms_template_id`,`notify_arrow`,`notify_arrow_color`,`notify_ohc`,`notify_ohc_color`,`cmd_send`,`cmd_gateway`,`cmd_type`,`cmd_string`) VALUES 
		('".$rowu["id"]."','fueldiscnt','FuelWire Disconnect','true','','0','".$week_days."','".$daytime."','','','','','','','".$notify_system."','false','','false','','0','0','false','arrow_yellow','false','#FFFF00','false','gprs','ascii','')";
		$r1 = mysqli_query($ms, $q1);
		
		$q1 = "INSERT INTO `gs_user_events` (`user_id`,`type`,`name`,`active`,`duration_from_last_event`,`duration_from_last_event_minutes`,`week_days`,`day_time`,`imei`,`checked_value`,`route_trigger`,`zone_trigger`,`routes`,`zones`,`notify_system`,`notify_email`,`notify_email_address`,`notify_sms`,`notify_sms_number`,`email_template_id`,`sms_template_id`,`notify_arrow`,`notify_arrow_color`,`notify_ohc`,`notify_ohc_color`,`cmd_send`,`cmd_gateway`,`cmd_type`,`cmd_string`) VALUES 
		('".$rowu["id"]."','gpsantcut','GPS Antenna Cut','true','','0','".$week_days."','".$daytime."','','','','','','','".$notify_system."','false','','false','','0','0','false','arrow_yellow','false','#FFFF00','false','gprs','ascii','')";
		$r1 = mysqli_query($ms, $q1);
		
		$q1 = "INSERT INTO `gs_user_events` (`user_id`,`type`,`name`,`active`,`duration_from_last_event`,`duration_from_last_event_minutes`,`week_days`,`day_time`,`imei`,`checked_value`,`route_trigger`,`zone_trigger`,`routes`,`zones`,`notify_system`,`notify_email`,`notify_email_address`,`notify_sms`,`notify_sms_number`,`email_template_id`,`sms_template_id`,`notify_arrow`,`notify_arrow_color`,`notify_ohc`,`notify_ohc_color`,`cmd_send`,`cmd_gateway`,`cmd_type`,`cmd_string`) VALUES 
		('".$rowu["id"]."','overspeed','Overspeed > 80','true','','0','".$week_days."','".$daytime."','','80','','','','','".$notify_system."','false','','false','','0','0','false','arrow_yellow','false','#FFFF00','false','gprs','ascii','')";
		$r1 = mysqli_query($ms, $q1);
		
		$q1 = "INSERT INTO `gs_user_events` (`user_id`,`type`,`name`,`active`,`duration_from_last_event`,`duration_from_last_event_minutes`,`week_days`,`day_time`,`imei`,`checked_value`,`route_trigger`,`zone_trigger`,`routes`,`zones`,`notify_system`,`notify_email`,`notify_email_address`,`notify_sms`,`notify_sms_number`,`email_template_id`,`sms_template_id`,`notify_arrow`,`notify_arrow_color`,`notify_ohc`,`notify_ohc_color`,`cmd_send`,`cmd_gateway`,`cmd_type`,`cmd_string`) VALUES 
		('".$rowu["id"]."','pwrcut','power Cut','true','','0','".$week_days."','".$daytime."','','','','','','','".$notify_system."','false','','false','','0','0','false','arrow_yellow','false','#FFFF00','false','gprs','ascii','')";
		$r1 = mysqli_query($ms, $q1);
		
		$q1 = "INSERT INTO `gs_user_events` (`user_id`,`type`,`name`,`active`,`duration_from_last_event`,`duration_from_last_event_minutes`,`week_days`,`day_time`,`imei`,`checked_value`,`route_trigger`,`zone_trigger`,`routes`,`zones`,`notify_system`,`notify_email`,`notify_email_address`,`notify_sms`,`notify_sms_number`,`email_template_id`,`sms_template_id`,`notify_arrow`,`notify_arrow_color`,`notify_ohc`,`notify_ohc_color`,`cmd_send`,`cmd_gateway`,`cmd_type`,`cmd_string`) VALUES 
		('".$rowu["id"]."','mainpwrsta','GPS Main Power Status','true','','0','".$week_days."','".$daytime."','','','','','','','".$notify_system."','false','','false','','0','0','false','arrow_yellow','false','#FFFF00','false','gprs','ascii','')";
		$r1 = mysqli_query($ms, $q1);
		
		$q1 = "INSERT INTO `gs_user_events` (`user_id`,`type`,`name`,`active`,`duration_from_last_event`,`duration_from_last_event_minutes`,`week_days`,`day_time`,`imei`,`checked_value`,`route_trigger`,`zone_trigger`,`routes`,`zones`,`notify_system`,`notify_email`,`notify_email_address`,`notify_sms`,`notify_sms_number`,`email_template_id`,`sms_template_id`,`notify_arrow`,`notify_arrow_color`,`notify_ohc`,`notify_ohc_color`,`cmd_send`,`cmd_gateway`,`cmd_type`,`cmd_string`) VALUES 
		('".$rowu["id"]."','param','Low Fuel < 20','true','false','0','".$week_days."','".$daytime."','','[{'src':'fuel1','cn':'lw','val':'20}]','off','off','','','".$notify_system."','false','','false','','0','0','false','arrow_yellow','false','#FFFF00','false','gprs','ascii','')";
		$r1 = mysqli_query($ms, $q1);
		
		$q1 = "INSERT INTO `gs_user_events` (`user_id`,`type`,`name`,`active`,`duration_from_last_event`,`duration_from_last_event_minutes`,`week_days`,`day_time`,`imei`,`checked_value`,`route_trigger`,`zone_trigger`,`routes`,`zones`,`notify_system`,`notify_email`,`notify_email_address`,`notify_sms`,`notify_sms_number`,`email_template_id`,`sms_template_id`,`notify_arrow`,`notify_arrow_color`,`notify_ohc`,`notify_ohc_color`,`cmd_send`,`cmd_gateway`,`cmd_type`,`cmd_string`) VALUES 
		('".$rowu["id"]."','param','A/C On','true','','0','".$week_days."','".$daytime."','','[{'src':'di1','cn':'eq','val':'1'}]','','','','','".$notify_system."','false','','false','','0','0','false','arrow_yellow','false','#FFFF00','false','gprs','ascii','')";
		$r1 = mysqli_query($ms, $q1);
				
		$q1 = "INSERT INTO `gs_user_events` (`user_id`,`type`,`name`,`active`,`duration_from_last_event`,`duration_from_last_event_minutes`,`week_days`,`day_time`,`imei`,`checked_value`,`route_trigger`,`zone_trigger`,`routes`,`zones`,`notify_system`,`notify_email`,`notify_email_address`,`notify_sms`,`notify_sms_number`,`email_template_id`,`sms_template_id`,`notify_arrow`,`notify_arrow_color`,`notify_ohc`,`notify_ohc_color`,`cmd_send`,`cmd_gateway`,`cmd_type`,`cmd_string`) VALUES 
		('".$rowu["id"]."','param','A/C Off','true','','0','".$week_days."','".$daytime."','','[{'src':'di1','cn':'eq','val':'1'}]','','','','','".$notify_system."','false','','false','','0','0','false','arrow_yellow','false','#FFFF00','false','gprs','ascii','')";
		$r1 = mysqli_query($ms, $q1);
		
		
	}
	
	
	function delUser($id)
	{
		global $ms, $gsValues;
		if(checkSettingsPrivileges('cpanel_user','delete')==true)
		{
			$q = "DELETE FROM `gs_users` WHERE `id`='".$id."'";
			$r = mysqli_query($ms, $q);
			
			// delete user sub users
			$q = "DELETE FROM `gs_users` WHERE `privileges` LIKE '%subuser%' AND `manager_id`='".$id."'";
			$r = mysqli_query($ms, $q);
			
			//$q = "DELETE FROM `gs_user_usage` WHERE `user_id`='".$id."'";
			//$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_user_billing_plans` WHERE `user_id`='".$id."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_user_zones` WHERE `user_id`='".$id."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_user_markers` WHERE `user_id`='".$id."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_user_objects` WHERE `user_id`='".$id."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_user_object_groups` WHERE `user_id`='".$id."'";
			$r = mysqli_query($ms, $q);		
			
			$q = "SELECT * FROM `gs_user_object_drivers` WHERE `user_id`='".$id."'";
	  		$r = mysqli_query($ms, $q);
			
			while ($row = mysqli_fetch_array($r))
			{
				$img_file = $gsValues['PATH_ROOT'].'data/user/drivers/'.$row['driver_img_file'];
				if(is_file($img_file))
				{
					@unlink($img_file);
				}			
			}
			
			$q = "DELETE FROM `gs_user_object_drivers` WHERE `user_id`='".$id."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_user_object_passengers` WHERE `user_id`='".$id."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_user_object_trailers` WHERE `user_id`='".$id."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_object_cmd_exec` WHERE `user_id`='".$id."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_user_cmd` WHERE `user_id`='".$id."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_user_cmd_schedule` WHERE `user_id`='".$id."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_user_reports` WHERE `user_id`='".$id."'";
			$r = mysqli_query($ms, $q);
			
			// delete user events
			$q = "SELECT * FROM `gs_user_events` WHERE `user_id`='".$id."'";
			$r = mysqli_query($ms, $q);
			
			while ($row = mysqli_fetch_array($r))
			{
				$event_id = $row['event_id'];
				
				$q2 = "DELETE FROM `gs_user_events_status` WHERE `event_id`='".$event_id."'";				
				$r2 = mysqli_query($ms, $q2);
			}
			
			$q = "DELETE FROM `gs_user_events` WHERE `user_id`='".$id."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_user_events_data` WHERE `user_id`='".$id."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `c_report_privilege` WHERE `user_id`='".$id."'";
			$r = mysqli_query($ms, $q);

			writeLog('object_op', 'Delete User: successful. user_id: '.$id);
		}else{
			writeLog('object_op', 'Try to Delete User. user_id: '.$id);
		}
		
	}
	
	function getUserObjectIMEIs($id)
	{
		global $ms;
		
		$result = false;
		
		$q = "SELECT * FROM `gs_user_objects` WHERE `user_id`='".$id."'";
		$r = mysqli_query($ms, $q);
		
		while($row = mysqli_fetch_array($r))
		{
			$result .= '"'.$row['imei'].'",';
		}
		$result = rtrim($result, ',');
		
		return $result;
	}
	
	function getUserNumberOfMarkers($id)
	{
		global $ms;
		
		$q = "SELECT * FROM `gs_user_markers` WHERE `user_id`='".$id."'";
		$r = mysqli_query($ms, $q);
		$count = mysqli_num_rows($r);
		
		return $count;
	}
	
	function getUserNumberOfZones($id)
	{
		global $ms;
		
		$q = "SELECT * FROM `gs_user_zones` WHERE `user_id`='".$id."'";
		$r = mysqli_query($ms, $q);
		$count = mysqli_num_rows($r);
		
		return $count;
	}
	
	function getUserNumberOfRoutes($id)
	{
		global $ms;
		
		$q = "SELECT * FROM `gs_user_routes` WHERE `user_id`='".$id."'";
		$r = mysqli_query($ms, $q);
		$count = mysqli_num_rows($r);
		
		return $count;
	}
	
	function checkUserPrivilegesArray($privileges)
	{
		if (!isset($privileges["history"])) { $privileges["history"] = true; }
		if (!isset($privileges["reports"])) { $privileges["reports"] = true; }
		if (!isset($privileges["sos"])) { $privileges["sos"] = true; }
		if (!isset($privileges["sendcommand"])) { $privileges["sendcommand"] = true; }
		if (!isset($privileges["boarding"])) { $privileges["boarding"] = true; }
		if (!isset($privileges["rilogbook"])) { $privileges["rilogbook"] = true; }
		if (!isset($privileges["dtc"])) { $privileges["dtc"] = true; }
		if (!isset($privileges["object_control"])) { $privileges["object_control"] = true; }
		if (!isset($privileges["image_gallery"])) { $privileges["image_gallery"] = true; }
		if (!isset($privileges["live_tripreport"])) { $privileges["live_tripreport"] = false;}
		if (!isset($privileges["chat"])) { $privileges["chat"] = true; }
		if (!isset($privileges["subaccounts"])) { $privileges["subaccounts"] = true; }
		
		return $privileges;
	}
	
	// #################################################
	//  END USER FUNCTIONS
	// #################################################
	
	// #################################################
	// OBJECT FUNCTIONS
	// #################################################
	
	function checkObjectLimitSystem()
	{
		global $ms, $gsValues;
		
		if ($gsValues['OBJECT_LIMIT'] == 0)
		{
			return false;
		}
		
		$q = "SELECT * FROM `gs_objects`";
		$r = mysqli_query($ms, $q);
		$num = mysqli_num_rows($r);
		
		if ($num >= $gsValues['OBJECT_LIMIT'])
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function checkObjectLimitUser($id)
	{
		global $ms;
		
		if ($_SESSION["obj_limit"] == 'true')
		{
			$q = "SELECT * FROM `gs_user_objects` WHERE `user_id`='".$id."'";
			$r = mysqli_query($ms, $q);
			$num = mysqli_num_rows($r);
			
			if($num >= $_SESSION["obj_limit_num"])
			{
				return true;
			}
			
			return false;
		}
		else
		{
			return false;
		}
	}
	
	function checkObjectExistsSystem($imei)
	{
		global $ms;
		
		$q = "SELECT * FROM `gs_objects` WHERE `imei`='".$imei."'";
		$r = mysqli_query($ms, $q);
		
		if (!$r)
		{
			return false;
		}
		
		$num = mysqli_num_rows($r);
		if ($num >= 1)
		{
			return true;
		}
		return false;	
	}
	
	function checkBoardimeiExists($boardid)
	{
		global $ms;
		
		$q = "SELECT * FROM `gs_objects` WHERE `board_id`='".$boardid."'";
		$r = mysqli_query($ms, $q);
		if (!$r)
		{
			return false;
		}
		
		$num = mysqli_num_rows($r);
		if ($num == 0)
		{
			return true;
		}
		return false;	
	}

	function checkObjectExistsUser($imei)
	{
		global $ms;
		
		$q = "SELECT * FROM `gs_user_objects` WHERE `imei`='".$imei."'";
		$r = mysqli_query($ms, $q);
		
		if (!$r)
		{
			return false;
		}
		
		$num = mysqli_num_rows($r);
		if ($num >= 1)
		{
			return true;
		}
		return false;
	}
	
	function adjustObjectTime($imei, $dt)
	{
		global $ms;
		
		$q = "SELECT * FROM `gs_objects` WHERE `imei`='".$imei."'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);
		
		if($row)
		{
			if (strtotime($dt) > 0)
			{
				$dt = gmdate("Y-m-d H:i:s", strtotime($dt.$row["time_adj"]));
			}
		}
		
		return $dt;
	}
	
	function createObjectDataTable($imei,$device,$fueltype="")
	{
		global $ms;
		
		if (!checkObjectExistsSystem($imei)) return false;

		
		if($fueltype=="FMS")
		{
			$q = "CREATE TABLE IF NOT EXISTS gs_object_data_".$imei."(	dt_server datetime NOT NULL,
										dt_tracker datetime NOT NULL,
										lat double,
										lng double,
										altitude double,
										angle double,
										speed double,
										params varchar(2048) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
										mileage double,
										fuelused double,
										KEY `dt_tracker` (`dt_tracker`)
										) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
			$r = mysqli_query($ms, $q);
		}
	    else
		{
			$q = "CREATE TABLE IF NOT EXISTS gs_object_data_".$imei."(	dt_server datetime NOT NULL,
										dt_tracker datetime NOT NULL,
										lat double,
										lng double,
										altitude double,
										angle double,
										speed double,
										params varchar(2048) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
										mileage double,
										KEY `dt_tracker` (`dt_tracker`)
										) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
			$r = mysqli_query($ms, $q);
		
		}
		//if not exist insert acc		
		$q="INSERT  INTO gs_object_sensors (`imei`,`name`,`type`,`param`, `result_type`,`text_1`,`text_0`,`units`,`lv`,`hv`,`formula`,`calibration`,data_list,popup) SELECT * FROM  
		(select '".$imei."' imei,'Ignition' name,'acc' type,'acc' param,'logic' result_type,'On' text_1,'Off' text_0,'' units,'0' lv,'0' hv,'' formula,'[]' calibration,'true' data_list,'true' popup) AS tmp WHERE NOT EXISTS ( SELECT * FROM gs_object_sensors WHERE imei ='".$imei."' and type='acc' and param='acc') LIMIT 1;";
		$r =mysqli_query($ms, $q);
		
		if($device!="Play FMT" && $device!="Play 102G" && $device!="Play Mobile (Android)" && $device!="Play Mobile (iOS)"&& $device!="Other" && $fueltype!="No Sensor")
		{
			//if not exist insert fuel
			$q="INSERT  INTO gs_object_sensors (`imei`,`name`,`type`,`param`, `result_type`,`text_1`,`text_0`,`units`,`lv`,`hv`,`formula`,`calibration`,data_list,popup) SELECT * FROM  
			(select '".$imei."' as imei,'Fuel 1' as name,'fuel' as type,'fuel1' as param,'value' as result_type,'' as text_1,'' as text_0,'Ltrs' as units,'0' lv,'0' hv,'X*1.0' formula,'[]' calibration,'true' data_list,'true' popup) AS tmp WHERE NOT EXISTS ( SELECT * FROM gs_object_sensors WHERE imei ='".$imei."' and type='fuel' and param='fuel1') LIMIT 1;";
			$r =mysqli_query($ms, $q);
		}
		
	
		if($device=="Play 4000" || $device=="Play 5000" || $device=="Play 6000"  || $device=="Play 6000F" )
		{
			//door
			$q1="INSERT  INTO gs_object_sensors (`imei`,`name`,`type`,`param`, `result_type`,`text_1`,`text_0`,`units`,`lv`,`hv`,`formula`,`calibration`,data_list,popup) SELECT * FROM  
			(select '".$imei."' imei,'Door' name,'cust' type,'di2' param,'logic' result_type,'Open' text_1,'Close' text_0,'' units,'0' lv,'0' hv,'' formula,'[]' calibration,'true' data_list,'true' popup) AS tmp WHERE NOT EXISTS ( SELECT * FROM gs_object_sensors WHERE imei ='".$imei."' and type='cust' and param='di2') LIMIT 1;";
			$r1 =mysqli_query($ms, $q1);
			
			$q1="INSERT  INTO gs_object_sensors (`imei`,`name`,`type`,`param`, `result_type`,`text_1`,`text_0`,`units`,`lv`,`hv`,`formula`,`calibration`,data_list,popup) SELECT * FROM  
			(select '".$imei."' imei,'Gps Antenna' name,'cust' type,'ala1' param,'logic' result_type,'Check Antenna' text_1,'OK' text_0,'' units,'0' lv,'0' hv,'' formula,'[]' calibration,'true' data_list,'true' popup) AS tmp WHERE NOT EXISTS ( SELECT * FROM gs_object_sensors WHERE imei ='".$imei."' and type='cust' and param='ala1') LIMIT 1;";
			$r1 =mysqli_query($ms, $q1);
			
			$q1="INSERT  INTO gs_object_sensors (`imei`,`name`,`type`,`param`, `result_type`,`text_1`,`text_0`,`units`,`lv`,`hv`,`formula`,`calibration`,data_list,popup) SELECT * FROM  
			(select '".$imei."' imei,'Main Power' name,'cust' type,'ala2' param,'logic' result_type,'Backup Battery' text_1,'OK' text_0,'' units,'0' lv,'0' hv,'' formula,'[]' calibration,'true' data_list,'true' popup) AS tmp WHERE NOT EXISTS ( SELECT * FROM gs_object_sensors WHERE imei ='".$imei."' and type='cust' and param='ala2') LIMIT 1;";
			$r1 =mysqli_query($ms, $q1);
			
				//if not exist insert A/C	
			$q1="INSERT  INTO gs_object_sensors (`imei`,`name`,`type`,`param`, `result_type`,`text_1`,`text_0`,`units`,`lv`,`hv`,`formula`,`calibration`,data_list,popup) SELECT * FROM  
			(select '".$imei."' imei,'A/C' name,'cust' type,'di1' param,'logic' result_type,'ON' text_1,'OFF' text_0,'' units,'0' lv,'0' hv,'' formula,'[]' calibration,'true' data_list,'true' popup) AS tmp WHERE NOT EXISTS ( SELECT * FROM gs_object_sensors WHERE imei ='".$imei."' and type='cust' and param='di1') LIMIT 1;";
			$r1 =mysqli_query($ms, $q1);
			
			//if not exist insert odo		
			$q="INSERT  INTO gs_object_sensors (`imei`,`name`,`type`,`param`, `result_type`,`text_1`,`text_0`,`units`,`lv`,`hv`,`formula`,`calibration`,data_list,popup) SELECT * FROM  
			(select '".$imei."' imei,'Odometer' name,'odo' type,'odo' param,'abs' result_type,'' text_1,'' text_0,'' units,'0' lv,'0' hv,'X' formula,'[]' calibration,'false' data_list,'false' popup) AS tmp WHERE NOT EXISTS ( SELECT * FROM gs_object_sensors WHERE imei ='".$imei."' and type='odo' and param='odo') LIMIT 1;";
			$r =mysqli_query($ms, $q);
			
			if($device=="Play 6000F")
			{
				//if not exist insert fuel2
				$q="INSERT  INTO gs_object_sensors (`imei`,`name`,`type`,`param`, `result_type`,`text_1`,`text_0`,`units`,`lv`,`hv`,`formula`,`calibration`,data_list,popup) SELECT * FROM  
				(select '".$imei."' as imei,'Fuel 2' as name,'fuel' as type,'fuel2' as param,'value' as result_type,'' as text_1,'' as text_0,'Ltrs' as units,'0' lv,'0' hv,'X*1.0' formula,'[]' calibration,'true' data_list,'true' popup) AS tmp WHERE NOT EXISTS ( SELECT * FROM gs_object_sensors WHERE imei ='".$imei."' and type='fuel' and param='fuel2') LIMIT 1;";
				$r =mysqli_query($ms, $q);
			}
			
		}
		else if($device=="Play FM100")
		{
			//if not exist insert odor		
			$q="INSERT  INTO gs_object_sensors (`imei`,`name`,`type`,`param`, `result_type`,`text_1`,`text_0`,`units`,`lv`,`hv`,`formula`,`calibration`,data_list,popup) SELECT * FROM  
			(select '".$imei."' imei,'Odometer' name,'odo' type,'odor' param,'rel' result_type,'' text_1,'' text_0,'' units,'0' lv,'0' hv,'X' formula,'[]' calibration,'false' data_list,'false' popup) AS tmp WHERE NOT EXISTS ( SELECT * FROM gs_object_sensors WHERE imei ='".$imei."' and type='odo' and param='odor') LIMIT 1;";
			$r =mysqli_query($ms, $q);
		
			$q1="INSERT  INTO gs_object_sensors (`imei`,`name`,`type`,`param`, `result_type`,`text_1`,`text_0`,`units`,`lv`,`hv`,`formula`,`calibration`,data_list,popup) SELECT * FROM  
			(select '".$imei."' imei,'A/C' name,'cust' type,'di3' param,'logic' result_type,'ON' text_1,'OFF' text_0,'' units,'0' lv,'0' hv,'' formula,'[]' calibration,'true' data_list,'true' popup) AS tmp WHERE NOT EXISTS ( SELECT * FROM gs_object_sensors WHERE imei ='".$imei."' and type='cust' and param='di3') LIMIT 1;";
			$r1 =mysqli_query($ms, $q1);
			
			$q1="INSERT  INTO gs_object_sensors (`imei`,`name`,`type`,`param`, `result_type`,`text_1`,`text_0`,`units`,`lv`,`hv`,`formula`,`calibration`,data_list,popup) SELECT * FROM  
			(select '".$imei."' imei,'Gps Antenna' name,'cust' type,'gps' param,'logic' result_type,'Check Antenna' text_1,'OK' text_0,'' units,'0' lv,'0' hv,'' formula,'[]' calibration,'true' data_list,'true' popup) AS tmp WHERE NOT EXISTS ( SELECT * FROM gs_object_sensors WHERE imei ='".$imei."' and type='cust' and param='gps') LIMIT 1;";
			$r1 =mysqli_query($ms, $q1);
			
			$q1="INSERT  INTO gs_object_sensors (`imei`,`name`,`type`,`param`, `result_type`,`text_1`,`text_0`,`units`,`lv`,`hv`,`formula`,`calibration`,data_list,popup) SELECT * FROM  
			(select '".$imei."' imei,'Gsm Antenna' name,'cust' type,'gsm' param,'logic' result_type,'Check Antenna' text_1,'OK' text_0,'' units,'0' lv,'0' hv,'' formula,'[]' calibration,'true' data_list,'true' popup) AS tmp WHERE NOT EXISTS ( SELECT * FROM gs_object_sensors WHERE imei ='".$imei."' and type='cust' and param='gsm') LIMIT 1;";
			$r1 =mysqli_query($ms, $q1);
			
			$q1="INSERT  INTO gs_object_sensors (`imei`,`name`,`type`,`param`, `result_type`,`text_1`,`text_0`,`units`,`lv`,`hv`,`formula`,`calibration`,data_list,popup) SELECT * FROM  
			(select '".$imei."' imei,'Main Power' name,'cust' type,'mainpower' param,'logic' result_type,'Backup Battery' text_1,'OK' text_0,'' units,'0' lv,'0' hv,'' formula,'[]' calibration,'true' data_list,'true' popup) AS tmp WHERE NOT EXISTS ( SELECT * FROM gs_object_sensors WHERE imei ='".$imei."' and type='cust' and param='mainpower') LIMIT 1;";
			$r1 =mysqli_query($ms, $q1);
			
			$q1="INSERT  INTO gs_object_sensors (`imei`,`name`,`type`,`param`, `result_type`,`text_1`,`text_0`,`units`,`lv`,`hv`,`formula`,`calibration`,data_list,popup) SELECT * FROM  
			(select '".$imei."' imei,'Fuel Sensor' name,'cust' type,'fuelwire' param,'logic' result_type,'Check Cable' text_1,'OK' text_0,'' units,'0' lv,'0' hv,'' formula,'[]' calibration,'true' data_list,'true' popup) AS tmp WHERE NOT EXISTS ( SELECT * FROM gs_object_sensors WHERE imei ='".$imei."' and type='cust' and param='fuelwire') LIMIT 1;";
			$r1 =mysqli_query($ms, $q1);
		}
		else if($device=="Play T20" || $device=="Play T09" || $device=="Play G200" || $device=="Play 102G"|| $device=="Play 102G"|| $device=="Play 102G" || $device=="Play Basic" || $device=="Play Coban" || $device=="Play Concox" || $device=="Play T100" || $device=="Play T200" )
		{
			//if not exist insert A/C
			if($device=="Play T100" || $device=="Play T200"){
				$q1="INSERT  INTO gs_object_sensors (`imei`,`name`,`type`,`param`, `result_type`,`text_1`,`text_0`,`units`,`lv`,`hv`,`formula`,`calibration`,data_list,popup) SELECT * FROM  
				(select '".$imei."' imei,'A/C' name,'cust' type,'adc1' param,'logic' result_type,'ON' text_1,'OFF' text_0,'' units,'0' lv,'0' hv,'' formula,'[]' calibration,'true' data_list,'true' popup) AS tmp WHERE NOT EXISTS ( SELECT * FROM gs_object_sensors WHERE imei ='".$imei."' and type='cust' and param='adc1') LIMIT 1;";
				$r1 =mysqli_query($ms, $q1);
			}else{
				$q1="INSERT  INTO gs_object_sensors (`imei`,`name`,`type`,`param`, `result_type`,`text_1`,`text_0`,`units`,`lv`,`hv`,`formula`,`calibration`,data_list,popup) SELECT * FROM  
				(select '".$imei."' imei,'A/C' name,'cust' type,'di1' param,'logic' result_type,'ON' text_1,'OFF' text_0,'' units,'0' lv,'0' hv,'' formula,'[]' calibration,'true' data_list,'true' popup) AS tmp WHERE NOT EXISTS ( SELECT * FROM gs_object_sensors WHERE imei ='".$imei."' and type='cust' and param='di1') LIMIT 1;";
				$r1 =mysqli_query($ms, $q1);
			}
			
			//if not exist insert odo		
			$q="INSERT  INTO gs_object_sensors (`imei`,`name`,`type`,`param`, `result_type`,`text_1`,`text_0`,`units`,`lv`,`hv`,`formula`,`calibration`,data_list,popup) SELECT * FROM  
			(select '".$imei."' imei,'Odometer' name,'odo' type,'odo' param,'abs' result_type,'' text_1,'' text_0,'' units,'0' lv,'0' hv,'X' formula,'[]' calibration,'false' data_list,'false' popup) AS tmp WHERE NOT EXISTS ( SELECT * FROM gs_object_sensors WHERE imei ='".$imei."' and type='odo' and param='odo') LIMIT 1;";
			$r =mysqli_query($ms, $q);
			
		}
		else if($device=="Play FMT" )
		{
			//if not exist insert fuel
			$q="INSERT  INTO gs_object_sensors (`imei`,`name`,`type`,`param`, `result_type`,`text_1`,`text_0`,`units`,`lv`,`hv`,`formula`,`calibration`,data_list,popup) SELECT * FROM  
			(select '".$imei."' as imei,'Fuel 1' as name,'fuel' as type,'lls_fuel1' as param,'value' as result_type,'' as text_1,'' as text_0,'Ltrs' as units,'0' lv,'0' hv,'X/10' formula,'[]' calibration,'true' data_list,'true' popup) AS tmp WHERE NOT EXISTS ( SELECT * FROM gs_object_sensors WHERE imei ='".$imei."' and type='fuel' and param='lls_fuel1') LIMIT 1;";
			$r =mysqli_query($ms, $q);
			
			//if not exist insert odo		
			$q="INSERT  INTO gs_object_sensors (`imei`,`name`,`type`,`param`, `result_type`,`text_1`,`text_0`,`units`,`lv`,`hv`,`formula`,`calibration`,data_list,popup) SELECT * FROM  
			(select '".$imei."' imei,'Odometer' name,'odo' type,'odo' param,'abs' result_type,'' text_1,'' text_0,'' units,'0' lv,'0' hv,'X' formula,'[]' calibration,'false' data_list,'false' popup) AS tmp WHERE NOT EXISTS ( SELECT * FROM gs_object_sensors WHERE imei ='".$imei."' and type='odo' and param='odo') LIMIT 1;";
			$r =mysqli_query($ms, $q);
			
		}
		else 
		{
				//if not exist insert odo		
			$q="INSERT  INTO gs_object_sensors (`imei`,`name`,`type`,`param`, `result_type`,`text_1`,`text_0`,`units`,`lv`,`hv`,`formula`,`calibration`,data_list,popup) SELECT * FROM  
			(select '".$imei."' imei,'Odometer' name,'odo' type,'odo' param,'abs' result_type,'' text_1,'' text_0,'' units,'0' lv,'0' hv,'X' formula,'[]' calibration,'false' data_list,'false' popup) AS tmp WHERE NOT EXISTS ( SELECT * FROM gs_object_sensors WHERE imei ='".$imei."' and type='odo' and param='odo') LIMIT 1;";
			$r =mysqli_query($ms, $q);
		}
				
		return true;
	}
	
	function addObjectSystem($name, $imei, $active, $object_expire, $object_expire_dt, $manager_id
	,$fueltype="No Sensor",$fuel1,$fuel2,$fuel3,$fuel4,$temp1,$temp2,$temp3,$staff,$triptype,$device,$sim_number)
	{
		global $ms,$_SESSION;
		$odotype="sen";
		if($device=="Play Basic" || $device=="Play Coban" || $device=="Play Concox"|| $device=="Play T100"|| $device=="Play T200"
		 || $device=="Play Mobile (Android)" || $device=="Play Mobile (iOS)" 
		 || $device=="Other" || $device=="Play TR20" || $device=="Play Max" || $device=="I-Tracker"|| $device=="Play FMT")
		{
			$odotype="gps";
		}
		
		if (checkObjectExistsSystem($imei))
		{
			
			$manager_id=$_SESSION["manager_id"];
			$obj=getObject($imei);
			if($obj!=false)$manager_id=$obj["manager_id"];
						
			if($_SESSION["manager_id"]!=$manager_id)
			{
				$q = "UPDATE `gs_objects` SET 
   					name='".$name."',
    				fueltype='".$fueltype."',
    				fuel1='".$fuel1."',
    				fuel2='".$fuel2."',
    				fuel3='".$fuel3."',
    				fuel4='".$fuel4."',
    				temp1='".$temp1."',
    				temp2='".$temp2."',
    				temp3='".$temp3."',
    				staff='".$staff."' ,
    				device='".$device."',
    				sim_number='".$sim_number."',
    				manager_id='".$_SESSION["manager_id"]."',
    				odometer_type='".$odotype."'
    				WHERE `imei`='".$imei."'";
			 	$r = mysqli_query($ms,$q);
			}
			else 
			{
			 	$q = "UPDATE `gs_objects` SET 
   					name='".$name."',
    				fueltype='".$fueltype."',
    				fuel1='".$fuel1."',
    				fuel2='".$fuel2."',
    				fuel3='".$fuel3."',
    				fuel4='".$fuel4."',
    				temp1='".$temp1."',
    				temp2='".$temp2."',
    				temp3='".$temp3."',
    				staff='".$staff."' ,
    				device='".$device."',
    				sim_number='".$sim_number."',
    				odometer_type='".$odotype."'
    				WHERE `imei`='".$imei."'";
			 	$r = mysqli_query($ms,$q);
			}
		}
		else
		{
			$maparrow='{"arrow_no_connection":"arrow_black","arrow_stopped":"arrow_red","arrow_moving":"arrow_green","arrow_engine_idle":"arrow_orange"}';
		$q = "INSERT INTO `gs_objects` (`imei`,
						`active`,
						`object_expire`,
						`object_expire_dt`,
						`manager_id`,
						`name`,
						`map_icon`,
						 map_arrows,
						`icon`,
						`tail_color`,
						`tail_points`,
						`odometer_type`,
						`engine_hours_type`,
						device,sim_number,
						fueltype,fuel1,fuel2,fuel3,fuel4,temp1,temp2,temp3,staff,triptype)
						VALUES
						('".$imei."',
						'".$active."',
						'".$object_expire."',
						'".$object_expire_dt."',
						'".$manager_id."',
						'".$name."',
						'arrow',
						'".$maparrow."',
						'img/markers/objects/land-truck.svg',
						'#00FF44',
						7,
						'".$odotype."',
						'acc',
						'".$device."','".$sim_number."',
						'".$fueltype."', '".$fuel1."', '".$fuel2."', '".$fuel3."', '".$fuel4."', '".$temp1."', '".$temp2."', '".$temp3."', '".$staff."','".$triptype."')";		
		$r = mysqli_query($ms, $q);
		}
	
		// delete from unused objects
		$q = "DELETE FROM `gs_objects_unused` WHERE `imei`='".$imei."'";
		$r = mysqli_query($ms, $q);
		
		//write log
		writeLog('object_op', 'Add object: successful. IMEI: '.$imei);
		
		return true;
	}
	
	function addObjectSystemExtended($name, $imei,$bimei, $model, $vin, $plate_number, $device, $sim_number, $active, $object_expire, $object_expire_dt, $manager_id,$installdate)
	{
		global $ms;
		
		$odotype="sen";
		if($device=="Play Basic" || $device=="Play Coban" || $device=="Play Concox"|| $device=="Play T100"|| $device=="Play T200"
		|| $device=="Play Mobile (Android)" || $device=="Play Mobile (iOS)" 
		|| $device=="Other" || $device=="Play TR20" || $device=="Play Max" || $device=="I-Tracker" || $device=="Dispenser")
		{
			$odotype="gps";
		}
		$maparrow='{"arrow_no_connection":"arrow_black","arrow_stopped":"arrow_red","arrow_moving":"arrow_green","arrow_engine_idle":"arrow_orange"}';
		$q = "INSERT INTO `gs_objects` (`imei`,`board_id`,
						`active`,
						`object_expire`,
						`object_expire_dt`,
						`manager_id`,
						`name`,
						`map_icon`,
						map_arrows,
						`icon`,
						`tail_color`,
						`tail_points`,
						`device`,
						`sim_number`,
						`model`,
						`vin`,
						`plate_number`,
						`odometer_type`,
						`engine_hours_type`,`installdate`)
						VALUES
						('".$imei."','".$bimei."',
						'".$active."',
						'".$object_expire."',
						'".$object_expire_dt."',
						'".$manager_id."',
						'".$name."',
						'arrow',
						'".$maparrow."',
						'img/markers/objects/land-truck.svg',
						'#00FF44',
						7,
						'".$device."',
						'".$sim_number."',
						'".$model."',
						'".$vin."',
						'".$plate_number."',
						'".$odotype."',
						'acc',
						'".$installdate."')";
							
		$r = mysqli_query($ms, $q);
		// delete from unused objects
		$q = "DELETE FROM `gs_objects_unused` WHERE `imei`='".$imei."'";
		$r = mysqli_query($ms, $q);
		
		//write log
		writeLog('object_op', 'Add object: successful. IMEI: '.$imei);
	}
	
	function addObjectUser($user_id, $imei, $group_id, $driver_id, $trailer_id)
	{
		global $ms;
		
		if (!$user_id) return false;
		
		if (!checkObjectExistsSystem($imei)) return false;
		
		$q = "SELECT * FROM `gs_user_objects` WHERE `user_id`='".$user_id."' AND `imei`='".$imei."'";
                $r = mysqli_query($ms, $q);
                $num = mysqli_num_rows($r);
                if ($num == 0)
                {
			$q = "INSERT INTO `gs_user_objects` 	(`user_id`,
								`imei`,
								`group_id`,
								`driver_id`,
								`trailer_id`)
								VALUES (
								'".$user_id."',
								'".$imei."',
								'".$group_id."',
								'".$driver_id."',
								'".$trailer_id."')";
			$r = mysqli_query($ms, $q);
                }
		
		return true;
	}
	
	function duplicateObjectSystem($duplicate_imei, $imei, $object_expire, $object_expire_dt, $manager_id, $name)
	{
		global $ms;
		
		$q = "SELECT * FROM `gs_objects` WHERE `imei`='".$duplicate_imei."'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);
		
		$q = "INSERT INTO `gs_objects` (`imei`,
						`active`,
						`object_expire`,
						`object_expire_dt`,
						`manager_id`,
						`name`,
						`icon`,
						`map_arrows`,
						`map_icon`,
						`tail_color`,
						`tail_points`,
						`device`,
						`sim_number`,
						`model`,
						`vin`,
						`plate_number`,
						`odometer_type`,
						`engine_hours_type`,
						`odometer`,
						`engine_hours`,
						`fcr`,
						`time_adj`,
						`accuracy`)
						VALUES
						('".$imei."',
						'true',
						'".$object_expire."',
						'".$object_expire_dt."',
						'".$manager_id."',
						'".$name."',
						'".$row['icon']."',
						'".$row['map_arrows']."',
						'".$row['map_icon']."',
						'".$row['tail_color']."',
						'".$row['tail_points']."',
						'".$row['device']."',
						'".$row['sim_number']."',
						'".$row['model']."',
						'".$row['vin']."',
						'".$row['plate_number']."',
						'".$row['odometer_type']."',
						'".$row['engine_hours_type']."',
						'".$row['odometer']."',
						'".$row['engine_hours']."',
						'".$row['fcr']."',
						'".$row['time_adj']."',
						'".$row['accuracy']."')";		
		$r = mysqli_query($ms, $q);
		
		$q = "SELECT * FROM `gs_object_sensors` WHERE `imei`='".$duplicate_imei."'";
		$r = mysqli_query($ms, $q);
		while($row = mysqli_fetch_array($r))
		{
			$q2 = "INSERT INTO `gs_object_sensors` (`imei`,
								`name`,
								`type`,
								`param`,
								`data_list`, 
								`popup`, 
								`result_type`,
								`text_1`,
								`text_0`,
								`units`,
								`lv`,
								`hv`,
								`formula`,
								`calibration`)
								VALUES
								('".$imei."',
								'".$row['name']."',
								'".$row['type']."',
								'".$row['param']."',
								'".$row['data_list']."',
								'".$row['popup']."',
								'".$row['result_type']."',
								'".$row['text_1']."',
								'".$row['text_0']."',
								'".$row['units']."',
								'".$row['lv']."',
								'".$row['hv']."',
								'".$row['formula']."',
								'".$row['calibration']."')";
			$r2 = mysqli_query($ms, $q2);
		}
		
		$q = "SELECT * FROM `gs_object_services` WHERE `imei`='".$duplicate_imei."'";
		$r = mysqli_query($ms, $q);
		while($row = mysqli_fetch_array($r))
		{
			$q2 = "INSERT INTO `gs_object_services` (`imei`,
								`name`,
								`data_list`,
								`popup`,
								`odo`,
								`odo_interval`,
								`odo_last`, 
								`engh`,
								`engh_interval`,
								`engh_last`,
								`days`,
								`days_interval`,
								`days_last`,
								`odo_left`,
								`odo_left_num`,
								`engh_left`,
								`engh_left_num`,
								`days_left`,
								`days_left_num`,
								`update_last`,
								`notify_service_expire`)
								VALUES
								('".$imei."',
								'".$row['name']."',
								'".$row['data_list']."',
								'".$row['popup']."',
								'".$row['odo']."',
								'".$row['odo_interval']."',
								'".$row['odo_last']."',
								'".$row['engh']."',
								'".$row['engh_interval']."',
								'".$row['engh_last']."',
								'".$row['days']."',
								'".$row['days_interval']."',
								'".$row['days_last']."',
								'".$row['odo_left']."',
								'".$row['odo_left_num']."',
								'".$row['engh_left']."',
								'".$row['engh_left_num']."',
								'".$row['days_left']."',
								'".$row['days_left_num']."',
								'".$row['update_last']."',
								'".$row['notify_service_expire']."')";
			$r2 = mysqli_query($ms, $q2);
		}
		
		$q = "SELECT * FROM `gs_object_custom_fields` WHERE `imei`='".$duplicate_imei."'";
		$r = mysqli_query($ms, $q);
		while($row = mysqli_fetch_array($r))
		{
			$q2 = "INSERT INTO `gs_object_custom_fields` (`imei`,
									`name`,
									`value`,
									`data_list`, 
									`popup`)
									VALUES
									('".$imei."',
									'".$row['name']."',
									'".$row['value']."',
									'".$row['data_list']."',
									'".$row['popup']."')";
			$r2 = mysqli_query($ms, $q2);
		}
		
		$q = "SELECT * FROM `ex_user_work_hour` WHERE `imei`='".$duplicate_imei."'";
		$r = mysqli_query($ms, $q);
		while($row = mysqli_fetch_array($r))
		{			
			$q2 = "INSERT INTO `ex_user_work_hour` 
			(`imei`,user_id, `from_time`, `to_time`, `today`, `day`,status) VALUES
			 ('".$imei."', '".$row['user_id']."', '".$row['from_time']."', '".$row['to_time']."', 
			 '".$row['today']."', 
			 '".$row['today']."', '".$row['status']."')";
			
			$r2 = mysqli_query($ms, $q2);
		}
		
		return $row;
	}
	
	function delObjectUser($user_id, $imei)
	{
		global $ms;
		// if (($_SESSION["cpanel_privileges"] == 'super_admin') || ($_SESSION["cpanel_privileges"] == 'admin'))
		if(checkSettingsPrivileges('object','delete')==true)
		{

			$q = "DELETE FROM `gs_user_objects` WHERE `user_id`='".$user_id."' AND `imei`='".$imei."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_user_events_data` WHERE `user_id`='".$user_id."' AND `imei`='".$imei."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_user_events_status` WHERE `imei`='".$imei."'";
			$r = mysqli_query($ms, $q);
			
			$q="SELECT * FROM `gs_user_events`  WHERE `user_id`='".$user_id."'";
			$r=mysqli_query($ms,$q);
			$count=mysqli_num_rows($r);
			while($row=mysqli_fetch_array($r)){	
				$eximei=explode(',',$row['imei']);
			  	$imeisto='';
			    foreach($eximei as $i => $value) 
			    {
			    	if($value!=$imei)
			    	{
						if($imeisto!='')
							$imeisto.=','.$value;
			    		else
				    		$imeisto=$value;
				    }
			    }
			    
				updatesettingobjectimei($imeisto,$row['user_id'],$row['event_id']);

			}
		}else{

			writeLog('object_op', 'Try to Delete user object. IMEI: '.$imei);
		}
	}
	
	function updatesettingobjectimei($imeisto,$dbuserid,$dbeventid){
		global $ms;

		$q="UPDATE gs_user_events SET imei='".$imeisto."' WHERE event_id='".$dbeventid."' AND user_id='".$dbuserid."' ";
		$r = mysqli_query($ms, $q);
		
		echo 'OK';
	}

	function delObjectSystem($imei)
	{
		global $ms, $gsValues;
		if(checkSettingsPrivileges('object','delete')==true)
		// if (($_SESSION["cpanel_privileges"] == 'super_admin') || ($_SESSION["cpanel_privileges"] == 'admin'))
		{
		
			$q = "DELETE FROM `gs_objects` WHERE `imei`='".$imei."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_rilogbook_data` WHERE `imei`='".$imei."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_dtc_data` WHERE `imei`='".$imei."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_object_sensors` WHERE `imei`='".$imei."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_object_services` WHERE `imei`='".$imei."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_object_custom_fields` WHERE `imei`='".$imei."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `ex_user_work_hour` WHERE `imei`='".$imei."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_user_objects` WHERE `imei`='".$imei."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_user_events_data` WHERE `imei`='".$imei."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_user_events_status` WHERE `imei`='".$imei."'";
			$r = mysqli_query($ms, $q);
			
			$q = "SELECT * FROM `gs_object_img` WHERE `imei`='".$imei."'";
	  		$r = mysqli_query($ms, $q);
			
			while($row = mysqli_fetch_array($r))
			{
				$img_file = $gsValues['PATH_ROOT'].'data/img/'.$row['img_file'];
				if(is_file($img_file))
				{
					@unlink($img_file);
				}			
			}
			
			$q = "DELETE FROM `gs_object_img` WHERE `imei`='".$imei."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_object_chat` WHERE `imei`='".$imei."'";
			$r = mysqli_query($ms, $q);

			$q = "DELETE FROM `ckm_dailykm` WHERE `imei`='".$imei."'";
			$r = mysqli_query($ms, $q);

			$q = "UPDATE `dstudent` SET `imei`='' WHERE `imei`='".$imei."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `droute_events` WHERE `imei`='".$imei."'";
			$r = mysqli_query($ms, $q);

			$q = "DELETE FROM `droute_events_daily` WHERE `imei`='".$imei."'";
			$r = mysqli_query($ms, $q);

			$q = "DROP TABLE gs_object_data_".$imei;
			$r = mysqli_query($ms, $q);
			
			//write log
			writeLog('object_op', 'Delete object: successful. IMEI: '.$imei);
		}else{
			
			writeLog('object_op', 'Try to Delete object. IMEI: '.$imei);
		}
	}
	
	function changeObjectIMEI($old_imei, $new_imei)
	{
		global $ms;
		
		$old_imei = strtoupper($old_imei);
		$new_imei = strtoupper($new_imei);
		
		if (checkObjectExistsSystem($new_imei))
		{
			return false;
		}
		
		// data table
		$q = "alter table gs_object_data_".$old_imei." rename to gs_object_data_".$new_imei;
		$r = mysqli_query($ms, $q);
		
		// gs_user_reports
		$q = "SELECT * FROM `gs_user_reports` WHERE `imei` LIKE '%".$old_imei."%'";
		$r = mysqli_query($ms, $q);
		
		while($row = mysqli_fetch_array($r))
		{
			$imeis = explode(',', $row['imei']);
			
			for ($i = 0; $i < count($imeis); ++$i)
			{
				if ($imeis[$i] == $old_imei)
				{
					$imeis[$i] = $new_imei;
				}
			}
			
			$imeis_ = implode(",", $imeis);
			
			$q2 = "UPDATE `gs_user_reports` SET `imei`='".$imeis_."' WHERE `report_id`='".$row['report_id']."'";
			$r2 = mysqli_query($ms, $q2);
		}
		
		// gs_user_events
		$q = "SELECT * FROM `gs_user_events` WHERE `imei` LIKE '%".$old_imei."%'";
		$r = mysqli_query($ms, $q);
		
		while($row = mysqli_fetch_array($r))
		{
			$imeis = explode(',', $row['imei']);
			
			for ($i = 0; $i < count($imeis); ++$i)
			{
				if ($imeis[$i] == $old_imei)
				{
					$imeis[$i] = $new_imei;
				}
			}
			
			$imeis_ = implode(",", $imeis);
			
			$q2 = "UPDATE `gs_user_events` SET `imei`='".$imeis_."' WHERE `event_id`='".$row['event_id']."'";
			$r2 = mysqli_query($ms, $q2);
		}
		
		// gs_user_cmd_schedule
		$q = "SELECT * FROM `gs_user_cmd_schedule` WHERE `imei` LIKE '%".$old_imei."%'";
		$r = mysqli_query($ms, $q);
		
		while($row = mysqli_fetch_array($r))
		{
			$imeis = explode(',', $row['imei']);
			
			for ($i = 0; $i < count($imeis); ++$i)
			{
				if ($imeis[$i] == $old_imei)
				{
					$imeis[$i] = $new_imei;
				}
			}
			
			$imeis_ = implode(",", $imeis);
			
			$q2 = "UPDATE `gs_user_cmd_schedule` SET `imei`='".$imeis_."' WHERE `cmd_id`='".$row['cmd_id']."'";
			$r2 = mysqli_query($ms, $q2);
		}
		
		// gs_user_events_data
		$q = "UPDATE `gs_user_events_data` SET `imei`='".$new_imei."' WHERE `imei`='".$old_imei."'";
		$r = mysqli_query($ms, $q);
		
		// gs_user_events_status
		$q = "UPDATE `gs_user_events_status` SET `imei`='".$new_imei."' WHERE `imei`='".$old_imei."'";
		$r = mysqli_query($ms, $q);
		
		// gs_user_objects
		$q = "UPDATE `gs_user_objects` SET `imei`='".$new_imei."' WHERE `imei`='".$old_imei."'";
		$r = mysqli_query($ms, $q);
		
		// gs_objects
		$q = "UPDATE `gs_objects` SET `imei`='".$new_imei."' WHERE `imei`='".$old_imei."'";
		$r = mysqli_query($ms, $q);
		
		// gs_object_cmd_exec
		$q = "UPDATE `gs_object_cmd_exec` SET `imei`='".$new_imei."' WHERE `imei`='".$old_imei."'";
		$r = mysqli_query($ms, $q);
		
		// gs_object_img
		$q = "UPDATE `gs_object_img` SET `imei`='".$new_imei."' WHERE `imei`='".$old_imei."'";
		$r = mysqli_query($ms, $q);
		
		// gs_object_chat
		$q = "UPDATE `gs_object_chat` SET `imei`='".$new_imei."' WHERE `imei`='".$old_imei."'";
		$r = mysqli_query($ms, $q);
		
		// gs_object_sensors
		$q = "UPDATE `gs_object_sensors` SET `imei`='".$new_imei."' WHERE `imei`='".$old_imei."'";
		$r = mysqli_query($ms, $q);
		
		// gs_object_services
		$q = "UPDATE `gs_object_services` SET `imei`='".$new_imei."' WHERE `imei`='".$old_imei."'";
		$r = mysqli_query($ms, $q);
		
		// gs_object_custom_fields
		$q = "UPDATE `gs_object_custom_fields` SET `imei`='".$new_imei."' WHERE `imei`='".$old_imei."'";
		$r = mysqli_query($ms, $q);
		
		// ex_user_work_hour
		$q = "UPDATE `ex_user_work_hour` SET `imei`='".$new_imei."' WHERE `imei`='".$old_imei."'";
		$r = mysqli_query($ms, $q);
		
		// gs_rilogbook_data
		$q = "UPDATE `gs_rilogbook_data` SET `imei`='".$new_imei."' WHERE `imei`='".$old_imei."'";
		$r = mysqli_query($ms, $q);
		
		// gs_dtc_data
		$q = "UPDATE `gs_dtc_data` SET `imei`='".$new_imei."' WHERE `imei`='".$old_imei."'";
		$r = mysqli_query($ms, $q);
		
		// delete from unused objects
		$q = "DELETE FROM `gs_objects_unused` WHERE `imei`='".$new_imei."'";
		$r = mysqli_query($ms, $q);
		
		return true;
	}
	
	function clearObjectHistory($imei)
	{
		global $ms;
		if(checkSettingsPrivileges('clr_history','delete')==true)
		// if (($_SESSION["cpanel_privileges"] == 'super_admin') || ($_SESSION["cpanel_privileges"] == 'admin'))
		{
			$q = "DELETE FROM gs_object_data_".$imei;
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_rilogbook_data` WHERE `imei`='".$imei."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_dtc_data` WHERE `imei`='".$imei."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_user_events_data` WHERE `imei`='".$imei."'";
			$r = mysqli_query($ms, $q);
			
			$q = "DELETE FROM `gs_user_events_status` WHERE `imei`='".$imei."'";
			$r = mysqli_query($ms, $q);
			
			$q = "UPDATE `gs_objects` SET  `dt_server`='0000-00-00 00:00:00',
							`dt_tracker`='0000-00-00 00:00:00',
							`lat`='0',
							`lng`='0',
							`altitude`='0',
							`angle`='0',
							`speed`='0',
							`loc_valid`='0',
							`params`='',
							`dt_last_stop`='0000-00-00 00:00:00',
							`dt_last_idle`='0000-00-00 00:00:00',
							`dt_last_move`='0000-00-00 00:00:00'
							WHERE `imei`='".$imei."'";
			$r = mysqli_query($ms, $q);
			
			//write log
			writeLog('object_op', 'Clear object history: successful. IMEI: '.$imei);
		}else{
			writeLog('object_op', 'Try to Clear object history. IMEI: '.$imei);
		}
	}
	
	function checkObjectActive($imei)
	{
		global $ms;
		
		$q = "SELECT * FROM `gs_objects` WHERE `imei`='".$imei."'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);
		
		if ($row['active'] == 'true')
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function getObjectName($imei)
	{
		global $ms;
		
		$q = "SELECT * FROM `gs_objects` WHERE `imei`='".$imei."'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);
		
		return $row['name'];
	}
	
	function getObjectDriverFromSensor($user_id, $imei, $params)
	{
		global $ms;
		
		$driver = false;
		
		$driver_assign_id = false;
		
		$sensor = getSensorFromType($imei, 'da');
		
		if ($sensor != false)
		{
			$sensor_ = $sensor[0];
		
			$sensor_data = getSensorValue($params, $sensor_);
			$driver_assign_id = $sensor_data['value'];
		}
		else
		{
			return $driver;                                      
		
		}
		
		$q = "SELECT * FROM `gs_user_object_drivers` WHERE UPPER(`driver_assign_id`)='".strtoupper($driver_assign_id)."' AND `user_id`='".$user_id."'";
		$r = mysqli_query($ms, $q);
		$driver = mysqli_fetch_array($r);
		
		return $driver;
	}
	
	function getObjectTrailerFromSensor($user_id, $imei, $params)
	{
		global $ms;
		
		$trailer = false;
		
		$trailer_assign_id = false;
		
		$sensor = getSensorFromType($imei, 'ta');
		
		if ($sensor != false)
		{
			$sensor_ = $sensor[0];
			
			$sensor_data = getSensorValue($params, $sensor_);
			$trailer_assign_id = $sensor_data['value'];
		}
		else
		{
			return $trailer;                                      
		
		}
		
		$q = "SELECT * FROM `gs_user_object_trailers` WHERE UPPER(`trailer_assign_id`)='".strtoupper($trailer_assign_id)."' AND `user_id`='".$user_id."'";
		$r = mysqli_query($ms, $q);
		$trailer = mysqli_fetch_array($r);
		
		return $trailer;
	}
	
	function getObjectDriver($user_id, $imei, $params)
	{
		global $ms;
		
		$driver = false;
		
		$q = "SELECT * FROM `gs_user_objects` WHERE `user_id`='".$user_id ."' AND `imei`='".$imei."'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);
		
		$driver_id = $row['driver_id'];
		
		if ($driver_id == '-1')
		{
			return $driver;
		}
		
		if ($driver_id == '0')
		{
			return getObjectDriverFromSensor($user_id, $imei, $params);
		}
	       
		$q = "SELECT * FROM `gs_user_object_drivers` WHERE `user_id`='".$user_id ."' AND `driver_id`='".$driver_id."'";
		$r = mysqli_query($ms, $q);
		$driver = mysqli_fetch_array($r);
		
		return $driver;
	}
	
	function getObjectTrailer($user_id, $imei, $params)
	{
		global $ms;
		
		$trailer = false;
		
		$q = "SELECT * FROM `gs_user_objects` WHERE `user_id`='".$user_id ."' AND `imei`='".$imei."'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);
		
		$trailer_id = $row['trailer_id'];
		
		if ($trailer_id == '-1')
		{
			return $trailer;
		}
		
		if ($trailer_id == '0')
		{
			return getObjectTrailerFromSensor($user_id, $imei, $params);
		}
	       
		$q = "SELECT * FROM `gs_user_object_trailers` WHERE `user_id`='".$user_id ."' AND `trailer_id`='".$trailer_id."'";
		$r = mysqli_query($ms, $q);
		$trailer = mysqli_fetch_array($r);
		
		return $trailer;
	}
	
	function getObjectOdometer($imei)
	{
		global $ms;
		
		$q = "SELECT * FROM `gs_objects` WHERE `imei`='".$imei."'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);
		
		return floor($row['odometer']);
	}
	
	function getObjectEngineHours($imei, $details)
	{
		global $ms;
		
		$q = "SELECT * FROM `gs_objects` WHERE `imei`='".$imei."'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);
		
		if ($details)
		{
			// return floor($row['engine_hours'] / 60 / 60);	nandha

			return getTimeDetails($row['engine_hours'], false);	 
		}
		else
		{
			return floor($row['engine_hours'] / 60 / 60);	
		}
	}
	
	function getObjectIpPortProtocol($imei)
	{
		global $ms;
		
		$q = "SELECT ip,port,protocol FROM `gs_objects` WHERE `imei`='".$imei."'";
		$r = mysqli_query($ms, $q);
		if($row = mysqli_fetch_array($r))
		{
			return $row;
		}		
		else 
		{
			return 0;
		}
	}
	
	function getObjectFCR($imei)
	{
		global $ms, $gsValues;
		
		// default fcr
		$default = array(	'source' => 'rates',
					'measurement' => 'l100km',
					'cost' => 0,
					'summer' => 0,
					'winter' => 0,
					'winter_start' => '12-01',
					'winter_end' => '03-01');
		
		$q = "SELECT * FROM `gs_objects` WHERE `imei`='".$imei."'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);
		
		// set default fcr if not set in DB
		if (($row['fcr'] == '') || (json_decode($row['fcr'],true) == null))
		{
			$fcr = $default;
		}
		else
		{
			$fcr = json_decode($row['fcr'],true);
			
			if (!isset($fcr["source"])) { $fcr["source"] = $default["source"]; }
			if (!isset($fcr["measurement"])) { $fcr["measurement"] = $default["measurement"]; }
			if (!isset($fcr["cost"])) { $fcr["cost"] = $default["cost"]; }
			if (!isset($fcr["summer"])) { $fcr["summer"] = $default["summer"]; }
			if (!isset($fcr["winter"])) { $fcr["winter"] = $default["winter"]; }
			if (!isset($fcr["winter_start"])) { $fcr["winter_start"] = $default["winter_start"]; }
			if (!isset($fcr["winter_end"])) { $fcr["winter_end"] = $default["winter_end"]; }
		}
		
		return $fcr;
	}
	
	function getObjectAccuracy($imei)
	{
		global $ms, $gsValues;
		
		// default accuracy
		$default = array(	'stops' => 'gps',
					'min_moving_speed' => 6,
					'min_idle_speed' => 3,
					'min_diff_points' => 0.0005,
					'use_gpslev' => false,
					'min_gpslev' => 5,
					'use_hdop' => false,
					'max_hdop' => 3,
					'min_ff' => 10,
					'min_ft' => 10,
					'fueltype'=>'No Sensor',
					'fuel1'=>'No Sensor',
					'fuel2'=>'No Sensor',
					'fuel3'=>'No Sensor',
					'fuel4'=>'No Sensor',
					'temp1'=>'NO',
					'temp2'=>'NO',
					'temp3'=>'NO');
		
		$q = "SELECT * FROM `gs_objects` WHERE `imei`='".$imei."'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);

		// set default accuracy if not set in DB
		if (($row['accuracy'] == '') || (json_decode($row['accuracy'],true) == null))
		{
			$accuracy = $default;			
		}
		else
		{
			$accuracy = json_decode($row['accuracy'],true);
			$accuracy["fueltype"] =$row["fueltype"];
			$accuracy["fuel1"] =$row["fuel1"];
			$accuracy["fuel2"] =$row["fuel2"];
			$accuracy["fuel3"] =$row["fuel3"];
			$accuracy["fuel4"] =$row["fuel4"];
			$accuracy["temp1"] =$row["temp1"];
			$accuracy["temp2"] =$row["temp2"];
			$accuracy["temp3"] =$row["temp3"];
			
			if (!isset($accuracy["stops"])) { $accuracy["stops"] = $default["stops"]; }
			if (!isset($accuracy["min_moving_speed"])) { $accuracy["min_moving_speed"] = $default["min_moving_speed"]; }
			if (!isset($accuracy["min_idle_speed"])) { $accuracy["min_idle_speed"] = $default["min_idle_speed"]; }
			if (!isset($accuracy["min_diff_points"])) { $accuracy["min_diff_points"] = $default["min_diff_points"]; }
			if (!isset($accuracy["use_gpslev"])) { $accuracy["use_gpslev"] = $default["use_gpslev"]; }
			if (!isset($accuracy["min_gpslev"])) { $accuracy["min_gpslev"] = $default["min_gpslev"]; }
			if (!isset($accuracy["use_hdop"])) { $accuracy["use_hdop"] = $default["use_hdop"]; }
			if (!isset($accuracy["max_hdop"])) { $accuracy["max_hdop"] = $default["max_hdop"]; }
			if (!isset($accuracy["min_ff"])) { $accuracy["min_ff"] = $default["min_ff"]; }
			if (!isset($accuracy["min_ft"])) { $accuracy["stops"] = $default["stops"]; }
		    //if (!isset($accuracy["fueltype"])) { $accuracy["fueltype"] = $default["fueltype"]; }

		}
		
		return $accuracy;
	}
	
	function getObjectSensors($imei)
	{
		global $ms;
		
		// get object sensor list
		$q = "SELECT * FROM `gs_object_sensors` WHERE `imei`='".$imei."' ORDER BY `name` ASC";
		
		$r = mysqli_query($ms, $q);
		
		$sensors = array();
		
		while($row=mysqli_fetch_array($r))
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
							'data_list' => $row['data_list'],
							'popup' => $row['popup'],
							'result_type' => $row['result_type'],
							'text_1' => $row['text_1'],
							'text_0' => $row['text_0'],
							'units' => $row['units'],
							'lv' => $row['lv'],
							'hv' => $row['hv'],
							'formula' => $row['formula'],
							'tanksize' => $row['tank_size'],
							'calibration' => $calibration
							);
		}
		
		return $sensors;
	}
	
	function getObjectService($imei)
	{
		global $ms;
		
		// get object service list
		$q = "SELECT * FROM `gs_object_services` WHERE `imei`='".$imei."' ORDER BY `name` ASC";
		$r = mysqli_query($ms, $q);
		
		$service = array();
		
		while($row=mysqli_fetch_array($r))
		{
			$row['odo_interval'] = floor(convDistanceUnits($row['odo_interval'], 'km', $_SESSION["unit_distance"]));
			$row['odo_last'] = floor(convDistanceUnits($row['odo_last'], 'km', $_SESSION["unit_distance"]));
			$row['odo_left_num'] = floor(convDistanceUnits($row['odo_left_num'], 'km', $_SESSION["unit_distance"]));
			
			$service_id = $row['service_id'];
			$service[$service_id] = array(	'name' => $row['name'],
							'data_list' => $row['data_list'],
							'popup' => $row['popup'],
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
	
	function getObjectCustomFields($imei)
	{
		global $ms;
		
		// get object service list
		$q = "SELECT * FROM `gs_object_custom_fields` WHERE `imei`='".$imei."' ORDER BY `name` ASC";
		$r = mysqli_query($ms, $q);
		
		$custom_fields = array();
		
		while($row=mysqli_fetch_array($r))
		{			
			$field_id = $row['field_id'];
			$custom_fields[$field_id] = array(	'name' => $row['name'],
								'value' => $row['value'],
								'data_list' => $row['data_list'],
								'popup' => $row['popup']
								);
		}
		
		return $custom_fields;
	}
	
	function getObjectWorkingHour($imei)
	{
		global $ms;
		
		// get object workinghour list
		$q = "SELECT * FROM `ex_user_work_hour` WHERE `imei`='".$imei."' ORDER BY `from_time` ASC";
		$r = mysqli_query($ms, $q);
		
		$timing = array();
		
		while($row=mysqli_fetch_array($r))
		{			
			$field_id = $row['whid'];
			$timing[$field_id] = array
			(	'from' => $row['from_time'],
								'to' => $row['to_time'],
								'type' => $row['today'],
								'day' => $row['day'],
								'status' => $row['status']
								);
		}
		
		return $timing;
	}
	
	function getUserExpireAvgDate($ids)
        {
		global $ms;
		
		$date_from_today = '';
                $total_days = 0;
                $count = 0;
		
		$ids_ = '';
		for ($i = 0; $i < count($ids); ++$i)
		{
			if ($_SESSION["user_id"] != $ids[$i])
			{
				$ids_ .= '"'.$ids[$i].'",';	
			}
		}
		$ids_ = rtrim($ids_, ',');
                
                $q = "SELECT * FROM `gs_users` WHERE `id` IN (".$ids_.")";
		$r = mysqli_query($ms, $q);
                
		if (!$r)
		{
			return $date_from_today;
		}
		
		while($row = mysqli_fetch_array($r))
		{			
			if ($row['account_expire'] == 'true')
			{
				$object_expire_dt = strtotime($row['account_expire_dt']);
				$today = strtotime(gmdate('Y-m-d'));
				
				$diff_days = round(($object_expire_dt - $today) / 86400);
				
				if ($diff_days > 0)
				{
					$total_days += $diff_days;
				}	
			}
			
			$count++;
		}	
                
		if ($count == 0)
		{
			return $date_from_today;
		}
		
		$total_days = round($total_days/$count);
		      
		$date_from_today = gmdate('Y-m-d', strtotime(gmdate('Y-m-d'). ' + '.$total_days.' days'));
                
		return $date_from_today;
        }
	
	function getObjectExpireAvgDate($imeis)
        {
		global $ms;
		
		$date_from_today = '';
                $total_days = 0;
                $count = 0;
		
		$imeis_ = '';
		for ($i = 0; $i < count($imeis); ++$i)
		{
			$imeis_ .= '"'.$imeis[$i].'",';
		}
		$imeis_ = rtrim($imeis_, ',');
                
                $q = "SELECT * FROM `gs_objects` WHERE `imei` IN (".$imeis_.")";
		$r = mysqli_query($ms, $q);
                
		if (!$r)
		{
			return $date_from_today;
		}
		
		while($row = mysqli_fetch_array($r))
		{			
			if ($row['object_expire'] == 'true')
			{
				$object_expire_dt = strtotime($row['object_expire_dt']);
				$today = strtotime(gmdate('Y-m-d'));
				
				$diff_days = round(($object_expire_dt - $today) / 86400);
				
				if ($diff_days > 0)
				{
					$total_days += $diff_days;
				}	
			}
			
			$count++;
		}	
                
		if ($count == 0)
		{
			return $date_from_today;
		}
		
		$total_days = round($total_days/$count);
		      
		$date_from_today = gmdate('Y-m-d', strtotime(gmdate('Y-m-d'). ' + '.$total_days.' days'));
                
		return $date_from_today;
        }
	
	function sendObjectSMSCommand($user_id, $imei, $name, $cmd)
	{
		global $ms, $gsValues;
		
		$result = false;
		
		//check user usage
		if (!checkUserUsage($user_id, 'sms')) return $result;
		
		// validate
		$imei = strtoupper($imei);
		
		if ($imei == '') return $result;
		
		if ($cmd == '') return $result;
		
		// variables
		$cmd = str_replace("%IMEI%", $imei, $cmd);
		
		$q = "SELECT * FROM `gs_users` WHERE `id`='".$user_id."'";
		$r = mysqli_query($ms, $q);
		$ud = mysqli_fetch_array($r);
		
		$q = "SELECT * FROM `gs_objects` WHERE `imei`='".$imei."'";
		$r = mysqli_query($ms, $q);
		$od = mysqli_fetch_array($r);
		
		$number = $od['sim_number'];
		
		if ($ud['sms_gateway'] == 'true')
		{
			if ($ud['sms_gateway_type'] == 'http')
			{
				$result = sendSMSHTTP($ud['sms_gateway_url'], '', $number, $cmd);
			}
			else if ($ud['sms_gateway_type'] == 'app')
			{
				$result = sendSMSAPP($ud['sms_gateway_identifier'], '',  $number, $cmd);
			}
		}
		else
		{
			if (($ud['sms_gateway_server'] == 'true') && ($gsValues['SMS_GATEWAY'] == 'true'))
			{
				if ($gsValues['SMS_GATEWAY_TYPE'] == 'http')
				{
					$result = sendSMSHTTP($gsValues['SMS_GATEWAY_URL'], $gsValues['SMS_GATEWAY_NUMBER_FILTER'], $number, $cmd);
				}
				else if ($gsValues['SMS_GATEWAY_TYPE'] == 'app')
				{
					$result = sendSMSAPP($gsValues['SMS_GATEWAY_IDENTIFIER'], $gsValues['SMS_GATEWAY_NUMBER_FILTER'], $number, $cmd);
				}
			}
		}
		
		if ($result == true)
		{
			$q = "INSERT INTO `gs_object_cmd_exec`(`user_id`,
								`dt_cmd`,
								`imei`,
								`name`,
								`gateway`,
								`type`,
								`cmd`,
								`status`)
								VALUES
								('".$user_id."',
								'".gmdate("Y-m-d H:i:s")."',
								'".$imei."',
								'".$name."',
								'sms',
								'ascii',
								'".$cmd."',							 
								'1')";
			$r = mysqli_query($ms, $q);
			
			//update user usage
			updateUserUsage($user_id, false, false, 1, false);
		}
		
		return $result;
	}
	
	function sendObjectGPRSCommand($user_id, $imei, $name, $type, $cmd)
	{
		global $ms;
		
		$result = false;
		
		// validate
		$imei = strtoupper($imei);
				
		if ($imei == '') return $result;
		
		if ($cmd == '') return $result;
		
		if ($type == 'ascii')
		{
			// variables
			$cmd = str_replace("%IMEI%", $imei, $cmd);
		}
		else if ($type == 'hex')
		{
			$cmd = strtoupper($cmd);
			
			if (!ctype_xdigit($cmd)) return $result;
		}
		else
		{
			return $result;
		}
		
		$q = "SELECT * FROM `gs_object_cmd_exec` WHERE `imei`='".$imei."' AND `type`='".$type."' AND `cmd`='".$cmd."' AND `status`='0'";
		$r = mysqli_query($ms, $q);
		$num = mysqli_num_rows($r);
		if ($num == 0)
		{
			$q = "INSERT INTO `gs_object_cmd_exec`(`user_id`,
								`dt_cmd`,
								`imei`,
								`name`,
								`gateway`,
								`type`,
								`cmd`,
								`status`)
								VALUES
								('".$user_id."',
								'".gmdate("Y-m-d H:i:s")."',
								'".$imei."',
								'".$name."',
								'gprs',
								'".$type."',
								'".$cmd."',							 
								'0')";
			$r = mysqli_query($ms, $q);
			
			$result = true;
		}
		
		return $result;
	}
	
	// #################################################
	// END OBJECT FUNCTIONS
	// #################################################
	
	// #################################################
	// SENSOR FUNCTIONS
	// #################################################
	
	function mergeParams($old, $new)
	{
		
		if (is_array($old) && is_array($new))
		{
			$new = array_merge($old, $new);	
		}		
		return $new;
	}
	
	function getParamsArray($params)
	{
		$arr_params = array();
		
		if ($params != '')
		{
			$params = json_decode($params,true);
			
			if (is_array($params))
			{
				foreach ($params as $key => $value)
				{
					array_push($arr_params, $key);
				}
			}
		}
		
		return $arr_params;
	}

function getParamsArray_decode($params)
	{
		$arr_params = array();
		
		if ($params != ''){
			
			if (is_array($params))
			{
				foreach ($params as $key => $value)
				{
					array_push($arr_params, $key);
				}
			}
		}
		
		return $arr_params;
	}
	
	function getParamValue($params, $param)
	{
		$result = 0;
		if (isset($params[$param]))
		{
			$result = $params[$param];
		}
		return $result;
	}
	
	function paramsToArray($params)
	{
		// keep compatibility with old software versions which used '|' and with software versions using JSON
		$arr_params = array();
		$typeparam=gettype($params);
		if ($typeparam=="string" && substr($params, -1) == '|')
		{
			$params = explode("|", $params);
				
			for ($i = 0; $i < count($params)-1; ++$i)
			{
				$param = explode("=", $params[$i]);
				if(count($param)>1)
					$arr_params[$param[0]] = $param[1];
			}
		}
		else if($typeparam=="array")
		{
			$arr_params=$params;
		}
		else if($typeparam=="string")
		{
			$arr_params = json_decode($params,true);
		}
		
		if (!is_array($arr_params))
		{
			$arr_params = array();
		}
		
		return $arr_params;
	}
	
	function getSensorValue($params, $sensor)
	{
		$result = array();
		$result['value'] = 0;
		$result['value_full'] = '';

		$param_value = getParamValue($params, $sensor['param']);
		// formula
		if (($sensor['result_type'] == 'abs') || ($sensor['result_type'] == 'rel') || ($sensor['result_type'] == 'value'))
		{
			if ($sensor['formula'] != '')
			{
				$formula = strtolower($sensor['formula']);
				if (!is_numeric($param_value))
				{
					$param_value = 0;
				}
				$formula = str_replace('x',$param_value,$formula);
				$param_value = calcString($formula);				
			}
		}
		
		if (($sensor['result_type'] == 'abs') || ($sensor['result_type'] == 'rel'))
		{
			$param_value = sprintf("%01.3f", $param_value);
			
			$result['value'] = $param_value;
			$result['value_full'] = $param_value;
		}
		else if ($sensor['result_type'] == 'logic')
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
			
			if($sensor['type'] != 'fuel')
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
	
	function getSensors($imei)
	{
		global $ms;
		
		$result = array();
		
		$q = "SELECT * FROM `gs_object_sensors` WHERE `imei`='".$imei."'";
		$r = mysqli_query($ms, $q);
		
		while($sensor=mysqli_fetch_array($r))
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
	
	function getSensorFromType($imei, $type)
	{
		global $ms;
		
		$result = array();
		
		$q = "SELECT * FROM `gs_object_sensors` WHERE `imei`='".$imei."' AND `type`='".$type."' order by param ";
		$r = mysqli_query($ms, $q);
		while($sensor=mysqli_fetch_array($r))
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
	
	// #################################################
	// END SENSOR FUNCTIONS
	// #################################################
	
	// #################################################
	// MATH FUNCTIONS
	// #################################################
	
	// needed for older than PHP 5.4 version
	if (!function_exists('hex2bin'))
	{
		function hex2bin( $str )
		{
			$sbin = "";
			$len = strlen($str);
			for ($i = 0; $i < $len; $i += 2)
			{
				$sbin .= pack("H*", substr($str, $i, 2));
			}
			return $sbin;
		}
	}
	
	function calcString($str)
	{
		$result = 0;
		try
		{
			$str = trim($str);
			$str = preg_replace('/[^0-9\(\)+-\/\*.]/', '', $str);
			$str = $str.';';
			
			return $result + eval('return '.$str);	
		}
		catch (Exception $e)
		{
			return $result;
		}
	}
	
	function getUnits($units)
	{
		$result = array();
		
		$units = explode(",", $units);
		
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
	
	function convDateToNum($dt)
        {
                $dt = str_replace('-', '', $dt);
                $dt = str_replace(':', '', $dt);
                $dt = str_replace(' ', '', $dt);
                
                return $dt;
        }
        
        function isDateInRange($dt, $start, $end)
        {
                 if ($start > $end)
                {
                        return ($dt > $start) || ($dt < $end);
                }
                else
                {
                        return ($dt > $start) && ($dt < $end);
                }
        }
	
	function getTimeDetails($sec, $show_days)
	{
		global $la;
		
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
				else
				{
					$minutes = $rest1 / 60;
				}
			}
			else
			{
				$hours = $rest / 3600;
			}
		}
		
		if ($show_days == false)
		{
			$hours += $days * 24;
			$days = 0;
		}
		
		if($days > 0){$days = $days.' '.$la['UNIT_D'].' ';}
		else{$days = false;}
		if($hours > 0){$hours = $hours.' '.$la['UNIT_H'].' ';}
		else{$hours = false;}
		if($minutes > 0){$minutes = $minutes.' '.$la['UNIT_MIN'].' ';}
		else{$minutes = false;}
		$seconds = $seconds.' '.$la['UNIT_S'];
		return $days.$hours.$minutes.$seconds;
	}
	
	function getTimeDetailsAPI($sec, $show_days)
	{
		global $la;
		
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
				else
				{
					$minutes = $rest1 / 60;
				}
			}
			else
			{
				$hours = $rest / 3600;
			}
		}
		
		if ($show_days == false)
		{
			$hours += $days * 24;
			$days = 0;
		}
		
		if($days > 0){$days = $days.' d ';}
		else{$days = false;}
		if($hours > 0){$hours = $hours.' h ';}
		else{$hours = false;}
		if($minutes > 0){$minutes = $minutes.' m ';}
		else{$minutes = false;}
		$seconds = $seconds.' s';
		
		return $days.$hours.$minutes.$seconds;
	}
	
	
	function getTimeDifferenceDetails($start_date, $end_date)
	{
		$diff = strtotime($end_date)-strtotime($start_date);
		return getTimeDetails($diff, true);
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
		
		// check for all X and Y
                if(!is_int(count($ver_arr)/2))
                {
                        array_pop($ver_arr);
                }
		
		$polySides = 0;
		$i = 0;
		
		while ($i < count($ver_arr))
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
		
		$points_arr = explode(',', $points);
		
		// check for all X and Y
                if(!is_int(count($points_arr)/2))
                {
                        array_pop($points_arr);
                }
		
		$points_num = 0;
		$i = 0;
		
		while ($i < count($points_arr))
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
		if (($area == 0) || (.5 * $ab) == 0)
		{
			return 0;
		}
		
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
		
		//if($ab + $ac == $bc) // then points are collinear - point is on the line segment
		//{
		//	return 0;
		//}
		//else
		if($angle['a'] <= 90 && $angle['b'] <= 90) // A or B are not obtuse - return height as distance
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
	// STRING/ARRAY/VALIDATION FUNCTIONS
	// #################################################
	
	function isDateValid($date)
	{
		if (empty($date) or $date === '0000-00-00' or $date === '0000-00-00 00:00:00')
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	function stringToBool($str) {
		return filter_var($str, FILTER_VALIDATE_BOOLEAN);
	}

	function searchString($str, $findme)
	{
		return preg_match('/'.$findme.'/',$str);
	}
	
	function truncateString($text, $chars)
	{
		if (strlen($text) > $chars)
		{
			$text = substr($text, 0, $chars).'...';
		}
		return $text;
	}
	
	function generatorTag()
	{
		global $gsValues;
		echo '<meta name="generator" content="'.$gsValues['GENERATOR'].'" />';
	}
	
	// #################################################
	// END STRING/ARRAY/VALIDATION FUNCTIONS
	// #################################################
	
	// #################################################
	// TEMPLATE FUNCTIONS
	// #################################################
	
	function getDefaultTemplate($name, $language)
	{
		global $ms;
		
		$result = false;
		
		$q = "SELECT * FROM `gs_templates` WHERE `name`='".$name."' AND `language`='".$language."'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);
		
		if (!$row)
		{
			$q = "SELECT * FROM `gs_templates` WHERE `name`='".$name."' AND `language`='english'";
			$r = mysqli_query($ms, $q);
			$row = mysqli_fetch_array($r);
		}
		
		if ($row)
		{
			$result = array('subject' => $row['subject'], 'message' => $row['message']);	
		}
		
		return $result;
	}
	
	// #################################################
	// END TEMPLATE FUNCTIONS
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
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);
		
		if ($row)
		{
			return $row['address'];
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
		$r = mysqli_query($ms, $q);
	}
	
	// #################################################
	// END GEOCODER FUNCTIONS
	// #################################################
	
	// #################################################
	// LANGUAGE FUNCTIONS
	// #################################################
	
	function loadLanguage($lng, $units = '')
	{
		global $ms, $la, $gsValues;
		
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
		
		// set unit strings
		$units = getUnits($units);
		
		if ($units["unit_distance"] == 'km')
		{
			$la["UNIT_SPEED"] = $la['UNIT_KPH'];
			$la["UNIT_DISTANCE"] = $la['UNIT_KM'];
			$la["UNIT_HEIGHT"] = $la['UNIT_M'];
		}
		else if ($units["unit_distance"] == 'mi')
		{
			$la["UNIT_SPEED"] = $la['UNIT_MPH'];
			$la["UNIT_DISTANCE"] = $la['UNIT_MI'];
			$la["UNIT_HEIGHT"] = $la['UNIT_FT'];
		}
		else if ($units["unit_distance"] == 'nm')
		{
			$la["UNIT_SPEED"] = $la['UNIT_KN'];
			$la["UNIT_DISTANCE"] = $la['UNIT_NM'];
			$la["UNIT_HEIGHT"] = $la['UNIT_FT'];
		}
		
		if ($units["unit_capacity"] == 'l')
		{
			$la["UNIT_CAPACITY"] = $la['UNIT_LITERS'];
		}
		else
		{
			$la["UNIT_CAPACITY"] = $la['UNIT_GALLONS'];
		}
		
		if ($units["unit_temperature"] == 'c')
		{
			$la["UNIT_TEMPERATURE"] = 'C';
		}
		else
		{
			$la["UNIT_TEMPERATURE"] = 'F';
		}
	}
	
	function getLanguageListFiles()
	{
		global $gsValues;
		
		$result = '';
		
		$path = $gsValues['PATH_ROOT'].'lng';
		$dh = opendir($path);
	    
		$languages = array();
		    
		while (($file = readdir($dh)) !== false)
		{
			if ($file != '.' && $file != '..' && $file != 'Thumbs.db')
			{
				$folder_path = $path.'/'.$file;
				
				if (is_dir($folder_path))
				{
					if (file_exists($folder_path.'/lng_main.php'))
					{
						$lng = strtolower($file);
						
						if ($lng != 'english')
						{
							$languages[] = $lng;	
						}
					}
				}
			}
		}
		
		closedir($dh);
		
		sort($languages);
		
		foreach ($languages as $value)
		{        
		    $result .= '<option value="'.$value.'">'.ucfirst($value).'</option>';
		}
		
		return $result;
	}
	
	function getLanguageList()
	{
		global $ms, $gsValues;
		
		$result = '';
		
		$languages = explode(",", $gsValues['LANGUAGES']);
		
		array_unshift($languages , 'english');
		
		foreach ($languages as $value)
		{
			if ($value != '')
			{
				$result .= '<option value="'.$value.'">'.ucfirst($value).'</option>';	
			}
		}
		
		return $result;
	}
	
	// #################################################
	// END LANGUAGE FUNCTIONS
	// #################################################
	
	// #################################################
	// USAGE FUNCTIONS
	// #################################################
	
	function checkUserUsage($user_id, $service)
	{
		global $gsValues, $ms;
		
		$result = false;
		
		if ($user_id == false)
		{
			die;
		}
		
		// get gs_users counters
		$q = "SELECT * FROM `gs_users` WHERE `id`='".$user_id."'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);
		
		$email = $row['usage_email_daily'];
		$sms = $row['usage_sms_daily'];
		$api = $row['usage_api_daily'];
		
		$email_cnt = $row['usage_email_daily_cnt'];
		$sms_cnt = $row['usage_sms_daily_cnt'];
		$api_cnt = $row['usage_api_daily_cnt'];
		
		if ($service == 'email')
		{
			if ($email != '')
                        {
                                if ($email_cnt < $email)
                                {
                                        $result = true;
                                }
                        }
                        else
                        {
                                if ($email_cnt < $gsValues['USAGE_EMAIL_DAILY'])
                                {
                                        $result = true;
                                }
                        }  
		}
		
		if ($service == 'sms')
		{
			if ($sms != '')
                        {
                                if ($sms_cnt < $sms)
                                {
                                        $result = true;
                                }
                        }
                        else
                        {
                                if ($sms_cnt < $gsValues['USAGE_SMS_DAILY'])
                                {
                                        $result = true;
                                }
                        }  
		}
		
		if ($service == 'api')
		{
			if ($api != '')
                        {
                                if ($api_cnt < $api)
                                {
                                        $result = true;
                                }
                        }
                        else
                        {
                                if ($api_cnt < $gsValues['USAGE_API_DAILY'])
                                {
                                        $result = true;
                                }
                        }  
		}
		
		return $result;
	}
	
	function updateUserUsage($user_id, $login, $email, $sms, $api)
	{
		global $ms;
		
		if ($user_id == false)
		{
			die;
		}
		
		$date = gmdate("Y-m-d");
		
		if ($login == false){$login = 0;}
		if ($email == false){$email = 0;}
		if ($sms == false){$sms = 0;}
		if ($api == false){$api = 0;}
		
		// update gs_users counters
		$q = "UPDATE gs_users SET 	usage_email_daily_cnt=usage_email_daily_cnt+".$email.",
						usage_sms_daily_cnt=usage_sms_daily_cnt+".$sms.",
						usage_api_daily_cnt=usage_api_daily_cnt+".$api."
						WHERE id='".$user_id."'";	
		$r = mysqli_query($ms, $q);
		
		// get gs_users counters
		$q = "SELECT * FROM `gs_users` WHERE `id`='".$user_id."'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);
		
		$email = $row['usage_email_daily_cnt'];
		$sms = $row['usage_sms_daily_cnt'];
		$api = $row['usage_api_daily_cnt'];
		
		// add/update user usage table
		$q = "SELECT * FROM `gs_user_usage` WHERE `user_id`='".$user_id."' AND `dt_usage`='".$date."'";
		$r = mysqli_query($ms, $q);
		
		$row = mysqli_fetch_array($r);
		
		if ($row)
		{
			$q = "UPDATE gs_user_usage SET 	login=login+".$login.",
								email=".$email.",
								sms=".$sms.",
								api=".$api."
								WHERE usage_id='".$row['usage_id']."'";	
			$r = mysqli_query($ms, $q);	
		}
	}
	
	// #################################################
	// END USAGE FUNCTIONS
	// #################################################
	
	// #################################################
	// LOG FUNCTIONS
	// #################################################
	
	function writeLog($log, $log_data)
	{
		global $ms, $gsValues;
		
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
	
	
	//code done by vetrivel ASAP
	/*
		
	function paramsMerge($old, $new)
	{
		return mergeParams($old, $new);
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
		
	function paramsParamValue($params, $param)
	{		
		return getParamValue($params, $param);
	}
	*/
	
	
	
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
	
	
	
	function paramsParamValue($params, $param)
	{
		$result = "";
		
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
	
	function getTimeDifferenceDetails_dailyboarding($start_date, $end_date)
	{
		$diff = strtotime($end_date)-strtotime($start_date);
		return getTimeDetailsboarding($diff);
	}
	
	function getTimeDetailsboarding($sec)
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
	
	//code end by vetrivel.N
		
	//code update by vetrivel.N
	function fngetFuelvalueHT($fuelP,$formul)
	{
		$formula = strtolower($formul);
		if (!is_numeric($fuelP))
		{
			$fuelP = 0;
		}
		$formula = str_replace('x',$fuelP,$formula);
		$fuelP = calcString($formula);
		
		return  $fuelP;
	}
	
	function getBoxVolume($sensor)
	{
		global $ms;
		$result="0";
		$param_value=100;

		if(isset($sensor['param']) && $sensor['param']=='adc'){
			$param_value=5;
		}
		if(isset($sensor['param']) && $sensor['param']=='fmb'){
			$param_value=90000;
		}
		
		if (($sensor['result_type'] == 'abs') || ($sensor['result_type'] == 'rel') || ($sensor['result_type'] == 'value'))
		{
			if ($sensor['formula'] != '')
			{
				$formula = strtolower($sensor['formula']);
				if (!is_numeric($param_value))
				{
					$param_value = 0;
				}
				$formula = str_replace('x',$param_value,$formula);
				$result = calcString($formula);				
			}
		}
		else if ($sensor['result_type'] == 'percentage')
		{
			 $result = 100;
		}
		if(isset($sensor['param']) && $sensor['param']=='lls_fuel1'){
			$tanksize=0;
			$tanksize = @$sensor['tank_size'];
			if($tanksize>0){
				$result=$tanksize;
			}else{
				$result=0;
			}
		}
		
		return $result;
	} 
	
	
	function getIFSData($imei, $accuracy, $fuel_sensors, $dtf, $dtt,$raw)
	{
		//if (!checkUserToObjectPrivileges($imei))
		//{
		//	return;
		//}
		global $ms;
		$ifs_data = array();
		
		$q = "SELECT DISTINCT dt_tracker, speed, params,mileage FROM `gs_object_data_".$imei."` 
                    WHERE dt_tracker BETWEEN '".$dtf."' AND '".$dtt."' ORDER BY dt_tracker ASC";

		$r = mysqli_query($ms, $q);
        $itr=0; $itrfl=0;$itrfltr=0;
        $sensor = $fuel_sensors[0];
		if($r!=false){
		while($route_data=mysqli_fetch_array($r,MYSQLI_ASSOC))
		{
	
			//$params = json_decode($route_data['params'],true);
			$paramsV = paramsToArray($route_data['params']); // CODE UPDATED BY VETRIVEL.N			
            $data = getSensorValue($paramsV, $sensor);
			$dt_tracker = convUserTimezone($route_data['dt_tracker']);
			$speed = $route_data['speed'];
			//update by vetrivel.N
			$mileage = $route_data['mileage'];
            if($speed == null){
                $speed = 0;
            }             
			$acc = getParamValue($paramsV, 'acc');
            if($acc == null){
                $acc = 0;
            }    
            //$fuel = paramsParamValue($route_data['params'], 'fuel1');
            $fuel_ltrs = $data['value'];
            if($fuel_ltrs == null){
                $fuel_ltrs = 0;
            }            
            
            if($sensor['param']=='adc'){
				$fuel = getParamValue($paramsV, 'adc');
				$fuel =$fuel*100/5;
			}else if($sensor['param']=='lls_fuel1'){
				// $fuel = getParamValue($paramsV, 'lls_fuel1');
				// if($fuel_sensors[0]['tank_size']>0){
				// 	$fuel=$fuel_ltrs/$fuel_sensors[0]['tank_size']*100;
				// }else{
				// 	$fuel =$fuel_ltrs;
				// }
				$fuel =$fuel_ltrs;
			}else{
				$fuel = getParamValue($paramsV, 'fuel1');
			}

            if($fuel == null){
                $fuel = 0;
            }
            $temp1 = getParamValue($paramsV, 'temp1');
            
            if ($temp1 == null){
                $temp1 = 0;
            }
			if($_SESSION["unit_distance"] == "mi")
			{
				$speed = floor($speed / 1.609344);
			}		

			if((($fuel!=".00" &&  $fuel!="00.0" && $fuel!="0.0" && $fuel!="00.00" 
			 && $fuel!="0.00" && $fuel!=""  && $fuel<=100) || count($ifs_data)<=0) || ($sensor['param']=='lls_fuel1' && $fuel_ltrs>0) || $raw )
			 {
					$ifs_data[] = array( "date" => $dt_tracker,
								"speed" => $speed,
								"acc" => $acc,
		                        "fuel" => $fuel,
		                        "fuelltr" => $fuel_ltrs,
		                        "temp1" => $temp1,
								"mileage" => round($mileage,2)
								//,"params"=>$route_data['params']
								);
			 }		
			else 
			{
				if(count($ifs_data)>0)
				{
					$ifs_data[] = array( "date" => $dt_tracker,
						"speed" => $speed,
						"acc" => $acc,
                        "fuel" => $ifs_data[count($ifs_data)-1]["fuel"],
                        "fuelltr" => $ifs_data[count($ifs_data)-1]["fuelltr"],
                        "temp1" => $temp1,
						"mileage" => round($mileage,2)
						//,"params"=>$route_data['params']
						);
				}
			}		
						/*
						if($itr==0)
			{
						$itr=$itr+1;
						
						$itrfl=$fuel;
						$itrfltr=$fuel_ltrs;
			}
			else if($itr==5)
			{
				$itr=0;
				$itrfl=$itrfl/5;
				$itrfltr=$itrfl*1.2;
				$ifs_data[(count($ifs_data)-1)]['fuel']=$itrfl;
				$ifs_data[(count($ifs_data)-1)]['fuelltrs']=$itrfl;
			}
			else 
			{
				$itr=$itr+1;
				$itrfl=$itrfl+$fuel;
				$itrfltr=$itrfltr+$fuel_ltrs;
			}*/

		}

		
		}
		
		
		return $ifs_data;
	}
	
	function getIFSDataFuelold($imei, $accuracy, $fuel_sensors, $dtf, $dtt)
	{
		//if (!checkUserToObjectPrivileges($imei))
		//{
		//	return;
		//}
		
		global $ms;
		
		$ifs_data = array();
		
		$q = "SELECT DISTINCT dt_tracker, speed, params,mileage FROM `gs_object_data_".$imei."` 
                    WHERE dt_tracker BETWEEN '".$dtf."' AND '".$dtt."' ORDER BY dt_tracker ASC";
					
		$r = mysqli_query($ms, $q);
        
        $sensor = $fuel_sensors[0];
		
        if(!$r)
        {
        	return $ifs_data;
        }
        
		while($route_data=mysqli_fetch_array($r,MYSQLI_ASSOC))
		{
		  
            $data = getSensorValue($route_data['params'], $sensor);
            
            $data2=null;
            $data3=null;
            $data4=null;
            $fuel2="";$fuel_ltrs2="";
			$fuel3="";$fuel_ltrs3="";
			$fuel4="";$fuel_ltrs4="";
			
		
			
          		for($if=0;$if<count($fuel_sensors);$if++)
            	{
            		try{
            	
					if($fuel_sensors[$if]["param"]=="fuel2")
					{
						  $data2 = getSensorValue($route_data['params'], $fuel_sensors[$if]);
            
						  $fuel_ltrs2 = $data2['value'];
						  if($fuel_ltrs2 == null){
						  	$fuel_ltrs2 = 0;
						  }

						  $fuel2 = paramsParamValue($route_data['params'], 'fuel2');
						  if($fuel2 == null){
						  	$fuel2 = 0;
						  }
            
					}
					else if($fuel_sensors[$if]["param"]=="fuel2")
					{
							     
						$data3 = getSensorValue($route_data['params'], $fuel_sensors[$if]);

						$fuel_ltrs3 = $data3['value'];
						if($fuel_ltrs3 == null){
							$fuel_ltrs3 = 0;
						}


						$fuel3 = paramsParamValue($route_data['params'], 'fuel3');
						if($fuel3 == null){
							$fuel3 = 0;
						}
					}
					else if($fuel_sensors[$if]["param"]=="fuel2")
					{
					   $data4 = getSensorValue($route_data['params'], $fuel_sensors[$if]);
            
					   $fuel_ltrs4 = $data4['value'];
					   if($fuel_ltrs4 == null){
					   	$fuel_ltrs4 = 0;
					   }
					    
					   $fuel4 = paramsParamValue($route_data['params'], 'fuel4');
					   if($fuel4 == null){
					   	$fuel4 = 0;
					   }
						
					}
            		}catch (Exception $e)
            		{
            				print($e);
            		}
            	}
          
          
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
            $fuel_ltrs = $data['value'];
            if($fuel_ltrs == null){
                $fuel_ltrs = 0;
            }  
            
          
                      
            $fuel = paramsParamValue($route_data['params'], 'fuel1');
            if($fuel == null){
                $fuel = 0;
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
						"fuel2" => $fuel2,
                        "fuelltrs2" => $fuel_ltrs2,
						"fuel3" => $fuel3,
                        "fuelltrs3" => $fuel_ltrs3,
						"fuel4" => $fuel4,
                        "fuelltrs4" => $fuel_ltrs4,
						"mileage" => $mileage);
		}
		
		
		return $ifs_data;
	}
	
	
	function getIFSDataFuel($imei, $accuracy, $fuel_sensors, $dtf, $dtt)
	{
		//if (!checkUserToObjectPrivileges($imei))
		//{
		//	return;
		//}
		global $ms;
		$ifs_data = array();
		$route=array();

		if (!is_array($fuel_sensors) && !$fuel_sensors instanceof Countable) {
		    return array($ifs_data,$route);
		}

		$extraqry="";
		if($accuracy['fueltype']=="FMS")
		{
			$extraqry=",fuelused";	
		}
		
		$q = "SELECT dt_tracker,
					lat,
					lng,
					altitude,
					angle,
					speed,
					params,mileage".$extraqry."
					 FROM `gs_object_data_".$imei."` 
                    WHERE dt_tracker BETWEEN '".$dtf."' AND '".$dtt."' ORDER BY dt_tracker ASC";
		
		$r = mysqli_query($ms,$q);
        $sensor = $fuel_sensors[0];
        if(!$r)
        {
        	return array($ifs_data,$route);
        }
		while($route_data=mysqli_fetch_array($r,MYSQLI_ASSOC))
		{
		  //$params = json_decode($route_data['params'],true);
			$paramsV = paramsToArray($route_data['params']); // CODE UPDATED BY VETRIVEL.N		
			
            $data = getSensorValue($paramsV, $sensor);
			$fuelused =0;
			if($accuracy['fueltype']=="FMS")
			{
				$fuelused = $route_data['fuelused'];
			}
			
            $data2=null;
            $data3=null;
            $data4=null;
            $fuel2="";$fuel_ltrs2="";
			$fuel3="";$fuel_ltrs3="";
			$fuel4="";$fuel_ltrs4="";

          		for($if=0;$if<count($fuel_sensors);$if++)
            	{
            		try{
            	
					if($fuel_sensors[$if]["param"]=="fuel2")
					{
						  $data2 = getSensorValue($paramsV, $fuel_sensors[$if]);
            
						  $fuel_ltrs2 = $data2['value'];
						  if($fuel_ltrs2 == null){
						  	$fuel_ltrs2 = 0;
						  }

						  $fuel2 = getParamValue($paramsV, 'fuel2');
						  if($fuel2 == null){
						  	$fuel2 = 0;
						  }
            
					}
					else if($fuel_sensors[$if]["param"]=="fuel3")
					{
							     
						$data3 = getSensorValue($paramsV, $fuel_sensors[$if]);

						$fuel_ltrs3 = $data3['value'];
						if($fuel_ltrs3 == null){
							$fuel_ltrs3 = 0;
						}


						$fuel3 = getParamValue($paramsV, 'fuel3');
						if($fuel3 == null){
							$fuel3 = 0;
						}
					}
					else if($fuel_sensors[$if]["param"]=="fuel4")
					{
					   $data4 = getSensorValue($paramsV, $fuel_sensors[$if]);
            
					   $fuel_ltrs4 = $data4['value'];
					   if($fuel_ltrs4 == null){
					   	$fuel_ltrs4 = 0;
					   }
					    
					   $fuel4 = getParamValue($paramsV, 'fuel4');
					   if($fuel4 == null){
					   	$fuel4 = 0;
					   }
						
					}
            		}catch (Exception $e)
            		{
            				print($e);
            		}
            	}
          
          
			$dt_tracker = convUserTimezone($route_data['dt_tracker']);
			$speed = $route_data['speed'];
			//update by vetrivel.N
			$mileage = round($route_data['mileage'],2);
            if($speed == null){
                $speed = 0;
            }             
			$acc = getParamValue($paramsV, 'acc');
            if($acc == null){
                $acc = 0;
            }    
            //$fuel = paramsParamValue($paramsV, 'fuel1');
            $fuel_ltrs=0;
            $fuel_ltrs = $data['value'];
            if($fuel_ltrs == null){
                $fuel_ltrs = 0;
            }  
            
          
                      
            $fuel = getParamValue($paramsV, 'fuel1');
            if($fuel == null){
                $fuel = 0;
            }
            

			if($_SESSION["unit_distance"] == "mi")
			{
				$speed = floor($speed / 1.609344);
			}

			if(($fuel!=".00" &&  $fuel!="00.0" && $fuel!="0.0" && $fuel!="00.00" 
			 && $fuel!="0.00" && $fuel!=""  && $fuel<=100) || count($ifs_data)<=0){
			$ifs_data[] = array( "date" => $dt_tracker,
						"speed" => $speed,
						"acc" => $acc,
                        "fuel" => $fuel,
                        "fuelltrs" => $fuel_ltrs,
						"fuel2" => $fuel2,
                        "fuelltrs2" => $fuel_ltrs2,
						"fuel3" => $fuel3,
                        "fuelltrs3" => $fuel_ltrs3,
						"fuel4" => $fuel4,
                        "fuelltrs4" => $fuel_ltrs4,
						"mileage" => round($mileage,2));
			}
			else 
			{
				if(count($ifs_data)>0)
				{
					$ifs_data[] = array( "date" => $dt_tracker,
						"speed" => $speed,
						"acc" => $acc,
                         "fuel" => $ifs_data[count($ifs_data)-1]["fuel"],
                        "fuelltrs" => $ifs_data[count($ifs_data)-1]["fuelltrs"],
						"fuel2" => $fuel2,
                        "fuelltrs2" => $fuel_ltrs2,
						"fuel3" => $fuel3,
                        "fuelltrs3" => $fuel_ltrs3,
						"fuel4" => $fuel4,
                        "fuelltrs4" => $fuel_ltrs4,
						"mileage" => round($mileage,2));
				}
			}
			
		    $dt_tracker = convUserTimezone($route_data['dt_tracker']);
			$lat = $route_data['lat'];
			$lng = $route_data['lng'];
			$altitude = $route_data['altitude'];
			$angle = $route_data['angle'];
			$speed = $route_data['speed'];
		
			
			$mileage = $route_data['mileage'];
			
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
					if(isset($paramsV["fuel1"]))
					{
						if(($paramsV["fuel1"]!=".00" &&  $paramsV["fuel1"]!="00.0" && $paramsV["fuel1"]!="0.0" && $paramsV["fuel1"]!="00.00"
					 	&& $paramsV["fuel1"]!="0.00" && $paramsV["fuel1"]!=""  && $paramsV["fuel1"]<=100 ) || count($route)<=0){
					 	$route[] = array(	$dt_tracker,
					 	$lat,
					 	$lng,
					 	$altitude,
					 	$angle,
					 	$speed,
					 	$paramsV,
					 	round($mileage,2),
					 	$fuelused);
					 }
					 else
					 {
					 	if(count($route)>0)
					 	{
					 		$paramsV["fuel1"]=$route[count($route)-1][6]["fuel1"];
					 		$route[] = array(	$dt_tracker,
					 		$lat,
					 		$lng,
					 		$altitude,
					 		$angle,
					 		$speed,
					 		$paramsV,
					 		round($mileage,2),
					 		$fuelused);
					 	}
					 }
					}
				}
			}
			
		}

		return array($ifs_data,$route);
	}
	
	
	function getIFSDataTemp($imei, $accuracy, $fuel_sensors, $dtf, $dtt)
	{
		//if (!checkUserToObjectPrivileges($imei))
		//{
		//	return;
		//}
		global $ms;
		$ifs_data = array();
		
		$q = "SELECT DISTINCT dt_tracker,lat,lng,altitude, speed, params,mileage FROM gs_object_data_".$imei."
                    WHERE dt_tracker BETWEEN '".$dtf."' AND '".$dtt."' ORDER BY dt_tracker ASC";
					
		$r = mysqli_query($ms, $q);
        
        $sensor = $fuel_sensors[0];
        while($route_data=mysqli_fetch_array($r,MYSQLI_ASSOC))
		{
		  	$paramsV = paramsToArray($route_data['params']); 
			$dt_tracker = convUserTimezone($route_data['dt_tracker']);
			$speed = $route_data['speed'];
			//update by vetrivel.N
			$mileage = $route_data['mileage'];
            if($speed == null){
                $speed = 0;
            }             
			$acc = getParamValue($paramsV, 'acc');
            if($acc == null){
                $acc = 0;
            }    
                     
            $temp1 = getParamValue($paramsV, 'temp1');
            
            if ($temp1 == null){
                $temp1 = 0;
            }
            
			 $temp2 = getParamValue($paramsV, 'temp2');
            
            if ($temp2 == null){
                $temp2 = 0;
            }
            
            
			 $temp3 = getParamValue($paramsV, 'temp3');
            
            if ($temp3 == null){
                $temp3 = 0;
            }
            			
			if($_SESSION["unit_distance"] == "mi")
			{
				$speed = floor($speed / 1.609344);
			}
						$ifs_data[] = array( "date" => $dt_tracker,
						"lat" => $route_data['lat'],
						"lng" => $route_data['lng'],
						"altitude" => $route_data['lng'],
						"speed" => $speed,
						"acc" => $acc,
                        "temp1" => $temp1,
			 			"temp2" => $temp2,
			 			"temp3" => $temp3,
						"mileage" => round($mileage,2));
		}
		
		return $ifs_data;
	}
	
	
	function getIFSDataTempReport($imei, $accuracy, $fuel_sensors, $dtf, $dtt,$stop_duration)
	{
		//if (!checkUserToObjectPrivileges($imei))
		//{
		//	return;
		//}
		global $ms;
		$stop_duration=($stop_duration*60);
		
		$ifs_data = array();
		
		$q = "SELECT DISTINCT dt_tracker,lat,lng,altitude, speed, params,mileage FROM `gs_object_data_".$imei."` 
                    WHERE dt_tracker BETWEEN '".$dtf."' ANd '".$dtt."' GROUP BY 
					UNIX_TIMESTAMP(dt_tracker) DIV ".$stop_duration." ORDER BY dt_tracker ASC";
					
		$r = mysqli_query($ms, $q);
        
        $sensor = $fuel_sensors[0];
		
		while($route_data=mysqli_fetch_array($r,MYSQLI_ASSOC))
		{
		  	$paramsV = paramsToArray($route_data['params']); 
			$dt_tracker = convUserTimezone($route_data['dt_tracker']);
			$speed = $route_data['speed'];
			//update by vetrivel.N
			$mileage = $route_data['mileage'];
            if($speed == null){
                $speed = 0;
            }             
			$acc = paramsParamValue($paramsV, 'acc');
            if($acc == null){
                $acc = 0;
            }    
          
            
            $temp1 = paramsParamValue($paramsV, 'temp1');
            
            if ($temp1 == null){
                $temp1 = 0;
            }
            
			 $temp2 = paramsParamValue($paramsV, 'temp2');
            
            if ($temp2 == null){
                $temp2 = 0;
            }
            
			 $temp3 = paramsParamValue($paramsV, 'temp3');
            
            if ($temp3 == null){
                $temp3 = 0;
            }
            
			
			if($_SESSION["unit_distance"] == "mi")
			{
				$speed = floor($speed / 1.609344);
			}
			
			$ifs_data[] = array( "date" => $dt_tracker,
						"lat" => $route_data['lat'],
						"lng" => $route_data['lng'],
						"altitude" => $route_data['lng'],
						"speed" => $speed,
						"acc" => $acc,
                        "temp1" => $temp1,
			 			"temp2" => $temp2,
			 			"temp3" => $temp3,
						"mileage" => $mileage);
		}
		
		return $ifs_data;
	}
	
	function Insert_Issue($imei,$reason,$detail,$count)
	{
		global $ms;
		$q = "insert into z_issue (create_date,imei,reason,detail,count) values
		('".gmdate("Y-m-d H:i:s")."','".$imei."','".$reason."','".$detail."','".$count."')";
		$r = mysqli_query($ms, $q);		
	}
	
	function GetCSNFromRemote($user_id)
	{
		global $ms,$gsValues;
		$resultcsn=array();
		$qgetuser="select userapikey from  gs_users where  id=".$user_id;
		$rslt= mysqli_query($ms,$qgetuser);
		if ($row=mysqli_fetch_assoc($rslt))
		{
			
			$url = $gsValues['POST_RFID'][$user_id]["url"].'/api/GetCSN?Key='.$row["userapikey"];
			$options = array(
								'http' => array(
								'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
								'method'  => 'GET'//,
			)
			);
			
			$context  = stream_context_create($options);
			$result = file_get_contents($url, false, $context);
			$result=json_decode($result, true);
			if($result["Type"]=="S")
			{
				$updatecnt=0;
				$rtncsn=mysqli_query($ms,"Delete from csnmast");
				$resultcsn["Oracle"]=count($result["Mydata"]);
				for($ivel=0;$ivel<count($result["Mydata"]);$ivel++)
				{
					$cardnoo=$result["Mydata"][$ivel]["CARDNO"];
					$cardnoo=str_pad($cardnoo,10,"0",STR_PAD_LEFT);
					
					$qincsn=" insert into csnmast values('".$result["Mydata"][$ivel]["EMPID"]."',
					'".$cardnoo."','".$result["Mydata"][$ivel]["FIRSTNAME"]."'
					,'".$result["Mydata"][$ivel]["GNAME"]."','".$result["Mydata"][$ivel]["ROUTENO"]."');";
					$rtncsn=mysqli_query($ms,$qincsn);
					if( mysqli_affected_rows($ms)>0)$updatecnt++;
					
				}
				
				$resultcsn["Updated"]=$updatecnt;
				$resultcsn["Failed"]=$resultcsn["Oracle"]-$updatecnt;
			}
		}
		return $resultcsn;
	}
	
	function GetCSN_Local($user_id)
	{
		global $ms,$gsValues;
		$result=array();
		$q="select * from csnmast";
		if($row=mysqli_query($ms,$q))
		{
			$csnlist=array();
			while($rowcsn=mysqli_fetch_assoc($row))
			{
				$csnlist[]=$rowcsn;
			}
				
			$result['Type'] = 'S';
			$result['Mydata'] = array("Count"=>count($csnlist),"List"=>$csnlist);
			$result['Message'] = "CSN Details Not Found!";
			 
		}
		else
		{
			$result['Type'] = 'E';
			$result['Mydata'] = "";
			$result['Message'] = "CSN Details Not Found!";
		}
		return $result;

	}
	
	function getObject($imei)
	{
		global $ms;
		$q = "SELECT * FROM `gs_objects` WHERE `imei`='".$imei."'";
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
	
	function reportlist_selecteduser($id){
global $ms;
$qq="select rl.rid,rl.report_lng,rl.report_name,IFNULL(rp.active,'D') active from c_report_list rl left join c_report_privilege rp on rl.rid=rp.rid and rl.active='A' and rp.user_id='".$id."'";
$qq1=mysqli_query($ms,$qq);
while($row2=mysqli_fetch_assoc($qq1)){
	$select_report[$row2['rid']]=$row2;	
}
return $select_report;
}

function chkreportgroup($user_report,$userreport_group)
{
	for($ir=0;$ir<count($user_report);$ir++)
    {
	   	if($user_report[$ir]["group_id"]==$userreport_group && $user_report[$ir]["active"]=='A')
	   		return $userreport_group;
    }
	return false;
}

function loginuserreportlist($id){
	global $ms;
	$_SESSION['listdata']=array();
	$_SESSION['report_group']=array();
	if(isset($_SESSION["cpanel_user_id"])){
		$us_id=$_SESSION["cpanel_user_id"];
	}else{
		$us_id=$id;
	}
	$qq = "select rl.rid,rl.report_lng,rl.group_id,rl.report_name,IFNULL(rp.active,'D') active from c_report_list rl left join c_report_privilege rp on rl.rid=rp.rid and rl.active='A' and rp.user_id='".$us_id."'";
	$rr = mysqli_query($ms, $qq);
	while($row1=mysqli_fetch_assoc($rr))
	{
		$report_list[]=$row1;
		$_SESSION['listdata'][]=$row1;
	}
	$qq1 = "select * from c_report_group";

	$rr1 = mysqli_query($ms, $qq1);
	
	while($row2=mysqli_fetch_assoc($rr1))
	{
		$report_group[$row2['gid']]=$row2;
		$_SESSION['report_group'][]=$row2;
	}
	
	return $report_list;
}
	//Code Modified End By Vetrivel.NR

//CAN deletedthis function
function update_report_listOLD($id,$report_list){
	global $ms;
		$exreport_list=explode(',', $report_list);
		$sq=mysqli_query($ms,"SELECT * FROM c_report_privilege where user_id='".$id."'");
		while($row=mysqli_fetch_assoc($sq)){
			if(in_array($row['rid'],$exreport_list)){$action='A';}
			else{$action='D';
				$q="SELECT * from gs_users where manager_id='".$id."'";
				$r=mysqli_query($ms,$q);
				while ($ro=mysqli_fetch_assoc($r)) {
				if($ro['manager_id']!=$ro['id']){
					$uq1=mysqli_query($ms,"UPDATE c_report_privilege SET active='".$action."' WHERE user_id='".$ro['id']."' and `rid`='".$row['rid']."'");
					}
				}
			}
			$uq=mysqli_query($ms,"UPDATE c_report_privilege SET active='".$action."' WHERE user_id='".$id."' and `rid`='".$row['rid']."'");
			

		}
}

function update_report_list($id, $report_list) {
    global $ms;
    $exreport_list = explode(',', $report_list);

    // Get all existing privileges for the user
    $existing_reports = [];
    $sq = mysqli_query($ms, "SELECT * FROM c_report_privilege WHERE user_id = '" . $id . "'");
    while ($row = mysqli_fetch_assoc($sq)) {
        $existing_reports[$row['rid']] = $row;
    }

    // First handle all rids in the input list
    foreach ($exreport_list as $rid) {
        if (isset($existing_reports[$rid])) {
            // If it exists, just activate
            mysqli_query($ms, "UPDATE c_report_privilege SET active = 'A' WHERE user_id = '" . $id . "' AND rid = '" . $rid . "'");
        } else {
            // If it doesn't exist, insert new
            mysqli_query($ms, "INSERT INTO c_report_privilege (user_id, rid, active) VALUES ('" . $id . "', '" . $rid . "', 'A')");
        }
    }

    // Now handle deactivation for any reports that are not in the list
    foreach ($existing_reports as $rid => $row) {
        if (!in_array($rid, $exreport_list)) {
            // Deactivate for current user
            mysqli_query($ms, "UPDATE c_report_privilege SET active = 'D' WHERE user_id = '" . $id . "' AND rid = '" . $rid . "'");

            // Also update sub-users
            $q = "SELECT * FROM gs_users WHERE manager_id = '" . $id . "'";
            $r = mysqli_query($ms, $q);
            while ($ro = mysqli_fetch_assoc($r)) {
                if ($ro['manager_id'] != $ro['id']) {
                    mysqli_query($ms, "UPDATE c_report_privilege SET active = 'D' WHERE user_id = '" . $ro['id'] . "' AND rid = '" . $rid . "'");
                }
            }
        }
    }
}


function update_google_useage()
{
	global $ms;
	if ($_SESSION["privileges"] == 'subuser')
	{
		$user_id = $_SESSION["manager_id"];
	}
	else
	{
		$user_id = $_SESSION["user_id"];
	}

	$date = gmdate("Y-m-d");
	$q = "UPDATE gs_user_usage SET 	google_address=google_address+1	WHERE dt_usage='".$date."' and user_id='".$user_id."'";	
	$r = mysqli_query($ms, $q);	

}

	//Code Modified End By Vetrivel.NR

// by Nandha
function check_google_useage_count(){

	global $ms;
	if ($_SESSION['privileges'] == 'subuser')
	{
		$user_id = $_SESSION["manager_id"];
	}
	else
	{
		$user_id = $_SESSION["user_id"];
	}

	$qs="SELECT COUNT(imei) as imei FROM gs_user_objects WHERE user_id='".$user_id."'";
	$rs=mysqli_query($ms,$qs);
	$rows=mysqli_fetch_assoc($rs);
	$objectcount=$rows['imei'];
	$totaddcount=$objectcount*40;
	$date = gmdate("Y-m-d");
	$q = "SELECT google_address FROM gs_user_usage where user_id='".$user_id."' and dt_usage='".$date."'";	
	$r = mysqli_query($ms, $q);
	$row=mysqli_fetch_assoc($r);
	$todayreq=$row['google_address'];

	if($row['google_address']<$totaddcount){
		return true;
	}else{
		return false;
	}
}

function fuelConsolidateData($imei, $dtf, $dtt){
	global $_SESSION, $la, $user_id;
		
		$result = '';
		
		$accuracy = getObjectAccuracy($imei);
		$fuel_sensors = getSensorFromType($imei, 'fuel');
		
		if(!$fuel_sensors)
		{
			$return=$la['SENSOR_NOT_ADD'];
		}
		$retndata = getIFSDataFuel($imei, $accuracy, $fuel_sensors, $dtf, $dtt);
		$route=array();
		if (count($retndata)> 0) // || ($fuel_sensors == false))
		{
			$route=$retndata[1];		
		}
		$ff = getFuelRecords($route, $accuracy, $fuel_sensors);
		return $ff;
}

function getFuelRecords($route, $accuracy, $fuel_sensors){
	$result = array();
	$result['fillings'] = array();

	if ($fuel_sensors == false)
	{
		return array('Start'=>0,'End'=>0,'Filling'=>0);
	}
	$startf='';$endf="";$filledv=0;$fruelvalue='';
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
			if($startf==''){
				$startf=$before['value'];
			}else{
				$endf=$after['value'];
			}
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
						$filledv+=$filled;
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
					 	 	//$filledv=$after['value_full']-$beforev;
							$afterValue = floatval($after['value_full']);
							$beforeValue = floatval($beforev);

							$filledv = $afterValue - $beforeValue;
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
					 	 			$filledv+=$filled;	
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
					 	 	$filledv+=$filled;				 	 		
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
$fruelvalue=array('Start'=>$startf,'End'=>$endf,'Filling'=>$filledv);
return $fruelvalue;
	
}

function addUserSettings($user_id,$user_set,$subuser_set,$object_set,$group_set,$event_set,$zone_set,$route_set,$marker_set,$duplicate_set,$clr_history_set){
	global $_SESSION, $la,$ms;
	$userSettings=checkUserSettings($_SESSION['user_id']);
	$user_set=json_encode(validateUserSettings($userSettings['cpanel_user'],$user_set));
	$object_set=json_encode(validateUserSettings($userSettings['object'],$object_set));
	$subuser_set=json_encode(validateUserSettings($userSettings['subaccount'],$subuser_set));
	$group_set=json_encode(validateUserSettings($userSettings['group'],$group_set));
	$marker_set=json_encode(validateUserSettings($userSettings['markers'],$marker_set));
	$route_set=json_encode(validateUserSettings($userSettings['route'],$route_set));
	$event_set=json_encode(validateUserSettings($userSettings['events'],$event_set));
	$zone_set=json_encode(validateUserSettings($userSettings['zones'],$zone_set));
	$duplicate_set=json_encode(validateUserSettings($userSettings['duplicate'],$duplicate_set));
	$clr_history_set=json_encode(validateUserSettings($userSettings['clr_history'],$clr_history_set));

	$q="INSERT INTO gs_user_settings (`user_id`,`cpanel_user`,`object`,`subaccount`,`group`,`markers`,`events`,`route`,`zones`,`duplicate`,`clr_history`,`update_date`) VALUES ('".$user_id."','".$user_set."','".$object_set."','".$subuser_set."','".$group_set."','".$marker_set."','".$event_set."','".$route_set."','".$zone_set."','".$duplicate_set."','".$clr_history_set."','".gmdate("Y-m-d H:i:s")."')";
	mysqli_query($ms,$q);

}

function updateUserSettings($user_id,$user_set,$subuser_set,$object_set,$group_set,$event_set,$zone_set,$route_set,$marker_set,$duplicate_set,$clr_history_set){
	global $_SESSION, $la,$ms;

	if(isset($_SESSION['cpanel_user_id'])){
		$userSettings=checkUserSettings($_SESSION["cpanel_user_id"]);
	}else{
		$userSettings=checkUserSettings($_SESSION["user_id"]);
	}

	$user_set=json_encode(validateUserSettings($userSettings['cpanel_user'],$user_set));
	$object_set=json_encode(validateUserSettings($userSettings['object'],$object_set));
	$subuser_set=json_encode(validateUserSettings($userSettings['subaccount'],$subuser_set));
	$group_set=json_encode(validateUserSettings($userSettings['group'],$group_set));
	$marker_set=json_encode(validateUserSettings($userSettings['markers'],$marker_set));
	$route_set=json_encode(validateUserSettings($userSettings['route'],$route_set));
	$event_set=json_encode(validateUserSettings($userSettings['events'],$event_set));
	$zone_set=json_encode(validateUserSettings($userSettings['zones'],$zone_set));
	$duplicate_set=json_encode(validateUserSettings($userSettings['duplicate'],$duplicate_set));
	$clr_history_set=json_encode(validateUserSettings($userSettings['clr_history'],$clr_history_set));
	$qs="SELECT * FROM gs_user_settings where `user_id`='".$user_id."'";
	$r=mysqli_query($ms,$qs);
	if(mysqli_num_rows($r)!=0){
		$q="UPDATE gs_user_settings SET `cpanel_user`='".$user_set."',`object`='".$object_set."',`subaccount`='".$subuser_set."',`group`='".$group_set."',`markers`='".$marker_set."',`events`='".$event_set."',`route`='".$route_set."',`zones`='".$zone_set."',`duplicate`='".$duplicate_set."',`clr_history`='".$clr_history_set."',`update_date`='".gmdate("Y-m-d H:i:s")."' where `user_id`='".$user_id."'";
	}else{
		$q="INSERT INTO gs_user_settings (`user_id`,`cpanel_user`,`object`,`subaccount`,`group`,`markers`,`events`,`route`,`zones`,`duplicate`,`clr_history`,`update_date`) VALUES ('".$user_id."','".$user_set."','".$object_set."','".$subuser_set."','".$group_set."','".$marker_set."','".$event_set."','".$route_set."','".$zone_set."','".$duplicate_set."','".$clr_history_set."','".gmdate("Y-m-d H:i:s")."')";
	}
	mysqli_query($ms,$q);
}

function checkUserSettings($id){
	global $ms,$la;
	$result=array();
	
	// $q="SELECT b.cpanel_user,b.object,b.subaccount,b.group,b.markers,b.route,b.events,b.zones,b.duplicate,b.clr_history FROM gs_users a left join gs_user_settings b on a.id=b.user_id where a.id='".$id."'";
	
	$q="SELECT a.cpanel_user,a.object,a.subaccount,a.group,a.markers,a.route,a.events,a.zones,a.duplicate,a.clr_history,b.* FROM gs_user_settings a LEFT JOIN gs_users b ON a.user_id=b.id where b.id='".$id."'";

	$r=mysqli_query($ms,$q);
	if(mysqli_num_rows($r)>0){
		while($row=mysqli_fetch_assoc($r)){
			$result=$row;
		}
	}else{
		$result['cpanel_user']='{"add":false,"edit":false,"delete":false}';
		$result['object']='{"add":false,"edit":false,"delete":false}';
		$result['subaccount']='{"add":false,"edit":false,"delete":false}';
		$result['group']='{"add":false,"edit":false,"delete":false}';
		$result['markers']='{"add":false,"edit":false,"delete":false}';
		$result['route']='{"add":false,"edit":false,"delete":false}';
		$result['events']='{"add":false,"edit":false,"delete":false}';
		$result['zones']='{"add":false,"edit":false,"delete":false}';
		$result['duplicate']='{"add":false,"edit":false,"delete":false}';
		$result['clr_history']='{"add":false,"edit":false,"delete":false}';
	}
	return $result;
}

function validateUserSettings($user_set,$new_user_set){

	$result=array();
	
	$user=json_decode($user_set,true);
	$new_user=json_decode($new_user_set,true);

	if(isset($user['add']) && $user['add']==true && isset($new_user['add'])){
		$result['add']=$new_user['add'];
	}else{
		$result['add']=false;
	}

	if(isset($user['edit']) && $user['edit']==true && isset($new_user['edit'])){
		$result['edit']=$new_user['edit'];
	}else{
		$result['edit']=false;
	}

	if(isset($user['delete']) && $user['delete']==true && isset($new_user['delete'])){
		$result['delete']=$new_user['delete'];
	}else{
		$result['delete']=false;
	}
	return $result;
}

function checkSettingsPrivileges($n,$s){
	
	if(isset($_SESSION['cpanel_user_id'])){
		$userSettings=checkUserSettings($_SESSION["cpanel_user_id"]);
	}else{
		$userSettings=checkUserSettings($_SESSION["user_id"]);
	}
	switch($n){
		case 'cpanel_user':
			$cpaneluser=json_decode($userSettings['cpanel_user'],true);
			if($cpaneluser[$s]==true){$value=true;}else{$value=false;}
			return $value;
		break;
		case 'object':			
			$object=json_decode($userSettings['object'],true);
			if($object[$s]==true){$value=true;}else{$value=false;}
			return $value;
		break;
		case 'clr_history':
			$clrhistory=json_decode($userSettings['clr_history'],true);
			if($clrhistory[$s]==true){$value=true;}else{$value=false;}
			return $value;
		break;
		case 'duplicate':
			$duplicate=json_decode($userSettings['duplicate'],true);
			if($duplicate[$s]==true){$value=true;}else{$value=false;}
			return $value;
		break;
		case 'events':
			$events=json_decode($userSettings['events'],true);
			if($events[$s]==true){$value=true;}else{$value=false;}
			return $value;
		break;
		case 'markers':
			$markers=json_decode($userSettings['markers'],true);
			if($markers[$s]==true){$value=true;}else{$value=false;}
			return $value;
		break;
		case 'route':
			$route=json_decode($userSettings['route'],true);
			if($route[$s]==true){$value=true;}else{$value=false;}
			return $value;
		break;
		case 'zones':
			$zones=json_decode($userSettings['zones'],true);
			if($zones[$s]==true){$value=true;}else{$value=false;}
			return $value;
		break;
		case 'subaccount':
			$subaccount=json_decode($userSettings['subaccount'],true);
			if($subaccount[$s]==true){$value=true;}else{$value=false;}
			return $value;
		break;
		case 'group':
			$group=json_decode($userSettings['group'],true);
			if($group[$s]==true){$value=true;}else{$value=false;}
			return $value;
		break;
	}
}

function getsimNumber($id)
	{
		global $ms;	
		$q = "SELECT * FROM `gs_simcard_details` WHERE `id`='".$id."'";
		$r = mysqli_query($ms, $q);
		if(mysqli_num_rows($r)>0){
			$row = mysqli_fetch_array($r);
			
			return $row['mob_number'];
		}else{
			return 0;
		}
	}

function getsimProvicer($id)
	{
		global $ms;		
		$q = "SELECT * FROM `gs_simcard_details` WHERE `id`='".$id."'";
		$r = mysqli_query($ms, $q);
		if(mysqli_num_rows($r)>0){
			$row = mysqli_fetch_array($r);			
			return $row['sim_provider'];
		}else{
			return '';
		}
	}

function getDailyMileage($imei,$dtf,$dtt){
	global $ms;
	// $result=array();
	$qs="SELECT MIN(mileage) as `minvalue`,MAX(mileage) AS `maxvalue` FROM gs_object_data_".$imei." WHERE dt_server BETWEEN '".$dtf."' AND '".$dtt."'";
	$r=mysqli_query($ms,$qs);
	if(mysqli_num_rows($r)>0){
		$row=mysqli_fetch_assoc($r);
		$result=array("minvalue"=>$row['minvalue'],"maxvalue"=>$row['maxvalue']);
	}else{
		$result=array("minvalue"=>0,"maxvalue"=>0);
	}
	return $result;
}

function getobjectgroup($imei,$user_id=''){
	global $ms;
	$q="SELECT c.group_name FROM gs_users a LEFT JOIN gs_user_objects b ON a.id=b.user_id LEFT JOIN gs_objects d ON b.imei=d.imei LEFT JOIN gs_user_object_groups c ON c.group_id=b.group_id WHERE d.imei=".$imei."";
	if($user_id!=''){
		$q.=" and b.user_id='".$user_id."'";
	}
	$r=mysqli_query($ms,$q);
	if(mysqli_num_rows($r)>0){
		$row=mysqli_fetch_assoc($r);
		return $row['group_name'];
	}else{
		return 'no';
	}
}

function getobjectfreezkm($imei){
	global $ms;
	$q="SELECT freeze_km FROM gs_objects where imei=".$imei." ";
	$r=mysqli_query($ms,$q);
	if(mysqli_num_rows($r)>0){
		$row=mysqli_fetch_assoc($r);
		if($row['freeze_km']==NULL){
			return 0;
		}
		return $row['freeze_km'];
	}else{
		return 0;
	}
}

function getObjectAccessories($imei){
	global $ms;
	$result=array();
	$q="SELECT * FROM gs_object_accessories WHERE imei='".$imei."'";
	$r=mysqli_query($ms,$q);
	if(mysqli_num_rows($r)>0){
		$row=mysqli_fetch_assoc($r);
		if($row['ac_line']=='yes'){
			 $result[] = 'AC Line';
		}
		if($row['temp_sensor']=='yes'){
			 $result[] = 'Temprature Sensor';
		}
		if($row['rfid']=='yes'){
			 $result[] = 'RFID';
		}
		if($row['panic_button']=='yes'){
			 $result[] = 'Panic Button';
		}
		if($row['buzzer']=='yes'){
			 $result[] = 'Buzzer';
		}
		if($row['ignition_line_relay']=='yes'){
			 $result[] = 'Ignition Line Relay';
		}
		if($row['cctv']=='yes'){
			 $result[] = 'CCTV';
		}	
		$result=implode(',', $result);
	}
	$result='No Accessories';
	return $result;
}

function get_dispensor_trackerParams($imei){
	global $ms;
	$q="SELECT params FROM gs_objects where imei='".$imei."'";
	$r=mysqli_query($ms,$q);
	$row=mysqli_fetch_assoc($r);
	$params=json_decode($row['params'],true);
	foreach ($params as $key => $val) {
	    $params['T_'.$key] = $val;
	    unset($params[$key]);
	}
	return $params;
}

function get_ObjectLastevent($imei){
	global $ms;
	$event='';
	$q="SELECT * FROM gs_user_events_data where imei='".$imei."' order by dt_tracker desc limit 1";
	$r=mysqli_query($ms,$q);
	if(mysqli_num_rows($r)>0){
		$row=mysqli_fetch_assoc($r);
		$event=$row['event_desc'];
	}
	return $event;
}

function getpeopleSNRnumber($rfid){
	global $ms;
	$rfidr=$rfid;
	// $rfid = substr(str_repeat(0, 10).$rfid, - 10);
	$rfid=str_split($rfid, 2);
	if(gettype($rfid)=='array'){
		$rfid = array_reverse($rfid);
		$rfid=strtoupper(join("",$rfid));
		$q="SELECT * FROM dstudent where rfid='".$rfid."'";
		$r=mysqli_query($ms,$q);
		if(mysqli_num_rows($r)>0){
			$row=mysqli_fetch_assoc($r);
			return $row['rfid_snr_no'];
		}else{
			// $rfid=dechex($rfid);
			// $rfid=substr($rfid, 0, -2);
			// $rfid=$rfid;
			// $rfid='XXXXXXXX'.$rfid;
			return $rfid;
		}
	}
	return $rfidr;

}

?>
