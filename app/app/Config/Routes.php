<?php declare(strict_types=1);

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->group('api', function ($routes) {
    $routes->post('coasters', 'Api\CoasterController::create');
    $routes->get('coasters', 'Api\CoasterController::index');
    $routes->get('coasters/personnel', 'Api\CoasterController::getPersonnel');
    $routes->put('coasters/personnel', 'Api\CoasterController::setPersonnel');
    $routes->get('coasters/status', 'Api\CoasterController::status');
    $routes->get('coasters/(:segment)/status', 'Api\CoasterController::coasterStatus/$1');
    $routes->put('coasters/(:segment)', 'Api\CoasterController::update/$1');
    $routes->post('coasters/(:segment)/wagons', 'Api\WagonController::create/$1');
    $routes->delete('coasters/(:segment)/wagons/(:segment)', 'Api\WagonController::delete/$1/$2');
    $routes->get('coasters/(:segment)/wagons', 'Api\WagonController::index/$1');
    $routes->get('coasters/(:segment)', 'Api\CoasterController::show/$1');
});
$routes->get('rollercoasters', 'RollercoasterController::index');
$routes->get('rollercoasters/(:segment)/wagons', 'RollercoasterController::wagons/$1');
$routes->get('rollercoasters/personnel', 'RollercoasterController::personnel');
