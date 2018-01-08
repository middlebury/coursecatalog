<?php

/** Zend_Controller_Action */
class ExportsController extends Zend_Controller_Action
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
            '1',
            CURRENT_TIMESTAMP,
            '35411217E5C630E81792E8F256807B19',
            'Corey Selover',"
            . "'" . $this->getRequest()->getPost('jsonData') . "')";
          $stmt = $db->prepare($query);
          $stmt->execute();
          return $this->getRequest()->getPost();
        }
    }
    else {
        echo 'Not Ajax';
        // ... Do normal controller logic here (To catch non ajax calls to the script)
    }
  }
}

?>
