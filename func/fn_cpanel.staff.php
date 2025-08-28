<?
	set_time_limit(0);
	
	session_start();
	include ('../init.php');
	include ('fn_common.php');
	include ('../tools/email.php');
	include ('../tools/sms.php');
	checkUserSession();
	checkUserCPanelPrivileges();
	loadLanguage($_SESSION["language"], $_SESSION["units"]);
		
	global  $la,$ms;
		
	// check privileges
	if ($_SESSION["privileges"] == 'subuser')
	{
		$user_id = $_SESSION["manager_id"];
	}
	else
	{
		$user_id = $_SESSION["user_id"];
	}
	
	if(@$_GET['cmd'] == 'select_work_type')
	{
		$q = "SELECT * FROM staff_work  ORDER BY work_id ASC";
		$ret=mysqli_query($ms,$q);
		$rtnary=array();
		while($row = mysqli_fetch_assoc($ret))
		{
			$rtnary[]=$row;
		}
		$q = "SELECT * FROM staff_work_report  ORDER BY work_id ASC";
		$ret=mysqli_query($ms,$q);
		$rworkreport=array();
		while($row = mysqli_fetch_assoc($ret))
		{
			$rworkreport[]=$row;
		}
		$result["type"]="S";
		$result["msg"]="";
		$result["mydata"]["type"]=$rtnary;
		$result["mydata"]["report"]=$rworkreport;
		echo json_encode($result);
		die;
	}



header("Content-Type: application/json");

$input = json_decode(file_get_contents("php://input"), true);
$work = $input['work'] ?? '';

$result = ["type" => "E", "msg" => "Invalid work", "mydata" => []];

if ($work != '') {
    $types = [];

    // Example mapping (you can replace this with DB query)
    if ($work == "installation") {
        $types = [
            ["id" => 1, "name" => "CCTV Setup"],
            ["id" => 2, "name" => "Network Wiring"],
            ["id" => 3, "name" => "Device Mounting"]
        ];
    } elseif ($work == "offline") {
        $types = [
            ["id" => 4, "name" => "Troubleshooting"],
            ["id" => 5, "name" => "Cable Check"],
            ["id" => 6, "name" => "Router Reset"]
        ];
    } elseif ($work == "replace") {
        $types = [
            ["id" => 7, "name" => "Replace HDD"],
            ["id" => 8, "name" => "Replace Camera"],
            ["id" => 9, "name" => "Replace Adapter"]
        ];
    } elseif ($work == "general") {
        $types = [
            ["id" => 10, "name" => "Inspection"],
            ["id" => 11, "name" => "Cleaning"],
            ["id" => 12, "name" => "Testing"]
        ];
    }

    $result = ["type" => "S", "msg" => "", "mydata" => $types];
}

echo json_encode($result);
exit;



///////////////////////////////////////////////////////////////////////////////////


	
	if(@$_GET['cmd'] == 'select_client_type')
	{
		$q = "SELECT * FROM gs_users WHERE PRIVILEGES NOT LIKE '%subuser%' ORDER BY id ASC";
		$ret=mysqli_query($ms,$q);
		$rtnary=array();
		while($row = mysqli_fetch_assoc($ret))
		{
			$rtnary[]=$row;
		}
		$result["type"]="S";
		$result["msg"]="";
		$result["mydata"]=$rtnary;
		echo json_encode($result);
		die;
	}
	
	if(@$_GET['cmd'] == 'select_staff_data')
	{
		$q = "SELECT * FROM staff_data  ORDER BY staff_id ASC";
		$ret=mysqli_query($ms,$q);
		$rtnary=array();
		while($row = mysqli_fetch_assoc($ret))
		{
			$rtnary[]=$row;
		}
		$result["type"]="S";
		$result["msg"]="";
		$result["mydata"]=$rtnary;
		echo json_encode($result);
		die;
	}
	
	if(@$_GET['cmd'] == 'select_staff_imei')
	{
		$q = "select go.imei,go.name from gs_objects go join gs_user_objects guo on guo.imei=go.imei
	             where guo.user_id=".@$_GET['user'];
		$ret=mysqli_query($ms,$q);
		$rtnary=array();
		if($ret)
		{
			while($row = mysqli_fetch_assoc($ret))
			{
				$rtnary[]=$row;
			}
		}
		$result["type"]="S";
		$result["msg"]="";
		$result["mydata"]=$rtnary;
		echo json_encode($result);
		die;
	}

	// if(@$_POST['cmd'] == 'save_staff')
	// {
	// 	$service_id = $_POST["service_id"];
	// 	$client_id = $_POST["client_id"];
	// 	$staff_id = $_POST["staff_id"];
	// 	$site_location = $_POST["site_location"];
	// 	$schedule_date = $_POST["schedule_date"];
	// 	$intime = $_POST["intime"];
	// 	$outtime = $_POST["outtime"];
	// 	$imei = $_POST["imei"];
	// 	$works = $_POST["works"];
	// 	$work_type = $_POST["work_type"];
	// 	$warrenty = $_POST["warrenty"];
	// 	$office_note = $_POST["office_note"];
	
	
	// 	if($service_id==""){
	// 		$service_id=0;
	// 	}
	
	// 	$q = "select * from staff_service WHERE	`service_id`='".$service_id."'  ";
	// 	$r = mysqli_query($ms,$q);
	// 	if ($service_id == '0' || (!$rows=mysqli_fetch_assoc($r)))
	// 	{
	// 		$q = "INSERT INTO `staff_service` (user_id,client_id,staff_id,site_location,
	// 			 schedule_date,intime,outtime,imei,works,warrenty,office_note,create_by) VALUES (
	// 			 '".$user_id."','".$client_id."','".$staff_id."','".$site_location."',
	// 			 '".$schedule_date."','".$intime."','".$outtime."','".$imei."','".$works."',
	// 			 '".$warrenty."','".$office_note."','".$_SESSION["user_id"]."')";

	// 		$r = mysqli_query($ms,$q);
	// 		$service_ID = mysqli_insert_id($ms);
			
	// 		for($issub=0;$issub<count($work_type);$issub++)
	// 		{
	// 			$work_id=$work_type[$issub];
	// 			$q = "INSERT INTO `staff_service_sub` (user_id,service_id,work_id,create_by) VALUES (
	// 			 '".$user_id."','".$service_ID."','".$work_id."','".$_SESSION["user_id"]."')";
	// 			$r = mysqli_query($ms,$q);
	// 		}
	// 	}
	// 	else
	// 	{
	// 		$service_id=$_POST["service_id"];
	// 		$q = "UPDATE `staff_service`  SET  `client_id`='".$client_id."',`staff_id`='".$staff_id."',`site_location`='".$site_location."',
	// 		   `schedule_date`='".$schedule_date."',`intime`='".$intime."',`outtime`='".$outtime."',
	// 		   `imei`='".$imei."', `works`='".$works."',
	// 		   `warrenty`='".$warrenty."', `office_note`='".$office_note."',modify_by='".$_SESSION["user_id"]."'  WHERE user_id='".$user_id."' and `service_id`='".$service_id."' ";
	// 		$r = mysqli_query($ms,$q);

	// 		$service_ID = $service_id;
	// 		$q = "delete from `staff_service_sub` where service_id='".$service_ID."'";
				
	// 		$r = mysqli_query($ms,$q);
	// 		for($issub=0;$issub<count($work_type);$issub++)
	// 		{
	// 			$work_id=$work_type[$issub];
	// 			$q = "INSERT INTO `staff_service_sub` (user_id,service_id,work_id,create_by) VALUES (
	// 			 '".$user_id."','".$service_ID."','".$work_id."','".$_SESSION["user_id"]."')";
	// 			$r = mysqli_query($ms,$q);
	// 		}
			
	// 	}
	
		
	
		
		
	// 	echo 'OK';
	// 	die;
	// }
	if(@$_POST['cmd'] == 'save_staff')
	{
		$service_id = $_POST["service_id"];
		$client_id = $_POST["client_id"];
		$staff_id = $_POST["staff_id"];
		$company = $_POST["company"];
		$site_location = $_POST["site_location"];
		$work_date = $_POST["work_date"];
		$imei = $_POST["imei"];
		$chobjectname = $_POST["chobjectname"];
		$objectname = $_POST["objectname"];
		$chvehicletype = $_POST["chvehicletype"];
		$vehicletype = $_POST["vehicletype"];
		$chfuel_tanksize = $_POST["chfuel_tanksize"];
		$fuel_tanksize = $_POST["fuel_tanksize"];
		$chfuel1 = $_POST["chfuel1"];
		$fuel1 = $_POST["fuel1"];
		$chfuel2 = $_POST["chfuel2"];
		$fuel2 = $_POST["fuel2"];
		$simaction = $_POST["simaction"];
		$simnumber = $_POST["simnumber"];
		$works = $_POST["works"];
		$work_type = $_POST["work_type"];
		$accessories_list = $_POST["accessories_list"];
		$report_list = @$_POST["report_list"];				
		$status = $_POST["status"];				
		$remark = $_POST["remark"];				
		$service_close = $_POST["service_status"];
		$newsimid=$_POST['newsimid'];
		
		$warrenty = $_POST["warrenty"];
		$warrenty_expire = $_POST["warrenty_expire"];
		$reason = $_POST["reason"];	
		$editstaffdata = $_POST["editstaffdata"];

		$oldobjectname='';
		$oldvehicletype='';
		$oldtanksize='';
		$oldfuel1='';
		$oldfuel2='';
		$oldsimid='';
		if($service_id==""){
			$service_id=0;
		}

		$q = "select * from staff_service WHERE	`service_id`='".$service_id."'  ";
		$r = mysqli_query($ms,$q);
		if ($service_id == '0' || (!$rows=mysqli_fetch_assoc($r)))
		{
			if($chobjectname=='true'){
				$oldobjectname=$editstaffdata['name'];
			}
			if($chvehicletype=='true'){
				$oldvehicletype=$editstaffdata['vehicle_type'];
			}
			if($chfuel_tanksize=='true'){
				$oldtanksize=0;
			}
			if($chfuel1=='true'){
				$oldfuel1=$editstaffdata['fuel1'];
			}
			if($chfuel2=='true'){
				$oldfuel2=$editstaffdata['fuel2'];
			}
			if($simaction=='true'){
				$oldsimid=$editstaffdata['id'];
			}
			$q = "INSERT INTO `staff_service` (user_id,client_id,staff_id,company,site_location,
				 schedule_date,imei,check_object_name,object_name,old_object_name,check_vehical_type,vehicle_type,old_vehicle_type,check_tank_size,fuel_tank_size,old_fuel_tank_size,check_fuel1,fuel1,old_fuel1,check_fuel2,fuel2,old_fuel2,check_new_sim,sim_id,old_sim_id,works,warrenty,warrenty_expire,create_by,status,reason,remark) VALUES (
				 '".$user_id."','".$client_id."','".$staff_id."','".$company."','".$site_location."','".$work_date."','".$imei."','".$chobjectname."','".$objectname."','".$oldobjectname."','".$chvehicletype."','".$vehicletype."','".$oldvehicletype."','".$chfuel_tanksize."','".$fuel_tanksize."','".$oldtanksize."','".$chfuel1."','".$fuel1."','".$oldfuel1."','".$chfuel2."','".$fuel2."','".$oldfuel2."','".$simaction."','".$newsimid."','".$oldsimid."','".$works."',
				 '".$warrenty."','".$warrenty_expire."','".$_SESSION["user_id"]."','".$status."','".$reason."','".$remark."')";

			$r = mysqli_query($ms,$q);

			$service_ID = mysqli_insert_id($ms);
			if(count($work_type)>0){
				for($issub=0;$issub<count($work_type);$issub++)
				{
					$work_id=$work_type[$issub];
					$q = "INSERT INTO `staff_service_sub` (user_id,service_id,work_id,create_by) VALUES (
					 '".$user_id."','".$service_ID."','".$work_id."','".$_SESSION["user_id"]."')";
					$r = mysqli_query($ms,$q);

					if( $service_close!='' && ($work_id=="37" || $work_id==37)){

						$qu="SELECT user_id FROM gs_user_objects where imei='".$imei."'";
						
						$rr=mysqli_query($ma,$qu);
						if(mysqli_num_rows($rr)!=0){
							while($rowr=mysqli_fetch_assoc($rr)){
								$qr="INSERT INTO staff_service_removed_list (`service_id`,`actionby_id`,`imei`,`title`,`removed_from`) VALUES ('".$service_ID."','".$_SESSION["user_id"]."','".$imei."','Remove User','".$rowr['user_id']."')";
								$r = mysqli_query($ms,$qr);
							}
						}

						$qu="UPDATE gs_simcard_details SET status='DI' where id=(SELECT sim_id FROM gs_objects where imei='".$imei."')";
						$r = mysqli_query($ms,$qu);

						$qr="INSERT INTO staff_service_removed_list (`service_id`,`actionby_id`,`imei`,`title`,`removed_from`) VALUES ('".$service_ID."','".$_SESSION["user_id"]."','".$imei."','Remove Sim','".$oldsimid."')";
						$r = mysqli_query($ms,$qr);

						$qu="UPDATE gs_objects SET sim_id='0',sim_number='',old_sim_number=sim_number,active='false' where imei='".$imei."'";
						$r = mysqli_query($ms,$qu);

						$qu="UPDATE staff_service SET service_close='".$service_close."' WHERE service_id='".$service_ID."'";
						$r = mysqli_query($ms,$qu);

						$qu="DELETE FROM gs_user_objects where imei='".$imei."'";
						$r = mysqli_query($ms,$qu);
					}
				}
			}
			if(count($report_list)>0){
				for($j=0;$j<count($report_list);$j++)
				{
					$report_id=$report_list[$j];
					$q = "INSERT INTO `staff_service_sub_report` (user_id,service_id,work_id,create_by) VALUES (
					 '".$user_id."','".$service_ID."','".$report_id."','".$_SESSION["user_id"]."')";
					$r = mysqli_query($ms,$q);
				}
			}						
			if(count($accessories_list)>0){
				if($accessories_list!=''){
					$acline='no';$tsli='no';$rfidli='no';$pbli='no';$buzzli='no';$ignli='no';$cctvli='no';
					for($k=0;$k<count($accessories_list);$k++)
					{
						$acc_list=$accessories_list[$k];					
						if($acc_list=='A/C Line'){
							$acline='yes';
						}else if($acc_list=='Temp Sensor'){
							$tsli='yes';
						}else if($acc_list=='RFID Reader'){
							$rfidli='yes';
						}else if($acc_list=='Panic Button'){
							$pbli='yes';
						}else if($acc_list=='Buzzer'){
							$buzzli='yes';
						}else if($acc_list=='Ignition Line Relay'){
							$ignli='yes';
						}else if($acc_list=='CCTV'){
							$cctvli='yes';
						}				
					}
					$qs="SELECT * FROM gs_object_accessories where imei='".$imei."'";
					$rs=mysqli_query($ms,$qs);
					if(mysqli_num_rows($rs)==0){
						$q = "INSERT INTO `gs_object_accessories` (imei,ac_line,temp_sensor,rfid,panic_button,buzzer,ignition_line_relay,cctv) VALUES (
						 '".$imei."','".$acline."','".$tsli."','".$rfidli."','".$pbli."','".$buzzli."','".$ignli."','".$cctvli."')";
					}else{
						$q="UPDATE gs_object_accessories SET ac_line='".$acline."',temp_sensor='".$tsli."',rfid='".$rfidli."',panic_button='".$pbli."',buzzer='".$buzzli."',ignition_line_relay='".$ignli."',cctv='".$cctvli."' WHERE imei='".$imei."'";
					}
					$r = mysqli_query($ms,$q);
				}
			}
			updateobjectSettings($_POST,$oldobjectname,$oldvehicletype,$oldtanksize,$oldfuel1,$oldfuel2,$oldsimid);
		}
		else
		{
			$service_id=$_POST["service_id"];
			$q = "UPDATE `staff_service`  SET  `client_id`='".$client_id."',`staff_id`='".$staff_id."',`site_location`='".$site_location."',
			   `schedule_date`='".$schedule_date."',`intime`='".$intime."',`outtime`='".$outtime."',
			   `imei`='".$imei."', `works`='".$works."',
			   `warrenty`='".$warrenty."', `office_note`='".$office_note."',modify_by='".$_SESSION["user_id"]."'  WHERE user_id='".$user_id."' and `service_id`='".$service_id."' ";
			$r = mysqli_query($ms,$q);

			$service_ID = $service_id;
			$q = "delete from `staff_service_sub` where service_id='".$service_ID."'";
				
			$r = mysqli_query($ms,$q);
			for($issub=0;$issub<count($work_type);$issub++)
			{
				$work_id=$work_type[$issub];
				$q = "INSERT INTO `staff_service_sub` (user_id,service_id,work_id,create_by) VALUES (
				 '".$user_id."','".$service_ID."','".$work_id."','".$_SESSION["user_id"]."')";
				$r = mysqli_query($ms,$q);
			}

			for($j=0;$j<count($report_list);$j++)
			{
				$report_id=$report_list[$j];
				$q = "INSERT INTO `staff_service_sub_report` (user_id,service_id,work_id,create_by) VALUES (
				 '".$user_id."','".$service_ID."','".$report_id."','".$_SESSION["user_id"]."')";
				$r = mysqli_query($ms,$q);
			}

			for($k=0;$k<count($accessories_list);$k++)
			{
				$acc_list=$accessories_list[$k];
				$acline='no';$tsli='no';$rfidli='no';$pbli='no';$buzzli='no';$ignli='no';$cctvli='no';
				if($acc_lis=='A/C Line'){
					$acline='yes';
				}else if($acc_lis=='Temp Sensor'){
					$tsli='yes';
				}else if($acc_lis=='RFID Reader'){
					$rfidli='yes';
				}else if($acc_lis=='Panic Button'){
					$pbli='yes';
				}else if($acc_lis=='Buzzer'){
					$buzzli='yes';
				}else if($acc_lis=='Ignition Line Relay'){
					$ignli='yes';
				}else if($acc_lis=='CCTV'){
					$cctvli='yes';
				}
				$qs="SELECT * FROM gs_object_accessories where imei='".$imei."'";
				$rs=mysqli_query($ms,$qs);
				if(mysqli_num_rows($rs)==0){
					$q = "INSERT INTO `gs_object_accessories` (imei,ac_line,temp_sensor,rfid,panic_button,buzzer,ignition_line_relay,cctv) VALUES (
					 '".$imei."','".$acline."','".$tsli."','".$rfidli."','".$pbli."','".$buzzli."','".$ignli."','".$cctvli."')";
				}else{
					$q="UPDATE gs_object_accessories SET ac_line='".$acline."',temp_sensor='".$tsli."',rfid='".$rfidli."',panic_button='".$pbli."',buzzer='".$buzzli."',ignition_line_relay='".$ignli."',cctv='".$cctvli."' WHERE imei='".$imei."'";
				}
				$r = mysqli_query($ms,$q);
			}
			
		}
		echo 'OK';
		die;
	}
	
	
	if(@$_POST['cmd'] == 'delete_staff')
	{
		$service_id = $_POST["service_id"];
		$q = "delete from staff_service  WHERE	service_id='".$service_id."' ";
		$r = mysqli_query($ms,$q);
	
		echo 'OK';
		die;
	}
	
	if(@$_GET['cmd'] == 'select_staff')
	{
		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
		if(!$sidx) $sidx =1;
		
		$from = @$_GET['from'];
		$to = @$_GET['to'];
		
		// get records number
		$total_pages = 1;
		$service_id = 0;
		if($from!='' && $to!='')
		{
			// $q = "select sd.staff_name staff,gu.username client,ss.* from  staff_service ss join
 		// 	 gs_users gu  on ss.client_id=gu.id  join
 		// 	 staff_data sd on  ss.staff_id=sd.staff_id where 
 		// 	 ss.user_id='".$user_id."' and (schedule_date BETWEEN '".$from."' and '".$to." 23:00:00') order by schedule_date desc";
 			 $q = "select sd.staff_name staff,gu.username client,ss.* from  staff_service ss join
 			 gs_users gu  on ss.client_id=gu.id  join
 			 staff_data sd on  ss.staff_id=sd.staff_id where (schedule_date BETWEEN '".$from."' and '".$to." 23:00:00') order by schedule_date desc";
		}
		else
		{
			// $q = "select sd.staff_name staff,gu.username client,ss.* from  staff_service ss join
 		// 	 gs_users gu  on ss.client_id=gu.id  join
 		// 	 staff_data sd on  ss.staff_id=sd.staff_id where 
 		// 	 ss.user_id='".$user_id."' order by schedule_date desc limit 50";			
 			 $q = "select sd.staff_name staff,gu.username client,ss.* from  staff_service ss join
 			 gs_users gu  on ss.client_id=gu.id  join
 			 staff_data sd on  ss.staff_id=sd.staff_id order by schedule_date desc limit 50";			
		}
		
		$result = mysqli_query($ms,$q);
		$count = mysqli_num_rows($result);
		$responce = new stdClass();
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		if ($result!=false)
		{
			$i=0;
			while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$rowWT['work_type']="";
				$rowWT['accessories']="";
				$qs = "select group_concat(work_id) work_id from staff_service_sub where service_id='".$row['service_id']."'";
				$r_service = mysqli_query($ms,$qs);
				if($row_service=mysqli_fetch_assoc($r_service))
				{
					$rowWT['work_type']=$row_service["work_id"];
				}
				$row['sim_id']=getsimNumber($row['sim_id']).'/'.getsimProvicer($row['sim_id']);
				$row['objectname']=getObjectName($row['imei']);
				// $qa = "select * from gs_object_accessories where service_id='".$row['imei']."'";
				// $r_acc = mysqli_query($ms,$qa);
				// if($row_acc=mysqli_fetch_assoc($r_acc))
				// {
				// 	$rowWT['accessories']=$row_acc;
				// }
				
				// $modify = '<a href="#" onclick="staffedit(\''.$row['service_id'].'\');" title="Edit"><img src="theme/images/edit.svg"></a> <a href="#" onclick="staffdelete(\''.$row['service_id'].'\');" title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>';	
				$modify ='<a href="#" onclick="staffserviceDetails(\''.$row['service_id'].'\');" title="Edit"><img src="theme/images/action.svg"></a>';			
	
				$responce->rows[$i]['id'] = $i;
				$responce->rows[$i]['cell']=array($row['service_id'],$row['client'], $row['staff'],$row['company'],$row['site_location'], $row['schedule_date'],$row['sim_id'],$row['objectname'],$row['imei'], $row['works'],$rowWT['work_type'], $row['warrenty'],$row['reason'],$modify,$row['client_id'], $row['staff_id']);
				$i++;
	
			}
		}
	
		header('Content-type: application/json');
		echo json_encode($responce);
		die;
	}

	if(@$_GET['cmd']=='staff_client_service_list'){
		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
		if(!$sidx) $sidx =1;
		
		// get records number
		$total_pages = 1;
		$service_id = 0;
		// if(isset($_POST['client'])==''){
		$q="SELECT imei,object_name,works,service_id FROM staff_service WHERE client_id!=0 ";
		// }
		if(@$_GET['client']!='Select'){
			$q.=" and client_id='".$_GET['client']."'";
		}
		if(@$_GET['imei']!='Select'){
			$q.=" and imei='".$_GET['imei']."'";
		}
		if(@$_GET['staffid']!='Select'){
			$q.=" and staff_id='".$_GET['staffid']."'";
		}
		$q.=" ORDER BY service_id DESC LIMIT 0,25";
		$result = mysqli_query($ms,$q);
		$count = mysqli_num_rows($result);
		$responce = new stdClass();
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		if ($result!=false)
		{
			$i=0;
			while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$modify ='<a href="#" onclick="staffserviceDetails(\''.$row['service_id'].'\');" title="Edit"><img src="theme/images/action.svg"></a>';			
	
				$responce->rows[$i]['id'] = $i;
				$responce->rows[$i]['cell']=array($row['object_name'],$row['works'],$modify);
				$i++;
			}
		}
		header('Content-type: application/json');
		echo json_encode($responce);
		die;
	}
	
	if(@$_POST['cmd'] == 'save_emp')
	{
		$staff_id = $_POST["staff_id"];		
		$staff_name = $_POST["staff_name"];
		$gender = $_POST["gender"];
		$dob = $_POST["dob"];
		$qualification = $_POST["qualification"];		
		$jod = $_POST["jod"];
		$experiance = $_POST["experiance"];
		$mobile = $_POST["mobile"];
		$address = $_POST["address"];
	    $card_id = $_POST["card_id"];
	    $desi_id = $_POST["desi_id"];
	    $note = $_POST["note"];
	
		if($staff_id==""){
			$staff_id=0;
		}
	
		$q = "select * from staff_data WHERE`staff_id`='".$staff_id."'";
		$r = mysqli_query($ms,$q);
		if ($staff_id == '0' || (!$rows=mysqli_fetch_assoc($r)))
		{
			$q = "INSERT INTO `staff_data` (user_id,staff_name,gender,qualification,dob,
				 jod,experiance,mobile,address,card_id,desi_id,note) VALUES ('".$user_id."',
				'".$staff_name."','".$gender."','".$qualification."','".$dob."','".$jod."',
				 '".$experiance."','".$mobile."','".$address."','".$card_id."','".$desi_id."','".$note."')";

			$r = mysqli_query($ms,$q);
			$staff_id = mysqli_insert_id($ms);
		
		}
		else
		{
			
			$q = "UPDATE `staff_data`  SET  `staff_name`='".$staff_name."',`gender`='".$gender."',`qualification`='".$qualification."',
			   `dob`='".$dob."',`jod`='".$jod."',`experiance`='".$experiance."',
			   `mobile`='".$mobile."', `address`='".$address."',`card_id`='".$card_id."',`desi_id`='".$desi_id."',`note`='".$note."'  WHERE `staff_id`='".$staff_id."' ";
			$r = mysqli_query($ms,$q);

		}
	
		echo 'OK';
		die;
	}
	
	
	if(@$_POST['cmd'] == 'delete_staff_emp')
	{
		$staff_id = $_POST["staff_id"];
		$q = "delete from staff_data  WHERE	staff_id='".$staff_id."' ";
		$r = mysqli_query($ms,$q);
	
		echo 'OK';
		die;
	}

	  if(@$_GET['cmd'] == 'select_staff_emp')
	{
		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
		if(!$sidx) $sidx =1;
		
		// get records number
		$total_pages = 1;
		$staff_id=0;
		
	
		
		$q = "SELECT sd.*,sdes.desi_name desi_name FROM staff_data sd join staff_desi sdes where sd.desi_id= sdes.desi_id";
		
		$result = mysqli_query($ms,$q);
		$count = mysqli_num_rows($result);
		$responce = new stdClass();
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		
		if ($result!=false)
		{
			$i=0;
			while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				
				
				$modify = '<a href="#" onclick="staffempedit(\''.$row['staff_id'].'\');" title="Edit"><img src="theme/images/edit.svg"></a> <a href="#" onclick="staffempdelete(\''.$row['staff_id'].'\');" title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>';
					
	
				$responce->rows[$i]['id'] = $i;
				$responce->rows[$i]['cell']=array($row['staff_id'],$row['staff_name'], $row['gender'], $row['dob'],$row['jod'],$row['qualification'], $row['experiance'],$row['mobile'], $row['address'],$row['card_id'],$row['desi_name'],$row['note'],$modify,$row['desi_id']);
				$i++;
	
			}
		}
	
		header('Content-type: application/json');
		echo json_encode($responce);
		die;
	}
	if(@$_GET['cmd'] == 'select_designation')
	{
		$q = "SELECT * FROM staff_desi  ORDER BY desi_id ASC";
		$ret=mysqli_query($ms,$q);
		$rtnary=array();
		while($row = mysqli_fetch_assoc($ret))
		{
			$rtnary[]=$row;
		}
		
		$result["type"]="S";
		$result["msg"]="";
		$result["mydata"]=$rtnary;
		echo json_encode($result);
		
		
		die;
	}
	
if(@$_POST['cmd']=='load_detail_object_list'){
	$result=array();
	$result['accessories']=array();
	$q="SELECT *,b.mob_number,b.sim_provider FROM gs_objects a LEFT JOIN gs_simcard_details b ON a.sim_id=b.id WHERE a.imei='".$_POST['object']."'";
	$r=mysqli_query($ms,$q);
	if(mysqli_num_rows($r)>0){
		$row=mysqli_fetch_assoc($r);
		if(strtotime(gmdate("Y-m-d")) < strtotime($row['installdate'])){
			$row['warrenty_status']='true';
		}else{
			$row['warrenty_status']='false';
		}
		$result=$row;		
		$qi="SELECT * from gs_object_accessories where imei='".$row['imei']."'";
		$ri=mysqli_query($ms,$qi);
		if(mysqli_num_rows($ri)>0){
			while($rowi=mysqli_fetch_assoc($ri)){
				$result['accessories']=$rowi;
			}
		}
	}else{
		$result='NO DATA';
	}
	header('Content-type: application/json');
	echo json_encode($result);
	die;
}
if(@$_POST['cmd']=='load_sim_details'){

	$result=array();
	$q="SELECT * FROM gs_simcard_details WHERE (id) NOT IN (SELECT sim_id FROM gs_objects WHERE sim_id!=0) AND STATUS='A'";
	// $myfile = fopen("vvv.txt", "a");
	$r=mysqli_query($ms,$q);
	while($row=mysqli_fetch_assoc($r)){
		$result[]=array("id"=>$row['id'],"number"=>$row['mob_number'].' /'.$row['sim_provider']);
	}
	header('Content-type: application/json');
	echo json_encode($result);
	die;
}

if(@$_POST['cmd']=='view_service_entry_details'){

	$result=array();
	$q="SELECT a.service_id,a.company,a.site_location,a.schedule_date,a.imei,a.object_name,a.vehicle_type,a.fuel_tank_size,a.fuel1,a.fuel2,a.works,a.warrenty,a.status,a.service_close,a.reason,b.username,c.staff_name,d.mob_number,d.sim_provider,a.check_object_name,a.old_object_name,a.check_vehical_type,a.old_vehicle_type,a.check_tank_size,a.old_fuel_tank_size,a.check_fuel1,a.old_fuel1,a.check_fuel2,a.old_fuel2,a.check_new_sim,a.old_sim_id,a.sim_id,a.remark FROM staff_service a LEFT JOIN gs_users b ON a.client_id=b.id LEFT JOIN staff_data c ON a.staff_id=c.staff_id LEFT JOIN gs_objects e ON a.imei=e.imei LEFT JOIN gs_simcard_details d ON e.sim_id=d.id WHERE a.service_id='".$_POST['service_id']."'";
	$r=mysqli_query($ms,$q);
	while($row=mysqli_fetch_assoc($r)){
		$result=$row;
		$result['worktype']='';
		$result['workreport']='';
		$result['accessories']='';
		$result['updatechanges']='';
		$qw="SELECT b.work FROM staff_service_sub a LEFT JOIN staff_work b ON a.work_id=b.work_id WHERE a.service_id='".$row['service_id']."'";
		$rw=mysqli_query($ms,$qw);
		if(mysqli_num_rows($rw)>0){
			while($roww=mysqli_fetch_assoc($rw)){
				$result['worktype'][]=$roww;
			}
		}
		$qr="SELECT b.work FROM staff_service_sub_report a LEFT JOIN staff_work_report b ON a.work_id=b.work_id WHERE a.service_id='".$row['service_id']."'";
		$rr=mysqli_query($ms,$qr);
		if(mysqli_num_rows($rr)>0){
			while($rowr=mysqli_fetch_assoc($rr)){
				$result['workreport'][]=$rowr;
			}
		}
		$qi="SELECT * from gs_object_accessories where imei='".$row['imei']."'";
		$ri=mysqli_query($ms,$qi);
		if(mysqli_num_rows($ri)>0){
			while($rowi=mysqli_fetch_assoc($ri)){
				$result['accessories']=$rowi;
			}
		}
		if($row['check_object_name']=='true'){
			$result['updatechanges'][]=array('name'=>'Object Name','value'=>$row['old_object_name'],'new'=>$row['object_name']);
		}
		if($row['check_vehical_type']=='true'){
			$result['updatechanges'][]=array('name'=>'Vehicle type','value'=>$row['old_vehicle_type'],'new'=>$row['vehicle_type']);
		}
		if($row['check_tank_size']=='true'){
			$result['updatechanges'][]=array('name'=>'Fuel Tank Size','value'=>$row['old_fuel_tank_size'],'new'=>$row['fuel_tank_size']);
		}
		if($row['check_fuel1']=='true'){
			$result['updatechanges'][]=array('name'=>'Fuel1','value'=>$row['old_fuel1'],'new'=>$row['fuel1']);
		}
		if($row['check_fuel2']=='true'){
			$result['updatechanges'][]=array('name'=>'Fuel2','value'=>$row['old_fuel2'],'new'=>$row['fuel2']);
		}
		if($row['check_new_sim']=='true'){
			$result['updatechanges'][]=array('name'=>'Sim Number','value'=>getsimNumber($row['old_sim_id']),'new'=>getsimNumber($row['sim_id']));
		}
	}
	header('Content-type: application/json');
	echo json_encode($result);
	die;
}

// if(@$_POST['cmd']=='remove_sim_from_device'){
// 	if ($_SESSION["cpanel_privileges"] == 'super_admin' || $_SESSION["cpanel_privileges"] == 'admin')
// 	{
// 		$q="UPDATE gs_objects SET sim_id='0',sim_number='',old_sim_number=CONCAT_WS(',',(SELECT mob_number FROM gs_simcard_details WHERE id='".$_POST['sid']."'),old_sim_number) WHERE imei='".$_POST['imei']."'";
// 		if(mysqli_query($ms,$q)){
// 			echo 'OK';
// 		}else{
// 			echo 'ERROR';
// 		}
// 	}else{
// 		echo 'NO_PERMISSION';
// 	}
// }

function updateobjectSettings($post,$oldobjectname,$oldvehicletype,$oldtanksize,$oldfuel1,$oldfuel2,$oldsimid){
	global $ms;
	// fuel settings
	if($post['chfuel1']=='true'){
		$q="SELECT * FROM gs_object_sensors WHERE `imei`='".$post['imei']."' and type='fuel'";
		$r=mysqli_query($ms,$q);
		if(mysqli_num_rows($r)>0){
			while($row=mysqli_fetch_assoc($r)){
				if($post['fuel1']=="No Sensor"){
					$qs="UPDATE gs_object_sensors SET `data_list`='false' where `imei`='".$post['imei']."' and type='fuel' ";
				}else{
					$qs="UPDATE gs_object_sensors SET `data_list`='true' where `imei`='".$post['imei']."' and type='fuel' ";				
				}
				$rs=mysqli_query($ms,$qs);
			}
		}else{
			$qs="INSERT  INTO gs_object_sensors (`imei`,`name`,`type`,`param`, `result_type`,`text_1`,`text_0`,`units`,`lv`,`hv`,`formula`,`calibration`,data_list,popup) SELECT * FROM  (select '".$post['imei']."' as imei,'Fuel 1' as name,'fuel' as type,'fuel1' as param,'value' as result_type,'' as text_1,'' as text_0,'Ltrs' as units,'0' lv,'0' hv,'X*1.0' formula,'[]' calibration,'true' data_list,'true' popup) AS tmp WHERE NOT EXISTS ( SELECT * FROM gs_object_sensors WHERE imei ='".$post['imei']."' and type='fuel' and param='fuel1') LIMIT 1;";			
			$rs=mysqli_query($ms,$qs);			
		}
		$qb="UPDATE gs_objects set fuel1='".$post['fuel1']."' where imei='".$post['imei']."'";
		mysqli_query($ms,$qb);
	}
	// object name settings
	if($post['chobjectname']=='true'){
		$q="UPDATE gs_objects SET name='".$post['objectname']."' where imei='".$post['imei']."' and name='".$oldobjectname."'";
		mysqli_query($ms,$q);
	}
	// vehicle type settings
	if($post['chvehicletype']=='true'){
		$q="UPDATE gs_objects SET vehicle_type='".$post['vehicletype']."' where imei='".$post['imei']."'";
		mysqli_query($ms,$q);
	}
	// fuel lank size
	if($post['chfuel_tanksize']=='true' && $post['fuel1']!='No Sensor' && $post['fuel_tanksize']!=''){
		$q="SELECT * FROM gs_object_sensors WHERE `imei`='".$post['imei']."' and type='fuel'";
		$r=mysqli_query($ms,$q);
		if(mysqli_num_rows($r)!=0){
			$qs="UPDATE gs_object_sensors SET `formula`='X*".$post['fuel_tanksize']."' where `imei`='".$post['imei']."' and type='fuel' ";
			mysqli_query($ms,$qs);
		}
	}
	// sim change settings
	if($post['simaction']=='true'){
		$q="UPDATE gs_objects SET sim_id='".$post['newsimid']."',sim_number='".getsimNumber($post['newsimid'])."' where imei='".$post['imei']."'";
		mysqli_query($ms,$q);
	}
}
?>