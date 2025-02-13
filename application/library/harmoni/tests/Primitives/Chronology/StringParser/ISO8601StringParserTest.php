<?php

/**
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: ISO8601StringParserTestCase.class.php,v 1.5 2007/09/04 20:25:25 adamfranco Exp $
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
 * @version $Id: ISO8601StringParserTestCase.class.php,v 1.5 2007/09/04 20:25:25 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class ISO8601StringParserTest extends TestCase
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
        $parser = new ISO8601StringParser(
            '2005-05-23T15:25:10-04:00');
        $this->assertTrue($parser->canHandle());
        $this->assertEquals(2005, $parser->year());
        $this->assertEquals(5, $parser->month());
        $this->assertEquals(23, $parser->day());
        $this->assertEquals(15, $parser->hour());
        $this->assertEquals(25, $parser->minute());
        $this->assertEquals(10, $parser->second());
        $this->assertEquals(-4, $parser->offsetHour());
        $this->assertEquals(0, $parser->offsetMinute());
        $this->assertEquals(null, $parser->offsetSecond());

        $parser = new ISO8601StringParser(
            '2005-05-03 15:25:10-04:30');
        $this->assertTrue($parser->canHandle());
        $this->assertEquals(2005, $parser->year());
        $this->assertEquals(5, $parser->month());
        $this->assertEquals(3, $parser->day());
        $this->assertEquals(15, $parser->hour());
        $this->assertEquals(25, $parser->minute());
        $this->assertEquals(10, $parser->second());
        $this->assertEquals(-4, $parser->offsetHour());
        $this->assertEquals(-30, $parser->offsetMinute());
        $this->assertEquals(null, $parser->offsetSecond());

        $parser = new ISO8601StringParser(
            '20050523152510-0400');
        $this->assertTrue($parser->canHandle());
        $this->assertEquals(2005, $parser->year());
        $this->assertEquals(5, $parser->month());
        $this->assertEquals(23, $parser->day());
        $this->assertEquals(15, $parser->hour());
        $this->assertEquals(25, $parser->minute());
        $this->assertEquals(10, $parser->second());
        $this->assertEquals(-4, $parser->offsetHour());
        $this->assertEquals(0, $parser->offsetMinute());
        $this->assertEquals(null, $parser->offsetSecond());

        $parser = new ISO8601StringParser(
            '20050523152510');
        $this->assertTrue($parser->canHandle());
        $this->assertEquals(2005, $parser->year());
        $this->assertEquals(5, $parser->month());
        $this->assertEquals(23, $parser->day());
        $this->assertEquals(15, $parser->hour());
        $this->assertEquals(25, $parser->minute());
        $this->assertEquals(10, $parser->second());
        $this->assertEquals(null, $parser->offsetHour());
        $this->assertEquals(null, $parser->offsetMinute());
        $this->assertEquals(null, $parser->offsetSecond());

        $parser = new ISO8601StringParser(
            '2005-05-03 15:25:10Z');
        $this->assertTrue($parser->canHandle());
        $this->assertEquals(2005, $parser->year());
        $this->assertEquals(5, $parser->month());
        $this->assertEquals(3, $parser->day());
        $this->assertEquals(15, $parser->hour());
        $this->assertEquals(25, $parser->minute());
        $this->assertEquals(10, $parser->second());
        $this->assertEquals(0, $parser->offsetHour());
        $this->assertEquals(0, $parser->offsetMinute());
        $this->assertEquals(0, $parser->offsetSecond());

        $parser = new ISO8601StringParser(
            '2006-11-12 18:00:00');
        $this->assertTrue($parser->canHandle());
        $this->assertEquals(2006, $parser->year());
        $this->assertEquals(11, $parser->month());
        $this->assertEquals(12, $parser->day());
        $this->assertEquals(18, $parser->hour());
        $this->assertEquals(0, $parser->minute());
        $this->assertEquals(0, $parser->second());
        $this->assertEquals(0, $parser->offsetHour());
        $this->assertEquals(0, $parser->offsetMinute());
        $this->assertEquals(0, $parser->offsetSecond());

        $parser = new ISO8601StringParser(
            '2005-05-03');
        $this->assertTrue($parser->canHandle());
        $this->assertEquals(2005, $parser->year());
        $this->assertEquals(5, $parser->month());
        $this->assertEquals(3, $parser->day());
        $this->assertEquals(null, $parser->hour());
        $this->assertEquals(null, $parser->minute());
        $this->assertEquals(null, $parser->second());
        $this->assertEquals(null, $parser->offsetHour());
        $this->assertEquals(null, $parser->offsetMinute());
        $this->assertEquals(null, $parser->offsetSecond());

        $parser = new ISO8601StringParser(
            '20050503');
        $this->assertTrue($parser->canHandle());
        $this->assertEquals(2005, $parser->year());
        $this->assertEquals(5, $parser->month());
        $this->assertEquals(3, $parser->day());
        $this->assertEquals(null, $parser->hour());
        $this->assertEquals(null, $parser->minute());
        $this->assertEquals(null, $parser->second());
        $this->assertEquals(null, $parser->offsetHour());
        $this->assertEquals(null, $parser->offsetMinute());
        $this->assertEquals(null, $parser->offsetSecond());

        $parser = new ISO8601StringParser(
            '2005-05');
        $this->assertTrue($parser->canHandle());
        $this->assertEquals(2005, $parser->year());
        $this->assertEquals(5, $parser->month());
        $this->assertEquals(null, $parser->day());
        $this->assertEquals(null, $parser->hour());
        $this->assertEquals(null, $parser->minute());
        $this->assertEquals(null, $parser->second());
        $this->assertEquals(null, $parser->offsetHour());
        $this->assertEquals(null, $parser->offsetMinute());
        $this->assertEquals(null, $parser->offsetSecond());

        $parser = new ISO8601StringParser(
            '200505');
        $this->assertTrue($parser->canHandle());
        $this->assertEquals(2005, $parser->year());
        $this->assertEquals(5, $parser->month());
        $this->assertEquals(null, $parser->day());
        $this->assertEquals(null, $parser->hour());
        $this->assertEquals(null, $parser->minute());
        $this->assertEquals(null, $parser->second());
        $this->assertEquals(null, $parser->offsetHour());
        $this->assertEquals(null, $parser->offsetMinute());
        $this->assertEquals(null, $parser->offsetSecond());

        $parser = new ISO8601StringParser(
            '2005');
        $this->assertTrue($parser->canHandle());
        $this->assertEquals(2005, $parser->year());
        $this->assertEquals(null, $parser->month());
        $this->assertEquals(null, $parser->day());
        $this->assertEquals(null, $parser->hour());
        $this->assertEquals(null, $parser->minute());
        $this->assertEquals(null, $parser->second());
        $this->assertEquals(null, $parser->offsetHour());
        $this->assertEquals(null, $parser->offsetMinute());
        $this->assertEquals(null, $parser->offsetSecond());

        $parser = new ISO8601TimeStringParser(
            '15:25:10Z');
        $this->assertTrue($parser->canHandle());
        $this->assertEquals(null, $parser->year());
        $this->assertEquals(null, $parser->month());
        $this->assertEquals(null, $parser->day());
        $this->assertEquals(15, $parser->hour());
        $this->assertEquals(25, $parser->minute());
        $this->assertEquals(10, $parser->second());
        $this->assertEquals(0, $parser->offsetHour());
        $this->assertEquals(0, $parser->offsetMinute());
        $this->assertEquals(0, $parser->offsetSecond());

        $parser = new ISO8601TimeStringParser(
            '15:25:10');
        $this->assertTrue($parser->canHandle());
        $this->assertEquals(null, $parser->year());
        $this->assertEquals(null, $parser->month());
        $this->assertEquals(null, $parser->day());
        $this->assertEquals(15, $parser->hour());
        $this->assertEquals(25, $parser->minute());
        $this->assertEquals(10, $parser->second());
        $this->assertEquals(null, $parser->offsetHour());
        $this->assertEquals(null, $parser->offsetMinute());
        $this->assertEquals(null, $parser->offsetSecond());

        $parser = new ISO8601TimeStringParser(
            'T152510');
        $this->assertTrue($parser->canHandle());
        $this->assertEquals(null, $parser->year());
        $this->assertEquals(null, $parser->month());
        $this->assertEquals(null, $parser->day());
        $this->assertEquals(15, $parser->hour());
        $this->assertEquals(25, $parser->minute());
        $this->assertEquals(10, $parser->second());
        $this->assertEquals(null, $parser->offsetHour());
        $this->assertEquals(null, $parser->offsetMinute());
        $this->assertEquals(null, $parser->offsetSecond());

        $parser = new ISO8601TimeStringParser(
            'T152510.375');
        $this->assertTrue($parser->canHandle());
        $this->assertEquals(null, $parser->year());
        $this->assertEquals(null, $parser->month());
        $this->assertEquals(null, $parser->day());
        $this->assertEquals(15, $parser->hour());
        $this->assertEquals(25, $parser->minute());
        $this->assertEquals(10.375, $parser->second());
        $this->assertEquals(null, $parser->offsetHour());
        $this->assertEquals(null, $parser->offsetMinute());
        $this->assertEquals(null, $parser->offsetSecond());
    }

    public function testBadForms()
    {
        $parser = new ISO8601StringParser(
            'April');
        $this->assertFalse($parser->canHandle());

        $parser = new ISO8601StringParser(
            '5-4-2000');
        $this->assertFalse($parser->canHandle());

        $parser = new ISO8601StringParser(
            '1234567890');
        $this->assertFalse($parser->canHandle());
    }
}
