<?php
/**
 * CI4 Debug - Full Bootstrap
 */

namespace {
    define('ENVIRONMENT', 'development');
    define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
    chdir(FCPATH);

    echo "<h1>CI4 Debug - Full Bootstrap</h1>";
    echo "<h2>PHP Version</h2>" . phpversion() . "<br>";

    echo "<h2>Paths</h2>";
    require FCPATH . '../app/Config/Paths.php';
    $paths = new \Config\Paths();
    echo "App: " . realpath($paths->appDirectory) . "<br>";
    echo "System: " . realpath($paths->systemDirectory) . "<br>";

    echo "<h2>Loading</h2>";
    try {
        // Load vendor autoload
        require FCPATH . '../vendor/autoload.php';
        echo "1. Vendor autoload loaded<br>";

        // Load Config classes
        require $paths->systemDirectory . '/Config/AutoloadConfig.php';
        require $paths->systemDirectory . '/Config/BaseConfig.php';
        require $paths->systemDirectory . '/Config/Constants.php';
        echo "2. Config classes loaded<br>";

        // Initialize Autoloader
        require $paths->systemDirectory . '/Autoloader/Autoloader.php';
        require APPPATH . 'Config/Autoload.php';
        require $paths->systemDirectory . '/Modules/Modules.php';
        require APPPATH . 'Config/Modules.php';

        $autoloader = new \CodeIgniter\Autoloader\Autoloader();
        $autoloader->initialize(
            new \Config\Autoload(),
            new \Config\Modules()
        )->register();
        echo "3. Autoloader initialized<br>";

        // Initialize Services
        require $paths->systemDirectory . '/Config/BaseService.php';
        require $paths->systemDirectory . '/Config/Services.php';
        require APPPATH . 'Config/Services.php';

        // Load RouteCollection
        require $paths->systemDirectory . '/Router/RouteCollection.php';
        require $paths->systemDirectory . '/Router/RouteCollectionInterface.php';
        require $paths->systemDirectory . '/Router/RouteHandler.php';
        require $paths->systemDirectory . '/Router/Router.php';
        require $paths->systemDirectory . '/Router/RouterInterface.php';

        echo "4. Router classes loaded<br>";

        // Create RouteCollection instance
        $routes = new \CodeIgniter\Router\RouteCollection(
            \Config\Services::locator(),
            new \Config\App()
        );
        echo "5. RouteCollection created<br>";

        // Make $routes available to Routes.php
        // This is the key part - we need to include Routes.php with $routes in scope
        echo "6. Including Routes.php...<br>";
        include APPPATH . 'Config/Routes.php';
        echo "6b. Routes.php included<br>";

        // Now get the routes
        $definedRoutes = $routes->getRoutes();
        echo "<h2>Defined Routes (" . count($definedRoutes) . ")</h2>";
        echo "<pre>" . print_r(array_keys($definedRoutes), true) . "</pre>";

    } catch (Throwable $e) {
        echo "<h2>Error</h2>";
        echo "Message: " . $e->getMessage() . "<br>";
        echo "File: " . $e->getFile() . "<br>";
        echo "Line: " . $e->getLine() . "<br>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
}

