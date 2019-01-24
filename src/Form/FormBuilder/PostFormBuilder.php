<?php

namespace App\Form\FormBuilder;

use OCFram\Form\FormBuilder\FormBuilder;
use OCFram\Form\Field\StringField;
use OCFram\Form\Field\TextField;
use OCFram\HTTPComponents\HTTPRequest;
use OCFram\Validator\MaxLengthValidator;
use OCFram\Validator\MinLengthValidator;
use OCFram\Validator\NotNullValidator;

class PostFormBuilder extends FormBuilder
{
    public function build()
    {
        $this->form->addField(new StringField([
                'label' => 'Titre',
                'name' => 'title',
                'maxLength' => 100,
                'validators' => [
                    new MinLengthValidator('Le titre spécifié est trop court (5 caractères minimum)', 5),
                    new MaxLengthValidator('Le titre spécifié est trop long (100 caractères maximum)', 100),
                    new NotNullValidator('Merci de spécifier le titre de l\'article'),
                ],
            ]))
            ->addField(new StringField([
                'label' => 'Chapô',
                'name' => 'heading',
                'maxLength' => 100,
                'validators' => [
                    new MinLengthValidator('Le chapô spécifié est trop court (5 caractères minimum)', 5),
                    new MaxLengthValidator('Le chapô spécifié est trop long (100 caractères maximum)', 100),
                    new NotNullValidator('Merci de spécifier le chapô de l\'article'),
                ],
            ]))
            ->addField(new TextField([
                'label' => 'Contenu',
                'name' => 'content',
                'rows' => 8,
                'cols' => 60,
                'validators' => [
                    new MinLengthValidator('Le contenu spécifié est trop court (30 caractères minimum)', 30),
                    new MaxLengthValidator('Le contenu spécifié est trop long (2000 caractères minimum)', 2000),
                    new NotNullValidator('Merci de spécifier le contenu de l\'article'),
                ],
            ]));
    }

    public function getFormDatasFromRequest(HTTPRequest $request)
    {
        if ($request->method() == 'POST') {
            $this->form->getField('title')->setValue($request->postData('title'));
            $this->form->getField('heading')->setValue($request->postData('heading'));
            $this->form->getField('content')->setValue($request->postData('content'));
        }
    }

    public function buildFormWithEntityDatas()
    {
        $post = $this->form->getEntity();
        $this->form->getField('heading')->setValue($post->getHeading());
        $this->form->getField('title')->setValue($post->getTitle());
        $this->form->getField('content')->setValue($post->getContent());
    }
}