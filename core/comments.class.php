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
	#####################################
	# Variable declaration
	#####################################
	public $content;
	#####################################
	# Start Class
	#####################################
	public function __construct()
	{
		ob_start();
		self::getCommentsModel();
		self::getCommentsController();
		self::getCommentsView();
		$this->content = ob_get_contents();
		ob_end_clean();
	}
	#####################################
	# get file model for module comments
	#####################################
	private function getCommentsModel ()
	{
		$file = ROOT.'model/comments/model.class.php';
		if (file_exists($file)) {
			require $file;
		} else {
			throw new Exception('No file model.class.php present in directory model/comments/');
		}
	}
	#####################################
	# get file view for module comments
	#####################################
	private function getCommentsView ()
	{
		$file = ROOT.'view/comments/modules.tpl.php';
		if (file_exists($file)) {
			require $file;
		} else {
			throw new Exception('No file modules.tpl.php present in directory model/view/');
		}
	}
	#####################################
	# get file controller for module comments
	#####################################
	private function getCommentsController ()
	{
		$file = ROOT.'controller/comments/controller.class.php';
		if (file_exists($file)) {
			require $file;
		} else {
			throw new Exception('No file index.tpl.php present in directory model/controller/');
		}
	}
}
