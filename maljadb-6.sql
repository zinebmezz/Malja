-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : dim. 01 juin 2025 à 00:19
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `maljadb`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

CREATE TABLE `admins` (
  `user_id` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `availability`
--

CREATE TABLE `availability` (
  `id` int(11) NOT NULL,
  `therapist_id` char(36) DEFAULT NULL,
  `available_day` varchar(50) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `availability`
--

INSERT INTO `availability` (`id`, `therapist_id`, `available_day`, `start_time`, `end_time`) VALUES
(2, '6833a5ef59af88.21040025', 'Sunday', '11:20:00', '12:39:00'),
(3, '6833a5ef59af88.21040025', 'Monday', '12:40:00', '02:30:00'),
(4, '6833a5ef59af88.21040025', 'Tuesday', '12:40:00', '23:04:00');

-- --------------------------------------------------------

--
-- Structure de la table `booking_requests`
--

CREATE TABLE `booking_requests` (
  `id` int(11) NOT NULL,
  `patient_id` char(36) DEFAULT NULL,
  `therapist_id` char(36) DEFAULT NULL,
  `requested_time` datetime DEFAULT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `sender_id` char(36) DEFAULT NULL,
  `receiver_id` char(36) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `sent_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déclencheurs `chat_messages`
--
DELIMITER $$
CREATE TRIGGER `prevent_therapist_to_therapist` BEFORE INSERT ON `chat_messages` FOR EACH ROW BEGIN
  DECLARE senderRole VARCHAR(20);
  DECLARE receiverRole VARCHAR(20);

  -- Get the role of the sender
  SELECT role INTO senderRole FROM users WHERE id = NEW.sender_id;

  -- Get the role of the receiver
  SELECT role INTO receiverRole FROM users WHERE id = NEW.receiver_id;

  -- Check if both are therapists
  IF senderRole = 'therapist' AND receiverRole = 'therapist' THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Therapists cannot message other therapists.';
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` char(36) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `patients`
--

CREATE TABLE `patients` (
  `user_id` char(36) NOT NULL,
  `age` enum('Adult','Teen','Kid','') DEFAULT NULL,
  `gender` enum('male','female') NOT NULL,
  `medical_history` text DEFAULT NULL,
  `therapy_preferences` text DEFAULT NULL,
  `medical_file` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `patients`
--

INSERT INTO `patients` (`user_id`, `age`, `gender`, `medical_history`, `therapy_preferences`, `medical_file`, `profile_picture`) VALUES
('683b7fc6b7af54.51199763', 'Adult', 'female', NULL, NULL, NULL, NULL);

--
-- Déclencheurs `patients`
--
DELIMITER $$
CREATE TRIGGER `prevent_therapist_being_patient` BEFORE INSERT ON `patients` FOR EACH ROW BEGIN
  IF EXISTS (
    SELECT 1 FROM therapists WHERE user_id = NEW.user_id
  ) THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'User is already a therapist and cannot be a patient';
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `patient_id` char(36) DEFAULT NULL,
  `therapist_id` char(36) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_date` datetime DEFAULT current_timestamp(),
  `status` enum('completed','failed','pending') DEFAULT 'pending',
  `payment_method` enum('credit_card','paypal','bank_transfer') NOT NULL DEFAULT 'credit_card'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `patient_id` char(36) DEFAULT NULL,
  `therapist_id` char(36) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `patient_id` char(36) DEFAULT NULL,
  `therapist_id` char(36) DEFAULT NULL,
  `session_time` datetime DEFAULT NULL,
  `session_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `therapists`
--

CREATE TABLE `therapists` (
  `user_id` char(36) NOT NULL,
  `license_number` varchar(100) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `therapists`
--

INSERT INTO `therapists` (`user_id`, `license_number`, `is_verified`) VALUES
('6833a5ef59af88.21040025', '500', 0);

--
-- Déclencheurs `therapists`
--
DELIMITER $$
CREATE TRIGGER `prevent_patient_being_therapist` BEFORE INSERT ON `therapists` FOR EACH ROW BEGIN
  IF EXISTS (
    SELECT 1 FROM patients WHERE user_id = NEW.user_id
  ) THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'User is already a patient and cannot be a therapist';
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `therapist_profile`
--

CREATE TABLE `therapist_profile` (
  `id` int(11) NOT NULL,
  `user_id` char(36) NOT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `experience` int(11) DEFAULT NULL,
  `certifications` text DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `availability` text DEFAULT NULL,
  `rating` float DEFAULT 5,
  `wilaya` varchar(100) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `cv_file` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `languages` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `session_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `therapist_profile`
--

INSERT INTO `therapist_profile` (`id`, `user_id`, `specialization`, `experience`, `certifications`, `bio`, `availability`, `rating`, `wilaya`, `phone_number`, `cv_file`, `price`, `languages`, `profile_picture`, `session_type`) VALUES
(8, '6833a5ef59af88.21040025', 'depression,relationships', 10, 'CBT', 'FHHDHFH', NULL, 5, 'Annaba', '787001133', '../uploads/6833a5ef5a15f_Cryptography_Cheatsheet.pdf', 500.00, 'ENGLISH ARABIC', 'profile_pic/1748505989_ Sequence Diagram.png', 'video,in-person');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` char(36) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `role` enum('admin','therapist','patient') NOT NULL DEFAULT 'patient'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `created_at`, `role`) VALUES
('6833a5ef59af88.21040025', 'zinebmez', 'zinebmez@ex.com', '$2y$10$OTeG0OyTz0FNQoWrPB.ZieuNMZszhp0tOcKIqXaMcmcKxz3XrvSNy', '2025-05-26 00:21:19', 'therapist'),
('683b7fc6b7af54.51199763', 'doha', 'doha@ex.com', '$2y$10$TeWRYSSCDtpVVSkZN1zYCetJeURsTydofHzEE/4PI94JFW31Ab3.u', '2025-05-31 23:16:38', 'patient');

--
-- Déclencheurs `users`
--
DELIMITER $$
CREATE TRIGGER `before_insert_users` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
  IF NEW.id IS NULL THEN
    SET NEW.id = UUID();
  END IF;
END
$$
DELIMITER ;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`user_id`);

--
-- Index pour la table `availability`
--
ALTER TABLE `availability`
  ADD PRIMARY KEY (`id`),
  ADD KEY `therapist_id` (`therapist_id`);

--
-- Index pour la table `booking_requests`
--
ALTER TABLE `booking_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `therapist_id` (`therapist_id`);

--
-- Index pour la table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`user_id`);

--
-- Index pour la table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `therapist_id` (`therapist_id`);

--
-- Index pour la table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `therapist_id` (`therapist_id`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `therapist_id` (`therapist_id`);

--
-- Index pour la table `therapists`
--
ALTER TABLE `therapists`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `license_number` (`license_number`);

--
-- Index pour la table `therapist_profile`
--
ALTER TABLE `therapist_profile`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `availability`
--
ALTER TABLE `availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `booking_requests`
--
ALTER TABLE `booking_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `therapist_profile`
--
ALTER TABLE `therapist_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `availability`
--
ALTER TABLE `availability`
  ADD CONSTRAINT `availability_ibfk_1` FOREIGN KEY (`therapist_id`) REFERENCES `therapists` (`user_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `booking_requests`
--
ALTER TABLE `booking_requests`
  ADD CONSTRAINT `booking_requests_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_requests_ibfk_2` FOREIGN KEY (`therapist_id`) REFERENCES `therapists` (`user_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`therapist_id`) REFERENCES `therapists` (`user_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`therapist_id`) REFERENCES `therapists` (`user_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sessions_ibfk_2` FOREIGN KEY (`therapist_id`) REFERENCES `therapists` (`user_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `therapists`
--
ALTER TABLE `therapists`
  ADD CONSTRAINT `therapists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `therapist_profile`
--
ALTER TABLE `therapist_profile`
  ADD CONSTRAINT `therapist_profile_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `therapists` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
