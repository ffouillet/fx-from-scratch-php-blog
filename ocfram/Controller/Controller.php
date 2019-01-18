<?php

namespace OCFram\Controller;

use OCFram\ApplicationComponent;
use OCFram\HTTPComponents\HTTPResponse;

class Controller extends ApplicationComponent {

    protected $action = '';
    protected $view = '';

    const CONTROLLER_METHOD_DOES_NOT_EXISTS = 1;
    const VIEW_NOT_FOUND = 2;

    public function __construct($application, $action = null)
    {
        parent::__construct($application);

        $this->setAction($action);

    }

    public function execute() {

        $method = 'execute'.ucfirst($this->action);

        if (!method_exists($this, $method)) {
            throw new \RuntimeException("The requested method does not exists in this Controller",
                self::CONTROLLER_METHOD_DOES_NOT_EXISTS);
        }

        return $this->$method($this->getApp()->getHTTPRequest());
    }

    public function render($viewFile, array $viewVariables = []) {

        $viewFilePath = APPLICATION_VIEW_FILES_PATH . $viewFile;

        if (!file_exists($viewFilePath) || !is_file($viewFilePath)) {
            throw new \InvalidArgumentException('The viewFile you specified doesn\'t exists in Application View File Path', self::VIEW_NOT_FOUND);
        }

        $content = $this->getApp()->getTemplateEngine()->render($viewFile, $viewVariables);

        return new HTTPResponse($content);
    }

    public function renderHTTPError($statusCode) {

        $statusCode = (int) $statusCode;

        if ($statusCode == false) {
            throw new \InvalidArgumentException("HTTP StatusCode must be a valid integer");
        }

        $content = $this->getApp()->getTemplateEngine()->render('errors/'.$statusCode.'.html.twig');

        return new HTTPResponse($content, $statusCode);
    }

    public function setAction($action)
    {
        if (is_string($action) && empty($action)) {
            throw new \InvalidArgumentException('Action must be a valid string');
        }

        $this->action = $action;
    }

    public function setView($view)
    {
        if (!is_string($view) || empty($view)) {
            throw new \InvalidArgumentException('View must be a valid string');
        }

        $this->view = $view;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getView()
    {
        return $this->view;
    }

    public function getEntityManager() {
        return $this->getApp()->getEntityManager();
    }

}