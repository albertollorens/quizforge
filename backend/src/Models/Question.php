<?php

namespace App\Models;

use PDO;

class Question
{
    private PDO $db;
    private Answer $answerModel;

    public function __construct(PDO $db, Answer $answerModel)
    {
        $this->db = $db;
        $this->answerModel = $answerModel;
    }

    /**
     * Crea una pregunta per un quiz concret
     */
    public function create(int $quiz_id, array $data): int
    {        
        $stmt = $this->db->prepare("
            INSERT INTO questions (quiz_id, title, type, statement)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            $quiz_id,
            $data['title'],
            $data['type'],
            $data['statement']
        ]);

        $question_id = (int)$this->db->lastInsertId();

        // Crear respostes        
        if (isset($data['answers'])) {
            $this->answerModel->createMultiple(
                $question_id,
                $data['answers']
            );
        }

        return $question_id;
    }

    /**
     * Obté totes les preguntes d'un quiz
     */
    public function getAllByQuiz(int $quiz_id): array
    {
        $stmt = $this->db->prepare("SELECT * FROM questions WHERE quiz_id = ?");
        $stmt->execute([$quiz_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retorna el model Answer associat
     */
    public function getAnswerModel(): \App\Models\Answer
    {
        return $this->answerModel;
    }

    /**
     * Elimina les preguntes per Id Quiz
     */
    public function deleteByQuizId(int $quiz_id): void
    {
        $stmt = $this->db->prepare("
            DELETE FROM questions
            WHERE quiz_id = :quiz_id
        ");

        $stmt->execute([
            ':quiz_id' => $quiz_id
        ]);
    }
}