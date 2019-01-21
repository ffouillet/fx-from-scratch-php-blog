<?php

namespace OCFram\Entities\Utils;

class ArrayToEntityConverter {

    public static function convert(array $datas, $entityName) {

        if (!is_string($entityName) || empty($entityName)) {
            throw new \InvalidArgumentException("Entity name must be a valid string");
        }

        $entityNamespace = "App\\Entity\\" . $entityName;

        $entities = [];

        foreach($datas as $arrayEntityData) {

            $entity = new $entityNamespace($arrayEntityData);

            $entities[] = $entity;
        }

        return $entities;
    }
}