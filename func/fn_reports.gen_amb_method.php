<?php


	
	function getGenerateGeneral($dtf,$dtt,$emergency,$address,$peoplecount ,$phone ,
	$patientname ,$age_from ,$age_to ,$gender ,$breath ,$conscious ,$book_status ,$crewtime ,$taken,$book_by)
	{
		global $ms,$_SESSION;
		$user_id="";
		if ($_SESSION["privileges"] == 'subuser')
		{
			$user_id = $_SESSION["manager_id"];
		}
		else
		{
			$user_id = $_SESSION["user_id"];
		}
		
		$route = array();
		
		$taken=$taken*3600;
		
		$yesterday = gmdate("Y-m-d H:m:s", time()-86400);
	
		$user_id="";
		if ($_SESSION["privileges"] == 'subuser')
		{
			$user_id = $_SESSION["manager_id"];
		}
		else
		{
			$user_id = $_SESSION["user_id"];
		}
		
		$booking_id= "";

		if($address!="")
		$address=" and ah.address like '%".$address."%' ";
		
		if($peoplecount!="")
		$peoplecount=" and ah.people_count='".$peoplecount."' ";
		
		if($phone!="")
		$phone=" and ah.contact_no like '%".$phone."%'";
		
		if($patientname!="")
		$patientname=" and ah.person_name like '%".$patientname."%' ";
		
	
		if($gender!="All")
		$gender=" and ah.gender='".$gender."' ";
		else $gender=""; 
		
		if($breath!="All")
		$breath=" and ah.breathing='".$breath."' ";
		else $breath="";
		
		if($conscious!="All")
		$conscious=" and ah.conscious='".$conscious."' ";
		else $conscious="";
		
		if($book_status!="All")
		$book_status=" and ah.booking_status='".$book_status."' ";
		else $book_status="";
		
		if($book_by!="All")
		$book_by=" and ah.book_by='".$book_by."' ";
		else $book_by="";
		
		
		$q = "SELECT DISTINCT ah.create_date,ah.book_by,ah.emergency_address,ah.contact_no,ah.emergency_reason,ah.people_count,ah.age,ah.conscious,ah.breathing,ah.gender,ah.person_name,ah.note1,ah.booking_status,ah.app_user_id,ah.self_others,ah.allocated_driver_id,
   		 ah.allocated_imei,ah.allocated_driver_phone,ah.allocated_vehicleno,ah.allocated_time,ah.driver_accept_time,ah.vehicle_reached_time,ah.pickedup_time,ah.reached_dest_time
    	 ,gs.username allocateddriver,ah.lat,ah.lng,concat(ahu.name,'-',ahu.mobileno) appuser FROM `ah_booking` ah left join gs_users gs on ah.allocated_driver_id = gs.id
    	  left join ah_user ahu on ahu.userid=ah.app_user_id
    	 where user_id=".$user_id." and ( ah.create_date between '".$dtf."' and '".$dtt."')
    	 and ( ah.age between '".$age_from."' and '".$age_to."'  )
		 ".$address.$peoplecount.$phone.$patientname. $gender.$breath.$conscious.$book_status.$book_by."
    	 order by ah.create_date asc";
		$r = mysqli_query($ms,$q);
		
		while($route_data=mysqli_fetch_array($r,MYSQLI_ASSOC))
		{
			// $control_room_id= $route_data['control_room_id'];
			$create_date = $route_data['create_date'];
			$book_by = $route_data['book_by'];
			$emergency_address = $route_data['emergency_address'];
			$contact_no = $route_data['contact_no'];
			$emergency_reason = $route_data['emergency_reason'];
			$people_count = $route_data['people_count'];
			$age = $route_data['age'];
			$conscious = $route_data['conscious'];
			$breathing = $route_data['breathing'];
			$gender = $route_data['gender'];
			$person_name = $route_data['person_name'];
			$note1 = $route_data['note1'];
			$booking_status = $route_data['booking_status'];
			$app_user_id = $route_data['appuser'];
			$self_others = $route_data['self_others'];
			$allocated_driver_id = $route_data['allocateddriver'];
			$allocated_driver_phone = $route_data['allocated_driver_phone'];
			$allocated_vehicleno = $route_data['allocated_vehicleno'];
			$allocated_time = $route_data['allocated_time'];
			$driver_accept_time = $route_data['driver_accept_time'];
			$vehicle_reached_time = $route_data['vehicle_reached_time'];
			$pickedup_time = $route_data['pickedup_time'];
			$reached_dest_time = $route_data['reached_dest_time'];
			$lat = $route_data['lat'];
			$lng = $route_data['lng'];

			$crew_acceptance=null;
			$crew_response=null;
			$crew_onscene=null;
			$crew_ontransport=null;
			$crew_turnaround=null;
			$crew_total=null;
			$crew_time_mth=null;
			
			if(isset($allocated_time) && isset($driver_accept_time))
			$crew_acceptance=strtotime($driver_accept_time)-strtotime($allocated_time);
			
			if(isset($driver_accept_time) && isset($vehicle_reached_time))
			$crew_response=strtotime($vehicle_reached_time)-strtotime($driver_accept_time);
			
			if(isset($vehicle_reached_time) && isset($pickedup_time))
			$crew_onscene=strtotime($pickedup_time)-strtotime($vehicle_reached_time);
			
			if(isset($pickedup_time) && isset($reached_dest_time))
			$crew_ontransport=strtotime($reached_dest_time)-strtotime($pickedup_time);
			
			//if(isset($driver_accept_time) && isset($vehicle_reached_time))
			//$crew_turnaround=strtotime($vehicle_reached_time)-strtotime($driver_accept_time);
			
			if(isset($allocated_time) && isset($reached_dest_time))
			$crew_total=strtotime($reached_dest_time)-strtotime($allocated_time);
			$crew_time_mth=checkcrew($crewtime,$taken,$crew_acceptance,$crew_response,$crew_onscene,$crew_ontransport,$crew_total);

				if ($imei =!0 && $crew_time_mth==true)
				{
					$route[] = array(
							// $control_room_id,
							"create_date"=>$create_date,
							"book_by"=>$book_by,
							"emergency_address"=>$emergency_address,
							"contact_no"=>$contact_no ,
							"emergency_reason"=>$emergency_reason ,
							"people_count"=>$people_count ,
							"age"=>$age,
							"conscious"=>$conscious,
							"breathing"=>$breathing,
							"gender"=>$gender,
							"person_name"=>$person_name,
							"note1"=>$note1,
							"booking_status"=>$booking_status,
							"app_user_id"=>$app_user_id,
							"self_others"=>$self_others,
							"allocated_driver_id"=>$allocated_driver_id,
							"allocated_driver_phone"=>$allocated_driver_phone,
							"allocated_vehicleno"=>$allocated_vehicleno,
							"allocated_time"=>$allocated_time,
							"driver_accept_time"=>$driver_accept_time,
							"vehicle_reached_time"=>$vehicle_reached_time,
							"pickedup_time"=>$pickedup_time,
							"reached_dest_time"=>$reached_dest_time,
							"lat"=>$lat,
							"lng"=>$lng,
							"crew_acceptance"=>$crew_acceptance,
							"crew_response"=>$crew_response,
							"crew_onscene"=>$crew_onscene,
							"crew_ontransport"=>$crew_ontransport,
							//"crew_turnaround"=>$crew_turnaround,
							"crew_total"=>$crew_total
							);
							
							/*

							 "crew_acceptance"=>getTimeDetails($crew_acceptance, true),
							"crew_response"=>getTimeDetails($crew_response, true),
							"crew_onscene"=>getTimeDetails($crew_onscene, true),
							"crew_ontransport"=>getTimeDetails($crew_ontransport, true),
							//"crew_turnaround"=>$crew_turnaround,
							"crew_total"=>getTimeDetails($crew_total, true)
							 */
				}
			}
					
		return $route;
	}
	
	function checkcrew($crewtime,$taken,$crew_acceptance,$crew_response,$crew_onscene,$crew_ontransport,$crew_total)
	{
		$rtnstatus=false;
		switch ($crewtime) {
			case "Acceptance":
					if(isset($crew_acceptance) && $taken>=$crew_acceptance )
					$rtnstatus=true;
			break;
			case "Response":
					if(isset($crew_response) && $taken>=$crew_response )
					$rtnstatus=true;
			break;
			case "On scene":
					if(isset($crew_onscene) && $taken>=$crew_onscene )
					$rtnstatus=true;
			break;
			case "Transport":
					if(isset($crew_ontransport) && $taken>=$crew_ontransport)
					$rtnstatus=true;
			break;
			case "Total":
					if(isset($crew_total) && $taken>=$crew_total)
					$rtnstatus=true;
			break;
			case "All":
					$rtnstatus=true;
			break;
			default:
				$rtnstatus=false;
			break;
		}
		return $rtnstatus;
	}
	


?>