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
if (!defined('CHECK_INDEX')) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Direct access forbidden');
    exit("<!DOCTYPE html>\r\n<html><head>\r\n<title>403 Direct access forbidden</title>\r\n</head><body>\r\n<h1>Direct access forbidden</h1>\r\n<p>The requested URL " . $_SERVER['SCRIPT_NAME'] . " is prohibited.</p>\r\n</body></html>");
}
$data = $this->data;
$data = current($data);
?>
<article class="bel_cms_article">
	<h1><?= $this->data['title']; ?></h1>
	<ul class="bel_cms_article_tags">
		<li>
			<i class="fa fa-calendar-o"></i>
			<span><?= $this->data['date_create']; ?></span>
		</li>
		<li>
			<i class="fa fa-user"></i>
			<span><?= $this->data['author']; ?></span>
		</li>
	</ul>
	<?php if (!empty($this->data['img'])): ?>
	<div class="bel_cms_article_img"><img src="<?= $this->data['img']; ?>"></div>
	<?php endif; ?>
	<div class="bel_cms_article_short_text">
		<?= $this->data['short_text']; ?>
	</div>
	<div class="bel_cms_article_long_text">
		<?= $this->data['long_text']; ?>
	</div>
</article>
