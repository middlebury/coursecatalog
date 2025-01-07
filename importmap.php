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
    'export' => [
        'path' => 'export.js',
        'entrypoint' => true,
    ],
    'export_revision_diff' => [
        'path' => 'export_revision_diff.js',
        'entrypoint' => true,
    ],
    'export_revision_history' => [
        'path' => 'export_revision_history.js',
        'entrypoint' => true,
    ],
    'export_jobs' => [
        'path' => 'export_jobs.js',
        'entrypoint' => true,
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
    'diff' => [
        'version' => '5.1.0',
    ],
    'hogan.js' => [
        'version' => '3.0.2',
    ],
    'diff2html' => [
        'version' => '3.4.48',
    ],
    'diff2html/bundles/css/diff2html.min.css' => [
        'version' => '3.4.48',
        'type' => 'css',
    ],
    'diff2html/bundles/js/diff2html-ui.min.js' => [
        'version' => '3.4.48',
    ],
];
