<?php

namespace App\Command;

use App\Service\Osid\Runtime;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:update:build-indices',
    description: 'Build search indices in the local database.',
)]
class BuildIndiciesCommand extends Command
{
    public function __construct(
        private Runtime $osidRuntime,
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

        $courseManager = $this - osidRuntime->getCourseManager();

        if (!$courseManager->supportsCourseOfferingSearch()) {
            $io->error('CourseOfferingSearch is unsupported. Not building indices.');

            return Command::FAILURE;
        }

        $searchSession = $courseManager->getCourseOfferingSearchSession();
        if (!method_exists($searchSession, 'buildIndex')) {
            $io->error('CourseOfferingSearch does not support the buildIndex() method. Not building indices.');

            return Command::FAILURE;
        }

        $minMemory = '300M';
        $minBytes = $this->asBytes($minMemory);
        $currentBytes = $this->asBytes(ini_get('memory_limit'));
        if ($currentBytes < $minBytes) {
            ini_set('memory_limit', $minMemory);
        }

        $searchSession->buildIndex(true);

        $io->success('Indices have be rebuilt in the local database.');

        return Command::SUCCESS;
    }

    private function asBytes($val)
    {
        $val = trim($val);
        $num = (int) preg_replace('/^([0-9]+)(.*)$/', '', $val);
        $last = strtolower($val[strlen($val) - 1]);
        switch ($last) {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
                $num *= 1024;
                // no break
            case 'm':
                $num *= 1024;
                // no break
            case 'k':
                $num *= 1024;
        }

        return $num;
    }
}
