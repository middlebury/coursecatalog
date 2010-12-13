<?php
/**
 * @since 5/2/05
 * @package harmoni.primitives.chronology
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: DateAndTime.class.php,v 1.7 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */ 

require_once(dirname(__FILE__)."/../Magnitudes/Magnitude.class.php");


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
 * @package harmoni.primitives.chronology
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: DateAndTime.class.php,v 1.7 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class DateAndTime 
	extends Magnitude
{

/*********************************************************
 * Instance Variables
 *********************************************************/

	/**
	 * @var integer $jdn; JulianDateNumber 
	 * @access private
	 * @since 5/11/05
	 */
	var $jdn;
	
	/**
	 * @var integer $seconds; Seconds this day 
	 * @access private
	 * @since 5/11/05
	 */
	var $seconds;
	
	/**
	 * @var object Duration $offset; The offset from UTC 
	 * @access private
	 * @since 5/11/05
	 */
	var $offset;

/*********************************************************
 * Class Methods
 *********************************************************/
	
	/**
	 * One second precision.
	 * 
	 * @return object Duration
	 * @access public
	 * @since 5/12/05
	 * @static
	 */
	static function clockPrecision () {
		$obj = Duration::withSeconds(1);
		return $obj;
	}
	
	/**
	 * Answer the duration we are offset from UTC
	 * 
	 * @return object Duration
	 * @access public
 	 * @static
	 * @since 5/3/05
	 */
	static function localOffset () {
		$timeZone = DateAndTime::localTimeZone();
		return $timeZone->offset();
	}
	
	/**
	 * Answer the local TimeZone
	 * 
	 * @return object Duration
	 * @access public
 	 * @static
	 * @since 5/3/05
	 */
	static function localTimeZone () {
		$tzAbbreviation = date('T');
		$tzOffset = date('Z');
		if ($tzAbbreviation && $tzOffset)
			$obj = TimeZone::offsetNameAbbreviation(
						Duration::withSeconds($tzOffset),
						$tzAbbreviation,
						$tzAbbreviation);
		else
			$obj = TimeZone::defaultTimeZone();
		
		return $obj;
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
	 * Answer a new instance representing the Squeak epoch: 1 January 1901
	 * 
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object DateAndTime
	 * @access public
	 * @since 5/2/05
	 * @static
	 */
	static function epoch ( $class = 'DateAndTime' ) {
		eval('$result = '.$class.'::withJulianDayNumber(
					ChronologyConstants::SqueakEpoch(), 
					$class
				);');
		return $result;
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
	 * @param string $aString The input string.
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object DateAndTime
	 * @access public
	 * @since 5/12/05
	 * @static
	 */
	static function fromString ( $aString, $class = 'DateAndTime' ) {
		$parser = StringParser::getParserFor($aString);

		if (!is_string($aString) || !preg_match('/[^\W]/', $aString) || !$parser) {
 			$null = null;
 			return $null;
			// die("'".$aString."' is not in a valid format.");
		}
		
		if (!is_null($parser->offsetHour()))
			eval('$result = '.$class.'::withYearMonthDayHourMinuteSecondOffset(
				$parser->year(), $parser->month(), $parser->day(), $parser->hour(),
				$parser->minute(), $parser->second(), 
				Duration::withDaysHoursMinutesSeconds(0, $parser->offsetHour(),
				$parser->offsetMinute(), $parser->offsetSecond()), $class);');
		else if (!is_null($parser->hour()))
			eval('$result = '.$class.'::withYearMonthDayHourMinuteSecond(
				$parser->year(), $parser->month(), $parser->day(), $parser->hour(),
				$parser->minute(), $parser->second(), $class);');
		else
			eval('$result = '.$class.'::withYearMonthDay(
				$parser->year(), $parser->month(), $parser->day(), $class);');
		
		return $result;
	}
	
	/**
	 * Answer a new instance starting at midnight local time.
	 * 
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object DateAndTime
	 * @access public
	 * @since 5/3/05
	 * @static
	 */
	static function midnight ( $class = 'DateAndTime' ) {
		eval('$result = '.$class.'::now("'.$class.'");');
		$obj =$result->atMidnight();
		return $obj;
	}
	
	/**
	 * Answer a new instance starting at noon local time.
	 * 
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object DateAndTime
	 * @access public
	 * @since 5/3/05
	 * @static
	 */
	static function noon ( $class = 'DateAndTime' ) {
		eval('$result = '.$class.'::now('.$class.');');
		$obj =$result->atNoon();
		return $obj;
	}
	
	/**
	 * Answer the current date and time.
	 * 
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object DateAndTime
	 * @access public
	 * @since 5/12/05
	 * @static
	 */
	static function now ( $class = 'DateAndTime' ) {
		eval('$result = '.$class.'::withYearMonthDayHourMinuteSecondOffset(
				'.intval(date('Y')).',
				'.intval(date('n')).',
				'.intval(date('j')).',
				'.intval(date('G')).',
				'.intval(date('i')).',
				'.intval(date('s')).',
				$null = NULL,
				$class
			);');
		
		return $result;
	}
	
	/**
	 * Answer a new instance representing today
	 * 
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object DateAndTime
	 * @access public
	 * @since 5/12/05
	 * @static
	 */
	static function today ( $class = 'DateAndTime' ) {
		eval('$result = '.$class.'::midnight($class);');
		
		return $result;
	}
	
	/**
	 * Answer a new instance representing tomorow
	 * 
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object DateAndTime
	 * @access public
	 * @since 5/12/05
	 * @static
	 */
	static function tomorrow ( $class = 'DateAndTime' ) {
		eval('$today = '.$class.'::today($class);');
		$todaysDate =$today->asDate();
		$tomorowsDate =$todaysDate->next();
		$obj =$tomorowsDate->asDateAndTime();
		return $obj;
	}
	
	/**
	 * Create a new instance from Date and Time objects
	 * 
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object DateAndTime
	 * @access public
	 * @since 5/12/05
	 * @static
	 */
	static function withDateAndTime ( $aDate, $aTime, $class = 'DateAndTime' ) {
		eval('$result = '.$class.'::withYearDayHourMinuteSecond(
				$aDate->startYear(),
				$aDate->dayOfYear(),
				$aTime->hour(),
				$aTime->minute(),
				$aTime->second(),
				$class
			);');
		
		return $result;
	}
	
	/**
	 * Create a new new instance for a given Julian Day Number.
	 * 
	 * @param integer $aJulianDayNumber
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object DateAndTime
	 * @access public
	 * @since 5/2/05
	 * @static
	 */
	static function withJulianDayNumber ( $aJulianDayNumber, $class = 'DateAndTime' ) {
		
		// Validate our passed class name.
		if (!(strtolower($class) == strtolower('DateAndTime')
			|| is_subclass_of(new $class, 'DateAndTime')))
		{
			die("Class, '$class', is not a subclass of 'DateAndTime'.");
		}
		
		$days = Duration::withDays($aJulianDayNumber);
		
		$dateAndTime = new $class;
		$dateAndTime->ticksOffset($days->ticks(), DateAndTime::localOffset());
		return $dateAndTime;
	}
	
	/**
	 * Create a new instance.
	 * 
	 * @param integer $anIntYear
	 * @param integer $anIntDayOfYear
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @access public
 	 * @static
	 * @since 5/4/05
	 */
	static function withYearDay ( $anIntYear, $anIntDayOfYear, $class = 'DateAndTime') {
		eval('$result = '.$class.'::withYearDayHourMinuteSecond(
				$anIntYear,
				$anIntDayOfYear, 
				0, 
				0, 
				0,
				$class
			);');
		return $result;
	}
	
	/**
	 * Create a new instance.
	 * 
	 * @param integer $anIntYear
	 * @param integer $anIntDayOfYear
	 * @param integer $anIntHour
	 * @param integer $anIntMinute
	 * @param integer $anIntSecond
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object DateAndTime
	 * @access public
 	 * @static
	 * @since 5/4/05
	 */
	static function withYearDayHourMinuteSecond ( $anIntYear, $anIntDayOfYear, 
		$anIntHour, $anIntMinute, $anIntSecond, $class = 'DateAndTime' ) 
	{
		eval('$return = '.$class.'::withYearDayHourMinuteSecondOffset(
				$anIntYear,
				$anIntDayOfYear, 
				$anIntHour, 
				$anIntMinute, 
				$anIntSecond, 
				'.$class.'::localOffset(),
				$class
			);');
		return $return;
	}
	
	/**
	 * Create a new instance.
	 * 
	 * @param integer $anIntYear
	 * @param integer $anIntDayOfYear
	 * @param integer $anIntHour
	 * @param integer $anIntMinute
	 * @param integer $anIntSecond
	 * @param object Duration $aDurationOffset
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object DateAndTime
	 * @access public
 	 * @static
	 * @since 5/4/05
	 */
	static function withYearDayHourMinuteSecondOffset ( $anIntYear, $anIntDayOfYear, 
		$anIntHour, $anIntMinute, $anIntSecond, $aDurationOffset, $class = 'DateAndTime' ) 
	{
		eval('$result = '.$class.'::withYearMonthDayHourMinuteSecondOffset(
				$anIntYear,
				1, 
				1, 
				$anIntHour, 
				$anIntMinute, 
				$anIntSecond,
				$aDurationOffset,
				$class
			);');
		if ($anIntDayOfYear <= 1)
			$day = Duration::withDays(0);
		else
			$day = Duration::withDays($anIntDayOfYear - 1);
		$obj =$result->plus($day);
		return $obj;
	}
	
	/**
	 * Create a new instance.
	 * 
	 * @param integer $anIntYear
	 * @param integer $anIntOrStringMonth
	 * @param integer $anIntDay
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @access public
 	 * @static
	 * @since 5/4/05
	 */
	static function withYearMonthDay ( $anIntYear, $anIntOrStringMonth, $anIntDay, 
		$class = 'DateAndTime' ) 
	{
		eval('$result = '.$class.'::withYearMonthDayHourMinuteSecondOffset(
				$anIntYear,
				$anIntOrStringMonth, 
				$anIntDay, 
				0, 
				0, 
				0,
				$null = NULL,
				$class
			);');
		
		return $result;
	}
	
	/**
	 * Create a new instance.
	 * 
	 * @param integer $anIntYear
	 * @param integer $anIntOrStringMonth
	 * @param integer $anIntDay
	 * @param integer $anIntHour
	 * @param integer $anIntMinute
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object DateAndTime
	 * @access public
 	 * @static
	 * @since 5/4/05
	 */
	static function withYearMonthDayHourMinute ( $anIntYear, $anIntOrStringMonth, 
		$anIntDay, $anIntHour, $anIntMinute, $class = 'DateAndTime' ) 
	{
		eval('$result = '.$class.'::withYearMonthDayHourMinuteSecondOffset(
				$anIntYear,
				$anIntOrStringMonth, 
				$anIntDay, 
				$anIntHour, 
				$anIntMinute, 
				0,
				$null = NULL,
				$class
			);');
		
		return $result;
	}
	
	/**
	 * Create a new instance.
	 * 
	 * @param integer $anIntYear
	 * @param integer $anIntOrStringMonth
	 * @param integer $anIntDay
	 * @param integer $anIntHour
	 * @param integer $anIntMinute
	 * @param integer $anIntSecond
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object DateAndTime
	 * @access public
 	 * @static
	 * @since 5/4/05
	 */
	static function withYearMonthDayHourMinuteSecond ( $anIntYear, $anIntOrStringMonth, 
		$anIntDay, $anIntHour, $anIntMinute, $anIntSecond, $class = 'DateAndTime' ) 
	{
		eval('$result = '.$class.'::withYearMonthDayHourMinuteSecondOffset(
				$anIntYear,
				$anIntOrStringMonth, 
				$anIntDay, 
				$anIntHour, 
				$anIntMinute, 
				$anIntSecond,
				$null = NULL,
				$class
			);');
		
		return $result;
	}
	
	/**
	 * Create a new instance.
	 * 
	 * @param integer $anIntYear
	 * @param integer $anIntOrStringMonth
	 * @param integer $anIntDay
	 * @param integer $anIntHour
	 * @param integer $anIntMinute
	 * @param integer $anIntSecond
	 * @param object Duration $aDurationOffset
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object DateAndTime
	 * @access public
 	 * @static
	 * @since 5/4/05
	 */
	static function withYearMonthDayHourMinuteSecondOffset ( $anIntYear, 
		$anIntOrStringMonth, $anIntDay, $anIntHour, $anIntMinute, 
		$anIntSecond, $aDurationOffset, $class = 'DateAndTime'  ) 
	{
		// Validate our passed class name.
		if (!(strtolower($class) == strtolower('DateAndTime')
			|| is_subclass_of(new $class, 'DateAndTime')))
		{
			die("Class, '$class', is not a subclass of 'DateAndTime'.");
		}
		
		// Ensure that we have no days less than 1.
		if ($anIntDay < 1)
			$anIntDay = 1;
		
		
		if (is_numeric($anIntOrStringMonth))
			$monthIndex = $anIntOrStringMonth;
		else
			$monthIndex = Month::indexOfMonth($anIntOrStringMonth);
		
		$p = intval(($monthIndex - 14) / 12);
		$q = $anIntYear + 4800 + $p;
		$r = $monthIndex - 2 - (12 * $p);
		$s = intval(($anIntYear + 4900 + $p) / 100);
		
		$julianDayNumber = 		intval((1461 * $q) / 4)
							+ 	intval((367 * $r) / 12)
							-	intval((3 * $s) / 4)
							+	($anIntDay - 32075);		
		
		$since = Duration::withDaysHoursMinutesSeconds($julianDayNumber,
				$anIntHour, $anIntMinute, $anIntSecond);

		if (is_null($aDurationOffset))
			$offset = DateAndTime::localOffset();
		else
			$offset =$aDurationOffset;
		
		$dateAndTime = new $class;
		$dateAndTime->ticksOffset($since->ticks(), $offset);
		return $dateAndTime;
	}
	
	/**
	 * Answer a new instance representing yesterday
	 * 
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object DateAndTime
	 * @access public
	 * @since 5/12/05
	 * @static
	 */
	static function yesterday ( $class = 'DateAndTime' ) {
		eval('$today = '.$class.'::today($class);');
		$todaysDate =$today->asDate();
		$yesterdaysDate =$todaysDate->previous();
		$obj =$yesterdaysDate->asDateAndTime();
		return $obj;
	}
	
	
/*********************************************************
 * 	Instance Methods - Private
 *********************************************************/
	
	/**
	 * Initialize this DateAndTime.
	 * ticks is {julianDayNumber. secondCount. nanoSeconds}
	 * 
	 * @param array $ticks
	 * @param object Duration $utcOffset
	 * @return void
	 * @access private
	 * @since 5/2/05
	 */
	function ticksOffset ( $ticks, $utcOffset ) {
//		$this->_normalize($ticks, 2, ChronologyConstants::NanosInSecond());
		$this->_normalize($ticks, 1, ChronologyConstants::SecondsInDay());
		
		$this->jdn = $ticks[0];
		$this->seconds = $ticks[1];
//		$this->nanos = $ticks[2];
		$this->offset =$utcOffset;
	}
	
	/**
	 * Normalize tick values to make things like "2 days, 35 hours" into
	 * "3 days, 9 hours".
	 * 
	 * @param ref array $ticks
	 * @param integer $i The index of the array to normalize.
	 * @param integer $base The base to normalize to.
	 * @return void
	 * @access private
	 * @since 5/3/05
	 */
	function _normalize (&$ticks, $i, $base) {
		$tick = $ticks[$i];
		$quo = floor(abs($tick)/$base);
		$rem = $tick % $base;
		if ($rem < 0) {
			$quo = $quo-1;
			$rem = $base + $rem;
		}
		$ticks[$i-1] = $ticks[$i-1]+$quo;
		$ticks[$i] = $rem;
	}
	
	/**
	 * Private - answer an array with our instance variables. Assumed to be UTC
	 * 
	 * @return array
	 * @access private
	 * @since 5/4/05
	 */
	function ticks () {
		return array ($this->jdn, $this->seconds);
	}
	
/*********************************************************
 * Instance Methods - Accessing
 *********************************************************/
 	
	/**
	 * Answer the date and time at midnight on the day of the receiver.
	 * 
	 * @return object DateAndTime
	 * @access public
	 * @since 5/25/05
	 */
	function atMidnight () {
		eval('$result = '.get_class($this).'::withYearMonthDay($this->year(),
				$this->month(), $this->dayOfMonth(), "'.get_class($this).'");');
		return $result;
	}
	
	/**
	 * Answer noon on the day of the reciever
	 * 
	 * @return object DateAndTime
	 * @access public
	 * @since 5/25/05
	 */
	function atNoon () {
		eval('$result = '.get_class($this).'::withYearMonthDayHourMinuteSecond(
			$this->year(), $this->month(), $this->dayOfMonth(), 12, 0, 0, 
			'.get_class($this).');');
		return $result;
	}
	
	/**
	 * Answer the day
	 * 
	 * @return integer
	 * @access public
	 * @since 5/3/05
	 */
	function day () {
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
	 * @access public
	 * @since 5/3/05
	 */
	function dayMonthYearArray () {
		$l = $this->jdn + 68569;
		$n = floor((4 * $l) / 146097);
		$l = $l - floor(((146097 * $n) + 3) / 4);
		$i = floor((4000 * ($l + 1)) / 1461001);
		$l = ($l - floor((1461 * $i) / 4)) + 31;
		$j = floor((80 * $l) / 2447);
		$dd = $l - (floor((2447 * $j) / 80));
		$l = floor($j / 11);
		$mm = $j + 2 - (12 * $l);
		$yyyy = (100 * ($n - 49)) + $i + $l;
		return array('dd' => $dd, 'mm' => $mm, 'yyyy' => $yyyy);
	}
	
	/**
	 * Answer the day of the month
	 * 
	 * @return integer
	 * @access public
	 * @since 5/3/05
	 */
	function dayOfMonth () {
		$array = $this->dayMonthYearArray();
		return $array['dd'];
	}
	
	/**
	 * Answer the day of the week
	 * 
	 * @return integer
	 * @access public
	 * @since 5/3/05
	 */
	function dayOfWeek () {
		$x = $this->jdn + 1;
		return ($x - (intval($x / 7) * 7)) + 1;
	}
	
	/**
	 * Answer the day of the week abbreviation
	 * 
	 * @return string
	 * @access public
	 * @since 5/3/05
	 */
	function dayOfWeekAbbreviation () {
		return substr($this->dayOfWeekName(), 0, 3);
	}
	
	/**
	 * Answer the day of the week name
	 * 
	 * @return string
	 * @access public
	 * @since 5/3/05
	 */
	function dayOfWeekName () {
		return Week::nameOfDay($this->dayOfWeek());
	}
	
	/**
	 * Answer the day of the year
	 * 
	 * @return integer
	 * @access public
	 * @since 5/3/05
	 */
	function dayOfYear () {
		$thisYear = Year::withYear($this->year());
		$start =$thisYear->start();
		return ($this->jdn - $start->julianDayNumber() + 1);
	}
	
	/**
	 * Answer the number of days in the month represented by the receiver.
	 * 
	 * @return ingteger
	 * @access public
	 * @since 5/5/05
	 */
	function daysInMonth () {
		$month =$this->asMonth();
		return $month->daysInMonth();
	}
	
	/**
	 * Answer the number of days in the year represented by the receiver.
	 * 
	 * @return ingteger
	 * @access public
	 * @since 5/5/05
	 */
	function daysInYear () {
		$year =$this->asYear();
		return $year->daysInYear();
	}
	
	/**
	 * Answer the number of days in the year after the date of the receiver.
	 * 
	 * @return ingteger
	 * @access public
	 * @since 5/5/05
	 */
	function daysLeftInYear () {
		return $this->daysInYear() - $this->dayOfYear();
	}
	
	/**
	 * Answer the duration of this object (always zero)
	 * 
	 * @return object Duration
	 * @access public
	 * @since 5/5/05
	 */
	function duration () {
		$obj = Duration::zero();
		return $obj;
	}
	
	/**
	 * Answer the day-in-the-year of the first day of our month
	 * 
	 * @return integer
	 * @access public
	 * @since 5/5/05
	 */
	function firstDayOfMonth () {
		$month =$this->asMonth();
		$monthStart =$month->start();
		return $monthStart->day();
	}
	
	/**
	 * Answer just 'hh:mm:ss'. This is equivalent to Squeak's printHMSOn: method.
	 * 
	 * @return string
	 * @access public
	 * @since 5/10/05
	 */
	function hmsString () {
		$result = '';
		$result .= str_pad($this->hour(), 2, '0', STR_PAD_LEFT);
		$result .= ':';
		$result .= str_pad($this->minute(), 2, '0', STR_PAD_LEFT);
		$result .= ':';
		$result .= str_pad($this->second(), 2, '0', STR_PAD_LEFT);
		return $result;
	}
	
	/**
	 * Answer the hours (0-23)
	 * 
	 * @return integer
	 * @access public
	 * @since 5/3/05
	 */
	function hour () {
		return $this->hour24();
	}
	
	/**
	 * Answer the hours (0-23)
	 * 
	 * @return integer
	 * @access public
	 * @since 5/3/05
	 */
	function hour24 () {
		$duration = Duration::withSeconds($this->seconds);
		return $duration->hours();
	}
	
	/**
	 * Answer an <integer> between 1 and 12, inclusive, representing the hour 
	 * of the day in the 12-hour clock of the local time of the receiver.
	 * 
	 * @return integer
	 * @access public
	 * @since 5/4/05
	 */
	function hour12 () {
		$x = ($this->hour24() - 1) % 12;
		if ($x < 0)
			$x = $x + 12;
		return $x + 1;
	}
	
	/**
	 * Return if this year is a leap year
	 * 
	 * @return boolean
	 * @access public
	 * @since 5/4/05
	 */
	function isLeapYear () {
		return Year::isYearLeapYear($this->year());
	}
	
	/**
	 * Return the JulianDayNumber of this DateAndTime
	 * 
	 * @return integer
	 * @access public
	 * @since 5/4/05
	 */
	function julianDayNumber () {
		return $this->jdn;
	}
	
	/**
	 * Return the Meridian Abbreviation ('AM'/'PM')
	 * 
	 * @return string
	 * @access public
	 * @since 5/5/05
	 */
	function meridianAbbreviation () {
		$time =$this->asTime();
		return $time->meridianAbbreviation();
	}
	
	/**
	 * Answer the miniute (0-59)
	 * 
	 * @return integer
	 * @access public
	 * @since 5/3/05
	 */
	function minute () {
		$duration = Duration::withSeconds($this->seconds);
		return $duration->minutes();
	}
	
	/**
	 * Answer the month
	 * 
	 * @return integer
	 * @access public
	 * @since 5/3/05
	 */
	function month () {
		$array = $this->dayMonthYearArray();
		return $array['mm'];
	}
	
	/**
	 * Answer the day of the week abbreviation
	 * 
	 * @return string
	 * @access public
	 * @since 5/3/05
	 */
	function monthAbbreviation () {
		return substr($this->monthName(), 0, 3);
	}
	
	/**
	 * Answer the index of the month.
	 * 
	 * @return integer
	 * @access public
	 * @since 5/3/05
	 */
	function monthIndex () {
		return $this->month();
	}
	
	/**
	 * Answer the name of the month.
	 * 
	 * @return string
	 * @access public
	 * @since 5/3/05
	 */
	function monthName () {
		return Month::nameOfMonth($this->month());
	}
	
	/**
	 * Answer the offset
	 * 
	 * @return object Duration
	 * @access public
	 * @since 5/3/05
	 */
	function offset () {
		return $this->offset;
	}
	
	/**
	 * Answer the second (0-59)
	 * 
	 * @return integer
	 * @access public
	 * @since 5/3/05
	 */
	function second () {
		$duration = Duration::withSeconds($this->seconds);
		return $duration->seconds();
	}
	
	/**
	 * Print as per ISO 8601 sections 5.3.3 and 5.4.1.
	 * If printLeadingSpaceToo is false, prints either:
	 *		'YYYY-MM-DDThh:mm:ss.s+ZZ:zz:z' (for positive years) 
	 *	or 
	 *		'-YYYY-MM-DDThh:mm:ss.s+ZZ:zz:z' (for negative years)
	 *
	 * If printLeadingSpaceToo is true, prints either:
	 * 		' YYYY-MM-DDThh:mm:ss.s+ZZ:zz:z' (for positive years) 
	 *	or 
	 *		'-YYYY-MM-DDThh:mm:ss.s+ZZ:zz:z' (for negative years)
	 *
	 * This is equivalent to Squeak's printOn:withLeadingSpace: method.
	 * 
	 * @return string
	 * @access public
	 * @since 5/10/05
	 */
	function printableString ( $printLeadingSpaceToo = FALSE ) {
		$result = $this->ymdString($printLeadingSpaceToo);
		$result .= 'T';
		$result .= $this->hmsString();
		
		if ($this->offset->isPositive())
			$result .= '+';
		else
			$result .= '-';
		
		$result .= str_pad(abs($this->offset->hours()), 2, '0', STR_PAD_LEFT);
		$result .= ':';
		$result .= str_pad(abs($this->offset->minutes()), 2, '0', STR_PAD_LEFT);
		
		if ($this->offset->seconds() != 0) {
			$result .= ':';
			$result .= intval(abs($this->offset->minutes())/10);
		}
		
		return $result;
	}
	
	/**
	 * Answer the Time Zone that corresponds to our offset.
	 * 
	 * @return object TimeZone
	 * @access public
	 * @since 5/10/05
	 */
	function timeZone () {
		// Search through the array of timezones for one that matches. Otherwise,
		// build our own. The name and abbreviation are just a guess, as multiple
		// Time Zones have the same offset.
		$zoneArray = TimeZone::timeZones();
		foreach (array_keys($zoneArray) as $key) {
			if ($this->offset->isEqualTo($zoneArray[$key]->offset()))
				return $zoneArray[$key];
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
	 * @access public
	 * @since 5/10/05
	 */
	function timeZoneAbbreviation () {
		$timeZone =$this->timeZone();
		return $timeZone->abbreviation();
	}
	
	/**
	 * Answer the TimeZone name.
	 * 
	 * @return string
	 * @access public
	 * @since 5/10/05
	 */
	function timeZoneName () {
		$timeZone =$this->timeZone();
		return $timeZone->name();
	}
	
	/**
	 * Answer the year
	 * 
	 * @return integer
	 * @access public
	 * @since 5/3/05
	 */
	function year () {
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
	 * @access public
	 * @since 5/10/05
	 */
	function ymdString ( $printLeadingSpaceToo = FALSE ) {
		$year = $this->year();
		$month = $this->month();
		$day = $this->dayOfMonth();
		
		$result = '';
		
		if ($year < 0) {
			$result .= '-';
		} else {
			if ($printLeadingSpaceToo)
				$result .= ' ';
		}
		
		$result .= str_pad(abs($year), 4, '0', STR_PAD_LEFT);
		$result .= '-';
		$result .= str_pad($month, 2, '0', STR_PAD_LEFT);
		$result .= '-';
		$result .= str_pad($day, 2, '0', STR_PAD_LEFT);
		return $result;
	}

	/**
	 * Print just the month, day, and year on aStream.
	 *
	 * @return string
	 * @access public
	 * @since 5/10/05
	 */
	function mdyString () {
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
	 * See: http://us2.php.net/manual/en/function.date.php for details
	 * 
	 * @param string $format
	 * @return string
	 * @access public
	 * @since 11/21/08
	 */
	public function format ($format) {
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
	 * @param object $comparand
	 * @return boolean
	 * @access public
	 * @since 5/3/05
	 */
	function isEqualTo ( $comparand ) {
		if ($this === $comparand)
			return TRUE;

		if (!method_exists($comparand, 'asDateAndTime'))
			return FALSE;
		
		$comparandAsDateAndTime =$comparand->asDateAndTime();
		
		if ($this->offset->isEqualTo($comparandAsDateAndTime->offset())) {
			$myTicks = $this->ticks();
			$comparandTicks = $comparandAsDateAndTime->ticks();
		} else {
			$meAsUTC =$this->asUTC();
			$myTicks = $meAsUTC->ticks();
			$comparandAsUTC =$comparandAsDateAndTime->asUTC();
			$comparandTicks = $comparandAsUTC->ticks();
		}
		
		if ($myTicks[0] != $comparandTicks[0])
			return FALSE;
		else
			return ($myTicks[1] == $comparandTicks[1]);
	}
	
	/**
	 * comparand conforms to protocol DateAndTime,
	 * or can be converted into something that conforms.
	 * 
	 * @param object $comparand
	 * @return boolean
	 * @access public
	 * @since 5/3/05
	 */
	function isLessThan ( $comparand ) {
		$comparandAsDateAndTime =$comparand->asDateAndTime();
		
		if ($this->offset->isEqualTo($comparandAsDateAndTime->offset())) {
			$myTicks = $this->ticks();
			$comparandTicks = $comparandAsDateAndTime->ticks();
		} else {
			$meAsUTC =$this->asUTC();
			$myTicks = $meAsUTC->ticks();
			$comparandAsUTC =$comparandAsDateAndTime->asUTC();
			$comparandTicks = $comparandAsUTC->ticks();
		}
		
		if ($myTicks[0] < $comparandTicks[0])
			return TRUE;
		else
			return (($myTicks[0] == $comparandTicks[0]) 
				&& ($myTicks[1] < $comparandTicks[1]));
	}
	

/*********************************************************
 * Instance methods - Operations
 *********************************************************/
	
	/**
	 * Subtract a Duration or DateAndTime.
	 * 
	 * @param object $operand
	 * @return object
	 * @access public
	 * @since 5/3/05
	 */
	function minus ( $operand ) {
		$methods = get_class_methods($operand);
		
		// If this conforms to the DateAndTimeProtocal
		if (in_array('asdateandtime', $methods) 
			| in_array('asDateAndTime', $methods)) 
		{
			$meLocal =$this->asLocal();
			$lticks = $meLocal->ticks();
			$opDAndT =$operand->asDateAndTime();
			$opLocal =$opDAndT->asLocal();
			$rticks = $opLocal->ticks();
			
			$obj = Duration::withSeconds(
				(($lticks[0] - $rticks[0]) * ChronologyConstants::SecondsInDay())
				+ ($lticks[1] - $rticks[1]));
			
			return $obj;
			
		} 
		// If this conforms to the Duration protocal
		else {
			$obj =$this->plus($operand->negated());
			return $obj;
		}
	}
	
	
	/**
	 * Answer a new Duration whose our date + operand. The operand must implement
	 * asDuration().
	 * 
	 * @param object $operand
	 * @return object DateAndTime
	 * @access public
	 * @since 5/4/05
	 */
	function plus ( $operand ) {
		$ticks = array();
		$duration =$operand->asDuration();
		$durationTicks = $duration->ticks();
		
		foreach ($this->ticks() as $key => $value) {
			$ticks[$key] = $value + $durationTicks[$key];
		}
		
		$class = get_class($this);
		$result = new $class();
		$result->ticksOffset($ticks, $this->offset());
		return $result;
	}
	

/*********************************************************
 * Instance methods - Converting
 *********************************************************/
	
	/**
	 * Answer a Date that represents this object
	 * 
	 * @return object Date
	 * @access public
	 * @since 5/5/05
	 */
	function asDate () {
		$obj = Date::starting($this);
		return $obj;
	}
	
	/**
	 * Answer a DateAndTime that represents this object
	 * 
	 * @return object DateAndTime
	 * @access public
	 * @since 5/4/05
	 */
	function asDateAndTime () {
		return $this;
	}
	
	/**
	 * Answer a Duration that represents this object, the duration since
	 * midnight.
	 * 
	 * @return object Duration
	 * @access public
	 * @since 5/4/05
	 */
	function asDuration () {
		$obj = Duration::withSeconds($this->seconds);
		return $obj;
	}
	
	/**
	 * Answer a DateAndTime that represents the object, but at local time.
	 * 
	 * @return object DateAndTime
	 * @access public
	 * @since 5/5/05
	 */
	function asLocal () {
		$myOffset =$this->offset();
		if ($myOffset->isEqualTo(DateAndTime::localOffset()))
			return $this;
		else {
			$obj =$this->utcOffset(DateAndTime::localOffset());
			return $obj;
		}
	}
	
	/**
	 * Answer the month that represents this date's month
	 * 
	 * @return object Month
	 * @access public
	 * @since 5/5/05
	 */
	function asMonth () {
		$obj = Month::starting($this);
		return $obj;
	}
	
	/**
	 * Return the number of seconds since the Squeak epoch.
	 * 
	 * @return integer
	 * @access public
	 * @since 5/5/05
	 */
	function asSeconds () {
		eval('$epoch = '.get_class($this).'::epoch();');
		$sinceEpoch =$this->minus($epoch);
		return $sinceEpoch->asSeconds();
	}
	
	/**
	 * Answer a Time that represents our time component
	 * 
	 * @return object Time
	 * @access public
	 * @since 5/5/05
	 */
	function asTime () {
		$obj = Time::withSeconds($this->seconds);
		return $obj;
	}
	
	/**
	 * Answer a Timestamp that represents this DateAndTime
	 * 
	 * @return object TimeStamp
	 * @access public
	 * @since 5/5/05
	 */
	function asTimeStamp () {
		$obj =$this->asA('TimeStamp');
		return $obj;
	}
	
	/**
	 * Answer a PHP build-in DateTime object (PHP > 5.2) with our values.
	 * 
	 * @return object DateTime
	 * @access public
	 * @since 11/21/08
	 */
	public function asDateTime () {
		$result = $this->ymdString(false);
		$result .= 'T';
		$result .= $this->hmsString();
		
		if ($this->offset->isPositive())
			$result .= '+';
		else
			$result .= '-';
		
		$result .= str_pad(abs($this->offset->hours()), 2, '0', STR_PAD_LEFT);
		$result .= ':';
		$result .= str_pad(abs($this->offset->minutes()), 2, '0', STR_PAD_LEFT);
		
		$resultWithTZ = $result;
		if ($this->offset->seconds() != 0) {
			$resultWithTZ .= ':';
			$resultWithTZ .= intval(abs($this->offset->minutes())/10);
		}
		
		$dateTime = new DateTime($resultWithTZ);
		
		// If our timezone abbrieviation has the same value as the offset, use it.
		$tzone = new DateTimeZone($this->timeZoneAbbreviation());
 		if ($tzone !== false && $tzone->getOffset($dateTime) == $this->offset->asSeconds()) {
//  			printpre('setting timezone');
 			$dateTime->setTimezone($tzone);
 		}
		
		return $dateTime;
	}
	
	/**
	 * Answer a DateAndTime equivalent to the reciever, but at UTC (offset = 0)
	 * 
	 * @return object DateAndTime
	 * @access public
	 * @since 5/4/05
	 */
	function asUTC () {
		$obj =$this->utcOffset(Duration::withHours(0));
		return $obj;
	}
	
	/**
	 * Answer the week that represents this date's week
	 * 
	 * @return object Week
	 * @access public
	 * @since 5/5/05
	 */
	function asWeek () {
		$obj = Week::starting($this);
		return $obj;
	}
	
	/**
	 * Answer the year that represents this date's year
	 * 
	 * @return object Year
	 * @access public
	 * @since 5/5/05
	 */
	function asYear () {
		$obj = Year::starting($this);
		return $obj;
	}
	
	/**
	 * Return a Timespan where the receiver is the middle of the Duration
	 * 
	 * @param object Duration $aDuration
	 * @return object Timespan
	 * @access public
	 * @since 5/12/05
	 */
	function middleOf ( $aDuration ) {
		$duration =$aDuration->asDuration();
		
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
	 * @param object Duration $aDuration
	 * @return object DateAndTime
	 * @access public
	 * @since 5/4/05
	 */
	function withOffset ( $anOffset ) {
		$class = get_class($this);
		$equiv = new $class;
		$equiv->ticksOffset($this->ticks(), $anOffset->asDuration());
		return $equiv;
	}
	
	/**
	 * Answer a Timespan. anEnd conforms to protocol DateAndTime or protocol Timespan
	 * 
	 * @param object DateAndTime $anEnd
	 * @return object Timespan
	 * @access public
	 * @since 5/12/05
	 */
	function to ( $anEnd ) {
		$obj = Timespan::startingEnding($this, $anEnd->asDateAndTime());
		return $obj;
	}
	
	/**
	 * Answer a Timespan. anEnd conforms to protocol DateAndTime or protocol Timespan
	 * 
	 * @param object DateAndTime $anEnd
	 * @param object Duration
	 * @return object Schedule
	 * @access public
	 * @since 5/12/05
	 */
	function toBy ( $anEnd, $aDuration ) {
		$schedule = Schedule::startingEnding($this, $anEnd->asDateAndTime());
		$schedule->addToSchedule(array($aDuration->asDuration()));
		return $schedule;
	}
	
	/**
	 * Answer a <DateAndTime> equivalent to the receiver but offset from UTC by 
	 * aDuration. This will convert the recievers time, to the time at anOffset;
	 * i.e. 11am at UTC-05:00 would become 9am at UTC-7:00 when -7 hours is passed
	 * as the offset.
	 * 
	 * @param object Duration $aDuration
	 * @return object DateAndTime
	 * @access public
	 * @since 5/4/05
	 */
	function utcOffset ( $anOffset ) {
		$duration =$anOffset->asDuration();
		$equiv =$this->plus($duration->minus($this->offset()));
		$equiv->ticksOffset($equiv->ticks(), $duration);
		return $equiv;
	}
}

require_once(dirname(__FILE__)."/ChronologyConstants.class.php");
require_once(dirname(__FILE__)."/Date.class.php");
require_once(dirname(__FILE__)."/Duration.class.php");
require_once(dirname(__FILE__)."/Month.class.php");
require_once(dirname(__FILE__)."/Time.class.php");
require_once(dirname(__FILE__)."/TimeStamp.class.php");
require_once(dirname(__FILE__)."/TimeZone.class.php");
require_once(dirname(__FILE__)."/Week.class.php");
require_once(dirname(__FILE__)."/Year.class.php");

?>