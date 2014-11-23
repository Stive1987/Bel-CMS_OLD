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

class Statistics
{

	function __construct()
	{
		$data = array(TABLE_USERS, TABLE_COMMENTS, TABLE_FORUM_POST, TABLE_NEWS);
		$results = BDD::getInstance() -> countAll($data);

		if ($results) {
			if (is_array($results)) {
				foreach ($results as $k => $v) {
					$this -> $k = $v;
				}
			}
		}
	}
}
