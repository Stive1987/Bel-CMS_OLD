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
if (basename (__FILE__) == basename ($_SERVER['SCRIPT_FILENAME'])) { die ('Direct access forbidden'); }

if (get_local()) {
	$array = array(
		#####################################
		# Réglages MySQL - LOCAL
		#####################################
		'DB_NAME'     => 'websites',
		'DB_USER'     => 'root',
		'DB_PASSWORD' => 'usbw',
		'DB_HOST'     => 'localhost',
		'DB_PREFIX'   => '',
		'DB_PORT'     => '3306'
		);
} else {
	$array = array(
		#####################################
		# Réglages MySQL - WEBSITE
		#####################################
		'DB_NAME'     => '*****',
		'DB_USER'     => '*****',
		'DB_PASSWORD' => '*****',
		'DB_HOST'     => 'localhost',
		'DB_PREFIX'   => '',
		'DB_PORT'     => '3306'
	);
}