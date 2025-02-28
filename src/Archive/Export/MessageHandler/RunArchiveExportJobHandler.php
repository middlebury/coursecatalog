<?php

namespace App\Archive\Export\MessageHandler;

use App\Archive\Export\ArchiveFileManager;
use App\Archive\Export\ArchiveHtmlGenerator;
use App\Archive\Export\Message\RunArchiveExportJob;
use App\Archive\ExportJob\ExportJobStorage;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class RunArchiveExportJobHandler implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(
        private ArchiveHtmlGenerator $archiveHtmlGenerator,
        private ArchiveFileManager $archiveFileManager,
        private ExportJobStorage $exportJobStorage,
    ) {
    }

    public function __invoke(RunArchiveExportJob $runArchiveExportJob)
    {
        $job = $this->exportJobStorage->getJob($runArchiveExportJob->getJobId());
        $this->archiveFileManager->updateArchive(
            $job,
            $this->archiveHtmlGenerator->generateHtmlForJob($job),
        );
    }
}
