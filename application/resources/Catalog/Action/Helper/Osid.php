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
	 */
	public function getConfigPath () {
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
	 */
	public function setConfigPath ($path) {
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
			$runtimeManager = $this->getRuntimeManager();
			
			$config = Zend_Registry::getInstance()->config;
			if (!isset($config->osid->course_impl) || !strlen(trim($config->osid->course_impl))) {
				$implClass = 'banner_course_CourseManager';
			} else {
				$implClass = $config->osid->course_impl;
			}
			
			self::$courseManager = $runtimeManager->getManager(osid_OSID::COURSE(), $implClass, '3.0.0');
		}
		
		return self::$courseManager;
	}
	
	/**
	 * Answer the Runtime Manager
	 * 
	 * @return osid_OsidRuntimeManager
	 * @access public
	 * @since 4/20/09
	 */
	public function getRuntimeManager () {
		if (!isset(self::$runtimeManager)) {
			self::$runtimeManager = new phpkit_AutoloadOsidRuntimeManager($this->getConfigPath());
		}
		
		return self::$runtimeManager;
	}
}

?>