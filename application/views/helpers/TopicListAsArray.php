<?php

/**
 * A helper to Answer an array containing the list items.
 * 
 * @since 6/9/10
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Catalog_View_Helper_TopicListAsArray
	extends Catalog_Action_Helper_AbstractOsidIdentifier
{
	/**
	 * Answer an array containing the list items.
	 * 
	 * @param osid_course_TopicList $topicList
	 * @return array
	 * @access public
	 * @since 4/28/09
	 */
	public function topicListAsArray (osid_course_TopicList $topicList) {
		return Zend_Controller_Action_HelperBroker::getStaticHelper('Topics')->topicListAsArray($topicList);
	}
}

?>