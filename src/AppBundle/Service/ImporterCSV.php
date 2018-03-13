<?php
declare(strict_types=1);

namespace AppBundle\Service;

use AppBundle\Entity\Product;
use AppBundle\Helper\ImportResult;
use Doctrine\ORM\EntityManager;
use Port\Csv\CsvReader;
use Port\Doctrine\DoctrineWriter;
use Symfony\Component\Validator\Validator\RecursiveValidator;

abstract class ImporterCSV
{
    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @var RecursiveValidator
     */
    private $validator;

    /**
     * @var EntityConverter
     */
    protected $converter;

    /**
     * @var CsvReader
     */
    protected $reader;

    /**
     * @var DoctrineWriter
     */
    protected $writer;

    /**
     * @var integer
     */
    private $pendingItemsCount;

    /**
     * @var ImportResult
     */
    protected $result;

    /**
     * ImporterCSV constructor.
     *
     * @param EntityManager $manager
     * @param RecursiveValidator $validator
     * @param EntityConverter $converter
     */
    public function __construct(EntityManager $manager, RecursiveValidator $validator, EntityConverter $converter)
    {
        $this->manager = $manager;
        $this->validator = $validator;
        $this->converter = $converter;
        $this->result = new ImportResult();
    }

    /**
     * Perform process of import.
     *
     * @param \SplFileObject $file
     * @return ImportResult
     */
    abstract public function perform(\SplFileObject $file): ImportResult;

    /**
     * Process data from CSV file.
     */
    abstract protected function processData(): void;

    /**
     * Create CSV Reader.
     *
     * @param \SplFileObject $file
     * @return CsvReader
     */
    protected function createReader(\SplFileObject $file): CsvReader
    {
        $reader = new CsvReader($file);
        $reader->setHeaderRowNumber(0);

        return $reader;
    }

    /**
     * Create writer for database.
     *
     * @return DoctrineWriter
     */
    protected function createWriter(): DoctrineWriter
    {
        $this->pendingItemsCount = 0;

        return new DoctrineWriter($this->manager, Product::class);
    }

    /**
     * Validate product data with constraints.
     *
     * @param Product $product
     * @throws \Exception
     */
    protected function validateProduct(Product $product): void
    {
        $errors = $this->validator->validate($product);
        if (count($errors) > 0) {
            throw new \Exception();
        }
    }

    /**
     * Write product data to database.
     *
     * @param Product $product
     */
    protected function writeProduct(Product $product): void
    {
        $this->writer->writeItem($product->toArray());
        $this->pendingItemsCount++;

        if ($this->pendingItemsCount == 100) {
            $this->writer->flush();
            $this->pendingItemsCount = 0;
        }
    }

    /**
     * Add wrong items to import result.
     */
    protected function addWrongItems(): void
    {
        if ($this->reader->hasErrors()) {
            $this->result->addWrongItems($this->reader->getErrors());
        }
    }
}