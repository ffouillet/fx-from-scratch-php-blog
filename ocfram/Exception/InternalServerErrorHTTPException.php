<?php

namespace OCFram\Exception;

class InternalServerErrorHTTPException extends HTTPException {

    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct(500, $message, $code = 0, $previous = null);
    }
}