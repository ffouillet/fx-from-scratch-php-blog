<?php

namespace OCFram\Validator;

class EmailValidator extends Validator
{

    public function isValid($value)
    {

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

}