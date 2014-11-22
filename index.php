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
$GLOBALS['start_page']    = microtime(true);
$GLOBALS['count_queries'] = null;

define ('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));

require dirname(__FILE__) . '/assets/include.php';

set_error_handler('managementMistakes');
set_exception_handler("theManagementExceptions");
register_shutdown_function('managementOfFatalErrors');

new Dispatcher();
