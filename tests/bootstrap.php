<?php

// Add our application/test/ directory to the autoload path.
define('APPLICATION_PATH', __DIR__.'/../application');
set_include_path(APPLICATION_PATH.'/test'.\PATH_SEPARATOR.get_include_path());
// Change to the public/ directory so that paths in our config files aren't broken.
chdir(dirname(__DIR__).'/public');

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}
