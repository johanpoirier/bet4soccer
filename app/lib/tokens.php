<?php

class Tokens
{
    var $db;
    var $config;

    public function __construct(&$db, &$config)
    {
        $this->db = $db;
        $this->config = $config;
    }

    /*******************/

    function add($userID, $deviceUuid, $token)
    {
        $req = 'REPLACE INTO ' . $this->config['db_prefix'] . 'tokens (`userID`, `device`, `token`)';
        $req .= " VALUES ($userID, '$deviceUuid', '$token')";

        return $this->db->insert($req);
    }

    /*******************/

    function get_by_user_and_device($userID, $deviceUuid)
    {
        $req = 'SELECT token FROM ' . $this->config['db_prefix'] . 'tokens';
        $req .= " WHERE  userID = $userID and device = '$deviceUuid'";

        return $this->db->select_one($req);
    }

    function get_by_user($userID)
    {
        $req = 'SELECT token FROM ' . $this->config['db_prefix'] . 'tokens';
        $req .= " WHERE  userID = $userID";

        $nb_tokens = 0;
        return $this->db->select_array($req, $nb_tokens);
    }

    function get()
    {
        $req = 'SELECT * FROM ' . $this->config['db_prefix'] . 'tokens';
        $req .= ' ORDER BY date DESC';

        $nb_tokens = 0;
        return $this->db->select_array($req, $nb_tokens);
    }

}
