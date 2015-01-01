<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBooksFlatCheckedFlag extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('books_flat', function(Blueprint $table)
		{
			$table->tinyInteger('Checked')->default()->after('SubTitle');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('books_flat', function(Blueprint $table)
		{
			$table->dropColumn('Checked');
		});
	}

}
