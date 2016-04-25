<?php

require_once(BASE_PATH.'lib/curl.php');
require_once(BASE_PATH.'lib/functions.inc.php');
require_once('lib/betlib.php');

define('EMPTY_TEST', 0);
define('RANDOM_TEST', 1);
define('NORMAL_TEST', 2);

class TestEngine
{
	var $array_tests;
	var $debug;
	var $host;
	var $start;
	
	function TestEngine($host=false,$debug=false)
	{		
		$this->start = get_moment();	
		$this->array_tests = array();
		$this->debug = $debug;
		if(!$host) $this->host = $_SERVER['HTTP_HOST'];
		else $this->host = $host;
	}
	
	function echo_test_result($name,$test) {
		echo "".$name."     ";
		if($test) echo "<font color=green><b>OK</b></font><BR />";
		else echo "<font color=red><b>FAILED</b></font><BR />";
	}

	function execute_test_by_type($test,$type) {
			$content = "";
			$success = !$this->debug;
			try {
				$url = $this->host.$test['URL'];
				foreach($test['GET'] as $key => $val) {
					if($type == EMPTY_TEST) $val = "";
					if($type == RANDOM_TEST) $val = get_random();
					$url .= "&".$key."=".urlencode($val);
				}
				$http = new HTTPQuery($url);
				$post = " POST=";
				foreach($test['POST'] as $key => $val) {
					if($type == EMPTY_TEST) $val = "";
					if($type == RANDOM_TEST) $val = get_random();
					$post .= "&".$key."=".urlencode($val); 
					$http->addPostData($key, $val);
				}
				$content .=  "<br/>----------------------<br/><b><i>".$url." ".$post."</i></b><br/>----------------------<br/>";
				$http->setTimeout(10);
				$t = get_moment();
				$ret = $http->doRequest();
				$e = get_elapsed_time($t,get_moment());
				$l = (strlen($ret)==0)?"<font color='red'><b>0</b></font>":strlen($ret);
				//if($type == NORMAL_TEST) echo "GO ".$url." ".$post." : ".$l." ".$e."<br />";
				if(stristr($ret, "error") || stristr($ret, "warning") || stristr($ret, "notice")) {
					$content .= $ret;
					if(stristr($ret, "SQL Error") && ($type == EMPTY_TEST || $type == RANDOM_TEST)) $success = true;
					else $success = false;
				}
			} catch (Exception $e) {
				echo "ERROR:".$e->getMessage();
				die($e->getMessage());
			}
			if(!$success) echo $content;
			return $success;
	}

	function get_delay() {
		return get_elapsed_time($this->start,get_moment());
	}
	
	function execute() {
		foreach($this->array_tests as $test) {
			$rand = (isset($test['rand']))?$test['rand']:true;
			$empty = (isset($test['empty']))?$test['empty']:true;
			if($empty) $this->execute_test_by_type($test,EMPTY_TEST);
			if($rand) $this->execute_test_by_type($test,RANDOM_TEST);
			$this->execute_test_by_type($test,NORMAL_TEST);
		}
		$this->array_tests=array();
	}

	function login($login,$pass) {
		$this->array_tests[] = array('URL' => '/?act=login',
			'POST' => array(
				'login' => $login,
				'pass' => $pass,
			),
			'GET' => array(),
		);
	}
	
	function add_group($group_name) {
		$this->array_tests[] = array('URL' => '/?act=save_HTTP_group',
			'rand' => false,
			'POST' => array(),
			'GET' => array(
				'group_id' => '',
				'group_name' => $group_name,
			),
		);
	}

	function edit_group($group_id,$group_name) {
		$this->array_tests[] = array('URL' => '/?act=save_HTTP_group',
			'rand' => false,
			'POST' => array(),
			'GET' => array(
				'group_id' => $group_id,
				'group_name' => $group_name,
			),
		);
	}
	
	function add_user($login,$pass,$name,$email,$groupID,$status) {
		$this->array_tests[] = array('URL' => '/?act=add_user',
			'POST' => array(
				'login' => $login,
				'pass' => $pass,
				'name' => $name,
				'groupID' => $groupID,
				'email' => $email,
				'status' => $status
			), 
			'GET' => array(),
		);
	}
	
	function logout() {
		$this->array_tests[] = array('URL' => '/?act=logout',
			'POST' => array(),
			'GET' => array(),
		);
	}
	
	function add_HTTP_user($login,$pass,$name,$email,$groupID,$status) {
		$this->array_tests[] = array('URL' => '/?act=save_HTTP_user',
			'POST' => array(),
			'GET' => array(
				'login' => $login,
				'pass' => $pass,
				'name' => $name,
				'groupID' => $groupID,
				'email' => $email,
				'status' => $status
			),
		);
	}

	function change_password($old_password,$new_password1,$new_password2,$w=1) {
		$this->array_tests[] = array('URL' => '/?act=change_password',
			'POST' => array(
				'old_password' => $old_password,
				'new_password1' => $new_password1,
				'new_password2' => $new_password2,
			),
			'GET' => array(
				'w' => $w,
			),
		);
	}

	function forgot_password($login) {
		$this->array_tests[] = array('URL' => '/?act=forgot_password',
			'POST' => array(
				'login' =>$login,
			),
			'GET' => array(),
		);
	}

	function add_team($name,$pool) {
		$this->array_tests[] = array('URL' => '/?act=add_team',
			'POST' => array(
				'name' => $name,
				'pool' => $pool,
			),
			'GET' => array(),
		);
	}

	function edit_teams() {
		$this->array_tests[] = array('URL' => '/?act=edit_teams',
			'POST' => array(),
			'GET' => array(),
		);
	}

	function get_teams($pool) {
		$this->array_tests[] = array('URL' => '/?act=get_HTTP_teams',
			'POST' => array(),
			'GET' => array(
				'pool' => 'A',
			)
		);
	}
	
	function get_user($userID) {
		$this->array_tests[] = array('URL' => '/?act=get_HTTP_user',
			'POST' => array(),
			'GET' => array(
				'userID' => $userID,
			),
		);
	}
	
	function get_users() {
		$this->array_tests[] = array('URL' => '/?act=get_HTTP_users',
			'POST' => array(),
			'GET' => array(),
		);
	}
	
	function get_group($groupID) {
		$this->array_tests[] = array('URL' => '/?act=get_HTTP_group',
			'POST' => array(),
			'GET' => array(
				'groupID' => $groupID,
			),
		);
	}
	
	function get_groups() {
		$this->array_tests[] = array('URL' => '/?act=get_HTTP_groups',
			'POST' => array(),
			'GET' => array(),
		);
	}
	
	function edit_users() {
		$this->array_tests[] = array('URL' => '/?act=edit_users',
			'POST' => array(),
			'GET' => array(),
		);
	}
	
	function view_users() {
		$this->array_tests[] = array('URL' => '/?act=view_users',
		'POST' => array(),
		'GET' => array(),
		);
	}
	
	function add_match($month,$day,$hour,$minute,$teamA,$teamB,$round,$rank) {
		$this->array_tests[] = array('URL' => '/?act=add_match',
			'POST' => array(
				'month' => $month,
				'day' => $day,
				'hour' => $hour, 
				'minute' => $minute,
				'teamA' => $teamA,
				'teamB' => $teamB,
				'round' => $round,
				'rank' => $rank,
			),
			'GET' => array(),
		);
	}
	
	function view_matches() {
		$this->array_tests[] = array('URL' => '/?act=view_matches',
			'POST' => array(),
			'GET' => array(),
		);
	}
	
	function add_bet($userID,$matchID,$team,$score,$j=0) {
		$this->array_tests[] = array('URL' => '/?act=save_HTTP_bet',
			'POST' => array(),
			'GET' => array(
				'userID' => $userID,
				'matchID' => $matchID,
				'team' => $team,
				'score' => $score,
				'j' => $j,
			),
		);
	}
	
	function add_final_bet($userID,$matchID,$team,$score,$teamID,$teamW,$j=0) {
		$this->array_tests[] = array('URL' => '/?act=save_HTTP_final_bet',
			'POST' => array(),
			'GET' => array(
				'userID' => $userID,
				'matchID' => $matchID,
				'team' => $team,
				'score' => $score,
				'teamID' => $teamID,
				'teamW' => $teamW,
				'j' => '2',
			),
		);
	}
	
	function view_bets($userID) {
		$this->array_tests[] = array('URL' => '/?act=view_bets',
			'POST' => array(),
			'GET' => array(
				'userID' => $userID,
			),
		);
	}
	
	function view_finals_bets($userID) {
		$this->array_tests[] = array('URL' => '/?act=view_finals_bets',
			'POST' => array(),
			'GET' => array(
				'userID' => $userID,
			),
		);
	}
	
	function add_bets($bets) {
		$post = array();
		
		foreach($bets as $bet) {
			$post[$bet['matchID'].'_score_team_A'] = $bet['scoreA'];
			$post[$bet['matchID'].'_score_team_B'] = $bet['scoreB'];
		}
				
		$this->array_tests[] = array('URL' => '/?act=save_bets',
			'POST' => $post,
			'GET' => array(),
		);
	}
	
	function add_finals_bets($bets) {
		$post = array();
		
		foreach($bets as $bet) {
			$post[$bet['round'].'TH_'.$bet['rank'].'_TEAM_W'] = $bet['teamW'];
			$post[$bet['round'].'TH_'.$bet['rank'].'_MATCH_ID'] = $bet['matchID'];
			$post[$bet['round'].'TH_'.$bet['rank'].'_TEAM_A_ID'] = $bet['teamAID'];
			$post[$bet['round'].'TH_'.$bet['rank'].'_TEAM_A_SCORE'] = $bet['teamAscore'];
			$post[$bet['round'].'TH_'.$bet['rank'].'_TEAM_B_ID'] = $bet['teamBID'];
			$post[$bet['round'].'TH_'.$bet['rank'].'_TEAM_B_SCORE'] = $bet['teamBscore'];
		}

		$this->array_tests[] = array('URL' => '/?act=save_finals_bets',
			'POST' => $post,
			'GET' => array(),
		);
	}
	
	function add_result($matchID,$team,$score,$j=0) {
		$this->array_tests[] = array('URL' => '/?act=save_HTTP_result',
			'POST' => array(),
			'GET' => array(
				'matchID' => $matchID,
				'team' => $team,
				'score' => $score,
				'j' => $j,
			),
		);
	}
	
	function add_final_result($matchID,$team,$score,$teamID,$teamW,$j=0) {
		$this->array_tests[] = array('URL' => '/?act=save_HTTP_final_result',
		'POST' => array(),
		'GET' => array(
			'matchID' => $matchID,
			'team' => $team,
			'score' => $score,
			'teamID' => $teamID,
			'teamW' => $teamW,
			'j' => $j,
			),
		);
	}
	
	function update_ranking() {
		$this->array_tests[] = array('URL' => '/?act=update_HTTP_ranking',
		'POST' => array(),
		'GET' => array(),
		);
	}
	
	function add_tag($text,$groupID) {
		$this->array_tests[] = array('URL' => '/',
			'POST' => array(
				'act' => 'save_HTTP_tag',
				'text' => $text,
				'groupID' => $groupID,
			),
			'GET' => array(),
		);
	}
	
	function view_odds() {
		$this->array_tests[] = array('URL' => '/?act=view_odds', 'POST' => array(), 'GET' => array(),
		);
	}
	
	function view_finals_odds() {
		$this->array_tests[] = array('URL' => '/?act=view_finals_odds', 'POST' => array(), 'GET' => array(),
		);
	}
	
	function view_results() {
		$this->array_tests[] = array('URL' => '/?act=view_results', 'POST' => array(), 'GET' => array(),
		);
	}
	
	function edit_results() {
		$this->array_tests[] = array('URL' => '/?act=edit_results', 'POST' => array(), 'GET' => array(),
		);
	}
	
	function edit_finals_results() {
		$this->array_tests[] = array('URL' => '/?act=edit_finals_results', 'POST' => array(), 'GET' => array(),
		);
	}
	
	function view_teams() {
		$this->array_tests[] = array('URL' => '/?act=view_teams', 'POST' => array(), 'GET' => array(),
		);
	}
	
	function view_ranking() {
		$this->array_tests[] = array('URL' => '/?act=view_ranking', 'POST' => array(), 'GET' => array(),
		);
	}
	
	function view_ranking_teams() {
		$this->array_tests[] = array('URL' => '/?act=view_ranking_teams', 'POST' => array(), 'GET' => array(),
		);
	}
	
	function view_ranking_users_in_team() {
		$this->array_tests[] = array('URL' => '/?act=view_ranking_users_in_team', 'POST' => array(), 'GET' => array(),
		);
	}
	
	function rules() {
		$this->array_tests[] = array('URL' => '/?act=rules', 'POST' => array(), 'GET' => array(),
		);
	}
	
	function bets() {
		$this->array_tests[] = array('URL' => '/?act=bets', 'POST' => array(), 'GET' => array(),
		);
	}
	
	function finals_bets() {
		$this->array_tests[] = array('URL' => '/?act=finals_bets', 'POST' => array(), 'GET' => array(),
		);
	}
	
	function get_tags() {
		$this->array_tests[] = array('URL' => '/?act=get_HTTP_tags', 'POST' => array(), 'GET' => array(),
		);
	}
	
	function check_bets() {
		$this->array_tests[] = array(
			'URL' => '/?act=check_bets',
			'POST' => array(),
			'GET' => array(),
		);
	}
	
	function account() {
		$this->array_tests[] = array(
			'URL' => '/?act=account',
			'POST' => array(
			),
			'GET' => array(
			),
		);	
	}
	
	function join_group($groupID,$pass,$code) {
		$this->array_tests[] = array(
			'URL' => '/?act=join_group',
			'POST' => array(
				'group' => $groupID,
				'password' => $pass,
				'code' => $code,
			),
			'GET' => array(
			),
		);	
	}
	
	function leave_group($groupID) {
		$this->array_tests[] = array(
			'URL' => '/?act=leave_group',
			'POST' => array(
			),
			'GET' => array(
				'groupID' => $groupID,
			),
		);	
	}
	
	function create_group($group_name,$pass1,$pass2) {
		$this->array_tests[] = array(
			'URL' => '/?act=create_group',
			'POST' => array(
				'group_name' => $group_name,
				'password1' => $pass1,
				'password2' => $pass2
			),
			'GET' => array(
			),
		);	
	}

	function invite_friends($email1,$groupID1,$email2=false,$groupID2=false,$email3=false,$groupID3=false,$email4=false,$groupID4=false,$email5=false,$groupID5=false,$email6=false,$groupID6=false,$email7=false,$groupID7=false,$email8=false,$groupID8=false,$email9=false,$groupID9=false,$email10=false,$groupID10=false)
	{
		$this->array_tests[] = array(
			'URL' => '/?act=invite_friends',
			'POST' => array(
				'email1' => $email1,
				'email2' => $email2,
				'email3' => $email3,
				'email4' => $email4,
				'email5' => $email5,
				'email6' => $email6,
				'email7' => $email7,
				'email8' => $email8,
				'email9' => $email9,
				'email10' => $email10,
				'groupID1' => $groupID1,
				'groupID2' => $groupID2,
				'groupID3' => $groupID3,
				'groupID4' => $groupID4,
				'groupID5' => $groupID5,
				'groupID6' => $groupID6,
				'groupID7' => $groupID7,
				'groupID8' => $groupID8,
				'groupID9' => $groupID9,
				'groupID10' => $groupID10,
			),
			'GET' => array(
			),
		);
	}	
	
	function del_user($userID) {
		$this->array_tests[] = array('URL' => '/?act=del_HTTP_user',
			'POST' => array(),
			'GET' => array(
				'userID' => $userID,
			),
		);
	}
	
	function del_tag($tagID) {
		$this->array_tests[] = array('URL' => '/?act=del_HTTP_tag',
			'POST' => array(
				'tagID' => $tagID,
			),
			'GET' => array(),
		);
	}
}
