<?php

namespace OCFram\Entities;

use OCFram\Database\DBAL\DatabaseAccessObject;

class EntityManager {

    protected $databaseAccessObject;
    protected $managedModels = [];

    const INVALID_MODEL_NAME = 1;

    public function __construct(DatabaseAccessObject $databaseAccessObject)
    {
        $this->databaseAccessObject = $databaseAccessObject;
    }

    public function getModel($model) {
        if (!is_string($model) || empty($model)) {
            throw new \InvalidArgumentException("Model must be a valid string (not empty)", self::INVALID_MODEL_NAME);
        }

        if (!isset($this->$managedModels[$model])) {

            $modelManagerClass = 'App\\Model\\'.ucfirst($model).'Manager'.ucfirst($this->api);

            $this->managedModels[$model] = new $modelManagerClass($this->dao);
        }
    }

    public function getDb() {
        return $this->databaseAccessObject;
    }

}