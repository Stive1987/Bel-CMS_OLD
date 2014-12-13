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
if (!defined('CHECK_INDEX')) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Direct access forbidden');
    exit("<!DOCTYPE html>\r\n<html><head>\r\n<title>403 Direct access forbidden</title>\r\n</head><body>\r\n<h1>Direct access forbidden</h1>\r\n<p>The requested URL " . $_SERVER['SCRIPT_NAME'] . " is prohibited.</p>\r\n</body></html>");
}
if (!isset($_SESSION['hash_key'])):
?>
		<section id="bel_cms_user_login">
			<h1><img src="assets/img/logo_bel_cms.png" alt="BEL-CMS Logo"></h1>
			<form id="formSendLostPassword" action="User/send/ajax" method="post" accept-charset="utf-8">
				<input type="text" name="value" placeholder="Saisissez votre e-mail ou pseudo" required="required">
				<input type="text" name="token" placeholder="Saisissez votre clef si obtenu par e-mail">
				<input type="hidden" name="type" value="lostPassword">
				<input type="submit" value="Recuperer">
			</form>
		</section>
<?php
endif;
?>