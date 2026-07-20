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
$routes->get('/transfert-multiple', 'ClientController::transfertMultiple');
$routes->post('/transfert-multiple', 'ClientController::transfertMultiple');
$routes->get('/historique', 'ClientController::voirHistorique');

// Espace opérateur
$routes->get('/operateur', 'TransactionController::index');
$routes->get('/operateur/gains', 'TransactionController::gains');
$routes->get('/operateur/clients', 'TransactionController::listeClient');
$routes->get('/operateur/clients/(:num)', 'TransactionController::voirTransactionClient/$1');
$routes->get('/operateur/baremes', 'BaremeController::index');
$routes->get('/operateur/baremes/ajouter', 'BaremeController::ajouter');
$routes->post('/operateur/baremes/ajouter', 'BaremeController::ajouter');
$routes->get('/operateur/baremes/modifier/(:num)', 'BaremeController::modifier/$1');
$routes->post('/operateur/baremes/modifier/(:num)', 'BaremeController::modifier/$1');
$routes->post('/operateur/baremes/supprimer/(:num)', 'BaremeController::supprimer/$1');
$routes->get('/solde', 'ClientController::voirSolde');