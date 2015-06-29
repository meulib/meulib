<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBookcopiesOwnerComment extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bookcopies', function(Blueprint $table)
		{
			// for large libraries - for the owner to know where the 
			// book is located within the library
			$table->string('OwnersComment',5000)->after('ForGiveAway');
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
			$table->dropColumn('OwnersComment');
		});
	}

}
