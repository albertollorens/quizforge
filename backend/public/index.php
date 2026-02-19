<?php

require '../vendor/autoload.php';
require '../src/config/Database.php';

use Slim\Factory\AppFactory; //Slim Fwk 4
use App\Middleware\CorsMiddleware; //CORS
use App\Config\Database;
use App\Controllers\LoginController;
use App\Controllers\QuizController;
use App\Models\User;
use App\Models\Quiz;

$app = AppFactory::create();

// Middlewares obligatoris
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->add(new CorsMiddleware()); //CORS
$app->addErrorMiddleware(true, true, true);

// CORS
$app->add(function ($request, $handler) {
    if ($request->getMethod() === 'OPTIONS') {
        $response = new \Slim\Psr7\Response();
    } else {
        $response = $handler->handle($request);
    }
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
        ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
});

// INJECCIÓ DE DEPENDÈNCIES
// Crear instàncies dels models amb PDO
$userModel = new User(Database::connect());
$quizModel = new Quiz(Database::connect());

$loginController = new LoginController($userModel);
$quizController = new QuizController($quizModel);

// Rutes
//require __DIR__ . '/../src/routes/auth.php';
require __DIR__ . '/../src/routes/api.php';

// Comprovació d'errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Si no és /api, servir Vue
if (!str_starts_with($_SERVER['REQUEST_URI'], '/api')) {
    readfile(__DIR__ . '/index.html');
    exit;
} else {
    $app->options('/{routes:.+}', function ($request, $response) {
        return $response;
    });
    $app->run();
}