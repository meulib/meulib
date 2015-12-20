<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBorrowingfeeBookcopy extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bookcopies', function(Blueprint $table)
		{
			// how much is the owner charging to lend this book
			$table->smallInteger('BorrowingFee')->unsigned()->default(0)->after('Checked');
			// how much is the owner charging to post this book outside the owner's city
			$table->smallInteger('PostingRate')->unsigned()->default(0)->after('BorrowingFee');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bookcopies', function(Blueprint $table)
		{
			$table->dropColumn('PostingRate');
			$table->dropColumn('BorrowingFee');
		});
	}

}
