<?php
/**
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Service;

use App\Schedule;
use App\Service\Osid\Runtime;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * The Schedules class provides access to a list of user-created schedules.
 *
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Schedules
{
    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security,
        private Runtime $osidRuntime,
    ) {
    }

    /**
     * Create a schedule.
     *
     * @return Schedule
     *
     * @since 8/2/10
     */
    public function createSchedule(\osid_id_Id $termId)
    {
        $stmt = $this->entityManager->getConnection()->prepare('INSERT INTO user_schedules (user_id, term_id_keyword, term_id_authority, term_id_namespace, name) VALUES (?, ?, ?, ?, ?);');
        $name = 'Untitled Schedule';
        $stmt->bindValue(1, $this->getUserIdentifier());
        $stmt->bindValue(2, $termId->getIdentifier());
        $stmt->bindValue(3, $termId->getAuthority());
        $stmt->bindValue(4, $termId->getIdentifierNamespace());
        $stmt->bindValue(5, $name);
        $stmt->executeQuery();
        $id = $this->entityManager->getConnection()->lastInsertId();

        return new Schedule($id, $this->entityManager->getConnection(), $this->getUserIdentifier(), $this->osidRuntime->getCourseManager(), $name, $termId);
    }

    /**
     * Delete a schedule.
     *
     * @param string $scheduleId
     *
     * @return void
     *
     * @since 7/29/10
     */
    public function deleteSchedule($scheduleId)
    {
        $stmt = $this->entityManager->getConnection()->prepare('DELETE FROM user_schedules WHERE id = ? AND user_id = ? LIMIT 1;');
        $stmt->bindValue(1, $scheduleId);
        $stmt->bindValue(2, $this->getUserIdentifier());
        $stmt->executeQuery();
    }

    /**
     * Answer a schedule by Id.
     *
     * @param string $scheduleId
     *
     * @return Schedule
     *
     * @since 8/2/10
     */
    public function getSchedule($scheduleId)
    {
        $stmt = $this->entityManager->getConnection()->prepare('SELECT * FROM user_schedules WHERE id = ? AND user_id = ? LIMIT 1;');
        $stmt->bindValue(1, $scheduleId);
        $stmt->bindValue(2, $this->getUserIdentifier());
        $result = $stmt->executeQuery();
        while (($row = $result->fetchAssociative()) !== false) {
            return new Schedule(
                $row['id'],
                $this->entityManager->getConnection(),
                $this->getUserIdentifier(),
                $this->osidRuntime->getCourseManager(),
                $row['name'],
                new \phpkit_id_Id(
                    $row['term_id_authority'],
                    $row['term_id_namespace'],
                    $row['term_id_keyword'],
                ),
            );
        }
        throw new \InvalidArgumentException('Schedule was not found.');
    }

    /**
     * Answer all schedules for the current user.
     *
     * @return array of Schedule objects
     *
     * @since 8/2/10
     */
    public function getSchedules()
    {
        $stmt = $this->entityManager->getConnection()->prepare('SELECT * FROM user_schedules WHERE user_id = ?;');
        $stmt->bindValue(1, $this->getUserIdentifier());
        $result = $stmt->executeQuery();

        $schedules = [];
        while (($row = $result->fetchAssociative()) !== false) {
            $schedules[] = new Schedule(
                $row['id'],
                $this->entityManager->getConnection(),
                $this->getUserIdentifier(),
                $this->osidRuntime->getCourseManager(),
                $row['name'],
                new \phpkit_id_Id(
                    $row['term_id_authority'],
                    $row['term_id_namespace'],
                    $row['term_id_keyword'],
                ),
            );
        }

        return $schedules;
    }

    /**
     * Answer schedules for the current user for a given term.
     *
     * @return array of Schedule objects
     *
     * @since 8/2/10
     */
    public function getSchedulesByTerm(\osid_id_Id $termId)
    {
        $stmt = $this->entityManager->getConnection()->prepare('SELECT * FROM user_schedules WHERE user_id = ? AND term_id_keyword = ? AND term_id_authority = ? AND term_id_namespace = ?;');
        $stmt->bindValue(1, $this->getUserIdentifier());
        $stmt->bindValue(2, $termId->getIdentifier());
        $stmt->bindValue(3, $termId->getAuthority());
        $stmt->bindValue(4, $termId->getIdentifierNamespace());
        $result = $stmt->executeQuery();

        $schedules = [];
        while (($row = $result->fetchAssociative()) !== false) {
            $schedules[] = new Schedule(
                $row['id'],
                $this->entityManager->getConnection(),
                $this->getUserIdentifier(),
                $this->osidRuntime->getCourseManager(),
                $row['name'],
                new \phpkit_id_Id(
                    $row['term_id_authority'],
                    $row['term_id_namespace'],
                    $row['term_id_keyword'],
                ),
            );
        }

        return $schedules;
    }

    /**
     * Get the ID of the currently authenticated user.
     *
     * @return string
     *                The currently authenticated user's ID
     */
    protected function getUserIdentifier(): string
    {
        $user = $this->security->getUser();
        if (!$user) {
            throw new \Exception('No authenticated user found.');
        }

        return $user->getUserIdentifier();
    }

    /**
     * Answer a saved catalog Id or null.
     *
     * @return osid_id_Id or NULL
     */
    public function getSavedUserCatalogId()
    {
        if (!isset($this->savedCatalogId)) {
            $stmt = $this->entityManager->getConnection()->prepare('SELECT * FROM user_catalog WHERE user_id = ?');
            $stmt->bindValue(1, $this->getUserIdentifier());
            $result = $stmt->executeQuery();
            $row = $result->fetchAssociative();
            if ($row) {
                $this->savedCatalogId = new \phpkit_id_Id(
                    $row['catalog_id_authority'],
                    $row['catalog_id_namespace'],
                    $row['catalog_id_keyword'],
                );
            } else {
                $this->savedCatalogId = null;
            }
        }

        return $this->savedCatalogId;
    }

    /**
     * Set the saved catalog id.
     *
     * @return void
     */
    public function setSavedUserCatalogId(\osid_id_Id $catalogId)
    {
        if (null !== $this->getSavedUserCatalogId() && $catalogId->isEqual($this->getSavedUserCatalogId())) {
            return;
        }

        $insert = $this->entityManager->getConnection()->prepare('INSERT INTO user_catalog (user_id, catalog_id_authority, catalog_id_namespace, catalog_id_keyword) VALUES (?, ?, ?, ?);');
        try {
            $insert->bindValue(1, $this->getUserIdentifier());
            $insert->bindValue(2, $catalogId->getAuthority());
            $insert->bindValue(3, $catalogId->getIdentifierNamespace());
            $insert->bindValue(4, $catalogId->getIdentifier());
            $insert->executeQuery();
        } catch (UniqueConstraintViolationException $e) {
            // Already exists
            $update = $this->entityManager->getConnection()->prepare('UPDATE user_catalog SET catalog_id_authority = ?, catalog_id_namespace = ?, catalog_id_keyword = ? WHERE user_id = ?');
            $update->bindValue(1, $catalogId->getAuthority());
            $update->bindValue(2, $catalogId->getIdentifierNamespace());
            $update->bindValue(3, $catalogId->getIdentifier());
            $update->bindValue(4, $this->getUserIdentifier());
            $update->executeQuery();
        }

        $this->savedCatalogId = $catalogId;
    }
}
