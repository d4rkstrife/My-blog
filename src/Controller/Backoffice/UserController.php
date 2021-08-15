<?php

declare(strict_types=1);

namespace  App\Controller\Backoffice;

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

    public function __construct(UserRepository $userRepository, View $view, Session $session)
    {
        $this->userRepository = $userRepository;
        $this->view = $view;
        $this->session = $session;
    }

    public function userAction(Request $request): Response
    {
        if (
            $this->session->get('user') !== null
            && ($this->session->get('user')->getGrade() === 'superAdmin' || $this->session->get('user')->getGrade() === 'admin')
        ) {
            //si le bouton supprimer a été cliqué
            if (!empty($request->request()->all())) {
                $post = (object) $request->request()->all();
                $criteria = array(
                    'email' => $post->delete
                );
                $user = $this->userRepository->findOneBy($criteria);
                $this->userRepository->delete($user);
            }

            $users = $this->userRepository->findAll();

            return new Response($this->view->render([
                'template' => 'user',
                'data' => ['users' => $users],
            ], 'Backoffice'), 200);
        }
        return new Response('', 304, ['redirect' => 'home']);
    }

    public function userAccountAction(Request $request): Response
    {
        $user = $this->userRepository->findOneBy(array('email' => $request->request()->get('mail')));

        if ($request->request()->get('grade')) {
            $user->setGrade($request->request()->get('grade'));
            $this->userRepository->update($user) ? $this->session->addFlashes('success', 'Grade mis à jour') : $this->session->addFlashes('error', 'Impossible de mettre à jour');
        }

        return new Response($this->view->render([
            'template' => 'userAccount',
            'data' => ['user' => $user],
        ], 'Backoffice'), 200);
    }
}
