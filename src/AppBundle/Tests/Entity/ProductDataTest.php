<?php

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\ProductData;
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
        $data = array(
            ProductData::FILE_PRODUCT_CODE => self::PRODUCT_CODE,
            ProductData::FILE_PRODUCT_NAME => self::PRODUCT_NAME,
            ProductData::FILE_PRODUCT_DESCRIPTION => self::PRODUCT_DESCRIPTION,
            ProductData::FILE_STOCK => self::PRODUCT_STOCK,
            ProductData::FILE_COST => self::PRODUCT_COST,
            ProductData::FILE_DISCONTINUED => self::PRODUCT_DISCONTINUED
        );

        $product = ProductData::create($data);
        $this->assertInstanceOf('AppBundle\Entity\ProductData', $product);

        return $product;
    }

    /**
     * @depends testCreateFromData
     * @param ProductData $product
     */
    public function testGetters(ProductData $product)
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
     * @param ProductData $product
     */
    public function testToArray(ProductData $product)
    {
        $data = $product->toArray();
        $this->assertInternalType('array', $data);
    }
}