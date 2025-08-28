<?php


function reportGenerateTripWise($imei, $dtf, $dtt,  $speed_limit, $stop_duration)
{
	global $ms,$_SESSION, $la, $user_id,$gsValues;

	if(isset($gsValues['POST_RFID'][$user_id]["id"]) && $user_id==$gsValues['POST_RFID'][$user_id]["id"])
	{
		$dtf=date('Y-m-d H:i',strtotime('-30 minutes',strtotime($dtf)));//one hour before datetime
		$dtt=date('Y-m-d H:i',strtotime('30 minutes',strtotime($dtt))); //one hour after  datetime
	}
	else
	{
		$dtf=date('Y-m-d H:i',strtotime('-59 minutes',strtotime($dtf)));//one hour before datetime
		$dtt=date('Y-m-d H:i',strtotime('59 minutes',strtotime($dtt))); //one hour after  datetime
	}
	
	$trip=array();
	$trip['trip'] = array();
	$tmp_trip=array();
	$aryEvent=array();
	$aLstEvent=array();
	$aLstEvent["startend"]="";
	$curnt_route_strt=0;
	$curnt_route_end=0;
	$curnt_route_strtend=0;
	$itd=0;
	$qEd="select event_id,trip_id,route_id,  event_desc,imei,  obj_name,  dt_server,  dt_tracker,  lat,
	 lng,  altitude,  angle,  speed,  params,  type,  zoneid,startend point from gs_user_events_data 
	 where user_id='".$user_id."' and ctype='Scheduled' and imei='".$imei."' and 
	 (dt_tracker between '".$dtf."' and '".$dtt."') ORDER BY dt_tracker";
	$event_data=getAllRow($qEd);

	if(count($event_data)>1)
	{

		$qT="select de.event_id,de.route_id,de.tripname,de.tfh,de.tfm,de.tth,de.ttm,de.today,guz.zone_name,dr.point from droute_events de
				join droute_sub dr on dr.route_id=de.route_id and (dr.point='Start' or dr.point='End')
				join gs_user_zones guz on guz.zone_id=dr.zonecode
				where imei='".$imei."' and de.user_id=".$user_id;
		$trip_data=getAllRow($qT);

		if(count($trip_data)>1)
		{

			for($ie=0;$ie<count($event_data);$ie++)
			{

				//$dtftot=($sngl_T['tfh'].'.'.$sngl_T['tfm']);
				//$dtttot=($sngl_T['tth'].'.'.$sngl_T['ttm']);
				//$curdateindian=date('Y-m-d',strtotime(gmdate("Y-m-d").$ud["timezone"]));
				//$dtftot=date('H.i',strtotime('-30 minutes',strtotime($curdateindian." ".$sngl_T['tfh'].":".$sngl_T['tfm'].":00")));
				//$dtttot=date('H.i',strtotime('+59 minutes',strtotime($curdateindian." ".$sngl_T['tth'].":".$sngl_T['ttm'].":00")));
				$sngl_E=$event_data[$ie];

				for($irv=0;$irv<count($trip_data);$irv++)
				{
					$sngl_T=$trip_data[$irv];
					$diFend=23.59;
					$diTfrom=0;


					$curdateindian=date('Y-m-d',strtotime(convUserTimezone($sngl_E["dt_tracker"])));
					$dtftot=date('H.i',strtotime('-30 minutes',strtotime($curdateindian." ".$sngl_T['tfh'].":".$sngl_T['tfm'].":00")));
					
					if(isset($gsValues['POST_RFID'][$user_id]["id"]) && $user_id==$gsValues['POST_RFID'][$user_id]["id"])
						$dtttot=date('H.i',strtotime('+30 minutes',strtotime($curdateindian." ".$sngl_T['tth'].":".$sngl_T['ttm'].":00")));
					else
						$dtttot=date('H.i',strtotime('+59 minutes',strtotime($curdateindian." ".$sngl_T['tth'].":".$sngl_T['ttm'].":00")));

					$dttchk=floatval(date("H.i",strtotime(convUserTimezone($sngl_E["dt_tracker"]))));
					if(($sngl_T["today"]=="Same" && (($dttchk) >= ($dtftot) && ($dttchk) <= ($dtttot)))
					||  ($sngl_T["today"]=="Different"  && (($dttchk >= $dtftot and $dttchk <= $diFend)
					|| ($dttchk >= $diTfrom and $dttchk <= $dtttot)))
					)
					{
						if($sngl_E["point"]==$sngl_T["point"] && $sngl_T["point"]=="Start" )
						{

							if(count($tmp_trip)>0)
							{
								$last_event_routedata=$tmp_trip[count($tmp_trip)-1]["trip_data"];
								$last_event_zoneid=$tmp_trip[count($tmp_trip)-1]["start_zone"];
								$last_event_routeid=$tmp_trip[count($tmp_trip)-1]["route_id"];
								$last_event_eventid=$tmp_trip[count($tmp_trip)-1]["event_id"];
								$last_event_end=$tmp_trip[count($tmp_trip)-1]["end"];

								if($last_event_end=="Yes")
								{
										
									$itd++;
									$get_start_end=getAllRow($q= "select dr.point,guz.zone_name,guz.zone_id,de.tripname,d.routename
									 				  from droute_sub dr
 													  join gs_user_zones guz on guz.zone_id=dr.zonecode
 													  join droute_events de on de.route_id=dr.route_id
 													  join droute d on d.route_id=dr.route_id
  													  where  (dr.point='Start' or dr.point='End') and 
  													  dr.route_id=".$sngl_E["route_id"]." and de.event_id=".$sngl_E["trip_id"]) ;

									$tmp_trip[] = array("sno"=>$itd,
									"obj_name"=>$sngl_E['obj_name'],
									"imei"=>$sngl_E['imei'],
									"tripname"=>$get_start_end[0]['tripname'],
									"hotspot"=>$get_start_end[0]['routename'],
									"create"=>date("Y-m-d",strtotime(convUserTimezone($sngl_E['dt_tracker']))),
									"fromplace"=>$get_start_end[0]["zone_name"],
									"toplace"=>$get_start_end[count($get_start_end)-1]["zone_name"],
									"fromtime"=>$sngl_T["tfh"].":".$sngl_T["tfm"],
									"totime"=>$sngl_T["tth"].":".$sngl_T["ttm"],
									"Afromtime"=>date("H:i",strtotime(convUserTimezone($sngl_E['dt_tracker']))),
									"Atotime"=>"",
									"avg_speed"=>"",
									"delay"=>"",
									"route_length"=>"",
									"taken_time"=>"",
									"ovr_speed"=>"",
									"engine_idle"=>"",
									"event_list"=>"",
									"event_count"=>"0",
									"point"=>$sngl_E["point"],
									"start_zone"=>$sngl_E["zoneid"],
									"route_id"=>$sngl_E["route_id"],
									"event_id"=>$sngl_E["trip_id"],
									"trip_data"=>$get_start_end,
									"ognl_time"=>$sngl_E['dt_tracker'],
									"end"=>"No",
									"vstart"=>convUserTimezone($sngl_E["dt_tracker"])
									);
								}
								else if(($last_event_routeid!=$sngl_E["route_id"] || $last_event_eventid!=$sngl_E["trip_id"]))
								{
									//copy past create route array here
									//$trip['trip'][]=$tmp_trip[count($tmp_trip)-1];
									$itd++;
									
									$get_start_end=getAllRow($qvt= "select dr.point,guz.zone_name,guz.zone_id,de.tripname,d.routename
									 				  from droute_sub dr
 													  join gs_user_zones guz on guz.zone_id=dr.zonecode
 													  join droute_events de on de.route_id=dr.route_id
 													   join droute d on d.route_id=dr.route_id
  													  where  (dr.point='Start' or dr.point='End') and 
  													  dr.route_id='".$sngl_E["route_id"]."' and de.event_id=".$sngl_E["trip_id"]) ;
									if(count($get_start_end)>0){
									$tmp_trip[] = array("sno"=>$itd,
									"obj_name"=>$sngl_E['obj_name'],
									"imei"=>$sngl_E['imei'],
									"tripname"=>$get_start_end[0]['tripname'],
									"hotspot"=>$get_start_end[0]['routename'],
									"create"=>date("Y-m-d",strtotime(convUserTimezone($sngl_E['dt_tracker']))),
									"fromplace"=>$get_start_end[0]["zone_name"],
									"toplace"=>$get_start_end[count($get_start_end)-1]["zone_name"],
									"fromtime"=>$sngl_T["tfh"].":".$sngl_T["tfm"],
									"totime"=>$sngl_T["tth"].":".$sngl_T["ttm"],
									"Afromtime"=>date("H:i",strtotime(convUserTimezone($sngl_E['dt_tracker']))),
									"Atotime"=>"",
									"avg_speed"=>"",
									"delay"=>"",
									"route_length"=>"",
									"taken_time"=>"",
									"ovr_speed"=>"",
									"engine_idle"=>"",
									"event_list"=>"",
									"event_count"=>"0",
									"point"=>$sngl_E["point"],
									"start_zone"=>$sngl_E["zoneid"],
									"route_id"=>$sngl_E["route_id"],
									"event_id"=>$sngl_E["trip_id"],
									"trip_data"=>$get_start_end,
									"ognl_time"=>$sngl_E['dt_tracker'],
									"end"=>"No",
									"vstart"=>convUserTimezone($sngl_E["dt_tracker"])
									);
									}
								}
								else if($last_event_zoneid!=$sngl_E["zoneid"] && $last_event_routeid==$sngl_E["route_id"] && $last_event_eventid==$sngl_E["trip_id"])
								{
									//leave it because its second start point
									//for($leer=0;$leer<count($last_event_route);$leer++)
								}
								else 
								{
																		
					
								}
								
								
							}
							else 
							{
								$itd++;
								$qgse="select dr.point,guz.zone_name,guz.zone_id,de.tripname,d.routename
									 				  from droute_sub dr
 													  join gs_user_zones guz on guz.zone_id=dr.zonecode
 													  join droute_events de on de.route_id=dr.route_id
 													   join droute d on d.route_id=dr.route_id
  													  where  (dr.point='Start' or dr.point='End') and 
  													  dr.route_id=".$sngl_E["route_id"]." and de.event_id=".$sngl_E["trip_id"];	
							$get_start_end=getAllRow($qgse);
								
							if(count($get_start_end)>0){
								$tmp_trip[] = array("sno"=>$itd,
								"obj_name"=>$sngl_E['obj_name'],
								"imei"=>$sngl_E['imei'],
								"tripname"=>$get_start_end[0]['tripname'],
								"hotspot"=>$get_start_end[0]['routename'],
								"create"=>date("Y-m-d",strtotime(convUserTimezone($sngl_E['dt_tracker']))),
								"fromplace"=>$get_start_end[0]["zone_name"],
								"toplace"=>$get_start_end[count($get_start_end)-1]["zone_name"],
								"fromtime"=>$sngl_T["tfh"].":".$sngl_T["tfm"],
								"totime"=>$sngl_T["tth"].":".$sngl_T["ttm"],
								"Afromtime"=>date("H:i",strtotime(convUserTimezone($sngl_E['dt_tracker']))),
								"Atotime"=>"",
								"avg_speed"=>"",
								"delay"=>"",
								"route_length"=>"",
								"taken_time"=>"",
								"ovr_speed"=>"",
								"engine_idle"=>"",
								"event_list"=>"",
								"event_count"=>"0",
								"point"=>$sngl_E["point"],
								"start_zone"=>$sngl_E["zoneid"],
								"route_id"=>$sngl_E["route_id"],
								"event_id"=>$sngl_E["trip_id"],
								"trip_data"=>$get_start_end,
								"ognl_time"=>$sngl_E['dt_tracker'],
								"end"=>"No",
								"vstart"=>convUserTimezone($sngl_E["dt_tracker"])
								);
								}
							}
						}
						else
						{
							//If point is end or midpoint
							if(count($tmp_trip)>0)
							{
								//If point is end 
								if(trim($sngl_T["point"])=="End" || $sngl_T["point"]=="" )
								{
									$last_event_routeid=$tmp_trip[count($tmp_trip)-1]["route_id"];
									$last_event_eventid=$tmp_trip[count($tmp_trip)-1]["event_id"];
									$last_event_ognl_time=$tmp_trip[count($tmp_trip)-1]["ognl_time"];
									$last_event_end=$tmp_trip[count($tmp_trip)-1]["end"];

									//update end of the trip
									//if($last_event_ognl_time<$sngl_E['dt_tracker'] &&  $sngl_E["point"]==$sngl_T["point"] && $sngl_E["point"]=="End" && $last_event_end!="Yes" && ( $last_event_routeid==$sngl_E["route_id"] && $last_event_eventid==$sngl_E["trip_id"]) )
									//if( $sngl_E["point"]==$sngl_T["point"] && $sngl_E["point"]=="End" && $last_event_end!="Yes" && ( $last_event_routeid==$sngl_E["route_id"] && $last_event_eventid==$sngl_E["trip_id"]) )
									if( $sngl_E["point"]==$sngl_T["point"] && $sngl_E["point"]=="End" && $last_event_end!="Yes" && ( $last_event_routeid==$sngl_E["route_id"] && $last_event_eventid==$sngl_E["trip_id"]) )
									{

										//echo json_encode($sngl_E);
										$rout=getRoute($imei, $last_event_ognl_time,$sngl_E['dt_tracker'], $stop_duration, false);

										$eventT=$rout["events"];
										$takentiming=getTimeDifferenceDetails($last_event_ognl_time,$sngl_E["dt_tracker"]);

										$delay=(floatval(str_replace(":",".",$tmp_trip[count($tmp_trip)-1]["totime"]))- floatval(date('H.i',strtotime(convUserTimezone($sngl_E["dt_tracker"])))));
										$delay=round($delay,2);
										if($delay<0)
										{
											$pieces = explode(".", abs($delay));
											if(count($pieces)>1)
											$delay= "- ".$pieces[0].' H '.$pieces[1].' m ';
											else
											$delay= "- ".$pieces[0].' m ';
										}
										else
										{
											try{
												$pieces = explode(".", abs($delay));
												if(count($pieces)>1)
												$delay= "+ ". $pieces[0].' H '.$pieces[1].' m ';
												else
												$delay= "- ".$pieces[0].' m ';
											}
											catch (Exception $e)
											{
												echo 'Caught exception: ',  $e->getMessage(), "\n";
											}
										}

										$overspeedcount=0;
										for($ieo=0;$ieo<count($eventT);$ieo++)
										{
											if($eventT[$ieo][8]=="overspeed")
											$overspeedcount=$overspeedcount+1;
										}

										$tmp_trip[count($tmp_trip)-1]["Atotime"]=date("H:i",strtotime(convUserTimezone($sngl_E["dt_tracker"])));
										$tmp_trip[count($tmp_trip)-1]["avg_speed"]=$rout["avg_speed"];
										$tmp_trip[count($tmp_trip)-1]["delay"]=$delay;
										$tmp_trip[count($tmp_trip)-1]["route_length"]=$rout["route_length"];
										$tmp_trip[count($tmp_trip)-1]["taken_time"]=$takentiming;
										$tmp_trip[count($tmp_trip)-1]["ovr_speed"]=$overspeedcount;
										$tmp_trip[count($tmp_trip)-1]["engine_idle"]=$rout["engine_idle"];
										$tmp_trip[count($tmp_trip)-1]["event_count"]=count($eventT);
										$tmp_trip[count($tmp_trip)-1]["event_list"]=$rout["events"];
										$tmp_trip[count($tmp_trip)-1]["end"]="Yes";
										$tmp_trip[count($tmp_trip)-1]["vend"]=convUserTimezone($sngl_E["dt_tracker"]);
									}
									else if($sngl_E["point"]=="" && $last_event_routeid==$sngl_E["route_id"]&& $last_event_eventid==$sngl_E["trip_id"])
									{
										//If point is midpoint
										if($tmp_trip[count($tmp_trip)-1]["end"]!="Yes")
										{
											$tmp_trip[count($tmp_trip)-1]["ognl_time_to"]=$sngl_E["dt_tracker"];
											$tmp_trip[count($tmp_trip)-1]["Atotime"]=date("H:i",strtotime(convUserTimezone($sngl_E["dt_tracker"])));
											$tmp_trip[count($tmp_trip)-1]["vend"]=convUserTimezone($sngl_E["dt_tracker"]);
										}
									}//mid								
									
									
								}//end mid
							}//trip perusu
						}//else end mid
					}//time
					
				}//for
			}//for
			
		
			
			//here check trips end status is Yes otherwise generate report with last midpoint data
			for($velu=0;$velu<count($tmp_trip);$velu++)
			{
				if($tmp_trip[$velu]["end"]!="Yes" && isset($tmp_trip[$velu]["ognl_time_to"]))
				{
					
					$rout=getRoute($imei, $tmp_trip[$velu]["ognl_time"],$tmp_trip[$velu]["ognl_time_to"], $stop_duration, false);

					$eventT=$rout["events"];
					$takentiming=getTimeDifferenceDetails($tmp_trip[$velu]["ognl_time"], $tmp_trip[$velu]["ognl_time_to"]);

					$delay=(floatval(str_replace(":",".",$tmp_trip[$velu]["totime"]))- floatval(str_replace(":",".",$tmp_trip[$velu]["Atotime"])));
					$delay=round($delay,2);
					if($delay<0)
					{
						$pieces = explode(".", abs($delay));
						if(count($pieces)>1)
						$delay= "- ".$pieces[0].' H '.$pieces[1].' m ';
						else
						$delay= "- ".$pieces[0].' m ';
					}
					else
					{
						try{
							$pieces = explode(".", abs($delay));
							if(count($pieces)>1)
							$delay= "+ ". $pieces[0].' H '.$pieces[1].' m ';
							else
							$delay= "- ".$pieces[0].' m ';
						}
						catch (Exception $e)
						{
							echo 'Caught exception: ',  $e->getMessage(), "\n";
						}
					}

					$overspeedcount=0;
					for($ie=0;$ie<count($eventT);$ie++)
					{
						if($eventT[$ie][8]=="overspeed")
						$overspeedcount=$overspeedcount+1;
					}
						
					//$tmp_trip[$velu]["Atotime"]=date("H:i",strtotime(convUserTimezone($sngl_E["dt_tracker"])));
					$tmp_trip[$velu]["avg_speed"]=$rout["avg_speed"];
					$tmp_trip[$velu]["delay"]=$delay;
					$tmp_trip[$velu]["route_length"]=$rout["route_length"];
					$tmp_trip[$velu]["taken_time"]=$takentiming;
					$tmp_trip[$velu]["ovr_speed"]=$overspeedcount;
					$tmp_trip[$velu]["engine_idle"]=$rout["engine_idle"];
					$tmp_trip[$velu]["event_count"]=count($eventT);
					$tmp_trip[$velu]["event_list"]=$rout["events"];
					$tmp_trip[$velu]["end"]="Mid";
					$tmp_trip[count($tmp_trip)-1]["vend"]=convUserTimezone($tmp_trip[$velu]["ognl_time_to"]);
				}
			}
			
		
		}
	}
	
	$trip["trip"]=$tmp_trip;

	return $trip;
}


function reportGenerateTripWiseDaily($imei, $dtf, $dtt,  $speed_limit, $stop_duration)
{
	global $ms;
	global $_SESSION, $la, $user_id;
	
	$trip=array();
	$trip['trip'] = array();
	
	$dtfT=date('Y-m-d H:i:s',strtotime('-59 minutes',strtotime($dtf)));//one hour before datetime
	$dttT=date('Y-m-d H:i:s',strtotime('59 minutes',strtotime($dtt))); //one hour after  datetime	
		
	$qT = "SELECT  * FROM droute_events_daily where (datefrom>='".$dtf."' and dateto<='".$dtt."')  and user_id='".$user_id."' and imei='".$imei."' ORDER BY event_id ";
	$rT = mysqli_query($ms,$qT);
	//echo $qT;
	if(!$rT)
	{
	$itd=0;
	while($rowT=mysql_fetch_assoc($rT))
	{
		$itd++;
		$zones=GetZoneTrip($rowT["route_id"]);//get trip start and end place details
		if(count($zones)>1)
		{
			if($zones[0]["point"]=="Start" &&$zones[count($zones)-1]["point"]=="End" )
			{
				$dtfT=date('Y-m-d H:i:s',strtotime('-59 minutes',strtotime($rowT["datefrom"])));
				$dttT=date('Y-m-d H:i:s',strtotime('59 minutes',strtotime($rowT["dateto"])));

				$eventT=getEventsForDaily($imei,$user_id,$rowT["route_id"],$dtfT,$dttT,$rowT["event_id"]);
				$isfinish="Yes";
				$rowT["startzone"]="";
				$rowT["Astarttime"]="";
				$rowT["endzone"]="";
				$rowT["Aendtime"]="";
							
				$eventtary=array();
				$starttime="";$endtime="";
				$overspeedcount=0;
				for($ie=0;$ie<count($eventT);$ie++)
				{
				
					
					for($iz=0;$iz<count($zones);$iz++)
					{
						if(($eventT[$ie]["type"]=="Zone_In" || $eventT[$ie]["type"]=="Zone_Out"))
						{
							if($eventT[$ie]["startend"]=="Start" && $eventT[$ie]["zoneid"]==$zones[$iz]["zone_id"] && $starttime=="")
							{
								$starttime=$eventT[$ie]["dt_tracker"];
								$rowT["startzone"]=$eventT[$ie]["event_desc"];
								$rowT["Astarttime"]=$eventT[$ie]["dt_tracker"];
							}

							if($eventT[$ie]["startend"]=="End" && $eventT[$ie]["zoneid"]==$zones[$iz]["zone_id"])
							{
								$endtime=$eventT[$ie]["dt_tracker"];
								$rowT["endzone"]=$eventT[$ie]["event_desc"];
								$rowT["Aendtime"]=$eventT[$ie]["dt_tracker"];
							}

						}
						else
						{
							if($eventT[$ie]["type"]=="overspeed")
							$overspeedcount=$overspeedcount+1;
						}
					}
				}
				
				if(count($eventT)>1)
				{
					if($rowT["Astarttime"]=="")
					{
						$isfinish="No";
						$rowT["startzone"]=$eventT[0]["event_desc"];
						$rowT["Astarttime"]=$eventT[0]["dt_tracker"];
					}
				
					if($rowT["Aendtime"]=="")
					{
						$isfinish="No";
						$rowT["endzone"]=$eventT[count($eventT)-1]["event_desc"];
						$rowT["Aendtime"]=$eventT[count($eventT)-1]["dt_tracker"];
					}
					
					$rout=getRoute($imei,$rowT["Astarttime"],$rowT["Aendtime"], $stop_duration,false);
						
					$delay="";
					$takentiming="";
					if($rowT['Aendtime']!="")
					{
						$delay=getTimeDifferenceDetails($rowT['dateto'],$rowT['Aendtime']);
						$takentiming=getTimeDifferenceDetails($rowT['Astarttime'],$rowT['Aendtime']);
					}
				
					for($iev=0;$iev<count($rout["events"]);$iev++)
					{
						if($rout["events"][$iev][8]=="overspeed")
							$overspeedcount=$overspeedcount+1;
					}
				
				$trip['trip'][] = array($itd,
				"",
				$rowT['imei'],
				$rowT['tripname'],
				$rowT['routeid'],
				$rowT["Astarttime"],
				$rowT["startzone"],
				$rowT['endzone'],
				$rowT['datefrom'],
				$rowT['dateto'],
				$rowT['Astarttime'],
				$rowT['Aendtime'],
				$rout["avg_speed"],
				$delay,
				$rout["route_length"],
				$takentiming,
				$overspeedcount,
				$rout["engine_idle"],
				count($eventT),
				$eventT,
				$isfinish
				);
				}
			}
		}
	}
}

	return $trip;
}


function getEventsForDaily($imei,$userid,$route_id,$dtfT,$dttT,$tripid)
{
	global $ms;
	$routeT = array();
	//$q = "SELECT * FROM gs_user_events_data WHERE imei='".$imei."' and user_id='".$userid."' and trip_id='".$tripid."' and route_id='".$route_id."' and dt_tracker BETWEEN '".$dtfT."' AND '".$dttT."' and ctype='Daily' ORDER BY dt_tracker ASC";
	//$q = "SELECT * FROM gs_user_events_data WHERE imei='".$imei."' and user_id='".$userid."' and trip_id='".$tripid."' and route_id='".$route_id."' and ctype='Daily' ORDER BY dt_tracker ASC;";
	/*
	$q=" SELECT gued.route_id,trip_id,event_desc,obj_name,dt_tracker,lat,lng,speed,params,type,zoneid,startend,ctype FROM gs_user_events_data gued
		join droute_sub ds on ds.route_id=gued.route_id and ds.zonecode=gued.zoneid
 		WHERE imei='".$imei."' and gued.user_id='".$userid."' and trip_id='".$tripid."' and gued.route_id='".$route_id."' and ctype='Daily' ORDER BY dt_tracker ASC;";
	*/
	$q=" SELECT gued.route_id,trip_id,event_desc,obj_name,dt_tracker,lat,lng,speed,params,type,zoneid,startend,ctype FROM gs_user_events_data gued
		join droute_sub ds on ds.route_id=gued.route_id and ds.zonecode=gued.zoneid
 		WHERE imei='".$imei."' and gued.user_id='".$userid."' and trip_id='".$tripid."' and gued.route_id='".$route_id."' and ctype='Daily' and dt_tracker BETWEEN '".$dtfT."' AND '".$dttT."'  ORDER BY dt_tracker ASC;";
	
	//echo $q;
	$r = mysqli_query($ms,$q);
	if(!$r)
	{return $routeT;}

	while($route_data=mysqli_fetch_array($r,MYSQLI_ASSOC))
	{
		$routeT[]=$route_data;
	}
	return $routeT;
}


function GetRawdata($imei,$dtfT,$dttT)
{
	global $ms;
	$routeT = array();
	$q = "SELECT dt_tracker,lat,lng,altitude,angle,speed,params,mileage FROM `gs_object_data_".$imei."` WHERE dt_tracker BETWEEN '".$dtfT."' AND '".$dttT."' ORDER BY dt_tracker ASC";
	$r = mysqli_query($ms,$q);
	if(!$r)
	{return $routeT;}

	while($route_data=mysqli_fetch_array($r,MYSQLI_ASSOC))
	{
		$routeT[]=$route_data;
	}
	return $routeT;
}


function GetZoneTrip($routeid)
{
	global $ms;
	$routeT=array();
	$q = "SELECT guz.zone_id,guz.zone_name,ds.zoneinout,ds.point FROM droute_sub ds join gs_user_zones guz on guz.zone_id=ds.zonecode where route_id=".$routeid;

	$r = mysqli_query($ms,$q);
	if(!$r)
	{return $routeT;}
		
	while($route_data=mysqli_fetch_array($r,MYSQLI_ASSOC))
	{
		$routeT[]=$route_data;
	}
	return $routeT;
}


function getAllRow($query)
{
	global $ms;
	$rtnary=array();
	$r = mysqli_query($ms,$query);
	//echo $query;

	while($route_data=mysqli_fetch_assoc($r))
	{
		$rtnary[]= $route_data;
	}
	return $rtnary;
}


function getFirstRow($query)
{
	global $ms;
	$r = mysqli_query($ms,$query);
	if($route_data=mysqli_fetch_array($r,MYSQLI_ASSOC))
	{
		return $route_data;
	}
	return false;
}


function getRoundTrip($imeis, $dtff, $dttt, $min_stop_duration, $filter,$speed_limit,$zone_id,$zone_idv)
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
	$q = "select * from gs_user_events_data where zoneid!=0 and (zoneid=".$zone_id." or zoneid=".$zone_idv.") and user_id='".$user_id."' and imei=".$imeis." and (dt_tracker between '".$dtff."' and '".$dttt."')  and (ctype is null or ctype='') order by dt_tracker  asc";
		$r = mysqli_query($ms,$q);
		if(!$r)
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
					$overspeeds_count=0;
					if ($speed_limit > 0)
					{
						$overspeeds = getRouteOverspeeds($result['route'], $speed_limit);
						$overspeeds_count = count($overspeeds);
					}
					else
					{
						$overspeeds_count = 0;
					}

						$trip["trip"][]= array(
						$from['obj_name'],
						$from['event_desc'],
						$to['event_desc'],
						$from['dt_tracker'],
						$to['dt_tracker'],
						$result['avg_speed'],
						$result['route_length'],
						$result['drives_duration'],
						$overspeeds_count,
						$result['stops_duration'],
						$result['events'],
						$result['top_speed']
						);
				}		
			}
		}
	
		return $trip;
}
	

function getRoundTripSingle($imeis, $dtff, $dttt, $min_stop_duration, $filter,$speed_limit,$zone_id,$zone_idv)
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
		$q = "select * from gs_user_events_data where zoneid!=0 and zoneid=".$zone_id."  and user_id='".$user_id."' and imei=".$imeis." and (dt_tracker between '".$dtff."' and '".$dttt."')  and ctype is null  order by dt_tracker  asc";

		$r = mysqli_query($ms,$q);
		if(!$r)
        {
        	return $trip;
        }
        $eventdatanew = array();
		while($trip_data=mysqli_fetch_array($r,MYSQLI_ASSOC))
		{			
			//if($trip_data["type"]=="Zone_Out" && count($eventdatanew)==0)
			if(($trip_data["type"]=="zone_out" || $trip_data["type"]=="Zone_Out")&& count($eventdatanew)==0)
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
					$overspeeds_count=0;
					if ($speed_limit > 0)
					{
						$overspeeds = getRouteOverspeeds($result['route'], $speed_limit);
						$overspeeds_count = count($overspeeds);
					}
					else
					{
						$overspeeds_count = 0;
					}
					
						$trip["trip"][]= array(
						$from['obj_name'],
						$from['event_desc'],
						$to['event_desc'],
						$from['dt_tracker'],
						$to['dt_tracker'],
						$result['avg_speed'],
						$result['route_length'],
						$result['drives_duration'],
						$overspeeds_count,
						$result['stops_duration'],
						$result['events'],
						$result['top_speed']
						);
				}		
			}
		}
		
	
		return $trip;
}


function reportGenerateTripWise_RFID($imei, $dtf, $dtt,  $speed_limit, $stop_duration,$emptyreport=false)
{
	global $ms,$_SESSION, $la, $user_id,$gsValues;

	$dtf_zero=$dtf;
	$dtt_zero=$dtt;
	
	if(isset($gsValues['POST_RFID'][$user_id]["id"]) && $user_id==$gsValues['POST_RFID'][$user_id]["id"])
	{
		$dtf=date('Y-m-d H:i',strtotime('-30 minutes',strtotime($dtf)));//one hour before datetime
		$dtt=date('Y-m-d H:i',strtotime('30 minutes',strtotime($dtt))); //one hour after  datetime
	}
	else
	{
		$dtf=date('Y-m-d H:i',strtotime('-59 minutes',strtotime($dtf)));//one hour before datetime
		$dtt=date('Y-m-d H:i',strtotime('59 minutes',strtotime($dtt))); //one hour after  datetime
	}
	
	$trip=array();
	$trip['trip'] = array();
	$final_trip=array();
	$tmp_trip=array();
	$aryEvent=array();
	$aLstEvent=array();
	$aLstEvent["startend"]="";
	$curnt_route_strt=0;
	$curnt_route_end=0;
	$curnt_route_strtend=0;
	$itd=0;
	$qEd="select event_id,trip_id,route_id,  event_desc,imei,  obj_name,  dt_server,  dt_tracker,  lat,
	 lng,  altitude,  angle,  speed,  params,  type,  zoneid,startend point from gs_user_events_data 
	 where user_id='".$user_id."' and ctype='Scheduled' and imei='".$imei."' and 
	 (dt_tracker between '".$dtf."' and '".$dtt."') ORDER BY dt_tracker";
	$event_data=getAllRow($qEd);
	//echo $qEd;
	if(count($event_data)>1)
	{

		$qT="select de.event_id,de.route_id,de.tripname,de.tfh,de.tfm,de.tth,de.ttm,de.today,guz.zone_name,dr.point from droute_events de
				join droute_sub dr on dr.route_id=de.route_id and (dr.point='Start' or dr.point='End')
				join gs_user_zones guz on guz.zone_id=dr.zonecode
				where imei='".$imei."' and de.user_id=".$user_id;
		$trip_data=getAllRow($qT);
		//echo $qT;
		if(count($trip_data)>1)
		{

			for($ie=0;$ie<count($event_data);$ie++)
			{

				//$dtftot=($sngl_T['tfh'].'.'.$sngl_T['tfm']);
				//$dtttot=($sngl_T['tth'].'.'.$sngl_T['ttm']);
				//$curdateindian=date('Y-m-d',strtotime(gmdate("Y-m-d").$ud["timezone"]));
				//$dtftot=date('H.i',strtotime('-30 minutes',strtotime($curdateindian." ".$sngl_T['tfh'].":".$sngl_T['tfm'].":00")));
				//$dtttot=date('H.i',strtotime('+59 minutes',strtotime($curdateindian." ".$sngl_T['tth'].":".$sngl_T['ttm'].":00")));
				$sngl_E=$event_data[$ie];

				for($irv=0;$irv<count($trip_data);$irv++)
				{
					$sngl_T=$trip_data[$irv];
					$diFend=23.59;
					$diTfrom=0;


					$curdateindian=date('Y-m-d',strtotime(convUserTimezone($sngl_E["dt_tracker"])));
					$dtftot=date('H.i',strtotime('-30 minutes',strtotime($curdateindian." ".$sngl_T['tfh'].":".$sngl_T['tfm'].":00")));
					
					$dtft_actual=date('Y-m-d H:i:s',strtotime($curdateindian." ".$sngl_T['tfh'].":".$sngl_T['tfm'].":00"));
					$dtft_actual_15=date('Y-m-d H:i:s',strtotime('-15 minutes',strtotime($curdateindian." ".$sngl_T['tfh'].":".$sngl_T['tfm'].":00")));
					
					$dttt_actual=date('Y-m-d H:i:s',strtotime($curdateindian." ".$sngl_T['tth'].":".$sngl_T['ttm'].":00"));
					$dttt_actual_15=date('Y-m-d H:i:s',strtotime('+15 minutes',strtotime($curdateindian." ".$sngl_T['tth'].":".$sngl_T['ttm'].":00")));
					
					if(floatval($sngl_T['tfh'].".".$sngl_T['tfm']) > floatval($sngl_T['tth'].".".$sngl_T['ttm']))
					{
						$curdateindianoneday=date('Y-m-d',strtotime('+1 day',strtotime($curdateindian)));
						$dttt_actual=date('Y-m-d H:i:s',strtotime($curdateindianoneday." ".$sngl_T['tth'].":".$sngl_T['ttm'].":00"));
						$dttt_actual_15=date('Y-m-d H:i:s',strtotime('+15 minutes',strtotime($curdateindianoneday." ".$sngl_T['tth'].":".$sngl_T['ttm'].":00")));
					}
					
					if(isset($gsValues['POST_RFID'][$user_id]["id"]) && $user_id==$gsValues['POST_RFID'][$user_id]["id"])
						$dtttot=date('H.i',strtotime('+30 minutes',strtotime($curdateindian." ".$sngl_T['tth'].":".$sngl_T['ttm'].":00")));
					else
						$dtttot=date('H.i',strtotime('+59 minutes',strtotime($curdateindian." ".$sngl_T['tth'].":".$sngl_T['ttm'].":00")));
					

					$dttchk=floatval(date("H.i",strtotime(convUserTimezone($sngl_E["dt_tracker"]))));
					if(($sngl_T["today"]=="Same" && (($dttchk) >= ($dtftot) && ($dttchk) <= ($dtttot)))
					||  ($sngl_T["today"]=="Different"  && (($dttchk >= $dtftot and $dttchk <= $diFend)
					|| ($dttchk >= $diTfrom and $dttchk <= $dtttot)))
					)
					{
						if($sngl_E["point"]==$sngl_T["point"] && $sngl_T["point"]=="Start" )
						{

							if(count($tmp_trip)>0)
							{
								$last_event_routedata=$tmp_trip[count($tmp_trip)-1]["trip_data"];
								$last_event_zoneid=$tmp_trip[count($tmp_trip)-1]["start_zone"];
								$last_event_routeid=$tmp_trip[count($tmp_trip)-1]["route_id"];
								$last_event_eventid=$tmp_trip[count($tmp_trip)-1]["event_id"];
								$last_event_end=$tmp_trip[count($tmp_trip)-1]["end"];

								if($last_event_end=="Yes")
								{
										
									$itd++;
									$get_start_end=getAllRow("select dr.point,guz.zone_name,guz.zone_id,de.tripname,d.routename
									 				  from droute_sub dr
 													  join gs_user_zones guz on guz.zone_id=dr.zonecode
 													  join droute_events de on de.route_id=dr.route_id
 													  join droute d on d.route_id=dr.route_id
  													  where  (dr.point='Start' or dr.point='End') and 
  													  dr.route_id=".$sngl_E["route_id"]." and de.event_id=".$sngl_E["trip_id"]) ;
									$tmp_trip[] = array("sno"=>$itd,
									"obj_name"=>$sngl_E['obj_name'],
									"imei"=>$sngl_E['imei'],
									"tripname"=>$get_start_end[0]['tripname'],
									"hotspot"=>$get_start_end[0]['routename'],
									"create"=>date("Y-m-d",strtotime(convUserTimezone($sngl_E['dt_tracker']))),
									"fromplace"=>$get_start_end[0]["zone_name"],
									"toplace"=>$get_start_end[count($get_start_end)-1]["zone_name"],
									"fromtime"=>$sngl_T["tfh"].":".$sngl_T["tfm"],
									"totime"=>$sngl_T["tth"].":".$sngl_T["ttm"],
									"Afromtime"=>date("H:i",strtotime(convUserTimezone($sngl_E['dt_tracker']))),
									"Atotime"=>"",
									"avg_speed"=>"",
									"delay"=>"",
									"route_length"=>"",
									"taken_time"=>"",
									"ovr_speed"=>"",
									"engine_idle"=>"",
									"event_list"=>"",
									"event_count"=>"0",
									"point"=>$sngl_E["point"],
									"start_zone"=>$sngl_E["zoneid"],
									"route_id"=>$sngl_E["route_id"],
									"event_id"=>$sngl_E["trip_id"],
									"trip_data"=>$get_start_end,
									"ognl_time"=>$sngl_E['dt_tracker'],
									"end"=>"No"
									,"dt_actual"=>$dtft_actual
									,"dt_actual_15"=>$dtft_actual_15
									,"dt_actual_to"=>$dttt_actual
									,"dt_actual_to_15"=>$dttt_actual_15
									);
								}
								else if(($last_event_routeid!=$sngl_E["route_id"] || $last_event_eventid!=$sngl_E["trip_id"]))
								{
									//copy past create route array here
									//$trip['trip'][]=$tmp_trip[count($tmp_trip)-1];
									$itd++;
									$get_start_end=getAllRow("select dr.point,guz.zone_name,guz.zone_id,de.tripname,d.routename
									 				  from droute_sub dr
 													  join gs_user_zones guz on guz.zone_id=dr.zonecode
 													  join droute_events de on de.route_id=dr.route_id
 													  join droute d on d.route_id=dr.route_id
  													  where  (dr.point='Start' or dr.point='End') and 
  													  dr.route_id=".$sngl_E["route_id"]." and de.event_id=".$sngl_E["trip_id"]) ;
									if(count($get_start_end)>0){
									$tmp_trip[] = array("sno"=>$itd,
									"obj_name"=>$sngl_E['obj_name'],
									"imei"=>$sngl_E['imei'],
									"tripname"=>$get_start_end[0]['tripname'],
									"hotspot"=>$get_start_end[0]['routename'],
									"create"=>date("Y-m-d",strtotime(convUserTimezone($sngl_E['dt_tracker']))),
									"fromplace"=>$get_start_end[0]["zone_name"],
									"toplace"=>$get_start_end[count($get_start_end)-1]["zone_name"],
									"fromtime"=>$sngl_T["tfh"].":".$sngl_T["tfm"],
									"totime"=>$sngl_T["tth"].":".$sngl_T["ttm"],
									"Afromtime"=>date("H:i",strtotime(convUserTimezone($sngl_E['dt_tracker']))),
									"Atotime"=>"",
									"avg_speed"=>"",
									"delay"=>"",
									"route_length"=>"",
									"taken_time"=>"",
									"ovr_speed"=>"",
									"engine_idle"=>"",
									"event_list"=>"",
									"event_count"=>"0",
									"point"=>$sngl_E["point"],
									"start_zone"=>$sngl_E["zoneid"],
									"route_id"=>$sngl_E["route_id"],
									"event_id"=>$sngl_E["trip_id"],
									"trip_data"=>$get_start_end,
									"ognl_time"=>$sngl_E['dt_tracker'],
									"end"=>"No"
									,"dt_actual"=>$dtft_actual
									,"dt_actual_15"=>$dtft_actual_15
									,"dt_actual_to"=>$dttt_actual
									,"dt_actual_to_15"=>$dttt_actual_15
									);
									}
								}
								else if($last_event_zoneid!=$sngl_E["zoneid"] && $last_event_routeid==$sngl_E["route_id"] && $last_event_eventid==$sngl_E["trip_id"])
								{
									//leave it because its second start point
									//for($leer=0;$leer<count($last_event_route);$leer++)
								}
								else 
								{
																		
					
								}
							}
							else 
							{
								$itd++;
								$qgse="select dr.point,guz.zone_name,guz.zone_id,de.tripname,d.routename
									 				  from droute_sub dr
 													  join gs_user_zones guz on guz.zone_id=dr.zonecode
 													  join droute_events de on de.route_id=dr.route_id
 													  join droute d on d.route_id=dr.route_id
  													  where  (dr.point='Start' or dr.point='End') and 
  													  dr.route_id=".$sngl_E["route_id"]." and de.event_id=".$sngl_E["trip_id"];	
							$get_start_end=getAllRow($qgse);
								
							if(count($get_start_end)>0){
								$tmp_trip[] = array("sno"=>$itd,
								"obj_name"=>$sngl_E['obj_name'],
								"imei"=>$sngl_E['imei'],
								"tripname"=>$get_start_end[0]['tripname'],
								"hotspot"=>$get_start_end[0]['routename'],
								"create"=>date("Y-m-d",strtotime(convUserTimezone($sngl_E['dt_tracker']))),
								"fromplace"=>$get_start_end[0]["zone_name"],
								"toplace"=>$get_start_end[count($get_start_end)-1]["zone_name"],
								"fromtime"=>$sngl_T["tfh"].":".$sngl_T["tfm"],
								"totime"=>$sngl_T["tth"].":".$sngl_T["ttm"],
								"Afromtime"=>date("H:i",strtotime(convUserTimezone($sngl_E['dt_tracker']))),
								"Atotime"=>"",
								"avg_speed"=>"",
								"delay"=>"",
								"route_length"=>"",
								"taken_time"=>"",
								"ovr_speed"=>"",
								"engine_idle"=>"",
								"event_list"=>"",
								"event_count"=>"0",
								"point"=>$sngl_E["point"],
								"start_zone"=>$sngl_E["zoneid"],
								"route_id"=>$sngl_E["route_id"],
								"event_id"=>$sngl_E["trip_id"],
								"trip_data"=>$get_start_end,
								"ognl_time"=>$sngl_E['dt_tracker'],
								"end"=>"No"
								,"dt_actual"=>$dtft_actual
								,"dt_actual_15"=>$dtft_actual_15
								,"dt_actual_to"=>$dttt_actual
								,"dt_actual_to_15"=>$dttt_actual_15
								);
								}
							}
						}
						else
						{
							//If point is end or midpoint
							if(count($tmp_trip)>0)
							{
								//If point is end 
								if(trim($sngl_T["point"])=="End" || $sngl_T["point"]=="" )
								{
									$last_event_routeid=$tmp_trip[count($tmp_trip)-1]["route_id"];
									$last_event_eventid=$tmp_trip[count($tmp_trip)-1]["event_id"];
									$last_event_ognl_time=$tmp_trip[count($tmp_trip)-1]["ognl_time"];
									$last_event_end=$tmp_trip[count($tmp_trip)-1]["end"];
										
									//update end of the trip
									//if($last_event_ognl_time<$sngl_E['dt_tracker'] &&  $sngl_E["point"]==$sngl_T["point"] && $sngl_E["point"]=="End" && $last_event_end!="Yes" && ( $last_event_routeid==$sngl_E["route_id"] && $last_event_eventid==$sngl_E["trip_id"]) )
									if( $sngl_E["point"]==$sngl_T["point"] && $sngl_E["point"]=="End" && $last_event_end!="Yes" && ( $last_event_routeid==$sngl_E["route_id"] && $last_event_eventid==$sngl_E["trip_id"]) )
									{
										//echo json_encode($sngl_E);
										$rout=getRoute($imei, $last_event_ognl_time,$sngl_E['dt_tracker'], $stop_duration, false);

										$eventT=$rout["events"];
										$takentiming=getTimeDifferenceDetails($last_event_ognl_time,$sngl_E["dt_tracker"]);

										$tmp_ognl_time_to=convUserTimezone($sngl_E["dt_tracker"]);
										if(strtotime($tmp_trip[count($tmp_trip)-1]["dt_actual_to"])< strtotime($tmp_ognl_time_to))
											$delay="+ ".getTimeDifferenceDetails($tmp_trip[count($tmp_trip)-1]["dt_actual_to"], $tmp_ognl_time_to);
										else 
											$delay="- ".getTimeDifferenceDetails($tmp_ognl_time_to, $tmp_trip[count($tmp_trip)-1]["dt_actual_to"]);
										
						
										$overspeedcount=0;
										for($ieo=0;$ieo<count($eventT);$ieo++)
										{
											if($eventT[$ieo][8]=="overspeed")
											$overspeedcount=$overspeedcount+1;
										}

										$tmp_trip[count($tmp_trip)-1]["Atotime"]=date("H:i",strtotime(convUserTimezone($sngl_E["dt_tracker"])));
										$tmp_trip[count($tmp_trip)-1]["avg_speed"]=$rout["avg_speed"];
										$tmp_trip[count($tmp_trip)-1]["delay"]=$delay;
										$tmp_trip[count($tmp_trip)-1]["route_length"]=$rout["route_length"];
										$tmp_trip[count($tmp_trip)-1]["taken_time"]=$takentiming;
										$tmp_trip[count($tmp_trip)-1]["ovr_speed"]=$overspeedcount;
										$tmp_trip[count($tmp_trip)-1]["engine_idle"]=$rout["engine_idle"];
										$tmp_trip[count($tmp_trip)-1]["event_count"]=count($eventT);
										$tmp_trip[count($tmp_trip)-1]["event_list"]=$rout["events"];
										$tmp_trip[count($tmp_trip)-1]["end"]="Yes";
										$tmp_trip[count($tmp_trip)-1]["ognl_time_to"]=$sngl_E["dt_tracker"];
										
										$dt_actual=$tmp_trip[count($tmp_trip)-1]["dt_actual"];
										$dt_actual_15=$tmp_trip[count($tmp_trip)-1]["dt_actual_15"];
										
										$final_start=$last_event_ognl_time;
										if($last_event_ognl_time>$dtft_actual)
											$final_start=$dtft_actual;
										else if( $last_event_ognl_time<$dtft_actual && $last_event_ognl_time>$dt_actual_15)
											$final_start=$dt_actual_15;
										else
										{
											$final_start=date('Y-m-d H:i:s',strtotime('-15 minutes',strtotime($last_event_ognl_time)));
										}
										
										$dt_actual_to=$tmp_trip[count($tmp_trip)-1]["dt_actual_to"];
										/*
										$dt_actual_to=$tmp_trip[count($tmp_trip)-1]["dt_actual_to"];
										$dt_actual_to_15=$tmp_trip[count($tmp_trip)-1]["dt_actual_to_15"];
										$final_end=$sngl_E["dt_tracker"];
										if($sngl_E["dt_tracker"]<$dt_actual_to)
											$final_end=$dt_actual_to;
										else if( $sngl_E["dt_tracker"]>$dt_actual_to && $sngl_E["dt_tracker"]<$dt_actual_to_15)
											$final_end=$dt_actual_to_15;
										else
										{
											$final_end=date('Y-m-d H:i:s',strtotime('+15 minutes',strtotime($sngl_E["dt_tracker"])));
										}
										*/
										
										$final_end=date('Y-m-d H:i:s',strtotime('+15 minutes',strtotime($sngl_E["dt_tracker"])));
										$tmp_trip[count($tmp_trip)-1]["vstart"]=$final_start;
										$tmp_trip[count($tmp_trip)-1]["vend"]=$final_end;
										
										if(isset($gsValues['POST_RFID'][$user_id]["id"]) && $user_id==$gsValues['POST_RFID'][$user_id]["id"])
										{
											$hotspot_name=$tmp_trip[count($tmp_trip)-1]["hotspot"];
											$trip_name=$tmp_trip[count($tmp_trip)-1]["tripname"];
											if(strlen($hotspot_name)>2)
												$hotspot_name=substr($hotspot_name,0,2);
												
											$crename=getCrewDeatil($trip_name,$dt_actual,$dt_actual_to,$imei);
											$tmp_trip[count($tmp_trip)-1]["routeno"]=$hotspot_name;
											$tmp_trip[count($tmp_trip)-1]["crew"]=$crename["crew"];
												//temp crew finiding procedure
												//$crename=gettmpCrewDeatil($imei,$final_start,$final_end);
											$q = "(SELECT cm.routeno,cm.gname,gr.dt_swipe,gr.lat,gr.lng,gr.rfid,cm.empid,cm.firstname,'' extra FROM gs_rfid_swipe_data gr left join csnmast cm on cm.cardno=gr.rfid WHERE gr.imei='".$imei."' AND gr.dt_swipe BETWEEN '".$final_start."' AND '".$final_end."' ORDER BY gr.dt_swipe ASC) union 
 													(select c.routeno,c.gname,'' dt_swipe,'' lat,'' lng,c.cardno rfid,c.empid,c.firstname,'D' extra from csnmast c where c.routeno='".$hotspot_name."' and gname='".$crename["crew"]."' and c.cardno not in 
													(select gr.rfid from gs_rfid_swipe_data gr WHERE gr.imei='".$imei."' AND gr.dt_swipe BETWEEN '".$final_start."' AND '".$final_end."'))";
											$tmp_trip[count($tmp_trip)-1]["rfidlist"]=gettmpCrewFinal($q,$hotspot_name,$crename["crew"]);
											
										}
										else 
										{
											$q = " SELECT gr.dt_swipe,gr.lat,gr.lng,gr.rfid,ds.sid,ds.name,dpt.dept_name,dsc.section_name
 												FROM gs_rfid_swipe_data gr left join dstudent ds on gr.rfid=ds.rfid  
												left join dept dpt on dpt.dept_id=ds.dept_id left join dsection dsc on dsc.section_id=ds.section_id
												where gr.imei='".$imei."' AND gr.dt_swipe BETWEEN '".$final_start."' AND '".$final_end."' ORDER BY gr.dt_swipe ASC";
											$tmp_trip[count($tmp_trip)-1]["rfidlist"]=getAllRow($q);
										}
										
									}
									else if($sngl_E["point"]=="" && $last_event_routeid==$sngl_E["route_id"]&& $last_event_eventid==$sngl_E["trip_id"])
									{
										//If point is midpoint
										if($tmp_trip[count($tmp_trip)-1]["end"]!="Yes")
										{
											$tmp_trip[count($tmp_trip)-1]["ognl_time_to"]=$sngl_E["dt_tracker"];
											$tmp_trip[count($tmp_trip)-1]["Atotime"]=date("H:i",strtotime(convUserTimezone($sngl_E["dt_tracker"])));
											$last_event_ognl_time=$tmp_trip[count($tmp_trip)-1]["ognl_time"];
											$hotspot_name=$tmp_trip[count($tmp_trip)-1]["hotspot"];
										}
									}//mid
									
								}//end mid
							}//trip perusu
						}//else end mid
					}//time
					
				}//for
			}//for
			
		
			
			//here check trips end status is Yes otherwise generate report with last midpoint data
			for($velu=0;$velu<count($tmp_trip);$velu++)
			{
				if($tmp_trip[$velu]["end"]!="Yes" && isset($tmp_trip[$velu]["ognl_time_to"]))
				{
					
					$rout=getRoute($imei, $tmp_trip[$velu]["ognl_time"],$tmp_trip[$velu]["ognl_time_to"], $stop_duration, false);

					$eventT=$rout["events"];
					$takentiming=getTimeDifferenceDetails($tmp_trip[$velu]["ognl_time"], $tmp_trip[$velu]["ognl_time_to"]);

					
					$tmp_ognl_time_to=convUserTimezone($tmp_trip[$velu]["ognl_time_to"]);
					if(strtotime($tmp_trip[$velu]["dt_actual_to"])< strtotime($tmp_ognl_time_to))
						$delay="+ ".getTimeDifferenceDetails($tmp_trip[$velu]["dt_actual_to"], $tmp_ognl_time_to);
					else 
						$delay="- ".getTimeDifferenceDetails($tmp_ognl_time_to, $tmp_trip[$velu]["dt_actual_to"]);
					
					$overspeedcount=0;
					for($ie=0;$ie<count($eventT);$ie++)
					{
						if($eventT[$ie][8]=="overspeed")
						$overspeedcount=$overspeedcount+1;
					}
						
					//$tmp_trip[$velu]["Atotime"]=date("H:i",strtotime(convUserTimezone($sngl_E["dt_tracker"])));
					$tmp_trip[$velu]["avg_speed"]=$rout["avg_speed"];
					$tmp_trip[$velu]["delay"]=$delay;
					$tmp_trip[$velu]["route_length"]=$rout["route_length"];
					$tmp_trip[$velu]["taken_time"]=$takentiming;
					$tmp_trip[$velu]["ovr_speed"]=$overspeedcount;
					$tmp_trip[$velu]["engine_idle"]=$rout["engine_idle"];
					$tmp_trip[$velu]["event_count"]=count($eventT);
					$tmp_trip[$velu]["event_list"]=$rout["events"];
					$tmp_trip[$velu]["end"]="Mid";
					
					$final_end=$tmp_trip[$velu]["ognl_time_to"];
					$last_event_ognl_time=$tmp_trip[$velu]["ognl_time"];
					$hotspot_name=$tmp_trip[$velu]["hotspot"];

					$dt_actual=$tmp_trip[$velu]["dt_actual"];
					$dt_actual_15=$tmp_trip[$velu]["dt_actual_15"];

					$final_start=$last_event_ognl_time;
					if($last_event_ognl_time>$dtft_actual)
					$final_start=$dtft_actual;
					else if( $last_event_ognl_time<$dtft_actual && $last_event_ognl_time>$dt_actual_15)
					$final_start=$dt_actual_15;
					else
					{
						$final_start=date('Y-m-d H:i:s',strtotime('-15 minutes',strtotime($last_event_ognl_time)));
					}
					
					$dt_actual_to=$tmp_trip[count($tmp_trip)-1]["dt_actual_to"];
					/*
					$dt_actual_to=$tmp_trip[count($tmp_trip)-1]["dt_actual_to"];
					$dt_actual_to_15=$tmp_trip[count($tmp_trip)-1]["dt_actual_to_15"];
					if($sngl_E["dt_tracker"]<$dt_actual_to)
						$final_end=$dt_actual_to;
					else if( $sngl_E["dt_tracker"]>$dt_actual_to && $sngl_E["dt_tracker"]<$dt_actual_to_15)
						$final_end=$dt_actual_to_15;
					else
					{
						$final_end=date('Y-m-d H:i:s',strtotime('+15 minutes',strtotime($final_end)));
					}
					*/

					$final_end=date('Y-m-d H:i:s',strtotime('+15 minutes',strtotime($final_end)));
					
					$tmp_trip[$velu]["vstart"]=$final_start;
					$tmp_trip[$velu]["vend"]=$final_end;
					
					if(isset($gsValues['POST_RFID'][$user_id]["id"]) && $user_id==$gsValues['POST_RFID'][$user_id]["id"])
					{
						$trip_name=$tmp_trip[count($tmp_trip)-1]["tripname"];
										
						if(strlen($hotspot_name)>2)
						$hotspot_name=substr($hotspot_name,0,2);
						
						$crename=getCrewDeatil($trip_name,$dt_actual,$dt_actual_to,$imei);
						
						$tmp_trip[$velu]["routeno"]=$hotspot_name;
						$tmp_trip[$velu]["crew"]=$crename["crew"];
											
						//temp crew finiding procedure
						//$crename=gettmpCrewDeatil($imei,$final_start,$final_end);
						$q = "(SELECT cm.routeno,cm.gname,gr.dt_swipe,gr.lat,gr.lng,gr.rfid,cm.empid,cm.firstname,'' extra FROM gs_rfid_swipe_data gr left join csnmast cm on cm.cardno=gr.rfid WHERE gr.imei='".$imei."' AND gr.dt_swipe BETWEEN '".$final_start."' AND '".$final_end."' ORDER BY gr.dt_swipe ASC) union
 													(select c.routeno,c.gname,'' dt_swipe,'' lat,'' lng,c.cardno rfid,c.empid,c.firstname,'D' extra from csnmast c where c.routeno='".$hotspot_name."' and gname='".$crename["crew"]."' and c.cardno not in 
													(select gr.rfid from gs_rfid_swipe_data gr WHERE gr.imei='".$imei."' AND gr.dt_swipe BETWEEN '".$final_start."' AND '".$final_end."'))";
						$tmp_trip[count($tmp_trip)-1]["rfidlist"]=gettmpCrewFinal($q,$hotspot_name,$crename["crew"]);
						
					}
					else
					{
						$q = " SELECT gr.dt_swipe,gr.lat,gr.lng,gr.rfid,ds.sid,ds.name,dpt.dept_name,dsc.section_name
 												FROM gs_rfid_swipe_data gr left join dstudent ds on gr.rfid=ds.rfid  
												left join dept dpt on dpt.dept_id=ds.dept_id left join dsection dsc on dsc.section_id=ds.section_id
												where gr.imei='".$imei."' AND gr.dt_swipe BETWEEN '".$final_start."' AND '".$final_end."' ORDER BY gr.dt_swipe ASC";
						$tmp_trip[count($tmp_trip)-1]["rfidlist"]=getAllRow($q);
					}
				}
			}
			
			if($emptyreport)
			{
				//code for empty trip
				if(count($tmp_trip)>0)
				{
					
					if(isset($tmp_trip[0]["ognl_time"]))
					{
						$dtf_zero_to=$tmp_trip[0]["ognl_time"];
					}
					else 
					{
						$dtf_zero_to=convUserUTCTimezone($tmp_trip[0]["dt_actual"]);
					}	
					$rout=getRoute($imei, $dtf_zero,$dtf_zero_to, $stop_duration, false);
					$eventT=$rout["events"];
					$takentiming=getTimeDifferenceDetails($dtf_zero, $dtf_zero_to);
				
					$overspeedcount=0;
					for($ie=0;$ie<count($eventT);$ie++)
					{
						if($eventT[$ie][8]=="overspeed")
						$overspeedcount=$overspeedcount+1;
					}
					
					$final_trip[] = array("sno"=>count($final_trip)+1,
								"obj_name"=>'',
								"imei"=>$imei,
								"tripname"=>'Empty Trip',
								"hotspot"=>' - ',
								"create"=>date("Y-m-d",strtotime(convUserTimezone($dtf_zero))),
								"fromplace"=>'',
								"toplace"=>'',
								"fromtime"=>date("H:i",strtotime(convUserTimezone($dtf_zero))),
								"totime"=>date("H:i",strtotime(convUserTimezone($dtf_zero_to))),
								"Afromtime"=>'',
								"Atotime"=>"",
								"avg_speed"=>$rout["avg_speed"],
								"delay"=>"",
								"route_length"=>$rout["route_length"],
								"taken_time"=>$takentiming,
								"ovr_speed"=>$overspeedcount,
								"engine_idle"=>$rout["engine_idle"],
								"event_list"=>$eventT,
								"event_count"=>count($eventT),
								"point"=>'',
								"start_zone"=>'',
								"route_id"=>'',
								"event_id"=>'',
								"trip_data"=>'',
								"ognl_time"=>$dtf_zero,
								"ognl_time_to"=>$dtf_zero_to,
								"end"=>"Empty"
								,"dt_actual"=>$dtf_zero
								,"dt_actual_15"=>$dtf_zero
								,"dt_actual_to"=>$dtf_zero_to
								,"dt_actual_to_15"=>$dtf_zero_to
								);
					
				}
				
				for($velu=0;$velu<count($tmp_trip);$velu++)
				{
					if($velu!=0)
					{
						$tmpchk='';
						if(isset($final_trip[count($final_trip)-1]["ognl_time_to"]))
						{
							$dtf_zero=$final_trip[count($final_trip)-1]["ognl_time_to"];
						}
						else 
						{
							$dtf_zero=convUserUTCTimezone($final_trip[count($final_trip)-1]["dt_actual_to"]);
						}
						
						if(isset($tmp_trip[$velu]["ognl_time"]))
						{
							$dtf_zero_to=$tmp_trip[$velu]["ognl_time"];
						}
						else 
						{
							$dtf_zero_to=$tmp_trip[$velu]["dt_actual"];
						}	
					
						$rout=getRoute($imei, $dtf_zero,$dtf_zero_to, $stop_duration, false);
						if(count($rout) && isset($rout["avg_speed"]))
						{
							$eventT=$rout["events"];
							$takentiming=getTimeDifferenceDetails($dtf_zero, $dtf_zero_to);
							$overspeedcount=0;
							for($ie=0;$ie<count($eventT);$ie++)
							{
								if($eventT[$ie][8]=="overspeed")
								$overspeedcount=$overspeedcount+1;
							}
							$final_trip[] = array("sno"=>count($final_trip)+1,
										"obj_name"=>'',
										"imei"=>$imei,
										"tripname"=>'Empty Trip',
										"hotspot"=>$tmpchk.' - ',
										"create"=>date("Y-m-d",strtotime(convUserTimezone($dtf_zero))),
										"fromplace"=>convUserTimezone($dtf_zero).'',
										"toplace"=>convUserTimezone($dtf_zero_to).'',
										"fromtime"=>date("H:i",strtotime(convUserTimezone($dtf_zero))),
										"totime"=>date("H:i",strtotime(convUserTimezone($dtf_zero_to))),
										"Afromtime"=>'',
										"Atotime"=>"",
										"avg_speed"=>$rout["avg_speed"],
										"delay"=>"",
										"route_length"=>$rout["route_length"],
										"taken_time"=>$takentiming,
										"ovr_speed"=>$overspeedcount,
										"engine_idle"=>$rout["engine_idle"],
										"event_list"=>$eventT,
										"event_count"=>count($eventT),
										"point"=>'',
										"start_zone"=>'',
										"route_id"=>'',
										"event_id"=>'',
										"trip_data"=>'',
										"ognl_time"=>$dtf_zero,
										"ognl_time_to"=>$dtf_zero_to,
										"end"=>"Empty"
										,"dt_actual"=>$dtf_zero
										,"dt_actual_15"=>$dtf_zero
										,"dt_actual_to"=>$dtf_zero_to
										,"dt_actual_to_15"=>$dtf_zero_to
										);
						}
					}
					
					$tmp_trip[$velu]["sno"]=count($final_trip)+1;
					$final_trip[]=$tmp_trip[$velu];
	
				}
			}
			else
				$final_trip=$tmp_trip;
			
		}
	}
	
	$trip["trip"]=$final_trip;
	return $trip;
}
	

function gettmpCrewFinal($query,$routeno,$crew)
{
	global $ms;
	$rtnary=array();
	$r = mysqli_query($ms,$query);
	while($route_data=mysqli_fetch_assoc($r))
	{
		if($route_data["extra"]=="")
		{
			if($route_data["routeno"]==$routeno &&$route_data["gname"]==$crew )
			$route_data["extra"]="O";
			else 
			$route_data["extra"]="N";
		}
		$rtnary[]= $route_data;
	}
	return $rtnary;
}


function decimal_to_time($decimal,$dv) {
	
	return getTimeDifferenceDetails( $dv,$decimal);
	return $decimal." , ".$dv;
    $hours = floor($decimal / 60);
    $minutes = floor($decimal / 60);
    //$seconds = $decimal - (int)$decimal;
    //$seconds = round($seconds * 60);
 
    return str_pad($hours, 2, "0", STR_PAD_LEFT) . ":" . str_pad($minutes, 2, "0", STR_PAD_LEFT);
}


function gettmpCrewDeatil($imei,$dtf,$dtt)
{
	$rtncrew["count"]="";
	$rtncrew["crew"]="";
	
	$q="select count(gr.gname) countv,gr.gname from gs_rfid_swipe_data gr where 
		gr.gname in ('A','B','C') and
		gr.imei='".$imei."' AND gr.dt_swipe BETWEEN '".$dtf."' AND '".$dtt."'
 		order by countv desc ";
	$crew=getAllRow($q);
	if(count($crew)>0)
	{
		$rtncrew["count"]=$crew[0]["countv"];
		$rtncrew["crew"]=$crew[0]["gname"];
	}
	 
	return $rtncrew;
}


function getCrewDeatil($tripname,$dtf,$dtt,$imei)
{
	//convert df -3 hour from actual time coz trip starts before
	$dtf=date('Y-m-d H:i:s',strtotime('- 3 hour',strtotime($dtf)));
	//convert dt +35 minute from actual time coz trip ends later
	$dtt=date('Y-m-d H:i:s',strtotime('+ 35 minutes',strtotime($dtt)));
	
	global $ms,$_SESSION, $la, $user_id,$gsValues;
	$rtncrew["count"]="";
	$rtncrew["crew"]="";
	if( strpos($tripname,'Pickup') !== false ) {
		$q="select tid,unit_name,start_time,end_time,crew,dat from schedule_timev  where user_id='".$user_id."'
			and (start_time BETWEEN '".$dtf."' AND '".$dtt."')";
		$crew=getAllRow($q);
		if(count($crew)>0)
		{
			$rtncrew["count"]=$crew[0]["tid"];
			$rtncrew["crew"]=$crew[0]["unit_name"];
		}
	}
	else if( strpos($tripname,'Drop') !== false ) {
		$q="select tid,unit_name,start_time,end_time,crew,dat from schedule_timev  where user_id='".$user_id."'
			and (end_time BETWEEN '".$dtf."' AND '".$dtt."')";
		$crew=getAllRow($q);
		if(count($crew)>0)
		{
			$rtncrew["count"]=$crew[0]["tid"];
			$rtncrew["crew"]=$crew[0]["unit_name"];
		}
	}
	else 
	{
		$rtncrew=gettmpCrewDeatil($imei,$dtf,$dtt);
	}

	return $rtncrew;
}


?>