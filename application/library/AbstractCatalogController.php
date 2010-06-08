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
	private static $configPath;
	
	private static $idAuthorityToShorten;
		
	/**
	 * Answer the configuration path
	 * 
	 * @return string
	 * @access public
	 * @since 6/11/09
	 * @static
	 */
	public static function getConfigPath () {
		if (!isset(self::$configPath))
			self::$configPath = BASE_PATH.'/configuration.plist';
		
		return self::$configPath;
	}
	
	/**
	 * Set the configuration path
	 * 
	 * @param string $path
	 * @access public
	 * @since 6/11/09
	 * @throws osid_InvalidStateException The config path has already been set.
	 * @static
	 */
	public static function setConfigPath ($path) {
		if (isset(self::$configPath))
			throw new osid_InvalidStateException('the config path has already been set');
		
		self::$configPath = $path;
	}
	
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
			self::$runtimeManager = new phpkit_AutoloadOsidRuntimeManager(self::getConfigPath());
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
		try {
			return new phpkit_id_URNInetId($idString);
		} catch (osid_InvalidArgumentException $e) {
			if (self::getIdAuthorityToShorten())
				return new phpkit_id_Id(self::getIdAuthorityToShorten(), 'urn', $idString);
			else
				throw $e;
		}
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
		if (self::getIdAuthorityToShorten() 
				&& strtolower($id->getIdentifierNamespace()) == 'urn' 
				&& strtolower($id->getAuthority()) == self::getIdAuthorityToShorten())
			return $id->getIdentifier();
		else
			return phpkit_id_URNInetId::getInetURNString($id);
	}
	
	/**
	 * Get and OSID type object from a string.
	 * 
	 * @param string $idString
	 * @return osid_type_Type
	 * @access public
	 * @since 4/21/09
	 * @static
	 */
	public static function getOsidTypeFromString ($idString) {
		try {
			return new phpkit_type_URNInetType($idString);
		} catch (osid_InvalidArgumentException $e) {
			if (self::getIdAuthorityToShorten())
				return new phpkit_type_Type('urn', self::getIdAuthorityToShorten(), $idString);
			else
				throw $e;
		}
	}
	
	/**
	 * Answer a string representation of an OSID type object
	 * 
	 * @param osid_type_Type $type
	 * @return string
	 * @access public
	 * @since 4/21/09
	 * @static
	 */
	public static function getStringFromOsidType (osid_type_Type $type) {
		if (self::getIdAuthorityToShorten() 
				&& strtolower($type->getIdentifierNamespace()) == 'urn' 
				&& strtolower($type->getAuthority()) == self::getIdAuthorityToShorten())
			return $type->getIdentifier();
		else
			return phpkit_id_URNInetType::getInetURNString($type);
	}
	
	/**
	 * Answer the id-authority for whom ids should be shortened.
	 * 
	 * @return mixed string or false if none should be shortened.
	 * @access protected
	 * @since 6/16/09
	 * @static
	 */
	protected static function getIdAuthorityToShorten () {
		if (!isset(self::$idAuthorityToShorten)) {
			try {
				$authority = phpkit_configuration_ConfigUtil::getSingleValuedValue(
    								self::getRuntimeManager()->getConfiguration(), 
    								new phpkit_id_URNInetId('urn:inet:middlebury.edu:config:catalog/shorten_ids_for_authority'),
    								new phpkit_type_Type('urn', 'middlebury.edu', 'Primitives/String'));
    			if (strlen($authority))
	    			self::$idAuthorityToShorten = $authority;
	    		else
	    			self::$idAuthorityToShorten = false;
    		} catch (osid_NotFoundException $e) {
    			self::$idAuthorityToShorten = false;
    		} catch (osid_ConfigurationErrorException $e) {
    			self::$idAuthorityToShorten = false;
    		}
		}
		return self::$idAuthorityToShorten;
	}
	
	/**
	 * Answer the course offering genus types that should be searched by default
	 * for a catalog
	 * 
	 * @return array of osid_type_Types
	 * @access protected
	 * @since 6/16/09
	 * @static
	 */
	protected static function getDefaultGenusTypes () {
		try {
			$types = array();
			$typeStrings = phpkit_configuration_ConfigUtil::getMultiValuedValue(
								self::getRuntimeManager()->getConfiguration(), 
								new phpkit_id_URNInetId('urn:inet:middlebury.edu:config:catalog/default_offering_genus_types_to_search'),
								new phpkit_type_Type('urn', 'middlebury.edu', 'Primitives/String'));
			foreach ($typeStrings as $typeString) {
				$types[] = new phpkit_type_URNInetType($typeString);
			}
			return $types;
		} catch (osid_NotFoundException $e) {
		} catch (osid_ConfigurationErrorException $e) {
		}
		
		return array();
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
    	$hour = $hour % 24;
    	
    	if (!$hour)
    		$string = 12;
    	else if ($hour < 13)
	    	$string = $hour;
	    else
	    	$string = $hour - 12;
    	
    	$string .= ':'.str_pad($minute, 2, '0', STR_PAD_LEFT);
    	
    	if ($hour < 13)
    		$string .= ' am';
    	else
    		$string .= ' pm';
    		
    	return $string;
    }
    
    /**
     * Answer the "current" termId for the catalog passed. If multiple terms overlap
     * to be 'current', only one will be returned.
     * 
     * @param osid_id_Id $catalogId
     * @return osid_id_Id The current term id.
     * @throws osid_NotFoundException
     * @access public
     * @since 6/11/09
     * @static
     */
    public static function getCurrentTermId (osid_id_Id $catalogId) {
    	if (!isset($_SESSION['current_terms']))
    		$_SESSION['current_terms'] = array();
    	$catalogIdString = self::getStringFromOsidId($catalogId);
    	if (!isset($_SESSION['current_terms'][$catalogIdString])) {
    		$manager = self::getCourseManager();
    		if (!$manager->supportsTermLookup())
    			throw new osid_NotFoundException('Could not determine a current term id. The manager does not support term lookup.');
    		$termLookup = $manager->getTermLookupSessionForCatalog($catalogId);
	    	$_SESSION['current_terms'][$catalogIdString] = self::getClosestTermId($termLookup->getTerms());
    	}
    	if (!isset($_SESSION['current_terms'][$catalogIdString]))
    		throw new osid_NotFoundException('Could not determine a current term id for the catalog passed.');
    	
    	return $_SESSION['current_terms'][$catalogIdString];
    }
    
    /**
     * Answer the term id whose timespan is closest to now. 
     * 
     * @param osid_course_TermList $terms
     * @param optional DateTime $date The date to reference the terms to.
     * @return osid_id_Id
     * @access public
     * @since 6/11/09
     * @static
     */
    public static function getClosestTermId (osid_course_TermList $terms, DateTime $date = null) {
    	$ids = array();
    	$diffs = array();
    	
    	if (is_null($date))
	    	$date = time();
	    else
	    	$date = intval($date->format('U'));
	    
    	if (!$terms->hasNext())
    		throw new osid_NotFoundException('Could not determine a current term id. No terms found.');
		
		while ($terms->hasNext()) {
			$term = $terms->getNextTerm();
			$start = intval($term->getStartTime()->format('U'));
			$end = intval($term->getEndTime()->format('U'));
			
			// If our current time is within the term timespan, return that term's id.
			if ($date >= $start && $date <= $end)
				return $term->getId();
			
			$ids[] = $term->getId();
			$diffs[] = abs($date - $start) + abs($date - $end);
		}
		
		array_multisort($diffs, SORT_NUMERIC, SORT_ASC, $ids);
		return $ids[0];
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
    	$db = self::getCourseManager()->getDB();
    	if (method_exists($db, 'getCounters')) {
    		foreach ($db->getCounters() as $name => $num) {
		    	$response->setHeader('X-'.$name, $num);
		    }
		}		
    	$response->setHeader('X-Runtime', $this->getExecTime());
    }
    
    /**
     * Set our cache control headers.
     * 
     * @return void
     * @access private
     * @since 6/4/10
     */
    private function setCacheControlHeaders () {
    	try {
			// Only allow caching if anonymous. This will ensure that users'
			// browser caches will not cache pages if logged in.
			require_once(APPLICATION_PATH.'/controllers/AuthController.php');
			if (!AuthController::isAuthenticated()) {
				
				// Set cache-control headers
				$maxAge = phpkit_configuration_ConfigUtil::getSingleValuedValue(
									self::getRuntimeManager()->getConfiguration(), 
									new phpkit_id_URNInetId('urn:inet:middlebury.edu:config:catalog/max_age'),
									new phpkit_type_Type('urn', 'middlebury.edu', 'Primitives/Integer'));
				if ($maxAge > 0 && !$this->getResponse()->isException()) {
					$this->getResponse()->setHeader('Expires', gmdate('D, d M Y H:i:s', time() + $maxAge).' GMT', true);
					$this->getResponse()->setHeader('Cache-Control', 'public', true);
					$this->getResponse()->setHeader('Cache-Control', 'max-age='.$maxAge);
				}
				
			}
				
		} catch (osid_NotFoundException $e) {
		} catch (osid_ConfigurationErrorException $e) {
		}
    }
}

?>