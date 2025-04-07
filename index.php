<?php
// Point d'entrée de l'application
require_once 'config/config.php';
require_once 'core/Router.php';
require_once 'core/Controller.php';
require_once 'core/Model.php';
require_once 'core/Database.php';
require_once 'core/Auth.php';

// Charger les modèles
require_once 'models/UserModel.php';
require_once 'models/ChessModel.php';

// Démarrer la session
session_start();
// Initialiser le routeur
$router = new Router();

// Définir les routes
$router->addRoute('GET', '/', 'HomeController@index');
$router->addRoute('GET', '/login', 'AuthController@loginForm');
$router->addRoute('POST', '/login', 'AuthController@login');
$router->addRoute('GET', '/register', 'AuthController@registerForm');
$router->addRoute('POST', '/register', 'AuthController@register');
$router->addRoute('GET', '/logout', 'AuthController@logout');

// Routes du tableau de bord
$router->addRoute('GET', '/dashboard', 'DashboardController@index');
$router->addRoute('GET', '/leaderboard', 'DashboardController@leaderboard');


// Routes de gestion des utilisateurs
$router->addRoute('GET', '/users', 'UserController@index');
$router->addRoute('GET', '/users/create', 'UserController@createForm');
$router->addRoute('POST', '/users/create', 'UserController@create');
$router->addRoute('GET', '/users/edit/:id', 'UserController@editForm');
$router->addRoute('POST', '/users/edit/:id', 'UserController@update');
$router->addRoute('POST', '/users/delete/:id', 'UserController@delete');

// Routes pour les parties d'echecs
$router->addRoute('GET', '/games', 'ChessController@index');
$router->addRoute('GET', '/game/:id', 'ChessController@game');
$router->addRoute('GET', '/game/:id/get-position', 'ChessController@getposition');
$router->addRoute('POST', '/game/:id/update-position', 'ChessController@updateposition');
$router->addRoute('POST', '/game/create-game', 'ChessController@newgame');
// $router->addRoute('POST', '/game/:id/update-status', 'ChessController@updatestatus');
// $router->addRoute('POST', '/game/:id/save-move', 'ChessController@savemove');
$router->addRoute('GET', '/game/:id/get-status', 'ChessController@getstatus');
// Dispatcher la requête
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

