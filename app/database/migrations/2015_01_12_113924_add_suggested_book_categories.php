<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSuggestedBookCategories extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('book_categories', function(Blueprint $table)
		{
			$table->tinyInteger('Suggested')->default(0)->after('CategoryID');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('book_categories', function(Blueprint $table)
		{
			$table->dropColumn('Suggested');
		});
	}

}
