<?php

/**
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: KeywordStringParserTestCase.class.php,v 1.3 2007/09/04 20:25:25 adamfranco Exp $
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
 * @version $Id: KeywordStringParserTestCase.class.php,v 1.3 2007/09/04 20:25:25 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class KeywordStringParserTest extends TestCase
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
     * Test the methods.
     */
    public function testNow()
    {
        $parser = new KeywordStringParser('now');
        $this->assertTrue($parser->canHandle());

        $dateAndTime = DateAndTime::now();
        $offset = $dateAndTime->offset();

        $this->assertEquals($dateAndTime->year(), $parser->year());
        $this->assertEquals($dateAndTime->month(), $parser->month());
        $this->assertEquals($dateAndTime->dayOfMonth(), $parser->day());
        $this->assertEquals($dateAndTime->hour(), $parser->hour());
        $this->assertEquals($dateAndTime->minute(), $parser->minute());
        $this->assertEquals($dateAndTime->second(), $parser->second());
        $this->assertEquals($offset->hours(), $parser->offsetHour());
        $this->assertEquals($offset->minutes(), $parser->offsetMinute());
        $this->assertEquals($offset->seconds(), $parser->offsetSecond());
    }

    /**
     * Test the methods.
     */
    public function testToday()
    {
        $parser = new KeywordStringParser('today');
        $this->assertTrue($parser->canHandle());

        $date = Date::today();
        $dateAndTime = $date->start();
        $offset = $dateAndTime->offset();

        $this->assertEquals($dateAndTime->year(), $parser->year());
        $this->assertEquals($dateAndTime->month(), $parser->month());
        $this->assertEquals($dateAndTime->dayOfMonth(), $parser->day());
        $this->assertEquals(0, $parser->hour());
        $this->assertEquals(0, $parser->minute());
        $this->assertEquals(0, $parser->second());
        $this->assertEquals($offset->hours(), $parser->offsetHour());
        $this->assertEquals($offset->minutes(), $parser->offsetMinute());
        $this->assertEquals($offset->seconds(), $parser->offsetSecond());
    }

    /**
     * Test the methods.
     */
    public function testTomorrow()
    {
        $parser = new KeywordStringParser('tomorrow');
        $this->assertTrue($parser->canHandle());

        $date = Date::tomorrow();
        $dateAndTime = $date->start();
        $offset = $dateAndTime->offset();

        $this->assertEquals($dateAndTime->year(), $parser->year());
        $this->assertEquals($dateAndTime->month(), $parser->month());
        $this->assertEquals($dateAndTime->dayOfMonth(), $parser->day());
        $this->assertEquals(0, $parser->hour());
        $this->assertEquals(0, $parser->minute());
        $this->assertEquals(0, $parser->second());
        $this->assertEquals($offset->hours(), $parser->offsetHour());
        $this->assertEquals($offset->minutes(), $parser->offsetMinute());
        $this->assertEquals($offset->seconds(), $parser->offsetSecond());
    }

    /**
     * Test the methods.
     */
    public function testYesterday()
    {
        $parser = new KeywordStringParser('yesterday');
        $this->assertTrue($parser->canHandle());

        $date = Date::yesterday();
        $dateAndTime = $date->start();
        $offset = $dateAndTime->offset();

        $this->assertEquals($dateAndTime->year(), $parser->year());
        $this->assertEquals($dateAndTime->month(), $parser->month());
        $this->assertEquals($dateAndTime->dayOfMonth(), $parser->day());
        $this->assertEquals(0, $parser->hour());
        $this->assertEquals(0, $parser->minute());
        $this->assertEquals(0, $parser->second());
        $this->assertEquals($offset->hours(), $parser->offsetHour());
        $this->assertEquals($offset->minutes(), $parser->offsetMinute());
        $this->assertEquals($offset->seconds(), $parser->offsetSecond());
    }
}
