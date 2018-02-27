<?php

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
        $this->skippedItems = array();
    }

    /**
     * Get count of successful items.
     *
     * @return int
     */
    public function getSuccessfulCount()
    {
        return $this->successfulCount;
    }

    /**
     * Get count of skipped items.
     *
     * @return int
     */
    public function getSkippedCount()
    {
        return count($this->skippedItems);
    }

    /**
     * Get count of processed items.
     *
     * @return int
     */
    public function getProcessedCount()
    {
        return $this->getSuccessfulCount() + $this->getSkippedCount();
    }

    /**
     * Get skipped items.
     *
     * @return array
     */
    public function getSkippedItems()
    {
        return $this->skippedItems;
    }

    /**
     * Increment count of successful items.
     */
    public function incrementSuccessfulCount()
    {
        $this->successfulCount++;
    }

    /**
     * Add skipped item.
     *
     * @param array $row
     */
    public function addSkippedItem($row)
    {
        if ($row != null) {
            $this->skippedItems[] = implode(",", $row);
        }
    }

    /**
     * Add array of wrong items.
     *
     * @param array $errors
     */
    public function addWrongItems($errors)
    {
        foreach ($errors as $row) {
            $this->addSkippedItem($row);
        }
    }
}