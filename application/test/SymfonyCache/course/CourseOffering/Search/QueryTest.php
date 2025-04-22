<?php

namespace Catalog\Tests\SymfonyCache\course\CourseOffering\Search;

use Catalog\OsidImpl\SymfonyCache\course\CourseManager;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class QueryTest extends \banner_course_CourseOffering_Search_QueryTest
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$runtimeManager = new \phpkit_AutoloadOsidRuntimeManager(realpath(__DIR__.'/../../../').'/configuration.plist');
        self::$courseManager = self::$runtimeManager->getManager(\osid_OSID::COURSE(), CourseManager::class, '3.0.0');
        self::$courseManager->setCache(new ArrayAdapter());
    }
}
