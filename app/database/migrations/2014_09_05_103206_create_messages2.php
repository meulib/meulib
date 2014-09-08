<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessages2 extends Migration {

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
		$sql = "CREATE TABLE `".$tblPrefix."messages2` ("
  				."`ID` bigint(20) NOT NULL AUTO_INCREMENT,"
  				."`MsgID` bigint(20) NOT NULL,"
  				."`UserID` varchar(15) NOT NULL,"
  				."`FromTo` tinyint(1) NOT NULL COMMENT '1 implies from, 0 implies to',"
  				."`OtherUserID` varchar(15) NOT NULL,"
  				."`TransactionID` bigint(20) NOT NULL,"
  				."`Message` varchar(500) NOT NULL,"
  				."`MsgDateTime` datetime NOT NULL,"
  				."`ReadFlag` tinyint(1) NOT NULL DEFAULT '0',"
  				."`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',"
  				."`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',"
  				."PRIMARY KEY (`ID`),"
  				."UNIQUE KEY `MsgUser` (`MsgID`,`UserID`,`FromTo`)"
				.") ENGINE=InnoDB  DEFAULT CHARSET=utf8" ;
		DB::statement($sql);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('messages2');
	}

}
