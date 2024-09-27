<?php

use PHPUnit\Framework\TestCase;

/**
 * Test class for banner_course_Term_Catalog_Session.
 * Generated by PHPUnit on 2009-04-29 at 16:55:23.
 */
class banner_course_Term_Catalog_SessionTest extends TestCase
{
    use banner_DatabaseTestTrait;

    /**
     * @var banner_course_CourseOfferingCatalogLookupSession
     */
    protected $session;

    protected $mcugId;
    protected $miisId;

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
        $this->session = self::$courseManager->getTermCatalogSession();

        $this->mcugId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:catalog.MCUG');
        $this->miisId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:catalog.MIIS');
        $this->unknownId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:unknown_id');

        $this->termId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:term.200890');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
    }

    public function testUseComparativeTermCatalogView()
    {
        $this->session->useComparativeTermCatalogView();
        $termIds = $this->session->getTermIdsByCatalogs(new phpkit_id_ArrayIdList([
            $this->mcugId,
            $this->unknownId]));
        $this->assertInstanceOf('osid_id_IdList', $termIds);
        $this->assertEquals(13, $termIds->available());
        $this->assertInstanceOf('osid_id_Id', $termIds->getNextId());
    }

    public function testUsePlenaryTermCatalogView()
    {
        $this->expectException(osid_NotFoundException::class);

        $this->session->usePlenaryTermCatalogView();
        $termIds = $this->session->getTermIdsByCatalogs(new phpkit_id_ArrayIdList([
            $this->mcugId,
            $this->unknownId]));
    }

    /**
     * @todo Implement testCanLookupTermCatalogMappings().
     */
    public function testCanLookupTermCatalogMappings()
    {
        $this->assertTrue($this->session->canLookupTermCatalogMappings());
    }

    /**
     * @todo Implement testGetTermIdsByCatalog().
     */
    public function testGetTermIdsByCatalog()
    {
        $termIds = $this->session->getTermIdsByCatalog($this->mcugId);
        $this->assertInstanceOf('osid_id_IdList', $termIds);
        $this->assertEquals(13, $termIds->available());
        $this->assertInstanceOf('osid_id_Id', $termIds->getNextId());
    }

    /**
     * @todo Implement testGetTermsByCatalog().
     */
    public function testGetTermsByCatalog()
    {
        $terms = $this->session->getTermsByCatalog($this->mcugId);
        $this->assertInstanceOf('osid_course_TermList', $terms);
        $this->assertEquals(13, $terms->available());
        $this->assertInstanceOf('osid_course_Term', $terms->getNextTerm());
    }

    /**
     * @todo Implement testGetTermIdsByCatalogs().
     */
    public function testGetTermIdsByCatalogs()
    {
        $termIds = $this->session->getTermIdsByCatalogs(new phpkit_id_ArrayIdList([
            $this->mcugId]));
        $this->assertInstanceOf('osid_id_IdList', $termIds);
        $this->assertEquals(13, $termIds->available());
        $this->assertInstanceOf('osid_id_Id', $termIds->getNextId());
    }

    /**
     * @todo Implement testGetTermsByCatalogs().
     */
    public function testGetTermsByCatalogs()
    {
        $terms = $this->session->getTermsByCatalogs(new phpkit_id_ArrayIdList([
            $this->mcugId]));
        $this->assertInstanceOf('osid_course_TermList', $terms);
        $this->assertEquals(13, $terms->available());
        $this->assertInstanceOf('osid_course_Term', $terms->getNextTerm());
    }

    /**
     * @todo Implement testGetCatalogIdsByTerm().
     */
    public function testGetCatalogIdsByTerm()
    {
        $catalogIds = $this->session->getCatalogIdsByTerm($this->termId);
        $this->assertEquals(1, $catalogIds->available());
        $this->assertTrue($catalogIds->getNextId()->isEqual($this->mcugId));
    }

    /**
     * @todo Implement testGetCatalogsByTerm().
     */
    public function testGetCatalogsByTerm()
    {
        $catalogs = $this->session->getCatalogsByTerm($this->termId);
        $this->assertEquals(1, $catalogs->available());
        $this->assertTrue($catalogs->getNextCourseCatalog()->getId()->isEqual($this->mcugId));
    }
}
