<?php
/**
 * @since 10/14/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 *  <p>This is the query interface for searching courses. Each method match
 *  specifies an <code> AND </code> term while multiple invocations of the
 *  same method produce a nested <code> OR. </code> </p>.
 */
class banner_course_Course_Search_Query extends banner_course_AbstractQuery implements osid_course_CourseQuery, middlebury_course_Course_Search_TopicQueryRecord, middlebury_course_Course_Search_InstructorsQueryRecord, middlebury_course_Course_Search_LocationQueryRecord, middlebury_course_Course_Search_TermQueryRecord
{
    /**
     * Constructor.
     *
     * @param banner_course_CourseOffering_AbstractSession $session
     *
     * @return void
     *
     * @since 5/20/09
     */
    public function __construct(banner_course_AbstractSession $session)
    {
        parent::__construct($session);

        $this->addSupportedRecordType(new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:topic'));
        $this->addSupportedRecordType(new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors'));
        $this->addSupportedRecordType(new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:term'));
        $this->addSupportedRecordType(new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:location'));

        $this->wildcardStringMatchType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:search:wildcard');
        $this->booleanStringMatchType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:search:boolean');

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
     * @param string $keyword keyword to match
     * @param object osid_type_Type $stringMatchType the string match type
     * @param bool $match <code> true </code> for a positive match, <code>
     *                    false </code> for a negative match
     *
     * @throws osid_InvalidArgumentException <code> keyword is </code> not of
     *                                              <code> stringMatchType </code>
     * @throws osid_NullArgumentException <code>    keyword </code> or <code>
     *                                              stringMatchType </code> is <code> null </code>
     * @throws osid_UnsupportedException <code>
     *                                              supportsStringMatchType(stringMatchType) </code> is <code>
     *                                              false </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchKeyword($keyword, osid_type_Type $stringMatchType, $match)
    {
        throw new osid_UnimplementedException();
        if (!is_string($keyword)) {
            throw new osid_InvalidArgumentException("\$keyword '$keyword' must be a string.");
        }

        if ($stringMatchType->isEqual($this->booleanStringMatchType)
                || $stringMatchType->isEqual($this->wildcardStringMatchType)) {
            foreach (explode(' ', $keyword) as $param) {
                if ($match) {
                    $this->keywordString .= $param.' ';
                } else {
                    $this->keywordString .= '-'.preg_replace('/^[+-]*(.+)$/i', '\1', $param).' ';
                }
            }
        } else {
            throw new osid_UnsupportedException('The stringMatchType passed is not supported.');
        }
    }

    /**
     *  Adds a display name to match. Multiple display name matches can be
     *  added to perform a boolean <code> OR </code> among them.
     *  <br/><br/>.
     *
     * @param string $displayName display name to match
     * @param object osid_type_Type $stringMatchType the string match type
     * @param bool $match <code> true </code> for a positive match, <code>
     *                    false </code> for a negative match
     *
     * @throws osid_InvalidArgumentException <code> keyword is </code> not of
     *                                              <code> stringMatchType </code>
     * @throws osid_NullArgumentException <code>    displayName </code> or
     *                                              <code> stringMatchType </code> is <code> null </code>
     * @throws osid_UnsupportedException <code>
     *                                              supportsStringMatchType(stringMatchType) </code> is <code>
     *                                              false </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchDisplayName($displayName, osid_type_Type $stringMatchType, $match)
    {
        $this->matchNumber($displayName, $stringMatchType, $match);
    }

    /**
     *  Adds a description name to match. Multiple description matches can be
     *  added to perform a boolean <code> OR </code> among them.
     *
     * @param string $description description to match
     * @param object osid_type_Type $stringMatchType the string match type
     * @param bool $match <code> true </code> for a positive match, <code>
     *                    false </code> for a negative match
     *
     * @throws osid_InvalidArgumentException <code> keyword is </code> not of
     *                                              <code> stringMatchType </code>
     * @throws osid_NullArgumentException <code>    description </code> or
     *                                              <code> stringMatchType </code> is <code> null </code>
     * @throws osid_UnsupportedException <code>
     *                                              supportsStringMatchType(stringMatchType) </code> is <code>
     *                                              false </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchDescription($description, osid_type_Type $stringMatchType, $match)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Matches a description that has any value.
     *
     * @param bool $match <code> true </code> to match any description,
     *                    <code> false </code> to match descriptions with no values
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchAnyDescription($match)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Sets a <code> Type </code> for querying objects of a given genus. A
     *  genus type matches if the specified type is the same genus as the
     *  object genus type.
     *
     *  @param object osid_type_Type $genusType the object genus type
     * @param bool $match <code> true </code> for a positive match, <code>
     *                    false </code> for a negative match
     *
     * @throws osid_NullArgumentException <code> genusType </code> is <code>
     *                                           null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchGenusType(osid_type_Type $genusType, $match)
    {
        if ($genusType->isEqual(new phpkit_type_URNInetType('urn:inet:middlebury.edu:status-inactive'))) {
            $this->addClause('genus_type', "SCBCRSE_CSTA_CODE IN ('C', 'I', 'P', 'T', 'X')", [], $match);
        }
        if ($genusType->isEqual(new phpkit_type_URNInetType('urn:inet:middlebury.edu:status-active'))) {
            $this->addClause('genus_type', "SCBCRSE_CSTA_CODE NOT IN ('C', 'I', 'P', 'T', 'X')", [], $match);
        }
        if ($genusType->isEqual(new phpkit_type_URNInetType('urn:inet:osid.org:genera:none'))) {
            $this->addClause('genus_type', 'TRUE', [], $match);
        } else {
            $this->addClause('genus_type', 'FALSE', [], $match);
        }
    }

    /**
     *  Sets a <code> Type </code> for querying objects of a given genus. A
     *  genus type matches if the specified type is the same genus as the
     *  object or if the specified type is an ancestor of the object genus in
     *  a type hierarchy.
     *
     *  @param object osid_type_Type $genusType the object genus type
     * @param bool $match <code> true </code> for a positive match, <code>
     *                    false </code> for a negative match
     *
     * @throws osid_NullArgumentException <code> genusType </code> is <code>
     *                                           null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchParentGenusType(osid_type_Type $genusType, $match)
    {
        if ($genusType->isEqual(new phpkit_type_URNInetType('urn:inet:osid.org:genera:none'))) {
            $this->addClause('parent_genus_type', 'TRUE', [], $match);
        } else {
            $this->addClause('parent_genus_type', 'FALSE', [], $match);
        }
    }

    /**
     *  Sets a <code> Type </code> for querying objects having records
     *  implementing a given record type. This includes records of the same
     *  interface type as the one provided and records implementing an
     *  ancestor interface type in an interface hierarchy.
     *
     *  @param object osid_type_Type $recordType the record interface type
     * @param bool $match <code> true </code> for a positive match, <code>
     *                    false </code> for a negative match
     *
     * @throws osid_NullArgumentException <code> recordType </code> is <code>
     *                                           null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchRecordType(osid_type_Type $recordType, $match)
    {
        if ($this->implementsRecordType($recordType)) {
            $this->addClause('record_type', 'TRUE', [], $match);
        } else {
            $this->addClause('record_type', 'FALSE', [], $match);
        }
    }

    /*********************************************************
     * Methods from osid_course_CourseQuery
     *********************************************************/

    /**
     *  Adds a title for this query.
     *
     * @param string $title title string to match
     * @param object osid_type_Type $stringMatchType the string match type
     * @param bool $match <code> true </code> for a positive match, <code>
     *                    false </code> for a negative match
     *
     * @throws osid_InvalidArgumentException <code> title </code> not of
     *                                              <code> stringMatchType </code>
     * @throws osid_NullArgumentException <code>    title </code> or <code>
     *                                              stringMatchType </code> is <code> null </code>
     * @throws osid_UnsupportedException <code>
     *                                              supportsStringMatchType(stringMatchType) </code> is <code>
     *                                              false </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchTitle($title, osid_type_Type $stringMatchType, $match)
    {
        if (!is_string($title)) {
            throw new osid_InvalidArgumentException("\$title '$title' must be a string.");
        }

        if ($stringMatchType->isEqual($this->wildcardStringMatchType)) {
            $param = str_replace('*', '%', $title);
            $this->addClause('title', 'SCBCRSE_TITLE LIKE(?)', [$param], $match);
        } else {
            throw new osid_UnsupportedException('The stringMatchType passed is not supported.');
        }
    }

    /**
     *  Matches a title that has any value.
     *
     * @param bool $match <code> true </code> to match courses with any
     *                    title, <code> false </code> to match assets with no title
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchAnyTitle($match)
    {
        $this->addClause('title', 'SCBCRSE_TITLE IS NOT NULL', [], $match);
    }

    /**
     *  Adds a course number for this query.
     *
     * @param string $number course number string to match
     * @param object osid_type_Type $stringMatchType the string match type
     * @param bool $match <code> true </code> for a positive match, <code>
     *                    false </code> for a negative match
     *
     * @throws osid_InvalidArgumentException <code> number </code> not of
     *                                              <code> stringMatchType </code>
     * @throws osid_NullArgumentException <code>    number </code> or <code>
     *                                              stringMatchType </code> is <code> null </code>
     * @throws osid_UnsupportedException <code>
     *                                              supportsStringMatchType(stringMatchType) </code> is <code>
     *                                              false </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchNumber($number, osid_type_Type $stringMatchType,
        $match)
    {
        if (!is_string($number)) {
            throw new osid_InvalidArgumentException("\$number '$number' must be a string.");
        }

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
                $this->addClause('number', 'FALSE', [], $match);

                return;
            }

            $clauses = [];
            $params = [];

            // Subject
            if (isset($matches[1]) && $matches[1]) {
                $param = strtoupper(str_replace('*', '%', $matches[1]));
                if (isset($matches[2]) && $matches[2]) {
                    $param .= '%';
                }

                $clauses[] = 'SCBCRSE_SUBJ_CODE LIKE(?)';
                $params[] = $param;
            }

            // Number
            if (isset($matches[3]) && $matches[3]) {
                $param = str_replace('*', '%', $matches[3]);
                if ($matches[2]) {
                    $param = '%'.$param;
                }

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
            throw new osid_UnsupportedException('The type Authority: '.$stringMatchType->getAuthority().' IdNamespace: '.$stringMatchType->getIdentifierNamespace().' Id: '.$stringMatchType->getIdentifier().'  is not supported. Only Authority: '.$this->wildcardStringMatchType->getAuthority().' IdNamespace: '.$this->wildcardStringMatchType->getIdentifierNamespace().' Id: '.$this->wildcardStringMatchType->getIdentifier().' are supported');
        }
    }

    /**
     *  Matches a course number that has any value.
     *
     * @param bool $match <code> true </code> to match courses with any
     *                    number, <code> false </code> to match assets with no title
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchAnyNumber($match)
    {
        $this->addClause('number', 'TRUE', [], $match);
    }

    /**
     *  Matches courses with credits between the given numbers inclusive.
     *
     * @param float $min   low number
     * @param float $max   high number
     * @param bool  $match <code> true </code> for a positive match, <code>
     *                     false </code> for a negative match
     *
     * @throws osid_InvalidArgumentException <code> max </code> is less than
     *                                              <code> min </code>
     * @throws osid_NullArgumentException           null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchCredits($min, $max, $match)
    {
        if (!is_numeric($min)) {
            throw new osid_InvalidArgumentException("\$min must be a float. '$min' given.");
        }
        if (!is_numeric($max)) {
            throw new osid_InvalidArgumentException("\$max must be a float. '$max' given.");
        }
        $min = (float) $min;
        $max = (float) $max;
        if ($min < 0) {
            throw new osid_InvalidArgumentException('$min must be a float greater than or equal to zero.');
        }
        if ($min > $max) {
            throw new osid_InvalidArgumentException('$min must be less than or equal to $max.');
        }

        $this->addClause('credits', '(SCBCRSE_BILL_HR_HIGH >= ? AND SCBCRSE_BILL_HR_HIGH <= ?)', [$min, $max], $match);
    }

    /**
     *  Matches a course that has any credits assigned.
     *
     * @param bool $match <code> true </code> to match courses with any
     *                    credits, <code> false </code> to match assets with no credits
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchAnyCredits($match)
    {
        $this->addClause('credits', 'SCBCRSE_BILL_HR_HIGH > 0', [], $match);
    }

    /**
     *  Matches courses with the prerequisites informational string.
     *
     * @param string $prereqInfo prerequisite informational string to match
     * @param object osid_type_Type $stringMatchType the string match type
     * @param bool $match <code> true </code> for a positive match, <code>
     *                    false </code> for a negative match
     *
     * @throws osid_InvalidArgumentException <code> prereqInfo </code> not of
     *                                              <code> stringMatchType </code>
     * @throws osid_NullArgumentException <code>    prereqInfo </code> or <code>
     *                                              stringMatchType </code> is <code> null </code>
     * @throws osid_UnsupportedException <code>
     *                                              supportsStringMatchType(stringMatchType) </code> is <code>
     *                                              false </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchPrereqInfo($prereqInfo,
        osid_type_Type $stringMatchType, $match)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Matches a course that has any prerequisite information assigned.
     *
     * @param bool $match <code> true </code> to match courses with any
     *                    prerequisite information, <code> false </code> otherwise
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchAnyPrereqInfo($match)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Sets the catalog <code> Id </code> for this query to match courses
     *  that have a related course offering.
     *
     *  @param object osid_id_Id $courseOfferingId a course offering <code> Id
     *          </code>
     * @param bool $match <code> true </code> if a positive match, <code>
     *                    false </code> for negative match
     *
     * @throws osid_NullArgumentException <code> courseOfferingId </code> is
     *                                           <code> null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchCourseOfferingId(osid_id_Id $courseOfferingId, $match)
    {
        $this->addClause('course_offering_id', '(SSBSECT_TERM_CODE = ? AND SSBSECT_CRN = ?)',
            [$this->session->getTermCodeFromOfferingId($courseOfferingId),
                $this->session->getCrnFromOfferingId($courseOfferingId)],
            $match);
        $this->addTableJoin('LEFT JOIN SSBSECT ON (SCBCRSE_SUBJ_CODE = SSBSECT_SUBJ_CODE AND SCBCRSE_CRSE_NUMB = SSBSECT_CRSE_NUMB)');
        $this->addTableJoin('INNER JOIN course_catalog_college ON course_catalog_college.coll_code = SCBCRSE_COLL_CODE');
        $this->addTableJoin('INNER JOIN course_catalog ON course_catalog_college.catalog_id = course_catalog.catalog_id');

        $this->addClause('active_sections', 'SSBSECT_SSTS_CODE = ? AND (course_catalog.prnt_ind_to_exclude IS NULL OR SSBSECT_PRNT_IND != course_catalog.prnt_ind_to_exclude)', ['A'], true);
        $this->addTableJoin('INNER JOIN course_catalog_college ON course_catalog_college.coll_code = SCBCRSE_COLL_CODE');
        $this->addTableJoin('INNER JOIN course_catalog ON course_catalog_college.catalog_id = course_catalog.catalog_id');
    }

    /**
     *  Tests if a <code> CourseOfferingQuery </code> is available.
     *
     * @return boolean <code> true </code> if a course offering query
     *                        interface is available, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseOfferingQuery()
    {
        return false;
    }

    /**
     *  Gets the query interface for a course offering. Multiple retrievals
     *  produce a nested <code> OR </code> term.
     *
     * @return object osid_course_CourseOfferingQuery the course offering
     *                query
     *
     * @throws osid_UnimplementedException <code>
     *                                            supportsCourseOfferingQuery() </code> is <code> false </code>
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseOfferingQuery() </code> is <code> true.
     *              </code>
     */
    public function getCourseOfferingQuery()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Matches courses that have any course offering.
     *
     * @param bool $match <code> true </code> to match courses with any
     *                    course offering, <code> false </code> to match courses with no
     *                    course offering
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchAnyCourseOffering($match)
    {
        $this->addClause('course_offering_exists', 'SSBSECT_TERM_CODE IS NOT NULL',
            [],
            $match);
        $this->addTableJoin('LEFT JOIN SSBSECT ON (SCBCRSE_SUBJ_CODE = SSBSECT_SUBJ_CODE AND SCBCRSE_CRSE_NUMB = SSBSECT_CRSE_NUMB)');

        $this->addClause('active_sections', 'SSBSECT_SSTS_CODE = ? AND (course_catalog.prnt_ind_to_exclude IS NULL OR SSBSECT_PRNT_IND != course_catalog.prnt_ind_to_exclude)', ['A'], true);
        $this->addTableJoin('INNER JOIN course_catalog_college ON course_catalog_college.coll_code = SCBCRSE_COLL_CODE');
        $this->addTableJoin('INNER JOIN course_catalog ON course_catalog_college.catalog_id = course_catalog.catalog_id');
    }

    /**
     *  Sets the course catalog <code> Id </code> for this query to match
     *  courses assigned to course catalogs.
     *
     *  @param object osid_id_Id $courseCatalogId the course catalog <code> Id
     *          </code>
     * @param bool $match <code> true </code> for a positive match, <code>
     *                    false </code> for a negative match
     *
     * @throws osid_NullArgumentException <code> courseCatalogId </code> is
     *                                           <code> null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchCourseCatalogId(osid_id_Id $courseCatalogId, $match)
    {
        $this->addClause('course_catalog_id', 'SCBCRSE_COLL_CODE IN (
			SELECT
				coll_code
			FROM
				course_catalog_college
			WHERE
				catalog_id = ?)',
            [$this->session->getDatabaseIdString($courseCatalogId, 'catalog.')],
            $match);
    }

    /**
     *  Tests if a <code> CourseCatalogQuery </code> is available.
     *
     * @return boolean <code> true </code> if a course catalog query
     *                        interface is available, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseCatalogQuery()
    {
        return false;
    }

    /**
     *  Gets the query interface for a course catalog. Multiple retrievals
     *  produce a nested <code> OR </code> term.
     *
     * @return object osid_course_CourseCatalogQuery the course catalog query
     *
     * @throws osid_UnimplementedException <code>
     *                                            supportsCourseCatalogQuery() </code> is <code> false </code>
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseCatalogQuery() </code> is <code> true.
     *              </code>
     */
    public function getCourseCatalogQuery()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the record query interface corresponding to the given <code>
     *  Course </code> record <code> Type. </code> Multiple record retrievals
     *  produce a nested <code> OR </code> term.
     *
     *  @param object osid_type_Type $courseRecordType a course record type
     *
     * @return object osid_course_CourseQueryRecord the course query record
     *
     * @throws osid_NullArgumentException <code> courseRecordType </code> is
     *                                           <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure occurred
     * @throws osid_UnsupportedException <code>
     *                                           hasRecordType(courseRecordType) </code> is <code> false
     *                                           </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseQueryRecord(osid_type_Type $courseRecordType)
    {
        if (!$this->implementsRecordType($courseRecordType)) {
            throw new osid_UnsupportedException('The record type passed is not supported.');
        }

        return $this;
    }

    /*********************************************************
     * Methods from osid_course_CourseQueryRecord
     *********************************************************/

    /**
     *  Gets the <code> CourseQuery </code> from which this record originated.
     *
     * @return object osid_course_CourseQuery the course query
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseQuery()
    {
        return $this;
    }

    /*********************************************************
     * Methods from middlebury_course_Course_Search_TopicQueryRecord
     *********************************************************/

    /**
     *  Sets the topic <code> Id </code> for this query to match
     *  courses that have a related topic.
     *
     *  @param object osid_id_Id $topicId a topic <code> Id </code>
     * @param bool $match <code> true </code> if a positive match, <code>
     *                    false </code> for negative match
     *
     * @throws osid_NullArgumentException <code> topicId </code> is <code>
     *                                           null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchTopicId(osid_id_Id $topicId, $match)
    {
        $type = $this->session->getTopicLookupSession()->getTopicType($topicId);
        $value = $this->session->getTopicLookupSession()->getTopicValue($topicId);
        switch ($type) {
            case 'subject':
                $this->addClause('subject_topic_id', 'SCBCRSE_SUBJ_CODE = ?', [$value], $match);

                return;
            case 'department':
                $this->addClause('department_topic_id', 'SCBCRSE_DEPT_CODE = ?', [$value], $match);

                return;
            case 'division':
                $this->addClause('division_topic_id', 'SCBCRSE_DIVS_CODE = ?', [$value], $match);

                return;
            case 'requirement':
                $this->addClause('requirement_topic_id', 'SCRATTR_ATTR_CODE = ?', [$value], $match);
                $this->addTableJoin('LEFT JOIN scrattr_recent ON (SCBCRSE_SUBJ_CODE = SCRATTR_SUBJ_CODE AND SCBCRSE_CRSE_NUMB = SCRATTR_CRSE_NUMB)');

                return;
            case 'level':
                $this->addClause('level_topic_id', 'SCRLEVL_LEVL_CODE = ?', [$value], $match);
                $this->addTableJoin('LEFT JOIN scrlevl_recent ON (SCBCRSE_SUBJ_CODE = SCRLEVL_SUBJ_CODE AND SCBCRSE_CRSE_NUMB = SCRLEVL_CRSE_NUMB)');

                return;
            default:
                $this->addClause('topic_id', 'FALSE', [], $match);
        }
    }

    /**
     *  Tests if an <code> TopicQuery </code> is available.
     *
     * @return boolean <code> true </code> if a topic query interface is
     *                        available, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsTopicQuery()
    {
        return false;
    }

    /**
     *  Gets the query interface for an topic. Multiple retrievals produce a
     *  nested <code> OR </code> term.
     *
     * @return object osid_course_TopicQuery the topic query
     *
     * @throws osid_UnimplementedException <code> supportsTopicQuery()
     *                                            </code> is <code> false </code>
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTopicQuery() </code> is <code> true. </code>
     */
    public function getTopicQuery()
    {
        throw new osid_UnimplementedException();
    }

    /*********************************************************
     * Methods from middlebury_course_Course_Search_InstructorsQueryRecord
     *********************************************************/

    /**
     *  Sets the instructor <code> Id </code> for this query to match courses
     *  that have a related instructor.
     *
     *  @param object osid_id_Id $instructorId an instructor <code> Id </code>
     * @param bool $match <code> true </code> if a positive match, <code>
     *                    false </code> for negative match
     *
     * @throws osid_NullArgumentException <code> instructorId </code> is <code>
     *                                           null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchInstructorId(osid_id_Id $instructorId, $match)
    {
        $this->addClause('instructor_id', 'WEB_ID = ?', [$this->session->getDatabaseIdString($instructorId, 'resource.person.')], $match);
        $this->addTableJoin('LEFT JOIN SSBSECT ON (SCBCRSE_SUBJ_CODE = SSBSECT_SUBJ_CODE AND SCBCRSE_CRSE_NUMB = SSBSECT_CRSE_NUMB)');
        $this->addTableJoin('LEFT JOIN SYVINST ON (SYVINST_TERM_CODE = SSBSECT_TERM_CODE AND SYVINST_CRN = SSBSECT_CRN)');

        $this->addClause('active_sections', 'SSBSECT_SSTS_CODE = ? AND (course_catalog.prnt_ind_to_exclude IS NULL OR SSBSECT_PRNT_IND != course_catalog.prnt_ind_to_exclude)', ['A'], true);
        $this->addTableJoin('INNER JOIN course_catalog_college ON course_catalog_college.coll_code = SCBCRSE_COLL_CODE');
        $this->addTableJoin('INNER JOIN course_catalog ON course_catalog_college.catalog_id = course_catalog.catalog_id');
    }

    /**
     *  Tests if an <code> InstructorQuery </code> is available.
     *
     * @return boolean <code> true </code> if a instructor query interface is
     *                        available, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsInstructorQuery()
    {
        return false;
    }

    /**
     *  Gets the query interface for an instructor. Multiple retrievals produce a
     *  nested <code> OR </code> term.
     *
     * @return object osid_resource_ResourceQuery the instructor query
     *
     * @throws osid_UnimplementedException <code> supportsInstructorQuery()
     *                                            </code> is <code> false </code>
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsInstructorQuery() </code> is <code> true. </code>
     */
    public function getInstructorQuery()
    {
        throw new osid_UnimplementedException();
    }

    /*********************************************************
     * Methods from middlebury_course_Course_Search_TermQueryRecord
     *********************************************************/

    /**
     *  Sets the term <code> Id </code> for this query to match courses
     *  that have a related term.
     *
     *  @param object osid_id_Id $termId an term <code> Id </code>
     * @param bool $match <code> true </code> if a positive match, <code>
     *                    false </code> for negative match
     *
     * @throws osid_NullArgumentException <code> termId </code> is <code>
     *                                           null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchTermId(osid_id_Id $termId, $match)
    {
        $this->addClause('term_id', 'SSBSECT_TERM_CODE = ?', [$this->session->getDatabaseIdString($termId, 'term.')], $match);
        $this->addTableJoin('LEFT JOIN SSBSECT ON (SCBCRSE_SUBJ_CODE = SSBSECT_SUBJ_CODE AND SCBCRSE_CRSE_NUMB = SSBSECT_CRSE_NUMB)');

        $this->addClause('active_sections', 'SSBSECT_SSTS_CODE = ? AND (course_catalog.prnt_ind_to_exclude IS NULL OR SSBSECT_PRNT_IND != course_catalog.prnt_ind_to_exclude)', ['A'], true);
        $this->addTableJoin('INNER JOIN course_catalog_college ON course_catalog_college.coll_code = SCBCRSE_COLL_CODE');
        $this->addTableJoin('INNER JOIN course_catalog ON course_catalog_college.catalog_id = course_catalog.catalog_id');
    }

    /**
     *  Tests if an <code> TermQuery </code> is available.
     *
     * @return boolean <code> true </code> if a term query interface is
     *                        available, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsTermQuery()
    {
        return false;
    }

    /**
     *  Gets the query interface for an term. Multiple retrievals produce a
     *  nested <code> OR </code> term.
     *
     * @return object osid_resource_ResourceQuery the term query
     *
     * @throws osid_UnimplementedException <code> supportsTermQuery()
     *                                            </code> is <code> false </code>
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTermQuery() </code> is <code> true. </code>
     */
    public function getTermQuery()
    {
        throw new osid_UnimplementedException();
    }

    /*********************************************************
     * Methods from middlebury_course_Course_Search_LocationQueryRecord
     *********************************************************/

    /**
     *  Sets the location resource <code> Id </code> for this query to match courses
     *  that have a related location resource.
     *
     *  @param object osid_id_Id $resourceId A location resource <code> Id </code>
     * @param bool $match <code> true </code> if a positive match, <code>
     *                    false </code> for negative match
     *
     * @throws osid_NullArgumentException <code> resourceId </code> is <code>
     *                                           null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchLocationId(osid_id_Id $instructorId, $match)
    {
        $this->addClause('location_id', 'SSBSECT_CAMP_CODE = ?', [$this->session->getDatabaseIdString($instructorId, 'resource.place.campus.')], $match);
        $this->addTableJoin('LEFT JOIN SSBSECT ON (SCBCRSE_SUBJ_CODE = SSBSECT_SUBJ_CODE AND SCBCRSE_CRSE_NUMB = SSBSECT_CRSE_NUMB)');

        $this->addClause('active_sections', 'SSBSECT_SSTS_CODE = ? AND (course_catalog.prnt_ind_to_exclude IS NULL OR SSBSECT_PRNT_IND != course_catalog.prnt_ind_to_exclude)', ['A'], true);
        $this->addTableJoin('INNER JOIN course_catalog_college ON course_catalog_college.coll_code = SCBCRSE_COLL_CODE');
        $this->addTableJoin('INNER JOIN course_catalog ON course_catalog_college.catalog_id = course_catalog.catalog_id');
    }

    /**
     *  Tests if a <code> LocationQuery </code> is available.
     *
     * @return boolean <code> true </code> if a location query interface is
     *                        available, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsLocationQuery()
    {
        return false;
    }

    /**
     *  Gets the query interface for a location. Multiple retrievals produce a
     *  nested <code> OR </code> term.
     *
     * @return object osid_resource_ResourceQuery the location query
     *
     * @throws osid_UnimplementedException <code> supportsLocationQuery()
     *                                            </code> is <code> false </code>
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsLocationQuery() </code> is <code> true. </code>
     */
    public function getLocationQuery()
    {
        throw new osid_UnimplementedException();
    }
}
