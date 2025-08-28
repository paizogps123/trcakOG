<?
        // ############################################################
        // All listed setting can be changed only by editing this file
        // Other settings can be changed from CPanel/Manage server
        // ############################################################
        
        $gsValues['VERSION_ID'] = 0;
        $gsValues['VERSION'] = '1.0';
        
        $gsValues['HTTP_MODE'] = 'http'; // options: http/https
        
        // lock admin to IP addresses, example $gsValues['ADMIN_IP'] = '127.0.0.1,222.222.222.222,333.333.333.333';
        $gsValues['ADMIN_IP'] = '';
        
        // log out admin user if IP changes during active session, provides additional security from session stealing
        $gsValues['ADMIN_IP_SESSION_CHECK'] = false; // options: false/true
        
        $gsValues['SERVER_IP'] = '127.0.0.1'; // used only as information in CPanel
        $gsValues['URL_SERVER_PORTS'] = '';  // used only as information in CPanel
        
        // multi server login
        $gsValues['MULTI_SERVER_LOGIN'] = false; // options: false/true
        $gsValues['MULTI_SERVER_LIST'] = array('' => '');
        
        $gsValues['OBJECT_LIMIT'] = 0; // options: 0 means no limit, number sets limit
        $gsValues['LOCATION_FILTER'] = true; // options: false/true
        $gsValues['CURL'] = true; // options: false/true
        
        // path to root of web application
        // if application is installed not in root folder of web server, then folder name must be added, for example we install it in track folder: $_SERVER['DOCUMENT_ROOT'].'/track';
        // very often web servers have no $_SERVER['DOCUMENT_ROOT'] set at all, then direct path should be used, for example c:/wamp/www or any other leading to www or public_html folder
        $gsValues['PATH_ROOT'] = $_SERVER['DOCUMENT_ROOT']."/track/";
        // url to root of web application, example: $gsValues['URL_ROOT'] = 'YOUR_DOMAIN/track';
        $gsValues['URL_ROOT'] = 'http://localhost/track/';
        
        $gsValues['URL_GC'] = array(); // do not remove this line
        $gsValues['URL_GC'][] = 'http://localhost/track/tools/gc/google.php'; // url to geocoder, used for getting addresses, example: $gsValues['URL_GC'][] = 'YOUR_DOMAIN/track/tools/gc/google.php';
        //$gsValues['URL_GC'][] = ''; // another url to geocoder (if needed)
        //$gsValues['URL_GC'][] = ''; // another url to geocoder (if needed)
        
        // hardware key, should be same as in GPS-Server
        $gsValues['HW_KEY'] = '1695AC938554DCBFEA62D461861F0D69VETRI';
        
     
         // connection to MySQL database
         
   
		
        $gsValues['DB_PORT']     = '3306'; // database host
	$gsValues['DB_HOSTNAME'] = '127.0.0.1'; // database host
        $gsValues['DB_USERNAME'] = 'root'; // database user name
        $gsValues['DB_PASSWORD'] = '2025'; // database password
	$gsValues['DB_NAME']	 = 'pgsbeta'; // database name
		
    
     

$gsValues['POST_RFID'] =array();
$crewtype_tme[0]=array("start"=>"6","end"=>"14");
$crewtype_tme[1]=array("start"=>"14","end"=>"22");
$crewtype_tme[2]=array("start"=>"22","end"=>"6");

$gsValues['POST_RFID_ID'] = array(array("id"=>2219,"url"=>"")); 

$gsValues['zone_india1']="9.579084,75.937500,11.953349,74.838867,16.130262,72.290039,20.468189,69.960938,24.846565,67.675781,26.509905,69.345703,28.998532,69.433594,32.472695,72.685547,38.272689,72.333984,37.439974,79.980469,29.916852,81.738281,28.381735,86.132813,29.840644,88.945313,29.611670,97.207031,21.453069,93.164063,22.105999,90.263672,22.268764,89.472656,18.187607,86.967773,7.449624,77.255859,9.579084,75.937500";
$gsValues['zone_india']="9.579084,75.937500,11.953349,74.838867,16.130262,72.290039,20.468189,69.960938,24.846565,67.675781,26.509905,69.345703,28.998532,69.433594,32.472695,72.685547,38.272689,72.333984,37.439974,79.980469,29.916852,81.738281,28.381735,86.132813,29.840644,88.945313,29.611670,97.207031,21.453069,93.164063,22.105999,90.263672,22.268764,89.472656,18.187607,86.967773,15.161687,82.651367,9.026756,79.609985,7.449624,77.255859,9.579084,75.937500";
$gsValues['zone_uganda']="1.199864,35.087891,-1.115766,34.003982,-1.170687,30.433426,-1.446380,30.303955,-1.555099,29.873123,-1.434291,29.202957,3.761943,30.670166,4.373075,34.289627,2.596653,35.124588,1.199864,35.087891";

?>
