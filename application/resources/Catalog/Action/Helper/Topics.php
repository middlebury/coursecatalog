<?php

/**
 * A helper to with functions for handling topics.
 *
 * @since 6/9/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Catalog_Action_Helper_Topics extends Catalog_Action_Helper_AbstractOsidIdentifier
{
    /**
     * Answer an array containing the list items.
     *
     * @return array
     *
     * @since 4/28/09
     */
    public function topicListAsArray(osid_course_TopicList $topicList)
    {
        $topics = [];
        while ($topicList->hasNext()) {
            $topics[] = $topicList->getNextTopic();
        }

        return $topics;
    }

    /**
     * Return an array of topics matching a type.
     *
     * @return array
     *
     * @since 4/28/09
     *
     * @static
     */
    public static function filterTopicsByType(array $topics, osid_type_Type $type)
    {
        $matching = [];
        foreach ($topics as $topic) {
            if ($topic->getGenusType()->isEqual($type)) {
                $matching[] = $topic;
            }
        }

        return $matching;
    }
}
