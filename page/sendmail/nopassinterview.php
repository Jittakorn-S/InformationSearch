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
//Set who the message is to be sent to
$sqlemail = "SELECT avatarenvelope FROM STD_PORTFOLIO.dbo.std_master WHERE stdid = '$student_id'";
$getemail = $sqlconn->prepare($sqlemail);
$getemail->execute();
$rowemail = $getemail->fetch(PDO::FETCH_ASSOC);
$mail->addAddress($rowemail["avatarenvelope"], '');
//Set the subject line
$mail->Subject = 'Announcement of interview results from the information search system';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
//$mail->msgHTML(file_get_contents('content.html'), dirname(__FILE__));
$mail->msgHTML("คุณไม่ผ่านการสัมภาษณ์กรุณาไปที่เว็บไซต์ http://www.encm.rmutt.ac.th/informationsearch/index.php?p=interviewresults เพื่อดูรายละเอียด");
$mail->send();
