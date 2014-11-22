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

class GetUrl extends Define
{
	#####################################
	# Variable declaration
	#####################################
	private $default_mod  = 'news';
	private $url;
	public  $module,
			$action,
			$id,
			$page,
			$ajax  = false,
			$admin = false;
	#####################################
	# Start Class
	#####################################
	function __construct ()
	{
		self::getUrl ();
		self::getAdmin ();
		self::getNameModule ();
		self::getNameAction ();
		self::getId ();
		self::getPage ();
		self::getAjax ();
	}
	#####################################
	# Get url and parse parameter
	#####################################
	private function getUrl ()
	{
		$this -> url = isset($_REQUEST['param']) && !empty($_REQUEST['param']) ? explode('/', strtolower(rtrim($_REQUEST['param'], '/'))) : false;
	}
	#####################################
	# Get the name of the module request
	#####################################
	private function getNameModule ()
	{
		if ($this -> admin) {
			if (!empty($this -> url[1])) {
				$module = strtolower($this -> url[1]);
				if (in_array($module, scan_directory(ROOT.'assets/managements/pages/'))) {
					$return = $module;
				} else {
					$return = 'index';
				}
			} else {
				$return = 'index';
			}
		} else {
			if (!empty($this -> url[0])) {
				$module = strtolower($this -> url[0]);
				if (in_array($module, scan_directory(ROOT.'controller/'))) {
					$return = $module;
				} else {
					$return = $this -> default_mod;
				}
			} else {
				$return = $this -> default_mod;
			}
		}
		$this -> module = $return;
	}
	#####################################
	# Get the action of the module request
	#####################################
	private function getNameAction ()
	{
		if ($this -> admin) {
			if (!empty($this -> url[2])) {
				$return = strtolower($this -> url[2]);
			} else {
				$return = 'index';
			}
		} else {
			if (!empty($this -> url[1])) {
				$return = strtolower($this -> url[1]);
			} else {
				$return = 'index';
			}
		}
		$this -> action = $return;
	}
	#####################################
	# Get the id or rewrite name of the module request
	#####################################
	private function getId ()
	{
		if ($this -> admin) {
			if (!empty($this -> url[3])) {
				$return = (int) $this -> url[3];
			} else {
				$return = false;
			}
		} else {
			if (!empty($this -> url[2])) {
				if (is_numeric($this -> url[2])) {
					$return = (int) $this -> url[2];
				} else {
					$return = remove_accent($this -> url[2]);
				}
			} else {
				$return = false;
			}
		}
		$this -> id = $return;
	}
	#####################################
	# Get the number of the module request
	#####################################
	private function getPage ()
	{
		if ($this -> admin) {
			if (!empty($this -> url[4])) {
				if (is_numeric($this -> url[4])) {
					$return = (int) $this -> url[4];
				} else {
					$return = false;
				}
			} else {
				$return = false;
			}
		} else {
			if (!empty($this -> url[3])) {
				if (is_numeric($this -> url[3])) {
					$return = (int) $this -> url[3];
				} else {
					$return = false;
				}
			} else {
				$return = false;
			}
		}
		$this -> page = $return;
	}
	#####################################
	# Checks if it is a page in ajax
	#####################################
	private function getAjax ()
	{
		if (!empty($this -> url)) {
			foreach ($this -> url as $k) {
				if ($k == 'ajax') {
					$return = true;
					break;
				} else {
					$return = false;
				}
			}
		} else {
			$return = false;
		}
		$this -> ajax =  $return;
	}
	#####################################
	# Checks if it is a page of the administration
	#####################################
	private function getAdmin ()
	{
		if (!empty($this -> url[0])) {
			$module = strtolower($this -> url[0]);
			if ($module == 'admin' || $module == 'managements') {
				$return = true;
			} else {
				$return = false;
			}
		} else {
			$return = false;
		}
		$this -> admin =  $return;
	}
}
$getUrl = new GetUrl();
$define -> constant (array(
	'GET_MODULE' => $getUrl -> module,
	'GET_ACTION' => $getUrl -> action,
	'GET_ID'     => $getUrl -> id,
	'GET_PAGE'   => $getUrl -> page,
	'GET_AJAX'   => $getUrl -> ajax,
	'GET_ADMIN'  => $getUrl -> admin
));
