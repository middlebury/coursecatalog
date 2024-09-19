<?php
/**
 * @since 5/4/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Month.class.php,v 1.5 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

require_once __DIR__.'/Timespan.class.php';

/**
 * I am a timespan that represents a month.
 *
 * To create new Month instances, <b>use one of the static instance-creation
 * methods</b>, NOT 'new Month':
 *		- {@link current Month::current()}
 *		- {@link current Month::current()}
 *		- {@link epoch Month::epoch()}
 *		- {@link fromString Month::fromString($aString)}
 *		- {@link starting Month::starting($aDateAndTime)}
 *		- {@link startingDuration Month::startingDuration($aDateAndTime, $aDuration)}
 *		- {@link startingEnding Month::startingEnding($startDateAndTime, $endDateAndTime)}
 *		- {@link withMonthYear Month::withMonthYear($anIntegerOrStringMonth, $anIntYear)}
 *
 * @since 5/4/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Month.class.php,v 1.5 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class Month extends Timespan
{
    /*********************************************************
     * Class Methods
     *********************************************************/

    /**
     * Return the index of a string Month.
     *
     * @param string $aNameString
     *
     * @return int
     *
     * @since 5/4/05
     *
     * @static
     */
    public static function indexOfMonth($aNameString)
    {
        foreach (ChronologyConstants::MonthNames() as $i => $name) {
            if (preg_match("/$aNameString.*/i", $name)) {
                return $i;
            }
        }

        $errorString = $aNameString.' is not a recognized month name.';
        if (function_exists('throwError')) {
            throwError(new Error($errorString));
        } else {
            exit($errorString);
        }
    }

    /**
     * Return the name of the month at index.
     *
     * @param int $anInteger
     *
     * @return string
     *
     * @since 5/4/05
     *
     * @static
     */
    public static function nameOfMonth($anInteger)
    {
        $names = ChronologyConstants::MonthNames();
        if ($names[$anInteger]) {
            return $names[$anInteger];
        }

        $errorString = $anInteger.' is not a valid month index.';
        if (function_exists('throwError')) {
            throwError(new Error($errorString));
        } else {
            exit($errorString);
        }
    }

    /**
     * Answer the days in this month on a given year.
     *
     * @param string   $indexOrNameString
     * @param ingteger $yearInteger
     *
     * @return int
     *
     * @since 5/5/05
     *
     * @static
     */
    public static function daysInMonthForYear($indexOrNameString, $yearInteger)
    {
        if (is_numeric($indexOrNameString)) {
            $index = $indexOrNameString;
        } else {
            $index = self::indexOfMonth($indexOrNameString);
        }

        if ($index < 1 | $index > 12) {
            $errorString = $index.' is not a valid month index.';
            if (function_exists('throwError')) {
                throwError(new Error($errorString));
            } else {
                exit($errorString);
            }
        }

        $monthDays = ChronologyConstants::DaysInMonth();
        $days = $monthDays[$index];

        if (2 == $index && Year::isYearLeapYear($yearInteger)) {
            return $days + 1;
        } else {
            return $days;
        }
    }

    /*********************************************************
     * Class Methods - Instance Creation
     *
     * All static instance creation methods have an optional
     * $class parameter which is used to get around the limitations
     * of not being	able to find the class of the object that
     * recieved the initial method call rather than the one in
     * which it is implemented. These parameters SHOULD NOT BE
     * USED OUTSIDE OF THIS PACKAGE.
     *********************************************************/

    /**
     * Answer a new object that represents now.
     *
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object Month
     *
     * @since 5/5/05
     *
     * @static
     */
    public static function current($class = 'Month')
    {
        $obj = parent::current($class);

        return $obj;
    }

    /**
     * Answer a Month starting on the Squeak epoch: 1 January 1901.
     *
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object Month
     *
     * @since 5/5/05
     *
     * @static
     */
    public static function epoch($class = 'Month')
    {
        $obj = parent::epoch($class);

        return $obj;
    }

    /**
     * Read a month from the stream in any of the forms:
     *
     *		- July 1998
     *
     * @param string $aString
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object Month
     *
     * @since 5/10/05
     *
     * @static
     */
    public static function fromString($aString, $class = 'Month')
    {
        $parser = StringParser::getParserFor($aString);

        if (!is_string($aString) || !preg_match('/[^\W]/', $aString) || !$parser) {
            $null = null;

            return $null;
            // die("'".$aString."' is not in a valid format.");
        }

        eval('$result = '.$class.'::withMonthYear($parser->month(),
					$parser->year(), $class);');

        return $result;
    }

    /**
     * Create a new object starting now, with zero duration.
     *
     * @param object DateAndTime $aDateAndTime
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object Month
     *
     * @since 5/5/05
     *
     * @static
     */
    public static function starting($aDateAndTime, $class = 'Month')
    {
        $obj = parent::starting($aDateAndTime, $class);

        return $obj;
    }

    /**
     * Create a new object with given start and end DateAndTimes.
     *
     * @param object DateAndTime $startDateAndTime
     * @param object DateAndTime $endDateAndTime
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object Month
     *
     * @since 5/11/05
     *
     * @static
     */
    public static function startingEnding($startDateAndTime, $endDateAndTime,
        $class = 'Month')
    {
        $obj = parent::startingEnding($startDateAndTime, $endDateAndTime, $class);

        return $obj;
    }

    /**
     * Create a new object starting now, with a given duration.
     * Override - as each month has a defined duration.
     *
     * @param object DateAndTime $aDateAndTime
     * @param object Duration $aDuration
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object Month
     *
     * @since 5/5/05
     *
     * @static
     */
    public static function startingDuration($aDateAndTime, $aDuration, $class = 'Month')
    {
        // Validate our passed class name.
        if (!(strtolower($class) == strtolower('Month')
            || is_subclass_of(new $class(), 'Month'))) {
            exit("Class, '$class', is not a subclass of 'Month'.");
        }

        $start = $aDateAndTime->asDateAndTime();
        $adjusted = DateAndTime::withYearMonthDay($start->year(), $start->month(), 1);
        $days = self::daysInMonthForYear($adjusted->month(), $adjusted->year());

        $month = new $class();
        $month->setStart($adjusted);
        $month->setDuration(Duration::withDays($days));

        return $month;
    }

    /**
     * Create a Month for the given <year> and <month>.
     * <month> may be a number or a String with the
     * name of the month. <year> should be with 4 digits.
     *
     * @param string $anIntegerOrStringMonth
     * @param int    $anIntegerYear          four-digit year
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object Month
     *
     * @since 5/11/05
     *
     * @static
     */
    public static function withMonthYear($anIntegerOrStringMonth, $anIntegerYear,
        $class = 'Month')
    {
        eval('$result = '.$class.'::starting(DateAndTime::withYearMonthDay(
			$anIntegerYear, $anIntegerOrStringMonth, 1), $class);');

        return $result;
    }

    /*********************************************************
     * Instance methods - Accessing
     *********************************************************/

    /**
     * Answer the number of days.
     *
     * @return int
     *
     * @since 5/5/05
     */
    public function daysInMonth()
    {
        return $this->duration->days();
    }

    /**
     * Answer the index of this object.
     *
     * @return int
     *
     * @since 5/23/05
     */
    public function index()
    {
        return $this->startMonthIndex();
    }

    /**
     * Answer the name of this object.
     *
     * @return string
     *
     * @since 5/23/05
     */
    public function name()
    {
        return $this->startMonthName();
    }

    /**
     * Answer a printable string.
     *
     * @return string
     *
     * @since 5/23/05
     */
    public function printableString($printLeadingSpaceToo = false)
    {
        return $this->name().' '.$this->startYear();
    }

    /*********************************************************
     * Instance methods - Operations
     *********************************************************/

    /**
     * Answer the previous object of our duration.
     *
     * @return object Timespan
     *
     * @since 5/10/05
     */
    public function previous()
    {
        eval('$result = '.static::class.'::startingDuration(
 			$this->start->minus(Duration::withDays(1)),
 			$this->duration,
 			"'.static::class.'");');

        return $result;
    }

    /*********************************************************
     * Instance Methods - Converting
     *********************************************************/

    /**
     * Answer the receiver as a Month.
     *
     * @return object Month
     *
     * @since 5/23/05
     */
    public function asMonth()
    {
        return $this;
    }
}
