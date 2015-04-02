<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUseraccessAdmin extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_access', function(Blueprint $table)
		{
			$table->boolean('IsAdmin')->default(0)->after('RegistrationIP');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_access', function(Blueprint $table)
		{
			$table->dropColumn('IsAdmin');
		});
	}

}
