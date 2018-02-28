<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\ImportCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ImportCommandTest extends KernelTestCase
{
    const FILE_PATH = "/home/ITRANSITION.CORP/v.akulchik/Documents/Symfony/stock.csv";

    /**
     * @var CommandTester
     */
    private $commandTester;

    private $commandName;

    public function setUp()
    {
        self::bootKernel();
        $application = new Application(self::$kernel);
        $application->add(new ImportCommand());

        $command = $application->find('app:import');
        $this->commandName = $command->getName();
        $this->commandTester = new CommandTester($command);
    }

    public function testExecuteErrorFilenameTestMode()
    {
        $this->executeCommandTestMode('testfile', 'test');

        $output = $this->commandTester->getDisplay();
        $this->assertContains('Please, enter correct filename.', $output);
    }

    public function testExecuteErrorFilename()
    {
        $this->executeCommand('testfile');

        $output = $this->commandTester->getDisplay();
        $this->assertContains('Please, enter correct filename.', $output);
    }

    public function testExecuteTestMode()
    {
        $this->executeCommandTestMode(self::FILE_PATH, 'test');

        $output = $this->commandTester->getDisplay();
        $this->assertContains('Complete.', $output);
        $this->assertContains('Processed items: 29', $output);
    }

    public function testExecute()
    {
        $this->executeCommand(self::FILE_PATH);

        $output = $this->commandTester->getDisplay();
        $this->assertContains('Complete.', $output);
        $this->assertContains('Processed items: ', $output);
    }

    private function executeCommand($filename)
    {
        $this->commandTester->execute(array(
            'command' => $this->commandName,
            'filename' => $filename,
        ));
    }

    private function executeCommandTestMode($filename, $testMode)
    {
        $this->commandTester->execute(array(
            'command' => $this->commandName,
            'filename' => $filename,
            'test' => $testMode,
        ));
    }
}