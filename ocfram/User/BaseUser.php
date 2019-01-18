<?php

namespace OCFram\User;

session_start();

class BaseUser {

    protected $roles = ['USER'];

    public function getAttribute($attribute)
    {
        return isset($_SESSION[$attribute]) ? $_SESSION[$attribute] : null;
    }

    public function getFlash()
    {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);

        return $flash;
    }

    public function hasFlash()
    {
        return isset($_SESSION['flash']);
    }

    public function isAuthenticated()
    {
        return isset($_SESSION['auth']) && $_SESSION['auth'] === true;
    }

    public function setAuthenticated($authenticated = true)
    {
        if (!is_bool($authenticated))
        {
            throw new \InvalidArgumentException('Value specified to User::setAuthenticated must be a boolean');
        }

        $_SESSION['auth'] = $authenticated;
    }

    public function setFlash($value)
    {
        $_SESSION['flash'] = $value;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function addRoles(array $roles) {

        $newRoles = array_unique(array_merge($this->roles, $roles));

        $this->roles = $newRoles;
    }


}