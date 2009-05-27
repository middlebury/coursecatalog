<?php
/**
 * @since 5/27/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 * A List for retrieving sections based on search results
 * 
 * @since 5/27/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class banner_course_CourseOfferingSearchList
	extends banner_course_AbstractCourseOfferingList
	implements osid_course_CourseOfferingList
{
	/**
	 * Constructor
	 * 
	 * @param PDO $db
	 * @param banner_course_CourseOfferingSessionInterface $session
	 * @param optional osid_id_Id $catalogDatabaseId
     * @param object osid_course_CourseOfferingQuery $courseQuery the search 
     *          query 
     * @return void
	 * @access public
	 * @since 4/13/09
	 */
	public function __construct (PDO $db, banner_course_CourseOfferingSessionInterface $session, osid_id_Id $catalogId = null, osid_course_CourseOfferingQuery $courseQuery) {
		$this->courseQuery = $courseQuery;
		
		$this->parameters = array();
		$this->where = $courseQuery->getWhereClause();
		
		foreach ($courseQuery->getParameters() as $i => $val) {
			$name = ':co_search_'.$i;
			$this->parameters[$name] = $val;
			$this->where = preg_replace('/\?/', $name, $this->where, 1);
		}
		
		parent::__construct($db, $session, $catalogId);
	}
	
	/**
	 * Answer the input parameters
	 * 
	 * @return array
	 * @access protected
	 * @since 4/17/09
	 */
	protected function getInputParameters () {
		return $this->parameters;
	}
	
	/**
	 * Answer additional where terms. E.g. 'SSRMEET_MON_DAY = true AND SSRMEET_TUE_DAY = false'
	 * 
	 * @return array
	 * @access protected
	 * @since 4/17/09
	 */
	protected function getWhereTerms() {
		return $this->where;
	}
}
