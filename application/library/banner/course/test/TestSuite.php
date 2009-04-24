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
        
        // Initialize our testing database
        $db = $this->sharedFixture['CourseManager']->getDB();
        harmoni_SQLUtils::runSQLfile(dirname(__FILE__).'/../sql/table_creation.sql', $db);
        harmoni_SQLUtils::runSQLfile(dirname(__FILE__).'/../sql/test_data.sql', $db);
    }
 
    protected function tearDown()
    {
    	$this->sharedFixture['CourseManager']->shutdown();
//     	$this->sharedFixture['RuntimeManager']->shutdown();
        
        // Remove our testing database
        $db = $this->sharedFixture['CourseManager']->getDB();
        harmoni_SQLUtils::runSQLfile(dirname(__FILE__).'/../sql/drop_tables.sql', $db);
        
        $this->sharedFixture = NULL;
    }
}
?>