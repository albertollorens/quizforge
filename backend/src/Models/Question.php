<?php

namespace App\Models;

use PDO;

class Question
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Crea una pregunta per un quiz concret
     */
    public function create(int $quiz_id, array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO questions (quiz_id, title, type)
            VALUES (?, ?, ?)
        ");

        $stmt->execute([
            $quiz_id,
            $data['title'],
            $data['type']
        ]);

        return (int)$this->db->lastInsertId();
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
}