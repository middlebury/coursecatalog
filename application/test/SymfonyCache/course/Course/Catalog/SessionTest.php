<?php

namespace Catalog\Tests\SymfonyCache\course\Course\Catalog;

use Catalog\OsidImpl\Middlebury\OsidRuntimeManager;
use Catalog\OsidImpl\SymfonyCache\course\CourseManager;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class SessionTest extends \banner_course_Course_Catalog_SessionTest
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$runtimeManager = new OsidRuntimeManager(static::getOsidConfig());
        self::$courseManager = self::$runtimeManager->getManager(\osid_OSID::COURSE(), CourseManager::class, '3.0.0');
        self::$courseManager->setCache(new ArrayAdapter());
    }
}
