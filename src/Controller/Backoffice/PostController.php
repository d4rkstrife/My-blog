<?php

declare(strict_types=1);

namespace  App\Controller\Backoffice;

use App\View\View;
use App\Service\Http\Response;
use App\Model\Repository\PostRepository;

final class PostController
{
    private PostRepository $postRepository;
    private View $view;

    public function __construct(PostRepository $postRepository, View $view)
    {
        $this->postRepository = $postRepository;
        $this->view = $view;
    }

    public function postAdminAction(): Response
    {
        $posts = $this->postRepository->findAll();

        return new Response($this->view->render([
            'template' => 'posts',
            'data' => ['posts' => $posts],
        ], 'Backoffice'));
    }
}
