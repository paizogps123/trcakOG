<? 
	session_start();
	include ('../init.php');
	include ('fn_common.php');
	checkUserSession();
	
	loadLanguage($_SESSION["language"], $_SESSION["units"]);
	
	// check privileges
	if ($_SESSION["privileges"] == 'subuser')
	{
		$user_id = $_SESSION["manager_id"];
	}
	else
	{
		$user_id = $_SESSION["user_id"];
	}

	if(@$_POST['cmd'] == 'delete_object_working_hour')
	{
		$field_id = $_POST["field_id"];
		$imei = $_POST["imei"];
		
		$q = "DELETE FROM `ex_user_work_hour` WHERE user_id='".$user_id."' and `whid`='".$field_id."' AND `imei`='".$imei."'";
		$r = mysqli_query($ms, $q);
		//echo $q;
		echo 'OK';
		die;
	}
	
	if(@$_POST['cmd'] == 'delete_selected_object_working_hour')
	{
		$items = $_POST["items"];
		$imei = $_POST["imei"];
		
		for ($i = 0; $i < count($items); ++$i)
		{
			$item = $items[$i];
			
			$q = "DELETE FROM `ex_user_work_hour` WHERE  user_id='".$user_id."' and `whid`='".$item."' AND `imei`='".$imei."'";
			$r = mysqli_query($ms, $q);
		}
		
		echo 'OK';
		die;
	}
	
	if(@$_POST['cmd'] == 'save_object_working_hour')
	{
		$field_id = $_POST["field_id"];
		$imei = $_POST["imei"];
		$from_time = $_POST["ft"];
		$to_time = $_POST['tt'];
		$today = $_POST['type'];
		$day = $_POST['day'];
		$status = $_POST['status'];
				
		if ($field_id == 'false')
		{
			$q = "INSERT INTO `ex_user_work_hour` (`imei`,user_id, `from_time`, `to_time`, `today`, `day`,status) VALUES ('".$imei."', '".$user_id."', '".$from_time."', '".$to_time."', '".$today."', '".$day."', '".$status."')";
		}
		else
		{
			$q = "UPDATE `ex_user_work_hour` SET `imei`='".$imei."', `from_time`='".$from_time."', `to_time`='".$to_time."', `today`='".$today."', `day`='".$day."', `status`='".$status."' WHERE `whid`='".$field_id."'";
		}

		$r = mysqli_query($ms, $q);
		
		echo 'OK';
	}
	
	if(@$_GET['cmd'] == 'load_object_working_hour')
	{ 
		$page = $_GET['page']; // get the requested page
		$limit = $_GET['rows']; // get how many rows we want to have into the grid
		$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
		$sord = $_GET['sord']; // get the direction
		
		$imei = $_GET['imei'];
		
		if(!$sidx) $sidx =1;
		
		// get records number
		$q = "SELECT * FROM `ex_user_work_hour` WHERE `imei`='".$imei."'";
		$r = mysqli_query($ms, $q);
		$count = mysqli_num_rows($r);
		
		$q = "SELECT * FROM `ex_user_work_hour` WHERE `imei`='".$imei."' ORDER BY $sidx $sord";
		$r = mysqli_query($ms, $q);
		
		$response = new stdClass();
		$response->page = 1;
		//$response->total = $count;
		$response->records = $count;
		if ($r)
		{
			$i=0;
			while($row = mysqli_fetch_array($r)) {
				
				$field_id = $row["whid"];
				$from_time = $row["from_time"];
				$to_time = $row['to_time'];
				$today = $row['today'];
				$day = $row['day'];
				$status = $row['status'];
				
								
				if ($status == 'true')
				{
					$status = '<img src="theme/images/tick-green.svg" />';
				}
				else
				{
					$status = '<img src="theme/images/remove-red.svg" style="width:12px;" />';
				}
				
				// set modify buttons
				$modify = '<a href="#" onclick="settingsObjectWorkingHourProperties(\''.$field_id.'\');" title="'.$la['EDIT'].'"><img src="theme/images/edit.svg" />';
				$modify .= '</a><a href="#" onclick="settingsObjectWorkingHourDelete(\''.$field_id.'\');" title="'.$la['DELETE'].'"><img src="theme/images/remove3.svg" /></a>';
				// set row
				$response->rows[$i]['id']=$field_id;
				$response->rows[$i]['cell']=array($from_time,$to_time,$day,$status,$modify);
				$i++;
			}
		}
		
		header('Content-type: application/json');
		echo json_encode($response);
		die;
	}
?>