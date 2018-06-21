#!/usr/bin/php
<?php

header('Content-Type: text/plain; charset=utf-8');

define('BASE_PATH', dirname(__FILE__) . '/../../');
define('WEB_PATH', '/');
define('URL_PATH', '/');

require  BASE_PATH . 'lib/betlib.php';
$engine = new BetEngine(true, false);

$bets = $engine->bets->get_incomplete_bets();
foreach ($bets as $bet) {
    echo 'User ' . $bet['userID'] . ' for game ' . $bet['matchID'] . ": set score 0 to team $team.\n";
    $team = $bet['scoreA'] === null ? 'A' : 'B';
    $engine->bets->force_add($bet['userID'], $bet['matchID'], $team, '0');
}
echo "\nOK\n";
