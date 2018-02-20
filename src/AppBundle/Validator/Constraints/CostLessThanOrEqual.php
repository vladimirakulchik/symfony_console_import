<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CostLessThanOrEqual extends Constraint
{
    public $value;
    public $message = 'This value should be less than or equal to {{ compared_value }}.';

    public function getDefaultOption()
    {
        return 'value';
    }
}