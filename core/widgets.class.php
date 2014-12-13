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

final class widgets
{
	#####################################
	# Variable declaration
	#####################################
	protected 	$vars       = array();
	private	  	$widgetsDir = 'widgets/';
	public    	$content;
	#####################################
	# Start Class
	#####################################
	function __construct($data)
	{
		if (!array_key_exists($data, $GLOBALS['widgets'])) {
			foreach ($GLOBALS['widgets'] as $name => $value) {
				if ($value['positioning'] == $data && $value['activate']) {
					if ($value['cache'] === true) {
						$cache = new Cache(ROOT.'tmp_cache/', 60);
						if (!$cache -> start($name)) {
							self::getGlobalfunction($name, $value);
						}
						$cache -> end();
					} else {
						self::getGlobalfunction($name, $value);
					}
				}
			}
		} else {
			if ($GLOBALS['widgets'][$data]['get'] === false) {
				if ($GLOBALS['widgets'][$data]['cache'] === true) {
					$cache = new Cache(ROOT.'tmp_cache/', 60);
					if (!$cache -> start($data)) {
						self::getGlobalfunction($data, $GLOBALS['widgets'][$data]);
					}
					$cache -> end();
				} else {
					self::getGlobalfunction($data, $GLOBALS['widgets'][$data]);
				}
			}
		}
	}
	#####################################
	# Temporary insertion and function calls
	#####################################
	private function getGlobalfunction ($key, $value)
	{
		ob_start();
		self::getModel($key, $value['fileModel']);
		self::getController($key, $value['fileController']);
		self::getView($key, $value['fileView']);
		$this ->  content = ob_get_contents();
		ob_end_clean();
		$GLOBALS['widgets'][$key]['get'] = true;
		self::getGlobalView();
	}
	#####################################
	# Get main view for widgets
	#####################################
	public function getGlobalView ()
	{
		$fileGlobalView    = ROOT.$this -> widgetsDir.'view.tpl';
		$fileGlobalViewTpl = TEMPLATE.'widgets/view.tpl';

		if (file_exists($fileGlobalViewTpl)) {
			require $fileGlobalViewTpl;
		} else if (file_exists($fileGlobalView)) {
			require $fileGlobalView;
		} else {
			throw new Exception('No file view.tpl present in directory ' . $this -> widgetsDir);
		}
	}
	#####################################
	# Get view
	#####################################
	private function getView ($name, $dir)
	{
		if (file_exists($dir)) {
			require $dir;
		} else {
			throw new Exception('No file  view.tpl present in directory ' . $this -> widgetsDir.$name);
		}
	}
	#####################################
	# Get model (BDD)
	#####################################
	private function getModel ($name, $dir)
	{
		if (file_exists($dir)) {
			require_once $dir;
		}
	}
	#####################################
	# Get controller (Access)
	#####################################
	private function getController ($name, $dir)
	{
		if (file_exists($dir)) {
			$nameController = 'ControllerWidget'.ucfirst($name);
			require_once $dir;
			if (class_exists($nameController)) {
				$controller = new $nameController();
				foreach ($controller as $key => $value) {
					$this -> $key = $value;
				}
			} else {
				throw new Exception('No class exists : '. $nameController);
			}
		} else {
			throw new Exception('No file controller.class.php present in directory ' . $this -> widgetsDir.$name);
		}
	}

	public function __set ($name, $value)
	{
		$this -> vars[$name] = $value;
	}

	public function __get ($name)
	{
		return $this -> vars[$name];
	}
}
	#####################################
	# Get Widgets and list
	#####################################
class GetWidgetsList
{
	#####################################
	# Variable declaration
	#####################################
	protected	$widgetsDir = 'widgets/',
	          	$widgetsActive;
	private static $def_widgets = array(
		'id',
		'name',
		'activate',
		'groups',
		'positioning',
		'sorts',
		'cache'
	);
	#####################################
	# Start Class
	#####################################
	function __construct()
	{
		$GLOBALS['widgets'] = self::getWidgetsActive();
	}
	#####################################
	# Get name position widgets
	#####################################
	public function getWidgetsNamePosition ($data) {
		switch ($data) {
			case 1:
				$return = TOP;
				break;
			case 2:
				$return = RIGHT;
				break;
			case 3:
				$return = BOTTOM;
				break;
			case 4:
				$return = LEFT;
				break;
		}
		return $return;
	}
	#####################################
	# Get widgtes active (array)
	#####################################
	public function getWidgetsActive ()
	{
		$return = array();

		$arrayListWidgets     = array();
		$returnListWidgetsSql = self::getDataSqlWidgets();

		foreach (scan_directory(ROOT.$this -> widgetsDir) as $name) {
			if (array_key_exists($name, $returnListWidgetsSql)) {
				if ($returnListWidgetsSql[$name]['access']) {
					$arrayListWidgets[$name] = array(
						'fileModel'      => ROOT.$this -> widgetsDir.$name.'/model.class.php',
						'fileView'       => ROOT.$this -> widgetsDir.$name.'/view.tpl',
						'fileController' => ROOT.$this -> widgetsDir.$name.'/controller.class.php',
						'positioning'    => self::getWidgetsNamePosition($returnListWidgetsSql[$name]['positioning']),
						'cache'          => $returnListWidgetsSql[$name]['cache'],
						'activate'       => $returnListWidgetsSql[$name]['activate'],
						'get'            => false
					);
				}
			}
		}

		return $arrayListWidgets;
	}
	#####################################
	# Look activate widgets
	#####################################
	protected function getDataSqlWidgets ()
	{
		$return = array();
		$datas = array(
			'fields' => self::$def_widgets,
			'table'  => TABLE_WIDGETS,
			'order'  => ' ORDER BY sorts DESC'
		);

		$results = BDD::getInstance() -> read($datas);

		if ($results)
		{
			foreach ($results as $v) {
				$return[$v['name']] = array(
					'activate'    => ($v['activate'] == '0') ? false : true,
					'access'      => $this -> authorizationWidgets(explode('|', $v['groups'])),
					'positioning' => $v['positioning'],
					'cache'       => ($v['cache'] == '0') ? false : true
				);
			}
		}
		return $return;
	}
	#####################################
	# Look authorization widgets
	#####################################
	protected function authorizationWidgets (array $data)
	{
		if ($data && is_array($data)) {
			if (isset($_SESSION['groups']) && !empty($_SESSION['groups'])) {
				$groups = explode('|', $_SESSION['groups']);
				foreach ($groups as $k => $v) {
					$return = (in_array($v, $data)) ? true : false;
					if ($return) break;
				}
			} else {
				$return = (in_array(3, $data)) ? true : false;
			}
		} else { 
			$return = false;
		}
		return $return;
	}
}
