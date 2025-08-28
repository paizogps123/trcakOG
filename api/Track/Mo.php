<?php
//code written by vetrivel.N - starts here
   include ('../../init.php');
   //include ('../Base.php');  
   include ('../../func/fn_common.php');
   include ('../../func/fn_route.php');
   include ('../../tools/email.php');
   //date_default_timezone_set("Asia/Kolkata");
   
  function get_imei_str($rowuimei)
  {
	$imeistr ="";
	$imeidata = explode(",",$rowuimei);
	for($i=0;$i<count($imeidata);$i++)
	{
		if($imeistr=="")
		{
		 $imeistr = "'".$imeidata[$i]."'";
		}
		else
		{
		 $imeistr = $imeistr.",'".$imeidata[$i]."'";
		}
	}
	return $imeistr;
  
  }

   function Get_History($getpost)
   {

      ///*
      global $ms;

      $imei = $getpost['imei'];
      $dtf = $getpost['dtf'];
      $dtt = $getpost['dtt'];
      $min_stop_duration = $getpost['stopduration'];

      switch ($min_stop_duration)
      {
         case "> 1 Min":
            $min_stop_duration=1;
            break;
         case "> 2 Min":
            $min_stop_duration=2;
            break;
         case "> 5 Min":
            $min_stop_duration=5;
            break;
         case "> 10 Min":
            $min_stop_duration=10;
            break;
         case "> 20 Min":
            $min_stop_duration=20;
            break;
         case "> 30 Min":
            $min_stop_duration=30;
            break;
         case "> 1 H":
            $min_stop_duration=60;
            break;
         case "> 2 H":
            $min_stop_duration=120;
            break;
         case "> 5 H":
            $min_stop_duration=300;
            break;
      }

    $login=getUserFromToken();
     if(!isset($login))
     {
      $result['tyv'] = 'e';
      $result['msg'] = 'Invalid Token,Please Login Again!';
      $result['mydata'] =array();
      header('Content-type: application/json');
      echo json_encode($result);
      die;
     }
      $user_id=$login['id'];

      $q = "select  id,active,privileges,manager_id from gs_users WHERE id=".$user_id;
      $r = mysqli_query($ms,$q);
      if ($row = mysqli_fetch_assoc($r))
      {
         if($row['active']==true)
         {
            $aryprivileges = json_decode($row["privileges"], true);
            $row["type"]=$aryprivileges["type"];

            if($aryprivileges["type"]=="subuser"){
               $user_id=$row['manager_id'];
            }
             
            if($aryprivileges["history"]==false || $aryprivileges["history"]=="false")
      	   {
		/*
      	         $result['tyv'] = 'e';
                  $result['msg'] = 'No data found!';
                  header('Content-type: application/json');
                  echo json_encode($result);
                  die;
		*/
      	   }

            $_SESSION=array();

            if (!checkUserToObjectPrivileges($user_id, $imei))
            {
               $result['tyv'] = 'e';
               $result['msg'] = 'No data found(Object)!';
               header('Content-type: application/json');
               echo json_encode($result);
               die;
            }
            
            setUserSessionSettings($user_id);
            loadLanguage($_SESSION['language'], $_SESSION["units"]);

            $dtf=$dtf.":00";$dtt=$dtt.":00";
            //date_default_timezone_set('UTC');
		
	   $user_res= array(1564,1676,1677,1678,1679,1686);
	  
           if(in_array($user_id, $user_res))
	   {
		$df = strtotime($dtf);
		$dtf2 = "2021-9-01 00:00:00";
		$df2 = strtotime($dtf2);
		if($df<$df2)
		{
		   $dtf = "2021-9-01 00:00:00";


			$myfile = fopen("vvvR.txt", "a");
	fwrite($myfile,json_encode($_POST) );
	fwrite($myfile, "\n");
fwrite($myfile,$dtf );
	fwrite($myfile, "\n");

	fclose($myfile);


		   
		}
		
	   }

            // $result = getRoute($imei, convUserUTCTimezone($dtf), convUserUTCTimezone($dtt), $min_stop_duration, true);
            $result = getRoute($imei, $dtf, $dtt, $min_stop_duration, true,false,false);

            
            for($irot=0;$irot<count($result["route"]);$irot++)
            {
               unset($result["route"][$irot][6]);
               $result["route"][$irot] = array_values($result["route"][$irot]);
               //$result["route"][$irot]=$unsetparm;
            }
            //$result['route'] = array_values($result['route']);
            $result['tyv'] = 's';
            $result['msg'] = '';
         }
         else
         {
            $result['tyv'] = 'e';
            $result['msg'] = 'Your Account Has Been Deactivated,Contact Admin!';

         }
      }
      else
      {
         $result['tyv'] = 'e';
         $result['msg'] = 'Invalid Login Details!';
      }

      header('Content-type: application/json');
      echo json_encode($result);
      //header("Connection: close");
      //header("Content-length: " . (string)ob_get_length());

      die;
   }
   
   
   function Get_User($getpost)
   {
      global $ms;

      $q = "select  id,active,  privileges,  username,email,  timezone from gs_users WHERE
      ( username='".$getpost["username"]."') and  password='".md5($getpost["password"])."' " ;
      $r = mysqli_query($ms,$q);
     
      if ($row = mysqli_fetch_assoc($r))
      {
         if($row['active']=='true')
         {
            //$token=encrypt($row['id']);
         $token=encrypt(date("YmdHis").$row["id"]);
         $qt = "insert into api_token(user_id,token,status) values('".$row['id']."','".$token."','A')" ;
         $rt = mysqli_query($ms,$qt);

            $limei = array('1552','1553','1554','1555','1556','1557','1558','1559','1560','1561','1562','1563');
            
            if (in_array($row['id'], $limei))
              {
               $last_id = $ms->insert_id;
               $ddvalue=array('username'=>$row['username'],'Login Time'=>convUserUTCTimezoneret(gmdate("Y-m-d H:i:s")),'Token id'=>$last_id,'user_id'=>$row['id']);
               $myfile = fopen("singletrack_usage/".date("d-m-Y", strtotime($ddvalue['Login Time']))."magna_user_login.txt", "a");
               fwrite($myfile,json_encode($ddvalue));
               fwrite($myfile, "\n");
               fclose($myfile);
              }
   
            $aryprivileges = json_decode($row["privileges"], true);
            $row["type"]=$aryprivileges["type"];
            $row["history"]=$aryprivileges["history"];
	    $row["dailykm"]= @$aryprivileges["history"];
            $row["reports"]=$aryprivileges["reports"];
            $row["object_control"]=$aryprivileges["object_control"];
            $row["chat"]=$aryprivileges["chat"];
            $row["token"]=$token;           
            unset($row['privileges']);
            unset($row['id']); 

            $result['tyv'] = 's';
            $result['msg'] = '';
            $result['mydata'] =($row);
         }
         else
         {
            $result['tyv'] = 'e';
            $result['msg'] = 'Your Account Has Been Deactivated,Contact Admin!';

         }

      }
      else
      {
         $result['tyv'] = 'e';
         $result['msg'] = 'Invalid Login Details!';
      }

      header('Content-type: application/json');
      echo json_encode($result);
      die;
   }

   function Get_Voice($getpost)
   {
      global $ms;

      $q = "select  id,active,  privileges,  username,email,  timezone from gs_users WHERE
      ( username='".$getpost["username"]."') and  password='".md5($getpost["password"])."'  " ;


      $r = mysqli_query($ms,$q);
     
      if ($row = mysqli_fetch_assoc($r))
      {
         if($row['active']==true)
         {
            //$token=encrypt($row['id']);
         $token=encrypt(date("YmdHis").random_string(10));
         $qt = "insert into api_token(user_id,token) values('".$row['id']."','".$token."')" ;
         $rt = mysqli_query($ms,$qt);
         
            $aryprivileges = json_decode($row["privileges"], true);
            $row["type"]=$aryprivileges["type"];
            $row["history"]=$aryprivileges["history"];
            $row["reports"]=$aryprivileges["reports"];
            $row["object_control"]=$aryprivileges["object_control"];
            $row["chat"]=$aryprivileges["chat"];
            $row["token"]=$token;
            unset($row['privileges']);
            unset($row['id']);

            if($row["type"]=="super_admin" || $row["type"]=="manager" || $row["type"]=="admin" || $row["type"]=="user" )
            {
               $result['tyv'] = 's';
               $result['msg'] = '';
               $result['mydata'] =($row);
            }
            else
            {
               $result['tyv'] = 'e';
               $result['msg'] = 'Invalid Access';

            }
         }
         else
         {
            $result['tyv'] = 'e';
            $result['msg'] = 'Your Account Has Been Deactivated,Contact Admin!';

         }

      }
      else
      {
         $result['tyv'] = 'e';
         $result['msg'] = 'Invalid Login Details!';
      }

      header('Content-type: application/json');
      echo json_encode($result);
      die;
   }
   
   

   function Get_Imei($getpost)
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
     
      $q="";

      $qu = "select  id,active,  privileges,  username,email,  timezone from gs_users WHERE
     id='".$login["id"]."' " ;
    $ru = mysqli_query($ms,$qu);
    if ($rowu = mysqli_fetch_assoc($ru))
    {
   

     $aryprivileges = json_decode($rowu["privileges"], true);
     $rowu["type"]=$aryprivileges["type"];
      
     if($rowu["type"]=="subuser")
     {
      $rowu["imei"]=$aryprivileges["imei"];
      $imeistr=get_imei_str($rowu["imei"]);
      
    
		
      //$q="select imei,dt_server,dt_tracker,lat,lng,altitude,angle,speed,loc_valid,params,name,tail_points,tail_color,odometer,engine_hours,sim_number,device,fuel1,temp1
        //from gs_objects where active='true' and imei in (" . $rowu["imei"] .") ";
      
       $q="select gs.imei,gs.dt_server,gs.dt_tracker,gs.lat,gs.lng,gs.altitude,gs.angle,gs.speed,gs.loc_valid,gs.params,
         gs.name,gs.tail_points,gs.tail_color,gs.sim_number,gs.device,gs.odometer,gs.engine_hours,gs.fuel1,gs.temp1,gos.param from gs_objects gs 
         left join gs_object_sensors  gos on gs.imei=gos.imei  and gos.name='A/C'
         where gs.active='true' and gs.imei in (" . $imeistr.") ";
       
     }
     else
     {
      //$q="select imei,dt_server,dt_tracker,lat,lng,altitude,angle,speed,loc_valid,params,name,tail_points,tail_color,odometer,engine_hours,sim_number,device,fuel1,temp1
        //from gs_objects where active='true' and imei in (select imei from gs_user_objects  where user_id='".decrypt($getpost["token"])."') ";
        $q=" select gs.imei,gs.dt_server,gs.dt_tracker,gs.lat,gs.lng,gs.altitude,gs.angle,gs.speed,gs.loc_valid,gs.params,
         gs.name,gs.tail_points,gs.tail_color,gs.sim_number,gs.device,gs.odometer,gs.engine_hours,gs.fuel1,gs.temp1,gos.param from gs_objects gs 
         join gs_user_objects  guo on gs.imei=guo.imei
         left join gs_object_sensors  gos on gs.imei=gos.imei  and gos.name='A/C'
         where gs.active='true' and guo.user_id='".$login["id"]."'";
      
     }
     
      $r = mysqli_query($ms,$q);

      if (!$r)
      {
         $result['tyv'] = 'e';
         $result['msg'] = 'No Data Found!';
      }
      else
      {
         $result_array =array();
         while($row = mysqli_fetch_assoc($r))
         {

            //$row["engine_hours"]= floor($row['engine_hours'] / 60 / 60);
            $row["engine_hours"]=getTimeDetailsAPI($row['engine_hours'], false);
            
            $tracker_time = strtotime($row["dt_tracker"]);
            $current_time = strtotime(gmdate("Y-m-d H:i:s"));

            $tmp=round(abs($tracker_time - $current_time) / 60,2);
            if ($tmp<= 10)
            { $row["loc_valid"]="1";}
            else
            { $row["loc_valid"]="0";}

            // $row["dt_tracker"]=date("Y-m-d H:i:s", strtotime($row["dt_tracker"]."+5 hours +30 minutes"));
            $row["dt_tracker"]=$row["dt_tracker"];
            $aryparams = paramsToArray($row['params']);
            if(!isset($aryparams["acc"]) )
            {
               $row["acc"]=0;
            }
            else
            {
               $row["acc"]=$aryparams["acc"];

            }

            if(!isset($aryparams["fuel1"]) )
            {
               $row["fueld1"]="-";
            }
            else
            {
               $row["fueld1"]=$aryparams["fuel1"];
               $fuel_sensors = getSensorFromType($row["imei"], 'fuel');
               if($fuel_sensors!=false)
               {
                  $fuelvalue = getSensorValue($aryparams, $fuel_sensors[0]);
                  $row["fueld1"] = $fuelvalue["value"];
               }
            }

            if(!isset($aryparams["temp1"]) )
            {
               $row["tempd1"]="-";
            }
            else
            {
               $row["tempd1"]=$aryparams["temp1"];
            }

               
            if($row["fuel1"]=="No Sensor" ||$row["fuel1"]=="" || $row["fueld1"]==".00" || $row["fueld1"]=="" )
            {
               $row["fueld1"]="-";
            }
            if($row["temp1"]=="NO" || $row["temp1"]==null )
            {
               $row["tempd1"]="-";
            }
               
            $row["ac"]=getParamValue($aryparams, $row["param"]);
            
            
            //$fuelformula=formula($row["imei"]);
               
            //if($fuelformula!="" && $row["fueld1"]!="-" )
            //{
            // $row["fueld1"] =floatval($fuelformula)*floatval($row["fueld1"]);
            //}
            
            unset($row['params']);
            $result_array[]=$row;

         }
         $result['tyv'] = 's';
         $result['msg'] = '';
         $result['mydata'] =( $result_array);
      }
      
    }
    else 
      {
         $result['tyv'] = 'e';
         $result['msg'] = 'No Data Found!';
      }

   

       
      header('Content-type: application/json');
      echo json_encode($result);
      die;
       

   }


   function  formula ($imei)
   {
      global $ms;
      $q = "select formula from gs_object_sensors where param='fuel1' and type='fuel' and imei='".$imei."' " ;
       
      $r = mysqli_query($ms,$q);
      if ($row = mysqli_fetch_assoc($r))
      {
         $fuelformula=$row["formula"];
         $formulaonly = explode("|", $fuelformula);
         if(count($formulaonly)>1)
         return $formulaonly[1];
         else
         return "";
      }
      else
      {
         return "";
      }
   }
    
   function Get_Events($getpost)
   {
      global $ms;

   $login=getUserFromToken();
     if(!isset($login))
     {
      $result['tyv'] = 'e';
      $result['msg'] = 'Invalid Token,Please Login Again!';
      $result['mydata'] =array();
      header('Content-type: application/json');
      echo json_encode($result);
      die;
     }
     
      $qobjectdata="select fuel1,temp1  from gs_objects where imei='".$getpost["imei"]."';";
      $result_object=getfirstrow($qobjectdata);
      if($result_object==false){
         $result['tyv'] = 'e';
         $result['msg'] = 'No Data Found!';
      }
      else {
          
         //$q = "select event_id,event_desc,dt_server,dt_tracker,lat,lng,altitude,angle,speed,params,type,imei from gs_user_events_data where  imei='".$getpost["imei"]."' and user_id='".decrypt($getpost["token"])."' ORDER BY event_id DESC LIMIT 50 " ;
         //$q = "select event_id,event_desc,dt_server,dt_tracker,lat,lng,altitude,angle,speed,params,type,imei from gs_user_events_data where  imei='".$getpost["imei"]."' ORDER BY event_id DESC LIMIT 50 " ;
         $q = "select ged.event_id,ged.event_desc,ged.dt_server,ged.dt_tracker,ged.lat,ged.lng,ged.altitude,ged.angle,ged.speed,
            ged.params,ged.type,ged.imei,gos.param
            from gs_user_events_data ged
            left join gs_object_sensors  gos on ged.imei=gos.imei and gos.name='A/C'
            where  ged.imei='".$getpost["imei"]."' ORDER BY ged.event_id DESC LIMIT 50 " ;

         $r = mysqli_query($ms,$q);

         if (!$r)
         {
            $result['tyv'] = 'e';
            $result['msg'] = 'No Data Found!';
         }
         else
         {
            $result_array =array();
            while($row = mysqli_fetch_assoc($r))
            {
                
               // $row["dt_tracker"]=date("Y-m-d H:i:s", strtotime($row["dt_tracker"]."+5 hours +30 minutes"));
               $row["dt_tracker"]=$row["dt_tracker"];

               $aryparams = paramsToArray($row['params']);
               if(!isset($aryparams["acc"]) )
               {
                  $row["acc"]=0;
               }
               else
               {
                  $row["acc"]=$aryparams["acc"];

               }
                
               if(!isset($aryparams["fuel1"]) )
               {
                  $row["fueld1"]="-";
               }
               else
               {
                  $row["fueld1"]=$aryparams["fuel1"];
               }
                
               if(!isset($aryparams["temp1"]) )
               {
                  $row["tempd1"]="-";
               }
               else
               {
                  $row["tempd1"]=$aryparams["temp1"];
               }
               
               if(!isset($aryparams["fuel1"]) || $row["fueld1"]>100 ||$result_object["fuel1"]=="No Sensor" ||$result_object["fuel1"]=="" || $row["fueld1"]==".00" || $row["fueld1"]=="" )
               {
                  $row["fueld1"]="-";
               }
               else
               {
                  $row["fueld1"]=$aryparams["fuel1"];
                  $fuel_sensors = getSensorFromType($row["imei"], 'fuel');
                  if($fuel_sensors!=false)
                  {
                     $fuelvalue = getSensorValue($aryparams, $fuel_sensors[0]);
                     $row["fueld1"] = $fuelvalue["value"];
                  }
               }
            
                
               if($result_object["temp1"]=="NO" || $result_object["temp1"]==null )
               {
                  $row["tempd1"]="-";
               }

               $row["ac"]=getParamValue($aryparams, $row["param"]);
               //$fuelformula=formula($getpost["imei"]);
                
               //if($fuelformula!="" && $row["fueld1"]!="-" )
               //{
                  //$row["fueld1"] =floatval($fuelformula)*floatval($row["fueld1"]);
               //}
               unset($row['params']);
               $result_array[]=$row;
                

            }
            $result['tyv'] = 's';
            $result['msg'] = '';
            $result['mydata'] =( $result_array);

         }

      }

      header('Content-type: application/json');
      echo json_encode($result);
      die;
   }
    
   function getfirstrow($query)
   {
      global $ms;
      $query = mysqli_query($ms,$query);
      if ($row = mysqli_fetch_assoc($query))
      {
         return $row;
      }
      else {
         return false;
      }
   }

   function Get_Single_Track($getpost)
   {
     global $ms;
     //  start ->creat by nandha from mani sir
     create_magna_log($_POST);
     // end

     $login=getUserFromToken();
     if(!isset($login))
     {
      $result['tyv'] = 'e';
      $result['msg'] = 'Invalid Token,Please Login Again!';
      $result['mydata'] =array();
      header('Content-type: application/json');
      echo json_encode($result);
      die;
     }
     //$q="select imei,dt_server,dt_tracker,lat,lng,altitude,angle,speed,loc_valid,params,name,tail_points,tail_color,odometer,engine_hours,sim_number,device,fuel1,temp1
       //  from gs_objects where active='true' and imei in (select imei from gs_user_objects  where user_id='".decrypt($getpost["token"])."' and imei='".$getpost["imei"]."')";

//      $q="select imei,dt_server,dt_tracker,lat,lng,altitude,angle,speed,loc_valid,params,name,tail_points,tail_color,odometer,engine_hours,sim_number,device,fuel1,temp1
//         from gs_objects where active='true' and imei='".$getpost["imei"]."' ";
     
     $q="select go.imei,go.dt_server,go.dt_tracker,go.lat,go.lng,go.altitude,go.angle,go.speed,go.loc_valid,go.params
         ,go.name,go.tail_points,go.tail_color,go.odometer,go.engine_hours,go.sim_number,go.device,go.fuel1,go.temp1,gos.param
         from gs_objects go left join gs_object_sensors  gos on go.imei=gos.imei  and gos.name='A/C'
         where go.imei='".$getpost["imei"]."' and go.active='true' ";
     
     $r = mysqli_query($ms,$q);
     if (!$r)
     {
      $result['tyv'] = 'e';
      $result['msg'] = 'No Data Found!';
     }
     else
     {
       
      $result_array =array();
      while($row = mysqli_fetch_assoc($r))
      {

         //$row["engine_hours"]= floor($row['engine_hours'] / 60 / 60);
            $row["engine_hours"]=getTimeDetailsAPI($row['engine_hours'], false);
         
         
         $tracker_time = strtotime($row["dt_tracker"]);
            $current_time = strtotime(gmdate("Y-m-d H:i:s"));
            $tmp=round(abs($tracker_time - $current_time) / 60,2);
         if ($tmp<= 10)
         { $row["loc_valid"]="1";}
         else 
         { $row["loc_valid"]="0";}
         
         // $row["dt_tracker"]=date("Y-m-d H:i:s", strtotime($row["dt_tracker"]."+5 hours +30 minutes"));
         $row["dt_tracker"]=$row["dt_tracker"];
         $aryparams = paramsToArray($row['params']);
         if(!isset($aryparams["acc"]) )
               {
                  $row["acc"]=0;
               }
               else
               {
                  $row["acc"]=$aryparams["acc"];

               }
                
               if(!isset($aryparams["fuel1"]) )
               {
                  $row["fueld1"]="-";
               }
               else
               {
                  $row["fueld1"]=$aryparams["fuel1"];
                  $fuel_sensors = getSensorFromType($row["imei"], 'fuel');
                  if($fuel_sensors!=false)
                  {
                     $fuelvalue = getSensorValue($aryparams, $fuel_sensors[0]);
                     $row["fueld1"] = $fuelvalue["value"];
                  }
               }
                
               if(!isset($aryparams["temp1"]) )
               {
                  $row["tempd1"]="-";
               }
               else
               {
                  $row["tempd1"]=$aryparams["temp1"];
               }
         
          
         if( $row["fuel1"]=="No Sensor" ||$row["fuel1"]=="" || $row["fueld1"]==".00" || $row["fueld1"]=="" )
         {
            $row["fueld1"]="-";
         }
         if($row["temp1"]=="NO" || $row["temp1"]==null )
         {
            $row["tempd1"]="-";
         }
          
         $fuelformula=formula($row["imei"]);
          
         if($fuelformula!="" && $row["fueld1"]!="-" )
         {
            $row["fueld1"] =floatval($fuelformula)*floatval($row["fueld1"]);
         }
         
         $row["ac"]=getParamValue($aryparams, $row["param"]);
            
         unset($row['params']);
         $result_array[]=$row;

      }
      $result['tyv'] = 's';
      $result['msg'] = '';
      $result['mydata'] =( $result_array);
     }

      
     header('Content-type: application/json');
     echo json_encode($result);
     die;
      

   }
        
   function getUserFromToken()
   {
      global $ms;
      $rtn_ary=false;
      $q = "SELECT gu.* FROM gs_users gu JOIN api_token at ON at.user_id=gu.id WHERE at.token='".$_POST["token"]."'";
      $r = mysqli_query($ms,$q);
      if($r)
      {
         if ($row = mysqli_fetch_assoc($r))
	 {
            $rtn_ary= $row;
	 }
      }
      return $rtn_ary;
   }
   

function Get_Daily_km($getpost){
   global $ms;
   $login=getUserFromToken();
   $user_id=$login['id'];
   // $user_id=decrypt($getpost['token']);
   if(checkReportPrivilege('45',$user_id)==true){
      $result_array=array();
      // $onlydate=array();


  $user_res= array(1564,1676,1677,1678,1679,1686);
	  
           if(in_array($user_id, $user_res))
	   {
		$df = strtotime($getpost['dtf']);
		$dtf2 = "2021-9-01 00:00:00";
		$df2 = strtotime($dtf2);
		if($df<$df2)
		{
		   $getpost['dtf'] = "2021-9-01 00:00:00";


			$myfile = fopen("vvvR.txt", "a");
	fwrite($myfile,json_encode($_POST) );
	fwrite($myfile, "\n");
fwrite($myfile,$dtf );
	fwrite($myfile, "\n");

	fclose($myfile);


		   
		}
		
	   }



      $date_from = strtotime(date('Y-m-d',strtotime($getpost['dtf'])));
      $date_to = strtotime(date('Y-m-d',strtotime($getpost['dtt'])));
      $q="SELECT * FROM gs_users a LEFT JOIN gs_user_objects b ON a.id=b.user_id Where b.user_id='".$user_id."'";
      
      $r=mysqli_query($ms,$q);
      
      if(mysqli_num_rows($r)){

         while($row=mysqli_fetch_assoc($r)){
         $date=array();
            for ($i=$date_from; $i<$date_to; $i+=86400) {

               $qu="SELECT * FROM ckm_dailykm where dt_create='".date("Y-m-d", $i)."' and imei='".$row['imei']."' ";
               // $qu="SELECT * FROM ckm_dailykm a LEFT JOIN gs_user_objects b ON a.imei=b.imei WHERE a.dt_create='".date("Y-m-d", $i)."' AND b.user_id='".$user_id."'"
               $ru=mysqli_query($ms,$qu);
               if(mysqli_num_rows($ru)>0){
                  while($rowu=mysqli_fetch_assoc($ru)){
                     $date[]=array("date"=>date("Y-m-d", $i),"distance"=>$rowu['distance']);
                  } 
               }else{
                  $date[]=array("date"=>date("Y-m-d", $i),"distance"=>'0');
               }                      
            }
            $obname["object"]=getObjectName($row['imei']);
            $obname["km"]=$date;
            $result_array[]=$obname;

            $result['tyv'] = 's';
            $result['msg'] = '';
            $result['mydata'] =( $result_array);
            
         }

      }else{
         $result['tyv'] = 'e';
         $result['msg'] = 'No Data Found';
         $result['mydata'] =( $result_array);
      }
   }else{
      $result['tyv'] = 'e';
      $result['msg'] = 'No Permission';
      $result['mydata'] =( $result_array);
   }

header('Content-type: application/json');
echo json_encode($result);
die;
}

function checkReportPrivilege($id,$user_id){
   global $ms;
   $q="SELECT * FROM c_report_group a LEFT JOIN c_report_list b ON a.gid=b.group_id LEFT JOIN c_report_privilege c ON b.rid=c.rid WHERE c.user_id='".$user_id."' AND b.active='A' AND c.active='A' AND c.rid='".$id."'";
   $r=mysqli_query($ms,$q);
   if(mysqli_num_rows($r)>0){
      return true;
   }else{
      return false;
   }   
}


 function Get_Report_List($getpost)
{
   global $ms;
   loadLanguage("english");
   global $la;
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

   $rptlst=array();
   $rptlstraw=reportlist_selecteduser($login["id"]);

   for ($i=0; $i <count($rptlstraw) ; $i++) 
   { 
      if($rptlstraw[$i]["active"]=="A")
      {
         $rptlstraw[$i]["report_lng"]=$la[$rptlstraw[$i]["report_lng"]];
         $rptlst[]=$rptlstraw[$i];
      }
   }

   $result['tyv'] = 's';
   $result['msg'] = '';
   $result['mydata'] =$rptlst;

   header('Content-type: application/json');
   echo json_encode($result);
   die;
}

 function Daily_Analysis($getpost)
{
   global $ms;
   loadLanguage("english");
   global $la;
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
	
   $privileges = json_decode($login["privileges"],true);
   $utype = $privileges["type"];
   
            if($privileges["history"]==false || $privileges["history"]=="false")
	    {
		/*
	       $result['tyv'] = 'e';
               $result['msg'] = 'No data found!';
               header('Content-type: application/json');
               echo json_encode($result);
               die;
		*/
	    }

   
   	$user_id = $login["id"];
   	$imei = $getpost['imei'];
	$dtf = $getpost['dtf'];
	$dtt = $getpost['dtt'];
	$min_stop_duration = $getpost['stop_duration'];
	

  $user_res= array(1564,1676,1677,1678,1679,1686);
	  
           if(in_array($user_id, $user_res))
	   {
		$df = strtotime($dtf);
		$dtf2 = "2021-9-01 00:00:00";
		$df2 = strtotime($dtf2);
		if($df<$df2)
		{
		   $dtf = "2021-9-01 00:00:00";


			$myfile = fopen("vvvR.txt", "a");
	fwrite($myfile,json_encode($_POST) );
	fwrite($myfile, "\n");
fwrite($myfile,$dtf );
	fwrite($myfile, "\n");

	fclose($myfile);


		   
		}
		
	   }



	if($utype=="subuser")
	{
		$user_id=$login["manager_id"];
	}
	
	if (!checkUserToObjectPrivileges($user_id, $imei))
	{
		 $result['tyv'] = 'e';
	      $result['msg'] = 'Sorry, User don\'t have a access for this object! ';
	      $result['mydata'] =array();
	      header('Content-type: application/json');
	      echo json_encode($result);
		die;
	}
	
	$resultda = getRoute($imei, convUserUTCTimezoneset($dtf), convUserUTCTimezoneset($dtt), $min_stop_duration, true);

	for($i=0;$i<count($resultda['route']);$i++){
		// echo $resultda['route'][$i][0];
		$resultda['route'][$i][0]=convUserUTCTimezoneret($resultda['route'][$i][0]);
	}
	for($j=0;$j<count($resultda['stops']);$j++){
		$resultda['stops'][$j][6]=convUserUTCTimezoneret($resultda['stops'][$j][6]);
		$resultda['stops'][$j][7]=convUserUTCTimezoneret($resultda['stops'][$j][7]);
	}
	for($k=0;$k<count($resultda['drives']);$k++){
		$resultda['drives'][$k][4]=convUserUTCTimezoneret($resultda['drives'][$k][4]);
		$resultda['drives'][$k][5]=convUserUTCTimezoneret($resultda['drives'][$k][5]);
	}

	if(isset($resultda["route"]) && count($resultda["route"])>1)
	{
		$resultda["first_loc"]=$resultda["route"][0];
		$resultda["last_loc"]=$resultda["route"][count($resultda["route"])-1];
	}
	
	$ft=get_fueldata_offline($imei,convUserUTCTimezoneset($dtt));
	
	$resultda["theft"]=$ft["theft"];
	$resultda["fill"]=$ft["fill"];
		
    $result['tyv'] = 's';
    $result['msg'] = '';
    $result['mydata'] =$resultda;

   header('Content-type: application/json');
   echo json_encode($result);
   die;
} 

function convUserUTCTimezoneset($dt){
   $dt = gmdate("Y-m-d H:i:s", strtotime($dt.'-5 hours -30 minutes'));
   return $dt;
}  

function convUserUTCTimezoneret($dt){
   $dt = gmdate("Y-m-d H:i:s", strtotime($dt.'+5 hours +30 minutes'));
   return $dt;
}  

function create_magna_log($post){
   global $ms;
   $q="SELECT at_id,user_id FROM api_token where token='".$post['token']."' and user_id IN ('1552','1553','1554','1555','1556','1557','1558','1559','1560','1561','1562','1563')";
   $r=mysqli_query($ms,$q);
   if(mysqli_num_rows($r)>0){
      $row=mysqli_fetch_assoc($r);
      $_POST['token']=$row['at_id'];
      $_POST['user_id']=$row['user_id'];
      $_POST['date&time']=convUserUTCTimezoneret(gmdate("Y-m-d H:i:s"));
      $myfile = fopen("singletrack_usage/".date("d-m-Y", strtotime($_POST['date&time']))."data_log.txt", "a");
      fwrite($myfile,json_encode($_POST));
      fwrite($myfile, "\n");
      fclose($myfile);
   }
}


?>
