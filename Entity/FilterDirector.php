<?php
/**
 * Created by PhpStorm.
 * User: l3yr0y
 * Date: 11/4/15
 * Time: 8:39 AM
 */

namespace L3yIncubator\SearchBundle\Entity;

use Doctrine\ORM\EntityManager;

class FilterDirector
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param Filter $filter
     * @param bool|true $distinct
     *
     * @throws \InvalidArgumentException
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function queryBuilder(Filter $filter, $distinct = true)
    {
        $cmf = $this->em->getMetadataFactory();

        // Check for valid Entity
        if ($filter->getEntity() == null || $cmf->isTransient($filter->getEntity())) {
            throw new \InvalidArgumentException(sprintf(
                "%s must be a valid entity name, %s given",
                "Filter#entityName",
                $filter->getEntity()
            ));
        }

        $queryBuilder = $this->em
            ->getRepository($filter->getEntity())
            ->createQueryBuilder($filter->getAlias())
        ;

        $distinct = ($distinct) ? 'DISTINCT ' : '';

        $queryBuilder->select($distinct . $filter->getAlias());

        $data = $filter->getData();
        foreach ($data as $field => $values) { // Class properties
            $count = count($values);
            $where = '';
            for ($i=0; $i < $count; $i++) { // Values
                $where .= "co.$field LIKE :" . $field . $i;
                if ($i != $count - 1) // Isn't last field's value then keep concatenating with OR
                    $where .= ' OR ';
                $parameters[$field . $i] = $values[$i];
            }
            $queryBuilder->andWhere($where);
        }

        $queryBuilder->setParameters($parameters);

        return $queryBuilder;
    }
}