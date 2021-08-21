-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : lun. 16 août 2021 à 18:38
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
('Je suis fier de moi!!', 1, 10, 41, '2021-08-09 18:38:24', 38),
('Félicitations!!', 1, 46, 43, '2021-08-16 18:33:41', 39);

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
('Bienvenue sur mon blog', 'Trop content de vous accueillir sur mon site.', 'Bienvenue sur mon blog! Vous trouverez ici toutes mes dernières news, toutes les choses que j\'ai envie de partager chaque jour avec vous! N\'hésitez pas à me laisser vos impressions...\r\nA très vite pour plein de sujets intéressants!', '2021-07-20 23:05:58', 10, 10, '2021-07-22 11:00:03'),
('Nouveau titre   ', 'Ce post ne servait à rien...', 'Je peux donc en modifier le contenu pour qu\'il ne soit plus inutile!...', '2021-07-22 09:02:33', 10, 11, '2021-08-08 17:58:39'),
(' Modification ', 'Les posts peuvent maintenant être modifiés!', 'A compter d\'aujourd\'hui la modification des posts devient possible! Merci au dev!!', '2021-07-22 09:02:51', 10, 12, '2021-08-07 16:16:20'),
('Moins de Posts par page!', 'On s\'attaque à la pagination.', 'Aujourd\'hui je m\'attaque à la pagination sur la page des posts, il va falloir se concentrer!', '2021-08-09 07:34:44', 10, 40, '2021-08-09 12:38:13'),
('Pagination', 'Ca y est elle est en place!', 'Après plusieurs tentatives, ça y est la pagination est en place!!', '2021-08-09 07:34:53', 10, 41, '2021-08-09 18:39:12'),
('Nombre d\'articles par page', 'Il est modifiable!', 'On peut changer le nombre d\'articles que l\'on souhaite afficher par page dans la liste des posts en modifiant le fichier config.ini!', '2021-08-09 21:11:34', 10, 43, '2021-08-12 07:53:02');

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
('Grandclement', 'Pierre', 'D4rkstrife', 'p.gdc85@gmail.com', 'superAdmin', '$2y$10$8cRhFES66gzPA3PS6ZN.f.sFSSdCENa2pCLmu92EdH6RkFnS6gV0m', '2021-07-20 22:59:27', 1, 10, '0'),
('Dupont', 'Patrice', 'Patoche', 'patoche@gmail.com', 'admin', '$2y$10$ZtzOsolw3DrVFUyNdQaoi..eB07vb.19vjQnfEofqbS0j/DkesZqq', '2021-08-09 21:54:51', 1, 45, '6a94bafa25fdcf774de8c6dcce643b2f'),
('Docteur', 'Strife', 'Blablabla', 'd4rkstrife@gmail.com', 'member', '$2y$10$ti2M8pvOpA./GjUc2m2/Buv.Z/kCrb4qRpFGRZjK9CsYZKIGxo0G.', '2021-08-16 18:32:48', 1, 46, NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

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
