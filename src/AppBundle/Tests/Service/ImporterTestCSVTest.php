<?php
declare(strict_types=1);

namespace AppBundle\Tests\Service;

use AppBundle\Helper\ImportResult;
use AppBundle\Service\ImporterTestCSV;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ImporterTestCSVTest extends KernelTestCase
{
    const FILE_PATH = '/home/ITRANSITION.CORP/v.akulchik/Documents/Symfony/stock.csv';

    /**
     * @var ImporterTestCSV
     */
    private $importerFile;

    public function setUp(): void
    {
        self::bootKernel();
        $this->importerFile = self::$kernel->getContainer()->get('importer.csv.test');
    }

    public function testPerform(): void
    {
        $file = new \SplFileObject(self::FILE_PATH);
        $result = $this->importerFile->perform($file);
        $this->assertInstanceOf(ImportResult::class, $result);
        $this->assertGreaterThan(0, $result->getProcessedCount());
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
