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
	protected $error    = null,
			  $vars     = array();

	protected function error ($data = null)
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

	public function getCss ()
	{
		$files      = array();
		$return     = '';
		$files[]    = 'assets/css/bel-cms.css';
		$fileModule = ROOT.'assets/css/modules/'.GET_MODULE.'.css';

		if (ACTIVE_ICON == 1) {
			$files[] = 'assets/css/foundation-icons.css';
		}

		if (is_file($fileModule)) {
			$files[] = 'assets/css/modules/'.GET_MODULE.'.css';
		}

		$filesModule = 'templates/'.TEMPLATE.'/css/modules/'.GET_MODULE.'.css';
		if (is_file($filesModule)) {
			$files[] = $filesModule;
		}

		foreach ($files as $k => $v) {
			$return .= '<link href="'.$v.'" rel="stylesheet" type="text/css" media="all">'."\n";
		}

		return $return;
	}

	public function getJs ()
	{
		$files      = array();
		$return     = '';
		$fileModule = ROOT.'assets/js/modules/'.GET_MODULE.'.js';

	    if (ACTIVE_JQUERY == 1) {
	        $files[] = 'assets/js/jquery-2.1.1.min.js';
	    }

		$files[]    = 'assets/js/bel-cms.js';

		if (is_file($fileModule)) {
			$files[] = 'assets/js/modules/'.GET_MODULE.'.js';
		}

		$filesModule = 'templates/'.TEMPLATE.'/js/modules/'.GET_MODULE.'.js';
		if (is_file($filesModule)) {
			$files[] = $filesModule;
		}

		foreach ($files as $k => $v) {
			$return .= '<script src="'.$v.'" type="text/javascript"></script>'."\n";
		}

		return $return;
	}

	/**
	* Permet de passer une ou plusieurs variable à la vue
	* @param $key nom de la variable OU tableau de variables
	* @param $value Valeur de la variable
	**/
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

}
