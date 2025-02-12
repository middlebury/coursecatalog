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
class banner_course_CourseOffering_Lookup_ByGenusTypeList extends banner_course_CourseOffering_AbstractList implements osid_course_CourseOfferingList
{
    /**
     * Constructor.
     *
     * @return void
     *
     * @since 4/13/09
     */
    public function __construct(PDO $db, banner_course_CourseOffering_SessionInterface $session, osid_id_Id $catalogId, osid_type_Type $genusType)
    {
        $this->genusType = $genusType;

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
        return [':schd_code' => $this->session->getScheduleCodeFromGenusType($this->genusType)];
    }

    /**
     * Answer additional where terms. E.g. 'SSRMEET_MON_DAY = true AND SSRMEET_TUE_DAY = false'.
     *
     * @return array
     *
     * @since 4/17/09
     */
    protected function getWhereTerms()
    {
        return 'SSBSECT_SCHD_CODE = :schd_code';
    }
}
