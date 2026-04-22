<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'HomeController::index');

// Auth
$routes->get('register', 'AuthController::register');
$routes->post('register', 'AuthController::registerPost');
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::loginPost');
$routes->get('logout', 'AuthController::logout');

// Items (public browsing)
$routes->get('items', 'ItemController::index');
$routes->get('items/(:num)', 'ItemController::show/$1');

// Items (auth required — managed by filter)
$routes->group('my', ['filter' => 'auth'], function($routes) {
    $routes->get('listings', 'ItemController::myListings');
    $routes->get('listings/create', 'ItemController::create');
    $routes->post('listings/create', 'ItemController::store');
    $routes->get('listings/edit/(:num)', 'ItemController::edit/$1');
    $routes->post('listings/edit/(:num)', 'ItemController::update/$1');
    $routes->get('listings/delete/(:num)', 'ItemController::delete/$1');
});

// Admin (admin filter)
$routes->group('admin', ['filter' => 'admin'], function($routes) {
    $routes->get('/', 'AdminController::index');
    $routes->get('categories', 'CategoryController::index');
    $routes->get('categories/create', 'CategoryController::create');
    $routes->post('categories/create', 'CategoryController::store');
    $routes->get('categories/edit/(:num)', 'CategoryController::edit/$1');
    $routes->post('categories/edit/(:num)', 'CategoryController::update/$1');
    $routes->get('categories/delete/(:num)', 'CategoryController::delete/$1');
    $routes->get('users', 'AdminController::users');
    $routes->get('items', 'AdminController::items');
    $routes->get('items/delete/(:num)', 'AdminController::deleteItem/$1');
    $routes->get('logs', 'AdminController::logs');
});