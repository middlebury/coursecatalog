<?php
/**
 * @since 2/22/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Service\CatalogSync\Syncer;

use App\Service\CatalogSync\Database\Destination\PdoDestinationDatabase;
use App\Service\CatalogSync\Database\DestinationDatabase;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This class implements the Banner-to-Catalog sync using the Banner OCI connection
 * on the source side and a MySQL-PDO connection on the temporary cache side,
 * and mysql_dump to copy from the cache to the destination.
 *
 * @since 2/22/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
abstract class AbstractSyncer
{
    protected $nonFatalErrors = [];
    protected $output;

    public function __construct(
        protected PdoDestinationDatabase $destination_db,
        protected array $allowedBlckCodes = [],
    ) {
        // Configure our block codes to import.
        if (!empty($allowedBlckCodes)) {
            foreach ($allowedBlckCodes as $code) {
                if (!is_string($code)) {
                    throw new \InvalidArgumentException('allowedBlckCodes[] must be an array of strings in the config.');
                }
            }
        }
    }

    /**
     * Set the Output iterface to write status lines to.
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output
     */
    public function setOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }

    /**
     * Roll back any changes to the destination.
     */
    public function rollback(): void
    {
        try {
            while ($this->destination_db->rollBack()) {
                // Keep rolling back all nested transactions.
            }
        } catch (\PDOException $e) {
            // We will get a PDOException after the last transaction is rolled back.
            // We can now just move on.
        }
    }

    /**
     * Set up connections to our source and destination.
     */
    public function connect(): void
    {
        $this->destination_db->connect();
    }

    /**
     * Update derived data in the destination database.
     */
    public function updateDerived(): void
    {
        $pdo = $this->destination_db->getPdo();

        // Build derived table for easier term-catalog lookups
        $this->output->write("Updating derived tables\t");
        $pdo->beginTransaction();
        $ttermcat = $pdo->prepare('DELETE FROM catalog_term');
        $ttermcat->execute();

        $searches = $pdo->query('SELECT * FROM catalog_term_match')->fetchAll();

        $itermcat = $pdo->prepare('
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
			');

        foreach ($searches as $search) {
            $itermcat->execute([
                ':catalog_id' => $search['catalog_id'],
                ':term_code_match' => $search['term_code_match'],
                ':term_display_label' => $search['term_display_label'],
            ]);
        }

        $this->output->write("...\tUpdated derived table: catalog_term\n");

        // Delete terms that have no sections in them.
        $this->output->write("Removing empty terms\t");

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
				coll.catalog_id, SSBSECT_TERM_CODE AS term_code, COUNT(SSBSECT_CRN) AS num_sections
			FROM
				course_catalog_college coll
				INNER JOIN course_catalog cat ON coll.catalog_id = cat.catalog_id
				LEFT JOIN temp_scbcrse_recent ON (coll_code = SCBCRSE_COLL_CODE)
				LEFT JOIN SSBSECT c ON (SCBCRSE_SUBJ_CODE = SSBSECT_SUBJ_CODE AND SCBCRSE_CRSE_NUMB = SSBSECT_CRSE_NUMB)
			WHERE
				coll_code IS NOT NULL
				AND SSBSECT_SSTS_CODE = 'A'
				AND (cat.prnt_ind_to_exclude IS NULL OR SSBSECT_PRNT_IND != cat.prnt_ind_to_exclude)
			GROUP BY coll.catalog_id, coll_code, SSBSECT_TERM_CODE
			");
        $empty_term_results = $pdo->query(
            'SELECT
				t.catalog_id,
				t.term_code
			FROM
				catalog_term t
				LEFT JOIN temp_section_catalog s ON (t.catalog_id = s.catalog_id AND t.term_code = s.term_code)
			WHERE
				s.num_sections IS NULL
			');
        $delete = $pdo->prepare(
            'DELETE FROM catalog_term
			WHERE
				catalog_id = ?
				AND term_code = ?');
        foreach ($empty_term_results->fetchAll(\PDO::FETCH_OBJ) as $term) {
            $delete->execute([$term->catalog_id, $term->term_code]);
        }
        $pdo->query('DROP TEMPORARY TABLE temp_scbcrse_recent');
        $pdo->query('DROP TEMPORARY TABLE temp_section_catalog');
        $this->output->write("...\tRemoved empty terms from derived table: catalog_term\n");

        // Delete terms that are manually inactivated.
        $this->output->write("Removing deactivated terms\t");
        $deactivated_term_results = $pdo->query(
            'SELECT
				*
			FROM
				catalog_term_inactive
			');
        foreach ($deactivated_term_results->fetchAll(\PDO::FETCH_OBJ) as $term) {
            $delete->execute([$term->catalog_id, $term->term_code]);
        }
        $this->output->write("...\tRemoved deactivated terms from derived table: catalog_term\n");

        $pdo->commit();

        // Rebuild our "materialized views"
        $this->output->write("Updating materialized views\t");
        \harmoni_SQLUtils::runSQLfile(__DIR__.'/../../../../application/library/banner/sql/create_views.sql', $pdo);
        $this->output->write("...\tUpdated materialized views\n");

        // Rebuild our indices now that tables are populated.
        $this->output->write("Rebuilding indices and optimizing tables\t");
        \harmoni_SQLUtils::runSQLfile(__DIR__.'/../../../../application/library/banner/sql/rebuild_indices.sql', $pdo);
        $this->output->write("...\tRebuilt indices and optimed tables\n");
    }

    /**
     * Disconnect from our databases.
     */
    public function disconnect(): void
    {
        $this->destination_db->disconnect();
    }

    /**
     * Answer the database we should copy into during copy.
     *
     * @return App\Service\CatalogSync\Database\DestinationDatabase
     */
    protected function getCopyTargetDatabase(): DestinationDatabase
    {
        return $this->destination_db;
    }

    /**
     * Take actions before copying data.
     */
    public function preCopy(): void
    {
        $this->validateSource();
        // Override if needed.
    }

    /**
     * Take actions after copying data.
     */
    public function postCopy(): void
    {
        // Override if needed.
    }

    /**
     * Validate that the source database has data.
     */
    protected function validateSource(): void
    {
        $source_db = $this->getCopySourceDatabase();

        // Verify that we have data in several key tables.
        if ($source_db->count('SSBSECT') < 1) {
            throw new \Exception('Source validation failed, SSBSECT has no rows.');
        }
        if ($source_db->count('SCBCRSE') < 1) {
            throw new \Exception('Source validation failed, SCBCRSE has no rows.');
        }
        if ($source_db->count('STVTERM') < 1) {
            throw new \Exception('Source validation failed, STVTERM has no rows.');
        }
    }

    /**
     * Copy data.
     */
    public function copy(): void
    {
        $source_db = $this->getCopySourceDatabase();
        $target_db = $this->getCopyTargetDatabase();
        $target_db->beginTransaction();

        // GENERAL.GORINTG
        $this->output->write("Updating GORINTG\t");
        $target_db->truncate('GORINTG');
        $insert = $target_db->prepareInsert('GORINTG', [
            'GORINTG_INTEGRATION_CDE',
            'GORINTG_DESC',
            'GORINTG_INTP_CODE',
            'GORINTG_USER_ID',
            'GORINTG_ACTIVITY_DATE',
            'GORINTG_DATA_ORIGIN',
        ]);
        $select = $source_db->query('GENERAL.GORINTG');
        $select->convertDate('GORINTG_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated GORINTG\n");

        // GENERAL.GTVDUNT
        $this->output->write("Updating GTVDUNT\t");
        $target_db->truncate('GTVDUNT');
        $insert = $target_db->prepareInsert('GTVDUNT', [
            'GTVDUNT_CODE',
            'GTVDUNT_DESC',
            'GTVDUNT_NUMBER_OF_DAYS',
            'GTVDUNT_ACTIVITY_DATE',
            'GTVDUNT_USER_ID',
            'GTVDUNT_VR_MSG_NO',
        ]);
        $select = $source_db->query('GENERAL.GTVDUNT');
        $select->convertDate('GTVDUNT_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated GTVDUNT\n");

        // GENERAL.GTVINSM
        $this->output->write("Updating GTVINSM\t");
        $target_db->truncate('GTVINSM');
        $insert = $target_db->prepareInsert('GTVINSM', [
            'GTVINSM_CODE',
            'GTVINSM_DESC',
            'GTVINSM_ACTIVITY_DATE',
            'GTVINSM_USER_ID',
            'GTVINSM_VR_MSG_NO',
        ]);
        $select = $source_db->query('GENERAL.GTVINSM');
        $select->convertDate('GTVINSM_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated GTVINSM\n");

        // GENERAL.GTVINTP
        $this->output->write("Updating GTVINTP\t");
        $target_db->truncate('GTVINTP');
        $insert = $target_db->prepareInsert('GTVINTP', [
            'GTVINTP_CODE',
            'GTVINTP_DESC',
            'GTVINTP_USER_ID',
            'GTVINTP_ACTIVITY_DATE',
            'GTVINTP_DATA_ORIGIN',
        ]);
        $select = $source_db->query('GENERAL.GTVINTP');
        $select->convertDate('GTVINTP_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated GTVINTP\n");

        // GENERAL.GTVMTYP
        $this->output->write("Updating GTVMTYP\t");
        $target_db->truncate('GTVMTYP');
        $insert = $target_db->prepareInsert('GTVMTYP', [
            'GTVMTYP_CODE',
            'GTVMTYP_DESC',
            'GTVMTYP_SYS_REQ_IND',
            'GTVMTYP_ACTIVITY_DATE',
            'GTVMTYP_USER_ID',
            'GTVMTYP_VR_MSG_NO',
        ]);
        $select = $source_db->query('GENERAL.GTVMTYP');
        $select->convertDate('GTVMTYP_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated GTVMTYP\n");

        // GENERAL.GTVSCHS
        $this->output->write("Updating GTVSCHS\t");
        $target_db->truncate('GTVSCHS');
        $insert = $target_db->prepareInsert('GTVSCHS', [
            'GTVSCHS_CODE',
            'GTVSCHS_DESC',
            'GTVSCHS_SYSTEM_REQ_IND',
            'GTVSCHS_ACTIVITY_DATE',
        ]);
        $select = $source_db->query('GENERAL.GTVSCHS');
        $select->convertDate('GTVSCHS_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated GTVSCHS\n");

        // SATURN.SCBCRSE
        $this->output->write("Updating SCBCRSE\t");
        $target_db->truncate('SCBCRSE');
        $insert = $target_db->prepareInsert('SCBCRSE', [
            'SCBCRSE_SUBJ_CODE',
            'SCBCRSE_CRSE_NUMB',
            'SCBCRSE_EFF_TERM',
            'SCBCRSE_COLL_CODE',
            'SCBCRSE_DIVS_CODE',
            'SCBCRSE_DEPT_CODE',
            'SCBCRSE_CSTA_CODE',
            'SCBCRSE_TITLE',
            'SCBCRSE_CIPC_CODE',
            'SCBCRSE_CREDIT_HR_IND',
            'SCBCRSE_CREDIT_HR_LOW',
            'SCBCRSE_CREDIT_HR_HIGH',
            'SCBCRSE_LEC_HR_IND',
            'SCBCRSE_LEC_HR_LOW',
            'SCBCRSE_LEC_HR_HIGH',
            'SCBCRSE_LAB_HR_IND',
            'SCBCRSE_LAB_HR_LOW',
            'SCBCRSE_LAB_HR_HIGH',
            'SCBCRSE_OTH_HR_IND',
            'SCBCRSE_OTH_HR_LOW',
            'SCBCRSE_OTH_HR_HIGH',
            'SCBCRSE_BILL_HR_IND',
            'SCBCRSE_BILL_HR_LOW',
            'SCBCRSE_BILL_HR_HIGH',
            'SCBCRSE_APRV_CODE',
            'SCBCRSE_REPEAT_LIMIT',
            'SCBCRSE_PWAV_CODE',
            'SCBCRSE_TUIW_IND',
            'SCBCRSE_ADD_FEES_IND',
            'SCBCRSE_ACTIVITY_DATE',
            'SCBCRSE_CONT_HR_LOW',
            'SCBCRSE_CONT_HR_IND',
            'SCBCRSE_CONT_HR_HIGH',
            'SCBCRSE_CEU_IND',
            'SCBCRSE_REPS_CODE',
            'SCBCRSE_MAX_RPT_UNITS',
            'SCBCRSE_CAPP_PREREQ_TEST_IND',
            'SCBCRSE_DUNT_CODE',
            'SCBCRSE_NUMBER_OF_UNITS',
            'SCBCRSE_DATA_ORIGIN',
            'SCBCRSE_USER_ID',
        ]);
        $select = $source_db->query('SATURN.SCBCRSE');
        $select->convertDate('SCBCRSE_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated SCBCRSE\n");

        // SATURN.SCBDESC
        $this->output->write("Updating SCBDESC\t");
        $target_db->truncate('SCBDESC');
        $insert = $target_db->prepareInsert('SCBDESC', [
            'SCBDESC_SUBJ_CODE',
            'SCBDESC_CRSE_NUMB',
            'SCBDESC_TERM_CODE_EFF',
            'SCBDESC_ACTIVITY_DATE',
            'SCBDESC_USER_ID',
            'SCBDESC_TEXT_NARRATIVE',
            'SCBDESC_TERM_CODE_END',
        ]);
        $select = $source_db->query('SATURN.SCBDESC', [
            'SCBDESC_SUBJ_CODE',
            'SCBDESC_CRSE_NUMB',
            'SCBDESC_TERM_CODE_EFF',
            'SCBDESC_ACTIVITY_DATE',
            'SCBDESC_USER_ID',
            'SCBDESC_TEXT_NARRATIVE',
            'SCBDESC_TERM_CODE_END',
        ]);
        $select->convertDate('SCBDESC_ACTIVITY_DATE');
        $select->convertText('SCBDESC_TEXT_NARRATIVE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated SCBDESC\n");

        // SATURN.SCRATTR
        $this->output->write("Updating SCRATTR\t");
        $target_db->truncate('SCRATTR');
        $insert = $target_db->prepareInsert('SCRATTR', [
            'SCRATTR_SUBJ_CODE',
            'SCRATTR_CRSE_NUMB',
            'SCRATTR_EFF_TERM',
            'SCRATTR_ATTR_CODE',
            'SCRATTR_ACTIVITY_DATE',
        ]);
        $select = $source_db->query('SATURN.SCRATTR', [
            'SCRATTR_SUBJ_CODE',
            'SCRATTR_CRSE_NUMB',
            'SCRATTR_EFF_TERM',
            'SCRATTR_ATTR_CODE',
            'SCRATTR_ACTIVITY_DATE',
        ]);
        $select->convertDate('SCRATTR_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated SCRATTR\n");

        // SATURN.SCREQIV
        $this->output->write("Updating SCREQIV\t");
        $target_db->truncate('SCREQIV');
        $insert = $target_db->prepareInsert('SCREQIV', [
            'SCREQIV_SUBJ_CODE',
            'SCREQIV_CRSE_NUMB',
            'SCREQIV_EFF_TERM',
            'SCREQIV_SUBJ_CODE_EQIV',
            'SCREQIV_CRSE_NUMB_EQIV',
            'SCREQIV_START_TERM',
            'SCREQIV_END_TERM',
            'SCREQIV_ACTIVITY_DATE',
        ]);
        $select = $source_db->query('SATURN.SCREQIV', [
            'SCREQIV_SUBJ_CODE',
            'SCREQIV_CRSE_NUMB',
            'SCREQIV_EFF_TERM',
            'SCREQIV_SUBJ_CODE_EQIV',
            'SCREQIV_CRSE_NUMB_EQIV',
            'SCREQIV_START_TERM',
            'SCREQIV_END_TERM',
            'SCREQIV_ACTIVITY_DATE',
        ]);
        $select->convertDate('SCREQIV_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated SCREQIV\n");

        // SATURN.SCRLEVL
        $this->output->write("Updating SCRLEVL\t");
        $target_db->truncate('SCRLEVL');
        $insert = $target_db->prepareInsert('SCRLEVL', [
            'SCRLEVL_SUBJ_CODE',
            'SCRLEVL_CRSE_NUMB',
            'SCRLEVL_EFF_TERM',
            'SCRLEVL_LEVL_CODE',
            'SCRLEVL_ACTIVITY_DATE',
        ]);
        $select = $source_db->query('SATURN.SCRLEVL', [
            'SCRLEVL_SUBJ_CODE',
            'SCRLEVL_CRSE_NUMB',
            'SCRLEVL_EFF_TERM',
            'SCRLEVL_LEVL_CODE',
            'SCRLEVL_ACTIVITY_DATE',
        ]);
        $select->convertDate('SCRLEVL_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated SCRLEVL\n");

        // SATURN.SOBPTRM
        $this->output->write("Updating SOBPTRM\t");
        $target_db->truncate('SOBPTRM');
        $insert = $target_db->prepareInsert('SOBPTRM', [
            'SOBPTRM_TERM_CODE',
            'SOBPTRM_PTRM_CODE',
            'SOBPTRM_DESC',
            'SOBPTRM_START_DATE',
            'SOBPTRM_END_DATE',
            'SOBPTRM_REG_ALLOWED',
            'SOBPTRM_WEEKS',
            'SOBPTRM_CENSUS_DATE',
            'SOBPTRM_ACTIVITY_DATE',
            'SOBPTRM_SECT_OVER_IND',
            'SOBPTRM_CENSUS_2_DATE',
            'SOBPTRM_MGRD_WEB_UPD_IND',
            'SOBPTRM_FGRD_WEB_UPD_IND',
            'SOBPTRM_WAITLST_WEB_DISP_IND',
            'SOBPTRM_INCOMPLETE_EXT_DATE',
            'SOBPTRM_SURROGATE_ID',
            'SOBPTRM_VERSION',
            'SOBPTRM_USER_ID',
            'SOBPTRM_DATA_ORIGIN',
            'SOBPTRM_VPDI_CODE',
            'SOBPTRM_FINAL_GRDE_PUB_DATE',
            'SOBPTRM_DET_GRDE_PUB_DATE',
            'SOBPTRM_REAS_GRDE_PUB_DATE',
            'SOBPTRM_REAS_DET_GRDE_PUB_DATE',
            'SOBPTRM_SCORE_OPEN_DATE',
            'SOBPTRM_SCORE_CUTOFF_DATE',
            'SOBPTRM_REAS_SCORE_OPEN_DATE',
            'SOBPTRM_REAS_SCORE_CUTOFF_DATE',
        ]);
        $select = $source_db->query('SATURN.SOBPTRM');
        $select->convertDate('SOBPTRM_START_DATE');
        $select->convertDate('SOBPTRM_END_DATE');
        $select->convertDate('SOBPTRM_CENSUS_DATE');
        $select->convertDate('SOBPTRM_ACTIVITY_DATE');
        $select->convertDate('SOBPTRM_CENSUS_2_DATE');
        $select->convertDate('SOBPTRM_INCOMPLETE_EXT_DATE');
        $select->convertDate('SOBPTRM_FINAL_GRDE_PUB_DATE');
        $select->convertDate('SOBPTRM_DET_GRDE_PUB_DATE');
        $select->convertDate('SOBPTRM_REAS_GRDE_PUB_DATE');
        $select->convertDate('SOBPTRM_REAS_DET_GRDE_PUB_DATE');
        $select->convertDate('SOBPTRM_SCORE_OPEN_DATE');
        $select->convertDate('SOBPTRM_SCORE_CUTOFF_DATE');
        $select->convertDate('SOBPTRM_REAS_SCORE_OPEN_DATE');
        $select->convertDate('SOBPTRM_REAS_SCORE_CUTOFF_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated SOBPTRM\n");

        // SATURN.SSBXLST
        $this->output->write("Updating SSBXLST\t");
        $target_db->truncate('SSBXLST');
        $insert = $target_db->prepareInsert('SSBXLST', [
            'SSBXLST_TERM_CODE',
            'SSBXLST_XLST_GROUP',
            'SSBXLST_DESC',
            'SSBXLST_MAX_ENRL',
            'SSBXLST_ENRL',
            'SSBXLST_SEATS_AVAIL',
            'SSBXLST_ACTIVITY_DATE',
        ]);
        $select = $source_db->query('SATURN.SSBXLST');
        $select->convertDate('SSBXLST_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated SSBXLST\n");

        // SATURN.SSRXLST
        $this->output->write("Updating SSRXLST\t");
        $target_db->truncate('SSRXLST');
        $insert = $target_db->prepareInsert('SSRXLST', [
            'SSRXLST_TERM_CODE',
            'SSRXLST_CRN',
            'SSRXLST_XLST_GROUP',
            'SSRXLST_ACTIVITY_DATE',
        ]);
        $select = $source_db->query('SATURN.SSRXLST');
        $select->convertDate('SSRXLST_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated SSRXLST\n");

        // SATURN.SIRASGN
        $this->output->write("Updating SIRASGN\t");
        $target_db->truncate('SIRASGN');
        $insert = $target_db->prepareInsert('SIRASGN', [
            'SIRASGN_TERM_CODE',
            'SIRASGN_CRN',
            'SIRASGN_PIDM',
            'SIRASGN_CATEGORY',
            'SIRASGN_PERCENT_RESPONSE',
            'SIRASGN_WORKLOAD_ADJUST',
            'SIRASGN_PERCENT_SESS',
            'SIRASGN_PRIMARY_IND',
            'SIRASGN_OVER_RIDE',
            'SIRASGN_POSITION',
            'SIRASGN_ACTIVITY_DATE',
            'SIRASGN_FCNT_CODE',
            'SIRASGN_POSN',
            'SIRASGN_SUFF',
            'SIRASGN_ASTY_CODE',
            'SIRASGN_DATA_ORIGIN',
            'SIRASGN_USER_ID',
        ]);
        $select = $source_db->query('SATURN.SIRASGN');
        $select->convertDate('SIRASGN_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated SIRASGN\n");

        // SATURN.SSBDESC
        $this->output->write("Updating SSBDESC\t");
        $target_db->truncate('SSBDESC');
        $insert = $target_db->prepareInsert('SSBDESC', [
            'SSBDESC_TERM_CODE',
            'SSBDESC_CRN',
            'SSBDESC_TEXT_NARRATIVE',
            'SSBDESC_ACTIVITY_DATE',
            'SSBDESC_USER_ID',
        ]);
        $select = $source_db->query('SATURN.SSBDESC', [
            'SSBDESC_TERM_CODE',
            'SSBDESC_CRN',
            'SSBDESC_TEXT_NARRATIVE',
            'SSBDESC_ACTIVITY_DATE',
            'SSBDESC_USER_ID',
        ]);
        $select->convertDate('SSBDESC_ACTIVITY_DATE');
        $select->convertText('SSBDESC_TEXT_NARRATIVE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated SSBDESC\n");

        // SATURN.SSBSECT
        $this->output->write("Updating SSBSECT\t");
        $target_db->truncate('SSBSECT');
        $insert = $target_db->prepareInsert('SSBSECT', [
            'SSBSECT_TERM_CODE',
            'SSBSECT_CRN',
            'SSBSECT_PTRM_CODE',
            'SSBSECT_SUBJ_CODE',
            'SSBSECT_CRSE_NUMB',
            'SSBSECT_SEQ_NUMB',
            'SSBSECT_SSTS_CODE',
            'SSBSECT_SCHD_CODE',
            'SSBSECT_CAMP_CODE',
            'SSBSECT_CRSE_TITLE',
            'SSBSECT_CREDIT_HRS',
            'SSBSECT_BILL_HRS',
            'SSBSECT_GMOD_CODE',
            'SSBSECT_SAPR_CODE',
            'SSBSECT_SESS_CODE',
            'SSBSECT_LINK_IDENT',
            'SSBSECT_PRNT_IND',
            'SSBSECT_GRADABLE_IND',
            'SSBSECT_TUIW_IND',
            'SSBSECT_REG_ONEUP',
            'SSBSECT_PRIOR_ENRL',
            'SSBSECT_PROJ_ENRL',
            'SSBSECT_MAX_ENRL',
            'SSBSECT_ENRL',
            'SSBSECT_SEATS_AVAIL',
            'SSBSECT_TOT_CREDIT_HRS',
            'SSBSECT_CENSUS_ENRL',
            'SSBSECT_CENSUS_ENRL_DATE',
            'SSBSECT_ACTIVITY_DATE',
            'SSBSECT_PTRM_START_DATE',
            'SSBSECT_PTRM_END_DATE',
            'SSBSECT_PTRM_WEEKS',
            'SSBSECT_RESERVED_IND',
            'SSBSECT_WAIT_CAPACITY',
            'SSBSECT_WAIT_COUNT',
            'SSBSECT_WAIT_AVAIL',
            'SSBSECT_LEC_HR',
            'SSBSECT_LAB_HR',
            'SSBSECT_OTH_HR',
            'SSBSECT_CONT_HR',
            'SSBSECT_ACCT_CODE',
            'SSBSECT_ACCL_CODE',
            'SSBSECT_CENSUS_2_DATE',
            'SSBSECT_ENRL_CUT_OFF_DATE',
            'SSBSECT_ACAD_CUT_OFF_DATE',
            'SSBSECT_DROP_CUT_OFF_DATE',
            'SSBSECT_CENSUS_2_ENRL',
            'SSBSECT_VOICE_AVAIL',
            'SSBSECT_CAPP_PREREQ_TEST_IND',
            'SSBSECT_GSCH_NAME',
            'SSBSECT_BEST_OF_COMP',
            'SSBSECT_SUBSET_OF_COMP',
            'SSBSECT_INSM_CODE',
            'SSBSECT_REG_FROM_DATE',
            'SSBSECT_REG_TO_DATE',
            'SSBSECT_LEARNER_REGSTART_FDATE',
            'SSBSECT_LEARNER_REGSTART_TDATE',
            'SSBSECT_DUNT_CODE',
            'SSBSECT_NUMBER_OF_UNITS',
            'SSBSECT_NUMBER_OF_EXTENSIONS',
            'SSBSECT_DATA_ORIGIN',
            'SSBSECT_USER_ID',
            'SSBSECT_INTG_CDE',
        ]);
        $select = $source_db->query('SATURN.SSBSECT');
        $select->convertDate('SSBSECT_CENSUS_ENRL_DATE');
        $select->convertDate('SSBSECT_ACTIVITY_DATE');
        $select->convertDate('SSBSECT_PTRM_START_DATE');
        $select->convertDate('SSBSECT_PTRM_END_DATE');
        $select->convertDate('SSBSECT_CENSUS_2_DATE');
        $select->convertDate('SSBSECT_ENRL_CUT_OFF_DATE');
        $select->convertDate('SSBSECT_ACAD_CUT_OFF_DATE');
        $select->convertDate('SSBSECT_DROP_CUT_OFF_DATE');
        $select->convertDate('SSBSECT_REG_FROM_DATE');
        $select->convertDate('SSBSECT_REG_TO_DATE');
        $select->convertDate('SSBSECT_LEARNER_REGSTART_FDATE');
        $select->convertDate('SSBSECT_LEARNER_REGSTART_TDATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated SSBSECT\n");

        // SATURN.SSRATTR
        $this->output->write("Updating SSRATTR\t");
        $target_db->truncate('SSRATTR');
        $insert = $target_db->prepareInsert('SSRATTR', [
            'SSRATTR_TERM_CODE',
            'SSRATTR_CRN',
            'SSRATTR_ATTR_CODE',
            'SSRATTR_ACTIVITY_DATE',
        ]);
        $select = $source_db->query('SATURN.SSRATTR');
        $select->convertDate('SSRATTR_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated SSRATTR\n");

        // SATURN.SSRBLCK
        $this->output->write("Updating SSRBLCK\t");
        $target_db->truncate('SSRBLCK');
        $insert = $target_db->prepareInsert('SSRBLCK', [
            'SSRBLCK_TERM_CODE',
            'SSRBLCK_BLCK_CODE',
            'SSRBLCK_CRN',
            'SSRBLCK_CREDIT_HRS',
            'SSRBLCK_BILL_HRS',
            'SSRBLCK_GMOD_CODE',
            'SSRBLCK_APPR_IND',
            'SSRBLCK_ACTIVITY_DATE',
        ]);
        // Select only particular Block Codes if we are configured to do so.
        if (count($this->allowedBlckCodes)) {
            $codes = [];
            foreach ($this->allowedBlckCodes as $code) {
                $codes[] = "'".$code."'";
            }
            $where = 'WHERE SSRBLCK_BLCK_CODE IN ('.implode(', ', $codes).')';
        } else {
            $where = '';
        }
        $select = $source_db->query('SATURN.SSRBLCK', [], $where);
        $select->convertDate('SSRBLCK_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated SSRBLCK\n");

        // SATURN.SSRMEET
        $this->output->write("Updating SSRMEET\t");
        $target_db->truncate('SSRMEET');
        $insert = $target_db->prepareInsert('SSRMEET', [
            'SSRMEET_TERM_CODE',
            'SSRMEET_CRN',
            'SSRMEET_DAYS_CODE',
            'SSRMEET_DAY_NUMBER',
            'SSRMEET_BEGIN_TIME',
            'SSRMEET_END_TIME',
            'SSRMEET_BLDG_CODE',
            'SSRMEET_ROOM_CODE',
            'SSRMEET_ACTIVITY_DATE',
            'SSRMEET_START_DATE',
            'SSRMEET_END_DATE',
            'SSRMEET_CATAGORY',
            'SSRMEET_SUN_DAY',
            'SSRMEET_MON_DAY',
            'SSRMEET_TUE_DAY',
            'SSRMEET_WED_DAY',
            'SSRMEET_THU_DAY',
            'SSRMEET_FRI_DAY',
            'SSRMEET_SAT_DAY',
            'SSRMEET_SCHD_CODE',
            'SSRMEET_OVER_RIDE',
            'SSRMEET_CREDIT_HR_SESS',
            'SSRMEET_MEET_NO',
            'SSRMEET_HRS_WEEK',
            'SSRMEET_FUNC_CODE',
            'SSRMEET_COMT_CODE',
            'SSRMEET_SCHS_CODE',
            'SSRMEET_MTYP_CODE',
            'SSRMEET_DATA_ORIGIN',
            'SSRMEET_USER_ID',
        ]);
        $select = $source_db->query('SATURN.SSRMEET');
        $select->convertDate('SSRMEET_ACTIVITY_DATE');
        $select->convertDate('SSRMEET_START_DATE');
        $select->convertDate('SSRMEET_END_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated SSRMEET\n");

        // SATURN.STVACYR
        $this->output->write("Updating STVACYR\t");
        $target_db->truncate('STVACYR');
        $insert = $target_db->prepareInsert('STVACYR', [
            'STVACYR_CODE',
            'STVACYR_DESC',
            'STVACYR_ACTIVITY_DATE',
            'STVACYR_SYSREQ_IND',
        ]);
        $select = $source_db->query('SATURN.STVACYR');
        $select->convertDate('STVACYR_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated STVACYR\n");

        // SATURN.STVAPRV
        $this->output->write("Updating STVAPRV\t");
        $target_db->truncate('STVAPRV');
        $insert = $target_db->prepareInsert('STVAPRV', [
            'STVAPRV_CODE',
            'STVAPRV_DESC',
            'STVAPRV_ACTIVITY_DATE',
        ]);
        $select = $source_db->query('SATURN.STVAPRV');
        $select->convertDate('STVAPRV_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated STVAPRV\n");

        // SATURN.STVASTY
        $this->output->write("Updating STVASTY\t");
        $target_db->truncate('STVASTY');
        $insert = $target_db->prepareInsert('STVASTY', [
            'STVASTY_CODE',
            'STVASTY_DESC',
            'STVASTY_ACTIVITY_DATE',
        ]);
        $select = $source_db->query('SATURN.STVASTY');
        $select->convertDate('STVASTY_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated STVASTY\n");

        // SATURN.STVATTR
        $this->output->write("Updating STVATTR\t");
        $target_db->truncate('STVATTR');
        $insert = $target_db->prepareInsert('STVATTR', [
            'STVATTR_CODE',
            'STVATTR_DESC',
            'STVATTR_ACTIVITY_DATE',
        ]);
        $select = $source_db->query('SATURN.STVATTR');
        $select->convertDate('STVATTR_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated STVATTR\n");

        // SATURN.STVBLCK
        $this->output->write("Updating STVBLCK\t");
        $target_db->truncate('STVBLCK');
        $insert = $target_db->prepareInsert('STVBLCK', [
            'STVBLCK_CODE',
            'STVBLCK_DESC',
            'STVBLCK_ACTIVITY_DATE',
        ]);
        $select = $source_db->query('SATURN.STVBLCK');
        $select->convertDate('STVBLCK_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated STVBLCK\n");

        // SATURN.STVBLDG
        $this->output->write("Updating STVBLDG\t");
        $target_db->truncate('STVBLDG');
        $insert = $target_db->prepareInsert('STVBLDG', [
            'STVBLDG_CODE',
            'STVBLDG_DESC',
            'STVBLDG_ACTIVITY_DATE',
            'STVBLDG_VR_MSG_NO',
        ]);
        $select = $source_db->query('SATURN.STVBLDG');
        $select->convertDate('STVBLDG_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated STVBLDG\n");

        // SATURN.STVCAMP
        $this->output->write("Updating STVCAMP\t");
        $target_db->truncate('STVCAMP');
        $insert = $target_db->prepareInsert('STVCAMP', [
            'STVCAMP_CODE',
            'STVCAMP_DESC',
            'STVCAMP_ACTIVITY_DATE',
            'STVCAMP_DICD_CODE',
        ]);
        $select = $source_db->query('SATURN.STVCAMP');
        $select->convertDate('STVCAMP_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated STVCAMP\n");

        // SATURN.STVCIPC
        $this->output->write("Updating STVCIPC\t");
        $target_db->truncate('STVCIPC');
        $insert = $target_db->prepareInsert('STVCIPC', [
            'STVCIPC_CODE',
            'STVCIPC_DESC',
            'STVCIPC_ACTIVITY_DATE',
            'STVCIPC_CIPC_A_IND',
            'STVCIPC_CIPC_B_IND',
            'STVCIPC_CIPC_C_IND',
            'STVCIPC_SP04_PROGRAM_CDE',
        ]);
        $select = $source_db->query('SATURN.STVCIPC');
        $select->convertDate('STVCIPC_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated STVCIPC\n");

        // SATURN.STVCOLL
        $this->output->write("Updating STVCOLL\t");
        $target_db->truncate('STVCOLL');
        $insert = $target_db->prepareInsert('STVCOLL', [
            'STVCOLL_CODE',
            'STVCOLL_DESC',
            'STVCOLL_ADDR_STREET_LINE1',
            'STVCOLL_ADDR_STREET_LINE2',
            'STVCOLL_ADDR_STREET_LINE3',
            'STVCOLL_ADDR_CITY',
            'STVCOLL_ADDR_STATE',
            'STVCOLL_ADDR_COUNTRY',
            'STVCOLL_ADDR_ZIP_CODE',
            'STVCOLL_ACTIVITY_DATE',
            'STVCOLL_SYSTEM_REQ_IND',
            'STVCOLL_VR_MSG_NO',
            'STVCOLL_STATSCAN_CDE3',
            'STVCOLL_DICD_CODE',
        ]);
        $select = $source_db->query('SATURN.STVCOLL');
        $select->convertDate('STVCOLL_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated STVCOLL\n");

        // SATURN.STVCOMT
        $this->output->write("Updating STVCOMT\t");
        $target_db->truncate('STVCOMT');
        $insert = $target_db->prepareInsert('STVCOMT', [
            'STVCOMT_CODE',
            'STVCOMT_DESC',
            'STVCOMT_TRANS_PRINT',
            'STVCOMT_ACTIVITY_DATE',
        ]);
        $select = $source_db->query('SATURN.STVCOMT');
        $select->convertDate('STVCOMT_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated STVCOMT\n");

        // SATURN.STVCSTA
        $this->output->write("Updating STVCSTA\t");
        $target_db->truncate('STVCSTA');
        $insert = $target_db->prepareInsert('STVCSTA', [
            'STVCSTA_CODE',
            'STVCSTA_DESC',
            'STVCSTA_ACTIVITY_DATE',
            'STVCSTA_ACTIVE_IND',
        ]);
        $select = $source_db->query('SATURN.STVCSTA');
        $select->convertDate('STVCSTA_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated STVCSTA\n");

        // SATURN.STVDEPT
        $this->output->write("Updating STVDEPT\t");
        $target_db->truncate('STVDEPT');
        $insert = $target_db->prepareInsert('STVDEPT', [
            'STVDEPT_CODE',
            'STVDEPT_DESC',
            'STVDEPT_ACTIVITY_DATE',
            'STVDEPT_SYSTEM_REQ_IND',
            'STVDEPT_VR_MSG_NO',
        ]);
        $select = $source_db->query('SATURN.STVDEPT');
        $select->convertDate('STVDEPT_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated STVDEPT\n");

        // SATURN.STVDIVS
        $this->output->write("Updating STVDIVS\t");
        $target_db->truncate('STVDIVS');
        $insert = $target_db->prepareInsert('STVDIVS', [
            'STVDIVS_CODE',
            'STVDIVS_DESC',
            'STVDIVS_ACTIVITY_DATE',
        ]);
        $select = $source_db->query('SATURN.STVDIVS');
        $select->convertDate('STVDIVS_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated STVDIVS\n");

        // SATURN.STVFCNT
        $this->output->write("Updating STVFCNT\t");
        $target_db->truncate('STVFCNT');
        $insert = $target_db->prepareInsert('STVFCNT', [
            'STVFCNT_CODE',
            'STVFCNT_DESC',
            'STVFCNT_ACTIVITY_DATE',
        ]);
        $select = $source_db->query('SATURN.STVFCNT');
        $select->convertDate('STVFCNT_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated STVFCNT\n");

        // SATURN.STVLEVL
        $this->output->write("Updating STVLEVL\t");
        $target_db->truncate('STVLEVL');
        $insert = $target_db->prepareInsert('STVLEVL', [
            'STVLEVL_CODE',
            'STVLEVL_DESC',
            'STVLEVL_ACTIVITY_DATE',
            'STVLEVL_ACAD_IND',
            'STVLEVL_CEU_IND',
            'STVLEVL_SYSTEM_REQ_IND',
            'STVLEVL_VR_MSG_NO',
            'STVLEVL_EDI_EQUIV',
        ]);
        $select = $source_db->query('SATURN.STVLEVL');
        $select->convertDate('STVLEVL_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated STVLEVL\n");

        // SATURN.STVMEET
        $this->output->write("Updating STVMEET\t");
        $target_db->truncate('STVMEET');
        $insert = $target_db->prepareInsert('STVMEET', [
            'STVMEET_CODE',
            'STVMEET_MON_DAY',
            'STVMEET_TUE_DAY',
            'STVMEET_WED_DAY',
            'STVMEET_THU_DAY',
            'STVMEET_FRI_DAY',
            'STVMEET_SAT_DAY',
            'STVMEET_SUN_DAY',
            'STVMEET_BEGIN_TIME',
            'STVMEET_END_TIME',
            'STVMEET_ACTIVITY_DATE',
        ]);
        $select = $source_db->query('SATURN.STVMEET');
        $select->convertDate('STVMEET_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated STVMEET\n");

        // SATURN.STVPTRM
        $this->output->write("Updating STVPTRM\t");
        $target_db->truncate('STVPTRM');
        $insert = $target_db->prepareInsert('STVPTRM', [
            'STVPTRM_CODE',
            'STVPTRM_DESC',
            'STVPTRM_ACTIVITY_DATE',
            'STVPTRM_SYSTEM_REQ_IND',
            'STVPTRM_SURROGATE_ID',
            'STVPTRM_VERSION',
            'STVPTRM_USER_ID',
            'STVPTRM_DATA_ORIGIN',
            'STVPTRM_VPDI_CODE',
        ]);
        $select = $source_db->query('SATURN.STVPTRM');
        $select->convertDate('STVPTRM_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated STVPTRM\n");

        // SATURN.STVPWAV
        $this->output->write("Updating STVPWAV\t");
        $target_db->truncate('STVPWAV');
        $insert = $target_db->prepareInsert('STVPWAV', [
            'STVPWAV_CODE',
            'STVPWAV_DESC',
            'STVPWAV_ACTIVITY_DATE',
        ]);
        $select = $source_db->query('SATURN.STVPWAV');
        $select->convertDate('STVPWAV_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated STVPWAV\n");

        // SATURN.STVREPS
        $this->output->write("Updating STVREPS\t");
        $target_db->truncate('STVREPS');
        $insert = $target_db->prepareInsert('STVREPS', [
            'STVREPS_CODE',
            'STVREPS_DESC',
            'STVREPS_ACTIVITY_DATE',
        ]);
        $select = $source_db->query('SATURN.STVREPS');
        $select->convertDate('STVREPS_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated STVREPS\n");

        // SATURN.STVSCHD
        $this->output->write("Updating STVSCHD\t");
        $target_db->truncate('STVSCHD');
        $insert = $target_db->prepareInsert('STVSCHD', [
            'STVSCHD_CODE',
            'STVSCHD_DESC',
            'STVSCHD_ACTIVITY_DATE',
            'STVSCHD_INSTRUCT_METHOD',
            'STVSCHD_COOP_IND',
            'STVSCHD_AUTO_SCHEDULER_IND',
            'STVSCHD_INSM_CODE',
            'STVSCHD_VR_MSG_NO',
        ]);
        $select = $source_db->query('SATURN.STVSCHD');
        $select->convertDate('STVSCHD_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated STVSCHD\n");

        // SATURN.STVSUBJ
        $this->output->write("Updating STVSUBJ\t");
        $target_db->truncate('STVSUBJ');
        $insert = $target_db->prepareInsert('STVSUBJ', [
            'STVSUBJ_CODE',
            'STVSUBJ_DESC',
            'STVSUBJ_ACTIVITY_DATE',
            'STVSUBJ_VR_MSG_NO',
            'STVSUBJ_DISP_WEB_IND',
        ]);
        $select = $source_db->query('SATURN.STVSUBJ');
        $select->convertDate('STVSUBJ_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated STVSUBJ\n");

        // SATURN.STVTERM
        $this->output->write("Updating STVTERM\t");
        $target_db->truncate('STVTERM');
        $insert = $target_db->prepareInsert('STVTERM', [
            'STVTERM_CODE',
            'STVTERM_DESC',
            'STVTERM_START_DATE',
            'STVTERM_END_DATE',
            'STVTERM_FA_PROC_YR',
            'STVTERM_ACTIVITY_DATE',
            'STVTERM_FA_TERM',
            'STVTERM_FA_PERIOD',
            'STVTERM_FA_END_PERIOD',
            'STVTERM_ACYR_CODE',
            'STVTERM_HOUSING_START_DATE',
            'STVTERM_HOUSING_END_DATE',
            'STVTERM_SYSTEM_REQ_IND',
            'STVTERM_TRMT_CODE',
        ]);
        $select = $source_db->query('SATURN.STVTERM');
        $select->convertDate('STVTERM_START_DATE');
        $select->convertDate('STVTERM_END_DATE');
        $select->convertDate('STVTERM_ACTIVITY_DATE');
        $select->convertDate('STVTERM_HOUSING_START_DATE');
        $select->convertDate('STVTERM_HOUSING_END_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated STVTERM\n");

        // SATURN.STVTRMT
        $this->output->write("Updating STVTRMT\t");
        $target_db->truncate('STVTRMT');
        $insert = $target_db->prepareInsert('STVTRMT', [
            'STVTRMT_CODE',
            'STVTRMT_DESC',
            'STVTRMT_ACTIVITY_DATE',
        ]);
        $select = $source_db->query('SATURN.STVTRMT');
        $select->convertDate('STVTRMT_ACTIVITY_DATE');
        $insert->insertAll($select);
        $this->output->write("...\tUpdated STVTRMT\n");

        // SATURN_MIDD.SYVINST
        $this->output->write("Updating SYVINST\t");
        $target_db->truncate('SYVINST');
        $insert = $target_db->prepareInsert('SYVINST', [
            'SYVINST_TERM_CODE',
            'SYVINST_CRN',
            'SYVINST_PIDM',
            'SYVINST_LAST_NAME',
            'SYVINST_FIRST_NAME',
            'WEB_ID',
        ]);
        $select = $source_db->query('SATURN_MIDD.SYVINST');
        $select->convertBin2Hex('WEB_ID');
        $insert->insertAll($select, [$this, 'preprocessSyvinstRow']);
        $this->output->write("...\tUpdated SYVINST\n");

        $target_db->commit();
    }

    /**
     * Validate that user rows contain valid data and fix to avoid failure on
     * user accounts.
     */
    public function preprocessSyvinstRow(object $row): void
    {
        $missing = [];
        if (empty($row->SYVINST_FIRST_NAME)) {
            $missing[] = 'SYVINST_FIRST_NAME';
            $row->SYVINST_FIRST_NAME = 'Unknown';
        }
        if (empty($row->SYVINST_LAST_NAME)) {
            $missing[] = 'SYVINST_LAST_NAME';
            $row->SYVINST_LAST_NAME = 'Unknown';
        }
        if (count($missing)) {
            $message = 'Encountered bad user data in SYVINST. SYVINST_PIDM='.$row->SYVINST_PIDM.' has empty values for '.implode(', ', $missing).'. Using "Unknown" as a placeholder, but upstream data should be fixed.';
            $this->nonFatalErrors[] = $message;
            echo $message."\n";
        }
    }

    /**
     * Answer an array of non-fatal errors that should be mailed.
     *
     * @return array
     *               An array of error messages
     */
    public function getNonFatalErrors(): array
    {
        return array_unique($this->nonFatalErrors);
    }
}
