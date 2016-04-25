SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `euro2016__invitations` (
  `code` varchar(32) COLLATE utf8_bin NOT NULL,
  `senderID` int(9) UNSIGNED NOT NULL,
  `userTeamID` int(9) UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8_bin NOT NULL,
  `expiration` datetime NOT NULL,
  `status` int(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `euro2016__matchs` (
  `matchID` int(11) UNSIGNED NOT NULL,
  `teamA` int(11) NOT NULL DEFAULT '0',
  `teamB` int(11) NOT NULL DEFAULT '0',
  `scoreA` int(11) DEFAULT NULL,
  `scoreB` int(11) DEFAULT NULL,
  `pnyA` int(5) DEFAULT NULL,
  `pnyB` int(5) DEFAULT NULL,
  `bonusA` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `bonusB` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `phaseID` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `status` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `euro2016__phases` (
  `phaseID` int(10) UNSIGNED NOT NULL,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `aller_retour` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `nb_matchs` int(6) UNSIGNED NOT NULL DEFAULT '0',
  `nb_qualifies` int(3) NOT NULL DEFAULT '1',
  `phasePrecedente` int(10) UNSIGNED DEFAULT NULL,
  `nbPointsRes` int(6) UNSIGNED NOT NULL DEFAULT '0',
  `nbPointsQualifie` int(6) UNSIGNED NOT NULL DEFAULT '0',
  `nbPointsScoreNiv1` int(6) UNSIGNED NOT NULL DEFAULT '0',
  `nbPointsScoreNiv2` int(6) UNSIGNED NOT NULL DEFAULT '0',
  `nbPointsEcartNiv1` int(6) UNSIGNED NOT NULL DEFAULT '0',
  `nbPointsEcartNiv2` int(6) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `euro2016__pools` (
  `poolID` int(10) UNSIGNED NOT NULL,
  `phaseID` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO `euro2016__pools` (`poolID`, `phaseID`, `name`) VALUES
  (1, NULL, 'Groupe A'),
  (2, NULL, 'Groupe B'),
  (3, NULL, 'Groupe C'),
  (4, NULL, 'Groupe D'),
  (5, NULL, 'Groupe E'),
  (6, NULL, 'Groupe F');

CREATE TABLE `euro2016__pronos` (
  `userID` int(9) NOT NULL DEFAULT '0',
  `matchID` int(9) NOT NULL DEFAULT '0',
  `scoreA` int(2) DEFAULT NULL,
  `scoreB` int(2) DEFAULT NULL,
  `pnyA` int(5) DEFAULT NULL,
  `pnyB` int(5) DEFAULT NULL,
  `status` int(2) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `euro2016__settings` (
  `name` varchar(35) NOT NULL DEFAULT '',
  `value` varchar(35) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `status` int(2) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `euro2016__settings` (`name`, `value`, `date`, `status`) VALUES
  ('LAST_RESULT', NULL, '2006-06-18 11:54:05', 0),
  ('LAST_GENERATE', NULL, '2015-11-01 12:23:08', 0),
  ('NB_MATCHS_PLAYED', '0', NULL, 0),
  ('DATE_DEBUT', NULL, '2016-06-10 21:00:00', 0),
  ('DATE_FIN', NULL, '2016-07-10 21:00:00', 0),
  ('NB_POINTS_VICTOIRE', '3', NULL, 0),
  ('NB_POINTS_NUL', '1', NULL, 0);

CREATE TABLE `euro2016__tags` (
  `tagID` int(5) NOT NULL,
  `userID` int(5) NOT NULL DEFAULT '0',
  `userTeamID` int(6) NOT NULL DEFAULT '-1',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tag` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `euro2016__teams` (
  `teamID` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `poolID` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `status` int(5) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `euro2016__users` (
  `userID` int(9) UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `login` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `password` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `points` int(9) NOT NULL DEFAULT '0',
  `nbresults` int(5) NOT NULL DEFAULT '0',
  `nbscores` int(5) NOT NULL DEFAULT '0',
  `diff` int(5) NOT NULL DEFAULT '0',
  `last_rank` int(3) UNSIGNED NOT NULL DEFAULT '1',
  `userTeamID` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `status` int(9) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `euro2016__users` (`userID`, `name`, `login`, `email`, `password`, `status`) VALUES (1, 'John Foo', 'admin', 'admin@bet4soccer.fr', 'f71dbe52628a3f83a77ab494817525c6', 1);

CREATE TABLE `euro2016__user_teams` (
  `userTeamID` int(10) UNSIGNED NOT NULL,
  `name` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `password` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ownerID` int(9) NOT NULL,
  `avgPoints` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `totalPoints` int(6) UNSIGNED NOT NULL DEFAULT '0',
  `maxPoints` int(6) UNSIGNED NOT NULL DEFAULT '0',
  `lastRank` int(6) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


ALTER TABLE `euro2016__invitations`
  ADD PRIMARY KEY (`code`);

ALTER TABLE `euro2016__matchs`
  ADD PRIMARY KEY (`matchID`);

ALTER TABLE `euro2016__phases`
  ADD PRIMARY KEY (`phaseID`);

ALTER TABLE `euro2016__pools`
  ADD PRIMARY KEY (`poolID`);

ALTER TABLE `euro2016__pronos`
  ADD PRIMARY KEY (`userID`,`matchID`);

ALTER TABLE `euro2016__settings`
  ADD PRIMARY KEY (`name`);

ALTER TABLE `euro2016__tags`
  ADD PRIMARY KEY (`tagID`);

ALTER TABLE `euro2016__teams`
  ADD PRIMARY KEY (`teamID`);

ALTER TABLE `euro2016__users`
  ADD PRIMARY KEY (`userID`);

ALTER TABLE `euro2016__user_teams`
  ADD PRIMARY KEY (`userTeamID`);
