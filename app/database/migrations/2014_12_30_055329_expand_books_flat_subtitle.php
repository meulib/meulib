<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExpandBooksFlatSubtitle extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$connSettings = Config::get('database.connections');
		$tblPrefix = '';
		$tblPrefix = $connSettings['mysql']['prefix'];
		$sql = "ALTER TABLE `".$tblPrefix."books_flat` MODIFY COLUMN `SubTitle` VARCHAR(500)";
		DB::statement($sql);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$connSettings = Config::get('database.connections');
		$tblPrefix = '';
		$tblPrefix = $connSettings['mysql']['prefix'];
		$sql = "ALTER TABLE `".$tblPrefix."books_flat` MODIFY COLUMN `SubTitle` VARCHAR(100)";
		DB::statement($sql);
	}

}
