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
class ControllerModuleUser extends User
{

	public function __construct($name)
	{
		self::$name();
	}

	function index ()
	{
		if (!isset($_SESSION['hash_key'])) {
			Dispatcher::redirect('User/Login/ajax', 0);
		} else {
			$get_list_avatar = scan_file('uploads/users/'.$_SESSION['hash_key'].'/', array('gif', 'jpg', 'jpeg', 'png'), true);
			foreach (User::getDataProfil($_SESSION['hash_key']) as $k => $v) {
				if ($k == 'config') {
					$v = explode('|', $v);
					$this->gravatar = ($v[0] == 1) ? true : false;					
				}
				if ($k == 'list_avatar') {
					if (empty($v)) {
						$v = array();
					} else if (stristr($v, '|')) {
						$v = explode('|', $v);
					} else {
						$v = array($v);
					}
					$this->array_list_avatar['dir'] = array();
					foreach ($get_list_avatar as $v_get) {
						$this->array_list_avatar['dir'][] = $v_get;
					}
					$this->array_list_avatar['bdd'] = $v;
				}
				if ($k == 'gender') {
					if ($v == 0) {
						$v = MAN;
					} else {
						$v = WOMAN;
					}
				}
				$v = (empty($v)) ? UNKNOWN : $v;
				$this->$k = $v;
			}
			foreach (User::getDataUser($_SESSION['hash_key']) as $k => $v) {
				$k = ($k == 'mail') ? 'private_mail' : $k;
				if ($k == 'main_groups') {
					if ($v == 0) {
						$v = (int) 3;
					}
				}
				$v = ($k == 'main_groups') ? $GLOBALS['config']->groups[$v] : $v;
				$v = ($k == 'valid') ? $GLOBALS['config']->valid[$v] : $v;
				$v = ($k == 'avatar') ? (empty($v)) ? ROOT_ABS.'assets/img/default_avatar.jpg' : $v : $v;
				$v = (empty($v)) ? UNKNOWN : $v;
				$this->$k = $v;
			}
			$this->connexions = User::getAllConnexions($_SESSION['hash_key']);
			$this->comments = User::getAllComments($_SESSION['hash_key']);
		}
	}

	function login ()
	{
		if (isset($_SESSION['hash_key'])) {
			Dispatcher::redirect('User', 0);
		}
	}

	function logout ()
	{
		if (isset($_SESSION['hash_key'])) {
			$this -> logout = Users::logout();
		}
	}

	function lostpassword ()
	{
	}
	function registration ()
	{
		
	}

	function sendavatar ()
	{
		$dir             = ROOT.'uploads/users/'.$_SESSION['hash_key'].'/';

		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}
		$return          = false;
		$file            = basename($_FILES['avatar']['name']);
		$filesize        = $_FILES['avatar']['size'];
		$extensions      = array('.png', '.gif', '.jpg', '.jpeg');
		$extension       = strrchr($_FILES['avatar']['name'], '.');

		if (!in_array(strtolower($extension), $extensions)) {
			$type   = 'red';
			$return = 'Vous devez uploader un fichier de type png, gif, jpg, jpeg';
		}

		if (return_bytes($filesize) > max_upload_file()) {
			$type   = 'blue';
			$return = 'Le fichier est trop gros...';
		}

		if ($return === false) {
			$file = remove_accent($file);
			if (move_uploaded_file($_FILES['avatar']['tmp_name'], $dir . $file)) {
				$type   = 'green';
				$return = 'Upload effectué avec succès !';
			} else {
				$type = 'red';
				$return = 'Echec de l\'upload !';
			}
		}

		$this->type = $type;
		$this->text = $return;
	}

	function send ()
	{
		if (isset($_POST['type'])) {
			if ($_POST['type'] == 'login') {
				unset($_POST['type']);
				$return = array(
					'text'       => Users::login($_POST['name'], $_POST['password']),
					'linkReturn' => 'User'
				);
			} else if ($_POST['type'] == 'account') {
				unset($_POST['type']);
				$return = array(
					'text'       => User::sendAccount($_POST),
					'linkReturn' => 'User'
				);
			} else if ($_POST['type'] == 'lostPassword') {
				unset($_POST['type']);
				$return = array(
					'text'       => User::checkToken($_POST)
				);
			} else if ($_POST['type'] == 'registration') {
				unset($_POST['type']);
				$return = array(
					'text'       => User::sendRegistration($_POST),
					'linkReturn' => 'User'
				);
			} else if ($_POST['type'] == 'config') {
				unset($_POST['type']);
				$return = array(
					'text'       => User::sendconfig($_POST),
					'linkReturn' => 'User'
				);
			} else if ($_POST['type'] == 'avatar') {
				unset($_POST['type']);
				$return = array(
					'text'       => User::changerAvatar($_POST['href'])
				);
			} else if ($_POST['type'] == 'delavatar') {
				unset($_POST['type']);
				$return = array(
					'text'       => User::deleteAvatar($_POST)
				);
			} else {
				$return = array(
					'text'       => 'Error link',
					'linkReturn' => 'User'
				);
			}
		} else {
			Dispatcher::redirect('User', 0);
		}
		$this -> data = $return;
	}
}
?>
