<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserEmailPhoneNullable extends Migration {

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
		DB::statement("ALTER TABLE ".$tblPrefix."users CHANGE EMail EMail VARCHAR(100) NULL");
		DB::statement("ALTER TABLE ".$tblPrefix."users CHANGE PhoneNumber PhoneNumber VARCHAR(50) NULL");
		DB::statement("UPDATE ".$tblPrefix."users SET PhoneNumber = NULL WHERE LENGTH(PhoneNumber)=0");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$connSettings = Config::get('database.connections');
		$tblPrefix = '';
		$tblPrefix = $connSettings['mysql']['prefix'];
		DB::statement("UPDATE ".$tblPrefix."users SET PhoneNumber = '' WHERE PhoneNumber IS NULL");
		DB::statement("ALTER TABLE ".$tblPrefix."users CHANGE PhoneNumber PhoneNumber VARCHAR(50) NOT NULL");
		DB::statement("ALTER TABLE ".$tblPrefix."users CHANGE EMail Email VARCHAR(100) NOT NULL");
		
	}

}
