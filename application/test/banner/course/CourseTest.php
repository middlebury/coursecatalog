<?php

/**
 * Test class for banner_course_Course.
 * Generated by PHPUnit on 2009-04-15 at 13:36:26.
 */
class banner_course_CourseTest extends phpkit_test_phpunit_AbstractOsidObjectTest
{
    use banner_DatabaseTestTrait;

    /**
     * @var banner_course_Course
     */
    protected osid_course_Course $object;

    private osid_course_CourseLookupSession $session;
    private osid_id_Id $mcugId;
    private osid_id_Id $physId;
    private osid_id_Id $geolId;
    private osid_id_Id $geogId;
    private osid_id_Id $chemId;
    private osid_type_Type $termRecordType;
    private osid_type_Type $alternatesType;

    /**
     * Answer the Object to test.
     *
     * @return osid_OsidObject
     *
     * @since 4/15/09
     */
    protected function getObject()
    {
        return $this->object;
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->mcugId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:catalog-MCUG');
        $this->physId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:course-PHYS0201');
        $this->geolId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:course-GEOL0250');
        $this->geogId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:course-GEOG0250');
        $this->chemId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:course-CHEM0104');
        $this->session = self::getCourseManager()->getCourseLookupSessionForCatalog($this->mcugId);
        $this->object = $this->session->getCourse($this->physId);

        $this->termRecordType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:terms');
        $this->alternatesType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:alternates');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
        $this->session->close();
    }

    /**
     * @todo Implement testGetTitle().
     */
    public function testGetTitle()
    {
        $this->assertIsString($this->object->getTitle());
    }

    /**
     * Test that the title of the chemistry Course has the more recent version of the title.
     * Effective 200390 and 200490, the title is "Fundamentals of Chemistry II", however
     * effective 200520, the title changed to "General Chemistry II".
     */
    public function testEffectiveDateTitle()
    {
        $object = $this->session->getCourse($this->chemId);
        $this->assertEquals('General Chemistry II', $object->getTitle());
    }

    public function testGetPhysDescription()
    {
        $this->assertEquals("This course probes a number of areas for which classical physics has provided no adequate explanations. Topics covered include Einstein's special relativity, quantization of atomic energy levels and photons, the atomic models of Rutherford and Bohr, and wave-particle duality. (PHYS 0109, MATH 0122, PHYS 0110 concurrent or prior) 3 hrs. lect.", $this->object->getDescription());
    }

    /**
     * @todo Implement testGetNumber().
     */
    public function testGetNumber()
    {
        $this->assertIsString($this->object->getNumber());
    }

    /**
     * @todo Implement testGetCredits().
     */
    public function testGetCredits()
    {
        $this->assertIsFloat($this->object->getCredits());
    }

    /**
     * @todo Implement testGetPrereqInfo().
     */
    public function testGetPrereqInfo()
    {
        $this->assertIsString($this->object->getTitle());
    }

    public function testGetTopicIds()
    {
        $list = $this->object->getTopicIds();
        $this->assertInstanceOf('osid_id_IdList', $list);
        $this->assertEquals(4, $list->available());
        $this->assertInstanceOf('osid_id_Id', $list->getNextId());
    }

    public function testGetTopics()
    {
        $list = $this->object->getTopics();
        $this->assertInstanceOf('osid_course_TopicList', $list);
        $this->assertEquals(4, $list->available());
        $this->assertInstanceOf('osid_course_Topic', $list->getNextTopic());
    }

    public function testTopicIds()
    {
        $list = $this->object->getTopicIds();
        $identifiers = [
            'topic-subject-PHYS',
            'topic-department-PHYS',
            'topic-division-NSCI',
            'topic-level-UG',
        ];
        $found = [];
        $this->assertTrue($list->hasNext());
        while ($list->hasNext()) {
            $found[] = $list->getNextId()->getIdentifier();
        }
        foreach ($identifiers as $id) {
            if (!in_array($id, $found)) {
                $this->fail('Topic "'.$id.'" was not found.');
            }
        }
    }

    /**
     * @todo Implement testGetCourseRecord().
     */
    public function testGetCourseRecord()
    {
        $types = $this->object->getRecordTypes();
        while ($types->hasNext()) {
            $this->assertInstanceOf('osid_course_CourseRecord', $this->object->getCourseRecord($types->getNextType()));
        }
    }

    /*********************************************************
     * Tests for the TermsRecord
     *********************************************************/
    public function testSupportsTermRecord()
    {
        $this->assertTrue($this->object->hasRecordType($this->termRecordType));
    }

    public function testGetTermRecord()
    {
        $record = $this->object->getCourseRecord($this->termRecordType);
        $this->assertInstanceOf('middlebury_course_Course_TermsRecord', $record);
        $this->assertInstanceOf('osid_course_CourseRecord', $record);
    }

    public function testTermRecordImplementsRecordType()
    {
        $record = $this->object->getCourseRecord($this->termRecordType);
        $this->assertTrue($record->implementsRecordType($this->termRecordType));
    }

    public function testGetTermRecordCourse()
    {
        $record = $this->object->getCourseRecord($this->termRecordType);
        $course = $record->getCourse();
        $this->assertInstanceOf('osid_course_Course', $course);
    }

    public function testGetTermIds()
    {
        $record = $this->object->getCourseRecord($this->termRecordType);
        $ids = $record->getTermIds();
        $this->assertEquals(7, $ids->available());
        $this->assertInstanceOf('osid_id_Id', $ids->getNextId());

        $next4 = $ids->getNextIds(4);
        $this->assertCount(4, $next4);
        foreach ($next4 as $id) {
            $this->assertInstanceOf('osid_id_Id', $id);
        }
    }

    public function testGetTerms()
    {
        $record = $this->object->getCourseRecord($this->termRecordType);
        $terms = $record->getTerms();
        $this->assertEquals(7, $terms->available());
        $this->assertInstanceOf('osid_course_Term', $terms->getNextTerm());
    }

    public function testGetChemTerms()
    {
        $object = $this->session->getCourse($this->chemId);
        $record = $object->getCourseRecord($this->termRecordType);
        $terms = $record->getTerms();
        $this->assertEquals(13, $terms->available());
        $this->assertInstanceOf('osid_course_Term', $terms->getNextTerm());
    }

    /*********************************************************
     * Tests for AlternatesRecord.
     *********************************************************/
    public function testHasAlternates()
    {
        $record = $this->object->getCourseRecord($this->alternatesType);
        $this->assertFalse($record->hasAlternates());
    }

    public function testGetAlternateIds()
    {
        $record = $this->object->getCourseRecord($this->alternatesType);
        $ids = $record->getAlternateIds();
        $this->assertInstanceOf('osid_id_IdList', $ids);
    }

    public function testGetAlternates()
    {
        $record = $this->object->getCourseRecord($this->alternatesType);
        $alternates = $record->getAlternates();
        $this->assertInstanceOf('osid_course_CourseList', $alternates);
    }

    public function testHasGeolAlternates()
    {
        $course = $this->session->getCourse($this->geolId);
        $record = $course->getCourseRecord($this->alternatesType);
        $this->assertTrue($record->hasAlternates());
    }

    public function testGetGeolAlternateIds()
    {
        $course = $this->session->getCourse($this->geolId);
        $record = $course->getCourseRecord($this->alternatesType);
        $ids = $record->getAlternateIds();
        $this->assertInstanceOf('osid_id_IdList', $ids);
        $this->assertEquals(1, $ids->available());
    }

    public function testGetGeolAlternates()
    {
        $course = $this->session->getCourse($this->geolId);
        $record = $course->getCourseRecord($this->alternatesType);
        $alternates = $record->getAlternates();
        $this->assertInstanceOf('osid_course_CourseList', $alternates);
        $this->assertEquals(1, $alternates->available());
    }
    //
    //     /**
    //      *
    //      */
    //     public function testGeogIsPrimary () {
    //     	$course = $this->session->getCourse($this->geogId);
    //     	$record = $course->getCourseRecord($this->alternatesType);
    //     	$this->assertFalse($record->isPrimary());
    //     }
    //
    //     /**
    //      *
    //      */
    //     public function testGeolIsPrimary () {
    //     	$course = $this->session->getCourse($this->geolId);
    //     	$record = $course->getCourseRecord($this->alternatesType);
    //     	$this->assertTrue($record->isPrimary());
    //     }
}
