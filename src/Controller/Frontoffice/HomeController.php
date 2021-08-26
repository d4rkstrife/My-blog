<?php

declare(strict_types=1);

namespace  App\Controller\Frontoffice;

use App\View\View;
use App\Service\Mailer;
use App\Service\Http\Request;
use App\Service\Http\Response;
use App\Service\DataValidation;
use App\Service\Http\Session\Session;

final class HomeController
{
    private View $view;
    private DataValidation $validator;
    private Mailer $mailer;
    private Session $session;

    public function __construct(View $view, Mailer $mailer, Session $session, DataValidation $validator)
    {
        $this->view = $view;
        $this->mailer = $mailer;
        $this->session = $session;
        $this->validator = $validator;
    }

    public function homeAction(Request $request): Response
    {

        if (!empty($request->request()->all())) {
            $infoContact =  $request->request()->all();

            $name = $this->validator->validate($infoContact['name']);
            $surname = $this->validator->validate($infoContact['surname']);
            $mail = $this->validator->validate($infoContact['mail']);
            $content = $this->validator->validate($infoContact['content']);
            $mailIsSend = true;
            $error = false;
            $flashes = '';

            if (!$this->validator->isValidEntry($name)) {
                $flashes .= 'Nom incorrect. ';
                $error = true;
            }
            if (!$this->validator->isValidEntry($surname)) {
                $flashes .= 'Prenom incorrect. ';
                $error = true;
            }
            if (!$this->validator->isValidMail($mail)) {
                $flashes .= 'Mail incorrect. ';
                $error = true;
            }
            if (!$error) {
                $this->mailer->send([
                    'name' => $name,
                    'surname' => $surname,
                    'mail' => $mail,
                    'content' => $content
                ]) ? $this->session->addFlashes('success', 'Message envoyé.') : $this->session->addFlashes('error', "Le mail n'a pas pu être envoyé.");
            }

            if ($error) {
                $this->session->addFlashes('error', $flashes);
            }
        }

        return new Response($this->view->render([
            'template' => 'home',
            'data' => [],
        ], 'Frontoffice'));
    }

    public function unauthorizedAction(): Response
    {
        return new Response($this->view->render([
            'template' => 'unauthorized',
            'data' => [],
        ], 'Frontoffice'));
    }
}
