<?php
/**
 * @since 4/15/09
 * @package banner.course.test
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

require_once 'PHPUnit/Framework.php';

/**
 * An abstract class for testing the common session methods in course sessions.
 * 
 * @since 4/15/09
 * @package banner.course.test
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
abstract class banner_course_test_SessionTestAbstract
	extends PHPUnit_Framework_TestCase
{
		
	/**
	 * 
	 */
	public function testIsAuthenticated () {
		$this->assertType('boolean', $this->session->isAuthenticated());
		
		$this->session->close();
		try {
			$this->session->isAuthenticated();
			$this->fail('Should have thrown an osid_IllegalStateException');
		} catch (osid_IllegalStateException $e) {
			$this->assertTrue(true);
		}
	}
	
	/**
	 * 
	 */
	public function testGetAuthenticatedAgents () {
		$agents = $this->session->getAuthenticatedAgents();
		$this->assertType('osid_authentication_AgentList', $agents);
		
		if ($this->session->isAuthenticated())
			$this->assertTrue($agents->hasNext());
		
		$this->session->close();
		try {
			$this->session->isAuthenticated();
			$this->fail('Should have thrown an osid_IllegalStateException');
		} catch (osid_IllegalStateException $e) {
			$this->assertTrue(true);
		}
	}
	
	/**
	 * 
	 */
	public function testSupportsTransactions () {
		$this->assertType('boolean', $this->session->supportsTransactions());
	}
	
	/**
	 * 
	 */
	public function testStartTransaction () {
		if ($this->session->supportsTransactions()) {
			$this->startTransaction();
			
			// Try starting a second transaction.
			try {
				$this->session->startTransaction();
				$this->fail('Should have thrown an osid_IllegalStateException');
			} catch (osid_IllegalStateException $e) {
				$this->assertTrue(true);
			}
		} else {
			try {
				$this->session->startTransaction();
				$this->fail('Should have thrown an osid_UnsupportedException');
			} catch (osid_UnsupportedException $e) {
				$this->assertTrue(true);
			}
		}
	}
	
	/**
	 * 
	 */
	public function testClose () {
		$this->session->close();
		try {
			$this->session->startTransaction();
			$this->fail('Should have thrown an osid_IllegalStateException');
		} catch (osid_IllegalStateException $e) {
			$this->assertTrue(true);
		}
	}
}

?>