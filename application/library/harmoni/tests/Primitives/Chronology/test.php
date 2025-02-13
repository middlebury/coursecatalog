<?php

/**
 * A group test template using the SimpleTest unit testing package.
 * Just add the UnitTestCase files below using addTestFile().
 *
 * @since 5/3/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: test.php,v 1.4 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
if (!defined('HARMONI')) {
    require_once __DIR__.'/../../../../harmoni.inc.php';
}

if (!defined('SIMPLE_TEST')) {
    define('SIMPLE_TEST', HARMONI.'simple_test/');
}

require_once SIMPLE_TEST.'simple_unit.php';
require_once SIMPLE_TEST.'dobo_simple_html_test.php';

$test = new GroupTest('Chronology Tests');
$test->addTestFile(__DIR__.'/DateTestCase.class.php');
$test->addTestFile(__DIR__.'/DateAndTimeTestCase.class.php');
$test->addTestFile(__DIR__.'/DurationTestCase.class.php');
$test->addTestFile(__DIR__.'/MonthTestCase.class.php');
$test->addTestFile(__DIR__.'/ScheduleTestCase.class.php');
$test->addTestFile(__DIR__.'/TimeTestCase.class.php');
$test->addTestFile(__DIR__.'/TimeStampTestCase.class.php');
$test->addTestFile(__DIR__.'/TimespanTestCase.class.php');
$test->addTestFile(__DIR__.'/YearTestCase.class.php');
$test->addTestFile(__DIR__.'/WeekTestCase.class.php');
$test->addTestFile(__DIR__.'/SObjectTestCase.class.php');

$test->addTestFile(__DIR__.'/../StringParser/tests/test.php');

$test->attachObserver(new DoboTestHtmlDisplay());
$test->run();
