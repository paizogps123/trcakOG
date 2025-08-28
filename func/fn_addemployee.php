<?
session_start();
	include ('../init.php');
	include ('fn_common.php');
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

if(@$_GET['cmd']=='add_stis_department'){
	$page = $_GET['page']; // get the requested page
	$limit = $_GET['rows']; // get how many rows we want to have into the grid
	$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
	$sord = $_GET['sord']; // get the direction
	
	if(!$sidx) $sidx =1;
            // get records number			
	$q = "SELECT * FROM `cc_department` WHERE `user_id`=".$user_id." ";
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
	
	$q = "SELECT * FROM `cc_department` WHERE `user_id`=".$user_id." LIMIT $start, $limit";
	$r = mysqli_query($ms, $q);	
	
	$response = new stdClass();
	$response->page = $page;
	$response->total = $total_pages;
	$response->records = $count;		
	if ($r)
	{
		$i=0;
		while($row = mysqli_fetch_array($r))
		{
			$dep_id = $row['dept_id'];
			$dep_name =$row['dept_name'];
			
			// set modify buttons
			$modify= '<a href="#" onclick="editSistDepartment(\''.$dep_id.'\');" title="'.$la['EDIT'].'"><img src="theme/images/edit.svg" />';
			$modify .= '<a href="#" onclick="deleteSistDepartment(\''.$dep_id.'\');" title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>';				
			// set row
			$response->rows[$i]['id']=$dep_id;
			$response->rows[$i]['cell']=array($dep_name,$modify);
			$i++;
		}	
	}	
	header('Content-type: application/json');
	echo json_encode($response);
	die;
}

if(@$_GET['cmd']=='add_stis_shift'){
	serviceCheckAccountDateLimit();
	$page = $_GET['page']; // get the requested page
	$limit = $_GET['rows']; // get how many rows we want to have into the grid
	$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
	$sord = $_GET['sord']; // get the direction
	
	if(!$sidx) $sidx =1;
            // get records number			
	$q = "SELECT * FROM `cc_shift` WHERE `user_id`=".$user_id." ";
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
	
	$q = "SELECT * FROM `cc_shift` WHERE `user_id`=".$user_id." LIMIT $start, $limit";
	$r = mysqli_query($ms, $q);	
	
	$response = new stdClass();
	$response->page = $page;
	$response->total = $total_pages;
	$response->records = $count;		
	if ($r)
	{
		$i=0;
		while($row = mysqli_fetch_array($r))
		{
			$sh_id = $row['sid'];
			$shnmae =$row['name'];	
			if 	($row['status']=='A'){
				$status = '<img src="theme/images/tick-green.svg" />';
			}else if($row['status']=='D'){
				$status = '<img src="theme/images/remove-red.svg" />';
			}else{
				$status = 'Nill';
			}	
			$from =$row['from'];	
			$to =$row['to'];	
			$days =$row['days'];	
			
			// set modify buttons
			$modify= '<a href="#" onclick="editSistShift(\''.$sh_id.'\');" title="'.$la['EDIT'].'"><img src="theme/images/edit.svg" />';
			$modify .= '<a href="#" onclick="deleteSistShift(\''.$sh_id.'\');" title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>';				
			// set row
			$response->rows[$i]['id']=$sh_id;
			$response->rows[$i]['cell']=array($shnmae,$from,$to,$days,$status,$modify);
			$i++;
		}	
	}
	
	header('Content-type: application/json');
	echo json_encode($response);
	die;
}

if(@$_GET['cmd'] == "add_employee_list")
	{
		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
		
		if(!$sidx) $sidx =1;
                // get records number			
		$q = "SELECT * FROM `cc_live_user` WHERE `client`=".$user_id." ";
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
		
		$q = "SELECT *,a.status as userstatus FROM cc_live_user a JOIN cc_department b ON b.dept_id=a.department JOIN cc_shift c ON a.shift=c.sid where a.client=".$user_id." LIMIT $start, $limit";
		$r = mysqli_query($ms, $q);	
		
		$response = new stdClass();
		$response->page = $page;
		$response->total = $total_pages;
		$response->records = $count;		
		if ($r)
		{
			$i=0;
			while($row = mysqli_fetch_array($r))
			{
				if($row['userstatus']=='A'){
					$status = '<img src="theme/images/tick-green.svg" />';
				}else if($row['userstatus']=='D'){
					$status = '<img src="theme/images/remove-red.svg" />';
				}else{
					$status = 'Nill';
				}
				$live_id = $row['live_user_id'];
				$username =$row['username'];
				$empid = $row['empid'];
				$mobile = $row['mobile'];
				$address = $row['address'];	
				$department = $row['dept_name'];	
				$shift = $row['name'];
				if($row['user_type']=='U'){
					$user_type ='User';
				}elseif($row['user_type']=='S'){
					$user_type = 'Security';
				}else{
					$user_type='';
				}
				// set modify buttons
				$modify= '<a href="#" onclick="editaddEmployee(\''.$live_id.'\');" title="'.$la['EDIT'].'"><img src="theme/images/edit.svg" />';
				$modify .= '<a href="#" onclick="deleteaddEmployee(\''.$live_id.'\');" title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>';				
				// set row
				$response->rows[$i]['id']=$live_id;
				$response->rows[$i]['cell']=array($empid,$username,$department,$shift,$mobile,$address,$status,$user_type,$modify);
				$i++;
			}	
		}
		
		header('Content-type: application/json');
		echo json_encode($response);
		die;
	}

if(@$_GET['cmd'] == "add_security_list")
	{
		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
		
		if(!$sidx) $sidx =1;
                // get records number			
		$q = "SELECT * FROM `cc_live_user` WHERE `client`=".$user_id." ";
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
		
		$q = "SELECT *,a.status as userstatus FROM cc_live_user a where a.client=".$user_id." LIMIT $start, $limit";
		$r = mysqli_query($ms, $q);	
		
		$response = new stdClass();
		$response->page = $page;
		$response->total = $total_pages;
		$response->records = $count;		
		if ($r)
		{
			$i=0;
			while($row = mysqli_fetch_array($r))
			{
				if($row['userstatus']=='A'){
					$status = '<img src="theme/images/tick-green.svg" />';
				}else if($row['userstatus']=='D'){
					$status = '<img src="theme/images/remove-red.svg" />';
				}else{
					$status = 'Nill';
				}
				$live_id = $row['live_user_id'];
				$username =$row['username'];
				$empid = $row['empid'];
				$mobile = $row['mobile'];
				$gender = $row['gender'];
				$address = $row['address'];	
				$department = '';	
				$shift = '';
				if($row['user_type']=='U'){
					$user_type ='User';
				}elseif($row['user_type']=='S'){
					$user_type = 'Security';
				}else{
					$user_type='';
				}
				// set modify buttons
				$modify= '<a href="#" onclick="editaddSecurity(\''.$live_id.'\');" title="'.$la['EDIT'].'"><img src="theme/images/edit.svg" />';
				$modify .= '<a href="#" onclick="deleteaddSecurity(\''.$live_id.'\');" title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>';				
				// set row
				$response->rows[$i]['id']=$live_id;
				$response->rows[$i]['cell']=array($username,$mobile,$gender,$address,$status,$modify);
				$i++;
			}	
		}
		
		header('Content-type: application/json');
		echo json_encode($response);
		die;
	}

if(@$_GET['cmd']=='load_stis_cabrequest'){
	$page = $_GET['page']; // get the requested page
	$limit = $_GET['rows']; // get how many rows we want to have into the grid
	$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
	$sord = $_GET['sord']; // get the direction
	
	if(!$sidx) $sidx =1;
            // get records number			
	$q = "SELECT * FROM `cc_trip_manual` WHERE `user_id`=".$user_id." ";
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
	
	$q = "SELECT * FROM cc_trip_manual a LEFT JOIN gs_users b on a.client_id=b.id WHERE `user_id`=".$user_id." LIMIT $start, $limit";
	$r = mysqli_query($ms, $q);	

	$response = new stdClass();
	$response->page = $page;
	$response->total = $total_pages;
	$response->records = $count;		
	if ($r)
	{
		$i=0;
		while($row = mysqli_fetch_array($r))
		{
			$tr_id = $row['tripm_id'];
			$client=$row['username'];	
			$schedule=$row['schedule_at'];	
			$from =$row['from_loc'];	
			$to =$row['to_loc'];	
			if($row['security_allocated']=='Y'){
				$security ='Yes';
			}else if($row['security_allocated']=='N'){
				$security ='No';
			}
			$totuser =$row['total_user'];	
			if 	($row['status']=='A'){
				$status = '<img src="theme/images/tick-green.svg" />';
			}else if($row['status']=='D'){
				$status = '<img src="theme/images/remove-red.svg" />';
			}else{
				$status = 'Nill';
			}		
			if ($row['trip_type']=='P'){
				$type ='Pick Up';
			}else if($row['trip_type']=='D'){
				$type ='Drop';
			}
			if($row['trip_status']=='W'){
				$tripststus='<img src="theme/images/waiting.png" style="width: 70px !important;"/>';
			}elseif($row['trip_status']=='C'){
				$tripststus='<img src="theme/images/cancel.png" style="width: 70px !important;"/>';
			}elseif($row['trip_status']=='A'){
				$tripststus='<img src="theme/images/accept.png" style="width: 70px !important;"/>';
			}elseif($row['trip_status']=='F'){
				$tripststus='<img src="theme/images/complete.png" style="width: 70px !important;"/>';
			}else{
				$tripststus='';
			}
			// set modify buttons
			$modify= '<a href="#" onclick="editsistCabRequest(\''.$tr_id.'\');" title="'.$la['EDIT'].'"><img src="theme/images/edit.svg" />';
			// $modify .= '<a href="#" onclick="deletesistCabRequest(\''.$tr_id.'\');" title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>';	
			$modify .= '<a href="#" onclick="viewSistCabRequestUser(\''.$tr_id.'\');" title="'.$la['VIEWER'].'"><img src="theme/images/file.svg" /></a>';	
			$modify .= '<a href="#" onclick="cancelsistCabRequest(\''.$tr_id.'\');" title="'.$la['CANCEL'].'"><img src="theme/images/remove-red.svg" /></a>';			
			// set row
			$response->rows[$i]['id']=$tr_id;
			$response->rows[$i]['cell']=array($client,$schedule,$from,$to,$security,$totuser,$status,$type,$tripststus,$modify);
			$i++;
		}	
	}
	
	header('Content-type: application/json');
	echo json_encode($response);
	die;
}

if(@$_GET['cmd']=='load_stis_cabrequest_vendor'){
	$page = $_GET['page']; // get the requested page
	$limit = $_GET['rows']; // get how many rows we want to have into the grid
	$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
	$sord = $_GET['sord']; // get the direction
	
	if(!$sidx) $sidx =1;
            // get records number			
	$q = "SELECT * FROM `cc_trip_manual` WHERE `user_id`=".$user_id." ";
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
	
	$q = "SELECT * FROM cc_trip_manual a LEFT JOIN gs_users b on a.user_id=b.id WHERE `client_id`=".$user_id." and a.user_id!='0' LIMIT $start, $limit";
	$r = mysqli_query($ms, $q);	
	
	$response = new stdClass();
	$response->page = $page;
	$response->total = $total_pages;
	$response->records = $count;		
	if ($r)
	{
		$i=0;
		while($row = mysqli_fetch_array($r))
		{
			$tr_id = $row['tripm_id'];
			$client=$row['username'];	
			$schedule=$row['schedule_at'];	
			$from =$row['from_loc'];	
			$to =$row['to_loc'];	
			if($row['security_allocated']=='Y'){
				$security ='Yes';
			}else if($row['security_allocated']=='N'){
				$security ='No';
			}
			$totuser =$row['total_user'];	
			if 	($row['status']=='A'){
				$status = '<img src="theme/images/tick-green.svg" />';
			}else if($row['status']=='D'){
				$status = '<img src="theme/images/remove-red.svg" />';
			}else{
				$status = 'Nill';
			}		
			if ($row['trip_type']=='P'){
				$type ='Pick Up';
			}else if($row['trip_type']=='D'){
				$type ='Drop';
			}

			if($row['trip_status']=='W'){
				$tripststus='<img src="theme/images/waiting.png" style="width: 70px !important;"/>';
			}elseif($row['trip_status']=='C'){
				$tripststus='<img src="theme/images/cancel.png" style="width: 70px !important;"/>';
			}elseif($row['trip_status']=='A'){
				$tripststus='<img src="theme/images/accept.png" style="width: 70px !important;"/>';
			}elseif($row['trip_status']=='F'){
				$tripststus='<img src="theme/images/complete.png" style="width: 70px !important;"/>';
			}else{
				$tripststus='';
			}
			
			// set modify buttons
			$modify = '<a href="#" onclick="sistVendorAddDriver(\''.$tr_id.'\');" title="'.$la['DRIVER'].'"><img src="theme/images/driver-st.svg" /></a>';
			$modify .= '<a href="#" onclick="viewSistCabRequestUser(\''.$tr_id.'\');" title="'.$la['VIEWER'].'"><img src="theme/images/file.svg" /></a>';				
			// set row
			$response->rows[$i]['id']=$tr_id;
			$response->rows[$i]['cell']=array($client,$schedule,$from,$to,$security,$totuser,$status,$type,$tripststus,$modify);
			$i++;
		}	
	}
	
	header('Content-type: application/json');
	echo json_encode($response);
	die;
}

if(@$_GET['cmd']=='load_stis_self_cabrequest_vendor'){
	$page = $_GET['page']; // get the requested page
	$limit = $_GET['rows']; // get how many rows we want to have into the grid
	$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
	$sord = $_GET['sord']; // get the direction
	
	if(!$sidx) $sidx =1;
            // get records number			
	$q = "SELECT * FROM `cc_trip_manual` WHERE `user_id`=".$user_id." ";
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
	$q="SELECT a.tripm_id,c.username,a.schedule_at,a.from_loc,a.to_loc,a.security_allocated,a.total_user,a.status,a.trip_type,a.trip_status,d.username AS drivername,d.subuser_phone,c.mobile as cl_mobile FROM cc_trip_manual a LEFT JOIN cc_trip_manual_sub b ON a.tripm_id=b.tripm_id LEFT JOIN cc_live_user c ON c.live_user_id=b.live_user_id and c.user_type!='S' LEFT JOIN gs_users d ON d.id=a.allocated_driver WHERE a.client_id=".$user_id." AND a.user_id='0' and c.username!=''";
	if(isset($_GET['tripstatus']) && $_GET['tripstatus']!=''){
		$q.="and a.trip_status='".$_GET['tripstatus']."'";
	}

	$q.=" ORDER BY a.tripm_id desc LIMIT $start, $limit";
	$r = mysqli_query($ms, $q);		
	$response = new stdClass();
	$response->page = $page;
	$response->total = $total_pages;
	$response->records = $count;		
	if ($r)
	{
		$i=0;
		while($row = mysqli_fetch_array($r))
		{
			$tr_id = $row['tripm_id'];
			$client=$row['username'];	
			$clientnumber=$row['cl_mobile'];	
			$schedule=$row['schedule_at'];	
			$from =$row['from_loc'];	
			$to =$row['to_loc'];	
			if($row['security_allocated']=='Y'){
				$security ='Yes';
			}else if($row['security_allocated']=='N'){
				$security ='No';
			}
			$driver=$row['drivername'];
			$drivernumber=$row['subuser_phone'];	
			if ($row['trip_type']=='P'){
				$type ='Pick Up';
			}else if($row['trip_type']=='D'){
				$type ='Drop';
			}

			if($row['trip_status']=='W'){
				$tripststus='<img src="theme/images/waiting.png" style="width: 70px !important;"/>';
			}elseif($row['trip_status']=='C'){
				$tripststus='<img src="theme/images/cancel.png" style="width: 70px !important;"/>';
			}elseif($row['trip_status']=='A'){
				$tripststus='<img src="theme/images/accept.png" style="width: 70px !important;"/>';
			}elseif($row['trip_status']=='F'){
				$tripststus='<img src="theme/images/complete.png" style="width: 70px !important;"/>';
			}elseif($row['trip_status']=='P'){
				$tripststus='<img src="theme/images/pickup.png" style="width: 70px !important;"/>';
			}elseif($row['trip_status']=='S'){
				$tripststus='<img src="theme/images/start.png" style="width: 70px !important;"/>';
			}else{
				$tripststus='';
			}
			
			// set modify buttons
			$modify= '<a href="#" onclick="sistSelfrequestEdit(\''.$tr_id.'\');" title="'.$la['EDIT'].'"><img src="theme/images/edit.svg" />';	
			$modify .= '<a href="#" onclick="deletesistCabRequest(\''.$tr_id.'\');" title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>';
			$modify .= '<a href="#" onclick="sistSelfrequestView(\''.$tr_id.'\');" title="'.$la['VIEWER'].'"><img src="theme/images/file.svg" /></a>';
			// set row
			$response->rows[$i]['id']=$tr_id;
			$response->rows[$i]['cell']=array($client,$clientnumber,$schedule,$from,$to,$security,$driver,$type,$tripststus,$modify);
			$i++;
		}	
	}
	
	header('Content-type: application/json');
	echo json_encode($response);
	die;
}

if(@$_POST['cmd']=='self_cabbooking_status'){
	$result=array();
	$usertype_name='';
	$usertype_mobile='';
	$q="SELECT a.tripm_id,c.user_type,c.username,a.schedule_at,a.from_loc,a.to_loc,a.security_allocated,a.trip_type,a.trip_status,c.mobile,a.allocated_driver as driverid,d.username as drivername,d.subuser_phone as drivermobile,a.all_picked,a.all_droped FROM cc_trip_manual a LEFT JOIN cc_trip_manual_sub b ON a.tripm_id=b.tripm_id LEFT JOIN cc_live_user c ON c.live_user_id=b.live_user_id LEFT JOIN gs_users d ON d.id=a.allocated_driver  WHERE a.tripm_id=".$_POST['id']." AND a.user_id='0'";
	$r=mysqli_query($ms,$q);
	while($row=mysqli_fetch_assoc($r)){
		$result=$row;

		if($row['trip_status']=='W'){
			$result['status']='<img src="theme/images/waiting.png" style="width: 70px !important;"/>';
		}else if($row['trip_status']=='C'){
			$result['status']='<img src="theme/images/cancel.png" style="width: 70px !important;"/>';
		}else if($row['trip_status']=='A'){
			$result['status']='<img src="theme/images/accept.png" style="width: 70px !important;"/>';
		}else if($row['trip_status']=='F'){
			$result['status']='<img src="theme/images/complete.png" style="width: 70px !important;"/>';
		}else if($row['trip_status']=='P'){
			$result['status']='<img src="theme/images/pickup.png" style="width: 70px !important;"/>';
		}else if($row['trip_status']=='S'){
			$result['status']='<img src="theme/images/start.png" style="width: 70px !important;"/>';
		}else{
			$result['status']='';
		}

		if($row['user_type']=='S'){
			$usertype_name=$row['username'];
			$usertype_mobile=$row['mobile'];
		}
		$result['usertype_name']=$usertype_name;
		$result['usertype_mobile']=$usertype_mobile;

	}
	header('Content-type: application/json');
	echo json_encode($result);
	die;
}

if(@$_POST['cmd']=='edit_self_request'){
	$result=array();
	$usertype_id='';
	$q="SELECT a.tripm_id,c.user_type,c.live_user_id,c.username,a.schedule_at,a.from_loc,a.from_lat,a.from_lng,a.to_lat,a.to_lng,a.to_loc,a.security_allocated,a.total_user,a.status,a.trip_type,a.trip_status,c.mobile,d.user_id as driverid FROM cc_trip_manual a LEFT JOIN cc_trip_manual_sub b ON a.tripm_id=b.tripm_id LEFT JOIN cc_live_user c ON c.live_user_id=b.live_user_id LEFT JOIN cc_trip_manual_request d ON d.tripm_id=a.tripm_id WHERE a.tripm_id=".$_POST['id']." AND a.user_id='0'";
	$r=mysqli_query($ms,$q);
	while($row=mysqli_fetch_array($r)){
		$result=$row;
		if($row['user_type']=='S'){
			$usertype_id=$row['live_user_id'];
		}
		$result['usertype_id']=$usertype_id;
	}
	header('Content-type: application/json');
	echo json_encode($result);
	die;
}

if(@$_POST['cmd']=='update_stis_self_newcab_request'){
	$q="SELECT * FROM cc_trip_manual_sub where tripm_id='".$_POST['cid']."'";
	$r=mysqli_query($ms,$q);
	while($row=mysqli_fetch_array($r)){
		$sq="select * from cc_live_user  where live_user_id='".$row['live_user_id']."' ";
		$sr=mysqli_query($ms,$sq);
		$rows=mysqli_fetch_array($sr);
		if($rows['user_type']=='U'){
			$qu="UPDATE cc_live_user SET username='".$_POST['cna']."',mobile='".$_POST['cmo']."' where live_user_id='".$rows['live_user_id']."' and user_type='U'";
			if(mysqli_query($ms,$qu)){
				$qt="UPDATE cc_trip_manual SET schedule_at='".$_POST['csc']."',from_loc='".$_POST['cfr']."',to_loc='".$_POST['cto']."',security_allocated='".$_POST['cse']."',allocated_driver='".$_POST['cdr']."',trip_type='".$_POST['ctr']."',`from_lat`='".$_POST['cfrlat']."',`from_lng`='".$_POST['cfrlon']."',`to_lat`='".$_POST['ctolat']."',`to_lng`='".$_POST['ctolon']."' WHERE tripm_id='".$_POST['cid']."'";
				
				if(mysqli_query($ms,$qt)){
					$ql="UPDATE cc_trip_manual_request SET user_id='".$_POST['cdr']."' WHERE tripm_id='".$_POST['cid']."'";
					mysqli_query($ms,$ql);
				}
			}
		}else{
			if($_POST['cse']=='Y' && $rows['user_type']=='S'){
				$q="UPDATE cc_trip_manual_sub SET live_user_id='".$_POST['csena']."' WHERE tripms_id='".$row['tripms_id']."'";
			}else{
				$q="DELETE FROM cc_trip_manual_sub where live_user_id='".$_POST['csena']."'";				
			}
			mysqli_query($ms,$q);
		}
	}
	echo 'OK';
}

if(@$_POST['cmd']=='add_new_employee'){
	$client=$user_id;
	$count=0;
	if ($_POST['eid']!=''){
		$qs="SELECT * FROM cc_live_user WHERE empid='".$_POST['eid']."'";
		$r=mysqli_query($ms,$qs);
		$count=mysqli_num_rows($r);
	}
	if ($count>0){
		echo 'USERID';
	}else{
		$q="INSERT INTO cc_live_user (`client`,`empid`,`username`,`mobile`,`dob`,`gender`,`address`,`user_type`,`status`,`department`,`shift`) VALUES ('".$client."','".$_POST['eid']."','".$_POST['un']."','".$_POST['mo']."','".$_POST['dob']."','".$_POST['gen']."','".$_POST['add']."','".$_POST['ut']."','".$_POST['st']."','".$_POST['de']."','".$_POST['sh']."')";
		if (mysqli_query($ms,$q)){
			echo 'OK';
		}else{
			echo 'ERROR';
		}
	}
}

if (@$_POST['cmd']=='delete_addemployee'){
	$q="DELETE FROM cc_live_user where live_user_id='".$_POST['id']."'";
	mysqli_query($ms,$q);
	echo 'OK';
}

if(@$_POST['cmd']=='edit_addemployee'){
	$result=array();
	$q="SELECT live_user_id,empid,username,mobile,dob,gender,address,status,user_type,department,shift FROM cc_live_user where live_user_id='".$_POST['eid']."'";
	$r=mysqli_query($ms,$q);
	while($row=mysqli_fetch_array($r)){
		$result=$row;
	}
header('Content-type: application/json');
echo json_encode($result);
die;
}

if(@$_POST['cmd']=='update_live_employee'){
	$client=$user_id;
	$q="UPDATE cc_live_user SET `client`='".$client."',`empid`='".$_POST['eid']."',`username`='".$_POST['un']."',`mobile`='".$_POST['mo']."',`dob`='".$_POST['dob']."',`gender`='".$_POST['gen']."',`address`='".$_POST['add']."',`user_type`='".$_POST['ut']."',`status`='".$_POST['st']."',department='".$_POST['de']."',`shift`='".$_POST['sh']."' WHERE live_user_id='".$_POST['li_id']."'";
	if(mysqli_query($ms,$q)){
		echo 'OK';
	}else{
		echo 'Error';
	}
}

if(@$_POST['cmd']=='edit_sits_department'){
	$result=array();
	$q="SELECT * FROM cc_department WHERE dept_id='".$_POST['edid']."'";
	$r=mysqli_query($ms,$q);
	while($row=mysqli_fetch_array($r)){
		$result=$row;
	}
	header('Content-type: application/json');
	echo json_encode($result);
	die;
}

if(@$_POST['cmd']=='add_stis_department'){
	$q="INSERT INTO cc_department (`user_id`,`dept_name`) VALUES ('".$user_id."','".$_POST['dname']."')";
	if(mysqli_query($ms,$q)){
		echo 'OK';
	}else{
		echo 'Error';
	}
}

if(@$_POST['cmd']=='update_stis_department'){
	$q="UPDATE cc_department SET `dept_name`='".$_POST['dn']."' WHERE `dept_id`='".$_POST['did']."'";
	if(mysqli_query($ms,$q)){
		echo 'OK';
	}else{
		echo 'Error';
	}
}

if(@$_POST['cmd']=='delete_sits_department'){
	$q="DELETE FROM cc_department WHERE `dept_id`='".$_POST['edid']."'";
	if(mysqli_query($ms,$q)){
		echo 'OK';
	}else{
		echo 'Error';
	}
}

if(@$_POST['cmd']=='add_stif_shift'){
	$q="INSERT INTO cc_shift (`user_id`,`name`,`status`,`from`,`to`) VALUES ('".$user_id."','".$_POST['sna']."','".$_POST['st']."','".$_POST['sfr']."','".$_POST['sto']."')";
	if(mysqli_query($ms,$q)){
		echo 'OK';
	}else{
		echo 'ERROR';
	}
}

if(@$_POST['cmd']=='edit_sits_shift'){
	$result=array();
	$q="SELECT * FROM cc_shift WHERE `sid`='".$_POST['sdid']."'";
	$r=mysqli_query($ms,$q);
	while($row=mysqli_fetch_array($r)){
		$result=$row;
	}
	header('Content-type: application/json');
	echo json_encode($result);
}
if(@$_POST['cmd']=='update_stif_shift'){
	$q="UPDATE cc_shift SET `name`='".$_POST['sna']."',`status`='".$_POST['st']."',`from`='".$_POST['sfr']."',`to`='".$_POST['sto']."' WHERE `sid`='".$_POST['sid']."'";
	mysqli_query($ms,$q);
	echo 'OK';
}

if(@$_POST['cmd']=='delete_stis_shift'){
	$q="DELETE FROM cc_shift WHERE `sid`='".$_POST['sid']."'";
	mysqli_query($ms,$q);
	echo 'OK';
}

if(@$_POST['cmd']=='load_stis_data'){
	$result=array();
	$q="SELECT sid,name FROM cc_shift where user_id='".$user_id."' and status='A'";
	$r=mysqli_query($ms,$q);
	while($row=mysqli_fetch_array($r)){
		$result['shift'][]=$row;
	}
	$qd="SELECT dept_id,dept_name FROM cc_department WHERE user_id='".$user_id."'";
	$rd=mysqli_query($ms,$qd);
	while($rowd=mysqli_fetch_array($rd)){
		$result['department'][]=$rowd;
	}
	header('Content-type: application/json');
	echo json_encode($result);
}

if(@$_POST['cmd']=="save_stis_newcab_request"){
	$q="INSERT INTO cc_trip_manual (`client_id`,`user_id`,`cab_type_id`,`schedule_at`,`from_loc`,`to_loc`,`security_allocated`,`total_user`,`status`,`trip_type`) VALUES ('".$_POST['cid']."','".$user_id."','".$_POST['cty']."','".$_POST['csc']."','".$_POST['cfr']."','".$_POST['cto']."','".$_POST['cse']."','".$_POST['ctu']."','".$_POST['cst']."','".$_POST['ctr']."')";
	if(mysqli_query($ms,$q)){
		$last_id = $ms->insert_id;
		$livuser_count=explode(',',$_POST['liveuser']);
		for($i=0;$i<count($livuser_count);$i++){
			$qT="INSERT INTO cc_trip_manual_sub (tripm_id,live_user_id) VALUES ('".$last_id."','".$livuser_count[$i]."')";
			mysqli_query($ms,$qT);
		}		
	}
	echo 'OK';
}

if(@$_POST['cmd']=="save_stis_self_newcab_request"){
	$qu="INSERT INTO cc_live_user (`client`,`username`,`mobile`,`user_type`,`dob`,`gender`,`address`,`status`,`department`,`shift`) VALUES ('0','".$_POST['cna']."','".$_POST['cmo']."','U','','','','A','','')";
	if(mysqli_query($ms,$qu)){
		$last_id1= $ms->insert_id;
		$q="INSERT INTO cc_trip_manual (`client_id`,`user_id`,`cab_type_id`,`imei`,`schedule_at`,`from_loc`,`to_loc`,`security_allocated`,`total_user`,`status`,`trip_type`,`allocated_driver`,`driver_status`,`trip_status`,`from_lat`,`from_lng`,`to_lat`,`to_lng`) VALUES ('".$user_id."','','','".$_POST['cob']."','".$_POST['csc']."','".$_POST['cfr']."','".$_POST['cto']."','".$_POST['cse']."','','A','".$_POST['ctr']."','".$_POST['cdr']."','A','A','".$_POST['cfrlat']."','".$_POST['cfrlon']."','".$_POST['ctolat']."','".$_POST['ctolon']."')";
		if(mysqli_query($ms,$q)){
			$last_id = $ms->insert_id;
			if($_POST['cse']=='Y'){
				$last_id1=$last_id1.','.$_POST['csena'];
			}
			$exid=explode(',',$last_id1);
			// $potp=generateNumericOTP();
			$potp='1234';
			for($i=0;$i<count($exid);$i++){
				$qT="INSERT INTO cc_trip_manual_sub (tripm_id,live_user_id,my_pickup_otp) VALUES ('".$last_id."','".$exid[$i]."','".$potp."')";
				mysqli_query($ms,$qT);
			}
			$qD="INSERT INTO cc_trip_manual_request (tripm_id,user_id) VALUES ('".$last_id."','".$_POST['cdr']."')";
			mysqli_query($ms,$qD);
			echo 'OK';
		}
	}
	$dq="SELECT * FROM gs_users where id='".$_POST['cdr']."'";
	$dr=mysqli_query($ms,$dq);
	$drow=mysqli_fetch_assoc($dr);
	$mobileno=$drow['subuser_phone'];
	$content='Hello++'.$drow['username'].'+!+You+have+received+a+booking+on+STIS+Driver+app+.Trip+time+'.$_POST['csc'].'+.have+a+safe+drive.+Thank+You';
	$v="http://mssg.365dayssms.com/api/v2/sms/send?access_token=c7f8c8a288d3bb1e6ac77c52eb645f23&message=".$content."&sender=PLYINC&to=".$mobileno."&service=T";
	$result = file_get_contents($v);
}

if(@$_POST['cmd'] == 'select_client_type')
	{
		$q = "SELECT id,username FROM cc_user_clients uc left join gs_users us on uc.company_id='".$user_id."' WHERE uc.user_id=us.id";
		$ret=mysqli_query($ms,$q);
		$rtnary=array();
		while($row = mysqli_fetch_assoc($ret))
		{
			$rtnary['client'][]=$row;
		}
		echo json_encode($rtnary);
		die;
	}

if(@$_POST['cmd'] == 'select_liveuser_type')
	{
		$qq = "SELECT live_user_id,username as live_username FROM cc_live_user where client='".$user_id."' ORDER BY live_user_id ASC";
		$retq=mysqli_query($ms,$qq);
		$liveuser=array();
		while($rowq = mysqli_fetch_assoc($retq))
		{
			$liveuser[]=$rowq;
		}
		$result["liveuser"]=$liveuser;
		echo json_encode($result);
		die;
	}

if(@$_POST['cmd']=='edit_stis_cabrequest'){
	$result=array();
	$q="SELECT tripm_id,client_id,cab_type_id,schedule_at,from_loc,to_loc,security_allocated,total_user,status,trip_type FROM cc_trip_manual WHERE tripm_id='".$_POST['id']."'";
	$r=mysqli_query($ms,$q);
	while($row=mysqli_fetch_array($r)){
		$result['tripmanual']=$row;
		$qt="SELECT a.tripm_id as sub_tripid,a.live_user_id,b.username,b.mobile FROM cc_trip_manual_sub a left join cc_live_user b on a.live_user_id=b.live_user_id  WHERE a.tripm_id='".$row['tripm_id']."'";
		$rt=mysqli_query($ms,$qt);
		while($rowT=mysqli_fetch_array($rt)){
			$result['tripmanualsub'][]=$rowT;
		}
	}
	header('Content-type: application/json');
	echo json_encode($result);
}

if(@$_POST['cmd']=='update_stis_cabrequest'){
	$selarrat='';
	$qs="SELECT * FROM cc_trip_manual where tripm_id='".$_POST['ctid']."'";
	$rs=mysqli_query($ms,$qs);
	$rows=mysqli_fetch_array($rs);
	if($rows['client_id']==$_POST['cid']){
		$q="UPDATE cc_trip_manual SET `client_id`='".$_POST['cid']."',`cab_type_id`='".$_POST['cty']."',`schedule_at`='".$_POST['csc']."',`from_loc`='".$_POST['cfr']."',`to_loc`='".$_POST['cto']."',`security_allocated`='".$_POST['cse']."',`total_user`='".$_POST['ctu']."',`status`='".$_POST['cst']."',`trip_type`='".$_POST['ctr']."' WHERE tripm_id='".$_POST['ctid']."'";
	}else{
		$q="UPDATE cc_trip_manual SET `client_id`='".$_POST['cid']."',`cab_type_id`='".$_POST['cty']."',`schedule_at`='".$_POST['csc']."',`from_loc`='".$_POST['cfr']."',`to_loc`='".$_POST['cto']."',`security_allocated`='".$_POST['cse']."',`total_user`='".$_POST['ctu']."',`status`='".$_POST['cst']."',`trip_type`='".$_POST['ctr']."',trip_status='W' WHERE tripm_id='".$_POST['ctid']."'";
		$qr="DELETE FROM cc_trip_manual_request WHERE tripm_id='".$_POST['ctid']."'";
		mysqli_query($ms,$qr);
	}
	mysqli_query($ms,$q);
	$qa="SELECT live_user_id FROM cc_trip_manual_sub WHERE tripm_id='".$_POST['ctid']."'";
	$ra=mysqli_query($ms,$qa);
	while($rowa=mysqli_fetch_array($ra)){
		$selarrat=$selarrat.','.$rowa['live_user_id'];		
	}
	$exreport_list=explode(',', $selarrat);
	$livuser_count=explode(',',$_POST['liveuser']);
	for($i=0;$i<count($exreport_list);$i++){
		if(!in_array($exreport_list[$i],$livuser_count)){
			$qa="DELETE FROM cc_trip_manual_sub WHERE `tripm_id`='".$_POST['ctid']."' and live_user_id='".$exreport_list[$i]."'";
			mysqli_query($ms,$qa);
		}
	}
	for($i=0;$i<count($livuser_count);$i++){
		if(!in_array($livuser_count[$i],$exreport_list)){
			$qu="INSERT INTO cc_trip_manual_sub (`tripm_id`,`live_user_id`) VALUES ('".$_POST['ctid']."','".$livuser_count[$i]."')";
			mysqli_query($ms,$qu);
		}
	}
	echo 'OK';
}

if(@$_POST['cmd']=='delete_stis_cabrequest'){
	$q="SELECT * FROM cc_trip_manual_sub where tripm_id='".$_POST['cid']."'";
	$r=mysqli_query($ms,$q);
	$row=mysqli_fetch_array($r);
	$qu="DELETE FROM `cc_live_user` where live_user_id='".$row['live_user_id']."'";
	mysqli_query($ms,$qu);
	$q="DELETE FROM `cc_trip_manual` WHERE `tripm_id`='".$_POST['id']."'";
	mysqli_query($ms,$q);
	$q="DELETE FROM `cc_trip_manual_request` WHERE `tripm_id`='".$_POST['id']."'";
	mysqli_query($ms,$q);
	$q="DELETE FROM `cc_trip_manual_sub` WHERE `tripm_id`='".$_POST['id']."'";
	mysqli_query($ms,$q);
	echo 'OK';
}

if(@$_POST['cmd']=='add_jqgrid_liveuser_date'){
	$result=array();
	$q="SELECT live_user_id,username,mobile FROM cc_live_user WHERE live_user_id='".$_POST['id']."'";
	$r=mysqli_query($ms,$q);
	while($row=mysqli_fetch_array($r)){
		$result=$row;
	}
	header('Content-type: application/json');
	echo json_encode($result);
}

if(@$_POST['cmd']=='load_vendor_allocate_driver'){
	$result=array();
	$q="SELECT id,username FROM gs_users where manager_id='".$user_id."' and driver='true'";
	$r=mysqli_query($ms,$q);
	while($row=mysqli_fetch_array($r)){
		$dcou=0;
		// $qu="SELECT status FROM cc_trip_manual_request WHERE user_id='".$row['id']."' and status='W'";
		// $ru=mysqli_query($ms,$qu);
		// if(mysqli_num_rows($ru)>0){
		// 	while($rowu=mysqli_fetch_array($ru)){
		// 		$dcou+=1;
		// 	}
		// 	if($dcou<2){
		// 		$result[]=$row;
		// 	}
		// }else{
			$result[]=$row;
		// }
	}
	header('Content-type: application/json');
	echo json_encode($result);
}

if(@$_POST['cmd']=='add_vendor_allocatedriver_data'){
	$result=array();
	$q="SELECT id,username,subuser_phone FROM gs_users where id='".$_POST['id']."'";
	$r=mysqli_query($ms,$q);
	while($row=mysqli_fetch_array($r)){
		$result[]=$row;
	}
	header('Content-type: application/json');
	echo json_encode($result);
}

if(@$_POST['cmd']=='save_trip_allocate_driver'){
	$seldriver='';
	$q="UPDATE cc_trip_manual SET trip_status='A'WHERE tripm_id='".$_POST['tid']."'";
	if(mysqli_query($ms,$q)){
		$qa="SELECT user_id FROM cc_trip_manual_request WHERE tripm_id='".$_POST['tid']."'";
		$ra=mysqli_query($ms,$qa);
		while($rowa=mysqli_fetch_array($ra)){
			if(!isset($seldriver)){
				$seldriver=$rowa['user_id'];	
			}else{
				$seldriver=$seldriver.','.$rowa['user_id'];
			}	
		}	
		$seldri=explode(',', $seldriver);
		$driid=explode(',', $_POST['driverid']);
		for($i=0;$i<count($seldri);$i++){
			if(!in_array($seldri[$i],$driid)){
				$qa="DELETE FROM cc_trip_manual_request WHERE `tripm_id`='".$_POST['tid']."' and user_id='".$seldri[$i]."'";
				mysqli_query($ms,$qa);
			}
		}
		for($j=0;$j<count($driid);$j++){
			if(!in_array($driid[$j],$seldri)){
				$qu="INSERT INTO cc_trip_manual_request (`tripm_id`,`user_id`) VALUES ('".$_POST['tid']."','".$driid[$j]."')";
				mysqli_query($ms,$qu);
			}
		}
		echo 'OK';
	}else{
		echo 'ERROR';
	}
}

if(@$_POST['cmd']=='load_vendor_allocatedriver_data'){
	$result=array();
	$q="SELECT b.id,b.username,b.subuser_phone FROM cc_trip_manual_request a LEFT JOIN gs_users b ON b.id=a.user_id WHERE a.tripm_id='".$_POST['id']."'";
	$r=mysqli_query($ms,$q);
	while($row=mysqli_fetch_array($r)){
		$result[]=$row;
	}
	header('Content-type: application/json');
	echo json_encode($result);
}

if(@$_POST['cmd']=='grid_stis_allocatedriver'){
	$result=array();
	$q="SELECT b.id,b.username,b.subuser_phone FROM cc_trip_manual_request a LEFT JOIN gs_users b ON b.id=a.user_id WHERE a.tripm_id='".$_POST['id']."'";
	$r=mysqli_query($ms,$q);
	while($row=mysqli_fetch_array($r)){
		$result[]=$row;
	}
	header('Content-type: application/json');
	echo json_encode($result);
}

if(@$_POST['cmd']=='vendor_cancel_trip' || @$_POST['cmd']=='company_cancel_trip'){
	$q="UPDATE cc_trip_manual SET trip_status='C',client_id='0' WHERE tripm_id='".$_POST['tid']."'";
	mysqli_query($ms,$q);
	$qt="UPDATE cc_trip_manual_request SET status='C' WHERE tripm_id='".$_POST['tid']."'";
	mysqli_query($ms,$qt);
	echo 'OK';
}

function generateNumericOTP($n=4) { 
	$generator = "1357902468"; 
	$result = ""; 
	for ($i = 1; $i <= $n; $i++) { 
	    $result .= substr($generator, (rand()%(strlen($generator))), 1);
	} 
	return $result; 
} 

?>
