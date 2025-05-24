<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

error_reporting(E_ALL);
ini_set("display_errors", 1);

require __DIR__ . '/../vendor/autoload.php';

// Create Router instance
$router = new \Bramus\Router\Router();
$router->setNamespace('Controllers');




// ================== Product Routes ==================

// List all products
$router->get('/products', 'ProductController@getAll');

// List all products
$router->get('/products/featured', 'ProductController@getFeatured');

// Search for products
$router->post('/products/search', 'ProductController@searchProducts');

// Get a single product by ID
$router->get('/products/(\d+)', 'ProductController@getOne');

// Create a new product
$router->post('/products/insert', 'ProductController@create');

// Update an existing product by ID
$router->put('/products/(\d+)', 'ProductController@update');

// Delete a product by ID
$router->delete('/products/(\d+)', 'ProductController@delete');

// Set a product as featured
$router->put('/products/(\d+)/featured', 'ProductController@setFeaturedStatus');

// Get products by category ID
$router->get('/products/category/(\d+)', 'ProductController@getProductsByCategoryId');





// ================== Category Routes ==================

// List all categories
$router->get('/categories', 'CategoryController@getAll');

// Get a single category by ID
$router->get('/categories/(\d+)', 'CategoryController@getOne');




// ================== User Routes ==================

// Get a single user by ID
$router->get('/user/(\d+)', 'UserController@getOne');

// Get a single user by ID
$router->get('/me', 'UserController@getMe');

// Create a new user
$router->post('/user/create', 'UserController@create');

// Update an existing user by ID
$router->put('/user/update/(\d+)', 'UserController@update');

// Update a user's password by ID
$router->put('/user/update-password/(\d+)', 'UserController@updatePassword');

// User login
$router->post('/login', 'UserController@login');

// User refresg token
$router->post('/refresh-token', 'UserController@refreshToken');





// ================== Cart Routes ==================

// Get a user's cart by user ID
$router->get('/cart/(\d+)', 'CartController@getCart');

// Add a product to the user's cart
$router->put('/cart/add/(\d+)', 'CartController@addToCart');

// Update the quantity of a product in the user's cart
$router->put('/cart/update/(\d+)', 'CartController@updateQuantity');

// Remove a product from the cart by product ID
$router->delete('/cart/removeproduct/(\d+)', 'CartController@removeProduct');

// Clear the user's cart
$router->delete('/cart/clear', 'CartController@clear');




// ================== Cart Routes ==================

// Get a user's cart by user ID
$router->get('/orders', 'OrderController@getAll');

// Get a user's cart by user ID
$router->post('/orders/create/(\d+)', 'OrderController@create');

// Get a user's cart by user ID
$router->put('/orders/update', 'OrderController@update');



// Run the router
$router->run();
