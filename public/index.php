<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Router.php';

// Charger les variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

define('APP_ENV', $_ENV['APP_ENV'] ?? 'production');

// Instancie le router
$router = new Router();


// ───────────────────────────────────────────────────────
// PAGES PUBLIQUES
// ───────────────────────────────────────────────────────
$router->get('/',                           'HomeController',        'index');

// Célébrités
$router->get('/celebrities',                'CelebritiesController', 'index');
$router->get('/api/celebrities',            'CelebritiesController', 'api');
$router->get('/celebrities/:slug',          'CelebritiesController', 'show');
$router->get('/api/celebrities/:id/looks',  'CelebritiesController', 'apiLooks');

// Séries
$router->get('/series',                     'SeriesController',      'index');
$router->get('/api/series',                 'SeriesController',      'api');
$router->get('/series/:slug',               'SeriesController',      'show');
$router->get('/api/series/:id/looks',       'SeriesController',      'apiLooks');

// Looks
$router->get('/looks',                      'LooksController',       'index');
$router->get('/api/looks',                  'LooksController',       'api');
$router->get('/looks/:id',                  'LooksController',       'show');

// Style Finder
$router->get('/style-finder',               'StyleFinderController', 'index');
$router->post('/style-finder',              'StyleFinderController', 'analyze');

// Magazine
$router->get('/magazine',                   'MagazineController',    'index');
$router->get('/magazine/:slug',             'MagazineController',    'show');

// Recherche
$router->get('/api/search',                 'SearchController',      'index');


// ───────────────────────────────────────────────────────
// AUTH — pages
// ───────────────────────────────────────────────────────
$router->get('/login',                      'AuthController',        'loginPage');
$router->get('/register',                   'AuthController',        'registerPage');
$router->get('/logout',                     'AuthController',        'logout');

// Confirmation email & reset mot de passe
$router->get('/auth/verify',                'AuthController',        'verify');
$router->get('/auth/reset',                 'AuthController',        'resetPage');

// AUTH — API AJAX
$router->post('/api/auth/login',            'AuthController',        'login');
$router->post('/api/auth/register',         'AuthController',        'register');
$router->post('/api/auth/forgot',           'AuthController',        'forgot');
$router->post('/api/auth/reset',            'AuthController',        'reset');

// Mot de passe oublié
$router->get('/mot-de-passe-oublie',        'AuthController',        'forgotPage');


// ───────────────────────────────────────────────────────
// COMPTE (pages protégées)
// ───────────────────────────────────────────────────────
$router->get('/mon-compte',                 'CompteController',      'index');
$router->get('/mon-compte/modifier',        'CompteController',      'editPage');
$router->post('/api/compte/modifier',       'CompteController',      'update');
$router->post('/api/compte/mot-de-passe',   'CompteController',      'updatePassword');
$router->post('/api/compte/supprimer',      'CompteController',      'delete');


// ───────────────────────────────────────────────────────
// FAVORIS
// ───────────────────────────────────────────────────────
$router->get('/favoris',                    'FavorisController',     'index');
$router->post('/api/favoris/toggle',        'FavorisController',     'toggle');
$router->get('/api/favoris',                'FavorisController',     'api');


// ───────────────────────────────────────────────────────
// PANIER
// ───────────────────────────────────────────────────────
$router->get('/panier',                     'PanierController',      'index');
$router->post('/api/panier/ajouter',        'PanierController',      'ajouter');
$router->post('/api/panier/retirer',        'PanierController',      'retirer');
$router->post('/api/panier/modifier',       'PanierController',      'modifier');
$router->post('/api/panier/vider',          'PanierController',      'vider');
$router->get('/api/panier',                 'PanierController',      'api');


// ───────────────────────────────────────────────────────
// PAGES LÉGALES
// ───────────────────────────────────────────────────────
$router->get('/conditions',                 'PageController',        'conditions');
$router->get('/confidentialite',            'PageController',        'confidentialite');


// ───────────────────────────────────────────────────────
// Dispatcher
// ───────────────────────────────────────────────────────
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($uri);