<?php

if(function_exists('xdebug_disable')) { xdebug_disable(); }
        error_reporting(E_ALL ^ E_DEPRECATED);

error_reporting(E_ERROR | E_PARSE);

function encrypt($pure_string ) {

	$iv = "1234567812345678";
	$password = "NightCrawler";
	$method = "aes-128-ecb";
	$encrypted = openssl_encrypt($pure_string, $method, $password,false, $iv);
	$encrypted=ascii2hex($encrypted);
	return $encrypted;
}

function decrypt($encrypted_string) {
	$iv = "1234567812345678";
	$password = "NightCrawler";
	$method = "aes-128-ecb";
	$encrypted_string=hex2ascii($encrypted_string);
	$decrypted = openssl_decrypt($encrypted_string, $method, $password,false, $iv);
	return $decrypted;
}

function ascii2hex($ascii) {
	$hex = '';
	for ($i = 0; $i < strlen($ascii); $i++) {
		$byte = strtoupper(dechex(ord($ascii{$i})));
		$byte = str_repeat('0', 2 - strlen($byte)).$byte;
		$hex.=$byte."";
	}
	return $hex;
}


function hex2ascii($hex){
	$ascii='';
	$hex=str_replace(" ", "", $hex);
	for($i=0; $i<strlen($hex); $i=$i+2) {
		$ascii.=chr(hexdec(substr($hex, $i, 2)));
	}
	return($ascii);
}

$i = "p@izorfid_prrd@ta";
$c = encrypt($i);

echo $i;

echo "<br>";

echo $c;

echo "<br>";

$p = decrypt($c);

echo  $p;


?>
