<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CostOrStockGreaterThan extends Constraint
{
    /**
     * @var float
     */
    public $cost;

    /**
     * @var integer
     */
    public $stock;

    public $message = 'Cost less than {{ constraint_cost }} and stock less than {{ constraint_stock }}.';

    /**
     * Returns the name of the default option.
     *
     * @return string
     */
    public function getDefaultOption()
    {
        return 'cost';
    }

    /**
     * Returns the name of the required options.
     *
     * @return array
     */
    public function getRequiredOptions()
    {
        return array('cost', 'stock');
    }

    /**
     * Returns whether the constraint can be put onto classes, properties or
     * both.
     *
     * @return array|string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}