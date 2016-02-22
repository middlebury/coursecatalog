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
	protected $destination_db_config;

	/**
	 * Configure this sync instance
	 *
	 * @param Zend_Config $config
	 * @return void
	 * @access public
	 */
	public function configure (Zend_Config $config) {
		$this->destination_db_config = $this->validatePdoConfig($config->destination_db, 'destination_db');
	}

	/**
	 * Validate options for a PDO configuration.
	 *
	 * @param Zend_Config $config
	 * @return Zend_Config
	 */
	protected function validatePdoConfig(Zend_Config $config, $name = '') {
		// Check our configuration
		if (empty($config->type)) {
			throw new Exception($name.'.type must be specified in the config.');
		}
		if (empty($config->host)) {
			throw new Exception($name.'.host must be specified in the config.');
		}
		if (empty($config->database)) {
			throw new Exception($name.'.database must be specified in the config.');
		}
		if (empty($config->username)) {
			throw new Exception($name.'.username must be specified in the config.');
		}
		if (empty($config->password)) {
			$config->password = '';
		}
		return $config;
	}

	/**
	 * Answer a Pdo instance based on configuration parameters.
	 *
	 * @param Zend_Config $config
	 * @return PDO
	 */
	protected function createPdo(Zend_Config $config) {
		$dsn = $config->type.':host='.$config->host.';dbname='.$config->database;
		return new Pdo($dsn, $config->username, $config->password, $this->getDestDatabaseOptions($config->type));
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
		$this->destination_db = $this->createPdo($this->destination_db_config);
	}

	/**
	 * Answer some database options for our connection.
	 *
	 * @param string $type
	 * @return array
	 */
	protected function getDestDatabaseOptions($type) {
		$options = array();
		// $options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		// The libmysql driver needs to allocate a buffer bigger than the expected data
		if (defined('PDO::MYSQL_ATTR_MAX_BUFFER_SIZE') && $type == 'mysql') {
			$options[PDO::MYSQL_ATTR_MAX_BUFFER_SIZE] = 1024*1024*100;
		}
		// The mysqlnd driver on the other hand allocates buffers as big as needed.
		else {
			// nothing needed.
		}
		return $options;
	}

	/**
	 * Update derived data in the destination database.
	 *
	 * @return void
	 * @access public
	 */
	public function updateDerived () {
		// Build derived table for easier term-catalog lookups
		print "Updating derived tables\t";
		$this->destination_db->beginTransaction();
		$ttermcat = $this->destination_db->prepare("TRUNCATE TABLE catalog_term");
		$ttermcat->execute();

		$searches = $this->destination_db->query("SELECT * FROM catalog_term_match")->fetchAll();

		$itermcat = $this->destination_db->prepare("
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
		$this->destination_db->query(
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
				$this->destination_db->query(
			"DELETE FROM catalog_term
			WHERE
				term_code IN (SELECT term_code FROM empty_terms)
			");

		$this->destination_db->query("DROP TEMPORARY TABLE empty_terms");
		print "...\tRemoved empty terms from derived table: catalog_term\n";

		// Delete terms that are manually inactivated.
		print "Removing deactivated terms\t";
		$this->destination_db->query(
			"DELETE FROM
				catalog_term
			WHERE
				term_code IN (SELECT term_code FROM catalog_term_inactive)
			");
		print "...\tRemoved deactivated terms from derived table: catalog_term\n";

		// Rebuild our "materialized views"
		require_once(dirname(__FILE__).'/../../harmoni/SQLUtils.php');
		print "Updating materialized views\t";
		harmoni_SQLUtils::runSQLfile(dirname(__FILE__).'/../../banner/sql/create_views.sql', $this->destination_db);
		print "...\tUpdated materialized views\n";

		$this->destination_db->commit();
	}

	/**
	 * Disconnect from our databases
	 *
	 * @return void
	 * @access public
	 */
	public function disconnect () {
		$this->destination_db = null;
	}

}
