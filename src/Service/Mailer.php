<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\ParseConfig;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



class Mailer
{
    public function send($infoUser)
    {
        $config = new ParseConfig('../config.ini');
        $smtpInfos = $config->parseFile();
        $infoObject = (object)$infoUser;
        $mail = new PHPMailer(true);
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = $smtpInfos->host;                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = $smtpInfos->username;                     //SMTP username
        $mail->Password   = $smtpInfos->password;                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($infoObject->mail, $infoObject->name);
        $mail->addAddress('p.gdc85@gmail.com', 'me');     //Add a recipient

        //Attachments
        //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Demande de contact';
        $mail->Body    = 'Nom : ' . $infoObject->name . ' ' . $infoObject->surname . '<br/>
        Mail : ' . $infoObject->mail . '<br/>
        Message : ' . $infoObject->content;

        $mail->send();
    }
}
