<?
/*File Created and code Written by NR.Vetrivel*/

// if (in_array($_POST['imei'], $blockimei))
// {
// 	$myfile = fopen("vvv.txt", "a");
// 	fwrite($myfile,json_encode($_POST));
// 	fwrite($myfile, "\n");
// 	fclose($myfile);
// 	echo "DATAOK";
// 	$_POST["method"]='';
// 	die; 
// }

//KRC user id : 137

global $ms;
// $q="SELECT * FROM gs_objects a left join gs_user_objects b on a.imei=b.imei where b.user_id='1447' and a.imei='".$_POST['imei']."'";
// $r=mysqli_query($ms,$q);
// if(mysqli_num_rows($r)>0){
// 	echo "DATAOK";
// 	$_POST["method"]='';
// 	die;
// }



	$devicelisti=array();

	//$devicelisti[]="862212128001947" ; $devicelisti[]="862212128001993" ; $devicelisti[]="862212128001939" ;
	//$devicelisti[]="862212128001957" ; $devicelisti[]="862212128001977" ; $devicelisti[]="862212128001959" ;
	//$devicelisti[]="862212128001945" ; $devicelisti[]="862212128001937" ; $devicelisti[]="862212128002011" ;
	//$devicelisti[]="862212128001948" ; $devicelisti[]="862212128001951" ; $devicelisti[]="862212128001994" ;
	//$devicelisti[]="862212128002006" ; $devicelisti[]="862212128001954" ; $devicelisti[]="862212128001998" ;	
	//$devicelisti[]="862212128001958" ; $devicelisti[]="862212128002013" ; $devicelisti[]="862212128001992" ;



$devicelisti[]="862212128002011" ;$devicelisti[]="862212128001948" ;$devicelisti[]="862212128001951" ;$devicelisti[]="862212128001994" ;$devicelisti[]="862212128002006" ;$devicelisti[]="862212128001954" ;$devicelisti[]="862212128001998" ;$devicelisti[]="862303128001483" ;$devicelisti[]="862212128002013" ;$devicelisti[]="862212128001993" ;$devicelisti[]="862212128001939" ;$devicelisti[]="862304128000865" ;$devicelisti[]="862304128000854" ;$devicelisti[]="862306128002346" ;$devicelisti[]="862304128000897" ;$devicelisti[]="862304128000852" ;$devicelisti[]="862304128000863" ;$devicelisti[]="862304128000870" ;$devicelisti[]="862212128001947" ;$devicelisti[]="862212128001992" ;$devicelisti[]="862306128002347" ;$devicelisti[]="862212128001937" ;$devicelisti[]="862212128001945" ;$devicelisti[]="862306128002470" ;$devicelisti[]="862403128002895" ;$devicelisti[]="862403128002906" ;$devicelisti[]="862403128002886" ;$devicelisti[]="862403128002909" ;$devicelisti[]="862401128000433" ;$devicelisti[]="862403128002795" ;$devicelisti[]="862306128001905" ;$devicelisti[]="862403128002744" ;$devicelisti[]="862403128002788" ;$devicelisti[]="862403128002793" ;$devicelisti[]="862403128002735" ;$devicelisti[]="862401128000486" ;

	 $devicelisti[]="862403128002923" ;
#	$svg =array();
	#	$svg[] = "90001296"; $svg[] = "90001297";  $svg[] = "90000779";	
	
	$svgstr = "90000779,862306128002344,862306128002460,358657100409932,90000717,862304128000887,90000641,862306128002468,862306128002427"; 

	$svg = explode(",",$svgstr);

	$bofa = array();
	#$bofa[]= "862306128001695";
 	#$bofa[]= "862306128001679";	
	// $dontstore=array();
	// $dontstore[]="21675493" ; $dontstore[]="21679966" ; $dontstore[]="21938180" ;
	// $dontstore[]="22699906" ; $dontstore[]="90000919" ; $dontstore[]="90001118" ;
	// $dontstore[]="90001114" ; $dontstore[]="90001110" ; $dontstore[]="90001106" ;
	// $dontstore[]="90001098" ; $dontstore[]="90001097" ; $dontstore[]="90001096" ;
	// $dontstore[]="90001094" ; $dontstore[]="90001090" ; $dontstore[]="90001089" ;
	
	
	// $dontstore[]="90001072" ; $dontstore[]="90001024" ; $dontstore[]="90001119" ;
	// $dontstore[]="90001253" ; $dontstore[]="90001482" ; $dontstore[]="90001483" ;
	// $dontstore[]="90001468" ; $dontstore[]="90001494" ; $dontstore[]="90001493" ;
	// $dontstore[]="21575974" ; $dontstore[]="22437687" ; $dontstore[]="22030086" ;
	// $dontstore[]="90001107" ; $dontstore[]="21593480" ; $dontstore[]="22470928" ;
	
	// $dontstore[]="22701231" ; $dontstore[]="22691044" ; $dontstore[]="22578639" ;
	// $dontstore[]="22072476" ; $dontstore[]="21937778" ; $dontstore[]="22596896" ;
	// $dontstore[]="22680831" ; $dontstore[]="22103313" ; $dontstore[]="17408928" ;
	// $dontstore[]="22420246" ; $dontstore[]="22547840" ; $dontstore[]="22535506" ;
	// $dontstore[]="22699930" ; $dontstore[]="22504239" ; $dontstore[]="21899820" ;
	
	// $dontstore[]="22518817" ; $dontstore[]="21675121" ; $dontstore[]="22032322" ;
	// $dontstore[]="22443362" ; $dontstore[]="22615928" ; $dontstore[]="22050050" ;
	// $dontstore[]="22640454" ; $dontstore[]="21597069" ; $dontstore[]="22097713" ;
	// $dontstore[]="22475265" ; $dontstore[]="22031340" ; $dontstore[]="22454435" ;
	// $dontstore[]="21567666" ; $dontstore[]="21982949" ; $dontstore[]="22690988" ;
	
	// $dontstore[]="22513933" ; $dontstore[]="21598539" ; $dontstore[]="22440954" ;
	// $dontstore[]="21582293" ; $dontstore[]="21591120" ; $dontstore[]="22555090" ;
	// $dontstore[]="21571445" ; $dontstore[]="22540464" ; $dontstore[]="22037537" ;
	// $dontstore[]="21924024" ; $dontstore[]="21573383" ; $dontstore[]="22612743" ;
	// $dontstore[]="22721668" ; $dontstore[]="21987559" ; $dontstore[]="22525374" ;
	
	// $dontstore[]="22567657" ; $dontstore[]="22064275" ; $dontstore[]="22430708" ;
	// $dontstore[]="22076485" ; $dontstore[]="22628152" ; $dontstore[]="22636148" ;
	// $dontstore[]="90001101" ; $dontstore[]="22013207" ; $dontstore[]="22503439" ;
	// $dontstore[]="22690228" ; $dontstore[]="22605267" ; $dontstore[]="21592649" ;
	// $dontstore[]="21565967" ; $dontstore[]="22580312" ; $dontstore[]="22591764" ;
	
	// $dontstore[]="17322343" ; $dontstore[]="21596632" ; $dontstore[]="90001117" ;
	// $dontstore[]="90001112" ; $dontstore[]="90001109" ; $dontstore[]="90001103" ;
	// $dontstore[]="90001093" ; $dontstore[]="90001092" ; $dontstore[]="90001084" ;
	// $dontstore[]="90001045" ; $dontstore[]="90001152" ; $dontstore[]="90001125" ;
	// $dontstore[]="90001234" ; $dontstore[]="90001250" ; $dontstore[]="90001199" ;
	
	
	// $dontstore[]="90001219" ; $dontstore[]="90001216" ; $dontstore[]="90001212" ;
	// $dontstore[]="90001105" ; $dontstore[]="90001224" ; $dontstore[]="90001222" ;
	// $dontstore[]="90001492" ; $dontstore[]="21906708" ; $dontstore[]="22027405" ;
	// $dontstore[]="22508891" ; $dontstore[]="21583523" ; $dontstore[]="22729414" ;
	// $dontstore[]="22046413" ; $dontstore[]="22661948" ; $dontstore[]="22533576" ;
	
	// $dontstore[]="22518692" ; $dontstore[]="21582319" ; $dontstore[]="22481313" ;
	// $dontstore[]="21581899" ; $dontstore[]="90001730" ; $dontstore[]="90001801" ; 
	// $dontstore[]="90001755" ; $dontstore[]="22116042" ; $dontstore[]="90001782" ; 
	// $dontstore[]="22496261" ; $dontstore[]="90001827" ; $dontstore[]="90001787" ;
	// $dontstore[]="90001821" ; $dontstore[]="90001750" ; $dontstore[]="90001091" ;
	
	// if (in_array(@$_POST["imei"], $dontstore))
	// {
	// 	echo "DATAOK";
	// 	die; 
	// }
	
	// if(@$_POST["rfid"] != ""){
	// 	$myfile = fopen("vvv_test rfid.txt", "a");
	// 	fwrite($myfile,json_encode($_POST));
	// 	fwrite($myfile, "\n");
	// 	fclose($myfile);
	// }

	// if(@$_POST["imei"] == "7008005"){
	// 	$myfile = fopen("vvvt_7008005.txt", "a");
	// 	fwrite($myfile,json_encode($_POST));
	// 	fwrite($myfile, "\n");
	// 	fclose($myfile);
	// }
	if(@$_POST['imei']!='862205053062412' && @$_POST['imei']!='862205053063865' && @$_POST['imei']!='862205053063832' && @$_POST['imei']!='18053076616' && @$_POST['imei']!='357457040000011' && @$_POST['imei']!='862205053063675' && @$_POST['imei']!='862205053074896' && @$_POST['imei']!='862205053066801' && @$_POST['imei']!='862205053075497' && @$_POST['imei']!='862205053076016' && @$_POST['imei']!='350544500206530' && @$_POST['imei']!='867035048224578' && @$_POST['imei']!='862205056097233'){
	// if(@$_POST['imei']!='357457040000011' && @$_POST['imei']=='357457040000029' && @$_POST['imei']=='357457040000037' && @$_POST['imei']=='357457040000045' && @$_POST['imei']=='357457040000052'){
	if(@$_POST['lat']!="" && @$_POST['lng']!="" && (@$_POST["op"] != "rfid_swipe" || @$_POST['rfid']!='') && @$_POST["protocol"] != "p_tracker" && @$_POST["protocol"] != "iltracker")
	{
		global $gsValues;
		$in_zone = isPointInPolygonDELETE($gsValues['zone_india'], $_POST['lat'], $_POST['lng']);
		if(!$in_zone)
		{
			$in_zoneuga = isPointInPolygonDELETE($gsValues['zone_uganda'], $_POST['lat'], $_POST['lng']);
			if(!$in_zone && !$in_zoneuga)
			{
			  	   echo "DATAOK1";
				   die;
			}
		}
	}
	// }
	}

	if (@$_POST['rfid']!=''){			

		update_dstudent_swipdetails($_POST);
		// unauthorized_peopleswipe($_POST);
	}
	
	function isPointInPolygonDELETE($vertices, $lat, $lng)
	{
		$polyX = array();
		$polyY = array();
		
		$ver_arr = explode(',', $vertices);
		
		// check for all X and Y
                if(!is_int(count($ver_arr)/2))
                {
                        array_pop($ver_arr);
                }
		
		$polySides = 0;
		$i = 0;
		
		while ($i < count($ver_arr))
		{
			$polyX[] = $ver_arr[$i+1];
			$polyY[] = $ver_arr[$i];
			
			$i+=2;
			$polySides++;
		}
		
		$j = $polySides-1 ;
		$oddNodes = 0;
		
		for ($i=0; $i<$polySides; $i++)
		{
			if ($polyY[$i]<$lat && $polyY[$j]>=$lat || $polyY[$j]<$lat && $polyY[$i]>=$lat)
			{
				if ($polyX[$i]+($lat-$polyY[$i])/($polyY[$j]-$polyY[$i])*($polyX[$j]-$polyX[$i])<$lng)
				{
					$oddNodes=!$oddNodes;
				}
			}
			$j=$i;
		}
		
		return $oddNodes;
	}
	

$loc=array();
$loc['protocol'] = "";
$loc['net_protocol'] = "";
$loc['ip'] = "";
$loc['port'] = "";
$devicevlist=array();

global $ms;

	if (@$_POST["op"] == "rfid_swipe")
	{
		new_rfidswipe();
	}
	else
	if (@$_POST["cmd"] == "img_store_loc")
	{
	
		$loc = array();

		$imei = $_POST['imei'];

		#check if object exists in system
		//if (!checkObjectExistsSystem($imei))
		//{
			//return false;
		//}
		
		$loc_prev = get_gs_objects_data($imei);

		$loc['dt_server'] = gmdate("Y-m-d H:i:s");
		$loc['dt_tracker'] = $loc_prev["dt_tracker"];
		$loc['lat'] = $loc_prev["lat"];
		$loc['lng'] = $loc_prev["lng"];
		$loc['altitude'] = $loc_prev["altitude"];
		$loc['angle'] = $loc_prev["angle"];
		$loc['speed'] = $loc_prev["speed"];
		$loc['params'] = $loc_prev["params"];

		$img_file = $imei.'_'.$loc['dt_tracker'].'_'.$loc['dt_server'].'.jpg';
		$img_file = str_replace('-', '', $img_file);
		$img_file = str_replace(':', '', $img_file);
		$img_file = str_replace(' ', '_', $img_file);

		// save to database
		$q = "INSERT INTO gs_object_img (img_file,
                                                imei,
                                                dt_server,
                                                dt_tracker,
                                                lat,
                                                lng,
                                                altitude,
                                                angle,
                                                speed,
                                                params
                                                ) VALUES (
                                                '".$img_file."',
                                                '".$imei."',
                                                '".$loc['dt_server']."',
                                                '".$loc['dt_tracker']."',
                                                '".$loc['lat']."',
                                                '".$loc['lng']."',
                                                '".$loc['altitude']."',
                                                '".$loc['angle']."',
                                                '".$loc['speed']."',
                                                '".json_encode($loc['params'])."')";

		$r = mysqli_query($ms, $q);
	
		// save file
		$img_path = $gsValues['PATH_ROOT'].'/data/img/';
		$img_path = $img_path.basename($img_file);

		$postdata =  $_POST["img"];
		$postdata = hex2bin($postdata);

		if(substr($postdata,0,3) == "\xFF\xD8\xFF")
		{
			$fp = fopen($img_path,"w");
			fwrite($fp,$postdata);
			fclose($fp);
		}
	}
	else
	if(@$_POST['method'] == 'ipportFM')
	{
		$loc['imei'] = $_POST['imei'];
		$loc['version'] = $_POST['version'];
		$loc['dt_server'] = date("Y-m-d H:i:s");


		$q = 'SELECT * FROM ipport WHERE imei="'.$loc['imei'].'"';

		$r = mysqli_query($ms,$q) or die(mysqli_error());
		$row = mysqli_fetch_array($r);

		if (!$row)
		{
			$q = 'INSERT INTO ipport(imei,version,dt_server)  VALUES ("'.$loc['imei'].'","'.$loc['version'].'","'.$loc['dt_server'].'" );';
			$r = mysqli_query($ms,$q) or die(mysqli_error());
		}
		else
		{
			$q = 'update ipport set version="'.$loc['version'].'",dt_server="'.$loc['dt_server'].'"  WHERE imei="'.$loc['imei'].'"';
			$r = mysqli_query($ms,$q) or die(mysqli_error());
		}
	}
	else if(@$_POST['method'] == 'ipportFMBOOT')
	{
		$loc['imei'] = $_POST['imei'];
		$loc['boot'] = $_POST['boot'];
		$loc['dt_server'] = date("Y-m-d H:i:s");

		$q = 'update ipport set boot="'.$loc['boot'].'"  WHERE imei="'.$loc['imei'].'"';
		$r = mysqli_query($ms,$q) or die(mysqli_error());

	}
	else if(@$_POST['method'] == 'apndetails')
	{
		$loc['imei'] = @$_POST['imei'];
		$loc['ip'] = @$_POST['ip'];
		$loc['port'] = @$_POST['port'];
		$loc['apndetail'] = @$_POST['apndetail'];
		$loc['dt_server'] = date("Y-m-d H:i:s");

		$q = 'insert into ipport(imei,apn,ip,port,dt_server)   values("'.$loc['imei'].'","'.$loc['apndetail'].'","'.$loc['ip'].'","'.$loc['port'].'","'.$loc['dt_server'].'")';
		$r = mysqli_query($ms,$q) or die(mysqli_error());
	}
	else if(@$_POST['method'] == 'ipportFMBOOTSELECT')
	{
		//fuel!='SEND' and
		$arr=array();
		$q = "SELECT * FROM ipport WHERE   boot!='2' ";
		$r = mysqli_query($ms,$q);
		$i=0;
		while($row = mysqli_fetch_array($r,MYSQLI_ASSOC))
		{
			$arr[]=array("imei"=>$row["imei"]);
		}

		header('Content-type: application/json');
		echo json_encode($arr);
		die;
	}
	else if(@$_POST['method'] == 'ipportapndetail')
	{
		//fuel!='SEND' and
		$arr=array();
		$q = "select distinct imei,ip,port,apn from ipport";
		$r = mysqli_query($ms,$q);
		$i=0;
		while($row = mysqli_fetch_array($r,MYSQLI_ASSOC))
		{
			$arr[]=$row;
		}

		header('Content-type: application/json');
		echo json_encode($arr);
		die;
	}
	else if(@$_POST['method'] == 'ipportFMFUEL')
	{
		$loc['imei'] = $_POST['imei'];
		$loc['fuel'] = $_POST['fuel'];
		$loc['dt_server'] = date("Y-m-d H:i:s");

		$q = 'update ipport set fuel="'.$loc['fuel'].'"  WHERE imei="'.$loc['imei'].'"';
		$r = mysqli_query($ms,$q) or die(mysqli_error());

	}
	else if(@$_POST['method'] == 'ipportFMFUELSELECT')
	{
		//fuel!='SEND' and
		$arr=array();
		$q = "SELECT * FROM ipport WHERE   fuel!='OK' ";
		$r = mysqli_query($ms,$q);
		$i=0;
		while($row = mysqli_fetch_array($r,MYSQLI_ASSOC))
		{
			$arr[]=array("imei"=>$row["imei"]);
		}

		header('Content-type: application/json');
		echo json_encode($arr);
		die;
	}
	else if(@$_POST['method'] == 'ipportdata')
	{
		$loc['imei'] = $_POST['imei'];
		$loc['ip'] = $_POST['ip'];
		$loc['port'] = $_POST['port'];
		$loc['dt_server'] = gmdate("Y-m-d H:i:s");


		$q = 'SELECT * FROM ipport WHERE imei="'.$loc['imei'].'"';

		$r = mysqli_query($ms,$q) or die(mysqli_error());
		$row = mysqli_fetch_array($r);

		if (!$row)
		{
			$q = 'INSERT INTO ipport(imei,ip,PORT,dt_server)  VALUES ("'.$loc['imei'].'","'.$loc['ip'].'","'.$loc['port'].'","'.$loc['dt_server'].'" );';
			$r = mysqli_query($ms,$q) or die(mysqli_error());

		}
		else
		{
			$q = 'update ipport set ip="'.$loc['ip'].'",PORT="'.$loc['port'].'",dt_server="'.$loc['dt_server'].'"  WHERE imei="'.$loc['imei'].'"';
			$r = mysqli_query($ms,$q) or die(mysqli_error());
		}
	}
	else
	if(@$_POST['method'] == 'cmdreply')
	{

		$loc['imei'] = $_POST['imei'];
		$loc['status'] = $_POST['status'];
		$loc['command'] = $_POST['command'];
		$loc['responsedate'] = date("Y-m-d H:i:s");


		$q = 'SELECT * FROM command WHERE imei="'.$loc['imei'].'"';

		$r = mysqli_query($ms,$q) or die(mysqli_error());
		$row = mysqli_fetch_array($r);

		if(isset($_POST['reply']))
		{
			$arrv = explode(",",$loc['command']);
			$loc['command']="";
			foreach ($arrv as $field => $value)
			{
				if($loc['command']=="")
				$loc['command']="'".$value."'";
				else
				$loc['command']=$loc['command'].",'".$value."'";
			}
				
			$loc['reply'] = $_POST['reply'];
			$q = "update command set response='".$loc['reply']."',status='".$loc['status']."',responsedate='".$loc['responsedate']."'  WHERE imei='".$loc['imei']."' and used in (".$loc['command'].") and response  not in ('Send','Waiting','Offline')";
			$r = mysqli_query($ms,$q) or die(mysqli_error());
		}
		else
		{
			$q = "update command set status='".$loc['status']."',responsedate='".$loc['responsedate']."'  WHERE imei='".$loc['imei']."' and command='".$loc['command']."' and response  not in ('Send','Waiting','Offline')";
			$r = mysqli_query($ms,$q) or die(mysqli_error());
		}
	}
	else if(@$_POST["method"]=="Cmd")
	{
	// $myfile = fopen("0_vetrivel_commander.txt", "a");
	// fwrite($myfile,json_encode($_POST));
	// fwrite($myfile, "\n");
	// fclose($myfile);

	$q = "update command set response='".$_POST['Response']."',status='Finished'  WHERE imei='".$_POST['imei']."' and response=''";
	$r = mysqli_query($ms,$q) or die(mysqli_error());


	}

	function new_rfidswipe()
	{
		$swipe['imei'] = $_POST["imei"];
		$swipe['dt_server'] = gmdate("Y-m-d H:i:s");
		$swipe['dt_swipe'] = $_POST["dt_tracker"];
		$swipe['lat'] = $_POST["lat"];
		$swipe['lng'] = $_POST["lng"];
		$swipe['rfid'] = $_POST["rfid"];
		$swipe['rfid_access'] = @$_POST["rfid_access"];
		$swipe['op']="rfid_swipe";
		$swipe['event']="rfid_swipe";

	//if(strlen($swipe['rfid'])>8)
	//{
	//$swipe['rfid']=substr($swipe['rfid'], 0,8);
		//}

		//if($swipe['imei']=="90001515" || $swipe['imei']=="90001531" )
		{
				global $ms;
				$q = "SELECT gs_objects.*, gs_user_objects.*
							FROM gs_objects
							INNER JOIN gs_user_objects ON gs_objects.imei = gs_user_objects.imei
							WHERE gs_user_objects.imei='".$swipe['imei']."'";

				$r = mysqli_query($ms, $q);
				while($od = mysqli_fetch_array($r))
				{
					// get user data
					$q2 = "SELECT * FROM `gs_users` WHERE `id`='".$od['user_id']."'";
					$r2 = mysqli_query($ms, $q2);
					$ud = mysqli_fetch_array($r2);
						
					// events loop
					$q2 = "SELECT * FROM `gs_user_events` WHERE type='unauthorize' and `user_id`='".$od['user_id']."' AND UPPER(`imei`) LIKE '%".$swipe['imei']."%' order by notify_sms desc ";
					$r2 = mysqli_query($ms, $q2);

					while($ed = mysqli_fetch_array($r2))
					{
						if ($ed['active'] == 'true')
						{
							$loc['type']=$ed['type'];
							
							if ($ed['type'] == 'unauthorize')
							{
								$loc=$swipe;
								$ed['event_desc']="Unauthorize - ".$swipe['rfid'];
								
								$qu="SELECT count(c.firstname) count FROM csnmast c JOIN droute_events de ON de.event_id=c.ROUTENO WHERE c.cardno='".$swipe['rfid']."' and de.imei='".$swipe['imei']."'";
								$ruv = mysqli_query($ms, $qu);
								$row_count = mysqli_fetch_assoc($ruv);
															
								if($row_count["count"]==0)
								{
									// insert event into list	type,	'".$ed['type']."',
									$q = "INSERT INTO `gs_user_events_data` (	user_id,
															
																event_desc,
																notify_system,
																notify_arrow,
																notify_arrow_color,
																notify_ohc,
																notify_ohc_color,
																imei,
																dt_server,
																dt_tracker,
																lat,
																lng,
																type
																) VALUES (
																'".$ed['user_id']."',
															
																'".$ed['event_desc']."',
																'".$ed['notify_system']."',
																'".$ed['notify_arrow']."',
																'".$ed['notify_arrow_color']."',
																'".$ed['notify_ohc']."',
																'".$ed['notify_ohc_color']."',
																'".$od['imei']."',
																'".$loc['dt_server']."',
																'".$loc['dt_swipe']."',
																'".$loc['lat']."',
																'".$loc['lng']."',
																'".$ed["type"]."')";
									$r = mysqli_query($ms, $q);
								}
							}
						}
					}	
				}
		}
		
		insert_db_rfid_swipe($swipe);		
		
	}
	

function  unassigntrip($loc,$loc_prev)
{
	/*

	$acc=intval(paramsParamValue($loc['params'],'acc'));

	if (strtotime($loc['dt_tracker']) > strtotime($loc_prev['dt_tracker']) && $loc_prev!=false)
	{
	$acc_prev=intval(paramsParamValue($loc_prev['params'],'acc'));
		
	if($acc==1 && ($acc_prev!=$acc))
	{
	$q="insert into unassigntrip(imei,datetime,starttime,startlat,startlng,startspeed,startparam) values ('".$loc['imei']."','".$loc['dt_server']."','".$loc['dt_tracker']."','".$loc['lat']."','".$loc['lng']."',
	'".$loc['speed']."','".$loc['params']."')";
	$r = mysqli_query($ms,$q);
	}
	else if($acc==0 && ($acc_prev!=$acc) )
	{
	$q="update unassigntrip set endtime='".$loc['dt_tracker']."',
	endlat='".$loc['lat']."',endlng='".$loc['lng']."',endspeed='".$loc['speed']."',
	endparam='".$loc['params']."' where imei='".$loc['imei']."' and endtime is null ";
	$r = mysqli_query($ms,$q);
	}
	}
	else
	{
	$rtnvalu=get_object_last_data($loc['imei'],$loc['dt_tracker']);
	if($rtnvalu!=false)
	{
	$acc_prev=intval(paramsParamValue($rtnvalu['params'],'acc'));

	if($acc==1 && ($acc_prev!=$acc))
	{
	$q="insert into unassigntrip(imei,datetime,starttime,startlat,startlng,startspeed,startparam) values ('".$loc['imei']."','".$loc['dt_server']."','".$loc['dt_tracker']."','".$loc['lat']."','".$loc['lng']."',
	'".$loc['speed']."','".$loc['params']."')";
	$r = mysqli_query($ms,$q);
	}
	else if($acc==0 && ($acc_prev!=$acc) )
	{
	$q="update unassigntrip set endtime='".$loc['dt_tracker']."',
	endlat='".$loc['lat']."',endlng='".$loc['lng']."',endspeed='".$loc['speed']."',
	endparam='".$loc['params']."' where imei='".$loc['imei']."' and endtime is null ";
	$r = mysqli_query($ms,$q);
	}
	}
	else
	{
	if($acc==1)
	{
	$q="insert into unassigntrip(imei,datetime,starttime,startlat,startlng,startspeed,startparam) values ('".$loc['imei']."','".$loc['dt_server']."','".$loc['dt_tracker']."','".$loc['lat']."','".$loc['lng']."',
	'".$loc['speed']."','".$loc['params']."')";
	$r = mysqli_query($ms,$q);
	}
	}
		
	}
	*/

}



function get_object_last_data($imei,$dt_tracker)
{
	global $ms;
	$q = "SELECT * FROM gs_object_data_".$imei." WHERE dt_tracker<'".$dt_tracker."' and params!='' and params!='1' limit 1";
	$r = mysqli_query($ms,$q) or die(mysqli_error());
	$row = mysqli_fetch_array($r);

	return $row;
}
function postDeviceData($loc,  $deviceName) {

	$token  = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VybmFtZSI6IlBBSVpPR1BTU09MVVRJT05TIiwidmVuZG9ybmFtZSI6IiIsImlhdCI6MTczOTM0MDMwM30.C9pqymSM6WDgc1U_B_f_qqESf_k9iSXm8yyL0UvxvNQ";
    // Map the data from $loc to the API expected fields.
    $deviceData = array(
        "gpsId"         => isset($loc['imei']) ? $loc['imei'] : "",   // Unique device ID
        "timestamp"     => isset($loc['dt_tracker']) ? strtotime($loc['dt_tracker']) : time(),  // Unix timestamp in seconds
        "lat"           => isset($loc['lat']) ? $loc['lat'] : 0,        // Latitude
        "lng"           => isset($loc['lng']) ? $loc['lng'] : 0,        // Longitude
        "speed"         => isset($loc['speed']) ? $loc['speed'] : 0,    // Optional: Bus speed in Km/hr
        "ignitionStatus"=> (isset($loc['params']['acc']) && $loc['params']['acc'] == "1") ? 1 : 0, // 1 if ignition is on; otherwise 0
        "address"       => "", 
        "acStatus"      => false,
        "orientation"   => isset($loc['angle']) ? $loc['angle'] : 0,     // Optional: Bus direction
        "vehicleNo"     => $deviceName                              // Vehicle registration number
    );

    // Wrap the single device data in an array as the API expects an array of packets.
    $postData = array($deviceData);
    $jsonData = json_encode($postData);

    // API endpoint
    $url = "http://fleetdata.yourbus.in/api/gpsdata";

    // Initialize cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Authorization: Bearer " . $token,
        "Content-Type: application/json",
        "Content-Length: " . strlen($jsonData)
    ));

    // Execute the request
    $result = curl_exec($ch);
    $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    // If there was a cURL error, write it to the log file.
    // if ($curlError) {
    //     $errorLog = date("Y-m-d H:i:s") . " - CURL Error: " . $curlError . "\n";
    //     file_put_contents("0_redbus_kcms_error.txt", $errorLog, FILE_APPEND);
    // }else{
    // 	$errorLog = date("Y-m-d H:i:s") . " - CURL result: " . json_encode($result) . "\n";
    // 	file_put_contents("0_redbus_kcms_succ.txt", $errorLog, FILE_APPEND);
    // }
}


function PostRedBus($loc,$prevddata)
{


	try {
	global $ms,$devicevlist,$devicelisti,$svg,$bofa;
	//$devicevlist=array();
	$string = str_replace(' ', '', $prevddata['name']);
	$q = "SELECT * FROM `gs_users` WHERE `privileges` like '%".$loc["imei"]."%' and id in (2208)";
	$r = mysqli_query($ms, $q);
	if ($r)
	{
		$num = mysqli_num_rows($r);
		if ($num >= 1)
		{
			postDeviceData($loc,$string);
		}
	}
	if (in_array($loc["imei"], $devicevlist))
	{

		//$dt= strtotime(date("Y-m-d H:i:s", strtotime($loc['dt_tracker'].'+ 5 hour + 30 minutes')));
		$dt= strtotime($loc['dt_tracker']);
				
		$llt=$loc['lat'].','.$loc['lng'].','.$dt.',0,0,0,0';
		$url = 'http://track1.yourbus.in/processGPSV2.php?acc_key=P1e2YZs7hFbW4IpS&gps_id='.$string.'&llt1='.$llt;

		$options = array(
								'http' => array(
								'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
								'method'  => 'GET'//,
		)
		);
		
		$context  = stream_context_create($options);
		//Insert_Issue($loc['imei'],'Redbus',$url.json_encode($loc),1);
		$result = file_get_contents($url, false, $context);
	
		
	// if($loc["imei"]=="90000713")
	// {
	//  $myfile = fopen("0loc_rathi.txt", "a");
	//  fwrite($myfile,$url);
	//  fwrite($myfile, "\n");
	//   fwrite($myfile,json_encode($result));
	//  fwrite($myfile, "\n");
	//  fclose($myfile);
	// }
	
		
	}
	if (in_array($loc["imei"], $devicelisti))
	{
		$isSos = $loc["event"] == "sos";
		
		$url="http://tracking.moveinsync.com:8080/gps-tracking/devices/packets";

		$postdata =array(
			"deviceImei"=>  $loc['imei'],
			"deviceId"=>  $loc['imei'],
			"timestamp"=>  (strtotime($loc['dt_tracker'])*1000),
			"latitude"=>  $loc['lat'],
			"longitude"=>  $loc['lng'],
			"speed"=>  $loc['speed'],
			"bearing"=>  @$loc['angle'],
			// "locationAccuracy"=>  "909",
			"deviceType"=>  "PAIZOGPS",
			"alertMap" => ["PANIC" => $isSos]
		);
		$dv=json_encode(array($postdata));							
		$opts = array('http' =>
				array(
					'method'  => 'POST',
					'header'  => 'Content-type: application/json',
					'content' => $dv
				)
		);

		$context  = stream_context_create($opts);

		$result = file_get_contents($url, false, $context);

		$myfile = fopen("boapanic.txt", "a");
		fwrite($myfile,$dv);
		fwrite($myfile, "\n");
		fwrite($myfile,json_encode($result));
		fwrite($myfile, "\n");
		fclose($myfile);
		
	}else if (in_array($loc["imei"], $svg))
	{
		$svg_name = str_replace(' ', '', $prevddata['name']);	
		$curl = curl_init();
		$postdata =array(
			"deviceIMEI"=>	$svg_name,
			"latitude"=>	$loc['lat'],
			"longitude"=>	$loc['lng'],
			"gpsTS"=>	(strtotime($loc['dt_tracker'])*1000),
			"speed"=>	$loc['speed'],
			"orientation"=>	$loc['angle'],
			"odometer"=>	intval(@$loc['params']["odo"]),
			"availability"=>	"A",
			"ignitionOn"=>	$loc['params']["acc"] == "1" ? true : false,
			"sos"=>	false,
			"acOn"=>	false
		);
		$dv=json_encode(array($postdata));		
		echo $dv;

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://telematics.loconav.com:5080/',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $dv,
			CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Authorization: tVxS%mm%dQ*q8ur83w%B3uPAY5qCCfHI'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);

		//$myfile = fopen("0loc_svg.txt", "a");
		//fwrite($myfile,$dv);
		//fwrite($myfile, "\n");
		//fwrite($myfile,json_encode($response));
		//fwrite($myfile, "\n");
		//fclose($myfile);
	}

	else if (in_array($loc["imei"], $bofa))
	{	
		$isSos = $loc["event"] == "sos";
		$svg_name = str_replace(' ', '', $prevddata['name']);
		$curl = curl_init();
		$postdata = array(
	"deviceImei" => $loc['imei'],			
    "deviceId" => $svg_name,
    "latitude" => $loc['lat'],
    "longitude" => $loc['lng'],
    "timestamp" => strtotime($loc['dt_tracker']) * 1000,
    "speed" => $loc['speed'],
	"bearing"=>  @$loc['angle'],
    "deviceType" => "PAIZOGPS",
	"alertMap" => ["PANIC" => $isSos]
);
		$dv=json_encode(array($postdata));	
	
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://tracking.moveinsync.com:8080/gps-tracking/devices/PAIZOGPS/packets',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $dv,
			CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);

		$myfile = fopen("boapanic.txt", "a");
		fwrite($myfile,$dv);
		fwrite($myfile, "\n");
		fwrite($myfile,json_encode($response));
		fwrite($myfile, "\n");
		fclose($myfile);

		
	}else{

		// postToBOA($prevddata,$loc);
		// #return "";
		$q = "SELECT * FROM `gs_user_objects` WHERE `imei`='".$loc["imei"]."' and user_id  in (1035)";
		$r = mysqli_query($ms, $q);
		
		if ($r)
		{
			$num = mysqli_num_rows($r);
			if ($num >= 1)
			{
				$isSos = $loc["event"] == "sos";
				$svg_name = str_replace(' ', '', $prevddata['name']);
				$curl = curl_init();
				$postdata = array(
				     "deviceImei" => $loc['imei'],
				     "deviceId" => $svg_name,
				     "latitude" => $loc['lat'],
				    "longitude" => $loc['lng'],
				    "timestamp" => strtotime($loc['dt_tracker']) * 1000,
				    "speed" => $loc['speed'],
					"bearing"=>  @$loc['angle'],
				    "deviceType" => "PAIZOGPS",
					"alertMap" => ["PANIC" => $isSos]
				);

				$dv=json_encode(array($postdata));	
			
				curl_setopt_array($curl, array(
					CURLOPT_URL => 'http://tracking.moveinsync.com:8080/gps-tracking/devices/PAIZOGPS/packets',
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'POST',
					CURLOPT_POSTFIELDS => $dv,
					CURLOPT_HTTPHEADER => array(
					'Content-Type: application/json',
					),
				));

				$response = curl_exec($curl);

				curl_close($curl);

				if(isset($loc["event"]) && $loc["event"] != ""){
				$myfile = fopen("boapanic.txt", "a");
				fwrite($myfile, "\n");
				// fwrite($myfile,json_encode($loc));
				// fwrite($myfile, "\n");
				fwrite($myfile,json_encode($postdata));
				fwrite($myfile, "\n");
				fwrite($myfile,json_encode($response));
				fwrite($myfile, "\n");
				fclose($myfile);
				}
			}
		}

		

		$q = "SELECT * FROM `gs_user_objects` WHERE `imei`='".$loc["imei"]."' and user_id=1232";
		$r = mysqli_query($ms, $q);
		
		if ($r)
		{
			$num = mysqli_num_rows($r);
			if ($num >= 1)
			{
				$svg_name = str_replace(' ', '', $prevddata['name']);
				$curl = curl_init();
				$postdata =[
					"authkey" => "19c4ae24-ac06-5ef5-9e8a-d9eda58da7ff",
					"vendor" => "SBLT",
					"data" => [
						[
							"zid" => 4529,
							"locations" => [
								[
									"vehiclename" => $svg_name,
									"location" => [
										[
											"lat" => $loc['lat'],
											"lng" => $loc['lng'],
											"speed" => $loc['speed'],
											"accuracy" => 1,
											"ts" => strtotime($loc['dt_tracker']),
											"panic" => 0,
											"rfid" => "",
											"ign" => @$loc['params']["acc"]
										]
									]
								]
							]
						]
					]
				];
				$dv=json_encode($postdata);	
			
				curl_setopt_array($curl, array(
					CURLOPT_URL => 'http://vru.verayu.com/lib/process_location_update_tp.php',
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 60,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'POST',
					CURLOPT_POSTFIELDS => $dv,
					CURLOPT_HTTPHEADER => array(
					'Content-Type: application/json',
					),
				));

				$response = curl_exec($curl);

				curl_close($curl);

				
			}
		}

				//redbus posting by user id
		$match_user_id = array(2097,2098,2100);
		$q = "SELECT user_id FROM `gs_user_objects` WHERE `imei`='".$loc["imei"]."'";
		$r = mysqli_query($ms, $q);
		if ($r)
		{
			$num = mysqli_num_rows($r);
			if ($num >= 1)
			{
				$user_ids = array();
			    while ($row = mysqli_fetch_assoc($r)) {
			        $user_ids[] = $row['user_id'];
			    }

    			$uFound = (count(array_intersect($user_ids, $match_user_id))) ? true : false;
    			if($uFound)
    			{
    				$string = str_replace(' ', '', $prevddata['name']);
					$dt= strtotime($loc['dt_tracker']);
					$llt=$loc['lat'].','.$loc['lng'].','.$dt.',0,0,0,0';
					$url = 'http://track1.yourbus.in/processGPSV2.php?acc_key=P1e2YZs7hFbW4IpS&gps_id='.$string.'&llt1='.$llt;

					$options = array(
											'http' => array(
											'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
											'method'  => 'GET'//,
					)
					);
					
					$context  = stream_context_create($options);
					$result = file_get_contents($url, false, $context);


    			}
			}
		}
		//end redbus posting by user id



	}

	}
	catch (Exception  $e)
	{
		
	}
}
/*Code written by Nandha*/
function update_dstudent_swipdetails($swipe){
	global $ms;
	$q="update dstudent SET `last_swipe_vehicle`='".$swipe["imei"]."',`last_swipe_time`='".$swipe['dt']."' WHERE rfid='".$swipe["rfid"]."'";
	mysqli_query($ms,$q);
	// check_events($loc, true, true, false);
}

/*Code Updated and written by N.Vetrivel*/
function insert_db_rfid_swipe($swipe)
{
	global $ms,$gsValues;
		$qgetuser="select guo.imei,gu.userapikey,guo.user_id from gs_user_objects  guo join gs_users gu on gu.id=guo.user_id
			   where  guo.imei='".$swipe["imei"]."' ";
		if($ro= mysqli_query($ms,$qgetuser))
		{
			while($row=mysqli_fetch_assoc($ro))
			{
				if(isset($gsValues['POST_RFID'][$row["user_id"]]))
				{
					insert_db_rfid_swipe_data_gnroute($swipe,$ms,$row);
					die;
				}
				else
				{
					// one minute interval between card swipes
					$q = "SELECT * FROM gs_rfid_swipe_data WHERE `imei`='".$swipe['imei']."' AND
					 `rfid`='".$swipe['rfid']."' AND 
					 `dt_swipe` between DATE_SUB('".$swipe['dt_swipe']."', INTERVAL 1 MINUTE) and '".$swipe['dt_swipe']."'";
					$r = mysqli_query($ms,$q) or die(mysqli_error());
					$row = mysqli_fetch_array($r);
					if (!$row)
					{
						if(strlen($swipe['rfid'])>=11 && substr($swipe['rfid'],0,2)=="99")
						{$swipe['rfid']=substr($swipe['rfid'],2,10);}
						insert_db_rfid_swipe_data($swipe,$ms);
						die;
					}
				}
			}
		}
		else
		{
			// $myfile = fopen("vvv333.txt", "a");
			// fwrite($myfile,$qgetuser."=> vetri => ".json_encode($swipe) );
			// fwrite($myfile, "\n");
			// fclose($myfile);
			insert_db_rfid_swipe_data($swipe,$ms);
		}
}


function insert_db_rfid_swipe_data($swipe,$ms)
{
	$rfid_snr=getRFIDtoSNR($swipe['rfid']);
	$q = 'INSERT INTO gs_rfid_swipe_data (	dt_server,
								dt_swipe,
								imei,
								lat,
								lng,
								rfid,
								access,
								emp_id,
								snr_number
								) VALUES (
								"'.$swipe['dt_server'].'",
								"'.$swipe['dt_swipe'].'",
								"'.$swipe['imei'].'",
								"'.$swipe['lat'].'",
								"'.$swipe['lng'].'",
								"'.$swipe['rfid'].'",
								"'.$swipe['rfid_access'].'",
								"'.rfidDstudent_id($swipe['rfid']).'",
								"'.$rfid_snr.'"	)';
	$r = mysqli_query($ms,$q) or die(mysqli_error());
}

function rfidDstudent_id($rfid)
{
	global $ms;
	$q="SELECT * FROM dstudent where rfid='".$rfid."'";
	$r=mysqli_query($ms,$q);
	if($row=mysqli_fetch_assoc($r)){
		return $row['uid'];
	}else{
		return 0;
	}
}

function insert_db_rfid_swipe_data_gnroute($swipe,$ms,$rowfrom)
{
	$swipe["gname"]="";$swipe["routeno"]="";
	$qgetuser="select gname,routeno from csnmast   where  cardno='".$swipe["rfid"]."' ";
	if($ro= mysqli_query($ms,$qgetuser))
	{
		if($row=mysqli_fetch_assoc($ro))
		{
			$swipe["gname"]=$row["gname"];
			$swipe["routeno"]=$row["routeno"];
		}
	}
	$rfid_snr=getRFIDtoSNR($swipe['rfid']);
	$q = 'INSERT INTO gs_rfid_swipe_data (dt_server,
								dt_swipe,
								imei,
								lat,
								lng,
								rfid,
								emp_id,
								snr_number,
								gname,
								routeno,
								status
								) VALUES (
								"'.$swipe['dt_server'].'",
								"'.$swipe['dt_swipe'].'",
								"'.$swipe['imei'].'",
								"'.$swipe['lat'].'",
								"'.$swipe['lng'].'",
								"'.$swipe['rfid'].'",
								"'.rfidDstudent_id($swipe['rfid']).'",
								"'.$rfid_snr.'",
								"'.$swipe['gname'].'",
								"'.$swipe['routeno'].'",0)';
	
	$r = mysqli_query($ms,$q) or die(mysqli_error());
	
	//PosttoRFIDRemote($swipe,$rowfrom);
}


function PosttoRFIDRemote($swipe,$row)
{
	global $ms,$gsValues;

		
	$url = $gsValues['POST_RFID'][$row["user_id"]]["url"].'/api/StoreRFID?Key='.$row["userapikey"].'&CardId='.$swipe['rfid'].'&Machine='.$swipe['imei'].'&gname='.$swipe['gname'].'&routeno='.$swipe['routeno'].'&dttime='.str_replace(" ","%20",$swipe['dt_swipe']);
	$options = array(
					'http' => array(
					'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
					'method'  => 'GET'//,
		)
		);
	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);

	if($result==false){
		//$q = "update gs_rfid_swipe_data set status='0' where dt_swipe='".$swipe['dt_swipe']."' and imei='".$swipe['imei']."' and rfid='".$swipe['rfid']."'";	
		//$r =mysqli_query($ms,$q) or die(mysqli_error());
	}
	
// $myfile = fopen("vvv.txt", "a");
// fwrite($myfile,$url." => ".json_encode($result) );
// fwrite($myfile, "\n");
// fclose($myfile);
	
}

function getRFIDtoSNR($rfid){
	if($rfid!=''){
		// $hex=dechex($rfid);
		$hex=str_split($rfid, 2);
		if(gettype($hex)=='array'){
			$hex = array_reverse($hex);
			$hex=strtoupper(join("",$hex));
			// $hex=substr(str_repeat(0, 8).$hex, - 8);
			return $hex;
		}
	}
	return '';
}


function postToBOA($prevddata,$loc){
	//boa subuser
	$iboasubuser = [862406128000961,862406128000899,862406128000965,862406128000900,862406128000890,862406128000237,862406128000953,862406128000960];
	
	if (in_array($loc["imei"], $iboasubuser))
	{
			$isSos = $loc["event"] == "sos";
			$svg_name = str_replace(' ', '', $prevddata['name']);
			$curl = curl_init();
			$postdata = array(
			    "deviceImei" => $loc["imei"],
    			    "deviceId" => $svg_name,
			    "latitude" => $loc['lat'],
			    "longitude" => $loc['lng'],
			    "timestamp" => strtotime($loc['dt_tracker']) * 1000,
			    "speed" => $loc['speed'],
				"bearing"=>  @$loc['angle'],
			    "deviceType" => "PAIZOGPS",
				"alertMap" => ["PANIC" => $isSos]
			);
			$dv=json_encode(array($postdata));	
		
			curl_setopt_array($curl, array(
				CURLOPT_URL => 'http://tracking.moveinsync.com:8080/gps-tracking/devices/PAIZOGPS/packets',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => $dv,
				CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json',
				),
			));

			$response = curl_exec($curl);

			curl_close($curl);

			$myfile = fopen("boapanic.txt", "a");
			fwrite($myfile,$dv);
			fwrite($myfile, "\n");
			fwrite($myfile,json_encode($response));
			fwrite($myfile, "\n");
			fclose($myfile);
			
	}
}



//4k
$devicevlist[]="4230709050" ;$devicevlist[]="4230708887";
//fm100
$devicevlist[]="7001549" ;$devicevlist[]="7001492";$devicevlist[]="7001557";
$devicevlist[]="7001556" ;$devicevlist[]="7001554";$devicevlist[]="7001552";
$devicevlist[]="7001487" ;$devicevlist[]="7001511";$devicevlist[]="7001559";
$devicevlist[]="7008013" ;$devicevlist[]="7008016";$devicevlist[]="7008017";
$devicevlist[]="7008046" ;$devicevlist[]="7008047";


$devicevlist[]="7001119" ;$devicevlist[]="7001322" ;$devicevlist[]="7001325";
$devicevlist[]="7001329" ;$devicevlist[]="7001331" ;$devicevlist[]="7001333";
$devicevlist[]="7001339" ;$devicevlist[]="7001341" ;$devicevlist[]="7001342";
$devicevlist[]="7001345" ;$devicevlist[]="7001347" ;$devicevlist[]="7001350";
$devicevlist[]="7001353" ;$devicevlist[]="7001372" ;$devicevlist[]="7001375";
$devicevlist[]="7001382" ;$devicevlist[]="7001484" ;

$devicevlist[]="7001332" ;$devicevlist[]="7001354" ;$devicevlist[]="7001343";
$devicevlist[]="7001337" ;$devicevlist[]="7001346" ;$devicevlist[]="7001348";
$devicevlist[]="7001327" ;$devicevlist[]="7001355" ;$devicevlist[]="7001338";
$devicevlist[]="7001326" ;$devicevlist[]="7001336" ;$devicevlist[]="7001338";
$devicevlist[]="7001380" ;$devicevlist[]="7001362" ;
$devicevlist[]="7008013";
$devicevlist[]="7001538" ;$devicevlist[]="7001328" ;

$devicevlist[]="4230709433";$devicevlist[]="7001353";$devicevlist[]="7001332";
$devicevlist[]="7001372";$devicevlist[]="7001339";$devicevlist[]="7001382";
$devicevlist[]="50000872";

//fm100 volvo
$devicevlist[]="7001520" ;
//102g
$devicevlist[]="22486288";$devicevlist[]="21593324";
$devicevlist[]="21565439";$devicevlist[]="22592564";
//t20

$devicevlist[]="50000805";$devicevlist[]="50000808";
$devicevlist[]="50000802";$devicevlist[]="50000768";$devicevlist[]="21584331";
$devicevlist[]="22560181";$devicevlist[]="50000727";$devicevlist[]="50000729";
$devicevlist[]="50000823";$devicevlist[]="50000784";


$devicevlist[]="90000277";$devicevlist[]="90000296";$devicevlist[]="90000278";
$devicevlist[]="90000280";$devicevlist[]="90000283";$devicevlist[]="90000208";
$devicevlist[]="90000291";$devicevlist[]="90000288";$devicevlist[]="90000297";
$devicevlist[]="90000298";$devicevlist[]="90000248";$devicevlist[]="90000205";



$devicevlist[]="4230709050" ; $devicevlist[]="4230708887" ; $devicevlist[]="6120317295" ;
$devicevlist[]="6120317402" ; $devicevlist[]="6120317427" ; $devicevlist[]="6120317432";
$devicevlist[]="6120317476" ; $devicevlist[]="6120317536" ; $devicevlist[]="6120317539" ; 
$devicevlist[]="6120317542" ; $devicevlist[]="6120317544" ; $devicevlist[]="6120317650" ; 
$devicevlist[]="6120317662" ; $devicevlist[]="6120317671" ; $devicevlist[]="6120317689" ;
$devicevlist[]="6120317692" ; $devicevlist[]="6120317694" ; $devicevlist[]="6120317719"; 
$devicevlist[]="6120317739" ; $devicevlist[]="6120317845" ; $devicevlist[]="6120317889" ;
$devicevlist[]="6120317891" ; $devicevlist[]="6120317921" ; $devicevlist[]="6120317928" ;
$devicevlist[]="6120317936" ; $devicevlist[]="6120317939" ; $devicevlist[]="6120317948" ;
$devicevlist[]="6120317966" ; $devicevlist[]="6120317969" ; $devicevlist[]="6120317972" ;
$devicevlist[]="6120317985" ; $devicevlist[]="6230517479" ; $devicevlist[]="6230830230" ;
$devicevlist[]="6230830703" ; $devicevlist[]="6240517617" ; $devicevlist[]="6550409256" ;
$devicevlist[]="6550409278" ; $devicevlist[]="6550409280" ; $devicevlist[]="6550409324" ;
$devicevlist[]="6550409421" ; $devicevlist[]="6550409430" ; $devicevlist[]="6550409474";
$devicevlist[]="6550409570" ; $devicevlist[]="6550409657" ; $devicevlist[]="6550409777" ;
$devicevlist[]="6550409866" ;

$devicevlist[]="6120317501" ;$devicevlist[]="6120317844" ;
$devicevlist[]="6120317790" ;$devicevlist[]="6550409115" ;
$devicevlist[]="6230830118" ;$devicevlist[]="6120317564" ;
$devicevlist[]="6120317737" ;$devicevlist[]="6120317945" ;
$devicevlist[]="4230708260" ;$devicevlist[]="6120317841" ;
$devicevlist[]="4230708819" ;$devicevlist[]="6120317880" ;
$devicevlist[]="6120317419" ;$devicevlist[]="4240621836" ;
$devicevlist[]="6120317825" ;$devicevlist[]="6120317953" ;
$devicevlist[]="6230830415" ;$devicevlist[]="6120317657" ;
$devicevlist[]="6120317689" ;


$devicevlist[]="4230708774" ;

$devicevlist[]="90000663" ;$devicevlist[]="90000713" ;

$devicevlist[]="90000612" ; $devicevlist[]="90000671" ; $devicevlist[]="90000620" ;

$devicevlist[]="90000689" ; $devicevlist[]="90000709" ; $devicevlist[]="90000750" ;

$devicevlist[]="90000654" ; $devicevlist[]="90000751" ; $devicevlist[]="90000584" ;

$devicevlist[]="90000740" ; 

$devicevlist[]="50000885" ; $devicevlist[]="50000888" ; $devicevlist[]="90000892" ;

$devicevlist[]="90000463" ;$devicevlist[]="90000461" ;

$devicevlist[]="90000650" ;$devicevlist[]="90000677" ;
$devicevlist[]="90000715" ;$devicevlist[]="90000674" ;

$devicevlist[]="50000752" ;$devicevlist[]="90000718" ;
$devicevlist[]="90000463" ;

$devicevlist[]="6550409130" ;$devicevlist[]="6550409351" ;
$devicevlist[]="90000696" ;$devicevlist[]="6120317916" ;
$devicevlist[]="6120317525" ;$devicevlist[]="6550409654" ;
$devicevlist[]="6550409515" ;$devicevlist[]="6120317444" ;
$devicevlist[]="6550409363" ;$devicevlist[]="6120317802" ;
$devicevlist[]="6550409791" ;$devicevlist[]="7001494" ;
$devicevlist[]="6230830242" ;$devicevlist[]="6230830338" ;
$devicevlist[]="90000930";$devicevlist[]="6120317653";
$devicevlist[]="90001449" ;$devicevlist[]="90001206" ;

$devicevlist[]="90000660" ; $devicevlist[]="90000534" ; $devicevlist[]="90000666" ; 
$devicevlist[]="90001776" ; $devicevlist[]="10084625" ; $devicevlist[]="10085150" ;
$devicevlist[]="10114497" ; $devicevlist[]="90000398" ; $devicevlist[]="357412089124974" ; 
$devicevlist[]="357412089129817" ; 



/*File Created and code Written end by NR.Vetrivel */
?>
