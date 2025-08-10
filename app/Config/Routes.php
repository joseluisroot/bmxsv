<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'HomeController::index');
$routes->get('atletas/(:segment)', 'AtletasController::perfil/$1');

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
