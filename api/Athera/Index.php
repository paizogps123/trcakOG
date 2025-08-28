
<?php

   include ('Mo.php');  
   $result['tyv'] = 'e';$result['mydata'] = "";$result['msg'] = "";
   if(isset($_GET["cmd"]))
   $_POST=$_GET;
    
   //accept only android and ios request otherwise reject
   /*$agent = $_SERVER['HTTP_USER_AGENT'];
$agent=strtolower($agent);

if (strpos($agent, 'android') !== false) {
$os = 'Android';
}

http://mobiledetect.net/
*/
  
     if(@$_POST['cmd'] == 'Send_OTP')
    {
    	if( !isset($_POST["mobi"]))
    	{
    		$result['tyv'] = 'e';
    		$result['mydata'] = "";
    		$result['msg'] = "Invalid Data!";
    		header('Content-type: application/json');echo json_encode($result);die;
    	}
    	else
    	{
    		Send_OTP($_POST);
    	}
    }
    else   if(@$_POST['cmd'] == 'Login')
    {
    	if( !isset($_POST["token"]) || !isset($_POST["OTP"]))
    	{
    		$result['tyv'] = 'e';
    		$result['mydata'] = "";
    		$result['msg'] = "Invalid Data!";
    		header('Content-type: application/json');echo json_encode($result);die;
    	}
    	else
    	{
    		Login($_POST);
    	}
    }
    else   if(@$_POST['cmd'] == 'My_Profile')
    {
    	if( !isset($_POST["token"]))
    	{
    		$result['tyv'] = 'e';
    		$result['mydata'] = "";
    		$result['msg'] = "Invalid Data!";
    		header('Content-type: application/json');echo json_encode($result);die;
    	}
    	else
    	{
    		My_Profile($_POST);
    	}
    }
    else   if(@$_POST['cmd'] == 'Get_My_Child')
    {
    	if( !isset($_POST["token"]))
    	{
    		$result['tyv'] = 'e';
    		$result['mydata'] = "";
    		$result['msg'] = "Invalid Data!";
    		header('Content-type: application/json');echo json_encode($result);die;
    	}
    	else
    	{
    		Get_My_Child($_POST);
    	}
    }
     else   if(@$_POST['cmd'] == 'Track_Child')
    {
    	if( !isset($_POST["token"]) || !isset($_POST["id"]))
    	{
    		$result['tyv'] = 'e';
    		$result['mydata'] = "";
    		$result['msg'] = "Invalid Data!";
    		header('Content-type: application/json');echo json_encode($result);die;
    	}
    	else
    	{
    		Track_Child($_POST);
    	}
    }
    else   if(@$_POST['cmd'] == 'Add_Parent')
    {
    	if( !isset($_POST["token"]) || !isset($_POST["name"])  || !isset($_POST["email"]) || !isset($_POST["phone"]))
    	{
    		$result['tyv'] = 'e';
    		$result['mydata'] = "";
    		$result['msg'] = "Invalid Data!";
    		header('Content-type: application/json');echo json_encode($result);die;
    	}
    	else
    	{
    		Add_Parent($_POST);
    	}
    }
    else   if(@$_POST['cmd'] == 'Edit_Parent')
    {
    	if( !isset($_POST["token"]) || !isset($_POST["name"])  || !isset($_POST["email"]) || !isset($_POST["phone"])  || !isset($_POST["id"]))
    	{
    		$result['tyv'] = 'e';
    		$result['mydata'] = "";
    		$result['msg'] = "Invalid Data!";
    		header('Content-type: application/json');echo json_encode($result);die;
    	}
    	else
    	{
    		Edit_Parent($_POST);
    	}
    }
     else   if(@$_POST['cmd'] == 'Delete_Parent')
    {
    	if( !isset($_POST["token"]) || !isset($_POST["id"]))
    	{
    		$result['tyv'] = 'e';
    		$result['mydata'] = "";
    		$result['msg'] = "Invalid Data!";
    		header('Content-type: application/json');echo json_encode($result);die;
    	}
    	else
    	{
    		Delete_Parent($_POST);
    	}
    }
    else   if(@$_POST['cmd'] == 'List_Parent')
    {
    	if( !isset($_POST["token"]))
    	{
    		$result['tyv'] = 'e';
    		$result['mydata'] = "";
    		$result['msg'] = "Invalid Data!";
    		header('Content-type: application/json');echo json_encode($result);die;
    	}
    	else
    	{
    		List_Parent($_POST);
    	}
    }
     else   if(@$_POST['cmd'] == 'Set_Zone_Limit')
    {
    	if( !isset($_POST["token"]) || !isset($_POST["limit_type"]) || !isset($_POST["limit_value"]) || !isset($_POST["id"]))
    	{
    		$result['tyv'] = 'e';
    		$result['mydata'] = "";
    		$result['msg'] = "Invalid Data!";
    		header('Content-type: application/json');echo json_encode($result);die;
    	}
    	else
    	{
    		Set_Zone_Limit($_POST);
    	}
    }
    else   if(@$_POST['cmd'] == 'Feedback')
    {
    	if( !isset($_POST["token"]) || !isset($_POST["feedback"]))
    	{
    		$result['tyv'] = 'e';
    		$result['mydata'] = "";
    		$result['msg'] = "Invalid Data!";
    		header('Content-type: application/json');echo json_encode($result);die;
    	}
    	else
    	{
    		Feedback($_POST);
    	}
    }
    else   if(@$_POST['cmd'] == 'Logout')
    {
    	if( !isset($_POST["token"]))
    	{
    		$result['tyv'] = 'e';
    		$result['mydata'] = "";
    		$result['msg'] = "Invalid Data!";
    		header('Content-type: application/json');echo json_encode($result);die;
    	}
    	else
    	{
    		Logout($_POST);
    	}
    }
    else   if(@$_POST['cmd'] == 'SOS_event')
    {      
       $myfile = fopen("SOS_vvv.txt", "a");
      fwrite($myfile,json_encode($_post));
      fwrite($myfile, "\n");
      fclose($myfile);
      if( !isset($_POST["token"]))
      {
         $result['tyv'] = 'e';
         $result['mydata'] = "";
         $result['msg'] = "Invalid Data!";
         header('Content-type: application/json');echo json_encode($result);die;
      }
      else
      {
         Create_SOSevent($_POST);
      }
    }
   	else
   	{
   		$result['tyv'] = 'e';
   		$result['mydata'] = "";
   		$result['msg'] = "Invalid Command!";
   		header('Content-type: application/json');echo json_encode($result);die;
   	}




?>