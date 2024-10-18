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
     * @param string $userId
     *
     * @return void
     *
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
     *
     */
    public function add(\osid_id_Id $courseId)
    {
        $stmt = $this->entityManager->getConnection()->prepare('INSERT INTO user_savedcourses (user_id, course_id_keyword, course_id_authority, course_id_namespace) VALUES (?, ?, ?, ?);');
        try {
            $stmt->executeQuery([
                $this->getUserIdentifier(),
                $courseId->getIdentifier(),
                $courseId->getAuthority(),
                $courseId->getIdentifierNamespace(),
            ]);
        } catch (UniqueConstraintViolationException $e) {
            throw new \Exception('Bookmark already added.', 23000, $e);
        }
    }

    /**
     * Remove a bookmark.
     *
     * @return void
     *
     */
    public function remove(\osid_id_Id $courseId)
    {
        $stmt = $this->entityManager->getConnection()->prepare('DELETE FROM user_savedcourses WHERE user_id = ? AND course_id_keyword = ? AND course_id_authority = ? AND course_id_namespace = ? LIMIT 1;');
        $stmt->executeQuery([
            $this->getUserIdentifier(),
            $courseId->getIdentifier(),
            $courseId->getAuthority(),
            $courseId->getIdentifierNamespace(),
        ]);
    }

    /**
     * Answer true if the course Id passed is bookmarked.
     *
     * @return bool
     *
     */
    public function isBookmarked(\osid_id_Id $courseId)
    {
        $stmt = $this->entityManager->getConnection()->prepare('SELECT COUNT(*) as is_bookmarked FROM user_savedcourses WHERE user_id = ? AND course_id_keyword = ? AND course_id_authority = ? AND course_id_namespace = ?');
        $result = $stmt->executeQuery([
            $this->getUserIdentifier(),
            $courseId->getIdentifier(),
            $courseId->getAuthority(),
            $courseId->getIdentifierNamespace(),
        ]);
        $num = (int) $result->fetchOne();

        return $num > 0;
    }

    /**
     * Answer an array of all bookmarked courseIds.
     *
     * @return \osid_id_IdList[]
     *
     */
    public function getAllBookmarkedCourseIds()
    {
        $stmt = $this->entityManager->getConnection()->prepare('SELECT * FROM user_savedcourses WHERE user_id = ?');
        $result = $stmt->executeQuery([
            $this->getUserIdentifier(),
        ]);
        $ids = [];
        foreach ($stmt->fetchAll() as $row) {
            $ids[] = new \phpkit_id_Id($row['course_id_authority'], $row['course_id_namespace'], $row['course_id_keyword']);
        }

        return new \phpkit_id_ArrayIdList($ids);
    }

    /**
     * Answer an array of all bookmarked courses.
     *
     * @return \osid_course_CourseList
     *
     */
    public function getAllBookmarkedCourses()
    {
        $courseIdList = $this->getAllBookmarkedCourseIds();
        if (!$courseIdList->hasNext()) {
            return new \phpkit_course_ArrayCourseList([]);
        }

        $courseLookupSession = $this->courseManager->getCourseLookupSession();
        $courseLookupSession->useFederatedCourseCatalogView();

        return $courseLookupSession->getCoursesByIds($courseIdList);
    }

    /**
     * Answer an array of all bookmarked courses that match a given catalog and term.
     *
     * @return \osid_course_CourseList
     *
     */
    public function getBookmarkedCoursesInCatalogForTerm(\osid_id_Id $catalogId, \osid_id_Id $termId)
    {
        $courseIdList = $this->getAllBookmarkedCourseIds();
        if (!$courseIdList->hasNext()) {
            return new \phpkit_course_ArrayCourseList([]);
        }

        $searchSession = $this->courseManager->getCourseSearchSessionForCatalog($catalogId);

        $search = $searchSession->getCourseSearch();
        $search->searchAmongCourses($courseIdList);

        $query = $searchSession->getCourseQuery();
        $record = $query->getCourseQueryRecord(new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:term'));
        $record->matchTermId($termId, true);

        // Limit to just active courses
        $query->matchGenusType(new \phpkit_type_URNInetType('urn:inet:middlebury.edu:status-active'), true);

        $results = $searchSession->getCoursesBySearch($query, $search);

        return $results->getCourses();
    }

    protected function getUserIdentifier() {
        $user = $this->security->getUser();
        if (!$user) {
            throw new \Exception("No authenticated user found.");
        }
        return $user->getUserIdentifier();
    }
}
