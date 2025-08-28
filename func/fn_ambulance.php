<?

	

	session_start();
	include ('../init.php');
	include ('fn_common.php');
    include ('fn_route.php');
    include ('../tools/sms.php');
    include ('../tools/gc_func.php');
    
	checkUserSession();
	
	loadLanguage($_SESSION["language"]);
	
	global  $la,$ms,$gsValues;
		
	date_default_timezone_set("Asia/Kolkata");
	// check privileges
	if ($_SESSION["privileges"] == 'subuser')
	{
		$user_id = $_SESSION["manager_id"];
	}
	else
	{
		$user_id = $_SESSION["user_id"];
	}
	
	/*
	if ($_SESSION["ambulance"] != true)
	{
		echo $la["CONTACT_ADMINISTRATOR"];
		die;
	}
	*/
	
	if(@$_POST['cmd'] == 'save_booking')
	{
		$emergency_address = $_POST["emergencyaddress"];
		$contact_no = $_POST["contactno"];
		$emergency_reason = $_POST["emergencyreason"];
		$people_count = $_POST["peoplecount"];
		$age = $_POST["age"];
		$conscious = $_POST["conscious"];
		$breathing = $_POST["breathing"];
		$gender = $_POST["gender"];
		$person_name = $_POST["personname"];
		$bookingid = $_POST["bookingid"];
		$note1=$_POST["note1"];
		$note2=$_POST["note2"];
		$bookingid=0;
		$bookingid=intval($_POST["bookingid"]);
		$lat=$_POST["lat"];
		$lng=$_POST["lng"];
		$createdate=gmdate("Y-m-d H:i:s");
		$updatetime=gmdate("Y-m-d H:i:s");
		$createdate=$updatetime;
		if($people_count=="")$people_count=0;
		if($age=="")$age=0;
		
		if($bookingid==0)
		{
			if($lat=="" && $lng=="")
			{
				$result = '';
				$search = htmlentities(urlencode($emergency_address));
			
//        	$url = $gsValues['URL_GC'][0].'?cmd=address&search='.$search;
//        	$result = @file_get_contents($url);
//        	if ($result != '[]')
//        	{
//        		$result = json_decode($result,true);
//        		$lat = $result[0]["lat"];
//        		$lng = $result[0]["lng"];
//        	}

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
			
			$q = "insert into ah_booking(control_room_id,app_user_id,user_id,book_by,self_others,
		emergency_address,contact_no,emergency_reason,people_count,age,conscious,breathing,gender,
		person_name,booking_status,feature_booking,
		create_date,lat,lng,note1,note2,updatetime)  values ('".$_SESSION["user_id"]."','0','".$user_id."','Web','Others',
		'".$emergency_address."','".$contact_no."','".$emergency_reason."','".$people_count."',
		'".$age."','".$conscious."','".$breathing."','".$gender."','".$person_name."','Waiting',
		'No','".$createdate."','".$lat."','".$lng."','".$note1."','".$note2."','".$updatetime."')";

			$r = mysqli_query($ms,$q);
			
			$q = "insert into  ah_booking_update(booking_id,control_room_id,update_date,update_data,before_data) values
				('".$bookingid."','".$_SESSION["user_id"]."','".gmdate("Y-m-d H:i:s")."','".json_encode($_POST)."','')";	
			$r = mysqli_query($ms,$q);
		
		}
		else
		{
			
		$bfr_data="";
		$q = "select * from ah_booking  where booking_id='".$bookingid."' ";
        $r = mysqli_query($ms,$q);  
          
        if ($row = mysqli_fetch_assoc($r))
        {
        	$bfr_data=json_encode($row);
        }
		
        if($lat=="" && $lng=="")
        {

        	$result = '';
        	$search = htmlentities(urlencode($emergency_address));
        	
//        	$url = $gsValues['URL_GC'][0].'?cmd=address&search='.$search;
//        	$result = @file_get_contents($url);
//        	if ($result != '[]')
//        	{
//        		$result = json_decode($result,true);
//        		$lat = $result[0]["lat"];
//        		$lng = $result[0]["lng"];
//        	}

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
			$q = "update ah_booking set emergency_address='".$emergency_address."',
		contact_no='".$contact_no."',
		emergency_reason='".$emergency_reason."',
		people_count='".$people_count."',
		age='".$age."',
		conscious='".$conscious."',
		breathing='".$breathing."',
		gender='".$gender."',
		gender='".$gender."',
		person_name='".$person_name."',
		lat='".$lat."',
		lng='".$lng."',
		updatetime='".$updatetime."',
		note1='".$note1."',note2='".$note2."' where booking_id='".$bookingid."'";
		
		$r = mysqli_query($ms,$q);

		$q = "insert into  ah_booking_update(booking_id,control_room_id,update_date,update_data,before_data) values
		('".$bookingid."','".$_SESSION["user_id"]."','".gmdate("Y-m-d H:i:s")."','".json_encode($_POST)."','".$bfr_data."')";	
		$r = mysqli_query($ms,$q);
        
		
		}

		echo 'OK';

		die;
	}
	else if(@$_POST['cmd'] == 'update_booking')
	{
		$updatetime=gmdate("Y-m-d H:i:s");
		$bookingid = $_POST["bookingid"];
		$emergency_address = $_POST["emergencyaddress"];
		$contact_no = $_POST["contactno"];
		$emergency_reason = $_POST["emergencyreason"];
		$people_count = $_POST["peoplecount"];
		$age = $_POST["age"];
		$conscious = $_POST["conscious"];
		$breathing = $_POST["breathing"];
		$gender = $_POST["gender"];
		$person_name = $_POST["personname"];
		$lat=$_POST["lat"];
		$lng=$_POST["lng"];
		
		$bfr_data="";
		$q = "select * from ah_booking  where booking_id='".$bookingid."' ";
        $r = mysqli_query($ms,$q);  
          
        if ($row = mysqli_fetch_assoc($r))
        {
        	$bfr_data=json_encode($row);
        }
		
		$q = "update ah_booking set emergency_address='".$emergency_address."',
		contact_no='".$contact_no."',
		emergency_reason='".$emergency_reason."',
		people_count='".$people_count."',
		age='".$age."',
		conscious='".$conscious."',
		breathing='".$breathing."',
		gender='".$gender."',
		person_name='".$person_name."'";
		
		$r = mysqli_query($ms,$q);

		$q = "insert into  ah_booking_update(booking_id,control_room_id,update_date,update_data,before_data) values
		('".$bookingid."','".$_SESSION["user_id"]."','".gmdate("Y-m-d H:i:s")."','".json_encode($_POST)."','".$bfr_data."')";	
		$r = mysqli_query($ms,$q);

		echo 'OK';
		die;
	}
	else if(@$_POST['cmd'] == 'duplicate')
	{
		$bookingid = $_POST["bookingid"];
		//$createdate=date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s") ."+ 5 hour + 30 minutes"));
		$createdate=gmdate("Y-m-d H:i:s");
		$updatetime=gmdate("Y-m-d H:i:s");
		$bfr_data="";
		$parent_id=0;
		$parent_id=$bookingid;
		$q = "select * from ah_booking  where booking_id='".$bookingid."' ";
        $r = mysqli_query($ms,$q);     
        if ($row = mysqli_fetch_assoc($r))
        {
        	$bfr_data=json_encode($row);
        	if(isset($row["parent_booking_id"]))
        	$parent_id=$row["parent_booking_id"];
        }
		
		$q = "insert into ah_booking(user_id,control_room_id,book_by,app_user_id,self_others,emergency_address,
			 contact_no,emergency_reason,people_count,age,conscious,breathing,gender,person_name,note1,note2,note3,
			 lat,lng,book_lat,book_lng,booking_status,create_date,parent_booking_id,updatetime) 
			 select user_id,control_room_id,book_by,app_user_id,self_others,emergency_address,
			 contact_no,emergency_reason,people_count,age,conscious,breathing,gender,person_name,note1,note2,note3,
			 lat,lng,book_lat,book_lng,'Waiting' booking_status,'".$createdate."' create_date,".$parent_id." parent_booking_id,'".$updatetime."' updatetime from ah_booking where
 			 booking_id=".$bookingid;
	

		$r = mysqli_query($ms,$q);

		$qv = "insert into  ah_booking_update(booking_id,control_room_id,update_date,update_data,before_data) values
				('".$bookingid."','".$_SESSION["user_id"]."','".gmdate("Y-m-d H:i:s")."','".json_encode($bfr_data)."','')";	
		$rv = mysqli_query($ms,$qv);

		echo 'OK';
		die;
	}
	
	else if(@$_POST['cmd'] == 'cancel_booking')
	{
		$bookingid = $_POST["bookingid"];
		$reason = $_POST["reason"];

		if ($_SESSION["privileges"] != 'subuser' && $_SESSION["privileges"] != 'viewer')
		{
			$q = "update ah_booking set booking_status='Canceled',updatetime='".gmdate("Y-m-d H:i:s")."'  WHERE	booking_id='".$bookingid."'";
			$r = mysqli_query($ms,$q);

			$qc = "insert into  ah_driver_cancel(control_room_id,booking_id,canceltime,reason,cancel_by)
        				values('".$_SESSION["user_id"]."','".$bookingid."','".gmdate("Y-m-d H:i:s")."','".$reason."','CC') ";
			$rc= mysqli_query($ms,$qc);
			
			$q = "update gs_users set subuser_status='Waiting'  WHERE id=(select allocated_driver_id from ah_booking where booking_id='".$bookingid."')";
			$r = mysqli_query($ms,$q);

			echo 'OK';
		}
		else
		{
			echo 'THIS_ACCOUNT_HAS_NO_PRIVILEGES_TO_DO_THAT';
		}
		die;
	}
	else if(@$_POST['cmd'] == 'cange_status_wait')
	{
		$bookingid = $_POST["bookingid"];

		$q = "update ah_booking set booking_status='Waiting',updatetime='".gmdate("Y-m-d H:i:s")."'  WHERE	booking_id='".$bookingid."'";
		$r = mysqli_query($ms,$q);
       				
		echo 'OK';
		
		die;
	}
	else if(@$_POST['cmd'] == 'delete_booking')
	{
		$bookingid = $_POST["bookingid"];
		$reason=$_POST["reason"];
				
		$q = "update ah_booking set booking_status='Deleted',updatetime='".gmdate("Y-m-d H:i:s")."'  WHERE	booking_id='".$bookingid."'";	
		$r = mysqli_query($ms,$q);

		$q = "update gs_users set subuser_status='Waiting'  WHERE id=(select allocated_driver_id from ah_booking where booking_id='".$bookingid."')";
		$r = mysqli_query($ms,$q);
		
		$q = "insert into  ah_booking_delete(booking_id,control_room_id,delete_date,reason) values
		('".$bookingid."','".$_SESSION["user_id"]."','".gmdate("Y-m-d H:i:s")."','".$reason."')";	
		$r = mysqli_query($ms,$q);
		
		
		
		echo 'OK';
		die;
	}
	
	else if(@$_POST['cmd'] == 'allocate_to_driver')
	{
		$bookingid = $_POST["bookingid"];
		$driver_id=$_POST["driver_id"];
		$driver_phone=$_POST["driver_phone"];
		$driver_imei=$_POST["driver_imei"];
		$driver_vehicle=$_POST["driver_vehicle"];

		$qdr="select id,sms_gateway_url from gs_users where ( subuser_status='Active' or subuser_status='Waiting') and id='".$driver_id."'";

		$rdr=mysqli_query($ms,$qdr);
		if($rordr=mysqli_fetch_assoc($rdr))
		{
	 	$qt = "update ah_booking set
		allocated_driver_id='".$driver_id."',
		control_room_id='".$_SESSION["user_id"]."',
		allocated_time='".gmdate("Y-m-d H:i:s")."',
		booking_status='Allocated',
		allocated_driver_phone='".$driver_phone."',
		allocated_imei='".$driver_imei."',
		allocated_vehicleno='".$driver_vehicle."'
		,updatetime='".gmdate("Y-m-d H:i:s")."'
 		where booking_id='$bookingid' ";
	 	$rt = mysqli_query($ms,$qt);
	 	if($rt)
	 	{
	 		$q = "insert into  ah_booking_allocate(booking_id,control_room_id,allocate_date,allocated_driver_id) values
			('".$bookingid."','".$_SESSION["user_id"]."','".gmdate("Y-m-d H:i:s")."','".$driver_id."')";	
	 		$r = mysqli_query($ms,$q);

	 		$qtsms = "select * from ah_booking where booking_id='$bookingid' ";
	 		$rdrsms = mysqli_query($ms,$qtsms);
	 		if($rordrsms=mysqli_fetch_assoc($rdrsms))
	 		{
	 			$message="Patient Info: Name: ".$rordrsms["person_name"]."Phone : ".$rordrsms["contact_no"]." Addr: ".$rordrsms["emergency_address"];
	 			if($rordrsms["emergency_reason"]!="" && $rordrsms["emergency_reason"]!="Select")
	 			$message.="Emergency: ".$rordrsms["emergency_reason"];

	 			$message.=" Addr: ".$rordrsms["emergency_address"];
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

	else if(@$_GET['cmd'] == 'select_booking')
	{
			
		$dtf="";$dtt="";
		
		$status=$_GET['status'];
		
		if($status=="Deleted" && ($_SESSION["privileges"] == 'subuser' || $_SESSION["privileges"] == 'viewer'))
		{
			$status=" and booking_status!='Deleted'";
		}
		else
		{
		if($status=="Select" || $status=="select")
		{
			$status=" and booking_status in('Waiting','Allocated','Accepted','Reached','Picked Up') and booking_status not in ('Finished','Canceled','Deleted')";
		}
		else 
		$status=" and booking_status='".$status."'";
		}
		
	
		$dtf = $_GET['from']." 00:00:00";
	
		
		$dtt = $_GET['to']." 23:59:59";
			
		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
		
		if(!$sidx) $sidx =1;
		
		// get records number
		//$q = "SELECT * FROM ah_booking WHERE user_id='".$user_id."' and booking_status!='Deleted' ".$status." and create_date BETWEEN '".$dtf."' AND '".$dtt."'";
		$q = "SELECT * FROM ah_booking WHERE user_id='".$user_id."' ".$status." ";

		$r = mysqli_query($ms, $q);
		$count = 0;
		if($r)
		$count = mysqli_num_rows($r);
		
		if( $count >0 )
		{
			$total_pages = ceil($count/$limit);
		}
		else
		{
			$total_pages = 1;
		}
		
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit; // do not put $limit*($page - 1)
		
		$responce = new stdClass();
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		
		if ($count > 0)
		{
			$q .= " ORDER BY $sidx $sord LIMIT $start, $limit";
			$r = mysqli_query($ms, $q);
			if (!$r){die;}
			
			$i=0;
			while($row = mysqli_fetch_assoc($r))
			{
				// set modify buttons
				$modify="";
				if($row["booking_status"]=="Waiting"  )
				{
					$modify .= '<a href="#" onclick="bookingdelete(\''.$row['booking_id'].'\');" title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a> &nbsp;';
					$modify .= '<a href="#" onclick="cancel_booking(\''.$row['booking_id'].'\');" title="'.$la['CANCEL'].'"><img src="theme/images/remove-red.svg" /></a>&nbsp;';
					//$modify .= '<a href="#" onclick="bookingedit(\''.$row['booking_id'].'\');" title="'.$la['EDIT'].'"><img src="theme/images/edit.svg" /></a>&nbsp;';
					
					$modify .= '<a href="#" onclick="bookingedit(\''.$row['booking_id'].'\');" title="'.$la['EDIT'].'"><i class="demo-icon icon-edit"> </i></a>&nbsp;';
					$modify .= '<a href="#" onclick="booking_allocate(\''.$row['booking_id'].'\');" title="'.$la['ALLOCATE_TO_OBJECT'].'"><i class="demo-icon icon-taxi"> </i></a>&nbsp;';
					
					$modify .= '<a href="#" onclick="bookingduplicate(\''.$row['booking_id'].'\');" title="'.$la['DUPLICATE'].'"><i class="demo-icon icon-tasks"> </i></a>&nbsp;';
					
					//$modify .= '<a href="#" onclick="bookingduplicate(\''.$row['booking_id'].'\');" title="'.$la['DUPLICATE'].'"><img src="theme/images/export.svg" /></a>&nbsp;';
				}
				else if($row["booking_status"]=="Deleted" || $row["booking_status"]=="Canceled" )
				{
					if ($_SESSION["privileges"] != 'subuser' && $_SESSION["privileges"] != 'viewer')
					{
						$modify .= '<a href="#" onclick="booking_change_wait(\''.$row['booking_id'].'\');" title="'.$la['DELETE'].'"><img src="theme/images/tick-green.svg" /></a>';
					}
				} 
				else if($row["booking_status"]=="Finished")
				{
					$modify = '';
				}
				else 
				{
					$modify .= '<a href="#" onclick="bookingdelete(\''.$row['booking_id'].'\');" title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>&nbsp;';
					$modify .= '<a href="#" onclick="cancel_booking(\''.$row['booking_id'].'\');" title="'.$la['CANCEL'].'"><img src="theme/images/remove-red.svg" /></a>&nbsp;';
					$modify .= '<a href="#" onclick="bookingedit(\''.$row['booking_id'].'\');" title="'.$la['EDIT'].'"><img src="theme/images/edit.svg" /></a>&nbsp;';
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
				
				if($row["parent_booking_id"]==0)
				$bookby=$row["book_by"];
				else
				$bookby="<label onclick='bookingedit(".$row['parent_booking_id'].");' style='border:1px solid #180ee2;padding: 2px;background:#180ee2;color:white;border-radius:5px;' title='This is additional booking (duplicate)' >".$row["book_by"]."</label>";
				
				//$responce->rows[$i]['id'] = $i;
				$responce->rows[$i]['id']=$row['booking_id'];
				$responce->rows[$i]['cell']=array("modify"=>$modify,
				"booking_id"=>$row["booking_id"],"book_by"=>$bookby,"createdate"=>$row["create_date"],"status"=>$stats
				,"app_user_id"=>$row["app_user_id"],"self_others"=>$row["self_others"],
				"emergency_address"=>$row["emergency_address"],"contact_no"=>$row["contact_no"],
				"emergency_reason"=>$row["emergency_reason"],"people_count"=>$row["people_count"],
				"age"=>$row["age"],"conscious"=>$row["conscious"],"breathing"=>$row["breathing"],
				"gender"=>$row["gender"],"person_name"=>$row["person_name"],"note1"=>$row["note1"],"note2"=>$row["note2"],
				"allocated_driver_id"=>$row["allocated_driver_id"],"allocated_driver_phone"=>$row["allocated_driver_phone"],
				"allocated_vehicleno"=>$row["allocated_vehicleno"],"allocated_imei"=>$row["allocated_imei"],
				"allocated_time"=>$row["allocated_time"],"driver_accept_time"=>$row["driver_accept_time"],
				"vehicle_reached_time"=>$row["vehicle_reached_time"],"pickedup_time"=>$row["pickedup_time"],
				"reached_dest_time"=>$row["reached_dest_time"]
				);
				$i++;
			}
		}
		
		header('Content-type: application/json');
		echo json_encode($responce);
		die;
	}
	else if(@$_GET['cmd'] == 'select_vehicle_list')
	{

		
		if(@$_GET['bookingid']=="")
		{
			$responce = new stdClass();
		$responce->page = 1;
		$responce->total = 1;
		$responce->records = 0;
		
		header('Content-type: application/json');
		echo json_encode($responce);
		die;
		}

		$bookingid=$_GET['bookingid'];
		 
		$qve="select * from ah_booking where booking_id='".$bookingid."'";
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

			// get records number

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
			(select allocated_driver_id from ah_booking where booking_status in 
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
						$modify = '<a href="#" onclick="allocateto_booking('.$i.');" title="'.$la['ALLOCATE_THIS_VEHICLE'].'"><img src="img/siren.png" style="width:24px;height:26px;" /></a>';
						
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


					
			header('Content-type: application/json');
			echo json_encode($responce);
			die;
		}
		else
		{
			$responce = new stdClass();
		$responce->page = 1;
		$responce->total = 1;
		$responce->records = 0;
		
		header('Content-type: application/json');
		echo json_encode($responce);
		die;
		}
	}

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
			usleep(150000);
			if ($position == '')
			{
				$position = geocoderGetAddress($lat, $lng);	
			}
			else
			{
				$position .= ' - '.geocoderGetAddress($lat, $lng);	
			}
		}
		
		return $position;
	}
   
    
?>