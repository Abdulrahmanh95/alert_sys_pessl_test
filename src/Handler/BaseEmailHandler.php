<?php

namespace App\Handler;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class BaseEmailHandler
{
    protected function instantiateMail()
    {
        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);
        // Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                     // Enable verbose debug output
        $mail->isSMTP();                                           // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                  // Enable SMTP authentication
        $mail->Username   = 'kobybryant.h@gmail.com';              // SMTP username
        $mail->Password   = 'kbso83295';                           // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;        // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                   // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        return $mail;
    }
}