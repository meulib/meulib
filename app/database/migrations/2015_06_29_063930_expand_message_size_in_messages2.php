<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExpandMessageSizeInMessages2 extends Migration {

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
		$sql = "ALTER TABLE `".$tblPrefix."messages2` MODIFY COLUMN `Message` VARCHAR(5000)";
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
		$sql = "ALTER TABLE `".$tblPrefix."messages2` MODIFY COLUMN `Message` VARCHAR(500)";
		DB::statement($sql);
	}

}
