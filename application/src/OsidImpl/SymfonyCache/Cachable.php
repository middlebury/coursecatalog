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
     * Answer data from the cache specific to this instance or NULL if unavailable.
     *
     * @param string $key
     */
    protected function cacheGetInstance($key)
    {
        return $this->cache->getItem($this->hashInstance($key))->get();
    }

    /**
     * Set data into the cache specific to this instance and return the data.
     *
     * @param string $key
     */
    protected function cacheSetInstance($key, $value)
    {
        // create a new item by trying to get it from the cache.
        $item = $this->cache->getItem($this->hashInstance($key));
        $item->set($value);
        $this->cache->save($item);

        return $value;
    }

    /**
     * Delete an item from cache.
     *
     * @param string $key
     *
     * @return void
     */
    protected function cacheDeleteInstance($key)
    {
        $this->cache->deleteItem($this->hashInstance($key));
    }

    /**
     * Hash a key into a per-instance value.
     *
     * @param string $key
     *
     * @return string
     */
    private function hashInstance($key)
    {
        return str_replace('\\', '-', str_replace(':', '_', str_replace('.', ',', $this->collectionId.';'.$this->idString.';'.$key)));
    }
}
