<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsArchived extends Migration {

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
		$sql = "CREATE TABLE `".$tblPrefix."transactions_archived` ("
  				."`ID` bigint(20) NOT NULL,"
  				."`Borrower` varchar(15) NOT NULL,"
  				."`Lender` varchar(15) NOT NULL,"
  				."`ItemCopyID` bigint(20) NOT NULL,"
  				."`ItemID` int(11) NOT NULL,"
  				."`Status` tinyint(4) NOT NULL COMMENT '1: Requested 2: Lent 10: Returned',"
  				."`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,"
  				."`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',"
  				."PRIMARY KEY (`ID`)"
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
		Schema::dropIfExists('transactions_archived');
	}

}
