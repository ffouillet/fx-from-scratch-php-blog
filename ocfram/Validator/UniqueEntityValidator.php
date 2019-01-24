<?php

namespace OCFram\Validator;

use OCFram\Entities\EntityManager;

class UniqueEntityValidator extends Validator
{

    protected $entityClass;
    protected $entityAttribute;
    protected $entityManager;

    public function __construct($errorMessage, $entityClass, $entityAttribute, EntityManager $em)
    {
        $this->setEntityClass($entityClass);
        $this->setEntityAttribute($entityAttribute);
        $this->setEntityManager($em);
        parent::__construct($errorMessage);
    }

    public function isValid($value)
    {
        $uniqueEntity =
            $this->getEntityManager()
            ->getRepository($this->entityClass)
                ->findOneByAttribute($this->entityAttribute, $value);

        if (!empty($uniqueEntity)) {
            return false;
        }

        return true;
    }

    public function setEntityClass($entityClass)
    {
        if (!is_string($entityClass) || $entityClass == "") {
            throw new \InvalidArgumentException("entityClass must be a valid string");
        }

        if (!class_exists("\\App\\Entity\\".ucfirst($entityClass))) {
            throw new \InvalidArgumentException($entityClass . " class doesn't exists");
        }

        $this->entityClass = $entityClass;
    }

    public function setEntityAttribute($entityAttribute)
    {
        if (!is_string($entityAttribute) || $entityAttribute == "") {
            throw new \InvalidArgumentException("entityAttribute must be a valid string");
        }

        if (!property_exists("\\App\\Entity\\".ucfirst($this->entityClass), $entityAttribute)) {
            throw new \InvalidArgumentException(
                $this->entityClass . " " . "does not have " . $entityAttribute. " attribute");
        }

        $this->entityAttribute = $entityAttribute;
    }

    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }

}