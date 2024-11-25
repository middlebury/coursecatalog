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
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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
    public function __construct(
        private Runtime $osidRuntime,
        private IdMap $osidIdMap,
        private TermHelper $osidTermHelper,
        private DataLoader $osidDataLoader,
    ) {
    }

    #[Route('/courses/list/{catalogId}', name: 'list_courses')]
    public function listAction(\osid_id_Id $catalogId)
    {
        $data = [
            'courses' => [],
        ];
        $lookupSession = $this->osidRuntime->getCourseManager()->getCourseLookupSessionForCatalog($catalogId);
        $data['title'] = 'Courses in '.$lookupSession->getCourseCatalog()->getDisplayName();
        $data['catalogId'] = $catalogId;
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

    #[Route('/courses/view/{courseId}/{termId}', name: 'view_course')]
    public function view(\osid_id_Id $courseId, ?\osid_id_Id $termId = null)
    {
        $data = $this->osidDataLoader->getCourseDataById($courseId, $termId);
        $data['offerings'] = $this->osidDataLoader->getCourseOfferingsData($data['course'], $data['term']);

        // Set the selected Catalog Id.
        $data['catalogId'] = null;
        $catalogSession = $this->osidRuntime->getCourseManager()->getCourseCatalogSession();
        $catalogIds = $catalogSession->getCatalogIdsByCourse($data['course']->getId());
        if ($catalogIds->hasNext()) {
            $data['catalogId'] = $catalogIds->getNextId();
        }

        return $this->render('courses/view.html.twig', $data);
    }

    #[Route('/courses/viewxml/{courseId}/{termId}', name: 'view_course_xml')]
    public function viewxml(\osid_id_Id $courseId, ?\osid_id_Id $termId = null)
    {
        $data = [];
        $courseData = $this->osidDataLoader->getCourseDataById($courseId, $termId);
        $courseData['offerings'] = $this->osidDataLoader->getCourseOfferingsData($courseData['course'], $courseData['term']);
        $courseData['alternates'] = $this->osidDataLoader->getAllCourseAlternates($courseData['course']);
        $data['courses'] = [$courseData];

        $data['title'] = $data['courses'][0]['course']->getDisplayName();
        $data['feedLink'] = $this->generateUrl('view_course', ['courseId' => $courseId], UrlGeneratorInterface::ABSOLUTE_URL);

        $response = new Response($this->renderView('courses/list.xml.twig', $data));
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');

        return $response;
    }

    #[Route('/courses/searchxml/{catalogId}', name: 'search_courses_xml')]
    public function searchxmlAction(Request $request, \osid_id_Id $catalogId)
    {
        $searchSession = $this->osidRuntime->getCourseManager()->getCourseOfferingSearchSessionForCatalog($catalogId);

        $keywords = $request->get('keywords');
        if (!empty($keywords)) {
            $keywords = trim($keywords);
        }

        $courses = [];
        // Fetch courses
        if ($keywords && strlen($keywords)) {
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
        $data['title'] = 'Course Search: "'.$keywords.'"';
        $data['feedLink'] = $this->generateUrl(
            'search_courses_xml',
            [
                'catalogId' => $catalogId,
                'keywords' => $request->get('keywords'),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $response = new Response($this->renderView('courses/list.xml.twig', $data));
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');

        return $response;
    }

    #[Route('/courses/topicxml/{catalogId}', name: 'list_courses_by_topic')]
    public function topicxmlAction(Request $request, \osid_id_Id $catalogId)
    {
        $searchSession = $this->osidRuntime->getCourseManager()->getCourseSearchSessionForCatalog($catalogId);
        $this->termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);

        if (!$request->get('topic')) {
            throw new BadRequestHttpException('A topic must be specified.');
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

        $recentCourses = new DepartmentRecentCourses($this->osidIdMap, $courses, $this->getParameter('app.osid.reference_date'));
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
            } catch (\osid_NotFoundException $e) {
                $topicNames[] = $this->osidIdMap->toString($topicId);
            }
        }
        $data['title'] = 'Courses in '.implode(', ', $topicNames);
        $data['feedLink'] = $this->generateUrl(
            'list_courses_by_topic',
            [
                'catalogId' => $catalogId,
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

    #[Route('/courses/byidxml/{catalogId}', name: 'list_courses_by_ids')]
    public function byidxmlAction(Request $request, \osid_id_Id $catalogId)
    {
        $lookupSession = $this->osidRuntime->getCourseManager()->getCourseLookupSessionForCatalog($catalogId);
        $this->termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);

        $ids = $request->get('id', []);
        if (!$ids) {
            throw new BadRequestHttpException("'id[]' must be specified.");
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

        $recentCourses = new DepartmentRecentCourses($this->osidIdMap, $courses, $this->getParameter('app.osid.reference_date'));
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
                'catalogId' => $catalogId,
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
     */
    #[Route('/courses/instructorxml/{instructorId}/{catalogId}', name: 'list_courses_by_instructor')]
    public function instructorxmlAction(Request $request, \osid_id_Id $instructorId, ?\osid_id_Id $catalogId = null)
    {
        if (!$catalogId) {
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
            $offeringSearchSession = $this->osidRuntime->getCourseManager()->getCourseOfferingSearchSessionForCatalog($catalogId);
            $courseLookupSession = $this->osidRuntime->getCourseManager()->getCourseLookupSessionForCatalog($catalogId);
            $this->termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
        }

        if (!$instructorId) {
            // @todo Make sure that this error response is cacheable.
            throw new BadRequestHttpException('An instructor must be specified.');
        }

        // Expand plain instructor Ids to fully qualified ones.
        if (!preg_match('/^resource\.person\./', $this->osidIdMap->toString($instructorId))) {
            $instructorId = $this->osidIdMap->fromString('resource.person.'.$this->osidIdMap->toString($instructorId));
        }
        $resourceLookup = $this->osidRuntime->getCourseManager()->getResourceManager()->getResourceLookupSession();
        $instructorResource = $resourceLookup->getResource($instructorId);

        // Fetch Offerings
        $query = $offeringSearchSession->getCourseOfferingQuery();

        $instructorRecord = $query->getCourseOfferingQueryRecord(new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors'));
        $instructorRecord->matchInstructorId($instructorId, true);

        $order = $offeringSearchSession->getCourseOfferingSearchOrder();
        $order->orderByDisplayName();
        $search = $offeringSearchSession->getCourseOfferingSearch();
        $search->orderCourseOfferingResults($order);

        $courseOfferings = $offeringSearchSession->getCourseOfferingsBySearch($query, $search);

        $recentCourses = new InstructorRecentCourses($this->osidIdMap, $courseOfferings, $courseLookupSession, $this->getParameter('app.osid.reference_date'));
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
            $courseData['include_alternates_in_title'] = true;
            $courseData['terms'] = $this->osidDataLoader->getRecentTermDataForCourse($currentTerm, $recentCourses, $course);
            $courseData['alternates'] = $this->osidDataLoader->getRecentCourseAlternates($recentCourses, $course);
            $courseData['offerings'] = [];
            $data['courses'][] = $courseData;
        }
        $data['title'] = 'Courses taught by '.$instructorResource->getDisplayName();
        $data['feedLink'] = $this->generateUrl(
            'list_courses_by_instructor',
            [
                'instructorId' => $instructorId,
                'catalogId' => $catalogId,
                'term_catalog' => $request->get('term_catalog'),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $response = new Response($this->renderView('courses/list.xml.twig', $data));
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');

        return $response;
    }
}
