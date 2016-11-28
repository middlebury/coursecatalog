<?php

// Add custom routes for the Kurogo JSON API.
$router = Zend_Controller_Front::getInstance()->getRouter();
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
