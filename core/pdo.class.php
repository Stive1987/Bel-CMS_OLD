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
require_once ROOT.'config/config.php';

$define -> constant ($array);

class GETBDD
{
    private static $instance;
    private static $conf = 'local';
    protected $bdd;

    public function __construct()
    {
        $pdo_options = array();
        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        $pdo_options[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES utf8';

        try {
            $this -> bdd = new PDO('mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_PREFIX.DB_NAME, DB_USER, DB_PASSWORD, $pdo_options);
        }
        catch (PDOException $e) {
            die('Échec lors de la connexion : ' . $e -> getMessage());
        }
    }

    public static function getInstance()
    {
        // Générer l'instance
        if (!self::$instance)
        {
            self::$instance = new BDD();
        }

        return self::$instance;
    }

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

class BDD extends GETBDD
{
	public function read($data = array())
	{
		$GLOBALS['count_queries']++;
		$db = $this -> bdd;

		$fields = (!isset($data['fields'])) ? '*' : $data['fields'];

		if (is_array($fields))
			$fields = implode(',', $fields);

		$order  = (!isset($data['order']))  ? '' : $data['order'];
		$limit  = (!isset($data['limit']))  ? '' : ' LIMIT '.$data['limit'];
		if (!isset($data['table']))
		{
			die('Il manque le nom de la table');
		}

		$table  = $data['table'];
		$where = (isset($data['where']) ? $this -> createWhere($data['where']) : '');

		try
		{
			$sql = 'SELECT '.$fields.' FROM '.$table.$where.$order.$limit.' ';
			$select = $db -> prepare($sql);
			$select -> execute();
			$select -> setFetchMode(PDO::FETCH_ASSOC);

			$returnData = $select -> fetchAll();
		}
		catch (Exception $e)
		{
			die($e->getMessage());
		}

		return $returnData;
	}

	public function insert($data = array())
	{
		$GLOBALS['count_queries']++;
		if (!isset($data['table']))
		{
			die('Il manque le nom de la table');
		}
		$table  = $data['table'];

		$keys = array_keys($data['data']);
		$tmpValue = implode(', ', array_map(array($this, 'secureField'), $keys));
		$tmpInto = implode(', ', $keys);

		$insert = $this->bdd -> prepare('INSERT INTO '.$table.' ('.$tmpInto.') VALUES ('.$tmpValue.')');

		$insert -> execute($data['data']) or die(debug($insert -> errorInfo()));

		return true;
	}

	public function count($data = array())
	{
		$GLOBALS['count_queries']++;
		$db = $this->bdd;

		$where = (isset($data['where']) ? $this -> createWhere($data['where']) : '');

		if (!isset($data['table']))
		{
			die('Il manque le nom de la table');
		}

		$fields = (!isset($data['fields'])) ? 'id' : $data['fields'];

		$dbcCount = $db -> prepare('SELECT count(?) FROM '.$data['table'].$where);
		$dbcCount -> execute(array($fields)) or die(debug($dbcCount -> errorInfo()));

		$returnData = $dbcCount -> fetchColumn();

		return $returnData;
	}

	public function update($data = array())
	{
		$GLOBALS['count_queries']++;
		if (is_array($data) === false){
			$this->bdd->execute($data);
			return;
		}

		$db = $this->bdd;

		if (!isset($data['table']))
		{
			die('Il manque le nom de la table');
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
			$dbiInsert = $db -> prepare($sql);
			$dbiInsert -> execute($data['data']) or die(debug($dbiInsert -> errorInfo()));
		}
		catch (Exception $e)
		{
			die($e->getMessage());
		}
		return true;

	}

	public function delete($data = array())
	{
		$GLOBALS['count_queries']++;
		$db = $this->bdd;

		if (!isset($data['table']))
		{
			die('Il manque le nom de la table');
		}

		$table  = $data['table'];
		$where = (isset($data['where']) ? $this->createWhere($data['where']) : '');

		try
		{
			$sql = 'DELETE FROM '.$table.$where.' ';
			$del = $db -> prepare($sql);
			$del -> execute();
		}
		catch (Exception $e)
		{
			die($e->getMessage());
		}

		return true;
	}

	public function countAll($data = array())
	{
		$GLOBALS['count_queries']++;
		$db = $this->bdd;

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
				$select = $db -> prepare($sql);
				$select -> execute();
				$returnData = $select -> fetch(PDO::FETCH_ASSOC);
			}
			catch (Exception $e)
			{
				die($e->getMessage());
			}
			return $returnData;
		}
	}

	public function readRand($data = array())
	{
		$GLOBALS['count_queries']++;
		$db = $this -> bdd;

		$fields = (!isset($data['fields'])) ? '*' : $data['fields'];

		if (is_array($fields))
			$fields = implode(',', $fields);

		$order  = ' ORDER BY RAND()';
		$limit  = (!isset($data['limit']))  ? '' : ' LIMIT '.$data['limit'];
		if (!isset($data['table']))
		{
			die('Il manque le nom de la table');
		}

		$table  = $data['table'];

		try
		{
			$sql = 'SELECT '.$fields.' FROM '.$table.$order.$limit.' ';
			$select = $db -> prepare($sql);
			$select -> execute();
			$select -> setFetchMode(PDO::FETCH_ASSOC);

			$returnData = $select -> fetchAll();
		}
		catch (Exception $e)
		{
			die($e->getMessage());
		}

		return $returnData;
	}

	private function secureField($value)
	{
		return ':'.$value;
	}
}