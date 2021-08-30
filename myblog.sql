-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : lun. 30 août 2021 à 20:00
-- Version du serveur :  5.7.24
-- Version de PHP : 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `myblog`
--

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `content` text NOT NULL,
  `state` tinyint(1) NOT NULL,
  `fk_user` int(11) NOT NULL,
  `fk_post` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`content`, `state`, `fk_user`, `fk_post`, `date`, `id`) VALUES
('C\'est génial!', 1, 52, 1, '2021-08-30 08:29:25', 1),
('Génial!', 1, 52, 3, '2021-08-30 21:57:30', 3),
('Je ne peux pas y aller...', 1, 52, 5, '2021-08-30 21:57:49', 4),
('Elle est reservée pour nous...', 1, 51, 5, '2021-08-30 21:58:33', 5),
('C\'est par là qu\'il faut passer si vous oubliez vos identifiants...', 1, 51, 4, '2021-08-30 21:59:25', 6);

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

CREATE TABLE `post` (
  `title` varchar(256) NOT NULL,
  `chapo` text NOT NULL,
  `content` longtext NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fk_user` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `date_modif` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`title`, `chapo`, `content`, `date`, `fk_user`, `id`, `date_modif`) VALUES
('Nouveau blog', 'Bienvenue sur mon blog', 'Ca y est il est terminé! C\'est parti pour plein d\'articles intéressants sur tous les sujets qui m\'interessent!', '2021-08-28 23:02:24', 51, 1, '2021-08-30 21:50:02'),
('Création de compte', 'Elle est disponible!', 'Vous pouvez créer un compte pour avoir la possibilité de laisser un commentaire sur les articles.', '2021-08-30 21:52:04', 50, 3, NULL),
('Formulaire de contact', 'Il est sur la page d\'accueil', 'Vous souhaitez plus d\'informations, vous avez un problème avec votre compte ou vous voulez simplement me dire ce que vous pensez de mon blog? Le formulaire de contact est là pour vous! Alors n\'hésitez pas à laisser un message, je vous répondrais dès que possible.', '2021-08-30 21:54:03', 50, 4, NULL),
('Zone d\'administration', 'Réservée aux administrateurs', 'Vous avez été promu administrateur et vous ne savez pas par où commencer? La zone d\'administration se trouve dans le pied de page et vous donne accès aux pages de gestion des posts, des commentaires et des utilisateurs ( réservé aux superAdministrateurs).', '2021-08-30 21:56:33', 50, 5, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `name` varchar(256) NOT NULL,
  `surname` varchar(256) NOT NULL,
  `pseudo` varchar(256) NOT NULL,
  `mail` varchar(256) NOT NULL,
  `grade` enum('member','admin','superAdmin') NOT NULL,
  `password` varchar(256) NOT NULL,
  `inscription_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_validate` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL,
  `registration_key` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`name`, `surname`, `pseudo`, `mail`, `grade`, `password`, `inscription_date`, `is_validate`, `user_id`, `registration_key`) VALUES
('Dupont', 'Patrick', 'SuperAdminAccount', 'superAdmin@mail.com', 'superAdmin', '$2y$10$UBxoLTLRtCZhJGsbkueW9eNeGTpfV8vHR9qgqwj0C3PZVW4Lk4n5e', '2021-08-28 07:54:19', 1, 50, '06f772945c622dc1f0ef2940fa0e5bf9'),
('Martin', 'Franck', 'adminAccount', 'admin@mail.com', 'admin', '$2y$10$EZebUsGps07dxedWcxwsT.mXKtTcpK975Kr1JktDyclW56EAMHGvG', '2021-08-28 07:57:26', 1, 51, '91648bd727a96e55505ffffce812e70f'),
('Lavigne', 'Antoine', 'memberAccount', 'member@mail.com', 'member', '$2y$10$yLayMq/AgfrLWdnHkqwtk.kqwGqXYB2X0OGtIEQ6LRGUTBqvrH8/a', '2021-08-28 07:59:57', 1, 52, 'cbfc078a9c9b21e462e8312215222e4f');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`fk_user`),
  ADD KEY `fk_post` (`fk_post`),
  ADD KEY `fk_post_2` (`fk_post`);

--
-- Index pour la table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_2` (`fk_user`),
  ADD KEY `user` (`fk_user`) USING BTREE;

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `mail` (`mail`),
  ADD UNIQUE KEY `pseudo` (`pseudo`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`fk_post`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`fk_user`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`fk_user`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
