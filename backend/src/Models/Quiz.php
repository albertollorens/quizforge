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
        $stmt = $this->db->prepare("SELECT id, title, course, `group`, description, created_at FROM quizzes WHERE user_id = ? ORDER BY id DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Devuelve un quiz por id (opcional)
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT id, title, course, `group`, description, created_at FROM quizzes WHERE id = ?");
        $stmt->execute([$id]);
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);
        return $quiz ?: null;
    }

    public function create($user_id, $data) {
        $stmt = $this->db->prepare("INSERT INTO quizzes (user_id, title, course, `group`, description, giftformat, xmlformat) VALUES (?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $user_id,
            $data['title'],
            $data['course'], 
            $data['group'],
            $data['description'],
            $data['giftformat'],
            $data['xmlformat']
        ]);

        return $this->db->lastInsertId();
    }

    public function update(int $quiz_id, int $user_id, array $data) {

        $this->db->beginTransaction();        
        $stmt = $this->db->prepare("UPDATE quizzes SET title = :title, course = :course, `group` = :group, `description` = :description, `giftformat` = :giftformat, `xmlformat` = :xmlformat WHERE id = :id AND user_id = :user_id");

        $stmt->execute([
            ':title' => $data['title'],
            ':course' => $data['course'],
            ':group' => $data['group'],
            ':description' => $data['description'],
            ':giftformat' => $data['giftformat'],
            ':xmlformat' => $data['xmlformat'],
            ':id' => $quiz_id,
            ':user_id' => $user_id
        ]);

        // Eliminar questions antigues 
        $stmt = $this->db->prepare("DELETE FROM questions WHERE quiz_id = :quiz_id");
        $stmt->execute([':quiz_id' => $quiz_id]);

        // Inserir noves questions
        $stmt = $this->db->prepare("INSERT INTO questions (quiz_id, question_text, question_type) VALUES (:quiz_id, :text, :type)");
        foreach ($data['questions'] as $q) {
            $stmt->execute([
                ':quiz_id' => $quiz_id,
                ':text' => $q['question_text'],
                ':type' => $q['type']
            ]);
        }

        $this->db->commit();
    }

    public function delete(int $quiz_id, int $user_id): bool {
        $stmt = $this->db->prepare("DELETE FROM questions WHERE quiz_id = :quiz_id");
        $stmt->execute([':quiz_id' => $quiz_id]);
        $stmt = $this->db->prepare("DELETE FROM quizzes WHERE id = :quiz_id AND user_id = :user_id");
        $stmt->execute([
            ':quiz_id' => $quiz_id,
            ':user_id' => $user_id
        ]);

        return $stmt->rowCount() > 0;
    }

    public function beginTransaction() {
        $this->db->beginTransaction();
    }

    public function commit() {
        $this->db->commit();
    }

    public function rollBack() {
        $this->db->rollBack();
    }
}
