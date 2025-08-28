
<?php
function Report($getpost)
{

   global $ms;
   $login=getUserFromToken();
   if(!$login)
   {
      $result['tyv'] = 'e';
      $result['msg'] = 'Invalid Token,Please Login Again!';
      $result['mydata'] =array();
      header('Content-type: application/json');
      echo json_encode($result);
      die;
   }

   $_SESSION = getUserData($login['id']);
   $user_id=$login['id'];

   $_POST["user_id"] = $user_id;
   $_POST["name"] = @$getpost['reporttype'];
   $_POST["type"] = @$getpost['reporttype'];
   $_POST["format"] = @$getpost['format']==""? "html":@$getpost['format'] ;
   $_POST["stop_duration"] = @$getpost['stop_duration']==""? "5":@$getpost['stop_duration'];
   $_POST["speed_limit"] = @$getpost['speed_limit']==""? "70":@$getpost['speed_limit'];
   $_POST["imei"] = @$getpost['imei'];
   $_POST["dtf"] = @$getpost['dtf'];
   $_POST["dtt"] = @$getpost['dtt'];

   $_POST["show_coordinates"] = true;
   $_POST["show_addresses"] = false;
   $_POST["zones_addresses"] =false;
 
   $_POST["zone_ids"] = "";
   $_POST["zone_idsv"] = "";
   $_POST["sensor_names"] = "";
   $_POST["data_items"] = array();
   $_POST["cmd"] ="report";
   $_POST['schedule']="";

   $_POST["event_list"] ="";
   $_POST["email"] ="";

   $data_items =array(); 
   $data_items['general']='route_start,route_end,route_length,move_duration,stop_duration,stop_count,top_speed,avg_speed,overspeed_count,fuel_cost,engine_work,engine_idle,odometer,engine_hours,driver';
   $data_items['general_merged']='route_start,route_end,route_length,move_duration,stop_duration,stop_count,top_speed,avg_speed,overspeed_count,fuel_cost,engine_work,engine_idle,odometer,engine_hours,driver,total';
   $data_items['daily_km']='route_start,route_end,route_length,move_duration,stop_duration,stop_count,top_speed,avg_speed,overspeed_count,fuel_cost,engine_work,engine_idle,odometer,engine_hours,driver,total';
   $data_items['object_info']='imei,transport_model,vin,plate_number,odometer,engine_hours,driver,trailer,gps_device,sim_card_number,group_name,fueltype,fuel1,fuel2,temp1,temp2';
   $data_items['current_position']='time,position,speed,altitude,angle,status,odometer,engine_hours';
   $data_items['drives_stops']='status,start,end,duration,move_duration,stop_duration,route_length,top_speed,avg_speed,fuel_cost,engine_work,engine_idle';
   $data_items['travel_sheet']='time_a,position_a,time_b,position_b,duration,length,fuel_cost,total';
   $data_items['overspeed']='start,end,duration,top_speed,avg_speed,overspeed_position';
   $data_items['underspeed']='start,end,duration,top_speed,avg_speed,underspeed_position';
   $data_items['zone_in_out']='zone_in,zone_out,duration,zone_name,zone_position';
   $data_items['events']='time,event,event_position,total';
   $data_items['service']='service,last_service,status';
   $data_items['rag']='overspeed_score,harsh_acceleration_score,harsh_braking_score,harsh_cornering_score';
   $data_items['logic_sensors']='sensor,activation_time,deactivation_time,duration,activation_position,deactivation_position';
   $data_items['fuelfillings']='time,position,before,after,filled,sensor,driver,total';
   $data_items['fuelthefts']='time,position,before,after,stolen,sensor,driver,total';
   $data_items['tripreport']='VEHICLENO,TRIPNAME,HOTSPOTNAME,DATE,ROUTE_START,ROUTE_END,PROUTE_START,PROUTE_END,AROUTE_START,AROUTE_END,AVG_SPEED,DELAY,TAKENKM,DURATION,OVERSPEED_COUNT,OVRTIMEPARKING,VIEW_DETAIL';
   $data_items['offline']='imei,object,date,duration,altitude,angle,speed,position,GPS_DEVICE,sim_card_number,GROUP,fueltype,fuel1,fuel2,temp1,temp2';
   $data_items['maintenancereport']='CLIENT_ID,STAFFID,SITE_LOCATION,SCHEDULE,OBJECT,IMEI,WORK,IN_TIME,OUT_TIME,UNDER_WARRENTY,NOTE,TYPES_WORK';
   $data_items['tempreport']='TIME,POSITION,ALTITUDE,SPEED,IGNITION,TEMP1,TEMP2,TEMP3';
   $data_items['booking_report']='control_user_name,create_date,book_by,emergency_address,contact_no,emergency_reason,people_count,age,conscious,breathing,gender,person_name,note1,status,app_user_id,self_others,allocated_driver_id,allocated_driver_phone,allocated_vehicleno,allocated_imei,driver_accept_time,vehicle_reached_time,pickedup_time,reached_dest_time';
   $data_items['rfidtripreport']='VNAME,VEHICLENO,VENDOR,DRIVER,DRIVERPH,DRIVERRFIDID,STARTTIME,ENDTIME,MILEAGE';$data_items["derfidtripreport"]='';
   $data_items["volvoreport"]='';
   $data_items["fuelana"]='';
   $data_items["fuelgraph"]='';
   $data_items["tempgraph"]='';
   $data_items["ifsinfograph"]='';
   $data_items["acc_graph"] =''; 
   $data_items["apeed_graph"] =''; 
   $data_items["altitude_graph"] =''; 
   $data_items["fuellevel_graph"] =''; 
   $data_items["temperature_graph"] =''; 
   $data_items["sensor_graph"] ='';


      $_POST["data_items"] =  @$data_items[$_POST["type"]];


 
         $postdata = http_build_query(
                     $_POST);
         

         $opts = array('http' =>
               array(
                  'method'  => 'POST',
                  'header'  => 'Content-type: application/x-www-form-urlencoded',
                  'content' => $postdata
               )
         );
         
         $context  = stream_context_create($opts);
         
         global $gsValues;
      
         $url = $gsValues['URL_ROOT'].'/func/fn_reports.gen.php';
         //$url ='http://localhost/tracka/trackmigwc/func/fn_reports.gen.php';

         $resultrpt = file_get_contents($url, false, $context);

         //echo $resultrpt;die;

   $result['tyv'] = 's';
   $result['msg'] = '';
   $result['mydata'] =$resultrpt;

   header('Content-type: application/json');
   echo json_encode($result);
   die;
}


?>
