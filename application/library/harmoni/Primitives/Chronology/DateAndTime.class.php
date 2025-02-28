<?php

/**
 * @since 5/2/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: DateAndTime.class.php,v 1.7 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

require_once __DIR__.'/../Magnitudes/Magnitude.class.php';
require_once __DIR__.'/AsDateAndTime.php';

/**
 * I represent a point in UTC time as defined by ISO 8601. I have zero duration.
 *
 * My implementation uses two Integers and a Duration:
 * 		- jdn		- julian day number.
 * 		- seconds	- number of seconds since midnight.
 * 		- offset	- duration from UTC.
 *
 * To create new DateAndTime instances, <b>use one of the static instance-creation
 * methods</b>, NOT 'new DateAndTime':
 *		- {@link epoch DateAndTime::epoch()}
 *		- {@link epoch DateAndTime::epoch()}
 *		- {@link fromString DateAndTime::fromString($aString)}
 *		- {@link midnight DateAndTime::midnight()}
 *		- {@link now DateAndTime::now()}
 *		- {@link noon DateAndTime::noon()}
 *		- {@link today DateAndTime::today()}
 *		- {@link tomorrow DateAndTime::tomorrow()}
 *		- {@link withDateAndTime DateAndTime::withDateAndTime($aDate, $aTime)}
 *		- {@link withJulianDayNumber DateAndTime::withJulianDayNumber($aJulianDayNumber)}
 *		- {@link withYearDay DateAndTime::withYearDay($anIntYear, $anIntDayOfYear)}
 *		- {@link withYearDayHourMinuteSecond DateAndTime::withYearDayHourMinuteSecond(
 *						$anIntYear, $anIntDayOfYear, $anIntHour, $anIntMinute,
 *						$anIntSecond)}
 *		- {@link withYearDayHourMinuteSecondOffset
 *						DateAndTime::withYearDayHourMinuteSecondOffset($anIntYear,
 *						$anIntDayOfYear, $anIntHour, $anIntMinute, $anIntSecond,
 *						$aDurationOffset)}
 *		- {@link withYearMonthDay DateAndTime::withYearMonthDay($anIntYear,
 *						$anIntOrStringMonth, $anIntDay)}
 *		- {@link withYearMonthDayHourMinute DateAndTime::withYearMonthDayHourMinute(
 *						$anIntYear, $anIntOrStringMonth, $anIntDay, $anIntHour,
 *						$anIntMinute)}
 *		- {@link withYearMonthDayHourMinuteSecond
 *						DateAndTime::withYearMonthDayHourMinuteSecond($anIntYear,
 *						$anIntOrStringMonth, $anIntDay, $anIntHour, $anIntMinute,
 *						$anIntSecond)}
 *		- {@link withYearMonthDayHourMinuteSecondOffset
 *						DateAndTime::withYearMonthDayHourMinuteSecondOffset($anIntYear,
 *						$anIntOrStringMonth, $anIntDay, $anIntHour, $anIntMinute,
 *						$anIntSecond, $aDurationOffset)}
 *		- {@link yesterday DateAndTime::yesterday()}
 *
 * @since 5/2/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: DateAndTime.class.php,v 1.7 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class DateAndTime extends Magnitude implements AsDateAndTime
{
    /*********************************************************
     * Instance Variables
     *********************************************************/

    /**
     * @var int; JulianDateNumber
     *
     * @since 5/11/05
     */
    public $jdn;

    /**
     * @var int; Seconds this day
     *
     * @since 5/11/05
     */
    public $seconds;

    /**
     * @var object Duration; The offset from UTC
     *
     * @since 5/11/05
     */
    public $offset;

    /*********************************************************
     * Class Methods
     *********************************************************/

    /**
     * One second precision.
     *
     * @return Duration
     *
     * @since 5/12/05
     *
     * @static
     */
    public static function clockPrecision()
    {
        return Duration::withSeconds(1);
    }

    /**
     * Answer the duration we are offset from UTC.
     *
     * @return Duration
     *
     * @static
     *
     * @since 5/3/05
     */
    public static function localOffset()
    {
        return static::localTimeZone()->offset();
    }

    /**
     * Answer the local TimeZone.
     *
     * @return Duration
     *
     * @static
     *
     * @since 5/3/05
     */
    public static function localTimeZone()
    {
        $tzAbbreviation = date('T');
        $tzOffset = date('Z');
        if ($tzAbbreviation && $tzOffset) {
            return TimeZone::offsetNameAbbreviation(
                Duration::withSeconds($tzOffset),
                $tzAbbreviation,
                $tzAbbreviation
            );
        } else {
            return TimeZone::defaultTimeZone();
        }
    }

    /*********************************************************
     * Class Methods - Instance Creation
     *********************************************************/

    /**
     * Answer a new instance representing the Squeak epoch: 1 January 1901.
     *
     * @return DateAndTime
     *
     * @since 5/2/05
     *
     * @static
     */
    public static function epoch()
    {
        return static::withJulianDayNumber(ChronologyConstants::SqueakEpoch());
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
     *
     * @return DateAndTime
     *
     * @since 5/12/05
     *
     * @static
     */
    public static function fromString(string $aString)
    {
        $parser = StringParser::getParserFor($aString);

        if (!is_string($aString) || !preg_match('/[^\W]/', $aString) || !$parser) {
            return null;
        }

        if (!is_null($parser->offsetHour())) {
            return static::withYearMonthDayHourMinuteSecondOffset(
                $parser->year(),
                $parser->month(),
                $parser->day(),
                (int) $parser->hour(),
                (int) $parser->minute(),
                (int) $parser->second(),
                Duration::withDaysHoursMinutesSeconds(
                    0,
                    (int) $parser->offsetHour(),
                    (int) $parser->offsetMinute(),
                    (int) $parser->offsetSecond()
                )
            );
        } elseif (!is_null($parser->hour())) {
            return static::withYearMonthDayHourMinuteSecond(
                $parser->year(),
                $parser->month(),
                $parser->day(),
                $parser->hour(),
                (int) $parser->minute(),
                (int) $parser->second()
            );
        } else {
            if (is_null($parser->month())) {
                return static::withYearMonthDay($parser->year(), 1, 1);
            } elseif (is_null($parser->day())) {
                return static::withYearMonthDay($parser->year(), $parser->month(), 1);
            } else {
                return static::withYearMonthDay($parser->year(), $parser->month(), $parser->day());
            }
        }
    }

    /**
     * Answer a new instance starting at midnight local time.
     *
     * @return DateAndTime
     *
     * @since 5/3/05
     *
     * @static
     */
    public static function midnight()
    {
        $now = static::now();

        return $now->atMidnight();
    }

    /**
     * Answer a new instance starting at noon local time.
     *
     * @return DateAndTime
     *
     * @since 5/3/05
     *
     * @static
     */
    public static function noon()
    {
        $now = static::now();

        return $now->atNoon();
    }

    /**
     * Answer the current date and time.
     *
     * @return DateAndTime
     *
     * @since 5/12/05
     *
     * @static
     */
    public static function now()
    {
        return static::withYearMonthDayHourMinuteSecondOffset(
            (int) date('Y'),
            (int) date('n'),
            (int) date('j'),
            (int) date('G'),
            (int) date('i'),
            (int) date('s'),
            null
        );
    }

    /**
     * Answer a new instance representing today.
     *
     * @return DateAndTime
     *
     * @since 5/12/05
     *
     * @static
     */
    public static function today()
    {
        return static::midnight();
    }

    /**
     * Answer a new instance representing tomorow.
     *
     * @return DateAndTime
     *
     * @since 5/12/05
     *
     * @static
     */
    public static function tomorrow()
    {
        $today = static::today();
        $todaysDate = $today->asDate();
        $tomorowsDate = $todaysDate->next();

        return $tomorowsDate->asDateAndTime();
    }

    /**
     * Create a new instance from Date and Time objects.
     *
     * @return DateAndTime
     *
     * @since 5/12/05
     *
     * @static
     */
    public static function withDateAndTime(Date $aDate, Time $aTime)
    {
        return static::withYearDayHourMinuteSecond(
            $aDate->startYear(),
            $aDate->dayOfYear(),
            $aTime->hour(),
            $aTime->minute(),
            $aTime->second()
        );
    }

    /**
     * Create a new new instance for a given Julian Day Number.
     *
     * @return DateAndTime
     *
     * @since 5/2/05
     *
     * @static
     */
    public static function withJulianDayNumber(int $aJulianDayNumber)
    {
        $days = Duration::withDays($aJulianDayNumber);

        $dateAndTime = new static();
        $dateAndTime->ticksOffset($days->ticks(), self::localOffset());

        return $dateAndTime;
    }

    /**
     * Create a new instance.
     *
     * @static
     *
     * @since 5/4/05
     */
    public static function withYearDay(int $anIntYear, int $anIntDayOfYear)
    {
        return static::withYearDayHourMinuteSecond(
            $anIntYear,
            $anIntDayOfYear,
            0,
            0,
            0
        );
    }

    /**
     * Create a new instance.
     *
     * @return DateAndTime
     *
     * @static
     *
     * @since 5/4/05
     */
    public static function withYearDayHourMinuteSecond(int $anIntYear, int $anIntDayOfYear, int $anIntHour, int $anIntMinute, int $anIntSecond)
    {
        return static::withYearDayHourMinuteSecondOffset(
            $anIntYear,
            $anIntDayOfYear,
            $anIntHour,
            $anIntMinute,
            $anIntSecond,
            static::localOffset()
        );
    }

    /**
     * Create a new instance.
     *
     * @return DateAndTime
     *
     * @static
     *
     * @since 5/4/05
     */
    public static function withYearDayHourMinuteSecondOffset(int $anIntYear, int $anIntDayOfYear, int $anIntHour, int $anIntMinute, int $anIntSecond, ?Duration $aDurationOffset)
    {
        $result = static::withYearMonthDayHourMinuteSecondOffset(
            $anIntYear,
            1,
            1,
            $anIntHour,
            $anIntMinute,
            $anIntSecond,
            $aDurationOffset
        );
        if ($anIntDayOfYear <= 1) {
            $day = Duration::withDays(0);
        } else {
            $day = Duration::withDays($anIntDayOfYear - 1);
        }

        return $result->plus($day);
    }

    /**
     * Create a new instance.
     *
     * @static
     *
     * @since 5/4/05
     */
    public static function withYearMonthDay(int $anIntYear, int|string $anIntOrStringMonth, int $anIntDay)
    {
        return static::withYearMonthDayHourMinuteSecondOffset(
            $anIntYear,
            $anIntOrStringMonth,
            $anIntDay,
            0,
            0,
            0,
            null
        );
    }

    /**
     * Create a new instance.
     *
     * @return DateAndTime
     *
     * @static
     *
     * @since 5/4/05
     */
    public static function withYearMonthDayHourMinute(int $anIntYear, int|string $anIntOrStringMonth, int $anIntDay, int $anIntHour, int $anIntMinute)
    {
        return static::withYearMonthDayHourMinuteSecondOffset(
            $anIntYear,
            $anIntOrStringMonth,
            $anIntDay,
            $anIntHour,
            $anIntMinute,
            0,
            null,
        );
    }

    /**
     * Create a new instance.
     *
     * @return DateAndTime
     *
     * @static
     *
     * @since 5/4/05
     */
    public static function withYearMonthDayHourMinuteSecond(int $anIntYear, int|string $anIntOrStringMonth, int $anIntDay, int $anIntHour, int $anIntMinute, int $anIntSecond)
    {
        return static::withYearMonthDayHourMinuteSecondOffset(
            $anIntYear,
            $anIntOrStringMonth,
            $anIntDay,
            $anIntHour,
            $anIntMinute,
            $anIntSecond,
            null
        );
    }

    /**
     * Create a new instance.
     *
     * @return DateAndTime
     *
     * @static
     *
     * @since 5/4/05
     */
    public static function withYearMonthDayHourMinuteSecondOffset(int $anIntYear,
        int|string $anIntOrStringMonth, int $anIntDay, int $anIntHour, int $anIntMinute,
        int $anIntSecond, ?Duration $aDurationOffset)
    {
        // Ensure that we have no days less than 1.
        if ($anIntDay < 1) {
            $anIntDay = 1;
        }

        if (is_numeric($anIntOrStringMonth)) {
            $monthIndex = $anIntOrStringMonth;
        } else {
            $monthIndex = Month::indexOfMonth($anIntOrStringMonth);
        }

        $p = (int) (($monthIndex - 14) / 12);
        $q = $anIntYear + 4800 + $p;
        $r = $monthIndex - 2 - (12 * $p);
        $s = (int) (($anIntYear + 4900 + $p) / 100);

        $julianDayNumber = (int) ((1461 * $q) / 4)
                            + (int) ((367 * $r) / 12)
                            - (int) ((3 * $s) / 4)
                            + ($anIntDay - 32075);

        $since = Duration::withDaysHoursMinutesSeconds($julianDayNumber,
            $anIntHour, $anIntMinute, $anIntSecond);

        if (null === $aDurationOffset) {
            $offset = self::localOffset();
        } else {
            $offset = $aDurationOffset;
        }

        $dateAndTime = new static();
        $dateAndTime->ticksOffset($since->ticks(), $offset);

        return $dateAndTime;
    }

    /**
     * Answer a new instance representing yesterday.
     *
     * @return DateAndTime
     *
     * @since 5/12/05
     *
     * @static
     */
    public static function yesterday()
    {
        $today = static::today();
        $todaysDate = $today->asDate();
        $yesterdaysDate = $todaysDate->previous();

        return $yesterdaysDate->asDateAndTime();
    }

    /*********************************************************
     * 	Instance Methods - Private
     *********************************************************/

    /**
     * Initialize this DateAndTime.
     * ticks is {julianDayNumber. secondCount. nanoSeconds}.
     *
     * @return void
     *
     * @since 5/2/05
     */
    public function ticksOffset(array $ticks, Duration $utcOffset)
    {
        //		$this->_normalize($ticks, 2, ChronologyConstants::NanosInSecond());
        $this->_normalize($ticks, 1, ChronologyConstants::SecondsInDay());

        $this->jdn = $ticks[0];
        $this->seconds = $ticks[1];
        //		$this->nanos = $ticks[2];
        $this->offset = $utcOffset;
    }

    /**
     * Normalize tick values to make things like "2 days, 35 hours" into
     * "3 days, 9 hours".
     *
     * @param ref array $ticks
     * @param int $i    the index of the array to normalize
     * @param int $base the base to normalize to
     *
     * @return void
     *
     * @since 5/3/05
     */
    private function _normalize(array &$ticks, int $i, int $base)
    {
        $tick = $ticks[$i];
        $quo = floor(abs($tick) / $base);
        $rem = $tick % $base;
        if ($rem < 0) {
            --$quo;
            $rem = $base + $rem;
        }
        $ticks[$i - 1] += $quo;
        $ticks[$i] = $rem;
    }

    /**
     * Private - answer an array with our instance variables. Assumed to be UTC.
     *
     * @return array
     *
     * @since 5/4/05
     */
    public function ticks()
    {
        return [$this->jdn, $this->seconds];
    }

    /*********************************************************
     * Instance Methods - Accessing
     *********************************************************/

    /**
     * Answer the date and time at midnight on the day of the receiver.
     *
     * @return DateAndTime
     *
     * @since 5/25/05
     */
    public function atMidnight()
    {
        return static::withYearMonthDayHourMinuteSecondOffset($this->year(), $this->month(), $this->dayOfMonth(), 0, 0, 0, $this->offset());
    }

    /**
     * Answer noon on the day of the reciever.
     *
     * @return DateAndTime
     *
     * @since 5/25/05
     */
    public function atNoon()
    {
        return static::withYearMonthDayHourMinuteSecondOffset($this->year(), $this->month(), $this->dayOfMonth(), 12, 0, 0, $this->offset());
    }

    /**
     * Answer the day.
     *
     * @return int
     *
     * @since 5/3/05
     */
    public function day()
    {
        return $this->dayOfYear();
    }

    /**
     * Return an array with the following elements:
     *	'dd' 	=> day of the year
     *	'mm'	=> month
     *	'yyyy'	=> year
     *
     * The algorithm is from Squeak's DateAndTime>>dayMonthYearDo: method.
     *
     * @return array
     *
     * @since 5/3/05
     */
    public function dayMonthYearArray()
    {
        $l = $this->jdn + 68569;
        $n = floor((4 * $l) / 146097);
        $l -= floor(((146097 * $n) + 3) / 4);
        $i = floor((4000 * ($l + 1)) / 1461001);
        $l = ($l - floor((1461 * $i) / 4)) + 31;
        $j = floor((80 * $l) / 2447);
        $dd = $l - floor((2447 * $j) / 80);
        $l = floor($j / 11);
        $mm = $j + 2 - (12 * $l);
        $yyyy = (100 * ($n - 49)) + $i + $l;

        return ['dd' => $dd, 'mm' => $mm, 'yyyy' => $yyyy];
    }

    /**
     * Answer the day of the month.
     *
     * @return int
     *
     * @since 5/3/05
     */
    public function dayOfMonth()
    {
        $array = $this->dayMonthYearArray();

        return $array['dd'];
    }

    /**
     * Answer the day of the week.
     *
     * @return int
     *
     * @since 5/3/05
     */
    public function dayOfWeek()
    {
        $x = $this->jdn + 1;

        return ($x - ((int) ($x / 7) * 7)) + 1;
    }

    /**
     * Answer the day of the week abbreviation.
     *
     * @return string
     *
     * @since 5/3/05
     */
    public function dayOfWeekAbbreviation()
    {
        return substr($this->dayOfWeekName(), 0, 3);
    }

    /**
     * Answer the day of the week name.
     *
     * @return string
     *
     * @since 5/3/05
     */
    public function dayOfWeekName()
    {
        return Week::nameOfDay($this->dayOfWeek());
    }

    /**
     * Answer the day of the year.
     *
     * @return int
     *
     * @since 5/3/05
     */
    public function dayOfYear()
    {
        $thisYear = Year::withYear($this->year());
        $start = $thisYear->start();

        return $this->jdn - $start->julianDayNumber() + 1;
    }

    /**
     * Answer the number of days in the month represented by the receiver.
     *
     * @return ingteger
     *
     * @since 5/5/05
     */
    public function daysInMonth()
    {
        $month = $this->asMonth();

        return $month->daysInMonth();
    }

    /**
     * Answer the number of days in the year represented by the receiver.
     *
     * @return ingteger
     *
     * @since 5/5/05
     */
    public function daysInYear()
    {
        $year = $this->asYear();

        return $year->daysInYear();
    }

    /**
     * Answer the number of days in the year after the date of the receiver.
     *
     * @return ingteger
     *
     * @since 5/5/05
     */
    public function daysLeftInYear()
    {
        return $this->daysInYear() - $this->dayOfYear();
    }

    /**
     * Answer the duration of this object (always zero).
     *
     * @return Duration
     *
     * @since 5/5/05
     */
    public function duration()
    {
        $obj = Duration::zero();

        return $obj;
    }

    /**
     * Answer the day-in-the-year of the first day of our month.
     *
     * @return int
     *
     * @since 5/5/05
     */
    public function firstDayOfMonth()
    {
        $month = $this->asMonth();
        $monthStart = $month->start();

        return $monthStart->day();
    }

    /**
     * Answer just 'hh:mm:ss'. This is equivalent to Squeak's printHMSOn: method.
     *
     * @return string
     *
     * @since 5/10/05
     */
    public function hmsString()
    {
        $result = '';
        $result .= str_pad($this->hour(), 2, '0', \STR_PAD_LEFT);
        $result .= ':';
        $result .= str_pad($this->minute(), 2, '0', \STR_PAD_LEFT);
        $result .= ':';
        $result .= str_pad($this->second(), 2, '0', \STR_PAD_LEFT);

        return $result;
    }

    /**
     * Answer the hours (0-23).
     *
     * @return int
     *
     * @since 5/3/05
     */
    public function hour()
    {
        return $this->hour24();
    }

    /**
     * Answer the hours (0-23).
     *
     * @return int
     *
     * @since 5/3/05
     */
    public function hour24()
    {
        $duration = Duration::withSeconds($this->seconds);

        return $duration->hours();
    }

    /**
     * Answer an <integer> between 1 and 12, inclusive, representing the hour
     * of the day in the 12-hour clock of the local time of the receiver.
     *
     * @return int
     *
     * @since 5/4/05
     */
    public function hour12()
    {
        $x = ($this->hour24() - 1) % 12;
        if ($x < 0) {
            $x += 12;
        }

        return $x + 1;
    }

    /**
     * Return if this year is a leap year.
     *
     * @return bool
     *
     * @since 5/4/05
     */
    public function isLeapYear()
    {
        return Year::isYearLeapYear($this->year());
    }

    /**
     * Return the JulianDayNumber of this DateAndTime.
     *
     * @return int
     *
     * @since 5/4/05
     */
    public function julianDayNumber()
    {
        return $this->jdn;
    }

    /**
     * Return the Meridian Abbreviation ('AM'/'PM').
     *
     * @return string
     *
     * @since 5/5/05
     */
    public function meridianAbbreviation()
    {
        $time = $this->asTime();

        return $time->meridianAbbreviation();
    }

    /**
     * Answer the miniute (0-59).
     *
     * @return int
     *
     * @since 5/3/05
     */
    public function minute()
    {
        $duration = Duration::withSeconds($this->seconds);

        return $duration->minutes();
    }

    /**
     * Answer the month.
     *
     * @return int
     *
     * @since 5/3/05
     */
    public function month()
    {
        $array = $this->dayMonthYearArray();

        return $array['mm'];
    }

    /**
     * Answer the day of the week abbreviation.
     *
     * @return string
     *
     * @since 5/3/05
     */
    public function monthAbbreviation()
    {
        return substr($this->monthName(), 0, 3);
    }

    /**
     * Answer the index of the month.
     *
     * @return int
     *
     * @since 5/3/05
     */
    public function monthIndex()
    {
        return $this->month();
    }

    /**
     * Answer the name of the month.
     *
     * @return string
     *
     * @since 5/3/05
     */
    public function monthName()
    {
        return Month::nameOfMonth($this->month());
    }

    /**
     * Answer the offset.
     *
     * @return Duration
     *
     * @since 5/3/05
     */
    public function offset()
    {
        return $this->offset;
    }

    /**
     * Answer the second (0-59).
     *
     * @return int
     *
     * @since 5/3/05
     */
    public function second()
    {
        $duration = Duration::withSeconds($this->seconds);

        return $duration->seconds();
    }

    /**
     * Print as per ISO 8601 sections 5.3.3 and 5.4.1.
     * If printLeadingSpaceToo is false, prints either:
     *		'YYYY-MM-DDThh:mm:ss.s+ZZ:zz:z' (for positive years)
     *	or
     *		'-YYYY-MM-DDThh:mm:ss.s+ZZ:zz:z' (for negative years).
     *
     * If printLeadingSpaceToo is true, prints either:
     * 		' YYYY-MM-DDThh:mm:ss.s+ZZ:zz:z' (for positive years)
     *	or
     *		'-YYYY-MM-DDThh:mm:ss.s+ZZ:zz:z' (for negative years)
     *
     * This is equivalent to Squeak's printOn:withLeadingSpace: method.
     *
     * @return string
     *
     * @since 5/10/05
     */
    public function printableString(bool $printLeadingSpaceToo = false)
    {
        $result = $this->ymdString($printLeadingSpaceToo);
        $result .= 'T';
        $result .= $this->hmsString();

        if ($this->offset->isPositive()) {
            $result .= '+';
        } else {
            $result .= '-';
        }

        $result .= str_pad(abs($this->offset->hours()), 2, '0', \STR_PAD_LEFT);
        $result .= ':';
        $result .= str_pad(abs($this->offset->minutes()), 2, '0', \STR_PAD_LEFT);

        if (0 != $this->offset->seconds()) {
            $result .= ':';
            $result .= (int) (abs($this->offset->minutes()) / 10);
        }

        return $result;
    }

    /**
     * Answer the Time Zone that corresponds to our offset.
     *
     * @return TimeZone
     *
     * @since 5/10/05
     */
    public function timeZone()
    {
        // Search through the array of timezones for one that matches. Otherwise,
        // build our own. The name and abbreviation are just a guess, as multiple
        // Time Zones have the same offset.
        $zoneArray = TimeZone::timeZones();
        foreach (array_keys($zoneArray) as $key) {
            if ($this->offset->isEqualTo($zoneArray[$key]->offset())) {
                return $zoneArray[$key];
            }
        }
        $obj = TimeZone::offsetNameAbbreviation(
            $this->offset,
            $tzAbbreviation,
            $tzAbbreviation);

        return $obj;
    }

    /**
     * Answer the TimeZone abbreviation.
     *
     * @return string
     *
     * @since 5/10/05
     */
    public function timeZoneAbbreviation()
    {
        $timeZone = $this->timeZone();

        return $timeZone->abbreviation();
    }

    /**
     * Answer the TimeZone name.
     *
     * @return string
     *
     * @since 5/10/05
     */
    public function timeZoneName()
    {
        $timeZone = $this->timeZone();

        return $timeZone->name();
    }

    /**
     * Answer the year.
     *
     * @return int
     *
     * @since 5/3/05
     */
    public function year()
    {
        $array = $this->dayMonthYearArray();

        return $array['yyyy'];
    }

    /**
     * Print just the year, month, and day on aStream.
     *
     * If printLeadingSpaceToo is true, then print as:
     * 	' YYYY-MM-DD' (if the year is positive) or '-YYYY-MM-DD' (if the year is negative)
     * otherwise print as:
     * 	'YYYY-MM-DD' or '-YYYY-MM-DD'
     *
     * This is equivalent to Squeak's printYMDOn:withLeadingSpace: method.
     *
     * @return string
     *
     * @since 5/10/05
     */
    public function ymdString(bool $printLeadingSpaceToo = false)
    {
        $year = $this->year();
        $month = $this->month();
        $day = $this->dayOfMonth();

        $result = '';

        if ($year < 0) {
            $result .= '-';
        } else {
            if ($printLeadingSpaceToo) {
                $result .= ' ';
            }
        }

        $result .= str_pad(abs($year), 4, '0', \STR_PAD_LEFT);
        $result .= '-';
        $result .= str_pad($month, 2, '0', \STR_PAD_LEFT);
        $result .= '-';
        $result .= str_pad($day, 2, '0', \STR_PAD_LEFT);

        return $result;
    }

    /**
     * Print just the month, day, and year on aStream.
     *
     * @return string
     *
     * @since 5/10/05
     */
    public function mdyString()
    {
        $year = $this->year();
        $month = $this->month();
        $day = $this->dayOfMonth();

        $result = '';

        if ($year < 0) {
            $year = '-'.$year;
        }

        return "$month/$day/$year";
    }

    /**
     * Answer a string formated using the php date() format sting.
     * See: http://us2.php.net/manual/en/function.date.php for details.
     *
     * @return string
     *
     * @since 11/21/08
     */
    public function format(string $format)
    {
        // For PHP < 5.2.0
        if (!class_exists('DateTime')) {
            return date($format, $this->asTimestamp()->asUnixTimestamp());
        }

        return $this->asDateTime()->format($format);
    }

    /*********************************************************
     * Instance methods - Comparing/Testing
     *********************************************************/
    /**
     * comparand conforms to protocol DateAndTime,
     * or can be converted into something that conforms.
     *
     * @return bool
     *
     * @since 5/3/05
     */
    public function isEqualTo($comparand)
    {
        if ($this === $comparand) {
            return true;
        }

        if (!method_exists($comparand, 'asDateAndTime')) {
            return false;
        }

        $comparandAsDateAndTime = $comparand->asDateAndTime();

        if ($this->offset->isEqualTo($comparandAsDateAndTime->offset())) {
            $myTicks = $this->ticks();
            $comparandTicks = $comparandAsDateAndTime->ticks();
        } else {
            $meAsUTC = $this->asUTC();
            $myTicks = $meAsUTC->ticks();
            $comparandAsUTC = $comparandAsDateAndTime->asUTC();
            $comparandTicks = $comparandAsUTC->ticks();
        }

        if ($myTicks[0] != $comparandTicks[0]) {
            return false;
        } else {
            return $myTicks[1] == $comparandTicks[1];
        }
    }

    /**
     * comparand conforms to protocol DateAndTime,
     * or can be converted into something that conforms.
     *
     * @return bool
     *
     * @since 5/3/05
     */
    public function isLessThan($comparand)
    {
        $comparandAsDateAndTime = $comparand->asDateAndTime();

        if ($this->offset->isEqualTo($comparandAsDateAndTime->offset())) {
            $myTicks = $this->ticks();
            $comparandTicks = $comparandAsDateAndTime->ticks();
        } else {
            $meAsUTC = $this->asUTC();
            $myTicks = $meAsUTC->ticks();
            $comparandAsUTC = $comparandAsDateAndTime->asUTC();
            $comparandTicks = $comparandAsUTC->ticks();
        }

        if ($myTicks[0] < $comparandTicks[0]) {
            return true;
        } else {
            return ($myTicks[0] == $comparandTicks[0])
                    && ($myTicks[1] < $comparandTicks[1]);
        }
    }

    /*********************************************************
     * Instance methods - Operations
     *********************************************************/

    /**
     * Subtract a Duration or DateAndTime.
     *
     * @return object
     *
     * @since 5/3/05
     */
    public function minus($operand)
    {
        $methods = get_class_methods($operand);

        // If this conforms to the DateAndTimeProtocol
        if (in_array('asdateandtime', $methods)
            | in_array('asDateAndTime', $methods)) {
            $meLocal = $this->asLocal();
            $lticks = $meLocal->ticks();
            $opDAndT = $operand->asDateAndTime();
            $opLocal = $opDAndT->asLocal();
            $rticks = $opLocal->ticks();

            $obj = Duration::withSeconds(
                (($lticks[0] - $rticks[0]) * ChronologyConstants::SecondsInDay())
                + ($lticks[1] - $rticks[1]));

            return $obj;
        }
        // If this conforms to the Duration protocol
        else {
            $obj = $this->plus($operand->negated());

            return $obj;
        }
    }

    /**
     * Answer a new Duration whose our date + operand. The operand must implement
     * asDuration().
     *
     * @return DateAndTime
     *
     * @since 5/4/05
     */
    public function plus($operand)
    {
        $ticks = [];
        $duration = $operand->asDuration();
        $durationTicks = $duration->ticks();

        foreach ($this->ticks() as $key => $value) {
            $ticks[$key] = $value + $durationTicks[$key];
        }

        $result = new static();
        $result->ticksOffset($ticks, $this->offset());

        return $result;
    }

    /*********************************************************
     * Instance methods - Converting
     *********************************************************/

    /**
     * Answer a Date that represents this object.
     *
     * @return Date
     *
     * @since 5/5/05
     */
    public function asDate()
    {
        $obj = Date::starting($this);

        return $obj;
    }

    /**
     * Answer a DateAndTime that represents this object.
     *
     * @return DateAndTime
     *
     * @since 5/4/05
     */
    public function asDateAndTime()
    {
        return $this;
    }

    /**
     * Answer a Duration that represents this object, the duration since
     * midnight.
     *
     * @return Duration
     *
     * @since 5/4/05
     */
    public function asDuration()
    {
        $obj = Duration::withSeconds($this->seconds);

        return $obj;
    }

    /**
     * Answer a DateAndTime that represents the object, but at local time.
     *
     * @return DateAndTime
     *
     * @since 5/5/05
     */
    public function asLocal()
    {
        $myOffset = $this->offset();
        if ($myOffset->isEqualTo(self::localOffset())) {
            return $this;
        } else {
            $obj = $this->utcOffset(self::localOffset());

            return $obj;
        }
    }

    /**
     * Answer the month that represents this date's month.
     *
     * @return Month
     *
     * @since 5/5/05
     */
    public function asMonth()
    {
        $obj = Month::starting($this);

        return $obj;
    }

    /**
     * Return the number of seconds since the Squeak epoch.
     *
     * @return int
     *
     * @since 5/5/05
     */
    public function asSeconds()
    {
        $epoch = static::epoch();
        $sinceEpoch = $this->minus($epoch);

        return $sinceEpoch->asSeconds();
    }

    /**
     * Answer a Time that represents our time component.
     *
     * @return Time
     *
     * @since 5/5/05
     */
    public function asTime()
    {
        $obj = Time::withSeconds($this->seconds);

        return $obj;
    }

    /**
     * Answer a Timestamp that represents this DateAndTime.
     *
     * @return TimeStamp
     *
     * @since 5/5/05
     */
    public function asTimeStamp()
    {
        $obj = $this->asA('TimeStamp');

        return $obj;
    }

    /**
     * Answer a PHP build-in DateTime object (PHP > 5.2) with our values.
     *
     * @return DateTime
     *
     * @since 11/21/08
     */
    public function asDateTime()
    {
        $result = $this->ymdString(false);
        $result .= 'T';
        $result .= $this->hmsString();

        if ($this->offset->isPositive()) {
            $result .= '+';
        } else {
            $result .= '-';
        }

        $result .= str_pad(abs($this->offset->hours()), 2, '0', \STR_PAD_LEFT);
        $result .= ':';
        $result .= str_pad(abs($this->offset->minutes()), 2, '0', \STR_PAD_LEFT);

        $resultWithTZ = $result;
        if (0 != $this->offset->seconds()) {
            $resultWithTZ .= ':';
            $resultWithTZ .= (int) (abs($this->offset->minutes()) / 10);
        }

        $dateTime = new DateTime($resultWithTZ);

        // If our timezone abbrieviation has the same value as the offset, use it.
        $tzone = new DateTimeZone($this->timeZoneAbbreviation());
        if (false !== $tzone && $tzone->getOffset($dateTime) == $this->offset->asSeconds()) {
            //  			printpre('setting timezone');
            $dateTime->setTimezone($tzone);
        }

        return $dateTime;
    }

    /**
     * Answer a DateAndTime equivalent to the reciever, but at UTC (offset = 0).
     *
     * @return DateAndTime
     *
     * @since 5/4/05
     */
    public function asUTC()
    {
        $obj = $this->utcOffset(Duration::withHours(0));

        return $obj;
    }

    /**
     * Answer the week that represents this date's week.
     *
     * @return Week
     *
     * @since 5/5/05
     */
    public function asWeek()
    {
        $obj = Week::starting($this);

        return $obj;
    }

    /**
     * Answer the year that represents this date's year.
     *
     * @return Year
     *
     * @since 5/5/05
     */
    public function asYear()
    {
        $obj = Year::starting($this);

        return $obj;
    }

    /**
     * Return a Timespan where the receiver is the middle of the Duration.
     *
     * @return Timespan
     *
     * @since 5/12/05
     */
    public function middleOf(Duration $aDuration)
    {
        $duration = $aDuration->asDuration();

        $obj = Timespan::startingDuration(
            $this->minus($duration->dividedBy(2)),
            $duration);

        return $obj;
    }

    /**
     * Answer a <DateAndTime> equivalent to the receiver but offset from UTC by
     * aDuration. This will not convert the recievers time, merely change the
     * offset to anOffset; i.e. 11am at UTC-05:00 would become 11am at UTC-7:00
     * when -7 hours is passed as the offset.
     *
     * @return DateAndTime
     *
     * @since 5/4/05
     */
    public function withOffset(Duration $anOffset)
    {
        $equiv = new static();
        $equiv->ticksOffset($this->ticks(), $anOffset->asDuration());

        return $equiv;
    }

    /**
     * Answer a Timespan. anEnd conforms to protocol AsDateAndTime.
     *
     * @return Timespan
     *
     * @since 5/12/05
     */
    public function to(AsDateAndTime $anEnd)
    {
        $obj = Timespan::startingEnding($this, $anEnd->asDateAndTime());

        return $obj;
    }

    /**
     * Answer a Timespan. anEnd conforms to protocol DateAndTime or protocol Timespan.
     *
     * @param DateAndTime $anEnd
     * @param Duration
     *
     * @return Schedule
     *
     * @since 5/12/05
     */
    public function toBy(AsDateAndTime $anEnd, Duration $aDuration)
    {
        $schedule = Schedule::startingEnding($this, $anEnd->asDateAndTime());
        $schedule->addToSchedule([$aDuration->asDuration()]);

        return $schedule;
    }

    /**
     * Answer a <DateAndTime> equivalent to the receiver but offset from UTC by
     * aDuration. This will convert the recievers time, to the time at anOffset;
     * i.e. 11am at UTC-05:00 would become 9am at UTC-7:00 when -7 hours is passed
     * as the offset.
     *
     * @return DateAndTime
     *
     * @since 5/4/05
     */
    public function utcOffset(Duration $anOffset)
    {
        $duration = $anOffset->asDuration();
        $equiv = $this->plus($duration->minus($this->offset()));
        $equiv->ticksOffset($equiv->ticks(), $duration);

        return $equiv;
    }
}

require_once __DIR__.'/ChronologyConstants.class.php';
require_once __DIR__.'/Date.class.php';
require_once __DIR__.'/Duration.class.php';
require_once __DIR__.'/Month.class.php';
require_once __DIR__.'/Time.class.php';
require_once __DIR__.'/TimeStamp.class.php';
require_once __DIR__.'/TimeZone.class.php';
require_once __DIR__.'/Week.class.php';
require_once __DIR__.'/Year.class.php';
