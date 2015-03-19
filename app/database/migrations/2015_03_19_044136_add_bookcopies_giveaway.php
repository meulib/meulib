<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBookcopiesGiveaway extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bookcopies', function(Blueprint $table)
		{
			$table->boolean('ForGiveAway')->default(0)->after('LocationID');
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
			$table->dropColumn('ForGiveAway');
		});
	}

}
