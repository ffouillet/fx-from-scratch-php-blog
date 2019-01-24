<?php

namespace OCFram\Routing;

class Route
{

    protected $controller;
    protected $name;
    protected $action;
    protected $url;
    protected $varsNames;
    protected $vars;

    public function __construct($name, $url, $controller, $action, array $varsNames)
    {
        $this->setName($name);
        $this->setUrl($url);
        $this->setController($controller);
        $this->setAction($action);
        $this->setVarsNames($varsNames);
    }

    public function hasVars()
    {
        return !empty($this->varsNames);
    }

    public function match($url)
    {
        if (preg_match('#^' . $this->url . '$#', $url, $matches)) {
            return $matches;
        }

        return false;
    }

    public function setController($controller)
    {
        if (is_string($controller)) {
            $this->controller = $controller;
        }
    }

    public function setAction($action)
    {
        if (is_string($action)) {
            $this->action = $action;
        }
    }

    public function setUrl($url)
    {
        if (is_string($url)) {
            $this->url = $url;
        }
    }

    public function setVars(array $vars)
    {
        $this->vars = $vars;
    }

    public function setVarsNames(array $varsNames)
    {
        $this->varsNames = $varsNames;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getVarsNames()
    {
        return $this->varsNames;
    }

    public function getVars()
    {
        return $this->vars;
    }

    public function getName()
    {
        return $this->name;
    }

}