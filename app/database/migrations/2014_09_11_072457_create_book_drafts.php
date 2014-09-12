<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookDrafts extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('book_drafts', function(Blueprint $table)
		{
			$table->bigIncrements('ID');
			$table->string('Title', 100);
			$table->string('SubTitle', 100)->nullable();
			$table->string('Author1', 100);
			$table->string('Author2', 100)->nullable();
			$table->string('Language1', 50)->default('English');
			$table->string('Language2', 50)->nullable();

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
		Schema::dropIfExists('book_drafts');
	}

}
