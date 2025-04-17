<?php

namespace Catalog\OsidImpl\SymfonyCache;

/**
 * A cachable session.
 *
 * @copyright Copyright &copy; 2025, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
abstract class CachableSession extends AbstractSession
{
    /**
     * Contructor.
     *
     * @return void
     */
    public function __construct(\osid_course_CourseManager $manager)
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
     */
    private function hash($key)
    {
        return $this->collectionId.':'.$this->idString.':'.$key;
    }

    /**
     * Convert an OSID Id to a string representation.
     */
    protected function osidIdToString(\osid_id_Id $id): string
    {
        return $id->getIdentifierNamespace().':'.$id->getAuthority().':'.$id->getIdentifier();
    }

    /**
     * Convert an OSID Type to a string representation.
     */
    protected function osidTypeToString(\osid_type_Type $type): string
    {
        return $type->getIdentifierNamespace().':'.$type->getAuthority().':'.$type->getIdentifier();
    }
}
