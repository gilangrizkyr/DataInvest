<?php
echo "PHP Working! Time: " . date('Y-m-d H:i:s');
echo "<br>PHP Version: " . PHP_VERSION;
echo "<br>Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'N/A');
echo "<br>SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'N/A');
echo "<br>REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A');
echo "<br>PATH_INFO: " . ($_SERVER['PATH_INFO'] ?? 'NOT SET');
echo "<br>QUERY_STRING: " . ($_SERVER['QUERY_STRING'] ?? 'NOT SET');

