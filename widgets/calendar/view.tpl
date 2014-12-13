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
$dates = $this->date;
?>
<div id="bel_cms_calendar">

	<div id="bel_cms_calendar_months_year"><?php echo $this->months[date('n')-1].' ' . $this->year;; ?></div>

	<?php
	foreach (current($dates) as $m => $days):
		$currentMonth = (date('n') == $m) ? 'current' : '';
		if ((date('n') == $m)):
		?>
		<div class="bel_cms_calendar_month <?php echo $currentMonth; ?>" id="bel_cms_calendar_month_<?php echo $m; ?>">
			<table>
				<thead>
					<tr>
						<?php
						foreach ($this->days as $d):
							?>
							<th><?php echo substr($d, 0,1) ?></th>
							<?php
						endforeach;
						?>
					</tr>
				</thead>
				<tbody>
					<tr>
					<?php
					$end = end($days);
					foreach ($days as $d => $w):
						$currentDay = (date('j') == $d) ? 'class="current"' : '';
						if ($d === 1):
						?>
							<td class="none" colspan="<?php echo $w-1; ?>"></td>
						<?php
						endif;
						?>
							<td <?php echo $currentDay; ?>><?php echo $d; ?></td>
						<?php
						if ($w == 7):
						?>
							</tr><tr>
						<?php
						endif;
					endforeach;
					if ($end != 7):
						?>
						<td colspan="<?php echo 7-$end; ?>"></td>
						<?php
					endif;
					?>
					</tr>
				</tbody>
			</table>
		</div>
		<?php
		endif;
	endforeach;
	?>
</div>
