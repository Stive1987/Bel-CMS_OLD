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
class ControllerModuleNews extends News
{

	public function __construct($name)
	{
		self::$name();
	}

	function index()
	{
		$this -> data = News::getNews(false, NB_NEWS);
	}

	function readmore()
	{
		$data = remove_accent(GET_ID);
		$this -> data = News::getNews($data);
	}
}
?>
