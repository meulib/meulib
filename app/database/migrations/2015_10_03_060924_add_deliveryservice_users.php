<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeliveryserviceUsers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->boolean('fDeliveryService')->default(0)->after('LibraryName');
			$table->integer('WithinCityDeliveryRate')->default(0)->after('fDeliveryService');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->dropColumn('WithinCityDeliveryRate');
			$table->dropColumn('fDeliveryService');
		});
	}

}
