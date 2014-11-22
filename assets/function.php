<?php
/**
 * Bel-CMS [Content management system]
 * @version 0.0.1
 * @link http://www.bel-cms.be
 * @link http://www.stive.eu
 * @license http://opensource.org/licenses/GPL-3.0 copyleft
 * @copyright 2014 Bel-CMS
 * @author Stive - mail@stive.eu
 */
date_default_timezone_set("Europe/Brussels");

function debug ($var = null, $exitAfter = false) {
	echo '<div style="width:100%;margin: 15px auto;padding:5px 10px;font-size:14px;background:#F1F1F1;color:black;"><pre>';
	var_dump($var);
	echo '</pre></div>';
	if ($exitAfter === true) {
		exit();
	}
}

function get_local () {
	return (strpos($_SERVER['HTTP_HOST'], 'localhost') === 0 OR strpos($_SERVER['HTTP_HOST'], '127.0.0') === 0 OR strpos($_SERVER['HTTP_HOST'], '192.168') === 0) ? true : false;
}

function get_ip () {
	if (isset($_SERVER['HTTP_CLIENT_IP'])) {
		return $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else {
		return (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
	}
}

function trim_value (&$value) {
	$value = trim($value);
}
function cutText($text, $length, $ending = '...', $exact = false) {
	if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
		return $text;
	}
	preg_match_all('/(<.+?>)?([^<>]*)/is', $text, $matches, PREG_SET_ORDER);
	$total_length = 0;
	$arr_elements = array();
	$truncate = '';
	foreach($matches as $element) {
		if (!empty($element[1])) {
			if(preg_match('/^<\s*.+?\/\s*>$/s', $element[1])) {
			} elseif(preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $element[1], $element2)) {
				$pos = array_search($element2[1], $arr_elements);
				if($pos !== false) {
					unset($arr_elements[$pos]);
				}
			} elseif(preg_match('/^<\s*([^\s>!]+).*?>$/s', $element[1], $element2)) {
				array_unshift($arr_elements,
				strtolower($element2[1]));
			}
			$truncate .= $element[1];
		}
		$content_length = strlen(preg_replace('/(&[a-z]{1,6};|&#[0-9]+;)/i', ' ', $element[2]));
		if ($total_length >= $length) {
			break;
		} elseif ($total_length+$content_length > $length) {
			$left = $total_length>$length?$total_length-$length:$length-$total_length;
			$entities_length = 0;
			if(preg_match_all('/&[a-z]{1,6};|&#[0-9]+;/i', $element[2], $element3, PREG_OFFSET_CAPTURE)) {
				foreach($element3[0] as $entity) {
					if($entity[1]+1-$entities_length <= $left) {
						$left--;
						$entities_length += strlen($entity[0]);
					} else break;
				}
			}
			$truncate .= substr($element[2], 0, $left+$entities_length);
			break;
		} else {
			$truncate .= $element[2];
			$total_length += $content_length;
		}
	}
	if (!$exact) {
		$spacepos = strrpos($truncate, ' ');
		if (isset($spacepos)) {
			$truncate = substr($truncate, 0, $spacepos);
		}
	}
	$truncate .= $ending;
	foreach($arr_elements as $element) {
		$truncate .= '</' . $element . '>';
	}
	return $truncate;
}
/**
* @return src gravatar or avatar default
*/
function get_gravatar ($mail, $size = 100) {
	$default = 'includes/img/nkAvatar.jpg';
	if (empty($mail)) {
		$return = $default;
	} else {
		$return = 'http://www.gravatar.com/avatar/'.md5(strtolower(trim($mail))).'?d='.urlencode('https://gravatar.com/images/grav-logo-2x.png').'&s='.$size;
	}
	return $return;
}

function size ($fichier, $dir = false) {
	if ($dir === false) {
	// Lecture de la taille du fichier
		$size = filesize($fichier);
	} else if ($dir === true) {
		$size = $fichier;
	}
	// Conversion en Go, Mo, Ko
	if ($size >= 1073741824){
		$size = round($size / 1073741824 * 100) / 100 . " Go";
	} elseif ($size >= 1048576) {
		$size = round($size / 1048576 * 100) / 100 . " Mo";
	} elseif ($size >= 1024) {
		$size = round($size / 1024 * 100) / 100 . " Ko";
	} else {
		$size = $size . " o";
	}
	if ($size==0) {
		$size="-";
	}
	return $size;
}

function dir_size ($dir = false) {
	$size = null;
	if ($dir && is_dir($dir)) {
		if (substr($dir,-1) != "/" ) $dir .= "/";
		if ($dir_id = opendir($dir)) {
			while (($item = readdir($dir_id)) !== false) {
				if ($item != "." && $item != ".." ) {
					if (is_dir($dir . $item)) {
						$size += dir_size($dir . $item);
					} else {
						$size += filesize($dir . $item);
					}
				}
			}
			closedir($dir_id);
		}
	}
	return $size;
}

function scan_directory($dir) {
	$list_dir = array();
	$my_directory = opendir($dir) or die('Error');
	while($entry = @readdir($my_directory)) {
		if (is_dir($dir.'/'.$entry) && $entry != '.' && $entry != '..') {
			$list_dir[] = $entry;
		}
	}
	closedir($my_directory);
	return $list_dir;
}

function return_bytes ($val) {
	$val = trim($val);
	$last = strtolower($val[strlen($val)-1]);
	switch($last) {
		// Le modifieur 'G' est disponible depuis PHP 5.1.0
		case 'g':
			$val *= 1024;
		case 'm':
			$val *= 1024;
		case 'k':
			$val *= 1024;
	}

	return $val;
}

function remove_accent ($str)
{
	$characters = array(
		'À' => 'a', 'Á' => 'a', 'Â' => 'a', 'Ä' => 'a', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ä' => 'a', '@' => 'a',
		'È' => 'e', 'É' => 'e', 'Ê' => 'e', 'Ë' => 'e', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', '€' => 'e',
		'Ì' => 'i', 'Í' => 'i', 'Î' => 'i', 'Ï' => 'i', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
		'Ò' => 'o', 'Ó' => 'o', 'Ô' => 'o', 'Ö' => 'o', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'ö' => 'o',
		'Ù' => 'u', 'Ú' => 'u', 'Û' => 'u', 'Ü' => 'u', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'µ' => 'u',
		'Œ' => 'oe', 'œ' => 'oe',
		'$' => 's');
 
	$return = strtr($str, $characters);
	$return = preg_replace('#[^A-Za-z0-9]+#', '-', $return);
	$return = trim($return, '-');
	$return = strtolower($return);
 
	return $return;
}

function make_constant ($str) {
	$characters = array(
		'À' => 'a', 'Á' => 'a', 'Â' => 'a', 'Ä' => 'a', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ä' => 'a', '@' => 'a',
		'È' => 'e', 'É' => 'e', 'Ê' => 'e', 'Ë' => 'e', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', '€' => 'e',
		'Ì' => 'i', 'Í' => 'i', 'Î' => 'i', 'Ï' => 'i', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
		'Ò' => 'o', 'Ó' => 'o', 'Ô' => 'o', 'Ö' => 'o', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'ö' => 'o',
		'Ù' => 'u', 'Ú' => 'u', 'Û' => 'u', 'Ü' => 'u', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'µ' => 'u',
		'Œ' => 'oe', 'œ' => 'oe',
		'$' => 's');
	$return = strtr($str, $characters);
	$return = preg_replace('#[^A-Za-z0-9]+#', '_', $return);
	$return = trim($return, '-');
	$return = strtoupper($str);
	
	return $return;
}