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
 * FIX URI FOR TANPA mod_rewrite
 *---------------------------------------------------------------
 */

// Get the path from REQUEST_URI
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
$basePath = str_replace('index.php', '', $scriptName);

// Extract path info
$path = $requestUri;

// Remove query string
$path = strtok($path, '?');

// Remove base path
$path = str_replace($basePath, '', $path);

// Remove index.php
$path = str_replace('index.php', '', $path);

// Clean up path
$path = '/' . ltrim($path, '/');

// Set PATH_INFO
$_SERVER['PATH_INFO'] = $path;
$_SERVER['SCRIPT_NAME'] = $scriptName;

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 */

// ⬇️ NAIK SATU FOLDER (KELUAR DARI public)
require dirname(__DIR__) . '/app/Config/Paths.php';
require dirname(__DIR__) . '/vendor/autoload.php';

$paths = new Paths();

// Load framework
require $paths->systemDirectory . '/Boot.php';

exit(Boot::bootWeb($paths));
