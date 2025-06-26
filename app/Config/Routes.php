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
$routes->get('cart/add', 'CartController::addTocart');
$routes->post('cart/add', ['App\\Controllers\\CartController', 'addToCart']);
$routes->get('cart', ['App\\Controllers\\CartController', 'viewCart']);
$routes->get('cart/count', ['App\\Controllers\\CartController', 'count']);
$routes->get('cart/remove/(:num)', ['App\Controllers\CartController', 'removeFromCart/$1']);
$routes->get('logout', ['Auth', 'logout']);
