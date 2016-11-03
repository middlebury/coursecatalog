<?php

/**
 * A helper access the bookmarks model.
 *
 * @since 6/9/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Catalog_Action_Helper_Bookmarks
	extends Zend_Controller_Action_Helper_Abstract
{
	static $bookmarks;

	/**
	 * Answer the Bookmarks object
	 *
	 * @return string
	 * @access public
	 * @since 6/14/10
	 */
	public function direct () {
		if (!self::$bookmarks) {
			$auth = Zend_Controller_Action_HelperBroker::getStaticHelper('Auth')->getHelper();
			$courseManager = Zend_Controller_Action_HelperBroker::getStaticHelper('Osid')->getCourseManager();

			// Initialize our Model
			if (!$auth->isAuthenticated())
				throw new Exception('You must be logged in to perform this action.');

			self::$bookmarks = new Bookmarks(Zend_Registry::get('db'),  $auth->getUserId(), $courseManager);
		}
		return self::$bookmarks;
	}

}
