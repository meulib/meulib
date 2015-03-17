<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCoverBook extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('books_flat', function(Blueprint $table)
		{
			$table->string('CoverFilename', 100)->default(null)->after('SubTitle');
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
			$table->dropColumn('CoverFilename');
		});
	}

}
