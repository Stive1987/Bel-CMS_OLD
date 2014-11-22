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

class Dispatcher extends Common
{
	#####################################
	# Variable declaration
	#####################################
	protected $bel_cms_content;
	protected $bel_cms_admin;
	#####################################
	# Start Class
	#####################################
	function __construct ()
	{
		new GetWidgetsList();
		if (defined('GET_ADMIN') && GET_ADMIN) {
			self::renderAdmin();
		} else {
			$GLOBALS['time_loading_page'] = round(microtime(true) - $GLOBALS['start_page'], 3).' s';
			self::renderTemplate();
		}
	}
	#####################################
	# Proceedings of the theme
	#####################################
	private function renderTemplate ()
	{
		if (GET_AJAX) {
			new modules(GET_MODULE, GET_ACTION);
		} else {
			require_once TEMPLATE.'template.php';
		}
	}
	#####################################
	# Get model for adminitrator
	#####################################
	private function getModelAdmin ()
	{
		$file = ROOT.'assets/managements/pages/'.GET_MODULE.'/model.class.php';
		if (is_file($file)) {
			require_once $file;
		}
	}
	#####################################
	# Get view for adminitrator
	#####################################
	private function getViewAdmin ()
	{
		if (GET_MODULE != 'index') {
			$data = $this -> vars;
			$file = ROOT.'assets/managements/pages/'.GET_MODULE.'/'.GET_ACTION.'.tpl.php';
			if (!is_file($file)) {
				self::error('le fichier de la vu <strong>'.GET_MODULE.' - '.GET_ACTION.'</strong> n\'existe pas');
			} else {
				require_once $file;
			}
		} else {
			$file = ROOT.'assets/managements/pages/index.tpl.php';
			require_once $file;
		}
	}
	#####################################
	# Get controller for adminitrator
	#####################################
	private function getControllerAdmin ()
	{
		if (GET_MODULE != 'index') {
			$file = ROOT.'assets/managements/pages/'.GET_MODULE.'/controller.class.php';
			if (!is_file($file)) {
				self::error('le fichier du controller Admin <strong>'.GET_MODULE.'</strong> n\'existe pas');
			} else {
				require_once $file;
				if (class_exists('ControllerModuleAdmin')) {
					$ControllerModuleAdmin = new ControllerModuleAdmin();
				} else {
					self::error('la Class ControllerModuleAdmin du module <strong>'.GET_MODULE.'</strong> n\'existe pas');
				}
				if (method_exists('ControllerModuleAdmin', GET_ACTION)) {
					$action = GET_ACTION;
					self::set($ControllerModuleAdmin -> $action());
				} else {
					self::error('la Function '.GET_ACTION.' du module admin : <strong>'.GET_MODULE.'</strong> n\'existe pas');
				}
			}
		}
	}
	#####################################
	# Get render for adminitrator
	#####################################
	private function renderPageAdmin ()
	{
		require_once ROOT.'assets/managements/commonadmin.class.php';
		ob_start();
		self::getModelAdmin();
		self::getControllerAdmin();
		if (!empty($this -> error)) {
			echo $this -> error;
		}
		self::getViewAdmin();
		$this -> bel_cms_admin = ob_get_contents();
		ob_end_clean();
		if (GET_AJAX) {
			echo $this -> bel_cms_admin;
		} else {
			require_once ROOT.'assets/managements/index.php';
		}
	}
	#####################################
	# Get render for adminitrator login
	#####################################
	private function renderLoginAdmin ()
	{
		require_once ROOT.'assets/managements/commonadmin.class.php';
		ob_start();
		if (isset($_POST) && (!empty($_POST))) {
			if ($_POST['type'] == 'login') {
				$return = array(
					'text'       => $admin -> checkLogin($_POST),
					'linkReturn' => 'Admin'
				);
			} else {
				$return = array(
					'text'       => 'Error Post',
					'linkReturn' => 'Admin'
				);
			}
			echo json_encode($return);
		} else {
			$file = ROOT.'assets/managements/login.php';
			if (!is_file($file)) {
				self::error('le fichier <strong>Login</strong> n\'existe pas');
			} else {
				require_once $file;
			}
		}
		$this -> bel_cms_admin = ob_get_contents();
		ob_end_clean();
		if (!empty($this -> error)) {
			echo $this -> error;
		}
		echo $this -> bel_cms_admin;
	}
	#####################################
	# Proceedings of the adminitrator
	#####################################
	private function renderAdmin ()
	{
		if (!isset($_SESSION['admin']) || $_SESSION['admin'] === false || $_SESSION['admin_time'] < time()) {
			self::renderLoginAdmin();
		} else {
			self::renderPageAdmin();
		}
	}
}
