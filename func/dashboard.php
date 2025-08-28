<?
	//CODE BY VETRIVEL.N
	session_start();
	include ('../init.php');
	include ('fn_common.php');
	include ('fn_route.php');
	include ('fn_reports.genv.php');
	include ('../tools/html2pdf.php');
	include ('fn_download.header.php');
	checkUserSession();
	
	loadLanguage($_SESSION["language"]);
	global $ms;
	$user_id="";
		// check privileges
		if ($_SESSION["privileges"] == 'subuser')
		{
			$user_id = $_SESSION["manager_id"];
		}
		else
		{
			$user_id = $_SESSION["user_id"];
		}
	
	// Code written by VETRIVEL.N  2-11-2015 :) 
	

	if(@$_POST['cmd'] == 'liveroute' || @$_GET['cmd'] == 'liveroute')
	{
		
		// check if reports are called by user or server (sheduled reports)
		checkUserSession();
		loadLanguage($_SESSION["language"]);
		
		$imeis=getUserObjectIMEInew($user_id,@$_GET['group_id']);
		
		header('Content-type: application/json');
		echo json_encode(reportsGenerateGenDashboard($imeis));
		//echo  (reportsGenerateGenInfoMerged($imeis));
		
		//echo  json_encode(reportsGenerateGenInfoMerged("359710043215285"));
		
	}
	else if(@$_POST['cmd'] == 'tripdata' || @$_GET['cmd'] == 'tripdata')
	{
		global $la, $gsValues;
		$dtff=gmdate("Y-m-d")." 00:00:00";
		$dttt=gmdate("Y-m-d H:i:s");
		$speed_limit="60";
		$min_stop_duration="10";
	

		$imeis=getUserObjectIMEInew($user_id);
		$imei='';
		for ($i=0; $i<count($imeis); ++$i)
		{
			if($imei=="")
			$imei ="'".$imeis[$i]."'";
			else
			$imei =$imei.",'".$imeis[$i]."'";
		}
		header('Content-type: application/json');
		$retdata=getTRIPWISE($imei, $dtff, $dttt, $min_stop_duration, true,$speed_limit);
		echo json_encode($retdata['trip']);
	}
	else if(@$_GET['cmd'] == 'select_notification')
    {
    	$dttt=convUserUTCTimezone(gmdate("Y-m-d H:i:s"));
        $q = "select  dr.* from alert dr where (user_id=0 or user_id='".$user_id."' ) and (datefrom <='".$dttt."' and dateto >='".$dttt."' )order by datefrom,dateto";
 
        $result = mysqli_query($ms,$q);
      
        $responce = array();
      
        if ($result!=false)
        {
        	 while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
            {
            	if($row['url']!="")
                $row['link']='<a  style="color:red; font-size: medium;" target="_blank" href="'. $row['url'].'">'. $row['info'].'</a>';
                else 
                $row['link']='<a  style="color:red; font-size: medium;" >'. $row['info'].'</a>';
                
                $responce[]=$row;
            }
        }
    
        header('Content-type: application/json');
        echo json_encode($responce);
        die;
    }
	
	function reportsGenerateGenInfoMerged($imeis) 
	{
		global $ms;
		global $la, $gsValues;
		$dtf=gmdate("Y-m-d")." 00:00:00";
		//$dtf="1-10-2015 00:00:00";
		$dtt=gmdate("Y-m-d H:i:s");
		$speed_limit="60";
		$stop_duration="10";
		$totaltodayevents=0;
		global $_SESSION, $la, $user_id;
		$allresult=array();
		$resultjson=array();
		$condition=$_GET['condition'];
		$tpe=$_GET['type'];
		
		$imeistr="";
		for ($i=0; $i<count($imeis); ++$i)
		{
			if($imeistr=="")
			{
				$imeistr="'".$imeis[$i]."'";
			}
			else 
			{
				$imeistr.=",'".$imeis[$i]."'";
			}
		}
		
		$sensordata=array();
		$q2sv = "SELECT * FROM `gs_object_sensors` WHERE name='A/C' and imei in (".$imeistr.")";
		$r2sv = mysqli_query($ms,$q2sv);
		if($r2sv!=false)
		{	
			while($r2svsub=mysqli_fetch_array($r2sv,MYSQLI_ASSOC))
			{
				$sensordata[]=$r2svsub;
			}
		}
		
		$vonline=0;$voffline=0;$moffline=0;$vnormal=0;$voverspeed=0;$tempabnormal=0;
		$ija=0;
		for ($i=0; $i<count($imeis); ++$i)
		{
			try 
			{
				
			$imei = $imeis[$i];
			$data=array();
			
			$activ='false';
			$q2  = "SELECT * FROM `gs_objects` WHERE `imei`='".$imei."' ";
			$DATENOW=date("Y-m-d");
			if(intval($user_id)==279)
			{
				if($tpe=="A")
				$q2 = "SELECT * FROM `gs_objects` WHERE `imei`='".$imei."' and imei in (select imei from droute_events_daily where imei='".$imei."'  and (DATE(datefrom)='".$DATENOW."' or  DATE(dateto)='".$DATENOW."'))";
				else if($tpe=="NA")
				$q2 = "SELECT * FROM `gs_objects` WHERE `imei`='".$imei."' and imei NOT in (select imei from droute_events_daily where imei='".$imei."'  and (DATE(datefrom)='".$DATENOW."' or  DATE(dateto)='".$DATENOW."'))";
			}
			else
			{
				if($tpe=="A")
				$q2 = "SELECT * FROM `gs_objects` WHERE `imei`='".$imei."' and imei in (select imei from droute_events where imei='".$imei."' )";
				else if($tpe=="NA")
				$q2 = "SELECT * FROM `gs_objects` WHERE `imei`='".$imei."' and imei NOT in (select imei from droute_events where imei='".$imei."' )";
			}

			$r2 = mysqli_query($ms,$q2);
			$row2 = mysqli_fetch_array($r2,MYSQLI_ASSOC);

			if ($row2['active'] == 'true')
			{

				$accuracy = getObjectAccuracy($imei);
				//$data=getRouteRaw($imei, $accuracy, $dtf, $dtt);//for getting route length
				$dataevents=getRouteEvents($imei, $dtf, $dtt);

				$dt_server = $row2['dt_server'];
				$dt_tracker = convUserTimezone($row2['dt_tracker']);
				$lat = $row2['lat'];
				$lng = $row2['lng'];
				$altitude = $row2['altitude'];
				$angle = $row2['angle'];
				$speed = $row2['speed'];
				$params = paramsToArray($row2['params']);
				$speed = convSpeedUnits($speed, 'km', $_SESSION["unit_distance"]);
				$altitude = convAltitudeUnits($altitude, 'km', $_SESSION["unit_distance"]);
				
				$accstat="";
				$acc=paramsParamValue($params,"acc");
				$fuel=0;
				if($acc==0)
				{	
					$speed ="0";
					$accstat="Off";
					$acc='<button class="btn btn-danger waves-effect waves-light m-b-5"><i style="line-height:0.5" class="fa fa-empire"></i> <span>Off</span></button>';
					$acc = '<img src="'.$gsValues["URL_ROOT"].'/theme/images/img/red.png" style="width:20px;">';
				}
				else
				{
					$accstat="On";
					$acc='<button class="btn btn-success waves-effect waves-light m-b-5"><i style="line-height:0.5" class="fa fa-empire"></i> <span>On</span></button>';
					$acc = '<img src="'.$gsValues["URL_ROOT"].'/theme/images/img/enginerun.gif" style="width:20px;">';
				}
				
				$acstat="";$ac=array();
				$ac["value"]="";
				$acparam="";
				for ($ik=0; $ik<count($sensordata); ++$ik)
				{
					if($sensordata[$ik]["name"]=="A/C")
					{
						//$ac=paramsParamValue($params,$sensordata[$ik]["param"]);
						$ac=getSensorValue($params,$sensordata[$ik]);
						//$acstat=$sensordata[$ik];
						
					}
				}
				
				if($ac["value"]==1)
				{	
					$acstat="On";
					$ac = '<img title="On" src="'.$gsValues["URL_ROOT"].'/theme/images/img/acon.png" style="width:20px;">';
				}
				else 
				{
					$acstat="Off";
					$ac = '<img title="Off" src="'.$gsValues["URL_ROOT"].'/theme/images/img/acoff.png" style="width:20px;">';
				}
				
				$sensorf = getSensorFromType($imei, 'fuel');
				
				if(count($sensorf)>0)
				$fuel=getSensorValue($params,$sensorf[0]);
				
				
				// object loc valid
				$loc_valid = $row2['loc_valid'];
				if($loc_valid =="0")
				{ 
					$loc_valid = '<button class="btn btn-icon waves-effect waves-light btn-danger m-b-5"><i style="line-height:0.5" class=" md-gps-fixed"></i>Offline</button>';
				}
				else
				{
					$loc_valid = '<button class="btn btn-icon waves-effect waves-light btn-success m-b-5"><i style="line-height:0.5" class=" md-gps-fixed"></i>Online</button>';
					//$loc_valid = '0';
					//$speed = 0;
				}
				
				$onlinestate="";
				// connection/loc valid check
				$dt_now = gmdate("Y-m-d H:i:s");
				$dt_now=date("Y-m-d H:i:s", strtotime($dt_now."+5 hours 30 minutes"));
				$dt_difference = strtotime($dt_now) - strtotime($dt_tracker);
				
				if(abs($dt_difference) < (1440 * 60))
				{ 
					$onlinestate="Online Vehicles";
					$vonline++;
					$conn_valid = '<img src="'.$gsValues["URL_ROOT"].'/theme/images/img/greensignal.png" width="20px" >';
				}
				else
				{
					if($row2['vehicle_status']=='maintenance'){
						$onlinestate="Maintenance Vehicles";
						$moffline++;
					}else{
						$onlinestate="Offline Vehicles";
						$voffline++;
					}
					$speed ="0";					
					//$conn_valid = '<button class="btn btn-icon waves-effect waves-light btn-danger m-b-5"><i style="line-height:0.5" class=" md-pin-drop"></i>Offline</button>';
					$conn_valid = '<img src="'.$gsValues["URL_ROOT"].'/theme/images/img/redsignal.png" width="20px" >';
					//$loc_valid = '0';
					//$speed = 0;
				}
				
				
				
				$overspeeds_count = 0;
				$tempabnormal_count = 0;
				$route_length=round($row2['odometer'],2);
				$nearest_zone="";
				
				//if(count($data)>0)
				//{
					//$route_length=(floatval($data[count($data)-1][7])- floatval( $data[0][7]));
				//}
				
				$nearest_zone=getNearestZonev($imei,$lat,$lng);
				
				$last_event="";
				if(count($dataevents)>0)
				{
					$totaltodayevents=$totaltodayevents+count($dataevents);
					$arrevent=caculateoverspeedcountdb($dataevents);
					$overspeeds_count=$arrevent[0];
					$tempabnormal_count=$arrevent[1];
					$last_event=$dataevents[count($dataevents)-1][0];
				}
				
				if($overspeeds_count>0)
				$voverspeed++;
				else 
				$vnormal++;
				
				if($tempabnormal_count>0)
				$tempabnormal++;
				

				$vno=getObjectName($imei);
				
				if($condition=="" || ($condition=="Online Vehicles"&& $onlinestate=="Online Vehicles" ) || ($condition=="Offline Vehicles"&& $onlinestate=="Offline Vehicles") || ($condition=="Maintenance Vehicle"&& $onlinestate=="Maintenance Vehicles" ) || ($condition=="Normal Speed"&& $overspeeds_count==0 ) || ($condition=="Over Speed"&& $overspeeds_count>0 ) )
				{
				$resultjson[]=array("SNo"=>($i+1),"Object"=>$vno,"Time"=>$dt_tracker,"Status"=>$onlinestate,"Ignition"=>$accstat,
				"i1"=>'<a style="cursor: pointer;" onclick="utilsFollowObject('.$imei.', false)" tag="follow_new"> '.$vno.'</a>',"i2"=>$conn_valid,"i3"=>$acc,
				"Speed"=>$speed,"Fuel_Level"=>$fuel["value"],"Overspeed_Count"=>$overspeeds_count,"Route_Length"=>$route_length.' Km',"Last_Event"=>$last_event,
				"Nearest_Zone"=>$nearest_zone,"Temperature_Abnormal"=>$tempabnormal_count,"Imei"=>$imei,"AC"=>$acstat,"ACI"=>$ac,'Vehicle_Status'=>$row2['vehicle_status']
				);
				/*
				$resultjson[]=array("SNo"=>($i+1),"Object"=>$vno,"Time"=>$dt_tracker,"Status"=>$onlinestate,"Ignition"=>$accstat,
				"i1"=>'<a style="cursor: pointer;" id="object_menu_'.$imei.'" tag="follow_new"> '.$vno.'</a>',"i2"=>$conn_valid,"i3"=>$acc,
				"Speed"=>$speed,"Fuel_Level"=>$fuel["value"],"Overspeed_Count"=>$overspeeds_count,"Route_Length"=>$route_length.' '.$_SESSION["unit_distance_string"],"Last_Event"=>$last_event,
				"Nearest_Zone"=>$nearest_zone,"Temperature_Abnormal"=>$tempabnormal_count,"Imei"=>$imei
				);
				*/
				}
				//$i++;
				
			}
			
		}
		catch (Exception $e) {
		echo 'Caught exception: ',  $e->getMessage(), "\n";

		}
			
		}
		$eventtoday=array();
		$sosalert=0;
		$qevent = "select count(*) event,event_id,type from gs_user_events_data where user_id='".$user_id."' and imei in (".$imeistr.") and DATE(dt_tracker)='".gmdate("Y-m-d")."' and type in('gpsantcut','gsmantcut','fuelstolen','fueldiscnt','pwrcut','lowbat','lowdc','sos') group by type";
		$revent = mysqli_query($ms,$qevent);
		if($revent!=false)
		{	
			while($rowevent=mysqli_fetch_assoc($revent))
			{
				$eventtoday[]=$rowevent;
				$sq="select * from event_action where event_id='".$rowevent['event_id']."'";
				$sr=mysqli_query($ms,$sq);
				if(mysqli_num_rows($sr)!=0){
					while($srow=mysqli_fetch_assoc($sr)){
						$sosalert+=1;
					}
				}	
			}
		}
		
		$todayreports=0;$todayemails=0;
		$qcnt = "select count(count) cont,type
				,(select count(user_id) from emaillog sv
				where  date(datetime)='".date("Y-m-d")."' and user_id='".$user_id."' and mailcontent is not null and e.type=sv.type
				) mail from emaillog e where date(datetime)='".date("Y-m-d")."' and user_id='".$user_id."' group by type";
		$rcnt = mysqli_query($ms,$qcnt);
		if($rcnt!=false)
		{
			while($rowcnt = mysqli_fetch_array($rcnt))
			{
				//if($rowcnt['type']=='Event' || $rowcnt['type']=='Schedule')
				//$todayreports=$todayreports+intval($rowcnt['cont']);
				//else 
				
				if($rowcnt['type']=='Report')
				$todayreports=intval($rowcnt['cont']);
				
				if($rowcnt['type']=='Event' || $rowcnt['type']=='EventBoarding' || $rowcnt['type']=='Schedule')
				$todayemails=$todayemails+intval($rowcnt['cont']);
				 
				
				//if($rowcnt['mail']!=0)
				//$todayemails=$todayemails+intval($rowcnt['mail']);
			}
		}

		$allresult["todayreports"]=$todayreports;
		$allresult["todayemails"]=$todayemails;
		$allresult["todayevents"]=$totaltodayevents;
		$allresult["event"]=($eventtoday);
		$allresult["sosalert"]=($sosalert);
		$allresult["content"]=($resultjson);
		$allresult["online"]=$vonline;
		$allresult["offline"]=$voffline;
		$allresult["mai_offline"]=$moffline;
		$allresult["normal"]=$vnormal;
		$allresult["overspeed"]=$voverspeed;
		$allresult["tempabnormal"]=$tempabnormal;
		
		return $allresult;
	}

	function reportsGenerateGenDashboard($imeis)
	{
		global $ms,$user_id;
		global $la, $gsValues;
		$dtf=gmdate("Y-m-d")." 00:00:00";
		//$dtf="1-10-2015 00:00:00";
		$dtt=gmdate("Y-m-d H:i:s");
		$speed_limit="60";
		$stop_duration="10";
		$totaltodayevents=0;
		global $_SESSION, $la, $user_id;
		$allresult=array();
		$resultjson=array();
		$dataevents = array();	
		$condition=$_GET['condition'];
		$tpe=$_GET['type'];

		$imeistr="";
		for ($i=0; $i<count($imeis); ++$i)
		{
			if($imeistr=="")
			{
				$imeistr="'".$imeis[$i]."'";
			}
			else
			{
				$imeistr.=",'".$imeis[$i]."'";
			}
		}

		$sensordata=array();
		$q2sv = "SELECT * FROM `gs_object_sensors` WHERE name='A/C' and imei in (".$imeistr.")";
		$r2sv = mysqli_query($ms,$q2sv);
		if($r2sv!=false)
		{
			while($r2svsub=mysqli_fetch_array($r2sv,MYSQLI_ASSOC))
			{
				$sensordata[$r2svsub["imei"]]=$r2svsub;
			}
		}

		$vonline=0;$voffline=0;$moffline=0;$vnormal=0;$voverspeed=0;$tempabnormal=0;
		$ija=0;
		$DATENOW=date("Y-m-d");
		$q  = "select *,
			CASE triptype
    		WHEN 'Daily' THEN 
	  		(
	 		(select count(imei) from droute_events_daily where imei=go.imei  
	 		and (DATE(datefrom)='".$DATENOW."' or  DATE(dateto)='".$DATENOW."'))
	 		)
    		WHEN 'Scheduled' THEN (select count(imei) from droute_events where imei=go.imei)
    		ELSE '0'
			END trip
			from gs_objects go join gs_user_objects guo on go.imei=guo.imei and guo.user_id=".$user_id." and active='true' and go.imei in (".$imeistr.")";
		$r2 = mysqli_query($ms,$q);

		if($r2!=false)
		{
			try
			{
				while($row2 = mysqli_fetch_array($r2,MYSQLI_ASSOC))
				{
					$imei = $row2["imei"];
					if($tpe=="" || ($tpe=="A" &&($row2["trip"]!="0")) || ($tpe=="NA" &&($row2["trip"]=="0")) )
					{
						
						$data=array();
						$activ='false';
						$accuracy = getObjectAccuracy($imei);
						$dataevents= getRouteEvents($imei, $dtf, $dtt);	
						$dt_server = $row2['dt_server'];
						$dt_tracker = convUserTimezone($row2['dt_tracker']);
						$lat = $row2['lat'];
						$lng = $row2['lng'];
						$altitude = $row2['altitude'];
						$angle = $row2['angle'];
						$speed = $row2['speed'];
						$params = paramsToArray($row2['params']);
						$speed = convSpeedUnits($speed, 'km', $_SESSION["unit_distance"]);

						$altitude = convAltitudeUnits($altitude, 'km', $_SESSION["unit_distance"]);
						$accstat="";
						$acc=paramsParamValue($params,"acc");
						$fuel=0;
						if($acc==0)
						{
							$speed ="0";
							$accstat="Off";
							$acc='<button class="btn btn-danger waves-effect waves-light m-b-5"><i style="line-height:0.5" class="fa fa-empire"></i> <span>Off</span></button>';
							$acc = '<img src="'.$gsValues["URL_ROOT"].'/theme/images/img/red.png" style="width:20px;">';
						}
						else
						{
							$accstat="On";
							$acc='<button class="btn btn-success waves-effect waves-light m-b-5"><i style="line-height:0.5" class="fa fa-empire"></i> <span>On</span></button>';
							$acc = '<img src="'.$gsValues["URL_ROOT"].'/theme/images/img/enginerun.gif" style="width:20px;">';
						}
							
						$acstat="";$ac=array();
						$ac["value"]="";
						$acparam="";
						if(isset($sensordata[$imei]))
						{
							$ac=getSensorValue($params,$sensordata[$imei]);
						}

						if($ac["value"]==1)
						{
							$acstat="On";
							$ac = '<img title="On" src="'.$gsValues["URL_ROOT"].'/theme/images/img/acon.png" style="width:20px;">';
						}
						else
						{
							$acstat="Off";
							$ac = '<img title="Off" src="'.$gsValues["URL_ROOT"].'/theme/images/img/acoff.png" style="width:20px;">';
						}

						$sensorf = getSensorFromType($imei, 'fuel');

						try
						{
							
							if( $sensorf != false &&  isset($sensorf) && count($sensorf)>0)
							{
								$fuel=getSensorValue($params,$sensorf[0]);
							}
							else{
								$fuel =0;
							}
						}
						catch(Exception $e) {
						  	// echo 'Message: ' .$e->getMessage();
						  	$myfile = fopen("0515.txt", "a");
							$txt = json_encode($e->getMessage());
							fwrite($myfile, $txt);
							$txt = json_encode($sensorf);
							fwrite($myfile, $txt);
							fclose($myfile);
						}


						// object loc valid
						$loc_valid = $row2['loc_valid'];
						if($loc_valid =="0")
						{
							$loc_valid = '<button class="btn btn-icon waves-effect waves-light btn-danger m-b-5"><i style="line-height:0.5" class=" md-gps-fixed"></i>Offline</button>';
						}
						else
						{
							$loc_valid = '<button class="btn btn-icon waves-effect waves-light btn-success m-b-5"><i style="line-height:0.5" class=" md-gps-fixed"></i>Online</button>';
						}

						$onlinestate="";
						// connection/loc valid check
						$dt_now = gmdate("Y-m-d H:i:s");
						$dt_now=date("Y-m-d H:i:s", strtotime($dt_now."+5 hours 30 minutes"));
						$dt_difference = strtotime($dt_now) - strtotime($dt_tracker);

						if(abs($dt_difference) < (1440 * 60))
						{
							$onlinestate="Online Vehicles";
							$vonline++;
							$conn_valid = '<img src="'.$gsValues["URL_ROOT"].'/theme/images/img/greensignal.png" width="20px" >';
						}
						else
						{
							if($row2['vehicle_status']=='maintenance'){
								$onlinestate="Maintenance Vehicles";
								$moffline++;
							}else{
								$onlinestate="Offline Vehicles";
								$voffline++;
							}
							$speed ="0";
							
							$conn_valid = '<img src="'.$gsValues["URL_ROOT"].'/theme/images/img/redsignal.png" width="20px" >';
						}

						$overspeeds_count = 0;
						$tempabnormal_count = 0;
						$route_length=round($row2['odometer'],2);
						$nearest_zone="";

						$nearest_zone=getNearestZonev($imei,$lat,$lng);

						$last_event="";
						if(count($dataevents)>0)
						{
							$totaltodayevents=$totaltodayevents+count($dataevents);
							$arrevent=caculateoverspeedcountdb($dataevents);							
							$overspeeds_count=$arrevent[0];
							$tempabnormal_count=$arrevent[1];
							$last_event=$dataevents[count($dataevents)-1][0];
						}
						

						if($overspeeds_count>0)
						$voverspeed++;
						else
						$vnormal++;

						if($tempabnormal_count>0)
						$tempabnormal++;

						if($condition=="" || ($condition=="Online Vehicles"&& $onlinestate=="Online Vehicles" ) || ($condition=="Offline Vehicles"&& $onlinestate=="Offline Vehicles") || ($condition=="Maintenance Vehicle"&& $onlinestate=="Maintenance Vehicles" ) || ($condition=="Normal Speed"&& $overspeeds_count==0 ) || ($condition=="Over Speed"&& $overspeeds_count>0 ) )
						{
							$resultjson[]=array("SNo"=>($i+1),"Object"=>$row2['name'],"Model"=>$row2['model'],"Time"=>$dt_tracker,"Status"=>$onlinestate,"Ignition"=>$accstat,"i1"=>'<a style="cursor: pointer;" onclick="utilsFollowObject('.$imei.', false)" tag="follow_new"> '.$row2['name'].'</a>',"i2"=>$conn_valid,"i3"=>$acc,"Speed"=>$speed,"Fuel_Level"=>$fuel["value"],"Overspeed_Count"=>$overspeeds_count,"Route_Length"=>$route_length.' Km',"Last_Event"=>$last_event,"Nearest_Zone"=>$nearest_zone,"Temperature_Abnormal"=>$tempabnormal_count,"Imei"=>$imei,"AC"=>$acstat,"ACI"=>$ac,'Vehicle_Status'=>$row2['vehicle_status']);
						}

					}
				}//while end
			}
			catch (Exception $e) {echo 'Caught exception: ',  $e->getMessage(), "\n";}
		}
		
		
		$eventtoday=array();
		$sosalert=0;
		$qevent = "select count(*) event,event_id,type from gs_user_events_data where user_id='".$user_id."' and imei in (".$imeistr.") and DATE(dt_tracker)='".gmdate("Y-m-d")."' and type in('gpsantcut','gsmantcut','fuelstolen','fueldiscnt','pwrcut','lowbat','lowdc','sos') group by type";
		$revent = mysqli_query($ms,$qevent);
		if($revent!=false){
			while($rowevent=mysqli_fetch_assoc($revent))
			{
				$eventtoday[]=$rowevent;				
			}
		}

		$sq="SELECT * FROM event_action a LEFT JOIN gs_user_events_data b ON a.event_id=b.event_id WHERE b.imei in (".$imeistr.") AND user_id='".$user_id."' and DATE(b.dt_tracker)='".gmdate("Y-m-d")."' AND b.type in('gpsantcut','gsmantcut','fuelstolen','fueldiscnt','pwrcut','lowbat','lowdc','sos') ";
		$sr=mysqli_query($ms,$sq);
		if(mysqli_num_rows($sr)!=0){
			while($srow=mysqli_fetch_assoc($sr)){
				$sosalert+=1;
			}
		}	
		
		$todayreports=0;$todayemails=0;
		$qcnt = "select count(count) cont,type
				,(select count(user_id) from emaillog sv
				where  date(datetime)='".date("Y-m-d")."' and user_id='".$user_id."' and mailcontent is not null and e.type=sv.type
				) mail from emaillog e where date(datetime)='".date("Y-m-d")."' and user_id='".$user_id."' group by type";
		$rcnt = mysqli_query($ms,$qcnt);
		if($rcnt!=false)
		{
			while($rowcnt = mysqli_fetch_array($rcnt))
			{
				//if($rowcnt['type']=='Event' || $rowcnt['type']=='Schedule')
				//$todayreports=$todayreports+intval($rowcnt['cont']);
				//else
				
				if($rowcnt['type']=='Report')
				$todayreports=intval($rowcnt['cont']);

				if($rowcnt['type']=='Event' || $rowcnt['type']=='EventBoarding' || $rowcnt['type']=='Schedule')
				$todayemails=$todayemails+intval($rowcnt['cont']);
					

				//if($rowcnt['mail']!=0)
				//$todayemails=$todayemails+intval($rowcnt['mail']);
			}
		}

		$allresult["todayreports"]=$todayreports;
		$allresult["todayemails"]=$todayemails;
		$allresult["todayeventstot"]=$totaltodayevents;
		$allresult["event"]=($eventtoday);
		$allresult["sosalert"]=($sosalert);
		$allresult["content"]=($resultjson);
		$allresult["online"]=$vonline;
		$allresult["offline"]=$voffline;
		$allresult["mai_offline"]=$moffline;
		$allresult["normal"]=$vnormal;
		$allresult["overspeed"]=$voverspeed;
		$allresult["tempabnormal"]=$tempabnormal;

		// if($user_id=='439'){
		// 	$myfile = fopen("vvv_1.txt", "a");
		// 	fwrite($myfile,'in');
		// 	fwrite($myfile, "\n");
		// 	fclose($myfile);
		// }
		// $myfile = fopen("newfile.txt", "w") ;
		// $txt=json_encode($allresult);
		// fwrite($myfile, $txt);
		// fclose($myfile);

		return $allresult;
	}

if(@$_GET['cmd'] == 'dashboard_live_tripwisereport'){

	global $user_id;
	

	$df_time = @$_GET['df_time'];
	$dt_time = @$_GET['dt_time'];

	if($df_time == ""){
		$df_time = "00:00:00";
	}

	if($dt_time == ""){
		$dt_time = "23:59:00";
	}

	$frdate=date('Y-m-d').' '.$df_time;
	$frdate=date('Y-m-d H:i:s',strtotime('-5 hour -30 minutes',strtotime($frdate)));
	
	$trdate=date('Y-m-d').' '.$dt_time;
	$trdate=date('Y-m-d H:i:s',strtotime('-5 hour -30 minutes',strtotime($trdate)));
	
	// $frdate=date('Y-m-d').' 00:00:00';
	// $frdate=convUserUTCTimezone($frdate);
	// $trdate=date('Y-m-d H:i:s');

	$page = $_GET['page']; // get the requested page
	$limit = $_GET['rows']; // get how many rows we want to have into the grid
	$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
	$sord = $_GET['sord']; // get the direction
	
	if(!$sidx) $sidx =1;
            // get records number			
	$q="SELECT a.imei,b.name,a.route_id,a.zoneid FROM gs_user_events_data a left join gs_objects b ON a.imei=b.imei left join gs_user_objects c on b.imei=c.imei WHERE a.user_id='".$user_id."' AND (a.dt_tracker BETWEEN '".$frdate."' AND '".$trdate."')  and a.ctype='Scheduled'  AND a.route_id!=0";
	if(isset($_GET['groupid']) && $_GET['groupid']!=''){
		$q.=" and c.group_id='".$_GET['groupid']."'";
	}
	if(isset($_GET['object']) && $_GET['object']!=''){
		$q.=" AND b.name LIKE '%".$_GET['object']."%'";
	}
	$q.=" GROUP BY a.imei  ORDER BY a.dt_tracker desc";
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

	$q="SELECT a.imei,b.name,a.route_id,a.zoneid FROM gs_user_events_data a left join gs_objects b ON a.imei=b.imei left join gs_user_objects c on b.imei=c.imei WHERE a.user_id='".$user_id."' AND (a.dt_tracker BETWEEN '".$frdate."' AND '".$trdate."')  and a.ctype='Scheduled'  AND a.route_id!=0";
	if(isset($_GET['groupid']) && $_GET['groupid']!=''){
		$q.=" and c.group_id='".$_GET['groupid']."'";
	}
	if(isset($_GET['object']) && $_GET['object']!=''){
		$q.=" AND b.name LIKE '%".$_GET['object']."%'";
	}
	$q.=" GROUP BY a.imei  ORDER BY a.dt_tracker desc LIMIT $start, $limit";
	$r=mysqli_query($ms,$q);
	$response = new stdClass();
	$response->page = $page;
	$response->total = $total_pages;
	$response->records = $count;
	$response->delete = @$_POST['dt_time']."->".$dt_time;
	$response->qry = $q;	
	$j=0;
	while($row=mysqli_fetch_array($r)){		
		$ret= reportGenerateTripWise($row['imei'],$frdate,$trdate,false,false);
		if(count($ret['trip'])>0){
			for($i=0;$i<count($ret['trip']);$i++){
				$imei=$row['imei'];
				$accuracy = getObjectAccuracy($row['imei']);
				$fuel_sensors = getSensorFromType($row['imei'], 'fuel');
				$emtykm=0;$kmpl=0;
				$objname=getObjectName($row['imei']);
				$tripname=$ret['trip'][$i]['tripname'];
				$hotspot=$ret['trip'][$i]['hotspot'];
				$create=$ret['trip'][$i]['create'];
				$fromplace=$ret['trip'][$i]['fromplace'];
				$toplace=$ret['trip'][$i]['toplace'];
				$date=$ret['trip'][$i]['create'];
				$fromtime=$ret['trip'][$i]['fromtime'];
				$totime=$ret['trip'][$i]['totime'];
				$Afromtime=$ret['trip'][$i]['Afromtime'];
				$tripclose=$ret['trip'][$i]['end'];				
					$Atotime='Waiting';
					$fulestart=0;
					$fuleend=0;
					$fulefilling=0;
					$fuledeff=0;
				if($ret['trip'][$i]['Atotime']!=''){
					$Atotime=$ret['trip'][$i]['Atotime'];
					$vfuel=fuelConsolidateData($row['imei'],convUserUTCTimezone($date.' '.$Afromtime),convUserUTCTimezone($date.' '.$Atotime));
					//$diffrence=abs(abs($vfuel['Start']-$vfuel['End'])-$vfuel['Filling']);
					$startff = isset($vfuel['Start']) ? (float) $vfuel['Start'] : 0;
					$endff = isset($vfuel['End']) ? (float) $vfuel['End'] : 0;
					$fillingff = isset($vfuel['Filling']) ? (float) $vfuel['Filling'] : 0;

					// Calculate the difference
					$diffrence = abs(abs($startff - $endff) - $fillingff);

					$fulestart=sprintf("%01.1f",$vfuel['Start']);
					$fuleend=sprintf("%01.1f",$vfuel['End']);
					$fulefilling=sprintf("%01.1f",$vfuel['Filling']);
					$fuledeff=sprintf("%01.1f",$diffrence);
				}			
				$avg_speed=$ret['trip'][$i]['avg_speed'];
				$delay=$ret['trip'][$i]['delay'];
				$route_length=$ret['trip'][$i]['route_length'];
				$taken_time=$ret['trip'][$i]['taken_time'];
				$ovr_speed=$ret['trip'][$i]['ovr_speed'];
				$engine_idle=$ret['trip'][$i]['engine_idle'];
				$freezedkm=$ret['trip'][$i]['freezedkm'];
				if($route_length!=0 && $fuledeff!=0){
					$kmpl=sprintf("%01.1f", ($route_length/$fuledeff));
				}
				// $freezedkm=10;
				$emptykm= (float) $route_length- (float) $freezedkm;				
				if($emptykm>0 && $freezedkm!=0){$emtykm=$emptykm;}	
				$onboardemp=GetObjectRFID_Details($row['imei'],convUserUTCTimezone($fromtime),convUserUTCTimezone($totime));			
				$response->rows[$j]['id']='trip'.$j.$ret['trip'][$i]['imei'];
				$response->rows[$j]['cell']=array($objname,$tripname,count($onboardemp),$fromplace,$toplace,$fromtime,$totime,$Afromtime,$Atotime,$route_length,$freezedkm,$emtykm,$taken_time,$fulestart,$fuleend,$fulefilling,$fuledeff,$kmpl,$tripclose,$user_id);
				$j++;
			}
		}
	}
	header('Content-type: application/json');
	echo json_encode($response);
	die;
}
if(@$_POST['cmd']=='downloadLiveTripReport'){
	global $user_id;
	$format=$_POST['formate'];
	//$frdate=date('Y-m-d').' 00:00:00';
	// $frdate='2020-01-05 00:00:00';
	//$frdate=date('Y-m-d H:i:s',strtotime('-5 hour -30 minutes',strtotime($frdate)));
	//$trdate=date('Y-m-d H:i:s');

	$df_time = @$_POST['df_time'];
	$dt_time = @$_POST['dt_time'];

	if($df_time == ""){
		$df_time = "00:00:00";
	}

	if($dt_time == ""){
		$dt_time = "23:59:00";
	}

	$format=$_POST['formate'];

	$frdate=date('Y-m-d').' '.$df_time;
	$frdate=date('Y-m-d H:i:s',strtotime('-5 hour -30 minutes',strtotime($frdate)));
	
	$trdate=date('Y-m-d').' '.$dt_time;
	$trdate=date('Y-m-d H:i:s',strtotime('-5 hour -30 minutes',strtotime($trdate)));

	$q="SELECT a.imei,b.name,a.route_id,a.zoneid FROM gs_user_events_data a left join gs_objects b ON a.imei=b.imei WHERE a.user_id='".$user_id."' AND (a.dt_tracker BETWEEN '".$frdate."' AND '".$trdate."') and a.ctype='Scheduled'";
	$q.=" GROUP BY a.imei  ORDER BY a.dt_tracker desc";
	$r=mysqli_query($ms,$q);
	$response = new stdClass();	
	$j=0;
	$result='<table><tr><th>Live Trip Wise KM Report</th></tr><tr><th>Date:'.convUserTimezone($frdate).' - '.convUserTimezone($trdate).'<th></tr></table>';
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
		$ret= reportGenerateTripWise($row['imei'],$frdate,$trdate,false,false);
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
	$result.='</table>';	
	
	if ($format == 'html' || $format=='pdf')
	{
		$report_html = reportsAddHeaderStart($format);	
		$report_html .= reportsAddStyle($format);
	    $report_html .= reportsAddHeaderEnd();
		$report_html .= '<img class="logo" src="'.$gsValues['URL_LOGO'].'" /><hr/>';
		$report_html .= $result;
		$report_html .= '</body>';
		$report_html .= '</html>';
		$report=$report_html;
		if($format=='pdf'){
			$report = html2pdf($report);
		}
		$report=base64_encode($report);
	}else{
		$report=base64_encode($result);
	}
	echo $report;
}	

if(@$_GET['cmd']=='dashboarServicelist'){
	global $user_id;
	$page = $_GET['page']; // get the requested page
	$limit = $_GET['rows']; // get how many rows we want to have into the grid
	$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
	$sord = $_GET['sord']; // get the direction
	if(!$sidx) $sidx =1;
	$q = "SELECT * FROM gs_object_services a left join gs_user_objects b on a.imei=b.imei where b.user_id='".$user_id."'";
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

	$q="SELECT a.* FROM gs_object_services a left join gs_user_objects b on a.imei=b.imei where b.user_id='".$user_id."' LIMIT $start, $limit";
	$r=mysqli_query($ms,$q);
	$response = new stdClass();
	$response->page = $page;
	$response->total = $total_pages;
	$response->records = $count;	
	$j=0;
	while($row=mysqli_fetch_array($r)){
		$imei=$row['imei'];
		$service_id = $row["service_id"];
		$object=getObjectName($imei);
		$enginehours='No';$odometerleft='NO';$daysleft='No';$lastservice='No';
		$odometer = getObjectOdometer($imei);
		$odometer = floor(convDistanceUnits($odometer, 'km', $_SESSION["unit_distance"]));		
		$engine_hours = getObjectEngineHours($imei, false);
		$service_id = $row["service_id"];
		$name = $row['name'];
		
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
				$odometerleft = '<font color="red">'.$la['ODOMETER_EXPIRED'].' ('.$odo_diff.' '.$la["UNIT_DISTANCE"].')</font>';
			}
			else
			{
				$odometerleft = $la['ODOMETER_LEFT'].' ('.$odo_diff.' '.$la["UNIT_DISTANCE"].')';
			}
		}
		
		if ($row['engh'] == 'true')
		{
			$engh_diff = $engine_hours - $row['engh_last'];
			$engh_diff = $row['engh_interval'] - $engh_diff;
			
			if ($engh_diff <= 0)
			{
				$engh_diff = abs($engh_diff);
				$enginehours = '<font color="red">'.$la['ENGINE_HOURS_EXPIRED'].' ('.$engh_diff.' '.$la["UNIT_H"].')</font>';
			}
			else
			{
				$enginehours = $la['ENGINE_HOURS_LEFT'].' ('.$engh_diff.' '.$la["UNIT_H"].')';
			}
		}
		 
		if ($row['days'] == 'true')
		{
			$days_diff = strtotime(gmdate("Y-m-d")) - (strtotime($row['days_last']));
			$days_diff = floor($days_diff/3600/24);
			$days_diff = $row['days_interval'] - $days_diff;
			
			if ($days_diff <= 0)
			{
				$days_diff = abs($days_diff);
				$lastservice = '<font color="red">'.$row['days_last'].'</font>';
				$daysleft = '<font color="red">'.$la['DAYS_EXPIRED'].' ('.$days_diff.')</font>';
			}
			else
			{
				$lastservice = $row['days_last'];
				$daysleft = $la['DAYS_LEFT'].' ('.$days_diff.')';
			}
		}
		// $modify = '<a href="#" onclick="dashboardServiceEdit(\''.$imei.'\',\''.$service_id.'\',\'edit\');" title="'.$la['EDIT'].'"><img src="theme/images/edit.svg" />';
		// $modify .= '</a><a href="#" onclick="dashboardServiceEdit(\''.$imei.'\',\''.$service_id.'\',\'delete\');" title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>';
		$response->rows[$j]['id']=$imei;
		$response->rows[$j]['cell']=array($object,$name,$odometerleft,$enginehours,$lastservice,$daysleft,'');
		$j++;
	}
header('Content-type: application/json');
echo json_encode($response);
die;
}

if(@$_POST['cmd']=='dispenser_dashboard_details'){
	$vehiclecount=0;
	$dispensing_count=0;
	$tanker_low_fuel=0;
	$vehicl_low_fuel=0;
	$today_dispensing_qty=0;
	$tanker_ilock=0;
	$dispenser_ilock=0;
	$object_data=array();
	$table_data=array();	
	$tanker_low_fuel_status='';
	$vehicle_low_fuel_status='';
	$q="SELECT a.* FROM gs_objects a left join gs_user_objects b on a.imei=b.imei where b.user_id='".$user_id."'";
	$r=mysqli_query($ms,$q);
	if(mysqli_num_rows($r)>0){
		while ($row=mysqli_fetch_assoc($r)) {
			$row['param']=json_decode($row['params'],true);

			$vehiclecount+=1;			
			if(isset($row['param']['AC']) && $row['param']['AC']=='1'){
				$dispensing_count+=1;
			}
			$today_dispensing_qty=get_today_dispensing_qty($row['imei']);
			
			$lock_id=array();
			if($row['dispenser_lock_id']!=''){
				$lock_id[]=$row['dispenser_lock_id'];
			}
			if($row['tanker_lock_id']!=''){
				$lock_id[]=$row['tanker_lock_id'];
			}
			$locks=implode(',',$lock_id);

			$url=$gsValues['iLock_status'].'?cmd=Get_Lock_Status&imei='.$locks.'';

			$lockstatus = file_get_contents($url,true);
			$lockstatus=json_decode($lockstatus,true);

			$dis_lock=@$lockstatus['status'][$row['dispenser_lock_id']];
			$tanker_lock=@$lockstatus['status'][$row['tanker_lock_id']];

			// if($row['imei']=='861359036779450'){
			// 	$dis_lock='Open';
			// 	$tanker_lock='Locked';
			// }

			if($dis_lock=='Open'){
				$dispenser_ilock+=1;
			}

			if($tanker_lock=='Open'){
				$tanker_ilock+=1;
			}

			if($row['dis_tracker_imei']!=''){
				$darray=get_dispensor_trackerParams($row['dis_tracker_imei']);
				$row['param'] = mergeParams($row['param'], $darray);

				$fuelLevel=calculate_Fuel_ltr($darray);
				if($row['tanker_low_fuel']!='' && $fuelLevel['compartment']<$row['tanker_low_fuel']){
					$tanker_low_fuel+=1;
					$tanker_low_fuel_status='Yes';
				}
				if($row['vehicle_low_fuel']!='' && $fuelLevel['v_tank']<$row['vehicle_low_fuel']){
					$vehicl_low_fuel+=1;
					$vehicle_low_fuel_status='Yes';
				}
			}

			$dt_now = gmdate("Y-m-d H:i:s");
			$dt_difference = strtotime($dt_now) - strtotime($row['dt_server']);
			if($dt_difference < 1440 * 60)
			{
				$loc_valid = $row['loc_valid'];
				
				if ($loc_valid == 1)
				{
					$conn = 2;
				}
				else
				{
					$conn = 1;
				}	
			}
			else
			{
				// offline status
				if (strtotime($row['dt_server']) > 0)
				{
					$result[$imei]['st'] = 'off';
					$result[$imei]['ststr'] = $la['OFFLINE'].' '.getTimeDetails(strtotime(gmdate("Y-m-d H:i:s")) - strtotime($row['dt_server']), true);
				}
				
				$conn = 0;
				$speed = 0;
			}

			$name=$row['name'];
			$imei=$row['imei'];
			$date=$row['dt_tracker'];
			$acc=@$row['param']['acc'];
			$ac=@$row['param']['ac'];
			$compartment1=@$row['param']['T_fuel2'];
			$compartment2=@$row['param']['T_fuel3'];
			$v_tank=@$row['param']['T_fuel1'];
			$lastevent=get_ObjectLastevent($row['imei']);
			$table_data[$row['imei']]=array('name'=>$name,'imei'=>$imei,'connection'=>$conn,'dt_tracker'=>$date,'dispenser_status'=>$ac,'acc'=>$acc,'compartment1'=>$compartment1,'compartment2'=>$compartment2,'v_tank'=>$v_tank,'tanker_ilock'=>$tanker_lock,'dispenser_ilock'=>$dis_lock,'lat'=>$row['lat'],'lng'=>$row['lng'],'last_event'=>$lastevent,'tanker_low_fuel'=>$tanker_low_fuel_status,'vehicle_low_fuel'=>$vehicle_low_fuel_status);
			// $object_data[]=$row;
		}
	}
	$response=array('totaldispenser'=>$vehiclecount,'dispensing_vehicle'=>$dispensing_count,'tanker_lowfuel'=>$tanker_low_fuel,'vehicle_lowfuel'=>$vehicl_low_fuel,'today_dispensing'=>$today_dispensing_qty,'tanker_ilock'=>$tanker_ilock,'dispenser_ilock'=>$dispenser_ilock,'table_data'=>$table_data);

	header('Content-type: application/json');
	echo json_encode($response);
	die;
}

function calculate_Fuel_ltr($darray){
	$compartment=0;
	$v_tank=0;
	if(isset($darray['T_fuel1'])){
		$v_tank+=$darray['T_fuel1'];
	}
	if(isset($darray['T_fuel2'])){
		$compartment+=$darray['T_fuel2'];
	}
	if(isset($darray['T_fuel3'])){
		$compartment+=$darray['T_fuel3'];
	}
	if(isset($darray['T_fuel4'])){
		$compartment+=$darray['T_fuel4'];
	}
	if(isset($darray['T_fuel5'])){
		$compartment+=$darray['T_fuel5'];
	}
	$result=array('compartment'=>$compartment,'v_tank'=>$v_tank);
	return $result;
}

function get_today_dispensing_qty($imei){
	global $ms;
	$ltr=0;
	$q="SELECT * FROM command where imei='".$imei."' and command='Last_Delivery_Alarm' and Status='Finished'";
	$r=mysqli_query($ms,$q);
	if(mysqli_num_rows($r)>0){
		while ($row=mysqli_fetch_assoc($r)) {
			$ltr_data=explode(',',$row['response']);
			$ltr+=$ltr_data[1];
		}
	}
	return $ltr;
}

function GetObjectRFID_Details($imei,$from,$to){
	global $ms;
	$return=array();
	$q="SELECT dt_swipe,imei,lat,lng,rfid FROM gs_rfid_swipe_data WHERE imei='".$imei."' and dt_swipe BETWEEN '".$from."' and '".$to."' ORDER BY dt_swipe ASC";
	$r=mysqli_query($ms,$q);
	if(mysqli_num_rows($r)>0){
		while($row=mysqli_fetch_array($r)){
			$return[]=$row;
		}
	}
	return $return;
}
?>
