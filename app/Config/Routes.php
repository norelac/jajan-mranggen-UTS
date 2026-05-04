<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ─── Public Routes ──────────────────────────────────────────────────────────
$routes->get('/', 'Home::index');
$routes->get('/produk', 'Home::products');
$routes->get('/produk/(:segment)', 'Home::productDetail/$1');
$routes->get('/kategori/(:segment)', 'Home::category/$1');

// ─── Auth Routes ─────────────────────────────────────────────────────────────
$routes->group('auth', function ($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::loginProcess');
    $routes->get('register', 'Auth::register');
    $routes->post('register', 'Auth::registerProcess');
    $routes->get('logout', 'Auth::logout', ['filter' => 'auth']);
});

// ─── Admin Routes ─────────────────────────────────────────────────────────────
$routes->group('admin', ['filter' => 'admin'], function ($routes) {
    $routes->get('/', 'Admin\Dashboard::index');
    $routes->get('dashboard', 'Admin\Dashboard::index');

    // Users CRUD
    $routes->get('users', 'Admin\Users::index');
    $routes->get('users/create', 'Admin\Users::create');
    $routes->post('users/store', 'Admin\Users::store');
    $routes->get('users/edit/(:num)', 'Admin\Users::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\Users::update/$1');
    $routes->get('users/delete/(:num)', 'Admin\Users::delete/$1');

    // Categories CRUD
    $routes->get('categories', 'Admin\Categories::index');
    $routes->get('categories/create', 'Admin\Categories::create');
    $routes->post('categories/store', 'Admin\Categories::store');
    $routes->get('categories/edit/(:num)', 'Admin\Categories::edit/$1');
    $routes->post('categories/update/(:num)', 'Admin\Categories::update/$1');
    $routes->get('categories/delete/(:num)', 'Admin\Categories::delete/$1');

    // Products CRUD
    $routes->get('products', 'Admin\Products::index');
    $routes->get('products/create', 'Admin\Products::create');
    $routes->post('products/store', 'Admin\Products::store');
    $routes->get('products/edit/(:num)', 'Admin\Products::edit/$1');
    $routes->post('products/update/(:num)', 'Admin\Products::update/$1');
    $routes->get('products/delete/(:num)', 'Admin\Products::delete/$1');

    // Orders
    $routes->get('orders', 'Admin\Orders::index');
    $routes->get('orders/(:num)', 'Admin\Orders::show/$1');
    $routes->post('orders/updateStatus/(:num)', 'Admin\Orders::updateStatus/$1');
});

// ─── Penjual Routes ───────────────────────────────────────────────────────────
$routes->group('penjual', ['filter' => 'penjual'], function ($routes) {
    $routes->get('/', 'Penjual\Dashboard::index');
    $routes->get('dashboard', 'Penjual\Dashboard::index');

    // Products CRUD
    $routes->get('products', 'Penjual\Products::index');
    $routes->get('products/create', 'Penjual\Products::create');
    $routes->post('products/store', 'Penjual\Products::store');
    $routes->get('products/edit/(:num)', 'Penjual\Products::edit/$1');
    $routes->post('products/update/(:num)', 'Penjual\Products::update/$1');
    $routes->get('products/delete/(:num)', 'Penjual\Products::delete/$1');

    // Orders
    $routes->get('orders', 'Penjual\Orders::index');
    $routes->get('orders/(:num)', 'Penjual\Orders::show/$1');
    $routes->post('orders/updateStatus/(:num)', 'Penjual\Orders::updateStatus/$1');
});

// ─── Pembeli Routes ───────────────────────────────────────────────────────────
$routes->group('pembeli', ['filter' => 'pembeli'], function ($routes) {
    $routes->get('/', 'Pembeli\Dashboard::index');
    $routes->get('dashboard', 'Pembeli\Dashboard::index');

    // Cart
    $routes->get('cart', 'Pembeli\Cart::index');
    $routes->post('cart/add', 'Pembeli\Cart::add');
    $routes->post('cart/update', 'Pembeli\Cart::update');
    $routes->get('cart/remove/(:num)', 'Pembeli\Cart::remove/$1');
    $routes->get('cart/clear', 'Pembeli\Cart::clear');

    // Orders
    $routes->get('orders', 'Pembeli\Orders::index');
    $routes->post('orders/checkout', 'Pembeli\Orders::checkout');
    $routes->get('orders/(:num)', 'Pembeli\Orders::show/$1');
    $routes->post('orders/cancel/(:num)', 'Pembeli\Orders::cancel/$1');

    // Profile
    $routes->get('profile', 'Pembeli\Profile::index');
    $routes->post('profile/update', 'Pembeli\Profile::update');
});
