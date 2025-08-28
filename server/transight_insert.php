<?php

// header('Content-type: application/json');
// $content = file_get_contents("php://input");
// $recive=json_decode($content,true);

// // $myfile = fopen("transight_log.txt", "a");
// // fwrite($myfile,json_encode($recive));
// // fwrite($myfile, "\n");
// // fclose($myfile);

// $myfile = fopen("transight_log.txt", "a");
// fwrite($myfile,json_encode($_POST['vltjson']));
// fwrite($myfile, "\n");
// fwrite($myfile,json_encode($_GET));
// fwrite($myfile, "\n");
// fclose($myfile);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // collect value of input field
  $name = $_REQUEST['vltjson'];

  	$myfile = fopen("transight_log.txt", "a");
	fwrite($myfile,'****************************');
	fwrite($myfile, "\n");
	fwrite($myfile,$name);
	fwrite($myfile, "\n");
	fwrite($myfile,'****************************');
	fwrite($myfile, "\n");
	fclose($myfile);

  echo json_encode(array("command" =>"GET CUUR"));
}

// $myfile = fopen("transight_log.txt", "a");
// fwrite($myfile,json_encode(json_decode($_POST),true));
// fwrite($myfile, "\n");
// fwrite($myfile,json_encode($_GET));
// fwrite($myfile, "\n");
// fclose($myfile);

// echo json_encode(array("command" =>"GET CUUR"));
?>