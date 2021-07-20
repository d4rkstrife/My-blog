<?php

declare(strict_types=1);

namespace  App\Controller\Frontoffice;

use App\View\View;
use App\Service\Mailer;
use App\Service\Counter;
use App\Service\Database;
use App\Service\ParseConfig;
use App\Service\Http\Request;
use App\Service\Http\Response;


final class HomeController
{
    private View $view;
    private ParseConfig $config;
    private Object $repository;

    public function __construct(View $view, ParseConfig $config, Database $database, Object $repository)
    {

        $this->view = $view;
        $this->config = $config;
        $this->database = $database;
        $this->repository = $repository;
    }

    public function homeAction(Request $request): Response
    {
        if (!empty($request->request()->all())) {
            $mail = new Mailer($this->config->getConfig()->host, $this->config->getConfig()->username, $this->config->getConfig()->password);
            $mail->send($request->request()->all());
        }
        return new Response($this->view->renderFront([
            'template' => 'home',
            'data' => [],
        ]));
    }

    public function administrationAction(): Response
    {
        $counter = new Counter($this->database);
        $counter->countItems();

        return new Response($this->view->renderBack([
            'template' => 'home',
            'data' => [
                'nbrItems' => $counter
            ],
        ]));
    }
    public function postAdminAction(): Response
    {
        $posts = $this->repository->findAll();

        return new Response($this->view->renderBack([
            'template' => 'posts',
            'data' => ['posts' => $posts],
        ]));
    }
    public function postCommentAction(): Response
    {
        $comments = $this->repository->findAll();

        return new Response($this->view->renderBack([
            'template' => 'comment',
            'data' => ['comments' => $comments],
        ]));
    }
    public function userAction(): Response
    {
        $users = $this->repository->findAll();

        return new Response($this->view->renderBack([
            'template' => 'user',
            'data' => ['users' => $users],
        ]));
    }
}
