<?php

declare(strict_types=1);

namespace  App\Service;

use App\View\View;
use App\Service\Database;
use App\Service\Http\Request;
use App\Service\Http\Response;
use App\Service\Http\Session\Session;
use App\Model\Repository\PostRepository;
use App\Model\Repository\UserRepository;
use App\Model\Repository\CommentRepository;
use App\Controller\Frontoffice\HomeController;
use App\Controller\Frontoffice\PostController;
use App\Controller\Frontoffice\UserController;

// TODO cette classe router est un exemple très basic. Cette façon de faire n'est pas optimale
// TODO Le router ne devrait pas avoir la responsabilité de l'injection des dépendances

final class Router
{
    private Database $database;
    private View $view;
    private Request $request;
    private Session $session;
    private ParseConfig $config;

    public function __construct(Request $request, ParseConfig $config)
    {
        // dépendance
        $this->config = $config;
        $this->database = new Database($this->config->getConfig()->dbHost, $this->config->getConfig()->dbName, $this->config->getConfig()->dbUser, $this->config->getConfig()->dbPass, $this->config->getConfig()->dbPort);

        $this->session = new Session();
        $this->view = new View($this->session);
        $this->request = $request;
    }

    public function run(): Response
    {
        //On test si une action a été défini ? si oui alors on récupére l'action : sinon on mets une action par défaut (ici l'action posts)
        $action = $this->request->query()->has('action') ? $this->request->query()->get('action') : 'home';

        //Déterminer sur quelle route nous sommes // Attention algorithme naïf
        // *** @Route http://localhost:8000/?action=home ***
        if ($action === 'home') {
            $postRepo = new PostRepository($this->database);
            $controller = new HomeController($this->view, $this->config, $this->database, $postRepo);
            return $controller->homeAction($this->request);

            // *** @Route http://localhost:8000/?action=administration ***
        } elseif ($action === 'administration') {
            $postRepo = new PostRepository($this->database);
            $controller = new HomeController($this->view, $this->config, $this->database, $postRepo);
            return $controller->administrationAction();

            // *** @Route http://localhost:8000/?action=postsAdmin ***
        } elseif ($action === 'postsAdmin') {
            $postRepo = new PostRepository($this->database);
            $controller = new HomeController($this->view, $this->config, $this->database, $postRepo);
            return $controller->postAdminAction();

            // *** @Route http://localhost:8000/?action=commentAdmin ***
        } elseif ($action === 'commentAdmin') {
            $commentRepo = new commentRepository($this->database);
            $controller = new HomeController($this->view, $this->config, $this->database, $commentRepo);
            return $controller->postCommentAction($this->request);

            // *** @Route http://localhost:8000/?action=userAdmin ***
        } elseif ($action === 'userAdmin') {
            $userRepo = new userRepository($this->database);
            $controller = new HomeController($this->view, $this->config, $this->database, $userRepo);
            return $controller->userAction($this->request);


            // *** @Route http://localhost:8000/?action=posts ***
        } elseif ($action === 'posts') {
            //injection des dépendances et instanciation du controller
            $postRepo = new PostRepository($this->database);
            $controller = new PostController($postRepo, $this->view);

            return $controller->displayAllAction();

            // *** @Route http://localhost:8000/?action=post&id=5 ***
        } elseif ($action === 'post' && $this->request->query()->has('id')) {
            //injection des dépendances et instanciation du controller
            $postRepo = new PostRepository($this->database);
            $controller = new PostController($postRepo, $this->view);

            $commentRepo = new CommentRepository($this->database);

            //  return $controller->displayOneAction((int) $this->request->query()->get('id'), $commentRepo);
            return $controller->displayOneAction((object) $this->request, $commentRepo, $this->session->get('user'));

            // *** @Route http://localhost:8000/?action=login ***
        } elseif ($action === 'login') {
            $userRepo = new UserRepository($this->database);
            $controller = new UserController($userRepo, $this->view, $this->session);

            return $controller->loginAction($this->request);

            // *** @Route http://localhost:8000/?action=register ***
        } elseif ($action === 'register') {
            $userRepo = new UserRepository($this->database);
            $controller = new UserController($userRepo, $this->view, $this->session);

            return $controller->registerAction($this->request);

            // *** @Route http://localhost:8000/?action=logout ***
        } elseif ($action === 'logout') {
            $userRepo = new UserRepository($this->database);
            $controller = new UserController($userRepo, $this->view, $this->session);

            return $controller->logoutAction();
        }

        return new Response("Error 404 - cette page n'existe pas<br><a href='index.php?action=home'>Aller Ici</a>", 404);
    }
}
