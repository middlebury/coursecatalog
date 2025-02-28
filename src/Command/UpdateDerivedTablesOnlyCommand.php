<?php

namespace App\Command;

use App\Service\CatalogSync\Director;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:update:derived-tables-only',
    description: 'Update the derived tables in the local database.',
)]
class UpdateDerivedTablesOnlyCommand extends Command
{
    public function __construct(
        private Director $director,
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

        $this->director->setOutput($output);
        $this->director->updateDerived();
        $io->success('Derived tables in the local catalog database have been updated.');

        return Command::SUCCESS;
    }
}
