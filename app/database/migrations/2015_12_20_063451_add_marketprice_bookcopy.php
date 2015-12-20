<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMarketpriceBookcopy extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bookcopies', function(Blueprint $table)
		{
			// what is the price of this book if purchased in the market
			$table->smallInteger('MarketRate')->unsigned()->default(0)->after('PostingRate');
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
			$table->dropColumn('MarketRate');
		});
	}

}
