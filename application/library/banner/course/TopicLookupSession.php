<?php
/**
 * @since 4/9/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 *  <p>This session provides methods for retrieving <code> Topic </code> 
 *  objects. The <code> Topic </code> represents a subject category in which 
 *  courses can be tagged. </p> 
 *  
 *  <p> This session defines views that offer differing behaviors when 
 *  retrieving multiple objects. </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> comparative view: elements may be silently omitted or re-ordered 
 *      </li> 
 *      <li> plenary view: provides a complete set or is an error condition 
 *      </li> 
 *      <li> isolated course catalog view: All topic methods in this session 
 *      operate, retrieve and pertain to topics defined explicitly in the 
 *      current course catalog. Using an isolated view is useful for managing 
 *      <code> Topics </code> with the <code> TopicAdminSession. </code> </li> 
 *      <li> federated course catalog view: All topic methods in this session 
 *      operate, retrieve and pertain to all topics defined in this course 
 *      catalog and any other topics implicitly available in this course 
 *      catalog through course catalog inheritence. </li> 
 *  </ul>
 *  Generally, the comparative view should be used for most applications as it 
 *  permits operation even if there is data that cannot be accessed. The 
 *  methods <code> useFederatedCourseCatalogView() </code> and <code> 
 *  useIsolatedCourseCatalogView() </code> behave as a radio group and one 
 *  shou </p>
 * 
 * @package org.osid.course
 */
class banner_course_TopicLookupSession
    extends banner_course_AbstractCourseSession
    implements osid_course_TopicLookupSession
{

	/**
	 * Constructor
	 * 
	 * @param banner_course_CourseManagerInterface $manager
	 * @param osid_id_Id $catalogId
	 * @return void
	 * @access public
	 * @since 4/10/09
	 */
	public function __construct (banner_course_CourseManagerInterface $manager, osid_id_Id $catalogId) {
		parent::__construct($manager, 'section/');
		
		$this->catalogId = $catalogId;
	}


    /**
     *  Gets the <code> CourseCatalog </code> <code> Id </code> associated 
     *  with this session. 
     *
     *  @return object osid_id_Id the <code> CourseCatalog Id </code> 
     *          associated with this session 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalogId() {
    	return $this->catalogId;
    }

    /**
     *  Gets the <code> CourseCatalog </code> associated with this session. 
     *
     *  @return object osid_course_CourseCatalog the course catalog 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalog() {
    	if (!isset($this->catalog)) {
	    	$lookup = $this->manager->getCourseCatalogLookupSession();
			$lookup->usePlenaryView();
			$this->catalog = $lookup->getCourseCatalog($this->getCourseCatalogId());
		}
		return $this->catalog;
    }


    /**
     *  Tests if this user can perform <code> Topic </code> lookups. A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known all methods in this session will result in 
     *  a <code> PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer lookup operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if lookup methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canLookupTopics() {
    	return true;
    }


    /**
     *  The returns from the lookup methods may omit or translate elements 
     *  based on this session, such as authorization, and not result in an 
     *  error. This view is used when greater interoperability is desired at 
     *  the expense of precision. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useComparativeTopicView() {
    	$this->useComparativeView();
    }


    /**
     *  A complete view of the <code> Topic </code> returns is desired. 
     *  Methods will return what is requested or result in an error. This view 
     *  is used when greater precision is desired at the expense of 
     *  interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryTopicView() {
    	$this->usePlenaryView();
    }


    /**
     *  Federates the view for methods in this session. A federated view will 
     *  include topics in course catalogs which are children of this course 
     *  catalog in the course catalog hierarchy. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useFederatedCourseCatalogView() {
    	$this->useFederatedView();
    }


    /**
     *  Isolates the view for methods in this session. An isolated view 
     *  restricts lookups to this course catalog only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedCourseCatalogView() {
    	$this->useIsolatedView();
    }


    /**
     *  Gets the <code> Topic </code> specified by its <code> Id. </code> In 
     *  plenary mode, the exact <code> Id </code> is found or a <code> 
     *  NOT_FOUND </code> results. Otherwise, the returned <code> Topic 
     *  </code> may have a different <code> Id </code> than requested, such as 
     *  the case where a duplicate <code> Id </code> was assigned to a <code> 
     *  Topic </code> and retained for compatibility. 
     *
     *  @param object osid_id_Id $topicId <code> Id </code> of the <code> 
     *          Topic </code> 
     *  @return object osid_course_Topic the topic 
     *  @throws osid_NotFoundException <code> topicId </code> not found 
     *  @throws osid_NullArgumentException <code> topicId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method is must be implemented. 
     */
    public function getTopic(osid_id_Id $topicId) {
    	if ($this->usesIsolatedView() && $this->getCourseCatalogId()->isEqual($this->manager->getCombinedCatalogId()))
    		throw new osid_NotFoundException('This catalog does not directly contain any courses. Use useFederatedView() to access courses in child catalogs.');
   		$type = $this->getTopicType($topicId);
   		switch ($type) {
   			case 'subject':
   				return $this->getSubjectTopic($topicId);
   			case 'department':
   				return $this->getDepartmentTopic($topicId);
   			case 'division':
   				return $this->getDivisionTopic($topicId);
   			case 'requirement':
   				return $this->getRequirementTopic($topicId);
   			default:
   				throw new osid_NotFoundException('No topic found with category '.$type);
   		}
    }
    
    /**
     * Answer the type string corresponding to the topic id
     * 
     * @param osid_id_Id $topicId
     * @return string
     * @access public
     * @since 4/24/09
     */
    public function getTopicType (osid_id_Id $topicId) {
    	$string = $this->getDatabaseIdString($topicId, 'topic/');
    	if (!preg_match('#(subject|department|division|requirement)/.+#', $string, $matches))
    		throw new osid_NotFoundException('Could not turn "'.$string.'" into a topic type.');
    	
    	return $matches[1];
    }
    
    /**
     * Answer the value string corresponding to the topic id
     * 
     * @param osid_id_Id $topicId
     * @return string
     * @access public
     * @since 4/24/09
     */
    public function getTopicValue (osid_id_Id $topicId) {
    	$string = $this->getDatabaseIdString($topicId, 'topic/');
    	if (!preg_match('#(subject|department|division|requirement)/(.+)#', $string, $matches))
    		throw new osid_NotFoundException('Could not turn "'.$string.'" into a topic type.');
    	
    	return $matches[2];
    }
    
    private static $getSubjectTopic_stmts = array();
    /**
     * Answer a subject topic by id
     * 
     * @param osid_id_Id $topicId
     * @return osid_course_Topic
     * @access private
     * @since 4/24/09
     */
    private function getSubjectTopic (osid_id_Id $topicId) {
    	$catalogWhere = $this->getCatalogWhereTerms();
    	if (!isset(self::$getSubjectTopic_stmts[$catalogWhere])) {
	    	$query =
"SELECT 
    STVSUBJ_CODE,
	STVSUBJ_DESC
FROM 
	scbcrse
	INNER JOIN stvsubj ON SCBCRSE_SUBJ_CODE = STVSUBJ_CODE
WHERE
	SCBCRSE_SUBJ_CODE = :subject_code
	AND SCBCRSE_COLL_CODE IN (
		SELECT
			coll_code
		FROM
			course_catalog_college
		WHERE
			".$this->getCatalogWhereTerms()."
	)

GROUP BY SCBCRSE_SUBJ_CODE
";
			self::$getSubjectTopic_stmts[$catalogWhere] = $this->manager->getDB()->prepare($query);
		}
		
		$parameters = array_merge(
			array(
				':subject_code' => $this->getDatabaseIdString($topicId, 'topic/subject/')
			),
			$this->getCatalogParameters());
		self::$getSubjectTopic_stmts[$catalogWhere]->execute($parameters);
		$row = self::$getSubjectTopic_stmts[$catalogWhere]->fetch(PDO::FETCH_ASSOC);
		self::$getSubjectTopic_stmts[$catalogWhere]->closeCursor();
		
		if (!$row['STVSUBJ_CODE'])
			throw new osid_NotFoundException("Could not find a topic  matching the subject code ".$this->getDatabaseIdString($topicId, 'topic/subject/').".");
		
		return new banner_course_Topic(
					$this->getOsidIdFromString($row['STVSUBJ_CODE'], 'topic/subject/'),
					$row['STVSUBJ_CODE'],
					$row['STVSUBJ_DESC'],
					new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/subject")
			);
    }
    
    private static $getSubjectTopics_stmts = array();
    /**
     * Answer all of the subject topics
     * 
     * @return osid_course_TopicList
     * @access private
     * @since 4/24/09
     */
    private function getSubjectTopics () {
    	$catalogWhere = $this->getCatalogWhereTerms();
    	if (!isset(self::$getSubjectTopics_stmts[$catalogWhere])) {
	    	$query =
"SELECT 
    STVSUBJ_CODE,
	STVSUBJ_DESC
FROM 
	scbcrse
	INNER JOIN stvsubj ON SCBCRSE_SUBJ_CODE = STVSUBJ_CODE
WHERE
	STVSUBJ_DISP_WEB_IND = 'Y'
	AND SCBCRSE_COLL_CODE IN (
		SELECT
			coll_code
		FROM
			course_catalog_college
		WHERE
			".$this->getCatalogWhereTerms()."
	)

GROUP BY SCBCRSE_SUBJ_CODE
";
			self::$getSubjectTopics_stmts[$catalogWhere] = $this->manager->getDB()->prepare($query);
		}
		
		$parameters = $this->getCatalogParameters();
		self::$getSubjectTopics_stmts[$catalogWhere]->execute($parameters);
				
		$topics = array();
		while ($row = self::$getSubjectTopics_stmts[$catalogWhere]->fetch(PDO::FETCH_ASSOC)) {
			$topics[] = new banner_course_Topic(
						$this->getOsidIdFromString($row['STVSUBJ_CODE'], 'topic/subject/'),
						$row['STVSUBJ_CODE'],
						$row['STVSUBJ_DESC'],
						new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/subject")
				);
		}
		self::$getSubjectTopics_stmts[$catalogWhere]->closeCursor();
		return new phpkit_course_ArrayTopicList($topics);
    }
    
    private static $getDepartmentTopic_stmts = array();
    /**
     * Answer a department topic by id
     * 
     * @param osid_id_Id $topicId
     * @return osid_course_Topic
     * @access private
     * @since 4/24/09
     */
    private function getDepartmentTopic (osid_id_Id $topicId) {
    	$catalogWhere = $this->getCatalogWhereTerms();
    	if (!isset(self::$getDepartmentTopic_stmts[$catalogWhere])) {
	    	$query =
"SELECT 
    STVDEPT_CODE,
	STVDEPT_DESC
FROM 
	scbcrse
	INNER JOIN stvdept ON SCBCRSE_DEPT_CODE = STVDEPT_CODE
WHERE
	SCBCRSE_DEPT_CODE = :department_code
	AND SCBCRSE_COLL_CODE IN (
		SELECT
			coll_code
		FROM
			course_catalog_college
		WHERE
			".$this->getCatalogWhereTerms()."
	)

GROUP BY SCBCRSE_DEPT_CODE
";
			self::$getDepartmentTopic_stmts[$catalogWhere] = $this->manager->getDB()->prepare($query);
		}
		
		$parameters = array_merge(
			array(
				':department_code' => $this->getDatabaseIdString($topicId, 'topic/department/')
			),
			$this->getCatalogParameters());
		self::$getDepartmentTopic_stmts[$catalogWhere]->execute($parameters);
		$row = self::$getDepartmentTopic_stmts[$catalogWhere]->fetch(PDO::FETCH_ASSOC);
		self::$getDepartmentTopic_stmts[$catalogWhere]->closeCursor();
		
		if (!$row['STVDEPT_CODE'])
			throw new osid_NotFoundException("Could not find a topic matching the department code ".$this->getDatabaseIdString($topicId, 'topic/department/').".");
		
		return new banner_course_Topic(
					$this->getOsidIdFromString($row['STVDEPT_CODE'], 'topic/department/'),
					$row['STVDEPT_CODE'],
					$row['STVDEPT_DESC'],
					new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/department")
			);
    }
    
    private static $getDepartmentTopics_stmts = array();
    /**
     * Answer all of the Department topics
     * 
     * @return osid_course_TopicList
     * @access private
     * @since 4/24/09
     */
    private function getDepartmentTopics () {
    	$catalogWhere = $this->getCatalogWhereTerms();
    	if (!isset(self::$getDepartmentTopics_stmts[$catalogWhere])) {
	    	$query =
"SELECT 
    STVDEPT_CODE,
	STVDEPT_DESC
FROM 
	scbcrse
	INNER JOIN stvdept ON SCBCRSE_DEPT_CODE = STVDEPT_CODE
WHERE
	SCBCRSE_COLL_CODE IN (
		SELECT
			coll_code
		FROM
			course_catalog_college
		WHERE
			".$this->getCatalogWhereTerms()."
	)

GROUP BY SCBCRSE_DEPT_CODE
";
			self::$getDepartmentTopics_stmts[$catalogWhere] = $this->manager->getDB()->prepare($query);
		}
		
		$parameters = $this->getCatalogParameters();
		self::$getDepartmentTopics_stmts[$catalogWhere]->execute($parameters);
				
		$topics = array();
		while ($row = self::$getDepartmentTopics_stmts[$catalogWhere]->fetch(PDO::FETCH_ASSOC)) {
			$topics[] = new banner_course_Topic(
						$this->getOsidIdFromString($row['STVDEPT_CODE'], 'topic/department/'),
						$row['STVDEPT_CODE'],
						$row['STVDEPT_DESC'],
						new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/department")
				);
		}
		self::$getDepartmentTopics_stmts[$catalogWhere]->closeCursor();
		return new phpkit_course_ArrayTopicList($topics);
    }
    
    private static $getDivisionTopic_stmts = array();
    /**
     * Answer a division topic by id
     * 
     * @param osid_id_Id $topicId
     * @return osid_course_Topic
     * @access private
     * @since 4/24/09
     */
    private function getDivisionTopic (osid_id_Id $topicId) {
    	$catalogWhere = $this->getCatalogWhereTerms();
    	if (!isset(self::$getDivisionTopic_stmts[$catalogWhere])) {
	    	$query =
"SELECT 
    STVDIVS_CODE,
	STVDIVS_DESC
FROM 
	scbcrse
	INNER JOIN stvdivs ON SCBCRSE_DIVS_CODE = STVDIVS_CODE
WHERE
	SCBCRSE_DIVS_CODE = :division_code
	AND SCBCRSE_COLL_CODE IN (
		SELECT
			coll_code
		FROM
			course_catalog_college
		WHERE
			".$this->getCatalogWhereTerms()."
	)

GROUP BY SCBCRSE_DIVS_CODE
";
			self::$getDivisionTopic_stmts[$catalogWhere] = $this->manager->getDB()->prepare($query);
		}
		
		$parameters = array_merge(
			array(
				':division_code' => $this->getDatabaseIdString($topicId, 'topic/division/')
			),
			$this->getCatalogParameters());
		self::$getDivisionTopic_stmts[$catalogWhere]->execute($parameters);
		$row = self::$getDivisionTopic_stmts[$catalogWhere]->fetch(PDO::FETCH_ASSOC);
		self::$getDivisionTopic_stmts[$catalogWhere]->closeCursor();
		
		if (!$row['STVDIVS_CODE'])
			throw new osid_NotFoundException("Could not find a topic matching the division code ".$this->getDatabaseIdString($topicId, 'topic/division/').".");
		
		return new banner_course_Topic(
					$this->getOsidIdFromString($row['STVDIVS_CODE'], 'topic/division/'),
					$row['STVDIVS_CODE'],
					$row['STVDIVS_DESC'],
					new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/division")
			);
    }
    
    private static $getDivisionTopics_stmts = array();
    /**
     * Answer all of the Division topics
     * 
     * @return osid_course_TopicList
     * @access private
     * @since 4/24/09
     */
    private function getDivisionTopics () {
    	$catalogWhere = $this->getCatalogWhereTerms();
    	if (!isset(self::$getDivisionTopics_stmts[$catalogWhere])) {
	    	$query =
"SELECT 
    STVDIVS_CODE,
	STVDIVS_DESC
FROM 
	scbcrse
	INNER JOIN stvdivs ON SCBCRSE_DIVS_CODE = STVDIVS_CODE
WHERE
	SCBCRSE_COLL_CODE IN (
		SELECT
			coll_code
		FROM
			course_catalog_college
		WHERE
			".$this->getCatalogWhereTerms()."
	)

GROUP BY SCBCRSE_DIVS_CODE
";
			self::$getDivisionTopics_stmts[$catalogWhere] = $this->manager->getDB()->prepare($query);
		}
		
		$parameters = $this->getCatalogParameters();
		self::$getDivisionTopics_stmts[$catalogWhere]->execute($parameters);
				
		$topics = array();
		while ($row = self::$getDivisionTopics_stmts[$catalogWhere]->fetch(PDO::FETCH_ASSOC)) {
			$topics[] = new banner_course_Topic(
						$this->getOsidIdFromString($row['STVDIVS_CODE'], 'topic/division/'),
						$row['STVDIVS_CODE'],
						$row['STVDIVS_DESC'],
						new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/division")
				);
		}
		self::$getDivisionTopics_stmts[$catalogWhere]->closeCursor();
		return new phpkit_course_ArrayTopicList($topics);
    }
    
    private static $getRequirementTopic_stmts = array();
    /**
     * Answer a requirement topic by id
     * 
     * @param osid_id_Id $topicId
     * @return osid_course_Topic
     * @access private
     * @since 4/24/09
     */
    private function getRequirementTopic (osid_id_Id $topicId) {
    	$catalogWhere = $this->getCatalogWhereTerms();
    	if (!isset(self::$getRequirementTopic_stmts[$catalogWhere])) {
	    	$query =
"SELECT 
    STVATTR_CODE,
	STVATTR_DESC
FROM 
	course_section_college
	INNER JOIN ssrattr ON (section_term_code = SSRATTR_TERM_CODE AND section_crn = SSRATTR_CRN)
	INNER JOIN stvattr ON SSRATTR_ATTR_CODE = STVATTR_CODE
WHERE
	SSRATTR_ATTR_CODE = :requirement_code
	AND section_coll_code IN (
		SELECT
			coll_code
		FROM
			course_catalog_college
		WHERE
			".$this->getCatalogWhereTerms()."
	)

GROUP BY STVATTR_CODE
";
			self::$getRequirementTopic_stmts[$catalogWhere] = $this->manager->getDB()->prepare($query);
		}
		
		$parameters = array_merge(
			array(
				':requirement_code' => $this->getDatabaseIdString($topicId, 'topic/requirement/')
			),
			$this->getCatalogParameters());
		self::$getRequirementTopic_stmts[$catalogWhere]->execute($parameters);
		$row = self::$getRequirementTopic_stmts[$catalogWhere]->fetch(PDO::FETCH_ASSOC);
		self::$getRequirementTopic_stmts[$catalogWhere]->closeCursor();
		
		if (!$row['STVATTR_CODE'])
			throw new osid_NotFoundException("Could not find a topic matching the requirement code ".$this->getDatabaseIdString($topicId, 'topic/requirement/').".");
		
		return new banner_course_Topic(
					$this->getOsidIdFromString($row['STVATTR_CODE'], 'topic/requirement/'),
					$row['STVATTR_CODE'],
					$row['STVATTR_DESC'],
					new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/requirement")
			);
    }
    
    private static $getRequirementTopics_stmts = array();
    /**
     * Answer all of the requirement topics
     * 
     * @return osid_course_TopicList
     * @access private
     * @since 4/24/09
     */
    private function getRequirementTopics () {
    	$catalogWhere = $this->getCatalogWhereTerms();
    	if (!isset(self::$getRequirementTopics_stmts[$catalogWhere])) {
	    	$query =
"SELECT 
    STVATTR_CODE,
	STVATTR_DESC
FROM 
	course_section_college
	INNER JOIN ssrattr ON (section_term_code = SSRATTR_TERM_CODE AND section_crn = SSRATTR_CRN)
	INNER JOIN stvattr ON SSRATTR_ATTR_CODE = STVATTR_CODE
WHERE
	section_coll_code IN (
		SELECT
			coll_code
		FROM
			course_catalog_college
		WHERE
			".$this->getCatalogWhereTerms()."
	)

GROUP BY STVATTR_CODE
";
			self::$getRequirementTopics_stmts[$catalogWhere] = $this->manager->getDB()->prepare($query);
		}
		
		$parameters = $this->getCatalogParameters();
		self::$getRequirementTopics_stmts[$catalogWhere]->execute($parameters);
				
		$topics = array();
		while ($row = self::$getRequirementTopics_stmts[$catalogWhere]->fetch(PDO::FETCH_ASSOC)) {
			$topics[] = new banner_course_Topic(
						$this->getOsidIdFromString($row['STVATTR_CODE'], 'topic/requirement/'),
						$row['STVATTR_CODE'],
						$row['STVATTR_DESC'],
						new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/requirement")
				);
		}
		self::$getRequirementTopics_stmts[$catalogWhere]->closeCursor();
		return new phpkit_course_ArrayTopicList($topics);
    }
    
    /**
	 * Answer the catalog where terms
	 * 
	 * @return string
	 * @access private
	 * @since 4/20/09
	 */
	private function getCatalogWhereTerms () {
		if (is_null($this->catalogId) || $this->catalogId->isEqual($this->getCombinedCatalogId()))
			return 'TRUE';
		else
			return 'catalog_id = :catalog_id';
	}
	
	/**
	 * Answer the input parameters
	 * 
	 * @return array
	 * @access private
	 * @since 4/17/09
	 */
	private function getCatalogParameters () {
		$params = array();
		if (!is_null($this->catalogId) && !$this->catalogId->isEqual($this->getCombinedCatalogId()))
			$params[':catalog_id'] = $this->getCatalogDatabaseId($this->catalogId);
		return $params;
	}


    /**
     *  Gets a <code> TopicList </code> corresponding to the given <code> 
     *  IdList. </code> In plenary mode, the returned list contains all of the 
     *  topics specified in the <code> Id </code> list, in the order of the 
     *  list, including duplicates, or an error results if an <code> Id 
     *  </code> in the supplied list is not found or inaccessible. Otherwise, 
     *  inaccessible <code> Topics </code> may be omitted from the list and 
     *  may present the elements in any order including returning a unique 
     *  set. 
     *
     *  @param object osid_id_IdList $topicIdList the list of <code> Ids 
     *          </code> to rerieve 
     *  @return object osid_course_TopicList the returned <code> Topic list 
     *          </code> 
     *  @throws osid_NotFoundException an <code> Id was </code> not found 
     *  @throws osid_NullArgumentException <code> topicIdList </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTopicsByIds(osid_id_IdList $topicIdList) {
    	$topics = array();
    	
    	while ($topicIdList->hasNext()) {
    		try {
    			$topics[] = $this->getTopic($topicIdList->getNextId());
    		} catch (osid_NotFoundException $e) {
    			if ($this->usesPlenaryView())
    				throw $e;
    		} catch (osid_PermissionDeniedException $e) {
    			if ($this->usesPlenaryView())
    				throw $e;
    		}
    	}
    	
    	return new phpkit_course_ArrayTopicList($topics);
    }


    /**
     *  Gets a <code> TopicList </code> corresponding to the given subject 
     *  genus <code> Type </code> which does not include topics of types 
     *  derived from the specified <code> Type. </code> In plenary mode, the 
     *  returned list contains all known topics or an error results. 
     *  Otherwise, the returned list may contain only those topics that are 
     *  accessible through this session. In both cases, the order of the set 
     *  is not specified. 
     *
     *  @param object osid_type_Type $topicGenusType a topic genus type 
     *  @return object osid_course_TopicList the returned <code> Topic list 
     *          </code> 
     *  @throws osid_NullArgumentException <code> topicGenusType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTopicsByGenusType(osid_type_Type $topicGenusType) {
    	if (strtolower($topicGenusType->getIdentifierNamespace()) != 'urn'
    		|| $topicGenusType->getAuthority() != 'middlebury.edu')
    	{
    		return new phpkit_EmptyList('osid_course_TopicList');
    	}
   		switch ($topicGenusType->getIdentifier()) {
   			case 'genera:topic/subject':
   				return $this->getSubjectTopics();
   			case 'genera:topic/department':
   				return $this->getDepartmentTopics();
   			case 'genera:topic/division':
   				return $this->getDivisionTopics();
   			case 'genera:topic/requirement':
   				return $this->getRequirementTopics();
   			default:
   				return new phpkit_EmptyList('osid_course_TopicList');
   		}
    }


    /**
     *  Gets a <code> TopicList </code> corresponding to the given topic genus 
     *  <code> Type </code> and include any additional topics with genus types 
     *  derived from the specified <code> Type. </code> In plenary mode, the 
     *  returned list contains all known topics or an error results. 
     *  Otherwise, the returned list may contain only those topics that are 
     *  accessible through this session. In both cases, the order of the set 
     *  is not specified. 
     *
     *  @param object osid_type_Type $topicGenusType a topic genus type 
     *  @return object osid_course_TopicList the returned <code> Topic list 
     *          </code> 
     *  @throws osid_NullArgumentException <code> topicGenusType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTopicsByParentGenusType(osid_type_Type $topicGenusType) {
    	return $this->getTopicsByGenusType($topicGenusType);
    }


    /**
     *  Gets a <code> TopicList </code> containing the given topic record 
     *  <code> Type. </code> In plenary mode, the returned list contains all 
     *  known topics or an error results. Otherwise, the returned list may 
     *  contain only those topics that are accessible through this session. In 
     *  both cases, the order of the set is not specified. 
     *
     *  @param object osid_type_Type $topicRecordType a topic record type 
     *  @return object osid_course_TopicList the returned <code> Topic list 
     *          </code> 
     *  @throws osid_NullArgumentException <code> topicRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTopicsByRecordType(osid_type_Type $topicRecordType) {
    	return new phpkit_EmptyList('osid_course_TopicList');
    }


    /**
     *  Gets all <code> Topics. </code> In plenary mode, the returned list 
     *  contains all known topics or an error results. Otherwise, the returned 
     *  list may contain only those topics that are accessible through this 
     *  session. In both cases, the order of the set is not specified. 
     *
     *  @return object osid_course_TopicList a list of <code> Topics </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTopics() {
    	$topicList = new phpkit_CombinedList('osid_course_TopicList');
    	$topicList->addList($this->getSubjectTopics());
    	$topicList->addList($this->getDepartmentTopics());
    	$topicList->addList($this->getDivisionTopics());
    	$topicList->addList($this->getRequirementTopics());
    	return $topicList;
    }

}
