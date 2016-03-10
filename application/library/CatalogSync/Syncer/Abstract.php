<?php
/**
 * @since 2/22/16
 * @package CatalogSync
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * This class implements the Banner-to-Catalog sync using the Banner OCI connection
 * on the source side and a MySQL-PDO connection on the temporary cache side,
 * and mysql_dump to copy from the cache to the destination.
 *
 * @since 2/22/16
 * @package CatalogSync
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
abstract class CatalogSync_Syncer_Abstract
{

	protected $destination_db;
	protected $allowedBlckCodes = array();

	/**
	 * Configure this sync instance
	 *
	 * @param Zend_Config $config
	 * @return void
	 * @access public
	 */
	public function configure (Zend_Config $config) {
		$this->destination_db = new CatalogSync_Database_Destination_Pdo('destination_db');
		$this->destination_db->configure($config->destination_db);

		// Configure our block codes to import.
		if (!empty($config->allowedBlckCodes)) {
			foreach ($config->allowedBlckCodes as $code) {
				if (!is_string($code)) {
					throw new Exception('allowedBlckCodes[] must be an array of strings in the config.');
				}
				$this->allowedBlckCodes[] = $code;
			}
		}
	}

	/**
	 * Roll back any changes to the destination.
	 *
	 * @return void
	 * @access public
	 */
	public function rollback () {
		try {
			while ($this->destination_db->rollBack()) {
				// Keep rolling back all nested transactions.
			}
		} catch (PDOException $e) {
			// We will get a PDOException after the last transaction is rolled back.
			// We can now just move on.
		}
	}

	/**
	 * Set up connections to our source and destination.
	 *
	 * @return void
	 * @access public
	 */
	public function connect () {
		$this->destination_db->connect();
	}

	/**
	 * Update derived data in the destination database.
	 *
	 * @return void
	 * @access public
	 */
	public function updateDerived () {
		$pdo = $this->destination_db->getPdo();

		// Build derived table for easier term-catalog lookups
		print "Updating derived tables\t";
		$pdo->beginTransaction();
		$ttermcat = $pdo->prepare("DELETE FROM catalog_term");
		$ttermcat->execute();

		$searches = $pdo->query("SELECT * FROM catalog_term_match")->fetchAll();

		$itermcat = $pdo->prepare("
			INSERT INTO
				catalog_term
				(catalog_id, term_code, term_display_label)
			SELECT
				:catalog_id,
				STVTERM_CODE,
				:term_display_label
			FROM
				STVTERM
			WHERE
				STVTERM_CODE LIKE (:term_code_match)
			");

		foreach ($searches as $search) {
			$itermcat->execute(array(
				':catalog_id' => $search['catalog_id'],
				':term_code_match' => $search['term_code_match'],
				':term_display_label' => $search['term_display_label']
			));
		}

		print "...\tUpdated derived table: catalog_term\n";

		// Delete terms that have no sections in them.
		print "Removing empty terms\t";

		// We can't rely on our view to be created yet, so built the most recent version of
		// course rows data into a temporary table.
		$pdo->query("CREATE TEMPORARY TABLE temp_scbcrse_recent
		SELECT
			crse1.*,
			IF (SCREQIV_SUBJ_CODE_EQIV IS NULL, 0, 1) AS has_alternates
		FROM
			SCBCRSE AS crse1

			-- 'Outer self exclusion join' to fetch only the most recent SCBCRSE row.
			LEFT JOIN SCBCRSE AS crse2
				ON (crse1.SCBCRSE_SUBJ_CODE = crse2.SCBCRSE_SUBJ_CODE
					AND crse1.SCBCRSE_CRSE_NUMB = crse2.SCBCRSE_CRSE_NUMB
					-- If crse2 is effective after crse1, a join will be successfull and crse2 non-null.
					-- On the latest crse1, crse2 will be null.
					AND crse1.SCBCRSE_EFF_TERM < crse2.SCBCRSE_EFF_TERM)
			LEFT JOIN SCREQIV
				ON (crse1.SCBCRSE_SUBJ_CODE = SCREQIV_SUBJ_CODE
					AND crse1.SCBCRSE_CRSE_NUMB = SCREQIV_CRSE_NUMB)

		WHERE

			-- Clause for the 'outer self exclusion join'
			crse2.SCBCRSE_SUBJ_CODE IS NULL");

		$pdo->query(
			"CREATE TEMPORARY TABLE temp_section_catalog
			SELECT
				catalog_id, SSBSECT_TERM_CODE AS term_code, COUNT(SSBSECT_CRN) AS num_sections
			FROM
				course_catalog_college
				LEFT JOIN temp_scbcrse_recent ON (coll_code = SCBCRSE_COLL_CODE)
				LEFT JOIN SSBSECT c ON (SCBCRSE_SUBJ_CODE = SSBSECT_SUBJ_CODE AND SCBCRSE_CRSE_NUMB = SSBSECT_CRSE_NUMB)
			WHERE
				coll_code IS NOT NULL
				AND SSBSECT_SSTS_CODE = 'A'
				AND SSBSECT_PRNT_IND != 'N'
			GROUP BY catalog_id, coll_code, SSBSECT_TERM_CODE
			");
		$empty_term_results = $pdo->query(
			"SELECT
				t.catalog_id,
				t.term_code
			FROM
				catalog_term t
				LEFT JOIN temp_section_catalog s ON (t.catalog_id = s.catalog_id AND t.term_code = s.term_code)
			WHERE
				s.num_sections IS NULL
			");
		$delete = $pdo->prepare(
			"DELETE FROM catalog_term
			WHERE
				catalog_id = ?
				AND term_code = ?");
		foreach ($empty_term_results->fetchAll(PDO::FETCH_OBJ) as $term) {
			$delete->execute(array($term->catalog_id, $term->term_code));
		}
		$pdo->query("DROP TEMPORARY TABLE temp_scbcrse_recent");
		$pdo->query("DROP TEMPORARY TABLE temp_section_catalog");
		print "...\tRemoved empty terms from derived table: catalog_term\n";

		// Delete terms that are manually inactivated.
		print "Removing deactivated terms\t";
		$deactivated_term_results = $pdo->query(
			"SELECT
				*
			FROM
				catalog_term_inactive
			");
		foreach ($deactivated_term_results->fetchAll(PDO::FETCH_OBJ) as $term) {
			$delete->execute(array($term->catalog_id, $term->term_code));
		}
		print "...\tRemoved deactivated terms from derived table: catalog_term\n";

		$pdo->commit();

		// Rebuild our "materialized views"
		require_once(dirname(__FILE__).'/../../harmoni/SQLUtils.php');
		print "Updating materialized views\t";
		harmoni_SQLUtils::runSQLfile(dirname(__FILE__).'/../../banner/sql/create_views.sql', $pdo);
		print "...\tUpdated materialized views\n";
	}

	/**
	 * Disconnect from our databases
	 *
	 * @return void
	 * @access public
	 */
	public function disconnect () {
		$this->destination_db->disconnect();
	}

	/**
	 * Answer the database we should copy into during copy.
	 *
	 * @return CatalogSync_Database_Destination
	 * @access public
	 */
	protected function getCopyTargetDatabase () {
		return $this->destination_db;
	}

	/**
	 * Take actions before copying data.
	 *
	 * @return void
	 * @access public
	 */
	public function preCopy () {
		// Override if needed.
	}

	/**
	 * Take actions after copying data.
	 *
	 * @return void
	 * @access public
	 */
	public function postCopy () {
		// Override if needed.
	}

	/**
	 * Copy data.
	 *
	 * @return void
	 * @access public
	 */
	public function copy () {
		$source_db = $this->getCopySourceDatabase();
		$target_db = $this->getCopyTargetDatabase();
		$target_db->beginTransaction();

		// GENERAL.GORINTG
		print "Updating GORINTG\t";
		$target_db->truncate("GORINTG");
		$insert = $target_db->prepareInsert("GORINTG", array(
				"GORINTG_CODE",
				"GORINTG_DESC",
				"GORINTG_INTP_CODE",
				"GORINTG_USER_ID",
				"GORINTG_ACTIVITY_DATE",
				"GORINTG_DATA_ORIGIN",
			));
		$select = $source_db->query("GENERAL.GORINTG");
		$select->convertDate("GORINTG_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated GORINTG\n";

		// GENERAL.GTVDUNT
		print "Updating GTVDUNT\t";
		$target_db->truncate("GTVDUNT");
		$insert = $target_db->prepareInsert("GTVDUNT", array(
				"GTVDUNT_CODE",
				"GTVDUNT_DESC",
				"GTVDUNT_NUMBER_OF_DAYS",
				"GTVDUNT_ACTIVITY_DATE",
				"GTVDUNT_USER_ID",
				"GTVDUNT_VR_MSG_NO",
			));
		$select = $source_db->query("GENERAL.GTVDUNT");
		$select->convertDate("GTVDUNT_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated GTVDUNT\n";

		// GENERAL.GTVINSM
		print "Updating GTVINSM\t";
		$target_db->truncate("GTVINSM");
		$insert = $target_db->prepareInsert("GTVINSM", array(
				"GTVINSM_CODE",
				"GTVINSM_DESC",
				"GTVINSM_ACTIVITY_DATE",
				"GTVINSM_USER_ID",
				"GTVINSM_VR_MSG_NO",
			));
		$select = $source_db->query("GENERAL.GTVINSM");
		$select->convertDate("GTVINSM_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated GTVINSM\n";

		// GENERAL.GTVINTP
		print "Updating GTVINTP\t";
		$target_db->truncate("GTVINTP");
		$insert = $target_db->prepareInsert("GTVINTP", array(
				"GTVINTP_CODE",
				"GTVINTP_DESC",
				"GTVINTP_USER_ID",
				"GTVINTP_ACTIVITY_DATE",
				"GTVINTP_DATA_ORIGIN",
			));
		$select = $source_db->query("GENERAL.GTVINTP");
		$select->convertDate("GTVINTP_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated GTVINTP\n";

		// GENERAL.GTVMTYP
		print "Updating GTVMTYP\t";
		$target_db->truncate("GTVMTYP");
		$insert = $target_db->prepareInsert("GTVMTYP", array(
				"GTVMTYP_CODE",
				"GTVMTYP_DESC",
				"GTVMTYP_SYS_REQ_IND",
				"GTVMTYP_ACTIVITY_DATE",
				"GTVMTYP_USER_ID",
				"GTVMTYP_VR_MSG_NO",
			));
		$select = $source_db->query("GENERAL.GTVMTYP");
		$select->convertDate("GTVMTYP_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated GTVMTYP\n";

		// GENERAL.GTVSCHS
		print "Updating GTVSCHS\t";
		$target_db->truncate("GTVSCHS");
		$insert = $target_db->prepareInsert("GTVSCHS", array(
				"GTVSCHS_CODE",
				"GTVSCHS_DESC",
				"GTVSCHS_SYSTEM_REQ_IND",
				"GTVSCHS_ACTIVITY_DATE",
			));
		$select = $source_db->query("GENERAL.GTVSCHS");
		$select->convertDate("GTVSCHS_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated GTVSCHS\n";

		// SATURN.SCBCRSE
		print "Updating SCBCRSE\t";
		$target_db->truncate("SCBCRSE");
		$insert = $target_db->prepareInsert("SCBCRSE", array(
				"SCBCRSE_SUBJ_CODE",
				"SCBCRSE_CRSE_NUMB",
				"SCBCRSE_EFF_TERM",
				"SCBCRSE_COLL_CODE",
				"SCBCRSE_DIVS_CODE",
				"SCBCRSE_DEPT_CODE",
				"SCBCRSE_CSTA_CODE",
				"SCBCRSE_TITLE",
				"SCBCRSE_CIPC_CODE",
				"SCBCRSE_CREDIT_HR_IND",
				"SCBCRSE_CREDIT_HR_LOW",
				"SCBCRSE_CREDIT_HR_HIGH",
				"SCBCRSE_LEC_HR_IND",
				"SCBCRSE_LEC_HR_LOW",
				"SCBCRSE_LEC_HR_HIGH",
				"SCBCRSE_LAB_HR_IND",
				"SCBCRSE_LAB_HR_LOW",
				"SCBCRSE_LAB_HR_HIGH",
				"SCBCRSE_OTH_HR_IND",
				"SCBCRSE_OTH_HR_LOW",
				"SCBCRSE_OTH_HR_HIGH",
				"SCBCRSE_BILL_HR_IND",
				"SCBCRSE_BILL_HR_LOW",
				"SCBCRSE_BILL_HR_HIGH",
				"SCBCRSE_APRV_CODE",
				"SCBCRSE_REPEAT_LIMIT",
				"SCBCRSE_PWAV_CODE",
				"SCBCRSE_TUIW_IND",
				"SCBCRSE_ADD_FEES_IND",
				"SCBCRSE_ACTIVITY_DATE",
				"SCBCRSE_CONT_HR_LOW",
				"SCBCRSE_CONT_HR_IND",
				"SCBCRSE_CONT_HR_HIGH",
				"SCBCRSE_CEU_IND",
				"SCBCRSE_REPS_CODE",
				"SCBCRSE_MAX_RPT_UNITS",
				"SCBCRSE_CAPP_PREREQ_TEST_IND",
				"SCBCRSE_DUNT_CODE",
				"SCBCRSE_NUMBER_OF_UNITS",
				"SCBCRSE_DATA_ORIGIN",
				"SCBCRSE_USER_ID",
			));
		$select = $source_db->query("SATURN.SCBCRSE");
		$select->convertDate("SCBCRSE_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated SCBCRSE\n";

		// SATURN.SCBDESC
		print "Updating SCBDESC\t";
		$target_db->truncate("SCBDESC");
		$insert = $target_db->prepareInsert("SCBDESC", array(
				"SCBDESC_SUBJ_CODE",
				"SCBDESC_CRSE_NUMB",
				"SCBDESC_TERM_CODE_EFF",
				"SCBDESC_ACTIVITY_DATE",
				"SCBDESC_USER_ID",
				"SCBDESC_TEXT_NARRATIVE",
				"SCBDESC_TERM_CODE_END",
			));
		$select = $source_db->query("SATURN.SCBDESC", array(
				"SCBDESC_SUBJ_CODE",
				"SCBDESC_CRSE_NUMB",
				"SCBDESC_TERM_CODE_EFF",
				"SCBDESC_ACTIVITY_DATE",
				"SCBDESC_USER_ID",
				"SCBDESC_TEXT_NARRATIVE",
				"SCBDESC_TERM_CODE_END",
			));
		$select->convertDate("SCBDESC_ACTIVITY_DATE");
		$select->convertText("SCBDESC_TEXT_NARRATIVE");
		$insert->insertAll($select);
		print "...\tUpdated SCBDESC\n";

		// SATURN.SCRATTR
		print "Updating SCRATTR\t";
		$target_db->truncate("SCRATTR");
		$insert = $target_db->prepareInsert("SCRATTR", array(
				"SCRATTR_SUBJ_CODE",
				"SCRATTR_CRSE_NUMB",
				"SCRATTR_EFF_TERM",
				"SCRATTR_ATTR_CODE",
				"SCRATTR_ACTIVITY_DATE",
			));
		$select = $source_db->query("SATURN.SCRATTR", array(
				"SCRATTR_SUBJ_CODE",
				"SCRATTR_CRSE_NUMB",
				"SCRATTR_EFF_TERM",
				"SCRATTR_ATTR_CODE",
				"SCRATTR_ACTIVITY_DATE",
			));
		$select->convertDate("SCRATTR_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated SCRATTR\n";

		// SATURN.SCREQIV
		print "Updating SCREQIV\t";
		$target_db->truncate("SCREQIV");
		$insert = $target_db->prepareInsert("SCREQIV", array(
				"SCREQIV_SUBJ_CODE",
				"SCREQIV_CRSE_NUMB",
				"SCREQIV_EFF_TERM",
				"SCREQIV_SUBJ_CODE_EQIV",
				"SCREQIV_CRSE_NUMB_EQIV",
				"SCREQIV_START_TERM",
				"SCREQIV_END_TERM",
				"SCREQIV_ACTIVITY_DATE",
			));
		$select = $source_db->query("SATURN.SCREQIV", array(
				"SCREQIV_SUBJ_CODE",
				"SCREQIV_CRSE_NUMB",
				"SCREQIV_EFF_TERM",
				"SCREQIV_SUBJ_CODE_EQIV",
				"SCREQIV_CRSE_NUMB_EQIV",
				"SCREQIV_START_TERM",
				"SCREQIV_END_TERM",
				"SCREQIV_ACTIVITY_DATE",
			));
		$select->convertDate("SCREQIV_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated SCREQIV\n";

		// SATURN.SCRLEVL
		print "Updating SCRLEVL\t";
		$target_db->truncate("SCRLEVL");
		$insert = $target_db->prepareInsert("SCRLEVL", array(
				"SCRLEVL_SUBJ_CODE",
				"SCRLEVL_CRSE_NUMB",
				"SCRLEVL_EFF_TERM",
				"SCRLEVL_LEVL_CODE",
				"SCRLEVL_ACTIVITY_DATE",
			));
		$select = $source_db->query("SATURN.SCRLEVL", array(
				"SCRLEVL_SUBJ_CODE",
				"SCRLEVL_CRSE_NUMB",
				"SCRLEVL_EFF_TERM",
				"SCRLEVL_LEVL_CODE",
				"SCRLEVL_ACTIVITY_DATE",
			));
		$select->convertDate("SCRLEVL_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated SCRLEVL\n";

		// SATURN.SSBXLST
		print "Updating SSBXLST\t";
		$target_db->truncate("SSBXLST");
		$insert = $target_db->prepareInsert("SSBXLST", array(
				"SSBXLST_TERM_CODE",
				"SSBXLST_XLST_GROUP",
				"SSBXLST_DESC",
				"SSBXLST_MAX_ENRL",
				"SSBXLST_ENRL",
				"SSBXLST_SEATS_AVAIL",
				"SSBXLST_ACTIVITY_DATE",
			));
		$select = $source_db->query("SATURN.SSBXLST");
		$select->convertDate("SSBXLST_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated SSBXLST\n";

		// SATURN.SSRXLST
		print "Updating SSRXLST\t";
		$target_db->truncate("SSRXLST");
		$insert = $target_db->prepareInsert("SSRXLST", array(
				"SSRXLST_TERM_CODE",
				"SSRXLST_CRN",
				"SSRXLST_XLST_GROUP",
				"SSRXLST_ACTIVITY_DATE",
			));
		$select = $source_db->query("SATURN.SSRXLST");
		$select->convertDate("SSRXLST_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated SSRXLST\n";

		// SATURN.SIRASGN
		print "Updating SIRASGN\t";
		$target_db->truncate("SIRASGN");
		$insert = $target_db->prepareInsert("SIRASGN", array(
				"SIRASGN_TERM_CODE",
				"SIRASGN_CRN",
				"SIRASGN_PIDM",
				"SIRASGN_CATEGORY",
				"SIRASGN_PERCENT_RESPONSE",
				"SIRASGN_WORKLOAD_ADJUST",
				"SIRASGN_PERCENT_SESS",
				"SIRASGN_PRIMARY_IND",
				"SIRASGN_OVER_RIDE",
				"SIRASGN_POSITION",
				"SIRASGN_ACTIVITY_DATE",
				"SIRASGN_FCNT_CODE",
				"SIRASGN_POSN",
				"SIRASGN_SUFF",
				"SIRASGN_ASTY_CODE",
				"SIRASGN_DATA_ORIGIN",
				"SIRASGN_USER_ID",
			));
		$select = $source_db->query("SATURN.SIRASGN");
		$select->convertDate("SIRASGN_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated SIRASGN\n";

		// SATURN.SSBDESC
		print "Updating SSBDESC\t";
		$target_db->truncate("SSBDESC");
		$insert = $target_db->prepareInsert("SSBDESC", array(
				"SSBDESC_TERM_CODE",
				"SSBDESC_CRN",
				"SSBDESC_TEXT_NARRATIVE",
				"SSBDESC_ACTIVITY_DATE",
				"SSBDESC_USER_ID",
			));
		$select = $source_db->query("SATURN.SSBDESC", array(
				"SSBDESC_TERM_CODE",
				"SSBDESC_CRN",
				"SSBDESC_TEXT_NARRATIVE",
				"SSBDESC_ACTIVITY_DATE",
				"SSBDESC_USER_ID",
			));
		$select->convertDate("SSBDESC_ACTIVITY_DATE");
		$select->convertText("SSBDESC_TEXT_NARRATIVE");
		$insert->insertAll($select);
		print "...\tUpdated SSBDESC\n";

		// SATURN.SSBSECT
		print "Updating SSBSECT\t";
		$target_db->truncate("SSBSECT");
		$insert = $target_db->prepareInsert("SSBSECT", array(
				"SSBSECT_TERM_CODE",
				"SSBSECT_CRN",
				"SSBSECT_PTRM_CODE",
				"SSBSECT_SUBJ_CODE",
				"SSBSECT_CRSE_NUMB",
				"SSBSECT_SEQ_NUMB",
				"SSBSECT_SSTS_CODE",
				"SSBSECT_SCHD_CODE",
				"SSBSECT_CAMP_CODE",
				"SSBSECT_CRSE_TITLE",
				"SSBSECT_CREDIT_HRS",
				"SSBSECT_BILL_HRS",
				"SSBSECT_GMOD_CODE",
				"SSBSECT_SAPR_CODE",
				"SSBSECT_SESS_CODE",
				"SSBSECT_LINK_IDENT",
				"SSBSECT_PRNT_IND",
				"SSBSECT_GRADABLE_IND",
				"SSBSECT_TUIW_IND",
				"SSBSECT_REG_ONEUP",
				"SSBSECT_PRIOR_ENRL",
				"SSBSECT_PROJ_ENRL",
				"SSBSECT_MAX_ENRL",
				"SSBSECT_ENRL",
				"SSBSECT_SEATS_AVAIL",
				"SSBSECT_TOT_CREDIT_HRS",
				"SSBSECT_CENSUS_ENRL",
				"SSBSECT_CENSUS_ENRL_DATE",
				"SSBSECT_ACTIVITY_DATE",
				"SSBSECT_PTRM_START_DATE",
				"SSBSECT_PTRM_END_DATE",
				"SSBSECT_PTRM_WEEKS",
				"SSBSECT_RESERVED_IND",
				"SSBSECT_WAIT_CAPACITY",
				"SSBSECT_WAIT_COUNT",
				"SSBSECT_WAIT_AVAIL",
				"SSBSECT_LEC_HR",
				"SSBSECT_LAB_HR",
				"SSBSECT_OTH_HR",
				"SSBSECT_CONT_HR",
				"SSBSECT_ACCT_CODE",
				"SSBSECT_ACCL_CODE",
				"SSBSECT_CENSUS_2_DATE",
				"SSBSECT_ENRL_CUT_OFF_DATE",
				"SSBSECT_ACAD_CUT_OFF_DATE",
				"SSBSECT_DROP_CUT_OFF_DATE",
				"SSBSECT_CENSUS_2_ENRL",
				"SSBSECT_VOICE_AVAIL",
				"SSBSECT_CAPP_PREREQ_TEST_IND",
				"SSBSECT_GSCH_NAME",
				"SSBSECT_BEST_OF_COMP",
				"SSBSECT_SUBSET_OF_COMP",
				"SSBSECT_INSM_CODE",
				"SSBSECT_REG_FROM_DATE",
				"SSBSECT_REG_TO_DATE",
				"SSBSECT_LEARNER_REGSTART_FDATE",
				"SSBSECT_LEARNER_REGSTART_TDATE",
				"SSBSECT_DUNT_CODE",
				"SSBSECT_NUMBER_OF_UNITS",
				"SSBSECT_NUMBER_OF_EXTENSIONS",
				"SSBSECT_DATA_ORIGIN",
				"SSBSECT_USER_ID",
				"SSBSECT_INTG_CDE",
			));
		$select = $source_db->query("SATURN.SSBSECT");
		$select->convertDate("SSBSECT_CENSUS_ENRL_DATE");
		$select->convertDate("SSBSECT_ACTIVITY_DATE");
		$select->convertDate("SSBSECT_PTRM_START_DATE");
		$select->convertDate("SSBSECT_PTRM_END_DATE");
		$select->convertDate("SSBSECT_CENSUS_2_DATE");
		$select->convertDate("SSBSECT_ENRL_CUT_OFF_DATE");
		$select->convertDate("SSBSECT_ACAD_CUT_OFF_DATE");
		$select->convertDate("SSBSECT_DROP_CUT_OFF_DATE");
		$select->convertDate("SSBSECT_REG_FROM_DATE");
		$select->convertDate("SSBSECT_REG_TO_DATE");
		$select->convertDate("SSBSECT_LEARNER_REGSTART_FDATE");
		$select->convertDate("SSBSECT_LEARNER_REGSTART_TDATE");
		$insert->insertAll($select);
		print "...\tUpdated SSBSECT\n";

		// SATURN.SSRATTR
		print "Updating SSRATTR\t";
		$target_db->truncate("SSRATTR");
		$insert = $target_db->prepareInsert("SSRATTR", array(
				"SSRATTR_TERM_CODE",
				"SSRATTR_CRN",
				"SSRATTR_ATTR_CODE",
				"SSRATTR_ACTIVITY_DATE",
			));
		$select = $source_db->query("SATURN.SSRATTR");
		$select->convertDate("SSRATTR_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated SSRATTR\n";

		// SATURN.SSRBLCK
		print "Updating SSRBLCK\t";
		$target_db->truncate("SSRBLCK");
		$insert = $target_db->prepareInsert("SSRBLCK", array(
				"SSRBLCK_TERM_CODE",
				"SSRBLCK_BLCK_CODE",
				"SSRBLCK_CRN",
				"SSRBLCK_CREDIT_HRS",
				"SSRBLCK_BILL_HRS",
				"SSRBLCK_GMOD_CODE",
				"SSRBLCK_APPR_IND",
				"SSRBLCK_ACTIVITY_DATE",
			));
		// Select only particular Block Codes if we are configured to do so.
		if (count($this->allowedBlckCodes)) {
			$codes = array();
			foreach ($this->allowedBlckCodes as $code) {
				$codes[] = "'".$code."'";
			}
			$where = "WHERE SSRBLCK_BLCK_CODE IN (".implode(', ', $codes).")";
		} else {
			$where = '';
		}
		$select = $source_db->query("SATURN.SSRBLCK", array(), $where);
		$select->convertDate("SSRBLCK_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated SSRBLCK\n";

		// SATURN.SSRMEET
		print "Updating SSRMEET\t";
		$target_db->truncate("SSRMEET");
		$insert = $target_db->prepareInsert("SSRMEET", array(
				"SSRMEET_TERM_CODE",
				"SSRMEET_CRN",
				"SSRMEET_DAYS_CODE",
				"SSRMEET_DAY_NUMBER",
				"SSRMEET_BEGIN_TIME",
				"SSRMEET_END_TIME",
				"SSRMEET_BLDG_CODE",
				"SSRMEET_ROOM_CODE",
				"SSRMEET_ACTIVITY_DATE",
				"SSRMEET_START_DATE",
				"SSRMEET_END_DATE",
				"SSRMEET_CATAGORY",
				"SSRMEET_SUN_DAY",
				"SSRMEET_MON_DAY",
				"SSRMEET_TUE_DAY",
				"SSRMEET_WED_DAY",
				"SSRMEET_THU_DAY",
				"SSRMEET_FRI_DAY",
				"SSRMEET_SAT_DAY",
				"SSRMEET_SCHD_CODE",
				"SSRMEET_OVER_RIDE",
				"SSRMEET_CREDIT_HR_SESS",
				"SSRMEET_MEET_NO",
				"SSRMEET_HRS_WEEK",
				"SSRMEET_FUNC_CODE",
				"SSRMEET_COMT_CODE",
				"SSRMEET_SCHS_CODE",
				"SSRMEET_MTYP_CODE",
				"SSRMEET_DATA_ORIGIN",
				"SSRMEET_USER_ID",
			));
		$select = $source_db->query("SATURN.SSRMEET");
		$select->convertDate("SSRMEET_ACTIVITY_DATE");
		$select->convertDate("SSRMEET_START_DATE");
		$select->convertDate("SSRMEET_END_DATE");
		$insert->insertAll($select);
		print "...\tUpdated SSRMEET\n";

		// SATURN.STVACYR
		print "Updating STVACYR\t";
		$target_db->truncate("STVACYR");
		$insert = $target_db->prepareInsert("STVACYR", array(
				"STVACYR_CODE",
				"STVACYR_DESC",
				"STVACYR_ACTIVITY_DATE",
				"STVACYR_SYSREQ_IND",
			));
		$select = $source_db->query("SATURN.STVACYR");
		$select->convertDate("STVACYR_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated STVACYR\n";

		// SATURN.STVAPRV
		print "Updating STVAPRV\t";
		$target_db->truncate("STVAPRV");
		$insert = $target_db->prepareInsert("STVAPRV", array(
				"STVAPRV_CODE",
				"STVAPRV_DESC",
				"STVAPRV_ACTIVITY_DATE",
			));
		$select = $source_db->query("SATURN.STVAPRV");
		$select->convertDate("STVAPRV_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated STVAPRV\n";

		// SATURN.STVASTY
		print "Updating STVASTY\t";
		$target_db->truncate("STVASTY");
		$insert = $target_db->prepareInsert("STVASTY", array(
				"STVASTY_CODE",
				"STVASTY_DESC",
				"STVASTY_ACTIVITY_DATE",
			));
		$select = $source_db->query("SATURN.STVASTY");
		$select->convertDate("STVASTY_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated STVASTY\n";

		// SATURN.STVATTR
		print "Updating STVATTR\t";
		$target_db->truncate("STVATTR");
		$insert = $target_db->prepareInsert("STVATTR", array(
				"STVATTR_CODE",
				"STVATTR_DESC",
				"STVATTR_ACTIVITY_DATE",
			));
		$select = $source_db->query("SATURN.STVATTR");
		$select->convertDate("STVATTR_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated STVATTR\n";

		// SATURN.STVBLCK
		print "Updating STVBLCK\t";
		$target_db->truncate("STVBLCK");
		$insert = $target_db->prepareInsert("STVBLCK", array(
				"STVBLCK_CODE",
				"STVBLCK_DESC",
				"STVBLCK_ACTIVITY_DATE",
			));
		$select = $source_db->query("SATURN.STVBLCK");
		$select->convertDate("STVBLCK_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated STVBLCK\n";

		// SATURN.STVBLDG
		print "Updating STVBLDG\t";
		$target_db->truncate("STVBLDG");
		$insert = $target_db->prepareInsert("STVBLDG", array(
				"STVBLDG_CODE",
				"STVBLDG_DESC",
				"STVBLDG_ACTIVITY_DATE",
				"STVBLDG_VR_MSG_NO",
			));
		$select = $source_db->query("SATURN.STVBLDG");
		$select->convertDate("STVBLDG_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated STVBLDG\n";

		// SATURN.STVCAMP
		print "Updating STVCAMP\t";
		$target_db->truncate("STVCAMP");
		$insert = $target_db->prepareInsert("STVCAMP", array(
				"STVCAMP_CODE",
				"STVCAMP_DESC",
				"STVCAMP_ACTIVITY_DATE",
				"STVCAMP_DICD_CODE",
			));
		$select = $source_db->query("SATURN.STVCAMP");
		$select->convertDate("STVCAMP_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated STVCAMP\n";

		// SATURN.STVCIPC
		print "Updating STVCIPC\t";
		$target_db->truncate("STVCIPC");
		$insert = $target_db->prepareInsert("STVCIPC", array(
				"STVCIPC_CODE",
				"STVCIPC_DESC",
				"STVCIPC_ACTIVITY_DATE",
				"STVCIPC_CIPC_A_IND",
				"STVCIPC_CIPC_B_IND",
				"STVCIPC_CIPC_C_IND",
				"STVCIPC_SP04_PROGRAM_CDE",
			));
		$select = $source_db->query("SATURN.STVCIPC");
		$select->convertDate("STVCIPC_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated STVCIPC\n";

		// SATURN.STVCOLL
		print "Updating STVCOLL\t";
		$target_db->truncate("STVCOLL");
		$insert = $target_db->prepareInsert("STVCOLL", array(
				"STVCOLL_CODE",
				"STVCOLL_DESC",
				"STVCOLL_ADDR_STREET_LINE1",
				"STVCOLL_ADDR_STREET_LINE2",
				"STVCOLL_ADDR_STREET_LINE3",
				"STVCOLL_ADDR_CITY",
				"STVCOLL_ADDR_STATE",
				"STVCOLL_ADDR_COUNTRY",
				"STVCOLL_ADDR_ZIP_CODE",
				"STVCOLL_ACTIVITY_DATE",
				"STVCOLL_SYSTEM_REQ_IND",
				"STVCOLL_VR_MSG_NO",
				"STVCOLL_STATSCAN_CDE3",
				"STVCOLL_DICD_CODE",
			));
		$select = $source_db->query("SATURN.STVCOLL");
		$select->convertDate("STVCOLL_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated STVCOLL\n";

		// SATURN.STVCOMT
		print "Updating STVCOMT\t";
		$target_db->truncate("STVCOMT");
		$insert = $target_db->prepareInsert("STVCOMT", array(
				"STVCOMT_CODE",
				"STVCOMT_DESC",
				"STVCOMT_TRANS_PRINT",
				"STVCOMT_ACTIVITY_DATE",
			));
		$select = $source_db->query("SATURN.STVCOMT");
		$select->convertDate("STVCOMT_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated STVCOMT\n";

		// SATURN.STVCSTA
		print "Updating STVCSTA\t";
		$target_db->truncate("STVCSTA");
		$insert = $target_db->prepareInsert("STVCSTA", array(
				"STVCSTA_CODE",
				"STVCSTA_DESC",
				"STVCSTA_ACTIVITY_DATE",
				"STVCSTA_ACTIVE_IND",
			));
		$select = $source_db->query("SATURN.STVCSTA");
		$select->convertDate("STVCSTA_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated STVCSTA\n";

		// SATURN.STVDEPT
		print "Updating STVDEPT\t";
		$target_db->truncate("STVDEPT");
		$insert = $target_db->prepareInsert("STVDEPT", array(
				"STVDEPT_CODE",
				"STVDEPT_DESC",
				"STVDEPT_ACTIVITY_DATE",
				"STVDEPT_SYSTEM_REQ_IND",
				"STVDEPT_VR_MSG_NO",
			));
		$select = $source_db->query("SATURN.STVDEPT");
		$select->convertDate("STVDEPT_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated STVDEPT\n";

		// SATURN.STVDIVS
		print "Updating STVDIVS\t";
		$target_db->truncate("STVDIVS");
		$insert = $target_db->prepareInsert("STVDIVS", array(
				"STVDIVS_CODE",
				"STVDIVS_DESC",
				"STVDIVS_ACTIVITY_DATE",
			));
		$select = $source_db->query("SATURN.STVDIVS");
		$select->convertDate("STVDIVS_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated STVDIVS\n";

		// SATURN.STVFCNT
		print "Updating STVFCNT\t";
		$target_db->truncate("STVFCNT");
		$insert = $target_db->prepareInsert("STVFCNT", array(
				"STVFCNT_CODE",
				"STVFCNT_DESC",
				"STVFCNT_ACTIVITY_DATE",
			));
		$select = $source_db->query("SATURN.STVFCNT");
		$select->convertDate("STVFCNT_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated STVFCNT\n";

		// SATURN.STVLEVL
		print "Updating STVLEVL\t";
		$target_db->truncate("STVLEVL");
		$insert = $target_db->prepareInsert("STVLEVL", array(
				"STVLEVL_CODE",
				"STVLEVL_DESC",
				"STVLEVL_ACTIVITY_DATE",
				"STVLEVL_ACAD_IND",
				"STVLEVL_CEU_IND",
				"STVLEVL_SYSTEM_REQ_IND",
				"STVLEVL_VR_MSG_NO",
				"STVLEVL_EDI_EQUIV",
			));
		$select = $source_db->query("SATURN.STVLEVL");
		$select->convertDate("STVLEVL_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated STVLEVL\n";

		// SATURN.STVMEET
		print "Updating STVMEET\t";
		$target_db->truncate("STVMEET");
		$insert = $target_db->prepareInsert("STVMEET", array(
				"STVMEET_CODE",
				"STVMEET_MON_DAY",
				"STVMEET_TUE_DAY",
				"STVMEET_WED_DAY",
				"STVMEET_THU_DAY",
				"STVMEET_FRI_DAY",
				"STVMEET_SAT_DAY",
				"STVMEET_SUN_DAY",
				"STVMEET_BEGIN_TIME",
				"STVMEET_END_TIME",
				"STVMEET_ACTIVITY_DATE",
			));
		$select = $source_db->query("SATURN.STVMEET");
		$select->convertDate("STVMEET_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated STVMEET\n";

		// SATURN.STVPWAV
		print "Updating STVPWAV\t";
		$target_db->truncate("STVPWAV");
		$insert = $target_db->prepareInsert("STVPWAV", array(
				"STVPWAV_CODE",
				"STVPWAV_DESC",
				"STVPWAV_ACTIVITY_DATE",
			));
		$select = $source_db->query("SATURN.STVPWAV");
		$select->convertDate("STVPWAV_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated STVPWAV\n";

		// SATURN.STVREPS
		print "Updating STVREPS\t";
		$target_db->truncate("STVREPS");
		$insert = $target_db->prepareInsert("STVREPS", array(
				"STVREPS_CODE",
				"STVREPS_DESC",
				"STVREPS_ACTIVITY_DATE",
			));
		$select = $source_db->query("SATURN.STVREPS");
		$select->convertDate("STVREPS_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated STVREPS\n";

		// SATURN.STVSCHD
		print "Updating STVSCHD\t";
		$target_db->truncate("STVSCHD");
		$insert = $target_db->prepareInsert("STVSCHD", array(
				"STVSCHD_CODE",
				"STVSCHD_DESC",
				"STVSCHD_ACTIVITY_DATE",
				"STVSCHD_INSTRUCT_METHOD",
				"STVSCHD_COOP_IND",
				"STVSCHD_AUTO_SCHEDULER_IND",
				"STVSCHD_INSM_CODE",
				"STVSCHD_VR_MSG_NO",
			));
		$select = $source_db->query("SATURN.STVSCHD");
		$select->convertDate("STVSCHD_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated STVSCHD\n";

		// SATURN.STVSUBJ
		print "Updating STVSUBJ\t";
		$target_db->truncate("STVSUBJ");
		$insert = $target_db->prepareInsert("STVSUBJ", array(
				"STVSUBJ_CODE",
				"STVSUBJ_DESC",
				"STVSUBJ_ACTIVITY_DATE",
				"STVSUBJ_VR_MSG_NO",
				"STVSUBJ_DISP_WEB_IND",
			));
		$select = $source_db->query("SATURN.STVSUBJ");
		$select->convertDate("STVSUBJ_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated STVSUBJ\n";

		// SATURN.STVTERM
		print "Updating STVTERM\t";
		$target_db->truncate("STVTERM");
		$insert = $target_db->prepareInsert("STVTERM", array(
				"STVTERM_CODE",
				"STVTERM_DESC",
				"STVTERM_START_DATE",
				"STVTERM_END_DATE",
				"STVTERM_FA_PROC_YR",
				"STVTERM_ACTIVITY_DATE",
				"STVTERM_FA_TERM",
				"STVTERM_FA_PERIOD",
				"STVTERM_FA_END_PERIOD",
				"STVTERM_ACYR_CODE",
				"STVTERM_HOUSING_START_DATE",
				"STVTERM_HOUSING_END_DATE",
				"STVTERM_SYSTEM_REQ_IND",
				"STVTERM_TRMT_CODE",
			));
		$select = $source_db->query("SATURN.STVTERM");
		$select->convertDate("STVTERM_START_DATE");
		$select->convertDate("STVTERM_END_DATE");
		$select->convertDate("STVTERM_ACTIVITY_DATE");
		$select->convertDate("STVTERM_HOUSING_START_DATE");
		$select->convertDate("STVTERM_HOUSING_END_DATE");
		$insert->insertAll($select);
		print "...\tUpdated STVTERM\n";

		// SATURN.STVTRMT
		print "Updating STVTRMT\t";
		$target_db->truncate("STVTRMT");
		$insert = $target_db->prepareInsert("STVTRMT", array(
				"STVTRMT_CODE",
				"STVTRMT_DESC",
				"STVTRMT_ACTIVITY_DATE",
			));
		$select = $source_db->query("SATURN.STVTRMT");
		$select->convertDate("STVTRMT_ACTIVITY_DATE");
		$insert->insertAll($select);
		print "...\tUpdated STVTRMT\n";

		// SATURN_MIDD.SYVINST
		print "Updating SYVINST\t";
		$target_db->truncate("SYVINST");
		$insert = $target_db->prepareInsert("SYVINST", array(
				"SYVINST_TERM_CODE",
				"SYVINST_CRN",
				"SYVINST_PIDM",
				"SYVINST_LAST_NAME",
				"SYVINST_FIRST_NAME",
				"WEB_ID",
			));
		$select = $source_db->query("SATURN_MIDD.SYVINST");
		$select->convertBin2Hex("WEB_ID");
		$insert->insertAll($select);
		print "...\tUpdated SYVINST\n";

		$target_db->commit();
	}

}
