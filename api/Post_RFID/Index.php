
<?php
   include ('Mo.php');  
   $result['Type'] = 'E';$result['Mydata'] = "";$result['Message'] = "";
   if(isset($_GET["cmd"]))
   $_POST=$_GET; 

// echo encrypt('Play@ApIHm!lKey');


   //accept only android and ios request otherwise reject
   /*$agent = $_SERVER['HTTP_USER_AGENT'];
$agent=strtolower($agent);

if (strpos($agent, 'android') !== false) {
$os = 'Android';
}

http://mobiledetect.net/
*/
   
// $myfile = fopen("vvvrfid_api.txt", "a");
// fwrite($myfile,$ip=' From Client: '.json_encode(@$_POST)." Time :".gmdate("Y-m-d H:i:s")." SERVER : ".json_encode(@$_SERVER));
// fwrite($myfile, "\n");
// fclose($myfile);
    
    if(@$_POST['cmd'] == 'Get_RFID_Datails' || @$_POST['cmd'] == 'Get_RFID_Details')
    {
    	if(!isset($_POST["key"]))
    	{
    		$result['Type'] = 'E';
    		$result['Mydata'] = "";
    		$result['Message'] = "Invalid Data!";
    		header('Content-type: application/json');echo json_encode($result);die;
    	}
    	else
    	{
    		Get_RFID_Datails($_POST);
    	}
    }
    if(@$_POST['cmd'] == 'Get_RFID_DateWise')
    {
      if(!isset($_POST["key"]))
      {
         $result['Type'] = 'E';
         $result['Mydata'] = "";
         $result['Message'] = "Invalid Data!";
         header('Content-type: application/json');echo json_encode($result);die;
      }
      else
      {
         Get_RFID_DateWise($_POST);
      }
    }
    else
   	{
   		$result['Type'] = 'e';
   		$result['Mydata'] = "";
   		$result['Message'] = "Invalid Command!";
   		header('Content-type: application/json');echo json_encode($result);die;
   	}




?>
