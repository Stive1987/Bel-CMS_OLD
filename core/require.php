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
define ('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));
if (!function_exists('password_hash')) {
	require_once ROOT.'core/password.php';
}
foreach (array(
	ROOT.'core/error.class.php',
	ROOT.'core/common.class.php',
	ROOT.'core/config.class.php',
	ROOT.'config.inc.php',
	ROOT.'core/config.table.php',
	ROOT.'core/spdo.class.php',
	ROOT.'core/user.class.php',
	ROOT.'core/dispatcher.class.php',
	ROOT.'core/modules.class.php',
	ROOT.'core/widgets.class.php',
	ROOT.'core/comments.class.php',
	ROOT.'core/managements.class.php',
	ROOT.'core/template.class.php',
	ROOT.'core/BelCMS.class.php',
) as $v) {
	include $v;
} unset($v);
