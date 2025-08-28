<?php

	include ('email.php');
	include ('../config.php');
	include ('../config.custom.php');

	sendEmail1("support@paizogps.com,muthu@paizogps.com,katwinl@paizogps.com", "We can use Paizogps.com email on email systems", "We can use Paizogps.com email on email system, Please ignore this email", $no_reply = false, $att_name = false, $att_str = false);
	
	function sendEmail1($email, $subject, $message, $no_reply = false, $att_name = false, $att_str = false)
	{
		require_once 'PHPMailerAutoload.php';
		global $ms, $gsValues;
		
		$signature = "\r\n\r\n".$gsValues['EMAIL_SIGNATURE'];
		$message .= $signature;
		$message = str_replace("\\n", "\n", $message); 
		
		$mail = new PHPMailer();
	
		$mail->IsSMTP(); // telling to use SMTP
		#$mail->Host       = "smtp.email.ap-mumbai-1.oci.oraclecloud.com";
		#$mail->Port       = 587;
		$mail->SMTPDebug  = 1;
		#$mail->SMTPAuth   = true;
		#$mail->SMTPSecure = "tls";
		#$mail->Username   = "ocid1.user.oc1..aaaaaaaauw3emslet4tpaamrz5gt7pcjesl4kils57wadk6mp4m5kvotiqsq@ocid1.tenancy.oc1..aaaaaaaaggaw26kjzmg3nbjbnueb75dqcmvyep5qqp26gk4624dz3xioy4oq.gk.com";
		#$mail->Password   = '0{Lp.mS+KSFjS8Ou_$BR';
		
		
		$email_from = $gsValues['EMAIL'];
		
		if ($no_reply != false)
		{
			if ($gsValues['EMAIL_NO_REPLY'] != '')
			{
				$email_from = $gsValues['EMAIL_NO_REPLY'];
			}	
		}
		if(@$email_from=="")
		$email_from ="no-reply@paizogps.in";

		$mail->From = $email_from;
		$mail->FromName = $gsValues['NAME'];		
		$mail->SetFrom($email_from, $gsValues['NAME']);
		$mail->AddReplyTo($email_from, $gsValues['NAME']);
		
		// multiple emails
		$emails = explode(",", $email);
		
		for ($i = 0; $i < count($emails); ++$i)
		{
			if ($i > 4)
			{
				break;
			}
			
			$email = trim($emails[$i]);
			
			$mail->AddAddress($email, '');
		}
		
		if ($att_name != false)
		{
			$mail->AddStringAttachment($att_str,$att_name);
		}
		
		$mail->Subject = $subject;
		$mail->Body = $message;
		echo  json_encode($mail);

		if(!$mail->Send())
		{
	 $myfile = fopen("email_f.txt", "a");
	 fwrite($myfile,"fail   ".$email." ^ ". $subject." ^ ". json_encode($mail));
	 fwrite($myfile, "\n");
	 fclose($myfile);
			return false;
		}
		else
		{
	 $myfile = fopen("email_s.txt", "a");
	 fwrite($myfile,"success   ".$email." ^ ". $subject." ^ ". $message);
	 fwrite($myfile, "\n");
	 fclose($myfile);
			return count($emails);
		}
	}

?>
