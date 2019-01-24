<?php

namespace OCFram;

use OCFram\Config\ApplicationConfig;
use OCFram\Controller\Controller;
use OCFram\Entities\EntityManager;
use OCFram\Exception\AccessDeniedException;
use OCFram\Exception\ForbiddenHTTPException;
use OCFram\Exception\HTTPException;
use OCFram\Exception\NotFoundHTTPException;
use OCFram\HTTPComponents\HTTPRequest;
use OCFram\HTTPComponents\HTTPResponse;
use OCFram\Routing\Router;
use OCFram\Security\AccessControl;
use OCFram\User\BaseUser;
use Twig_Environment;

class Application
{
    protected $config;
    protected $router;
    protected $accessControl;
    protected $httpRequest;
    protected $httpResponse;
    protected $entityManager;
    protected $templateEngine;
    protected $user;

    public function __construct(ApplicationConfig $config,
                                Router $router,
                                AccessControl $accessControl,
                                HTTPRequest $httpRequest,
                                HTTPResponse $httpResponse,
                                EntityManager $entityManager,
                                Twig_Environment $templateEngine,
                                BaseUser $user)
    {
        $this->config = $config;
        $this->router = $router;
        $this->accessControl = $accessControl;
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

        // Check if route requires authentication and or authorization.
        try {
            $this->accessControl->isUserAllowedToReachThisRoute($matchedRoute, $this->user);
        } catch (AccessDeniedException $e) {
            throw new ForbiddenHTTPException($e->getMessage(), $e->getCode());
        }

        // Add URL variables to $_GET array
        if ($matchedRoute->getVars() !== null) {
            $_GET = array_merge($_GET, $matchedRoute->getVars());
        }

        // Instanciate Controller.
        $controllerClass = 'App\\Controller\\'.$matchedRoute->getController().'Controller';

        return new $controllerClass($this, $matchedRoute->getAction());
    }

    public function run(){

        try {
            $controller = $this->getController();
            $this->httpResponse = $controller->execute();
        } catch (HTTPException $e) {

            // Current page requires authentication
            if ($e instanceof ForbiddenHTTPException && $e->getCode() == AccessControl::USER_NOT_AUTHENTICATED) {

                $this->httpResponse->redirect('/login');
            }

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

    public function getUser()
    {
        return $this->user;
    }

}