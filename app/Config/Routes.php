<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Pages
$routes->get('/', 'Home::index'); 
$routes->get('home', 'Home::index');
$routes->get('about', 'About::index');
$routes->get('faq', 'Faq::index');
$routes->get('account', 'Account::index');
$routes->get('contact', 'Contact::index');

$routes->get('documents/graduateThesis', 'Graduates::index');

// Authentication
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::loginPost');
$routes->get('logout', 'Auth::logout');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::registerPost');
$routes->post('edit/(:num)', 'Auth::edit/$1');
// $routes->get('forgot-password', 'Auth::forgotPassword');
// $routes->post('forgot-password', 'Auth::forgotPassword');



// Graduate Thesis
$routes->get('documents/graduateThesis/create', 'Graduates::createGraduateThesis'); // Display the form to create a new graduate thesis
$routes->post('documents/graduateThesis/create', 'Graduates::insertGraduateThesis'); // Handle the form submission to create a new graduate thesis

// $routes->post('thesis/update/(:any)', 'Thesis::update/$1');
// $routes->get('thesis/delete/(:any)', 'Thesis::delete/$1');
// $routes->get('thesis/download/(:any)', 'Thesis::download/$1');
// $routes->get('thesis/search', 'Thesis::search');
// $routes->get('thesis/(:any)', 'Thesis::view/$1');
