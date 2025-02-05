<?php

namespace App\Command;

use App\Archive\Export\EventListener\ProgressBarListener;
use App\Archive\Export\Exporter;
use App\Archive\ExportJob\ExportJobStorage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:export:single',
    description: 'Export a single catalog archive by ID.',
)]
class ExportPrintCatalogCommand extends Command
{
    public function __construct(
        private Exporter $exporter,
        private ExportJobStorage $exportJobStorage,
        private ProgressBarListener $consoleEventListener,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('job-id', InputArgument::REQUIRED, 'The id of the job to export.')
            ->addOption('memory-limit', null, InputOption::VALUE_REQUIRED, 'A memory limit to use for this operation.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if (!empty($input->getOption('memory-limit'))) {
            // Set a memory limit for this operation if one is configured.
            // This can allow this upgrade script to consume more memory than other operations.
            ini_set('memory_limit', $input->getOption('memory-limit'));
        }

        // Set up our output for monitoring events.
        $progressBar = new ProgressBar($output, 1);
        ProgressBar::setFormatDefinition('custom', " %current%/%max% [%bar%] %percent:3s%% \n %message%");
        $progressBar->setFormat('custom');
        $this->consoleEventListener->setProgressBar($progressBar);

        $this->exporter->generateForJob(
            $this->exportJobStorage->getJob($input->getArgument('job-id'))
        );
        $io->success('The catalog archive has been exported.');

        return Command::SUCCESS;
    }
}
