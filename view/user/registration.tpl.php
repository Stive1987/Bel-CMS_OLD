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
	<h1><img src="assets/img/logo_bel_cms.png" alt="BEL-CMS Logo"></h1>
	<form id="formSendRegistration" action="User/send/ajax" method="post" accept-charset="utf-8">
		<input type="text" name="name" placeholder="Saisissez votre nom" required="required">
		<input type="email" name="mail" placeholder="Saisissez votre e-mail" required="required">
		<input type="password" name="password" placeholder="Saisissez votre mot de passe" required="required">
		<input type="text" name="security" placeholder="1+9 en lettre (dix)" required="required">
		<input type="hidden" name="type" value="registration">
		<input type="submit" value="Enregistrez-Vous">
	</form>
	<a class="bel_cms_button red" href="User">Retour</a>
<?php
endif;
?>