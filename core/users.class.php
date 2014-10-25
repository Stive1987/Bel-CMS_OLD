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
/**
* Class user
* @return infos de user
*/
class Users
{
	protected static $_table = 'users';
	protected static $info;
	protected static $def = array(
		'name',
		'password',
		'mail',
		'hash_key',
		'date_registration',
		'last_visit',
		'website',
		'groups',
		'valid',
		'last_ip'
	);

	public function __construct () {
		if (!session_id()) {
			session_start();
			self::autoLogin();
			self::updateLastVistst();
		}
	}
	// Update last visit
	public static function updateLastVistst() {
		if (isset($_SESSION['hash_key'])) {
			return BDD::getInstance() -> update(
				array(
					'table' => self::$_table,
					'data'  => array(
						'last_visit' => date('Y-m-d H:i:s'),
						'last_ip'    => get_ip()
					),
					'where' => array(
						'name' => 'hash_key',
						'value'=> $_SESSION['hash_key']
					)
				)
			);
		} else {
			$_SESSION['groups'] = 3;
		}
	}


	public static function login($name = null, $password = null)
	{
		// Verifie que $name & $password ne son pas vide
		if (!empty($name) AND !empty($password)) {
			// Connexion par mail ou name
			if (strpos($name, '@')) {
				$request = 'mail';
			} else {
				$request = 'name';
			}

			$datas = array(
				'table'  => self::$_table,
				'fields' => self::$def,
				'where'  => array(
					'name'  => $request,
					'value' => $name
				)
			);

			$results = BDD::getInstance() -> read($datas);

			$results = current($results);

			if ($results && is_array($results) && sizeof($results)) {
				if (password_verify($password, $results['password'])) {
					$setcookie = $results['name'].'###'.$results['hash_key'].'###'.date('Y-m-d H:i:s').'###'.$results['password'];
					setcookie('BEL-CMS-COOKIE', $setcookie, time()+60*60*24*30, '/');
					foreach (self::$def as $key) {
						$_SESSION[$key] = $results[$key];
					}
					unset($_SESSION['password']);
					$return = 'La connexion a été éffectuée avec succès';
				} else {
					$return = 'Mauvaise combinaison de Pseudonyme/mail et mot de passe';
				}
			} else {
				$return = 'Aucun utilisateur avec ce Pseudonyme/mail';
			}
		} else {
			$return = 'Le nom ou le mot de passe est obligatoire';
		}
		return $return;
	}

	public static function registration(array $datas) {
	   return 'L\'enregistrement a été effectué avec succès.';
	}

	public static function logout()
	{
		setcookie('BEL-CMS-COOKIE', NULL, -1, '/');
		session_destroy();
		return 'Votre session est vos cookie de ce site sont effacés';
	}

	public static function autoLogin()
	{
		// Si la session existe déjà, inutile d'aller plus loin
		if (!isset($_SESSION) OR empty($_SESSION)) {
			// Control si la variable $_COOKIE existe
			if (isset($_COOKIE['BEL-CMS-COOKIE']) AND !empty($_COOKIE['BEL-CMS-COOKIE'])  ) {
				// Passe en tableaux les valeurs du $_COOKIE
				$cookie = explode('###', $_COOKIE['BEL-CMS-COOKIE']);
				$name = $cookie[0]; $hash_key = $cookie[1]; $date = $cookie[2]; $hash = $cookie[3];
				if ($hash_key AND strlen($hash_key) == 32) {
					$datas = array(
						'table'  => self::$_table,
						'fields' => self::$def
					);

					$arrayRead['where'][] = array(
										'name'  => 'name',
										'value' => $name
					);

					$arrayRead['where'][] = array(
										'name'  => 'hash_key',
										'value' => $hash_key
					);

					$arrayRead['where'][] = array(
										'name'  => 'password',
										'value' => $hash
					);

					$results = BDD::getInstance() -> read($datas);

					$results = current($results);

					if ($results && is_array($results) && sizeof($results)) {
						foreach (self::$def as $key) {
							$_SESSION[$key] = $results[$key];
						}
						unset($_SESSION['password']);
					}

				} else {
					return false;
				}
			} else {
					return false;
			}
		}
	}

	public static function getInfosUser($id = null)
	{
		$datas = array();

		$datas = array(
			'table'  => self::$_table,
			'fields' => self::$def
		);

		if ($id) {
			if (is_array($id)) {
				$id = array_unique($id);
				foreach ($id as $k) {
					$datas['where'][] = array(
						'name'  => 'hash_key',
						'value' => $k
					);
				}
			} else {
				$datas['where'] = array(
					'name'  => 'hash_key',
					'value' => $id
				);
			}
		}

		$results = BDD::getInstance() -> read($datas);

		$return = array();

		if ($results && is_array($results) && sizeof($results))
		{
			foreach ($results as $k => $v)
			{
				if (!array_key_exists($v['hash_key'], $return)) {
					$return[$v['hash_key']] = $v;
					unset($return[$v['hash_key']]['hash_key']);
				}
			}
		}
		return $return;
	}

}
new Users;
?>