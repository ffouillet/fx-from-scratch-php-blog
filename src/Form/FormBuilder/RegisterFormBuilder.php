<?php

namespace App\Form\FormBuilder;

use OCFram\Entities\BaseEntity;
use OCFram\Entities\EntityManager;
use OCFram\Form\Field\EmailField;
use OCFram\Form\Field\PasswordField;
use OCFram\Form\Field\StringField;
use OCFram\Form\FormBuilder\FormBuilder;
use OCFram\HTTPComponents\HTTPRequest;
use OCFram\Validator\MaxLengthValidator;
use OCFram\Validator\MinLengthValidator;
use OCFram\Validator\NotNullValidator;
use OCFram\Validator\PasswordConfirmValidator;
use OCFram\Validator\UniqueEntityValidator;

class RegisterFormBuilder extends FormBuilder
{

    public function __construct(BaseEntity $entity = null, EntityManager $em)
    {
        $this->setEntityManager($em);

        parent::__construct($entity);
    }

    public function build()
    {
        $this->form->addField(new StringField([
            'label' => 'Nom d\'utilisateur',
            'name' => 'username',
            'validators' => [
                new NotNullValidator('Merci de spécifier votre nom d\'utilisateur'),
                new MinLengthValidator('Votre nom d\'utilisateur est trop court (3 caractères minimum)',3),
                new MaxLengthValidator('Votre nom d\'utilisateur est trop long (30 caractères maximum)',30),
                new UniqueEntityValidator(
                    'Votre nom d\'utilisateur n\'est pas disponible, merci d\'en choisir un nouveau',
                    'User', 'username', $this->getEntityManager()
                )
            ],
        ]))
        ->addField(new EmailField([
            'label' => 'Adresse email',
            'name' => 'email',
            'validators' => [
                new NotNullValidator('Merci de spécifier votre adresse email'),
                new UniqueEntityValidator(
                    'Votre adresse email n\'est pas disponible, merci d\'en choisir une nouvelle',
                    'User', 'email', $this->getEntityManager()
                )
            ],
        ]))
        ->addField(new PasswordField([
            'label' => 'Mot de passe',
            'name' => 'password',
            'validators' => [
                new NotNullValidator('Merci de spécifier votre mot de passe'),
                new MinLengthValidator('Votre mot de passe est trop court (6 caractères minimum)',6),
                new MaxLengthValidator('Votre mot de passe est trop long (50 caractères maximum)',50)
            ],
        ]))
        ->addField(new PasswordField([
            'label' => 'Confirmation de mot de passe',
            'name' => 'passwordConfirm',
            'validators' => [
                new NotNullValidator('Merci de spécifier votre confirmation de mot de passe')
            ],
        ]));

    }

    public function getFormDatasFromRequest(HTTPRequest $request)
    {
        if ($request->method() == 'POST') {
            $this->form->getField('username')->setValue($request->postData('username'));
            $this->form->getField('email')->setValue($request->postData('email'));
            $this->form->getField('password')->setValue($request->postData('password'));
            $this->form->getField('passwordConfirm')->setValue($request->postData('passwordConfirm'));
        }

    }

}