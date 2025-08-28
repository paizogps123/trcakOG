<?php

 
 
  function sendcommandv($cmd,$imei,$use,$porto,$devicetype)
 {
 	$reply='';
	try {
		if($porto==11906 ||$porto==11905 || $porto==11904 || $porto==11903 || $porto==11902 || $devicetype=="Play T20"|| $devicetype=="Play T09"|| $devicetype=="Play T09+")
			$protocol='TCP';
		else 
			$protocol='TCPD';

	if ($porto=="")
$porto="11906";
		
		$url='http://184.168.124.42:8356/WebCommand20.aspx?key=VetriTest123$&cmd='.ascii2hex($cmd).'&lockimei='.$imei.'&ip=184.168.124.42&port='.$porto.'&device='.urlencode($devicetype).'&protocol='.$protocol."&use=".urlencode($use);		


		$options = array(
								'http' => array(
								'header'  => "Content-type: text/html; charset=utf-8",
								'method'  => 'GET'
		)
		);
		
		$context  = stream_context_create($options);
		$reply = file_get_contents($url);

		$replyary=explode('<!DOC', $reply);
		if(count($replyary)>0)
		{
			$reply=$replyary[0];
		}


	}
	catch (xception $e)
	{
		error_reporting(0);
		$reply= "Not Responding";
	}
	return  preg_replace("/[\\n\\r]+/", "", $reply);
 }
 


?>