<?php

declare(strict_types=1);

namespace  App\Controller\Backoffice;

use App\View\View;
use App\Service\Database;
use App\Service\ParseConfig;
use App\Service\Http\Request;
use App\Service\Http\Response;
use App\Model\Repository\PostRepository;
use App\Model\Repository\UserRepository;
use App\Model\Repository\CommentRepository;

final class HomeController
{
    private View $view;
    private Database $database;
    private ParseConfig $config;

    public function __construct(View $view, ParseConfig $config, Database $database)
    {
        $this->view = $view;
        $this->config = $config;
        $this->database = $database;
    }


    public function administrationAction(): Response
    {
        $postRepository = new PostRepository($this->database);
        $commentRepository = new CommentRepository($this->database);
        $userRepository = new UserRepository($this->database);

        return new Response($this->view->render([
            'template' => 'home',
            'data' => [
                'nbrPost' => $postRepository->count(),
                'nbrUser' => $userRepository->count(),
                'nbrComment' => $commentRepository->count(),
            ],
        ], 'Backoffice'));
    }
}
