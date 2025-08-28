<?
date_default_timezone_set("Asia/Kolkata");

	session_start();
	include ('../init.php');
	include ('fn_common.php');
    include ('fn_route.php');
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
	
	if(@$_POST['cmd'] == 'live_driver_employee')
    {
        $imei = $_POST["imei"];
        $responce=null;      
        $q = "select *  from rfidgps  WHERE   imei='".$imei."' and endtime is  null and status='Open' and user_id='".$user_id."'"; 
        $r = mysqli_query($ms,$q);

        if(mysqli_num_rows($r) > 0 )
        {
            $earr=array();
            while($rda=mysqli_fetch_array($r))
            {
                $responce[]=array("driver"=>$rda["drivername"]);
                $route= detailrfidtripreport($imei,$rda["starttime"],'Open',$rda["driverrfid"]);
           


           for ($i=0; $i<count($route); ++$i)
           {
               $dte=null;
               if($route[$i]["endtime"]==null)
               {
                 $dte=convUserTimezone(gmdate("Y-m-d H:i:s"));
               }
               else
               {
                 $dte=$route[$i]["endtime"];  
               }
               
               
                $emp= detailrfidtripemployee($imei,$route[$i]["starttime"],$dte,$route[$i]["driverrfid"]);
           
                if (count($emp) > 0)
                {
                    for ($iv=0; $iv<count($emp); ++$iv)
                    {
                       if($emp[$iv]["endtime"]==null)
                       {
                         $earr[]=array($emp[$iv]["name"]);  
                       } 
                        
                    }
                }
                
           }
           
          
            }    
            
             $responce[]=array("employee"=>array($earr));
        }
        
        header('Content-type: application/json');
        echo json_encode($responce);
        
        die;
    }
    

	if(@$_POST['cmd'] == 'save_department')
	{
		$dept_id = $_POST["dept_id"];
		$dept_name = $_POST["dept_name"];
		
		$q = "select * from rfiddept WHERE	`dept_name`='".$dept_name."' and dept_id !='".$dept_id."' and user_id='".$user_id."' ";
		$r = mysqli_query($ms,$q);
	
		
	
		if(mysqli_num_rows($r) == 0)
		{
		if ($dept_id == '0')
		{
			$q = "INSERT INTO `rfiddept` (dept_name,user_id) VALUES ('".$dept_name."','".$user_id."')";
		}
		else
		{
			$q = "UPDATE `rfiddept`  SET  `dept_name`='".$dept_name."'  WHERE	`dept_id`='".$dept_id."' and user_id='".$user_id."'";	
		}
		
		$r = mysqli_query($ms,$q);
		
		echo 'OK';
		
		}
		else {
			echo 'Name Already Exists';
		}
		
		die;
	}
	if(@$_POST['cmd'] == 'delete_department')
	{
		$dept_id = $_POST["dept_id"];
				
		$q = "delete from rfiddept  WHERE	dept_id='".$dept_id."' and user_id='".$user_id."'";	
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
		
	
		$q = "SELECT DISTINCT	dept_id,dept_name FROM rfiddept where user_id='".$user_id."' ORDER BY dept_name ASC";
		
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
				
				$modify = '<a href="#" onclick="rfidtripdepartmentedit(\''.$row['dept_id'].'\');" title="Edit"><img src="theme/images/edit.svg"></a><a href="#" onclick="rfidtripdepartmentdelete(\''.$row['dept_id'].'\');" title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>';
			
				
				$responce->rows[$i]['id'] = $i;
				$responce->rows[$i]['cell']=array($row['dept_name'], $modify,$row['dept_id']);
				$i++;
			}
		}
	
		header('Content-type: application/json');
		echo json_encode($responce);
		die;
	}

	if(@$_POST['cmd'] == 'save_employee')
	{
		$uid = $_POST["uid"];
		$dept_id = $_POST["dept_id"];
		$name = $_POST["name"];
		$gender = $_POST["gender"];
		$rfidid = $_POST["rfidid"];
	
		
		$q = "select * from rfidemployee WHERE	`rfidid`='".$rfidid."' and  `uid`!='".$uid."' and user_id='".$user_id."' ";
		$r = mysqli_query($ms,$q);
	

		if(mysqli_num_rows($r) == 0 )
		{
			
		if ($uid == '0')
		{
			$q = "INSERT INTO `rfidemployee` (dept_id,name,gender,rfidid,user_id) VALUES ('".$dept_id."','".$name."','".$gender."','".$rfidid."','".$user_id."')";
		}
		else
		{
			$q = "UPDATE `rfidemployee`  SET  `dept_id`='".$dept_id."',`name`='".$name."',`gender`='".$gender."',`rfidid`='".$rfidid."'  WHERE `uid`='".$uid."' and user_id='".$user_id."'";	
		}
		
		$r = mysqli_query($ms,$q);
		
		echo 'OK';
		}
		else
	    {
			echo 'Name Already Exists';
		}

	
		
		die;
	}
	if(@$_POST['cmd'] == 'delete_employee')
	{
		$uid = $_POST["uid"];
		$q = "delete from rfidemployee  WHERE	uid='".$uid."' and user_id='".$user_id."'";	
		$r = mysqli_query($ms,$q);
		
		echo 'OK';
		die;
	}
	else if(@$_GET['cmd'] == 'select_employee')
	{
		
			
		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
		
		$total_pages = 1;
	
		
		$strq="";
		if( $_GET['name']!="")
		{
			$name = $_GET['name'];
			$strq=" and (re.dept_id like'%".$name."%' or rd.dept_name like '%".$name."%' or re.name like'%".$name."%'  or re.rfidid like '%".$name."%'  )";	
		} 
		
	
		$q = "select re.*,rd.dept_id,rd.dept_name from rfidemployee re  join rfiddept rd on rd.dept_id=re.dept_id and rd.user_id=re.user_id and re.user_id='".$user_id."' ".$strq." order by rd.dept_name asc,re.name ";
			
		
			
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
				
				$modify = '<a href="#" onclick="rfidemployeeedit(\''.$row['uid'].'\');" title="Edit"><img src="theme/images/edit.svg"></a><a href="#" onclick="rfidemployeedelete(\''.$row['uid'].'\');" title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>';
			
				
				$responce->rows[$i]['id'] = $i;
				$responce->rows[$i]['cell']=array($row['uid'],$row['dept_id'],$row['dept_name'],$row['name'],$row['rfidid'],$row['gender'], $modify);
				$i++;
			}
		}
	
	
		header('Content-type: application/json');
		echo json_encode($responce);
		die;
	}

	
	if(@$_POST['cmd'] == 'save_driverrfid')
	{
		$uid = $_POST["driverrfid"];
		$driverrfidno = $_POST["driverrfidno"];
		
		$q = "select * from driverrfid WHERE	`driverrfid`='".$driverrfidno."' and uid !='".$uid."' and user_id='".$user_id."' ";
		$r = mysqli_query($ms,$q);
	
		
	
		if(mysqli_num_rows($r) == 0)
		{
		
			$q = "INSERT INTO `driverrfid` (driverrfid,user_id) VALUES ('".$driverrfidno."','".$user_id."')";
		
			$r = mysqli_query($ms,$q);
		
		echo 'OK';
		
		}
		else {
			echo 'RFID Already Exists';
		}
		
		die;
	}
	if(@$_POST['cmd'] == 'delete_driverrfid')
	{
		$uid = $_POST["driverrfid"];
				
		$q = "delete from driverrfid  WHERE	uid='".$uid."' and user_id='".$user_id."'";	
		$r = mysqli_query($ms,$q);

		
		
		echo 'OK';
		die;
	}
	
	else if(@$_GET['cmd'] == 'select_driverrfid')
	{
			
		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
		
		if(!$sidx) $sidx =1;
		
		// get records number
	
	
		$total_pages = 1;
		
	
		$q = "SELECT DISTINCT	* FROM driverrfid where user_id='".$user_id."' ORDER BY uid ASC";
		
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
				
				$modify = '<a href="#" onclick="rfidtripdriverdelete(\''.$row['uid'].'\');" title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>';
			
				
				$responce->rows[$i]['id'] = $i;
				$responce->rows[$i]['cell']=array($row['uid'],$row['driverrfid'], $modify);
				$i++;
			}
		}
	
		header('Content-type: application/json');
		echo json_encode($responce);
		die;
	}
	
	
	if(@$_POST['cmd'] == 'save_gpsissue')
	{
		
		$imei = $_POST["imei"];
		$driverrfid = $_POST["driverrfid"];
		$vehicleno = $_POST["vehicleno"];
		$vendorname = $_POST["vendorname"];
		$drivername = $_POST["drivername"];
		$driverphone = $_POST["driverphone"];
		$stattime = gmdate("Y-m-d H:i:s");
		
		$q = "select * from rfidgps WHERE	(`driverrfid`='".$driverrfid."' or imei='".$imei."')and endtime is null  and user_id='".$user_id."' ";
		$r = mysqli_query($ms,$q);
	
		if(mysqli_num_rows($r) == 0)
		{
		
			$q = "INSERT INTO `rfidgps` (imei,driverrfid,vehicleno,vendorname,drivername,driverphone,starttime,status,user_id) VALUES  ('".$imei."', '".$driverrfid."','".$vehicleno."','".$vendorname."','".$drivername."','".$driverphone."','".$stattime."','Open','".$user_id."')";
		
			$r = mysqli_query($ms,$q);
		
		echo 'OK';
		
		}
		else
		{
			echo 'Check Previouse GPS issue (Object / Driver RFID allocated)';
		}
		
		die;
	}
	if(@$_POST['cmd'] == 'delete_gpsissue')
	{
		$uid = $_POST["uid"];
				
		$q = "delete from rfidgps  WHERE	uid='".$uid."' and user_id='".$user_id."'";	
		$r = mysqli_query($ms,$q);

		
		
		echo 'OK';
		die;
	}
	if(@$_POST['cmd'] == 'gpsissue_final_close')
	{
		$uid = $_POST["uid"];
		$reasontxt = $_POST["reasontxt"];
		$endtime = gmdate("Y-m-d H:i:s");
				
		$q = "update rfidgps set reason='".$reasontxt."',status='Close',endtime='".$endtime."' WHERE uid='".$uid."' and user_id='".$user_id."'";	
		$r = mysqli_query($ms,$q);

	
		
		echo 'OK';
		die;
	}
	else if(@$_GET['cmd'] == 'select_gpsclose')
	{
			
		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
		
		if(!$sidx) $sidx =1;
		
		// get records number
	
	
		$total_pages = 1;
		
		$strq="";
		if( $_GET['name']!="")
		{
			$name = $_GET['name'];
			$strq=" and (r.imei like'%".$name."%' or r.driverrfid like '%".$name."%' or r.vehicleno like'%".$name."%'  or r.vendorname like '%".$name."%'  or r.drivername like'%".$name."%'  or r.driverphone like '%".$name."%'  )";	
		} 
	
		$q = "select r.*,g.name from rfidgps r join gs_objects g on r.imei=g.imei and r.user_id='".$user_id."' ".$strq." and r.endtime is null ORDER BY r.uid ASC";

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
				
				$modify = '<a href="#" onclick="rfidgpsissueclose(\''.$row['uid'].'\');" title="'.$la['GPSRECEIPTC'].'"><img src="theme/images/img/receipt.png" /></a><a href="#" onclick="rfidgpsissuedelete(\''.$row['uid'].'\');" title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>';
			
				$responce->rows[$i]['id'] = $i;
				$responce->rows[$i]['cell']=array($row['uid'],$row['imei'],$row['name'],$row['driverrfid'],$row['vehicleno'],$row['vendorname'],$row['drivername'],$row['driverphone'],convUserTimezone( $row['starttime']), $modify);
				$i++;
			}
		}
	
		header('Content-type: application/json');
		echo json_encode($responce);
		die;
	}
	
	if(@$_GET['cmd'] == 'select_gpsreport')
    {
            
        $page = $_GET['page']; // get the requested page
        $limit = $_GET['rows']; // get how many rows we want to have into the grid
        $sidx = $_GET['sidx']; // get index row - i.e. user click to sort
        $sord = $_GET['sord']; // get the direction
        
        if(!$sidx) $sidx =1;
        
        // get records number
    
    
        $total_pages = 1;
        
        $strq="";
        if( $_GET['name']!="")
        {
            $name = $_GET['name'];
            $strq=" and (r.imei like'%".$name."%' or r.driverrfid like '%".$name."%' or r.vehicleno like'%".$name."%'  or r.vendorname like '%".$name."%'  or r.drivername like'%".$name."%'  or r.driverphone like '%".$name."%'  )";  
        } 
    
        $q = "select r.*,g.name from rfidgps r join gs_user_trackers g on r.imei=g.imei and r.user_id='".$user_id."' ".$strq." and r.endtime is null ORDER BY r.uid ASC";

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
                $mileage=0;                
                $modify = '<a href="#" onclick="rfidgpsissuereport(\''.$row['uid'].'\');" title="'.$la['RFIDREPORT'].'"><img src="theme/images/img/report.png" /></a>';
            
                $responce->rows[$i]['id'] = $i;
                $responce->rows[$i]['cell']=array($row['uid'],$row['imei'],$row['name'],$row['vehicleno'],$row['vendorname'],$row['drivername'],$row['driverphone'],convUserTimezone( $row['starttime']),convUserTimezone( $row['endtime']),$mileage, $modify);
                $i++;
            }
        }
    
        header('Content-type: application/json');
        echo json_encode($responce);
        die;
    }

   
    
?>