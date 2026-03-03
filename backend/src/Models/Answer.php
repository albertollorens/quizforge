<?php

namespace App\Models;

use PDO;

class Answer
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Crear una resposta
     */
    public function create(int $question_id, array $answer): void
    {
        $stmt = $this->db->prepare("
            INSERT INTO answers
            (question_id, text, correct, weight, match_pair)
            VALUES
            (:question_id, :text, :correct, :weight, :match_pair)
        ");

        $stmt->bindValue(':question_id', $question_id, PDO::PARAM_INT);
        $stmt->bindValue(':text', $answer['text'], PDO::PARAM_STR);

        $stmt->bindValue(
            ':correct',
            isset($answer['correct']) ? (int)$answer['correct'] : 0,
            PDO::PARAM_INT
        );

        if (isset($answer['weight'])) {
            $stmt->bindValue(':weight', $answer['weight']);
        } else {
            $stmt->bindValue(':weight', 0, PDO::PARAM_INT);
        }

        if (isset($answer['match_pair'])) {
            $stmt->bindValue(':match_pair', $answer['match_pair'], PDO::PARAM_STR);
        } else {
            $stmt->bindValue(':match_pair', null, PDO::PARAM_NULL);
        }

        $stmt->execute();
    }

    /**
     * Crear múltiples respostes
     */
    public function createMultiple(int $question_id, array $answers): void
    {        
        foreach ($answers as $answer) {
            $this->create($question_id, $answer);
        }
    }

    /**
     * Obtenir respostes d'una pregunta
     */
    public function getByQuestionId(int $question_id): array
    {
        $stmt = $this->db->prepare("
            SELECT text, correct, weight, match_pair
            FROM answers
            WHERE question_id = :question_id
            ORDER BY id ASC
        ");

        $stmt->execute([
            ':question_id' => $question_id
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Eliminar respostes d'una pregunta
     */
    public function deleteByQuestionId(int $question_id): void
    {
        $stmt = $this->db->prepare("
            DELETE FROM answers
            WHERE question_id = :question_id
        ");

        $stmt->execute([
            ':question_id' => $question_id
        ]);
    }
}