<?php

set_include_path(realpath(dirname(__FILE__).'/../') . PATH_SEPARATOR . get_include_path());

require_once(dirname(__FILE__).'/../../autoload.php');

class apc_TestSuite extends banner_TestSuite
{
	public static function suite()
    {
        $suite = new apc_TestSuite('apc.course');
        
        banner_TestSuite::recursiveAddTests($suite, dirname(__FILE__).'/../banner/course');
        banner_TestSuite::recursiveAddTests($suite, dirname(__FILE__).'/../banner/resource');
        
        return $suite;
    }
    
    protected function setUp()
    {
    	$this->setMemoryLimit();
    	$this->sharedFixture = banner_TestSuite::loadBannerDbAndGetSharedArray();
    	
    	// Replace the course manager with our APC one after setup is complete.
    	$this->sharedFixture['BannerCourseManager'] = $this->sharedFixture['CourseManager'];
    	unset($this->sharedFixture['CourseManager']);
    	$this->sharedFixture['CourseManager'] = $this->sharedFixture['RuntimeManager']->getManager(osid_OSID::COURSE(), 'apc_course_CourseManager', '3.0.0');
    }
 
    protected function tearDown()
    {
    	// Shut down our course
    	$this->sharedFixture['CourseManager']->shutdown();
    	$this->sharedFixture['CourseManager'] = $this->sharedFixture['BannerCourseManager'];
    	
        banner_TestSuite::emptyBannerDbAndCloseSharedArray($this->sharedFixture);
        $this->sharedFixture = NULL;
        $this->resetMemoryLimit();
    }
}
?>