<?php

class Audit
{
    var $db;
    var $config;

    public function __construct(&$db, &$config)
    {
        $this->db = $db;
        $this->config = $config;
    }

    /*******************/

    function add($userID, $category, $action)
    {
        prepare_alphanumeric_data(array(&$action));
        $action = htmlspecialchars(trim($action));

        $req = 'INSERT INTO ' . $this->config['db_prefix'] . 'audit (`userID`, `category`, `action`)';
        $req .= " VALUES ($userID, '$category', '$action')";

        return $this->db->insert($req);
    }

    function delete($id)
    {
        $req = 'DELETE FROM ' . $this->config['db_prefix'] . 'audit';
        $req .= " WHERE id = $id";

        return $this->db->exec_query($req);
    }

    /*******************/

    function get($id)
    {
        // Main Query
        $req = 'SELECT id, category, action, DATE_FORMAT(date,\'%Y-%m-%d %H:%i:%s\') as date,';
        $req .= ' u.userID, u.name, u.login, u.status as userStatus';
        $req .= ' FROM ' . $this->config['db_prefix'] . 'audit a';
        $req .= ' LEFT JOIN ' . $this->config['db_prefix'] . 'users u ON (u.userID = a.userID)';
        $req .= " WHERE id = $id";

        $nb_audit_logs = 0;
        $log = $this->db->select_line($req, $nb_audit_logs);

        return $log;
    }

    function get_between($start = false, $limit = false)
    {
        // Main Query
        $req = 'SELECT id, category, action, DATE_FORMAT(date,\'%Y-%m-%d %H:%i:%s\') as date,';
        $req .= ' u.userID, u.name, u.login, u.status as userStatus';
        $req .= ' FROM ' . $this->config['db_prefix'] . 'audit a';
        $req .= ' LEFT JOIN ' . $this->config['db_prefix'] . 'users u ON (u.userID = a.userID)';
        $req .= ' ORDER BY id DESC';
        if ($limit != false) {
            $req .= ' LIMIT ' . $limit . ' OFFSET ' . $start . '';
        }

        $nb_logs = 0;
        $logs = $this->db->select_array($req, $nb_logs);

        return $logs;
    }

    function get_by_user($userID)
    {
        // Main Query
        $req = 'SELECT id, category, action, DATE_FORMAT(date,\'%Y-%m-%d %H:%i:%s\') as date,';
        $req .= ' u.userID, u.name, u.login, u.status as userStatus';
        $req .= ' FROM ' . $this->config['db_prefix'] . 'audit a';
        $req .= ' LEFT JOIN ' . $this->config['db_prefix'] . "users u ON (u.userID = a.userID AND a.userID = $userID)";
        $req .= ' ORDER BY id DESC';

        $nb_logs = 0;
        $logs = $this->db->select_array($req, $nb_logs);

        return $logs;
    }

    function get_by_user_and_category($userID, $category)
    {
        // Main Query
        $req = 'SELECT id, category, action, DATE_FORMAT(date,\'%Y-%m-%d %H:%i:%s\') as date,';
        $req .= ' u.userID, u.name, u.login, u.status as userStatus';
        $req .= ' FROM ' . $this->config['db_prefix'] . 'audit a';
        $req .= ' LEFT JOIN ' . $this->config['db_prefix'] . "users u ON (u.userID = a.userID)";
        $req .= ' WHERE 1';
        if ($category) {
            $req .= " AND category = '$category'";
        }
        if ($userID) {
            $req .= " AND a.userID = $userID";
        }
        $req .= ' ORDER BY id DESC';

        $nb_logs = 0;
        $logs = $this->db->select_array($req, $nb_logs);

        return $logs;
    }

    function get_categories()
    {
        // Main Query
        $req = 'SELECT DISTINCT category';
        $req .= ' FROM ' . $this->config['db_prefix'] . 'audit';
        $req .= ' WHERE category IS NOT NULL';
        $req .= ' ORDER BY category ASC';

        $nb_categories = 0;
        $categories = $this->db->select_col($req, $nb_categories);

        return $categories;
    }
}
