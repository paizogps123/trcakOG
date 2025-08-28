<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

   include ('Report.php');  
   include ('Mo.php');  


   $result['tyv'] = 'e';$result['mydata'] = "";$result['msg'] = "";
   if(isset($_GET["cmd"]))
   $_POST=$_GET;
    
	// $myfile = fopen("vvv.txt", "a");
	// fwrite($myfile,json_encode($_POST) );
	// fwrite($myfile, "\n");
	// fclose($myfile);


    if(@$_POST['cmd'] == 'Get_User')
    {
        if( !isset($_POST["username"]) || !isset($_POST["password"]) )
        {
            $result['tyv'] = 'e';
            $result['mydata'] = "";
            $result['msg'] = "Invalid Data!";
            header('Content-type: application/json');echo json_encode($result);die;
        }
        else
        {
            Get_User($_POST);
        }
    }
    else if(@$_POST['cmd'] == 'Get_Voice')
    {
        if( !isset($_POST["username"]) || !isset($_POST["password"]) )
        {
            $result['tyv'] = 'e';
            $result['mydata'] = "";
            $result['msg'] = "Invalid Data!";
            header('Content-type: application/json');echo json_encode($result);die;
        }
        else
        {
            Get_Voice($_POST);
        }
    }

    else if(@$_POST['cmd'] == 'Get_Imei')
    {
        if( !isset($_POST["token"])  )
        {
            $result['tyv'] = 'e';
            $result['mydata'] = "";
            $result['msg'] = "Invalid Data!";
            header('Content-type: application/json');echo json_encode($result);die;
        }
        else
        {
            Get_Imei($_POST);
         }

         header('Content-type: application/json');
         echo json_encode($result);
         die;
    }

    else if (@$_POST['cmd'] == "Get_History")
    {
       if( !isset($_POST["imei"]) || !isset($_POST["dtf"])|| !isset($_POST["dtt"]) || !isset($_POST["stopduration"]) || !isset($_POST["stop"]) || !isset($_POST["events"]) || !isset($_POST["token"]))
        {
            $result['tyv'] = 'e';
            $result['mydata'] = $_POST;
            $result['msg'] = "Invalid Data!";
            header('Content-type: application/json');echo json_encode($result);die;
        }
        else
        {
            Get_History($_POST);
        }
        
    }

    else if (@$_POST['cmd'] == "Get_Events")
    {
        if( !isset($_POST["imei"]) || !isset($_POST["token"]) )
        {
            $result['tyv'] = 'e';
            $result['mydata'] = "";
            $result['msg'] = "Invalid Data!";
            header('Content-type: application/json');echo json_encode($result);die;
        }
        else
        {
            Get_Events($_POST);
        }

    }

    else if(@$_POST['cmd'] == 'Get_Single_Track')
    {
        if( !isset($_POST["token"]) || !isset($_POST["imei"])  )
        {
            $result['tyv'] = 'e';
            $result['mydata'] = "";
            $result['msg'] = "Invalid Data!";
            header('Content-type: application/json');echo json_encode($result);die;
        }
        else
        {
            Get_Single_Track($_POST);
         }

         header('Content-type: application/json');
         echo json_encode($result);
         die;
    }
// nandha
     else if(@$_POST['cmd'] == 'Get_Daily_km')
    {
        if( !isset($_POST["token"]) || !isset($_POST["dtf"]) || !isset($_POST["dtt"])  )
        {
            $result['tyv'] = 'e';
            $result['mydata'] = "";
            $result['msg'] = "Invalid Data!";
            header('Content-type: application/json');echo json_encode($result);
            die;
        }
        else
        {
            Get_Daily_km($_POST);
         }

         header('Content-type: application/json');
         echo json_encode($result);
         die;
    }
    //vetrivel
     else if(@$_POST['cmd'] == 'Report_List')
    {
        if( !isset($_POST["token"])  )
        {
            $result['tyv'] = 'e';
            $result['mydata'] = "";
            $result['msg'] = "Invalid Data!";
            header('Content-type: application/json');echo json_encode($result);die;
        }
        else
        {
            Get_Report_List($_POST);
        }

         header('Content-type: application/json');
         echo json_encode($result);
         die;
    }
    //code written by NR.Vetrivel
    else   if(@$_POST['cmd'] == 'Report')
    {
    //     $myfile = fopen("rr.txt", "a");
    // fwrite($myfile,json_encode($_POST) );
    // fwrite($myfile, "\n");
    // fclose($myfile);
        if( !isset($_POST["token"]) || !isset($_POST["imei"])|| !isset($_POST["dtf"])|| !isset($_POST["dtt"])|| 
            !isset($_POST["reporttype"]))
        {
            $result['tyv'] = 'e';
            $result['mydata'] = "";
            $result['msg'] = "Invalid Data!";
            header('Content-type: application/json');echo json_encode($result);die;
        }
        else
        {
            Report($_POST);
        }
    }
    else   if(@$_POST['cmd'] == 'Daily_Analysis')
    {
    // 	$myfile = fopen("vvv_1.txt", "a");
	// fwrite($myfile,json_encode($_POST) );
	// fwrite($myfile, "\n");
	// fclose($myfile);
        if( !isset($_POST["token"]) || !isset($_POST["imei"])|| !isset($_POST["dtf"])|| !isset($_POST["dtt"])
        || !isset($_POST["stop_duration"])
        )
        {
            $result['tyv'] = 'e';
            $result['mydata'] = "";
            $result['msg'] = "Invalid Data!";
            header('Content-type: application/json');echo json_encode($result);die;
        }
        else
        {
            Daily_Analysis($_POST);
        }
    }
    //vetrivel.NR
    else
    {
        $result['tyv'] = 'e';
        $result['mydata'] = "";
        $result['msg'] = "Invalid Command!";
        header('Content-type: application/json');echo json_encode($result);die;
    }




?>
