#!/usr/bin/php
<?php

header('Content-Type: text/plain; charset=utf-8');

define('BASE_PATH', dirname(__FILE__) . '/../../');
define('WEB_PATH', '/');
define('URL_PATH', '/');

require  BASE_PATH . 'lib/betlib.php';
$bet = new BetEngine(false, false);

$daysToCheck = 1;
$gameCount = $bet->matches->get_nb_matchs_in_the_next_n_days($daysToCheck);
if ($gameCount === 0) {
  echo "No games\n";
  exit;
}

$users = $bet->users->get_active_users_who_have_not_bet($daysToCheck, $gameCount);
foreach ($users as $user) {
    echo $user['email'] . "\n";
    $bet->emailer->send($user['email'], $user['name'], $bet->config['blog_title'] . ' - Matchs à pronostiquer', 'Bonjour ' . $user['name'] . ",\n\nIl y a des matchs dans moins de 24H et vous n'avez toujours pas pronostiqué !\n\nRendez-vous sur " .$bet->config['url'] . " pour voter.\n\nCordialement,\nL'équipe " . $bet->config['support_team'] . "\n");
}
echo "\nOK\n";
