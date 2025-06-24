<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


$routes->get('login', 'Auth::login');
$routes->post('login/auth', 'Auth::loginAuth');
$routes->get('register', 'Auth::register');
$routes->post('register/save', 'Auth::registerSave');
$routes->get('logout', 'Auth::logout');
