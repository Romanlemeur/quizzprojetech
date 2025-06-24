<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Routes principales
$routes->get('/', 'Home::index');
$routes->get('about', 'Home::about');
$routes->get('contact', 'Home::contact');

// Routes d'authentification
$routes->group('auth', function($routes) {
    $routes->get('/', 'Auth::index');
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::login');
    $routes->get('register', 'Auth::register');
    $routes->post('register', 'Auth::register');
    $routes->get('logout', 'Auth::logout');
});

// Routes administrateur
$routes->group('admin', function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    
    // Gestion des administrateurs
    $routes->get('admins', 'Admin::admins');
    $routes->get('add-admin', 'Admin::addAdmin');
    $routes->post('add-admin', 'Admin::addAdmin');
    $routes->get('delete-admin/(:num)', 'Admin::deleteAdmin/$1');
    
    // Gestion des quiz
    $routes->get('quiz', 'Admin::quiz');
    $routes->get('add-quiz', 'Admin::addQuiz');
    $routes->post('add-quiz', 'Admin::addQuiz');
    $routes->get('edit-quiz/(:num)', 'Admin::editQuiz/$1');
    $routes->post('edit-quiz/(:num)', 'Admin::editQuiz/$1');
    $routes->get('delete-quiz/(:num)', 'Admin::deleteQuiz/$1');
    
    // Gestion des questions
    $routes->get('questions/(:num)', 'Admin::questions/$1');
    $routes->get('add-question/(:num)', 'Admin::addQuestion/$1');
    $routes->post('add-question/(:num)', 'Admin::addQuestion/$1');
    $routes->get('delete-question/(:num)', 'Admin::deleteQuestion/$1');
    
    // Gestion des sessions
    $routes->get('start-session/(:num)', 'Admin::startSession/$1');
    $routes->get('stop-session/(:num)', 'Admin::stopSession/$1');
    $routes->get('live-session/(:num)', 'Admin::liveSession/$1');
    $routes->post('update-score', 'Admin::updateScore');
    $routes->get('next-question/(:num)', 'Admin::nextQuestion/$1');
});

// Routes joueur
$routes->group('joueur', function($routes) {
    $routes->get('dashboard', 'Joueur::dashboard');
    $routes->get('join-session/(:num)', 'Joueur::joinSession/$1');
    $routes->get('play/(:num)', 'Joueur::play/$1');
    $routes->post('answer', 'Joueur::answer');
    $routes->get('results/(:num)', 'Joueur::results/$1');
    $routes->get('profile', 'Joueur::profile');
    $routes->post('update-profile', 'Joueur::updateProfile');
});

// Routes par défaut de CodeIgniter (à supprimer si pas besoin)
// $routes->get('(:any)', 'Home::index/$1');
