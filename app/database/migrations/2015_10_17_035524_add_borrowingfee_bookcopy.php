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
			$table->smallInteger('BorrowingFee')->unsigned()->default(0)->after('Checked');
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
