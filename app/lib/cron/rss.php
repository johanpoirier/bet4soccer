#!/usr/bin/php
<?php

define('BASE_PATH', __DIR__ . '/../../');
define('WEB_PATH', '/');
define('URL_PATH', '/');

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../betlib.php';

header("Content-Type: text/plain; charset=utf-8");

$simulation = false;
$bet = new BetEngine(false, false);

$feedIo = \FeedIo\Factory::create()->getFeedIo();
$rss = $feedIo->read($bet->config['rss_feed_url']);
$regexp = sprintf('/^$s : ([a-zA-Z\- ]*) - ([a-zA-Z\- ]*) \(score final : ([0-9])-([0-9])/', $bet->config['rss_feed_title']);

foreach ($rss->getFeed() as $item) {
    $content = $item->getTitle();
    if (preg_match($regexp, $content, $vars)) {
        $match = $bet->matches->get_by_team_names($vars[1], $vars[2]);
        if($match) {
            if($match['scoreMatchA'] == null) {
                echo "engine->games->saveResult(" . $match['matchID'] . ", " . $match['teamAid'] . ", ". $vars[3] . ");\n";
                if ($simulation === false) {
                    $bet->matches->saveResult($match['matchID'], $match['teamAid'], $match[3]);
                }
            }
            if($match['scoreMatchB'] == null) {
                echo "engine->games->saveResult(" . $match['matchID'] . ", " . $match['teamBid'] . ", ". $vars[4] . ");\n";
                if ($simulation === false) {
                    $bet->matches->saveResult($match['matchID'], $match['teamBid'], $match[4]);
                }
            }
        }
    }
}
?>
