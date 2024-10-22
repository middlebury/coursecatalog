<?php
/**
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Controller;

use App\Helper\RecentCourses\Department as DepartmentRecentCourses;
use App\Helper\RecentCourses\Instructor as InstructorRecentCourses;
use App\Service\Osid\DataLoader;
use App\Service\Osid\IdMap;
use App\Service\Osid\Runtime;
use App\Service\Osid\TermHelper;
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
     * @var \App\Service\Osid\DataLoader
     */
    private $osidDataLoader;

    /**
     * Construct a new Catalogs controller.
     *
     * @param \App\Service\Osid\Runtime $osidRuntime
     *   The osid.runtime service.
     * @param \App\Service\Osid\IdMap $osidIdMap
     *   The osid.id_map service.
     * @param \App\Service\Osid\TermHelper $osidTermHelper
     *   The osid.term_helper service.
     * @param \App\Service\Osid\DataLoader $osidDataLoader
     *   The osid.topic_helper service.
     */
    public function __construct(Runtime $osidRuntime, IdMap $osidIdMap, TermHelper $osidTermHelper, DataLoader $osidDataLoader) {
        $this->osidRuntime = $osidRuntime;
        $this->osidIdMap = $osidIdMap;
        $this->osidTermHelper = $osidTermHelper;
        $this->osidDataLoader = $osidDataLoader;
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
        $data = $this->osidDataLoader->getCourseDataByIdString($course, $term);
        $data['offerings'] = $this->osidDataLoader->getCourseOfferingsData($data['course'], $data['term']);

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
        $courseData = $this->osidDataLoader->getCourseDataByIdString($course, $term);
        $courseData['offerings'] = $this->osidDataLoader->getCourseOfferingsData($courseData['course'], $courseData['term']);
        $courseData['alternates'] = $this->osidDataLoader->getAllCourseAlternates($courseData['course']);
        $data['courses'] = [$courseData];

        $data['title'] = $data['courses'][0]['course']->getDisplayName();
        $data['feedLink'] = $this->generateUrl('view_course', ['course' => $course], UrlGeneratorInterface::ABSOLUTE_URL);

        $response = new Response($this->renderView('courses/list.xml.twig', $data));
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');
        return $response;
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
            $data['courses'][] = $this->osidDataLoader->getCourseData($course);
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
            $courseData = $this->osidDataLoader->getCourseData($course);
            $courseData['terms'] = $this->osidDataLoader->getRecentTermDataForCourse($currentTerm, $recentCourses, $course);
            $courseData['alternates'] = $this->osidDataLoader->getRecentCourseAlternates($recentCourses, $course);
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
            $courseData = $this->osidDataLoader->getCourseData($course);
            $courseData['terms'] = $this->osidDataLoader->getRecentTermDataForCourse($currentTerm, $recentCourses, $course);
            $courseData['alternates'] = $this->osidDataLoader->getRecentCourseAlternates($recentCourses, $course);
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
                throw new \osid_InvalidArgumentException('The catalog id specified was not of the correct format.');
            } catch (\osid_NotFoundException $e) {
                throw new \osid_NotFoundException('The catalog id specified was not found.');
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

        if (preg_match('/^resource\.person\./', $instructor)) {
            $instructorId = $this->osidIdMap->fromString($instructor);
        }
        else {
            $instructorId = $this->osidIdMap->fromString('resource.person.'.$instructor);
        }
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
            $courseData = $this->osidDataLoader->getCourseData($course);
            $courseData['include_alternates_in_title'] = TRUE;
            $courseData['terms'] = $this->osidDataLoader->getRecentTermDataForCourse($currentTerm, $recentCourses, $course);
            $courseData['alternates'] = $this->osidDataLoader->getRecentCourseAlternates($recentCourses, $course);
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


}
