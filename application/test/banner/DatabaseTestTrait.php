<?php

trait banner_DatabaseTestTrait {

	public static $runtimeManager;
	public static $courseManager;

	public static function setUpBeforeClass(): void
	{
		self::setUpDatabase();
	}

	public static function tearDownAfterClass(): void
	{
		self::tearDownDatabase();
	}

	public static function setUpDatabase(): void
	{
		self::setMemoryLimit();
		try {
			self::loadBannerDb();
		} catch (Exception $e) {
			print $e->getMessage()."\n";
			throw $e;
		}
	}

	public static function tearDownDatabase(): void
	{
		try {
			self::emptyBannerDbAndClose();
		} catch (Exception $e) {
			print $e->getMessage()."\n";
			throw $e;
		}
		self::resetMemoryLimit();
	}

	/**
	 * Load the banner testing database
	 *
	 * @access public
	 * @since 6/11/09
	 */
	public static function loadBannerDb () {
		self::$runtimeManager = new phpkit_AutoloadOsidRuntimeManager(dirname(__FILE__).'/configuration.plist');
		self::$courseManager = self::$runtimeManager->getManager(osid_OSID::COURSE(), 'banner_course_CourseManager', '3.0.0');

		// Initialize our testing database
		$db = self::$courseManager->getDB();
		harmoni_SQLUtils::runSQLfile(dirname(__FILE__).'/sql/drop_tables.sql', $db);
		harmoni_SQLUtils::runSQLfile(APPLICATION_PATH.'/library/banner/sql/table_creation.sql', $db);
		harmoni_SQLUtils::runSQLfile(dirname(__FILE__).'/sql/test_data.sql', $db);

		// Build our full-text search index.
		$searchSession = self::$courseManager->getCourseOfferingSearchSession();
		$searchSession->buildIndex(false);

		if (method_exists($db, 'resetCounters')) {
		$db->resetCounters();
		$db->recordDuplicates();
		}
	}

	/**
	 * Destroy the data in the banner testing database and shut down the managers.
	 *
	 * @return void
	 * @access public
	 * @since 6/11/09
	 */
	public static function emptyBannerDbAndClose () {
		// Remove our testing database
		$db = self::$courseManager->getDB();
		if (method_exists($db, 'getCounters')) {
			$maxName = 0;
			$maxNum = 0;
			foreach ($db->getCounters() as $name => $num) {
				$maxName = max($maxName, strlen($name));
				$maxNum = max($maxNum, strlen(strval($num)));
			}
			print "\n";
			foreach ($db->getCounters() as $name => $num) {
				print "\n".$name;
				for ($i = strlen($name); $i < $maxName + 1; $i++)
					print ' ';
				print str_pad($num, $maxNum, ' ', STR_PAD_LEFT);
			}
			print "\n";

			try {
				$dupes = $db->getDuplicates();
				$totalDupes = 0;
				ob_start();
				foreach ($db->getDuplicates() as $dup) {
					print "\nDuplicated ". $dup['count']." times:\n";
					print $dup['query'];
					$totalDupes = $totalDupes + $dup['count'];
				}
				$detail = ob_get_clean();
				print "\nTotal duplicated statement preparations: ".$totalDupes;
				if ($totalDupes)
					print "\n";
				print $detail;
				print "\n";
			} catch (Exception $e) {
				print "\nDuplicate statement preparations not recorded.\n";
			}
		}
		harmoni_SQLUtils::runSQLfile(dirname(__FILE__).'/sql/drop_tables.sql', $db);

		self::$courseManager->shutdown();
		self::$runtimeManager->shutdown();
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

	private static $minMemory = '300M';
	private static $currentMemory = null;

	protected static function setMemoryLimit() {
		$minBytes = self::asBytes(self::$minMemory);
		$currentBytes = self::asBytes(ini_get('memory_limit'));
		if ($currentBytes < $minBytes) {
			self::$currentMemory = ini_get('memory_limit');
			ini_set('memory_limit', self::$minMemory);
		}
	}

	/**
	 * return the memory limit to its previous value
	 *
	 * @return void
	 * @access private
	 * @since 11/12/09
	 */
	protected static function resetMemoryLimit () {
		if (!is_null(self::$currentMemory)) {
			ini_set('memory_limit', self::$currentMemory);
		}
	}

	private static function asBytes($val) {
		$val = trim($val);
		$last = strtolower($val[strlen($val)-1]);
		$num = substr($val, 0, strlen($val) - 1);
		switch($last) {
			// The 'G' modifier is available since PHP 5.1.0
			case 'g':
				$num *= 1024;
			case 'm':
				$num *= 1024;
			case 'k':
				$num *= 1024;
		}

		return $num;
	}
}
