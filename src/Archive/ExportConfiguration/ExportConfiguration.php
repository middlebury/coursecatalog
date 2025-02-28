<?php

namespace App\Archive\ExportConfiguration;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * An Export configuration instance.
 */
class ExportConfiguration
{
    public function __construct(
        protected int $id,
        protected string $label,
        protected \osid_id_Id $catalogId,
        protected Connection $db,
        private Security $security,
    ) {
    }

    /**
     * Answer the id of this configuration.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Answer the label of this configuration.
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Answer the catalog id this configuration is associated with.
     *
     * @return osid_id_Id
     */
    public function getCatalogId(): \osid_id_Id
    {
        return $this->catalogId;
    }

    /**
     * Answer all export configuration revisions.
     *
     * @return array<App\Archive\ExportConfiguration\ExportConfigurationRevision>
     */
    public function getAllRevisions(): array
    {
        $revisions = [];
        $result = $this->db->executeQuery(
            'SELECT * FROM archive_configuration_revisions WHERE arch_conf_id = ? ORDER BY last_saved DESC',
            [$this->getId()]
        )->fetchAllAssociative();
        foreach ($result as $row) {
            $revisions[] = $this->revisionFromRow($row);
        }

        return $revisions;
    }

    /**
     * Answer a single export configuration revision.
     *
     * @return App\Archive\ExportConfiguration\ExportConfigurationRevision
     */
    public function getRevision(int $id): ExportConfigurationRevision
    {
        $row = $this->db->executeQuery(
            'SELECT * FROM archive_configuration_revisions WHERE arch_conf_id = ? AND id=?',
            [$this->getId(), $id]
        )->fetchAssociative();
        if (false === $row) {
            throw new \Exception('No revision was found with the id.');
        }

        return $this->revisionFromRow($row);
    }

    /**
     * Answer the latest export configuration revision.
     *
     * @return App\Archive\ExportConfiguration\ExportConfigurationRevision
     */
    public function getLatestRevision(): ExportConfigurationRevision
    {
        $row = $this->db->executeQuery('SELECT * FROM archive_configuration_revisions WHERE arch_conf_id = ? ORDER BY last_saved DESC, id DESC LIMIT 1', [$this->getId()])->fetchAssociative();
        if (false === $row) {
            throw new \Exception('There are no revisions of this configuration.');
        }

        return $this->revisionFromRow($row);
    }

    /**
     * Helper function to convert a database row into a revision object.
     *
     * @param array $row
     *                   The database row from the archive_configuration_revisions table
     *
     * @return App\Archive\ExportConfiguration\ExportConfigurationRevision
     */
    protected function revisionFromRow(array $row)
    {
        return new ExportConfigurationRevision(
            $row['id'],
            json_decode($row['json_data'], true),
            $row['note'],
            new \DateTime($row['last_saved']),
            $row['user_id'],
            $row['user_disp_name'],
        );
    }

    /**
     * Create a new export configuration revision.
     *
     * @return exportConfigurationRevision
     *                                     The new configuration revision
     */
    public function createRevision(array $content, string $note)
    {
        $user = $this->security->getUser();
        $this->db->executeQuery(
            'INSERT INTO archive_configuration_revisions (
                `arch_conf_id`,
                `note`,
                `last_saved`,
                `user_id`,
                `user_disp_name`,
                `json_data`
            )
            VALUES (
              :configId,
              :note,
              CURRENT_TIMESTAMP,
              :userId,
              :userDN,
              :jsonData)',
            [
                'configId' => $this->getId(),
                'note' => $note,
                'userId' => $user->getUserIdentifier(),
                'userDN' => $user->getName(),
                'jsonData' => json_encode($content, \JSON_PRETTY_PRINT),
            ]
        );

        return $this->getRevision($this->db->lastInsertId());
    }

    /**
     * Delete this configuration and all of its revisions.
     */
    public function delete()
    {
        // Delete revisions that depend on this config.
        $this->db->executeQuery(
            'DELETE FROM archive_configuration_revisions WHERE arch_conf_id = ?',
            [$this->getId()],
        );
        $this->db->executeQuery(
            'DELETE FROM archive_configurations WHERE id = ?',
            [$this->getId()],
        );
    }
}
