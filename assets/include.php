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
if (!function_exists('password_hash')) {
	require_once ROOT.'core/password.php';
}
require_once ROOT.'core/cache.class.php';
require_once ROOT.'assets/function.php';
require_once ROOT.'core/error.class.php';
require_once ROOT.'core/define.class.php';
require_once ROOT.'lang/lang.fr.php';
require_once ROOT.'core/table.php';
require_once ROOT.'core/common.class.php';
require_once ROOT.'core/pdo.class.php';
require_once ROOT.'core/geturl.class.php';
require_once ROOT.'core/config.class.php';
require_once ROOT.'core/users.class.php';
require_once ROOT.'core/dispatcher.class.php';
require_once ROOT.'core/widgets.class.php';
require_once ROOT.'core/comments.class.php';
require_once ROOT.'core/modules.class.php';
require_once ROOT.'core/homepage.class.php';
