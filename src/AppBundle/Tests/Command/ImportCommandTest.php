<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\ImportCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ImportCommandTest extends KernelTestCase
{
    public function testExecuteErrorFilename()
    {
        self::bootKernel();
        $application = new Application(self::$kernel);
        $application->add(new ImportCommand());
        $command = $application->find('app:import');

        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'filename' => 'testfile',
        ));

        $output = $commandTester->getDisplay();
        $this->assertContains('Please, enter correct filename.', $output);
    }

    public function testExecuteTestMode()
    {
        self::bootKernel();
        $application = new Application(self::$kernel);
        $application->add(new ImportCommand());
        $command = $application->find('app:import');

        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'filename' => "/home/ITRANSITION.CORP/v.akulchik/Documents/Symfony/stock.csv",
            'test' => 'test',
        ));

        $output = $commandTester->getDisplay();
        $this->assertContains('Complete.', $output);
    }
}