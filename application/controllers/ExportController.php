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
 * @since 1/23/18
 * @package catalog.controllers
 *
 * @copyright Copyright &copy; 2018, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

class ExportController
  extends AbstractCatalogController
{
  /**
	 * Initialize object
	 *
	 * Called from {@link __construct()} as final step of object instantiation.
	 *
	 * @return void
	 */
  public function init () {
    parent::init();
    $this->view->csrf_key = $this->_helper->csrfKey();

    if (!$this->_helper->auth()->isAuthenticated())
      $this->_helper->auth()->login();

    $config = Zend_Registry::getInstance()->config;
    if (!isset($config->admin->administrator_ids))
      throw new PermissionDeniedException('No admins are defined for this application.');
    $admins = explode(',', $config->admin->administrator_ids);
    if (!in_array($this->_helper->auth()->getUserId(), $admins))
      throw new PermissionDeniedException('You are not authorized to administer this application.' . $admins[1]);
  }

  /**
	 * Echo JSON data of latest revision for a particular archive configuration.
	 *
	 * @return void
	 * @access public
	 * @since 1/23/18
	 */
  public function latestrevisionAction() {

    $this->_helper->layout()->disableLayout();
    $this->_helper->viewRenderer->setNoRender(true);

    $db = Zend_Registry::get('db');
    $query =
    "SELECT
      *
     FROM archive_configuration_revisions a
     INNER JOIN (
      SELECT
        arch_conf_id,
        MAX(last_saved) as latest
      FROM archive_configuration_revisions
      GROUP BY arch_conf_id
    ) b ON a.arch_conf_id = b.arch_conf_id and a.last_saved = b.latest
     WHERE a.arch_conf_id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute(array(filter_input(INPUT_GET, 'configId', FILTER_SANITIZE_SPECIAL_CHARS)));
    $latestRevision = $stmt->fetch();
    echo $latestRevision['json_data'];
  }

  /**
	 * Display revision history for a given archive configuration.
	 *
	 * @return void
	 * @access public
	 * @since 1/24/18
	 */
  public function revisionhistoryAction() {
    if(!$this->_getParam('configId')) {
      header('HTTP/1.1 400 Bad Request');
      print "A configId must be specified.";
      exit;
    }
    $db = Zend_Registry::get('db');
    $query = "SELECT label FROM archive_configurations WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute(array(filter_input(INPUT_GET, 'configId', FILTER_SANITIZE_SPECIAL_CHARS)));
    $this->view->configLabel = $stmt->fetch()['label'];

    $query = "SELECT * FROM archive_configuration_revisions WHERE arch_conf_id = ? ORDER BY last_saved DESC";
    $stmt = $db->prepare($query);
    $stmt->execute(array(filter_input(INPUT_GET, 'configId', FILTER_SANITIZE_SPECIAL_CHARS)));
    $this->view->revisions = $stmt->fetchAll();
  }

  /**
	 * Display diff between two revisions.
	 *
	 * @return void
	 * @access public
	 * @since 1/25/18
	 */
  public function revisiondiffAction() {
    if (!$this->getRequest()->isPost()) {
      print "This page requires POST data.";
    } else {
      $this->view->text1 = $this->getRequest()->getPost('text1');
      $this->view->text2 = $this->getRequest()->getPost('text2');
      $this->view->time1 = $this->getRequest()->getPost('time1');
      $this->view->time2 = $this->getRequest()->getPost('time2');
    }
  }

  /**
	 * Display revision JSON data.
	 *
	 * @return void
	 * @access public
	 * @since 1/25/18
	 */
  public function viewjsonAction() {
    if (!$this->_getParam('revId')) {
      print "This route requires a revId";
    } else {
      $db = Zend_Registry::get('db');
      $query = "SELECT * FROM archive_configuration_revisions WHERE id = ?";
      $stmt = $db->prepare($query);
      $stmt->execute(array(filter_input(INPUT_GET, 'revId', FILTER_SANITIZE_SPECIAL_CHARS)));
      $this->view->revision = $stmt->fetch();
    }
  }

  /**
	 * Provide interface for creating a new archive configuration.
	 *
	 * @return void
	 * @access public
	 * @since 1/23/18
	 */
  public function newconfigAction() {
    $lookupSession = $this->_helper->osid->getCourseManager()->getCourseCatalogLookupSession();
    $this->view->catalogs = $lookupSession->getCourseCatalogs();
  }

  /**
	 * Delete an archive configuration.
	 *
	 * @return void
	 * @access public
	 * @since 1/23/18
	 */
  public function deleteconfigAction() {
    $this->_helper->layout()->disableLayout();
    $this->_helper->viewRenderer->setNoRender(true);

    // Delete revisions that depend on this config.
    $db = Zend_Registry::get('db');
    $query = "DELETE FROM archive_configuration_revisions WHERE arch_conf_id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute(array($this->getRequest()->getPost('configId')));

    // Delete this config.
    $query = "DELETE FROM archive_configurations WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute(array($this->getRequest()->getPost('configId')));
  }

  /**
	 * Insert a new archive configuration into the database.
	 *
	 * @return void
	 * @access public
	 * @since 1/23/18
	 */
  public function insertconfigAction() {
    if ($this->getRequest()->isPost()) {
      $safeLabel = filter_input(INPUT_POST, 'label', FILTER_SANITIZE_SPECIAL_CHARS);
      $safeCatalogId = filter_input(INPUT_POST, 'catalog_id', FILTER_SANITIZE_SPECIAL_CHARS);

      $db = Zend_Registry::get('db');
      $query =
      "INSERT INTO archive_configurations (id, label, catalog_id)
      VALUES (NULL,:label,:catalogId)";
      $stmt = $db->prepare($query);
      $stmt->execute(array(':label' => $safeLabel, ':catalogId' => $safeCatalogId));
    }

    $this->_helper->redirector('export', 'admin');
  }

  /**
	 * Echo JSON data of all archive export jobs.
	 *
	 * @return void
	 * @access public
	 * @since 1/23/18
	 */
  public function listjobsAction() {
    $this->_helper->layout()->disableLayout();
    $this->_helper->viewRenderer->setNoRender(true);

    $db = Zend_Registry::get('db');
    $configs = $db->query("SELECT * FROM archive_configurations")->fetchAll();
    $revisions = $db->query("SELECT * FROM archive_configuration_revisions ORDER BY last_saved DESC")->fetchAll();
    $jobs = $db->query("SELECT * FROM archive_jobs ORDER BY terms DESC")->fetchAll();

    $data = array();
    $data[] = array("configs" => $configs);
    $data[] = array("revisions" => $revisions);
    $data[] = array("jobs" => $jobs);

    echo json_encode($data);
  }

  /**
	 * Provide an interface for creating a new archive export job.
	 *
	 * @return void
	 * @access public
	 * @since 1/23/18
	 */

  public function newjobAction() {
    $db = Zend_Registry::get('db');
    $this->view->configs = $db->query("SELECT * FROM archive_configurations")->fetchAll();

    $this->view->config = NULL;
		if ($this->_getParam('config')) {
			foreach($this->view->configs as $config) {
				if ($config['label'] === $this->_getParam('config'))
					$this->view->config = $config;
			}
		}
  }

  /**
	 * Delete an archive export job.
	 *
	 * @return void
	 * @access public
	 * @since 1/23/18
	 */
  public function deletejobAction() {
    $this->_helper->layout()->disableLayout();
    $this->_helper->viewRenderer->setNoRender(true);

    // Delete revisions that depend on this config.
    $db = Zend_Registry::get('db');
    $query = "DELETE FROM archive_jobs WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute(array($this->getRequest()->getPost('jobId')));
  }

  /**
	 * Insert a new archive export job into the DB.
	 *
	 * @return void
	 * @access public
	 * @since 1/23/18
	 */
  public function insertjobAction() {
    if ($this->getRequest()->isPost()) {
      $safeConfigId = filter_input(INPUT_POST, 'configId', FILTER_SANITIZE_SPECIAL_CHARS);
      $safeExportPath = filter_input(INPUT_POST, 'export_path', FILTER_SANITIZE_SPECIAL_CHARS);
      $safeTerms = filter_input(INPUT_POST, 'terms', FILTER_SANITIZE_SPECIAL_CHARS);

      $db = Zend_Registry::get('db');
      $query =
      "INSERT INTO archive_jobs (id, active, export_path, config_id, revision_id, terms)
      VALUES (NULL, 1, :export_path, :config_id, NULL, :terms)";
      $stmt = $db->prepare($query);
      $stmt->execute(array(':export_path' => $safeExportPath, ':config_id' => $safeConfigId, ':terms' => $safeTerms));
    }

    $this->_helper->redirector('schedule', 'admin');
  }

  /**
	 * Update an existing archive export job.
	 *
	 * @return void
	 * @access public
	 * @since 1/23/18
	 */
  public function updatejobAction() {
    $this->_helper->layout()->disableLayout();
    $this->_helper->viewRenderer->setNoRender(true);

    if ($this->getRequest()->isPost()) {
      $safeId = filter_input(INPUT_POST, 'jobId', FILTER_SANITIZE_SPECIAL_CHARS);
      $safeActive = filter_input(INPUT_POST, 'active', FILTER_SANITIZE_SPECIAL_CHARS);
      $safeExportPath = filter_input(INPUT_POST, 'export_path', FILTER_SANITIZE_SPECIAL_CHARS);
      $safeConfigId = filter_input(INPUT_POST, 'config_id', FILTER_SANITIZE_SPECIAL_CHARS);
      $safeRevisionId = filter_input(INPUT_POST, 'revision_id', FILTER_SANITIZE_SPECIAL_CHARS);
      if ($safeRevisionId === 'latest') $safeRevisionId = NULL;
      $safeTerms = filter_input(INPUT_POST, 'terms', FILTER_SANITIZE_SPECIAL_CHARS);

      $db = Zend_Registry::get('db');
      $query =
      "UPDATE archive_jobs
      SET active = :active, export_path = :export_path, config_id = :config_id, revision_id = :revision_id, terms = :terms
      WHERE id = :id";
      $stmt = $db->prepare($query);
      $stmt->execute(array(':id' => $safeId, ':active' => $safeActive, ':export_path' => $safeExportPath, ':config_id' => $safeConfigId, ':revision_id' => $safeRevisionId, ':terms' => $safeTerms));
    }
  }

  /**
	 * Echo JSON data of all archive configuration revisions.
	 *
	 * @return void
	 * @access public
	 * @since 1/23/18
	 */
  public function listrevisionsAction() {
    $this->_helper->layout()->disableLayout();
    $this->_helper->viewRenderer->setNoRender(true);

    $db = Zend_Registry::get('db');
    $revisions = $db->query("SELECT * FROM archive_configuration_revisions ORDER BY last_saved DESC")->fetchAll();

    echo json_encode($revisions);

  }

  /**
	 * Revert to an older revision by re-inserting it with new timestamp.
	 *
	 * @return void
	 * @access public
	 * @since 1/25/18
	 */
  public function reverttorevisionAction() {
    $this->_helper->layout()->disableLayout();
    $this->_helper->viewRenderer->setNoRender(true);

    $safeRevisionId = filter_input(INPUT_POST, 'revId', FILTER_SANITIZE_SPECIAL_CHARS);

    $db = Zend_Registry::get('db');
    $query = "SELECT * FROM archive_configuration_revisions WHERE id=:id";
    $stmt = $db->prepare($query);
    $stmt->execute(array(':id' => $safeRevisionId));
    $oldRevision = $stmt->fetch();
    $note = "Revert to revision: " . $safeRevisionId . " from " . $oldRevision['last_saved'];

    $query =
    "INSERT INTO archive_configuration_revisions (`arch_conf_id`, `note`, `last_saved`, `user_id`, `user_disp_name`, `json_data`)
    VALUES (
      :configId,
      :note,
      CURRENT_TIMESTAMP,
      :userId,
      :userDN,
      :jsonData)";
    $stmt = $db->prepare($query);
    $stmt->execute(array(':configId' => $oldRevision['arch_conf_id'], ':note' => $note, ':userId' => $this->_helper->auth()->getUserId(), ':userDN' => $this->_helper->auth()->getUserDisplayName(), ':jsonData' => $oldRevision['json_data']));
  }

  /**
	 * Insert new archive configuration revision to the DB.
	 *
	 * @return void
	 * @access public
	 * @since 1/23/18
	 */
  public function insertrevisionAction() {
    $this->_helper->layout()->disableLayout();
    $this->_helper->viewRenderer->setNoRender(true);

    if(!$this->getRequest()->isPost()) {
      print "This route requires a POST request";
      return;
    }

    $safeConfigId = filter_input(INPUT_POST, 'configId', FILTER_SANITIZE_SPECIAL_CHARS);
    $safeNote = filter_input(INPUT_POST, 'note', FILTER_SANITIZE_SPECIAL_CHARS);
    $jsonArray = json_decode($this->getRequest()->getPost('jsonData'));
    foreach($jsonArray as $key => $value) {
      $value = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
    }
    $safeJsonData = json_encode($jsonArray, JSON_PRETTY_PRINT);

    $db = Zend_Registry::get('db');
    $query =
    "INSERT INTO archive_configuration_revisions (`arch_conf_id`, `note`, `last_saved`, `user_id`, `user_disp_name`, `json_data`)
    VALUES (
      :configId,
      :note,
      CURRENT_TIMESTAMP,
      :userId,
      :userDN,
      :jsonData)";
    $stmt = $db->prepare($query);
    $stmt->execute(array(':configId' => $safeConfigId, ':note' => $safeNote, ':userId' => $this->_helper->auth()->getUserId(), ':userDN' => $this->_helper->auth()->getUserDisplayName(), ':jsonData' => $safeJsonData));
    return $this->getRequest()->getPost();
  }

  /**
	 * Echo HTML for a course list dropdown menu based on an archive configuration ID.
	 *
	 * @return void
	 * @access public
	 * @since 1/23/18
	 */
  public function generatecourselistAction() {

    if ($this->_getParam('catalogId')) {
      $catalogId = $this->_helper->osidId->fromString($this->_getParam('catalogId'));
      $this->departmentType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/department");
			$this->subjectType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/subject");

      $topicSearchSession = $this->_helper->osid->getCourseManager()->getTopicSearchSessionForCatalog($catalogId);
			$topicQuery = $topicSearchSession->getTopicQuery();
			$topicQuery->matchGenusType($this->departmentType, true);
			if (isset($termId) && $topicQuery->hasRecordType($this->termType)) {
				$record = $topicQuery->getTopicQueryRecord($this->termType);
				$record->matchTermId($termId, true);
			}
			$search = $topicSearchSession->getTopicSearch();
			$order = $topicSearchSession->getTopicSearchOrder();
			$order->orderByDisplayName();
			$search->orderTopicResults($order);
			$searchResults = $topicSearchSession->getTopicsBySearch($topicQuery, $search);
			$departments = $searchResults->getTopics();

			$topicQuery = $topicSearchSession->getTopicQuery();
			$topicQuery->matchGenusType($this->subjectType, true);
			if (isset($termId) && $topicQuery->hasRecordType($this->termType)) {
				$record = $topicQuery->getTopicQueryRecord($this->termType);
				$record->matchTermId($termId, true);
			}
			$search = $topicSearchSession->getTopicSearch();
			$order = $topicSearchSession->getTopicSearchOrder();
			$order->orderByDisplayName();
			$search->orderTopicResults($order);
			$searchResults = $topicSearchSession->getTopicsBySearch($topicQuery, $search);
			$subjects = $searchResults->getTopics();

      $sectionInput = "
      <select class='section-dropdown' value='unselected'>
        <option value='unselected'>Please choose a subject</option>
        <optgroup label='Subjects'>";
        while ($subjects->hasNext()) {
          $topic = $subjects->getNextTopic();
          $sectionInput .= "<option value='" . Zend_Controller_Action_HelperBroker::getStaticHelper('OsidId')->toString($topic->getId()) . "'>" . $this->view->escape($topic->getDisplayName()) . "</option>";
        }
        $sectionInput .= "
        </optgroup>
        <optgroup label='Departments'>";
        while ($departments->hasNext()) {
          $topic = $departments->getNextTopic();
          $sectionInput .= "<option value='" . Zend_Controller_Action_HelperBroker::getStaticHelper('OsidId')->toString($topic->getId()) . "'>" . $this->view->escape($topic->getDisplayName()) . "</option>";
        }
        $sectionInput .= "
        </optgroup>
      </select>";

      echo $sectionInput;

      $this->_helper->layout()->disableLayout();
      $this->_helper->viewRenderer->setNoRender(true);
    }
    else {
      echo 'Invalid request.  Please provide a catalogId';
    }
  }

  /**
	 * Echo whether a user-entered term ID is valid.
	 *
	 * @return void
	 * @access public
	 * @since 1/23/18
	 */
   // TODO - return instead of echo?
  public function validtermAction() {
    if (!$this->_getParam('catalogId')) {
      echo "No catalog specified!";
      exit;
    }
    if (!$this->_getParam('term')) {
      echo "No term specified!";
      exit;
    }

    $catalogId = $this->_helper->osidId->fromString($this->_getParam('catalogId'));
    $this->termLookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSessionForCatalog($catalogId);

    try {
      $termString = 'term/'.$this->_getParam('term');
      $termId = $this->_helper->osidId->fromString($termString);
    }
    catch (osid_InvalidArgumentException $e) {
			header('HTTP/1.1 400 Bad Request');
			print "The term id specified was not of the correct format.";
			exit;
		}
    catch (osid_NotFoundException $e) {
      echo "not valid";
    }

    if ($this->termLookupSession->getTerm($termId)) {
      echo "valid";
    } else {
      echo "not valid";
    }

    $this->_helper->layout()->disableLayout();
    $this->_helper->viewRenderer->setNoRender(true);
  }
}

?>
