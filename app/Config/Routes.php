<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'ClientController::accueil');
$routes->post('/', 'ClientController::accueil');
$routes->get('/home', 'ClientController::home');
$routes->get('/depot', 'ClientController::faireUnDepot');
$routes->post('/depot', 'ClientController::faireUnDepot');
$routes->get('/retrait', 'ClientController::faireUnRetrait');
$routes->post('/retrait', 'ClientController::faireUnRetrait');
$routes->get('/transfert', 'ClientController::faireUnTransfert');
$routes->post('/transfert', 'ClientController::faireUnTransfert');
$routes->get('/historique', 'ClientController::voirHistorique');
