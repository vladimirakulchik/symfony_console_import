<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:import')

            // the short description shown while running "php app/console list"
            ->setDescription('Import data from CSV file.')

            // the full command description shown when running the command with the "--help" option
            ->setHelp('This command allows you import data from CSV file into a MySQL database table.')

            ->addArgument('filename', InputArgument::REQUIRED, 'The name of CSV file.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument('filename');

        $service = $this->getContainer()->get('app.import_csv');
        $service->readCsv($filename);
        
    }
}