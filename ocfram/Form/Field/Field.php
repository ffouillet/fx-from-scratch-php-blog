<?php

namespace OCFram\Form\Field;

use OCFram\Utils\ObjectHydratorTrait;
use OCFram\Validator\Validator;

abstract class Field
{

    use ObjectHydratorTrait;

    protected $errorMessage;
    protected $label;
    protected $name;
    protected $value;
    protected $validators = [];

    public function __construct(array $options = array())
    {
        if (!empty($options)) {
            $this->hydrate($options);
        }
    }

    abstract public function buildWidget();

    public function isValid()
    {
        foreach ($this->validators as $validator) {

            if (!$validator->isValid($this->value)) {
                $this->errorMessage = $validator->getErrorMessage();
                return false;
            }
        }

        return true;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label)
    {
        if (is_string($label)) {
            $this->label = $label;
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        if (is_string($name)) {
            $this->name = $name;
        }
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function setValidators(array $validators)
    {
        foreach ($validators as $validator)
        {
            if ($validator instanceof Validator && !in_array($validator, $this->validators)) {

                $this->validators[] = $validator;

            }
        }
    }

    public function getValidators()
    {
        return $this->validators;
    }

}