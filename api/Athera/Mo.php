
<?php
 
   include ('../../init.php');  
   include ('../../func/fn_common.php');
   include ('../../tools/sms.php'); 
   date_default_timezone_set("Asia/Kolkata");
 	
   function LiveTrack_Child($studentinfo)
   {
   	global $ms;
   		$curdateindian=date('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s").'+ 5 hour + 30 minutes'));
		$curdateindian=date('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s")));
		$curdateindianHM=date('H.i',strtotime(date("Y-m-d H:i:s")));
		$tripdata=CheckWithInTripTime($studentinfo,$curdateindianHM);
		if($tripdata)
		{
			$Live=Get_Live_Loc($tripdata);
			if($Live)
			{
				$result['tyv'] = 's';
            	$result['Boardornot'] = 'Na';
            	if($tripdata["route_id"]==$studentinfo["route_id"])
            	$result['pickordrop'] = 'P';
            	else if($tripdata["route_id"]==$studentinfo["route_id_down"])
            	$result['pickordrop'] = 'D';
            	else 
            	$result['pickordrop'] = 'Na';
            	
            	$result['msg'] = '';
            	$result['mydata'] = $Live;
			}
			else 
			{
				$result['tyv'] = 'e';
            	$result['Boardornot'] = 'Na';
            	$result['msg'] = 'Sorry, Live data not available!';
			}
		}
		else 
		{
			$result['tyv'] = 'e';
            $result['Boardornot'] = 'Na';
            $result['msg'] = 'Sorry, Please try on scheduled time!';
		}
   		header('Content-type: application/json');
   	echo json_encode($result);
   	die;
   }
   
   	function Get_Live_Loc($tripdata)
    {
    	global  $ms;
    	$qvehi="select dt_tracker,lat,lng,speed,angle,name from gs_objects where active='true' and imei='".$tripdata["imei"]."'";
    	$rovehi=mysqli_query($ms,$qvehi);
    	if($rowvehi=mysqli_fetch_assoc($rovehi))
    	{
    		//$rowvehi["lat"]="13.068881";
    		//$rowvehi["lng"]="80.173159";
    		
    		//$rowvehi["lat"]="13.072246";
    		//$rowvehi["lng"]="80.170648";
    		
    		//$rowvehi["lat"]="13.071306";
    		//$rowvehi["lng"]="80.174768";
    		
    		$rowvehi["dt_tracker"]=date("Y-m-d H:i:s", strtotime($rowvehi["dt_tracker"]."+5 hours +30 minutes"));
    		return $rowvehi;
    	}
    	else 
    	{
    		return false;
    	}
    }
    
    function CheckWithInTripTime($studentinfo,$curdateindianHM)
    {
    	global  $ms;
    	$qtrip=" select * from droute_events where active='true' and (route_id = '".$studentinfo["route_id"]."' or route_id='".$studentinfo["route_id_down"]."') and concat(tfh,'.',tfm)<=".$curdateindianHM." and concat(tth,'.',ttm)>=".$curdateindianHM."  and user_id=".$studentinfo["user_id"]." limit 1";
    	$rotrip=mysqli_query($ms,$qtrip);
    	if($roetrip=mysqli_fetch_assoc($rotrip))
    	{
    		return $roetrip;
    	}
    	else 
    	{
    		return false;
    	}
    }
   
   function Track_Child($getpost)
   {
   	global $ms;
   	$result=array();
    $userdata=getTokenData($getpost);
   	$parentdata=getParent($userdata["parent_id"]);
    $qp="select uid id,user_id,route_id,route_id_down from dstudent  where uid in (select student_id from dstudent_sub where parent_type='P' and  contactno=".$parentdata["contactno"].") and status='Active' and uid =". decrypt($getpost["id"]);
   	$rdb=mysqli_query($ms,$qp);
   	if($rowp=mysqli_fetch_assoc($rdb))
	{
		
   		LiveTrack_Child($rowp);
    }
    else 
    {
    	
    	$qru="select parent_id id,parent_type type,parent_name name,emailid email,contactno phone,refer_user  from dstudent_sub where parent_type='S' and contactno=".$parentdata["contactno"];
   		$rru=mysqli_query($ms,$qru);
   		while($rowru=mysqli_fetch_assoc($rru))
   		{
   			$qp="select uid id,user_id,route_id,route_id_down from dstudent  where uid in
   			 (select student_id from dstudent_sub where parent_id=".$rowru["refer_user"].")
   			  and status='Active'  and uid =". decrypt($getpost["id"]." limit 1");
   			$rdb=mysqli_query($ms,$qp);
   			if ($rowp=mysqli_fetch_assoc($rdb))
   			{
   				LiveTrack_Child($rowp);
   				die;
	   		}
   		}
   		
    }
   
 
   	header('Content-type: application/json');
   	echo json_encode($result);
   	die;
   }
   	
   function Send_OTP($getpost)
   {
   	global $ms, $gsValues;
   	$qparent="select * from dstudent_sub where contactno='".$getpost["mobi"]."'";
   	$rparent = mysqli_query($ms,$qparent);
   	if ($rowparent = mysqli_fetch_assoc($rparent))
   	{
   		
   		$user_id=$rowparent["user_id"];
   		$contactno=$rowparent["contactno"];
   		if($contactno==$getpost["mobi"])
   		{
   			
   			$qtkn="select * from dstudent_sub_token where parent_id='".$rowparent["parent_id"]."' order by token_id desc limit 1 ";
   			$rtkn = mysqli_query($ms,$qtkn);
   			if ($rowtkn = mysqli_fetch_assoc($rtkn))
   			{
   				$cur_time=date("Y-m-d H:i:s");
   				$last_time=date('Y-m-d H:i:s',strtotime('+ 5 minutes',strtotime($rowtkn["otp_time"])));
   				//$last_time=$rowtkn["otp_time"];
   				if($last_time>$cur_time)
   				{
   					$result['tyv'] = 'e';
   					$result['msg'] = '/ Notification : Please try after 5 minutes!';
   					header('Content-type: application/json');
   					echo json_encode($result);
   					die;
   				}
   				else
   				{
   					/*
   					$result['tyv'] = 'e';
   					$result['msg'] =$user_id.'   '.$rowtkn["otp_time"]."    ". $last_time.'>'.$cur_time;
   					header('Content-type: application/json');
   					echo json_encode($result);
   					die;
   					*/
   				}
   			}
   			
   			$quser="select * from gs_users where id='".$user_id."'";
   			$ruser = mysqli_query($ms,$quser);
   			if ($rowuser = mysqli_fetch_assoc($ruser))
   			{
   				//$sms_gateway_url=$rowuser["sms_gateway_url"];
   				//if($rowuser['sms_gateway']==true && $sms_gateway_url!="")
   				{
   					$token=encrypt(random_string(10));
   					if($getpost["mobi"]=='9876543210'){
   						$otp='123456';
   					}else{
   						$otp=date("isd").rand(10,99);
   					}
   					$qotp="insert into dstudent_sub_token (parent_id,token,otp,otp_time,status,create_date) values('".$rowparent["parent_id"]."','".$token."','".$otp."','".date("Y-m-d H:i:s")."','D','".date("Y-m-d H:i:s")."')";
   					$rotp = mysqli_query($ms,$qotp);
   					$otp="Please verify your OTP : ".$otp." PAIZO";
   					

 $myfile = fopen("vvv.txt", "a");
      fwrite($myfile,json_encode($rowuser));
      fwrite($myfile, "\n");
      fclose($myfile);

   				if ($rowuser['sms_gateway'] == 'true')
				{
					if ($rowuser['sms_gateway_type'] == 'http')
					{
						$resultdel = sendSMSHTTP($rowuser['sms_gateway_url'], '', $contactno, $otp);	
            // $resultdel='http://sms.jjbulksms.com/api/v2/sms/send?access_token=1c47ef658ffc295ad5d78cb6b0ce844f&message='.$otp.'&sender=PLYGPS&to='.$contactno.'&service=T';            
						$result['tyv'] = 's';
   						$result['msg'] = 'OTP send successfully!';
   						$result['mydata'] =$token;
					}
					else
	   				{
   						$result['tyv'] = 'e';
   						$result['msg'] = 'Invalid SMS Gateway1!';
   					}
				}
				else
				{
					if (($rowuser['sms_gateway_server'] == 'true') && ($gsValues['SMS_GATEWAY'] == 'true'))
					{
						if ($gsValues['SMS_GATEWAY_TYPE'] == 'http')
						{
							$resultdel = sendSMSHTTP($gsValues['SMS_GATEWAY_URL'], $gsValues['SMS_GATEWAY_NUMBER_FILTER'], $contactno, $otp);
            // $resultdel='http://sms.jjbulksms.com/api/v2/sms/send?access_token=1c47ef658ffc295ad5d78cb6b0ce844f&message='.$otp.'&sender=PLYGPS&to='.$contactno.'&service=T';

							$result['tyv'] = 's';
   							$result['msg'] = 'OTP send successfully!';
   							$result['mydata'] =$token;	
						}
					}
					else
	   				{
   						$result['tyv'] = 'e';
   						$result['msg'] = 'Invalid SMS Gateway2!';
   					}
				}
   					
   					
   					
   				}
   				
   			}
   			else
   			{
   				$result['tyv'] = 'e';
   				$result['msg'] = 'Invalid User Details!';
   			}
   		}
   		else
   		{
   			$result['tyv'] = 'e';
   			$result['msg'] = 'Invalid Mobile Number!';
   		}
   	}
   	else
   	{
   		$result['tyv'] = 'e';
   		$result['msg'] = 'Invalid Mobile Number!';
   	}

   	header('Content-type: application/json');
   	echo json_encode($result);
   	die;
   }

   function Login($getpost)
   {
   	global $ms;
   	$userdata=getUserFromToekn($getpost["token"]);

   	if($userdata["otp"]== trim($getpost["OTP"]))
   	{
   		$qotp="update dstudent_sub_token set status='A' where parent_id='".$userdata["parent_id"]."' and otp='".trim($getpost["OTP"])."' ";
   		$rotp = mysqli_query($ms,$qotp);
   		if($rotp)
   		{
   			$result['tyv'] = 's';
   			$result['msg'] = 'Login success';
   			$result['mydata'] = array();
   		}
   	}
   	else
   	{
   		$result['tyv'] = 'e';
   		$result['msg'] = 'Please enter valid OTP!';
   		$result['mydata'] = array();
   	}
   	header('Content-type: application/json');
   	echo json_encode($result);
   	die;
   }
   
   function My_Profile($getpost)
   {
   	global $ms;
   	$userdata=getTokenData($getpost);
   	$parentdata=getParent($userdata["parent_id"]);
   	if(isset($parentdata))
   	{
   		$rtndata["name"]=$parentdata["parent_name"];
   		$rtndata["email"]=$parentdata["emailid"];
   		$rtndata["phone"]=$parentdata["contactno"];
   		$rtndata["type"]=$parentdata["parent_type"];
   		
   		$result['tyv'] = 's';
   		$result['msg'] = '';
   		$result['mydata'] =$rtndata;
   			
   	}
   	else
   	{
   		$result['tyv'] = 'e';
   		$result['msg'] = 'Invalid user details!';
   		$result['mydata'] = array();
   	}
   	header('Content-type: application/json');
   	echo json_encode($result);
   	die;
   }

   function Get_My_Child($getpost)
   {
   	global $ms;
    $userdata=getTokenData($getpost);
   	$parentdata=getParent($userdata["parent_id"]);
   		//adding child for primary parent 
   		$rtndatav=array();
   		$qp=" select ds.uid id,ds.name,ds.sid studentid,pickup_limit pickuplimit,drop_limit droplimit,
			  (select zone_vertices from gs_user_zones where zone_id=ds.zone_id ) pickeuploc,
			  (select zone_vertices from gs_user_zones where zone_id=ds.zone_id_down ) droploc
			  from dstudent ds  
			  where ds.uid in (select student_id from dstudent_sub where parent_type='P' and contactno=".$parentdata["contactno"].")";
   		$rdb=mysqli_query($ms,$qp);
   		while ($rowp=mysqli_fetch_assoc($rdb))
   		{
   			$rowp["pickeuploc"]=GetCenterFromLatLng($rowp["pickeuploc"]);
   			$rowp["droploc"]=GetCenterFromLatLng($rowp["droploc"]);
   			$rowp["id"]=encrypt($rowp["id"]);
   			$rtndatav[]=$rowp;
   		}
   	
   		//adding child for secondary parent 
   		$qru="select parent_id id,parent_type type,parent_name name,emailid email,contactno phone,refer_user  from dstudent_sub where parent_type='S' and contactno=".$parentdata["contactno"];
   		$rru=mysqli_query($ms,$qru);
   		while($rowru=mysqli_fetch_assoc($rru))
   		{
   			$qp=" select ds.uid id,ds.name,ds.sid studentid,pickup_limit pickuplimit,drop_limit droplimit,
				(select zone_vertices from gs_user_zones where zone_id=ds.zone_id ) pickeuploc,
				(select zone_vertices from gs_user_zones where zone_id=ds.zone_id_down ) droploc
				from dstudent ds  
				where ds.uid in (select student_id from dstudent_sub where parent_id=".$rowru["refer_user"].")";
   			$rdb=mysqli_query($ms,$qp);
   			while ($rowp=mysqli_fetch_assoc($rdb))
   			{
   				$rowp["pickeuploc"]=GetCenterFromLatLng($rowp["pickeuploc"]);
   				$rowp["droploc"]=GetCenterFromLatLng($rowp["droploc"]);
	   			$rowp["id"]=encrypt($rowp["id"]);
   				$rtndatav[]=$rowp;
	   		}
   		}
   		
   		
   		if(count($rtndatav)>0)
   		{
   			$result['tyv'] = 's';
   			$result['msg'] = '';
   			$result['mydata'] =$rtndatav; 
   		}
  		else 
   		{
   			$result['tyv'] = 'e';
   			$result['msg'] = 'Invalid parent details!';
   			$result['mydata'] = array();
   		}
   	
   		
   	header('Content-type: application/json');
   	echo json_encode($result);
   	die;
   	
   	if($parentdata["refer_user"]==0 && $parentdata["parent_type"]=="P")
   	{	
   		
   	}
   	else if($parentdata["refer_user"]!=0 && $parentdata["parent_type"]=="S")
   	{
   		$rtndatav=array();
   		
   		
   		
   		$result['tyv'] = 's';
   		$result['msg'] = '';
   		$result['mydata'] =$rtndatav;
   	}
   	else
   	{
   		$result['tyv'] = 'e';
   		$result['msg'] = 'Please contact admin!';
   		$result['mydata'] = array();
   	}

   }

   function Add_Parent($getpost)
   {
   	global $ms;
   	$userdata=getTokenData($getpost);
   	$parentdata=getParent($userdata["parent_id"]);
   	if($parentdata["refer_user"]==0 && $parentdata["parent_type"]=="P")
   	{
   		
   		$qchk="select parent_id from dstudent_sub where ( contactno='".$getpost["phone"]."' or emailid='".$getpost["email"]."') and refer_user='".$parentdata["parent_id"]."'";
   		$rchk=mysqli_query($ms,$qchk);
   		if(!$rowchk=mysqli_fetch_assoc($rchk))
   		{
   			$qsv = "INSERT INTO `dstudent_sub` (refer_user,student_id,user_id,parent_name,contactno,emailid,parent_type,create_date) VALUES
		         ('".$parentdata["parent_id"]."','0','".$parentdata["user_id"]."','".$getpost["name"]."','".$getpost["phone"]."','".$getpost["email"]."','S','".date("Y-m-d H:i:s")."')";
   			if(mysqli_query($ms,$qsv))
   			{
				$result['tyv'] = 's';
				$result['msg'] = 'Details updated successfully!';
				$result['mydata'] ="";
			}
			else
			{
				$result['tyv'] = 'e';
				$result['msg'] = 'Details updated failed!';
			}
   		}
  	 	else
		{
			$result['tyv'] = 'e';
			$result['msg'] = 'Details already entered!';
		}
   		
   	}
   	else
   	{
   		$result['tyv'] = 'e';
   		$result['msg'] = 'Invalid access!';
   		$result['mydata'] = array();
   	}
   	header('Content-type: application/json');
   	echo json_encode($result);
   	die;
   }

  function Create_SOSevent($getpost)
   {
   	global $ms;

   	$result=array();
   	$userdata=getTokenData($getpost);
   	$parentdata=getParent($userdata["parent_id"]);
   	$q2 = "SELECT * FROM `gs_user_events` WHERE `user_id`='".$parentdata['user_id']."' and type='dstudent_sos' order by notify_sms desc ";
   		

   	// echo $q2;
		$r2 = mysqli_query($ms, $q2);
		if(mysqli_num_rows($r2)>0)
		{
			while($ed = mysqli_fetch_array($r2))
			{			
				if ($ed['active'] == 'true')
				{
					$object_info=get_peopletravelingobject_details($userdata["parent_id"]);
					// if (get_userevent_status($ed['event_id'], $object_info['imei']) == -1)
					// {						
					// 	set_userevent_status($ed['event_id'], $object_info['imei'], '1');						
						// add event desc to event data array
						$ed['event_desc'] = $ed['name'];

						$q = "INSERT INTO `gs_user_events_data` (user_id,
										imei,
										event_desc,
										notify_system,
										notify_arrow,
										notify_arrow_color,
										notify_ohc,
										notify_ohc_color,
										dt_server,
										dt_tracker,
										lat,
										lng,
										altitude,
										speed,
										angle,
										params,
										type
										) VALUES (
										'".$ed['user_id']."',
										'".$object_info['imei']."',
										'People SOS Alert - ".$parentdata['parent_name']."',
										'".$ed['notify_system']."',
										'".$ed['notify_arrow']."',
										'".$ed['notify_arrow_color']."',
										'".$ed['notify_ohc']."',
										'".$ed['notify_ohc_color']."',
										'".gmdate("Y-m-d H:i:s")."',
										'".gmdate("Y-m-d H:i:s")."',
										'".$getpost['lat']."',
										'".$getpost['lng']."',
										'".$object_info['altitude']."','".$object_info['speed']."','".$object_info['angle']."','".$object_info['params']."',
										'".$ed["type"]."')";

						// $myfile = fopen("0_vetrivel_commander.txt", "a");
						// fwrite($myfile,$q);
						// fwrite($myfile, "\n");
						// fclose($myfile);

						$r = mysqli_query($ms, $q);
						$result['tyv'] = 's';
			   		$result['msg'] = 'Event Created!';
			   		$result['mydata'] = array();
					// }else
			  //  	{
			  //  		$result['tyv'] = 's';
			  //  		$result['msg'] = 'Event Already Created!';
			  //  		$result['mydata'] = array();
			  //  	}
				}
				else
		   	{
		   		$result['tyv'] = 'e';
		   		$result['msg'] = 'Inactive User!';
		   		$result['mydata'] = array();
		   	}
			}
		}else{
			$result['tyv'] = 's';
   		$result['msg'] = 'Event not Created Contack Admin!';
   		$result['mydata'] = array();
		}

   	header('Content-type: application/json');
   	echo json_encode($result);
   	die;
   }
   
   function Edit_Parent($getpost)
   {
   	global $ms;

   	$userdata=getTokenData($getpost);
   	$parentdata=getParent($userdata["parent_id"]);
   	$getpost["id"]=decrypt($getpost["id"]);
   	if(($parentdata["refer_user"]==0 && $parentdata["parent_type"]=="P") || $getpost["id"]==$userdata["parent_id"])
   	{
   		$qchk="select parent_id from dstudent_sub where ( contactno='".$getpost["phone"]."' or emailid='".$getpost["email"]."') and parent_id!='".$getpost["id"]."' and refer_user='".$parentdata["parent_id"]."'";
   		$rchk=mysqli_query($ms,$qchk);
   		if(!$rowchk=mysqli_fetch_assoc($rchk))
   		{   	
   			if($getpost["id"]==$userdata["parent_id"])		
   			$qsv = "update `dstudent_sub` set parent_name='".$getpost["name"]."',contactno='".$getpost["phone"]."',emailid='".$getpost["email"]."' where refer_user='".$parentdata["refer_user"]."' and user_id='".$parentdata["user_id"]."' and parent_id='".$getpost["id"]."' ";
   			else
   			$qsv = "update `dstudent_sub` set parent_name='".$getpost["name"]."',contactno='".$getpost["phone"]."',emailid='".$getpost["email"]."' where refer_user='".$parentdata["parent_id"]."' and user_id='".$parentdata["user_id"]."' and parent_id='".$getpost["id"]."' ";
   			if(mysqli_query($ms,$qsv))
   			{
				$result['tyv'] = 's';
				$result['msg'] = 'Details updated successfully!';
				$result['mydata'] ="";
			}
			else
			{
				$result['tyv'] = 'e';
				$result['msg'] = 'Details updated failed!';
			}
   		}
  	 	else
		{
			$result['tyv'] = 'e';
			$result['msg'] = 'Details already entered!';
		}
   	}
   	else
   	{
   		$result['tyv'] = 'e';
   		$result['msg'] = 'Invalid access!';
   		$result['mydata'] = array();
   	}
   	header('Content-type: application/json');
   	echo json_encode($result);
   	die;
   }
      
   function Delete_Parent($getpost)
   {
   	global $ms;
   
   	$userdata=getTokenData($getpost);
   	$parentdata=getParent($userdata["parent_id"]);
   	$getpost["id"]=decrypt($getpost["id"]);
   	if(($parentdata["refer_user"]==0 && $parentdata["parent_type"]=="P") && $parentdata["parent_id"]==$userdata["parent_id"])
   	{   		 
   			$qsv = "delete from `dstudent_sub` where refer_user='".$parentdata["parent_id"]."' and user_id='".$parentdata["user_id"]."' and parent_id='".$getpost["id"]."' and parent_type='S'";
   			if(mysqli_query($ms,$qsv))
   			{
				$result['tyv'] = 's';
				$result['msg'] = 'Details updated successfully!';
				$result['mydata'] ="";
			}
			else
			{
				$result['tyv'] = 'e';
				$result['msg'] = 'Details updated failed!';
			}
   	}
   	else
   	{
   		$result['tyv'] = 'e';
   		$result['msg'] = 'Invalid access!';
   		$result['mydata'] = array();
   	}
   	header('Content-type: application/json');
   	echo json_encode($result);
   	die;
   }
        
   function List_Parent($getpost)
   {
   	global $ms;
   	$userdata=getTokenData($getpost);
   	$parentdata=getParent($userdata["parent_id"]);
   	if($parentdata["refer_user"]==0 && $parentdata["parent_type"]=="P")
   	{	
   		$rtndatav=array();
   		$rtndata["id"]=encrypt($parentdata["parent_id"]);
   		$rtndata["type"]=$parentdata["parent_type"];
   		$rtndata["name"]=$parentdata["parent_name"];
   		$rtndata["email"]=$parentdata["emailid"];
   		$rtndata["phone"]=$parentdata["contactno"];
   		$rtndatav[]=$rtndata;
   		$qp="select parent_id id,parent_type type,parent_name name,emailid email,contactno phone from dstudent_sub where refer_user=".$parentdata["parent_id"];
   		$rdb=mysqli_query($ms,$qp);
   		while ($rowp=mysqli_fetch_assoc($rdb))
   		{
   			$rowp["id"]=encrypt($rowp["id"]);
   			$rtndatav[]=$rowp;
   		}
   		$result['tyv'] = 's';
   		$result['msg'] = '';
   		$result['mydata'] =$rtndatav;
   		 
   		 
   	}
   	else if($parentdata["refer_user"]!=0 && $parentdata["parent_type"]=="S")
   	{
   		$rtndatav=array();
   		$rtndata["id"]=encrypt($parentdata["parent_id"]);
   		$rtndata["type"]=$parentdata["parent_type"];
   		$rtndata["name"]=$parentdata["parent_name"];
   		$rtndata["email"]=$parentdata["emailid"];
   		$rtndata["phone"]=$parentdata["contactno"];
   		$rtndatav[]=$rtndata;
   		$result['tyv'] = 's';
   		$result['msg'] = '';
   		$result['mydata'] = $rtndatav;
   	}
   	else
   	{
   		$result['tyv'] = 'e';
   		$result['msg'] = 'Please contact admin!';
   		$result['mydata'] = array();
   	}
   	header('Content-type: application/json');
   	echo json_encode($result);
   	die;
   }
    
   function Set_Zone_Limit($getpost)
   {
   	global $ms;
   	$userdata=getTokenData($getpost);
   	$parentdata=getParent($userdata["parent_id"]);
   	if(isset($parentdata))
   	{
   		//need to verify is theri child's data

   		$stud_id=decrypt($getpost["id"]);
   		$qp="select uid from dstudent where uid=".$stud_id;
   		$rdb=mysqli_query($ms,$qp);
   		if($rowp=mysqli_fetch_assoc($rdb))
   		{
   			if($rowp["uid"]!=$stud_id)//secondary verify, not necessary :P bored now so write ths
   			{
   				$result['tyv'] = 'e';
   				$result['msg'] = 'Invalid data,please contact admin!';
   				$result['mydata'] = array();
   			}
   			
   			$clo="";
   			if($getpost["limit_type"]=="P")
   			{
	   			$clo="pickup_limit";
   			}
   			else if($getpost["limit_type"]=="D")
   			{
	   			$clo="drop_limit";
   			}

   			$ql="update dstudent set ".$clo."=".$getpost["limit_value"]." where uid=".$stud_id;
   			if(mysqli_query($ms,$ql))
   			{
	   			$result['tyv'] = 's';
   				$result['msg'] = 'Details updated successfully!';
   				$result['mydata'] = array();
   			}
   			else
   			{
	   			$result['tyv'] = 'e';
   				$result['msg'] = 'Details updated failed!';
   				$result['mydata'] = array();
   			}
   		}
   		else
   		{
	   		$result['tyv'] = 'e';
   			$result['msg'] = 'Invalid data,please contact admin!';
   			$result['mydata'] = array();
   		}
   	}
   	else
   	{
   		$result['tyv'] = 'e';
   		$result['msg'] = 'Invalid user details!';
   		$result['mydata'] = array();
   	}
   	header('Content-type: application/json');
   	echo json_encode($result);
   	die;
   }
     
   function getParent($parent_id)
 	{
 		global $ms;
 		//$getpost["id"]=decrypt($getpost["id"]);
 			$q = "select parent_id,user_id,student_id,parent_name,emailid,contactno,parent_type,refer_user from dstudent_sub WHERE  parent_id='".$parent_id."' limit 1 " ;
 			$r = mysqli_query($ms,$q);
 			if ($rowuser = mysqli_fetch_assoc($r))
 			{
 				return 	$rowuser;
 			}
 			else
 			{
 				$result['tyv'] = 'e';
 				$result['msg'] = 'Invalid parent details!';
 				header('Content-type: application/json');
 				echo json_encode($result);
 				die;
 			}
 		
 	}
      
   function getUserFromToekn($token)
 	{
 		global $ms;
 	
 			$q = "select parent_id,token,status,otp,forget from dstudent_sub_token WHERE  token='".$token."' limit 1 " ;
 			$r = mysqli_query($ms,$q);
 			if ($rowuser = mysqli_fetch_assoc($r))
 			{
 				return 	$rowuser;
 			}
 			else
 			{
 				$result['tyv'] = 'e';
 				$result['msg'] = 'Invalid user details.!';
 				header('Content-type: application/json');
 				echo json_encode($result);
 				die;
 			}
 		
 	}

   function getTokenData($getpost)
   {
 		$userdata=getUserFromToekn($getpost["token"]);
  	 	if($userdata["status"]=='A')
	   	{
	   		return $userdata;
   		}
   		else
   		{
	   		$result['tyv'] = 'e';
   			$result['msg'] = 'Please verify OTP!';
   			$result['mydata'] = array();
   		}
   		
   		header('Content-type: application/json');
   		echo json_encode($result);
   		die;
 	}
 		
   function random_string($length) {
    $key = '';
    $keys = array_merge(range(0, 9), range('a', 'z'));

    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
    }

    return $key;
}

   function Feedback($getpost)
   {
   	global $ms;
   	$userdata=getTokenData($getpost);
   	$parentdata=getParent($userdata["parent_id"]);
   	if($parentdata["refer_user"]==0 && $parentdata["parent_type"]=="P")
   	{
   	
   			$qsv = "INSERT INTO zathera_feedback (user_id,parent_id,create_date,feedback) VALUES
		         ('".$parentdata["user_id"]."','".$parentdata["parent_id"]."','".date("Y-m-d H:i:s")."','".$getpost["feedback"]."')";
   			if(mysqli_query($ms,$qsv))
   			{
				$result['tyv'] = 's';
				$result['msg'] = 'Details updated successfully!';
				$result['mydata'] ="";
			}
			else
			{
				$result['tyv'] = 'e';
				$result['msg'] = 'Details updated failed!';
			}
   		
   	}
   	else
   	{
   		$result['tyv'] = 'e';
   		$result['msg'] = 'Invalid access!';
   		$result['mydata'] = array();
   	}
   	header('Content-type: application/json');
   	echo json_encode($result);
   	die;
   }
   
   function Logout($getpost)
   {
   	global $ms;
   	 
   	$q = "delete from dstudent_sub_token WHERE  token='".$getpost["token"]."' " ;
 	$r = mysqli_query($ms,$q);
	
   	$result['tyv'] = 's';
   	$result['msg'] = 'Details updated successfully!';
   	$result['mydata'] = array();

   	header('Content-type: application/json');
   	echo json_encode($result);
   	die;
   }
  
   function rtnoutput($data)
   {
   	header('Content-type: application/json');
   	echo json_encode($result);
   	die;
   }
   
   
   function GetCenterFromLatLng($data)
   {
   	
	$ver_arr = explode(',', $data);
		
    if(!is_int(count($ver_arr)/2))
    {
         array_pop($ver_arr);
    }
    

    $data=array();   
	 for ($j = 0; $j <count($ver_arr); $j += 2) {
        $lat = $ver_arr[$j];
        $lng = $ver_arr[$j + 1];
      $data[]=array($lat, $lng);
    }
    
    if (!is_array($data)) return FALSE;

    //print_r ($data);
    
      $lats = $lons = array();
    foreach ($data as $key => $value) {
        array_push($lats, $value[0]);
        array_push($lons, $value[1]);
    }
    
    //print_r ($lats);
    //print_r ($lons);
    
    $minlat = round(min($lats),6);
    $maxlat = round(max($lats),6);
    $minlon = round(min($lons),6);
    $maxlon = round(max($lons),6);
    $lat = $maxlat - (($maxlat - $minlat) / 2);
    $lng = $maxlon - (($maxlon - $minlon) / 1.9);
    return array("lat" => $lat, "lon" => $lng);
    
   }

function get_userevent_status($event_id, $imei)
{
	global $ms;

	$result = '-1';

	$q = "SELECT * FROM `gs_user_events_status` WHERE `event_id`='".$event_id."' AND `imei`='".$imei."'";
	$r = mysqli_query($ms, $q);
	$row = mysqli_fetch_array($r);
	if ($row)
	{
		$result = $row['event_status'];
	}
	else
	{
		$q = "INSERT INTO `gs_user_events_status` (`event_id`,`imei`,`event_status`) VALUES ('".$event_id."','".$imei."','-1')";
		$r = mysqli_query($ms, $q);
	}

	return $result;
}

function set_userevent_status($event_id, $imei, $value)
{
	global $ms;

	$q = "UPDATE `gs_user_events_status` SET `event_status`='".$value."',`dt_server`='".gmdate("Y-m-d H:i:s")."' WHERE `event_id`='".$event_id."' AND `imei`='".$imei."'";
	$r = mysqli_query($ms, $q);
}

function get_peopletravelingobject_details($pid){
	global $ms;
	$q="SELECT a.imei,a.active,a.object_expire,a.object_expire_dt,a.dt_server,a.dt_tracker,a.altitude,a.angle,a.speed,a.params FROM gs_objects a LEFT JOIN gs_user_objects b ON a.imei=b.imei LEFT JOIN dstudent c ON c.last_swipe_vehicle=a.imei LEFT JOIN dstudent_sub d ON d.student_id=c.uid WHERE d.parent_id='".$pid."' LIMIT 1";
	// $q="SELECT a.imei,a.active,a.object_expire,a.object_expire_dt,a.dt_server,a.dt_tracker,a.altitude,a.angle,a.speed,a.params FROM gs_objects a LEFT JOIN gs_user_objects b ON a.imei=b.imei LEFT JOIN dstudent c ON c.last_swipe_vehicle=a.imei LEFT JOIN dstudent_sub d ON d.student_id=c.uid WHERE d.parent_id='".$pid."' and c.last_swipe_time >= DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 HOUR), '%Y-%m-%d %H:00:00') LIMIT 1";

	$r=mysqli_query($ms,$q);
	if(mysqli_num_rows($r)>0){
		while($row=mysqli_fetch_assoc($r)){
			if($row['active']=='true'){
				if($row['object_expire']=='true'){
					return $row;
				}else{
					$result['tyv'] = 's';
			   		$result['msg'] = 'Swiped Vehicle Invalid, Please Contact admin!';
			   		$result['mydata'] = array();
			   		header('Content-type: application/json');
				   	echo json_encode($result);
				   	die;
				}
			}else{
				$result['tyv'] = 's';
		   		$result['msg'] = 'Swiped Vehicle Invalid, Please Contact admin!';
		   		$result['mydata'] = array();
		   		header('Content-type: application/json');
			   	echo json_encode($result);
			   	die;
			}
		}
	}else{
		$result['tyv'] = 's';
   		$result['msg'] = 'Swiped Vehicle Invalid, Please Contact admin!';
   		$result['mydata'] = array();
   		header('Content-type: application/json');
	   	echo json_encode($result);
	   	die;
	}
}

?>
