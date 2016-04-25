<?php

class Matches {

    var $parent;

    public function __construct(&$parent) {
        $this->parent = $parent;
    }

    /*     * **************** */

    function add($match_date, $match_teamA, $match_teamB, $round = 0, $rank = 0) {
        prepare_numeric_data(array(&$match_teamA, &$match_teamB, &$round, &$rank));
        prepare_alphanumeric_data(array(&$match_date));
        if ($rank > 0 && $round > 0) {
            if ($matchID = $this->is_final_exist($round, $rank)) {
                $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'matches';
                $req .= ' SET "date" =\'' . addslashes($match_date) . '\', "teamA" = ' . $match_teamA . ' , "teamB" = ' . $match_teamB . '';
                $req .= ' WHERE "matchID" = ' . $matchID . '';
                $res = $this->parent->db->exec_query($req);
                if ($res) {
                    $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'bets';
                    $req .= ' SET "teamA" = ' . $match_teamA . ' , "teamB" = ' . $match_teamB . '';
                    $req .= ' WHERE "matchID" = ' . $matchID;
                    return $this->parent->db->exec_query($req);
                } else {
                    return $res;
                }
            } else {
                $req = 'INSERT INTO ' . $this->parent->config['db_prefix'] . 'matches (date,"teamA","teamB",round,rank)';
                $req .= ' VALUES (\'' . $match_date . '\',' . $match_teamA . ',' . $match_teamB . ',' . $round . ',' . $rank . ')';
                return $this->parent->db->insert($req);
            }
        } else {
            if ($matchID = $this->is_exist($match_teamA, $match_teamB)) {
                $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'matches SET "date" = \'' . addslashes($match_date) . '\'';
                $req .= ' WHERE "matchID" = ' . $matchID . '';
                return $this->parent->db->exec_query($req);
            } else {
                $req = 'INSERT INTO ' . $this->parent->config['db_prefix'] . 'matches ("date","teamA","teamB")';
                $req .= ' VALUES (\'' . $match_date . '\',' . $match_teamA . ',' . $match_teamB . ')';
                return $this->parent->db->insert($req);
            }
        }
    }

    function delete($matchID) {
        prepare_numeric_data(array(&$matchID));
        $req = 'DELETE';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'matches';
        $req .= ' WHERE "matchID" = ' . $matchID . ';';
        $this->parent->db->exec_query($req);
        return;
    }

    function count_played() {
        // Main Query
        $req = 'SELECT count(DISTINCT m."matchID")';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'matches m';
        $req .= ' WHERE m."scoreA" IS NOT NULL AND m."scoreB" IS NOT NULL';

        $nb_matches = $this->parent->db->select_one($req);

        return $nb_matches;
    }

    /*     * **************** */

    function count() {
        // Main Query
        $req = 'SELECT count("matchID")';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'matches m';

        $nb_matches = $this->parent->db->select_one($req);

        if ($this->parent->debug)
            echo($nb_matches);

        return $nb_matches;
    }

    function get_next() {
        // Main Query
        $req = 'SELECT *,';
        $req .= ' tA.teamID AS teamAid,';
        $req .= ' tB.teamID AS teamBid,';
        $req .= ' tA.name AS teamAname,';
        $req .= ' tB.name AS teamBname,';
        $req .= ' tA.pool AS teamPool,';
        $req .= ' DATE_FORMAT(m1.date,\'le %d/%m à %Hh%i\') AS date_str,';
        $req .= ' TIME_TO_SEC(TIMEDIFF(m1.date,NOW())) AS delay_sec,';
        $req .= ' DATEDIFF(m1.date,NOW()) AS delay_days';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'matches AS m1';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'teams tA ON (m1.teamA = tA.teamID)';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'teams tB ON (m1.teamB = tB.teamID)';
        $req .= ' WHERE m1.date = (';
        $req .= '		SELECT MIN(date)';
        $req .= ' 		FROM ' . $this->parent->config['db_prefix'] . 'matches AS m2';
        $req .= '		WHERE ADDTIME(m2.date,\'01:45:00\') > NOW()';
        $req .= ')';

        $nb_matches = 0;
        $matches = $this->parent->db->select_array($req, $nb_matches);

        if ($this->parent->debug) {
            array_show($matches);
        }

        return $matches;
    }

    function get_next_by_pool($pool) {
        // Main Query
        $req = 'SELECT *,';
        $req .= ' "tA"."teamID" as "teamAid",';
        $req .= ' "tB"."teamID" as "teamBid",';
        $req .= ' "tA"."name" as "teamAname",';
        $req .= ' "tB"."name" as "teamBname",';
        $req .= ' "tA"."pool" as "teamPool",';
        if ($this->parent->config['DB'] == "PgSQL") {
            $req .= ' to_char("date",\'le DD/MM à HH24:MI\') as "date_str",';
            $req .= ' EXTRACT(EPOCH FROM "date" - NOW())::integer as "delay_sec",';
            $req .= ' EXTRACT(DAY FROM "date" - NOW())::integer as "delay_days"';
        } else {
            $req .= ' DATE_FORMAT("date",\'le %d/%m à %Hh%i\') as "date_str",';
            $req .= ' TIME_TO_SEC(TIMEDIFF("date",NOW())) as "delay_sec",';
            $req .= ' DATEDIFF("date",NOW()) as "delay_days"';
        }
        $req .= ' FROM "' . $this->parent->config['db_prefix'] . 'matches" "m1"';
        $req .= ' LEFT JOIN "' . $this->parent->config['db_prefix'] . 'teams" "tA" ON ("m1"."teamA" = "tA"."teamID")';
        $req .= ' LEFT JOIN "' . $this->parent->config['db_prefix'] . 'teams" "tB" ON ("m1"."teamB" = "tB"."teamID")';
        $req .= ' WHERE "date" = ( ';
        $req .= '		SELECT MIN(date)';
        $req .= ' 		FROM "' . $this->parent->config['db_prefix'] . 'matches" "m2"';
        $req .= '    	LEFT JOIN ' . $this->parent->config['db_prefix'] . 'teams "tA" ON (m2."teamA" = "tA"."teamID")';
        $req .= '    	LEFT JOIN ' . $this->parent->config['db_prefix'] . 'teams "tB" ON (m2."teamB" = "tB"."teamID")';
        $req .= '    	WHERE "date" > NOW()';
        $req .= '    	AND "tA".pool = \'' . $pool . '\'';
        $req .= '   	 AND "tB".pool = \'' . $pool . '\'';
        $req .= ')';
        $req .= ' AND "tA".pool = \'' . $pool . '\'';
        $req .= ' AND "tB".pool = \'' . $pool . '\'';

        $matches = $this->parent->db->select_array($req, $nb_matches);

        if ($this->parent->debug)
            array_show($matches);

        return $matches;
    }

    function get_first() {
        // Main Query
        $req = 'SELECT *,';
        $req .= ' "tA"."teamID" as "teamAid",';
        $req .= ' "tB"."teamID" as "teamBid",';
        $req .= ' "tA"."name" as "teamAname",';
        $req .= ' "tB"."name" as "teamBname",';
        $req .= ' "tA"."pool" as "teamPool",';
        if ($this->parent->config['DB'] == "PgSQL") {
            $req .= ' to_char("date",\'le DD/MM à HH24:MI\') as "date_str",';
            $req .= ' EXTRACT(EPOCH FROM "date" - NOW())::integer as "delay_sec",';
            $req .= ' EXTRACT(DAY FROM "date" - NOW())::integer as "delay_days"';
        } else {
            $req .= ' DATE_FORMAT("date",\'le %d/%m à %Hh%i\') as "date_str",';
            $req .= ' TIME_TO_SEC(TIMEDIFF("date",NOW())) as "delay_sec",';
            $req .= ' DATEDIFF("date",NOW()) as "delay_days"';
        }
        $req .= ' FROM "' . $this->parent->config['db_prefix'] . 'matches" m';
        $req .= ' LEFT JOIN "' . $this->parent->config['db_prefix'] . 'teams" "tA" ON (m."teamA" = "tA"."teamID")';
        $req .= ' LEFT JOIN "' . $this->parent->config['db_prefix'] . 'teams" "tB" ON (m."teamB" = "tB"."teamID")';
        $req .= ' ORDER BY m."date" ASC';
        $req .= ' LIMIT 1 OFFSET 0';

        $match = $this->parent->db->select_line($req, $null);

        if ($this->parent->debug)
            array_show($match);

        return $match;
    }

    function get_first_played() {
        // Main Query
        $req = 'SELECT *,';
        $req .= ' "tA"."teamID" as "teamAid",';
        $req .= ' "tB"."teamID" as "teamBid",';
        $req .= ' "tA"."name" as "teamAname",';
        $req .= ' "tB"."name" as "teamBname",';
        $req .= ' "tA"."pool" as "teamPool",';
        if ($this->parent->config['DB'] == "PgSQL") {
            $req .= ' to_char("date",\'le DD/MM à HH24:MI\') as "date_str",';
            $req .= ' EXTRACT(EPOCH FROM "date" - NOW())::integer as "delay_sec",';
            $req .= ' EXTRACT(DAY FROM "date" - NOW())::integer as "delay_days"';
        } else {
            $req .= ' DATE_FORMAT("date",\'le %d/%m à %Hh%i\') as "date_str",';
            $req .= ' TIME_TO_SEC(TIMEDIFF("date",NOW())) as "delay_sec",';
            $req .= ' DATEDIFF("date",NOW()) as "delay_days"';
        }
        $req .= ' FROM "' . $this->parent->config['db_prefix'] . 'matches" m';
        $req .= ' LEFT JOIN "' . $this->parent->config['db_prefix'] . 'teams" "tA" ON (m."teamA" = "tA"."teamID")';
        $req .= ' LEFT JOIN "' . $this->parent->config['db_prefix'] . 'teams" "tB" ON (m."teamB" = "tB"."teamID")';
        $req .= ' WHERE m."scoreA" IS NOT NULL and m."scoreB" IS NOT NULL';
        $req .= ' ORDER BY m."date" ASC';
        $req .= ' LIMIT 1 OFFSET 0';

        $match = $this->parent->db->select_line($req, $null);

        if ($this->parent->debug)
            array_show($match);

        return $match;
    }

    function get_played_until_day($day) {
        // Main Query
        $req = 'SELECT *,';
        $req .= ' "tA"."teamID" as "teamAid",';
        $req .= ' "tB"."teamID" as "teamBid",';
        $req .= ' "tA"."name" as "teamAname",';
        $req .= ' "tB"."name" as "teamBname",';
        $req .= ' "tA"."pool" as "teamPool",';
        if ($this->parent->config['DB'] == "PgSQL") {
            $req .= ' to_char("date",\'le DD/MM à HH24:MI\') as "date_str",';
            $req .= ' EXTRACT(EPOCH FROM "date" - NOW())::integer as "delay_sec",';
            $req .= ' EXTRACT(DAY FROM "date" - NOW())::integer as "delay_days"';
        } else {
            $req .= ' DATE_FORMAT("date",\'le %d/%m à %Hh%i\') as "date_str",';
            $req .= ' TIME_TO_SEC(TIMEDIFF("date",NOW())) as "delay_sec",';
            $req .= ' DATEDIFF("date",NOW()) as "delay_days"';
        }
        $req .= ' FROM "' . $this->parent->config['db_prefix'] . 'matches" m';
        $req .= ' LEFT JOIN "' . $this->parent->config['db_prefix'] . 'teams" "tA" ON (m."teamA" = "tA"."teamID")';
        $req .= ' LEFT JOIN "' . $this->parent->config['db_prefix'] . 'teams" "tB" ON (m."teamB" = "tB"."teamID")';

        $req .= ' WHERE m."scoreA" IS NOT NULL and m."scoreB" IS NOT NULL';
        $req .= ' AND date <= \'' . $day . ' 23:59:59\'';
        $req .= ' ORDER BY m."date" ASC';

        $matches = $this->parent->db->select_array($req, $nb_matches);

        if ($this->parent->debug)
            array_show($matches);

        return $matches;
    }

    function get_last() {
        // Main Query
        $req = 'SELECT *,';
        $req .= ' "tA"."teamID" as "teamAid",';
        $req .= ' "tB"."teamID" as "teamBid",';
        $req .= ' "tA"."name" as "teamAname",';
        $req .= ' "tB"."name" as "teamBname",';
        $req .= ' "tA"."pool" as "teamPool",';
        if ($this->parent->config['DB'] == "PgSQL") {
            $req .= ' to_char("date",\'le DD/MM à HH24:MI\') as "date_str",';
            $req .= ' EXTRACT(EPOCH FROM "date" - NOW())::integer as "delay_sec",';
            $req .= ' EXTRACT(DAY FROM "date" - NOW())::integer as "delay_days"';
        } else {
            $req .= ' DATE_FORMAT("date",\'le %d/%m à %Hh%i\') as "date_str",';
            $req .= ' TIME_TO_SEC(TIMEDIFF("date",NOW())) as "delay_sec",';
            $req .= ' DATEDIFF("date",NOW()) as "delay_days"';
        }
        $req .= ' FROM "' . $this->parent->config['db_prefix'] . 'matches" m';
        $req .= ' LEFT JOIN "' . $this->parent->config['db_prefix'] . 'teams" "tA" ON (m."teamA" = "tA"."teamID")';
        $req .= ' LEFT JOIN "' . $this->parent->config['db_prefix'] . 'teams" "tB" ON (m."teamB" = "tB"."teamID")';
        $req .= ' ORDER BY m.date DESC';
        $req .= ' LIMIT 1 OFFSET 0';

        $match = $this->parent->db->select_line($req, $null);

        if ($this->parent->debug)
            array_show($match);

        return $match;
    }

    function get_last_played_until_day($day) {
        // Main Query
        $req = 'SELECT *,';
        $req .= ' "tA"."teamID" as "teamAid",';
        $req .= ' "tB"."teamID" as "teamBid",';
        $req .= ' "tA"."name" as "teamAname",';
        $req .= ' "tB"."name" as "teamBname",';
        $req .= ' "tA"."pool" as "teamPool",';
        if ($this->parent->config['DB'] == "PgSQL") {
            $req .= ' to_char("date",\'le DD/MM à HH24:MI\') as "date_str",';
            $req .= ' EXTRACT(EPOCH FROM "date")::integer as "time",';
            $req .= ' EXTRACT(EPOCH FROM "date" - NOW())::integer as "delay_sec",';
            $req .= ' EXTRACT(DAY FROM "date" - NOW())::integer as "delay_days"';
        } else {
            $req .= ' DATE_FORMAT("date",\'le %d/%m à %H:%i\') as "date_str",';
            $req .= ' UNIX_TIMESTAMP("date") as "time",';
            $req .= ' TIME_TO_SEC(TIMEDIFF("date",NOW())) as "delay_sec",';
            $req .= ' DATEDIFF("date",NOW()) as "delay_days"';
        }
        $req .= ' FROM "' . $this->parent->config['db_prefix'] . 'matches" m';
        $req .= ' LEFT JOIN "' . $this->parent->config['db_prefix'] . 'teams" "tA" ON (m."teamA" = "tA"."teamID")';
        $req .= ' LEFT JOIN "' . $this->parent->config['db_prefix'] . 'teams" "tB" ON (m."teamB" = "tB"."teamID")';
        $req .= ' WHERE m."scoreA" IS NOT NULL and m."scoreB" IS NOT NULL';
        $req .= ' AND date <= \'' . $day . ' 23:59:59\'';
        $req .= ' ORDER BY m.date DESC';
        $req .= ' LIMIT 1 OFFSET 0';

        $match = $this->parent->db->select_line($req, $null);

        if ($this->parent->debug)
            array_show($match);

        return $match;
    }

    function get_last_played() {
        // Main Query
        $req = 'SELECT *,';
        $req .= ' "tA"."teamID" as "teamAid",';
        $req .= ' "tB"."teamID" as "teamBid",';
        $req .= ' "tA"."name" as "teamAname",';
        $req .= ' "tB"."name" as "teamBname",';
        $req .= ' "tA"."pool" as "teamPool",';
        if ($this->parent->config['DB'] == "PgSQL") {
            $req .= ' to_char("date",\'le DD/MM à HH24:MI\') as "date_str",';
            $req .= ' EXTRACT(EPOCH FROM "date")::integer as "time",';
            $req .= ' EXTRACT(EPOCH FROM "date" - NOW())::integer as "delay_sec",';
            $req .= ' EXTRACT(DAY FROM "date" - NOW())::integer as "delay_days"';
        } else {
            $req .= ' DATE_FORMAT("date",\'le %d/%m à %H:%i\') as "date_str",';
            $req .= ' UNIX_TIMESTAMP("date") as "time",';
            $req .= ' TIME_TO_SEC(TIMEDIFF("date",NOW())) as "delay_sec",';
            $req .= ' DATEDIFF("date",NOW()) as "delay_days"';
        }
        $req .= ' FROM "' . $this->parent->config['db_prefix'] . 'matches" m';
        $req .= ' LEFT JOIN "' . $this->parent->config['db_prefix'] . 'teams" "tA" ON (m."teamA" = "tA"."teamID")';
        $req .= ' LEFT JOIN "' . $this->parent->config['db_prefix'] . 'teams" "tB" ON (m."teamB" = "tB"."teamID")';
        $req .= ' WHERE m."scoreA" IS NOT NULL and m."scoreB" IS NOT NULL';
        $req .= ' ORDER BY m.date DESC';
        $req .= ' LIMIT 1 OFFSET 0';

        $match = $this->parent->db->select_line($req, $null);

        if ($this->parent->debug)
            array_show($match);

        return $match;
    }

    function get_final($round, $rank) {
        // Main Query
        $req = 'SELECT *,';
        $req .= ' "tA"."teamID" as "teamAid",';
        $req .= ' "tB"."teamID" as "teamBid",';
        $req .= ' "tA"."name" as "teamAname",';
        $req .= ' "tB"."name" as "teamBname",';
        $req .= ' "tA"."pool" as "teamPool",';
        if ($this->parent->config['DB'] == "PgSQL") {
            $req .= ' to_char("date",\'le DD/MM à HH24:MI\') as "date_str",';
            $req .= ' EXTRACT(EPOCH FROM "date" - NOW())::integer as "delay_sec",';
            $req .= ' EXTRACT(DAY FROM "date" - NOW())::integer as "delay_days"';
        } else {
            $req .= ' DATE_FORMAT("date",\'le %d/%m à %Hh%i\') as "date_str",';
            $req .= ' TIME_TO_SEC(TIMEDIFF("date",NOW())) as "delay_sec",';
            $req .= ' DATEDIFF("date",NOW()) as "delay_days"';
        }
        $req .= ' FROM "' . $this->parent->config['db_prefix'] . 'matches" m';
        $req .= ' LEFT JOIN "' . $this->parent->config['db_prefix'] . 'teams" "tA" ON (m."teamA" = "tA"."teamID")';
        $req .= ' LEFT JOIN "' . $this->parent->config['db_prefix'] . 'teams" "tB" ON (m."teamB" = "tB"."teamID")';
        $req .= ' WHERE m.round = ' . $round;
        $req .= ' AND m.rank = ' . $rank;
        $req .= ' ORDER BY date, "teamAname"';

        $match = $this->parent->db->select_line($req, $null);

        if ($this->parent->debug)
            array_show($match);

        return $match;
    }

    /*     * **************** */

    function get() {
        // 0 arg = get all matches
        // 1 arg = get one match

        $nb_args = func_num_args();
        $args = func_get_args();
        $matchID = null;

        if ($nb_args > 0) {
            $matchID = $args[0];
            if (($matchID == "") || ($matchID == NULL))
                $matchID = 'NULL';
        }

        // Main Query
        $req = 'SELECT *,';
        $req .= ' "tA"."teamID" as "teamAid",';
        $req .= ' "tB"."teamID" as "teamBid",';
        $req .= ' "tA"."name" as "teamAname",';
        $req .= ' "tB"."name" as "teamBname",';
        $req .= ' "tA"."pool" as "teamPool",';
        if ($this->parent->config['DB'] == "PgSQL") {
            $req .= ' to_char("date",\'le DD/MM à HH24:MI\') as "date_str",';
            $req .= ' EXTRACT(EPOCH FROM "date" - NOW())::integer as "delay_sec",';
            $req .= ' EXTRACT(DAY FROM "date" - NOW())::integer as "delay_days"';
        } else {
            $req .= ' DATE_FORMAT("date",\'le %d/%m à %Hh%i\') as "date_str",';
            $req .= ' TIME_TO_SEC(TIMEDIFF("date",NOW())) as "delay_sec",';
            $req .= ' DATEDIFF("date",NOW()) as "delay_days"';
        }
        $req .= ' FROM "' . $this->parent->config['db_prefix'] . 'matches" m';
        $req .= ' LEFT JOIN "' . $this->parent->config['db_prefix'] . 'teams" "tA" ON (m."teamA" = "tA"."teamID")';
        $req .= ' LEFT JOIN "' . $this->parent->config['db_prefix'] . 'teams" "tB" ON (m."teamB" = "tB"."teamID")';
        if ($nb_args > 0)
            $req .= ' WHERE m."matchID" = ' . $matchID . '';
        $req .= ' ORDER BY "date", "teamAname"';

        // Execute Query			
        $matches = $this->parent->db->select_array($req, $nb_matches);
        if ($this->parent->debug)
            array_show($matches);

        // Return results
        if ($nb_args > 0 && $nb_matches > 0)
            return $matches[0];
        else
            return $matches;
    }

    /*     * **************** */

    function get_by_teams($teamA, $teamB) {
        // Main Query
        $req = 'SELECT *,';
        $req .= ' "tA"."teamID" as "teamAid",';
        $req .= ' "tB"."teamID" as "teamBid",';
        $req .= ' "tA"."name" as "teamAname",';
        $req .= ' "tB"."name" as "teamBname",';
        $req .= ' "tA"."pool" as "teamPool",';
        if ($this->parent->config['DB'] == "PgSQL") {
            $req .= ' to_char("date",\'le DD/MM à HH24:MI\') as "date_str",';
            $req .= ' EXTRACT(EPOCH FROM "date" - NOW())::integer as "delay_sec",';
            $req .= ' EXTRACT(DAY FROM "date" - NOW())::integer as "delay_days"';
        } else {
            $req .= ' DATE_FORMAT("date",\'le %d/%m à %Hh%i\') as "date_str",';
            $req .= ' TIME_TO_SEC(TIMEDIFF("date",NOW())) as "delay_sec",';
            $req .= ' DATEDIFF("date",NOW()) as "delay_days"';
        }
        $req .= ' FROM "' . $this->parent->config['db_prefix'] . 'matches" m';
        $req .= ' LEFT JOIN "' . $this->parent->config['db_prefix'] . 'teams" "tA" ON (m."teamA" = "tA"."teamID")';
        $req .= ' LEFT JOIN "' . $this->parent->config['db_prefix'] . 'teams" "tB" ON (m."teamB" = "tB"."teamID")';
        $req .= ' WHERE m."teamA" = ' . $teamA . '';
        $req .= ' AND m."teamB" = ' . $teamB . '';

        $matches = $this->parent->db->select_line($req, $nb_teams);

        if ($this->parent->debug)
            array_show($matches);

        return $matches;
    }

    /*     * **************** */

    function get_by_pool($pool) {
        // Main Query
        $req = 'SELECT *,';
        $req .= ' "tA"."teamID" as "teamAid",';
        $req .= ' "tB"."teamID" as "teamBid",';
        $req .= ' "tA"."name" as "teamAname",';
        $req .= ' "tB"."name" as "teamBname",';
        $req .= ' "tA"."pool" as "teamPool",';
        if ($this->parent->config['DB'] == "PgSQL") {
            $req .= ' to_char("date",\'le DD/MM à HH24:MI\') as "date_str",';
            $req .= ' EXTRACT(EPOCH FROM "date" - NOW())::integer as "delay_sec",';
            $req .= ' EXTRACT(DAY FROM "date" - NOW())::integer as "delay_days"';
        } else {
            $req .= ' DATE_FORMAT("date",\'le %d/%m à %Hh%i\') as "date_str",';
            $req .= ' TIME_TO_SEC(TIMEDIFF("date",NOW())) as "delay_sec",';
            $req .= ' DATEDIFF("date",NOW()) as "delay_days"';
        }
        $req .= ' FROM "' . $this->parent->config['db_prefix'] . 'matches" m';
        $req .= ' LEFT JOIN "' . $this->parent->config['db_prefix'] . 'teams" "tA" ON (m."teamA" = "tA"."teamID")';
        $req .= ' LEFT JOIN "' . $this->parent->config['db_prefix'] . 'teams" "tB" ON (m."teamB" = "tB"."teamID")';
        $req .= ' WHERE "tA".pool = \'' . $pool . '\'';
        $req .= ' AND "tB".pool = \'' . $pool . '\'';
        $req .= ' ORDER BY "date", "teamAname"';

        $matches = $this->parent->db->select_array($req, $nb_teams);

        if ($this->parent->debug)
            array_show($matches);

        return $matches;
    }

    /*     * **************** */

    function get_by_pool_before_now($pool) {
        // Main Query
        $req = 'SELECT *,';
        $req .= ' "tA"."teamID" as "teamAid",';
        $req .= ' "tB"."teamID" as "teamBid",';
        $req .= ' "tA"."name" as "teamAname",';
        $req .= ' "tB"."name" as "teamBname",';
        $req .= ' "tA"."pool" as "teamPool",';
        if ($this->parent->config['DB'] == "PgSQL") {
            $req .= ' to_char("date",\'le DD/MM à HH24:MI\') as "date_str",';
            $req .= ' EXTRACT(EPOCH FROM "date" - NOW())::integer as "delay_sec",';
            $req .= ' EXTRACT(DAY FROM "date" - NOW())::integer as "delay_days"';
        } else {
            $req .= ' DATE_FORMAT("date",\'le %d/%m à %Hh%i\') as "date_str",';
            $req .= ' TIME_TO_SEC(TIMEDIFF("date",NOW())) as "delay_sec",';
            $req .= ' DATEDIFF("date",NOW()) as "delay_days"';
        }
        $req .= ' FROM "' . $this->parent->config['db_prefix'] . 'matches" m';
        $req .= ' LEFT JOIN "' . $this->parent->config['db_prefix'] . 'teams" "tA" ON (m."teamA" = "tA"."teamID")';
        $req .= ' LEFT JOIN "' . $this->parent->config['db_prefix'] . 'teams" "tB" ON (m."teamB" = "tB"."teamID")';
        $req .= ' WHERE "tA".pool = \'' . $pool . '\'';
        $req .= ' AND "tB".pool = \'' . $pool . '\'';
        $req .= ' AND m."date" <= NOW()';
        $req .= ' ORDER BY "date", "teamAname"';

        $matches = $this->parent->db->select_array($req, $nb_teams);

        if ($this->parent->debug)
            array_show($matches);

        return $matches;
    }

    function get_HTTP($matchID, $isRounds = 0) {
        $match = $this->get($matchID);
        if (isset($match)) {
            if ($isRounds == 1) {
                $match['pool'] = "PF";
            }
            $data = $match['pool'];
            $data .= "|" . $match['round'];
            $data .= "|" . $match['rank'];
            $data .= "|" . intval(substr($match['date'], 8, 2));
            $data .= "|" . intval(substr($match['date'], 5, 2));
            $data .= "|" . substr($match['date'], 0, 4);
            $data .= "|" . substr($match['date'], 11, 2);
            $data .= "|" . substr($match['date'], 14, 2);
            $data .= "|" . $match['teamA'];
            $data .= "|" . $match['teamB'];
            $data .= "|" . $match['matchID'];

            echo $data;
        }
    }

    /*     * **************** */

    function is_final_exist($round, $rank) {
        // Main Query
        $req = 'SELECT "matchID"';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'matches m ';
        $req .= ' WHERE round = ' . $round . '';
        $req .= ' AND rank = ' . $rank . '';

        return $this->parent->db->select_one($req, null);
    }

    function is_exist() {
        // 1 arg = test by matchID
        // 2 arg = test by teams

        $nb_args = func_num_args();
        $args = func_get_args();

        if ($nb_args == 1) {
            $matchID = $args[0];
            if (($matchID == "") || ($matchID == NULL))
                $matchID = 'NULL';
            // Main Query
            $req = 'SELECT "matchID"';
            $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'matches m ';
            $req .= ' WHERE m."matchID" = ' . $matchID . '';
            return $this->parent->db->select_one($req, null);
        } elseif ($nb_args == 2) {
            $teamA = $args[0];
            $teamB = $args[1];
            // Main Query
            $req = 'SELECT "matchID"';
            $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'matches m ';
            $req .= ' WHERE m."teamA" = ' . $teamA . '';
            $req .= ' AND m."teamB" = ' . $teamB . '';
            return $this->parent->db->select_one($req, null);
        } else
            return false;
    }

    function is_open($matchID) {
        // Main Query
        if ($this->parent->config['DB'] == "PgSQL") {
            $req = 'SELECT to_char("date",\'HH24:MI:SS:MM:DD:YYYY\') as "date_str"';
        } else {
            $req = 'SELECT DATE_FORMAT("date",\'%H:%i:%s:%m:%d:%Y\') as "date_str"';
        }
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'matches m ';
        $req .= ' WHERE m."matchID" = ' . $matchID . '';

        $date_str = $this->parent->db->select_one($req);
        if ($date_str) {
            $date_array = explode(":", $date_str);
            $time_match = mktime($date_array[0], $date_array[1] - 15, $date_array[2], $date_array[3], $date_array[4], $date_array[5]);
            $time_now = mktime();
            return($time_now < $time_match);
        } else
            return false;
    }

    function get_pool($matchID) {
        // Main Query
        $req = 'SELECT "tA".pool as "teamPool"';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'matches m ';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'teams "tA" ON (m."teamA" = "tA"."teamID")';
        $req .= ' WHERE m."matchID" = ' . $matchID . '';

        return $this->parent->db->select_one($req, null);
    }

    function get_last_pool() {
        // Main Query
        $req = 'SELECT *,';
        if ($this->parent->config['DB'] == "PgSQL") {
            $req .= ' to_char("date",\'le DD/MM à HH24:MI\') as "date_str",';
            $req .= ' EXTRACT(EPOCH FROM "date" - NOW())::integer as "delay_sec"';
        } else {
            $req .= ' DATE_FORMAT("date",\'le %d/%m à %Hh%i\') as "date_str",';
            $req .= ' TIME_TO_SEC(TIMEDIFF("date",NOW())) as "delay_sec"';
        }
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'matches m1';
        $req .= ' WHERE date = (';
        $req .= '    SELECT MAX(date) ';
        $req .= '    FROM ' . $this->parent->config['db_prefix'] . 'matches m2 ';
        $req .= '    WHERE m2.round IS NULL';
        $req .= ')';
        $req .= ' AND m1.round IS NULL';

        $match = $this->parent->db->select_line($req, $null);

        if ($this->parent->debug)
            array_show($match);

        return $match;
    }

    function get_nb_matchs_in_the_next_n_days($nbDays) {
        // Main Query
        $req = "SELECT count(m.matchID) FROM " . $this->parent->config['db_prefix'] . "matches AS m";
        $req .= " WHERE DATEDIFF(m.date, NOW()) >= 0 AND DATEDIFF(m.date, NOW()) <= " . $nbDays;

        $nbMatchs = $this->parent->db->select_one($req);

        return $nbMatchs;
    }

    /*     * **************** */

    function add_HTTP_final_result($matchID, $team, $score, $teamID, $teamW, $num) {
        $match = $this->get($matchID);
        if (!$match)
            return false;
        $round = $match['round'];
        $rank = $match['rank'];

        if ($score == "")
            $score = 'NULL';
        if ($this->is_final_exist($round, $rank)) {
            $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'matches';
            $req .= ' SET "score' . $team . '" = ' . $score . ', "team' . $team . '" = ' . $teamID . ', "teamW" = \'' . addslashes($teamW) . '\'';
            $req .= ' WHERE "matchID" = ' . $matchID . '';
            $ret = $this->parent->db->exec_query($req);
        } else {
            $req = 'INSERT INTO ' . $this->parent->config['db_prefix'] . 'matches ("matchID","score' . $team . '","team' . $team . '","teamW")';
            $req .= ' VALUES (' . $matchID . ',' . $score . ',' . $teamID . ',\'' . $teamW . '\')';
            $ret = $this->parent->db->insert($req);
        }
        if ($round != 1 && $round != 3) {
            $next_round = ceil($round / 2);
            $next_rank = ceil($rank / 2);
            $next_match = $this->get_final($next_round, $next_rank);
            if ($next_match) {
                $next_matchID = $next_match['matchID'];
                $next_team = (is_float($rank / 2)) ? 'A' : 'B';
                $next_teamID = $match['team' . $teamW];

                if ($this->is_final_exist($next_round, $next_rank)) {
                    $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'matches';
                    $req .=' SET "team' . $next_team . '" = ' . $next_teamID . '';
                    $req .=' WHERE "matchID" = ' . $next_matchID . '';
                    $ret = $this->parent->db->exec_query($req);
                } else {
                    $req = 'INSERT INTO ' . $this->parent->config['db_prefix'] . 'matches ("matchID","team' . $next_team . '")';
                    $req .= ' VALUES (' . $next_matchID . ',' . $next_teamID . ')';
                    $ret = $this->parent->db->insert($req);
                }
            }
        }
        echo $matchID . "|" . $round . "|" . $rank . "|" . $teamW . "|" . $num . "|";

        return $ret;
    }

    /*     * **************** */

    function add_HTTP_result($matchID, $team, $score, $num) {
        if ($score == "")
            $score = 'NULL';
        $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'matches';
        $req .= ' SET "score' . $team . '" = ' . $score . '';
        $req .= ' WHERE "matchID" = ' . $matchID . '';
        $ret = $this->parent->db->exec_query($req);

        $pool = $this->get_pool($matchID);
        $matches = $this->get_by_pool($pool);
        $teams = $this->parent->teams->get_by_pool($pool);

        $array_teams = $this->parent->teams->get_ranking($teams, $matches, 'score');

        echo $matchID . "|" . $pool . "|" . $num . "|";
        foreach ($array_teams as $team) {
            echo $team['teamID'] . ";" . $team['name'] . ";" . rawurlencode(utf8_encode($team['name'])) . ";" . $team['points'] . ";" . (($team['diff'] > 0) ? "+" : "") . $team['diff'] . "|";
        }

        $this->parent->settings->set_last_result();
        return $ret;
    }

}
