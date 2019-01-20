<?php

namespace OCFram\Database\DBAL\Driver;

abstract class DBDriver {

    protected $dbHost;
    protected $dbName;
    protected $dbUser;
    protected $dbPassword;
    protected $dbConnectionOptions; // Not used for the moment.

    public function __construct($dbHost, $dbName, $dbUser, $dbPassword, array $dbConnectionOptions = null)
    {
        $this->setDbHost($dbHost);
        $this->setDbName($dbName);
        $this->setDbUser($dbUser);
        $this->setDbPassword($dbPassword);
        $this->setDbConnectionOptions($dbConnectionOptions);
    }

    abstract protected function connect();

    public function setDbHost($dbHost)
    {
        if (!is_string($dbHost) || empty($dbHost)) {
            throw new \InvalidArgumentException('DB Host must be a valid string');
        }

        $this->dbHost = $dbHost;

    }

    public function setDbName($dbName)
    {
        if (!is_string($dbName) || empty($dbName)) {
            throw new \InvalidArgumentException('DB Name must be a valid string');
        }

        $this->dbName = $dbName;
    }

    public function setDbUser($dbUser)
    {
        if (!is_string($dbUser) || empty($dbUser)) {
            throw new \InvalidArgumentException('DB User must be a valid string');
        }

        $this->dbUser = $dbUser;
    }

    public function setDbPassword($dbPassword)
    {

        if (!is_string($dbPassword)) {
            throw new \InvalidArgumentException('DB Password must be a valid string');
        }

        $this->dbPassword = $dbPassword;
    }

    public function setDbConnectionOptions($dbConnectionOptions)
    {
        $this->dbConnectionOptions = $dbConnectionOptions;
    }

    public function getDbHost()
    {
        return $this->dbHost;
    }

    public function getDbName()
    {
        return $this->dbName;
    }

    public function getDbUser()
    {
        return $this->dbUser;
    }

    public function getDbPassword()
    {
        return $this->dbPassword;
    }

    public function getDbConnectionOptions()
    {
        return $this->dbConnectionOptions;
    }

    public function getDriverName()
    {
        // Return class name
        $path = explode('\\', get_class($this));
        return str_replace('Driver','',array_pop($path));
    }

}