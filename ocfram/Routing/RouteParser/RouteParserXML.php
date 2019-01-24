<?php

namespace OCFram\Routing\RouteParser;

use OCFram\Routing\Route;

class RouteParserXML extends RouteParser {

    const ROUTE_FILE_NOT_IN_XML_FORMAT = 1;

    public function getRoutesFromRouteFile() {

        if (preg_match("#\.xml$#", $this->routesFilePath) == 0) {
            throw new \RuntimeException("Routes file is not in XML format as required", self::ROUTE_FILE_NOT_IN_XML_FORMAT);
        }

        $xml = new \DOMDocument;
        $xml->load($this->routesFilePath);

        $routesXML = $xml->getElementsByTagName('route');

        $routes = array();

        foreach ($routesXML as $route)
        {

            $vars = [];

            // Check if variables are in the URL
            if ($route->hasAttribute('vars'))
            {
                $vars = explode(',', $route->getAttribute('vars'));
            }

            $routes[] = new Route($route->getAttribute('name'),
                $route->getAttribute('url'),
                $route->getAttribute('controller'),
                $route->getAttribute('action'), $vars);

        }

        return $routes;
    }

}