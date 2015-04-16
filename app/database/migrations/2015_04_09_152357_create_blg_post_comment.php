<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlgPostComment extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('blg_post_comment', function(Blueprint $table)
		{
			$table->increments('CommentID');
			$table->integer('PostID');
			$table->char('UserID',15);
			$table->string('Name', 100);
			$table->string('Email', 100);
			$table->string('WebsiteURL', 100);
			$table->string('Comment', 10000);
			$table->timestamp('WhenCommented');

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
		Schema::dropIfExists('blg_post_comment');
	}

}
