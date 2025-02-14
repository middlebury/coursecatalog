<?php

/**
 * @since 5/4/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Date.class.php,v 1.6 2008/02/29 21:25:23 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

require_once __DIR__.'/Timespan.class.php';
require_once __DIR__.'/DateAndTime.class.php';

/**
 * Instances of Date are Timespans with duration of 1 day.
 * Their default creation assumes a start of midnight in the local time zone.
 *
 * To create new Date instances, <b>use one of the static instance-creation
 * methods</b>, NOT 'new Date':
 *		- {@link current Date::current()}
 *		- {@link current Date::current()}
 *		- {@link epoch Date::epoch()}
 *		- {@link fromString Date::fromString($aString)}
 *		- {@link starting Date::starting($aDateAndTime)}
 *		- {@link startingDuration Date::startingDuration($aDateAndTime, $aDuration)}
 *		- {@link startingEnding Date::startingEnding($startDateAndTime, $endDateAndTime)}
 *		- {@link today Date::today()}
 *		- {@link tomorrow Date::tomorrow()}
 *		- {@link withJulianDayNumber Date::withJulianDayNumber($aJulianDayNumber)}
 *		- {@link withYearDay Date::withYearDay($anIntYear, $anIntDayOfYear)}
 *		- {@link yesterday Date::yesterday()}
 *
 * @since 5/4/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Date.class.php,v 1.6 2008/02/29 21:25:23 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class Date extends Timespan
{
    /*********************************************************
     * Class Methods
     *********************************************************/

    /*********************************************************
     * Class Methods - Instance Creation
     *********************************************************/

    /**
     * Read a Date from the stream in any of the forms:
     *
     *		- <day> <monthName> <year>		(5 April 1982; 5-APR-82)
     *
     *		- <monthName> <day> <year>		(April 5, 1982)
     *
     *		- <monthNumber> <day> <year>		(4/5/82)
     *		- <day><monthName><year>			(5APR82)
     *		- <four-digit year><two-digit monthNumber><two-digit day>
     *											(19820405; 1982-04-05)
     *
     * @return Date
     *
     * @since 5/10/05
     *
     * @static
     */
    public static function fromString(string $aString)
    {
        $parser = StringParser::getParserFor($aString);

        if (!is_string($aString) || !preg_match('/[^\W]/', $aString) || !$parser) {
            return null;
        }

        return static::withYearMonthDay($parser->year(), $parser->month(), $parser->day());
    }

    /**
     * Create a new object starting now, with our default one day duration.
     *
     * @return Date
     *
     * @since 5/5/05
     *
     * @static
     */
    public static function starting(AsDateAndTime $aDateAndTime)
    {
        return static::startingDuration($aDateAndTime->asUTC()->atMidnight(), Duration::withDays(1));
    }

    /**
     * Answer today's date.
     *
     * @return Date
     *
     * @since 5/10/05
     *
     * @static
     */
    public static function today()
    {
        return static::current();
    }

    /**
     * Answer tommorow's date.
     *
     * @return Date
     *
     * @since 5/10/05
     *
     * @static
     */
    public static function tomorrow()
    {
        $today = static::today();

        return $today->next();
    }

    /**
     * Create a new object starting on the julian day number specified.
     *
     * @return Date
     *
     * @since 5/10/05
     *
     * @static
     */
    public static function withJulianDayNumber(int $anInteger)
    {
        return static::starting(DateAndTime::withJulianDayNumber($anInteger));
    }

    /**
     * Create a new object starting on the year, month, and day of month specified.
     *
     * @return Date
     *
     * @since 5/10/05
     *
     * @static
     */
    public static function withYearMonthDay(int $anIntYear, int $anIntOrStringMonth, int $anIntDay)
    {
        return static::starting(DateAndTime::withYearMonthDay($anIntYear, $anIntOrStringMonth, $anIntDay));
    }

    /**
     * Create a new object starting on the year and day of year specified.
     *
     * @return Date
     *
     * @since 5/10/05
     *
     * @static
     */
    public static function withYearDay(int $anIntYear, int $anIntDay)
    {
        return static::starting(DateAndTime::withYearDay($anIntYear, $anIntDay));
    }

    /**
     * Answer yesterday's date.
     *
     * @return Date
     *
     * @since 5/10/05
     *
     * @static
     */
    public static function yesterday()
    {
        $today = static::today();

        return $today->previous();
    }

    /*********************************************************
     * Instance methods - Accessing/Printing
     *********************************************************/

    /**
     * Answer the receiver rendered in standard U.S.A format mm/dd/yyyy.
     *
     * @return string
     *
     * @since 5/23/05
     */
    public function mmddyyyyString()
    {
        return $this->printableStringWithFormat([2, 1, 3, '/', 1, 1, 2]);
    }

    /**
     * Format is '4 June 2005'.
     *
     * @return string
     *
     * @since 5/20/05
     */
    public function printableString(bool $printLeadingSpaceToo = false)
    {
        return $this->printableStringWithFormat([1, 2, 3, ' ', 3, 1]);
    }

    /**
     * Print a description of the receiver on aStream using the format
     * denoted the argument, formatArray:
     *
     *		array(item, item, item, sep, monthfmt, yearfmt, twoDigits)
     *
     *		items: 1=day 2=month 3=year will appear in the order given,
     *
     *		separated by sep which is eaither an ascii code or character.
     *
     *		monthFmt: 1=09 2=Sep 3=September
     *
     *		yearFmt: 1=1996 2=96
     *
     *		digits: (missing or)1=9 2=09.
     *
     *	See the examples in printOn: and mmddyy
     *
     * @return string
     *
     * @since 5/20/05
     */
    public function printableStringWithFormat(array $formatArray)
    {
        $result = '';
        $twoDigits = (count($formatArray) > 6 && $formatArray[6] > 1);
        $monthFormat = $formatArray[4];
        $yearFormat = $formatArray[5];
        $separator = $formatArray[3];

        for ($i = 0; $i < 3; ++$i) {
            $element = $formatArray[$i];

            switch ($element) {
                case 1:
                    if ($twoDigits) {
                        $result .= str_pad($this->dayOfMonth(), 2, '0', \STR_PAD_LEFT);
                    } else {
                        $result .= $this->dayOfMonth();
                    }
                    break;

                case 2:
                    if (1 == $monthFormat) {
                        if ($twoDigits) {
                            $result .= str_pad($this->startMonth(), 2, '0', \STR_PAD_LEFT);
                        } else {
                            $result .= $this->startMonth();
                        }
                    } elseif (2 == $monthFormat) {
                        $result .= substr(Month::nameOfMonth($this->startMonth()), 0, 3);
                    } elseif (3 == $monthFormat) {
                        $result .= Month::nameOfMonth($this->startMonth());
                    }
                    break;

                case 3:
                    if (2 == $yearFormat) {
                        $result .= str_pad($this->startYear() % 100, 2, '0', \STR_PAD_LEFT);
                    } else {
                        $result .= $this->startYear();
                    }
            }

            if ($i < 2 && $separator) {
                $result .= $separator;
            }
        }

        return $result;
    }

    /**
     * Format the date in ISO 8601 standard like '2002-10-22'.
     *
     * @return string
     *
     * @since 5/23/05
     */
    public function yyyymmddString()
    {
        return $this->printableStringWithFormat([3, 2, 1, '-', 1, 1, 2]);
    }

    /*********************************************************
     * Instance Methods - Operations
     *********************************************************/

    /**
     * Answer the date that occurs $anInteger days from this date.
     *
     * @return Date
     *
     * @since 5/20/05
     */
    public function addDays(int $anInteger)
    {
        $asDateAndTime = $this->asDateAndTime();
        $newDateAndTime = $asDateAndTime->plus(Duration::withDays($anInteger));
        $obj = $newDateAndTime->asDate();

        return $obj;
    }

    /**
     *  Answer the date that occurs $anInteger days before this date.
     *
     * @return Date
     *
     * @since 5/23/05
     */
    public function subtractDays(int $anInteger)
    {
        $obj = $this->addDays(0 - $anInteger);

        return $obj;
    }

    /**
     * Answer the previous date whose weekday name is dayName.
     *
     * @return Date
     *
     * @since 5/23/05
     */
    public function previousDayNamed(string $dayNameString)
    {
        $days = abs($this->dayOfWeek() - (Week::indexOfDay($dayNameString) % 7));
        if (0 == $days) {
            $days = 7;
        }
        $obj = $this->subtractDays($days);

        return $obj;
    }

    /*********************************************************
     * Instance Methods - Converting
     *********************************************************/

    /**
     * Answer the reciever as a Date.
     *
     * @return Date
     *
     * @since 5/23/05
     */
    public function asDate()
    {
        return $this;
    }

    /**
     * Answer the seconds since the Squeak epoch: 1 January 1901.
     *
     * @return int
     *
     * @since 5/23/05
     */
    public function asSeconds()
    {
        return $this->start->asSeconds();
    }
}
