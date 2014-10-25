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

	function __construct ()
	{
		self::renderTemplate();
	}

	private function getController () {
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

	protected function getView ()
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
	protected function getHomePage ()
	{
		$file = ROOT.'templates/'.TEMPLATE.'/homepage.tpl.php';
		if (is_file($file)) {
			require_once $file;
		} else {
			return false;
		}
	}

	protected function renderModule ()
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

	protected function renderTemplate ()
	{
		self::renderModule();
		if (defined('AJAX')) {
			echo $this ->  bel_cms_content;
		} else {
			require_once ROOT.'templates/'.TEMPLATE.'/template.php';
		}
	}
}