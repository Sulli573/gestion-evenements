-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 02 mai 2025 à 13:45
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_evenements`
--

-- --------------------------------------------------------

--
-- Structure de la table `evenements`
--

DROP TABLE IF EXISTS `evenements`;
CREATE TABLE IF NOT EXISTS `evenements` (
  `id_evenement` int NOT NULL AUTO_INCREMENT,
  `nom_evenement` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date_evenement` datetime NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `description_evenement` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `place_evenement` int NOT NULL,
  `place_restantes` int NOT NULL,
  `prix_evenement` double NOT NULL,
  `image_evenement` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type_evenement` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_finish` bit(2) DEFAULT b'0',
  `id_organisateur` int DEFAULT NULL,
  `id_lieu` int NOT NULL,
  PRIMARY KEY (`id_evenement`),
  KEY `evenements_organisateur_FK` (`id_organisateur`),
  KEY `evenements_lieu0_FK` (`id_lieu`)
) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `evenements`
--

INSERT INTO `evenements` (`id_evenement`, `nom_evenement`, `date_evenement`, `heure_debut`, `heure_fin`, `description_evenement`, `place_evenement`, `place_restantes`, `prix_evenement`, `image_evenement`, `type_evenement`, `is_finish`, `id_organisateur`, `id_lieu`) VALUES
(12, 'Concert Rock and Roll des chats!!', '2025-06-02 00:00:00', '00:00:00', '00:00:00', 'Un concert rock en plein air de folie avec nala et khaleesi.', 501, 498, 20, '', 'Concerts', b'00', 3, 1),
(13, 'Conférence Tech', '2023-07-20 00:00:00', '00:00:00', '00:00:00', 'Conférence sur les nouvelles technologies.', 300, 300, 0, 'conference_tech.jpg', 'Conférence', b'00', 3, 1),
(78, 'Garorok', '2025-04-08 00:00:00', '08:00:00', '23:00:00', 'Festival de rock and roll', 400, 400, 40, '78.png', 'Festival', b'00', 3, 6),
(149, 'Les petits clowns', '2025-06-25 00:00:00', '18:00:00', '19:00:00', 'Pièce de théâtre des enfants de l\'école de la paix', 30, 29, 5, '149.png', 'pièce de théâtre', b'00', 3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `inscrire`
--

DROP TABLE IF EXISTS `inscrire`;
CREATE TABLE IF NOT EXISTS `inscrire` (
  `code` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `id_evenement` int NOT NULL,
  `nbr_ticket` int NOT NULL,
  `date_inscription` date NOT NULL,
  PRIMARY KEY (`code`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_evenement` (`id_evenement`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `inscrire`
--

INSERT INTO `inscrire` (`code`, `id_utilisateur`, `id_evenement`, `nbr_ticket`, `date_inscription`) VALUES
(1, 14, 77, 1, '2025-04-13'),
(2, 14, 12, 1, '2025-04-13'),
(3, 14, 12, 1, '2025-04-15'),
(4, 14, 77, 1, '2025-04-15'),
(5, 14, 12, 1, '2025-04-15'),
(6, 14, 12, 1, '2025-04-15'),
(7, 15, 77, 1, '2025-04-17'),
(8, 15, 77, 1, '2025-04-17'),
(9, 17, 149, 1, '2025-05-02');

--
-- Déclencheurs `inscrire`
--
DROP TRIGGER IF EXISTS `after_inscription_insert`;
DELIMITER $$
CREATE TRIGGER `after_inscription_insert` AFTER INSERT ON `inscrire` FOR EACH ROW BEGIN
    UPDATE evenements
    SET place_restantes = place_restantes - NEW.nbr_ticket
    WHERE id_evenement = NEW.id_evenement;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `lieu`
--

DROP TABLE IF EXISTS `lieu`;
CREATE TABLE IF NOT EXISTS `lieu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_lieu` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `adresse_lieu` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `lieu`
--

INSERT INTO `lieu` (`id`, `nom_lieu`, `adresse_lieu`) VALUES
(1, 'Salle des Fêtes', '09 rue de la Liberté, 75000 Paris'),
(6, 'Terrain de foot', '8 place du gazon');

-- --------------------------------------------------------

--
-- Structure de la table `organisateur`
--

DROP TABLE IF EXISTS `organisateur`;
CREATE TABLE IF NOT EXISTS `organisateur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_organisateur` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `contact_organisateur` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email_organisateur` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_idx` (`email_organisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `organisateur`
--

INSERT INTO `organisateur` (`id`, `nom_organisateur`, `contact_organisateur`, `email_organisateur`) VALUES
(3, 'Sullivan', '+336154526502', 'sullivan@gmail.com');

-- --------------------------------------------------------

--
-- Structure de la table `user_tokens`
--

DROP TABLE IF EXISTS `user_tokens`;
CREATE TABLE IF NOT EXISTS `user_tokens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user_tokens`
--

INSERT INTO `user_tokens` (`id`, `user_id`, `token`, `created_at`) VALUES
(61, 15, '5879539f5b1eb211c2647f119ca5bc23bf49618b2e1eb8c4acaa5cb29a3c6d1a', '2025-04-29 08:33:49'),
(58, 15, 'b23d10f4a768ae73a1e9bede7a23a5f9f0d61c6ef487f80c6d7723fe2910075b', '2025-04-20 13:35:03'),
(51, 16, 'b7897e8c390c70f1b022879fa347523df2c83b654cefa02d07e8063da1deff7d', '2025-04-17 19:41:06');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `nom_utilisateur` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `courriel_utilisateur` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mot_de_passe_utilisateur` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role_utilisateur` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `is_suspended` tinyint(1) NOT NULL DEFAULT '0',
  `motif_suspension` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `unique_email` (`courriel_utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom_utilisateur`, `courriel_utilisateur`, `mot_de_passe_utilisateur`, `role_utilisateur`, `is_active`, `is_suspended`, `motif_suspension`) VALUES
(14, 'Sulli', 'sulli@123.com', '$2y$10$NN1r7rOX6dzjf4xG5p7E7eSMDrg0LwPQN1wHaONSxAQLaI6an64xS', 'admin', 0, 0, NULL),
(15, 'sulli', 'sulli@1234.com', '$2y$10$BQD6jcqZt8BYgg5BnEKxPe0qYSC1TLdMeR.3RDy1ypYYqxNYOYk6u', 'user', 0, 0, NULL),
(16, 'sulli2', 'sulli@12345.com', '$2y$10$RONJ/CRUZamb/diHmUHZ.O4LbKqTS15aCOaLT/KX7u02ugmRk.07y', 'user', 0, 0, NULL),
(17, 'test', 'test@test.com', '$2y$10$/Wdvga2/i4Pan9WiGwcBQO2y7N7Fq58vTPat5L1livRISx5FFzTYi', 'user', 0, 1, '');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `evenements`
--
ALTER TABLE `evenements`
  ADD CONSTRAINT `evenements_lieu0_FK` FOREIGN KEY (`id_lieu`) REFERENCES `lieu` (`id`),
  ADD CONSTRAINT `evenements_organisateur_FK` FOREIGN KEY (`id_organisateur`) REFERENCES `organisateur` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
