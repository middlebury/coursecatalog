<?php

require_once 'PHPUnit/Framework.php';

require_once(dirname(__FILE__).'/../../../autoload.php');

class banner_test_TestSuite extends PHPUnit_Framework_TestSuite
{
	public static function suite()
    {
        $suite = new banner_test_TestSuite('banner');
        
        foreach (scandir(dirname(__FILE__)) as $file) {
        	if (preg_match('/Test\.php$/', $file))
        		$suite->addTestFile(dirname(__FILE__).'/'.$file);
        }
        
        $suite->addTestSuite('banner_course_test_TestSuite');
        
        
        return $suite;
    }
    
    protected function setUp()
    {
    	
    }
 
    protected function tearDown()
    {

    }
}
?>