<?php

namespace App\Service\Osid;

/**
 * A helper to with functions for handling topics.
 *
 * @since 6/9/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class TopicHelper
{
    /**
     * Answer an array containing the list items.
     *
     * @param osid_course_TopicList $topicList
     *                                         The topic list to convert
     *
     * @return array
     */
    public function topicListAsArray(\osid_course_TopicList $topicList)
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
     * @param array          $topics
     *                               An array of osid_course_TopicList objects
     * @param osid_type_Type $type
     *                               The type of topic to filter for
     *
     * @return array
     */
    public function filterTopicsByType(array $topics, \osid_type_Type $type)
    {
        $matching = [];
        foreach ($topics as $topic) {
            if ($topic->getGenusType()->isEqual($type)) {
                $matching[] = $topic;
            }
        }

        return $matching;
    }

    /**
     * Return an array of topics organized by type.
     *
     * @param osid_course_TopicList $topicList
     *
     * @return array
     */
    public function asTypedArray(\osid_course_TopicList $topicList)
    {
        $topics = $this->topicListAsArray($topicList);
        $typedArray = [];

        $typedArray['subjectTopics'] = $this->filterTopicsByType(
            $topics,
            new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.subject')
        );
        $typedArray['departmentTopics'] = $this->filterTopicsByType(
            $topics,
            new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.department')
        );
        $typedArray['divisionTopics'] = $this->filterTopicsByType(
            $topics,
            new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.division')
        );
        $typedArray['requirementTopics'] = $this->filterTopicsByType(
            $topics,
            new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.requirement')
        );
        $typedArray['levelTopics'] = $this->filterTopicsByType(
            $topics,
            new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.level')
        );
        $typedArray['blockTopics'] = $this->filterTopicsByType(
            $topics,
            new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.block')
        );
        $typedArray['instructionMethodTopics'] = $this->filterTopicsByType(
            $topics,
            new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.instruction_method')
        );

        return $typedArray;
    }
}
