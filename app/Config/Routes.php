<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'UserController::index');
$routes->get('dashboard', 'PayrollController::index');
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::attemptLogin');
$routes->get('logout', 'AuthController::logout');

$routes->group('users', static function ($routes) {
    $routes->get('/', 'UserController::index');
    $routes->get('create', 'UserController::create');
    $routes->post('store', 'UserController::store');
    $routes->get('edit/(:num)', 'UserController::edit/$1');
    $routes->post('update/(:num)', 'UserController::update/$1');
    $routes->get('view/(:num)', 'UserController::view/$1');
    $routes->post('delete/(:num)', 'UserController::delete/$1');
    $routes->post('toggle-status/(:num)', 'UserController::toggleStatus/$1');
});

$routes->group('contractors', static function ($routes) {
    $routes->get('/', 'ContractorController::index');
    $routes->get('create', 'ContractorController::create');
    $routes->post('store', 'ContractorController::store');
    $routes->get('edit/(:num)', 'ContractorController::edit/$1');
    $routes->post('update/(:num)', 'ContractorController::update/$1');
    $routes->get('view/(:num)', 'ContractorController::view/$1');
    $routes->post('delete/(:num)', 'ContractorController::delete/$1');
    $routes->post('toggle-status/(:num)', 'ContractorController::toggleStatus/$1');
});

$routes->group('employees', static function ($routes) {
    $routes->get('/', 'EmployeeController::index');
    $routes->get('create', 'EmployeeController::create');
    $routes->post('store', 'EmployeeController::store');
    $routes->get('view/(:num)', 'EmployeeController::view/$1');
    $routes->get('edit/(:num)', 'EmployeeController::edit/$1');
    $routes->post('update/(:num)', 'EmployeeController::update/$1');
    $routes->post('update-salary/(:num)', 'EmployeeController::updateSalary/$1');
    $routes->post('delete/(:num)', 'EmployeeController::delete/$1');
    $routes->post('toggle-status/(:num)', 'EmployeeController::toggleStatus/$1');
});

$routes->group('payroll', static function ($routes) {
    $routes->get('/', 'PayrollController::index');
    $routes->get('month/(:num)/(:num)', 'PayrollController::month/$1/$2');
});

$routes->group('attendance', static function ($routes) {
    $routes->get('/', 'PayrollController::index');
    $routes->get('month/(:num)/(:num)', 'PayrollController::month/$1/$2');
});



