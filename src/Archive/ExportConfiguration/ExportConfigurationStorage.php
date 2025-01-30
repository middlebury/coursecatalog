<?php

namespace App\Archive\ExportConfiguration;

use App\Service\Osid\IdMap;
use App\Service\Osid\Runtime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * Provides access to archive export configuration.
 */
class ExportConfigurationStorage
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Runtime $osidRuntime,
        private IdMap $osidIdMap,
        private Security $security,
    ) {
    }

    /**
     * Answer all export configurations.
     *
     * @return array<ExportConfiguration>
     */
    public function getAllConfigurations(): array
    {
        $configurations = [];
        $db = $this->entityManager->getConnection();
        foreach ($db->executeQuery('SELECT * FROM archive_configurations')->fetchAllAssociative() as $row) {
            $configurations[] = new ExportConfiguration(
                $row['id'],
                $row['label'],
                $this->osidIdMap->fromString($row['catalog_id']),
                $db,
                $this->security,
            );
        }

        return $configurations;
    }

    /**
     * Answer a single export configuration.
     */
    public function getConfiguration(int $id): ExportConfiguration
    {
        $db = $this->entityManager->getConnection();
        $row = $db->executeQuery('SELECT * FROM archive_configurations WHERE id=?', [$id])->fetchAssociative();

        return new ExportConfiguration(
            $row['id'],
            $row['label'],
            $this->osidIdMap->fromString($row['catalog_id']),
            $db,
            $this->security,
        );
    }

    /**
     * Create a new export configuration.
     *
     * @param string     $label
     *                              A label for the configuration
     * @param osid_id_Id $catalogId
     *                              The identifier of the catalog this configuration is associated with
     *
     * @return exportConfiguration
     *                             The new configuration
     */
    public function createConfiguration(string $label, \osid_id_Id $catalogId)
    {
        $db = $this->entityManager->getConnection();
        $db->executeQuery(
            'INSERT INTO archive_configurations (id, label, catalog_id) VALUES (NULL,:label,:catalogId)',
            [
                'label' => $label,
                'catalogId' => $this->osidIdMap->toString($catalogId),
            ]
        );

        return $this->getConfiguration($db->lastInsertId());
    }
}
