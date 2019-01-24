<?php

namespace App\Form\FormHandler;

use App\Entity\User;
use OCFram\Application;
use OCFram\Form\Form;
use OCFram\Form\FormHandler\FormHandler;

class RegisterFormHandler extends FormHandler {

    public static function handle(Form $form, Application $app) {

        if ($app->getHTTPRequest()->method() != 'POST') {
            return false;
        }

        // Check password confirmation
        $passwordAndConfirmationIdentical = false;
        $password = $form->getField('password')->getValue();
        $passwordConfirm = $form->getField('passwordConfirm')->getValue();

        if ($passwordConfirm == $password) {
            $passwordAndConfirmationIdentical = true;
        } else {
            $form->getField('passwordConfirm')->setErrorMessage('Votre mot de passe et sa confirmation ne sont pas identiques.');
        }

        if ($form->isValid() && $passwordAndConfirmationIdentical) {

            $user = new User();

            $user->setUsername($form->getField('username')->getValue());
            $user->setEmail($form->getField('email')->getValue());
            $user->setPlainPassword($form->getField('password')->getValue());

            $encodedPassword = $user->encodePassword($user->getPlainPassword());
            $user->setPassword($encodedPassword);

            $app->getUser()->setFlash(['type' => 'success',
                'message' => 'Merci ! Votre inscription a bien été prise en compte. Vous pouvez dès à présent vous connecter.']);

            $app->getEntityManager()->getRepository('User')->save($user, ['username', 'email', 'password', 'roles']);

            return true;
        }

        return false;

    }
}