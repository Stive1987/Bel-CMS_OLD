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

class Template
{
	#########################################
	# Variable declaration
	#########################################
	protected 	$css,
				$js,
				$rootTpl,
				$title,
				$description,
				$full;
	#########################################
	# Start Class
	#########################################
	function __construct()
	{
		self::getInfosTpl();
		self::getDirTpl();
		self::getCss();
		self::getJs();
		self::getTplFull();
		self::getTemplate();
	}
	#########################################
	# Get name website
	#########################################
	private function getInfosTpl ()
	{
		$this->title = WEBSITE_NAME;
		$this->description = WEBSITE_DESCRIPTION;
	}
	#########################################
	# Get template
	#########################################
	private function getTemplate ()
	{
		$dir = ROOT.'templates/'.self::getNameTpl().'/template.tpl.php';

		if (is_file($dir)) {
			require $dir;
		} else {
			throw new Exception('Impossible d\'accéder au template : '.self::getNameTpl());
		}
	}
	#########################################
	# Get name template
	#########################################
	public static function getNameTpl ()
	{
		$return = TEMPLATE;
		return $return;
	}
	#########################################
	# Get dir template
	#########################################
	private function getDirTpl ()
	{
		$return = ROOT_TPL.'templates/'.self::getNameTpl().'/';
		$this->rootTpl = $return;
	}
	#########################################
	# Get all css
	#########################################
	private function getCss ()
	{
		$files          = array();
		$return         = '';
		$fileModules    = ROOT.'assets/css/modules/'.GET_MODULE.'.css';
		$filesTplModule = ROOT.'templates/'.self::getNameTpl().'/modules/'.GET_MODULE.'/'.GET_MODULE.'.css';

		if (ACTIVE_CSS_IONICONS == 1) {
			$files[] = ROOT_TPL.'assets/css/ionicons.min.css';
		}

		if (ACTIVE_CSS_FANCYBOX == 1) {
			$files[] = ROOT_TPL.'assets/plugins/fancybox/jquery.fancybox-1.3.4.css';
		}

		if (ACTIVE_CSS_DEFAULT == 1) {
			$files[] = ROOT_TPL.'assets/css/bel-cms.css';
		}

		if (is_file($fileModules)) {
			$files[] = ROOT_TPL.'assets/css/modules/'.GET_MODULE.'.css';
		}

		if (is_file($filesTplModule)) {
			$files[] = $this->rootTpl.'modules/'.GET_MODULE.'/'.GET_MODULE.'.css';
		}

		foreach ($GLOBALS['widgets'] as $name => $value) {
			if ($value['activate']) {
				$file = ROOT.'widgets/'.$name.'/style.css';
				if (is_file($file)) {
					$files[] = ROOT_TPL.'widgets/'.$name.'/style.css';
				}
			}
		}

		foreach ($files as $v) {
			$return .= '		<link href="'.$v.'" rel="stylesheet" type="text/css" media="all">'."\n";
		}

		$this->css = $return;
	}
	#########################################
	# Get all javascript
	#########################################
	private function getJs ()
	{
		$files          = array();
		$return         = '';
		if (ACTIVE_JQUERY == 1) {
			$files[] = ROOT_TPL.'assets/js/jquery-2.1.1.min.js';
		}
		$files[]        = ROOT_TPL.'assets/plugins/tinymce/tinymce.min.js';
		$fileModules    = ROOT.'assets/js/modules/'.GET_MODULE.'.js';
		$filesTplModule = ROOT.'templates/'.self::getNameTpl().'/modules/'.GET_MODULE.'/'.GET_MODULE.'.js';

		if (ACTIVE_JS_DEFAULT == 1) {
			$files[] = ROOT_TPL.'assets/js/bel-cms.js';
		}

		if (ACTIVE_CSS_FANCYBOX == 1) {
			$files[] = ROOT_TPL.'assets/plugins/fancybox/jquery.fancybox-1.3.4.pack.js';
		}

		if (is_file($fileModules)) {
			$files[] = ROOT_TPL.'assets/js/modules/'.GET_MODULE.'.js';
		}

		if (is_file($filesTplModule)) {
			$files[] = $this->rootTpl.'modules/'.GET_MODULE.'/'.GET_MODULE.'.js';
		}

		foreach ($files as $v) {
			$return .= '		<script src="'.$v.'"></script>'."\n";
		}

		$this->js = $return;
	}
	#########################################
	# Get Booleans for template full
	#########################################
	public function getTplFull ()
	{
		$data = explode(',', strtolower(TPL_FULL));

		foreach ($data as $k => $v) {
			$data[$k] = trim($v);
		}

		$return = (in_array(GET_MODULE, $data)) ? true : false;

		$this->full = $return;
	}
}
