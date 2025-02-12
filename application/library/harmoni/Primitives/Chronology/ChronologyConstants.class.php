<?php

/**
 * @since 5/2/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: ChronologyConstants.class.php,v 1.3 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

/**
 * ChronologyConstants is a SharedPool for the constants used by the
 * Kernel-Chronology classes.
 *
 * @since 5/2/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: ChronologyConstants.class.php,v 1.3 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 *
 * @static
 */
class ChronologyConstants
{
    /**
     * Julian day number of 1 Jan 1901.
     *
     * @return int
     *
     * @since 5/2/05
     *
     * @static
     */
    public static function SqueakEpoch()
    {
        return 2415386;
    }

    /**
     * Number of seconds in a day.
     *
     * @return int
     *
     * @since 5/2/05
     *
     * @static
     */
    public static function SecondsInDay()
    {
        return 86400;
    }

    /**
     * Number of seconds in an hour.
     *
     * @return int
     *
     * @since 5/2/05
     *
     * @static
     */
    public static function SecondsInHour()
    {
        return 3600;
    }

    /**
     * Number of seconds in a minute.
     *
     * @return int
     *
     * @since 5/2/05
     *
     * @static
     */
    public static function SecondsInMinute()
    {
        return 60;
    }

    /**
     * Nanoseconds in a second.
     *
     * @return int
     *
     * @since 5/2/05
     *
     * @static
     */
    public static function NanosInSecond()
    {
        return 10 ** 9;
    }

    /**
     * Nanoseconds in a millisecond.
     *
     * @return int
     *
     * @since 5/2/05
     *
     * @static
     */
    public static function NanosInMillisecond()
    {
        return 10 ** 6;
    }

    /**
     * Names of days of the week.
     *
     * @return array
     *
     * @since 5/2/05
     *
     * @static
     */
    public static function DayNames()
    {
        return [1 => 'Sunday', 2 => 'Monday', 3 => 'Tuesday', 4 => 'Wednesday',
            5 => 'Thursday', 6 => 'Friday', 7 => 'Saturday'];
    }

    /**
     * Names of months.
     *
     * @return array
     *
     * @since 5/2/05
     *
     * @static
     */
    public static function MonthNames()
    {
        return [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September',
            10 => 'October', 11 => 'November', 12 => 'December'];
    }

    /**
     * Names number of days in each month.
     *
     * @return array
     *
     * @since 5/2/05
     *
     * @static
     */
    public static function DaysInMonth()
    {
        return [1 => 31, 2 => 28, 3 => 31, 4 => 30, 5 => 31, 6 => 30,
            7 => 31, 8 => 31, 9 => 30, 10 => 31, 11 => 30, 12 => 31];
    }
}
