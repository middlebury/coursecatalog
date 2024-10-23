<?php
/**
 * @since 4/16/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * This interface defines a few methods to allow course offering objects to get back to
 * other data from sessions such as terms and courses.
 *
 * @since 4/16/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
abstract class banner_course_CourseOffering_AbstractSession extends banner_course_AbstractSession implements banner_course_CourseOffering_SessionInterface
{
    /**
     * Answer a list of instructors for the course offering id passed.
     *
     * @return osid_id_IdList
     *
     * @since 4/30/09
     */
    public function getInstructorIdsForOffering(osid_id_Id $offeringId)
    {
        $ids = [];
        foreach ($this->getInstructorDataForOffering($offeringId) as $row) {
            $ids[] = $this->getOsidIdFromString($row['WEB_ID'], 'resource.person.');
        }

        return new phpkit_id_ArrayIdList($ids);
    }

    /**
     * Answer a list of instructors for the course offering id passed.
     *
     * @return osid_resource_ResourceList
     *
     * @since 4/30/09
     */
    public function getInstructorsForOffering(osid_id_Id $offeringId)
    {
        $people = [];
        foreach ($this->getInstructorDataForOffering($offeringId) as $row) {
            $people[] = new banner_resource_Resource_Person(
                $this->getOsidIdFromString($row['WEB_ID'], 'resource.person.'),
                $row['SYVINST_LAST_NAME'],
                $row['SYVINST_FIRST_NAME']
            );
        }

        return new phpkit_resource_ArrayResourceList($people);
    }

    /**
     * Answer the instructor data rows for an offering id.
     *
     * @return array
     *
     * @since 5/1/09
     */
    private function getInstructorDataForOffering(osid_id_Id $offeringId)
    {
        $stmt = $this->getInstructorsForOfferingStatment();
        $stmt->execute([
            ':term_code' => $this->getTermCodeFromOfferingId($offeringId),
            ':crn' => $this->getCrnFromOfferingId($offeringId),
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private static $instructorsForOffering_stmt;

    /**
     * Answer the instructors statement.
     *
     * @return PDOStatement
     *
     * @since 5/1/09
     */
    private function getInstructorsForOfferingStatment()
    {
        if (!isset(self::$instructorsForOffering_stmt)) {
            $query = '
SELECT
	WEB_ID,
	SYVINST_LAST_NAME,
	SYVINST_FIRST_NAME,
	SIRASGN_PRIMARY_IND
FROM
	SYVINST
	INNER JOIN SIRASGN ON SIRASGN_PIDM = SYVINST_PIDM
WHERE
	SYVINST_TERM_CODE = :term_code
	AND SIRASGN_TERM_CODE = :term_code
	AND SYVINST_CRN = :crn
	AND SIRASGN_CRN = :crn
ORDER BY
	SIRASGN_PRIMARY_IND DESC, SYVINST_LAST_NAME, SYVINST_FIRST_NAME
';
            self::$instructorsForOffering_stmt = $this->manager->getDB()->prepare($query);
        }

        return self::$instructorsForOffering_stmt;
    }

    private static $alternatesForOffering_stmt;

    /**
     * Answer the alternate course-offering ids.
     *
     * @return PDOStatement
     *
     * @since 5/1/09
     */
    public function getAlternateIdsForOffering(osid_id_Id $offeringId)
    {
        if (!isset(self::$alternatesForOffering_stmt)) {
            $query = '
SELECT
	*
FROM
	SSRXLST
WHERE
	SSRXLST_XLST_GROUP IN
		(SELECT
			SSRXLST_XLST_GROUP
		FROM
			SSRXLST
		WHERE
			SSRXLST_TERM_CODE = :term_code
			AND SSRXLST_CRN = :crn
		)
	AND SSRXLST_TERM_CODE = :term_code_2
	AND SSRXLST_CRN != :crn_2
';
            self::$alternatesForOffering_stmt = $this->manager->getDB()->prepare($query);
        }
        self::$alternatesForOffering_stmt->execute([
            ':term_code' => $this->getTermCodeFromOfferingId($offeringId),
            ':crn' => $this->getCrnFromOfferingId($offeringId),
            ':term_code_2' => $this->getTermCodeFromOfferingId($offeringId),
            ':crn_2' => $this->getCrnFromOfferingId($offeringId),
        ]);
        $rows = self::$alternatesForOffering_stmt->fetchAll(PDO::FETCH_ASSOC);
        self::$alternatesForOffering_stmt->closeCursor();

        $ids = [];
        foreach ($rows as $row) {
            $ids[] = $this->getOfferingIdFromTermCodeAndCrn($row['SSRXLST_TERM_CODE'], $row['SSRXLST_CRN']);
        }

        return new phpkit_id_ArrayIdList($ids);
    }

    private static $requirementTopics_stmt;

    /**
     * Answer the requirement topic ids for a given course offering id.
     *
     * @param string osid_id_Id $courseOfferingId
     *
     * @return array of osid_id_Id objects
     *
     * @since 4/27/09
     */
    public function getRequirementTopicIdsForCourseOffering(osid_id_Id $courseOfferingId)
    {
        if (!isset(self::$requirementTopics_stmt)) {
            $query = '
SELECT
	SSRATTR_ATTR_CODE
FROM
	SSRATTR
WHERE
	SSRATTR_TERM_CODE = :term_code
	AND SSRATTR_CRN = :crn
';
            self::$requirementTopics_stmt = $this->manager->getDB()->prepare($query);
        }

        $parameters = [
            ':term_code' => $this->getTermCodeFromOfferingId($courseOfferingId),
            ':crn' => $this->getCrnFromOfferingId($courseOfferingId),
        ];
        self::$requirementTopics_stmt->execute($parameters);
        $topicIds = [];
        while ($row = self::$requirementTopics_stmt->fetch(PDO::FETCH_ASSOC)) {
            $topicIds[] = $this->getOsidIdFromString($row['SSRATTR_ATTR_CODE'], 'topic.requirement.');
        }
        self::$requirementTopics_stmt->closeCursor();

        return $topicIds;
    }

    private static $levelTopics_stmt;

    /**
     * Answer the level topic ids for a given course offering id.
     *
     * @param string osid_id_Id $courseOfferingId
     *
     * @return array of osid_id_Id objects
     *
     * @since 4/27/09
     */
    public function getLevelTopicIdsForCourseOffering(osid_id_Id $courseOfferingId)
    {
        if (!isset(self::$levelTopics_stmt)) {
            $query = '
SELECT
	SCRLEVL_LEVL_CODE
FROM
	SSBSECT
	INNER JOIN scrlevl_recent ON (SSBSECT_SUBJ_CODE = SCRLEVL_SUBJ_CODE AND SSBSECT_CRSE_NUMB = SCRLEVL_CRSE_NUMB)
WHERE
	SSBSECT_TERM_CODE = :term_code
	AND SSBSECT_CRN = :crn
';
            self::$levelTopics_stmt = $this->manager->getDB()->prepare($query);
        }

        $parameters = [
            ':term_code' => $this->getTermCodeFromOfferingId($courseOfferingId),
            ':crn' => $this->getCrnFromOfferingId($courseOfferingId),
        ];
        self::$levelTopics_stmt->execute($parameters);
        $topicIds = [];
        while ($row = self::$levelTopics_stmt->fetch(PDO::FETCH_ASSOC)) {
            $topicIds[] = $this->getOsidIdFromString($row['SCRLEVL_LEVL_CODE'], 'topic.level.');
        }
        self::$levelTopics_stmt->closeCursor();

        return $topicIds;
    }

    private static $blockTopics_stmt;

    /**
     * Answer the block topic ids for a given course offering id.
     *
     * @param string osid_id_Id $courseOfferingId
     *
     * @return array of osid_id_Id objects
     *
     * @since 4/27/09
     */
    public function getBlockTopicIdsForCourseOffering(osid_id_Id $courseOfferingId)
    {
        if (!isset(self::$blockTopics_stmt)) {
            $query = '
SELECT
	SSRBLCK_BLCK_CODE
FROM
	SSRBLCK
WHERE
	SSRBLCK_TERM_CODE = :term_code
	AND SSRBLCK_CRN = :crn
';
            self::$blockTopics_stmt = $this->manager->getDB()->prepare($query);
        }

        $parameters = [
            ':term_code' => $this->getTermCodeFromOfferingId($courseOfferingId),
            ':crn' => $this->getCrnFromOfferingId($courseOfferingId),
        ];
        self::$blockTopics_stmt->execute($parameters);
        $topicIds = [];
        while ($row = self::$blockTopics_stmt->fetch(PDO::FETCH_ASSOC)) {
            $topicIds[] = $this->getOsidIdFromString($row['SSRBLCK_BLCK_CODE'], 'topic.block.');
        }
        self::$blockTopics_stmt->closeCursor();

        return $topicIds;
    }

    private static $meetingRows_stmt;

    /**
     * Answer all meeting rows for a course offering.
     *
     * @param string osid_id_Id $courseOfferingId
     *
     * @return array
     *
     * @since 6/10/09
     */
    public function getCourseOfferingMeetingRows(osid_id_Id $courseOfferingId)
    {
        if (!isset(self::$meetingRows_stmt)) {
            $query = '
SELECT
	SSRMEET_BLDG_CODE,
	SSRMEET_ROOM_CODE,
	SSRMEET_BEGIN_TIME,
	SSRMEET_END_TIME,
	SSRMEET_SUN_DAY,
	SSRMEET_MON_DAY,
	SSRMEET_TUE_DAY,
	SSRMEET_WED_DAY,
	SSRMEET_THU_DAY,
	SSRMEET_FRI_DAY,
	SSRMEET_SAT_DAY,
	SSRMEET_START_DATE,
	SSRMEET_END_DATE,
	STVBLDG_DESC
FROM
	SSRMEET
	LEFT JOIN STVBLDG ON SSRMEET_BLDG_CODE = STVBLDG_CODE
WHERE
	SSRMEET_TERM_CODE = :term_code
	AND SSRMEET_CRN = :crn
ORDER BY
	SSRMEET_START_DATE ASC
';
            self::$meetingRows_stmt = $this->manager->getDB()->prepare($query);
        }

        $parameters = [
            ':term_code' => $this->getTermCodeFromOfferingId($courseOfferingId),
            ':crn' => $this->getCrnFromOfferingId($courseOfferingId),
        ];
        self::$meetingRows_stmt->execute($parameters);

        return self::$meetingRows_stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
