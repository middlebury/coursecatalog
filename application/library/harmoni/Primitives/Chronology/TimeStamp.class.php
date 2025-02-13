<?php

/**
 * @since 5/11/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: TimeStamp.class.php,v 1.6 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

require_once __DIR__.'/DateAndTime.class.php';

/**
 * This represents a duration of 0 length that marks a particular point in time.
 *
 * To create new TimeStamp instances, <b>use one of the static instance-creation
 * methods</b>, NOT 'new TimeStamp':
 *		- {@link current TimeStamp::current()}
 *		- {@link current TimeStamp::current()}
 *		- {@link epoch TimeStamp::epoch()}
 *		- {@link fromString TimeStamp::fromString($aString)}
 *		- {@link fromUnixTimeStamp TimeStamp::fromUnixTimeStamp($aUnixTimeStamp)}
 *		- {@link midnight TimeStamp::midnight()}
 *		- {@link now TimeStamp::now()}
 *		- {@link noon TimeStamp::noon()}
 *		- {@link today TimeStamp::today()}
 *		- {@link tomorrow TimeStamp::tomorrow()}
 *		- {@link withDateAndTime TimeStamp::withDateAndTime($aDate, $aTime)}
 *		- {@link withJulianDayNumber TimeStamp::withJulianDayNumber($aJulianDayNumber)}
 *		- {@link withYearDay TimeStamp::withYearDay($anIntYear, $anIntDayOfYear)}
 *		- {@link withYearDayHourMinuteSecond TimeStamp::withYearDayHourMinuteSecond(
 *						$anIntYear, $anIntDayOfYear, $anIntHour, $anIntMinute,
 *						$anIntSecond)}
 *		- {@link withYearDayHourMinuteSecondOffset
 *						TimeStamp::withYearDayHourMinuteSecondOffset($anIntYear,
 *						$anIntDayOfYear, $anIntHour, $anIntMinute, $anIntSecond,
 *						$aDurationOffset)}
 *		- {@link withYearMonthDay TimeStamp::withYearMonthDay($anIntYear,
 *						$anIntOrStringMonth, $anIntDay)}
 *		- {@link withYearMonthDayHourMinute TimeStamp::withYearMonthDayHourMinute(
 *						$anIntYear, $anIntOrStringMonth, $anIntDay, $anIntHour,
 *						$anIntMinute)}
 *		- {@link withYearMonthDayHourMinuteSecond
 *						TimeStamp::withYearMonthDayHourMinuteSecond($anIntYear,
 *						$anIntOrStringMonth, $anIntDay, $anIntHour, $anIntMinute,
 *						$anIntSecond)}
 *		- {@link withYearMonthDayHourMinuteSecondOffset
 *						TimeStamp::withYearMonthDayHourMinuteSecondOffset($anIntYear,
 *						$anIntOrStringMonth, $anIntDay, $anIntHour, $anIntMinute,
 *						$anIntSecond, $aDurationOffset)}
 *		- {@link yesterday TimeStamp::yesterday()}
 *
 * @since 5/11/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: TimeStamp.class.php,v 1.6 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class TimeStamp extends DateAndTime
{
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
     * Answer a TimeStamp representing now.
     *
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object TimeStamp
     *
     * @since 5/13/05
     */
    public static function current($class = 'TimeStamp')
    {
        eval('$result = '.$class.'::now();');

        return $result;
    }

    /**
     * Answer a TimeStamp representing the Squeak epoch: 1 January 1901.
     *
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object TimeStamp
     *
     * @since 5/2/05
     *
     * @static
     */
    public static function epoch($class = 'TimeStamp')
    {
        $obj = parent::epoch($class);

        return $obj;
    }

    /**
     * Answer a new instance represented by a string:
     *
     *	- '-1199-01-05T20:33:14.321-05:00'
     *	- ' 2002-05-16T17:20:45.00000001+01:01'
     *	- ' 2002-05-16T17:20:45.00000001'
     *	- ' 2002-05-16T17:20'
     *	- ' 2002-05-16T17:20:45'
     *	- ' 2002-05-16T17:20:45+01:57'
     *	- ' 2002-05-16T17:20:45-02:34'
     *	- ' 2002-05-16T17:20:45+00:00'
     *	- ' 1997-04-26T01:02:03+01:02:3'
     *
     * @param string $aString the input string
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object TimeStamp
     *
     * @since 5/12/05
     *
     * @static
     */
    public static function fromString($aString, $class = 'TimeStamp')
    {
        $obj = parent::fromString($aString, $class);

        return $obj;
    }

    /**
     * Create a new TimeStamp from a UNIX timestamp.
     *
     * @param int $aUnixTimeStamp The number of seconds since the Unix Epoch
     *                            (January 1 1970 00:00:00 GMT/UTC)
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object Timestamp
     *
     * @since 5/27/05
     *
     * @static
     */
    public static function fromUnixTimeStamp($aUnixTimeStamp, $class = 'TimeStamp')
    {
        $sinceUnixEpoch = Duration::withSeconds($aUnixTimeStamp);

        eval('$unixEpoch = '.$class.'::withYearMonthDayHourMinuteSecondOffset(
						1970, 1, 1, 0, 0, 0, Duration::zero());');
        $obj = $unixEpoch->plus($sinceUnixEpoch);

        return $obj;
    }

    /**
     * Answer a new instance starting at midnight local time.
     *
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object TimeStamp
     *
     * @since 5/3/05
     *
     * @static
     */
    public static function midnight($class = 'TimeStamp')
    {
        $obj = parent::midnight($class);

        return $obj;
    }

    /**
     * Answer the current time.
     *
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object TimeStamp
     *
     * @since 5/12/05
     *
     * @static
     */
    public static function now($class = 'TimeStamp')
    {
        $obj = parent::now($class);

        return $obj;
    }

    /**
     * Answer a new instance starting at noon local time.
     *
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object TimeStamp
     *
     * @since 5/3/05
     *
     * @static
     */
    public static function noon($class = 'TimeStamp')
    {
        $obj = parent::noon($class);

        return $obj;
    }

    /**
     * Answer a new instance representing today.
     *
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object TimeStamp
     *
     * @since 5/12/05
     *
     * @static
     */
    public static function today($class = 'TimeStamp')
    {
        $obj = parent::today($class);

        return $obj;
    }

    /**
     * Answer a new instance representing tomorow.
     *
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object TimeStamp
     *
     * @since 5/12/05
     *
     * @static
     */
    public static function tomorrow($class = 'TimeStamp')
    {
        $obj = parent::tomorrow($class);

        return $obj;
    }

    /**
     * Create a new instance from Date and Time objects.
     *
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object TimeStamp
     *
     * @since 5/12/05
     *
     * @static
     */
    public static function withDateAndTime($aDate, $aTime, $class = 'TimeStamp')
    {
        $obj = parent::withDateAndTime($aDate, $aTime, $class);

        return $obj;
    }

    /**
     * Create a new instance for a given Julian Day Number.
     *
     * @param int $aJulianDayNumber
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object TimeStamp
     *
     * @since 5/2/05
     *
     * @static
     */
    public static function withJulianDayNumber($aJulianDayNumber, $class = 'TimeStamp')
    {
        $obj = parent::withJulianDayNumber($aJulianDayNumber, $class);

        return $obj;
    }

    /**
     * Create a new instance.
     *
     * @param int $anIntYear
     * @param int $anIntDayOfYear
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @static
     *
     * @since 5/4/05
     */
    public static function withYearDay($anIntYear, $anIntDayOfYear, $class = 'TimeStamp')
    {
        $obj = parent::withYearDay($anIntYear, $anIntDayOfYear, $class);

        return $obj;
    }

    /**
     * Create a new instance.
     *
     * @param int $anIntYear
     * @param int $anIntDayOfYear
     * @param int $anIntHour
     * @param int $anIntMinute
     * @param int $anIntSecond
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object TimeStamp
     *
     * @static
     *
     * @since 5/4/05
     */
    public static function withYearDayHourMinuteSecond($anIntYear, $anIntDayOfYear,
        $anIntHour, $anIntMinute, $anIntSecond, $class = 'TimeStamp')
    {
        $obj = parent::withYearDayHourMinuteSecond($anIntYear, $anIntDayOfYear,
            $anIntHour, $anIntMinute, $anIntSecond, $class);

        return $obj;
    }

    /**
     * Create a new instance.
     *
     * @param int $anIntYear
     * @param int $anIntDayOfYear
     * @param int $anIntHour
     * @param int $anIntMinute
     * @param int $anIntSecond
     * @param object Duration $aDurationOffset
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object TimeStamp
     *
     * @static
     *
     * @since 5/4/05
     */
    public static function withYearDayHourMinuteSecondOffset($anIntYear, $anIntDayOfYear,
        $anIntHour, $anIntMinute, $anIntSecond, $aDurationOffset, $class = 'TimeStamp')
    {
        $obj = parent::withYearDayHourMinuteSecondOffset($anIntYear, $anIntDayOfYear,
            $anIntHour, $anIntMinute, $anIntSecond, $aDurationOffset, $class);

        return $obj;
    }

    /**
     * Create a new instance.
     *
     * @param int $anIntYear
     * @param int $anIntOrStringMonth
     * @param int $anIntDay
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object TimeStamp
     *
     * @static
     *
     * @since 5/4/05
     */
    public static function withYearMonthDay($anIntYear, $anIntOrStringMonth, $anIntDay,
        $class = 'Timestamp')
    {
        $obj = parent::withYearMonthDay($anIntYear, $anIntOrStringMonth, $anIntDay,
            $class);

        return $obj;
    }

    /**
     * Create a new instance.
     *
     * @param int $anIntYear
     * @param int $anIntOrStringMonth
     * @param int $anIntDay
     * @param int $anIntHour
     * @param int $anIntMinute
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object TimeStamp
     *
     * @static
     *
     * @since 5/4/05
     */
    public static function withYearMonthDayHourMinute($anIntYear, $anIntOrStringMonth,
        $anIntDay, $anIntHour, $anIntMinute, $class = 'TimeStamp')
    {
        $obj = parent::withYearMonthDayHourMinute($anIntYear, $anIntOrStringMonth,
            $anIntDay, $anIntHour, $anIntMinute, $class);

        return $obj;
    }

    /**
     * Create a new instance.
     *
     * @param int $anIntYear
     * @param int $anIntOrStringMonth
     * @param int $anIntDay
     * @param int $anIntHour
     * @param int $anIntMinute
     * @param int $anIntSecond
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object TimeStamp
     *
     * @static
     *
     * @since 5/4/05
     */
    public static function withYearMonthDayHourMinuteSecond($anIntYear, $anIntOrStringMonth,
        $anIntDay, $anIntHour, $anIntMinute, $anIntSecond, $class = 'TimeStamp')
    {
        $obj = parent::withYearMonthDayHourMinuteSecond($anIntYear, $anIntOrStringMonth,
            $anIntDay, $anIntHour, $anIntMinute, $anIntSecond, $class);

        return $obj;
    }

    /**
     * Create a new instance.
     *
     * @param int $anIntYear
     * @param int $anIntOrStringMonth
     * @param int $anIntDay
     * @param int $anIntHour
     * @param int $anIntMinute
     * @param int $anIntSecond
     * @param object Duration $aDurationOffset
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object TimeStamp
     *
     * @static
     *
     * @since 5/4/05
     */
    public static function withYearMonthDayHourMinuteSecondOffset($anIntYear,
        $anIntOrStringMonth, $anIntDay, $anIntHour, $anIntMinute,
        $anIntSecond, $aDurationOffset, $class = 'TimeStamp')
    {
        $obj = parent::withYearMonthDayHourMinuteSecondOffset($anIntYear,
            $anIntOrStringMonth, $anIntDay, $anIntHour, $anIntMinute,
            $anIntSecond, $aDurationOffset, $class);

        return $obj;
    }

    /**
     * Answer a new instance representing yesterday.
     *
     * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
     *		This parameter is used to get around the limitations of not being
     *		able to find the class of the object that recieved the initial
     *		method call.
     *
     * @return object TimeStamp
     *
     * @since 5/12/05
     *
     * @static
     */
    public static function yesterday($class = 'TimeStamp')
    {
        $obj = parent::yesterday($class);

        return $obj;
    }

    /*********************************************************
     * Instance methods - Accessing
     *********************************************************/

    /**
     * Print receiver's date and time.
     *
     * @return string
     *
     * @since 5/13/05
     */
    public function printableString($printLeadingSpaceToo = false)
    {
        $date = $this->date();
        $time = $this->time();

        return $date->printableString().' '.$time->printableString();
    }

    /*********************************************************
     * Instance methods - Converting
     *********************************************************/

    /**
     * Answer a DateAndTime that represents this TimeStamp.
     *
     * @return object DateAndTime
     *
     * @since 5/5/05
     */
    public function asDateAndTime()
    {
        return DateAndTime::withYearMonthDayHourMinuteSecondOffset($this->year(), $this->month(), $this->dayOfMonth(), $this->hour(), $this->minute(), $this->second(), $this->offset());
    }

    /**
     * Answer a Timestamp that represents this DateAndTime.
     *
     * @return object TimeStamp
     *
     * @since 5/5/05
     */
    public function asTimeStamp()
    {
        return $this;
    }

    /**
     * Answer the reciever as a UNIX timestamp - The number of seconds since the
     * Unix Epoch (January 1 1970 00:00:00 GMT/UTC).
     *
     * @return int
     *
     * @since 5/27/05
     */
    public function asUnixTimeStamp()
    {
        $sinceUnixEpoch = $this->minus(self::withYearMonthDayHourMinuteSecondOffset(
            1970, 1, 1, 0, 0, 0, Duration::zero()));

        return $sinceUnixEpoch->asSeconds();
    }

    /**
     * Answer the date of the receiver.
     *
     * @return object Date
     *
     * @since 5/13/05
     */
    public function date()
    {
        $obj = $this->asDate();

        return $obj;
    }

    /**
     * Answer a two element Array containing the receiver's date and time.
     *
     * @return array
     *
     * @since 5/13/05
     */
    public function dateAndTimeArray()
    {
        return [
            $this->date(),
            $this->time(),
        ];
    }

    /**
     * Answer a TimeStamp which is anInteger days before the receiver.
     *
     * @param int $anInteger
     *
     * @return object TimeStamp
     *
     * @since 5/13/05
     */
    public function minusDays($anInteger)
    {
        $obj = $this->minus(Duration::withDays($anInteger));

        return $obj;
    }

    /**
     * Answer a TimeStamp which is anInteger seconds before the receiver.
     *
     * @param int $anInteger
     *
     * @return object TimeStamp
     *
     * @since 5/13/05
     */
    public function minusSeconds($anInteger)
    {
        $obj = $this->minus(Duration::withSeconds($anInteger));

        return $obj;
    }

    /**
     * Answer a TimeStamp which is anInteger days after the receiver.
     *
     * @param int $anInteger
     *
     * @return object TimeStamp
     *
     * @since 5/13/05
     */
    public function plusDays($anInteger)
    {
        $obj = $this->plus(Duration::withDays($anInteger));

        return $obj;
    }

    /**
     * Answer a TimeStamp which is anInteger seconds after the receiver.
     *
     * @param int $anInteger
     *
     * @return object TimeStamp
     *
     * @since 5/13/05
     */
    public function plusSeconds($anInteger)
    {
        $obj = $this->plus(Duration::withSeconds($anInteger));

        return $obj;
    }

    /**
     * Answer the time of the receiver.
     *
     * @return object Time
     *
     * @since 5/13/05
     */
    public function time()
    {
        $obj = $this->asTime();

        return $obj;
    }
}
