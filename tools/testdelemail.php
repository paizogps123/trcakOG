<?php

	include ('s_init.php');
	include ('../func/fn_common.php');
	include ('gc_func.php');

include("email.php");

$v = sendEmail("support@paizogps.com","TN 18 AW 4236 - EMERGENCY ALERT", "Hello, This is event message, please do not reply to this message. Object: TN 18 AW 4236 Event: EMERGENCY ALERT Position: http://maps.google.com/maps?q=12.90549,80.20784&t=m Speed: 2 kph Time (position): 2021-10-27 17:58:16 --  Paizo Gps
");

echo json_encode( $v);

?>
