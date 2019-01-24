<?php

namespace OCFram\Entities;

use OCFram\Utils\ObjectHydratorTrait;

abstract class BaseEntity implements \ArrayAccess
{

    use ObjectHydratorTrait;

    protected $id;
    protected $errors = [];

    public function __construct(array $datas = [])
    {
        if (!empty($datas)) {
            $this->hydrate($datas);
        }
    }

    public function isNew()
    {
        return empty($this->id);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = (int) $id;
    }

    public function offsetGet($var)
    {
        if (isset($this->$var) && is_callable([$this, $var])) {
            return $this->$var();
        }

    }

    public function offsetSet($var, $value)
    {

        $method = 'set'.ucfirst($var);

        if (isset($this->$var) && is_callable([$this, $method])) {
            $this->$method($value);
        }
    }

    public function offsetExists($var)
    {
        return isset($this->$var) && is_callable([$this, $var]);
    }

    public function offsetUnset($var)
    {
        throw new \Exception('Entity value deletion is forbidden');
    }

}