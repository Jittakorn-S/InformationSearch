<?php
date_default_timezone_set('Asia/Bangkok');

use PHPMailer\PHPMailer\PHPMailer;

require 'mail/PHPMailer.php';
require 'mail/SMTP.php';
//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;
//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';
//Set the hostname of the mail server
$mail->Host = "smtp.gmail.com";
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 587;
//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'tls';
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication
$mail->Username = "informationsearchsystem@gmail.com";
//Password to use for SMTP authentication
$mail->Password = "Test!234";
//Set who the message is to be sent from
$mail->setFrom('informationsearchsystem@gmail.com', '');
//Sqlid
$sqlcheckid = "SELECT company_id FROM appointment WHERE idappointment = {$_POST['idappointment']}";
$getid = mysqli_query($conn, $sqlcheckid);
$resulid = mysqli_fetch_array($getid);
$id = $resulid['company_id'];
//Sqlemail
$sqlemail = "SELECT email FROM member WHERE id = '$id'";
$getemail = mysqli_query($conn, $sqlemail);
$resulemail = mysqli_fetch_array($getemail);
//Set who the message is to be sent to
$mail->addAddress($resulemail['email'], '');
//Set the subject line
$mail->Subject = 'Result from student';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
// $mail->msgHTML(file_get_contents('content.html'), dirname(__FILE__));
$mail->msgHTML("นักศึกษาไม่ตกลงร่วมงานกับคุณ");
$mail->send();
