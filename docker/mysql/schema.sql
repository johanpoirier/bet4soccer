SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+02:00";

use bets;

--
-- Base de données :  `bets`
--

-- --------------------------------------------------------

--
-- Structure de la table `cdm2018__audit`
--

CREATE TABLE `cdm2018__audit` (
  `id` int(12) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userID` int(9) UNSIGNED NOT NULL,
  `category` varchar(9) DEFAULT NULL,
  `action` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `cdm2018__bets`
--

CREATE TABLE `cdm2018__bets` (
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
-- Structure de la table `cdm2018__groups`
--

CREATE TABLE `cdm2018__groups` (
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
-- Structure de la table `cdm2018__invitations`
--

CREATE TABLE `cdm2018__invitations` (
  `code` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `senderID` int(9) UNSIGNED NOT NULL,
  `groupID` int(9) UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `expiration` datetime NOT NULL,
  `status` int(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cdm2018__matches`
--

CREATE TABLE `cdm2018__matches` (
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
-- Structure de la table `cdm2018__settings`
--

CREATE TABLE `cdm2018__settings` (
  `name` varchar(35) COLLATE utf8_general_ci NOT NULL,
  `value` varchar(35) COLLATE utf8_general_ci DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `status` int(2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cdm2018__stats_user`
--

CREATE TABLE `cdm2018__stats_user` (
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
-- Structure de la table `cdm2018__tags`
--

CREATE TABLE `cdm2018__tags` (
  `tagID` int(9) UNSIGNED NOT NULL,
  `userID` int(9) UNSIGNED NOT NULL,
  `groupID` int(9) UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL,
  `tag` text COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cdm2018__teams`
--

CREATE TABLE `cdm2018__teams` (
  `teamID` int(9) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8_general_ci NOT NULL,
  `fifaRank` int(4) NOT NULL DEFAULT '0',
  `pool` char(1) COLLATE utf8_general_ci NOT NULL,
  `status` int(2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Structure de la table `cdm2018__tokens`
--

CREATE TABLE `cdm2018__tokens` (
  `userID` int(9) UNSIGNED NOT NULL,
  `device` VARCHAR(36) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `token` VARCHAR(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Structure de la table `cdm2018__users`
--

CREATE TABLE `cdm2018__users` (
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
-- Index pour les tables exportées
--

--
-- Index pour la table `cdm2018__audit`
--
ALTER TABLE `cdm2018__audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_date` (`date`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `userID` (`userID`);

--
-- Index pour la table `cdm2018__bets`
--
ALTER TABLE `cdm2018__bets`
ADD PRIMARY KEY (`userID`,`matchID`),
ADD KEY `teamA` (`teamA`),
ADD KEY `teamB` (`teamB`),
ADD KEY `matchID` (`matchID`);

--
-- Index pour la table `cdm2018__groups`
--
ALTER TABLE `cdm2018__groups`
ADD PRIMARY KEY (`groupID`),
ADD UNIQUE KEY `name` (`name`),
ADD KEY `ownerID` (`ownerID`);

--
-- Index pour la table `cdm2018__invitations`
--
ALTER TABLE `cdm2018__invitations`
  ADD PRIMARY KEY (`code`),
  ADD KEY `groupID` (`groupID`);

--
-- Index pour la table `cdm2018__matches`
--
ALTER TABLE `cdm2018__matches`
  ADD PRIMARY KEY (`matchID`),
  ADD UNIQUE KEY `round` (`round`,`rank`),
  ADD KEY `teamA` (`teamA`),
  ADD KEY `teamB` (`teamB`);

--
-- Index pour la table `cdm2018__settings`
--
ALTER TABLE `cdm2018__settings`
  ADD PRIMARY KEY (`name`);

--
-- Index pour la table `cdm2018__stats_user`
--
ALTER TABLE `cdm2018__stats_user`
  ADD KEY `userID` (`userID`);

--
-- Index pour la table `cdm2018__tags`
--
ALTER TABLE `cdm2018__tags`
  ADD PRIMARY KEY (`tagID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `groupID` (`groupID`);

--
-- Index pour la table `cdm2018__teams`
--
ALTER TABLE `cdm2018__teams`
  ADD PRIMARY KEY (`teamID`);

--
-- Index pour la table `cdm2018__tokens`
--
ALTER TABLE `cdm2018__tokens`
  ADD PRIMARY KEY (`userID`,`device`);

--
-- Index pour la table `cdm2018__users`
--
ALTER TABLE `cdm2018__users`
  ADD PRIMARY KEY (`userID`),
  ADD KEY `login` (`login`);


--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `cdm2018__audit`
--
ALTER TABLE `cdm2018__audit`
  MODIFY `id` int(12) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `cdm2018__groups`
--
ALTER TABLE `cdm2018__groups`
MODIFY `groupID` int(9) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `cdm2018__matches`
--
ALTER TABLE `cdm2018__matches`
MODIFY `matchID` int(9) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `cdm2018__tags`
--
ALTER TABLE `cdm2018__tags`
MODIFY `tagID` int(9) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `cdm2018__teams`
--
ALTER TABLE `cdm2018__teams`
MODIFY `teamID` int(9) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `cdm2018__users`
--
ALTER TABLE `cdm2018__users`
MODIFY `userID` int(9) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `cdm2018__bets`
--
ALTER TABLE `cdm2018__bets`
ADD CONSTRAINT `cdm2018__bets_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `cdm2018__users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `cdm2018__bets_ibfk_2` FOREIGN KEY (`matchID`) REFERENCES `cdm2018__matches` (`matchID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `cdm2018__bets_ibfk_3` FOREIGN KEY (`teamA`) REFERENCES `cdm2018__teams` (`teamID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `cdm2018__bets_ibfk_4` FOREIGN KEY (`teamB`) REFERENCES `cdm2018__teams` (`teamID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `cdm2018__groups`
--
ALTER TABLE `cdm2018__groups`
ADD CONSTRAINT `cdm2018__groups_ibfk_1` FOREIGN KEY (`ownerID`) REFERENCES `cdm2018__users` (`userID`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Contraintes pour la table `cdm2018__invitations`
--
ALTER TABLE `cdm2018__invitations`
ADD CONSTRAINT `cdm2018__invitations_ibfk_1` FOREIGN KEY (`groupID`) REFERENCES `cdm2018__groups` (`groupID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `cdm2018__invitations_ibfk_10` FOREIGN KEY (`groupID`) REFERENCES `cdm2018__groups` (`groupID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `cdm2018__matches`
--
ALTER TABLE `cdm2018__matches`
ADD CONSTRAINT `cdm2018__matches_ibfk_1` FOREIGN KEY (`teamA`) REFERENCES `cdm2018__teams` (`teamID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `cdm2018__matches_ibfk_2` FOREIGN KEY (`teamB`) REFERENCES `cdm2018__teams` (`teamID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `cdm2018__stats_user`
--
ALTER TABLE `cdm2018__stats_user`
ADD CONSTRAINT `cdm2018__stats_user_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `cdm2018__users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `cdm2018__tags`
--
ALTER TABLE `cdm2018__tags`
ADD CONSTRAINT `cdm2018__tags_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `cdm2018__users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `cdm2018__tags_ibfk_2` FOREIGN KEY (`groupID`) REFERENCES `cdm2018__groups` (`groupID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `cdm2018__tokens`
--
ALTER TABLE `cdm2018__tokens`
  ADD CONSTRAINT `cdm2018__tokens_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `cdm2018__users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;



--
-- Contenu pour les tables exportées
--

INSERT INTO `cdm2018__settings` (`name`, `value`, `date`, `status`) VALUES
  ('IS_GROUP_RANKING_GENERATING', '', NULL, 0),
  ('IS_RANKING_GENERATING', '', '0000-00-00 00:00:00', 0),
  ('IS_USER_RANKING_GENERATING', '', NULL, 0),
  ('LAST_GENERATE', NULL, '2014-07-13 23:36:35', 0),
  ('LAST_RESULT', NULL, '2014-06-27 11:43:55', 0),
  ('MONEY', '2', NULL, 0),
  ('NB_MATCHES_GENERATED', '0', NULL, 0),
  ('RANK_GROUPS_UPDATE', NULL, '2014-07-13 23:36:35', 0),
  ('RANK_UPDATE', NULL, '2014-07-13 23:36:34', 0);

INSERT INTO `cdm2018__users` (`userID`, `name`, `login`, `password`, `email`, `status`) VALUES
  (1, 'John Foo', 'admin', 'c87a9050eb2f1734881f89e638770e4317abb184eb69a9fbdb35d24d11d14254', 'admin@bet4soccer.fr', 1);

INSERT INTO `cdm2018__teams` (`teamID`, `name`, `fifaRank`, `pool`, `status`) VALUES
  (1, 'Russie', '17', 'A', 1),
  (2, 'Arabie Saoudite', '24', 'A', 1),
  (3, 'Egypte', '8', 'A', 1),
  (4, 'Uruguay', '32', 'A', 1);

INSERT INTO `cdm2018__matches` (`matchID`, `teamA`, `teamB`, `scoreA`, `scoreB`, `date`) VALUES
  (1, 1, 2, NULL, NULL, '2018-06-14 17:00:00'),
  (2, 3, 4, NULL, NULL, '2018-06-15 14:00:00');

INSERT INTO `cdm2018__bets` (`userID`, `matchID`, `scoreA`, `scoreB`) VALUES (1, 1, 2, 1);
