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

final class Comments
{
	#########################################
	# Variable declaration
	#########################################
	public  $content;
	#########################################
	# Start Class
	#########################################
	public function __construct()
	{
		ob_start();
		ob_end_clean();
	}

}
