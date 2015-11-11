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
     * Last key element
     *
     * @var string
     */
    private $last;

    /**
     * @var int
     */
    private $offset;

    /**
     * @var int
     */
    private $limit;

    /**
     * @param string $data
     * @param string $entity
     * @param string $alias
     * @param array  $parameters
     * @param array  $operators
     */
    public function __construct($data, $entity, $alias, array $parameters = array(), array $operators = array())
    {
        $this->setData($data);
        $this->entity = $entity;
        $this->alias = $alias;
        $this->operators = $operators;
        $this->offset = isset($parameters['offset']) ? (int) $parameters['offset'] : 0;
        $this->limit = isset($parameters['limit']) ? (int) $parameters['limit'] : 0;
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

        $keys = array_keys($data);
        $this->last = array_pop($keys);
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
        return $this->operators;
    }

    /**
     * @param $operators
     */
    public function setOperators($operators)
    {
        $this->operators = $operators;
    }

    /**
     * @return mixed|string
     */
    public function getLast()
    {
        return $this->last;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     * @return $this
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }
}