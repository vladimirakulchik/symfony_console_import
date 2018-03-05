<?php

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\Product;

class ProductTest extends \PHPUnit_Framework_TestCase
{
    public function test__construct()
    {
        $product = new Product();
        $this->assertInstanceOf(Product::class, $product);

        return $product;
    }

    /**
     * @depends test__construct
     * @param Product $product
     */
    public function testToArray(Product $product)
    {
        $array = $product->toArray();
        $this->assertInternalType('array', $array);
    }
}
