<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


$routes->get('auth/login', 'Auth::login');
$routes->post('login/auth', 'Auth::loginAuth');
$routes->get('register', 'Auth::register');
$routes->post('register/save', 'Auth::registerSave');
$routes->get('auth/logout', 'Auth::logout');
$routes->get('cart/add', 'CartController::addToCart');
$routes->post('cart/add', ['App\\Controllers\\CartController', 'addToCart']);
$routes->get('cart', ['App\\Controllers\\CartController', 'viewCart']);
$routes->get('cart/count', ['App\\Controllers\\CartController', 'getCartCount']);
$routes->get('cart/remove/(:num)', ['App\Controllers\CartController', 'removeFromCart/$1']);
$routes->get('/category/(:any)', 'category::index/$1');
$routes->post('cart/updateQuantity/(:num)/(:num)', 'CartController::updateQuantity/$1/$2');

$routes->delete('cart/remove/(:num)', 'CartController::removeFromCart/$1');
$routes->post('cart/saveForLater/(:num)', 'CartController::saveForLater/$1');

