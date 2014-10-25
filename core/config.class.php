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
	protected $return;
	private static $def = array(
		'name',
		'value'
	);
	
	public function getConfig ()
	{
		$return = array();

		$datas = array(
			'fields'     => self::$def,
			'table'      => TABLE_CONFIG
		);

		$results = BDD::getInstance() -> read($datas);

		if ($results)
		{
			foreach ($results as $k => $v) {
				$return[$v['name']] = $v['value'];
			}
		}

		$return['access_template'] = 'templates/'.$return['template'].'/';
		$return['name_website'] = (!isset($return['name_website']) OR empty($return['name_website'])) ? 'Bel-CMS' : $return['name_website'];
		$return['mail_admin'] = (!isset($return['mail_admin']) OR empty($return['mail_admin'])) ? 'noreturn@localhost.com' : $return['mail_admin'];

		foreach ($return as $k => $v) {
			$return[make_constant($k)] = $v;
			unset($return[$k]);
		}

		return $return;
	}
	
	public function baseConfig ()
	{
		$return = array(
			#####################################
			# Réglages CMS
			#####################################
			'BASE_URL'  => 'http://'.$_SERVER['HTTP_HOST'],
			'HOME_PAGE' => (empty($_GET['file'])) ? TRUE : FALSE,
		);

		return $return;
	}

	public function tplFull ()
	{
		if (!defined('CURRENT_FULL_TPL')) {
			$data = explode(',', TPL_FULL);

			array_walk($data, 'trim_value');

			$return['CURRENT_FULL_TPL'] = (in_array(GET_MODULE, $data)) ? TRUE : FALSE;

			return $return;
		}
	}
}
$config = new Config_CMS();
$define -> constant ($config -> getConfig());
$define -> constant ($config -> baseConfig());
$define -> constant ($config -> tplFull());