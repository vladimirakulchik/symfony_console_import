<?php
declare(strict_types=1);

namespace AppBundle\Service;

use AppBundle\Entity\Product;

class EntityConverter
{
    private $name;
    private $description;
    private $code;
    private $stock;
    private $cost;
    private $discontinued;
    private $discontinuedMark;

    /**
     * EntityConverter constructor.
     *
     * @param $name
     * @param $description
     * @param $code
     * @param $stock
     * @param $cost
     * @param $discontinued
     * @param $discontinuedMark
     */
    public function __construct(
        string $name,
        string $description,
        string $code,
        string $stock,
        string $cost,
        string $discontinued,
        string $discontinuedMark
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->code = $code;
        $this->stock = $stock;
        $this->cost = $cost;
        $this->discontinued = $discontinued;
        $this->discontinuedMark = $discontinuedMark;
    }

    /**
     * Product constructor.
     *
     * @param array $data
     * @return Product
     */
    public function createProduct(array $data): Product
    {
        $product = new Product();

        $product->setProductName($data[$this->name]);
        $product->setProductDesc($data[$this->description]);
        $product->setProductCode($data[$this->code]);
        $product->setDtmAdded(new \DateTime());

        if (is_numeric($data[$this->stock])) {
            $product->setStock((integer)$data[$this->stock]);
        }

        if (is_numeric($data[$this->cost])) {
            $product->setCost((float)$data[$this->cost]);
        }

        if ($data[$this->discontinued] == $this->discontinuedMark) {
            $product->setDtmDiscontinued(new \DateTime());
        }

        return $product;
    }
}