<?php

/** Zend_Controller_Action */
class ExportController extends Zend_Controller_Action
{
  public function addAction()
  {
    $this->_helper->layout()->disableLayout();
    $this->_helper->viewRenderer->setNoRender(true);

    if ($this->getRequest()->isXmlHttpRequest()) {
        if ($this->getRequest()->isPost()) {
          $db = Zend_Registry::get('db');
          $query =
          "INSERT INTO archive_configuration_revisions (`arch_conf_id`, `last_saved`, `user_id`, `user_disp_name`, `json_data`)
          VALUES (
            " . $this->getRequest()->getPost('catalogId') . ",
            CURRENT_TIMESTAMP,
            '" . $this->getRequest()->getPost('uid') . "',
            '" . $this->getRequest()->getPost('udn') . "',"
            . "'" . $this->getRequest()->getPost('jsonData') . "')";
          $stmt = $db->prepare($query);
          $stmt->execute();
          return $this->getRequest()->getPost();
        }
    }
    else {
        echo 'This route for XmlHttpRequests only.  Sorry!';
    }
  }

  public function generatecourselistAction()
  {
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
}

?>
