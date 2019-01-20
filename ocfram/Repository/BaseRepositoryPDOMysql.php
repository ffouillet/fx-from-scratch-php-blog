<?php

namespace OCFram\Repository;

class BaseRepositoryPDOMysql extends BaseRepository {

    public function findOneById($id)
    {
        if (!is_int($id)) {
            throw new \InvalidArgumentException("id parameter must be a valid integer");
        }

        $sqlQuery = "SELECT * FROM ".$this->getTableName(). " ";
        $sqlQuery.= "WHERE ";
        $sqlQuery.= "id = ".$id;

        $results = $this->getDb()->query($sqlQuery)->fetchAll(\PDO::FETCH_ASSOC);

        if ($results) {

            $hydratedEntities = [];

            $entityName = ucfirst($this->getEntityName());
            $entityNamespace = "App\\Entity\\" . $entityName;

            foreach($results as $result) {

                $entity = new $entityNamespace($result);

            }
        }

        return $result;
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

    }

}