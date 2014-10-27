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
	protected $bel_cms_content;
	protected $bel_cms_admin;

	function __construct ()
	{
		if (defined('GET_ADMIN') && GET_ADMIN) {
			self::renderAdmin();
		} else {
			self::renderTemplate();
		}
	}

	private function getController ()
	{
		$file = ROOT.'controller/'.GET_MODULE.'/controller.class.php';
		if (!is_file($file)) {
			self::error('le fichier du controller <strong>'.GET_MODULE.'</strong> n\'existe pas');
		} else {
			require_once $file;
			if (class_exists('ControllerModule')) {
				$ControllerModule = new ControllerModule();
			} else {
				self::error('la Class ControllerModule du module <strong>'.GET_MODULE.'</strong> n\'existe pas');
			}
			if (method_exists('ControllerModule', GET_ACTION)) {
				$action = GET_ACTION;
				self::set($ControllerModule -> $action());
			} else {
				self::error('la Function '.GET_ACTION.' du module <strong>'.GET_MODULE.'</strong> n\'existe pas');
			}
		}
	}

	private function getModel ()
	{
		$file = ROOT.'model/'.GET_MODULE.'/model.class.php';
		if (is_file($file)) {
			require_once $file;
		}
	}

	private function getView ()
	{
		$data = $this -> vars;
		$fileViewModule    = ROOT.'view/'.GET_MODULE.'/'.GET_ACTION.'.tpl.php';
		$fileViewModuleTpl = ROOT.'templates/'.TEMPLATE.'/modules/'.GET_MODULE.'/'.GET_ACTION.'.tpl.php';
		if (!is_file($fileViewModule)) {
			self::error('le fichier de la vu <strong>'.GET_MODULE.' - '.GET_ACTION.'</strong> n\'existe pas');
		} else {
			//$bel_cms_comments = self::comments();
			if (is_file($fileViewModuleTpl)) {
				require_once $fileViewModuleTpl;
			} else {
				require_once $fileViewModule;
			}
		}
	}

	/**
	* Permet de verifier si une page home existe
	* @return booleen
	**/
	private function getHomePage ()
	{
		$file = ROOT.'templates/'.TEMPLATE.'/homepage.tpl.php';
		if (is_file($file)) {
			require_once $file;
		} else {
			return false;
		}
	}

	private function renderModule ()
	{
		ob_start();
		if (HOME_PAGE AND self::getHomePage() !== false) {
			self::getHomePage();
		} else {
			self::getModel();
			self::getController();
			if (!empty($this -> error)) {
				echo $this -> error;
			}
			self::getView();
		}
		$this ->  bel_cms_content = ob_get_contents();
		ob_end_clean();
	}

	private function renderTemplate ()
	{
		self::renderModule();
		if (defined('AJAX')) {
			echo $this ->  bel_cms_content;
		} else {
			require_once ROOT.'templates/'.TEMPLATE.'/template.php';
		}
	}

	/* ADMINISTRATION */

	private function getModelAdmin ()
	{
		$file = ROOT.'assets/managements/pages/'.GET_MODULE.'/model.class.php';
		if (is_file($file)) {
			require_once $file;
		}
	}

	private function getViewAdmin ()
	{
		$data = $this -> vars;
		$file = ROOT.'assets/managements/pages/'.GET_MODULE.'/'.GET_ACTION.'.tpl.php';
		if (!is_file($file)) {
			self::error('le fichier de la vu <strong>'.GET_MODULE.' - '.GET_ACTION.'</strong> n\'existe pas');
		} else {
			require_once $file;
		}
	}

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
		if (defined('AJAX')) {
			echo $this -> bel_cms_admin;
		} else {
			require_once ROOT.'assets/managements/index.php';
		}
	}

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

	private function renderAdmin ()
	{
		if (!isset($_SESSION['admin']) || $_SESSION['admin'] === false || $_SESSION['admin_time'] < time()) {
			self::renderLoginAdmin();
		} else {
			self::renderPageAdmin();
		}
	}
}