<?php
echo "Coming soon...";
die();
ini_set('session.gc_maxlifetime', 86400); // 24 hours
session_set_cookie_params(86400);
session_start();

header("Content-Type: text/html; charset=utf-8");
define('WEB_PATH', "/");
define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . "/");
define('URL_PATH', "/");

require('include/classes/Engine.php');

$debug = false;
$engine = new Engine(false, $debug);

define('LOGIN', (isset($_GET['op']) && ($_GET['op']) == "login"));
define('REGISTER', (isset($_GET['op']) && ($_GET['op']) == "register"));
define('FORGOT_IDS', (isset($_GET['op']) && ($_GET['op']) == "forgot_ids"));
define('AUTHENTIFICATION_NEEDED', (!isset($_SESSION['userID']) && !LOGIN && !REGISTER && !FORGOT_IDS));
define('PHASE_ID_ACTIVE', $engine->getPhaseIDActive());
define('CODE', ( isset($_GET['c']) && !isset($_GET['op'])));

$op = "";
$pageToInclude = "";
global $message;

if (FORGOT_IDS) {
    if (isset($_POST['email'])) {
        $res = $engine->sendIDs($_POST['email']);
        redirect("/?message=" . $res);
    } else {
        $pageToInclude = "pages/forgot_ids.php";
    }
} elseif (CODE) {
    if (isset($_SESSION['userID'])) {
        redirect("/?op=join_group&c=" . $_GET['c']);
    } else {
        $invitation = $engine->getInvitation($_GET['c']);
        if ($invitation['status'] == 1) {
            redirect("/?op=register&c=" . $_GET['c']);
        } elseif ($invitation['status'] == 2) {
            redirect("/?s=" . $_GET['c']);
        } else {
            redirect("/?op=register");
        }
    }
} elseif (REGISTER) {
    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['login'])) {
        if ($_POST['password1'] != $_POST['password2']) {
            redirect("/?op=register&message=" . PASSWORD_MISMATCH);
        }
        $userTeamID = 0;
        $code = $_POST['code'];
        if ($code != "") {
            $teamId = $engine->useInvitation($code);
            if ($teamId) {
                $userTeamID = $teamId;
            }
        }
        $status = $engine->addUser($_POST['login'], $_POST['password1'], $_POST['name'], $_POST['firstname'], $_POST['email'], $userTeamID, 0);

        if ($status < 0) {
            redirect("/?op=register&c=" . $_POST['code'] . "&message=" . $status);
        } else {
            redirect("/?message=" . REGISTER_OK);
        }
    }
    if (isset($_GET['message']) && $_GET['message']) {
        $message = $engine->lang['messages'][$_GET['message']];
    }
    $pageToInclude = "pages/register.php";
} elseif (AUTHENTIFICATION_NEEDED) {
    if (isset($_GET['message']) && $_GET['message']) {
        $message = $engine->lang['messages'][$_GET['message']];
    }
    $pageToInclude = "pages/login.php";
} else {
    if (isset($_REQUEST['op'])) {
        $op = $_REQUEST['op'];
    }
    switch ($op) {
        case "login":
            if ($engine->login($_POST['login'], $_POST['pass'])) {
                if (isset($_POST['code']) && ($_POST['code'] != "")) {
                    redirect("/?c=" . $_POST['code']);
                } else {
                    $pageToInclude = "pages/ranking.php";
                }
            } else {
                $message = $engine->lang['messages'][INCORRECT_CREDENTIALS];
                $pageToInclude = "pages/login.php";
            }
            break;

        case "logout":
            session_destroy();
            redirect("/");
            break;

        case "account":
            if (isset($_GET['message']) && $_GET['message']) {
                $message = $engine->lang['messages'][$_GET['message']];
            }
            $pageToInclude = "pages/account.php";
            break;

        case "change_account":
            if (isset($_GET['message']) && $_GET['message']) {
                $message = $engine->lang['messages'][$_GET['message']];
            }
            $pageToInclude = "pages/change_account.php";
            break;

        case "update_profile":
            $message = "";
            $pwd = "";
            if ((strlen($_POST['pwd1']) > 0)) {
                if ($_POST['pwd1'] == $_POST['pwd2'])
                    $pwd = $_POST['pwd1'];
                else {
                    redirect("/?op=my_profile&message=" . PASSWORD_MISMATCH);
                }
            }
            if (!$engine->updateProfile($_SESSION['userID'], $_POST['name'], $_POST['email'], $_POST['pwd1'])) {
                redirect("/?op=my_profile&message=" . UNKNOWN_ERROR);
            }
            redirect("/?op=my_profile&message=" . CHANGE_ACCOUNT_OK);
            break;

        case "view_ranking":
            $pageToInclude = "pages/ranking.php";
            break;

        case "view_ranking_teams":
            $pageToInclude = "pages/ranking_teams.php";
            break;

        case "view_ranking_users_in_team":
            $pageToInclude = "pages/ranking_users_in_team.php";
            break;

        case "update_ranking":
            $engine->updateRanking();
            $engine->updateUserTeamRanking();
            $pageToInclude = "pages/ranking.php";
            break;

        case "edit_games":
            $pageToInclude = "pages/admin/edit_games.php";
            break;

        case "edit_results":
            $pageToInclude = "pages/admin/edit_results.php";
            break;

        case "view_results":
            $pageToInclude = "pages/view_results.php";
            break;

        case "add_match":
            $engine->addMatch($_POST['phase'], $_POST['pool'], $_POST['day'], $_POST['month'], $_POST['hour'], $_POST['minutes'], $_POST['teamA'], $_POST['teamB'], $_POST['idMatch']);
            $pageToInclude = "pages/admin/edit_games.php";
            break;

        case "edit_pronos":
            $pageToInclude = "pages/edit_pronos.php";
            break;

        case "view_pronos":
            $pageToInclude = "pages/view_pronos.php";
            break;

        case "edit_pf":
            $phase = $engine->getPhase(PHASE_ID_ACTIVE);
            if ($phase['phasePrecedente'] == NULL) {
                redirect("/?op=edit_pronos");
            }
            $pageToInclude = "pages/edit_pf.php";
            break;

        case "save_pronos":
            $userId = $_REQUEST['userId'];
            foreach ($_POST as $input => $score) {
                $ipt = strtok($input, "_");
                if ($ipt == "iptScoreTeam") {
                    $team = strtok("_");
                    $matchID = strtok("_");
                    if (!$engine->isDatePassed($matchID)) {
                        $engine->saveProno($userId, $matchID, $team, $score);
                    } else {
                        $user = $engine->getUser($userId);
                        if (($_SESSION['status'] == 1) && ($userId != $_SESSION['userID'])) {
                            $engine->saveProno($userId, $matchID, $team, $score);
                        }
                    }
                }
            }
            $pageToInclude = "pages/edit_pronos.php";
            break;

        case "save_pf":
            $userId = $_REQUEST['userId'];
            foreach ($_POST as $input => $score) {
                $ipt = strtok($input, "_");
                if ($ipt == "iptScoreTeam") {
                    $team = strtok("_");
                    $matchID = strtok("_");
                    if (isset($_POST["iptPny_" . $matchID])) {
                        $pny = $_POST["iptPny_" . $matchID];
                        $engine->saveProno($userId, $matchID, $team, $score, $pny);
                    } else {
                        $engine->saveProno($userId, $matchID, $team, $score);
                    }
                }
            }
            $pageToInclude = "pages/edit_pf.php";
            break;

        case "save_results":
            foreach ($_POST as $input => $score) {
                $ipt = strtok($input, "_");
                if ($ipt == "iptScoreTeam") {
                    $team = strtok("_");
                    $matchID = strtok("_");
                    $bonus = $_POST["sltBonus_" . $team . "_" . $matchID];
                    $pny = '';
                    if (isset($_POST["iptPny_" . $matchID])) {
                        $pny = $_POST["iptPny_" . $matchID];
                    }
                    $engine->saveResult($matchID, $team, $score, $bonus, $pny);
                }
            }
            $pageToInclude = "pages/admin/edit_results.php";
            break;

        case "rules":
            $pageToInclude = "pages/rules.php";
            break;

        case "edit_users":
            $pageToInclude = "pages/admin/users.php";
            break;

        case "add_user":
            $submit = $_POST['add_user'];
            $login = $_POST['login'];
            if ($submit == "Supprimer") {
                $engine->deleteUser($login);
            } else {
                $name = $_POST['name'];
                $pass = $_POST['pass'];
                $mail = $_POST['mail'];
                $userTeamId = $_POST['sltUserTeam'];
                $isAdmin = 0;
                if (isset($_POST['admin'])) {
                    $isAdmin = $_POST['admin'];
                }
                $engine->addOrUpdateUser($login, $pass, $name, $mail, $userTeamId, $isAdmin);
            }

            $pageToInclude = "pages/admin/users.php";
            break;

        case "join_group":
            if (isset($_POST['group'])) {
                $userID = $_SESSION['userID'];
                $password = (isset($_POST['password'])) ? $_POST['password'] : false;
                $code = (isset($_POST['code'])) ? $_POST['code'] : false;
                $status = $engine->joinUserTeam($userID, $_POST['group'], $code, $password);
                redirect("/?op=account&message=" . $status);
            } else {
                if (isset($_GET['message']) && $_GET['message']) {
                    $message = $engine->lang['messages'][$_GET['message']];
                }
                $pageToInclude = "pages/join_group.php";
            }
            break;

        case "leave_group":
            if (!isset($_GET['user_team_id'])) {
                redirect("/?op=account");
            }
            $engine->leaveuserTeam($engine->getCurrentUserId(), $_GET['user_team_id']);
            redirect("/?op=account");
            break;

        case "create_group":
            if (isset($_POST['group_name']) && isset($_POST['password1']) && isset($_POST['password2'])) {
                if ($_POST['password1'] != $_POST['password2']) {
                    redirect("/?op=create_group&message=" . PASSWORD_MISMATCH);
                }
                if ($engine->addGroup('', $_POST['group_name'], $_POST['password1'])) {
                    $group = $engine->getUserTeamByName($_POST['group_name']);
                    $engine->joinUserTeam($engine->getCurrentUserId(), $group['userTeamID'], false);
                    redirect("/?op=invite_friends&message=" . CREATE_GROUP_OK . "&g=" . $group['groupID']);
                } else {
                    redirect("/?op=create_group&message=" . GROUP_ALREADY_EXISTS);
                }
            }
            if (isset($_GET['message']) && $_GET['message']) {
                $message = $engine->lang['messages'][$_GET['message']];
            }
            $pageToInclude = "pages/create_group.php";
            break;

        case "invite_friends":
            if (isset($_POST['type'])) {
                if ($_POST['type'] == 'OUT') {
                    $invitations = array();
                    $emails = array();
                    $nb_invitations = 5;
                    for ($i = 0; $i < $nb_invitations; $i++) {
                        if ((isset($_POST['email_' . $i])) && ($_POST['email_' . $i] != "")) {
                            $invitation = array();
                            $invitation['email'] = $_POST['email_' . $i];
                            $invitation['userTeamID'] = $_POST['userTeamID'];
                            $invitations[] = $invitation;
                            $emails[] = $_POST['email_' . $i];
                        }
                    }
                    $codes = $engine->createUniqInvitations($invitations, $_POST['type']);
                    $status = $engine->sendInvitations($emails, $codes, $_POST['type']);
                    redirect("/?op=invite_friends&message=" . $status . "");
                } elseif ($_POST['type'] == 'IN') {
                    $invitations = array();
                    $emails = array();
                    $nb_invitations = 5;
                    for ($i = 0; $i < $nb_invitations; $i++) {
                        if ((isset($_POST['userID_' . $i])) && ($_POST['userID_' . $i] != "")) {
                            if ($_POST['userID_' . $i] == 0) {
                                continue;
                            }
                            $invitation = array();
                            $user = $engine->getUser($_POST['userID_' . $i]);
                            if (!$user) {
                                continue;
                            }
                            $invitation['userID'] = $_POST['userID_' . $i];
                            $invitation['email'] = $user['email'];
                            $invitation['userTeamID'] = $_POST['userTeamID'];
                            $invitations[] = $invitation;
                            $emails[] = $user['email'];
                        }
                    }
                    $codes = $engine->createUniqInvitations($invitations, $_POST['type']);
                    $status = $engine->sendInvitations($emails, $codes, $_POST['type']);
                    redirect("/?op=invite_friends&message=" . $status . "");
                } else {
                    redirect("/?op=invite_friends");
                }
            }

            $user = $engine->getCurrentUser();
            if (isset($_GET['message']) && $_GET['message']) {
                $message = $engine->lang['messages'][$_GET['message']];
            } elseif (($user['userTeamID'] == "") || ($user['userTeamID'] == 0)) {
                $message = $engine->lang['messages'][INVITE_WITHOUT_GROUP];
            }
            $pageToInclude = "pages/invite_friends.php";
            break;

        default:
            if (AUTHENTIFICATION_NEEDED) {
                $pageToInclude = "pages/login.php";
            } else {
                $pageToInclude = "pages/ranking.php";
            }
            break;
    }
}
?><!DOCTYPE html>
<html lang="fr">
    <head>
        <title><?php echo $engine->config['title']; ?></title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <meta name="viewport" content="initial-scale=1.0, user-scalable=yes" />
        <link type="text/css" rel="stylesheet" href="include/theme/<?php echo $engine->config['template']; ?>/fanions.css" />
        <link type="text/css" rel="stylesheet" href="include/theme/<?php echo $engine->config['template']; ?>/main.css" />
        <script type="text/javascript" src="/js/jquery-2.1.4.min.js"> </script>
        <script type="text/javascript" src="/js/main.js?v=16637273922"> </script>
    </head>
    <body>
        <div id="main">
<?php include_once("include/theme/" . $engine->config['template'] . "/header.php"); ?>
<?php if ($engine->isLoggedIn()) { ?>
            <nav>
                <ul class="nav-group">
                    <li class="nav-group-item"><a href="/?op=view_ranking">Classement</a></li>
                    <li class="nav-group-item"><a href="/?op=edit_pronos">Mes pronostics</a></li>
                    <li class="nav-group-item"><a href="/?op=edit_pf">Ma phase finale</a></li>
                    <li class="nav-group-item"><a href="/?op=view_results">Résultats</a></li>
                </ul>
<?php   if ($engine->admin) { ?>
                <ul class="nav-group admin">
                    <li class="nav-group-item"><a href="/?op=edit_users">Users</a></li>
                    <li class="nav-group-item"><a href="/?op=edit_results">Results</a></li>
                    <li class="nav-group-item"><a href="/?op=edit_games">Games</a></li>
                    <li class="nav-group-item"><a href="/?op=edit_teams">Teams</a></li>
                </ul>
<?php
        }
     }
?>
            </nav>
            <section id="mainarea">
<?php
if (strlen($pageToInclude) > 0) {
    include_once($pageToInclude);
}
?>
            </section>
<?php
if (isset($_SESSION["userID"])) {
    include_once("include/theme/" . $engine->config['template'] . "/footer_private.php");
} else {
    include_once("include/theme/" . $engine->config['template'] . "/footer_public.php");
}
?>
        </div>
    </body>
</html>
