<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends ContainerAwareCommand
{
    // Configuration command.
    protected function configure()
    {
        $this
            ->setName('app:import')

            // Short description.
            ->setDescription('Import data from CSV file.')

            // Full command description.
            ->setHelp('This command allows you import data from CSV file into a MySQL database table.')

            ->addArgument('filename', InputArgument::REQUIRED, 'The name of CSV file.')
        ;
    }

    // Execute command.
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument('filename');

        $importer = $this->getContainer()->get('importer.csv');
        $importer->import($filename);

    }
}