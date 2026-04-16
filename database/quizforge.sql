-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 15-04-2026 a las 21:44:05
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
) ENGINE=InnoDB AUTO_INCREMENT=219 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(164, 99, 'CSS', 0, -50, NULL),
(191, 111, '18 de juliol de 1936', 1, 100, NULL),
(192, 111, '1 de gener de 1936', 0, 0, NULL),
(193, 111, '14 d\'abril de 1931', 0, 0, NULL),
(194, 111, '26 de març de 1937', 0, 0, NULL),
(195, 112, 'Republians', 1, 50, NULL),
(196, 112, 'Nacionalistes', 1, 50, NULL),
(197, 112, 'Monàrquics', 0, 0, NULL),
(198, 112, 'Anarquistes', 0, 0, NULL),
(199, 113, 'True', 1, 0, NULL),
(200, 113, 'False', 0, 0, NULL),
(201, 114, 'Francisco Franco', 1, 0, NULL),
(202, 114, 'José Sanjurjo', 1, 0, NULL),
(203, 116, 'Francisco Franco -> Líder nacionalista', 0, 0, 'Líder nacionalista'),
(204, 116, 'Manuel Azaña -> President republicà', 0, 0, 'President republicà');

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
) ENGINE=InnoDB AUTO_INCREMENT=123 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `title`, `type`, `statement`, `created_at`) VALUES
(98, 44, 'Capital d\'Espanya', 'singlechoice', 'Quina és la capital d\'Espanya?', '2026-03-02 23:12:49'),
(99, 44, 'Llenguatges de programació', 'multichoice', 'Selecciona llenguatges de programació', '2026-03-02 23:12:49'),
(111, 49, 'Quina data marca l\'inici de la guerra civil espanyola?', 'singlechoice', 'Quina data marca l\'inici de la guerra civil espanyola?', '2026-04-14 22:39:32'),
(112, 49, 'Quins eren els principals bàndols durant la guerra civil espanyola?', 'multichoice', 'Quins eren els principals bàndols durant la guerra civil espanyola?', '2026-04-14 22:39:32'),
(113, 49, 'La guerra civil espanyola va durar més de 3 anys.', 'truefalse', 'La guerra civil espanyola va durar més de 3 anys.', '2026-04-14 22:39:32'),
(114, 49, 'Qui era el líder del bàndol nacionalista durant la guerra civil espanyola?', 'shortanswer', 'Qui era el líder del bàndol nacionalista durant la guerra civil espanyola?', '2026-04-14 22:39:32'),
(115, 49, 'Descriu l\'impacte de la guerra civil espanyola en la societat espanyola.', 'essay', 'Descriu l\'impacte de la guerra civil espanyola en la societat espanyola.', '2026-04-14 22:39:32'),
(116, 49, 'Relaciona els personatges amb els seus rols durant la guerra civil.', 'matching', 'Relaciona els personatges amb els seus rols durant la guerra civil.', '2026-04-14 22:39:32');

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
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `quizzes`
--

INSERT INTO `quizzes` (`id`, `user_id`, `title`, `course`, `group`, `description`, `giftformat`, `xmlformat`, `created_at`) VALUES
(1, 2, 'Javascript bàsic', '2025-2026', '1DAW', 'Qüestionari sobre els continguts (PART I) de Javascript de la 2ª avaluació', '', '', '2026-02-09 22:30:02'),
(2, 2, 'CSS flexbox', '2025-2026', '1DAW', 'Qüestionari sobre la propietat display: flex en CSS', '', '', '2026-02-09 22:30:02'),
(3, 2, 'HTML5', '2025-2026', '1DAW', 'Tipus test sobre conceptes d\'HTML5', '', '', '2026-02-09 22:30:46'),
(44, 2, 'Quiz de prova Moodle Import', '2025-2026', '1r DAW', 'Quiz de prova amb tots els tipus de preguntes', '::Capital d\'Espanya::[html]Quina és la capital d\'Espanya? {\n=Madrid\n~Barcelona\n~València\n~Sevilla\n}\n\n::Llenguatges de programació::[html]Selecciona llenguatges de programació {\n~%50%JavaScript\n~%50%PHP\n~%-50%HTML\n~%-50%CSS\n}', '<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<quiz>\n        <question type=\"multichoice\">\n          <name><text>Capital d\'Espanya</text></name>\n          <questiontext format=\"html\">\n            <text><![CDATA[Quina és la capital d\'Espanya?]]></text>\n          </questiontext>\n          <single>true</single>\n          <answer fraction=\"100\">\n                <text><![CDATA[Madrid]]></text>\n          </answer>\n            <answer fraction=\"0\">\n                <text><![CDATA[Barcelona]]></text>\n          </answer>\n            <answer fraction=\"0\">\n                <text><![CDATA[València]]></text>\n          </answer>\n            <answer fraction=\"0\">\n                <text><![CDATA[Sevilla]]></text>\n          </answer>\n        </question>\n\n        <question type=\"multichoice\">\n          <name><text>Llenguatges de programació</text></name>\n          <questiontext format=\"html\">\n            <text><![CDATA[Selecciona llenguatges de programació]]></text>\n          </questiontext>\n          <single>false</single>\n      \n            <answer fraction=\"50\">\n              <text><![CDATA[JavaScript]]></text>\n            </answer>\n        \n            <answer fraction=\"50\">\n              <text><![CDATA[PHP]]></text>\n            </answer>\n        \n            <answer fraction=\"-50\">\n              <text><![CDATA[HTML]]></text>\n            </answer>\n        \n            <answer fraction=\"-50\">\n              <text><![CDATA[CSS]]></text>\n            </answer>\n          </question>\n</quiz>', '2026-03-02 23:12:37'),
(49, 2, 'La guerra civil espanyola de 1936', '2026', 'AI Generated', 'Quiz generat amb l\'API d\'OpenAI: La guerra civil espanyola de 1936 de nivell basico en idioma valencia', '::Quina data marca l\'inici de la guerra civil espanyola?::[html]Quina data marca l\'inici de la guerra civil espanyola? {\n=18 de juliol de 1936\n~1 de gener de 1936\n~14 d\'abril de 1931\n~26 de març de 1937\n}\n\n::Quins eren els principals bàndols durant la guerra civil espanyola?::[html]Quins eren els principals bàndols durant la guerra civil espanyola? {\n~%50%Republians\n~%50%Nacionalistes\n~%0%Monàrquics\n~%0%Anarquistes\n}\n\n::La guerra civil espanyola va durar més de 3 anys.::[html]La guerra civil espanyola va durar més de 3 anys. {TRUE}\n\n::Qui era el líder del bàndol nacionalista durant la guerra civil espanyola?::[html]Qui era el líder del bàndol nacionalista durant la guerra civil espanyola? {=Francisco Franco =José Sanjurjo}\n\n::Descriu l\'impacte de la guerra civil espanyola en la societat espanyola.::[html]Descriu l\'impacte de la guerra civil espanyola en la societat espanyola. {}\n\n::Relaciona els personatges amb els seus rols durant la guerra civil.::[html]Relaciona els personatges amb els seus rols durant la guerra civil. {\n=Francisco Franco -> Líder nacionalista->Líder nacionalista\n=Manuel Azaña -> President republicà->President republicà\n}', '<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<quiz>\n<question type=\"multichoice\">\n  <name><text>Quina data marca l\'inici de la guerra civil espanyola?</text></name>\n  <questiontext format=\"html\"><text><![CDATA[Quina data marca l\'inici de la guerra civil espanyola?]]></text></questiontext>\n  <single>true</single>\n  <answer fraction=\"100\"><text><![CDATA[18 de juliol de 1936]]></text></answer>\n  <answer fraction=\"0\"><text><![CDATA[1 de gener de 1936]]></text></answer>\n  <answer fraction=\"0\"><text><![CDATA[14 d\'abril de 1931]]></text></answer>\n  <answer fraction=\"0\"><text><![CDATA[26 de març de 1937]]></text></answer>\n</question>\n\n<question type=\"multichoice\">\n  <name><text>Quins eren els principals bàndols durant la guerra civil espanyola?</text></name>\n  <questiontext format=\"html\"><text><![CDATA[Quins eren els principals bàndols durant la guerra civil espanyola?]]></text></questiontext>\n  <single>false</single>\n  <answer fraction=\"50\"><text><![CDATA[Republians]]></text></answer>\n  <answer fraction=\"50\"><text><![CDATA[Nacionalistes]]></text></answer>\n  <answer fraction=\"0\"><text><![CDATA[Monàrquics]]></text></answer>\n  <answer fraction=\"0\"><text><![CDATA[Anarquistes]]></text></answer>\n</question>\n\n<question type=\"truefalse\">\n  <name><text>La guerra civil espanyola va durar més de 3 anys.</text></name>\n  <questiontext format=\"html\"><text><![CDATA[La guerra civil espanyola va durar més de 3 anys.]]></text></questiontext>\n  <answer fraction=\"100\"><text>true</text></answer>\n  <answer fraction=\"0\"><text>false</text></answer>\n</question>\n\n<question type=\"shortanswer\">\n  <name><text>Qui era el líder del bàndol nacionalista durant la guerra civil espanyola?</text></name>\n  <questiontext format=\"html\"><text><![CDATA[Qui era el líder del bàndol nacionalista durant la guerra civil espanyola?]]></text></questiontext>\n<answer fraction=\"100\"><text><![CDATA[Francisco Franco]]></text></answer>\n<answer fraction=\"100\"><text><![CDATA[José Sanjurjo]]></text></answer>\n</question>\n\n<question type=\"essay\">\n  <name><text>Descriu l\'impacte de la guerra civil espanyola en la societat espanyola.</text></name>\n  <questiontext format=\"html\"><text><![CDATA[Descriu l\'impacte de la guerra civil espanyola en la societat espanyola.]]></text></questiontext>\n</question>\n\n<question type=\"matching\">\n  <name><text>Relaciona els personatges amb els seus rols durant la guerra civil.</text></name>\n  <questiontext format=\"html\"><text><![CDATA[Relaciona els personatges amb els seus rols durant la guerra civil.]]></text></questiontext>\n  <subquestion>\n    <text><![CDATA[Francisco Franco -> Líder nacionalista]]></text>\n    <answer><text><![CDATA[Líder nacionalista]]></text></answer>\n  </subquestion>\n  <subquestion>\n    <text><![CDATA[Manuel Azaña -> President republicà]]></text>\n    <answer><text><![CDATA[President republicà]]></text></answer>\n  </subquestion>\n</question>\n</quiz>', '2026-04-14 22:39:32');

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
  `plan` enum('free','pro','premium','') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'free',
  `rol` enum('user','admin','','') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `plan`, `rol`, `created_at`) VALUES
(2, 'alberto', 'alberto@gmail.com', '$2y$10$ZVtm7iT8NwQBNzyjkMUfyOOEw2JkIvRD6qCgTBFPsr42L8Mqwlmpm', 'pro', 'admin', '2026-02-09 22:02:17'),
(7, 'pep', 'pep@gmail.com', '$2y$10$9CEa6YSo3nXt9DLPpiTBne8SCHaaJD/7BrSTx3VVMZprfUXUT.Bwe', 'free', 'user', '2026-02-10 15:52:49');

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
