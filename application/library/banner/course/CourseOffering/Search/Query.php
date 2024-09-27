<?php
/**
 * @since 5/04/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 *  <p>This is the query interface for searching course offerings. Each method
 *  match specifies an <code> AND </code> term while multiple invocations of
 *  the same method produce a nested <code> OR. </code> </p>.
 */
class banner_course_CourseOffering_Search_Query extends banner_course_AbstractQuery implements osid_course_CourseOfferingQuery, osid_course_CourseOfferingQueryRecord, middlebury_course_CourseOffering_Search_InstructorsQueryRecord, middlebury_course_CourseOffering_Search_WeeklyScheduleQueryRecord, middlebury_course_CourseOffering_Search_EnrollmentQueryRecord
{
    /**
     * Constructor.
     *
     * @return void
     *
     * @since 5/20/09
     */
    public function __construct(banner_course_CourseOffering_AbstractSession $session)
    {
        parent::__construct($session);

        $this->addSupportedRecordType(new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors'));
        $this->addSupportedRecordType(new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:weekly_schedule'));
        $this->addSupportedRecordType(new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:enrollment'));

        $this->wildcardStringMatchType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:search:wildcard');
        $this->booleanStringMatchType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:search:boolean');

        $this->addStringMatchType($this->wildcardStringMatchType);
        $this->addStringMatchType($this->booleanStringMatchType);

        $this->keywordString = '';
    }

    /**
     * Answer the clause sets.
     *
     * @return array
     *
     * @since 6/11/09
     */
    protected function getClauseSets()
    {
        $clauseSets = parent::getClauseSets();
        if (strlen($this->keywordString)) {
            $clauseSets[] = ['MATCH (SSBSECT_fulltext) AGAINST (:co_keyword_param IN BOOLEAN MODE)'];
        }

        return $clauseSets;
    }

    /**
     * Answer the array of parameters that matches our current state.
     *
     * @return array
     *
     * @since 5/20/09
     */
    public function getParameters()
    {
        $params = parent::getParameters();

        if (strlen($this->keywordString)) {
            $params[':co_keyword_param'] = trim($this->keywordString);
            $params[':co_relevence_param'] = trim($this->keywordString);
        }

        return $params;
    }

    /**
     * Answer an array of additional columns to return.
     *
     * @return array
     *
     * @since 6/10/09
     */
    public function getAdditionalColumns()
    {
        $columns = parent::getAdditionalColumns();
        if (strlen($this->keywordString)) {
            $columns[] = 'MATCH (SSBSECT_fulltext) AGAINST (:co_relevence_param IN BOOLEAN MODE) AS relevence';
        }

        return $columns;
    }

    /**
     * Answer an array column/direction terms for a SQL ORDER BY clause.
     *
     * @return array
     *
     * @since 5/28/09
     */
    public function getOrderByTerms()
    {
        $parts = parent::getOrderByTerms();
        if (strlen($this->keywordString)) {
            $parts[] = 'relevence DESC';
        }

        return $parts;
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
        try {
            $schdCode = $this->session->getScheduleCodeFromGenusType($genusType);
            $this->addClause('genus_type', 'SSBSECT_SCHD_CODE = ?', [$schdCode], $match);
        } catch (osid_NotFoundException $e) {
            $this->addClause('genus_type', 'FALSE', [], $match);
        }
    }

    /**
     * Answer the schedule code from a genus type.
     *
     * @return mixed string or null
     *
     * @since 5/27/09
     */
    private function getGenusTypeCode(osid_type_Type $genusType)
    {
        if ('urn' != strtolower($genusType->getIdentifierNamespace())) {
            return null;
        } elseif (strtolower($genusType->getAuthority()) != strtolower($this->session->getIdAuthority())) {
            return null;
        }

        if (!preg_match('/^genera:offering\.([a-z]+)$/i', $genusType->getIdentifier(), $matches)) {
            return null;
        }

        return $matches[1];
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
     * Methods From osid_course_CourseOfferingQueryRecord
     *********************************************************/

    /**
     *  Gets the <code> CourseOfferingQuery </code> from which this record
     *  originated.
     *
     * @return object osid_course_CourseOfferingQuery the course offering
     *                query
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingQuery()
    {
        return $this;
    }

    /*********************************************************
     * Methods from osid_course_CourseOfferingQuery
     *********************************************************/

    /**
     *  Gets the record query interface corresponding to the given <code>
     *  CourseOffering </code> record <code> Type. </code> Multiple record
     *  retrievals produce a nested <code> OR </code> term.
     *
     *  @param object osid_type_Type $courseOfferingRecordType a course
     *          offering record type
     *
     * @return object osid_course_CourseOfferingQueryRecord the course
     *                offering query record
     *
     * @throws osid_NullArgumentException <code> courseOfferingRecordType
     *                                           </code> is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure occurred
     * @throws osid_UnsupportedException <code>
     *                                           hasRecordType(courseOfferingRecordType) </code> is <code>
     *                                           false </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingQueryRecord(osid_type_Type $courseOfferingRecordType)
    {
        if (!$this->implementsRecordType($courseOfferingRecordType)) {
            throw new osid_UnsupportedException('The record type passed is not supported.');
        }

        return $this;
    }

    /*********************************************************
     * Matching methods from osid_course_CourseOfferingQuery
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
            $this->addClause('title', 'section_title LIKE(?)', [$param], $match);
        } else {
            throw new osid_UnsupportedException('The stringMatchType passed is not supported.');
        }
    }

    /**
     *  Matches a title that has any value.
     *
     * @param bool $match <code> true </code> to match course offerings
     *                    with any title, <code> false </code> to match course offerings
     *                    with no title
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchAnyTitle($match)
    {
        $this->addClause('title', 'section_title IS NOT NULL', [], $match);
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

	# 4 SEQUENCE NUMBER
	([a-z])?

	(?: -
		# 5 term designation
		([a-z])
		# 6 year designation
		([0-9]{2})
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

                $clauses[] = 'SSBSECT_SUBJ_CODE LIKE(?)';
                $params[] = $param;
            }

            // Number
            if (isset($matches[3]) && $matches[3]) {
                $param = str_replace('*', '%', $matches[3]);
                if ($matches[2]) {
                    $param = '%'.$param;
                }

                $clauses[] = 'SSBSECT_CRSE_NUMB LIKE(?)';
                $params[] = $param;
            }

            // Sequence number
            if (isset($matches[4]) && $matches[4]) {
                $param = strtoupper($matches[4]);

                $clauses[] = 'SSBSECT_SEQ_NUMB = ?';
                $params[] = $param;
            }

            // Term designation
            if (isset($matches[5]) && $matches[5]) {
                $param = strtoupper($matches[5]);

                $clauses[] = 'term_display_label = ?';
                $params[] = $param;
            }

            // Year designation
            if (isset($matches[6]) && $matches[6]) {
                $param = strtoupper($matches[6]);

                $clauses[] = 'SSBSECT_TERM_CODE LIKE(?)';
                $params[] = '20'.$param.'%';
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
     * @param bool $match <code> true </code> to match course offerings
     *                    with any number, <code> false </code> to match course
     *                    offerings with no number
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

        $this->addClause('credits', '(SSBSECT_CREDIT_HRS >= ? AND SSBSECT_CREDIT_HRS <= ?)', [$min, $max], $match);
    }

    /**
     *  Matches a course that has any credits assigned.
     *
     * @param bool $match <code> true </code> to match course offerings
     *                    with any credits, <code> false </code> to match course
     *                    offerings with no credits
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchAnyCredits($match)
    {
        $this->addClause('credits', 'SSBSECT_CREDIT_HRS > 0', [], $match);
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
     *  Sets the course <code> Id </code> for this query to match courses
     *  offerings that have a related course.
     *
     *  @param object osid_id_Id $courseId a course <code> Id </code>
     * @param bool $match <code> true </code> if a positive match, <code>
     *                    false </code> for negative match
     *
     * @throws osid_NullArgumentException <code> courseId </code> is <code>
     *                                           null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchCourseId(osid_id_Id $courseId, $match)
    {
        $this->addClause('course_id', '(SSBSECT_SUBJ_CODE = ? AND SSBSECT_CRSE_NUMB = ?)',
            [$this->session->getSubjectFromCourseId($courseId),
                $this->session->getNumberFromCourseId($courseId)],
            $match);
    }

    /**
     *  Tests if a <code> CourseQuery </code> is available.
     *
     * @return boolean <code> true </code> if a course query interface is
     *                        available, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseQuery()
    {
        return false;
    }

    /**
     *  Gets the query interface for a course. Multiple retrievals produce a
     *  nested <code> OR </code> term.
     *
     * @return object osid_course_CourseQuery the course query
     *
     * @throws osid_UnimplementedException <code> supportsCourseQuery()
     *                                            </code> is <code> false </code>
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseQuery() </code> is <code> true. </code>
     */
    public function getCourseQuery()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Sets the term <code> Id </code> for this query to match courses
     *  offerings that have a related term.
     *
     *  @param object osid_id_Id $termId a term <code> Id </code>
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
        try {
            $partOfTerm = $this->session->getPartOfTermCodeFromTermId($termId);
        } catch (osid_NotFoundException $e) {
            $partOfTerm = null;
        }
        if (null === $partOfTerm) {
            $this->addClause('term_id', 'SSBSECT_TERM_CODE = ?',
                [$this->session->getTermCodeFromTermId($termId)],
                $match);
        } else {
            $this->addClause('term_id', '(SSBSECT_TERM_CODE = ? AND SSBSECT_PTRM_CODE = ?)',
                [
                    $this->session->getTermCodeFromTermId($termId),
                    $partOfTerm,
                ], $match);
        }
    }

    /**
     *  Tests if a <code> TermQuery </code> is available.
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
     *  Gets the query interface for a term. Multiple retrievals produce a
     *  nested <code> OR </code> term.
     *
     * @return object osid_course_TermQuery the term query
     *
     * @throws osid_UnimplementedException <code> supportsTermQuery() </code>
     *                                            is <code> false </code>
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTermQuery() </code> is <code> true. </code>
     */
    public function getTermQuery()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Sets the topic <code> Id </code> for this query to match courses
     *  offerings that have a related topic.
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
                $this->addClause('subject_topic_id', 'SSBSECT_SUBJ_CODE = ?', [$value], $match);

                return;
            case 'department':
                $this->addClause('department_topic_id', 'SCBCRSE_DEPT_CODE = ?', [$value], $match);

                return;
            case 'division':
                $this->addClause('division_topic_id', 'SCBCRSE_DIVS_CODE = ?', [$value], $match);

                return;
            case 'requirement':
                $this->addClause('requirement_topic_id', 'SSRATTR_ATTR_CODE = ?', [$value], $match);
                $this->addTableJoin('LEFT JOIN SSRATTR ON (SSRATTR_TERM_CODE = SSBSECT_TERM_CODE AND SSRATTR_CRN = SSBSECT_CRN)');

                return;
            case 'level':
                $this->addClause('level_topic_id', 'SCRLEVL_LEVL_CODE = ?', [$value], $match);
                $this->addTableJoin('LEFT JOIN scrlevl_recent ON (SSBSECT_SUBJ_CODE = SCRLEVL_SUBJ_CODE AND SSBSECT_CRSE_NUMB = SCRLEVL_CRSE_NUMB)');

                return;
            case 'block':
                $this->addClause('block_topic_id', 'SSRBLCK_BLCK_CODE = ?', [$value], $match);
                $this->addTableJoin('LEFT JOIN SSRBLCK ON (SSRBLCK_TERM_CODE = SSBSECT_TERM_CODE AND SSRBLCK_CRN = SSBSECT_CRN)');

                return;
            case 'instruction_method':
                $this->addClause('instruction_method_topic_id', 'SSBSECT_INSM_CODE = ?', [$value], $match);

                return;
            default:
                $this->addClause('topic_id', 'FALSE', [], $match);
        }
    }

    /**
     *  Tests if a <code> TopicQuery </code> is available.
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
     *  Gets the query interface for a topic. Multiple retrievals produce a
     *  nested <code> OR </code> topic.
     *
     * @return object osid_course_TopicQuery the topic query
     *
     * @throws osid_UnimplementedException <code> supportsTopicQuery() </code>
     *                                            is <code> false </code>
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTopicQuery() </code> is <code> true. </code>
     */
    public function getTopicQuery()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Adds a location informational string for this query.
     *
     * @param string $locationInfo location string string to match
     * @param object osid_type_Type $stringMatchType the string match type
     * @param bool $match <code> true </code> for a positive match, <code>
     *                    false </code> for a negative match
     *
     * @throws osid_InvalidArgumentException <code> locationInfo </code> not
     *                                              of <code> stringMatchType </code>
     * @throws osid_NullArgumentException <code>    locationInfo </code> or
     *                                              <code> stringMatchType </code> is <code> null </code>
     * @throws osid_UnsupportedException <code>
     *                                              supportsStringMatchType(stringMatchType) </code> is <code>
     *                                              false </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchLocationInfo($locationInfo,
        osid_type_Type $stringMatchType, $match)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Matches a location informational string that has any value.
     *
     * @param bool $match <code> true </code> to match courses offerings
     *                    with any location string, <code> false </code> to match course
     *                    offerings with no location string
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchAnyLocationInfo($match)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Sets the location resource <code> Id </code> for this query to match
     *  courses offerings that have a related location resource.
     *
     *  @param object osid_id_Id $resourceId a location resource <code> Id
     *          </code>
     * @param bool $match <code> true </code> if a positive match, <code>
     *                    false </code> for negative match
     *
     * @throws osid_NullArgumentException <code> locationId </code> is <code>
     *                                           null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchLocationId(osid_id_Id $resourceId, $match)
    {
        // Try room locations
        try {
            $locationString = $this->session->getDatabaseIdString($resourceId, 'resource.place.room.');
            $locationParts = explode('.', $locationString);
            $this->addClause(
                'location',
                '(SSRMEET_BLDG_CODE = ? AND SSRMEET_ROOM_CODE = ?)',
                [$locationParts[0], $locationParts[1]],
                $match);
        }
        // Try building locations
        catch (osid_NotFoundException $e) {
            try {
                $building = $this->session->getDatabaseIdString($resourceId, 'resource.place.building.');
                $this->addClause(
                    'location',
                    'SSRMEET_BLDG_CODE = ?',
                    [$building],
                    $match);
            }
            // Try campus locations
            catch (osid_NotFoundException $e) {
                $campus = $this->session->getDatabaseIdString($resourceId, 'resource.place.campus.');
                $this->addClause(
                    'location',
                    'SSBSECT_CAMP_CODE = ?',
                    [$campus],
                    $match);
            }
        }
    }

    /**
     *  Tests if a <code> ResourceQuery </code> is available for the location.
     *
     * @return boolean <code> true </code> if a resource query interface is
     *                        available, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsLocationQuery()
    {
        return false;
    }

    /**
     *  Gets the query interface for a location resource. Multiple retrievals
     *  produce a nested <code> OR </code> term.
     *
     * @return object osid_resource_ResourceQuery the resource query
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

    /**
     *  Matches any location resource.
     *
     * @param bool $match <code> true </code> to match course offerings
     *                    with any location, <code> false </code> to match course
     *                    offerings with no location
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchAnyLocation($match)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Adds a schedule informational string for this query.
     *
     * @param string $scheduleInfo schedule string string to match
     * @param object osid_type_Type $stringMatchType the string match type
     * @param bool $match <code> true </code> for a positive match, <code>
     *                    false </code> for a negative match
     *
     * @throws osid_InvalidArgumentException <code> scheduleInfo </code> not
     *                                              of <code> stringMatchType </code>
     * @throws osid_NullArgumentException <code>    scheduleInfo </code> or
     *                                              <code> stringMatchType </code> is <code> null </code>
     * @throws osid_UnsupportedException <code>
     *                                              supportsStringMatchType(stringMatchType) </code> is <code>
     *                                              false </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchScheduleInfo($scheduleInfo,
        osid_type_Type $stringMatchType, $match)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Matches a schedule informational string that has any value.
     *
     * @param bool $match <code> true </code> to match courses offerings
     *                    with any schedule string, <code> false </code> to match course
     *                    offerings with no schedule string
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchAnyScheduleInfo($match)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Sets the calendar <code> Id </code> for this query to match courses
     *  offerings that have a related calendar.
     *
     *  @param object osid_id_Id $calendarId a calendar <code> Id </code>
     * @param bool $match <code> true </code> if a positive match, <code>
     *                    false </code> for negative match
     *
     * @throws osid_NullArgumentException <code> calendarId </code> is <code>
     *                                           null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchCalendarId(osid_id_Id $calendarId, $match)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Tests if a <code> CalendarQuery </code> is available for the location.
     *
     * @return boolean <code> true </code> if a calendar query interface is
     *                        available, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCalendarQuery()
    {
        return false;
    }

    /**
     *  Gets the query interface for a calendar. Multiple retrievals produce a
     *  nested <code> OR </code> term.
     *
     * @return object osid_calendaring_CalendarQuery the calendar query
     *
     * @throws osid_UnimplementedException <code> supportsCalendarQuery()
     *                                            </code> is <code> false </code>
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCalendarQuery() </code> is <code> true. </code>
     */
    public function getCalendarQuery()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Matches any calendar resource.
     *
     * @param bool $match <code> true </code> to match course offerings
     *                    with any calendar, <code> false </code> to match course
     *                    offerings with no calendar
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchAnyCalendar($match)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Sets the course catalog <code> Id </code> for this query to match
     *  courses offerings assigned to a learning objecive.
     *
     *  @param object osid_id_Id $learningObjectiveId a learning objective
     *          <code> Id </code>
     * @param bool $match <code> true </code> for a positive match, <code>
     *                    false </code> for a negative match
     *
     * @throws osid_NullArgumentException <code> learningObjectiveId </code>
     *                                           is <code> null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchLearningObjectiveId(osid_id_Id $learningObjectiveId,
        $match)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Tests if a <code> LearningObjective </code> is available for the
     *  location.
     *
     * @return boolean <code> true </code> if a learning objective query
     *                        interface is available, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsLearningObjectiveQuery()
    {
        return false;
    }

    /**
     *  Gets the query interface for a learning objective. Multiple retrievals
     *  produce a nested <code> OR </code> term.
     *
     * @return object osid_learning_ObjectiveQuery the learning objective
     *                query
     *
     * @throws osid_UnimplementedException <code>
     *                                            supportsLearningObjectiveQuery() </code> is <code> false
     *                                            </code>
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsLearningObjectiveQuery() </code> is <code> true.
     *              </code>
     */
    public function getLearningObjectiveQuery()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Matches any learning objective.
     *
     * @param bool $match <code> true </code> to match course offerings
     *                    with any learning objective, <code> false </code> to match
     *                    course offerings with no learning objective
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchAnyLearningObjective($match)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Sets the course catalog <code> Id </code> for this query to match
     *  course offerings assigned to course catalogs.
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
        $this->addClause('course_catalog_id',
            '(SSBSECT_TERM_CODE IN (
	SELECT
		term_code
	FROM
		catalog_term
	WHERE
		catalog_id = ?)
AND SCBCRSE_COLL_CODE IN (
		SELECT
			coll_code
		FROM
			course_catalog_college
		WHERE
			catalog_id = ?
	))',
            [
                $this->session->getDatabaseIdString($courseCatalogId, 'catalog.'),
                $this->session->getDatabaseIdString($courseCatalogId, 'catalog.'),
            ],
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
     *  Adds a class url for this query.
     *
     * @param string $url url string to match
     * @param object osid_type_Type $stringMatchType the string match type
     * @param bool $match <code> true </code> for a positive match, <code>
     *                    false </code> for a negative match
     *
     * @throws osid_InvalidArgumentException <code> url </code> not of <code>
     *                                              stringMatchType </code>
     * @throws osid_NullArgumentException <code>    url </code> or <code>
     *                                              stringMatchType </code> is <code> null </code>
     * @throws osid_UnsupportedException <code>
     *                                              supportsStringMatchType(stringMatchType) </code> is <code>
     *                                              false </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchURL($url, osid_type_Type $stringMatchType, $match)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Matches a url that has any value.
     *
     * @param bool $match <code> true </code> to match course offerings
     *                    with any url, <code> false </code> to match course offerings
     *                    with no url
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchAnyURL($match)
    {
        throw new osid_UnimplementedException();
    }

    /*********************************************************
     * Methods from middlebury_course_CourseOffering_Search_InstructorsQueryRecord
     *********************************************************/

    /**
     *  Sets the instructor <code> Id </code> for this query to match course
     *  offerings that have a related instructor.
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
        $this->addTableJoin('LEFT JOIN SYVINST ON (SYVINST_TERM_CODE = SSBSECT_TERM_CODE AND SYVINST_CRN = SSBSECT_CRN)');
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
     * Methods from middlebury_course_CourseOffering_Search_WeeklyScheduleQueryRecord
     *********************************************************/

    /**
     * Matches a meeting on Sunday.
     *
     * @param bool $match <code> true </code> if a positive match, <code>
     *                    false </code> for negative match
     *
     * @compliance mandatory This method must be implemented.
     */
    public function matchMeetsSunday($match)
    {
        $this->addHavingClause('meets_sunday', 'SSRMEET_SUN_DAY IS NOT NULL', [], $match);
    }

    /**
     * Matches a meeting on Monday.
     *
     * @param bool $match <code> true </code> if a positive match, <code>
     *                    false </code> for negative match
     *
     * @compliance mandatory This method must be implemented.
     */
    public function matchMeetsMonday($match)
    {
        $this->addHavingClause('meets_monday', 'SSRMEET_MON_DAY IS NOT NULL', [], $match);
    }

    /**
     * Matches a meeting on Tuesday.
     *
     * @param bool $match <code> true </code> if a positive match, <code>
     *                    false </code> for negative match
     *
     * @compliance mandatory This method must be implemented.
     */
    public function matchMeetsTuesday($match)
    {
        $this->addHavingClause('meets_tuesday', 'SSRMEET_TUE_DAY IS NOT NULL', [], $match);
    }

    /**
     * Matches a meeting on Wednesday.
     *
     * @param bool $match <code> true </code> if a positive match, <code>
     *                    false </code> for negative match
     *
     * @compliance mandatory This method must be implemented.
     */
    public function matchMeetsWednesday($match)
    {
        $this->addHavingClause('meets_wednesday', 'SSRMEET_WED_DAY IS NOT NULL', [], $match);
    }

    /**
     * Matches a meeting on Thursday.
     *
     * @param bool $match <code> true </code> if a positive match, <code>
     *                    false </code> for negative match
     *
     * @compliance mandatory This method must be implemented.
     */
    public function matchMeetsThursday($match)
    {
        $this->addHavingClause('meets_thursday', 'SSRMEET_THU_DAY IS NOT NULL', [], $match);
    }

    /**
     * Matches a meeting on Friday.
     *
     * @param bool $match <code> true </code> if a positive match, <code>
     *                    false </code> for negative match
     *
     * @compliance mandatory This method must be implemented.
     */
    public function matchMeetsFriday($match)
    {
        $this->addHavingClause('meets_friday', 'SSRMEET_FRI_DAY IS NOT NULL', [], $match);
    }

    /**
     * Matches a meeting on Saturday.
     *
     * @param bool $match <code> true </code> if a positive match, <code>
     *                    false </code> for negative match
     *
     * @compliance mandatory This method must be implemented.
     */
    public function matchMeetsSaturday($match)
    {
        $this->addHavingClause('meets_saturday', 'SSRMEET_SAT_DAY IS NOT NULL', [], $match);
    }

    /**
     * Matches meeting times that fall within the range given.
     *
     * @param int  $rangeStart The lower bound of the start time in seconds since midnight. 0-86399
     * @param int  $rangeEnd   The upper bound of the end time in seconds since midnight. 1-86400
     * @param bool $match      <code> true </code> if a positive match, <code>
     *                         false </code> for negative match
     *
     * @compliance mandatory This method must be implemented.
     *
     * @throws osid_NullArgumentException    rangeStart or rangeEnd are null
     * @throws osid_InvalidArgumentException rangeStart or rangeEnd are out of range
     */
    public function matchMeetingTime($rangeStart, $rangeEnd, $match)
    {
        if (null === $rangeStart) {
            throw new osid_NullArgumentException('$rangeStart cannot be null');
        }
        if (null === $rangeEnd) {
            throw new osid_NullArgumentException('$rangeEnd start cannot be null');
        }
        if (!is_numeric($rangeStart)) {
            throw new osid_InvalidArgumentException('$rangeStart must be an integer between 0 and 86399');
        }
        if (!is_numeric($rangeEnd)) {
            throw new osid_InvalidArgumentException('$rangeEnd must be an integer between 1 and 86400');
        }

        $rangeStart = (int) $rangeStart;
        $rangeEnd = (int) $rangeEnd;
        if ($rangeStart < 0 || $rangeStart > 86399) {
            throw new osid_InvalidArgumentException('$rangeStart must be an integer between 0 and 86399');
        }
        if ($rangeEnd < 1 || $rangeEnd > 86400) {
            throw new osid_InvalidArgumentException('$rangeEnd must be an integer between 1 and 86400');
        }

        $this->addClause('meeting_time', '(SSRMEET_BEGIN_TIME >= ? AND SSRMEET_END_TIME <= ?)', [$this->getTimeString($rangeStart), $this->getTimeString($rangeEnd)], $match);
    }

    /**
     * Answer a 24-hour time string from an integer number of seconds.
     *
     * @param int $seconds
     *
     * @return string
     *
     * @since 6/10/09
     */
    protected function getTimeString($seconds)
    {
        $hour = floor($seconds / 3600);
        $minute = floor(($seconds - ($hour * 3600)) / 60);

        return str_pad($hour, 2, '0', \STR_PAD_LEFT).str_pad($minute, 2, '0', \STR_PAD_LEFT);
    }

    /*********************************************************
     * Methods from the middlebury_course_CourseOffering_EnrollmentQueryRecord
     *********************************************************/

    /**
     * Match CourseOfferings that may be or have been open for enrollment.
     * These may have a non-zero maximum enrollment or other flag that indicates
     * that they are not just placeholders (such as for cross-lists).
     *
     * @param bool $match <code> true </code> if a positive match, <code>
     *                    false </code> for negative match
     */
    public function matchEnrollable($match)
    {
        $this->addClause('enrollable', 'SSBSECT_MAX_ENRL > 0', [], $match);
    }

    /**
     * Match CourseOfferings based on their enrollment.
     *
     * @param int  $rangeStart The lower bound of enrollment range to match. 0 or greater.
     * @param int  $rangeEnd   The upper bound of the enrollment range to match. 0 or greater, or NULL to indicate no upper bound.
     * @param bool $match      <code> true </code> if a positive match, <code>
     *                         false </code> for negative match
     */
    public function matchEnrollment($rangeStart, $rangeEnd, $match)
    {
        if (null === $rangeStart) {
            throw new osid_NullArgumentException('$rangeStart cannot be null');
        }
        if (!is_numeric($rangeStart)) {
            throw new osid_InvalidArgumentException('$rangeStart must be an integer 0 or greater');
        }
        $rangeStart = (int) $rangeStart;
        if ($rangeStart < 0) {
            throw new osid_InvalidArgumentException('$rangeStart must be an integer 0 or greater');
        }

        if (null === $rangeEnd) {
            $this->addClause('enrollment', 'SSBSECT_ENRL >= ?', [$rangeStart], $match);
        } else {
            if (!is_numeric($rangeEnd)) {
                throw new osid_InvalidArgumentException('$rangeEnd must be an integer 0 or greater');
            }
            $rangeEnd = (int) $rangeEnd;
            if ($rangeEnd < 0) {
                throw new osid_InvalidArgumentException('$rangeEnd must be an integer 0 or greater');
            }
            $this->addClause('enrollment', '(SSBSECT_ENRL >= ? AND SSBSECT_ENRL <= ?)', [$rangeStart, $rangeEnd], $match);
        }
    }

    /**
     * Match CourseOfferings based on their seats available.
     *
     * @param int  $rangeStart The lower bound of the seats range to match. NULL to indicate no lower bound.
     * @param int  $rangeEnd   The upper bound of the seats range to match. NULL to indicate no upper bound.
     * @param bool $match      <code> true </code> if a positive match, <code>
     *                         false </code> for negative match
     */
    public function matchSeatsAvailable($rangeStart, $rangeEnd, $match)
    {
        if (null === $rangeStart && null === $rangeEnd) {
            throw new osid_NullArgumentException('Both $rangeStart and $rangeEnd cannot be null');
        }

        if (null === $rangeStart) {
            if (!is_numeric($rangeEnd)) {
                throw new osid_InvalidArgumentException('$rangeEnd must be an integer');
            }
            $this->addClause('seats_available', 'SSBSECT_SEATS_AVAIL <= ?', [$rangeEnd], $match);

            return;
        }

        if (!is_numeric($rangeStart)) {
            throw new osid_InvalidArgumentException('$rangeStart must be an integer');
        }
        $rangeStart = (int) $rangeStart;

        if (null === $rangeEnd) {
            $this->addClause('seats_available', 'SSBSECT_SEATS_AVAIL >= ?', [$rangeStart], $match);
        } else {
            if (!is_numeric($rangeEnd)) {
                throw new osid_InvalidArgumentException('$rangeEnd must be an integer');
            }

            $rangeEnd = (int) $rangeEnd;
            $this->addClause('seats_available', '(SSBSECT_SEATS_AVAIL >= ? AND SSBSECT_SEATS_AVAIL <= ?)', [$rangeStart, $rangeEnd], $match);
        }
    }
}
