<?php
/**
 * @since 2/22/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * This class implements the Banner-to-Catalog sync using the Banner OCI connection
 * on the source side and a MySQL-PDO connection on the destination side.
 *
 * @since 2/22/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class CatalogSync_Syncer_Oci extends CatalogSync_Syncer_Abstract implements CatalogSync_Syncer
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
        $this->source_db = new CatalogSync_Database_Source_Oci('source_banner_db');
        $this->source_db->configure($config->source_banner_db);

        /*
         * Custom Error handler function to throw exceptions on any PHP
         * Warnings or Errors. This should catch any OCI problems that
         * are not picked up by calls to oci_error().
         */
        set_error_handler([$this, 'exception_error_handler'], E_ERROR | E_WARNING);
    }

    /**
     * Custom Error handler function to throw exceptions on any PHP
     * Warnings or Errors. This should catch any OCI problems that
     * are not picked up by calls to oci_error().
     */
    public function exception_error_handler($errno, $errstr, $errfile = null, $errLine = null, $errcontext = null)
    {
        throw new Exception($errstr, $errno);
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
