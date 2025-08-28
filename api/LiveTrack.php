<?
	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Methods: GET, POST');
	header("Access-Control-Allow-Headers: X-Requested-With");

	date_default_timezone_set("Asia/Kolkata");

	 include ('../init.php');
   	 include ('Base.php');  
	 
   	 
	$myfile = fopen("log.txt", "a");
	fwrite($myfile,$ip="Request From : ".get_client_ip().' From Client: '.json_encode($_POST)." Time :".gmdate("Y-m-d H:i:s"));
	fwrite($myfile, "\n");
	fclose($myfile);

   	 loadLanguage($gsValues['LANGUAGE']);
   	 
	$user_id="";
	$imei="";
	$key="";
	$ip="";
	$result=array();
	
	$result["type"]="";
	$result["msg"]="";
	//$result["data"]="";
	
	
	$ip=get_client_ip();

          if(@$_POST['cmd'] == 'DeviceTrack')
          {
          
           if(isset($_POST['deviceid']) && isset($_POST['key']))
           {
            $dt=date("Y-m-d H:i:s");
            
            
            $dtf=date('Y-m-d H:i:s',strtotime('+30 minutes',strtotime($dt)));       
            $dtt=date('Y-m-d H:i:s',strtotime('-1hour -30 minutes',strtotime($dt)));    
            
            $q1 = "SELECT gu.dt_tracker,gu.lat,gu.lng,gt.imei,gu.speed,gu.altitude,gu.angle,gu.dt_tracker FROM gs_user_objects gt join  gs_objects gu on gu.imei=gt.imei where gu.name='".$_POST['deviceid']."'   LIMIT 1";
              $r1 = mysqli_query($ms,$q1);
              if ($row1 = mysqli_fetch_array($r1))
              {
                $imei=$row1['imei'];
              $q0 = "SELECT * from api_setting  where apikey='".$_POST['key']."' LIMIT 1";
  
                $r0 = mysqli_query($ms,$q0);
                //if(true)
                if ($row0 = mysqli_fetch_array($r0))
                {
                      $result["type"]="Success";
                      $uri="https://www.google.com/maps?q=".$row1['lat'].",".$row1['lng']."&t=m&z=15&output=embed";
                      $result["data"]=$uri;
                      $result["lat"]=$row1['lat'];
                      $result["lng"]=$row1['lng'];
                      $result["speed"]=$row1['speed'];
                      $result["altitude"]=$row1['altitude'];
                      $result["angle"]=$row1['angle'];
                      $result["dt_tracker"]=date("d-m-Y H:i:s",strtotime($row1['dt_tracker']."+ 5 hour + 30 minutes"));
              
                  $dt_now = gmdate("Y-m-d H:i:s");
                $dt_difference = strtotime($dt_now) - strtotime($result["dt_tracker"]);

                if($dt_difference < 1440 * 60)
              {
                $conn_valid = '1';
              }
              else
              {
                $conn_valid = '0';
              }
              
              if($conn_valid=='0')
              {
                $result["type"]="Error";
              }
              
              ///echo "<center><img width=\"250px\" height=\"80px\" id=\"logo\" src=\"http://track.playgps.co.in/plytrack/img/logo.png\"></center>";
              //echo "<br><br>";  
                      //echo "<iframe style=\"width:100%;height:80%\" src=".$uri."></iframe>";
                }
                else
                {
                      $result["type"]="Error";
                      $result["msg"]="Invalid Trip";
                }
               }
              else 
              {
                $result["type"]="Error";
                $result["msg"]="Invalid Vehicle";
              }
            }
            else 
            {
              $result["type"]="Invalid Data";
            }
          } 
				else if(@$_POST['cmd'] == 'LiveByUser')
    			{
    			
    			 if(isset($_POST['deviceid']) && isset($_POST['key']))
  				 {
   					$dt=date("Y-m-d H:i:s");
   					$q1 = "SELECT gu.dt_tracker,gu.lat,gu.lng,gt.imei,gu.speed,gu.altitude,gu.angle,gu.dt_tracker FROM gs_user_objects gt join  gs_objects gu on gu.imei=gt.imei where gu.name='".$_POST['deviceid']."'   LIMIT 1";
        			$r1 = mysqli_query($ms,$q1);
        			if ($row1 = mysqli_fetch_array($r1))
        			{
        				$imei=$row1['imei'];
   						$q0 = "SELECT * from alttrip  where imei='".$imei."' and tripkey='".$_POST['key']."' and  date_from<='".$dt."' and date_to>='".$dt."'  LIMIT 1";   						        		
/*DELETE THIS QUERY  */ 						$q0 = "SELECT * from alttrip  where imei='".$imei."' and tripkey='".$_POST['key']."'  LIMIT 1";
        				$r0 = mysqli_query($ms,$q0);
        				//if(true)
		        		if ($row0 = mysqli_fetch_array($r0))
        				{
	                		$result["type"]="Success";
	                		$uri="https://www.google.com/maps?q=".$row1['lat'].",".$row1['lng']."&t=m&z=15&output=embed";
	                		$result["data"]=$uri;
	                		$result["lat"]=$row1['lat'];
	                		$result["lng"]=$row1['lng'];
	                		$result["speed"]=$row1['speed'];
	                		$result["altitude"]=$row1['altitude'];
	                		$result["angle"]=$row1['angle'];
	                		$result["dt_tracker"]=date("d-m-Y H:i:s",strtotime($row1['dt_tracker']."+ 5 hour + 30 minutes"));
							
        					$dt_now = gmdate("Y-m-d H:i:s");
						  	$dt_difference = strtotime($dt_now) - strtotime($result["dt_tracker"]);
							if($dt_difference < 5 * 60)
							{
								$result["conn_valid"] = '1';
							}
							else
							{
								$result["conn_valid"] = '0';
							}
				
							///echo "<center><img width=\"250px\" height=\"80px\" id=\"logo\" src=\"http://track.playgps.co.in/plytrack/img/logo.png\"></center>";
							//echo "<br><br>";	
                			//echo "<iframe style=\"width:100%;height:80%\" src=".$uri."></iframe>";
        				}
        				else
        				{
	           		  		$result["type"]="Error";
	           		  		$result["msg"]="Invalid Trip";
        				}
      				 }
        			else 
        			{
		        		$result["type"]="Error";
		        		$result["msg"]="Invalid Vehicle";
        			}
   					}
   					else 
   					{
   						$result["type"]="Invalid Data";
   					}
    			}
    			
    			else 
    			
    			if(@$_POST['cmd'] == 'LiveTrack')
    			{
    			
    			 if(isset($_POST['deviceid']) && isset($_POST['key']))
  				 {
   					$dt=date("Y-m-d H:i:s");
   					
   					
   					$dtf=date('Y-m-d H:i:s',strtotime('+30 minutes',strtotime($dt)));				
   					$dtt=date('Y-m-d H:i:s',strtotime('-1hour -30 minutes',strtotime($dt)));		
   					
   					$q1 = "SELECT gu.dt_tracker,gu.lat,gu.lng,gt.imei,gu.speed,gu.altitude,gu.angle,gu.dt_tracker FROM gs_user_objects gt join  gs_objects gu on gu.imei=gt.imei where gu.name='".$_POST['deviceid']."'   LIMIT 1";
        			$r1 = mysqli_query($ms,$q1);
        			if ($row1 = mysqli_fetch_array($r1))
        			{
        				$imei=$row1['imei'];
   						$q0 = "SELECT * from droute_events_daily  where imei='".$imei."' and tripkey='".$_POST['key']."' and  datefrom<='".$dtf."' and dateto>='".$dtt."'  LIMIT 1";
  
        				$r0 = mysqli_query($ms,$q0);
        				//if(true)
		        		if ($row0 = mysqli_fetch_array($r0))
        				{
	                		$result["type"]="Success";
	                		$uri="https://www.google.com/maps?q=".$row1['lat'].",".$row1['lng']."&t=m&z=15&output=embed";
	                		$result["data"]=$uri;
	                		$result["lat"]=$row1['lat'];
	                		$result["lng"]=$row1['lng'];
	                		$result["speed"]=$row1['speed'];
	                		$result["altitude"]=$row1['altitude'];
	                		$result["angle"]=$row1['angle'];
	                		$result["dt_tracker"]=date("d-m-Y H:i:s",strtotime($row1['dt_tracker']."+ 5 hour + 30 minutes"));
							
        					$dt_now = gmdate("Y-m-d H:i:s");
						  	$dt_difference = strtotime($dt_now) - strtotime($result["dt_tracker"]);

						  	if($dt_difference < 1440 * 60)
							{
								$conn_valid = '1';
							}
							else
							{
								$conn_valid = '0';
							}
							
							if($conn_valid=='0')
							{
								$result["type"]="Error";
							}
							
							///echo "<center><img width=\"250px\" height=\"80px\" id=\"logo\" src=\"http://track.playgps.co.in/plytrack/img/logo.png\"></center>";
							//echo "<br><br>";	
                			//echo "<iframe style=\"width:100%;height:80%\" src=".$uri."></iframe>";
        				}
        				else
        				{
	           		  		$result["type"]="Error";
	           		  		$result["msg"]="Invalid Trip";
        				}
      				 }
        			else 
        			{
		        		$result["type"]="Error";
		        		$result["msg"]="Invalid Vehicle";
        			}
   					}
   					else 
   					{
   						$result["type"]="Invalid Data";
   					}
    			}
    			else if(@$_POST['cmd'] == 'load_marker' && isset($_POST['key']))
    			{
    				$result = array();

    				$q = "SELECT * FROM `droute_events_daily` WHERE `tripkey`='".$_POST['key']."'  LIMIT 1";
        $r = mysqli_query($ms,$q);
        if ($row = mysqli_fetch_array($r))
        {
        		$user_id=$row['user_id'];

				if($row['route_id']==null)
        		$q = "SELECT * FROM `gs_user_markers`	WHERE `user_id`='".$user_id."' ORDER BY `marker_name` ASC";
        		else 
        		$q = "SELECT * FROM `gs_user_markers`	WHERE marker_name in (SELECT distinct zone_name FROM `gs_user_zones`	WHERE `user_id`='".$user_id."' and zone_id in(select zonecode from droute_sub where route_id='".$row['route_id']."') ) ORDER BY `marker_name` ASC;";
        		
        		$r = mysqli_query($ms,$q);
        		while($row=mysqli_fetch_array($r))
        		{
        			$marker_id = $row['marker_id'];
        			$result[$marker_id]['visible'] = true;

        			$result[$marker_id]['data'] = array(	'name' => $row['marker_name'],
								'desc' => $row['marker_desc'],
								'icon' => $row['marker_icon'],
								'visible' => $row['marker_visible'],
								'lat' => $row['marker_lat'],
								'lng' => $row['marker_lng'],
        			);
        		}
        }

    				echo json_encode($result);
    				die;
    			}
    			
    			else if(@$_POST['cmd'] == 'load_marker_data' && isset($_POST['key']))
    			{
    				$result = array();

    				$q = "SELECT * FROM `alttrip` WHERE `tripkey`='".$_POST['key']."'  LIMIT 1";
        $r = mysqli_query($ms,$q);
        if ($row = mysqli_fetch_array($r))
        {
        		$user_id=$row['user_id'];


        		$q = "SELECT * FROM `gs_user_markers`	WHERE `user_id`='".$user_id."' ORDER BY `marker_name` ASC";
        		$r = mysqli_query($ms,$q);
        		while($row=mysqli_fetch_array($r))
        		{
        			$marker_id = $row['marker_id'];
        			$result[$marker_id]['visible'] = true;

        			$result[$marker_id]['data'] = array(	'name' => $row['marker_name'],
								'desc' => $row['marker_desc'],
								'icon' => $row['marker_icon'],
								'visible' => $row['marker_visible'],
								'lat' => $row['marker_lat'],
								'lng' => $row['marker_lng'],
        			);
        		}
        }

    				echo json_encode($result);
    				die;
    			}
    			else 
    			{
    				
    				$result["type"]="Invalid Command";
    			}
    			
    			header('Content-type: application/json');
        	    echo json_encode($result);	
        		die;    
	    

?>
