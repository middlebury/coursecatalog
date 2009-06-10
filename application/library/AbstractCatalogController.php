<?php
/**
 * @since 4/20/09
 * @package catalog.controlers
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 * This is an abstract class that should be extended by any controller that needs
 * access to the the OSID course manager or runtime manager.
 * 
 * @since 4/20/09
 * @package catalog.controlers
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
abstract class AbstractCatalogController 
	extends Zend_Controller_Action
{
	
	private static $runtimeManager;
	private static $courseManager;
	
	/**
	 * Initialize our view with common properties
	 * 
	 * @return void
	 * @access public
	 * @since 4/22/09
	 */
	public function init () {
		// Add the catalog list for menu generation.
		$this->view->menuCatalogs = self::getCourseManager()->getCourseCatalogLookupSession()->getCourseCatalogs();
		$this->view->catalogIdString = $this->_getParam('catalog');
		$this->view->termIdString = $this->_getParam('term');
	}
	
	/**
	 * Set the selected catalog id.
	 * 
	 * @param osid_id_Id $id
	 * @return void
	 * @access protected
	 * @since 4/22/09
	 */
	protected function setSelectedCatalogId (osid_id_Id $id) {
		$this->view->menuCatalogSelectedId = $id;
	}
	
	/**
	 * Answer the selected catalog id
	 * 
	 * @return osid_id_Id
	 * @access protected
	 * @since 5/1/09
	 */
	protected function getSelectedCatalogId () {
		return $this->view->menuCatalogSelectedId;
	}
	
	/**
	 * Answer the CourseManager
	 * 
	 * @return osid_course_CourseManager
	 * @access public
	 * @since 4/20/09
	 * @static
	 */
	public static function getCourseManager () {
		if (!isset(self::$courseManager)) {
			$runtimeManager = self::getRuntimeManager();
			self::$courseManager = $runtimeManager->getManager(osid_OSID::COURSE(), 'banner_course_CourseManager', '3.0.0');
		}
		
		return self::$courseManager;
	}
	
	/**
	 * Answer the Runtime Manager
	 * 
	 * @return osid_OsidRuntimeManager
	 * @access public
	 * @since 4/20/09
	 * @static
	 */
	public static function getRuntimeManager () {
		if (!isset(self::$runtimeManager)) {
			self::$runtimeManager = new phpkit_AutoloadOsidRuntimeManager(BASE_PATH.'/configuration.plist');
		}
		
		return self::$runtimeManager;
	}
	
	/**
	 * Get and OSID id object from a string.
	 * 
	 * @param string $idString
	 * @return osid_id_Id
	 * @access public
	 * @since 4/21/09
	 * @static
	 */
	public static function getOsidIdFromString ($idString) {
		return new phpkit_id_URNInetId($idString);
	}
	
	/**
	 * Answer a string representation of an OSID id object
	 * 
	 * @param osid_id_Id $id
	 * @return string
	 * @access public
	 * @since 4/21/09
	 * @static
	 */
	public static function getStringFromOsidId (osid_id_Id $id) {
		return phpkit_id_URNInetId::getInetURNString($id);
	}
	
	/**
	 * Load topics into our view
	 * 
	 * @param osid_course_TopicList
	 * @return void
	 * @access protected
	 * @since 4/28/09
	 */
	protected function loadTopics (osid_course_TopicList $topicList) {
		$topics = self::topicListAsArray($topicList);
		
 		$this->view->subjectTopics = self::filterTopicsByType($topics, new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/subject"));
 		
 		$this->view->departmentTopics = self::filterTopicsByType($topics, new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/department"));
 		
 		$this->view->divisionTopics = self::filterTopicsByType($topics, new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/division"));
 		
 		$this->view->requirementTopics = self::filterTopicsByType($topics, new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/requirement"));
	}
	
	/**
	 * Answer an array containing the list items.
	 * 
	 * @param osid_course_TopicList $topicList
	 * @return array
	 * @access public
	 * @since 4/28/09
	 * @static
	 */
	public static function topicListAsArray (osid_course_TopicList $topicList) {
		$topics = array();
		while ($topicList->hasNext()) {
			$topics[] = $topicList->getNextTopic();
		}
		return $topics;
	}
	
	/**
	 * Return an array of topics matching a type 
	 * 
	 * @param array $topics
	 * @param osid_type_Type $type
	 * @return array
	 * @access public
	 * @since 4/28/09
	 * @static
	 */
	public static function filterTopicsByType (array $topics, osid_type_Type $type) {
		$matching = array();
		foreach ($topics as $topic) {
			if ($topic->getGenusType()->isEqual($type)) 
				$matching[] = $topic;
		}
		return $matching;
	}
	
	/**
     * Answer a 24-hour time string from an integer number of seconds.
     * 
     * @param integer $seconds
     * @return string
     * @access protected
     * @since 6/10/09
     * @static
     */
    public static function getTimeString ($seconds) {
    	$hour = floor($seconds/3600);
    	$minute = floor(($seconds - ($hour * 3600))/60);
    	return str_pad($hour, 2, '0', STR_PAD_LEFT).':'.str_pad($minute, 2, '0', STR_PAD_LEFT);
    }
	
	private $startTime;
	
	/**
	 * Answer the execution time
	 * 
	 * @return float
	 * @access private
	 * @since 4/30/09
	 */
	private function getExecTime () {
		if (isset($GLOBALS['start_time']))
			$start = $GLOBALS['start_time'];
		else if (isset($this->startTime))
			$start = $this->startTime;
		else
			return null;
		
		$end = microtime();
		
		list($sm, $ss) = explode(" ", $start);
		list($em, $es) = explode(" ", $end);

		$s = $ss + $sm;
		$e = $es + $em;

		return round($e-$s, 6);
	}
	
	/**
     * Pre-dispatch routines
     *
     * Called before action method. If using class with
     * {@link Zend_Controller_Front}, it may modify the
     * {@link $_request Request object} and reset its dispatched flag in order
     * to skip processing the current action.
     *
     * @return void
     */
    public function preDispatch()
    {
    	$this->startTime = microtime();
    }
	
	/**
     * Post-dispatch routines
     *
     * Called after action method execution. If using class with
     * {@link Zend_Controller_Front}, it may modify the
     * {@link $_request Request object} and reset its dispatched flag in order
     * to process an additional action.
     *
     * Common usages for postDispatch() include rendering content in a sitewide
     * template, link url correction, setting headers, etc.
     *
     * @return void
     */
    public function postDispatch()
    {
    	$response = $this->getResponse();
    	$db = self::getCourseManager()->getDB();
    	if (method_exists($db, 'getCounters')) {
    		foreach ($db->getCounters() as $name => $num) {
		    	$response->setHeader('X-'.$name, $num);
		    }
		}
    	$response->setHeader('X-Runtime', $this->getExecTime());
    }
}

?>