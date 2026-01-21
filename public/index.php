<?php
/**
 * CI4 Index - Working Without mod_rewrite
 * Menggunakan ?r=/route untuk akses halaman
 */

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

// Base path aplikasi
$basePath = '/DataInvest/public';

// Setup route dari query string ?r=/dashboard
if (isset($_GET['r'])) {
    $route = ltrim($_GET['r'], '/');
    unset($_GET['r']);
    $_SERVER['QUERY_STRING'] = '';
    
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $requestUri = str_replace($basePath . '/index.php', '', $requestUri);
    if ($requestUri === $basePath) {
        $requestUri = $basePath . '/';
    }
    $_SERVER['REQUEST_URI'] = $requestUri . '/' . $route;
    $_SERVER['PATH_INFO'] = '/' . $route;
}

// Boot CI4
require dirname(__DIR__) . '/app/Config/Paths.php';
require dirname(__DIR__) . '/vendor/autoload.php';

$paths = new \Config\Paths();

require $paths->systemDirectory . '/Boot.php';

exit(\CodeIgniter\Boot::bootWeb($paths));

