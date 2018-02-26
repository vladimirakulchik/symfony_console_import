<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\ProductData;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CostOrStockGreaterThanValidator extends ConstraintValidator
{
    /**
     * Checks if the passed value is valid.
     *
     * @param $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof ProductData) {
            return;
        }

        if (!$constraint instanceof CostOrStockGreaterThan) {
            return;
        }

        $costValue = $value->getCost();
        $stockValue = $value->getStock();
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