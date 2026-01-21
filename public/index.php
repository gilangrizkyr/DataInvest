<?php
/**
 * CI4 Minimal Index - Tanpa mod_rewrite
 * Cara akses: index.php/dashboard
 */

define('ENVIRONMENT', 'development');
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

// Coba ambil route dari PATH_INFO
$route = '';

if (isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO']) {
    $route = ltrim($_SERVER['PATH_INFO'], '/');
}
// Fallback ke QUERY_STRING
elseif (isset($_GET['r'])) {
    $route = ltrim($_GET['r'], '/');
}
// Fallback ke REQUEST_URI
elseif (isset($_SERVER['REQUEST_URI'])) {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = preg_replace('#/index\.php.*#', '', $uri);
    $route = ltrim($uri, '/');
}

// Default ke dashboard
if (empty($route)) {
    $route = 'dashboard';
}

// Simpan route untuk CI4
$_SERVER['PATH_INFO'] = '/' . $route;
$_SERVER['REQUEST_URI'] = '/' . $route;

// Boot CI4
require dirname(__DIR__) . '/app/Config/Paths.php';
require dirname(__DIR__) . '/vendor/autoload.php';

$paths = new \Config\Paths();

require $paths->systemDirectory . '/Boot.php';

exit(\CodeIgniter\Boot::bootWeb($paths));

