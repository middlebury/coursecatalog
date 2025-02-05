<?php

namespace App\Archive\Export;

use App\Archive\Export\Event\ExportProgressEvent;
use App\Archive\Export\Exception\RequirementNotXmlException;
use App\Archive\Export\Exception\RequirementRequestFailedException;
use App\Archive\ExportConfiguration\ExportConfigurationStorage;
use App\Archive\ExportJob\ExportJob;
use App\Service\Osid\IdMap;
use App\Service\Osid\Runtime;
use App\Service\Osid\TopicHelper;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Twig\Environment as TwigEnvironment;

/**
 * Generates an Archive of the catalog.
 */
class ArchiveHtmlGenerator
{
    private $alternateType;
    private $alternateInTermsType;
    private $identifiersType;

    public function __construct(
        private ExportConfigurationStorage $exportConfigurationStorage,
        private EventDispatcherInterface $eventDispatcher,
        protected IdMap $osidIdMap,
        private Runtime $osidRuntime,
        private TopicHelper $osidTopicHelper,
        private HttpClientInterface $httpClient,
        private TwigEnvironment $twig,
        private UrlGeneratorInterface $urlGenerator,
    ) {
        $this->alternateType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:alternates');
        $this->alternateInTermsType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:alternates-in-terms');
        $this->identifiersType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:banner_identifiers');
    }

    /**
     * Generate an archive of the catalog.
     */
    public function generateHtmlForJob(ExportJob $job)
    {
        $config = $this->exportConfigurationStorage->getConfiguration($job->getConfigurationId());

        if (is_null($job->getRevisionId())) {
            $revision = $config->getLatestRevision();
        } else {
            $revision = $config->getRevision($job->getRevisionId());
        }
        $catalogId = $config->getCatalogId();

        $context = [];
        $context['job'] = $job;
        $context['catalogId'] = $catalogId;
        $context['termIds'] = $job->getTermIds();
        $context['courseSearchSession'] = $this->osidRuntime->getCourseManager()->getCourseSearchSessionForCatalog($catalogId);
        $context['offeringSearchSession'] = $this->osidRuntime->getCourseManager()->getCourseOfferingSearchSessionForCatalog($catalogId);
        $context['termLookupSession'] = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
        $context['startTerm'] = $context['termIds'][0];
        $context['endTerm'] = end($context['termIds']);

        $this->eventDispatcher->dispatch(new ExportProgressEvent($job, getmypid(), 'Starting export.'));

        $sections = $this->sectionsFromConfig($revision->getContent());
        $data = [];

        $data['title'] = $data['page_title'] = $this->getTitle($context);
        $data['sections'] = $this->populateSectionData($sections, $context);
        $data['breadcrumbs'] = $this->getBreadcrumbs($job);

        return $this->twig->render('archive/generate.html.twig', $data);
    }

    /**
     * Answer a title for this export.
     */
    protected function getTitle(array $context): string
    {
        $title = 'Course Catalog - ';
        $title .= $context['courseSearchSession']->getCourseCatalog()->getDisplayName();
        $termNames = [];
        foreach ($context['termIds'] as $termId) {
            $termNames[] = $context['termLookupSession']->getTerm($termId)->getDisplayName();
        }
        if (count($termNames)) {
            $title .= ' - '.implode(', ', $termNames);
        }

        return $title;
    }

    /**
     * Answer a breadcrumbs array for a archive file.
     */
    protected function getBreadcrumbs(ExportJob $job)
    {
        $breadcrumbs = [
            [
                'label' => 'Course Catalog',
                'uri' => $this->urlGenerator->generate('home'),
            ],
            [
                'label' => 'Archives',
                'uri' => $this->urlGenerator->generate('view_archive'),
            ],
        ];
        $path = '';
        foreach (explode('/', $job->getExportPath()) as $dir) {
            if (empty($path)) {
                $path = $dir;
            } else {
                $path = $path.'/'.$dir;
            }
            $breadcrumbs[] = [
                'label' => $dir,
                'uri' => $this->urlGenerator->generate('view_archive', ['path' => $path]),
            ];
        }
        $filename = str_replace('/', '-', $job->getExportPath()).'_snapshot-'.date('Y-m-d').'.html';
        $breadcrumbs[] = [
            'label' => $filename,
            'uri' => $this->urlGenerator->generate('view_archive', ['path' => $path.'/'.$filename]),
        ];

        return $breadcrumbs;
    }

    /**
     * Process a configuration array and extract values for easier processing.
     *
     * @param array $configSections
     *                              The raw configuration sections as entered by the user
     *
     * @return array
     *               The configuration sections processed to
     */
    protected function sectionsFromConfig(array $configSections)
    {
        $sections = [];
        foreach ($configSections as $group) {
            foreach ($group as $entry) {
                if (in_array(gettype($entry), ['object', 'array'])) {
                    $section = [];
                    foreach ($entry as $sectionKey => $sectionValue) {
                        if ('type' === $sectionKey) {
                            $section['type'] = $sectionValue;
                        } else {
                            switch ($section['type']) {
                                case 'h1':
                                case 'h2':
                                    $vals = explode(';', $sectionValue);
                                    if (count($vals) > 1) {
                                        $section['text'] = $vals[0];
                                        $section['toc_text'] = $vals[1];
                                    } else {
                                        $section['text'] = $sectionValue;
                                    }
                                    break;
                                case 'toc':
                                    $section['toc_text'] = $sectionValue;
                                    break;
                                case 'page_content':
                                    $section['url'] = $sectionValue;
                                    break;
                                case 'custom_text':
                                    // TODO - Unify naming of this type with export config UI.
                                    $section['type'] = 'html';
                                    $section['text'] = $sectionValue;
                                    $section['text'] = str_replace("\n", '<br>', $section['text']);
                                    break;
                                case 'course_list':
                                    $section['type'] = 'courses';
                                    // Check if course filters are included.
                                    if (strpos($sectionValue, ',')) {
                                        $filters = substr($sectionValue, strpos($sectionValue, ',') + 1);
                                        $filters = explode(',', $filters);
                                        $adjustedFilters = '';
                                        foreach ($filters as $filter) {
                                            $adjustedFilters .= $filter.'|';
                                        }
                                        // strip trailing |
                                        $adjustedFilters = substr($adjustedFilters, 0, -1);
                                        $sectionValue = substr($sectionValue, 0, strpos($sectionValue, ','));
                                        $section['number_filter'] = '/('.$adjustedFilters.')/';
                                    } else {
                                        $section['number_filter'] = null;
                                    }
                                    $section['id'] = $this->osidIdMap->fromString($sectionValue);
                                    break;
                                default:
                                    throw new \InvalidArgumentException('Section type is invalid: '.$section['type']);
                                    break;
                            }
                        }
                    }
                    $sections[] = $section;
                }
            }
        }

        return $sections;
    }

    protected function populateSectionData(array $sections, array $context)
    {
        $totalSections = count($sections);
        $currentSection = 1;
        foreach ($sections as $key => &$section) {
            $text = '';
            if (!empty($section['text'])) {
                $text = $section['text'];
            } elseif (!empty($section['url'])) {
                $text = $section['url'];
            } elseif (!empty($section['id'])) {
                $text = $this->osidIdMap->toString($section['id']);
            }
            $this->eventDispatcher->dispatch(new ExportProgressEvent(
                $context['job'],
                getmypid(),
                'Printing section '.$currentSection.' of '.$totalSections.' ('.$section['type'].' '.$text.' )',
                $currentSection,
                $totalSections,
            ));
            switch ($section['type']) {
                case 'h1':
                case 'toc':
                case 'h2':
                    break;
                case 'text':
                    break;
                case 'html':
                    $section['text'] = \banner_course_Course::convertDescription($section['text']);
                    break;
                case 'page_content':
                    $section['content'] = $this->getRequirements($section['url']);
                    break;
                case 'courses':
                    $section['courses'] = $this->getCourses($section['id'], $context, $section['number_filter']);
                    break;
                default:
                    throw new \Exception('Unknown section type '.$section['type']);
            }
            $this->eventDispatcher->dispatch(new ExportProgressEvent(
                $context['job'],
                getmypid(),
                'Printed section '.$currentSection.' of '.$totalSections.' ('.$section['type'].' '.$text.' )',
                $currentSection,
                $totalSections,
            ));
            ++$currentSection;
        }

        return $sections;
    }

    /**
     * Answer requirements text.
     *
     * @return string
     */
    protected function getRequirements($url)
    {
        // D9 URL, don't bother with RSS attempt.
        if (preg_match('#^https://www\.middlebury\.edu/(college|institute)/#', $url)) {
            try {
                return $this->getRequirementsFromD9Html($url);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
        // D7 URL (might redirect to D9)
        else {
            try {
                try {
                    return $this->getRequirementsFromD7Rss($url);
                } catch (RequirementNotXmlException $e) {
                    return $this->getRequirementsFromD9Html($url);
                }
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
    }

    /**
     * Answer requirements text.
     *
     * @return string
     */
    protected function getRequirementsFromD7Rss($url)
    {
        $feedUrl = $url.'/feed';
        $response = $this->httpClient->request('GET', $feedUrl);
        if (200 != $response->getStatusCode()) {
            throw new RequirementRequestFailedException('Received a non-success status for requirements RSS feed ('.$response->getStatusCode().') at '.$feedUrl, $response->getStatusCode());
        }
        if (!preg_match('#^(text|application)/(xml|rss\+xml)($|;)#', $response->getHeaders()['content-type'][0])) {
            throw new RequirementNotXmlException('Received a non-xml Content-Type for requirements RSS ('.$response->getHeaders()['content-type'][0].') at  '.$feedUrl);
        }
        $feedDoc = new \DOMDocument();
        $feedDoc->loadXml($response->getContent());
        $xpath = new \DOMXPath($feedDoc);
        $descriptions = $xpath->query('/rss/channel/item/description');
        ob_start();
        foreach ($descriptions as $description) {
            $body = $description->nodeValue;
            // Parse the HTML
            $html = new \DOMDocument();
            // Force the HTML snippet to be interpreted as UTF-8 as that is what Drupal
            // is returning. Without this prefix, the snippet will be assumed to be
            // ISO-8859-1. See: https://stackoverflow.com/a/8218649/15872
            $html->loadHTML('<?xml encoding="utf-8" ?>'.$body);
            $htmlXPath = new \DOMXPath($html);
            // Only print out the inner-HTML of the body fields, excluding taxonomy
            // terms and any other fields printed. Note that this is dependent
            // on the Drupal markup and will need to be updated if that changes.
            $bodies = $htmlXPath->query('//div[contains(@class, "field-name-body")]/div/div');
            if ($bodies->length) {
                foreach ($bodies as $domBody) {
                    foreach ($domBody->childNodes as $child) {
                        echo $html->saveHTML($child);
                    }
                }
            }
            // If we don't have any bodies or the markup changes, just use the full text.
            else {
                echo $html->saveHTML();
            }
        }

        return ob_get_clean();
    }

    /**
     * Answer requirements text.
     *
     * @return string
     */
    protected function getRequirementsFromD9Html($url)
    {
        $response = $this->httpClient->request('GET', $url);
        if (200 != $response->getStatusCode()) {
            throw new RequirementRequestFailedException('Received a non-success status for requirements page ('.$response->getStatusCode().') at  '.$url, $response->getStatusCode());
        }
        if (!preg_match('#^text/html($|;)#', $response->getHeaders()['content-type'][0])) {
            throw new RequirementNotXmlException('Received a non-HTML Content-Type for requirements page ('.$response->getHeaders()['content-type'][0].') at  '.$url);
        }
        $feedDoc = new \DOMDocument();
        $feedDoc->encoding = 'utf-8';
        libxml_use_internal_errors(true);
        $feedDoc->loadHTML($response->getContent());
        libxml_clear_errors();
        $xpath = new \DOMXPath($feedDoc);
        ob_start();
        // Only print out the inner-HTML of the body fields, excluding taxonomy
        // terms and any other fields printed. Note that this is dependent
        // on the Drupal markup and will need to be updated if that changes.
        $bodies = $xpath->query('//div[contains(@class, "paragraphs")]/div');
        if ($bodies->length) {
            foreach ($bodies as $domBody) {
                foreach ($domBody->childNodes as $child) {
                    echo $feedDoc->saveHTML($child);
                }
            }
        }

        return ob_get_clean();
    }

    /**
     * Print out the courses for a topic.
     *
     * @param optional string $number_filter A regular expression to filter out courses on
     *
     * @return void
     *
     * @since 4/26/10
     */
    protected function getCourses(\osid_id_Id $topicId, array $context, $number_filter = null)
    {
        $topic_courses = [];
        $offeringQuery = $context['offeringSearchSession']->getCourseOfferingQuery();
        $offeringQuery->matchTopicId($topicId, true);
        foreach ($context['termIds'] as $termId) {
            $offeringQuery->matchTermId($termId, true);
        }
        $offerings = $context['offeringSearchSession']->getCourseOfferingsByQuery($offeringQuery);

        // Limit Courses to those offerings in the terms
        $query = $context['courseSearchSession']->getCourseQuery();
        if ($offerings->hasNext()) {
            while ($offerings->hasNext()) {
                $query->matchCourseOfferingId($offerings->getNextCourseOffering()->getId(), true);
            }
        } else {
            return [];
        }
        $search = $context['courseSearchSession']->getCourseSearch();
        $order = $context['courseSearchSession']->getCourseSearchOrder();
        $order->orderByNumber();
        $order->ascend();
        $search->orderCourseResults($order);
        $courses = $context['courseSearchSession']->getCoursesBySearch($query, $search)->getCourses();

        $i = 0;
        while ($courses->hasNext()) {
            $course = $courses->getNextCourse();
            ++$i;

            // Filter out courses by number if needed.
            if (!empty($number_filter) && preg_match($number_filter, $course->getNumber())) {
                continue;
            }

            $courseIdString = $this->osidIdMap->toString($course->getId());
            $this->printedCourseIds[] = $courseIdString;

            $topic_courses[] = $this->getCourseData($course, $context);

            // 			if ($i > 10)
            // 				break;
        }

        return $topic_courses;
    }

    /**
     * Print out a single course.
     *
     * @return void
     *
     * @since 4/28/10
     */
    protected function getCourseData(\osid_course_Course $course, array $context)
    {
        $data = new \stdClass();
        $data->id = $this->osidIdMap->toString($course->getId());
        $data->anchor = str_replace('/', '_', $data->id);
        $data->sections = [];
        $data->display_name = $course->getDisplayName();
        $data->description = $course->getDescription();

        $termsType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:terms');
        $termStrings = [];
        if ($course->hasRecordType($termsType)) {
            $termsRecord = $course->getCourseRecord($termsType);
            try {
                $terms = $termsRecord->getTerms();
                while ($terms->hasNext()) {
                    $term = $terms->getNextTerm();
                    // See if the term is in one of our chosen terms
                    foreach ($context['termIds'] as $selectedTermId) {
                        if ($selectedTermId->isEqual($term->getId())) {
                            $termStrings[$this->osidIdMap->toString($term->getId())] = $term->getDisplayName();
                        }
                    }
                }
            } catch (\osid_OperationFailedException $e) {
            }
        }
        $data->term_strings = $termStrings;

        /*********************************************************
         * Section descriptions
         *********************************************************/
        // Look for different Section Descriptions
        $offeringQuery = $context['offeringSearchSession']->getCourseOfferingQuery();
        $offeringQuery->matchCourseId($course->getId(), true);
        $offeringQuery->matchGenusType(new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:offering.LCT'), true);
        $offeringQuery->matchGenusType(new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:offering.SEM'), true);
        $offeringQuery->matchGenusType(new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:offering.IND'), true);
        foreach ($context['termIds'] as $termId) {
            $offeringQuery->matchTermId($termId, true);
        }
        $order = $context['offeringSearchSession']->getCourseOfferingSearchOrder();
        $order->orderByTerm();
        $order->ascend();
        $search = $context['offeringSearchSession']->getCourseOfferingSearch();
        $search->orderCourseOfferingResults($order);
        $offerings = $context['offeringSearchSession']->getCourseOfferingsBySearch($offeringQuery, $search);

        // each offering (section) may have the same or different title and description from other sections
        // of the course. Group the sections by title/description and term so that
        // any differences are properly represented while condensing as much as possible.
        $sectionData = [];
        $courseDescriptionHash = sha1($course->getDescription());
        $allCourseInstructors = [];
        $allSectionDescriptions = [];
        $allSectionRequirementTopics = [];

        $instructorsType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors');
        $identifiersType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:banner_identifiers');
        $namesType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:person_names');
        $requirementType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.requirement');
        $enrollmentNumbersType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:enrollment_numbers');
        while ($offerings->hasNext()) {
            $offering = $offerings->getNextCourseOffering();
            $term = $offering->getTerm();
            $termIdString = $this->osidIdMap->toString($term->getId());
            if (!isset($sectionData[$termIdString])) {
                $sectionData[$termIdString] = [
                    'label' => $term->getDisplayName(),
                    'sections' => [],
                    'req_seats' => [],
                    'total_seats' => 0,
                ];
            }
            if (!isset($allCourseInstructors[$termIdString])) {
                $allCourseInstructors[$termIdString] = [
                    'label' => $term->getDisplayName(),
                    'instructors' => [],
                ];
            }
            if ($offering->getDescription() && $offering->getDescription() != $course->getDescription()) {
                $sectionDescriptionHash = sha1($offering->getDescription());
                $sectionDescription = $offering->getDescription();
            } else {
                $sectionDescriptionHash = $courseDescriptionHash;
                $sectionDescription = $course->getDescription();
            }
            $allSectionDescriptions[$sectionDescriptionHash] = $sectionDescription;
            if (!isset($sectionData[$termIdString]['sections'][$sectionDescriptionHash])) {
                $sectionData[$termIdString]['sections'][$sectionDescriptionHash] = [
                    'description' => $sectionDescription,
                    'instructors' => [],
                    'section_numbers' => [],
                    'requirements' => [],
                    'total_seats' => 0,
                ];
            }
            if ($offering->hasRecordType($identifiersType)) {
                $identifiersRecord = $offering->getCourseOfferingRecord($identifiersType);
                $sectionData[$termIdString]['sections'][$sectionDescriptionHash]['section_numbers'][] = $identifiersRecord->getSequenceNumber();
            }
            // Add the number of seats.
            if ($offering->hasRecordType($enrollmentNumbersType)) {
                $enrollmentNumbersRecord = $offering->getCourseOfferingRecord($enrollmentNumbersType);
                $sectionData[$termIdString]['total_seats'] += $enrollmentNumbersRecord->getMaxEnrollment();
                $sectionData[$termIdString]['sections'][$sectionDescriptionHash]['total_seats'] += $enrollmentNumbersRecord->getMaxEnrollment();

                // If the offering has no enrollment and isn't the primary
                // of a cross-listed pair, use the data from the primary
                // section.
                if (0 == $enrollmentNumbersRecord->getMaxEnrollment() && $offering->hasRecordType($this->alternateType)) {
                    $offeringAlternateRecord = $offering->getCourseOfferingRecord($this->alternateType);
                    if ($offeringAlternateRecord->hasAlternates() && !$offeringAlternateRecord->isPrimary()) {
                        $primaryAlternate = $this->_getPrimaryAlternate($offering);
                        if ($primaryAlternate && $primaryAlternate->hasRecordType($enrollmentNumbersType)) {
                            $primaryEnrollmentNumbersRecord = $primaryAlternate->getCourseOfferingRecord($enrollmentNumbersType);

                            $sectionData[$termIdString]['total_seats'] += $primaryEnrollmentNumbersRecord->getMaxEnrollment();
                            $sectionData[$termIdString]['sections'][$sectionDescriptionHash]['total_seats'] += $primaryEnrollmentNumbersRecord->getMaxEnrollment();
                        }
                    }
                }
            }
            // Build an array of requirements for each offering description in case we need to print them separately.
            $topics = $offering->getTopics();
            while ($topics->hasNext()) {
                $topic = $topics->getNextTopic();
                $topicId = $topic->getId();
                $topicIdString = $this->osidIdMap->toString($topic->getId());
                if ($requirementType->isEqual($topic->getGenusType())) {
                    $allSectionRequirementTopics[] = $topic;
                    if (!isset($sectionData[$termIdString]['sections'][$sectionDescriptionHash]['requirements'][$topicIdString])) {
                        $sectionData[$termIdString]['sections'][$sectionDescriptionHash]['requirements'][$topicIdString] = [
                            'label' => $topic->getDisplayName(),
                            'total_seats' => 0,
                            'term_seats' => [
                                $termIdString => [
                                    'term_label' => $term->getDisplayName(),
                                    'seats' => 0,
                                ],
                            ],
                        ];
                    }
                    // Associate the number of seats for each requirement.
                    if ($offering->hasRecordType($enrollmentNumbersType)) {
                        $enrollmentNumbersRecord = $offering->getCourseOfferingRecord($enrollmentNumbersType);
                        if (!isset($sectionData[$termIdString]['req_seats'][$topicIdString])) {
                            $sectionData[$termIdString]['req_seats'][$topicIdString] = 0;
                        }
                        $sectionData[$termIdString]['req_seats'][$topicIdString] += $enrollmentNumbersRecord->getMaxEnrollment();
                        $sectionData[$termIdString]['sections'][$sectionDescriptionHash]['requirements'][$topicIdString]['total_seats'] += $enrollmentNumbersRecord->getMaxEnrollment();
                        $sectionData[$termIdString]['sections'][$sectionDescriptionHash]['requirements'][$topicIdString]['term_seats'][$termIdString]['seats'] += $enrollmentNumbersRecord->getMaxEnrollment();

                        // If the offering has no enrollment and isn't the primary
                        // of a cross-listed pair, use the data from the primary
                        // section.
                        if (0 == $enrollmentNumbersRecord->getMaxEnrollment() && $offering->hasRecordType($this->alternateType)) {
                            $offeringAlternateRecord = $offering->getCourseOfferingRecord($this->alternateType);
                            if ($offeringAlternateRecord->hasAlternates() && !$offeringAlternateRecord->isPrimary()) {
                                $primaryAlternate = $this->_getPrimaryAlternate($offering);
                                $primaryAlternateHasTopic = false;
                                if ($primaryAlternate) {
                                    $primaryAlternateTopicIds = $primaryAlternate->getTopicIds();
                                    while ($primaryAlternateTopicIds->hasNext()) {
                                        if ($topicId->isEqual($primaryAlternateTopicIds->getNextId())) {
                                            $primaryAlternateHasTopic = true;
                                            break;
                                        }
                                    }
                                }
                                // Only add the enrollment of the primary cross-listed section
                                // if it has the topic at hand.
                                if ($primaryAlternateHasTopic && $primaryAlternate->hasRecordType($enrollmentNumbersType)) {
                                    $primaryEnrollmentNumbersRecord = $primaryAlternate->getCourseOfferingRecord($enrollmentNumbersType);

                                    $sectionData[$termIdString]['req_seats'][$topicIdString] += $primaryEnrollmentNumbersRecord->getMaxEnrollment();
                                    $sectionData[$termIdString]['sections'][$sectionDescriptionHash]['requirements'][$topicIdString]['total_seats'] += $primaryEnrollmentNumbersRecord->getMaxEnrollment();
                                    $sectionData[$termIdString]['sections'][$sectionDescriptionHash]['requirements'][$topicIdString]['term_seats'][$termIdString]['seats'] += $primaryEnrollmentNumbersRecord->getMaxEnrollment();
                                }
                            }
                        }
                    }
                }
            }
            if ($offering->hasRecordType($instructorsType)) {
                $instructorsRecord = $offering->getCourseOfferingRecord($instructorsType);
                $instructors = $instructorsRecord->getInstructors();
                while ($instructors->hasNext()) {
                    $instructor = $instructors->getNextResource();
                    $instructorIdString = $this->osidIdMap->toString($instructor->getId());
                    $sectionData[$termIdString]['sections'][$sectionDescriptionHash]['instructors'][$instructorIdString] = $instructor->getDisplayName();
                    $allCourseInstructors[$termIdString]['instructors'][$instructorIdString] = $sectionData[$termIdString]['sections'][$sectionDescriptionHash]['instructors'][$instructorIdString];
                    if ($instructor->hasRecordType($namesType)) {
                        $nameRecord = $instructor->getResourceRecord($namesType);
                        $sectionData[$termIdString]['sections'][$sectionDescriptionHash]['instructors'][$instructorIdString] = substr($nameRecord->getGivenName(), 0, 1).'. '.$nameRecord->getSurname();
                        $allCourseInstructors[$termIdString]['instructors'][$instructorIdString] = $sectionData[$termIdString]['sections'][$sectionDescriptionHash]['instructors'][$instructorIdString];
                    }
                }
            }
        }
        $data->instructors = $this->getInstructorText($allCourseInstructors);
        // Don't show an instructor list for "0500" "Independent Study" courses.
        if (preg_match('/0500$/', $course->getNumber())) {
            $data->instructors = '';
        }

        // Requirements-fullfilled data structure:
        // - Course-level reqs apply to all sections unless some, but not all specify the req.
        // - Sections may have additional reqs.
        //
        //	[
        //		total_seats => INT,		# The total number of seats in the course
        //
        //		req_seats => INT,		# The number of seats that fullfill this req.
        //								# By definition this will equal total_seats
        //								# for course-level reqs.
        //
        //		term_seats => [			# The number of seats for this req, broken out by term.
        //
        //			term_label => STR,	# Label for the term.
        //
        //			total_seats => INT,	# The total number of seats in this term.
        //
        //			req_seats => INT,	# The number of seats that fullfill this req.
        //								# By definition this will equal total_seats
        //								# for course-level reqs.
        //		]
        // ]

        // Apply all course-level topics.
        $allTopics = $this->osidTopicHelper->topicListAsArray($course->getTopics());
        $reqs = [];
        $topicType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.requirement');
        $topicTypeString = $this->osidIdMap->typeToString($topicType);
        $topics = $this->osidTopicHelper->filterTopicsByType($allTopics, $topicType);
        foreach ($topics as $topic) {
            $topicIdString = $this->osidIdMap->toString($topic->getId());
            $req = [
                'label' => $topic->getDisplayName(),
                'total_seats' => 0,
                'req_seats' => 0,
                'term_seats' => [],
            ];
            // Add up the total number of seats for the course
            foreach ($sectionData as $termIdString => $term) {
                $req['term_seats'][$termIdString] = [
                    'term_label' => $term['label'],
                    'total_seats' => $term['total_seats'],
                    'req_seats' => $term['total_seats'],
                ];
                $req['total_seats'] += $term['total_seats'];
                if (isset($term['req_seats'][$topicIdString])) {
                    $req['req_seats'] += $term['req_seats'][$topicIdString];
                }
            }
            $reqs[$topicIdString] = $req;
        }

        // Add requirements that are only present on some offerings.
        foreach ($allSectionRequirementTopics as $topic) {
            $topicIdString = $this->osidIdMap->toString($topic->getId());
            // Overwrite the course-level values if we have this requirement specified
            // per-section.
            $req = [
                'label' => $topic->getDisplayName(),
                'total_seats' => 0,
                'req_seats' => 0,
                'term_seats' => [],
            ];
            foreach ($sectionData as $termIdString => $term) {
                $req['term_seats'][$termIdString] = [
                    'term_label' => $term['label'],
                    'total_seats' => $term['total_seats'],
                    'req_seats' => 0,
                ];
                $req['total_seats'] += $term['total_seats'];
                if (isset($term['req_seats'][$topicIdString])) {
                    $req['term_seats'][$termIdString]['req_seats'] += $term['req_seats'][$topicIdString];
                    $req['req_seats'] += $term['req_seats'][$topicIdString];
                }
            }
            $reqs[$topicIdString] = $req;
        }

        ksort($reqs);
        $data->requirements = $reqs;

        $sectionDescriptionsText = '';
        // Replace the description with the one from the section[s] if there is only one section description and it is
        // different from the course.
        if (1 == count($allSectionDescriptions) && key($allSectionDescriptions) != $courseDescriptionHash) {
            $data->description = current($allSectionDescriptions);
        }
        // If there are multiple section descriptions, print them separately
        elseif (count($allSectionDescriptions) > 1) {
            foreach ($sectionData as $termId => $termSectionData) {
                $term_data = new \stdClass();
                $term_data->idString = $termId;
                $term_data->label = $termSectionData['label'];
                $term_data->req_seats = $termSectionData['req_seats'];
                $term_data->total_seats = $termSectionData['total_seats'];
                $data->terms[] = $term_data;
                foreach ($termSectionData['sections'] as $hash => $section) {
                    $section_data = new \stdClass();
                    $section_data->description = $section['description'];
                    $section_data->requirements = $section['requirements'];
                    if (count($termSectionData['sections']) > 1) {
                        $section_data->section_numbers = $section['section_numbers'];
                    } else {
                        $section_data->section_numbers = [];
                    }
                    if (count($section['instructors'])) {
                        $section_data->instructors = '('.implode(', ', $section['instructors']).')';
                    } else {
                        $section_data->instructors = '';
                    }
                    // Don't show an instructor list for "INTD 0500" courses other than section-C.
                    if (preg_match('/^INTD\s*0500$/', $course->getNumber()) && $section['section_numbers'] != ['C']) {
                        $section_data->instructors = '';
                    }
                    $term_data->sections[] = $section_data;
                }
            }
        }

        // Look for a longer title that exceeds the limits of the Banner title field
        // injected into the description as a bold first line.
        if (preg_match('#^<strong>([^\n\r]+)</strong>(?:\s*<br />(.*)|\s*)$#sm', $data->description, $matches)) {
            // Decode entities in the title to ensure they don't get double-encoded.
            $data->title = html_entity_decode($matches[1]);
            if (isset($matches[2])) {
                $data->description = trim($matches[2]);
            } else {
                $data->description = '';
            }
        } else {
            $data->title = $course->getTitle();
        }

        /*********************************************************
         * Crosslists
         *********************************************************/
        $data->alternates = [];
        try {
            if ($course->hasRecordType($this->alternateInTermsType)) {
                $record = $course->getCourseRecord($this->alternateInTermsType);
                if ($record->hasAlternatesInTerms($context['startTerm'], $context['endTerm'])) {
                    $alternates = $record->getAlternatesInTerms($context['startTerm'], $context['endTerm']);
                    while ($alternates->hasNext()) {
                        $alternate = $alternates->getNextCourse();

                        $altInSelectedTerms = false;
                        if ($alternate->hasRecordType($termsType)) {
                            $termsRecord = $alternate->getCourseRecord($termsType);
                            try {
                                $terms = $termsRecord->getTerms();
                                while ($terms->hasNext() && !$altInSelectedTerms) {
                                    $term = $terms->getNextTerm();
                                    // See if the term is in one of our chosen terms
                                    foreach ($context['termIds'] as $selectedTermId) {
                                        if ($selectedTermId->isEqual($term->getId())) {
                                            $altInSelectedTerms = true;
                                            break;
                                        }
                                    }
                                }
                            } catch (\osid_OperationFailedException $e) {
                            }
                        }
                        if ($altInSelectedTerms) {
                            $alt_data = new \stdClass();
                            $alt_data->display_name = $alternate->getDisplayName();
                            $alt_data->id = $this->osidIdMap->toString($alternate->getId());
                            $alt_data->anchor = str_replace('/', '_', $alt_data->id);
                            if ($alternate->hasRecordType($this->alternateType)) {
                                $alt_record = $alternate->getCourseRecord($this->alternateType);
                                $alt_data->is_primary = $alt_record->isPrimary();
                            }
                            $data->alternates[] = $alt_data;
                        }
                    }
                }
            }
            // Sort the alternates to prevent content thrashing just due to order.
            usort($data->alternates, function ($a, $b) {
                return strcmp($a->id, $b->id);
            });
        } catch (\osid_NotFoundException $e) {
        }

        return $data;
    }

    /**
     * Answer an instructor listing string.
     *
     * @param string $termIdString
     *
     * @return string
     */
    protected function getInstructorText(array $sectionInstructors, $termIdString = null)
    {
        if (empty($sectionInstructors)) {
            return '';
        }
        foreach ($sectionInstructors as $termId => &$termInfo) {
            $ids = array_keys($termInfo['instructors']);
            sort($ids);
            $termInfo['hash'] = implode(':', $ids);
            $termInfo['instructorString'] = implode(', ', $termInfo['instructors']);
        }

        // Use just the instructors of the term passed.
        if ($termIdString) {
            if (empty($sectionInstructors[$termIdString]['instructorString'])) {
                return '';
            } else {
                return '('.$sectionInstructors[$termIdString]['instructorString'].')';
            }
        }

        // For a course with just a single term, use that term's instructors
        if (1 === count($sectionInstructors)) {
            reset($sectionInstructors);
            $info = current($sectionInstructors);
            if (empty($info['instructorString'])) {
                return '';
            } else {
                return '('.$info['instructorString'].')';
            }
        }

        // For courses with multiple terms, first find out if the instructor list is always the same.
        reset($sectionInstructors);
        $firstTerm = current($sectionInstructors);
        $firstHash = $firstTerm['hash'];
        $instructorListConstant = true;
        foreach ($sectionInstructors as $termId => $info) {
            if ($info['hash'] != $firstHash) {
                $instructorListConstant = false;
            }
        }
        // If we have the same instructor list each term, just use the first string.
        if ($instructorListConstant) {
            if (empty($firstTerm['instructorString'])) {
                return '';
            } else {
                return '('.$firstTerm['instructorString'].')';
            }
        }
        // If we have a different instructor list each term, identify them.
        else {
            $termStrings = [];
            foreach ($sectionInstructors as $termId => $info) {
                if (!empty($info['instructorString'])) {
                    $termStrings[] = $info['label'].': '.$info['instructorString'];
                }
            }

            return '('.implode('; ', $termStrings).')';
        }
    }

    public function _textToLink($text)
    {
        return preg_replace('/[^a-z0-9.:]+/i', '-', $text);
    }

    public function _getPrimaryAlternate(\osid_course_CourseOffering $offering)
    {
        $primary = null;
        if (!$offering->hasRecordType($this->alternateType)) {
            return null;
        }
        $baseSequenceNumber = null;
        if ($offering->hasRecordType($this->identifiersType)) {
            $baseIdentifiersRecord = $offering->getCourseOfferingRecord($this->identifiersType);
            $baseSequenceNumber = $baseIdentifiersRecord->getSequenceNumber();
        }
        $baseAlternateRecord = $offering->getCourseOfferingRecord($this->alternateType);
        $alternates = $baseAlternateRecord->getAlternates();
        while ($alternates->hasNext()) {
            $alternateOffering = $alternates->getNextCourseOffering();
            $identifiersRecord = null;
            if ($alternateOffering->hasRecordType($this->identifiersType)) {
                $identifiersRecord = $alternateOffering->getCourseOfferingRecord($this->identifiersType);
            }
            if ($alternateOffering->hasRecordType($this->alternateType)) {
                $offeringAlternateRecord = $alternateOffering->getCourseOfferingRecord($this->alternateType);
                if ($offeringAlternateRecord->isPrimary()) {
                    $primary = $alternateOffering;

                    // Also check the sequence number on the off chance that we
                    // have multiple primaries designated. If not we'll default to any primary found.
                    if ($identifiersRecord && $baseSequenceNumber == $identifiersRecord->getSequenceNumber()) {
                        return $alternateOffering;
                    }
                }
            }
        }

        return $primary;
    }
}
