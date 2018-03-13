<?php
declare(strict_types=1);

namespace AppBundle\Service;

use AppBundle\Helper\ImportResult;

class ImporterTestCSV extends ImporterCSV
{
    /**
     * Perform process of import.
     *
     * @param \SplFileObject $file
     * @return ImportResult
     */
    public function perform(\SplFileObject $file): ImportResult
    {
        $this->reader = $this->createReader($file);
        $this->processData();

        return $this->result;
    }

    /**
     * Process data form CSV file.
     */
    protected function processData(): void
    {
        foreach ($this->reader as $row) {
            try {
                $product = $this->converter->createProduct($row);
                $this->validateProduct($product);
                $this->result->incrementSuccessfulCount();
            } catch (\Exception $e) {
                $this->result->addSkippedItem($row);
            }
        }

        $this->addWrongItems();
    }
}