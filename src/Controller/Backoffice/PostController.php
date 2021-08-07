<?php

declare(strict_types=1);

namespace  App\Controller\Backoffice;

use App\View\View;
use App\Model\Entity\Post;
use App\Service\Http\Request;
use App\Service\Http\Response;
use App\Service\Http\Session\Session;
use App\Model\Repository\PostRepository;

final class PostController
{
    private PostRepository $postRepository;
    private View $view;
    private Session $session;

    public function __construct(PostRepository $postRepository, View $view, Session $session)
    {
        $this->postRepository = $postRepository;
        $this->view = $view;
        $this->session = $session;
    }

    public function postAdminAction(): Response
    {
        if (
            $this->session->get('user') !== null
            && ($this->session->get('user')->getGrade() === 'superAdmin' || $this->session->get('user')->getGrade() === 'admin')
        ) {
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

    public function newPostAction(Request $request): Response
    {
        if (
            $this->session->get('user') !== null
            && ($this->session->get('user')->getGrade() === 'superAdmin' || $this->session->get('user')->getGrade() === 'admin')
        ) {
            if (!empty($request->request()->all())) {

                $data = $request->request();
                $newPost = new Post();
                $newPost
                    ->setTitle($data->get('title'))
                    ->setChapo($data->get('chapo'))
                    ->setContent($data->get('content'))
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
