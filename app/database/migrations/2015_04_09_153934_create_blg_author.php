<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlgAuthor extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('blg_author', function(Blueprint $table)
		{
			$table->increments('AuthorID');
			$table->char('UserID',15);
			$table->string('Name', 100);
			$table->string('Email', 100);
			$table->string('WebsiteURL', 100);
			$table->string('SocialMediaLink', 100);
			$table->string('About', 10000);

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
		Schema::dropIfExists('blg_author');
	}

}
