
<?php
 
 // Code Done by vetrivel.N
 
   date_default_timezone_set("Asia/Kolkata");


 
   include ('../init.php');
   include ('Base.php');
   include ('../tools/gc_func.php');

	$imei="";	
	$key="";
	$ip="";
	
	$result=array();
	$result["type"]="";
	$result["msg"]="";
	$result["data"]="";
	//$_POST["key"]="PlayGps_Garudar_Ramco*#";
	//$_POST["cmd"]="GetLive_LastEvent";
	//$_POST["show_address"]="true";
// echo encrypt('Play@ApNKey');

// $myfile = fopen("request.txt", "a");
// fwrite($myfile,date('Y-m-d H:i:s').'  -  '.get_client_ip() );
// fwrite($myfile, "\n");
// fwrite($myfile,json_encode($_GET));
// fwrite($myfile, "\n");
// fwrite($myfile,json_encode($_POST));
// fwrite($myfile, "\n");
// fclose($myfile);

global $ms;
	if(isset($_GET["cmd"]))
	$_POST=$_GET;


	$ip=get_client_ip();
	If(isset($_POST["key"]))
	{
		$key=$_POST["key"];	 
		$de_key=decrypt($_POST["key"]);
		if($de_key==''){
			$de_key='.';
		}
	}
	else
	{
		$result["type"]="Error";
		$result["msg"]="Invalid Key.";	
		echo json_encode($result);	
        die;  
	}
	
	if(@$_POST["show_address"]=="true")
	{
		$show_addresses=true;
		$zones_addresses=false;
	}
	else {
		$show_addresses=false;
		$zones_addresses=true;
	}

	if($key=='PlayGps_Garudar_Ramco*#')
	$key='PlayGps_Garudar_Ramco*';



	if(@$_POST["cmd"]=="GetLive_LastEvent")
	{
		$q = "SELECT * FROM `gs_users` WHERE `userapikey` = '".$key."'  LIMIT 1";

		$r = mysqli_query($ms,$q);
		if ($row = mysqli_fetch_array($r))
		{
			if ($row['active'] == 'true')
			{
					$userid=$row['id'];

					$privileges = json_decode($row["privileges"], true);
   				
					//$privileges=explode('|',$row['privileges']);

					if(count($privileges)<0)
					{
						$result["type"]="Error";
						$result['msg'] = 'Invalid Account Details';
						header('Content-type: application/json');
						echo json_encode($result);
						die;
					}
					$rtnary=array();
					$accnttype=$privileges["type"];
					if(isset($privileges["imei"]))
					$imeis=$privileges["imei"];
					$q1="";
					
					if($accnttype!="subuser")
						$q1 = "select gt.imei,gt.dt_tracker,gt.lat,gt.lng,gt.speed,gt.angle,gt.altitude,gt.odometer,gt.name,gt.sim_number,gt.plate_number from gs_objects gt  join gs_user_objects gut on gt.imei=gut.imei and gut.user_id='".$userid."' ";
					else
						$q1 = "select gt.imei,gt.dt_tracker,gt.lat,gt.lng,gt.speed,gt.angle,gt.altitude,gt.odometer,gt.name,gt.sim_number,gt.plate_number from gs_objects gt  join gs_user_objects gut on gt.imei=gut.imei and gut.imei in (".$imeis.") ";

						$r1 = mysqli_query($ms,$q1);
					while($row1 = mysqli_fetch_array($r1))
					{
						$snlg=array();
						// $snlg["VehicleId"]=$row1["sim_number"];
						// $snlg["VehicleName"]=$row1["plate_number"];
						// $snlg["VehicleRTONo"]=$row1["name"];
						$snlg["VehicleNumber"]=$row1["name"];
						$snlg["VehicleLocation"]=reportsGetPossition($row1["lat"],$row1["lng"],$show_addresses,$zones_addresses);
						$snlg["VehicleGpsDateTime"]=date("d-m-Y H:i:s",strtotime($row1['dt_tracker'].$row['timezone']));
						$snlg["VehicleLatitude"]=$row1["lat"];
						$snlg["VehicleLongitude"]=$row1["lng"];
						$snlg["VehicleSpeed"]=$row1["speed"];
						$snlg["VehicleOdometer"]=$row1["odometer"];
						// $snlg["TransporterName"]="Garudar";
							
						$server_time=gmdate("Y-m-d")." 00:00:00";
						$cur_time= gmdate("Y-m-d H:i:s");
												
						$qmlg="select mileage from gs_tracker_data_".$row1["imei"]." where dt_tracker between '".$server_time."' and '".$cur_time."' order by dt_tracker desc;";
						//echo $qmlg;
						$mileage=GetRecord($qmlg);
						if(count($mileage)>0)
						{
							$snlg["VehicleDayKm"]=$mileage[0]["mileage"]- $mileage[count($mileage)-1]["mileage"];
						}
						else
							$snlg["VehicleDayKm"]="0";

						
						$event=GetRecord("select dt_tracker,event_desc,type from gs_user_events_data where imei='".$row1["imei"]."' and type in('Zone_Out','Zone_In','zone_out','zone_in') order by event_id desc limit 1");
						if(count($event)>0)
						{
							$snlg["GeofenceLocation"]=$event[0]["event_desc"];
							$snlg["GeofenceInOUt"]=$event[0]["type"];
							$snlg["GeofenceDateTime"]=date("d-m-Y H:i:s",strtotime($event[0]["dt_tracker"].$row['timezone']));
						}
						else
						{
							$snlg["GeofenceLocation"]="";
							$snlg["GeofenceInOUt"]="";
							$snlg["GeofenceDateTime"]="";
						}
						
						
						$rtnary[]=$snlg;
					}
					
					$result["type"]="Success";
					$result['data'] = $rtnary;
										
				
			}
			else
			{
				$result["type"]="Error";
				$result['msg'] = 'Your Account Is Locked';

			}
		}
		else
		{
			$result["type"]="Error";
			$result['msg'] = 'Invalid API Access Details';
		}
	}
	else if(@$_POST["cmd"]=="Get_Gps")
	{
		$q = "SELECT * FROM `gs_users` WHERE (`userapikey` = '".$key."' OR `userapikey`='".$de_key."') LIMIT 1";
			// echo $q;
		
		$r = mysqli_query($ms,$q);
		if ($row = mysqli_fetch_array($r))
		{
			if ($row['active'] == 'true')
			{
					$userid=$row['id'];
					$privileges = json_decode($row["privileges"], true);
					if(count($privileges)<0)
					{
						$result["type"]="Error";
						$result['msg'] = 'Invalid Account Details';
						header('Content-type: application/json');
						echo json_encode($result);
						die;
					}
					$rtnary=array();
					$accnttype=$privileges["type"];
					if(isset($privileges["imei"]))
					$imeis=$privileges["imei"];
					$q1="";
					
					if($accnttype!="subuser")
						$q1 = "select gt.imei,gt.dt_tracker,gt.lat,gt.lng,gt.speed,gt.angle,gt.altitude,gt.odometer,gt.name,gt.sim_number,gt.plate_number,gt.params from gs_objects gt  join gs_user_objects gut on gt.imei=gut.imei and gut.user_id='".$userid."' ";
					else
						$q1 = "select gt.imei,gt.dt_tracker,gt.lat,gt.lng,gt.speed,gt.angle,gt.altitude,gt.odometer,gt.name,gt.sim_number,gt.plate_number,gt.params from gs_objects gt  where gt.imei in (".$imeis.") ";

						$r1 = mysqli_query($ms,$q1);
					while($row1 = mysqli_fetch_array($r1))
					{
						$snlg=array();
						
						$sensor = getSensorFromType($row1['imei'], 'acc');
						// $sensor = $sensor[0];						
						$params = json_decode($row1['params'],true);
						$data = @$params[@$sensor[0]['param']];
						
						$snlg["Imei"]= @$row1["imei"];
						$snlg["RegNo"]=$row1["name"];
						$snlg["Time"]=date("d-m-Y H:i:s",strtotime($row1['dt_tracker'].$row['timezone']));
						$snlg["Lat"]=$row1["lat"];
						$snlg["Lng"]=$row1["lng"];
						$snlg["Speed"]=$row1["speed"];
						$snlg["Odometer"]=$row1["odometer"];
						$snlg["Ignition"]=@$data;
						// $snlg["address"]=getOSMaddress($row1["lat"],$row1["lng"]);
						$rtnary[]=$snlg;
					}
					$result["type"]="Success";
					$result['data'] = $rtnary;
			}
			else
			{
				$result["type"]="Error";
				$result['msg'] = 'Your Account Is Locked';

			}
		}
		else
		{
			$result["type"]="Error";
			$result['msg'] = 'Invalid API Access Details';
		}
	}
	else if(@$_POST["cmd"]=="Get_Event"){
		$q="SELECT a.*,b.active as api_active,b.last_access_event_id FROM gs_users a LEFT JOIN api_setting b ON a.id=b.user_id WHERE (b.apikey='".$key."' OR b.apikey='".$de_key."') ";
		$r=mysqli_query($ms,$q);
		if(mysqli_num_rows($r)>0){
			$row=mysqli_fetch_assoc($r);
			if($row['api_active']=='true'){
				$rtnary=array();
				$event="SELECT a.dt_tracker,a.event_desc,a.type,a.imei,a.dt_tracker,a.lat,a.lng,a.speed,a.event_id,c.name from gs_user_events_data as a left join gs_user_objects b on a.imei=b.imei AND a.user_id=b.user_id left join gs_objects c on b.imei=c.imei WHERE b.user_id='".$row['id']."' and a.event_id>'".$row['last_access_event_id']."' order BY a.event_id desc";
				
				$userid=$row['id'];
				$privileges = json_decode($row["privileges"], true);
				if(count($privileges)<0)
				{
					$result["type"]="Error";
					$result['msg'] = 'Invalid Account Details';
					header('Content-type: application/json');
					echo json_encode($result);
					die;
				}
				$rtnary=array();
				$accnttype=$privileges["type"];
				if(isset($privileges["imei"]))
				$imeis=$privileges["imei"];
				$q1="";

				if($accnttype!="subuser"){
					$event="SELECT a.dt_tracker,a.event_desc,a.type,a.imei,a.dt_tracker,a.lat,a.lng,a.speed,a.event_id,c.name from gs_user_events_data as a left join gs_user_objects b on a.imei=b.imei AND a.user_id=b.user_id left join gs_objects c on b.imei=c.imei WHERE b.user_id='".$row['id']."' and a.event_id>'".$row['last_access_event_id']."' order BY a.event_id desc limit 1000";
				} else{
					$event="SELECT a.dt_tracker,a.event_desc,a.type,a.imei,a.dt_tracker,a.lat,a.lng,a.speed,a.event_id,c.name from gs_user_events_data as a left join gs_user_objects b on a.imei=b.imei AND a.user_id=b.user_id left join gs_objects c on b.imei=c.imei WHERE c.imei in (".$imeis.") and a.event_id>'".$row['last_access_event_id']."' order BY a.event_id desc limit 1000";
				}
				$eventR=mysqli_query($ms,$event);
				if(mysqli_num_rows($eventR)>0){
					while($row_event=mysqli_fetch_assoc($eventR)){
						
						if(!isset($lastevent_id)){
							$lastevent_id=$row_event['event_id'];
						}

					
					if($key == "Paiz0BoA" ){

							if($row_event["type"] =="sos"){
								$snlg=array();
								$snlg["deviceImei"]=@$row_event["imei"];
								$snlg["deviceId"]=$row_event["name"];
								$snlg["timestamp"]=strtotime($row_event['dt_tracker'].$row['timezone']);
								$snlg["latitude"]=$row_event["lat"];
								$snlg["longitude"]=$row_event["lng"];
								$snlg["speed"]=$row_event["speed"];
								$snlg["bearing"]=0;
								$snlg["deviceType"]="AUTOCOP";
								$snlg["alertMap"]=["PANIC" => true];
								$rtnary[]=$snlg;
						}
						
					}else{
						$snlg=array();
						$snlg["Imei"]=@$row_event["imei"];
						$snlg["RegNo"]=$row_event["name"];
						$snlg["Time"]=date("d-m-Y H:i:s",strtotime($row_event['dt_tracker'].$row['timezone']));
						$snlg["Type"]=$row_event["type"];
						$snlg["Title"]=$row_event["event_desc"];
						$snlg["Lat"]=$row_event["lat"];
						$snlg["Lng"]=$row_event["lng"];
						$snlg["Speed"]=$row_event["speed"];
						$rtnary[]=$snlg;
					}
	
					}
					$qu="UPDATE api_setting SET last_access_event_id='".$lastevent_id."' WHERE (apikey='".$key."' OR apikey='".$de_key."') and user_id='".$row['id']."'";
					mysqli_query($ms,$qu);
					$result["type"]="Success";
					$result['data'] = $rtnary;
				}else{
					$result["type"]="Success";
					// $result['data'] = 'No Event Found.';
					$result['data'] = array();
				}
			}else{
				$result["type"]="Error";
				$result['data'] = 'Invalid API.';
			}
		}else{
			$result["type"]="Error";
			$result['data'] = 'Invalid API';
		}
	}
	else if(@$_POST["cmd"]=="Get_Daily_km4")
	{
		$q = "SELECT * FROM `gs_users` WHERE `userapikey` = '".$key."' LIMIT 1";
		
		$r = mysqli_query($ms,$q);
		if ($row = mysqli_fetch_array($r))
		{
			if ($row['active'] == 'true')
			{
					$userid=$row['id'];
					$privileges = json_decode($row["privileges"], true);
					if(count($privileges)<0)
					{
						$result["type"]="Error";
						$result['msg'] = 'Invalid Account Details';
						header('Content-type: application/json');
						echo json_encode($result);
						die;
					}
					$rtnary=array();
					$accnttype=$privileges["type"];
					if(isset($privileges["imei"]))
					$imeis=$privileges["imei"];
					$q1="";
					
					if($accnttype!="subuser")
						$q1 = "SELECT go.name,cd.distance,(cd.dt_create) date FROM ckm_dailykm cd  JOIN gs_objects go ON go.imei=cd.imei join gs_user_objects gut on cd.imei=gut.imei and gut.user_id='".$userid."' and date(cd.dt_create)='".date('Y-m-d')."'";
					else
						$q1 = "SELECT go.name,cd.distance,(cd.dt_create) date FROM ckm_dailykm cd  JOIN gs_objects go ON go.imei=cd.imei  where go.imei in (".$imeis.")  and date(cd.dt_create)='".date('Y-m-d')."'";
					
						$r1 = mysqli_query($ms,$q1);
					while($row1 = mysqli_fetch_assoc($r1))
					{
						$snlg=array();
						$rtnary[]=$row1;
					}
					$result["type"]="Success";
					$result['data'] = $rtnary;
			}
			else
			{
				$result["type"]="Error";
				$result['msg'] = 'Your Account Is Locked';

			}
		}
		else
		{
			$result["type"]="Error";
			$result['msg'] = 'Invalid API Access Details';
		}
	}
	else if(@$_POST["cmd"]=="Get_Event_list")
	{
		$q = "SELECT gu.id,gu.active,gu.privileges,gu.username,a.last_runtime,a.api_name,a.api_data FROM gs_users gu 
			   join api_setting a on a.user_id=gu.id and a.api_name='PUSH' WHERE userapikey = '".$key."'  LIMIT 1";
		
		$r = mysqli_query($ms,$q);
		if ($row = mysqli_fetch_array($r))
		{
			if ($row['active'] == 'true')
			{
					$userid=$row['id'];
					$privileges = json_decode($row["privileges"], true);
					$api_data = json_decode($row["api_data"], true);
					if(count($privileges)<0 || !isset($api_data))
					{
						$result["type"]="Error";
						$result['msg'] = 'Invalid Account Details';
						header('Content-type: application/json');
						echo json_encode($result);
						die;
					}
					$rtnary=array();
					$accnttype=$privileges["type"];
					if(isset($privileges["imei"]))
					$imeis=$privileges["imei"];
					$q1="";	
					$curent_time=gmdate("Y-m-d H:i:s");	
					$qc = "update api_setting set last_runtime='".$curent_time."'  where user_id='".$userid."' and api_name='".$row['api_name']."'";
					$rc = mysqli_query($ms,$qc);
					
					//$q1 = "select gue.dt_tracker,gue.event_desc,gue.lat,gue.lng,go.name from gs_user_events_data gue  join gs_objects go on go.imei=gue.imei where gue.user_id='".$userid."'  and gue.dt_server between '2017-12-24 00:00:00' and '2017-12-25 00:00:00' and type in (".$api_data["events"].")";
					$q1 = "select gue.dt_tracker,gue.event_desc,gue.lat,gue.lng,go.name,gue.type from gs_user_events_data gue  join gs_objects go on go.imei=gue.imei where gue.user_id='".$userid."'  and gue.dt_server between '".$row['last_runtime']."' and '".$curent_time."' and type in (".$api_data["events"].")";
					//str_replace(" ","",$row1["name"])
					$r1 = mysqli_query($ms,$q1);
			
					
					$eventlist=$api_data["event_syn"];

					$dv=array();
					while($row1 = mysqli_fetch_array($r1))
					{
						$data = array("api" => "Ti0Kf5Wyx/4=","date" => $row1["dt_tracker"],"vehicle_number"=>str_replace(" ","",$row1["name"]),
    					"driver_code" =>"","lat_lon"=>$row1["lat"].",".$row1["lng"],"issue_type"=>$eventlist[$row1["type"]] );
    					$dv[]=$data;	
					}
					
					$url = $api_data["url"];
    				$data = array("data" => $dv);
    			    $data_string = json_encode($data);
					$options = array(
							'http' => array(
							'header'  => "Content-type:application/json",
							'method'  => 'POST',
							'content' => $data_string
					));
					$context  = stream_context_create($options);
					$result = file_get_contents($url, false, $context);
					
					die;
			}
			else
			{
				$result["type"]="Error";
				$result['msg'] = 'Your Account Is Locked';

			}
		}
		else
		{
			$result["type"]="Error";
			$result['msg'] = 'Invalid API Access Details';
		}
	}
	else if(@$_POST["cmd"]=="GetObjectList")
	{
		if(@$_POST["key"]=="OpenAuthToChange")
		{
			$q1 = "select imei from gs_objects";
			$r1 = mysqli_query($ms,$q1);
			$objary=array();
			while($row1 = mysqli_fetch_assoc($r1))
			{
				$objary[]=array("imei"=>$row1["imei"]);
			}
			$result["type"]="Success";
			$result['data'] = $objary;
		}
		else {
			$result["type"]="Error";
			$result['msg'] = 'Invalid Api key';
		}
	}
	else if(@$_POST["cmd"]=="Get_Object_History"){
		$q = "SELECT * FROM `gs_users` WHERE `userapikey` = '".$key."'  LIMIT 1";
		$r = mysqli_query($ms,$q);
		if ($row = mysqli_fetch_array($r))
		{
				$object='';
				if ($row['active'] == 'true')
				{
					$userid=$row['id'];
					$privileges = json_decode($row["privileges"], true);
					if(count($privileges)<0)
					{
						$result["type"]="Error";
						$result['msg'] = 'Invalid Account Details';
						header('Content-type: application/json');
						echo json_encode($result);
						die;
					}

					if(isset($_POST['vehicle_number']) || isset($_POST['from_date']) || isset($_POST['to_date'])){
						$object=trim($_POST['vehicle_number']);
						$from_date_UTC = gmdate("Y-m-d H:i:s", strtotime($_POST['from_date']));	
						$to_date_UTC = gmdate("Y-m-d H:i:s", strtotime($_POST['to_date']));	
					}else{
						$result["type"]="Error";
						$result['msg'] = 'API Parameter Missing';
						header('Content-type: application/json');
						echo json_encode($result);
						die;
					}

					$rtnary=array();
					$accnttype=$privileges["type"];
					if(isset($privileges["imei"]))
					$imeis=$privileges["imei"];
					$q1="";					

					$q1="SELECT a.imei,a.name FROM gs_objects a left join gs_user_objects b on a.imei=b.imei where a.name LIKE '%$object%' ";
					$r1=mysqli_query($ms,$q1);
					if(mysqli_num_rows($r1)>0){
							$row1=mysqli_fetch_array($r1);
							$q2="select dt_tracker,lat,lng,speed,mileage,params FROM gs_object_data_".$row1['imei']." where dt_tracker between '".$from_date_UTC."' and '".$to_date_UTC."' ";
							$r2=mysqli_query($ms,$q2);
							$sensor = getSensorFromType($row1['imei'], 'acc');

							while($row2=mysqli_fetch_assoc($r2)){
									$snlg=array();
							
								$params = json_decode($row2['params'],true);
								$data = @$params[@$sensor[0]['param']];

								$snlg["Imei"]=$row1['imei'];
								$snlg["RegNo"]=$row1["name"];
								$snlg["Time"]=date("d-m-Y H:i:s",strtotime($row2['dt_tracker'].$row['timezone']));
								$snlg["Lat"]=$row2["lat"];
								$snlg["Lng"]=$row2["lng"];
								$snlg["Speed"]=$row2["speed"];
								$snlg["Odometer"]=$row2["mileage"];
								$snlg["Ignition"]=@$data;
								// $snlg["address"]=getOSMaddress($row1["lat"],$row1["lng"]);
								$rtnary[]=$snlg;
							}
							$result["type"]="Success";
							$result['data'] = $rtnary;
						}else{
									$result["type"]="Error";
								$result['msg'] = 'Object not Found';
						}
				}
			else
			{
				$result["type"]="Error";
				$result['msg'] = 'Your Account Is Locked';

			}
		}

	}
	else if(@$_POST["cmd"]=="Get_RFID"){

		$q="SELECT a.*,b.active as api_active,b.last_access_event_id FROM gs_users a LEFT JOIN api_setting b ON a.id=b.user_id WHERE (b.apikey='".$key."' OR b.apikey='".$de_key."') ";
		$r=mysqli_query($ms,$q);
		if(mysqli_num_rows($r)>0){
			$row=mysqli_fetch_assoc($r);
			if($row['api_active']=='true'){
				$rtnary=array();
				$event="SELECT a.dt_tracker,a.event_desc,a.type,a.imei,a.dt_tracker,a.lat,a.lng,a.speed,a.event_id,c.name from gs_user_events_data as a left join gs_user_objects b on a.imei=b.imei AND a.user_id=b.user_id left join gs_objects c on b.imei=c.imei WHERE b.user_id='".$row['id']."' and a.event_id>'".$row['last_access_event_id']."' order BY a.event_id desc";
				
				$userid=$row['id'];
				$privileges = json_decode($row["privileges"], true);
				if(count($privileges)<0)
				{
					$result["type"]="Error";
					$result['msg'] = 'Invalid Account Details';
					header('Content-type: application/json');
					echo json_encode($result);
					die;
				}
				$rtnary=array();
				$accnttype=$privileges["type"];
				if(isset($privileges["imei"]))
				$imeis=$privileges["imei"];

				$resultary=array();
				$q="SELECT s.swipe_id, s.dt_server, s.dt_swipe, s.lat, s.lng, s.imei, s.rfid FROM gs_rfid_swipe_data s JOIN gs_user_objects u ON s.imei = u.imei WHERE s.status = '0' AND u.user_id = ".$userid." LIMIT 1000 ";
				$r=mysqli_query($ms,$q);
				$arystr='';
				while($rowkey=mysqli_fetch_assoc($r))
				{
					$resultary[]=$rowkey;
					if($arystr=='')
					$arystr=$rowkey["swipe_id"];
					else 
					$arystr=$arystr.','.$rowkey["swipe_id"];
				}
				$q="update gs_rfid_swipe_data set status=1 where swipe_id in (".$arystr.")";
				$r=mysqli_query($ms,$q);		  
				
				$result['type'] = 'Success';
				$result['data'] = $resultary;
				$result['msg'] = ""; 
			

			}else{
				$result["type"]="Error";
				$result['data'] = 'Invalid API.';
			}
		}else{
			$result["type"]="Error";
			$result['data'] = 'Invalid API';
		}
	}
	else
	{
		$result["type"]="Error";
		$result['msg'] = 'Invalid Command';
			
	}
	
	 
	header('Content-type: application/json');
	echo json_encode($result);
	die;

	 
	function reportsGetPossition($lat, $lng, $show_addresses, $zones_addresses)
	{
		global $user_id, $zones_addr, $zones_addr_loaded,$ms;
		
		$position=null;
        
         if($lat!='' && $lng!='')
         $position=$lat.','.$lng;
         
		//$position = '<a href="http://maps.google.com/maps?q='.$lat.','.$lng.'&t=m" target="_blank">'.$lat.' &deg;, '.$lng.' &deg;</a>';
		
		if ($zones_addresses == 'true')
		{
			if ($zones_addr_loaded == false)
			{
				$q = "SELECT * FROM `gs_user_zones` WHERE `user_id`='".$user_id."'";
				$r = mysqli_query($ms,$q);
				
				while($row=mysqli_fetch_assoc($r))
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
					$position .= ' - '.$zone_name;
					
					return $position;
				}
			}
		}
		
		if ($show_addresses == 'true')
		{
			usleep(150000);
            if($lat!='' && $lng!='')
			$position = geocoderGetAddress($lat, $lng);
    
		}
		
		return $position;
	}
	
   function GetRecord($qry)
    {	
    	global $ms;
    	$rtnary=array();
        $r = mysqli_query($ms,$qry);
        if($r)
        {
        	while($row = mysqli_fetch_assoc($r))
			{
				$rtnary[] = $row;
			}
			return $rtnary;
        }
        
        return false;
    }
          
function getOSMaddress($lat,$lng){
	// $lat=12.9097916;
	// $lng=80.2362858;
	if($lat!='' && $lng!=''){
		$search_url = "https://nominatim.openstreetmap.org/search?q=".$lat.','.$lng."&format=json";
		// echo $search_url;

		$httpOptions = [
		    "http" => [
		        "method" => "GET",
		        "header" => "User-Agent: Nominatim-Test"
		    ]
		];

		$streamContext = stream_context_create($httpOptions);
		$json = file_get_contents($search_url, false, $streamContext);
		// echo $json;
		if(count($json)!=0){
		    $json=json_decode($json,true);
		    return json_encode($json[0]['display_name']);
		}else{
		    return '';
		}
	}else{
		return '';
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
?>


