<?php

namespace OCFram\Security;

class SecuredArea {

    protected $areaUrlPrefix;
    protected $requiredRoles;

    const AREA_PREFIX_IS_NOT_A_VALID_STRING = 1;

    public function __construct($areaUrlPrefix, array $requiredRoles)
    {
        $this->setAreaUrlPrefix($areaUrlPrefix);
        $this->setRequiredRolesToAccessArea($requiredRoles);
    }

    public function setRequiredRolesToAccessArea(array $roles)
    {
        $this->requiredRoles = $roles;
    }

    public function setAreaUrlPrefix($areaUrlPrefix) {

        if (!is_string($areaUrlPrefix) || empty($areaUrlPrefix)) {
            throw new \InvalidArgumentException("areaPrefix must be a valid string",
                self::AREA_PREFIX_IS_NOT_A_VALID_STRING);
        }

        $this->areaUrlPrefix = $areaUrlPrefix;
    }

    public function getAreaUrlPrefix()
    {
        return $this->areaUrlPrefix;
    }

    public function getRequiredRoles()
    {
        return $this->requiredRoles;
    }
}