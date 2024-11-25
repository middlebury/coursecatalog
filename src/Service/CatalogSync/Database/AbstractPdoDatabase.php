<?php
/**
 * @since 2/23/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Service\CatalogSync\Database;

/**
 * This interface defines the requirements of destination databases.
 *
 * @since 2/23/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class AbstractPdoDatabase
{
    protected $pdo;

    /**
     * Constructor.
     */
    public function __construct(
        protected string $dsn,
        protected string $username,
        protected string $password,
    ) {
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
     * Set up connections to our source and destination.
     */
    public function connect(): void
    {
        $this->pdo = $this->createPdo();
    }

    /**
     * Answer a Pdo instance based on configuration parameters.
     *
     * @return PDO
     */
    private function createPdo(): \PDO
    {
        $dsn = $this->dsn;
        // Ensure that we have a charset specified.
        if (!preg_match('/charset=/', $dsn)) {
            $dsn .= ';charset=utf8mb4';
        }
        if (!preg_match('/^(\w+):.+/', $dsn, $m)) {
            throw new \InvalidArgumentException("DSN '$dsn' does not look like a valid PDO database DSN.");
        }
        $type = $m[1];

        return new \PDO($dsn, $this->username, $this->password, $this->getDatabaseOptions($type));
    }

    /**
     * Answer some database options for our connection.
     *
     * @param string $type
     */
    private function getDatabaseOptions($type): array
    {
        $options = [];
        $options[\PDO::ATTR_ERRMODE] = \PDO::ERRMODE_EXCEPTION;
        $options[\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY] = false;
        // The libmysql driver needs to allocate a buffer bigger than the expected data
        if (defined('\PDO::MYSQL_ATTR_MAX_BUFFER_SIZE') && 'mysql' == $type) {
            $options[\PDO::MYSQL_ATTR_MAX_BUFFER_SIZE] = 1024 * 1024 * 100;
        }
        // The mysqlnd driver on the other hand allocates buffers as big as needed.
        else {
            // nothing needed.
        }

        return $options;
    }

    /**
     * Disconnect from our databases.
     */
    public function disconnect(): void
    {
        $this->pdo = null;
    }
}
