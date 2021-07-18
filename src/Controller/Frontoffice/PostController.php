<?php

declare(strict_types=1);

namespace  App\Controller\Frontoffice;

use App\View\View;
use App\Model\Entity\User;
use App\Model\Entity\Comment;
use App\Service\Http\Response;
use App\Service\DataValidation;
use App\Model\Repository\PostRepository;
use App\Model\Repository\CommentRepository;

final class PostController
{
    private PostRepository $postRepository;
    private View $view;

    public function __construct(PostRepository $postRepository, View $view )
    {
        $this->postRepository = $postRepository;
        $this->view = $view;
    }

    public function displayOneAction(object $request, CommentRepository $commentRepository, ?User $user): Response
    {
        $id = $request->query()->get('id');
        $post = $this->postRepository->findOneBy(['id' => $id]);
        $comments = $commentRepository->findBy(['idPost' => $id, 'state' => 1]);
        $response = new Response('<h1>faire une redirection vers la page d\'erreur, ce post n\'existe pas</h1><a href="index.php?action=posts">Liste des posts</a><br>', 404);
        
        if ($request->request()->has('comment')) {
            $content = $request->request()->get('comment');
            if($content != ''){
                $newComment = new Comment ();
                $validation = new DataValidation;
                $newUser = new User();
                $newUser->setId($user->getId());
                $newComment
                ->setText($validation->validate($content))
                ->setIdPost($id)
                ->setUser($newUser);
                
                $commentRepository->create($newComment);
            }            
        }

        if ($post !== null) {
            $response = new Response($this->view->renderFront(
                [
                    'template' => 'post',
                    'data' => [
                        'post' => $post,
                        'comments' => $comments,
                    ],
                ],
            ));
        }

        return $response;
    }

    public function displayAllAction(): Response
    {
        $posts = $this->postRepository->findAll();

        return new Response($this->view->renderFront([
            'template' => 'posts',
            'data' => ['posts' => $posts],
        ]));
    }
}
