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

Common::constant(array(
	#########################################
	# Tables
	#########################################
	'TABLE_COMMENTS'        => DB_PREFIX.'comments',
	'TABLE_CONFIG'          => DB_PREFIX.'config',
	'TABLE_FORUM'           => DB_PREFIX.'forum',
	'TABLE_FORUM_CAT'       => DB_PREFIX.'forum_cat',
	'TABLE_FORUM_POST'      => DB_PREFIX.'forum_post',
	'TABLE_FORUM_THREADS'   => DB_PREFIX.'forum_threads',
	'TABLE_GROUPS'          => DB_PREFIX.'groups',
	'TABLE_ACTION'          => DB_PREFIX.'infos_action',
	'TABLE_LIST_CONNEXIONS' => DB_PREFIX.'list_connections',
	'TABLE_MAIL_BLACKLIST'  => DB_PREFIX.'mails_blacklist',
	'TABLE_MODULES'         => DB_PREFIX.'modules',
	'TABLE_NEWS'            => DB_PREFIX.'news',
	'TABLE_PAGES'           => DB_PREFIX.'pages',
	'TABLE_USERS'           => DB_PREFIX.'users',
	'TABLE_USERS_PROFILS'   => DB_PREFIX.'users_profils',
	'TABLE_USERS_SOCIAL'    => DB_PREFIX.'users_social',
	'TABLE_WIDGETS'         => DB_PREFIX.'widgets',
	'TABLE_DOWNLOADS'       => DB_PREFIX.'downloads',
));
?>
