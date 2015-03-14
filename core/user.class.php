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

class User
{
	#########################################
	# Variable declaration
	#########################################
	private static $structureUser = array(
		'name',
		'password',
		'mail',
		'hash_key',
		'date_registration',
		'last_visit',
		'groups',
		'valid',
		'ip'
	);
	#########################################
	# Start Class
	#########################################
	public function __construct () {
		if (!session_id()) {
			session_start();
			self::autoLogin();
			//self::updateLastVistst();
		}
	}
	#########################################
	# login function
	#########################################
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
				'table'  => TABLE_USERS,
				'fields' => self::$structureUser,
				'where'  => array(
					'name'  => $request,
					'value' => $name
				)
			);

			$results = BDD::getInstance()->select($datas, false);

			if ($results && is_array($results) && sizeof($results)) {
				if (password_verify($password, $results['password'])) {
					$setcookie = $results['name'].'###'.$results['hash_key'].'###'.date('Y-m-d H:i:s').'###'.$results['password'];
					setcookie('BEL-CMS-COOKIE', $setcookie, time()+60*60*24*30, '/');
					foreach (self::$structureUser as $key) {
						$_SESSION[$key] = $results[$key];
					}
					unset($_SESSION['password']);

					$arrayInsertListConnexions = array(
						'id'       => '',
						'hash_key' => $results['hash_key'],
						'date'     => date('Y-m-d H:i:s'),
						'ip'       => Common::getIp()
					);
/*
					BDD::getInstance()->insert(
						array(
							'table'  => TABLE_LIST_CONNEXIONS,
							'data' => $arrayInsertListConnexions
						)
					);
					*/

					$return['msg']  = 'La connexion a été éffectuée avec succès';
					$return['type'] = 'green';
				} else {
					$return['msg']  = 'Mauvaise combinaison de Pseudonyme/mail et mot de passe';
					$return['type'] = 'red';
				}
			} else {
				$return['msg']  = 'Aucun utilisateur avec ce Pseudonyme/mail';
				$return['blue'] = 'green';
			}
		} else {
			$return['msg']  = 'Le nom ou le mot de passe est obligatoire';
			$return['type'] = 'red';
		}
		return $return;
	}
	#########################################
	# Logout
	#########################################
	public static function logout()
	{
		setcookie('BEL-CMS-COOKIE', NULL, -1, '/');
		session_destroy();
		$return['msg']  = 'Votre session est vos cookie de ce site sont effacés';
		$return['blue'] = 'green';
		return $return;
	}
	#########################################
	# Auto connection through cookie
	#########################################
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
						'table'  => TABLE_USERS,
						'fields' => self::$structureUser
					);

					$datas['where'][] = array(
										'name'  => 'name',
										'value' => $name
					);

					$datas['where'][] = array(
										'name'  => 'hash_key',
										'value' => $hash_key
					);

					$datas['where'][] = array(
										'name'  => 'password',
										'value' => $hash
					);

					$results = BDD::getInstance()->select($datas, false);

					if ($results && is_array($results) && sizeof($results)) {
						foreach (self::$structureUser as $key) {
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
}
