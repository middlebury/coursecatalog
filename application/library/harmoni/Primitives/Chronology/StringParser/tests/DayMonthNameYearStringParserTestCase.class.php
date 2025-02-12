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

require_once __DIR__.'/../DayMonthNameYearStringParser.class.php';

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
class DayMonthNameYearStringParserTestCase extends UnitTestCase
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
        $parser = new DayMonthNameYearStringParser(
            '23 May 2005');

        $this->assertTrue($parser->canHandle());
        $this->assertEqual($parser->year(), 2005);
        $this->assertEqual($parser->month(), 5);
        $this->assertEqual($parser->day(), 23);
        $this->assertEqual($parser->hour(), null);
        $this->assertEqual($parser->minute(), null);
        $this->assertEqual($parser->second(), null);
        $this->assertEqual($parser->offsetHour(), null);
        $this->assertEqual($parser->offsetMinute(), null);
        $this->assertEqual($parser->offsetSecond(), null);

        $parser = new DayMonthNameYearStringParser(
            "5 April '82");

        $this->assertTrue($parser->canHandle());
        $this->assertEqual($parser->year(), 1982);
        $this->assertEqual($parser->month(), 4);
        $this->assertEqual($parser->day(), 5);
        $this->assertEqual($parser->hour(), null);
        $this->assertEqual($parser->minute(), null);
        $this->assertEqual($parser->second(), null);
        $this->assertEqual($parser->offsetHour(), null);
        $this->assertEqual($parser->offsetMinute(), null);
        $this->assertEqual($parser->offsetSecond(), null);

        $parser = new DayMonthNameYearStringParser(
            '5APR82');

        $this->assertTrue($parser->canHandle());
        $this->assertEqual($parser->year(), 1982);
        $this->assertEqual($parser->month(), 4);
        $this->assertEqual($parser->day(), 5);
        $this->assertEqual($parser->hour(), null);
        $this->assertEqual($parser->minute(), null);
        $this->assertEqual($parser->second(), null);
        $this->assertEqual($parser->offsetHour(), null);
        $this->assertEqual($parser->offsetMinute(), null);
        $this->assertEqual($parser->offsetSecond(), null);

        $parser = new DayMonthNameYearStringParser(
            '5-APR-82');

        $this->assertTrue($parser->canHandle());
        $this->assertEqual($parser->year(), 1982);
        $this->assertEqual($parser->month(), 4);
        $this->assertEqual($parser->day(), 5);
        $this->assertEqual($parser->hour(), null);
        $this->assertEqual($parser->minute(), null);
        $this->assertEqual($parser->second(), null);
        $this->assertEqual($parser->offsetHour(), null);
        $this->assertEqual($parser->offsetMinute(), null);
        $this->assertEqual($parser->offsetSecond(), null);

        $parser = new DayMonthNameYearStringParser(
            '5APRIL1982');

        $this->assertTrue($parser->canHandle());
        $this->assertEqual($parser->year(), 1982);
        $this->assertEqual($parser->month(), 4);
        $this->assertEqual($parser->day(), 5);
        $this->assertEqual($parser->hour(), null);
        $this->assertEqual($parser->minute(), null);
        $this->assertEqual($parser->second(), null);
        $this->assertEqual($parser->offsetHour(), null);
        $this->assertEqual($parser->offsetMinute(), null);
        $this->assertEqual($parser->offsetSecond(), null);

        $parser = new DayMonthNameYearStringParser(
            'April 1982');

        $this->assertTrue($parser->canHandle());
        $this->assertEqual($parser->year(), 1982);
        $this->assertEqual($parser->month(), 4);
        $this->assertEqual($parser->day(), null);
        $this->assertEqual($parser->hour(), null);
        $this->assertEqual($parser->minute(), null);
        $this->assertEqual($parser->second(), null);
        $this->assertEqual($parser->offsetHour(), null);
        $this->assertEqual($parser->offsetMinute(), null);
        $this->assertEqual($parser->offsetSecond(), null);
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
