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

class Config_CMS
{
	#####################################
	# Variable declaration
	#####################################
	public 	$groups;
	public  $valid;
	private $template;
	private static $def_config = array(
		'name',
		'value'
	);
	private static $def_groups = array(
		'name',
		'id_group'
	);
	#####################################
	# Start Class
	#####################################
	function __construct()
	{
		self::getGroups();
		self::getStatusUsers();
	}
	#####################################
	# Get template
	#####################################
	private function getTpl ($data)
	{
		if ($data === false) {
			$return = ROOT.'assets/template/';
		} else {
			$return = ROOT.'templates/'.$data.'/';
		}
		return $return;
	}
	#####################################
	# Get template root absolute
	#####################################
	private function getTplAccess ($data)
	{
		if ($data === false) {
			$return = ROOT_ABS.'assets/template/';
		} else {
			$return = ROOT_ABS.'templates/'.$data.'/';
		}
		return $return;
	}
	#####################################
	# Get Booleans for template full
	#####################################
	public function tplFull ()
	{
		if (!defined('CURRENT_FULL_TPL')) {
			$data = explode(',', strtolower(TPL_FULL));

			array_walk($data, 'trim_value');

			$return['CURRENT_FULL_TPL'] = (in_array(GET_MODULE, $data)) ? true : false;

			return $return;
		}
	}
	#####################################
	# Get all config in BDD
	#####################################
	public function getConfig ()
	{
		$return = array();

		$datas = array(
			'fields'     => self::$def_config,
			'table'      => TABLE_CONFIG
		);

		$results = BDD::getInstance() -> read($datas);

		if ($results)
		{
			foreach ($results as $k => $v) {
				$return[$v['name']] = $v['value'];
			}
		}

		$return['access_template'] = ROOT_ABS.'templates/'.$return['template'].'/';
		$return['name_website']    = (!isset($return['name_website']) OR empty($return['name_website'])) ? 'Bel-CMS' : $return['name_website'];
		$return['mail_admin']      = (!isset($return['mail_admin']) OR empty($return['mail_admin'])) ? 'noreturn@localhost.com' : $return['mail_admin'];
		$tpl                       = $return['template'];
		$return['template']        = (isset($tpl) && !empty($return['template'])) ? self::getTpl($tpl) : self::getTpl(false);
		$return['access_template'] = (isset($tpl) && !empty($tpl)) ? self::getTplAccess($tpl) : self::getTplAccess(false);
		$return['active_comments'] = self::Comments($return['active_comments']);


		foreach ($return as $k => $v) {
			$return[make_constant($k)] = $v;
			unset($return[$k]);
		}

		return $return;
	}
	#####################################
	# Get group and list
	#####################################
	public function getGroups()
	{
		$result = array();

		$datas = array(
			'fields'     => self::$def_groups,
			'table'      => TABLE_GROUPS
		);

		$results = BDD::getInstance() -> read($datas);

		if ($results)
		{
			foreach ($results as $k => $v) {
				$result[$v['id_group']] = $v['name'];
			}
		}

		$this -> groups = $result;
	}
	#####################################
	# Add config
	#####################################
	public function baseConfig ()
	{
		$return = array(
			#####################################
			# Réglages CMS
			#####################################
			'BASE_URL'    => 'http://'.$_SERVER['HTTP_HOST'].str_replace('index.php', '', $_SERVER['PHP_SELF']),
			'ROOT_ABS'    => str_replace('index.php', '', $_SERVER['PHP_SELF']),
			'HOME_PAGE'   => (!isset($_REQUEST['param'])) ? true : false,
			'CHECK_INDEX' => true,
		);

		return $return;
	}
	#####################################
	# Get Booleans for Module comment
	#####################################
	public function Comments ($data)
	{
		if (in_array(GET_MODULE, explode(',', $data))) {
			$return = true;
		} else {
			$return = false;
		}
		return $return;
	}
	#####################################
	# Get status user
	#####################################
	public function getStatusUsers ()
	{
		$this -> valid = array(0 => GUEST, 1 => VALID);;
	}

}
$config = new Config_CMS();
$define -> constant ($config -> baseConfig());
$define -> constant ($config -> getConfig());
$define -> constant ($config -> tplFull());
