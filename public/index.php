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

// Cara 1: Dari query string ?r=/dashboard
if (isset($_GET['r'])) {
    $_SERVER['PATH_INFO'] = '/' . ltrim($_GET['r'], '/');
    $_SERVER['REQUEST_URI'] = $_SERVER['PATH_INFO'];
    $_SERVER['QUERY_STRING'] = 'r=' . $_GET['r'];
    parse_str($_SERVER['QUERY_STRING'], $_GET);
}
// Cara 2: Dari PATH_INFO (jika diset server)
elseif (isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO'] !== '') {
    // PATH_INFO sudah ada
}
// Cara 3: Dari REQUEST_URI (fallback)
elseif (isset($_SERVER['REQUEST_URI'])) {
    $uri = parse_url($_SERVER['REQUEST_URI']);
    $path = $uri['path'] ?? '/';
    
    // Hapus /index.php dari path
    $path = str_replace('/index.php', '', $path);
    $path = str_replace('/DataInvest/public/index.php', '', $path);
    
    if (!empty($path) && $path !== '/') {
        $_SERVER['PATH_INFO'] = $path;
        $_SERVER['REQUEST_URI'] = $path;
    } else {
        $_SERVER['PATH_INFO'] = '/dashboard';
        $_SERVER['REQUEST_URI'] = '/dashboard';
    }
}

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


