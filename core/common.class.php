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
class Common
{
	#####################################
	# Variable declaration
	#####################################
	protected $error      = null,
	          $vars       = array();
	#####################################
	# Data Error
	#####################################
	public function error ($data = null)
	{
		$this -> error =  '
			<div class="bel_cms_info_box info-error clearfix">
				<span></span>
				<div class="bel_cms_info_box-wrap">
					<h4>Error :</h4>
					<p>'.$data.'</p>
				</div>
			</div>';
	}
	#####################################
	# Get all style
	#####################################
	public function getCss ()
	{
		$listWidgetsActive = array();
		if (isset($GLOBALS['widgets']) && !empty($GLOBALS['widgets'])) {
			$listWidgetsActive = array();
			$listWidgetsDirCss = array();
			foreach ($GLOBALS['widgets'] as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $k_name => $v_del) {
						$listWidgetsActive[] = $k_name;
					}
				} else {
					$listWidgetsActive[] = $v;
				}
			}
		}

		$files          = array();
		$return         = '';
		$files[]        = ROOT_ABS.'assets/css/bel-cms.css';
		$fileModule     = ROOT.'assets/css/modules/'.GET_MODULE.'.css';
		$filesTplModule = TEMPLATE.'css/modules/'.GET_MODULE.'.css';

		if (ACTIVE_ICON == 1) {
			$files[] = ROOT_ABS.'assets/css/foundation-icons.css';
		}

		if (is_file($fileModule)) {
			$files[] = ROOT_ABS.'assets/css/modules/'.GET_MODULE.'.css';
		}

		if (is_file($filesTplModule)) {
			$files[] = ACCESS_TEMPLATE.'css/modules/'.GET_MODULE.'.css';
		}

		foreach ($listWidgetsActive as $k => $v) {
			$tmpFileRoot = ROOT.'widgets/'.$v.'/style.css';
			if (is_file($tmpFileRoot)) {
				$files[] = ROOT_ABS.'widgets/'.$v.'/style.css';
			}
		}

		foreach ($files as $k => $v) {
			$return .= '<link href="'.$v.'" rel="stylesheet" type="text/css" media="all">'."\n";
		}

		return $return;
	}
	#####################################
	# Get all JS
	#####################################
	public function getJs ()
	{
		$listWidgetsActive = array();
		if (isset($GLOBALS['widgets']) && !empty($GLOBALS['widgets'])) {
			$listWidgetsActive = array();
			$listWidgetsDirCss = array();
			foreach ($GLOBALS['widgets'] as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $k_name => $v_del) {
						$listWidgetsActive[] = $k_name;
					}
				} else {
					$listWidgetsActive[] = $v;
				}
			}
		}

		$files          = array();
		$return         = '';
		$files[]        = ROOT_ABS.'assets/js/bel-cms.js';
		$fileModule     = ROOT.'assets/js/modules/'.GET_MODULE.'.js';
		$filesTplModule = TEMPLATE.'js/modules/'.GET_MODULE.'.js';

		if (ACTIVE_JQUERY == 1) {
			$files[] = ROOT_ABS.'assets/js/jquery-2.1.1.min.js';
		}

		if (is_file($fileModule)) {
			$files[] = ROOT_ABS.'assets/js/modules/'.GET_MODULE.'.js';
		}

		if (is_file($filesTplModule)) {
			$files[] = ACCESS_TEMPLATE.'js/modules/'.GET_MODULE.'.js';
		}

		foreach ($listWidgetsActive as $k => $v) {
			$tmpFileRoot = ROOT.'widgets/'.$v.'/script.js';
			if (is_file($tmpFileRoot)) {
				$files[] = ROOT_ABS.'widgets/'.$v.'/script.js';
			}
		}

		foreach ($files as $k => $v) {
			$return .= '<script src="'.$v.'" type="text/javascript"></script>'."\n";
		}

		return $return;
	}
	#####################################
	# Redirect function
	#####################################
	public static function redirect ($url = null, $time = null)
	{
		$url  = (empty($url)) ? (empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['SERVER_NAME'] : $_SERVER['HTTP_REFERER'] : $_SERVER['HTTP_HOST'].'/'.$url;
		if (!strpos($url, 'http://')) {
			$url = 'http://'.$url;
		}
		$time = (empty($time)) ? 0 : (int) $time * 1000;
		?>
		<script>
		window.setTimeout(function() {
			window.location = '<?php echo $url; ?>';
		}, <?php echo $time; ?>);
		</script>
		<?php
	}
	#####################################
	# Set var
	#####################################
	public function set ($key,$value=null)
	{
		if (is_array($key)) {
			$this -> vars += $key;
		}
		else {
			if ($value) {
				$this -> vars[$key] = $value;
			} else {
				$this -> vars[] = $key;
			}
		}
	}

}
