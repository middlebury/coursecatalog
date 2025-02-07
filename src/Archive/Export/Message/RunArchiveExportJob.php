<?php

namespace App\Archive\Export\Message;

class RunArchiveExportJob
{
    public function __construct(
        private int $jobId,
    ) {
    }

    public function getJobId(): int
    {
        return $this->jobId;
    }
}
