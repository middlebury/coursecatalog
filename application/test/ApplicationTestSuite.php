<?php

require_once 'PHPUnit/Framework.php';

set_include_path(dirname(__FILE__) . PATH_SEPARATOR . get_include_path());

require_once(dirname(__FILE__).'/../autoload.php');

class ApplicationTestSuite extends PHPUnit_Framework_TestSuite
{
	public static function suite()
    {
        $suite = new banner_TestSuite('Application');
        
        $suite->addTestFile(dirname(__FILE__).'/AbstractCatalogControllerTest.php');
        
        return $suite;
    }
    
    protected function setUp()
    {
    	$this->sharedFixture = banner_TestSuite::loadBannerDbAndGetSharedArray();
    	AbstractCatalogController::setConfigPath(dirname(__FILE__).'/banner/configuration.plist');
    }
 
    protected function tearDown()
    {
		banner_TestSuite::emptyBannerDbAndCloseSharedArray($this->shareedFixture);
        $this->sharedFixture = NULL;
    }
}
?>