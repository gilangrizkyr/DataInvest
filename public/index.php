<?php
/**
 * CI4 Index - Working Without mod_rewrite
 * Menggunakan $_GET['r'] untuk route, lalu memodifikasi REQUEST_URI
 */

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

// Setup route sebelum CI4 boot
if (isset($_GET['r'])) {
    $route = ltrim($_GET['r'], '/');
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $_SERVER['REQUEST_URI'] = $requestUri . '/' . $route;
    $_SERVER['PATH_INFO'] = '/' . $route;
}

// Boot CI4
require dirname(__DIR__) . '/app/Config/Paths.php';
require dirname(__DIR__) . '/vendor/autoload.php';

$paths = new \Config\Paths();

require $paths->systemDirectory . '/Boot.php';

exit(\CodeIgniter\Boot::bootWeb($paths));

