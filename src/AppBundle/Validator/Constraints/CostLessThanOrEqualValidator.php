<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CostLessThanOrEqualValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if ($value['cost'] === null) {
            return;
        }

        if (!$constraint instanceof CostLessThanOrEqual) {
            return;
        }

        $cost = (float)$value['cost'];
        $comparedValue = (float)$constraint->value;

        if (!$this->compareValues($cost, $comparedValue)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ compared_value }}', $comparedValue)
                ->addViolation();
        }
    }

    private function compareValues($value1, $value2)
    {
        return $value1 <= $value2;
    }

}