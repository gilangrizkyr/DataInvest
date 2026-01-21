<?php

use CodeIgniter\Boot;
use CodeIgniter\Router\RouteCollection;
use Config\Paths;

/*
 *---------------------------------------------------------------
 * CHECK PHP VERSION
 *---------------------------------------------------------------
 */
$minPhpVersion = '8.1';
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo 'PHP version must be 8.1 or higher.';
    exit(1);
}

/*
 *---------------------------------------------------------------
 * SET THE CURRENT DIRECTORY
 *---------------------------------------------------------------
 */

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

/*
 *---------------------------------------------------------------
 * SETUP PATH INFO - UNTUK TANPA mod_rewrite
 *---------------------------------------------------------------
 */

// Default route
$route = 'dashboard';

// Ambil dari query string ?r=/dashboard
if (isset($_GET['r'])) {
    $route = ltrim($_GET['r'], '/');
}
// Atau dari PATH_INFO (jika server support)
elseif (isset($_SERVER['PATH_INFO']) && !empty($_SERVER['PATH_INFO'])) {
    $route = ltrim($_SERVER['PATH_INFO'], '/');
}
// Dari REQUEST_URI (fallback)
elseif (isset($_SERVER['REQUEST_URI'])) {
    $uri = $_SERVER['REQUEST_URI'];
    // Hapus query string
    if (($pos = strpos($uri, '?')) !== false) {
        $uri = substr($uri, 0, $pos);
    }
    // Hapus /index.php
    $uri = str_replace('/index.php', '', $uri);
    // Hapus path aplikasi
    $uri = preg_replace('#^/DataInvest/public#', '', $uri);
    if (!empty($uri) && $uri !== '/') {
        $route = ltrim($uri, '/');
    }
}

// Set server variables
$_SERVER['PATH_INFO'] = '/' . $route;
$_SERVER['REQUEST_URI'] = '/' . $route;
$_SERVER['QUERY_STRING'] = '';

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 */

require dirname(__DIR__) . '/app/Config/Paths.php';
require dirname(__DIR__) . '/vendor/autoload.php';

$paths = new Paths();

// Load framework
require $paths->systemDirectory . '/Boot.php';

exit(Boot::bootWeb($paths));

