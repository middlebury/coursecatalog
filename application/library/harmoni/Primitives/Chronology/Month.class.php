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

        throw new InvalidArgumentException($aNameString.' is not a recognized month name.');
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

        throw new InvalidArgumentException($aNameString.' is not a valid month index.');
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
            throw new InvalidArgumentException($aNameString.' is not a valid month index.');
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
     *********************************************************/

    /**
     * Read a month from the stream in any of the forms:
     *
     *		- July 1998
     *
     * @param string $aString
     *
     * @return object Month
     *
     * @since 5/10/05
     *
     * @static
     */
    public static function fromString($aString)
    {
        $parser = StringParser::getParserFor($aString);

        if (!is_string($aString) || !preg_match('/[^\W]/', $aString) || !$parser) {
            return null;
        }

        return static::withMonthYear($parser->month(), $parser->year());
    }

    /**
     * Create a new object starting now, with a given duration.
     * Override - as each month has a defined duration.
     *
     * @param object DateAndTime $aDateAndTime
     * @param object Duration $aDuration
     *
     * @return object Month
     *
     * @since 5/5/05
     *
     * @static
     */
    public static function startingDuration($aDateAndTime, $aDuration)
    {
        $start = $aDateAndTime->asDateAndTime();
        $adjusted = DateAndTime::withYearMonthDay($start->year(), $start->month(), 1);
        $days = static::daysInMonthForYear($adjusted->month(), $adjusted->year());

        $month = new static();
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
     *
     * @return object Month
     *
     * @since 5/11/05
     *
     * @static
     */
    public static function withMonthYear($anIntegerOrStringMonth, $anIntegerYear)
    {
        return static::starting(DateAndTime::withYearMonthDay($anIntegerYear, $anIntegerOrStringMonth, 1));
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
        return static::startingDuration(
            $this->start->minus(Duration::withDays(1)),
            $this->duration
        );
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
