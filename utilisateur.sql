-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 24 juin 2025 à 11:56
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `quiz_platform`
--

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `idUtilisateur` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `motDePasse` varchar(255) NOT NULL,
  `pseudo` varchar(50) DEFAULT NULL,
  `role` enum('admin','joueur') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`idUtilisateur`, `email`, `motDePasse`, `pseudo`, `role`) VALUES
(1, 'kionga.brunely@gmail.com', '$2y$10$al3e5UFdj5gCjaYJeNgMAecSGIVVYZma3ez5O70W7ZwFudd.1o4vi', '_mr_kionga', 'joueur'),
(3, 'fredenebrunely@gmail.com', '$2y$10$xtgd8sHMlR8JWywKOg3EVuPoCygh6U84OXKGNs2Ps2.KoBfxsAWWK', 'fredooneju', 'admin'),
(4, 'test@gmail.com', '$2y$10$nHovQ59KNidpWjBTZislquJd7VKzGSKWhAw5d7EyKdQpLy9tGSUDq', 'himra', 'joueur'),
(5, 'admin@quiz.com', '$2y$10$6vvBT23NTiDrt1gsnQCRCud9PTgNgByXbmYfc.PUrprntwwEuenTG', 'Administrateur', 'admin');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`idUtilisateur`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `idUtilisateur` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
