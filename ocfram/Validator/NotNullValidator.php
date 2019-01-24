<?php

namespace OCFram\Validator;

class NotNullValidator extends Validator
{

    public function isValid($value)
    {
        return $value != '';
    }

}