<?php

namespace OCFram\Exception;

class ForbiddenHTTPException extends HTTPException {

    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct(403, $message, $code, $previous = null);
    }
}