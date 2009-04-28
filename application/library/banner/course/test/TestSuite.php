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
        
        if (method_exists($db, 'resetCounters')) {
	       $db->resetCounters();
	       $db->recordDuplicates();
	    }
    }
 
    protected function tearDown()
    {
    	$this->sharedFixture['CourseManager']->shutdown();
//     	$this->sharedFixture['RuntimeManager']->shutdown();
        
        // Remove our testing database
        $db = $this->sharedFixture['CourseManager']->getDB();
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
        harmoni_SQLUtils::runSQLfile(dirname(__FILE__).'/../sql/drop_tables.sql', $db);
        
        $this->sharedFixture = NULL;
    }
}
?>