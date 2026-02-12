<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Quiz;

class QuizController
{
    private Quiz $quizModel;

    public function __construct(Quiz $quizModel) {
        $this->quizModel = $quizModel;
    }

    public function list(Request $request, Response $response): Response {
        // Obté user_id des del middleware (atribut del request)
        $user_id = $request->getAttribute('user_id');

        $quizzes = $this->quizModel->getAll($user_id);

        $response->getBody()->write(json_encode(['data' => $quizzes]));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
