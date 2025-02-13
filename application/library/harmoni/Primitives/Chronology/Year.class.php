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

require_once __DIR__.'/Timespan.class.php';

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
     *********************************************************/

    /**
     * Create a new object starting from midnight.
     *
     * @param DateAndTime $aDateAndTime
     *
     * @return Year
     *
     * @since 5/5/05
     *
     * @static
     */
    public static function startingDuration(AsDateAndTime $aDateAndTime, ?Duration $aDuration)
    {
        $asDateAndTime = $aDateAndTime->asDateAndTime();
        $midnight = $asDateAndTime->atMidnight();
        $year = new static();
        $year->setStart($midnight);
        $year->setDuration(Duration::withDays(static::getDaysInYear($midnight->year())));

        return $year;
    }

    /**
     * Create a new Year.
     *
     * @return Year
     *
     * @since 5/4/05
     *
     * @static
     */
    public static function withYear(int $anInteger)
    {
        $start = DateAndTime::withYearMonthDay($anInteger, 1, 1);

        return static::startingDuration(
            $start,
            null
        );
    }

    /**
     *  Return the number of days in a year.
     *
     * @return int
     *
     * @since 10/15/08
     *
     * @static
     */
    public static function getDaysInYear(int $anInteger)
    {
        if (null === $anInteger) {
            throw new InvalidArgumentException('Cannot execute daysInYear for NULL.');
        }

        if (self::isYearLeapYear($anInteger)) {
            return 365 + 1;
        } else {
            return 365;
        }
    }

    /**
     * Return TRUE if the year passed is a leap year.
     *
     * @return bool
     *
     * @since 10/15/08
     *
     * @static
     */
    public static function isYearLeapYear(int $anInteger)
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
    public function printableString(bool $printLeadingSpaceToo = false)
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
     * @return Year
     *
     * @since 5/23/05
     */
    public function asYear()
    {
        return $this;
    }
}
