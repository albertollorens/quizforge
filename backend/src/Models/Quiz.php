<?php
namespace App\Models;

use PDO;

class Quiz
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Devuelve todos los quizzes
    public function getAll(int $user_id): array
    {
        $stmt = $this->db->prepare("SELECT id, title, description FROM quizzes WHERE user_id = ? ORDER BY id DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Devuelve un quiz por id (opcional)
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT id, title, description FROM quizzes WHERE id = ?");
        $stmt->execute([$id]);
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);
        return $quiz ?: null;
    }
}
