<?php
/**
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Service;

use App\Service\Osid\Runtime;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * The Bookmarks class provides access to a list of courses bookmarked by a given
 * user.
 *
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Bookmarks
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
     * Add a bookmark.
     *
     * @return void
     */
    public function add(\osid_id_Id $courseId)
    {
        $stmt = $this->entityManager->getConnection()->prepare('INSERT INTO user_savedcourses (user_id, course_id_keyword, course_id_authority, course_id_namespace) VALUES (?, ?, ?, ?);');
        try {
            $stmt->bindValue(1, $this->getUserIdentifier());
            $stmt->bindValue(2, $courseId->getIdentifier());
            $stmt->bindValue(3, $courseId->getAuthority());
            $stmt->bindValue(4, $courseId->getIdentifierNamespace());
            $stmt->executeQuery();
        } catch (UniqueConstraintViolationException $e) {
            throw new \Exception('Bookmark already added.', 23000, $e);
        }
    }

    /**
     * Remove a bookmark.
     *
     * @return void
     */
    public function remove(\osid_id_Id $courseId)
    {
        $stmt = $this->entityManager->getConnection()->prepare('DELETE FROM user_savedcourses WHERE user_id = ? AND course_id_keyword = ? AND course_id_authority = ? AND course_id_namespace = ? LIMIT 1;');
        $stmt->bindValue(1, $this->getUserIdentifier());
        $stmt->bindValue(2, $courseId->getIdentifier());
        $stmt->bindValue(3, $courseId->getAuthority());
        $stmt->bindValue(4, $courseId->getIdentifierNamespace());
        $stmt->executeQuery();
    }

    /**
     * Answer true if the course Id passed is bookmarked.
     */
    public function isBookmarked(\osid_id_Id $courseId): bool
    {
        $stmt = $this->entityManager->getConnection()->prepare('SELECT COUNT(*) as is_bookmarked FROM user_savedcourses WHERE user_id = ? AND course_id_keyword = ? AND course_id_authority = ? AND course_id_namespace = ?');
        $stmt->bindValue(1, $this->getUserIdentifier());
        $stmt->bindValue(2, $courseId->getIdentifier());
        $stmt->bindValue(3, $courseId->getAuthority());
        $stmt->bindValue(4, $courseId->getIdentifierNamespace());
        $result = $stmt->executeQuery();
        $num = (int) $result->fetchOne();

        return $num > 0;
    }

    /**
     * Answer an array of all bookmarked courseIds.
     *
     * @return []
     */
    public function getAllBookmarkedCourseIds(): array
    {
        $stmt = $this->entityManager->getConnection()->prepare('SELECT * FROM user_savedcourses WHERE user_id = ?');
        $stmt->bindValue(1, $this->getUserIdentifier());
        $result = $stmt->executeQuery();
        $ids = [];
        while (($row = $result->fetchAssociative()) !== false) {
            $ids[] = new \phpkit_id_Id($row['course_id_authority'], $row['course_id_namespace'], $row['course_id_keyword']);
        }

        return $ids;
    }

    /**
     * Answer an array of all bookmarked courses.
     */
    public function getAllBookmarkedCourses(): array
    {
        $courseIdList = $this->getAllBookmarkedCourseIds();
        if (!$courseIdList->hasNext()) {
            return new \phpkit_course_ArrayCourseList([]);
        }

        $courseLookupSession = $this->osidRuntime->getCourseManager()->getCourseLookupSession();
        $courseLookupSession->useFederatedCourseCatalogView();

        $courseList = $courseLookupSession->getCoursesByIds($courseIdList);
        $courses = [];
        while ($courseList->hasNext()) {
            $courses[] = $courseList->getNextCourse();
        }

        return $courses;
    }

    /**
     * Answer an array of all bookmarked courses that match a given catalog and term.
     */
    public function getBookmarkedCoursesInCatalogForTerm(\osid_id_Id $catalogId, \osid_id_Id $termId): array
    {
        $courseIdList = $this->getAllBookmarkedCourseIds();
        if (empty($courseIdList)) {
            return [];
        }

        $searchSession = $this->osidRuntime->getCourseManager()->getCourseSearchSessionForCatalog($catalogId);

        $search = $searchSession->getCourseSearch();
        $search->searchAmongCourses(new \phpkit_id_ArrayIdList($courseIdList));

        $query = $searchSession->getCourseQuery();
        $record = $query->getCourseQueryRecord(new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:term'));
        $record->matchTermId($termId, true);

        // Limit to just active courses
        $query->matchGenusType(new \phpkit_type_URNInetType('urn:inet:middlebury.edu:status-active'), true);

        $results = $searchSession->getCoursesBySearch($query, $search);

        $courseList = $results->getCourses();
        $courses = [];
        while ($courseList->hasNext()) {
            $courses[] = $courseList->getNextCourse();
        }

        return $courses;
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
}
