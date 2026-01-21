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
 * SETUP PATH INFO - PENTING UNTUK TANPA mod_rewrite
 *---------------------------------------------------------------
 */

// Default ke dashboard
$_SERVER['PATH_INFO'] = '/dashboard';
$_SERVER['REQUEST_URI'] = '/dashboard';
$_SERVER['QUERY_STRING'] = '';

// Coba dari query string ?r=/dashboard
if (!empty($_GET['r'])) {
    $_SERVER['PATH_INFO'] = '/' . ltrim($_GET['r'], '/');
    $_SERVER['REQUEST_URI'] = $_SERVER['PATH_INFO'];
}

// Atau dari REQUEST_URI (jika index.php tidak di rewrite)
$requestUri = $_SERVER['REQUEST_URI'] ?? '/dashboard';

// Bersihkan REQUEST_URI dari query string
if (($pos = strpos($requestUri, '?')) !== false) {
    $requestUri = substr($requestUri, 0, $pos);
}

// Hapus /index.php dari path jika ada
$requestUri = preg_replace('#/index\.php.*$#', '', $requestUri);
$requestUri = preg_replace('#/DataInvest/public.*$#', '', $requestUri);

// Set ke dashboard jika kosong atau root
if (empty($requestUri) || $requestUri === '/') {
    $requestUri = '/dashboard';
}

$_SERVER['PATH_INFO'] = $requestUri;
$_SERVER['REQUEST_URI'] = $requestUri;

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

// Debug - uncomment untuk test
// echo "PATH_INFO: " . $_SERVER['PATH_INFO'] . "<br>";
// echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "<br>";
// exit;

exit(Boot::bootWeb($paths));

