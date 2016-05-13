<?php
unset($config);
$config = array();

/* MAIN INFORMATIONS */
$config['blog_title'] = 'UEFA Euro 2016';
$config['blog_description'] = 'Pariez sur l\'Euro 2016.';
$config['lang'] = 'fr';
$config['url'] = 'https://euro2016.jops-dev.com/';
$config['template_default'] = 'euro2016';
$config['templates'] = array('euro2016' => 'UEFA', 'cdm2014' => 'FIFA');
$config['tag_separator'] = '<br />';
$config['show_all_users_in_team'] = true;

$config['encoding'] = 'UTF8';
$config['force_encoding_fs'] = false;

$config['support_team'] = 'UEFA Euro 2016';
$config['email'] = 'johan.poirier+euro2016@gmail.com';
$config['email_simulation'] = false;

$config['DB'] = 'MySQL';
$config['db_prefix'] = 'euro2016__';

$config['auth'] = 'md5';

$config['min_ratio_played_matches_for_group'] = 0.65;
$config['min_ratio_played_matches_for_rank'] = 0.60;
$config['invitation_expiration'] = 30; /* in days */
$config['nb_invitations'] = 5;
$config['match_display_default'] = 'date';
$config['display_empty_group'] = false;
$config['display_unactive_group'] = true;
$config['money_group_name'] = 'Cagnotte';

$config['pools'] = array('A', 'B', 'C', 'D', 'E', 'F');

$config['rounds'] = array('8', '4', '2', '1');

$config['points_pool_good_result'] = 10;
$config['points_pool_exact_score'] = 4;

$config['points_8_good_result'] = 12;
$config['points_8_exact_score'] = 5;
$config['points_8_qualify'] = 2;

$config['points_4_good_result'] = 14;
$config['points_4_exact_score'] = 6;
$config['points_4_qualify'] = 3;

$config['points_2_good_result'] = 16;
$config['points_2_exact_score'] = 7;
$config['points_2_qualify'] = 4;

$config['points_1_good_result'] = 20;
$config['points_1_exact_score'] = 9;
$config['points_1_qualify'] = 6;

