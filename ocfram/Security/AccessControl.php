<?php

namespace OCFram\Security;

use OCFram\Exception\AccessDeniedException;
use OCFram\Routing\Route;
use OCFram\User\BaseUser;

class AccessControl {

    protected $securedRoutes;
    protected $securedAreas;

    const INVALID_SECURED_AREA_FORMAT = 1;
    const INVALID_SECURED_ROUTE_FORMAT = 2;
    const USER_NOT_AUTHENTICATED = 3;
    const USER_DOESNT_HAVE_REQUIRED_ROLE = 4;

    public function __construct(array $securedRoutesAndAreas)
    {
        $this->setSecuredAreas($securedRoutesAndAreas['securedAreas']);
        $this->setSecuredRoutes($securedRoutesAndAreas['securedRoutes']);
    }

    public function isUserAllowedToReachThisRoute(Route $matchedRoute, BaseUser $user)
    {

        $routeIsProtected = false;
        $routeRequiredRoles = array();

        // Check if route is part of a secured area
        foreach ($this->securedAreas as $securedArea) {
            if (preg_match("#^".$securedArea->getAreaUrlPrefix()."#", $matchedRoute->getUrl())) {
                $routeIsProtected = true;
                $routeRequiredRoles = $securedArea->getRequiredRoles();
            }
        }

        // Check if route is protected as an individual route
        foreach ($this->securedRoutes as $securedRoute) {

            if ($securedRoute->getRouteName() == $matchedRoute->getName()) {
                $routeIsProtected = true;
                $routeRequiredRoles = $securedRoute->getRequiredRoles();
            }
        }

        // Check if user can access this route
        if ($routeIsProtected) {
            if ($user->getRoles() == ['ANONYMOUS_USER'] && !empty($routeRequiredRoles) && !in_array("ANONYMOUS_USER",$routeRequiredRoles)) {

                throw new AccessDeniedException(
                    "Forbidden access, user must be authenticated in order to access this ressource",
                    self::USER_NOT_AUTHENTICATED);
            }

            $userHasRoleForThisRoute = true;
            foreach ($routeRequiredRoles as $routeRequiredRole) {
                if (!\in_array($routeRequiredRole, $user->getRoles())) {
                    $userHasRoleForThisRoute = false;
                }
            }

            if ($userHasRoleForThisRoute) {
                return true;
            } else {
                throw new AccessDeniedException(
                    "Forbidden access, you are not allowed to see this ressource",
                    self::USER_DOESNT_HAVE_REQUIRED_ROLE);
            }
        }

        return true;
    }

    public function getSecuredRoutes()
    {
        return $this->securedRoutes;
    }

    public function setSecuredRoutes($securedRoutes)
    {
        foreach ($securedRoutes as $securedRoute) {
            if (!$securedRoute instanceof SecuredRoute) {
                throw new\InvalidArgumentException(
                    "AccessControl accept as securedRoutes only instances of SecuredRoute",
                    self::INVALID_SECURED_ROUTE_FORMAT);
            }
        }

        $this->securedRoutes = $securedRoutes;
    }

    public function getSecuredAreas()
    {
        return $this->securedAreas;
    }

    public function setSecuredAreas($securedAreas)
    {
        foreach ($securedAreas as $securedArea) {
            if (!$securedArea instanceof SecuredArea) {
                throw new\InvalidArgumentException(
                    "AccessControl accept as securedAreas only instances of SecuredArea",
                    self::INVALID_SECURED_AREA_FORMAT);
            }
        }

        $this->securedAreas = $securedAreas;
    }

}