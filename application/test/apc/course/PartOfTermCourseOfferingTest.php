<?php

/**
 * Test class for banner_course_CourseOffering.
 * Generated by PHPUnit on 2009-04-16 at 10:31:07.
 */
class apc_course_PartOfTermCourseOfferingTest extends banner_course_PartOfTermCourseOfferingTest
{
    use banner_DatabaseTestTrait;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$runtimeManager = new phpkit_AutoloadOsidRuntimeManager(realpath(__DIR__.'/../').'/configuration.plist');
        self::$courseManager = self::$runtimeManager->getManager(osid_OSID::COURSE(), 'apc_course_CourseManager', '3.0.0');
    }
}
