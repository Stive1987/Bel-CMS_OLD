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

# Modules/Action/ID/Pages

class GetUrl extends Define
{
	private $default_mod  = 'news';

	function __construct ()
	{
		self::constant(self::getParamUrl());
	}

	private function getParamUrl ()
	{
		$p      = (isset($_GET['file']) AND !empty($_GET['file'])) ? explode('/', rtrim($_GET['file'], '/')) : array($this -> default_mod);

		$module = (strtolower($p[0]) == 'home' OR strtolower($p[0]) == 'accueil') ? $this -> default_mod : strtolower($p[0]);

		if (in_array('ajax', $p)) {
			$action = ($p[1] == 'ajax') ? 'index' : $p[1];
		} else {
			$action = (isset($data[1]) and !empty($data[1])) ? strtolower($data[1]) : 'index';
		}

		$id           = (isset($p[2]) AND !empty($p[2])) ? remove_accent($p[2]) : 0;
		$page         = (isset($p[3]) AND !empty($p[3])) ? (int) $p[3] : '';

		$param = array(
			'GET_MODULE' => $module,
			'GET_ACTION' => $action,
			'GET_ID'     => $id,
			'GET_PAGE'   => $page
		);

		return $param;
	}
}