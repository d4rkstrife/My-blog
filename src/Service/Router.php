<?php

declare(strict_types=1);

namespace  App\Service;

use App\View\View;
use App\Service\Mailer;
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
use App\Controller\Backoffice\CommentController;
use App\Controller\Backoffice\HomeController as BackofficeHomeController;
use App\Controller\Backoffice\PostController as BackofficePostController;
use App\Controller\Backoffice\UserController as BackofficeUserController;

// TODO cette classe router est un exemple très basic. Cette façon de faire n'est pas optimale

final class Router
{
    private Database $database;
    private View $view;
    private Request $request;
    private Session $session;
    private ParseConfig $config;
    private DataValidation $validator;
    private Mailer $mailer;

    public function __construct(Request $request, ParseConfig $config)
    {
        // dépendance
        $this->config = $config;
        $this->database = new Database($this->config->getConfig('dbHost'), $this->config->getConfig('dbName'), $this->config->getConfig('dbUser'), $this->config->getConfig('dbPass'), $this->config->getConfig('dbPort'));
        $this->mailer = new Mailer($this->config->getConfig('host'), $this->config->getConfig('username'), $this->config->getConfig('password'));
        $this->session = new Session();
        $this->view = new View($this->session);
        $this->validator = new DataValidation();
        $this->request = $request;
    }

    public function run(): Response
    {
        //On test si une action a été défini ? si oui alors on récupére l'action : sinon on mets une action par défaut (ici l'action posts)
        $action = $this->request->query()->has('action') ? $this->request->query()->get('action') : 'home';

        //Déterminer sur quelle route nous sommes // Attention algorithme naïf
        // *** @Route http://localhost:8000/?action=home ***
        if ($action === 'home') {
            $controller = new HomeController($this->view, $this->mailer, $this->session, $this->validator);
            return $controller->homeAction($this->request);

            // *** @Route http://localhost:8000/?action=administration ***
        } elseif ($action === 'administration') {
            $postRepo = new PostRepository($this->database);
            $controller = new BackofficeHomeController($this->view, $this->database, $this->session);
            return $controller->administrationAction();

            // *** @Route http://localhost:8000/?action=postsAdmin ***
        } elseif ($action === 'postsAdmin') {
            $postRepo = new PostRepository($this->database);
            $controller = new BackofficePostController($postRepo, $this->view, $this->session);
            return $controller->postAdminAction();


            // *** @Route http://localhost:8000/?action=commentAdmin ***
        } elseif ($action === 'commentAdmin') {
            $commentRepo = new commentRepository($this->database);
            $controller = new CommentController($commentRepo, $this->view, $this->session);
            return $controller->postCommentAction($this->request);


            // *** @Route http://localhost:8000/?action=userAdmin ***
        } elseif ($action === 'userAdmin') {
            $userRepo = new userRepository($this->database);
            $controller = new BackofficeUserController($userRepo, $this->view, $this->session);
            return $controller->userAction($this->request);


            // *** @Route http://localhost:8000/?action=posts ***
        } elseif ($action === 'posts') {
            //injection des dépendances et instanciation du controller
            $postRepo = new PostRepository($this->database);
            $controller = new PostController($postRepo, $this->view, $this->session);

            return $controller->displayAllAction();

            // *** @Route http://localhost:8000/?action=post&id=5 ***
        } elseif ($action === 'post' && $this->request->query()->has('id')) {
            //injection des dépendances et instanciation du controller
            $postRepo = new PostRepository($this->database);
            $controller = new PostController($postRepo, $this->view, $this->session);
            $commentRepo = new CommentRepository($this->database);

            return $controller->displayOneAction((object) $this->request, $commentRepo, $this->session->get('user'));

            // *** @Route http://localhost:8000/?action=login ***
        } elseif ($action === 'login') {
            $userRepo = new UserRepository($this->database);
            $controller = new UserController($userRepo, $this->view, $this->session, $this->validator, $this->mailer);

            return $controller->loginAction($this->request);

            // *** @Route http://localhost:8000/?action=register ***
        } elseif ($action === 'register') {
            $userRepo = new UserRepository($this->database);
            $controller = new UserController($userRepo, $this->view, $this->session, $this->validator, $this->mailer);

            return $controller->registerAction($this->request);

            // *** @Route http://localhost:8000/?action=logout ***
        } elseif ($action === 'logout') {
            $userRepo = new UserRepository($this->database);
            $controller = new UserController($userRepo, $this->view, $this->session, $this->validator, $this->mailer);

            return $controller->logoutAction();

            // *** @Route http://localhost:8000/?action=validation ***
        } elseif ($action === 'validation') {
            $userRepo = new UserRepository($this->database);
            $controller = new UserController($userRepo, $this->view, $this->session, $this->validator, $this->mailer);

            return $controller->validationAction($this->request);
        } elseif ($action === 'newPost') {
            $postRepo = new PostRepository($this->database);
            $controller = new BackofficePostController($postRepo, $this->view, $this->session);

            return $controller->newPostAction($this->request);
        }

        return new Response("Error 404 - cette page n'existe pas<br><a href='index.php?action=home'>Aller Ici</a>", 404);
    }
}
