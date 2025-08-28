<?php
	function reportsGenerateLoopAmb($type,$dtf, $dtt,$emergency,$address,$peoplecount ,$phone ,$patientname ,$age_from ,$age_to ,$gender ,$breath ,$conscious ,$book_status ,$crewtime ,$taken,$book_by)
	{
		global $la;	
		$result = '<div id="myModal" class="modal">
  					<div class="modal-content">
    				<span class="close" onclick="closeMap()">&times;</span>
   					<div id="map_canvas" style=" margin: 0; padding: 0; height:300px"></div>
  					</div>
				  </div>';	
		
		if ($type == "ambulance_general") //GENERAL_INFO_MERGED
		{
			$result .= '<h3>'.$la['AMBULANCE_RPT_GENERAL'].'</h3>';
			$result .= reportsAddReportHeader('', $dtf, $dtt);
			$result .= reportsGenerateGeneral(convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $emergency,$address,$peoplecount ,$phone ,$patientname ,$age_from ,$age_to ,$gender ,$breath ,$conscious ,$book_status ,$crewtime ,$taken,$book_by);
			$result .= '<br/><hr/>';
		}
		else if ($type == "ambulance_emergency") //GENERAL_INFO_MERGED
		{
			$result .= '<h3>'.$la['AMBULANCE_RPT_EMERGENCY'].'</h3>';
			$result .= reportsAddReportHeader('', $dtf, $dtt);
			$result .= reportsGenerateEmergency(convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $emergency,$address,$peoplecount ,$phone ,$patientname ,$age_from ,$age_to ,$gender ,$breath ,$conscious ,$book_status ,$crewtime ,$taken,$book_by);
			$result .= '<br/><hr/>';
		}
	
		
		return $result;
	}

		
	function reportsGenerateGeneral($dtf, $dtt,$emergency,$address,$peoplecount ,$phone ,$patientname ,$age_from ,$age_to ,$gender ,$breath ,$conscious ,$book_status ,$crewtime ,$taken,$book_by)
	{
		global $_SESSION, $la, $user_id;
				
		$route = getGenerateGeneral($dtf,$dtt,$emergency,$address,$peoplecount ,$phone ,$patientname ,$age_from ,$age_to ,$gender ,$breath ,$conscious ,$book_status ,$crewtime ,$taken,$book_by);
		
		if ((count($route) == 0) )
		{
			return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
	
		
		
		$result = '<table class="report" width="100%" ><tr align="center">';
		$result .='<th>'.$la['SINO'].'</th>';
		$result .='<th>'.$la['CREATE_DATE'].'</th>';
		$result .='<th>'.$la['BOOKING_STATUS'].'</th>';
		$result .='<th>'.$la['APP_USERID'].'</th>';
		//$result .='<th>'.$la['SELF_OTHERS'].'</th>';
		$result .='<th>'.$la['BOOK_BY'].'</th>';
		$result .='<th>'.$la['EMERGENCY_ADDRESS'].'</th>';
		$result .='<th>'.$la['CONTACT_NO'].'</th>';
		$result .='<th>'.$la['EMERGENCY_REASON'].'</th>';
		$result .='<th>'.$la['PEOPLE_COUNT'].'</th>';
		$result .='<th>'.$la['AGE'].'</th>';
		$result .='<th>'.$la['CONSCIOUS'].'</th>';
		$result .='<th>'.$la['BREATHING'].'</th>';
		$result .='<th>'.$la['GENDER'].'</th>';
		$result .='<th>'.$la['PERSON_NAME'].'</th>';
		$result .='<th>'.$la['NOTE1'].'</th>';
		$result .='<th>'.$la['ALLOCATED_DRIVER_ID'].'</th>';
		$result .='<th>'.$la['ALLOCATED_DRIVER_PHONE'].'</th>';
		$result .='<th>'.$la['ALLOCATED_VEHICLE_NO'].'</th>';
		$result .='<th>'.$la['ALLOCATED_TIME'].'</th>';
		$result .='<th>'.$la['DRIVER_ACCEPT_TIME'].'</th>';
		$result .='<th>'.$la['VEHICLE_REACHED_TIME'].'</th>';
		$result .='<th>'.$la['PICKEDUP_TIME'].'</th>';
		$result .='<th>'.$la['REACHED_DEST_TIME'].'</th>';
		
		$result .='<th>'.$la['ACCEPTANCE'].'</th>';
		$result .='<th>'.$la['RESPONSE'].'</th>';
		$result .='<th>'.$la['ON_SCENE'].'</th>';
		$result .='<th>'.$la['TRANSPORT'].'</th>';
		$result .='<th>'.$la['TOTAL'].'</th>';
		
		$result .='	</tr>';


		for ($i=0; $i<count($route); ++$i)
		{
			$result .= '<tr align="center">';
			$result .= '<td>'.($i+1).'</td>';
			$result .= '<td>'.$route[$i]["create_date"].'</td>';
			$result .= '<td>'.changeStatuslbl($route[$i]["booking_status"]).'</td>';
			$result .= '<td style="color:#126292">'.$route[$i]["app_user_id"].'</td>';
			//$result .= '<td>'.$route[$i]["self_others"].'</td>';
			$result .= '<td>'.$route[$i]["book_by"].'</td>';
			$result .= '<td style="cursor:pointer;color:#0e19ad" onclick="createMarker('.$route[$i]["lat"].','.$route[$i]["lng"].')">'.$route[$i]["emergency_address"].'</td>';
			$result .= '<td>'.$route[$i]["contact_no"].'</td>';
			$result .= '<td>'.$route[$i]["emergency_reason"].'</td>';
			$result .= '<td>'.$route[$i]["people_count"].'</td>';
			$result .= '<td>'.$route[$i]["age"].'</td>';
			$result .= '<td>'.$route[$i]["conscious"].'</td>';
			$result .= '<td>'.$route[$i]["breathing"].'</td>';
			$result .= '<td>'.$route[$i]["gender"].'</td>';
			$result .= '<td>'.$route[$i]["person_name"].'</td>';
			$result .= '<td>'.$route[$i]["note1"].'</td>';
			$result .= '<td>'.$route[$i]["allocated_driver_id"].'</td>';
			$result .= '<td>'.$route[$i]["allocated_driver_phone"].'</td>';
			$result .= '<td>'.$route[$i]["allocated_vehicleno"].'</td>';
			$result .= '<td>'.$route[$i]["allocated_time"].'</td>';
			$result .= '<td>'.$route[$i]["driver_accept_time"].'</td>';
			$result .= '<td>'.$route[$i]["vehicle_reached_time"].'</td>';
			$result .= '<td>'.$route[$i]["pickedup_time"].'</td>';
			$result .= '<td>'.$route[$i]["reached_dest_time"].'</td>';
			
			$result .= '<td>'.getTimeDetails($route[$i]["crew_acceptance"],true).'</td>';
			$result .= '<td>'.getTimeDetails($route[$i]["crew_response"],true).'</td>';
			$result .= '<td>'.getTimeDetails($route[$i]["crew_onscene"],true).'</td>';
			$result .= '<td>'.getTimeDetails($route[$i]["crew_ontransport"],true).'</td>';
			
			if($route[$i]["crew_total"]<=86400)
			$result .= '<td>'.getTimeDetails($route[$i]["crew_total"],true).'</td>';
			else 
			$result .= '<td style="background:red;color:white;" title="Trip Takes More than 24 hrs to Finish">'.getTimeDetails($route[$i]["crew_total"],true).'</td>';
			
			$result .= '</tr>';
		}

		$result .= '</table>';
		return $result;
		
	}
	
		
	function reportsGenerateEmergency($dtf, $dtt,$emergency,$address,$peoplecount ,$phone ,$patientname ,$age_from ,$age_to ,$gender ,$breath ,$conscious ,$book_status ,$crewtime ,$taken,$book_by)
	{
		global $_SESSION, $la, $user_id;
				
		$route = getGenerateGeneral($dtf,$dtt,$emergency,$address,$peoplecount ,$phone ,$patientname ,$age_from ,$age_to ,$gender ,$breath ,$conscious ,$book_status ,$crewtime ,$taken,$book_by);
		
		if ((count($route) == 0) )
		{
			return '<table><tr><td>'.$la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'].'</td></tr></table>';
		}
		$result='';
		$updated=array();
		for ($i=0; $i<count($route); ++$i)
		{
			if($route[$i]["emergency_reason"]=="" || $route[$i]["emergency_reason"]=="Select" )
			$emer="Not Entered";
			else
			$emer=$route[$i]["emergency_reason"];
			
			if(!isset($updated["emergency"]))
			{
				$updated[$emer][]= $route[$i];
			}
			else
			{
				$updated[$emer][]= $route[$i];
			}
		}
		
	foreach ($updated as $key => $value) {
  		$result .= '<h3> '.$la["EMERGENCY_REASON"]."  :  " .$key.'</h3>';
  		
  		$result .= '<table class="report" width="100%" ><tr align="center">';
		$result .='<th>'.$la['SINO'].'</th>';
		$result .='<th>'.$la['CREATE_DATE'].'</th>';
		$result .='<th>'.$la['BOOKING_STATUS'].'</th>';
		$result .='<th>'.$la['APP_USERID'].'</th>';
		//$result .='<th>'.$la['SELF_OTHERS'].'</th>';
		$result .='<th>'.$la['BOOK_BY'].'</th>';
		$result .='<th>'.$la['EMERGENCY_ADDRESS'].'</th>';
		$result .='<th>'.$la['CONTACT_NO'].'</th>';
		$result .='<th>'.$la['PEOPLE_COUNT'].'</th>';
		$result .='<th>'.$la['AGE'].'</th>';
		$result .='<th>'.$la['CONSCIOUS'].'</th>';
		$result .='<th>'.$la['BREATHING'].'</th>';
		$result .='<th>'.$la['GENDER'].'</th>';
		$result .='<th>'.$la['PERSON_NAME'].'</th>';
		$result .='<th>'.$la['NOTE1'].'</th>';
		$result .='<th>'.$la['ALLOCATED_DRIVER_ID'].'</th>';
		$result .='<th>'.$la['ALLOCATED_DRIVER_PHONE'].'</th>';
		$result .='<th>'.$la['ALLOCATED_VEHICLE_NO'].'</th>';
		$result .='<th>'.$la['ALLOCATED_TIME'].'</th>';
		$result .='<th>'.$la['DRIVER_ACCEPT_TIME'].'</th>';
		$result .='<th>'.$la['VEHICLE_REACHED_TIME'].'</th>';
		$result .='<th>'.$la['PICKEDUP_TIME'].'</th>';
		$result .='<th>'.$la['REACHED_DEST_TIME'].'</th>';	
		
		$result .='<th>'.$la['ACCEPTANCE'].'</th>';
		$result .='<th>'.$la['RESPONSE'].'</th>';
		$result .='<th>'.$la['ON_SCENE'].'</th>';
		$result .='<th>'.$la['TRANSPORT'].'</th>';
		$result .='<th>'.$la['TOTAL'].'</th>';	
		
		$result .='	</tr>';
  		for ($i=0; $i<count($value); ++$i)
		{
			$result .= '<tr align="center">';
			$result .= '<td>'.($i+1).'</td>';
			$result .= '<td>'.$value[$i]["create_date"].'</td>';
			$result .= '<td>'.changeStatuslbl($value[$i]["booking_status"]).'</td>';
			$result .= '<td style="color:#126292">'.$value[$i]["app_user_id"].'</td>';
			//$result .= '<td>'.$value[$i]["self_others"].'</td>';
			$result .= '<td>'.$value[$i]["book_by"].'</td>';
			$result .= '<td style="cursor:pointer;color:#0e19ad" onclick="createMarker('.$route[$i]["lat"].','.$route[$i]["lng"].')">'.$value[$i]["emergency_address"].'</td>';
			$result .= '<td>'.$value[$i]["contact_no"].'</td>';
			$result .= '<td>'.$value[$i]["people_count"].'</td>';
			$result .= '<td>'.$value[$i]["age"].'</td>';
			$result .= '<td>'.$value[$i]["conscious"].'</td>';
			$result .= '<td>'.$value[$i]["breathing"].'</td>';
			$result .= '<td>'.$value[$i]["gender"].'</td>';
			$result .= '<td>'.$value[$i]["person_name"].'</td>';
			$result .= '<td>'.$value[$i]["note1"].'</td>';
			$result .= '<td>'.$value[$i]["allocated_driver_id"].'</td>';
			$result .= '<td>'.$value[$i]["allocated_driver_phone"].'</td>';
			$result .= '<td>'.$value[$i]["allocated_vehicleno"].'</td>';
			$result .= '<td>'.$value[$i]["allocated_time"].'</td>';
			$result .= '<td>'.$value[$i]["driver_accept_time"].'</td>';
			$result .= '<td>'.$value[$i]["vehicle_reached_time"].'</td>';
			$result .= '<td>'.$value[$i]["pickedup_time"].'</td>';
			$result .= '<td>'.$value[$i]["reached_dest_time"].'</td>';
			
			$result .= '<td>'.getTimeDetails($value[$i]["crew_acceptance"],true).'</td>';
			$result .= '<td>'.getTimeDetails($value[$i]["crew_response"],true).'</td>';
			$result .= '<td>'.getTimeDetails($value[$i]["crew_onscene"],true).'</td>';
			$result .= '<td>'.getTimeDetails($value[$i]["crew_ontransport"],true).'</td>';
			
			if($value[$i]["crew_total"]<=86400)
			$result .= '<td>'.getTimeDetails($value[$i]["crew_total"],true).'</td>';
			else 
			$result .= '<td style="background:red;color:white;" title="Trip Takes More than 24 hrs to Finish">'.getTimeDetails($value[$i]["crew_total"],true).'</td>';
			
			$result .= '</tr>';
		}
		$result .= '</table>';
	}

		return $result;
		
	}
	
	

	function changeStatuslbl($status)
	{
		switch ($status)
		{
			case "Waiting":
				return "<label style='background: red;color: #fff;padding: 8px;'>".$status. "</label>";
				break;
			case "Allocated":
				return "<label style='background: #622bca;color: #fff;padding: 8px;'>".$status. "</label>";
				break;
			case "Accepted":
				return "<label style='background: #13759c;color: #fff;padding: 8px;'>".$status. "</label>";
				break;
			case "Reached":
				return "<label style='background: #82a203;color: #fff;padding: 8px;'>".$status. "</label>";
				break;
			case "Picked Up":
				return "<label style='background: #ba2bca;color: #fff;padding: 8px;'>".$status. "</label>";
				break;
			case "Finished":
				return "<label style='background: green;color: #fff;padding: 8px;'>".$status. "</label>";
				break;
			case "Canceled":
				return "<label style='background: black;color: #fff;padding: 8px;'>".$status. "</label>";
				break;
			default:
				return "<label style='background: #737373;color: #fff;padding: 8px;'>".$status. "</label>";
				break;
		}
		
	}

?>