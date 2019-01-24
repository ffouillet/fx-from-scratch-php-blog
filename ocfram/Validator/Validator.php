<?php

namespace OCFram\Validator;

abstract class Validator {

    protected $errorMessage;

    public function __construct($errorMessage)
    {
        $this->setErrorMessage($errorMessage);
    }

    abstract protected function isValid($value);

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function setErrorMessage($errorMessage) {

        if (!is_string($errorMessage) || empty($errorMessage)) {
            throw new \InvalidArgumentException("errorMessage must be a valid string");
        }

        $this->errorMessage = $errorMessage;
    }
}