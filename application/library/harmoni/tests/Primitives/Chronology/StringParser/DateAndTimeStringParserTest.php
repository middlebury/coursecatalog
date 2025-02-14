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
 * @version $Id: DateAndTimeStringParserTestCase.class.php,v 1.3 2007/09/04 20:25:25 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class DateAndTimeStringParserTest extends TestCase
{
    /**
     *  Sets up unit test wide variables at the start
     *	 of each test method.
     */
    protected function setUp(): void
    {
    }

    /**
     *	  Clears the data set in the setUp() method call.
     */
    protected function tearDown(): void
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
        $this->assertEquals(2005, $parser->year());
        $this->assertEquals(8, $parser->month());
        $this->assertEquals(20, $parser->day());
        $this->assertEquals(15, $parser->hour());
        $this->assertEquals(25, $parser->minute());
        $this->assertEquals(10, $parser->second());
        $this->assertEquals(null, $parser->offsetHour());
        $this->assertEquals(null, $parser->offsetMinute());
        $this->assertEquals(null, $parser->offsetSecond());

        $parser = new DateAndTimeStringParser(
            '2005-08-20 3:25:10 pm');
        $this->assertTrue($parser->canHandle());
        $this->assertEquals(2005, $parser->year());
        $this->assertEquals(8, $parser->month());
        $this->assertEquals(20, $parser->day());
        $this->assertEquals(15, $parser->hour());
        $this->assertEquals(25, $parser->minute());
        $this->assertEquals(10, $parser->second());
        $this->assertEquals(null, $parser->offsetHour());
        $this->assertEquals(null, $parser->offsetMinute());
        $this->assertEquals(null, $parser->offsetSecond());

        $parser = new DateAndTimeStringParser(
            '08/20/2005 3:25:10 pm');
        $this->assertTrue($parser->canHandle());
        $this->assertEquals(2005, $parser->year());
        $this->assertEquals(8, $parser->month());
        $this->assertEquals(20, $parser->day());
        $this->assertEquals(15, $parser->hour());
        $this->assertEquals(25, $parser->minute());
        $this->assertEquals(10, $parser->second());
        $this->assertEquals(null, $parser->offsetHour());
        $this->assertEquals(null, $parser->offsetMinute());
        $this->assertEquals(null, $parser->offsetSecond());

        $parser = new DateAndTimeStringParser(
            'August 20, 2005 3:25:10 pm');
        $this->assertTrue($parser->canHandle());
        $this->assertEquals(2005, $parser->year());
        $this->assertEquals(8, $parser->month());
        $this->assertEquals(20, $parser->day());
        $this->assertEquals(15, $parser->hour());
        $this->assertEquals(25, $parser->minute());
        $this->assertEquals(10, $parser->second());
        $this->assertEquals(null, $parser->offsetHour());
        $this->assertEquals(null, $parser->offsetMinute());
        $this->assertEquals(null, $parser->offsetSecond());
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
