<?php

namespace App\Controller;

use App\Service\Bookmarks;
use App\Service\Osid\DataLoader;
use App\Service\Osid\IdMap;
use App\Service\Osid\Runtime;
use App\Service\Osid\TermHelper;
use App\Service\Schedules as SchedulesService;
use App\Twig\SchedulesExtension;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * A controller for working with user-schedules.
 *
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Schedules extends AbstractController
{
    public function __construct(
        private SchedulesService $schedules,
        private Runtime $osidRuntime,
        private IdMap $osidIdMap,
        private TermHelper $osidTermHelper,
        private DataLoader $osidDataLoader,
        private SchedulesExtension $schedulesExtension,
        private Bookmarks $bookmarks,
    ) {
    }

    #[Route('/schedules/list/{catalogId}/{termId}', name: 'schedules')]
    public function indexAction(?\osid_id_Id $catalogId = null, ?\osid_id_Id $termId = null)
    {
        $this->initializeCatalogAndTerm($catalogId, $termId);
        $data = [
            'selectedCatalogId' => $this->catalogId,
            'selectedTermId' => $this->termId,
        ];
        $data['emailEnabled'] = $this->emailEnabled();

        // Catalogs
        $catalogLookupSession = $this->osidRuntime->getCourseManager()->getCourseCatalogLookupSession();
        $catalogs = $catalogLookupSession->getCourseCatalogs();
        $data['catalogs'] = [];
        while ($catalogs->hasNext()) {
            $data['catalogs'][] = $catalogs->getNextCourseCatalog();
        }

        // Load all terms for our selection control
        $this->termLookupSession->useFederatedCourseCatalogView();
        $terms = $this->termLookupSession->getTerms();
        $termCatalogSession = $this->osidRuntime->getCourseManager()->getTermCatalogSession();
        $data['terms'] = [];
        while ($terms->hasNext()) {
            $data['terms'][] = $terms->getNextTerm();
        }

        // Load the bookmarks for the selected catalog/terms
        if ($this->termId) {
            $data['bookmarked_courses'] = $this->bookmarks->getBookmarkedCoursesInCatalogForTerm($this->catalogId, $this->termId);
        } else {
            $data['bookmarked_courses'] = $this->bookmarks->getAllBookmarkedCourses();
        }

        // Load the Schedules for the selected catalog/terms
        if ($this->termId) {
            $data['schedules'] = $this->schedules->getSchedulesByTerm($this->termId);
        } else {
            $data['schedules'] = $this->schedules->getSchedules();
        }

        $data['leftText'] = "
        <p class='notice'><strong>Important:</strong> This tool is for planning purposes only. It does <strong>not</strong> register you for classes.</p>

        <div class='help'><strong>Basic Usage:</strong>
            <ol>
                <li>Search for courses in the catalog and save interesting ones.</li>
                <li>Create one or more schedules in the Planner.</li>
                <li>Add courses to schedules.</li>
                <li>Print or email your schedules.</li>
            </ol>

            <p><strong>For more help see:</strong> <a href='http://go.middlebury.edu/catalog-help' target='_blank'>go/catalog-help</a></p>
            <p><strong>Issues or Feedback?</strong> <a href='http://go.middlebury.edu/webbugs/Catalog/Planner' target='_blank'>go/webbugs/Catalog/Planner</a></p>
        </div>
        ";

        return $this->render('schedules/index.html.twig', $data);
    }

    /**
     * Create a new schedule.
     *
     * @return void
     *
     * @since 8/2/10
     */
    #[Route('/schedules/create/{catalogId}', name: 'create_schedule', methods: ['POST'])]
    public function createAction(Request $request, ?\osid_id_Id $catalogId = null)
    {
        if (!$this->isCsrfTokenValid('create-schedule', $request->get('csrf_key'))) {
            throw new AccessDeniedException('Invalid CSRF key.');
        }

        $this->schedules->createSchedule($this->osidIdMap->fromString($request->get('term')));

        return $this->redirectToRoute('schedules', [
            'catalogId' => $catalogId,
            'termId' => $request->get('term'),
        ]);
    }

    /**
     * Update a schedule.
     *
     * @return void
     *
     * @since 8/2/10
     */
    #[Route('/schedules/update/{scheduleId}/{catalogId}/{termId}', name: 'update_schedule', methods: ['POST'])]
    public function updateAction(Request $request, string $scheduleId, ?\osid_id_Id $catalogId = null, ?\osid_id_Id $termId = null)
    {
        if (!$this->isCsrfTokenValid('update-schedule', $request->get('csrf_key'))) {
            throw new AccessDeniedException('Invalid CSRF key.');
        }

        $schedule = $this->schedules->getSchedule($scheduleId);
        $schedule->setName($request->get('name'));

        return $this->redirectToRoute('schedules', [
            'catalogId' => $catalogId,
            'termId' => $termId,
        ]);
    }

    /**
     * Delete a schedule.
     *
     * @return void
     *
     * @since 8/2/10
     */
    #[Route('/schedules/delete/{scheduleId}/{catalogId}/{termId}', name: 'delete_schedule', methods: ['POST'])]
    public function deleteAction(Request $request, string $scheduleId, ?\osid_id_Id $catalogId = null, ?\osid_id_Id $termId = null)
    {
        if (!$this->isCsrfTokenValid('delete-schedule', $request->get('csrf_key'))) {
            throw new AccessDeniedException('Invalid CSRF key.');
        }

        $this->schedules->deleteSchedule($scheduleId);

        return $this->redirectToRoute('schedules', [
            'catalogId' => $catalogId,
            'termId' => $termId,
        ]);
    }

    /**
     * Add sections to a schedule.
     *
     * @return void
     *
     * @since 8/2/10
     */
    #[Route('/schedules/add/{catalogId}/{termId}', name: 'add_to_schedule', methods: ['POST'])]
    public function addAction(Request $request, ?\osid_id_Id $catalogId = null, ?\osid_id_Id $termId = null)
    {
        if (!$this->isCsrfTokenValid('add-to-schedule', $request->get('csrf_key'))) {
            throw new AccessDeniedException('Invalid CSRF key.');
        }

        $schedule = $this->schedules->getSchedule($request->get('scheduleId'));

        // Get our ids from the POST
        $offeringIds = [];
        foreach ($request->request->all() as $key => $val) {
            if (preg_match('/^section_group_[0-9]+$/', $key)) {
                $offeringIds[] = $this->osidIdMap->fromString($val);
            }
        }
        if (!count($offeringIds)) {
            throw new \InvalidArgumentException('No Sections selected.');
        }

        /*********************************************************
         * Validate the set of offerings chosen
         *********************************************************/
        $lookupSession = $this->osidRuntime->getCourseManager()->getCourseOfferingLookupSession();
        $lookupSession->useFederatedCourseCatalogView();
        $linkType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:link');

        $offering = $lookupSession->getCourseOffering($offeringIds[0]);
        $course = $offering->getCourse();
        $termId = $offering->getTermId();

        $selectedLinkSet = $this->osidIdMap->fromString($request->get('section_set'));
        $linkTypes = $course->getLinkTypeIdsForTermAndSet($termId, $selectedLinkSet);
        $requiredLinkTypes = [];
        while ($linkTypes->hasNext()) {
            $requiredLinkTypes[] = [
                'id' => $linkTypes->getNextId(),
                'found' => false,
            ];
        }

        foreach ($offeringIds as $id) {
            $offering = $lookupSession->getCourseOffering($id);

            // Verify that the offering is part of the selected link-set.
            $linkRecord = $offering->getCourseOfferingRecord($linkType);
            if (!$selectedLinkSet->isEqual($linkRecord->getLinkSetId())) {
                throw new \Exception('The offering chosen is not part of the link-set selected.');
            }

            // Check that we are adding a single section from each link-type.
            $linkTypeId = $linkRecord->getLinkTypeId();
            $checked = false;
            foreach ($requiredLinkTypes as $key => $info) {
                if ($info['id']->isEqual($linkTypeId)) {
                    $checked = true;
                    if ($info['found']) {
                        throw new \Exception('A second section from the same link-group is selected.');
                    } else {
                        $requiredLinkTypes[$key]['found'] = true;
                    }
                }
            }
            if (!$checked) {
                throw new \Exception("The link-group id of the offering '".$linkTypeId->getIdentifier()."' wasn't in the required list.");
            }

            // Also check that the sections are from the same course and term.
            if (!$offering->getTermId()->isEqual($termId)) {
                throw new \Exception('Trying to add offerings from multiple terms.');
            }
            if (!$offering->getCourseId()->isEqual($course->getId())) {
                throw new \Exception('Trying to add offerings from multiple courses.');
            }
        }
        // Check that we are adding a section for each link-group.
        foreach ($requiredLinkTypes as $info) {
            if (!$info['found']) {
                throw new \Exception('No offering was added for the link-group '.$info['id']->getIdentifier().' when adding sections for '.$course->getDisplayName().'. POST: '.print_r($_POST, true));
            }
        }

        /*********************************************************
         * Remove any offerings for the course already added.
         *********************************************************/
        foreach ($schedule->getOfferings() as $oldOffering) {
            if ($oldOffering->getCourseId()->isEqual($course->getId())) {
                $schedule->remove($oldOffering->getId());
            }
        }

        /*********************************************************
         * Add the offerings to the Schedule
         *********************************************************/
        foreach ($offeringIds as $offeringId) {
            try {
                $schedule->add($offeringId);
            } catch (\Exception $e) {
                if (23000 != $e->getCode()) {
                    throw $e;
                }
            }
        }

        return $this->redirectToRoute('schedules', [
            'catalogId' => $catalogId,
            'termId' => $termId,
        ]);
    }

    /**
     * Remove an offering from a schedule.
     *
     * @return void
     *
     * @since 8/4/10
     */
    #[Route('/schedules/remove/{scheduleId}/{catalogId}/{termId}', name: 'remove_from_schedule', methods: ['POST'])]
    public function removeAction(Request $request, string $scheduleId, ?\osid_id_Id $catalogId = null, ?\osid_id_Id $termId = null)
    {
        if (!$this->isCsrfTokenValid('remove-from-schedule', $request->get('csrf_key'))) {
            throw new AccessDeniedException('Invalid CSRF key.');
        }

        $schedule = $this->schedules->getSchedule($scheduleId);

        $lookupSession = $this->osidRuntime->getCourseManager()->getCourseOfferingLookupSession();
        $lookupSession->useFederatedCourseCatalogView();

        $offering = $lookupSession->getCourseOffering($this->osidIdMap->fromString($request->get('offering')));
        $courseId = $offering->getCourseId();

        // Remove the selected offering.
        $schedule->remove($offering->getId());

        // Remove all other offerings for the course.
        foreach ($schedule->getOfferings() as $offering) {
            if ($offering->getCourse()->getId()->isEqual($courseId)) {
                $schedule->remove($offering->getId());
            }
        }

        return $this->redirectToRoute('schedules', [
            'catalogId' => $catalogId,
            'termId' => $termId,
        ]);
    }

    /**
     * Answer a JSON list of sections information for a course.
     *
     * @return void
     *
     * @since 8/3/10
     */
    #[Route('/schedules/sectionsforcourse/{courseId}/{termId}', name: 'schedule_sections_for_course')]
    public function sectionsforcourseAction(Request $request, \osid_id_Id $courseId, \osid_id_Id $termId)
    {
        $offeringSearchSession = $this->osidRuntime->getCourseManager()->getCourseOfferingSearchSession();
        $offeringSearchSession->useFederatedCourseCatalogView();

        $query = $offeringSearchSession->getCourseOfferingQuery();
        $query->matchCourseId($courseId, true);
        $query->matchTermId($termId, true);

        $results = $offeringSearchSession->getCourseOfferingsByQuery($query);

        $schedule = $this->schedules->getSchedule($request->get('scheduleId'));

        $sets = [];
        $linkType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:link');
        $instructorType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors');
        while ($results->hasNext()) {
            $offering = $results->getNextCourseOffering();

            $instructors = $offering->getInstructors();
            $instructorString = '';
            while ($instructors->hasNext()) {
                $instructorString .= $instructors->getNextResource()->getDisplayName().', ';
            }
            $instructorString = trim($instructorString, ', ');

            $conflicts = $schedule->conflicts($offering);
            if ($conflicts) {
                $conflictString = '';
                $conflictingNames = [];
                foreach ($schedule->getConflictingEvents($offering) as $event) {
                    $conflictingNames[] = $event['name'];
                }
                $conflictString = 'Conflicts with: '.implode(', ', array_unique($conflictingNames));
            } else {
                $conflictString = '';
            }
            $info = [
                'id' => $this->osidIdMap->toString($offering->getId()),
                'name' => $offering->getDisplayName(),
                'type' => $offering->getGenusType()->getDisplayName(),
                'instructor' => $instructorString,
                'location' => $offering->getLocationInfo(),
                'availability' => $this->osidDataLoader->getAvailabilityLink($offering),
                'schedule' => $this->schedulesExtension->formatScheduleInfo($offering->getScheduleInfo()),
                'conflicts' => $conflicts,
                'conflictString' => $conflictString,
            ];

            // Get the link id and ensure that we have a set and type-group for it.
            $linkRecord = $offering->getCourseOfferingRecord($linkType);
            $linkSetIdString = $this->osidIdMap->toString($linkRecord->getLinkSetId());
            $info['link_set'] = $linkSetIdString;
            if (!isset($sets[$linkSetIdString])) {
                $sets[$linkSetIdString] = [];
            }
            if (!isset($sets[$linkSetIdString]['types'])) {
                $sets[$linkSetIdString]['types'] = [];
            }

            $linkTypeIdString = $this->osidIdMap->toString($linkRecord->getLinkTypeId());
            $info['link_type'] = $linkTypeIdString;
            if (!isset($sets[$linkSetIdString]['types'][$linkTypeIdString])) {
                $sets[$linkSetIdString]['types'][$linkTypeIdString] = [];
            }

            // To start with, enable the first section in each group.
            // Later, we may want to check if the target schedule already has
            // this course added and select the already-added versions so that
            // a second addition will update that course's sections rather than
            // add them again.
            if (!count($sets[$linkSetIdString]['types'][$linkTypeIdString])) {
                $info['selected'] = true;
            }

            if ($schedule->includes($offering->getId())) {
                if (count($sets[$linkSetIdString]['types'][$linkTypeIdString])) {
                    $sets[$linkSetIdString]['types'][$linkTypeIdString][0]['selected'] = false;
                }
                $info['selected'] = true;
                $sets[$linkSetIdString]['selected'] = true;
            }

            // Add the info to the appropriate set.
            $sets[$linkSetIdString]['types'][$linkTypeIdString][] = $info;
        }

        return new JsonResponse($sets);
    }

    /**
     * Answer an array of events for the schedule in JSON format.
     *
     * @return void
     *
     * @since 8/5/10
     */
    #[Route('/schedules/eventsjson/{scheduleId}.json', name: 'schedule_events_json')]
    public function eventsjsonAction(string $scheduleId)
    {
        $schedule = $this->schedules->getSchedule($scheduleId);

        $thisWeek = \Week::current();

        $events = $schedule->getWeeklyEvents();
        foreach ($events as $i => &$event) {
            $event['title'] = $event['name'];

            if ($event['crn']) {
                $event['raw']['crn'] = $event['crn'];
            }

            $day = $thisWeek->asDateAndTime();
            if ($event['dayOfWeek']) {
                $day = $day->plus(\Duration::withDays($event['dayOfWeek']));
            }

            $dateTime = $day->plus(\Duration::withSeconds($event['startTime']));
            $event['start'] = $dateTime->ymdString().' '.$dateTime->hmsString();
            $dateTime = $day->plus(\Duration::withSeconds($event['endTime']));
            $event['end'] = $dateTime->ymdString().' '.$dateTime->hmsString();

            $event['id'] = $i;
        }

        return new JsonResponse($events);
    }

    /**
     * Answer a PNG Image of the schedule.
     *
     * @return void
     *
     * @since 8/5/10
     */
    #[Route('/schedules/png/{scheduleId}.png', name: 'schedule_png')]
    public function pngAction(string $scheduleId)
    {
        $data = $this->getScheduleImageData($scheduleId);
        $image = $this->generateImage($data);
        ob_start();
        imagepng($image);
        imagedestroy($image);
        $response = new Response(ob_get_clean());
        $response->headers->set('Content-Type', 'image/png');

        return $response;
    }

    /**
     * Answer a print-view of the schedule.
     *
     * @return void
     *
     * @since 8/5/10
     */
    #[Route('/schedules/print/{scheduleId}', name: 'print_schedule')]
    public function printAction(string $scheduleId)
    {
        $data = [
            'schedule' => $this->schedules->getSchedule($scheduleId),
        ];

        return $this->render('schedules/print.html.twig', $data);
    }

    /**
     * Email a schedule to one or more addresses.
     *
     * @return void
     */
    #[Route('/schedules/email', name: 'email_schedule', methods: ['POST'])]
    public function emailAction(Request $request, MailerInterface $mailer)
    {
        try {
            if (!$this->emailEnabled()) {
                throw new \Exception('Emailing of schedules is not enabled in configuration.');
            }
            if (!$this->isCsrfTokenValid('send-email', $request->get('csrf_key'))) {
                throw new AccessDeniedException('Invalid CSRF key.');
            }

            $scheduleId = $request->get('scheduleId');
            $data = [];
            $data['schedule'] = $this->schedules->getSchedule($scheduleId);
            $data['messageBody'] = $request->get('message');

            // Generate the Schedule image.
            $imageData = $this->getScheduleImageData($scheduleId);
            $image = $this->generateImage($imageData);
            ob_start();
            imagepng($image);
            imagedestroy($image);
            $imageData = ob_get_clean();

            $userEmail = $this->getUser()->getEmail();

            // Build the email
            $imagePart = new DataPart($imageData, 'schedule_image.png', 'image/png');
            $email = (new TemplatedEmail())
                ->from($this->getFromEmail())
                ->replyTo($userEmail)
                ->cc($userEmail)
                ->subject(preg_replace('/[^\w \'"&-_.,\/*%#$@!()=+:;<>?]/', '', $request->get('subject')))
                ->textTemplate('schedules/email.txt.twig')
                ->htmlTemplate('schedules/email.html.twig')
                ->context($data)
                ->addPart($imagePart) // Add it as an attachment.
                ->addPart($imagePart->asInline()); // Add it inline for HTML.

            // Add additional recipients.
            if (strlen(trim($request->get('to'))) && trim($request->get('to')) != $userEmail) {
                $addresses = explode(',', str_replace(';', ',', $request->get('to')));
                foreach ($addresses as $address) {
                    $address = trim($address);
                    if ($address != $userEmail) {
                        $email->addTo($address);
                    }
                }
            }

            $mailer->send($email);

            $response = new Response('Email sent.');
            $response->headers->set('Content-Type', 'text/plain; charset=utf-8');

            return $response;
        } catch (\Exception $e) {
            $response = new Response($e->message);
            $response->setStatusCode(500);
            $response->headers->set('Content-Type', 'text/plain; charset=utf-8');

            return $response;
        }
    }

    /**
     * Initialize the catalog and term we are working with.
     *
     * Sets the following member properties:
     * 		catalogId				osid_id_Id or NULL
     *		termLookupSession		osid_course_TermLookupSession
     *		termId					osid_id_Id or NULL
     *
     * @return void
     */
    protected function initializeCatalogAndTerm(?\osid_id_Id $catalogId = null, ?\osid_id_Id $termId = null)
    {
        // Select the catalog.
        if ($catalogId) {
            $this->catalogId = $catalogId;
            $this->schedules->setSavedUserCatalogId($catalogId);
        } else {
            // Check for a saved catalog id.
            $this->catalogId = $this->schedules->getSavedUserCatalogId();
        }

        // Load the termLookupSession
        if ($this->catalogId) {
            $this->termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($this->catalogId);
        } else {
            $this->termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
            $this->catalogId = $this->termLookupSession->getCourseCatalogId();
        }

        // Select the term
        if ($termId && 'ANY' == $termId->getIdentifier()) {
            // Don't set a term
            $this->termId = null;
        } elseif (!$termId || 'CURRENT' == $termId->getIdentifier()) {
            $this->termId = $this->osidTermHelper->getNextOrLatestTermId($this->catalogId);
        } else {
            $this->termId = $termId;
        }
    }

    /**
     * Answer true if sending email is enabled.
     *
     * @return bool
     */
    protected function emailEnabled()
    {
        if (!$this->getParameter('app.schedules.email.enabled')) {
            return false;
        }

        // Allow enabling email for only some users
        return in_array('ROLE_CAN_SEND_EMAIL', $this->getUser()->getRoles());
    }

    /**
     * Answer the email address to send mail from.
     *
     * @return string
     */
    protected function getFromEmail()
    {
        $name = $this->getUser()->getName();
        if ($this->getParameter('app.schedules.email.send_mail_as_user')) {
            return $name.' <'.$this->getUser()->getEmail().'>';
        } elseif ($this->getParameter('app.schedules.email.send_mail_as')) {
            return $name.' - Catalog <'.$this->getParameter('app.schedules.email.send_mail_as').'>';
        } else {
            throw new \Exception('schedules.email.send_mail_as_user is false, but schedules.email.send_mail_as is not set.');
        }
    }

    /**
     * Get data needed for rendering the schedule image.
     */
    protected function getScheduleImageData(string $scheduleId): array
    {
        $data = [];
        $schedule = $this->schedules->getSchedule($scheduleId);
        $data['fontFile'] = $this->getParameter('app.schedules.image.font_file');
        $data['schedule'] = $schedule;
        $data['events'] = $schedule->getWeeklyEvents();
        $data['minTime'] = $schedule->getEarliestTime();
        $data['events'] = $schedule->getWeeklyEvents();
        if ($schedule->getLatestTime()) {
            $data['maxTime'] = $schedule->getLatestTime();
        } else {
            $data['minTime'] = 9 * 3600;
            $data['maxTime'] = 17 * 3600;
        }
        $data['width'] = null;
        $data['height'] = 600;

        return $data;
    }

    protected function generateImage(array $data): \GdImage
    {
        /*********************************************************
         * Figure out how many days we are working with.
         *********************************************************/
        $hasSunday = false;
        $hasSaturday = false;
        foreach ($data['events'] as $event) {
            if (0 == $event['dayOfWeek']) {
                $hasSunday = true;
            }
            if (6 == $event['dayOfWeek']) {
                $hasSaturday = true;
            }
        }
        $numDays = 7;
        $startDay = 0;
        $endDay = 6;
        if (!$hasSunday) {
            --$numDays;
            $startDay = 1;
        }
        if (!$hasSaturday) {
            --$numDays;
            $endDay = 5;
        }

        /*********************************************************
         * Set up our image.
         *********************************************************/

        $width = 910;
        if ($data['width']) {
            $width = $data['width'];
        }
        $height = 800;
        if ($data['height']) {
            $height = $data['height'];
        }

        if (empty($data['fontFile'])) {
            throw new \Exception('No font-file configured.');
        }
        if (!file_exists($data['fontFile'])) {
            throw new \Exception('Font-file missing, not found at '.$data['fontFile']);
        }

        $im = imagecreatetruecolor($width, $height);

        // Set colors
        $black = imagecolorallocate($im, 0, 0, 0);
        $white = imagecolorallocate($im, 255, 255, 255);
        $orange = imagecolorallocate($im, 255, 200, 0);
        $yellow = imagecolorallocate($im, 255, 255, 0);
        $tan = imagecolorallocate($im, 255, 255, 190);
        $ltgrey = imagecolorallocate($im, 235, 235, 235);
        $grey = imagecolorallocate($im, 200, 200, 200);
        $dkgrey = imagecolorallocate($im, 140, 140, 140);
        $blue = imagecolorallocate($im, 0, 90, 207);
        $red = imagecolorallocate($im, 255, 50, 50);
        $darkred = imagecolorallocate($im, 0, 0, 0);
        $ltblue = imagecolorallocate($im, 175, 210, 255);

        // Background color
        imagefilledrectangle($im, 0, 0, $width, $height, $white);

        $timeWidth = 97;
        $gridWidth = $width - $timeWidth;
        $dayWidth = floor($gridWidth / $numDays);

        $firstHour = floor($data['minTime'] / 3600);
        $gridStartTime = $firstHour * 3600;
        $hoursToShow = ceil(($data['maxTime'] - $data['minTime']) / 3600);

        $headerHeight = 48;
        $gridHeight = $height - $headerHeight;
        $hourHeight = floor($gridHeight / $hoursToShow);

        // header row & time column
        imagefilledrectangle($im, 1, 1, $width - 2, $headerHeight - 1, $ltgrey);
        imagefilledrectangle($im, 1, 1, $timeWidth - 1, $height - 2, $ltgrey);

        // Hours
        for ($h = 0; $h < $hoursToShow; ++$h) {
            $top = $headerHeight + ($hourHeight * $h);
            $bottom = $top + $hourHeight;
            imagerectangle($im, 0, $top, $width, $bottom, $grey);
        }

        // Days
        for ($d = $startDay; $d < $numDays; ++$d) {
            $left = $timeWidth + ($dayWidth * $d);
            $right = $left + $dayWidth;
            imagerectangle($im, $left, 0, $right, $height, $black);
        }

        // Day Labels
        $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        for ($d = $startDay; $d <= $endDay; ++$d) {
            $label = $days[$d];

            $dayPosition = $d - $startDay;
            $left = $timeWidth + ($dayWidth * $dayPosition);
            $size = 24;
            $bb = imagettfbbox($size, 0, $data['fontFile'], $label);
            $textWidth = $bb[2] - $bb[0];
            imagettftext($im, $size, 0, round($left + (($dayWidth - $textWidth) / 2)), $headerHeight - 5, $black, $data['fontFile'], $label);
        }

        // Hour Labels
        for ($i = 0; $i < $hoursToShow; ++$i) {
            $hour = $firstHour + $i;
            if ($hour < 12) {
                $hourString = $hour.':00 am';
            } elseif (12 == $hour) {
                $hourString = '12:00 pm';
            } else {
                $hourString = ($hour - 12).':00 pm';
            }

            $size = 12;
            $bb = imagettfbbox($size, 0, $data['fontFile'], $hourString);
            $textWidth = $bb[2] - $bb[0];
            $textHeight = $bb[1] - $bb[5];
            $top = $headerHeight + ($hourHeight * $i) + $textHeight + intval(($hourHeight - $textHeight) / 2);
            $left = $timeWidth - $textWidth - 10;
            imagettftext($im, $size, 0, $left, $top, $black, $data['fontFile'], $hourString);
        }

        /*********************************************************
         * Events
         *********************************************************/

        foreach ($data['events'] as $event) {
            if ($event['collisions']) {
                $eventColor = $red;
                $eventBorderColor = $darkred;
            } else {
                $eventColor = $ltblue;
                $eventBorderColor = $blue;
            }
            $dayPosition = $event['dayOfWeek'] - $startDay;
            $left = $timeWidth + ($dayWidth * $dayPosition) + 1;		// 1px margin
            $right = $left + $dayWidth - 2;	// 1px margin

            $top = $this->timePosition($gridStartTime, $hourHeight, $event['startTime']) + $headerHeight;
            $bottom = $this->timePosition($gridStartTime, $hourHeight, $event['endTime']) + $headerHeight;

            imagefilledrectangle($im, $left, $top, $right, $bottom, $eventColor);
            imagerectangle($im, $left, $top, $right, $bottom, $eventBorderColor);

            $start = \Time::withSeconds($event['startTime']);
            $end = \Time::withSeconds($event['endTime']);
            $string = $start->hour12().':'.str_pad($start->minute(), 2, '0', STR_PAD_LEFT).'-'.$end->hour12().':'.str_pad($end->minute(), 2, '0', STR_PAD_LEFT);
            $size = 10;
            $bb = imagettfbbox($size, 0, $data['fontFile'], $string);
            $textWidth = $bb[2] - $bb[0];
            $textHeight = $bb[1] - $bb[5];
            $textTop = $top + $textHeight + 3;
            $textLeft = $left + round(($dayWidth - $textWidth) / 2);
            imagettftext($im, $size, 0, $textLeft, $textTop, $black, $data['fontFile'], $string);

            $size = 10;
            $bb = imagettfbbox($size, 0, $data['fontFile'], $event['name']);
            $textWidth = $bb[2] - $bb[0];
            $textHeight = $bb[1] - $bb[5];
            $textTop = $top + $textHeight + 16;
            $textLeft = $left + round(($dayWidth - $textWidth) / 2);
            imagettftext($im, $size, 0, $textLeft, $textTop, $black, $data['fontFile'], $event['name']);

            $size = 10;
            $bb = imagettfbbox($size, 0, $data['fontFile'], $event['location']);
            $textWidth = $bb[2] - $bb[0];
            $textHeight = $bb[1] - $bb[5];
            $textTop = $top + $textHeight + $textHeight + 22;
            $textLeft = $left + round(($dayWidth - $textWidth) / 2);
            imagettftext($im, $size, 0, $textLeft, $textTop, $black, $data['fontFile'], $event['location']);

            $size = 10;
            $bb = imagettfbbox($size, 0, $data['fontFile'], $event['crn']);
            $textWidth = $bb[2] - $bb[0];
            $textHeight = $bb[1] - $bb[5];
            // $textTop = $top + $textHeight +$textHeight + 34;
            $textTop += 14;
            $textLeft = $left + round(($dayWidth - $textWidth) / 3);
            imagettftext($im, $size, 0, $textLeft, $textTop, $black, $data['fontFile'], 'CRN: '.$event['crn']);
        }

        /*********************************************************
         * Final Outlines.
         *********************************************************/

        // Background Outline
        imagerectangle($im, 0, 0, $width - 1, $height - 1, $black);
        // Header & time outlines
        imagerectangle($im, 0, 0, $width, $headerHeight, $black);
        imagerectangle($im, 0, 0, $timeWidth, $height, $black);

        /*********************************************************
         * Return the image for output by another view.
         *********************************************************/
        return $im;
    }

    protected function timePosition($gridStartTime, $hourHeight, $time)
    {
        $diff = $time - $gridStartTime;
        $hours = $diff / 3600;

        return round($hours * $hourHeight);
    }
}
