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

final class Modules extends Common
{
	#####################################
	# Variable declaration
	#####################################
	protected $default_mod   = 'news';
	protected $dataDir       = 'model/';
	protected $viewDir       = 'view/';
	protected $controllerDir = 'controller/';
	protected $vars          = array();
	protected $modName;
	protected $modAction;
	protected $dataFile;
	protected $viewFile;
	protected $controllerFile;
	protected $comments;
	#####################################
	# Start Class
	#####################################
	public function __construct($name = null, $action = null)
	{
		if ($this->getAccessModules()) {
			$this->modName   = ($name == null)   ? $this->default_mod : $name;
			$this->modAction = ($action == null) ? 'index' : $action;
			if (ACTIVE_COMMENTS) {
				$comment = new Comments();
				$this->comments = $comment->content;
			}
	 		self::getModel();
			self::getController();
			self::getView();
		} else {
			echo Error::render('No access modules', ERROR);
		}
	}
	#####################################
	# Get Model (BDD)
	#####################################
	private function getModel ()
	{
		$this->dataFile = ROOT.$this->dataDir.$this->modName.'/model.class.php';

		if (file_exists($this->dataFile)) {
			require $this->dataFile;
		} else {
			throw new Exception('No file model.class.php present in directory ' . $this->dataDir.$this->modName);
		}
	}
	#####################################
	# Get View (HTML)
	#####################################
	private function getView ()
	{
		$this->viewFile     = ROOT.$this->viewDir.$this->modName.'/'.$this->modAction.'.tpl.php';
		$this->viewFileTpl  = TEMPLATE.'modules/'.$this->modName.'/'.$this->modAction.'.tpl';
		$this->viewFilePage = ROOT.$this->viewDir.$this->modName.'/index.tpl.php';

		if ($this->modName == 'pages') {
			require $this->viewFilePage;
		} else if (file_exists($this->viewFileTpl)) {
			require $this->viewFileTpl;
		} else if (file_exists($this->viewFile)) {
			require $this->viewFile;
		} else {
			throw new Exception('No file  ' . $this->modAction . '.tpl.php present in directory ' . $this->viewDir.$this->modName);
		}
	}
	#####################################
	# Get Controller (ACCESS)
	#####################################
	private function getController ()
	{
		$this->controllerFile = ROOT.$this->controllerDir.$this->modName.'/controller.class.php';

		if (file_exists($this->controllerFile)) {
			require $this->controllerFile;
			$nameController = 'ControllerModule'.ucfirst($this->modName);
			if (class_exists($nameController)) {
				if ($this->modName != 'pages') {
					if (!method_exists($nameController, $this->modAction)) {
						throw new Exception('No methods exists : '. $this->modAction);
					}
				}
				$controller = new $nameController($this->modAction);
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
				throw new Exception('No class exists : '. $nameController);
			}
		} else {
			throw new Exception('No file controller.class.php present in directory ' . $this->controllerDir.$this->modName);
		}
	}
	#####################################
	# Get access modules
	#####################################
	private function getAccessModules ()
	{
		$return = false;

		$results = BDD::getInstance() -> read(array('table'=>TABLE_MODULES));

		if ($results)
		{
			$array_groups = (isset($_SESSION['groups'])) ? explode('|', $_SESSION['groups']) : array(3);

			foreach ($results as $v) {
				if ($v['name'] == GET_MODULE) {
					if ($v['groups_access'] == 0) {
						$return = true;
					} else {
						$v['groups_access'] = explode('|', $v['groups_access']);
						foreach ($v['groups_access'] as $group) {
							if (in_array($group, $array_groups)) {
								$return = true;
								break;
							}
						}
					}
					break;
				}
			}
		}
		return $return;
	}
	#####################################
	# Value assigned to the property
	####################################
	public function __set ($name, $value)
	{
		$this->vars[$name] = $value;
	}
	public function __get ($name)
	{
		return $this->vars[$name];
	}

}
