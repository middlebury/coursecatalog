<?php
/**
 * @since 10/14/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 *  <p>This is the query interface for searching courses. Each method match 
 *  specifies an <code> AND </code> term while multiple invocations of the 
 *  same method produce a nested <code> OR. </code> </p>
 * 
 * @package org.osid.course
 */
class banner_course_Course_Search_Query
	extends banner_course_AbstractQuery
    implements osid_course_CourseQuery
{
	
	/**
	 * Constructor
	 * 
	 * @param banner_course_CourseOffering_AbstractSession $session
	 * @return void
	 * @access public
	 * @since 5/20/09
	 */
	public function __construct (banner_course_AbstractSession $session) {
		parent::__construct($session);
		
		$this->addSupportedRecordType(new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors'));
		
		$this->wildcardStringMatchType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:search:wildcard");
		$this->booleanStringMatchType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:search:boolean");

		$this->addStringMatchType($this->wildcardStringMatchType);
		$this->addStringMatchType($this->booleanStringMatchType);
		
		$this->keywordString = '';
	}

/*********************************************************
 * Methods from osid_OsidQuery
 *********************************************************/
 	
    /**
     *  Adds a keyword to match. Multiple keywords can be added to perform a 
     *  boolean <code> OR </code> among them. A keyword may be applied to any 
     *  of the elements defined in this object such as the display name, 
     *  description or any method defined in an interface implemented by this 
     *  object. 
     *
     *  @param string $keyword keyword to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> keyword is </code> not of 
     *          <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> keyword </code> or <code> 
     *          stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchKeyword($keyword, osid_type_Type $stringMatchType, $match) {
    	throw new osid_UnimplementedException();
    	
    	if (!is_string($keyword))
    		throw new osid_InvalidArgumentException("\$keyword '$keyword' must be a string.");
    	
        if ($stringMatchType->isEqual($this->booleanStringMatchType)
        		|| $stringMatchType->isEqual($this->wildcardStringMatchType)) 
        {
        	foreach (explode(' ', $keyword) as $param) {
        		if ($match)
	        		$this->keywordString .= $param.' ';
	        	else
	        		$this->keywordString .= '-'.preg_replace('/^[+-]*(.+)$/i', '\1', $param).' ';
        	}
        } else {
	    	throw new osid_UnsupportedException("The stringMatchType passed is not supported.");
	    }
    }


    /**
     *  Adds a display name to match. Multiple display name matches can be 
     *  added to perform a boolean <code> OR </code> among them. 
     *  <br/><br/>
     *  
     *
     *  @param string $displayName display name to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> keyword is </code> not of 
     *          <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> displayName </code> or 
     *          <code> stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchDisplayName($displayName, osid_type_Type $stringMatchType, $match) {
    	$this->matchNumber($displayName, $stringMatchType, $match);
    }


    /**
     *  Adds a description name to match. Multiple description matches can be 
     *  added to perform a boolean <code> OR </code> among them. 
     *
     *  @param string $description description to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> keyword is </code> not of 
     *          <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> description </code> or 
     *          <code> stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchDescription($description, osid_type_Type $stringMatchType, $match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Matches a description that has any value. 
     *
     *  @param boolean $match <code> true </code> to match any description, 
     *          <code> false </code> to match descriptions with no values 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyDescription($match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Sets a <code> Type </code> for querying objects of a given genus. A 
     *  genus type matches if the specified type is the same genus as the 
     *  object genus type. 
     *
     *  @param object osid_type_Type $genusType the object genus type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> genusType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchGenusType(osid_type_Type $genusType, $match) {
    	throw new osid_UnimplementedException();
    	
    	try {
	    	$schdCode = $this->session->getScheduleCodeFromGenusType($genusType);
	    	$this->addClause('genus_type', 'SSBSECT_SCHD_CODE = ?', array($schdCode), $match);
    	} catch (osid_NotFoundException $e) {
    		$this->addClause('genus_type', 'FALSE', array(), $match);
    	}
    }
    
    /**
     * Answer the schedule code from a genus type
     * 
     * @param osid_type_Type $genusType
     * @return mixed string or null
     * @access private
     * @since 5/27/09
     */
    private function getGenusTypeCode (osid_type_Type $genusType) {
    	throw new osid_UnimplementedException();
    	
    	if (strtolower($genusType->getIdentifierNamespace()) != 'urn')
    		return null;
    	else if (strtolower($genusType->getAuthority()) != strtolower($this->session->getIdAuthority()))
    		return null;
    		
    	if (!preg_match('/^genera:offering\/([a-z]+)$/i', $genusType->getIdentifier(), $matches))
    		return null;
    	
    	return $matches[1];	
    }

    /**
     *  Sets a <code> Type </code> for querying objects of a given genus. A 
     *  genus type matches if the specified type is the same genus as the 
     *  object or if the specified type is an ancestor of the object genus in 
     *  a type hierarchy. 
     *
     *  @param object osid_type_Type $genusType the object genus type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> genusType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchParentGenusType(osid_type_Type $genusType, $match) {
    	if ($genusType->isEqual(new phpkit_type_URNInetType("urn:inet:osid.org:genera:none")))
    		$this->addClause('parent_genus_type', 'TRUE', array(), $match);
    	else
    		$this->addClause('parent_genus_type', 'FALSE', array(), $match);
    }


    /**
     *  Sets a <code> Type </code> for querying objects having records 
     *  implementing a given record type. This includes records of the same 
     *  interface type as the one provided and records implementing an 
     *  ancestor interface type in an interface hierarchy. 
     *
     *  @param object osid_type_Type $recordType the record interface type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> recordType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchRecordType(osid_type_Type $recordType, $match) {
    	if ($this->implementsRecordType($recordType))
    		$this->addClause('record_type', 'TRUE', array(), $match);
    	else
    		$this->addClause('record_type', 'FALSE', array(), $match);
    }


    /**
     *  Tests if this query supports the given record <code> Type. </code> The 
     *  given record type may be supported by the object through 
     *  interface/type inheritence. This method should be checked before 
     *  retrieving the record interface. 
     *
     *  @param object osid_type_Type $recordType a type 
     *  @return boolean <code> true </code> if a record query of the given 
     *          record <code> Type </code> is available, <code> false </code> 
     *          otherwise 
     *  @throws osid_NullArgumentException <code> recordType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasRecordType(osid_type_Type $recordType) {
    	return $this->implementsRecordType($recordType);
    }
 
 
 
/*********************************************************
 * Methods from osid_course_CourseQuery
 *********************************************************/


    /**
     *  Adds a title for this query. 
     *
     *  @param string $title title string to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> title </code> not of 
     *          <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> title </code> or <code> 
     *          stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchTitle($title, osid_type_Type $stringMatchType, $match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Matches a title that has any value. 
     *
     *  @param boolean $match <code> true </code> to match courses with any 
     *          title, <code> false </code> to match assets with no title 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyTitle($match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Adds a course number for this query. 
     *
     *  @param string $number course number string to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> number </code> not of 
     *          <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> number </code> or <code> 
     *          stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchNumber($number, osid_type_Type $stringMatchType, 
                                $match) {
    	if (!is_string($number))
    		throw new osid_InvalidArgumentException("\$number '$number' must be a string.");
    	
        if ($stringMatchType->isEqual($this->wildcardStringMatchType)) {
        	if (!preg_match('/
       
       # 1 SUBJECT CODE, with optional leading wildcard
       (
       	(?: \*)?
       	[a-z\*]{0,3}[a-z]
       )?	 
       
       # 2 Optional wildcard
       (\*)?
       
       # 3 COURSE NUMBER
       (
         [0-9][0-9\*]{0,5}
         (?: \*)?
       )?
       
       		/ix', $number, $matches)) {
        		$this->addClause('number', 'FALSE', array(), $match);
        		return;
        	}
        	
        	$clauses = array();
        	$params = array();
        	
        	// Subject
        	if (isset($matches[1]) && $matches[1]) {
        		$param = strtoupper(str_replace('*', '%', $matches[1]));
        		if (isset($matches[2]) && $matches[2])
        			$param = $param.'%';
        		
        		$clauses[] = 'SCBCRSE_SUBJ_CODE LIKE(?)';
        		$params[] = $param;
        	}
        	
        	// Number
        	if (isset($matches[3]) && $matches[3]) {
        		$param = str_replace('*', '%', $matches[3]);
        		if ($matches[2])
        			$param = '%'.$param;
        		
        		$clauses[] = 'SCBCRSE_CRSE_NUMB LIKE(?)';
        		$params[] = $param;
        	}
        	
//         	if ($number == 'PHYS0*') {
// 				print_r($matches);
// 				print_r($clauses);
// 				print_r($params);
//         	}
        	
        	$this->addClause('number', '('.implode(' AND ', $clauses).')', $params, $match);
        } else {
        	throw new osid_UnsupportedException("The type Authority: ".$stringMatchType->getAuthority()." IdNamespace: ".$stringMatchType->getIdentifierNamespace()." Id: ".$stringMatchType->getIdentifier()."  is not supported. Only Authority: ".$this->wildcardStringMatchType->getAuthority()." IdNamespace: ".$this->wildcardStringMatchType->getIdentifierNamespace()." Id: ".$this->wildcardStringMatchType->getIdentifier()." are supported");
       }
    }


    /**
     *  Matches a course number that has any value. 
     *
     *  @param boolean $match <code> true </code> to match courses with any 
     *          number, <code> false </code> to match assets with no title 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyNumber($match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Matches courses with credits between the given numbers inclusive. 
     *
     *  @param float $min low number 
     *  @param float $max high number 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> max </code> is less than 
     *          <code> min </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchCredits($min, $max, $match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Matches a course that has any credits assigned. 
     *
     *  @param boolean $match <code> true </code> to match courses with any 
     *          credits, <code> false </code> to match assets with no credits 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyCredits($match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Matches courses with the prerequisites informational string. 
     *
     *  @param string $prereqInfo prerequisite informational string to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> prereqInfo </code> not of 
     *          <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> prereqInfo </code> or <code> 
     *          stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchPrereqInfo($prereqInfo, 
                                    osid_type_Type $stringMatchType, $match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Matches a course that has any prerequisite information assigned. 
     *
     *  @param boolean $match <code> true </code> to match courses with any 
     *          prerequisite information, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyPrereqInfo($match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Sets the catalog <code> Id </code> for this query to match courses 
     *  that have a related course offering. 
     *
     *  @param object osid_id_Id $courseOfferingId a course offering <code> Id 
     *          </code> 
     *  @param boolean $match <code> true </code> if a positive match, <code> 
     *          false </code> for negative match 
     *  @throws osid_NullArgumentException <code> courseOfferingId </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchCourseOfferingId(osid_id_Id $courseOfferingId, $match) {
    	$this->addClause('course_offering_id', '(SSBSECT_TERM_CODE = ? AND SSBSECT_CRN = ?)', 
    		array($this->session->getTermCodeFromCourseOfferingId($courseId),
    			$this->session->getCrnCourseOfferingId($courseId)),
    		$match);
    	$this->addTableJoin('LEFT JOIN SSBSECT ON (SCBCRSE_SUBJ_CODE = SSBSECT_SUBJ_CODE AND SCBCRSE_CRSE_NUMB = SSBSECT_CRSE_NUMB)');
    }


    /**
     *  Tests if a <code> CourseOfferingQuery </code> is available. 
     *
     *  @return boolean <code> true </code> if a course offering query 
     *          interface is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseOfferingQuery() {
    	return false;
    }


    /**
     *  Gets the query interface for a course offering. Multiple retrievals 
     *  produce a nested <code> OR </code> term. 
     *
     *  @return object osid_course_CourseOfferingQuery the course offering 
     *          query 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCourseOfferingQuery() </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseOfferingQuery() </code> is <code> true. 
     *              </code> 
     */
    public function getCourseOfferingQuery() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Matches courses that have any course offering. 
     *
     *  @param boolean $match <code> true </code> to match courses with any 
     *          course offering, <code> false </code> to match courses with no 
     *          course offering 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyCourseOffering($match) {
    	$this->addClause('course_offering_exists', 'SSBSECT_TERM_CODE IS NOT NULL', 
    		array($this->session->getTermCodeFromCourseOfferingId($courseId),
    			$this->session->getCrnCourseOfferingId($courseId)),
    		$match);
    	$this->addTableJoin('LEFT JOIN SSBSECT ON (SCBCRSE_SUBJ_CODE = SSBSECT_SUBJ_CODE AND SCBCRSE_CRSE_NUMB = SSBSECT_CRSE_NUMB)');
    }


    /**
     *  Sets the course catalog <code> Id </code> for this query to match 
     *  courses assigned to course catalogs. 
     *
     *  @param object osid_id_Id $courseCatalogId the course catalog <code> Id 
     *          </code> 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchCourseCatalogId(osid_id_Id $courseCatalogId, $match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Tests if a <code> CourseCatalogQuery </code> is available. 
     *
     *  @return boolean <code> true </code> if a course catalog query 
     *          interface is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseCatalogQuery() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Gets the query interface for a course catalog. Multiple retrievals 
     *  produce a nested <code> OR </code> term. 
     *
     *  @return object osid_course_CourseCatalogQuery the course catalog query 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCourseCatalogQuery() </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseCatalogQuery() </code> is <code> true. 
     *              </code> 
     */
    public function getCourseCatalogQuery() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Gets the record query interface corresponding to the given <code> 
     *  Course </code> record <code> Type. </code> Multiple record retrievals 
     *  produce a nested <code> OR </code> term. 
     *
     *  @param object osid_type_Type $courseRecordType a course record type 
     *  @return object osid_course_CourseQueryRecord the course query record 
     *  @throws osid_NullArgumentException <code> courseRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasRecordType(courseRecordType) </code> is <code> false 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseQueryRecord(osid_type_Type $courseRecordType) {
    	throw new osid_UnimplementedException();
    }

}
