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

final class Modules
{
	#########################################
	# Variable declaration
	#########################################
	private $nameModule,
			$nameAction,
			$requestId,
			$requestPage,
			$dirTemplate;
	#########################################
	# Start Class
	#########################################
	function __construct ($nameModule = false, $nameAction = false, $requestId = false, $requestPage = false)
	{
		$this->dirTemplate = ROOT.'templates/'.Template::getNameTpl().'/';
		$this->nameModule  = ($nameModule  === false)  ? GET_MODULE : $nameModule;
		$this->nameAction  = ($nameAction  === false)  ? GET_ACTION : $nameAction;
		$this->requestId   = ($requestId   === false)  ? GET_ID     : $requestId;
		$this->requestPage = ($requestPage === false)  ? GET_PAGE   : $requestPage;

		if (self::existsModules($this->nameModule)) {

			if (ACTIVE_COMMENTS) {
				$comment = new Comments();
				$this->comments = $comment->content;
			}

	 		self::getModel();
			self::getController();
			self::getView();
		} else {
			Common::error('404', 'The requested Module '.$this->nameModule.' was not found on this server.');
		}
	}
	#########################################
	# Get model modules
	#########################################
	private function getModel ()
	{
		$defaultFile = ROOT.'/modules/model/model.'.$this->nameModule.'.class.php';
		$customFile  = $this->dirTemplate.'modules/'.$this->nameModule.'/model.'.$this->nameModule.'.class.php';

		if (file_exists($customFile)) {
			require $customFile;
		} else if (file_exists($defaultFile)) {
			require $defaultFile;
		} else {
			throw new Exception('No file model.'.$this->nameModule.'.class.php present in directory');
		}
	}
	#########################################
	# Get view modules
	#########################################
	private function getView ()
	{
		$defaultFile = ROOT.'/modules/view/'.$this->nameModule.'/'.$this->nameAction.'.tpl.php';
		$customFile  = $this->dirTemplate.'modules/'.$this->nameModule.'/'.$this->nameAction.'.tpl.php';

		if (file_exists($customFile)) {
			require $customFile;
		} else if (file_exists($defaultFile)) {
			require $defaultFile;
		} else {
			throw new Exception('No file '.$this->nameAction.'.tpl present in directory : '.$this->nameModule);
		}
	}
	#########################################
	# Get controller modules
	#########################################
	private function getController ()
	{
		$defaultFile = ROOT.'/modules/controller/controller.'.$this->nameModule.'.class.php';
		$customFile  = $this->dirTemplate.'modules/'.$this->nameModule.'/controller.'.$this->nameModule.'.class.php';

		if (file_exists($customFile)) {
			require $customFile;
		} else if (file_exists($defaultFile)) {
			require $defaultFile;
		} else {
			throw new Exception('No file controller.'.$this->nameModule.'.class.php present in directory');
		}

		if (file_exists($customFile) || file_exists($defaultFile)) {
			$classController = 'Controller'.$this->nameModule;
			if (class_exists($classController)) {
				if (method_exists($classController, $this->nameAction)) {
					$controller = new $classController($this->nameAction);
					if (count((array)$controller) == 1) {
						$key = key($controller);
						$value = $controller->$key;
						$this->$key = $value;
					} else {
						foreach ($controller as $k => $v) {
							$this->$k = $v;
						}
					}
				} else {
					throw new Exception('No methods exists : '.$this->nameAction.' in '.$defaultFile);
				}
			} else {
				throw new Exception('No class exists : '.$classController.' in '.$defaultFile);
			}
		}
	}
	#########################################
	# Check exist module
	#########################################
	private function existsModules ($search)
	{
		$arrayModules = str_replace( array('controller.', '.class.php'), '', Common::scanFiles(ROOT.'modules/controller/', 'php'));
		$return = in_array($search, $arrayModules) ? true : false;
		return $return;
	}
}
