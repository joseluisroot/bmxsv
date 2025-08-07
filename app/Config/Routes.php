<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('atletas/(:segment)', 'Atletas::perfil/$1');

$routes->get('migracion', 'Migrations::index');

$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::loginPost');
$routes->get('logout', 'AuthController::logout');

$routes->get('/dashboard', 'PadreController::index', ['filter' => 'auth']);
$routes->get('/padre/atletas', 'PadreController::atletas', ['filter' => 'auth']);

