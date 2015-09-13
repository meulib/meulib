<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionReminder extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('transactions_reminder', function(Blueprint $table)
		{
			$table->bigIncrements('ID');
			$table->bigInteger('TransactionID');
			$table->date('ReminderSentDate');
			$table->tinyInteger('ReminderType');
			$table->tinyInteger('ReminderTo');	// lender 1 or borrower 2

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
		Schema::dropIfExists('transactions_reminder');		//
	}

}
