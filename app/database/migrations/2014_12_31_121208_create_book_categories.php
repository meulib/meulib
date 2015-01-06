<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookCategories extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('book_categories', function(Blueprint $table)
		{
			$table->bigIncrements('ID');
			$table->bigInteger('BookID');
			$table->integer('CategoryID');

			$table->timestamps();

			$table->unique(array('BookID','CategoryID'),'unique_BookID_CategoryID');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('book_categories');
	}

}
