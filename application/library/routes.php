<?php

$router = Zend_Controller_Front::getInstance()->getRouter();

// Archive viewing and browsing.
$router->addRoute('archive_list',
    new Zend_Controller_Router_Route_Regex(
        'archive/(.+)',
        [
            'controller' => 'archive',
            'action' => 'index',
        ],
        [1 => 'path'],
        'archive/%s'
    )
);
$router->addRoute('archive_view',
    new Zend_Controller_Router_Route_Regex(
        'archive/([\w\-/_]+)/([^/]+\.html)',
        [
            'controller' => 'archive',
            'action' => 'view',
        ],
        [1 => 'path', 2 => 'file'],
        'archive/%s/%s'
    )
);
$router->addRoute('archive_generate',
    new Zend_Controller_Router_Route(
        'archive/generate',
        [
            'controller' => 'archive',
            'action' => 'generate',
        ]
    )
);

// Archive admin UI
$router->addRoute('admin_export',
    new Zend_Controller_Router_Route(
        'admin/export/:config',
        [
            'config' => '-1',
            'controller' => 'admin',
            'action' => 'export',
        ]
    )
);
$router->addRoute('export_revisionhistory',
    new Zend_Controller_Router_Route(
        'admin/export/:config/history',
        [
            'config' => '-1',
            'controller' => 'export',
            'action' => 'revisionhistory',
        ]
    )
);
$router->addRoute('export_viewjson',
    new Zend_Controller_Router_Route(
        'admin/export/revision/:revision',
        [
            'revision' => '-1',
            'controller' => 'export',
            'action' => 'viewjson',
        ]
    )
);
$router->addRoute('export_revisiondiff',
    new Zend_Controller_Router_Route(
        'admin/export/revisiondiff/:rev1/:rev2',
        [
            'rev1' => '-1',
            'rev2' => '-1',
            'controller' => 'export',
            'action' => 'revisiondiff',
        ]
    )
);
$router->addRoute('export_deleteconfig',
    new Zend_Controller_Router_Route(
        'admin/export/deleteconfig',
        [
            'controller' => 'export',
            'action' => 'deleteconfig',
        ]
    )
);
$router->addRoute('export_newconfig',
    new Zend_Controller_Router_Route(
        'admin/export/newconfig',
        [
            'controller' => 'export',
            'action' => 'newconfig',
        ]
    )
);
$router->addRoute('export_insertconfig',
    new Zend_Controller_Router_Route(
        'admin/export/insertconfig',
        [
            'controller' => 'export',
            'action' => 'insertconfig',
        ]
    )
);
$router->addRoute('export_insertrevision',
    new Zend_Controller_Router_Route(
        'admin/export/insertrevision',
        [
            'controller' => 'export',
            'action' => 'insertrevision',
        ]
    )
);
$router->addRoute('export_latestrevision',
    new Zend_Controller_Router_Route(
        'admin/export/latestrevision',
        [
            'controller' => 'export',
            'action' => 'latestrevision',
        ]
    )
);
$router->addRoute('export_reverttorevision',
    new Zend_Controller_Router_Route(
        'admin/export/reverttorevision',
        [
            'controller' => 'export',
            'action' => 'reverttorevision',
        ]
    )
);
$router->addRoute('export_generatecourselist',
    new Zend_Controller_Router_Route(
        'admin/export/generateCourseList',
        [
            'controller' => 'export',
            'action' => 'generatecourselist',
        ]
    )
);

// Archive Export

$router->addRoute('archive_export_job',
    new Zend_Controller_Router_Route(
        'archive/exportjob',
        [
            'controller' => 'archive',
            'action' => 'exportjob',
        ]
    )
);

$router->addRoute('archive_export_active_jobs',
    new Zend_Controller_Router_Route(
        'archive/exportactivejobs',
        [
            'controller' => 'archive',
            'action' => 'exportactivejobs',
        ]
    )
);

$router->addRoute('archive_export_single_job',
    new Zend_Controller_Router_Route(
        'archive/exportsinglejob',
        [
            'controller' => 'archive',
            'action' => 'exportsinglejob',
        ]
    )
);

$router->addRoute('archive_job_progress',
    new Zend_Controller_Router_Route(
        'archive/jobprogress',
        [
            'controller' => 'archive',
            'action' => 'jobprogress',
        ]
    )
);

// Add custom routes for the Kurogo JSON API.
$router->addRoute('kurogo_terms',
    new Zend_Controller_Router_Route(
        'api/json/:catalog/terms',
        [
            'controller' => 'json',
            'action' => 'terms',
        ]
    )
);
$router->addRoute('kurogo_areas',
    new Zend_Controller_Router_Route(
        'api/json/:catalog/catalogAreas/:code',
        [
            'controller' => 'json',
            'action' => 'areas',
        ]
    )
);
$router->addRoute('kurogo_catalog',
    new Zend_Controller_Router_Route(
        'api/json/:catalog/catalog/:code/:area',
        [
            'controller' => 'json',
            'action' => 'catalog',
        ]
    )
);
$router->addRoute('kurogo_search',
    new Zend_Controller_Router_Route(
        'api/json/:catalog/catalog/:code/search/:keyword',
        [
            'controller' => 'json',
            'action' => 'search',
            'keyword' => '',
        ]
    )
);
$router->addRoute('kurogo_area_search',
    new Zend_Controller_Router_Route(
        'api/json/:catalog/catalog/:code/:area/search/:keyword',
        [
            'controller' => 'json',
            'action' => 'search',
            'keyword' => '',
        ]
    )
);
