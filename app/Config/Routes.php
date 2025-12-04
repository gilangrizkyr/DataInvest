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
