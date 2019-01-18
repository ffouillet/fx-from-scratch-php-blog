<?php

namespace OCFram\HTTPComponents;

class HTTPResponse
{
    protected $content;
    protected $httpStatusCode;
    protected $headers = array();

    public function __construct($content = '', $httpStatusCode = 200)
    {
        $this->content = $content;
        $this->httpStatusCode = (int) $httpStatusCode;
    }

    public function addHeader($header)
    {
        if (!is_string($header) || empty($header)) {
            throw new \InvalidArgumentException('Header must be a valid string');
        }

        $this->headers[] = $header;
    }


    public function redirect($location)
    {
        header('Location: '.$location);
        exit;
    }

    public function send()
    {

        $this->handleHttpStatusCode();

        foreach ($this->headers as $header) {

            header($header);
        }

        exit($this->content);

    }

    // Changement par rapport à la fonction setcookie() : le dernier argument est par défaut à true
    public function setCookie($name, $value = '', $expire = 0, $path = null, $domain = null, $secure = false, $httpOnly = true)
    {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }

    public function setContent($content) {

        if (!is_string($content) || empty($content)) {
            throw new \InvalidArgumentException('HTTPResponse\'s content must be a valid string');
        }

        $this->content = $content;
    }

    public function setHttpStatusCode($httpStatusCode) {
        $httpStatusCode = (int) $httpStatusCode;

        if ($httpStatusCode == false) {
            throw new \InvalidArgumentException('HTTPResponse\'s status code must be a valid integer');
        }
    }

    public function handleHttpStatusCode() {

        switch ($this->httpStatusCode) {
            case 404 :
                $this->addHeader('HTTP/1.0 404 Not Found');
                break;
            default :
                break;
        }
    }

}
