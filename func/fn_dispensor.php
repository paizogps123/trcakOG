<?
session_start();
	include ('../init.php');
	include ('fn_common.php');
	include ('../tools/sms.php');
	// include ('../server/s_service.php');
	checkUserSession();
	
	loadLanguage($_SESSION["language"], $_SESSION["units"]);	
	// check privileges
	if ($_SESSION["privileges"] == 'subuser')
	{
		$user_id = $_SESSION["manager_id"];
	}
	else
	{
		$user_id = $_SESSION["user_id"];
	}

if(@$_GET['cmd']=='booking_list'){
	$page = $_GET['page']; // get the requested page
	$limit = $_GET['rows']; // get how many rows we want to have into the grid
	$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
	$sord = $_GET['sord']; // get the direction
	
	$user_id = $_SESSION["user_id"];
	
	if(!$sidx) $sidx =1;
	
	// get records number
	$q = "SELECT * FROM `booking_dispensor` WHERE `user_id`='".$user_id."'";
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
	
	$q = "SELECT * FROM `booking_dispensor` WHERE `user_id`='".$user_id."' ORDER BY $sidx $sord LIMIT $start, $limit";

	$r = mysqli_query($ms, $q);
	
	$response = new stdClass();
	$response->page = $page;
	$response->total = $total_pages;
	$response->records = $count;
	
	if (mysqli_num_rows($r)>0)
	{
		$i=0;
		while($row = mysqli_fetch_array($r)) {				
			$book_by = $row['book_by'];
			$createdate = $row['createdate'];
			$booking_status = $row['booking_status'];
			$customer_name = $row['customer_name'];
			$contact_no = $row['contact_no'];
			$fuel_qty = $row['fuel_qty'];
			$price_per_litter = $row['price_per_litter'];
			$allocated_driver_id = $row['allocated_driver_id'];
			$allocated_driver_phone = $row['allocated_driver_phone'];
			$updatetime = $row['updatetime'];
			$booking_address = $row['booking_address'];
			$driver='<lable title='.$allocated_driver_phone.'>'.$allocated_driver_id.'</lable>';
			
			// set modify buttons
			$modify = '';
			// $modify = '<a href="#" onclick="dispensor_booking_edit(\''.$row['id'].'\');" title="'.$la['EDIT'].'"><img src="theme/images/edit.svg" />';
			// $modify .= '</a><a href="#" onclick="dispensor_booking_update(\''.$row['id'].'\');"  title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>';
			// $modify .= '</a><a href="#" onclick="dispensor_booking_allocated(\''.$row['id'].'\');"  title="'.$la['ALLOCATED'].'"><i class="demo-icon icon-taxi"></i></a>';

			if($row["booking_status"]=="Waiting"  )
			{
				$modify .= '</a><a href="#" onclick="dispensor_booking_delete(\''.$row['id'].'\');"  title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>';
				// $modify .= '<a href="#" onclick="cancel_booking(\''.$row['booking_id'].'\');" title="'.$la['CANCEL'].'"><img src="theme/images/remove-red.svg" /></a>&nbsp;';
				//$modify .= '<a href="#" onclick="bookingedit(\''.$row['booking_id'].'\');" title="'.$la['EDIT'].'"><img src="theme/images/edit.svg" /></a>&nbsp;';
				
				$modify .= '<a href="#" onclick="dispensor_booking_edit(\''.$row['id'].'\');" title="'.$la['EDIT'].'"><img src="theme/images/edit.svg" />';
				$modify .= '</a><a href="#" onclick="dispensor_booking_allocated(\''.$row['id'].'\');"  title="'.$la['ALLOCATED'].'"><i class="demo-icon icon-taxi"></i></a>';
				
				//$modify .= '<a href="#" onclick="bookingduplicate(\''.$row['booking_id'].'\');" title="'.$la['DUPLICATE'].'"><img src="theme/images/export.svg" /></a>&nbsp;';
			}
			else if($row["booking_status"]=="Deleted" || $row["booking_status"]=="Canceled" )
			{
				if ($_SESSION["privileges"] != 'subuser' && $_SESSION["privileges"] != 'viewer')
				{
					$modify .= '<a href="#" onclick="dispensor_booking_change_wait(\''.$row['id'].'\');" title="'.$la['DELETE'].'"><img src="theme/images/tick-green.svg" /></a>';
				}
			} 
			else if($row["booking_status"]=="Finished")
			{
				$modify = '';
			}
			else 
			{
				$modify .= '</a><a href="#" onclick="dispensor_booking_delete(\''.$row['id'].'\');"  title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>';
				// $modify .= '<a href="#" onclick="cancel_booking(\''.$row['booking_id'].'\');" title="'.$la['CANCEL'].'"><img src="theme/images/remove-red.svg" /></a>&nbsp;';
				$modify .= '<a href="#" onclick="dispensor_booking_edit(\''.$row['id'].'\');" title="'.$la['EDIT'].'"><img src="theme/images/edit.svg" />';
			}

			if($row["booking_status"]=="Waiting")
			$stats="<label style='background: red;color: white;border-radius:4px;padding: 2px;text-align: center;'>".$row["booking_status"]."</label>";
			else if($row["booking_status"]=="Allocated")
			$stats="<label style='background: #622bca;color: white;border-radius:4px;padding: 2px;text-align: center;'>".$row["booking_status"]."</label>";
			else if($row["booking_status"]=="Accepted")
			$stats="<label style='background: #13759c;color: white;border-radius:4px;padding: 2px;text-align: center;'>".$row["booking_status"]."</label>";
			else if($row["booking_status"]=="Reached")
			$stats="<label style='background: #82a203;color: white;border-radius:4px;padding: 2px;text-align: center;'>".$row["booking_status"]."</label>";
			else if($row["booking_status"]=="Picked Up")
			$stats="<label style='background: #ba2bca;color: white;border-radius:4px;padding: 2px;text-align: center;'>".$row["booking_status"]."</label>";
			else if($row["booking_status"]=="Finished")
			$stats="<label style='background: green;color: white;border-radius:4px;padding: 2px;text-align: center;'>".$row["booking_status"]."</label>";
			else if($row["booking_status"]=="Canceled")
			$stats="<label style='background: black;color: white;border-radius:4px;padding: 2px;text-align: center;'>".$row["booking_status"]."</label>";
			else if($row["booking_status"]=="Deleted")
			$stats="<label style='background: #737373;color: white;border-radius:4px;padding: 2px;text-align: center;'>".$row["booking_status"]."</label>";
			// set row
			$response->rows[$i]['id']=$row['id'];
			$response->rows[$i]['cell']=array($modify,$book_by,$booking_address,$createdate,$stats,$customer_name,$contact_no,$fuel_qty,$price_per_litter,$driver,$updatetime);
			$i++;
		}	
	}
			
	header('Content-type: application/json');
	echo json_encode($response);
	die;
}
else if(@$_GET['cmd']=='avilable_driver_list'){
		$responce = new stdClass();
		if(@$_GET['bookingid']=="")
		{
			
		$responce->page = 1;
		$responce->total = 1;
		$responce->records = 0;
		
		header('Content-type: application/json');
		echo json_encode($responce);
		die;
		}

		$bookingid=$_GET['bookingid'];
		 
		$qve="select * from booking_dispensor where id='".$bookingid."'";
		

		$rve=mysqli_query($ms,$qve);
		if($rowveh=mysqli_fetch_assoc($rve))
		{

			
			$user_lat=$rowveh["lat"];
			$user_lng=$rowveh["lng"];

			 $_GET['page']="1";
			 $_GET['rows']="1";
			 $_GET['sord']="1";
			 $_GET['sidx']="1";
			 
			$page = $_GET['page']; // get the requested page
			$limit = $_GET['rows']; // get how many rows we want to have into the grid
			$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
			$sord = $_GET['sord']; // get the direction

			if(!$sidx) $sidx =1;

		// 	// get records number

			$total_pages = 1;
		 
			if ($_SESSION["privileges"] == 'subuser')
			{
				$user_id = $_SESSION["manager_id"];
			}
			else
			{
				$user_id = $_SESSION["user_id"];
			}
	
			$q = "select id,username,privileges,subuser_phone,subuser_status,imei,vehicle from gs_users 
			where manager_id='".$user_id."' and driver='true' and id not in
			(select allocated_driver_id from booking_dispensor where booking_status in 
			('Waiting','Allocated','Accepted','Reached','Picked Up')  and 
			user_id='".$user_id."'  and allocated_driver_id is not null ) 
			order by subuser_status";


			//user_id='".$_SESSION["user_id"]."'  and 
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
					//$privilegesary=json_decode($row["privileges"],true);
					//$imei=$privilegesary["imei"];
					$imei=$row['imei'];
	
					$vehicleno="";
					$qv="select name,lat,lng,loc_valid from gs_objects where imei='".$imei."' ";
					$rv = mysqli_query($ms,$qv);

					if ($rowv = mysqli_fetch_array($rv,MYSQLI_ASSOC))
					{

	
						$Dlat=$rowv['lat'];
						$Dlng=$rowv['lng'];
						$distance=round(getLengthBetweenCoordinates($Dlat,$Dlng,$user_lat,$user_lng),2);
						$location='<a href="https://maps.google.com/maps?q='.$rowv["lat"].','.$rowv['lng'].'&amp;t=m" target="_blank" ></a>';
						//$location=reportsGetPossition($route[$i]["lat2"], $route[$i]["lng2"],true, true, true);
						//$location=reportsGetPossition($rowv["lat"],$rowv["lng"],true, true,false);
						$track='<a style="cursor: pointer;" onclick="utilsFollowObject('.$imei.', false)" tag="follow_new">'.$rowv["name"].'</a>';
						//$modify = '<a href="#" onclick="allocateto_booking('.$bookingid.'~'.$row['id'].'~'.$row['subuser_phone'].'~'.$imei.'~'.$rowv['name'].');" title="'.$la['RFIDREPORT'].'"><img src="theme/images/img/report.png" /></a>';
						$modify = '<a href="#" onclick="driver_allocateto_booking('.$i.');" title="'.$la['ALLOCATE_THIS_VEHICLE'].'"><img src="img/siren.png" style="width:24px;height:26px;" /></a>';
						
						//status will be Active Waiting On Trip Deactive
						$username="";
						if($row['subuser_status']!="Deactive")
						$username='<a style="background:green;color:white;padding:3px;">'.$row['username'].'</a>';
						else
						$username='<a style="background:#696969;color:white;padding:3px;">'.$row['username'].'</a>';
						

						$responce->rows[$i]['id'] = $i;
						$responce->rows[$i]['cell']=array("uid"=>$row['id'],"vehicle"=>$track,"driver"=>$username,"imei"=>$imei,"phone"=>$row['subuser_phone'],"status"=>$row['subuser_status'],"distance"=>$distance,"lat"=>$rowv["lat"],"lng"=>$rowv["lng"],"location"=>$location,"loc_valid"=>$rowv["loc_valid"],"modify"=>$modify,"bid"=>$bookingid,"vname"=>$rowv["name"]);
						$i++;
					}
							
					}
				}
			}


					
			header('Content-type: application/json');
			echo json_encode($responce);
			die;
}

else if(@$_POST['cmd']=='dis_live_booking'){
	$lat=$_POST['latv'];
	$lng=$_POST['lngv'];
	if($lat=="" && $lng=="")
	{
		$result = '';
		$search = htmlentities(urlencode($emergency_address));
		$url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$search.'&oe=utf-8&key=AIzaSyDbhTAegponm1Lo5tuVaIF750nL1QCLwnc';
		$data = @file_get_contents($url);
		$jsondata = json_decode($data,true);
	
	 if(is_array($jsondata) && $jsondata['status']=="OK")
	{
		for ($i=0; $i<count($jsondata['results']); $i++)
		{
			$result = $jsondata['results'][$i]['formatted_address'];
			$lat = $jsondata['results'][$i]['geometry']['location']['lat'];
			$lng = $jsondata['results'][$i]['geometry']['location']['lng'];	
		}
	}
	
	}
	$q="INSERT INTO booking_dispensor (`user_id`,`control_room_id`,`book_by`,`self_others`,`booking_address`,`landmark`,`customer_name`,`contact_no`,`fuel_qty`,`price_per_litter`,`booking_type`,`note1`,`booking_time`,`booking_status`,`lat`,`lng`) VALUES ('".$user_id."','".$_SESSION["user_id"]."','Web','Others','".$_POST['loc']."','".$_POST['lndmark']."','".$_POST['name']."','".$_POST['conno']."','".$_POST['qty']."','".$_POST['price']."','".$_POST['type']."','".$_POST['note']."','".gmdate("Y-m-d H:i:s")."','Waiting','".$lat."','".$lng."')";
	mysqli_query($ms,$q);
	echo 'ok';
}

else if(@$_POST['cmd']=='dis_live_booking_update'){
	$lat=$_POST['latv'];
	$lng=$_POST['lngv'];
	if($lat=="" && $lng=="")
	{
		$result = '';
		$search = htmlentities(urlencode($emergency_address));
		$url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$search.'&oe=utf-8&key=AIzaSyDbhTAegponm1Lo5tuVaIF750nL1QCLwnc';
		$data = @file_get_contents($url);
		$jsondata = json_decode($data,true);
	
	 if(is_array($jsondata) && $jsondata['status']=="OK")
	{
		for ($i=0; $i<count($jsondata['results']); $i++)
		{
			$result = $jsondata['results'][$i]['formatted_address'];
			$lat = $jsondata['results'][$i]['geometry']['location']['lat'];
			$lng = $jsondata['results'][$i]['geometry']['location']['lng'];	
		}
	}
	
	}
	$q="SELECT * FROM booking_dispensor where id='".$_POST['book_id']."'";
	$r=mysqli_query($ms,$q);
	if(mysqli_num_rows($r)>0){

		$q="UPDATE booking_dispensor SET  `booking_address`='".$_POST['loc']."',`landmark`='".$_POST['lndmark']."',`customer_name`='".$_POST['name']."',`contact_no`='".$_POST['conno']."',`fuel_qty`='".$_POST['qty']."',`price_per_litter`='".$_POST['price']."',`booking_type`='".$_POST['type']."',`note1`='".$_POST['note']."',`lat`='".$lat."',`lng`='".$lng."' WHERE id='".$_POST['book_id']."'";
		mysqli_query($ms,$q);
	}
	echo 'ok';
}

else if(@$_POST['cmd']=='get_dis_live_booking_edit'){
	$result=array();
	$q="SELECT * FROM booking_dispensor where id='".$_POST['id']."'";
	$r=mysqli_query($ms,$q);
	if(mysqli_num_rows($r)>0){
		$row=mysqli_fetch_assoc($r);
		$result=$row;
	}
	header('Content-type: application/json');
	echo json_encode($result);
	die;
}
else if(@$_POST['cmd']=='allocate_to_driver'){
	$bookingid = $_POST["bookingid"];
		$driver_id=$_POST["driver_id"];
		$driver_phone=$_POST["driver_phone"];
		$driver_imei=$_POST["driver_imei"];
		$driver_vehicle=$_POST["driver_vehicle"];

		$qdr="select id,sms_gateway_url from gs_users where ( subuser_status='Active' or subuser_status='Waiting') and id='".$driver_id."'";

		$rdr=mysqli_query($ms,$qdr);
		if($rordr=mysqli_fetch_assoc($rdr))
		{
	 	$qt = "UPDATE booking_dispensor set	allocated_driver_id='".$driver_id."',control_room_id='".$_SESSION["user_id"]."',allocated_time='".gmdate("Y-m-d H:i:s")."',		booking_status='Allocated',	allocated_driver_phone='".$driver_phone."',	allocated_imei='".$driver_imei."',	allocated_vehicleno='".$driver_vehicle."',updatetime='".gmdate("Y-m-d H:i:s")."' where id='$bookingid' ";
	 	$rt = mysqli_query($ms,$qt);
	 	if($rt)
	 	{
	 	// 	$q = "insert into  ah_booking_allocate(booking_id,control_room_id,allocate_date,allocated_driver_id) values
			// ('".$bookingid."','".$_SESSION["user_id"]."','".gmdate("Y-m-d H:i:s")."','".$driver_id."')";	
	 	// 	$r = mysqli_query($ms,$q);

	 		$qtsms = "select * from booking_dispensor where id='$bookingid' ";
	 		$rdrsms = mysqli_query($ms,$qtsms);
	 		if($rordrsms=mysqli_fetch_assoc($rdrsms))
	 		{
	 			$message="Booking Info: Name: ".$rordrsms["customer_name"]."Phone : ".$rordrsms["contact_no"]." Addr: ".$rordrsms["booking_address"];
	 			
	 			$message.="Driver: ".$rordrsms["allocated_driver_id"];
	 			$message.="Driver Phone: ".$rordrsms["allocated_driver_phone"];
	 			$message.="QTY: ".$rordrsms["fuel_qty"];
	 			$message.="Price: ".$rordrsms["price_per_litter"];
	 			$result = sendSMSHTTP($rordr['sms_gateway_url'], '', $driver_phone, $message);
	 		}
	 		echo 'OK';
	 	}
	 	else
	 	{
	 		echo 'Driver allocated failed';
	 	}
		}
		else 
		{
			echo 'Driver is not active';
		}

		die;
}
else if(@$_POST['cmd'] == 'delete_booking')
{
	$bookingid = $_POST["bookingid"];
	$reason=$_POST["reason"];
			
	$q = "update booking_dispensor set booking_status='Deleted',updatetime='".gmdate("Y-m-d H:i:s")."'  WHERE id='".$bookingid."'";	
	$r = mysqli_query($ms,$q);

	$q = "update gs_users set subuser_status='Waiting'  WHERE id=(select allocated_driver_id from ah_booking where booking_id='".$bookingid."')";
	$r = mysqli_query($ms,$q);
	
	// $q = "insert into  ah_booking_delete(booking_id,control_room_id,delete_date,reason) values
	// ('".$bookingid."','".$_SESSION["user_id"]."','".gmdate("Y-m-d H:i:s")."','".$reason."')";	
	// $r = mysqli_query($ms,$q);
	
	
	
	echo 'OK';
	die;
}

else if(@$_POST['cmd'] == 'cange_status_wait')
{
	$bookingid = $_POST["bookingid"];

	$q = "update booking_dispensor set booking_status='Waiting',updatetime='".gmdate("Y-m-d H:i:s")."'  WHERE id='".$bookingid."'";
	$r = mysqli_query($ms,$q);
   				
	echo 'OK';
	
	die;
}

?>