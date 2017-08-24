<?php
$router = Zend_Controller_Front::getInstance()->getRouter();

// Archive viewing and browsing.
$router->addRoute('archive_list',
	new Zend_Controller_Router_Route_Regex(
		'archive/(.+)',
		array(
			'controller' => 'archive',
			'action'     => 'list'
		),
		array(1 => 'path'),
		'archive/%s'
	)
);
$router->addRoute('archive_view',
	new Zend_Controller_Router_Route_Regex(
		'archive/([\w-/_]+)/([^/]+\.html)',
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
