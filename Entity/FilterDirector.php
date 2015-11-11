<?php
/**
 * Created by PhpStorm.
 * User: l3yr0y
 * Date: 11/4/15
 * Time: 8:39 AM
 */

namespace L3yIncubator\SearchBundle\Entity;

use Doctrine\ORM\EntityManager;
use L3yIncubator\SearchBundle\Entity\Util\Operator;

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

        $parameters = array();
        $data = $filter->getData();

        $this->recursiveBuildQuery($data, $queryBuilder, $filter, $parameters);

        $queryBuilder->setParameters($parameters);

        // Limit - Pagination
        if ($filter->getLimit()) {
            $queryBuilder->setFirstResult($filter->getOffset())->setMaxResults($filter->getLimit());
        }

        return $queryBuilder;
    }

    private function recursiveBuildQuery(array $data, &$qb, Filter $filter, array &$parameters = array(), $field = null, &$i = 0)
    {
        $where = '';
        $_where = array();
        $operator = new Operator();

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                // Handle it myself #TODO Next release will be handled by next recursive iteration
                if (strtoupper($key) == 'GROUPED') {
                    foreach($value as $gk => $gv) {
                        // Set desired format
                        if (!is_array($gv))
                            $gv = explode(',', $gv);

                        foreach($gv as $gvv) {
                            $field = $gk;

                            $_where[] = $filter->getAlias() . '.' . $operator->defaultOperator($field, $gvv) . ':' . $operator->cleanOperators($field) . $i;
                            $parameters[$operator->cleanOperators($field) . $i] = $operator->cleanOperators($gvv, $operator::OPERATOR_VALUE);

                            $i++;
                        }
                    }
                } else {
                    $this->recursiveBuildQuery($value, $qb, $filter, $parameters, $key, $i);
                }
            } else {
                // Get field name (ignore field values's indexes)
                $field = is_numeric($key) ? $field : $key;

                $_where[] = $filter->getAlias() . '.' . $operator->defaultOperator($field, $value) . ':' . $operator->cleanOperators($field) . $i;
                $parameters[$operator->cleanOperators($field) . $i] = $operator->cleanOperators($value, $operator::OPERATOR_VALUE);

                $i++;
            }
        }

        $where .= implode(' OR ', $_where);

        $operators = $filter->getOperators();

        // Field operator
        if ($where) {
            if (isset($operators['field']) && strtoupper($operators['field']) == 'OR') {
                $qb->orWhere($where);
            } else {
                $qb->andWhere($where);
            }
        }
    }
}