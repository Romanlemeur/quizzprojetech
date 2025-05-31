<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Routes d'authentification
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::attemptRegister');

// Routes des quiz (utilisateurs)
$routes->get('quiz', 'Quiz::index');
$routes->get('quiz/category/(:num)', 'Quiz::category/$1');
$routes->get('quiz/start/(:num)', 'Quiz::start/$1');
$routes->post('quiz/submit', 'Quiz::submit');
$routes->get('quiz/result/(:num)/(:num)/(:num)/(:num)', 'Quiz::result/$1/$2/$3/$4');

// Routes d'administration (protégées par le filtre admin)
$routes->group('admin', ['filter' => 'admin'], function($routes) {
    $routes->get('/', 'Admin::index');
    
    // Gestion des quiz
    $routes->get('manageQuizzes', 'Admin::manageQuizzes');
    $routes->get('createQuiz', 'Admin::createQuiz');
    $routes->post('createQuiz', 'Admin::createQuiz');
    $routes->get('editQuiz/(:num)', 'Admin::editQuiz/$1');
    $routes->post('editQuiz/(:num)', 'Admin::editQuiz/$1');
    $routes->get('deleteQuiz/(:num)', 'Admin::deleteQuiz/$1');
    
    // Gestion des questions
    $routes->get('manageQuestions/(:num)', 'Admin::manageQuestions/$1');
    $routes->post('addQuestion/(:num)', 'Admin::addQuestion/$1');
    $routes->get('editQuestion/(:num)', 'Admin::editQuestion/$1');
    $routes->post('editQuestion/(:num)', 'Admin::editQuestion/$1');
    $routes->get('deleteQuestion/(:num)', 'Admin::deleteQuestion/$1');
    
    // Gestion des catégories
    $routes->get('manageCategories', 'Admin::manageCategories');
    $routes->post('addCategory', 'Admin::addCategory');
    $routes->post('editCategory/(:num)', 'Admin::editCategory/$1');
    $routes->get('deleteCategory/(:num)', 'Admin::deleteCategory/$1');
}); 