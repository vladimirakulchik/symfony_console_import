<?php
declare(strict_types=1);

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    /**
     * @return Product
     */
    public function test__construct(): Product
    {
        $product = new Product();
        $this->assertInstanceOf(Product::class, $product);

        return $product;
    }

    /**
     * @depends test__construct
     * @param Product $product
     */
    public function testToArray(Product $product): void
    {
        $array = $product->toArray();
        $this->assertInternalType('array', $array);
    }
}
