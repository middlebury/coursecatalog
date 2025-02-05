<?php

namespace App\Archive\Export;

use App\Archive\Export\Event\ExportProgressEvent;
use App\Archive\ExportJob\ExportJob;
use App\Archive\Storage\ArchiveStorage;

/**
 * Generates an Archive of the catalog.
 */
class ArchiveFileManager
{
    public function __construct(
        private ArchiveStorage $archiveStorage,
    ) {
    }

    /**
     * Update the archive storage with our new HTML content.
     */
    public function updateArchive(ExportJob $job, string $html)
    {
        $filename = str_replace('/', '-', $job->getExportPath()).'_snapshot-'.date('Y-m-d').'.html';
        $tempDir = $job->getExportPath().'/tmp';
        $tempPath = $tempDir.'/'.$filename;
        $finalPath = $job->getExportPath().'/html/'.$filename;
        $latestLinkName = str_replace('/', '-', $job->getExportPath()).'_latest.html';
        $latestLinkPath = $job->getExportPath().'/'.$latestLinkName;

        $tmpFile = $this->archiveStorage->writeFile(
            $tempPath,
            $html,
        );
        if ($this->archiveStorage->exists($latestLinkPath)) {
            $latestLink = $this->archiveStorage->get($latestLinkPath);
            $diff = trim(shell_exec('diff -w -I generated_date '.escapeshellarg($latestLink->realPath()).' '.escapeshellarg($tmpFile->realPath())));
            if (empty($diff)) {
                // Delete our temporary file and directory.
                $this->archiveStorage->delete($tempPath);
                $this->archiveStorage->delete($tempDir);
                $this->eventDispatcher->dispatch(new ExportProgressEvent(
                    $job,
                    getmypid(),
                    'Export finished. New version is the same as the last.',
                    0,
                    0,
                    true,
                ));

                return;
            }
        } else {
            // Move our temporary file to our html directory and update
            $this->archiveStorage->rename($tempPath, $finalPath, true);
            // Update our symbolic link to point at the new file.
            $this->archiveStorage->makeLink($latestLinkPath, 'html/'.$filename);
            // Delete our temporary directory.
            $this->archiveStorage->delete($tempDir);

            $this->eventDispatcher->dispatch(new ExportProgressEvent(
                $job,
                getmypid(),
                'Export finished. A new version has been stored at '.$finalPath,
                0,
                0,
                true,
            ));

            return;
        }
    }
}
