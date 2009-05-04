<?php
require_once 'PHPUnit/Framework.php';

/**
 * Test class for banner_resource_ResourceLookupSession.
 * Generated by PHPUnit on 2009-05-04 at 11:11:53.
 */
class banner_resource_test_ResourceLookupSessionTest
	extends phpkit_test_phpunit_AbstractOsidSessionTest
{
    /**
     * @var    banner_course_CourseLookupSession
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
        $this->allBinId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:resource/all');
		$this->courseManager = $this->sharedFixture['CourseManager'];
        $this->manager = $this->courseManager->getResourceManager();
        $this->session = $this->manager->getResourceLookupSession();
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
     * @todo Implement testGetBinId().
     */
    public function testGetBinId()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testGetBin().
     */
    public function testGetBin()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testCanLookupResources().
     */
    public function testCanLookupResources()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testUseComparativeResourceView().
     */
    public function testUseComparativeResourceView()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testUsePlenaryResourceView().
     */
    public function testUsePlenaryResourceView()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testUseFederatedBinView().
     */
    public function testUseFederatedBinView()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testUseIsolatedBinView().
     */
    public function testUseIsolatedBinView()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testGetResource().
     */
    public function testGetResource()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testGetResourcesByIds().
     */
    public function testGetResourcesByIds()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testGetResourcesByGenusType().
     */
    public function testGetResourcesByGenusType()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testGetResourcesByParentGenusType().
     */
    public function testGetResourcesByParentGenusType()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testGetResourcesByRecordType().
     */
    public function testGetResourcesByRecordType()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testGetResources().
     */
    public function testGetResources()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }
}
?>
