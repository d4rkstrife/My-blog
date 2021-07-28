<?php

declare(strict_types=1);

namespace  App\Controller\Frontoffice;

use App\View\View;
use App\Model\Entity\User;
use App\Service\Http\Request;
use App\Service\Http\Response;
use App\Service\DataValidation;
use App\Service\Http\Session\Session;
use App\Model\Repository\UserRepository;

final class UserController
{
    private UserRepository $userRepository;
    private View $view;
    private Session $session;
    private DataValidation $validator;

    public function __construct(UserRepository $userRepository, View $view, Session $session, DataValidation $validator)
    {
        $this->userRepository = $userRepository;
        $this->view = $view;
        $this->session = $session;
        $this->validator = $validator;
    }

    private function isValidLoginForm(?array $infoUser): bool
    {
        if ($infoUser === null) {
            return false;
        }
        $mail = $this->validator->validate($infoUser['email']);
        $password = $this->validator->validate($infoUser['password']);

        $user = $this->userRepository->findOneBy(['email' => $mail]);
        if ($user === null || !password_verify($password, $user->getPassword()) || $user->getState() != 1) {
            return false;
        }
        $this->session->set('user', $user);

        return true;
    }



    public function loginAction(Request $request): Response
    {
        if ($request->getMethod() === 'POST') {
            if ($this->isValidLoginForm($request->request()->all())) {
                return new Response($this->view->render(['template' => 'home', 'data' => []], 'Frontoffice'), 200);
            }
            $this->session->addFlashes('error', 'Mauvais identifiants ou compte non validé');
        }
        return new Response($this->view->render(['template' => 'login', 'data' => []], 'Frontoffice'), 200);
    }

    public function logoutAction(): Response
    {
        $this->session->remove('user');
        return new Response($this->view->render(['template' => 'home', 'data' => []], 'Frontoffice'), 200);
    }

    public function registerAction(Request $request): Response
    {
        if ($request->getMethod() === 'POST') {
            //validation ici

            $infoUser = $request->request();

            $name = $this->validator->validate($infoUser->get('nom'));
            $surname = $this->validator->validate($infoUser->get('prenom'));
            $pseudo = $this->validator->validate($infoUser->get('pseudo'));
            $mail = $this->validator->validate($infoUser->get('email'));
            $password = $this->validator->validate($infoUser->get('password'));
            $repassword = $this->validator->validate($infoUser->get('repassword'));
            $mailExist = $this->userRepository->count(['email' => $mail]);
            $pseudoExist = $this->userRepository->count(['pseudo' => $pseudo]);

            if ($password !== $repassword) {
                $this->session->addFlashes('error', 'Les mots de passe ne correspondent pas.');
            } elseif ($mailExist !== 0) {
                $this->session->addFlashes('error', "L'adresse email est déjà utilisée.");
            } elseif ($pseudoExist !== 0) {
                $this->session->addFlashes('error', "Le pseudo est déjà utilisé.");
            } elseif (!$this->validator->isValidEntry($name)) {
                $this->session->addFlashes('error', 'Nom invalide : caractères spéciaux interdits.');
            } elseif (!$this->validator->isValidEntry($surname)) {
                $this->session->addFlashes('error', 'Prénom invalide : caractères spéciaux interdits.');
            } elseif (!$this->validator->isValidMail($mail)) {
                $this->session->addFlashes('error', 'Mail non valide.');
            } else {

                $user = new User();
                $user
                    ->setName($name)
                    ->setSurname($surname)
                    ->setEmail($mail)
                    ->setPseudo($pseudo)
                    ->setPassword($password);
                $this->userRepository->create($user);
                $this->session->addFlashes('success', 'Compte créé,connectez vous');
            }
        }
        return new Response($this->view->render([
            'template' => 'register',
            'data' => []
        ], 'Frontoffice'), 200);
    }
}
