-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 23 juin 2025 à 12:14
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
) ENGINE=MyISAM AUTO_INCREMENT=137 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `options`
--

INSERT INTO `options` (`id`, `question_id`, `option_text`, `is_correct`, `option_order`) VALUES
(136, 34, '4', 0, 4),
(135, 34, '3', 1, 3),
(134, 34, '2', 0, 2),
(133, 34, '1', 0, 1),
(132, 33, '4', 0, 4),
(131, 33, '3', 0, 3),
(130, 33, '2', 1, 2),
(129, 33, '1', 0, 1),
(128, 32, '4', 0, 4),
(127, 32, '3', 0, 3),
(126, 32, '2', 0, 2),
(125, 32, '1', 1, 1);

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
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `question_text`, `question_order`, `points`) VALUES
(34, 3, 'test3', 3, 1),
(33, 3, 'test2', 2, 1),
(32, 3, 'test1', 1, 1);

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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `quizzes`
--

INSERT INTO `quizzes` (`id`, `category_id`, `title`, `description`, `image`, `time_limit`, `is_active`, `is_live`, `created_at`, `updated_at`) VALUES
(3, 1, 'Soundcloud', 'test3', NULL, 10, 1, 1, '2025-06-23 01:01:29', '2025-06-23 12:13:16');

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
  PRIMARY KEY (`id`),
  KEY `quiz_id` (`quiz_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `quiz_sessions`
--

INSERT INTO `quiz_sessions` (`id`, `quiz_id`, `current_question_id`, `start_time`, `end_time`, `is_active`, `created_at`, `updated_at`) VALUES
(4, 3, 32, '2025-06-23 12:13:16', NULL, 1, NULL, NULL),
(3, 3, NULL, '2025-06-23 11:47:17', NULL, 0, NULL, NULL);

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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user_scores`
--

INSERT INTO `user_scores` (`id`, `user_id`, `quiz_id`, `score`, `completed_at`, `created_at`, `updated_at`, `is_live`, `current_question`) VALUES
(1, 1, 1, 100, NULL, '2025-06-20 23:50:32', '2025-06-22 23:32:12', 1, 0),
(3, 1, 3, 0, NULL, '2025-06-23 03:04:11', '2025-06-23 03:04:11', 1, 0),
(4, 4, 3, 0, NULL, '2025-06-23 03:32:27', '2025-06-23 03:32:27', 1, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
