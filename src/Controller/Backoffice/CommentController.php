<?php

declare(strict_types=1);

namespace  App\Controller\Backoffice;

use App\View\View;
use App\Service\Http\Request;
use App\Service\Http\Response;
use App\Model\Repository\CommentRepository;

final class CommentController
{
    private CommentRepository $commentRepository;
    private View $view;

    public function __construct(CommentRepository $commentRepository, View $view)
    {
        $this->commentRepository = $commentRepository;
        $this->view = $view;
    }

    public function postCommentAction(Request $request): Response
    {

        //si un des boutons a été cliqué
        if (!empty($request->request()->all())) {
            $post = (object) $request->request()->all();

            //bouton supprimer
            if (isset($post->delete)) {
                $criteria = array(
                    'id' => $post->delete
                );
                $comment = $this->commentRepository->findOneBy($criteria);
                $this->commentRepository->delete($comment);

                //bouton valider
            } elseif (isset($post->validate)) {
                $criteria = array(
                    'id' => $post->validate
                );
                $comment = $this->commentRepository->findOneBy($criteria);
                $this->commentRepository->update($comment);
            }
        }
        $comments = $this->commentRepository->findAll();

        return new Response($this->view->render([
            'template' => 'comment',
            'data' => ['comments' => $comments],
        ], 'Backoffice'));
    }
}
