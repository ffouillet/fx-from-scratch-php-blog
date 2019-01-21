<?php

namespace OCFram\Repository;

use OCFram\Entities\Utils\ArrayToEntityConverter;

class BaseRepositoryPDOMysql extends BaseRepository {

    public function findOneById($id)
    {
        if (!is_int($id)) {
            throw new \InvalidArgumentException("id parameter must be a valid integer");
        }

        $sqlQuery = "SELECT * FROM ".$this->getTableName(). " ";
        $sqlQuery.= "WHERE ";
        $sqlQuery.= "id = ".$id;

        /*
         * Should have used PDO::FETCH_CLASS mode but
         * i don't like the fact that it gives an object (instead of array)
         * to the class constructor
         */
        $result = $this->getDb()->query($sqlQuery)->fetchAll(\PDO::FETCH_ASSOC);

        if ($result) {

            $hydratedEntity = ArrayToEntityConverter::convert($result, $this->getEntityName());
            $hydratedEntity = $hydratedEntity[0];

            return $hydratedEntity;
        }

        return false;

    }

    public function findOneByCriteria(array $criteria)
    {
        // TODO: Implement findOneByCriteria() method.
    }

    public function findBy(array $criteria, $orderBy = null, $limit = null, $offset = null)
    {
        // TODO: Implement findAllByCriteria() method.
    }

    public function findAll()
    {
        $sqlQuery = "SELECT * FROM ".$this->getTableName();

        $results = $this->getDb()->query($sqlQuery)->fetchAll(\PDO::FETCH_ASSOC);

        if ($results) {

            $hydratedEntities = ArrayToEntityConverter::convert($results, $this->getEntityName());
        }

        return $hydratedEntities;
    }

}