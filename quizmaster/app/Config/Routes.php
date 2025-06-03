<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Configuration du routeur
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

// Routes principales du site
// On définit chaque route manuellement pour plus de sécurité
$routes->get('/', 'Home::index');
$routes->get('quiz', 'Quiz::index');
$routes->get('quiz/popular', 'Quiz::popular');
$routes->get('quiz/category/(:num)', 'Quiz::category/$1');
$routes->get('quiz/start/(:num)', 'Quiz::start/$1');
$routes->post('quiz/submit', 'Quiz::submit');
$routes->get('quiz/result/(:num)/(:num)/(:num)/(:num)', 'Quiz::result/$1/$2/$3/$4');
$routes->get('leaderboard', 'Leaderboard::index');
$routes->get('leaderboard/quiz/(:num)', 'Leaderboard::quizLeaderboard/$1');
$routes->get('leaderboard/filter', 'Leaderboard::filter');
$routes->get('profile', 'Leaderboard::profile');
$routes->post('profile', 'Leaderboard::profile');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::attemptRegister');
$routes->get('logout', 'Auth::logout');
$routes->get('account', 'Auth::profile');

// Routes pour les quiz en direct
$routes->get('quiz/live', 'Quiz::joinLive');
$routes->get('quiz/live/(:num)', 'Quiz::livePage/$1');
$routes->get('quiz/get-current-question', 'Quiz::getCurrentQuestion');
$routes->post('quiz/submit-live-answer', 'Quiz::submitLiveAnswer');
$routes->get('quiz/get-live-leaderboard', 'Quiz::getLiveLeaderboard');

// Routes pour l'administration - protégées par le middleware Admin
$routes->group('admin', ['filter' => 'admin'], function($routes) {
    // Dashboard
    $routes->get('', 'Admin::dashboard');
    $routes->get('dashboard', 'Admin::dashboard');
    
    // Gestion des quiz
    $routes->get('quizzes', 'Admin::quizzes');
    $routes->get('quiz/create', 'Admin::createQuiz');
    $routes->post('quiz/store', 'Admin::storeQuiz');
    $routes->get('quiz/edit/(:num)', 'Admin::editQuiz/$1');
    $routes->post('quiz/update/(:num)', 'Admin::updateQuiz/$1');
    $routes->get('quiz/delete/(:num)', 'Admin::deleteQuiz/$1');
    
    // Quiz en direct
    $routes->get('quiz/start-live/(:num)', 'Admin::startLiveQuiz/$1');
    $routes->get('quiz/stop-live/(:num)', 'Admin::stopLiveQuiz/$1');
    $routes->get('live', 'Admin::liveQuiz');
    $routes->post('live/next-question', 'Admin::nextQuestion');
    $routes->get('live/participants', 'Admin::getLiveParticipants');
    $routes->post('live/update-score', 'Admin::updateParticipantScore');
    
    // Gestion des utilisateurs
    $routes->get('users', 'Admin::users');
    $routes->get('user/set-admin/(:num)', 'Admin::setAdmin/$1');
    $routes->get('user/remove-admin/(:num)', 'Admin::removeAdmin/$1');
    $routes->get('admin/add', 'Auth::addAdmin');
    $routes->post('admin/add', 'Auth::addAdmin');
    
    // Statistiques
    $routes->get('statistics', 'Admin::statistics');
});

// Routes pour le système de quiz
$routes->group('quiz', function($routes) {
    $routes->get('/', 'Quiz::categories');
    $routes->get('categories', 'Quiz::categories');
    $routes->get('popular', 'Quiz::popular');
    $routes->get('category/(:num)', 'Quiz::category/$1');
    $routes->get('start/(:num)', 'Quiz::start/$1');
    $routes->get('random', 'Quiz::random');
    $routes->get('random/(:num)', 'Quiz::random/$1');
    $routes->get('live', 'Quiz::live');
    $routes->post('submit', 'Quiz::submit');
    $routes->get('get-live-quiz', 'Quiz::getLiveQuiz');
    $routes->get('get-live-leaderboard', 'Quiz::getLiveLeaderboard');
});

// Routes pour les catégories
$routes->group('categories', function($routes) {
    $routes->get('/', 'Quiz::categories');
    $routes->get('(:num)', 'Quiz::category/$1');
});

if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}