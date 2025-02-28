<?php

namespace App\Archive\Export\EventListener;

use App\Archive\Export\Event\ExportProgressEvent;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

class ProgressBarListener
{
    public function setProgressBar(ProgressBar $progressBar)
    {
        $this->progressBar = $progressBar;
    }

    #[AsEventListener(event: ExportProgressEvent::class)]
    public function onExportProgress(ExportProgressEvent $event): void
    {
        if (isset($this->progressBar)) {
            // Start the progress bar.
            if (!$this->progressBar->getMaxSteps()) {
                $this->progressBar->start($event->getStep(), $event->getMaxSteps());
            }
            // Update the progress bar total if more items have been added.
            if ($this->progressBar->getMaxSteps() < $event->getMaxSteps()) {
                $this->progressBar->setMaxSteps($event->getMaxSteps());
            }
            // Advance our progress.
            if ($event->getStep() > $this->progressBar->getProgress()) {
                $this->progressBar->advance();
            }
            $this->progressBar->setMessage($event->getMessage());

            // Complete our progress bar if indicated.
            if ($event->isComplete()) {
                $this->progressBar->finish();
            }
        }
    }
}
