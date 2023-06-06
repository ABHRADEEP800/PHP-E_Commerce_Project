<?php

require('PHPMailer/src/PHPMailer.php');
require('PHPMailer/src/SMTP.php');

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer\PHPMailer\PHPMailer(true);

    //Server settings
    //Enable verbose debug output
    $mail->isSMTP();                           //Send using SMTP
    $mail->Host       = 'smtp.demohost.com';  //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                  //Enable SMTP authentication
    $mail->Username   = 'user@username.com';  //SMTP username
    $mail->Password   = 'password';           //SMTP password
    $mail->SMTPSecure = 'ssl';                  //Enable implicit TLS encryption
    $mail->Port       = 465;                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('user@username.com', 'user');
    
?>