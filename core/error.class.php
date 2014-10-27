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

function managementMistakes($type, $msg, $file, $l)
{
	switch ($type)
	{
		case E_ERROR:
		case E_PARSE:
		case E_CORE_ERROR:
		case E_CORE_WARNING:
		case E_COMPILE_ERROR:
		case E_COMPILE_WARNING:
		case E_USER_ERROR:
			$type_erreur = "Erreur fatale";
			$type_color  = "info-error";
			break;

		case E_WARNING:
		case E_USER_WARNING:
			$type_erreur = "Avertissement";
			$type_color  = "info-warning";
			break;

		case E_NOTICE:
		case E_USER_NOTICE:
			$type_erreur = "Remarque";
			$type_color  = "info-info";
			break;

		case E_STRICT:
			$type_erreur = "Syntaxe Obsolète";
			$type_color  = "info-warning";
			break;

		default:
			$type_erreur = "Erreur inconnue";
			$type_color  = "info-error";
	}

	$erreur = date("d.m.Y H:i:s") . ' - <b>' . $msg . '</b> ligne ' . $l . ' (' . $file . ')';

	// Affichage de l'erreur

	echo '	<div class="bel_cms_info_box '.$type_color.' clearfix">
				<span></span>
				<div class="bel_cms_info_box-wrap">
					<h4>'.$type_erreur.'</h4>
					<p>'.$erreur.'</p>
				</div>
			</div>';
}

function theManagementExceptions($exception)  
{
	managementMistakes (E_USER_ERROR, $exception->getMessage(), $exception->getFile(), $exception->getLine());  
}

function managementOfFatalErrors()
{
	if (is_array($e = error_get_last()))
	{
		$type    = isset($e['type']) ? $e['type'] : 0;
		$message = isset($e['message']) ? $e['message'] : '';
		$fichier = isset($e['file']) ? $e['file'] : '';
		$ligne   = isset($e['line']) ? $e['line'] : '';

		if ($type > 0) managementMistakes($type, $message, $fichier, $ligne);
	}
}

error_reporting(0);
set_error_handler('managementMistakes');
set_exception_handler("theManagementExceptions");
register_shutdown_function('managementOfFatalErrors');

class Error
{
	protected static function swtich ($type = FALSE)
	{
		switch ($type)
		{
			case ERROR:
				$type_error = "Erreur";
				$type_color = "info-error";
			break;
				
			case WARNING:
				$type_error = "Avertissement";
				$type_color = "info-warning";
			break;
				
			case INFO:
				$type_error = "Information";
				$type_color = "info-info";
			break;
				
			case SUCCESS:
				$type_error = "Succès";
				$type_color = "info-succes";
			break;
				
			default:
				$type_error = "Erreur inconnue";
				$type_color = "info-error";
		}
		return array('type_error' => $type_error, 'type_color' => $type_color);
	}

	public static function render ($text, $type = FALSE)
	{
		$switch = self::swtich($type);
		$return = 	'	<div class="bel_cms_info_box '.$switch['type_color'].' clearfix">
							<span></span>
							<div class="bel_cms_info_box-wrap">
								<h4>'.$switch['type_error'].'</h4>
								<p>'.$text.'</p>
							</div>
						</div>';
		return $return;
	}
}
?>