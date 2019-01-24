<?php

namespace App\Form\FormHandler;

use App\Entity\Comment;
use OCFram\Application;
use OCFram\Form\Form;
use OCFram\Form\FormHandler\FormHandler;
use OCFram\HTTPComponents\HTTPRequest;

class CommentFormHandler extends FormHandler {

    public static function handle(Form $form, Application $app) {

        if ($app->getHTTPRequest()->method() != "POST") {
            return;
        }

        $returnValues = array('comment' => null, 'form_submission_success' => false);

        $comment = $form->getEntity();

        if ($form->isValid()) {

            $comment->setPostId($app->getHTTPRequest()->getData('post_id'));
            $comment->setContent($form->getField('content')->getValue());

            $app->getEntityManager()->getRepository('Comment')->save($comment, ['postId', 'content', 'createdAt']);

            $app->getUser()->setFlash(
                ['type' => 'success',
                    'message' => 'Votre commentaire a bien été pris en compte, merci !
                 Il sera soumis à une validation avant affichage.']);

            $returnValues['form_submission_success'] = true;
        }

        return $returnValues;

    }
}