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

final class homePage
{
	function __construct()
	{
		$file = TEMPLATE.'homepage.tpl.php';
		if (is_file($file)) {
			require $file;
		} else {
			new modules();
		}
	}
}
