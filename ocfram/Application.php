<?php

namespace OCFram;

use OCFram\Config\ApplicationConfig;
use OCFram\Controller\Controller;
use OCFram\Entities\EntityManager;
use OCFram\Exception\HTTPException;
use OCFram\Exception\NotFoundHTTPException;
use OCFram\HTTPComponents\HTTPRequest;
use OCFram\HTTPComponents\HTTPResponse;
use OCFram\Routing\Router;
use OCFram\User\BaseUser;
use Twig_Environment;

class Application
{
    protected $config;
    protected $router;
    protected $httpRequest;
    protected $httpResponse;
    protected $entityManager;
    protected $templateEngine;
    protected $user;

    public function __construct(ApplicationConfig $config,
                                Router $router,
                                HTTPRequest $httpRequest,
                                HTTPResponse $httpResponse,
                                EntityManager $entityManager,
                                Twig_Environment $templateEngine,
                                BaseUser $user)
    {
        $this->config = $config;
        $this->router = $router;
        $this->httpRequest = $httpRequest;
        $this->httpResponse = $httpResponse;
        $this->entityManager = $entityManager;
        $this->templateEngine = $templateEngine;
        $this->user = $user;
    }

    public function getController() {

        try {
            $matchedRoute = $this->router->getRoute($this->httpRequest->requestURI());
        }
        catch (\RuntimeException $e) {

            if ($e->getCode() == Router::NO_ROUTE)
            {
                // If no route matchs, it means that requested page doesn't exists.
                throw new NotFoundHTTPException("Ressource you requested cannot be found");
            }
        }

        // Add URL variables to $_GET array
        $_GET = array_merge($_GET, $matchedRoute->vars());

        // Instanciate Controller.
        $controllerClass = 'App\\Controller\\'.$matchedRoute->getController().'Controller';

        return new $controllerClass($this, $matchedRoute->action());
    }

    public function run(){

        try {
            $controller = $this->getController();
            $this->httpResponse = $controller->execute();
        } catch (HTTPException $e) {
            $controller = new Controller($this);
            $this->httpResponse = $controller->renderHTTPError($e->getHttpStatusCode());
        }

        // Auth Part or Firewall

        $this->httpResponse->send();

    }

    public function getHTTPRequest()
    {
        return $this->httpRequest;
    }

    public function getHTTPResponse()
    {
        return $this->httpResponse;
    }

    public function getTemplateEngine()
    {
        return $this->templateEngine;
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }

}