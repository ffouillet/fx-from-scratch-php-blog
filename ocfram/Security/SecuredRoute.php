<?php

namespace OCFram\Security;

class SecuredRoute {

    protected $routeName;
    protected $requiredRoles;

    const INVALID_ROUTE_NAME = 1;

    public function __construct($routename, array $requiredRoles)
    {
        $this->setRouteName($routename);
        $this->setRequiredRoles($requiredRoles);
    }

    public function getRouteName()
    {
        return $this->routeName;
    }

    public function setRouteName($routeName)
    {
        if (!is_string($routeName) || empty($routeName)) {
            throw new \InvalidArgumentException("routeName must be a valid string",
                self::INVALID_ROUTE_NAME);
        }

        $this->routeName = $routeName;
    }

    public function getRequiredRoles()
    {
        return $this->requiredRoles;
    }

    public function setRequiredRoles($requiredRoles)
    {
        $this->requiredRoles = $requiredRoles;
    }

}