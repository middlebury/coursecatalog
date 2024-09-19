<?php

/**
 * A group test template using the SimpleTest unit testing package.
 * Just add the UnitTestCase files below using addTestFile().
 *
 * @since 5/23/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: test.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
if (!defined('HARMONI')) {
    define('HARMONI', __DIR__.'/../../../../');
}

if (!defined('SIMPLE_TEST')) {
    define('SIMPLE_TEST', HARMONI.'simple_test/');
}

require_once SIMPLE_TEST.'simple_unit.php';
require_once SIMPLE_TEST.'dobo_simple_html_test.php';

$test = new GroupTest('Chronology StringParser Tests');
$test->addTestFile(__DIR__.'/ISO8601StringParserTestCase.class.php');
$test->addTestFile(__DIR__.'/DayMonthNameYearStringParserTestCase.class.php');
$test->addTestFile(__DIR__.'/MonthNameDayYearStringParserTestCase.class.php');
$test->addTestFile(__DIR__.'/MonthNumberDayYearStringParserTestCase.class.php');
$test->addTestFile(__DIR__.'/KeywordStringParserTestCase.class.php');
$test->addTestFile(__DIR__.'/DateAndTimeStringParserTestCase.class.php');

$test->attachObserver(new DoboTestHtmlDisplay());
$test->run();
