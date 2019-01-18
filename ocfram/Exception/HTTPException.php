<?php

namespace OCFram\Exception;

abstract class HTTPException extends \RuntimeException {

    protected $httpStatusCode;

    public function __construct($httpStatusCode, $message = "", $code = 0, \Exception $previous = null)
    {
        $this->httpStatusCode = $httpStatusCode;

        parent::__construct($message, $code, $previous);
    }

    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }
}