<?php

namespace OCFram\Entities;

use OCFram\Database\DBAL\DatabaseAccessObject;

class EntityManager {

    protected $databaseAccessObject;
    protected $managedEntities = [];

    const INVALID_ENTITY_NAME = 1;

    public function __construct(DatabaseAccessObject $databaseAccessObject)
    {
        $this->databaseAccessObject = $databaseAccessObject;
    }

    public function getRepository($entityName, $tableName = null)
    {
        if (!is_string($entityName) || empty($entityName)) {
            throw new \InvalidArgumentException("entityName must be a valid string (not empty)", self::INVALID_MODEL_NAME);
        }

        if (!isset($this->managedEntities[$entityName])) {

            $repositoryClass = 'App\\Repository\\'.ucfirst($entityName).'Repository';
            $repositoryClass .= ucfirst($this->getDatabaseAccessObject()->getDbDriver()->getDriverName());

            $this->managedEntities[$entityName] = new $repositoryClass($this, $entityName, $tableName);
        }

        return $this->managedEntities[$entityName];
    }

    public function getDatabaseAccessObject()
    {
        return $this->databaseAccessObject;
    }

}