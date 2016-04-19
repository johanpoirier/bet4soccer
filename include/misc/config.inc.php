<?php
unset($config);
$config = array();

/* MAIN INFORMATIONS */
$config['title'] = "Coupe du Monde de Rugby 2015";
$config['author'] = "JoPs";
$config['email'] = "johan.poirier+cdm2015@gmail.com";
$config['lang'] = "fr";
$config['url'] = "http://cdm2015.jops-dev.com";
$config['template'] = "cdm2011";
$config['db_prefix'] = "cdm2015__";
$config['email_simulation'] = false;
$config['support_email'] = $config['email'];
$config['support_team'] = "CdM2015";

$config['invitation_expiration'] = 30; /* in days */

//                     hh,m,s,M,J,AAAA
$config['steps'][0] = "21,00,0,9,18,2015";

// rugby bet score data
$config['limite1'] = 20;
$config['ecart1a'] = 1;
$config['ecart1b'] = 4;
$config['limite2'] = 40;
$config['ecart2a'] = 3;
$config['ecart2b'] = 8;
$config['limite3'] = 60;
$config['ecart3a'] = 5;
$config['ecart3b'] = 12;
$config['ecart4a'] = 7;
$config['ecart4b'] = 20;
