<?php

use CodeIgniter\Router\RouteCollection;

/**
 * Konfigurasi Routes ShopEase — Tugas Minggu 12 (CRUD Kategori, Produk, Foto, Karyawan & Katalog)
 * Mahasiswa: Farrel Prastita Ramadhan (5025241219)
 *
 * @var RouteCollection $routes
 */
$routes->setAutoRoute(false);

// Preferensi bahasa dwibahasa (ID / EN)
$routes->get('prefs/lang/(:alpha)', 'PreferenceController::setLang/$1', ['as' => 'prefs.lang']);

// Storefront & Katalog Publik
$routes->get('/', 'CatalogController::home', ['as' => 'home']);
$routes->get('catalog', 'CatalogController::index', ['as' => 'catalog.index']);
$routes->get('product/(:segment)', 'CatalogController::detail/$1', ['as' => 'catalog.detail']);

// Autentikasi
$routes->group('', ['filter' => 'guest'], static function ($routes) {
    $routes->get('login', 'AuthController::login', ['as' => 'auth.login']);
    $routes->post('login', 'AuthController::authenticate', ['as' => 'auth.authenticate']);
    $routes->get('register', 'AuthController::register', ['as' => 'auth.register']);
    $routes->post('register', 'AuthController::store', ['as' => 'auth.store']);
});
$routes->post('logout', 'AuthController::logout', ['filter' => 'auth', 'as' => 'auth.logout']);

// Profil akun (semua user login)
$routes->group('profile', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'AuthController::profile', ['as' => 'profile.index']);
    $routes->post('update', 'AuthController::updateProfile', ['as' => 'profile.update']);
});

// Workspace Administrator (Kategori, Produk + Galeri, Karyawan)
$routes->group('admin', ['filter' => 'auth:admin'], static function ($routes) {
    $routes->get('/', 'Admin\ProductController::index', ['as' => 'admin.dashboard']);

    // CRUD Kategori
    $routes->get('categories', 'Admin\CategoryController::index', ['as' => 'admin.categories']);
    $routes->get('categories/create', 'Admin\CategoryController::create', ['as' => 'admin.categories.create']);
    $routes->post('categories/store', 'Admin\CategoryController::store', ['as' => 'admin.categories.store']);
    $routes->get('categories/(:num)/edit', 'Admin\CategoryController::edit/$1', ['as' => 'admin.categories.edit']);
    $routes->post('categories/(:num)/update', 'Admin\CategoryController::update/$1', ['as' => 'admin.categories.update']);
    $routes->post('categories/(:num)/delete', 'Admin\CategoryController::delete/$1', ['as' => 'admin.categories.delete']);

    // CRUD Produk & Pengelolaan Foto
    $routes->get('products', 'Admin\ProductController::index', ['as' => 'admin.products']);
    $routes->get('products/create', 'Admin\ProductController::create', ['as' => 'admin.products.create']);
    $routes->post('products/store', 'Admin\ProductController::store', ['as' => 'admin.products.store']);
    $routes->get('products/(:num)/edit', 'Admin\ProductController::edit/$1', ['as' => 'admin.products.edit']);
    $routes->post('products/(:num)/update', 'Admin\ProductController::update/$1', ['as' => 'admin.products.update']);
    $routes->post('products/(:num)/delete', 'Admin\ProductController::delete/$1', ['as' => 'admin.products.delete']);
    $routes->post('products/(:num)/images/upload', 'Admin\ProductController::uploadImages/$1', ['as' => 'admin.products.images.upload']);
    $routes->post('products/(:num)/images/(:num)/primary', 'Admin\ProductController::setPrimaryImage/$1/$2', ['as' => 'admin.products.images.primary']);
    $routes->post('products/(:num)/images/(:num)/delete', 'Admin\ProductController::deleteImage/$1/$2', ['as' => 'admin.products.images.delete']);

    // CRUD Direktori Karyawan (Khusus Administrator)
    $routes->get('employees', 'Admin\EmployeeController::index', ['as' => 'admin.employees']);
    $routes->get('employees/create', 'Admin\EmployeeController::create', ['as' => 'admin.employees.create']);
    $routes->post('employees/store', 'Admin\EmployeeController::store', ['as' => 'admin.employees.store']);
    $routes->get('employees/(:num)', 'Admin\EmployeeController::show/$1', ['as' => 'admin.employees.show']);
    $routes->get('employees/(:num)/edit', 'Admin\EmployeeController::edit/$1', ['as' => 'admin.employees.edit']);
    $routes->post('employees/(:num)/update', 'Admin\EmployeeController::update/$1', ['as' => 'admin.employees.update']);
    $routes->post('employees/(:num)/delete', 'Admin\EmployeeController::delete/$1', ['as' => 'admin.employees.delete']);
});

// Workspace Operator (Kategori & Produk; tidak bisa mengakses Direktori Karyawan)
$routes->group('operator', ['filter' => 'auth:operator,admin'], static function ($routes) {
    $routes->get('/', 'Admin\ProductController::index', ['as' => 'operator.dashboard']);

    $routes->get('categories', 'Admin\CategoryController::index', ['as' => 'operator.categories']);
    $routes->get('categories/create', 'Admin\CategoryController::create');
    $routes->post('categories/store', 'Admin\CategoryController::store');
    $routes->get('categories/(:num)/edit', 'Admin\CategoryController::edit/$1');
    $routes->post('categories/(:num)/update', 'Admin\CategoryController::update/$1');
    $routes->post('categories/(:num)/delete', 'Admin\CategoryController::delete/$1');

    $routes->get('products', 'Admin\ProductController::index', ['as' => 'operator.products']);
    $routes->get('products/create', 'Admin\ProductController::create');
    $routes->post('products/store', 'Admin\ProductController::store');
    $routes->get('products/(:num)/edit', 'Admin\ProductController::edit/$1');
    $routes->post('products/(:num)/update', 'Admin\ProductController::update/$1');
    $routes->post('products/(:num)/delete', 'Admin\ProductController::delete/$1');
    $routes->post('products/(:num)/images/upload', 'Admin\ProductController::uploadImages/$1');
    $routes->post('products/(:num)/images/(:num)/primary', 'Admin\ProductController::setPrimaryImage/$1/$2');
    $routes->post('products/(:num)/images/(:num)/delete', 'Admin\ProductController::deleteImage/$1/$2');
});
