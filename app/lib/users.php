<?php

class Users
{

    var $parent;

    public function __construct(&$parent)
    {
        $this->parent = $parent;
    }

    function add($login, $pass, $name, $firstname, $email, $groupID, $status)
    {
        $login = trim($login);
        $email = trim($email);
        $name = trim($name);
        $firstname = trim($firstname);
        if (strlen($firstname) > 0) {
            $name = $firstname . " " . $name;
        }
        if (!stristr($email, '@')) {
            return INCORRECT_EMAIL;
        }
        if ($this->is_exist($login)) {
            return LOGIN_ALREADY_EXISTS;
        }
        if ($name == null || $name == "" || $login == null || $login == "") {
            return FIELDS_EMPTY;
        }

        $req = 'INSERT INTO ' . $this->parent->config['db_prefix'] . 'users (login,password,name,email,groupID,status)';
        $req .= ' VALUES (\'' . addslashes($login) . '\',\'' . hash_hmac('sha256', $pass, $this->parent->config['secret_key']) . '\',\'' . addslashes($name) . '\',\'' . addslashes($email) . '\',' . (($groupID != '') ? '\'' . addslashes($groupID) . '\'' : 'NULL') . ',\'' . addslashes($status) . '\')';

        return $this->parent->db->insert($req);
    }

    function add_or_update($login, $pass, $name, $email, $groupID, $status)
    {
        $login = trim($login);
        $name = trim($name);
        $email = trim($email);
        if (!stristr($email, '@')) {
            return INCORRECT_EMAIL;
        }
        if ($name == null || $name == "" || $login == null || $login == "") {
            return FIELDS_EMPTY;
        }
        if ($this->is_exist($login)) {
            $passwordReq = '';
            if (strlen($pass) > 1) {
              $passwordReq = ' password=\'' . hash_hmac('sha256', $pass, $this->parent->config['secret_key']) . '\',';
            }
            $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'users';
            $req .= ' SET name=\'' . addslashes($name) . '\',' . $passwordReq . ' email=\'' . addslashes($email) . '\',';
            $req .= ' groupID=' . (($groupID != '') ? addslashes($groupID) : 'NULL') . ', status=' . addslashes($status) . ' WHERE login=\'' . addslashes($login) . '\'';
            return $this->parent->db->exec_query($req);
        } else {
            $req = 'INSERT INTO ' . $this->parent->config['db_prefix'] . 'users (login,password,name,email,groupID,status)';
            $req .= ' VALUES (\'' . addslashes($login) . '\',\'' . hash_hmac('sha256', $pass, $this->parent->config['secret_key']) . '\',\'' . addslashes($name) . '\',\'' . addslashes($email) . '\',' . (($groupID != '') ? '\'' . addslashes($groupID) . '\'' : 'NULL') . ',\'' . addslashes($status) . '\')';
            return $this->parent->db->insert($req);
        }
    }

    function update_account($name, $email)
    {
        prepare_alphanumeric_data(array(&$name, &$email));
        $user = $this->get_current();

        if (!$user)
            return UNKNOWN_ERROR;
        if (!stristr($email, '@'))
            return INCORRECT_EMAIL;
        if ($name == "")
            return FIELDS_EMPTY;
        if (($email != $user['email']) && ($this->is_exist_by_email($email)))
            return EMAIL_ALREADY_EXISTS;

        $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'users';
        $req .= ' SET name=\'' . $name . '\', email=\'' . $email . '\'';
        $req .= ' WHERE userID = ' . $user['userID'] . '';
        $ret = $this->parent->db->exec_query($req);
        $this->parent->update_session();
        if ($ret) {
            return CHANGE_ACCOUNT_OK;
        } else {
            return UNKNOWN_ERROR;
        }
    }

    function update_preferences($theme, $match_display)
    {
        prepare_alphanumeric_data(array(&$theme, &$match_display));
        $user = $this->get_current();

        if (!$user)
            return UNKNOWN_ERROR;

        $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'users';
        $req .= ' SET theme=\'' . $theme . '\', match_display=\'' . $match_display . '\'';
        $req .= ' WHERE userID=' . $user['userID'] . '';
        $ret = $this->parent->db->exec_query($req);
        $this->parent->update_session();
        if ($ret) {
            return CHANGE_ACCOUNT_OK;
        } else {
            return UNKNOWN_ERROR;
        }
    }

    function delete($userID)
    {
        $req = 'DELETE';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users';
        $req .= ' WHERE userID = ' . $userID . '';
        $this->parent->db->exec_query($req);
        return;
    }

    function get_HTTP($userID = null)
    {
        $users = $this->get($userID);

        // Return results
        $this->parent->setJsonHeader();
        echo json_encode($users);
    }

    function get($userID = null)
    {
        $params = [];

        // Main Query
        $req = 'SELECT u.userID, u.login, u.name, u.email, u.points, u.nbresults, u.nbscores, u.diff, u.last_rank, u.match_display, u.theme, u.status';
        $req .= ', u.groupID, t.name as groupName, u.groupID2, t2.name as groupName2, u.groupID3, t3.name as groupName3';
        $req .= ', DATE_FORMAT(u.last_connection, \'%d/%m\') as last_connection';
        $req .= ', DATE_FORMAT(u.last_bet, \'%d/%m\') as last_bet';
        $req .= ', count(b.userID) as nb_bets';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users u';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'groups t ON (u.groupID = t.groupID)';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'groups t2 ON (u.groupID2 = t2.groupID)';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'groups t3 ON (u.groupID3 = t3.groupID)';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'bets b ON (u.userID = b.userID AND b.scoreA IS NOT NULL AND b.scoreB IS NOT NULL)';
        if ($userID !== null) {
            $params = ['userID' => $userID];
            $req .= ' WHERE u.userID = :userID';
        }
        $req .= ' GROUP BY u.userID';
        $req .= ' ORDER by u.name';

        // Execute Query
        $nb_users = 0;
        $users = $this->parent->db->selectArray($req, $params, $nb_users);
        if ($this->parent->debug) {
            array_show($users);
        }

        // Return results
        if ($userID !== null && isset($users[0])) {
            return $users[0];
        }

        return $users;
    }

    /*     * **************** */

    function count()
    {
        // Main Query
        $req = 'SELECT COUNT(userID)';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users';

        $nb_user = $this->parent->db->select_one($req);

        if ($this->parent->debug) {
            array_show($nb_user);
        }

        return $nb_user;
    }

    /*     * **************** */

    function count_active()
    {
        // Main Query
        $req = 'SELECT COUNT(userID)';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users';
        $req .= ' WHERE userID IN (';
        $req .= ' SELECT u.userID';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users u';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'bets b ON (u.userID = b.userID AND b.scoreA IS NOT NULL AND b.scoreB IS NOT NULL)';
        $req .= ' GROUP BY u.userID';
        $req .= ' HAVING COUNT(b.matchID) > 0';
        $req .= ')';
        $req .= ' AND points IS NOT NULL';

        $nb_active_user = $this->parent->db->select_one($req);

        if ($this->parent->debug) {
            array_show($nb_active_user);
        }

        return $nb_active_user;
    }

    /*     * **************** */

    function count_active_by_group($groupID)
    {
        // Main Query
        $req = 'SELECT COUNT(userID)';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users';
        $req .= ' WHERE userID IN (';
        $req .= ' SELECT u.userID';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users u';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'bets b ON (u.userID = b.userID AND b.scoreA IS NOT NULL AND b.scoreB IS NOT NULL)';
        $req .= ' WHERE (u.groupID = ' . $groupID . '';
        $req .= ' OR u.groupID2 = ' . $groupID . '';
        $req .= ' OR u.groupID3 = ' . $groupID . ')';
        $req .= ' AND u.status >= 0';
        $req .= ' GROUP BY u.userID';
        $req .= ' HAVING COUNT(b.matchID) > 0';
        $req .= ')';

        $nb_active_user = $this->parent->db->select_one($req);

        if ($this->parent->debug) {
            array_show($nb_active_user);
        }

        return $nb_active_user;
    }

    /*     * **************** */

    function get_current_id()
    {
        return (isset($_SESSION['userID'])) ? $_SESSION['userID'] : false;
    }

    function get_current()
    {
        return $this->get($this->get_current_id());
    }

    function get_by_login($login)
    {
        // Main Query
        $req = 'SELECT *';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users';
        $req .= ' WHERE login = ?';

        $nb_users = 0;
        $user = $this->parent->db->selectLine($req, [$login], $nb_users);

        if ($this->parent->debug) {
            array_show($user);
        }

        return $user;
    }

    function get_by_email($email)
    {
        // Main Query
        $req = 'SELECT *';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users';
        $req .= ' WHERE email = ?';

        $nb_users = 0;
        $user = $this->parent->db->selectLine($req, [$email], $nb_users);

        if ($this->parent->debug) {
            array_show($user);
        }

        return $user;
    }

    function get_password($id)
    {
      // Main Query
      $req = 'SELECT password';
      $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users';
      $req .= " WHERE userID = $id";

      $password = $this->parent->db->select_one($req);

      if ($this->parent->debug) {
        echo $password;
      }

      return $password;
    }

    function count_by_group($groupID)
    {
        // Main Query
        $req = 'SELECT COUNT(u.userID)';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users u';
        $req .= ' WHERE (u.groupID = ' . $groupID . '';
        $req .= ' OR u.groupID2 = ' . $groupID . '';
        $req .= ' OR u.groupID3 = ' . $groupID . ')';
        $req .= ' AND u.status >= 0';

        $nb_users = $this->parent->db->select_one($req);
        if ($this->parent->debug) {
            echo $nb_users;
        }

        return $nb_users;
    }

    /*     * **************** */

    function get_by_group($groupID, $all_users = false)
    {
        // Main Query
        $req = 'SELECT DISTINCT(u.userID), u.name, u.login, u.points, u.nbresults, u.nbscores, u.diff, u.last_rank, u.status, t.name AS group_name';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users u';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'bets AS b ON(b.userID = u.userID)';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'groups AS t ON(t.groupID = u.groupID)';
        $req .= ' WHERE u.groupID = ' . $groupID . '';
        $req .= ' OR u.groupID2 = ' . $groupID . '';
        $req .= ' OR u.groupID3 = ' . $groupID . '';
        if (!$all_users) {
            $req .= ' AND (b.scoreA IS NOT null) AND (b.scoreB IS NOT null) AND u.status >= 0';
        }
        $req .= ' ORDER BY u.name ASC';

        $nb_users = 0;
        $users = $this->parent->db->select_array($req, $nb_users);
        if ($this->parent->debug) {
            array_show($users);
        }

        return $users;
    }

    function is_exist($login)
    {
        // Main Query
        $req = 'SELECT userID';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users ';
        $req .= ' WHERE login = \'' . $login . '\'';

        return $this->parent->db->select_one($req);
    }

    function get_max_points()
    {
        // Main Query
        $req = 'SELECT max(points)';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users ';

        return $this->parent->db->select_one($req);
    }

    function is_exist_by_email($email)
    {
        // Main Query
        $req = 'SELECT userID';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users ';
        $req .= ' WHERE email = \'' . $email . '\'';

        return $this->parent->db->select_one($req);
    }

    function is_name_exist($name)
    {
        // Main Query
        $req = 'SELECT userID';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users ';
        $req .= ' WHERE name = \'' . $name . '\'';

        return $this->parent->db->select_one($req);
    }

    function is_admin($userID)
    {
        // Main Query
        $req = 'SELECT status';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users ';
        $req .= ' WHERE userID = ' . $userID;

        $status = $this->parent->db->select_one($req);

        return $status == 1;
    }

    function unset_group($groupID, $userID = false)
    {
        if (!$userID) {
            $userID = $this->get_current_id();
        }
        $pos = $this->is_in_group($groupID, $userID);
        if (!$pos) {
            return false;
        }
        if ($pos == 1) {
            $pos = "";
        }

        $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'users';
        $req .= ' SET groupID' . $pos . ' = NULL';
        $req .= ' WHERE userID = ' . $userID . '';
        $ret = $this->parent->db->exec_query($req);
        $this->parent->update_session();

        return $ret;
    }

    function is_in_group($groupID, $userID = false)
    {
        if (!$userID) {
            $userID = $this->get_current_id();
        }
        $user = $this->get($userID);
        if ($user['groupID'] == $groupID) {
            return 1;
        }
        if ($user['groupID2'] == $groupID) {
            return 2;
        }
        if ($user['groupID3'] == $groupID) {
            return 3;
        }
        return false;
    }

    function count_groups($userID = false)
    {
        if (!$userID) {
            $userID = $this->get_current_id();
        }
        $user = $this->get($userID);
        $i = 0;
        if ($user['groupID'] > 0) {
            $i++;
        }
        if ($user['groupID2'] > 0) {
            $i++;
        }
        if ($user['groupID3'] > 0) {
            $i++;
        }
        return $i;
    }

    function get_next_free_groupID($userID = false)
    {
        if (!$userID) {
            $userID = $this->get_current_id();
        }
        $user = $this->get($userID);
        if (!($user['groupID'] > 0)) {
            return 1;
        }
        if (!($user['groupID2'] > 0)) {
            return 2;
        }
        if (!($user['groupID3'] > 0)) {
            return 3;
        }
        return false;
    }

    function set_group($userID, $groupID, $password, $code = false)
    {
        if ($code) {
            $invitation = $this->parent->groups->is_invited_by_code($code);
            if ($invitation['groupID'] != $groupID) {
                return JOIN_GROUP_FORBIDDEN;
            }
            $this->parent->groups->use_invitation($code);
        } elseif (!$this->parent->groups->is_invited($groupID, $userID) && !$this->parent->groups->is_authorized($groupID, $password)) {
            return JOIN_GROUP_FORBIDDEN;
        } elseif ($invitation = $this->parent->groups->is_invited($groupID, $userID)) {
            $this->parent->groups->use_invitation($invitation['code']);
        }
        $user = $this->get($userID);
        if (!$user) {
            return false;
        }

        $pos = $this->get_next_free_groupID($userID);
        if (!$pos) {
            return JOIN_GROUP_FULL;
        }
        if ($pos == 1) {
            $pos = "";
        }

        $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'users';
        $req .= ' SET groupID' . $pos . ' = ' . $groupID . '';
        $req .= ' WHERE userID = ' . $userID . '';
        $ret = $this->parent->db->exec_query($req);
        $this->parent->update_session();
        if ($ret) {
            return JOIN_GROUP_OK;
        } else {
            return false;
        }
    }

    function set_last_rank($userID, $last_rank)
    {
        $user = $this->get($userID);
        if (!$user) {
            return false;
        }

        prepare_numeric_data(array(&$last_rank, &$userID));

        $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'users';
        $req .= ' SET last_rank = ' . $last_rank . '';
        $req .= " WHERE userID = $userID";

        return $this->parent->db->exec_query($req);
    }

    function update_last_connection($userID)
    {
        $user = $this->get($userID);
        if (!$user) {
            return false;
        }

        prepare_numeric_data(array(&$userID));

        $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'users';
        $req .= ' SET last_connection = NOW()';
        $req .= " WHERE userID = $userID";

        return $this->parent->db->exec_query($req);
    }

    function update_last_bet($userID)
    {
        $user = $this->get($userID);
        if (!$user) {
            return false;
        }

        prepare_numeric_data(array(&$userID));

        $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'users';
        $req .= ' SET last_bet = NOW()';
        $req .= " WHERE userID = $userID";

        return $this->parent->db->exec_query($req);
    }

    function set_new_password($userID)
    {
        $user = $this->get($userID);
        if (!$user) {
            return false;
        }

        $new_pass = new_password(8);
        $mac = hash_hmac('sha256', $new_pass, $this->parent->config['secret_key']);

        $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'users';
        $req .= " SET password = '$mac'";
        $req .= " WHERE userID = $userID";

        if ($this->parent->db->exec_query($req)) {
            return $new_pass;
        }

        return false;
    }

    function set_password($userID, $old_password, $new_password1, $new_password2)
    {
        if ($new_password1 !== $new_password2) {
          return PASSWORD_MISMATCH;
        }

        $userPassword = $this->get_password($userID);
        if (empty($userPassword)) {
          return false;
        }

        if ($userPassword !== hash_hmac('sha256', $old_password, $this->parent->config['secret_key'])) {
          return INCORRECT_PASSWORD;
        }

        $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'users';
        $req .= ' SET password = \'' . hash_hmac('sha256', $new_password1, $this->parent->config['secret_key']) . '\'';
        $req .= " WHERE userID = $userID";

        if ($this->parent->db->exec_query($req)) {
            return CHANGE_PASSWORD_OK;
        }

        return INCORRECT_PASSWORD;
    }

    function is_authentificate($login, $pass, &$user)
    {
        // Main Query
        $req = 'SELECT *';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users';
        $req .= ' WHERE lower(login) = \'' . strtolower($login) . '\'';
        $req .= ' AND password = \'' . hash_hmac('sha256', $pass, $this->parent->config['secret_key']) . '\'';

        $nb_user = 0;
        $user = $this->parent->db->select_line($req, $nb_user);
        if ($nb_user === 1) {
            return true;
        }

        return INCORRECT_PASSWORD;
    }

    public function get_active_users_who_have_not_bet($dayCount, $gameCount)
    {
        $usersTableName = $this->parent->config['db_prefix'] . 'users';
        $betsTableName = $this->parent->config['db_prefix'] . 'bets';
        $matchesTableName = $this->parent->config['db_prefix'] . 'matches';

        $req = <<<SQL
SELECT * FROM $usersTableName
WHERE last_connection > (NOW() - INTERVAL 7 DAY) AND points is NOT NULL
AND userID NOT IN
(SELECT userID FROM $betsTableName b WHERE scoreA IS NOT NULL AND scoreB IS NOT NULL AND matchID IN
(SELECT m.matchID FROM $matchesTableName AS m
WHERE DATEDIFF(m.date, NOW()) >= 0 AND DATEDIFF(m.date, NOW()) <= :dayCount)
GROUP BY userID HAVING count(userID) = :gameCount)
SQL;

        $userCount = 0;
        $users = $this->parent->db->selectArray($req, ['dayCount' => $dayCount, 'gameCount' => $gameCount], $userCount);
        if ($this->parent->debug) {
            array_show($users);
        }
        return $users;
    }

    function get_full_ranking()
    {
        $users = $this->get();
        $nb_bets = $this->parent->bets->count_by_users();
        $ranks = [];
        usort($users, 'compare_users');
        $i = 1;
        $j = 0;
        $max_val = 0;
        $min_val = 0;

        if (sizeof($users) > 0) {
            $last_user = $users[0];
            foreach ($users as $ID => $user) {
                $ranks[$user['userID']] = $user;
                if (compare_users($user, $last_user) != 0) {
                    $i = $j + 1;
                }

                if ($nb_bets[$user['userID']] == 0) {
                    $ranks[$user['userID']]['rank'] = null;
                    $ranks[$user['userID']]['evol'] = 0;
                    continue;
                }

                if (!($user['last_rank'] > 0)) {
                    $ranks[$user['userID']]['rank'] = $i;
                    $ranks[$user['userID']]['evol'] = 0;
                    continue;
                }

                $ranks[$user['userID']]['rank'] = $i;
                $evol = $user['last_rank'] - $i;
                $ranks[$user['userID']]['evol'] = $evol;

                if ($evol > $max_val) {
                    $max_val = $evol;
                }
                if ($evol < $min_val) {
                    $min_val = $evol;
                }

                $j++;
                $last_user = $user;
            }
        }
        return $ranks;
    }

    function get_ranking()
    {
        $users = $this->get();
        $nb_bets = $this->parent->bets->count_by_users();
        $ranks = [];
        usort($users, "compare_users");
        $i = 1;
        $j = 0;
        $last_user = $users[0];
        foreach ($users as $ID => $user) {
            if ($nb_bets[$user['userID']] == 0) {
                $ranks[$user['userID']] = 'NULL';
                continue;
            }
            if (compare_users($user, $last_user) != 0) {
                $i = $j + 1;
            }
            $ranks[$user['userID']] = $i;
            $j++;
            $last_user = $user;
        }
        return $ranks;
    }

    function update_HTTP_ranking($update_rank = false)
    {
        $cur_user = $this->get_current_id();
        $is_generating = $this->parent->settings->get_value('IS_USER_RANKING_GENERATING');

        if (($is_generating > 0) && ($is_generating != $cur_user)) {
            echo "IN_PROGRESS";
            return false;
        } else {
            $this->parent->settings->set('IS_USER_RANKING_GENERATING', $cur_user, 'NULL');
        }
        $matches = $this->parent->matches->get();
        $users = [];
        $ranks = $this->get_ranking();
        $last_played = $this->parent->matches->get_last_played();

        $m = 0;
        foreach ($matches as $match) {
            if (($match['scoreA'] === null) || ($match['scoreB'] === null)) {
                continue;
            }

            $bets = $this->parent->bets->get_by_match($match['matchID']);
            foreach ($bets as $bet) {
                if (!isset($users[$bet['userID']])) {
                    $users[$bet['userID']] = [];
                    $users[$bet['userID']]['nbbets'] = 0;
                    $users[$bet['userID']]['points'] = 0;
                    $users[$bet['userID']]['nbscores'] = 0;
                    $users[$bet['userID']]['diff'] = 0;
                    $users[$bet['userID']]['nbresults'] = 0;
                    $users[$bet['userID']]['lastmatch'] = false;
                    $users[$bet['userID']]['rank'] = (isset($ranks[$bet['userID']])) ? $ranks[$bet['userID']] : count($ranks);
                }
                $result = $this->parent->bets->get_points($bet);
                if ($last_played['matchID'] == $match['matchID']) {
                    $users[$bet['userID']]['lastmatch'] = true;
                }
                if ($result['exact_score']) {
                    $users[$bet['userID']]['nbscores']++;
                }
                if ($result['good_result'] || $result['qualify']) {
                    $users[$bet['userID']]['nbresults']++;
                }
                $users[$bet['userID']]['points'] += $result['points'];
                $users[$bet['userID']]['diff'] += $result['diff'];
                $users[$bet['userID']]['nbbets']++;
            }
            $m++;
        }

        foreach ($users as $ID => $user) {
            if ((($user['nbbets'] / $m) < $this->parent->config['min_ratio_played_matches_for_rank']) && (!$user['lastmatch'])) {
                $user['points'] = 'NULL';
            }
            if ($update_rank) {
                $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'users';
                $req .= ' SET points = ' . $user['points'] . ', nbresults = ' . $user['nbresults'] . ', nbscores = ' . $user['nbscores'] . ', diff = ' . $user['diff'] . ', last_rank = ' . $user['rank'] . '';
                $req .= ' WHERE userID = ' . $ID . '';
                $this->parent->db->exec_query($req);
            } else {
                $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'users';
                $req .= ' SET points = ' . $user['points'] . ', nbresults = ' . $user['nbresults'] . ', nbscores = ' . $user['nbscores'] . ', diff = ' . $user['diff'] . '';
                $req .= ' WHERE userID = ' . $ID . '';
                $this->parent->db->exec_query($req);
            }
        }
        $this->parent->settings->set("NB_MATCHES_GENERATED", $m);
        if ($update_rank) {
            $this->parent->settings->set("RANK_UPDATE", "NULL", "NOW()");
        }
        $this->parent->settings->set('IS_USER_RANKING_GENERATING', 0, 'NULL');
        echo "OK";

        return true;
    }

    function is_ranking_ok()
    {
        return ($this->parent->settings->get_last_result() >= $this->parent->settings->get_last_generate());
    }

}
