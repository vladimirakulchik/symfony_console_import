<?php
declare(strict_types=1);

namespace AppBundle\Tests\Command;

use AppBundle\Command\ImportCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ImportCommandTest extends KernelTestCase
{
    const CORRECT_FILE_PATH = './stock.csv';
    const ERROR_FILENAME = './testfile.csv';
    const TEST_MODE_ARGUMENT = 'test';
    const ERROR_FILENAME_MESSAGE = 'Please, enter correct filename.';
    const COMPLETE_MESSAGE = 'Complete.';
    const PROCESSED_ITEMS_MESSAGE = 'Processed items: ';
    const SUCCESSFUL_MESSAGE = 'Successful: ';
    const SKIPPED_MESSAGE = 'Skipped: ';

    /**
     * @var CommandTester
     */
    private $commandTester;

    /**
     * @var string
     */
    private $commandName;

    public function setUp(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);
        $application->add(new ImportCommand());

        $command = $application->find('app:import');
        $this->commandName = $command->getName();
        $this->commandTester = new CommandTester($command);
    }

    public function testExecuteErrorFilenameTestMode(): void
    {
        $this->executeCommandTestMode(self::ERROR_FILENAME, self::TEST_MODE_ARGUMENT);

        $output = $this->commandTester->getDisplay();
        $this->assertContains(self::ERROR_FILENAME_MESSAGE, $output);
    }

    public function testExecuteErrorFilename(): void
    {
        $this->executeCommand(self::ERROR_FILENAME);

        $output = $this->commandTester->getDisplay();
        $this->assertContains(self::ERROR_FILENAME_MESSAGE, $output);
    }

    public function testExecuteCorrectFilenameTestMode(): void
    {
        $this->executeCommandTestMode(self::CORRECT_FILE_PATH, self::TEST_MODE_ARGUMENT);

        $output = $this->commandTester->getDisplay();
        $this->assertContains(self::COMPLETE_MESSAGE, $output);
        $this->assertContains(self::PROCESSED_ITEMS_MESSAGE, $output);
        $this->assertContains(self::SUCCESSFUL_MESSAGE, $output);
        $this->assertContains(self::SKIPPED_MESSAGE, $output);
    }

    public function testExecuteCorrectFilename(): void
    {
        $this->executeCommand(self::CORRECT_FILE_PATH);

        $output = $this->commandTester->getDisplay();
        $this->assertContains(self::COMPLETE_MESSAGE, $output);
        $this->assertContains(self::PROCESSED_ITEMS_MESSAGE, $output);
        $this->assertContains(self::SUCCESSFUL_MESSAGE, $output);
        $this->assertContains(self::SKIPPED_MESSAGE, $output);
    }

    private function executeCommand($filename): void
    {
        $this->commandTester->execute([
            'command' => $this->commandName,
            'filename' => $filename,
        ]);
    }

    private function executeCommandTestMode($filename, $testMode): void
    {
        $this->commandTester->execute([
            'command' => $this->commandName,
            'filename' => $filename,
            self::TEST_MODE_ARGUMENT => $testMode,
        ]);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
