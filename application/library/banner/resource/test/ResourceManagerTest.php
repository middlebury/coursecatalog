<?php
require_once 'PHPUnit/Framework.php';

/**
 * Test class for banner_resource_ResourceManager.
 * Generated by PHPUnit on 2009-05-04 at 09:45:33.
 */
class banner_resource_test_ResourceManagerTest
	extends phpkit_test_phpunit_AbstractOsidManagerTest
{
    /**
     * @var    banner_course_CourseManager
     * @access protected
     */
    protected $manager;
    
    /**
	 * Answer the manager object to test
	 * 
	 * @return osid_OsidManager
	 * @access protected
	 * @since 4/15/09
	 */
	protected function getManager () {
		return $this->manager;
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
     * @todo Implement testGetDB().
     */
    public function testGetDB()
    {
        $this->assertType('PDO', $this->manager->getDB());
    }

    /**
     * 
     */
    public function testGetIdAuthority()
    {
        $this->assertEquals('middlebury.edu', $this->manager->getIdAuthority());
    }

    /**
     * Ensure that we cannot initialize the manager again.
     * @expectedException osid_IllegalStateException
     */
    public function testInitialize()
    {
        $this->manager->initialize($this->sharedFixture['RuntimeManager']);
    }

    /**
     * 
     */
    public function testGetResourceLookupSession()
    {
        // If supported, validate our session response
    	if ($this->manager->supportsResourceLookup()) {
    		 $this->assertType('osid_resource_ResourceLookupSession', $this->manager->getResourceLookupSession());
    	} 
    	// Otherwise, ensure that the propper exception is thrown
    	else {
    		try {
				$this->manager->getResourceLookupSession();
				$this->fail('Should have thrown an osid_UnimplementedException.');
			} catch (osid_UnimplementedException $e) {
				$this->assertTrue(true);
			}
    	}
    }

    /**
     * 
     */
    public function testGetResourceLookupSessionForBin()
    {
        // If supported, validate our session response
    	if ($this->manager->supportsResourceLookup()) {
    		 $this->assertType('osid_resource_ResourceLookupSession', $this->manager->getResourceLookupSessionForBin($this->allBinId));
    	} 
    	// Otherwise, ensure that the propper exception is thrown
    	else {
    		try {
				$this->manager->getResourceLookupSessionForBin($this->allBinId);
				$this->fail('Should have thrown an osid_UnimplementedException.');
			} catch (osid_UnimplementedException $e) {
				$this->assertTrue(true);
			}
    	}
    }

    /**
     * 
     */
    public function testGetResourceSearchSession()
    {
    	// If supported, validate our session response
    	if ($this->manager->supportsResourceSearch()) {
    		 $this->assertType('osid_resource_ResourceSearchSession', $this->manager->getResourceSearchSession());
    	} 
    	// Otherwise, ensure that the propper exception is thrown
    	else {
    		try {
				$this->manager->getResourceSearchSession();
				$this->fail('Should have thrown an osid_UnimplementedException.');
			} catch (osid_UnimplementedException $e) {
				$this->assertTrue(true);
			}
    	}
       
    }

    /**
     * 
     */
    public function testGetResourceSearchSessionForBin()
    {
    	// If supported, validate our session response
    	if ($this->manager->supportsResourceSearch()) {
    		 $this->assertType('osid_resource_ResourceSearchSession', $this->manager->getResourceSearchSessionForBin($this->allBinId));
    	} 
    	// Otherwise, ensure that the propper exception is thrown
    	else {
    		try {
				$this->manager->getResourceSearchSessionForBin($this->allBinId);
				$this->fail('Should have thrown an osid_UnimplementedException.');
			} catch (osid_UnimplementedException $e) {
				$this->assertTrue(true);
			}
    	}
       
    }

    /**
     * 
     */
    public function testGetResourceAdminSession()
    {
    	// If supported, validate our session response
    	if ($this->manager->supportsResourceAdmin()) {
    		 $this->assertType('osid_resource_ResourceAdminSession', $this->manager->getResourceAdminSession());
    	} 
    	// Otherwise, ensure that the propper exception is thrown
    	else {
    		try {
				$this->manager->getResourceAdminSession();
				$this->fail('Should have thrown an osid_UnimplementedException.');
			} catch (osid_UnimplementedException $e) {
				$this->assertTrue(true);
			}
    	}
       
    }

    /**
     * 
     */
    public function testGetResourceAdminSessionForBin()
    {
    	// If supported, validate our session response
    	if ($this->manager->supportsResourceAdmin()) {
    		 $this->assertType('osid_resource_ResourceAdminSession', $this->manager->getResourceAdminSessionForBin($this->allBinId));
    	} 
    	// Otherwise, ensure that the propper exception is thrown
    	else {
    		try {
				$this->manager->getResourceAdminSessionForBin($this->allBinId);
				$this->fail('Should have thrown an osid_UnimplementedException.');
			} catch (osid_UnimplementedException $e) {
				$this->assertTrue(true);
			}
    	}
       
    }

    /**
     * 
     */
    public function testGetResourceNotificationSession()
    {
    	// If supported, validate our session response
    	if ($this->manager->supportsResourceNotification()) {
    		 $this->assertType('osid_resource_ResourceNotificationSession', $this->manager->getResourceNotificationSession(new phpkit_resource_DummyResourceReceiver));
    	} 
    	// Otherwise, ensure that the propper exception is thrown
    	else {
    		try {
				$this->manager->getResourceNotificationSession(new phpkit_resource_DummyResourceReceiver);
				$this->fail('Should have thrown an osid_UnimplementedException.');
			} catch (osid_UnimplementedException $e) {
				$this->assertTrue(true);
			}
    	}
       
    }

    /**
     * 
     */
    public function testGetResourceNotificationSessionForBin()
    {
    	// If supported, validate our session response
    	if ($this->manager->supportsResourceNotification()) {
    		 $this->assertType('osid_resource_ResourceNotificationSession', $this->manager->getResourceNotificationSessionForBin(new phpkit_resource_DummyResourceReceiver, $this->allBinId));
    	} 
    	// Otherwise, ensure that the propper exception is thrown
    	else {
    		try {
				$this->manager->getResourceNotificationSessionForBin(new phpkit_resource_DummyResourceReceiver, $this->allBinId);
				$this->fail('Should have thrown an osid_UnimplementedException.');
			} catch (osid_UnimplementedException $e) {
				$this->assertTrue(true);
			}
    	}
       
    }

    /**
     * 
     */
    public function testGetResourceBinSession()
    {
    	// If supported, validate our session response
    	if ($this->manager->supportsResourceBin()) {
    		 $this->assertType('osid_resource_ResourceBinSession', $this->manager->getResourceBinSession());
    	} 
    	// Otherwise, ensure that the propper exception is thrown
    	else {
    		try {
				$this->manager->getResourceBinSession();
				$this->fail('Should have thrown an osid_UnimplementedException.');
			} catch (osid_UnimplementedException $e) {
				$this->assertTrue(true);
			}
    	}
       
    }

    /**
     * 
     */
    public function testGetResourceBinAssignmentSession()
    {
    	// If supported, validate our session response
    	if ($this->manager->supportsResourceBinAssignment()) {
    		 $this->assertType('osid_resource_ResourceBinAssignmentSession', $this->manager->getResourceBinAssignmentSession());
    	} 
    	// Otherwise, ensure that the propper exception is thrown
    	else {
    		try {
				$this->manager->getResourceBinAssignmentSession();
				$this->fail('Should have thrown an osid_UnimplementedException.');
			} catch (osid_UnimplementedException $e) {
				$this->assertTrue(true);
			}
    	}
       
    }

    /**
     * 
     */
    public function testGetBinLookupSession()
    {
    	// If supported, validate our session response
    	if ($this->manager->supportsBinLookup()) {
    		 $this->assertType('osid_resource_BinLookupSession', $this->manager->getBinLookupSession());
    	} 
    	// Otherwise, ensure that the propper exception is thrown
    	else {
    		try {
				$this->manager->getBinLookupSession();
				$this->fail('Should have thrown an osid_UnimplementedException.');
			} catch (osid_UnimplementedException $e) {
				$this->assertTrue(true);
			}
    	}
       
    }

    /**
     * 
     */
    public function testGetBinSearchSession()
    {
    	// If supported, validate our session response
    	if ($this->manager->supportsBinSearch()) {
    		 $this->assertType('osid_resource_BinSearchSession', $this->manager->getBinSearchSession());
    	} 
    	// Otherwise, ensure that the propper exception is thrown
    	else {
    		try {
				$this->manager->getBinSearchSession();
				$this->fail('Should have thrown an osid_UnimplementedException.');
			} catch (osid_UnimplementedException $e) {
				$this->assertTrue(true);
			}
    	}
       
    }

    /**
     * 
     */
    public function testGetBinAdminSession()
    {
    	// If supported, validate our session response
    	if ($this->manager->supportsBinAdmin()) {
    		 $this->assertType('osid_resource_BinAdminSession', $this->manager->getBinAdminSession());
    	} 
    	// Otherwise, ensure that the propper exception is thrown
    	else {
    		try {
				$this->manager->getBinAdminSession();
				$this->fail('Should have thrown an osid_UnimplementedException.');
			} catch (osid_UnimplementedException $e) {
				$this->assertTrue(true);
			}
    	}
       
    }

    /**
     * 
     */
    public function testGetBinNotificationSession()
    {
    	// If supported, validate our session response
    	if ($this->manager->supportsBinNotification()) {
    		 $this->assertType('osid_resource_BinNotificationSession', $this->manager->getBinNotificationSession(new phpkit_resource_DummyBinReceiver));
    	} 
    	// Otherwise, ensure that the propper exception is thrown
    	else {
    		try {
				$this->manager->getBinNotificationSession(new phpkit_resource_DummyBinReceiver);
				$this->fail('Should have thrown an osid_UnimplementedException.');
			} catch (osid_UnimplementedException $e) {
				$this->assertTrue(true);
			}
    	}
       
    }

    /**
     * 
     */
    public function testGetBinHierarchySession()
    {
    	// If supported, validate our session response
    	if ($this->manager->supportsBinHierarchy()) {
    		 $this->assertType('osid_resource_BinHierarchySession', $this->manager->getBinHierarchySession());
    	} 
    	// Otherwise, ensure that the propper exception is thrown
    	else {
    		try {
				$this->manager->getBinHierarchySession();
				$this->fail('Should have thrown an osid_UnimplementedException.');
			} catch (osid_UnimplementedException $e) {
				$this->assertTrue(true);
			}
    	}
       
    }

    /**
     * 
     */
    public function testGetBinHierarchyDesignSession()
    {
    	// If supported, validate our session response
    	if ($this->manager->supportsBinHierarchyDesign()) {
    		 $this->assertType('osid_resource_BinHierarchyDesignSession', $this->manager->getBinHierarchyDesignSession());
    	} 
    	// Otherwise, ensure that the propper exception is thrown
    	else {
    		try {
				$this->manager->getBinHierarchyDesignSession();
				$this->fail('Should have thrown an osid_UnimplementedException.');
			} catch (osid_UnimplementedException $e) {
				$this->assertTrue(true);
			}
    	}
       
    }

    /**
     * 
     */
    public function testSupportsVisibleFederation()
    {
        $this->assertFalse($this->manager->supportsVisibleFederation());
    }

    /**
     * 
     */
    public function testSupportsResourceLookup()
    {
        $this->assertTrue($this->manager->supportsResourceLookup());
    }

    /**
     * 
     */
    public function testSupportsResourceSearch()
    {
        $this->assertFalse($this->manager->supportsResourceSearch());
    }

    /**
     * 
     */
    public function testSupportsResourceAdmin()
    {
        $this->assertFalse($this->manager->supportsResourceAdmin());
    }

    /**
     * 
     */
    public function testSupportsResourceNotification()
    {
        $this->assertFalse($this->manager->supportsResourceNotification());
    }

    /**
     * 
     */
    public function testSupportsResourceBin()
    {
        $this->assertFalse($this->manager->supportsResourceBin());
    }

    /**
     * 
     */
    public function testSupportsResourceBinAssignment()
    {
        $this->assertFalse($this->manager->supportsResourceBinAssignment());
    }

    /**
     * 
     */
    public function testSupportsBinLookup()
    {
        $this->assertFalse($this->manager->supportsBinLookup());
    }

    /**
     * 
     */
    public function testSupportsBinSearch()
    {
        $this->assertFalse($this->manager->supportsBinSearch());
    }

    /**
     * 
     */
    public function testSupportsBinAdmin()
    {
        $this->assertFalse($this->manager->supportsBinAdmin());
    }

    /**
     * 
     */
    public function testSupportsBinNotification()
    {
        $this->assertFalse($this->manager->supportsBinNotification());
    }

    /**
     * 
     */
    public function testSupportsBinHierarchy()
    {
        $this->assertFalse($this->manager->supportsBinHierarchy());
    }

    /**
     * 
     */
    public function testSupportsBinHierarchyDesign()
    {
        $this->assertFalse($this->manager->supportsBinHierarchyDesign());
    }

    /**
     * 
     */
    public function testSupportsBinHierarchySequencing()
    {
        $this->assertFalse($this->manager->supportsBinHierarchySequencing());
    }

    /**
     * 
     */
    public function testGetResourceRecordTypes()
    {
        $types = $this->manager->getResourceRecordTypes();
        $this->assertType('osid_type_TypeList', $types);
        
        // Check for any needed types or an empty list.
        $this->assertFalse($types->hasNext());
    }

    /**
     * 
     */
    public function testSupportsResourceRecordType()
    {
    	// Check support for any types listed
        $types = $this->manager->getResourceRecordTypes();
        while ($types->hasNext()) {
        	$this->assertTrue($this->manager->supportsResourceRecordType($types->getNextType()));
        }
        
        // Check for not supporting another type
        $this->assertFalse($this->manager->supportsResourceRecordType(new phpkit_type_URNInetType('urn:inet:middlebury.edu:nonexistanttype')));
    }

    /**
     * 
     */
    public function testGetResourceSearchRecordTypes()
    {
        $types = $this->manager->getResourceSearchRecordTypes();
        $this->assertType('osid_type_TypeList', $types);
        
        // Check for any needed types or an empty list.
        $this->assertFalse($types->hasNext());
    }

    /**
     * 
     */
    public function testSupportsResourceSearchRecordType()
    {
    	// Check support for any types listed
        $types = $this->manager->getResourceSearchRecordTypes();
        while ($types->hasNext()) {
        	$this->assertTrue($this->manager->supportsResourceSearchRecordType($types->getNextType()));
        }
        
        // Check for not supporting another type
        $this->assertFalse($this->manager->supportsResourceSearchRecordType(new phpkit_type_URNInetType('urn:inet:middlebury.edu:nonexistanttype')));
    }

    /**
     * 
     */
    public function testGetBinRecordTypes()
    {
        $types = $this->manager->getBinRecordTypes();
        $this->assertType('osid_type_TypeList', $types);
        
        // Check for any needed types or an empty list.
        $this->assertFalse($types->hasNext());
    }

    /**
     * 
     */
    public function testSupportsBinRecordType()
    {
    	// Check support for any types listed
        $types = $this->manager->getBinRecordTypes();
        while ($types->hasNext()) {
        	$this->assertTrue($this->manager->supportsBinRecordType($types->getNextType()));
        }
        
        // Check for not supporting another type
        $this->assertFalse($this->manager->supportsBinRecordType(new phpkit_type_URNInetType('urn:inet:middlebury.edu:nonexistanttype')));
    }

    /**
     * 
     */
    public function testGetBinSearchRecordTypes()
    {
        $types = $this->manager->getBinSearchRecordTypes();
        $this->assertType('osid_type_TypeList', $types);
        
        // Check for any needed types or an empty list.
        $this->assertFalse($types->hasNext());
    }

    /**
     * 
     */
    public function testSupportsBinSearchRecordType()
    {
    	// Check support for any types listed
        $types = $this->manager->getBinSearchRecordTypes();
        while ($types->hasNext()) {
        	$this->assertTrue($this->manager->supportsBinSearchRecordType($types->getNextType()));
        }
        
        // Check for not supporting another type
        $this->assertFalse($this->manager->supportsBinSearchRecordType(new phpkit_type_URNInetType('urn:inet:middlebury.edu:nonexistanttype')));
    }
}
?>
