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
class banner_course_Topic_Search_Query_Subject extends banner_course_AbstractQuery implements osid_course_TopicQuery
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
        parent::__construct($session);

        $this->wildcardStringMatchType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:search:wildcard');

        $this->addStringMatchType($this->wildcardStringMatchType);
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
    public function matchKeyword($keyword, osid_type_Type $stringMatchType,
        $match)
    {
        $this->matchDisplayName($keyword, $stringMatchType, $match);
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
        if (!is_string($displayName)) {
            throw new osid_InvalidArgumentException("\$displayName '$displayName' must be a string.");
        }

        if ($stringMatchType->isEqual($this->wildcardStringMatchType)) {
            $displayName = str_replace('*', '%', $displayName);
            $this->addClause('displayName', 'STVSUBJ_DESC LIKE(?)', [$displayName], $match);
        } else {
            throw new osid_UnsupportedException('The stringMatchType passed is not supported.');
        }
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
        throw new osid_UnimplementedException();
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
        throw new osid_UnimplementedException();
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
        throw new osid_UnimplementedException();
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
        throw new osid_UnimplementedException();
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
        throw new osid_UnimplementedException();
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
        throw new osid_UnimplementedException();
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
        throw new osid_UnimplementedException();
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
        throw new osid_UnimplementedException();
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
        throw new osid_UnimplementedException();
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
        throw new osid_UnimplementedException();
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
        $this->addClause('term', 'SSBSECT_TERM_CODE = ?', [$this->session->getDatabaseIdString($termId, 'term.')], $match);
    }
}
