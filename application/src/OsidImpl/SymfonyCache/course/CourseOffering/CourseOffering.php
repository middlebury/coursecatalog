<?php

namespace Catalog\OsidImpl\SymfonyCache\course\CourseOffering;

use Catalog\OsidImpl\SymfonyCache\Cachable;

/**
 *  <p>A <code> CourseOffering </code> represents a learning unit offered
 *  duing a <code> Term. </code> A <code> Course </code> is instantiated at a
 *  time and place through the creation of a <code> CourseOffering. </code>
 *  </p>.
 */
class CourseOffering extends Cachable implements \osid_course_CourseOffering, \middlebury_course_CourseOffering_AlternatesRecord
{
    private \osid_course_CourseOffering $offering;
    private \osid_id_Id $id;
    private $localRecordTypes;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct(
        private CourseOfferingLookupSession $cacheSession,
        \osid_id_Id|\osid_course_CourseOffering $offeringOrId,
    ) {
        if ($offeringOrId instanceof \osid_course_CourseOffering) {
            $this->offering = $offeringOrId;
            $this->id = $offeringOrId->getId();
        } else {
            $this->id = $offeringOrId;
        }

        parent::__construct(
            $cacheSession->getCache(),
            $this->id->getIdentifierNamespace().':'.$this->id->getAuthority().':'.$this->id->getIdentifier()
        );

        $this->localRecordTypes = [
            new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:alternates'),
        ];
    }

    /**
     * Answer our internal course offering object.
     *
     * @return \osid_course_CourseOffering
     */
    private function getOffering()
    {
        if (!isset($this->offering)) {
            $this->offering = $this->cacheSession->getWrappedSession()->getCourseOffering($this->getId());
        }

        return $this->offering;
    }

    /*********************************************************
     * Interface Methods
     *********************************************************/

    /*********************************************************
     * \osid_OsidObject
     *********************************************************/

    /**
     *  Gets the <code> Id </code> associated with this instance of this OSID
     *  object. Persisting any reference to this object is done by persisting
     *  the <code> Id </code> returned from this method. The <code> Id </code>
     *  returned may be different than the <code> Id </code> used to query
     *  this object. In this case, the new <code> Id </code> should be
     *  preferred over the old one for future queries.
     *
     * @return the <code> Id </code>
     *
     *  @compliance mandatory This method must be implemented.
     *
     *  @notes  The <code> Id </code> is intended to be constant and
     *          persistent. A consumer may at any time persist the <code> Id
     *          </code> for retrieval at any future time. Ideally, the <code>
     *          Id </code> should consistently resolve into the designated
     *          object and not be reused. In cases where objects are
     *          deactivated after a certain lifetime the provider should
     *          endeavor not to obliterate the object or its <code> Id </code>
     *          but instead should update the properties of the object
     *          including the deactiavted status and the elimination of any
     *          unwanted pieces of data. As such, there is no means for
     *          updating an <code> Id </code> and providers should consider
     *          carefully the identification scheme to implement.
     *          <br/><br/>
     *          <code> Id </code> assignments for objects are strictly in the
     *          realm of the provider and any errors should be fixed directly
     *          with the backend supporting system. Once an <code> Id </code>
     *          has been assigned in a production service it should be honored
     *          such that it may be necessary for the backend system to
     *          support <code> Id </code> aliasing to redirect the lookup to
     *          the current <code> Id. </code> Use of an <code> Id </code>
     *          OSID may be helpful to accomplish this task in a modular
     *          manner.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *  Gets the preferred display name associated with this instance of this
     *  OSID object appropriate for display to the user.
     *
     * @return the display name
     *
     *  @compliance mandatory This method must be implemented.
     *
     *  @notes  A display name is a string used for identifying an object in
     *          human terms. A provider may wish to initialize the display
     *          name based on one or more object attributes. In some cases,
     *          the display name may not map to a specific or significant
     *          object attribute but simply be used as a preferred display
     *          name that can be modified. A provider may also wish to
     *          translate the display name into a specific locale using the
     *          Locale service. Some OSIDs define methods for more detailed
     *          naming.
     */
    public function getDisplayName()
    {
        return $this->getOffering()->getDisplayName();
    }

    /**
     *  Gets the description associated with this instance of this OSID
     *  object.
     *
     * @return the description
     *
     *  @compliance mandatory This method must be implemented.
     *
     *  @notes  A description is a string used for describing an object in
     *          human terms and may not have significance in the underlying
     *          system. A provider may wish to initialize the description
     *          based on one or more object attributes and/or treat it as an
     *          auxiliary piece of data that can be modified. A provider may
     *          also wish to translate the description into a specific locale
     *          using the Locale service.
     */
    public function getDescription()
    {
        return $this->getOffering()->getDescription();
    }

    /**
     *  Gets the record types available in this object. A record <code> Type
     *  </code> explicitly indicates the specification of an interface to the
     *  record. A record may or may not inherit other record interfaces
     *  through interface inheritance in which case support of a record type
     *  may not be explicit in the returned list. Interoperability with the
     *  typed interface to this object should be performed through <code>
     *  hasRecordType(). </code>.
     *
     * @return the record types available through this object
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getRecordTypes()
    {
        return $this->getOffering()->getRecordTypes();
    }

    /**
     *  Tests if this object supports the given record <code> Type. </code>
     *  The given record type may be supported by the object through
     *  interface/type inheritence. This method should be checked before
     *  retrieving the record interface.
     *
     *  @param object \osid_type_Type $recordType a type
     *
     *  @return <code> true </code> if a record of the given record <code>
     *          Type </code> is available, <code> false </code> otherwise
     *
     * @throws \osid_NullArgumentException <code> recordType </code> is <code>
     *                                            null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function hasRecordType(\osid_type_Type $recordType)
    {
        return $this->getOffering()->hasRecordType($recordType);
    }

    /**
     *  Gets the genus type of this object.
     *
     * @return the genus type of this object
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getGenusType()
    {
        return $this->getOffering()->getGenusType();
    }

    /**
     *  Tests if this object is of the given genus <code> Type. </code> The
     *  given genus type may be supported by the object through the type
     *  hierarchy.
     *
     *  @param object \osid_type_Type $genusType a genus type
     *
     *  @return <code> true </code> if this object is of the given genus
     *          <code> Type, </code> <code> false </code> otherwise
     *
     * @throws \osid_NullArgumentException <code> genusType </code> is <code>
     *                                            null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function isOfGenusType(\osid_type_Type $genusType)
    {
        return $this->getOffering()->isOfGenusType($genusType);
    }

    /**
     *  Tests to see if the last method invoked retrieved up-to-date data.
     *  Simple retrieval methods do not specify errors as, generally, the data
     *  is retrieved once at the time this object is instantiated. Some
     *  implementations may provide real-time data though the application may
     *  not always care. An implementation providing a real-time service may
     *  fall back to a previous snapshot in case of error. This method returns
     *  false if the data last retrieved was stale.
     *
     *  @return <code> true </code> if the last data retrieval was up to date,
     *          <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     *
     *  @notes  Providers should return false unless all getters are
     *          implemented using real-time queries, or some trigger process
     *          keeps the data in this object current. Providers should
     *          populate basic data elements at the time this object is
     *          instantiated, or set an error, to ensure some data
     *          availability.
     */
    public function isCurrent()
    {
        return false;
    }

    /**
     *  Gets a list of all properties of this object including those
     *  corresponding to data within this object's records. Properties provide
     *  a means for applications to display a representation of the contents
     *  of an object without understanding its record interface
     *  specifications. Applications needing to examine a specific property or
     *  perform updates should use the methods defined by the object's record
     *  <code> Type. </code>.
     *
     * @return a list of properties
     *
     * @throws \osid_OperationFailedException  unable to complete request
     * @throws \osid_PermissionDeniedException an authorization failure
     *                                         occurred
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getProperties()
    {
        return $this->getOffering()->getProperties();
    }

    /**
     *  Gets a list of properties corresponding to the specified record type.
     *  Properties provide a means for applications to display a
     *  representation of the contents of an object without understanding its
     *  record interface specifications. Applications needing to examine a
     *  specific property or perform updates should use the methods defined by
     *  the object record <code> Type. </code> The resulting set includes
     *  properties specified by parents of the record <code> type </code> in
     *  the case a record's interface extends another.
     *
     *  @param object \osid_type_Type $recordType the record type corresponding
     *          to the properties set to retrieve
     *
     * @return a list of properties
     *
     * @throws \osid_NullArgumentException <code> recordType </code> is <code>
     *                                            null </code>
     * @throws \osid_OperationFailedException     unable to complete request
     * @throws \osid_PermissionDeniedException    an authorization failure
     *                                            occurred
     * @throws \osid_UnsupportedException <code>  hasRecordType(recordType)
     *                                            </code> is <code> false </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getPropertiesByRecordType(\osid_type_Type $recordType)
    {
        return $this->getOffering()->getPropertiesByRecordType($recordType);
    }

    /*********************************************************
     * \osid_course_CourseOffering
     *********************************************************/

    /**
     *  Gets the formal title of this course. It may be the same as the
     *  display name or it may be used to more formally label the course. A
     *  display name might be Physics 102 where the title is Introduction to
     *  Electromagentism.
     *
     * @return string the course title
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTitle()
    {
        return $this->getOffering()->getTitle();
    }

    /**
     *  Gets the course number which is a label generally used to index the
     *  course in a catalog, such as T101 or 16.004.
     *
     * @return string the course number
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getNumber()
    {
        return $this->getOffering()->getNumber();
    }

    /**
     * Gets the Course Reference Number which is a label used to inidcate both
     * the course name and specific section, such as 70001.
     *
     * @return string the Course Reference Number
     *
     * @compliance optional This method is not required in subclasses.
     */
    public function getCourseReferenceNumber()
    {
        return $this->getOffering()->getCourseReferenceNumber();
    }

    /**
     *  Gets the number of credits in this course.
     *
     * @return float the number of credits
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCredits()
    {
        return $this->getOffering()->getCredits();
    }

    /**
     *  Gets the an informational string for the course prerequisites.
     *
     * @return string the prerequisites
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getPrereqInfo()
    {
        return $this->getOffering()->getPrereqInfo();
    }

    /**
     *  Gets the canonical course <code> Id </code> associated with this
     *  course offering.
     *
     * @return object \osid_id_Id the course <code> Id </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseId()
    {
        return $this->getOffering()->getCourseId();
    }

    /**
     *  Gets the canonical course associated with this course offering.
     *
     * @return object \osid_course_Course the course
     *
     * @throws \osid_OperationFailedException unable to complete request
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourse()
    {
        return $this->cacheSession->getCourseLookupSession()->getCourse($this->getCourseId());
    }

    /**
     *  Gets the <code> Id </code> of the <code> Term </code> of this
     *  offering.
     *
     * @return object \osid_id_Id the <code> Term </code> <code> Id </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTermId()
    {
        return $this->getOffering()->getTermId();
    }

    /**
     *  Gets the <code> Term </code> of this offering.
     *
     * @return object \osid_course_Term the term
     *
     * @throws \osid_OperationFailedException unable to complete request
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTerm()
    {
        return $this->cacheSession->getTermLookupSession()->getTerm($this->getTermId());
    }

    /**
     *  WARNING: This method was not in the OSID trunk as of 2009-04-27. A
     *  ticket requesting the addition of this method is available at:
     *  http://oki.assembla.com/spaces/osid-dev/tickets/18-osid-course---No-way-to-map-Topics-to-Courses-or-CourseOfferings-
     *  Gets a list of the <code> Id </code> s of the <code> Topic </code> s
     *  this offering is associated with.
     *
     * @return object \osid_id_IdList the <code> Topic </code> <code> Id
     *                </code> s
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTopicIds()
    {
        return $this->getOffering()->getTopicIds();
    }

    /**
     *  WARNING: This method was not in the OSID trunk as of 2009-04-27. A
     *  ticket requesting the addition of this method is available at:
     *  http://oki.assembla.com/spaces/osid-dev/tickets/18-osid-course---No-way-to-map-Topics-to-Courses-or-CourseOfferings-
     *  Gets the <code> Topic </code> s this offering is associated with.
     *
     * @return object \osid_course_TopicList the topics
     *
     * @throws \osid_OperationFailedException unable to complete request
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTopics()
    {
        return $this->cacheSession->getTopicLookupSession()
            ->getTopicsByIds($this->getTopicIds());
    }

    /**
     *  Gets a string describing the location of this course offering.
     *
     * @return string location info
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getLocationInfo()
    {
        return $this->getOffering()->getLocationInfo();
    }

    /**
     *  Tests if this course offering has an associated location resource.
     *
     * @return bool <code> true </code> if this course offering has a
     *                     location resource, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function hasLocation()
    {
        return $this->getOffering()->hasLocation();
    }

    /**
     *  Gets the <code> Id </code> of the <code> Resource </code> representing
     *  the location of this course offering.
     *
     * @return object \osid_id_Id the location
     *
     * @throws \osid_IllegalStateException <code> hasLocation() </code> is
     *                                            <code> false </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getLocationId()
    {
        return $this->getOffering()->getLocationId();
    }

    /**
     *  Gets the <code> Resource </code> representing the location of this
     *  offering.
     *
     * @return object \osid_resource_Resource the location
     *
     * @throws \osid_IllegalStateException <code> hasLocation() </code> is
     *                                            <code> false </code>
     * @throws \osid_OperationFailedException     unable to complete request
     * @throws \osid_PermissionDeniedException    authorization failure
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getLocation()
    {
        try {
            return $this->cacheSession->getResourceLookupSession()->getResource($this->getLocationId());
        } catch (\osid_NotFoundException $e) {
            throw new \osid_OperationFailedException($e->getMessage());
        }
    }

    /**
     *  Gets a string describing the schedule of this course offering.
     *
     * @return string schedule info
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getScheduleInfo()
    {
        return $this->getOffering()->getScheduleInfo();
    }

    /**
     *  Tests if this course offering has an associated calendar.
     *
     * @return bool <code> true </code> if this course offering has a
     *                     calendar, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function hasCalendar()
    {
        return $this->getOffering()->hasCalendar();
    }

    /**
     *  Gets the calendar for this course offering. Schedule items are
     *  associated with this calendar through the available Scheduling
     *  manager.
     *
     * @return object \osid_id_Id <code> Id </code> of a <code> </code>
     *                calendar
     *
     * @throws \osid_IllegalStateException <code> hasCalendar() </code> is
     *                                            <code> false </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCalendarId()
    {
        return $this->getOffering()->getCalendarId();
    }

    /**
     *  Gets the calendar for this course offering, which may be a root in a
     *  calendar hierarchy.
     *
     * @return object \osid_calendaring_Calendar a calendar
     *
     * @throws \osid_IllegalStateException <code> hasCalendar() </code> is
     *                                            <code> false </code>
     * @throws \osid_OperationFailedException     unable to complete request
     * @throws \osid_PermissionDeniedException    authorization failure
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCalendar()
    {
        return $this->getOffering()->getCalendar();
    }

    /**
     *  Tests if this course offering has an associated learning objective.
     *
     * @return bool <code> true </code> if this course offering has a
     *                     learning objective, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function hasLearningObjective()
    {
        return $this->getOffering()->hasLearningObjective();
    }

    /**
     *  Gets the root node of a learning objective map for this course
     *  offering.
     *
     * @return object \osid_id_Id <code> Id </code> of a <code> l </code>
     *                earning <code> Objective </code>
     *
     * @throws \osid_IllegalStateException <code> hasLearningObjective()
     *                                            </code> is <code> false </code>
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function getLearningObjectiveId()
    {
        return $this->getOffering()->getLearningObjectiveId();
    }

    /**
     *  Gets the root node of a learning objective map for this course
     *  offering.
     *
     * @return object \osid_learning_Objective the returned learning <code>
     *                Objective </code>
     *
     * @throws \osid_IllegalStateException <code> hasLearningObjective()
     *                                            </code> is <code> false </code>
     * @throws \osid_OperationFailedException     unable to complete request
     * @throws \osid_PermissionDeniedException    authorization failure
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getLearningObjective()
    {
        return $this->getOffering()->getLearningObjective();
    }

    /**
     *  Gets an external resource, such as a class web site, associated with
     *  this offering.
     *
     * @return string a URL string
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getURL()
    {
        return $this->getOffering()->getURL();
    }

    /**
     *  Gets the record corresponding to the given <code> CourseOffering
     *  </code> record <code> Type. </code> This method must be used to
     *  retrieve an object implementing the requested record interface along
     *  with all of its ancestor interfaces. The <code>
     *  courseOfferingRecordType </code> may be the <code> Type </code>
     *  returned in <code> getRecordTypes() </code> or any of its parents in a
     *  <code> Type </code> hierarchy where <code>
     *  hasRecordType(courseOfferingRecordType) </code> is <code> true </code>
     *  .
     *
     *  @param object \osid_type_Type $courseOfferingRecordType the type of
     *          course offering record to retrieve
     *
     * @return object \osid_course_CourseOfferingRecord the course offering
     *                record
     *
     * @throws \osid_NullArgumentException <code> courseOfferingRecordType
     *                                            </code> is <code> null </code>
     * @throws \osid_OperationFailedException     unable to complete request
     * @throws \osid_PermissionDeniedException    authorization failure occurred
     * @throws \osid_UnsupportedException <code>
     *                                            hasRecordType(courseOfferingRecordType) </code> is <code>
     *                                            false </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingRecord(\osid_type_Type $courseOfferingRecordType)
    {
        if ($this->implementsRecordType($courseOfferingRecordType)) {
            return $this;
        }

        return $this->getOffering()->getCourseOfferingRecord($courseOfferingRecordType);
    }

    /*********************************************************
     * Record support
     *********************************************************/
    /**
     *  Tests if the given type is implemented by this record. Other types
     *  than that directly indicated by <code> getType() </code> may be
     *  supported through an inheritance scheme where the given type specifies
     *  a record that is a parent interface of the interface specified by
     *  <code> getType(). </code>.
     *
     *  @param object \osid_type_Type $recordType a type
     *
     * @return bool <code> true </code> if the given record <code> Type
     *                     </code> is implemented by this record, <code> false </code>
     *                     otherwise
     *
     * @throws \osid_NullArgumentException <code> recordType </code> is <code>
     *                                            null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function implementsRecordType(\osid_type_Type $recordType)
    {
        foreach ($this->localRecordTypes as $type) {
            if ($type->isEqual($recordType)) {
                return true;
            }
        }

        return false;
    }

    /**
     *  Gets the <code> CourseOffering </code> from which this record
     *  originated.
     *
     * @return object \osid_course_CourseOffering the course offering
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOffering()
    {
        return $this;
    }

    /*********************************************************
     * AlternatesRecord support
     *********************************************************/
    /**
     * Tests if this course offering has any alternate course offerings.
     *
     * @return bool <code> true </code> if this course offering has any
     *                     alternates, <code> false </code> otherwise
     *
     * @compliance mandatory This method must be implemented.
     */
    public function hasAlternates()
    {
        return $this->getOffering()->hasAlternates();
    }

    /**
     *  Gets the Ids of any alternate course offerings.
     *
     * @return object \osid_id_IdList the list of alternate ids
     *
     *  @compliance mandatory This method must be implemented.
     *
     * @throws \osid_OperationFailedException  unable to complete request
     * @throws \osid_PermissionDeniedException authorization failure
     */
    public function getAlternateIds()
    {
        return $this->getOffering()->getAlternateIds();
    }

    /**
     *  Gets the alternate <code> CourseOfferings </code>.
     *
     * @return object \osid_course_CourseOfferingList The list of alternates
     *
     *  @compliance mandatory This method must be implemented.
     *
     * @throws \osid_OperationFailedException  unable to complete request
     * @throws \osid_PermissionDeniedException authorization failure
     */
    public function getAlternates()
    {
        return $this->cacheSession->getCourseOfferingsByIds($this->getAlternateIds());
    }

    /**
     * Answer <code> true </code> if this course is the primary version in a group of
     * alternates.
     *
     * @return bool
     *
     *  @compliance mandatory This method must be implemented.
     *
     * @throws \osid_OperationFailedException  unable to complete request
     * @throws \osid_PermissionDeniedException authorization failure
     */
    public function isPrimary()
    {
        return $this->getOffering()->isPrimary();
    }
}
