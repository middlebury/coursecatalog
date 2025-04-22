<?php

namespace Catalog\OsidImpl\SymfonyCache;

use Psr\Cache\CacheItemPoolInterface;

/**
 * A cachable object.
 *
 * @copyright Copyright &copy; 2025, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
abstract class Cachable
{
    protected function __construct(
        private CacheItemPoolInterface $cache,
        private $idString,
        private $collectionId = null,
    ) {
        if (!$collectionId) {
            $this->collectionId = static::class;
        }
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
}
