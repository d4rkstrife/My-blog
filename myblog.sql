-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mar. 20 juil. 2021 à 10:09
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
('Trop cool, hâte de te lire!', 1, 2, 1, '2021-06-30 03:06:43', 1),
('Génial!', 0, 3, 1, '2021-06-30 08:06:43', 2),
('Bienvenue!', 1, 4, 2, '2021-06-30 08:06:43', 3),
('Bienvenue à elle!', 1, 4, 7, '2021-06-30 08:06:43', 4),
('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sed malesuada mi. Vestibulum accumsan, sem sed iaculis rhoncus, libero justo feugiat mauris, a gravida sapien quam dignissim ipsum. Nulla vulputate varius lacus auctor ultricies. Morbi vel justo lectus. Donec sit amet suscipit odio, et dictum nibh. Phasellus sapien massa, semper at consequat eu, accumsan a neque. Sed imperdiet malesuada ligula ac posuere. Duis aliquam venenatis lacinia. ', 0, 4, 9, '2021-06-30 08:06:43', 6),
('hello', 0, 3, 2, '2021-07-14 08:23:03', 20),
('Elle est trop sympa en plus!', 0, 1, 7, '2021-07-19 10:35:26', 21);

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

CREATE TABLE `post` (
  `title` varchar(256) NOT NULL,
  `chapo` text NOT NULL,
  `content` longtext NOT NULL,
  `date` datetime NOT NULL,
  `fk_user` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `date_modif` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`title`, `chapo`, `content`, `date`, `fk_user`, `id`, `date_modif`) VALUES
('Mon premier billet', 'Venez vite lire mes premières impressions sur mon blog.', 'Trop content de mon nouveau blog je vais pouvoir raconter ma vie.', '2021-06-15 10:13:56', 1, 1, '2021-07-05 22:36:20'),
('Bonjour à tous', 'Enfin Admin, je vais pouvoir vous raconter ma vie!', 'Passionée d\'informatique depuis longtemps, venez lire mes futurs billets dans lesquels je vous raconterai toutes les anecdotes de mon parcours pro!', '2021-06-15 22:33:23', 5, 2, '2021-07-05 22:36:20'),
('Nouvelle Admin', 'Bienvenue à Hulule', 'Hulule vient de passer Admin! Pensez à la féliciter et à aller lire ses billets.', '2021-06-15 23:04:46', 1, 7, '2021-07-05 22:36:20'),
('Lorem Ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sed nunc urna. Cras imperdiet sit amet dui ut dictum. Ut a sodales risus, vel pretium diam. ', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sed nunc urna. Cras imperdiet sit amet dui ut dictum. Ut a sodales risus, vel pretium diam. Maecenas gravida velit dui, quis tincidunt augue pretium vitae. Fusce dictum tellus quis tellus ullamcorper finibus. Phasellus faucibus enim eget dui posuere ornare. Aenean viverra nisl vitae iaculis gravida. Pellentesque sed accumsan ante. In sed nisi at eros tincidunt luctus gravida a justo. Integer eget orci risus. Interdum et malesuada fames ac ante ipsum primis in faucibus.\r\n\r\nSed non massa orci. Etiam lacus felis, ultrices eu tempor a, pulvinar eu nisi. Aliquam id fringilla tortor. Sed consectetur, ligula a blandit placerat, nisi erat sagittis sapien, ac sagittis odio nibh sed neque. Nunc ante ante, tincidunt vitae blandit a, posuere hendrerit risus. Mauris ipsum ex, congue non quam ut, mollis vehicula erat. Mauris vel nunc tempor, placerat dui vel, rutrum eros. Phasellus tincidunt nisi vel leo posuere, nec suscipit nunc tristique.\r\n\r\nMauris at dui eleifend, condimentum metus nec, lobortis turpis. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Suspendisse non nisi laoreet, aliquet sem dignissim, pellentesque nisl. Sed pellentesque lorem a ligula mollis tristique. Nam faucibus diam id dolor mattis finibus. Phasellus vel pharetra odio, ut consequat justo. Phasellus at lacus non diam luctus posuere. Donec lacus odio, semper a ligula in, eleifend pharetra justo. Suspendisse elementum, ex non sagittis finibus, urna lectus aliquam arcu, at posuere nisl neque et dui. Phasellus volutpat tempus ex, ut faucibus purus efficitur sed. Suspendisse id est sit amet diam vestibulum placerat. Vivamus mi leo, egestas non ultricies ac, auctor eget arcu. Integer ut aliquet justo. Mauris nibh urna, elementum non ultrices quis, cursus vel lorem. Vestibulum vehicula augue erat, eu finibus ligula vulputate sed. Suspendisse lorem erat, elementum at nisl in, semper dignissim erat.\r\n\r\nCurabitur ac dui eu orci vestibulum ullamcorper in in orci. Phasellus consequat eu metus sagittis accumsan. Quisque eu cursus velit. Sed finibus ante at felis dignissim iaculis. Nullam sed dignissim turpis, finibus vehicula ante. Sed viverra mi vel leo sodales, vel dapibus turpis imperdiet. Etiam mollis fringilla tortor, nec maximus leo suscipit at. Pellentesque placerat et nunc at interdum. Proin convallis felis sollicitudin, consequat nunc ac, feugiat erat.\r\n\r\nSed eu aliquet dolor. Donec non ipsum tincidunt, euismod eros sed, efficitur odio. Ut id cursus eros. Aliquam elementum tristique aliquam. Donec ullamcorper augue at justo commodo accumsan. Proin bibendum dolor magna, non dictum massa tincidunt et. Etiam condimentum, dui vel consequat luctus, nibh orci pulvinar lectus, finibus iaculis odio diam ut dolor. Nullam rutrum tellus elit, a commodo lacus venenatis sed. Vivamus viverra rhoncus ipsum quis molestie. Maecenas euismod porta ipsum, vel elementum justo maximus id. Proin eleifend tortor nec dictum facilisis. Phasellus sit amet consequat ex. Vivamus eget ornare eros, eu fringilla neque. Vestibulum eleifend, odio eu lacinia faucibus, ante est tincidunt turpis, et iaculis augue neque id felis. Mauris aliquam turpis a nibh sollicitudin, in consequat sapien mattis. Cras imperdiet purus ut aliquam faucibus.\r\n\r\n', '2021-06-30 06:56:11', 1, 9, NULL);

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
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`name`, `surname`, `pseudo`, `mail`, `grade`, `password`, `inscription_date`, `is_validate`, `user_id`) VALUES
('Patrick', 'Dupont', 'Patoche', 'patoche@gmail.com', 'superAdmin', '12345', '2021-06-15 10:12:48', 1, 1),
('Antoine', 'Zavatta', 'Savate', 'zavatta@gmail.com', 'member', '12345', '2021-06-15 13:17:20', 1, 2),
('Felipa', 'Sanchez', 'Nounia', 'f.sanchez@hotmail.com', 'member', '12345', '2021-06-15 22:17:54', 1, 3),
('Farouk', 'Martin', 'Fartin', 'fartin@gmail.com', 'member', '12345', '2021-06-15 23:21:24', 1, 4),
('Carole', 'La Chouette', 'Hulule', 'hulule@gmail.com', 'admin', '12345', '2021-06-15 11:25:42', 1, 5),
('nom', 'prénom', 'pseudo', 'mail', 'member', '12345', '2021-07-20 11:34:22', 0, 6),
('Grandclement', 'Pierre', 'D4rkstrife', 'p.gdc85@gmail.com', 'superAdmin', '15ju1985', '2021-07-20 11:44:40', 1, 8),
('Bla', 'Blabla', 'Blablabla', 'blabla@gmail.com', 'member', '$2y$10$GFWGpTg9wiNf.lkD07HdHewh6w5XsFE68XrLETSBx/57vcSd2PFhG', '2021-07-20 12:00:50', 0, 9);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
