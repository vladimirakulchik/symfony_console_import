<?php
declare(strict_types=1);

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
     * @var SymfonyStyle
     */
    private $io;

    /**
     * Command configuration.
     */
    protected function configure(): void
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
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);

        $filename = $input->getArgument('filename');

        try {
            $file = new \SplFileObject($filename);
        } catch (\Exception $e) {
            $this->showFilenameError();
            return;
        }

        if (($input->hasArgument('test')) &&
            ($input->getArgument('test') == 'test')) {
            $importer = $this->getContainer()->get('importer.csv.test');
        } else {
            $importer = $this->getContainer()->get('importer.csv');
        }

        $result = $importer->perform($file);
        $this->showResult($result);
    }

    /**
     * Show result of command.
     *
     * @param ImportResult $result
     */
    private function showResult(ImportResult $result): void
    {
        $this->io->success('Complete.');
        $this->io->writeln('Processed items: ' . $result->getProcessedCount());
        $this->io->writeln('Successful: ' . $result->getSuccessfulCount());
        $this->io->writeln('Skipped: ' . $result->getSkippedCount());

        $this->io->warning('Skipped rows:');
        $this->io->text($result->getSkippedItems());
        $this->io->writeln('');
    }

    /**
     * Show filename error message.
     */
    private function showFilenameError(): void
    {
        $this->io->error('Please, enter correct filename.');
    }
}