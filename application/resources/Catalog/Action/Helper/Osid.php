<?php

/**
 * A helper to provide access to the CourseManager OSID and OSID configuration.
 * 
 * @since 6/9/10
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Catalog_Action_Helper_Osid
	extends Zend_Controller_Action_Helper_Abstract
{
	
	private static $runtimeManager;
	private static $courseManager;
	private static $configPath;
		
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
	 * Answer the CourseManager
	 * 
	 * @return osid_course_CourseManager
	 * @access public
	 * @since 4/20/09
	 */
	public function getCourseManager () {
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
	 * Answer the course offering genus types that should be searched by default
	 * for a catalog
	 * 
	 * @return array of osid_type_Types
	 * @access public
	 * @since 6/16/09
	 */
	public function getDefaultGenusTypes () {
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
    public function getCurrentTermId (osid_id_Id $catalogId) {
    	$catalogIdString = Zend_Controller_Action_HelperBroker::getStaticHelper('OsidId')->toString($catalogId);
    	$cacheKey = 'current_term::'.$catalogIdString;
    	$currentTerm = self::cache_get($cacheKey);
    	if (!$currentTerm) {
    		$manager = self::getCourseManager();
    		if (!$manager->supportsTermLookup())
    			throw new osid_NotFoundException('Could not determine a current term id. The manager does not support term lookup.');
    		$termLookup = $manager->getTermLookupSessionForCatalog($catalogId);
	    	$currentTerm = self::getClosestTermId($termLookup->getTerms());
	    	if (!$currentTerm)
		    	throw new osid_NotFoundException('Could not determine a current term id for the catalog passed.');
	    	
	    	self::cache_set($cacheKey, $currentTerm);
    	}
    	
    	return $currentTerm;
    }
    
    /**
     * Fetch from cache
     * 
     * @param string $key
     * @return mixed, FALSE on failure
     * @access private
     * @since 6/9/10
     */
    private static function cache_get ($key) {
    	if (function_exists('apc_fetch')) {
    		return apc_fetch($key);
    	}
    	// Fall back to Session caching if APC is not available.
    	else {
			if (!isset($_SESSION['cache'][$key]))
				return false;
			return $_SESSION['cache'][$key];
		}
    }
    
    /**
     * Set an item in the cache
     * 
     * @param string $key
     * @param mixed $value
     * @return boolean true on success, false on failure
     * @access private
     * @since 6/9/10
     */
    private static function cache_set ($key, $value) {
    	if (function_exists('apc_fetch')) {
    		return apc_store($key, $value, 3600);
    	}
    	// Fall back to Session caching if APC is not available.
    	else {
			if (!isset($_SESSION['cache']))
				$_SESSION['cache'] = array();
			$_SESSION['cache'][$key] = $value;
			return true;
		}
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

	
}

?>