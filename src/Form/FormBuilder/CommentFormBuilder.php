<?php

namespace App\Form\FormBuilder;

use OCFram\Form\FormBuilder\FormBuilder;
use OCFram\Form\Field\TextField;
use OCFram\HTTPComponents\HTTPRequest;
use OCFram\Validator\MaxLengthValidator;
use OCFram\Validator\NotNullValidator;

class CommentFormBuilder extends FormBuilder
{

    public function build()
    {

        $this->form->addField(new TextField([
                'label' => 'Contenu',
                'name' => 'content',
                'rows' => 7,
                'cols' => 50,
                'validators' => [
                    new NotNullValidator('Merci de spécifier votre commentaire'),
                    new MaxLengthValidator('Votre commentaire est trop long (200 caractères maximum)', 200),
                ],
            ]));

    }

    public function getFormDatasFromRequest(HTTPRequest $request)
    {
        if ($request->method() == 'POST') {
            $this->form->getField('content')->setValue($request->postData('content'));
        }
    }

}