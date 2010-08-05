<?php
/**
 * @since 5/25/05
 * @package harmoni.primitives.chronology
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Schedule.class.php,v 1.5 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */ 
 
require_once(dirname(__FILE__)."/Timespan.class.php");

/**
 * I represent a powerful class for implementing recurring schedules.
 *
 * To create new Schedule instances, <b>use one of the static instance-creation 
 * methods</b>, NOT 'new Schedule':
 *		- {@link current Schedule::current()}
 *		- {@link current Schedule::current()}
 *		- {@link epoch Schedule::epoch()}
 *		- {@link starting Schedule::starting($aDateAndTime)}
 *		- {@link startingDuration Schedule::startingDuration($aDateAndTime, $aDuration)}
 *		- {@link startingEnding Schedule::startingEnding($startDateAndTime, $endDateAndTime)}
 * 
 * @since 5/25/05
 * @package harmoni.primitives.chronology
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Schedule.class.php,v 1.5 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class Schedule
	extends Timespan
{
		
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
	 * @return object Schedule
	 * @access public
	 * @since 5/5/05
	 * @static
	 */
	static function current ( $class = 'Schedule' ) {
		$obj = parent::current($class);
		return $obj;
	}
	
	/**
	 * Answer a Month starting on the Squeak epoch: 1 January 1901
	 * 
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object Schedule
	 * @access public
	 * @since 5/5/05
	 * @static
	 */
	static function epoch ( $class = 'Schedule' ) {
		$obj = parent::epoch($class);
		return $obj;
	}
	
	/**
	 * Create a new object starting now, with zero duration
	 * 
	 * @param object DateAndTime $aDateAndTime
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object Schedule
	 * @access public
	 * @since 5/5/05
	 * @static
	 */
	static function starting ( $aDateAndTime, $class = 'Schedule' ) {
		$obj = parent::starting($aDateAndTime, $class);
		return $obj;
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
	 * @return object Schedule
	 * @access public
	 * @static
	 * @since 5/11/05
	 */
	static function startingEnding ( $startDateAndTime, $endDateAndTime, 
		$class = 'Schedule' ) 
	{
		$obj = parent::startingEnding ( $startDateAndTime, $endDateAndTime, $class);
		return $obj;
	}
	
		
	/**
	 * Create a new object starting now, with a given duration.
	 * 
	 * @param object DateAndTime $aDateAndTime
	 * @param object Duration $aDuration
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object Schedule
	 * @access public
	 * @since 5/5/05
	 * @static
	 */
	static function startingDuration ( $aDateAndTime, $aDuration, $class = 'Schedule' ) {
		$obj = parent::startingDuration ( $aDateAndTime, $aDuration, $class);
		return $obj;
	}

/*********************************************************
 * Instance methods - Enumerating
 *********************************************************/
 
 	/**
 	 * Return an array of the DateAndTimes scheduled between aStart and anEnd.
 	 * 
 	 * @param object $aStart
 	 * @param object $anEnd
 	 * @return array Of DateAndTime objects
 	 * @access public
 	 * @since 5/25/05
 	 */
 	function between ( $aStart, $anEnd ) {
 		$results = array();
 		$end =$anEnd->min($this->end());
 		
 		// iterate to the first element in the range
 		$element =$this->start();
 		$i = 0;
 		while ($element->isLessThan($aStart)) {
 			$element =$element->plus($this->schedule[$i]);
 			$i++;
 			if ($i >= count($this->schedule))
 				$i = 0;
 		}
 		
 		// Reset our schedule index to the first one.
 		// This is the way it is implemented in Squeak, though I'm not sure why.
 		$i = 0;
 		
 		// Collect the results
 		while ($element->isLessThanOrEqualTo($anEnd)) {
 			$results[] =$element;
 			
 			$element =$element->plus($this->schedule[$i]);
 			$i++;
 			if ($i >= count($this->schedule))
 				$i = 0;
 		}
 		
 		return $results;
 	}
 	
 	/**
 	 * Answer the DateAndTimes scheduled over the reciever's entire duration.
 	 * 
 	 * @return Of DateAndTime objects
 	 * @access public
 	 * @since 5/25/05
 	 */
 	function dateAndTimes () {
 		$obj =$this->between($this->start, $this->end());
 		return $obj;
 	}
	
	/**
	 * Set the schedule
	 * 
	 * @param array $anArrayOfDurations
	 * @return void
	 * @access public
	 * @since 5/25/05
	 */
	function setSchedule ( $anArrayOfDurations ) {
		$this->schedule =$anArrayOfDurations;
	}
	
	/**
	 * Get the schedule elements
	 * 
	 * @return array $anArrayOfDurations
	 * @access public
	 * @since 5/25/05
	 */
	function getSchedule () {
		return $this->schedule;
	}
}

?>