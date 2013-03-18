<?php

/**
 * Test class for banner_course_CourseOffering_Search_Order.
 * Generated by PHPUnit on 2009-05-28 at 14:06:03.
 */
class banner_course_CourseOffering_Search_OrderTest
	extends PHPUnit_Framework_TestCase
{
    /**
     * @var    banner_course_CourseOffering_Search_Order
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


        $this->manager = $this->sharedFixture['CourseManager'];
    	$this->mcugId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:catalog/MCUG');
        $this->session = $this->manager->getCourseOfferingSearchSessionForCatalog($this->mcugId);
        
        $this->object = $this->session->getCourseOfferingSearchOrder();

        $this->termId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:term/200420');
		        
        $this->query = $this->session->getCourseOfferingQuery();
        $this->query->matchTermId($this->termId, true);
        
        $this->search = $this->session->getCourseOfferingSearch();
        
        $this->physOfferingId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:section/200890/90143');
       	$this->geolOfferingId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:section/200420/20663');
       	$this->chemOfferingId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:section/200420/20073');
        
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
    	$this->search->orderCourseOfferingResults($this->object);
    	$results = $this->session->getCourseOfferingsBySearch($this->query, $this->search);
//     	print_r($results->debug());
        $this->assertEquals(8, $results->getResultSize());
    }

    /**
     *  
     */
    public function testGetOrderByClause()
    {
        $this->assertType('string', $this->object->getOrderByClause());
        $this->assertEquals('', $this->object->getOrderByClause());
    }

    /**
     *  
     */
    public function testGetAdditionalTableJoins()
    {
        $this->assertType('array', $this->object->getAdditionalTableJoins());
        $this->assertEquals(0, count($this->object->getAdditionalTableJoins()));
    }

    /**
     *  
     */
    public function testAscend()
    {
        $this->object->orderByTitle();
        $this->object->ascend();
        $this->assertEquals('ORDER BY section_title ASC', $this->object->getOrderByClause());
        $this->object->descend();
        $this->object->ascend();
        $this->assertEquals('ORDER BY section_title ASC', $this->object->getOrderByClause());
        
    }

    /**
     *  
     */
    public function testDescend()
    {
        $this->object->orderByTitle();
        $this->object->descend();
        $this->assertEquals('ORDER BY section_title DESC', $this->object->getOrderByClause());
        $this->object->ascend();
        $this->object->descend();
        $this->assertEquals('ORDER BY section_title DESC', $this->object->getOrderByClause());
    }

    /**
     *  
     */
    public function testOrderByDisplayName()
    {
    	$this->object->orderByDisplayName();
    	$this->object->descend();
        $this->assertEquals('ORDER BY SSBSECT_SUBJ_CODE DESC, SSBSECT_CRSE_NUMB DESC, SSBSECT_SEQ_NUMB DESC, term_display_label DESC, SSBSECT_TERM_CODE DESC', $this->object->getOrderByClause());
        $this->assertEquals(0, count($this->object->getAdditionalTableJoins()));
    }

    /**
     *  
     */
    public function testOrderByGenusType()
    {
        $this->object->orderByGenusType();
    	$this->object->descend();
        $this->assertEquals('ORDER BY SSBSECT_SCHD_CODE DESC', $this->object->getOrderByClause());
        $this->assertEquals(0, count($this->object->getAdditionalTableJoins()));
    }

    /**
     *  
     */
    public function testHasRecordType()
    {
        $this->assertTrue($this->object->hasRecordType($this->instructorsType));
        $this->assertFalse($this->object->hasRecordType($this->otherType));
    }

    /**
     *  
     */
    public function testImplementsRecordType()
    {
    	$record = $this->object->getCourseSearchOrderRecord($this->instructorsType);
        $this->assertTrue($record->implementsRecordType($this->instructorsType));
        $this->assertFalse($record->implementsRecordType($this->otherType));
    }

    /**
     *  
     */
    public function testGetCourseOfferingSearchOrder()
    {
        $record = $this->object->getCourseSearchOrderRecord($this->instructorsType);
        $order = $record->getCourseOfferingSearchOrder();
        $this->assertType('osid_course_CourseOfferingSearchOrder', $order);
    }

    /**
     *  
     */
    public function testGetCourseSearchOrderRecord()
    {
        $record = $this->object->getCourseSearchOrderRecord($this->instructorsType);
        $this->assertType('osid_course_CourseOfferingSearchOrderRecord', $record);
    }
    
     /**
     *  @expectedException osid_UnsupportedException
     */
    public function testGetCourseSearchOrderRecordOther()
    {
        $record = $this->object->getCourseSearchOrderRecord($this->otherType);
        $this->assertType('osid_course_CourseOfferingSearchOrderRecord', $record);
    }

    /**
     *  
     */
    public function testOrderByTitle()
    {
        $this->object->orderByTitle();
        $this->object->descend();
        $this->assertEquals('ORDER BY section_title DESC', $this->object->getOrderByClause());
        
    }

    /**
     *  
     */
    public function testOrderByNumber()
    {
        $this->object->orderByDisplayName();
    	$this->object->descend();
        $this->assertEquals('ORDER BY SSBSECT_SUBJ_CODE DESC, SSBSECT_CRSE_NUMB DESC, SSBSECT_SEQ_NUMB DESC, term_display_label DESC, SSBSECT_TERM_CODE DESC', $this->object->getOrderByClause());
        $this->assertEquals(0, count($this->object->getAdditionalTableJoins()));
    }

    /**
     *  
     */
    public function testOrderByCredits()
    {
        $this->object->orderByCredits();
    	$this->object->descend();
        $this->assertEquals('ORDER BY SSBSECT_CREDIT_HRS DESC', $this->object->getOrderByClause());
        $this->assertEquals(0, count($this->object->getAdditionalTableJoins()));
    }

    /**
     *  
     */
    public function testOrderByPrereqInfo()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     *  
     */
    public function testOrderByCourse()
    {
        $this->object->orderByCourse();
    	$this->object->descend();
        $this->assertEquals('ORDER BY SSBSECT_SUBJ_CODE DESC, SSBSECT_CRSE_NUMB DESC', $this->object->getOrderByClause());
        $this->assertEquals(0, count($this->object->getAdditionalTableJoins()));
    }

    /**
     *  
     */
    public function testSupportsCourseSearchOrder()
    {
        $this->assertFalse($this->object->supportsCourseSearchOrder());
    }

    /**
     *  @expectedException osid_UnimplementedException
     */
    public function testGetCourseSearchOrder()
    {
        $this->object->getCourseSearchOrder();
    }

    /**
     *  
     */
    public function testOrderByTerm()
    {
        $this->object->orderByTerm();
    	$this->object->descend();
        $this->assertEquals('ORDER BY SSBSECT_TERM_CODE DESC', $this->object->getOrderByClause());
        $this->assertEquals(0, count($this->object->getAdditionalTableJoins()));
    }

    /**
     *  
     */
    public function testSupportsTermSearchOrder()
    {
        $this->assertFalse($this->object->supportsTermSearchOrder());
    }

    /**
     *  @expectedException osid_UnimplementedException
     */
    public function testGetTermSearchOrder()
    {
        $this->object->getTermSearchOrder();
    }

    /**
     *  
     */
    public function testOrderByLocationInfo()
    {
        $this->object->orderByLocationInfo();
    	$this->object->descend();
        $this->assertEquals('ORDER BY SSRMEET_BLDG_CODE DESC, SSRMEET_ROOM_CODE DESC', $this->object->getOrderByClause());
        $this->assertEquals(0, count($this->object->getAdditionalTableJoins()));
    }

    /**
     *  
     */
    public function testOrderByLocation()
    {
        $this->object->orderByLocation();
    	$this->object->descend();
        $this->assertEquals('ORDER BY SSRMEET_BLDG_CODE DESC, SSRMEET_ROOM_CODE DESC', $this->object->getOrderByClause());
        $this->assertEquals(0, count($this->object->getAdditionalTableJoins()));
    }

    /**
     *  
     */
    public function testSupportsLocationSearchOrder()
    {
        $this->assertFalse($this->object->supportsLocationSearchOrder());
    }

    /**
     *  @expectedException osid_UnimplementedException
     */
    public function testGetLocationSearchOrder()
    {
        $this->object->getLocationSearchOrder();
    }

    /**
     *  
     */
    public function testOrderByScheduleInfo()
    {
        $this->object->orderByScheduleInfo();
    	$this->object->descend();
        $this->assertEquals('ORDER BY SSRMEET_START_DATE DESC, SSRMEET_END_DATE DESC, SSRMEET_SUN_DAY DESC, SSRMEET_MON_DAY DESC, SSRMEET_TUE_DAY DESC, SSRMEET_WED_DAY DESC, SSRMEET_THU_DAY DESC, SSRMEET_FRI_DAY DESC, SSRMEET_SAT_DAY DESC, SSRMEET_BEGIN_TIME DESC, SSRMEET_END_TIME DESC', $this->object->getOrderByClause());
        $this->assertEquals(0, count($this->object->getAdditionalTableJoins()));
    }

    /**
     *  
     */
    public function testOrderByCalendar()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     *  
     */
    public function testSupportsCalendarSearchOrder()
    {
        $this->assertFalse($this->object->supportsCalendarSearchOrder());
    }

    /**
     *  @expectedException osid_UnimplementedException
     */
    public function testGetCalendarSearchOrder()
    {
        $this->object->getCalendarSearchOrder();
    }

    /**
     *  
     */
    public function testOrderByLearningObjective()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     *  
     */
    public function testSupportsLearningObjectiveSearchOrder()
    {
        $this->assertFalse($this->object->supportsLearningObjectiveSearchOrder());
    }

    /**
     *  @expectedException osid_UnimplementedException
     */
    public function testGetLearningObjectiveSearchOrder()
    {
        $this->object->getLearningObjectiveSearchOrder();
    }

    /**
     *  
     */
    public function testOrderByURL()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     *  
     */
    public function testOrderByInstructor()
    {
        $this->object->orderByInstructor();
    	$this->object->descend();
        $this->assertEquals('ORDER BY SYVINST_LAST_NAME DESC, SYVINST_FIRST_NAME DESC', $this->object->getOrderByClause());
        $this->assertEquals(1, count($this->object->getAdditionalTableJoins()));
    }
    
/*********************************************************
 * Methods from middlebury_course_CourseOffering_Search_InstructorsSearchOrderRecord
 *********************************************************/

	/**
     * 
     */
    public function testOrderByMeetsSunday() {
    	$this->object->orderByMeetsSunday();
    	$this->object->ascend();
        $this->assertEquals('ORDER BY SSRMEET_SUN_DAY DESC', $this->object->getOrderByClause());
        $this->assertEquals(0, count($this->object->getAdditionalTableJoins()));
    }

	/**
     * 
     */
    public function testOrderByMeetsMonday() {
    	$this->object->orderByMeetsMonday();
    	$this->object->ascend();
        $this->assertEquals('ORDER BY SSRMEET_MON_DAY DESC', $this->object->getOrderByClause());
        $this->assertEquals(0, count($this->object->getAdditionalTableJoins()));
    }
    
	/**
     * 
     */
    public function testOrderByMeetsTuesday() {
    	$this->object->orderByMeetsTuesday();
    	$this->object->ascend();
        $this->assertEquals('ORDER BY SSRMEET_TUE_DAY DESC', $this->object->getOrderByClause());
        $this->assertEquals(0, count($this->object->getAdditionalTableJoins()));
    }
	
	/**
     * 
     */
    public function testOrderByMeetsWednesday() {
    	$this->object->orderByMeetsWednesday();
    	$this->object->ascend();
        $this->assertEquals('ORDER BY SSRMEET_WED_DAY DESC', $this->object->getOrderByClause());
        $this->assertEquals(0, count($this->object->getAdditionalTableJoins()));
    }
    
    /**
     * 
     */
    public function testOrderByMeetsThursday() {
    	$this->object->orderByMeetsThursday();
    	$this->object->ascend();
        $this->assertEquals('ORDER BY SSRMEET_THU_DAY DESC', $this->object->getOrderByClause());
        $this->assertEquals(0, count($this->object->getAdditionalTableJoins()));
    }
    
    /**
     * 
     */
    public function testOrderByMeetsFriday() {
    	$this->object->orderByMeetsFriday();
    	$this->object->ascend();
        $this->assertEquals('ORDER BY SSRMEET_FRI_DAY DESC', $this->object->getOrderByClause());
        $this->assertEquals(0, count($this->object->getAdditionalTableJoins()));
    }
    
    /**
     * 
     */
    public function testOrderByMeetsSaturday() {
    	$this->object->orderByMeetsSaturday();
    	$this->object->ascend();
        $this->assertEquals('ORDER BY SSRMEET_SAT_DAY DESC', $this->object->getOrderByClause());
        $this->assertEquals(0, count($this->object->getAdditionalTableJoins()));
    }
    
    /**
     * 
     */
    public function testOrderByMeetingTime () {
    	$this->object->orderByMeetingTime();
    	$this->object->ascend();
        $this->assertEquals('ORDER BY SSRMEET_BEGIN_TIME ASC', $this->object->getOrderByClause());
        $this->assertEquals(0, count($this->object->getAdditionalTableJoins()));
    }
    
}
?>
