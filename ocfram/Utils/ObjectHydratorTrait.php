<?php

namespace OCFram\Utils;

trait ObjectHydratorTrait {

    public function hydrate(array $datas)
    {

        foreach ($datas as $attribute => $value) {

            // If attribute is a date, convert it to a DateTime (must respect a naming convention for date attributes)
            // Naming convention, attribute suffix is 'At'
            if (preg_match("#.*At$#", $attribute)) {
                $value = new \DateTime($value);
            }

            $method = 'set'.ucfirst($attribute);

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }
}