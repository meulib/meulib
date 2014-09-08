<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAccess extends Migration {

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
		$sql = "CREATE TABLE `".$tblPrefix."user_access` ("
				."`UserID` varchar(15) NOT NULL,"
  				."`Username` varchar(20) NOT NULL,"
  				."`EMail` varchar(100) NOT NULL,"
  				."`Pwd` varchar(60) NOT NULL,"
  				."`Active` tinyint(4) NOT NULL DEFAULT '0',"
  				."`ActivationHash` varchar(40) DEFAULT NULL,"
  				."`PwdResetHash` varchar(40) DEFAULT NULL,"
  				."`PwdResetTimestamp` bigint(20) DEFAULT NULL,"
  				."`RememberMeToken` varchar(64) DEFAULT NULL,"
  				."`FailedLogins` tinyint(4) NOT NULL DEFAULT '0',"
  				."`LastFailedLogin` int(10) DEFAULT NULL,"
  				."`RegistrationIP` varchar(39) DEFAULT '0.0.0.0',"
  				."`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',"
  				."`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',"
  				."PRIMARY KEY (`UserID`),"
  				."UNIQUE KEY `Username` (`Username`),"
  				."UNIQUE KEY `EMail` (`EMail`)"
				.") ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		DB::statement($sql);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('user_access');
	}

}
