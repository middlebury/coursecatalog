<?php
require_once 'PHPUnit/Framework.php';

/**
 * Test class for banner_course_CourseOfferingLookupSession.
 * Generated by PHPUnit on 2009-04-17 at 14:47:28.
 */
class banner_course_test_CourseOfferingLookupSessionTest 
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
        $this->session = $this->manager->getCourseOfferingLookupSessionForCatalog($this->mcugId);
        
        $this->physId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:course/PHYS0201');
        $this->mathId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:course/MATH0300');
        $this->chemId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:course/CHEM0104');
        
       	$this->physOfferingId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:section/200893/90143');
       	$this->geolOfferingId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:section/200420/20663');
        $this->unknownOfferingId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:section/178293/2101');
        
        $this->termId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:term/200893');
        
        $this->unknownType = new phpkit_type_URNInetType("urn:inet:osid.org:unknown_type");
    	
        $this->generaNoneType = new phpkit_type_URNInetType("urn:inet:osid.org:genera:none");
        $this->secondaryType = new phpkit_type_URNInetType("urn:inet:osid.org:genera:secondary");
        $this->undergraduateType = new phpkit_type_URNInetType("urn:inet:osid.org:genera:undergraduate");
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
    public function testCanLookupCourseOfferings()
    {
        $this->assertTrue($this->session->canLookupCourseOfferings());
    }

    /**
     * 
     */
    public function testUseComparativeCourseOfferingView()
    {
       $this->session->useComparativeCourseOfferingView();
       $offerings = $this->session->getCourseOfferingsByIds(new phpkit_id_ArrayIdList(array(
       					$this->physOfferingId,
       					$this->geolOfferingId,
       					$this->unknownOfferingId)));
       $this->assertEquals(2, $offerings->available());
    }

    /**
     * Should thrown osid_NotFoundExceptions for unknown results.
     * @expectedException osid_NotFoundException
     */
    public function testUsePlenaryCourseOfferingView()
    {
        $this->session->usePlenaryCourseOfferingView();
        $offerings = $this->session->getCourseOfferingsByIds(new phpkit_id_ArrayIdList(array(
       					$this->physOfferingId,
       					$this->geolOfferingId,
       					$this->unknownOfferingId)));
    }

    /**
     * 
     */
    public function testUseFederatedCourseCatalogView()
    {
        $this->session->useComparativeCourseOfferingView();
        $this->session->useFederatedCourseCatalogView();
     	$offerings = $this->session->getCourseOfferingsByIds(new phpkit_id_ArrayIdList(array(
       					$this->physOfferingId,
       					$this->geolOfferingId,
       					$this->unknownOfferingId)));
       	$this->assertEquals(2, $offerings->available());
       	
       	$this->session->usePlenaryCourseOfferingView();
       	try {
       		$offerings = $this->session->getCourseOfferingsByIds(new phpkit_id_ArrayIdList(array(
       					$this->physOfferingId,
       					$this->geolOfferingId,
       					$this->unknownOfferingId)));
       		$this->fail('should have thrown an osid_NotFoundException.');
       	} catch (osid_NotFoundException $e) {
       		$this->assertTrue(true);
       	}
    }

    /**
     * 
     */
    public function testUseIsolatedCourseCatalogView()
    {
        $this->session->useComparativeCourseOfferingView();
        $this->session->useIsolatedCourseCatalogView();
     	$offerings = $this->session->getCourseOfferingsByIds(new phpkit_id_ArrayIdList(array(
       					$this->physOfferingId,
       					$this->geolOfferingId,
       					$this->unknownOfferingId)));
       	$this->assertEquals(2, $offerings->available());
       	
       	$this->session->usePlenaryCourseOfferingView();
       	try {
       		$offerings = $this->session->getCourseOfferingsByIds(new phpkit_id_ArrayIdList(array(
       					$this->physOfferingId,
       					$this->geolOfferingId,
       					$this->unknownOfferingId)));
       		$this->fail('Should have thrown an osid_NotFoundException');
       	} catch (osid_NotFoundException $e) {
       		$this->assertTrue(true);
       	}
       	
       	$this->session->usePlenaryCourseOfferingView();
		$offerings = $this->session->getCourseOfferingsByIds(new phpkit_id_ArrayIdList(array(
					$this->physOfferingId,
					$this->geolOfferingId)));
		$this->assertEquals(2, $offerings->available());
    }

    /**
     * 
     */
    public function testGetCourseOffering()
    {
        $this->assertType('osid_course_CourseOffering', $this->session->getCourseOffering($this->physOfferingId));
    }

    /**
     * 
     */
    public function testGetCourseOfferingsByIds()
    {
        $offerings = $this->session->getCourseOfferingsByIds(new phpkit_id_ArrayIdList(array(
					$this->physOfferingId,
					$this->geolOfferingId)));
		$this->assertEquals(2, $offerings->available());
		while ($offerings->hasNext()) {
			$this->assertType('osid_course_CourseOffering', $offerings->getNextCourseOffering());
		}
    }

    /**
     * 
     */
    public function testGetCourseOfferingsByGenusType()
    {
        $offerings = $this->session->getCourseOfferingsByGenusType($this->generaNoneType);
       	$this->assertType('osid_course_CourseOfferingList', $offerings);
       	$this->assertTrue($offerings->hasNext());
       	
       	$offerings = $this->session->getCourseOfferingsByGenusType($this->secondaryType);
       	$this->assertType('osid_course_CourseOfferingList', $offerings);
       	$this->assertFalse($offerings->hasNext());
    }

    /**
     * 
     */
    public function testGetCourseOfferingsByParentGenusType()
    {
        $offerings = $this->session->getCourseOfferingsByParentGenusType($this->generaNoneType);
       	$this->assertType('osid_course_CourseOfferingList', $offerings);
       	$this->assertTrue($offerings->hasNext());
       	
       	$offerings = $this->session->getCourseOfferingsByParentGenusType($this->secondaryType);
       	$this->assertType('osid_course_CourseOfferingList', $offerings);
       	$this->assertFalse($offerings->hasNext());
    }

    /**
     * 
     */
    public function testGetCourseOfferingsByRecordType()
    {
        $offerings = $this->session->getCourseOfferingsByParentGenusType($this->unknownType);
       	$this->assertType('osid_course_CourseOfferingList', $offerings);
       	$this->assertFalse($offerings->hasNext());
    }

    /**
     * 
     */
    public function testGetCourseOfferingsForCourse()
    {
        $offerings = $this->session->getCourseOfferingsForCourse($this->physId);
       	$this->assertType('osid_course_CourseOfferingList', $offerings);
       	$this->assertEquals(16, $offerings->available());
       	while ($offerings->hasNext()) {
       		$offering = $offerings->getNextCourseOffering();
       		$this->assertTrue($this->physId->isEqual($offering->getCourseId()));
       	}
    }

    /**
     * 
     */
    public function testGetCourseOfferingsByTerm()
    {
        $offerings = $this->session->getCourseOfferingsByTerm($this->termId);
       	$this->assertType('osid_course_CourseOfferingList', $offerings);
       	$this->assertEquals(7, $offerings->available());
       	$i = 0;
       	while ($offerings->hasNext() && $i < 10) {
       		$offering = $offerings->getNextCourseOffering();
       		$this->assertTrue($this->termId->isEqual($offering->getTermId()));
       		$i++;
       	}
    }

    /**
     * 
     */
    public function testGetCourseOfferingsByTermForCourse()
    {
        $offerings = $this->session->getCourseOfferingsByTermForCourse($this->termId, $this->physId);
       	$this->assertType('osid_course_CourseOfferingList', $offerings);
       	$this->assertEquals(1, $offerings->available());
       	while ($offerings->hasNext()) {
       		$offering = $offerings->getNextCourseOffering();
       		$this->assertTrue($this->termId->isEqual($offering->getTermId()));
       		$this->assertTrue($this->physId->isEqual($offering->getCourseId()));
       	}
       	
       	$offerings = $this->session->getCourseOfferingsByTermForCourse($this->termId, $this->chemId);
       	$this->assertType('osid_course_CourseOfferingList', $offerings);
       	$this->assertEquals(4, $offerings->available());
       	while ($offerings->hasNext()) {
       		$offering = $offerings->getNextCourseOffering();
       		$this->assertTrue($this->termId->isEqual($offering->getTermId()));
       		$this->assertTrue($this->chemId->isEqual($offering->getCourseId()));
       	}
    }

    /**
     * 
     */
    public function testGetCourseOfferings()
    {        
        $offerings = $this->session->getCourseOfferings();
        
       	$this->assertType('osid_course_CourseOfferingList', $offerings);
       	$this->assertEquals(228, $offerings->available());
       	
       	$this->assertTrue($offerings->hasNext());
       	$this->assertType('osid_course_CourseOffering', $offerings->getNextCourseOffering());
       	
       	$offerings->skip($offerings->available() - 1);
       	$this->assertTrue($offerings->hasNext());
       	$this->assertType('osid_course_CourseOffering', $offerings->getNextCourseOffering());
       	$this->assertFalse($offerings->hasNext());
    }
}
?>
