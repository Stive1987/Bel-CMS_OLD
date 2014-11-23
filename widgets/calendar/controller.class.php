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

class ControllerWidgetCalendar extends Calendar
{

	function __construct()
	{
		$this->title = 'Calendrier';
		$this->year  = date('Y');
		$this->date  = $this->GetDate($this->year);
	}
}
