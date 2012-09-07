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
	/**
	 * Initialize our view with common properties
	 * 
	 * @return void
	 * @access public
	 * @since 4/22/09
	 */
	public function init () {
		// Add the catalog list for menu generation.
		$this->view->menuCatalogs = $this->_helper->osid->getCourseManager()->getCourseCatalogLookupSession()->getCourseCatalogs();
		$this->view->catalogIdString = $this->_getParam('catalog');
		$this->view->termIdString = $this->_getParam('term');
		$this->view->addHelperPath(APPLICATION_PATH.'/views/helpers', 'Catalog_View_Helper');
		$this->view->doctype('XHTML1_TRANSITIONAL');
		
		$this->setLayout();
	}
	
	/**
	 * Configure the layout to use for the current action.
	 * 
	 * @return void
	 */
	protected function setLayout () {
		if ($this->_getParam('catalog')) {
			$config = Zend_Registry::getInstance()->config;
			if (count($config->catalog->layouts)) {
				$catalogId = $this->_helper->osidId->fromString($this->_getParam('catalog'));
				foreach ($config->catalog->layouts as $layoutConfig) {
					if ($catalogId->isEqual(new phpkit_id_URNInetId($layoutConfig->catalog_id))) {
						$this->_helper->layout()->setLayout($layoutConfig->layout);
						break;
					}
				}
			}
		}
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
		$this->view->menuCatalogSelected = $this->_helper->osid->getCourseManager()->getCourseCatalogLookupSession()->getCourseCatalog($id);
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
	 * Load topics into our view
	 * 
	 * @param osid_course_TopicList
	 * @return void
	 * @access protected
	 * @since 4/28/09
	 */
	protected function loadTopics (osid_course_TopicList $topicList) {
		$topics = $this->_helper->topics->topicListAsArray($topicList);
		
 		$this->view->subjectTopics = $this->_helper->topics->filterTopicsByType($topics, new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/subject"));
 		
 		$this->view->departmentTopics = $this->_helper->topics->filterTopicsByType($topics, new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/department"));
 		
 		$this->view->divisionTopics = $this->_helper->topics->filterTopicsByType($topics, new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/division"));
 		
 		$this->view->requirementTopics = $this->_helper->topics->filterTopicsByType($topics, new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/requirement"));
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
    	$this->setCacheControlHeaders();
    			
    	$response = $this->getResponse();
//     	$db = $this->_helper->osid->getCourseManager()->getDB();
//     	if (method_exists($db, 'getCounters')) {
//     		foreach ($db->getCounters() as $name => $num) {
// 		    	$response->setHeader('X-'.$name, $num);
// 		    }
// 		}		
    	$response->setHeader('X-Runtime', $this->getExecTime());
    }
    
    /**
     * Set our cache control headers.
     * 
     * @return void
     * @access protected
     * @since 6/4/10
     */
    protected function setCacheControlHeaders () {
		// Only allow caching if anonymous. This will ensure that users'
		// browser caches will not cache pages if logged in.
		if (!$this->_helper->auth()->isAuthenticated() && !headers_sent()) {
			// Set cache-control headers
			$config = Zend_Registry::getInstance()->config;
			$maxAge = intval($config->cache_control->max_age);
			$expirationOffset = intval($config->cache_control->expiration_offset);
			if (!$expirationOffset)
				$expirationOffset = $maxAge;
			
			if ($maxAge > 0 && !$this->getResponse()->isException()) {
				$this->getResponse()->setHeader('Expires', gmdate('D, d M Y H:i:s', time() + $expirationOffset).' GMT', true);
				$this->getResponse()->setHeader('Cache-Control', 'public', true);
				$this->getResponse()->setHeader('Cache-Control', 'max-age='.$maxAge);
				$this->getResponse()->setHeader('Pragma', '', true);
			}
		}
		
		$this->getResponse()->setHeader('Vary', 'Cookie,Accept-Encoding', true);
    }
}

?>