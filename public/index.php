<?php

use CodeIgniter\Boot;
use Config\Paths;

$minPhpVersion = '8.1';
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo 'PHP version must be 8.1 or higher.';
    exit(1);
}

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

require dirname(__DIR__) . '/app/Config/Paths.php';
require dirname(__DIR__) . '/vendor/autoload.php';

$paths = new Paths();

// =============================================================================
// SUPPORT FOR SERVERS WITHOUT mod_rewrite (Query String Routing)
// =============================================================================
// This allows routes like: index.php?r=/dashboard instead of /dashboard

if (isset($_GET['r']) && $_GET['r']) {
    $route = $_GET['r'];
    
    // Remove r from GET to avoid CI4 seeing the "=" character
    unset($_GET['r']);
    
    // Rebuild query string without 'r'
    $queryString = http_build_query($_GET);
    if ($queryString) {
        $route .= '?' . $queryString;
    }
    
    // Set PATH_INFO and REQUEST_URI so CodeIgniter recognizes the route
    $_SERVER['PATH_INFO'] = $route;
    $_SERVER['REQUEST_URI'] = '/index.php' . $route;
    $_SERVER['QUERY_STRING'] = $queryString;
    
    // Also handle ORIG_PATH_INFO if available
    if (isset($_SERVER['ORIG_PATH_INFO'])) {
        $_SERVER['ORIG_PATH_INFO'] = '/index.php' . $route;
    }
}

require $paths->systemDirectory . '/Boot.php';

exit(Boot::bootWeb($paths));

