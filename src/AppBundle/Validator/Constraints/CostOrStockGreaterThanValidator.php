<?php
declare(strict_types=1);

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\Product;
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
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof Product) {
            return;
        }

        if (!$constraint instanceof CostOrStockGreaterThan) {
            return;
        }

        $costValue = (float)$value->getCost();
        $stockValue = (float)$value->getStock();
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

    /**
     * Is first number less than second.
     *
     * @param float $value1
     * @param float $value2
     * @return bool
     */
    private function compareValues(float $value1, float $value2): bool
    {
        return $value1 < $value2;
    }
}