<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Dashboard::index');
$routes->get('/dashboard', 'Dashboard::index');
$routes->post('/dashboard/upload', 'Dashboard::upload');
$routes->get('/dashboard/metadata/(:num)', 'Dashboard::metadata/$1');
$routes->post('/dashboard/processMetadata', 'Dashboard::processMetadata');
$routes->get('/dashboard/edit-metadata/(:num)', 'Dashboard::editMetadata/$1');
$routes->post('/dashboard/updateMetadata', 'Dashboard::updateMetadata');
$routes->post('/dashboard/deleteUpload', 'Dashboard::deleteUpload');
$routes->get('/dashboard/download', 'Dashboard::download');
$routes->post('/dashboard/setLanguage', 'Dashboard::setLanguage');

// Security Monitoring
// $routes->get('security-monitoring', 'SecurityMonitoring::index');
// $routes->get('api/security/threats', 'SecurityMonitoring::getThreats');
// $routes->get('api/security/statistics', 'SecurityMonitoring::getStatistics');
// $routes->get('api/security/trend', 'SecurityMonitoring::getTrend');
// $routes->get('api/security/threat/(:num)', 'SecurityMonitoring::getThreatDetail/$1');
// $routes->get('api/security/filter/severity/(:alpha)', 'SecurityMonitoring::filterBySeverity/$1');
// $routes->get('api/security/filter/status/(:alpha)', 'SecurityMonitoring::filterByStatus/$1');
// $routes->get('api/security/export', 'SecurityMonitoring::exportThreats');

$routes->get('security-monitoring', 'SecurityMonitoring::index');
$routes->get('api/security/threats', 'SecurityMonitoring::getThreats');
$routes->get('api/security/export', 'SecurityMonitoring::export');
