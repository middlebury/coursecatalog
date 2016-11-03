<?php

/**
 * A helper to Return an array of topics matching a type
 *
 * @since 6/9/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Catalog_View_Helper_FilterTopicsByType
	extends Catalog_Action_Helper_AbstractOsidIdentifier
{
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
		return Zend_Controller_Action_HelperBroker::getStaticHelper('Topics')->filterTopicsByType($topics, $type);
	}
}
