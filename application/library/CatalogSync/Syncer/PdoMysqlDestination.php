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
abstract class CatalogSync_Syncer_PdoMysqlDestination
{

	protected $destination_db;

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
		$ttermcat = $pdo->prepare("TRUNCATE TABLE catalog_term");
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
		$pdo->query(
			"CREATE TEMPORARY TABLE empty_terms
			SELECT
				term_code
			FROM
				`catalog_term`
				LEFT JOIN SSBSECT ON term_code = SSBSECT_TERM_CODE
			WHERE
				SSBSECT_CRN IS NULL
			GROUP BY
				term_code
			");
				$pdo->query(
			"DELETE FROM catalog_term
			WHERE
				term_code IN (SELECT term_code FROM empty_terms)
			");

		$pdo->query("DROP TEMPORARY TABLE empty_terms");
		print "...\tRemoved empty terms from derived table: catalog_term\n";

		// Delete terms that are manually inactivated.
		print "Removing deactivated terms\t";
		$pdo->query(
			"DELETE FROM
				catalog_term
			WHERE
				term_code IN (SELECT term_code FROM catalog_term_inactive)
			");
		print "...\tRemoved deactivated terms from derived table: catalog_term\n";

		// Rebuild our "materialized views"
		require_once(dirname(__FILE__).'/../../harmoni/SQLUtils.php');
		print "Updating materialized views\t";
		harmoni_SQLUtils::runSQLfile(dirname(__FILE__).'/../../banner/sql/create_views.sql', $pdo);
		print "...\tUpdated materialized views\n";

		$pdo->commit();
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

}
