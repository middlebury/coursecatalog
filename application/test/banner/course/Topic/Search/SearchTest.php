<?php

/**
 * Test class for banner_course_Topic_Search_Search.
 * Generated by PHPUnit on 2009-06-11 at 13:04:22.
 */
class banner_course_Topic_Search_SearchTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var    banner_course_Topic_Search_Search
	 * @access protected
	 */
	protected $object;

	static $runtimeManager;
	static $courseManager;

	public static function setUpBeforeClass()
	{
		self::$runtimeManager = new phpkit_AutoloadOsidRuntimeManager(realpath(dirname(__FILE__).'/../../../').'/configuration.plist');
		self::$courseManager = self::$runtimeManager->getManager(osid_OSID::COURSE(), 'banner_course_CourseManager', '3.0.0');
	}

	public static function tearDownAfterClass()
	{
		self::$courseManager->shutdown();
		self::$runtimeManager->shutdown();
	}

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

		$this->session = self::$courseManager->getTopicSearchSessionForCatalog($this->mcugId);
		$this->object = $this->session->getTopicSearch();

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
	public function testLimitResultSet()
	{
		$this->object->limitResultSet(1, 10);
	}

	/**
	 *
	 */
	public function testHasSearchRecordType()
	{
		$this->assertFalse($this->object->hasSearchRecordType($this->termType));
	}

	/**
	 *
	 */
	public function testSearchWithinTopicResults()
	{
		$results = $this->session->getTopicsBySearch($this->session->getTopicQuery(), $this->session->getTopicSearch());
		$this->object->searchWithinTopicResults($results);
	}

	/**
	 *
	 */
	public function testSearchAmongTopics()
	{
		$topicList = new phpkit_id_ArrayIdList(array(
			new phpkit_id_URNInetId('urn:inet:middlebury.edu:topic/requirement/DED'),
			new phpkit_id_URNInetId('urn:inet:middlebury.edu:topic/requirement/SCI')
		));

		$this->object->searchAmongTopics($topicList);
	}

	/**
	 *
	 */
	public function testOrderTopicResults()
	{
		$order = $this->session->getTopicSearchOrder();
		$order->orderByDisplayName();
		$this->object->orderTopicResults($order);
	}

	/**
	 * @expectedException osid_UnsupportedException
	 */
	public function testGetTopicSearchRecord()
	{
		$this->object->getTopicSearchRecord($this->termType);
	}
}
