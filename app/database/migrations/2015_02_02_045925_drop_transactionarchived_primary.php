<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTransactionarchivedPrimary extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('transactions_archived', function(Blueprint $table)
		{
			$table->dropPrimary('PRIMARY');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('transactions_archived', function(Blueprint $table)
		{
			$table->primary('ID');
		});
	}

}
