<?php

namespace Catalog\OsidImpl\SymfonyCache;

use Catalog\OsidImpl\SymfonyCache\course\CourseManager;
use Psr\Cache\CacheItemPoolInterface;

/**
 * A cachable session.
 *
 * @copyright Copyright &copy; 2025, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
abstract class CachableSession extends AbstractSession
{
    private string $collectionId;
    private string $idString;
    private CacheItemPoolInterface $cache;

    /**
     * Contructor.
     *
     * @return void
     */
    public function __construct(
        CourseManager $manager,
    ) {
        parent::__construct($manager);

        $this->collectionId = static::class;
        $this->idString = $this->osidIdToString($this->getCourseCatalogId());
        $this->cache = $manager->getCache();
    }

    public function getCache(): CacheItemPoolInterface
    {
        return $this->cache;
    }

    /**
     * Answer data from the cache or NULL if not available.
     *
     * @param string $key
     */
    protected function cacheGetPlain($key)
    {
        return $this->cache->getItem($this->hash($key))->get();
    }

    /**
     * Set data into the cache and return the data.
     *
     * @param string $key
     */
    protected function cacheSetPlain($key, $value)
    {
        // create a new item by trying to get it from the cache.
        $item = $this->cache->getItem($this->hash($key));
        $item->set($value);
        $this->cache->save($item);

        return $value;
    }

    /**
     * Answer data from the cache or NULL if not available.
     *
     * @param string $key
     */
    protected function cacheGetObj($key)
    {
        // No difference between plain/object in this implementation.
        return $this->cacheGetPlain($key);
    }

    /**
     * Set data into the cache and return the data.
     *
     * @param string $key
     */
    protected function cacheSetObj($key, $value)
    {
        // No difference between plain/object in this implementation.
        return $this->cacheSetPlain($key, $value);
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
        $this->cache->deleteItem($this->hash($key));
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
        return str_replace('\\', '-', str_replace(':', '_', str_replace('.', ',', $this->collectionId.';'.$this->idString.';'.$key)));
    }

    /**
     * Convert an OSID Id to a string representation.
     */
    protected function osidIdToString(\osid_id_Id $id): string
    {
        return $id->getIdentifierNamespace().';'.$id->getAuthority().';'.$id->getIdentifier();
    }

    /**
     * Convert an OSID Type to a string representation.
     */
    protected function osidTypeToString(\osid_type_Type $type): string
    {
        return $type->getIdentifierNamespace().';'.$type->getAuthority().';'.$type->getIdentifier();
    }
}
