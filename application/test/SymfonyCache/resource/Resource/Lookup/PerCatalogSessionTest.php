<?php

namespace Catalog\Tests\SymfonyCache\resource\Resource\Lookup;

use Catalog\OsidImpl\Middlebury\OsidRuntimeManager;
use Catalog\OsidImpl\SymfonyCache\course\CourseManager;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class PerCatalogSessionTest extends \banner_resource_Resource_Lookup_PerCatalogSessionTest
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$runtimeManager = new OsidRuntimeManager(static::getOsidConfig());
        self::$courseManager = self::$runtimeManager->getManager(\osid_OSID::COURSE(), CourseManager::class, '3.0.0');
        self::$courseManager->setCache(new ArrayAdapter());
    }
}
