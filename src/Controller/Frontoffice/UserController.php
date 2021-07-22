<?php

declare(strict_types=1);

namespace  App\Controller\Frontoffice;

use App\View\View;
use App\Service\Http\Request;
use App\Service\Http\Response;
use App\Service\Http\Session\Session;
use App\Model\Repository\UserRepository;

final class UserController
{
    private UserRepository $userRepository;
    private View $view;
    private Session $session;

    // TODO => ne doit pas resté dans le controller, voir comment on peut en faire
    // un service générique de validation
    private function isValidLoginForm(?array $infoUser): bool
    {
        if ($infoUser === null) {
            return false;
        }

        $user = $this->userRepository->findOneBy(['email' => $infoUser['email']]);
        if ($user === null || !password_verify($infoUser['password'], $user->getPassword()) || $user->getState() != 1) {
            return false;
        }
        $this->session->set('user', $user);

        return true;
    }

    public function __construct(UserRepository $userRepository, View $view, Session $session)
    {
        $this->userRepository = $userRepository;
        $this->view = $view;
        $this->session = $session;
    }

    public function loginAction(Request $request): Response
    {
        if ($request->getMethod() === 'POST') {

            if ($this->isValidLoginForm($request->request()->all())) {

                return new Response($this->view->renderFront(['template' => 'home', 'data' => []]), 200);
            }
            $this->session->addFlashes('error', 'Mauvais identifiants ou compte non validé');
        }
        return new Response($this->view->renderFront(['template' => 'login', 'data' => []]));
    }

    public function logoutAction(): Response
    {
        $this->session->remove('user');
        return new Response($this->view->renderFront(['template' => 'home', 'data' => []]), 200);
    }

    public function registerAction(Request $request): Response
    {
        if ($request->getMethod() === 'POST') {
            $this->userRepository->create($request->request());
            $this->session->addFlashes('success', 'Compte créé,connectez vous');
        }
        return new Response($this->view->renderFront([
            'template' => 'register',
            'data' => [],
        ]));
    }
}
