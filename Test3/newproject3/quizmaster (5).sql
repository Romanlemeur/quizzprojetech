-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 26 juin 2025 à 21:50
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `quizmaster`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `image`) VALUES
(1, 'Consoles rétro', NULL, NULL),
(2, 'Jeux d’arcade', NULL, NULL),
(3, 'Plateformes 8-bit', NULL, NULL),
(4, 'Plateformes 16-bit', NULL, NULL),
(5, 'RPG classiques', NULL, NULL),
(6, 'Shoot’em up', NULL, NULL),
(7, 'Jeux de combat rétro', NULL, NULL),
(8, 'Puzzle rétro', NULL, NULL),
(9, 'Jeux de sport rétro', NULL, NULL),
(10, 'Jeux de course rétro', NULL, NULL),
(11, 'Jeux d’aventure rétro', NULL, NULL),
(12, 'Jeux de plateforme rétro', NULL, NULL),
(13, 'Accessoires vintage', NULL, NULL),
(14, 'Musiques de jeux rétro', NULL, NULL),
(15, 'Histoire du jeu vidéo', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `options`
--

DROP TABLE IF EXISTS `options`;
CREATE TABLE IF NOT EXISTS `options` (
  `id` int NOT NULL AUTO_INCREMENT,
  `question_id` int NOT NULL,
  `option_text` text NOT NULL,
  `is_correct` tinyint(1) DEFAULT '0',
  `option_order` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`)
) ENGINE=MyISAM AUTO_INCREMENT=233 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `options`
--

INSERT INTO `options` (`id`, `question_id`, `option_text`, `is_correct`, `option_order`) VALUES
(172, 43, '4', 0, 4),
(171, 43, '3', 1, 3),
(170, 43, '2', 0, 2),
(169, 43, '1', 0, 1),
(168, 42, '4', 0, 4),
(167, 42, '3', 0, 3),
(166, 42, '2', 1, 2),
(165, 42, '1', 0, 1),
(164, 41, '4', 0, 4),
(163, 41, '3', 0, 3),
(162, 41, '2', 0, 2),
(161, 41, '1', 1, 1),
(173, 44, '1', 1, 1),
(174, 44, '2', 0, 2),
(175, 44, '3', 0, 3),
(176, 44, '4', 0, 4),
(177, 45, '1', 0, 1),
(178, 45, '23', 1, 2),
(179, 45, '3', 0, 3),
(180, 45, '4', 0, 4),
(181, 46, '1', 0, 1),
(182, 46, '2', 0, 2),
(183, 46, '3', 1, 3),
(184, 46, '4', 0, 4),
(185, 47, '1', 1, 1),
(186, 47, '2', 0, 2),
(187, 47, '3', 0, 3),
(188, 47, '4', 0, 4),
(189, 48, '1', 0, 1),
(190, 48, '2', 1, 2),
(191, 48, '3', 0, 3),
(192, 48, '4', 0, 4),
(193, 49, '1', 0, 1),
(194, 49, '2', 0, 2),
(195, 49, '3', 1, 3),
(196, 49, '4', 0, 4),
(229, 58, '4', 0, 1),
(228, 57, '4', 0, 4),
(227, 57, '3', 0, 3),
(226, 57, '2', 1, 2),
(225, 57, '1', 0, 1),
(224, 56, 'test 2', 0, 4),
(221, 56, 'réponse une', 1, 1),
(222, 56, 'Mario', 0, 2),
(223, 56, 'test 1', 0, 3),
(230, 58, '3', 1, 2),
(231, 58, '2', 0, 3),
(232, 58, '1', 0, 4);

-- --------------------------------------------------------

--
-- Structure de la table `questions`
--

DROP TABLE IF EXISTS `questions`;
CREATE TABLE IF NOT EXISTS `questions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `quiz_id` int NOT NULL,
  `question_text` text NOT NULL,
  `question_order` int DEFAULT '0',
  `points` int DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `quiz_id` (`quiz_id`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `question_text`, `question_order`, `points`) VALUES
(43, 3, 'test3', 3, 1),
(42, 3, 'test2', 2, 1),
(41, 3, 'test1', 1, 1),
(44, 4, 'test1', 1, 1),
(45, 4, 'test2', 2, 1),
(46, 4, 'test3', 3, 1),
(47, 5, 'petit1', 1, 1),
(48, 5, 'test2', 2, 1),
(49, 5, 'test 3', 3, 1),
(58, 6, 'test 33', 3, 1),
(57, 6, 'test 2', 2, 1),
(56, 6, 'test1', 1, 3);

-- --------------------------------------------------------

--
-- Structure de la table `quizzes`
--

DROP TABLE IF EXISTS `quizzes`;
CREATE TABLE IF NOT EXISTS `quizzes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `time_limit` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `is_live` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `quizzes`
--

INSERT INTO `quizzes` (`id`, `category_id`, `title`, `description`, `image`, `time_limit`, `is_active`, `is_live`, `created_at`, `updated_at`) VALUES
(6, 14, 'Soundcloud rétro', 'connais-tu tout ces sons emblématiques?', NULL, 10, 1, 1, '2025-06-26 16:11:32', '2025-06-26 18:56:41');

-- --------------------------------------------------------

--
-- Structure de la table `quiz_sessions`
--

DROP TABLE IF EXISTS `quiz_sessions`;
CREATE TABLE IF NOT EXISTS `quiz_sessions` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `quiz_id` int UNSIGNED NOT NULL,
  `current_question_id` int DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `question_ends_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quiz_id` (`quiz_id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `quiz_sessions`
--

INSERT INTO `quiz_sessions` (`id`, `quiz_id`, `current_question_id`, `start_time`, `end_time`, `is_active`, `created_at`, `updated_at`, `question_ends_at`) VALUES
(28, 6, 56, '2025-06-26 21:50:00', NULL, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Manuel Bassien', 'manuel.bassien97118@gmail.com', '$2y$10$lq5bR8w9mHZUpltiH6gfGO.i8uNTqwe/UvITFossVe53wtLqG/j.G', 'admin', '2025-06-19 23:31:34', '2025-06-20 01:31:34'),
(3, 'admin', 'admin@example.com', '$2y$10$wHk7QwQwQwQwQwQwQwQwQeQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQw', 'admin', '2025-06-20 01:49:34', '2025-06-20 03:49:34'),
(4, 'PrincessP', 'mywrld971@gmail.com', '$2y$10$COAn0CXp0EdIHM26UQuk.OBRwS2gM/A8dETWmoFOUBGAlD7yuDjzW', 'user', '2025-06-23 01:31:49', '2025-06-23 03:31:49');

-- --------------------------------------------------------

--
-- Structure de la table `user_answers`
--

DROP TABLE IF EXISTS `user_answers`;
CREATE TABLE IF NOT EXISTS `user_answers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_score_id` int NOT NULL,
  `question_id` int NOT NULL,
  `option_id` int NOT NULL,
  `is_correct` tinyint(1) DEFAULT '0',
  `time_taken` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`),
  KEY `option_id` (`option_id`),
  KEY `idx_user_answers_score` (`user_score_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user_scores`
--

DROP TABLE IF EXISTS `user_scores`;
CREATE TABLE IF NOT EXISTS `user_scores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `quiz_id` int NOT NULL,
  `score` int NOT NULL,
  `completed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_live` tinyint(1) DEFAULT '0',
  `current_question` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `quiz_id` (`quiz_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user_scores`
--

INSERT INTO `user_scores` (`id`, `user_id`, `quiz_id`, `score`, `completed_at`, `created_at`, `updated_at`, `is_live`, `current_question`) VALUES
(8, 4, 6, 0, NULL, '2025-06-26 18:12:34', '2025-06-26 18:12:34', 1, 0),
(7, 4, 5, 0, NULL, '2025-06-26 12:58:16', '2025-06-26 12:58:16', 1, 0),
(6, 4, 4, 0, NULL, '2025-06-26 12:05:43', '2025-06-26 12:05:43', 1, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
