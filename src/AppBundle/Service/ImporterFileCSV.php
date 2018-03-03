<?php
declare(strict_types=1);

namespace AppBundle\Service;

use AppBundle\Helper\ImportResult;

class ImporterFileCSV extends ImporterCSV
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
        $this->writer = $this->createWriter();
        $this->writer->prepare();
        $this->processData();
        $this->writer->finish();

        return $this->result;
    }

    /**
     * Process data from CSV file.
     */
    protected function processData(): void
    {
        foreach ($this->reader as $row) {
            try {
                $product = $this->converter->createProduct($row);
                $this->validateProduct($product);
                $this->writeProduct($product);
                $this->result->incrementSuccessfulCount();
            } catch (\Exception $e) {
                $this->result->addSkippedItem($row);
            }
        }

        $this->addWrongItems();
    }
}