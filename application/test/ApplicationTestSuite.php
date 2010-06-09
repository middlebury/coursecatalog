<?php

require_once 'PHPUnit/Framework.php';

set_include_path(dirname(__FILE__) . PATH_SEPARATOR . get_include_path());

require_once(dirname(__FILE__).'/../autoload.php');

class ApplicationTestSuite extends PHPUnit_Framework_TestSuite
{
	public static function suite()
    {
        $suite = new banner_TestSuite('Application');
        
        self::recursiveAddTests($suite, dirname(__FILE__).'/Catalog');
        
        return $suite;
    }
    
    protected function setUp()
    {
    	$this->sharedFixture = banner_TestSuite::loadBannerDbAndGetSharedArray();
    	Zend_Controller_Action_HelperBroker::getStaticHelper('Osid')->setConfigPath(dirname(__FILE__).'/banner/configuration.plist');
    }
 
    protected function tearDown()
    {
		banner_TestSuite::emptyBannerDbAndCloseSharedArray($this->shareedFixture);
        $this->sharedFixture = NULL;
    }
    
    /**
     * Recursively add test files in subdirectories
     * 
     * @param PHPUnit_Framework_TestSuite $suite
     * @param string $dir
     * @return void
     * @access protected
     * @since 6/3/09
     */
    protected static function recursiveAddTests (PHPUnit_Framework_TestSuite $suite, $dir) {
    	foreach (scandir($dir) as $file) {
        	if (preg_match('/Test\.php$/', $file)) {
        		$suite->addTestFile($dir.'/'.$file);
        	} else if (is_dir($dir.'/'.$file) && preg_match('/^[a-z0-9]+/i', $file)) {
        		self::recursiveAddTests($suite, $dir.'/'.$file);
        	}
        }
    }
}
?>