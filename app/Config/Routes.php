<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/auth/login', 'Auth::login', ['filter' => 'auth:guest']);
$routes->post('/auth/process-login', 'Auth::processLogin');
$routes->get('/auth/logout', 'Auth::logout');
$routes->get('/auth/forgot-password', 'Auth::forgotPassword');
$routes->post('/auth/process-forgot-password', 'Auth::processForgotPassword');
$routes->get('/auth/reset-password/(:segment)', 'Auth::resetPassword/$1');
$routes->post('/auth/process-reset-password', 'Auth::processResetPassword');

// DASHBOARD ROUTES
$routes->get('/', 'Dashboard::index', ['filter' => 'roleFilter']);
$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'roleFilter']);
$routes->post('/dashboard/upload', 'Dashboard::upload', ['filter' => 'roleFilter']);
$routes->get('/dashboard/metadata/(:num)', 'Dashboard::metadata/$1', ['filter' => 'roleFilter']);
$routes->post('/dashboard/processMetadata', 'Dashboard::processMetadata', ['filter' => 'roleFilter']);
$routes->get('/dashboard/edit-metadata/(:num)', 'Dashboard::editMetadata/$1', ['filter' => 'roleFilter']);
$routes->post('/dashboard/updateMetadata', 'Dashboard::updateMetadata', ['filter' => 'roleFilter']);
$routes->post('/dashboard/deleteUpload', 'Dashboard::deleteUpload', ['filter' => 'roleFilter']);
$routes->get('/dashboard/download', 'Dashboard::download', ['filter' => 'roleFilter']);
$routes->post('/dashboard/setLanguage', 'Dashboard::setLanguage', ['filter' => 'roleFilter']);

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
    $routes->get('delete/(:num)', 'UserManagement::delete/$1');
});

// FAQ ROUTE
$routes->get('/faq', 'Faq::index');
$routes->get('/faq', 'Faq::index', ['filter' => 'roleFilter']);