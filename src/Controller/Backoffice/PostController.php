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

final class PostController
{
    private PostRepository $postRepository;
    private View $view;
    private Session $session;
    private DataValidation $validator;

    public function __construct(PostRepository $postRepository, View $view, Session $session, DataValidation $validator)
    {
        $this->postRepository = $postRepository;
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
        return new Response('<head>
        <meta http-equiv="refresh" content="0; URL=index.php?action=home" />
      </head>');
    }

    public function updateAction(Request $request): Response
    {
        $action = (object) $request->request()->all();
        $post = '';
        if (isset($action->modif)) {
            $criteria = array(
                'id' => $action->modif
            );

            $post = $this->postRepository->findOneBy($criteria);
        } elseif (!isset($action->modif)) {
            $title = $this->validator->validate($action->title);
            $chapo = $this->validator->validate($action->chapo);
            $content = $this->validator->validate($action->content);
            /*   $criteria = array(
                'id' => $action->id,
                'title' => $title,
                'chapo' => $chapo,
                'content' => $content

            ); */
            $post = new Post();
            $post
                ->setId((int) $action->id)
                ->setTitle($action->title)
                ->setChapo($action->chapo)
                ->setContent($action->content);

            $this->postRepository->update($post) ? $this->session->addFlashes('success', 'Post mis à jour') : $this->session->addFlashes('error', 'Mise à jour du post impossible.');
        }


        return new Response($this->view->render([
            'template' => 'updatePost',
            'data' => ['post' => $post],
        ], 'Backoffice'));
    }

    public function newPostAction(Request $request): Response
    {
        if (
            $this->session->get('user') !== null
            && ($this->session->get('user')->getGrade() === 'superAdmin' || $this->session->get('user')->getGrade() === 'admin')
        ) {
            if (!empty($request->request()->all())) {

                $data = $request->request();
                $title = $this->validator->validate($data->get('title'));
                $chapo = $this->validator->validate($data->get('chapo'));
                $content = $this->validator->validate($data->get('content'));
                $newPost = new Post();
                $newPost
                    ->setTitle($title)
                    ->setChapo($chapo)
                    ->setContent($content)
                    ->setAutor($this->session->get('user'));

                $this->postRepository->create($newPost) ? $this->session->addFlashes('success', 'Post enregistré avec succès') : $this->session->addFlashes('Error', "Le Post n' a pas pu être enregistré.");
            }
            return new Response($this->view->render([
                'template' => 'newPost',
                'data' => [],
            ], 'Backoffice'));
        }
        return new Response('<head>
        <meta http-equiv="refresh" content="0; URL=index.php?action=home" />
      </head>');
    }
}
