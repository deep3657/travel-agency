<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Suppress deprecation notices from vendor packages that have not yet been
// updated for PHP 8.5 (e.g. PDO::MYSQL_ATTR_SSL_CA in laravel/framework and
// phpoffice/phpspreadsheet). Remove once those packages release PHP-8.5-clean
// versions and the project is upgraded.
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
