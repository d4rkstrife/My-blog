<?php

declare(strict_types=1);

namespace  App\Controller\Frontoffice;

use App\View\View;
use App\Model\Entity\User;
use App\Service\Pagination;
use App\Model\Entity\Comment;
use App\Service\Http\Request;
use App\Service\Http\Response;
use App\Service\DataValidation;
use App\Service\Http\Session\Session;
use App\Model\Repository\PostRepository;
use App\Model\Repository\CommentRepository;

final class PostController
{
    private PostRepository $postRepository;
    private View $view;
    private Session $session;
    private Datavalidation $validator;

    public function __construct(PostRepository $postRepository, View $view, Session $session, DataValidation $validator)
    {
        $this->postRepository = $postRepository;
        $this->view = $view;
        $this->session = $session;
        $this->validator = $validator;
    }

    public function displayOneAction(Request $request, CommentRepository $commentRepository, ?User $user): Response
    {
        $id = $request->query()->get('id');
        $post = $this->postRepository->findOneBy(['id' => $id]);
        $comments = $commentRepository->findBy(['idPost' => $id, 'state' => 1]);
        $response = new Response($this->view->render([
            'template' => 'unauthorized',
            'data' => [],
        ], 'Frontoffice'), 404);

        if (($post !== null) && ($request->request()->has('comment'))) {
            $content = $request->request()->get('comment');
            if ($content != '') {
                $newComment = new Comment();
                $newUser = new User();
                $newUser->setId($user->getId());
                $newComment
                    ->setText($this->validator->validate($content))
                    ->setIdPost((int) $id)
                    ->setUser($newUser);

                $commentRepository->create($newComment) ? $this->session->addFlashes('success', 'Commentaire en attente de validation') : $this->session->addFlashes('error', 'Enregistrement du commentaire impossible');
            }
        }

        if ($post !== null) {
            $response = new Response($this->view->render(
                [
                    'template' => 'post',
                    'data' => [
                        'post' => $post,
                        'comments' => $comments
                    ],
                ],
                'Frontoffice'
            ));
        }

        return $response;
    }

    public function displayAllAction(Pagination $pagination, Request $request): Response
    {

        $page = (int) $request->query()->get('page');
        $infos = $pagination->render($this->postRepository->count(), $page);
        $criteria = array(
            'table' => 'post'
        );
        $limit = $infos['limit'];
        $offset = $infos['offset'];
        $order = array(
            'order' => $infos['order']
        );


        $posts = $this->postRepository->findBy($criteria, $order, $limit, $offset);

        return new Response($this->view->render([
            'template' => 'posts',
            'data' => ['posts' => $posts, 'page' => $page, 'nbrPages' => round($infos['nbrPages'])],
        ], 'Frontoffice'));
    }
}
