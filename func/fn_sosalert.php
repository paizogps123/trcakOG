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


if(@$_POST['cmd'] == "save_soscompletetoken_data")
	{
		global $ms;
		$items=$_POST['items'];		

		$q = "INSERT INTO event_action (`response`,`action_taken`,`action_by`,`event_id`,`action_date`) VALUE ('".$items[1]."','".$items[2]."','".$items[3]."','".$items[0]."','".gmdate("Y-m-d H:i:s")."')";
		$r1 = mysqli_query($ms, $q);	
			
		echo 'OK';
		die;
	}



?>