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
            $this->session->get('user') === null
            || ($this->session->get('user')->getGrade() !== 'superAdmin')
        ) {
            return new Response('', 303, ['redirect' => 'unauthorized']);
        } elseif (
            $this->session->get('user') !== null
            && ($this->session->get('user')->getGrade() === 'superAdmin')
        ) {
            //si le bouton supprimer a été cliqué
            if ($request->getMethod() === 'POST') {
                $post = (object) $request->request()->all();
                $criteria = array(
                    'email' => $post->delete
                );
                $user = $this->userRepository->findOneBy($criteria);
                $this->userRepository->delete($user) ? $this->session->addFlashes('success', 'Utilisateur supprimé') : $this->session->addFlashes('error', 'Suppression impossible');
            }

            $users = $this->userRepository->findAll();

            return new Response($this->view->render([
                'template' => 'user',
                'data' => ['users' => $users],
            ], 'Backoffice'), 200);
        }
    }

    public function userAccountAction(Request $request): Response
    {
        if (
            ($this->session->get('user') !== null
            && ($this->session->get('user')->getGrade() === 'superAdmin'))
        ) {
            $user = $this->userRepository->findOneBy(array('email' => $request->request()->get('mail')));

            if ($request->request()->get('grade')) {
                $user->setGrade($request->request()->get('grade'));
                $this->userRepository->update($user) ? $this->session->addFlashes('success', 'Grade mis à jour') : $this->session->addFlashes('error', 'Impossible de mettre à jour');
                return new Response('', 303, ['redirect' => 'userAdmin']);
            }

            return new Response($this->view->render([
                'template' => 'userAccount',
                'data' => ['user' => $user],
            ], 'Backoffice'), 200);
        } elseif (
            ($this->session->get('user') === null
                || ($this->session->get('user')->getGrade() !== 'superAdmin'))
            && ($request->getMethod() !== 'POST')
        ) {
            return new Response('', 303, ['redirect' => 'unauthorized']);
        }
    }
}
