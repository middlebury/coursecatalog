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

    /**
     * Answer the host we are connecting to.
     *
     * @return string
     *                The hostname
     */
    public function getHost(): string
    {
        if (!preg_match('/host=([^;]+);/i', $this->dsn, $m)) {
            throw new \Exception('Could not extract the host name from our DSN.');
        }

        return $m[1];
    }

    /**
     * Answer the port we are connecting to.
     *
     * @return int
     *             The port
     */
    public function getPort(): int
    {
        if (!preg_match('/port=(\d+);/i', $this->dsn, $m)) {
            return 3306;
        }

        return (int) $m[1];
    }

    /**
     * Answer the database name we are connecting to.
     *
     * @return string
     *                The database name
     */
    public function getDatabase(): string
    {
        if (!preg_match('/dbname=([^;]+);/i', $this->dsn, $m)) {
            throw new \Exception('Could not extract the dbname from our DSN.');
        }

        return $m[1];
    }

    /**
     * Answer the database username we are using.
     *
     * @return string
     *                The username
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Answer the database password we are using.
     *
     * @return string
     *                The password
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
