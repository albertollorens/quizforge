<?php
use App\Middleware\AuthMiddleware;

//use App\Controllers\QuizController;
//use App\Controllers\LoginController;

$app->group('/api', function ($group) use ($loginController, $quizController, $openAIController) {

    // Auth
    $group->post('/login',    [$loginController, 'login']);
    $group->post('/register', [$loginController, 'register']);

    // User Profile (protegit per JWT)
    $group->group('', function ($group) use ($loginController) {   
        $group->get('/users',     [$loginController, 'list']);     
        //$group->post('/user',       [$loginController, 'create']);
        $group->get('/user/{id}',   [$loginController, 'get']);
        $group->put('/user/{id}',   [$loginController, 'update']);
        $group->delete('/user/{id}',[$loginController, 'delete']);
    })->add(new AuthMiddleware());

    // Quizzes (protegit per JWT)
    $group->group('', function ($group) use ($quizController) {
        $group->get('/quizzes',        [$quizController, 'list']);
        $group->post('/quizzes',       [$quizController, 'create']);
        $group->get('/quizzes/{id}',   [$quizController, 'get']);
        $group->put('/quizzes/{id}',   [$quizController, 'update']);
        $group->delete('/quizzes/{id}',[$quizController, 'delete']);
    })->add(new AuthMiddleware());

    // Generació IA integrada en QuizController
    $group->group('', function ($group) use ($openAIController){
        $group->post('/aiquiz/generate',       [$openAIController, 'generate']);
    })->add(new AuthMiddleware());

});
