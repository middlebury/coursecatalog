<?php
/** 
 * @package harmoni.primitives.chronology.tests
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: SObjectTestCase.class.php,v 1.4 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 * @since 5/3/05
 */

require_once(HARMONI."/Primitives/Objects/SObject.class.php");

/**
 * A single unit test case. This class is intended to test one particular
 * class. Replace 'testedclass.php' below with the class you would like to
 * test.
 *
 * @since 5/3/05
 *
 * @package harmoni.primitives.chronology.tests
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: SObjectTestCase.class.php,v 1.4 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

class SObjectTestCase extends UnitTestCase {
	
	/**
	*  Sets up unit test wide variables at the start
	*	 of each test method.
	*	 @access public
	*/
	function setUp() {
		// TestPeople
		$this->personA = new TestPerson;
		$this->personA->name = 'Albert';
		
		$this->personB = new TestPerson;
		$this->personB->name = 'Bob';
		
		$this->personC = new TestPerson;
		$this->personC->name = 'Charlie';
		
		$this->personD = new TestPerson;
		$this->personD->name = 'Danny';
		
		$this->personA->child =$this->personB;
		$this->personB->child =$this->personC;
		$this->personC->child =$this->personD;
		
		
		// TestPeople Arrays
		$this->personM = new TestPerson;
		$this->personM->name = 'Monty';
		$this->personM->child = array();
		
		$this->personN = new TestPerson;
		$this->personN->name = 'Nick';
		$this->personN->child = array();
		
		$this->personO = new TestPerson;
		$this->personO->name = 'Olivia';
		$this->personO->child = array();
		
		$this->personP = new TestPerson;
		$this->personP->name = 'Patsy';
		$this->personP->child = array();
		
		$this->personM->child[0] =$this->personN;
		$this->personM->child[1] = 'Linda';
		$this->personN->child[0] =$this->personO;
		$this->personO->child[0] =$this->personP;
	}
	
	/**
	 *	  Clears the data set in the setUp() method call.
	 *	  @access public
	 */
	function tearDown() {
		// perhaps, unset $obj here
	}
	

/*********************************************************
 * Test shallow and deep copying
 *********************************************************/
	function test_shallow_copy() {
		$personE =$this->personA->shallowCopy();
		$personE->name = 'Edward';
		$personE->child->name = 'Frank';
		$personE->child->child->name = 'Gertrude';
		$personE->child->child->child->name = 'Horace';
		
		$this->assertEqual($this->personA->name, 'Albert');
		$this->assertEqual($this->personB->name, 'Frank');
		$this->assertEqual($this->personC->name, 'Gertrude');
		$this->assertEqual($this->personD->name, 'Horace');
	}
	
	function test_deep_copy() {
		$personE =$this->personA->deepCopy();
		$personE->name = 'Edward';
		$personE->child->name = 'Frank';
		$personE->child->child->name = 'Gertrude';
		$personE->child->child->child->name = 'Horace';
		
		$this->assertEqual($this->personA->name, 'Albert');
		$this->assertEqual($this->personB->name, 'Bob');
		$this->assertEqual($this->personC->name, 'Charlie');
		$this->assertEqual($this->personD->name, 'Danny');
		
		$this->assertNotReference($this->personA, $personE);
		$this->assertNotReference($this->personB, $personE->child);
		$this->assertNotReference($this->personC, $personE->child->child);
		$this->assertNotReference($this->personD, $personE->child->child->child);
	}
	
	function test_copy_two_level() {
		$personE =$this->personA->copyTwoLevel();
		$personE->name = 'Edward';
		$personE->child->name = 'Frank';
		$personE->child->child->name = 'Gertrude';
		$personE->child->child->child->name = 'Horace';
		
		$this->assertEqual($this->personA->name, 'Albert');
		$this->assertEqual($this->personB->name, 'Bob');
		$this->assertEqual($this->personC->name, 'Gertrude');
		$this->assertEqual($this->personD->name, 'Horace');
		
		$this->assertNotReference($this->personA, $personE);
		$this->assertNotReference($this->personB, $personE->child);
		$this->assertReference($this->personC, $personE->child->child);
		$this->assertReference($this->personD, $personE->child->child->child);
	}
	
	
/*********************************************************
 * Test with arrays
 *********************************************************/
	function test_array_shallow_copy() {
		$personQ =$this->personM->shallowCopy();
		$personQ->name = 'Quincy';
		$personQ->child[0]->name = 'Roberto';
		$personQ->child[1] = 'Kelly';
		$personQ->child[0]->child[0]->name = 'Sidney';
		$personQ->child[0]->child[0]->child[0]->name = 'Tim';
		
		$this->assertEqual($this->personM->name, 'Monty');
		$this->assertEqual($this->personM->child[1], 'Linda');
		$this->assertEqual($this->personN->name, 'Roberto');
		$this->assertEqual($this->personO->name, 'Sidney');
		$this->assertEqual($this->personP->name, 'Tim');
	}
	
	function test_array_deep_copy() {
		$personQ =$this->personM->deepCopy();
		$personQ->name = 'Quincy';
		$personQ->child[1] = 'Kelly';
		$personQ->child[0]->name = 'Roberto';
		$personQ->child[0]->child[0]->name = 'Sidney';
		$personQ->child[0]->child[0]->child[0]->name = 'Tim';
		
		$this->assertEqual($this->personM->name, 'Monty');
		$this->assertEqual($this->personM->child[1], 'Linda');
		$this->assertEqual($this->personN->name, 'Nick');
		$this->assertEqual($this->personO->name, 'Olivia');
		$this->assertEqual($this->personP->name, 'Patsy');
	}
	
	function test_array_copy_two_level() {
		$personQ =$this->personM->copyTwoLevel();
		$personQ->name = 'Quincy';
		$personQ->child[1] = 'Kelly';
		$personQ->child[0]->name = 'Roberto';
		$personQ->child[0]->child[0]->name = 'Sidney';
		$personQ->child[0]->child[0]->child[0]->name = 'Tim';
		
		$this->assertEqual($this->personM->name, 'Monty');
		$this->assertEqual($this->personM->child[1], 'Linda');
		$this->assertEqual($this->personN->name, 'Roberto');
		$this->assertEqual($this->personO->name, 'Sidney');
		$this->assertEqual($this->personP->name, 'Tim');
	}
	
/*********************************************************
 * Test the copy() method, should be the same as shallowCopy
 *********************************************************/
 	function test_copy() {
		$personE =$this->personA->copy();
		$personE->name = 'Edward';
		$personE->child->name = 'Frank';
		$personE->child->child->name = 'Gertrude';
		$personE->child->child->child->name = 'Horace';
		
		$this->assertEqual($this->personA->name, 'Albert');
		$this->assertEqual($this->personB->name, 'Frank');
		$this->assertEqual($this->personC->name, 'Gertrude');
		$this->assertEqual($this->personD->name, 'Horace');
		
		$this->assertNotReference($this->personA, $personE);
		$this->assertReference($this->personB, $personE->child);
		$this->assertReference($this->personC, $personE->child->child);
		$this->assertReference($this->personD, $personE->child->child->child);
	}
	
	function test_array_copy() {
		$personQ =$this->personM->copy();
		$personQ->name = 'Quincy';
		$personQ->child[0]->name = 'Roberto';
		$personQ->child[1] = 'Kelly';
		$personQ->child[0]->child[0]->name = 'Sidney';
		$personQ->child[0]->child[0]->child[0]->name = 'Tim';
		
		$this->assertEqual($this->personM->name, 'Monty');
		$this->assertEqual($this->personM->child[1], 'Linda');
		$this->assertEqual($this->personN->name, 'Roberto');
		$this->assertEqual($this->personO->name, 'Sidney');
		$this->assertEqual($this->personP->name, 'Tim');
		
		$this->assertNotReference($this->personM, $personQ);
		$this->assertNotReference($this->personM->child, $personQ->child);
		$this->assertNotReference($this->personM->child[1], $personQ->child[1]);
		$this->assertReference($this->personN, $personQ->child[0]);
		$this->assertReference($this->personO, $personQ->child[0]->child[0]);
		$this->assertReference($this->personP, $personQ->child[0]->child[0]->child[0]);
	}
	
/*********************************************************
 * Test the coverting methods
 *********************************************************/

	function test_printable_string() {
		$this->assertEqual($this->personM->printableString(), 'a Testperson');
		$octopus = new OctopusObject;
		$this->assertEqual($octopus->printableString(), 'an Octopusobject(8 legs)');
		
		$this->assertEqual($this->personM->asString(), 'a Testperson');
		$octopus = new OctopusObject;
		$this->assertEqual($octopus->asString(), 'an Octopusobject(8 legs)');
	}
	
	function test_as_a() {
		$octopus = new OctopusObject;
		$dog =$octopus->asA('DogObject');
		
		$this->assertEqual($dog->numberOfLegs, 8);
		$this->assertEqual($dog->numberOfTails, 1);
		
		$props = get_object_vars($dog);
		$this->assertFalse(in_array('numberOfSuckers', $props));
		
		// Same class
		$octopus->numberOfLegs = 20;
		$octopus->numberOfSuckers = 100;
		
		$octopus2 =$octopus->asA('OctopusObject');
		
		$this->assertNotReference($octopus, $octopus2);
		$this->assertEqual($octopus2->numberOfLegs, 20);
		$this->assertEqual($octopus2->numberOfSuckers, 100);
		
		$props = get_object_vars($octopus2);
		$this->assertEqual(count($props), 2);
		
	}
	
	function test_new_from() {
		$octopus = new OctopusObject;
		$dog = SObject::newFrom('DogObject', $octopus);
		
		$this->assertEqual($dog->numberOfLegs, 8);
		$this->assertEqual($dog->numberOfTails, 1);
		
		$props = get_object_vars($dog);
		$this->assertFalse(in_array('numberOfSuckers', $props));
		
		
		// Same class
		$octopus->numberOfLegs = 20;
		$octopus->numberOfSuckers = 100;
		
		$octopus2 = SObject::newFrom('OctopusObject', $octopus);
		
		$this->assertEqual($octopus2->numberOfLegs, 20);
		$this->assertEqual($octopus2->numberOfSuckers, 100);
		
		$props = get_object_vars($octopus2);
		$this->assertEqual(count($props), 2);	
	}
	
/*********************************************************
 * Comparing Methods
 *********************************************************/
 	function test_isequalto() {
 		$octopus = new OctopusObject;
 		$octopus2 =$octopus;
 		$dog = new DogObject;
 		
 		$this->assertTrue($octopus->isEqualTo($octopus2));
 		$this->assertFalse($octopus->isEqualTo($dog));
 		
 		$this->assertFalse($octopus->isNotEqualTo($octopus2));
 		$this->assertTrue($octopus->isNotEqualTo($dog));
 		
 		$octopus3 =$octopus->deepCopy();
 		$this->assertTrue($octopus->isEqualTo($octopus3));
 		$this->assertFalse($octopus->isNotEqualTo($octopus3));
 		
 		$octopus3->numberOfLegs = 5;
 		$this->assertFalse($octopus->isEqualTo($octopus3));
 		$this->assertTrue($octopus->isNotEqualTo($octopus3));
 	}
 	
 	function test_isreferenceto() {
 		$octopus = new OctopusObject;
 		$octopus2 =$octopus;
 		$dog = new DogObject;
 		
 		$this->assertTrue($octopus->isReferenceTo($octopus2));
 		$this->assertFalse($octopus->isReferenceTo($dog));
 		
 		$this->assertFalse($octopus->isNotReferenceTo($octopus2));
 		$this->assertTrue($octopus->isNotReferenceTo($dog));
 		
 		$octopus3 =$octopus->deepCopy();
 		$this->assertEqual($octopus->numberOfLegs, 8);
 		$this->assertEqual($octopus3->numberOfLegs, 8);
 		$this->assertNotReference($octopus, $octopus3);
 		$this->assertFalse($octopus->isReferenceTo($octopus3));
 		$this->assertTrue($octopus->isNotReferenceTo($octopus3));
 		
 		$octopus3->numberOfLegs = 5;
 		$this->assertEqual($octopus->numberOfLegs, 8);
 		$this->assertEqual($octopus3->numberOfLegs, 5);
 		$this->assertFalse($octopus->isReferenceTo($octopus3));
 		$this->assertTrue($octopus->isNotReferenceTo($octopus3));
 	}
}


/**
 * A testing class
 * 
 * @since 7/12/05
 * @package harmoni.primitives.chronology.tests
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: SObjectTestCase.class.php,v 1.4 2007/09/04 20:25:26 adamfranco Exp $
 */
class TestPerson 
	extends SObject 
{
		
	/**
	 * @var string $name;  
	 * @access private
	 * @since 7/12/05
	 */
	var $name;
	
	/**
	 * @var object $child;  
	 * @access private
	 * @since 7/12/05
	 */
	var $child;
	
}

/**
 * A testing class
 * 
 * @since 7/12/05
 * @package harmoni.primitives.chronology.tests
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: SObjectTestCase.class.php,v 1.4 2007/09/04 20:25:26 adamfranco Exp $
 */
class OctopusObject 
	extends SObject 
{
	
	/**
	 * @var string $numberOfLegs;  
	 * @access private
	 * @since 7/12/05
	 */
	var $numberOfLegs;
	
	/**
	 * @var string $numberOfSuckers;  
	 * @access private
	 * @since 7/12/05
	 */
	var $numberOfSuckers;
	
	/**
	 * A constructor
	 * 
	 * @return object
	 * @access public
	 * @since 7/12/05
	 */
	function OctopusObject () {
		$this->numberOfLegs = 8;
		$this->numberOfSuckers = 12345;
	}
	
	/**
	 * Overriding printablestring
	 * 
	 * @return string
	 * @access public
	 * @since 7/12/05
	 */
	function printableString () {
		$string = parent::printableString();
		return $string.'('.$this->numberOfLegs.' legs)';
	}
}

/**
 * A testing class
 * 
 * @since 7/12/05
 * @package harmoni.primitives.chronology.tests
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: SObjectTestCase.class.php,v 1.4 2007/09/04 20:25:26 adamfranco Exp $
 */
class DogObject 
	extends SObject 
{
	
	/**
	 * @var string $numberOfLegs;  
	 * @access private
	 * @since 7/12/05
	 */
	var $numberOfLegs;
	
	/**
	 * @var string $numberOfTails;  
	 * @access private
	 * @since 7/12/05
	 */
	var $numberOfTails;
	
	/**
	 * A constructor
	 * 
	 * @return object
	 * @access public
	 * @since 7/12/05
	 */
	function DogObject () {
		$this->numberOfLegs = 8;
		$this->numberOfTails = 1;
	}
}

?>