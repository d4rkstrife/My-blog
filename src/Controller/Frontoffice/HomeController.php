<?php

declare(strict_types=1);

namespace  App\Controller\Frontoffice;

use App\View\View;
use App\Service\Mailer;
use App\Service\Database;
use App\Service\ParseConfig;
use App\Service\Http\Request;
use App\Service\Http\Response;

final class HomeController
{
    private View $view;
    private ParseConfig $config;
    private Database $database;

    public function __construct(View $view, ParseConfig $config, Database $database)
    {

        $this->view = $view;
        $this->config = $config;
        $this->database = $database;
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
