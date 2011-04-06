<?php

/** Zend_Controller_Action */
class AdminController 
	extends AbstractCatalogController
{
	
	/**
	 * Constructor
	 * 
	 * @return void
	 * @access public
	 * @since 11/3/09
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
			throw new PermissionDeniedException('You are not authorized to administer this application.');
	}
	
	/**
	 * List Admin Screens
	 * 
	 * @return void
	 * @access public
	 * @since 7/29/10
	 */
	public function indexAction () {
		
	}
	
	/**
	 * Manage term visibility
	 * 
	 * @return void
	 * @access public
	 * @since 7/29/10
	 */
	public function termsAction () {
		$db = Zend_Registry::get('db');
		
		if ($this->_getParam('change_visibility')) {
			// Verify our CSRF key
			if (!$this->_getParam('csrf_key') == $this->_helper->csrfKey())
				throw new PermissionDeniedException('Invalid CSRF Key. Please log in again.');
			
			// Verify that this is a valid term.
			$term = $this->_getParam('term');
			$verifyStmt = $db->prepare("SELECT COUNT(*) FROM STVTERM WHERE STVTERM_CODE = ?");
			$verifyStmt->execute(array($term));
			$valid = intval($verifyStmt->fetchColumn());
			$verifyStmt->closeCursor();
			if (!$valid)
				throw new InvalidArgumentException('Invalid term-code: '.$term);
			
			// Disable the term
			if ($this->_getParam('disabled') == 'true') {
				$visibilityStmt = $db->prepare('INSERT INTO catalog_term_inactive VALUES (?);');	
			}
			// Enable the term
			else {
				$visibilityStmt = $db->prepare('DELETE FROM catalog_term_inactive WHERE term_code=?;');
			}
			$visibilityStmt->execute(array($term));
		}
		
		$searches = $db->query("SELECT * FROM catalog_term_match")->fetchAll();
		
		$catalogs = array();
		$queries = array();
		foreach ($searches as $search) {
			$catalogs[] = $search['catalog_id'];
			$queries[] = 
"	SELECT
		'".$search['catalog_id']."' AS catalog,
		STVTERM_CODE,
		STVTERM_DESC
	FROM
		STVTERM
	WHERE
		STVTERM_CODE LIKE ('".$search['term_code_match']."')";
		}
		$union = implode("\n\tUNION\n", $queries);
		
		$query =	
"SELECT
	t.*,
	IF(i.term_code, 1, 0) AS manually_disabled,
	count(SSBSECT_CRN) AS num_sections
FROM
	(\n".$union."\n\t) AS t
	LEFT JOIN catalog_term_inactive i ON STVTERM_CODE = i.term_code
	LEFT JOIN SSBSECT s ON STVTERM_CODE = SSBSECT_TERM_CODE
WHERE
	catalog = ?
GROUP BY
	STVTERM_CODE
ORDER BY
	catalog ASC, STVTERM_CODE DESC";
		$stmt = $db->prepare($query);
		
		$this->view->catalogs = array_unique($catalogs);
		
// 		print "<pre>".$query."</pre>";
		if ($this->_getParam('catalog') && in_array($this->_getParam('catalog'), $this->view->catalogs))
			$catalog = $this->_getParam('catalog');
		else
			$catalog = $this->view->catalogs[0];
		
		$stmt->execute(array($catalog));
		$this->view->catalog = $catalog;
		$this->view->terms = $stmt->fetchAll();
	}
	
	public function masqueradeAction()
    {
    	$masqueradeAuth = $this->_helper->auth->getMasqueradeHelper();
    	
    	if ($this->_getParam('masquerade')) {
			// Verify our CSRF key
			if (!$this->_getParam('csrf_key') == $this->_helper->csrfKey())
				throw new PermissionDeniedException('Invalid CSRF Key. Please log in again.');
			
			$masqueradeAuth->changeUser($this->_getParam('masquerade'));
			$this->_redirect('/', array('prependBase' => true, 'exit' => true));
		}
		
		$this->view->userId = $this->_helper->auth()->getUserId();
		$this->view->userName = $this->_helper->auth()->getUserDisplayName();
    }
}
