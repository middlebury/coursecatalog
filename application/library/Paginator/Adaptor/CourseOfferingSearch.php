<?php
/**
 * @since 6/2/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * This adapter provides a wrapper to allow usage of the Zend_Paginator with
 * CourseOfferingSearchResults.
 *
 * @since 6/2/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Paginator_Adaptor_CourseOfferingSearch implements Zend_Paginator_Adapter_Interface
{
    /**
     * Constructor.
     *
     * @param optional osid_course_CourseOfferingSearch $search
     *
     * @return void
     *
     * @since 6/2/09
     */
    public function __construct(osid_course_CourseOfferingSearchSession $session, osid_course_CourseOfferingQuery $query, ?osid_course_CourseOfferingSearch $search = null)
    {
        $this->session = $session;
        $this->query = $query;

        if (null === $search) {
            $this->search = $this->session->getCourseOfferingSearch();
        } else {
            $this->search = $search;
        }
    }

    /**
     * Returns the total number of rows in the collection.
     *
     * @since 6/2/09
     */
    public function count(): int
    {
        if (!isset($this->results)) {
            $this->getItems(0, 20);
        }

        return $this->results->getResultSize();
    }

    /**
     * Returns an collection of items for a page.
     *
     * @param int $offset           Page offset
     * @param int $itemCountPerPage Number of items per page
     *
     * @return array
     */
    public function getItems($offset, $itemCountPerPage)
    {
        $start = $offset + 1;
        $end = $offset + $itemCountPerPage;

        $this->search->limitResultSet($start, $end);
        $this->results = $this->session->getCourseOfferingsBySearch($this->query, $this->search);

        $offerings = [];
        $list = $this->results->getCourseOfferings();
        while ($list->hasNext()) {
            $offerings[] = $list->getNextCourseOffering();
        }

        return $offerings;
    }
}
