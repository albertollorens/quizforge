<?php
namespace App\Models;

use PDO;

class User {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function findByEmail(string $email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Buscar usuario por ID de Google
    public function findByGoogleId(string $googleId) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE google_id = ?");
        $stmt->execute([$googleId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Buscar usuario por ID de Microsoft
    public function findByMicrosoftId(string $microsoftId) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE microsoft_id = ?");
        $stmt->execute([$microsoftId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getNumQuizzes(int $id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM quizzes WHERE user_id = ?");
        $stmt->execute([$id]);
        return (int) $stmt->fetchColumn();
    }

    public function create(string $username, string $email, string $password): bool {
        $hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, password, provider) VALUES (?, ?, ?, 'local')"
        );

        return $stmt->execute([$username, $email, $hash]);
    }

    // Crear usuario con Google OAuth
    public function createWithGoogle(string $googleId, string $email, string $name, ?string $profilePictureUrl = null): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, google_id, profile_picture, provider, plan, rol) 
             VALUES (?, ?, ?, ?, 'google', 'free', 'user')"
        );

        return $stmt->execute([$name, $email, $googleId, $profilePictureUrl]);
    }

    // Crear usuario con Microsoft OAuth
    public function createWithMicrosoft(string $microsoftId, string $email, string $name, ?string $profilePictureUrl = null): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, microsoft_id, profile_picture, provider, plan, rol) 
             VALUES (?, ?, ?, ?, 'microsoft', 'free', 'user')"
        );

        return $stmt->execute([$name, $email, $microsoftId, $profilePictureUrl]);
    }

    // Actualizar usuario existente con datos de Google
    public function updateWithGoogle(int $id, string $googleId, ?string $profilePictureUrl = null): bool {
        $stmt = $this->db->prepare(
            "UPDATE users SET google_id = ?, profile_picture = ?, provider = 'google' WHERE id = ?"
        );

        return $stmt->execute([$googleId, $profilePictureUrl, $id]);
    }

    // Actualizar usuario existente con datos de Microsoft
    public function updateWithMicrosoft(int $id, string $microsoftId, ?string $profilePictureUrl = null): bool {
        $stmt = $this->db->prepare(
            "UPDATE users SET microsoft_id = ?, profile_picture = ?, provider = 'microsoft' WHERE id = ?"
        );

        return $stmt->execute([$microsoftId, $profilePictureUrl, $id]);
    }

    public function update(int $id, string $username, string $email, string $password) {        
        $stmt = $this->db->prepare("UPDATE users SET name = ?, email = ?, password = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        $stmt->execute([$username, $email, $password, $id]);
    }

    // Obtener usuario por ID
    public function findById(int $id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Listar todos los usuarios
    public function listAll() {
        $stmt = $this->db->prepare("SELECT id, name, email, provider, plan, rol, created_at FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Eliminar usuario
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
