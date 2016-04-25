#!/usr/local/bin/php
<?php

header("Content-Type: text/plain; charset=utf-8");

define('WEB_PATH', "/");
define('BASE_PATH', "../../");
define('URL_PATH', "/");

require( BASE_PATH . 'lib/betlib.php');
$bet = new BetEngine(false, false);

$nbDaysToCheck = 1;
$nbGames = $bet->matches->get_nb_matchs_in_the_next_n_days($nbDaysToCheck);
if ($nbGames > 0) {
    $users = $bet->users->get_active_users_who_have_not_bet($nbDaysToCheck, $nbGames);
    foreach ($users as $user) {
        echo $user['email'] . "\n";
        utf8_mail($user['email'], $bet->config['blog_title'] . " - Matchs à pronostiquer", "Bonjour " . $user['login'] . ",\n\nIl y a des matchs dans moins de 24H et vous n'avez toujours pas pronostiqué !\n\nRendez-vous sur " .$bet->config['url'] . " pour voter.\n\nCordialement,\nL'équipe " . $bet->config['support_team'] . "\n", $bet->config['blog_title'], $bet->config['email'], $bet->config['email_simulation']);
    }
    echo "\nOK\n";
}
else {
    echo "No games\n";
}
?>
