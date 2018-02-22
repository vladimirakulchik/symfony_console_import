<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CostOrStockGreaterThanValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if ($value === null) {
            return;
        }

        if (!$constraint instanceof CostOrStockGreaterThan) {
            return;
        }

        $stockValue = $this->context->getRoot()['stock'];

        if ($stockValue === null) {
            return;
        }

        $costValue = (float)$value;
        $stockValue = (float)$stockValue;
        $constraintCost = $constraint->cost;
        $constraintStock = $constraint->stock;

        if ($this->compareValues($costValue, $constraintCost)) {
            if ($this->compareValues($stockValue, $constraintStock)) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ constraint_cost }}', $constraintCost)
                    ->setParameter('{{ constraint_stock }}', $constraintStock)
                    ->addViolation();
            }
        }
    }

    private function compareValues($value1, $value2)
    {
        return $value1 < $value2;
    }

}