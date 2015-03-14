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

require dirname(__FILE__).'/core/require.php';

error_reporting(0);
set_error_handler('managementMistakes');
set_exception_handler("theManagementExceptions");
register_shutdown_function('managementOfFatalErrors');

new BelCMS;
