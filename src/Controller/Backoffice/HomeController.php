<?php

declare(strict_types=1);

namespace  App\Controller\Backoffice;

use App\View\View;
use App\Service\Database;
use App\Service\Http\Response;
use App\Service\Http\Session\Session;
use App\Model\Repository\PostRepository;
use App\Model\Repository\UserRepository;
use App\Model\Repository\CommentRepository;

final class HomeController
{
    private View $view;
    private Database $database;
    private Session $session;

    public function __construct(View $view, Database $database, Session $session)
    {
        $this->view = $view;
        $this->database = $database;
        $this->session = $session;
    }


    public function administrationAction(): Response
    {
        //2 if pour chaque statut
        if (
            $this->session->get('user') === null
            || ($this->session->get('user')->getGrade() !== 'superAdmin'
                && $this->session->get('user')->getGrade() !== 'admin')
        ) {
            return new Response('', 303, ['redirect' => 'unauthorized']);
        }

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
        ], 'Backoffice'), 200);
    }
}
