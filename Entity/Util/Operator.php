<?php
/**
 * Created by PhpStorm.
 * User: l3yr0y
 * Date: 11/9/15
 * Time: 8:51 AM
 */

namespace L3yIncubator\SearchBundle\Entity\Util;


class Operator extends OperatorHandler
{
    public function defaultOperator($field, $value)
    {
        if (!$this->getOperator($field)) {
            return !is_object($value) ? "$field LIKE " : "$field = ";
        }

        return $field;
    }
}