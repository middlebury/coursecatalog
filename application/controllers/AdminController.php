<?php

include_once("fsmparserclass.inc.php");

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

	public function markupAction()
	{
		$this->view->parser = self::getFsmParser();

		if (isset($_POST['sample_text']) && strlen($_POST['sample_text'])) {
			$this->view->sampleText = $_POST['sample_text'];
		} else {
			$this->view->sampleText = "This is some text. Shakespeare wrote /The Merchant of Venice/ as well as /Macbeth/. Words can have slashes in them such as AC/DC, but this does not indicate italics.\n\nSpaces around slashes such as this / don't cause italics either. Quotes may be /\"used inside slashes\",/ or \"/outside of them/\". *Bold Text* should have asterisk characters around it. Like slashes, * can be used surrounded by spaces, or surrounded by letters or numbers and not cause bold formatting: 4*5 = 20 or 4 * 5 = 20. Numbers as well as text can be bold *42* or italic /85/";
		}
		$this->view->sampleText = htmlspecialchars($this->view->sampleText);

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

	/* Manage archive export configurations */
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

		// If user has selected a configuration to modify, get the latest revision.
		if(isset($this->view->config))
		{
			$catalogId = $this->_helper->osidId->fromString($this->view->config['catalog_id']);
			$this->view->catalogId = $this->view->config['catalog_id'];
		}
	}

	/**
	 * Manage catalog archive scheduling
	 *
	 * @return void
	 * @access public
	 * @since 1/16/2018
	 */
	public function scheduleAction()
	{
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

	/**
   * Answer an FMS Parser configured to convert markdown into XHTML text
   *
   * @return FSMParser
   * @access private
   * @since 2/9/17
   * @static
   */
  private static function getFsmParser () {
    if (!isset(self::$fsmParser)) {
      self::$fsmParser = new FSMParser();
      //---------Programming the FSM:
      /*********************************************************
       * Normal state
       *********************************************************/
      // Enter from unknown into normal state if the first character is not a slash or bold.
      self::$fsmParser->FSM('/[^\/\*]/s','echo $STRING;','CDATA','UNKNOWN');
      //In normal state, catch all other data
      self::$fsmParser->FSM('/./s','echo $STRING;','CDATA','CDATA');
      /*********************************************************
       * Italic
       *********************************************************/
      // Enter into Italic if at the begining of the line.
      self::$fsmParser->FSM(
        '/^\/\w/',
        'preg_match("/^\/(\w)/", $STRING, $m); echo "<em>".$m[1];',
        'ITALIC',
        'UNKNOWN');
      //In normal state, catch italic start
      self::$fsmParser->FSM(
        '/[^\w.:\/]\/\w/',
        'preg_match("/(\W)\/(\w)/", $STRING, $m); echo $m[1]."<em>".$m[2];',
        'ITALIC',
        'CDATA');
      // Close out of italic state back to normal
      self::$fsmParser->FSM(
        '/\w\/\W/',
        'preg_match("/(\w)\/(\W)/", $STRING, $m); echo $m[1]."</em>".$m[2];',
        'CDATA',
        'ITALIC');
      //In normal state, catch italic start for whitespace+non-word
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
      //In italic state, catch all other data
      self::$fsmParser->FSM('/./s','echo $STRING;','ITALIC','ITALIC');
      /*********************************************************
       * Bold
       *********************************************************/
      // Enter into Bold if at the begining of the line.
      self::$fsmParser->FSM(
        '/^\*\w/',
        'preg_match("/^\*(\w)/", $STRING, $m); echo "<strong>".$m[1];',
        'BOLD',
        'UNKNOWN');
      //In normal state, catch bold start
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
      //In normal state, catch bold start for whitespace+non-word
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
      //In bold state, catch all other data
      self::$fsmParser->FSM('/./s','echo $STRING;','BOLD','BOLD');
    }
    return self::$fsmParser;
  }
  private static $fsmParser;
}
