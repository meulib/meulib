<?php
require_once('DB.Connection.php');
require_once("Exceptions.php");
if (!defined('__ROOT__')) define('__ROOT__', dirname(dirname(__FILE__))); 
require_once(__ROOT__.'/config.php'); 

class CreateTables
{
	private $dbconn;

	public function __construct()
	{
		$this->dbconn = new DBConn();
	}
	
	function __destruct()
	{
		$this->dbconn = null;
    }

    private function createTable($tblName, $sql)
    {
    	try 
		{
			$this->dbconn->execPlain($sql);
			return "Created table ".$tblName;
		} catch(PDOException $e) 
		{ 
			return $e->getMessage(); 
		}
    }

    private function booksFlat()
    {
    	$tblName = TBL_PREFIX."books_flat";
    	$sql = "CREATE TABLE IF NOT EXISTS `".$tblName."` (
			  `ID` int(2) NOT NULL DEFAULT '0',
			  `Title` varchar(100) DEFAULT NULL,
			  `Author1ID` int(1) DEFAULT NULL,
			  `Author1` varchar(100) NOT NULL,
			  `Author2ID` int(1) DEFAULT NULL,
			  `Author2` varchar(100) NOT NULL,
			  `Language1ID` int(1) DEFAULT NULL,
			  `Language1` varchar(50) NOT NULL,
			  `Language2ID` int(1) DEFAULT NULL,
			  `Language2` varchar(50) NOT NULL,
			  `SubTitle` varchar(100) DEFAULT NULL,
			  PRIMARY KEY (`ID`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		return $this->createTable($tblName, $sql);		
	}

	private function authors()
	{
		$tblName = TBL_PREFIX."authors";
		$sql = "CREATE TABLE IF NOT EXISTS `".$tblName."` (
			  `ID` int(1) NOT NULL AUTO_INCREMENT,
			  `AuthorName` varchar(100) NOT NULL,
			  PRIMARY KEY (`ID`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
		return $this->createTable($tblName, $sql);		
	}

	private function bookcopies()
	{
		$tblName = TBL_PREFIX."bookcopies";
		$sql = "CREATE TABLE IF NOT EXISTS `".$tblName."` (
			  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
			  `BookID` int(2) NOT NULL,
			  `UserID` varchar(15) NOT NULL,
			  `Status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Not Checked, 1: Available, 2: Lent Out, 3: Overdue, 4: Defaulted, 5: Temporarily Withdrawn by User, 6: Private',
			  `Checked` bit(1) NOT NULL DEFAULT b'0',
			  PRIMARY KEY (`ID`),
			  UNIQUE KEY `COL 2` (`BookID`,`UserID`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
		return $this->createTable($tblName, $sql);		
	}

	private function books()
	{
		$tblName = TBL_PREFIX."books";
		$sql = "CREATE TABLE IF NOT EXISTS `".$tblName."` (
		  `ID` int(2) NOT NULL AUTO_INCREMENT,
		  `Title` varchar(100) DEFAULT NULL,
		  `Author1` varchar(1) DEFAULT NULL,
		  `Author2` varchar(1) DEFAULT NULL,
		  `Language1` int(1) DEFAULT NULL,
		  `Language2` varchar(1) DEFAULT NULL,
		  `SubTitle` varchar(100) DEFAULT NULL,
		  PRIMARY KEY (`ID`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
		return $this->createTable($tblName, $sql);		
	}

	private function bookCategory()
	{
		$tblName = TBL_PREFIX."book_category";
		$sql = "CREATE TABLE IF NOT EXISTS `".$tblName."` (
			  `ID` int(1) NOT NULL AUTO_INCREMENT,
			  `BookID` int(1) NOT NULL,
			  `CategoryID` int(1) NOT NULL,
			  PRIMARY KEY (`ID`),
			  KEY `CategoryID` (`CategoryID`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
		return $this->createTable($tblName, $sql);
	}

	private function categories()
	{
		$tblName = TBL_PREFIX."categories";
		$sql = "CREATE TABLE IF NOT EXISTS `".$tblName."` (
		  `ID` int(1) NOT NULL AUTO_INCREMENT,
		  `CategoryName` varchar(100) NOT NULL,
		  PRIMARY KEY (`ID`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
		return $this->createTable($tblName, $sql);
	}

	private function languages()
	{
		$tblName = TBL_PREFIX."languages";
		$sql = "CREATE TABLE IF NOT EXISTS `".$tblName."` (
		  `COL 1` int(1) DEFAULT NULL,
		  `COL 2` varchar(21) DEFAULT NULL
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		return $this->createTable($tblName, $sql);
	}

	private function messages2()
	{
		$tblName = TBL_PREFIX."messages2";
		$sql = "CREATE TABLE IF NOT EXISTS `".$tblName."` (
		  `ID` bigint(20) NOT NULL,
		  `UserID` varchar(15) NOT NULL,
		  `FromTo` tinyint(1) NOT NULL COMMENT '1 implies from, 0 implies to',
		  `OtherUserID` varchar(15) NOT NULL,
		  `TransactionID` bigint(20) NOT NULL,
		  `Message` varchar(500) NOT NULL,
		  `MsgDateTime` datetime NOT NULL,
		  `ReadFlag` tinyint(1) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`ID`,`UserID`,`FromTo`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;"; 
		return $this->createTable($tblName, $sql);
	}

	private function transactionsActive()
	{
		$tblName = TBL_PREFIX."transactions_active";
		$sql = "CREATE TABLE IF NOT EXISTS `".$tblName."` (
		  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
		  `Borrower` varchar(15) NOT NULL,
		  `Lender` varchar(15) NOT NULL,
		  `ItemCopyID` bigint(20) NOT NULL,
		  `ItemID` int(11) NOT NULL,
		  `Status` tinyint(4) NOT NULL COMMENT '1: Requested 2: Lent 10: Returned',
		  `CreatedOn` datetime NOT NULL,
		  `LastChangeOn` datetime NOT NULL,
		  PRIMARY KEY (`ID`),
		  UNIQUE KEY `Borrower` (`Borrower`,`Lender`,`ItemCopyID`),
		  KEY `Borrower_2` (`Borrower`,`Lender`,`ItemCopyID`,`Status`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
		return $this->createTable($tblName, $sql);
	}

	private function transactionsHistory()
	{
		$tblName = TBL_PREFIX."transactions_history";
		$sql = "CREATE TABLE IF NOT EXISTS `".$tblName."` (
			  `TransactionID` bigint(20) NOT NULL,
			  `Status` tinyint(4) NOT NULL,
			  `WhenAction` datetime NOT NULL,
			  UNIQUE KEY `TransactionID` (`TransactionID`,`Status`,`WhenAction`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		return $this->createTable($tblName, $sql);
	}

	private function transactionMessages()
	{
		$tblName = TBL_PREFIX."transaction_messages";
		$sql = "CREATE TABLE IF NOT EXISTS `".$tblName."` (
		  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
		  `TransactionID` bigint(20) DEFAULT NULL,
		  `MessageFrom` varchar(15) DEFAULT NULL,
		  `MessageTo` varchar(15) DEFAULT NULL,
		  `MsgDateTime` datetime DEFAULT NULL,
		  `Message` varchar(500) DEFAULT NULL,
		  PRIMARY KEY (`ID`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
		return $this->createTable($tblName, $sql);
	}

	private function users()
	{
		$tblName = TBL_PREFIX."users";
		$sql = "CREATE TABLE IF NOT EXISTS `".$tblName."` (
		  `UserID` varchar(15) NOT NULL,
		  `FullName` varchar(100) DEFAULT NULL,
		  `Address` varchar(500) NOT NULL,
		  `Locality` varchar(100) NOT NULL,
		  `City` varchar(100) NOT NULL,
		  `State` varchar(100) NOT NULL,
		  `EMail` varchar(100) NOT NULL,
		  `PhoneNumber` varchar(50) DEFAULT NULL,
		  `LendingCount` int(11) NOT NULL DEFAULT '0',
		  `BorrowingCount` int(11) NOT NULL DEFAULT '0',
		  `LenderRanking` decimal(2,1) NOT NULL DEFAULT '0.0',
		  `LenderRankingCount` int(11) NOT NULL DEFAULT '0',
		  `BorrowerRanking` decimal(2,1) DEFAULT '0.0',
		  `BorrowerRankingCount` int(11) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`UserID`),
		  UNIQUE KEY `EMail` (`EMail`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		return $this->createTable($tblName, $sql);
	}

	private function userAccess()
	{
		$tblName = TBL_PREFIX."user_access";
		$sql = "CREATE TABLE IF NOT EXISTS `".$tblName."` (
		  `UserID` varchar(15) NOT NULL,
		  `Username` varchar(20) NOT NULL,
		  `EMail` varchar(100) NOT NULL,
		  `Pwd` varchar(60) NOT NULL,
		  `Active` tinyint(4) NOT NULL DEFAULT '0',
		  `ActivationHash` varchar(40) DEFAULT NULL,
		  `PwdResetHash` varchar(40) DEFAULT NULL,
		  `PwdResetTimestamp` bigint(20) DEFAULT NULL,
		  `RememberMeToken` varchar(64) DEFAULT NULL,
		  `FailedLogins` tinyint(4) NOT NULL DEFAULT '0',
		  `LastFailedLogin` int(10) DEFAULT NULL,
		  `RegistrationDateTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		  `RegistrationIP` varchar(39) DEFAULT '0.0.0.0',
		  PRIMARY KEY (`UserID`),
		  UNIQUE KEY `Username` (`Username`),
		  UNIQUE KEY `EMail` (`EMail`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		return $this->createTable($tblName, $sql);
	}

	public function createAllTables()
	{

		$msg = array($this->authors());
		array_push($msg, $this->bookcopies());
		array_push($msg, $this->books());
		array_push($msg, $this->booksFlat());
		array_push($msg, $this->bookCategory());
		array_push($msg, $this->categories());
		array_push($msg, $this->languages());
		array_push($msg, $this->messages2());
		array_push($msg, $this->transactionsActive());
		array_push($msg, $this->transactionsHistory());
		array_push($msg, $this->transactionMessages());
		array_push($msg, $this->users());
		array_push($msg, $this->userAccess());
		return $msg;
	}

}

?>