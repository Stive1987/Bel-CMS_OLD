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
		$p  = (isset($_GET['file']) && !empty($_GET['file'])) ? explode('/', rtrim($_GET['file'], '/')) : array($this -> default_mod);
		$p0 = (strtolower($p[0]) == 'home' || strtolower($p[0]) == 'accueil') ? $this -> default_mod : strtolower($p[0]);

		if (isset($_GET['file']) && false !== strpos(strtolower($_GET['file']), 'ajax')) {
			define('AJAX', true);
		}

		if (!empty($p0) && $p0 == 'managements' || $p0 == 'admin') {
			$p1 = (isset($p[1]) && !empty($p[1])) ? strtolower($p[1]) : 'index';
			$p2 = (isset($p[2]) && !empty($p[2])) ? strtolower($p[2]) : 'index';
			$p3 = (isset($p[3]) && !empty($p[3])) ? (int) $p[3] : '';
			$p4 = (isset($p[4]) && !empty($p[4])) ? (int) $p[4] : '';
			$param = array(
				'GET_MODULE' => $p1,
				'GET_ACTION' => $p2,
				'GET_ID'     => $p3,
				'GET_PAGE'   => $p4,
				'GET_ADMIN'  => true
			);
		} else {
			$p1 = (isset($p[1]) && !empty($p[1])) ? strtolower($p[1]) : 'index';
			$p2 = (isset($p[2]) && !empty($p[2])) ? remove_accent($p[2]) : '';
			$p3 = (isset($p[3]) && !empty($p[3])) ? (int) $p[3] : '';
			$param = array(
				'GET_MODULE' => $p0,
				'GET_ACTION' => $p1,
				'GET_ID'     => $p2,
				'GET_PAGE'   => $p3
			);
		}

		return $param;
	}
}
new GetUrl();