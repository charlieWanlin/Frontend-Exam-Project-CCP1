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
$router->get('/api/celebrities',         'CelebritiesController', 'api'); // route API pour les requêtes AJAX de la page célébrités
$router->get('/celebrities/:slug',     'CelebritiesController', 'show');
$router->get('/api/celebrities/:id/looks', 'CelebritiesController', 'apiLooks'); // route API pour les requêtes AJAX de la page détail célébrité
$router->get('/series',                'SeriesController',      'index');
$router->get('/api/series',            'SeriesController',      'api'); // route API pour les requêtes AJAX de la page séries
$router->get('/series/:slug',          'SeriesController',      'show');
$router->get('/api/series/:id/looks',   'SeriesController',      'apiLooks'); // route API pour les requêtes AJAX de la page détail série
$router->get('/looks/:id',             'LooksController',       'show');
$router->get('/style-finder', 'StyleFinderController', 'index');
$router->get('/magazine', 'MagazineController', 'index');

// Lance le routing
// -------------------------------------------------------
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($uri);