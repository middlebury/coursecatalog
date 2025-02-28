<?php

namespace App\Archive\Export\EventListener;

use App\Archive\Export\Event\ExportProgressEvent;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

class ConsoleOutputListener
{
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    #[AsEventListener(event: ExportProgressEvent::class)]
    public function onExportProgress(ExportProgressEvent $event): void
    {
        if (isset($this->output)) {
            $this->output->writeln($event->getMessage());
        }
    }
}
