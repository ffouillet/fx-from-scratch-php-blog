<?php

namespace OCFram\Database\DBAL\Driver;

class DBDriverFactory {

    public static function createDBDriver($dbDriver, $dbHost, $dbName, $dbUser, $dbPassword, array $dbConnectionOptions = null) {

        $dbDriverClass = self::getDBDriverClass($dbDriver);

        return new $dbDriverClass($dbHost, $dbName, $dbUser, $dbPassword, $dbConnectionOptions);
    }

    public static function getDBDriverClass($dbDriver) {

        $dbDriversBaseNamespace = "\\OCFram\\Database\\DBAL\\Driver";
        $dbDriverClass = $dbDriversBaseNamespace . '\\' .$dbDriver . 'Driver';

        if (!class_exists($dbDriverClass)) {
            throw new \RuntimeException("The DB driver class '".$dbDriverClass."' you requested doesn't exists. 
                                                    Please check for typos in the db_driver config parameter value
                                                    or create the driver class if it doesn't exists.");
        }

        return $dbDriverClass;

    }
}