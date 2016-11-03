<?php
/**
 * @since 5/27/09
 * @package banner.course
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * A List for retrieving topics based on search results
 *
 * @since 5/27/09
 * @package banner.course
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class banner_course_Topic_Search_List
	extends banner_course_Topic_AbstractList
	implements osid_course_TopicList,
	osid_course_TopicSearchResults
{
	/**
	 * Constructor
	 *
	 * @param PDO $db
	 * @param banner_course_SessionInterface $session
	 * @param osid_id_Id $catalogDatabaseId
	 * @param osid_course_TopicQuery $topicQuery the search
	 *          query
	 * @param osid_course_TopicSearch $topicSearch
	 * @return void
	 * @access public
	 * @since 4/13/09
	 */
	public function __construct (PDO $db, banner_course_SessionInterface $session, osid_id_Id $catalogId, osid_course_TopicQuery $topicQuery, osid_course_TopicSearch $topicSearch) {
		$this->db = $db;
		$this->topicQuery = $topicQuery;

		$this->requirementWhere = $topicQuery->getRequirementWhereClause();
		$this->divisionWhere = $topicQuery->getDivisionWhereClause();
		$this->departmentWhere = $topicQuery->getDepartmentWhereClause();
		$this->subjectWhere = $topicQuery->getSubjectWhereClause();
		$this->levelWhere = $topicQuery->getLevelWhereClause();
		$this->blockWhere = $topicQuery->getBlockWhereClause();

// 		$searchWhere = $topicSearch->getWhereClause();
// 		if (strlen($searchWhere)) {
// 			$this->requirementWhere .= "\n\tAND ".$searchWhere;
// 			$this->divisionWhere .= "\n\tAND ".$searchWhere;
// 			$this->departmentWhere .= "\n\tAND ".$searchWhere;
// 			$this->subjectWhere .= "\n\tAND ".$searchWhere;
// 			$this->blockWhere .= "\n\tAND ".$searchWhere;
// 		}

		$orderTerms = $topicSearch->getOrderByTerms();
		if (count($orderTerms))
			$this->orderBy = 'ORDER BY '.(implode(', ', $orderTerms));
		else
			$this->orderBy = 'ORDER BY id ASC';
		$this->limit = $topicSearch->getLimitClause();

		$this->parameters = array();

		if ($this->includeRequirements()) {
			foreach ($topicQuery->getRequirementParameters() as $i => $val) {
				if (is_int($i)) {
					$name = ':req_search_'.$i;
					$this->parameters[$name] = $val;
					$this->requirementWhere = preg_replace('/\?/', $name, $this->requirementWhere, 1);
				} else if (preg_match('/^:[a-z0-9_]+$/i', $i)) {
					$this->parameters[$i] = $val;
				} else {
					throw new osid_OperationFailedException("Invalid parameter name '$i'. Must be an integer or of the ':param_name' form.");
				}
			}
		}

		if ($this->includeLevels()) {
			foreach ($topicQuery->getLevelParameters() as $i => $val) {
				if (is_int($i)) {
					$name = ':level_search_'.$i;
					$this->parameters[$name] = $val;
					$this->levelWhere = preg_replace('/\?/', $name, $this->levelWhere, 1);
				} else if (preg_match('/^:[a-z0-9_]+$/i', $i)) {
					$this->parameters[$i] = $val;
				} else {
					throw new osid_OperationFailedException("Invalid parameter name '$i'. Must be an integer or of the ':param_name' form.");
				}
			}
		}

		if ($this->includeBlocks()) {
			foreach ($topicQuery->getBlockParameters() as $i => $val) {
				if (is_int($i)) {
					$name = ':req_search_'.$i;
					$this->parameters[$name] = $val;
					$this->blockWhere = preg_replace('/\?/', $name, $this->blockWhere, 1);
				} else if (preg_match('/^:[a-z0-9_]+$/i', $i)) {
					$this->parameters[$i] = $val;
				} else {
					throw new osid_OperationFailedException("Invalid parameter name '$i'. Must be an integer or of the ':param_name' form.");
				}
			}
		}

		if ($this->includeDivisions()) {
			foreach ($topicQuery->getDivisionParameters() as $i => $val) {
				if (is_int($i)) {
					$name = ':div_search_'.$i;
					$this->parameters[$name] = $val;
					$this->divisionWhere = preg_replace('/\?/', $name, $this->divisionWhere, 1);
				} else if (preg_match('/^:[a-z0-9_]+$/i', $i)) {
					$this->parameters[$i] = $val;
				} else {
					throw new osid_OperationFailedException("Invalid parameter name '$i'. Must be an integer or of the ':param_name' form.");
				}
			}
		}

		if ($this->includeDepartments()) {
			foreach ($topicQuery->getDepartmentParameters() as $i => $val) {
				if (is_int($i)) {
					$name = ':dep_search_'.$i;
					$this->parameters[$name] = $val;
					$this->departmentWhere = preg_replace('/\?/', $name, $this->departmentWhere, 1);
				} else if (preg_match('/^:[a-z0-9_]+$/i', $i)) {
					$this->parameters[$i] = $val;
				} else {
					throw new osid_OperationFailedException("Invalid parameter name '$i'. Must be an integer or of the ':param_name' form.");
				}
			}
		}

		if ($this->includeSubjects()) {
			foreach ($topicQuery->getSubjectParameters() as $i => $val) {
				if (is_int($i)) {
					$name = ':sub_search_'.$i;
					$this->parameters[$name] = $val;
					$this->subjectWhere = preg_replace('/\?/', $name, $this->subjectWhere, 1);
				} else if (preg_match('/^:[a-z0-9_]+$/i', $i)) {
					$this->parameters[$i] = $val;
				} else {
					throw new osid_OperationFailedException("Invalid parameter name '$i'. Must be an integer or of the ':param_name' form.");
				}
			}
		}

// 		foreach ($topicSearch->getParameters() as $i => $val) {
// 			if (is_int($i)) {
// 				$name = ':search_search_'.$i;
// 				$this->parameters[$name] = $val;
// 				$this->requirementWhere = preg_replace('/\?/', $name, $this->requirementWhere, 1);
// 				$this->divisionWhere = preg_replace('/\?/', $name, $this->divisionWhere, 1);
// 				$this->departmentWhere = preg_replace('/\?/', $name, $this->departmentWhere, 1);
// 				$this->subjectWhere = preg_replace('/\?/', $name, $this->subjectWhere, 1);
// 				$this->blockWhere = preg_replace('/\?/', $name, $this->blockWhere, 1);
// 			} else if (preg_match('/^:[a-z0-9_]+$/i', $i)) {
// 				$this->parameters[$i] = $val;
// 			} else {
// 				throw new osid_OperationFailedException("Invalid parameter name '$i'. Must be an integer or of the ':param_name' form.");
// 			}
// 		}

// 		print $this->debug();
		parent::__construct($db, $session, $catalogId);
	}


	/**
	 * Answer the ORDER BY clause to use
	 *
	 * @return string
	 * @access protected
	 * @since 5/28/09
	 */
	protected function getOrderByClause () {
		if ($this->orderBy)
			return $this->orderBy;
		else
			return parent::getOrderByClause();
	}

	/**
	 * Answer the LIMIT clause to use
	 *
	 * @return string
	 * @access protected
	 * @since 5/28/09
	 */
	protected function getLimitClause () {
		if ($this->limit)
			return $this->limit;
		else
			return parent::getLimitClause();
	}

	/**
	 * Answer the input parameters
	 *
	 * @return array
	 * @access protected
	 * @since 4/17/09
	 */
	protected function getInputParameters () {
		return $this->parameters;
	}

	/**
	 * Answer additional where terms. E.g. 'SSRMEET_MON_DAY = true AND SSRMEET_TUE_DAY = false'
	 *
	 * @return array
	 * @access protected
	 * @since 4/17/09
	 */
	protected function getRequirementWhereTerms() {
		return $this->requirementWhere;
	}

	/**
	 * Answer additional where terms. E.g. 'SSRMEET_MON_DAY = true AND SSRMEET_TUE_DAY = false'
	 *
	 * @return array
	 * @access protected
	 * @since 4/17/09
	 */
	protected function getLevelWhereTerms() {
		return $this->levelWhere;
	}

	/**
	 * Answer additional where terms. E.g. 'SSRMEET_MON_DAY = true AND SSRMEET_TUE_DAY = false'
	 *
	 * @return array
	 * @access protected
	 * @since 4/17/09
	 */
	protected function getBlockWhereTerms() {
		return $this->blockWhere;
	}

	/**
	 * Answer additional where terms. E.g. 'SSRMEET_MON_DAY = true AND SSRMEET_TUE_DAY = false'
	 *
	 * @return array
	 * @access protected
	 * @since 4/17/09
	 */
	protected function getDivisionWhereTerms() {
		return $this->divisionWhere;
	}

	/**
	 * Answer additional where terms. E.g. 'SSRMEET_MON_DAY = true AND SSRMEET_TUE_DAY = false'
	 *
	 * @return array
	 * @access protected
	 * @since 4/17/09
	 */
	protected function getDepartmentWhereTerms() {
		return $this->departmentWhere;
	}

	/**
	 * Answer additional where terms. E.g. 'SSRMEET_MON_DAY = true AND SSRMEET_TUE_DAY = false'
	 *
	 * @return array
	 * @access protected
	 * @since 4/17/09
	 */
	protected function getSubjectWhereTerms() {
		return $this->subjectWhere;
	}

	/**
	 * Answer true if requirement topics should be included
	 *
	 * @return boolean
	 * @access protected
	 * @since 6/12/09
	 */
	protected function includeRequirements () {
		return $this->topicQuery->includeRequirements();
	}

	/**
	 * Answer true if level topics should be included
	 *
	 * @return boolean
	 * @access protected
	 * @since 6/12/09
	 */
	protected function includeLevels () {
		return $this->topicQuery->includeLevels();
	}

	/**
	 * Answer true if block topics should be included
	 *
	 * @return boolean
	 * @access protected
	 * @since 6/12/09
	 */
	protected function includeBlocks () {
		return $this->topicQuery->includeBlocks();
	}

	/**
	 * Answer true if division topics should be included
	 *
	 * @return boolean
	 * @access protected
	 * @since 6/12/09
	 */
	protected function includeDivisions () {
		return $this->topicQuery->includeDivisions();
	}

	/**
	 * Answer true if department topics should be included
	 *
	 * @return boolean
	 * @access protected
	 * @since 6/12/09
	 */
	protected function includeDepartments () {
		return $this->topicQuery->includeDepartments();
	}

	/**
	 * Answer true if subject topics should be included
	 *
	 * @return boolean
	 * @access protected
	 * @since 6/12/09
	 */
	protected function includeSubjects () {
		return $this->topicQuery->includeSubjects();
	}

/*********************************************************
 * Methods from osid_course_CourseOfferingSearchResults
 *********************************************************/

	/**
	 *  Returns the size of a result set from a search query. This number
	 *  serves as an estimate to provide feedback for refining search queries
	 *  and may not be the number of elements available through an <code>
	 *  OsidList. </code>
	 *
	 *  @return integer the result size
	 *  @compliance mandatory This method must be implemented.
	 */
	public function getResultSize() {
		if (!isset($this->resultSize)) {
			if ($this->limit) {
				$tmpLimit = $this->limit;
				$this->limit = null;

				$stmt = $this->db->prepare($this->getCountQuery($this->getQuery()));
				$stmt->execute($this->getAllInputParameters());
				$this->resultSize = intval($stmt->fetchColumn());
				$stmt->closeCursor();

				$this->limit = $tmpLimit;
			} else {
				$this->resultSize = $this->available();
			}

		}
		return $this->resultSize;
	}


	/**
	 *  Gets the search record types available in this search. A record <code>
	 *  Type </code> explicitly indicates the specification of an interface to
	 *  the record. A record may or may not inherit other record interfaces
	 *  through interface inheritance in which case support of a record type
	 *  may not be explicit in the returned list. Interoperability with the
	 *  typed interface to this object should be performed through <code>
	 *  hasSearchRecordType(). </code>
	 *
	 *  @return object osid_type_TypeList the search record types available
	 *          through this object
	 *  @compliance mandatory This method must be implemented.
	 */
	public function getSearchRecordTypes() {
		return new phpkit_EmptyList('osid_type_TypeList');
	}


	/**
	 *  Tests if this search results supports the given record <code> Type.
	 *  </code> The given record type may be supported by the object through
	 *  interface/type inheritence. This method should be checked before
	 *  retrieving the record interface.
	 *
	 *  @param object osid_type_Type $searchRecordType a type
	 *  @return boolean <code> true </code> if a search record the given
	 *          record <code> Type </code> is available, <code> false </code>
	 *          otherwise
	 *  @throws osid_NullArgumentException <code> searchRecordType </code> is
	 *          <code> null </code>
	 *  @compliance mandatory This method must be implemented.
	 */
	public function hasSearchRecordType(osid_type_Type $searchRecordType) {
		return false;
	}


	/**
	 *  Gets a list of properties. Properties provide a means for applications
	 *  to display a representation of the contents of a search record without
	 *  understanding its <code> Type </code> specification. Applications
	 *  needing to examine a specific property should use the extension
	 *  interface defined by its <code> Type. </code>
	 *
	 *  @return object osid_PropertyList a list of properties
	 *  @throws osid_OperationFailedException unable to complete request
	 *  @throws osid_PermissionDeniedException an authorization failure
	 *          occurred
	 *  @compliance mandatory This method must be implemented.
	 */
	public function getProperties() {
		return new phpkit_EmptyList('osid_PropertyList');
	}


	/**
	 *  Gets a list of properties corresponding to the specified search record
	 *  type. Properties provide a means for applications to display a
	 *  representation of the contents of a search record without
	 *  understanding its record interface specification. Applications needing
	 *  to examine a specific propertyshould use the methods defined by the
	 *  search record <code> Type. </code> The resulting set includes
	 *  properties specified by parents of the record <code> type </code> in
	 *  the case a record's interface extends another.
	 *
	 *  @param object osid_type_Type $searchRecordType the search record type
	 *          corresponding to the properties set to retrieve
	 *  @return object osid_PropertyList a list of properties
	 *  @throws osid_NullArgumentException <code> searchRecordType </code> is
	 *          <code> null </code>
	 *  @throws osid_OperationFailedException unable to complete request
	 *  @throws osid_PermissionDeniedException an authorization failure
	 *          occurred
	 *  @throws osid_UnsupportedException <code>
	 *          hasSearchRecordType(searchRecordType) </code> is <code> false
	 *          </code>
	 *  @compliance mandatory This method must be implemented.
	 */
	public function getPropertiesBySearchRecordType(osid_type_Type $searchRecordType) {
		throw new osid_UnsupportedException('The search-record-type passed is not supported.');
	}

	/**
	 *  Gets the topic list resulting from a search.
	 *
	 *  @return object osid_course_TopicList the topic list
	 *  @compliance mandatory This method must be implemented.
	 */
	public function getTopics() {
		return $this;
	}


	/**
	 *  Gets the record corresponding to the given topic search record <code>
	 *  Type. </code> This method must be used to retrieve an object
	 *  implementing the requested record interface along with all of its
	 *  ancestor interfaces.
	 *
	 *  @param object osid_type_Type $topicSearchRecordType a topic search
	 *          record type
	 *  @return object osid_course_TopicSearchResultsRecord the topic search
	 *          interface
	 *  @throws osid_NullArgumentException <code> topicSearchRecordType
	 *          </code> is <code> null </code>
	 *  @throws osid_OperationFailedException unable to complete request
	 *  @throws osid_PermissionDeniedException authorization failure occurred
	 *  @throws osid_UnsupportedException <code>
	 *          hasSearchRecordType(topicSearchRecordType) </code> is <code>
	 *          false </code>
	 *  @compliance mandatory This method must be implemented.
	 */
	public function getTopicSearchResultsRecord(osid_type_Type $topicSearchRecordType) {
		throw new osid_UnsupportedException('The search-record-type passed is not supported.');
	}
}
