<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogPost extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('blg_post', function(Blueprint $table)
		{
			$table->increments('PostID');
			$table->string('Title',100);
			$table->string('SubTitle', 100);
			$table->string('Slug', 100)->unique();
			$table->longText('Body');
			$table->string('Excerpt', 1000);
			$table->tinyInteger('Status')->default(0);	// draft 0, published 1
			$table->tinyInteger('Type')->default(1); // static (page) 0, post 1
			$table->integer('AuthorID')->nullable();
			$table->timestamp('WhenPublished');

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
		Schema::dropIfExists('blg_post');
	}

}
