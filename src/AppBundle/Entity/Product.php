<?php
declare(strict_types=1);

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
class Product
{
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
    private $productName;

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
     * @var string
     *
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
     * @Assert\GreaterThan(0)
     * @Assert\LessThanOrEqual(1000)
     *
     * @ORM\Column(name="decCost", type="decimal", precision=12, scale=4, nullable=false)
     */
    private $cost;

    /**
     * Product constructor.
     */
    public function __construct()
    {
    }

    /**
     * Convert object to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * @return int
     */
    public function getProductDataId(): int
    {
        return $this->productDataId;
    }

    /**
     * @return string
     */
    public function getProductName():? string
    {
        return $this->productName;
    }

    /**
     * @param string $productName
     */
    public function setProductName(string $productName): void
    {
        $this->productName = $productName;
    }

    /**
     * @return string
     */
    public function getProductDesc():? string
    {
        return $this->productDesc;
    }

    /**
     * @param string $productDesc
     */
    public function setProductDesc(string $productDesc): void
    {
        $this->productDesc = $productDesc;
    }

    /**
     * @return string
     */
    public function getProductCode():? string
    {
        return $this->productCode;
    }

    /**
     * @param string $productCode
     */
    public function setProductCode(string $productCode): void
    {
        $this->productCode = $productCode;
    }

    /**
     * @return \DateTime|null
     */
    public function getDtmAdded():? \DateTime
    {
        return $this->dtmAdded;
    }

    /**
     * @param \DateTime $dtmAdded
     */
    public function setDtmAdded(\DateTime $dtmAdded): void
    {
        $this->dtmAdded = $dtmAdded;
    }

    /**
     * @return \DateTime|null
     */
    public function getDtmDiscontinued():? \DateTime
    {
        return $this->dtmDiscontinued;
    }

    /**
     * @param \DateTime $dtmDiscontinued
     */
    public function setDtmDiscontinued(\DateTime $dtmDiscontinued): void
    {
        $this->dtmDiscontinued = $dtmDiscontinued;
    }

    /**
     * @return null|string
     */
    public function getTimestamp():? string
    {
        return $this->timestamp;
    }

    /**
     * @param string $timestamp
     */
    public function setTimestamp(string $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return int|null
     */
    public function getStock():? int
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    /**
     * @return float|null
     */
    public function getCost():? float
    {
        return $this->cost;
    }

    /**
     * @param float $cost
     */
    public function setCost(float $cost): void
    {
        $this->cost = $cost;
    }
}
