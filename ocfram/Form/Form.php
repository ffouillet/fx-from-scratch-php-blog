<?php

namespace OCFram\Form;

use OCFram\Entities\BaseEntity;
use OCFram\Form\Field\Field;

class Form
{
    protected $entity;
    protected $isEntityMapped;
    protected $fields = [];

    public function __construct(BaseEntity $entity = null)
    {

        if ($entity != null) {
            $this->setEntity($entity);
        }

    }

    public function addField(Field $field)
    {
        $attribute = $field->getName();

        if ($this->isEntityMapped) {
            $attributeGetMethod = "get".ucfirst($attribute);
            $field->setValue($this->entity->$attributeGetMethod());
        }

        $this->fields[$attribute] = $field;
        return $this;
    }

    public function createView()
    {
        $view = '';

        foreach ($this->fields as $field) {
            $view .= $field->buildWidget();
        }

        return $view;
    }

    public function isValid()
    {
        $valid = true;

        // Check that all fields ar valid
        foreach ($this->fields as $field) {

            if (!$field->isValid()) {

                $valid = false;
            }
        }

        return $valid;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function setEntity(BaseEntity $entity)
    {
        $this->entity = $entity;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getField($field)
    {
        if (!isset($this->fields[$field])) {
            throw new \InvalidArgumentException('Form field '.$field.' doesn\'t exists.');
        }

        return $this->fields[$field];
    }

    public function setIsEntitymapped($isEntityMapped)
    {
        if ($isEntityMapped !== true && $isEntityMapped !== false) {
            throw new \InvalidArgumentException("isEntityMapped must be a valid boolean");
        }

        $this->isEntityMapped = $isEntityMapped;
    }

    public function getIsEntityMapped()
    {
        return $this->isEntityMapped;
    }
}