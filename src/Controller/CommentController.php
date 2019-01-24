<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\FormBuilder\CommentFormBuilder;
use App\Form\FormBuilder\PostFormBuilder;
use App\Form\FormHandler\CommentFormHandler;
use App\Form\FormHandler\PostFormHandler;
use OCFram\Controller\Controller;
use OCFram\Exception\NotFoundHTTPException;
use OCFram\HTTPComponents\HTTPRequest;

class CommentController extends Controller {

    public function executeShowNotValidatedCommentList(HTTPRequest $request)
    {

        $em = $this->getEntityManager();

        $comments = $em->getRepository('Comment')->findNotValidated();

        return $this->render('posts/comments/notValidatedList.html.twig', [
            'comments' => $comments,
            'user' => $this->getApp()->getUser(),
            'flashMessage' => $this->getApp()->getUser()->getFlash()]);
    }

    // TODO : Gérer echec validation
    public function executeValidateComment(HTTPRequest $request)
    {

        $em = $this->getEntityManager();
        $commentId = (int) $request->getData('comment_id');
        $comment = $em->getRepository('Comment')->findOneById($commentId);

        if ($comment == false) {
            throw new NotFoundHTTPException();
        }

        $validateSuccess = $em->getRepository('Comment')->validate($comment);

        if ($validateSuccess) {

            $this->getApp()->getUser()->setFlash(['type' => 'success',
                'message' => 'Merci ! Le commentaire a bien été validé et sera désormais visible par tous']);
            $this->getApp()->getHTTPResponse()->redirect('/admin/comments/validation');
        }

    }


}