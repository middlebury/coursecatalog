<?php
/**
 * @since 2/22/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * This class implements the Banner-to-Catalog sync using Pdo connection
 * on the source side and a MySQL-PDO connection on the destination side.
 *
 * @since 2/22/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class CatalogSync_Syncer_PdoMysql extends CatalogSync_Syncer_Abstract implements CatalogSync_Syncer
{
    protected $source_db;

    /**
     * Configure this sync instance.
     *
     * @return void
     */
    public function configure(Zend_Config $config)
    {
        parent::configure($config);
        $this->source_db = new CatalogSync_Database_Source_PdoMysql('source_mysql_db');
        $this->source_db->configure($config->source_mysql_db);
    }

    /**
     * Set up connections to our source and destination.
     *
     * @return void
     */
    public function connect()
    {
        parent::connect();

        // Connect to Banner
        $this->source_db->connect();
    }

    /**
     * Disconnect from our databases.
     *
     * @return void
     */
    public function disconnect()
    {
        parent::disconnect();
        $this->source_db->disconnect();
    }

    /**
     * Answer the database we should copy from.
     *
     * @return CatalogSync_Database_Source
     */
    protected function getCopySourceDatabase()
    {
        return $this->source_db;
    }
}
