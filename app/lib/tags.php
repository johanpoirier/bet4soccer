<?php

class Tags
{
    var $parent;

    public function __construct(&$parent)
    {
        $this->parent = $parent;
    }

    /*******************/

    function add($text, $groupID = '', $userID = false)
    {
        if (!$userID) {
            $userID = $this->parent->users->get_current_id();
        }
        
        if ($userID != $this->parent->users->get_current_id()) {
            return false;
        }
        if ($this->parent->users->is_in_group($groupID) === false) {
            return false;
        }

        prepare_numeric_data(array(&$groupID, &$userID));
        prepare_alphanumeric_data(array(&$text));
        $text = htmlspecialchars(trim($text));
        if ($text == 'NULL') {
            return false;
        }
        $req = 'INSERT INTO ' . $this->parent->config['db_prefix'] . 'tags (userID,groupID,date,tag)';
        $req .= ' VALUES (' . $userID . ',' . $groupID . ',NOW(),\'' . $text . '\')';

        return $this->parent->db->insert($req);
    }

    function delete($tagID)
    {
        $tag = $this->get($tagID);
        if (!$tag) return false;
        if (!$this->parent->islogin()) return false;
        if ($tag['userID'] != $_SESSION['userID'] && !$this->parent->isadmin()) return false;

        $req = 'DELETE FROM ' . $this->parent->config['db_prefix'] . 'tags';
        $req .= ' WHERE tagID = ' . $tagID . '';

        return $this->parent->db->exec_query($req);
    }

    function is_exist($tagID)
    {
        prepare_numeric_data(array(&$tagID));
        // Main Query
        $req = 'SELECT tagID';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'tags ';
        $req .= ' WHERE tagID = ' . $tagID;

        return $this->parent->db->select_one($req);

    }

    function get($tagID)
    {
        // Main Query
        $req = 'SELECT *,';
        $req .= 'DATE_FORMAT(date,\'%d/%m à %kh%i\') as date_str';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'tags t ';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'users u ON (u.userID = t.userID)';
        $req .= ' WHERE tagID = ' . $tagID . '';

        $nb_tags = 0;
        $tag = $this->parent->db->select_line($req, $nb_tags);

        if ($this->parent->debug) {
            array_show($tag);
        }

        return $tag;
    }

    /*******************/

    function count()
    {
        // Main Query
        $req = 'SELECT count(*)';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'tags t ';

        $nb_tags = $this->parent->db->select_one($req);

        if ($this->parent->debug) {
            echo($nb_tags);
        }

        return $nb_tags;
    }

    /*******************/

    function count_by_group($groupID = false)
    {
        // Main Query
        $req = 'SELECT count(*)';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'tags t ';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'users u ON (u.userID = t.userID)';
        if ($groupID) {
            $req .= ' WHERE t.groupID = ' . $groupID . '';
        } else {
            $req .= ' WHERE t.groupID IS NULL';
        }
        $nb_tags = $this->parent->db->select_one($req);

        if ($this->parent->debug) {
            echo($nb_tags);
        }

        return $nb_tags;
    }

    /*******************/

    function get_between($start = false, $limit = false)
    {
        // Main Query
        $req = 'SELECT *,';
        $req .= 'DATE_FORMAT(date,\'%d/%m à %kh%i\') as date_str';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'tags t ';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'users u ON (u.userID = t.userID)';
        $req .= ' ORDER BY date DESC';
        if ($limit != false) {
            $req .= ' LIMIT ' . $limit . ' OFFSET ' . $start . '';
        }

        $nb_tags = 0;
        $tags = $this->parent->db->select_array($req, $nb_tags);

        if ($this->parent->debug) {
            array_show($tags);
        }

        return $tags;
    }

    /*******************/

    function get_by_group($groupID, $start = false, $limit = false)
    {
        // Main Query
        $req = 'SELECT *,';
        $req .= 'DATE_FORMAT(date,\'%d/%m à %kh%i\') as date_str';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'tags t ';
        $req .= ' LEFT JOIN ' . $this->parent->config['db_prefix'] . 'users u ON (u.userID = t.userID)';
        $req .= ' WHERE t.groupID ' . (($groupID != '') ? " = $groupID" : ' IS NULL');
        $req .= ' ORDER BY date DESC';
        if ($limit != false) {
            $req .= ' LIMIT ' . $limit . ' OFFSET ' . $start . '';
        }

        $nb_tags = 0;
        $tags = $this->parent->db->select_array($req, $nb_tags);

        if ($this->parent->debug) {
            array_show($tags);
        }

        return $tags;
    }

    /*******************/

    function get_by_tag($text)
    {
        // Main Query
        $req = 'SELECT *';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'tags t ';
        $req .= ' WHERE t.tag = \'' . $text . '\'';

        $nb_tags = 0;
        $tag = $this->parent->db->select_line($req, $nb_tags);

        if ($this->parent->debug) {
            array_show($tag);
        }

        return $tag;
    }
}
