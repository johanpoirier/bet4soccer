SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+02:00";

--
-- Base de données :  `bets`
--

-- --------------------------------------------------------

--
-- Structure de la table `euro2016__audit`
--

CREATE TABLE `euro2016__audit` (
  `id` int(12) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userID` int(9) UNSIGNED NOT NULL,
  `category` varchar(9) DEFAULT NULL,
  `action` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `euro2016__bets`
--

CREATE TABLE `euro2016__bets` (
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
-- Structure de la table `euro2016__groups`
--

CREATE TABLE `euro2016__groups` (
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
-- Structure de la table `euro2016__invitations`
--

CREATE TABLE `euro2016__invitations` (
  `code` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `senderID` int(9) UNSIGNED NOT NULL,
  `groupID` int(9) UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `expiration` datetime NOT NULL,
  `status` int(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `euro2016__matches`
--

CREATE TABLE `euro2016__matches` (
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
-- Structure de la table `euro2016__settings`
--

CREATE TABLE `euro2016__settings` (
  `name` varchar(35) COLLATE utf8_general_ci NOT NULL,
  `value` varchar(35) COLLATE utf8_general_ci DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `status` int(2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `euro2016__stats_user`
--

CREATE TABLE `euro2016__stats_user` (
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
-- Structure de la table `euro2016__tags`
--

CREATE TABLE `euro2016__tags` (
  `tagID` int(9) UNSIGNED NOT NULL,
  `userID` int(9) UNSIGNED NOT NULL,
  `groupID` int(9) UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL,
  `tag` text COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `euro2016__teams`
--

CREATE TABLE `euro2016__teams` (
  `teamID` int(9) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8_general_ci NOT NULL,
  `fifaRank` int(4) NOT NULL DEFAULT '0',
  `pool` char(1) COLLATE utf8_general_ci NOT NULL,
  `status` int(2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Structure de la table `euro2016__tokens`
--

CREATE TABLE `euro2016__tokens` (
  `userID` int(9) UNSIGNED NOT NULL,
  `device` VARCHAR(36) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `token` VARCHAR(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Structure de la table `euro2016__users`
--

CREATE TABLE `euro2016__users` (
  `userID` int(9) UNSIGNED NOT NULL,
  `name` varchar(40) COLLATE utf8_general_ci NOT NULL,
  `login` varchar(30) COLLATE utf8_general_ci NOT NULL,
  `password` varchar(32) COLLATE utf8_general_ci NOT NULL,
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
-- Index pour la table `euro2016__audit`
--
ALTER TABLE `euro2016__audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_date` (`date`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `userID` (`userID`);

--
-- Index pour la table `euro2016__bets`
--
ALTER TABLE `euro2016__bets`
ADD PRIMARY KEY (`userID`,`matchID`),
ADD KEY `teamA` (`teamA`),
ADD KEY `teamB` (`teamB`),
ADD KEY `matchID` (`matchID`);

--
-- Index pour la table `euro2016__groups`
--
ALTER TABLE `euro2016__groups`
ADD PRIMARY KEY (`groupID`),
ADD UNIQUE KEY `name` (`name`),
ADD KEY `ownerID` (`ownerID`);

--
-- Index pour la table `euro2016__invitations`
--
ALTER TABLE `euro2016__invitations`
  ADD PRIMARY KEY (`code`),
  ADD KEY `groupID` (`groupID`);

--
-- Index pour la table `euro2016__matches`
--
ALTER TABLE `euro2016__matches`
  ADD PRIMARY KEY (`matchID`),
  ADD UNIQUE KEY `round` (`round`,`rank`),
  ADD KEY `teamA` (`teamA`),
  ADD KEY `teamB` (`teamB`);

--
-- Index pour la table `euro2016__settings`
--
ALTER TABLE `euro2016__settings`
  ADD PRIMARY KEY (`name`);

--
-- Index pour la table `euro2016__stats_user`
--
ALTER TABLE `euro2016__stats_user`
  ADD KEY `userID` (`userID`);

--
-- Index pour la table `euro2016__tags`
--
ALTER TABLE `euro2016__tags`
  ADD PRIMARY KEY (`tagID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `groupID` (`groupID`);

--
-- Index pour la table `euro2016__teams`
--
ALTER TABLE `euro2016__teams`
  ADD PRIMARY KEY (`teamID`);

--
-- Index pour la table `euro2016__tokens`
--
ALTER TABLE `euro2016__tokens`
  ADD PRIMARY KEY (`userID`,`device`);

--
-- Index pour la table `euro2016__users`
--
ALTER TABLE `euro2016__users`
  ADD PRIMARY KEY (`userID`),
  ADD KEY `login` (`login`);


--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `euro2016__audit`
--
ALTER TABLE `euro2016__audit`
  MODIFY `id` int(12) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `euro2016__groups`
--
ALTER TABLE `euro2016__groups`
MODIFY `groupID` int(9) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `euro2016__matches`
--
ALTER TABLE `euro2016__matches`
MODIFY `matchID` int(9) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `euro2016__tags`
--
ALTER TABLE `euro2016__tags`
MODIFY `tagID` int(9) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `euro2016__teams`
--
ALTER TABLE `euro2016__teams`
MODIFY `teamID` int(9) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `euro2016__users`
--
ALTER TABLE `euro2016__users`
MODIFY `userID` int(9) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `euro2016__bets`
--
ALTER TABLE `euro2016__bets`
ADD CONSTRAINT `euro2016__bets_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `euro2016__users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `euro2016__bets_ibfk_2` FOREIGN KEY (`matchID`) REFERENCES `euro2016__matches` (`matchID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `euro2016__bets_ibfk_3` FOREIGN KEY (`teamA`) REFERENCES `euro2016__teams` (`teamID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `euro2016__bets_ibfk_4` FOREIGN KEY (`teamB`) REFERENCES `euro2016__teams` (`teamID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `euro2016__groups`
--
ALTER TABLE `euro2016__groups`
ADD CONSTRAINT `euro2016__groups_ibfk_1` FOREIGN KEY (`ownerID`) REFERENCES `euro2016__users` (`userID`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Contraintes pour la table `euro2016__invitations`
--
ALTER TABLE `euro2016__invitations`
ADD CONSTRAINT `euro2016__invitations_ibfk_1` FOREIGN KEY (`groupID`) REFERENCES `euro2016__groups` (`groupID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `euro2016__invitations_ibfk_10` FOREIGN KEY (`groupID`) REFERENCES `euro2016__groups` (`groupID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `euro2016__matches`
--
ALTER TABLE `euro2016__matches`
ADD CONSTRAINT `euro2016__matches_ibfk_1` FOREIGN KEY (`teamA`) REFERENCES `euro2016__teams` (`teamID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `euro2016__matches_ibfk_2` FOREIGN KEY (`teamB`) REFERENCES `euro2016__teams` (`teamID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `euro2016__stats_user`
--
ALTER TABLE `euro2016__stats_user`
ADD CONSTRAINT `euro2016__stats_user_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `euro2016__users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `euro2016__tags`
--
ALTER TABLE `euro2016__tags`
ADD CONSTRAINT `euro2016__tags_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `euro2016__users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `euro2016__tags_ibfk_2` FOREIGN KEY (`groupID`) REFERENCES `euro2016__groups` (`groupID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `euro2016__tokens`
--
ALTER TABLE `euro2016__tokens`
  ADD CONSTRAINT `euro2016__tokens_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `euro2016__users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;



--
-- Contenu pour les tables exportées
--

INSERT INTO `euro2016__settings` (`name`, `value`, `date`, `status`) VALUES
  ('IS_GROUP_RANKING_GENERATING', '', NULL, 0),
  ('IS_RANKING_GENERATING', '', '0000-00-00 00:00:00', 0),
  ('IS_USER_RANKING_GENERATING', '', NULL, 0),
  ('LAST_GENERATE', NULL, '2014-07-13 23:36:35', 0),
  ('LAST_RESULT', NULL, '2014-06-27 11:43:55', 0),
  ('MONEY', '2', NULL, 0),
  ('NB_MATCHES_GENERATED', '0', NULL, 0),
  ('RANK_GROUPS_UPDATE', NULL, '2014-07-13 23:36:35', 0),
  ('RANK_UPDATE', NULL, '2014-07-13 23:36:34', 0);

INSERT INTO `euro2016__users` (`userID`, `name`, `login`, `password`, `email`, `status`) VALUES
  (1, 'John Foo', 'admin', 'f71dbe52628a3f83a77ab494817525c6', 'admin@bet4soccer.fr', 1);

INSERT INTO `euro2016__teams` (`teamID`, `name`, `fifaRank`, `pool`, `status`) VALUES
  (1, 'France', '17', 'A', 1),
  (2, 'Roumanie', '24', 'A', 1),
  (3, 'Angleterre', '8', 'B', 1),
  (4, 'Russie', '32', 'B', 1);

INSERT INTO `euro2016__matches` (`matchID`, `teamA`, `teamB`, `scoreA`, `scoreB`, `date`) VALUES
  (1, 1, 2, 2, 1, '2016-06-11 21:00:00'),
  (2, 3, 4, NULL, NULL, '2020-07-12 18:00:00');

INSERT INTO `euro2016__bets` (`userID`, `matchID`, `scoreA`, `scoreB`) VALUES (1, 1, 2, 1);
