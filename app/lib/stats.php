<?php

include_once(BASE_PATH . 'lib/date_iterator.php');

class Stats
{
    var $db;
    var $config;
    var $lang;
    var $users;
    var $groups;
    var $matches;
    var $bets;

    public function __construct(&$db, &$config, &$lang, &$users, &$groups, &$matches, &$bets)
    {
        $this->db = $db;
        $this->config = $config;
        $this->lang = $lang;
        $this->users = $users;
        $this->groups = $groups;
        $this->matches = $matches;
        $this->bets = $bets;
    }

    function get_user_stats($userID)
    {
        // Main Query
        $req = 'SELECT *, label FROM ' . $this->config['db_prefix'] . 'stats_user';
        $req .= ' WHERE userID = :userID';

        $nb_stats = 0;
        $stats = $this->db->selectArray($req, ['userID' => $userID], $nb_stats);

        return $stats;
    }

    function get_user_stats_by_type($userID, $type)
    {
        $_userStats = $this->get_user_stats($userID);
        $userStats = [];
        foreach ($_userStats as $_userStat) $userStats[$_userStat['label']] = $_userStat;
        $nb_users = $this->users->count_active();
        $nb_groups = $this->groups->count();

        $firstMatch = $this->matches->get_first();
        $lastMatch = $this->matches->get_last();
        $dateIterator = new DateIterator('day', $firstMatch['date'], $lastMatch['date']);

        $stats = [];
        foreach ($dateIterator as $day => $date) {
            $dateTime = new DateTime($date);
            $date_key = $dateTime->format('m/d');
            $date_array = explode('/', $date_key);
            $new_date = $date_array[1] . " " . $this->lang['months'][$date_array[0] - 1];
            if ($type == 1) $stats[$new_date] = (isset($userStats[$date_key])) ? $userStats[$date_key]['rank'] : $nb_users;
            elseif ($type == 2) $stats[$new_date] = (isset($userStats[$date_key])) ? $userStats[$date_key]['points'] : 0;
            else if ($type == 4) $stats[$new_date] = (isset($userStats[$date_key])) ? $userStats[$date_key]['rank_group'] : $nb_groups;
        }
        return $stats;
    }

    function get_user_stat_max_of($data)
    {
        // Main Query
        $req = "SELECT MAX($data) FROM " . $this->config['db_prefix'] . "stats_user";

        $max = $this->db->select_one($req);

        return $max;
    }

    function generate_user_stats()
    {
        // empty the table
        $this->db->exec_query("DELETE FROM " . $this->config['db_prefix'] . "stats_user");

        // period of time
        $dateTimeFormat = 'Y-m-d';
        $firstMatchPlayed = $this->matches->get_first_played();
        $lastMatchPlayed = $this->matches->get_last_played();
        $yesterday = date($dateTimeFormat, $lastMatchPlayed['time'] - 86400);
        $dateIterator = new DateIterator('day', $firstMatchPlayed['date'], $lastMatchPlayed['date']);

        // ranking snapshot for each day
        $globalUsersRanking = [];
        $nbMatchesPlayed = 0;
        $reqBase = "INSERT INTO " . $this->config['db_prefix'] . "stats_user VALUES";
        $reqGroupBase = "UPDATE " . $this->config['db_prefix'] . "stats_user SET";
        foreach ($dateIterator as $day => $date) {
            $dateTime = new DateTime($date);

            $globalUsersRanking = $this->get_user_ranking_until_day($date, $nbMatchesPlayed);
            usort($globalUsersRanking, "compare_users");
            $rank = $j = 1;
            $last_user = (isset($globalUsersRanking[0])) ? $globalUsersRanking[0] : false;
            foreach ($globalUsersRanking as $user) {
                if ((($user['nbbets'] / $nbMatchesPlayed) < $this->config['min_ratio_played_matches_for_rank']) && (!$user['lastmatch'])) {
                    $user['points'] = 0;
                }
                if (compare_users($user, $last_user) != 0) $rank = $j;
                $req = $reqBase . " (" . $user['userID'] . ", '" . $dateTime->format('m/d') . "', $rank, 0, " . $user['points'] . ", " . $user['nbresults'] . ", " . $user['nbscores'] . ")";
                $this->db->insert($req);
                if ($date == $yesterday) $this->users->set_last_rank($user['userID'], $rank);
                $last_user = $user;
                $j++;
            }
            $globalGroupsRanking = $this->get_group_ranking($globalUsersRanking, $nbMatchesPlayed);
            usort($globalGroupsRanking, "compare_groups");
            $rank = $j = 1;
            $last_group = (isset($globalGroupsRanking[0])) ? $globalGroupsRanking[0] : false;
            foreach ($globalGroupsRanking as $group) {
                if (compare_groups($group, $last_group) != 0) $rank = $j;
                if ($date == $yesterday) $this->groups->set_last_rank($group['groupID'], $rank);
                $last_group = $group;
                $j++;
            }
        }
        return;
    }

    function get_user_ranking_until_day($day, &$nbMatchesPlayed)
    {
        if (!$day) $day = now();

        $matches = $this->matches->get_played_until_day($day);
        $nbMatchesPlayed = count($matches);
        $last_played = $this->matches->get_last_played_until_day($day);
        $users = [];

        // Points pr les matchs
        foreach ($matches as $match) {
            $bets = $this->bets->get_by_match($match['matchID']);

            foreach ($bets as $bet) {
                if (!isset($users[$bet['userID']])) {
                    $user = $this->users->get($bet['userID']);
                    $users[$bet['userID']] = [];
                    $users[$bet['userID']]['userID'] = $bet['userID'];
                    $users[$bet['userID']]['points'] = 0;
                    $users[$bet['userID']]['nbscores'] = 0;
                    $users[$bet['userID']]['nbresults'] = 0;
                    $users[$bet['userID']]['nbbets'] = 0;
                    $users[$bet['userID']]['rank'] = 'NULL';
                    $users[$bet['userID']]['login'] = $user['login'];
                    $users[$bet['userID']]['name'] = $user['name'];
                    $users[$bet['userID']]['groupID'] = $user['groupID'];
                    $users[$bet['userID']]['groupID2'] = $user['groupID2'];
                    $users[$bet['userID']]['groupID3'] = $user['groupID3'];
                    $users[$bet['userID']]['diff'] = $user['diff'];
                    $users[$bet['userID']]['lastmatch'] = false;
                }

                if (($bet['scoreBetA'] !== null) && ($bet['scoreBetB'] !== null) && ($match['scoreA'] !== null) && ($match['scoreB'] !== null)) {
                    $resBet = $this->bets->get_points($bet);
                    if ($last_played['matchID'] == $match['matchID']) $users[$bet['userID']]['lastmatch'] = true;
                    $users[$bet['userID']]['nbbets']++;
                    $users[$bet['userID']]['points'] += $resBet['points'];
                    $users[$bet['userID']]['nbresults'] += $resBet['good_result'];
                    $users[$bet['userID']]['nbscores'] += $resBet['exact_score'];
                    $users[$bet['userID']]['diff'] -= (abs($match['scoreA'] - $bet['scoreBetA']) + abs($match['scoreB'] - $bet['scoreBetB']));
                }
            }
        }

        return $users;
    }

    function get_group_ranking($users, $nbMatchesPlayed)
    {
        $groups = [];

        foreach ($users as $user) {
            $uID = $user['userID'];
            if (($this->bets->count_played_by_user($uID) / $nbMatchesPlayed) <= $this->config['min_ratio_played_matches_for_group']) continue;
            for ($i = 1; $i <= 3; $i++) {
                if ($i == 1) $gID = $user['groupID'];
                else $gID = $user['groupID' . $i];

                if ($gID === null || $gID == '') continue;

                if (!isset($groups[$gID])) {
                    $groups[$gID]['groupID'] = $gID;
                    $groups[$gID]['avgPoints'] = 0;
                    $groups[$gID]['maxPoints'] = 0;
                    $groups[$gID]['totalPoints'] = 0;
                    $groups[$gID]['nbActiveUsers'] = 0;
                }
                if ($user['points'] > $groups[$gID]['maxPoints']) $groups[$gID]['maxPoints'] = $user['points'];
                $groups[$gID]['totalPoints'] += $user['points'];
                $groups[$gID]['nbActiveUsers']++;
            }
        }
        foreach ($groups as $gID => $group) {
            $groups[$gID]['avgPoints'] = $group['totalPoints'] / $group['nbActiveUsers'];
        }
        return $groups;
    }
}
