<?php
/**
 * @since 11/16/09
 * @package catalog.library
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 * A helper for accessing recent courses for an instructor
 * 
 * @since 11/16/09
 * @package catalog.library
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Helper_RecentCourses_Instructor 
	extends Helper_RecentCourses_Abstract
{
	
	private $termsCache;
	
	/**
	 * Constructor
	 * 
	 * @param osid_course_CourseSearchResults $courses
	 * @param osid_course_CourseOfferingSearchSession $searchSession
	 * @param osid_id_Id $instructorId
	 * @return void
	 * @access public
	 * @since 11/16/09
	 */
	public function __construct (osid_course_CourseSearchResults $courses, osid_course_CourseOfferingSearchSession $searchSession, osid_id_Id $instructorId) {
		$this->termsCache = array();
		$this->searchSession = $searchSession;
		$this->instructorId = $instructorId;
		parent::__construct($courses);
	}
	
	
	/**
	 * Answer the terms for a course. These may be all terms or terms taught
	 * 
	 * @param osid_course_Course $course
	 * @return array
	 * @access protected
	 * @since 11/16/09
	 */
	protected function fetchCourseTerms (osid_course_Course $course) {
		$cacheKey = AbstractCatalogController::getStringFromOsidId($course->getId())
			."_".AbstractCatalogController::getStringFromOsidId($this->instructorId);
		
		if (!isset($this->termsCache[$cacheKey])) {
			$instructorsType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:record:instructors");
			$allTerms = array();
			
			$query = $this->searchSession->getCourseOfferingQuery();
			$query->matchCourseId($course->getId(), true);
			
			$instructorsRecord = $query->getCourseOfferingQueryRecord($instructorsType);
			$instructorsRecord->matchInstructorId($this->instructorId, true);
			
			$search = $this->searchSession->getCourseOfferingSearch();
			$order = $this->searchSession->getCourseOfferingSearchOrder();
			$order->orderByTerm();
			$order->ascend();
			$search->orderCourseOfferingResults($order);
			
			$offerings = $this->searchSession->getCourseOfferingsBySearch($query, $search);
			
	// 		print $offerings->debug();
			
			$seen = array();
			while ($offerings->hasNext()) {
				$term = $offerings->getNextCourseOffering()->getTerm();
				$termIdString = AbstractCatalogController::getStringFromOsidId($term->getId());
	// 			print $termIdString."\n";
				if (!in_array($termIdString, $seen)) {
					$allTerms[] = $term;
					$seen[] = $termIdString;
				}
			}
			$this->termsCache[$cacheKey] = $allTerms;	
		}
		return $this->termsCache[$cacheKey];
	}
}

?>