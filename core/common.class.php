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

class Common
{
	#########################################
	# page Error
	#########################################
	public static function error ($title = false, $data = false)
	{
		require ROOT.'assets/tpl/404.tpl.php';
	}
	#########################################
	# define constant array or simple name
	#########################################
	public static function constant ($data = false, $value = false)
	{
		if ($data) {
			if (is_array($data)) {
				foreach ($data as $constant => $tableName) {
					if (!defined($constant)) {
						define($constant, $tableName);
					}
				}
			} else {
				if ($value || $data) {
					if (!defined($constant)) {
						define($data, $value);
					}
				}
			}
		}
	}
	#########################################
	# debug
	#########################################
	public static function debug ($data = null, $exitAfter = false)
	{
		echo '<div id="bel_cms_debug"><pre>';
		var_dump($data);
		echo '</pre></div>';
		if ($exitAfter === true) {
			exit();
		}
	}
	#########################################
	# clear url and constant name
	#########################################
	public static function makeConstant ($data) {
		$characters = array(
			'À' => 'a', 'Á' => 'a', 'Â' => 'a', 'Ä' => 'a', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ä' => 'a', '@' => 'a',
			'È' => 'e', 'É' => 'e', 'Ê' => 'e', 'Ë' => 'e', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', '€' => 'e',
			'Ì' => 'i', 'Í' => 'i', 'Î' => 'i', 'Ï' => 'i', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
			'Ò' => 'o', 'Ó' => 'o', 'Ô' => 'o', 'Ö' => 'o', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'ö' => 'o',
			'Ù' => 'u', 'Ú' => 'u', 'Û' => 'u', 'Ü' => 'u', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'µ' => 'u',
			'Œ' => 'oe', 'œ' => 'oe',
			'$' => 's');
		$return = strtr($data, $characters);
		$return = preg_replace('#[^A-Za-z0-9]+#', '_', $return);
		$return = trim($return, '-');
		$return = strtoupper($data);

		return $return;
	}
	#########################################
	# Redirect function
	#########################################
	public static function redirect ($url = null, $time = null)
	{
		$url  = (empty($url)) ? (empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['SERVER_NAME'] : $_SERVER['HTTP_REFERER'] : $_SERVER['HTTP_HOST'].'/'.$url;
		if (!strpos($url, 'http://')) {
			$url = 'http://'.$url;
		}
		$time = (empty($time)) ? 0 : (int) $time * 1000;
		?>
		<script>
		window.setTimeout(function() {
			window.location = '<?php echo $url; ?>';
		}, <?php echo $time; ?>);
		</script>
		<?php
	}
	#########################################
	# Refresh page
	#########################################
	public static function refresh ($page = null, $time = null)
	{
		if ($page === null) {
			$page = $_SERVER['PHP_SELF'];
		}
		if ($time === null) {
			$time = "0";
		}
		header("Refresh: $time; url=$page");
	}
	#########################################
	# Scan directory
	#########################################
	public static function scanDirectory ($dir = false) {

		$return = array();

		if ($dir) {

			$myDirectory = opendir($dir) or die('Error scan directory');

			while($entry = @readdir($myDirectory)) {

				if (is_dir($dir.'/'.$entry) && $entry != '.' && $entry != '..') {

					$return[] = $entry;

				}

			}

			closedir($myDirectory);
		}

		return $return;
	}
	#########################################
	# Scan file
	#########################################
	public static function scanFiles ($dir, $ext = false, $full_access = false) {

		$return = array();

		if (is_dir($dir)) {

			if ($dh = opendir($dir)) {

				while (($file = readdir($dh)) !== false) {

					if ($file != '.' && $file != '..') {

						if ($ext) {

							$fileExt = substr ($file, -3);

							if (is_array($ext)) {

								if (array_search($fileExt, $ext)) {

									$return[] = ($full_access) ? $dir.$file : $file;
								}

							} else {

								if ($fileExt == $ext) {

									$return[] = ($full_access) ? $dir.$file : $file;

								}

							}

						} else {

							$return[] = ($full_access) ? $dir.$file : $file;
						}
					}
				}

				closedir($dh);

			}

		}

		return $return;
	}
	#########################################
	# Get IP
	#########################################
	public static function getIp () {

		if (isset($_SERVER['HTTP_CLIENT_IP'])) {

			$return =  $_SERVER['HTTP_CLIENT_IP'];

		}

		elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {

			$return =  $_SERVER['HTTP_X_FORWARDED_FOR'];

		}

		else {

			$return =  (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
		}

		if ($return == '::1') {

			$return = 'localhost';

		}

		return $return;
	}
	#########################################
	# Transform date
	#########################################
	public static function transformDate ($date, $time = false, $custom = false)
	{
		if (!empty($custom)) {

			$date = new DateTime($date);
			$return = $date->format($custom);

		} else {

			if ($time) {

				$date = new DateTime($date);
				$return = $date->format('d/m/Y H:i:s');

			} else {

				$date = new DateTime($date);
				$return = $date->format('d/m/Y');

			}
		}

		return $return;
	}
	#########################################
	# Send Mail
	#########################################
	public static function sendMail(array $data)
	{
		$fromName = WEBSITE_NAME;
		$fromMail = MAIL_ADMINISTRATOR;
		$subject  = (isset($data['subject']) AND !empty($data['subject'])) ? $data['subject'] : 'Mail test Bel-CMS';
		$content  = (isset($data['content']) AND !empty($data['content'])) ? $data['content'] : 'Ceci est un test mail';
		$sendMail = (isset($data['sendMail']) AND !empty($data['sendMail'])) ? $data['sendMail'] : false;

		if ($sendMail) {

			if (filter_var($sendMail, FILTER_VALIDATE_EMAIL)) {

				$headers   = array();
				$headers[] = "MIME-Version: 1.0";
				$headers[] = 'Content-Type: text/html; charset="utf-8"';
				$headers[] = "From: {$fromName} <{$fromMail}>";
				$headers[] = "Reply-To: NoReply <{$fromMail}>";
				$headers[] = "X-Mailer: PHP/".phpversion();
				$return = @mail($sendMail, $subject, $content, implode("\n", $headers));

			} else {

				$return = false;

			}

		} else {

			$return = false;

		}

		return $return;
	}

}
