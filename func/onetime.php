<?
	//CODE BY VETRIVEL.N
	
	include ('../init.php');
	include ('fn_common.php');
	include ('fn_route.php');
	set_time_limit(0);
	
	///echo  dechex("25197".date("s"));
	//echo "vetri"
	
	//FOR SALCOMP SERVER PUSHING FAILED DATA LOG 
	// dt_swipe between '2018-07-23 00:00:00'  and '2018-07-23 09:59:59' and 
	$qv="select * from gs_rfid_swipe_data where status=0 and
	 imei in (select imei from gs_user_objects where user_id=726)";
	$rv= mysqli_query($ms,$qv);
	$iv=1;
	while($row=mysqli_fetch_assoc($rv))
	{
		
		 $url="http://220.225.211.131:2544/api/StoreRFID?Key=PlaySalcompv*0*&CardId=".$row["rfid"]."&Machine=".$row["imei"]."&gname=".$row["gname"]."&routeno=".$row["routeno"]."&dttime=".str_replace(" ","%20",$row['dt_swipe'])." ";
		
		//echo "<br>";
		
		$options = array(
					'http' => array(
					'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
					'method'  => 'GET'//,
		)
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		
		$myfile = fopen("vvvsalcomppushlog.txt", "a");
		fwrite($myfile,$url .' -> '.json_encode($result) );
		fwrite($myfile, "\n");
		fclose($myfile);
		
		if($result==false)
		{
			echo  $iv.'failed';
		}
		else 
		{
			$qi="update gs_rfid_swipe_data set status=1 where dt_swipe='".$row['dt_swipe']."' and rfid='".$row['rfid']."';";
			$rvi= mysqli_query($ms,$qi);
			echo "<br>";
			//echo  $iv;
		}
		$iv++;
	}
	
	/*
	$eventlist=array();
					$eventlist["overspeed"]="OVER SPEED";
					$eventlist["underspeed"]="UNDER SPEED";
					$eventlist["gpsantcut"]="GPS ANTENNA CUT";
					$eventlist["fuelstolen"]="FUEL SIPHON";
					$eventlist["lowfuel"]="LOW FUEL";
					$eventlist["haccel"]="HARSH ACCELERATION";
					$eventlist["hbrake"]="HARSH BRAKING";
					$eventlist["hcorn"]="HARSH CORNERING";
					echo json_encode($eventlist);die;
	
$url   = "http://117.202.8.55/PlayGPSWebService/PlayGPSWs?wsdl";

// web service input params
$request_param = array(
    "routename" => 1,
    "employeecode" => 2,
    "imeinumber" => 3
);


$client = new SoapClient($url, array("trace" => 1, "exception" => 0)); 

// Call wsdl function 
//$result = $client->__soapCall("authenticateUser",$request_param, NULL); 
$result = $client->__soapCall("seatingArrangement",array("tripid" => 1), NULL);

// Echo the result 
echo "<pre>".print_r($result, true)."</pre>"; 


	

/*
	$url = 'http://prrsvc.winstrata.com/Service.svc/Complaints';
    $data = array("api" => "Ti0Kf5Wyx/4=","date" => "10-10-2017 20:54:03","vehicle_number"=>"TN 21 BD 1238",
    "driver_code" =>"","lat_lon"=>"12.837736333333334,79.89758966666666","issue_type"=>"Nope" );
    $dv=array();
    $dv[]=$data;
    $data = array("data" => $dv);
    $data_string = json_encode($data);
	
	$options = array(
							'http' => array(
							'header'  => "Content-type:application/json",
							'method'  => 'POST',
							'content' => $data_string
	)
	);
		
	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
   
    echo ($result);
    die;
    

	if(@$_GET["sharingan"]!="Vetrivel...")
	{
		echo  "Wrong Sharingan by N.Vetrivel :P". "<br>";
		die;
	}
	
	global $ms;
	
	
	//FOR SALCOMP SERVER PUSHING FAILED DATA LOG
	$qv="select * from gs_rfid_swipe_data where dt_swipe between '2017-12-07 13:56:07'  and '2017-12-13 3:19:00' and imei in (select imei from gs_user_objects where user_id=726) ";
	$rv= mysqli_query($ms,$qv);
	$iv=1;
	while($row=mysqli_fetch_assoc($rv))
	{
		echo $url="http://220.225.211.131:2544/api/StoreRFID?Key=PlaySalcompv*0*&CardId=".$row["rfid"]."&Machine=".$row["imei"]."&gname=".$row["gname"]."&routeno=".$row["routeno"]."&dttime=".str_replace(" ","%20",$row['dt_swipe'])."";
		
		echo "<br>";
		
		$options = array(
					'http' => array(
					'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
					'method'  => 'GET'//,
		)
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		
		$myfile = fopen("vvvsalcomppushlog.txt", "a");
		fwrite($myfile,$url .' -> '.json_encode($result) );
		fwrite($myfile, "\n");
		fclose($myfile);
		
		if($result==false)
		{
			echo  $iv.'failed';
		}
		else 
		{
			echo  $iv;
		}
		$iv++;
	}
	
	//echo json_encode($gsValues['POST_RFID']);
	

echo "<br>".$curdateindian=date('Y-m-d H:i:s',strtotime(gmdate("Y-m-d H:i:s")."+ 5 hour + 30 minutes"));
echo "<br>".$curdateindian=date('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s")));
echo "<br>". $day_of_week = gmdate('w', strtotime($curdateindian));
echo "<br>". $day_of_week = gmdate('w', strtotime("2017-10-8 "));
		$week_days = explode(',', "true,true,true,true,true,true,true,");
echo "<br>".json_encode($week_days);
echo "<br>".$yesterday = gmdate("Y-m-d H:i:s", strtotime($curdateindian)-86400);
		die;
		
	//echo $gsValues['POST_RFID'][726]["url"];
	//die;
	
	
		$qgetuser="select userapikey from  gs_users where  id=".$gsValues['POST_RFID'][726]["id"];
		$rslt= mysqli_query($ms,$qgetuser);
		if ($row=mysqli_fetch_assoc($rslt))
		{
			
			$url = 'http://220.225.211.131:2544/api/GetCSN?Key='.$row["userapikey"];
			$options = array(
								'http' => array(
								'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
								'method'  => 'GET'//,
			)
			);
			
			$context  = stream_context_create($options);
			$result = file_get_contents($url, false, $context);
			$result=json_decode($result, true);
			if($result["Type"]=="S")
			{
				
				$rtncsn=mysqli_query($ms,"Delete from csnmast");
				for($ivel=0;$ivel<count($result["Mydata"]);$ivel++)
				{
					$cardnoo=$result["Mydata"][$ivel]["CARDNO"];
					$cardnoo=str_pad($cardnoo,10,"0",STR_PAD_LEFT);
				
					$qincsn=" insert into csnmast values('".$result["Mydata"][$ivel]["EMPID"]."',
					'".$cardnoo."','".$result["Mydata"][$ivel]["FIRSTNAME"]."'
					,'".$result["Mydata"][$ivel]["GNAME"]."','".$result["Mydata"][$ivel]["ROUTENO"]."');";
					$rtncsn=mysqli_query($ms,$qincsn);
				}
				
			}
		}
	
	*/
	
	/*
	function cloneDatabase($dbName, $newDbName){
    global $admin,$ms;
    
    $getTables  =   mysqli_query("SHOW TABLES");   
    $tables =   array();
    while($row = mysqli_fetch_row($getTables)){
        $tables[]   =   $row[0];
    }
    $createTable    =   $getTables("CREATE DATABASE `$newDbName` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;") or die(mysql_error());
    foreach($tables as $cTable){
        $db_check   =   @mysql_select_db ( $newDbName );
        $create     =   $admin->query("CREATE TABLE $cTable LIKE ".$dbName.".".$cTable);
        if(!$create) {
            $error  =   true;
        }
        $insert     =   $admin->query("INSERT INTO $cTable SELECT * FROM ".$dbName.".".$cTable);
    }
    return !isset($error);
	}



	$clone  = cloneDatabase('dbname','newdbname');  // first: toCopy, second: new database

	*/

	/*
	global $ms;
	
	$cnt=0;
	$q = "select * from gs_objects_sensors where type='fuel' order by sensor_id asc";
	$r = mysqli_query($ms,$q);
	$qry="";
	while ($row = mysqli_fetch_assoc($r))
	{
		$strdata=$row["formula"];
		$strdataary=explode('|',$strdata);
		if(count($strdataary)>1)
		{
			$cnt++;
		$for="X*".$strdataary[1];
		$qry .="update gs_object_sensors set formula='".$for."' where sensor_id='".$row["sensor_id"]."' ; ";
		}
	}
	
	echo $qry."->";
	
	*/
	
	

	/*
	$sql = "select * from gs_user_events  pe where pe.checked_value like'%|%';";
	$result = mysqli_query($ms, $sql);
	
	if (mysqli_num_rows($result) > 0) {
    // output data of each row
    	while($row = mysqli_fetch_assoc($result)) {
    		
    		$valall=explode('|',$row["checked_value"]);
    		$velvalue="";
    		if($valall==3)
    		{
    			$velvalue= '[{"src":"'.$valall[0].'","cn":"'.$valall[1].'","val":"'.$valall[2].'"}]';
    			echo "<br>";
    		}
    		else
    		{
    			if($valall[0]==$valall[3] && $valall[1]==$valall[4]&& $valall[2]==$valall[5])
    			{
    				$velvalue= '[{"src":"'.$valall[0].'","cn":"'.$valall[1].'","val":"'.$valall[2].'"}]';
    				echo "<br>";
    			}
    			else
    			{
    				$velvalue= '[{"src":"'.$valall[0].'","cn":"'.$valall[1].'","val":"'.$valall[2].'"},
    				  {"src":"'.$valall[3].'","cn":"'.$valall[4].'","val":"'.$valall[5].'"}]';
    				echo "<br>";
    			}
    		}
    		
	        $qb = "update gs_user_events set 
	        checked_value = '".$velvalue."' where event_id= '".$row["event_id"]."' ; ";
			//$rb = mysqli_query($ms,$qb);
				echo $qb;
	    }
	} else {
	    echo "0 results";
	}

	*/

	//echo  "<br> <br> <br> <br> <br> Execution Finished !";
?>
