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

class Managements
{
	#########################################
	# Variable declaration
	#########################################
	protected $sidebar, $breadcrumb, $page;
	#########################################
	# Start Class
	#########################################
	function __construct ()
	{
		if (self::checkSession()) {

			$this->sidebar    = self::menu();
			$this->breadcrumb = self::breadcrumb();
			self::getPages();

			require ROOT.'managements/index.tpl.php';

		} else {

			if (isset($_REQUEST['type']) && $_REQUEST['type'] == 'login') {

				echo json_encode(self::checkLogin($_REQUEST));

			} else {

				require ROOT.'managements/login.php';

			}

		}
		// Common::modulesAccess();
	}
	#########################################
	# Check login for admin
	#########################################
	protected function checkLogin ($data)
	{
		$return = '';

		if ($data) {

			if (!empty($data['mail']) && !empty($data['password'])) {

				$checkPass =    array(
									'fields'     => 'password',
									'table'      => TABLE_USERS,
									'where'      => array(
										 'name'  => 'mail',
										 'value' => $data['mail']
									)
				);

				$result = BDD::getInstance()->select($checkPass);

				if ($result && sizeof($result)) {

					if (!password_verify($data['password'], $result->password)) {

						$return['text']     = 'Le mot de passe ne correspondent pas avec celui du compte';
						$return['type']     = 'red';

					} else {

						$_SESSION['admin']      = true;
						$_SESSION['admin_time'] = time() + (60*60);
						$return['text']         = 'Bienvenu administrateur, vous allez être redirigé...';
						$return['redirect']     = 'Dashboard?admin';

					}

				} else {

					$return['text']     = 'Aucun compte avec cet e-mail';
					$return['type']     = 'red';

				}
			} else {

				$return['text'] = 'Le champ mot de passe et e-mail est obligatoire';
				$return['type'] = 'red';

			}
		} else {

			$return['text'] = 'Aucune donnée';
			$return['type'] = 'red';

		}
		// ***   ajouter un log sur les connexions réussi ou raté   ****
		return $return;
	}
	#########################################
	# Get Pages # include function MVC
	#########################################
	private function getPages ()
	{
		$dir = '';

		if (in_array(GET_MODULE, self::getPagesAdmin('modules'))) {

			$dir = 'modules';

		} else if (in_array(GET_MODULE, self::getPagesAdmin('param'))) {

			$dir = 'param';

		} else if (GET_MODULE == 'dashboard') {

			require ROOT.'managements/index.tpl.php';

		} else {

			require ROOT.'managements/error.tpl.php';

		}

		if (!empty($dir)) {

			$file = ROOT.'managements/pages/'.$dir.'/';

			ob_start();

			self::model($file);

			self::controller($file);

			self::view($file);

			$this->page = ob_get_contents();

			ob_end_clean();

		}
	}
	#########################################
	# Include model
	#########################################
	private function model ($dir)
	{
		$file = $dir.GET_MODULE.'/model.class.php';

		if (file_exists($file)) {

			require $file;

		}
	}
	#########################################
	# Include view
	#########################################
	private function view ($dir)
	{
		$file = $dir.GET_MODULE.'/'.GET_ACTION.'.tpl.php';

		if (file_exists($file)) {

			require $file;

		} else {

			throw new Exception('No file '.GET_ACTION.'.tpl.php present in directory '. $dir.GET_MODULE);

		}

	}
	#########################################
	# Include controller
	#########################################
	private function controller ($dir)
	{
		$file = $dir.GET_MODULE.'/controller.class.php';

		if (file_exists($file)) {

			require $file;

		} else {

			throw new Exception('No file controller.class.php present in directory '. $dir.GET_MODULE);

		}


	}
	#########################################
	# Include Cascading Style Sheets
	#########################################
	private function cascadingStyleSheets ($file)
	{
		$file = $file.GET_MODULE.'/styles.css';
	}
	#########################################
	# Include JavaScript
	#########################################
	private function javaScript ($file)
	{
		$file = $file.GET_MODULE.'/scripts.js';

		if (file_exists($file)) {

		}
	}
	#########################################
	# Check session admin and time
	#########################################
	private function checkSession ()
	{
		if (!isset($_SESSION['admin']) || $_SESSION['admin'] === false || $_SESSION['admin_time'] < time()) {

			$return = false;

		} else {

			$return = true;

		}

		return $return;
	}
	#########################################
	# Updatz time session
	#########################################
	protected function updateAdminSession ()
	{
		$_SESSION['admin_time'] = time() + (60*60);
	}
	#########################################
	# Breadcrumb
	#########################################
	private function breadcrumb ()
	{
		$breadrumb  = '<ol class="breadcrumb">';
		$breadrumb .= '<li><a href="'.self::getUrlAdmin('Dashboard?admin').'"><i class="fa fa-dashboard"></i> Home</a></li>';

		if (!defined(GET_MODULE) || (GET_MODULE != 'index')) {
			$breadrumb .= '<li><a href="'.GET_MODULE.'?admin">'.ucfirst(GET_MODULE).'</a></li>';
			if (defined(GET_ACTION) || GET_ACTION != 'index') {
				$breadrumb .= '<li><a href="'.ucfirst(GET_MODULE).'/'.GET_ACTION.'?admin">'.ucfirst(GET_ACTION).'</a></li>';
				if (defined(GET_ID)) {
					$breadrumb .= '<li><a href="'.ucfirst(GET_MODULE).'/'.GET_ACTION.'/'.GET_ID.'?admin">'.GET_ID.'</a></li>';
				}
			}
		}
		$breadrumb .= '</ol>';
		return $breadrumb;
	}
	#########################################
	# Transform url
	#########################################
	public static function getUrlAdmin ($url)
	{
		$return = BASE_URL.$url;
		return $return;
	}
	#########################################
	# Get menu for Admin
	#########################################
	private function menu ()
	{
		$sidebar  = '';
		$sidebar  =	'	<ul class="sidebar-menu">';
		$sidebar .=	'	<li class="active"><a href="'.self::getUrlAdmin('Dashboard?admin').'"><i class="fa fa-tachometer"></i> <span>Dashboard</span></a></li>';

		/* Paramètres */
		$sidebar .=	'	<li class="treeview"><a href="#"><i class="fa fa-cogs"></i> <span>Paramètres</span><i class="fa fa-angle-left pull-right"></i></a><ul class="treeview-menu">';

		foreach (self::getPagesAdmin('param') as $link) {

			$constant = (defined(Common::makeConstant($link))) ? constant(Common::makeConstant($link)) : ucfirst($link);
			$link     = self::getUrlAdmin(ucfirst($link).'?admin');
			$sidebar .=	' <li><a href="'.$link.'"><i class="fa fa-angle-double-right"> </i> <span>'.$constant.'</span></a></li>';

		}

		$sidebar .=	'	</ul></li>';
		/* Modules */
		$sidebar .=	'	<li class="treeview"><a href="#"><i class="fa fa-cubes"></i> <span>Modules</span><i class="fa fa-angle-left pull-right"></i></a><ul class="treeview-menu">';

		foreach (self::getPagesAdmin('modules') as $link) {

			$constant = (defined(Common::makeConstant($link))) ? constant(Common::makeConstant($link)) : ucfirst($link);
			$link     = self::getUrlAdmin(ucfirst($link).'?admin');
			$sidebar .=	' <li><a href="'.$link.'"><i class="fa fa-angle-double-right"> </i> <span>'.$constant.'</span></a></li>';

		}
		$sidebar .=	'	</ul></li>';

		/* Widgets */
		$sidebar .=	'	<li><a href="#"><i class="fa fa-th"></i> <span>Widgets</span></a></li>';

		return $sidebar;
	}

	#########################################
	# Listing modules and pref
	#########################################
	protected function getPagesAdmin ($type = null)
	{
		$array_dir_exclude = array('index.html', 'modules', 'param');
		$removeExt   = array('.php', '.html', '.tpl', '.tpl.php');
		$return      = array('modules' => array(), 'param' => array());
		$dirModules  = Common::scanFiles(ROOT.'managements/pages/modules/');
		$dirParam    = Common::scanFiles(ROOT.'managements/pages/param/');
		$dirIndex    = Common::scanFiles(ROOT.'managements/pages/');

		foreach ($dirModules as $v) {

			if ($v != 'index.html') {

				$return['modules'][] = $v;

			}

		}

		foreach ($dirParam as $v) {

			if ($v != 'index.html') {

				$return['param'][] = $v;

			}

		}

		foreach ($dirIndex as $v) {

			if (!in_array($v, $array_dir_exclude)) {

				$return['index'][] = str_replace($removeExt, '', $v);

			}

		}

		switch ($type) {

			case 'index':
				$return = $return['index'];
				break;

			case 'modules':
				$return = $return['modules'];
				break;

			case 'param':
				$return = $return['param'];
				break;

			default:
				$return = array_merge($return['modules'], $return['param'], $return['index']);
				break;

		}

		return $return;
	}

	public static function alert ($data = false)
	{
		switch ($data) {

			case RED:
				$color = 'alert-danger';
				$icon  = 'fa-ban';
				break;

			case BLUE:
				$color = 'alert-info';
				$icon  = 'fa-info';
				break;

			case YELLOW:
				$color = 'alert-warning';
				$icon  = 'fa-warning';
				break;

			case GREEN:
				$color = 'alert-success';
				$icon  = 'fa-check';
				break;

			default:
				$color = 'alert-info';
				$icon  = 'fa-info';
				break;
		}

		$return = array('color' => $color, 'icon' => $icon);

		return $return;
	}

}
