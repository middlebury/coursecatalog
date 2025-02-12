<?php

use PHPUnit\Framework\TestCase;

/**
 * Test class for banner_course_Topic_Search_Session.
 * Generated by PHPUnit on 2009-06-11 at 12:53:23.
 */
class banner_course_Topic_Search_SessionTest extends TestCase
{
    use banner_DatabaseTestTrait;

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

    public function testGetCourseCatalogId()
    {
        $this->assertInstanceOf('osid_id_Id', $this->session->getCourseCatalogId());
        $this->assertTrue($this->mcugId->isEqual($this->session->getCourseCatalogId()));
    }

    public function testGetCourseCatalog()
    {
        $this->assertInstanceOf('osid_course_CourseCatalog', $this->session->getCourseCatalog());
        $this->assertTrue($this->mcugId->isEqual($this->session->getCourseCatalog()->getId()));
    }

    public function testCanSearchTopics()
    {
        $this->assertTrue($this->session->canSearchTopics());
    }

    public function testUseFederatedCourseCatalogView()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testUseIsolatedCourseCatalogView()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testGetTopicQuery()
    {
        $this->assertInstanceOf('osid_course_TopicQuery', $this->session->getTopicQuery());
    }

    public function testGetTopicsByQuery()
    {
        $this->assertInstanceOf('osid_course_TopicList', $this->session->getTopicsByQuery($this->session->getTopicQuery()));
    }

    public function testGetTopicSearch()
    {
        $this->assertInstanceOf('osid_course_TopicSearch', $this->session->getTopicSearch());
    }

    public function testGetTopicSearchOrder()
    {
        $this->assertInstanceOf('osid_course_TopicSearchOrder', $this->session->getTopicSearchOrder());
    }

    public function testGetTopicsBySearch()
    {
        $this->assertInstanceOf('osid_course_TopicSearchResults', $this->session->getTopicsBySearch($this->session->getTopicQuery(), $this->session->getTopicSearch()));
    }
}
