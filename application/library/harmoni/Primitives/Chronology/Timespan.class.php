<?php
/**
 * @since 5/2/05
 * @package harmoni.primitives.chronology
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Timespan.class.php,v 1.6 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */ 

require_once(dirname(__FILE__)."/../Magnitudes/Magnitude.class.php");

/**
 * Timespan represents a duration starting at a specific DateAndTime.
 *
 * To create new Timespan instances, <b>use one of the static instance-creation 
 * methods</b>, NOT 'new Timespan':
 *		- {@link current Timespan::current()}
 *		- {@link current Timespan::current()}
 *		- {@link epoch Timespan::epoch()}
 *		- {@link starting Timespan::starting($aDateAndTime)}
 *		- {@link startingDuration Timespan::startingDuration($aDateAndTime, $aDuration)}
 *		- {@link startingEnding Timespan::startingEnding($startDateAndTime, $endDateAndTime)}
 * 
 * @since 5/2/05
 * @package harmoni.primitives.chronology
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Timespan.class.php,v 1.6 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class Timespan 
	extends Magnitude
{

	/**
	 * @var object DateAndTime $start; The starting point of this time-span 
	 * @access private
	 * @since 5/11/05
	 */
	var $start;
	
	/**
	 * @var object Duration $duration; The duration of this time-span. 
	 * @access private
	 * @since 5/11/05
	 */
	var $duration;

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
	 * Answer a new object that represents now.
	 * 
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object Timespan
	 * @access public
	 * @since 5/5/05
	 * @static
	 */
	static function current ( $class = 'Timespan' ) {
		eval('$result = '.$class.'::starting(DateAndTime::now(), $class);');
		
		return $result;
	}
	
	/**
	 * Answer a Timespan starting on the Squeak epoch: 1 January 1901
	 * 
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object Timespan
	 * @access public
	 * @since 5/5/05
	 * @static
	 */
	static function epoch ( $class = 'Timespan' ) {
		eval('$result = '.$class.'::starting(DateAndTime::epoch(), $class);');
		
		return $result;
	}
	
	/**
	 * Create a new object starting now, with zero duration
	 * 
	 * @param object DateAndTime $aDateAndTime
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object Timespan
	 * @access public
	 * @since 5/5/05
	 * @static
	 */
	static function starting ( $aDateAndTime, $class = 'Timespan' ) {
		eval('$result = '.$class.'::startingDuration(
				$aDateAndTime, Duration::zero(), $class);');
		
		return $result;
	}
	
	/**
	 * Create a new object
	 * 
	 * @param object DateAndTime $aDateAndTime
	 * @param object Duration $aDuration
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object Timespan
	 * @access public
	 * @since 5/5/05
	 * @static
	 */
	static function startingDuration ( $aDateAndTime, $aDuration, $class = 'Timespan' ) {
		
		// Validate our passed class name.
		if (!(strtolower($class) == strtolower('Timespan')
			|| is_subclass_of(new $class, 'Timespan')))
		{
			die("Class, '$class', is not a subclass of 'Timespan'.");
		}
		
		$timeSpan = new $class;
		$timeSpan->setStart($aDateAndTime);
		$timeSpan->setDuration($aDuration);
		
		return $timeSpan;
	}
	
	/**
	 * Create a new object with given start and end DateAndTimes
	 * 
	 * @param object DateAndTime $startDateAndTime
	 * @param object DateAndTime $endDateAndTime
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object Timespan
	 * @access public
	 * @since 5/11/05
	 * @static
	 */
	static function startingEnding ( $startDateAndTime, $endDateAndTime, $class = 'Timespan' ) 
	{
		$end =$endDateAndTime->asDateAndTime();
		eval('$result = '.$class.'::startingDuration(
			$startDateAndTime,
			$end->minus($startDateAndTime),
			$class);');
		
		return $result;
	}
	
/*********************************************************
 * Instance Methods - Private
 *********************************************************/
 
	/**
	 * Do not use this constructor for building objects, please use the 
	 * class-methods Timespan::new(), Timespan::starting(), etcetera, instead.
	 * 
	 * @return object Timespan
	 * @access private
	 * @since 5/2/05
	 */
	function Timespan () {
		
	}

	/**
	 * Store the start DateAndTime of this timespan
	 * 
	 * @param object DateAndTime $aDateAndTime
	 * @return void
	 * @access private
	 * @since 5/4/05
	 */
	function setStart ( $aDateAndTime ) {
		$this->start =$aDateAndTime;
	}
	
	/**
	 * Set the Duration of this timespan
	 * 
	 * @param object Duration $aDuration
	 * @return void
	 * @access private
	 * @since 5/4/05
	 */
	function setDuration ( $aDuration ) {
		$this->duration =$aDuration;
	}

/*********************************************************
 * Instance methods - Comparing/Testing
 *********************************************************/
	
	/**
	 * Test if this Timespan is equal to a Timespan.
	 * 
	 * @param object Timespan $aTimespan
	 * @return boolean
	 * @access public
	 * @since 5/3/05
	 */
	function isEqualTo ( $aTimespan ) {
		return ($this->start->isEqualTo($aTimespan->start())
			&& $this->duration->isEqualTo($aTimespan->duration()));
	}
	
	/**
	 * Test if this Timespan is less than a comparand.
	 * 
	 * @param object $aComparand
	 * @return boolean
	 * @access public
	 * @since 5/3/05
	 */
	function isLessThan ( $aComparand ) {
		return ($this->start->isLessThan($aComparand));
	}
	
	/**
	 * Answer TRUE if the argument is within the timespan covered by the reciever.
	 * 
	 * @param object DateAndTime $aDateAndTime A DateAndTime or Timespan.
	 * @return boolean
	 * @access public
	 * @since 5/13/05
	 */
	function includes ( $aDateAndTime ) {
		// If the argument is a Timespan, check the end-date as well.
		if (strtolower(get_class($aDateAndTime)) == 'timespan' 
			|| is_subclass_of($aDateAndTime, 'Timespan')) 
		{
			return ($this->includes($aDateAndTime->start()) 
				&& $this->includes($aDateAndTime->end()));
		
		} 
		// If the argument is a DateAndTime, just check it.
		else {
			$asDandT =$aDateAndTime->asDateAndTime();
			return $asDandT->isBetween($this->start(), $this->end());
		}
	}
	
	/**
	 * Answer whether all the elements of anArray are in the receiver.
	 * 
	 * @param array $anArray An array of Timespans or DateAndTimes.
	 * @return boolean
	 * @access public
	 * @since 5/13/05
	 */
	function includesAllOf ( $anArray ) {
		foreach (array_keys($anArray) as $key) {
			if (!$this->includes($anArray[$key]))
				return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * Answer whether any the elements of anArray are in the receiver.
	 * 
	 * @param array $anArray An array of Timespans or DateAndTimes.
	 * @return boolean
	 * @access public
	 * @since 5/13/05
	 */
	function includesAnyOf ( $anArray ) {
		foreach (array_keys($anArray) as $key) {
			if ($this->includes($anArray[$key]))
				return TRUE;
		}
		
		return FALSE;
	}

/*********************************************************
 * Instance methods - Operations
 *********************************************************/
 
 	/**
	 * Return the Timespan both have in common, or null
	 * 
	 * @param object Timespan $aTimespan
	 * @return mixed object Timespan OR null
	 * @access public
	 * @since 5/13/05
	 */
	function intersection ( $aTimespan ) {
		$start =$this->start();
		$end =$this->end();
		
		$aBeginning =$start->max($aTimespan->start());
		$anEnd =$end->min($aTimespan->end());
		
		if ($anEnd->isLessThan($aBeginning)) {
			$null = null;
			return $null;
		} else {
			eval('$result = '.get_class($this).'::startingEnding($aBeginning, $anEnd);');
			return $result;
		}
	}
	
	/**
	 * Subtract a Duration or DateAndTime.
	 * 
	 * @param object $operand
	 * @return object Timespan (if operand is a Duration) OR Duration (if operand is a DateAndTime).
	 * @access public
	 * @since 5/3/05
	 */
	function minus ( $operand ) {
		$methods = get_class_methods($operand);
		
		// If this conforms to the DateAndTimeProtocal
		if (in_array('asdateandtime', $methods) 
			| in_array('asDateAndTime', $methods)) 
		{
			$obj =$this->start->minus($operand);
			return $obj;
		} 
		// If this conforms to the Duration protocal
		else {
			$obj =$this->plus($operand->negated());
			return $obj;
		}
	}

	/**
	 * Answer the next object of our duration.
	 * 
	 * @return object Timespan
	 * @access public
	 * @since 5/10/05
	 */
	function next () {
 		eval('$result = '.get_class($this).'::startingDuration(
 			$this->start->plus($this->duration),
 			$this->duration,
 			"'.get_class($this).'");');
 		return $result;
	}
	
	/**
	 * Add a Duration.
	 * 
	 * @param object Duration $aDuration
	 * @return object Timespan The result.
	 * @access public
	 * @since 5/3/05
	 */
	function plus ( $aDuration ) {
		$classname = get_class($this);
		
		eval('$result = '.$classname.'::startingDuration($this->start->plus($aDuration), 
			$this->duration());');
		
		return $result;
	}
	
	/**
	 * Answer the previous object of our duration.
	 * 
	 * @return object Timespan
	 * @access public
	 * @since 5/10/05
	 */
	function previous () {
		eval('$result = '.get_class($this).'::startingDuration(
 			$this->start->minus($this->duration),
 			$this->duration,
 			"'.get_class($this).'");');
 		return $result;
	}
	
	/**
	 * Return the Timespan spanned by both
	 * 
	 * @param object Timespan $aTimespan
	 * @return mixed object Timespan OR null
	 * @access public
	 * @since 5/13/05
	 */
	function union ( $aTimespan ) {
		$start =$this->start();
		$end =$this->end();
		
		$aBeginning =$start->min($aTimespan->start());
		$anEnd =$end->max($aTimespan->end());
		
		$obj = Timespan::startingEnding(
				$aBeginning, 
				$anEnd->plus(DateAndTime::clockPrecision()));
		
		return $obj;
	}
	
/*********************************************************
 * Instance Methods - Accessing
 *********************************************************/
 	
 	/**
	 * Answer the day
	 * 
	 * @return integer
	 * @access public
	 * @since 5/13/05
	 */
	function day () {
		return $this->dayOfYear();
	}
	
 	/**
 	 * Answer the day of the month represented by the receiver.
 	 * 
 	 * @return integer
 	 * @access public
 	 * @since 5/11/05
 	 */
 	function dayOfMonth () {
 		return $this->start->dayOfMonth();
 	}
 	
 	/**
 	 * Answer the day of the week represented by the receiver.
 	 * 
 	 * @return integer
 	 * @access public
 	 * @since 5/11/05
 	 */
 	function dayOfWeek () {
 		return $this->start->dayOfWeek();
 	}
 	
 	/**
 	 * Answer the day of the week represented by the receiver.
 	 * 
 	 * @return integer
 	 * @access public
 	 * @since 5/11/05
 	 */
 	function dayOfWeekName () {
 		return $this->start->dayOfWeekName();
 	}
 	
 	/**
 	 * Answer the day of the year represented by the receiver.
 	 * 
 	 * @return integer
 	 * @access public
 	 * @since 5/11/05
 	 */
 	function dayOfYear () {
 		return $this->start->dayOfYear();
 	}
 	
 	/**
	 * Answer the number of days in the month represented by the receiver.
	 * 
	 * @return ingteger
	 * @access public
	 * @since 5/13/05
	 */
	function daysInMonth () {
		return $this->start->daysInMonth();
	}
	
	/**
	 * Answer the number of days in the year represented by the receiver.
	 * 
	 * @return ingteger
	 * @access public
	 * @since 5/13/05
	 */
	function daysInYear () {
		return $this->start->daysInYear();
	}
	
	/**
	 * Answer the number of days in the year after the date of the receiver.
	 * 
	 * @return ingteger
	 * @access public
	 * @since 5/13/05
	 */
	function daysLeftInYear () {
		return $this->start->daysLeftInYear();
	}

 	
 	/**
 	 * Answer the Duration of this timespan
 	 * 
 	 * @return object Duration
 	 * @access public
 	 * @since 5/11/05
 	 */
 	function duration () {
 		return $this->duration;
 	}
 	
 	/**
 	 * Answer the end of this timespan
 	 * 
 	 * @return object DateAndTime
 	 * @access public
 	 * @since 5/11/05
 	 */
 	function end () {
 		$next =$this->next();
 		$nextStart =$next->start();
 		$obj =$nextStart->minus(DateAndTime::clockPrecision());
 		return $obj;
 	}
 	
 	/**
	 * Answer the day-in-the-year of the first day of our month
	 * 
	 * @return integer
	 * @access public
	 * @since 5/13/05
	 */
	function firstDayOfMonth () {
		return $this->start->firstDayOfMonth();
	}
 	
 	/**
 	 * Answer TRUE if the year represented by the receiver is a leap year.
 	 * 
 	 * @return integer
 	 * @access public
 	 * @since 5/11/05
 	 */
 	function isLeapYear () {
 		return $this->start->isLeapYear();
 	}
 	
 	/**
 	 * Answer the Julian day number represented by the reciever.
 	 * 
 	 * @return integer
 	 * @access public
 	 * @since 5/11/05
 	 */
 	function julianDayNumber () {
 		return $this->start->julianDayNumber();
 	}
 	
 	/**
 	 * Return a printable string
 	 * 
 	 * @return string
 	 * @access public
 	 * @since 5/13/05
 	 */
 	function printableString () {
 		return $this->start->printableString().'D'.$this->duration->printableString();
 	}
 	
 	/**
 	 * Answer the month represented by the receiver.
 	 * 
 	 * @return integer
 	 * @access public
 	 * @since 5/11/05
 	 */
 	function startMonth () {
 		return $this->start->month();
 	}
 	
 	/**
 	 * Answer the month represented by the receiver.
 	 * 
 	 * @return integer
 	 * @access public
 	 * @since 5/11/05
 	 */
 	function startMonthAbbreviation () {
 		return $this->start->monthAbbreviation();
 	}
 	
 	/**
 	 * Answer the month represented by the receiver.
 	 * 
 	 * @return integer
 	 * @access public
 	 * @since 5/11/05
 	 */
 	function startMonthIndex () {
 		return $this->start->monthIndex();
 	}
 	
 	/**
 	 * Answer the month represented by the receiver.
 	 * 
 	 * @return integer
 	 * @access public
 	 * @since 5/11/05
 	 */
 	function startMonthName () {
 		return $this->start->monthName();
 	}
 	
 	/**
 	 * Answer the start DateAndTime of this timespan
 	 * 
 	 * @return object DateAndTime
 	 * @access public
 	 * @since 5/11/05
 	 */
 	function start () {
 		return $this->start;
 	}
 	
 	/**
 	 * Answer the year represented by the receiver.
 	 * 
 	 * @return integer
 	 * @access public
 	 * @since 5/11/05
 	 */
 	function startYear () {
 		return $this->start->year();
 	}
 
/*********************************************************
 * Instance Methods - Enumerating
 *********************************************************/

	/**
	 * Return an array of the DateAndTimes that occur every $aDuration in the reciever.
	 * 
	 * @return array
	 * @access public
	 * @since 5/13/05
	 */
	function every ( $aDuration ) {
		$every = array();
		
		$element =$this->start;
		$end =$this->end();
		
		while ($element->isLessThanOrEqualTo($end)) {
			$every[] =$element;
			$element =$element->plus($aDuration);
		}
		
		return $every;
	}
	
	/**
	 * Return an array of the dates in the reciever.
	 * 
	 * @return array
	 * @access public
	 * @since 5/13/05
	 */
	function dates () {
		$dates = array();
		
		$element =$this->start->asDate();
		$end =$this->end();
		
		while ($element->isLessThanOrEqualTo($end)) {
			$dates[] =$element;
			$element =$element->next();
		}
		
		return $dates;
	}
	
	/**
	 * Return an array of the Months in the reciever.
	 * 
	 * @return array
	 * @access public
	 * @since 5/13/05
	 */
	function months () {
		$months = array();
		
		$element =$this->start->asMonth();
		$end =$this->end();
		
		while ($element->isLessThanOrEqualTo($end)) {
			$months[] =$element;
			$element =$element->next();
		}
		
		return $months;
	}
	
	/**
	 * Return an array of the weeks in the reciever.
	 * 
	 * @return array
	 * @access public
	 * @since 5/13/05
	 */
	function weeks () {
		$weeks = array();
		
		$element =$this->start->asWeek();
		$end =$this->end();
		
		while ($element->isLessThanOrEqualTo($end)) {
			$weeks[] =$element;
			$element =$element->next();
		}
		
		return $weeks;
	}
	
	/**
	 * Return an array of the years in the reciever.
	 * 
	 * @return array
	 * @access public
	 * @since 5/13/05
	 */
	function years () {
		$years = array();
		
		$element =$this->start->asYear();
		$end =$this->end();
		
		while ($element->isLessThanOrEqualTo($end)) {
			$years[] =$element;
			$element =$element->next();
		}
		
		return $years;
	}
 	
/*********************************************************
 * Instance Methods - Converting
 *********************************************************/
	
	/**
	 * Answer this instance converted.
	 * 
	 * @return obect Date
	 * @access public
	 * @since 5/13/05
	 */
	function asDate () {
		$obj =$this->start->asDate();
		return $obj;
	}
	
	/**
	 * Answer this instance converted.
	 * 
	 * @return obect DateAndTime
	 * @access public
	 * @since 5/13/05
	 */
	function asDateAndTime () {
		return $this->start;
	}
	
	/**
	 * Answer this instance converted.
	 * 
	 * @return obect Duration
	 * @access public
	 * @since 5/13/05
	 */
	function asDuration () {
		return $this->duration;
	}
	
	/**
	 * Answer this instance converted.
	 * 
	 * @return obect Month
	 * @access public
	 * @since 5/13/05
	 */
	function asMonth () {
		$obj =$this->start->asMonth();
		return $obj;
	}
	
	/**
	 * Answer this instance converted.
	 * 
	 * @return obect Time
	 * @access public
	 * @since 5/13/05
	 */
	function asTime () {
		$obj =$this->start->asTime();
		return $obj;
	}
	
	/**
	 * Answer this instance converted.
	 * 
	 * @return obect TimeStamp
	 * @access public
	 * @since 5/13/05
	 */
	function asTimeStamp () {
		$obj =$this->start->asTimeStamp();
		return $obj;
	}
	
	/**
	 * Answer this instance converted.
	 * 
	 * @return obect Week
	 * @access public
	 * @since 5/13/05
	 */
	function asWeek () {
		$obj =$this->start->asWeek();
		return $obj;
	}
	
	/**
	 * Answer this instance converted.
	 * 
	 * @return obect Year
	 * @access public
	 * @since 5/13/05
	 */
	function asYear () {
		$obj =$this->start->asYear();
		return $obj;
	}
	
	/**
	 * Answer an Timespan. anEnd must be aDateAndTime or a Timespan
	 * 
	 * @param object $anEnd Must be a DateAndTime or a Timespan
	 * @return object Timespan
	 * @access public
	 * @since 5/13/05
	 */
	function to ( $anEnd ) {
		$obj = Timespan::startingEnding($this->start(), $anEnd->asDateAndTime());
		return $obj;
	}
}

require_once(dirname(__FILE__)."/DateAndTime.class.php");

?>