<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public pages (no authentication required)

$routes->get('about', 'About::index');
$routes->get('faq', 'Faq::index');
$routes->get('contact', 'Contact::index');

// Authentication routes (no filter needed)
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::loginPost');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::registerPost');

// Protected routes (require authentication)
$routes->group('', ['filter' => 'logged_in'], function ($routes) {
    // User account and analytics
    $routes->get('/', 'Home::index');
    $routes->get('home', 'Home::index');
    $routes->get('account', 'Account::index');
    $routes->get('analytics', 'Analytics::index');
    $routes->get('users', 'Users::index');
    $routes->get('getAnalyticsData', 'Analytics::getAnalyticsData');
    $routes->get('logout', 'Auth::logout');
    $routes->post('edit/(:num)', 'Auth::edit/$1');

    // User management (for admin)
    $routes->get('users/view/(:num)', 'Users::view/$1');
    $routes->post('users/edit/(:num)', 'Users::edit/$1');

    // Graduate Thesis
    $routes->get('documents/graduateThesis', 'Graduates::index');
    $routes->get('documents/graduateThesis/create', 'Graduates::createGraduateThesis');
    $routes->post('documents/graduateThesis/create', 'Graduates::insertGraduateThesis');
    $routes->get('documents/graduateThesis/view/(:num)', 'Graduates::view/$1');
    $routes->get('documents/graduateThesis/download/(:num)', 'Graduates::download/$1');
    $routes->post('documents/graduateThesis/edit/(:num)', 'Graduates::edit/$1');

    // Dissertations
    $routes->get('documents/dissertations', 'Dissertations::index');
    $routes->get('documents/dissertations/create', 'Dissertations::createDissertations');
    $routes->post('documents/dissertations/create/', 'Dissertations::insertDissertations');
    $routes->get('documents/dissertations/view/(:num)', 'Dissertations::view/$1');
    $routes->get('documents/dissertations/download/(:num)', 'Dissertations::download/$1');
    $routes->post('documents/dissertations/edit/(:num)', 'Dissertations::edit/$1');

    // Faculty Research
    $routes->get('documents/facultyResearch', 'FacultyResearch::index');
    $routes->get('documents/facultyResearch/create', 'FacultyResearch::createFacultyResearch');
    $routes->post('documents/facultyResearch/create/', 'FacultyResearch::insertFacultyResearch');
    $routes->get('documents/facultyResearch/view/(:num)', 'FacultyResearch::view/$1');
    $routes->get('documents/facultyResearch/download/(:num)', 'FacultyResearch::download/$1');
    $routes->post('documents/facultyResearch/edit/(:num)', 'FacultyResearch::edit/$1');

    // Document management (for librarian and adviser)
    $routes->get('documents/submitted/', 'Documents::submitted');
    $routes->get('documents/endorsed/', 'Documents::endorsed');
    $routes->get('documents/published/', 'Documents::published');
    $routes->post('documents/published/edit/(:num)', 'Dissertations::edit/$1');

    $routes->get('documents/viewDocument/(:num)', 'Documents::viewDocument/$1');
    $routes->post('search', 'Documents::search');
    $routes->get('exportAnalytics', 'Analytics::exportAnalytics');
});
