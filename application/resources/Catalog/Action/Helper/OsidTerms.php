<?php

/**
 * A helper to with functions for handling terms
 * 
 * @since 6/9/10
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Catalog_Action_Helper_OsidTerms
	extends Catalog_Action_Helper_AbstractOsidIdentifier
{
	
	/**
     * Answer the "current" termId for the catalog passed. If multiple terms overlap
     * to be 'current', only one will be returned.
     * 
     * @param osid_id_Id $catalogId
     * @return osid_id_Id The current term id.
     * @throws osid_NotFoundException
     * @access public
     * @since 6/11/09
     */
    public function getCurrentTermId (osid_id_Id $catalogId) {
    	$catalogIdString = Zend_Controller_Action_HelperBroker::getStaticHelper('OsidId')->toString($catalogId);
    	$cacheKey = 'current_term::'.$catalogIdString;
    	$currentTerm = self::cache_get($cacheKey);
    	if (!$currentTerm) {
    		$manager = Zend_Controller_Action_HelperBroker::getStaticHelper('Osid')->getCourseManager();
    		if (!$manager->supportsTermLookup())
    			throw new osid_NotFoundException('Could not determine a current term id. The manager does not support term lookup.');
    		$termLookup = $manager->getTermLookupSessionForCatalog($catalogId);
	    	$currentTerm = $this->getClosestTermId($termLookup->getTerms());
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
     */
    public function getClosestTermId (osid_course_TermList $terms, DateTime $date = null) {
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