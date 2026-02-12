<?php
use App\Middleware\AuthMiddleware;

//use App\Controllers\QuizController;
//use App\Controllers\LoginController;

$app->group('/api', function ($group) use ($loginController, $quizController) {

    // Auth
    $group->post('/login',    [$loginController, 'login']);
    $group->post('/register', [$loginController, 'register']);

    // Quizzes (protegit per JWT)
    $group->group('', function ($group) use ($quizController) {
        $group->get('/quizzes',        [$quizController, 'list']);
        /*$group->post('/quizzes',       [QuizController::class, 'store']);
        $group->get('/quizzes/{id}',   [QuizController::class, 'show']);
        $group->put('/quizzes/{id}',   [QuizController::class, 'update']);
        $group->delete('/quizzes/{id}',[QuizController::class, 'destroy']);*/
    })->add(new AuthMiddleware());

});
