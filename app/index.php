<?php

error_reporting(E_ALL);
session_start();

define('WEB_PATH', '/');
define('BASE_PATH', dirname(realpath(__FILE__)) . '/');
define('URL_PATH', '/');
require('lib/betlib.php');
$debug = false;
$bet = new BetEngine(false, $debug);
$w = '';

// keep me logged in
if (!$bet->islogin()) {
    $bet->remember_me();
}

define('EDIT_USERS', (($bet->isadmin()) && isset($_GET['act']) && ($_GET['act']) == "edit_users"));
define('ADD_USER', (($bet->isadmin()) && isset($_GET['act']) && ($_GET['act']) == "add_user"));
define('GET_HTTP_USER', (($bet->isadmin()) && isset($_GET['act']) && ($_GET['act']) == "get_HTTP_user"));
define('DEL_HTTP_USER', (($bet->isadmin()) && isset($_GET['act']) && ($_GET['act']) == "del_HTTP_user"));
define('GET_HTTP_USERS', (($bet->isadmin()) && isset($_GET['act']) && ($_GET['act']) == "get_HTTP_users"));
define('SAVE_HTTP_USER', (($bet->isadmin()) && isset($_GET['act']) && ($_GET['act']) == "save_HTTP_user"));
define('GET_HTTP_GROUP', (($bet->isadmin()) && isset($_GET['act']) && ($_GET['act']) == "get_HTTP_group"));
define('DEL_HTTP_GROUP', (($bet->isadmin()) && isset($_GET['act']) && ($_GET['act']) == "del_HTTP_group"));
define('GET_HTTP_GROUPS', (($bet->isadmin()) && isset($_GET['act']) && ($_GET['act']) == "get_HTTP_groups"));
define('SAVE_HTTP_GROUP', (($bet->isadmin()) && isset($_GET['act']) && ($_GET['act']) == "save_HTTP_group"));
define('SAVE_HTTP_RESULT', (($bet->isadmin()) && isset($_POST['act']) && ($_POST['act']) == "save_HTTP_result"));
define('SAVE_HTTP_FINAL_RESULT', (($bet->isadmin()) && isset($_POST['act']) && ($_POST['act']) == "save_HTTP_final_result"));
define('EDIT_TEAMS', (($bet->isadmin()) && isset($_GET['act']) && ($_GET['act']) == "edit_teams"));
define('ADD_TEAM', (($bet->isadmin()) && isset($_GET['act']) && ($_GET['act']) == "add_team"));
define('DEL_TEAM', (($bet->isadmin()) && isset($_GET['act']) && ($_GET['act']) == "del_team"));
define('EDIT_MATCHES', (($bet->isadmin()) && isset($_GET['act']) && ($_GET['act']) == "edit_matches"));
define('GET_HTTP_MATCH', (isset($_GET['act']) && ($_GET['act']) == "get_HTTP_match"));
define('UPDATE_HTTP_RANKING', (($bet->isadmin()) && isset($_GET['act']) && ($_GET['act']) == "update_HTTP_ranking"));
define('UPDATE_HTTP_STATS', (($bet->isadmin()) && isset($_GET['act']) && ($_GET['act']) == "update_HTTP_stats"));
define('ADD_MATCH', (($bet->isadmin()) && isset($_GET['act']) && ($_GET['act']) == "add_match"));
define('IMPORT_CSV_FILE', (($bet->isadmin()) && isset($_GET['act']) && ($_GET['act']) == "import_csv_file"));
define('GENERATE_STATS', (($bet->isadmin()) && isset($_GET['act']) && ($_GET['act']) == "gen_stats"));
define('SET_SETTING', (($bet->isadmin()) && isset($_GET['act']) && ($_GET['act']) == "set_setting"));

define('VIEW_USERS', (isset($_GET['act']) && ($_GET['act']) == "view_users"));
define('VIEW_MATCHES', (isset($_GET['act']) && ($_GET['act']) == "view_matches"));
define('VIEW_ODDS', (isset($_GET['act']) && ($_GET['act']) == "view_odds"));
define('VIEW_FINALS_ODDS', (isset($_GET['act']) && ($_GET['act']) == "view_finals_odds"));
define('VIEW_RESULTS', (isset($_GET['act']) && ($_GET['act']) == "view_results"));
define('EDIT_RESULTS', (isset($_GET['act']) && ($_GET['act']) == "edit_results") && ($bet->isadmin()));
define('EDIT_FINALS_RESULTS', (isset($_GET['act']) && ($_GET['act']) == "edit_finals_results") && ($bet->isadmin()));
define('VIEW_TEAMS', (isset($_GET['act']) && ($_GET['act']) == "view_teams"));
define('VIEW_USERS_RANKING', (isset($_GET['act']) && ($_GET['act']) == "view_users_ranking"));
define('VIEW_USERS_VISUAL_RANKING', (isset($_GET['act']) && ($_GET['act']) == "view_users_visual_ranking"));
define('VIEW_GROUPS_RANKING', (isset($_GET['act']) && ($_GET['act']) == "view_groups_ranking"));
define('VIEW_USERS_RANKING_BY_GROUP', (isset($_GET['act']) && ($_GET['act']) == "view_users_ranking_by_group"));
define('VIEW_STATS', (isset($_GET['act']) && ($_GET['act']) == "view_stats"));
define('VIEW_MATCH_STATS', (isset($_GET['act']) && ($_GET['act']) == "view_match_stats"));
define('RULES', (isset($_GET['act']) && ($_GET['act']) == "rules"));

define('EDIT_BETS', (isset($_GET['act']) && ($_GET['act']) == "bets"));
define('EDIT_FINALS_BETS', (isset($_GET['act']) && ($_GET['act']) == "finals_bets"));
define('SAVE_BETS', (isset($_GET['act']) && ($_GET['act']) == "save_bets"));
define('SAVE_FINALS_BETS', (isset($_GET['act']) && ($_GET['act']) == "save_finals_bets"));

define('VIEW_BETS_OF_USER', (isset($_GET['act']) && ($_GET['act']) == "view_bets" && isset($_GET['user'])));
define('VIEW_FINALS_BETS_OF_USER', (isset($_GET['act']) && ($_GET['act']) == "view_finals_bets" && isset($_GET['user'])));

define('GET_HTTP_TEAMS', (isset($_GET['act']) && ($_GET['act']) == "get_HTTP_teams"));
define('SAVE_HTTP_BET', (isset($_POST['act']) && ($_POST['act']) == "save_HTTP_bet"));
define('SAVE_HTTP_FINAL_BET', (isset($_POST['act']) && ($_POST['act']) == "save_HTTP_final_bet"));

define('SAVE_HTTP_TAG', (isset($_POST['act']) && ($_POST['act']) == "save_HTTP_tag"));
define('DEL_HTTP_TAG', (isset($_POST['act']) && ($_POST['act']) == "del_HTTP_tag"));
define('GET_HTTP_TAGS', (isset($_GET['act']) && ($_GET['act']) == "get_HTTP_tags"));

define('REGISTER', (isset($_GET['act']) && ($_GET['act']) == "register"));
define('ACCOUNT', (isset($_GET['act']) && ($_GET['act']) == "account"));
define('LOGIN', (isset($_GET['act']) && ($_GET['act']) == "login"));
define('LOGOUT', (isset($_GET['act']) && ($_GET['act']) == "logout"));
define('CHECK_BETS', (isset($_GET['act']) && ($_GET['act']) == "check_bets"));
define('FORGOT_PASSWORD', (isset($_GET['act']) && ($_GET['act']) == "forgot_password"));
define('FORGOT_LOGIN', (isset($_GET['act']) && ($_GET['act']) == "forgot_login"));

define('CHANGE_PASSWORD', (isset($_GET['act']) && ($_GET['act']) == "change_password"));
define('CHANGE_ACCOUNT', (isset($_GET['act']) && ($_GET['act']) == "change_account"));
define('CHANGE_PREFERENCES', (isset($_GET['act']) && ($_GET['act']) == "change_preferences"));
define('JOIN_GROUP', (isset($_GET['act']) && ($_GET['act']) == "join_group"));
define('LEAVE_GROUP', (isset($_GET['act']) && ($_GET['act']) == "leave_group"));
define('CREATE_GROUP', (isset($_GET['act']) && ($_GET['act']) == "create_group"));
define('INVITE_FRIENDS', (isset($_GET['act']) && ($_GET['act']) == "invite_friends"));

define('VIEW_AUDIT', isset($_GET['act']) && ($_GET['act'] == "audit") && $bet->isadmin());
define('ADMIN', isset($_GET['act']) && ($_GET['act'] == "admin") && $bet->isadmin());
define('RESET', isset($_GET['act']) && ($_GET['act'] == "reset") && $bet->isadmin());

define('CODE', (isset($_GET['c']) && !isset($_GET['act'])));

define('AUTHENTIFICATION', (!isset($_SESSION['userID']) && !LOGIN && !CODE));

if (FORGOT_PASSWORD) {
    if ($debug)
        echo "FORGOT_PASSWORD<br />";
    if (isset($_POST['login']) && $_POST['login']) {
        if ($bet->send_password($_POST['login'])) {
            redirect("/?w=" . FORGOT_PASSWORD_OK);
        } else {
            redirect("/?w=" . USER_UNKNOWN);
        }
    } else {
        $bet->load_header();
        $bet->load_header_menu();
        $bet->load_forgot_password();
        $bet->load_tail();
        $bet->display();
        exit();
    }
}

if (FORGOT_LOGIN) {
    if ($debug)
        echo "FORGOT_PASSWORD<br />";
    if (isset($_POST['email']) && $_POST['email']) {
        if ($bet->send_login($_POST['email'])) {
            redirect("/?w=" . FORGOT_LOGIN_OK);
        } else {
            redirect("/?w=" . USER_UNKNOWN);
        }
    } else {
        $bet->load_header();
        $bet->load_header_menu();
        $bet->load_forgot_login();
        $bet->load_tail();
        $bet->display();
        exit();
    }
}

if (CODE) {
    if ($debug) {
        echo "REGISTER<br />";
    }
    if (isset($_SESSION['userID'])) {
        redirect("/?act=join_group&c=" . $_GET['c']);
    } else {
        $invitation = $bet->groups->get_invitation($_GET['c']);
        if ($invitation['status'] == 1) {
            redirect("/?act=register&c=" . $_GET['c']);
        } elseif ($invitation['status'] == 2) {
            redirect("/?s=" . $_GET['c']);
        } else {
            redirect("/?act=register");
        }
    }
}
if (REGISTER) {
    if ($debug) {
        echo "REGISTER<br />";
    }

    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['login'])) {
        if ($_POST['password1'] != $_POST['password2']) {
            redirect("?act=register&c=" . $_POST['code'] . "&w=" . PASSWORD_MISMATCH);
        }
        $groupID = $bet->groups->use_invitation($_POST['code']);
        $status = $bet->users->add($_POST['login'], $_POST['password1'], $_POST['name'], $_POST['firstname'], $_POST['email'], $groupID, 0);
        if ($status < 0) {
            redirect("?act=register&c=" . $_POST['code'] . "&w=" . $status);
        }
        else {
            $bet->audit->add($status, 'register', 'a créé son compte');
            redirect("/?w=" . REGISTER_OK);
        }
    }
    if (isset($_GET['w']) && $_GET['w']) {
        $w = $bet->lang['warning'][$_GET['w']];
    }
    if (isset($_GET['c'])) {
        $code = $_GET['c'];
    }
    else {
        $code = false;
    }
    $bet->load_header();
    $bet->load_header_menu();
    $bet->load_register($w, $code);
    $bet->load_tail();
    $bet->display();
    exit();
}

if (AUTHENTIFICATION) {
    if ($debug)
        echo "AUTHENTIFICATION<br />";
    $bet->load_header();
    $bet->load_header_menu();
    if (isset($_GET['w']) && isset($bet->lang['warning'][$_GET['w']]))
        $w = $bet->lang['warning'][$_GET['w']];
    $bet->load_authentification($w, (isset($_GET['s'])) ? $_GET['s'] : false);
    $bet->load_tail();
    $bet->display();
    exit();
}

if (CHECK_BETS) {
    if ($debug)
        echo "CHECK_BETS<br />";
    $bet->check_bets();
    exit();
}

if (LOGIN) {
    if ($debug) {
        echo "LOGIN<br />";
    }
    $ret = $bet->login($_POST['login'], $_POST['pass'], isset($_POST['keep']), $_POST['uuid']);
    $code = (isset($_POST['code']) && ($_POST['code'] != "")) ? "?c=" . $_POST['code'] : "";
    $and_code = (isset($_POST['code']) && ($_POST['code'] != "")) ? "&s=" . $_POST['code'] : "";
    if ($ret > 0) {
        redirect("/" . $code);
    } else {
        redirect("/?w=" . $ret . $and_code);
    }
}

if (LOGOUT) {
    if ($debug) {
        echo "LOGOUT<br />";
    }
    $bet->audit->add($_SESSION['userID'], 'auth', 's\'est déconnecté');
    session_destroy();
    setcookie('device', '', time() - 7200);
    setcookie('rememberme', '', time() - 7200);
    redirect('/');
} elseif (ADD_TEAM) {
    if ($debug)
        echo "ADD_TEAM<br />";
    $bet->teams->add($_POST['name'], $_POST['pool'], $_POST['fifaRank']);
    redirect("/?act=edit_teams");
} elseif (ADD_MATCH) {
    if ($debug)
        echo "ADD_MATCH<br />";
    $bet->matches->add(date('Y') . "-" . $_POST['month'] . "-" . $_POST['day'] . " " . $_POST['hour'] . ":" . $_POST['minute'] . ":00", $_POST['teamA'], $_POST['teamB'], $_POST['round'], $_POST['rank']);
    redirect("/?act=edit_matches&month=" . $_POST['month'] . "&day=" . $_POST['day'] . "&pool=" . $_POST['pool']);
} elseif (ADD_USER) {
    if ($debug)
        echo "ADD_USER<br />";
    $bet->users->add($_POST['login'], $_POST['pass'], $_POST['name'], $_POST['email'], $_POST['groupID'], $_POST['status']);
    redirect("/?act=edit_users");
}

if (GET_HTTP_TEAMS) {
    if ($debug)
        echo "GET_HTTP_TEAMS<br />";
    $bet->teams->get_HTTP_by_pool($_GET['pool']);
    exit();
}
if (GET_HTTP_MATCH) {
    if ($debug)
        echo "GET_HTTP_MATCH<br />";
    $bet->matches->get_HTTP($_GET['matchID'], $_GET['isRounds']);
    exit();
} elseif (DEL_HTTP_USER) {
    if ($debug)
        echo "DEL_HTTP_USER<br />";
    $bet->users->delete($_GET['userID']);
    exit();
} elseif (DEL_TEAM) {
    if ($debug)
        echo "DEL_TEAM<br />";
    $bet->teams->delete($_GET['teamID']);
    exit();
} elseif (UPDATE_HTTP_RANKING) {
    if ($debug) {
        echo "UPDATE_HTTP_RANKING<br />";
    }
    $rank_to_update = $bet->settings->is_rank_to_update();
    if (!$bet->users->update_HTTP_ranking($rank_to_update)) {
        exit();
    }
    if (!$bet->groups->update_HTTP_ranking($rank_to_update)) {
        exit();
    }
    $bet->settings->set("LAST_GENERATE", "NULL", "NOW()");
    exit();
} elseif (UPDATE_HTTP_STATS) {
    if ($debug)
        echo "UPDATE_HTTP_STATS<br />";
    $bet->stats->generate_user_stats();
    exit();
} elseif (IMPORT_CSV_FILE) {
    if ($debug)
        echo "IMPORT_CSV_FILE<br />";
    $bet->import_csv_file();
    redirect("/?act=edit_users");
} elseif (GET_HTTP_USER) {
    if ($debug)
        echo "GET_HTTP_USER<br />";
    $bet->users->get_HTTP($_GET['userID']);
    exit();
} elseif (GET_HTTP_USERS) {
    if ($debug)
        echo "GET_HTTP_USERS<br />";
    $bet->users->get_HTTP();
    exit();
} elseif (SAVE_HTTP_USER) {
    if ($debug)
        echo "SAVE_HTTP_USER<br />";
    $bet->users->add_or_update($_GET['login'], $_GET['pass'], $_GET['name'], $_GET['email'], $_GET['groupID'], $_GET['status']);
    exit();
} elseif (GET_HTTP_GROUP) {
    if ($debug)
        echo "GET_HTTP_GROUP<br />";
    $bet->groups->get_HTTP($_GET['groupID']);
    exit();
} elseif (GET_HTTP_GROUPS) {
    if ($debug)
        echo "GET_HTTP_GROUPS<br />";
    $bet->groups->get_HTTP();
    exit();
} elseif (SAVE_HTTP_GROUP) {
    if ($debug) {
        echo "SAVE_HTTP_GROUP<br />";
    }
    echo $bet->groups->add($_GET['group_id'], $_GET['group_name']);
    exit();
} elseif (DEL_HTTP_GROUP) {
    if ($debug)
        echo "DEL_HTTP_GROUP<br />";
    $bet->groups->delete($_GET['groupID']);
    exit();
} elseif (SAVE_HTTP_FINAL_RESULT) {
    if ($debug)
        echo "SAVE_HTTP_FINAL_RESULT<br />";
    $bet->matches->add_HTTP_final_result($_POST['matchID'], $_POST['team'], $_POST['score'], $_POST['teamID'], $_POST['teamW']);
    exit();
} elseif (SAVE_HTTP_RESULT) {
    if ($debug) {
        echo "SAVE_HTTP_RESULT<br />";
    }
    $bet->matches->add_HTTP_result($_POST['matchID'], $_POST['team'], $_POST['score']);
    exit();
} elseif (GET_HTTP_TAGS) {
    if ($debug) {
        echo "GET_HTTP_TAGS<br />";
    }
    $bet->load_tags(((isset($_GET['groupID']) && ($_GET['groupID'] != '')) ? $_GET['groupID'] : 0), ((isset($_GET['start']) && ($_GET['start'] != '')) ? $_GET['start'] : 0));
    $bet->display();
    exit();
} elseif (DEL_HTTP_TAG) {
    if ($debug)
        echo "DEL_HTTP_TAG<br />";
    $bet->tags->delete($_POST['tagID']);
    if (isset($_POST['groupID']))
        $bet->load_tags($_POST['groupID']);
    else
        $bet->load_tags();
    $bet->display();
    exit();
} elseif (SAVE_HTTP_TAG) {
    if ($debug) {
        echo "SAVE_HTTP_TEAM_TAG<br />";
    }
    $bet->tags->add($_POST['text'], $_POST['groupID']);
    $bet->load_tags($_POST['groupID']);
    $bet->display();
    exit();
} elseif (SAVE_HTTP_FINAL_BET) {
    if ($debug) {
        echo "SAVE_HTTP_FINAL_BET<br />";
    }
    $bet->bets->add_HTTP_final($_POST['userID'], $_POST['matchID'], $_POST['team'], $_POST['score'], $_POST['teamID'], $_POST['teamW']);
    exit();
} elseif (SAVE_HTTP_BET) {
    if ($debug) {
        echo "SAVE_HTTP_BET<br />";
    }
    $bet->bets->add_HTTP($_POST['userID'], $_POST['matchID'], $_POST['team'], $_POST['score']);
    exit();
} elseif (SAVE_BETS) {
    if ($debug) {
        echo "SAVE_BETS<br />";
    }
    $user_bets = $_POST;
    $userId = $_SESSION['userID'];
    $bet->bets->add_array($userId, $user_bets);
    redirect("/?act=bets");
} elseif (SAVE_FINALS_BETS) {
    if ($debug)
        echo "SAVE_FINALS_BETS<br />";
    $user_bets = $_POST;
    $userId = $_SESSION['userID'];
    $bet->bets->add_array($userId, $user_bets, true);
    redirect("/?act=finals_bets");
} elseif (GENERATE_STATS) {
    if ($debug)
        echo "GENERATE_STATS<br />";
    $bet->stats->generate_user_stats();
    redirect("/");
} elseif (SET_SETTING) {
    if ($debug)
        echo "SET_SETTING<br />";
    $bet->settings->set($_POST['setting'], $_POST['value']);
    redirect("/?act=" . $_POST['act']);
} elseif (VIEW_MATCH_STATS) {
    if ($debug) {
        echo "VIEW_MATCH_STATS<br />";
    }
    $bet->load_match_stats($_GET['matchID']);
    $bet->display();
    exit();
} elseif (RESET) {
    if ($debug) {
        echo "RESET<br />";
    }
    $bet->reset();
    redirect("/?act=admin");
}

$bet->load_header();
$bet->load_header_menu();

if (EDIT_RESULTS) {
    if ($debug)
        echo "EDIT_RESULTS<br />";
    $bet->load_results(true);
} elseif (EDIT_FINALS_RESULTS) {
    if ($debug)
        echo "EDIT_FINALS_RESULTS<br />";
    $bet->load_finals_results(true);
} elseif (VIEW_RESULTS) {
    if ($debug)
        echo "VIEW_RESULTS<br />";
    $bet->load_results(false);
} elseif (VIEW_ODDS) {
    if ($debug)
        echo "VIEW_ODDS<br />";
    $bet->load_odds(false);
} elseif (VIEW_FINALS_ODDS) {
    if ($debug)
        echo "VIEW_FINALS_ODDS<br />";
    $bet->load_finals_odds(false);
} elseif (EDIT_TEAMS) {
    if ($debug)
        echo "EDIT_TEAMS<br />";
    $teams = $bet->teams->get();
    $bet->load_teams($teams, true);
} elseif (EDIT_MATCHES) {
    if ($debug)
        echo "EDIT_MATCHES<br />";
    $matches = $bet->matches->get();
    if (isset($_GET['month']) && isset($_GET['day'])) {
        $bet->load_matches($matches, $_GET['month'], $_GET['day'], $_GET['pool']);
    } else {
        $bet->load_matches($matches);
    }
} elseif (EDIT_USERS) {
    if ($debug)
        echo "EDIT_USERS<br />";
    $users = $bet->users->get();
    $groups = $bet->groups->get_with_users();
    $bet->load_users($users, $groups);
} elseif (EDIT_BETS) {
    if ($debug) {
        echo "EDIT_BETS<br />";
    }

    $userID = false;
    if (isset($_GET['user'])) {
        $userID = $_GET['user'];
    }

    if (!isset($_GET['match_display'])) {
        $match_display = $bet->config['match_display_default'];
    } else {
        $match_display = $_GET['match_display'];
    }
    if ($match_display == 'pool') {
        $bet->load_bets([ 'edit' => true, 'userID' => $userID ]);
    } else if ($match_display == 'date') {
        $bet->load_bets([ 'edit' => true, 'userID' => $userID, 'orderByDate' => true ]);
    }
} elseif (EDIT_FINALS_BETS) {
    $userID = false;
    if (isset($_GET['user'])) {
        $userID = $_GET['user'];
    }

    if ($debug) {
        echo "EDIT_FINALS_BETS<br />";
    }
    $bet->load_finals_bets(true, $userID);
} elseif (VIEW_BETS_OF_USER) {
    if ($debug) {
        echo "VIEW_BETS_OF_USER<br />";
    }
    $bet->load_bets([ 'edit' => false, 'userID' => $_GET['user'] ]);
} elseif (VIEW_FINALS_BETS_OF_USER) {
    if ($debug)
        echo "VIEW_FINALS_BETS_OF_USER<br />";
    $bet->load_finals_bets(false, $_GET['user']);
} elseif (VIEW_USERS_VISUAL_RANKING) {
    if ($debug) {
        echo "VIEW_USERS_VISUAL_RANKING<br />";
    }
    $bet->load_users_visual_ranking();
} elseif (VIEW_GROUPS_RANKING) {
    if ($debug)
        echo "VIEW_GROUPS_RANKING<br />";
    $groups = $bet->groups->get();
    $bet->load_groups_ranking($groups);
} elseif (VIEW_USERS_RANKING_BY_GROUP) {
    if ($debug) {
        echo "VIEW_USERS_RANKING_BY_GROUP<br />";
    }
    $userId = $_SESSION['userID'];
    if (isset($_REQUEST['user'])) {
        $userId = $_REQUEST['user'];
    }
    $currentUser = $bet->users->get($userId);
    $groupId = $currentUser['groupID'];
    if (isset($_GET['groupID'])) {
        $groupId = $_GET['groupID'];
    }
    $bet->load_users_ranking_by_group($groupId);
} elseif (VIEW_STATS) {
    if ($debug)
        echo "VIEW_STATS<br />";
    $bet->load_user_stats($_GET['user']);
} elseif (ACCOUNT) {
    if ($debug)
        echo "ACCOUNT<br />";
    if (isset($_GET['w']) && $_GET['w'])
        $w = $bet->lang['warning'][$_GET['w']];
    $bet->load_account($w);
} elseif (RULES) {
    if ($debug)
        echo "RULES<br />";
    $bet->load_rules();
} elseif (CHANGE_ACCOUNT) {
    if ($debug)
        echo "CHANGE_ACCOUNT<br />";
    if (isset($_POST['username']) && $_POST['email']) {
        $ret = $bet->users->update_account($_POST['username'], $_POST['email']);
        redirect("/?act=change_account&w=" . $ret . "");
    } else {
        if (isset($_GET['w']) && $_GET['w'])
            $w = $bet->lang['warning'][$_GET['w']];
        $bet->load_change_account($w);
    }
} elseif (CHANGE_PASSWORD) {
    if ($debug)
        echo "CHANGE_PASSWORD<br />";
    if (isset($_POST['old_password']) && $_POST['old_password']) {
        $userId = $_SESSION['userID'];
        $ret = $bet->users->set_password($userId, $_POST['old_password'], $_POST['new_password1'], $_POST['new_password2']);
        redirect("/?act=change_account&w=" . $ret . "");
    } else {
        if (isset($_GET['w']) && $_GET['w'])
            $w = $bet->lang['warning'][$_GET['w']];
        $bet->load_change_account($w);
    }
} elseif (CHANGE_PREFERENCES) {
    if ($debug)
        echo "CHANGE_PREFERENCES<br />";
    if (isset($_POST['theme']) && $_POST['match_display']) {
        $ret = $bet->users->update_preferences($_POST['theme'], $_POST['match_display']);
        redirect("/?act=change_account&w=" . $ret . "");
    } else {
        if (isset($_GET['w']) && $_GET['w'])
            $w = $bet->lang['warning'][$_GET['w']];
        $bet->load_change_account($w);
    }
} elseif (JOIN_GROUP) {
    if ($debug)
        echo "JOIN_GROUP<br />";
    if (isset($_POST['group'])) {
        $userID = $_SESSION['userID'];
        $password = (isset($_POST['password'])) ? $_POST['password'] : false;
        $code = (isset($_POST['code'])) ? $_POST['code'] : false;
        $status = $bet->users->set_group($userID, $_POST['group'], $password, $code);
        redirect("/?act=account&w=" . $status . "");
    } else {
        $c = false;
        if (isset($_GET['w']) && $_GET['w'])
            $w = $bet->lang['warning'][$_GET['w']];
        if (isset($_GET['c']))
            $c = $_GET['c'];
        $bet->load_join_group($w, $c);
    }
} elseif (LEAVE_GROUP) {
    if ($debug)
        echo "LEAVE_GROUP<br />";
    if (!isset($_GET['groupID']))
        redirect("/?act=account");
    $bet->users->unset_group($_GET['groupID']);
    redirect("/?act=account");
} elseif (CREATE_GROUP) {
    if ($debug)
        echo "CREATE_GROUP<br />";
    if (isset($_POST['group_name']) && isset($_POST['password1']) && isset($_POST['password2'])) {
        if ($_POST['password1'] != $_POST['password2'])
            redirect("?act=create_group&w=" . PASSWORD_MISMATCH);
        if ($bet->groups->add('', $_POST['group_name'], $_POST['password1'])) {
            $group = $bet->groups->get_by_name($_POST['group_name']);
            redirect("/?act=create_group&w=" . CREATE_GROUP_OK . "&g=" . $group['groupID']);
        } else {
            redirect("/?act=create_group&w=" . GROUP_ALREADY_EXISTS);
        }
    }
    if (isset($_GET['w']) && $_GET['w'])
        $w = $bet->lang['warning'][$_GET['w']];
    $bet->load_create_group($w);
} elseif (INVITE_FRIENDS) {
    if ($debug){
        echo "INVITE_FRIENDS<br />";
    }
    if (isset($_POST['type'])) {
        if ($_POST['type'] == 'OUT') {
            $invitations = [];
            $emails = [];
            $nb_invitations = $bet->config['nb_invitations'];
            for ($i = 1; $i <= $nb_invitations; $i++) {
                if ((isset($_POST['email' . $i])) && ($_POST['email' . $i] != "")) {
                    $invitation = [];
                    $invitation['email'] = $_POST['email' . $i];
                    $invitation['groupID'] = $_POST['groupID' . $i];
                    $invitations[] = $invitation;
                    $emails[] = $_POST['email' . $i];
                }
            }
            $codes = $bet->groups->create_uniq_invitations($invitations, $_POST['type']);
            $status = $bet->send_invitations($emails, $codes, $_POST['type']);
            $bet->audit->add($_SESSION['userID'], 'invit', 'a invité de nouveaux utilisateurs : ' . implode(', ', $emails));
            redirect("/?act=invite_friends&w=" . $status . "");
        } elseif ($_POST['type'] == 'IN') {
            $invitations = [];
            $emails = [];
            $nb_invitations = $bet->config['nb_invitations'];
            for ($i = 1; $i <= $nb_invitations; $i++) {
                if ((isset($_POST['userID' . $i])) && ($_POST['userID' . $i] != "")) {
                    if ($_POST['userID' . $i] == 0)
                        continue;
                    $invitation = [];
                    $user = $bet->users->get($_POST['userID' . $i]);
                    if (!$user)
                        continue;
                    $invitation['userID'] = $_POST['userID' . $i];
                    $invitation['email'] = $user['email'];
                    $invitation['groupID'] = $_POST['groupID' . $i];
                    $invitations[] = $invitation;
                    $emails[] = $user['email'];
                }
            }
            $codes = $bet->groups->create_uniq_invitations($invitations, $_POST['type']);
            $status = $bet->send_invitations($emails, $codes, $_POST['type']);
            $bet->audit->add($_SESSION['userID'], 'invit', 'a invité les utilisateurs : ' . implode(', ', $emails));
            redirect("/?act=invite_friends&w=" . $status . "");
        } else {
            redirect("/?act=invite_friends");
        }
    }
    $user = $bet->users->get_current();
    if (isset($_GET['w']) && $_GET['w']) {
        $w = $bet->lang['warning'][$_GET['w']];
    }
    elseif (($user['groupID'] == "") && ($user['groupID2'] == "") && ($user['groupID3'] == "")) {
        $w = $bet->lang['warning'][INVITE_WITHOUT_GROUP];
    }
    $bet->load_invite_friends($w);
} elseif (ADMIN) {
    if ($debug) {
        echo "ADMIN<br />";
    }
    $bet->load_header();
    $bet->load_admin();
    $bet->display();
    exit();
} elseif (VIEW_AUDIT) {
    if ($debug) {
        echo "VIEW_AUDIT<br />";
    }
    $user = isset($_GET['user']) ? $_GET['user'] : false;
    $category = isset($_GET['category']) ? $_GET['category'] : false;
    if ($user || $category) {
        $logs = $bet->audit->get_by_user_and_category($user, $category);
    } else {
        $logs = $bet->audit->get_between(0, 500);
    }
    $bet->load_audit_logs($logs);
} else {
    if ($debug) {
        echo "VIEW_USERS_RANKING<br />";
    }
    $bet->users->update_last_connection($_SESSION['userID']);
    $bet->load_users_ranking();
}

//$bet->load_menu();
$bet->load_tail(true);

$bet->display();
