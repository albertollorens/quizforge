<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Quiz;
use App\Models\Question;

class QuizController
{
    private Quiz $quizModel;
    private Question $questionModel;

    public function __construct(Quiz $quizModel, Question $questionModel) {
        $this->quizModel = $quizModel;
        $this->questionModel = $questionModel;
    }

    public function list(Request $request, Response $response): Response {
        // Obté user_id des del middleware (atribut del request)
        $user_id = $request->getAttribute('user_id');

        $quizzes = $this->quizModel->getAll($user_id);

        $response->getBody()->write(json_encode(['data' => $quizzes]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function create(Request $request, Response $response): Response {
        $user_id = $request->getAttribute('user_id');
        $data = $request->getParsedBody();

        try {
            // Crea el Quiz          
            $quiz_id = $this->quizModel->create($user_id, $data);            

            // 2️⃣ Crear preguntes
            foreach ($data['questions'] as $question) {
                $this->questionModel->create($quiz_id, $question);              
            }

            $response->getBody()->write(json_encode([
                'success' => true,
                'quiz_id' => $quiz_id
            ]));
            
        } catch(\Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]));

            return $response->withStatus(500);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function get(Request $request, Response $response, array $args): Response {

        $user_id = $request->getAttribute('user_id');
        $quiz_id = $args['id'];
        $quiz = $this->quizModel->getById($quiz_id, $user_id);

        if (!$quiz) {
            return $response->withStatus(404);
        }

        // Obtenim totes les preguntes del quiz
        $questions = $this->questionModel->getAllByQuiz($quiz_id);

        // Afegim respostes o parells per cada pregunta segons el tipus
        foreach ($questions as &$q) {            
            // Obtenim les respostes normals
            $q['answers'] = $this->questionModel->getAnswerModel()->getByQuestionId($q['id']);
        }
        // Afegim les preguntes al quiz
        $quiz['questions'] = $questions;

        $response->getBody()->write(json_encode($quiz));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function update(Request $request, Response $response, array $args): Response {

        $user_id = $request->getAttribute('user_id');
        $quiz_id = $args['id'];
        $data = $request->getParsedBody();

        try {
            // Actualitza les dades del Quiz          
            $this->quizModel->update($quiz_id, $user_id, $data);         

            // Elimina les preguntes del quiz
            $this->questionModel->deleteByQuizId($quiz_id);

            // Insereix les noves preguntes
            foreach ($data['questions'] as $question) {
                $this->questionModel->create($quiz_id, $question);              
            }

            $response->getBody()->write(json_encode([
                'success' => true
            ]));
        } catch(\Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]));

            return $response->withStatus(500);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function delete(Request $request, Response $response, array $args): Response {
        $user_id = $request->getAttribute('user_id');
        $quiz_id = $args['id'];
        $deleted = $this->quizModel->delete($quiz_id, $user_id);

        if (!$deleted) {
            $response->getBody()->write(json_encode([
                'error' => 'Quiz no trobat'
            ]));

            return $response
                ->withStatus(404)
                ->withHeader('Content-Type', 'application/json');

        }

        $response->getBody()->write(json_encode([
            'success' => true
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
