<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBookcopiesLocation extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bookcopies', function(Blueprint $table)
		{
			$table->integer('LocationID')->default(0)->after('UserID');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bookcopies', function(Blueprint $table)
		{
			$table->dropColumn('LocationID');
		});
	}

}
