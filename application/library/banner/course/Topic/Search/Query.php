<?php
/**
 * @since 6/11/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 *  <p>This is the query interface for searching topics. Each method match
 *  specifies an <code> AND </code> term while multiple invocations of the
 *  same method produce a nested <code> OR. </code> </p>.
 *
 * @since 6/11/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class banner_course_Topic_Search_Query implements osid_course_TopicQuery, middlebury_course_Topic_Search_TermQueryRecord
{
    /**
     * Constructor.
     *
     * @return void
     *
     * @since 5/20/09
     */
    public function __construct(banner_course_SessionInterface $session)
    {
        $this->requirementQuery = new banner_course_Topic_Search_Query_Requirement($session);
        $this->levelQuery = new banner_course_Topic_Search_Query_Level($session);
        $this->blockQuery = new banner_course_Topic_Search_Query_Block($session);
        $this->instructionMethodQuery = new banner_course_Topic_Search_Query_InstructionMethod($session);
        $this->divisionQuery = new banner_course_Topic_Search_Query_Division($session);
        $this->departmentQuery = new banner_course_Topic_Search_Query_Department($session);
        $this->subjectQuery = new banner_course_Topic_Search_Query_Subject($session);

        $this->wildcardStringMatchType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:search:wildcard');

        $this->termType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:terms');

        $this->subjectType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.subject');
        $this->departmentType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.department');
        $this->divisionType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.division');
        $this->requirementType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.requirement');
        $this->levelType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.level');
        $this->blockType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.block');
        $this->instructionMethodType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.instruction_method');

        $this->toInclude = [];
    }

    /**
     * Answer true if requirement topics should be included.
     *
     * @return bool
     *
     * @since 6/12/09
     */
    public function includeRequirements()
    {
        if (!count($this->toInclude)) {
            return true;
        } else {
            return in_array('requirement', $this->toInclude);
        }
    }

    /**
     * Answer true if level topics should be included.
     *
     * @return bool
     *
     * @since 6/12/09
     */
    public function includeLevels()
    {
        if (!count($this->toInclude)) {
            return true;
        } else {
            return in_array('level', $this->toInclude);
        }
    }

    /**
     * Answer true if block topics should be included.
     *
     * @return bool
     *
     * @since 6/12/09
     */
    public function includeBlocks()
    {
        if (!count($this->toInclude)) {
            return true;
        } else {
            return in_array('block', $this->toInclude);
        }
    }

    /**
     * Answer true if instruction_method topics should be included.
     *
     * @return bool
     *
     * @since 6/12/09
     */
    public function includeInstructionMethods()
    {
        if (!count($this->toInclude)) {
            return true;
        } else {
            return in_array('instruction_method', $this->toInclude);
        }
    }

    /**
     * Answer true if division topics should be included.
     *
     * @return bool
     *
     * @since 6/12/09
     */
    public function includeDivisions()
    {
        if (!count($this->toInclude)) {
            return true;
        } else {
            return in_array('division', $this->toInclude);
        }
    }

    /**
     * Answer true if department topics should be included.
     *
     * @return bool
     *
     * @since 6/12/09
     */
    public function includeDepartments()
    {
        if (!count($this->toInclude)) {
            return true;
        } else {
            return in_array('department', $this->toInclude);
        }
    }

    /**
     * Answer true if subject topics should be included.
     *
     * @return bool
     *
     * @since 6/12/09
     */
    public function includeSubjects()
    {
        if (!count($this->toInclude)) {
            return true;
        } else {
            return in_array('subject', $this->toInclude);
        }
    }

    /**
     * Answer the Where clause for requirements.
     *
     * @return string
     *
     * @since 6/12/09
     */
    public function getRequirementWhereClause()
    {
        return $this->requirementQuery->getWhereClause();
    }

    /**
     * Answer the input parameters for requirements.
     *
     * @return string
     *
     * @since 6/12/09
     */
    public function getRequirementParameters()
    {
        return $this->requirementQuery->getParameters();
    }

    /**
     * Answer the Where clause for levels.
     *
     * @return string
     *
     * @since 6/12/09
     */
    public function getLevelWhereClause()
    {
        return $this->levelQuery->getWhereClause();
    }

    /**
     * Answer the input parameters for levels.
     *
     * @return string
     *
     * @since 6/12/09
     */
    public function getLevelParameters()
    {
        return $this->levelQuery->getParameters();
    }

    /**
     * Answer the Where clause for blocks.
     *
     * @return string
     *
     * @since 6/12/09
     */
    public function getBlockWhereClause()
    {
        return $this->blockQuery->getWhereClause();
    }

    /**
     * Answer the input parameters for blocks.
     *
     * @return string
     *
     * @since 6/12/09
     */
    public function getBlockParameters()
    {
        return $this->blockQuery->getParameters();
    }

    /**
     * Answer the Where clause for instruction_methods.
     *
     * @return string
     *
     * @since 6/12/09
     */
    public function getInstructionMethodWhereClause()
    {
        return $this->instructionMethodQuery->getWhereClause();
    }

    /**
     * Answer the input parameters for instruction_methods.
     *
     * @return string
     *
     * @since 6/12/09
     */
    public function getInstructionMethodParameters()
    {
        return $this->instructionMethodQuery->getParameters();
    }

    /**
     * Answer the Where clause for divisions.
     *
     * @return string
     *
     * @since 6/12/09
     */
    public function getDivisionWhereClause()
    {
        return $this->divisionQuery->getWhereClause();
    }

    /**
     * Answer the input parameters for divisions.
     *
     * @return string
     *
     * @since 6/12/09
     */
    public function getDivisionParameters()
    {
        return $this->divisionQuery->getParameters();
    }

    /**
     * Answer the Where clause for departments.
     *
     * @return string
     *
     * @since 6/12/09
     */
    public function getDepartmentWhereClause()
    {
        return $this->departmentQuery->getWhereClause();
    }

    /**
     * Answer the input parameters for departments.
     *
     * @return string
     *
     * @since 6/12/09
     */
    public function getDepartmentParameters()
    {
        return $this->departmentQuery->getParameters();
    }

    /**
     * Answer the Where clause for subjects.
     *
     * @return string
     *
     * @since 6/12/09
     */
    public function getSubjectWhereClause()
    {
        return $this->subjectQuery->getWhereClause();
    }

    /**
     * Answer the input parameters for subjects.
     *
     * @return string
     *
     * @since 6/12/09
     */
    public function getSubjectParameters()
    {
        return $this->subjectQuery->getParameters();
    }

    /*********************************************************
     * Methods from osid_OsidQuery
     *********************************************************/

    /**
     *  Gets the string matching types supported. A string match type
     *  specifies the syntax of the string query, such as matching a word or
     *  including a wildcard or regular expression.
     *
     * @return object osid_type_TypeList a list containing the supported
     *                string match types
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getStringMatchTypes()
    {
        return new phpkit_type_ArrayTypeList([$this->wildcardStringMatchType]);
    }

    /**
     *  Tests if the given string matching type is supported.
     *
     *  @param object osid_type_Type $searchType a <code> Type </code>
     *          indicating a string match type
     *
     * @return boolean <code> true </code> if the given Type is supported,
     *                        <code> false </code> otherwise
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsStringMatchType(osid_type_Type $searchType)
    {
        return $this->wildcardStringMatchType->isEqual($searchType);
    }

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
    public function matchKeyword($keyword, osid_type_Type $stringMatchType,
        $match)
    {
        $this->requirementQuery->matchKeyword($keyword, $stringMatchType, $match);
        $this->levelQuery->matchKeyword($keyword, $stringMatchType, $match);
        $this->blockQuery->matchKeyword($keyword, $stringMatchType, $match);
        $this->instructionMethodQuery->matchKeyword($keyword, $stringMatchType, $match);
        $this->divisionQuery->matchKeyword($keyword, $stringMatchType, $match);
        $this->departmentQuery->matchKeyword($keyword, $stringMatchType, $match);
        $this->subjectQuery->matchKeyword($keyword, $stringMatchType, $match);
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
    public function matchDisplayName($displayName,
        osid_type_Type $stringMatchType, $match)
    {
        $this->requirementQuery->matchDisplayName($displayName, $stringMatchType, $match);
        $this->levelQuery->matchDisplayName($displayName, $stringMatchType, $match);
        $this->blockQuery->matchDisplayName($displayName, $stringMatchType, $match);
        $this->instructionMethodQuery->matchDisplayName($displayName, $stringMatchType, $match);
        $this->divisionQuery->matchDisplayName($displayName, $stringMatchType, $match);
        $this->departmentQuery->matchDisplayName($displayName, $stringMatchType, $match);
        $this->subjectQuery->matchDisplayName($displayName, $stringMatchType, $match);
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
    public function matchDescription($description,
        osid_type_Type $stringMatchType, $match)
    {
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
        if ($this->requirementType->isEqual($genusType)) {
            $type = 'requirement';
        }
        if ($this->levelType->isEqual($genusType)) {
            $type = 'level';
        }
        if ($this->blockType->isEqual($genusType)) {
            $type = 'block';
        }
        if ($this->instructionMethodType->isEqual($genusType)) {
            $type = 'instruction_method';
        }
        if ($this->divisionType->isEqual($genusType)) {
            $type = 'division';
        }
        if ($this->departmentType->isEqual($genusType)) {
            $type = 'department';
        }
        if ($this->subjectType->isEqual($genusType)) {
            $type = 'subject';
        }

        if (!isset($type)) {
            return;
        }

        if ($match) {
            $this->toInclude[] = $type;
        } else {
            if (!count($this->toInclude)) {
                $this->toInclude[] = 'requirement';
                $this->toInclude[] = 'level';
                $this->toInclude[] = 'block';
                $this->toInclude[] = 'instruction_method';
                $this->toInclude[] = 'division';
                $this->toInclude[] = 'department';
                $this->toInclude[] = 'subject';
            }
            unset($this->toInclude[array_search($type, $this->toInclude)]);
            $this->toInclude = array_values($this->toInclude);
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
        $this->matchGenusType($genusType, $match);
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
    }

    /**
     *  Tests if this query supports the given record <code> Type. </code> The
     *  given record type may be supported by the object through
     *  interface/type inheritence. This method should be checked before
     *  retrieving the record interface.
     *
     *  @param object osid_type_Type $recordType a type
     *
     * @return boolean <code> true </code> if a record query of the given
     *                        record <code> Type </code> is available, <code> false </code>
     *                        otherwise
     *
     * @throws osid_NullArgumentException <code> recordType </code> is <code>
     *                                           null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function hasRecordType(osid_type_Type $recordType)
    {
        return $this->termType->isEqual($recordType);
    }

    /*********************************************************
     * Methods from osid_course_TopicQuery
     *********************************************************/

    /**
     *  Sets the course offering <code> Id </code> for this query to match
     *  terms assigned to course offerings.
     *
     *  @param object osid_id_Id $courseOfferingId the course offering <code>
     *          Id </code>
     * @param bool $match <code> true </code> for a positive match, <code>
     *                    false </code> for a negative match
     *
     * @throws osid_NullArgumentException <code> courseOfferingId </code> is
     *                                           <code> null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchCourseOfferingId(osid_id_Id $courseOfferingId, $match)
    {
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
     * @param bool $match <code> true </code> to match terms with any
     *                    course offering, <code> false </code> to match subjects with
     *                    no composition
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchAnyCourseOffering($match)
    {
    }

    /**
     *  Sets the course catalog <code> Id </code> for this query to match
     *  terms assigned to course catalogs.
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
     *  Topic </code> record <code> Type. </code> Multiple record retrievals
     *  produce a nested <code> OR </code> term.
     *
     *  @param object osid_type_Type $topicRecordType a topic record type
     *
     * @return object osid_course_TopicQueryRecord the topic record
     *
     * @throws osid_NullArgumentException <code> topicRecordType </code> is
     *                                           <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure occurred
     * @throws osid_UnsupportedException <code>
     *                                           hasRecordType(topicRecordType) </code> is <code> false </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTopicQueryRecord(osid_type_Type $topicRecordType)
    {
        if ($this->hasRecordType($topicRecordType)) {
            return $this;
        } else {
            throw new osid_UnsupportedException('The topic type passed is not supported.');
        }
    }

    /*********************************************************
     * methods from middlebury_course_Topic_Search_TermQueryRecord
     *********************************************************/

    /**
     *  Tests if the given type is implemented by this record. Other types
     *  than that directly indicated by <code> getType() </code> may be
     *  supported through an inheritance scheme where the given type specifies
     *  a record that is a parent interface of the interface specified by
     *  <code> getType(). </code>.
     *
     *  @param object osid_type_Type $recordType a type
     *
     * @return boolean <code> true </code> if the given record <code> Type
     *                        </code> is implemented by this record, <code> false </code>
     *                        otherwise
     *
     * @throws osid_NullArgumentException <code> recordType </code> is <code>
     *                                           null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function implementsRecordType(osid_type_Type $recordType)
    {
        return $this->hasRecordType($recordType);
    }

    /**
     *  Gets the <code> TopicQuery </code> from which this record originated.
     *
     * @return object osid_course_TopicQuery the topic query
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTopicQuery()
    {
        return $this;
    }

    /**
     *  Sets the term <code> Id </code> for this query to match topics in that term.
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
        $this->requirementQuery->matchTermId($termId, $match);
        $this->levelQuery->matchTermId($termId, $match);
        $this->blockQuery->matchTermId($termId, $match);
        $this->instructionMethodQuery->matchTermId($termId, $match);
        $this->divisionQuery->matchTermId($termId, $match);
        $this->departmentQuery->matchTermId($termId, $match);
        $this->subjectQuery->matchTermId($termId, $match);
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
     * @return object types_course_TermQuery the term query
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
}
