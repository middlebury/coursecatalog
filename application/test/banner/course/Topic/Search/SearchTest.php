<?php

use PHPUnit\Framework\TestCase;

/**
 * Test class for banner_course_Topic_Search_Search.
 * Generated by PHPUnit on 2009-06-11 at 13:04:22.
 */
class banner_course_Topic_Search_SearchTest extends TestCase
{
    use banner_DatabaseTestTrait;

    /**
     * @var banner_course_Topic_Search_Search
     */
    protected osid_course_TopicSearch $object;

    private osid_course_TopicSearchSession $session;
    private osid_type_Type $wildcardStringMatchType;
    private osid_id_Id $mcugId;
    private osid_id_Id $miisId;
    private osid_id_Id $termId;
    private osid_type_Type $termType;
    private osid_type_Type $subjectType;
    private osid_type_Type $departmentType;
    private osid_type_Type $divisionType;
    private osid_type_Type $requirementType;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->wildcardStringMatchType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:search:wildcard');

        $this->mcugId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:catalog.MCUG');
        $this->miisId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:catalog.MIIS');

        $this->session = self::$courseManager->getTopicSearchSessionForCatalog($this->mcugId);
        $this->object = $this->session->getTopicSearch();

        $this->termId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:term.200820');

        $this->termType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:terms');

        $this->subjectType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.subject');
        $this->departmentType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.department');
        $this->divisionType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.division');
        $this->requirementType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.requirement');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
    }

    public function testLimitResultSet()
    {
        $this->object->limitResultSet(1, 10);
        $this->assertTrue(true, 'No unexpected exceptions were thrown.');
    }

    public function testHasSearchRecordType()
    {
        $this->assertFalse($this->object->hasSearchRecordType($this->termType));
    }

    public function testSearchWithinTopicResults()
    {
        $results = $this->session->getTopicsBySearch($this->session->getTopicQuery(), $this->session->getTopicSearch());
        $this->object->searchWithinTopicResults($results);
        $this->assertTrue(true, 'No unexpected exceptions were thrown.');
    }

    public function testSearchAmongTopics()
    {
        $topicList = new phpkit_id_ArrayIdList([
            new phpkit_id_URNInetId('urn:inet:middlebury.edu:topic.requirement.DED'),
            new phpkit_id_URNInetId('urn:inet:middlebury.edu:topic.requirement.SCI'),
        ]);

        $this->object->searchAmongTopics($topicList);
        $this->assertTrue(true, 'No unexpected exceptions were thrown.');
    }

    public function testOrderTopicResults()
    {
        $order = $this->session->getTopicSearchOrder();
        $order->orderByDisplayName();
        $this->object->orderTopicResults($order);
        $this->assertTrue(true, 'No unexpected exceptions were thrown.');
    }

    public function testGetTopicSearchRecord()
    {
        $this->expectException(osid_UnsupportedException::class);

        $this->object->getTopicSearchRecord($this->termType);
    }
}
