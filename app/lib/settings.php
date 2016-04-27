<?php

class Settings
{
    var $parent;

    public function __construct(&$parent)
    {
        $this->parent = $parent;
    }

    function get_last_result()
    {
        return $this->get_date('LAST_RESULT');
    }

    function get_last_generate()
    {
        return $this->get_date('LAST_GENERATE');
    }

    function get_next_rank_groups_update()
    {
        $req = 'SELECT UNIX_TIMESTAMP(ADDDATE(date,1))';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'settings';
        $req .= ' WHERE name = \'RANK_GROUPS_UPDATE\'';

        $rank_update = $this->parent->db->select_one($req, null);

        if ($this->parent->debug) {
            echo $rank_update;
        }

        return $rank_update;
    }

    function get_next_rank_update()
    {
        $req = 'SELECT UNIX_TIMESTAMP(ADDDATE(date,1))';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'settings';
        $req .= ' WHERE name = \'RANK_UPDATE\'';

        $rank_update = $this->parent->db->select_one($req, null);

        if ($rank_update == "") {
            $this->set_last_rank_update();
        }

        if ($this->parent->debug) {
            echo $rank_update;
        }

        return $rank_update;
    }

    function set_last_rank_update()
    {
        return $this->set('RANK_UPDATE', 'NULL', 'NOW()');
    }

    function set_next_rank_groups_update()
    {
        return $this->set('RANK_GROUPS_UPDATE', 'NULL', 'NOW()');
    }

    function set_last_result()
    {
        return $this->set('LAST_RESULT', 'NULL', 'NOW()');
    }

    function set_last_generate()
    {
        return $this->set('LAST_GENERATE', 'NULL', 'NOW()');
    }

    function get_date($setting)
    {
        prepare_alphanumeric_data(array(&$setting));
        // Main Query
        $req = 'SELECT date';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'settings';
        $req .= ' WHERE name = \'' . $setting . '\'';

        $date = $this->parent->db->select_one($req, null);

        if ($this->parent->debug) echo $date;

        return $date;
    }

    function get_value($setting)
    {
        prepare_alphanumeric_data(array(&$setting));
        // Main Query
        $req = 'SELECT value';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'settings';
        $req .= ' WHERE name = \'' . $setting . '\'';

        $value = $this->parent->db->select_one($req, null);

        if ($this->parent->debug) echo $value;

        return $value;
    }

    function is_exist($setting)
    {
        prepare_alphanumeric_data(array(&$setting));
        // Main Query
        $req = 'SELECT name';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'settings';
        $req .= ' WHERE name = \'' . $setting . '\'';

        $value = $this->parent->db->select_one($req, null);

        if ($this->parent->debug) echo $value;

        return $value;
    }

    function set($setting, $value = 'NULL', $date = 'NULL')
    {
        prepare_alphanumeric_data(array(&$setting, &$value, &$date));

        if ($date != 'NULL' && $date != 'NOW()') $date = '\'' . $date . '\'';
        if ($value != 'NULL' && !is_numeric($value)) $value = '\'' . $value . '\'';

        if ($this->is_exist($setting)) {
            $req = 'UPDATE ' . $this->parent->config['db_prefix'] . 'settings';
            $req .= ' SET date = ' . $date . ', value = ' . $value . '';
            $req .= ' WHERE name = \'' . $setting . '\'';
        } else {
            $req = 'INSERT INTO ' . $this->parent->config['db_prefix'] . 'settings (name,value,date)';
            $req .= ' VALUES (\'' . $setting . '\',' . $value . ',' . $date . ')';
        }

        $this->parent->db->exec_query($req);

        return;
    }

    function is_rank_to_update()
    {
        $req = 'SELECT 1';
        $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'settings';
        $req .= " WHERE name = 'LAST_GENERATE'";
        $req .= " AND DATE_FORMAT(date, '%m%e%H') <> DATE_FORMAT(NOW(), '%m%e%H')";
        $isLastGenerate = $this->parent->db->select_one($req, null);

        if ($isLastGenerate == 1) {
            $req = 'SELECT count(matchID) as nbMatchs';
            $req .= ' FROM ' . $this->parent->config['db_prefix'] . 'matches';
            $req .= " WHERE DATE_FORMAT(date, '%m%e') = DATE_FORMAT(NOW(), '%m%e')";
            $req .= ' AND scoreA IS NULL AND scoreB IS NULL';
            $nbMatchs = $this->parent->db->select_one($req, null);

            return ($nbMatchs === 0);
        } else {
            return false;
        }
    }
}
