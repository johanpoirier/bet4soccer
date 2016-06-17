<?php

require_once(__DIR__ . '/vendor/autoload.php');

include_once(BASE_PATH . 'lib/define.inc.php');
include_once(BASE_PATH . 'lib/config.inc.php');
include_once(BASE_PATH . 'lang/' . $config['lang'] . '.inc.php');
include_once(BASE_PATH . 'lib/functions.inc.php');
include_once(BASE_PATH . 'lib/template.php');
include_once(BASE_PATH . 'lib/settings.php');
include_once(BASE_PATH . 'lib/matches.php');
include_once(BASE_PATH . 'lib/teams.php');
include_once(BASE_PATH . 'lib/stats.php');
include_once(BASE_PATH . 'lib/tags.php');
include_once(BASE_PATH . 'lib/users.php');
include_once(BASE_PATH . 'lib/bets.php');
include_once(BASE_PATH . 'lib/groups.php');
include_once(BASE_PATH . 'lib/audit.php');
include_once(BASE_PATH . 'lib/tokens.php');
include_once(BASE_PATH . 'lib/db.mysql.php');

class BetEngine
{

    var $db;
    var $debug;
    var $config;
    var $template;
    var $start_time;
    var $lang;
    var $template_location;
    var $template_web_location;
    var $blocks_loaded;
    var $msg;
    var $settings;
    var $matches;
    var $teams;
    var $stats;
    var $tags;
    var $users;
    var $bets;
    var $groups;
    var $audit;
    var $tokens;

    public function __construct($admin = false, $debug = false)
    {
        global $config;
        global $lang;
        global $debug;

        $this->start_time = get_moment();
        $this->config = $config;
        $this->debug = $debug;
        $this->lang = $lang;

        $this->db = new MySQL_DB();
        $this->db->set_debug($debug);

        $this->msg = "";
        $this->page_views = false;
        if (isset($_SESSION['theme'])) {
            $this->template_location = BASE_PATH . 'template/' . $_SESSION['theme'] . "/";
            $this->template_web_location = WEB_PATH . 'template/' . $_SESSION['theme'] . "/";
        } else {
            $this->template_location = BASE_PATH . 'template/' . $config['template_default'] . "/";
            $this->template_web_location = WEB_PATH . 'template/' . $config['template_default'] . "/";
        }
        $this->template = new Template($this->template_location);
        $this->blocks_loaded = [];

        $this->settings = new Settings($this, $this->db, $this->config, $this->lang, $this->debug);
        $this->matches = new Matches($this, $this->db, $this->config, $this->lang, $this->debug);
        $this->teams = new Teams($this, $this->db, $this->config, $this->lang, $this->debug);
        $this->tags = new Tags($this, $this->db, $this->config, $this->lang, $this->debug);
        $this->users = new Users($this, $this->db, $this->config, $this->lang, $this->debug);
        $this->bets = new Bets($this, $this->db, $this->config, $this->lang, $this->debug);
        $this->groups = new Groups($this, $this->db, $this->config, $this->lang, $this->debug);
        
        $this->audit = new Audit($this->db, $this->config);
        $this->stats = new Stats($this->db, $this->config, $this->lang, $this->users, $this->groups, $this->matches, $this->bets);
        $this->tokens = new Tokens($this->db, $this->config);
    }

    function islogin()
    {
        return isset($_SESSION['userID']);
    }

    function isadmin()
    {
        return isset($_SESSION['status']) && (intval($_SESSION['status']) === 1);
    }

    /*     * **************** */
    /*     LOADERS     */
    /*     * **************** */

    function load_authentification($warning = "", $code = false)
    {
        $this->template->set_filenames(array(
            'connexion' => 'connexion.tpl'
        ));
        $this->template->assign_vars(array(
            'TITLE' => $this->config['blog_title'],
            'WARNING' => $warning,
            'LABEL_LOGIN' => $this->lang['LABEL_LOGIN'],
            'TPL_WEB_PATH' => $this->template_web_location,
            'CODE' => $code,
        ));

        $this->blocks_loaded[] = 'connexion';
    }

    /*     * **************** */

    function load_header($title = false)
    {
        $this->template->set_filenames([ 'head' => 'header.tpl' ]);

        if ($title) {
            $title = $this->config['blog_title'] . " - " . $title;
        } else {
            $title = $this->config['blog_title'];
        }

        $this->template->assign_vars(array(
            'TITLE' => $title,
            'MAIN_MSG' => $this->config['blog_description'],
            'URL' => $this->config['url'],
            'TPL_WEB_PATH' => $this->template_web_location
        ));

        $this->blocks_loaded[] = 'head';
    }

    /*     * **************** */

    function import_csv_file()
    {
        if (!isset($_FILES['csv_file']['tmp_name']) || !file_exists($_FILES['csv_file']['tmp_name']))
            return false;

        $file = fopen($_FILES['csv_file']['tmp_name'], "r");

        for ($i = 0; ($data = fgetcsv($file, 1000, ";")) !== FALSE; $i++) {

            $num = count($data);
            if ($num == 6) {
                if ($teamID = $this->groups->get_ID_by_name($data[4])) {
                    $this->users->add($data[0], $data[1], $data[2], $data[3], $teamID, $data[5]);
                }
            }
        }
        fclose($file);
    }

    /*     * **************** */

    function load_header_menu()
    {
        $this->template->set_filenames(array(
            'header_menu' => 'header_menu.tpl'
        ));

        $matches = $this->matches->get_next();
        $last_pool_match = $this->matches->get_last_pool();

        $delay_str = "";
        $match_str = "";

        if (isset($matches[0])) {
            $delay = (isset($matches[0]['delay_sec'])) ? $matches[0]['delay_sec'] : 0;
            $delay_days = (isset($matches[0]['delay_days'])) ? $matches[0]['delay_days'] : 0;

            if ($delay_days > 31) {
                $delay = $delay_days * 60 * 60 * 24;
            }

            if ($delay > 0) {
                $delay_days = floor($delay / (60 * 60 * 24));
                $delay = $delay - $delay_days * 24 * 60 * 60;
                if ($delay_days > 0) {
                    $delay_str .= $delay_days . "j ";
                }

                $delay_hour = floor($delay / (60 * 60));
                $delay = $delay - $delay_hour * 60 * 60;
                if ($delay_hour > 0) {
                    $delay_str .= $delay_hour . "h ";
                }

                $delay_min = floor($delay / (60));
                if ($delay_min > 0) {
                    $delay_str .= $delay_min . "m";
                }
                $match_str = "-> Prochain match <u>" . $matches[0]['date_str'] . "</u> (<i>dans " . $delay_str . "</i>) :";
            } else {
                $delay = abs($delay);
                if ($delay > 0 && $delay < (45 * 60)) {
                    $delay_str = "depuis " . floor($delay / 60) . "m";
                } elseif ($delay > (60 * 45) && $delay < (60 * 60)) {
                    $delay_str = "Mi-Temps";
                } else {
                    $delay_str = "depuis " . ((floor($delay / 60)) - 15) . "m";
                }
                $match_str = "-> Match en cours (<i>" . $delay_str . "</i>):";
            }
        }

        $this->template->assign_block_vars('matches', array(
            'MATCH_STR' => $match_str
        ));

        foreach ($matches as $match) {
            if ($this->islogin()) {
                $this->template->assign_block_vars('matches.list', array(
                    'ID' => $match['matchID'],
                    'TEAM_NAME_A' => $match['teamAname'],
                    'TEAM_NAME_B' => $match['teamBname']
                ));
            } else {
                $this->template->assign_block_vars('matches.ext_list', array(
                    'ID' => $match['matchID'],
                    'TEAM_NAME_A' => $match['teamAname'],
                    'TEAM_NAME_B' => $match['teamBname']
                ));
            }
        }

        $group_name = "";
        $is_g1 = (isset($_SESSION['group_name']) && ($_SESSION['group_name'] != ""));
        $is_g2 = (isset($_SESSION['group_name2']) && ($_SESSION['group_name2'] != ""));
        $is_g3 = (isset($_SESSION['group_name3']) && ($_SESSION['group_name3'] != ""));

        if ($is_g1 || $is_g2 || $is_g3) {
            $group_name .= "(";
            if ($is_g1) {
                $group_name .= $_SESSION['group_name'];
                if ($is_g2 || $is_g3)
                    $group_name .= "/";
            }
            if ($is_g2) {
                $group_name .= $_SESSION['group_name2'];
                if ($is_g3)
                    $group_name .= "/";
            }
            if ($is_g3) {
                $group_name .= $_SESSION['group_name3'];
            }
            $group_name .= ")";
        }

        $this->template->assign_vars(array(
            'USERNAME' => (isset($_SESSION['user_name'])) ? $_SESSION['user_name'] : "",
            'ADMIN' => (isset($_SESSION['status']) && $_SESSION['status'] == 1) ? true : false,
            'VIEW_STATS' => (isset($_SESSION['userID'])) ? "<a href=\"/?act=view_stats&user=" . $_SESSION['userID'] . "\"><img src=\"" . $this->template_web_location . "/images/stats.gif\" alt=\"stats\" /></a>" : "",
            'USERID' => (isset($_SESSION['userID'])) ? $_SESSION['userID'] : "",
            'HEADER_GROUP_NAME' => $group_name,
            'FINALS' => ((isset($last_pool_match['delay_sec'])) && ($last_pool_match['delay_sec'] > 0)) ? "" : "finals_",
            'MATCH_DISPLAY' => "&match_display=" . (isset($_SESSION['match_display']) ? $_SESSION['match_display'] : $this->config['match_display_default']),
            'LOGGED_IN' => $this->islogin()
        ));

        if ($this->islogin()) {
            $this->template->assign_block_vars('logged_in', []);
            $this->template->assign_block_vars('logged_in.account', []);
            if ($this->isadmin()) {
                $this->template->assign_block_vars('logged_in.admin_bar', []);
                $this->template->assign_block_vars('logged_in.user_nav', []);
                $this->template->assign_block_vars('logged_in.admin_nav', []);
            } else {
                $this->template->assign_block_vars('logged_in.user_bar', []);
                $this->template->assign_block_vars('logged_in.user_nav', []);
            }
        }


        $this->blocks_loaded[] = 'header_menu';
    }

    /*     * **************** */

    function load_tail($private = false)
    {
        if ($private) {
            $this->template->set_filenames(array(
                'tail' => 'tail_private.tpl'
            ));
        } else {
            $this->template->set_filenames(array(
                'tail' => 'tail_public.tpl'
            ));
        }
        $this->template->assign_vars(array(
            'QUERIES_TIME' => $this->get_queries_time(),
            'NB_QUERIES' => $this->get_nb_queries(),
            'EXEC_TIME' => get_elapsed_time($this->start_time, get_moment()),
            'TPL_WEB_PATH' => $this->template_web_location,
            'CONTACT_EMAIL' => $this->config['email']
        ));

        if ($this->isadmin()) {
            $this->template->assign_block_vars('menu_admin', []);
        }

        if ($this->page_views) {
            $this->template->assign_block_vars('post_view', array(
                'PAGE_VIEWS' => $this->page_views
            ));
        }
        $this->blocks_loaded[] = 'tail';
    }

    /*     * **************** */

    function load_rules()
    {
        $this->template->set_filenames(array(
            'rules' => 'rules.tpl'
        ));

        $rounds = $this->config['rounds'];
        array_unshift($rounds, 'pool');

        $pools = $this->config['pools'];

        $points = [];
        $total = 0;
        $total_pool = 0;
        $total_finals = 0;

        foreach ($rounds as $round) {
            if ($round == 'pool') {
                $nbmatches = 0;
                foreach ($pools as $pool) {
                    $nbmatches += count($this->matches->get_by_pool($pool));
                }
            } elseif ($round != 3) {
                $nbmatches = $round;
            } else {
                $nbmatches = 1;
            }

            if ($round == 'pool') {
                $total_round = $nbmatches * ($this->config['points_' . $round . '_good_result'] + $this->config['points_' . $round . '_exact_score']);
            } else {
                $total_round = $nbmatches * ($this->config['points_' . $round . '_good_result'] + $this->config['points_' . $round . '_qualify'] + $this->config['points_' . $round . '_exact_score']);
            }

            $this->template->assign_block_vars('rounds', array(
                'NAME' => ($round == 'pool') ? $this->lang['LABEL_POOL'] : $this->lang['LABEL_' . $round . '_FINAL'],
                'POINTS_GOOD_RESULT' => $this->config['points_' . $round . '_good_result'],
                'POINTS_QUALIFY' => ($round == 'pool') ? "" : $this->config['points_' . $round . '_qualify'],
                'POINTS_EXACT_SCORE' => $this->config['points_' . $round . '_exact_score'],
                'POINTS_SUM' => $total_round / $nbmatches,
                'POINTS_NB_MATCHES' => $nbmatches,
                'POINTS_TOTAL' => $total_round,
            ));

            $total += $total_round;
            if ($round == 'pool') {
                $total_pool += $total_round;
            } else {
                $total_finals += $total_round;
            }
        }

        $this->template->assign_vars(array(
            'TPL_WEB_PATH' => $this->template_web_location,
            'POINTS_FINALS_TOTAL' => $total_finals,
            'POINTS_POOL_TOTAL' => $total_pool,
            'POINTS_ALL_TOTAL' => $total,
        ));

        $this->blocks_loaded[] = 'rules';
    }

    /*     * **************** */

    function load_menu()
    {
        $this->template->set_filenames(array(
            'menu' => 'menu.tpl'
        ));

        $this->template->assign_vars(array(
            'TPL_WEB_PATH' => $this->template_web_location
        ));


        $this->blocks_loaded[] = 'menu';
    }

    /*     * **************** */

    function load_teams($teams)
    {
        $this->template->set_filenames(array(
            'teams' => 'teams.tpl'
        ));

        $pools_teams = [];

        foreach ($teams as $team) {
            $p = $team['pool'];
            $t = array(
                'ID' => $team['teamID'],
                'NAME' => $team['name'],
                'NAME_URL' => $this->config['force_encoding_fs'] ? rawurlencode(utf8_decode($team['name'])) : rawurlencode($team['name']),
                'POOL' => $team['pool'],
                'STATUS' => $team['status']
            );

            if (!isset($pools_teams[$p]))
                $pools_teams[$p] = [];
            array_push($pools_teams[$p], $t);
        }

        $pools = $this->config['pools'];

        foreach ($pools as $pool) {
            $this->template->assign_block_vars('pools', array(
                'NAME' => $pool
            ));
            if (isset($pools_teams[$pool]))
                $teams = $pools_teams[$pool];
            else
                $teams = [];
            foreach ($teams as $team) {
                $this->template->assign_block_vars('pools.teams', $team);
            }
        }

        $this->blocks_loaded[] = 'teams';
    }

    /*     * **************** */

    function load_money()
    {
        $this->template->set_filenames(array(
            'money' => 'money.tpl'
        ));

        $money = $this->settings->get_value('MONEY');

        $this->template->assign_vars(array(
            'TOTAL_MONEY' => $money,
            '1ST_MONEY' => $money * 0.6,
            '2ND_MONEY' => $money * 0.3,
            '3RD_MONEY' => $money * 0.1,
        ));
        $this->blocks_loaded[] = 'money';
    }

    function load_forgot_password()
    {
        $this->template->set_filenames([ 'forgot_password' => 'forgot_password.tpl' ]);

        $this->template->assign_vars([ 'LABEL_FORGOTTEN_PASSWORD' => $this->lang['LABEL_FORGOTTEN_PASSWORD'] ]);
        $this->blocks_loaded[] = 'forgot_password';
    }

    function load_forgot_login()
    {
        $this->template->set_filenames([ 'forgot_login' => 'forgot_login.tpl' ]);

        $this->template->assign_vars([ 'LABEL_FORGOTTEN_LOGIN' => $this->lang['LABEL_FORGOTTEN_LOGIN'] ]);
        $this->blocks_loaded[] = 'forgot_login';
    }

    function load_change_account($warning = "")
    {
        $this->template->set_filenames([ 'change_account' => 'change_account.tpl' ]);

        $user = $this->users->get_current();

        $themes = $this->config['templates'];
        if ($user['theme'] == "")
            $user['theme'] = $this->config['template_default'];
        foreach ($themes as $theme_id => $theme_name) {
            $this->template->assign_block_vars('themes', array(
                'ID' => $theme_id,
                'NAME' => $theme_name,
                'SELECTED' => ($user['theme'] == $theme_id) ? " selected=\"selected\"" : ""
            ));
        }

        $match_display_options = array("pool" => "par poule", "date" => "par date");
        if ($user['match_display'] == "")
            $user['match_display'] = $this->config['match_display_default'];
        foreach ($match_display_options as $id => $label) {
            $this->template->assign_block_vars('match_display', array(
                'ID' => $id,
                'LABEL' => $label,
                'SELECTED' => ($user['match_display'] == $id) ? " selected=\"selected\"" : ""
            ));
        }

        $this->template->assign_vars(array(
            'WARNING' => $warning,
            'LABEL_LOGIN' => $this->lang['LABEL_LOGIN'],
            'LOGIN' => $user['login'],
            'USERNAME' => $user['name'],
            'EMAIL' => $user['email'],
            'THEME' => $user['theme']
        ));

        $this->blocks_loaded[] = 'change_account';
    }

    /*     * **************** */

    function load_users($users, $users_groups)
    {
        $this->template->set_filenames(array(
            'users' => 'users.tpl'
        ));

        $this->template->assign_vars(array(
            'MONEY' => $this->settings->get_value('MONEY')
        ));

        foreach ($users as $user) {
            $this->template->assign_block_vars('users', array(
                'ID' => $user['userID'],
                'LOGIN' => $user['login'],
                'PASS' => $user['password'],
                'POINTS' => $user['points'],
                'STATUS' => $user['status'],
                'ID_GROUP' => $user['groupID'],
                'ID_GROUP2' => $user['groupID2'],
                'ID_GROUP3' => $user['groupID3']
            ));
            if ($user['status'] == 1) {
                $this->template->assign_block_vars('users.admin', array(
                    'NAME' => $user['name'],
                    'ID_GROUP' => $user['groupID'],
                    'LOGIN' => $user['login'],
                    'GROUP_NAME' => $user['groupName'],
                    'GROUP_NAME2' => $user['groupName2'],
                    'GROUP_NAME3' => $user['groupName3'],
                    'LAST_CONNECTION' => $user['last_connection'],
                    'LAST_BET' => $user['last_bet'],
                    'BETS_COUNT' => $user['nb_bets']
                ));
            } else {
                $this->template->assign_block_vars('users.user', array(
                    'NAME' => $user['name'],
                    'ID_GROUP' => $user['groupID'],
                    'LOGIN' => $user['login'],
                    'GROUP_NAME' => $user['groupName'],
                    'GROUP_NAME2' => $user['groupName2'],
                    'GROUP_NAME3' => $user['groupName3'],
                    'LAST_CONNECTION' => $user['last_connection'],
                    'LAST_BET' => $user['last_bet'],
                    'BETS_COUNT' => $user['nb_bets']
                ));
            }
        }

        foreach ($users_groups as $groups) {
            $this->template->assign_block_vars('groups', array(
                'ID_GROUP' => $groups['groupID'],
                'NAME' => $groups['name'],
                'COUNT' => $groups['nb_users']
            ));
        }

        $this->blocks_loaded[] = 'users';
    }

    /*     * **************** */


    function load_audit_logs($logs)
    {
        $this->template->set_filenames([ 'audit' => 'audit.tpl' ]);

        $users = $this->users->get();
        foreach ($users as $user) {
            $this->template->assign_block_vars('users', [
                'ID' => $user['userID'],
                'NAME' => $user['name']
            ]);
        }

        $categories = $this->audit->get_categories();
        foreach ($categories as $category) {
            $this->template->assign_block_vars('categories', [ 'CATEGORY' => $category ]);
        }
        
        foreach ($logs as $log) {
            $this->template->assign_block_vars('logs', [
                'DATE' => $log['date'],
                'USER_ID' => $log['userID'],
                'USER_NAME' => $log['name'],
                'CATEGORY' => $log['category'],
                'ACTION' => $log['action']
            ]);
        }

        $this->blocks_loaded[] = 'audit';
    }

    /*     * **************** */

    function load_users_ranking()
    {
        $this->template->set_filenames(array(
            'users_ranking' => 'users_ranking.tpl'
        ));

        $users = $this->users->get_full_ranking();
        $nb_bets = $this->bets->count_by_users();
        $nb_played_bets = $this->bets->count_played_by_users();;
        $nb_active_users = $this->users->count_active();
        $nb_matches = $this->settings->get_value('NB_MATCHES_GENERATED');
        if ($nb_matches == "") {
            $nb_matches = 0;
        }
        $last_pool_match = $this->matches->get_last_pool();

        $this->template->assign_vars(array(
            'NB_USERS' => $this->users->count(),
            'NB_MATCHES' => ($nb_matches > 1) ? $nb_matches . " matches" : $nb_matches . " match",
            'NB_ACTIVE_USERS' => $this->users->count_active(),
            'LABEL_TEAMS_RANKING' => $this->lang['LABEL_TEAMS_RANKING']
        ));

        $ses_g1 = ($_SESSION['group_name'] != "");
        $ses_g2 = ($_SESSION['group_name2'] != "");
        $ses_g3 = ($_SESSION['group_name3'] != "");

        if ($ses_g1) {
            $this->template->assign_block_vars('g1', array(
                'GROUP_ID' => $_SESSION['group_id'],
                'GROUP_NAME' => $_SESSION['group_name']
            ));
        }

        if ($ses_g2) {
            $this->template->assign_block_vars('g2', array(
                'GROUP_ID2' => $_SESSION['group_id2'],
                'GROUP_NAME2' => $_SESSION['group_name2']
            ));
        }

        if ($ses_g3) {
            $this->template->assign_block_vars('g3', array(
                'GROUP_ID3' => $_SESSION['group_id3'],
                'GROUP_NAME3' => $_SESSION['group_name3']
            ));
        }

        $group = $this->groups->get_by_name($this->config['money_group_name']);
        if ($group && $this->users->is_in_group($group['groupID'])) {
            $this->template->assign_block_vars('money', array(
                'AMOUNT' => $this->settings->get_value('MONEY')
            ));
        }

        $max_evol = ['userID' => 0, 'evol' => 0];
        $min_evol = ['userID' => 0, 'evol' => 0];

        foreach ($users as $user) {
            if ($nb_bets[$user['userID']] == 0)
                continue;

            if ($user['points'] != null) {
                $evol = $user['evol'];
                if ($evol == 0) {
                    $img = "egal.png";
                    $rank_class = "still";
                } elseif ($evol > 5) {
                    $img = "arrow_up2.png";
                    $rank_class = "high_increase";
                } elseif ($evol > 0) {
                    $rank_class = "increase";
                    $img = "arrow_up1.png";
                } elseif ($evol < -5) {
                    $rank_class = "high_drop";
                    $img = "arrow_down2.png";
                } elseif ($evol < 0) {
                    $img = "arrow_down1.png";
                    $rank_class = "drop";
                }
                if ($evol > 0)
                    $evol = "+" . $evol;

                if ($evol > $max_evol['evol']) {
                    $max_evol['userID'] = $user['userID'];
                    $max_evol['evol'] = $evol;
                }

                if ($evol < $min_evol['evol']) {
                    $min_evol['userID'] = $user['userID'];
                    $min_evol['evol'] = $evol;
                }
            } else {
                $img = false;
                $i = null;
            }

            $group_name = "";
            $is_g1 = ($user['groupName'] != "");
            $is_g2 = ($user['groupName2'] != "");
            $is_g3 = ($user['groupName3'] != "");

            if ($is_g1) {
                $group_name .= "<a class=\"nolink\" href=\"/?act=view_users_ranking_by_group&groupID=" . $user['groupID'] . "\">" . $user['groupName'] . "</span></a>";
                if ($is_g2 || $is_g3)
                    $group_name .= "<br />";
            }
            if ($is_g2) {
                $group_name .= "<a class=\"nolink\" href=\"/?act=view_users_ranking_by_group&groupID=" . $user['groupID2'] . "\">" . $user['groupName2'] . "</a>";
                if ($is_g3)
                    $group_name .= "<br />";
            }
            if ($is_g3) {
                $group_name .= "<a class=\"nolink\" href=\"/?act=view_users_ranking_by_group&groupID=" . $user['groupID3'] . "\">" . $user['groupName3'] . "</a>";
            }

            if ($_SESSION['userID'] == $user['userID']) {
                if ($last_pool_match['delay_sec'] > 0) {
                    $user_url = "<a href=\"/?act=bets\">";
                } else {
                    $user_url = "<a href=\"/?act=finals_bets\">";
                }
            } elseif ($this->users->is_admin($_SESSION['userID'])) {
                if ($last_pool_match['delay_sec'] > 0) {
                    $user_url = '<a href="/?act=bets&user=' . $user['userID'] . '">';
                } else {
                    $user_url = '<a href="/?act=finals_bets&user=' . $user['userID'] . '">';
                }
            } else {
                if ($last_pool_match['delay_sec'] > 0) {
                    $user_url = '<a href="/?act=view_bets&user=' . $user['userID'] . '">';
                } else {
                    $user_url = '<a href="/?act=view_finals_bets&user=' . $user['userID'] . '">';
                }
            }

            $class = "";
            if ($_SESSION['userID'] == $user['userID']) {
                $class = "me";
            } elseif ($nb_active_users > 10 && $this->matches->get_last_played() !== false) {
                if ($user['rank'] <= 3) {
                    $class = "first";
                } elseif ($user['rank'] > ($nb_active_users - 1)) {
                    $class = "last";
                }
            }

            $this->template->assign_block_vars('users', array(
                'EVOLUTION' => ($img) ? "($evol)" : "",
                'RANK_CLASS' => ($img) ? $rank_class : "",
                'RANK' => ($user['points'] != null) ? $user['rank'] : "",
                'LAST_RANK' => ($img) ? "<img src=\"" . $this->template_web_location . "/images/" . $img . "\" /><br/><span style=\"text-align:center;font-size:70%;\">(" . $evol . ")</span>" : "",
                'NB_MISS_BETS' => ((isset($nb_played_bets[$user['userID']])) && ($nb_played_bets[$user['userID']] < $nb_matches)) ? "(<span style=\"color:grey;\">-" . ($nb_matches - $nb_played_bets[$user['userID']]) . "</span>)" : "",
                'ID' => $user['userID'],
                'NAME' => $user['name'],
                'GROUP' => $group_name,
                'VIEW_BETS' => $user_url,
                'VIEW_STATS' => ($user['points'] != "") ? "<a href=\"/?act=view_stats&user=" . $user['userID'] . "\"><img src=\"" . $this->template_web_location . "/images/stats.gif\" alt=\"stats\" /></a>" : "",
                'POINTS' => $user['points'],
                'NBRESULTS' => $user['nbresults'],
                'NBSCORES' => $user['nbscores'],
                'DIFF' => $user['diff'],
                'STATUS' => $user['status'],
                'CLASS' => $class
            ));
        }

        if (sizeof($users) > 0 && $users[$_SESSION['userID']]['rank'] != null) {
            $evol = $users[$_SESSION['userID']]['evol'];
            $this->template->assign_block_vars('mine', array(
                'ID' => $_SESSION['userID'],
                'RANK' => $users[$_SESSION['userID']]['rank'],
                'POINTS' => $users[$_SESSION['userID']]['points'],
                'EVOL' => (($evol > 0) ? "+" : "") . $evol . " place" . ((abs($evol) > 1) ? "s" : "")
            ));
        }

        if ($max_evol['userID'] != 0) {
            $this->template->assign_block_vars('max', array(
                'ID' => $max_evol['userID'],
                'NAME' => $users[$max_evol['userID']]['name'],
                'EVOL' => $max_evol['evol'] . " place" . (($max_evol['evol'] > 1) ? "s" : "")
            ));
        }

        if ($min_evol['userID'] != 0) {
            $this->template->assign_block_vars('min', array(
                'ID' => $min_evol['userID'],
                'NAME' => $users[$min_evol['userID']]['name'],
                'EVOL' => $min_evol['evol'] . " place" . ((abs($max_evol['evol']) > 1) ? "s" : "")
            ));
        }

        $this->blocks_loaded[] = 'users_ranking';
    }

    function load_users_visual_ranking()
    {
        $this->template->set_filenames(array(
            'users_visual_ranking' => 'users_visual_ranking.tpl'
        ));

        $userID = $_SESSION['userID'];
        $users = $this->users->get();
        $nb_active_users = $this->users->count_active();
        $nb_total_users = sizeof($users);

        $nb_matches = $this->settings->get_value('NB_MATCHES_GENERATED');
        if ($nb_matches == "") {
            $nb_matches = 0;
        }
        $last_pool_match = $this->matches->get_last_pool();

        $this->template->assign_vars(array(
            'NB_USERS' => $this->users->count(),
            'NB_MATCHES' => ($nb_matches > 1) ? $nb_matches . " matches" : $nb_matches . " match",
            'NB_ACTIVE_USERS' => $nb_active_users,
            'LABEL_TEAMS_RANKING' => $this->lang['LABEL_TEAMS_RANKING']
        ));

        if (sizeof($users) > 0) {
            usort($users, "compare_users");

            $i = 1;
            $j = 0;
            $k = 0;
            $last_user = $users[0];

            foreach ($users as $user) {
                if (($user['nb_bets'] == 0) || $user['points'] == '') {
                    $nb_total_users--;
                    continue;
                }
                if (compare_users($user, $last_user) != 0) {
                    $i = $j + 1;
                }

                $class = "";
                if ($userID == $user['userID']) {
                    $class = "me";
                } elseif ($nb_active_users > 10) {
                    if ($i <= 3) {
                        $class = "first";
                    } elseif ($i > ($nb_active_users - 1)) {
                        $class = "last";
                    }
                }

                $users_view[$k++] = array(
                    'RANK' => $i,
                    'ID' => $user['userID'],
                    'NAME' => $user['name'],
                    'LOGIN' => $user['login'],
                    'POINTS' => $user['points'],
                    'CLASS' => $class
                );
                $last_user = $user;
                $j++;
            }

            $no_user_view = array(
                'RANK' => "-",
                'ID' => "",
                'NAME' => "",
                'LOGIN' => "",
                'POINTS' => "",
                'CLASS' => ""
            );

            $users_points_gap = $users_view[0]['POINTS'] - $users_view[$k - 1]['POINTS'];
            $index_user = 0;
            for ($i = $users_view[0]['POINTS']; $i >= $users_view[$k - 1]['POINTS']; $i--) {
                $user = null;
                $nb_users_same_pts = 0;
                for ($j = $index_user; $j < sizeof($users_view); $j++) {
                    while ($users_view[$j]['POINTS'] == $i) {
                        $nb_users_same_pts++;
                        if ($user == null) {
                            $user = $users_view[$j];
                        } else {
                            $user['NAME'] .= ', ' . $users_view[$j]['NAME'];
                        }
                        $j++;
                        if ($j >= sizeof($users_view)) {
                            break;
                        }
                        $index_user = $j;
                    }
                }
                if ($user == null) {
                    $user = $no_user_view;
                    $user['POINTS'] = $i;
                }
                if ($nb_users_same_pts > 1) {
                    $user['NB'] = "<u>" . $nb_users_same_pts . " parieurs</u> : ";
                }
                $this->template->assign_block_vars('users', $user);
            }
        }

        $this->blocks_loaded[] = 'users_visual_ranking';
    }

    /*     * **************** */

    function load_groups_ranking($groups)
    {
        $this->template->set_filenames(array(
            'groups_ranking' => 'groups_ranking.tpl'
        ));

        $groupsView = [];
        $nbMatchesPlayed = $this->matches->count_played();
        $nb_matches = $this->settings->get_value('NB_MATCHES_GENERATED');
        if ($nb_matches == "") {
            $nb_matches = 0;
        }
        $this->template->assign_vars(array(
            'LABEL_TEAMS_RANKING' => $this->lang['LABEL_TEAMS_RANKING'],
            'NB_MATCHES' => ($nb_matches > 1) ? $nb_matches . " matches" : $nb_matches . " match",
            'NB_GROUPS' => $this->groups->count(),
            'NB_ACTIVE_GROUPS' => $this->groups->count_active(),
        ));

        $ses_g1 = ($_SESSION['group_name'] != "");
        $ses_g2 = ($_SESSION['group_name2'] != "");
        $ses_g3 = ($_SESSION['group_name3'] != "");

        if ($ses_g1) {
            $this->template->assign_block_vars('g1', array(
                'GROUP_ID' => $_SESSION['group_id'],
                'GROUP_NAME' => $_SESSION['group_name']
            ));
        }

        if ($ses_g2) {
            $this->template->assign_block_vars('g2', array(
                'GROUP_ID2' => $_SESSION['group_id2'],
                'GROUP_NAME2' => $_SESSION['group_name2']
            ));
        }

        if ($ses_g3) {
            $this->template->assign_block_vars('g3', array(
                'GROUP_ID3' => $_SESSION['group_id3'],
                'GROUP_NAME3' => $_SESSION['group_name3']
            ));
        }

        $group = $this->groups->get_by_name($this->config['money_group_name']);
        if ($group && $this->users->is_in_group($group['groupID'])) {
            $this->template->assign_block_vars('money', array(
                'AMOUNT' => $this->settings->get_value('MONEY')
            ));
        }

        foreach ($groups as $group) {
            $users = $this->users->get_by_group($group['groupID']);
            $group['nbUsersActifs'] = 0;
            foreach ($users as $user) {
                $played_bets = $this->bets->count_played_by_user($user['userID']);
                if (($nbMatchesPlayed) > 0 && ($played_bets / $nbMatchesPlayed) > $this->config['min_ratio_played_matches_for_group']) {
                    $group['nbUsersActifs']++;
                }
            }
            if (!$this->config['display_empty_group'] && count($users) < 3) {
                continue;
            }
            if (!$this->config['display_unactive_group'] && $group['nbUsersActifs'] < 3) {
                continue;
            }
            $group['nbUsersTotal'] = $this->users->count_by_group($group['groupID']);
            $groupsView[] = $group;
        }

        $rank = 1;
        $last_team = (isset($groups[0])) ? $groups[0] : "";
        usort($groupsView, "compare_groups");
        for ($i = 0; $i < sizeof($groupsView); $i++) {
            if (compare_groups($groupsView[$i], $last_team) != 0)
                $rank = $i + 1;
            $groupsView[$i]['rank'] = $rank;

            $evol = $groupsView[$i]['lastRank'] - $rank;

            if ($evol == 0) {
                $img = "egal.png";
                $rank_class = "still";
            } elseif ($evol > 5) {
                $img = "arrow_up2.png";
                $rank_class = "high_increase";
            } elseif ($evol > 0) {
                $rank_class = "increase";
                $img = "arrow_up1.png";
            } elseif ($evol < -5) {
                $rank_class = "high_drop";
                $img = "arrow_down2.png";
            } elseif ($evol < 0) {
                $img = "arrow_down1.png";
                $rank_class = "drop";
            }
            if ($evol > 0) {
                $evol = "+" . $evol;
            }

            $this->template->assign_block_vars('teams', array(
                'RANK' => $rank,
                'EVOLUTION' => ($img) ? "($evol)" : "",
                'RANK_CLASS' => ($img) ? $rank_class : "",
                'LAST_RANK' => "<img src=\"" . $this->template_web_location . "/images/" . $img . "\" /><br/><span style=\"text-align:center;font-size:70%;\">(" . $evol . ")</span>",
                'NAME' => $groupsView[$i]['name'],
                'GROUP_ID' => $groupsView[$i]['groupID'],
                'NB_ACTIFS' => $groupsView[$i]['nbUsersActifs'],
                'NB_TOTAL' => $groupsView[$i]['nbUsersTotal'],
                'AVG_POINTS' => $groupsView[$i]['avgPoints'],
                'TOTAL_POINTS' => $groupsView[$i]['totalPoints'],
                'MAX_POINTS' => $groupsView[$i]['maxPoints'],
                'COLOR' => (($_SESSION['group_id'] == $groupsView[$i]['groupID']) || ($_SESSION['group_id2'] == $groupsView[$i]['groupID']) || ($_SESSION['group_id3'] == $groupsView[$i]['groupID'])) ? "#FFDA9A" : ""
            ));

            $last_team = $groupsView[$i];
        }

        $this->blocks_loaded[] = 'groups_ranking';
    }

    /*     * **************** */

    function load_users_ranking_by_group($groupID)
    {
        $this->template->set_filenames(array(
            'users_ranking_by_group' => 'users_ranking_by_group.tpl'
        ));

        $nb_bets = $this->bets->count_by_users();
        $users = $this->users->get_by_group($groupID, $this->config['show_all_users_in_team']);
        $group = $this->groups->get($groupID);
        if (!$group)
            return false;
        $nbMatchesPlayed = $this->matches->count_played();
        $nb_matches = $this->settings->get_value('NB_MATCHES_GENERATED');
        if ($nb_matches == "")
            $nb_matches = 0;
        $last_pool_match = $this->matches->get_last_pool();

        $this->template->assign_vars(array(
            'GROUP_ID' => $groupID,
            'GROUP_NAME' => $group['name'],
            'NB_USERS' => $this->users->count_by_group($groupID),
            'NB_MATCHES' => ($nb_matches > 1) ? $nb_matches . " matches" : $nb_matches . " match",
            'NB_ACTIVE_USERS' => $this->users->count_active_by_group($groupID),
            'LABEL_TEAMS_RANKING' => $this->lang['LABEL_TEAMS_RANKING']
        ));

        $ses_g1 = ($_SESSION['group_name'] != "");
        $ses_g2 = ($_SESSION['group_name2'] != "");
        $ses_g3 = ($_SESSION['group_name3'] != "");

        if ($ses_g1) {
            $this->template->assign_block_vars('g1', array(
                'GROUP_ID' => $_SESSION['group_id'],
                'GROUP_NAME' => ($groupID == $_SESSION['group_id']) ? "<strong>" . $_SESSION['group_name'] . "</strong>" : $_SESSION['group_name'],
            ));
        }

        if ($ses_g2) {
            $this->template->assign_block_vars('g2', array(
                'GROUP_ID2' => $_SESSION['group_id2'],
                'GROUP_NAME2' => ($groupID == $_SESSION['group_id2']) ? "<strong>" . $_SESSION['group_name2'] . "</strong>" : $_SESSION['group_name2'],
            ));
        }

        if ($ses_g3) {
            $this->template->assign_block_vars('g3', array(
                'GROUP_ID3' => $_SESSION['group_id3'],
                'GROUP_NAME3' => ($groupID == $_SESSION['group_id3']) ? "<strong>" . $_SESSION['group_name3'] . "</strong>" : $_SESSION['group_name3'],
            ));
        }

        $group = $this->groups->get_by_name($this->config['money_group_name']);
        if ($group && $this->users->is_in_group($group['groupID'])) {
            $this->template->assign_block_vars('money', array(
                'AMOUNT' => $this->settings->get_value('MONEY')
            ));
        }


        $nb_played_bets = $this->bets->count_played_by_users();
        usort($users, "compare_users");

        $i = 1;
        $j = 0;
        $last_user = (isset($users[0])) ? $users[0] : false;

        foreach ($users as $user) {
            if (($nb_bets[$user['userID']] == 0) && (!$this->config['show_all_users_in_team']))
                continue;
            if (compare_users($user, $last_user) != 0)
                $i = $j + 1;

            if ($this->bets->count_played_by_user($user['userID']) > 0) {
                if ($user['last_rank'] == '')
                    $user['last_rank'] = 1;
                $evol = $user['last_rank'] - $i;
                if ($evol == 0)
                    $img = "egal.png";
                elseif ($evol > 5)
                    $img = "arrow_up2.png";
                elseif ($evol > 0)
                    $img = "arrow_up1.png";
                elseif ($evol < -5)
                    $img = "arrow_down2.png";
                elseif ($evol < 0)
                    $img = "arrow_down1.png";
                if ($evol > 0)
                    $evol = "+" . $evol;
            } else {
                $img = false;
                $i = null;
            }

            if ($_SESSION['userID'] == $user['userID']) {
                if ($last_pool_match['delay_sec'] > 0) {
                    $user_url = "<a href=\"/?act=bets\">";
                } else {
                    $user_url = "<a href=\"/?act=finals_bets\">";
                }
            } else {
                if ($last_pool_match['delay_sec'] > 0) {
                    $user_url = "<a href=\"/?act=view_bets&user=" . $user['userID'] . "\">";
                } else {
                    $user_url = "<a href=\"/?act=view_finals_bets&user=" . $user['userID'] . "\">";
                }
            }

            $this->template->assign_block_vars('users', array(
                'RANK' => $i,
                'LAST_RANK' => ($img) ? "<img src=\"" . $this->template_web_location . "/images/" . $img . "\" /><br/><span style=\"text-align:center;font-size:70%;\">(" . $evol . ")</span>" : "",
                'NB_MISS_BETS' => (isset($nb_played_bets[$user['userID']]) && ($nb_played_bets[$user['userID']] < $nb_matches)) ? "(<span style=\"color:grey;\">-" . ($nb_matches - $nb_played_bets[$user['userID']]) . "</span>)" : "",
                'ID' => $user['userID'],
                'NAME' => $user['name'],
                'GROUP' => $user['group_name'],
                'VIEW_BETS' => $user_url,
                'POINTS' => $user['points'],
                'NBRESULTS' => $user['nbresults'],
                'NBSCORES' => $user['nbscores'],
                'DIFF' => $user['diff'],
                'STATUS' => $user['status'],
                'COLOR' => ($_SESSION['userID'] == $user['userID']) ? "#FFDA9A" : ((($nbMatchesPlayed > 0) && ($this->bets->count_played_by_user($user['userID']) / $nbMatchesPlayed) <= $this->config['min_ratio_played_matches_for_group']) ? "#E6E6E6" : "")
            ));
            $last_user = $user;
            $j++;
        }

        $this->blocks_loaded[] = 'users_ranking_by_group';
    }

    /*     * **************** */

    function load_tags($groupID = false, $start = false)
    {
        $this->template->set_filenames(array(
            'tags' => 'tags.tpl'
        ));

        $start = $start ? $start : 0;

        $tags = [];
        if (($groupID && $this->users->is_in_group($groupID) > 0) || ($groupID === 0)) {
            $tags = $this->tags->get_by_group($groupID, $start, 10);
        }

        $nb_tags = $this->tags->count_by_group($groupID);
        $max = ceil($nb_tags / 10);
        $page = ceil(($start + 1) / 10);
        $prev = ($page - 2) * 10;
        $next = $page * 10;

        if ($max <= 1) {
            $navig = "";
        } elseif ($page == 1) {
            $navig = "<strong><a href=\"#\" onclick=\"getTags($groupID, $next);\">>></a></strong>";
        } elseif (($page > 1) && ($page < $max)) {
            $navig = "<strong><a href=\"#\" onclick=\"getTags($groupID, $prev);\"><<</a> <a href=\"#\" onclick=\"getTags($groupID , $next);\">>></a></strong>";
        } elseif ($page == $max) {
            $navig = "<strong><a href=\"#\" onclick=\"getTags($groupID, $prev);\"><<</a></strong>";
        }

        foreach ($tags as $tag) {
            if ($tag['userID'] == $_SESSION['userID'] || $this->isadmin()) {
                $del_img = "<a href=\"#\"><img src=\"" . $this->template_web_location . "images/del.png\" onclick=\"delTag(" . $tag['tagID'] . ", '" . $groupID . "');\" border=\"0\"/></a>";
            } else {
                $del_img = "";
            }

            $tag_str = stripslashes($tag['tag']);

            $this->template->assign_block_vars('tags', array(
                'ID' => $tag['tagID'],
                'DEL_IMG' => $del_img,
                'DATE' => $tag['date_str'],
                'USER' => $tag['name'],
                'TEXT' => $tag_str
            ));
        }
        $this->template->assign_vars(array(
            'NAVIG' => $navig,
            'TAG_SEPARATOR' => $this->config['tag_separator']
        ));

        $this->blocks_loaded[] = 'tags';
    }

    /*     * **************** */

    function load_matches($matches, $lastMonth = false, $lastDay = false, $lastPool = false)
    {
        $this->template->set_filenames(array(
            'matches' => 'matches.tpl'
        ));

        $pools_matches = [];
        $rounds_matches = [];

        foreach ($matches as $match) {
            if ($match['round'] != NULL) {
                $r = $match['round'];
                $m = array(
                    'ID' => $match['matchID'],
                    'DATE' => $match['date_str'],
                    'TEAM_NAME_A' => $match['teamAname'],
                    'TEAM_NAME_B' => $match['teamBname'],
                    'RANK' => $match['rank']
                );
                if (!isset($rounds_matches[$r]))
                    $rounds_matches[$r] = [];
                array_push($rounds_matches[$r], $m);
            } else {
                $p = $match['teamPool'];
                $m = array(
                    'ID' => $match['matchID'],
                    'DATE' => $match['date_str'],
                    'TEAM_NAME_A' => $match['teamAname'],
                    'TEAM_NAME_B' => $match['teamBname'],
                    'POOL' => $match['teamPool']
                );
                if (!isset($pools_matches[$p]))
                    $pools_matches[$p] = [];
                array_push($pools_matches[$p], $m);
            }
        }

        /* Dates pour formulaire de saisie */

        $this->template->assign_vars(array(
            'YEAR' => date('Y'),
            'LAST_POOL' => $lastPool ? $lastPool : ""
        ));

        for ($i = 1; $i <= 8; $i++) {
            $this->template->assign_block_vars('rank', array(
                'RANK' => $i
            ));
        }

        for ($i = 1; $i <= 31; $i++) {
            $selected = "";
            if ($lastDay == $i) {
                $selected = "selected=\"selected\"";
            }
            $this->template->assign_block_vars('days', array(
                'DAY' => $i,
                'SELECTED' => $selected
            ));
        }

        for ($i = 6; $i <= 7; $i++) {
            $selected = "";
            if ($lastMonth == $i) {
                $selected = "selected=\"selected\"";
            }
            $this->template->assign_block_vars('months', array(
                'NAME' => $this->lang['months'][$i - 1],
                'VALUE' => $i,
                'SELECTED' => $selected
            ));
        }

        /* Liste des matches */
        $pools = $this->config['pools'];
        foreach ($pools as $pool) {
            $this->template->assign_block_vars('pools', array(
                'NAME' => $pool,
                'VALUE' => $pool
            ));
            if (isset($pools_matches[$pool])) {
                $matches = $pools_matches[$pool];
            } else {
                $matches = [];
            }
            foreach ($matches as $match) {
                $this->template->assign_block_vars('pools.matches', $match);
            }
        }

        $rounds = $this->config['rounds'];

        foreach ($rounds as $round) {
            $this->template->assign_block_vars('rounds', array(
                'NAME' => $this->lang['LABEL_' . $round . '_FINAL'],
                'VALUE' => $round
            ));
            if (isset($rounds_matches[$round]))
                $matches = $rounds_matches[$round];
            else
                $matches = [];
            foreach ($matches as $match) {
                $this->template->assign_block_vars('rounds.matches', $match);
            }
        }

        $this->blocks_loaded[] = 'matches';
    }

    /*     * **************** */

    function load_finals_results($edit = false)
    {
        if ($edit) {
            $this->template->set_filenames(array(
                'edit_finals_results' => 'edit_finals_results.tpl'
            ));
        } else {
            $this->template->set_filenames(array(
                'view_finals_results' => 'view_finals_results.tpl'
            ));
        }

        $array_template = [];
        $rounds = $this->config['rounds'];
        $teams = array('A', 'B');

        $array_template_extra = [];
        $this->template->assign_block_vars('finals', []);

        $this->template->assign_vars(array(
            'UPDATE_RANK_LINK' => ($this->users->is_ranking_ok()) ? "<b><a href=\"#\" onclick=\"updateRanking(0)\">Classement obsoléte.</a><b>" : "<b>Classement à jour.</b>"
        ));

        /* ROUND */

        foreach ($rounds as $round) {

            $round_name = $this->lang['LABEL_' . $round . '_FINAL'];

            $this->template->assign_block_vars('finals.rounds', array(
                'ROUND' => $round,
                'NAME' => $round_name
            ));

            if ($round == 3)
                $j = 1;
            else
                $j = $round;

            if (($round != 3) || (!in_array(3, $rounds))) {
                $this->template->assign_block_vars('finals.rounds.merge_top', []);
            }
            if (($round != 1) || (!in_array(3, $rounds))) {
                $this->template->assign_block_vars('finals.rounds.merge_bottom', []);
            }


            /* RANK */

            for ($i = 1; $i <= $j; $i++) {

                $match = $this->matches->get_final($round, $i);
                if (empty($match))
                    continue;

                if ($round == 8) {
                    $height_top = 0;
                    $height_bottom = 0;
                }
                if ($round == 4) {
                    $height_top = 62;
                    $height_bottom = 62;
                }
                if ($round == 2) {
                    $height_top = 157;
                    $height_bottom = 157;
                }
                if ($round == 1) {
                    $height_top = 345;
                    $height_bottom = (in_array(3, $rounds)) ? 190 : 345;
                }
                if ($round == 3) {
                    $height_top = 0;
                    $height_bottom = 35;
                }

                $this->template->assign_block_vars('finals.rounds.ranks', array(
                    'RANK' => $i,
                    'DATE' => "<i>" . $match['date_str'] . "</i>",
                    'MATCH_ID' => $match['matchID'],
                    'TEAM_W' => (isset($match['teamW'])) ? $match['teamW'] : "",
                    'HEIGHT_TOP' => $height_top,
                    'HEIGHT_BOTTOM' => $height_bottom
                ));

                $teamA = (isset($match['teamA']) && $match['teamA'] != "" && $match['teamA'] != NULL) ? $this->teams->get($match['teamA']) : "";
                $teamB = (isset($match['teamB']) && $match['teamB'] != "" && $match['teamB'] != NULL) ? $this->teams->get($match['teamB']) : "";
                $match['teamAname'] = (isset($teamA['name'])) ? $teamA['name'] : "";
                $match['teamBname'] = (isset($teamB['name'])) ? $teamB['name'] : "";
                if (!isset($match['scoreA']))
                    $match['scoreA'] = NULL;
                if (!isset($match['scoreB']))
                    $match['scoreB'] = NULL;
                $color = [];
                $color['A'] = (isset($match['teamW']) && $match['teamW'] == 'A') ? "#99FF99" : "#F9F9F9";
                $color['B'] = (isset($match['teamW']) && $match['teamW'] == 'B') ? "#99FF99" : "#F9F9F9";

                foreach ($teams as $team) {
                    $teamName = (isset($match['team' . $team . 'name'])) ? $match['team' . $team . 'name'] : "";
                    $this->template->assign_block_vars('finals.rounds.ranks.teams', array(
                        'TEAM' => $team,
                        'ID' => (isset($match['team' . $team])) ? $match['team' . $team] : "",
                        'NAME' => $teamName,
                        'COLOR' => $color[$team],
                        'IMG' => "&nbsp;<img width=\"15px\" src=\"" . $this->template_web_location . "images/flag/" . ($this->config['force_encoding_fs'] ? rawurlencode(utf8_decode($teamName)) : rawurlencode($teamName)) . ".png\" />",
                        'SCORE' => (isset($match['score' . $team])) ? $match['score' . $team] : ""
                    ));

                    if ($team == 'B' && $round != 1 && $round != 3) {
                        if ($i % 2 == 1)
                            $this->template->assign_block_vars('finals.rounds.ranks.teams.top_line', []);
                        else
                            $this->template->assign_block_vars('finals.rounds.ranks.bottom_line', []);
                    }
                }
            }
        }

        if ($edit)
            $this->blocks_loaded[] = 'edit_finals_results';
        else
            $this->blocks_loaded[] = 'view_finals_results';
    }

    /*     * **************** */

    function load_results($edit)
    {
        if ($edit) {
            $this->template->set_filenames(array(
                'edit_results' => 'edit_results.tpl'
            ));
        } else {
            $this->template->set_filenames(array(
                'view_results' => 'view_results.tpl'
            ));
        }
        $pools = $this->config['pools'];

        $this->template->assign_vars(array(
            'UPDATE_RANK_LINK' => ($this->users->is_ranking_ok()) ? "<b><a href=\"#\" onClick=\"javascript:updateRanking(0);\">Classement obsoléte.</a><b>" : "<b>Classement à jour.</b>"
        ));

        foreach ($pools as $pool) {

            $this->template->assign_block_vars('pools', array(
                'POOL' => $pool
            ));

            if ($edit) {
                $matches = $this->matches->get_by_pool($pool);
            } else {
                $matches = $this->matches->get_by_pool_before_now($pool);
                $next_matches = $this->matches->get_next_by_pool($pool);
                if (!$next_matches)
                    continue;

                $delay = $next_matches[0]['delay_sec'];
                $delay_str = "";

                $delay_days = floor($delay / (60 * 60 * 24));
                $delay = $delay - $delay_days * 24 * 60 * 60;
                if ($delay_days > 0)
                    $delay_str .= $delay_days . "j ";

                $delay_hour = floor($delay / (60 * 60));
                $delay = $delay - $delay_hour * 60 * 60;
                if ($delay_hour > 0)
                    $delay_str .= $delay_hour . "h ";

                $delay_min = floor($delay / (60));
                if ($delay_min > 0)
                    $delay_str .= $delay_min . "m";

                $this->template->assign_block_vars('pools.next_matches', array(
                    'DATE' => $next_matches[0]['date_str'],
                    'DELAY' => $delay_str
                ));

                foreach ($next_matches as $next_match) {
                    $this->template->assign_block_vars('pools.next_matches.list', array(
                        'TEAM_NAME_A' => $next_match['teamAname'],
                        'TEAM_NAME_B' => $next_match['teamBname']
                    ));
                }
            }
            $teams = $this->teams->get_by_pool($pool);

            foreach ($matches as $match) {
                $this->template->assign_block_vars('pools.matches', array(
                    'ID' => $match['matchID'],
                    'DATE' => $match['date_str'],
                    'TEAM_NAME_A' => $match['teamAname'],
                    'TEAM_NAME_B' => $match['teamBname'],
                    'SCORE_A' => (is_numeric($match['scoreA'])) ? $match['scoreA'] : "",
                    'SCORE_B' => (is_numeric($match['scoreB'])) ? $match['scoreB'] : "",
                    'TEAM_COLOR_A' => ($match['scoreA'] > $match['scoreB']) ? "#99FF99" : "transparent",
                    'TEAM_COLOR_B' => ($match['scoreB'] > $match['scoreA']) ? "#99FF99" : "transparent",
                    'TEAM_NAME_A_URL' => $this->config['force_encoding_fs'] ? rawurlencode(utf8_decode($match['teamAname'])) : rawurlencode($match['teamAname']),
                    'TEAM_NAME_B_URL' => $this->config['force_encoding_fs'] ? rawurlencode(utf8_decode($match['teamBname'])) : rawurlencode($match['teamBname']),
                    'POOL' => $match['teamPool']
                ));
            }

            if (count($matches) > 0) {
                $array_teams = $this->teams->get_ranking($teams, $matches, 'score');

                foreach ($array_teams as $team) {
                    $this->template->assign_block_vars('pools.teams', array(
                        'ID' => $team['teamID'],
                        'NAME' => $team['name'],
                        'NAME_URL' => $this->config['force_encoding_fs'] ? rawurlencode(utf8_decode($team['name'])) : rawurlencode($team['name']),
                        'POINTS' => $team['points'],
                        'DIFF' => (($team['diff'] > 0) ? "+" : "") . $team['diff']
                    ));
                }
            }
        }
        if ($edit) {
            $this->blocks_loaded[] = 'edit_results';
        } else {
            $this->blocks_loaded[] = 'view_results';
        }
    }

    /*     * **************** */

    function load_finals_odds()
    {
        $this->template->set_filenames(array(
            'view_finals_odds' => 'view_finals_odds.tpl'
        ));

        $array_template = [];
        $rounds = $this->config['rounds'];
        $teams = array('A', 'B');

        $array_template_extra = [];
        $this->template->assign_block_vars('finals', []);

        $odds_teams = [];

        /* ROUND */
        foreach ($rounds as $round) {
            $round_name = $this->lang['LABEL_' . $round . '_FINAL'];

            $this->template->assign_block_vars('finals.rounds', [
                'ROUND' => $round,
                'NAME' => $round_name
            ]);

            if ($round == 3) {
                $j = 1;
            } else {
                $j = $round;
            }

            if (($round != 3) || (!in_array(3, $rounds))) {
                $this->template->assign_block_vars('finals.rounds.merge_top', []);
            }
            if (($round != 1) || (!in_array(3, $rounds))) {
                $this->template->assign_block_vars('finals.rounds.merge_bottom', []);
            }


            /* RANK */
            for ($rank = 1; $rank <= $j; $rank++) {
                $match = $this->matches->get_final($round, $rank);
                if (!$match)
                    continue;

                if ($round == 8) {
                    $height_top = 0;
                    $height_bottom = 0;
                }
                if ($round == 4) {
                    $height_top = 88;
                    $height_bottom = 88;
                }
                if ($round == 2) {
                    $height_top = 237;
                    $height_bottom = 237;
                }
                if ($round == 1) {
                    $height_top = 525;
                    $height_bottom = (in_array(3, $rounds)) ? 335 : 525;
                }
                if ($round == 3) {
                    $height_top = 0;
                    $height_bottom = 35;
                }

                $bets = $this->bets->get_by_match($match['matchID']);
                if (isset($odds_teams[$round][$rank]['A']) && isset($odds_teams[$round][$rank]['B'])) {
                    $odds = $this->bets->get_odds_by_match($match['matchID'], $odds_teams[$round][$rank]['A'], $odds_teams[$round][$rank]['B']);
                } else {
                    $odds = $this->bets->get_odds_by_match($match['matchID']);
                }

                /* Resultats du match, si joué */
                $match_result = "";
                if ($match['scoreA'] > $match['scoreB'] || $match['teamW'] == 'A')
                    $match_result = 'A';
                elseif ($match['scoreA'] < $match['scoreB'] || $match['teamW'] == 'B')
                    $match_result = 'B';
                elseif ($match['scoreA'] == $match['scoreB'] && $match['teamW'] == 'A')
                    $match_result = 'A';
                elseif ($match['scoreA'] == $match['scoreB'] && $match['teamW'] == 'B')
                    $match_result = 'B';
                if (($match['scoreA'] == NULL) && ($match['scoreB'] == NULL))
                    $match_result = "";

                /* Resultats des paris */
                $bets_result = '';
                if ($odds['B_WINS'] > $odds['A_WINS'])
                    $bets_result = 'A';
                elseif ($odds['B_WINS'] < $odds['A_WINS'])
                    $bets_result = 'B';
                elseif ($odds['B_WINS'] == $odds['A_WINS'] && $odds['A_AVG'] > $odds['B_AVG'])
                    $bets_result = 'A';
                elseif ($odds['B_WINS'] == $odds['A_WINS'] && $odds['A_AVG'] < $odds['B_AVG'])
                    $bets_result = 'B';

                if (($match_result != "") && ($round != 1 && $round != 3)) {
                    $next_round = ceil($round / 2);
                    $next_rank = ceil($rank / 2);
                    $next_team = (is_float($rank / 2)) ? "A" : "B";
                    if ((isset($odds_teams[$round][$rank][$bets_result])) && ((($match['scoreA'] == NULL) && ($match['scoreB'] == NULL)) || (($match['scoreA'] == "") && ($match['scoreB'] == "")))) {
                        $next_teamID = $odds_teams[$round][$rank][$bets_result];
                    } else {
                        $next_teamID = $match["team" . $match_result];
                    }
                    $odds_teams[$next_round][$next_rank][$next_team] = $next_teamID;
                }

                if ($round == 2) {
                    $bets_looser = ($bets_result == 'B') ? 'A' : 'B';
                    $next_team = (is_float($rank / 2)) ? "A" : "B";
                    if (isset($odds_teams[$round][$rank][$bets_looser])) {
                        $next_teamID = $odds_teams[$round][$rank][$bets_looser];
                    } else {
                        $next_teamID = $match["team" . $bets_looser];
                    }
                    $odds_teams[3][1][$next_team] = $next_teamID;
                }

                /* Stats paris */
                $exact_bets = $this->bets->get_by_match($match['matchID'], EXACT_SCORE);
                $good_bets = $this->bets->get_by_match($match['matchID'], GOOD_RESULT);
                $nb_exact_bets = count($exact_bets);
                $nb_good_bets = count($good_bets);
                $str_exact_bets = "";
                $str_good_bets = "";
                if ($match_result != "") {
                    if ($nb_exact_bets == 0)
                        $str_exact_bets = "aucun score exact";
                    if ($nb_good_bets == 0)
                        $str_good_bets = "aucun bon résultat";
                    if ($nb_exact_bets == 1)
                        $str_exact_bets = "1 score exact";
                    if ($nb_good_bets == 1)
                        $str_good_bets = "1 bon résultat";
                    if ($nb_exact_bets > 1)
                        $str_exact_bets = $nb_exact_bets . " scores exacts";
                    if ($nb_good_bets > 1)
                        $str_good_bets = $nb_good_bets . " bons résultats";
                }

                $this->template->assign_block_vars('finals.rounds.ranks', array(
                    'RANK' => $rank,
                    'DATE' => "<em>" . $match['date_str'] . "</em>",
                    'MATCH_ID' => $match['matchID'],
                    'TEAM_W' => (isset($bet['teamW'])) ? $bet['teamW'] : "",
                    'HEIGHT_TOP' => $height_top,
                    'HEIGHT_BOTTOM' => $height_bottom,
                    'EXACT_BETS' => "<strong>$str_exact_bets</strong>",
                    'GOOD_BETS' => $str_good_bets
                ));

                foreach ($good_bets as $good_bet) {
                    $this->template->assign_block_vars('finals.rounds.ranks.good_bets', array(
                        'USERID' => $good_bet['userID'],
                        'NAME' => $good_bet['username']
                    ));
                }

                foreach ($exact_bets as $exact_bet) {
                    $this->template->assign_block_vars('finals.rounds.ranks.exact_bets', array(
                        'USERID' => $exact_bet['userID'],
                        'NAME' => $exact_bet['username']
                    ));
                }

                $color = [];
                $result = $match_result == '' ? $bets_result : $match_result;
                $color['A'] = ($result == 'A') ? "#99FF99" : "#F9F9F9";
                $color['B'] = ($result == 'B') ? "#99FF99" : "#F9F9F9";

                foreach ($teams as $team) {
                    if (isset($odds_teams[$round][$rank][$team]) && ((($match['scoreA'] == "") && ($match['scoreB'] == "")) || (($match['scoreA'] == NULL) && ($match['scoreB'] == NULL))) && ($round < 4)) {
                        $bets_team = $this->teams->get($odds_teams[$round][$rank][$team]);
                    } else {
                        $bets_team = $this->teams->get($match['team' . $team]);
                    }

                    $this->template->assign_block_vars('finals.rounds.ranks.teams', array(
                        'TEAM' => $team,
                        'ID' => (isset($bets_team['teamID'])) ? $bets_team['teamID'] : "",
                        'NAME' => (isset($bets_team['name'])) ? $bets_team['name'] : "",
                        'COLOR' => $color[$team],
                        'IMG' => (isset($bets_team['name']) && $bets_team['name'] != "") ? "&nbsp;<img width=\"15px\" src=\"" . $this->template_web_location . "images/flag/" . ($this->config['force_encoding_fs'] ? rawurlencode(utf8_decode($bets_team['name'])) : rawurlencode($bets_team['name'])) . ".png\" />" : "",
                        'SCORE' => (isset($match['score' . $team])) ? $match['score' . $team] : "",
                        'AVG' => $odds[$team . '_AVG'],
                        'ODD' => ($match_result == $team) ? "<b>" . $odds[$team . '_WINS'] . "/1</b>" : $odds[$team . '_WINS'] . "/1",
                    ));

                    if ($team == 'B' && $round != 1 && $round != 3) {
                        if ($rank % 2 == 1)
                            $this->template->assign_block_vars('finals.rounds.ranks.teams.top_line', []);
                        else
                            $this->template->assign_block_vars('finals.rounds.ranks.bottom_line', []);
                    }
                }
            }
        }

        $this->blocks_loaded[] = 'view_finals_odds';
    }

    function load_match_stats($matchID)
    {
        $this->template->set_filenames(array(
            'match_stats' => 'match_stats.tpl'
        ));
        $match = $this->matches->get($matchID);

        $is_match_open = $this->matches->is_open($matchID);
        $is_match_finished = (($match['scoreA'] != "") && ($match['scoreB'] != ""));

        $odds = $this->bets->get_odds_by_match($matchID);
        $match_result = "";
        $str_exact_bets = "";
        $str_good_bets = "";

        if (!$is_match_finished && !$is_match_open) {
            $score_bets = $this->bets->get_by_match_group_by_score($matchID);
            $nb_bets = $score_bets['count'];
            foreach (array('A', 'N', 'B') as $score_type) {
                foreach ($score_bets[$score_type] as $score => $score_bet) {
                    $this->template->assign_block_vars('score_' . $score_type, array(
                        'POURCENTAGE' => round(($score_bet['count'] / $nb_bets) * 100),
                        'SCORE' => $score
                    ));
                    foreach ($score_bet['users'] as $user) {
                        $this->template->assign_block_vars('score_' . $score_type . '.users', array(
                            'ID' => $user['ID'],
                            'NAME' => $user['NAME']
                        ));
                    }
                }
            }
        }
        if ($is_match_finished) {
            if ($match['scoreA'] > $match['scoreB'])
                $match_result = 'A';
            if ($match['scoreA'] < $match['scoreB'])
                $match_result = 'B';
            if ($match['scoreA'] == $match['scoreB'])
                $match_result = 'N';

            $exact_bets = $this->bets->get_by_match($matchID, EXACT_SCORE);
            $good_bets = $this->bets->get_by_match($matchID, GOOD_RESULT);

            $nb_exact_bets = count($exact_bets);
            $nb_good_bets = count($good_bets);

            if ($match_result != "") {
                if ($nb_exact_bets == 0)
                    $str_exact_bets = "aucun score exact";
                if ($nb_good_bets == 0)
                    $str_good_bets = "aucun bon résultat";
                if ($nb_exact_bets == 1)
                    $str_exact_bets = "1 score exact";
                if ($nb_good_bets == 1)
                    $str_good_bets = "1 bon résultat";
                if ($nb_exact_bets > 1)
                    $str_exact_bets = $nb_exact_bets . " scores exacts";
                if ($nb_good_bets > 1)
                    $str_good_bets = $nb_good_bets . " bons résultats";
            }

            if (($match['scoreA'] != "") && ($match['scoreB'] != "")) {
                $this->template->assign_block_vars('avg', array(
                    'A' => $odds['A_AVG'],
                    'B' => $odds['B_AVG'],
                ));
            }
            foreach ($good_bets as $good_bet) {
                $this->template->assign_block_vars('good_bets', array(
                    'USERID' => $good_bet['userID'],
                    'NAME' => $good_bet['username']
                ));
            }

            foreach ($exact_bets as $exact_bet) {
                $this->template->assign_block_vars('exact_bets', array(
                    'USERID' => $exact_bet['userID'],
                    'NAME' => $exact_bet['username']
                ));
            }
        }

        $this->template->assign_vars(array(
            'TPL_WEB_PATH' => $this->template_web_location,
            'ID' => $matchID,
            'NAME' => ($match['round'] > 0) ? $this->lang['LABEL_' . $match['round'] . '_FINAL'] : "Poule " . $match['teamPool'],
            'DATE' => $match['date_str'],
            'TEAM_NAME_A' => $match['teamAname'],
            'TEAM_NAME_B' => $match['teamBname'],
            'SCORE_A' => ($match['scoreA'] != "") ? $match['scoreA'] : $odds['A_AVG'],
            'SCORE_B' => ($match['scoreB'] != "") ? $match['scoreB'] : $odds['B_AVG'],
            'COLOR' => (($match['scoreA'] == "") && ($match['scoreB'] == "")) ? "blue" : "black",
            'ODD_A' => ($odds['A_WINS'] != null) ? (($match_result == 'A') ? "<b>" . $odds['A_WINS'] . "/1</b>" : $odds['A_WINS'] . "/1") : "",
            'ODD_B' => ($odds['B_WINS'] != null) ? (($match_result == 'B') ? "<b>" . $odds['B_WINS'] . "/1</b>" : $odds['B_WINS'] . "/1") : "",
            'ODD_NUL' => ($odds['NUL'] != null) ? (($match_result == 'N') ? "<b>" . $odds['NUL'] . "/1</b>" : $odds['NUL'] . "/1") : "",
            'TEAM_COLOR_A' => ($match['scoreA'] > $match['scoreB']) ? "#99FF99" : "transparent",
            'TEAM_COLOR_B' => ($match['scoreB'] > $match['scoreA']) ? "#99FF99" : "transparent",
            'TEAM_NAME_A_URL' => $this->config['force_encoding_fs'] ? rawurlencode(utf8_decode($match['teamAname'])) : rawurlencode($match['teamAname']),
            'TEAM_NAME_B_URL' => $this->config['force_encoding_fs'] ? rawurlencode(utf8_decode($match['teamBname'])) : rawurlencode($match['teamBname']),
            'EXACT_BETS' => "<b>" . $str_exact_bets . "</b>",
            'GOOD_BETS' => $str_good_bets,
            'POOL' => $match['teamPool']
        ));

        $this->blocks_loaded[] = 'match_stats';
    }

    function load_odds()
    {
        $this->template->set_filenames(array(
            'view_odds' => 'view_odds.tpl'
        ));

        $pools = $this->config['pools'];

        foreach ($pools as $pool) {

            $this->template->assign_block_vars('pools', array(
                'POOL' => $pool
            ));

            $matches = $this->matches->get_by_pool($pool);
            $teams = $this->teams->get_by_pool($pool);

            foreach ($matches as $k => $match) {
                $odds = $this->bets->get_odds_by_match($match['matchID']);

                /* Resultats du match, si joué */
                $match_result = "";
                if ($match['scoreA'] > $match['scoreB'])
                    $match_result = 'A';
                if ($match['scoreA'] < $match['scoreB'])
                    $match_result = 'B';
                if ($match['scoreA'] == $match['scoreB'])
                    $match_result = 'N';
                if (($match['scoreA'] == NULL) && ($match['scoreB'] == NULL))
                    $match_result = "";

                /* Stats paris */
                $exact_bets = $this->bets->get_by_match($match['matchID'], EXACT_SCORE);
                $good_bets = $this->bets->get_by_match($match['matchID'], GOOD_RESULT);
                $nb_exact_bets = count($exact_bets);
                $nb_good_bets = count($good_bets);
                $str_exact_bets = "";
                $str_good_bets = "";
                if ($match_result != "") {
                    if ($nb_exact_bets == 0)
                        $str_exact_bets = "aucun score exact";
                    if ($nb_good_bets == 0)
                        $str_good_bets = "aucun bon résultat";
                    if ($nb_exact_bets == 1)
                        $str_exact_bets = "1 score exact";
                    if ($nb_good_bets == 1)
                        $str_good_bets = "1 bon résultat";
                    if ($nb_exact_bets > 1)
                        $str_exact_bets = $nb_exact_bets . " scores exacts";
                    if ($nb_good_bets > 1)
                        $str_good_bets = $nb_good_bets . " bons résultats";
                }

                $bets[$k]['scoreOddA'] = round($odds['A_AVG']);
                $bets[$k]['scoreOddB'] = round($odds['B_AVG']);

                $this->template->assign_block_vars('pools.matches', array(
                    'ID' => $match['matchID'],
                    'DATE' => $match['date_str'],
                    'TEAM_NAME_A' => $match['teamAname'],
                    'TEAM_NAME_B' => $match['teamBname'],
                    'SCORE_A' => $match['scoreA'],
                    'SCORE_B' => $match['scoreB'],
                    'AVG_A' => $odds['A_AVG'],
                    'AVG_B' => $odds['B_AVG'],
                    'ODD_A' => ($odds['A_WINS'] != null) ? (($match_result == 'A') ? "<b>" . $odds['A_WINS'] . "/1</b>" : $odds['A_WINS'] . "/1") : "",
                    'ODD_B' => ($odds['B_WINS'] != null) ? (($match_result == 'B') ? "<b>" . $odds['B_WINS'] . "/1</b>" : $odds['B_WINS'] . "/1") : "",
                    'ODD_NUL' => ($odds['NUL'] != null) ? (($match_result == 'N') ? "<b>" . $odds['NUL'] . "/1</b>" : $odds['NUL'] . "/1") : "",
                    'TEAM_COLOR_A' => ($match['scoreA'] > $match['scoreB']) ? "#99FF99" : "transparent",
                    'TEAM_COLOR_B' => ($match['scoreB'] > $match['scoreA']) ? "#99FF99" : "transparent",
                    'TEAM_NAME_A_URL' => $this->config['force_encoding_fs'] ? rawurlencode(utf8_decode($match['teamAname'])) : rawurlencode($match['teamAname']),
                    'TEAM_NAME_B_URL' => $this->config['force_encoding_fs'] ? rawurlencode(utf8_decode($match['teamBname'])) : rawurlencode($match['teamBname']),
                    'EXACT_BETS' => "<b>" . $str_exact_bets . "</b>",
                    'GOOD_BETS' => $str_good_bets,
                    'POOL' => $match['teamPool']
                ));

                foreach ($good_bets as $good_bet) {
                    $this->template->assign_block_vars('pools.matches.good_bets', array(
                        'USERID' => $good_bet['userID'],
                        'NAME' => $good_bet['username']
                    ));
                }

                foreach ($exact_bets as $exact_bet) {
                    $this->template->assign_block_vars('pools.matches.exact_bets', array(
                        'USERID' => $exact_bet['userID'],
                        'NAME' => $exact_bet['username']
                    ));
                }
            }

            $array_teams = $this->teams->get_ranking($teams, $matches, 'score');

            foreach ($array_teams as $team) {
                $this->template->assign_block_vars('pools.teams', array(
                    'ID' => $team['teamID'],
                    'NAME' => $team['name'],
                    'NAME_URL' => $this->config['force_encoding_fs'] ? rawurlencode(utf8_decode($team['name'])) : rawurlencode($team['name']),
                    'POINTS' => $team['points'],
                    'DIFF' => (($team['diff'] > 0) ? "+" : "") . $team['diff']
                ));
            }
        }

        $this->blocks_loaded[] = 'view_odds';
    }

    /*     * **************** */

    function load_finals_bets($edit = false, $userID = false)
    {
        if ($userID != false) {
            $user = $this->users->get($userID);
            $current_user = $user['name'];
            $all_bets = false;
        } else {
            $userID = $this->users->get_current_id();
            $current_user = $_SESSION['user_name'];
            $all_bets = true;
        }

        if (($userID != $this->users->get_current_id()) && !$this->users->is_admin($this->users->get_current_id())) {
            $edit = false;
        }

        $adminEdit = ($userID != $this->users->get_current_id()) && $this->users->is_admin($this->users->get_current_id());

        if ($edit) {
            $this->template->set_filenames(array(
                'edit_finals_bets' => 'edit_finals_bets.tpl'
            ));
        } else {
            $this->template->set_filenames(array(
                'view_finals_bets' => 'view_finals_bets.tpl'
            ));
        }

        $this->template->assign_vars(array(
            'PAGE_TITLE' => $adminEdit ? "Phase finale de $current_user" : 'Ma phase finale',
            'CURRENT_USER_ID' => $userID,
            'USER_URL' => ($userID) ? "&user=" . $userID : "",
            'SUBMIT_STATE' => $adminEdit ? "disabled" : ""
        ));

        $array_template = [];
        $rounds = $this->config['rounds'];
        $teams = array('A', 'B');

        $array_template_extra = [];
        $this->template->assign_block_vars('finals', []);

        /* ROUND */

        foreach ($rounds as $round) {

            $round_name = $this->lang['LABEL_' . $round . '_FINAL'];

            $this->template->assign_block_vars('finals.rounds', array(
                'ROUND' => $round,
                'NAME' => $round_name
            ));

            /* Exception match 3ème place */
            if ($round == 3)
                $j = 1;
            else
                $j = $round;

            /* Structure */

            if ($round == 8) {
                $height_top = 0;
                $height_bottom = 0;
            }
            if ($round == 4) {
                $height_top = 61;
                $height_bottom = 61;
            }
            if ($round == 2) {
                $height_top = 154;
                $height_bottom = 154;
            }
            if ($round == 1) {
                $height_top = 334;
                $height_bottom = (in_array(3, $rounds)) ? 190 : 334;
            }
            if ($round == 3) {
                $height_top = 0;
                $height_bottom = 34;
            }

            if (($round != 3) || (!in_array(3, $rounds)))
                $this->template->assign_block_vars('finals.rounds.merge_top', []);
            if (($round != 1) || (!in_array(3, $rounds)))
                $this->template->assign_block_vars('finals.rounds.merge_bottom', []);

            /* RANK */

            for ($i = 1; $i <= $j; $i++) {

                $match = $this->matches->get_final($round, $i);
                if (!$match)
                    continue;

                $bet = $this->bets->get_by_match_and_user($match['matchID'], $userID);
                //if(!$bet) continue;

                /* Tests */
                $betA_is_null = (!isset($bet['teamBetA']) || (strlen($bet['teamBetA']) != 0));
                $matchA_is_set = (isset($match['teamAid']) && (strlen($match['teamAid']) != 0));
                $betB_is_null = (!isset($bet['teamBetB']) || (strlen($bet['teamBetB']) != 0));
                $matchB_is_set = (isset($match['teamBid']) && (strlen($match['teamBid']) != 0));
                $match_is_set = (isset($match['teamA']) && isset($match['teamB']) && ($match['teamA'] != null) && ($match['teamB'] != null) && ($match['teamA'] != 0) && ($match['teamB'] != 0));
                $result_is_set = (isset($match['scoreA'])) && (isset($match['scoreB'])) && ($match['scoreA'] != NULL) && ($match['scoreB'] != NULL);


                /* Priorité à l'équipe du résultat */
                if ($matchA_is_set) {
                    $bet['teamAname'] = (isset($match['teamAname'])) ? $match['teamAname'] : "";
                    $bet['teamBetA'] = (isset($match['teamAid'])) ? $match['teamAid'] : "";
                }

                if ($matchB_is_set) {
                    $bet['teamBname'] = (isset($match['teamBname'])) ? $match['teamBname'] : "";
                    $bet['teamBetB'] = (isset($match['teamBid'])) ? $match['teamBid'] : "";
                }

                /* Calcul des points */
                if ($result_is_set) {
                    $points = "0pt";
                    $color = "red";

                    $result = $this->bets->get_points($bet);

                    $diff = $result['diff'];

                    if ($result['good_result'] || $result['qualify']) {
                        $points = "+" . $result['points'] . "pt";
                        $color = "green";
                    }

                    if ($result['exact_score']) {
                        $points = "<b>+" . $result['points'] . "pts</b>";
                        $color = "green";
                    }
                } else {
                    $points = "";
                    $color = "";
                    $diff = "";
                }

                /* Si on visualise les score d'un autre joueur et que les paris sont encore ouverts, on les mets �  null */
                if (!$edit && !$all_bets && $this->matches->is_open($match['matchID'])) {
                    $bet['scoreBetA'] = null;
                    $bet['scoreBetB'] = null;
                    $bet['teamW'] = null;
                }

                /* Attribution des variables  de rang */
                $this->template->assign_block_vars('finals.rounds.ranks', array(
                    'RANK' => $i,
                    'DATE' => "<i>" . $match['date_str'] . "</i>",
                    'MATCH_ID' => $match['matchID'],
                    'TEAM_W' => (isset($bet['teamW'])) ? $bet['teamW'] : "",
                    'HEIGHT_TOP' => $height_top,
                    'HEIGHT_BOTTOM' => $height_bottom,
                    'POINTS' => $points,
                    'COLOR' => $color,
                    'DIFF' => ($diff != "") ? "(" . $diff . ")" : "",
                ));

                if (!$betA_is_null && !$matchA_is_set) {
                    $teamA = (isset($bet['teamBetA']) && $bet['teamBetA'] != "" && $bet['teamBetA'] != NULL) ? $this->teams->get($bet['teamBetA']) : "";
                    $bet['teamAname'] = (isset($teamA['name'])) ? $teamA['name'] : "";
                }

                if (!$betB_is_null && !$matchB_is_set) {
                    $teamB = (isset($bet['teamBetB']) && $bet['teamBetB'] != "" && $bet['teamBetB'] != NULL) ? $this->teams->get($bet['teamBetB']) : "";
                    $bet['teamBname'] = (isset($teamB['name'])) ? $teamB['name'] : "";
                }
                if (!isset($bet['scoreBetA']))
                    $bet['scoreBetA'] = NULL;
                if (!isset($bet['scoreBetB']))
                    $bet['scoreBetB'] = NULL;
                $color = [];
                $color['A'] = (isset($bet['teamW']) && $bet['teamW'] == 'A') ? "#99FF99" : "#F9F9F9";
                $color['B'] = (isset($bet['teamW']) && $bet['teamW'] == 'B') ? "#99FF99" : "#F9F9F9";

                if ($match_is_set && $edit && ($this->matches->is_open($match['matchID']) || ($this->users->is_admin($this->users->get_current_id()) && ($userID != $this->users->get_current_id())))) {
                    $mode = 'edit';
                } else {
                    $mode = 'view';
                }

                foreach ($teams as $team) {
                    if ($team == 'A')
                        $prev_rank = ($i * 2) - 1;
                    elseif ($team == 'B')
                        $prev_rank = ($i * 2);
                    $prev_round = $round * 2;
                    $prev_match = $this->matches->get_final($prev_round, $prev_rank);
                    $prev_bet = $this->matches->get_final($prev_round, $prev_rank);

                    $teamName = (isset($bet['team' . $team . 'name'])) ? $bet['team' . $team . 'name'] : "";
                    if (!$this->matches->is_open($match['matchID']) || $edit) {
                        $this->template->assign_block_vars('finals.rounds.ranks.teams', array(
                            'TEAM' => $team,
                            'ID' => (isset($bet['teamBet' . $team])) ? $bet['teamBet' . $team] : "",
                            'TEAM_REAL' => (isset($prev_match['teamW'])) ? $prev_match['teamW'] : "",
                            'NAME' => $teamName,
                            'COLOR' => $color[$team],
                            'IMG' => ($teamName != "") ? "&nbsp;<img width=\"15px\" src=\"" . $this->template_web_location . "images/flag/" . ($this->config['force_encoding_fs'] ? rawurlencode(utf8_decode($teamName)) : rawurlencode($teamName)) . ".png\" />" : "",
                            'SCORE' => (isset($bet['scoreBet' . $team])) ? $bet['scoreBet' . $team] : "",
                            'RESULT' => (isset($match['score' . $team])) ? $match['score' . $team] : ""
                        ));

                        if (((($team == 'A') && ($i % 2 == 1)) || (($team == 'B') && ($i % 2 == 0))) && ($match['scoreB'] != NULL) && ($match['scoreA'] != NULL)) {
                            $this->template->assign_block_vars('finals.rounds.ranks.teams.points', []);
                        }
                    } else {
                        $this->template->assign_block_vars('finals.rounds.ranks.teams', array(
                            'TEAM' => "",
                            'ID' => (isset($bet['teamBet' . $team])) ? $bet['teamBet' . $team] : "",
                            'TEAM_REAL' => "",
                            'NAME' => "",
                            'COLOR' => "#F9F9F9",
                            'IMG' => "",
                            'SCORE' => "",
                            'RESULT' => ""
                        ));
                    }

                    $this->template->assign_block_vars('finals.rounds.ranks.teams.' . $mode, []);
                    if ($team == 'B' && $round != 1 && $round != 3) {
                        if ($i % 2 == 1)
                            $this->template->assign_block_vars('finals.rounds.ranks.teams.top_line', []);
                        else
                            $this->template->assign_block_vars('finals.rounds.ranks.bottom_line', []);
                    }
                }
            }
        }

        // Stats
        $types = array(1 => "Evolution au classement", 2 => "Nb de points par jour");
        $userStats = $this->stats->get_user_stats($userID);
        foreach ($types as $id => $type) {
            if ($id == 1) {
                $xSerie = "[";
                $data = "[";
                $nbJournee = 1;
                foreach ($userStats as $stat) {
                    $data .= " [ $nbJournee, " . $stat['rank'] . "], ";
                    $xSerie .= " [ $nbJournee, ''], ";
                    $nbJournee++;
                }
                $data .= " ]";
                $xSerie .= " ]";

                $this->template->assign_block_vars('stats', array(
                    'TYPE' => $type,
                    'ID' => $id,
                    'DATA' => '[ ' . $data . ' ]',
                    'XSERIE' => $xSerie,
                    'YMIN' => 1,
                    'YMAX' => $this->users->count_active(),
                    'YTICKS' => "[ 1, 50, 100, 150, 200, 250, " . $this->users->count_active() . " ]",
                    'TRANSFORM' => 'function (v) { return -v; }',
                    'INVERSE_TRANSFORM' => 'function (v) { return -v; }',
                    'COLOR' => '#5166ED'
                ));
            } else if ($id == 2) {
                $xSerie = "[";
                $data = "[";
                $nbJournee = 1;
                $last_stat = null;
                $maxPts = 0;
                foreach ($userStats as $stat) {
                    if ($last_stat == null) {
                        $points = $stat['points'];
                    } else {
                        $points = ($stat['points'] - $last_stat['points']);
                    }

                    $data .= " [ $nbJournee, " . $points . "], ";
                    $xSerie .= " [ $nbJournee, ''], ";

                    if ($points > $maxPts) {
                        $maxPts = $points;
                    }

                    $nbJournee++;
                    $last_stat = $stat;
                }
                $data .= " ]";
                $xSerie .= " ]";

                if ($maxPts < 20) {
                    $maxPts = 20;
                }
                $ticks = [];
                $inc = round($maxPts / 5);
                for ($i = 0; $i < 6; $i++) {
                    $ticks[$i] = $i * $inc;
                }

                $this->template->assign_block_vars('stats', array(
                    'TYPE' => $type,
                    'ID' => $id,
                    'DATA' => '[ ' . $data . ' ]',
                    'XSERIE' => $xSerie,
                    'YMIN' => 0,
                    'YMAX' => $maxPts + 1,
                    'YTICKS' => '[' . implode(',', $ticks) . ']',
                    'COLOR' => '#50BA50'
                ));
            }
        }

        if ($edit) {
            $this->blocks_loaded[] = 'edit_finals_bets';
        }
        else {
            $this->blocks_loaded[] = 'view_finals_bets';
        }
    }

    function load_bets($options = [ 'edit' => false, 'userID' => null, 'orderByDate' => false ])
    {
        $edit = isset($options['edit']) ? $options['edit'] : false;
        $userID = isset($options['userID']) ? $options['userID'] : null;
        $orderByDate = isset($options['orderByDate']) ? $options['orderByDate'] : false;

        if ($userID != false) {
            $user = $this->users->get($userID);
            $current_user = $user['name'];
            $all_bets = false;
        } else {
            $userID = $this->users->get_current_id();
            $current_user = $_SESSION['user_name'];
            $all_bets = true;
        }

        if (($userID != $this->users->get_current_id()) && !$this->users->is_admin($this->users->get_current_id())) {
            $edit = false;
        }

        $adminEdit = ($userID != $this->users->get_current_id()) && $this->users->is_admin($this->users->get_current_id());

        $template = sprintf('%s_bets%s.tpl', $edit ? 'edit' : 'view', $orderByDate ? '_date-ordered' : '');
        $this->template->set_filenames([
            'bets' => $template
        ]);

        $this->template->assign_vars([
            'PAGE_TITLE' => $adminEdit || !$edit ? "Pronostics de $current_user" : 'Ma phase de poules',
            'CURRENT_USER_ID' => $userID,
            'USER_URL' => ($userID) ? "&user=" . $userID : "",
            'SUBMIT_STATE' => $adminEdit ? "disabled" : ""
        ]);

        if ($orderByDate) {
            $this->load_bets_by_date($userID, $edit, $all_bets);
        }

        $pools = $this->config['pools'];
        foreach ($pools as $pool) {
            $this->template->assign_block_vars('pools', array(
                'POOL' => $pool
            ));

            $teams = $this->teams->get_by_pool($pool);
            $bets = $this->bets->get_by_pool($pool, $userID);

            if (!$orderByDate) {
                $this->load_bets_by_pool($userID, $bets, $edit, $all_bets);
            }

            $array_teams = $this->teams->get_ranking($teams, $bets, 'scoreBet');
            foreach ($array_teams as $team) {
                $this->template->assign_block_vars('pools.teams', array(
                    'ID' => $team['teamID'],
                    'NAME' => $team['name'],
                    'NAME_URL' => $this->config['force_encoding_fs'] ? rawurlencode(utf8_decode($team['name'])) : rawurlencode($team['name']),
                    'POINTS' => $team['points'],
                    'DIFF' => (($team['diff'] > 0) ? "+" : "") . $team['diff']
                ));
            }
        }

        // Stats
        $types = array(1 => "Evolution au classement", 2 => "Nb de points par jour");
        $userStats = $this->stats->get_user_stats($userID);
        foreach ($types as $id => $type) {
            if ($id == 1) {
                $xSerie = "[";
                $data = "[";
                $nbJournee = 1;
                foreach ($userStats as $stat) {
                    $data .= " [ $nbJournee, " . $stat['rank'] . "], ";
                    $xSerie .= " [ $nbJournee, ''], ";
                    $nbJournee++;
                }
                $data .= " ]";
                $xSerie .= " ]";

                $this->template->assign_block_vars('stats', array(
                    'TYPE' => $type,
                    'ID' => $id,
                    'DATA' => '[ ' . $data . ' ]',
                    'XSERIE' => $xSerie,
                    'YMIN' => 1,
                    'YMAX' => $this->users->count_active(),
                    'YTICKS' => "[ 1, 50, 100, 150, 200, 250, " . $this->users->count_active() . " ]",
                    'TRANSFORM' => 'function (v) { return -v; }',
                    'INVERSE_TRANSFORM' => 'function (v) { return -v; }',
                    'COLOR' => '#5166ED'
                ));
            } else if ($id == 2) {
                $xSerie = "[";
                $data = "[";
                $nbJournee = 1;
                $maxPts = 0;
                $last_stat = null;
                foreach ($userStats as $stat) {
                    if ($last_stat == null) {
                        $points = $stat['points'];
                    } else {
                        $points = ($stat['points'] - $last_stat['points']);
                    }

                    $data .= " [ $nbJournee, " . $points . "], ";
                    $xSerie .= " [ $nbJournee, ''], ";

                    if ($points > $maxPts) {
                        $maxPts = $points;
                    }

                    $nbJournee++;
                    $last_stat = $stat;
                }
                $data .= " ]";
                $xSerie .= " ]";

                if ($maxPts < 20) {
                    $maxPts = 20;
                }
                $ticks = [];
                $inc = round($maxPts / 5);
                for ($i = 0; $i < 6; $i++) {
                    $ticks[$i] = $i * $inc;
                }

                $this->template->assign_block_vars('stats', array(
                    'TYPE' => $type,
                    'ID' => $id,
                    'DATA' => '[ ' . $data . ' ]',
                    'XSERIE' => $xSerie,
                    'YMIN' => 0,
                    'YMAX' => $maxPts + 1,
                    'YTICKS' => '[' . implode(',', $ticks) . ']',
                    'COLOR' => '#50BA50',
                    'TRANSFORM' => 'false',
                    'INVERSE_TRANSFORM' => 'false'
                ));
            }
        }

        $this->blocks_loaded[] = 'bets';
    }

    function load_bets_by_pool($userID, $bets, $edit = false, $all_bets = false)
    {
        foreach ($bets as $k => $bet) {
            if (!$edit && !$all_bets && $this->matches->is_open($bet['matchID'])) {
                $bets[$k]['scoreBetA'] = null;
                $bets[$k]['scoreBetB'] = null;
                $bet['scoreBetA'] = null;
                $bet['scoreBetB'] = null;
            }
            $match = $this->matches->get($bet['matchID']);
            $result = $this->bets->get_points($bet);

            $points = "0pt";
            $color = "red";
            $diff = $result['diff'];

            if ($result['good_result'] || $result['qualify']) {
                $points = "+" . $result['points'] . "pt";
                $color = "green";
            }

            if ($result['exact_score']) {
                $points = "<b>+" . $result['points'] . "pts</b>";
                $color = "green";
            }

            if ((!isset($match['scoreA'])) || (!isset($match['scoreB'])) || ($match['scoreA'] == NULL) || ($match['scoreB'] == NULL)) {
                $points = "";
                $color = "";
                $diff = "";
            }
            $this->template->assign_block_vars('pools.bets', array(
                'ID' => $bet['matchID']
            ));
            if ($edit && ($this->matches->is_open($bet['matchID']) || ($this->users->is_admin($this->users->get_current_id()) && ($userID != $this->users->get_current_id())))) {
                $this->template->assign_block_vars('pools.bets.edit', array(
                    'ID' => $bet['matchID'],
                    'DATE' => $bet['date_str'],
                    'TEAM_NAME_A' => $bet['teamAname'],
                    'TEAM_NAME_B' => $bet['teamBname'],
                    'TEAM_RANK_A' => $bet['teamAfifaRank'],
                    'TEAM_RANK_B' => $bet['teamBfifaRank'],
                    'SCORE_A' => (is_numeric($bet['scoreBetA'])) ? $bet['scoreBetA'] : "",
                    'SCORE_B' => (is_numeric($bet['scoreBetB'])) ? $bet['scoreBetB'] : "",
                    'RESULT_A' => (isset($match['scoreA']) && is_numeric($match['scoreA'])) ? $match['scoreA'] : "",
                    'RESULT_B' => (isset($match['scoreB']) && is_numeric($match['scoreB'])) ? $match['scoreB'] : "",
                    'POINTS' => $points,
                    'COLOR' => $color,
                    'DIFF' => ($diff != "") ? "(" . $diff . ")" : "",
                    'TEAM_COLOR_A' => ($bet['scoreBetA'] > $bet['scoreBetB']) ? "#99FF99" : "transparent",
                    'TEAM_COLOR_B' => ($bet['scoreBetB'] > $bet['scoreBetA']) ? "#99FF99" : "transparent",
                    'TEAM_NAME_A_URL' => $this->config['force_encoding_fs'] ? rawurlencode(utf8_decode($bet['teamAname'])) : rawurlencode($bet['teamAname']),
                    'TEAM_NAME_B_URL' => $this->config['force_encoding_fs'] ? rawurlencode(utf8_decode($bet['teamBname'])) : rawurlencode($bet['teamBname']),
                    'POOL' => $bet['teamPool'],
                    'DISABLED' => (!$this->users->is_admin($this->users->get_current_id()) && ($userID != $this->users->get_current_id())) ? "DISABLED" : ""
                ));
            } else {
                $this->template->assign_block_vars('pools.bets.view', array(
                    'ID' => $bet['matchID'],
                    'DATE' => $bet['date_str'],
                    'TEAM_NAME_A' => $bet['teamAname'],
                    'TEAM_NAME_B' => $bet['teamBname'],
                    'TEAM_RANK_A' => $bet['teamAfifaRank'],
                    'TEAM_RANK_B' => $bet['teamBfifaRank'],
                    'SCORE_A' => (is_numeric($bet['scoreBetA'])) ? $bet['scoreBetA'] : "",
                    'SCORE_B' => (is_numeric($bet['scoreBetB'])) ? $bet['scoreBetB'] : "",
                    'RESULT_A' => (isset($match['scoreA']) && is_numeric($match['scoreA'])) ? $match['scoreA'] : "",
                    'RESULT_B' => (isset($match['scoreB']) && is_numeric($match['scoreB'])) ? $match['scoreB'] : "",
                    'POINTS' => $points,
                    'COLOR' => $color,
                    'DIFF' => ($diff != "") ? "(" . $diff . ")" : "",
                    'TEAM_COLOR_A' => ($bet['scoreBetA'] > $bet['scoreBetB']) ? "#99FF99" : "transparent",
                    'TEAM_COLOR_B' => ($bet['scoreBetB'] > $bet['scoreBetA']) ? "#99FF99" : "transparent",
                    'TEAM_NAME_A_URL' => $this->config['force_encoding_fs'] ? rawurlencode(utf8_decode($bet['teamAname'])) : rawurlencode($bet['teamAname']),
                    'TEAM_NAME_B_URL' => $this->config['force_encoding_fs'] ? rawurlencode(utf8_decode($bet['teamBname'])) : rawurlencode($bet['teamBname']),
                    'POOL' => $bet['teamPool'],
                    'DISABLED' => "DISABLED",
                    'USER_URL' => ($userID) ? "&user=" . $userID : ""
                ));
            }
        }
    }

    function load_bets_by_date($userID, $edit = false, $all_bets = false)
    {
        $bets = $this->bets->get_by_user($userID, 'pool');
        foreach ($bets as $k => $bet) {
            if (!$edit && !$all_bets && $this->matches->is_open($bet['matchID'])) {
                $bets[$k]['scoreBetA'] = null;
                $bets[$k]['scoreBetB'] = null;
                $bet['scoreBetA'] = null;
                $bet['scoreBetB'] = null;
            }
            $match = $this->matches->get($bet['matchID']);
            $result = $this->bets->get_points($bet);

            $points = "0pt";
            $color = "red";
            $diff = $result['diff'];

            if ($result['good_result'] || $result['qualify']) {
                $points = "+" . $result['points'] . "pt";
                $color = "green";
            }

            if ($result['exact_score']) {
                $points = "<b>+" . $result['points'] . "pts</b>";
                $color = "green";
            }

            if ((!isset($match['scoreA'])) || (!isset($match['scoreB'])) || ($match['scoreA'] == NULL) || ($match['scoreB'] == NULL)) {
                $points = "";
                $color = "";
                $diff = "";
            }
            $this->template->assign_block_vars('bets', array(
                'ID' => $bet['matchID']
            ));
            if ($edit && ($this->matches->is_open($bet['matchID']) || ($this->users->is_admin($this->users->get_current_id()) && ($userID != $this->users->get_current_id())))) {
                $this->template->assign_block_vars('bets.edit', array(
                    'ID' => $bet['matchID'],
                    'DATE' => $bet['date_str'],
                    'TEAM_NAME_A' => $bet['teamAname'],
                    'TEAM_NAME_B' => $bet['teamBname'],
                    'TEAM_RANK_A' => $bet['teamAfifaRank'],
                    'TEAM_RANK_B' => $bet['teamBfifaRank'],
                    'SCORE_A' => (is_numeric($bet['scoreBetA'])) ? $bet['scoreBetA'] : "",
                    'SCORE_B' => (is_numeric($bet['scoreBetB'])) ? $bet['scoreBetB'] : "",
                    'RESULT_A' => (isset($match['scoreA']) && is_numeric($match['scoreA'])) ? $match['scoreA'] : "",
                    'RESULT_B' => (isset($match['scoreB']) && is_numeric($match['scoreB'])) ? $match['scoreB'] : "",
                    'POINTS' => $points,
                    'COLOR' => $color,
                    'DIFF' => ($diff != "") ? "(" . $diff . ")" : "",
                    'TEAM_COLOR_A' => ($bet['scoreBetA'] > $bet['scoreBetB']) ? "#99FF99" : "transparent",
                    'TEAM_COLOR_B' => ($bet['scoreBetB'] > $bet['scoreBetA']) ? "#99FF99" : "transparent",
                    'TEAM_NAME_A_URL' => $this->config['force_encoding_fs'] ? rawurlencode(utf8_decode($bet['teamAname'])) : rawurlencode($bet['teamAname']),
                    'TEAM_NAME_B_URL' => $this->config['force_encoding_fs'] ? rawurlencode(utf8_decode($bet['teamBname'])) : rawurlencode($bet['teamBname']),
                    'POOL' => $bet['teamPool'],
                    'DISABLED' => (!$this->users->is_admin($this->users->get_current_id()) && ($userID != $this->users->get_current_id())) ? "DISABLED" : ""
                ));
            } else {
                $this->template->assign_block_vars('bets.view', array(
                    'ID' => $bet['matchID'],
                    'DATE' => $bet['date_str'],
                    'TEAM_NAME_A' => $bet['teamAname'],
                    'TEAM_NAME_B' => $bet['teamBname'],
                    'TEAM_RANK_A' => $bet['teamAfifaRank'],
                    'TEAM_RANK_B' => $bet['teamBfifaRank'],
                    'SCORE_A' => (is_numeric($bet['scoreBetA'])) ? $bet['scoreBetA'] : "",
                    'SCORE_B' => (is_numeric($bet['scoreBetB'])) ? $bet['scoreBetB'] : "",
                    'RESULT_A' => (isset($match['scoreA']) && is_numeric($match['scoreA'])) ? $match['scoreA'] : "",
                    'RESULT_B' => (isset($match['scoreB']) && is_numeric($match['scoreB'])) ? $match['scoreB'] : "",
                    'POINTS' => $points,
                    'COLOR' => $color,
                    'DIFF' => ($diff != "") ? "(" . $diff . ")" : "",
                    'TEAM_COLOR_A' => ($bet['scoreBetA'] > $bet['scoreBetB']) ? "#99FF99" : "transparent",
                    'TEAM_COLOR_B' => ($bet['scoreBetB'] > $bet['scoreBetA']) ? "#99FF99" : "transparent",
                    'TEAM_NAME_A_URL' => $this->config['force_encoding_fs'] ? rawurlencode(utf8_decode($bet['teamAname'])) : rawurlencode($bet['teamAname']),
                    'TEAM_NAME_B_URL' => $this->config['force_encoding_fs'] ? rawurlencode(utf8_decode($bet['teamBname'])) : rawurlencode($bet['teamBname']),
                    'POOL' => $bet['teamPool'],
                    'DISABLED' => "DISABLED",
                    'USER_URL' => ($userID) ? "&user=" . $userID : ""
                ));
            }
        }
    }

    function load_user_stats($userID = false)
    {
        $this->template->set_filenames(array(
            'user_stats' => 'user_stats.tpl'
        ));

        if ($userID != false) {
            $user = $this->users->get($userID);
        } else {
            $user = $this->users->get($_SESSION['userID']);
        }

        $this->template->assign_vars(array(
            'CURRENT_USER' => $user['name'],
            'MAX_POINTS' => $this->users->get_max_points(),
        ));

        $types = array(1 => "Classement général", 2 => "Evolution du nombre de points");
        foreach ($types as $id => $type) {
            $this->template->assign_block_vars('stats', array(
                'TYPE' => $type,
                'ID' => $id
            ));
            $datas = $this->stats->get_user_stats_by_type($userID, $id);
            $days = array_keys($datas);
            $i = 0;
            foreach ($days as $day) {
                $this->template->assign_block_vars('stats.days', array(
                    'DAY_VALUE' => $day,
                ));
                $i++;
            }
            foreach ($datas as $data) {
                $this->template->assign_block_vars('stats.datas', array(
                    'DATA_VALUE' => $data,
                ));
            }
        }

        $this->blocks_loaded[] = 'user_stats';
    }

    function display()
    {
        foreach ($this->blocks_loaded as $block)
            $this->template->pparse($block);
    }

    function login($login, $pass, $keep, $deviceUuid)
    {
        $ret = $this->users->is_authentificate($login, $pass, $user);
        if ($ret > 0) {
            $this->log_user_in($user);

            // store identification
            if ($keep === true) {
                $token = $this->generate_random_token();
                $this->tokens->add($user['userID'], $deviceUuid, $token);
                $cookie = $user['userID'] . ':' . $token;

                $mac = hash_hmac('sha256', $cookie, $this->config['secret_key']);
                $cookie .= ':' . $mac;

                setcookie('device', $deviceUuid, time() + 60 * 60 * 24 * 365, null, null, true, true);
                setcookie('rememberme', $cookie, time() + 60 * 60 * 24 * 365, null, null, true, true);
            }

            return true;
        } else
            return $ret;
    }

    function log_user_in($user)
    {
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['login'] = $user['login'];
        $_SESSION['userID'] = $user['userID'];
        $_SESSION['group_id'] = $user['groupID'];
        $_SESSION['group_id2'] = $user['groupID2'];
        $_SESSION['group_id3'] = $user['groupID3'];
        $_SESSION['group_name'] = $this->groups->get_name_by_user($user['userID']);
        $_SESSION['group_name2'] = $this->groups->get_name_by_user($user['userID'], 2);
        $_SESSION['group_name3'] = $this->groups->get_name_by_user($user['userID'], 3);
        $_SESSION['status'] = $user['status'];
        $_SESSION['theme'] = $user['theme'];
        $_SESSION['match_display'] = $user['match_display'];
    }

    function generate_random_token() {
        $factory = new RandomLib\Factory;
        $generator = $factory->getMediumStrengthGenerator();
        return $generator->generateString(128);
    }

    function remember_me() {
        $cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
        $deviceUuid = isset($_COOKIE['device']) ? $_COOKIE['device'] : '';
        if ($cookie) {
            list ($userID, $token, $mac) = explode(':', $cookie);
            if (!hash_equals(hash_hmac('sha256', $userID . ':' . $token, $this->config['secret_key']), $mac)) {
                return false;
            }

            $userToken = $this->tokens->get_by_user_and_device($userID, $deviceUuid);

            if ($userToken && hash_equals($userToken, $token)) {
                $user = $this->users->get($userID);
                $this->log_user_in($user);
                return true;
            }
        }
        return false;
    }

    function update_session()
    {
        if ($this->islogin()) {
            $user = $this->users->get_current();
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['login'] = $user['login'];
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['group_id'] = $user['groupID'];
            $_SESSION['group_id2'] = $user['groupID2'];
            $_SESSION['group_id3'] = $user['groupID3'];
            $_SESSION['group_name'] = $this->groups->get_name_by_user($user['userID']);
            $_SESSION['group_name2'] = $this->groups->get_name_by_user($user['userID'], 2);
            $_SESSION['group_name3'] = $this->groups->get_name_by_user($user['userID'], 3);
            $_SESSION['status'] = $user['status'];
            $_SESSION['theme'] = $user['theme'];
            $_SESSION['match_display'] = $user['match_display'];
            return true;
        } else
            return false;
    }

    function load_account($warning = "")
    {
        $this->template->set_filenames(array(
            'account' => 'account.tpl'
        ));

        $user = $this->users->get_current();

        $group = ($user) ? $this->groups->get($user['groupID']) : false;
        $group2 = ($user) ? $this->groups->get($user['groupID2']) : false;
        $group3 = ($user) ? $this->groups->get($user['groupID3']) : false;

        $this->template->assign_vars(array(
            'TPL_WEB_PATH' => $this->template_web_location,
            'WARNING' => $warning,
        ));

        if ($group) {
            $this->template->assign_block_vars('leave_group1', array(
                'GROUP_ID' => $group['groupID'],
                'GROUP_NAME' => $group['name'],
                'GROUP_NAME_JS' => addslashes($group['name']),
            ));
        }
        if ($group2) {
            $this->template->assign_block_vars('leave_group2', array(
                'GROUP_ID' => $group2['groupID'],
                'GROUP_NAME' => $group2['name'],
                'GROUP_NAME_JS' => addslashes($group2['name']),
            ));
        }
        if ($group3) {
            $this->template->assign_block_vars('leave_group3', array(
                'GROUP_ID' => $group3['groupID'],
                'GROUP_NAME' => $group3['name'],
                'GROUP_NAME_JS' => addslashes($group3['name']),
            ));
        }
        if ($this->users->count_groups() < 3) {
            $this->template->assign_block_vars('join_group', []);
        }

        $this->blocks_loaded[] = 'account';
    }

    function load_admin()
    {
        $this->template->set_filenames([ 'admin' => 'admin.tpl' ]);

        $this->template->assign_vars([ 'TPL_WEB_PATH' => $this->template_web_location ]);

        $this->blocks_loaded[] = 'admin';
    }

    function load_join_group($warning = "", $code = false)
    {
        $this->template->set_filenames(array(
            'join_group' => 'join_group.tpl'
        ));

        $user = $this->users->get_current();
        $auto_groupID = false;
        $invitation = false;

        if ($code) {
            $invitation = $this->groups->is_invited_by_code($code);
            if ($invitation)
                $auto_groupID = $invitation['groupID'];
        }

        $this->template->assign_vars(array(
            'TPL_WEB_PATH' => $this->template_web_location,
            'WARNING' => $warning,
            'AUTO_JOIN' => ($code && (!($this->users->is_in_group($auto_groupID)))) ? $auto_groupID : "0",
            'CODE' => $code,
        ));

        $groups = $this->groups->get();

        foreach ($groups as $group) {
            if (($user['groupID'] == $group['groupID']) || ($user['groupID2'] == $group['groupID']) || ($user['groupID3'] == $group['groupID']))
                continue;
            $owner = $this->users->get($group['ownerID']);
            $this->template->assign_block_vars('groups', array(
                'ID' => $group['groupID'],
                'NAME' => $group['name'],
                'NAME_STRIP' => stripslashes($group['name']),
                'OWNER_NAME' => ($owner) ? $owner['name'] : "",
                'IS_NEED_AUTH' => ($this->groups->is_invited($group['groupID']) || ($group['groupID'] == $code)) ? 0 : 1,
                'SELECTED' => ($group['groupID'] == $auto_groupID) ? " SELECTED" : ""
            ));
        }

        $this->blocks_loaded[] = 'join_group';
    }

    function load_leave_group()
    {
        $this->template->set_filenames(array(
            'leave_group' => 'leave_group.tpl'
        ));

        $this->template->assign_vars(array(
            'TPL_WEB_PATH' => $this->template_web_location
        ));

        $this->blocks_loaded[] = 'leave_group';
    }

    function load_create_group($warning = "")
    {
        $this->template->set_filenames(array(
            'create_group' => 'create_group.tpl'
        ));

        $this->template->assign_vars(array(
            'WARNING' => $warning,
            'TPL_WEB_PATH' => $this->template_web_location
        ));

        $this->blocks_loaded[] = 'create_group';
    }

    function load_invite_friends($warning = "")
    {
        $this->template->set_filenames(array(
            'invite_friends' => 'invite_friends.tpl'
        ));

        $user = $this->users->get_current();

        $this->template->assign_vars(array(
            'WARNING' => $warning,
            'TPL_WEB_PATH' => $this->template_web_location,
        ));

        $send_invitations = $this->groups->get_invitations_by_sender($user['userID']);

        if (count($send_invitations) > 0) {
            $this->template->assign_block_vars('is_send', []);
            foreach ($send_invitations as $send_invitation) {
                if ($send_invitation['status'] == 2 || $send_invitation['status'] == -2) {
                    $user_invited = $this->users->get_by_email($send_invitation['email']);
                    $name = $user_invited['name'];
                } else {
                    $name = $send_invitation['email'];
                }
                $this->template->assign_block_vars('is_send.send_invitations', array(
                    'NAME' => $name,
                    'GROUP_NAME' => $send_invitation['group_name'],
                    'STATUS' => ($send_invitation['status'] < 0) ? "<font color='orange'><b>Utilisée</b></font>" : (($send_invitation['expired'] == 1) ? "<font color='red'><b>Expirée</b></font>" : "<font color='green'><b>Inutilisée</b></font>"),
                ));
            }
        }

        $nb_invitations = $this->config['nb_invitations'];
        $groups = $this->groups->get();

        if ($this->users->count_groups($user['userID']) > 0) {
            $this->template->assign_block_vars('is_group', []);
            $users = $this->users->get();

            for ($i = 1; $i <= $nb_invitations; $i++) {
                $this->template->assign_block_vars('is_group.invit_in', array(
                    'ID' => $i,
                ));
                foreach ($users as $u) {
                    if ($u['userID'] == $user['userID'])
                        continue;
                    $this->template->assign_block_vars('is_group.invit_in.users', array(
                        'ID' => $u['userID'],
                        'NAME' => $u['name'],
                    ));
                }
                foreach ($groups as $group) {
                    if (($user['groupID'] != $group['groupID']) && ($user['groupID2'] != $group['groupID']) && ($user['groupID3'] != $group['groupID']))
                        continue;
                    $owner = $this->users->get($group['ownerID']);
                    $this->template->assign_block_vars('is_group.invit_in.groups', array(
                        'ID' => $group['groupID'],
                        'NAME' => $group['name'],
                        'NAME_STRIP' => stripslashes($group['name']),
                        'OWNER_NAME' => ($owner) ? $owner['name'] : "",
                    ));
                }
            }
        }

        for ($i = 1; $i <= $nb_invitations; $i++) {
            $this->template->assign_block_vars('invit_out', array(
                'ID' => $i,
            ));
            foreach ($groups as $group) {
                if (($user['groupID'] != $group['groupID']) && ($user['groupID2'] != $group['groupID']) && ($user['groupID3'] != $group['groupID']))
                    continue;
                $owner = $this->users->get($group['ownerID']);
                $this->template->assign_block_vars('invit_out.groups', array(
                    'ID' => $group['groupID'],
                    'NAME' => $group['name'],
                    'NAME_STRIP' => stripslashes($group['name']),
                    'OWNER_NAME' => ($owner) ? $owner['name'] : "",
                ));
            }
        }

        $this->blocks_loaded[] = 'invite_friends';
    }

    function load_register($warning, $code)
    {
        $this->template->set_filenames(array(
            'register' => 'register.tpl'
        ));

        if ($code)
            $invitation = $this->groups->is_invited_by_code($code);

        $this->template->assign_vars(array(
            'TPL_WEB_PATH' => $this->template_web_location,
            'WARNING' => $warning,
            'CODE' => $code,
            'EMAIL' => (isset($invitation['email'])) ? $invitation['email'] : "",
        ));

        $this->blocks_loaded[] = 'register';
    }

    function send_invitations($emails, $invitations, $type)
    {
        $current_user = $this->users->get_current();
        $ret = false;
        if ($type == 'OUT') {
            foreach ($emails as $email) {
                if (isset($invitations[$email]))
                    $code = $invitations[$email]['code'];
                $subject = $current_user['name'] . " vous invite à venir pronostiquer avec lui sur les matches de la coupe du monde !";
                $content = "Bonjour,\n\n";
                $content .= $current_user['name'] . " a pensé que vous seriez intéressé pour venir pronostiquer avec lui sur les matches de la coupe du monde.\n";
                $content .= "Pour cela, inscrivez-vous sur Euro2016 en cliquant sur le lien suivant :\n\n";
                if (isset($code))
                    $content .= "http://" . $_SERVER['HTTP_HOST'] . "/?c=" . $code . "\n\n";
                else
                    $content .= "http://" . $_SERVER['HTTP_HOST'] . "/?act=register\n\n";
                $content .= "Cordialement,\n";
                $content .= "L'équipe de Euro2016\n";
                $ret = utf8_mail($email, $subject, $content, $this->config['blog_title'], $this->config['email'], $this->config['email_simulation']);
            }
        } elseif ($type == 'IN') {
            foreach ($emails as $email) {
                $code = $invitations[$email]['code'];
                $groupID = $invitations[$email]['groupID'];
                $group = $this->groups->get($groupID);
                $subject = "[" . $this->config['blog_title'] . "] " . $current_user['name'] . " vous invite à venir rejoindre le groupe " . $group['name'];
                $content = "Bonjour,\n\n";
                $content .= $current_user['name'] . " vous invite à venir à rejoindre le groupe " . $group['name'] . "\n";
                $content .= "Pour accepter cette invitation, cliquez sur le lien suivant :\n\n";
                $content .= "http://" . $_SERVER['HTTP_HOST'] . "/?c=" . $code . "\n\n";
                $content .= "Cordialement,\n";
                $content .= "L'équipe de Euro2016\n";
                $ret = utf8_mail($email, $subject, $content, $this->config['blog_title'], $this->config['email'], $this->config['email_simulation']);
            }
        }
        if ($ret)
            return SEND_INVITATIONS_OK;
        else
            SEND_INVITATIONS_ERROR;
    }

    function send_login($email)
    {
        $user = $this->users->get_by_email($email);

        if ($user) {
            return utf8_mail($user['email'], "Euro2016 - Oubli de votre login", "Bonjour,\n\nVotre login est : " . $user['login'] . "\n\nCordialement,\nL'équipe Euro2016\n", $this->config['blog_title'], $this->config['email'], $this->config['email_simulation']);
        }
        else {
            utf8_mail($this->config['email'], "Euro2016 - Utilisateur '" . $email . "' inconnu", "L'utilisateur avec l'email '" . $email . "' a tenté de récupérer son login.\n", $this->config['blog_title'], $this->config['email'], $this->config['email_simulation']);
            return false;
        }
    }

    function send_password($login)
    {
        $user = $this->users->get_by_login($login);
        $new_pass = $this->users->set_new_password($user['userID']);

        if ($user) {
            return utf8_mail($user['email'], "Euro2016 - Oubli de mot de passe", "Bonjour,\n\nVotre nouveau mot de passe est : " . $new_pass . "\n\nCordialement,\nL'équipe Euro2016\n", $this->config['blog_title'], $this->config['email'], $this->config['email_simulation']);
        }
        else {
            utf8_mail($this->config['email'], "Euro2016 - Utilisateur " . $login . " inconnu", "L'utilisateur " . $login . " a tenté de récupérer son mot de passe.\n", $this->config['blog_title'], $this->config['email'], $this->config['email_simulation']);
            return false;
        }
    }

    /*     * **************** */

    function reset()
    {
        $requests = [
            "UPDATE " . $this->config['db_prefix'] . "groups SET avgPoints=0, totalPoints=0, maxPoints=0, lastRank=1;",
            "UPDATE " . $this->config['db_prefix'] . "users SET points=0, nbresults=0, nbscores=0, diff=0, last_rank=1, last_connection=NULL, last_bet=NULL;",
            "UPDATE " . $this->config['db_prefix'] . "settings SET value=0 WHERE name = 'NB_MATCHES_GENERATED';",
            "UPDATE " . $this->config['db_prefix'] . "matches SET scoreA=NULL, scoreB=null, teamW=NULL;",
            "DELETE FROM " . $this->config['db_prefix'] . "bets;",
            "DELETE FROM " . $this->config['db_prefix'] . "matches WHERE round IS NOT NULL;",
            "DELETE FROM " . $this->config['db_prefix'] . "tags;",
            "DELETE FROM " . $this->config['db_prefix'] . "invitations;",
            "DELETE FROM " . $this->config['db_prefix'] . "stats_user;",
         ];

        foreach ($requests as $req) {
            $this->db->exec_query($req);
        }
    }

    /*     * **************** */

    function get_queries_time()
    {
        return $this->db->exec_time;
    }

    /*     * **************** */

    function get_nb_queries()
    {
        return $this->db->nb_queries;
    }

    function check_bets()
    {
        $users = $this->users->get();
        foreach ($users as $user) {
            $bets = $this->bets->get_by_user($user['userID']);
            foreach ($bets as $bet) {
                if (($bet['scoreBetA'] == NULL) && ($bet['scoreBetB'] == NULL))
                    continue;
                $match = $this->matches->get($bet['matchID']);
                if (!$match)
                    continue;

                $m_teamA_id = $match['teamAid'];
                $m_teamA_name = $match['teamAname'];
                $m_scoreA = $match['scoreA'];
                $m_teamB_id = $match['teamBid'];
                $m_teamB_name = $match['teamBname'];
                $m_scoreB = $match['scoreB'];
                $m_teamW = $match['teamW'];

                $round = $match['round'];
                $rank = $match['rank'];

                $b_teamA_id = $bet['teamAid'];
                $b_teamA_obj = ($b_teamA_id != NULL && $b_teamA_id != "") ? $this->teams->get($b_teamA_id) : array('name' => 'NULL');
                $b_teamA_name = $b_teamA_obj['name'];
                $b_scoreA = $bet['scoreBetA'];
                $b_teamB_id = $bet['teamBid'];
                $b_teamB_obj = ($b_teamB_id != NULL && $b_teamB_id != "") ? $this->teams->get($b_teamB_id) : array('name' => 'NULL');
                $b_teamB_name = $b_teamB_obj['name'];
                $b_scoreB = $bet['scoreBetB'];
                $b_teamW = $bet['teamW'];

                if ($round != NULL && $rank != NULL && $round != "" && $rank != "") {
                    if ($round != 1 && $round != 3) {
                        $next_round = ceil($round / 2);
                        $next_rank = ceil($rank / 2);
                        $next_team = (is_float($rank / 2)) ? 'A' : 'B';
                        $next_match = $this->matches->get_final($next_round, $next_rank);
                        if (!$next_match)
                            continue;
                        $next_bet = $this->bets->get_by_match($next_match['matchID'], $user['userID']);
                        if (!$next_bet)
                            continue;
                    }
                    if ($round != 8 && $round != 3) {
                        $prev_round = $round * 2;
                        $prev_rank_A = ($rank * 2) - 1;
                        $prev_rank_B = $rank * 2;
                        $prev_match_A = $this->matches->get_final($prev_round, $prev_rank_A);
                        if (!$prev_match_A)
                            continue;
                        $prev_bet_A = $this->bets->get_by_match($prev_match_A['matchID'], $user['userID']);
                        if (!$prev_bet_A)
                            continue;
                        $prev_match_B = $this->matches->get_final($prev_round, $prev_rank_B);
                        if (!$prev_match_B)
                            continue;
                        $prev_bet_B = $this->bets->get_by_match($prev_match_B['matchID'], $user['userID']);
                        if (!$prev_bet_B)
                            continue;
                    }

                    if (($round == 8) && ($m_teamA_id != $b_teamA_id)) {
                        echo $user['name'] . " => " . $round . "/" . $rank . " : Team A incorrecte (" . $m_teamA_name . " != " . $b_teamA_name . ")<br/>";
                        echo "UPDATE " . $this->parent->config['db_prefix'] . "bets SET teamA = " . $m_teamA_id . " WHERE userID = " . $user['userID'] . " AND matchID = " . $match['matchID'] . ";<br/>";
                    }
                    if (($round == 8) && ($m_teamB_id != $b_teamB_id)) {
                        echo $user['name'] . " => " . $round . "/" . $rank . " : Team B incorrecte (" . $m_teamB_name . " != " . $b_teamB_name . ")<br/>";
                        echo "UPDATE " . $this->parent->config['db_prefix'] . "bets SET teamB = " . $m_teamB_id . " WHERE userID = " . $user['userID'] . " AND matchID = " . $match['matchID'] . ";<br/>";
                    }

                    if (($round != 1) && ($round != 3)) {
                        if ($bet['teamW'] == NULL || $bet['teamW'] == "") {
                            echo $user['name'] . " => " . $round . "/" . $rank . " : TeamW null<br/>";
                        } elseif (isset($next_bet['team' . $next_team]) && isset($bet['team' . $bet['teamW']]) && isset($next_match['team' . $next_team]) && ($next_bet['team' . $next_team] != $bet['team' . $bet['teamW']]) && !($next_round == 4 && $next_bet['team' . $next_team] == $next_match['team' . $next_team])) {
                            $next_team_obj = ($next_bet['team' . $next_team] != "" && $next_bet['team' . $next_team] != "35" && $next_bet['team' . $next_team] != NULL) ? $this->get_team($next_bet['team' . $next_team]) : array('name' => 'NULL');
                            $team_obj = ($bet['team' . $bet['teamW']] != "" && $bet['team' . $bet['teamW']] != "35" && $bet['team' . $bet['teamW']] != NULL) ? $this->get_team($bet['team' . $bet['teamW']]) : array('name' => 'NULL');
                            echo $user['name'] . " => " . $round . "/" . $rank . " : Team " . $bet['teamW'] . " incorrecte (" . $team_obj['name'] . " qualifiée mais " . $next_team_obj['name'] . " au tour suivant)<br/>";

                            echo "UPDATE " . $this->parent->config['db_prefix'] . "bets SET team" . $next_team . " = " . $bet['team' . $bet['teamW']] . " WHERE userID = " . $user['userID'] . " AND matchID = " . $next_match['matchID'] . ";<br/>";
                        }
                    }
                }
            }
        }
    }

}
