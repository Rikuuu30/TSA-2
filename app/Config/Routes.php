<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attempt', ['filter' => 'csrf']);
$routes->post('logout', 'Auth::logout', ['filter' => 'csrf']);
// Read-only pages remain public. Only task-management actions require login.
$routes->get('/', 'Home::index');
$routes->get('tasks', 'Tasks::index');
$routes->get('profile', 'Profile::index');
$routes->get('about', 'About::index');
$routes->get('auth-flow', 'AuthFlow::index');

$routes->group('', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->get('tasks/new', 'Tasks::new');
    $routes->post('tasks', 'Tasks::create', ['filter' => 'csrf']);
    $routes->get('tasks/(:num)/edit', 'Tasks::edit/$1');
    $routes->post('tasks/(:num)/update', 'Tasks::update/$1', ['filter' => 'csrf']);
    $routes->post('tasks/(:num)/start', 'Tasks::start/$1', ['filter' => 'csrf']);
    $routes->post('tasks/(:num)/toggle', 'Tasks::toggle/$1', ['filter' => 'csrf']);
    $routes->post('tasks/(:num)/delete', 'Tasks::delete/$1', ['filter' => 'csrf']);
});
