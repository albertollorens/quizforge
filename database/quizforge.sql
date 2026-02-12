-- ============================================
-- CREACIÓN DE BASE DE DATOS
-- ============================================

CREATE DATABASE IF NOT EXISTS quizforge
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE quizforge;

-- ============================================
-- TABLA USUARIOS
-- ============================================

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABLA CUESTIONARIOS
-- ============================================

CREATE TABLE quizzes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  title VARCHAR(150) NOT NULL,
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT fk_quiz_user
    FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON DELETE CASCADE
);

-- ============================================
-- TABLA PREGUNTAS
-- ============================================

CREATE TABLE questions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  quiz_id INT NOT NULL,
  type ENUM(
    'single',
    'multiple',
    'matching',
    'cloze',
    'short',
    'essay'
  ) NOT NULL,
  question_text TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT fk_question_quiz
    FOREIGN KEY (quiz_id)
    REFERENCES quizzes(id)
    ON DELETE CASCADE
);

-- ============================================
-- TABLA RESPUESTAS
-- ============================================

CREATE TABLE answers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  question_id INT NOT NULL,
  answer_text TEXT NOT NULL,
  is_correct BOOLEAN DEFAULT FALSE,
  match_pair VARCHAR(255) NULL,

  CONSTRAINT fk_answer_question
    FOREIGN KEY (question_id)
    REFERENCES questions(id)
    ON DELETE CASCADE
);

-- ============================================
-- ÍNDICES (MEJOR RENDIMIENTO)
-- ============================================

CREATE INDEX idx_quizzes_user ON quizzes(user_id);
CREATE INDEX idx_questions_quiz ON questions(quiz_id);
CREATE INDEX idx_answers_question ON answers(question
