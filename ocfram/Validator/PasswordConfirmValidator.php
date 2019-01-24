<?php

namespace OCFram\Validator;

class PasswordConfirmValidator extends Validator
{

    protected $firstPassword;

    public function __construct($errorMessage, $firstPassword)
    {
        parent::__construct($errorMessage);
    }

    public function isValid($value)
    {
        return $value == $this->firstPassword;
    }

    public function setFirstPassword($firstPassword)
    {
        if (!is_string($firstPassword) || $firstPassword == "") {
            throw new \InvalidArgumentException("firstPassword must be a valid string");
        }
    }

}