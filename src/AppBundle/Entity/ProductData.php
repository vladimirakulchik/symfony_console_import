<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @AppAssert\CostOrStockGreaterThan(cost = 5, stock = 10)
 *
 * @ORM\Table(name="tblProductData",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="strProductCode", columns={"strProductCode"})})
 * @ORM\Entity
 *
 * @UniqueEntity("productCode")
 */
class ProductData
{
    const FILE_PRODUCT_NAME = 'Product Name';
    const FILE_PRODUCT_DESCRIPTION = 'Product Description';
    const FILE_PRODUCT_CODE = 'Product Code';
    const FILE_STOCK = 'Stock';
    const FILE_COST = 'Cost in GBP';
    const FILE_DISCONTINUED = 'Discontinued';
    const DISCONTINUED_MARK = 'yes';
    
    /**
     * @var integer
     *
     * @ORM\Column(name="intProductDataId", type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $productDataId;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="strProductName", type="string", length=50, nullable=false)
     */
    public $productName;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="strProductDesc", type="string", length=255, nullable=false)
     */
    private $productDesc;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="strProductCode", type="string", length=10, nullable=false)
     */
    private $productCode;

    /**
     * @var \DateTime
     *
     * @Assert\DateTime()
     *
     * @ORM\Column(name="dtmAdded", type="datetime", nullable=true)
     */
    private $dtmAdded;

    /**
     * @var \DateTime
     *
     * @Assert\NotIdenticalTo("")
     *
     * @ORM\Column(name="dtmDiscontinued", type="datetime", nullable=true)
     */
    private $dtmDiscontinued;

    /**
     * @Assert\IsNull()
     *
     * @ORM\Column(name="stmTimestamp", type="datetime", nullable=false)
     * @ORM\Version
     */
    private $timestamp;

    /**
     * @var integer
     *
     * @Assert\NotNull()
     * @Assert\GreaterThan(0)
     *
     * @ORM\Column(name="intStock", type="integer", precision=12, options={"unsigned"=true}, nullable=false)
     */
    private $stock;

    /**
     * @var float
     *
     * @Assert\NotNull()
     * @Assert\LessThanOrEqual(1000)
     *
     * @ORM\Column(name="decCost", type="decimal", precision=12, scale=4, nullable=false)
     */
    private $cost;

    /**
     * ProductData constructor.
     */
    public function __construct()
    {
    }

    /**
     * ProductData constructor.
     *
     * @param array $data
     * @return ProductData
     */
    public static function create($data)
    {
        $product = new self();
        $product->productName = $data[self::FILE_PRODUCT_NAME];
        $product->productDesc = $data[self::FILE_PRODUCT_DESCRIPTION];
        $product->productCode = $data[self::FILE_PRODUCT_CODE];
        $product->dtmAdded = new \DateTime();

        $product->stock = is_numeric($data[self::FILE_STOCK]) ? (integer)$data[self::FILE_STOCK] : null;
        $product->cost = is_numeric($data[self::FILE_COST]) ? (float)$data[self::FILE_COST] : null;
        $product->dtmDiscontinued =
            $data[self::FILE_DISCONTINUED] == self::DISCONTINUED_MARK ? new \DateTime() : null;

        return $product;
    }

    /**
     * Convert object to array.
     *
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }

    /**
     * @return mixed
     */
    public function getProductDataId()
    {
        return $this->productDataId;
    }

    /**
     * @return string
     */
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * @param string $productName
     */
    public function setProductName($productName)
    {
        $this->productName = $productName;
    }

    /**
     * @return string
     */
    public function getProductDesc()
    {
        return $this->productDesc;
    }

    /**
     * @param string $productDesc
     */
    public function setProductDesc($productDesc)
    {
        $this->productDesc = $productDesc;
    }

    /**
     * @return string
     */
    public function getProductCode()
    {
        return $this->productCode;
    }

    /**
     * @param string $productCode
     */
    public function setProductCode($productCode)
    {
        $this->productCode = $productCode;
    }

    /**
     * @return \DateTime
     */
    public function getDtmAdded()
    {
        return $this->dtmAdded;
    }

    /**
     * @param \DateTime $dtmAdded
     */
    public function setDtmAdded($dtmAdded)
    {
        $this->dtmAdded = $dtmAdded;
    }

    /**
     * @return \DateTime
     */
    public function getDtmDiscontinued()
    {
        return $this->dtmDiscontinued;
    }

    /**
     * @param \DateTime $dtmDiscontinued
     */
    public function setDtmDiscontinued($dtmDiscontinued)
    {
        $this->dtmDiscontinued = $dtmDiscontinued;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return int
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     */
    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    /**
     * @return float
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param float $cost
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }
}
