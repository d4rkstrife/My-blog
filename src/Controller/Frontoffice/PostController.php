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
use App\Service\TokenProtection;

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

    public function displayOneAction(Request $request, CommentRepository $commentRepository, ?User $user, TokenProtection $token): Response
    {

        $id = $request->query()->get('id');
        $post = $this->postRepository->findOneBy(['id' => $id]);
        $comments = $commentRepository->findBy(['idPost' => $id, 'state' => 1]);
        if ($post === null) {
            return new Response('', 303, ['redirect' => 'home']);
        }

        if ($request->getMethod() === 'POST') {
            if ($request->request()->get('token') === $this->session->get('token')) {
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
            } elseif ($request->request()->get('token') !== $this->session->get('token')) {
                $this->session->addFlashes('error', 'Ajout du commentaire impossible.');
            }
        }

        $token->generateToken();

        $response = new Response($this->view->render(
            [
                'template' => 'post',
                'data' => [
                    'post' => $post,
                    'comments' => $comments,
                    'token' => $token->getToken()
                ],
            ],
            'Frontoffice'
        ));


        return $response;
    }

    public function displayAllAction(Pagination $pagination, Request $request): Response
    {

        $page = (int) $request->query()->get('page');
        $infos = $pagination->render($this->postRepository->count(), $page);

        $posts = $this->postRepository->findBy([], ['order' => 'post.date'], $infos['limit'], $infos['offset']);

        return new Response($this->view->render([
            'template' => 'posts',
            'data' => ['posts' => $posts, 'page' => $infos['page'], 'nbrPages' => $infos['nbrPages']],
        ], 'Frontoffice'));
    }
}
