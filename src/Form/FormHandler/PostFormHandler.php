<?php

namespace App\Form\FormHandler;

use App\Entity\Comment;
use OCFram\Application;
use OCFram\Form\Form;
use OCFram\Form\FormHandler\FormHandler;
use OCFram\HTTPComponents\HTTPRequest;

class PostFormHandler extends FormHandler {

    public static function handle(Form $form, Application $app, $edit = false) {

        if ($app->getHTTPRequest()->method() != "POST") {
            return;
        }

        $post = $form->getEntity();

        if ($form->isValid()) {

            $post->setTitle($form->getField('title')->getValue());
            $post->setHeading($form->getField('heading')->getValue());
            $post->setContent($form->getField('content')->getValue());
            $post->setAuthorId($app->getUser()->getId());

            $app->getEntityManager()->getRepository('Post')->save($post, $edit);

            if ($edit === true) {
                $successMessage = "Merci ! Votre article a bien été modifié.";
            } else {
                $successMessage = "Votre article a bien été pris en crée, merci !
                 Il est désormais visible sur la page d'accueil du blog.";
            }
            $app->getUser()->setFlash(
                ['type' => 'success',
                    'message' => $successMessage]);

            return true;
        }

        return false;

    }
}