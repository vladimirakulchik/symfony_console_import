<?php
declare(strict_types=1);

namespace AppBundle\Tests\Helper;

use AppBundle\Helper\ImportResult;
use PHPUnit\Framework\TestCase;

class ImportResultTest extends TestCase
{
    /**
     * @return ImportResult
     */
    public function test__construct(): ImportResult
    {
        $result = new ImportResult();
        $this->assertInstanceOf(ImportResult::class, $result);

        return $result;
    }

    /**
     * @depends test__construct
     * @param ImportResult $result
     */
    public function testGetSuccessfulCount(ImportResult $result): void
    {
        $this->assertEquals(0, $result->getSuccessfulCount());
    }

    /**
     * @depends test__construct
     * @param ImportResult $result
     */
    public function testGetSkippedCount(ImportResult $result): void
    {
        $this->assertEquals(0, $result->getSkippedCount());
    }

    /**
     * @depends test__construct
     * @param ImportResult $result
     */
    public function testGetProcessedCount(ImportResult $result): void
    {
        $this->assertEquals(0, $result->getProcessedCount());
    }

    /**
     * @depends test__construct
     * @param ImportResult $result
     */
    public function testGetSkippedItems(ImportResult $result): void
    {
        $items = $result->getSkippedItems();
        $this->assertInternalType('array', $items);
        $this->assertEmpty($items);
    }

    public function testIncrementSuccessfulCount(): void
    {
        $result = new ImportResult();
        $count = $result->getSuccessfulCount();
        $result->incrementSuccessfulCount();
        $count++;
        $this->assertEquals($count, $result->getSuccessfulCount());
    }

    public function testAddSkippedItem(): void
    {
        $result = new ImportResult();
        $this->assertEquals(0, $result->getSkippedCount());
        $item = ['error'];
        $result->addSkippedItem($item);
        $this->assertEquals(count($item), $result->getSkippedCount());
        $this->assertEquals($item, $result->getSkippedItems());
    }

    public function testAddWrongItems(): void
    {
        $result = new ImportResult();
        $this->assertEquals(0, $result->getSkippedCount());
        $items = [['error'], ['test']];
        $result->addWrongItems($items);
        $this->assertEquals(count($items), $result->getSkippedCount());

        $errors = $result->getSkippedItems();
        $this->assertEquals($items[0][0], $errors[0]);
        $this->assertEquals($items[1][0], $errors[1]);
    }
}
