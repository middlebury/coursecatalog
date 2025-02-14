<?php

/**
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: MonthNameDayYearStringParserTestCase.class.php,v 1.4 2007/09/04 20:25:26 adamfranco Exp $
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
 * @version $Id: MonthNameDayYearStringParserTestCase.class.php,v 1.4 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class MonthNameDayYearStringParserTest extends TestCase
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
        $parser = new MonthNameDayYearStringParser(
            'May 23 2005');

        $this->assertEquals(2005, $parser->year());
        $this->assertEquals(5, $parser->month());
        $this->assertEquals(23, $parser->day());
        $this->assertEquals(null, $parser->hour());
        $this->assertEquals(null, $parser->minute());
        $this->assertEquals(null, $parser->second());
        $this->assertEquals(null, $parser->offsetHour());
        $this->assertEquals(null, $parser->offsetMinute());
        $this->assertEquals(null, $parser->offsetSecond());

        $parser = new MonthNameDayYearStringParser(
            "April 5'82");

        $this->assertEquals(1982, $parser->year());
        $this->assertEquals(4, $parser->month());
        $this->assertEquals(5, $parser->day());
        $this->assertEquals(null, $parser->hour());
        $this->assertEquals(null, $parser->minute());
        $this->assertEquals(null, $parser->second());
        $this->assertEquals(null, $parser->offsetHour());
        $this->assertEquals(null, $parser->offsetMinute());
        $this->assertEquals(null, $parser->offsetSecond());

        $parser = new MonthNameDayYearStringParser(
            'APR-5-82');

        $this->assertEquals(1982, $parser->year());
        $this->assertEquals(4, $parser->month());
        $this->assertEquals(5, $parser->day());
        $this->assertEquals(null, $parser->hour());
        $this->assertEquals(null, $parser->minute());
        $this->assertEquals(null, $parser->second());
        $this->assertEquals(null, $parser->offsetHour());
        $this->assertEquals(null, $parser->offsetMinute());
        $this->assertEquals(null, $parser->offsetSecond());

        $parser = new MonthNameDayYearStringParser(
            'April 5, 82');

        $this->assertEquals(1982, $parser->year());
        $this->assertEquals(4, $parser->month());
        $this->assertEquals(5, $parser->day());
        $this->assertEquals(null, $parser->hour());
        $this->assertEquals(null, $parser->minute());
        $this->assertEquals(null, $parser->second());
        $this->assertEquals(null, $parser->offsetHour());
        $this->assertEquals(null, $parser->offsetMinute());
        $this->assertEquals(null, $parser->offsetSecond());

        $parser = new MonthNameDayYearStringParser(
            'August 10th, 2006');

        $this->assertEquals(2006, $parser->year());
        $this->assertEquals(8, $parser->month());
        $this->assertEquals(10, $parser->day());
        $this->assertEquals(null, $parser->hour());
        $this->assertEquals(null, $parser->minute());
        $this->assertEquals(null, $parser->second());
        $this->assertEquals(null, $parser->offsetHour());
        $this->assertEquals(null, $parser->offsetMinute());
        $this->assertEquals(null, $parser->offsetSecond());
    }
}
