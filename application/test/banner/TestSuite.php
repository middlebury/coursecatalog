<?php

set_include_path(realpath(dirname(__FILE__).'/../') . PATH_SEPARATOR . get_include_path());

require_once(dirname(__FILE__).'/../../autoload.php');

class banner_TestSuite extends PHPUnit_Framework_TestSuite
{
	public static function suite()
    {
        $suite = new banner_TestSuite('banner.course');
        
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
    
    /**
     * Load the banner testing database
     * 
     * @access public
     * @since 6/11/09
     */
    public function loadBannerDb () {
        $this->runtimeManager = new phpkit_AutoloadOsidRuntimeManager(dirname(__FILE__).'/configuration.plist');
        $this->courseManager = $this->runtimeManager->getManager(osid_OSID::COURSE(), 'banner_course_CourseManager', '3.0.0');
        
        // Initialize our testing database
        $db = $this->courseManager->getDB();
        harmoni_SQLUtils::runSQLfile(dirname(__FILE__).'/sql/drop_tables.sql', $db);
        harmoni_SQLUtils::runSQLfile(APPLICATION_PATH.'/library/banner/sql/table_creation.sql', $db);
        harmoni_SQLUtils::runSQLfile(dirname(__FILE__).'/sql/test_data.sql', $db);
        
        // Build our full-text search index.
        $searchSession = $this->courseManager->getCourseOfferingSearchSession();
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
    public function emptyBannerDbAndClose () {
        // Remove our testing database
        $db = $this->courseManager->getDB();
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
        
        $this->courseManager->shutdown();
        $this->runtimeManager->shutdown();
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
    
    private $minMemory = '300M';
    private $currentMemory = null;
    
    protected function setMemoryLimit() {
    	$minBytes = $this->asBytes($this->minMemory);
    	$currentBytes = $this->asBytes(ini_get('memory_limit'));
    	if ($currentBytes < $minBytes) {
    		$this->currentMemory = ini_get('memory_limit');
    		ini_set('memory_limit', $this->minMemory);
    	}
    }
    
    /**
     * return the memory limit to its previous value
     * 
     * @return void
     * @access private
     * @since 11/12/09
     */
    protected function resetMemoryLimit () {
    	if (!is_null($this->currentMemory)) {
    		ini_set('memory_limit', $this->currentMemory);
    	}
    }
    
    private function asBytes($val) {
		$val = trim($val);
		$last = strtolower($val[strlen($val)-1]);
		switch($last) {
			// The 'G' modifier is available since PHP 5.1.0
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}
	
		return $val;
	}
}
?>