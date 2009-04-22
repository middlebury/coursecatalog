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
}

?>