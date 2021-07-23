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
    private ParseConfig $config;

    public function __construct(View $view, ParseConfig $config)
    {
        $this->view = $view;
        $this->config = $config;
    }

    public function homeAction(Request $request): Response
    {
        if (!empty($request->request()->all())) {
            $mail = new Mailer($this->config->getConfig('host'), $this->config->getConfig('username'), $this->config->getConfig('password'));
            $mail->send($request->request()->all());
        }
        return new Response($this->view->render([
            'template' => 'home',
            'data' => [],
        ], 'Frontoffice'));
    }
}
