<?php

set_include_path(dirname(__FILE__) . PATH_SEPARATOR . get_include_path());

require_once(dirname(__FILE__).'/../autoload.php');

class ApplicationTestSuite extends banner_TestSuite
{
	public static function suite()
	{
		$suite = new ApplicationTestSuite('Application');

		self::recursiveAddTests($suite, dirname(__FILE__).'/Catalog');

		return $suite;
	}

	protected function setUp()
	{
		$this->loadBannerDb();

		Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH.'/controllers/helper', 'Helper');
		Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH.'/resources/Catalog/Action/Helper', 'Catalog_Action_Helper');
		Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH.'/resources/Auth/Action/Helper', 'Auth_Action_Helper');

		$registry = Zend_Registry::getInstance();
		$registry->config = new Zend_Config_Ini(BASE_PATH.'/frontend_config.ini', 'development');

		Zend_Controller_Action_HelperBroker::getStaticHelper('Osid')->setConfigPath(dirname(__FILE__).'/apc/configuration.plist');

	}

	protected function tearDown()
	{
		$this->emptyBannerDbAndClose();
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
