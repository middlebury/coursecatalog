<?php
require_once 'PHPUnit/Framework.php';

/**
 * Test class for banner_course_CourseOfferingCatalogSession.
 * Generated by PHPUnit on 2009-04-23 at 13:53:35.
 */
class banner_course_test_CourseOfferingCatalogSessionTest 
	extends phpkit_test_phpunit_AbstractOsidSessionTest
{
    /**
     * @var    banner_course_CourseOfferingCatalogLookupSession
     * @access protected
     */
    protected $session;
    
    protected $mcugId;
    protected $miisId;

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
        $this->manager = $this->sharedFixture['CourseManager'];
        $this->session = $this->manager->getCourseOfferingCatalogSession();
        
        $this->mcugId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:catalog/MCUG');
        $this->miisId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:catalog/MIIS');
        $this->unknownId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:unknown_id');
        
        $this->sectionId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:section/200893/92418');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     */
    protected function tearDown()
    {
    	$this->session->close();
    }

    /**
     * @todo Implement testUseComparativeCourseCatalogView().
     */
    public function testUseComparativeCourseOfferingCatalogView()
    {
       $this->session->useComparativeCourseOfferingCatalogView();
       $courseOfferingIds = $this->session->getCourseOfferingIdsByCatalogs(new phpkit_id_ArrayIdList(array(
       					$this->mcugId,
       					$this->unknownId)));
       $this->assertType('osid_id_IdList', $courseOfferingIds);
       $this->assertEquals(107, $courseOfferingIds->available());
       
       $courseOfferings = $this->session->getCourseOfferingsByCatalogs(new phpkit_id_ArrayIdList(array(
       					$this->mcugId,
       					$this->unknownId)));
       $this->assertType('osid_course_CourseOfferingList', $courseOfferings);
       $this->assertEquals(107, $courseOfferings->available());
    }

    /**
     * @todo Implement testUsePlenaryCourseOfferingCatalogView().
     */
    public function testUsePlenaryCourseOfferingCatalogView()
    {
       $this->session->usePlenaryCourseOfferingCatalogView();
       try {
	       $courseOfferingIds = $this->session->getCourseOfferingIdsByCatalogs(new phpkit_id_ArrayIdList(array(
    	   					$this->unknownId,
    	   					$this->mcugId
       						)));
	       $this->fail('Should have thrown an osid_NotFoundException');
	   } catch (osid_NotFoundException $e) {
	   }
	   
	   try {
	       $courseOfferings = $this->session->getCourseOfferingsByCatalogs(new phpkit_id_ArrayIdList(array(
    	   					$this->unknownId,
    	   					$this->mcugId
       						)));
	       $this->fail('Should have thrown an osid_NotFoundException');
	   } catch (osid_NotFoundException $e) {
	   }
    }

    /**
     * @todo Implement testCanLookupCourseOfferingCatalogMappings().
     */
    public function testCanLookupCourseOfferingCatalogMappings()
    {
        $this->assertTrue($this->session->canLookupCourseOfferingCatalogMappings());
    }

    /**
     * @todo Implement testGetCourseOfferingIdsByCatalog().
     */
    public function testGetCourseOfferingIdsByCatalog()
    {
       $courseOfferingIds = $this->session->getCourseOfferingIdsByCatalog($this->mcugId);
       $this->assertType('osid_id_IdList', $courseOfferingIds);
       $this->assertEquals(107, $courseOfferingIds->available());
       $this->assertType('osid_id_Id', $courseOfferingIds->getNextId());
    }

    /**
     * @todo Implement testGetCourseOfferingsByCatalog().
     */
    public function testGetCourseOfferingsByCatalog()
    {
       $courseOfferings = $this->session->getCourseOfferingsByCatalog($this->mcugId);
       $this->assertType('osid_course_CourseOfferingList', $courseOfferings);
       $this->assertEquals(107, $courseOfferings->available());
       $this->assertType('osid_course_CourseOffering', $courseOfferings->getNextCourseOffering());
    }

    /**
     * @todo Implement testGetCourseOfferingIdsByCatalogs().
     */
    public function testGetCourseOfferingIdsByCatalogs()
    {
       $courseOfferingIds = $this->session->getCourseOfferingIdsByCatalogs(new phpkit_id_ArrayIdList(array(
       					$this->mcugId)));
       $this->assertType('osid_id_IdList', $courseOfferingIds);
       $this->assertEquals(107, $courseOfferingIds->available());
       $this->assertType('osid_id_Id', $courseOfferingIds->getNextId());
    }

    /**
     * @todo Implement testGetCourseOfferingsByCatalogs().
     */
    public function testGetCourseOfferingsByCatalogs()
    {
       $courseOfferings = $this->session->getCourseOfferingsByCatalogs(new phpkit_id_ArrayIdList(array(
       					$this->mcugId)));
       $this->assertType('osid_course_CourseOfferingList', $courseOfferings);
       $this->assertEquals(107, $courseOfferings->available());
       $this->assertType('osid_course_CourseOffering', $courseOfferings->getNextCourseOffering());
    }

    /**
     * @todo Implement testGetCatalogIdsByCourseOffering().
     */
    public function testGetCatalogIdsByCourseOffering()
    {
        $catalogIds = $this->session->getCatalogIdsByCourseOffering($this->sectionId);
        $this->assertEquals(1, $catalogIds->available());
        $this->assertTrue($catalogIds->getNextId()->isEqual($this->mcugId));
    }

    /**
     * @todo Implement testGetCatalogsByCourseOffering().
     */
    public function testGetCatalogsByCourseOffering()
    {
        $catalogs = $this->session->getCatalogsByCourseOffering($this->sectionId);
        $this->assertEquals(1, $catalogs->available());
        $this->assertTrue($catalogs->getNextCourseCatalog()->getId()->isEqual($this->mcugId));
    }
}
?>
