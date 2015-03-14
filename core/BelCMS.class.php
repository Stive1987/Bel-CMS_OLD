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

final class BelCMS extends Dispatcher
{

	function __construct()
	{
		#########################################
		# initialize __construct
		#########################################
		parent::__construct();
		#########################################
		# initializes cache time
		#########################################
		ob_start();
		parent::__construct();
		#########################################
		# initializes config
		#########################################
		new Config;
		#########################################
		# initializes user (auto-login)
		#########################################
		new User;
		#########################################
		# initializes list widgets
		#########################################
		new GetWidgetsList;
		#########################################
		# initializes template or managements
		#########################################
		if (GET_ADMIN) {
			new Managements;
		} else {
			new Template;
		}
		#########################################
		# Output buffer
		#########################################
		ob_end_flush();
	}
}
