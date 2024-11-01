<?php

namespace App\Tests;

trait AppDatabaseTestTrait
{
    use \banner_DatabaseTestTrait;

    public static function setUpBeforeClass(): void
    {
        self::tearDownAppDatabase();
        self::setUpDatabase();
        self::loadAppDatabase();
    }

    public static function tearDownAfterClass(): void
    {
        self::tearDownAppDatabase();
        self::tearDownDatabase();
    }

    /**
     * Load the app testing database tables.
     */
    public static function loadAppDatabase()
    {
        // Initialize our testing database
        $db = self::getDB();
        \harmoni_SQLUtils::runSQLfile(APPLICATION_PATH.'/sql/user_schedules.sql', $db);
    }

    /**
     * Remove the app testing database tables.
     */
    public static function tearDownAppDatabase()
    {
        $db = self::getDB();
        \harmoni_SQLUtils::runSQLfile(APPLICATION_PATH.'/sql/drop_user_schedules.sql', $db);
    }
}
