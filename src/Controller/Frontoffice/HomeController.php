<?php

declare(strict_types=1);

namespace  App\Controller\Frontoffice;

use App\View\View;
use App\Service\Http\Response;
use App\Service\Http\Request;
use App\Service\Mailer;


final class HomeController
{
    private View $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function homeAction(Request $request): Response
    {
        if (!empty($request->request()->all())) {
            $test = $request->request()->all();
            foreach ($test as $key => $value) {
                var_dump($value);
            }
            $mail = new Mailer();
            $mail->send($request->request()->all());
        }
        return new Response($this->view->render([
            'template' => 'home',
            'data' => [],
        ]));
    }

    public function administrationAction(): Response
    {
        return new Response($this->view->render([
            'template' => 'administration',
            'data' => [],
        ]));
    }
}
