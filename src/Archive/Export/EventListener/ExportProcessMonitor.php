<?php

namespace App\Archive\Export\EventListener;

use App\Archive\Export\Event\ExportProgressEvent;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 * Provides access to archive export configuration.
 */
class ExportProcessMonitor
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * Answer the status of a single process by PID.
     */
    public function getProcessStatus(int $pid): ?string
    {
        $db = $this->entityManager->getConnection();

        return $db->fetchOne('SELECT progress FROM archive_export_progress WHERE pid = ?', [$pid]);
    }

    /**
     * Answer all active export processes.
     */
    public function getAllProcesses(): array
    {
        $processes = [];
        $db = $this->entityManager->getConnection();

        return $db->executeQuery('SELECT * FROM archive_export_progress')->fetchAllAssociative();
    }

    /**
     * Update process progress.
     */
    public function updateProcessProgress(int $pid, string $message): void
    {
        $db = $this->entityManager->getConnection();
        try {
            $db->insert(
                'archive_export_progress',
                [
                    'pid' => $pid,
                    'progress' => substr($message, 0, 255), // Trim the message to our column width.
                ],
            );
            $this->logger->info('Beginning archive export process in pid {pid} with message: {message}', ['pid' => $pid, 'message' => $message]);
        } catch (UniqueConstraintViolationException $e) {
            $db->update(
                'archive_export_progress',
                ['progress' => substr($message, 0, 255)], // Trim the message to our column width.
                ['pid' => $pid],
            );
        }
    }

    /**
     * Clear out stale process records for failed jobs.
     *
     * Note, if this service is running in a cluster, this command should only
     * be run on the machine that is actually executing the jobs.
     */
    public function clearStaleProcesses()
    {
        foreach ($this->getAllProcesses() as $process) {
            if (!posix_getpgid($process['pid'])) {
                $this->logger->info('Removing failed archive export process with pid {pid} and message: {message}', [
                    'pid' => $process['pid'],
                    'message' => $process['progress'],
                ]);
                $this->removeProcess($process['pid']);
            }
        }
    }

    /**
     * Remove a process from our listing.
     */
    public function removeProcess(int $pid)
    {
        $db = $this->entityManager->getConnection();
        $db->delete(
            'archive_export_progress',
            ['pid' => $pid],
        );
    }

    #[AsEventListener(event: ExportProgressEvent::class)]
    public function onExportProgress(ExportProgressEvent $event): void
    {
        if ($event->isComplete()) {
            $this->logger->info('Completed archive export process in pid {pid} with message: {message}', [
                'pid' => $event->getPid(),
                'message' => $event->getMessage(),
            ]);
            $this->removeProcess($event->getPid());
        } else {
            $this->updateProcessProgress($event->getPid(), $event->getMessage());
        }
    }
}
