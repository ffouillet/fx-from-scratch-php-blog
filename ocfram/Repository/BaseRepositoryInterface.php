<?php

namespace OCFram\Repository;

use OCFram\Entities\BaseEntity;
use OCFram\Entities\EntityManager;

interface BaseRepositoryInterface {
    public function __construct(EntityManager $entityManager, $entityName, $tableName);
    public function findOneById($id);
    public function findOneByCriteria(array $criteria);
    public function findBy(array $criteria, $orderBy = null, $limit = null, $offset = null);
    public function findAll();
    public function countAll();
    public function setEntityName($entityName);
    public function setTableName($tableName);
    // TODO : public function save(BaseEntity $entity, array $fieldsToSave);
}