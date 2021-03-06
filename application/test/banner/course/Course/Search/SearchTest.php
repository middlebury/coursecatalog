<?php

/**
 * Test class for banner_course_Course_Search_Search.
 * Generated by PHPUnit on 2009-10-16 at 10:20:06.
 */
class banner_course_Course_Search_SearchTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var    banner_course_Course_Search_Search
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
		$this->mcugId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:catalog/MCUG');
		$this->miisId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:catalog/MIIS');
		$this->unknownId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:unknown_id');

		$this->session = self::$courseManager->getCourseSearchSessionForCatalog($this->mcugId);

		$this->wildcardStringMatchType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:search:wildcard");

		$this->query = $this->session->getCourseQuery();
		$this->query->matchNumber('*0*', $this->wildcardStringMatchType, true);

		$this->object = $this->session->getCourseSearch();

		$this->physId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:course/PHYS0201');
		$this->geolId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:course/GEOL0250');
		$this->chemId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:course/CHEM0104');
		$this->unknownId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:course/XXXX0101');

		$this->deptTopicId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:topic/department/PHYS');
		$this->subjTopicId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:topic/subject/CHEM');
		$this->divTopicId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:topic/division/NSCI');

		$this->unknownType = new phpkit_type_URNInetType("urn:inet:osid.org:unknown_type");

		$this->generaNoneType = new phpkit_type_URNInetType("urn:inet:osid.org:genera:none");
		$this->secondaryType = new phpkit_type_URNInetType("urn:inet:osid.org:genera:secondary");
		$this->undergraduateType = new phpkit_type_URNInetType("urn:inet:osid.org:genera:undergraduate");

		$this->instructorsType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors');
		$this->otherType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:other');
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
	public function testGetLimitClause()
	{
		$this->assertInternalType('string', $this->object->getLimitClause());
		$this->assertEquals('', $this->object->getLimitClause());
	}

	/**
	 *
	 */
	public function testGetOrderByClause()
	{
		$this->assertInternalType('string', $this->object->getOrderByClause());
		$this->assertEquals('', $this->object->getOrderByClause());
	}

	/**
	 *
	 */
	public function testGetWhereClause()
	{
		$this->assertInternalType('string', $this->object->getWhereClause());
		$this->assertEquals('', $this->object->getWhereClause());
	}

	/**
	 *
	 */
	public function testGetAdditionalTableJoins()
	{
		$this->assertInternalType('array', $this->object->getAdditionalTableJoins());
		$this->assertEquals(0, count($this->object->getAdditionalTableJoins()));

	}

	/**
	 *
	 */
	public function testGetParameters()
	{
		$this->assertInternalType('array', $this->object->getParameters());
		$this->assertEquals(0, count($this->object->getParameters()));
	}

	/**
	 *
	 */
	public function testLimitResultSet()
	{
		$results = $this->session->getCoursesBySearch($this->query, $this->object);
		$this->assertEquals(4, $results->getResultSize());

		$this->object->limitResultSet(1, 3);
		$this->assertEquals('LIMIT 0, 3', $this->object->getLimitClause());

		$results = $this->session->getCoursesBySearch($this->query, $this->object);
		$this->assertEquals(4, $results->getResultSize());

		$this->assertEquals(3, $results->getCourses()->available());

	}

	/**
	 *
	 */
	public function testLimitResultSet2()
	{
		$results = $this->session->getCoursesBySearch($this->query, $this->object);
		$this->assertEquals(4, $results->getResultSize());

		$this->object->limitResultSet(2, 3);
		$this->assertEquals('LIMIT 1, 2', $this->object->getLimitClause());

		$results = $this->session->getCoursesBySearch($this->query, $this->object);
		$this->assertEquals(4, $results->getResultSize());

		$this->assertEquals(2, $results->getCourses()->available());

	}

	/**
	 *  @expectedException osid_InvalidArgumentException
	 */
	public function testLimitResultSetInverted()
	{
		$this->object->limitResultSet(10, 1);
	}

	/**
	 *  @expectedException osid_InvalidArgumentException
	 */
	public function testLimitResultSetOutOfRange0()
	{
		$this->object->limitResultSet(0, 10);
	}

	/**
	 *  @expectedException osid_InvalidArgumentException
	 */
	public function testLimitResultSetOutOfRangeN1()
	{
		$this->object->limitResultSet(-1, 10);
	}

	/**
	 *  @expectedException osid_NullArgumentException
	 */
	public function testLimitResultSetOutNullStart()
	{
		$this->object->limitResultSet(null, 10);
	}

	/**
	 *  @expectedException osid_NullArgumentException
	 */
	public function testLimitResultSetOutNullEnd()
	{
		$this->object->limitResultSet(1, null);
	}

	/**
	 *
	 */
	public function testHasSearchRecordType()
	{
		$this->assertFalse($this->object->hasSearchRecordType($this->instructorsType));
		$this->assertFalse($this->object->hasSearchRecordType($this->otherType));
	}

	/**
	 *
	 */
	public function testSearchWithinCourseResults()
	{
		$query = $this->session->getCourseQuery();
		$query->matchNumber('*2*', $this->wildcardStringMatchType, true);

		$all200Results = $this->session->getCoursesBySearch($query, $this->object);
		$this->assertInstanceOf('osid_course_CourseSearchResults', $all200Results);
		$this->assertEquals(3, $all200Results->getResultSize());

		$this->object->searchWithinCourseResults($all200Results);

		$query2 = $this->session->getCourseQuery();
		$query2->matchNumber('*E*', $this->wildcardStringMatchType, true);

		$results = $this->session->getCoursesBySearch($query2, $this->object);
//     	print $results->debug();
		$this->assertInstanceOf('osid_course_CourseSearchResults', $results);
		$this->assertEquals(2, $results->getResultSize());
	}

	/**
	 *
	 */
	public function testSearchAmongCourses()
	{
		$this->query = $this->session->getCourseQuery();
		$this->query->matchNumber('*2*', $this->wildcardStringMatchType, true);

		$results = $this->session->getCoursesBySearch($this->query, $this->object);
		$this->assertEquals(3, $results->getResultSize());

		$courses = new phpkit_id_ArrayIdList(array(
			$this->physId,
			$this->chemId));
		$this->object->searchAmongCourses($courses);

		$this->assertEquals(
			'((SCBCRSE_SUBJ_CODE = ? AND SCBCRSE_CRSE_NUMB = ?)
		OR (SCBCRSE_SUBJ_CODE = ? AND SCBCRSE_CRSE_NUMB = ?))',
			$this->object->getWhereClause());

		$params = $this->object->getParameters();
		$this->assertEquals('PHYS', $params[0]);
		$this->assertEquals('0201', $params[1]);
		$this->assertEquals('CHEM', $params[2]);
		$this->assertEquals('0104', $params[3]);

		$results = $this->session->getCoursesBySearch($this->query, $this->object);
//         print $results->debug();
		$this->assertEquals(1, $results->getResultSize());
	}

	/**
	 *
	 */
	public function testOrderCourseResults()
	{
		$order = $this->session->getCourseSearchOrder();
		$order->orderByDisplayName();

		$this->object->orderCourseResults($order);

		$results = $this->session->getCoursesBySearch($this->query, $this->object);
		$this->assertEquals(4, $results->getResultSize());
	}

	/**
	 * @expectedException osid_UnsupportedException
	 */
	public function testGetCourseSearchRecord()
	{
	$this->object->getCourseSearchRecord($this->otherType);
	}
}
