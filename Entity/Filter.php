<?php

namespace L3yIncubator\SearchBundle\Entity;

class Filter
{
    /**
     * @var mixed
     */
    private $data;

    /**
     * Entity class name
     *
     * @var string
     */
    private $entity;

    /**
     * Query entity alias
     *
     * @var string
     */
    private $alias;

    /**
     * Search method, defaults "="
     *
     * @var array
     */
    private $operators = array();

    /**
     * @param string $data
     * @param string $entity
     * @param string $alias
     * @param array  $operators
     */
    public function __construct($data, $entity, $alias, array $operators = array())
    {
        $this->data = $data;
        $this->entity = $entity;
        $this->alias = $alias;
        $this->operators = $operators;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return mixed
     */
    public function getOperators()
    {
        return $this->Operators;
    }

    /**
     * @param $operators
     */
    public function setOperators($operators)
    {
        $this->operators = $operators;
    }
}