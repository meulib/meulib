<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->char('UserID',15)->primary();
			$table->string('FullName', 100);
			$table->string('Address', 500);
			$table->string('Locality', 100);
			$table->string('City', 100);
			$table->string('State', 100);
			$table->string('EMail', 100)->unique();
			$table->string('PhoneNumber', 50);

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
		Schema::dropIfExists('users');
	}

}
