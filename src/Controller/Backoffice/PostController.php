<?php

declare(strict_types=1);

namespace  App\Controller\Backoffice;

use App\View\View;
use App\Model\Entity\Post;
use App\Service\Http\Request;
use App\Service\Http\Response;
use App\Service\DataValidation;
use App\Service\Http\Session\Session;
use App\Model\Repository\PostRepository;
use App\Model\Repository\UserRepository;
use App\Service\TokenProtection;

final class PostController
{
    private PostRepository $postRepository;
    private UserRepository $userRepository;
    private View $view;
    private Session $session;
    private DataValidation $validator;

    public function __construct(PostRepository $postRepository, UserRepository $userRepository, View $view, Session $session, DataValidation $validator)
    {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->view = $view;
        $this->session = $session;
        $this->validator = $validator;
    }

    public function postAdminAction(Request $request): Response
    {
        if (
            $this->session->get('user') !== null
            && ($this->session->get('user')->getGrade() === 'superAdmin' || $this->session->get('user')->getGrade() === 'admin')
        ) {
            //si un des boutons a été cliqué
            if (!empty($request->request()->all())) {
                $action = (object) $request->request()->all();

                //bouton supprimer
                if (isset($action->delete)) {
                    $criteria = array(
                        'id' => $action->delete
                    );
                    $post = $this->postRepository->findOneBy($criteria);
                    $this->postRepository->delete($post) ? $this->session->addFlashes('success', 'Post supprimé.') : $this->session->addFlashes('error', 'Suppression impossible.');

                    //bouton modifier
                }
            }
            $posts = $this->postRepository->findAll();

            return new Response($this->view->render([
                'template' => 'posts',
                'data' => ['posts' => $posts],
            ], 'Backoffice'));
        }
        return new Response('', 303, ['redirect' => 'unauthorized']);
    }

    public function updateAction(Request $request, TokenProtection $token): Response
    {
        $action = (object) $request->request()->all();
        $post = '';
        $users = $this->userRepository->findBy(array('grade1' => 'admin', 'grade2' => 'superAdmin'));

        if (isset($action->modif)) {
            $criteria = array(
                'id' => $action->modif
            );

            $post = $this->postRepository->findOneBy($criteria);
        } elseif (!isset($action->modif)) {
            $autor = $this->validator->validate($action->autor);
            $title = $this->validator->validate($action->title);
            $chapo = $this->validator->validate($action->chapo);
            $content = $this->validator->validate($action->content);

            $user = $this->userRepository->findOneBy(['pseudo' => $autor]);
            $post = new Post();
            $post->setId((int) $action->id)
                ->setTitle($title)
                ->setChapo($chapo)
                ->setContent($content);

            if ($title === '' || $chapo === '' || $content === '') {
                $this->session->addFlashes('error', 'Tous les champs doivent être remplis.');
                $post->setAutor($user);
            } elseif ($user) {
                if ($request->request()->get('token') === $this->session->get('token')) {
                    $post->setAutor($user);
                    $this->postRepository->update($post) ? $this->session->addFlashes('success', 'Post mis à jour') : $this->session->addFlashes('error', 'Mise à jour du post impossible.');
                    return new Response('', 303, ['redirect' => 'postsAdmin']);
                } elseif ($request->request()->get('token') !== $this->session->get('token')) {
                    $this->session->addFlashes('error', "Impossible de modifier le post.");
                    $post->setAutor($user);
                }
            } elseif (!$user) {
                $post->setAutor($this->session->get('user'));
                $this->session->addFlashes('error', "Cet utilisateur n'existe pas.");
            }
        }

        $token->generateToken();

        return new Response($this->view->render([
            'template' => 'updatePost',
            'data' => ['post' => $post, 'users' => $users, 'token' => $token->getToken()],
        ], 'Backoffice'));
    }

    public function newPostAction(Request $request, TokenProtection $token): Response
    {
        if (
            $this->session->get('user') !== null
            && ($this->session->get('user')->getGrade() === 'superAdmin' || $this->session->get('user')->getGrade() === 'admin')
        ) {
            $newPost = new Post();
            $newPost
                ->setTitle('')
                ->setChapo('')
                ->setContent('');

            if ($request->getMethod() === 'POST') {
                $data = $request->request();
                $title = $this->validator->validate($data->get('title'));
                $chapo = $this->validator->validate($data->get('chapo'));
                $content = $this->validator->validate($data->get('content'));

                $newPost
                    ->setTitle($title)
                    ->setChapo($chapo)
                    ->setContent($content)
                    ->setAutor($this->session->get('user'));

                if ($request->request()->get('token') === $this->session->get('token')) {
                    if ($title === '' || $chapo === '' || $content === '') {
                        $this->session->addFlashes('error', 'Tous les champs doivent être remplis');
                    } elseif ($title !== '' || $chapo !== '' || $content !== '') {
                        $this->postRepository->create($newPost) ? $this->session->addFlashes('success', 'Post enregistré avec succès') : $this->session->addFlashes('Error', "Le Post n' a pas pu être enregistré.");
                        return new Response('', 303, ['redirect' => 'postsAdmin']);
                    }
                } elseif ($request->request()->get('token') !== $this->session->get('token')) {
                    $this->session->addFlashes('error', 'Impossible de créer le post');
                }
            }

            $token->generateToken();

            return new Response($this->view->render([
                'template' => 'newPost',
                'data' => [
                    'post' => $newPost,
                    'token' => $token->getToken()
                ],
            ], 'Backoffice'));
        }
        return new Response('', 304, ['redirect' => 'unauthorized']);
    }
}
