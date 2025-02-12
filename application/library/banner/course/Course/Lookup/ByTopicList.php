<?php

/**
 * @since 4/13/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * An iterator for retrieving all courses from a catalog.
 *
 * @since 4/13/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class banner_course_Course_Lookup_ByTopicList extends banner_course_Course_AbstractList implements osid_course_CourseList
{
    private osid_id_Id $topicId;

    /**
     * Constructor.
     *
     * @return void
     *
     * @since 4/13/09
     */
    public function __construct(PDO $db, banner_course_AbstractSession $session, osid_id_Id $catalogId, osid_id_Id $topicId)
    {
        $this->topicId = $topicId;

        parent::__construct($db, $session, $catalogId);
    }

    /**
     * Answer the input parameters.
     *
     * @return array
     *
     * @since 4/17/09
     */
    protected function getInputParameters()
    {
        $type = $this->session->getTopicLookupSession()->getTopicType($this->topicId);
        $value = $this->session->getTopicLookupSession()->getTopicValue($this->topicId);
        switch ($type) {
            case 'subject':
                return [':subject_code' => $value];
            case 'department':
                return [':department_code' => $value];
            case 'division':
                return [':division_code' => $value];
            default:
                throw new osid_NotFoundException('No topic found with category '.$type);
        }
    }

    /**
     * Answer additional where terms. E.g. 'SSRMEET_MON_DAY = true AND SSRMEET_TUE_DAY = false'.
     *
     * @return string
     *
     * @since 4/17/09
     */
    protected function getWhereTerms()
    {
        $type = $this->session->getTopicLookupSession()->getTopicType($this->topicId);
        switch ($type) {
            case 'subject':
                return 'SCBCRSE_SUBJ_CODE = :subject_code';
            case 'department':
                return 'SCBCRSE_DEPT_CODE = :department_code';
            case 'division':
                return 'SCBCRSE_DIVS_CODE = :division_code';
            default:
                throw new osid_NotFoundException('No topic found with category '.$type);
        }
    }
}
