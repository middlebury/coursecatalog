<?php

set_include_path(realpath(dirname(__FILE__).'/../') . PATH_SEPARATOR . get_include_path());

require_once(dirname(__FILE__).'/../../autoload.php');

class apc_TestSuite extends banner_TestSuite
{
	public static function suite()
	{
		$suite = new apc_TestSuite('apc.course');

		self::recursiveAddTests($suite, dirname(__FILE__).'/course');
		self::recursiveAddTests($suite, dirname(__FILE__).'/resource');

		return $suite;
	}

	protected function setUp()
	{
		$this->setMemoryLimit();
		$this->loadBannerDb();
	}

	protected function tearDown()
	{
		$this->emptyBannerDbAndClose();
		$this->resetMemoryLimit();
	}
}
