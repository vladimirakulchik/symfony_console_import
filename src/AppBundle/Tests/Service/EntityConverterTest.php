<?php
declare(strict_types=1);

namespace AppBundle\Tests\Service;

use AppBundle\Entity\Product;
use AppBundle\Service\EntityConverter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EntityConverterTest extends KernelTestCase
{
    /**
     * @var EntityConverter
     */
    private $converter;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $stock;

    /**
     * @var string
     */
    private $cost;

    /**
     * @var string
     */
    private $discontinued;

    /**
     * @var string
     */
    private $discontinuedMark;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $this->converter = $container->get('entity.converter');
        $this->name = $container->getParameter('file_product_name');
        $this->description = $container->getParameter('file_product_description');
        $this->code = $container->getParameter('file_product_code');
        $this->stock = $container->getParameter('file_stock');
        $this->cost = $container->getParameter('file_cost');
        $this->discontinued = $container->getParameter('file_discontinued');
        $this->discontinuedMark = $container->getParameter('discontinued_mark');
    }

    public function createProductProvider(): array
    {
        return [
            ['code', 'name', 'desc', '100', '58', ''],
            ['c', 'name1', 'desc1', '200', '8', 'yes'],
            ['757', 'nam', 'description', '', '', '']
        ];
    }

    /**
     * @dataProvider createProductProvider
     * @param string $name
     * @param string $description
     * @param string $code
     * @param string $stock
     * @param string $cost
     * @param string $discontinued
     */
    public function testCreateProduct(
        string $code,
        string $name,
        string $description,
        string $stock,
        string $cost,
        string $discontinued
    ): void {
        $data = [
            $this->code => $code,
            $this->name => $name,
            $this->description => $description,
            $this->stock => $stock,
            $this->cost => $cost,
            $this->discontinued => $discontinued
        ];

        $product = $this->converter->createProduct($data);
        $this->assertInstanceOf(Product::class, $product);

        $this->assertEquals($code, $product->getProductCode());
        $this->assertEquals($name, $product->getProductName());
        $this->assertEquals($description, $product->getProductDesc());
        $this->assertEquals((int)$stock, $product->getStock());
        $this->assertEquals((float)$cost, $product->getCost());
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
