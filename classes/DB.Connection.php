<?php
require_once("Exceptions.php");
//require_once($_SERVER['DOCUMENT_ROOT'].'../config.php');
define('__ROOT__', dirname(dirname(__FILE__))); 
require_once(__ROOT__.'/config.php');
define("DB_TYPE", "mysql");

// php version in uniserver: 5.4.19
// php version on syminet: 5.3.3-7+squeeze17

class DBConn
{
	
	private $dbcnx;
	private $isOpen = false;
	
	public function __construct()
	{
		//$this->$isOpen = false;
	}
	
	function __destruct()
	{
		//$this->dbcnx = null;
        $this->close();
    }

	
	private function open()
	{
		try 
		{
			$connString = DB_TYPE.":host=".DB_HOST.";dbname=".DB_NAME;
			$this->dbcnx = new PDO($connString, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$this->dbcnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//echo var_dump($this->dbcnx);
			$this->isOpen = true;
		} catch (PDOException $e)
		{
			echo 'ERROR: ' . $e->getMessage();
		}

	}
	
	public function close()
	{
		$this->dbcnx = null;
	}

	public function selectOneVal($sql, $params=null, $close=false)
	{
		$result = false;
		try 
		{
			if  ($this->dbcnx == null)
			{
				$this->open();
			}
			//echo var_dump($this->dbcnx);
			//echo ($this->dbcnx == null);
			$statement = $this->dbcnx->prepare($sql);
			$statement->execute($params);
			$result = $statement->fetchColumn();
			if ($close)
			{
				$this->close();
			}
		} catch (PDOException $e)
		{
			echo 'ERROR: ' . $e->getMessage();
		}
		return $result;
	}

	// get for recursive loop access
	public function selectAll($sql, $params=null, $close=false)
	{
		$result = false;
		try 
		{
			if  ($this->dbcnx == null)
			{
				$this->open();
			}
			//echo var_dump($this->dbcnx);
			//echo ($this->dbcnx == null);
			$statement = $this->dbcnx->prepare($sql);
			$statement->execute($params);
			$result = $statement->fetchAll();
			if ($close)
			{
				$this->close();
			}
		} catch (PDOException $e)
		{
			echo 'ERROR: ' . $e->getMessage();
		}
		return $result;
	}

	// get one row
	public function selectOneRow($sql, $params=null, $close=false)
	{
		$result = false;
		try 
		{
			if  ($this->dbcnx == null)
			{
				$this->open();
			}
			$statement = $this->dbcnx->prepare($sql);
			$statement->execute($params);
			$result = $statement->fetch();
			if ($close)
			{
				$this->close();
			}
		} catch (PDOException $e)
		{
			echo 'ERROR: ' . $e->getMessage();
		}
		return $result;
	}
	
	public function transactionBegin()
	{
		if  ($this->dbcnx == null)
		{
			$this->open();
		}
		$this->dbcnx->beginTransaction();
	}

	public function transactionCommit()
	{
		$this->dbcnx->commit();
		$this->close();
	}

	public function transactionRollback()
	{
		$this->dbcnx->rollBack();
		$this->close();
	}

	public function executeInTran($sql, $params, $returnLastInsertID = false)
	{
		if  ($this->dbcnx == null)
		{
			$this->open();
		}
		$statement = $this->dbcnx->prepare($sql);
		$statement->execute($params);
		if ($returnLastInsertID)
			return $this->dbcnx->lastInsertId();
	}
	
	public function execute($sql, $params, $close=false)
	{
		if  ($this->dbcnx == null)
		{
			$this->open();
		}
		try
		{
			$statement = $this->dbcnx->prepare($sql);
			$statement->execute($params);
			$affected = $statement->rowCount();

			if ($close)
			{
				$this->close();
			}

			return $affected;
		} catch (PDOException $e)
		{
			echo 'ERROR: ' . $e->getMessage();
		}
	}

	public function execPlain($sql, $close=false)
	{
		if  ($this->dbcnx == null)
			$this->open();

		$this->dbcnx->exec($sql);

		if ($close)
			$this->close();
	}

	/*public function lastID($close = true)
	{
		if (!$this->isOpen)
		{
			$this->open();
		}
		$sql = "select LAST_INSERT_ID()";
		$result = $this->select($sql, false);
		if ($close)
		{
			$this->close();
		}
		$row = mysql_fetch_array($result);
		return $row[0];
	}*/
}
?>