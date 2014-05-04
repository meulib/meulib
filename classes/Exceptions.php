<?php

class MyException extends Exception
{
	public function fullErrorMessage()
	{
		$errorMsg = "Error on line " . $this->getLine(). " in " . $this->getFile() . ": <b>" . $this->errorMessage() . "</b>";
		return $errorMsg;
	}
	
	public function errorMessage()
	{
		return $this->getMessage();
	}
}

class DBConnectionException extends MyException
{
	public function errorMessage()
	{
		return "Could not connect to DB. " . $this->getMessage();
	}
}

class DBQueryException extends MyException
{
	public function errorMessage()
	{
		return "Could not query the DB. " . $this->getMessage();
	}
}

class DBExecuteException extends MyException
{
	public function errorMessage()
	{
		return "Could not execute changes on the DB. " . $this->getMessage();
	}
}

class DBSafeStringException extends MyException
{
	public function errorMessage()
	{
		return "Could not convert string to safe string. " . $this->getMessage();
	}
}

?>