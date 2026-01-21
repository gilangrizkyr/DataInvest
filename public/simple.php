<?php
/**
 * Simple entry point - bypasses CI4 routing entirely
 * For servers WITHOUT mod_rewrite
 */

// Set environment
define('ENVIRONMENT', 'development');
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(FCPATH);

// Get the path
$path = $_SERVER['REQUEST_URI'] ?? '/';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';

// Remove query string
$path = strtok($path, '?');

// Remove script name
$path = str_replace($scriptName, '', $path);
$path = str_replace('/DataInvest/public', '', $path);
$path = str_replace('/DataInvest', '', $path);

// Clean path
$path = '/' . ltrim($path, '/');
$path = rtrim($path, '/');
if ($path === '') $path = '/';

// Remove leading/trailing slashes
$segments = array_filter(explode('/', $path));
$segments = array_values($segments);

// Bootstrap CI4 manually
require FCPATH . '../app/Config/Paths.php';
require FCPATH . '../vendor/autoload.php';

$paths = new Config\Paths();

// Load required system files
require $paths->systemDirectory . '/Common.php';
require $paths->systemDirectory . '/Config/BaseConfig.php';
require $paths->appDirectory . '/Config/App.php';
require $paths->systemDirectory . '/Config/Constants.php';

// Load the controller
require FCPATH . '../app/Controllers/BaseController.php';
require FCPATH . '../app/Controllers/Dashboard.php';
require FCPATH . '../app/Controllers/Auth.php';

// Get the controller and method
$controllerName = !empty($segments[0]) ? $segments[0] : 'dashboard';
$method = !empty($segments[1]) ? $segments[1] : 'index';
$params = array_slice($segments, 2);

// Map URL to controller
$controllerMap = [
    'dashboard' => 'Dashboard',
    'auth' => 'Auth',
    'login' => 'Auth',
    'logout' => 'Auth',
    'security-monitoring' => 'SecurityMonitoring',
    'user-management' => 'UserManagement',
    'faq' => 'Faq',
];

// Check if we need to redirect to login first
$session = \Config\Services::session();
$loginController = new \App\Controllers\Auth();

if (in_array($controllerName, ['dashboard', 'security-monitoring', 'user-management'])) {
    if (empty($session->get('logged_in'))) {
        header('Location: /DataInvest/public/simple.php/auth/login');
        exit;
    }
}

// Route to appropriate controller
switch ($controllerName) {
    case 'dashboard':
    case 'login':
    case 'logout':
    case 'auth':
        $controller = $loginController;
        break;
    default:
        $controller = new \App\Controllers\Dashboard();
}

// Call the method with params
if (method_exists($controller, $method)) {
    call_user_func_array([$controller, $method], $params);
} else {
    // Try index as fallback
    if (method_exists($controller, 'index')) {
        call_user_func([$controller, 'index']);
    } else {
        echo "404 - Page not found";
    }
}

