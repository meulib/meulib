<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookcopies extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Laravel does not support comments for columns in
		// db. Hence using raw mysql statement

		// get table prefix

		$connSettings = Config::get('database.connections');
		$tblPrefix = '';
		$tblPrefix = $connSettings['mysql']['prefix'];
		$sql = "CREATE TABLE `".$tblPrefix."bookcopies` ("
				. "`ID` bigint(20) NOT NULL AUTO_INCREMENT,"
				. "`BookID` bigint(20) NOT NULL,"
				. "`UserID` varchar(15) NOT NULL,"
				. "`Status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0: Not Checked, 1: Available, 2: Lent Out, 3: Overdue, 4: Defaulted, 5: Temporarily Withdrawn by User, 6: Private',"
				. "`Checked` bit(1) NOT NULL DEFAULT b'0',"
				. "`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,"
				. "`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',"
				. "PRIMARY KEY (`ID`),"
				. "UNIQUE KEY `COL 2` (`BookID`,`UserID`)"
				. ") ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";
		//DB::statement($s);
		DB::connection()->getPdo()->exec( $sql );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('bookcopies');
	}

}
