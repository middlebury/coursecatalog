<?php
/**
 * @since 4/14/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

include_once("fsmparserclass.inc.php");

/**
 *  <p>A <code> Course </code> represents a canonical learning unit. A <code> 
 *  Course </code> is instantiated at a time and place through the creation of 
 *  a <code> CourseOffering. </code> </p>
 * 
 * @since 4/14/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class banner_course_Course 
    extends phpkit_AbstractOsidObject
    implements osid_course_Course, 
    middlebury_course_Course_TermsRecord,
    middlebury_course_Course_AlternatesRecord
{
	
	/**
	 * Translate any markup or encoding in the description into UTF-8 XHTML.
	 * 
	 * @param string $description
	 * @return string
	 * @access public
	 * @since 10/23/09
	 * @static
	 */
	public static function convertDescription ($description) {
		$tmp = error_reporting();
		error_reporting(E_WARNING);
		
		$parser = self::getFsmParser();
		ob_start();
		$parser->Parse($description,"UNKNOWN");
		
		error_reporting($tmp);
		return nl2br(ob_get_clean());
	}
	
	/**
	 * Answer an FMS Parser configured to convert markdown into XHTML text
	 * 
	 * @return FSMParser
	 * @access private
	 * @since 10/23/09
	 * @static
	 */
	private static function getFsmParser () {
		if (!isset(self::$fsmParser)) {			
			self::$fsmParser = new FSMParser();

			//---------Programming the FSM:
			
			/*********************************************************
			 * Normal state
			 *********************************************************/
			
			// Enter from unknown into normal state if the first character is not a slash or bold.
			self::$fsmParser->FSM('/[^\/\*]/s','echo $STRING;','CDATA','UNKNOWN');
			
			//In normal state, catch all other data
			self::$fsmParser->FSM('/./s','echo $STRING;','CDATA','CDATA');
			
			/*********************************************************
			 * Italic
			 *********************************************************/
			// Enter into Italic if at the begining of the line.
			self::$fsmParser->FSM(
				'/^\/\w/',
				'preg_match("/^\/(\w)/", $STRING, $m); echo "<em>".$m[1];',
				'ITALIC',
				'UNKNOWN');
			
			//In normal state, catch italic start
			self::$fsmParser->FSM(
				'/[^\w.:\/]\/\w/',
				'preg_match("/(\W)\/(\w)/", $STRING, $m); echo $m[1]."<em>".$m[2];',
				'ITALIC',
				'CDATA');
			
			// Close out of italic state back to normal
			self::$fsmParser->FSM(
				'/\w\/\W/',
				'preg_match("/(\w)\/(\W)/", $STRING, $m); echo $m[1]."</em>".$m[2];',
				'CDATA',
				'ITALIC');
			
			//In normal state, catch italic start for whitespace+non-word
			self::$fsmParser->FSM(
				'/\s\/[^\s]/',
				'preg_match("/(\s)\/([^\s])/", $STRING, $m); echo $m[1]."<em>".$m[2];',
				'ITALIC',
				'CDATA');
			
			// Close out of italic state back to normal for whitespace+non-word
			self::$fsmParser->FSM(
				'/[^\s]\/\s/',
				'preg_match("/([^\s])\/(\s)/", $STRING, $m); echo $m[1]."</em>".$m[2];',
				'CDATA',
				'ITALIC');
			
			// Close out of italic state back to normal if bold at the very end
			self::$fsmParser->FSM(
				'/\w\/$/',
				'preg_match("/(\w)\/$/", $STRING, $m); echo $m[1]."</em>";',
				'CDATA',
				'ITALIC');
			
			// Close out of italic state back to normal if there is no closing mark.
			self::$fsmParser->FSM(
				'/.$/',
				'preg_match("/(.)$/", $STRING, $m); echo $m[1]."</em>";',
				'CDATA',
				'ITALIC');
			
			//In italic state, catch all other data
			self::$fsmParser->FSM('/./s','echo $STRING;','ITALIC','ITALIC');
			
			
			/*********************************************************
			 * Bold
			 *********************************************************/
			
			// Enter into Bold if at the begining of the line.
			self::$fsmParser->FSM(
				'/^\*\w/',
				'preg_match("/^\*(\w)/", $STRING, $m); echo "<strong>".$m[1];',
				'BOLD',
				'UNKNOWN');
			
			//In normal state, catch bold start
			self::$fsmParser->FSM(
				'/[^\w.]\*\w/',
				'preg_match("/(\W)\*(\w)/", $STRING, $m); echo $m[1]."<strong>".$m[2];',
				'BOLD',
				'CDATA');
				
			// Close out of bold state back to normal
			self::$fsmParser->FSM(
				'/\w\*\W/',
				'preg_match("/(\w)\*(\W)/", $STRING, $m); echo $m[1]."</strong>".$m[2];',
				'CDATA',
				'BOLD');
			
			//In normal state, catch bold start for whitespace+non-word
			self::$fsmParser->FSM(
				'/\s\/[^\s]/',
				'preg_match("/(\s)\/([^\s])/", $STRING, $m); echo $m[1]."<strong>".$m[2];',
				'BOLD',
				'CDATA');
			
			// Close out of bold state back to normal for whitespace+non-word
			self::$fsmParser->FSM(
				'/[^\s]\/\s/',
				'preg_match("/([^\s])\/(\s)/", $STRING, $m); echo $m[1]."</strong>".$m[2];',
				'CDATA',
				'BOLD');
				
			// Close out of bold state back to normal if bold at the very end
			self::$fsmParser->FSM(
				'/\w\*$/',
				'preg_match("/(\w)\*$/", $STRING, $m); echo $m[1]."</strong>";',
				'CDATA',
				'BOLD');
			
			// Close out of bold state back to normal if bold if there is no closing mark.
			self::$fsmParser->FSM(
				'/.$/',
				'preg_match("/(.)$/", $STRING, $m); echo $m[1]."</strong>";',
				'CDATA',
				'BOLD');
				
			//In bold state, catch all other data
			self::$fsmParser->FSM('/./s','echo $STRING;','BOLD','BOLD');			
		}
		return self::$fsmParser;
	}
	private static $fsmParser;

	/**
	 * Constructor
	 * 
	 * @param osid_id_Id $id
	 * @param string $displayName
	 * @param banner_course_AbstractSession $session
	 * @return void
	 * @access public
	 * @since 4/13/09
	 */
	public function __construct (osid_id_Id $id, $displayName, $description, $title, $credits, array $topicIds, $hasAlternates, banner_course_AbstractSession $session) {
		parent::__construct();
		$this->setId($id);
		$this->setDisplayName($displayName);
		if (is_null($description))
			$this->setDescription('');
		else
			$this->setDescription(self::convertDescription($description));
		$this->title = $title;
		$this->credits = floatval($credits);
		$this->topicIds = $topicIds;
		$this->hasAlternates = $hasAlternates;
		$this->session = $session;
		
		$this->addRecordType(new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:terms'));
		$this->addRecordType(new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:alternates'));
	}
	
    /**
     *  Gets the formal title of this course. It may be the same as the 
     *  display name or it may be used to more formally label the course. A 
     *  display name might be Physics 102 where the title is Introduction to 
     *  Electromagentism. 
     *
     *  @return string the course title 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTitle() {
    	return $this->title;
    }


    /**
     *  Gets the course number which is a label generally used to indedx the 
     *  course in a catalog, such as T101 or 16.004. 
     *
     *  @return string the course number 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getNumber() {
    	return $this->getDisplayName();
    }


    /**
     *  Gets the number of credits in this course. 
     *
     *  @return float the number of credits 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCredits() {
    	return $this->credits;
    }


    /**
     *  Gets the an informational string for the course prerequisites. 
     *
     *  @return string the prerequisites 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getPrereqInfo() {
    	return '';
    }
    
    /**
     *  WARNING: This method was not in the OSID trunk as of 2009-04-27. A 
     *  ticket requesting the addition of this method is available at: 
     *  http://oki.assembla.com/spaces/osid-dev/tickets/18-osid-course---No-way-to-map-Topics-to-Courses-or-CourseOfferings- 
     *  Gets a list of the <code> Id </code> s of the <code> Topic </code> s 
     *  this course is associated with. 
     *
     *  @return object osid_id_IdList the <code> Topic </code> <code> Id 
     *          </code> s 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTopicIds() {
    	if (!isset($this->allTopicIds)) {
    		$this->allTopicIds = array_merge($this->topicIds, $this->session->getRequirementTopicIdsForCourse($this->getId()));
    	}
    	return new phpkit_id_ArrayIdList($this->allTopicIds);
    }


    /**
     *  WARNING: This method was not in the OSID trunk as of 2009-04-27. A 
     *  ticket requesting the addition of this method is available at: 
     *  http://oki.assembla.com/spaces/osid-dev/tickets/18-osid-course---No-way-to-map-Topics-to-Courses-or-CourseOfferings- 
     *  Gets the <code> Topic </code> s this course is associated with. 
     *
     *  @return object osid_course_TopicList the topics 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTopics() {
    	return $this->session->getTopicLookupSession()->getTopicsByIds($this->getTopicIds());
    }


    /**
     *  Gets the record corresponding to the given <code> Course </code> 
     *  record <code> Type. </code> This method must be used to retrieve an 
     *  object implementing the requested record interface along with all of 
     *  its ancestor interfaces. The <code> courseRecordType </code> may be 
     *  the <code> Type </code> returned in <code> getRecordTypes() </code> or 
     *  any of its parents in a <code> Type </code> hierarchy where <code> 
     *  hasRecordType(courseRecordType) </code> is <code> true </code> . 
     *
     *  @param object osid_type_Type $courseRecordType the type of course 
     *          record to retrieve 
     *  @return object osid_course_CourseRecord the course record 
     *  @throws osid_NullArgumentException <code> courseRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasRecordType(courseRecordType) </code> is <code> false 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseRecord(osid_type_Type $courseRecordType) {
    	if ($this->hasRecordType($courseRecordType));
    		return $this;
    	throw new osid_UnsupportedException('Course record type is not supported.');
    }
    
/*********************************************************
 * Methods from osid_course_CourseRecord
 *********************************************************/

	/**
     *  Gets the <code> Course </code> from which this record originated. 
     *
     *  @return object osid_course_Course the course 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourse() {
    	return $this;
    }

/*********************************************************
 * Methods from osid_OsidRecord
 *********************************************************/

	/**
     *  Tests if the given type is implemented by this record. Other types 
     *  than that directly indicated by <code> getType() </code> may be 
     *  supported through an inheritance scheme where the given type specifies 
     *  a record that is a parent interface of the interface specified by 
     *  <code> getType(). </code> 
     *
     *  @param object osid_type_Type $recordType a type 
     *  @return boolean <code> true </code> if the given record <code> Type 
     *          </code> is implemented by this record, <code> false </code> 
     *          otherwise 
     *  @throws osid_NullArgumentException <code> recordType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function implementsRecordType(osid_type_Type $recordType) {
    	return $this->hasRecordType($recordType);
    }

/*********************************************************
 * Methods from middlebury_course_Course_TermsRecord
 *********************************************************/

	/**
     * Gets the Ids of the Terms in which a <code>Course Offering</code> has been 
     * taught for a <code> Course. </code>
     *
     *  @return object osid_id_IdList the list of term ids.
     *  @compliance mandatory This method must be implemented. 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     */
    public function getTermIds() {
    	return $this->getTerms();
    }
    
    /**
     * Gets the <code> Terms </code> in which a <code>Course Offering</code> has 
     * been taught for a <code> Course. </code>
     *
     *  @return object osid_course_TermList the list of terms.
     *  @compliance mandatory This method must be implemented. 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     */
    public function getTerms() {
    	try {
	    	return new banner_course_Term_ForCourseList($this->session->getManager()->getDB(), $this->session, $this->getId());
	    } catch (osid_NotFoundException $e) {
	    	throw new osid_OperationFailedException($e->getMessage(), $e->getCode(), $e);
	    }
    }
    
/*********************************************************
 * Methods from middlebury_course_Course_AlternatesRecord
 *********************************************************/

    
    /**
	 * Tests if this course has any alternate courses.
	 * 
	 * @return boolean <code> true </code> if this course has any
     *          alternates, <code> false </code> otherwise 
	 * @access public
     * @compliance mandatory This method must be implemented. 
	 */
	public function hasAlternates () {
		return (intval($this->hasAlternates) > 0);
	}

    /**
     *  Gets the Ids of any alternate courses
     *
     *  @return object osid_id_IdList the list of alternate ids.
     *  @compliance mandatory This method must be implemented. 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     */
    public function getAlternateIds() {
		if (!$this->hasAlternates())
    		return new phpkit_EmptyList('osid_id_IdList');
    	
    	return $this->session->getCourseLookupSession()->getAlternateIdsForCourse($this->getId());
	}
    
    /**
     *  Gets the alternate <code> Courses </code>.
     *
     *  @return object osid_course_CourseList The list of alternates.
     *  @compliance mandatory This method must be implemented. 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     */
    public function getAlternates() {
		$lookupSession = $this->session->getCourseLookupSession();
		$lookupSession->useComparativeView();
    	return $lookupSession->getCoursesByIds($this->getAlternateIds());
	}
	
	/**
	 * Answer <code> true </code> if this course is the primary version in a group of
	 * alternates.
	 * 
	 *  @return boolean
     *  @compliance mandatory This method must be implemented. 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
	 */
	public function isPrimary () {
		return false; // Currently no way of determining the 'primary'
	}
}
