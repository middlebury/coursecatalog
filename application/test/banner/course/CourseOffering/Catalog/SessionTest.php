<?php

/**
 * Test class for banner_course_CourseOffering_Catalog_Session.
 * Generated by PHPUnit on 2009-04-23 at 13:53:35.
 */
class banner_course_CourseOffering_Catalog_SessionTest extends phpkit_test_phpunit_AbstractOsidSessionTest
{
    use banner_DatabaseTestTrait;

    /**
     * @var banner_course_CourseOfferingCatalogLookupSession
     */
    protected osid_course_CourseOfferingCatalogSession $session;

    private osid_id_Id $mcugId;
    private osid_id_Id $miisId;
    private osid_id_Id $unknownId;
    private osid_id_Id $sectionId;

    /**
     * Answer the session object to test.
     *
     * @return osid_OsidSession
     *
     * @since 4/15/09
     */
    protected function getSession()
    {
        return $this->session;
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->session = self::$courseManager->getCourseOfferingCatalogSession();

        $this->mcugId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:catalog.MCUG');
        $this->miisId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:catalog.MIIS');
        $this->unknownId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:unknown_id');

        $this->sectionId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:section.200890.92418');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
        $this->session->close();
    }

    /**
     * @todo Implement testUseComparativeCourseCatalogView().
     */
    public function testUseComparativeCourseOfferingCatalogView()
    {
        $this->session->useComparativeCourseOfferingCatalogView();
        $courseOfferingIds = $this->session->getCourseOfferingIdsByCatalogs(new phpkit_id_ArrayIdList([
            $this->mcugId,
            $this->unknownId]));
        $this->assertInstanceOf('osid_id_IdList', $courseOfferingIds);
        $this->assertEquals(94, $courseOfferingIds->available());

        $courseOfferings = $this->session->getCourseOfferingsByCatalogs(new phpkit_id_ArrayIdList([
            $this->mcugId,
            $this->unknownId]));
        $this->assertInstanceOf('osid_course_CourseOfferingList', $courseOfferings);
        $this->assertEquals(94, $courseOfferings->available());
    }

    /**
     * @todo Implement testUsePlenaryCourseOfferingCatalogView().
     */
    public function testUsePlenaryCourseOfferingCatalogView()
    {
        $this->session->usePlenaryCourseOfferingCatalogView();
        try {
            $courseOfferingIds = $this->session->getCourseOfferingIdsByCatalogs(new phpkit_id_ArrayIdList([
                $this->unknownId,
                $this->mcugId,
            ]));
            $this->fail('Should have thrown an osid_NotFoundException');
        } catch (osid_NotFoundException $e) {
            $this->assertTrue(true, 'The expected exception was thrown.');
        }

        try {
            $courseOfferings = $this->session->getCourseOfferingsByCatalogs(new phpkit_id_ArrayIdList([
                $this->unknownId,
                $this->mcugId,
            ]));
            $this->fail('Should have thrown an osid_NotFoundException');
        } catch (osid_NotFoundException $e) {
            $this->assertTrue(true, 'The expected exception was thrown.');
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
        $this->assertInstanceOf('osid_id_IdList', $courseOfferingIds);
        $this->assertEquals(94, $courseOfferingIds->available());
        $this->assertInstanceOf('osid_id_Id', $courseOfferingIds->getNextId());
    }

    /**
     * @todo Implement testGetCourseOfferingsByCatalog().
     */
    public function testGetCourseOfferingsByCatalog()
    {
        $courseOfferings = $this->session->getCourseOfferingsByCatalog($this->mcugId);
        $this->assertInstanceOf('osid_course_CourseOfferingList', $courseOfferings);
        $this->assertEquals(94, $courseOfferings->available());
        $this->assertInstanceOf('osid_course_CourseOffering', $courseOfferings->getNextCourseOffering());
    }

    /**
     * @todo Implement testGetCourseOfferingIdsByCatalogs().
     */
    public function testGetCourseOfferingIdsByCatalogs()
    {
        $courseOfferingIds = $this->session->getCourseOfferingIdsByCatalogs(new phpkit_id_ArrayIdList([
            $this->mcugId]));
        $this->assertInstanceOf('osid_id_IdList', $courseOfferingIds);
        $this->assertEquals(94, $courseOfferingIds->available());
        $this->assertInstanceOf('osid_id_Id', $courseOfferingIds->getNextId());
    }

    /**
     * @todo Implement testGetCourseOfferingsByCatalogs().
     */
    public function testGetCourseOfferingsByCatalogs()
    {
        $courseOfferings = $this->session->getCourseOfferingsByCatalogs(new phpkit_id_ArrayIdList([
            $this->mcugId]));
        $this->assertInstanceOf('osid_course_CourseOfferingList', $courseOfferings);
        $this->assertEquals(94, $courseOfferings->available());
        $this->assertInstanceOf('osid_course_CourseOffering', $courseOfferings->getNextCourseOffering());
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
