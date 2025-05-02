<?php

namespace Catalog\Tests\SymfonyCache\course\Topic\Search;

use Catalog\OsidImpl\Middlebury\OsidRuntimeManager;
use Catalog\OsidImpl\SymfonyCache\course\CourseManager;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class QueryTest extends \banner_course_Topic_Search_QueryTest
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$runtimeManager = new OsidRuntimeManager(static::getOsidConfig());
        self::$courseManager = self::$runtimeManager->getManager(\osid_OSID::COURSE(), CourseManager::class, '3.0.0');
        self::$courseManager->setCache(new ArrayAdapter());
    }
}
