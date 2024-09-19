<?php
/**
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: DateAndTimeStringParserTestCase.class.php,v 1.3 2007/09/04 20:25:25 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 *
 * @since 5/3/05
 */

require_once __DIR__.'/../DateAndTimeStringParser.class.php';

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
 * @version $Id: DateAndTimeStringParserTestCase.class.php,v 1.3 2007/09/04 20:25:25 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class DateAndTimeStringParserTestCase extends UnitTestCase
{
    /**
     *  Sets up unit test wide variables at the start
     *	 of each test method.
     */
    protected function setUp()
    {
    }

    /**
     *	  Clears the data set in the setUp() method call.
     */
    protected function tearDown()
    {
        // perhaps, unset $obj here
    }

    /**
     * Test the creation methods.
     */
    public function testFullFormat()
    {
        $parser = new DateAndTimeStringParser(
            '2005-08-20 15:25:10');
        $this->assertTrue($parser->canHandle());
        $this->assertEqual($parser->year(), 2005);
        $this->assertEqual($parser->month(), 8);
        $this->assertEqual($parser->day(), 20);
        $this->assertEqual($parser->hour(), 15);
        $this->assertEqual($parser->minute(), 25);
        $this->assertEqual($parser->second(), 10);
        $this->assertEqual($parser->offsetHour(), null);
        $this->assertEqual($parser->offsetMinute(), null);
        $this->assertEqual($parser->offsetSecond(), null);

        $parser = new DateAndTimeStringParser(
            '2005-08-20 3:25:10 pm');
        $this->assertTrue($parser->canHandle());
        $this->assertEqual($parser->year(), 2005);
        $this->assertEqual($parser->month(), 8);
        $this->assertEqual($parser->day(), 20);
        $this->assertEqual($parser->hour(), 15);
        $this->assertEqual($parser->minute(), 25);
        $this->assertEqual($parser->second(), 10);
        $this->assertEqual($parser->offsetHour(), null);
        $this->assertEqual($parser->offsetMinute(), null);
        $this->assertEqual($parser->offsetSecond(), null);

        $parser = new DateAndTimeStringParser(
            '08/20/2005 3:25:10 pm');
        $this->assertTrue($parser->canHandle());
        $this->assertEqual($parser->year(), 2005);
        $this->assertEqual($parser->month(), 8);
        $this->assertEqual($parser->day(), 20);
        $this->assertEqual($parser->hour(), 15);
        $this->assertEqual($parser->minute(), 25);
        $this->assertEqual($parser->second(), 10);
        $this->assertEqual($parser->offsetHour(), null);
        $this->assertEqual($parser->offsetMinute(), null);
        $this->assertEqual($parser->offsetSecond(), null);

        $parser = new DateAndTimeStringParser(
            'August 20, 2005 3:25:10 pm');
        $this->assertTrue($parser->canHandle());
        $this->assertEqual($parser->year(), 2005);
        $this->assertEqual($parser->month(), 8);
        $this->assertEqual($parser->day(), 20);
        $this->assertEqual($parser->hour(), 15);
        $this->assertEqual($parser->minute(), 25);
        $this->assertEqual($parser->second(), 10);
        $this->assertEqual($parser->offsetHour(), null);
        $this->assertEqual($parser->offsetMinute(), null);
        $this->assertEqual($parser->offsetSecond(), null);
    }

    public function testBadForms()
    {
        $parser = new DateAndTimeStringParser(
            'April');
        $this->assertFalse($parser->canHandle());

        $parser = new DateAndTimeStringParser(
            '5-4-2000');
        $this->assertFalse($parser->canHandle());

        $parser = new DateAndTimeStringParser(
            '1234567890');
        $this->assertFalse($parser->canHandle());
    }
}
