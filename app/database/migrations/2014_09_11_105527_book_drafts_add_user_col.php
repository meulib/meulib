<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BookDraftsAddUserCol extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('book_drafts', function(Blueprint $table)
		{
			$table->string('UserID',15)->after('ID');

			$table->unique(array('UserID', 'Title'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('book_drafts', function(Blueprint $table)
		{
			$table->dropColumn('UserID');

			$table->dropUnique('book_drafts_userid_title_unique');
		});
	}

}
