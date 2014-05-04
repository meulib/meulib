<?php

class UIMyException extends Exception
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

class NoUserException extends MyException
{
	public function errorMessage()
	{
		return "No user logged in " . $this->getMessage();
	}
}

?>