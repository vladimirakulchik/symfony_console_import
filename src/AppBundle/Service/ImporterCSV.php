<?php

namespace AppBundle\Service;

use AppBundle\Entity\ProductData;
use AppBundle\Helper\ImportResult;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Doctrine\ORM\EntityManager;
use Port\Csv\CsvReader;
use Port\Doctrine\DoctrineWriter;

class ImporterCSV
{
    const ENTITY_PATH = 'AppBundle\Entity\ProductData';

    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @var RecursiveValidator
     */
    private $validator;

    /**
     * @var CsvReader
     */
    private $reader;

    /**
     * @var DoctrineWriter
     */
    private $writer;

    /**
     * @var ImportResult
     */
    private $result;

    /**
     * ImporterCSV constructor.
     *
     * @param EntityManager $manager
     * @param RecursiveValidator $validator
     */
    public function __construct(EntityManager $manager, RecursiveValidator $validator)
    {
        $this->manager = $manager;
        $this->validator = $validator;
        $this->result = new ImportResult();
    }

    /**
     * Perform import data.
     *
     * @param $filename
     * @return ImportResult|null
     */
    public function perform($filename)
    {
        if (!($path = realpath($filename))) {
            return null;
        }

        $this->reader = $this->createReader($path);
        $this->writer = $this->createWriter();
        $this->writer->prepare();
        $this->importData();
        $this->writer->finish();
        $this->addWrongItems();

        return $this->result;
    }

    /**
     * Validate and import data to database.
     */
    private function importData()
    {
        foreach ($this->reader as $row) {
            try {
                $product = ProductData::create($row);
                $this->validateProduct($product);
                $this->writeProduct($product);
                $this->result->incrementSuccessfulCount();
            } catch (\Exception $e) {
                $this->result->addSkippedItem($row);
            }
        }
    }

    /**
     * Perform import data in test mode.
     *
     * @param $filename
     * @return ImportResult|null
     */
    public function testPerform($filename)
    {
        if (!($path = realpath($filename))) {
            return null;
        }

        $this->reader = $this->createReader($path);
        $this->testImportData();
        $this->addWrongItems();

        return $this->result;
    }

    /**
     * Validate and import data to database in test mode.
     */
    private function testImportData()
    {
        foreach ($this->reader as $row) {
            try {
                $product = ProductData::create($row);
                $this->validateProduct($product);
                $this->result->incrementSuccessfulCount();
            } catch (\Exception $e) {
                $this->result->addSkippedItem($row);
            }
        }
    }

    /**
     * Create CSV Reader.
     *
     * @param string $path
     * @return CsvReader
     */
    private function createReader($path)
    {
        $file = new \SplFileObject($path);
        $reader = new CsvReader($file);
        $reader->setHeaderRowNumber(0);

        return $reader;
    }

    /**
     * Create writer for database.
     *
     * @return DoctrineWriter
     */
    private function createWriter()
    {
        return new DoctrineWriter($this->manager, self::ENTITY_PATH);
    }

    /**
     * Validate product data with constraints.
     *
     * @param ProductData $product
     * @throws \Exception
     */
    private function validateProduct(ProductData $product)
    {
        $errors = $this->validator->validate($product);
        if (count($errors) > 0) {
            throw new \Exception();
        }
    }

    /**
     * Write product data to database.
     *
     * @param ProductData $product
     */
    private function writeProduct(ProductData $product)
    {
        $this->writer->writeItem($product->toArray());
        $this->writer->flush();
    }

    /**
     * Add wrong items to import result.
     */
    private function addWrongItems()
    {
        if ($this->reader->hasErrors()) {
            $this->result->addWrongItems($this->reader->getErrors());
        }
    }
}