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
    private string $mail;

    public function __construct(string $host, string $userName, string $password, string $mail)
    {
        $this->host = $host;
        $this->username = $userName;
        $this->password = $password;
        $this->mail = $mail;
    }
    public function send(array $infoUser): bool
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
        $mail->addAddress($this->mail, 'me');     //Add a recipient

        //Attachments
        //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Demande de contact';
        $mail->Body    = 'Nom : ' . $infoObject->name . ' ' . $infoObject->surname . '<br/>
                Mail : ' . $infoObject->mail . '<br/>
                Message : ' . $infoObject->content;

        return $mail->send();
    }

    public function sendConfirmationMessage(User $user): bool
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
        $mail->setFrom($this->mail, 'My Blog');
        $mail->addAddress($user->getEmail(), $user->getName());

        //Attachments
        //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Inscription sur My Blog';
        $mail->Body    = 'Votre compte sur My Blog a bien ??t?? cr????. Afin de le valider, veuillez cliquer sur le lien : http://localhost:8000/index.php?action=validation&amp;id=' . $user->getId() . '&amp;key=' . $user->getRegistrationKey() . '';

        return $mail->send();
    }
}
