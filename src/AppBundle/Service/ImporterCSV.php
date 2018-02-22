<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Port\Steps\StepAggregator;
use Port\Csv\CsvReader;
use Port\Doctrine\DoctrineWriter;
use Port\Steps\Step\MappingStep;
use Port\Steps\Step\ConverterStep;
use Port\Steps\Step\FilterStep;
use Port\Filter\ValidatorFilter;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAssert;
use AppBundle\Converter\DateTimeConverter;

class ImporterCSV
{
    const ENTITY_PATH = 'AppBundle\Entity\ProductData';
    const ENTITY_PRODUCT_NAME = 'productName';
    const ENTITY_PRODUCT_DESCRIPTION = 'productDesc';
    const ENTITY_PRODUCT_CODE = 'productCode';
    const ENTITY_STOCK = 'stock';
    const ENTITY_COST = 'cost';
    const ENTITY_DISCONTINUED = 'dtmDiscontinued';
    const ENTITY_DTM_ADDED = 'dtmAdded';

    const FILE_PRODUCT_NAME = 'Product Name';
    const FILE_PRODUCT_DESCRIPTION = 'Product Description';
    const FILE_PRODUCT_CODE = 'Product Code';
    const FILE_STOCK = 'Stock';
    const FILE_COST = 'Cost in GBP';
    const FILE_DISCONTINUED = 'Discontinued';

    const MAX_COST = 1000;
    const MIN_COST = 5;
    const MIN_STOCK = 10;

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
        $workflow->addStep($this->createConverterStep());

        $filter = $this->createValidatorFilter();
        $workflow->addStep($this->createFilterStep($filter));

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

        $mappingStep->map(
            $this->getFormatName(self::FILE_PRODUCT_NAME),
            $this->getFormatName(self::ENTITY_PRODUCT_NAME)
        );
        $mappingStep->map(
            $this->getFormatName(self::FILE_PRODUCT_DESCRIPTION),
            $this->getFormatName(self::ENTITY_PRODUCT_DESCRIPTION)
        );
        $mappingStep->map(
            $this->getFormatName(self::FILE_PRODUCT_CODE),
            $this->getFormatName(self::ENTITY_PRODUCT_CODE)
        );
        $mappingStep->map(
            $this->getFormatName(self::FILE_STOCK),
            $this->getFormatName(self::ENTITY_STOCK)
        );
        $mappingStep->map(
            $this->getFormatName(self::FILE_COST),
            $this->getFormatName(self::ENTITY_COST)
        );
        $mappingStep->map(
            $this->getFormatName(self::FILE_DISCONTINUED),
            $this->getFormatName(self::ENTITY_DISCONTINUED)
        );

        return $mappingStep;
    }

    private function getFormatName($name)
    {
        return sprintf('[%s]', $name);
    }

    private function createFilterStep(ValidatorFilter $filter)
    {
        $filterStep = new FilterStep();
        $filterStep->add($filter);

        return $filterStep;
    }

    private function createValidatorFilter()
    {
        $filter = new ValidatorFilter($this->validator);

        $filter->add(self::ENTITY_PRODUCT_NAME, new Assert\NotNull());
        $filter->add(self::ENTITY_PRODUCT_DESCRIPTION, new Assert\NotNull());
        $filter->add(self::ENTITY_PRODUCT_CODE, new Assert\NotNull());
        $filter->add(self::ENTITY_DTM_ADDED, new Assert\DateTime());
        $filter->add(self::ENTITY_DISCONTINUED, new Assert\NotIdenticalTo(''));
        $filter->add(self::ENTITY_STOCK, new Assert\NotNull());
        $filter->add(self::ENTITY_COST, new Assert\NotNull());
        $filter->add(self::ENTITY_COST, new Assert\LessThanOrEqual(self::MAX_COST));
        $filter->add(self::ENTITY_COST, new AppAssert\CostOrStockGreaterThan(array(
            'cost' => self::MIN_COST,
            'stock' => self::MIN_STOCK,
        )));

        return $filter;
    }

    private function createConverterStep()
    {
        $converterStep = new ConverterStep();
        $converterStep->add(new DateTimeConverter());

        return $converterStep;
    }
}