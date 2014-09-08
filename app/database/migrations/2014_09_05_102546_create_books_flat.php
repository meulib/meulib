<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksFlat extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('books_flat', function(Blueprint $table)
		{
			$table->bigInteger('ID');
			$table->string('Title', 100);
			$table->integer('Author1ID');
			$table->string('Author1', 100);
			$table->integer('Author2ID');
			$table->string('Author2', 100);
			$table->integer('Language1ID');
			$table->string('Language1', 50);
			$table->integer('Language2ID');
			$table->string('Language2', 50);
			$table->string('SubTitle', 100);

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('books_flat');
	}

}
