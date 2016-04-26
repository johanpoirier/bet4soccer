<?php

class Groups
{
	var $parent;

	public function __construct(&$parent)
	{
		$this->parent = $parent;
	}
		
	function add($group_id,$group_name,$password="")
	{
		$group_name = trim($group_name);
		if($group_name == null || $group_name == "") return false;
		$ownerID = $this->parent->users->get_current_id();
		prepare_alphanumeric_data(array(&$group_name,&$password));
		prepare_numeric_data(array(&$group_id));
		
		if($group_id = $this->is_exist($group_id)) {
			$req = 'UPDATE '.$this->parent->config['db_prefix'].'groups ';
			$req .= ' SET name = \''.$group_name.'\', password = \''.$group_name.'\'';
			$req .= ' WHERE groupID = '.$group_id.'';
			return $this->parent->db->exec_query($req);
		} else {
			if($this->get_by_name($group_name)) return false;
			$req = 'INSERT INTO '.$this->parent->config['db_prefix'].'groups (name,password,"ownerID")';
			$req .= ' VALUES (\''.$group_name.'\', \''.$password.'\', '.$ownerID.')';
			return $this->parent->db->insert($req);
		}
		return $group_id;
	}

	function delete($groupID)
	{
		prepare_numeric_data(array(&$groupID));
		$req = 'DELETE';
		$req .= ' FROM '.$this->parent->config['db_prefix'].'groups';
		$req .= ' WHERE "groupID"='.$groupID.'';
		$this->parent->db->exec_query($req);
		return;
	}
	
	function get_HTTP()
	{	
		// 0 arg = get all groups
		// 1 arg = get one group
		
		$nb_args = func_num_args();	
		$args = func_get_args(); 	
		$groupID = null;
		
		if($nb_args > 0) {
			$groupID = $args[0];
			if(($groupID == "") || ($groupID == NULL)) $groupID = 'NULL'; 
		}
		// Main Query
		$req = 'SELECT g.*, count("userID") as nb_users';
		$req .= ' FROM '.$this->parent->config['db_prefix'].'groups AS g';
		$req .= ' LEFT JOIN '.$this->parent->config['db_prefix'].'users AS u ON ((g."groupID" = u."groupID") OR (g."groupID" = u."groupID3") OR (g."groupID" = u."groupID2"))';
		if($nb_args > 0) $req .= ' WHERE g."groupID" = '.$groupID;
		$req .= ' GROUP BY g."groupID"';
		$req .= ' ORDER BY name ASC';
				
		// Execute Query			
		$groups = $this->parent->db->select_array($req,$nb_groups);
		if($this->parent->debug) array_show($groups);
		
		// Return results
		if($nb_args > 0 && $nb_groups > 0) {
			$group = $groups[0];
			echo $group['groupID']."|".$group['name']."|".$group['nb_users'];
			return $group;
		} else {
			foreach($groups as $group) {
				echo $group['groupID'].";".$group['name'].";".$group['nb_users']."|";
			}
			return $groups;
		}	
	}
	
	function get()
	{	
		// 0 arg = get all groups
		// 1 arg = get one group
		
		$nb_args = func_num_args();	
		$args = func_get_args(); 	
		$groupID = null;
		
		if($nb_args > 0) {
			$groupID = $args[0];
			prepare_numeric_data(array(&$groupID)); 
		}
		
		// Main Query
		$req = 'SELECT *';
		$req .= ' FROM '.$this->parent->config['db_prefix'].'groups';
		if($nb_args > 0) $req .= ' WHERE groupID = '.$groupID.'';
		$req .= ' ORDER BY name ASC';
					
		// Execute Query			
		$groups = $this->parent->db->select_array($req,$nb_groups);
		if($this->parent->debug) array_show($groups);
		
		// Return results
		if($nb_args > 0 && $nb_groups > 0) return $groups[0];
		else return $groups;
	}

	function get_by_name($groupName)
	{
		// Main Query
		$req = "SELECT *";
		$req .= " FROM ".$this->parent->config['db_prefix']."groups";
		$req .= " WHERE name = '".addslashes($groupName)."'";
		$group = $this->parent->db->select_line($req,$null);
		if($this->parent->debug) array_show($group);

		return $group;
	}
	
	function get_with_users()
	{
		// Main Query
		$req = 'SELECT g.groupID, g.name , count(userID) as nb_users';
		$req .= ' FROM '.$this->parent->config['db_prefix'].'groups AS g';
		$req .= ' LEFT JOIN '.$this->parent->config['db_prefix'].'users AS u ON ((g.groupID = u.groupID) OR (g.groupID = u.groupID3) OR (g.groupID = u.groupID2))';
		$req .= ' GROUP BY g.groupID,g.name';
		$req .= ' ORDER BY g.name ASC';
						
		// Execute Query			
		$groups = $this->parent->db->select_array($req, $nb_groups);
		if($this->parent->debug) array_show($groups);
		
		// Return results
		else return $groups;
	}

	function set_last_rank($groupID,$last_rank)
	{
        prepare_numeric_data(array(&$last_rank,&$groupID));
        $group = $this->get($groupID);
        if(!$group) return false;
        
        $req = 'UPDATE '.$this->parent->config['db_prefix'].'groups';
        $req .= ' SET lastRank = '.$last_rank;
        $req .= ' WHERE groupID = '.$groupID;
        
        return $this->parent->db->exec_query($req);
	}

	function count()
	{
		// Main Query
		$req = 'SELECT COUNT(groupID)';
		$req .= ' FROM '.$this->parent->config['db_prefix'].'groups';
		
		$nb_group = $this->parent->db->select_one($req);

		if($this->parent->debug) array_show($nb_group);
		
		return $nb_group;
	}

	function count_active()
	{
		// Main Query
        $req = 'SELECT COUNT(groupID)';
		$req .= ' FROM '.$this->parent->config['db_prefix'].'groups';
		$req .= ' WHERE groupID IN (';
		$req .= ' SELECT u.groupID';
		$req .= ' FROM '.$this->parent->config['db_prefix'].'users u';
		$req .= ' LEFT JOIN '.$this->parent->config['db_prefix'].'bets b ON (u.userID = b.userID AND b.scoreA IS NOT NULL AND b.scoreB IS NOT NULL)';
		$req .= ' GROUP BY u.userID, u.groupID';
        $req .= ' HAVING COUNT(b.matchID) > 0';
        $req .= ' UNION';
		$req .= ' SELECT u.groupID2';
		$req .= ' FROM '.$this->parent->config['db_prefix'].'users u';
		$req .= ' LEFT JOIN '.$this->parent->config['db_prefix'].'bets b ON (u.userID = b.userID AND b.scoreA IS NOT NULL AND b.scoreB IS NOT NULL)';
		$req .= ' GROUP BY u.userID, u.groupID2';
        $req .= ' HAVING COUNT(b.matchID) > 0';
        $req .= ' UNION';
		$req .= ' SELECT u.groupID3';
		$req .= ' FROM '.$this->parent->config['db_prefix'].'users u';
		$req .= ' LEFT JOIN '.$this->parent->config['db_prefix'].'bets b ON (u.userID = b.userID AND b.scoreA IS NOT NULL AND b.scoreB IS NOT NULL)';
		$req .= ' GROUP BY u.userID, u.groupID3';
        $req .= ' HAVING COUNT(b.matchID) > 0';
        $req .= ')';

		$nb_active_group = $this->parent->db->select_one($req);

		if($this->parent->debug) array_show($nb_active_group);
		
		return $nb_active_group;
	}
	
	function is_authorized($groupID, $password)
	{
		$group = $this->get($groupID);
		return ($group['password'] == $password);
	}
	
	function is_invited_by_code($code)
	{
		// Main Query
		$req = 'SELECT *';
		$req .= ' FROM '.$this->parent->config['db_prefix'].'invitations';
		$req .= ' WHERE code = \''.addslashes($code).'\'';
		$req .= ' AND expiration >= NOW()';
		$req .= ' AND status > 0';
		$invitation = $this->parent->db->select_line($req,$null);
		if($this->parent->debug) array_show($invitation);
		return $invitation;
	}
	
	function get_invitation($code)
	{
		prepare_alphanumeric_data(array(&$code));
		// Main Query
		$req = 'SELECT i.*,(expiration < NOW()) as expired,g.name as group_name';
		$req .= ' FROM '.$this->parent->config['db_prefix'].'invitations i';
		$req .= ' LEFT JOIN '.$this->parent->config['db_prefix'].'groups g ON (i.groupID = g.groupID)';
		$req .= ' WHERE code = \''.$code.'\'';
		$invitation = $this->parent->db->select_line($req,$null);
		if($this->parent->debug) array_show($invitation);
		return $invitation;
	}
	
	function get_invitations_by_sender($senderID)
	{
		prepare_numeric_data(array(&$senderID));
		// Main Query
		$req = 'SELECT i.*,(expiration < NOW()) as expired,g.name as group_name';
		$req .= ' FROM '.$this->parent->config['db_prefix'].'invitations i';
		$req .= ' LEFT JOIN '.$this->parent->config['db_prefix'].'groups g ON (i.groupID = g.groupID)';
		$req .= ' WHERE senderID = '.$senderID.'';
		$invitations = $this->parent->db->select_array($req,$null);
		if($this->parent->debug) array_show($invitations);
		return $invitations;
	}
	
	function is_invited_by_email($email)
	{
		// Main Query
		$req = 'SELECT *';
		$req .= ' FROM '.$this->parent->config['db_prefix'].'invitations';
		$req .= ' WHERE email = \''.addslashes($email).'\'';
		$req .= ' AND expiration >= NOW()';
		$req .= ' AND status > 0';
		$invitation = $this->parent->db->select_line($req,$null);
		if($this->parent->debug) array_show($invitation);
		return $invitation;
	}
	
	function delete_invitation($code)
	{
		prepare_alphanumeric_data(array(&$code));
		$invitation = $this->get_invitation($code);
		if($invitation['status'] < 0) return true;
		$req = 'UPDATE '.$this->parent->config['db_prefix'].'invitations';
		$req .= ' SET status = -'.$invitation['status'].'';
		$req .= ' WHERE code = \''.$code.'\';';
		$this->parent->db->exec_query($req);
		return true;
	}
	
	
	function use_invitation($code)
	{
		$invitation = $this->is_invited_by_code($code);
		if($invitation) {
			$this->delete_invitation($code);
			return $invitation['groupID'];
		} else return false;
	}
	
	function create_uniq_invitation($email,$groupID,$type)
	{
		$code = md5(uniqid(rand(), true));
		$user = $this->parent->users->get_current();
		if($this->parent->users->is_in_group($groupID,$user['userID']) < 1) return false;
		// Main Query
		$req = 'INSERT INTO '.$this->parent->config['db_prefix'].'invitations (code,"senderID","groupID",email,expiration,status)';
		$req .= ' VALUES (\''.addslashes($code).'\', \''.$user['userID'].'\',\''.$groupID.'\', \''.addslashes($email).'\',';
		if($this->parent->config['DB'] == "PgSQL") {
			$req .= '(NOW() + INTERVAL \''.$this->parent->config['invitation_expiration'].' DAY\')';
		} else {
			$req .= 'DATE_ADD(NOW(), INTERVAL '.$this->parent->config['invitation_expiration'].' DAY)';
		}	
		if($type == 'IN') $req .= ',2)';
		else $req .= ',1)';
		$ret = $this->parent->db->insert($req);
		return $code;
	}
	
	function create_uniq_invitations($invitations,$type)
	{
		$codes = array();
		foreach($invitations as $invitation) {
			if($invitation['groupID'] == 0) continue;
			if($code = $this->create_uniq_invitation($invitation['email'],$invitation['groupID'],$type)) {
				$codes[$invitation['email']]['code'] = $code;
				$codes[$invitation['email']]['groupID'] = $invitation['groupID'];
			}
		}
		return $codes;
	}
	
	function is_invited($groupID,$userID=false)
	{
		if($userID) $user = $this->parent->users->get($userID);
		else $user = $this->parent->users->get_current();
		$email = $user['email'];

		// Main Query
		$req = 'SELECT *';
		$req .= ' FROM '.$this->parent->config['db_prefix'].'invitations';
		$req .= ' WHERE email = \''.addslashes($email).'\'';
		$req .= ' AND "groupID" = '.$groupID.'';
		$req .= ' AND expiration >= NOW()';
		$req .= ' AND status > 0';
		$invitation = $this->parent->db->select_line($req,$null);
		if($this->parent->debug) array_show($group);
		return $invitation;
	}

	function get_name_by_user($userID,$order="")
	{
		prepare_numeric_data(array(&$userID));
		// Main Query
		$req = 'SELECT t.name';
		$req .= ' FROM '.$this->parent->config['db_prefix'].'users u';
		$req .= ' LEFT JOIN '.$this->parent->config['db_prefix'].'groups t ON (u.groupID'.$order.' = t.groupID)';
		$req .= ' WHERE u.userID = '.$userID.'';

		$group_name = $this->parent->db->select_one($req);
		if($this->parent->debug) echo $group_name;

		return $group_name;
	}	
	
	function is_exist($group_id)
	{
		if(!$group_id || ($group_id == "")) return false;
		// Main Query
		$req = 'SELECT groupID';
		$req .= ' FROM '.$this->parent->config['db_prefix'].'groups ';
		$req .= ' WHERE groupID = '.$group_id.'';
		
		return $this->parent->db->select_one($req,null);

   }
  
	function update_HTTP_ranking($update_rank=false) {
		$cur_user = $this->parent->users->get_current_id();
		$is_generating = $this->parent->settings->get_value('IS_GROUP_RANKING_GENERATING');
		if(($is_generating > 0) && ($is_generating != $cur_user)) {
			echo "IN_PROGRESS";
			return false;
		} else $this->parent->settings->set('IS_GROUP_RANKING_GENERATING',$cur_user,'NULL');
		$groups = $this->get();
        $groupsView = array();
        $nbMatchesPlayed = $this->parent->matches->count_played();
		if($nbMatchesPlayed == 0) return false;

        foreach($groups as $group) {
            $group['avgPoints'] = 0;
            $group['maxPoints'] = 0;
            $group['totalPoints'] = 0;

            $users = $this->parent->users->get_by_group($group['groupID']);
            $nbUsersActifs = 0;
            foreach($users as $user) {
                if(($this->parent->bets->count_played_by_user($user['userID']) / $nbMatchesPlayed) > $this->parent->config['min_ratio_played_matches_for_group']) {
                    $group['totalPoints'] += $user['points'];
                    if($user['points'] > $group['maxPoints']) {
                        $group['maxPoints'] = $user['points'];
                    }
                    $nbUsersActifs++;
                }
            }
            if($nbUsersActifs > 0) {
                $group['avgPoints'] = round($group['totalPoints'] / $nbUsersActifs,2);
            }
            $groupsView[] = $group;
        }

        // MaJ BDD
        usort($groupsView, "compare_groups");
        for($i=0; $i < sizeof($groupsView); $i++) {
            $group = $groupsView[$i];
            $group['rank'] = ($i + 1);

            if($update_rank) {
				$req = 'UPDATE '.$this->parent->config['db_prefix'].'groups';
				$req .= ' SET avgPoints = '.$group['avgPoints'].', totalPoints = '.$group['totalPoints'].', maxPoints = '.$group['maxPoints'].', lastRank = '.$group['rank'].'';
				$req .= ' WHERE groupID = '.$group['groupID'].';';
                $this->parent->db->exec_query($req);
            }
            else {
				$req = 'UPDATE '.$this->parent->config['db_prefix'].'groups';
				$req .= ' SET avgPoints = '.$group['avgPoints'].', totalPoints = '.$group['totalPoints'].', maxPoints = '.$group['maxPoints'].'';
				$req .= ' WHERE groupID = '.$group['groupID'].';';
                $this->parent->db->exec_query($req);
            }
        }
        if($update_rank) $this->parent->settings->set("RANK_GROUPS_UPDATE", "NULL", "NOW()");
		$this->parent->settings->set('IS_GROUP_RANKING_GENERATING',0,'NULL');
        echo "OK";
        return true; 
	}
}
