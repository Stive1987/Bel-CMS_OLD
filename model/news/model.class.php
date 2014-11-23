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
	class News
	{
		private static $_table = 'news';
		private static $def    = array(
			'id',
			'title',
			'short_text',
			'long_text',
			'date_create',
			'tags',
			'author',
			'img',
			'count_news',
			'rewrite_name'
		);

		public static function getNews($where = false, $number = false)
		{
			$datas = array(
				'table'  => self::$_table,
				'fields' => self::$def,
				'order'  => ' ORDER BY ID DESC'
			);

			if ($number && $number > 0) {
				$datas['limit'] = (int) $number;
			}

			if ($where) {
				if (is_numeric($where)) {
					$datas['where'] = array(
						'name'  => 'id',
						'value' => (int) $where
					);
				} else {
					$datas['where'] = array(
						'name'  => 'rewrite_name',
						'value' => $where
					);
				}
			}

			$results = BDD::getInstance() -> read($datas);

			if ($results && is_array($results) && sizeof($results)) {
				foreach ($results as $k => $v) {
					$results[$k]['rewrite_name'] = remove_accent($results[$k]['rewrite_name']);
				}
				return $results;
			}
		}

	}
?>
