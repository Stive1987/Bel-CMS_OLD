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
class SqlConnection
{
	#########################################
	# Variable declaration
	#########################################
	private static  $instance;
	protected       $connectDb,
					$config = 'local';
	#########################################
	# Start Class
	#########################################
	public function __construct()
	{
		$pdo_options = array();
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$pdo_options[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES utf8';

		try {
            $this->connectDb = new PDO('mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_PREFIX.DB_NAME, DB_USER, DB_PASSWORD, $pdo_options);
		}
		catch (PDOException $e) {
			die('Échec lors de la connexion : ' . $e->getMessage());
		}
	}
	#########################################
	# Get instance
	#########################################
	public static function getInstance()
	{
		if (!self::$instance)
		{
			self::$instance = new BDD();
		}
		return self::$instance;
	}
	#########################################
	# Create where for BDD
	#########################################
	protected function createWhere($wheres)
	{
		$sql = " WHERE 1 ";

		$count = count($wheres);

		if ($count == 1) {
			$wheres = current($wheres);
		}

		if (!isset($wheres['name']) AND !isset($wheres['value'])) {
			foreach ($wheres as $k => $v)
			{
				$prevKey = $k - 1;
				if ($prevKey == '-1') {
					$condition = 'AND ';
				} else {
					$condition = ($v['name'] == $wheres[$prevKey]['name']) ? 'OR ' : 'AND ';
				}

				$operateur = (isset($v['op']) AND !empty($v['op'])) ? $v['op'] : ' = ';
				$value = "'".$v['value']."'";
				$sql .= $condition.$v['name'] . $operateur . $value;
				if ($count == $k) {
					break;
				}
			}
		} else {
			$condition = 'AND ';
			$operateur = (isset($wheres['op']) AND !empty($wheres['op'])) ? $wheres['op'] : ' = ';
			$value = "'".$wheres['value']."'";
			$sql .= $condition.$wheres['name'] . $operateur . $value;
		}
		return $sql;
	}
}
class BDD extends SqlConnection
{
	/*
	 * Select SQL multiple
	 * @data : tableau pour construire une requête
	 * @data['table']  = table à sélectionnée
	 * @data['fields'] = champs à sélectionner, tous les champs si vide
	 * @data['where']  = array('name', 'value') multiple possible
	 * @data['limit']  = int
	 */
	public function selectMultiple (array $data, $pdoType = true)
	{
		$return = null;
		$conx = $this->connectDb;

		if (!isset($data['table']) && !empty($data['table'])) {
			die('Error table');
		}

		$table = $data['table'];

		$fields = (!isset($data['fields'])) ? '*' : $data['fields'];
		$fields = (is_array($fields)) ? implode(',', $fields) : $fields;
		$limit  = (!isset($data['limit'])) ? '' : ' LIMIT '.$data['limit'];
		$order  = (!isset($data['order'])) ? '' : ' ORDER BY '.$data['order'];
		$where  = (isset($data['where']) ? $this->createWhere($data['where']) : '');

		$requete = $conx->prepare('
			SELECT '.$fields.'
			FROM '.$table.$where.$order.$limit.' '
		);

		if ($requete->execute()) {
			if ($pdoType) {
				$requete->setFetchMode(PDO::FETCH_OBJ);
			} else {
				$requete->setFetchMode(PDO::FETCH_ASSOC);
			}
			$return = $requete->fetchAll();
		} else {
			throw new Exception('Impossible d\'exécuter la requête :', $conx->errorInfo());
		}

		return $return;
	}
	/*
	 * Select SQL unique
	 * @data : tableau pour construire une requête
	 * @data['table']  = table à sélectionnée
	 * @data['fields'] = champs à sélectionner, tous les champs si vide
	 * @data['where']  = array('name', 'value') multiple possible
	 */
	public function select (array $data, $pdoType = true)
	{
		$return = null;
		$conx = $this->connectDb;

		if (!isset($data['table']) && !empty($data['table'])) {
			die('Error table');
		}

		$table = $data['table'];

		$fields = (!isset($data['fields'])) ? '*' : $data['fields'];
		$fields = (is_array($fields)) ? implode(',', $fields) : $fields;
		$limit  = ' LIMIT 1';
		$order  = (!isset($data['order'])) ? '' : ' ORDER BY '.$data['order'];
		$where  = (isset($data['where']) ? $this->createWhere($data['where']) : '');

		$requete = $conx->prepare('
			SELECT '.$fields.'
			FROM '.$table.$where.$order.$limit.' '
		);

		if ($requete->execute()) {
			if ($pdoType) {
				$requete->setFetchMode(PDO::FETCH_OBJ);
			} else {
				$requete->setFetchMode(PDO::FETCH_ASSOC);
			}
			$return = $requete->fetch();
		} else {
			throw new Exception('Impossible d\'exécuter la requête :', $conx->errorInfo());
		}

		return $return;
	}
	#########################################
	# Returns the number of line
	#########################################
	public function count($data = array())
	{
		$conx = $this->connectDb;

		$where = (isset($data['where']) ? $this->createWhere($data['where']) : '');

		if (!isset($data['table']) && !empty($data['table'])) {
			die('Error table');
		}

		$fields = (!isset($data['fields'])) ? 'id' : $data['fields'];

		$dbcCount = $conx->prepare('SELECT count(?) FROM '.$data['table'].$where);
		$dbcCount->execute(array($fields)) or die(debug($dbcCount->errorInfo()));

		$return = $dbcCount->fetchColumn();

		return $return;
	}
	#########################################
	# account on multiple table
	#########################################
	public function countMultiple($data = array())
	{
		$conx = $this->connectDb;

		if (empty($data)) {
			return false;
		} else {
			$tmpCount = array();
			foreach ($data as $k => $v) {
				$tmpCount[] = '(SELECT COUNT(id) FROM '.$v.') as count_'.$v.'';
			}
			$tmpCount = implode(', ', $tmpCount);
			try
			{
				$sql = 'SELECT '.$tmpCount.' ';
				$select = $conx->prepare($sql);
				$select->execute();
				$returnData = $select->fetch(PDO::FETCH_ASSOC);
			}
			catch (Exception $e)
			{
				die($e->getMessage());
			}
			return $returnData;
		}
	}
	#########################################
	# Insert data into database
	#########################################
	public function insert($data = array())
	{
		if (!isset($data['table']) && !empty($data['table'])) {
			die('Error table');
		}

		$table  = $data['table'];

		$keys = array_keys($data['data']);
		$tmpValue = implode(', ', array_map(array($this, 'secureField'), $keys));
		$tmpInto = implode(', ', $keys);

		$insert = $this->connectDb->prepare('INSERT INTO '.$table.' ('.$tmpInto.') VALUES ('.$tmpValue.')');

		$insert->execute($data['data']) or die(debug($insert->errorInfo()));

		return true;
	}
	#########################################
	# Update an online database
	#########################################
	public function update($data = array())
	{
		$conx = $this->connectDb;

		if (!isset($data['table']) && !empty($data['table'])) {
			die('Error table');
		}

		$table    = $data['table'];
		$where    = (isset($data['where']) ? $this->createWhere($data['where']) : '');
		$keys     = array_keys($data['data']);
		$tmpValue = array();
		foreach ($keys as $key)
		{
			$tmpValue[] = $key . ' = ' . $this->secureField($key);
		}

		try
		{
			$sql = 'UPDATE '.$table.' SET '.implode(', ', $tmpValue).$where;
			$dbiInsert = $conx->prepare($sql);
			$dbiInsert->execute($data['data']) or die(debug($dbiInsert -> errorInfo()));
		}
		catch (Exception $e)
		{
			die($e->getMessage());
		}
		return true;

	}
	#########################################
	# Security
	#########################################
	private function secureField($value)
	{
		return ':'.$value;
	}
}
