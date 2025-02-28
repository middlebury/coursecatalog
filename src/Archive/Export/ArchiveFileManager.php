<?php

namespace App\Archive\Export;

use App\Archive\Export\Event\ExportProgressEvent;
use App\Archive\ExportJob\ExportJob;
use App\Archive\Storage\ArchiveFileInterface;
use App\Archive\Storage\ArchiveStorage;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Generates an Archive of the catalog.
 */
class ArchiveFileManager
{
    /**
     * The date used in filenames for writing the latest export.
     *
     * @var DateTime
     */
    private \DateTime $date;

    public function __construct(
        private ArchiveStorage $archiveStorage,
        private EventDispatcherInterface $eventDispatcher,
    ) {
        $this->date = new \DateTime();
    }

    /**
     * Update the archive storage with our new HTML content.
     */
    public function updateArchive(ExportJob $job, string $html)
    {
        $filename = str_replace('/', '-', $job->getExportPath()).'_snapshot-'.$this->date->format('Y-m-d').'.html';
        $tempDir = $job->getExportPath().'/tmp';
        $tempPath = $tempDir.'/'.$filename;
        $finalPath = $job->getExportPath().'/html/'.$filename;
        $latestLinkName = str_replace('/', '-', $job->getExportPath()).'_latest.html';
        $latestLinkPath = $job->getExportPath().'/'.$latestLinkName;

        $tmpFile = $this->archiveStorage->writeFile(
            $tempPath,
            $html,
        );
        if ($this->archiveStorage->exists($latestLinkPath) && $this->exportFilesAreSame($tmpFile, $this->archiveStorage->get($latestLinkPath))) {
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

    /**
     * Answer true if files are the same except for the generated date.
     *
     * @param App\Archive\Storage\ArchiveFileInterface $file1
     * @param App\Archive\Storage\ArchiveFileInterface $file2
     *
     * @return bool
     */
    public function exportFilesAreSame(ArchiveFileInterface $file1, ArchiveFileInterface $file2)
    {
        $diff = shell_exec('diff -w -I generated_date '.escapeshellarg($file1->realPath()).' '.escapeshellarg($file2->realPath()));
        // Trim off any extra whitespace.
        if ($diff) {
            $diff = trim($diff);
        }

        return empty($diff);
    }

    /**
     * Set the date used for export filenames.
     *
     * @param DateTime $date
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }
}
