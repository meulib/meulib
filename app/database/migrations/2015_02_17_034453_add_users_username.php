<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersUsername extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->string('Username', 20)->default(null)->after('UserID');
		});

		// update data in users table for existing users
		// UPDATE olx_users INNER JOIN olx_user_access ON 
		// olx_users.UserID = olx_user_access.UserID SET 
		// olx_users.Username = olx_user_access.Username
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->dropColumn('Username');
		});
	}

}
