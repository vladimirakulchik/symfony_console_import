<?php
declare(strict_types=1);

namespace AppBundle\Helper;

class ImportResult
{
    /**
     * @var integer
     */
    private $successfulCount;

    /**
     * @var array
     */
    private $skippedItems;

    /**
     * ImportResult constructor.
     */
    public function __construct()
    {
        $this->successfulCount = 0;
        $this->skippedItems = [];
    }

    /**
     * Get count of successful items.
     *
     * @return int
     */
    public function getSuccessfulCount(): int
    {
        return $this->successfulCount;
    }

    /**
     * Get count of skipped items.
     *
     * @return int
     */
    public function getSkippedCount(): int
    {
        return count($this->skippedItems);
    }

    /**
     * Get count of processed items.
     *
     * @return int
     */
    public function getProcessedCount(): int
    {
        return $this->getSuccessfulCount() + $this->getSkippedCount();
    }

    /**
     * Get skipped items.
     *
     * @return array
     */
    public function getSkippedItems(): array
    {
        return $this->skippedItems;
    }

    /**
     * Increment count of successful items.
     */
    public function incrementSuccessfulCount(): void
    {
        $this->successfulCount++;
    }

    /**
     * Add skipped item.
     *
     * @param array $row
     */
    public function addSkippedItem(array $row): void
    {
        if ($row != null) {
            $this->skippedItems[] = implode(',', $row);
        }
    }

    /**
     * Add array of wrong items
     *
     * @param array $errors
     */
    public function addWrongItems(array $errors): void
    {
        foreach ($errors as $row) {
            $this->addSkippedItem($row);
        }
    }
}