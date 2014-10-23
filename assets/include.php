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

require_once ROOT.'assets/function.php';
require_once ROOT.'core/define.class.php';
require_once ROOT.'core/pdo.class.php';
require_once ROOT.'core/geturl.class.php';
require_once ROOT.'core/dispatcher.class.php';
