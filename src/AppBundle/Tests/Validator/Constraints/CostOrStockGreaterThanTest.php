<?php
declare(strict_types=1);

namespace AppBundle\Tests\Validator\Constraints;

use AppBundle\Validator\Constraints\CostOrStockGreaterThan;
use PHPUnit\Framework\TestCase;

class CostOrStockGreaterThanTest extends TestCase
{
    public function testGetDefaultOption(): void
    {
        $constraint = new CostOrStockGreaterThan(['cost' => 5, 'stock' => 10]);
        $defaultOption = $constraint->getDefaultOption();
        $this->assertEquals('cost', $defaultOption);
    }

    public function testGetRequiredOptions(): void
    {
        $options = ['cost', 'stock'];
        $constraint = new CostOrStockGreaterThan(['cost' => 5, 'stock' => 10]);
        $requiredOptions = $constraint->getRequiredOptions();
        $this->assertEquals($options, $requiredOptions);
    }

    public function testGetTargets(): void
    {
        $constraint = new CostOrStockGreaterThan(['cost' => 5, 'stock' => 10]);
        $targets = $constraint->getTargets();
        $this->assertEquals('class', $targets);
    }
}
