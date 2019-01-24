<?php

namespace OCFram\Repository;

use OCFram\Entities\EntityManager;

abstract class BaseRepository implements BaseRepositoryInterface {

    protected $entityName;
    protected $tableName;
    protected $entityManager;

    public function __construct(EntityManager $entityManager, $entityName, $tableName)
    {

        $this->setEntityName($entityName);

        // If no table name is given, will use the entityName
        if ($tableName == "") {
            $this->setTableName(strtolower($entityName));
        } else {
            $this->setTableName($tableName);
        }

        $this->entityManager = $entityManager;
    }

    abstract public function findOneById($id);

    abstract public function findOneByCriteria(array $criterias);

    abstract public function findBy(array $criterias, $orderBy = null, $limit = null, $offset = null);

    abstract public function findAll();

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getEntityName()
    {
        return $this->entityName;
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }

    public function getDb()
    {
        return $this->getEntityManager()->getDatabaseAccessObject()->getDb();
    }
}