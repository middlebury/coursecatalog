<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => 'app.js',
        'entrypoint' => true,
    ],
    'bookmarks' => [
        'path' => 'bookmarks.js',
    ],
    'offering_search' => [
        'path' => 'offering_search.js',
        'entrypoint' => true,
    ],
    'schedules' => [
        'path' => 'schedules.js',
        'entrypoint' => true,
    ],
    'jquery-expander' => [
        'version' => '2.0.2',
    ],
    'jquery' => [
        'version' => '3.7.1',
    ],
    'jquery-ui' => [
        'path' => 'jquery-ui/jquery-ui.js',
    ],
    '@toast-ui/calendar' => [
        'version' => '2.1.3',
    ],
    'tui-date-picker' => [
        'version' => '4.3.3',
    ],
    'tui-time-picker' => [
        'version' => '2.1.6',
    ],
    '@toast-ui/calendar/dist/toastui-calendar.min.css' => [
        'version' => '2.1.3',
        'type' => 'css',
    ],
];
