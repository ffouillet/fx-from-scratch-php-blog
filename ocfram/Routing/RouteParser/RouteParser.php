<?php

namespace OCFram\Routing\RouteParser;

abstract class RouteParser {

    protected $routesFilePath;

    const ROUTES_FILE_NOT_FOUND = 1;

    public function __construct($routesFilePath)
    {
        $this->setRoutesFilePath($routesFilePath);
    }

    protected function setRoutesFilePath($routesFilePath)
    {

        if (!is_string($routesFilePath) || !file_exists($routesFilePath) || !is_file($routesFilePath)) {
            throw new \RuntimeException("Routes file cannot be found", self::ROUTES_FILE_NOT_FOUND);
        }

        $this->routesFilePath = $routesFilePath;

    }

    abstract protected function getRoutesFromRouteFile();


}