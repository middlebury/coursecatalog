<?php
$router = Zend_Controller_Front::getInstance()->getRouter();

// Archive viewing and browsing.
$router->addRoute('archive_list',
	new Zend_Controller_Router_Route_Regex(
		'archive/(.+)',
		array(
			'controller' => 'archive',
			'action'     => 'index'
		),
		array(1 => 'path'),
		'archive/%s'
	)
);
$router->addRoute('archive_view',
	new Zend_Controller_Router_Route_Regex(
		'archive/([\w\-/_]+)/([^/]+\.html)',
		array(
			'controller' => 'archive',
			'action'     => 'view'
		),
		array(1 => 'path', 2 => 'file'),
		'archive/%s/%s'
	)
);
$router->addRoute('archive_generate',
	new Zend_Controller_Router_Route(
		'archive/generate',
		array(
			'controller' => 'archive',
			'action'     => 'generate'
		)
	)
);

// Archive admin UI
$router->addRoute('admin_export',
	new Zend_Controller_Router_Route(
		'admin/export/:config',
		array(
			'config'		  => '-1',
			'controller' 	=> 'admin',
			'action'			=> 'export'
		)
	)
);
$router->addRoute('export_revisionhistory',
	new Zend_Controller_Router_Route(
		'admin/export/:config/history',
		array(
			'config'		  => '-1',
			'controller' 	=> 'export',
			'action'			=> 'revisionhistory'
		)
	)
);
$router->addRoute('export_viewjson',
	new Zend_Controller_Router_Route(
		'admin/export/revision/:revision',
		array(
			'revision'	  => '-1',
			'controller' 	=> 'export',
			'action'			=> 'viewjson'
		)
	)
);
$router->addRoute('export_revisiondiff',
	new Zend_Controller_Router_Route(
		'admin/export/revisiondiff/:rev1/:rev2',
		array(
			'rev1'	  => '-1',
			'rev2'		=> '-1',
			'controller' 	=> 'export',
			'action'			=> 'revisiondiff'
		)
	)
);
$router->addRoute('export_deleteconfig',
	new Zend_Controller_Router_Route(
		'admin/export/deleteconfig',
		array(
			'controller' 	=> 'export',
			'action'			=> 'deleteconfig'
		)
	)
);
$router->addRoute('export_newconfig',
	new Zend_Controller_Router_Route(
		'admin/export/newconfig',
		array(
			'controller' 	=> 'export',
			'action'			=> 'newconfig'
		)
	)
);
$router->addRoute('export_insertconfig',
	new Zend_Controller_Router_Route(
		'admin/export/insertconfig',
		array(
			'controller' 	=> 'export',
			'action'			=> 'insertconfig'
		)
	)
);
$router->addRoute('export_insertrevision',
	new Zend_Controller_Router_Route(
		'admin/export/insertrevision',
		array(
			'controller' 	=> 'export',
			'action'			=> 'insertrevision'
		)
	)
);
$router->addRoute('export_latestrevision',
	new Zend_Controller_Router_Route(
		'admin/export/latestrevision',
		array(
			'controller' 	=> 'export',
			'action'			=> 'latestrevision'
		)
	)
);
$router->addRoute('export_reverttorevision',
	new Zend_Controller_Router_Route(
		'admin/export/reverttorevision',
		array(
			'controller' 	=> 'export',
			'action'			=> 'reverttorevision'
		)
	)
);
$router->addRoute('export_generatecourselist',
	new Zend_Controller_Router_Route(
		'admin/export/generateCourseList',
		array(
			'controller' 	=> 'export',
			'action'			=> 'generatecourselist'
		)
	)
);

// Archive Export

$router->addRoute('archive_export_job',
	new Zend_Controller_Router_Route(
		'archive/exportjob',
		array(
			'controller' 	=> 'archive',
			'action'			=> 'exportjob'
		)
	)
);

$router->addRoute('archive_export_active_jobs',
	new Zend_Controller_Router_Route(
		'archive/exportactivejobs',
		array(
			'controller' 	=> 'archive',
			'action'			=> 'exportactivejobs'
		)
	)
);

$router->addRoute('archive_export_single_job',
	new Zend_Controller_Router_Route(
		'archive/exportsinglejob',
		array(
			'controller' 	=> 'archive',
			'action'			=> 'exportsinglejob'
		)
	)
);

$router->addRoute('archive_job_progress',
	new Zend_Controller_Router_Route(
		'archive/jobprogress',
		array(
			'controller' 	=> 'archive',
			'action'			=> 'jobprogress'
		)
	)
);

// Add custom routes for the Kurogo JSON API.
$router->addRoute('kurogo_terms',
	new Zend_Controller_Router_Route(
		'api/json/:catalog/terms',
		array(
			'controller' => 'json',
			'action'     => 'terms'
		)
	)
);
$router->addRoute('kurogo_areas',
	new Zend_Controller_Router_Route(
		'api/json/:catalog/catalogAreas/:code',
		array(
			'controller' => 'json',
			'action'     => 'areas'
		)
	)
);
$router->addRoute('kurogo_catalog',
	new Zend_Controller_Router_Route(
		'api/json/:catalog/catalog/:code/:area',
		array(
			'controller' => 'json',
			'action'     => 'catalog'
		)
	)
);
$router->addRoute('kurogo_search',
	new Zend_Controller_Router_Route(
		'api/json/:catalog/catalog/:code/search/:keyword',
		array(
			'controller' => 'json',
			'action'     => 'search',
			'keyword'    => '',
		)
	)
);
$router->addRoute('kurogo_area_search',
	new Zend_Controller_Router_Route(
		'api/json/:catalog/catalog/:code/:area/search/:keyword',
		array(
			'controller' => 'json',
			'action'     => 'search',
			'keyword'    => '',
		)
	)
);
