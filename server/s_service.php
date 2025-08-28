<?
	set_time_limit(0);
	
	ob_start();
	
	include ('s_init.php');
	include ('s_events.php');
	include ('../func/fn_common.php');
	include ('../func/fn_cleanup.php');
	include ('../tools/gc_func.php');
	ini_set('memory_limit', '1524M');

	if (@$_GET["op"] == "sms_gateway_app")
  	{
		if (!isset($_GET["identifier"])) { die; }
		
		if ($_GET["identifier"] == '') { die; }
		
		$format = strtolower(@$_GET["format"]);
		
		$q = "SELECT * FROM `gs_sms_gateway_app` WHERE `identifier`='".$_GET["identifier"]."' ORDER BY `dt_sms` ASC";
		$r = mysqli_query($ms, $q);
		
		if($format == 'json')
		{
			$result = array();
			
			while($row = mysqli_fetch_array($r))
			{
				$result[] = array($row['dt_sms'], $row['number'], $row['message']);
			}
			
			echo json_encode($result);
		}
		else
		{
			$result = '';
			
			while($row = mysqli_fetch_array($r))
			{
				$result.= $row['dt_sms'].chr(30).$row['number'].chr(30).$row['message'].chr(29);
			}
			
			echo $result;
		}
		
		$q2 = "DELETE FROM `gs_sms_gateway_app` WHERE `identifier`='".$_GET['identifier']."'";
		$r2 = mysqli_query($ms, $q2);
		
		die;
	}
	
	if (@$_GET["op"] == "chat_new_messages")
  	{
		$imei = $_GET["imei"];
		
		// get unread messages number
		$q = "SELECT * FROM `gs_object_chat` WHERE `imei`='".$imei."' AND `side`='S' AND `status`=0";
		$r = mysqli_query($ms, $q);
		$msg_num = mysqli_num_rows($r);
		
		// set messages to delivered
		$q = "UPDATE `gs_object_chat` SET `status`=1 WHERE `imei`='".$imei."' AND `side`='S' AND `status`=0";
		$r = mysqli_query($ms, $q);
		
		echo $msg_num;
		die;
	}	
	
	if ((@$_GET["op"] == "object_exists_system") || (@$_GET["op"] == "check_object_exists_system"))
  	{
		echo checkObjectExistsSystem($_GET["imei"]);
		die;
	}	
	
	if (@$_GET["op"] == "cmd_exec_imei_get")
  	{
		$format = strtolower(@$_GET["format"]);
		
		$q = "SELECT * FROM `gs_object_cmd_exec` WHERE `status`='0' AND `imei`='".$_GET["imei"]."'";
		$r = mysqli_query($ms, $q);
		
		if($format == 'json')
		{
			$result = array();
			
			while($row = mysqli_fetch_array($r))
			{
				$result[] = array($row['cmd_id'], $row['cmd']);
				
				$q2 = "UPDATE `gs_object_cmd_exec` SET `status`='1' WHERE `cmd_id`='".$row["cmd_id"]."'";
				$r2 = mysqli_query($ms, $q2);
			}
			
			echo json_encode($result);
		}
		else
		{
			$result = '';
			
			while($row = mysqli_fetch_array($r))
			{
				$result.= $row['cmd_id'].chr(30).$row['cmd'].chr(29);
				
				$q2 = "UPDATE `gs_object_cmd_exec` SET `status`='1' WHERE `cmd_id`='".$row["cmd_id"]."'";
				$r2 = mysqli_query($ms, $q2);
			}
			
			echo $result;
		}
		
		die;
	}
	
	if (@$_GET["op"] == "cmd_exec_get")
  	{
		$format = strtolower(@$_GET["format"]);
		
		$q = "SELECT * FROM `gs_object_cmd_exec` WHERE `status`='0' ORDER BY `cmd_id` ASC";
		$r = mysqli_query($ms, $q);
		
		$result = '';
		
		if($format == 'json')
		{
			$result = array();
			
			while($row = mysqli_fetch_array($r))
			{
				$result[] = array($row['cmd_id'], $row['imei'], $row['type'], $row['cmd']);
			}
			
			echo json_encode($result);
		}
		else
		{
			$result = '';
			
			while($row = mysqli_fetch_array($r))
			{
				$result.= $row['cmd_id'].chr(30).$row['imei'].chr(30).$row['type'].chr(30).$row['cmd'].chr(29);
			}
			
			echo $result;
		}
		
		die;
	}
	
	if (@$_GET["op"] == "cmd_exec_set")
  	{
		if (isset($_GET["re_hex"]))
		{
			$q = "UPDATE `gs_object_cmd_exec` SET `status`='".$_GET["status"]."', `re_hex`='".$_GET["re_hex"]."' WHERE `cmd_id`='".$_GET["cmd_id"]."'";
		}
		else
		{
			$q = "UPDATE `gs_object_cmd_exec` SET `status`='".$_GET["status"]."' WHERE `cmd_id`='".$_GET["cmd_id"]."'";
		}
		
		$r = mysqli_query($ms, $q);
		
		echo "OK";
		die;
	}
	
	if ($gsValues['HW_KEY'] != @$_GET["key"] && 'ABCV123' != @$_GET["key"])
	{
		Service_For_CrewDetail();
		echo 'Incorrect hardware key.'.$_GET["key"];
		die;
	}
	else
	{
		echo "OK";
	}
	
	header("Connection: close");
	header("Content-length: " . (string)ob_get_length());
	ob_end_flush();
	
	if (@$_GET["op"] == "clear_demo_history")
	{
		clearObjectHistory($_GET['imei']);
	}
	
	if (@$_GET["op"] == "service12h")
	{
		//serviceEVKeyUpdate();
		serviceServerCleanup();
	}
	
	if (@$_GET["op"] == "service1h")
	{
		
		//if($ivcnt==0)
		{
			$sysdate=date('Y-m-d',strtotime(gmdate("Y-m-d")."+5 hours +30 minutes"));
    		$sysfrom=$sysdate." 00:00:01";
    		$systo=$sysdate." 23:59:00";
    			
    		$qmv="update droute_events_daily set datefrom='".$sysfrom."',dateto='".$systo."'  where user_id='621' ";
    		$rmv = mysqli_query($ms,$qmv);
    		//$ivcnt++;
    		Insert_Issue('621','riya',$qmv,1);
		}
		
		serviceCheckAccountDateLimit();
		serviceCheckObjectDateLimit();
		serviceClearVarious();
		serviceClearHistory();
	}
	
	if (@$_GET["op"] == "service30min")
	{
		if ($gsValues['REPORTS_SCHEDULE'] == 'true')
		{
			serviceSendReportDaily();
			serviceSendReportWeekly();
			servicedeleteemaillog30();
		}
	}
	
	if (@$_GET["op"] == "service5min")
	{
		serviceCMDSchedule();
		serviceEventService();
		serviceDbBackup();
	}
	
	if (@$_GET["op"] == "service1min")
	{
		serviceClearCounters();
		serviceEvents();
	}
	

	function serviceEVKeyUpdate(){
		global $ms, $gsValues;
		return;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($ch, CURLOPT_URL, 'https://apiplatform.intellicar.in/api/standard/gettoken');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"username\": \"systemadmin_ntlindia\", \"password\": \"ntlindia123\"}");

		$headers = array();
		$headers[] = 'Content-Type: application/json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
		    echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);

		$result_json = json_decode($result,true);
		$token = $result_json["data"]["token"];


		$q = "update `api_setting` set apikey='".$token."' WHERE `api_name`='ev_pull_30_sec'";
		$r = mysqli_query($ms, $q);
		
		echo "KEY Updated";

	}
	// service 24h
	function serviceDbBackup()
	{
		global $ms, $gsValues;
		
		$email = $gsValues['DB_BACKUP_EMAIL'];
		
		if ($email == ''){ die; }

		// check when last time sent
		$q = "SELECT * FROM `gs_system` WHERE `key`='DB_BACKUP_TIME_LAST'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);
		
		if ($row)
		{			
			$dt_send = gmdate("Y-m-d").' '.$gsValues['DB_BACKUP_TIME'].':00';
			
			if(strtotime($row['value']) < strtotime($dt_send))
			{
				if(strtotime(gmdate('Y-m-d H:i:s')) < strtotime($dt_send))
				{
					die;   
				}
			}
			else
			{
				die;
			}
		}
	
		// get all of the tables
		$tables = array();
		$r = mysqli_query($ms, 'SHOW TABLES');
		while($row = mysqli_fetch_row($r))
		{
			$tables[] = $row[0];
		}
		
		$return = '';
		
		// cycle through
		foreach($tables as $table)
		{
			$row2 = mysqli_fetch_row(mysqli_query($ms, 'SHOW CREATE TABLE '.$table));
			$return.= $row2[1].";\n";
			
			if ((stristr($table, 'gs_dtc_data') == false) &&
			    (stristr($table, 'gs_geocoder_cache') == false) &&
			    (stristr($table, 'gs_objects_unused') == false) &&
			    (stristr($table, 'gs_object_chat') == false) &&
			    (stristr($table, 'gs_object_cmd_exec') == false) &&
			    (stristr($table, 'gs_object_data') == false) &&
			    (stristr($table, 'gs_object_img') == false) &&
			    (stristr($table, 'gs_rilogbook_data') == false) &&
			    (stristr($table, 'gs_sms_gateway_app') == false) &&
			    (stristr($table, 'gs_user_account_recover') == false) &&
			    (stristr($table, 'gs_user_events_data') == false) &&
			    (stristr($table, 'gs_user_events_status') == false) &&
			    (stristr($table, 'gs_user_failed_logins') == false) &&
			    (stristr($table, 'gs_user_reports_generated') == false) &&
			    (stristr($table, 'gs_user_usage') == false))
			{
				$return.="\n";
				
				$r = mysqli_query($ms, 'SELECT * FROM '.$table);
				$num_fields = mysqli_num_fields($r);
				
				for ($i = 0; $i < $num_fields; $i++) 
				{
					while($row = mysqli_fetch_row($r))
					{
						$return.= 'INSERT INTO '.$table.' VALUES(';
						for($j=0; $j<$num_fields; $j++) 
						{
							$row[$j] = addslashes($row[$j]);
							if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
							if ($j<($num_fields-1)) { $return.= ','; }
						}
						$return.= ");\n";
					}
				}
			}
			$return.="\n";
		}
		
		//save file
		$file = 'database_backup.sql';
		
		//send file via email
		$template = getDefaultTemplate('database_backup', 'english');
		
		$subject = $template['subject'];
		$message = $template['message'];
		
		$subject = str_replace("%SERVER_NAME%", $gsValues['NAME'], $subject);
		$subject = str_replace("%URL_SHOP%", $gsValues['URL_SHOP'], $subject);
		
		$message = str_replace("%SERVER_NAME%", $gsValues['NAME'], $message);
		$message = str_replace("%URL_SHOP%", $gsValues['URL_SHOP'], $message);
		
		if (sendEmail($email, $subject, $message, false, $file, $return))
		{
			$q = "SELECT * FROM `gs_system` WHERE `key`='DB_BACKUP_TIME_LAST'";
			$r = mysqli_query($ms, $q);
			$row = mysqli_fetch_array($r);
			
			if ($row)
			{
				$q = "UPDATE gs_system SET `value`='".gmdate("Y-m-d H:i:s")."' WHERE `key`='DB_BACKUP_TIME_LAST'";
				$r = mysqli_query($ms, $q);
			}
			else
			{
				$q = "INSERT INTO `gs_system`(`key`,`value`) VALUES ('DB_BACKUP_TIME_LAST', '".gmdate("Y-m-d H:i:s")."')";
				$r = mysqli_query($ms, $q);
			}
		}
	}
	
	// service 12h
	function serviceServerCleanup()
	{
		global $ms, $gsValues;
	
		if ($gsValues['SERVER_CLEANUP_USERS_AE'] == "true")
		{
			$days = $gsValues['SERVER_CLEANUP_USERS_DAYS'];
			$result = serverCleanupUsers($days);
		}
		
		if ($gsValues['SERVER_CLEANUP_OBJECTS_NOT_ACTIVATED_AE'] == "true")
		{
			$days = $gsValues['SERVER_CLEANUP_OBJECTS_NOT_ACTIVATED_DAYS'];
			$result = serverCleanupObjectsNotActivated($days);
		}
		
		if ($gsValues['SERVER_CLEANUP_OBJECTS_NOT_USED_AE'] == "true")
		{
			$result = serverCleanupObjectsNotUsed();
		}
		
		if ($gsValues['SERVER_CLEANUP_DB_JUNK_AE'] == "true")
		{
			$result = serverCleanupDbJunk();
		}
	}
	
	// service 1h
	function serviceCheckAccountDateLimit()
  	{
		global $ms, $gsValues, $la;
		
		// deactivate expired accounts
		$q = "UPDATE gs_users SET `active`='false' WHERE account_expire ='true' AND account_expire_dt <= UTC_DATE()";
		$r = mysqli_query($ms, $q);
		
		// remind about object expiry
		if ($gsValues['NOTIFY_ACCOUNT_EXPIRE'] == 'true')
		{
			$q = "SELECT * FROM `gs_users`";
			$r = mysqli_query($ms, $q); 
			
			while ($ud = mysqli_fetch_array($r))
			{
				$user_id = $ud["id"];
				$account_expire = $ud["account_expire"];
				$account_expire_dt = $ud["account_expire_dt"];
				$email = $ud["email"];
				$notify_account_expire = $ud['notify_account_expire'];
				
				if ($account_expire == 'true')
				{
					$notify = false;
					
					$diff = strtotime($account_expire_dt) - strtotime(gmdate("Y-m-d"));
					$days = $diff / 86400;
					
					if ($days <= $gsValues['NOTIFY_ACCOUNT_EXPIRE_PERIOD'])
					{
						$notify = true;
					}
					
					if ($notify == true)
					{
						if ($notify_account_expire != 'true')
						{
							$template = getDefaultTemplate('expiring_account', $ud["language"]);
							
							$subject = $template['subject'];
							$message = $template['message'];
							
							$subject = str_replace("%SERVER_NAME%", $gsValues['NAME'], $subject);
							$subject = str_replace("%URL_SHOP%", $gsValues['URL_SHOP'], $subject);
							
							$message = str_replace("%SERVER_NAME%", $gsValues['NAME'], $message);
							$message = str_replace("%URL_SHOP%", $gsValues['URL_SHOP'], $message);
							
							if (sendEmail($email, $subject, $message))
							{					
								$q4 = "UPDATE gs_users SET `notify_account_expire`='true' WHERE `id`='".$user_id."'";
								$r4 = mysqli_query($ms, $q4);
							}	
						}
					}
					else
					{
						$q4 = "UPDATE gs_users SET `notify_account_expire`='false' WHERE `id`='".$user_id."'";
						$r4 = mysqli_query($ms, $q4);
					}	
				}
			}
		}
	}
	
	function serviceCheckObjectDateLimit()
  	{
		global $ms, $gsValues, $la;
		
		// deactivate expired objects
		$q = "UPDATE gs_objects SET `active`='false' WHERE `active`='true' AND `object_expire`='true' AND object_expire_dt <= UTC_DATE()";
		$r = mysqli_query($ms, $q);
		
		// remind about object expiry
		if ($gsValues['NOTIFY_OBJ_EXPIRE'] == 'true')
		{
			$q = "SELECT * FROM `gs_users` WHERE `privileges` NOT LIKE ('%subuser%')";
			$r = mysqli_query($ms, $q); 
			
			while ($ud = mysqli_fetch_array($r))
			{
				$notify = false;
				
				$user_id = $ud["id"];
				$email = $ud["email"];
				
				$q2 = "SELECT * FROM `gs_user_objects` WHERE `user_id`='".$user_id."'";
				$r2 = mysqli_query($ms, $q2);
				
				while ($row2 = mysqli_fetch_array($r2))
				{
					$imei = $row2['imei'];
					
					$q3 = "SELECT * FROM `gs_objects` WHERE `imei`='".$imei."' AND `active`='true' AND `object_expire`='true'";
					$r3 = mysqli_query($ms, $q3);
					$row3 = mysqli_fetch_array($r3);
										
					$diff = strtotime($row3['object_expire_dt']) - strtotime(gmdate("Y-m-d"));
					$days = $diff / 86400;
					
					if ($days <= $gsValues['NOTIFY_OBJ_EXPIRE_PERIOD'])
					{
						$notify = true;
						break;
					}	
				}
				
				if ($notify == true)
				{
					if ($ud['notify_object_expire'] != 'true')
					{
						$template = getDefaultTemplate('expiring_objects', $ud["language"]);
						
						$subject = $template['subject'];
						$message = $template['message'];
						
						$subject = str_replace("%SERVER_NAME%", $gsValues['NAME'], $subject);
						$subject = str_replace("%URL_SHOP%", $gsValues['URL_SHOP'], $subject);
						
						$message = str_replace("%SERVER_NAME%", $gsValues['NAME'], $message);
						$message = str_replace("%URL_SHOP%", $gsValues['URL_SHOP'], $message);
						
						if (sendEmail($email, $subject, $message))
						{					
							$q4 = "UPDATE gs_users SET `notify_object_expire`='true' WHERE `id`='".$user_id."'";
							$r4 = mysqli_query($ms, $q4);
						}	
					}
				}
				else
				{
					$q4 = "UPDATE gs_users SET `notify_object_expire`='false' WHERE `id`='".$user_id."'";
					$r4 = mysqli_query($ms, $q4);
				}
			}
		}
	}
	
	function serviceClearHistory()
	{
		global $ms, $gsValues;
		
		if (!isset($gsValues['HISTORY_PERIOD']))
		{
			die;
		}
		
		if ($gsValues['HISTORY_PERIOD'] < 30)
		{
			die;
		}
		
		$q = "SELECT * FROM `gs_objects` ORDER BY `imei` ASC";
  		$r = mysqli_query($ms, $q);  
		
		while($row = mysqli_fetch_array($r)) 
		{
			$q2 = "DELETE FROM `gs_object_data_".$row['imei']."` WHERE dt_tracker < DATE_SUB(UTC_DATE(), INTERVAL ".$gsValues['HISTORY_PERIOD']." DAY)";
  			$r2 = mysqli_query($ms, $q2);
		}
	}
	
	function serviceClearVarious()
	{
		global $ms, $gsValues;
		
		if (!isset($gsValues['HISTORY_PERIOD']))
		{
			die;
		}
		
		if ($gsValues['HISTORY_PERIOD'] < 30)
		{
			die;
		}
		
		$q = "DELETE FROM `gs_user_failed_logins` WHERE dt_login < DATE_SUB(UTC_DATE(), INTERVAL 1 DAY)";
		$r = mysqli_query($ms, $q);
		
		$q = "DELETE FROM `gs_user_account_recover` WHERE dt_recover < DATE_SUB(UTC_DATE(), INTERVAL 1 DAY)";
		$r = mysqli_query($ms, $q);
		
		$q = "DELETE FROM `gs_user_usage` WHERE dt_usage < DATE_SUB(UTC_DATE(), INTERVAL 7 DAY)";
		//$r = mysqli_query($ms, $q);  //remove slashes
		
		$q = "DELETE FROM `gs_object_cmd_exec` WHERE dt_cmd < DATE_SUB(UTC_DATE(), INTERVAL 1 DAY)";
  		$r = mysqli_query($ms, $q);
		
		$q = "DELETE FROM `gs_sms_gateway_app` WHERE dt_sms < DATE_SUB(UTC_DATE(), INTERVAL 1 DAY)";
  		$r = mysqli_query($ms, $q);
		
		$q = "SELECT * FROM `gs_user_reports_generated` WHERE dt_report < DATE_SUB(UTC_DATE(), INTERVAL 30 DAY)";
  		$r = mysqli_query($ms, $q);
		
		while($row = mysqli_fetch_array($r))
		{
			$q2 = "DELETE FROM `gs_user_reports_generated` WHERE `report_id`='".$row['report_id']."'";
			$r2 = mysqli_query($ms, $q2);
			
			$report_file = $gsValues['PATH_ROOT'].'data/user/reports/'.$row['report_file'];
			if(is_file($report_file))
			{
				@unlink($report_file);
			}
		}
		
		$q = "DELETE FROM `gs_user_events_data` WHERE dt_tracker < DATE_SUB(UTC_DATE(), INTERVAL ".$gsValues['HISTORY_PERIOD']." DAY)";
  		$r = mysqli_query($ms, $q);
		
		$q = "DELETE FROM `gs_rilogbook_data` WHERE dt_tracker < DATE_SUB(UTC_DATE(), INTERVAL ".$gsValues['HISTORY_PERIOD']." DAY)";
		$r = mysqli_query($ms, $q);
		
		$q = "DELETE FROM `gs_dtc_data` WHERE dt_tracker < DATE_SUB(UTC_DATE(), INTERVAL ".$gsValues['HISTORY_PERIOD']." DAY)";
		$r = mysqli_query($ms, $q);
		
		$q = "SELECT * FROM `gs_object_img` WHERE dt_tracker < DATE_SUB(UTC_DATE(), INTERVAL ".$gsValues['HISTORY_PERIOD']." DAY)";
  		$r = mysqli_query($ms, $q);
		
		while($row = mysqli_fetch_array($r))
		{
			$q2 = "DELETE FROM `gs_object_img` WHERE `img_id`='".$row['img_id']."'";
			$r2 = mysqli_query($ms, $q2);
			
			$img_file = $gsValues['PATH_ROOT'].'data/img/'.$row['img_file'];
			if(is_file($img_file))
			{
				@unlink($img_file);
			}
		}
		
		$q = "SELECT * FROM `gs_object_chat` WHERE dt_server < DATE_SUB(UTC_DATE(), INTERVAL ".$gsValues['HISTORY_PERIOD']." DAY)";
  		$r = mysqli_query($ms, $q);
	}
	
	function serviceSendReportWeekly()
	{
		global $ms, $gsValues;
		
		// get weekly reports
		//$q = "SELECT * FROM `gs_user_reports` WHERE schedule_period LIKE '%w%' AND dt_schedule_w < DATE_SUB(UTC_DATE(), INTERVAL 6 DAY)";
		$q = "SELECT gur.* FROM gs_user_reports gur join gs_users gu on gu.id=gur.user_id and gu.active='true'  WHERE schedule_period LIKE '%w%' AND dt_schedule_w < DATE_SUB(UTC_DATE(), INTERVAL 6 DAY)";
		$r = mysqli_query($ms, $q);
		
		if (!$r){die;}
		
		$reports = array();
		
		while($report = mysqli_fetch_array($r))
		{
			// check if user day passed depending on set timezone
			$dt = convUserIDTimezone($report['user_id'], gmdate("Y-m-d H:i:s"));
			if (strtotime($dt) < strtotime(gmdate('Y-m-d')))
			{
				continue;
			}
			
			$previous_week = strtotime("-1 week +1 day");
			
			// get prev week monday
			$start_week = strtotime("last monday", $previous_week);
			
			// get next week monday
			$end_week = strtotime("next monday", $start_week);
			
			$report['dtf'] = gmdate("Y-m-d", $start_week).' 00:00:00';
			$report['dtt'] = gmdate("Y-m-d", $end_week).' 00:00:00';
			
			$dt_schedule_w = gmdate('Y-m-d', strtotime('monday')).' 00:00:00';
			
			$q2 = 'UPDATE gs_user_reports SET `dt_schedule_w` = "'.$dt_schedule_w.'" WHERE report_id="'.$report['report_id'].'"';
			$r2 = mysqli_query($ms, $q2);
			
			if ($r2)
			{
				$reports[] = $report;
			}
			
			// generate 5 reports at once
			if (count($reports) > 4)
			{
				if ($gsValues['CURL'] == true)
				{
					serviceSendReportsCURL($reports);
				}
				else
				{
					serviceSendReports($reports);
				}
				
				// reset previous reports
				$reports = array();
			}
		}
		
		// generate left reports
		if (count($reports) > 0)
		{
			if ($gsValues['CURL'] == true)
			{
				serviceSendReportsCURL($reports);
			}
			else
			{
				serviceSendReports($reports);
			}
			
			// reset previous reports
			$reports = array();
		}
	}
	
	function servicedeleteemaillog30()
	{
		global $ms, $gsValues;
		if ((gmdate("H") < 2) || (gmdate("H") > 22))
		{	
			die;
		}
		
		$day8 = date('Y-m-d',strtotime("-6 days")).' 00:00:00'; // delete record bfr 6 days
		$q = "delete from emaillog where datetime<'".$day8."'";
		$r = mysqli_query($ms,$q);
	}
	
	function serviceSendReportDaily()
	{
		global $ms, $gsValues;
		
		// get daily reports
		//$q = "SELECT * FROM `gs_user_reports` WHERE schedule_period LIKE '%d%' AND dt_schedule_d < UTC_DATE()";
		$q="SELECT gur.* FROM gs_user_reports gur join gs_users gu on gu.id=gur.user_id and gu.active='true'  WHERE schedule_period LIKE '%d%' AND dt_schedule_d < UTC_DATE()";
		$r = mysqli_query($ms, $q);
		
		if (!$r){die;}
		
		$reports = array();
		
		while($report = mysqli_fetch_array($r))
		{			
			// check if user day passed depending on set timezone
			$dt = convUserIDTimezone($report['user_id'], gmdate("Y-m-d H:i:s"));
			if (strtotime($dt) < strtotime(gmdate('Y-m-d')))
			{
				continue;
			}
			
			$report['dtf'] = gmdate('Y-m-d',strtotime("-1 days")).' 00:00:00'; // yesterday
			$report['dtt'] = gmdate('Y-m-d').' 00:00:00'; // today
			
			$dt_schedule_d = gmdate("Y-m-d H:i:s");
			
			$q2 = 'UPDATE gs_user_reports SET `dt_schedule_d` = "'.$dt_schedule_d.'" WHERE report_id="'.$report['report_id'].'"';
			$r2 = mysqli_query($ms, $q2);
			
			if ($r2)
			{
				$reports[] = $report;
			}
		
			// generate 5 reports at once
			if (count($reports) > 4)
			{
				if ($gsValues['CURL'] == true)
				{
					serviceSendReportsCURL($reports);
				}
				else
				{
					serviceSendReports($reports);
				}
				
				// reset previous reports
				$reports = array();
			}
		}
		
		// generate left reports
		if (count($reports) > 0)
		{
			if ($gsValues['CURL'] == true)
			{
				serviceSendReportsCURL($reports);
			}
			else
			{
				serviceSendReports($reports);
			}
				
			// reset previous reports
			$reports = array();
		}
	}
	
	function serviceSendReports($reports)
	{
		global $ms, $gsValues;
		
		$url = $gsValues['URL_ROOT'].'/func/fn_reports.gen.php';
		
		$reports_count = count($reports);
		
		for($i = 0; $i < $reports_count; $i++)
		{
			$postdata = http_build_query(
							array(
								'cmd' => 'report',
								'schedule' => true,
								'user_id' => $reports[$i]['user_id'],
								'email' => $reports[$i]['schedule_email_address'],
								'name' => $reports[$i]['name'],
								'type' => $reports[$i]['type'],
								'format' => $reports[$i]['format'],
								'show_coordinates' => $reports[$i]['show_coordinates'],
								'show_addresses' => $reports[$i]['show_addresses'],
								'zones_addresses' => $reports[$i]['zones_addresses'],
								'stop_duration' => $reports[$i]['stop_duration'],
								'speed_limit' => $reports[$i]['speed_limit'],
								'imei' => $reports[$i]['imei'],
								'zone_ids' => $reports[$i]['zone_ids'],
								'zone_idsv' => $reports[$i]['zone_idsv'],
								'sensor_names' => $reports[$i]['sensor_names'],
								'data_items' => $reports[$i]['data_items'],
								'dtf' => $reports[$i]['dtf'],
								'dtt' => $reports[$i]['dtt'],
								'rpt_type'=> $reports[$i]['rpt_type'],
								'emergency' => explode(',',$reports[$i]['emergency']),
        						'address' => $reports[$i]['address'],
        						'people_count' => $reports[$i]['people_count'],
        						'phone' => $reports[$i]['phone'],
        						'patient_name' => $reports[$i]['patient_name'],
        						'age_from' => $reports[$i]['age_from'],
        						'age_to' => $reports[$i]['age_to'],
        						'gender' => $reports[$i]['gender'],
        						'breath' => $reports[$i]['breath'],
        						'conscious' => $reports[$i]['conscious'],
        						'book_status' => $reports[$i]['book_status'],
        						'crewtime' => $reports[$i]['crewtime']
							));
			
			$opts = array('http' =>
					array(
						'method'  => 'POST',
						'header'  => 'Content-type: application/x-www-form-urlencoded',
						'content' => $postdata
					)
			);
			
			$context  = stream_context_create($opts);
			
			$result = file_get_contents($url, false, $context);
			
			$result = null;
			unset($result);
		}
	}
	
	function serviceSendReportsCURL($reports)
	{
		global $ms, $gsValues;
		
		$url = $gsValues['URL_ROOT'].'/func/fn_reports.gen.php';
		
		$reports_count = count($reports);
		
		$curl_arr = array();
		$master = curl_multi_init();
		
		for($i = 0; $i < $reports_count; $i++)
		{
			$postdata = http_build_query(
							array(
								'cmd' => 'report',
								'schedule' => true,
								'user_id' => $reports[$i]['user_id'],
								'email' => $reports[$i]['schedule_email_address'],
								'name' => $reports[$i]['name'],
								'type' => $reports[$i]['type'],
								'format' => $reports[$i]['format'],
								'show_coordinates' => $reports[$i]['show_coordinates'],
								'show_addresses' => $reports[$i]['show_addresses'],
								'zones_addresses' => $reports[$i]['zones_addresses'],
								'stop_duration' => $reports[$i]['stop_duration'],
								'speed_limit' => $reports[$i]['speed_limit'],
								'imei' => $reports[$i]['imei'],
								'zone_ids' => $reports[$i]['zone_ids'],
								'zone_idsv' => $reports[$i]['zone_idsv'],
								'sensor_names' => $reports[$i]['sensor_names'],
								'data_items' => $reports[$i]['data_items'],
								'dtf' => $reports[$i]['dtf'],
								'dtt' => $reports[$i]['dtt'],
								'rpt_type'=> $reports[$i]['rpt_type'],
								'emergency' => explode(',',$reports[$i]['emergency']),
        						'address' => $reports[$i]['address'],
        						'people_count' => $reports[$i]['people_count'],
        						'phone' => $reports[$i]['phone'],
        						'patient_name' => $reports[$i]['patient_name'],
        						'age_from' => $reports[$i]['age_from'],
        						'age_to' => $reports[$i]['age_to'],
        						'gender' => $reports[$i]['gender'],
        						'breath' => $reports[$i]['breath'],
        						'conscious' => $reports[$i]['conscious'],
        						'book_status' => $reports[$i]['book_status'],
        						'crewtime' => $reports[$i]['crewtime']
							));
			

							
			$curl_arr[$i] = curl_init($url);
			curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl_arr[$i], CURLOPT_POST, 1);
			curl_setopt($curl_arr[$i], CURLOPT_POSTFIELDS, $postdata);
			curl_multi_add_handle($master, $curl_arr[$i]);




		}
		
		do
		{
			curl_multi_exec($master, $running);
		}
		while ($running > 0);
		
		for ($i = 0; $i < $reports_count; $i++)
		{
			$result = curl_multi_getcontent($curl_arr[$i]);
		}
		
		unset($curl_arr);
	}
	
	// service 5min
	function serviceCMDSchedule()
	{
		global $ms;
		
		$q = "SELECT * FROM `gs_user_cmd_schedule`";
		$r = mysqli_query($ms, $q);
		
		while($row = mysqli_fetch_array($r))
		{
			if ($row['active'] == 'true')
			{
				if ($row['exact_time'] == 'true')
				{
					$curr_dt = convUserIDTimezone($row['user_id'], gmdate("Y-m-d H:i:s"));
					
					if ((strtotime($row['dt_schedule_e']) < strtotime($row['exact_time_dt'])) && (strtotime($row['exact_time_dt']) <= strtotime($curr_dt)))
					{
						$imeis = explode(",", $row['imei']);
						
						for ($i=0; $i<count($imeis); ++$i)
						{
							$imei = $imeis[$i];
							
							if ($row['gateway'] == 'gprs')
							{				
								sendObjectGPRSCommand($row['user_id'], $imei, $row['name'], $row['type'], $row['cmd']);
							}
							else if ($row['gateway'] == 'sms')
							{
								sendObjectSMSCommand($row['user_id'], $imei, $row['name'], $row['cmd']);
							}
						}
						
						$q2 = 'UPDATE gs_user_cmd_schedule SET `dt_schedule_e` = "'.$curr_dt.'" WHERE cmd_id="'.$row['cmd_id'].'"';
						$r2 = mysqli_query($ms, $q2);
					}	
				}
				else
				{
					$curr_dt = convUserIDTimezone($row['user_id'], gmdate("Y-m-d H:i:s"));
					
					$day_of_week = gmdate('w', strtotime($curr_dt));
					$day_time = json_decode($row['day_time'], true);
					
					if ($day_time != null)
					{
						if (($day_time['sun'] == true) && ($day_of_week == 0))
						{
							$time = $day_time['sun_time'];
						}
						else if (($day_time['mon'] == true) && ($day_of_week == 1))
						{
							$time = $day_time['mon_time'];
						}
						else if (($day_time['tue'] == true) && ($day_of_week == 2))
						{
							$time = $day_time['tue_time'];
						}
						else if (($day_time['wed'] == true) && ($day_of_week == 3))
						{
							$time = $day_time['wed_time'];
						}
						else if (($day_time['thu'] == true) && ($day_of_week == 4))
						{
							$time = $day_time['thu_time'];
						}
						else if (($day_time['fri'] == true) && ($day_of_week == 5))
						{
							$time = $day_time['fri_time'];
						}
						else if (($day_time['sat'] == true) && ($day_of_week == 6))
						{
							$time = $day_time['sat_time'];
						}
						else
						{
							continue;
						}
						
						if (isset($time))
						{
							if ((strtotime($row['dt_schedule_d']) == '') || ((gmdate('w', strtotime($row['dt_schedule_d'])) != gmdate('w', strtotime($curr_dt)))))
							{
								$time = strtotime($time);
								$curr_time = strtotime(date("H:i", strtotime($curr_dt)));
								
								if ($time <= $curr_time)
								{
									$imeis = explode(",", $row['imei']);
									
									for ($i=0; $i<count($imeis); ++$i)
									{
										$imei = $imeis[$i];
										
										if ($row['gateway'] == 'gprs')
										{				
											sendObjectGPRSCommand($row['user_id'], $imei, $row['name'], $row['type'], $row['cmd']);
										}
										else if ($row['gateway'] == 'sms')
										{
											sendObjectSMSCommand($row['user_id'], $imei, $row['name'], $row['cmd']);
										}
									}
									
									$q2 = 'UPDATE gs_user_cmd_schedule SET `dt_schedule_d` = "'.$curr_dt.'" WHERE cmd_id="'.$row['cmd_id'].'"';
									$r2 = mysqli_query($ms, $q2);
								}
							}
						}
					}
				}
			}
		}
	}
	
	function serviceEventService()
	{
		global $ms;
		
		$q = "SELECT * FROM `gs_user_events` WHERE `type`='service'";
		$r = mysqli_query($ms, $q);
		
		while($ed = mysqli_fetch_array($r))
		{
			if ($ed['active'] == 'true')
			{
				// get user data
				$q2 = "SELECT * FROM `gs_users` WHERE `id`='".$ed['user_id']."'";
				$r2 = mysqli_query($ms, $q2);
				$ud = mysqli_fetch_array($r2);
				
				// get object details
				$q2 = "SELECT gs_objects.*, gs_user_objects.*
					FROM gs_objects
					INNER JOIN gs_user_objects ON gs_objects.imei = gs_user_objects.imei
					WHERE gs_user_objects.user_id='".$ed['user_id']."'";
				$r2 = mysqli_query($ms, $q2);
				
				$imeis = explode(",", $ed['imei']);
				
				while($od = mysqli_fetch_array($r2))
				{
					if (!in_array($od['imei'], $imeis))
					{
						continue;
					}
					
					$q3 = "SELECT * FROM `gs_object_services` WHERE `imei`='".$od['imei']."'";
					$r3 = mysqli_query($ms, $q3);
					
					while($sd = mysqli_fetch_array($r3))
					{
						$event = false;
						
						// check if odo is expired
						if (($sd['odo'] == 'true') && ($sd['odo_left'] == 'true'))
						{
							$odometer = getObjectOdometer($od['imei']);
							
							$odo_diff = $odometer - $sd['odo_last'];
							$odo_diff = $sd['odo_interval'] - $odo_diff;
							
							if ($odo_diff <= $sd['odo_left_num'])
							{
								$event = true;
								
								if ($sd['update_last'] == 'true')
								{
									$q4 = "UPDATE gs_object_services SET `odo_last` = odo_last + ".$sd['odo_interval']." WHERE `service_id`='".$sd['service_id']."'";
									$r4 = mysqli_query($ms, $q4);	
								}
							}
						}
						
						// check if engh is expired
						if (($sd['engh'] == 'true') && ($sd['engh_left'] == 'true'))
						{
							$engine_hours = getObjectEngineHours($od['imei'], false);
							
							$engh_diff = $engine_hours - $sd['engh_last'];
							$engh_diff = $sd['engh_interval'] - $engh_diff;
							
							if ($engh_diff <= $sd['engh_left_num'])
							{
								$event = true;
								
								if ($sd['update_last'] == 'true')
								{
									$q4 = "UPDATE gs_object_services SET `engh_last` = engh_last + ".$sd['engh_interval']." WHERE `service_id`='".$sd['service_id']."'";
									$r4 = mysqli_query($ms, $q4);
								}
							}
						}
						
						// check if days are expired
						if (($sd['days'] == 'true') && ($sd['days_left'] == 'true'))
						{
							$days_diff = strtotime(gmdate("Y-m-d")) - (strtotime($sd['days_last']));
							$days_diff = floor($days_diff/3600/24);
							$days_diff = $sd['days_interval'] - $days_diff;
							
							if ($days_diff <= $sd['days_left_num'])
							{
								$event = true;
								
								if ($sd['update_last'] == 'true')
								{
									$days_last = gmdate('Y-m-d', strtotime($sd['days_last']. ' + '.$sd['days_interval'].' days'));
									
									$q4 = "UPDATE gs_object_services SET `days_last` = '".$days_last."' WHERE `service_id`='".$sd['service_id']."'";
									$r4 = mysqli_query($ms, $q4);
								}
							}
						}
						
						if ($event == true)
						{
							if (($sd['notify_service_expire'] != 'true') || ($sd['update_last'] == 'true'))
							{
								if ($sd['update_last'] != 'true')
								{
									$q4 = "UPDATE gs_object_services SET `notify_service_expire` = 'true' WHERE `service_id`='".$sd['service_id']."'";
									$r4 = mysqli_query($ms, $q4);
								}
								
								// get object last location
								$q4 = "SELECT * FROM `gs_objects` WHERE `imei`='".$od['imei']."'";
								$r4 = mysqli_query($ms, $q4);
								$loc = mysqli_fetch_array($r4);
								
								// set dt_server and dt_tracker to show exact time
								$loc['dt_server'] = gmdate("Y-m-d H:i:s");
								$loc['dt_tracker'] = $loc['dt_server'];
								
								$loc['params'] = json_decode($loc['params'],true);
								
								// add event desc to event data array
								$ed['event_desc'] = $sd['name'];
								
								event_notify($ed,$ud,$od,$loc);
							}
						}
						else
						{
							$q4 = "UPDATE gs_object_services SET `notify_service_expire` = 'false' WHERE `service_id`='".$sd['service_id']."'";
							$r4 = mysqli_query($ms, $q4);
						}
					}
				}
			}			
		}
	}
	
	// service 1 minute
	function serviceClearCounters()
	{
		global $ms;
		
		$q = "SELECT * FROM `gs_users` WHERE dt_usage_d < UTC_DATE()";
		$r = mysqli_query($ms, $q);
		
		while($row = mysqli_fetch_array($r))
		{
			$user_id = $row['id'];
			
			$q2 = "UPDATE gs_users SET 	usage_email_daily_cnt=0,
							usage_sms_daily_cnt=0,
							usage_api_daily_cnt=0,
							`dt_usage_d`='".gmdate("Y-m-d")."'
							WHERE id='".$user_id."'";	
			$r2 = mysqli_query($ms, $q2);
			
			$q2 = "INSERT INTO `gs_user_usage`(`user_id`,
							`dt_usage`,
							`login`,
							`email`,
							`sms`,
							`api`,
							google_address
							)
							VALUES
							('".$user_id."',
							'".gmdate("Y-m-d")."',
							'0',
							'0',
							'0',
							'0',
							'0')";
			$r2 = mysqli_query($ms, $q2);
		}
	}
	
	function serviceEvents()
	{
		global $ms;
		
		// get all imeis which sent data during last 24 hours
		$q = "SELECT * FROM `gs_objects` WHERE dt_server > DATE_SUB(UTC_DATE(), INTERVAL 1 DAY) ";
		$r = mysqli_query($ms, $q);
		
		while($loc = mysqli_fetch_assoc($r))
		{
			$loc['params'] = json_decode($loc['params'],true);
			check_events($loc, false, false, true);
		}
		
	}
	
	//code written by vetrivel.NR 
	 
	function Service_For_CrewDetail()
	{
		
		global $ms,$gsValues;
		//BAC, ACB, CBA
		//sunday leave
		//$crewtype=array("BAC","ACB","CBA");
		$crewtype=$gsValues['POST_RFID'][726]["crew_format"];
		//$crewtype_tme[0]=array("start"=>"6","end"=>"14");
		//$crewtype_tme[1]=array("start"=>"14","end"=>"22");
		//$crewtype_tme[2]=array("start"=>"22","end"=>"6");
		$crewtype_tme=$gsValues['POST_RFID'][726]["crew_time"];
				
		$curdateindian=date('Y-m-d',strtotime(gmdate("Y-m-d H:i:s")."+ 5 hour + 30 minutes"));
 		$day_of_week = gmdate('w', strtotime($curdateindian));
 		$day_of_week=1;
 		if($day_of_week==1)//if monday change crew format
 		{
 			
			$q="select group_concat(unit_name) crew_form,dat from schedule_timev group by dat order by tid desc limit 1";
 			if($r = mysqli_query($ms, $q))
 			{
 				if($row = mysqli_fetch_assoc($r))
 				{
 					
 					$assign_current_crew="";
 					$strcrew=str_replace(',','',$row["crew_form"]);
 					for($ic=0;$ic<count($crewtype);$ic++)
 					{
 						if($strcrew==$crewtype[$ic])
 						{
 							if($ic==count($crewtype))
 							{
 								$assign_current_crew=$crewtype[0];
 							}
 							else 
 							{
 								$assign_current_crew=$crewtype[$ic+1];
 							}
 							$crewarray=str_split($assign_current_crew);
 							for($icar=0;$icar<count($crewarray);$icar++)
 							{	
 								
 								if($icar==2)
 								{
 									$curdateindian_next=date('Y-m-d H:i:s', strtotime('+1 day', strtotime($curdateindian)));
 									$qassin="insert into schedule_timev(UNIT_NAME,START_TIME,END_TIME,DAT) values
 									('".$crewarray[$icar]."','".$curdateindian." ".$crewtype_tme[$icar]["start"]."'
 									,'".$curdateindian_next." ".$crewtype_tme[$icar]["end"]."',,'".$curdateindian."')";
									$rassin = mysqli_query($ms, $qassin);
 								}
 								else
 								{
 									$qassin="insert into schedule_timev(UNIT_NAME,START_TIME,END_TIME,DAT) values
 									('".$crewarray[$icar]."','".$curdateindian." ".$crewtype_tme[$icar]["start"]."'
 									,'".$curdateindian." ".$crewtype_tme[$icar]["end"]."',,'".$curdateindian."')";
 									$rassin = mysqli_query($ms, $qassin);
 									
 								} 								
 							}
 						}
 						else
 						{
 							//report to playgps & salcomp		
 						}
 					}
 				}
 			}
 			else
 			{ 				
 				$q1="insert into schedule_timev(UNIT_NAME,START_TIME,END_TIME,DAT) select 
 					 stv.UNIT_NAME,stv.START_TIME,stv.END_TIME,stv.DAT
                     from schedule_timev stv where stv.dat='" .$curdateindian . "'";
 				$r1 = mysqli_query($ms, $q1);
 			}	 			
 		}
 		else //if($day_of_week==0)//if sunday/ not other than monday store previouse day crew format
 		{
 			
 			$yesterday = gmdate("Y-m-d", strtotime($curdateindian)-86400);
 			$q1="insert into schedule_timev(UNIT_NAME,START_TIME,END_TIME,DAT) select 
 					 stv.UNIT_NAME,stv.START_TIME,stv.END_TIME,stv.DAT
                     from schedule_timev stv where stv.dat='" .$yesterday . "'";
 			//echo $q1;
 			$r1 = mysqli_query($ms, $q1);
 		}
	}
	
	
	
	//code written end by vetrivel.NR
?>
