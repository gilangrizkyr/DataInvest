<?php

use CodeIgniter\Router\RouteCollection;


/**
 * @var RouteCollection $routes
 */

// HOME ROUTE - Public landing page
$routes->get('/', 'Home::index');
$routes->get('/api/public/data', 'Home::apiData');

// AUTH ROUTES
$routes->get('/auth/login', 'Auth::login', ['filter' => 'auth:guest']);
$routes->post('/auth/process-login', 'Auth::processLogin');
$routes->get('/auth/logout', 'Auth::logout');
$routes->get('/auth/forgot-password', 'Auth::forgotPassword');
$routes->post('/auth/process-forgot-password', 'Auth::processForgotPassword');
$routes->get('/auth/reset-password/(:segment)', 'Auth::resetPassword/$1');
$routes->post('/auth/process-reset-password', 'Auth::processResetPassword');

// DASHBOARD ROUTES
$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'roleFilter']);
$routes->get('/profile', 'Dashboard::profile', ['filter' => 'auth']);
$routes->post('/dashboard/upload', 'Dashboard::upload', ['filter' => 'roleFilter']);
$routes->get('/dashboard/metadata/(:num)', 'Dashboard::metadata/$1', ['filter' => 'roleFilter']);
$routes->post('/dashboard/processMetadata', 'Dashboard::processMetadata', ['filter' => 'roleFilter']);
$routes->get('/dashboard/editMetadata/(:num)', 'Dashboard::editMetadata/$1', ['filter' => 'roleFilter']);
$routes->post('/dashboard/updateMetadata', 'Dashboard::updateMetadata', ['filter' => 'roleFilter']);
$routes->post('/dashboard/deleteUpload', 'Dashboard::deleteUpload', ['filter' => 'roleFilter']);
$routes->get('/dashboard/download', 'Dashboard::download', ['filter' => 'roleFilter']);
$routes->post('/dashboard/setLanguage', 'Dashboard::setLanguage', ['filter' => 'roleFilter']);
$routes->get('/dashboard/logs', 'Dashboard::logs', ['filter' => 'roleFilter']);
$routes->get('/dashboard/settings', 'Dashboard::settings', ['filter' => 'roleFilter']);
$routes->get('/logs', 'Dashboard::logs', ['filter' => 'roleFilter']);
$routes->get('/settings', 'Dashboard::settings', ['filter' => 'roleFilter']);

// SECURITY MONITORING ROUTES
$routes->get('security-monitoring', 'SecurityMonitoring::index', ['filter' => 'roleFilter']);
$routes->get('api/security/threats', 'SecurityMonitoring::getThreats', ['filter' => 'roleFilter']);
$routes->get('api/security/export', 'SecurityMonitoring::export', ['filter' => 'roleFilter']);

// USER MANAGEMENT ROUTES
$routes->group('user-management', ['filter' => 'roleFilter:superadmin'], function ($routes) {
    $routes->get('/', 'UserManagement::index');
    $routes->get('create', 'UserManagement::create');
    $routes->post('store', 'UserManagement::store');
    $routes->get('edit/(:num)', 'UserManagement::edit/$1');
    $routes->post('update/(:num)', 'UserManagement::update/$1');
    $routes->delete('delete/(:num)', 'UserManagement::delete/$1');
    $routes->get('delete/(:num)', 'UserManagement::delete/$1'); // Keep GET for direct link support
});

// FAQ ROUTE - Public
$routes->get('/faq', 'Faq::index');

$routes->get('dashboard/download-sector-lkpm', 'Dashboard::downloadSectorLKPM');