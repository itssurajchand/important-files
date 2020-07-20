<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Phpmailer_library
{
    public function __construct()
    {
        //log_message('Debug', 'PHPMailer class is loaded.');
    }

    public function load()
    {
    	 require_once(APPPATH."third_party/php_mailer/vendor/autoload.php");
       
        //$mail = new PHPMailer;
        $mail = new PHPMailer(true);
		$mail->IsSMTP();                                      // set mailer to use SMTP
		$mail->Host = "smtp.gmail.com";  // specify main and backup server
		$mail->SMTPAuth = true;     // turn on SMTP authentication
		$mail->SMTPSecure = 'ssl';
		$mail->Port = 465;
		$mail->Username = " ";  // SMTP username
		$mail->Password = " "; // SMTP password
		// $mail->From = " ";
		// $mail->FromName = " ";
		//$mail->AddBCC(" ");
		$mail->IsHTML(true);
		return $mail;
    }
}