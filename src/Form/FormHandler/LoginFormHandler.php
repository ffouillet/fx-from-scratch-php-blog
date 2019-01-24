<?php

namespace App\Form\FormHandler;

use App\Entity\User;
use OCFram\Application;
use OCFram\Form\Form;
use OCFram\Form\FormHandler\FormHandler;

class LoginFormHandler extends FormHandler {

    public static function handle(Form $form, Application $app) {

        if ($app->getHTTPRequest()->method() == 'POST' && $form->isValid()) {

            // Check if user credentials are valid
            $username = $form->getField('username')->getValue();
            $password = $form->getField('password')->getValue();

            $user = $app->getEntityManager()->getRepository('User')->findOneByAttribute('username', $username);

            $badCredentialsMessage = 'Nom d\'utilisateur ou mot de passe incorrect';
            if (!$user) {
                $app->getUser()->setFlash(['type' => 'error', 'message' => $badCredentialsMessage]);
                return false;
            }

            $user = $user[0];

            $user = new User($user);

            // Check if password typed and user's hashed password match
            $passwordCorrect = password_verify($password, $user->getPassword());

            if ($passwordCorrect) {
                $user->setAuthenticated();
            } else {
                $app->getUser()->setFlash(['type' => 'error', 'message' => $badCredentialsMessage]);
                return false;
            }

            return true;
        }

        return false;

    }
}