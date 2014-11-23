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

class ControllerWidgetStats extends Statistics
{
	function __construct()
	{
		parent::__construct();
		$this -> cache         = true;
		$this -> title         = 'Statistique';
		$this -> count_queries = $GLOBALS['count_queries'];
		$this -> loading_page  = $GLOBALS['time_loading_page'];
	}
}
