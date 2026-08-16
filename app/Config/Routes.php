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

$routes->group('api', static function ($routes) {
    $routes->post('timing/pass', 'Api\TimingController::pass');
    $routes->get('timing/hit/(:num)/summary', 'Api\TimingController::summary/$1');
    $routes->get('timing/session/(:num)/summary', 'Api\TimingController::sessionSummary/$1');

    $routes->get('performance/athlete/(:num)/setup-comparison', 'Api\PerformanceController::setupComparison/$1');
    $routes->get('performance/athlete/(:num)/best-hits', 'Api\PerformanceController::bestHits/$1');
    $routes->get('performance/athlete/(:num)/history', 'Api\PerformanceController::history/$1');
    $routes->get('performance/athlete/(:num)/dashboard', 'Api\PerformanceController::dashboard/$1');
    $routes->get('performance/session/(:num)/ranking', 'Api\PerformanceController::sessionRanking/$1');

    $routes->get('performance/athlete/(:num)/full-dashboard', 'Api\PerformanceController::fullDashboard/$1');
    $routes->get('performance/session/(:num)/coach-dashboard','Api\PerformanceController::coachDashboard/$1');

    $routes->get('performance/session/(:num)/stream','Api\PerformanceController::liveSessionStream/$1');
    $routes->get('performance/hit/(:num)/compare/(:num)','Api\PerformanceController::compareHits/$1/$2');

    $routes->get('performance/athlete/(:num)/compare/(:num)','Api\PerformanceController::compareAthletes/$1/$2');
    $routes->get('performance/club/ranking', 'Api\PerformanceController::clubRanking');
    $routes->get('performance/athlete/(:num)/progress','Api\PerformanceController::athleteProgress/$1');

    $routes->get('performance/session/active', 'Api\PerformanceController::activeSession');

    $routes->post('performance/session/(:num)/status', 'Api\PerformanceController::updateSessionStatus/$1');
    $routes->post('performance/session/(:num)/hit', 'Api\PerformanceController::createSessionHit/$1');

    $routes->get('performance/session/(:num)/hits', 'Api\PerformanceController::sessionHits/$1');
    $routes->get('performance/athletes', 'Api\PerformanceController::athletes');
    $routes->get('performance/configurations', 'Api\PerformanceController::configurations');

});

$routes->get('performance/atleta/(:num)', 'PerformanceController::atleta/$1');
$routes->get('performance/session/(:num)/live', 'PerformanceController::sesionLive/$1');
$routes->get('performance/coach/(:num)', 'PerformanceController::coach/$1');
$routes->get('performance/hit/(:num)/compare/(:num)', 'PerformanceController::compareHits/$1/$2');
$routes->get('performance/athlete/(:num)/compare/(:num)','PerformanceController::compareAthletes/$1/$2');
$routes->get('performance/club/ranking', 'PerformanceController::clubRanking');
$routes->get('performance/session/(:num)/control','PerformanceController::sessionControl/$1');
$routes->get('performance/session/(:num)/simulator','PerformanceController::sessionSimulator/$1');





