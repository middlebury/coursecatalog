<?php

/**
 * A helper to with functions for handling topics
 *
 * @since 6/9/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Catalog_Action_Helper_Topics
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

}
