<?php
/**
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Controller;

use App\Helper\RecentCourses\Department as DepartmentRecentCourses;
use App\Helper\RecentCourses\Instructor as InstructorRecentCourses;
use App\Helper\RecentCourses\RecentCoursesInterface;
use App\Service\Osid\IdMap;
use App\Service\Osid\Runtime;
use App\Service\Osid\TermHelper;
use App\Service\Osid\TopicHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * A controller for working with courses.
 *
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Courses extends AbstractController
{

    /**
     * @var \App\Service\Osid\Runtime
     */
    private $osidRuntime;

    /**
     * @var \App\Service\Osid\IdMap
     */
    private $osidIdMap;

    /**
     * @var \App\Service\Osid\TermHelper
     */
    private $osidTermHelper;

    /**
     * @var \App\Service\Osid\TopicHelper
     */
    private $osidTopicHelper;

    /**
     * Construct a new Catalogs controller.
     *
     * @param \App\Service\Osid\Runtime $osidRuntime
     *   The osid.runtime service.
     * @param \App\Service\Osid\IdMap $osidIdMap
     *   The osid.id_map service.
     * @param \App\Service\Osid\TermHelper $osidTermHelper
     *   The osid.term_helper service.
     * @param \App\Service\Osid\TopicHelper $osidTopicHelper
     *   The osid.topic_helper service.
     */
    public function __construct(Runtime $osidRuntime, IdMap $osidIdMap, TermHelper $osidTermHelper, TopicHelper $osidTopicHelper) {
        $this->osidRuntime = $osidRuntime;
        $this->osidIdMap = $osidIdMap;
        $this->osidTermHelper = $osidTermHelper;
        $this->osidTopicHelper = $osidTopicHelper;
        $this->alternateType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:alternates');
        $this->instructorsType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors');
        $this->namesType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:person_names');
    }

    #[Route('/courses/list/{catalog}', name: 'list_courses')]
    public function listAction($catalog = NULL)
    {
        $data = [
            'courses' => [],
        ];
        if ($catalog) {
            $catalogId = $this->osidIdMap->fromString($catalog);
            $lookupSession = $this->osidRuntime->getCourseManager()->getCourseLookupSessionForCatalog($catalogId);
            $data['title'] = 'Courses in '.$lookupSession->getCourseCatalog()->getDisplayName();
        } else {
            $lookupSession = $this->osidRuntime->getCourseManager()->getCourseLookupSession();
            $data['title'] = 'Courses in All Catalogs';
        }
        $lookupSession->useFederatedCourseCatalogView();

        $courses = $lookupSession->getCourses();
        while ($courses->hasNext()) {
            $data['courses'][] = $courses->getNextCourse();
        }
        // $this->setSelectedCatalogId($lookupSession->getCourseCatalogId());
        // $this->view->headTitle($this->view->title);
        // $this->view->menuIsCourses = true;

        return $this->render('courses/list.html.twig', $data);
    }

    #[Route('/courses/view/{course}/{term}', name: 'view_course')]
    public function view($course, $term = NULL)
    {
        $data = $this->getCourseDataByIdString($course, $term);

        // Set the selected Catalog Id.
        // $catalogSession = $this->osidRuntime->getCourseManager()->getCourseCatalogSession();
        // $catalogIds = $catalogSession->getCatalogIdsByCourse($id);
        // if ($catalogIds->hasNext()) {
        //     $this->setSelectedCatalogId($catalogIds->getNextId());
        // }

        // $this->view->menuIsCourses = true;

        // Bookmarked Courses and Schedules
        // $data['bookmarks_CourseId'] = $course->getId();

        return $this->render('courses/view.html.twig', $data);

    }

    #[Route('/courses/viewxml/{course}/{term}', name: 'view_course_xml')]
    public function viewxml($course, $term = NULL)
    {
        $data = [];
        $courseData = $this->getCourseDataByIdString($course, $term);
        $courseData['offerings'] = $this->getCourseOfferingsData($courseData['course'], $courseData['term']);
        $courseData['alternates'] = $this->getAllAlternates($courseData['course']);
        $data['courses'] = [$courseData];

        $data['title'] = $data['courses'][0]['course']->getDisplayName();
        $data['feedLink'] = $this->generateUrl('view_course', ['course' => $course], UrlGeneratorInterface::ABSOLUTE_URL);

        $response = new Response($this->renderView('courses/list.xml.twig', $data));
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');
        return $response;
    }

    /**
     * Answer an array of course data suitable for templating.
     *
     * @param string $idString
     *   The course id string.
     * @param string $termIdString
     *   A reference term's id string if one is being used for filtering
     *   offerings.
     *
     * @return array
     *   An array of data about the course.
     */
    protected function getCourseDataByIdString($idString, $termIdString = NULL)
    {
        $id = $this->osidIdMap->fromString($idString);
        $lookupSession = $this->osidRuntime->getCourseManager()->getCourseLookupSession();
        $lookupSession->useFederatedCourseCatalogView();

        if ($termIdString) {
            $termId = $this->osidIdMap->fromString($termIdString);
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
            $termLookupSession->useFederatedCourseCatalogView();
            $term = $termLookupSession->getTerm($termId);
        }
        else {
            $term = NULL;
        }

        return $this->getCourseData($lookupSession->getCourse($id), $term);
    }

    /**
     * Answer an array of course data suitable for templating.
     *
     * @param \osid_course_Course $course
     *   The course.
     * @param \osid_course_Term $term
     *   A reference term if one is being used for filtering offerings.
     *
     * @return array
     *   An array of data about the course.
     */
    protected function getCourseData(\osid_course_Course $course, \osid_course_Term|NULL $term = NULL) {
        $data = [];
        $data['course'] = $course;
        $data['term'] = $term;
        // Optional add-on data that can be populated by other methods.
        $data['is_primary'] = TRUE;
        $data['alternates'] = NULL;
        $data['offerings'] = [];
        $data['terms'] = [];
        $data['include_alternates_in_title'] = TRUE;
        // Load the topics into our view
        $data = array_merge($data, $this->getTopics($course->getTopics()));
        // Alternate status.
        $data['is_primary'] = TRUE;
        if ($course->hasRecordType($this->alternateType)) {
            $record = $course->getCourseRecord($this->alternateType);
            $data['is_primary'] = $record->isPrimary();
        }
        return $data;
    }

    /**
     * Answer a list of all alternates for a course.
     *
     * @param \osid_course_Course $course
     *   The course to get alternates for.
     *
     * @return osid_course_Course[]
     *   The courses, annotated with additional is_primary values.
     */
    protected function getAllAlternates(\osid_course_Course $course) {
        $data = NULL;
        if ($course->hasRecordType($this->alternateType)) {
            $record = $course->getCourseRecord($this->alternateType);
            $data = [];
            if ($record->hasAlternates()) {
                $alternates = $record->getAlternates();
                while ($alternates->hasNext()) {
                    $alternate = $alternates->getNextCourse();
                    $alternate->is_primary = FALSE;
                    if ($alternate->hasRecordType($this->alternateType)) {
                        $alternateRecord = $alternate->getCourseRecord($this->alternateType);
                        if ($alternateRecord->isPrimary()) {
                            $alternate->is_primary = TRUE;
                        }
                    }
                    $data[] = $alternate;
                }
            }
        }
        return $data;
    }

    /**
     * Answer a list of alternates for a course filtered to recent ones.
     *
     * @param \App\Helper\RecentCourses\RecentCoursesInterface $recentCourses
     *   The helper used to filter to recent courses.
     * @param \osid_course_Course $course
     *   The course to get alternates for.
     *
     * @return osid_course_Course[]
     *   The courses, annotated with additional is_primary values.
     */
    protected function getRecentAlternates(RecentCoursesInterface $recentCourses, $course) {
        $data = NULL;
        foreach ($recentCourses->getAlternatesForCourse($course) as $alternate) {
            $alternate->is_primary = FALSE;
            if ($alternate->hasRecordType($this->alternateType)) {
                $alternateRecord = $alternate->getCourseRecord($this->alternateType);
                if ($alternateRecord->isPrimary()) {
                    $alternate->is_primary = TRUE;
                }
            }
            $data[] = $alternate;
        }
        return $data;
    }

    /**
     * Answer an array of course offering data suitable for templating.
     *
     * @param \osid_course_Course $course
     *   The course offerings are associated with.
     * @param \osid_course_Term $term
     *   A reference term if one is being used for filtering offerings.
     *
     * @return array
     *   An array of course offering data.
     */
    protected function getCourseOfferingsData(\osid_course_Course $course, \osid_course_Term|NULL $term = NULL) {
        $data = [];
        $offeringLookupSession = $this->osidRuntime->getCourseManager()->getCourseOfferingLookupSession();
        $offeringLookupSession->useFederatedCourseCatalogView();
        if ($term) {
            $offerings = $offeringLookupSession->getCourseOfferingsByTermForCourse(
                $term->getId(),
                $course->getId(),
            );
        } else {
            $offerings = $offeringLookupSession->getCourseOfferingsForCourse($course->getId());
        }
        while ($offerings->hasNext()) {
            $offering = $offerings->getNextCourseOffering();

            if ($offering->hasRecordType($this->instructorsType)) {
                $instructorsRecord = $offering->getCourseOfferingRecord($this->instructorsType);
                $instructors = $instructorsRecord->getInstructors();
                $offering->instructors = [];
                $offering->instructorNames = [];
                if ($instructors->hasNext()) {
                    while ($instructors->hasNext()) {
                        $instructor = $instructors->getNextResource();
                        $instructorData = [
                            'resource' => $instructor,
                            'givename' => NULL,
                            'surname' => NULL,
                        ];
                        if ($instructor->hasRecordType($this->namesType)) {
                            $namesRecord = $instructor->getResourceRecord($this->namesType);
                            $instructorData['givename'] = $namesRecord->getGivenName();
                            $instructorData['surname'] = $namesRecord->getSurname();
                            $offering->instructorNames[] = $namesRecord->getSurname();
                        } else {
                            $offering->instructorNames[] = $instructor->getDisplayName();
                        }
                        $offering->instructors[] = $instructorData;
                    }
                }
            }

            $data[] = $offering;
        }

        return $data;
    }

    #[Route('/courses/searchxml/{catalog}', name: 'search_courses_xml')]
    public function searchxmlAction(Request $request, $catalog)
    {
        if (!$catalog) {
            header('HTTP/1.1 400 Bad Request');
            echo 'A catalog must be specified.';
            exit;
        }
        try {
            $catalogId = $this->osidIdMap->fromString($catalog);
            $searchSession = $this->osidRuntime->getCourseManager()->getCourseOfferingSearchSessionForCatalog($catalogId);
        } catch (\osid_InvalidArgumentException $e) {
            header('HTTP/1.1 400 Bad Request');
            echo 'The catalog id specified was not of the correct format.';
            exit;
        } catch (\osid_NotFoundException $e) {
            header('HTTP/1.1 404 Not Found');
            echo 'The catalog id specified was not found.';
            exit;
        }

        $keywords = trim($request->get('keywords'));

        $courses = [];
        // Fetch courses
        if (strlen($keywords)) {
            // For now we will do an offering search and return courses
            // only from it. If a course search session is available, it would
            // be preferable to use that.
            $query = $searchSession->getCourseOfferingQuery();
            $query->matchKeyword(
                $keywords,
                new \phpkit_type_URNInetType('urn:inet:middlebury.edu:search:wildcard'),
                true);
            $offerings = $searchSession->getCourseOfferingsByQuery($query);

            while ($offerings->hasNext() && count($courses) <= 20) {
                $offering = $offerings->getNextCourseOffering();
                $courseIdString = $this->osidIdMap->toString($offering->getCourseId());
                if (!isset($courses[$courseIdString])) {
                    try {
                        $courses[$courseIdString] = $offering->getCourse();
                    } catch (\osid_OperationFailedException $e) {
                    }
                }
            }
        }

        $data = [
            'courses' => [],
        ];
        foreach ($courses as $courseIdString => $course) {
            $data['courses'][] = $this->getCourseData($course);
        }
        $data['title'] = 'Course Search: "' . $keywords . '"';
        $data['feedLink'] = $this->generateUrl(
            'search_courses_xml',
            [
                'catalog' => $catalog,
                'keywords' => $request->get('keywords'),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $response = new Response($this->renderView('courses/list.xml.twig', $data));
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');
        return $response;
    }

    #[Route('/courses/topicxml/{catalog}', name: 'list_courses_by_topic')]
    public function topicxmlAction(Request $request, $catalog)
    {
        if (!$catalog) {
            header('HTTP/1.1 400 Bad Request');
            echo 'A catalog must be specified.';
            exit;
        }
        try {
            $catalogId = $this->osidIdMap->fromString($catalog);
            $searchSession = $this->osidRuntime->getCourseManager()->getCourseSearchSessionForCatalog($catalogId);

            $this->termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
        } catch (\osid_InvalidArgumentException $e) {
            header('HTTP/1.1 400 Bad Request');
            echo 'The catalog id specified was not of the correct format.';
            exit;
        } catch (\osid_NotFoundException $e) {
            header('HTTP/1.1 404 Not Found');
            echo 'The catalog id specified was not found.';
            exit;
        }

        if (!$request->get('topic')) {
            header('HTTP/1.1 400 Bad Request');
            echo 'A topic must be specified.';
            exit;
        }

        $topicsIds = [];
        if (is_array($request->get('topic'))) {
            foreach ($request->get('topic') as $idString) {
                $topicIds[] = $this->osidIdMap->fromString($idString);
            }
        } else {
            $topicIds[] = $this->osidIdMap->fromString($request->get('topic'));
        }

        // Fetch courses
        $query = $searchSession->getCourseQuery();

        $topicRecord = $query->getCourseQueryRecord(new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:topic'));
        foreach ($topicIds as $topicId) {
            $topicRecord->matchTopicId($topicId, true);
        }

        // Limit by location
        $locationIds = [];
        if (is_array($request->get('location'))) {
            foreach ($request->get('location') as $idString) {
                $locationIds[] = $this->osidIdMap->fromString($idString);
            }
        } elseif ($request->get('location')) {
            $locationIds[] = $this->osidIdMap->fromString($request->get('location'));
        }
        $locationRecord = $query->getCourseQueryRecord(new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:location'));
        foreach ($locationIds as $locationId) {
            $locationRecord->matchLocationId($locationId, true);
        }

        // Limit to just active courses
        $query->matchGenusType(new \phpkit_type_URNInetType('urn:inet:middlebury.edu:status-active'), true);

        $courses = $searchSession->getCoursesByQuery($query)->getCourses();

        $recentCourses = new DepartmentRecentCourses($this->osidIdMap, $courses);
        if ($request->get('cutoff')) {
            $recentCourses->setRecentInterval(new \DateInterval($request->get('cutoff')));
        }

        // Set the next and previous terms.
        $currentTermId = $this->osidTermHelper->getCurrentTermId($this->termLookupSession->getCourseCatalogId());
        $currentTerm = $this->termLookupSession->getTerm($currentTermId);

        $data = [
            'courses' => [],
        ];
        foreach ($recentCourses->getPrimaryCourses() as $course) {
            $courseData = $this->getCourseData($course);
            $courseData['terms'] = $this->getRecentTermData($currentTerm, $recentCourses, $course);
            $courseData['alternates'] = $this->getRecentAlternates($recentCourses, $course);
            $courseData['offerings'] = [];
            $data['courses'][] = $courseData;
        }

        $topicLookup = $this->osidRuntime->getCourseManager()->getTopicLookupSession();
        $topicLookup->useFederatedCourseCatalogView();
        $topicNames = [];
        foreach ($topicIds as $topicId) {
            try {
                $topic = $topicLookup->getTopic($topicId);
                $topicNames[] = $topic->getDisplayName();
            }
            catch (\osid_NotFoundException $e) {
                $topicNames[] = $this->osidIdMap->toString($topicId);
            }
        }
        $data['title'] = 'Courses in ' . implode(', ', $topicNames);
        $data['feedLink'] = $this->generateUrl(
            'list_courses_by_topic',
            [
                'catalog' => $catalog,
                'topic' => $request->get('topic'),
                'cutoff' => $request->get('cutoff'),
                'location' => $request->get('location'),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $response = new Response($this->renderView('courses/list.xml.twig', $data));
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');
        return $response;
    }

    #[Route('/courses/byidxml/{catalog}', name: 'list_courses_by_ids')]
    public function byidxmlAction(Request $request, $catalog)
    {
        if (!$catalog) {
            header('HTTP/1.1 400 Bad Request');
            echo 'A catalog must be specified.';
            exit;
        }
        try {
            $catalogId = $this->osidIdMap->fromString($catalog);
            $lookupSession = $this->osidRuntime->getCourseManager()->getCourseLookupSessionForCatalog($catalogId);
            $this->termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
        } catch (\osid_InvalidArgumentException $e) {
            header('HTTP/1.1 400 Bad Request');
            echo 'The catalog id specified was not of the correct format.';
            exit;
        } catch (\osid_NotFoundException $e) {
            header('HTTP/1.1 404 Not Found');
            echo 'The catalog id specified was not found.';
            exit;
        }
        $ids = $request->get('id', []);
        if (!$ids) {
            header('HTTP/1.1 400 Bad Request');
            echo "'id[]' must be specified.";
            exit;
        }

        $courseIds = [];
        if (is_array($ids)) {
            foreach ($ids as $idString) {
                $courseIds[] = $this->osidIdMap->fromString($idString);
            }
        } else {
            $courseIds[] = $this->osidIdMap->fromString($ids);
        }
        // Use Comparative view to include any found courses, ignoring missing ids.
        $lookupSession->useComparativeCourseView();

        $courses = $lookupSession->getCoursesByIds(new \phpkit_id_ArrayIdList($courseIds));

        $recentCourses = new DepartmentRecentCourses($this->osidIdMap, $courses);
        if ($request->get('cutoff')) {
            $recentCourses->setRecentInterval(new \DateInterval($request->get('cutoff')));
        }

        // Set the next and previous terms.
        $currentTermId = $this->osidTermHelper->getCurrentTermId($this->termLookupSession->getCourseCatalogId());
        $currentTerm = $this->termLookupSession->getTerm($currentTermId);

        $data = [
            'courses' => [],
        ];
        foreach ($recentCourses->getPrimaryCourses() as $course) {
            $courseData = $this->getCourseData($course);
            $courseData['terms'] = $this->getRecentTermData($currentTerm, $recentCourses, $course);
            $courseData['alternates'] = $this->getRecentAlternates($recentCourses, $course);
            $courseData['offerings'] = [];
            $data['courses'][] = $courseData;
        }
        $data['title'] = 'Courses by Id';
        $data['feedLink'] = $this->generateUrl(
            'list_courses_by_ids',
            [
                'catalog' => $catalog,
                'id' => $request->get('id'),
                'cutoff' => $request->get('cutoff'),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $response = new Response($this->renderView('courses/list.xml.twig', $data));
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');
        return $response;
    }

    /**
     * Build a list of courses associated with an instructor.
     *
     * The process is:
     *   1. Find sections taught by the instructor in the time-frame (default is past 4 years).
     *   3. For each section...
     *      a. Get the cross-listed sections from SSB_XLST
     *      b. Take the section plus its cross-listed sections, get their course
     *         entries and merge them into a single result.
     *
     */
    #[Route('/courses/instructorxml/{instructor}/{catalog}', name: 'list_courses_by_instructor')]
    public function instructorxmlAction(Request $request, $instructor, $catalog = NULL)
    {
        if (!$catalog) {
            $offeringSearchSession = $this->osidRuntime->getCourseManager()->getCourseOfferingSearchSession();
            $offeringSearchSession->useFederatedCourseCatalogView();
            $courseLookupSession = $this->osidRuntime->getCourseManager()->getCourseLookupSession();
            $courseLookupSession->useFederatedCourseCatalogView();

            // Allow term current/past to be limited to a certain catalog while courses are fetched from many
            if ($request->get('term_catalog')) {
                $catalogId = $this->osidIdMap->fromString($request->get('term_catalog'));
                $this->termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
            }
            // fall back to terms from any catalog.
            else {
                $this->termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
                $this->termLookupSession->useFederatedCourseCatalogView();
            }
        } else {
            try {
                $catalogId = $this->osidIdMap->fromString($catalog);
                $offeringSearchSession = $this->osidRuntime->getCourseManager()->getCourseOfferingSearchSessionForCatalog($catalogId);
                $courseLookupSession = $this->osidRuntime->getCourseManager()->getCourseLookupSessionForCatalog($catalogId);

                $this->termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
            } catch (\osid_InvalidArgumentException $e) {
                throw new osid_InvalidArgumentException('The catalog id specified was not of the correct format.');
            } catch (\osid_NotFoundException $e) {
                throw new osid_NotFoundException('The catalog id specified was not found.');
                exit;
            }
        }

        $instructor = trim($instructor);

        if (!$instructor || !strlen($instructor)) {
            // Make sure that this error response is cacheable.
            $this->setCacheControlHeaders();
            $this->getResponse()->sendHeaders();

            throw new \InvalidArgumentException('An instructor must be specified.');
        }

        $instructorId = $this->osidIdMap->fromString('resource.person.'.$instructor);
        $resourceLookup = $this->osidRuntime->getCourseManager()->getResourceManager()->getResourceLookupSession();
        try {
            $instructorResource = $resourceLookup->getResource($instructorId);
        } catch (\osid_NotFoundException $e) {
            // Make sure that this error response is cacheable.
            $this->setCacheControlHeaders();
            $this->getResponse()->sendHeaders();

            throw $e;
        }

        // Fetch Offerings
        $query = $offeringSearchSession->getCourseOfferingQuery();

        $instructorRecord = $query->getCourseOfferingQueryRecord(new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors'));
        $instructorRecord->matchInstructorId($instructorId, true);

        $order = $offeringSearchSession->getCourseOfferingSearchOrder();
        $order->orderByDisplayName();
        $search = $offeringSearchSession->getCourseOfferingSearch();
        $search->orderCourseOfferingResults($order);

        $courseOfferings = $offeringSearchSession->getCourseOfferingsBySearch($query, $search);

        $recentCourses = new InstructorRecentCourses($this->osidIdMap, $courseOfferings, $courseLookupSession);
        if ($request->get('cutoff')) {
            $recentCourses->setRecentInterval(new DateInterval($request->get('cutoff')));
        }

        // Set the next and previous terms.
        $currentTermId = $this->osidTermHelper->getCurrentTermId($this->termLookupSession->getCourseCatalogId());
        $currentTerm = $this->termLookupSession->getTerm($currentTermId);

        $data = [
            'courses' => [],
        ];
        foreach ($recentCourses->getPrimaryCourses() as $course) {
            $courseData = $this->getCourseData($course);
            $courseData['include_alternates_in_title'] = TRUE;
            $courseData['terms'] = $this->getRecentTermData($currentTerm, $recentCourses, $course);
            $courseData['alternates'] = $this->getRecentAlternates($recentCourses, $course);
            $courseData['offerings'] = [];
            $data['courses'][] = $courseData;
        }
        $data['title'] = 'Courses taught by '.$instructorResource->getDisplayName();
        $data['feedLink'] = $this->generateUrl(
            'list_courses_by_instructor',
            [
                'instructor' => $instructor,
                'catalog' => $catalog,
                'term_catalog' => $request->get('term_catalog'),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $response = new Response($this->renderView('courses/list.xml.twig', $data));
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');
        return $response;
    }

    /**
     * Output an RSS feed of courses from results.
     *
     * @param Helper_RecentCourses_Abstract $recentCourses
     * @param string                        $title
     * @param string                        $url
     *
     * @return void
     *
     * @since 10/19/09
     */
    protected function outputCourseFeed(Helper_RecentCourses_Interface $recentCourses, $title, $url)
    {
        // Set our cache-control headers since we will be flushing content soon.
        $this->setCacheControlHeaders();
        $this->getResponse()->sendHeaders();

        $now = $this->DateTime_getTimestamp(new DateTime());

        // Close the session before we send headers and content.
        session_write_close();

        header('Content-Type: text/xml');
        echo '<?xml version="1.0" encoding="utf-8" ?>
<rss version="2.0" xmlns:catalog="http://www.middlebury.edu/course_catalog">
    <channel>
        <title>'.htmlspecialchars($title).'</title>
        <link>'.$url.'</link>
        <description></description>
        <lastBuildDate>'.date('r').'</lastBuildDate>
        <generator>Course Catalog</generator>
        <docs>http://blogs.law.harvard.edu/tech/rss</docs>

';

        while (ob_get_level()) {
            ob_end_flush();
        }
        flush();

        // Set the next and previous terms
        $currentTermId = $this->osidTermHelper->getCurrentTermId($this->termLookupSession->getCourseCatalogId());
        $currentTerm = $this->termLookupSession->getTerm($currentTermId);
        $currentEndTime = $this->DateTime_getTimestamp($currentTerm->getEndTime());

        //         print "<description><![CDATA[";
        //         print ($courses->debug());
        //         print "]]></description>";

        $catalogSession = $this->osidRuntime->getCourseManager()->getCourseCatalogSession();
        $termsType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:terms');

        //         foreach ($groups as $key => $group) {
        //             print "\n$key";
        //             foreach ($group as $course) {
        //                 print "\n\t".$this->osidIdMap->toString($course->getId());
        //             }
        //         }

        foreach ($recentCourses->getPrimaryCourses() as $course) {
            $courseIdString = $this->osidIdMap->toString($course->getId());

            echo "\n\t\t<item>";

            echo "\n\t\t\t<title>";
            $alternates = $recentCourses->getAlternatesForCourse($course);
            $name = $course->getDisplayName();
            foreach ($alternates as $alt) {
                $name .= ' / '.$alt->getDisplayName();
            }
            echo htmlspecialchars($name.' - '.$course->getTitle());
            echo '</title>';

            echo "\n\t\t\t<link>";
            $catalog = $catalogSession->getCatalogIdsByCourse($course->getId());
            if ($catalog->hasNext()) {
                $catalogIdString = $this->osidIdMap->toString($catalog->getNextId());
            } else {
                $catalogIdString = null;
            }
            echo $this->_helper->pathAsAbsoluteUrl($this->_helper->url('view', 'courses', null, ['catalog' => $catalogIdString, 'course' => $courseIdString]));
            echo '</link>';

            echo "\n\t\t\t<guid isPermaLink='true'>";
            echo $this->_helper->pathAsAbsoluteUrl($this->_helper->url('view', 'courses', null, ['catalog' => $catalogIdString, 'course' => $courseIdString]));
            echo '</guid>';

            echo "\n\t\t\t<description><![CDATA[";
            echo $course->getDescription();
            echo ']]></description>';
            echo "\n\t\t\t<catalog:id>".$courseIdString.'</catalog:id>';
            echo "\n\t\t\t<catalog:display_name>".htmlspecialchars($course->getDisplayName()).'</catalog:display_name>';
            echo "\n\t\t\t<catalog:title>".htmlspecialchars($course->getTitle()).'</catalog:title>';

            foreach ($alternates as $alt) {
                echo "\n\t\t\t<catalog:alternate>";
                echo "\n\t\t\t\t<catalog:id>".$this->osidIdMap->toString($alt->getId()).'</catalog:id>';
                echo "\n\t\t\t\t<catalog:display_name>".htmlspecialchars($alt->getDisplayName()).'</catalog:display_name>';
                echo "\n\t\t\t\t<catalog:title>".htmlspecialchars($alt->getTitle()).'</catalog:title>';
                echo "\n\t\t\t</catalog:alternate>";
            }

            $recentTerms = $recentCourses->getTermsForCourse($course);
            if (count($recentTerms)) {
                $termStrings = [];
                foreach ($recentTerms as $term) {
                    echo "\n\t\t\t<catalog:term id=\"".$this->osidIdMap->toString($term->getId()).'"';
                    if ($term->getId()->isEqual($currentTermId)) {
                        echo ' type="current"';
                    } elseif ($currentEndTime < $this->DateTime_getTimestamp($term->getEndTime())) {
                        echo ' type="future"';
                    } elseif ($now > $this->DateTime_getTimestamp($term->getStartTime()) && $now < $this->DateTime_getTimestamp($term->getEndTime())) {
                        echo ' type="current"';
                    } else {
                        echo ' type="past"';
                    }
                    echo '>'.$term->getDisplayName().'</catalog:term>';
                }
            }

            $allTopics = $this->osidTopicHelper->topicListAsArray($course->getTopics());
            $topicType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.department');
            $topicTypeString = $this->_helper->osidType->toString($topicType);
            $topics = $this->osidTopicHelper->filterTopicsByType($allTopics, $topicType);
            foreach ($topics as $topic) {
                $topicParams['topic'] = $this->osidIdMap->toString($topic->getId());
                echo "\n\t\t\t<catalog:topic type=\"".$topicTypeString.'" id="'.$this->osidIdMap->toString($topic->getId()).'" href="'.$this->_helper->pathAsAbsoluteUrl($this->view->url($topicParams)).'">';
                echo $this->view->escape($topic->getDisplayName());
                echo '</catalog:topic> ';
            }

            $topicType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.requirement');
            $topicTypeString = $this->_helper->osidType->toString($topicType);
            $topics = $this->osidTopicHelper->filterTopicsByType($allTopics, $topicType);
            foreach ($topics as $topic) {
                $topicParams['topic'] = $this->osidIdMap->toString($topic->getId());
                echo "\n\t\t\t<catalog:topic type=\"".$topicTypeString.'" id="'.$this->osidIdMap->toString($topic->getId()).'" href="'.$this->_helper->pathAsAbsoluteUrl($this->view->url($topicParams)).'">';
                echo $this->view->escape($topic->getDisplayName());
                echo '</catalog:topic> ';
            }

            $topicType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.level');
            $topicTypeString = $this->_helper->osidType->toString($topicType);
            $topics = $this->osidTopicHelper->filterTopicsByType($allTopics, $topicType);
            foreach ($topics as $topic) {
                $topicParams['topic'] = $this->osidIdMap->toString($topic->getId());
                echo "\n\t\t\t<catalog:topic type=\"".$topicTypeString.'" id="'.$this->osidIdMap->toString($topic->getId()).'" href="'.$this->_helper->pathAsAbsoluteUrl($this->view->url($topicParams)).'">';
                echo $this->view->escape($topic->getDisplayName());
                echo '</catalog:topic> ';
            }

            echo "\n\t\t</item>";
            flush();
        }

        echo '
    </channel>
</rss>';
        exit;
    }

    /**
     * Answer an array of data that includes the term and past/current/future.
     *
     * @param \osid_course_Term $currentTerm
     *   The current term to compare against.
     * @param \App\Helper\RecentCourses\RecentCoursesInterface $recentCourses
     *   The helper used to filter to recent courses.
     * @param \osid_course_Course $course
     *   The course to get alternates for.
     *
     * @return array
     *   An array of term data. Sub-keys are 'term' (the Term object) and
     *   'type' (current/future/past).
     */
    protected function getRecentTermData(\osid_course_Term $currentTerm, RecentCoursesInterface $recentCourses, \osid_course_Course $course) {
        $now = $this->DateTime_getTimestamp(new \DateTime());
        $currentTermId = $currentTerm->getId();
        $currentEndTime = $this->DateTime_getTimestamp($currentTerm->getEndTime());
        $recentTerms = $recentCourses->getTermsForCourse($course);
        $data = [];
        if (count($recentTerms)) {
            foreach ($recentTerms as $term) {
                if ($term->getId()->isEqual($currentTermId)) {
                    $type = 'current';
                } elseif ($currentEndTime < $this->DateTime_getTimestamp($term->getEndTime())) {
                    $type = 'future';
                } elseif ($now > $this->DateTime_getTimestamp($term->getStartTime()) && $now < $this->DateTime_getTimestamp($term->getEndTime())) {
                    $type = 'current';
                } else {
                    $type = 'past';
                }
                $data[] = [
                    'term' => $term,
                    'type' => $type,
                ];
            }
        }
        return $data;
    }

    public function DateTime_getTimestamp($dt)
    {
        $dtz_original = $dt->getTimezone();
        $dtz_utc = new \DateTimeZone('UTC');
        $dt->setTimezone($dtz_utc);
        $year = (int) $dt->format('Y');
        $month = (int) $dt->format('n');
        $day = (int) $dt->format('j');
        $hour = (int) $dt->format('G');
        $minute = (int) $dt->format('i');
        $second = (int) $dt->format('s');
        $dt->setTimezone($dtz_original);

        return gmmktime($hour, $minute, $second, $month, $day, $year);
    }

    /**
     * Load topics into our view.
     *
     * @param osid_course_TopicList
     *
     * @return void
     *
     * @since 4/28/09
     */
    protected function getTopics(\osid_course_TopicList $topicList)
    {
        $data = [];
        $topics = $this->osidTopicHelper->topicListAsArray($topicList);

        $data['subjectTopics'] = $this->osidTopicHelper->filterTopicsByType($topics, new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.subject'));

        $data['departmentTopics'] = $this->osidTopicHelper->filterTopicsByType($topics, new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.department'));

        $data['divisionTopics'] = $this->osidTopicHelper->filterTopicsByType($topics, new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.division'));

        $data['requirementTopics'] = $this->osidTopicHelper->filterTopicsByType($topics, new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.requirement'));

        $data['levelTopics'] = $this->osidTopicHelper->filterTopicsByType($topics, new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.level'));

        $data['blockTopics'] = $this->osidTopicHelper->filterTopicsByType($topics, new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.block'));

        $data['instructionMethodTopics'] = $this->osidTopicHelper->filterTopicsByType($topics, new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.instruction_method'));

        return $data;
    }
}
