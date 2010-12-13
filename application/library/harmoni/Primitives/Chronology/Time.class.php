<?php
/**
 * @since 5/5/05
 * @package harmoni.primitives.chronology
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Time.class.php,v 1.8 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */ 

require_once(dirname(__FILE__)."/ChronologyConstants.class.php");
require_once(dirname(__FILE__)."/../Magnitudes/Magnitude.class.php");
require_once(dirname(__FILE__)."/Month.class.php");
require_once(dirname(__FILE__)."/TimeZone.class.php");
require_once(dirname(__FILE__)."/Week.class.php");
require_once(dirname(__FILE__)."/Year.class.php");

/**
 * This represents a period of time.
 *
 * My implementation uses one SmallIntegers:
 * seconds	- number of seconds since midnight.
 *
 * To create new Time instances, <b>use one of the static instance-creation 
 * methods</b>, NOT 'new Time':
 *		- {@link fromString Time::fromString($aString)}
 *		- {@link fromString Time::fromString($aString)}
 *		- {@link midnight Time::midnight()}
 *		- {@link noon Time::noon()}
 *		- {@link withHourMinuteSecond Time::withHourMinuteSecond($anIntHour, $anIntMinute, 
 *						$anIntSecond)}
 *		- {@link withSeconds Time::withSeconds($anIntSeconds)}
 * 
 * @since 5/5/05
 * @package harmoni.primitives.chronology
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Time.class.php,v 1.8 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class Time 
	extends Magnitude
{

	/**
	 * @var integer $seconds; The seconds from midnight of this time 
	 * @access private
	 * @since 5/11/05
	 */
	var $seconds;

/*********************************************************
 * Class Methods - Instance Creation
 *********************************************************/
	
	/**
	 * Read a Time from the stream in the forms:
	 *		- <hour24>:<minute>:<second>
	 *		- <hour>:<minute>:<second> <am/pm>
	 *		- <minute>, <second> or <am/pm> may be omitted.  e.g. 1:59:30 pm; 8AM; 15:30
	 * 
	 * @param string $aString
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object Time
	 * @access public
	 * @static
	 * @since 5/24/05
	 */
	static function fromString ( $aString, $class = 'Time' ) {
		$parser = StringParser::getParserFor($aString);
		
		if (!is_string($aString) || !preg_match('/[^\W]/', $aString) || !$parser) {
 			$null = null;
 			return $null;
			// die("'".$aString."' is not in a valid format.");
		}
		
		eval('$result = '.$class.'::withHourMinuteSecond($parser->hour(),
						$parser->minute(), $parser->second(), $class);');
		return $result;
	}
	
	/**
	 * Answer the Time at midnight
	 * 
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object Time
	 * @access public
	 * @static
	 * @since 5/25/05
	 */
	static function midnight ( $class = 'Time' ) {
		eval('$result = '.$class.'::withSeconds(0, $class);');
		return $result;
	}
	
	/**
	 * Answer the Time at noon
	 * 
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object Time
	 * @access public
	 * @since 5/25/05
	 * @static
	 */
	static function noon ( $class = 'Time' ) {
		eval('$result = '.$class.'::withHourMinuteSecond(12, 0, 0, $class);');
		return $result;
	}
	
	/**
	 * Answer a Time from midnight
	 * 
	 * @param integer $anIntHour
	 * @param integer $anIntMinute
	 * @param integer $anIntSecond
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object Time
	 * @access public
 	 * @static
	 * @since 5/4/05
	 */
	static function withHourMinuteSecond ($anIntHour, $anIntMinute, $anIntSecond, $class = 'Time' ) 
	{
		eval('$result = '.$class.'::withSeconds(
							  ($anIntHour * ChronologyConstants::SecondsInHour())
							+ ($anIntMinute * ChronologyConstants::SecondsInMinute())
							+ $anIntSecond, $class);');
		return $result;
	}
	
	/**
	 * Answer a Time from midnight
	 * 
	 * @param integer $anIntSeconds
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object Time
	 * @access public
	 * @static
	 * @since 5/5/05
	 */
	static function withSeconds ( $anIntSeconds, $class = 'Time' ) {
		// Lop off any seconds beyond those in a day
		$duration = Duration::withSeconds($anIntSeconds);
		$ticks = $duration->ticks();
		$seconds = $ticks[1];
		
		// Make sure that we have a positive time since midnight
		if ($seconds < 0)
			$seconds = ChronologyConstants::SecondsInDay() + $seconds;
		
		// Validate our passed class name.
		if (!(strtolower($class) == strtolower('Time')
			|| is_subclass_of(new $class, 'Time')))
		{
			die("Class, '$class', is not a subclass of 'Time'.");
		}
		
		$time = new $class;
		$time->setSeconds($seconds);
		return $time;
	}
	
	
/*********************************************************
 * 	Instance Methods - Private
 *********************************************************/
	
	/**
	 * Set our seconds
	 * 
	 * @param ingteger $anIntSeconds
	 * @return void
	 * @access private
	 * @since 5/5/05
	 */
	function setSeconds ( $anIntSeconds ) {
		$this->seconds = $anIntSeconds;
	}
	
	/**
	 * Private - answer an array with our instance variables. Assumed to be UTC
	 * 
	 * @return array
	 * @access private
	 * @since 5/4/05
	 */
	function ticks () {
		return array (0, $this->seconds);
	}
	
/*********************************************************
 * Instance Methods - Accessing
 *********************************************************/
	
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
	 * Answer the hours (0-23)
	 * 
	 * @return integer
	 * @access public
	 * @since 5/3/05
	 */
	function hour24 () {
		$duration =$this->asDuration();
		return $duration->hours();
	}
		
	/**
	 * Return the Meridian Abbreviation ('AM'/'PM')
	 * 
	 * @return string
	 * @access public
	 * @since 5/5/05
	 */
	function meridianAbbreviation () {
		if ($this->hour() < 12)
			return 'AM';
		else
			return 'PM';
	}
	
	/**
	 * Answer the minute (0-59)
	 * 
	 * @return integer
	 * @access public
	 * @since 5/3/05
	 */
	function minute () {
		$asDuration =$this->asDuration();
		return $asDuration->minutes();
	}
	
	/**
	 * Format is 'h:mm:ss am'  or, if showSeconds is false, 'h:mm am'
	 * 
	 * @param optional boolean $showSeconds
	 * @return string
	 * @access public
	 * @since 5/20/05
	 */
	function string12 ( $showSeconds = TRUE ) {
		if ($this->hour() > 12)
			$result = $this->hour() - 12;
		else
			$result = $this->hour();
		
		if (!$result)
			$result = 12;
		
		$result .= ':';
		$result .= str_pad(abs($this->minute()), 2, '0', STR_PAD_LEFT);
		
		if ($showSeconds) {
			$result .= ':';
			$result .= str_pad(abs($this->second()), 2, '0', STR_PAD_LEFT);
		}
		
		if ($this->hour() >= 12)
			$result .= ' pm';
		else
			$result .= ' am';
		
		return $result;
	}
	
	/**
	 * Format is 'hh:mm:ss' or, if showSeconds is false, 'hh:mm'
	 * 
	 * @param optional boolean $showSeconds
	 * @return string
	 * @access public
	 * @since 5/20/05
	 */
	function string24 ( $showSeconds = TRUE ) {
		$result = str_pad(abs($this->hour()), 2, '0', STR_PAD_LEFT);
		$result .= ':';
		$result .= str_pad(abs($this->minute()), 2, '0', STR_PAD_LEFT);
		
		if ($showSeconds) {
			$result .= ':';
			$result .= str_pad(abs($this->second()), 2, '0', STR_PAD_LEFT);
		}
		
		return $result;
	}
	
	/**
	 * Format is 'h:mm<:ss> am'
	 * 
	 * @return string
	 * @access public
	 * @since 5/20/05
	 */
	function printableString () {
		return $this->string12(($this->second() != 0));
	}
	
	/**
	 * Answer the second (0-59)
	 * 
	 * @return integer
	 * @access public
	 * @since 5/3/05
	 */
	function second () {
		$asDuration =$this->asDuration();
		return $asDuration->seconds();
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

		if (!strtolower(get_class($comparand)) == 'time' 
			&& !is_subclass_of($comparand, 'Time'))
			return FALSE;
				
		$myTicks = $this->ticks();
		$comparandTicks = $comparand->ticks();
		
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
		$myDuration =$this->asDuration();
		return $myDuration->isLessThan($comparand->asDuration());
	}
	

/*********************************************************
 * Instance methods - Operations
 *********************************************************/
 	
 	/**
 	 * Answer a Time that is nSeconds after the receiver.
 	 * 
 	 * @param integer $anInteger
 	 * @return object Time
 	 * @access public
 	 * @since 5/25/05
 	 */
 	function addSeconds ( $anInteger ) {
 		eval('$result = '.get_class($this).'::withSeconds(
 				$this->asSeconds() + $anInteger);');
 		return $result;
 	}
 	
 	/**
 	 * Answer a Time that is timeInterval after the receiver. timeInterval is an 
	 * instance of Date or Time.
 	 * 
 	 * @param object $timeAmount An instance of Date or Time.
 	 * @return object Time
 	 * @access public
 	 * @since 5/25/05
 	 */
 	function addTime ( $timeAmount ) {
 		eval('$result = '.get_class($this).'::withSeconds(
 				$this->asSeconds() + $timeAmount->asSeconds());');
 		return $result;
 	}
	
	/**
 	 * Answer a Time that is timeInterval before the receiver. timeInterval is  
	 * an instance of Date or Time.
 	 * 
 	 * @param object $timeAmount An instance of Date or Time.
 	 * @return object Time
 	 * @access public
 	 * @since 5/25/05
 	 */
 	function subtractTime ( $timeAmount ) {
 		eval('$result = '.get_class($this).'::withSeconds(
 				$this->asSeconds() - $timeAmount->asSeconds());');
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
		$obj = Date::today();
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
		$dateAndTime = DateAndTime::today();
		$obj =$dateAndTime->plus($this);
		return $obj;
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
	 * Answer the month that represents this date's month
	 * 
	 * @return object Month
	 * @access public
	 * @since 5/5/05
	 */
	function asMonth () {
		$asDateAndTime =$this->asDateAndTime();
		$obj =$asDateAndTime->asMonth();
		return $obj;
	}
	
	/**
	 * Answer the number of seconds since midnight of the receiver.
	 * 
	 * @return integer
	 * @access public
	 * @since 5/5/05
	 */
	function asSeconds () {
		return $this->seconds;
	}
	
	/**
	 * Answer a Time that represents our time component
	 * 
	 * @return object Time
	 * @access public
	 * @since 5/5/05
	 */
	function asTime () {
		return $this;
	}
	
	/**
	 * Answer a Timestamp that represents this DateAndTime
	 * 
	 * @return object TimeStamp
	 * @access public
	 * @since 5/5/05
	 */
	function asTimeStamp () {
		$asDateAndTime =$this->asDateAndTime();
		$obj =$asDateAndTime->asTimeStamp();
		return $obj;
	}
	
	/**
	 * Answer this time as a Week
	 * 
	 * @return object Year
	 * @access public
	 * @since 5/5/05
	 */
	function asWeek () {
		$asDateAndTime =$this->asDateAndTime();
		$obj =$asDateAndTime->asWeek();
		return $obj;
	}
	
	/**
	 * Answer this time as a Year
	 * 
	 * @return object Year
	 * @access public
	 * @since 5/5/05
	 */
	function asYear () {
		$asDateAndTime =$this->asDateAndTime();
		$obj =$asDateAndTime->asYear();
		return $obj;
	}
	
	/**
	 * Answer a Timespan. anEnd must respond to asDateAndTime()
	 * 
	 * @param object $anEnd anEnd must understand asDateAndTime()
	 * @return object Timespan
	 * @access public
	 * @since 5/25/05
	 */
	function to ( $anEnd ) {
		$asDateAndTime =$this->asDateAndTime();
		$obj =$asDateAndTime->to($anEnd);
		return $obj;
	}
}

?>