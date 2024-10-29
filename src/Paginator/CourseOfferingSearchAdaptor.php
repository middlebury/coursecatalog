<?php
/**
 * @since 6/2/09
 *
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Paginator;

/**
 * This adapter provides a wrapper to allow usage of paginators with
 * CourseOfferingSearchResults.
 *
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class CourseOfferingSearchAdaptor
{
    private $itemsCallback;

    /**
     * Constructor.
     *
     * @param \osid_course_CourseOfferingSearchSession $session
     *                                                                The search session to run searches against
     * @param \osid_course_CourseOfferingQuery         $query
     *                                                                The search query to run to find offerings
     * @param \osid_course_CourseOfferingSearch        $search
     *                                                                An optional search criteria (sorting, limits, etc) to apply to the
     *                                                                query
     * @param callable                                 $itemsCallback
     *                                                                An optional callback to apply to each item returned by getItems()
     */
    public function __construct(
        private \osid_course_CourseOfferingSearchSession $session,
        private \osid_course_CourseOfferingQuery $query,
        ?\osid_course_CourseOfferingSearch $search = null,
        ?callable $itemsCallback = null,
    ) {
        if (null === $search) {
            $this->search = $this->session->getCourseOfferingSearch();
        } else {
            $this->search = $search;
        }
        $this->itemsCallback = $itemsCallback;
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

        $items = [];
        $list = $this->results->getCourseOfferings();
        while ($list->hasNext()) {
            $offering = $list->getNextCourseOffering();
            if ($this->itemsCallback) {
                $items[] = call_user_func($this->itemsCallback, $offering);
            } else {
                $items[] = $offering;
            }
        }

        return $items;
    }
}
