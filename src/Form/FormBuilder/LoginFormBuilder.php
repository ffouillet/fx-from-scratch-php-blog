<?php

namespace App\Form\FormBuilder;

use OCFram\Form\Field\PasswordField;
use OCFram\Form\Field\StringField;
use OCFram\Form\FormBuilder\FormBuilder;
use OCFram\HTTPComponents\HTTPRequest;
use OCFram\Validator\NotNullValidator;

class LoginFormBuilder extends FormBuilder
{

    public function build()
    {
        $this->form->addField(new StringField([
            'label' => 'Nom d\'utilisateur',
            'name' => 'username',
            'validators' => [
                new NotNullValidator('Merci de spécifier votre nom d\'utilisateur')
            ],
        ]))
        ->addField(new PasswordField([
            'label' => 'Mot de passe',
            'name' => 'password',
            'validators' => [
                new NotNullValidator('Merci de spécifier votre mot de passe')
            ],
        ]));

    }

    public function getFormDatasFromRequest(HTTPRequest $request)
    {
        if ($request->method() == 'POST') {
            $this->form->getField('username')->setValue($request->postData('username'));
            $this->form->getField('password')->setValue($request->postData('password'));
        }
    }

}