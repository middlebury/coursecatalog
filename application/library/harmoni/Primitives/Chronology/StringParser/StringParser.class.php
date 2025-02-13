<?php

/**
 * @since 5/23/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: StringParser.class.php,v 1.8 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

require_once __DIR__.'/../../Objects/SObject.class.php';
require_once __DIR__.'/../Month.class.php';

/**
 * StringParser and its decendent classes form a Strategy pattern. They classes
 * that each implement a differnt method (strategy) for parsing strings into
 * dates and times.
 *
 * To try to parse a string using all (general) StringParsers use the
 * {@link getParserFor getParserFor($aString)} method to iterate through the
 * parsers until one is found that can handle the input:
 * <code>
 * 	$parser = StringParser::getParserFor($aString);
 *
 *	if (!$parser)
 *		die("'".$aString."' is not in a valid format.");
 *
 * 	$result = Date::withYearMonthDay($parser->year(), $parser->month(), $parser->day());
 * </code>
 *
 * To use StringParsers individually, use the canHandle($aString) method to find out if it is
 * appropriate to use this parse for a given string. If it is appropriate, create
 * a new StringParser with the given string and access its elements for the results:
 * <code>
 * 	$parser = new ANSI58216StringParser($aString);
 *
 *	if (!$parser)
 *		die("'".$aString."' is not in a valid format.");
 *
 * 	$result = Duration::withDaysHoursMinutesSeconds($parser->day(), $parser->hour(),
 *					$parser->minute(), $parser->second());
 * </code>
 *
 * To create new StringParsers, implement the canHandle() and parse() methods.
 *
 * @since 5/23/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: StringParser.class.php,v 1.8 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
abstract class StringParser extends SObject
{
    /*********************************************************
     * Class Methods
     *********************************************************/

    /**
     * Answer the parser that was able to successfully parse the input string
     * or FALSE if none could handle the input string.
     *
     * @return mixed object StringParser OR FALSE
     *
     * @since 5/24/05
     *
     * @static
     */
    public static function getParserFor(string $aString)
    {
        // Go through our parsers and try to find one that understands the format.
        $parserClasses = [
            'ISO8601StringParser',
            'ISO8601TimeStringParser',
            'DayMonthNameYearStringParser',
            'MonthNameDayYearStringParser',
            'MonthNumberDayYearStringParser',
            'KeywordStringParser',
            'TimeStringParser',
            'DateAndTimeStringParser',
            'DateAndISOTimeStringParser',
        ];

        $handled = false;
        while (!$handled && current($parserClasses)) {
            $parserClass = current($parserClasses);
            $parser = new $parserClass($aString);

            if ($parser->canHandle()) {
                $handled = true;
                break;
            } else {
                next($parserClasses);
            }
        }

        if ($handled && is_object($parser)) {
            return $parser;
        } else {
            $false = false;

            return $false;
        }
    }

    /*********************************************************
     * Instance Variables
     *********************************************************/

    /**
     * @var string; The input string
     *
     * @since 5/23/05
     */
    public $input;

    /**
     * @var int; The year found in the input
     *
     * @since 5/23/05
     */
    public $year;

    /**
     * @var int; The month found in the input
     *
     * @since 5/23/05
     */
    public $month;

    /**
     * @var int; The day found in the input
     *
     * @since 5/23/05
     */
    public $day;

    /**
     * @var int; The hour found in the input
     *
     * @since 5/23/05
     */
    public $hour;

    /**
     * @var int; The minute found in the input
     *
     * @since 5/23/05
     */
    public $minute;

    /**
     * @var int; The second found in the input
     *
     * @since 5/23/05
     */
    public $second;

    /**
     * @var int; The hour offset from UTC found in the input
     *
     * @since 5/23/05
     */
    public $offsetHour;

    /**
     * @var int; The minute offset from UTC found in the input
     *
     * @since 5/23/05
     */
    public $offsetMinute;

    /**
     * @var int; The second offset from UTC found in the input
     *
     * @since 5/23/05
     */
    public $offsetSecond;

    /*********************************************************
     * Instance Methods
     *********************************************************/

    /**
     * Create a new parser with the given input string.
     *
     * @return null
     *
     * @since 5/23/05
     */
    public function __construct(string $aString)
    {
        $this->input = $aString;

        if ($this->canHandle()) {
            $this->parse();
        }
    }

    /**
     * Answer True if this parser can handle the format of the string passed.
     *
     * @return bool
     *
     * @since 5/23/05
     */
    abstract public function canHandle();

    /**
     * Parse the input string and set our elements based on the contents of the
     * input string. Elements not found in the string will be null.
     *
     * @return void
     *
     * @since 5/23/05
     */
    abstract public function parse();

    /*********************************************************
     * Instance Methods - Accessing
     *********************************************************/

    /**
     * Answer the year or NULL.
     *
     * @return int
     *
     * @since 5/23/05
     */
    public function year()
    {
        return $this->year;
    }

    /**
     * Answer the month or NULL.
     *
     * @return int
     *
     * @since 5/23/05
     */
    public function month()
    {
        return $this->month;
    }

    /**
     * Answer the day or NULL.
     *
     * @return int
     *
     * @since 5/23/05
     */
    public function day()
    {
        return $this->day;
    }

    /**
     * Answer the hour or NULL.
     *
     * @return int
     *
     * @since 5/23/05
     */
    public function hour()
    {
        return $this->hour;
    }

    /**
     * Answer the minute or NULL.
     *
     * @return int
     *
     * @since 5/23/05
     */
    public function minute()
    {
        return $this->minute;
    }

    /**
     * Answer the second or NULL.
     *
     * @return int
     *
     * @since 5/23/05
     */
    public function second()
    {
        return $this->second;
    }

    /**
     * Answer the hour offset from UTC or NULL.
     *
     * @return int
     *
     * @since 5/23/05
     */
    public function offsetHour()
    {
        return $this->offsetHour;
    }

    /**
     * Answer the minute offset from UTC or NULL.
     *
     * @return int
     *
     * @since 5/23/05
     */
    public function offsetMinute()
    {
        return $this->offsetMinute;
    }

    /**
     * Answer the second offset from UTC or NULL.
     *
     * @return int
     *
     * @since 5/23/05
     */
    public function offsetSecond()
    {
        return $this->offsetSecond;
    }

    /*********************************************************
     * Instance Methods - Setting (private
     *********************************************************/

    /**
     * Set the year.
     *
     * @return void
     *
     * @since 5/23/05
     */
    public function setYear(int $anInteger)
    {
        $this->year = (int) $anInteger;
    }

    /**
     * Set the month.
     *
     * @return void
     *
     * @since 5/23/05
     */
    public function setMonth(int|string $anIntOrString)
    {
        if (!$anIntOrString) {
            $this->month = null;
        } elseif (is_numeric($anIntOrString)) {
            $this->month = (int) $anIntOrString;
        } else {
            $this->month = Month::indexOfMonth($anIntOrString);
        }
    }

    /**
     * Set the day.
     *
     * @return void
     *
     * @since 5/23/05
     */
    public function setDay(int $anInteger)
    {
        $this->day = (int) $anInteger;
    }

    /**
     * Set the hour.
     *
     * @return void
     *
     * @since 5/23/05
     */
    public function setHour(int $anInteger)
    {
        $this->hour = (int) $anInteger;
    }

    /**
     * Set the minute.
     *
     * @return void
     *
     * @since 5/23/05
     */
    public function setMinute(int $anInteger)
    {
        $this->minute = (int) $anInteger;
    }

    /**
     * Set the second.
     *
     * @return void
     *
     * @since 5/23/05
     */
    public function setSecond(int $anInteger)
    {
        $this->second = $anInteger;
    }

    /**
     * Set the hour offset from UTC.
     *
     * @return void
     *
     * @since 5/23/05
     */
    public function setOffsetHour(int $anInteger)
    {
        $this->offsetHour = (int) $anInteger;
    }

    /**
     * Set the minute offset from UTC.
     *
     * @return void
     *
     * @since 5/23/05
     */
    public function setOffsetMinute(int $anInteger)
    {
        $this->offsetMinute = (int) $anInteger;
    }

    /**
     * Set the second offset from UTC.
     *
     * @return void
     *
     * @since 5/23/05
     */
    public function setOffsetSecond(int $anInteger)
    {
        $this->offsetSecond = $anInteger;
    }
}

require_once __DIR__.'/RegexStringParser.class.php';
require_once __DIR__.'/TwoDigitYearStringParser.class.php';

require_once __DIR__.'/ANSI58216StringParser.class.php';
require_once __DIR__.'/ISO8601StringParser.class.php';
require_once __DIR__.'/ISO8601TimeStringParser.class.php';
require_once __DIR__.'/DayMonthNameYearStringParser.class.php';
require_once __DIR__.'/MonthNameDayYearStringParser.class.php';
require_once __DIR__.'/MonthNumberDayYearStringParser.class.php';
require_once __DIR__.'/KeywordStringParser.class.php';
require_once __DIR__.'/TimeStringParser.class.php';
require_once __DIR__.'/DateAndTimeStringParser.class.php';
require_once __DIR__.'/DateAndISOTimeStringParser.class.php';
