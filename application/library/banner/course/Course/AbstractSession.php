<?php
/**
 * @since 11/18/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * This abstract class defines common methods for course sessions.
 *
 * @since 11/18/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
abstract class banner_course_Course_AbstractSession extends banner_course_AbstractSession
{
    private static $requirementTopics_stmt;

    /**
     * Answer the requirement topic ids for a given course id.
     *
     * @param string osid_id_Id $courseId
     *
     * @return array of osid_id_Id objects
     *
     * @since 4/27/09
     */
    public function getRequirementTopicIdsForCourse(osid_id_Id $courseId)
    {
        if (!isset(self::$requirementTopics_stmt)) {
            $query = '
SELECT
	SCRATTR_ATTR_CODE
FROM
	scrattr_recent
WHERE
	SCRATTR_SUBJ_CODE = :subj_code
	AND SCRATTR_CRSE_NUMB = :crse_numb
';
            self::$requirementTopics_stmt = $this->manager->getDB()->prepare($query);
        }

        $parameters = [
            ':subj_code' => $this->getSubjectFromCourseId($courseId),
            ':crse_numb' => $this->getNumberFromCourseId($courseId),
        ];
        self::$requirementTopics_stmt->execute($parameters);
        $topicIds = [];
        while ($row = self::$requirementTopics_stmt->fetch(PDO::FETCH_ASSOC)) {
            $topicIds[] = $this->getOsidIdFromString($row['SCRATTR_ATTR_CODE'], 'topic.requirement.');
        }
        self::$requirementTopics_stmt->closeCursor();

        return $topicIds;
    }

    private static $levelTopics_stmt;

    /**
     * Answer the level topic ids for a given course id.
     *
     * @param string osid_id_Id $courseId
     *
     * @return array of osid_id_Id objects
     *
     * @since 4/27/09
     */
    public function getLevelTopicIdsForCourse(osid_id_Id $courseId)
    {
        if (!isset(self::$levelTopics_stmt)) {
            $query = '
SELECT
	SCRLEVL_LEVL_CODE
FROM
	scrlevl_recent
WHERE
	SCRLEVL_SUBJ_CODE = :subj_code
	AND SCRLEVL_CRSE_NUMB = :crse_numb
';
            self::$levelTopics_stmt = $this->manager->getDB()->prepare($query);
        }

        $parameters = [
            ':subj_code' => $this->getSubjectFromCourseId($courseId),
            ':crse_numb' => $this->getNumberFromCourseId($courseId),
        ];
        self::$levelTopics_stmt->execute($parameters);
        $topicIds = [];
        while ($row = self::$levelTopics_stmt->fetch(PDO::FETCH_ASSOC)) {
            $topicIds[] = $this->getOsidIdFromString($row['SCRLEVL_LEVL_CODE'], 'topic.level.');
        }
        self::$levelTopics_stmt->closeCursor();

        return $topicIds;
    }
}
