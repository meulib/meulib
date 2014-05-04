<?php
require_once('DB.Connection.php');
require_once("Exceptions.php");
if (!defined('__ROOT__')) define('__ROOT__', dirname(dirname(__FILE__))); 
require_once(__ROOT__.'/config.php'); 

class Items
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

	// gets books from books_flat. does not bother about
	// bookcopies and location (yet)
	public function getItems()
	{
		$sql = "SELECT ID as BookID, Title, SubTitle, Author1, Author2, Author1ID, Author2ID "
			. "FROM ".TBL_PREFIX."books_flat";
		$result = $this->dbconn->selectAll($sql, null, true);
		return $result;
	}

	public function getItemsByOWner($userid)
	{
		$sql = "SELECT bf.ID as BookID, bc.ID as BookCopyID, bc.Status, Title, SubTitle, "
			. "Author1, Author2, Author1ID, Author2ID "
			. "FROM ".TBL_PREFIX."books_flat bf, ".TBL_PREFIX."bookcopies bc where "
			. "bf.ID = bc.bookID and bc.UserID = :userid";
		$result = $this->dbconn->selectAll($sql, array('userid'=>$userid), true);
		return $result;
	}

	public function getItemDetail($itemid)
	{
		$sql = "SELECT ID as BookID, Title, SubTitle, Author1, Author2, Author1ID, Author2ID, "
			. "Language1ID, Language1, Language2ID, Language2 FROM ".TBL_PREFIX."books_flat"
			. " WHERE ID = :itemid";
		//echo $sql;
		$result = $this->dbconn->selectOneRow($sql, array('itemid'=>$itemid),false);
		return $result;
	}

	public function getItemCopies($itemid)
	{
		// TO DO - CHECK - WHAT IS THIS Status in (1,2,3)?
		// AVOID HARD CODING STATUS VALS
		// USE DEFINED STATUS CONSTANTS
		$sql = "SELECT bc.ID, bc.UserID, u.FullName, u.Locality, u.City, u.LendingCount, u.LenderRanking, u.LenderRankingCount "
			. "FROM ".TBL_PREFIX."bookcopies AS bc INNER JOIN ".TBL_PREFIX."users AS u ON bc.UserID = u.UserID "
			. "where bc.Status in (1,2,3) and BookID = :itemid";
		$result = $this->dbconn->selectAll($sql, array('itemid'=>$itemid),true);
		return $result;
	}

	public function getItemCopyDetails($itemCopyID)
	{
		$sql = "SELECT * FROM ".TBL_PREFIX."bookcopies"
				. " WHERE ID = :itemcopyid";
		$result = $this->dbconn->selectOneRow($sql,array('itemcopyid' => $itemCopyID),true);
		return $result;
	}

}
?>