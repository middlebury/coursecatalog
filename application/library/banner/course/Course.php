<?php
/**
 * @since 4/14/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 *  <p>A <code> Course </code> represents a canonical learning unit. A <code>
 *  Course </code> is instantiated at a time and place through the creation of
 *  a <code> CourseOffering. </code> </p>.
 *
 * @since 4/14/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class banner_course_Course extends phpkit_AbstractOsidObject implements osid_course_Course, middlebury_course_Course_TermsRecord, middlebury_course_Course_AlternatesRecord, middlebury_course_Course_LinkRecord
{
    /**
     * Translate any markup or encoding in the description into UTF-8 XHTML.
     *
     * @param string $description
     *
     * @return string
     *
     * @since 10/23/09
     *
     * @static
     */
    public static function convertDescription($description)
    {
        // Trim leading/trailing line-breaks
        $description = trim(preg_replace('/<br\/?>/i', "\n", $description));
        // Trim off unclosed <p> tags at the end of the description.
        $description = trim(preg_replace('/<p>$/si', '', $description));

        $tmp = error_reporting();
        error_reporting(\E_WARNING);

        $parser = self::getFsmParser();
        ob_start();
        $parser->Parse($description, 'UNKNOWN');
        error_reporting($tmp);

        $output = ob_get_clean();
        $urlRegex = '{
  \\b
  # Match the leading part (proto://hostname, or just hostname)
  (
	# http://, or https:// leading part
	(https?)://[-\\w]+(\\.\\w[-\\w]*)+
  |
	# or, try to find a hostname with more specific sub-expression
	(?i: [a-z0-9] (?:[-a-z0-9]*[a-z0-9])? \\. )+ # sub domains
	# Now ending .com, etc. For these, require lowercase
	(?-i: com\\b
		| edu\\b
		| biz\\b
		| gov\\b
		| in(?:t|fo)\\b # .int or .info
		| mil\\b
		| net\\b
		| org\\b
		| [a-z][a-z]\\.[a-z][a-z]\\b # two-letter country code
	)
  )

  # Allow an optional port number
  ( : \\d+ )?

  # The rest of the URL is optional, and begins with /
  (
	/
	# The rest are heuristics for what seems to work well
	[^.!,?;"\\\'<>()\[\]\{\}\s\x7F-\\xFF]*
	(
	[.!,?]+ [^.!,?;"\\\'<>()\\[\\]\{\\}\s\\x7F-\\xFF]+
	)*
  )?
}ix
';
        if (preg_match_all($urlRegex, $output, $matches, \PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $start = strpos($output, $match[0]);

                // Case with leading protocol://
                if (!empty($match[2])) {
                    $link = '<a href="'.$match[0].'">'.$match[0].'</a>';
                }
                // just www.example.edu
                else {
                    $link = '<a href="http://'.$match[0].'">'.$match[0].'</a>';
                }

                $output = substr_replace($output, $link, $start, strlen($match[0]));
            }
        }

        // Ensure that all tags are closed and there is not javascript.
        $output = HtmlString::getSafeHtml($output);

        // Remove leading/trailing newlines with paragraphs as these are probably
        // errors in hand-entered HTML and will generate extra white-space.
        // Really, users should just be entering whitespace or HTML, not a mixture
        // of both.
        // Example:
        //		text<p>
        //		other text<p>
        //		third text<p>
        $output = preg_replace('#<p>\s+#i', '<p>', $output);
        $output = preg_replace('#\s+</p>#i', '</p>', $output);

        return nl2br($output);
    }

    /**
     * Answer an FMS Parser configured to convert markdown into XHTML text.
     *
     * @return FSMParser
     *
     * @since 10/23/09
     *
     * @static
     */
    private static function getFsmParser()
    {
        if (!isset(self::$fsmParser)) {
            self::$fsmParser = new FSMParser();

            // ---------Programming the FSM:

            /*********************************************************
             * Normal state
             *********************************************************/

            // Enter from unknown into normal state if the first character is not a slash or bold.
            self::$fsmParser->FSM('/[^\/\*]/s', 'echo $STRING;', 'CDATA', 'UNKNOWN');

            // In normal state, catch all other data
            self::$fsmParser->FSM('/./s', 'echo $STRING;', 'CDATA', 'CDATA');

            /*********************************************************
             * HTLM tag handling.
             *********************************************************/

            // Enter into Italic with <em> or <i> tags.
            self::$fsmParser->FSM(
                '/<em>|<i>/i',
                'preg_match("/<em>|<i>/i", $STRING, $m); echo "<em>".$m[1];',
                'ITALIC');

            // close italic with </em> or </i> tags.
            self::$fsmParser->FSM(
                '/<\/em>|<\/i>/i',
                'preg_match("/<\/em>|<\/i>/i", $STRING, $m); echo "</em>".$m[1];',
                'CDATA',
                'ITALIC');

            // Enter into Bold with <strong> or <b> tags.
            self::$fsmParser->FSM(
                '/<strong>|<b>/i',
                'preg_match("/<strong>|<b>/i", $STRING, $m); echo "<strong>".$m[1];',
                'BOLD');

            // close italic with </strong> or </b> tags.
            self::$fsmParser->FSM(
                '/<\/strong>|<\/b>/i',
                'preg_match("/<\/strong>|<\/b>/i", $STRING, $m); echo "</strong>".$m[1];',
                'CDATA',
                'BOLD');

            /*********************************************************
             * Italic
             *********************************************************/

            // Enter into Italic if at the begining of the line.
            self::$fsmParser->FSM(
                '/^\/[^\s]/',
                'preg_match("/^\/([^\s])/", $STRING, $m); echo "<em>".$m[1];',
                'ITALIC',
                'UNKNOWN');

            // In normal state, catch italic start.
            // Be sure not to match </ in case there is HTML in the text.
            self::$fsmParser->FSM(
                '/[^\w.:<\/]\/\w/',
                'preg_match("/(\W)\/(\w)/", $STRING, $m); echo $m[1]."<em>".$m[2];',
                'ITALIC',
                'CDATA');

            // Close out of italic state back to normal
            self::$fsmParser->FSM(
                '/\w\/\W/',
                'preg_match("/(\w)\/(\W)/", $STRING, $m); echo $m[1]."</em>".$m[2];',
                'CDATA',
                'ITALIC');

            // In normal state, catch italic start for whitespace+non-word
            self::$fsmParser->FSM(
                '/\s\/[^\s]/',
                'preg_match("/(\s)\/([^\s])/", $STRING, $m); echo $m[1]."<em>".$m[2];',
                'ITALIC',
                'CDATA');

            // Close out of italic state back to normal for whitespace+non-word
            self::$fsmParser->FSM(
                '/[^\s]\/\s/',
                'preg_match("/([^\s])\/(\s)/", $STRING, $m); echo $m[1]."</em>".$m[2];',
                'CDATA',
                'ITALIC');

            // Close out of italic state back to normal if bold at the very end
            self::$fsmParser->FSM(
                '/\w\/$/',
                'preg_match("/(\w)\/$/", $STRING, $m); echo $m[1]."</em>";',
                'CDATA',
                'ITALIC');

            // Close out of italic state back to normal if there is no closing mark.
            self::$fsmParser->FSM(
                '/.$/',
                'preg_match("/(.)$/", $STRING, $m); echo $m[1]."</em>";',
                'CDATA',
                'ITALIC');

            // In italic state, catch all other data
            self::$fsmParser->FSM('/./s', 'echo $STRING;', 'ITALIC', 'ITALIC');

            /*********************************************************
             * Bold
             *********************************************************/

            // Enter into Bold if at the begining of the line.
            self::$fsmParser->FSM(
                '/^\*[^\s]/',
                'preg_match("/^\*([^\s])/", $STRING, $m); echo "<strong>".$m[1];',
                'BOLD',
                'UNKNOWN');

            // In normal state, catch bold start
            self::$fsmParser->FSM(
                '/[^\w.]\*\w/',
                'preg_match("/(\W)\*(\w)/", $STRING, $m); echo $m[1]."<strong>".$m[2];',
                'BOLD',
                'CDATA');

            // Close out of bold state back to normal
            self::$fsmParser->FSM(
                '/\w\*\W/',
                'preg_match("/(\w)\*(\W)/", $STRING, $m); echo $m[1]."</strong>".$m[2];',
                'CDATA',
                'BOLD');

            // In normal state, catch bold start for whitespace+non-word
            self::$fsmParser->FSM(
                '/\s\*[^\s]/',
                'preg_match("/(\s)\*([^\s])/", $STRING, $m); echo $m[1]."<strong>".$m[2];',
                'BOLD',
                'CDATA');

            // Close out of bold state back to normal for whitespace+non-word
            self::$fsmParser->FSM(
                '/[^\s]\*\s/',
                'preg_match("/([^\s])\*(\s)/", $STRING, $m); echo $m[1]."</strong>".$m[2];',
                'CDATA',
                'BOLD');

            // Close out of bold state back to normal if bold at the very end
            self::$fsmParser->FSM(
                '/\w\*$/',
                'preg_match("/(\w)\*$/", $STRING, $m); echo $m[1]."</strong>";',
                'CDATA',
                'BOLD');

            // Close out of bold state back to normal if bold if there is no closing mark.
            self::$fsmParser->FSM(
                '/.$/',
                'preg_match("/(.)$/", $STRING, $m); echo $m[1]."</strong>";',
                'CDATA',
                'BOLD');

            // In bold state, catch all other data
            self::$fsmParser->FSM('/./s', 'echo $STRING;', 'BOLD', 'BOLD');
        }

        return self::$fsmParser;
    }
    private static $fsmParser;

    private $raw_description;

    /**
     * Constructor.
     *
     * @param string $displayName
     *
     * @return void
     *
     * @since 4/13/09
     */
    public function __construct(osid_id_Id $id, $displayName, $description, $title, $credits, array $topicIds, $hasAlternates, banner_course_AbstractSession $session)
    {
        parent::__construct();
        $this->setId($id);
        $this->setDisplayName($displayName);
        if (null === $description) {
            $this->setDescription('');
        } else {
            $this->setRawDescription($description);
        }
        $this->title = $title;
        $this->credits = (float) $credits;
        $this->topicIds = $topicIds;
        $this->hasAlternates = $hasAlternates;
        $this->session = $session;

        $this->addRecordType(new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:terms'));
        $this->addRecordType(new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:alternates'));
        $this->addRecordType(new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:alternates-in-terms'));
        $this->addRecordType(new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:link'));
    }

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
        return $this->title;
    }

    /**
     * Gets the description associated with this instance of this OSID
     * object.
     *
     * @return the description
     *
     * @compliance mandatory This method must be implemented.
     *
     * @notes   A description is a string used for describing an object in
     *          human terms and may not have significance in the underlying
     *          system. A provider may wish to initialize the description
     *          based on one or more object attributes and/or treat it as an
     *          auxiliary piece of data that can be modified. A provider may
     *          also wish to translate the description into a specific locale
     *          using the Locale service.
     */
    public function getDescription()
    {
        $description = parent::getDescription();
        if (empty($description) && !empty($this->raw_description)) {
            $this->setDescription(self::convertDescription(trim($this->raw_description)));
        }

        return parent::getDescription();
    }

    /**
     * Set the description.
     *
     * @param string $description
     *
     * @return void
     *
     * @since 10/28/08
     */
    protected function setRawDescription($description)
    {
        $this->raw_description = $description;
        $this->setDescription('');
    }

    /**
     *  Gets the course number which is a label generally used to indedx the
     *  course in a catalog, such as T101 or 16.004.
     *
     * @return string the course number
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getNumber()
    {
        return $this->getDisplayName();
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
        return $this->credits;
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
        return '';
    }

    /**
     *  WARNING: This method was not in the OSID trunk as of 2009-04-27. A
     *  ticket requesting the addition of this method is available at:
     *  http://oki.assembla.com/spaces/osid-dev/tickets/18-osid-course---No-way-to-map-Topics-to-Courses-or-CourseOfferings-
     *  Gets a list of the <code> Id </code> s of the <code> Topic </code> s
     *  this course is associated with.
     *
     * @return object osid_id_IdList the <code> Topic </code> <code> Id
     *                </code> s
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTopicIds()
    {
        if (!isset($this->allTopicIds)) {
            $this->allTopicIds = array_merge($this->topicIds, $this->session->getRequirementTopicIdsForCourse($this->getId()), $this->session->getLevelTopicIdsForCourse($this->getId()));
        }

        return new phpkit_id_ArrayIdList($this->allTopicIds);
    }

    /**
     *  WARNING: This method was not in the OSID trunk as of 2009-04-27. A
     *  ticket requesting the addition of this method is available at:
     *  http://oki.assembla.com/spaces/osid-dev/tickets/18-osid-course---No-way-to-map-Topics-to-Courses-or-CourseOfferings-
     *  Gets the <code> Topic </code> s this course is associated with.
     *
     * @return object osid_course_TopicList the topics
     *
     * @throws osid_OperationFailedException unable to complete request
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTopics()
    {
        return $this->session->getTopicLookupSession()->getTopicsByIds($this->getTopicIds());
    }

    /**
     *  Gets the record corresponding to the given <code> Course </code>
     *  record <code> Type. </code> This method must be used to retrieve an
     *  object implementing the requested record interface along with all of
     *  its ancestor interfaces. The <code> courseRecordType </code> may be
     *  the <code> Type </code> returned in <code> getRecordTypes() </code> or
     *  any of its parents in a <code> Type </code> hierarchy where <code>
     *  hasRecordType(courseRecordType) </code> is <code> true </code> .
     *
     *  @param object osid_type_Type $courseRecordType the type of course
     *          record to retrieve
     *
     * @return object osid_course_CourseRecord the course record
     *
     * @throws osid_NullArgumentException <code> courseRecordType </code> is
     *                                           <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure occurred
     * @throws osid_UnsupportedException <code>
     *                                           hasRecordType(courseRecordType) </code> is <code> false
     *                                           </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseRecord(osid_type_Type $courseRecordType)
    {
        if ($this->hasRecordType($courseRecordType));

        return $this;
        throw new osid_UnsupportedException('Course record type is not supported.');
    }

    /*********************************************************
     * Methods from osid_course_CourseRecord
     *********************************************************/

    /**
     *  Gets the <code> Course </code> from which this record originated.
     *
     * @return object osid_course_Course the course
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourse()
    {
        return $this;
    }

    /*********************************************************
     * Methods from osid_OsidRecord
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

    /*********************************************************
     * Methods from middlebury_course_Course_TermsRecord
     *********************************************************/

    /**
     * Gets the Ids of the Terms in which a <code>Course Offering</code> has been
     * taught for a <code> Course. </code>.
     *
     * @return object osid_id_IdList the list of term ids
     *
     *  @compliance mandatory This method must be implemented.
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException authorization failure
     */
    public function getTermIds()
    {
        return $this->getTerms();
    }

    /**
     * Gets the <code> Terms </code> in which a <code>Course Offering</code> has
     * been taught for a <code> Course. </code>.
     *
     * @return object osid_course_TermList the list of terms
     *
     *  @compliance mandatory This method must be implemented.
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException authorization failure
     */
    public function getTerms()
    {
        try {
            return new banner_course_Term_ForCourseList($this->session->getManager()->getDB(), $this->session, $this->getId());
        } catch (osid_NotFoundException $e) {
            throw new osid_OperationFailedException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /*********************************************************
     * Methods from middlebury_course_Course_AlternatesRecord
     *********************************************************/

    /**
     * Tests if this course has any alternate courses.
     *
     * @return boolean <code> true </code> if this course has any
     *                        alternates, <code> false </code> otherwise
     *
     * @compliance mandatory This method must be implemented.
     */
    public function hasAlternates()
    {
        return (int) $this->hasAlternates > 0;
    }

    /**
     *  Gets the Ids of any alternate courses.
     *
     * @return object osid_id_IdList the list of alternate ids
     *
     *  @compliance mandatory This method must be implemented.
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException authorization failure
     */
    public function getAlternateIds()
    {
        if (!$this->hasAlternates()) {
            return new phpkit_EmptyList('osid_id_IdList');
        }

        return $this->session->getCourseLookupSession()->getAlternateIdsForCourse($this->getId());
    }

    /**
     *  Gets the alternate <code> Courses </code>.
     *
     * @return object osid_course_CourseList The list of alternates
     *
     *  @compliance mandatory This method must be implemented.
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException authorization failure
     */
    public function getAlternates()
    {
        $lookupSession = $this->session->getCourseLookupSession();
        $lookupSession->useComparativeView();

        return $lookupSession->getCoursesByIds($this->getAlternateIds());
    }

    /**
     * Answer <code> true </code> if this course is the primary version in a group of
     * alternates.
     *
     * @return bool
     *
     *  @compliance mandatory This method must be implemented.
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException authorization failure
     */
    public function isPrimary()
    {
        // Get the most recent offereings for this course and its equivalents
        $session = $this->session->getCourseOfferingSearchSession();
        $query = $session->getCourseOfferingQuery();

        $query->matchCourseId($this->getId(), true);
        $alternateIds = $this->getAlternateIds();
        while ($alternateIds->hasNext()) {
            $query->matchCourseId($alternateIds->getNextId(), true);
        }

        $search = $session->getCourseOfferingSearch();
        $order = $session->getCourseOfferingSearchOrder();
        $order->orderByTerm();
        $order->descend();
        $search->orderCourseOfferingResults($order);

        $offerings = $session->getCourseOfferingsBySearch($query, $search);
        while ($offerings->hasNext()) {
            $offering = $offerings->getNextCourseOffering();
            if ($offering->isPrimary()) {
                if ($offering->getCourseId()->isEqual($this->getId())) {
                    return true;
                } else {
                    return false;
                }
            }
        }

        return false;
    }

    /*********************************************************
     * Methods from middlebury_course_Course_AlternatesInTermsRecord
     *********************************************************/

    /**
     * Tests if this course has any alternate courses, effective between the terms specified (inclusive).
     *
     * @return boolean <code> true </code> if this course has any
     *                        alternates, <code> false </code> otherwise
     *
     * @compliance mandatory This method must be implemented.
     */
    public function hasAlternatesInTerms(osid_id_Id $startTerm, osid_id_Id $endTerm)
    {
        return (int) $this->hasAlternates > 0;
    }

    /**
     *  Gets the Ids of any alternate courses effective between the terms specified (inclusive).
     *
     * @return object osid_id_IdList the list of alternate ids
     *
     *  @compliance mandatory This method must be implemented.
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException authorization failure
     */
    public function getAlternateIdsInTerms(osid_id_Id $startTerm, osid_id_Id $endTerm)
    {
        if (!$this->hasAlternatesInTerms($startTerm, $endTerm)) {
            return new phpkit_EmptyList('osid_id_IdList');
        }

        return $this->session->getCourseLookupSession()->getAlternateIdsForCourseInTerms($this->getId(), $startTerm, $endTerm);
    }

    /**
     *  Gets the alternate <code> Courses </code>.
     *
     * @return object osid_course_CourseList The list of alternates
     *
     *  @compliance mandatory This method must be implemented.
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException authorization failure
     */
    public function getAlternatesInTerms(osid_id_Id $startTerm, osid_id_Id $endTerm)
    {
        $lookupSession = $this->session->getCourseLookupSession();
        $lookupSession->useComparativeView();

        return $lookupSession->getCoursesByIds($this->getAlternateIdsInTerms($startTerm, $endTerm));
    }

    /**
     * Answer <code> true </code> if this course is the primary version in a group of
     * alternates.
     *
     * @return bool
     *
     *  @compliance mandatory This method must be implemented.
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException authorization failure
     */
    public function isPrimaryInTerms(osid_id_Id $startTerm, osid_id_Id $endTerm)
    {
        // Get the most recent offereings for this course and its equivalents
        $session = $this->session->getCourseOfferingSearchSession();
        $query = $session->getCourseOfferingQuery();

        $query->matchCourseId($this->getId(), true);
        $alternateIds = $this->getAlternateIdsInTerms($startTerm, $endTerm);
        while ($alternateIds->hasNext()) {
            $query->matchCourseId($alternateIds->getNextId(), true);
        }

        $search = $session->getCourseOfferingSearch();
        $order = $session->getCourseOfferingSearchOrder();
        $order->orderByTerm();
        $order->descend();
        $search->orderCourseOfferingResults($order);

        $offerings = $session->getCourseOfferingsBySearch($query, $search);
        while ($offerings->hasNext()) {
            $offering = $offerings->getNextCourseOffering();
            if ($offering->isPrimary()) {
                if ($offering->getCourseId()->isEqual($this->getId())) {
                    return true;
                } else {
                    return false;
                }
            }
        }

        return false;
    }

    /*********************************************************
     * 	Methods from middlebury_course_Course_LinkRecord
     *********************************************************/
    /**
     * Answer the link-set ids for the offerings of this course in the term specified.
     *
     * The offerings of a course in a term will be grouped into one or more link sets
     * (set 1, set 2, set 3, etc).
     * Each offering also has a link type (such as lecture, discussion, lab, etc).
     *
     * When registering for a Course that has multiple Offerings (such as lecture + lab or
     * lectures at different times), students must choose a link set and then one offering
     * of each type within that set.
     *
     * @return osid_id_IdList
     *
     * @since 8/3/10
     */
    public function getLinkSetIdsForTerm(osid_id_Id $termId)
    {
        $linkSetIds = [];
        foreach ($this->getLinkIdStrings($termId) as $linkIdString) {
            if (null === $linkIdString) {
                $linkSetIds[] = 'NULL';
            } else {
                // Link ids are of the form L1, L2, D1, D2.
                // The set id is the second charactor.
                $linkSetIds[] = substr($linkIdString, 1, 1);
            }
        }
        $linkSetIds = array_unique($linkSetIds);
        foreach ($linkSetIds as $key => $val) {
            $linkSetIds[$key] = $this->session->getOsidIdFromString($val, 'link_set.');
        }

        return new phpkit_id_ArrayIdList($linkSetIds);
    }

    /**
     * Answer the link-type ids for the offerings of this course in the term specified.
     *
     * The offerings of a course in a term will be grouped into one or more link sets
     * (set 1, set 2, set 3, etc).
     * Each offering also has a link type (such as lecture, discussion, lab, etc).
     *
     * When registering for a Course that has multiple Offerings (such as lecture + lab or
     * lectures at different times), students must choose a link set and then one offering
     * of each type within that set.
     *
     * @return osid_id_IdList
     *
     * @since 8/3/10
     */
    public function getLinkTypeIdsForTermAndSet(osid_id_Id $termId, osid_id_Id $linkSetId)
    {
        $linkTypeIds = [];
        foreach ($this->getLinkIdStrings($termId) as $linkIdString) {
            if (null !== $linkIdString) {
                // Link ids are of the form L1, L2, D1, D2.
                // The set id is the second charactor.
                $setId = substr($linkIdString, 1, 1);
                // The type id is the first charactor.
                $typeId = substr($linkIdString, 0, 1);
                if ($linkSetId->isEqual($this->session->getOsidIdFromString($setId, 'link_set.'))) {
                    $linkTypeIds[] = $typeId;
                }
            }
        }
        $linkTypeIds = array_unique($linkTypeIds);

        // Ensure that we always at least have the null type.
        if (!count($linkTypeIds)) {
            $linkTypeIds[] = 'NULL';
        }

        foreach ($linkTypeIds as $key => $val) {
            $linkTypeIds[$key] = $this->session->getOsidIdFromString($val, 'link_type.');
        }

        return new phpkit_id_ArrayIdList($linkTypeIds);
    }

    /**
     * Answer an array of link-id strings for the term given.
     *
     * @return array of strings
     */
    protected function getLinkIdStrings(osid_id_Id $termId)
    {
        if (!isset(self::$linkStmt)) {
            self::$linkStmt = $this->session->getManager()->getDb()->prepare(
                "SELECT
	SSBSECT_LINK_IDENT
FROM
	ssbsect_scbcrse
	INNER JOIN course_catalog_college ON course_catalog_college.coll_code = SCBCRSE_COLL_CODE
	INNER JOIN course_catalog ON course_catalog_college.catalog_id = course_catalog.catalog_id
WHERE
	SSBSECT_SUBJ_CODE = ?
	AND SSBSECT_CRSE_NUMB = ?
	AND SSBSECT_TERM_CODE = ?
	AND SSBSECT_SSTS_CODE = 'A'
	AND (course_catalog.prnt_ind_to_exclude IS NULL OR SSBSECT_PRNT_IND != course_catalog.prnt_ind_to_exclude)
GROUP BY
	SSBSECT_LINK_IDENT
ORDER BY
	SSBSECT_SEQ_NUMB");
        }

        self::$linkStmt->execute([
            $this->session->getSubjectFromCourseId($this->getId()),
            $this->session->getNumberFromCourseId($this->getId()),
            $this->session->getTermCodeFromTermId($termId),
        ]);
        $linkIds = self::$linkStmt->fetchAll(PDO::FETCH_COLUMN);
        if (!is_array($linkIds)) {
            throw new Exception('$linkIds should be an array. '.$linkIds.' got instead.');
        }

        return array_unique($linkIds);
    }
    private static $linkStmt;
}
