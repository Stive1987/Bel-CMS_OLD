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
<!doctype html>
<html class="bel-cms" lang="fr">
	<head>
		<meta charset="utf-8">
		<title>BEL-CMS # Login</title>
		<base href="<?php echo BASE_URL; ?>">
		<link rel="stylesheet" href="assets/css/bel-cms.css">
		<link rel="stylesheet" href="assets/css/modules/user.css">
		<script src="assets/js/jquery-2.1.1.min.js"></script>
		<script src="assets/js/bel-cms.js"></script>
		<script src="assets/js/modules/user.js"></script>
	</head>
	<body>
		<section id="bel_cms_user_login">
			<h1><img src="assets/img/logo_bel_cms.png" alt="BEL-CMS Logo"></h1>
			<form id="formSendLogin" action="User/send/ajax" method="post" accept-charset="utf-8">
				<input type="text" name="name" placeholder="Saisissez votre e-mail ou pseudo" required="required">
				<input type="password" name="password" placeholder="Saisissez votre mot de passe" required="required">
				<input type="hidden" name="type" value="login">
				<input type="submit" value="Identifiez-Vous">
			</form>
			<a class="bel_cms_button red" id="lostPassword" href="User/lostPassword/ajax">Mot de passe perdu</a>
			<a class="bel_cms_button blue" id="registration" href="User/registration/ajax">Inscription</a>
		</section>
	</body>
</html>
<?php
endif;
?>