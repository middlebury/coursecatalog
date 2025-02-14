<?php

/**
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: SObjectTestCase.class.php,v 1.4 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 *
 * @since 5/3/05
 */

use PHPUnit\Framework\TestCase;

/**
 * A single unit test case. This class is intended to test one particular
 * class. Replace 'testedclass.php' below with the class you would like to
 * test.
 *
 * @since 5/3/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: SObjectTestCase.class.php,v 1.4 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class SObjectTest extends TestCase
{
    private SObject $personA;
    private SObject $personB;
    private SObject $personC;
    private SObject $personD;
    private SObject $personM;
    private SObject $personN;
    private SObject $personO;
    private SObject $personP;

    /**
     *  Sets up unit test wide variables at the start
     *	 of each test method.
     */
    protected function setUp(): void
    {
        // TestPeople
        $this->personA = new TestPerson();
        $this->personA->name = 'Albert';

        $this->personB = new TestPerson();
        $this->personB->name = 'Bob';

        $this->personC = new TestPerson();
        $this->personC->name = 'Charlie';

        $this->personD = new TestPerson();
        $this->personD->name = 'Danny';

        $this->personA->child = $this->personB;
        $this->personB->child = $this->personC;
        $this->personC->child = $this->personD;

        // TestPeople Arrays
        $this->personM = new TestPerson();
        $this->personM->name = 'Monty';
        $this->personM->child = [];

        $this->personN = new TestPerson();
        $this->personN->name = 'Nick';
        $this->personN->child = [];

        $this->personO = new TestPerson();
        $this->personO->name = 'Olivia';
        $this->personO->child = [];

        $this->personP = new TestPerson();
        $this->personP->name = 'Patsy';
        $this->personP->child = [];

        $this->personM->child[0] = $this->personN;
        $this->personM->child[1] = 'Linda';
        $this->personN->child[0] = $this->personO;
        $this->personO->child[0] = $this->personP;
    }

    /**
     *	  Clears the data set in the setUp() method call.
     */
    protected function tearDown(): void
    {
        // perhaps, unset $obj here
    }

    /*********************************************************
     * Test shallow and deep copying
     *********************************************************/
    public function testShallowCopy()
    {
        $personE = $this->personA->shallowCopy();
        $personE->name = 'Edward';
        $personE->child->name = 'Frank';
        $personE->child->child->name = 'Gertrude';
        $personE->child->child->child->name = 'Horace';

        $this->assertEquals('Albert', $this->personA->name);
        $this->assertEquals('Frank', $this->personB->name);
        $this->assertEquals('Gertrude', $this->personC->name);
        $this->assertEquals('Horace', $this->personD->name);
    }

    public function testDeepCopy()
    {
        $personE = $this->personA->deepCopy();
        $personE->name = 'Edward';
        $personE->child->name = 'Frank';
        $personE->child->child->name = 'Gertrude';
        $personE->child->child->child->name = 'Horace';

        $this->assertEquals('Albert', $this->personA->name);
        $this->assertEquals('Bob', $this->personB->name);
        $this->assertEquals('Charlie', $this->personC->name);
        $this->assertEquals('Danny', $this->personD->name);

        $this->assertNotSame($this->personA, $personE);
        $this->assertNotSame($this->personB, $personE->child);
        $this->assertNotSame($this->personC, $personE->child->child);
        $this->assertNotSame($this->personD, $personE->child->child->child);
    }

    public function testCopyTwoLevel()
    {
        $personE = $this->personA->copyTwoLevel();
        $personE->name = 'Edward';
        $personE->child->name = 'Frank';
        $personE->child->child->name = 'Gertrude';
        $personE->child->child->child->name = 'Horace';

        $this->assertEquals('Albert', $this->personA->name);
        $this->assertEquals('Bob', $this->personB->name);
        $this->assertEquals('Gertrude', $this->personC->name);
        $this->assertEquals('Horace', $this->personD->name);

        $this->assertNotSame($this->personA, $personE);
        $this->assertNotSame($this->personB, $personE->child);
        $this->assertSame($this->personC, $personE->child->child);
        $this->assertSame($this->personD, $personE->child->child->child);
    }

    /*********************************************************
     * Test with arrays
     *********************************************************/
    public function testArrayShallowCopy()
    {
        $personQ = $this->personM->shallowCopy();
        $personQ->name = 'Quincy';
        $personQ->child[0]->name = 'Roberto';
        $personQ->child[1] = 'Kelly';
        $personQ->child[0]->child[0]->name = 'Sidney';
        $personQ->child[0]->child[0]->child[0]->name = 'Tim';

        $this->assertEquals('Monty', $this->personM->name);
        $this->assertEquals('Linda', $this->personM->child[1]);
        $this->assertEquals('Roberto', $this->personN->name);
        $this->assertEquals('Sidney', $this->personO->name);
        $this->assertEquals('Tim', $this->personP->name);
    }

    public function testArrayDeepCopy()
    {
        $personQ = $this->personM->deepCopy();
        $personQ->name = 'Quincy';
        $personQ->child[1] = 'Kelly';
        $personQ->child[0]->name = 'Roberto';
        $personQ->child[0]->child[0]->name = 'Sidney';
        $personQ->child[0]->child[0]->child[0]->name = 'Tim';

        $this->assertEquals('Monty', $this->personM->name);
        $this->assertEquals('Linda', $this->personM->child[1]);
        $this->assertEquals('Nick', $this->personN->name);
        $this->assertEquals('Olivia', $this->personO->name);
        $this->assertEquals('Patsy', $this->personP->name);
    }

    public function testArrayCopyTwoLevel()
    {
        $personQ = $this->personM->copyTwoLevel();
        $personQ->name = 'Quincy';
        $personQ->child[1] = 'Kelly';
        $personQ->child[0]->name = 'Roberto';
        $personQ->child[0]->child[0]->name = 'Sidney';
        $personQ->child[0]->child[0]->child[0]->name = 'Tim';

        $this->assertEquals('Monty', $this->personM->name);
        $this->assertEquals('Linda', $this->personM->child[1]);
        $this->assertEquals('Roberto', $this->personN->name);
        $this->assertEquals('Sidney', $this->personO->name);
        $this->assertEquals('Tim', $this->personP->name);
    }

    /*********************************************************
     * Test the copy() method, should be the same as shallowCopy
     *********************************************************/
    public function testCopy()
    {
        $personE = $this->personA->copy();
        $personE->name = 'Edward';
        $personE->child->name = 'Frank';
        $personE->child->child->name = 'Gertrude';
        $personE->child->child->child->name = 'Horace';

        $this->assertEquals('Albert', $this->personA->name);
        $this->assertEquals('Frank', $this->personB->name);
        $this->assertEquals('Gertrude', $this->personC->name);
        $this->assertEquals('Horace', $this->personD->name);

        $this->assertNotSame($this->personA, $personE);
        $this->assertSame($this->personB, $personE->child);
        $this->assertSame($this->personC, $personE->child->child);
        $this->assertSame($this->personD, $personE->child->child->child);
    }

    public function testArrayCopy()
    {
        $personQ = $this->personM->copy();
        $personQ->name = 'Quincy';
        $personQ->child[0]->name = 'Roberto';
        $personQ->child[1] = 'Kelly';
        $personQ->child[0]->child[0]->name = 'Sidney';
        $personQ->child[0]->child[0]->child[0]->name = 'Tim';

        $this->assertEquals('Monty', $this->personM->name);
        $this->assertEquals('Linda', $this->personM->child[1]);
        $this->assertEquals('Roberto', $this->personN->name);
        $this->assertEquals('Sidney', $this->personO->name);
        $this->assertEquals('Tim', $this->personP->name);

        $this->assertNotSame($this->personM, $personQ);
        $this->assertNotSame($this->personM->child, $personQ->child);
        $this->assertNotSame($this->personM->child[1], $personQ->child[1]);
        $this->assertSame($this->personN, $personQ->child[0]);
        $this->assertSame($this->personO, $personQ->child[0]->child[0]);
        $this->assertSame($this->personP, $personQ->child[0]->child[0]->child[0]);
    }

    /*********************************************************
     * Test the coverting methods
     *********************************************************/

    public function testPrintableString()
    {
        $this->assertEquals('a TestPerson', $this->personM->printableString());
        $octopus = new OctopusObject();
        $this->assertEquals('an OctopusObject(8 legs)', $octopus->printableString());

        $this->assertEquals('a TestPerson', $this->personM->asString());
        $octopus = new OctopusObject();
        $this->assertEquals('an OctopusObject(8 legs)', $octopus->asString());
    }

    public function testAsA()
    {
        $octopus = new OctopusObject();
        $dog = $octopus->asA('DogObject');

        $this->assertEquals(8, $dog->numberOfLegs);
        $this->assertEquals(1, $dog->numberOfTails);

        $props = get_object_vars($dog);
        $this->assertFalse(in_array('numberOfSuckers', $props));

        // Same class
        $octopus->numberOfLegs = 20;
        $octopus->numberOfSuckers = 100;

        $octopus2 = $octopus->asA('OctopusObject');

        $this->assertNotSame($octopus, $octopus2);
        $this->assertEquals(20, $octopus2->numberOfLegs);
        $this->assertEquals(100, $octopus2->numberOfSuckers);

        $props = get_object_vars($octopus2);
        $this->assertCount(2, $props);
    }

    public function testNewFrom()
    {
        $octopus = new OctopusObject();
        $dog = SObject::newFrom('DogObject', $octopus);

        $this->assertEquals(8, $dog->numberOfLegs);
        $this->assertEquals(1, $dog->numberOfTails);

        $props = get_object_vars($dog);
        $this->assertFalse(in_array('numberOfSuckers', $props));

        // Same class
        $octopus->numberOfLegs = 20;
        $octopus->numberOfSuckers = 100;

        $octopus2 = SObject::newFrom('OctopusObject', $octopus);

        $this->assertEquals(20, $octopus2->numberOfLegs);
        $this->assertEquals(100, $octopus2->numberOfSuckers);

        $props = get_object_vars($octopus2);
        $this->assertCount(2, $props);
    }

    /*********************************************************
     * Comparing Methods
     *********************************************************/
    public function testIsequalto()
    {
        $octopus = new OctopusObject();
        $octopus2 = $octopus;
        $dog = new DogObject();

        $this->assertTrue($octopus->isEqualTo($octopus2));
        $this->assertFalse($octopus->isEqualTo($dog));

        $this->assertFalse($octopus->isNotEqualTo($octopus2));
        $this->assertTrue($octopus->isNotEqualTo($dog));

        $octopus3 = $octopus->deepCopy();
        $this->assertTrue($octopus->isEqualTo($octopus3));
        $this->assertFalse($octopus->isNotEqualTo($octopus3));

        $octopus3->numberOfLegs = 5;
        $this->assertFalse($octopus->isEqualTo($octopus3));
        $this->assertTrue($octopus->isNotEqualTo($octopus3));
    }

    public function testIsreferenceto()
    {
        $octopus = new OctopusObject();
        $octopus2 = $octopus;
        $dog = new DogObject();

        $this->assertTrue($octopus->isReferenceTo($octopus2));
        $this->assertFalse($octopus->isReferenceTo($dog));

        $this->assertFalse($octopus->isNotReferenceTo($octopus2));
        $this->assertTrue($octopus->isNotReferenceTo($dog));

        $octopus3 = $octopus->deepCopy();
        $this->assertEquals(8, $octopus->numberOfLegs);
        $this->assertEquals(8, $octopus3->numberOfLegs);
        $this->assertNotSame($octopus, $octopus3);
        $this->assertFalse($octopus->isReferenceTo($octopus3));
        $this->assertTrue($octopus->isNotReferenceTo($octopus3));

        $octopus3->numberOfLegs = 5;
        $this->assertEquals(8, $octopus->numberOfLegs);
        $this->assertEquals(5, $octopus3->numberOfLegs);
        $this->assertFalse($octopus->isReferenceTo($octopus3));
        $this->assertTrue($octopus->isNotReferenceTo($octopus3));
    }
}

/**
 * A testing class.
 *
 * @since 7/12/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: SObjectTestCase.class.php,v 1.4 2007/09/04 20:25:26 adamfranco Exp $
 */
class TestPerson extends SObject
{
    /**
     * @var string;
     *
     * @since 7/12/05
     */
    public $name;

    /**
     * @var object;
     *
     * @since 7/12/05
     */
    public $child;
}

/**
 * A testing class.
 *
 * @since 7/12/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: SObjectTestCase.class.php,v 1.4 2007/09/04 20:25:26 adamfranco Exp $
 */
class OctopusObject extends SObject
{
    /**
     * @var string;
     *
     * @since 7/12/05
     */
    public $numberOfLegs;

    /**
     * @var string;
     *
     * @since 7/12/05
     */
    public $numberOfSuckers;

    /**
     * A constructor.
     *
     * @return object
     *
     * @since 7/12/05
     */
    public function __construct()
    {
        $this->numberOfLegs = 8;
        $this->numberOfSuckers = 12345;
    }

    /**
     * Overriding printablestring.
     *
     * @return string
     *
     * @since 7/12/05
     */
    public function printableString()
    {
        $string = parent::printableString();

        return $string.'('.$this->numberOfLegs.' legs)';
    }
}

/**
 * A testing class.
 *
 * @since 7/12/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: SObjectTestCase.class.php,v 1.4 2007/09/04 20:25:26 adamfranco Exp $
 */
class DogObject extends SObject
{
    /**
     * @var string;
     *
     * @since 7/12/05
     */
    public $numberOfLegs;

    /**
     * @var string;
     *
     * @since 7/12/05
     */
    public $numberOfTails;

    /**
     * A constructor.
     *
     * @return object
     *
     * @since 7/12/05
     */
    public function __construct()
    {
        $this->numberOfLegs = 8;
        $this->numberOfTails = 1;
    }
}
