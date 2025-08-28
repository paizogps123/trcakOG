<?

	session_start();
	include ('../init.php');
	include ('fn_common.php');
	include ('tcpaccess.php');
	include ('../tools/sms.php');
	include ('commandlist.php');
	checkUserSession();
	
	// check privileges
	if ($_SESSION["privileges"] == 'subuser')
	{
		$user_id = $_SESSION["manager_id"];
	}
	else
	{
		$user_id = $_SESSION["user_id"];
	}
	
	if(!$_SESSION["cpanel_privileges"] && $user_id!="82")
	{
		header('Content-type: application/json');
		echo json_encode(false);        
		die;
	}


	
	
	if(@$_POST['cmd'] == 'save_cmd')
	{
		$imei = $_POST["imei"];
		$devicetype = $_POST["devicetype"];
		$use = $_POST["use"];
		$cmd_type = $_POST["cmd_type"];
		$cmd = $_POST["cmdo"];
		
		$qr="select * from command where user_id='".$user_id."' and imei='".$imei."' and used='".$use."' and status!='Finished'";
		$rr = mysqli_query($ms,$qr);
		$count = mysqli_num_rows($rr);

		if($count==0)
		{		
			$port=getObjectIpPortProtocol($imei);
			if(!isset($port["port"]))
			$port["port"]=8016;
			else
			{
				$port=$port["port"];
			}	
			$reply=sendcommandv($cmd,$imei,$use,$port,$devicetype);

	
		if($reply=="Command Send")
		echo "OK";
		else 
		{
			echo $reply;
			//if (ob_get_length()) ob_end_clean();
			//echo $port["port"]."Server Not Responding";
			//die;
		}
		
			$q = "INSERT INTO `command`(`user_id`,
							`imei`,
							`devicetype`,
							`used`,
							`cmdtype`,command,senddate,response,status)
							VALUES
							('".$user_id."',
							'".$imei."',
							'".$devicetype."',
							'".$use."',
							'".$cmd_type."',
							'".$cmd."',
							'".gmdate("Y-m-d H:i:s")."','','Waiting')";				
			$r = mysqli_query($ms,$q);
		
		}
		else 
		{
			echo "Already command is processing ";
		}
		
		die;
	}
	else
	
	if(@$_POST['cmd'] == 'delete_cmd_exec')
	{
		$cmd_id = $_POST["cmd_id"];
		
		$q = "DELETE FROM `command` WHERE `cmd_id`='".$cmd_id."' AND `user_id`='".$user_id."'";
		$r = mysqli_query($ms,$q);
		
		echo 'OK';
		die;
	}
	else 

	if(@$_GET['cmd'] == 'load_cmd_exec_list')
	{
		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
		
		if(!$sidx) $sidx =1;
		
		// get records number
		$q = "SELECT * FROM `command` WHERE `user_id`='".$user_id."'";
		$r = mysqli_query($ms,$q);
		$count = mysqli_num_rows($r);
		
		$q = "SELECT * FROM `command` WHERE `user_id`='".$user_id."' ORDER BY $sidx $sord";
		$result = mysqli_query($ms,$q);
		
		$responce = new stdClass();

		$i=0;
		while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
		{
			$cmd_id = $row['cmd_id'];
			$time = convUserTimezone($row['senddate']);
			$object = getObjectName($user_id, $row['imei']);

			if ($row['response'] == '' && $row['Status']!='Offline' )
			{
				$status = '<span class="spinner" style="height: 3px;"></span>';
				$name_cmd = $row['devicetype'].', '.$row['cmdtype'].', '.$row['used'].', '.$row['command'];
				$re_hex = $row['Status'];
			}
			else
			{
				$status = '<img src="theme/images/tick-green.svg" />';
				$name_cmd = $row['devicetype'].', '.$row['cmdtype'].', '.$row['used'].', '.$row['command'];
				if($row['Status']!='Offline')
				$re_hex = $re_hex = $row['responsedate'].' -> '.$row['response'];
				else 
				$re_hex = $re_hex = $row['responsedate'].' -> '.$row['Status'];
			}

			
			
			// set modify buttons
			$modify = '<a href="#" onclick="cmdExecDeletenew(\''.$cmd_id.'\');"><img src="theme/images/remove3.svg" /></a>';
			// set row
			$responce->rows[$i]['id']=$cmd_id;
			$responce->rows[$i]['cell']=array($time,$object,$name_cmd,$status,$modify,$re_hex);
			$i++;
		}
		
		$responce->page = 1;
		//$responce->total = $count;
		$responce->records = $count;
		
		header('Content-type: application/json');
		echo json_encode($responce);
		die;
	}


?>