<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionMessages extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transaction_messages', function(Blueprint $table)
		{
			$table->bigIncrements('ID');
			$table->bigInteger('TransactionID');
			$table->string('MessageFrom',15);
			$table->string('MessageTo',15);
			$table->datetime('MsgDateTime');
			$table->string('Message',500);

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
		Schema::dropIfExists('transaction_messages');
	}

}
