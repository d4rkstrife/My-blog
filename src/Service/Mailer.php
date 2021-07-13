<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\ParseConfig;
use PHPMailer\PHPMailer\PHPMailer;


class Mailer
{
    private ParseConfig $config;
    private Object $smtpInfos;
    private string $host;
    private string $username;
    private string $password;

    public function __construct()
    {
        $this->config = new ParseConfig('../config.ini');
        $this->smtpInfos = $this->config->parseFile();
        $this->host = $this->smtpInfos->host;
        $this->username = $this->smtpInfos->username;
        $this->password = $this->smtpInfos->password;
    }
    public function send($infoUser)
    {

        $infoObject = (object)$infoUser;
        $mail = new PHPMailer(true);
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = $this->host;                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = $this->username;                     //SMTP username
        $mail->Password   = $this->password;                               //SMTP password
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
