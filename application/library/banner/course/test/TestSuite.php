<?php

require_once 'PHPUnit/Framework.php';

require_once(dirname(__FILE__).'/../../../../autoload.php');

class banner_course_test_TestSuite extends PHPUnit_Framework_TestSuite
{
	public static function suite()
    {
        $suite = new banner_course_test_TestSuite('banner.course');
        
        foreach (scandir(dirname(__FILE__)) as $file) {
        	if (preg_match('/Test\.php$/', $file))
        		$suite->addTestFile(dirname(__FILE__).'/'.$file);
        }
        
        return $suite;
    }
    
    protected function setUp()
    {
    	$this->sharedFixture = array();
    	$this->sharedFixture['RuntimeManager'] = new phpkit_AutoloadOsidRuntimeManager(dirname(__FILE__).'/configuration.plist');
        $this->sharedFixture['CourseManager'] = $this->sharedFixture['RuntimeManager']->getManager(osid_OSID::COURSE(), 'banner_course_CourseManager', '3.0.0');
    }
 
    protected function tearDown()
    {
    	$this->sharedFixture['CourseManager']->shutdown();
//     	$this->sharedFixture['RuntimeManager']->shutdown();
        $this->sharedFixture = NULL;
    }
}
?>