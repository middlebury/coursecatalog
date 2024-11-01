<?php

namespace App\Tests\Service\Osid;

use App\Service\Osid\Runtime;
use App\Service\Osid\TopicHelper;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TopicHelperTest extends KernelTestCase
{
    use \banner_DatabaseTestTrait;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->mcugId = new \phpkit_id_URNInetId('urn:inet:middlebury.edu:catalog.MCUG');

        $this->osidTopicHelper = static::getContainer()
            ->get(TopicHelper::class);
        $this->topicLookup = static::getContainer()
            ->get(Runtime::class)
            ->getCourseManager()
            ->getTopicLookupSessionForCatalog($this->mcugId);
    }

    public function testTopicListAsArray()
    {
        $topics = $this->topicLookup->getTopics();
        $numTopics = $topics->available();
        $topicArray = $this->osidTopicHelper->topicListAsArray($topics);
        $this->assertIsArray($topicArray);
        $this->assertCount($numTopics, $topicArray);
        $this->assertInstanceOf('osid_course_Topic', $topicArray[0]);
    }

    public function testFilterTopicsByType()
    {
        $topics = $this->topicLookup->getTopics();
        $numTopics = $topics->available();
        $topicArray = $this->osidTopicHelper->topicListAsArray($topics);

        $subjectType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.subject');

        $filteredTopics = $this->osidTopicHelper->filterTopicsByType($topicArray, $subjectType);
        $this->assertIsArray($filteredTopics);
        $this->assertLessThan($numTopics, count($filteredTopics));
        $this->assertCount(4, $filteredTopics);
        $this->assertInstanceOf('osid_course_Topic', $filteredTopics[0]);
    }
}
