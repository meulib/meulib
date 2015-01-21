<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFounders extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('founders', function(Blueprint $table)
		{
			$table->increments('ID');
			$table->char('UserID',15);
			$table->string('Name', 100);
			$table->string('ClaimToFame', 500);
			$table->string('PictureFile',100);
			$table->timestamp('When');

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
		Schema::dropIfExists('founders');
	}

}
