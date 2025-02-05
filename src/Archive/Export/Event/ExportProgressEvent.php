<?php

namespace App\Archive\Export\Event;

use App\Archive\ExportJob\ExportJob;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Generates an Archive of the catalog.
 */
class ExportProgressEvent extends Event
{
    public function __construct(
        private ExportJob $job,
        private ?int $pid,
        private string $message,
        private int $step = 0,
        private int $maxSteps = 0,
        private bool $isComplete = false,
    ) {
    }

    public function getJob(): ExportJob
    {
        return $this->job;
    }

    public function getPid(): ?int
    {
        return $this->pid;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getStep(): int
    {
        return $this->step;
    }

    public function getMaxSteps(): int
    {
        return $this->maxSteps;
    }

    public function isComplete(): bool
    {
        return $this->isComplete;
    }
}
