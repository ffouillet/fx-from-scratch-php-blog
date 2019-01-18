<?php

namespace OCFram\Exception;

class NotFoundHTTPException extends HTTPException {

    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct(404, $message, $code = 0, $previous = null);
    }
}