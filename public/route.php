<?php
/**
 * Alternative entry point untuk server TANPA mod_rewrite
 * Gunakan URL: https://domain.com/DataInvest/public/route.php/path/to/route
 */

// Set path info manually jika tidak tersedia
if (!isset($_SERVER['PATH_INFO']) || empty($_SERVER['PATH_INFO'])) {
    $requestUri = $_SERVER['REQUEST_URI'];
    $scriptName = $_SERVER['SCRIPT_NAME'];
    
    // Extract path dari request URI
    $path = str_replace($scriptName, '', $requestUri);
    $path = str_replace('/public/route.php', '', $path);
    $path = str_replace('/DataInvest/public/route.php', '', $path);
    
    // Remove query string
    $path = strtok($path, '?');
    
    $_SERVER['PATH_INFO'] = $path ?: '/';
}

// Bootstrap CodeIgniter
require_once __DIR__ . '/../app/Config/Paths.php';
require_once dirname(__DIR__) . '/vendor/autoload.php';

$paths = new \Config\Paths();
require $paths->systemDirectory . '/Boot.php';

\CodeIgniter\Boot::bootWeb($paths);

