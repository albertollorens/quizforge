-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 03-03-2026 a las 11:59:05
-- Versión del servidor: 8.4.7
-- Versión de PHP: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `quizforge`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `answers`
--

DROP TABLE IF EXISTS `answers`;
CREATE TABLE IF NOT EXISTS `answers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `question_id` int NOT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `correct` tinyint(1) DEFAULT '0',
  `weight` int NOT NULL DEFAULT '0',
  `match_pair` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_answers_question` (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=191 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `answers`
--

INSERT INTO `answers` (`id`, `question_id`, `text`, `correct`, `weight`, `match_pair`) VALUES
(157, 98, 'Madrid', 1, 100, NULL),
(158, 98, 'Barcelona', 0, 0, NULL),
(159, 98, 'València', 0, 0, NULL),
(160, 98, 'Sevilla', 0, 0, NULL),
(161, 99, 'JavaScript', 1, 50, NULL),
(162, 99, 'PHP', 1, 50, NULL),
(163, 99, 'HTML', 0, -50, NULL),
(164, 99, 'CSS', 0, -50, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `questions`
--

DROP TABLE IF EXISTS `questions`;
CREATE TABLE IF NOT EXISTS `questions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `quiz_id` int NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('singlechoice','multichoice','matching','gapselect','truefalse','shortanswer','essay') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `statement` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_questions_quiz` (`quiz_id`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `title`, `type`, `statement`, `created_at`) VALUES
(98, 44, 'Capital d\'Espanya', 'singlechoice', 'Quina és la capital d\'Espanya?', '2026-03-02 23:12:49'),
(99, 44, 'Llenguatges de programació', 'multichoice', 'Selecciona llenguatges de programació', '2026-03-02 23:12:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quizzes`
--

DROP TABLE IF EXISTS `quizzes`;
CREATE TABLE IF NOT EXISTS `quizzes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `course` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `giftformat` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `xmlformat` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_quizzes_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `quizzes`
--

INSERT INTO `quizzes` (`id`, `user_id`, `title`, `course`, `group`, `description`, `giftformat`, `xmlformat`, `created_at`) VALUES
(1, 2, 'Javascript bàsic', '2025-2026', '1DAW', 'Qüestionari sobre els continguts (PART I) de Javascript de la 2ª avaluació', '', '', '2026-02-09 22:30:02'),
(2, 2, 'CSS flexbox', '2025-2026', '1DAW', 'Qüestionari sobre la propietat display: flex en CSS', '', '', '2026-02-09 22:30:02'),
(3, 2, 'HTML5', '2025-2026', '1DAW', 'Tipus test sobre conceptes d\'HTML5', '', '', '2026-02-09 22:30:46'),
(44, 2, 'Quiz de prova Moodle Import', '2025-2026', '1r DAW', 'Quiz de prova amb tots els tipus de preguntes', '::Capital d\'Espanya::[html]Quina és la capital d\'Espanya? {\n=Madrid\n~Barcelona\n~València\n~Sevilla\n}\n\n::Llenguatges de programació::[html]Selecciona llenguatges de programació {\n~%50%JavaScript\n~%50%PHP\n~%-50%HTML\n~%-50%CSS\n}', '<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<quiz>\n        <question type=\"multichoice\">\n          <name><text>Capital d\'Espanya</text></name>\n          <questiontext format=\"html\">\n            <text><![CDATA[Quina és la capital d\'Espanya?]]></text>\n          </questiontext>\n          <single>true</single>\n          <answer fraction=\"100\">\n                <text><![CDATA[Madrid]]></text>\n          </answer>\n            <answer fraction=\"0\">\n                <text><![CDATA[Barcelona]]></text>\n          </answer>\n            <answer fraction=\"0\">\n                <text><![CDATA[València]]></text>\n          </answer>\n            <answer fraction=\"0\">\n                <text><![CDATA[Sevilla]]></text>\n          </answer>\n        </question>\n\n        <question type=\"multichoice\">\n          <name><text>Llenguatges de programació</text></name>\n          <questiontext format=\"html\">\n            <text><![CDATA[Selecciona llenguatges de programació]]></text>\n          </questiontext>\n          <single>false</single>\n      \n            <answer fraction=\"50\">\n              <text><![CDATA[JavaScript]]></text>\n            </answer>\n        \n            <answer fraction=\"50\">\n              <text><![CDATA[PHP]]></text>\n            </answer>\n        \n            <answer fraction=\"-50\">\n              <text><![CDATA[HTML]]></text>\n            </answer>\n        \n            <answer fraction=\"-50\">\n              <text><![CDATA[CSS]]></text>\n            </answer>\n          </question>\n</quiz>', '2026-03-02 23:12:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(2, 'alberto', 'alberto@gmail.com', '$2y$10$ZVtm7iT8NwQBNzyjkMUfyOOEw2JkIvRD6qCgTBFPsr42L8Mqwlmpm', '2026-02-09 22:02:17'),
(7, 'pep', 'pep@gmail.com', '$2y$10$9CEa6YSo3nXt9DLPpiTBne8SCHaaJD/7BrSTx3VVMZprfUXUT.Bwe', '2026-02-10 15:52:49');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
