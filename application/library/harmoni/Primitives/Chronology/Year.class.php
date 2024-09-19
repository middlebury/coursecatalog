<?php
/**
 * @since 5/4/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Year.class.php,v 1.5 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

require_once dirname(__FILE__).'/Timespan.class.php';

/**
 * I am a Timespan that represents a Year.
 *
 * To create new Year instances, <b>use one of the static instance-creation
 * methods</b>, NOT 'new Year':
 *		- {@link current Year::current()}
 *		- {@link current Year::current()}
 *		- {@link epoch Year::epoch()}
 *		- {@link starting Year::starting($aDateAndTime)}
 *		- {@link startingDuration Year::startingDuration($aDateAndTime, $aDuration)}
 *		- {@link startingEnding Year::startingEnding($startDateAndTime, $endDateAndTime)}
 *		- {@link withYear Year::withYear($anInteger)}
 *
 * @since 5/4/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Year.class.php,v 1.5 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class Year extends Timespan
{
    /*********************************************************
     * Class Methods
     *********************************************************/

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
     * @return object Year
     *
     * @since 5/5/05
     *
     * @static
     */
    public static function current($class = 'Year')
    {
        $obj = parent::current($class);

        return $obj;
    }

    /**
     * Answer a Year starting on the Squeak epoch: 1 January 1901.
     *
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object Year
     *
     * @since 5/5/05
     *
     * @static
     */
    public static function epoch($class = 'Year')
    {
        $obj = parent::epoch($class);

        return $obj;
    }

    /**
     * Create a new object starting now.
     *
     * @param object DateAndTime $aDateAndTime
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object Year
     *
     * @since 5/5/05
     *
     * @static
     */
    public static function starting($aDateAndTime, $class = 'Year')
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
     * @return object Year
     *
     * @since 5/11/05
     *
     * @static
     */
    public static function startingEnding($startDateAndTime, $endDateAndTime,
        $class = 'Year')
    {
        $obj = parent::startingEnding($startDateAndTime, $endDateAndTime, $class);

        return $obj;
    }

    /**
     * Create a new object starting from midnight.
     *
     * @param object DateAndTime $aDateAndTime
     * @param object Duration $aDuration
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object Year
     *
     * @since 5/5/05
     *
     * @static
     */
    public static function startingDuration($aDateAndTime, $aDuration, $class = 'Year')
    {
        // Validate our passed class name.
        if (!(strtolower($class) == strtolower('Year')
            || is_subclass_of(new $class(), 'Year'))) {
            exit("Class, '$class', is not a subclass of 'Year'.");
        }

        $asDateAndTime = $aDateAndTime->asDateAndTime();
        $midnight = $asDateAndTime->atMidnight();
        $year = new $class();
        $year->setStart($midnight);
        $year->setDuration(Duration::withDays(Year::getDaysInYear($midnight->year())));

        return $year;
    }

    /**
     * Create a new Year.
     *
     * @param int $anInteger
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object Year
     *
     * @since 5/4/05
     *
     * @static
     */
    public static function withYear($anInteger, $class = 'Year')
    {
        $start = DateAndTime::withYearMonthDay($anInteger, 1, 1);
        eval('$result = '.$class.'::startingDuration(
				$start, 
				$null = NULL,
				$class
			);');

        return $result;
    }

    /**
     *  Return the number of days in a year.
     *
     * @param int $anInteger
     *
     * @return int
     *
     * @since 10/15/08
     *
     * @static
     */
    public static function getDaysInYear($anInteger)
    {
        if (is_null($anInteger)) {
            throw new InvalidArgumentException('Cannot execute daysInYear for NULL.');
        }

        if (Year::isYearLeapYear($anInteger)) {
            return 365 + 1;
        } else {
            return 365;
        }
    }

    /**
     * Return TRUE if the year passed is a leap year.
     *
     * @param int $anInteger
     *
     * @return bool
     *
     * @since 10/15/08
     *
     * @static
     */
    public static function isYearLeapYear($anInteger)
    {
        if ($anInteger > 0) {
            $adjustedYear = $anInteger;
        } else {
            $adjustedYear = 0 - ($anInteger + 1);
        }

        if ((0 != $adjustedYear % 4)
            || ((0 == $adjustedYear % 100) && (0 != $adjustedYear % 400))) {
            return false;
        } else {
            return true;
        }
    }

    /*********************************************************
     * Hybrid Class/Instance Methods
     *********************************************************/

    /**
     * Return TRUE if this year passed is a leap year.
     *
     * @return bool
     *
     * @since 5/4/05
     *
     * @static
     */
    public function isLeapYear()
    {
        return self::isYearLeapYear($this->startYear());
    }

    /*********************************************************
     * Instance Methods - Accessing
     *********************************************************/

    /**
     * Answer a printable string.
     *
     * @return string
     *
     * @since 5/23/05
     */
    public function printableString($printLeadingSpaceToo = false)
    {
        return $this->startYear();
    }

    /**
     * Return the number of days in a year.
     *
     * @return int
     *
     * @since 5/4/05
     */
    public function daysInYear()
    {
        return $this->duration->days();
    }

    /*********************************************************
     * Instance Methods - Converting
     *********************************************************/

    /**
     * Answer the receiver as a Year.
     *
     * @return object Year
     *
     * @since 5/23/05
     */
    public function asYear()
    {
        return $this;
    }
}
