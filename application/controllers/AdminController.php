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
			throw new PermissionDeniedException('You are not authorized to administer this application.' . $admins[1]);
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
			$catalog = $this->_getParam('catalog');
			$term = $this->_getParam('term');
			$verifyStmt = $db->prepare("SELECT COUNT(*) FROM STVTERM WHERE STVTERM_CODE = ?");
			$verifyStmt->execute(array($term));
			$valid = intval($verifyStmt->fetchColumn());
			$verifyStmt->closeCursor();
			if (!$valid)
				throw new InvalidArgumentException('Invalid term-code: '.$term);

			// Disable the term
			if ($this->_getParam('disabled') == 'true') {
				$visibilityStmt = $db->prepare('INSERT INTO catalog_term_inactive (catalog_id, term_code) VALUES (?, ?);');
			}
			// Enable the term
			else {
				$visibilityStmt = $db->prepare('DELETE FROM catalog_term_inactive WHERE catalog_id = ? AND term_code = ?;');
			}
			$visibilityStmt->execute(array($catalog, $term));
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
	LEFT JOIN catalog_term_inactive i ON (STVTERM_CODE = i.term_code AND i.catalog_id = ?)
	LEFT JOIN course_catalog c ON i.catalog_id = c.catalog_id
	LEFT JOIN ssbsect_scbcrse s ON (STVTERM_CODE = SSBSECT_TERM_CODE
		AND SCBCRSE_COLL_CODE IN (
			SELECT coll_code
			FROM course_catalog_college
			WHERE catalog_id = ?
		)
		AND SSBSECT_SSTS_CODE = 'A'
		AND (c.prnt_ind_to_exclude IS NULL OR SSBSECT_PRNT_IND != c.prnt_ind_to_exclude)
		)
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

		$stmt->execute(array($catalog, $catalog, $catalog));
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

	public function exportAction()
	{
		$db = Zend_Registry::get('db');

		$this->view->configs = $db->query("SELECT * FROM archive_configurations")->fetchAll();

		$this->view->config = NULL;
		if ($this->_getParam('config')) {
			foreach($this->view->configs as $config) {
				if ($config['label'] === $this->_getParam('config'))
					$this->view->config = $config;
			}
		}

		if(isset($this->view->config)) {
			$query = "SELECT * FROM archive_configuration_revisions WHERE arch_conf_id = ?";
			$stmt = $db->prepare($query);
			$stmt->execute(array($this->view->config['id']));
			$this->view->latestRevision = $stmt->fetch();

		}
	}

	/**
	 * Manage antirequisites
	 *
	 * @return void
	 * @access public
	 * @since 12/6/2017
	 */
	public function antirequisitesAction() {
		$db = Zend_Registry::get('db');

		// Delete any requested item.
		if ($this->_getParam('delete')) {
			// Verify our CSRF key
			if (!$this->_getParam('csrf_key') == $this->_helper->csrfKey())
				throw new PermissionDeniedException('Invalid CSRF Key. Please log in again.');

			// Verify that this is a valid term.
			$deleteStmt = $db->prepare("DELETE FROM antirequisites WHERE subj_code = ? AND crse_numb = ? AND subj_code_eqiv = ? AND crse_numb_eqiv = ?");
			$deleteStmt->execute(array(
				$this->_getParam('subj_code'),
				$this->_getParam('crse_numb'),
				$this->_getParam('subj_code_eqiv'),
				$this->_getParam('crse_numb_eqiv'),
			));
		}

		// Add any chosen items.
		if ($this->_getParam('add')) {
			// Verify our CSRF key
			if (!$this->_getParam('csrf_key') == $this->_helper->csrfKey())
				throw new PermissionDeniedException('Invalid CSRF Key. Please log in again.');

			// Verify that this is a valid term.
			$insertStmt = $db->prepare("INSERT INTO antirequisites (subj_code, crse_numb, subj_code_eqiv, crse_numb_eqiv, added_by, comments) VALUES (?, ?, ?, ?, ?, ?)");
			foreach ($this->_getParam('equivalents_to_add') as $toAdd) {
				$params = explode('/', $toAdd);
				$params[] = $this->view->getUserDisplayName();
				$params[] = $this->_getParam($toAdd.'-comments');
				$insertStmt->execute($params);
			}

		}

		// Select our already-created antirequisites
		$this->view->antirequisites = $db->query("SELECT * FROM antirequisites ORDER BY subj_code, crse_numb, subj_code_eqiv, crse_numb_eqiv")->fetchAll();


		// Supply search results.
		$this->view->search_subj_code = $this->_getParam('search_subj_code');
		$this->view->search_crse_numb = $this->_getParam('search_crse_numb');
		if ($this->_getParam('search_subj_code') && $this->_getParam('search_crse_numb')) {
			$searchStmt = $db->prepare(
				"SELECT
					*,
					subj_code IS NOT NULL AS antirequisite
				FROM
					SCREQIV
					LEFT JOIN antirequisites
						ON (SCREQIV_SUBJ_CODE = subj_code AND SCREQIV_CRSE_NUMB = crse_numb
							AND SCREQIV_SUBJ_CODE_EQIV = subj_code_eqiv AND SCREQIV_CRSE_NUMB_EQIV = crse_numb_eqiv)
				WHERE
					(SCREQIV_SUBJ_CODE = ? AND SCREQIV_CRSE_NUMB = ?)
					OR (SCREQIV_SUBJ_CODE_EQIV = ? AND SCREQIV_CRSE_NUMB_EQIV = ?)
				GROUP BY
					SCREQIV_SUBJ_CODE, SCREQIV_CRSE_NUMB, SCREQIV_SUBJ_CODE_EQIV, SCREQIV_CRSE_NUMB_EQIV
				ORDER BY
					SCREQIV_SUBJ_CODE, SCREQIV_CRSE_NUMB, SCREQIV_SUBJ_CODE_EQIV, SCREQIV_CRSE_NUMB_EQIV
				");
			$searchStmt->execute(array(
					$this->_getParam('search_subj_code'),
					$this->_getParam('search_crse_numb'),
					$this->_getParam('search_subj_code'),
					$this->_getParam('search_crse_numb'),
				));
			$this->view->searchResults = $searchStmt->fetchAll(PDO::FETCH_OBJ);
		}
	}
}
