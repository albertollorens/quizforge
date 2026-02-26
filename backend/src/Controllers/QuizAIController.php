<?php

namespace App\Controllers;

use App\Services\OpenAIService;
use App\Models\Quiz;
use App\Models\Question;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class QuizAIController
{
    private OpenAIService $ai;
    private Quiz $quizModel;
    private Question $questionModel;

    public function __construct(
        OpenAIService $ai,
        Quiz $quizModel,
        Question $questionModel
    ) {
        $this->ai = $ai;
        $this->quizModel = $quizModel;
        $this->questionModel = $questionModel;
    }

    public function generate(Request $request, Response $response): Response
    {
        try {

            $user = $request->getAttribute('user');

            if (!$user) {
                throw new \Exception("Usuari no autenticat");
            }

            $user_id = $user->sub;

            $data = $request->getParsedBody();

            $topic = $data['topic'] ?? 'programació';
            $level = $data['level'] ?? 'bàsic';

            // 1️⃣ Generar preguntes amb IA
            $result = $this->ai->generateQuestions($topic, $level);

            $questions = json_decode($result, true);

            // 2️⃣ Crear quiz
            $quiz_id = $this->quizModel->create(
                "Quiz sobre $topic",
                $user_id
            );

            // 3️⃣ Guardar preguntes
            foreach ($questions as $q) {

                $this->questionModel->create(
                    $quiz_id,
                    $q['question'],
                    $q['answers'],
                    $q['correct']
                );
            }

            $response->getBody()->write(json_encode([
                'success' => true,
                'quiz_id' => $quiz_id
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json');

        } catch (\Exception $e) {

            $response->getBody()->write(json_encode([
                'error' => $e->getMessage()
            ]));

            return $response->withStatus(500);
        }
    }
}