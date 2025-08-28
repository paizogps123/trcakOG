<?
	set_time_limit(900);

	// check if reports are called by user or service
	if (!isset($_POST['schedule']))
	{
		session_start();
	}
	
	if(isset($_GET["cmd"]))
	$_POST=$_GET;
	

	include ('../init.php');
	include ('fn_common.php');
	include ('fn_reports.genv.php');
	include ('fn_reports.gen_amb.php');
	include ('fn_reports.gen_amb_method.php');
	include ('fn_route.php');
	include ('../tools/gc_func.php');
	include ('../tools/email.php');
	include ('../tools/html2pdf.php');
	include ('fn_download.header.php');
	
	// check if reports are called by user or service
	if (isset($_POST['schedule']))
	{
		$_SESSION = getUserData($_POST['user_id']);
		loadLanguage($_SESSION["language"], $_SESSION["units"]);
	}
	else
	{
		checkUserSession();
		loadLanguage($_SESSION["language"], $_SESSION["units"]);
	}
	
	
	if(@$_POST['cmd'] == 'report')
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
		
		// generate or send report to e-mail
		if (isset($_POST['schedule'])  && $_POST['schedule'] != "")
		{
			//$myfile = fopen("0 report vvv.txt", "a");
			//fwrite($myfile,"enter report");
			//fwrite($myfile, "\n");
			//fclose($myfile);
			
			
			//check user usage
			if (!checkUserUsage($user_id, 'email')) die;
		
			reportsSend();
		}
		else
		{
			$report = reportsGenerate();		
			
			if ($report != false)
			{
				echo $report;
			}
		}
		
		die;
	}
	else if(@$_POST['cmd'] == 'report_json')
	{
		global $_POST, $la, $user_id;
		// check privileges
		if ($_SESSION["privileges"] == 'subuser')
		{
			$user_id = $_SESSION["manager_id"];
		}
		else
		{
			$user_id = $_SESSION["user_id"];
		}
		$imeis = $_POST['imei'];
		$type = $_POST['type'];
		$stop_duration = $_POST['stop_duration'];
		$speed_limit = $_POST['speed_limit'];
		// $max_speed_limit = @$_POST['max_speed'];
		$dtf = $_POST['dtf'];
		$dtt = $_POST['dtt'];
		$data=array();
		$imeis=explode(',',$imeis);
		
		if($type=="tripreport")
		{
			for ($i=0; $i<count($imeis); ++$i)
			{
				$imei = $imeis[$i];
				$data[]=reportGenerateTripWise($imei, $dtf, $dtt,  $speed_limit, $stop_duration);
			}
		}
		else if($type=="tripwise_rfiddata")
		{
			for ($i=0; $i<count($imeis); ++$i)
			{
				$imei = $imeis[$i];
				$data[]=reportGenerateTripWise_RFID($imei, $dtf, $dtt,  $speed_limit, $stop_duration);
			}
		}
		
		echo json_encode($data);
		die;
	}

	function reportsSend()
	{
		global $_POST, $la, $user_id;
		
		fndblogemail('Schedule');
		$subject = $la['REPORT'].' - '.$_POST['name'];
		
		$message = $la['HELLO'].",\r\n\r\n";
		$message .=  $la['THIS_IS_REPORT_MESSAGE'];

			
		$filename = $_POST['type'].'_'.$_POST['dtf'].'_'.$_POST['dtt'].'.'.$_POST['format'];
		$report = reportsGenerate();
		
		if(isset($_POST['mobile_api']) && $_POST['mobile_api']==true){
			echo $report;
		}

		if ($report != false)
		{
			$result = sendEmail($_POST['email'], $subject, $message, true, $filename, $report);
			
			if ($result)
			{
				//update user usage
				updateUserUsage($user_id, false, $result, false, false);
			}
		}
		
		die;
	}
	
	function reportsGenerate()
	{
		
		global $_POST, $ms, $gsValues, $user_id;
		fndblogemail('Report');
		$name = $_POST['name'];
		$type = $_POST['type'];
		$format = $_POST['format'];
		$show_coordinates = $_POST['show_coordinates'];
		$show_addresses = $_POST['show_addresses'];
		$zones_addresses = $_POST['zones_addresses'];
		$stop_duration = $_POST['stop_duration'];
		$speed_limit = $_POST['speed_limit'];
		$max_speed_limit = $_POST['max_speed'];
		$imei = $_POST['imei'];
		$zone_ids = $_POST['zone_ids'];
		$zone_idsv = @$_POST['zone_idsv'];
		$sensor_names = $_POST['sensor_names'];
		$data_items = $_POST['data_items'];
		$event_list = $_POST['event_list'];
		$dtf = $_POST['dtf'];
		$dtt = $_POST['dtt'];
		
		$emergency="";
		$rpt_type = @$_POST["rpt_type"];
		if(isset($_POST["emergency"]))
		$emergency = implode(',',@$_POST["emergency"]);
        $address = @$_POST["address"];
        $peoplecount = @$_POST["peoplecount"];
        $phone = @$_POST["phone"];
        $patientname = @$_POST["patientname"];
        $age_from = @$_POST["age_from"];
        $age_to = @$_POST["age_to"];
        $gender = @$_POST["gender"];
        $breath = @$_POST["breath"];
        $conscious = @$_POST["conscious"];
        $book_status = @$_POST["book_status"];
        $crewtime = @$_POST["crewtime"];
        $taken = @$_POST["taken"];
		$book_by = @$_POST["book_by"];
		
		// check if object is not removed from system and also if it is active
		$imeis = array();
		$imeis_ = explode(",", $imei);
		for ($i=0; $i<count($imeis_); ++$i)
		{
			$imei = $imeis_[$i];
			
			if (checkObjectActive($imei))
			{
				if (checkUserToObjectPrivileges($user_id, $imei))
				{
					$imeis[] = $imei;	
				}
			}	
		}
		

		if ((count($imeis) == 0 && $rpt_type!="A") && $type!="maintenancereport" && $type!="emergencyalert" && $type!="master_lisid")
		{
			return false;
		}
		
		
		$data_items = explode(',', $data_items);
		
		$report_html = reportsAddHeaderStart($format);
		$report_html .= reportsAddStyle($format);
		$report_html .= reportsAddJS($type);
		$report_html .= reportsAddHeaderEnd();
		
		
		if (($format == 'html') || ($format == 'pdf'))
		{
			$report_html .= '<img class="logo" src="'.$gsValues['URL_LOGO'].'" /><hr/>';
		}
		
		
		if($rpt_type=="" || $rpt_type=="G")
		{
			$report_html .= reportsGenerateLoop($type, $imeis, $dtf, $dtt, $speed_limit, $stop_duration, $show_coordinates, $show_addresses, $zones_addresses, $zone_ids,$zone_idsv, $sensor_names, $data_items,$format,$event_list);
			$rpt_type="G";			
		}
		else if($rpt_type=="A")
		$report_html .= reportsGenerateLoopAmb($type,$dtf, $dtt,$emergency,$address,$peoplecount ,$phone ,$patientname ,$age_from ,$age_to ,$gender ,$breath ,$conscious ,$book_status ,$crewtime ,$taken,$book_by);

		$report_html .= '</body>';
		$report_html .= reportsAddFooter($type);
		$report_html .= '</html>';		
		$report = $report_html;
		
		if ($format == 'pdf')
		{
			$report = html2pdf($report);
		}
		
		if (!isset($_POST['schedule']))
		{
			$report = base64_encode($report);	
		}
		
		// store generated report
		if ($zone_ids != '')
		{
			$zones = count(explode(",", $zone_ids));
		}
		else
		{
			$zones = 0;
		}
		
		if ($sensor_names != '')
		{
			$sensors = count(explode(",", $sensor_names));
		}
		else
		{
			$sensors = 0;
		}
		
		if (isset($_POST['schedule']))
		{
			$schedule = 'true';
		}
		else
		{
			$schedule = 'false';
		}
		
		$filename = $type.'_'.$dtf.'_'.$dtt;
		
		$report_file = $user_id.'_'.md5($dtf.$dtt.gmdate("Y-m-d H:i:s"));
		$file_path = $gsValues['PATH_ROOT'].'data/user/reports/'.$report_file;
		
		$report_html = base64_encode($report_html);
		
		$fp = fopen($file_path, 'wb');
		fwrite($fp, $report_html);
		fclose($fp);
		writeLog('report_log', '-'.$name.'-'.$show_addresses.'-'.$zones_addresses);
		if(is_file($file_path))
		{
			$q = "INSERT INTO `gs_user_reports_generated`(	`user_id`,
									`dt_report`,
									`name`,
									`type`,
									`format`,
									`objects`,
									`zones`,
									`sensors`,
									`schedule`,
									`filename`,
									`report_file`,
									rpt_type)
									VALUES
									('".$user_id."',
									'".gmdate("Y-m-d H:i:s")."',
									'".$name."',
									'".$type."',
									'".$format."',
									'".count($imeis)."',
									'".$zones."',
									'".$sensors."',
									'".$schedule."',
									'".$filename."',
									'".$report_file."',
									'".$rpt_type."'
									)";
			$r = mysqli_query($ms, $q);	
		}
		
		return $report;
	}
	
function reportsGenerateLoop($type, $imeis, $dtf, $dtt, $speed_limit, $stop_duration, $show_coordinates, $show_addresses, $zones_addresses, $zone_ids,$zone_idsv, $sensor_names, $data_items,$format,$event_list)
	{
		global $la,$user_id,$gsValues;
		
		$result = '';
		
		for ($i=0; $i<count($imeis); ++$i)
		{
			$imei = $imeis[$i];
			
			if ($type == "general") //GENERAL_INFO
			{
				$result .= '<h3>'.$la['GENERAL_INFO'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsGenerateGenInfo($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $speed_limit, $stop_duration, $data_items);
				$result .= '<br/><hr/>';
			}
			else if ($type == "drives_stops") //DRIVES_AND_STOPS
			{
				$result .= '<h3>'.$la['DRIVES_AND_STOPS'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsGenerateDrivesAndStops($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $stop_duration, $show_coordinates, $show_addresses, $zones_addresses, $data_items);
				$result .= '<br/><hr/>';
			}
			else if ($type == "travel_sheet") //TRAVEL_SHEET
			{
				$result .= '<h3>'.$la['TRAVEL_SHEET'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsGenerateTravelSheet($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $stop_duration, $show_coordinates, $show_addresses, $zones_addresses, $data_items);
				$result .= '<br/><hr/>';
			}
			else if ($type == "overspeed") //OVERSPEED
			{
				$result .= '<h3>'.$la['OVERSPEEDS'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsGenerateOverspeed($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $speed_limit, $show_coordinates, $show_addresses, $zones_addresses, $data_items);
				$result .= '<br/><hr/>';
			}
			else if ($type == "underspeed") //UNDERSPEED
			{
				$result .= '<h3>'.$la['UNDERSPEEDS'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsGenerateUnderspeed($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $speed_limit, $show_coordinates, $show_addresses, $zones_addresses, $data_items);
				$result .= '<br/><hr/>';
			}
			// else if ($type == "zone_in_out") //ZONE_IN_OUT
			// {
			// 	$result .= '<h3>'.$la['ZONE_IN_OUT'].'</h3>';
			// 	$result .= reportsAddReportHeader($imei, $dtf, $dtt);
			// 	$result .= reportsGenerateZoneInOut($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $show_coordinates, $show_addresses, $zones_addresses, $zone_ids, $data_items);
			// 	$result .= '<br/><hr/>';
			// }
			else if ($type == "toll_report") //ZONE_IN_OUT
			{
				$result .= '<h3>'.$la['TOLL_REPORT'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsGenerateTollInOut($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $show_coordinates, $show_addresses, $zones_addresses, $zone_ids, $data_items);
				$result .= '<br/><hr/>';
			}
			else if ($type == "events") //EVENTS
			{
				$result .= '<h3>'.$la['EVENTS'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsGenerateEvents($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $show_coordinates, $show_addresses, $zones_addresses, $data_items,$event_list);
				$result .= '<br/><hr/>';
			}
			else if ($type == "service") //SERVICE
			{
				$result .= '<h3>'.$la['SERVICE'].'</h3>';
				$result .= reportsAddReportHeader($imei);
				$result .= reportsGenerateService($imei, $data_items);
				$result .= '<br/><hr/>';
			}
			else if ($type == "offlinefule") //OFFLINE_FULE
			{
				$result .= '<h3>'.$la['OFFLINE_FULE'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsOfflineFuel($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $show_coordinates, $show_addresses, $zones_addresses, $data_items);
				$result .= '<br/><hr/>';
			}
			else if ($type == "fuelfillings") //FUEL_FILLINGS
			{
				$result .= '<h3>'.$la['FUEL_FILLINGS'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsGenerateFuelFillings($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $show_coordinates, $show_addresses, $zones_addresses, $data_items);
				$result .= '<br/><hr/>';
			}
			else if ($type == "fuelthefts") //FUEL_THEFTS
			{
				$result .= '<h3>'.$la['FUEL_THEFTS'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsGenerateFuelThefts($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $show_coordinates, $show_addresses, $zones_addresses, $data_items);
				$result .= '<br/><hr/>';
			}
			else if ($type == "logic_sensors") //LOGIC_SENSORS
			{
				$sensors = getSensors($imei);
				$sensors_ = array();
				
				$sensor_names_ = explode(",", $sensor_names);				
				for ($j=0; $j<count($sensor_names_); ++$j)
				{
					for ($k=0; $k<count($sensors); ++$k)
					{
						if ($sensors[$k]['result_type'] == 'logic')
						{
							if ($sensor_names_[$j] == $sensors[$k]['name'])
							{
								$sensors_[] = $sensors[$k];
							}
						}
					}
				}
				
				$result .= '<h3>'.$la['LOGIC_SENSORS'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsGenerateLogicSensorInfo($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $sensors_, $show_coordinates, $show_addresses, $zones_addresses, $data_items);
				$result .= '<br/><hr/>';
			}
			else if ($type == "speed_graph") //SPEED
			{
				$sensors = array(array('name' => '', 'type' => 'speed', 'units' => $la["UNIT_SPEED"], 'result_type' => ''));
				
				$result .= '<h3>'.$la['SPEED_GRAPH'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsGenerateGraph($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $sensors);
				$result .= '<br/><hr/>';
			}
			else if ($type == "altitude_graph") //ALTITUDE
			{
				$sensors = array(array('name' => '', 'type' => 'altitude', 'units' => $la["UNIT_HEIGHT"], 'result_type' => ''));
				
				$result .= '<h3>'.$la['ALTITUDE_GRAPH'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsGenerateGraph($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $sensors);
				$result .= '<br/><hr/>';
			}
			else if ($type == "acc_graph") //ACC
			{
				$sensors = getSensorFromType($imei, 'acc');
				
				$result .= '<h3>'.$la['IGNITION_GRAPH'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsGenerateGraph($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $sensors);
				$result .= '<br/><hr/>';
			}
			else if ($type == "fuellevel_graph") //FUEL_LEVEL
			{
				$sensors = getSensorFromType($imei, 'fuel');
				
				$result .= '<h3>'.$la['FUEL_LEVEL_GRAPH'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsGenerateGraph($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $sensors);
				$result .= '<br/><hr/>';
			}
			else if ($type == "temperature_graph") //TEMPERATURE
			{
				$sensors = getSensorFromType($imei, 'temp');
				
				$result .= '<h3>'.$la['TEMPERATURE_GRAPH'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsGenerateGraph($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $sensors);
				$result .= '<br/><hr/>';
			}			
			else if ($type == "sensor_graph") //SENSOR
			{
				$sensors = getSensors($imei);
				$sensors_ = array();
				
				$sensor_names_ = explode(",", $sensor_names);				
				for ($j=0; $j<count($sensor_names_); ++$j)
				{
					for ($k=0; $k<count($sensors); ++$k)
					{
						if ($sensor_names_[$j] == $sensors[$k]['name'])
						{
							$sensors_[] = $sensors[$k];
						}
					}
				}
				
				$result .= '<h3>'.$la['SENSOR_GRAPH'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsGenerateGraph($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $sensors_);
				$result .= '<br/><hr/>';
			}
			
			//BELOW REPORTs are developed by N.VETRIVEL
				else if ($type == "fuelana") //FUEL_Analysis
			{
				 //code update by vetrivel
				if($i<10)
				{
				$result .= '<h3>'.$la['FUEL_ANA'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsGeneratefuelana($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $stop_duration, $show_addresses, $zones_addresses,$data_items,$show_coordinates);
				$result .= '<br/><hr/>';
				}
			}
			 else if ($type == "rfidtripreport") //RFID Trip Report 
            {
                 //code update by vetrivel
                if($i<10)
                {
                $result .= '<h3>'.$la['RFIDREPORT'].'</h3>';
                $result .= reportsAddReportHeader($imei, $dtf, $dtt);
                $result .= reportsGeneraterfidtrip($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $stop_duration, $show_addresses, $zones_addresses, $data_items,$show_coordinates);
                $result .= '<br/><hr/>';
                }
            }
            else if ($type == "derfidtripreport") //Detailed RFID Report
            {
                 //code update by vetrivel
                if($i<10)
                {
                $result .= '<h3>'.$la['DRFIDREPORT'].'</h3>';
                $result .= reportsAddReportHeader($imei, $dtf, $dtt);
                $result .= reportsGeneratedetailrfidtrip($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $stop_duration, $show_addresses, $zones_addresses, $data_items,$show_coordinates);
                $result .= '<br/><hr/>';
                }
            }
			else if ($type == "ifsinfograph") //FUEL TEMP graph
			{
				 //code update by vetrivel
				if($i<100)
				{
				$result .= '<h3>'.$la['IFS_INFO_GRAPH'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsGenerateIFSGraph($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt),$i,$data_items);
				$result .= '<br/><hr/>';
				}
			}
			else if ($type == "ifsinfograph_raw") //FUEL TEMP graph
			{
				 //code update by vetrivel
				if($i<10)
				{
				$result .= '<h3>'.$la['IFS_INFO_GRAPH'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsGenerateIFSGraph($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt),$i,$data_items,true);
				$result .= '<br/><hr/>';
				}
			}
		     else if ($type == "fuelgraph") //FUEL_graph
			{
				 //code update by vetrivel
				if($i<10)
				{
				$result .= '<h3>'.$la['FUELDATA'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsGenerateFuelGraph($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt),$i,$stop_duration, $show_addresses, $zones_addresses,$show_coordinates);
				$result .= '<br/><hr/>';
				}
			}
			else if ($type == "driverbehavior") //FUEL_graph
			{
				 //code update by vetrivel
				if($i<10)
				{
				$result .= '<h3>'.$la['DRIVER_BEHAVIOR'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsDriverBehaviorGraph($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt),$i);
				$result .= '<br/><hr/>';
				}
			}
				else if ($type == "tempgraph") //TEMP_Graph
			{
				 //code update by vetrivel
				if($i<10)
				{
				$result .= '<h3>'.$la['TEMPDATA'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsGenerateTempGraph($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt),$i);
				$result .= '<br/><hr/>';
				}
			}	
			else if ($type == "tempreport") //TEMP_Report
			{
				 //code update by vetrivel
				if($i<10)
				{
				$result .= '<h3>'.$la['TEMPREPORT'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsGenerateTempReport($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt),$i,$stop_duration,$show_addresses, $zones_addresses, $data_items,$show_coordinates);
				$result .= '<br/><hr/>';
				}
			}
			else if ($type == "volvoreport") //FUEL_Analysis
			{
				 //code update by vetrivel
				if($i<10)
				{
				$result .= '<h3>'.$la['VOLVOREPORT'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsGenerateVOLVO($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $stop_duration, $show_addresses, $zones_addresses,$show_coordinates);
				$result .= '<br/><hr/>';
				}
			}
			else if ($type == "booking_report") //BOOKING_REPORT
			{
				$result .= '<h3>'.$la['BOOKING_REPORT'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsGeneratebooking($imei,$dtf, $dtt, $data_items, $show_coordinates);
				$result .= '<br/><hr/>';
			}
			 else if ($type == "roundtripreport") //ROUND TRIP REPORT developed by N.VETRIVEL
			{
				$result .= '<h3>'.$la['ROUNDTRIPREPORT'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsGenerateroundtrip($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $speed_limit, $stop_duration,$zone_ids,$zone_idsv,$show_coordinates, $show_addresses, $zones_addresses,$format);
				$result .= '<br/><hr/>';
			} 
			 else if ($type == "zone_wise_trip_report") //ROUND TRIP REPORT developed by N.VETRIVEL
			{
				$result .= '<h3>'.$la['ZONE_WISE_TRIP_REPORT'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsGenerateZonewiseTripreport($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $speed_limit, $stop_duration,$show_coordinates, $show_addresses, $zones_addresses,$format);
				$result .= '<br/><hr/>';
			}  
			else if ($type == "tripreportdaily") 
			{
				$result .= '<h3>'.$la['TRIPREPORTDAILY'].'1</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportGenerateTripWiseDaily_print($imei, ($dtf), ($dtt), $speed_limit, $stop_duration,$show_coordinates, $show_addresses, $zones_addresses,$format);
				$result .= '<br/><hr/>';
			}
			else if ($type == "tripreport") 
			{
				$result .= '<h3>'.$la['TRIPREPORT'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportGenerateTripWise_print($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $speed_limit, $stop_duration,$show_coordinates, $show_addresses, $zones_addresses,$format);
				$result .= '<br/><hr/>';
			}
			else if ($type == "rfiddata" ) //EVENTS
			{
				$result .= '<h3>'.$la['RFID_DATA'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsGenerateRfidData($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $show_coordinates, $show_addresses, $zones_addresses, $data_items);
				$result .= '<br/><hr/>';
			}
			else if ($type == "tripwise_rfiddata") //EVENTS
			{
				$result .= '<h3>'.$la['TRIPWISE_RFIDREPORT'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportGenerateTripWiseRFID_Mthd($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $speed_limit, $stop_duration, $show_coordinates, $show_addresses, $zones_addresses,$format,false);
				$result .= '<br/><hr/>';
			}
			else if ($type == "tripwise_rfiddataempty") //EVENTS
			{
				$result .= '<h3>'.$la['TRIPWISE_RFIDREPORT'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportGenerateTripWiseRFID_Mthd($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $speed_limit, $stop_duration, $show_coordinates, $show_addresses, $zones_addresses,$format,true);
				$result .= '<br/><hr/>';
			}
			else if ($type == "detailed_driver_behavior") //FUEL_graph
			{
				 //code update by nandha
				$result .= '<h3>'.$la['DETAILED_DRIVER_BEHAVIOR'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportsDetailedDriverBehavior($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $speed_limit, $data_items);
				$result .= '<br/><hr/>';
			}
			/*else if ($type == "live_trip_report") //EVENTS
			{
				$result .= '<h3>'.$la['LIVE_TRIP'].'</h3>';
				$result .= reportsAddReportHeader($imei, $dtf, $dtt);
				$result .= reportGenerateLiveTrip($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $speed_limit, $stop_duration, $show_coordinates, $show_addresses, $zones_addresses,$format,false);
				$result .= '<br/><hr/>';
			}
			 */
			
			//update by vetrivel
			
		}
		
		if ($type == "general_merged") //GENERAL_INFO_MERGED
		{
			$result .= '<h3>'.$la['GENERAL_INFO_MERGED'].'</h3>';
			$result .= reportsAddReportHeader('', $dtf, $dtt);
			$result .= reportsGenerateGenInfoMerged($imeis, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $speed_limit, $stop_duration, $data_items);
			$result .= '<br/><hr/>';
		}
		if ($type == "consolidated_rfid_data") //GENERAL_INFO_MERGED
		{
			$result .= '<h3>'.$la['CONSOLIDATED_RFID_DATA'].'</h3>';
			$result .= reportsAddReportHeader('', $dtf, $dtt);
			$result .= reportsGenerateConsolidatedRfidDataNew($imeis, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $show_coordinates, $show_addresses, $zones_addresses, $data_items);
			$result .= '<br/><hr/>';
		}
		if ($type == "zone_in_out") //ZONE_IN_OUT
		{
			$result .= '<h3>Beta '.$la['ZONE_IN_OUT'].'</h3>';
			$result .= reportsAddReportHeader("", $dtf, $dtt);
			$result .= reportsGenerateZoneInOutNew($imeis, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $show_coordinates, $show_addresses, $zones_addresses, $zone_ids, $data_items);
			$result .= '<br/><hr/>';
		}
		if ($type == "maintenancereport") //maintenancereport
		{
			$result .= '<h3>'.$la['MAINTENANCE'].'</h3>';
			$result .= reportsAddReportHeader('', $dtf, $dtt);
			$result .= reportsGeneratemaintenancereport($imeis,$dtf,$dtt,$data_items,$show_coordinates);
			$result .= '<br/><hr/>';
		}
		if ($type == "daily_km") //DAILY_KM_REPORT
		{
			$result .= '<h3>'.$la['DAILY_KM_REPORT'].'</h3>';
			$result .= reportsAddReportHeader('', $dtf, $dtt);
			$result .= reportsGenerateDailyKM($imeis, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $speed_limit, $stop_duration, $data_items,$format);
			$result .= '<br/><hr/>';
		}
		if ($type == "vehicle_daily_km")
		{
			$result .= '<h3>'.$la['VEHICLE_DAILY_KM'].'</h3>';
		//	$result .= reportsAddReportHeader('', convUserUTCTimezone($dtf), convUserUTCTimezone($dtt));
			//	$result .= reportsGenerateCustomVehicleDailyKM($imeis, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt));
			$result .= reportsAddReportHeader('', $dtf, $dtt);
			$result .= reportsGenerateVehicleDailyKM($imeis, $dtf, $dtt);
			$result .= '<br/><hr/>';
		}
		if ($type == "custom_vehicle_daily_km")
		{
			$result .= '<h3>'.$la['CUSTOM_VEHICLE_DAILY_KM'].'</h3>';
			//$result .= reportsAddReportHeader('', $dtf, $dtt);
			//$result .= reportsGenerateCustomVehicleDailyKM($imeis, $dtf, $dtt);
			$result .= reportsAddReportHeader('', ($dtf), ($dtt));
			$result .= reportsGenerateCustomVehicleDailyKM($imeis,($dtf), ($dtt));
			$result .= '<br/><hr/>';
		}
		else if ($type == "object_info") //OBJECT_INFO
		{
			$result .= '<h3>'.$la['OBJECT_INFO'].'</h3>';
			$result .= reportsAddReportHeader('', $dtf, $dtt);
			$result .= reportsGenerateObjectInfo($imeis, $data_items);
			$result .= '<br/><hr/>';
		}
		else if ($type == "rag") //RAG
		{
			$result .= '<h3>'.$la['DRIVER_BEHAVIOR_RAG'].'</h3>';
			$result .= reportsAddReportHeader('', $dtf, $dtt);
			$result .= reportsGenerateRag($imeis, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $speed_limit, $data_items);
			$result .= '<br/><hr/>';
		}
		else if ($type == "current_position") //CURRENT POSITION
		{
			$result .= '<h3>'.$la['CURRENT_POSITION'].'</h3>';
			$result .= reportsAddReportHeader('', $dtf, $dtt);
			$result .= reportsGenerateCurrentPosition($imeis, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $show_coordinates, $show_addresses, $zones_addresses, $data_items);
			$result .= '<br/><hr/>';
		}
					//update by vetrivel.NR
		if ($type == "tripreportvdontuse") 
		{
			$result .= '<h3>'.$la['TRIPREPORT'].'</h3>';
			$result .= reportsAddReportHeader('', $dtf, $dtt);
			$result .= reportsGenerateTripWiseReport($imeis, ($dtf), ($dtt), $speed_limit, $stop_duration,$data_items);
			$result .= '<br/><hr/>';
		}
		else if ($type == "hmil_custom_report") //FUEL_graph
		{
			 //code update by nandha
			$result .= '<h3>'.$la['HMIL_CUSTOME_REPORT'].'</h3>';
			$result .= reportsAddReportHeader('', $dtf, $dtt);
			$result .= reportsHMILcustomReport($imeis, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $speed_limit, $data_items);
			$result .= '<br/><hr/>';
		}
		if ($type == "offline") //OFFLINE
		{
			$result .= '<h3>'.$la['OFFLINE'].'</h3>';
			$result .= reportsGenerateoffline($data_items,$show_coordinates);
			$result .= '<br/><hr/>';
		}	
		
		if ($type == "offlinesensor") //OFFLINE SENSOR
		{
			$result .= '<h3>'.$la['OFFLINE_SENSOR'].'</h3>';
			$result .= reportsGenerateofflinesensor($show_coordinates, $show_addresses, $zones_addresses);
			$result .= '<br/><hr/>';
		}
		if ($type == "csnreport") //OFFLINE SENSOR
		{
			$result .= '<h3>'.$la['CSN_REPORT'].'</h3>';
			$result .= reportCSNDetails($show_coordinates, $show_addresses, $zones_addresses);
			$result .= '<br/><hr/>';
		}
		else if ($type == "emergencyalert") //EMERGENCY_ALERT
		{
			$result .= '<h3>'.$la['EMERGENCY_ALERT_REPORT'].'</h3>';
			$result .= reportsAddReportHeader("", $dtf, $dtt);
			$result .= reportsEmergencyAlert('', convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $show_coordinates, $show_addresses, $zones_addresses, $data_items);
			$result .= '<br/><hr/>';
		}
		else if ($type == "master_lisid") //EMERGENCY_ALERT
		{
			$result .= '<h3>'.$la['MASTER_LIST_ID'].'</h3>';
			$result .= reportsAddReportHeader("", $dtf, $dtt);
			$result .= reportsMaster_lisid('', convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $show_coordinates, $show_addresses, $zones_addresses, $data_items);
			$result .= '<br/><hr/>';
		}
		else if ($type == "live_trip_report") //EVENTS
			{
				$result .= '<h3>'.$la['LIVE_TRIP'].'</h3>';
				$result .= reportsAddReportHeader('', $dtf, $dtt);
				$result .= reportGenerateLiveTrip($imeis, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $speed_limit, $stop_duration, $show_coordinates, $show_addresses, $zones_addresses,$format,false);
				$result .= '<br/><hr/>';
			}
	
		
		
			//End Code Done& update by vetrivel.NR
		
		
		return $result;
	}

	function reportsGenerateGenInfo($imei, $dtf, $dtt, $speed_limit, $stop_duration, $data_items) //GENERAL_INFO
	{
		global $_SESSION, $la, $user_id;
		
		$result = '';		
		$data = getRoute($imei, $dtf, $dtt, $stop_duration, true);
		
		if ($speed_limit > 0)
		{
			$overspeeds = getRouteOverspeeds($data['route'], $speed_limit);
			$overspeeds_count = count($overspeeds);
		}
		else
		{
			$overspeeds_count = 0;
		}
		
		if (count($data['route']) == 0)
		{
			return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
		
		$odometer = getObjectOdometer($imei);
		$odometer = floor(convDistanceUnits($odometer, 'km', $_SESSION["unit_distance"]));
		
		$result .= '<table>';
		if (in_array("route_start", $data_items))
		{
			$result .= '<tr>
					<td><strong>'.$la['ROUTE_START'].':</strong></td>
					<td>'.$data['route'][0][0].'</td>
				</tr>';
		}
		
		if (in_array("route_end", $data_items))
		{
			$result .= '<tr>
					<td><strong>'.$la['ROUTE_END'].':</strong></td>
					<td>'.$data['route'][count($data['route'])-1][0].'</td>
				</tr>';
		}
		
		if (in_array("route_length", $data_items))
		{
			$result .= '<tr>
					<td><strong>'.$la['ROUTE_LENGTH'].':</strong></td>
					<td>'.$data['route_length'].' '.$la["UNIT_DISTANCE"].'</td>
				</tr>';
		}
		
		if (in_array("move_duration", $data_items))
		{
			$result .= '<tr>
					<td><strong>'.$la['MOVE_DURATION'].':</strong></td>
					<td>'.$data['drives_duration'].'</td>
				</tr>';
		}
		
		if (in_array("stop_duration", $data_items))
		{
			$result .= '<tr>
					<td><strong>'.$la['STOP_DURATION'].':</strong></td>
					<td>'.$data['stops_duration'].'</td>
				</tr>';
		}
		
		if (in_array("stop_count", $data_items))
		{
			$result .= '<tr>
					<td><strong>'.$la['STOP_COUNT'].':</strong></td>
					<td>'.count($data['stops']).'</td>
				</tr>';
		}
		
		if (in_array("top_speed", $data_items))
		{
			$result .= '<tr>
					<td><strong>'.$la['TOP_SPEED'].':</strong></td>
					<td>'.$data['top_speed'].' '.$la["UNIT_SPEED"].'</td>
				</tr>';
		}
		
		if (in_array("avg_speed", $data_items))
		{
			$result .= '<tr>
					<td><strong>'.$la['AVG_SPEED'].':</strong></td>
					<td>'.$data['avg_speed'].' '.$la["UNIT_SPEED"].'</td>
				</tr>';
		}
		
		if (in_array("overspeed_count", $data_items))
		{
			$result .= '<tr>
					<td><strong>'.$la['OVERSPEED_COUNT'].':</strong></td>
					<td>'.$overspeeds_count.'</td>
				</tr>';
		}
		
		if (in_array("fuel_consumption", $data_items))
		{
			$result .= '<tr>
					<td><strong>'.$la['FUEL_CONSUMPTION'].':</strong></td>
					<td>'.$data['fuel_consumption'].' '.$la["UNIT_CAPACITY"].'</td>
				</tr>';
		}
		
		if (in_array("fuel_cost", $data_items))
		{
			$result .= '<tr>
					<td><strong>'.$la['FUEL_COST'].':</strong></td>
					<td>'.$data['fuel_cost'].' '.$_SESSION["currency"].'</td>
				</tr>';
		}
		
		if (in_array("engine_work", $data_items))
		{
			$result .= '<tr>
					<td><strong>'.$la['ENGINE_WORK'].':</strong></td>
					<td>'.$data['engine_work'].'</td>
				</tr>';
		}
		
		if (in_array("engine_idle", $data_items))
		{
			$result .= '<tr>
					<td><strong>'.$la['ENGINE_IDLE'].':</strong></td>
					<td>'.$data['engine_idle'].'</td>
				</tr>';
		}
		
		if (in_array("odometer", $data_items))
		{
			$result .= '<tr>
					<td><strong>'.$la['ODOMETER'].':</strong></td>
					<td>'.$odometer.' '.$la["UNIT_DISTANCE"].'</td>
				</tr>';
		}
		
		if (in_array("engine_hours", $data_items))
		{
			$result .= '<tr>
					<td><strong>'.$la['ENGINE_HOURS'].':</strong></td>
					<td>'.getObjectEngineHours($imei, true).'</td>
				</tr>';
		}
		
		if (in_array("driver", $data_items))
		{
			$result .= '<tr>';
			
			$params = $data['route'][count($data['route'])-1][6];
			
			$driver = getObjectDriver($user_id, $imei, $params);
			if ($driver['driver_name'] == ''){$driver['driver_name'] = $la['NA'];}
			
			$result .= 	'<td><strong>'.$la['DRIVER'].':</strong></td>
					<td>'.$driver['driver_name'].'</td>
					</tr>';
		}
		
		if (in_array("trailer", $data_items))
		{
			$result .= '<tr>';
			
			$params = $data['route'][count($data['route'])-1][6];
			$trailer = getObjectTrailer($user_id, $imei, $params);
			if ($trailer['trailer_name'] == ''){$trailer['trailer_name'] = $la['NA'];}
			
			$result .= 	'<td><strong>'.$la['TRAILER'].':</strong></td>
					<td>'.$trailer['trailer_name'].'</td>
					</tr>';
		}
		
		$result .= '</table>';
		
		return $result;
	}
	
	function reportsGenerateGenInfoMerged($imeis, $dtf, $dtt, $speed_limit, $stop_duration, $data_items) //GENERAL_INFO_MERGED
	{
		global $_SESSION, $la, $user_id;
		
		$result = '<table class="report" width="100%"><tr align="center">';
		
		$result .= '<th>'.$la['OBJECT'].'</th>';
		if (in_array("group", $data_items))
		{
			$result .= '<th>'.$la['GROUP'].'</th>';
		}
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
		if (in_array("route_length", $data_items))
		{
			$result .= '<th>'.$la['FREEZED_KM'].'</th>';
		}

		if (in_array("route_length", $data_items))
		{
			$result .= '<th>'.$la['DELAY'].'</th>';
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
			
			$data = getRoute($imei, $dtf, $dtt, $stop_duration, true);
					
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
				
				if (in_array("group", $data_items))
				{
					$result .= '<td>'.getobjectgroup($imei,$user_id).'</td>';
				}

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
				if (in_array("route_length", $data_items))
				{
					$result .= '<td>'.getobjectfreezkm($imei).'</td>';
				}
				if (in_array("route_length", $data_items))
				{
					$result .= '<td>'.($data['route_length']-getobjectfreezkm($imei)).'</td>';
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
					$engine_hours = (float) getObjectEngineHours($imei, true);
					
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
				
				$result .= '</tr>';
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
			if (in_array("group", $data_items))
			{
				$result .= '<td></td>';
			}
			
			if (in_array("route_length", $data_items))
			{
				$result .= '<td>'.$total_route_length.' '.$la["UNIT_DISTANCE"].'</td>';
			}
			if (in_array("route_length", $data_items))
			{
				$result .= '<td></td>';
			}
			if (in_array("route_length", $data_items))
			{
				$result .= '<td></td>';
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
	
		
	
	function reportsGenerateObjectInfo($imeis, $data_items)
	{
		global $ms, $_SESSION, $la, $user_id;
		
		$result = '<table class="report" width="100%" ><tr align="center">';
				
		$result .= '<th>'.$la['OBJECT'].'</th>';
		
		if (in_array("imei", $data_items))
		{
			$result .= '<th>'.$la['IMEI'].'</th>';
		}

		if (in_array("installation", $data_items))
		{
			$result .= '<th>'.$la['INSTALLATION'].'</th>';
		}
		
		if (in_array("transport_model", $data_items))
		{
			$result .= '<th>'.$la['TRANSPORT_MODEL'].'</th>';
		}
		
		if (in_array("vin", $data_items))
		{
			$result .= '<th>'.$la['VIN'].'</th>';
		}
		
		if (in_array("plate_number", $data_items))
		{
			$result .= '<th>'.$la['PLATE_NUMBER'].'</th>';
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
		
		if (in_array("gps_device", $data_items))
		{
			$result .= '<th>'.$la['GPS_DEVICE'].'</th>';
		}
		
		if (in_array("sim_card_number", $data_items))
		{
			$result .= '<th>'.$la['SIM_CARD_NUMBER'].'</th>';
		}
		
		if (in_array("group_name", $data_items))
		{
			$result .= '<th>'.$la['GROUP'].'</th>';
		}
		
		if (in_array("fueltype", $data_items))
		{
			$result .= '<th>'.$la['FUELTYPE'].'</th>';
		}
		
		if (in_array("fuel1", $data_items))
		{
			$result .= '<th>'.$la['FUEL1'].'</th>';
		}
		
		if (in_array("fuel2", $data_items))
		{
			$result .= '<th>'.$la['FUEL2'].'</th>';
		}
		
		if (in_array("temp1", $data_items))
		{
			$result .= '<th>'.$la['TEMP1'].'</th>';
		}
		
		if (in_array("temp2", $data_items))
		{
			$result .= '<th>'.$la['TEMP2'].'</th>';
		}
		
		$result .= '</tr>';
		
		if ($_SESSION["privileges"] == 'subuser')
		{
			$user_id = $_SESSION["manager_id"];
		}
		else
		{
			$user_id = $_SESSION["user_id"];
		}
	
		for ($i=0; $i<count($imeis); ++$i)
		{
			$imei = $imeis[$i];
			
			$q = "SELECT go.*,guog.group_name FROM gs_objects go join gs_user_objects guo on guo.imei=go.imei
				  left join gs_user_object_groups guog on guo.group_id=guog.group_id where go.imei='".$imei."' and guo.user_id='".$user_id."'";
			$r = mysqli_query($ms, $q);
			$row = mysqli_fetch_array($r);
			
			//$odometer = getObjectOdometer($imei);
			$odometer = floor($row['odometer']);
			$odometer = floor(convDistanceUnits($odometer, 'km', $_SESSION["unit_distance"]));
			
			$result .= '<tr align="center">';
			
			$result .= '<td>'.$row['name'].'</td>';
			
			if (in_array("imei", $data_items))
			{
				$result .= '<td>'.$row['imei'].'</td>';
			}

			if (in_array("installation", $data_items))
			{
				$result .= '<td>'.$row['installdate'].'</td>';
			}
			
			if (in_array("transport_model", $data_items))
			{
				$result .= '<td>'.$row['model'].'</td>';
			}
			
			if (in_array("vin", $data_items))
			{
				$result .= '<td>'.$row['vin'].'</td>';
			}
			
			if (in_array("plate_number", $data_items))
			{
				$result .= '<td>'.$row['plate_number'].'</td>';
			}
			
			if (in_array("odometer", $data_items))
			{
				$result .= '<td>'.$odometer.' '.$la["UNIT_DISTANCE"].'</td>';
			}
			
			if (in_array("engine_hours", $data_items))
			{
				$result .= '<td>'.getObjectEngineHours($imei, true).'</td>';
			}
			
			if (in_array("driver", $data_items))
			{
				$params = json_decode($row['params'],true);
				$driver = getObjectDriver($user_id, $imei, $params);
				if ($driver['driver_name'] == ''){$driver['driver_name'] = $la['NA'];}
				
				$result .= '<td>'.$driver['driver_name'].'</td>';
			}
			
			if (in_array("trailer", $data_items))
			{
				$params = json_decode($row['params'],true);
				$trailer = getObjectTrailer($user_id, $imei, $params);
				if ($trailer['trailer_name'] == ''){$trailer['trailer_name'] = $la['NA'];}
				
				$result .= '<td>'.$trailer['trailer_name'].'</td>';
			}
			
			if (in_array("gps_device", $data_items))
			{
				$result .= '<td>'.$row['device'].'</td>';
			}
			
			if (in_array("sim_card_number", $data_items))
			{
				$result .= '<td>'.$row['sim_number'].'</td>';
			}

			if (in_array("group_name", $data_items))
			{
				$result .= '<td>'.$row['group_name'].'</td>';
			}

			if (in_array("fueltype", $data_items))
			{
				$result .= '<td>'.$row['fueltype'].'</td>';
			}

			if (in_array("fuel1", $data_items))
			{
				$result .= '<td>'.$row['fuel1'].'</td>';
			}

			if (in_array("fuel2", $data_items))
			{
				$result .= '<td>'.$row['fuel2'].'</td>';
			}

			if (in_array("temp1", $data_items))
			{
				$result .= '<td>'.$row['temp1'].'</td>';
			}

			if (in_array("temp2", $data_items))
			{
				$result .= '<td>'.$row['temp2'].'</td>';
			}
				
			$result .= '</tr>';
		}

		$result .= '</table>';
		
		return $result;
	}
	
	function reportsGenerateCurrentPosition($imeis, $dtf, $dtt, $show_coordinates, $show_addresses, $zones_addresses, $data_items)
	{
		global $ms, $_SESSION, $gsValues, $la,$user_id;
		
		$result = '';
		
		$result = '<table class="report" width="100%" ><tr align="center">';
				
		$result .= '<th>'.$la['OBJECT'].'</th>';
		
		if ($_SESSION["user_id"]=="1035")
		{
			$result .= '<th>'.$la['VENDOR'].'</th>';
			$result .= '<th>'.$la['TRANSPORT_MODEL'].'</th>';
		}
		
		if (in_array("time", $data_items))
		{
			$result .= '<th>'.$la['TIME'].'</th>';
		}
		
		if (in_array("position", $data_items))
		{
			$result .= '<th>'.$la['POSITION'].'</th>';
		}
		
		if (in_array("speed", $data_items))
		{
			$result .= '<th>'.$la['SPEED'].'</th>';
		}
		
		if (in_array("altitude", $data_items))
		{
			$result .= '<th>'.$la['ALTITUDE'].'</th>';
		}
		
		if (in_array("angle", $data_items))
		{
			$result .= '<th>'.$la['ANGLE'].'</th>';
		}
		
		if (in_array("status", $data_items))
		{
			$result .= '<th>'.$la['STATUS'].'</th>';
		}
		
		if (in_array("odometer", $data_items))
		{
			$result .= '<th>'.$la['ODOMETER'].'</th>';
		}
		
		if (in_array("engine_hours", $data_items))
		{
			$result .= '<th>'.$la['ENGINE_HOURS'].'</th>';
		}
		
		$result .= '</tr>';
		
		for ($i=0; $i<count($imeis); ++$i)
		{
			$imei = $imeis[$i];
			
			//$q = "SELECT * FROM `gs_objects` WHERE `imei`='".$imei."'";
			if($user_id=='1035'){
				$q = "SELECT * FROM gs_objects obj join gs_user_objects uo on uo.imei=obj.imei left join gs_user_object_groups gup on gup.group_id=uo.group_id WHERE obj.imei='".$imei."' and uo.user_id='".$user_id."' ";		
			}else{
				$q = "SELECT * FROM `gs_objects` WHERE `imei`='".$imei."'";
			}

			$r = mysqli_query($ms, $q);
			
			while($row = mysqli_fetch_array($r))
			{
				$dt_server = $row['dt_server'];
				$dt_tracker = $row['dt_tracker'];
				$lat = $row['lat'];
				$lng = $row['lng'];
				$altitude = $row['altitude'];
				$angle = $row['angle'];
				$speed = $row['speed'];
				if($user_id=='1035'){
					$vendorname = $row['group_name'];
					$transportmodel = $row['model'];
				}else{
					$vendorname = "";
					$transportmodel = "";
				}
				
				if (($lat != 0) && ($lng != 0))
				{					
					$speed = convSpeedUnits($speed, 'km', $_SESSION["unit_distance"]);
					$altitude = convAltitudeUnits($altitude, 'km', $_SESSION["unit_distance"]);
					
					// status
					$status = '';
					$dt_last_stop = strtotime($row['dt_last_stop']);
					$dt_last_idle = strtotime($row['dt_last_idle']);
					$dt_last_move = strtotime($row['dt_last_move']);
					
					if (($dt_last_stop > 0) || ($dt_last_move > 0))
					{
						// stopped and moving
						if ($dt_last_stop >= $dt_last_move)
						{
							$status = $la['STOPPED'].' '.getTimeDetails(strtotime(gmdate("Y-m-d H:i:s")) - $dt_last_stop, true);
						}
						else
						{
							$status = $la['MOVING'].' '.getTimeDetails(strtotime(gmdate("Y-m-d H:i:s")) - $dt_last_move, true);
						}
						
						// idle
						if (($dt_last_stop <= $dt_last_idle) && ($dt_last_move <= $dt_last_idle))
						{
							$status = $la['ENGINE_IDLE'].' '.getTimeDetails(strtotime(gmdate("Y-m-d H:i:s")) - $dt_last_idle, true);
						}
					}
					
					// offline status
					$dt_now = gmdate("Y-m-d H:i:s");
					$dt_difference = strtotime($dt_now) - strtotime($dt_server);
					if($dt_difference > $gsValues['CONNECTION_TIMEOUT'] * 60)
					{
						if (strtotime($dt_server) > 0)
						{
							$status = $la['OFFLINE'].' '.getTimeDetails(strtotime(gmdate("Y-m-d H:i:s")) - strtotime($dt_server), true);
						}
						
						$speed = 0;
					}
					
					$odometer = getObjectOdometer($imei);
					$odometer = floor(convDistanceUnits($odometer, 'km', $_SESSION["unit_distance"]));
					
					$result .= '<tr align="center">';
					
					$result .= '<td>'.getObjectName($imei).'</td>';
					
					if ($_SESSION["user_id"]=="1035")
					{
						$result .= '<td>'.$vendorname.'</td>';
						$result .= '<td>'.$transportmodel.'</td>';
					}
					
					
					if (in_array("time", $data_items))
					{
						$result .= '<td>'.convUserTimezone($dt_tracker).'</td>';
					}
					
					if (in_array("position", $data_items))
					{
						$result .= '<td>'.reportsGetPossition($lat, $lng, $show_coordinates, $show_addresses, $zones_addresses).'</td>';
					}
					
					if (in_array("speed", $data_items))
					{
						$result .= '<td>'.$speed.' '.$la["UNIT_SPEED"].'</td>';
					}
					
					if (in_array("altitude", $data_items))
					{
						$result .= '<td>'.$altitude.' '.$la["UNIT_HEIGHT"].'</td>';
					}
					
					if (in_array("angle", $data_items))
					{
						$result .= '<td>'.$angle.'</td>';
					}
					
					if (in_array("status", $data_items))
					{
						$result .= '<td>'.$status.'</td>';
					}
					
					if (in_array("odometer", $data_items))
					{
						$result .= '<td>'.$odometer.' '.$la["UNIT_DISTANCE"].'</td>';
					}
					
					if (in_array("engine_hours", $data_items))
					{
						$result .= '<td>'.getObjectEngineHours($imei, true).'</td>';
					}
		
					$result .= '</tr>';
				}
				else
				{
					$result .= '<tr align="center">';
					$result .= '<td>'.getObjectName($imei).'</td>';
					$result .= '<td colspan="9">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td>';
					$result .= '</tr>';
				}
			}
		}
		
		$result .= '</table>';
		
		return $result;
	}

	function reportsGenerateDrivesAndStops($imei, $dtf, $dtt, $stop_duration, $show_coordinates, $show_addresses, $zones_addresses, $data_items) //DRIVES_AND_STOPS
	{
		global $_SESSION, $la, $user_id;
		
		$result = '';
		
		$data = getRoute($imei, $dtf, $dtt, $stop_duration, true);
		
		if (count($data['route']) < 2)
		{
			return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
		
		$result = '<table class="report" width="100%"><tr align="center">';
		
		if (in_array("status", $data_items))
		{
			$result .= '<th rowspan="2">'.$la['STATUS'].'</th>';
		}
		
		if (in_array("start", $data_items))
		{
			$result .= '<th rowspan="2">'.$la['START'].'</th>';
		}
		
		if (in_array("end", $data_items))
		{
			$result .= '<th rowspan="2">'.$la['END'].'</th>';
		}
		
		if (in_array("duration", $data_items))
		{
			$result .= '<th rowspan="2">'.$la['DURATION'].'</th>';
		}
		
		$result .= '<th colspan="3">'.$la['STOP_POSITION'].'</th>';
		
		if (in_array("fuel_consumption", $data_items))
		{
			$result .= '<th rowspan="2">'.$la['FUEL_CONSUMPTION'].'</th>';
		}
		
		if (in_array("fuel_cost", $data_items))
		{
			$result .= '<th rowspan="2">'.$la['FUEL_COST'].'</th>';
		}
		
		if (in_array("engine_idle", $data_items))
		{
			$result .= '<th rowspan="2">'.$la['ENGINE_IDLE'].'</th>';
		}
		
		$result .= '</tr>';
				
		$result .= '<tr align="center">
				<th>'.$la['LENGTH'].'</th>
				<th>'.$la['TOP_SPEED'].'</th>
				<th>'.$la['AVG_SPEED'].'</th>
				</tr>';
			
		$dt_sort = array();
		for ($i=0; $i<count($data['stops']); ++$i)
		{
			$dt_sort[] = $data['stops'][$i][6];
		}
		for ($i=0; $i<count($data['drives']); ++$i)
		{
			$dt_sort[] = $data['drives'][$i][4];
		}
		sort($dt_sort);	
		
		for ($i=0; $i<count($dt_sort); ++$i)
		{			
			for ($j=0; $j<count($data['stops']); ++$j)
			{
				if ($data['stops'][$j][6] == $dt_sort[$i])
				{
					$lat = sprintf("%01.6f", $data['stops'][$j][2]);
					$lng = sprintf("%01.6f", $data['stops'][$j][3]);
					
					$result .= '<tr align="center">';
					
					if (in_array("status", $data_items))
					{
						$result .= '<td>'.$la['STOPPED'].'</td>';
					}
					
					if (in_array("start", $data_items))
					{
						$result .= '<td>'.$data['stops'][$j][6].'</td>';
					}
					
					if (in_array("end", $data_items))
					{
						$result .= '<td>'.$data['stops'][$j][7].'</td>';
					}
					
					if (in_array("duration", $data_items))
					{
						$result .= '<td>'.$data['stops'][$j][8].'</td>';
					}
					
					$result .= '<td colspan="3">'.reportsGetPossition($lat, $lng, $show_coordinates, $show_addresses, $zones_addresses).'</td>';
					
					if (in_array("fuel_consumption", $data_items))
					{
						$result .= '<td></td>';
					}
					
					if (in_array("fuel_cost", $data_items))
					{
						$result .= '<td></td>';
					}
					
					if (in_array("engine_idle", $data_items))
					{
						$result .= '<td>'.$data['stops'][$j][9].'</td>';
					}
					
					$result .= '</tr>';
				}
			}
			for ($j=0; $j<count($data['drives']); ++$j)
			{
				if ($data['drives'][$j][4] == $dt_sort[$i])
				{					
					$result .= '<tr align="center">';
					
					if (in_array("status", $data_items))
					{
						$result .= '<td>'.$la['MOVING'].'</td>';
					}
					
					if (in_array("start", $data_items))
					{
						$result .= '<td>'.$data['drives'][$j][4].'</td>';
					}
					
					if (in_array("end", $data_items))
					{
						$result .= '<td>'.$data['drives'][$j][5].'</td>';
					}
					
					if (in_array("duration", $data_items))
					{
						$result .= '<td>'.$data['drives'][$j][6].'</td>';
					}
					
					$result .= '<td>'.$data['drives'][$j][7].' '.$la["UNIT_DISTANCE"].'</td>
							<td>'.$data['drives'][$j][8].' '.$la["UNIT_SPEED"].'</td>
							<td>'.$data['drives'][$j][9].' '.$la["UNIT_SPEED"].'</td>';
							
					if (in_array("fuel_consumption", $data_items))
					{
						$result .= '<td>'.$data['drives'][$j][10].' '.$la["UNIT_CAPACITY"].'</td>';
					}
					
					if (in_array("fuel_cost", $data_items))
					{
						$result .= '<td>'.$data['drives'][$j][11].' '.$_SESSION["currency"].'</td>';
					}
					
					if (in_array("engine_idle", $data_items))
					{
						$result .= '<td></td>';
					}
		
					$result .= '</tr>';
				}
			}
		}
		$result .= '</table><br/>';
		
		$result .= '<table>';
		
		if (in_array("move_duration", $data_items))
		{
			$result .= 	'<tr>
						<td><strong>'.$la['MOVE_DURATION'].':</strong></td>
						<td>'.$data['drives_duration'].'</td>
					</tr>';
		}
		
		if (in_array("stop_duration", $data_items))
		{
			$result .= 	'<tr>
						<td><strong>'.$la['STOP_DURATION'].':</strong></td>
						<td>'.$data['stops_duration'].'</td>
					</tr>';
		}
		
		if (in_array("route_length", $data_items))
		{
			$result .= 	'<tr>
						<td><strong>'.$la['ROUTE_LENGTH'].':</strong></td>
						<td>'.$data['route_length'].' '.$la["UNIT_DISTANCE"].'</td>
					</tr>';
		}
		
		if (in_array("top_speed", $data_items))
		{
			$result .= 	'<tr>
						<td><strong>'.$la['TOP_SPEED'].':</strong></td>
						<td>'.$data['top_speed'].' '.$la["UNIT_SPEED"].'</td>
					</tr>';
		}
		
		if (in_array("avg_speed", $data_items))
		{
			$result .= 	'<tr>
						<td><strong>'.$la['AVG_SPEED'].':</strong></td>
						<td>'.$data['avg_speed'].' '.$la["UNIT_SPEED"].'</td>
					</tr>';
		}
		
		if (in_array("fuel_consumption", $data_items))
		{
			$result .= 	'<tr>
						<td><strong>'.$la['FUEL_CONSUMPTION'].':</strong></td>
						<td>'.$data['fuel_consumption'].' '.$la["UNIT_CAPACITY"].'</td>
					</tr>';
		}
		
		if (in_array("fuel_cost", $data_items))
		{
			$result .= 	'<tr>
						<td><strong>'.$la['FUEL_COST'].':</strong></td>
						<td>'.$data['fuel_cost'].' '.$_SESSION["currency"].'</td>
					</tr>';
		}
		
		if (in_array("engine_work", $data_items))
		{
			$result .= 	'<tr>
						<td><strong>'.$la['ENGINE_WORK'].':</strong></td>
						<td>'.$data['engine_work'].'</td>
					</tr>';
		}
		
		if (in_array("engine_idle", $data_items))
		{
			$result .= 	'<tr>
						<td><strong>'.$la['ENGINE_IDLE'].':</strong></td>
						<td>'.$data['engine_idle'].'</td>
					</tr>';
		}
		
		$result .= '</table>';
		// if($user_id=='712'){
		// 	$myfile = fopen("vvv_report.txt", "a");
		// 	fwrite($myfile,error_reporting(E_ALL ^ E_NOTICE););
		// 	fwrite($myfile, "\n");
		// 	fclose($myfile);
		// }
		return $result;
	}
	
	function reportsGenerateTravelSheet($imei, $dtf, $dtt, $stop_duration, $show_coordinates, $show_addresses, $zones_addresses, $data_items) //TRAVEL_SHEET
	{
		global $_SESSION, $la, $user_id;
		
		$result = '';		
		$data = getRoute($imei, $dtf, $dtt, $stop_duration, true);
		
		if (count($data['drives']) < 1)
		{
			return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
		
		$result = '<table class="report" width="100%"><tr align="center">';
		
		if (in_array("time_a", $data_items))
		{
			$result .= '<th>'.$la['TIME_A'].'</th>';
		}
		
		if (in_array("position_a", $data_items))
		{
			$result .= '<th>'.$la['POSITION_A'].'</th>';
		}
		
		if (in_array("time_b", $data_items))
		{
			$result .= '<th>'.$la['TIME_B'].'</th>';
		}
		
		if (in_array("position_b", $data_items))
		{
			$result .= '<th>'.$la['POSITION_B'].'</th>';
		}
		
		if (in_array("duration", $data_items))
		{
			$result .= '<th>'.$la['DURATION'].'</th>';
		}
		
		if (in_array("length", $data_items))
		{
			$result .= '<th>'.$la['LENGTH'].'</th>';
		}
		
		if (in_array("fuel_consumption", $data_items))
		{
			$result .= '<th>'.$la['FUEL_CONSUMPTION'].'</th>';
		}
		
		if (in_array("fuel_cost", $data_items))
		{
			$result .= '<th>'.$la['FUEL_COST'].'</th>';
		}
		
		$result .= '</tr>';
		
		$total_route_length = 0;
		$total_fuel_consumption = 0;
		$total_fuel_cost = 0;
		
		for ($j=0; $j<count($data['drives']); ++$j)
		{			
			$route_id_a = $data['drives'][$j][0];
			$route_id_b = $data['drives'][$j][2];
			
			$lat1 = sprintf("%01.6f", $data['route'][$route_id_a][1]);
			$lng1 = sprintf("%01.6f", $data['route'][$route_id_a][2]);
			$lat2 = sprintf("%01.6f", $data['route'][$route_id_b][1]);
			$lng2 = sprintf("%01.6f", $data['route'][$route_id_b][2]);
			
			$time_a = $data['drives'][$j][4];
			
			$time_b = $data['drives'][$j][5];
			
			// this prevents double geocoder calling
			if(!isset($position_a))
			{
				$position_a = reportsGetPossition($lat1, $lng1, $show_coordinates, $show_addresses, $zones_addresses);
			}
			else
			{
				$position_a = $position_b;
			}
			
			$position_b = reportsGetPossition($lat2, $lng2, $show_coordinates, $show_addresses, $zones_addresses);
			
			$duration = $data['drives'][$j][6];
			
			$route_length = $data['drives'][$j][7];			
			$fuel_consumption = $data['drives'][$j][10];			
			$fuel_cost = $data['drives'][$j][11];
			
			$result .= '<tr align="center">';
			
			if (in_array("time_a", $data_items))
			{
				$result .= '<td>'.$time_a.'</td>';
			}
			
			if (in_array("position_a", $data_items))
			{
				$result .= '<td>'.$position_a.'</td>';
			}
			
			if (in_array("time_b", $data_items))
			{
				$result .= '<td>'.$time_b.'</td>';
			}
			
			if (in_array("position_b", $data_items))
			{
				$result .= '<td>'.$position_b.'</td>';
			}
			
			if (in_array("duration", $data_items))
			{
				$result .= '<td>'.$duration.'</td>';
			}
			
			if (in_array("length", $data_items))
			{
				$result .= '<td>'.$route_length.' '.$la["UNIT_DISTANCE"].'</td>';
				
				$total_route_length += $route_length;
			}
			
			if (in_array("fuel_consumption", $data_items))
			{
				$result .= '<td>'.$fuel_consumption.' '.$la["UNIT_CAPACITY"].'</td>';
				
				$total_fuel_consumption += $fuel_consumption;
			}
			
			if (in_array("fuel_cost", $data_items))
			{
				$result .= '<td>'.$fuel_cost.' '.$_SESSION["currency"].'</td>';
				
				$total_fuel_cost += $fuel_cost;
			}
			
			$result .= '</tr>';
		}
		
		if (in_array("total", $data_items))
		{
			$result .= '<tr align="center">';
			
			if (in_array("time_a", $data_items))
			{
				$result .= '<td></td>';
			}
			
			if (in_array("position_a", $data_items))
			{
				$result .= '<td></td>';
			}
			
			if (in_array("time_b", $data_items))
			{
				$result .= '<td></td>';
			}
			
			if (in_array("position_b", $data_items))
			{
				$result .= '<td></td>';
			}
			
			if (in_array("duration", $data_items))
			{
				$result .= '<td></td>';
			}
			
			if (in_array("length", $data_items))
			{
				$result .= '<td>'.$total_route_length.' '.$la["UNIT_DISTANCE"].'</td>';
			}
			
			if (in_array("fuel_consumption", $data_items))
			{
				$result .= '<td>'.$total_fuel_consumption.' '.$la["UNIT_CAPACITY"].'</td>';
			}
			
			if (in_array("fuel_cost", $data_items))
			{
				$result .= '<td>'.$total_fuel_cost.' '.$_SESSION["currency"].'</td>';
			}
			
			$result .= '</tr>';
		}
			
		$result .= '</table>';
		
		return $result;
	}
	
	// function reportsGenerateOverspeed($imei, $dtf, $dtt, $speed_limit,$max_speed_limit, $show_coordinates, $show_addresses, $zones_addresses, $data_items) //OVERSPEED
	function reportsGenerateOverspeed($imei, $dtf, $dtt, $speed_limit, $show_coordinates, $show_addresses, $zones_addresses, $data_items) //OVERSPEED
	{
		global $_SESSION, $la, $user_id;
		
		$accuracy = getObjectAccuracy($imei);
		
		$route = getRouteRaw($imei, $accuracy, $dtf, $dtt);
		//$route = removeRouteFakeCoordinates($route, array());
		$overspeeds = getRouteOverspeeds($route, $speed_limit);
		
		if ((count($route) == 0) || (count($overspeeds) == 0))
		{
			return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
		
		$result = '<table class="report" width="100%"><tr align="center">';
		
		if (in_array("start", $data_items))
		{
			$result .= '<th>'.$la['START'].'</th>';
		}
		
		if (in_array("end", $data_items))
		{
			$result .= '<th>'.$la['END'].'</th>';
		}
		
		if (in_array("duration", $data_items))
		{
			$result .= '<th>'.$la['DURATION'].'</th>';
		}
		
		if (in_array("top_speed", $data_items))
		{
			$result .= '<th>'.$la['TOP_SPEED'].'</th>';
		}
		
		if (in_array("avg_speed", $data_items))
		{
			$result .= '<th>'.$la['AVG_SPEED'].'</th>';
		}
		
		if (in_array("overspeed_position", $data_items))
		{
			$result .= '<th>'.$la['OVERSPEED_POSITION'].'</th>';
		}
		
		$result .= '</tr>';
		
		for ($i=0; $i<count($overspeeds); ++$i)
		{
			$result .= '<tr align="center">';
			
			if (in_array("start", $data_items))
			{
				$result .= '<td>'.$overspeeds[$i][0].'</td>';
			}
			
			if (in_array("end", $data_items))
			{
				$result .= '<td>'.$overspeeds[$i][1].'</td>';
			}
			
			if (in_array("duration", $data_items))
			{
				$result .= '<td>'.$overspeeds[$i][2].'</td>';
			}
			
			if (in_array("top_speed", $data_items))
			{
				$result .= '<td>'.$overspeeds[$i][3].' '.$la["UNIT_SPEED"].'</td>';
			}
			
			if (in_array("avg_speed", $data_items))
			{
				$result .= '<td>'.$overspeeds[$i][4].' '.$la["UNIT_SPEED"].'</td>';
			}
			
			if (in_array("overspeed_position", $data_items))
			{
				$result .= '<td>'.reportsGetPossition($overspeeds[$i][5], $overspeeds[$i][6], $show_coordinates, $show_addresses, $zones_addresses).'</td>';
			}
			
			$result .= '</tr>';
		}
		
		$result .= '</table>';
		
		return $result;
	}
	
	function reportsGenerateUnderspeed($imei, $dtf, $dtt, $speed_limit, $show_coordinates, $show_addresses, $zones_addresses, $data_items) //UNDERSPEED
	{
		global $_SESSION, $la, $user_id;
		
		$accuracy = getObjectAccuracy($imei);
		
		$route = getRouteRaw($imei, $accuracy, $dtf, $dtt);
		//$route = removeRouteFakeCoordinates($route, array());
		$underpeeds = getRouteUnderspeeds($route, $speed_limit);
		
		if ((count($route) == 0) || (count($underpeeds) == 0))
		{
			return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
		
		$result = '<table class="report" width="100%"><tr align="center">';
		
		if (in_array("start", $data_items))
		{
			$result .= '<th>'.$la['START'].'</th>';
		}
		
		if (in_array("end", $data_items))
		{
			$result .= '<th>'.$la['END'].'</th>';
		}
		
		if (in_array("duration", $data_items))
		{
			$result .= '<th>'.$la['DURATION'].'</th>';
		}
		
		if (in_array("top_speed", $data_items))
		{
			$result .= '<th>'.$la['TOP_SPEED'].'</th>';
		}
		
		if (in_array("avg_speed", $data_items))
		{
			$result .= '<th>'.$la['AVG_SPEED'].'</th>';
		}
		
		if (in_array("underspeed_position", $data_items))
		{
			$result .= '<th>'.$la['UNDERSPEED_POSITION'].'</th>';
		}
		
		$result .= '</tr>';
		
		for ($i=0; $i<count($underpeeds); ++$i)
		{
			$result .= '<tr align="center">';
			
			if (in_array("start", $data_items))
			{
				$result .= '<td>'.$underpeeds[$i][0].'</td>';
			}
			
			if (in_array("end", $data_items))
			{
				$result .= '<td>'.$underpeeds[$i][1].'</td>';
			}
			
			if (in_array("duration", $data_items))
			{
				$result .= '<td>'.$underpeeds[$i][2].'</td>';
			}
			
			if (in_array("top_speed", $data_items))
			{
				$result .= '<td>'.$underpeeds[$i][3].' '.$la["UNIT_SPEED"].'</td>';
			}
			
			if (in_array("avg_speed", $data_items))
			{
				$result .= '<td>'.$underpeeds[$i][4].' '.$la["UNIT_SPEED"].'</td>';
			}
			
			if (in_array("underspeed_position", $data_items))
			{
				$result .= '<td>'.reportsGetPossition($underpeeds[$i][5], $underpeeds[$i][6], $show_coordinates, $show_addresses, $zones_addresses).'</td>';
			}
			
			$result .= '</tr>';
		}
		
		$result .= '</table>';
		
		return $result;
	}

	function reportsGenerateZoneInOutNew($imeis, $dtf, $dtt, $show_coordinates, $show_addresses, $zones_addresses, $zone_ids, $data_items) //ZONE_IN_OUT
	{
		global $ms, $_SESSION, $la, $user_id;

		$result = '<table class="report" width="100%"><tr align="center">';
		
		$result .= '<th>'.$la['VEHICLENO'].'</th>';

		if (in_array("zone_in", $data_items))
		{
			$result .= '<th>'.$la['ZONE_IN'].'</th>';
		}
		
		if (in_array("zone_out", $data_items))
		{
			$result .= '<th>'.$la['ZONE_OUT'].'</th>';
		}
		
		if (in_array("duration", $data_items))
		{
			$result .= '<th>'.$la['DURATION'].'</th>';
		}

		$result .= '<th>'.$la['TAKENKM'].'</th>';
		
		if (in_array("zone_name", $data_items))
		{
			$result .= '<th>'.$la['ZONE_NAME'].'</th>';
		}
		
		if (in_array("zone_position", $data_items))
		{
			$result .= '<th>'.$la['ZONE_POSITION'].'</th>';
		}
		
		$result .= '</tr>';

		for ($i=0; $i<count($imeis); ++$i)
		{
			$imei = $imeis[$i];

			$temp_result = reportsGenerateZoneInOutNewSub($imei, $dtf, $dtt, $show_coordinates, $show_addresses, $zones_addresses, $zone_ids, $data_items);
			$result .= $temp_result;
		}

		$result .= '</table>';

		return $result;
	}

	function reportsGenerateZoneInOutNewSub($imei, $dtf, $dtt, $show_coordinates, $show_addresses, $zones_addresses, $zone_ids, $data_items) //ZONE_IN_OUT
	{
		global $ms, $_SESSION, $la, $user_id;
		
		$zone_ids = explode(",", $zone_ids);
		
		$accuracy = getObjectAccuracy($imei);
		
		$route = getRouteRaw($imei, $accuracy, $dtf, $dtt);
		$route_length_ary=0;
		if ($route!=''){
			$id_start_s = 0;
			$id_start = 0;
			$id_end = count($route)-1;
			
			$dt_start_s = $route[$id_start_s][0];
			$dt_start = $route[$id_start][0];
			$dt_end = $route[$id_end][0];

			// $route_length_ary = getRouteLength($route, $id_start_s, $id_end);
		}

		$vname = getObjectName($imei);

		//$route = removeRouteFakeCoordinates($route, array());
		
		$q = "SELECT * FROM `gs_user_zones` WHERE `user_id`='".$user_id."'";
		$r = mysqli_query($ms, $q);
		$zones = array();
		
		while($row=mysqli_fetch_array($r))
		{
			if(in_array($row['zone_id'], $zone_ids))
			{
				$zones[] = array($row['zone_id'],$row['zone_name'], $row['zone_vertices']);	
			}
		}
		
		if ((count($route) == 0) || (count($zones) == 0))
		{
			return '<tr><td>'.$vname.'</td><td colspan="6">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr>';
		}
		
		$in_zones = array();
		$in_zone = 0;

		for ($i=0; $i<count($route); ++$i)
		{

			$point_lat = $route[$i][1];
			$point_lng = $route[$i][2];
			
			for ($j=0; $j<count($zones); ++$j)
			{
				$zone_id = $zones[$j][0];
				$zone_name = $zones[$j][1];
				$zone_vertices = $zones[$j][2];
				
				$isPointInPolygon = isPointInPolygon($zone_vertices, $point_lat, $point_lng);
				
				if ($isPointInPolygon)
				{
					if ($in_zone == 0)
					{
						$in_zone_start = $route[$i][0];
						$in_zone_name = $zone_name;
						$in_zone_lat = $point_lat;
						$in_zone_lng = $point_lng;
						$in_zone = $zone_id;
					}
				}
				else
				{
					if ($in_zone == $zone_id)
					{
						$in_zone_end = $route[$i][0];
						$in_zone_duration = getTimeDifferenceDetails($in_zone_start, $in_zone_end);
						$in_zone = 0;
						$temp_i_start = $in_zone_start;

						if(count($in_zones)>0){
							$temp_id_end = count($in_zones)-1;
							$temp_last_zone_entry = $in_zones[$temp_id_end];
							$temp_i_start = $temp_last_zone_entry[1];
						}else{
							$temp_i_start = $in_zones[0][1];
						}

						$route_length_ary = getRouteLengthByDT($route, $temp_i_start, $in_zone_end);

						$in_zones[] = array($in_zone_start,
									$in_zone_end,
									$in_zone_duration,
									$in_zone_name,
									$in_zone_lat,
									$in_zone_lng,
									$route_length_ary
									);

					}
				}
			}
		}

		// add last zone record if it did not leave
		if ($in_zone != 0)
		{
			$temp_i_start = $in_zone_start;
			if(count($in_zones)>0){
				$temp_id_end = count($in_zones)-1;
				$temp_last_zone_entry = $in_zones[$temp_id_end];
				$temp_i_start = $temp_last_zone_entry[1];
			}
			// $route_length_ary = getRouteLengthByDT($route, $temp_i_start, $route[$id_end][0]);

			$in_zones[] = array($in_zone_start,
						$la['NA'],
						$la['NA'],
						$in_zone_name,
						$in_zone_lat,
						$in_zone_lng,
						0
						);	
		}
		
		if (count($in_zones) == 0)
		{
			return '<tr><td>'.$vname.'</td><td colspan="6">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr>';
		}
		
		$result = "";
		
		for ($i=0; $i<count($in_zones); ++$i)
		{
			$result .= '<tr align="center">';

			$result .= '<td>'.$vname.'</td>';
			
			if (in_array("zone_in", $data_items))
			{
				$result .= '<td>'.$in_zones[$i][0].'</td>';
			}
			
			if (in_array("zone_out", $data_items))
			{
				$result .= '<td>'.$in_zones[$i][1].'</td>';
			}
			
			if (in_array("duration", $data_items))
			{
				$result .= '<td>'.$in_zones[$i][2].'</td>';
			}

			$result .= '<td>'.$in_zones[$i][6].'</td>';
			
			if (in_array("zone_name", $data_items))
			{
				$result .= '<td>'.$in_zones[$i][3].'</td>';
			}
			
			if (in_array("zone_position", $data_items))
			{
				$result .= '<td>'.reportsGetPossition($in_zones[$i][4], $in_zones[$i][5], $show_coordinates, $show_addresses, $zones_addresses).'</td>';
			}
			
			$result .= '</tr>';
		}
		
		return $result;
	}
	
	function reportsGenerateZoneInOut($imei, $dtf, $dtt, $show_coordinates, $show_addresses, $zones_addresses, $zone_ids, $data_items) //ZONE_IN_OUT
	{
		global $ms, $_SESSION, $la, $user_id;
		
		$zone_ids = explode(",", $zone_ids);
		
		$accuracy = getObjectAccuracy($imei);
		
		$route = getRouteRaw($imei, $accuracy, $dtf, $dtt);
		$route_length_ary=0;
		if ($route!=''){
			$id_start_s = 0;
			$id_start = 0;
			$id_end = count($route)-1;
			
			$dt_start_s = $route[$id_start_s][0];
			$dt_start = $route[$id_start][0];
			$dt_end = $route[$id_end][0];

			$route_length_ary = getRouteLength($route, $id_start_s, $id_end);
		}

		// $myfile = fopen("vvv_f.txt", "a");
		// fwrite($myfile,$imei);
		// fwrite($myfile, "\n");
		// fwrite($myfile,json_encode($route_length_ary));
		// fwrite($myfile, "\n");
		// fclose($myfile);

		//$route = removeRouteFakeCoordinates($route, array());
		
		$q = "SELECT * FROM `gs_user_zones` WHERE `user_id`='".$user_id."'";
		$r = mysqli_query($ms, $q);
		$zones = array();
		
		while($row=mysqli_fetch_array($r))
		{
			if(in_array($row['zone_id'], $zone_ids))
			{
				$zones[] = array($row['zone_id'],$row['zone_name'], $row['zone_vertices']);	
			}
		}
		
		if ((count($route) == 0) || (count($zones) == 0))
		{
			return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
		
		$in_zones = array();
		$in_zone = 0;

		for ($i=0; $i<count($route); ++$i)
		{
			$point_lat = $route[$i][1];
			$point_lng = $route[$i][2];
			
			for ($j=0; $j<count($zones); ++$j)
			{
				$zone_id = $zones[$j][0];
				$zone_name = $zones[$j][1];
				$zone_vertices = $zones[$j][2];
				
				$isPointInPolygon = isPointInPolygon($zone_vertices, $point_lat, $point_lng);
				
				if ($isPointInPolygon)
				{
					if ($in_zone == 0)
					{
						$in_zone_start = $route[$i][0];
						$in_zone_name = $zone_name;
						$in_zone_lat = $point_lat;
						$in_zone_lng = $point_lng;
						$in_zone = $zone_id;
					}
				}
				else
				{
					if ($in_zone == $zone_id)
					{
						$in_zone_end = $route[$i][0];
						$in_zone_duration = getTimeDifferenceDetails($in_zone_start, $in_zone_end);
						$in_zone = 0;
						
						$in_zones[] = array($in_zone_start,
									$in_zone_end,
									$in_zone_duration,
									$in_zone_name,
									$in_zone_lat,
									$in_zone_lng
									);
					}
				}
			}
		}
		
		// add last zone record if it did not leave
		if ($in_zone != 0)
		{
			$in_zones[] = array($in_zone_start,
						$la['NA'],
						$la['NA'],
						$in_zone_name,
						$in_zone_lat,
						$in_zone_lng
						);	
		}
		
		if (count($in_zones) == 0)
		{
			return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
		
		$result = '<table class="report" width="100%"><tr align="center">';
		
		if (in_array("zone_in", $data_items))
		{
			$result .= '<th>'.$la['ZONE_IN'].'</th>';
		}
		
		if (in_array("zone_out", $data_items))
		{
			$result .= '<th>'.$la['ZONE_OUT'].'</th>';
		}
		
		if (in_array("duration", $data_items))
		{
			$result .= '<th>'.$la['DURATION'].'</th>';
		}
		
		if (in_array("zone_name", $data_items))
		{
			$result .= '<th>'.$la['ZONE_NAME'].'</th>';
		}
		
		if (in_array("zone_position", $data_items))
		{
			$result .= '<th>'.$la['ZONE_POSITION'].'</th>';
		}
		
		$result .= '</tr>';
		
		for ($i=0; $i<count($in_zones); ++$i)
		{
			$result .= '<tr align="center">';
			
			if (in_array("zone_in", $data_items))
			{
				$result .= '<td>'.$in_zones[$i][0].'</td>';
			}
			
			if (in_array("zone_out", $data_items))
			{
				$result .= '<td>'.$in_zones[$i][1].'</td>';
			}
			
			if (in_array("duration", $data_items))
			{
				$result .= '<td>'.$in_zones[$i][2].'</td>';
			}
			
			if (in_array("zone_name", $data_items))
			{
				$result .= '<td>'.$in_zones[$i][3].'</td>';
			}
			
			if (in_array("zone_position", $data_items))
			{
				$result .= '<td>'.reportsGetPossition($in_zones[$i][4], $in_zones[$i][5], $show_coordinates, $show_addresses, $zones_addresses).'</td>';
			}
			
			$result .= '</tr>';
		}
		
		$result .= '</table>';
		if (in_array("TOTAL_ROUTE_LENGTH", $data_items)){
			$result .= '<br><center><table>';
			$result .= '<tr>
				<td><strong><h3>'.$la['TOTAL_ROUTE_LENGTH'].':</h3></strong></td>
				<td>'.$route_length_ary.' '.$la["UNIT_DISTANCE"].'</td>
			</tr>';
			$result .= '</table></center>';
		}
		
		return $result;
	}

	function reportsGenerateTollInOut($imei, $dtf, $dtt, $show_coordinates, $show_addresses, $zones_addresses, $zone_ids, $data_items) //ZONE_IN_OUT
	{
		global $ms, $_SESSION, $la, $user_id;
		
		$zone_ids = explode(",", $zone_ids);
		
		$accuracy = getObjectAccuracy($imei);
		
		$route = getRouteRaw($imei, $accuracy, $dtf, $dtt);
		//$route = removeRouteFakeCoordinates($route, array());
		
		$q = "SELECT * FROM `gs_user_zones` WHERE user_id='1270' and group_id='23'";		
		$r = mysqli_query($ms, $q);
		$zones = array();
		
		while($row=mysqli_fetch_array($r))
		{
			$zones[] = array($row['zone_id'],$row['zone_name'], $row['zone_vertices']);		
		}		
		
		
		if ((count($route) == 0) || (count($zones) == 0))
		{
			return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}

		$in_zones = array();
		$in_zone = 0;
		for ($i=0; $i<count($route); ++$i)
		{
			$point_lat = $route[$i][1];
			$point_lng = $route[$i][2];
			
			for ($j=0; $j<count($zones); ++$j)
			{
				$zone_id = $zones[$j][0];
				$zone_name = $zones[$j][1];
				$zone_vertices = $zones[$j][2];
				
				$isPointInPolygon = isPointInPolygon($zone_vertices, $point_lat, $point_lng);
				if ($isPointInPolygon)
				{
					if ($in_zone == 0)
					{
						$in_zone_start = $route[$i][0];
						$in_zone_name = $zone_name;
						$in_zone_lat = $point_lat;
						$in_zone_lng = $point_lng;
						$in_zone = $zone_id;
					}
				}
				else
				{
					if ($in_zone == $zone_id)
					{
						$in_zone_end = $route[$i][0];
						$in_zone_duration = getTimeDifferenceDetails($in_zone_start, $in_zone_end);
						$in_zone = 0;
						
						$in_zones[] = array($in_zone_start,
									$in_zone_end,
									$in_zone_duration,
									$in_zone_name,
									$in_zone_lat,
									$in_zone_lng
									);
					}
				}
			}
		}
		
		// add last zone record if it did not leave
		if ($in_zone != 0)
		{
			$in_zones[] = array($in_zone_start,
						$la['NA'],
						$la['NA'],
						$in_zone_name,
						$in_zone_lat,
						$in_zone_lng
						);	
		}		

		if (count($in_zones) == 0)
		{
			return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
		
		$result = '<table class="report" width="100%"><tr align="center">';
		
		if (in_array("zone_in", $data_items))
		{
			$result .= '<th>'.$la['TOLL_IN'].'</th>';
		}
		
		if (in_array("zone_out", $data_items))
		{
			$result .= '<th>'.$la['TOLL_OUT'].'</th>';
		}
		
		if (in_array("duration", $data_items))
		{
			$result .= '<th>'.$la['DURATION'].'</th>';
		}
		
		if (in_array("zone_name", $data_items))
		{
			$result .= '<th>'.$la['TOLL_NAME'].'</th>';
		}
		
		if (in_array("zone_position", $data_items))
		{
			$result .= '<th>'.$la['POSITION'].'</th>';
		}
		
		$result .= '</tr>';
		
		for ($i=0; $i<count($in_zones); ++$i)
		{

			$result .= '<tr align="center">';
			
			if (in_array("zone_in", $data_items))
			{
				$result .= '<td>'.$in_zones[$i][0].'</td>';
			}
			
			if (in_array("zone_out", $data_items))
			{
				$result .= '<td>'.$in_zones[$i][1].'</td>';
			}
			
			if (in_array("duration", $data_items))
			{
				$result .= '<td>'.$in_zones[$i][2].'</td>';
			}
			
			if (in_array("zone_name", $data_items))
			{
				$result .= '<td>'.$in_zones[$i][3].'</td>';
			}
			
			if (in_array("zone_position", $data_items))
			{
				$result .= '<td>'.reportsGetPossition($in_zones[$i][4], $in_zones[$i][5], $show_coordinates, $show_addresses, $zones_addresses).'</td>';
			}
			
			$result .= '</tr>';
		}
		
		$result .= '</table>';
		
		return $result;
	}
	
	function reportsGenerateEvents($imei, $dtf, $dtt, $show_coordinates, $show_addresses, $zones_addresses, $data_items,$event_list) //EVENTS
	{
		global $ms, $_SESSION, $la, $user_id;
		$event_data='';
		$event_list=explode(',', $event_list);
		for($i=0;$i<count($event_list);$i++){
			$event_data.='\''.$event_list[$i].'\',';
		}
		$event_data=substr($event_data,0,-1);
		$q = "SELECT * FROM `gs_user_events_data` WHERE `user_id`='".$user_id."' AND `imei`='".$imei."' AND dt_tracker BETWEEN '".$dtf."' AND '".$dtt."'";
		if(count($event_list)>0){
			$q.=" AND type IN (".$event_data.")";
		}
		$q.=" ORDER BY dt_tracker ASC";
		$r = mysqli_query($ms, $q);
		$count = mysqli_num_rows($r);
		
		if ($count == 0)
		{
			return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
		
		$result = '<table class="report" width="100%"><tr align="center">';
		
		if (in_array("time", $data_items))
		{
			$result .= '<th>'.$la['TIME'].'</th>';
		}
		
		if (in_array("event", $data_items))
		{
			$result .= '<th>'.$la['EVENT'].'</th>';
		}
		
		if (in_array("event_position", $data_items))
		{
			$result .= '<th>'.$la['EVENT_POSITION'].'</th>';
		}
		
		$result .= '</tr>';	
		
		$total_events = array();
		
		while($event_data=mysqli_fetch_array($r))
		{
			$result .= '<tr align="center">';
			
			if (in_array("time", $data_items))
			{
				$result .= '<td>'.convUserTimezone($event_data['dt_tracker']).'</td>';
			}
			
			if (in_array("event", $data_items))
			{
				$result .= '<td>'.$event_data['event_desc'].'</td>';
			}
			
			if (in_array("event_position", $data_items))
			{
				$result .= '<td>'.reportsGetPossition($event_data['lat'], $event_data['lng'], $show_coordinates, $show_addresses, $zones_addresses).'</td>';
			}
			
			$result .= '</tr>';
			
			if (isset($total_events[$event_data['event_desc']]))
			{
				$total_events[$event_data['event_desc']]++;
			}
			else
			{
				$total_events[$event_data['event_desc']] = 1;
			}
		}
		
		$result .= '</table>';
		
		if (in_array("total", $data_items))
		{
			$result .= '<br/>';
			
			ksort($total_events);
			
			$result .= '<table>';
			foreach ($total_events as $key=>$value)
			{
				$result .= '<tr>
					<td><strong>'.$key.':</strong></td>
					<td>'.$value.'</td>
				</tr>';
			}
			$result .= '</table>';
		}
		
		return $result;
	}
	
	function reportsGenerateService($imei, $data_items) //SERVICE
	{
		global $ms, $_SESSION, $la, $user_id;
		
		$result = '';
		
		$q = "SELECT * FROM `gs_object_services` WHERE `imei`='".$imei."' ORDER BY name asc";
		$r = mysqli_query($ms, $q);		
		$count = mysqli_num_rows($r);
		
		if ($count == 0)
		{
			return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
		
		$result = '<table class="report" width="100%"><tr align="center">';
		
		if (in_array("service", $data_items))
		{
			$result .= '<th width="20%">'.$la['SERVICE'].'</th>';
		}
		
		if (in_array("last_service", $data_items))
		{
			$result .= 	'<th width="15%">'.$la['LAST_SERVICE'].' ('.$la["UNIT_DISTANCE"].')</th>
					<th width="15%">'.$la['LAST_SERVICE'].' (h)</th>
					<th width="15%">'.$la['LAST_SERVICE'].'</th>';
		}
		
		if (in_array("status", $data_items))
		{
			$result .= '<th width="35%">'.$la['STATUS'].'</th>';
		}
		
		$result .= '</tr>';
		
		// get real odometer and engine hours
                $odometer = getObjectOdometer($imei);
		$odometer = floor(convDistanceUnits($odometer, 'km', $_SESSION["unit_distance"]));
		
		$engine_hours = getObjectEngineHours($imei, false);
		
		while($row = mysqli_fetch_array($r)) {
			$service_id = $row["service_id"];
			$name = $row['name'];
                        $odo_last = $la['NA'];
			$engh_last = $la['NA'];
			$days_last = $la['NA'];
			
			$status_arr = array();
			
                        if ($row['odo'] == 'true')
                        {
				$row['odo_interval'] = floor(convDistanceUnits($row['odo_interval'], 'km', $_SESSION["unit_distance"]));
				$row['odo_last'] = floor(convDistanceUnits($row['odo_last'], 'km', $_SESSION["unit_distance"]));
				
				$odo_diff = $odometer - $row['odo_last'];
				$odo_diff = $row['odo_interval'] - $odo_diff;
				
				if ($odo_diff <= 0)
				{
					$odo_diff = abs($odo_diff);
					$status_arr[] = '<font color="red">'.$la['ODOMETER_EXPIRED'].' ('.$odo_diff.' '.$la["UNIT_DISTANCE"].')</font>';
				}
				else
				{
					$status_arr[] = $la['ODOMETER_LEFT'].' ('.$odo_diff.' '.$la["UNIT_DISTANCE"].')';
				}
				
				$odo_last = $row['odo_last'];
                        }
                        
                        if ($row['engh'] == 'true')
                        {
				$engh_diff = $engine_hours - $row['engh_last'];
				$engh_diff = $row['engh_interval'] - $engh_diff;
				
				if ($engh_diff <= 0)
				{
					$engh_diff = abs($engh_diff);
					$status_arr[] = '<font color="red">'.$la['ENGINE_HOURS_EXPIRED'].' ('.$engh_diff.' '.$la["UNIT_H"].')</font>';
				}
				else
				{
					$status_arr[] = $la['ENGINE_HOURS_LEFT'].' ('.$engh_diff.' '.$la["UNIT_H"].')';
				}
				
				$engh_last = $row['engh_last'];
                        }
                        
                        if ($row['days'] == 'true')
                        {
				$days_diff = strtotime(gmdate("M d Y ")) - (strtotime($row['days_last']));
				$days_diff = floor($days_diff/3600/24);
				$days_diff = $row['days_interval'] - $days_diff;
				
				if ($days_diff <= 0)
				{
					$days_diff = abs($days_diff);
					$status_arr[] = '<font color="red">'.$la['DAYS_EXPIRED'].' ('.$days_diff.')</font>';
				}
				else
				{
					$status_arr[] = $la['DAYS_LEFT'].' ('.$days_diff.')';
				}
				
				$days_last = $row['days_last'];
                        }
			
			if (in_array("service", $data_items))
			{
				$result .= '<tr><td>'.$name.'</td>';
			}
			
			if (in_array("last_service", $data_items))
			{
				$result .= '<td align="center">'.$odo_last.'</td>
					<td align="center">'.$engh_last.'</td>
					<td align="center">'.$days_last.'</td>';
			}
			
			if (in_array("status", $data_items))
			{
				$status = strtolower(implode(", ", $status_arr));
				$result .= '<td>'.$status.'</td>';
			}
			
			$result .= '</tr>';
		}
		
		$result .= '</table>';
		
		return $result;
	}
	
	function reportsGenerateRag($imeis, $dtf, $dtt, $speed_limit, $data_items)
	{
		global $ms, $_SESSION, $la, $user_id;
		
		$result = '<table class="report" width="100%" ><tr align="center">';
				
		$result .= '<th>'.$la['DRIVER'].'</th>';
		$result .= '<th>'.$la['OBJECT'].'</th>';
		$result .= '<th>'.$la['ROUTE_LENGTH'].'</th>';
		
		if (in_array("overspeed_score", $data_items))
		{
			$result .= '<th>'.$la['OVERSPEED_DURATION'].'</th>';
			$result .= '<th>'.$la['OVERSPEED_SCORE'].'</th>';
		}
		
		if (in_array("harsh_acceleration_score", $data_items))
		{
			$result .= '<th>'.$la['HARSH_ACCELERATION_COUNT'].'</th>';
			$result .= '<th>'.$la['HARSH_ACCELERATION_SCORE'].'</th>';
		}
		
		if (in_array("harsh_braking_score", $data_items))
		{
			$result .= '<th>'.$la['HARSH_BRAKING_COUNT'].'</th>';
			$result .= '<th>'.$la['HARSH_BRAKING_SCORE'].'</th>';
		}
		
		if (in_array("harsh_cornering_score", $data_items))
		{
			$result .= '<th>'.$la['HARSH_CORNERING_COUNT'].'</th>';
			$result .= '<th>'.$la['HARSH_CORNERING_SCORE'].'</th>';
		}
		
		$result .= '<th>'.$la['RAG'].'</th>';
		$result .= '</tr>';
		
		$rag = array();
		
		for ($i=0; $i<count($imeis); ++$i)
		{
			$imei = $imeis[$i];
			
			$data = getRoute($imei, $dtf, $dtt, 1, true);
			
			if (count($data['route']) == 0)
			{
				continue;
			}
			
			$haccel_count = 0;
			$hbrake_count = 0;
			$hcorn_count = 0;
			
			$q = "SELECT * FROM `gs_user_events_data` WHERE `user_id`='".$user_id."' AND `imei`='".$imei."'
			AND `type`='haccel' AND dt_tracker BETWEEN '".$dtf."' AND '".$dtt."' ORDER BY dt_tracker ASC";
			$r = mysqli_query($ms, $q);
			
			$haccel_count = mysqli_num_rows($r);
			
			$q = "SELECT * FROM `gs_user_events_data` WHERE `user_id`='".$user_id."' AND `imei`='".$imei."'
			AND `type`='hbrake' AND dt_tracker BETWEEN '".$dtf."' AND '".$dtt."' ORDER BY dt_tracker ASC";
			$r = mysqli_query($ms, $q);
			
			$hbrake_count = mysqli_num_rows($r);
			
			$q = "SELECT * FROM `gs_user_events_data` WHERE `user_id`='".$user_id."' AND `imei`='".$imei."'
			AND `type`='hcorn' AND dt_tracker BETWEEN '".$dtf."' AND '".$dtt."' ORDER BY dt_tracker ASC";
			$r = mysqli_query($ms, $q);
			
			$hcorn_count = mysqli_num_rows($r);
			
			$q = "SELECT * FROM `gs_objects` WHERE `imei`='".$imei."'";
			$r = mysqli_query($ms, $q);
			$row = mysqli_fetch_array($r);
			$params = json_decode($row['params'],true);
			$driver = getObjectDriver($user_id, $imei, $params);
						
			if ($driver == false)
			{
				continue;
			}
			
			$route_length = $data['route_length'];
			
			$overspeed_duration = 0;
			$overspeed = 0;
			
			for ($j=0; $j<count($data['route']); ++$j)
			{
				$speed = $data['route'][$j][5];
				
				if ($speed > $speed_limit)
				{	
					if($overspeed == 0)
					{
						$overspeed_start = $data['route'][$j][0];
						$overspeed = 1;
					}
				}
				else
				{
					if ($overspeed == 1)
					{
						$overspeed_end = $data['route'][$j][0];
						$overspeed_duration += strtotime($overspeed_end) - strtotime($overspeed_start);
						$overspeed = 0;
					}
				}
			}
			
			if ($route_length > 0 )
			{
				$overspeed_score = $overspeed_duration / 10 / $route_length * 100;
				$overspeed_score = sprintf('%0.2f', $overspeed_score);
				
				$haccel_score = $haccel_count / $route_length * 100;
				$haccel_score = sprintf('%0.2f', $haccel_score);
				
				$hbrake_score = $hbrake_count / $route_length * 100;
				$hbrake_score = sprintf('%0.2f', $hbrake_score);
				
				$hcorn_score = $hcorn_count / $route_length * 100;
				$hcorn_score = sprintf('%0.2f', $hcorn_score);	
			}
			else
			{
				$overspeed_score = 0;
				$haccel_score = 0;
				$hbrake_score = 0;
				$hcorn_score = 0;
			}
			
			$rag_score = 0;
			
			if (in_array("overspeed_score", $data_items))
			{
				$rag_score += $overspeed_score;
			}
			
			if (in_array("harsh_acceleration_score", $data_items))
			{
				$rag_score += $haccel_score;
			}
			
			if (in_array("harsh_braking_score", $data_items))
			{
				$rag_score += $hbrake_score;
			}
			
			if (in_array("harsh_cornering_score", $data_items))
			{
				$rag_score += $hcorn_score;
			}
			
			$rag_score = sprintf('%0.2f', $rag_score);
			
			$rag[] = array('driver_name' => $driver['driver_name'],
				       'object_name' => getObjectName($imei),
				       'route_length' => $route_length,
				       'overspeed_duration' => $overspeed_duration,
				       'overspeed_score' => $overspeed_score,
				       'haccel_count' => $haccel_count,
				       'haccel_score' => $haccel_score,
				       'hbrake_count' => $hbrake_count,
				       'hbrake_score' => $hbrake_score,
				       'hcorn_count' => $hcorn_count,
				       'hcorn_score' => $hcorn_score,
				       'rag_score' => $rag_score
			);
		}
		
		if (count($rag) == 0)
		{
			$result .= '<tr><td align="center" colspan="12">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr>';
		}
		
		// list all drivers
		for ($i=0; $i<count($rag); ++$i)
		{
			$result .= '<tr align="center">';
			
			$result .= '<td>'.$rag[$i]['driver_name'].'</td>';
			$result .= '<td>'.$rag[$i]['object_name'].'</td>';
			$result .= '<td>'.$rag[$i]['route_length'].' '.$la['UNIT_DISTANCE'].'</td>';
			
			if (in_array("overspeed_score", $data_items))
			{
				$result .= '<td>'.$rag[$i]['overspeed_duration'].' '.$la['UNIT_S'].'</td>';
				$result .= '<td>'.$rag[$i]['overspeed_score'].'</td>';
			}
			
			if (in_array("harsh_acceleration_score", $data_items))
			{
				$result .= '<td>'.$rag[$i]['haccel_count'].'</td>';
				$result .= '<td>'.$rag[$i]['haccel_score'].'</td>';
			}
			
			if (in_array("harsh_braking_score", $data_items))
			{
				$result .= '<td>'.$rag[$i]['hbrake_count'].'</td>';
				$result .= '<td>'.$rag[$i]['hbrake_score'].'</td>';
			}
			
			if (in_array("harsh_cornering_score", $data_items))
			{
				$result .= '<td>'.$rag[$i]['hcorn_count'].'</td>';
				$result .= '<td>'.$rag[$i]['hcorn_score'].'</td>';
			}
			
			if ($rag[$i]['rag_score'] <= 2)
			{
				$rag_color = '#00FF00';
			}
			else if (($rag[$i]['rag_score'] > 2) && ($rag[$i]['rag_score'] <= 5))
			{
				$rag_color = '#FFFF00';
			}
			else if ($rag[$i]['rag_score'] > 5)
			{
				$rag_color = '#FF0000';
			}
			
			$result .= '<td bgcolor="'.$rag_color.'">'.$rag[$i]['rag_score'].'</td>';
			
			$result .= '</tr>';	
		}
		
		$result .= '</table>';
		
		return $result;
	}

	function reportsGenerateFuelFillings($imei, $dtf, $dtt, $show_coordinates, $show_addresses, $zones_addresses, $data_items) //FUEL_FILLINGS
	{
		global $_SESSION, $la, $user_id;
		
		$result = '';
		
		$accuracy = getObjectAccuracy($imei);
		$fuel_sensors = getSensorFromType($imei, 'fuel');
		
		if(!$fuel_sensors)
		{
			return '<table><tr><td>'.$la['SENSOR_NOT_ADD'].'</td></tr></table>';
		}
		
		//$route = getRouteRaw($imei, $accuracy, $dtf, $dtt);
		$retndata = getIFSDataFuel($imei, $accuracy, $fuel_sensors, $dtf, $dtt);
		$route=array();
		if (count($retndata)> 0) // || ($fuel_sensors == false))
		{
			$route=$retndata[1];		
		}
		
		$ff = getRouteFuelFillingsnew($route, $accuracy, $fuel_sensors);
		
		if ((count($route) == 0) || (count($ff['fillings']) == 0))
		{
			return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
		
		$result = '<table class="report" width="100%"><tr align="center">';
		
		if (in_array("time", $data_items))
		{
			$result .= '<th>'.$la['TIME'].'</th>';
		}
		
		if (in_array("position", $data_items))
		{
			$result .= '<th>'.$la['POSITION'].'</th>';
		}
		
		if (in_array("before", $data_items))
		{
			$result .= '<th>'.$la['BEFORE'].'</th>';
		}
		
		if (in_array("after", $data_items))
		{
			$result .= '<th>'.$la['AFTER'].'</th>';
		}
		
		if (in_array("filled", $data_items))
		{
			$result .= '<th>'.$la['FILLED']." ".$fuel_sensors[0]["units"].'</th>';
		}
		
		if (in_array("sensor", $data_items))
		{
			$result .= '<th>'.$la['SENSOR'].'</th>';
		}
		
		if (in_array("driver", $data_items))
		{
			$result .= '<th>'.$la['DRIVER'].'</th>';
		}
		
		$result .= '</tr>';
		
		
		for ($j=0; $j<count($fuel_sensors); ++$j)
		{
			$sensor = $fuel_sensors[$j]['name'];
			
			for ($i=0; $i<count($ff['fillings'][$sensor]); ++$i)
			{
				$lat = $ff['fillings'][$sensor][$i]["lat"];
				$lng = $ff['fillings'][$sensor][$i]["lng"];

				$params = $ff['fillings'][$sensor][$i]["params"];
				$driver = getObjectDriver($user_id, $imei, $params);
				if ($driver['driver_name'] == ''){$driver['driver_name'] = $la['NA'];}

				$result .= '<tr align="center">';
				if (in_array("time", $data_items))
				{
					$result .= '<td>'.$ff['fillings'][$sensor][$i]["end"].'</td>';
				}

				if (in_array("position", $data_items))
				{
					$result .= '<td>'.reportsGetPossition($lat, $lng, $show_coordinates, $show_addresses, $zones_addresses).'</td>';
				}

				if (in_array("before", $data_items))
				{
					$result .= '<td>'.$ff['fillings'][$sensor][$i]["before"].'</td>';
				}

				if (in_array("after", $data_items))
				{
					$result .= '<td>'.$ff['fillings'][$sensor][$i]["after"].'</td>';
				}

				if (in_array("filled", $data_items))
				{
					$result .= '<td>'.$ff['fillings'][$sensor][$i]["filled"].'</td>';
				}

				if (in_array("sensor", $data_items))
				{
					$result .= '<td>'.$ff['fillings'][$sensor][$i]["sensor"].'</td>';
				}

				if (in_array("driver", $data_items))
				{
					$result .= '<td>'.$driver['driver_name'].'</td>';
				}
						$result .= '</tr>';
					
			}
		}
		
		$result .= '</table>';
		
		if (in_array("total", $data_items))
		{
			$result .= '<br/>';
			$result .= '<table>';
			$result .= '<tr><td><strong>'.$la['FILLED'].':</strong></td><td>'.$ff['total_filled'].'</td></tr>';
			$result .= '</table>';
		}
		
		return $result;
	}
	
	function reportsGenerateFuelThefts($imei, $dtf, $dtt, $show_coordinates, $show_addresses, $zones_addresses, $data_items) //FUEL_THEFTS
	{
		global $_SESSION, $la, $user_id;
		
		$result = '';
		
		$accuracy = getObjectAccuracy($imei);
		$fuel_sensors = getSensorFromType($imei, 'fuel');
		
		if(!$fuel_sensors)
		{
			return '<table><tr><td>'.$la['SENSOR_NOT_ADD'].'</td></tr></table>';
		}
		

		//$route = getRouteRaw($imei, $accuracy, $dtf, $dtt);
		$retndata = getIFSDataFuel($imei, $accuracy, $fuel_sensors, $dtf, $dtt);
		$route=array();
		if (count($retndata)> 0) // || ($fuel_sensors == false))
		{
			$route=$retndata[1];		
		}
		
		$ft = getRouteFuelTheftsnew($route, $accuracy, $fuel_sensors);
		
		if ((count($route) == 0) || (count($ft['thefts']) == 0))
		{
			return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
		
		$result = '<table class="report" width="100%"><tr align="center">';
		
		if (in_array("time", $data_items))
		{
			$result .= '<th>'.$la['TIME'].'</th>';
		}
		
		if (in_array("position", $data_items))
		{
			$result .= '<th>'.$la['POSITION'].'</th>';
		}
		
		if (in_array("before", $data_items))
		{
			$result .= '<th>'.$la['BEFORE'].'</th>';
		}
		
		if (in_array("after", $data_items))
		{
			$result .= '<th>'.$la['AFTER'].'</th>';
		}
		
		if (in_array("stolen", $data_items))
		{
			$result .= '<th>'.$la['STOLEN'].'</th>';
		}
		
		if (in_array("sensor", $data_items))
		{
			$result .= '<th>'.$la['SENSOR'].'</th>';
		}
		
		if (in_array("driver", $data_items))
		{
			$result .= '<th>'.$la['DRIVER'].'</th>';
		}
		
		$result .= '</tr>';
		
		for ($j=0; $j<count($fuel_sensors); ++$j)
		{
			$sensor = $fuel_sensors[$j]['name'];
			for ($i=0; $i<count($ft['thefts'][$sensor]); ++$i)
			{
				if($ft['thefts'][$sensor][$i]["after"]!=0){
					$lat = $ft['thefts'][$sensor][$i]["lat"];
					$lng = $ft['thefts'][$sensor][$i]["lng"];
						
					$params = $ft['thefts'][$sensor][$i]["params"];
					$driver = getObjectDriver($user_id, $imei, $params);
					if ($driver['driver_name'] == ''){$driver['driver_name'] = $la['NA'];}
						
					$result .= '<tr align="center">';
						
					if (in_array("time", $data_items))
					{
						$result .= '<td>'.$ft['thefts'][$sensor][$i]["end"].'</td>';
					}
						
					if (in_array("position", $data_items))
					{
						$result .= '<td>'.reportsGetPossition($lat, $lng, $show_coordinates, $show_addresses, $zones_addresses).'</td>';
					}
						
					if (in_array("before", $data_items))
					{
						$result .= '<td>'.$ft['thefts'][$sensor][$i]["before"].'</td>';
					}
						
					if (in_array("after", $data_items))
					{
						$result .= '<td>'.$ft['thefts'][$sensor][$i]["after"].'</td>';
					}
						
					if (in_array("stolen", $data_items))
					{
						$result .= '<td>'.$ft['thefts'][$sensor][$i]["siphon"].'</td>';
					}
						
					if (in_array("sensor", $data_items))
					{
						$result .= '<td>'.$ft['thefts'][$sensor][$i]["sensor"].'</td>';
					}
						
					if (in_array("driver", $data_items))
					{
						$result .= '<td>'.$driver['driver_name'].'</td>';
					}
						
					$result .= '</tr>';
				}
			}
		}

		$result .= '</table>';
		
		if (in_array("total", $data_items))
		{
			$result .= '<br/>';
			$result .= '<table>';
			$result .= '<tr><td><strong>'.$la['STOLEN'].':</strong></td><td>'.$ft['total_stolen'].'</td></tr>';
			$result .= '</table>';
		}
		
		return $result;
	}
	
	function reportsGenerateLogicSensorInfo($imei, $dtf, $dtt, $sensors, $show_coordinates, $show_addresses, $zones_addresses, $data_items) //LOGIC_SENSORS
	{
		global $_SESSION, $gsValues, $la, $user_id;
		
		$accuracy = getObjectAccuracy($imei);		
		$route = getRouteRaw($imei, $accuracy, $dtf, $dtt);		
		$lsi = getRouteLogicSensorInfo($route, $accuracy, $sensors);
		
		if ((count($route) == 0) || (count($lsi) == 0) || ($sensors == false))
		{
			return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
		
		$result = '<table class="report" width="100%"><tr align="center">';
		
		if (in_array("sensor", $data_items))
		{
			$result .= '<th>'.$la['SENSOR'].'</th>';
		}
		
		if (in_array("activation_time", $data_items))
		{
			$result .= '<th>'.$la['ACTIVATION_TIME'].'</th>';
		}
		
		if (in_array("deactivation_time", $data_items))
		{
			$result .= '<th>'.$la['DEACTIVATION_TIME'].'</th>';
		}
		
		if (in_array("duration", $data_items))
		{
			$result .= '<th>'.$la['DURATION'].'</th>';
		}
		
		if (in_array("activation_position", $data_items))
		{
			$result .= '<th>'.$la['ACTIVATION_POSITION'].'</th>';
		}
		
		if (in_array("deactivation_position", $data_items))
		{
			$result .= '<th>'.$la['DEACTIVATION_POSITION'].'</th>';
		}
		
		$result .= '</tr>';
		
		for ($i=0; $i<count($lsi); ++$i)
		{
			$sensor_name = $lsi[$i][0];
			$lsi_activation_time = $lsi[$i][1];
			$lsi_deactivation_time = $lsi[$i][2];
			$lsi_duration = $lsi[$i][3];
			$lsi_activation_lat = $lsi[$i][4];
			$lsi_activation_lng = $lsi[$i][5];
			$lsi_deactivation_lat = $lsi[$i][6];
			$lsi_deactivation_lng = $lsi[$i][7];
			
			$result .= '<tr align="center">';
			
			if (in_array("sensor", $data_items))
			{
				$result .= '<td>'.$sensor_name.'</td>';
			}
			
			if (in_array("activation_time", $data_items))
			{
				$result .= '<td>'.$lsi_activation_time.'</td>';
			}
			
			if (in_array("deactivation_time", $data_items))
			{
				$result .= '<td>'.$lsi_deactivation_time.'</td>';
			}
			
			if (in_array("duration", $data_items))
			{
				$result .= '<td>'.$lsi_duration.'</td>';
			}
			
			if (in_array("activation_position", $data_items))
			{
				$result .= '<td>'.reportsGetPossition($lsi_activation_lat, $lsi_activation_lng, $show_coordinates, $show_addresses, $zones_addresses).'</td>';
			}
			
			if (in_array("deactivation_position", $data_items))
			{
				$result .= '<td>'.reportsGetPossition($lsi_deactivation_lat, $lsi_deactivation_lng, $show_coordinates, $show_addresses, $zones_addresses).'</td>';
			}
			
			$result .= '</tr>';
		}
		
		$result .= '</table>';
		
		return $result;
	}
	
	function reportsGenerateGraph($imei, $dtf, $dtt, $sensors) //SENSOR GRAPH
	{
		global $_SESSION, $gsValues, $la, $user_id;
		
		$result = '';
		
		$accuracy = getObjectAccuracy($imei);
		
		$route = getRouteRaw($imei, $accuracy, $dtf, $dtt);
		
		if ((count($route) == 0) || ($sensors == false))
		{
			return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
		
		// loop per sensors
		for ($i=0; $i<count($sensors); ++$i)
		{
			$graph = array();
			$graph['data'] = array();
			$graph['data_index'] = array();
			
			// prepare graph plot id
			$graph_plot_id = $imei.'_'.$i;
			
			// prepare data
			$sensor = $sensors[$i];
			
			for ($j=0; $j<count($route); ++$j)
			{				
				$dt_tracker = $route[$j][0];
				$dt_tracker_timestamp = strtotime($dt_tracker) * 1000;
				
				if ($sensor['type'] == 'speed')
				{
					$value = $route[$j][5];
				}
				else if ($sensor['type'] == 'altitude')
				{
					$value = $route[$j][3];
				}
				else
				{
					$data = getSensorValue($route[$j][6], $sensor);
					
					if ($sensor['type'] == 'engh')
					{
						$data['value'] = $data['value'] / 60 / 60;
						$data['value'] = sprintf("%01.2f", $data['value']);
					}
					
					$value = $data['value'];	
				}
				
				$graph['data'][] = array($dt_tracker_timestamp, $value);
				$graph['data_index'][$dt_tracker_timestamp] = $j;
			}
			
			// set units
			if ($sensor['type'] == 'odo')
			{
				$graph['units'] = $la['UNIT_DISTANCE'];
				$graph['result_type'] = $sensor['result_type'];
			}
			else if ($sensor['type'] == 'engh')
			{
				$graph['units'] = $la['UNIT_H'];
				$graph['result_type'] = $sensor['result_type'];
			}
			else
			{
				$graph['units'] = $sensor['units'];
				$graph['result_type'] = $sensor['result_type'];
			}
			
			$result .= '<script type="text/javascript">$(document).ready(function () {var graph = '.json_encode($graph).';initGraph("'.$graph_plot_id.'", graph);})</script>';
			
			$result .= '<div class="graph-controls">';
			
			if (($sensor['type'] != 'speed') && ($sensor['type'] != 'altitude'))
			{
				$result .= '<div class="graph-controls-left"><b>'.$la['SENSOR'].':</b> '.$sensor['name'].'</div>';
			}
		
			$result .= '<div class="graph-controls-right">
					<span id="graph_label_'.$graph_plot_id.'"></span>
					
					<a href="#" onclick="graphPanLeft(\''.$graph_plot_id.'\');" title="'.$la['PAN_LEFT'].'">
						<img src="'.$gsValues['URL_ROOT'].'/theme/images/arrow-left.svg" width="10px" border="0"/>
					</a>
					
					<a href="#" onclick="graphPanRight(\''.$graph_plot_id.'\');" title="'.$la['PAN_RIGHT'].'">
						<img src="'.$gsValues['URL_ROOT'].'/theme/images/arrow-right.svg" width="10px" border="0"/>
					</a>
					  
					<a href="#" onclick="graphZoomIn(\''.$graph_plot_id.'\');" title="'.$la['ZOOM_IN'].'">
						<img src="'.$gsValues['URL_ROOT'].'/theme/images/plus.svg" width="10px" border="0"/>
					</a>
					
					<a href="#" onclick="graphZoomOut(\''.$graph_plot_id.'\');" title="'.$la['ZOOM_OUT'].'">
						<img src="'.$gsValues['URL_ROOT'].'/theme/images/minus.svg" width="10px" border="0"/>
					</a>
				</div>
			</div>
			<div id="graph_plot_'.$graph_plot_id.'" style="height: 150px; width:100%;"></div>';
		}
		
		return $result;
	}
	
	$zones_addr = array();
	$zones_addr_loaded = false;
	
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
	
	function reportsAddReportHeader($imei, $dtf = false, $dtt = false)
	{
		global $la, $user_id;
		
		$result = '<table>';
		
		if ($imei != "")
		{
			$result .= '<tr>
					<td><strong>'.$la['OBJECT'].':</strong></td>
					<td>'.getObjectName($imei).'</td>
					</tr>';
		}
		
		if (($dtf != false) && ($dtt != false))
		{
			$result .= '<tr>
					<td><strong>'.$la['PERIOD'].':</strong></td>
					<td>'.$dtf.' - '.$dtt.'</td>
					</tr>';
		}
		
		$result .= '</table><br/>';
		
		return $result;
	}
	
	//Code update Start by VETRIVEL.NR
	
	
	      
	function reportsGenerateoffline($data_items,$show_coordinates) //OFFLINE DONE BY VETRIVEL.N
	{
		global $_SESSION, $la, $user_id;
				
		$route = getoffline();

		if ((count($route) == 0) )
		{
			return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
		
		$result = '<table class="report" width="100%" >
					<tr align="center">';
		
		if (in_array("imei", $data_items))
		{
				$result .='<th>'.$la['IMEI'].'</th>';
		}
		
		if (in_array("object", $data_items))
		{
				$result .='<th>'.$la['OBJECT'].'</th>';
		}
		
		if (in_array("date", $data_items))
		{
				$result .='<th>'.$la['DATE'].'</th>';
		}
	
		if (in_array("GPS_DEVICE", $data_items))
		{
			$result .= '<th>'.$la['GPS_DEVICE'].'</th>';
		}
		
		
		if (in_array("sim_card_number", $data_items))
		{
			$result .= '<th>'.$la['SIM_CARD_NUMBER'].'</th>';
		}
		
		if (in_array("GROUP", $data_items))
		{
			$result .= '<th>'.$la['GROUP'].'</th>';
		}
		
		if (in_array("fueltype", $data_items))
		{
			$result .= '<th>'.$la['FUELTYPE'].'</th>';
		}
		
		if (in_array("fuel1", $data_items))
		{
			$result .= '<th>'.$la['FUEL1'].'</th>';
		}
		
		if (in_array("fuel2", $data_items))
		{
			$result .= '<th>'.$la['FUEL2'].'</th>';
		}
		
		
		if (in_array("duration", $data_items))
		{
				$result .='<th>'.$la['DURATION'].'</th>';
		}
		
		if (in_array("altitude", $data_items))
		{
				$result .='<th>'.$la['ALTITUDE'].'</th>';
		}
		
		if (in_array("angle", $data_items))
		{
				$result .='<th>'.$la['ANGLE'].'</th>';
		}
		
		if (in_array("speed", $data_items))
		{
				$result .='<th>'.$la['SPEED'].'</th>';
		}
		
		if (in_array("position", $data_items))
		{
				$result .='<th>'.$la['POSITION'].'</th>';
		}
		
		
		
		if (in_array("temp1", $data_items))
		{
			$result .= '<th>'.$la['TEMP1'].'</th>';
		}
		
		if (in_array("temp2", $data_items))
		{
			$result .= '<th>'.$la['TEMP2'].'</th>';
		}
					
				$result .='	</tr>';
					
		for ($i=0; $i<count($route); ++$i)
		{
			$result .= '<tr align="center">';
			
			if (in_array("imei", $data_items))
			{
					$result .= '<td>'.$route[$i][0].'</td>';
			}
			
			if (in_array("object", $data_items))
			{
					$result .= '<td>'.$route[$i][8].'</td>';
			}
			
			if (in_array("date", $data_items))
			{
				$result .= '<td>'.$route[$i][1].'</td>';
			}
			
			if (in_array("GPS_DEVICE", $data_items))
			{
				$result .= '<td>'.$route[$i][17].'</td>';
			}
			
			if (in_array("sim_card_number", $data_items))
			{
				$result .= '<td>'.$route[$i][16].'</td>';
			}
			
			if (in_array("GROUP", $data_items))
			{
				$result .= '<td>'.$route[$i][10].'</td>';
			}

			if (in_array("fueltype", $data_items))
			{
				$result .= '<td>'.$route[$i][11].'</td>';
			}

			if (in_array("fuel1", $data_items))
			{
				$result .= '<td>'.$route[$i][12].'</td>';
			}

			if (in_array("fuel2", $data_items))
			{
				$result .= '<td>'.$route[$i][13].'</td>';
			}
			
			if (in_array("duration", $data_items))
			{
				$result .= '<td>'.$route[$i][9].'</td>';				
			}
			
			if (in_array("altitude", $data_items))
			{
				$result .= '<td>'.$route[$i][4].'</td>';
			}
			
			if (in_array("angle", $data_items))
			{
				$result .= '<td>'.$route[$i][5].'</td>';
			}
			if (in_array("speed", $data_items))
			{
				$result .= '<td>'.$route[$i][6].'</td>';
			}
			
			if (in_array("position", $data_items))
			{
				$result .= '<td>'.reportsGetPossition($route[$i][2], $route[$i][3],$show_coordinates, "true", "false").'</td>';
			}
			
			

			if (in_array("temp1", $data_items))
			{
				$result .= '<td>'.$route[$i][14].'</td>';
			}

			if (in_array("temp2", $data_items))
			{
				$result .= '<td>'.$route[$i][15].'</td>';
			}
				$result .= '</tr>';
		} 
					
		$result .= '</table>';
		
		return $result;
	}
	
	function reportsGenerateofflinesensor($show_coordinates, $show_addresses, $zones_addresses) //OFFLINE DONE BY VETRIVEL.N
	{
		global $_SESSION, $la, $user_id;
				
		$route = getofflinesensor();
				
		if ((count($route) == 0) )
		{
			return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
		
		$result = '<table class="report" width="100%" >
					<tr align="center">
						<th>'.$la['OBJECT'].'</th>
						<th>'.$la['DATE'].'</th>
						<th>'.$la['DURATION'].'</th>
						<th>'.$la['FUELPERCENTAGE'].'</th>
						<th>'.$la['ALTITUDE'].'</th>
						<th>'.$la['ANGLE'].'</th>
						<th>'.$la['SPEED'].'</th>
						<th>'.$la['POSITION'].'</th>
						
					</tr>';
					
		for ($i=0; $i<count($route); ++$i)
		{
			$result .= '<tr align="center">
							<td>'.$route[$i][8].'</td>
							<td>'.$route[$i][1].'</td>
							<td>'.$route[$i][9].'</td>
							<td>'.$route[$i][10].'</td>
							<td>'.$route[$i][4].'</td>
							<td>'.$route[$i][5].'</td>
							<td>'.$route[$i][6].'</td>
							<td>'.reportsGetPossition($route[$i][2], $route[$i][3], $show_coordinates, $show_addresses, $zones_addresses).'</td>
							
						</tr>';
		} 
					
		$result .= '</table>';
		
		return $result;
	}
		
	function reportsGenerateTripWiseReport($imeis, $dtf, $dtt, $speed_limit, $stop_duration,$data_items) //Trip Wise Report
	{
		global $_SESSION, $la, $user_id;
		
		/*
		$result = 	'
<script type="text/javascript" src="http://track.playgps.co.in/js/jquery-2.1.4.min.js"></script>
		<script>
$(document).ready(function(){
    $(".toggler").click(function(e){
        e.preventDefault();
        $(this).toggleClass("expand");
        var className = "cat"+$(this).attr("data-prod-cat");
        var $current= $(this).closest("tr").next();
        while($current.hasClass(className)){
            if($(this).hasClass("expand")){
               $current.show();
               $current = $current.next().next();
            }
            else{
               $current.hide();
               $current = $current.next();
            }
           
        }

    });
        $(".pitcher").click(function(e){
        e.preventDefault();
        var className = "cat"+$(this).attr("data-prod-cat");
        $(this).closest("tr").next().toggle();
        });
});

</script>';
			*/
		$result = "";
		$result .= '<table class="report" width="100%" >
				<tr align="center">
					<th>'.$la['SINO'].'</th>';
		if (in_array("VEHICLENO", $data_items))
		{
				$result .= '<th>'.$la['VEHICLENO'].'</th>';
		}
		if (in_array("TRIPNAME", $data_items))
		{
				$result .= '<th>'.$la['TRIPNAME'].'</th>';
		}
		if (in_array("HOTSPOTNAME", $data_items))
		{
				$result .= '<th>'.$la['HOTSPOTNAME'].'</th>';
		}
		if (in_array("DATE", $data_items))
		{
				$result .= '<th>'.$la['DATE'].'</th>';
		}
		if (in_array("ROUTE_START", $data_items))
		{
				$result .= '<th>'.$la['ROUTE_START'].'</th>';
		}
		if (in_array("ROUTE_END", $data_items))
		{
				$result .= '<th>'.$la['ROUTE_END'].'</th>';
		}
		if (in_array("PROUTE_START", $data_items))
		{
				$result .= '<th>'.$la['PROUTE_START'].'</th>';
		}
	    if (in_array("PROUTE_END", $data_items))
		{
				$result .= '<th>'.$la['PROUTE_END'].'</th>';
		}
	    if (in_array("AROUTE_START", $data_items))
		{
				$result .= '<th>'.$la['AROUTE_START'].'</th>';
		}			
	    if (in_array("AROUTE_END", $data_items))
		{
				$result .= '<th>'.$la['AROUTE_END'].'</th>';
		}			
	     if (in_array("AVG_SPEED", $data_items))
		{
				$result .= '<th>'.$la['AVG_SPEED'].'</th>';
		}			
	     if (in_array("DELAY", $data_items))
		{
				$result .= '<th>'.$la['DELAY'].'</th>';
		}			
			if (in_array("TAKENKM", $data_items))
		{
				$result .= '<th>'.$la['TAKENKM'].'</th>';
		}										
			if (in_array("DURATION", $data_items))
		{
				$result .= '<th>'.$la['DURATION'].'</th>';
		}				
		if (in_array("OVERSPEED_COUNT", $data_items))
		{
				$result .= '<th>'.$la['OVERSPEED_COUNT'].'</th>';
		}					
		if (in_array("OVRTIMEPARKING", $data_items))
		{
				$result .= '<th>'.$la['OVRTIMEPARKING'].'</th>';
		}	
		if (in_array("VIEW_DETAIL", $data_items))
		{
				$result .= '<th>'.$la['VIEW_DETAIL'].'</th>';
		}				
			'</tr>';
				
		$imei='';
		for ($i=0; $i<count($imeis); ++$i)
		{
			if($imei=="")
			$imei ="'".$imeis[$i]."'";
			else
			$imei =$imei.",'".$imeis[$i]."'";
		}
					
			$data = getTRIPWISE($imei, $dtf, $dtt, $stop_duration, true,$speed_limit);
					
			if (count($data['trip']) == 0)
			{
				$result .= '<tr>
						<td align="center" colspan="18">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td>
						</tr>';
			}
			else
			{
			
		for ($i=0; $i<count($data['trip']); ++$i)
		{
				$result .= '<tr>
				
						<td align="center">'.$data['trip'][$i][0].'</td>';
				if (in_array("VEHICLENO", $data_items))
				{
					$result .= '<td align="center">'.$data['trip'][$i][1].'</td>';
				}
				if (in_array("TRIPNAME", $data_items))
				{
					$result .= '<td align="center">'.$data['trip'][$i][2].'</td>';
				}
				if (in_array("HOTSPOTNAME", $data_items))
				{
					$result .= '<td align="center">'.$data['trip'][$i][3].'</td>';
				}
				if (in_array("DATE", $data_items))
				{
					$result .= '<td align="center">'.$data['trip'][$i][5].'</td>';
				}
				if (in_array("ROUTE_START", $data_items))
				{
					$result .= '<td align="center">'.$data['trip'][$i][6].'</td>';
				}
				if (in_array("PROUTE_END", $data_items))
				{
					$result .= '<td align="center">'.$data['trip'][$i][7].'</td>';
				}
		   	  if (in_array("PROUTE_START", $data_items))
				{
					$result .= '<td align="center">'.$data['trip'][$i][8].'</td>';
				}
				if (in_array("PROUTE_END", $data_items))
				{
					$result .= '<td align="center">'.$data['trip'][$i][9].'</td>';
				}
				if (in_array("AROUTE_START", $data_items))
				{
					$result .= '<td align="center">'.$data['trip'][$i][10].'</td>';
				}
		          if (in_array("AROUTE_END", $data_items))
				{
					$result .= '<td align="center">'.$data['trip'][$i][11].'</td>';
				}
				if (in_array("AVG_SPEED", $data_items))
				{
					$result .= '<td align="center">'.$data['trip'][$i][12].'</td>';
				}
				if (in_array("DELAY", $data_items))
				{
					$result .= '<td align="center">'.$data['trip'][$i][13].'</td>';
				}
				if (in_array("TAKENKM", $data_items))
				{
					$result .= '<td align="center">'.$data['trip'][$i][14].'</td>';
				}
				if (in_array("DURATION", $data_items))
				{
					$result .= '<td align="center">'.$data['trip'][$i][15].'</td>';
				}
				if (in_array("OVERSPEED_COUNT", $data_items))
				{
					$result .= '<td align="center">'.$data['trip'][$i][16].'</td>';
				}
				if (in_array("VIEW_DETAIL", $data_items))
				{
					$result .= '<td align="center">'.$data['trip'][$i][17].'</td>';
				}
				
						
						
						if($data['trip'][$i][18]!=0)
						$result .= '<td align="center"><a href="#" class="pitcher" data-prod-cat="1"> + </a></td>';
						else
						$result .= '<td align="center">NA</td>';
						$result .= '</tr>';
				
				if($data['trip'][$i][18]!=0)
				{
					$arrzone=explode('^', $data['trip'][$i][19]);
					$result .= '<tr class="cat1" style="display:none" >
						<td width="100%"  align="center" colspan="18">
						<table border="1" width="100%" >
						<tr  style="    font-weight: bold;padding: 2px;border: 1px solid #000000;color: white;background-color: #5C5D5C;" ><td>'.$la['ZONE_NAME'].'</td><td>'.$la['TIME'].'</td><td>'.$la['SPEED'].'</td></tr>';
						for($iz=0;$iz<count($arrzone);$iz++)
						{
							if($arrzone[$iz]!=0 && $arrzone[$iz]!="0" && $arrzone[$iz]!="")
							{
								$arrzonedata=explode('~', $arrzone[$iz]);
								
								$result .= '<tr><td>'.$arrzonedata[1].' ( '.$arrzonedata[2].' ) '.'</td><td>'.$arrzonedata[3].'</td><td>'.$arrzonedata[4].'</td></tr>';	
							}
						}						
						$result .= '</table></td></tr>';
				}
				
		}
		
		}
		
		$result .= '</table>';
		
		return $result;
	}
	
		
	function reportsGenerateVOLVO($imei, $dtf, $dtt, $stop_duration, $show_addresses, $zones_addresses,$show_coordinates)
	{
		global $_SESSION, $la, $user_id;
		
		$result='';
		$drivername="";
		$driverphoneno="";
		$totalfilling0=0;
		$totallength0=0;
		$totalduration0=0 ;
		
		$totalfilling=0;
		$totalduration=0 ;
		$totalfilling1=0;
		$totalduration1=0 ;	
		
	
			
		$accuracy = getObjectAccuracy($imei);
		
		if($accuracy['fueltype']!="FMS")
		{
		$result .=  '<table class="report" width="100%" >
		<tr><td style="text-align: center;">'.$la['NOT_AVAILABLE'].'</td></tr></table>';
			return $result;
		}
		
		$fuel_sensors = getSensorFromType($imei, 'fuel');
	
		//Function can be update here to make fast
		 
		$route = getRouteTripConsumption($imei, $dtf, $dtt, $stop_duration, true,$accuracy);

		//$route = getRouteRawnew($imei, $accuracy, $dtf, $dtt);
		
		$retndata = getIFSDataFuel($imei, $accuracy, $fuel_sensors, $dtf, $dtt);
		$routefuel=array();
		if (count($retndata)> 0) // || ($fuel_sensors == false))
		{
			$routefuel['route']=$retndata[1];		
		}
		
		$ff = getRouteFuelFillingsnew($routefuel['route'], $accuracy, $fuel_sensors);
		$ft = getRouteFuelTheftsnew($routefuel['route'], $accuracy, $fuel_sensors);
		
		
		$fuel1l=getLtrforFMS($imei);	
			
		//$ff =array();
		//$ft =array();
		
		$result .= '<table  width="100%" border="1" ><tr><td>';	
			
		//echo  json_encode($ff);
		$data=$route['drives'];
		//$data = fngettripconsumption($route, $accuracy, $fuel_sensors,$ff, $dtf, $dtt,$imei);
		
		
		if(isset($data) && !empty ($data))
		{
			for ($j=0; $j<count($data); $j++)
			{
				if(isset($data[$j][0])  )
				{
					
					$totalfilling0=$totalfilling0+$data[$j][7];
					$totallength0=$totallength0+$data[$j][4];
					$totalduration0 .= strtotime($totalduration0 . $data[$j][3]);
								
					//$totalfilling0=$totalfilling0+$data[$j][0]["fuel"];
					//$totallength0=$totallength0+$data[$j][0]["mid"];
					//$totalduration0 .= strtotime($totalduration0 . $data[$j][0]["duration"]);
				}
			}
		}
		
		$totalfilling=$ff['total_filled'];
		$totalfilling1=$ft['total_stolen'];
				
			$driver = getObjectDriver($user_id, $imei, "");
			if ($driver['driver_name'] == ''){$driver['driver_name'] = $la['NA'];}
			if(isset($driver['driver_name']))
				$drivername=$driver['driver_name'];
			if(isset($driver['driver_phone']))
				$driverphoneno=$driver['driver_phone'];
		
				
		$result .=  '<table class="report" width="100%" ><tr align="center">	<th style="background-color: rgb(50, 65, 165); color: white; font-weight: bold; padding: 7px;" colspan="7">FUEL SUMMARY</th></tr>
		<tr>
		<td > Driver Name : '.$drivername.' </td>
		<td >  Driver Phone : '.$driverphoneno.'  </td>
		</tr>
		<tr>
		<td > Total Trip length : '.$totallength0.$_SESSION["unit_distance"].' </td>
		<td > Total Fuel Consumption : '.$totalfilling0.$_SESSION["unit_capacity"].' </td>
		<td > Total Fuel Filling : '.$totalfilling.' Ltrs</td>
		<td > Total Fuel Siphon : '.$totalfilling1.' Ltrs</td>
		</tr></table>';
		
		$result .= '<br/><hr/>';
		
		
		if (count($data) <= 0 || empty($data) )
		{
			//$result .=  '<table class="report" width="100%" ><tr align="center">	<th style="background-color: rgb(50, 65, 165); color: white; font-weight: bold; padding: 7px;" colspan="5">FUEL CONSUMPTION</th></tr>		<tr><td style="text-align: center;">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
			$result .=  '';
		}
		else {
			
		$fcr = getObjectFCR($imei);

		$result .= '<table class="report" width="100%" >
		<tr align="center">	<th style="background-color: rgb(50, 65, 165); color: white; font-weight: bold; padding: 7px;" colspan="8">FUEL CONSUMPTION</th></tr>
		<tr align="center"><th colspan="8"><hr></th></tr>
	
						<tr align="center">
						
						<th >'.$la['START'].'</th>
						<th >'.$la['END'].'</th>
						<th >'.$la['DURATION'].'</th>
						<th >'.$la['SMILEAGE'].'</th>
						<th >'.$la['EMILEAGE'].'</th>
						<th>'.$la['LENGTH'].'</th>
						<th>'.$la['FUEL_CONSUMPTION'].'</th>
						<th>'.$la['KMPL'].'</th>
					</tr>';
					
			//echo  json_encode($data);
			
		if(isset($data) && !empty ($data))
		{
			for ($j=0; $j<count($data); $j++)
			{
				if(isset($data[$j][0])  )
				{
					$data[$j][7]=round($data[$j][7],1);
					$result .= '<tr align="center">
									<td>'.convUserTimezone( $data[$j][1]).'</td>
									<td>'. convUserTimezone($data[$j][2]).'</td>
									<td>'.$data[$j][3].'</td>
									<td>'.$data[$j][9].'</td>
									<td>'.$data[$j][10].'</td>
									<td>'.$data[$j][4].' '.$_SESSION["unit_distance"].'</td>';
						
						
					$result .= '<td>'.$data[$j][7].' '.$_SESSION["unit_capacity"].'</td>';
					
					if($data[$j][7]!=0)
					{
						$result .= '<td>'. round(($data[$j][4]/$data[$j][7]),2).'</td>';
					}	
						
					$result .= '</tr>';

				}
			}
		}
		
			//<td colspan="2"> Total Duration : '.$totalduration.'</td>
			
			$result .= '<tr align="center">
			<td colspan="4"> Total Trip length : '.$totallength0.$_SESSION["unit_distance"].' </td>
			<td colspan="4"> Total Fuel Consumption : '.$totalfilling0.$_SESSION["unit_capacity"].' </td> </tr>';
		$result .= '</table><br/>';
		}
		$result .= '<br/><hr/>';
		$result .= '<br/><br/>';
		
	
		
		if ((count($route) == 0) || (count($ff) == 0) || empty($data)  || empty($ff) )
		{
			//$result .=  '<table class="report" width="100%" ><tr align="center">	<th style="background-color: rgb(50, 65, 165); color: white; font-weight: bold; padding: 7px;" colspan="7">FUEL FILLING</th></tr>		<tr><td style="text-align: center;">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
			$result .=  '';
		}
		else
			{
		$result .= '<table class="report" width="100%" >
		<tr align="center">	<th style="background-color: rgb(50, 65, 165); color: white; font-weight: bold; padding: 7px;" colspan="8">FUEL FILLING</th></tr>
		<tr align="center"><th colspan="8"><hr></th></tr>
					<tr align="center">
						<th>'.$la['FROMDATE'].'</th>
						<th>'.$la['TODATE'].'</th>
						<th>'.$la['DURATION'].'</th>
						<th>'.$la['POSITION'].'</th>
						<th>'.$la['BEFORE'].' Ltrs</th>
						<th>'.$la['AFTER'].' Ltrs</th>
						<th>'.$la['FILLED'].'</th>
						<th>'.$la['MILEAGE'].'</th>
					</tr>';

		for ($j=0; $j<count($fuel_sensors); ++$j)
		{
			$sensor = $fuel_sensors[$j]['name'];
			if(isset($ft['fillings'][$sensor]))
			{
				for ($i=0; $i<count($ff['fillings'][$sensor]); ++$i)
				{
					$lat = $ff['fillings'][$sensor][$i]["lat"];
					$lng = $ff['fillings'][$sensor][$i]["lng"];

					$params = $ff['fillings'][$sensor][$i][7];
					$driver = getObjectDriver($user_id, $imei, $params);
					if ($driver['driver_name'] == ''){$driver['driver_name'] = $la['NA'];}
						
					$result .= '<tr align="center">
							<td>'.($ff['fillings'][$sensor][$i]["start"]).'</td>
							<td>'.($ff['fillings'][$sensor][$i]["end"]).'</td>
							<td>'.$ff['fillings'][$sensor][$i]["start"].'</td>
							<td>'.reportsGetPossition($lat, $lng,$show_coordinates, $show_addresses, $zones_addresses).'</td>
							<td>'.$ff['fillings'][$sensor][$i]["before"].'</td>
							<td>'.$ff['fillings'][$sensor][$i]["after"].'</td>
							<td>'.$ff['fillings'][$sensor][$i]["filled"].'</td>
							<td>'.$ff['fillings'][$sensor][$i]["mileage_end"].'</td>
						</tr>';
				}
			}
		}
		
		//Total Duration : '.$totalduration.'
		$result .= '<tr align="center"><td colspan="4"> </td><td colspan="4"> Total Fuel Filling : '.$totalfilling.' Ltrs </td> </tr>';
		$result .= '</table><br/>';
		}
		$result .= '<br/><hr/>';
		$result .= '<br/><br/>';
		
	
		
		if ((count($route) == 0) || (count($ft) == 0))
		{
			//$result .=  '<table class="report" width="100%" ><tr align="center">	<th style="background-color: rgb(50, 65, 165); color: white; font-weight: bold; padding: 7px;" colspan="7">FUEL Siphon</th></tr>		<tr><td style="text-align: center;">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
			$result .=  '';
		}
		else
		{
		$result .= '<table class="report" width="100%" >
		<tr align="center">	<th style="background-color: rgb(50, 65, 165); color: white; font-weight: bold; padding: 7px;" colspan="8">FUEL SIPHON </th></tr>
		<tr align="center"><th colspan="8"><hr></th></tr>
					<tr align="center">
						<th>'.$la['FROMDATE'].'</th>
						<th>'.$la['TODATE'].'</th>
						<th>'.$la['DURATION'].'</th>
						<th>'.$la['POSITION'].'</th>
						<th>'.$la['BEFORE'].' Ltrs</th>
						<th>'.$la['AFTER'].' Ltrs</th>
						<th>'.$la['STOLEN'].'</th>
						<th>'.$la['MILEAGE'].'</th>
					</tr>';

		
		for ($j=0; $j<count($fuel_sensors); ++$j)
		{
			$sensor = $fuel_sensors[$j]['name'];
			if(isset($ft['thefts'][$sensor]))
			{
				for ($i=0; $i<count($ft['thefts'][$sensor]); ++$i)
				{
					$lat = $ft['thefts'][$sensor][$i]["lat"];
					$lng = $ft['thefts'][$sensor][$i]["lng"];

					$params = $ft['fillings'][$sensor][$i][7];
					$driver = getObjectDriver($user_id, $imei, $params);
					if ($driver['driver_name'] == ''){$driver['driver_name'] = $la['NA'];}
						
					$result .= '<tr align="center">
							<td>'.($ft['thefts'][$sensor][$i]["start"]).'</td>
							<td>'.($ft['thefts'][$sensor][$i]["end"]).'</td>
							<td>'.$ft['thefts'][$sensor][$i]["start"].'</td>
							<td>'.reportsGetPossition($lat, $lng,$show_coordinates, $show_addresses, $zones_addresses).'</td>
							<td>'.$ft['thefts'][$sensor][$i]["before"].'</td>
							<td>'.$ft['thefts'][$sensor][$i]["after"].'</td>
							<td>'.$ft['thefts'][$sensor][$i]["siphon"].'</td>
							<td>'.$ft['thefts'][$sensor][$i]["mileage_end"].'</td>
						</tr>';
				}
			}
			
		}
		
		//here for col span 
		//Total Duration : '.$totalduration.'
		$result .= '<tr align="center"><td colspan="4"> </td><td colspan="4"> Total Fuel Siphon : '.$totalfilling1.' Ltrs</td> </tr>';
		
		$result .= '</table><br/>';
	}


	
		$result .= '</table ></tr></td>';
		
		return $result;
				
	}
	
	function reportsGeneratebooking($imei,$dtf, $dtt, $data_items,$show_coordinates) //BOOKING REPORT
	{
		global $_SESSION, $la, $user_id;
				
		$route = getbookingreport($imei,$dtf,$dtt);
		
		if ((count($route) == 0) )
		{
			return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
	
		
		
		$result = '<table class="report" width="100%" >
					<tr align="center">';
					
		// if (in_array("control_room", $data_items))
		// {
		//		$result .='<th>'.$la['CONTROL_ROOM'].'</th>';
		// }
		
		if (in_array("create_date", $data_items))
		{
				$result .='<th>'.$la['CREATE_DATE'].'</th>';
		}
		
		if (in_array("booking_status", $data_items))
		{
				$result .='<th>'.$la['BOOKING_STATUS'].'</th>';
		}
		if (in_array("app_user_id", $data_items))
		{
				$result .='<th>'.$la['APP_USERID'].'</th>';
		}
		if (in_array("self_others", $data_items))
		{
				$result .='<th>'.$la['SELF_OTHERS'].'</th>';
		}
			
		if (in_array("book_by", $data_items))
		{
				$result .='<th>'.$la['BOOK_BY'].'</th>';
		}
		
		if (in_array("emergency_address", $data_items))
		{
				$result .='<th>'.$la['EMERGENCY_ADDRESS'].'</th>';
		}
		
		if (in_array("contact_no", $data_items))
		{
				$result .='<th>'.$la['CONTACT_NO'].'</th>';
		}
		if (in_array("emergency_reason", $data_items))
		{
				$result .='<th>'.$la['EMERGENCY_REASON'].'</th>';
		}
		
		if (in_array("people_count", $data_items))
		{
				$result .='<th>'.$la['PEOPLE_COUNT'].'</th>';
		}
		
		if (in_array("age", $data_items))
		{
				$result .='<th>'.$la['AGE'].'</th>';
		}
		
		if (in_array("conscious", $data_items))
		{
				$result .='<th>'.$la['CONSCIOUS'].'</th>';
		}
		if (in_array("breathing", $data_items))
		{
				$result .='<th>'.$la['BREATHING'].'</th>';
		}
		
		if (in_array("gender", $data_items))
		{
				$result .='<th>'.$la['GENDER'].'</th>';
		}
		
		if (in_array("person_name", $data_items))
		{
				$result .='<th>'.$la['PERSON_NAME'].'</th>';
		}
		
		if (in_array("note1", $data_items))
		{
				$result .='<th>'.$la['NOTE1'].'</th>';
		}
				
		if (in_array("allocated_driver_id", $data_items))
		{
				$result .='<th>'.$la['ALLOCATED_DRIVER_ID'].'</th>';
		}
		
		if (in_array("allocated_driver_phone", $data_items))
		{
				$result .='<th>'.$la['ALLOCATED_DRIVER_PHONE'].'</th>';
		}
		
		if (in_array("allocated_vehicleno", $data_items))
		{
				$result .='<th>'.$la['ALLOCATED_VEHICLE_NO'].'</th>';
		}
		
	//if (in_array("allocated_imei", $data_items))
	//	{
	//			$result .='<th>'.$la['ALLOCATED_IMEI'].'</th>';
	//	}
		if (in_array("allocated_time", $data_items))
		{
				$result .='<th>'.$la['ALLOCATED_TIME'].'</th>';
		}
		
		if (in_array("driver_accept_time", $data_items))
		{
				$result .='<th>'.$la['DRIVER_ACCEPT_TIME'].'</th>';
		}
		
		if (in_array("vehicle_reached_time", $data_items))
		{
				$result .='<th>'.$la['VEHICLE_REACHED_TIME'].'</th>';
		}
		
		if (in_array("pickedup_time", $data_items))
		{
				$result .='<th>'.$la['PICKEDUP_TIME'].'</th>';
		}
		if (in_array("reached_dest_time", $data_items))
		{
				$result .='<th>'.$la['REACHED_DEST_TIME'].'</th>';
		}
						
		$result .='	</tr>';

	
		
		for ($i=0; $i<count($route); ++$i)
		{
			 
			$result .= '<tr align="center">';

			// if (in_array("control_room", $data_items))
			// {
			// $result .= '<td>'.$route[$i][0].'</td>';
			// }
			 
			if (in_array("create_date", $data_items))
			{
				$result .= '<td>'.$route[$i]["create_date"].'</td>';
			}
			 
			if (in_array("booking_status", $data_items))
			{
				$result .= '<td>'.$route[$i]["booking_status"].'</td>';
			}
			if (in_array("app_user_id", $data_items))
			{
				$result .= '<td>'.$route[$i]["app_user_id"].'</td>';
			}
			if (in_array("self_others", $data_items))
			{
				$result .= '<td>'.$route[$i]["self_others"].'</td>';
			}
			 
			if (in_array("book_by", $data_items))
			{
				$result .= '<td>'.$route[$i]["book_by"].'</td>';
			}
			if (in_array("emergency_address", $data_items))
			{
				$result .= '<td>'.$route[$i]["emergency_address"].'</td>';
			}
			 
			if (in_array("contact_no", $data_items))
			{
				$result .= '<td>'.$route[$i]["contact_no"].'</td>';
			}
			 
			if (in_array("emergency_reason", $data_items))
			{
				$result .= '<td>'.$route[$i]["emergency_reason"].'</td>';
			}
			 
			if (in_array("people_count", $data_items))
			{
				$result .= '<td>'.$route[$i]["people_count"].'</td>';
			}
			 
			if (in_array("age", $data_items))
			{
				$result .= '<td>'.$route[$i]["age"].'</td>';
			}
			 
			if (in_array("conscious", $data_items))
			{
				$result .= '<td>'.$route[$i]["conscious"].'</td>';
			}
			 
			if (in_array("breathing", $data_items))
			{
				$result .= '<td>'.$route[$i]["breathing"].'</td>';
			}
			if (in_array("gender", $data_items))
			{
				$result .= '<td>'.$route[$i]["gender"].'</td>';
			}
			if (in_array("person_name", $data_items))
			{
				$result .= '<td>'.$route[$i]["person_name"].'</td>';
			}
			 
			if (in_array("note1", $data_items))
			{
				$result .= '<td>'.$route[$i]["note1"].'</td>';
			}
			 
			if (in_array("allocated_driver_id", $data_items))
			{
				$result .= '<td>'.$route[$i]["allocated_driver_id"].'</td>';
			}
			 
			if (in_array("allocated_driver_phone", $data_items))
			{
				$result .= '<td>'.$route[$i]["allocated_driver_phone"].'</td>';
			}
			 
			if (in_array("allocated_vehicleno", $data_items))
			{
				$result .= '<td>'.$route[$i]["allocated_vehicleno"].'</td>';
			}
			 
			//if (in_array("allocated_imei", $data_items))
			//{
			// $result .= '<td>'.$route[$i][19].'</td>';
			//}

			if (in_array("allocated_time", $data_items))
			{
				$result .= '<td>'.$route[$i]["allocated_time"].'</td>';
			}
			 
			if (in_array("driver_accept_time", $data_items))
			{
				$result .= '<td>'.$route[$i]["driver_accept_time"].'</td>';
			}
			 
			if (in_array("vehicle_reached_time", $data_items))
			{
				$result .= '<td>'.$route[$i]["vehicle_reached_time"].'</td>';
			}
			 
			if (in_array("pickedup_time", $data_items))
			{
				$result .= '<td>'.$route[$i]["pickedup_time"].'</td>';
			}
			if (in_array("reached_dest_time", $data_items))
			{
				$result .= '<td>'.$route[$i]["reached_dest_time"].'</td>';
			}

			$result .= '</tr>';
		}

		$result .= '</table>';
		return $result;
		
	}
	
	function reportsGenerateTempReport($imei, $dtf, $dtt,$itv,$stop_duration,$show_addresses, $zones_addresses,$data_items,$show_coordinates) 
	{
		global $_SESSION, $gsValues, $la, $user_id;
        
        $result = '';		
		//$imei ='6120317585';
		$accuracy = getObjectAccuracy($imei);
		$fuel_sensors = getSensorFromType($imei, 'fuel');
		
		$route = getIFSDataTempReport($imei, $accuracy, $fuel_sensors, $dtf, $dtt,$stop_duration);
        
        $sensor = $fuel_sensors[0];
        
        $boxVolume = getBoxVolume($sensor);
        
				if ((count($route) == 0)) // || ($fuel_sensors == false))
		{
			return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
    	
		$result = '<table class="report" width="100%" >
					<tr align="center">';
				if (in_array("TIME", $data_items))
		{
				$result .= '<th>'.$la['TIME'].'</th>';
		}
						
				if (in_array("POSITION", $data_items))
		{
				$result .= '<th>'.$la['POSITION'].'</th>';
		}	
				if (in_array("ALTITUDE", $data_items))
		{
				$result .= '<th>'.$la['ALTITUDE'].'</th>';
				
		}
				if (in_array("SPEED", $data_items))
		{
				$result .= '<th>'.$la['SPEED'].'</th>';	
		}
				if (in_array("IGNITION", $data_items))
		{
				$result .= '<th>'.$la['IGNITION'].'</th>';	
		}
				if (in_array("TEMP1", $data_items))
		{
				$result .= '<th>'.$la['TMP1'].'</th>';	
		}	
						
				if (in_array("TEMP2", $data_items))
		{
				$result .= '<th>'.$la['TMP2'].'</th>';	
		}			
				if (in_array("TEMP3", $data_items))
		{
				$result .= '<th>'.$la['TMP3'].'</th>';	
		}				
						
		'</tr>';
					
					
		for ($i=0; $i<count($route); ++$i)
		{
			
			$result .= '<tr align="center">';
					if (in_array("TIME", $data_items))
		{
				$result .= '<td>'.$route[$i]["date"].'</td>';
		}
					if (in_array("POSITION", $data_items))
		{
				$result .= '<td>'.reportsGetPossition($route[$i]["lat"], $route[$i]["lng"],$show_coordinates, $show_addresses, $zones_addresses).'</td>';
		}
		
					if (in_array("ALTITUDE", $data_items))
		{
				$result .= '<td>'.$route[$i]["altitude"].'</td>';
		}
					if (in_array("SPEED", $data_items))
		{
				$result .= '<td>'.$route[$i]["speed"].'</td>';
		}

				if (in_array("IGNITION", $data_items))
		{
				$result .= '<td>'.$route[$i]["acc"].'</td>';
		}		
		
					if (in_array("TEMP1", $data_items))
		{
				$result .= '<td>'.$route[$i]["temp1"].'</td>';
		}		
					if (in_array("TEMP2", $data_items))
		{
				$result .= '<td>'.$route[$i]["temp2"].'</td>';
				}
					if (in_array("TEMP3", $data_items))
		{
				$result .= '<td>'.$route[$i]["temp3"].'</td>';
				}	
							
						'</tr>';
		}
		
		$result .= '</table>';
		
	      
		return $result;
	}    
 
	
	function reportsGeneraterfidtrip($imei, $dtf, $dtt,$stop_duration, $show_addresses, $zones_addresses,$data_items,$show_coordinates) //RFID Report
    {
        global $_SESSION, $la, $user_id;
        
        $result = '';
        
       
        //code update by vetrivel
        $route = rfidtripreport($imei, $dtf, $dtt);
               
        if (count($route) == 0)
        {
            return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
        }
        
        $result = '<table class="report" width="100%" >
                    <tr align="center">';
                    if (in_array("VNAME", $data_items))
		{
				$result .='<th>'.$la['VNAME'].'</th>';
		}
      				if (in_array("VEHICLENO", $data_items))
		{
				$result .='<th>'.$la['VEHICLENO'].'</th>';
		}
    				if (in_array("VENDOR", $data_items))
		{
				$result .='<th>'.$la['VENDOR'].'</th>';
		}
   					 if (in_array("DRIVER", $data_items))
		{
				$result .='<th>'.$la['DRIVER'].'</th>';
		}     
                     if (in_array("DRIVERPH", $data_items))
		{
				$result .='<th>'.$la['DRIVERPH'].'</th>';
		}       
               		  if (in_array("DRIVERRFIDID", $data_items))
		{
				$result .='<th>'.$la['DRIVERRFIDID'].'</th>';
		}                   
               		 if (in_array("STARTTIME", $data_items))
		{
				$result .='<th>'.$la['STARTTIME'].'</th>';
		}                   
                     if (in_array("ENDTIME", $data_items))
		{
				$result .='<th>'.$la['ENDTIME'].'</th>';
		}               
                  	 if (in_array("MILEAGE", $data_items))
		{
				$result .='<th>'.$la['MILEAGE'].'</th>';
		}                     
                      
                      
                    '</tr>';
                    
                    
     
                
        for ($i=0; $i<count($route); ++$i)
        {
           
            $routemi= detailrfidtripreport($imei,($route[$i][6]),($route[$i][7]),$route[$i][5]);

                 $mil=0;
         
           for ($imi=0; $imi<count($routemi); ++$imi)
           {
                 $mil=$mil+$routemi[$imi]["mileage"];
           }
          
           		 if ($route[$i][7] == ''){$route[$i][7] = $la['NA'];}
            
            $result .= '<tr align="center">';
            	if (in_array("VNAME", $data_items))
		{
				$result .='<td>'.$route[$i][0].'</td>';
		}
				if (in_array("VEHICLENO", $data_items))
		{
				$result .='<td>'.$route[$i][1].'</td>';
		}
            	if (in_array("VENDOR", $data_items))
		{
				$result .='<td>'.$route[$i][2].'</td>';
				
		}
         		if (in_array("DRIVER", $data_items))
		{
				$result .='<td>'.$route[$i][3].'</td>';
		}          
		        
             	if (in_array("DRIVERPH", $data_items))
		{
				$result .='<td>'.$route[$i][4].'</td>';
		}                         
           		if (in_array("DRIVERRFIDID", $data_items))
		{
				$result .='<td>'.$route[$i][5].'</td>';
		}                         
           		if (in_array("STARTTIME", $data_items))
		{
				$result .='<td>'.$route[$i][6].'</td>';
		}                  
             	if (in_array("ENDTIME", $data_items))
		{
				$result .='<td>'.$route[$i][7].'</td>';
		}                                
          	 	if (in_array("MILEAGE", $data_items))
		{
				$result .='<td>'.$mil.'</td>';
		}                               
                         
                        '</tr>';
        }
        
        $result .= '</table>';
        
        return $result;
    }
    
    function reportsGeneratedetailrfidtrip($imei, $dtf, $dtt, $show_addresses, $zones_addresses,$show_coordinates) //detail RFID Report
    {
        global $_SESSION, $la, $user_id;
        
        $result = '';

        $r1 = rfidtripreport($imei, $dtf, $dtt);
        
                    
        
               
        if (count($r1) == 0)
        {
            return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
        }
        
       
                    
        for ($i1=0; $i1<count($r1); $i1++)
        {
            
        
            $var1=$r1[$i1];
            
            $result .= '<table  width="100%" border="1" ><tr><td>';     

            $route= detailrfidtripreport($imei,($var1[6]),($var1[7]),$var1[5]);
          
                         
              
            if (count($route) == 0)
            {
               $result .= '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';  
            }
         
         $mil=0;
         
           for ($i=0; $i<count($route); ++$i)
           {
                  $result .=  '<table class="report" width="100%" ><tr align="center">  
                               <th style="background-color: rgb(50, 65, 165); color: white; font-weight: bold; padding: 7px;" colspan="7">
                               RFID Sub Trip '.($i+1).' & Employee Details</th></tr>';
        
                 $result .= '<tr align="center"><td colspan=2>Trip Start Details</td><td colspan=2>Trip End Details</td><td>Mileage</td></tr>
                            <tr align="center">
                            <td> '. convUserTimezone( $route[$i]["starttime"]).'</td>
                            <td> ' .reportsGetPossition($route[$i]["lat1"], $route[$i]["lng1"],$show_coordinates, $show_addresses, $zones_addresses).'</td>
                            <td> '.convUserTimezone($route[$i]["endtime"]).'</td>
                            <td> ' .reportsGetPossition($route[$i]["lat2"], $route[$i]["lng2"],$show_coordinates, $show_addresses, $zones_addresses).'</td>
                            <td>'.$route[$i]["mileage"].'</td></tr>';
                          
                            $result .= ' <tr align="center"><td  colspan=5>';
                            $result .= '<br/>';
                            $result .= ' </td></tr>';
                            
                            $result .= ' <tr align="center"><td  colspan=5>';
                 
                 $mil=$mil+$route[$i]["mileage"];
               
                
                 
                    $emp= detailrfidtripemployee($imei,$route[$i]["starttime"],$route[$i]["endtime"],$route[$i]["driverrfid"]);             
                            
                       
            if (count($emp) == 0)
            {
               $result .= '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';  
            }
            else 
            {
	               $result .=  '<table class="report" width="100%" ><tr align="center"> '; 
           
            $result .= '<tr align="center"><td>Employee Name </td> <td > Start Time</td><td > Start Position</td><td > End Time</td><td > End Position </td></tr>';
            }
         
           
           for ($iv=0; $iv<count($emp); ++$iv)
           {
                        
            
                 $result .= '<tr align="center">
                             <td> '. ( $emp[$iv]["name"]).'</td>
                            <td> '. ( $emp[$iv]["starttime"]).'</td>
                            <td> ' .reportsGetPossition($emp[$iv]["lat1"], $emp[$iv]["lng1"],$show_coordinates, $show_addresses, $zones_addresses).'</td>
                            <td> '.($emp[$iv]["endtime"]).'</td>
                            <td> ' .reportsGetPossition($emp[$iv]["lat2"], $emp[$iv]["lng2"],$show_coordinates, $show_addresses, $zones_addresses).'</td>
                          </tr>';
                          
                            $result .= ' <tr align="center"><td  colspan=5>';
                   
                    
                            
                            $result .= ' </td></tr>';
                          
                
            } 
                        $result .= '</table>';
                            
                            $result .= ' </td></tr>';
                            $result .= '</table>';
                
            } 


 
               $result .=  '<table class="report" width="100%" ><tr align="center">  
                         <th style="background-color: rgb(50, 65, 165); color: white; font-weight: bold; padding: 7px;" colspan="7">
                          RFID Trip '.($i1+1).' Summary </th></tr>';  
                          
                             $result .= '
                            <tr align="center">
                            <td>Object : ' .$var1[0]. '</td>
                            <td>Vehicle No : ' .$var1[1]. '</td>
                            <td>Vendor : ' .$var1[2]. '</td>
                            <td>Driver Name : ' .$var1[3]. '</td>
                            <td>Driver Phone : ' .$var1[4]. '</td>
                            </tr>';
                                $result .= '
                            <tr align="center">
                            <td>Driver RFID : ' .$var1[5]. '</td>
                            <td>Start Time : ' .$var1[6]. '</td>
                            <td>End Time : ' .$var1[7]. '</td>
                            <td>Mileage : '.($mil).' </td>
                            <td></td>
                            </tr>';
                            
                            $result .= ' <tr align="center"><td colspan=5>';
                        
            
               $result .= ' </td></tr>';
               $result .= '</table>';
            
             $result .= '</table>';
        
      
             $result .= '</td></tr></table>';
             
             
            $result .= '<br/><br/>';
            
            $result .= '<br/><br/>';
             
             
        }
        
          
        
        return $result;
    }

    function reportsDriverBehaviorGraph($imei, $dtf, $dtt,$i){
    	global $_SESSION, $gsValues, $la, $user_id;
        $result = '';
        $driverbeha=getDriverBehavior($imei,$dtf, $dtt);
        if($driverbeha=='NO DATA'){
        	return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
        }
        $result='<style>
				#chartdiv'.$i.' {
				  width: 100%;
				  height: 500px;
				}

				</style>

				<!-- Resources -->
				<script src="https://www.amcharts.com/lib/4/core.js"></script>
				<script src="https://www.amcharts.com/lib/4/charts.js"></script>
				<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>

				<!-- Chart code -->
				<script>
				am4core.ready(function() {

				// Create chart instance
				var chart = am4core.create("chartdiv'.$i.'", am4charts.XYChart);';

				// Add data
			$result.='chart.data = '.json_encode($driverbeha).';';

				// Create axes
			$result.='var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
					categoryAxis.dataFields.category = "date";
					categoryAxis.numberFormatter.numberFormat = "#";
					categoryAxis.renderer.inversed = true;
					categoryAxis.renderer.grid.template.location = 0;
					categoryAxis.renderer.cellStartLocation = 0.1;
					categoryAxis.renderer.cellEndLocation = 0.9;

					var  valueAxis = chart.xAxes.push(new am4charts.ValueAxis()); 
					valueAxis.renderer.opposite = true;

					// Create series
					function createSeries(field, name) {
						var series = chart.series.push(new am4charts.ColumnSeries());
						series.dataFields.valueX = field;
						series.dataFields.categoryY = "date";
						series.name = name;
						series.columns.template.tooltipText = "{name}:[bold]{valueX}[/]";
						series.columns.template.height = am4core.percent(100);
						series.sequencedInterpolation = true;

						var valueLabel = series.bullets.push(new am4charts.LabelBullet());
						valueLabel.label.text = "{valueX}";
						valueLabel.label.horizontalCenter = "left";
						valueLabel.label.dx = 10;
						valueLabel.label.hideOversized = false;
						valueLabel.label.truncate = false;

						var categoryLabel = series.bullets.push(new am4charts.LabelBullet());
						categoryLabel.label.text = "{name}";
						categoryLabel.label.horizontalCenter = "right";
						categoryLabel.label.dx = -10;
						categoryLabel.label.fill = am4core.color("#fff");
						categoryLabel.label.hideOversized = false;
						categoryLabel.label.truncate = false;
					}

					createSeries("mileage", "Mileage");
					createSeries("overspeed", "Over Speed");					
					// createSeries("fueldisconnect", "Fuel Wire Disconnect");
					// createSeries("breaking", "Harsh Breaking");
					// createSeries("acceleration", "Harsh Acceleration");

				}); // end am4core.ready()
				</script>';
        $result.='<div id="chartdiv'.$i.'"></div>';

        return $result;
    }
  
    	
	function reportsGenerateFuelGraph($imei, $dtf, $dtt,$itv,$stop_duration, $show_addresses, $zones_addresses,$show_coordinates) 
	{
		global $_SESSION, $gsValues, $la, $user_id;
        
        $result = '';		
		//$imei ='6120317585';
		$accuracy = getObjectAccuracy($imei);
		
		if($accuracy["fueltype"]=="No Sensor")
		{
			return '<table><tr><td>'.$la['SENSOR_NOT_ADD'].'</td></tr></table>';
		}

		$fuel_sensors = getSensorFromType($imei, 'fuel');

		
		if(!$fuel_sensors)
		{
			return '<table><tr><td>'.$la['SENSOR_NOT_ADD'].'</td></tr></table>';
		}
		
		
		$retndata = getIFSDataFuel($imei, $accuracy, $fuel_sensors, $dtf, $dtt);

		$boxVolume1=0;$boxVolume2=0;$boxVolume3=0;$boxVolume4=0;

		for($isv=0;$isv<count($fuel_sensors);$isv++)
		{
			if(isset($fuel_sensors[$isv]) && $fuel_sensors[$isv]["param"]=="fuel1")
			{
				$boxVolume1 = getBoxVolume($fuel_sensors[$isv]);
			}
			if(isset($fuel_sensors[$isv]) && $fuel_sensors[$isv]["param"]=="fuel2")
			{
				$boxVolume2 = getBoxVolume($fuel_sensors[$isv]);
			}
			if(isset($fuel_sensors[$isv]) && $fuel_sensors[$isv]["param"]=="fuel3")
			{
				$boxVolume3 = getBoxVolume($fuel_sensors[$isv]);
			}
			if(isset($fuel_sensors[$isv]) && $fuel_sensors[$isv]["param"]=="fuel4")
			{
				$boxVolume4 = getBoxVolume($fuel_sensors[$isv]);
			}

		}

		$route=array();
		$route1=array();

		if (count($retndata)> 0) // || ($fuel_sensors == false))
		{
	  		$route= $retndata[0];
			$route1=$retndata[1];		
		}
		else
		{
			return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
	
		if ((count($route) == 0)) // || ($fuel_sensors == false))
		{
			return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
		else 
		{
        
			$result .= 	'<script type="text/javascript">
            			$(document).ready(function () {
                            chartData'.$itv.' = '.json_encode($route).';
                           chartData'.$itv.'=chartOPTIMIZE(chartData'.$itv.');
            			})
	              </script>';
        
        $result .= 	'<script type="text/javascript">
                   var chart'.$itv.';
                   var cd'.$itv.';
                   

        
                   AmCharts.ready(function () {
        
                       // SERIAL CHART
                       chart'.$itv.' = new AmCharts.AmSerialChart();
                       //chart.baseHref = true;
                       chart'.$itv.'.pathToImages = "'.$gsValues['URL_ROOT'].'/js/amcharts/images/";
                       chart'.$itv.'.dataProvider = chartData'.$itv.';
                       chart'.$itv.'.categoryField = "date";
                       chart'.$itv.'.dataDateFormat = "YYYY-MM-DD JJ:NN:SS"; // ENTER YOUR PREFFERED DATE FORMAT TO DISPLAY CHART CORRECTLY
                       // listen for "dataUpdated" event (fired when chart is inited) and call zoomChart method when it happens
                       chart'.$itv.'.addListener("dataUpdated", zoomChart);
        
                       // AXES
                       // category
                       var categoryAxis'.$itv.' = chart'.$itv.'.categoryAxis;
                       categoryAxis'.$itv.'.parseDates = true; // as our data is date-based, we set parseDates to true
                       categoryAxis'.$itv.'.minPeriod = "ss"; // our data is daily, so we set minPeriod to DD
                       categoryAxis'.$itv.'.minorGridEnabled = true;
                       categoryAxis'.$itv.'.axisColor = "#DADADA";
                       categoryAxis'.$itv.'.twoLineMode = true;
                       categoryAxis'.$itv.'.dateFormats = [{
                            period: "fff",
                            format: "JJ:NN:SS"
                        }, {
                            period: "ss",
                            format: "JJ:NN:SS"
                        }, {
                            period: "mm",
                            format: "JJ:NN"
                        }, {
                            period: "hh",
                            format: "JJ:NN"
                        }, {
                            period: "DD",
                            format: "DD"
                        }, {
                            period: "WW",
                            format: "DD"
                        }, {
                            period: "MM",
                            format: "MMM"
                        }, {
                            period: "YYYY",
                            format: "YYYY"
                        }];
        
                       // first value axis (on the left)
                       var valueAxis1'.$itv.' = new AmCharts.ValueAxis();
                       valueAxis1'.$itv.'.axisColor = "#FF6600";
                       valueAxis1'.$itv.'.axisThickness = 2;
                       valueAxis1'.$itv.'.gridAlpha = 0;
                       valueAxis1'.$itv.'.minimum = 0;
                       valueAxis1'.$itv.'.maximum = '.$boxVolume1.';
                       //valueAxis1.unit = "Ltrs";
                        valueAxis1'.$itv.'.autoGridCount = false;
                        valueAxis1'.$itv.'.gridCount = 50;
                        valueAxis1'.$itv.'.labelFrequency = 2;                       
                       chart'.$itv.'.addValueAxis(valueAxis1'.$itv.');
                       
                        var valueAxis6'.$itv.' = new AmCharts.ValueAxis();
                       valueAxis6'.$itv.'.axisColor = "#FF6600";
                       valueAxis6'.$itv.'.axisThickness = 2;
                       valueAxis6'.$itv.'.gridAlpha = 0;
                       valueAxis6'.$itv.'.minimum = 0;
                       valueAxis6'.$itv.'.maximum = '.$boxVolume2.';
                       //valueAxis1.unit = "Ltrs";
                        valueAxis6'.$itv.'.autoGridCount = false;
                        valueAxis6'.$itv.'.gridCount = 50;
                        valueAxis6'.$itv.'.labelFrequency = 2;                       
                       chart'.$itv.'.addValueAxis(valueAxis6'.$itv.');
                       
                       var valueAxis7'.$itv.' = new AmCharts.ValueAxis();
                       valueAxis7'.$itv.'.axisColor = "#FF6600";
                       valueAxis7'.$itv.'.axisThickness = 2;
                       valueAxis7'.$itv.'.gridAlpha = 0;
                       valueAxis7'.$itv.'.minimum = 0;
                       valueAxis7'.$itv.'.maximum = '.$boxVolume3.';
                       //valueAxis1.unit = "Ltrs";
                       valueAxis7'.$itv.'.autoGridCount = false;
                       valueAxis7'.$itv.'.gridCount = 50;
                       valueAxis7'.$itv.'.labelFrequency = 2;                       
                       chart'.$itv.'.addValueAxis(valueAxis7'.$itv.');
                       
                       var valueAxis8'.$itv.' = new AmCharts.ValueAxis();
                       valueAxis8'.$itv.'.axisColor = "#FF6600";
                       valueAxis8'.$itv.'.axisThickness = 2;
                       valueAxis8'.$itv.'.gridAlpha = 0;
                       valueAxis8'.$itv.'.minimum = 0;
                       valueAxis8'.$itv.'.maximum = '.$boxVolume4.';
                       //valueAxis1.unit = "Ltrs";
                       valueAxis8'.$itv.'.autoGridCount = false;
                       valueAxis8'.$itv.'.gridCount = 50;
                       valueAxis8'.$itv.'.labelFrequency = 2;                       
                       chart'.$itv.'.addValueAxis(valueAxis8'.$itv.');
        
                       // second value axis (on the right)
                       var valueAxis2'.$itv.' = new AmCharts.ValueAxis();
                       valueAxis2'.$itv.'.position = "right"; // this line makes the axis to appear on the right
                       valueAxis2'.$itv.'.axisColor = "#FCD202";
                       valueAxis2'.$itv.'.gridAlpha = 0;
                       valueAxis2'.$itv.'.axisThickness = 2;
                       valueAxis2'.$itv.'.minimum = 0;
                       valueAxis2'.$itv.'.maximum = 2;
                       valueAxis2'.$itv.'.labelsEnabled = false;
                       chart'.$itv.'.addValueAxis(valueAxis2'.$itv.');
        
                       // third value axis (on the left, detached)
                       var valueAxis3'.$itv.' = new AmCharts.ValueAxis();
                       valueAxis3'.$itv.'.offset = 50; // this line makes the axis to appear detached from plot area
                       valueAxis3'.$itv.'.gridAlpha = 0;
                       valueAxis3'.$itv.'.axisColor = "#B0DE09";
                       valueAxis3'.$itv.'.axisThickness = 2;
                       valueAxis3'.$itv.'.minimum = 0;
                       valueAxis3'.$itv.'.maximum = 180;
                       //valueAxis3.unit = "Kmph";                       
                       chart'.$itv.'.addValueAxis(valueAxis3'.$itv.');
                       
                      
                       
                           // fifth value axis (on the right)
                       var valueAxis5'.$itv.' = new AmCharts.ValueAxis();
                       valueAxis5'.$itv.'.position = "right"; // this line makes the axis to appear on the right
                       valueAxis5'.$itv.'.axisColor = "#2A0CD0";
                       valueAxis5'.$itv.'.gridAlpha = 0;
                       valueAxis5'.$itv.'.axisThickness = 2;
                       //valueAxis5'.$itv.'.minimum = 0;
                       //valueAxis5'.$itv.'.maximum = 99999;
                     //  valueAxis5'.$itv.'.labelsEnabled = false;
                       chart'.$itv.'.addValueAxis(valueAxis5'.$itv.');
        
        
                       // GRAPHS
                       // first graph
                       var graph1'.$itv.' = new AmCharts.AmGraph();
                       graph1'.$itv.'.valueAxis = valueAxis1'.$itv.'; // we have to indicate which value axis should be used
                       graph1'.$itv.'.title = "Fuels 1(Ltrs)";
                       graph1'.$itv.'.valueField = "fuelltrs";
                       graph1'.$itv.'.bullet = "round";
                       graph1'.$itv.'.hideBulletsCount = 30;
                       graph1'.$itv.'.bulletBorderThickness = 1;
                       graph1'.$itv.'.lineThickness = 2;
                       graph1'.$itv.'.balloonText = "Fuel 1: <b>[[fuelltrs]]</b> Ltrs, <b>[[fuel]]</b> %";
                       chart'.$itv.'.addGraph(graph1'.$itv.');';
                       if($boxVolume2!=0){
                       $result .= 	'  var graph6'.$itv.' = new AmCharts.AmGraph();
                       graph6'.$itv.'.valueAxis = valueAxis6'.$itv.'; // we have to indicate which value axis should be used
                       graph6'.$itv.'.title = "Fuels 2(Ltrs)";
                       graph6'.$itv.'.valueField = "fuelltrs2";
                       graph6'.$itv.'.bullet = "round";
                       graph6'.$itv.'.hideBulletsCount = 30;
                       graph6'.$itv.'.bulletBorderThickness = 1;
                       graph6'.$itv.'.lineThickness = 2;
                       graph6'.$itv.'.balloonText = "Fuel 2: <b>[[fuelltrs2]]</b> Ltrs, <b>[[fuel2]]</b> %";
                       chart'.$itv.'.addGraph(graph6'.$itv.');';
                       }
                        if($boxVolume3!=0){
                       $result .= 	' 
                         var graph7'.$itv.' = new AmCharts.AmGraph();
                       graph7'.$itv.'.valueAxis = valueAxis7'.$itv.'; // we have to indicate which value axis should be used
                       graph7'.$itv.'.title = "Fuels 3(Ltrs)";
                       graph7'.$itv.'.valueField = "fuelltrs3";
                       graph7'.$itv.'.bullet = "round";
                       graph7'.$itv.'.hideBulletsCount = 30;
                       graph7'.$itv.'.bulletBorderThickness = 1;
                       graph7'.$itv.'.lineThickness = 2;
                       graph7'.$itv.'.balloonText = "Fuel 3: <b>[[fuelltrs3]]</b> Ltrs, <b>[[fuel3]]</b> %";
                       chart'.$itv.'.addGraph(graph7'.$itv.');';
                        }
                        if($boxVolume4!=0){
                       $result .= 	' 
                         var graph8'.$itv.' = new AmCharts.AmGraph();
                       graph8'.$itv.'.valueAxis = valueAxis8'.$itv.'; // we have to indicate which value axis should be used
                       graph8'.$itv.'.title = "Fuels 4(Ltrs)";
                       graph8'.$itv.'.valueField = "fuelltrs4";
                       graph8'.$itv.'.bullet = "round";
                       graph8'.$itv.'.hideBulletsCount = 30;
                       graph8'.$itv.'.bulletBorderThickness = 1;
                       graph8'.$itv.'.lineThickness = 2;
                       graph8'.$itv.'.balloonText = "Fuel 4: <b>[[fuelltrs4]]</b> Ltrs, <b>[[fuel4]]</b> %";
                       chart'.$itv.'.addGraph(graph8'.$itv.');';
                        }
                       // second graph
                       $result .= 	'   var graph2'.$itv.' = new AmCharts.AmGraph();
                       graph2'.$itv.'.valueAxis = valueAxis2'.$itv.'; // we have to indicate which value axis should be used
                       graph2'.$itv.'.title = "Ignition";
                       graph2'.$itv.'.valueField = "acc";
                       graph2'.$itv.'.bullet = "square";
                       graph2'.$itv.'.hideBulletsCount = 30;
                       graph2'.$itv.'.bulletBorderThickness = 1;
                       graph2'.$itv.'.fillColorsField = "#FCD202";
                       graph2'.$itv.'.fillAlphas = 0.3;
                       graph2'.$itv.'.balloonFunction = adjustBalloonText;
                       chart'.$itv.'.addGraph(graph2'.$itv.');
        
                       // third graph
                       var graph3'.$itv.' = new AmCharts.AmGraph();
                       graph3'.$itv.'.valueAxis = valueAxis3'.$itv.'; // we have to indicate which value axis should be used
                       graph3'.$itv.'.valueField = "speed";
                       graph3'.$itv.'.title = "Speed(Kmph)";
                       graph3'.$itv.'.bullet = "triangleUp";
                       graph3'.$itv.'.hideBulletsCount = 30;
                       graph3'.$itv.'.bulletBorderThickness = 1;
                       graph3'.$itv.'.fillColorsField = "#B0DE09";
                       graph3'.$itv.'.fillAlphas = 0.3;
                       graph3'.$itv.'.balloonText = "Speed: <b>[[value]]</b> Kmph";
                       chart'.$itv.'.addGraph(graph3'.$itv.');
                       
                                       
                       
					     var graph5'.$itv.' = new AmCharts.AmGraph();
                       graph5'.$itv.'.valueAxis = valueAxis5'.$itv.'; // we have to indicate which value axis should be used
                       graph5'.$itv.'.valueField = "mileage";
                       graph5'.$itv.'.title = "Kmph";
                       graph5'.$itv.'.bullet = "round";
                       graph5'.$itv.'.hideBulletsCount = 2;
                       graph5'.$itv.'.bulletBorderThickness = 1;
                      //graph5'.$itv.'.fillColorsField = "#2A0CD0";
                      // graph5'.$itv.'.fillAlphas = 0.3;
                       graph5'.$itv.'.balloonText = "Mileage: <b>[[value]]</b> Kmph";
                       chart'.$itv.'.addGraph(graph5'.$itv.');
                       
                       
                       // CURSOR
                       var chartCursor'.$itv.' = new AmCharts.ChartCursor();
                       chartCursor'.$itv.'.cursorAlpha = 0.1;
                       chartCursor'.$itv.'.fullWidth = true;
                       chartCursor'.$itv.'.categoryBalloonDateFormat = "JJ:NN:SS, DD MMM YYYY",
                	       chart'.$itv.'.addChartCursor(chartCursor'.$itv.');
        
                       // SCROLLBAR
                       var chartScrollbar'.$itv.' = new AmCharts.ChartScrollbar();
                       chartScrollbar'.$itv.'.graph = graph1'.$itv.';
                       chartScrollbar'.$itv.'.scrollbarHeight = 20;
                       chart'.$itv.'.addChartScrollbar(chartScrollbar'.$itv.');
        
                       // LEGEND
                       var legend'.$itv.' = new AmCharts.AmLegend();
                       legend'.$itv.'.marginLeft = 110;
                       legend'.$itv.'.useGraphSettings = true;
                       chart'.$itv.'.addLegend(legend'.$itv.');
        
                       // WRITE
                       chart'.$itv.'.write("chartdiv'.$itv.'");
                   });
                   
                   function adjustBalloonText(graphDataItem, graph){
                        
                           var value = graphDataItem.values.value;

                            if(value == 1){
                                return "Acc: <b>On</b>";
                            }
                            else{
                                return "Acc: <b>Off</b>";
                            }
                   }
                    
                   // this method is called when chart is first inited as we listen for "dataUpdated" event
                   function zoomChart() {
                       // different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
                       //chart.zoomToIndexes(0, 100);
                      
                   }
                   
			
                </script>';              
                
        $result .= '<div id="chartdiv'.$itv.'" style="width: 100%; height: 300px;"></div>';
             
		}
		
		$result1='';
		$drivername="";
		$driverphoneno="";
		$totalfilling0=0;
		$totallength0=0;
		$totalduration0=0 ;
		$totalfilling=0;
		$totalduration=0 ;
		$totalfilling1=0;
		$totalduration1=0 ;
		$ff=array();$ft=array();
		
		$ff = getRouteFuelFillingsnew($route1, $accuracy, $fuel_sensors);
		$ft = getRouteFuelTheftsnew($route1, $accuracy, $fuel_sensors);

		$data = fngettripconsumption($route1, $accuracy, $fuel_sensors,$ff, $dtf, $dtt,$imei);
		
		$tmpdata=array();
		if(isset($data) && !empty ($data))
		{
			for ($j=0; $j<count($data); $j++)
			{
				if(isset($data[$j][0])  && isset($data[$j][0]["mid"])&& isset($data[$j][0]["fuel"]))
				{
					//if(intval($data[$j][0]["mid"])>0 && intval($data[$j][0]["fuel"])>0 )
					{
						$tmpdata[]=$data[$j][0];
						$totalfilling0=$totalfilling0+$data[$j][0]["fuel"];
						$totallength0=$totallength0+$data[$j][0]["mid"];
						$totalduration0 .= strtotime($totalduration0 . $data[$j][0]["duration"]);
					}
				}
			}
		}

		$data=$tmpdata;
		
		$result .= '<table  width="100%" border="1" ><tr><td>';
		if (count($data) <= 0 || empty($data) )
		{
			$result .=  '<table class="report" width="100%" ><tr align="center">	<th style="background-color: rgb(50, 65, 165); color: white; font-weight: bold; padding: 7px;" colspan="5">FUEL CONSUMPTION</th></tr><tr><td style="text-align: center;">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
			$result .=  '';
		}
		else {
			
		$fcr = getObjectFCR($imei);
		
		$result .= '<table class="report" width="100%" >
		<tr align="center">	<th style="background-color: rgb(50, 65, 165); color: white; font-weight: bold; padding: 7px;" colspan="8">FUEL CONSUMPTION</th></tr>
		<tr align="center"><th colspan="8"><hr></th></tr>
	
						<tr align="center">
						
						<th >'.$la['START'].'</th>
						<th >'.$la['END'].'</th>
						<th >'.$la['DURATION'].'</th>
						<th >'.$la['SMILEAGE'].'</th>
						<th >'.$la['EMILEAGE'].'</th>
						<th>'.$la['TAKENKM'].'</th>
						<th>'.$la['FUEL_CONSUMPTION'].'</th>
						<th>'.$la['KMPL'].'</th>
					</tr>';
					
			//echo  json_encode($data);
			
			if(isset($data) && !empty ($data))
			{
				for ($j=0; $j<count($data); $j++)
				{
					if(isset($data[$j]))
					{
						$result .= '<tr align="center">
									<td>'.convUserTimezone( $data[$j]["dt_trackerfrom"]).'</td>
									<td>'. convUserTimezone($data[$j]["dt_trackerto"]).'</td>
									<td>'.$data[$j]["duration"].'</td>
									<td>'.$data[$j]["mi1"].'</td>
									<td>'.$data[$j]["mi2"].'</td>
									<td>'.$data[$j]["mid"].' '.$_SESSION["unit_distance"].'</td>';
						$result .= '<td>'.$data[$j]["fuel"].' '.$_SESSION["unit_capacity"].'</td>';
						if($data[$j]["fuel"]!=0)
						$result .= '<td>'.round( ($data[$j]["mid"]/$data[$j]["fuel"]),2) .'</td>';
						else 
						$result .= '<td> - </td>';
						$result .= '</tr>';
					}
				}
			}
			
		
			//<td colspan="2"> Total Duration : '.$totalduration.'</td>
			
			$result .= '<tr align="center">
			<td colspan="4"> Total Trip length : '.$totallength0.' '.$_SESSION["unit_distance"].' </td>
			<td colspan="4"> Total Fuel Consumption : '.$totalfilling0.' '.$_SESSION["unit_capacity"].' </td> </tr>';
		$result .= '</table>';
		}
		$result .= '<br/><hr/>';
			
		/* fuel consumption ends here by NR.Vetrivel*/
        if ((count($route1) == 0) || (count($ff["fillings"]) == 0))
		{
			$result1 .=  '<table class="report" width="100%" ><tr align="center">	<th style="background-color: rgb(50, 65, 165); color: white; font-weight: bold; padding: 7px;" colspan="7">FUEL FILLING</th></tr>
			<tr><td style="text-align: center;">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
		else
		{

		$result1 .= '<table class="report" width="100%" >
		<tr align="center">	<th style="background-color: rgb(50, 65, 165); color: white; font-weight: bold; padding: 7px;" colspan="10">FUEL FILLING</th></tr>
		<tr align="center"><th colspan="10"><hr></th></tr>
					<tr align="center">
						<th>'.$la['FROMDATE'].'</th>
						<th>'.$la['TODATE'].'</th>
						<th>'.$la['DURATION'].'</th>
						<th>'.$la['POSITION'].'</th>
						<th>'.$la['BEFORE'].'</th>
						<th>'.$la['AFTER'].'</th>
						<th>'.$la['FILLED'].'</th>
						<th>'.$la['SENSOR'].'</th>
					<th>'.$la['SMILEAGE'].'</th>
					<th>'.$la['EMILEAGE'].'</th>
					</tr>';

		for ($j=0; $j<count($fuel_sensors); ++$j)
		{
			$sensor = $fuel_sensors[$j]['name'];
			
			for ($i=0; $i<count($ff['fillings'][$sensor]);$i++)
			{
				$lat = $ff['fillings'][$sensor][$i]["lat"];
				$lng = $ff['fillings'][$sensor][$i]["lng"];

				$params = $ff['fillings'][$sensor][$i]["params"];
				$driver = getObjectDriver($user_id, $imei, $params);
				if ($driver['driver_name'] == ''){$driver['driver_name'] = $la['NA'];}

				$result1 .= '<tr align="center">
				<td>'.$ff['fillings'][$sensor][$i]["start"].'</td>
				<td>'.$ff['fillings'][$sensor][$i]["end"].'</td><td>'.getTimeDifferenceDetails($ff['fillings'][$sensor][$i]["start"],$ff['fillings'][$sensor][$i]["end"]).'</td>
				<td>'.reportsGetPossition($lat, $lng, $show_coordinates, $show_addresses, $zones_addresses).'</td>';
				$result1 .= '<td>'.$ff['fillings'][$sensor][$i]["before"].'</td>
				<td>'.$ff['fillings'][$sensor][$i]["after"].'</td>
				<td>'.$ff['fillings'][$sensor][$i]["filled"].'</td>
				<td>'.$ff['fillings'][$sensor][$i]["sensor"].'</td>
				<td>'.$ff['fillings'][$sensor][$i]["mileage_start"].'</td>
				<td>'.$ff['fillings'][$sensor][$i]["mileage_end"].'</td>
				</tr>';
					
				if(isset($driver['driver_name']))
				$drivername=$driver['driver_name'];
				if(isset($driver['driver_phone']))
				$driverphoneno=$driver['driver_phone'];
			}
		}

		//Total Duration : '.$totalduration.'
		$result1 .= '<tr align="center"><td colspan="4"> </td><td colspan="4"> Total Fuel Filling : '.$ff['total_filled'].' </td> </tr>';
		$result1 .= '</table>';
	}
	$result1 .= '<br/><hr/>';
	//$result1 .= '<br/><br/>';



	if ((count($route1) == 0) || (count($ft["thefts"]) == 0))
	{
		$result1 .=  '<table class="report" width="100%" ><tr align="center">	<th style="background-color: rgb(50, 65, 165); color: white; font-weight: bold; padding: 7px;" colspan="7">FUEL THEFT</th></tr>
		<tr><td style="text-align: center;">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
	}
	else
	{
		$result1 .= '<table class="report" width="100%" >
		<tr align="center">	<th style="background-color: rgb(50, 65, 165); color: white; font-weight: bold; padding: 7px;" colspan="10">FUEL SIPHON</th></tr>
		<tr align="center"><th colspan="10"><hr></th></tr>
					<tr align="center">
						<th>'.$la['FROMDATE'].'</th>
						<th>'.$la['TODATE'].'</th>
						<th>'.$la['DURATION'].'</th>
						<th>'.$la['POSITION'].'</th>
						<th>'.$la['BEFORE'].'</th>
						<th>'.$la['AFTER'].'</th>
						<th>'.$la['STOLEN'].'</th>
						<th>'.$la['SENSOR'].'</th>
						<th>'.$la['SMILEAGE'].'</th>
					<th>'.$la['EMILEAGE'].'</th>
					</tr>';

			
		for ($j=0; $j<count($fuel_sensors); ++$j)
		{
			$sensor = $fuel_sensors[$j]['name'];
			for ($i=0; $i<count($ft['thefts'][$sensor]); $i++)
			{
				$lat = $ft['thefts'][$sensor][$i]["lat"];
				$lng = $ft['thefts'][$sensor][$i]["lng"];
					
				$params = $ft['thefts'][$sensor][$i]["params"];
				$driver = getObjectDriver($user_id, $imei, $params);
				if ($driver['driver_name'] == ''){$driver['driver_name'] = $la['NA'];}
					
				$result1 .= '<tr align="center">
				<td>'.$ft['thefts'][$sensor][$i]["start"].'</td>
				<td>'.$ft['thefts'][$sensor][$i]["end"].'</td>
				<td>'.getTimeDifferenceDetails($ft['thefts'][$sensor][$i]["start"],$ft['thefts'][$sensor][$i]["end"]).'</td>
				<td>'.reportsGetPossition($lat, $lng, $show_coordinates, $show_addresses, $zones_addresses).'</td>
				<td>'.$ft['thefts'][$sensor][$i]["before"].'</td>
				<td>'.$ft['thefts'][$sensor][$i]["after"].'</td>
				<td>'.$ft['thefts'][$sensor][$i]["siphon"].'</td>
				<td>'.$ft['thefts'][$sensor][$i]["sensor"].'</td>
				<td>'.$ft['thefts'][$sensor][$i]["mileage_start"].'</td>
				<td>'.$ft['thefts'][$sensor][$i]["mileage_end"].'</td>
				</tr>';
				
			}
		}
			
		
		//here for col span
		//Total Duration : '.$totalduration.'
		$result1 .= '<tr align="center"><td colspan="4"> </td><td colspan="4"> Total Fuel Theft : '.$ft['total_stolen'].' </td> </tr>';

		$result1 .= '</table>';
	}


	$result1 .= '<br/><hr/>';
	

	$result1 .=  '<table class="report" width="100%" ><tr align="center">	<th style="background-color: rgb(50, 65, 165); color: white; font-weight: bold; padding: 7px;" colspan="7">FUEL SUMMARY</th></tr>
	<tr>
		
	<td > Driver Name : '.$drivername.' </td>
	<td >  Driver Phone : '.$driverphoneno.'  </td>
	
	<td > Total Fuel Filling : ';if(isset($ff['total_filled']))$result1 .=$ff['total_filled'];$result1 .= '</td> 
	<td > Total Fuel Theft : ';if(isset($ft['total_stolen']))$result1 .=$ft['total_stolen'];$result1 .= ' </td>
	
	</tr>
		<tr align="center">
			<td colspan="2"> Total Trip length : '.$totallength0.' '.$_SESSION["unit_distance"].' </td>
			<td colspan="2"> Total Fuel Consumption : '.$totalfilling0.' '.$_SESSION["unit_capacity"].' </td> </tr>
	</table>';

	$result1 .= '<br/><hr/>';
	//$result .= '<br/><br/>';
	$result1 .= '</table ></tr></td>';

	$returns=$result.$result1;
	//return $returns;

		return $returns;
	}    
  
	
	function reportsGenerateTempGraph($imei, $dtf, $dtt,$itv) 
	{
		global $_SESSION, $gsValues, $la, $user_id;
        
        $result = '';		
		//$imei ='6120317585';
		$accuracy = getObjectAccuracy($imei);
		
		if($accuracy["temp1"]=="NO" && $accuracy["temp2"]=="NO"  && $accuracy["temp3"]=="NO"  )
		{
			return '<table><tr><td>'.$la['SENSOR_NOT_ADD'].'</td></tr></table>';
		}
		
		$fuel_sensors = getSensorFromType($imei, 'fuel');
		
		$route = getIFSDataTemp($imei, $accuracy, $fuel_sensors, $dtf, $dtt);
        
        $sensor = $fuel_sensors[0];
        
        $boxVolume = getBoxVolume($sensor);
        
		if ((count($route) == 0)) // || ($fuel_sensors == false))
		{
			return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
        
		$result .= 	'<script type="text/javascript">
            			$(document).ready(function () {
                            chartData'.$itv.' = '.json_encode($route).';
                           
            			})
	              </script>';
        
        $result .= 	'<script type="text/javascript">
                   var chart'.$itv.';
                   var cd'.$itv.';
                   

        
                   AmCharts.ready(function () {
        
                       // SERIAL CHART
                       chart'.$itv.' = new AmCharts.AmSerialChart();
                       //chart.baseHref = true;
                       chart'.$itv.'.pathToImages = "'.$gsValues['URL_ROOT'].'/js/amcharts/images/";
                       chart'.$itv.'.dataProvider = chartData'.$itv.';
                       chart'.$itv.'.categoryField = "date";
                       chart'.$itv.'.dataDateFormat = "YYYY-MM-DD JJ:NN:SS"; // ENTER YOUR PREFFERED DATE FORMAT TO DISPLAY CHART CORRECTLY
                       // listen for "dataUpdated" event (fired when chart is inited) and call zoomChart method when it happens
                       chart'.$itv.'.addListener("dataUpdated", zoomChart);
        
                       // AXES
                       // category
                       var categoryAxis'.$itv.' = chart'.$itv.'.categoryAxis;
                       categoryAxis'.$itv.'.parseDates = true; // as our data is date-based, we set parseDates to true
                       categoryAxis'.$itv.'.minPeriod = "ss"; // our data is daily, so we set minPeriod to DD
                       categoryAxis'.$itv.'.minorGridEnabled = true;
                       categoryAxis'.$itv.'.axisColor = "#DADADA";
                       categoryAxis'.$itv.'.twoLineMode = true;
                       categoryAxis'.$itv.'.dateFormats = [{
                            period: "fff",
                            format: "JJ:NN:SS"
                        }, {
                            period: "ss",
                            format: "JJ:NN:SS"
                        }, {
                            period: "mm",
                            format: "JJ:NN"
                        }, {
                            period: "hh",
                            format: "JJ:NN"
                        }, {
                            period: "DD",
                            format: "DD"
                        }, {
                            period: "WW",
                            format: "DD"
                        }, {
                            period: "MM",
                            format: "MMM"
                        }, {
                            period: "YYYY",
                            format: "YYYY"
                        }];
        
                    
        
                       // second value axis (on the right)
                       var valueAxis2'.$itv.' = new AmCharts.ValueAxis();
                       valueAxis2'.$itv.'.position = "right"; // this line makes the axis to appear on the right
                       valueAxis2'.$itv.'.axisColor = "#FCD202";
                       valueAxis2'.$itv.'.gridAlpha = 0;
                       valueAxis2'.$itv.'.axisThickness = 2;
                       valueAxis2'.$itv.'.minimum = 0;
                       valueAxis2'.$itv.'.maximum = 2;
                       valueAxis2'.$itv.'.labelsEnabled = false;
                       chart'.$itv.'.addValueAxis(valueAxis2'.$itv.');
        
                       // third value axis (on the left, detached)
                       var valueAxis3'.$itv.' = new AmCharts.ValueAxis();
                       valueAxis3'.$itv.'.offset = 50; // this line makes the axis to appear detached from plot area
                       valueAxis3'.$itv.'.gridAlpha = 0;
                       valueAxis3'.$itv.'.axisColor = "#B0DE09";
                       valueAxis3'.$itv.'.axisThickness = 2;
                       valueAxis3'.$itv.'.minimum = 0;
                       valueAxis3'.$itv.'.maximum = 180;
                       //valueAxis3.unit = "Kmph";                       
                       chart'.$itv.'.addValueAxis(valueAxis3'.$itv.');
                       
                       // third value axis (on the left, detached)
                       var valueAxis4'.$itv.' = new AmCharts.ValueAxis();
                       valueAxis4'.$itv.'.offset = 100; // this line makes the axis to appear detached from plot area
                       valueAxis4'.$itv.'.gridAlpha = 0;
                       valueAxis4'.$itv.'.axisColor = "#d1655d";
                       valueAxis4'.$itv.'.axisThickness = 2;
                       valueAxis4'.$itv.'.minimum = -55;
                       valueAxis4'.$itv.'.maximum = 55;
                       //valueAxis4.unit = "C";                       
                       chart'.$itv.'.addValueAxis(valueAxis4'.$itv.');
                       
                        var valueAxis6'.$itv.' = new AmCharts.ValueAxis();
                       valueAxis6'.$itv.'.offset = 100; // this line makes the axis to appear detached from plot area
                       valueAxis6'.$itv.'.gridAlpha = 0;
                       valueAxis6'.$itv.'.axisColor = "#d1655d";
                       valueAxis6'.$itv.'.axisThickness = 2;
                       valueAxis6'.$itv.'.minimum = -55;
                       valueAxis6'.$itv.'.maximum = 55;
                       //valueAxis6.unit = "C";                       
                       chart'.$itv.'.addValueAxis(valueAxis6'.$itv.');
                       
                        var valueAxis7'.$itv.' = new AmCharts.ValueAxis();
                       valueAxis7'.$itv.'.offset = 100; // this line makes the axis to appear detached from plot area
                       valueAxis7'.$itv.'.gridAlpha = 0;
                       valueAxis7'.$itv.'.axisColor = "#d1655d";
                       valueAxis7'.$itv.'.axisThickness = 2;
                       valueAxis7'.$itv.'.minimum = -55;
                       valueAxis7'.$itv.'.maximum = 55;
                       //valueAxis7.unit = "C";                       
                       chart'.$itv.'.addValueAxis(valueAxis7'.$itv.');
                       
                         // fifth value axis (on the right)
                    var valueAxis5'.$itv.' = new AmCharts.ValueAxis();
                       valueAxis5'.$itv.'.position = "right"; // this line makes the axis to appear on the right
                       valueAxis5'.$itv.'.axisColor = "#2A0CD0";
                       valueAxis5'.$itv.'.gridAlpha = 0;
                       valueAxis5'.$itv.'.axisThickness = 2;
                       //valueAxis5'.$itv.'.minimum = 0;
                       //valueAxis5'.$itv.'.maximum = 99999;
                     //  valueAxis5'.$itv.'.labelsEnabled = false;
                       chart'.$itv.'.addValueAxis(valueAxis5'.$itv.');
        
        
                       // GRAPHS
                     
        
                       // second graph
                       var graph2'.$itv.' = new AmCharts.AmGraph();
                       graph2'.$itv.'.valueAxis = valueAxis2'.$itv.'; // we have to indicate which value axis should be used
                       graph2'.$itv.'.title = "Ignition";
                       graph2'.$itv.'.valueField = "acc";
                       graph2'.$itv.'.bullet = "square";
                       graph2'.$itv.'.hideBulletsCount = 30;
                       graph2'.$itv.'.bulletBorderThickness = 1;
                       graph2'.$itv.'.fillColorsField = "#FCD202";
                       graph2'.$itv.'.fillAlphas = 0.3;
                       graph2'.$itv.'.balloonFunction = adjustBalloonText;
                       chart'.$itv.'.addGraph(graph2'.$itv.');
        
                       // third graph
                       var graph3'.$itv.' = new AmCharts.AmGraph();
                       graph3'.$itv.'.valueAxis = valueAxis3'.$itv.'; // we have to indicate which value axis should be used
                       graph3'.$itv.'.valueField = "speed";
                       graph3'.$itv.'.title = "Speed(Kmph)";
                       graph3'.$itv.'.bullet = "triangleUp";
                       graph3'.$itv.'.hideBulletsCount = 30;
                       graph3'.$itv.'.bulletBorderThickness = 1;
                       graph3'.$itv.'.fillColorsField = "#B0DE09";
                       graph3'.$itv.'.fillAlphas = 0.3;
                       graph3'.$itv.'.balloonText = "Speed: <b>[[value]]</b> Kmph";
                       chart'.$itv.'.addGraph(graph3'.$itv.');
                       
                       // fourth graph
                       var graph4'.$itv.' = new AmCharts.AmGraph();
                       graph4'.$itv.'.valueAxis = valueAxis4'.$itv.'; // we have to indicate which value axis should be used
                       graph4'.$itv.'.valueField = "temp1";
                       graph4'.$itv.'.title = "Temprature1 (C)";
                       graph4'.$itv.'.bullet = "triangleDown";
                       graph4'.$itv.'.hideBulletsCount = 2;
                       graph4'.$itv.'.bulletBorderThickness = 1;
                       //graph4.lineColor = "#D1655D";
                       //graph4.fillColorsField = "#D1655D";
                       //graph4.fillAlphas = 0.3;
                       graph4'.$itv.'.balloonText = "Temp1: <b>[[value]] &deg;</b>C";
                       chart'.$itv.'.addGraph(graph4'.$itv.');';

        			if($accuracy["temp2"]!="NO")
					{
                        // sixth graph
                     $result .= 	'  var graph6'.$itv.' = new AmCharts.AmGraph();
                       graph6'.$itv.'.valueAxis = valueAxis6'.$itv.'; // we have to indicate which value axis should be used
                       graph6'.$itv.'.valueField = "temp2";
                       graph6'.$itv.'.title = "Temprature2 (C)";
                       graph6'.$itv.'.bullet = "triangleDown";
                       graph6'.$itv.'.hideBulletsCount = 2;
                       graph6'.$itv.'.bulletBorderThickness = 1;
                       //graph6.lineColor = "#D1655D";
                       //graph6.fillColorsField = "#D1655D";
                       //graph6.fillAlphas = 0.3;
                       graph6'.$itv.'.balloonText = "Temp2: <b>[[value]] &deg;</b>C";
                       chart'.$itv.'.addGraph(graph6'.$itv.');      ';
					}
                     if($accuracy["temp3"]!="NO")
					{
                         // seventh graph
                            $result .= 	'  var graph7'.$itv.' = new AmCharts.AmGraph();
                       graph7'.$itv.'.valueAxis = valueAxis7'.$itv.'; // we have to indicate which value axis should be used
                       graph7'.$itv.'.valueField = "temp3";
                       graph7'.$itv.'.title = "Temprature3 (C)";
                       graph7'.$itv.'.bullet = "triangleDown";
                       graph7'.$itv.'.hideBulletsCount = 2;
                       graph7'.$itv.'.bulletBorderThickness = 1;
                       //graph7.lineColor = "#D1655D";
                       //graph7.fillColorsField = "#D1655D";
                       //graph7.fillAlphas = 0.3;
                       graph7'.$itv.'.balloonText = "Temp3: <b>[[value]] &deg;</b>C";
                       chart'.$itv.'.addGraph(graph7'.$itv.');  ';
					}   
                       
					    // fifth graph
                        $result .= 	'var graph5'.$itv.' = new AmCharts.AmGraph();
                       graph5'.$itv.'.valueAxis = valueAxis5'.$itv.'; // we have to indicate which value axis should be used
                       graph5'.$itv.'.valueField = "mileage";
                       graph5'.$itv.'.title = "Kmph";
                       graph5'.$itv.'.bullet = "round";
                       graph5'.$itv.'.hideBulletsCount = 2;
                       graph5'.$itv.'.bulletBorderThickness = 1;
                      //graph5'.$itv.'.fillColorsField = "#2A0CD0";
                      // graph5'.$itv.'.fillAlphas = 0.3;
                       graph5'.$itv.'.balloonText = "Mileage: <b>[[value]]</b> Kmph";
                       chart'.$itv.'.addGraph(graph5'.$itv.');
					   
                       // CURSOR
                       var chartCursor'.$itv.' = new AmCharts.ChartCursor();
                       chartCursor'.$itv.'.cursorAlpha = 0.1;
                       chartCursor'.$itv.'.fullWidth = true;
                       chartCursor'.$itv.'.categoryBalloonDateFormat = "JJ:NN:SS, DD MMM YYYY",
                       chart'.$itv.'.addChartCursor(chartCursor'.$itv.');
        
                       // SCROLLBAR
                       var chartScrollbar'.$itv.' = new AmCharts.ChartScrollbar();
                       chartScrollbar'.$itv.'.graph = graph4'.$itv.';
                       chartScrollbar'.$itv.'.scrollbarHeight = 20;
                       chart'.$itv.'.addChartScrollbar(chartScrollbar'.$itv.');
        
                       // LEGEND
                       var legend'.$itv.' = new AmCharts.AmLegend();
                       legend'.$itv.'.marginLeft = 110;
                       legend'.$itv.'.useGraphSettings = true;
                       chart'.$itv.'.addLegend(legend'.$itv.');
        
                       // WRITE
                       chart'.$itv.'.write("chartdiv'.$itv.'");
                   });
                   
                   function adjustBalloonText(graphDataItem, graph){
                        
                           var value = graphDataItem.values.value;

                            if(value == 1){
                                return "Acc: <b>On</b>";
                            }
                            else{
                                return "Acc: <b>Off</b>";
                            }
                   }
                    
                   // this method is called when chart is first inited as we listen for "dataUpdated" event
                   function zoomChart() {
                       // different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
                       //chart.zoomToIndexes(0, 100);
                   }
                </script>';              
                
        $result .= '<div id="chartdiv'.$itv.'" style="width: 100%; height: 300px;"></div>';
        
       
		return $result;
	}    

	
	function reportsGeneratefuelana($imei, $dtf, $dtt, $stop_duration, $show_addresses, $zones_addresses,$show_coordinates)
	{
		global $_SESSION, $la, $user_id;
		
		$result='';
		$drivername="";
		$driverphoneno="";
		$totalfilling0=0;
		$totallength0=0;
		$totalduration0=0 ;
		
		$totalfilling=0;
		$totalduration=0 ;
		$totalfilling1=0;
		$totalduration1=0 ;	
		
			
		$accuracy = getObjectAccuracy($imei);
		$fuel_sensors = getSensorFromType($imei, 'fuel');
	
		$route = getRouteRawnew($imei, $accuracy, $dtf, $dtt);
		$ff = getRouteFuelFillingsnew($route, $accuracy, $fuel_sensors);
		$ft = getRouteFuelTheftsnew($route, $accuracy, $fuel_sensors);
		
		$ff =array();$ft=array();
		
		$result .= '<table  width="100%" border="1" ><tr><td>';	
			
		//echo  json_encode($ff);
		$data=array();
		
		//$data = fngettripconsumption($route, $accuracy, $fuel_sensors,$ff, $dtf, $dtt,$imei);
		
		if (count($data) <= 0 || empty($data) )
		{
			$result .=  '<table class="report" width="100%" ><tr align="center">	<th style="background-color: rgb(50, 65, 165); color: white; font-weight: bold; padding: 7px;" colspan="5">FUEL CONSUMPTION</th></tr>
		<tr><td style="text-align: center;">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
		else {
			
		$fcr = getObjectFCR($imei);
		
		$result .= '<table class="report" width="100%" >
		<tr align="center">	<th style="background-color: rgb(50, 65, 165); color: white; font-weight: bold; padding: 7px;" colspan="7">FUEL CONSUMPTION</th></tr>
		<tr align="center"><th colspan="7"><hr></th></tr>
	
						<tr align="center">
						
						<th >'.$la['START'].'</th>
						<th >'.$la['END'].'</th>
						<th >'.$la['DURATION'].'</th>
						<th >'.$la['SMILEAGE'].'</th>
						<th >'.$la['EMILEAGE'].'</th>
						<th>'.$la['LENGTH'].'</th>
						<th>'.$la['FUEL_CONSUMPTION'].'</th>
					</tr>';
					
			//echo  json_encode($data);
			
			if(isset($data) && !empty ($data))
			{
			for ($j=0; $j<count($data); $j++)
			{
				if(isset($data[$j][0])  )
				{
					$result .= '<tr align="center">
									<td>'.convUserTimezone( $data[$j][0]["dt_trackerfrom"]).'</td>
									<td>'. convUserTimezone($data[$j][0]["dt_trackerto"]).'</td>
									<td>'.$data[$j][0]["duration"].'</td>
									<td>'.$data[$j][0]["mi1"].'</td>
									<td>'.$data[$j][0]["mi2"].'</td>
									<td>'.$data[$j][0]["mid"].' '.$_SESSION["unit_distance"].'</td>
									<td>'.$data[$j][0]["fuel"].' '.$_SESSION["unit_capacity"].'</td>
								</tr>';
								
								$totalfilling0=$totalfilling0+$data[$j][0]["fuel"];
								$totallength0=$totallength0+$data[$j][0]["mid"];
								$totalduration0 .= strtotime($totalduration0 . $data[$j][0]["duration"]);
				}							
			}
			}
		
			//<td colspan="2"> Total Duration : '.$totalduration.'</td>
			
			$result .= '<tr align="center">
			<td colspan="3"> Total Trip length : '.$totallength0.$_SESSION["unit_distance"].' </td>
			<td colspan="4"> Total Fuel Consumption : '.$totalfilling0.$_SESSION["unit_capacity"].' </td> </tr>';
		$result .= '</table><br/>';
		}
		$result .= '<br/><hr/>';
		$result .= '<br/><br/>';
		
	
		
		if ((count($route) == 0) || (count($ff) == 0) || empty($data)  || empty($ff) )
		{
			$result .=  '<table class="report" width="100%" ><tr align="center">	<th style="background-color: rgb(50, 65, 165); color: white; font-weight: bold; padding: 7px;" colspan="7">FUEL FILLING</th></tr>
		<tr><td style="text-align: center;">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
		else
			{
		$result .= '<table class="report" width="100%" >
		<tr align="center">	<th style="background-color: rgb(50, 65, 165); color: white; font-weight: bold; padding: 7px;" colspan="7">FUEL FILLING</th></tr>
		<tr align="center"><th colspan="7"><hr></th></tr>
					<tr align="center">
						<th>'.$la['FROMDATE'].'</th>
						<th>'.$la['TODATE'].'</th>
						<th>'.$la['DURATION'].'</th>
						<th>'.$la['POSITION'].'</th>
						<th>'.$la['BEFORE'].'</th>
						<th>'.$la['AFTER'].'</th>
						<th>'.$la['FILLED'].'</th>
					
					</tr>';
					
		
		for ($i=0; $i<count($ff); ++$i)
		{
			$lat = $ff[$i][1];
			$lng = $ff[$i][2];
			
			$params = $ff[$i][7];
			$driver = getObjectDriver($user_id, $imei, $params);
			if ($driver['driver_name'] == ''){$driver['driver_name'] = $la['NA'];}
			
			$result .= '<tr align="center">
							<td>'.($ff[$i][0]).'</td>
							<td>'.($ff[$i][8]).'</td>
							<td>'.$ff[$i][9].'</td>
							<td>'.reportsGetPossition($lat, $lng,$show_coordinates, $show_addresses, $zones_addresses).'</td>
							<td>'.$ff[$i][3].'</td>
							<td>'.$ff[$i][4].'</td>
							<td>'.$ff[$i][5].'</td>
						
						</tr>';
						$totalfilling=$totalfilling+$ff[$i][5];
						
						
					//	$totalduration=strtotime($totalduration)+strtotime($ff[$i][9]);
					
					$totalduration .= strtotime($totalduration . $ff[$i][9]);
					if(isset($driver['driver_name']))
						$drivername=$driver['driver_name'];
					 if(isset($driver['driver_phone']))
						$driverphoneno=$driver['driver_phone'];
		}
		//Total Duration : '.$totalduration.'
		$result .= '<tr align="center"><td colspan="3"> </td><td colspan="4"> Total Fuel Filling : '.$totalfilling.$_SESSION["unit_capacity"].' </td> </tr>';
		$result .= '</table><br/>';
		}
		$result .= '<br/><hr/>';
		$result .= '<br/><br/>';
		
	
		
		if ((count($route) == 0) || (count($ft) == 0))
		{
			$result .=  '<table class="report" width="100%" ><tr align="center">	<th style="background-color: rgb(50, 65, 165); color: white; font-weight: bold; padding: 7px;" colspan="7">FUEL THEFT</th></tr>
		<tr><td style="text-align: center;">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
		else
		{
		$result .= '<table class="report" width="100%" >
		<tr align="center">	<th style="background-color: rgb(50, 65, 165); color: white; font-weight: bold; padding: 7px;" colspan="7">FUEL THEFT</th></tr>
		<tr align="center"><th colspan="7"><hr></th></tr>
					<tr align="center">
						<th>'.$la['FROMDATE'].'</th>
						<th>'.$la['TODATE'].'</th>
						<th>'.$la['DURATION'].'</th>
						<th>'.$la['POSITION'].'</th>
						<th>'.$la['BEFORE'].'</th>
						<th>'.$la['AFTER'].'</th>
						<th>'.$la['STOLEN'].'</th>
						
					</tr>';

			
					
		for ($i=0; $i<count($ft); ++$i)
		{
			$lat = $ft[$i][1];
			$lng = $ft[$i][2];
			
			$params = $ft[$i][7];
			$driver = getObjectDriver($user_id, $imei, $params);
			if ($driver['driver_name'] == ''){$driver['driver_name'] = $la['NA'];}
			
			$result .= '<tr align="center">
							<td>'.$ft[$i][0].'</td>
							<td>'.$ft[$i][8].'</td>
							<td>'.$ft[$i][9].'</td>
							<td>'.reportsGetPossition($lat, $lng,$show_coordinates, $show_addresses, $zones_addresses).'</td>
							<td>'.$ft[$i][3].'</td>
							<td>'.$ft[$i][4].'</td>
							<td>'.$ft[$i][5].'</td>
							
						</tr>';
							
					$totalfilling1=$totalfilling1+$ft[$i][5];
						
					$totalduration1 .= strtotime($totalduration1 . $ft[$i][9]);;
						
					
						
		}
		//here for col span 
		//Total Duration : '.$totalduration.'
		$result .= '<tr align="center"><td colspan="3"> </td><td colspan="4"> Total Fuel Theft : '.$totalfilling1.$_SESSION["unit_capacity"].' </td> </tr>';
		
		$result .= '</table><br/>';
	}


		$result .= '<br/><hr/>';
		$result .= '<br/><br/>';
		
					$result .=  '<table class="report" width="100%" ><tr align="center">	<th style="background-color: rgb(50, 65, 165); color: white; font-weight: bold; padding: 7px;" colspan="7">FUEL SUMMARY</th></tr>
		<tr>
		
	<td > Driver Name : '.$drivername.' </td>
	<td >  Driver Phone : '.$driverphoneno.'  </td>
		</tr>
		
		<tr>
		
	<td > Total Trip length : '.$totallength0.$_SESSION["unit_distance"].' </td>
	<td > Total Fuel Consumption : '.$totalfilling0.$_SESSION["unit_capacity"].' </td>
	<td > Total Fuel Filling : '.$totalfilling.$_SESSION["unit_capacity"].' </td>
	<td > Total Fuel Theft : '.$totalfilling1.$_SESSION["unit_capacity"].' </td>
		</tr></table>';
		
		$result .= '<br/><hr/>';
		$result .= '<br/><br/>';
		$result .= '</table ></tr></td>';
		
		return $result;
				
	}
	
	 //Newly added by SelvaG- single vehicle And unlimited vehicle reprt  code update by VETRIVEL.N
	function reportsGenerateIFSGraph($imei, $dtf, $dtt,$itv,$data_item,$raw=false) 
	{
		global $_SESSION, $gsValues, $la, $user_id;
        
        $result = '';		
		//$imei ='6120317585';
		$accuracy = getObjectAccuracy($imei);
		$fuel_sensors = getSensorFromType($imei, 'fuel');
		
		$route = getIFSData($imei, $accuracy, $fuel_sensors, $dtf, $dtt,$raw);
        $sensor = $fuel_sensors[0];
        if($imei=='350544500208981'){
        	$sensor['param']='fmb';
        }
        $boxVolume = getBoxVolume($sensor);
        
        if($boxVolume==0)
        {
        	$boxVolume=100;
        }
        
		if ((count($route) == 0)) // || ($fuel_sensors == false))
		{
			return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
        
		if($raw==false)
		{
		$result .= 	'<script type="text/javascript">
            			$(document).ready(function () {
                            chartData'.$itv.' = '.json_encode($route).';
                           chartData'.$itv.'=chartOPTIMIZE(chartData'.$itv.');
            			})
	              </script>';
		}
		else 
		{
			$result .= 	'<script type="text/javascript">
            			$(document).ready(function () {
                            chartData'.$itv.' = '.json_encode($route).';
                           chartData'.$itv.'=(chartData'.$itv.');
            			})
	              </script>';
		}
		
        $result .= 	'<script type="text/javascript">
                   var chart'.$itv.';
                   var cd'.$itv.';
                   

        
                   AmCharts.ready(function () {
        
                       // SERIAL CHART
                       chart'.$itv.' = new AmCharts.AmSerialChart();
                       //chart.baseHref = true;
                       chart'.$itv.'.pathToImages = "'.$gsValues['URL_ROOT'].'/js/amcharts/images/";
                       chart'.$itv.'.dataProvider = chartData'.$itv.';
                       chart'.$itv.'.categoryField = "date";
                       chart'.$itv.'.dataDateFormat = "YYYY-MM-DD JJ:NN:SS"; // ENTER YOUR PREFFERED DATE FORMAT TO DISPLAY CHART CORRECTLY
                       // listen for "dataUpdated" event (fired when chart is inited) and call zoomChart method when it happens
                       chart'.$itv.'.addListener("dataUpdated", zoomChart);
        
                       // AXES
                       // category
                       var categoryAxis'.$itv.' = chart'.$itv.'.categoryAxis;
                       categoryAxis'.$itv.'.parseDates = true; // as our data is date-based, we set parseDates to true
                       categoryAxis'.$itv.'.minPeriod = "ss"; // our data is daily, so we set minPeriod to DD
                       categoryAxis'.$itv.'.minorGridEnabled = true;
                       categoryAxis'.$itv.'.axisColor = "#DADADA";
                       categoryAxis'.$itv.'.twoLineMode = true;
                       categoryAxis'.$itv.'.dateFormats = [{
                            period: "fff",
                            format: "JJ:NN:SS"
                        }, {
                            period: "ss",
                            format: "JJ:NN:SS"
                        }, {
                            period: "mm",
                            format: "JJ:NN"
                        }, {
                            period: "hh",
                            format: "JJ:NN"
                        }, {
                            period: "DD",
                            format: "DD"
                        }, {
                            period: "WW",
                            format: "DD"
                        }, {
                            period: "MM",
                            format: "MMM"
                        }, {
                            period: "YYYY",
                            format: "YYYY"
                        }];
        
                       // first value axis (on the left)
                       var valueAxis1'.$itv.' = new AmCharts.ValueAxis();
                       valueAxis1'.$itv.'.axisColor = "#FF6600";
                       valueAxis1'.$itv.'.axisThickness = 2;
                       valueAxis1'.$itv.'.gridAlpha = 0;
                       valueAxis1'.$itv.'.minimum = 0;
                       valueAxis1'.$itv.'.maximum = '.$boxVolume.';
                       //valueAxis1.unit = "Ltrs";
                        valueAxis1'.$itv.'.autoGridCount = false;
                        valueAxis1'.$itv.'.gridCount = 50;
                        valueAxis1'.$itv.'.labelFrequency = 2;                       
                       chart'.$itv.'.addValueAxis(valueAxis1'.$itv.');
        
                       // second value axis (on the right)
                       var valueAxis2'.$itv.' = new AmCharts.ValueAxis();
                       valueAxis2'.$itv.'.position = "right"; // this line makes the axis to appear on the right
                       valueAxis2'.$itv.'.axisColor = "#FCD202";
                       valueAxis2'.$itv.'.gridAlpha = 0;
                       valueAxis2'.$itv.'.axisThickness = 2;
                       valueAxis2'.$itv.'.minimum = 0;
                       valueAxis2'.$itv.'.maximum = 2;
                       valueAxis2'.$itv.'.labelsEnabled = false;
                       chart'.$itv.'.addValueAxis(valueAxis2'.$itv.');
        
                       // third value axis (on the left, detached)
                       var valueAxis3'.$itv.' = new AmCharts.ValueAxis();
                       valueAxis3'.$itv.'.offset = 50; // this line makes the axis to appear detached from plot area
                       valueAxis3'.$itv.'.gridAlpha = 0;
                       valueAxis3'.$itv.'.axisColor = "#B0DE09";
                       valueAxis3'.$itv.'.axisThickness = 2;
                       valueAxis3'.$itv.'.minimum = 0;
                       valueAxis3'.$itv.'.maximum = 180;
                       //valueAxis3.unit = "Kmph";                       
                       chart'.$itv.'.addValueAxis(valueAxis3'.$itv.');
                       
                       // third value axis (on the left, detached)
                       var valueAxis4'.$itv.' = new AmCharts.ValueAxis();
                       valueAxis4'.$itv.'.offset = 100; // this line makes the axis to appear detached from plot area
                       valueAxis4'.$itv.'.gridAlpha = 0;
                       valueAxis4'.$itv.'.axisColor = "#d1655d";
                       valueAxis4'.$itv.'.axisThickness = 2;
                       valueAxis4'.$itv.'.minimum = -55;
                       valueAxis4'.$itv.'.maximum = 55;
                       //valueAxis4.unit = "C";                       
                       chart'.$itv.'.addValueAxis(valueAxis4'.$itv.');
                       
                       // fifth value axis (on the right)
                       var valueAxis5'.$itv.' = new AmCharts.ValueAxis();
                       valueAxis5'.$itv.'.position = "right"; // this line makes the axis to appear on the right
                       valueAxis5'.$itv.'.axisColor = "#2A0CD0";
                       valueAxis5'.$itv.'.gridAlpha = 0;
                       valueAxis5'.$itv.'.axisThickness = 2;
                       //valueAxis5'.$itv.'.minimum = 0;
                       //valueAxis5'.$itv.'.maximum = 99999;
                     //  valueAxis5'.$itv.'.labelsEnabled = false;
                       chart'.$itv.'.addValueAxis(valueAxis5'.$itv.');
        
        
                       // GRAPHS
                       // first graph
                       var graph1'.$itv.' = new AmCharts.AmGraph();
                       graph1'.$itv.'.valueAxis = valueAxis1'.$itv.'; // we have to indicate which value axis should be used
                       graph1'.$itv.'.title = "Fuels(Ltrs)";
                       graph1'.$itv.'.valueField = "fuelltr";
                       graph1'.$itv.'.bullet = "round";
                       graph1'.$itv.'.hideBulletsCount = 30;
                       graph1'.$itv.'.bulletBorderThickness = 1;
                       graph1'.$itv.'.lineThickness = 2;
                       graph1'.$itv.'.balloonText = "Fuel: <b>[[fuelltr]]</b> Ltrs, <b>[[fuel]]</b> %";
                       chart'.$itv.'.addGraph(graph1'.$itv.');
        
                       // second graph
                       var graph2'.$itv.' = new AmCharts.AmGraph();
                       graph2'.$itv.'.valueAxis = valueAxis2'.$itv.'; // we have to indicate which value axis should be used
                       graph2'.$itv.'.title = "Ignition";
                       graph2'.$itv.'.valueField = "acc";
                       graph2'.$itv.'.bullet = "square";
                       graph2'.$itv.'.hideBulletsCount = 30;
                       graph2'.$itv.'.bulletBorderThickness = 1;
                       graph2'.$itv.'.fillColorsField = "#FCD202";
                       graph2'.$itv.'.fillAlphas = 0.3;
                       graph2'.$itv.'.balloonFunction = adjustBalloonText;
                       chart'.$itv.'.addGraph(graph2'.$itv.');
        
                       // third graph
                       var graph3'.$itv.' = new AmCharts.AmGraph();
                       graph3'.$itv.'.valueAxis = valueAxis3'.$itv.'; // we have to indicate which value axis should be used
                       graph3'.$itv.'.valueField = "speed";
                       graph3'.$itv.'.title = "Speed(Kmph)";
                       graph3'.$itv.'.bullet = "triangleUp";
                       graph3'.$itv.'.hideBulletsCount = 30;
                       graph3'.$itv.'.bulletBorderThickness = 1;
                       graph3'.$itv.'.fillColorsField = "#B0DE09";
                       graph3'.$itv.'.fillAlphas = 0.3;
                       graph3'.$itv.'.balloonText = "Speed: <b>[[value]]</b> Kmph";
                       chart'.$itv.'.addGraph(graph3'.$itv.');
                       
                       // fourth graph
                       var graph4'.$itv.' = new AmCharts.AmGraph();
                       graph4'.$itv.'.valueAxis = valueAxis4'.$itv.'; // we have to indicate which value axis should be used
                       graph4'.$itv.'.valueField = "temp1";
                       graph4'.$itv.'.title = "Temprature (C)";
                       graph4'.$itv.'.bullet = "triangleDown";
                       graph4'.$itv.'.hideBulletsCount = 2;
                       graph4'.$itv.'.bulletBorderThickness = 1;
                       //graph4.lineColor = "#D1655D";
                       //graph4.fillColorsField = "#D1655D";
                       //graph4.fillAlphas = 0.3;
                       graph4'.$itv.'.balloonText = "Temp: <b>[[value]] &deg;</b>C";
                       chart'.$itv.'.addGraph(graph4'.$itv.');                       
                       
					    // fifth graph
                         var graph5'.$itv.' = new AmCharts.AmGraph();
                       graph5'.$itv.'.valueAxis = valueAxis5'.$itv.'; // we have to indicate which value axis should be used
                       graph5'.$itv.'.valueField = "mileage";
                       graph5'.$itv.'.title = "Kmph";
                       graph5'.$itv.'.bullet = "round";
                       graph5'.$itv.'.hideBulletsCount = 2;
                       graph5'.$itv.'.bulletBorderThickness = 1;
                      //graph5'.$itv.'.fillColorsField = "#2A0CD0";
                      // graph5'.$itv.'.fillAlphas = 0.3;
                       graph5'.$itv.'.balloonText = "Mileage: <b>[[value]]</b> Kmph";
                       chart'.$itv.'.addGraph(graph5'.$itv.');
					   
                       // CURSOR
                       var chartCursor'.$itv.' = new AmCharts.ChartCursor();
                       chartCursor'.$itv.'.cursorAlpha = 0.1;
                       chartCursor'.$itv.'.fullWidth = true;
                       chartCursor'.$itv.'.categoryBalloonDateFormat = "JJ:NN:SS, DD MMM YYYY",
                       chart'.$itv.'.addChartCursor(chartCursor'.$itv.');
        
                       // SCROLLBAR
                       var chartScrollbar'.$itv.' = new AmCharts.ChartScrollbar();
                       chartScrollbar'.$itv.'.graph = graph1'.$itv.';
                       chartScrollbar'.$itv.'.scrollbarHeight = 20;
                       chart'.$itv.'.addChartScrollbar(chartScrollbar'.$itv.');
        
                       // LEGEND
                       var legend'.$itv.' = new AmCharts.AmLegend();
                       legend'.$itv.'.marginLeft = 110;
                       legend'.$itv.'.useGraphSettings = true;
                       chart'.$itv.'.addLegend(legend'.$itv.');
        
                       // WRITE
                       chart'.$itv.'.write("chartdiv'.$itv.'");
                   });
                   
                   function adjustBalloonText(graphDataItem, graph){
                        
                           var value = graphDataItem.values.value;

                            if(value == 1){
                                return "Acc: <b>On</b>";
                            }
                            else{
                                return "Acc: <b>Off</b>";
                            }
                   }
                    
                   // this method is called when chart is first inited as we listen for "dataUpdated" event
                   function zoomChart() {
                       // different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
                       //chart.zoomToIndexes(0, 100);
                   }
                </script>';              
                
        $result .= '<div id="chartdiv'.$itv.'" style="width: 100%; height: 300px;"></div>';
    

		return $result;
	}    
	
	
	function fndblogemail($type)
	{
		try{
			global $ms, $gsValues;
		global $_SESSION,$user_id;
	
		$q="INSERT INTO emaillog ( datetime, user_id, count,type) VALUES ('".gmdate("Y-m-d h:m:s")."',".$user_id.",1,'".$type."')";
		$result = mysqli_query($ms,$q);
		
		}catch (Exception $e){}
	}
	
	
	function reportsGenerateroundtrip($imei, $dtf, $dtt, $speed_limit, $stop_duration,$zone_ids,$zone_idsv,$show_coordinates, $show_addresses, $zones_addresses,$format="") //Trip Wise Report
    {
    	global $_SESSION, $la, $user_id;
    	$result = 	'';
    	$result .= '<table class="report" width="100%" >
				<tr align="center">
					<th>'.$la['SINO'].'</th>
				
					<th>'.$la['ROUTE_START'].'</th>
					<th>'.$la['ROUTE_END'].'</th>	
					<th>'.$la['AROUTE_START'].'</th>
					<th>'.$la['AROUTE_END'].'</th>	
					<th>'.$la['AVG_SPEED'].'</th>
					<th>'.$la['TOP_SPEED'].'</th>										
					<th>'.$la['TAKENKM'].'</th>
					<th>'.$la['DURATION'].'</th>
					<th>'.$la['OVERSPEED_COUNT'].'</th>					
					<th>'.$la['OVRTIMEPARKING'].'</th>
					<th>'.$la['VIEW_DETAIL'].'</th>
				</tr>';
    		
    	$zone_ids = explode(",", $zone_ids);
    	$zone_idsv = explode(",", $zone_idsv);

    	if(count($zone_ids)>0 && count($zone_idsv)>0)
    	{

    		$data = getRoundTrip($imei,($dtf),($dtt), $stop_duration, true,$speed_limit,$zone_ids[0],$zone_idsv[0]);
    	    		
    		if (count($data['trip']) == 0)
    		{
    			$result .= '<tr><td align="center" colspan="17">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr>';
    		}
    		else
    		{

			 	$vehicleno=getObjectName($imei);
    			for ($i=0; $i<count($data['trip']); $i++)
    			{

    				$result .= '<tr>
						<td align="center">'.($i+1).'</td>
						
						<td align="center">'.$data['trip'][$i][1].'</td>
						<td align="center">'.$data['trip'][$i][2].'</td>
						<td align="center">'.convUserTimezone($data['trip'][$i][3]).'</td>
						<td align="center">'.convUserTimezone($data['trip'][$i][4]).'</td>
						<td align="center">'.$data['trip'][$i][5].'</td>
						<td align="center">'.$data['trip'][$i][11].'</td>
						<td align="center">'.$data['trip'][$i][6].'</td>
						<td align="center">'.$data['trip'][$i][7].'</td>
						<td align="center">'.$data['trip'][$i][8].'</td>
						<td align="center">'.$data['trip'][$i][9].'</td>';
    				
    				
    				if($data['trip'][$i][10]!=0)
    				$result .= '<td align="center"><a href="#" class="pitcher" data-prod-cat="1"> + </a></td>';
    				else
    				$result .= '<td align="center">NA</td>';
    				$result .= '</tr>';

    				if(isset($data['trip'][$i][10]))
    				{
    					if($format=="html")
					$result .= '<tr class="cat1" style="display:none" >';
					else
					$result .= '<tr class="cat1" >';
					
					$result .= '<td width="100%"  align="center" colspan="18">
						<table border="1" width="100%" >
						<tr  style="    font-weight: bold;padding: 2px;border: 1px solid #000000;color: white;background-color: #5C5D5C;" ><td>'.$la['ZONE_NAME'].'</td><td>'.$la['TIME'].'</td><td>'.$la['SPEED'].'</td></tr>';

    					$roe=$data['trip'][$i][10];
    					for ($ievs=0; $ievs<count($roe);$ievs++)
    					{
    						if(($roe[$ievs][8]=="Zone_In" || $roe[$ievs][8]=="Zone_Out") || $roe[$ievs][8]=="zone_in" || $roe[$ievs][8]=="zone_out")
    						$result .= '<tr><td>'.$roe[$ievs][0].'</td><td>'. $roe[$ievs][1].'</td><td><a href="http://maps.google.com/maps?q='.$roe[$ievs][2].','.$roe[$ievs][3].'&t=m" target="_blank">'.reportsGetPossition($roe[$ievs][2], $roe[$ievs][3], $show_coordinates, $show_addresses, $zones_addresses).'</a></td><td>'.$roe[$ievs][6].'</td></tr>';
    							
    					}
    					$result .= '</table></td></tr>';
    				}

    			}

    		}
    	}
    	else
    	{
    		$result .= '<tr>
						<td align="center" colspan="17">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td>
						</tr>';
    	}
    		
    	$result .= '</table>';

    	return $result;
    }

	
    function reportGenerateTripWiseDaily_print($imei, $dtf, $dtt, $speed_limit, $stop_duration,$show_coordinates, $show_addresses, $zones_addresses,$format="") //Trip Wise Report
	{
		global $_SESSION, $la, $user_id;
		
	
		$result = 	'';
			
		$result .= '<table class="report" width="100%" >
					<tr align="center">
					<th>'.$la['SINO'].'</th>
				
					<th>'.$la['TRIPNAME'].'</th>
					<th>'.$la['HOTSPOTNAME'].'</th>
					<th>'.$la['DATE'].'</th>
					<th>'.$la['ROUTE_START'].'</th>
					<th>'.$la['ROUTE_END'].'</th>	
					<th>'.$la['PROUTE_START'].'</th>
					<th>'.$la['PROUTE_END'].'</th>	
					<th>'.$la['AROUTE_START'].'</th>
					<th>'.$la['AROUTE_END'].'</th>	
					<th>'.$la['AVG_SPEED'].'</th>
					<th>'.$la['DELAY'].'</th>								
					<th>'.$la['TAKENKM'].'</th>
					<th>'.$la['DURATION'].'</th>
					<th>'.$la['OVERSPEED_COUNT'].'</th>					
					<th>'.$la['OVRTIMEPARKING'].'</th>
					<th>'.$la['VIEW_DETAIL'].'</th>
				   </tr>';
				
		
			$data=reportGenerateTripWiseDaily($imei, $dtf, $dtt,  $speed_limit, $stop_duration);
			//$data = getTRIPWISE_Daily($imei, $dtf, $dtt, $stop_duration, true,$speed_limit);
			
			
			if (count($data['trip']) == 0)
			{
				$result .= '<tr>
						<td align="center" colspan="17">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td>
						</tr>';
			}
			else
			{
			
			
		for ($i=0; $i<count($data['trip']); ++$i)
		{
				$result .= '<tr><td align="center">'.$data['trip'][$i][0].'</td>';
						
				if($data['trip'][$i][20]=="Yes")
						$result .= '<td align="center">'.$data['trip'][$i][3].'</td>';
			    else
						$result .= '<td  style="background:red;color:white;"  align="center">'.$data['trip'][$i][3].'</td>';
						
						$result .= '<td align="center">'.$data['trip'][$i][4].'</td>
						<td align="center">'.$data['trip'][$i][5].'</td>
						<td align="center">'.$data['trip'][$i][6].'</td>
						<td align="center">'.$data['trip'][$i][7].'</td>
						<td align="center">'.$data['trip'][$i][8].'</td>
						<td align="center">'.$data['trip'][$i][9].'</td>
						<td align="center">'.($data['trip'][$i][10]).'</td>
						<td align="center">'.($data['trip'][$i][11]).'</td>
						<td align="center">'.$data['trip'][$i][12].'</td>
						<td align="center">'.$data['trip'][$i][13].'</td>
						<td align="center">'.$data['trip'][$i][14].'</td>
						<td align="center">'.$data['trip'][$i][15].'</td>
						<td align="center">'.$data['trip'][$i][16].'</td>
						<td align="center">'.$data['trip'][$i][17].'</td>';
						if($data['trip'][$i][18]!=0)
						$result .= '<td align="center"><a href="#" class="pitcher" data-prod-cat="1"> + </a></td>';
						else
						$result .= '<td align="center">NA</td>';
						$result .= '</tr>';
				
				if($data['trip'][$i][18]!=0)
				{
					$aryzonedata=array();
					$arrzone=$data['trip'][$i][19];
					
				
					
					if($format=="html")
					$result .= '<tr class="cat1" style="display:none" >';
					else
					$result .= '<tr class="cat1" >';
					$chk_finish=false;
					$result .= '<td width="100%"  align="center" colspan="18">
						<table border="1" width="100%" >
						<tr  style="    font-weight: bold;padding: 2px;border: 1px solid #000000;color: white;background-color: #5C5D5C;" ><td>'.$la['ZONE_NAME'].'</td><td>'.$la['TIME'].'</td><td>'.$la['POSITION'].'</td><td>'.$la['SPEED'].'</td></tr>';
						for($iz=0;$iz<count($arrzone);$iz++)
						{
							if(isset($arrzone[$iz]))
							{
								$arrzonedata=$arrzone[$iz];
								if(!$chk_finish)
								$result .= '<tr><td>'.$arrzonedata["event_desc"].'</td><td>'. ($arrzonedata["dt_tracker"]).'</td><td><a href="http://maps.google.com/maps?q='.$arrzonedata['lat'].','.$arrzonedata['lng'].'&t=m" target="_blank">'.reportsGetPossition($arrzonedata['lat'], $arrzonedata['lng'], $show_coordinates, $show_addresses, $zones_addresses).'</a></td><td>'. $arrzonedata["speed"].'</td></tr>';
								if($arrzonedata["startend"]=="End")
								$chk_finish=true;
							}
						}						
						$result .= '</table></td></tr>';
				}
				
		}
		
		}
		
		$result .= '</table>';
		
		return $result;
	}
	

    function reportsOfflineFuel($imei, $dtf, $dtt, $speed_limit, $stop_duration,$format="") //Trip Wise Report
	{
		global $_SESSION, $la, $user_id;
		
	
		$result = 	'';
			
		$result .= '<h5 style="align:center;">'.$la['OFFLINE_FULE'].'</h5><br><table class="report" width="100%" >
					<tr align="center">	
					<th>'. $la['CREATE_DATE'].'</th>
					<th>'.$la['IMEI'].'</th>
					<th>'.$la['STARTTIME'].'</th>
					<th>'.$la['ENDTIME'].'</th>
					<th>'.$la['LATITUDE'].'</th>
					<th>'. $la['LONGITUDE'].'</th>
					<th>'.$la['BEFORE_VALUE'].'</th>
					<th>'. $la['AFTER_VALUE'].'</th>
					<th>'.$la['FILLED'].'</th>
					<th>'.$la['MILEAGE_START'].'</th>
					<th>'. $la['MILEAGE_END'].'</th>
					<th>KMPL</th>
				   </tr>';
				
		
			$data=reportGenerateOfflineFule($imei, $dtf, $dtt,  $speed_limit, $stop_duration);
			//$data = getTRIPWISE_Daily($imei, $dtf, $dtt, $stop_duration, true,$speed_limit);
			
			
			if (count($data['trip']) == 0)
			{
				$result .= '<tr>
						<td align="center" colspan="17">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td>
						</tr>';
			}
			else
			{
			
			
		for ($i=0; $i<count($data['trip']); ++$i)
		{
				$result .= '<tr>						
						<td align="center">'.$data['trip'][$i][2].'</td>
						<td align="center">'.$data['trip'][$i][3].'</td>
						<td align="center">'.convUserTimezone($data['trip'][$i][4]).'</td>
						<td align="center">'.convUserTimezone($data['trip'][$i][5]).'</td>
						<td align="center">'.$data['trip'][$i][6].'</td>
						<td align="center">'.($data['trip'][$i][7]).'</td>
						<td align="center">'.round($data['trip'][$i][8],3).'</td>
						<td align="center">'.round($data['trip'][$i][9],3).'</td>
						<td align="center">'.round($data['trip'][$i][10],3).'</td>
						<td align="center">'.$data['trip'][$i][11].'</td>
						<td align="center">'.$data['trip'][$i][12].'</td>';

				if (count($data['trip'])>$i+1){
					$kmpl=($data['trip'][$i+1][11]-$data['trip'][$i][11])/$data['trip'][$i+1][10];
					
				}else{
					$kmpl='-';
				}
				$result .= '<td align="center">'.round($kmpl,1).'</td>';

				if($data['trip'][$i][0]!=0)
				{
					$aryzonedata=array();
					$arrzone=$data['trip'][$i][19];
					
				
					
					if($format=="html")
					$result .= '<tr class="cat1" style="display:none" >';
					else
					$result .= '<tr class="cat1" >';
					$chk_finish=false;
					$result .= '<td width="100%"  align="center" colspan="18">
						<table border="1" width="100%" >
						<tr  style="    font-weight: bold;padding: 2px;border: 1px solid #000000;color: white;background-color: #5C5D5C;" ><td>'.$la['ZONE_NAME'].'</td><td>'.$la['TIME'].'</td><td>'.$la['SPEED'].'</td></tr>';
						$result .= '</table></td></tr>';
				}
				
		}
		
		}
		
		$result .= '</table>';
		$result .= '<h5>'.$la['FUEL_THEFT'].'</h5><br><table class="report" width="100%" >
					<tr align="center">	
					<th>'. $la['CREATE_DATE'].'</th>
					<th>'.$la['IMEI'].'</th>
					<th>'.$la['STARTTIME'].'</th>
					<th>'.$la['ENDTIME'].'</th>
					<th>'.$la['LATITUDE'].'</th>
					<th>'. $la['LONGITUDE'].'</th>
					<th>'.$la['BEFORE_VALUE'].'</th>
					<th>'. $la['AFTER_VALUE'].'</th>
					<th>'.$la['THEFT'].'</th>
					<th>'.$la['MILEAGE_START'].'</th>
					<th>'. $la['MILEAGE_END'].'</th>
				   </tr>';
				 
		
			$data=reportGenerateOfflineFuleTheft($imei, $dtf, $dtt,  $speed_limit, $stop_duration);
			//$data = getTRIPWISE_Daily($imei, $dtf, $dtt, $stop_duration, true,$speed_limit);
			
			
			if (count($data['trip']) == 0)
			{
				$result .= '<tr>
						<td align="center" colspan="17">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td>
						</tr>';
			}
			else
			{
			
			
		for ($i=0; $i<count($data['trip']); ++$i)
		{
				$result .= '<tr>						
						<td align="center">'.$data['trip'][$i][2].'</td>
						<td align="center">'.$data['trip'][$i][3].'</td>
						<td align="center">'.$data['trip'][$i][4].'</td>
						<td align="center">'.$data['trip'][$i][5].'</td>
						<td align="center">'.$data['trip'][$i][6].'</td>
						<td align="center">'.($data['trip'][$i][7]).'</td>
						<td align="center">'.round($data['trip'][$i][8],3).'</td>
						<td align="center">'.round($data['trip'][$i][9],3).'</td>
						<td align="center">'.round($data['trip'][$i][10],3).'</td>
						<td align="center">'.$data['trip'][$i][11].'</td>
						<td align="center">'.$data['trip'][$i][12].'</td>';
				
				if($data['trip'][$i][0]!=0)
				{
					$aryzonedata=array();
					$arrzone=$data['trip'][$i][19];
					
				
					
					if($format=="html")
					$result .= '<tr class="cat1" style="display:none" >';
					else
					$result .= '<tr class="cat1" >';
					$chk_finish=false;
					$result .= '<td width="100%"  align="center" colspan="18">
						<table border="1" width="100%" >
						<tr  style="    font-weight: bold;padding: 2px;border: 1px solid #000000;color: white;background-color: #5C5D5C;" ><td>'.$la['ZONE_NAME'].'</td><td>'.$la['TIME'].'</td><td>'.$la['SPEED'].'</td></tr>';
						$result .= '</table></td></tr>';
				}
				
		}
		
		}
		
		$result .= '</table>';
		
		return $result;
	}
	
	
    function reportGenerateTripWise_print($imei, $dtf, $dtt, $speed_limit, $stop_duration,$show_coordinates, $show_addresses, $zones_addresses,$format="") //Trip Wise Report
	{
		global $_SESSION, $la, $user_id,$gsValues;
		$result="";
		$result .= '<table class="report" width="100%" >
					<tr align="center">
					<th>'.$la['SINO'].'</th>
					<th>'.$la['TRIPNAME'].'</th>
					<th>'.$la['HOTSPOTNAME'].'</th>
					<th>'.$la['DATE'].'</th>
					<th>'.$la['ROUTE_START'].'</th>
					<th>'.$la['ROUTE_END'].'</th>	
					<th>'.$la['PROUTE_START'].'</th>
					<th>'.$la['PROUTE_END'].'</th>	
					<th>'.$la['AROUTE_START'].'</th>
					<th>'.$la['AROUTE_END'].'</th>	
					<th>'.$la['AVG_SPEED'].'</th>
					<th>'.$la['DELAY'].'</th>								
					<th>'.$la['TAKENKM'].'</th>
					<th>'.$la['DURATION'].'</th>
					<th>'.$la['OVERSPEED_COUNT'].'</th>					
					<th>'.$la['OVRTIMEPARKING'].'</th>
					<th>'.$la['VIEW_DETAIL'].'</th>
				   </tr>';
				
		
			$data=reportGenerateTripWise($imei, $dtf, $dtt,  $speed_limit, $stop_duration);
			//$data = getTRIPWISE_Daily($imei, $dtf, $dtt, $stop_duration, true,$speed_limit);
			
			if (count($data['trip']) == 0)
			{
				$result .= '<tr>
						<td align="center" colspan="17">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td>
						</tr>';
			}
			else
			{
				
		for ($i=0; $i<count($data['trip']); ++$i)
		{
				$result .= '<tr>
						<td align="center">'.$data['trip'][$i]["sno"].'</td>';
				
						if($data['trip'][$i]["end"]=="Yes")
						$result .= '<td align="center">'.$data['trip'][$i]["tripname"].'</td>';
						else
						$result .= '<td style="background:red;color:white;" align="center">'.$data['trip'][$i]["tripname"].'</td>';
						
						$result .= '<td align="center">'.$data['trip'][$i]["hotspot"].'</td>
						<td align="center">'.$data['trip'][$i]["create"].'</td>
						<td align="center">'.$data['trip'][$i]["fromplace"].'</td>
						<td align="center">'.$data['trip'][$i]["toplace"].'</td>
						<td align="center">'.$data['trip'][$i]["fromtime"].'</td>
						<td align="center">'.$data['trip'][$i]["totime"].'</td>
						<td align="center">'.$data['trip'][$i]["Afromtime"].'</td>
						<td align="center">'.$data['trip'][$i]["Atotime"].'</td>
						<td align="center">'.$data['trip'][$i]["avg_speed"].'</td>
						<td align="center">'.$data['trip'][$i]["delay"].'</td>
						<td align="center">'.$data['trip'][$i]["route_length"].'</td>
						<td align="center">'.$data['trip'][$i]["taken_time"].'</td>
						<td align="center">'.$data['trip'][$i]["ovr_speed"].'</td>
						<td align="center">'.$data['trip'][$i]["engine_idle"].'</td>';
						if($data['trip'][$i]["event_count"]!=0)
						$result .= '<td align="center"><a href="#" class="pitcher" data-prod-cat="1"> + </a></td>';
						else
						$result .= '<td align="center">NA</td>';
						$result .= '</tr>';
				
				if($data['trip'][$i]["event_count"]!=0)
				{
					$aryzonedata=array();
					$arrzone=$data['trip'][$i]["event_list"];
					//echo json_encode($arrzone);
					//echo "<br><br><br><br>";
					
					if($format=="html")
					$result .= '<tr class="cat1" style="display:none" >';
					else
					$result .= '<tr class="cat1" >';
					$st_date = "";
					$result .= '<td width="100%"  align="center" colspan="18">
						<table border="1" width="100%" >
						<tr  style="    font-weight: bold;padding: 2px;border: 1px solid #000000;color: white;background-color: #5C5D5C;" ><td>'.$la['ZONE_NAME'].'</td><td>'.$la['TIME'].'</td><td>'.$la['POSITION'].'</td><td>'.$la['SPEED'].'</td><td>'.$la['TAKENKM'].'</td></tr>';
						for($iz=0;$iz<count($arrzone);$iz++)
						{
							if(isset($arrzone[$iz]))
							{
								$arrzonedata=$arrzone[$iz];
								//if(count($arrzonedata)==6)
								{
									if ($arrzonedata[11]=="Scheduled")
									{
										$taken_km = 0;
										if($iz!=0)
										{
											$taken_km = $arrzonedata[5] - $arrzone[$iz-1][5];
											$ed_date = convUserUTCTimezone($arrzonedata[1]);
											$event_route = getRoute($imei, $st_date, $ed_date, $stop_duration, true,true);
											$taken_km = $event_route['route_length'];
											$st_date = $ed_date;
										}else{
											$st_date = convUserUTCTimezone($arrzonedata[1]);
										}

										$result .= '<tr><td>'.$arrzonedata[0].'</td><td>'. ($arrzonedata[1]).'</td><td><a href="http://maps.google.com/maps?q='.$arrzonedata[2].','.$arrzonedata[3].'&t=m" target="_blank">'.reportsGetPossition($arrzonedata[2], $arrzonedata[3], $show_coordinates, $show_addresses, $zones_addresses).'</a></td><td>'. $arrzonedata[6].'</td><td>'.$taken_km.'</td></tr>';
								 		$aryzonedata[]=$arrzonedata[1];
									}
								}
							}
						}						
						$result .= '</table></td></tr>';
				}
				
		}
		
		}
		
		$result .= '</table>';
		
		return $result;
	}
		
	function reportsGenerateRfidData($imei, $dtf, $dtt,  $show_coordinates, $show_addresses, $zones_addresses, $data_items) //EVENTS
	{
		global $ms,$_SESSION, $la, $user_id,$gsValues;
		$user_id="";
		if ($_SESSION["privileges"] == 'subuser')
		{
			$user_id = $_SESSION["manager_id"];
		}
		else
		{
			$user_id = $_SESSION["user_id"];
		}
		
		
		$result = '<table class="report" width="100%" >
					<tr align="center">
						<th>'.$la['TIME'].'</th>';
		
	
		$result .= '<th>'.$la['EMPLOYEE_NAME'].'</th> <th>Employee Id</th>';
		if($user_id=='1711' || $user_id==1711){
			$result .= '<th>SNR Number</th>';
		}
		if($user_id=='1564' || $user_id==1564){
			$result .= '<th>User Access</th>';
		}				
		$result .= '<th>'.$la['RFIDID'].'</th>
						<th>'.$la['POSITION'].'</th>
					</tr>';
					
		
		//$q = "SELECT gr.dt_swipe,gr.lat,gr.lng,gr.rfid,gr.snr_number,gr.rfid_hex,gr.rfid_snr,cm.empid,cm.firstname,gr.access FROM gs_rfid_swipe_data gr left join csnmast cm on cm.cardno=gr.rfid WHERE gr.imei='".$imei."' AND gr.dt_swipe BETWEEN '".$dtf."' AND '".$dtt."' ORDER BY gr.dt_swipe ASC";
		$q = "SELECT gr.dt_swipe,gr.lat,gr.lng,gr.rfid,gr.snr_number,gr.rfid_hex,gr.rfid_snr,cm.sid as empid,cm.name as firstname,gr.access FROM gs_rfid_swipe_data gr left join dstudent cm on cm.rfid=gr.rfid WHERE gr.imei='".$imei."' AND gr.dt_swipe BETWEEN '".$dtf."' AND '".$dtt."' ORDER BY gr.dt_swipe ASC";
		
		$r = mysqli_query($ms,$q);


		$count = mysqli_num_rows($r);
		
		if ($count == 0)
		{
			return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
		
		$total_events = array();
		
		while($swipe_data=mysqli_fetch_array($r,MYSQLI_ASSOC))
		{
			$result .= '<tr align="center">
				<td>'.convUserTimezone($swipe_data['dt_swipe']).'</td>';

			$result .= '<td>'.$swipe_data['firstname'].'</td><td>'.$swipe_data['empid'].'</td>';
			
			if($user_id=='1711' || $user_id==1711){
				if($swipe_data['rfid_snr']==''){
					$rfid=dechex($swipe_data['rfid']);
					$hex=str_split($rfid, 2);
					if(gettype($hex)=='array'){
						$hex = array_reverse($hex);
						$hex=strtoupper(join("",$hex));
						// $hex=substr(str_repeat(0, 8).$hex, - 8);
						$swipe_data['rfid_snr']=$hex;
					}
				}
				$result .= '<td>'.$swipe_data['rfid_snr'].'</td>';
			}
			if($user_id=='1564' || $user_id==1564){
				if($swipe_data['access']=='1'){
					$result .= '<td style="color:green;">Access</td>';
				}else{
					$result .= '<td style="color:red;">Access Denied</td>';
				}
			}
			$result .= '<td>'.$swipe_data['rfid'].'</td>
				<td>'.reportsGetPossition($swipe_data['lat'], $swipe_data['lng'], $show_coordinates,$show_addresses, $zones_addresses).'</td>
			</tr>';
			
			if (isset($total_events[$swipe_data['rfid']]))
			{
				$total_events[$swipe_data['rfid']]++;
			}
			else
			{
				$total_events[$swipe_data['rfid']] = 1;
			}
		}
		
		$result .= '</table><br/>';
		
		ksort($total_events);
		
		
		return $result;
	}
		
	function reportsGenerateConsolidatedRfidDataNew($imeis, $dtf, $dtt, $show_coordinates, $show_addresses, $zones_addresses, $data_items)
{
    global $ms, $_SESSION, $la, $user_id, $gsValues;

    $user_id = ($_SESSION["privileges"] == 'subuser') ? $_SESSION["manager_id"] : $_SESSION["user_id"];

    if (!is_array($imeis) || count($imeis) === 0) {
        return '<table><tr><td>No IMEIs provided.</td></tr></table>';
    }

    $escaped_imeis = array_map(function($imei) use ($ms) {
        return "'" . mysqli_real_escape_string($ms, $imei) . "'";
    }, $imeis);
    $imei_list = implode(',', $escaped_imeis);

    $q = "
        SELECT 
            gr.dt_swipe,
            gr.lat, gr.lng, gr.rfid,
            go.name AS vehicle_no
        FROM gs_rfid_swipe_data gr
        LEFT JOIN gs_objects go ON go.imei = gr.imei
        WHERE gr.imei IN ($imei_list)
            AND gr.dt_swipe BETWEEN '".$dtf."' AND '".$dtt."'
        ORDER BY gr.dt_swipe ASC
    ";

    $r = mysqli_query($ms, $q);
    $count = mysqli_num_rows($r);

    if ($count == 0) {
        return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
    }

    // Organize by date -> rfid+vehicle
    $date_map = [];

    while ($row = mysqli_fetch_assoc($r)) {
        $dt_swipe_local = convUserTimezone($row['dt_swipe']); // Convert to user timezone

        $swipe_date = date('Y-m-d', strtotime($dt_swipe_local));
        $swipe_time = date('H:i:s', strtotime($dt_swipe_local));
        $key = $row['rfid'] . '_' . $row['vehicle_no'];

        if (!isset($date_map[$swipe_date])) {
            $date_map[$swipe_date] = [];
        }

        if (!isset($date_map[$swipe_date][$key])) {
            $date_map[$swipe_date][$key] = [
                'vehicle_no' => $row['vehicle_no'],
                'rfid'       => $row['rfid'],
                'date'       => $swipe_date,
                'swipes'     => []
            ];
        }

        if (count($date_map[$swipe_date][$key]['swipes']) < 4) {
            $date_map[$swipe_date][$key]['swipes'][] = [
                'time' => $swipe_time,
                'loc'  => reportsGetPossition($row['lat'], $row['lng'], $show_coordinates, $show_addresses, $zones_addresses)
            ];
        }
    }

    // Generate HTML per date
    $result = '';
    foreach ($date_map as $date => $entries) {
        $result .= '<h3 style="margin-top:30px;">Date: ' . htmlspecialchars($date) . '</h3>';
        $result .= '<table class="report" width="100%" border="1" cellpadding="5" cellspacing="0">
            <tr align="center" style="background-color:#f2f2f2;">
                <th>SN</th>
                <th>Vehicle No</th>
                <th>RFID Tag</th>
                <th>Date</th>';

        for ($i = 1; $i <= 4; $i++) {
            $result .= "<th>Swipe $i</th><th>Loc$i</th>";
        }
        $result .= '</tr>';

        $sn = 1;
        foreach ($entries as $entry) {
            $result .= '<tr align="center">';
            $result .= '<td>' . $sn++ . '</td>';
            $result .= '<td>' . htmlspecialchars($entry['vehicle_no']) . '</td>';
            $result .= '<td>' . htmlspecialchars($entry['rfid']) . '</td>';
            $result .= '<td>' . htmlspecialchars($entry['date']) . '</td>';

            for ($i = 0; $i < 4; $i++) {
                if (isset($entry['swipes'][$i])) {
                    $result .= '<td>' . htmlspecialchars($entry['swipes'][$i]['time']) . '</td>';
                    $result .= '<td>' . $entry['swipes'][$i]['loc'] . '</td>';
                } else {
                    $result .= '<td></td><td></td>';
                }
            }
            $result .= '</tr>';
        }

        $result .= '</table>';
    }

    return $result;
}

		
	function reportGenerateTripWiseRFID_Mthd($imei, $dtf, $dtt, $speed_limit, $stop_duration, $show_coordinates, $show_addresses, $zones_addresses,$format,$emptyreport=false) //Trip Wise Report
	{
		global $_SESSION, $la, $user_id,$gsValues;
		$result="";
		$result .= '<table class="report" width="100%" >
					<tr align="center">
					<th>'.$la['SINO'].'</th>
					<th>'.$la['TRIPNAME'].'</th>
					<th>'.$la['HOTSPOTNAME'].'</th>
					<th>'.$la['DATE'].'</th>
					<th>'.$la['ROUTE_START'].'</th>
					<th>'.$la['ROUTE_END'].'</th>	
					<th>'.$la['PROUTE_START'].'</th>
					<th>'.$la['PROUTE_END'].'</th>	
					<th>'.$la['AROUTE_START'].'</th>
					<th>'.$la['AROUTE_END'].'</th>	
					<th>'.$la['AVG_SPEED'].'</th>
					<th>'.$la['DELAY'].'</th>								
					<th>'.$la['TAKENKM'].'</th>
					<th>'.$la['DURATION'].'</th>
					<th>'.$la['OVERSPEED_COUNT'].'</th>					
					<th>'.$la['OVRTIMEPARKING'].'</th>
					<th>'.$la['DAILY'].'</th>
					<th>'.$la['DEFAULTS'].'</th>
					<th>'.$la['EXTRA'].'</th>
					<th>'.$la['VIEW_DETAIL'].'</th>
				   </tr>';
				
		
			$data=reportGenerateTripWise_RFID($imei, $dtf, $dtt,  $speed_limit, $stop_duration,$emptyreport);
			//$data = getTRIPWISE_Daily($imei, $dtf, $dtt, $stop_duration, true,$speed_limit);
		
			if (count($data['trip']) == 0)
			{
				$result .= '<tr>
						<td align="center" colspan="21">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td>
						</tr>';
			}
			else
			{
				
		for ($i=0; $i<count($data['trip']); ++$i)
		{
			$resultcount="";
				$result .= '<tr>
						<td align="center">'.$data['trip'][$i]["sno"].'</td>';
				
						if($data['trip'][$i]["end"]=="Yes")
						$result .= '<td align="center">'.$data['trip'][$i]["tripname"].'</td>';
						else if($data['trip'][$i]["end"]=="Empty")
						$result .= '<td style="background:#909090;color:white;" align="center">'.$data['trip'][$i]["tripname"].'</td>';
						else 
						$result .= '<td style="background:red;color:white;" align="center">'.$data['trip'][$i]["tripname"].'</td>';
						
						$result .= '<td align="center">'.$data['trip'][$i]["hotspot"].'</td>
						<td align="center">'.$data['trip'][$i]["create"].'</td>
						<td align="center">'.$data['trip'][$i]["fromplace"].'</td>
						<td align="center">'.$data['trip'][$i]["toplace"].'</td>
						<td align="center">'.$data['trip'][$i]["fromtime"].'</td>
						<td align="center">'.$data['trip'][$i]["totime"].'</td>
						<td align="center">'.$data['trip'][$i]["Afromtime"].'</td>
						<td align="center">'.$data['trip'][$i]["Atotime"].'</td>
						<td align="center">'.$data['trip'][$i]["avg_speed"].'</td>
						<td align="center">'.$data['trip'][$i]["delay"].'</td>
						<td align="center">'.$data['trip'][$i]["route_length"].'</td>
						<td align="center">'.$data['trip'][$i]["taken_time"].'</td>
						<td align="center">'.$data['trip'][$i]["ovr_speed"].'</td>
						<td align="center">'.$data['trip'][$i]["engine_idle"].'</td>';
						if(isset($data['trip'][$i]["rfidlist"]) && count($data['trip'][$i]["rfidlist"])>0)
						{
							$arrrfidlst=$data['trip'][$i]["rfidlist"];
							$countO=0;$countN=0;$countD=0;
						for($iz=0;$iz<count($arrrfidlst);$iz++)
						{
							if( isset($gsValues['POST_RFID'][$user_id]) && $user_id==$gsValues['POST_RFID'][$user_id]["id"])
							{
								if($arrrfidlst[$iz]['extra']=="O")
								{
									$countO++;
									$resultcount .= '<tr align="center"  style=color:green;><td>'.($iz+1).'</td><td>'.convUserTimezone($arrrfidlst[$iz]['dt_swipe']).'</td>';
								}
								else if($arrrfidlst[$iz]['extra']=="N")
								{
									$countN++;
									$resultcount .= '<tr align="center" style=color:black;><td>'.($iz+1).'</td><td>'.convUserTimezone($arrrfidlst[$iz]['dt_swipe']).' Unauth</td>';
								}
								else
								{
									$countD++;
									$resultcount .= '<tr align="center" style=color:red;><td>'.($iz+1).'</td><td>'.convUserTimezone($arrrfidlst[$iz]['dt_swipe']).' Unauth</td>';
								}
								$resultcount .= '<td>'.$arrrfidlst[$iz]['firstname']." - Group : ".$arrrfidlst[$iz]['gname']." - Route : ".$arrrfidlst[$iz]['routeno'].'</td><td>'.$arrrfidlst[$iz]['empid'].'</td><td>'.$arrrfidlst[$iz]['rfid'].'</td>';
								$resultcount .= '<td>'.reportsGetPossition($arrrfidlst[$iz]['lat'], $arrrfidlst[$iz]['lng'], $show_coordinates,$show_addresses, $zones_addresses).'</td></tr>';
							} 
							else 
							{
								if($user_id=='1739'){
									// if ($arrrfidlst[$iz]['rfid']==$imei){
									// 	$col='green';
									// }else{
									// 	$col='red';
									// }
									$resultcount .= '<tr align="center" ><td>'.($iz+1).'</td><td>'.$arrrfidlst[$iz]['snr_number'].'</td><td>'.$arrrfidlst[$iz]['sid'].'</td><td>'.$arrrfidlst[$iz]['name'].'</td><td>'.$arrrfidlst[$iz]['dept_name'].'</td><td>'.$arrrfidlst[$iz]['phno'].'</td><td>'.convUserTimezone($arrrfidlst[$iz]['dt_swipe']).'</td>';
									$resultcount .= '<td>'.reportsGetPossition($arrrfidlst[$iz]['lat'], $arrrfidlst[$iz]['lng'], $show_coordinates,$show_addresses, $zones_addresses).'</td></tr>';
								}else{
									$resultcount .= '<tr align="center"><td>'.($iz+1).'</td><td>'.convUserTimezone($arrrfidlst[$iz]['dt_swipe']).'</td>';
									$resultcount .= '<td>'.$arrrfidlst[$iz]['dept_name'].'</td><td>'.$arrrfidlst[$iz]['section_name'].'</td><td>'.$arrrfidlst[$iz]['sid'].'</td><td>'.$arrrfidlst[$iz]['name'].'</td>';
									if($user_id=='1739'){
										$resultcount .= '<td>'.getpeopleSNRnumber($arrrfidlst[$iz]['rfid']).'</td>';
									}else{
										$resultcount .= '<td>'.$arrrfidlst[$iz]['rfid'].'</td>';

									}
									$resultcount .= '<td>'.reportsGetPossition($arrrfidlst[$iz]['lat'], $arrrfidlst[$iz]['lng'], $show_coordinates,$show_addresses, $zones_addresses).'</td></tr>';
								}
							}
						}
						
							$result .= '<td align="center">'.$countO.'</td><td align="center">'.$countD.'</td><td align="center">'.$countN.'</td>';
							
						}
						else 
						$result .= '<td align="center">0</td><td align="center">0</td><td align="center">0</td>';
						
						if($data['trip'][$i]["event_count"]!=0)
						{
							$result .= '<td align="center"><a href="#" class="pitcher" data-prod-cat="1"> + </a></td>';
						}
						else
						$result .= '<td align="center"><a href="#" class="pitcher" data-prod-cat="1"> + </a></td>';//$result .= '<td align="center">NA</td></tr>';
						
						$result .= '<tr class="cat1" ';
						if($format=="html")
						$result .= 'style="display:none" >';
						else 
						$result .= '>';
						
					$result .= '<td width="100%"  align="center" colspan="20">';
					
					if(isset($data['trip'][$i]["rfidlist"]) && count($data['trip'][$i]["rfidlist"])>0)
					{
						//if($user_id=='1739'){

							$result .= '<table class="report" width="100%" >
							<tr align="center">
							<th>'.$la['SINO'].'</th><th>'.$la['RFIDID'].'</th><th>'.$la['EMPLOYEEID'].'</th><th>'.$la['EMPLOYEE_NAME'].'</th><th>'.$la['DEPARTMENT'].'</th><th>'.$la['PHONE'].'</th><th>'.$la['TIME'].'</th><th>'.$la['POSITION'].'</th></tr>';

							$result .=$resultcount;
													
							$result .= '</table><br/>';

						//}
						//else{		
						//}
					}
					else
					{
						$result .= '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
					}	
						
				if($data['trip'][$i]["event_count"]!=0)
				{
					$aryzonedata=array();
					$arrzone=$data['trip'][$i]["event_list"];
					//echo json_encode($arrzone);
					//echo "<br><br><br><br>";
					//<tr class="cat1" style="display:none" >
					//	<td width="100%"  align="center" colspan="18">
					$result .= '
						<table border="1" width="100%" >
						<tr  style="    font-weight: bold;padding: 2px;border: 1px solid #000000;color: white;background-color: #5C5D5C;" ><td>'.$la['ZONE_NAME'].'</td><td>'.$la['TIME'].'</td><td>'.$la['SPEED'].'</td></tr>';
						for($iz=0;$iz<count($arrzone);$iz++)
						{
							if(isset($arrzone[$iz]))
							{
								$arrzonedata=$arrzone[$iz];
								//if(count($arrzonedata)==6)
								{
									if ($arrzonedata[11]=="Scheduled")
									{
										$result .= '<tr><td>'.$arrzonedata[0].'</td><td>'. ($arrzonedata[1]).'</td><td>'. $arrzonedata[6].'</td></tr>';
								 		$aryzonedata[]=$arrzonedata[1];
									}
								}
							}
						}						
						//$result .= '</table></td></tr>';
						$result .= '</table>';
				}
				
				$result .= '</td></tr>';
				
		}
		
		}
		
		$result .= '</table>';
		
		return $result;
	}
	
	function reportCSNDetails($show_coordinates, $show_addresses, $zones_addresses)
	{
		global $_SESSION, $la, $user_id,$gsValues;
		$result="";
			$mydata=GetCSN_Local($user_id);
  			if($mydata["Type"]=="S")
  			{
  				$csncount=$mydata['Mydata']["Count"];	
			$result .= 'Total Employee Count : '.$csncount.'<table class="report" width="100%" >
					<tr align="center">
						<th>Employee Id</th>
						<th>CSN No</th>
						<th>First Name</th>
						<th>Crew Name</th>
						<th>Route No</th>
					</tr>';
				
  				for($c=0;$c<$csncount;$c++)
  				{
  					$result .= '
					<tr align="center">
						<td>'.$mydata['Mydata']["List"][$c]["EMPID"].'</td>
						<td>'.$mydata['Mydata']["List"][$c]["CARDNO"].'</td>
						<td>'.$mydata['Mydata']["List"][$c]["FIRSTNAME"].'</td>
						<td>'.$mydata['Mydata']["List"][$c]["GNAME"].'</td>
						<td>'.$mydata['Mydata']["List"][$c]["ROUTENO"].'</td>
					</tr>';
  				}
  				
  				$result .= '<table>';
  			}
  			else 
  			{
  				$result .= '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
  			}
		return $result;
	}
		
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
					
					$total_engine_hours += (float) $engine_hours;
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

	function reportsGenerateVehicleDailyKM($imeis, $dtf, $dtt)
	{
		global $_SESSION, $la, $user_id;
		$result = '<table class="report" width="100%"><tr align="center">';
		$date_from = date('Y-m-d',strtotime($dtf)); 
		$date_from = strtotime($date_from);
		$date_to = date('Y-m-d',strtotime($dtt));
		$date_to = strtotime($date_to); 

		$result .= '<th>'.$la['OBJECT'].'</th>';
		$j=1;
		for ($i=$date_from; $i<$date_to; $i+=86400) {
		   ${'date_'.$j}=0;$j++;
		   $result .= "<th>".date("d-M", $i)."</th>";
		}

		$result .= '<th>Total KM</th></tr>';
		$totalkm=0;$dailytotalkm=0;

		foreach ($imeis as $k => &$imei){
			$data = getVehicleDailyKM($imei, $dtf, $dtt);			
			$result .= '<tr align="center">';			
			$result .= '<th>'.getObjectName($imei).'</th>';
			$objectkm=0;
			$l=1;
			foreach ($data as $key => $total) {
				${'date_'.$l}+=sprintf("%01.1f", $total["totalkm"]);				
				$l++;
				$objectkm+=sprintf("%01.1f", $total["totalkm"]);
				if($total["totalkm"]!=0){
					$result .= '<td><a style="color: black;" onclick="multipleReportDownload(\''.$imei.'\',\''.$total["date"].'\',\''.date('Y-m-d',strtotime('+1days',strtotime($total["date"]))).'\')">'.sprintf("%01.1f", $total["totalkm"]).'</a></td>';
				}else{
					$result .= '<td>0</td>';
				}
			}
			$totalkm+=$objectkm;
			$result .= '<th><a style="color: black;" onclick="multipleReportDownload(\''.$imei.'\',\''.$dtf.'\',\''.$dtt.'\')">'.$objectkm.'</a></th>';
			$result .= '</tr>';
		}

		$result .= '<tr align="center">';
		$result .= '<th>Total KM</th>';
		$l=1;
		for($k=0;$k<count($data);$k++){
			$dailytotalkm+=${'date_'.$l};
			$result .= '<th>'.${'date_'.$l}.'</th>';$l++;
		}
		if(sprintf("%01.0f",$totalkm)==sprintf("%01.0f",$dailytotalkm)){
			$result .= '<th><b>'.$totalkm.'</b></th>';
		}else{
			$result .= '<td style="font: -webkit-small-control;">'.$totalkm.'</td>';
		}		
		$result .= '</table>';
		return $result;
	}


	function reportsGenerateCustomVehicleDailyKM($imeis, $dtf, $dtt)
	{
		global $_SESSION, $la, $user_id;
		$result = '<table class="report" width="100%"><tr align="center">';
		$date_from = date('Y-m-d',strtotime($dtf)); 
		$date_from = strtotime($date_from);
		$date_to = date('Y-m-d',strtotime($dtt));
		$date_to = strtotime($date_to); 

		$result .= '<th>'.$la['OBJECT'].'</th>';
		$j=1;
		for ($i=$date_from; $i<$date_to; $i+=86400) {
		   ${'date_'.$j}=0;$j++;
		   $result .= "<th>".date("d-M", $i)."</th>";
		}

		$result .= '<th>Total KM</th></tr>';
		$totalkm=0;$dailytotalkm=0;

		foreach ($imeis as $k => &$imei){
			$data = getVehicleDailyKM($imei, $dtf, $dtt,"ckm_dailykm_by_every_30_minute");			
			$result .= '<tr align="center">';			
			$result .= '<th>'.getObjectName($imei).'</th>';
			$objectkm=0;
			$l=1;
			foreach ($data as $key => $total) {
				${'date_'.$l}+=sprintf("%01.1f", $total["totalkm"]);				
				$l++;
				$objectkm+=sprintf("%01.1f", $total["totalkm"]);
				if($total["totalkm"]!=0){
					$result .= '<td><a style="color: black;" onclick="multipleReportDownload(\''.$imei.'\',\''.$total["date"].'\',\''.date('Y-m-d',strtotime('+1days',strtotime($total["date"]))).'\')">'.sprintf("%01.1f", $total["totalkm"]).'</a></td>';
				}else{
					$result .= '<td>0</td>';
				}
			}
			$totalkm+=$objectkm;
			$result .= '<th><a style="color: black;" onclick="multipleReportDownload(\''.$imei.'\',\''.$dtf.'\',\''.$dtt.'\')">'.$objectkm.'</a></th>';
			$result .= '</tr>';
		}

		$result .= '<tr align="center">';
		$result .= '<th>Total KM</th>';
		$l=1;
		for($k=0;$k<count($data);$k++){
			$dailytotalkm+=${'date_'.$l};
			$result .= '<th>'.${'date_'.$l}.'</th>';$l++;
		}
		if(sprintf("%01.0f",$totalkm)==sprintf("%01.0f",$dailytotalkm)){
			$result .= '<th><b>'.$totalkm.'</b></th>';
		}else{
			$result .= '<td style="font: -webkit-small-control;">'.$totalkm.'</td>';
		}		
		$result .= '</table>';
		return $result;
	}
	function reportsGenerateCustomVehicleDailyKM30minbasedelete($imeis, $dtf, $dtt){
		global $_SESSION, $la, $user_id;
		$result = '<table class="report" width="100%"><tr align="center">';
		// $date_from = date('Y-m-d',strtotime($dtf)); 
		$date_from = strtotime($dtf);
		// $date_to = date('Y-m-d',strtotime($dtt));
		$date_to = strtotime($dtt); 

		$result .= '<th>'.$la['OBJECT'].'</th>';
		$j=1;
		for ($i=$date_from; $i<$date_to; $i+=86400) {
		   ${'date_'.$j}=0;$j++;
		   $result .= "<th>".date("d-M", $i)."</th>";
		}

		$result .= '<th>Total KM</th></tr>';
		$totalkm=0;$dailytotalkm=0;

		foreach ($imeis as $k => &$imei){
			$data = getCustomVehicleDailyKM($imei, $dtf, $dtt);			
			$result .= '<tr align="center">';			
			$result .= '<th>'.getObjectName($imei).'</th>';
			$objectkm=0;
			$l=1;
			foreach ($data as $key => $total) {
				${'date_'.$l}+=sprintf("%01.1f", $total["totalkm"]);				
				$l++;
				$objectkm+=sprintf("%01.1f", $total["totalkm"]);
				if($total["totalkm"]!=0){
					$result .= '<td><a style="color: black;" onclick="multipleReportDownload(\''.$imei.'\',\''.$total["date"].'\',\''.date('Y-m-d',strtotime('+1days',strtotime($total["date"]))).'\')">'.sprintf("%01.1f", $total["totalkm"]).'</a></td>';
				}else{
					$result .= '<td>0</td>';
				}
			}
			$totalkm+=$objectkm;
			$result .= '<th><a style="color: black;" onclick="multipleReportDownload(\''.$imei.'\',\''.$dtf.'\',\''.$dtt.'\')">'.$objectkm.'</a></th>';
			$result .= '</tr>';
		}

		$result .= '<tr align="center">';
		$result .= '<th>Total KM</th>';
		$l=1;
		for($k=0;$k<count($data);$k++){
			$dailytotalkm+=${'date_'.$l};
			$result .= '<th>'.${'date_'.$l}.'</th>';$l++;
		}
		if(sprintf("%01.0f",$totalkm)==sprintf("%01.0f",$dailytotalkm)){
			$result .= '<th><b>'.$totalkm.'</b></th>';
		}else{
			$result .= '<td style="font: -webkit-small-control;">'.$totalkm.'</td>';
		}		
		$result .= '</table>';
		return $result;
	}	
	
	function reportsGeneratemaintenancereport($imeis,$dtf,$dtt,$data_items,$show_coordinates) //OFFLINE DONE BY VETRIVEL.N
	{
		
		global $_SESSION, $la, $user_id;
				
		$imei='';
		for ($i=0; $i<count($imeis); ++$i)
		{
			if($imei=="")
			$imei ="'".$imeis[$i]."'";
			else
			$imei =$imei.",'".$imeis[$i]."'";
		}
		
		$route =getmaintenance($imei,$dtf,$dtt);

		if ((count($route) == 0) )
		{
			if (isset($_POST['schedule']))
			{
				die;
			}
			return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
		
		$result = '<table class="report" width="100%" >
					<tr align="center">';
		
		if (in_array("CLIENT_ID", $data_items))
		{
				$result .='<th>'.$la['CLIENT_ID'].'</th>';
		}
		if (in_array("COMPANY", $data_items))
		{
				$result .='<th>'.$la['COMPANY'].'</th>';
		}
		if (in_array("SITE_LOCATION", $data_items))
		{
				$result .='<th>'.$la['SITE_LOCATION'].'</th>';
		}
		if (in_array("WORK_DATE", $data_items))
		{
				$result .='<th>'.$la['WORK_DATE'].'</th>';
		}
		if (in_array("IMEI", $data_items))
		{
			$result .= '<th>'.$la['IMEI'].'</th>';
		}
		if (in_array("OBJECT", $data_items))
		{
			$result .= '<th>'.$la['OBJECT'].'</th>';
		}
		if (in_array("VEHICLE_TYPE", $data_items))
		{
			$result .= '<th>'.$la['VEHICLE_TYPE'].'</th>';
		}
		if (in_array("FUEL1", $data_items))
		{
			$result .= '<th>'.$la['FUEL1'].'</th>';
		}
		if (in_array("WORK", $data_items))
		{
			$result .= '<th>'.$la['WORK'].'</th>';
		}
		if (in_array("TYPES_WORK", $data_items))
		{
			$result .= '<th>'.$la['TYPES_WORK'].'</th>';
		}
		if (in_array("ACCESSORIES", $data_items))
		{
			$result .= '<th>'.$la['ACCESSORIES'].'</th>';
		}
		if (in_array("STATUS", $data_items))
		{
			$result .= '<th>'.$la['STATUS'].'</th>';
		}
		if (in_array("STAFFNAME", $data_items))
		{
				$result .='<th>'.$la['STAFFNAME'].'</th>';
		}
		if (in_array("UNDER_WARRENTY", $data_items))
		{
			$result .= '<th>'.$la['UNDER_WARRENTY'].'</th>';
		}
		if (in_array("SERVICE_CLOSE", $data_items))
		{
			$result .= '<th>'.$la['SERVICE_CLOSE'].'</th>';
		}	  
	    if (in_array("NOTE", $data_items))
		{
			$result .= '<th>'.$la['NOTE'].'</th>';
		}
		
	 
				$result .='	</tr>';
					
		for ($i=0; $i<count($route); ++$i)
		{
			$result .= '<tr align="center">';
			
			if (in_array("CLIENT_ID", $data_items))
			{
					$result .= '<td>'.$route[$i][0].'</td>';
			}

			if (in_array("COMPANY", $data_items))
			{
					$result .= '<td>'.$route[$i][1].'</td>';
			}
			if (in_array("SITE_LOCATION", $data_items))
			{
					$result .= '<td>'.$route[$i][2].'</td>';
			}
			if (in_array("WORK_DATE", $data_items))
			{
					$result .= '<td>'.$route[$i][3].'</td>';
			}
			if (in_array("IMEI", $data_items))
			{
				$result .= '<td>'.$route[$i][4].'</td>';
			}
			if (in_array("OBJECT", $data_items))
			{
				$result .= '<td>'.$route[$i][5].'</td>';
			}
			if (in_array("VEHICLE_TYPE", $data_items))
			{
				$result .= '<td>'.$route[$i][6].'</td>';
			}
			if (in_array("FUEL1", $data_items))
			{
				$result .= '<td>'.$route[$i][7].'</td>';
			}
			if (in_array("WORK", $data_items))
			{
				$result .= '<td>'.$route[$i][8].'</td>';
			}
			if (in_array("TYPES_WORK", $data_items))
			{
				$result .= '<td>'.$route[$i][9].'</td>';
			}
			if (in_array("ACCESSORIES", $data_items))
			{
				$result .= '<td>'.$route[$i][10].'</td>';
			}
			if (in_array("STATUS", $data_items))
			{
				$result .= '<td>'.$route[$i][11].'</td>';
			}
			if (in_array("STAFFNAME", $data_items))
			{
					$result .= '<td>'.$route[$i][12].'</td>';
			}
			if (in_array("UNDER_WARRENTY", $data_items))
			{
				$result .= '<td>'.$route[$i][13].'</td>';
			}
			if (in_array("SERVICE_CLOSE", $data_items))
			{
				$result .= '<td>'.$route[$i][14].'</td>';
			}  
		    if (in_array("NOTE", $data_items))
			{
				$result .= '<td>'.$route[$i][16].'</td>';
			}
				$result .= '</tr>';
		} 
					
		$result .= '</table>';
		
		return $result;
	}
	
	
	//CODE UPDATE END BY VETRIVEL.NR
	
	function reportsEmergencyAlert($imei='',$dtf, $dtt,$show_coordinates,$show_addresses, $zones_addresses, $data_items) //EMERGENCY_ALERT_REPORT
	{
		global $_SESSION, $la, $user_id;
		
	
		$result = 	'';
			
		$result .= '<table class="report" width="100%" >
					<tr align="center">	
					<th>'.$la['NAME'].'</th>
					<th>'.$la['VENDOR'].'</th>
					<th>'.$la['TRANSPORT_MODEL'].'</th>
					<th>'.$la['EVENT_DESCRIPTION'].'</th>
					<th>'.$la['DRIVER_NAME'].'</th>
					<th>'.$la['ALLOCATED_DRIVER_PHONE'].'</th>
					<th style="width:30%;">'. $la['LOCATION'].'</th>
					<th>'.$la['RECIVE_DATE'].'</th>
					<th>'.$la['ACTION_DATE'].'</th>					
					<th>'. $la['RESPONSE'].'</th>
					<th>'.$la['ACTION_TAKEN'].'</th>
					<th>'.$la['ACTION_BY'].'</th>					
				   </tr>';
				
		
			$data=reportGenerateEmergencyAlert('', $dtf, $dtt, $show_coordinates,$show_addresses, $zones_addresses, $data_items);
			//$data = getTRIPWISE_Daily($imei, $dtf, $dtt, $stop_duration, true,$speed_limit);
			
			
			if (count($data['trip']) == 0)
			{
				$result .= '<tr>
						<td align="center" colspan="17">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td>
						</tr>';
			}
			else
			{
			
			
		for ($i=0; $i<count($data['trip']); ++$i)
		{

				$result .= '<tr>						
						<td align="center">'.$data['trip'][$i][2].'</td>
						<td align="center">'.$data['trip'][$i][3].'</td>
						<td align="center">'.$data['trip'][$i][4].'</td>
						<td align="center">'.$data['trip'][$i][5].'</td>
						<td align="center">'.$data['trip'][$i][6].'</td>
						<td align="center">'.$data['trip'][$i][7].'</td>
						<td align="center">'.$data['trip'][$i][8].'</td>
						<td align="center">'.convUserTimezone($data['trip'][$i][9]).'</td>
						<td align="center">'.convUserTimezone($data['trip'][$i][10]).'</td>
						<td align="center">'.$data['trip'][$i][11].'</td>
						<td align="center">'.$data['trip'][$i][12].'</td>
						<td align="center">'.$data['trip'][$i][13].'</td>';
				
				if($data['trip'][$i][0]!=0)
				{
					$aryzonedata=array();
					$arrzone=$data['trip'][$i][19];
					
				
					
					if($format=="html")
					$result .= '<tr class="cat1" style="display:none" >';
					else
					$result .= '<tr class="cat1" >';
					$chk_finish=false;
					$result .= '<td width="100%"  align="center" colspan="18">
						<table border="1" width="100%" >
						<tr  style="    font-weight: bold;padding: 2px;border: 1px solid #000000;color: white;background-color: #5C5D5C;" ><td>'.$la['ZONE_NAME'].'</td><td>'.$la['TIME'].'</td><td>'.$la['SPEED'].'</td></tr>';
						$result .= '</table></td></tr>';
				}
				
		}
		
		}
		
		return $result;
	}

function reportsMaster_lisid($imei='',$dtf, $dtt,$show_coordinates,$show_addresses, $zones_addresses, $data_items) //EMERGENCY_ALERT_REPORT
	{
		global $_SESSION, $la, $user_id,$ms;
		$result = 	'';

		$q="SELECT * FROM dstudent where user_id='".$user_id."'";
		$r=mysqli_query($ms,$q);
		$sno=1;
		if(mysqli_num_rows($r)>0){
			$result.="<h1>Employee List</h1>";
			$result .= '<table class="report" width="30%">
					<tr align="center">
						<th>S.No</th>
						<th>Emp_ID</th>
						<th>Employee_Name</th>
						<th>Department</th>
						<th>Phone_Number</th>
						<th>RFID</th>
						<th>Pickup Point</th>
						<th>Pickup_Zone_Id</th>
						<th>Drop_Zone_Id</th>
						<th>Pickup_Route_Id</th>
						<th>Dorp_Route_Id</th>
						<th>Vehicle</th>
						<th>Shift</th>
						<th>Status</th>
					</tr>';
			while($row=mysqli_fetch_array($r)){
				$result .='<tr align="center">	
					<td>'.$sno.'</td>
					<td>'.$row['sid'].'</td>
					<td>'.$row['name'].'</td>';
					$q2="SELECT * FROM dept where dept_id='".$row['dept_id']."'";
					$r2=mysqli_query($ms,$q2);
					if(mysqli_num_rows($r1)>0){
						$row2=mysqli_fetch_array($r2);
						$result .='<td>'.$row2['dept_name'].'</td>';
					}else{
						$result .='<td></td>';
					}

					$result .='<td>'.$row['phno'].'</td>
					<td>'.$row['rfid'].'</td>';
					$q1="SELECT * FROM gs_user_zones where zone_id='".$row['zone_id']."'";
					$r1=mysqli_query($ms,$q1);
					if(mysqli_num_rows($r1)>0){
						$row1=mysqli_fetch_array($r1);
						$result .='<td>'.$row1['zone_name'].'</td>';
					}else{
						$result .='<td></td>';
					}
					$result .='<td>'.$row['zone_id'].'</td><td>'.$row['zone_id_down'].'</td>
					<td>'.$row['route_id'].'</td>
					<td>'.$row['route_id_down'].'</td>
					<td>'.getObjectName($row['imei']).'</td>
					<td>'.$row['shift'].'</td>
					<td>Active</td>
					</tr>';

					$sno+=1;
				}
			$result.='</table>';
		}
		
		$q="SELECT * FROM droute Where user_id='".$user_id."'";
		// echo $q;
		$r=mysqli_query($ms,$q);
		if(mysqli_num_rows($r)>0){
			$result.="<h1>Route List</h1>";
			$result .= '<table class="report" width="30%">
					<tr align="center">
						<th>Route Name</th>
						<th>Route ID</th>
					</tr>';
			while($row=mysqli_fetch_array($r)){
				$result .='<tr align="center">	
					<td>'.$row['routename'].'</td>
					<td>'.$row['route_id'].'</td>
					</tr>';
			}
			$result.='</table>';
		}
		$q="SELECT * FROM gs_user_zones Where user_id='".$user_id."'";
		$r=mysqli_query($ms,$q);
		if(mysqli_num_rows($r)>0){
			$result.="<br><br><h1>Zone List</h1>";
			$result .= '<table class="report" width="30%">
					<tr align="center">
						<th>Zone Name</th>
						<th>Zone ID</th>
					</tr>';
			while($row=mysqli_fetch_array($r)){
				$result .='<tr align="center">	
					<td>'.$row['zone_name'].'</td>
					<td>'.$row['zone_id'].'</td>
					</tr>';
			}
			$result.='</table>';
		}	
		$q="SELECT * FROM gs_objects a left join gs_user_objects b on a.imei=b.imei Where b.user_id='".$user_id."'";
		$r=mysqli_query($ms,$q);
		if(mysqli_num_rows($r)>0){
			$result.="<br><br><h1>Device List</h1>";
			$result .= '<table class="report" width="30%">
					<tr align="center">
						<th>Device Name</th>
						<th>Device ID</th>
					</tr>';
			while($row=mysqli_fetch_array($r)){
				$result .='<tr align="center">	
					<td>'.$row['name'].'</td>
					<td>'.$row['imei'].'</td>
					</tr>';
			}
			$result.='</table>';
		}	
		return $result;
	}

function reportsGenerateZonewiseTripreport($imei, $dtf, $dtt, $speed_limit, $stop_duration,$show_coordinates, $show_addresses, $zones_addresses,$format="") //Trip Wise Report
    {
    	global $_SESSION, $la, $user_id;
    	$result = 	'';
    	$result .= '<table class="report" width="100%" >
				<tr align="center">
					<th>'.$la['SINO'].'</th>
				
					<th>'.$la['STARTING_POINT'].'</th>
					<th>'.$la['STARTTIME'].'</th>
					<th>'.$la['END_POINT'].'</th>
					<th>'.$la['ENDTIME'].'</th>										
					<th>'.$la['TAKENKM'].'</th>
					<th>'.$la['POSITION'].'</th>
					<th>'.$la['DURATION'].'</th>

				</tr>';
    		$data = getRoundTrip1($imei,($dtf),($dtt), $stop_duration, true,$speed_limit);
	if (count($data['trip']) == 0)
		{
			$result .= '<tr>
				<td align="center" colspan="17">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td>
					</tr>';
		}
		else
		{
			for ($i=0; $i<count($data['trip']); ++$i)
			{
				$result .= '<tr>
						<td align="center">'.($i+1).'</td>
						<td align="center">'.$data['trip'][$i][1].'</td>
						<td align="center">'.convUserTimezone($data['trip'][$i][3]).'</td>
						<td align="center">'.$data['trip'][$i][2].'</td>
						<td align="center">'.convUserTimezone($data['trip'][$i][4]).'</td>
						<td align="center">'.$data['trip'][$i][5].'</td>
						<td><a href="http://maps.google.com/maps?q='.$data['trip'][$i][7].','.$data['trip'][$i][8].'&t=m" target="_blank">'.reportsGetPossition($data['trip'][$i][7], $data['trip'][$i][8], $show_coordinates, $show_addresses, $zones_addresses).'</a></td>
						<td align="center">'.$data['trip'][$i][6].'</td></tr>';
			}	
		}
			// $data=getRoute($imei, $dtf, $dtt, $stop_duration, $filter=false,$DailyKM=false);
    	$result .= '</table>';    	
    	return $result;
    }

function getRoundTrip1($imeis, $dtff, $dttt, $min_stop_duration, $filter,$speed_limit)
{

	global $ms;


	// check privileges
	if ($_SESSION["privileges"] == 'subuser')
	{
		$user_id = $_SESSION["manager_id"];
	}
	else
	{
		$user_id = $_SESSION["user_id"];
	}
	$trip=array();
	$trip["trip"]= array();
	$q = "select * from gs_user_events_data where zoneid!=0 and user_id='".$user_id."' and imei=".$imeis." and (dt_tracker between '".$dtff."' and '".$dttt."') order by dt_tracker  asc";
		$r = mysqli_query($ms,$q);

		if($r!=true)
        {
        	return $trip;
        }
        $eventdatanew = array();
		while($trip_data=mysqli_fetch_array($r,MYSQLI_ASSOC))
		{			
			if(($trip_data["type"]=="zone_out" || $trip_data["type"]=="Zone_Out") && count($eventdatanew)==0)
			{
				$eventdatanew[]=$trip_data;
			}
			else if(count($eventdatanew)>0 )
			{
				if($eventdatanew[count($eventdatanew)-1]["type"]==$trip_data["type"])
				{
					$eventdatanew[count($eventdatanew)-1]=$trip_data;
				}
				else
				{
					$eventdatanew[]=$trip_data;
				}
			} 
		}

		for($ie=0;$ie<count($eventdatanew);$ie=$ie+2)
		{
			$from=$eventdatanew[$ie];
			$to=array();
			if(isset($eventdatanew[$ie+1]))
			{ 
				$to=$eventdatanew[$ie+1];
			}
					
			if(isset($from) && isset($to) && isset($to["dt_tracker"]))
			{
			
				$result=getRoute($imeis,$from["dt_tracker"],$to["dt_tracker"],$min_stop_duration,$filter);
				if($result>0)
				{
						$trip["trip"][]= array(
						$from['obj_name'],
						$from['event_desc'],
						$to['event_desc'],
						$from['dt_tracker'],
						$to['dt_tracker'],
						// $result['avg_speed'],
						$result['route_length'],
						$result['drives_duration'],
						$from['lat'],
						$from['lng']
						// $overspeeds_count,
						// $result['stops_duration'],
						// $result['events'],
						// $result['top_speed']
						);
				}		
			}
		}
	
		return $trip;
}

	// This report requerment given by PRM
    // Created by Nandha

    function reportsDetailedDriverBehavior($imei, $dtf, $dtt, $speed_limit, $data_items){
    	global $_SESSION, $gsValues, $la, $user_id,$ms;
     $result = '';
     
     $data = getRoute($imei, $dtf, $dtt, 1, true);
     $result .='<h3>Overspeed Details</h3>';
     if (count($data['route'])> 0)
     {
     	$result .= '<table class="report" width="100%" ><tr align="center">';
     	$result .= '<tr align="center" >';				
		$result .= '<th>'.$la['TIME'].'</th>';
		$result .= '<th>'.$la['SPEED'].'</th>';
		$result .= '<th>'.$la['POSITION'].'</th>';
		$result .= '</tr>';	
		$overspeedcount=0;
	     for ($j=0; $j<count($data['route']); ++$j)
		{
			$speed = $data['route'][$j][5];
			
			if ($speed > $speed_limit)
			{
				$result .= '<tr align="center">';
				$result .= '<td>'.$data['route'][$j][0].'</td>';
				$result .= '<td>'.$data['route'][$j][5].'</td>';
				$result .= '<td><a href="http://maps.google.com/maps?q='.$data['route'][$j][1].','.$data['route'][$j][2].'&t=m" target="_blank">'.$data['route'][$j][1].' &deg;, '.$data['route'][$j][2].' &deg;</a></td>';
				$result .= '</tr>';	
				$overspeedcount+=1;
			}	
		}
		if($overspeedcount==0){
			$result .= '<tr><td align="center" colspan="12">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr>';
		}else{
			$result .= '<tr align="center">';
			$result .= '<th colspan=3>Number Of Overspeed Event - '.$overspeedcount.'</th>';
			$result .= '</tr>';
		}
		$result .= '</table>';
	}
	$q = "SELECT * FROM `gs_user_events_data` WHERE `user_id`='".$user_id."' AND `imei`='".$imei."'AND `type`='haccel_server' AND dt_tracker BETWEEN '".$dtf."' AND '".$dtt."' ORDER BY dt_tracker ASC";
	$r = mysqli_query($ms, $q);
	$result .='<h3>Acceleration Details</h3>';
	$result .= '<table class="report" width="100%" ><tr align="center">';
	$result .= '<tr align="center" >';				
	$result .= '<th>'.$la['TIME'].'</th>';	
	$result .= '<th>Start Speed</th>';
	$result .= '<th>End Speed</th>';
	// $result .= '<th>'.$la['DURATION'].'</th>';
	$result .= '<th>'.$la['POSITION'].'</th>';
	$result .= '</tr>';
	if(mysqli_num_rows($r)>0){
		while($row=mysqli_fetch_array($r)){
			// $qs="SELECT speed,dt_tracker FROM `gs_object_data_".$imei."` WHERE dt_tracker<'".$row['dt_tracker']."' ORDER by dt_tracker desc LIMIT 1";
			// $rs = mysqli_query($ms, $qs);
			// if(mysqli_num_rows($rs)>0){
			// 	$rows=mysqli_fetch_array($rs);
			// 	$slow=$rows['speed'];
			// 	$duration=getTimeDetails(strtotime($row['dt_tracker']) - strtotime($rows['dt_tracker']),false);
			// }else{
			// 	$slow=0;
			// 	$duration=0;
			// }
			if($row['custom_value']>0){
				$slow=round($row['speed']-$row['custom_value']);
			}else{
				$slow=0;
			}
			$result .= '<tr align="center">';
			$result .= '<td>'.$row['dt_tracker'].'</td>';			
			$result .= '<td>'.$slow.'</td>';
			$result .= '<td>'.$row['speed'].'</td>';
			// $result .= '<td>'.$duration.'</td>';
			$result .= '<td><a href="http://maps.google.com/maps?q='.$row['lat'].','.$row['lng'].'&t=m" target="_blank">'.$row['lat'].' &deg;, '.$row['lng'].' &deg;</a></td>';
			$result .= '</tr>';
		}
		$result .= '<tr align="center">';
		$result .= '<th colspan=5>Number Of Acceleration Event - '.mysqli_num_rows($r).'</th>';
		$result .= '</tr>';
	}else{
		$result .= '<tr><td align="center" colspan="12">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr>';
	}
	$result .= '</table>';

	$q = "SELECT * FROM `gs_user_events_data` WHERE `user_id`='".$user_id."' AND `imei`='".$imei."'
			AND `type`='hbrake_server' AND dt_tracker BETWEEN '".$dtf."' AND '".$dtt."' ORDER BY dt_tracker ASC";
	$r = mysqli_query($ms, $q);
	$result .='<h3>Harsh Breaking Details</h3>';
	$result .= '<table class="report" width="100%" ><tr align="center">';
	$result .= '<tr align="center" >';				
	$result .= '<th>'.$la['TIME'].'</th>';
	$result .= '<th>Start Speed</th>';
	$result .= '<th>End Speed</th>';
	// $result .= '<th>'.$la['DURATION'].'</th>';
	$result .= '<th>'.$la['POSITION'].'</th>';
	$result .= '</tr>';
	if(mysqli_num_rows($r)>0){
		while($row=mysqli_fetch_array($r)){

			// $qs="SELECT speed,dt_tracker FROM `gs_object_data_".$imei."` WHERE dt_tracker>'".$row['dt_tracker']."' ORDER by dt_tracker asc LIMIT 1";
			// $rs = mysqli_query($ms, $qs);
			// if(mysqli_num_rows($rs)>0){
			// 	$rows=mysqli_fetch_array($rs);
			// 	$fast=$rows['speed'];
			// 	$duration=getTimeDetails(strtotime($rows['dt_tracker']) - strtotime($row['dt_tracker']),false);
			// }else{
			// 	$fast=0;
			// 	$duration=0;
			// }
			if($row['custom_value']>0){
				$fast=round($row['custom_value']+$row['speed']);
			}else{
				$fast=0;
			}
			$result .= '<tr align="center">';
			$result .= '<td>'.$row['dt_tracker'].'</td>';
			$result .= '<td>'.$row['speed'].'</td>';
			$result .= '<td>'.$fast.'</td>';
			// $result .= '<td>'.$duration.'</td>';
			$result .= '<td><a href="http://maps.google.com/maps?q='.$row['lat'].','.$row['lng'].'&t=m" target="_blank">'.$row['lat'].' &deg;, '.$row['lng'].' &deg;</a></td>';
			$result .= '</tr>';
		}
		$result .= '<tr align="center">';
		$result .= '<th colspan=5>Number Of Breaking Event - '.mysqli_num_rows($r).'</th>';
		$result .= '</tr>';
	}else{
		$result .= '<tr><td align="center" colspan="12">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr>';
	}
	$result .= '</table>';

	$q = "SELECT * FROM `gs_user_events_data` WHERE `user_id`='".$user_id."' AND `imei`='".$imei."'
			AND `type`='hcorn' AND dt_tracker BETWEEN '".$dtf."' AND '".$dtt."' ORDER BY dt_tracker ASC";
	$r = mysqli_query($ms, $q);
	$result .='<h3>Harsh Cornering Details</h3>';
	$result .= '<table class="report" width="100%" ><tr align="center">';
	$result .= '<tr align="center" >';				
	$result .= '<th>'.$la['TIME'].'</th>';
	$result .= '<th>'.$la['SPEED'].'</th>';
	$result .= '<th>'.$la['POSITION'].'</th>';
	$result .= '</tr>';
	if(mysqli_num_rows($r)>0){
		while($row=mysqli_fetch_array($r)){
			$result .= '<tr align="center">';
			$result .= '<td>'.$row['dt_tracker'].'</td>';
			$result .= '<td>'.$row['speed'].'</td>';
			$result .= '<td><a href="http://maps.google.com/maps?q='.$row['lat'].','.$row['lng'].'&t=m" target="_blank">'.$row['lat'].' &deg;, '.$row['lng'].' &deg;</a></td>';
			$result .= '</tr>';
		}
		$result .= '<tr align="center">';
		$result .= '<th colspan=3>Number Of Harsh Cornering Event - '.mysqli_num_rows($r).'</th>';
		$result .= '</tr>';
	}else{
		$result .= '<tr><td align="center" colspan="12">'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr>';
	}
	$result .= '</table>';

	return $result;
			
    }


function reportsHMILcustomReport($imeis, $dtf, $dtt, $speed_limit, $data_items){
	global $_SESSION, $gsValues, $la, $user_id,$ms;
     $result = '';
     $result=GetObjectTripList($imeis, $dtf, $dtt);
     $Hotspot=GetObjectTripHotspot($imeis, $dtf, $dtt);
     for ($i=0; $i<count($imeis); ++$i)
		{
			$imei = $imeis[$i];
			// $result=$imei;
			// $result[]= reportGenerateTripWise($imei,$frdate,$trdate,false,false);
		}
     return json_encode($Hotspot);
}


// function getObjectTripDetails($imei $dtf, $dtt){
// 	global $_SESSION, $gsValues, $la, $user_id,$ms;

// }

function GetObjectTripList($imeis, $dtf, $dtt){
	global $_SESSION, $gsValues, $la, $user_id,$ms;
	$return=array();
	$imei_str=implode(',',$imeis);
	$q="SELECT * FROM droute_events WHERE imei in (".$imei_str.") and activ='true' ORDER BY imei ASC";
	$r=mysqli_query($ms,$q);
	if(mysqli_num_rows($r)>0){
		while($row=mysqli_fetch_assoc($r)){
			$row['starttime']=date('H:i:s',strtotime($row['tfh'].":".$row['tfm'].":00"));
			$row['endtime']=date('H:i:s',strtotime($row['tth'].":".$row['ttm'].":00"));
			$return[$row['imei']][$row['event_id']]=$row;
		}
	}
	return $return;

}

function GetObjectTripHotspot($imeis, $dtf, $dtt){
	global $_SESSION, $gsValues, $la, $user_id,$ms;
	$return=array();
	$imei_str=implode(',',$imeis);
	$q="SELECT a.* FROM droute_sub a LEFT JOIN droute_events b ON a.dsub_id=b.route_id AND b.active='true' and a.user_id=b.user_id WHERE a.user_id='".$user_id."' ORDER BY a.route_id ASC";
	$r=mysqli_query($ms,$q);
	if(mysqli_num_rows($r)>0){
		while($row=mysqli_fetch_assoc($r)){
			$return[$row['route_id']][]=$row;
		}
	}
	return $return;

}


function reportGenerateLiveTrip($imei, $dtf, $dtt, $speed_limit, $stop_duration, $show_coordinates, $show_addresses, $zones_addresses,$format,$emptyreport=false) //Live Trip Wise Report
	{
		global $_SESSION, $la, $user_id,$gsValues,$ms;

		$imeiStr = implode(",",$imei);

		$q="SELECT a.imei,b.name,a.route_id,a.zoneid FROM gs_user_events_data a left join gs_objects b ON a.imei=b.imei WHERE a.user_id='".$user_id."' AND (a.dt_tracker BETWEEN '".$dtf."' AND '".$dtt."') and a.ctype='Scheduled' and b.imei in (".$imeiStr.") GROUP BY a.imei  ORDER BY a.dt_tracker desc";
		$r=mysqli_query($ms,$q);
		

		$response = new stdClass();	
		$j=0;

		$result="";
		$result.='<table class="report" width="100%">';
		$result.="<tr align='center'>
				<th>".$la['NAME']."</th>
				<th>".$la['TRIPNAME']."</th>
				<th>".$la['START']."</th>
				<th>".$la['END']."</th>
				<th>".$la['DATE']."</th>
				<th>".$la['STARTTIME']."</th>
				<th>".$la['ENDTIME']."</th>
				<th>".$la['AROUTE_START']."</th>
				<th>".$la['AROUTE_END']."</th>
				<th>".$la['UNIT_KM']."</th>
				<th>".$la['FREEZED_KM']."</th>
				<th>".$la['EMPTYKM']."</th>
				<th>".$la['DURATION']."</th>						
				<th>".$la['STARTING_FUEL']."</th>
				<th>".$la['ENDING_FUEL']."</th>
				<th>".$la['FUEL_CONSUMPTION']."</th>
				<th>".$la['KMPL']."</th>
			</tr>";
				
		while($row=mysqli_fetch_array($r)){		
		$ret= reportGenerateTripWise($row['imei'],$dtf,$dtt,false,false);
		if(count($ret['trip'])>0){			
			for($i=0;$i<count($ret['trip']);$i++){
				$emtykm=0;$kmpl=0;
				$hotspot=$ret['trip'][$i]['hotspot'];
				$create=$ret['trip'][$i]['create'];
				$date=$ret['trip'][$i]['create'];
				$Afromtime=$ret['trip'][$i]['Afromtime'];
				$tripclose=$ret['trip'][$i]['end'];
				if($ret['trip'][$i]['Atotime']!=''){
					$Atotime=$ret['trip'][$i]['Atotime'];
					$vfuel=fuelConsolidateData($row['imei'],convUserUTCTimezone($date.' '.$Afromtime),convUserUTCTimezone($date.' '.$Atotime));
					$diffrence=abs(abs($vfuel['Start']-$vfuel['End']-$vfuel['Filling']));
					$fulestart=sprintf("%01.1f",$vfuel['Start']);
					$fuleend=sprintf("%01.1f",$vfuel['End']);
					$fulefilling=sprintf("%01.1f",$vfuel['Filling']);
					$fuledeff=sprintf("%01.1f",$diffrence);
				}else{
					$Atotime='Waiting';
					$fulestart=0;
					$fuleend=0;
					$fulefilling=0;
					$fuledeff=0;
				}				
				$avg_speed=$ret['trip'][$i]['avg_speed'];
				$delay=$ret['trip'][$i]['delay'];
				$route_length=$ret['trip'][$i]['route_length'];
				$freezedkm=$ret['trip'][$i]['freezedkm'];
				if($route_length!=0 && $fuledeff!=0){
					$kmpl=sprintf("%01.1f", ($route_length/$fuledeff));
				}
				// $freezedkm=10;
				$emptykm=$route_length-$freezedkm;				
				if($emptykm>0 && $freezedkm!=0){$emtykm=$emptykm;}
				$result.='<tr>
							<td>'.$row['name'].'</td>
							<td>'.$ret['trip'][$i]['tripname'].'</td>
							<td>'.$ret['trip'][$i]['fromplace'].'</td>
							<td>'.$ret['trip'][$i]['toplace'].'</td>
							<td>'.$date.'</td>
							<td>'.$ret['trip'][$i]['fromtime'].'</td>
							<td>'.$ret['trip'][$i]['totime'].'</td>
							<td>'.$ret['trip'][$i]['Afromtime'].'</td>
							<td>'.$Atotime.'</td>
							<td>'.$route_length.'</td>
							<td>'.$freezedkm.'</td>
							<td>'.$emtykm.'</td>
							<td>'.$ret['trip'][$i]['taken_time'].'</td>
							<td>'.$fulestart.'</td>
							<td>'.$fuleend.'</td>
							<td>'.$fuledeff.'</td>
							<td>'.$kmpl.'</td>
						</tr>';
				}
			}
		}

		$result .= '</table>';
		
		return $result;
	}
?>
