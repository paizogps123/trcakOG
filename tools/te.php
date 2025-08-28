<?php

	include ('email.php');
	include ('../config.php');
	include ('../config.custom.php');

	sendEmail1("vetri42@gmail.com", "Test Message", "Test msg by Playgps", $no_reply = false, $att_name = false, $att_str = false);
	
	function sendEmail1($email, $subject, $message, $no_reply = false, $att_name = false, $att_str = false)
	{
		require_once 'PHPMailerAutoload.php';
		global $ms, $gsValues;
		
		$signature = "\r\n\r\n".$gsValues['EMAIL_SIGNATURE'];
		$message .= $signature;
		$message = str_replace("\\n", "\n", $message); 
		
		$mail = new PHPMailer();
	
		$mail->IsSMTP(); // telling to use SMTP
		$mail->Host       = "smtp-relay.sendinblue.com";
		$mail->Port       = 587;
		$mail->SMTPDebug  = 1;
		$mail->SMTPAuth   = $gsValues['EMAIL_SMTP_AUTH'];
		$mail->SMTPSecure = $gsValues['EMAIL_SMTP_SECURE'];
		$mail->Username   = "support@paizogps.com";
		$mail->Password   = "m4AMgjqKdyrQfV0X";
		
		
		$email_from = $gsValues['EMAIL'];
		
		if ($no_reply != false)
		{
			if ($gsValues['EMAIL_NO_REPLY'] != '')
			{
				$email_from = $gsValues['EMAIL_NO_REPLY'];
			}	
		}
		if(@$email_from=="")
		$email_from ="no-reply@paizogps.com";

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
