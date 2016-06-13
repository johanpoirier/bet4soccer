<?php

class Audit
{
    var $parent;

    public function __construct(&$parent)
    {
        $this->parent = $parent;
    }

    /*******************/

    function add($action)
    {
        $userID = $this->parent->users->get_current_id();
        prepare_alphanumeric_data(array(&$action));
        $action = htmlspecialchars(trim($action));

        $req = 'INSERT INTO ' . $this->parent->config['db_prefix'] . 'audit (`userID`, `action`)';
        $req .= " VALUES ($userID, '$action')";

        echo $req;
        return $this->parent->db->insert($req);
    }

    function delete($id)
    {
        $req = 'DELETE FROM ' . $this->parent->config['db_prefix'] . 'audit';
        $req .= " WHERE id = $id";

        return $this->parent->db->exec_query($req);
    }

    function get($id)
    {
        // Main Query
        $req = 'SELECT id, action, DATE_FORMAT(date,\'%Y-%m-%d %H:%i:%s\') as date,';
        $req .= ' u.userID, u.name, u.login, u.status as userStatus';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'audit a';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'users u ON (u.userID = a.userID)';
        $req .= " WHERE id = $id";

        $nb_audit_logs = 0;
        $log = $this->parent->db->select_line($req, $nb_audit_logs);

        if ($this->parent->debug) {
            array_show($log);
        }

        return $log;
    }

    /*******************/

    function get_between($start = false, $limit = false)
    {
        // Main Query
        $req = 'SELECT id, action, DATE_FORMAT(date,\'%Y-%m-%d %H:%i:%s\') as date,';
        $req .= ' u.userID, u.name, u.login, u.status as userStatus';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'audit a';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'users u ON (u.userID = a.userID)';
        $req .= ' ORDER BY id DESC';
        if ($limit != false) {
            $req .= ' LIMIT ' . $limit . ' OFFSET ' . $start . '';
        }

        $nb_logs = 0;
        $logs = $this->parent->db->select_array($req, $nb_logs);

        if ($this->parent->debug) {
            array_show($logs);
        }

        return $logs;
    }

    /*******************/

    function get_by_user($userID)
    {
        // Main Query
        $req = 'SELECT id, action, DATE_FORMAT(date,\'%Y-%m-%d %H:%i:%s\') as date,';
        $req .= ' u.userID, u.name, u.login, u.status as userStatus';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'audit a';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . "users u ON (u.userID = a.userID AND a.userID = $userID)";
        $req .= ' ORDER BY id DESC';

        $nb_logs = 0;
        $logs = $this->parent->db->select_array($req, $nb_logs);

        if ($this->parent->debug) {
            array_show($logs);
        }

        return $logs;
    }
}
