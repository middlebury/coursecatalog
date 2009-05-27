<?php
require_once 'PHPUnit/Framework.php';

/**
 * Test class for banner_course_CourseOfferingSearchSession.
 * Generated by PHPUnit on 2009-05-20 at 13:10:33.
 */
class banner_course_CourseOfferingSearchSessionTest
	extends phpkit_test_phpunit_AbstractOsidSessionTest
{
/**
     * @var    banner_course_CourseCatalogLookupSession
     * @access protected
     */
    protected $session;
    
    /**
	 * Answer the session object to test
	 * 
	 * @return osid_OsidSession
	 * @access protected
	 * @since 4/15/09
	 */
	protected function getSession () {
		return $this->session;
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
        
        $this->manager = $this->sharedFixture['CourseManager'];
        $this->session = $this->manager->getCourseOfferingSearchSessionForCatalog($this->mcugId);
        
        $this->physId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:course/PHYS0201');
        $this->mathId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:course/MATH0300');
        $this->chemId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:course/CHEM0104');
        
       	$this->physOfferingId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:section/200890/90143');
       	$this->geolOfferingId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:section/200420/20663');
        $this->unknownOfferingId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:section/178293/2101');
        
        $this->termId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:term/200890');
        
        $this->physDeptTopicId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:topic/department/PHYS');
        $this->chemDeptTopicId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:topic/department/CHEM');
        $this->physSubjTopicId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:topic/subject/PHYS');
        $this->chemSubjTopicId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:topic/subject/CHEM');
        $this->dedReqTopicId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:topic/requirement/DED');
        $this->sciReqTopicId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:topic/requirement/SCI');
        
        $this->unknownType = new phpkit_type_URNInetType("urn:inet:osid.org:unknown_type");
    	
        $this->generaNoneType = new phpkit_type_URNInetType("urn:inet:osid.org:genera:none");
        $this->secondaryType = new phpkit_type_URNInetType("urn:inet:osid.org:genera:secondary");
        $this->undergraduateType = new phpkit_type_URNInetType("urn:inet:osid.org:genera:undergraduate");
        
        $this->wildcardStringMatchType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:search:wildcard");
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
    public function testCanSearchCourseOfferings()
    {
        $this->assertTrue($this->session->canSearchCourseOfferings());
    }

    /**
     * @todo Implement testUseFederatedCourseCatalogView().
     */
    public function testUseFederatedCourseCatalogView()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testUseIsolatedCourseCatalogView().
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
    public function testGetCourseOfferingQuery()
    {
        $this->assertType('osid_course_CourseOfferingQuery', $this->session->getCourseOfferingQuery());
    }

    /**
     * 
     */
    public function testGetCourseOfferingsByQuery()
    {
    	$query = $this->session->getCourseOfferingQuery();
    	$query->matchDisplayName('PHYS0201*', $this->wildcardStringMatchType, true);
    	
        $offerings = $this->session->getCourseOfferingsByQuery($query);
//         print $offerings->debug();
       	$this->assertType('osid_course_CourseOfferingList', $offerings);
       	$this->assertEquals(8, $offerings->available());
       	$this->assertType('osid_course_CourseOffering', $offerings->getNextCourseOffering());
    }

    /**
     * 
     */
    public function testGetCourseOfferingSearch()
    {
        $this->assertType('osid_course_CourseOfferingSearch', $this->session->getCourseOfferingSearch());
    }

    /**
     * 
     */
    public function testGetCourseOfferingSearchOrder()
    {
        $this->assertType('osid_course_CourseOfferingSearchOrder', $this->session->getCourseOfferingSearchOrder());
    }

    /**
     * @todo Implement testGetCourseOfferingsBySearch().
     */
    public function testGetCourseOfferingsBySearch()
    {
        $query = $this->session->getCourseOfferingQuery();
//     	$query->matchDisplayName('PH*', $this->wildcardStringMatchType, true);
    	
    	$query->matchDisplayName('*201*', $this->wildcardStringMatchType, true);
    	
    	$search = $this->session->getCourseOfferingSearch();
    	$search->limitResultSet(1, 3);
    	
        $results = $this->session->getCourseOfferingsBySearch($query, $search);
       	$this->assertType('osid_course_CourseOfferingSearchResults', $results);
       	$this->assertEquals(1111, $results->getResultSize());
       	
       	$offerings = $results->getCourseOfferings();
       	$this->assertEquals(1111, $offerings->available());
       	$this->assertType('osid_course_CourseOffering', $offerings->getNextCourseOffering());
    }
}
?>
