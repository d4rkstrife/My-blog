<?php

declare(strict_types=1);

namespace  App\Controller\Frontoffice;

use App\View\View;
use App\Service\Mailer;
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
    private Mailer $mailer;

    public function __construct(UserRepository $userRepository, View $view, Session $session, DataValidation $validator, Mailer $mailer)
    {
        $this->userRepository = $userRepository;
        $this->view = $view;
        $this->session = $session;
        $this->validator = $validator;
        $this->mailer = $mailer;
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
                return new Response('<head>
                <meta http-equiv="refresh" content="0; URL=index.php?action=home" />
              </head>');
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
            $pseudo = $this->validator->validate($infoUser->get('pseudo'));
            $mail = $this->validator->validate($infoUser->get('email'));
            $mailExist = $this->userRepository->count(['email' => $mail]);
            $pseudoExist = $this->userRepository->count(['pseudo' => $pseudo]);

            if ($mailExist) {
                $this->session->addFlashes('error', 'Cette adresse mail est déjà utilisée');
            } elseif ($pseudoExist) {
                $this->session->addFlashes('error', 'Ce pseudo est déjà utilisé');
            } elseif (!$pseudoExist && !$mailExist) {
                $name = $this->validator->validate($infoUser->get('nom'));
                $surname = $this->validator->validate($infoUser->get('prenom'));
                $password = $this->validator->validate($infoUser->get('password'));
                $repassword = $this->validator->validate($infoUser->get('repassword'));

                $error = false;
                $flashes = '';

                if (!$this->validator->isValidPassword($password)) {
                    $flashes .= 'Le mot de passe doit contenir huit caractères, au moins une majuscule, une minuscule et un chiffre.';
                    $error = true;
                }

                if ($password !== $repassword) {
                    $flashes .= 'Les mots de passe ne correspondent pas.';
                    $error = true;
                }

                if (!$this->validator->isValidEntry($name)) {
                    $flashes .= 'Nom invalide : caractères spéciaux interdits. ';
                    $error = true;
                }

                if (!$this->validator->isValidEntry($surname)) {
                    $flashes .= 'Prénom invalide : caractères spéciaux interdits. ';
                    $error = true;
                }

                if (!$this->validator->isValidMail($mail)) {
                    $flashes .= 'Mail non valide.';
                    $error = true;
                }

                if (!$name || !$surname || !$pseudo || !$password || !$repassword || !$mail) {
                    $flashes .=  'Remplir tous les champs.';
                    $error = true;
                }

                if ($error === true) {
                    $this->session->addFlashes('error', $flashes);
                    return new Response($this->view->render([
                        'template' => 'register',
                        'data' => [
                            'mail' => $mail,
                            'name' => $name,
                            'surname' => $surname,
                            'pseudo' => $pseudo
                        ]
                    ], 'Frontoffice'), 200);
                }

                $user = new User();
                $registrationKey = md5((string) time());

                $user
                    ->setName($name)
                    ->setSurname($surname)
                    ->setEmail($mail)
                    ->setPseudo($pseudo)
                    ->setPassword($password)
                    ->setRegistrationKey($registrationKey);

                $isCreated = $this->userRepository->create($user);
                if ($isCreated) {
                    $newUser = $this->userRepository->findOneBy(['email' => $mail]);
                    $this->mailer->sendConfirmationMessage($newUser);
                    $this->session->addFlashes('success', 'Compte créé, Cliquez sur le lien dans vos mails pour valider votre compte.');

                    return new Response($this->view->render([
                        'template' => 'login',
                        'data' => []
                    ], 'Frontoffice'), 200);
                } elseif (!$isCreated) {
                    $this->session->addFlashes('error', "Le compte n'a pas pu être créé");
                }
            }
        }
        return new Response($this->view->render([
            'template' => 'register',
            'data' => []
        ], 'Frontoffice'), 200);
    }

    public function validationAction(Request $request): Response
    {
        $infos = (array) $request->query()->all();
        $user = $this->userRepository->find((int) $infos['id']);
        //verifier clé ici
        $this->userRepository->update($user);
        $this->session->addFlashes('success', 'Compte validé, connectez-vous');

        return new Response('<head>
        <meta http-equiv="refresh" content="0; URL=index.php?action=login" />
      </head>');
    }
}
