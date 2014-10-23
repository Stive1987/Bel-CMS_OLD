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

$start_microtime = microtime(true);

define ('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));
$GLOBALS['count_queries'] = null;

require dirname(__FILE__) . '/assets/include.php';

new Dispatcher();

$end_microtime   = round(microtime(true) - $start_microtime, 3).' s';