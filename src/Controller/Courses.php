<?php
/**
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Controller;

use App\Service\Osid\IdMap;
use App\Service\Osid\Runtime;
use App\Service\Osid\TermHelper;
use App\Service\Osid\TopicHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    /**
     * Print out a list of all courses.
     *
     * @return void
     *
     * @since 4/21/09
     */
    public function listAction()
    {
        if ($this->_getParam('catalog')) {
            $catalogId = $this->osidIdMap->fromString($this->_getParam('catalog'));
            $lookupSession = $this->osidRuntime->getCourseManager()->getCourseLookupSessionForCatalog($catalogId);
            $this->view->title = 'Courses in '.$lookupSession->getCourseCatalog()->getDisplayName();
        } else {
            $lookupSession = $this->osidRuntime->getCourseManager()->getCourseLookupSession();
            $this->view->title = 'Courses in All Catalogs';
        }
        $lookupSession->useFederatedCourseCatalogView();

        $this->view->courses = $lookupSession->getCourses();

        $this->setSelectedCatalogId($lookupSession->getCourseCatalogId());
        $this->view->headTitle($this->view->title);

        $this->view->menuIsCourses = true;
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
        $data['courses'] = [$this->getCourseDataByIdString($course, $term)];

        $data['title'] = $data['courses'][0]['course']->getDisplayName();
        $data['feedLink'] = $this->generateUrl('view_course', ['course' => $course], UrlGeneratorInterface::ABSOLUTE_URL);

        $response = new Response($this->renderView('courses/list.xml.twig', $data));
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');
        return $response;
    }

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

    protected function getCourseData(\osid_course_Course $course, \osid_course_Term|NULL $term = NULL) {
        $data['course'] = $course;
        // Load the topics into our view
        $data = array_merge($data, $this->getTopics($course->getTopics()));

        // Alternates
        $data['is_primary'] = TRUE;
        $data['alternates'] = NULL;
        if ($course->hasRecordType($this->alternateType)) {
            $record = $course->getCourseRecord($this->alternateType);
            $data['is_primary'] = $record->isPrimary();
            if ($record->hasAlternates()) {
                $data['alternates'] = [];
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
                    $data['alternates'][] = $alternate;
                }
            }
        }

        // Term
        $data['term'] = $term;

        // offerings.
        $data['offerings'] = [];
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

            $data['offerings'][] = $offering;
        }

        return $data;
    }

    /**
     * Search for courses.
     *
     * @return void
     *
     * @since 6/15/09
     */
    public function searchxmlAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        if (!$this->_getParam('catalog')) {
            header('HTTP/1.1 400 Bad Request');
            echo 'A catalog must be specified.';
            exit;
        }
        try {
            $catalogId = $this->osidIdMap->fromString($this->_getParam('catalog'));
            $searchSession = $this->osidRuntime->getCourseManager()->getCourseOfferingSearchSessionForCatalog($catalogId);
        } catch (osid_InvalidArgumentException $e) {
            header('HTTP/1.1 400 Bad Request');
            echo 'The catalog id specified was not of the correct format.';
            exit;
        } catch (osid_NotFoundException $e) {
            header('HTTP/1.1 404 Not Found');
            echo 'The catalog id specified was not found.';
            exit;
        }

        $keywords = trim($this->_getParam('keywords'));
        $searchUrl = $this->_helper->pathAsAbsoluteUrl($this->_helper->url('search', 'offerings', null, ['catalog' => $this->_getParam('catalog'), 'keywords' => $keywords, 'submit' => 'Search']));

        header('Content-Type: text/xml');
        echo '<?xml version="1.0" encoding="utf-8" ?>
<rss version="2.0" xmlns:catalog="http://www.middlebury.edu/course_catalog">
    <channel>
        <title>Course Search: "'.htmlspecialchars($keywords).'"</title>
        <link>'.$searchUrl.'</link>
        <description></description>
        <lastBuildDate>'.date('r').'</lastBuildDate>
        <generator>Course Catalog</generator>
        <docs>http://blogs.law.harvard.edu/tech/rss</docs>

';
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
                    } catch (osid_OperationFailedException $e) {
                        //                         print "\n<item><title>Failure on ".$offering->getDisplayName()."</title><description><![CDATA[<pre>OfferingId:\n".print_r($offering->getId(), true)."\n\nCourseId:\n".print_r($offering->getCourseId(), true)."</pre>]]></description></item>";
                    }
                }
            }
        }

        // Print out courses as items.
        foreach ($courses as $courseIdString => $course) {
            echo "\n\t\t<item>";

            echo "\n\t\t\t<title>";
            echo htmlspecialchars($course->getDisplayName().' - '.$course->getTitle());
            echo '</title>';

            echo "\n\t\t\t<link>";
            echo $this->_helper->pathAsAbsoluteUrl($this->_helper->url('view', 'courses', null, ['catalog' => $this->_getParam('catalog'), 'course' => $courseIdString]));
            echo '</link>';

            echo "\n\t\t\t<guid isPermaLink='true'>";
            echo $this->_helper->pathAsAbsoluteUrl($this->_helper->url('view', 'courses', null, ['catalog' => $this->_getParam('catalog'), 'course' => $courseIdString]));
            echo '</guid>';

            echo "\n\t\t\t<description><![CDATA[";
            echo $course->getDescription();
            echo ']]></description>';
            echo "\n\t\t\t<catalog:id>".$courseIdString.'</catalog:id>';

            echo "\n\t\t</item>";
        }

        echo '
    </channel>
</rss>';

        exit;
    }

    /**
     * Search for courses.
     *
     * @return void
     *
     * @since 6/15/09
     */
    public function topicxmlAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        if (!$this->_getParam('catalog')) {
            header('HTTP/1.1 400 Bad Request');
            echo 'A catalog must be specified.';
            exit;
        }
        try {
            $catalogId = $this->osidIdMap->fromString($this->_getParam('catalog'));
            $searchSession = $this->osidRuntime->getCourseManager()->getCourseSearchSessionForCatalog($catalogId);

            $this->termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
        } catch (osid_InvalidArgumentException $e) {
            header('HTTP/1.1 400 Bad Request');
            echo 'The catalog id specified was not of the correct format.';
            exit;
        } catch (osid_NotFoundException $e) {
            header('HTTP/1.1 404 Not Found');
            echo 'The catalog id specified was not found.';
            exit;
        }

        if (!$this->_getParam('topic')) {
            header('HTTP/1.1 400 Bad Request');
            echo 'A topic must be specified.';
            exit;
        }

        $topicsIds = [];
        if (is_array($this->_getParam('topic'))) {
            foreach ($this->_getParam('topic') as $idString) {
                $topicIds[] = $this->osidIdMap->fromString($idString);
            }
        } else {
            $topicIds[] = $this->osidIdMap->fromString($this->_getParam('topic'));
        }

        $searchUrl = $this->_helper->pathAsAbsoluteUrl($this->_helper->url('search', 'offerings', null, []));

        // Fetch courses
        $query = $searchSession->getCourseQuery();

        $topicRecord = $query->getCourseQueryRecord(new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:topic'));
        foreach ($topicIds as $topicId) {
            $topicRecord->matchTopicId($topicId, true);
        }

        // Limit by location
        $locationIds = [];
        if (is_array($this->_getParam('location'))) {
            foreach ($this->_getParam('location') as $idString) {
                $locationIds[] = $this->osidIdMap->fromString($idString);
            }
        } elseif ($this->_getParam('location')) {
            $locationIds[] = $this->osidIdMap->fromString($this->_getParam('location'));
        }
        $locationRecord = $query->getCourseQueryRecord(new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:location'));
        foreach ($locationIds as $locationId) {
            $locationRecord->matchLocationId($locationId, true);
        }

        // Limit to just active courses
        $query->matchGenusType(new \phpkit_type_URNInetType('urn:inet:middlebury.edu:status-active'), true);

        $courses = $searchSession->getCoursesByQuery($query)->getCourses();

        $topicLookup = $this->osidRuntime->getCourseManager()->getTopicLookupSession();
        $topicLookup->useFederatedCourseCatalogView();
        $topic = $topicLookup->getTopic($topicId);

        $recentCourses = new Helper_RecentCourses_Department($courses);
        if ($this->_getParam('cutoff')) {
            $recentCourses->setRecentInterval(new DateInterval($this->_getParam('cutoff')));
        }
        $this->outputCourseFeed($recentCourses, htmlentities('Courses in  '.$topic->getDisplayName()), $searchUrl);
    }

    /**
     * Search for courses.
     *
     * @return void
     *
     * @since 6/15/09
     */
    public function byidxmlAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        if (!$this->_getParam('catalog')) {
            header('HTTP/1.1 400 Bad Request');
            echo 'A catalog must be specified.';
            exit;
        }
        try {
            $catalogId = $this->osidIdMap->fromString($this->_getParam('catalog'));
            $lookupSession = $this->osidRuntime->getCourseManager()->getCourseLookupSessionForCatalog($catalogId);
            $this->termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
        } catch (osid_InvalidArgumentException $e) {
            header('HTTP/1.1 400 Bad Request');
            echo 'The catalog id specified was not of the correct format.';
            exit;
        } catch (osid_NotFoundException $e) {
            header('HTTP/1.1 404 Not Found');
            echo 'The catalog id specified was not found.';
            exit;
        }

        if (!$this->_getParam('id')) {
            header('HTTP/1.1 400 Bad Request');
            echo "'id[]' must be specified.";
            exit;
        }

        $courseIds = [];
        if (is_array($this->_getParam('id'))) {
            foreach ($this->_getParam('id') as $idString) {
                $courseIds[] = $this->osidIdMap->fromString($idString);
            }
        } else {
            $courseIds[] = $this->osidIdMap->fromString($this->_getParam('id'));
        }

        // Use Comparative view to include any found courses, ignoring missing ids.
        $lookupSession->useComparativeCourseView();

        $courses = $lookupSession->getCoursesByIds(new phpkit_id_ArrayIdList($courseIds));

        $recentCourses = new Helper_RecentCourses_Department($courses);
        if ($this->_getParam('cutoff')) {
            $recentCourses->setRecentInterval(new DateInterval($this->_getParam('cutoff')));
        }

        $searchUrl = $this->_helper->pathAsAbsoluteUrl($this->_helper->url('byidxml', 'courses', null, [
            'catalog' => $this->_getParam('catalog'),
            'id' => $this->_getParam('id'),
            'cuttoff' => $this->_getParam('cutoff'),
        ]));
        $this->outputCourseFeed($recentCourses, 'Courses by Id', $searchUrl);
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
     * @return void
     *
     * @since 6/15/09
     */
    public function instructorxmlAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        if (!$this->_getParam('catalog')) {
            $offeringSearchSession = $this->osidRuntime->getCourseManager()->getCourseOfferingSearchSession();
            $offeringSearchSession->useFederatedCourseCatalogView();
            $courseLookupSession = $this->osidRuntime->getCourseManager()->getCourseLookupSession();
            $courseLookupSession->useFederatedCourseCatalogView();

            // Allow term current/past to be limited to a certain catalog while courses are fetched from many
            if ($this->_getParam('term_catalog')) {
                $catalogId = $this->osidIdMap->fromString($this->_getParam('term_catalog'));
                $this->termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
            }
            // fall back to terms from any catalog.
            else {
                $this->termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
                $this->termLookupSession->useFederatedCourseCatalogView();
            }
        } else {
            try {
                $catalogId = $this->osidIdMap->fromString($this->_getParam('catalog'));
                $offeringSearchSession = $this->osidRuntime->getCourseManager()->getCourseOfferingSearchSessionForCatalog($catalogId);
                $courseLookupSession = $this->osidRuntime->getCourseManager()->getCourseLookupSessionForCatalog($catalogId);

                $this->termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
            } catch (osid_InvalidArgumentException $e) {
                throw new osid_InvalidArgumentException('The catalog id specified was not of the correct format.');
            } catch (osid_NotFoundException $e) {
                throw new osid_NotFoundException('The catalog id specified was not found.');
                exit;
            }
        }

        $instructor = trim($this->_getParam('instructor'));

        if (!$instructor || !strlen($instructor)) {
            // Make sure that this error response is cacheable.
            $this->setCacheControlHeaders();
            $this->getResponse()->sendHeaders();

            throw new InvalidArgumentException('An instructor must be specified.');
        }

        $instructorId = $this->osidIdMap->fromString('resource.person.'.$instructor);
        $searchUrl = $this->_helper->pathAsAbsoluteUrl($this->_helper->url('view', 'resources', null, ['catalog' => $this->_getParam('catalog'), 'resource' => 'resouce.person.'.$instructor]));

        $resourceLookup = $this->osidRuntime->getCourseManager()->getResourceManager()->getResourceLookupSession();
        try {
            $instructorResource = $resourceLookup->getResource($instructorId);
        } catch (osid_NotFoundException $e) {
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

        $recentCourses = new Helper_RecentCourses_Instructor($courseOfferings, $courseLookupSession);
        if ($this->_getParam('cutoff')) {
            $recentCourses->setRecentInterval(new DateInterval($this->_getParam('cutoff')));
        }
        $this->outputCourseFeed($recentCourses, 'Courses taught by '.$instructorResource->getDisplayName(), $searchUrl);
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

    public function DateTime_getTimestamp($dt)
    {
        $dtz_original = $dt->getTimezone();
        $dtz_utc = new DateTimeZone('UTC');
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
