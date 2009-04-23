<?php

require_once 'PHPUnit/Framework.php';

require_once(dirname(__FILE__).'/../../autoload.php');

class test_TestSuite extends PHPUnit_Framework_TestSuite
{
	public static function suite()
    {
        $suite = new test_TestSuite('CourseCatalogLibrary');
        
        foreach (scandir(dirname(__FILE__)) as $file) {
        	if (preg_match('/Test\.php$/', $file))
        		$suite->addTestFile(dirname(__FILE__).'/'.$file);
        }
        
        $suite->addTestSuite('phpkit_test_phpunit_TestSuite');
        $suite->addTestSuite('banner_test_TestSuite');
        
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