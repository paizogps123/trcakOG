<?php

function ascii2hex($ascii) {
		$hex = '';
		for ($i = 0; $i < strlen($ascii); $i++) {
			$byte = strtoupper(dechex(ord($ascii{$i})));
			$byte = str_repeat('0', 2 - strlen($byte)).$byte;
			$hex.=$byte."";
		}
		return $hex;
	}

	function encrypt($pure_string ) {
	
		$iv = "1234567812345678";
		$password = "NightCrawler";
		$method = "aes-128-cbc";
		$encrypted = openssl_encrypt($pure_string, $method, $password,false, $iv);
		$encrypted=ascii2hex($encrypted);
		return $encrypted;
	}


function hex2ascii($hex){
		$ascii='';
		$hex=str_replace(" ", "", $hex);
		for($i=0; $i<strlen($hex); $i=$i+2) {
			$ascii.=chr(hexdec(substr($hex, $i, 2)));
		}
		return($ascii);
	}
	

function decrypt($encrypted_string) {
		$iv = "1234567812345678";
		$password = "NightCrawler";
		$method = "aes-128-cbc";
		$encrypted_string=hex2ascii($encrypted_string);
		$decrypted = openssl_decrypt($encrypted_string, $method, $password,false, $iv);
		return $decrypted;
	}


#echo encrypt("Thriveni");

echo "<br>";

echo decrypt("FB0FB235BCDAEFFC2170E08AFC81E669");
?>
