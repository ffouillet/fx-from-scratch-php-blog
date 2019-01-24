<?php

namespace OCFram\Database\DBAL\Driver;

class PDOMysqlDriver extends DBDriver
{

    public function connect()
    {

        $dsn  = 'mysql:dbname='.$this->getDbName().';';
        $dsn .= 'host='.$this->getDbHost();
        $dsn .= ';charset=utf8';

        try  {
            $db = new \PDO($dsn, $this->getDbUser(), $this->getDbPassword());
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            return $db;
        } catch (\PDOException $e) {
            throw new \PDOException("Connection to database failed : ".$e->getMessage(), $e->getCode());
        }

    }
    
}