<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsHistory extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transactions_history', function(Blueprint $table)
		{
			$table->bigIncrements('ID');
			$table->bigInteger('TransactionID');
			$table->tinyInteger('Status');
			$table->datetime('WhenAction');

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
		Schema::dropIfExists('transactions_history');
	}

}
