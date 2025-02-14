<?php

/**
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: DayMonthNameYearStringParserTestCase.class.php,v 1.3 2007/09/04 20:25:25 adamfranco Exp $
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
 * @version $Id: DayMonthNameYearStringParserTestCase.class.php,v 1.3 2007/09/04 20:25:25 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class DayMonthNameYearStringParserTest extends TestCase
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
        $parser = new DayMonthNameYearStringParser(
            '23 May 2005');

        $this->assertTrue($parser->canHandle());
        $this->assertEquals(2005, $parser->year());
        $this->assertEquals(5, $parser->month());
        $this->assertEquals(23, $parser->day());
        $this->assertEquals(null, $parser->hour());
        $this->assertEquals(null, $parser->minute());
        $this->assertEquals(null, $parser->second());
        $this->assertEquals(null, $parser->offsetHour());
        $this->assertEquals(null, $parser->offsetMinute());
        $this->assertEquals(null, $parser->offsetSecond());

        $parser = new DayMonthNameYearStringParser(
            "5 April '82");

        $this->assertTrue($parser->canHandle());
        $this->assertEquals(1982, $parser->year());
        $this->assertEquals(4, $parser->month());
        $this->assertEquals(5, $parser->day());
        $this->assertEquals(null, $parser->hour());
        $this->assertEquals(null, $parser->minute());
        $this->assertEquals(null, $parser->second());
        $this->assertEquals(null, $parser->offsetHour());
        $this->assertEquals(null, $parser->offsetMinute());
        $this->assertEquals(null, $parser->offsetSecond());

        $parser = new DayMonthNameYearStringParser(
            '5APR82');

        $this->assertTrue($parser->canHandle());
        $this->assertEquals(1982, $parser->year());
        $this->assertEquals(4, $parser->month());
        $this->assertEquals(5, $parser->day());
        $this->assertEquals(null, $parser->hour());
        $this->assertEquals(null, $parser->minute());
        $this->assertEquals(null, $parser->second());
        $this->assertEquals(null, $parser->offsetHour());
        $this->assertEquals(null, $parser->offsetMinute());
        $this->assertEquals(null, $parser->offsetSecond());

        $parser = new DayMonthNameYearStringParser(
            '5-APR-82');

        $this->assertTrue($parser->canHandle());
        $this->assertEquals(1982, $parser->year());
        $this->assertEquals(4, $parser->month());
        $this->assertEquals(5, $parser->day());
        $this->assertEquals(null, $parser->hour());
        $this->assertEquals(null, $parser->minute());
        $this->assertEquals(null, $parser->second());
        $this->assertEquals(null, $parser->offsetHour());
        $this->assertEquals(null, $parser->offsetMinute());
        $this->assertEquals(null, $parser->offsetSecond());

        $parser = new DayMonthNameYearStringParser(
            '5APRIL1982');

        $this->assertTrue($parser->canHandle());
        $this->assertEquals(1982, $parser->year());
        $this->assertEquals(4, $parser->month());
        $this->assertEquals(5, $parser->day());
        $this->assertEquals(null, $parser->hour());
        $this->assertEquals(null, $parser->minute());
        $this->assertEquals(null, $parser->second());
        $this->assertEquals(null, $parser->offsetHour());
        $this->assertEquals(null, $parser->offsetMinute());
        $this->assertEquals(null, $parser->offsetSecond());

        $parser = new DayMonthNameYearStringParser(
            'April 1982');

        $this->assertTrue($parser->canHandle());
        $this->assertEquals(1982, $parser->year());
        $this->assertEquals(4, $parser->month());
        $this->assertEquals(null, $parser->day());
        $this->assertEquals(null, $parser->hour());
        $this->assertEquals(null, $parser->minute());
        $this->assertEquals(null, $parser->second());
        $this->assertEquals(null, $parser->offsetHour());
        $this->assertEquals(null, $parser->offsetMinute());
        $this->assertEquals(null, $parser->offsetSecond());
    }

    public function testBadForms()
    {
        $parser = new DayMonthNameYearStringParser(
            'April');
        $this->assertFalse($parser->canHandle());

        $parser = new DayMonthNameYearStringParser(
            '5-4-2000');
        $this->assertFalse($parser->canHandle());
    }
}
