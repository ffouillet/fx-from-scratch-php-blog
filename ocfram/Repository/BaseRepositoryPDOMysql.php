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

    public function countAll()
    {
        $sqlQuery = "SELECT COUNT(*) FROM ".$this->getTableName();

        $nbrEntities = $this->getDb()->query($sqlQuery)->fetch(\PDO::FETCH_UNIQUE);

        if ($nbrEntities) {
            return $nbrEntities[0];
        }

        return false;
    }

    public function findOneByAttribute($attribute, $attributeValue) {

        if (!is_string($attribute) || empty($attribute)) {
            throw new\InvalidArgumentException('attribute parameter must be a valid string');
        }

        $entityClass = "App\\Entity\\" . ucfirst($this->getEntityName());

        if (!property_exists($entityClass, $attribute)) {
            throw new \InvalidArgumentException("Class User doesn't have attribute '".$attribute.'"');
        }

        $sqlQuery = "SELECT * FROM user WHERE ".$attribute." = :".$attribute;

        $stmt = $this->getDb()->prepare($sqlQuery);

        $pdoDataType = \PDO::PARAM_STR;

        switch (gettype($attribute)) {
            case 'integer' :
                $pdoDataType = \PDO::PARAM_INT;
                break;
            case 'object' :
                if ($attribute instanceof \DateTime) {
                    $attribute = $attribute->format('Y-m-d H:i:s');
                }
                break;
            default :
                break;
        }

        $stmt->bindParam($attribute, $attributeValue, $pdoDataType);

        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $stmt->closeCursor();

        return $result;
    }
}