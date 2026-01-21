<?php
/**
 * Simple Test - Tanpa Framework Bootstrap
 */

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(FCPATH);

echo "<h1>Simple Request Test</h1>";

echo "<h2>Server Info</h2>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "<br>";
echo "PATH_INFO: " . ($_SERVER['PATH_INFO'] ?? 'NOT SET') . "<br>";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'NOT SET') . "<br>";
echo "PHP_SELF: " . ($_SERVER['PHP_SELF'] ?? 'NOT SET') . "<br>";
echo "QUERY_STRING: " . ($_SERVER['QUERY_STRING'] ?? 'NOT SET') . "<br>";

echo "<h2>GET Parameters</h2>";
echo "<pre>" . print_r($_GET, true) . "</pre>";

// Parse URI manually
$uri = $_SERVER['REQUEST_URI'] ?? '/dashboard';
echo "<h2>Parsed URI</h2>";
echo "Raw URI: $uri<br>";

// Remove query string
if (($pos = strpos($uri, '?')) !== false) {
    $uri = substr($uri, 0, $pos);
}
echo "Clean URI: $uri<br>";

// Remove /index.php
$uri = str_replace('/index.php', '', $uri);
echo "After index.php removal: $uri<br>";

// Get route
$route = empty($uri) || $uri === '/' ? 'dashboard' : ltrim($uri, '/');
echo "<h2>Final Route</h2>";
echo "Route: $route<br>";

// Check if routes file has this route
echo "<h2>Route Check</h2>";
$routesFile = '../app/Config/Routes.php';
$content = file_get_contents($routesFile);

// Check for dashboard route
if (preg_match("/\\\$routes->get\(['\"]\/dashboard['\"]/", $content)) {
    echo "✓ Dashboard route found in Routes.php<br>";
} else {
    echo "✗ Dashboard route NOT found in Routes.php<br>";
}

// Check for dashboard/index
if (preg_match("/\\\$routes->get\(['\"]\/['\"].*Dashboard::index/", $content)) {
    echo "✓ Root route (/) -> Dashboard::index found<br>";
} else {
    echo "✗ Root route (/) NOT found<br>";
}

echo "<h2>Conclusion</h2>";
if (isset($_GET['r']) && $_GET['r'] === '/dashboard') {
    echo "URL yang harusnya bisa diakses: index.php?r=/dashboard<br>";
    echo "Route yang diminta: dashboard<br>";
    echo "Jika ini tidak bekerja, ada masalah dengan CI4 bootstrap, bukan routes.";
}

