<?php

class Users {

    var $parent;

    public function __construct(&$parent) {
        $this->parent = $parent;
    }

    function add($login, $pass, $name, $firstname, $email, $groupID, $status) {
        $login = trim($login);
        $email = trim($email);
        $name = trim($name);
        $firstname = trim($firstname);
        if (strlen($firstname) > 0) {
            $name = $firstname . " " . $name;
        }
        if (!stristr($email, '@'))
            return INCORRECT_EMAIL;
        if ($this->is_exist($login))
            return LOGIN_ALREADY_EXISTS;
        if ($name == null || $name == "" || $login == null || $login == "")
            return FIELDS_EMPTY;

        $req = 'INSERT INTO ' . $this->parent->config['db_prefix'] . 'users (login,password,name,email,"groupID",status)';
        $req .= ' VALUES (\'' . addslashes($login) . '\',\'' . md5($pass) . '\',\'' . addslashes($name) . '\',\'' . addslashes($email) . '\',' . (($groupID != '') ? '\'' . addslashes($groupID) . '\'' : 'NULL') . ',\'' . addslashes($status) . '\')';

        return $this->parent->db->insert($req);
    }

    function add_or_update($login, $pass, $name, $email, $groupID, $status) {
        $login = trim($login);
        $name = trim($name);
        $email = trim($email);
        if (!stristr($email, '@'))
            return INCORRECT_EMAIL;
        if ($name == null || $name == "" || $login == null || $login == "")
            return FIELDS_EMPTY;
        if ($this->is_exist($login)) {
            if (strlen($pass) > 1)
                $passwordReq = ' password=\'' . md5($pass) . '\',';
            $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'users';
            $req .= ' SET name=\'' . addslashes($name) . '\',' . $passwordReq . ' email=\'' . addslashes($email) . '\',';
            $req .= ' "groupID"=' . (($groupID != '') ? addslashes($groupID) : 'NULL') . ', status=' . addslashes($status) . ' WHERE login=\'' . addslashes($login) . '\'';
            return $this->parent->db->exec_query($req);
        }
        else {
            $req = 'INSERT INTO ' . $this->parent->config['db_prefix'] . 'users (login,password,name,email,"groupID",status)';
            $req .= ' VALUES (\'' . addslashes($login) . '\',\'' . md5($pass) . '\',\'' . addslashes($name) . '\',\'' . addslashes($email) . '\',' . (($groupID != '') ? '\'' . addslashes($groupID) . '\'' : 'NULL') . ',\'' . addslashes($status) . '\')';
            return $this->parent->db->insert($req);
        }
    }

    function update_account($name, $email) {
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
        $req .= ' WHERE "userID" = ' . $user['userID'] . '';
        $ret = $this->parent->db->exec_query($req);
        $this->parent->update_session();
        if ($ret)
            return CHANGE_ACCOUNT_OK;
        else
            return UNKNOWN_ERROR;
    }

    function update_preferences($theme, $match_display) {
        prepare_alphanumeric_data(array(&$theme, &$match_display));
        $user = $this->get_current();

        if (!$user)
            return UNKNOWN_ERROR;

        $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'users';
        $req .= ' SET theme=\'' . $theme . '\', match_display=\'' . $match_display . '\'';
        $req .= ' WHERE "userID"=' . $user['userID'] . '';
        $ret = $this->parent->db->exec_query($req);
        $this->parent->update_session();
        if ($ret)
            return CHANGE_ACCOUNT_OK;
        else
            return UNKNOWN_ERROR;
    }

    function delete($userID) {
        $req = 'DELETE';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users';
        $req .= ' WHERE "userID" = ' . $userID . '';
        $this->parent->db->exec_query($req);
        return;
    }

    function get_HTTP() {
        // 0 arg = get all users
        // 1 arg = get one user

        $nb_args = func_num_args();
        $args = func_get_args();
        $userID = null;

        if ($nb_args > 0) {
            $userID = $args[0];
            if (($userID == "") || ($userID == NULL))
                $userID = 'NULL';
        }

        // Main Query
        $req = 'SELECT *';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users';
        if ($nb_args > 0)
            $req .= ' WHERE "userID" = ' . $userID . '';
        $req .= ' ORDER by name';

        // Execute Query
        $users = $this->parent->db->select_array($req, $nb_users);
        if ($this->parent->debug)
            array_show($users);

        // Return results
        if ($nb_args > 0 && $nb_users > 0) {
            $user = $users[0];
            echo $user['userID'] . "|" . $user['name'] . "|" . $user['login'] . "|" . $user['password'] . "|" . $user['email'] . "|" . $user['groupID'] . "|" . $user['status'];
            return $user;
        } else {
            foreach ($users as $user) {
                echo $user['userID'] . ";" . $user['login'] . ";" . $user['name'] . ";" . $user['email'] . ";" . $user['groupID'] . ";" . $user['status'] . "|";
            }
            return $users;
        }
    }

    function get($userID=null)
    {
        $nb_args = func_num_args();

        // Main Query
        $req = 'SELECT u.*, t.name as groupName, t2.name as groupName2, t3.name as groupName3';
        $req .= ', DATE_FORMAT(u.last_connection, \'%d/%m\') as last_connection';
        $req .= ', DATE_FORMAT(u.last_bet, \'%d/%m\') as last_bet';
        $req .= ', count(b.userID) as nb_bets';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users u';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'groups t ON (u.groupID = t.groupID)';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'groups t2 ON (u.groupID2 = t2.groupID)';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'groups t3 ON (u.groupID3 = t3.groupID)';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'bets b ON (u.userID = b.userID)';
        $req .= ' WHERE b.scoreA IS NOT NULL AND b.scoreB IS NOT NULL';
        if ($nb_args > 0) {
            $req .= ' AND u.userID = ' . $userID . '';
        }
        $req .= ' GROUP BY b.userID';
        $req .= ' ORDER by u.name';

        // Execute Query
        $nb_users = 0;
        $users = $this->parent->db->select_array($req, $nb_users);
        if ($this->parent->debug) {
            array_show($users);
        }

        // Return results
        if ($nb_args > 0 && isset($users[0])) {
             return $users[0];
        }
        else {
            return $users;
        }
    }

	function get_with_no_vote() {
		// Main Query
        $req = 'SELECT userID, name, login, email from ' . $this->parent->config['db_prefix'] . 'users where userID not in';
		$req .= ' (SELECT userID FROM ' . $this->parent->config['db_prefix'] . 'bets as b WHERE b.matchID IN';
		$req .= ' (SELECT matchID FROM ' . $this->parent->config['db_prefix'] . 'matches WHERE DATEDIFF( DATE, CURRENT_DATE( ) ) = 0))';
		$req .= ' ORDER BY name';

        // Execute Query
        $users = $this->parent->db->select_array($req, $nb_users);
        if ($this->parent->debug) {
            array_show($users);
		}

        return $users;
	}
	
    /*     * **************** */

    function count() {
        // Main Query
        $req = 'SELECT COUNT("userID")';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users';

        $nb_user = $this->parent->db->select_one($req);

        if ($this->parent->debug)
            array_show($nb_user);

        return $nb_user;
    }

    /*     * **************** */

    function count_active() {
        // Main Query
        $req = 'SELECT COUNT("userID")';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users';
        $req .= ' WHERE "userID" IN (';
        $req .= ' SELECT u."userID"';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users u';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'bets b ON (u."userID" = b."userID" AND b."scoreA" IS NOT NULL AND b."scoreB" IS NOT NULL)';
        $req .= ' GROUP BY u."userID"';
        $req .= ' HAVING COUNT(b."matchID") > 0';
        $req .= ')';
        $req .= ' AND points IS NOT NULL';

        $nb_active_user = $this->parent->db->select_one($req);

        if ($this->parent->debug)
            array_show($nb_active_user);

        return $nb_active_user;
    }

    /*     * **************** */

    function count_active_by_group($groupID) {
        // Main Query
        $req = 'SELECT COUNT("userID")';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users';
        $req .= ' WHERE "userID" IN (';
        $req .= ' SELECT u."userID"';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users u';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'bets b ON (u."userID" = b."userID" AND b."scoreA" IS NOT NULL AND b."scoreB" IS NOT NULL)';
        $req .= ' WHERE (u."groupID" = ' . $groupID . '';
        $req .= ' OR u."groupID2" = ' . $groupID . '';
        $req .= ' OR u."groupID3" = ' . $groupID . ')';
        $req .= ' AND u.status >= 0';
        $req .= ' GROUP BY u."userID"';
        $req .= ' HAVING COUNT(b."matchID") > 0';
        $req .= ')';

        $nb_active_user = $this->parent->db->select_one($req);

        if ($this->parent->debug)
            array_show($nb_active_user);

        return $nb_active_user;
    }

    /*     * **************** */

    function get_current_id() {
        return (isset($_SESSION['userID'])) ? $_SESSION['userID'] : false;
    }

    function get_current() {
        return $this->get($this->get_current_id());
    }

    function get_by_login($login) {
        // Main Query
        $req = 'SELECT *';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users';
        $req .= ' WHERE login = \'' . $login . '\'';

        $user = $this->parent->db->select_line($req, $nb_users);

        if ($this->parent->debug)
            array_show($users);

        return $user;
    }

    function get_by_email($email) {
        prepare_alphanumeric_data(array(&$email));
        // Main Query
        $req = 'SELECT *';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users';
        $req .= ' WHERE email = \'' . $email . '\'';

        $user = $this->parent->db->select_line($req, $nb_users);

        if ($this->parent->debug)
            array_show($users);

        return $user;
    }

    function count_by_group($groupID) {
        // Main Query
        $req = 'SELECT COUNT(u."userID")';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users u';
        $req .= ' WHERE (u."groupID" = ' . $groupID . '';
        $req .= ' OR u."groupID2" = ' . $groupID . '';
        $req .= ' OR u."groupID3" = ' . $groupID . ')';
        $req .= ' AND u.status >= 0';

        $nb_users = $this->parent->db->select_one($req);
        if ($this->parent->debug)
            echo $nb_users;

        return $nb_users;
    }

    /*     * **************** */

    function get_by_group($groupID, $all_users = false) {
        // Main Query
        $req = 'SELECT DISTINCT(u."userID"), u.name, u.login, u.points, u.nbresults, u.nbscores, u.diff, u.last_rank, u.status, t.name AS group_name';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users u';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'bets AS b ON(b."userID" = u."userID")';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'groups AS t ON(t."groupID" = u."groupID")';
        $req .= ' WHERE u."groupID" = ' . $groupID . '';
        $req .= ' OR u."groupID2" = ' . $groupID . '';
        $req .= ' OR u."groupID3" = ' . $groupID . '';
        if (!$all_users) {
            $req .= ' AND (b."scoreA" IS NOT null) AND (b."scoreB" IS NOT null) AND u.status >= 0';
        }
        $req .= ' ORDER BY u.name ASC';

        $users = $this->parent->db->select_array($req, $nb_groups);
        if ($this->parent->debug)
            array_show($users);

        return $users;
    }

    function is_exist($login) {
        // Main Query
        $req = 'SELECT "userID"';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users ';
        $req .= ' WHERE login = \'' . $login . '\'';

        return $this->parent->db->select_one($req, null);
    }

    function get_max_points() {
        // Main Query
        $req = 'SELECT max(points)';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users ';

        return $this->parent->db->select_one($req, null);
    }

    function is_exist_by_email($email) {
        // Main Query
        $req = 'SELECT "userID"';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users ';
        $req .= ' WHERE email = \'' . $email . '\'';

        return $this->parent->db->select_one($req, null);
    }

    function is_name_exist($name) {
        // Main Query
        $req = 'SELECT "userID"';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users ';
        $req .= ' WHERE name = \'' . $name . '\'';

        return $this->parent->db->select_one($req, null);
    }

    function is_admin($userID) {
        // Main Query
        $req = 'SELECT status';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users ';
        $req .= ' WHERE userID = ' . $userID;

        $user = $this->parent->db->select_one($req, null);
		
		return $user['status'] == 1;
    }

    function unset_group($groupID, $userID = false) {
        if (!$userID)
            $userID = $this->get_current_id();
        $pos = $this->is_in_group($groupID, $userID);
        if (!$pos)
            return false;
        if ($pos == 1)
            $pos = "";
        $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'users';
        $req .= ' SET "groupID' . $pos . '" = NULL';
        $req .= ' WHERE "userID" = ' . $userID . '';
        $ret = $this->parent->db->exec_query($req);
        $this->parent->update_session();
        return $ret;
    }

    function is_in_group($groupID, $userID = false) {
        if (!$userID)
            $userID = $this->get_current_id();
        $user = $this->get($userID);
        if ($user['groupID'] == $groupID)
            return 1;
        if ($user['groupID2'] == $groupID)
            return 2;
        if ($user['groupID3'] == $groupID)
            return 3;
        return false;
    }

    function count_groups($userID = false) {
        if (!$userID)
            $userID = $this->get_current_id();
        $user = $this->get($userID);
        $i = 0;
        if ($user['groupID'] > 0)
            $i++;
        if ($user['groupID2'] > 0)
            $i++;
        if ($user['groupID3'] > 0)
            $i++;
        return $i;
    }

    function get_next_free_groupID($userID = false) {
        if (!$userID)
            $userID = $this->get_current_id();
        $user = $this->get($userID);
        if (!($user['groupID'] > 0))
            return 1;
        if (!($user['groupID2'] > 0))
            return 2;
        if (!($user['groupID3'] > 0))
            return 3;
        return false;
    }

    function set_group($userID, $groupID, $password, $code = false) {
        if ($code) {
            $invitation = $this->parent->groups->is_invited_by_code($code);
            if ($invitation['groupID'] != $groupID)
                return JOIN_GROUP_FORBIDDEN;
            $this->parent->groups->use_invitation($code);
        }
        elseif (!$this->parent->groups->is_invited($groupID, $userID) && !$this->parent->groups->is_authorized($groupID, $password)) {
            return JOIN_GROUP_FORBIDDEN;
        } elseif ($invitation = $this->parent->groups->is_invited($groupID, $userID)) {
            $this->parent->groups->use_invitation($invitation['code']);
        }
        $user = $this->get($userID);
        if (!$user)
            return false;

        $pos = $this->get_next_free_groupID($userID);
        if (!$pos)
            return JOIN_GROUP_FULL;
        if ($pos == 1)
            $pos = "";

        $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'users';
        $req .= ' SET "groupID' . $pos . '" = ' . $groupID . '';
        $req .= ' WHERE "userID" = ' . $userID . '';
        $ret = $this->parent->db->exec_query($req);
        $this->parent->update_session();
        if ($ret)
            return JOIN_GROUP_OK;
        else
            return false;
    }

    function set_last_rank($userID, $last_rank) {
        $user = $this->get($userID);
        if (!$user)
            return false;

        prepare_numeric_data(array(&$last_rank, &$userID));

        $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'users';
        $req .= ' SET last_rank = ' . $last_rank . '';
        $req .= ' WHERE "userID" = ' . $userID . '';

        return $this->parent->db->exec_query($req);
    }

    function update_last_connection($userID) {
        $user = $this->get($userID);
        if (!$user)
            return false;

        prepare_numeric_data(array(&$last_rank, &$userID));

        $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'users';
        $req .= ' SET last_connection = NOW()';
        $req .= ' WHERE "userID" = ' . $userID . '';

        return $this->parent->db->exec_query($req);
    }

    function update_last_bet($userID) {
        $user = $this->get($userID);
        if (!$user)
            return false;

        prepare_numeric_data(array(&$last_rank, &$userID));

        $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'users';
        $req .= ' SET last_bet = NOW()';
        $req .= ' WHERE "userID" = ' . $userID . '';

        return $this->parent->db->exec_query($req);
    }

    function set_new_password($userID) {
        $user = $this->get($userID);
        if (!$user)
            return false;
        $new_pass = new_password(8);

        $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'users';
        $req .= ' SET password = \'' . md5($new_pass) . '\'';
        $req .= ' WHERE "userID" = ' . $userID . '';

        if ($this->parent->db->exec_query($req))
            return $new_pass;
        else
            return false;
    }

    function set_password($userID, $old_password, $new_password1, $new_password2) {
        if ($new_password1 != $new_password2)
            return PASSWORD_MISMATCH;
        $user = $this->get($userID);
        if (!$user)
            return false;
        if ($user['password'] != md5($old_password))
            return INCORRECT_PASSWORD;

        $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'users';
        $req .= ' SET password = \'' . md5($new_password1) . '\'';
        $req .= ' WHERE "userID" = ' . $userID . '';

        if ($this->parent->db->exec_query($req))
            return CHANGE_PASSWORD_OK;
        else
            return INCORRECT_PASSWORD;
    }

    function is_authentificate($login, $pass, &$user) {
        if ($this->parent->config['auth'] == 'LDAP') {
            if ($pass == "" || $pass == null || !isset($pass))
                return false;
            $login = strtolower($login);
            $ldap_ret = $this->parent->ldap->connect($login, $pass);
            if ($ldap_ret <= 0)
                return $ldap_ret;
            if ($userID = $this->is_exist($login)) {
                $user = $this->get($userID);
                return true;
            }
            $ldap_user = $this->parent->ldap->get_user($login);
            if (!$ldap_user)
                return false;
            if ($ldap_user['site'] != 'Lyon')
                return AWL_NOT_GOOD_SITE;
            $group = $this->parent->groups->get_by_name($ldap_user['group_name']);
            $userID = $this->add($ldap_user['login'], 0, $ldap_user['name'], $ldap_user['email'], $group['groupID'], 0);
            $user = $this->get($userID);
            return ($this->is_exist($login));
        } else {
            // Main Query
            $req = 'SELECT *';
            $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'users ';
            $req .= ' WHERE lower(login) = \'' . strtolower($login) . '\'';
            $req .= ' AND password = \'' . md5($pass) . '\'';

            $user = $this->parent->db->select_line($req, $nb_user);
            if ($nb_user == 1)
                return true;
            else
                return INCORRECT_PASSWORD;
        }
    }

    function get_active_users_who_have_not_bet($nbDays, $nbGames) {
        $req = "SELECT * FROM " . $this->parent->config['db_prefix'] . "users";
        $req .= " WHERE userID NOT IN (";
        $req .= "SELECT b.userID FROM " . $this->parent->config['db_prefix'] . "users u";
        $req .= " RIGHT JOIN " . $this->parent->config['db_prefix'] . "bets b ON(u.userID = b.userID)";
        $req .= " WHERE b.scoreA IS NOT NULL AND b.scoreB IS NOT NULL and b.matchID IN (";
        $req .= "SELECT m.matchID FROM " . $this->parent->config['db_prefix'] . "matches AS m";
        $req .= " WHERE DATEDIFF(m.date, NOW()) >= 0  AND DATEDIFF(m.date, NOW()) <= " . $nbDays . ")";
        $req .= " GROUP BY b.userID HAVING count(b.userID) = " . $nbGames . ")";

        $nbUsers = 0;
        $users = $this->parent->db->select_array($req, $nbUsers);
        if ($this->parent->debug) {
            array_show($users);
        }
        return $users;
    }
    
    function get_full_ranking() {
        $users = $this->get();
        $nb_bets = $this->parent->bets->count_by_users();
        $ranks = array();
        usort($users, "compare_users");
        $i = 1;
        $j = 0;
        $max_val = 0;
        $min_val = 0;
        if (sizeof($users) > 0) {
            $last_user = $users[0];
            foreach ($users as $ID => $user) {
                $ranks[$user['userID']] = $user;
                if (compare_users($user, $last_user) != 0)
                    $i = $j + 1;
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

    function get_ranking() {
        $users = $this->get();
        $nb_bets = $this->parent->bets->count_by_users();
        $ranks = array();
        usort($users, "compare_users");
        $i = 1;
        $j = 0;
        $last_user = $users[0];
        foreach ($users as $ID => $user) {
            if ($nb_bets[$user['userID']] == 0) {
                $ranks[$user['userID']] = 'NULL';
                continue;
            }
            if (compare_users($user, $last_user) != 0)
                $i = $j + 1;
            $ranks[$user['userID']] = $i;
            $j++;
            $last_user = $user;
        }
        return $ranks;
    }

    function update_HTTP_ranking($update_rank = false) {
        $cur_user = $this->get_current_id();
        $is_generating = $this->parent->settings->get_value('IS_USER_RANKING_GENERATING');

        if (($is_generating > 0) && ($is_generating != $cur_user) && !$forced) {
            echo "IN_PROGRESS";
            return false;
        } else
            $this->parent->settings->set('IS_USER_RANKING_GENERATING', $cur_user, 'NULL');
        $matches = $this->parent->matches->get();
        $users = array();
        $ranks = $this->get_ranking();
        $last_played = $this->parent->matches->get_last_played();

        $m = 0;
        foreach ($matches as $match) {
            if (($match['scoreA'] == NULL) || ($match['scoreB'] == NULL))
                continue;

            $bets = $this->parent->bets->get_by_match($match['matchID']);
            foreach ($bets as $bet) {
                if (!isset($users[$bet['userID']])) {
                    $users[$bet['userID']] = array();
                    $users[$bet['userID']]['nbbets'] = 0;
                    $users[$bet['userID']]['points'] = 0;
                    $users[$bet['userID']]['nbscores'] = 0;
                    $users[$bet['userID']]['diff'] = 0;
                    $users[$bet['userID']]['nbresults'] = 0;
                    $users[$bet['userID']]['lastmatch'] = false;
                    $users[$bet['userID']]['rank'] = (isset($ranks[$bet['userID']])) ? $ranks[$bet['userID']] : count($ranks);
                }
                $result = $this->parent->bets->get_points($bet);
                if ($last_played['matchID'] == $match['matchID'])
                    $users[$bet['userID']]['lastmatch'] = true;
                if ($result['exact_score'])
                    $users[$bet['userID']]['nbscores']++;
                if ($result['good_result'] || $result['qualify'])
                    $users[$bet['userID']]['nbresults']++;
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
                $req .= ' WHERE "userID" = ' . $ID . '';
                $this->parent->db->exec_query($req);
            } else {
                $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'users';
                $req .= ' SET points = ' . $user['points'] . ', nbresults = ' . $user['nbresults'] . ', nbscores = ' . $user['nbscores'] . ', diff = ' . $user['diff'] . '';
                $req .= ' WHERE "userID" = ' . $ID . '';
                $this->parent->db->exec_query($req);
            }
        }
        $this->parent->settings->set("NB_MATCHES_GENERATED", $m);
        if ($update_rank)
            $this->parent->settings->set("RANK_UPDATE", "NULL", "NOW()");
        $this->parent->settings->set('IS_USER_RANKING_GENERATING', 0, 'NULL');
        echo "OK";
        return true;
    }

    function is_ranking_ok() {
        return ($this->parent->settings->get_last_result() >= $this->parent->settings->get_last_generate());
    }

}
