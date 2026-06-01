<?php

// Autoload des classes core
require_once '../app/core/Database.php';
require_once '../app/core/Router.php';

// Instancie le router
$router = new Router();

// -------------------------------------------------------
// Déclare toutes tes routes ici
// -------------------------------------------------------

$router->get('/',                      'HomeController',        'index');
$router->get('/celebrities',           'CelebritiesController', 'index');
$router->get('/celebrities/:slug',     'CelebritiesController', 'show');
$router->get('/series',                'SeriesController',      'index');
$router->get('/series/:slug',          'SeriesController',      'show');
$router->get('/looks/:id',             'LooksController',       'show');

// Lance le routing
// -------------------------------------------------------
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($uri);