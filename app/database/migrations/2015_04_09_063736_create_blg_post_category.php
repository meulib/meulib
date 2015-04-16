<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlgPostCategory extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('blg_post_category', function(Blueprint $table)
		{
			$table->integer('PostID');
			$table->string('Category',100);
			$table->string('CategorySlug', 100);

			$table->timestamps();

			$table->primary(array('PostID', 'Category'));

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('blg_post_category');
	}

}
