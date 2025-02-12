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
class banner_course_Course_Lookup_AllList extends banner_course_Course_AbstractList implements osid_course_CourseList
{
    /**
     * Constructor.
     *
     * @return void
     *
     * @since 4/13/09
     */
    public function __construct(PDO $db, banner_course_AbstractSession $session, osid_id_Id $catalogId)
    {
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
        return [];
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
        return '';
    }
}
