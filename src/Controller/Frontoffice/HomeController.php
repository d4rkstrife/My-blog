<?php

declare(strict_types=1);

namespace  App\Controller\Frontoffice;

use App\View\View;
use App\Service\Mailer;
use App\Service\ParseConfig;
use App\Service\Http\Request;
use App\Service\Http\Response;

final class HomeController
{
    private View $view;

    public function __construct(View $view, Mailer $mailer)
    {
        $this->view = $view;
        $this->mailer = $mailer;
    }

    public function homeAction(Request $request): Response
    {
        if (!empty($request->request()->all())) {
            $this->mailer->send($request->request()->all());
        }
        return new Response($this->view->render([
            'template' => 'home',
            'data' => [],
        ], 'Frontoffice'));
    }
}
