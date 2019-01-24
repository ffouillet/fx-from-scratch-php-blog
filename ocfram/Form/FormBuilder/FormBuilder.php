<?php

namespace OCFram\Form\FormBuilder;

use OCFram\Entities\BaseEntity;
use OCFram\Entities\EntityManager;
use OCFram\Form\Form;
use OCFram\HTTPComponents\HTTPRequest;

abstract class FormBuilder
{
    protected $entityManager;
    protected $form;

    public function __construct(BaseEntity $entity = null, EntityManager $entityManager = null)
    {
        $this->setForm(new Form($entity));

        if ($entity != null) {
            $this->build();
        }

        if ($entityManager != null) {
            $this->entityManager = $entityManager;
        }

    }

    abstract public function build();

    abstract public function getFormDatasFromRequest(HTTPRequest $request);

    public function setForm(Form $form)
    {
        $this->form = $form;
    }

    public function getForm()
    {
        return $this->form;
    }

    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }

}