<?
        session_start();
        include ('../init.php');
        include ('fn_common.php');
        checkUserSession();
        //Code Update By Vetrivel.NR
        // need to delete comments if there is no issue in live

        if (!isset($_GET['imei']) &&  !isset($_GET['object']))
        {
                //echo "1";
                die;
        }

        // check privileges
        if ($_SESSION["privileges"] == 'subuser')
        {
                $user_id = $_SESSION["manager_id"];
        }
        else
        {
                $user_id = $_SESSION["user_id"];
        }

       
        $imei='';
        if (isset($_GET['imei']))
        {
                $imei = @$_GET['imei'];
                //echo "T1";
        }


        $imei_list = "202400000047,353201353501031,353201353498717,353201355924587,353201353502393,353201355924314,353201355924470,353201355860815,353201355924561,353201353502096,353201356328531,353201355924736,353201355929552,352592573001211,353201356328465,353201355928117,353201355928059,353201355924397,353201353496794,353201355924496,353201353496703,353201355924686,353201356470168,353201355924579,353201353491977,353201353503268,353201353496950,353201356328945,353201356470325,353201353491902,353201353490904,353201353496786,353201353496869,353201353504456,353201355861003,353201353502377,353201355924660";

		// Convert the string to an array
		$imeis = explode(",", $imei_list);

		// Check if IMEI exists in the array
		$imei_exist = in_array($imei, $imeis);

		// $target_ip = "140.245.6.5";
        $uri_http = "https";
        $target_ip = "cam.paizogps.in";
        $user_name = "admin";
        if($imei_exist){
        	$target_ip = "15.235.206.64";
        	$user_name = "PAIZO";
            $uri_http = "http";
        }


        if (isset($_GET['object']))
        {
                $imei = @$_GET['object'];
                if($object=getObjectFromName($imei))
                {
                        $imei=$object["imei"];
                        //echo "T2";
                }
                else
                {
                        //echo json_encode($object)."2";
                        die;
                }
        }

        if($imei=='')
        {
                        //echo "3";
                        die;
        }

        $devID='';
        if (isset($_GET['devID']))
        {
                $devID = @$_GET['devID'];
        }

        if($devID=='')
        {
                        die;
        }

        loadLanguage($_SESSION["language"], $_SESSION["units"]);



        function get_token() {
            // URL to access
          	global $target_ip,$user_name,$uri_http; 
           	$url = $uri_http."://".$target_ip."/StandardApiAction_login.action?account=".$user_name."&password=Paizo@123";
         
  		file_put_contents("0url.txt", $url . PHP_EOL, FILE_APPEND);	

            // Initialize cURL session
            $ch = curl_init();

            // Set the URL and other cURL options
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Execute the cURL request and store the response
            $response = curl_exec($ch);

            // Check for cURL errors
            if (curl_errno($ch)) {
                throw new Exception('cURL error: ' . curl_error($ch));
            }

            // Close the cURL session
            curl_close($ch);

            // Decode the JSON response
            print_r($response);
            $data = json_decode($response, true);

            // Check if the response contains the 'jsession' key
            if (isset($data['jsession'])) {
                // Return the 'jsession' value
                return $data['jsession'];
            } else {
                throw new Exception("JSESSION not found in the response.");
            }
        }

        $jsession = get_token();

// Target URL


$target_url = $uri_http.'://'.$target_ip.'/808gps/open/player/video.html?lang=en&devIdno='.htmlspecialchars($devID).'&jsession='.$jsession;
//
// Perform the redirect
header('Location: ' . $target_url);
exit();


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <style>
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
            }
            iframe {
                width: 100%;
                height: 100%;
                border: none;
            }
        </style>
    </head>
    <body>
    <iframe src="http://140.245.6.5/808gps/open/player/video.html?lang=en&devIdno=<?php echo htmlspecialchars($devID); ?>&account=PAIZO&password=Paizo@123"></iframe>
    </body>
</html>



<!--
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <body>
        <iframe src="http://120.79.58.1:8088/808gps/open/player/video.html?lang=en&devIdno=<?php echo htmlspecialchars($devID); ?>&account=PGS&password=000000" height="auto"></iframe>
    </body>
</html>
-->
