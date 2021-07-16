<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\ParseConfig;
use PHPMailer\PHPMailer\PHPMailer;


class Mailer
{
    private ParseConfig $config;
    private string $host;
    private string $username;
    private string $password;
    private DataValidation $validator;

    public function __construct(string $host, string $userName, string $password)
    {
        $this->config = new ParseConfig('../config.ini');
        $this->host = $host;
        $this->username = $userName;
        $this->password = $password;
        $this->validator = new DataValidation();
    }
    public function send($infoUser)
    {

        $infoObject = (object)$infoUser;

        if(!empty($infoObject->name)
            && strlen($infoObject->name) <= 20
            && preg_match("/^[A-Za-z '-]+$/",$infoObject->name)
            && !empty($infoObject->surname)
            && strlen($infoObject->surname) <= 20
            && preg_match("/^[A-Za-z '-]+$/",$infoObject->surname)
            && !empty($infoObject->mail)
            && filter_var($infoObject->mail, FILTER_VALIDATE_EMAIL))
            {
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
                $mail->Body    = 'Nom : ' . $this->validator->validate($infoObject->name) . ' ' . $this->validator->validate($infoObject->surname) . '<br/>
                Mail : ' . $this->validator->validate($infoObject->mail) . '<br/>
                Message : ' . $this->validator->validate($infoObject->content);
        
                $mail->send();
            }
        
    }
}
