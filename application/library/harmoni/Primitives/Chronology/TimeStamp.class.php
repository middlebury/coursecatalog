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
     *********************************************************/

    /**
     * Create a new TimeStamp from a UNIX timestamp.
     *
     * @param int $aUnixTimeStamp The number of seconds since the Unix Epoch
     *                            (January 1 1970 00:00:00 GMT/UTC)
     *
     * @return object Timestamp
     *
     * @since 5/27/05
     *
     * @static
     */
    public static function fromUnixTimeStamp($aUnixTimeStamp)
    {
        $sinceUnixEpoch = Duration::withSeconds($aUnixTimeStamp);

        $unixEpoch = static::withYearMonthDayHourMinuteSecondOffset(1970, 1, 1, 0, 0, 0, Duration::zero());

        return $unixEpoch->plus($sinceUnixEpoch);
    }

    /**
     * Answer a TimeStamp representing now.
     *
     * @return object TimeStamp
     *
     * @since 5/13/05
     */
    public static function current()
    {
        return static::now();
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
