<?php

namespace AppBundle\Command;

use AppBundle\Helper\ImportResult;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportCommand extends ContainerAwareCommand
{
    /**
     * Command configuration.
     */
    protected function configure()
    {
        $this
            ->setName('app:import')
            ->setDescription('Import data from CSV file.')
            ->setHelp('This command allows you import data from CSV file into a MySQL database table.')
            ->addArgument('filename', InputArgument::REQUIRED, 'The name of CSV file.')
            ->addArgument('test', InputArgument::OPTIONAL, 'Import in test mode.')
        ;
    }

    /**
     * Execute command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $importer = $this->getContainer()->get('importer.csv');

        $filename = $input->getArgument('filename');

        if (($input->hasArgument('test')) &&
            ($input->getArgument('test') == 'test')) {
                $result = $importer->testPerform($filename);
        } else {
            $result = $importer->perform($filename);
        }

        $this->showResult(new SymfonyStyle($input, $output), $result);
    }

    /**
     * Show result of command.
     *
     * @param SymfonyStyle $io
     * @param $result
     */
    private function showResult(SymfonyStyle $io, $result)
    {
        if ($result instanceof ImportResult) {
            $io->success('Complete.');
            $io->writeln('Processed items: ' . $result->getProcessedCount());
            $io->writeln('Successful: ' . $result->getSuccessfulCount());
            $io->writeln('Skipped: ' . $result->getSkippedCount());

            $io->warning('Skipped rows:');
            $io->text($result->getSkippedItems());
            $io->writeln('');
        } else {
            $io->error('Please, enter correct filename.');
        }
    }
}