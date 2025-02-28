<?php

namespace App\Command;

use App\Archive\Export\Message\RunArchiveExportJob;
use App\Archive\ExportJob\ExportJobStorage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:export:enqueue:active',
    description: 'Enqueue all active catalog export jobs by ID, they will be exported by the messenger worker.',
)]
class EnqueuePrintCatalogExportsCommand extends Command
{
    public function __construct(
        private ExportJobStorage $exportJobStorage,
        private MessageBusInterface $messageBus,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $i = 0;
        foreach ($this->exportJobStorage->getAllJobs() as $job) {
            if ($job->getActive()) {
                // Add the job to our queue for processing.
                $this->messageBus->dispatch(new RunArchiveExportJob($job->getId()));
                ++$i;
            }
        }

        $io->success($i.' active jobs queued for export');

        return Command::SUCCESS;
    }
}
