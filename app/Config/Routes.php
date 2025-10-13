<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'HomeController::index');
$routes->get('atleta/(:segment)', 'AtletasController::perfil/$1', ['as' => 'atleta_perfil']);

$routes->get('migracion', 'Migrations::index');

$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::loginPost');
$routes->get('logout', 'AuthController::logout');

$routes->get('/dashboard', 'PadreController::index', ['filter' => 'auth']);
$routes->get('/padre/atletas', 'PadreController::atletas', ['filter' => 'auth']);

$routes->get('seeders', 'Seeders::index');
$routes->get('seeders/(:segment)', 'Seeders::run/$1');

$routes->get('noticias', 'NoticiasController::index');
$routes->get('noticias/(:segment)', 'NoticiasController::detalle/$1');

$routes->get('ranking/periodo/(:num)', 'RankingController::periodo/$1');

$routes->get('galeria', 'GaleriaController::index');
$routes->get('galeria/album/(:segment)', 'GaleriaController::album/$1');

$routes->get('faq', 'Pages::faq');

$routes->get('juegos/focus-numbers', 'JuegosController::focusNumbers');

