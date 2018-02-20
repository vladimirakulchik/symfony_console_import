<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Port\Steps\StepAggregator;
use Port\Csv\CsvReader;
use Port\Doctrine\DoctrineWriter;
use Port\Steps\Step\MappingStep;
use Port\Steps\Step\FilterStep;

class ImporterCSV
{
    const ENTITY_PATH = 'AppBundle\Entity\ProductData';
    const FILE_PRODUCT_NAME = '[Product Name]';
    const ENTITY_PRODUCT_NAME = '[productName]';
    const FILE_PRODUCT_DESCRIPTION = '[Product Description]';
    const ENTITY_PRODUCT_DESC = '[productDesc]';
    const FILE_PRODUCT_CODE = '[Product Code]';
    const ENTITY_PRODUCT_CODE = '[productCode]';
    const FILE_STOCK = '[Stock]';
    const ENTITY_STOCK = '[stock]';
    const FILE_COST = '[Cost in GBP]';
    const ENTITY_COST = '[cost]';
    const FILE_DISCONTINUED = '[Discontinued]';
    const ENTITY_DISCONTINUED = '[dtmDiscontinued]';

    protected $manager;
    protected $validator;

    public function __construct(EntityManager $manager, RecursiveValidator $validator)
    {
        $this->manager = $manager;
        $this->validator = $validator;
    }

    public function import($filename)
    {
        if (!($path = realpath($filename))) {
            print_r('Error');
            return;
        }

        $workflow = $this->createWorkflow($path);
        $workflow->addStep($this->createMappingStep());
        $workflow->addStep($this->createFilterStep());

        try {
            $result = $workflow->process();

            

        } catch (\Exception $e) {
            echo $e;
        }

    }

    private function createWorkflow($path)
    {
        $reader = $this->createReader($path);
        $writer = $this->createWriter();

        $workflow = new StepAggregator($reader);
        $workflow->addWriter($writer);

        return $workflow;
    }

    private function createReader($path)
    {
        $file = new \SplFileObject($path);
        $reader = new CsvReader($file);
        $reader->setHeaderRowNumber(0);

        return $reader;
    }

    private function createWriter()
    {
        return new DoctrineWriter($this->manager, self::ENTITY_PATH);
    }

    private function createMappingStep()
    {
        $mappingStep = new MappingStep();

        $mappingStep->map(self::FILE_PRODUCT_NAME, self::ENTITY_PRODUCT_NAME);
        $mappingStep->map(self::FILE_PRODUCT_DESCRIPTION, self::ENTITY_PRODUCT_DESC);
        $mappingStep->map(self::FILE_PRODUCT_CODE, self::ENTITY_PRODUCT_CODE);
        $mappingStep->map(self::FILE_STOCK, self::ENTITY_STOCK);
        $mappingStep->map(self::FILE_COST, self::ENTITY_COST);
//        $mappingStep->map(self::FILE_DISCONTINUED, self::ENTITY_DISCONTINUED);

        return $mappingStep;
    }

    private function createFilterStep()
    {
        $filterStep = new FilterStep();



        return $filterStep;
    }

}