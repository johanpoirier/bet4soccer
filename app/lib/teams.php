<?php

class Teams
{
    var $parent;

    public function __construct(&$parent)
    {
        $this->parent = $parent;
    }

    /*******************/

    /*******************/

    function add($team_name, $team_pool, $fifaRank)
    {
        $team_name = trim($team_name);
        $team_pool = trim($team_pool);
        if (!in_array($team_pool, $this->parent->config['pools'])) {
            return false;
        }
        if ($team_name == null || $team_name == "" || $team_pool == null || $team_pool == "") return false;
        if ($teamID = $this->is_exist($team_name)) {
            $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'teams';
            $req .= ' SET pool = \'' . addslashes($team_pool) . '\'';
            $req .= ', fifaRank = ' . $fifaRank;
            $req .= ' WHERE teamID = ' . $teamID . '';
            return $this->parent->db->exec_query($req);
        } else {
            $req = 'INSERT INTO ' . $this->parent->config['db_prefix'] . 'teams (name, pool, fifaRank)';
            $req .= " VALUES ('" . addslashes($team_name) . "', '$team_pool', $fifaRank)";
            return $this->parent->db->insert($req);
        }
    }

    function get()
    {
        // 0 arg = get all teams
        // 1 arg = get one team

        $nb_args = func_num_args();
        $args = func_get_args();
        $teamID = null;

        if ($nb_args > 0) {
            $teamID = $args[0];
            if (($teamID == "") || ($teamID === null)) {
                $teamID = 'NULL';
            }
        }
        // Main Query
        $req = 'SELECT *';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'teams';
        if ($nb_args > 0) {
            $req .= ' WHERE teamID = ' . $teamID . '';
        }

        // Execute Query
        $nb_teams = 0;
        $teams = $this->parent->db->select_array($req, $nb_teams);
        if ($this->parent->debug) {
            array_show($teams);
        }

        // Return results
        if (($nb_args > 0) && ($nb_teams > 0)) {
            return $teams[0];
        }
        else {
            return $teams;
        }
    }

    /*******************/

    function get_by_name($name)
    {
        // Main Query
        $req = 'SELECT *';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'teams';
        $req .= ' WHERE name = \'' . $name . '\'';

        $teams = $this->parent->db->select_line($req, $nb_teams);

        if ($this->parent->debug) array_show($teams);

        return $teams;
    }

    /*******************/

    function get_by_pool($pool)
    {
        // Main Query
        $req = 'SELECT *';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'teams';
        $req .= ' WHERE pool = \'' . $pool . '\'';
        
        $nb_teams = 0;
        $teams = $this->parent->db->select_array($req, $nb_teams);

        if ($this->parent->debug) {
            array_show($teams);
        }

        return $teams;
    }

    function delete($teamID)
    {
        prepare_numeric_data(array(&$teamID));
        $req = 'DELETE';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'teams';
        $req .= ' WHERE teamID = ' . $teamID . '';
        $this->parent->db->exec_query($req);

        return;
    }

    /*******************/

    function get_HTTP_by_pool($pool)
    {
        if ($pool == "PF") $teams = $this->get();
        else $teams = $this->get_by_pool($pool);
        foreach ($teams as $team) {
            echo $team['teamID'] . "," . $team['name'] . "|";
        }
    }

    /*******************/

    function is_exist($team)
    {
        // Main Query
        $req = 'SELECT teamID';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'teams t ';
        $req .= ' WHERE name = \'' . addslashes($team) . '\'';

        return $this->parent->db->select_one($req);

    }

    function get_ranking($teams, $bets, $libScore)
    {
        $array_teams = array();
        foreach ($teams as $team) {
            $team['points'] = 0;
            $team['diff'] = 0;
            $team['goals_scored'] = 0;
            $team['goals_conceded'] = 0;
            $array_teams[$team['teamID']] = $team;
        }

        foreach ($bets as $bet) {
            $match = $this->parent->matches->get($bet['matchID']);
            if ($match['scoreA'] != "") $bet[$libScore . 'A'] = $match['scoreA'];
            if ($match['scoreB'] != "") $bet[$libScore . 'B'] = $match['scoreB'];
            if ($bet[$libScore . 'A'] > $bet[$libScore . 'B']) {
                $array_teams[$bet['teamAid']]['points'] += 3;
                $array_teams[$bet['teamAid']]['diff'] += ($bet[$libScore . 'A'] - $bet[$libScore . 'B']);
                $array_teams[$bet['teamBid']]['diff'] -= ($bet[$libScore . 'A'] - $bet[$libScore . 'B']);
            }
            if ($bet[$libScore . 'A'] < $bet[$libScore . 'B']) {
                $array_teams[$bet['teamBid']]['points'] += 3;
                $array_teams[$bet['teamAid']]['diff'] -= ($bet[$libScore . 'B'] - $bet[$libScore . 'A']);
                $array_teams[$bet['teamBid']]['diff'] += ($bet[$libScore . 'B'] - $bet[$libScore . 'A']);
            }
            if ($bet[$libScore . 'A'] == $bet[$libScore . 'B'] && ($bet[$libScore . 'A'] != "")) {
                $array_teams[$bet['teamAid']]['points'] += 1;
                $array_teams[$bet['teamBid']]['points'] += 1;
            }
            $array_teams[$bet['teamAid']]['goals_scored'] += $bet[$libScore . 'A'];
            $array_teams[$bet['teamBid']]['goals_scored'] += $bet[$libScore . 'B'];
            $array_teams[$bet['teamAid']]['goals_conceded'] += $bet[$libScore . 'B'];
            $array_teams[$bet['teamBid']]['goals_conceded'] += $bet[$libScore . 'A'];
        }

        usort($array_teams, "compare_teams");

        return $array_teams;
    }
}
