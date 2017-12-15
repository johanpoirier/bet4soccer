<?php

function redirect($url)
{
	if(substr($url,0,1) == "/") {
		Header("Location: https://".$_SERVER['HTTP_HOST'].$url);
	} elseif(substr($url,0,7) == "https://") {
		Header("Location: ".$url);
	} else {
		Header("Location: https://".$_SERVER['HTTP_HOST'].final_slash(dirname($_SERVER['PHP_SELF'])).$url);
	}
	exit();
}

// The function get_moment returns a float value coresponding to the number
// of seconds since the unix epoch (1st January 1970) and the microseconds
// are precised : e.g. 1052343429.89276600
function get_moment()
{
  $t1 = explode( ' ', microtime() );
  $t2 = explode( '.', $t1[0] );
  $t2 = $t1[1].'.'.$t2[1];
  return $t2;
}

function new_password($length) {
        $exclude = array(34,39,44,47,92,96);
        mt_srand(time()); 
        $var = "";
        while (strlen($var) < $length) {
                $tmp = mt_rand(33,126); 
                if (in_array($tmp,$exclude))
                        continue; 
                $var .= chr($tmp);
        } 
        return $var;
}


function excerpt($str)
{
	$words = explode(" ",$str);
	$num_words = count($words);
	
	$excerpt="";
	for($i=0;($i <= 20 | $i < $num_words);$i++) $excerpt.=$words[$i]." ";
	$excerpt.="(...)";
	
	return $excerpt;
}


function cut_title($title,$lenght_max)
{
	$words = explode(" ",$title);
	
	$cut_lenght = $lenght_max - 3;
	
	$num_words = count($words);

	// First word too long
	if(strlen($words[0]) > $cut_lenght) return substr($words[0],0,$cut_lenght)."...";
	else $current_string = $words[0];

	$previous_string = $current_string;

	for($i=1; $i < $num_words;$i++)
	{
		$current_string .= " ".$words[$i];
		$current_lenght = strlen($current_string) + 1;
		
		// String too long
		if (($current_lenght > $cut_lenght) && ($previous_string != "")) return $previous_string."...";
		
		$previous_string = $current_string;
	}
	return $current_string;	
}
	
function explode_date($date_str)
{
	global $lang;
	$date_array = explode(" ",$date_str);
	$date_array['year'] = $date_array[0];
	$date_array['month'] = $date_array[1];
	$date_array['month_name'] = $lang['months'][$date_array[1]-1];
	$date_array['week'] = $date_array[2];
	$date_array['day'] = $date_array[3];
	$date_array['day_week'] = $lang['day_week'][$date_array[4]];
	$date_array['hour'] = $date_array[5];
	$date_array['minute'] = $date_array[6];
	$date_array['seconde'] = $date_array[7];
	
	return $date_array;
}

function create_array_from_args($name)
{
	$tmp_array = array();
	foreach($_POST as $key => $value)
	{
		$tmp = explode("_",$key);;
		if($tmp[0] == $name)
		{
			$tmp_array[substr(strstr($key,"_"),1)] = $value;
		}
	}
	return $tmp_array;
}

function array_show($array)
{
	echo "<PRE>";
	print_r($array);
	echo "</PRE>";
}


/**
 * The function get_elapsed_time returns the number of seconds (with 3 decimals precision)
 * between the start time and the end time given.
 *
 * @param $start
 * @param $end
 * @return float
 */
function get_elapsed_time($start, $end)
{
  return (float) number_format( $end - $start, 3, '.', ' ');
}

function array_vreduce($tab,$k)
{
	$i=$k;
	while(isset($tab[$i]))
	{
		$tab[$i]=$tab[++$i];
	}
	unset($tab[$i-1]);
	return $tab;
}

function unhtmlentities($string)
{
	$trans_tbl = get_html_translation_table (HTML_ENTITIES);
	$trans_tbl = array_flip ($trans_tbl);
	return strtr ($string, $trans_tbl);
}

function PostToHost($host, $path, $data_to_send)
{
	$fp = fsockopen($host,80);
	fputs($fp, "POST $path HTTP/1.1\n");
	fputs($fp, "Host: $host\n");
	fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
	fputs($fp, "Content-length: ".strlen($data_to_send)."\n"); fputs($fp,"Connection: close\n\n");
	fputs($fp, $data_to_send);
	fclose($fp);
}

function final_slash($dir)
{
	return (substr($dir, strlen($dir) -1, strlen($dir)) == "/")?$dir:$dir."/";
}		

function stripslashes_array($value)
{
   $value = is_array($value) ?
               array_map('stripslashes_array', $value) :
               stripslashes($value);

   return $value;
}

function compare_users($a, $b)
{
  if($a['points'] == $b['points']) { 
    if($a['nbresults'] == $b['nbresults']) {     
      if($a['nbscores'] == $b['nbscores']) {
          if($a['diff'] == $b['diff']) {   
            return 0;
          }
          return ($a['diff'] > $b['diff']) ? -1 : 1;
        }
      return ($a['nbscores'] > $b['nbscores']) ? -1 : 1;
      }
    return ($a['nbresults'] > $b['nbresults']) ? -1 : 1;
    }
  return ($a['points'] > $b['points']) ? -1 : 1;
}

function compare_groups($a, $b)
{
    if($a['avgPoints'] == $b['avgPoints']) {
        if($a['maxPoints'] == $b['maxPoints']) {
            return 0;
        }
        return ($a['maxPoints'] > $b['maxPoints']) ? -1 : 1;
    }
    return ($a['avgPoints'] > $b['avgPoints']) ? -1 : 1;
}

function compare_teams($a, $b)
{
    if($a['points'] == $b['points']) {
        if($a['diff'] == $b['diff']) {
        	if($a['goals_scored'] == $b['goals_scored']) {
         		return 0;
        	}
        	return ($a['goals_scored'] > $b['goals_scored']) ? -1 : 1;
        }
        return ($a['diff'] > $b['diff']) ? -1 : 1;       
    }

   return ($a['points'] > $b['points']) ? -1 : 1;
}

// Returns true if $string is valid UTF-8 and false otherwise.
function is_utf8($Str) {

	for ($i=0; $i<strlen($Str); $i++) {

		if (ord($Str[$i]) < 0x80) $n=0; # 0bbbbbbb

		elseif ((ord($Str[$i]) & 0xE0) == 0xC0) $n=1; # 110bbbbb

		elseif ((ord($Str[$i]) & 0xF0) == 0xE0) $n=2; # 1110bbbb

		elseif ((ord($Str[$i]) & 0xF0) == 0xF0) $n=3; # 1111bbbb

		else return false; # Does not match any model

		for ($j=0; $j<$n; $j++) { # n octets that match 10bbbbbb follow ?

			if ((++$i == strlen($Str)) || ((ord($Str[$i]) & 0xC0) != 0x80)) return false;

		}

	}

	return true;
}

function utf8_mail($email,$subject,$content,$from_label=false,$from_adress=false,$simulation=false) {
	// add headers for utf-8 message
	$headers = '';
 	if($from_label && $from_adress) {
 		$headers .= "From: $from_label <$from_adress>\n";
    	$headers .= "X-Sender: <$from_adress>\n";
    	$headers .= "Return-Path: <$from_adress>\n";
        //$headers .= "Bcc: <$from_adress>\n";
 	}
	$headers .= 'Content-Type: text/plain; charset="UTF-8"'."\n";
	$headers .= 'Content-Transfer-Encoding: 8bit\n'; 

	if($simulation) return true;
	else return(mail($email, $subject, $content, $headers)); 
}

function get_random($max_length=8) {
	$rand = "";
	for ($i=0; $i<$max_length; $i++) {
		$d=rand(1,30)%2;
		$rand .= $d ? chr(rand(65,90)) : chr(rand(48,57));
	} 
	return $rand;
}

function prepare_numeric_data($args)
{	
    for($i=0; $i < count($args); $i++) {
		if(!is_numeric($args[$i]) || $args[$i] == "" || $args[$i] == null) $args[$i] = 'NULL';
	}
}
 
function prepare_alphanumeric_data($args)
{	
    for($i=0; $i < count($args); $i++) {
		if($args[$i] == "" || $args[$i] == null) $args[$i] = '';
		$args[$i] = addslashes($args[$i]);
	}
}
