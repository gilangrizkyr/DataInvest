<?php

use CodeIgniter\Boot;
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
 * SETUP PATH INFO UNTUK TANPA mod_rewrite
 *---------------------------------------------------------------
 */

// Ambil path dari query string ?r=/dashboard
$path = '/dashboard'; // default

if (isset($_GET['r'])) {
    $path = '/' . ltrim($_GET['r'], '/');
} elseif (isset($_SERVER['PATH_INFO']) && !empty($_SERVER['PATH_INFO'])) {
    $path = $_SERVER['PATH_INFO'];
} elseif (isset($_SERVER['REQUEST_URI'])) {
    $uri = parse_url($_SERVER['REQUEST_URI']);
    $path = $uri['path'] ?? '/';
    // Hapus /index.php dari path
    $path = preg_replace('#/index\.php.*$#', '', $path);
    $path = preg_replace('#/DataInvest/public.*$#', '', $path);
    if (empty($path) || $path === '/') {
        $path = '/dashboard';
    }
}

// Set server variables
$_SERVER['PATH_INFO'] = $path;
$_SERVER['REQUEST_URI'] = $path;
$_SERVER['QUERY_STRING'] = '';

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 */

// Load Paths dan autoloader
require dirname(__DIR__) . '/app/Config/Paths.php';
require dirname(__DIR__) . '/vendor/autoload.php';

$paths = new Paths();

// Load framework
require $paths->systemDirectory . '/Boot.php';

exit(Boot::bootWeb($paths));


