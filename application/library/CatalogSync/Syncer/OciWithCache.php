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
 * and mysqldump to copy from the cache to the destination.
 *
 * @since 2/22/16
 * @package CatalogSync
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class CatalogSync_Syncer_OciWithCache
	extends CatalogSync_Syncer_Oci
	implements CatalogSync_Syncer
{

	protected $temp_db;
	protected $temp_db_config;
	protected $destination_db_config;

	/**
	 * Configure this sync instance
	 *
	 * @param Zend_Config $config
	 * @return void
	 * @access public
	 */
	public function configure (Zend_Config $config) {
		parent::configure($config);
		$this->temp_db = new CatalogSync_Database_Destination_Pdo('temp_db');
		$this->temp_db->configure($config->temp_db);

		// Store our configurations for use with shell commands.
		$this->temp_db_config = $this->temp_db->validateConfig($config->temp_db);
		$this->destination_db_config = $this->destination_db->validateConfig($config->destination_db);

		// Configure paths to shell commands.
		if (!empty($config->mysqldump)) {
			$this->mysqldump = $config->mysqldump;
		} else {
			$this->mysqldump = 'mysqldump';
		}
		if (!empty($config->mysql)) {
			$this->mysql = $config->mysql;
		} else {
			$this->mysql = 'mysql';
		}
	}

	/**
	 * Set up connections to our source and destination.
	 *
	 * @return void
	 * @access public
	 */
	public function connect () {
		parent::connect();
		$this->temp_db->connect();
	}

	/**
	 * Answer the database we should copy into during copy.
	 *
	 * @return CatalogSync_Database_Destination
	 * @access public
	 */
	protected function getCopyTargetDatabase () {
		return $this->temp_db;
	}

	/**
	 * Take actions before copying data.
	 *
	 * @return void
	 * @access public
	 */
	public function preCopy () {
		parent::preCopy();

		// Create the cache tables
		// Copy the primary table definitions into our temporary database
		$command = $this->mysqldump.' --add-drop-table --single-transaction --no-data '
		.' -h '.escapeshellarg($this->destination_db_config->host)
		.' -u '.escapeshellarg($this->destination_db_config->username)
		.' -p'.escapeshellarg($this->destination_db_config->password)
		.' '.escapeshellarg($this->destination_db_config->database)
		.' '.implode(' ', $this->getBannerTables())
		.' | '.$this->mysql
		.' -h '.escapeshellarg($this->temp_db_config->host)
		.' -u '.escapeshellarg($this->temp_db_config->username)
		.' -p'.escapeshellarg($this->temp_db_config->password)
		.' -D '.escapeshellarg($this->temp_db_config->database);
		print "Creating cache tables	...";
		exec($command, $output, $return_var);
		print "	done\n";
		if ($return_var) {
			throw new Exception('Moving from temp database to primary database failed: '.implode("\n", $output));
		}
	}

	/**
	 * Take actions after copying data.
	 *
	 * @return void
	 * @access public
	 */
	public function postCopy () {
		// Copy the temporary tables into our primary database
		// If we haven't had any problems updating from banner, import into our primary database
		$command = $this->mysqldump.' --add-drop-table --single-transaction '
			.' -h '.escapeshellarg($this->temp_db_config->host)
			.' -u '.escapeshellarg($this->temp_db_config->username)
			.' -p'.escapeshellarg($this->temp_db_config->password)
			.' '.escapeshellarg($this->temp_db_config->database)
			.' '.implode(' ', $this->getBannerTables())
			.' | '.$this->mysql
			.' -h '.escapeshellarg($this->destination_db_config->host)
			.' -u '.escapeshellarg($this->destination_db_config->username)
			.' -p'.escapeshellarg($this->destination_db_config->password)
			.' -D '.escapeshellarg($this->destination_db_config->database);
		print "Moving from cache database to primary database 	...";
		exec($command, $output, $return_var);
		print "	done\n";
		if ($return_var) {
			throw new Exception('Moving from temp database to primary database failed: '.implode("\n", $output));
		}
	}

	/**
	 * Disconnect from our databases
	 *
	 * @return void
	 * @access public
	 */
	public function disconnect () {
		parent::disconnect();
		$this->temp_db->disconnect();
	}

	/**
	 * Answer a list of the Banner tables.
	 *
	 * @return array
	 */
	protected function getBannerTables() {
		return array(
			'GORINTG',
			'GTVDUNT',
			'GTVINSM',
			'GTVINTP',
			'GTVMTYP',
			'GTVSCHS',
			'SCBCRSE',
			'SCBDESC',
			'SCRATTR',
			'SCREQIV',
			'SCRLEVL',
			'SIRASGN',
			'SSBDESC',
			'SSBSECT',
			'SSBXLST',
			'SSRATTR',
			'SSRBLCK',
			'SSRMEET',
			'SSRXLST',
			'STVACYR',
			'STVAPRV',
			'STVASTY',
			'STVATTR',
			'STVBLCK',
			'STVBLDG',
			'STVCAMP',
			'STVCIPC',
			'STVCOLL',
			'STVCOMT',
			'STVCSTA',
			'STVDEPT',
			'STVDIVS',
			'STVFCNT',
			'STVLEVL',
			'STVMEET',
			'STVPWAV',
			'STVREPS',
			'STVSCHD',
			'STVSUBJ',
			'STVTERM',
			'STVTRMT',
			'SYVINST',
			);
	}

}
