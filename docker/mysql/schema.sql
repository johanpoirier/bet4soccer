CREATE DATABASE IF NOT EXISTS `bets`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+02:00";

use bets;

--
-- Base de données :  `bets`
--

-- --------------------------------------------------------

--
-- Structure de la table `cdm2019__audit`
--

CREATE TABLE `cdm2019__audit` (
  `id` int(12) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userID` int(9) UNSIGNED NOT NULL,
  `category` varchar(9) DEFAULT NULL,
  `action` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `cdm2019__bets`
--

CREATE TABLE `cdm2019__bets` (
  `userID` int(9) UNSIGNED NOT NULL,
  `matchID` int(9) UNSIGNED NOT NULL,
  `scoreA` int(2) UNSIGNED DEFAULT NULL,
  `scoreB` int(2) UNSIGNED DEFAULT NULL,
  `teamA` int(9) UNSIGNED DEFAULT NULL,
  `teamB` int(9) UNSIGNED DEFAULT NULL,
  `teamW` enum('A','B') COLLATE utf8_general_ci DEFAULT NULL,
  `status` int(2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cdm2019__groups`
--

CREATE TABLE `cdm2019__groups` (
  `groupID` int(9) UNSIGNED NOT NULL,
  `password` varchar(30) COLLATE utf8_general_ci NOT NULL,
  `ownerID` int(9) UNSIGNED DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `avgPoints` float UNSIGNED NOT NULL DEFAULT '0',
  `totalPoints` int(9) UNSIGNED NOT NULL DEFAULT '0',
  `maxPoints` int(9) UNSIGNED NOT NULL DEFAULT '0',
  `lastRank` int(9) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cdm2019__invitations`
--

CREATE TABLE `cdm2019__invitations` (
  `code` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `senderID` int(9) UNSIGNED NOT NULL,
  `groupID` int(9) UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `expiration` datetime NOT NULL,
  `status` int(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cdm2019__matches`
--

CREATE TABLE `cdm2019__matches` (
  `matchID` int(9) UNSIGNED NOT NULL,
  `teamA` int(9) UNSIGNED DEFAULT NULL,
  `teamB` int(9) UNSIGNED DEFAULT NULL,
  `scoreA` int(2) UNSIGNED DEFAULT NULL,
  `scoreB` int(2) UNSIGNED DEFAULT NULL,
  `teamW` enum('A','B') COLLATE utf8_general_ci DEFAULT NULL,
  `date` datetime NOT NULL,
  `round` int(2) UNSIGNED DEFAULT NULL,
  `rank` int(2) UNSIGNED DEFAULT NULL,
  `status` int(2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cdm2019__settings`
--

CREATE TABLE `cdm2019__settings` (
  `name` varchar(35) COLLATE utf8_general_ci NOT NULL,
  `value` varchar(35) COLLATE utf8_general_ci DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `status` int(2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cdm2019__stats_user`
--

CREATE TABLE `cdm2019__stats_user` (
  `userID` int(9) UNSIGNED NOT NULL DEFAULT '0',
  `label` varchar(30) COLLATE utf8_general_ci NOT NULL,
  `rank` int(5) UNSIGNED NOT NULL DEFAULT '1',
  `rank_group` int(5) UNSIGNED NOT NULL DEFAULT '1',
  `points` int(9) UNSIGNED NOT NULL DEFAULT '0',
  `nbresults` int(5) UNSIGNED NOT NULL DEFAULT '0',
  `nbscores` int(5) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cdm2019__tags`
--

CREATE TABLE `cdm2019__tags` (
  `tagID` int(9) UNSIGNED NOT NULL,
  `userID` int(9) UNSIGNED NOT NULL,
  `groupID` int(9) UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL,
  `tag` text COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cdm2019__teams`
--

CREATE TABLE `cdm2019__teams` (
  `teamID` int(9) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8_general_ci NOT NULL,
  `fifaRank` int(4) NOT NULL DEFAULT '0',
  `pool` char(1) COLLATE utf8_general_ci NOT NULL,
  `status` int(2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Structure de la table `cdm2019__tokens`
--

CREATE TABLE `cdm2019__tokens` (
  `userID` int(9) UNSIGNED NOT NULL,
  `device` VARCHAR(36) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `token` VARCHAR(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Structure de la table `cdm2019__users`
--

CREATE TABLE `cdm2019__users` (
  `userID` int(9) UNSIGNED NOT NULL,
  `name` varchar(40) COLLATE utf8_general_ci NOT NULL,
  `login` varchar(30) COLLATE utf8_general_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_general_ci DEFAULT NULL,
  `points` int(9) UNSIGNED DEFAULT '0',
  `nbresults` int(9) UNSIGNED DEFAULT '0',
  `nbscores` int(9) UNSIGNED DEFAULT '0',
  `diff` int(9) DEFAULT '0',
  `last_rank` int(9) UNSIGNED DEFAULT '1',
  `groupID` int(9) UNSIGNED DEFAULT NULL,
  `groupID2` int(9) UNSIGNED DEFAULT NULL,
  `groupID3` int(9) UNSIGNED DEFAULT NULL,
  `theme` varchar(60) COLLATE utf8_general_ci DEFAULT NULL,
  `match_display` varchar(10) COLLATE utf8_general_ci DEFAULT NULL,
  `status` int(2) UNSIGNED NOT NULL DEFAULT '0',
  `last_connection` timestamp NULL DEFAULT NULL,
  `last_bet` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Structure de la table `bet4soccer__competitions`
--

CREATE TABLE `bet4soccer__competitions` (
  `id` mediumint(9) NOT NULL,
  `domain` varchar(20) NOT NULL DEFAULT 'Public',
  `name` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Structure de la table `bet4soccer__palmares`
--

CREATE TABLE `bet4soccer__palmares` (
  `id` mediumint(9) NOT NULL,
  `competitionId` mediumint(9) NOT NULL,
  `userName` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `userPoints` smallint(6) NOT NULL,
  `userResults` smallint(6) NOT NULL,
  `userScores` smallint(6) NOT NULL,
  `userDiff` smallint(6) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `cdm2019__audit`
--
ALTER TABLE `cdm2019__audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_date` (`date`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `userID` (`userID`);

--
-- Index pour la table `cdm2019__bets`
--
ALTER TABLE `cdm2019__bets`
ADD PRIMARY KEY (`userID`,`matchID`),
ADD KEY `teamA` (`teamA`),
ADD KEY `teamB` (`teamB`),
ADD KEY `matchID` (`matchID`);

--
-- Index pour la table `cdm2019__groups`
--
ALTER TABLE `cdm2019__groups`
ADD PRIMARY KEY (`groupID`),
ADD UNIQUE KEY `name` (`name`),
ADD KEY `ownerID` (`ownerID`);

--
-- Index pour la table `cdm2019__invitations`
--
ALTER TABLE `cdm2019__invitations`
  ADD PRIMARY KEY (`code`),
  ADD KEY `groupID` (`groupID`);

--
-- Index pour la table `cdm2019__matches`
--
ALTER TABLE `cdm2019__matches`
  ADD PRIMARY KEY (`matchID`),
  ADD UNIQUE KEY `round` (`round`,`rank`),
  ADD KEY `teamA` (`teamA`),
  ADD KEY `teamB` (`teamB`);

--
-- Index pour la table `cdm2019__settings`
--
ALTER TABLE `cdm2019__settings`
  ADD PRIMARY KEY (`name`);

--
-- Index pour la table `cdm2019__stats_user`
--
ALTER TABLE `cdm2019__stats_user`
  ADD KEY `userID` (`userID`);

--
-- Index pour la table `cdm2019__tags`
--
ALTER TABLE `cdm2019__tags`
  ADD PRIMARY KEY (`tagID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `groupID` (`groupID`);

--
-- Index pour la table `cdm2019__teams`
--
ALTER TABLE `cdm2019__teams`
  ADD PRIMARY KEY (`teamID`);

--
-- Index pour la table `cdm2019__tokens`
--
ALTER TABLE `cdm2019__tokens`
  ADD PRIMARY KEY (`userID`,`device`);

--
-- Index pour la table `cdm2019__users`
--
ALTER TABLE `cdm2019__users`
  ADD PRIMARY KEY (`userID`),
  ADD KEY `login` (`login`);

--
-- Index pour la table `bet4soccer__competitions`
--
ALTER TABLE `bet4soccer__competitions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `domain` (`domain`,`name`),
  ADD KEY `domain_2` (`domain`,`name`);

--
-- Index pour la table `bet4soccer__palmares`
--
ALTER TABLE `bet4soccer__palmares`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userNameIndex` (`userName`),
  ADD KEY `instanceNameIndex` (`competitionId`);


--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `cdm2019__audit`
--
ALTER TABLE `cdm2019__audit`
  MODIFY `id` int(12) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `cdm2019__groups`
--
ALTER TABLE `cdm2019__groups`
MODIFY `groupID` int(9) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `cdm2019__matches`
--
ALTER TABLE `cdm2019__matches`
MODIFY `matchID` int(9) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `cdm2019__tags`
--
ALTER TABLE `cdm2019__tags`
MODIFY `tagID` int(9) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `cdm2019__teams`
--
ALTER TABLE `cdm2019__teams`
MODIFY `teamID` int(9) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `cdm2019__users`
--
ALTER TABLE `cdm2019__users`
MODIFY `userID` int(9) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `bet4soccer__competitions`
--
ALTER TABLE `bet4soccer__competitions`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `bet4soccer__palmares`
--
ALTER TABLE `bet4soccer__palmares`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `cdm2019__bets`
--
ALTER TABLE `cdm2019__bets`
ADD CONSTRAINT `cdm2019__bets_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `cdm2019__users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `cdm2019__bets_ibfk_2` FOREIGN KEY (`matchID`) REFERENCES `cdm2019__matches` (`matchID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `cdm2019__bets_ibfk_3` FOREIGN KEY (`teamA`) REFERENCES `cdm2019__teams` (`teamID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `cdm2019__bets_ibfk_4` FOREIGN KEY (`teamB`) REFERENCES `cdm2019__teams` (`teamID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `cdm2019__groups`
--
ALTER TABLE `cdm2019__groups`
ADD CONSTRAINT `cdm2019__groups_ibfk_1` FOREIGN KEY (`ownerID`) REFERENCES `cdm2019__users` (`userID`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Contraintes pour la table `cdm2019__invitations`
--
ALTER TABLE `cdm2019__invitations`
ADD CONSTRAINT `cdm2019__invitations_ibfk_1` FOREIGN KEY (`groupID`) REFERENCES `cdm2019__groups` (`groupID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `cdm2019__invitations_ibfk_10` FOREIGN KEY (`groupID`) REFERENCES `cdm2019__groups` (`groupID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `cdm2019__matches`
--
ALTER TABLE `cdm2019__matches`
ADD CONSTRAINT `cdm2019__matches_ibfk_1` FOREIGN KEY (`teamA`) REFERENCES `cdm2019__teams` (`teamID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `cdm2019__matches_ibfk_2` FOREIGN KEY (`teamB`) REFERENCES `cdm2019__teams` (`teamID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `cdm2019__stats_user`
--
ALTER TABLE `cdm2019__stats_user`
ADD CONSTRAINT `cdm2019__stats_user_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `cdm2019__users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `cdm2019__tags`
--
ALTER TABLE `cdm2019__tags`
ADD CONSTRAINT `cdm2019__tags_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `cdm2019__users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `cdm2019__tags_ibfk_2` FOREIGN KEY (`groupID`) REFERENCES `cdm2019__groups` (`groupID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `cdm2019__tokens`
--
ALTER TABLE `cdm2019__tokens`
  ADD CONSTRAINT `cdm2019__tokens_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `cdm2019__users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;



--
-- Contenu pour les tables exportées
--

INSERT INTO `cdm2019__settings` (`name`, `value`, `date`, `status`) VALUES
  ('IS_GROUP_RANKING_GENERATING', '', NULL, 0),
  ('IS_RANKING_GENERATING', '', '0000-00-00 00:00:00', 0),
  ('IS_USER_RANKING_GENERATING', '', NULL, 0),
  ('LAST_GENERATE', NULL, '2014-07-13 23:36:35', 0),
  ('LAST_RESULT', NULL, '2014-06-27 11:43:55', 0),
  ('MONEY', '2', NULL, 0),
  ('NB_MATCHES_GENERATED', '0', NULL, 0),
  ('RANK_GROUPS_UPDATE', NULL, '2014-07-13 23:36:35', 0),
  ('RANK_UPDATE', NULL, '2014-07-13 23:36:34', 0);

INSERT INTO `cdm2019__users` (`userID`, `name`, `login`, `password`, `email`, `status`) VALUES
  (1, 'John Foo', 'admin', 'c87a9050eb2f1734881f89e638770e4317abb184eb69a9fbdb35d24d11d14254', 'admin@bet4soccer.fr', 1);

INSERT INTO `cdm2019__teams` (`teamID`, `name`, `fifaRank`, `pool`, `status`) VALUES
  (1, 'Russie', '17', 'A', 1),
  (2, 'Arabie Saoudite', '24', 'A', 1),
  (3, 'Egypte', '8', 'A', 1),
  (4, 'Uruguay', '32', 'A', 1);

INSERT INTO `cdm2019__matches` (`matchID`, `teamA`, `teamB`, `scoreA`, `scoreB`, `date`) VALUES
  (1, 1, 2, NULL, NULL, '2018-06-14 17:00:00'),
  (2, 3, 4, NULL, NULL, '2018-06-15 14:00:00');

INSERT INTO `cdm2019__bets` (`userID`, `matchID`, `scoreA`, `scoreB`) VALUES (1, 1, 2, 1);


INSERT INTO `bet4soccer__competitions` (`id`, `domain`, `name`, `startDate`, `endDate`) VALUES
(1, 'Public', 'Coupe du monde de rugby à XV, Nouvelle-Zélande 2011', '2011-09-09', '2011-10-23'),
(2, 'Public', 'Coupe du monde de la FIFA, Brésil 2014', '2014-06-12', '2014-07-13'),
(3, 'Public', 'Coupe du monde de rugby à XV, Angleterre 2015', '2015-09-18', '2015-10-31'),
(4, 'Public', 'Coupe du monde de la FIFA, Russie 2018', '2018-06-14', '2018-07-15'),
(8, 'Public', 'UEFA Euro 2012, Pologne-Ukraine', '2012-06-08', '2012-07-01'),
(9, 'Public', 'UEFA Euro 2016, France', '2016-06-10', '2016-07-10'),
(11, 'FLOP', 'Coupe du monde de la FIFA, Brésil 2014', '2014-06-12', '2014-07-13');

INSERT INTO `bet4soccer__palmares` (`id`, `competitionId`, `userName`, `userPoints`, `userResults`, `userScores`)
VALUES (1, 1, 'Raymond Cinquin', 585, 43, 6),
(2, 1, 'Louison Michot', 549, 42, 8),
(3, 1, 'Nicolas Flajolet', 540, 43, 3),
(4, 1, 'Cyril Poirier', 539, 42, 3),
(5, 1, 'Jérome Tisné', 522, 39, 10),
(6, 1, 'Jean-Pierre Flajolet', 518, 41, 4),
(7, 1, 'Christophe Bernard', 517, 41, 3),
(8, 1, 'Emmanuel Manhes', 514, 41, 2),
(9, 1, 'Florent Malgras', 509, 41, 2),
(10, 1, 'Sylvain Poirier', 508, 41, 6),
(11, 1, 'Bruno Miralles', 507, 41, 3),
(12, 1, 'Fred Lacombe', 504, 40, 3),
(13, 1, 'Frederic Goffette', 502, 40, 3),
(14, 1, 'Christophe Vacheron', 499, 40, 2),
(15, 1, 'Guillaume Lecus', 497, 40, 3),
(16, 1, 'Raphael Jeanton', 495, 40, 1),
(17, 1, 'Julie Luquet', 488, 40, 2),
(18, 1, 'Christophe Cobelli', 486, 38, 6),
(19, 1, 'Johan Poirier', 486, 39, 3),
(20, 1, 'Simon Minet', 485, 39, 2),
(21, 1, 'Francois Wuillaume', 485, 39, 5),
(22, 1, 'Michel Poirier', 479, 39, 3),
(23, 1, 'Gaelle Paraire', 476, 38, 2),
(24, 1, 'Serge Poirier', 475, 39, 3),
(25, 1, 'Michèle Poirier', 470, 38, 2),
(26, 1, 'Antoine Desvignes', 468, 38, 0),
(27, 1, 'Pascal Gustin', 467, 39, 1),
(28, 1, 'Ricardo Ramassamy', 467, 37, 5),
(29, 1, 'Therese Flajolet', 466, 39, 1),
(30, 1, 'David Bouteille', 464, 37, 5),
(31, 1, 'Jean-Hugues Robert', 464, 36, 3),
(32, 1, 'Louis Paraire', 462, 36, 2),
(33, 1, 'Xavier Laprêté', 455, 39, 2),
(34, 1, 'Peter Greenpillar', 447, 37, 1),
(35, 1, 'Marc Daudier', 447, 36, 2),
(36, 1, 'Joachim Mathieu', 446, 36, 2),
(37, 1, 'Olivier Paraire', 446, 37, 1),
(38, 1, 'Dorian Ferry', 436, 37, 0),
(39, 1, 'Héloïse Poirier', 434, 35, 2),
(40, 1, 'Francois Sousset', 433, 35, 4),
(41, 1, 'Fabien Flajolet', 430, 36, 1),
(42, 1, 'Guillaume Besset', 427, 36, 1),
(43, 1, 'Lionel Rival', 424, 35, 1),
(44, 1, 'Frédéric Ronsin', 422, 35, 2),
(45, 1, 'Jean-Etienne Peroz', 417, 33, 2),
(46, 1, 'Rémi Parard', 414, 35, 3),
(47, 1, 'Olivier Desmons', 403, 33, 6),
(48, 1, 'Hélène Mermet', 399, 35, 0),
(49, 1, 'Sebastien Dubuisson', 397, 34, 2),
(50, 1, 'Rodolphe Lemoine', 396, 34, 3),
(51, 1, 'Damien Dherret', 389, 33, 0),
(52, 1, 'Jamy Rondeau', 365, 32, 2),
(53, 1, 'Fabien Peyre', 362, 32, 2),
(54, 1, 'Paul Paul', 357, 33, 0),
(55, 1, 'Luarent Delley', 349, 32, 1),
(56, 1, 'Olivier Marchand', 344, 31, 1),
(57, 1, 'E H', 331, 28, 5),
(58, 1, 'Alban Paraire', 303, 28, 1),
(59, 1, 'Denis Vannier', 296, 26, 1),
(60, 1, 'Magaly Andrieu', 174, 15, 1),
(61, 1, 'Yoann Valla', 148, 12, 0),
(62, 1, 'Anthony Colas', 113, 10, 0),
(63, 1, 'Ludovic Lavigne', 110, 9, 1),
(64, 1, 'Arnaud Duperray', 107, 9, 1),
(65, 1, 'Christophe Lampin', 99, 9, 0),
(66, 1, 'Maxime Duclaux', 88, 7, 1),
(67, 1, 'Olivier Courtine', 81, 7, 1),
(68, 1, 'Jean-Yves Weinsberg', 78, 7, 0),
(69, 1, 'Romain Lenoel', 70, 6, 0),
(70, 1, 'Yann Robin', 67, 6, 0),
(71, 1, 'Régis Clermidy', 57, 5, 0),
(72, 1, 'Grégory Boulbès', 34, 3, 0),
(73, 1, 'Cedric Simon', 21, 2, 0),
(74, 1, 'Pierre-xavier Feige', 13, 1, 0),
(75, 3, 'Cyril Poirier', 573, 43, 5),
(76, 3, 'Bruno Miralles', 572, 43, 6),
(77, 3, 'Serge Poirier', 568, 42, 7),
(78, 3, 'Nicolas Flajolet', 559, 42, 4),
(79, 3, 'Sylvain Poirier', 559, 43, 6),
(80, 3, 'Simon Minet', 548, 42, 5),
(81, 3, 'Stephane Peroz', 547, 42, 3),
(82, 3, 'Romain Crétier', 546, 43, 2),
(83, 3, 'Benjamin Benoit', 540, 41, 7),
(84, 3, 'David Bouteille', 534, 40, 7),
(85, 3, 'Fabien Flajolet', 532, 39, 5),
(86, 3, 'Michel Poirier', 526, 40, 5),
(87, 3, 'Jean-Pierre Flajolet', 519, 41, 3),
(88, 3, 'Ricardo Ramassamy', 514, 40, 4),
(89, 3, 'Jean-Etienne Peroz', 510, 39, 5),
(90, 3, 'Michèle Poirier', 508, 39, 3),
(91, 3, 'Jérome Tisné', 503, 39, 3),
(92, 3, 'Antoine Desvignes', 496, 38, 4),
(93, 3, 'Tan-Boûy Kim', 495, 39, 2),
(94, 3, 'Aurélien Seguela', 492, 40, 5),
(95, 3, 'Christophe Cobelli', 490, 40, 2),
(96, 3, 'Julien Rocheteau', 489, 38, 5),
(97, 3, 'Therese Flajolet', 484, 38, 3),
(98, 3, 'Sylvain Rocca', 482, 38, 5),
(99, 3, 'Yannick Le Dean', 478, 39, 5),
(100, 3, 'Florian Prunier', 477, 36, 4),
(101, 3, 'Gaelle Poirier', 467, 37, 4),
(102, 3, 'Stéphan Colombo', 464, 36, 4),
(103, 3, 'Frédéric Lacombe', 464, 38, 2),
(104, 3, 'Johan Poirier', 457, 36, 1),
(105, 3, 'Frédéric Goffette', 454, 36, 3),
(106, 3, 'Raphael Trichard', 446, 35, 5),
(107, 3, 'Loïc Chabert', 435, 37, 1),
(108, 3, 'Florent Malgras', 422, 34, 5),
(109, 3, 'Yo Kaz', 415, 34, 4),
(110, 3, 'Louis Paraire', 407, 32, 3),
(111, 3, 'Peter Greenpillar', 394, 33, 2),
(112, 3, 'Olivier Paraire', 365, 32, 1),
(113, 3, 'Laurent Verschelde', 363, 31, 3),
(114, 3, 'Frédéric Gelvé', 343, 29, 1),
(115, 3, 'Dominique Delavalle', 338, 31, 1),
(116, 3, 'Riadh Klai', 334, 28, 2),
(117, 3, 'Alban Paraire', 327, 29, 2),
(118, 3, 'Guillaume Besset', 322, 28, 3),
(119, 3, 'Jonathan Caillaud', 251, 20, 2),
(120, 3, 'Samuel Tocanier', 200, 17, 1),
(121, 3, 'André Munerez', 187, 17, 1),
(122, 3, 'Mélodie Hazé', 175, 15, 3),
(123, 3, 'Laurent Burgat', 74, 6, 1),
(124, 3, 'Bertrand Legallou', 39, 3, 1),
(125, 3, 'Stéphanie Dif', 31, 3, 0),
(126, 8, 'Philippe Zébo', 39, 21, 5),
(127, 8, 'Mustapha Lakhdar Chaouch', 36, 18, 5),
(128, 8, 'Michèle Poirier', 33, 19, 4),
(129, 8, 'Béatrice Banry', 33, 17, 7),
(130, 8, 'Philippe Beuvier', 32, 20, 4),
(131, 8, 'Eric Jester', 32, 20, 4),
(132, 8, 'Thibaut Martinez', 32, 19, 5),
(133, 8, 'Alban Paraire', 32, 17, 3),
(134, 8, 'Louis Paraire', 32, 18, 5),
(135, 8, 'Tony', 31, 14, 4),
(136, 8, 'Mathieu Dubreuil', 31, 18, 4),
(137, 8, 'Sébastien Gauthier', 31, 16, 5),
(138, 8, 'Arnaud Chambrillon', 30, 17, 5),
(139, 8, 'Romain Crétier', 30, 18, 3),
(140, 8, 'Gaëlle Poirier', 30, 18, 4),
(141, 8, 'Helene Mermet', 30, 20, 2),
(142, 8, 'Pascal Gustin', 30, 18, 3),
(143, 8, 'Alexandre Poirier', 29, 18, 2),
(144, 8, 'Philipe Sillon', 29, 15, 5),
(145, 8, 'Valérie Travaillot', 29, 19, 6),
(146, 8, 'Damien Boisson', 29, 18, 7),
(147, 8, 'Denis Bouteillon', 29, 16, 5),
(148, 8, 'Pauline Michot', 29, 16, 4),
(149, 8, 'Simon Minet', 29, 20, 5),
(150, 8, 'Arnaud Sebert', 29, 21, 5),
(151, 8, 'Sebastien Grenon', 29, 17, 4),
(152, 8, 'Franck Gallier', 28, 18, 4),
(153, 8, 'Stephane Peroz', 28, 18, 1),
(154, 8, 'Stephan Colombo', 28, 18, 3),
(155, 8, 'Fred Bike', 28, 17, 3),
(156, 8, 'Antoine Desvignes', 28, 16, 5),
(157, 8, 'Tibo Lenne', 28, 18, 5),
(158, 8, 'François Avocat-Maulaz', 28, 19, 4),
(159, 8, 'Tan-Boûy Kim', 28, 18, 2),
(160, 8, 'Daivy Piccini', 27, 16, 5),
(161, 8, 'Jean-Etienne Peroz', 27, 19, 3),
(162, 8, 'Gontran Guignard', 27, 18, 5),
(163, 8, 'Julien Duchene', 27, 18, 5),
(164, 8, 'Johanna Janiszewski', 27, 17, 1),
(165, 8, 'Charline Moello', 26, 16, 3),
(166, 8, 'Leo Holzer', 26, 17, 5),
(167, 8, 'Yvan Colin', 25, 16, 3),
(168, 8, 'Tayeb Bouzahzah', 25, 14, 4),
(169, 8, 'Cédric Charreton', 25, 16, 4),
(170, 8, 'Jean-Hugues Robert', 25, 16, 3),
(171, 8, 'Gildas Janot', 25, 15, 1),
(172, 8, 'Olivier Paraire', 25, 16, 2),
(173, 8, 'Samuel Kergourlay', 25, 17, 4),
(174, 8, 'Romain Arquilliere', 25, 14, 3),
(175, 8, '플로랑 Malgras', 25, 15, 4),
(176, 8, 'Raphael Buathier', 25, 18, 4),
(177, 8, 'Pascal Balouzat', 25, 14, 4),
(178, 8, 'Samuel Bullier', 24, 16, 1),
(179, 8, 'Fabrizio Colochini', 24, 14, 4),
(180, 8, 'Julien Proietto', 24, 13, 3),
(181, 8, 'Peter Greenpillar', 24, 17, 3),
(182, 8, 'Thibaud Banry', 24, 14, 2),
(183, 8, 'Fabien Flajolet', 24, 15, 4),
(184, 8, 'Solène Maudet', 24, 17, 3),
(185, 8, 'Bruno Miralles', 24, 15, 3),
(186, 8, 'Joel Bigotte', 23, 16, 3),
(187, 8, 'Gaëtan Babonneau', 23, 16, 4),
(188, 8, 'Damien Gourdon', 23, 17, 2),
(189, 8, 'Hassan Hassahou', 23, 15, 3),
(190, 8, 'Rémi Provens', 23, 15, 5),
(191, 8, 'Vincent Desvignes', 23, 13, 4),
(192, 8, 'Alexandre Merle', 23, 15, 5),
(193, 8, 'Samuel Coutanceau', 23, 15, 3),
(194, 8, 'Sébastien Divenah', 23, 12, 4),
(195, 8, 'Anne-Isabelle Reynaud', 23, 14, 5),
(196, 8, 'Charly Faure', 23, 13, 2),
(197, 8, 'Serge Poirier', 23, 13, 2),
(198, 8, 'Pascal Banry', 23, 17, 1),
(199, 8, 'Michael Jennequin', 23, 16, 4),
(200, 8, 'GrosDoigts', 23, 14, 3),
(201, 8, 'Romain Dacosta', 22, 15, 3),
(202, 8, 'Christophe Roustan', 22, 16, 4),
(203, 8, 'Frédéric Artigou', 22, 15, 3),
(204, 8, 'Christophe Lopez', 22, 16, 4),
(205, 8, 'Ma Lausan', 22, 14, 4),
(206, 8, 'Sedat Orhan', 22, 13, 4),
(207, 8, 'André Uhmann', 22, 16, 4),
(208, 8, 'Ozturk Kayisci', 22, 15, 3),
(209, 8, 'Samir Zizou', 22, 14, 3),
(210, 8, 'Sebastien Bera', 22, 16, 2),
(211, 8, 'Lionel Pascal', 21, 15, 2),
(212, 8, 'Michel Roth', 21, 14, 4),
(213, 8, 'Denis Pirat', 21, 13, 2),
(214, 8, 'Anthony Droz-vincent', 21, 13, 5),
(215, 8, 'Guillaume Besset', 21, 15, 2),
(216, 8, 'Christophe Tanguy', 21, 15, 3),
(217, 8, 'Olivier Boud\'boude', 21, 11, 1),
(218, 8, 'Ab Benlau', 21, 10, 1),
(219, 8, 'Ruben Theresine', 21, 14, 3),
(220, 8, 'Stéphane Lebreton', 21, 14, 1),
(221, 8, 'Olivier Devers', 21, 13, 1),
(222, 8, 'Benjamin Merle', 21, 12, 4),
(223, 8, 'Axel Busolin', 21, 13, 5),
(224, 8, 'Frédéric Goffette', 21, 15, 2),
(225, 8, 'Julien Blanc', 21, 16, 1),
(226, 8, 'Mohamed Gaaloul', 21, 15, 5),
(227, 8, 'William Jeunet', 21, 13, 1),
(228, 8, 'Guillaume Poirier', 21, 15, 0),
(229, 8, 'Jean-Pierre Flajolet', 21, 17, 1),
(230, 8, 'Aziz Dahmani', 21, 15, 3),
(231, 8, 'Johan Poirier', 21, 15, 3),
(232, 8, 'Nathalie Closier', 21, 14, 4),
(233, 8, 'Christophe Cobelli', 21, 15, 3),
(234, 8, 'Vico Joate', 20, 14, 3),
(235, 8, 'Jerome Valdivia', 20, 11, 3),
(236, 8, 'Guillaume Laleure', 20, 11, 1),
(237, 8, 'Simon Garrido', 20, 11, 1),
(238, 8, 'Lionel Leveugle', 20, 14, 3),
(239, 8, 'Nicolas Flajolet', 20, 14, 3),
(240, 8, 'Jean-Charles Blanc', 20, 13, 3),
(241, 8, 'Nicolas Chevallier', 20, 13, 4),
(242, 8, 'Stan Ikan', 20, 14, 3),
(243, 8, 'David Mendes', 20, 15, 2),
(244, 8, 'Leecharlesperry', 20, 14, 3),
(245, 8, 'Fabien Delwal', 20, 14, 2),
(246, 8, 'Cyril Poirier', 20, 14, 3),
(247, 8, 'Michael Gaggioli', 20, 16, 1),
(248, 8, 'Mathieu Zaza', 20, 14, 2),
(249, 8, 'Antoine Bonnin', 20, 15, 3),
(250, 8, 'Jean-Christophe Pillon', 20, 14, 1),
(251, 8, 'Luc Gentil', 20, 15, 1),
(252, 8, 'Julien Vandenhende', 20, 14, 3),
(253, 8, 'Jean-Marc Duval', 20, 15, 2),
(254, 8, 'Alain Besset', 20, 13, 3),
(255, 8, 'Nicolas Martin', 19, 13, 4),
(256, 8, 'Thérèse Flajolet', 19, 14, 3),
(257, 8, 'Laurent Lacombe', 19, 13, 4),
(258, 8, 'Sylvain Bonnot', 19, 14, 2),
(259, 8, 'Badr El Arja', 19, 12, 1),
(260, 8, 'Carla Viana', 19, 13, 3),
(261, 8, 'Morgane Ribeyron', 19, 8, 2),
(262, 8, 'Damien Pulice', 19, 14, 3),
(263, 8, 'Ricardo Ramassamy', 19, 13, 3),
(264, 8, 'Yoann Poette', 19, 16, 1),
(265, 8, 'Alexandre Poirot', 19, 14, 0),
(266, 8, 'Michel Poirier', 19, 10, 1),
(267, 8, 'Angel Marguet', 19, 13, 3),
(268, 8, 'Nicolas Paluszek', 19, 13, 3),
(269, 8, 'Antony Hugues', 18, 12, 4),
(270, 8, 'Eric Bad', 18, 15, 2),
(271, 8, 'Sergio Vazquez', 18, 13, 2),
(272, 8, 'Florence Boisseaud', 18, 12, 3),
(273, 8, 'Sylvain Poirier', 18, 12, 3),
(274, 8, 'Tommy Fumat', 18, 12, 2),
(275, 8, 'Ronan le Cunff', 18, 14, 2),
(276, 8, 'Frédéric Bichet', 18, 15, 0),
(277, 8, 'Vulcain2B', 18, 14, 1),
(278, 8, 'Pierre-Emmanuel Vincent', 18, 14, 4),
(279, 8, 'Bob L\'Eponge', 18, 13, 1),
(280, 8, 'Stephane Amoudruz', 18, 14, 1),
(281, 8, 'Pierre Dauendorffer', 18, 12, 4),
(282, 8, 'Brice Desbois', 18, 12, 3),
(283, 8, 'Yannick Le Dean', 17, 11, 2),
(284, 8, 'Félix Theresine', 17, 11, 1),
(285, 8, 'Sylvain Guillet', 17, 12, 3),
(286, 8, 'Jonathan Bard', 17, 12, 1),
(287, 8, 'Karima Barni', 17, 13, 1),
(288, 8, 'Danielle Rollet', 17, 12, 1),
(289, 8, 'Tomtom Devers', 17, 13, 2),
(290, 8, 'Frédéric Lacombe', 17, 12, 3),
(291, 8, 'Stephane Durieux', 17, 14, 0),
(292, 8, 'Thibaut Lacroix', 17, 13, 2),
(293, 8, 'Alexandre Bercion', 16, 12, 1),
(294, 8, 'Nelly Druguet', 16, 11, 3),
(295, 8, 'Pascal Bardouil', 16, 11, 3),
(296, 8, 'Héloïse Poirier', 16, 12, 1),
(297, 8, 'Simon Roux', 16, 12, 4),
(298, 8, 'Matthieu Conseil', 16, 13, 2),
(299, 8, 'Laurent Delhautal', 16, 10, 2),
(300, 8, 'Mathieu Arnaudon', 16, 12, 4),
(301, 8, 'Romain Perrichon', 16, 11, 1),
(302, 8, 'Jeremie Sagnard', 16, 12, 3),
(303, 8, 'Gilles Vilquin', 16, 13, 0),
(304, 8, 'Thomas Laget', 16, 13, 1),
(305, 8, 'Younes Azizi', 16, 11, 2),
(306, 8, 'Mathieu Banry', 16, 13, 1),
(307, 8, 'Hugo Leonforte', 15, 13, 2),
(308, 8, 'Arnaud Conseil', 15, 11, 2),
(309, 8, 'Franck Gay', 15, 10, 2),
(310, 8, 'Hassen Ech Chatoui', 15, 11, 2),
(311, 8, 'Jeremy Guillemot', 15, 10, 2),
(312, 8, 'Johnny Busolin', 15, 11, 0),
(313, 8, 'Hamza Herrou', 15, 10, 1),
(314, 8, 'David Bouteille', 14, 9, 3),
(315, 8, 'Florian Sutter', 14, 9, 3),
(316, 8, 'Michel Santschi', 14, 11, 0),
(317, 8, 'Boris Feasson', 14, 12, 1),
(318, 8, 'Thomas Schalburg', 14, 11, 0),
(319, 8, 'Yves Rigaudier', 13, 9, 1),
(320, 8, 'Pierre-Yves Toupé', 13, 10, 3),
(321, 8, 'Miki Miki', 13, 11, 1),
(322, 8, 'Christophe Gualtieri', 13, 10, 1),
(323, 8, 'Jerome Tisné', 13, 9, 3),
(324, 8, 'Halim Naitssi', 13, 10, 1),
(325, 8, 'Julien Roux', 12, 7, 2),
(326, 8, 'Claudia Girod', 12, 8, 1),
(327, 8, 'Valéry Vaillant', 12, 8, 2),
(328, 8, 'Pierre Feige', 12, 10, 0),
(329, 8, 'Laura Holzer', 12, 7, 2),
(330, 8, 'Nico Bich', 11, 8, 0),
(331, 8, 'Lucien', 11, 10, 1),
(332, 8, 'Romain Rizzarello', 9, 8, 0),
(333, 9, 'Sam Lacroix', 406, 32, 11),
(334, 9, 'Lilia Vardparonyan', 375, 30, 8),
(335, 9, 'Nicolas Vermogen', 370, 32, 6),
(336, 9, 'Antoine Desvignes', 359, 27, 10),
(337, 9, 'Amandine Lacombe', 356, 29, 9),
(338, 9, 'Fidel Canari', 355, 31, 7),
(339, 9, 'Thibaut Lacroix', 352, 28, 10),
(340, 9, 'Qone Blotinho', 351, 28, 5),
(341, 9, 'Cyril Poirier', 351, 27, 8),
(342, 9, 'Hassen Ech Chatoui', 348, 28, 9),
(343, 9, 'Enora Deniel', 347, 25, 11),
(344, 9, 'Guillaume Poirier', 346, 28, 8),
(345, 9, 'Louis L', 346, 26, 10),
(346, 9, 'Oriane Morange', 344, 28, 5),
(347, 9, 'Pierre Arlotti', 343, 27, 9),
(348, 9, 'Alexandre Paluszek', 340, 29, 6),
(349, 9, 'JB Alexandre', 340, 29, 8),
(350, 9, 'Nelly Druguet', 340, 26, 9),
(351, 9, 'Sarreto', 339, 28, 7),
(352, 9, 'Benjamin Lhomme', 339, 26, 6),
(353, 9, 'Mélanie Fuchs', 339, 26, 7),
(354, 9, 'Philipe Sillon', 338, 28, 8),
(355, 9, 'David Bouteille', 337, 27, 7),
(356, 9, 'Sylvain Largier', 337, 28, 7),
(357, 9, 'Félix Theresine', 336, 27, 6),
(358, 9, 'Maïmouna Bâ', 336, 28, 7),
(359, 9, 'Michel Poirier', 335, 28, 6),
(360, 9, 'Johanna Janiszewski', 335, 27, 8),
(361, 9, 'Céline Detallante', 335, 27, 9),
(362, 9, 'Julien Dupoizat', 334, 27, 8),
(363, 9, 'David Tresse', 334, 24, 7),
(364, 9, 'Eddy Etheve', 334, 27, 4),
(365, 9, 'Tom', 333, 24, 7),
(366, 9, 'Sylvain Bonnot', 332, 26, 6),
(367, 9, 'Johnny Busolin', 331, 27, 5),
(368, 9, 'Mickaël Lequin', 331, 28, 5),
(369, 9, 'André Uhmann', 330, 26, 7),
(370, 9, 'Damien Thebault', 330, 29, 5),
(371, 9, 'Maxime Grard', 330, 27, 7),
(372, 9, 'Matthias Busolin', 329, 29, 6),
(373, 9, 'Arnaud Baboulin', 329, 26, 8),
(374, 9, 'Amans Mboumba', 329, 28, 8),
(375, 9, 'Isabelle Vanlichtervelde', 328, 27, 5),
(376, 9, 'Laurent Dam', 326, 27, 6),
(377, 9, 'Lorène Lanau', 326, 25, 8),
(378, 9, 'Marie-O Beuvaden', 326, 27, 5),
(379, 9, 'Anne-Isabelle Reynaud', 326, 27, 6),
(380, 9, 'Jonathan Gonthier', 324, 27, 6),
(381, 9, 'Leecharlesperry', 324, 27, 6),
(382, 9, 'Charles Bossi', 324, 28, 6),
(383, 9, 'Zhicun Xu', 324, 23, 4),
(384, 9, 'Sedat Orhan', 324, 24, 5),
(385, 9, 'Alban Paraire', 323, 27, 4),
(386, 9, 'Cédric Charreton', 322, 27, 4),
(387, 9, 'Thérèse Flajolet', 321, 26, 6),
(388, 9, 'Quentin Harle', 321, 23, 9),
(389, 9, 'Pierre Dauendorffer', 320, 26, 7),
(390, 9, 'Abdes', 319, 28, 4),
(391, 9, 'Julien Blanc', 319, 25, 6),
(392, 9, 'Chi Co', 318, 26, 7),
(393, 9, 'Pascaline Gauthier-Fournier', 317, 26, 9),
(394, 9, 'Guillaume Besset', 317, 29, 5),
(395, 9, 'Alex', 317, 24, 6),
(396, 9, 'Michel Munoz', 316, 26, 7),
(397, 9, 'Emilien Hummer', 316, 25, 7),
(398, 9, 'Gaétan Seranzi', 316, 28, 4),
(399, 9, 'Johan Poirier', 315, 27, 2),
(400, 9, 'Dimitri Delabaudiére', 314, 26, 3),
(401, 9, 'Philippe Dantec', 314, 25, 8),
(402, 9, 'Céline Rico', 313, 24, 6),
(403, 9, 'Patrice Derouin', 312, 27, 5),
(404, 9, 'Elodie Caro', 312, 27, 5),
(405, 9, 'Tan-Boûy Kim', 311, 26, 3),
(406, 9, 'Nicolas Flajolet', 311, 26, 6),
(407, 9, 'Bruno Ferriere', 310, 28, 4),
(408, 9, 'Ludovic Marcotte', 310, 26, 2),
(409, 9, 'Said Amzian', 309, 25, 7),
(410, 9, 'Zadig Michot Poirier', 309, 25, 7),
(411, 9, 'Mehdi Guecem', 308, 26, 7),
(412, 9, 'Pascal Banry', 308, 26, 4),
(413, 9, 'Damien Boisson', 308, 26, 2),
(414, 9, 'Morgane Ribeyron', 307, 25, 10),
(415, 9, 'Riadh Klai', 307, 25, 6),
(416, 9, 'Jerome Delannoy', 305, 25, 5),
(417, 9, 'Gilles Vilquin', 304, 26, 6),
(418, 9, 'Julie Guyader', 304, 23, 9),
(419, 9, 'Ricardo Ramassamy', 304, 28, 5),
(420, 9, 'Sylvain Poirier', 303, 26, 4),
(421, 9, 'Mathieu Banry', 302, 25, 6),
(422, 9, 'Valery Vaillant', 302, 23, 5),
(423, 9, 'Jérémy Bresson', 302, 25, 4),
(424, 9, 'Gabin Marais Legros', 302, 24, 7),
(425, 9, 'Alexandre Devin', 302, 24, 6),
(426, 9, 'Antony Hugues', 301, 22, 6),
(427, 9, 'Thibaud Banry', 301, 28, 5),
(428, 9, 'Matthieu Conseil', 300, 26, 5),
(429, 9, 'Loic Murat', 300, 25, 2),
(430, 9, 'Jm Greff', 300, 27, 7),
(431, 9, 'Tommy Courcaud', 299, 26, 6),
(432, 9, 'Stephane Guillier', 299, 25, 9),
(433, 9, 'Julien Vandenhende', 299, 25, 7),
(434, 9, 'Simon Delvarre', 299, 26, 6),
(435, 9, 'Guillaume Louis', 299, 23, 5),
(436, 9, 'Jean-Pierre Flajolet', 298, 26, 3),
(437, 9, 'Alain Besset', 297, 26, 6),
(438, 9, 'Laurent Lacombe', 297, 24, 5),
(439, 9, 'Karima Barni', 296, 24, 8),
(440, 9, 'David Viard', 295, 26, 7),
(441, 9, 'Anne-Sophie Tranchet', 295, 27, 6),
(442, 9, 'Peter Greenpillar', 293, 26, 2),
(443, 9, 'Allééé Tom ! ', 293, 25, 4),
(444, 9, 'Rémi Bauzac', 293, 23, 5),
(445, 9, 'Guillaume Charmetant', 293, 24, 4),
(446, 9, 'Alain Abenhaim', 292, 25, 8),
(447, 9, 'Fabien Arnaud', 292, 23, 4),
(448, 9, 'Denis Pirat', 290, 22, 8),
(449, 9, 'Julien Rocheteau', 289, 23, 7),
(450, 9, 'Kamel Rchidi', 289, 28, 6),
(451, 9, 'Philippe Soler', 289, 26, 2),
(452, 9, 'Herve Bono', 288, 27, 3),
(453, 9, 'Julien Rochette', 288, 22, 6),
(454, 9, 'Yannick Le Dean', 287, 24, 5),
(455, 9, 'Johann Pellet', 286, 22, 9),
(456, 9, 'Oliv', 286, 24, 6),
(457, 9, 'Hinda', 285, 21, 5),
(458, 9, 'Marc Helmreich', 284, 25, 2),
(459, 9, 'Stephane Peroz', 284, 22, 6),
(460, 9, 'Jean-Etienne Peroz', 284, 25, 4),
(461, 9, 'Frederic R', 282, 24, 4),
(462, 9, 'Elise Garcia', 282, 23, 4),
(463, 9, 'Denis B.', 282, 25, 7),
(464, 9, 'Mike Jokenice', 281, 23, 5),
(465, 9, 'Serge Poirier', 281, 27, 5),
(466, 9, 'Hugo Leonforte', 281, 22, 4),
(467, 9, 'Céline Cistac', 280, 22, 6),
(468, 9, 'Allééé mon Loup !!', 279, 24, 9),
(469, 9, 'Jérémie Dalmais', 279, 23, 2),
(470, 9, 'Romain Arquilliere', 277, 24, 5),
(471, 9, 'Louison Michot Poirier', 275, 23, 6),
(472, 9, 'Vincent Besset', 275, 24, 6),
(473, 9, 'Géraldine Bourdon', 275, 26, 5),
(474, 9, 'Olivier Paraire', 274, 25, 2),
(475, 9, 'Damien Leguin', 274, 22, 4),
(476, 9, 'Gaëlle Poirier', 274, 23, 3),
(477, 9, 'Christophe Monnier', 274, 21, 5),
(478, 9, 'Stéphanie Marais Legros', 273, 23, 10),
(479, 9, 'Stéphane Lapeyre', 273, 21, 5),
(480, 9, 'Eyobele Tewold', 273, 23, 4),
(481, 9, 'Patrice Sailly', 272, 23, 5),
(482, 9, 'Gaëlle Lepretre', 272, 22, 5),
(483, 9, 'Louis Paraire', 271, 23, 5),
(484, 9, 'Severine Duval', 271, 23, 3),
(485, 9, 'Fred Lacombe', 271, 24, 7),
(486, 9, 'Lionel Rouleau', 271, 22, 4),
(487, 9, 'Xavier Compte', 271, 22, 3),
(488, 9, 'Khéo Devers', 270, 20, 7),
(489, 9, 'WonderLuc', 270, 22, 4),
(490, 9, 'Carine El Khoury', 270, 20, 7),
(491, 9, 'Loïc Ghani', 269, 22, 3),
(492, 9, 'Wilfried Le Gac', 269, 24, 5),
(493, 9, 'Sylvain Rocca', 269, 23, 3),
(494, 9, 'Stéf Parent', 268, 22, 6),
(495, 9, 'Déniz Turan', 266, 22, 7),
(496, 9, 'Max Boiboi', 266, 21, 8),
(497, 9, 'Laurence Noleo', 265, 18, 6),
(498, 9, 'Lionel Pascal', 265, 24, 4),
(499, 9, 'Sabine Gauzere', 264, 22, 3),
(500, 9, 'Jeremy Guillemot', 264, 21, 4),
(501, 9, 'Olivier Servieres', 264, 23, 6),
(502, 9, 'Olivier Boudin', 264, 20, 6),
(503, 9, 'Arnaud Conseil', 264, 22, 3),
(504, 9, 'Morgane Dawant', 263, 19, 4),
(505, 9, 'Karen Bim', 263, 22, 4),
(506, 9, 'Ingrid Techer', 262, 21, 9),
(507, 9, 'Roger Ragheboom', 262, 23, 5),
(508, 9, 'Tayeb Bouzahzah', 261, 23, 10),
(509, 9, 'Fayçal Jamali', 260, 22, 4),
(510, 9, 'Florian Laporte', 259, 23, 2),
(511, 9, 'Aurélie Porhel', 259, 22, 3),
(512, 9, 'Valérie Travaillot', 259, 20, 5),
(513, 9, 'Ariane Lapeyre', 257, 21, 5),
(514, 9, 'Cécile Eeckhoudt', 256, 24, 3),
(515, 9, 'Adrien J', 256, 21, 5),
(516, 9, 'Sébastien Gauthier', 254, 22, 3),
(517, 9, 'Patrick Michel', 254, 22, 6),
(518, 9, 'Christophe Goutailler', 252, 23, 5),
(519, 9, 'Ozturk Kayisci', 252, 23, 5),
(520, 9, 'Sandrine Person', 251, 22, 3),
(521, 9, 'Emmanuelle Brunel', 251, 21, 9),
(522, 9, 'Abdalrahman Shlash', 250, 20, 5),
(523, 9, 'SebCbien', 250, 23, 3),
(524, 9, 'Franck Gay', 250, 22, 4),
(525, 9, 'Florian Prunier', 248, 18, 6),
(526, 9, 'Harisoa Rakotoarivao', 247, 19, 4),
(527, 9, 'Olivier Torres', 245, 19, 4),
(528, 9, 'Céline Moussa', 245, 19, 4),
(529, 9, 'Ninja 74', 244, 22, 6),
(530, 9, 'Lionel Cazenave', 243, 20, 3),
(531, 9, 'Xavinho', 242, 21, 3),
(532, 9, 'Fabien Flajolet', 241, 23, 0),
(533, 9, 'Julien Peyrache', 240, 20, 2),
(534, 9, 'Del Del', 240, 21, 4),
(535, 9, 'Sébastien Dupre', 239, 21, 4),
(536, 9, 'Olivier Malgras', 239, 19, 3),
(537, 9, 'Béatrice Banry', 237, 22, 6),
(538, 9, 'Michel Curtil', 237, 20, 4),
(539, 9, 'Michèle Poirier', 236, 21, 6),
(540, 9, 'Héloïse Poirier', 234, 21, 3),
(541, 9, 'Teo Moussa', 234, 22, 3),
(542, 9, 'Michael Jennequin', 233, 20, 0),
(543, 9, 'Vince Balaise', 232, 19, 4),
(544, 9, 'Romain Le Basque', 231, 19, 5),
(545, 9, 'Marine G.', 231, 21, 4),
(546, 9, 'Delphine Gavroy', 230, 19, 6),
(547, 9, 'Ludovic Nouvel', 229, 20, 2),
(548, 9, '@JC', 226, 18, 3),
(549, 9, 'Xavier Nagel', 226, 20, 1),
(550, 9, 'Mustafa Sager', 225, 19, 2),
(551, 9, 'Nathalie Verriele', 225, 21, 6),
(552, 9, 'Frédéric Goffette', 224, 19, 2),
(553, 9, 'Karoline Sander Dahl', 223, 20, 2),
(554, 9, 'Pierre-Alain Bailly', 223, 24, 3),
(555, 9, 'Ruben Thérésine-Lafon', 220, 18, 3),
(556, 9, 'Lucie Sançon', 220, 18, 6),
(557, 9, 'Marie-Laure Re', 220, 20, 4),
(558, 9, 'Alban Pigeon', 218, 19, 3),
(559, 9, 'Bruno Miralles', 217, 19, 4),
(560, 9, 'Benjamin Depoorter', 216, 18, 3),
(561, 9, 'Kevin Vincent', 216, 19, 2),
(562, 9, 'Allééé Tom !!', 216, 19, 2),
(563, 9, 'Younès Azizi', 216, 19, 4),
(564, 9, 'Laurent Delhautal', 208, 17, 6),
(565, 9, 'Stephan Colombo', 205, 20, 4),
(566, 9, 'Timothé Gioan', 203, 17, 3),
(567, 9, 'Joel Bigotte', 200, 18, 2),
(568, 9, 'Florent Ganofsky', 200, 15, 3),
(569, 9, 'Christoph Pfyffer', 199, 15, 3),
(570, 9, 'Jean-Hugues Robert', 198, 16, 4),
(571, 9, 'Séverine Haye', 197, 17, 2),
(572, 9, 'Hamza El-Agy', 195, 16, 4),
(573, 9, 'Samuel Preher', 192, 16, 2),
(574, 9, 'Malte Rehfeld', 186, 17, 3),
(575, 9, 'Erica Rambelon', 181, 16, 3),
(576, 9, 'Jean-Michel Aulas', 180, 16, 3),
(577, 9, 'Romain Rizzarello', 172, 16, 3),
(578, 9, 'Camerounais', 170, 15, 3),
(579, 9, 'Baptiste Guérif', 166, 15, 4),
(580, 9, 'Yoann Boucherand', 166, 15, 4),
(581, 9, 'Marina Dufros', 164, 14, 6),
(582, 9, 'Michel Jacquie', 163, 16, 4),
(583, 9, 'Agathe Jolicard', 162, 15, 3),
(584, 9, 'Stephen Nedelec', 162, 15, 3),
(585, 9, 'Patrick Atango', 156, 14, 4),
(586, 9, 'Thomas Vandelle', 152, 14, 3),
(587, 9, 'Matheo Dussert', 152, 14, 3),
(588, 9, 'Beatrice Blanchard', 144, 14, 1),
(589, 9, 'Yoann Fokschrud', 143, 11, 0),
(590, 9, 'Loïc Mahsas', 136, 12, 4),
(591, 9, 'David Plasse', 134, 13, 1),
(592, 9, 'Rlebasque', 130, 11, 5),
(593, 9, 'Toto Toto', 128, 12, 2),
(594, 9, 'David Del Castillo', 124, 8, 0),
(595, 9, 'Sylvain Gros', 118, 11, 2),
(596, 9, 'Violène Durand', 88, 8, 2),
(629, 11, 'Julien Pesanti', 73, 43, 7),
(630, 11, 'Jean Dong', 68, 42, 7),
(631, 11, 'Tom Fournier', 67, 39, 9),
(632, 11, 'Romain Charpenay', 67, 40, 8),
(633, 11, 'Charlotte Deligny', 67, 41, 9),
(634, 11, 'Clément Jause-Labert', 66, 43, 6),
(635, 11, 'Jean-Claude Gille', 65, 41, 9),
(636, 11, 'François Carré', 63, 36, 9),
(637, 11, 'Jean-Michel Lenoir', 62, 40, 7),
(638, 11, 'Camille Trubert', 60, 35, 9),
(639, 11, 'Bruno Vorillion', 60, 39, 7),
(640, 11, 'Robert Natalizio', 60, 37, 6),
(641, 11, 'Cédric Agnes', 60, 38, 7),
(642, 11, 'Claude Deroche', 60, 35, 5),
(643, 11, 'Matthieu Guiral', 59, 37, 3),
(644, 11, 'Sylvain Poirier', 58, 35, 10),
(645, 11, 'Gautier Montvenoux', 57, 34, 8),
(646, 11, 'Mustapha Hameg', 56, 36, 4),
(647, 11, 'Romain Pannetier', 56, 36, 7),
(648, 11, 'Romain Nicolas', 55, 36, 6),
(649, 11, 'Benedetto Professo', 53, 33, 8),
(650, 11, 'Fabien Gastineau', 53, 34, 10),
(651, 11, 'Luc Fontbonne', 53, 34, 6),
(652, 11, 'Laurent Delhautal', 52, 34, 5),
(653, 11, 'Benoit Bouchet', 51, 34, 5),
(654, 11, 'Marie Morin-Bacheter', 51, 34, 6),
(655, 11, 'Julien Chalencon', 50, 32, 5),
(656, 11, 'Eric Laborderie', 50, 35, 5),
(657, 11, 'Laurent Demorget', 49, 38, 4),
(658, 11, 'Grégory Piani', 49, 35, 2),
(659, 11, 'Patrick Baudet', 48, 31, 4),
(660, 11, 'Patrice Schaeffer', 45, 35, 3);

CREATE USER 'bet4soccer'@'%' IDENTIFIED BY 'password';
GRANT ALL ON bets.* TO 'bet4soccer'@'%';