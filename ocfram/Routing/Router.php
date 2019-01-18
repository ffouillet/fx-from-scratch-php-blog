<?php

namespace OCFram\Routing;

class Router {

    protected $routes = [];

    const NO_ROUTE = 1;

    public function addRoute(Route $route) {
        if (!in_array($route, $this->routes)) {
            $this->routes[] = $route;
        }
    }

    public function setRoutes(array $routes) {

        foreach ($routes as $route) {
            if (!$route instanceof Route) {
                throw new \RuntimeException("Router setRoutes method only accept an array of Route as argument.");
            }
        }

        $this->routes = $routes;
    }

    public function getRoute($url) {

        foreach ($this->routes as $route) {

            if (($varsValue = $route->match($url)) !== false) {

                // If route has variables
                if ($route->hasVars()) {

                    $varsNames = $route->getVarsNames();
                    $listVars = [];

                    foreach ($varsValue as $key => $match) {

                        // First value contains full captured string (check preg_match doc)
                        if ($key == 0) {
                            continue;
                        }

                        $listVars[$varsNames[$key - 1]] = $match;
                    }

                    $route->setVars($listVars);
                }

                return $route;
            }
        }

        throw new \RuntimeException('No route found for the given url', self::NO_ROUTE);
    }
}