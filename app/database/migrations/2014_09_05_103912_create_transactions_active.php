<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsActive extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$connSettings = Config::get('database.connections');
		$tblPrefix = '';
		$tblPrefix = $connSettings['mysql']['prefix'];
		$sql = "CREATE TABLE `".$tblPrefix."transactions_active` ("
  				."`ID` bigint(20) NOT NULL AUTO_INCREMENT,"
  				."`Borrower` varchar(15) NOT NULL,"
  				."`Lender` varchar(15) NOT NULL,"
  				."`ItemCopyID` bigint(20) NOT NULL,"
  				."`ItemID` int(11) NOT NULL,"
  				."`Status` tinyint(4) NOT NULL COMMENT '1: Requested 2: Lent 10: Returned',"
  				."`CreatedOn` datetime NOT NULL,"
  				."`LastChangeOn` datetime NOT NULL,"
  				."`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,"
  				."`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',"
  				."PRIMARY KEY (`ID`),"
  				."UNIQUE KEY `Borrower-ItemCopy` (`Borrower`,`ItemCopyID`)"
				.") ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;";
		DB::statement($sql);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('transactions_active');
	}

}
