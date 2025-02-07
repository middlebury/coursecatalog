<?php

namespace App\Command;

use App\Archive\ExportJob\ExportJobStorage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:export:active',
    description: 'Export active catalog archive jobs.',
)]
class ExportPrintCatalogsCommand extends Command
{
    public function __construct(
        private ExportJobStorage $exportJobStorage,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
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

        $status = Command::SUCCESS;
        foreach ($this->exportJobStorage->getAllJobs() as $job) {
            if ($job->getActive()) {
                // Sub commands run in the same process so we don't need to pass
                // the memory-limit option as we've already set it.
                $jobInput = new ArrayInput([
                    // the command name is passed as first argument
                    'command' => 'app:export:single',
                    'job-id' => $job->getId(),
                ]);
                $jobInput->setInteractive(false);
                $returnCode = $this->getApplication()->doRun($jobInput, $output);
                if (Command::SUCCESS != $returnCode) {
                    $status = $returnCode;
                }
            }
        }

        return $status;
    }
}
