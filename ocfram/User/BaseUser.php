<?php

namespace OCFram\User;

use OCFram\Entities\BaseEntity;

session_start();

class BaseUser extends BaseEntity {

    protected $username;
    protected $email;
    protected $plainPassword;
    protected $password;
    protected $createdAt;
    protected $roles = ['ANONYMOUS_USER'];

    public function __construct(array $datas = [])
    {
        $this->createdAt = new \DateTime();
        parent::__construct($datas);
    }

    public function getAttribute($attribute)
    {
        return isset($_SESSION[$attribute]) ? $_SESSION[$attribute] : null;
    }

    public function getFlash()
    {
        if (isset($_SESSION['flash'])) {

            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }

        return null;

    }

    public function hasFlash()
    {
        return isset($_SESSION['flash']);
    }

    public function isAuthenticated()
    {
        if (isset($_SESSION['userId']) && is_int($_SESSION['userId']) && $_SESSION['userId'] > 0) {
            return $_SESSION['userId'];
        }

    }

    public function setAuthenticated()
    {
        $_SESSION['userId'] = $this->getId();
    }

    public function setFlash($value)
    {
        $_SESSION['flash'] = $value;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function addRoles(array $roles)
    {
        $newRoles = array_unique(array_merge($this->roles, $roles));
        $this->roles = $newRoles;
    }

    public function setRoles($roles)
    {
        if (is_array($roles)) {
            $this->roles = $roles;
        } else if (is_string($roles)) {
            $this->roles = explode(',', $roles);
        }
    }


    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function encodePassword($plainPassword)
    {
        return password_hash($plainPassword, PASSWORD_BCRYPT);
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreated(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function hasRole($role)
    {
        return \in_array($role, $this->getRoles());
    }

    public function isAdmin()
    {
        return \in_array("ADMIN", $this->getRoles());
    }

    public function logout()
    {
        if (isset($_SESSION['userId'])) {
            unset($_SESSION['userId']);
        }
    }

}