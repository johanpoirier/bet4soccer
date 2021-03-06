<?php

class Bets {

    var $parent;

    public function __construct(&$parent)
    {
        $this->parent = $parent;
    }

    /*     * **************** */

    function add($userID, $matchID, $team, $score, $final = false, $final_teamID = false, $final_teamW = false)
    {
        prepare_numeric_data(array(&$userID, &$matchID, &$score, &$final_teamID));
        prepare_alphanumeric_data(array(&$team, &$final_teamW));

        if(!$this->parent->users->is_admin($this->parent->users->get_current_id())) {
          if ($userID != $this->parent->users->get_current_id()) {
            return false;
          }
          if (!$this->parent->matches->is_open($matchID)) {
            return false;
          }
        }

        if (!$this->parent->matches->is_exist($matchID)) {
          return false;
        }
        
        $this->parent->users->update_last_bet($_SESSION['userID']);
        $match = $this->parent->matches->get($matchID);

        if ($score === '') {
          $score = 'NULL';
        }
        if ((int) $score > 20) {
          $score = 20;
        }

        if ($final) {
            if ($final_teamID === '') {
              $final_teamID = 'NULL';
            }

            /* Set score */
            if ($this->is_exist($userID, $matchID)) {
                $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'bets';
                $req .= ' SET score' . $team . ' = ' . $score . ',';
                $req .= ' team' . $team . ' = ' . $final_teamID . ',';
                $req .= " teamW = '$final_teamW'";
                $req .= " WHERE userID = $userID";
                $req .= " AND matchID = $matchID";
                $this->parent->db->exec_query($req);
            } else {
                $req = 'INSERT INTO ' . $this->parent->config['db_prefix'] . 'bets (userID,matchID,score' . $team . ',team' . $team . ',teamW)';
                $req .= ' VALUES (' . $userID . ',' . $matchID . ',' . $score . ',' . $final_teamID . ',\'' . $final_teamW . '\');';
                $this->parent->db->insert($req);
            }
            $bet = $this->get_by_match($matchID);
            $round = $match['round'];
            $rank = $match['rank'];

            /* Set the winner on next round */
            if ($round != 1 && $round != 99) {
                $next_match = $this->parent->matches->get_final(ceil($round / 2), ceil($rank / 2));
                if ($next_match) {
                    $next_matchID = $next_match['matchID'];
                    $next_team = (is_float($rank / 2)) ? "A" : "B";
                    if ($round === 8) {
                      $next_teamID = $match["team" . $final_teamW];
                    }
                    else {
                      $next_teamID = (isset($bet["team" . $final_teamW])) ? $bet["team" . $final_teamW] : $match["team" . $final_teamW];
                    }
                    $this->add_next_final_team($userID, $next_matchID, $next_team, $next_teamID);
                }
            }

            /* Third place match */
            if ($round == 99) {
                $next_match = $this->parent->matches->get_final(3, 1);
                $next_matchID = $next_match['matchID'];
                $next_team = (is_float($rank / 2)) ? "A" : "B";
                $teamL = ($final_teamW == 'A') ? 'B' : 'A';
                $next_teamID = (isset($bet["team" . $teamL])) ? $bet["team" . $teamL] : $match["team" . $teamL];
                $this->add_next_final_team($userID, $next_matchID, $next_team, $next_teamID);
            }
        } else {
            if ($this->is_exist($userID, $matchID)) {
                $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'bets';
                $req .= ' SET score' . $team . ' = ' . $score . '';
                $req .= ' WHERE userID = ' . $userID . ' AND matchID = ' . $matchID . ';';
                $this->parent->db->exec_query($req);
            } else {
                $req = 'INSERT INTO ' . $this->parent->config['db_prefix'] . 'bets (userID,matchID,score' . $team . ')';
                $req .= ' VALUES (' . $userID . ',' . $matchID . ',' . $score . ');';
                $this->parent->db->insert($req);
            }
        }

        # Audit log
        $bet = $this->get_by_id($userID, $matchID);
        if (isset($bet['scoreA'], $bet['scoreB'])) {
            $this->parent->audit->add($userID, 'bets', sprintf('a pronostiqué %s-%s pour le match %s : %s - %s (début : %s)', $bet['scoreA'], $bet['scoreB'], $matchID, $match['teamAname'], $match['teamBname'], $match['date_str']));
        }

        return true;
    }

    public function force_add($userID, $matchID, $team, $score)
    {
      if ($this->is_exist($userID, $matchID)) {
        $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'bets';
        $req .= ' SET score' . $team . ' = ' . $score . '';
        $req .= ' WHERE userID = ' . $userID . ' AND matchID = ' . $matchID . ';';
        $this->parent->db->exec_query($req);
      } else {
        $req = 'INSERT INTO ' . $this->parent->config['db_prefix'] . 'bets (userID,matchID,score' . $team . ')';
        $req .= ' VALUES (' . $userID . ',' . $matchID . ',' . $score . ');';
        $this->parent->db->insert($req);
      }
    }

    function delete_by_match_and_user($matchID, $userID) {
        prepare_numeric_data(array(&$matchID, &$userID));
        $req = 'DELETE';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'bets';
        $req .= ' WHERE userID = ' . $userID . '';
        $req .= ' AND matchID = ' . $matchID . ';';
        $this->parent->db->exec_query($req);
        return;
    }

    function add_array($userID, $bets, $finals = false) {
        if ($finals) {
            $rounds = $this->parent->config['rounds'];
            $teams = array('A', 'B');
            foreach ($rounds as $round) {
                for ($rank = 1; $rank <= $round; $rank++) {
                    if (!isset($bets[$round . 'TH_' . $rank . '_MATCH_ID']))
                        continue;
                    $final_teamW = $bets[$round . 'TH_' . $rank . '_TEAM_W'];
                    if (!isset($bets[$round . 'TH_' . $rank . '_TEAM_A_SCORE']) || !isset($bets[$round . '_TEAM_B_SCORE']))
                        continue;
                    if ($bets[$round . 'TH_' . $rank . '_TEAM_A_SCORE'] > $bets[$round . 'TH_' . $rank . '_TEAM_B_SCORE'])
                        $final_teamW = 'A';
                    if ($bets[$round . 'TH_' . $rank . '_TEAM_A_SCORE'] < $bets[$round . 'TH_' . $rank . '_TEAM_B_SCORE'])
                        $final_teamW = 'B';
                    $matchID = $bets[$round . 'TH_' . $rank . '_MATCH_ID'];
                    foreach ($teams as $team) {
                        if (!isset($bets[$round . 'TH_' . $rank . '_TEAM_' . $team . '_ID']) || ($bets[$round . 'TH_' . $rank . '_MATCH_ID'] == 'NULL'))
                            continue;
                        $final_teamID = $bets[$round . 'TH_' . $rank . '_TEAM_' . $team . '_ID'];
                        $score = $bets[$round . 'TH_' . $rank . '_TEAM_' . $team . '_SCORE'];
                        $this->add($userID, $matchID, $team, $score, true, $final_teamID, $final_teamW);
                    }
                }
            }
        } else {
            foreach ($bets as $key => $score) {
                if (preg_match('/([0-9]+)_score_team_([A|B])/', $key, $matches)) {
                    list(, $matchID, $team) = $matches;
                    $this->add($userID, $matchID, $team, $score);
                }
            }
        }
    }

    function add_HTTP_final($userID, $matchID, $team, $score, $final_teamID, $final_teamW) {
        prepare_numeric_data(array(&$userID, &$matchID, &$score, &$final_teamID));
        prepare_alphanumeric_data(array(&$team, &$final_teamW));
        if ($final_teamW === '' || $final_teamW === null) {
            $final_teamW = 'A';
        }

        if ($ret = $this->add($userID, $matchID, $team, $score, true, $final_teamID, $final_teamW)) {
            $match = $this->parent->matches->get($matchID);
            $round = $match['round'];
            $rank = $match['rank'];
            echo $matchID . "|" . $round . "|" . $rank . "|" . $final_teamW . "|";
        } else {
            echo $ret;
        }
        return $ret;
    }

    function add_HTTP($userID, $matchID, $team, $score) {
        if ($this->add($userID, $matchID, $team, $score)) {
            $pool = $this->parent->matches->get_pool($matchID);
            $bets = $this->get_by_pool($pool);
            $teams = $this->parent->teams->get_by_pool($pool);
            $arrayTeams = $this->parent->teams->get_ranking($teams, $bets, 'scoreBet');

            $response = [
                'matchID' => $matchID,
                'pool' => $pool,
                'teams' => $arrayTeams
            ];

            $this->parent->setJsonHeader();
            echo json_encode($response);
        }
    }

    function add_next_final_team($userID, $next_matchID, $next_team, $next_teamID) {
        prepare_numeric_data(array(&$userID, &$next_matchID, &$next_teamID));
        prepare_alphanumeric_data(array(&$next_team));
        if ($next_teamID == "")
            $next_teamID = 'NULL';
        if ($this->is_exist($userID, $next_matchID)) {
            $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'bets';
            $req .= ' SET team' . $next_team . ' = ' . addslashes($next_teamID) . '';
            $req .= ' WHERE userID = ' . $userID . ' AND matchID = ' . $next_matchID . ';';
            $this->parent->db->exec_query($req);
        } else {
            $req = 'INSERT INTO ' . $this->parent->config['db_prefix'] . 'bets (userID,matchID,team' . $next_team . ')';
            $req .= ' VALUES (' . $userID . ',' . $next_matchID . ',' . $next_teamID . ');';
            $this->parent->db->insert($req);
        }
    }

    /*     * **************** */

    function count_by_users() {
        // Main Query
        $req = 'SELECT u.userID, COUNT(matchID) as nb_bet';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users u';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'bets b ON (u.userID = b.userID AND b.scoreA IS NOT NULL AND b.scoreB IS NOT NULL)';
        $req .= ' GROUP BY u.userID';

        $nb_teams = 0;
        $nb_bets = $this->parent->db->select_array($req, $nb_teams);

        $bets_array = [];

        foreach ($nb_bets as $nb_bet)
            $bets_array[$nb_bet['userID']] = $nb_bet['nb_bet'];

        if ($this->parent->debug)
            array_show($bets_array);

        return $bets_array;
    }

    /*     * **************** */

    function count_played_by_users() {
        // Main Query
        $req = 'SELECT u.userID, count(b.matchID) as nb_bet';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users u';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'bets b ON (u.userID = b.userID AND b.scoreA IS NOT NULL AND b.scoreB IS NOT NULL)';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'matches m ON (b.matchID = m.matchID)';
        $req .= ' WHERE (m.scoreA IS NOT NULL) AND (m.scoreB IS NOT NULL)';
        $req .= ' AND (b.scoreA IS NOT NULL) AND (b.scoreB IS NOT NULL)';
        $req .= ' GROUP BY u.userID';

        $nb_teams = 0;
        $nb_bets = $this->parent->db->select_array($req, $nb_teams);

        $bets_array = [];

        foreach ($nb_bets as $nb_bet) {
            $bets_array[$nb_bet['userID']] = $nb_bet['nb_bet'];
        }

        if ($this->parent->debug) {
            array_show($bets_array);
        }

        return $bets_array;
    }

    /*     * **************** */

    function count_played_by_user($userID) {
        prepare_numeric_data(array(&$userID));
        // Main Query
        $req = 'SELECT count(b.matchID)';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'bets b';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'matches m ON (b.matchID = m.matchID)';
        $req .= ' WHERE b.userID = ' . $userID . '';
        $req .= ' AND (m.scoreA IS NOT NULL) AND (m.scoreB IS NOT NULL)';
        $req .= ' AND (b.scoreA IS NOT NULL) AND (b.scoreB IS NOT NULL)';

        $nb_bets = $this->parent->db->select_one($req);

        if ($this->parent->debug)
            echo($nb_bets);

        return $nb_bets;
    }

    /*     * **************** */

    function get_by_id($userID, $matchID) {
        // Main Query
        $req = 'SELECT *';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'bets';
        $req .= " WHERE userID = $userID AND matchID = $matchID";
        
        $nb_bets = 0;
        $bet = $this->parent->db->select_line($req, $nb_bets);

        if ($this->parent->debug)
            array_show($bet);

        return $bet;
    }

    function get_odds_by_match($matchID, $teamA = NULL, $teamB = NULL) {
        prepare_numeric_data([&$matchID]);
        prepare_alphanumeric_data([&$teamA, &$teamB]);

        $params = ['matchID' => $matchID];

        // Main Query
        $req = 'SELECT *';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'bets b';
        $req .= ' WHERE matchID = :matchID';
        if ($teamA != null) {
            $params ['teamA'] = $teamA;
            $req .= ' AND teamA = :teamA';
        }
        if ($teamB != null) {
            $params ['teamB'] = $teamB;
            $req .= ' AND teamB = :teamB';
        }

        $nb_bets = 0;
        $bets = $this->parent->db->selectArray($req, $params, $nb_bets);

        $odds = [];
        $odds['A_WINS'] = 0;
        $odds['B_WINS'] = 0;
        $odds['NUL'] = 0;

        foreach ($bets as $bet) {
            if (($bet['scoreA'] > $bet['scoreB']) && ($bet['scoreA'] !== null) && ($bet['scoreB'] !== null))
                $odds['A_WINS']++;
            if (($bet['scoreB'] > $bet['scoreA']) && ($bet['scoreA'] !== null) && ($bet['scoreB'] !== null))
                $odds['B_WINS']++;
            if (($bet['scoreA'] == $bet['scoreB']) && ($bet['scoreA'] !== null) && ($bet['scoreB'] !== null))
                $odds['NUL']++;
            if (($bet['scoreA'] === null) && ($bet['scoreB'] === null))
                $nb_bets--;
        }
        $odds['A_AVG'] = median(array_map(function($bet) { return $bet['scoreA'];}, $bets));
        $odds['B_AVG'] = median(array_map(function($bet) { return $bet['scoreB'];}, $bets));
        $odds['A_WINS'] = ($nb_bets > 0) ? (round(($nb_bets + 1) / ($odds['A_WINS'] + 1), 2)) : null;
        $odds['B_WINS'] = ($nb_bets > 0) ? (round(($nb_bets + 1) / ($odds['B_WINS'] + 1), 2)) : null;
        $odds['NUL'] = ($nb_bets > 0) ? (round(($nb_bets + 1) / ($odds['NUL'] + 1), 2)) : null;

        if ($this->parent->debug)
            array_show($odds);

        return $odds;
    }

    function get_by_match_and_user($matchID, $userID = false) {
        prepare_numeric_data(array(&$matchID, &$userID));
        if (!$userID)
            $userID = (isset($_SESSION['userID'])) ? $_SESSION['userID'] : 0;
        if (!$matchID)
            return false;

        // Main Query
        $req = 'SELECT *, m.scoreA as scoreMatchA, m.scoreB as scoreMatchB, b.teamA as teamBetA, b.teamB as teamBetB, b.scoreA as scoreBetA, b.scoreB as scoreBetB, tA.teamID as teamAid, tB.teamID as teamBid, tA.name as teamAname, tB.name as teamBname, tA.fifaRank as teamAfifaRank, tB.fifaRank as teamBfifaRank, tA.pool as teamPool,';
        $req .= 'DATE_FORMAT(date,\'%W %d/%m, %Hh\') as date_str';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'matches m ';
        $req .= ' RIGHT JOIN ' . $this->parent->config['db_prefix'] . 'bets b ON (m.matchID = b.matchID)';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'teams tA ON (b.teamA = tA.teamID)';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'teams tB ON (b.teamB = tB.teamID)';
        $req .= ' WHERE m.matchID = ' . $matchID . '';
        $req .= ' AND b.userID = ' . $userID . '';
        $req .= ' ORDER BY date, teamAname;';

        $nb_bets = 0;
        $bet = $this->parent->db->select_line($req, $nb_bets);

        if ($this->parent->debug)
            array_show($bet);

        return $bet;
    }

    function get_by_match_and_group($matchID, $groupID) {
        prepare_numeric_data(array(&$matchID, &$groupID));
        if (!$groupID) {
            return false;
        }
        if (!$matchID) {
            return false;
        }

        // Main Query
        $req = 'SELECT *, m.scoreA as scoreMatchA, m.scoreB as scoreMatchB, b.teamA as teamBetA, b.teamB as teamBetB, b.scoreA as scoreBetA, b.scoreB as scoreBetB, tA.teamID as teamAid, tB.teamID as teamBid, tA.name as teamAname, tB.name as teamBname, tA.pool as teamPool,';
        $req .= 'DATE_FORMAT(date,\'%W %d/%m, %Hh\') as date_str';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'matches m ';
        $req .= ' RIGHT JOIN ' . $this->parent->config['db_prefix'] . 'bets b ON (m.matchID = b.matchID)';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'teams tA ON (b.teamA = tA.teamID)';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'teams tB ON (b.teamB = tB.teamID)';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'users u ON (u.userID = b.userID)';
        $req .= ' WHERE m.matchID = ' . $matchID . '';
        $req .= ' AND u.groupID = ' . $groupID . '';
        $req .= ' ORDER BY date, teamAname;';

        $nb_bets = 0;
        $bets = $this->parent->db->select_array($req, $nb_bets);

        if ($this->parent->debug)
            array_show($bets);

        return $bets;
    }

    function get_by_match($matchID, $points = false) {
        prepare_numeric_data([&$matchID, &$points]);
        // Main Query
        $req = 'SELECT m.matchID, m.scoreA as scoreMatchA, m.scoreB as scoreMatchB,';
        $req .= ' b.userID, b.teamA as teamBetA, b.teamB as teamBetB, b.scoreA as scoreBetA, b.scoreB as scoreBetB, b.teamW,';
        $req .= ' tA.teamID as teamAid, tB.teamID as teamBid, tA.name as teamAname, tB.name as teamBname, tA.pool as teamPool,';
        $req .= ' DATE_FORMAT(date,\'%W %d/%m, %Hh\') as date_str';
        if (($points == EXACT_SCORE) || ($points == GOOD_RESULT)) {
            $req .= ', u.name as username';
        }
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'matches m ';
        if ($points == EXACT_SCORE) {
            $req .= ' RIGHT JOIN ' . $this->parent->config['db_prefix'] . 'bets b ON (m.matchID = b.matchID AND m.scoreA IS NOT NULL AND m.scoreB IS NOT NULL AND ( b.scoreA IS NOT NULL OR b.scoreB IS NOT NULL ) AND m.scoreA = b.scoreA AND m.scoreB = b.scoreB)';
            $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'users u ON (u.userID = b.userID)';
        } elseif ($points == GOOD_RESULT) {
            $req .= ' RIGHT JOIN ' . $this->parent->config['db_prefix'] . 'bets b ON (m.matchID = b.matchID AND m.scoreA IS NOT NULL AND m.scoreB IS NOT NULL AND ( b.scoreA IS NOT NULL OR b.scoreB IS NOT NULL ) AND ( (m.scoreA > m.scoreB AND b.scoreA > b.scoreB) OR (m.scoreA < m.scoreB AND b.scoreA < b.scoreB) OR (m.scoreA = m.scoreB AND b.scoreA = b.scoreB) ) )';
            $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'users u ON (u.userID = b.userID)';
        } else {
            $req .= ' RIGHT JOIN ' . $this->parent->config['db_prefix'] . 'bets b ON (m.matchID = b.matchID AND m.scoreA IS NOT NULL AND m.scoreB IS NOT NULL AND ( b.scoreA IS NOT NULL OR b.scoreB IS NOT NULL ))';
        }
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'teams tA ON (m.teamA = tA.teamID)';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'teams tB ON (m.teamB = tB.teamID)';
        $req .= ' WHERE m.matchID = :matchID';
        $req .= ' ORDER BY date, teamAname';

        $nb_bets = 0;
        $bets = $this->parent->db->selectArray($req, ['matchID' => $matchID], $nb_bets);

        if ($this->parent->debug) {
            array_show($bets);
        }

        return $bets;
    }

    function get_by_match_group_by_score($matchID) {
        prepare_numeric_data([&$matchID]);
        // Main Query
        $req = 'SELECT *, m.scoreA as scoreMatchA, m.scoreB as scoreMatchB, b.teamA as teamBetA, b.teamB as teamBetB, b.scoreA as scoreBetA, b.scoreB as scoreBetB, tA.teamID as teamAid, tB.teamID as teamBid, tA.name as teamAname, tB.name as teamBname, tA.pool as teamPool, u.name as username,';
        $req .= 'DATE_FORMAT(date,\'%W %d/%m, %Hh\') as date_str';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'matches m ';
        $req .= ' RIGHT JOIN ' . $this->parent->config['db_prefix'] . 'bets b ON (m.matchID = b.matchID AND ( b.scoreA IS NOT NULL OR b.scoreB IS NOT NULL ))';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'users u ON (u.userID = b.userID)';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'teams tA ON (m.teamA = tA.teamID)';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'teams tB ON (m.teamB = tB.teamID)';
        $req .= ' WHERE m.matchID = ' . $matchID . '';
        $req .= ' ORDER BY date, teamAname';

        $nb_bets = 0;
        $bets = $this->parent->db->select_array($req, $nb_bets);
        if ($this->parent->debug)
            array_show($bets);

        $g_bets = [];
        $g_bets['A'] = [];
        $g_bets['N'] = [];
        $g_bets['B'] = [];
        $g_bets['count'] = $nb_bets;
        foreach ($bets as $bet) {
            $score = $bet['scoreA'] . "-" . $bet['scoreB'];
            $type = ($bet['scoreA'] > $bet['scoreB']) ? 'A' : (($bet['scoreB'] > $bet['scoreA']) ? 'B' : 'N');
            if (!isset($g_bets[$type][$score])) {
                $g_bets[$type][$score] = [];
                $g_bets[$type][$score]['users'] = [];
                $g_bets[$type][$score]['count'] = 0;
            }
            $_tmp = ['ID' => $bet['userID'], 'NAME' => $bet['username']];
            $g_bets[$type][$score]['users'][] = $_tmp;
            $g_bets[$type][$score]['count']++;
        }

        ksort($g_bets['A']);
        ksort($g_bets['N']);
        ksort($g_bets['B']);
        return $g_bets;
    }

    function get_by_pool($pool, $userID = false) {
        if (!$userID) {
            $userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : 0;
        }
        prepare_numeric_data([&$userID]);
        prepare_alphanumeric_data([&$pool]);

        // Main Query
        $req = 'SELECT m.matchID, m.scoreA as scoreMatchA, m.scoreB as scoreMatchB, b.scoreA as scoreBetA, b.scoreB as scoreBetB, tA.teamID as teamAid, tB.teamID as teamBid, tA.name as teamAname, tB.name as teamBname, tA.fifaRank as teamAfifaRank, tB.fifaRank as teamBfifaRank, tA.pool as teamPool, b.teamW,';
        $req .= 'DATE_FORMAT(date,\'%W %d/%m, %Hh%i\') as date_str';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'matches m ';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'bets b ON (m.matchID = b.matchID AND b.userID = :userID)';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'teams tA ON (m.teamA = tA.teamID)';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'teams tB ON (m.teamB = tB.teamID)';
        $req .= ' WHERE tA.pool = :poolA';
        $req .= ' AND tB.pool = :poolB';
        $req .= ' AND m.round IS NULL';
        $req .= ' ORDER BY date, teamAname;';

        $nb_bets = 0;
        $bets = $this->parent->db->selectArray($req, ['userID' => $userID, 'poolA' => $pool, 'poolB' => $pool], $nb_bets);

        if ($this->parent->debug) {
            array_show($bets);
        }

        return $bets;
    }

    /*     * **************** */

    function get_by_user($userID = false, $option = false) {
        prepare_numeric_data(array(&$userID));
        if (!$userID) {
            $userID = (isset($_SESSION['userID'])) ? $_SESSION['userID'] : 0;
        }
        if ($option) {
            prepare_alphanumeric_data(array(&$option));
        }

        // Main Query
        $req = 'SELECT m.matchID, m.scoreA as scoreMatchA, m.scoreB as scoreMatchB, b.scoreA as scoreBetA, b.scoreB as scoreBetB, tA.teamID as teamAid, tB.teamID as teamBid, tA.name as teamAname, tB.name as teamBname, tA.fifaRank as teamAfifaRank, tB.fifaRank as teamBfifaRank, tA.pool as teamPool, b.teamW,';
        $req .= 'DATE_FORMAT(date,\'%W %d/%m, %Hh%i\') as date_str';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'matches m ';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'bets b ON (m.matchID = b.matchID AND b.userID = :userID)';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'teams tA ON (m.teamA = tA.teamID)';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'teams tB ON (m.teamB = tB.teamID)';
        if ($option == "pool")
            $req .= ' WHERE round IS NULL';
        else if ($option == "rounds")
            $req .= ' WHERE round IS NOT NULL';
        $req .= ' ORDER BY date, teamAname;';

        $nb_bets = 0;
        $bets = $this->parent->db->selectArray($req, ['userID' => $userID], $nb_bets);

        if ($this->parent->debug) {
            array_show($bets);
        }

        return $bets;
    }

    public function get_incomplete_bets() {
        $tableName = $this->parent->config['db_prefix'] . 'bets';
        $req = <<<SQL
SELECT * from $tableName
WHERE (scoreA is NULL and scoreB is NOT NULL)
OR (scoreA is not NULL and scoreB is NULL)
SQL;
        $betCount = 0;
        return $this->parent->db->selectArray($req, [], $betCount);
    }

    function is_exist($userID, $matchID) {
        prepare_numeric_data(array(&$userID, &$matchID));
        // Main Query
        $req = 'SELECT matchID';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'bets ';
        $req .= ' WHERE userID = ' . $userID . '';
        $req .= ' AND matchID = ' . $matchID . '';

        return $this->parent->db->select_one($req);
    }

    function get_points($bet) {
        $result = [];
        $result['points'] = 0;
        $result['diff'] = '';
        $result['exact_score'] = false;
        $result['qualify'] = false;
        $result['good_result'] = false;

        if (!isset($bet['matchID'])) {
            return $result;
        }
        $match = $this->parent->matches->get($bet['matchID']);

        if (($match['scoreA'] === null) || ($match['scoreB'] === null) || ($bet['scoreBetA'] === null) || ($bet['scoreBetB'] === null)) {
            return $result;
        }

        if ($match['round'] === null) {
            $round = "pool";
        }
        else {
            $round = $match['round'];
        }

        if ($match['scoreA'] > $match['scoreB']) {
            $resultWinner = 'A';
        }
        elseif ($match['scoreA'] < $match['scoreB']) {
            $resultWinner = 'B';
        }
        elseif ($match['scoreA'] == $match['scoreB']) {
            $resultWinner = 'N';
        }

        if ($bet['scoreBetA'] > $bet['scoreBetB']) {
            $betWinner = 'A';
        }
        elseif ($bet['scoreBetA'] < $bet['scoreBetB']) {
            $betWinner = 'B';
        }
        elseif ($bet['scoreBetA'] == $bet['scoreBetB']) {
            $betWinner = 'N';
        }

        if (($bet['scoreBetA'] == $match['scoreA']) && ($bet['scoreBetB'] == $match['scoreB'])) {
            /* Exact score */
            $result['points'] += $this->parent->config['points_' . $round . '_exact_score'];
            $result['exact_score'] = true;
        }

        if (($bet['teamW'] === null) && ($bet['scoreBetA'] !== null) && ($bet['scoreBetB'] !== null)) {
            if ($bet['scoreBetA'] > $bet['scoreBetB']) {
                $bet['teamW'] = 'A';
            }
            if ($bet['scoreBetA'] < $bet['scoreBetB']) {
                $bet['teamW'] = 'B';
            }
        }

        if (( $bet['teamW'] == $match['teamW'] ) && ($match['teamW'] !== null)) {
            /* Good qualify */
            $result['points'] += $this->parent->config['points_' . $round . '_qualify'];
            $result['qualify'] = true;
        }

        if ($betWinner == $resultWinner) {
            /* Good Results */
            $result['points'] += $this->parent->config['points_' . $round . '_good_result'];
            $result['good_result'] = true;
        }

        $result['diff'] = -(abs($match['scoreA'] - $bet['scoreBetA']) + abs($match['scoreB'] - $bet['scoreBetB']));

        return $result;
    }

}
