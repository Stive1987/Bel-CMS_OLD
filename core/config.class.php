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

class Config
{
	#########################################
	# Start Class
	#########################################
	function __construct()
	{
		self::timeZone();
		self::getGroups();
		self::getConfig();
	}
	#########################################
	# TimeZone config
	#########################################
	private function timeZone ()
	{
		date_default_timezone_set("Europe/Brussels");
		setlocale(LC_TIME, "fr_FR", "fr_FR@euro", "fr", "FR", "fra_fra", "fra");
	}
	#########################################
	# Get config and insert define
	#########################################
	private function getConfig ()
	{
		$results = BDD::getInstance()->selectMultiple(
			array(
				'table'  => 'config',
				'fields' => array('name','value')
			)
		);

		foreach ($results as $v) {
			$return[Common::makeConstant($v->name)] = $v->value;
		}

		Common::constant($return); unset($return);

		$baseConfig = array(
			#########################################
			# Réglages CMS
			#########################################
			'BASE_URL'        => 'http://'.$_SERVER['HTTP_HOST'].str_replace('index.php', '', $_SERVER['PHP_SELF']),
			'ROOT_TPL'        => str_replace('index.php', '', $_SERVER['PHP_SELF']),
			'HOME_PAGE'       => (!isset($_REQUEST['param'])) ? true : false,
			'CHECK_INDEX'     => true,
			'ACTIVE_COMMENTS' => self::Comments(ACTIVE_COMMENTS),
			'ERROR_INDEX'     => '<!DOCTYPE html>\r\n<html><head>\r\n<title>403 Direct access forbidden</title>\r\n</head><body>\r\n<h1>Direct access forbidden</h1>\r\n<p>The requested URL '.$_SERVER['SCRIPT_NAME'].' is prohibited.</p>\r\n</body></html>',
		);

		Common::constant($baseConfig); unset($baseConfig);

		$fileLang = ROOT.'lang/base.lang.'.LANG.'.php';

		if (is_file($fileLang)) {
			include $fileLang;
		}
	}
	#########################################
	# Get Booleans for Module comment
	#########################################
	public function Comments ($data)
	{
		if (in_array(GET_MODULE, explode(',', $data))) {
			$return = true;
		} else {
			$return = false;
		}
		return $return;
	}
	#########################################
	# Request ID or rewrite_name secure
	#########################################
	public static function requestId ($data = false) {

		$return = false;

		if ($data) {
			if (ctype_digit($data)) {

				$return = intval($data);

			} else {

				$return = Common::makeConstant($data);

			}
		}

		return $return;
	}
	#########################################
	# Get group and list
	#########################################
	public function getGroups ()
	{
		$result = array();

		$datas = array(
			'fields' => 'id, name, id_group',
			'table'  => TABLE_GROUPS
		);

		$results = BDD::getInstance()->selectMultiple($datas);

		if ($results) {

			foreach ($results as $v) {
				$result[$v->id_group] = $v->name;
			}

		}

		$GLOBALS['groups'] = $result;
	}
	#########################################
	# List modules
	#########################################
	public static function listModules ($data)
	{
		switch (strtolower($data)) {

			case 'news':
				$return = TABLE_NEWS;
				break;

			case 'pages':
				$return = TABLE_PAGES;
				break;

			default:
				$return = false;
				break;

		}
		return $return;
	}
	#########################################
	# Get List modules
	#########################################
	public static function modulesAccess ()
	{
		$return = '';

		if (isset($GLOBALS['modules']) && !empty($GLOBALS['modules'])) {

			$return = $GLOBALS['modules'];

		} else {

			$results = BDD::getInstance()->selectMultiple(
				array(
					'table'  => TABLE_MODULES,
					'fields' => array('id', 'name_module', 'access_groups', 'admin_access_groups', 'admin_sub_access_groups', 'status')
				)
			);

			if ($results && sizeof($results)) {

				foreach ($results as $v) {

					$return[$v->name_module] = array(
						'access'    => explode(',', $v->access_groups),
						'admin'     => explode(',', $v->admin_access_groups),
						'admin_sub' => explode(',', $v->admin_sub_access_groups),
						'status'    => $v->status == 0 ? false : true
					);

				}

				$GLOBALS['modules'] = $return;

			}

		}

		return $return;

	}
}
