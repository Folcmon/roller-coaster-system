<?php declare(strict_types=1);

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->group('api', function ($routes) {
    $routes->post('coasters', 'Api\CoasterController::create');
    $routes->put('coasters/(:segment)', 'Api\CoasterController::update/$1');
    $routes->post('coasters/(:segment)/wagons', 'Api\WagonController::create/$1');
    $routes->delete('coasters/(:segment)/wagons/(:segment)', 'Api\WagonController::delete/$1/$2');
});
