<?php
require_once 'PHPUnit/Framework.php';

/**
 * Test class for banner_course_CourseLookupSession.
 * Generated by PHPUnit on 2009-04-15 at 11:59:13.
 */
class banner_course_CourseLookupSessionTest 
	extends banner_course_test_SessionTestAbstract
{
    /**
     * @var    banner_course_CourseLookupSession
     * @access protected
     */
    protected $session;
    
    protected $mcugId;
    protected $miisId;

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
        $this->session = $this->manager->getCourseLookupSessionForCatalog($this->mcugId);
        
        $this->physId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:course/PHYS0201');
        $this->mathId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:course/MATH0300');
        $this->unknownId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:course/XXXX0101');
        
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
    	$this->session->close();
    }

    /**
     * 
     */
    public function testGetCourseCatalogId()
    {
        $id = $this->session->getCourseCatalogId();
        $this->assertType('osid_id_Id', $id);
        $this->assertTrue($this->mcugId->isEqual($id));
    }

    /**
     * 
     */
    public function testGetCourseCatalog()
    {
        $this->assertType('osid_course_CourseCatalog', $this->session->getCourseCatalog());
    }

    /**
     *
     */
    public function testCanLookupCourses()
    {
        $this->assertTrue($this->session->canLookupCourses());
    }

    /**
     * 
     */
    public function testUseComparativeCourseView()
    {
       $this->session->useComparativeCourseView();
       $courses = $this->session->getCoursesByIds(new phpkit_id_ArrayIdList(array(
       					$this->physId,
       					$this->mathId,
       					$this->unknownId)));
       $this->assertEquals(2, $courses->available());
    }

    /**
     * Should thrown osid_NotFoundExceptions for unknown results.
     * @expectedException osid_NotFoundException
     */
    public function testUsePlenaryCourseView()
    {
        $this->session->usePlenaryCourseView();
        $courses = $this->session->getCoursesByIds(new phpkit_id_ArrayIdList(array(
       					$this->physId,
       					$this->mathId,
       					$this->unknownId)));
        $this->assertEquals(2, $courses->available());
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
     * @todo Implement testGetCourse().
     */
    public function testGetCourse()
    {
        $this->assertType('osid_course_Course', $this->session->getCourse($this->physId));
    }

    /**
     * @todo Implement testGetCoursesByIds().
     */
    public function testGetCoursesByIds()
    {
        $this->session->usePlenaryCourseView();
        $courses = $this->session->getCoursesByIds(new phpkit_id_ArrayIdList(array(
       					$this->physId,
       					$this->mathId)));
       	$this->assertType('osid_course_CourseList', $courses);
        $this->assertEquals(2, $courses->available());
        $this->assertTrue($courses->hasNext());
        $this->assertType('osid_course_Course', $courses->getNextCourse());
        $this->assertTrue($courses->hasNext());
        $this->assertType('osid_course_Course', $courses->getNextCourse());
        $this->assertFalse($courses->hasNext());
    }

    /**
     * @todo Implement testGetCoursesByGenusType().
     */
    public function testGetCoursesByGenusType()
    {
        $courses = $this->session->getCoursesByGenusType($this->generaNoneType);
       	$this->assertType('osid_course_CourseList', $courses);
       	$this->assertTrue($courses->hasNext());
       	
       	$courses = $this->session->getCoursesByGenusType($this->secondaryType);
       	$this->assertType('osid_course_CourseList', $courses);
       	$this->assertFalse($courses->hasNext());
    }

    /**
     * @todo Implement testGetCoursesByParentGenusType().
     */
    public function testGetCoursesByParentGenusType()
    {
        $courses = $this->session->getCoursesByParentGenusType($this->generaNoneType);
       	$this->assertType('osid_course_CourseList', $courses);
       	$this->assertTrue($courses->hasNext());
       	
       	$courses = $this->session->getCoursesByParentGenusType($this->secondaryType);
       	$this->assertType('osid_course_CourseList', $courses);
       	$this->assertFalse($courses->hasNext());
    }

    /**
     * @todo Implement testGetCoursesByRecordType().
     */
    public function testGetCoursesByRecordType()
    {
        $courses = $this->session->getCoursesByRecordType($this->unknownType);
       	$this->assertType('osid_course_CourseList', $courses);
       	$this->assertFalse($courses->hasNext());
    }

    /**
     * @todo Implement testGetCourses().
     */
    public function testGetCourses()
    {
        $courses = $this->session->getCourses();
       	$this->assertType('osid_course_CourseList', $courses);
       	
       	$this->assertGreaterThan(1, $courses->available());
       	$this->assertLessThan(10000, $courses->available());
       	
       	$this->assertTrue($courses->hasNext());
       	$this->assertType('osid_course_Course', $courses->getNextCourse());
       	
       	$courses->skip($courses->available() - 1);
       	$this->assertTrue($courses->hasNext());
       	$this->assertType('osid_course_Course', $courses->getNextCourse());
       	$this->assertFalse($courses->hasNext());
    }
}
?>
