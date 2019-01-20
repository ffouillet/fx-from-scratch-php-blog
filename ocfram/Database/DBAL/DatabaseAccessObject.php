<?php

namespace OCFram\Database\DBAL;

use OCFram\Database\DBAL\Driver\DBDriver;

class DatabaseAccessObject {

    protected $dbConnection;
    protected $dbDriver;

    public function __construct(DBDriver $dbDriver)
    {
        $this->setDbDriver($dbDriver);
    }

    public function initDbConnection() {

        $this->dbConnection = $this->dbDriver->connect();
    }

    public function getDb() {

        if ($this->dbConnection == null) {
            $this->initDbConnection();
        }

        return $this->dbConnection;
    }

    public function setDbDriver(DBDriver $dbDriver)
    {
        $this->dbDriver = $dbDriver;
    }

    public function getDbDriver()
    {
        return $this->dbDriver;
    }
}