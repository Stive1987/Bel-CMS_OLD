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

class Define
{
	public function constant ($data = false, $value = false)
	{
        if ($data) {
            if (is_array($data)) {
                foreach ($data as $constant => $tableName) {
                    if (!defined($constant)) {
                        define($constant, $tableName);
                    }
                } 
            } else {
                if ($value || $data) {
                	if (!defined($constant)) {
                    	define($data, $value);
                    }
                }
            }
        }
	}
}
$define = new Define();