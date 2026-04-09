-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 09 avr. 2026 à 22:56
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `monitoring`
--

-- --------------------------------------------------------

--
-- Structure de la table `devices`
--

CREATE TABLE `devices` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `status` enum('online','offline','warning') DEFAULT 'offline',
  `last_update` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `devices`
--

INSERT INTO `devices` (`id`, `name`, `ip`, `status`, `last_update`) VALUES
(1, 'Serveur Web', '192.168.1.10', 'online', '2025-06-10 07:07:33');

-- --------------------------------------------------------

--
-- Structure de la table `equipment`
--

CREATE TABLE `equipment` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `url` varchar(255) NOT NULL,
  `snmp_community` varchar(50) DEFAULT 'public',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `health_stats`
--

CREATE TABLE `health_stats` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `patients` int(11) DEFAULT 0,
  `revenus` decimal(10,2) DEFAULT 0.00,
  `tests` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `health_stats`
--

INSERT INTO `health_stats` (`id`, `date`, `patients`, `revenus`, `tests`) VALUES
(1, '2025-08-01 10:00:00', 12, 500.00, 8),
(2, '2025-08-02 10:00:00', 15, 750.00, 10),
(3, '2025-08-03 10:00:00', 18, 650.00, 12),
(4, '2025-08-04 10:00:00', 10, 400.00, 7),
(5, '2025-08-05 10:00:00', 22, 1200.00, 15);

-- --------------------------------------------------------

--
-- Structure de la table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','warning','error') DEFAULT 'info',
  `date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `logs`
--

INSERT INTO `logs` (`id`, `message`, `type`, `date`) VALUES
(1, 'Connexion réussie', 'info', '2025-06-10 07:06:17');

-- --------------------------------------------------------

--
-- Structure de la table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `date_naissance` date NOT NULL,
  `sexe` enum('M','F','Autre') NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `date_enregistrement` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `patients`
--

INSERT INTO `patients` (`id`, `nom`, `prenom`, `date_naissance`, `sexe`, `telephone`, `adresse`, `date_enregistrement`) VALUES
(9, 'Ndoumbou-daoud', 'Herlich', '2025-08-09', '', '241062400099', 'Ndoumboudaoud@gmail.com', '2025-08-09 03:08:42'),
(12, 'Maes', 'Du3615', '2025-08-09', '', '24177691826', 'Maes@gmail.com', '2025-08-09 06:28:53'),
(14, 'Mepeme', 'Murphy', '2025-08-12', '', '241062400099', 'Ulis@gmail.com', '2025-08-12 03:51:51'),
(18, 'Love', 'Love', '2024-09-15', '', '066176929', 'Love@gmail.com', '2025-09-05 02:55:26'),
(19, 'Melki', 'Man', '2025-11-08', '', '066218902', 'Man@gmail.com', '2025-11-08 07:47:50'),
(20, 'NDOUMBOU-DAOUD', 'Herlich', '2026-04-08', '', '33344555', 'Unnamed Road', '2026-04-08 21:08:10');

-- --------------------------------------------------------

--
-- Structure de la table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `report_content` text DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `revenus`
--

CREATE TABLE `revenus` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `date_enregistrement` datetime DEFAULT current_timestamp(),
  `devise` varchar(5) NOT NULL DEFAULT '€'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `revenus`
--

INSERT INTO `revenus` (`id`, `patient_id`, `montant`, `date_enregistrement`, `devise`) VALUES
(1, 8, 25.00, '2025-08-09 01:59:02', '€'),
(2, 8, 25.00, '2025-08-09 01:59:02', '€'),
(3, 9, 25.00, '2025-08-09 03:08:42', 'CFA'),
(4, 10, 300.00, '2025-08-09 03:10:34', '€'),
(5, 11, 200.00, '2025-08-09 03:16:23', '€'),
(6, 12, 100.00, '2025-08-09 06:28:53', '€'),
(7, 13, 20.00, '2025-08-10 03:37:48', 'CFA'),
(8, 14, 100.00, '2025-08-12 03:51:51', '€'),
(9, 15, 1000.00, '2025-08-12 04:43:59', 'CFA'),
(10, 16, 20.00, '2025-09-03 14:54:36', 'CFA'),
(11, 17, 20.50, '2025-09-03 15:13:49', '€'),
(12, 18, 50000.00, '2025-09-05 02:55:26', 'CFA'),
(13, 19, 17000.00, '2025-11-08 07:47:50', 'CFA'),
(14, 20, -0.06, '2026-04-08 21:08:10', '$');

-- --------------------------------------------------------

--
-- Structure de la table `system_stats`
--

CREATE TABLE `system_stats` (
  `id` int(11) NOT NULL,
  `cpu` float DEFAULT NULL,
  `ram` float DEFAULT NULL,
  `disk` float DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `system_stats`
--

INSERT INTO `system_stats` (`id`, `cpu`, `ram`, `disk`, `created_at`) VALUES
(1, 45, 62, 81, '2025-07-17 10:00:00'),
(2, 53, 70, 79, '2025-07-17 11:00:00'),
(3, 48, 65, 80, '2025-07-17 12:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `tests`
--

CREATE TABLE `tests` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `test_date` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `tests`
--

INSERT INTO `tests` (`id`, `title`, `test_date`) VALUES
(1, 'manger', '2025-07-19'),
(2, 'David', '2025-08-03'),
(3, 'Junior', '2025-08-03'),
(4, 'Eya', '2025-09-03'),
(5, 'Rere', '2025-09-03'),
(6, 'Love', '2025-09-05'),
(7, 'maladie', '2024-03-27');

-- --------------------------------------------------------

--
-- Structure de la table `tests_plan`
--

CREATE TABLE `tests_plan` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `date_test` date NOT NULL,
  `etat` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `name`, `password`, `role`) VALUES
(1, 'admin', 'admin@example.com', 'Admin', '$2y$10$cih7CRM.ZImbZq2vZwNCWOcKme3q7yKYf7oRiHINWCq/eMV3PCTam', 'admin'),
(2, 'mpeme', 'mpemeall@gmailcom', 'mpeme all', '$2y$10$kJshNhGhSC.r8NVTj8DOhONBK5xX14ipeFn2Rass2US4EhFh/uInK', 'user'),
(3, '', '', 'Herlich', '$2y$10$sTd.hpDYP2iv8bubXFE3xeHmaEY4CxE4zboIrR9YXgC7QVR2ewMxq', 'user'),
(4, 'herlich', 'mpemeall@gmail.com', 'david herlich', '$2y$10$HTWYWcmoeKroZfBsbshgnO5b37zFcXhD5LLv0WWBqcvIW4LfDoC/C', 'user'),
(5, 'daoud', 'ndoumboudaoud@gmail.com', 'ndoumbou daoud', '$2y$10$xRQ3/4UmpwKj3NA.NoA3Cu3MwQncVOqrkROlTa86QSnk25b7JFZH6', 'user'),
(6, 'michel', 'wanmichel@gmail.com', 'wan michel', '$2y$10$kElpYdgKbIjICYxDcTf/0u.NSqczJygRyMtxh72vOtCdOguTNJD3a', 'user'),
(8, 'Murphy', 'mpememurphy@gmail.com', 'MPEME', '$2y$10$IjJtvnE2StTK/D0XgeOVm.7tBEIIMZd.cd4SMi7lzpxNnSIGgMezy', 'user'),
(11, 'Marvin', 'cozy@gmail.com', 'Ongéa Marvin', '$2y$10$7rZhXqvw//83ElBu2jD4H.wof1OMLs7j.N.rf/MbOSaTULuoZasxy', 'user'),
(12, 'Enoc', 'enoc@gmail.com', 'Cloudflare', '$2y$10$/RPl5Rs9OrUzizZ.5Bd2WuzKMwMRlBf7TLjWgSSy6ESMtkeOcVW/a', 'user'),
(14, 'kayass', 'kayass@example.com', 'mane', '$2y$10$2FMpKt4sr9N4VEDYgMvD5ulF9EM47y4QrXxlyzJhHbP/rDfgLyTVK', 'user'),
(15, 'TOTO', 'TOTO@example.com', 'TON NOM', '$2y$10$olQWp1AEN7cVeMGBQ2MYgentjxn8PwEZyj8j.uC5r0uQJf0AHpzcq', 'user'),
(16, 'Fairy', 'fairy@gmail.com', 'FAIRYTA_War', '$2y$10$g3sUqnbRvkBfNMz4x6k3w.QxXBbY6cwNegtk9kqSpaWdb.Otn7IKq', 'user'),
(17, 'DAVID', 'DAVID@gmail.com', 'DAVID', '$2y$10$ZS1mSx1vYCbcO96dFTPqXecUxrMdotedND60m.zGeH1f8uZ.dEgNK', 'user'),
(18, 'Rien', 'rien@gmail.com', 'Rien', '$2y$10$8h08RSkmVt3bn42Ppw/tgeZoP2BmNtgG2Gu9I4DftA5Xc3m3AxwJ.', 'user'),
(21, 'Eya', 'eya@gmail.com', 'Eya', '$2y$10$EkL7OEFxFUHYMU5nmSJwb.mXG4198xVaXCZZBD5dy1INUJNR/GfDa', 'user'),
(22, 'Elango', 'Elango@gmail.com', 'Elango', '$2y$10$kJSLNAUsSkAK7CozVknJLetsHqRifNE6veLnXyHD38AJTWuyVLqTa', 'user'),
(24, 'Tate', 'tate@gmail.com', 'Tate', '$2y$10$jxU4H8q4NUiEAU79R/K1h.Z4vUkilLRuzGiRUzR15vjKjabX3wHL6', 'user'),
(25, 'Rere', 'rere@gmail.com', 'Rere', '$2y$10$K4XiJvS9GR9Cy5Nd5yV8OOjsv0zDFC4gnMceXD.eE.4InM3FPo9Dm', 'user'),
(26, 'Love', 'love@gmail.com', 'Love', '$2y$10$VDv07cRp4QZmKC/0HyWknOXHgkKiO1vRj0plEUhVTM3TNG4uH..BS', 'user');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `health_stats`
--
ALTER TABLE `health_stats`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `revenus`
--
ALTER TABLE `revenus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_revenus_patient` (`patient_id`);

--
-- Index pour la table `system_stats`
--
ALTER TABLE `system_stats`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tests_plan`
--
ALTER TABLE `tests_plan`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `devices`
--
ALTER TABLE `devices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `health_stats`
--
ALTER TABLE `health_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `revenus`
--
ALTER TABLE `revenus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `system_stats`
--
ALTER TABLE `system_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `tests`
--
ALTER TABLE `tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `tests_plan`
--
ALTER TABLE `tests_plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
