<?php
/**
 * @since 2/23/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * This interface defines the requirements of destination databases.
 *
 * @since 2/23/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class CatalogSync_Database_PdoAbstract
{
    protected $config;
    protected $pdo;
    protected $name;

    /**
     * Constructor.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        // Ensure that our connection is terminated.
        $this->disconnect();
    }

    /**
     * Configure this sync instance.
     *
     * @return void
     */
    public function configure(Zend_Config $config)
    {
        $this->config = $this->validateConfig($config);
    }

    /**
     * Validate options for a PDO configuration.
     *
     * @return Zend_Config
     */
    public function validateConfig(Zend_Config $config)
    {
        // Check our configuration
        if (empty($config->type)) {
            throw new Exception($this->name.'.type must be specified in the config.');
        }
        if (empty($config->host)) {
            throw new Exception($this->name.'.host must be specified in the config.');
        }
        if (empty($config->database)) {
            throw new Exception($this->name.'.database must be specified in the config.');
        }
        if (empty($config->username)) {
            throw new Exception($this->name.'.username must be specified in the config.');
        }
        if (empty($config->password)) {
            $config->password = '';
        }

        return $config;
    }

    /**
     * Set up connections to our source and destination.
     *
     * @return void
     */
    public function connect()
    {
        $this->pdo = $this->createPdo($this->config);
    }

    /**
     * Answer a Pdo instance based on configuration parameters.
     *
     * @return PDO
     */
    protected function createPdo(Zend_Config $config)
    {
        $dsn = $config->type.':host='.$config->host.';dbname='.$config->database.';charset=utf8mb4';

        return new PDO($dsn, $config->username, $config->password, $this->getDatabaseOptions($config->type));
    }

    /**
     * Answer some database options for our connection.
     *
     * @param string $type
     *
     * @return array
     */
    protected function getDatabaseOptions($type)
    {
        $options = [];
        $options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        $options[PDO::MYSQL_ATTR_USE_BUFFERED_QUERY] = false;
        // The libmysql driver needs to allocate a buffer bigger than the expected data
        if (defined('PDO::MYSQL_ATTR_MAX_BUFFER_SIZE') && 'mysql' == $type) {
            $options[PDO::MYSQL_ATTR_MAX_BUFFER_SIZE] = 1024 * 1024 * 100;
        }
        // The mysqlnd driver on the other hand allocates buffers as big as needed.
        else {
            // nothing needed.
        }

        return $options;
    }

    /**
     * Disconnect from our databases.
     *
     * @return void
     */
    public function disconnect()
    {
        $this->pdo = null;
    }
}
