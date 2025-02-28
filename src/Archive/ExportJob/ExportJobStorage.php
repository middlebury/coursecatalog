<?php

namespace App\Archive\ExportJob;

use App\Service\Osid\IdMap;
use App\Service\Osid\Runtime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * Provides access to archive export Job.
 */
class ExportJobStorage
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Runtime $osidRuntime,
        private IdMap $osidIdMap,
        private Security $security,
    ) {
    }

    /**
     * Answer all export Jobs.
     *
     * @return array<ExportJob>
     */
    public function getAllJobs(): array
    {
        $jobs = [];
        $db = $this->entityManager->getConnection();
        foreach ($db->executeQuery('SELECT * FROM archive_jobs ORDER BY id DESC')->fetchAllAssociative() as $row) {
            $jobs[] = new ExportJob(
                $db,
                $this->osidIdMap,
                $row['id'],
                $row['active'],
                $row['export_path'],
                $row['terms'],
                $row['config_id'],
                $row['revision_id'],
            );
        }

        return $jobs;
    }

    /**
     * Answer a single export job.
     */
    public function getJob(int $id): ExportJob
    {
        $db = $this->entityManager->getConnection();
        $row = $db->executeQuery('SELECT * FROM archive_jobs WHERE id=?', [$id])->fetchAssociative();
        if (!$row) {
            throw new \InvalidArgumentException('Unknown job.');
        }

        return new ExportJob(
            $db,
            $this->osidIdMap,
            $row['id'],
            $row['active'],
            $row['export_path'],
            $row['terms'],
            $row['config_id'],
            $row['revision_id'],
        );
    }

    /**
     * Create a new export job.
     *
     * @param string $exportPath
     *                           The relative path of archives for this job
     * @param int    $configId
     *                           The identifier of the configuration that this job is associated with
     * @param int? $revisionId
     *   The identifier of a revision to export. NULL for the latest revision.
     * @param string $terms
     *                       A comma-separated list of terms to export
     * @param bool   $active
     *                       True if this job is active
     *
     * @return exportJob
     *                   The new job
     */
    public function createJob(string $exportPath, int $configId, ?int $revisionId, string $terms, bool $active = true)
    {
        $db = $this->entityManager->getConnection();
        $db->executeQuery(
            'INSERT INTO archive_jobs (active, export_path, config_id, revision_id, terms) VALUES (:active, :export_path, :config_id, :revision_id, :terms)',
            [
                'active' => $active,
                'export_path' => $exportPath,
                'config_id' => $configId,
                'revision_id' => $revisionId,
                'terms' => $terms,
            ]
        );

        return $this->getJob($db->lastInsertId());
    }
}
