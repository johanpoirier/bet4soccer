#!/usr/bin/php
<?php

header("Content-Type: text/plain; charset=utf-8");

define('BASE_PATH', dirname(__FILE__) . '/../../');
define('WEB_PATH', '/');
define('URL_PATH', '/');

require( BASE_PATH . 'lib/engine.php');

$simulation = true;
$engine = new Engine(false, false);

define('MAGPIE_DIR', '../rss/');
require_once(MAGPIE_DIR . 'rss_fetch.inc');

$rss = fetch_rss($engine->config['rss_feed_url']);
$regexp = sprintf('/^$s : ([a-zA-Z\- ]*) - ([a-zA-Z\- ]*) \(score final : ([0-9])-([0-9])/', $engine->config['rss_feed_title']);

foreach ($rss->items as $item) {
    $content = $item['title'];
    if (preg_match($regexp, $content, $vars)) {
        $match = $engine->matches->get_by_team_names($vars[1], $vars[2]);
        if($match) {
            if($match['scoreMatchA'] == null) {
                echo "engine->games->saveResult(" . $match['matchID'] . ", " . $match['teamAid'] . ", ". $vars[3] . ");\n";
                if ($simulation === false) {
                    $engine->games->saveResult($match['matchID'], $match['teamAid'], $match[3]);
                }
            }
            if($match['scoreMatchB'] == null) {
                echo "engine->games->saveResult(" . $match['matchID'] . ", " . $match['teamBid'] . ", ". $vars[4] . ");\n";
                if ($simulation === false) {
                    $engine->games->saveResult($match['matchID'], $match['teamBid'], $match[4]);
                }
            }
        }
    }
}
?>
