<?php

namespace App\Repository;

use OCFram\Repository\BaseRepositoryPDOMysql;
use OCFram\User\BaseUser;

class UserRepositoryPDOMysql extends BaseRepositoryPDOMysql
{
    public function save(BaseUser $user)
    {
        $sqlQuery = "INSERT INTO user (username, email, password, roles, createdAt) 
                      VALUES (:username, :email, :password, :roles, :createdAt)";
        $stmt = $this->getDb()->prepare($sqlQuery);

        $username = $user->getUsername();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $roles = implode(',', $user->getRoles()) . ',BASE_USER';
        $createdAt = $user->getCreatedAt()->format('Y-m-d H:i:s');

        $stmt->bindParam('username', $username, \PDO::PARAM_STR);
        $stmt->bindParam('email', $email, \PDO::PARAM_STR);
        $stmt->bindParam('password', $password, \PDO::PARAM_STR);
        $stmt->bindParam('roles', $roles, \PDO::PARAM_STR);
        $stmt->bindParam('createdAt', $createdAt, \PDO::PARAM_STR);

        return $stmt->execute();
    }
}