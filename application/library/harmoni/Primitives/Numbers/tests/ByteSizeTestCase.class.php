<?php
/** 
 * @package harmoni.primitives.chronology.tests
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: ByteSizeTestCase.class.php,v 1.4 2007/09/04 20:25:28 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 * @since 5/25/05
 */

require_once(dirname(__FILE__)."/../ByteSize.class.php");

/**
 * A single unit test case. This class is intended to test one particular
 * class. Replace 'testedclass.php' below with the class you would like to
 * test.
 *
 * @since 5/25/05
 *
 * @package harmoni.primitives.chronology.tests
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: ByteSizeTestCase.class.php,v 1.4 2007/09/04 20:25:28 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

class ByteSizeTestCase extends UnitTestCase {
	
	/**
	*  Sets up unit test wide variables at the start
	*	 of each test method.
	*	 @access public
	*/
	function setUp() {
		$this->bNum = ByteSize::withValue(280);
		$this->kbNum = ByteSize::withValue(2800);
		$this->mbNum = ByteSize::withValue(2808093);
		$this->gbNum = ByteSize::withValue(8808033932);
		$this->reallyBigNum = ByteSize::withValue(80000000000000000000000000);
	}
	
	/**
	 *	  Clears the data set in the setUp() method call.
	 *	  @access public
	 */
	function tearDown() {
		// perhaps, unset $obj here
	}
	
	
	/**
	 * 
	 */ 
	function test_powerOf2() {
// 		print "\n<br/>bNum<br/>";
		$this->assertEqual(round($this->bNum->multipleOfPowerOf2(0), 2), 280);
		$this->assertEqual(round($this->bNum->multipleOfPowerOf2(10), 2), 0.27);
		$this->assertEqual(round($this->bNum->multipleOfPowerOf2(20), 2), 0.00);
		$this->assertEqual(round($this->bNum->multipleOfPowerOf2(30), 2), 0.00);
		$this->assertEqual(round($this->bNum->multipleOfPowerOf2(40), 2), 0.00);
		$this->assertEqual(round($this->bNum->multipleOfPowerOf2(50), 2), 0.00);
		$this->assertEqual(round($this->bNum->multipleOfPowerOf2(60), 2), 0.00);
		$this->assertEqual(round($this->bNum->multipleOfPowerOf2(70), 2), 0.00);
		$this->assertEqual(round($this->bNum->multipleOfPowerOf2(80), 2), 0.00);
		
// 		print "\n<br/>kbNum<br/>";
		$this->assertEqual(round($this->kbNum->multipleOfPowerOf2(0), 2), 2800);
		$this->assertEqual(round($this->kbNum->multipleOfPowerOf2(10), 2), 2.73);
		$this->assertEqual(round($this->kbNum->multipleOfPowerOf2(20), 2), 0.00);
		$this->assertEqual(round($this->kbNum->multipleOfPowerOf2(30), 2), 0.00);
		$this->assertEqual(round($this->kbNum->multipleOfPowerOf2(40), 2), 0.00);
		$this->assertEqual(round($this->kbNum->multipleOfPowerOf2(50), 2), 0.00);
		$this->assertEqual(round($this->kbNum->multipleOfPowerOf2(60), 2), 0.00);
		$this->assertEqual(round($this->kbNum->multipleOfPowerOf2(70), 2), 0.00);
		$this->assertEqual(round($this->kbNum->multipleOfPowerOf2(80), 2), 0.00);
		
// 		print "\n<br/>mbNum<br/>";
		$this->assertEqual(round($this->mbNum->multipleOfPowerOf2(0), 2), 2808093);
		$this->assertEqual(round($this->mbNum->multipleOfPowerOf2(10), 2), 2742.28);
		$this->assertEqual(round($this->mbNum->multipleOfPowerOf2(20), 2), 2.68);
		$this->assertEqual(round($this->mbNum->multipleOfPowerOf2(30), 2), 0.00);
		$this->assertEqual(round($this->mbNum->multipleOfPowerOf2(40), 2), 0.00);
		$this->assertEqual(round($this->mbNum->multipleOfPowerOf2(50), 2), 0.00);
		$this->assertEqual(round($this->mbNum->multipleOfPowerOf2(60), 2), 0.00);
		$this->assertEqual(round($this->mbNum->multipleOfPowerOf2(70), 2), 0.00);
		$this->assertEqual(round($this->mbNum->multipleOfPowerOf2(80), 2), 0.00);
		
// 		print "\n<br/>gbNum<br/>";
		$this->assertEqual(round($this->gbNum->multipleOfPowerOf2(0), 2), 8808033932);
		$this->assertEqual(round($this->gbNum->multipleOfPowerOf2(10), 2), 8601595.64);
		$this->assertEqual(round($this->gbNum->multipleOfPowerOf2(20), 2), 8400.00);
		$this->assertEqual(round($this->gbNum->multipleOfPowerOf2(30), 2), 8.20);
		$this->assertEqual(round($this->gbNum->multipleOfPowerOf2(40), 2), 0.01);
		$this->assertEqual(round($this->gbNum->multipleOfPowerOf2(50), 2), 0.00);
		$this->assertEqual(round($this->gbNum->multipleOfPowerOf2(60), 2), 0.00);
		$this->assertEqual(round($this->gbNum->multipleOfPowerOf2(70), 2), 0.00);
		$this->assertEqual(round($this->gbNum->multipleOfPowerOf2(80), 2), 0.00);
		
// 		print "\n<br/>reallybignum<br/>";
		$this->assertEqual(round($this->reallyBigNum->multipleOfPowerOf2(0), 2), 80000000000000000000000000);
		$this->assertEqual(round($this->reallyBigNum->multipleOfPowerOf2(40), 2), 72759576141834.27);
		$this->assertEqual(round($this->reallyBigNum->multipleOfPowerOf2(50), 2), 71054273576.01);
		$this->assertEqual(round($this->reallyBigNum->multipleOfPowerOf2(60), 2), 69388939.04);
		$this->assertEqual(round($this->reallyBigNum->multipleOfPowerOf2(70), 2), 67762.64);
		$this->assertEqual(round($this->reallyBigNum->multipleOfPowerOf2(80), 2), 66.17);
	}
	
	function test_printableString() {
		$this->assertEqual($this->bNum->printableString(), '280 B');
		$this->assertEqual($this->kbNum->printableString(), '2.73 kB');
		$this->assertEqual($this->mbNum->printableString(), '2.68 MB');
		$this->assertEqual($this->gbNum->printableString(), '8.20 GB');
		$this->assertEqual($this->reallyBigNum->printableString(), '66.17 YB');
	}
	
	function test_fromString() {
		$num = ByteSize::fromString('280 B');
		$this->assertEqual($num->printableString(), '280 B');
		$this->assertEqual(round($num->multipleOfPowerOf2(0), 2), 280);
		
		$num = ByteSize::fromString('2800 B');
		$this->assertEqual($num->printableString(), '2.73 kB');
		$this->assertEqual(round($num->multipleOfPowerOf2(0), 2), 2800);
		
		$num = ByteSize::fromString('2.73 kB');
		$this->assertEqual($num->printableString(), '2.73 kB');
		$this->assertEqual(round($num->multipleOfPowerOf2(0), 2), 2796);
		
		$num = ByteSize::fromString('2.73kB');
		$this->assertEqual($num->printableString(), '2.73 kB');
		$this->assertEqual(round($num->multipleOfPowerOf2(0), 2), 2796);
		
		$num = ByteSize::fromString('2.73kb');
		$this->assertEqual($num->printableString(), '2.73 kB');
		$this->assertEqual(round($num->multipleOfPowerOf2(0), 2), 2796);
		
		$num = ByteSize::fromString('2.73 KB');
		$this->assertEqual($num->printableString(), '2.73 kB');
		$this->assertEqual(round($num->multipleOfPowerOf2(0), 2), 2796);
		
		$num = ByteSize::fromString('2.73KB');
		$this->assertEqual($num->printableString(), '2.73 kB');
		$this->assertEqual(round($num->multipleOfPowerOf2(0), 2), 2796);
		
		$num = ByteSize::fromString('2.73		kB');
		$this->assertEqual($num->printableString(), '2.73 kB');
		$this->assertEqual(round($num->multipleOfPowerOf2(0), 2), 2796);
		
		$num = ByteSize::fromString('2.68 MB');
		$this->assertEqual($num->printableString(), '2.68 MB');
		
		$num = ByteSize::fromString('8.20 GB');
		$this->assertEqual($num->printableString(), '8.20 GB');
		
		$num = ByteSize::fromString('66.17 YB');
		$this->assertEqual($num->printableString(), '66.17 YB');
		$this->assertEqual(round($this->reallyBigNum->multipleOfPowerOf2(0), 2), 80000000000000000000000000);
		
		$num = ByteSize::fromString('80000000000000000000000000 B');
		$this->assertEqual($num->printableString(), '66.17 YB');
		$this->assertEqual(round($this->reallyBigNum->multipleOfPowerOf2(0), 2), 80000000000000000000000000);
	}
}
?>