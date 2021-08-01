-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : Dim 01 août 2021 à 06:52
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
('Hate de te lire!! A très vite :)', 1, 11, 10, '2021-07-20 23:06:34', 24),
('En plus tu écris tellement bien...', 1, 11, 10, '2021-07-20 23:21:57', 25),
('fffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff', 1, 10, 13, '2021-07-22 23:26:15', 27);

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
('Lorem Ipsum', 'Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus bibendum, ipsum at suscipit blandit, nulla metus mollis felis, id blandit lectus nulla sed neque. Aenean vel ante metus. Cras ultrices egestas accumsan. Etiam nec nunc id mauris iaculis vestibulum. Aliquam tellus leo, luctus id porttitor id, tincidunt ut purus. Fusce elementum mi orci, eu suscipit massa mollis quis. Vestibulum vulputate fringilla justo vel viverra.\r\n\r\nDuis enim nisl, consequat viverra maximus eu, lobortis pretium arcu. Nulla a interdum tellus. Nam euismod volutpat nulla vel imperdiet. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Nam lacinia nulla ultricies pellentesque luctus. Curabitur sagittis vulputate lacus in luctus. Sed rutrum sodales rutrum. Integer a sodales tellus, in gravida neque. Aenean sit amet libero placerat, ultrices eros sed, sagittis purus. Mauris egestas aliquet porttitor. Sed ac eros egestas, aliquam neque vitae, mattis lacus. Vestibulum id mattis nibh. Curabitur ipsum dui, sagittis id ultricies quis, dignissim eu tellus.', '2021-07-22 09:02:33', 10, 11, NULL),
('Lorem ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus bibendum, ipsum at suscipit blandit, nulla metus mollis felis, id blandit lectus nulla sed neque. Aenean vel ante metus.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus bibendum, ipsum at suscipit blandit, nulla metus mollis felis, id blandit lectus nulla sed neque. Aenean vel ante metus. Cras ultrices egestas accumsan. Etiam nec nunc id mauris iaculis vestibulum. Aliquam tellus leo, luctus id porttitor id, tincidunt ut purus. Fusce elementum mi orci, eu suscipit massa mollis quis. Vestibulum vulputate fringilla justo vel viverra.\r\n\r\nDuis enim nisl, consequat viverra maximus eu, lobortis pretium arcu. Nulla a interdum tellus. Nam euismod volutpat nulla vel imperdiet. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Nam lacinia nulla ultricies pellentesque luctus. Curabitur sagittis vulputate lacus in luctus. Sed rutrum sodales rutrum. Integer a sodales tellus, in gravida neque. Aenean sit amet libero placerat, ultrices eros sed, sagittis purus. Mauris egestas aliquet porttitor. Sed ac eros egestas, aliquam neque vitae, mattis lacus. Vestibulum id mattis nibh. Curabitur ipsum dui, sagittis id ultricies quis, dignissim eu tellus.', '2021-07-22 09:02:51', 10, 12, NULL),
('Lorem ipsum dolor sit ametd neque. Aenean vel ante metus.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus bibendum, ipsum at suscipit blandit, nulla metus mollis felis, id blandit lectus nulla sed neque. Aenean vel ante metus.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus bibendum, ipsum at suscipit blandit, nulla metus mollis felis, id blandit lectus nulla sed neque. Aenean vel ante metus. Cras ultrices egestas accumsan. Etiam nec nunc id mauris iaculis vestibulum. Aliquam tellus leo, luctus id porttitor id, tincidunt ut purus. Fusce elementum mi orci, eu suscipit massa mollis quis. Vestibulum vulputate fringilla justo vel viverra.\r\n\r\nDuis enim nisl, consequat viverra maximus eu, lobortis pretium arcu. Nulla a interdum tellus. Nam euismod volutpat nulla vel imperdiet. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Nam lacinia nulla ultricies pellentesque luctus. Curabitur sagittis vulputate lacus in luctus. Sed rutrum sodales rutrum. Integer a sodales tellus, in gravida neque. Aenean sit amet libero placerat, ultrices eros sed, sagittis purus. Mauris egestas aliquet porttitor. Sed ac eros egestas, aliquam neque vitae, mattis lacus. Vestibulum id mattis nibh. Curabitur ipsum dui, sagittis id ultricies quis, dignissim eu tellus.', '2021-07-22 09:03:22', 10, 13, NULL);

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
  `registration_key` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`name`, `surname`, `pseudo`, `mail`, `grade`, `password`, `inscription_date`, `is_validate`, `user_id`, `registration_key`) VALUES
('Grandclement', 'Pierre', 'D4rkstrife', 'p.gdc85@gmail.com', 'superAdmin', '$2y$10$8cRhFES66gzPA3PS6ZN.f.sFSSdCENa2pCLmu92EdH6RkFnS6gV0m', '2021-07-20 22:59:27', 1, 10, '0'),
('Patrice', 'Dupont', 'Patoche', 'patoche@gmail.com', 'member', '$2y$10$d9oHQ1nahTiHlY3YUBXX5eUaVVPj0tmz0npsYo3isErXv8GBKPngW', '2021-07-20 23:00:04', 1, 11, '0'),
('Ellen', 'Sue', 'Heineken', 'sue.ellen@gmail.com', 'member', '$2y$10$NyRSaTq3dloQonDCZXDhMe1ZtGc9EhH7HhXZlHsRh5y79ObA5GhG.', '2021-07-26 08:12:01', 1, 20, '0'),
('Poulette', 'Jean', 'Cocotte', 'blabla@gmail.com', 'member', '$2y$10$Ru92Js56ojsk/gSCjJvOmeMNoEJu2LqMqO1PqE7.N2zxKHOutBAM2', '2021-07-31 17:03:04', 0, 22, '0'),
('bladj', 'khfshu', 'nnbxhg', 'blabl@gmail.com', 'member', '$2y$10$GbkFfK4bv9NAKWZs6tHgU.rmRJRrws3I8/09tvLzdCn6EBlH41C.e', '2021-08-01 07:16:12', 0, 23, '1c8a9531ed1fc4dbc1268a1a5261869a'),
('Docteur', 'Strife', 'Darkstrife', 'd4rkstrife@gmail.com', 'member', '$2y$10$jj3WuiLangwMTLBAv.ksEOHwc7AB6opPG1wbgdqiRgNiLbS38mAIa', '2021-08-01 08:48:07', 1, 39, 'ca3fe5f374bf2e324996868a0dae4409');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

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
