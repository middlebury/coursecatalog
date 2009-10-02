<?php
require_once 'PHPUnit/Framework.php';

/**
 * Test class for banner_course_Topic_Search_Session.
 * Generated by PHPUnit on 2009-06-11 at 12:53:23.
 */
class banner_course_Topic_Search_SessionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var    banner_course_Topic_Search_Session
     * @access protected
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @access protected
     */
    protected function setUp()
    {
		$this->wildcardStringMatchType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:search:wildcard");
        
    	$this->mcugId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:catalog/MCUG');
        $this->miisId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:catalog/MIIS');

	 	$this->manager = $this->sharedFixture['CourseManager'];
        $this->session = $this->manager->getTopicSearchSessionForCatalog($this->mcugId);
        
        $this->termId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:term/200820');

		$this->termType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:terms');

        $this->subjectType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/subject");
        $this->departmentType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/department");
        $this->divisionType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/division");
        $this->requirementType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/requirement");
	}

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     */
    protected function tearDown()
    {
    }

    /**
     * 
     */
    public function testGetCourseCatalogId()
    {
        $this->assertType('osid_id_Id', $this->session->getCourseCatalogId());
        $this->assertTrue($this->mcugId->isEqual($this->session->getCourseCatalogId()));
    }

    /**
     * 
     */
    public function testGetCourseCatalog()
    {
        $this->assertType('osid_course_CourseCatalog', $this->session->getCourseCatalog());
        $this->assertTrue($this->mcugId->isEqual($this->session->getCourseCatalog()->getId()));
    }

    /**
     * 
     */
    public function testCanSearchTopics()
    {
        $this->assertTrue($this->session->canSearchTopics());
    }

    /**
     * 
     */
    public function testUseFederatedCourseCatalogView()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * 
     */
    public function testUseIsolatedCourseCatalogView()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * 
     */
    public function testGetTopicQuery()
    {
        $this->assertType('osid_course_TopicQuery', $this->session->getTopicQuery());
    }

    /**
     * 
     */
    public function testGetTopicsByQuery()
    {
        $this->assertType('osid_course_TopicList', $this->session->getTopicsByQuery($this->session->getTopicQuery()));
    }

    /**
     * 
     */
    public function testGetTopicSearch()
    {
        $this->assertType('osid_course_TopicSearch', $this->session->getTopicSearch());
    }

    /**
     * 
     */
    public function testGetTopicSearchOrder()
    {
        $this->assertType('osid_course_TopicSearchOrder', $this->session->getTopicSearchOrder());
    }

    /**
     * 
     */
    public function testGetTopicsBySearch()
    {
        $this->assertType('osid_course_TopicSearchResults', $this->session->getTopicsBySearch($this->session->getTopicQuery(), $this->session->getTopicSearch()));
    }
}
?>