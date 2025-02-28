<?php

namespace App\Command;

use App\Archive\Export\Message\RunArchiveExportJob;
use App\Archive\ExportJob\ExportJobStorage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:export:enqueue:single',
    description: 'Enqueue a single catalog export job by ID, it will be exported by the messenger worker.',
)]
class EnqueuePrintCatalogExportCommand extends Command
{
    public function __construct(
        private ExportJobStorage $exportJobStorage,
        private MessageBusInterface $messageBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('job-id', InputArgument::REQUIRED, 'The id of the job to export.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Verify that our job exists.
        $job = $this->exportJobStorage->getJob($input->getArgument('job-id'));
        // Add the job to our queue for processing.
        $this->messageBus->dispatch(new RunArchiveExportJob($job->getId()));

        $io->success('Job '.$input->getArgument('job-id').' is queued for export.');

        return Command::SUCCESS;
    }
}
