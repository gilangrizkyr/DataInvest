<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Dashboard::index');
$routes->get('/dashboard', 'Dashboard::index');
$routes->post('/dashboard/upload', 'Dashboard::upload');
$routes->get('/dashboard/download', 'Dashboard::download');
