<?php
/**
 * @since 8/23/17
 * @package catalog.controllers
 *
 * @copyright Copyright &copy; 2017, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * A controller for working with courses
 *
 * @since 8/23/17
 * @package catalog.controllers
 *
 * @copyright Copyright &copy; 2017, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class ArchiveController
	extends AbstractCatalogController
{

	/**
	 * Initialize object
	 *
	 * Called from {@link __construct()} as final step of object instantiation.
	 *
	 * @return void
	 */
	public function init() {
		$this->alternateType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:alternates');

		parent::init();
	}

	/**
	 * Print out a list of all courses
	 *
	 * @return void
	 * @access public
	 * @since 4/21/09
	 */
	public function listAction () {
		$request = $this->getRequest();
		var_dump($request->getParam('path'));

	}

	/**
	 * View a catalog details
	 *
	 * @return void
	 * @access public
	 * @since 4/21/09
	 */
	public function viewAction () {
		$config = Zend_Registry::getInstance()->config;
		$request = $this->getRequest();
		if (empty($config->catalog->archive_root)) {
			throw new Exception('Invalid configuration: catalog.archive_root must be defined.');
		}
		$archive_root = $config->catalog->archive_root;
		// Relative paths should be relative to our installation directory.
		if (!preg_match('#^/#', $archive_root)) {
			$archive_root = BASE_PATH .'/'.$archive_root;
		}
		$archive_root = realpath($archive_root);
		if (!$archive_root) {
			throw new Exception('Invalid configuration: catalog.archive_root is invalid.');
		}
		$target = $archive_root.'/'.$request->getParam('path').'/'.$request->getParam('file');
		// Verify that our target file is really in our root and not trying to
		// access other parts of our file-system or remote URLs.
		$target = realpath($target);
		if (!$target) {
			throw new InvalidArgumentException('The target path is invalid.');
		}
		if (strpos($target, $archive_root) !== 0) {
			throw new InvalidArgumentException('The target path must be located within catalog.archive_root.');
		}

		$doc = new DOMDocument();
		libxml_use_internal_errors(true); // Don't print errors related to HTML5 enties.
		$doc->loadHTML(file_get_contents($target));
		libxml_use_internal_errors(false);
		$xpath = new DOMXPath($doc);
		$this->view->headTitle($xpath->query('/html/head/title')->item(0)->nodeValue);
		foreach ($xpath->query('/html/body')->item(0)->childNodes as $node) {
			$this->view->body .= $doc->saveHTML($node);
		}
	}


	/**
	 * Answer a list of all recent courses
	 *
	 * @return void
	 * @access public
	 * @since 6/15/09
	 */
	public function generateAction () {
		if (!$this->_getParam('catalog')) {
			header('HTTP/1.1 400 Bad Request');
			print "A catalog must be specified.";
			exit;
		}

		$config = Zend_Registry::getInstance()->config;
		if ($config->catalog->print_password && !$this->_getParam('password')) {
			header('HTTP/1.1 400 Bad Request');
			print "A password must be specified.";
			exit;
		}
		if ($config->catalog->print_password && $this->_getParam('password') != $config->catalog->print_password) {
			header('HTTP/1.1 400 Bad Request');
			print "Invalid password specified.";
			exit;
		}

		if ($config->catalog->print_max_exec_time)
			ini_set('max_execution_time', $config->catalog->print_max_exec_time);

		try {
			$catalogId = $this->_helper->osidId->fromString($this->_getParam('catalog'));
			$this->courseSearchSession = $this->_helper->osid->getCourseManager()->getCourseSearchSessionForCatalog($catalogId);
			$this->offeringSearchSession = $this->_helper->osid->getCourseManager()->getCourseOfferingSearchSessionForCatalog($catalogId);

			$this->termLookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
		} catch (osid_InvalidArgumentException $e) {
			header('HTTP/1.1 400 Bad Request');
			print "The catalog id specified was not of the correct format.";
			exit;
		} catch (osid_NotFoundException $e) {
			header('HTTP/1.1 404 Not Found');
			print "The catalog id specified was not found.";
			exit;
		}

		try {
			$this->selectedTerms = array();
			// Get all offerings in the terms
			foreach ($this->_getParam('term') as $termIdString) {
				$termId = $this->_helper->osidId->fromString($termIdString);
				$this->selectedTerms[] = $termId;
			}
		} catch (osid_InvalidArgumentException $e) {
			header('HTTP/1.1 400 Bad Request');
			print "The term id specified was not of the correct format.";
			exit;
		}

		$sections = array();
		foreach ($config->catalog->print_sections as $i => $sectionConf) {
			$section = array('type' => $sectionConf->type);
			if ($sectionConf->type == 'h1') {
				if (strlen(trim($sectionConf->text)))
					$section['text'] = $sectionConf->text;
				else
					throw new InvalidArgumentException("catalog.print_sections.$i.text is missing.");
			} else if ($sectionConf->type == 'h2') {
				if (strlen(trim($sectionConf->text)))
					$section['text'] = $sectionConf->text;
				else
					throw new InvalidArgumentException("catalog.print_sections.$i.text is missing.");
			} else if ($sectionConf->type == 'page_content') {
				if (strlen(trim($sectionConf->url)))
					$section['url'] = $sectionConf->url;
				else
					throw new InvalidArgumentException("catalog.print_sections.$i.url is missing.");
			} else if ($sectionConf->type == 'courses') {
				if (strlen(trim($sectionConf->id)))
					$section['id'] = $this->_helper->osidId->fromString($sectionConf->id);
				else
					throw new InvalidArgumentException("catalog.print_sections.$i.id is missing.");
			} else {
				throw new InvalidArgumentException("catalog.print_sections.$i.type is '".$sectionConf->type."'. Must be one of h1, h2, page_content, or courses.");
			}

			$sections[] = $section;
		}

		$title = 'Course Catalog - ';
		$title .= $this->courseSearchSession->getCourseCatalog()->getDisplayName();
		$termNames = array();
		foreach ($this->selectedTerms as $termId) {
			$termNames[] = $this->termLookupSession->getTerm($termId)->getDisplayName();
		}
		if (count($termNames)) {
			$title .= ' - '.implode(', ', $termNames);
		}
		$this->view->title = $title;
		$this->view->headTitle($title);
		$this->view->sections = $sections;
		foreach ($this->view->sections as $key => &$section) {
			switch ($section['type']) {
				case 'h1':
					break;
				case 'h2':
					break;
				case 'text':
					break;
				case 'page_content':
					$section['content'] = $this->getRequirements($section['url']);
					break;
				case 'courses':
					$section['courses'] = $this->getCourses($section['id']);
					break;
				default:
					throw new Exception("Unknown section type ".$section['type']);
			}
		}

		$this->_helper->layout()->setLayout('minimal');
	}

	/**
	 * Answer requirements text
	 *
	 * @param $url
	 * @return string
	 * @access protected
	 * @since 4/26/10
	 */
	protected function getRequirements ($url) {
		$feedUrl = $url.'/feed';
		$feedDoc = new DOMDocument;
		$feedDoc->load($feedUrl);
		$xpath = new DOMXPath($feedDoc);
		$descriptions = $xpath->query('/rss/channel/item/description');
		ob_start();
		foreach ($descriptions as $description) {
			$body = $description->nodeValue;
			$body = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $body); // Strip out none-printable characters.
			print $body;
		}
		return ob_get_clean();
	}

	/**
	 * Print out the courses for a topic
	 *
	 * @param osid_id_Id $topicId
	 * @return void
	 * @access protected
	 * @since 4/26/10
	 */
	protected function getCourses (osid_id_Id $topicId) {
		$topic_courses = array();
		$offeringQuery = $this->offeringSearchSession->getCourseOfferingQuery();
		$offeringQuery->matchTopicId($topicId, true);
		foreach ($this->selectedTerms as $termId) {
			$offeringQuery->matchTermId($termId, true);
		}
		$offerings = $this->offeringSearchSession->getCourseOfferingsByQuery($offeringQuery);

		// Limit Courses to those offerings in the terms
		$query = $this->courseSearchSession->getCourseQuery();
		if ($offerings->hasNext()) {
			while ($offerings->hasNext()) {
				$query->matchCourseOfferingId($offerings->getNextCourseOffering()->getId(), true);
			}
		} else {
			return;
		}
		$search = $this->courseSearchSession->getCourseSearch();
		$order = $this->courseSearchSession->getCourseSearchOrder();
		$order->orderByDisplayName();
		$order->ascend();
		$search->orderCourseResults($order);
		$courses = $this->courseSearchSession->getCoursesBySearch($query, $search);

		$i = 0;
		while ($courses->hasNext()) {
			$course = $courses->getNextCourse();
			$i++;

			$courseIdString = $this->_helper->osidId->toString($course->getId());
			$this->printedCourseIds[] = $courseIdString;

			$topic_courses[] = $this->getCourseData($course);

// 			if ($i > 10)
// 				break;
		}
		return $topic_courses;
	}

	/**
	 * Print out a single course
	 *
	 * @param osid_course_Course $course
	 * @return void
	 * @access protected
	 * @since 4/28/10
	 */
	protected function getCourseData (osid_course_Course $course) {
		$data = new stdClass();
		$data->id = $this->_helper->osidId->toString($course->getId());
		$data->anchor = str_replace('/','_', $data->id);
		$data->sections = array();
		$data->display_name = $course->getDisplayName();
		$data->description = $course->getDescription();
		if (preg_match('#^<strong>([^\n\r]+)</strong>(?:\s*<br />(.*)|\s*)$#sm', $data->description, $matches)) {
			$data->title = $matches[1];
			if (isset($matches[2]))
				$data->description = trim($matches[2]);
			else
				$data->description = '';
		} else {
			$data->title = htmlspecialchars($course->getTitle());
		}

		$termsType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:record:terms");
		$termStrings = array();
		if ($course->hasRecordType($termsType)) {
			$termsRecord = $course->getCourseRecord($termsType);
			try {
				$terms = $termsRecord->getTerms();
				while ($terms->hasNext()) {
					$term = $terms->getNextTerm();
					// See if the term is in one of our chosen terms
					foreach ($this->selectedTerms as $selectedTermId) {
						if ($selectedTermId->isEqual($term->getId())) {
							$termStrings[$this->_helper->osidId->toString($term->getId())] = $term->getDisplayName();
						}
					}
				}
			} catch (osid_OperationFailedException $e) {
			}
		}
		$data->term_strings = $termStrings;

		$allTopics = $this->_helper->topics->topicListAsArray($course->getTopics());

		$reqs = array();
		$topicType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/requirement");
		$topicTypeString = $this->_helper->osidType->toString($topicType);
		$topics = $this->_helper->topics->filterTopicsByType($allTopics, $topicType);
		foreach ($topics as $topic) {
			$reqs[] = $this->view->escape($topic->getDisplayName());
		}
		$data->requirements = $reqs;

		/*********************************************************
		 * Section descriptions
		 *********************************************************/
		// Look for different Section Descriptions
		$offeringQuery = $this->offeringSearchSession->getCourseOfferingQuery();
		$offeringQuery->matchCourseId($course->getId(), true);
		$offeringQuery->matchGenusType(new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:offering/LCT"), true);
		$offeringQuery->matchGenusType(new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:offering/SEM"), true);
		foreach ($this->selectedTerms as $termId) {
			$offeringQuery->matchTermId($termId, true);
		}
		$order = $this->offeringSearchSession->getCourseOfferingSearchOrder();
		$order->orderByTerm();
		$order->ascend();
		$search = $this->offeringSearchSession->getCourseOfferingSearch();
		$search->orderCourseOfferingResults($order);
		$offerings = $this->offeringSearchSession->getCourseOfferingsBySearch($offeringQuery, $search);

		$sectionTerms = array();
		$sectionDescriptions = array();
		$sectionInstructors = array();
		$instructorsType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors');
		$namesType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:person_names');
		while ($offerings->hasNext()) {
			$offering = $offerings->getNextCourseOffering();
			$term = $offering->getTerm();
			$termIdString = $this->_helper->osidId->toString($term->getId());
			$sectionTerms[$termIdString] = $term->getDisplayName();
			if ($offering->getDescription() && $offering->getDescription() != $course->getDescription()) {
				$sectionDescriptions[$termIdString] = $offering->getDescription();
			}
			if ($offering->hasRecordType($instructorsType)) {
				$instructorsRecord = $offering->getCourseOfferingRecord($instructorsType);
				$instructors = $instructorsRecord->getInstructors();
				if (!isset($sectionInstructors[$termIdString]))
					$sectionInstructors[$termIdString] = array(
						'label' => $term->getDisplayName(),
						'instructors' => array(),
					);
				while($instructors->hasNext()) {
					$instructor = $instructors->getNextResource();
					$instructorIdString = $this->_helper->osidId->toString($instructor->getId());
					$sectionInstructors[$termIdString]['instructors'][$instructorIdString] = $instructor->getDisplayName();
					if ($instructor->hasRecordType($namesType)) {
						$nameRecord = $instructor->getResourceRecord($namesType);
						$sectionInstructors[$termIdString]['instructors'][$instructorIdString] = substr($nameRecord->getGivenName(), 0, 1).'. '.$nameRecord->getSurname();
					}
				}
			}
		}
		$data->instructors = $this->getInstructorText($sectionInstructors);

		$sectionDescriptionsText = '';
		// Replace the description with the one from the section if there is only one section.
		if (count($sectionDescriptions) == 1 && count($termStrings) == 1) {
			reset($sectionDescriptions);
			$data->description = current($sectionDescriptions);

		}
		// If there are multiple section descriptions, print them separately
		else if (count($sectionDescriptions)) {
			$data->description = '';
			foreach ($termStrings as $i => $desc) {
				$section_data = new stdClass;
				if (empty($sectionDescriptions[$i]))
					$section_data->description = $course->getDescription();
				else
					$section_data->description = $sectionDescriptions[$i];
				$section_data->term = $sectionTerms[$i];
				$section_data->instructors = $this->getInstructorText($sectionInstructors, $i);
				$data->sections[] = $section_data;
			}
		}

		/*********************************************************
		 * Crosslists
		 *********************************************************/
		$data->alternates = array();
		$alternateType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:record:terms");
		try {
			if ($course->hasRecordType($this->alternateType)) {
				$record = $course->getCourseRecord($this->alternateType);
				if ($record->hasAlternates()) {
					$alternates = $record->getAlternates();
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
									foreach ($this->selectedTerms as $selectedTermId) {
										if ($selectedTermId->isEqual($term->getId())) {
											$altInSelectedTerms = true;
											break;
										}
									}
								}
							} catch (osid_OperationFailedException $e) {
							}
						}
						if ($altInSelectedTerms) {
							$alt_data = new stdClass();
							$alt_data->display_name = $alternate->getDisplayName();
							$alt_data->id = $this->_helper->osidId->toString($alternate->getId());
							$alt_data->anchor = str_replace('/','_', $alt_data->id);
							if ($alternate->hasRecordType($this->alternateType)) {
								$alt_record = $alternate->getCourseRecord($this->alternateType);
								$alt_data->is_primary = $alt_record->isPrimary();
							}
							$data->alternates[] = $alt_data;
						}
					}
				}
			}
		} catch (osid_NotFoundException $e) {
		}

		return $data;
	}

	/**
	 * Answer an instructor listing string
	 *
	 * @param array $sectionInstructors
	 * @param string $termIdString
	 * @return string
	 */
	protected function getInstructorText (array $sectionInstructors, $termIdString = null) {
		if (empty($sectionInstructors))
			return '';
		foreach ($sectionInstructors as $termId => &$termInfo) {
			$ids = array_keys($termInfo['instructors']);
			sort($ids);
			$termInfo['hash'] = implode(':', $ids);
			$termInfo['instructorString'] = implode(', ', $termInfo['instructors']);
		}

		// Use just the instructors of the term passed.
		if ($termIdString) {
			if (empty($sectionInstructors[$termIdString]['instructorString']))
				return '';
			else
				return ' ('.$sectionInstructors[$termIdString]['instructorString'].')';
		}

		// For a course with just a single term, use that term's instructors
		if (count($sectionInstructors) === 1) {
			reset($sectionInstructors);
			$info = current($sectionInstructors);
			if (empty($info['instructorString']))
				return '';
			else
				return ' ('.$info['instructorString'].')';
		}

		// For courses with multiple terms, first find out if the instructor list is always the same.
		reset($sectionInstructors);
		$firstTerm = current($sectionInstructors);
		$firstHash = $firstTerm['hash'];
		$instructorListConstant = true;
		foreach ($sectionInstructors as $termId => $info) {
			if ($info['hash'] != $firstHash)
				$instructorListConstant = false;
		}
		// If we have the same instructor list each term, just use the first string.
		if ($instructorListConstant) {
			if (empty($firstTerm['instructorString']))
				return '';
			else
				return ' ('.$firstTerm['instructorString'].')';
		}
		// If we have a different instructor list each term, identify them.
		else {
			$termStrings = array();
			foreach ($sectionInstructors as $termId => $info) {
				if (!empty($info['instructorString']))
					$termStrings[] = $info['label'].': '.$info['instructorString'];
			}
			return ' ('.implode('; ', $termStrings).')';
		}
	}

	function _textToLink($text) {
		return preg_replace('/[^a-z0-9.:]+/i', '-', $text);
	}
}
