<?php
/**
 * @since 4/14/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 
 
/**
 *  <p>A <code> Term </code> represents a period of time in which a course is 
 *  offered. </p>
 * 
 * @package org.osid.course
 */
class banner_course_Term
    extends phpkit_AbstractOsidObject
    implements osid_course_Term
{
	
	/**
	 * Constructor
	 * 
	 * @param osid_id_Id $id
	 * @param string $displayName
	 * @param string $startDate
	 * @param string $endDate
	 * @return void
	 * @access public
	 * @since 4/13/09
	 */
	public function __construct (osid_id_Id $id, $displayName, $startDate, $endDate) {
		parent::__construct();
		$this->setId($id);
		$this->setDisplayName($displayName);
		$this->setDescription('');
		
		$this->startDate = $startDate;
		$this->endDate = $endDate;
	}

    /**
     *  Gets a display label for this term which may be less formal than the 
     *  display name. 
     *
     *  @return string the term label 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDisplayLabel() {
    	return $this->getDisplayName();
    }


    /**
     *  Gets the start time for this term. 
     *
     *  @return DateTime the start time 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getStartTime() {
    	return new DateTime($this->startDate);
    }


    /**
     *  Gets the end time for this term. 
     *
     *  @return DateTime the end time 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getEndTime() {
    	return new DateTime($this->endDate);
    }


    /**
     *  Tests if this term has an associated calendar. 
     *
     *  @return boolean <code> true </code> if there is a calendar associated 
     *          with this term, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasCalendar() {
    	return false;
    }


    /**
     *  Gets the <code> Calendar </code> <code> Id </code> associated with 
     *  this term. 
     *
     *  @return object osid_id_Id the calendar <code> Id </code> 
     *  @throws osid_IllegalStateException <code> hasCalendar() </code> is 
     *          <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCalendarId() {
    	throw new osid_IllegalStateException('Calendar is not supported.');
    }


    /**
     *  Gets the <code> Calendar </code> associated with this term. 
     *
     *  @return object osid_calendaring_Calendar the calendar 
     *  @throws osid_IllegalStateException <code> hasCalendar() </code> is 
     *          <code> false </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCalendar() {
    	throw new osid_IllegalStateException('Calendar is not supported.');
    }


    /**
     *  Gets the record corresponding to the given <code> Term </code> record 
     *  <code> Type. </code> This method must be used to retrieve an object 
     *  implementing the requested record interface along with all of its 
     *  ancestor interfaces. The <code> termRecordType </code> may be the 
     *  <code> Type </code> returned in <code> getRecordTypes() </code> or any 
     *  of its parents in a <code> Type </code> hierarchy where <code> 
     *  hasRecordType(termRecordType) </code> is <code> true </code> . 
     *
     *  @param object osid_type_Type $termRecordType the type of term record 
     *          to retrieve 
     *  @return object osid_course_TermRecord the term record 
     *  @throws osid_NullArgumentException <code> termRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> hasRecordType(termRecordType) 
     *          </code> is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTermRecord(osid_type_Type $termRecordType) {
    	throw new osid_UnsupportedException('The term record type is not supported.');
    }

}
