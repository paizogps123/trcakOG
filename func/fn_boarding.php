<?
date_default_timezone_set("Asia/Kolkata");

	session_start();
	include ('../init.php');
	include ('fn_common.php');
	checkUserSession();
	
	loadLanguage($_SESSION["language"]);
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
	function fnchktripdata($imei,$user_id,$uid,$week_days,$dtftot,$dtttot,$eventid,$today,$totalhours)
	{
		global  $la,$ms;

		$diFend=23.59;
		$diTfrom=0;

		$irtn=true;
		$q = "select * from droute_events WHERE active='true' and `imei`='".$imei."' and user_id='".$user_id."' and event_id != '".$eventid."' order by tfh,tfm ";
        $r = mysqli_query($ms,$q);
        if($r)
        {
        	while($row = mysqli_fetch_array($r,MYSQLI_ASSOC))
        	{
        	
   				$day_of_week = explode(';', $row["week_days"]);
                for($idow=0;$idow<count($day_of_week);$idow++)
                {
                	$day_of_weeksngl=$day_of_week[$idow];
                	if($day_of_weeksngl!="")
                	{
                	$week_daysgvn = explode(';', $week_days);
                	for($idgvn=0;$idgvn<count($week_daysgvn);$idgvn++)
                	{
                		$day_of_weekgv=$week_daysgvn[$idgvn];
                		if (($day_of_weeksngl == $day_of_weekgv) && ($idow==$idgvn))
	                	{                               	                     
                    		$dttchkF=($row['tfh'].'.'.$row['tfm']);
                    		$dttchkT=($row['tth'].'.'.$row['ttm']);
                    		$dtday=$row['today'];
                    		$totalhours=$row['totalhours'];

                    		if ($today=="Same" &&($dttchkF >= $dtftot and $dttchkF <= $dtttot) || ($dttchkT >= $dtftot and $dttchkT <= $dtttot))
                    		{                    			
								$irtn=false;
					  		}
                    		else 
	                		if ($today=="Different" 
	                		&&
	                		(
	                		($dttchkF >= $dtftot and $dttchkF <= $diFend) || ($dttchkT >= $dtftot and $dttchkT <= $diFend)
	                		||
	                		($dttchkF >= $diTfrom and $dttchkF <= $dtttot) || ($dttchkT >= $diTfrom and $dttchkT <= $dtttot)
	                		)
	                		)
                    		{
								$irtn=false;	
                    		}
                    		
    	             	}
                	 }
                 	
                	}
                }
        	}
        }
        
        if(!$irtn)
        {
        	echo $la['TIMINGE'];
        }
        
		        
        return $irtn;
	}
    
	function fnchktripdatadaily($imei,$user_id,$uid,$tf,$tt,$eventid)
	{
		global  $la,$ms;
		$irtn=true;

		$q = "select * from droute_events_daily WHERE active='true' and `imei`='".$imei."' and user_id='".$user_id."' and event_id != '".$eventid."'
			   and (( '".$tf."' <= datefrom  and  '".$tt."' >= datefrom ) 
			   or ( '".$tf."' <= dateto  and  '".$tt."' >= dateto ) )  ";
		
        $r = mysqli_query($ms,$q);
        if($r)
        {
        	while($row = mysqli_fetch_array($r,MYSQLI_ASSOC))
        	{
   					$irtn=false;
   					//break;
        	}
        }
        
        if(!$irtn)
        {
        	echo $la['TIMINGE'];
        }        
        return $irtn;
	}
   
	
    if(@$_POST['cmd'] == 'save_allocate')
    {
        $uid = $_POST["event_id"];
        $tripname = $_POST["tripname"];
        $active = $_POST["active"];
        $week_days = $_POST["week_days"];
        $dontholiday = $_POST["dontholi"];
        $imei = $_POST["imei"];
        $route_id = $_POST["route_id"];
        $notify_system = $_POST["notify_system"];
        $notify_email = $_POST["notify_email"];
        $notify_email_address = $_POST["notify_email_address"];
        $notify_sms = $_POST["notify_sms"];
        $notify_sms_number = $_POST["notify_sms_number"];
        $tfh = $_POST["tfh"];
        $tfm = $_POST["tfm"];
        $tth = $_POST["tth"];
        $ttm = $_POST["ttm"];
        $today= $_POST["today"];
        $intervel=0;
        $totalhours=0;
        
        if($today=="Same")
        $intervel=1;
        else
        $intervel=2;

        //extra code B
        
  	   $curdate=gmdate("Y-m-d");
	   if($today!="Same")  	   
  	   $tomorrow=date('Y-m-d',strtotime('+1 day',strtotime($curdate)));
  	   else 
  	   $tomorrow=$curdate;
  	   
	   $aftrdateF=date('Y-m-d H:i',strtotime('-0 minutes',strtotime($curdate." ".$tfh.":".$tfm.":00")));
       $aftrdate2F=date('Y-m-d',strtotime($aftrdateF));
       $aftrdateT=date('Y-m-d H:i',strtotime('+0 minutes',strtotime($tomorrow." ".$tth.":".$ttm.":00")));
	  
	   
       $aftrdate2T=date('Y-m-d',strtotime($aftrdateT));
 	   if($aftrdate2T!=$aftrdate2F)
	   {
			 $today="Different";
	   }
	
	   
	    $tfh=date('H',strtotime($aftrdateF));
	   	$tfm=date('i',strtotime($aftrdateF));
	   	$tth=date('H',strtotime($aftrdateT));
	   	$ttm=date('i',strtotime($aftrdateT));
   	   	   
	   //extra code E
		
	   $dtftot=floatval($tfh.'.'.$tfm);
       $dtttot=floatval($tth.'.'.$ttm);
       
      
	   
		if($today=="Same" && $dtftot>$dtttot)
		{
			echo $la['PLSENTERVALIDTIME'];
			return false;
		}
		
		$calwithin24=0;
		$calafter24=0;

		if($today=="Different")
		{
			$calwithin24=24-$dtftot;
			$calafter24=$dtttot;
		}
		else 
		{
			$calafter24=$dtttot-$dtftot;
		}
		
		$totalhours=($calwithin24+$calafter24);
		
		$diff = strtotime($aftrdateT) - strtotime($aftrdateF);
 		$diff_in_hrs = $diff/3600;
 		
	   
		if($diff_in_hrs>=24)
		{
			echo $la['PLSSETWITHIN_24_HOUR'];
			return false;
		}
		
        
        if(fnchktripdata($imei,$user_id,$route_id,$week_days,$dtftot,$dtttot,$uid,$today,$totalhours))
        {

        if($_POST["today"]=="Same")
        $intervel=1;
        else
        $intervel=2;
        
        if($today=="Different")
		{
			$calwithin24=24-$dtftot;
			$calafter24=$dtttot;
		}
		else 
		{
			$calafter24=$dtttot-$dtftot;
		}
		
		$totalhours=($calwithin24+$calafter24);
		
        if ($uid == '0')
        {
            $q = "INSERT INTO `droute_events` (tripname,event_id,user_id,imei,route_id,active,week_days,dontholiday,notify_system,notify_email,notify_email_address,notify_sms,notify_sms_number,tfh,tfm,tth,ttm,today,daysinterval,totalhours) values ('".$tripname."','".$uid."','".$user_id."','".$imei."','".$route_id."','".$active."','".$week_days."','".$dontholiday."','".$notify_system."','".$notify_email."','".$notify_email_address."','".$notify_sms."','".$notify_sms_number."','".$_POST["tfh"]."','".$_POST["tfm"]."','".$_POST["tth"]."','".$_POST["ttm"]."','".$_POST["today"]."','".$intervel."','".$totalhours."')";
        }
        else
        {
            $q = "UPDATE `droute_events`  SET tripname='".$tripname."', user_id='".$user_id."',imei='".$imei."',route_id='".$route_id."',active='".$active."',week_days='".$week_days."',dontholiday='".$dontholiday."',notify_system='".$notify_system."',notify_email='".$notify_email."',notify_email_address='".$notify_email_address."',notify_sms='".$notify_sms."',notify_sms_number='".$notify_sms_number."',tfh='".$_POST["tfh"]."',tfm='".$_POST["tfm"]."',tth='".$_POST["tth"]."',ttm='".$_POST["ttm"]."',today='".$_POST["today"]."',daysinterval='".$intervel."',totalhours='".$totalhours."'  WHERE `event_id`='".$uid."' and user_id='".$user_id."'";    
        }
        	$r = mysqli_query($ms,$q); 
        	echo 'OK';
        }
        else
        {
           // echo $la['TIMINGE']; DO NOTHING
        }

        die;
    }
    if(@$_POST['cmd'] == 'delete_allocate')
    {
        $dept_id = $_POST["uid"];
        $q = "delete from droute_events  WHERE   event_id='".$dept_id."' and user_id='".$user_id."'";  
        $r = mysqli_query($ms,$q);
       
        
        echo 'OK';
        die;
    }
    else if(@$_GET['cmd'] == 'select_allocate')
    {
            
        $page = $_GET['page']; // get the requested page
        $limit = $_GET['rows']; // get how many rows we want to have into the grid
        $sidx = $_GET['sidx']; // get index row - i.e. user click to sort
        $sord = $_GET['sord']; // get the direction
        
        $total_pages = 1;
               
        $strq="";
        if($_GET['object_id']!="")
        {
            $dept_id = $_GET['object_id'];
            $strq.=" and dr.imei='".$dept_id."'";  
        }
        else 
        {
        	die;
        }

        // if( $_GET['searchdata']!="")    { $fidata = $_GET["searchdata"];            $strq.= " and( gu.name='".$fidata."' or gu.imei='".$fidata."' or d.routename='".$fidata."' ) ";       } 
        
    
        $q = "select distinct dr.*,gu.name,d.routename from droute_events dr join gs_objects gu on dr.imei=gu.imei join droute d on d.route_id=dr.route_id 
              and dr.user_id='".$user_id."'  ".$strq."  order by dr.tfh,dr.tfm";

        $result = mysqli_query($ms,$q);
        $count = 0;
       
         
        if($result!=false)
        $count=mysqli_num_rows($result);
        
        $responce = new stdClass();
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        
        if ($result!=false)
        {
            $i=0;
            
            while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
            {
                if ($row['active'] == 'true')
            {
                $active = '<img src="theme/images/tick-green.svg" />';
            }
            else
            {
                $active = '<img src="theme/images/remove-red.svg" />';
            }
            
            $notify_system = explode(",", $row['notify_system']);
            
            if (@$notify_system[0] == 'true')
            {
                $notify_system = '<img src="theme/images/tick-green.svg" />';
            }
            else
            {
                $notify_system = '<img src="theme/images/remove-red.svg" />';
            }
            
            if ($row['notify_email'] == 'true')
            {
                $notify_email = '<img src="theme/images/tick-green.svg" />';
            }
            else
            {
                $notify_email = '<img src="theme/images/remove-red.svg" />';
            }
            
            if ($row['notify_sms'] == 'true')
            {
                $notify_sms = '<img src="theme/images/tick-green.svg" />';
            }
            else
            {
                $notify_sms = '<img src="theme/images/remove-red.svg" />';
            }
            
                $modify = '<a href="#" onclick="boardingallocatedit(\''.$row['event_id'].'\');" title="Edit"><img src="theme/images/edit.svg"></a><a href="#" onclick="boardingallocatedelete(\''.$row['event_id'].'\');" title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a><a href="#" onclick="addtripemployee(\''.$row['event_id'].'\');" title="'.$la['DELETE'].'"><img src="theme/images/user.svg" /></a>';
            
                $responce->rows[$i]['id'] = $i;
                $responce->rows[$i]['cell']=array($row['tripname'],$row['routename'],$active,$notify_system,($row['tfh'].'.'.$row['tfm']),($row['tth'].'.'.$row['ttm']),$modify,$row['event_id']);
                $i++;
            }
        }
    
        header('Content-type: application/json');
        echo json_encode($responce);
        die;
    }
    else if(@$_POST['cmd'] == 'edit_allocate')
    {
            
   
        
        $total_pages = 1;
               
        $strq="";
        if( $_POST['event_id']!="Select" && $_POST['event_id']!="")
        {
            $dept_id = $_POST['event_id'];
            $strq.=" and dr.event_id='".$dept_id."'";  
        } 

       
        $q = "select dr.*,gu.name,d.routename from droute_events dr join gs_objects gu on dr.imei=gu.imei join droute d on d.route_id=dr.route_id 
              and dr.user_id='".$user_id."'  ".$strq."  order by d.routename,dr.imei";

        $result = mysqli_query($ms,$q);
        $count = 0;
        

        if($result!=false)
        $count=mysqli_num_rows($result);
        
        $responce =null;
   
        
        if ($result!=false)
        {
           $i=0;
           
             while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
            {
                $responce->rows[$i]=array($row);
                $i++;
            }
        }
    
        header('Content-type: application/json');
        echo json_encode($responce);
        die;
    }
    else if(@$_POST['cmd'] == 'save_allocatedaily')
    {
        $uid = $_POST["event_id"];
        $tripname = $_POST["tripname"];
        $active = $_POST["active"];
        $dontholiday = $_POST["dontholi"];
        $imei = $_POST["imei"];
        $route_id = $_POST["route_id"];
        $notify_system = $_POST["notify_system"];
        $notify_email = $_POST["notify_email"];
        $notify_email_address = $_POST["notify_email_address"];
        $notify_sms = $_POST["notify_sms"];
        $notify_sms_number = $_POST["notify_sms_number"];
       
        $tfh = $_POST["tfh"];
        $tfm = $_POST["tfm"];
        $tth = $_POST["tth"];
        $ttm = $_POST["ttm"];
        $intervel=0;
        $totalhours=0;
        
        
    				$tf = date('Y-m-d H:i:s',strtotime($_POST["date_from"]." ".$tfh.":".$tfm.":00"));
        			$tt = date('Y-m-d H:i:s',strtotime($_POST["date_to"]." ".$tth.":".$ttm.":00"));
        			if($tf>$tt)
        			{
        				echo $la['PLSENTERVALIDTIME'];	
        				die;  
        			}
        			
        			$today="";
        			if(date('Y-m-d',strtotime($tf))==date('Y-m-d',strtotime($tt)))
        			{
        				$today="Same";	
        			}
        			else
        			{
        				$today="Different";
        			}
			        $_POST["daytype"]=$today;
			        
			       
			        $intervel=0;
			        $totalhours=0;
        
					        if($today=="Same")
					        $intervel=1;
					        else
					        $intervel=2;

        
					        //extra code B
					  	   
						   $aftrdateF=date('Y-m-d H:i',strtotime('-30 minutes',strtotime($tf)));
					       $aftrdate2F=date('Y-m-d',strtotime($aftrdateF));
					       $aftrdateT=date('Y-m-d H:i',strtotime('+30 minutes',strtotime($tt)));

					       $aftrdate2T=date('Y-m-d',strtotime($aftrdateT));
					 	   if($aftrdate2T!=$aftrdate2F)
						   {
								 $today="Different";
						   }

						
						   $diff=getTimeDifferenceDetails_dailyboarding($tf,$tt);
						   $totalhours=$diff;
							
						   
							if($diff>=23.30)
							{
        						echo $la['PLSSETWITHIN_24_HOUR'];
        						die;    
							}
								
        if(fnchktripdatadaily($imei,$user_id,$route_id,$aftrdateF,$aftrdateT,$uid))
        {

        if($_POST["daytype"]=="Same")
        $intervel=1;
        else
        $intervel=2;
        
     
		
        if ($uid <=0)
        {
        	
            $q = "INSERT INTO `droute_events_daily` (createdate,tripname,event_id,user_id,imei,route_id,active,dontholiday,notify_system,notify_email,notify_email_address,notify_sms,notify_sms_number,datefrom,dateto,today,daysinterval,totalhours) values ('".gmdate("Y-m-d H:i:s")."','".$tripname."','".$uid."','".$user_id."','".$imei."','".$route_id."','".$active."','".$dontholiday."','".$notify_system."','".$notify_email."','".$notify_email_address."','".$notify_sms."','".$notify_sms_number."','".$tf."','".$tt."','".$_POST["daytype"]."','".$intervel."','".$totalhours."')";
        }
        else
        {
            $q = "UPDATE `droute_events_daily`  SET tripname='".$tripname."', user_id='".$user_id."',imei='".$imei."',route_id='".$route_id."',active='".$active."',dontholiday='".$dontholiday."',notify_system='".$notify_system."',notify_email='".$notify_email."',notify_email_address='".$notify_email_address."',notify_sms='".$notify_sms."',notify_sms_number='".$notify_sms_number."',datefrom='".$tf."',dateto='".$tt."',today='".$_POST["daytype"]."',daysinterval='".$intervel."',totalhours='".$totalhours."'  WHERE `event_id`='".$uid."' and user_id='".$user_id."'";    
        }
       
        	$r = mysqli_query($ms,$q); 
        	echo 'OK';
        }
        die;
    }
    else  if(@$_POST['cmd'] == 'delete_allocatedaily')
    {
        $dept_id = $_POST["uid"];
        $q = "delete from droute_events_daily  WHERE   event_id='".$dept_id."' and user_id='".$user_id."'";  
        $r = mysqli_query($ms,$q);
       
        
        echo 'OK';
        die;
    }
    else if(@$_GET['cmd'] == 'select_allocatedaily')
    {
            
        $page = $_GET['page']; // get the requested page
        $limit = $_GET['rows']; // get how many rows we want to have into the grid
        $sidx = $_GET['sidx']; // get index row - i.e. user click to sort
        $sord = $_GET['sord']; // get the direction
        
        $total_pages = 1;
               
        $strq="";
        if($_GET['object_id']!="")
        {
            $dept_id = $_GET['object_id'];
            $strq.=" and dr.imei='".$dept_id."'";  
        }
        else 
        {
        	die;
        }

        // if( $_GET['searchdata']!="")    { $fidata = $_GET["searchdata"];            $strq.= " and( gu.name='".$fidata."' or gu.imei='".$fidata."' or d.routename='".$fidata."' ) ";       } 
        
    
        $q = "select dr.*,gu.name,d.routename from droute_events_daily dr join gs_objects gu on dr.imei=gu.imei join droute d on d.route_id=dr.route_id 
              and dr.user_id='".$user_id."'  ".$strq."  order by dr.datefrom desc,dr.dateto";
        
        $result = mysqli_query($ms,$q);
        $count = 0;
       
         
        if($result!=false)
        $count=mysqli_num_rows($result);
        
        $responce = new stdClass();
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        
        if ($result!=false)
        {
            $i=0;
            
            while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
            {
                if ($row['active'] == 'true')
            {
                $active = '<img src="theme/images/tick-green.svg" />';
            }
            else
            {
                $active = '<img src="theme/images/remove-red.svg" />';
            }
            
            $notify_system = explode(",", $row['notify_system']);
            
            if (@$notify_system[0] == 'true')
            {
                $notify_system = '<img src="theme/images/tick-green.svg" />';
            }
            else
            {
                $notify_system = '<img src="theme/images/remove-red.svg" />';
            }
            
            if ($row['notify_email'] == 'true')
            {
                $notify_email = '<img src="theme/images/tick-green.svg" />';
            }
            else
            {
                $notify_email = '<img src="theme/images/remove-red.svg" />';
            }
            
            if ($row['notify_sms'] == 'true')
            {
                $notify_sms = '<img src="theme/images/tick-green.svg" />';
            }
            else
            {
                $notify_sms = '<img src="theme/images/remove-red.svg" />';
            }
            
                $modify = '<a href="#" onclick="boardingallocateditdaily(\''.$row['event_id'].'\');" title="Edit"><img src="theme/images/edit.svg"></a><a href="#" onclick="boardingallocatedeletedaily(\''.$row['event_id'].'\');" title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>';
            
                $responce->rows[$i]['id'] = $i;
                $responce->rows[$i]['cell']=array($row['tripname'],$row['routename'],$active,$notify_system,$row['datefrom'],$row['dateto'],$modify,$row['event_id']);
                $i++;
            }
        }
    
        header('Content-type: application/json');
        echo json_encode($responce);
        die;
    }
     else if(@$_POST['cmd'] == 'edit_allocatedaily')
    {
            
   
        
        $total_pages = 1;
               
        $strq="";
        if( $_POST['event_id']!="Select" && $_POST['event_id']!="")
        {
            $dept_id = $_POST['event_id'];
            $strq.=" and dr.event_id='".$dept_id."'";  
        } 

       
        $q = "select 
         	  date(dr.datefrom) date_from,date(dr.dateto) date_to,
			  LPAD(hour(dr.datefrom),2,0) tfh,LPAD(hour(dr.dateto),2,0) tth,
			  LPAD(minute(dr.datefrom),2,0) tfm,LPAD(minute(dr.dateto),2,0) ttm,
        	  dr.*,gu.name,d.routename from droute_events_daily dr join gs_objects gu on dr.imei=gu.imei join droute d on d.route_id=dr.route_id 
              and dr.user_id='".$user_id."'  ".$strq."  order by d.routename,dr.imei";

        $result = mysqli_query($ms,$q);
        $count = 0;
        

        if($result!=false)
        $count=mysqli_num_rows($result);
        
        $responce =null;
   
        
        if ($result!=false)
        {
           $i=0;
           
             while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
            {
                $responce->rows[$i]=array($row);
                $i++;
            }
        }
    
        header('Content-type: application/json');
        echo json_encode($responce);
        die;
    }
    
	
	if(@$_POST['cmd'] == 'save_hotspot')
	{
		$hotspot_code = $_POST["hotspot_code"];
		$hotspot_name = $_POST["hotspot_name"];
		$zonedetail = $_POST["zonedetail"];
        $freezekm = $_POST["freezekm"];
        $travelroute = $_POST["travelroute"];
		
		$q = "select * from droute WHERE    `routename`='".$hotspot_name."' and route_id !='".$hotspot_code."' and user_id='".$user_id."' ";
        $r = mysqli_query($ms,$q);
    
        
    
        if(mysqli_num_rows($r) == 0)
        {
		
		if ($hotspot_code == '0')
		{
			$q = "INSERT INTO `droute` (routename,user_id,freezedkm,trip_route_id) VALUES ('".$hotspot_name."','".$user_id."','".$freezekm."','".$travelroute."')";
			$r = mysqli_query($ms,$q);
			$id = mysqli_insert_id($ms);
		
			$arr1=explode('~',$zonedetail);
			
			if(count($arr1)>0)
			{
				for ($i=0; $i < count($arr1); $i++) 
				{ 
					$arr2=explode('^',$arr1[$i]);
				if(count($arr2)>1)
				{
					$q = "INSERT INTO `droute_sub` (route_id,user_id,zonecode,zoneinout,message,point) VALUES ('".$id."','".$user_id."','".$arr2[0]."','".$arr2[1]."','".$arr2[2]."','".$arr2[3]."')";
					$r = mysqli_query($ms,$q);
				}
			 }
		
		}
			
			
			
		}
		else
		{
			$q = "UPDATE `droute`  SET  `routename`='".$hotspot_name."',freezedkm='".$freezekm."',trip_route_id='".$travelroute."'  WHERE `route_id`='".$hotspot_code."' and user_id='".$user_id."'";
			$r = mysqli_query($ms,$q);
			
			$q = "delete from  `droute_sub`  WHERE	`route_id`='".$hotspot_code."' and user_id='".$user_id."'";
			$r = mysqli_query($ms,$q);
			
			
			$arr1=explode('~',$zonedetail);
			
			if(count($arr1)>0)
			{
				for ($i=0; $i < count($arr1); $i++) 
				{ 
					$arr2=explode('^',$arr1[$i]);
				if(count($arr2)>1)
				{
					$q = "INSERT INTO `droute_sub` (route_id,user_id,zonecode,zoneinout,message,point) VALUES ('".$hotspot_code."','".$user_id."','".$arr2[0]."','".$arr2[1]."','".$arr2[2]."','".$arr2[3]."')";
					$r = mysqli_query($ms,$q);
				}
			 }
			}
				
		}
		
	
		
		echo 'OK';
		
		}
		else {
            echo $la['NAMEE'];;
        }
        
    
		
		die;
	}
	
	if(@$_POST['cmd'] == 'delete_hotspot')
	{
		$hotspot_id = $_POST["hotspot_id"];
			
		$q = "delete from droute_events  WHERE	route_id='".$hotspot_id."' and user_id='".$user_id."'";	
		$r = mysqli_query($ms,$q);

		$q = "delete from droute_sub  WHERE	route_id='".$hotspot_id."' and user_id='".$user_id."'";	
		$r = mysqli_query($ms,$q);
			
		$q="delete from droute  WHERE	route_id='".$hotspot_id."' and user_id='".$user_id."'";
		$r = mysqli_query($ms,$q);
			
		echo 'OK';
		die;
	}
	else if(@$_GET['cmd'] == 'select_hotspot')
	{
			
		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
		
		if(!$sidx) $sidx =1;
		
		// get records number
	
	
		$total_pages = 1;
		
		$q="select DISTINCT *,(select count(*) from droute_sub where route_id=dr.route_id) cnt from droute dr where user_id='".$user_id."' ORDER BY route_id ASC";
		//$q = "SELECT DISTINCT	* FROM droute where user_id='".$user_id."' ORDER BY route_id ASC";
		
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
				
				$modify = '<a href="#" onclick="boardinghotspotedit(\''.$row['route_id'].'\');" title="Edit"><img src="theme/images/edit.svg"></a><a href="#" onclick="boardinghotspotdelete(\''.$row['route_id'].'\');" title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>';
			
				
				$responce->rows[$i]['id'] = $i;
				$responce->rows[$i]['cell']=array($row['route_id'],$row['routename'],$row['cnt'], $modify);
				$i++;
			}
		}
	
		header('Content-type: application/json');
		echo json_encode($responce);
		die;
	}
	else if(@$_POST['cmd'] == 'select_hotspot_single')
    {
        $hotspot_id = $_POST["hotspot_id"];
        $r1 = array();
            
        $q = "select d.freezedkm,d.trip_route_id,d.routename,dr.*,guz.zone_name from droute d join droute_sub dr on 
              d.route_id=dr.route_id join gs_user_zones guz on dr.zonecode=guz.zone_id
              and dr.user_id=d.user_id and d.user_id=guz.user_id  and  dr.user_id='".$user_id."' and dr.route_id='".$hotspot_id."'";
        

        $result = mysqli_query($ms,$q);
        
        
        if ($result!=false)
        {
            $ir=0;
            while($row=mysqli_fetch_array($result))
            {
                $ir=$ir+1;
                $r1[] = array('routename' => $row['routename'],'sino'=>$ir,'zonecode'=>$row['zonecode'],'zonename'=>$row['zone_name'],'zoneinout' => $row['zoneinout'],'message' => $row['message'],'point' => $row['point'],'freezedkm'=>$row['freezedkm'],'travelroute'=>$row['trip_route_id']);
    
            }
        }
    
                        
        echo json_encode($r1);
        
        die;
    }
	

	if(@$_POST['cmd'] == 'save_department')
	{
		$dept_id = $_POST["dept_id"];
		$dept_name = $_POST["dept_name"];
		
		$q = "select * from dept WHERE	`dept_name`='".$dept_name."' and dept_id !='".$dept_id."' and user_id='".$user_id."' ";
		$r = mysqli_query($ms,$q);
	
		
	
		if(mysqli_num_rows($r) == 0)
		{
		if ($dept_id == '0')
		{
			$q = "INSERT INTO `dept` (dept_name,user_id) VALUES ('".$dept_name."','".$user_id."')";
		}
		else
		{
			$q = "UPDATE `dept`  SET  `dept_name`='".$dept_name."'  WHERE	`dept_id`='".$dept_id."' and user_id='".$user_id."'";	
		}
		
		$r = mysqli_query($ms,$q);
		
		echo 'OK';
		
		}
		else {
			 echo $la['NAMEE'];;
		}
		
		die;
	}
	if(@$_POST['cmd'] == 'delete_department')
	{
		$dept_id = $_POST["dept_id"];
		
		$q="delete from dsection  WHERE	section_id='".$dept_id."' and user_id='".$user_id."'";
		$r = mysqli_query($ms,$q);
		
		$q = "delete from dept  WHERE	dept_id='".$dept_id."' and user_id='".$user_id."'";	
		$r = mysqli_query($ms,$q);

		
		
		echo 'OK';
		die;
	}
	else if(@$_GET['cmd'] == 'select_department')
	{
			
		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
		
		if(!$sidx) $sidx =1;
		
		// get records number
	
	
		$total_pages = 1;
		
	
		$q = "SELECT DISTINCT	dept_id,dept_name FROM dept where user_id='".$user_id."' ORDER BY dept_name ASC";
		
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
				
				$modify = '<a href="#" onclick="boardingdepartmentedit(\''.$row['dept_id'].'\');" title="Edit"><img src="theme/images/edit.svg"></a><a href="#" onclick="boardingdepartmentdelete(\''.$row['dept_id'].'\');" title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>';
			
				
				$responce->rows[$i]['id'] = $i;
				$responce->rows[$i]['cell']=array($row['dept_name'], $modify,$row['dept_id']);
				$i++;
			}
		}
	
		header('Content-type: application/json');
		echo json_encode($responce);
		die;
	}


	if(@$_POST['cmd'] == 'save_section')
	{
		$dept_id = $_POST["dept_id"];
		$section_name = $_POST["section_name"];
		$section_id = $_POST["section_id"];
	
		
		$q = "select * from dsection WHERE	`section_name`='".$section_name."' and dept_id ='".$dept_id."' and `section_id`!='".$section_id."' and user_id='".$user_id."' ";
		$r = mysqli_query($ms,$q);
	

		if(mysqli_num_rows($r) == 0 )
		{
			
		if ($section_id == '0')
		{
			$q = "INSERT INTO `dsection` (dept_id,section_name,user_id) VALUES ('".$dept_id."','".$section_name."','".$user_id."')";
		}
		else
		{
			$q = "UPDATE `dsection`  SET  `dept_id`='".$dept_id."',`section_name`='".$section_name."'  WHERE `section_id`='".$section_id."' and user_id='".$user_id."'";	
		}
		
		$r = mysqli_query($ms,$q);
		
		echo 'OK';
		}
		else
	    {
			 echo $la['NAMEE'];;
		}
		
		die;
	}
	if(@$_POST['cmd'] == 'delete_section')
	{
		$dept_id = $_POST["section_id"];
		$q = "delete from dsection  WHERE	section_id='".$dept_id."' and user_id='".$user_id."'";	
		$r = mysqli_query($ms,$q);
		
		echo 'OK';
		die;
	}
	else if(@$_GET['cmd'] == 'select_section')
	{
			
		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
		
		$total_pages = 1;
	
		
		$strq="";
		if( $_GET['dept_id']!="Select"&& $_GET['dept_id']!="")
		{
			$dept_id = $_GET['dept_id'];
			$strq=" and d.dept_id='".$dept_id."'";	
		} 
		
	
		$q = "select d.dept_name,ds.section_name,d.dept_id,ds.section_id from dept d join  dsection ds on d.dept_id=ds.dept_id and d.user_id=ds.user_id and d.user_id='".$user_id."' ".$strq." order by d.dept_name asc,ds.section_name ";
			
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
				
				$modify = '<a href="#" onclick="boardingsectionedit(\''.$row['section_id'].'\');" title="Edit"><img src="theme/images/edit.svg"></a><a href="#" onclick="boardingsectiondelete(\''.$row['section_id'].'\');" title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>';
			
				
				$responce->rows[$i]['id'] = $i;
				$responce->rows[$i]['cell']=array($row['dept_name'],$row['section_name'], $modify,$row['section_id'],$row['dept_id']);
				$i++;
			}
		}
	
		header('Content-type: application/json');
		echo json_encode($responce);
		die;
	}

	if(@$_POST['cmd'] == 'save_holiday')
	{
		$uid = $_POST["uid"];
		$date = $_POST["date"];
		$dateto = $_POST["dateto"];
		$reason = $_POST["reason"];


		if ($uid == '0')
		{
			$q = "INSERT INTO `dholiday` (date,dateto,reason,user_id) VALUES ('".$date."','".$dateto."','".$reason."','".$user_id."')";
		}
		else
		{
			$q = "UPDATE `dholiday`  SET  `date`='".$date."',`dateto`='".$dateto."',`reason`='".$reason."'  WHERE	`uid`='".$uid."' and user_id='".$user_id."'";
		}

		$r = mysqli_query($ms,$q);

		echo 'OK';


		die;
	}
	if(@$_POST['cmd'] == 'delete_holiday')
	{
		$uid = $_POST["uid"];
		$q = "delete from dholiday  WHERE uid='".$uid."'";
		$r = mysqli_query($ms,$q);

		echo 'OK';
		die;
	}
	else if(@$_GET['cmd'] == 'select_holiday')
	{
			
		$page = 0; // get the requested page
		$limit = 0; // get how many rows we want to have into the grid
		$sidx = 0; // get index row - i.e. user click to sort
		$sord = 0; // get the direction

		if(!$sidx) $sidx =1;

		$total_pages = 1;

		$strq="";
		if( $_GET['df']!="" && $_GET['dt']!="")
		{
			$strq=" and (date between '".$_GET['df']."' and '".$_GET['dt']."')";
			$strq .=" or (dateto between '".$_GET['df']."' and '".$_GET['dt']."')";
		} 
	
		$q = "SELECT DISTINCT	* FROM dholiday where user_id='".$user_id."' ".$strq." ORDER BY date ASC";
			
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
				$modify = '<a href="#" onclick="boardingholidayedit(\''.$row['uid'].'\');" title="Edit"><img src="theme/images/edit.svg"></a><a href="#" onclick="boardingholidaydelete(\''.$row['uid'].'\');" title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>';
				$responce->rows[$i]['id'] = $i;
				$responce->rows[$i]['cell']=array($row['date'],$row['dateto'],$row['reason'], $modify,$row['uid']);
				$i++;
			}
		}
	
		header('Content-type: application/json');
		echo json_encode($responce);
		die;
		
	}

	
    
    if(@$_POST['cmd'] == 'save_student')
    {
        $uid = $_POST["uid"];
        $dept_id = $_POST["dept_id"];
        $section_id = $_POST["section_id"];
        $sid = $_POST["sid"];
        $name = $_POST["name"];
        $gender = $_POST["gender"];
        $phno = $_POST["phno"];
        $emailid = $_POST["emailid"];
        $route_id = $_POST["route_id"];
        $status = $_POST["status"];
        $dob = $_POST["dob"];
        $route_id_down = $_POST["route_id_down"];
        $zone_id = $_POST["zone_id"];
        $zone_id_down = $_POST["zone_id_down"];
        $object_imei = $_POST["object_imei"];
        
         $parent = $_POST["parent"];
         $rfidno = $_POST["rfidno"];
        
        $q = "select * from dstudent WHERE  `sid`='".$sid."' and dept_id ='".$dept_id."' and `section_id`!='".$section_id."' and user_id='".$user_id."' and uid != '".$uid."'  ";
        $r = mysqli_query($ms,$q);
    
        if(mysqli_num_rows($r) == 0 )
        {
            
     		  if ($uid == '0')
        	 {
		        $q = "INSERT INTO `dstudent` (dob,route_id_down,dept_id,section_id,sid,name,gender,route_id,status,user_id,rfid,create_date,zone_id,zone_id_down,imei) VALUES ('".$dob."','".$route_id_down."','".$dept_id."','".$section_id."',
	            '".$sid."','".$name."','".$gender."','".$route_id."','".$status."','".$user_id."','".$rfidno."','".date("Y-m-d H:i:s")."','".$zone_id."','".$zone_id_down."','".$object_imei."')";
		        $r = mysqli_query($ms,$q);
		        
		        $student_unique_id = mysqli_insert_id($ms);
		        
		         $qsv = "INSERT INTO `dstudent_sub` (student_id,user_id,parent_name,contactno,emailid,parent_type,create_date) VALUES
		          ('".$student_unique_id."','".$user_id."','".$parent."','".$phno."','".$emailid."','P','".date("Y-m-d H:i:s")."')";
		         $rsv = mysqli_query($ms,$qsv);
        	 }
        	 else
        	 {
           		$q = "UPDATE `dstudent`  SET  `dob`='".$dob."',route_id_down='".$route_id_down."',`dept_id`='".$dept_id."',section_id='".$section_id."',sid='".$sid."',name='".$name."',gender='".$gender."',route_id='".$route_id."',status='".$status."',  
           		rfid='".$rfidno."',zone_id='".$zone_id."',zone_id_down='".$zone_id_down."',imei='".$object_imei."' WHERE `uid`='".$uid."' and user_id='".$user_id."'";
           		$r = mysqli_query($ms,$q);
           		    
           	
           	   $qv = "select * from dstudent_sub WHERE  `student_id`='".$uid."' and parent_type='P'  ";
       		   $rv = mysqli_query($ms,$qv);
        	   if(mysqli_num_rows($rv) == 0 )
     		   {
     		   	 $qsv = "INSERT INTO `dstudent_sub` (student_id,user_id,parent_name,contactno,emailid,parent_type,create_date) VALUES
		          ('".$uid."','".$user_id."','".$parent."','".$phno."','".$emailid."','P','".date("Y-m-d H:i:s")."')";
		         $rsv = mysqli_query($ms,$qsv);
     		   }
     		   else 
     		   {
           		 $qsv = "update `dstudent_sub` set parent_name='".$parent."',
           		 contactno='".$phno."',emailid='".$emailid."' where parent_type='P' and `student_id`='".$uid."' and user_id='".$user_id."' " ;
		         $rsv = mysqli_query($ms,$qsv); 
		        
     		   } 
        	 }
        
        
        	echo 'OK';
        }
        else
        {
             echo $la['IDE'];
        }
        
        die;
    }
    if(@$_POST['cmd'] == 'delete_student')
    {
        $dept_id = $_POST["uid"];
        $q = "delete from dstudent  WHERE   uid='".$dept_id."' and user_id='".$user_id."'";  
        $r = mysqli_query($ms,$q);
        
        $q = "delete from dstudent_sub  WHERE   student_id='".$dept_id."' and user_id='".$user_id."'";  
        $r = mysqli_query($ms,$q);
        
        echo 'OK';
        die;
    }
    else if(@$_GET['cmd'] == 'select_student')
    {
            
        $page = $_GET['page']; // get the requested page
        $limit = $_GET['rows']; // get how many rows we want to have into the grid
        $sidx = $_GET['sidx']; // get index row - i.e. user click to sort
        $sord = $_GET['sord']; // get the direction
        
        $total_pages = 1;
        $strq="";
        if( $_GET['dept_id']!="Select" && $_GET['dept_id']!="")
        {
            $dept_id = $_GET['dept_id'];
            $strq.=" and s.dept_id='".$dept_id."'";  
        } 
        
         if( $_GET['section_id']!="Select" &&  $_GET['section_id']!="0" && $_GET['section_id']!="")
        {
            $section_id = $_GET['section_id'];
            $strq.=" and s.section_id='".$section_id."'";  
        } 
        
          if( $_GET['fidata']!="")
        {
            $fidata = $_GET["fidata"];
            $strq.= " and( s.sid='".$fidata."' or s.name='".$fidata."' or s.gender='".$fidata."' or s.phno='".$fidata."' or s.emailid='".$fidata."') ";  
        } 
        
    
        $q = "select distinct d.dept_name,ds.section_name,d.dept_id,ds.section_id,s.uid,s.name,s.sid,
 s.gender,s.dob,s.status,s.route_id,s.route_id_down,r.routename ,        
(select rd.routename from droute rd where rd.route_id=s.route_id_down ) routename_down 
,dsv.parent_name parent, dsv.contactno phno, dsv.emailid,s.rfid,s.zone_id,s.zone_id_down,s.imei as pe_imei
from dstudent s
left join dstudent_sub dsv on dsv.student_id=s.uid and dsv.parent_type='P'
 join dept d  on s.dept_id=d.dept_id  join dsection ds on d.dept_id=ds.dept_id
join droute r on r.route_id=s.route_id
 and d.user_id=ds.user_id and s.user_id=r.user_id and s.user_id=ds.user_id and 
  s.section_id=ds.section_id
                and d.user_id='".$user_id."'  ".$strq."  order by d.dept_name asc,ds.section_name";

        $result = mysqli_query($ms,$q);
        $count = 0;
        
        if($result!=false)
        $count=mysqli_num_rows($result);
        
        $responce = new stdClass();
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        
        if ($result!=false)
        {
            $i=0;
            while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
            {    
                $modify = '<a href="#" onclick="boardingstudentedit(\''.$row['uid'].'\');" title="Edit"><img src="theme/images/edit.svg"></a><a href="#" onclick="boardingstudentdelete(\''.$row['uid'].'\');" title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>';
            
                $responce->rows[$i]['id'] = $i;
                $responce->rows[$i]['cell']=array($row['dept_name'],$row['section_name'],$row['sid'],$row['name'],$row['gender'],$row['phno'],$row['dob'],$row['emailid'],$row['routename'],$row['routename_down'],$row['status'], $modify,$row['uid'],$row['route_id'],$row['section_id'],$row['dept_id'],$row['route_id_down'],$row['parent'],$row['rfid'],$row['zone_id'],$row['zone_id_down'],$row['pe_imei']);
                $i++;
            }
        }
    
        header('Content-type: application/json');
        echo json_encode($responce);
        die;
    }else if(@$_POST['cmd'] == 'upload_peoplelist'){
        // People master bulk upload Excel data upload in database 
        // $myfile = fopen("svvv.txt", "a");
        // fwrite($myfile,json_encode($_POST['exceldata']));
        // fwrite($myfile, "\n");
        // fclose($myfile);
        $filedata=$_POST['exceldata'];
        if(isset($filedata[0]['S.No']) && isset($filedata[0]['Emp_ID']) && isset($filedata[0]['Employee_Name']) && isset($filedata[0]['RFID']) && isset($filedata[0]['Vehicle']) ){
            for ($i=0; $i <count($filedata) ; $i++) {
                
                $imei=getObjectIMEI($filedata[$i]['Vehicle']);
                // if($filedata[$i]['Status']=='Active' || $filedata[$i]['Status']=='Deactive'){
                //     $filedata[$i]['Status']=$filedata[$i]['Status'];
                // }else{
                //     $filedata[$i]['Status']='Deactive';
                // }
                $phoneno=preg_replace("/[^0-9.]/", "", $filedata[$i]['Phone_Number']);
                $dep=getDepartmentID($filedata[$i]['Department']);
                $section=getSessionID($dep);
                $edata=array('empid'=>$filedata[$i]['Emp_ID'],'empname'=>$filedata[$i]['Employee_Name'],'routein'=>@$filedata[$i]['Pickup_Route_Id'],'routeout'=>@$filedata[$i]['Dorp_Route_Id'],'department'=>@$dep,'section'=>$section,'pickupzone'=>@$filedata[$i]['Pickup_Zone_Id'],'dropzone'=>@$filedata[$i]['Drop_Zone_Id'],'phone'=>$phoneno,'snrummber'=>$filedata[0]['RFID'],'rfid'=>$filedata[$i]['RFID'],'imei'=>$imei,'shift'=>'','Status'=>$filedata[$i]['Status']);
                $qs="select * from dstudent where sid='".$edata['empid']."'";
                $rs=mysqli_query($ms,$qs);
                if(mysqli_num_rows($rs)==0){
                    $qi="INSERT into dstudent (`user_id`,`route_id`,`route_id_down`,`zone_id`,`zone_id_down`,`dept_id`,`section_id`,`name`,`sid`,`phno`,`status`,`rfid`,`rfid_snr_no`,`imei`,`shift`) VALUES ('".$user_id."','".$edata['routein']."','".$edata['routeout']."','".$edata['pickupzone']."','".$edata['dropzone']."','".$edata['department']."','".$edata['section']."','".$edata['empname']."','".$edata['empid']."','".$edata['phone']."','".$edata['Status']."','".$edata['rfid']."','".$edata['snrummber']."','".$edata['imei']."','".$edata['shift']."') ";
                        // echo $qi;
                    if(mysqli_query($ms,$qi)){
                        $last_id = mysqli_insert_id($ms);
                        $qp="INSERT INTO dstudent_sub (`user_id`,`student_id`,`parent_name`,`contactno`,`status`,`parent_type`,`refer_user`,`emailid`) VALUES ('".$user_id."','".$last_id."','".$edata['empname']."','".$edata['phone']."','A','P','0','')";
                        // echo $qp;
                            mysqli_query($ms,$qp);
                    }

                }else{
                    $rows=mysqli_fetch_assoc($rs);
                    // echo $edata['empid'].'- Already Registred';
                    $qu="UPDATE dstudent set `route_id`='".$edata['routein']."',`route_id_down`='".$edata['routeout']."',`zone_id`='".$edata['pickupzone']."',`zone_id_down`='".$edata['dropzone']."',`dept_id`='".$edata['department']."',`section_id`='".$edata['section']."',`name`='".$edata['empname']."',`phno`='".$edata['phone']."',`status`='".$edata['Status']."',`rfid`='".$edata['rfid']."',`rfid_snr_no`='".$edata['snrummber']."',`imei`='".$edata['imei']."' where `sid`='".$edata['empid']."' and `user_id`='".$user_id."'";
                    // echo $qu;
                    mysqli_query($ms,$qu);
                    $qsu="SELECT * FROM dstudent_sub where `user_id`='".$user_id."' and `student_id`='".$rows['uid']."'";
                    $rsu=mysqli_query($ms,$qsu);
                    if(mysqli_num_rows($rsu)>0){
                        $rowqq=mysqli_fetch_assoc($r);
                        $qq="UPDATE dstudent_sub SET `parent_name`='".$edata['empname']."',`contactno`='".$edata['phone']."',`status`='A',`parent_type`='P',`refer_user`='0',`emailid`='' WHERE `user_id`='".$user_id."' and `student_id`='".$rows['uid']."'";
                        mysqli_query($ms,$qq);
                    }else{
                        $qp="INSERT INTO dstudent_sub (`user_id`,`student_id`,`parent_name`,`contactno`,`status`,`parent_type`,`refer_user`,`emailid`) VALUES ('".$user_id."','".$rows['uid']."','".$edata['empname']."','".$edata['phone']."','A','P','0','')";
                        // echo $qp;
                            mysqli_query($ms,$qp);
                    }
                }


            }
            echo 'OK';
            die;
            
        }else{
            echo 'Invalid_Colums';
            die;
        }
        // header('Content-type: application/json');
        
    }
    if(@$_POST['cmd'] == 'get_vehicle_employee'){
            $responce=array();
            // $q="SELECT a.* FROM dstudent a LEFT JOIN droute_events b ON a.imei=b.imei";
            $q="SELECT a.*,b.employee_ids FROM dstudent a LEFT JOIN droute_events b ON a.imei=b.imei WHERE a.user_id='".$user_id."' AND b.event_id='".$_POST['tripid']."'";
            $r=mysqli_query($ms,$q);
            if(mysqli_num_rows($r)>0){
                while($row=mysqli_fetch_assoc($r)){
                    $status='no';
                    if($row['employee_ids']!=''){
                        $exid=explode(',',$row['employee_ids']);
                        if (in_array($row['uid'], $exid)){
                            $status='yes';
                        }
                    }
                    $responce[]=array('emp_name'=>$row['name'],'emp_id'=>$row['uid'],'status'=>$status);
                }
            }
            header('Content-type: application/json');
            echo json_encode($responce);
            die;
        }
    if(@$_POST['cmd'] == 'update_trip_employee'){
            $q="UPDATE droute_events SET employee_ids='".$_POST['emplist']."' where user_id='".$user_id."' and event_id='".$_POST['tripid']."'";
            $r=mysqli_query($ms,$q);
            echo 'OK';
            die;
        }

function getDepartmentID($dep){
    global $user_id,$ms;
    $dep_id=0;
    $q="SELECT dept_id FROM dept where dept_name='".$dep."' and user_id='".$user_id."'";
    $r=mysqli_query($ms,$q);
    if(mysqli_num_rows($r)>0){
        $row=mysqli_fetch_assoc($r);
        $dep_id=$row['dept_id'];
    }else{
        $q="INSERT INTO dept (user_id,dept_name) VALUES ('".$user_id."','".$dep."')";
        mysqli_query($ms,$q);
        $dep_id = mysqli_insert_id($ms);
    }
    return $dep_id;
}

function getSessionID($depid){
    global $user_id,$ms;
    $sec_id=0;
    $q="SELECT section_id FROM dsection where dept_id='".$depid."' and user_id='".$user_id."' and section_name='A'";
    // echo $q;
    $r=mysqli_query($ms,$q);
    if(mysqli_num_rows($r)>0){
        $row=mysqli_fetch_assoc($r);
        $sec_id=$row['section_id'];
    }else{
        $q="INSERT INTO dsection (user_id,dept_id,section_name) VALUES ('".$user_id."','".$depid."','A')";
        // echo $q;
        mysqli_query($ms,$q);
        $sec_id = mysqli_insert_id($ms);
    }
    return $sec_id;
}

function getObjectIMEI($object){
    global $user_id,$ms;
    $object=str_replace(' ', '', $object);
    // $q="SELECT imei FROM gs_objects WHERE REPLACE(`name`, ' ', '') LIKE '%".$object."%' and user_id='".$user_id."' ";
    $q="SELECT a.imei FROM gs_objects a LEFT JOIN gs_user_objects b ON a.imei=b.imei WHERE REPLACE(a.name, ' ', '') LIKE '%".$object."%' AND b.user_id='".$user_id."'";
    
    $r=mysqli_query($ms,$q);
    if(mysqli_num_rows($r)>0){
        $row=mysqli_fetch_assoc($r);
        return $row['imei'];
    }else{
        return '';
    }
}
    

?>