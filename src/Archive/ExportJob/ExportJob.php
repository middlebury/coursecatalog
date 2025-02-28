<?php

namespace App\Archive\ExportJob;

use App\Service\Osid\IdMap;
use Doctrine\DBAL\Connection;

/**
 * An Export Job instance.
 */
class ExportJob
{
    public function __construct(
        protected Connection $db,
        protected IdMap $osidIdMap,
        protected int $id,
        protected bool $active,
        protected string $exportPath,
        protected string $terms,
        protected int $configId,
        protected ?int $revisionId = null,
    ) {
    }

    /**
     * Answer the id of this Job.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Answer true if this job is active.
     */
    public function getActive(): bool
    {
        return $this->active;
    }

    /**
     * Set the active state of this job.
     */
    public function setActive(bool $active)
    {
        $this->active = $active;
    }

    /**
     * Answer the export path for this job.
     */
    public function getExportPath(): string
    {
        return $this->exportPath;
    }

    /**
     * Set the export path for this job.
     */
    public function setExportPath(string $exportPath)
    {
        $this->exportPath = $exportPath;
    }

    /**
     * Answer the config id for this job.
     */
    public function getConfigurationId(): int
    {
        return $this->configId;
    }

    /**
     * Set the config id for this job.
     */
    public function setConfigurationId(int $configId)
    {
        $this->configId = $configId;
    }

    /**
     * Answer the revision id for this job or NULL for latest.
     */
    public function getRevisionId(): ?int
    {
        return $this->revisionId;
    }

    /**
     * Set the revision id for this job or NULL for latest.
     */
    public function setRevisionId(?int $revisionId)
    {
        $this->revisionId = $revisionId;
    }

    /**
     * Answer the terms for this job.
     */
    public function getTerms(): string
    {
        return $this->terms;
    }

    /**
     * Set the terms for this job.
     */
    public function setTerms(string $terms)
    {
        $this->terms = $terms;
    }

    /**
     * Answer an array of sorted Term Ids.
     *
     * @return array<\osid_id_Id>
     */
    public function getTermIds()
    {
        $ids = [];
        if (!empty($this->terms)) {
            $idStrings = explode(',', $this->terms);
            sort($idStrings);
            foreach ($idStrings as $idString) {
                $ids[] = $this->osidIdMap->fromString('term-'.$idString);
            }
        }

        return $ids;
    }

    /**
     * Delete this job and all of its revisions.
     */
    public function delete()
    {
        $this->db->executeQuery(
            'DELETE FROM archive_jobs WHERE id = ?',
            [$this->getId()],
        );
    }

    /**
     * Save changes to the object.
     */
    public function save()
    {
        $this->db->executeQuery(
            'UPDATE archive_jobs SET active=:active, export_path=:export_path, config_id=:config_id, revision_id=:revision_id, terms=:terms WHERE id=:id',
            [
                'active' => $this->active ? '1' : '0',
                'export_path' => $this->exportPath,
                'config_id' => $this->configId,
                'revision_id' => $this->revisionId,
                'terms' => $this->terms,
                'id' => $this->id,
            ]
        );
    }
}
