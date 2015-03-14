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

class Dispatcher
{
	#########################################
	# Variable declaration
	#########################################
	private $url;
	private $moduleDefault = 'news';
	#########################################
	# Start Class
	#########################################
	function __construct ()
	{
		$this->url = isset($_REQUEST['param']) && !empty($_REQUEST['param']) ? explode('/', strtolower(rtrim($_REQUEST['param'], '/'))) : false;
		unset($_REQUEST['param']);
		common::constant (array(
			'GET_MODULE' => self::nameModule(),
			'GET_ACTION' => self::nameAction(),
			'GET_ID'     => self::requestId(),
			'GET_PAGE'   => self::requestPage(),
			'GET_AJAX'   => self::requestAjax(),
			'GET_ADMIN'  => self::requestAdmin()
			)
		);
	}
	#########################################
	# Checks if it is a page in ajax
	#########################################
	private function requestAjax ()
	{
		return isset($_GET['ajax']) ? true : false;
	}
	#########################################
	# Checks if it is a page in ajax
	#########################################
	private function requestAdmin ()
	{
		return isset($_GET['admin']) ? true : false;
	}
	#########################################
	# Set name module
	#########################################
	private function nameModule ()
	{
		if (self::requestAdmin()) {

			$return = isset($this->url[0]) && !empty($this->url[0]) ? $this->url[0] : 'Dashboard';

		} else {

			$return = isset($this->url[0]) && !empty($this->url[0]) ? $this->url[0] : $this->moduleDefault;
			$return = $return == 'home' ? 'news' : $return;

		}
		return $return;
	}
	#########################################
	# Set name action
	#########################################
	private function nameAction ()
	{
		return isset($this->url[1]) && !empty($this->url[1]) ? $this->url[1] : 'index';
	}
	#########################################
	# Set id
	#########################################
	private function requestId ()
	{
		return isset($this->url[2]) && !empty($this->url[2]) ? $this->url[2] : false;
	}
	#########################################
	# Set page
	#########################################
	private function requestPage ()
	{
		return isset($this->url[3]) && !empty($this->url[3]) ? $this->url[3] : false;
	}
}
