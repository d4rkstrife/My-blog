<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\User;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    private string $host;
    private string $username;
    private string $password;

    public function __construct(string $host, string $userName, string $password)
    {
        $this->host = $host;
        $this->username = $userName;
        $this->password = $password;
    }
    public function send(array $infoUser): void
    {

        $infoObject = (object)$infoUser;

        if (
            !empty($infoObject->name)
            && strlen($infoObject->name) <= 20
            && preg_match("/^[A-Za-z '-]+$/", $infoObject->name)
            && !empty($infoObject->surname)
            && strlen($infoObject->surname) <= 20
            && preg_match("/^[A-Za-z '-]+$/", $infoObject->surname)
            && !empty($infoObject->mail)
            && filter_var($infoObject->mail, FILTER_VALIDATE_EMAIL)
        ) {
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
    public function sendConfirmationMessage(User $user)
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
        $mail->setFrom($user->getEmail(), 'My Blog');
        $mail->addAddress($user->getEmail(), $user->getName());

        //Attachments
        //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Inscription sur My Blog';
        $mail->Body    = 'http://localhost:8000/index.php?action=validation&amp;id=' . $user->getId() . '&amp;key=' . $user->getRegistrationKey() . '';

        $mail->send();
    }
}
