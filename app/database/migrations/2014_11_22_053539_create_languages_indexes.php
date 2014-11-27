<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguagesIndexes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('languages', function(Blueprint $table)
		{
			$table->unique('LanguageNative','unique_LanguageNative');
			$table->unique('LanguageEnglish','unique_LanguageEnglish');
		});

		Schema::table('books_flat', function(Blueprint $table)
		{
			$table->index('Language1ID','index_Language1ID');
			$table->index('Language2ID','index_Language2ID');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('locations', function(Blueprint $table)
		{
			$table->dropUnique('unique_LanguageNative');
			$table->dropUnique('unique_LanguageEnglish');
		});

		Schema::table('books_flat', function(Blueprint $table)
		{
			$table->dropIndex('index_Language1ID');
			$table->dropIndex('index_Language2ID');
		});
	}

}
