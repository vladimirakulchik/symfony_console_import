<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CostOrStockGreaterThan extends Constraint
{
    public $cost;
    public $stock;
    public $message = 'Cost less than {{ constraint_cost }} and stock less than {{ constraint_stock }}.';

    public function getDefaultOption()
    {
        return 'cost';
    }

    public function getRequiredOptions()
    {
        return array('cost', 'stock');
    }
}