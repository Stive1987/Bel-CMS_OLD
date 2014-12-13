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
	#####################################
	# Variable declaration
	#####################################
	protected static $_table_infos_action = 'infos_action';
	private static $_table_blacklist = 'mails_blacklist';
	private static $_table = 'users';
	private static $def = array(
		'id',
		'name',
		'password',
		'mail',
		'hash_key',
		'date_registration',
		'last_visit',
		'groups',
		'main_groups',
		'valid',
		'ip',
		'token',
		'avatar'
	);
	private static $def_profils = array(
		'id',
		'hash_key',
		'gender',
		'public_mail',
		'websites',
		'list_ip',
		'list_avatar',
		'config',
		'info_text'
	);
	private static $def_connexions = array(
		'id',
		'hash_key',
		'date',
		'ip'
	);
	private static $def_comments = array(
		'id',
		'modules',
		'id_mods',
		'text'
	);
	#####################################
	# Get data table list_connections
	#####################################
	public static function getAllConnexions ($hash_key)
	{
		if ($hash_key and ctype_alnum($hash_key)) {
			$sql = array(
				'fields'     => self::$def_connexions,
				'table'      => TABLE_LIST_CONNEXIONS,
				'where'      => array(
					 'name'  => 'hash_key',
					 'value' => $hash_key
				),
				'limit'      => 10,
				'order'      => 'ORDER BY id'
			);
			$return = BDD::getInstance()->read($sql);
			if (!$return) {
				$return = array();
			}
		}
		return $return;
	}
	#####################################
	# Changer avatar
	#####################################
	public static function changerAvatar ($href)
	{
		$return = 'ERROR';
		if ($href) {
			BDD::getInstance() -> update(
				array(
					'table' => TABLE_USERS,
					'data'  => array(
						'avatar'      => $href
					),
					'where' => array(
						'name' => 'hash_key',
						'value'=> $_SESSION['hash_key']
					)
				)
			);
			$return = 'Avatar sauvegardées avec succès';
		}
		return $return;
	}
	#####################################
	# Delete avatar
	#####################################
	public static function deleteAvatar (array $data)
	{
		$return = null;

		if ($data['file'] == 'dir') {
			if (is_file($data['href'])) {
				unlink($data['href']);
				$return = 'L\'avatar a été supprimé avec succès';
			} else {
				$return = 'L\'avatar a pas été trouver';
			}
		} else if ($data['file'] == 'bdd') {
			$sql_avatar = array(
				'fields' => 'list_avatar',
				'table' => TABLE_USERS_PROFILS,
				'where'      => array(
					 'name'  => 'hash_key',
					 'value' => $_SESSION['hash_key']
				),
				'limit'      => 1
			);
			$return = current(BDD::getInstance()->read($sql_avatar));
			if ($return) {
				if (is_array($return['list_avatar'])) {
					$array_list_avatar = explode('|', $return['list_avatar']);
					foreach ($array_list_avatar as $k => $v) {
						if ($v == $data['href']) {
							unset($array_list_avatar[$k]);
						}
					}
					$list_avatar = implode('|', $array_list_avatar);
				} else {
					if ($data['href'] == $return['list_avatar']) {
						$array_list_avatar = '';
						$list_avatar = $array_list_avatar;
					} else {
						$list_avatar = $return['list_avatar'];
					}
				}
			}
			# update list_avatar sql table user profils
			BDD::getInstance() -> update(
				array(
					'table' => TABLE_USERS_PROFILS,
					'data'  => array(
						'list_avatar' => $list_avatar
					),
					'where' => array(
						'name' => 'hash_key',
						'value'=> $_SESSION['hash_key']
					)
				)
			);
			$return = 'L\'avatar a été supprimé avec succès';
		}
		return $return;
	}
	#####################################
	# Get data table comments
	#####################################
	public static function getAllComments ($hash_key)
	{
		if ($hash_key and ctype_alnum($hash_key)) {
			$sql = array(
				'fields'     => self::$def_comments,
				'table'      => TABLE_COMMENTS,
				'where'      => array(
					 'name'  => 'hash_key',
					 'value' => $hash_key
				),
				'limit'      => 10,
				'order'      => 'ORDER BY id'
			);
			$return = BDD::getInstance()->read($sql);
			if (!$return) {
				$return = array();
			}
		}
		return $return;
	}
	#####################################
	# Get data table users
	#####################################
	public static function getDataUser ($hash_key)
	{
		if ($hash_key and ctype_alnum($hash_key)) {
			$sql = array(
				'fields'     => self::$def,
				'table'      => TABLE_USERS,
				'where'      => array(
					 'name'  => 'hash_key',
					 'value' => $hash_key
				),
				'limit'      => 1
			);
			$return = current(BDD::getInstance() -> read($sql));
		} else {
			$return = false;
		}
		return $return;
	}
	#####################################
	# Get data table users_profils
	#####################################
	/* @return['config'] = gravatar
	*/
	public static function getDataProfil ($hash_key)
	{
		if ($hash_key and ctype_alnum($hash_key)) {
			$sql = array(
				'fields'     => self::$def_profils,
				'table'      => TABLE_USERS_PROFILS,
				'where'      => array(
					 'name'  => 'hash_key',
					 'value' => $hash_key
				),
				'limit'      => 1
			);
			$return = current(BDD::getInstance() -> read($sql));
			if (empty($return)) {
				$data = array('id' => '', 'hash_key' => $hash_key);
				BDD::getInstance() -> insert(
					array(
						'table'  => TABLE_USERS_PROFILS,
						'data'   => $data
					)
				);
				$sql = array(
					'fields'     => self::$def_profils,
					'table'      => TABLE_USERS_PROFILS,
					'where'      => array(
						 'name'  => 'hash_key',
						 'value' => $hash_key
					),
					'limit'      => 1
				);
				$return = current(BDD::getInstance() -> read($sql));
			}
		} else {
			$return = array();
		}
		return $return;
	}
	#####################################
	# Send config account
	#####################################
	public static function sendconfig ($data)
	{
		if ($_SESSION['hash_key'] and ctype_alnum($_SESSION['hash_key'])) {
			# Initializes a true error
			$i = true;
			# Check password
			$checkPassword 	=	array(
									'fields'     => 'password, hash_key',
									'table'      => self::$_table,
									'where'      => array(
										'name'  => 'hash_key',
										'value' => $_SESSION['hash_key']
									)
			);
			$results = current(BDD::getInstance() -> read($checkPassword));
			if (!password_verify($data['password'], $results['password'])) {
				$return = 'Le mot de passe ne correspondent pas avec celui du compte';
				$i = false;
			}
			# insert data sql
			if ($i) {
				if (!empty($data['password_new'])) {
					$data['password_new'] = password_hash($data['password_new'], PASSWORD_DEFAULT);
					# insert data sql table user
					BDD::getInstance() -> update(
						array(
							'table' => TABLE_USERS,
							'data'  => array(
								'password' => $data['password_new']
							),
							'where' => array(
								'name' => 'hash_key',
								'value'=> $_SESSION['hash_key']
							)
						)
					);
				}
				# insert data sql table users profils
				$config = $data['gravatar'].'|';
				BDD::getInstance() -> update(
					array(
						'table' => TABLE_USERS_PROFILS,
						'data'  => array(
							'config'      => $config
						),
						'where' => array(
							'name' => 'hash_key',
							'value'=> $_SESSION['hash_key']
						)
					)
				);
				$return = 'Vos informations ont été sauvegardées avec succès';
			}
		} else {
			$return = 'Error hash key';
		}
		return $return;
	}
	#####################################
	# Send info account
	#####################################
	public static function sendAccount ($data)
	{
		if ($_SESSION['hash_key'] and ctype_alnum($_SESSION['hash_key'])) {
			# Initializes a true error
			$i = true;
			# Check password
			$checkPassword 	=	array(
									'fields'     => 'password, hash_key',
									'table'      => self::$_table,
									'where'      => array(
										'name'  => 'hash_key',
										'value' => $_SESSION['hash_key']
									)
			);
			$results = current(BDD::getInstance() -> read($checkPassword));
			if (!password_verify($data['password'], $results['password'])) {
				$return = 'Le mot de passe ne correspondent pas avec celui du compte';
				$i = false;
			}
			# Check private mail
			if ($i) {
				if ($_SESSION['mail'] != $data['private_mail']) {
					if (filter_var($data['private_mail'], FILTER_VALIDATE_EMAIL)) {
						$checkMail =    array(
											'fields'     => 'mail',
											'table'      => TABLE_USERS,
											'where'      => array(
												 'name'  => 'mail',
												 'value' => $data['private_mail']
											)
						);
						$checkMail = BDD::getInstance() -> count($checkMail);
						if ($checkMail >= 1) {
							$return = 'le courriel '.$data['private_mail'].' est déjà réservé.';
							$i = false;
						}
					} else {
						$return = 'le courriel '.$data['private_mail'].' n\'est pas valide';
						$i = false;
					}
				}
			}
			# Check public mail
			if ($i) {
				if (!filter_var($data['public_mail'], FILTER_VALIDATE_EMAIL)) {
					$return = 'le courriel '.$data['public_mail'].' n\'est pas valide';
					$i = false;
				}
			}
			# Check url valid
			if ($i) {
				if (!filter_var($data['website'], FILTER_VALIDATE_URL)) {
					$return = $data['website'].' n\'est pas valide';
					$i = false;
				}
			}
			# insert data sql
			if ($i) {
				# insert data sql table user
				BDD::getInstance() -> update(
					array(
						'table' => TABLE_USERS,
						'data'  => array(
							'mail'     => $data['private_mail']
						),
						'where' => array(
							'name' => 'hash_key',
							'value'=> $_SESSION['hash_key']
						)
					)
				);
				# insert data sql table users profils
				BDD::getInstance() -> update(
					array(
						'table' => TABLE_USERS_PROFILS,
						'data'  => array(
							'gender'      => $data['sex'],
							'public_mail' => $data['public_mail'],
							'websites'    => $data['website'],
							'info_text'   => $data['info_text']
						),
						'where' => array(
							'name' => 'hash_key',
							'value'=> $_SESSION['hash_key']
						)
					)
				);
				$return = 'Vos informations ont été sauvegardées avec succès';
			}
		} else {
			$return = 'Error hash key';
		}
		return $return;
	}
	#####################################
	# Content mail
	#####################################
	public static function contentMail($title, $content)
	{
		$return = '	<html>
						<body>
							<div>
								<table align="center" style="background:#efefef;width:90%;border: 1px solid #6f6e70; margin:0 auto;" border="0" cellspacing="0" cellpadding="0">
									<tr style="background:#28a1db;color:#FFFFFF;text-align:center;font-size:16px;line-height: 30px;">
										<td><strong>'.$title.'</strong></td>
									</tr>
									<tr style="margin-top:5px;margin-bottom:5px;"><td>
										<table align="center" style="width:90%; line-height:24px; padding:5px; margin:15px auto;" border="0" cellspacing="0" cellpadding="0">
											<tr style="color:#28a1db"><td>'.$content.'</td></tr>
										</table>
									</td></tr>
									<tr style="margin-top:5px;margin-bottom:5px;"><td>
										<table align="center" style="width:85%; line-height:24px; padding:5px; border-radius:3px; margin:15px auto;border:1px solid #DADADA" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td style="text-align: center;"><strong>Ip:</strong></td>
												<td>'.get_ip().'</td>
												<td><strong>Heure:</strong></td>
												<td>'.date('Y-m-d H:i:s').'</td>
											</tr>
										</table>
									</td></tr>
									<tr style="background:#6f6e70;text-align:center;border-top:1px solid #ccc; font-size:16px;line-height: 30px">
										<td><a style="text-decoration: none; color:#FFFFFF; display:block" href="http://www.bel-cms.be/">BEL-CMS Mail</a></td>
									</tr>
								</table>
							</div>
						</body>
					</html> ';
		return $return;
	}
	#####################################
	# Generator password 8 default
	#####################################
	public static function generatePass ($height = 8){
		// initialiser la variable $return
		$return = '';
		// Définir tout les caractères possibles dans le mot de passe,
		$character = "#'/*-&@$%2346789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		// obtenir le nombre de caractères dans la chaîne précédente
		$max = strlen($character);
		if ($height > $max) {
			$height = $max;
		}
		// initialiser le compteur
		$i = 0;
		// ajouter un caractère aléatoire à $return jusqu'à ce que $height soit atteint
		while ($i < $height) {
			// prendre un caractère aléatoire
			$letter = substr($character, mt_rand(0, $max-1), 1);
			// vérifier si le caractère est déjà utilisé dans $mdp
			if (!stristr($return, $character)) {
				// Si non, ajouter le caractère à $return et augmenter le compteur
				$return .= $letter;
				$i++;
			}
		}
		// retourner le résultat final
		return $return;
	}
	#####################################
	# Check token and send mail
	#####################################
	public static function checkToken($data = false)
	{
		if ($data) {
			if (strpos($data['value'], '@')) {
				$type = 'mail';
			} else {
				$type = 'name';
			}

			$check =    array(
				'fields'     => self::$def,
				'table'      => self::$_table,
				'where'      => array(
					 'name'  => $type,
					 'value' => $data['value']
				),
				'limit'      => 1
			);
			$results = current(BDD::getInstance() -> read($check));
			if ($results && is_array($results) && sizeof($results)) {
				if (empty($results['token'])) {
					// Création du token
					$hashToken = md5(uniqid(rand(), true));
					$timeToken = time() + 60*60;
					$token = $hashToken.'|'.$timeToken;
					// Mise à jours des données en BDD
					BDD::getInstance() -> update(
						array(
							'table' => self::$_table,
							'data'  => array(
								'token' => $token,
							),
							'where' => array(
								'name' => $type,
								'value'=> $data['value']
							)
						)
					);
					// Contenue du courriel
					$contentMail = '';
					$contentMail .= '<p>Token : <strong>' . $hashToken . '</strong></p>';
					$contentMail .= '<p>Valable : 1h00</p>';
					$mail = array(
						'subject'  => 'Demande de nouveau mot de passe',
						'content'  => self::contentMail('Token', $contentMail),
						'sendMail' => $results['mail']
					);
					$returnMail = Common::sendMail($mail);
					if ($returnMail) {
						$dataAction = array(
							'name'        => '',
							'ip'          => get_ip(),
							'date_insert' => date('Y-m-d H:i:s'),
							'text'        => 'Une demande de regénération de mot de passe à été demander',
							'modules'     => 'User'
						);

						BDD::getInstance() -> insert(
							array(
								'table'  => self::$_table_infos_action,
								'data'   => $dataAction
							)
						);
						$return = 'Un mail avec un token a été génère et envoyé par courriel';
					} else {
						$return = 'Le mail n\'a pas pu être envoyé, veuillez-vous référer à l\'administrateur du site';
					}
				} else {
					$explode = explode('|', $results['token']);
					if ($explode[1] <= time()) {
						// Reset du token
						BDD::getInstance() -> update(
							array(
								'table' => self::$_table,
								'data'  => array(
									'token' => '',
								),
								'where' => array(
									'name' => $type,
									'value'=> $data['value']
								)
							)
						);
						self::checkToken($data['value']);
						$return = 'Ce token n\'est plus valide, un nouveau a été génère';
					} else {
						if (empty($data['token'])) {
							$return = 'Votre token est valide, veuillez l\'utiliser';
						} else if ($data['token'] != $explode[0]) {
							$dataAction = array(
								'name'        => '',
								'ip'          => get_ip(),
								'date_insert' => date('Y-m-d H:i:s'),
								'text'        => 'Le token de correspondais pas avec celui du compte',
								'modules'     => 'User'
							);

							BDD::getInstance() -> insert(
								array(
									'table'  => self::$_table_infos_action,
									'data'   => $dataAction
								)
							);
							$return = 'Ce token ne correspond pas avec celui du compte';
						} else {
							$generatePass = self::generatePass(8);
							$password = password_hash($generatePass, PASSWORD_DEFAULT);
							// Update du mot de passe & reset du token
							BDD::getInstance() -> update(
								array(
									'table' => self::$_table,
									'data'  => array(
										'password' => $password,
										'token'    => '',
									),
									'where' => array(
										'name' => $type,
										'value'=> $data['value']
									)
								)
							);

							$contentMail = '';
							$contentMail .= '<p>Votre mot de passe  : <strong>' . $generatePass . '</strong></p>';
							$mail = array(
								'subject'  => 'Demande de nouveau mot de passe',
								'content'  => self::contentMail('Mot de passe', $contentMail),
								'sendMail' => $results['mail']
							);
							$returnMail = Common::sendMail($mail);

							$return = 'Voici votre nouveau mot de passe : '. $generatePass;
						}
					}
				}
			} else {
				$return = 'Aucun Nom et/ou pseudo connu';
			}
		} else {
			$return = 'Nom et/ou pseudo vide';
		}
		return $return;
	}
	#####################################
	# Check all mail for black list
	#####################################
	protected static function getMailBlackList ($data)
	{
		// Ajout du blacklistage des mail jetables
		$results_black_list = BDD::getInstance() -> read(array('table'=> self::$_table_blacklist));

		$arrayBlackList = array();

		foreach ($results_black_list as $k => $v) {
			$arrayBlackList[$v['id']] = $v['name'];
		}

		$tmpMailSplit = explode('@', $data);
		$tmpNdd =  explode('.', $tmpMailSplit[1]);
		if (in_array($tmpNdd[0], $arrayBlackList)) {
			$return = true;
		} else {
			$return = false;
		}
		return $return;
	}
	#####################################
	# Send registration
	#####################################
	protected static function sendRegistration ($data)
	{
		if ($data) {
			$error = null;
			// Ajout du blacklistage des mail jetables
			$results_black_list = BDD::getInstance() -> read(array('table'=> self::$_table_blacklist));

			$arrayBlackList = array();

			foreach ($results_black_list as $k => $v) {
				$arrayBlackList[$v['id']] = $v['name'];
			}

			if (!empty($data['mail'])) {
				$tmpMailSplit = explode('@', $data['mail']);
				$tmpNdd =  explode('.', $tmpMailSplit[1]);
			}

			foreach ($data as $k => $v) {
				if (!array_search($k, self::$def)) {
					if ($k != 'name') {
						unset($data[$k]);
					}
				}
			}
			if (empty($data['name']) OR empty($data['mail']) OR empty($data['password'])) {
				$return = 'Les champs Nom & e-mail & Mot de passe doit être rempli'; ++$error;
			} elseif (in_array($tmpNdd[0], $arrayBlackList)) {
				$return = 'Les faux mails ne sont pas autorisés'; ++$error;
			} elseif (strlen($_POST['security']) != 3 AND strtolower($_POST['security']) == 'dix')  {
				$return = 'Veuillez écrire <strong>dix</strong> dans le champ'; ++$error;
			} elseif (strlen($data['name']) < 4) {
				$return = 'Pseudo trop court, minimum 4 caractères'; ++$error;
			} elseif (strlen($data['name']) > 32) {
				$return = 'Pseudo trop long, maximum 32 caractères'; ++$error;
			} elseif (strlen($data['password']) < 6) {
				$return = 'Mot de passe trop court, minimum 6 caractères'; ++$error;
			} else {
				$checkName =    array(
									'fields'     => 'name',
									'table'      => self::$_table,
									'where'      => array(
										 'name'  => 'name',
										 'value' => $data['name']
									)
				);
				$returnCheckName = BDD::getInstance() -> count($checkName);
				$checkMail =    array(
									'fields'     => 'mail',
									'table'      => self::$_table,
									'where'      => array(
										 'name'  => 'mail',
										 'value' => $data['mail']
									)
				);
				$checkMail = BDD::getInstance() -> count($checkMail);
				if ($returnCheckName >= 1) {
					$return = 'ce Nom / Pseudo est déjà réservé.';
				} elseif ($checkMail >= 1) {
					$return = 'ce courriel est déjà réservé.';
				} else {

					$data['password']          = password_hash($data['password'], PASSWORD_DEFAULT);
					$data['hash_key']          = md5(uniqid(rand(), true));
					$data['date_registration'] = date('Y-m-d H:i:s');
					$data['last_visit']        = date('Y-m-d H:i:s');
					$data['groups']            = (int) 3;
					$data['main_groups']       = (int) 3;
					$data['valid']             = (int) 1;
					$data['ip']                = get_ip();

					BDD::getInstance() -> insert(
						array(
							'table'  => self::$_table,
							'data' => $data
						)
					);

					Users::login($_POST['name'],$_POST['password']);

					$dataAction = array(
						'name' => $data['name'],
						'ip' => get_ip(),
						'date_insert' => date('Y-m-d H:i:s'),
						'text' => 'S\'est enregistrer',
						'modules' => 'User'
					);

					BDD::getInstance() -> insert(
						array(
							'table'  => self::$_table_infos_action,
							'data' => $dataAction
						)
					);

					$return = 'Enregistrement en cours...';
				}
			}
			return $return;
		}
	}
}
?>