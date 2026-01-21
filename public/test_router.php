<?php
/**
 * Simple Router Test - Tanpa mod_rewrite
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Router Test</h1>";

$route = '';

// 1. Coba dari ?r=
if (isset($_GET['r'])) {
    $route = ltrim($_GET['r'], '/');
    echo "Route dari ?r=: $route<br>";
}
// 2. Dari REQUEST_URI
elseif (isset($_SERVER['REQUEST_URI'])) {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    echo "REQUEST_URI: $uri<br>";
    
    // Hapus index.php dari URI
    $uri = preg_replace('#/index\.php.*#', '', $uri);
    $uri = preg_replace('#^/DataInvest/public#', '', $uri);
    $route = ltrim($uri, '/');
    echo "Route dari REQUEST_URI: $route<br>";
}

echo "<h2>Final Route: '$route'</h2>";

if (empty($route) || $route === '/') {
    echo "→ Dashboard (default)";
} else {
    echo "→ Route: $route";
}

