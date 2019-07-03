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

CREATE USER 'bet4soccer'@'%' IDENTIFIED BY 'password';
GRANT ALL ON bets.* TO 'bet4soccer'@'%';
