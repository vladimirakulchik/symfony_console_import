<?php
declare(strict_types=1);

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductDataTest extends TestCase
{
    const PRODUCT_CODE = 'TEST';
    const PRODUCT_NAME = 'Test product';
    const PRODUCT_DESCRIPTION = 'Cool product';
    const PRODUCT_STOCK = '10';
    const PRODUCT_COST = '222';
    const PRODUCT_DISCONTINUED = 'yes';

    public function testCreateFromData()
    {
        $data = [
            Product::FILE_PRODUCT_CODE => self::PRODUCT_CODE,
            Product::FILE_PRODUCT_NAME => self::PRODUCT_NAME,
            Product::FILE_PRODUCT_DESCRIPTION => self::PRODUCT_DESCRIPTION,
            Product::FILE_STOCK => self::PRODUCT_STOCK,
            Product::FILE_COST => self::PRODUCT_COST,
            Product::FILE_DISCONTINUED => self::PRODUCT_DISCONTINUED
        ];

        $product = Product::create($data);
        $this->assertInstanceOf('AppBundle\Entity\Product', $product);

        return $product;
    }

    /**
     * @depends testCreateFromData
     * @param Product $product
     */
    public function testGetters(Product $product)
    {
        $this->assertEquals(self::PRODUCT_CODE, $product->getProductCode());
        $this->assertEquals(self::PRODUCT_NAME, $product->getProductName());
        $this->assertEquals(self::PRODUCT_DESCRIPTION, $product->getProductDesc());
        $this->assertEquals((int)self::PRODUCT_STOCK, $product->getStock());
        $this->assertEquals((float)self::PRODUCT_COST, $product->getCost());
        $this->assertInstanceOf('DateTime', $product->getDtmDiscontinued());
    }
    /**
     * @depends testCreateFromData
     * @param Product $product
     */
    public function testToArray(Product $product)
    {
        $data = $product->toArray();
        $this->assertInternalType('array', $data);
    }
}