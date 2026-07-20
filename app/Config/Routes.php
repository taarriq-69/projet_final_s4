<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('/depot', 'ClientController::faireUnDepot');
$routes->post('/depot', 'ClientController::faireUnDepot');
$routes->get('/retrait', 'ClientController::faireUnRetrait');
$routes->post('/retrait', 'ClientController::faireUnRetrait');
