<?php

/**
 * @since 8/11/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * A cachable object.
 *
 * @since 8/11/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
abstract class apc_course_CachableSession extends apc_course_AbstractSession
{
    /**
     * Contructor.
     *
     * @return void
     *
     * @since 8/10/10
     */
    public function __construct(osid_course_CourseManager $manager)
    {
        parent::__construct($manager);

        $this->collectionId = static::class;

        $catalogId = $this->getCourseCatalogId();

        $this->idString = $this->osidIdToString($catalogId);
    }
    private string $collectionId;
    private string $idString;

    /**
     * Answer data from the cache or NULL if not available.
     *
     * @param string $key
     *
     * @since 8/10/10
     */
    protected function cacheGetPlain($key)
    {
        $result = apcu_fetch($this->hash($key), $success);
        if (!$success) {
            return null;
        }

        return $result;
    }

    /**
     * Set data into the cache and return the data.
     *
     * @param string $key
     *
     * @since 8/10/10
     */
    protected function cacheSetPlain($key, $value)
    {
        $success = apcu_store($this->hash($key), $value);

        return $value;
    }

    /**
     * Answer data from the cache or NULL if not available.
     *
     * @param string $key
     *
     * @since 8/10/10
     */
    protected function cacheGetObj($key)
    {
        $result = apcu_fetch($this->hash($key), $success);
        if (!$success) {
            return null;
        }

        return unserialize($result);
    }

    /**
     * Set data into the cache and return the data.
     *
     * @param string $key
     *
     * @since 8/10/10
     */
    protected function cacheSetObj($key, $value)
    {
        $success = apcu_store($this->hash($key), serialize($value));

        return $value;
    }

    /**
     * Delete an item from cache.
     *
     * @param string $key
     *
     * @return void
     *
     * @since 8/10/10
     */
    protected function cacheDelete($key)
    {
        apcu_delete($this->hash($key));
    }

    /**
     * Hash a key into a per-instance value.
     *
     * @param string $key
     *
     * @return string
     *
     * @since 8/10/10
     */
    private function hash($key)
    {
        return $this->collectionId.':'.$this->idString.':'.$key;
    }

    /**
     * Convert an OSID Id to a string representation.
     */
    protected function osidIdToString(osid_id_Id $id): string
    {
        return $id->getIdentifierNamespace().':'.$id->getAuthority().':'.$id->getIdentifier();
    }
}
